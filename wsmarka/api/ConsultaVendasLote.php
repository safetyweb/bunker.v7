<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';

// Validação do método HTTP
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(400);
    echo '{"errors": [{"message": "O método para captura deve ser POST", "coderro": "400"}]}';
    exit();
}

$passmarka = getallheaders();
if (!array_key_exists('authorizationCode', $passmarka)) {
    http_response_code(400);
    echo '{"errors": [{"message": "Informe uma chave de acesso válida!", "coderro": "400"}]}';
    exit();
}

$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
$arraydadosaut = explode(';', $autoriz);

// Validação do usuário
$admconn = $connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $arraydadosaut[0] . "', '" . fnEncode($arraydadosaut[1]) . "','','','" . $arraydadosaut[4] . "','','')";
$buscauser = mysqli_query($admconn, $sql);
if (empty($buscauser->num_rows)) {
    http_response_code(400);
    echo '{"errors": [{"message": "Usuário ou senha inválido!", "coderro": "400"}]}';
    exit();
}
$user = mysqli_fetch_assoc($buscauser);

// Conexão temporária com a empresa
$conexaotmp = connTemp($arraydadosaut[4], '');
if (!array_key_exists('4', $arraydadosaut)) {
    http_response_code(400);
    echo '{"errors": [{"message": "Informe uma chave de acesso válida!", "coderro": "400"}]}';
    exit();
}

$Capturajson = file_get_contents("php://input");
$arrayjson = json_decode($Capturajson, true);

// Função para validar formato de data
function fnvalidateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$andwhere = "";
if (!empty($arrayjson['codVenda'])) {
    $andwhere .= "V.COD_VENDA in ({$arrayjson['codVenda']})";
} else {
    if (!empty($arrayjson['dataHoraInicial']) || !empty($arrayjson['dataHoraFinal'])) {
        $datahoraInicialValida = fnvalidateDate($arrayjson['dataHoraInicial']);
        $datahoraFinalValida   = fnvalidateDate($arrayjson['dataHoraFinal']);
        if ($datahoraInicialValida != 1 || $datahoraFinalValida != 1) {
            http_response_code(400);
            echo json_encode(["errors" => [
                "message" => "Formato date/time inválido! AAAA-MM-DD HH:MM:SS",
                "coderro" => "400"
            ]], JSON_PRETTY_PRINT);
            exit();
        }
    }
    if (empty($arrayjson['dataHoraFinal']) || empty($arrayjson['dataHoraInicial'])) {
        http_response_code(400);
        echo json_encode(["errors" => [
            "message" => "É necessário informar período!",
            "coderro" => "400"
        ]], JSON_PRETTY_PRINT);
        exit();
    } else {
        $andwhere .= "V.dat_cadastr_ws BETWEEN '{$arrayjson['dataHoraInicial']}' AND '{$arrayjson['dataHoraFinal']}'";
    }
}

if ($arrayjson['quantidadeLista'] > 100) {
    http_response_code(400);
    echo json_encode(["errors" => [
        "message" => "Limite máximo da lista é 100",
        "coderro" => "400"
    ]], JSON_PRETTY_PRINT);
    exit();
}

$entrada = new DateTime($arrayjson['dataHoraInicial']);
$saida   = new DateTime($arrayjson['dataHoraFinal']);
$intervalo = $entrada->diff($saida);

if ($intervalo->days > 10) {
    echo json_encode(["errors" => [
        "message" => "Intervalo de consulta não pode ultrapassar o período de 10 dias",
        "coderro" => "400"
    ]], JSON_PRETTY_PRINT);
    exit();
}

$hoje = new DateTime();
$intervalo2anos = $hoje->diff($saida);

// Paginação padrão
$proximaPagina = empty($arrayjson['proximaPagina']) || $arrayjson['proximaPagina'] == '0'
    ? 1 : $arrayjson['proximaPagina'];
