<?php include "_system/_functionsMain.php";
include '_system/_FUNCTION_WS.php';

$cod_empresa = fnLimpaCampo(fnDecode($_REQUEST['id']));
$opcao = $_REQUEST['opcao'];
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if (isset($_REQUEST['NUM_CGCECPF'])) {
    $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
}

if (isset($_REQUEST['COD_UNIVEND'])) {
    $cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
}

if (isset($_REQUEST['COD_CONFIGU'])) {
    $cod_configu = fnLimpaCampoZero($_REQUEST['COD_CONFIGU']);
} else {
    $cod_configu = 0;
}

if (isset($_REQUEST['LOG_ATIVO_TKT']) && empty($_REQUEST['LOG_ATIVO_TKT'])) {
    $log_ativo_tkt = 'N';
} else {
    $log_ativo_tkt = $_REQUEST['LOG_ATIVO_TKT'];
}

if (isset($_REQUEST['LOG_EMISDIA']) && empty($_REQUEST['LOG_EMISDIA'])) {
    $log_emisdia = 'N';
} else {
    $log_emisdia = $_REQUEST['LOG_EMISDIA'];
}

if (isset($_REQUEST['LOG_LISTAWS']) && empty($_REQUEST['LOG_LISTAWS'])) {
    $log_listaws = 'N';
} else {
    $log_listaws = $_REQUEST['LOG_LISTAWS'];
}

$num_historico_tkt = $_REQUEST['NUM_HISTORICO_TKT'][0];
$num_historico_Array = explode(";", $_REQUEST['NUM_HISTORICO_TKT'][0]);
$min_historico_tkt = $num_historico_Array['0'];
$max_historico_tkt = $num_historico_Array['1'];

if (isset($_REQUEST['COD_TEMPLATE_TKT'])) {
    $cod_template_tkt = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE_TKT']);
} else {
    $cod_template_tkt = 0;
}

if (isset($_REQUEST['QTD_COMPRAS_TKT'])) {
    $qtd_compras_tkt = fnLimpaCampoZero($_REQUEST['QTD_COMPRAS_TKT']);
} else {
    $qtd_compras_tkt = 0;
}

if (isset($_REQUEST['QTD_OFERTAS_TKT'])) {
    $qtd_ofertas_tkt = fnLimpaCampoZero($_REQUEST['QTD_OFERTAS_TKT']);
} else {
    $qtd_ofertas_tkt = 0;
}

if (isset($_REQUEST['QTD_OFERTWS_TKT'])) {
    $qtd_ofertws_tkt = fnLimpaCampoZero($_REQUEST['QTD_OFERTWS_TKT']);
} else {
    $qtd_ofertws_tkt = 0;
}

if (isset($_REQUEST['QTD_OFERTAS_LST'])) {
    $qtd_ofertas_lst = fnLimpaCampoZero($_REQUEST['QTD_OFERTAS_LST']);
} else {
    $qtd_ofertas_lst = 0;
}

if (isset($_REQUEST['QTD_CATEGOR_TKT'])) {
    $qtd_categor_tkt = fnLimpaCampoZero($_REQUEST['QTD_CATEGOR_TKT']);
} else {
    $qtd_categor_tkt = 0;
}

if (isset($_REQUEST['QTD_PRODUTOS_TKT'])) {
    $qtd_produtos_tkt = fnLimpaCampoZero($_REQUEST['QTD_PRODUTOS_TKT']);
} else {
    $qtd_produtos_tkt = 0;
}

if (isset($_REQUEST['QTD_PRODUTOS_CAT'])) {
    $qtd_produtos_cat = fnLimpaCampoZero($_REQUEST['QTD_PRODUTOS_CAT']);
} else {
    $qtd_produtos_cat = 0;
}

if (isset($_REQUEST['DES_PRATPRC'])) {
    $des_pratprc = fnLimpaCampo($_REQUEST['DES_PRATPRC']);
} else {
    $des_pratprc = '';
}

if (isset($_REQUEST['DES_VALIDADE'])) {
    $des_validade = fnLimpaCampoZero($_REQUEST['DES_VALIDADE']);
} else {
    $des_validade = 0;
}

//array das empresas multiacesso
if (isset($_POST['COD_BLKLIST'])) {
    $Arr_COD_BLKLIST = $_POST['COD_BLKLIST'];
    //print_r($Arr_COD_MULTEMP);			 
    for ($i = 0; $i < count($Arr_COD_BLKLIST); $i++) {
        $cod_blklist = $cod_blklist . $Arr_COD_BLKLIST[$i] . ",";
    }
    $cod_blklist = substr($cod_blklist, 0, -1);
} else {
    $cod_blklist = "0";
}

