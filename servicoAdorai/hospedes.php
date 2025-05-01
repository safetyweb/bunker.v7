<?php
include 'header.php';
include 'functions.php';

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if ($data === null) {
    $data["errors"]["message"] = "Erro ao decodificar JSON.";
    http_response_code(400);
    // echo json_encode($data);
    exit;
}

// Verifica se a ação está definida no JSON
if (!isset($data['acao'])) {
    $data["errors"]["message"] = "Ação não definida no JSON.";
    http_response_code(400);
    // echo json_encode($data);
    exit;
}


switch ($data['acao']) {
    case 'insere':


        $uuid = $data['uuid'];
        $cod_empresa = $data['cod_empresa'];
        $cod_pedido = $data['cod_pedido'];
        $cod_carrinho = $data['cod_carrinho'];
        $qtd_hospedes = $data['qtd_hospedes'];
        $des_observa = $data['des_observa'];
        $des_comment = $data['des_comment'];
        $arrayhospedes = $data['hospedes'];

        $telefone = $data['telefone'];
        $sqlAdPedido = "SELECT COD_PEDIDO FROM ADORAI_PEDIDO WHERE UUID = '$uuid'";
        $queryadPedido = mysqli_query($conexaotmp, $sqlAdPedido);
        $returnoPedido = mysqli_fetch_assoc($queryadPedido);
        $cod_pedido_sql = $returnoPedido['COD_PEDIDO'];
        if ($des_observa != "" && $des_observa != null) {
            $sqlUpdate = "UPDATE adorai_pedido_opcionais SET
                  DES_OBSERVA = '$des_observa'
                  WHERE COD_PEDIDO = (SELECT COD_PEDIDO FROM ADORAI_PEDIDO WHERE UUID = '$uuid') AND COD_EMPRESA = 274 AND COD_OPCIONAL = 2";
            mysqli_query($conexaotmp, $sqlUpdate);
            $arrayinsert[] .= $sqlUpdate;
        }

        if ($des_comment != "" && $des_comment != null) {
            $sqlUpdate = "UPDATE adorai_pedido SET
        des_comment = '$des_comment'
        WHERE UUID = '$uuid' AND COD_EMPRESA = $cod_empresa AND uuid = '$uuid'";
            mysqli_query($conexaotmp, $sqlUpdate);
        }

        $sql = "SELECT COUNT(*) AS count FROM HOSPEDES_ADORAI WHERE COD_EMPRESA = $cod_empresa AND UUID = '$uuid' AND COD_PEDIDO = $cod_pedido_sql";
        $result = mysqli_query($conexaotmp, $sql);
        $row = mysqli_fetch_assoc($result);
        $exists = $row['count'] > 0;

        foreach ($arrayhospedes as $key => $hospede) {

            $nome = $hospede['nome'];
            $sobrenome = $hospede['sobrenome'];
            $cpf = $hospede['cpf'];
            $email = $hospede['email'];
            $sexo = $hospede['sexo'];
            $nascimento = $hospede['nascimento'];
            $telefoneUp = $hospede['telefone'];
            $cod_hospede = $hospede['cod_hospede'];

            if (!$exists) {
                if ($key == 0) {
                    $sqlUpdatePedido = "UPDATE ADORAI_PEDIDO SET
                NOME = '$nome',
                SOBRENOME = '$sobrenome',
                EMAIL = '$email',
                TELEFONE = '$telefoneUp',
                CPF = '$cpf'
                WHERE UUID = '$uuid' AND COD_PEDIDO = $cod_pedido_sql AND COD_EMPRESA = 274
                ";
                    mysqli_query($conexaotmp, $sqlUpdatePedido);
                }

                $sqlInsert = "INSERT INTO HOSPEDES_ADORAI (
                COD_EMPRESA,
                COD_PEDIDO,
                UUID,
                NOM_HOSP,
                SOBRENOM_HOSP,
                NUM_CGCECPF,
                DES_EMAILUS,
                DES_SEXOPES,
                DAT_NASCIME,
                NUM_TELEFONE,
                COD_USUCADA,
                DAT_CADASTR
                ) VALUES (
                $cod_empresa,
                $cod_pedido_sql,
                '$uuid',
                '$nome',
                '$sobrenome',
                '$cpf',
                '$email',
                $sexo,
                '$nascimento',
                '$telefoneUp',
                9999,
                NOW()
            )";

                $sqlquery = mysqli_query($conexaotmp, $sqlInsert);
                $arrayinsert[] .= $sqlInsert;
            } else {
                if ($key == 0) {
                    $sqlDeleteHospede = "DELETE FROM HOSPEDES_ADORAI 
                WHERE UUID = '$uuid' AND COD_PEDIDO = $cod_pedido_sql AND COD_EMPRESA = 274
                ";
                    $sqlDeleteQuery = mysqli_query($conexaotmp, $sqlDeleteHospede);
                    $arrayinsert[] .= $sqlDeleteQuery;
                }

                if ($key == 0) {
                    $sqlUpdatePedido = "UPDATE ADORAI_PEDIDO SET
                NOME = '$nome',
                SOBRENOME = '$sobrenome',
                EMAIL = '$email',
                TELEFONE = '$telefoneUp',
                CPF = '$cpf'
                WHERE UUID = '$uuid' AND COD_PEDIDO = $cod_pedido_sql AND COD_EMPRESA = 274
                ";
                    mysqli_query($conexaotmp, $sqlUpdatePedido);
                }
                /*************************************
                 * RE-INSERINDO O QUE FOI APAGADO
                 * **********************************/
                $sqlInsertupdate = "INSERT INTO HOSPEDES_ADORAI (
                COD_EMPRESA,
                COD_PEDIDO,
                UUID,
                NOM_HOSP,
                SOBRENOM_HOSP,
                NUM_CGCECPF,
                DES_EMAILUS,
                DES_SEXOPES,
                DAT_NASCIME,
                NUM_TELEFONE,
                COD_USUCADA,
                DAT_CADASTR
                ) VALUES (
                $cod_empresa,
                $cod_pedido_sql,
                '$uuid',
                '$nome',
                '$sobrenome',
                '$cpf',
                '$email',
                $sexo,
                '$nascimento',
                '$telefoneUp',
                9999,
                NOW()
            )";

                $sqlqueryupd = mysqli_query($conexaotmp, $sqlInsertupdate);
                $arrayinsert[] .= $sqlqueryupd;
            }
        }
        $arr[] = $key;
        echo json_encode($sqlUpdatePedido);


        break;

    case 'lista':

        $uuid = $data['uuid'];
        $COD_EMPRESA = $data['COD_EMPRESA'];
        $qtd_hospedes = $data['qtd_hospedes'];

        $sql = "SELECT 
    AP.*,
    API.COD_PROPRIEDADE,
    API.COD_CHALE,
    API.DAT_INICIAL,
    API.DAT_FINAL,
    AC.NOM_QUARTO,
    ASP.ABV_STATUSPAG,
    AF.DES_FORMAPAG,
    AF.ABV_FORMAPAG,
    AF.COD_FORMAPAG,
    API.VALOR AS VAL_CARRINHO,
    UNV.COD_EXTERNO,
    UNV.NOM_FANTASI,
    AC.DES_IMAGEM,
    AP.VALOR_COBRADO,
    ADP.DES_CONTRATO,
    CP.TIP_DESCONTO,
    CP.VAL_DESCONTO,
    (
			SELECT SUM(C.val_credito)
			FROM caixa AS c 
			INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
			INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
			WHERE p.COD_EMPRESA = 274 
			AND p.COD_PEDIDO = AP.COD_PEDIDO
			AND c.cod_contrat = AP.COD_PEDIDO
			AND TC.TIP_OPERACAO = 'C'
			) AS TOTAL_PAGO
    FROM adorai_pedido AS AP 
    INNER JOIN ADORAI_PEDIDO_ITEMS AS API ON API.COD_PEDIDO = AP.COD_PEDIDO
    INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = API.COD_PROPRIEDADE
    INNER JOIN adorai_statuspag AS ASP ON ASP.COD_STATUSPAG = AP.COD_STATUSPAG
    LEFT JOIN adorai_chales AS AC ON AC.COD_EXTERNO = API.COD_CHALE AND AC.COD_EXCLUSA = 0
    LEFT JOIN adorai_propriedades as ADP ON ADP.COD_HOTEL = API.COD_PROPRIEDADE
    LEFT JOIN adorai_formapag AS AF ON AF.COD_FORMAPAG = AP.COD_FORMAPAG
    LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
    WHERE AP.COD_EMPRESA= 274 AND AP.UUID='$uuid'";
        $rs = mysqli_query($conexaotmp, $sql);
        $arrayhospedes = array();

        $arrayhospedes['pedidos'] = mysqli_fetch_assoc($rs);


        // HOSPEDES ADORAI
        $sql3 = "SELECT * FROM HOSPEDES_ADORAI WHERE COD_EMPRESA = 274 AND UUID = '$uuid'";
        $response3 = mysqli_query($conexaotmp, $sql3);

        if (mysqli_num_rows($response3) > 0) {
            $arrayhospedes['hospede_adorai'] = [];
            while ($hospedes = mysqli_fetch_assoc($response3)) {
                $arrayhospedes['hospede_adorai'][] = $hospedes;
            }
        }


        // OPICIONAIS ADORAI
        $sql2 = "SELECT 
    OA.COD_OPCIONAL, 
    OA.VAL_VALOR,
    OA.ABV_OPCIONAL,
    OA.LOG_CORTESIA,
    ACP.VALOR,
    ACP.QTD_OPCIONAL,
    ACP.DES_OBSERVA,
    OA.TIP_CALCULO
    FROM adorai_pedido_opcionais AS ACP
    INNER JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL AND OA.COD_EXCLUSA IS NULL
    INNER JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = ACP.COD_PEDIDO
    WHERE AP.COD_EMPRESA = 274 AND AP.UUID = '$uuid' AND ACP.COD_EXCLUSA IS NULL";

        $response2 = mysqli_query($conexaotmp, $sql2);

        while ($item = mysqli_fetch_assoc($response2)) {
            $arrayhospedes['opcionais'][] = $item;
        }

        echo json_encode($arrayhospedes);

        break;

    default:
        $data["errors"]["message"] = "Ação inválida no JSON.";
        http_response_code(400);
        echo json_encode($data);
        exit;
}