$quantidadeLista = empty($arrayjson['quantidadeLista']) || $arrayjson['quantidadeLista'] <= 0
    ? 50 : $arrayjson['quantidadeLista'];

if (!empty($arrayjson['cupom'])) {
    $andwhere .= " AND V.COD_CUPOM in ({$arrayjson['cupom']})";
}
if (!empty($arrayjson['codCliente'])) {
    $andwhere .= " AND V.COD_CLIENTE in ({$arrayjson['codCliente']})";
}
if (!empty($arrayjson['cpf'])) {
    $andwhere .= " AND CL.NUM_CGCECPF in ({$arrayjson['cpf']})";
}
if (!empty($arrayjson['vendasFid']) || $arrayjson['vendasFid'] == 'S') {
    $andwhere .= " AND CL.NUM_CARTAO > 0 ";
} elseif ($arrayjson['vendasFid'] == 'N') {
    $andwhere .= " AND CL.NUM_CARTAO = 0";
}

// Escolha da query com base na diferença entre a data atual e a data final
if ($intervalo2anos->y < 2) {
    $SQLQTDCLIENTE = "SELECT count(COD_VENDA) QTD_LISTA FROM vendas V
                      WHERE V.cod_empresa={$arraydadosaut[4]} AND $andwhere";
} else {
    $SQLQTDCLIENTE = "SELECT count(COD_VENDA) QTD_LISTA FROM vendas_bkp V
                      WHERE V.cod_empresa={$arraydadosaut[4]} AND $andwhere";
}

$rwQTDCLIENTE = mysqli_fetch_assoc(mysqli_query($conexaotmp, $SQLQTDCLIENTE));
$inicio = ($proximaPagina * $quantidadeLista) - $quantidadeLista;