switch ($opcao) {
    case 'simulador':
        if ($num_cgcecpf != '') {
            //SE TIVER CODIGO DO CLIENTE, CONSULTA SOAP DO DIOGO PARA BUSCAR A URL DO TICKET DO CLIENTE
            $sql = "SELECT COD_UNIVEND FROM CLIENTES WHERE NUM_CGCECPF = $num_cgcecpf AND COD_EMPRESA = " . $cod_empresa;
            $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
            if (mysqli_num_rows($arrayQuery) > 0) {
                // $rowCliente = mysqli_fetch_assoc($arrayQuery);
                // $cod_univend = $rowCliente['COD_UNIVEND'];
                // $cpf = $rowCliente['NUM_CGCECPF'];

                $sqlWs = "SELECT LOG_USUARIO,DES_SENHAUS,COD_EMPRESA FROM usuarios  WHERE cod_empresa=" . $cod_empresa . " AND COD_TPUSUARIO=10 AND log_estatus='S' limit 1";

                $queryWs = mysqli_query($connAdm->connAdm(), $sqlWs);
                $qrResult = mysqli_fetch_assoc($queryWs);
                $log_usuario = $qrResult['LOG_USUARIO'];
                $des_senhaus = fnDecode($qrResult['DES_SENHAUS']);
                $id = fnEncode($cod_empresa . ';' . $num_cgcecpf . ';' . $cod_univend);

                $arraygeratkt = array(
                    'cpf' => $num_cgcecpf,
                    'cod_empresa' => rtrim(trim($cod_empresa)),
                    'login' => rtrim(trim($log_usuario)),
                    'senha' => rtrim(trim($des_senhaus)),
                    'loja' => rtrim(trim($cod_univend))
                );


                GetURLTktMania($arraygeratkt);
                $url = "https://adm.bunker.mk/ticket/?tkt=$id&print=no";
                // $response = consultaCliente($num_cgcecpf, $cod_empresa, $log_usuario, $des_senhaus, $cod_univend);
                // $url = $response["body"]["envelope"]["body"]["buscaconsumidorresponse"]["buscaconsumidorresponse"]["acao_b_ticket_de_ofertas"]["url_ticketdeofertas"];

                if ($url != "") {
                    echo $url;
                }
            }
        }

        break;

    case 'CAD':
    case 'ALT':
        $sql = "CALL SP_ALTERA_CONFIGURACAO_TICKET (
				 '" . $cod_configu . "', 
				 '" . $cod_empresa . "', 
				 '" . $log_ativo_tkt . "', 
				 '" . $cod_template_tkt . "', 
				 '" . $qtd_compras_tkt . "', 
				 '" . $qtd_ofertas_tkt . "', 
				 '" . $num_historico_tkt . "', 
				 '" . $min_historico_tkt . "', 
				 '" . $max_historico_tkt . "', 
				 '" . $qtd_categor_tkt . "', 
				 '" . $cod_blklist . "', 
				 '" . $qtd_produtos_tkt . "', 
				 '" . $log_emisdia . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $qtd_ofertas_lst . "', 
				 '" . $qtd_ofertws_tkt . "', 
				 '" . $log_listaws . "', 
				 '" . $des_pratprc . "', 
				 '" . $des_validade . "', 
				 '" . $qtd_produtos_cat . "', 
				 '" . $opcao . "'    
				) ";
        // fnEscreve($sql);
        mysqli_query(connTemp($cod_empresa, ""), trim($sql));
        break;

    case 'buscaTexto':
        $cod_registr = fnLimpaCampoZero($_REQUEST['idr']);

        $sql = "SELECT DES_TEXTO FROM MODELOTEMPLATETKT 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_REGISTR = $cod_registr";
        // fnEscreve($sql);
        $qrTexto = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        if (isset($qrTexto['DES_TEXTO'])) {
            echo $qrTexto['DES_TEXTO'];
        } else {
            echo '';
        }

        break;

    case 'salvaTexto':
        $cod_registr = fnLimpaCampoZero($_REQUEST['idr']);
        $des_comentario = fnLimpaCampo(addslashes(htmlentities($_REQUEST['DES_COMENTARIO'])));

        $sql = "UPDATE MODELOTEMPLATETKT SET DES_TEXTO = '$des_comentario' 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_REGISTR = $cod_registr";
        mysqli_query(connTemp($cod_empresa, ""), trim($sql));

        break;
}
