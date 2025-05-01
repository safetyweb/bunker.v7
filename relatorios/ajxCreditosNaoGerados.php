<?php 

	include '../_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_cliente = fnLimpaCampoZero($_POST['pk']);
	$cod_empresa = fnLimpaCampoZero($_POST['cod_empresa']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);


	$sql = "UPDATE CLIENTES SET $campo = '$valor' WHERE COD_CLIENTE = $cod_cliente; ";

	$sql .= "UPDATE VENDAS SET COD_CREDITOU = 0 WHERE COD_CREDITOU = 3 AND COD_CLIENTE = $cod_cliente; ";
	fnEscreve($sql);
	mysqli_multi_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
						
?>