if ($intervalo2anos->y < 2) {
    $sqlresultado = "SELECT 
                        U.NOM_FANTASI,
                        V.COD_UNIVEND,
                        V.COD_VENDA,
                        CL.NUM_CGCECPF as CPF,
                        CL.DAT_NASCIME AS DATA_NASCIMENTO,
                        V.COD_CLIENTE,
                        /* Se os dados vierem somente de clientes_exc, define os campos como NULL */
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE COALESCE(CL.NOM_CLIENTE, CLE.NOM_CLIENTE)
                        END AS NOM_CLIENTE,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE V.DAT_CADASTR_WS
                        END AS DAT_CADASTR_WS,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE (CASE WHEN COALESCE(CL.NUM_CELULAR, CLE.NUM_CELULAR) = '' 
                                THEN NULL ELSE COALESCE(CL.NUM_CELULAR, CLE.NUM_CELULAR) END)
                        END AS NUM_CELULAR,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE (CASE WHEN COALESCE(CL.DES_EMAILUS, CLE.DES_EMAILUS) = '' 
                                THEN NULL ELSE COALESCE(CL.DES_EMAILUS, CLE.DES_EMAILUS) END)
                        END AS DES_EMAILUS,
                        V.COD_AVULSO,
                        FP.DES_FORMAPA,
                        V.VAL_TOTPRODU,
                        V.VAL_RESGATE,
                        V.VAL_DESCONTO,
                        V.VAL_TOTVENDA,
                        TRUNCATE((SELECT SUM(val_credito)
                                    FROM creditosdebitos
                                    WHERE tip_credito='C' 
                                      AND cod_venda=V.COD_VENDA 
                                      AND cod_statuscred !=6),2) AS VAL_CREDITO,
                        (SELECT MIN(dat_expira)
                         FROM creditosdebitos
                         WHERE tip_credito='C'
                           AND cod_venda=V.COD_VENDA 
                           AND cod_statuscred !=6) AS DAT_EXPIRA,
                        V.COD_VENDAPDV,
                        V.COD_CUPOM,
                        V.COD_VENDEDOR,
                        V.COD_ATENDENTE,
                        V.COD_ORCAMENTO
                    FROM vendas V
                    INNER JOIN unidadevenda U ON U.COD_UNIVEND = V.COD_UNIVEND
                    LEFT JOIN formapagamento FP ON FP.COD_FORMAPA = V.COD_FORMAPA
                    LEFT JOIN clientes CL ON CL.COD_CLIENTE = V.COD_CLIENTE
                    LEFT JOIN clientes_exc CLE ON CLE.COD_CLIENTE = V.COD_CLIENTE
                    WHERE V.cod_empresa = {$arraydadosaut[4]} 
                      AND $andwhere 
                    LIMIT $inicio, $quantidadeLista;";
} else {
    $sqlresultado = "SELECT 
                        U.NOM_FANTASI,
                        V.COD_UNIVEND,
                        V.COD_VENDA,
                        CL.NUM_CGCECPF as CPF,
                        CL.DAT_NASCIME AS DATA_NASCIMENTO,
                        V.COD_CLIENTE,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE COALESCE(CL.NOM_CLIENTE, CLE.NOM_CLIENTE)
                        END AS NOM_CLIENTE,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE V.DAT_CADASTR_WS
                        END AS DAT_CADASTR_WS,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE (CASE WHEN COALESCE(CL.NUM_CELULAR, CLE.NUM_CELULAR) = '' 
                                THEN NULL ELSE COALESCE(CL.NUM_CELULAR, CLE.NUM_CELULAR) END)
                        END AS NUM_CELULAR,
                        CASE 
                            WHEN CL.COD_CLIENTE IS NULL AND CLE.COD_CLIENTE IS NOT NULL 
                            THEN NULL 
                            ELSE (CASE WHEN COALESCE(CL.DES_EMAILUS, CLE.DES_EMAILUS) = '' 
                                THEN NULL ELSE COALESCE(CL.DES_EMAILUS, CLE.DES_EMAILUS) END)
                        END AS DES_EMAILUS,
                        V.COD_AVULSO,
                        FP.DES_FORMAPA,
                        truncate(V.VAL_TOTPRODU,2) VAL_TOTPRODU,
                        truncate(V.VAL_RESGATE,2) VAL_RESGATE,
                        truncate(V.VAL_DESCONTO,2) VAL_DESCONTO,
                        truncate(V.VAL_TOTVENDA,2) VAL_TOTVENDA,
                        TRUNCATE((SELECT SUM(val_credito)
                                    FROM creditosdebitos_bkp
                                    WHERE tip_credito='C' 
                                      AND cod_venda=V.COD_VENDA 
                                      AND cod_statuscred !=6),2) AS VAL_CREDITO,
                        (SELECT MIN(dat_expira)
                         FROM creditosdebitos_bkp
                         WHERE tip_credito='C'
                           AND cod_venda=V.COD_VENDA 
                           AND cod_statuscred !=6) AS DAT_EXPIRA,
                        V.COD_VENDAPDV,
                        V.COD_CUPOM,
                        V.COD_VENDEDOR,
                        V.COD_ATENDENTE,
                        V.COD_ORCAMENTO
                    FROM vendas_bkp V
                    INNER JOIN unidadevenda U ON U.COD_UNIVEND = V.COD_UNIVEND
                    LEFT JOIN formapagamento FP ON FP.COD_FORMAPA = V.COD_FORMAPA
                    LEFT JOIN clientes CL ON CL.COD_CLIENTE = V.COD_CLIENTE
                    LEFT JOIN clientes_exc CLE ON CLE.COD_CLIENTE = V.COD_CLIENTE
                    WHERE V.cod_empresa = {$arraydadosaut[4]} 
                      AND $andwhere 
                    LIMIT $inicio, $quantidadeLista";
}

$rsresultado = mysqli_fetch_all(mysqli_query($conexaotmp, $sqlresultado), MYSQLI_ASSOC);

