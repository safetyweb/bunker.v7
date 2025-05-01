<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
$cod_propriedade = fnLimpacampo($_REQUEST['COD_PROPRIEDADE']);
$cod_chale = fnLimpacampo($_REQUEST['COD_CHALE']);
$cod_item = fnLimpacampo($_REQUEST['COD_ITEM']);
$cod_carrinho = fnLimpacampo($_REQUEST['COD_CARRINHO']);
$des_owner = fnLimpacampo($_REQUEST['DES_OWNER']);
$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
$opcao = fnLimpacampo($_GET['opcao']);
$filtro_data = $_POST['FILTRO_DATA'];
$cod_statuspag = $_POST['COD_STATUSPAG'];
$cod_formapag = $_POST['COD_FORMAPAG'];
$log_statusreserva = $_POST['LOG_STATUSRESERVA'];
$cod_pedido = fnLimpacampoZero($_GET['idc']);

// fnEscreve($cod_empresa);

$andStatusReserva = "";
if ($log_statusreserva != "") {
    $andStatusReserva = "AND AP.LOG_STATUSRESERVA = '$log_statusreserva'";
}

if ($cod_propriedade == "" or $cod_propriedade == 9999) {
    $and_propriedade = " ";
} else {
    $and_propriedade = "AND AI.COD_PROPRIEDADE = $cod_propriedade";
}
if ($cod_chale != "") {
    $and_chale = "AND AI.COD_CHALE = $cod_chale";
} else {
    $and_chale = " ";
}

if ($filtro_data == "ALTERACAO") {
    $andDat = "AND AI.DAT_ALTERAC >= '$dat_ini 00:00:00'
    AND AI.DAT_ALTERAC >= '$dat_fim 23:59:59'";
} else if ($filtro_data == "DEFAULT") {
    $andDat = " AND AI.DAT_INICIAL >= '$dat_ini 00:00:00'
    AND AI.DAT_FINAL <= '$dat_fim 23:59:59'";
} else {
    $andDat = "AND AI.DAT_CADASTR >= '$dat_ini 00:00:00'
    AND AI.DAT_CADASTR <= '$dat_fim 23:59:59'";
}

if ($cod_statuspag != "") {
    $andStatusPag = "AND AP.COD_STATUSPAG = $cod_statuspag";
} else {
    $andStatusPag = "";
}

if ($cod_formapag != "") {
    $andFormaPag = "AND AP.COD_FORMAPAG = $cod_formapag";
} else {
    $andFormaPag = "";
}

if ($des_owner != "9999") {
    $andowner = "AND AP.DES_OWNER = '$des_owner'";
} else {
    $andowner = "";
}

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "
    SELECT DISTINCT
    AP.COD_PEDIDO,
    AP.ID_RESERVA, 
    AP.DES_OWNER,
    AP.NOME,
    AP.SOBRENOME,
    AP.TELEFONE,
    AP.DAT_CADASTR,
    AI.DAT_INICIAL,
    AI.DAT_FINAL,
    AP.VALOR,
    AP.VALOR_PEDIDO,
    AP.VALOR_OPCIONAIS,
    UNV.NOM_FANTASI,
    AC.NOM_QUARTO,
    AI.COD_PROPRIEDADE,
    AST.ABV_STATUSPAG,
    AP.COD_STATUSPAG,
    AP.LOG_STATUSRESERVA,
    FP.ABV_FORMAPAG
    FROM adorai_pedido AS AP
    LEFT JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
    LEFT JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
    LEFT JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
    LEFT JOIN adorai_statuspag AS AST ON AST.COD_STATUSPAG = AP.COD_STATUSPAG
    LEFT JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
    WHERE AP.COD_EMPRESA = $cod_empresa
    $andDat
    $andStatusPag
    $andStatusReserva
    $andFormaPag
    $and_propriedade
    $and_chale
    $andowner
    GROUP BY AP.COD_PEDIDO
    ";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['TELEFONE'] = fnmasktelefone($row['TELEFONE']);
            $row['DAT_INICIAL'] = fnDataShort($row['DAT_INICIAL']);
            $row['DAT_CADASTR'] = fnDataShort($row['DAT_CADASTR']);
            $row['DAT_FINAL'] = fnDataShort($row['DAT_FINAL']);
            $row['VALOR'] = fnValor($row['VALOR'], 2);

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');
        }
        fclose($arquivo);

        break;

    case 'CONFIR':

        $sql = "UPDATE ADORAI_PEDIDO SET 
            LOG_CONCLUIDO = 'S',
            COD_USUCADA = $cod_usucada,
            DAT_CONCLUSAO = NOW()
            WHERE COD_PEDIDO = $cod_pedido";

        mysqli_query(connTemp(274, ''), $sql);

        $response = ['success' => true];
        echo json_encode($response);
        break;
}
