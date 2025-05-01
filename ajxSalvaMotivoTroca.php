<?php
	
	//echo "<h5>_".$opcao."</h5>";
	include "./_system/_functionsMain.php";
	
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
	$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
	$num_cartao_novo = fnLimpaCampoZero($_POST['NUM_CARTAO_NOVO']);
	$cod_tipmoti = fnLimpaCampoZero($_POST['COD_TIPMOTI']);
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
	$opcao = 'ALT';


	//busca dados da empresa
	$sql = "select LOG_AUTOCAD FROM EMPRESAS WHERE COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaLOG_AUTOCAD = mysqli_fetch_assoc($arrayQuery);
	$log_autocad = $qrBuscaLOG_AUTOCAD['LOG_AUTOCAD'];

	$sql1 = "SELECT NUM_CARTAO FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";
	//fnEscreve($sql);
	$arrayQuery1 = mysqli_query(connTemp($cod_empresa,''),$sql1);
	$qrCard = mysqli_fetch_assoc($arrayQuery1);
	$num_cartao = $qrCard['NUM_CARTAO'];

	$sql2 = "CALL SP_ALTERA_NUMEROCARTAO(
				'".$cod_cliente."',
				'".$cod_empresa."',
				'".$num_cartao."',
				'".$num_cartao_novo."',
				'".$cod_usucada."',
				'".$cod_tipmoti."',
				'".$log_autocad."',
				'".$opcao."'  
			) ";
	
	// fnEscreve($sql2);
			
	mysqli_query(connTemp($cod_empresa,''),$sql2);

?>
