<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_venda = fnLimpaCampoZero(fnDecode($_POST['pk']));
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['codempresa']));
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);


	$sql = "UPDATE PEDIDO_MARKA SET $campo='$valor', 
									COD_USUNOTA = $_SESSION[SYS_COD_USUARIO] 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_VENDA = $cod_venda";
	fnEscreve($sql);
	fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
						
?>