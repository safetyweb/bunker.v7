<?php
include 'header.php';
include 'functions.php';

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data === null) {
    $data["errors"]["message"] = "Erro ao decodificar o arquivo.";
    http_response_code(400);
    // echo json_encode($data);
    exit;
}

// Verifica se a ação está definida no JSON
if (!isset($data['acao'])) {
    $data["errors"]["message"] = "Ação não definida no arquivo.";
    http_response_code(400);
    // echo json_encode($data);
    exit;
}


switch ($data['acao']) {
    case 'valida':

    $des_chavecupom = $data['chavecupom'];
    $num_cgcecpf = $data['num_cgcecpf'];
    $num_celular = $data['numC'];

    $andDado = "NUM_TELEFONE = '$num_celular'";

    if($num_cgcecpf != ""){
        $andDado = "NUM_CGCECPF = '$num_cgcecpf'";
    }

    if($des_chavecupom == ""){
        $response['errors']["message"] = "Cupom não informado.";
        http_response_code(400);
        echo json_encode($response);
        exit;
    }


    $sql = "SELECT * FROM CUPOM_ADORAI WHERE DES_CHAVECUPOM = '$des_chavecupom' AND LOG_ATIVO = 'S' LIMIT 1";
    
    $query = mysqli_query(conntemp(274, ''), $sql);

    if($qrResult =  mysqli_fetch_assoc($query)){

        //VALIDAÇÃO CUPOM POR DATA
        if($qrResult['LOG_VALIDADE'] == 'D'){
            $dat_ini = $qrResult['DAT_INI'];
            $dat_fin = $qrResult['DAT_FIN'];
            $hoje = date("Y-m-d");

            if($hoje < $dat_ini || $hoje > $dat_fin){
                $response = [];
                $response['errors']["message"] = "Cupom expirado.";
                http_response_code(400);
                echo json_encode($response);
                exit;
            }
        }


        //VALIDAÇÃO CUPOM POR HOSPEDE
        if($qrResult['LOG_HOSPEDE'] == 'S'){
            $sqlHospede = "SELECT * FROM HOSPEDES_ADORAI WHERE $andDado LIMIT 1";
            $queryHospede = mysqli_query(conntemp(274, ''), $sqlHospede);
            $qrHospede = mysqli_fetch_assoc($queryHospede);

            if($qrHospede['COD_HOSPEDE'] != $qrResult['COD_HOSPEDE']){
                $response = [];
                $response["errors"]["message"] = "Cupom indisponível para este documento.";
                http_response_code(400);
                echo json_encode($response);
                exit;
            }
        }

        //VALIDAÇÃO CUPOM POR QUANTIDADE
        if($qrResult['LOG_QTDUSO'] == "L"){
            $sqlPedidos = "SELECT count(COD_PEDIDO) AS CONTADOR FROM ADORAI_PEDIDO WHERE COD_CUPOMADORAI = ".$qrResult['COD_CUPOMADORAI'];
            $queryPedido = mysqli_query(conntemp(274, ''), $sqlPedidos);
            $qrPedido = mysqli_fetch_assoc($queryPedido);

            if($qrPedido['CONTADOR'] >= $qrResult['QTD_USO']){
                $response = [];
                $response["errors"]["message"] = "Cupom Esgotado.";
                http_response_code(400);
                echo json_encode($response);
                exit;
            }
        }

    }else{
        $response["errors"]["message"] = "Cupom inválido ou expirado.";
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    switch ($qrResult['TIP_DESCONTO']) {
        case '1':
            $regra = "V*D";
        break;

        case '2':
            $regra = "%*D";
        break;

        case '3':
            $regra = "%*T";
        break;
        
        default:
            $regra = "V*T";
        break;
    }

    $response["errors"]["message"] = "ok";
    $response['CUPOM']["TIP_DESCONTO"] = $qrResult['TIP_DESCONTO'];
    $response['CUPOM']["REGRA"] = $regra;
    $response['CUPOM']['VAL_DESCONTO'] = $qrResult['VAL_DESCONTO'];
    $response['CUPOM']['PROPRIEDADE'] = $qrResult['COD_PROPRIEDADE'];
    http_response_code(200);
    echo json_encode($response);

    break;

    default:
    $data["errors"]["message"] = "Ação inválida.";
    http_response_code(400);
    echo json_encode($data);
    exit;
}
