<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	$cpf = fnLimpacampo(fnLimpaDoc($_POST['c1']));
	$cartao = fnLimpacampo(fnLimpaDoc($_POST['c10']));
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);

	//busca dados do cliente
	if($cartao != ""){
		$sql = "SELECT COD_CLIENTE FROM CLIENTES where NUM_CARTAO = '$cartao' AND COD_EMPRESA = $cod_empresa";
	}else{
		$sql = "SELECT COD_CLIENTE FROM CLIENTES where NUM_CGCECPF = '$cpf' AND COD_EMPRESA = $cod_empresa";
	}
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaCliente['COD_CLIENTE'])){	
		echo fnEncode($qrBuscaCliente['COD_CLIENTE']);
	}else{
		echo 0;
	}