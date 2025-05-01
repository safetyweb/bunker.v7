<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_cliente = fnLimpaCampoZero($_POST['pk']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['codempresa']));
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	if($valor == 'N'){
		$addUpdate = ",VAL_BONIFICA = '0.00',
					PCT_JURIBONI = '0.00'"; 
	}

	if($valor == ""){
		$valor = "0.00";
	}

	$sql = "UPDATE CLIENTES SET $campo='$valor' 
			$addUpdate
			WHERE COD_EMPRESA = $cod_empresa
			AND COD_CLIENTE = $cod_cliente";
	fnEscreve($sql);
	fnTestesql(connTemp($cod_empresa,''),$sql);
						
?>