$dadoscliente = [];
foreach ($rsresultado as $cabecalho) {

    if ($intervalo2anos->y < 2) {
        $itm = "SELECT  
                    C.DES_CATEGOR,
                    SC.DES_SUBCATE,
                    P.COD_PRODUTO,
                    P.COD_EXTERNO,
                    P.DES_PRODUTO,
                    P.EAN,
                    F.NOM_FORNECEDOR,
                    ITM.QTD_PRODUTO,
                    truncate(ITM.VAL_UNITARIO ,2)  AS VAL_UNITARIO,
                    truncate(ITM.VAL_TOTITEM ,2)  AS VAL_TOTITEM,
                    truncate(ITM.VAL_DESCONTO ,2)  AS VAL_DESCONTO,
                    truncate(ITM.VAL_LIQUIDO,2)  AS VAL_LIQUIDO,
                    ITM.COD_VENDA,
                    P1.des_parametro AS des_parametro1,
                    P2.des_parametro AS des_parametro2,
                    P3.des_parametro AS des_parametro3,
                    P4.des_parametro AS des_parametro4,
                    P5.des_parametro AS des_parametro5,
                    P6.des_parametro AS des_parametro6,
                    P7.des_parametro AS des_parametro7,
                    P8.des_parametro AS des_parametro8,
                    P9.des_parametro AS des_parametro9,
                    P10.des_parametro AS des_parametro10,
                    P11.des_parametro AS des_parametro11,
                    P12.des_parametro AS des_parametro12,
                    P13.des_parametro AS des_parametro13
                FROM itemvenda ITM 
                INNER JOIN produtocliente P ON P.COD_PRODUTO = ITM.COD_PRODUTO
                LEFT JOIN categoria C ON C.COD_CATEGOR = P.COD_CATEGOR
                LEFT JOIN subcategoria SC ON SC.COD_SUBCATE = P.COD_SUBCATE
                LEFT JOIN fornecedormrka F ON F.COD_FORNECEDOR = P.COD_FORNECEDOR
                LEFT JOIN parametro1 P1 ON P1.cod_parametro = ITM.DES_PARAM1
                LEFT JOIN parametro2 P2 ON P2.cod_parametro = ITM.DES_PARAM2
                LEFT JOIN parametro3 P3 ON P3.cod_parametro = ITM.DES_PARAM3
                LEFT JOIN parametro4 P4 ON P4.cod_parametro = ITM.DES_PARAM4
                LEFT JOIN parametro5 P5 ON P5.cod_parametro = ITM.DES_PARAM5
                LEFT JOIN parametro6 P6 ON P6.cod_parametro = ITM.DES_PARAM6
                LEFT JOIN parametro7 P7 ON P7.cod_parametro = ITM.DES_PARAM7
                LEFT JOIN parametro8 P8 ON P8.cod_parametro = ITM.DES_PARAM8
                LEFT JOIN parametro9 P9 ON P9.cod_parametro = ITM.DES_PARAM9
                LEFT JOIN parametro10 P10 ON P10.cod_parametro = ITM.DES_PARAM10
                LEFT JOIN parametro11 P11 ON P11.cod_parametro = ITM.DES_PARAM11
                LEFT JOIN parametro12 P12 ON P12.cod_parametro = ITM.DES_PARAM12
                LEFT JOIN parametro13 P13 ON P13.cod_parametro = ITM.DES_PARAM13 
                WHERE ITM.COD_VENDA = {$cabecalho['COD_VENDA']}";
    } else {
        $itm = "SELECT  
                    C.DES_CATEGOR,
                    SC.DES_SUBCATE,
                    P.COD_PRODUTO,
                    P.COD_EXTERNO,
                    P.DES_PRODUTO,
                    P.EAN,
                    F.NOM_FORNECEDOR,
                    ITM.QTD_PRODUTO,
                    truncate(ITM.VAL_UNITARIO,2)  AS VAL_UNITARIO,
                    truncate(ITM.VAL_TOTITEM ,2)  AS VAL_TOTITEM,
                    truncate(ITM.VAL_DESCONTO,2)  AS VAL_DESCONTO,
                    truncate(ITM.VAL_LIQUIDO ,2)  AS VAL_LIQUIDO,
                    ITM.COD_VENDA,
                    P1.des_parametro AS des_parametro1,
                    P2.des_parametro AS des_parametro2,
                    P3.des_parametro AS des_parametro3,
                    P4.des_parametro AS des_parametro4,
                    P5.des_parametro AS des_parametro5,
                    P6.des_parametro AS des_parametro6,
                    P7.des_parametro AS des_parametro7,
                    P8.des_parametro AS des_parametro8,
                    P9.des_parametro AS des_parametro9,
                    P10.des_parametro AS des_parametro10,
                    P11.des_parametro AS des_parametro11,
                    P12.des_parametro AS des_parametro12,
                    P13.des_parametro AS des_parametro13
                FROM itemvenda_bkp ITM 
                INNER JOIN produtocliente P ON P.COD_PRODUTO = ITM.COD_PRODUTO
                LEFT JOIN categoria C ON C.COD_CATEGOR = P.COD_CATEGOR
                LEFT JOIN subcategoria SC ON SC.COD_SUBCATE = P.COD_SUBCATE
                LEFT JOIN fornecedormrka F ON F.COD_FORNECEDOR = P.COD_FORNECEDOR
                LEFT JOIN parametro1 P1 ON P1.cod_parametro = ITM.DES_PARAM1
                LEFT JOIN parametro2 P2 ON P2.cod_parametro = ITM.DES_PARAM2
                LEFT JOIN parametro3 P3 ON P3.cod_parametro = ITM.DES_PARAM3
                LEFT JOIN parametro4 P4 ON P4.cod_parametro = ITM.DES_PARAM4
                LEFT JOIN parametro5 P5 ON P5.cod_parametro = ITM.DES_PARAM5
                LEFT JOIN parametro6 P6 ON P6.cod_parametro = ITM.DES_PARAM6
                LEFT JOIN parametro7 P7 ON P7.cod_parametro = ITM.DES_PARAM7
                LEFT JOIN parametro8 P8 ON P8.cod_parametro = ITM.DES_PARAM8
                LEFT JOIN parametro9 P9 ON P9.cod_parametro = ITM.DES_PARAM9
                LEFT JOIN parametro10 P10 ON P10.cod_parametro = ITM.DES_PARAM10
                LEFT JOIN parametro11 P11 ON P11.cod_parametro = ITM.DES_PARAM11
                LEFT JOIN parametro12 P12 ON P12.cod_parametro = ITM.DES_PARAM12
                LEFT JOIN parametro13 P13 ON P13.cod_parametro = ITM.DES_PARAM13 
                WHERE ITM.COD_VENDA = {$cabecalho['COD_VENDA']}";
    }

    $rsitm = mysqli_fetch_all(mysqli_query($conexaotmp, $itm), MYSQLI_ASSOC);
    $itmarr = [];
    foreach ($rsitm as $cabecalhoitm) {
        $itmarr[] = array(
            'COD_PRODUTO'     => $cabecalhoitm['COD_PRODUTO'],
            'COD_EXTERNO'     => $cabecalhoitm['COD_EXTERNO'],
            'DES_PRODUTO'     => $cabecalhoitm['DES_PRODUTO'],
            'EAN'             => $cabecalhoitm['EAN'],
            'DES_CATEGOR'     => $cabecalhoitm['DES_CATEGOR'],
            'DES_SUBCATE'     => $cabecalhoitm['DES_SUBCATE'],
            'NOM_FORNECEDOR'  => $cabecalhoitm['NOM_FORNECEDOR'],
            'QTD_PRODUTO'     => $cabecalhoitm['QTD_PRODUTO'],
            'VAL_UNITARIO'    => $cabecalhoitm['VAL_UNITARIO'],
            'VAL_TOTITEM'     => $cabecalhoitm['VAL_TOTITEM'],
            'VAL_DESCONTO'    => $cabecalhoitm['VAL_DESCONTO'],
            'VAL_LIQUIDO'     => $cabecalhoitm['VAL_LIQUIDO'],
            'des_parametro1'  => $cabecalhoitm['des_parametro1'],
            'des_parametro2'  => $cabecalhoitm['des_parametro2'],
            'des_parametro3'  => $cabecalhoitm['des_parametro3'],
            'des_parametro4'  => $cabecalhoitm['des_parametro4'],
            'des_parametro5'  => $cabecalhoitm['des_parametro5'],
            'des_parametro6'  => $cabecalhoitm['des_parametro6'],
            'des_parametro7'  => $cabecalhoitm['des_parametro7'],
            'des_parametro8'  => $cabecalhoitm['des_parametro8'],
            'des_parametro9'  => $cabecalhoitm['des_parametro9'],
            'des_parametro10' => $cabecalhoitm['des_parametro10'],
            'des_parametro11' => $cabecalhoitm['des_parametro11'],
            'des_parametro12' => $cabecalhoitm['des_parametro12'],
            'des_parametro13' => $cabecalhoitm['des_parametro13']
        );
    }

    $dadoscliente[] = array(
        'NOM_FANTASI'     => $cabecalho['NOM_FANTASI'],
        'COD_UNIVEND'     => $cabecalho['COD_UNIVEND'],
        'COD_VENDA'       => $cabecalho['COD_VENDA'],
        'CPF'             => $cabecalho['CPF'],
        'DATA_NASCIMENTO' => $cabecalho['DATA_NASCIMENTO'],
        'COD_CLIENTE'     => $cabecalho['COD_CLIENTE'],
        'NOM_CLIENTE'     => $cabecalho['NOM_CLIENTE'],
        'DAT_CADASTR_WS'  => $cabecalho['DAT_CADASTR_WS'],
        'NUM_CELULAR'     => $cabecalho['NUM_CELULAR'],
        'DES_EMAIL'       => $cabecalho['DES_EMAILUS'],
        'COD_AVULSO'      => $cabecalho['COD_AVULSO'],
        'DES_FORMAPA'     => $cabecalho['DES_FORMAPA'],
        'VAL_TOTPRODU'    => $cabecalho['VAL_TOTPRODU'],
        'VAL_RESGATE'     => $cabecalho['VAL_RESGATE'],
        'VAL_DESCONTO'    => $cabecalho['VAL_DESCONTO'],
        'VAL_TOTVENDA'    => $cabecalho['VAL_TOTVENDA'],
        'VAL_CREDITO'     => $cabecalho['VAL_CREDITO'],
        'DAT_EXPIRA'      => $cabecalho['DAT_EXPIRA'],
        'COD_VENDAPDV'    => $cabecalho['COD_VENDAPDV'],
        'COD_CUPOM'       => $cabecalho['COD_CUPOM'],
        'COD_VENDEDOR'    => $cabecalho['COD_VENDEDOR'],
        'COD_ATENDENTE'   => $cabecalho['COD_ATENDENTE'],
        'COD_ORCAMENTO'   => $cabecalho['COD_ORCAMENTO'],
        'ITENS'           => $itmarr
    );
}

$total_paginas = ceil($rwQTDCLIENTE['QTD_LISTA'] / $quantidadeLista);
$msg = ($total_paginas > $proximaPagina) ? "TRUE" : "FALSE";

$return = array(
    'msgerro'                 => 'OK',
    'coderro'                 => '200',
    'quantidaregistrototal'   => $rwQTDCLIENTE['QTD_LISTA'],
    'quantidaderegistrolista' => $quantidadeLista,
    'paginaatual'             => $proximaPagina,
    'paginacao'               => $msg,
    'Vendas'                  => $dadoscliente
);

echo json_encode($return, JSON_NUMERIC_CHECK);
