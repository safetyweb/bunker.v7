<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_empresa = fnLimpaCampoZero($_POST['pk']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);


	$sql = "UPDATE EMPRESAS SET $campo='$valor' WHERE COD_EMPRESA = $cod_empresa";
	fnEscreve($sql);
	fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
						
?>