<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_link = fnLimpaCampoZero(fnDecode($_POST['pk']));
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['id']));
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);


	$sql = "UPDATE LINK_ADORAI SET $campo='$valor' WHERE COD_LINK = $cod_link";
	fnEscreve($sql);
	fnTestesql(conntemp($cod_empresa,""),$sql);
						
?>