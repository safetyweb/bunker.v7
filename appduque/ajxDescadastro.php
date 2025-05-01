<?php


	include './_system/_functionsMain.php';
	// header("X-Frame-Options: SAMEORIGIN");

	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));	

	$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));	

	$sql = "CALL `SP_EXCLUI_CLIENTES`($cod_cliente, $cod_empresa, '9998', 'exc', 3)";
    // fnEscreve($sql);
    mysqli_query(connTemp($cod_empresa, ''), $sql);

?>