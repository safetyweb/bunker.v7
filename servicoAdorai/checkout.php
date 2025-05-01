<?php
include 'header.php';
include 'functions.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);



$acao = fnLimpaCampo(@$_REQUEST["ACAO"]);
$uuid = fnLimpaCampo(@$_REQUEST["UUID"]);

if ($acao == "CONFIRMA_PIX") {
    $status = fnLimpaCampoZero(@$_REQUEST["COD_STATUSPAG"]);
    if ($status == 0) {
        $status = 6;
    }
    $sql = "UPDATE adorai_pedido SET "
        . " COD_STATUSPAG='$status', "
        . " DAT_STATUSPAG=NOW(), "
        . " DAT_ALTERAC=NOW() "
        . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }


    // PAGAMENTO CONFIRMADO   
    if ($status == 6) {
        $sql = "INSERT INTO CAIXA (COD_EMPRESA, COD_CONTRAT, COD_TIPO, VAL_CREDITO, COD_USUCADA)"
            . " SELECT $cod_empresa,COD_PEDIDO,14,VALOR_COBRADO,9999 FROM adorai_pedido WHERE UUID='$uuid'";

        $rs = mysqli_query($conexaotmp, $sql);
        if (!$rs) {
            $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
            http_response_code(400);
            echo json_encode($data);
            exit;
        }
    }

    $data["status"] = "ok";
    echo json_encode($data);
    exit;
}


$nome = fnLimpaCampo(@$_REQUEST["NOME"]);
$sobrenome = fnLimpaCampo(@$_REQUEST["SOBRENOME"]);
$email = fnLimpaCampoEmail(@$_REQUEST["EMAIL"]);
$telefone = fnLimpaCampo(@$_REQUEST["TELEFONE"]);
$cpf = fnLimpaCampo(@$_REQUEST["CPF"]);

$nome_pg = fnLimpaCampo(@$_REQUEST["NOME_PG"]);
$sobrenome_pg = fnLimpaCampo(@$_REQUEST["SOBRENOME_PG"]);
$email_pg = fnLimpaCampoEmail(@$_REQUEST["EMAIL_PG"]);
$telefone_pg = fnLimpaCampo(@$_REQUEST["TELEFONE_PG"]);
$cpf_pg = fnLimpaCampo(@$_REQUEST["CPF_PG"]);

$id_reserva = fnLimpaCampoZero(@$_REQUEST["ID_RESERVA"]);
$cod_statuspag = fnLimpaCampoZero(@$_REQUEST["COD_STATUSPAG"]);
$valor_cobrado = fnLimpaCampoZero(@$_REQUEST["VALOR_COBRADO"]);
$val_cupom = fnLimpaCampoZero(@$_REQUEST["VALOR_CUPOM"]);
$desconto_pix = fnLimpaCampoZero(@$_REQUEST["DESCONTO_PIX"]);
$pix_50 = fnLimpaCampo(@$_REQUEST["PIX_50"]);

$des_api_hotel = fnLimpaCampo(@$_REQUEST["DES_API_HOTEL"]);
$des_api_pagamento = fnLimpaCampo(@$_REQUEST["DES_API_PAGAMENTO"]);

$cod_cupom = fnLimpaCampo(@$_REQUEST["COD_CUPOM"]);

$erro = fnLimpaCampo(@$_REQUEST["ERRO"]);

if ($id_reserva == 0 && $erro == "") {
    $arrHotel = json_decode($des_api_hotel, true);
    $id_reserva = $arrHotel['HotelReservations']['HotelReservation']['ResGlobalInfo']['HotelReservationIDs']['HotelReservationID']['@attributes']['ResID_Type'];
}


$cod_formapag = fnLimpaCampo(@$_REQUEST["COD_FORMAPAG"]);

