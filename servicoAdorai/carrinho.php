<?php
include 'header.php';
include 'functions.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (@$_REQUEST["acao"] == "") {
    $data["errors"]["message"] = "Parâmetro 'acao' não definido!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}
$acao = fnLimpaCampo(@$_REQUEST["acao"]);

if ($acao == "insere") {

    $uuid = fnLimpaCampo(@$_REQUEST["UUID"]);

    $cod_item = +fnLimpaCampoZero(@$_REQUEST["COD_ITEM"]);
    $cod_propriedade = +fnLimpaCampoZero(@$_REQUEST["COD_PROPRIEDADE"]);
    $cod_chale = +fnLimpaCampoZero(@$_REQUEST["COD_CHALE"]);
    $telefone = +fnLimpaCampo(@$_REQUEST["TELEFONE"]);
    $valor = +fnLimpaCampoZero(@$_REQUEST["VALOR"]);
    $dat_inicial = formatarData(fnLimpaCampo(@$_REQUEST["DAT_INICIAL"]));
    $dat_final = formatarData(fnLimpaCampo(@$_REQUEST["DAT_FINAL"]));

    if ($cod_item == "") {
        $data["errors"]["message"] = "Parâmetro 'COD_ITEM' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    if ($dat_inicial == "") {
        $data["errors"]["message"] = "Parâmetro 'DAT_INICIAL' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    if ($dat_final == "") {
        $data["errors"]["message"] = "Parâmetro 'DAT_FINAL' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }

    $cod_carrinho = 0;

    if ($uuid <> "") {
        $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
        $rs = mysqli_query($conexaotmp, $sql);
        if (mysqli_num_rows($rs) <= 0) {
            $uuid = "";
        } else {
            $linha = mysqli_fetch_assoc($rs);
            $cod_carrinho = $linha["COD_CARRINHO"];
        }
    }

    $valor_total = 0;
    if ($uuid == "") {
        $uuid = generateUUID();

        $sql = "INSERT INTO adorai_carrinho (COD_EMPRESA,UUID,TELEFONE,DAT_CADASTR) "
            . " VALUES "
            . " ($cod_empresa,'$uuid','$telefone',NOW()) ";

        $rs = mysqli_query($conexaotmp, $sql);
        if (!$rs) {
            $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
            http_response_code(400);
            echo json_encode($data);
            exit;
        }

        $cod_carrinho = mysqli_insert_id($conexaotmp);
    } else {

        $sql = "UPDATE adorai_carrinho SET "
            . " DAT_ALTERAC=NOW() "
            . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";

        $rs = mysqli_query($conexaotmp, $sql);
        if (!$rs) {
            $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
            http_response_code(400);
            echo json_encode($data);
            exit;
        }
    }


    $sql = "INSERT INTO adorai_carrinho_items (COD_CARRINHO,COD_EMPRESA,COD_PROPRIEDADE,COD_CHALE,COD_EXTERNO,DAT_INICIAL,DAT_FINAL,VALOR,DAT_CADASTR)"
        . " VALUES "
        . " ($cod_carrinho,$cod_empresa,$cod_propriedade,$cod_chale,'$cod_item','$dat_inicial','$dat_final','$valor',NOW()) ";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }


    $sql = "UPDATE adorai_carrinho SET "
        . " VALOR=(SELECT SUM(VALOR) FROM adorai_carrinho_items i WHERE i.COD_CARRINHO = adorai_carrinho.COD_CARRINHO), "
        . " DAT_ALTERAC=NOW() "
        . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);

    $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);
    $carrinho = mysqli_fetch_assoc($rs);

    $sql = "SELECT * FROM adorai_carrinho_items WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);
    $items = array();
    while ($linha = mysqli_fetch_assoc($rs)) {
        $items[] = $linha; // Adiciona cada linha à array $items
    }

    $data["uuid"] = $uuid;
    $data["ITEMS"] = $items;
    $data = array_merge($data, $carrinho);



    echo json_encode($data);
    exit;
} elseif ($acao == "altera-opcionais") {

    $uuid = fnLimpaCampo(@$_REQUEST["UUID"]);

    if ($uuid == "") {
        $data["errors"]["message"] = "Parâmetro 'UUID' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }

    $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND  UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);
    if (mysqli_num_rows($rs) <= 0) {
        $data["errors"]["message"] = "Carrinho não localizado!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    $linha = mysqli_fetch_assoc($rs);

    $cod_carrinho = $linha["COD_CARRINHO"];

    $opcionais = [];
    if (isset($_REQUEST["OPCIONAIS"])) {
        if (is_array(@$_REQUEST["OPCIONAIS"])) {
            $opcionais = $_REQUEST["OPCIONAIS"];
        } else {
            $opcionais = json_decode($_REQUEST["OPCIONAIS"], true);
        }
    }


    $sql = "DELETE FROM adorai_carrinho_opcionais WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);

    foreach ($opcionais as $opcional) {
        $sql = "INSERT INTO adorai_carrinho_opcionais (COD_CARRINHO,COD_OPCIONAL,COD_EMPRESA,QTD_OPCIONAL,VALOR,DAT_CADASTR)"
            . " SELECT "
            . " $cod_carrinho COD_CARRINHO,"
            . " COD_OPCIONAL,"
            . " $cod_empresa COD_EMPRESA,"
            . " " . (@$opcional["QTD_OPCIONAL"] <> "" ? $opcional["QTD_OPCIONAL"] : 1) . " QTD_OPCIONAL,"
            . " " . (@$opcional["VAL_VALOR"] <> "" ? $opcional["VAL_VALOR"] : "VAL_VALOR") . " VALOR,"
            . " NOW() DAT_CADASTR"
            . " FROM opcionais_adorai WHERE COD_OPCIONAL IN (0" . $opcional["COD_OPCIONAL"] . ")";
        $rs = mysqli_query($conexaotmp, $sql);
        if (!$rs) {
            $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
            http_response_code(400);
            echo json_encode($data);
            exit;
        }
    }



    $sql = "SELECT ACO.*, OA.LOG_CORTESIA FROM adorai_carrinho_opcionais ACO
            INNER JOIN opcionais_adorai OA ON OA.COD_OPCIONAL = ACO.COD_OPCIONAL
            WHERE ACO.COD_EMPRESA=$cod_empresa 
            AND ACO.COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);
    $items = array();
    while ($linha = mysqli_fetch_assoc($rs)) {
        $items[] = $linha; // Adiciona cada linha à array $items
    }

    $data["uuid"] = $uuid;
    $data["OPCIONAIS"] = $items;
    $data = array_merge($data);



    echo json_encode($data);
    exit;
} elseif ($acao == "remover-item") {

    $uuid = fnLimpaCampo(@$_REQUEST["UUID"]);
    $cod_item = +fnLimpaCampoZero(@$_REQUEST["COD_ITEM"]);

    if ($uuid == "") {
        $data["errors"]["message"] = "Parâmetro 'UUID' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    if ($cod_item == "") {
        $data["errors"]["message"] = "Parâmetro 'COD_ITEM' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }

    $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND  UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);
    if (mysqli_num_rows($rs) <= 0) {
        $data["errors"]["message"] = "Carrinho não localizado!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    $linha = mysqli_fetch_assoc($rs);

    $cod_carrinho = $linha["COD_CARRINHO"];

    $sql = "DELETE FROM adorai_carrinho_items WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho' AND COD_ITEM='$cod_item'";
    $rs = mysqli_query($conexaotmp, $sql);



    $sql = "UPDATE adorai_carrinho SET "
        . " VALOR=(SELECT SUM(VALOR) FROM adorai_carrinho_items i WHERE i.COD_CARRINHO = adorai_carrinho.COD_CARRINHO), "
        . " DAT_ALTERAC=NOW() "
        . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);

    $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);
    $carrinho = mysqli_fetch_assoc($rs);

    $sql = "SELECT * FROM adorai_carrinho_items WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='$cod_carrinho'";
    $rs = mysqli_query($conexaotmp, $sql);
    $items = array();
    while ($linha = mysqli_fetch_assoc($rs)) {
        $items[] = $linha; // Adiciona cada linha à array $items
    }

    $data["uuid"] = $uuid;
    $data["ITEMS"] = $items;
    $data = array_merge($data, $carrinho);



    echo json_encode($data);
    exit;
} elseif ($acao == "listar") {

    $uuid = fnLimpaCampo(@$_REQUEST["UUID"]);

    if ($uuid == "") {
        $data["errors"]["message"] = "Parâmetro 'UUID' não definido!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }


    $sql = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);
    if (mysqli_num_rows($rs) <= 0) {
        $data["errors"]["message"] = "Carrinho não localizado!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    $carrinho = mysqli_fetch_assoc($rs);

    $sql = "SELECT ACO.*, OA.LOG_CORTESIA FROM adorai_carrinho_opcionais ACO
            INNER JOIN opcionais_adorai OA ON OA.COD_OPCIONAL = ACO.COD_OPCIONAL
            WHERE ACO.COD_EMPRESA=$cod_empresa 
            AND ACO.COD_CARRINHO='" . $carrinho["COD_CARRINHO"] . "'";
    $rs = mysqli_query($conexaotmp, $sql);
    $items = array();
    while ($linha = mysqli_fetch_assoc($rs)) {
        $items[] = $linha; // Adiciona cada linha à array $items
    }

    $data["OPCIONAIS"] = $items;
    $data = array_merge($data, $carrinho);

    $sql = "SELECT * FROM adorai_carrinho_items WHERE COD_EMPRESA=$cod_empresa AND COD_CARRINHO='" . $carrinho["COD_CARRINHO"] . "'";
    $rs = mysqli_query($conexaotmp, $sql);
    $items = array();
    while ($linha = mysqli_fetch_assoc($rs)) {
        $items[] = $linha; // Adiciona cada linha à array $items
    }

    $data["uuid"] = $uuid;
    $data["ITEMS"] = $items;
    $data = array_merge($data, $carrinho);



    echo json_encode($data);
    exit;
} else {
    $data["errors"]["message"] = "Ação inválida!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}