if ($uuid == "") {
    $data["errors"]["message"] = "Parâmetro 'UUID' não definido!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}

if ($cod_formapag == "") {
    $data["errors"]["message"] = "Parâmetro 'COD_FORMAPAG' não definido!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}

//Verifica se já tem pedido com esse UUID
$sql_pedido = "SELECT * FROM adorai_pedido WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
$rs_pedido = mysqli_query($conexaotmp, $sql_pedido);
if (mysqli_num_rows($rs_pedido) <= 0) {

    //Não tem pedido, pega do carrinho
    //Verifica se tem carrinho com esse UUID
    $sql_carrinho = "SELECT * FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs_carrinho = mysqli_query($conexaotmp, $sql_carrinho);
    if (mysqli_num_rows($rs_carrinho) <= 0) {
        $data["errors"]["message"] = "Carrinho não encontrado!";
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    $carrinho = mysqli_fetch_assoc($rs_carrinho);
    $cod_carrinho = $carrinho["COD_CARRINHO"];

    //Gera o pedido com base no carrinho
    $sql = "INSERT INTO adorai_pedido (COD_CARRINHO,COD_EMPRESA,UUID,NOME,SOBRENOME,EMAIL,TELEFONE,CPF,NOME_PG,SOBRENOME_PG,EMAIL_PG,TELEFONE_PG,CPF_PG,ID_RESERVA,DAT_CADASTR,COD_CUPOM, VAL_CUPOM, DESCONTO_PIX, PIX_50)"
        . " VALUES "
        . " ($cod_carrinho,$cod_empresa,'$uuid','$nome','$sobrenome','$email','$telefone','$cpf','$nome_pg','$sobrenome_pg','$email_pg','$telefone_pg','$cpf_pg','$id_reserva',NOW(),'$cod_cupom','$val_cupom','$desconto_pix','$pix_50') ";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
    $cod_pedido = mysqli_insert_id($conexaotmp);

    $sql = "INSERT INTO adorai_pedido_items (COD_PEDIDO,COD_EMPRESA,COD_PROPRIEDADE,COD_CHALE,COD_EXTERNO,DAT_INICIAL,DAT_FINAL,VALOR,DAT_CADASTR)"
        . " SELECT $cod_pedido COD_PEDIDO,COD_EMPRESA,COD_PROPRIEDADE,COD_CHALE,COD_EXTERNO,DAT_INICIAL,DAT_FINAL,VALOR,NOW() DAT_CADASTR FROM adorai_carrinho_items WHERE COD_CARRINHO = " . $carrinho["COD_CARRINHO"] . " ORDER BY COD_ITEM DESC LIMIT 1";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }

    $sql = "INSERT INTO adorai_pedido_opcionais (COD_PEDIDO,COD_OPCIONAL,COD_EMPRESA,QTD_OPCIONAL,VALOR,DAT_CADASTR)"
        . " SELECT $cod_pedido COD_PEDIDO,COD_OPCIONAL,COD_EMPRESA,QTD_OPCIONAL,VALOR,NOW() DAT_CADASTR FROM adorai_carrinho_opcionais WHERE COD_CARRINHO = " . $carrinho["COD_CARRINHO"] . " ";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }

    $sql = "UPDATE adorai_pedido SET "
        . " VALOR_PEDIDO=(SELECT SUM(VALOR) FROM adorai_pedido_items i WHERE i.COD_PEDIDO = adorai_pedido.COD_PEDIDO), "
        . " VALOR_OPCIONAIS=(SELECT SUM(VALOR) FROM adorai_pedido_opcionais i WHERE i.COD_PEDIDO = adorai_pedido.COD_PEDIDO), "
        . " DAT_ALTERAC=NOW() "
        . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);


    $sql = "UPDATE adorai_pedido SET "
        . " VALOR=IFNULL(VALOR_PEDIDO,0) + IFNULL(VALOR_OPCIONAIS,0), "
        . " VALOR_COBRADO=$valor_cobrado, "
        . " DAT_ALTERAC=NOW() "
        . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);

    //Remove item do carrinho

    $sql = "DELETE FROM adorai_carrinho WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
}

if ($cod_statuspag == 6) {
    // PAGAMENTO CONFIRMADO  
    if ($cod_formapag == 1) {
        $tip_credito = 14;
    } else {
        $tip_credito = 15;
    }

    $sql = "INSERT INTO CAIXA (COD_EMPRESA, COD_CONTRAT, COD_TIPO, VAL_CREDITO, COD_USUCADA)"
        . " SELECT $cod_empresa,COD_PEDIDO,$tip_credito,VALOR_COBRADO,9999 FROM adorai_pedido WHERE UUID='$uuid'";

    $rs = mysqli_query($conexaotmp, $sql);
    if (!$rs) {
        $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
        http_response_code(400);
        echo json_encode($data);
        exit;
    }
}

$sql = "UPDATE adorai_pedido SET "
    . " DES_API_HOTEL='$des_api_hotel', "
    . " DES_API_PAGAMENTO='$des_api_pagamento', "
    . " ERRO='$erro', "
    . " COD_STATUSPAG='$cod_statuspag', "
    . " COD_FORMAPAG='$cod_formapag', "
    . " DAT_STATUSPAG=NOW(), "
    . " DAT_ALTERAC=NOW() "
    . " WHERE COD_EMPRESA=$cod_empresa AND UUID='$uuid'";
$rs = mysqli_query($conexaotmp, $sql);
if (!$rs) {
    $data["errors"]["message"] = "Erro MySQL: " . mysqli_error($conexaotmp);
    http_response_code(400);
    echo json_encode($data);
    exit;
}

$data["message"] = "Pedido feito com sucesso!";

echo json_encode($data);
exit;
