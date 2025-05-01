<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	


	$cod_item = fnLimpaCampoZero($_POST['coditem']);
	$tipo_item = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	if($tipo_item == "CHALE"){

		$sql = "SELECT * FROM ADORAI_PEDIDO WHERE COD_PEDIDO = $cod_item";
		$query = mysqli_query(connTemp(274, ''), $sql);

		if($qrBusca = mysqli_fetch_assoc($query)){
			$sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
				VAL_REFERENCIA_CHALE = '$valor'
				WHERE COD_PEDIDO = $cod_item";
			mysqli_query(connTemp(274,''), $sqlUpdate);
		}

	}else if($tipo_item == "COMMENT"){

		
			$sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
				DES_OBSERVA = '$valor'
				WHERE COD_PEDIDO = $cod_item";
			mysqli_query(connTemp(274,''), $sqlUpdate);

	}else{

		$sql = "SELECT * FROM ADORAI_PEDIDO_OPCIONAIS WHERE COD_ITEM_OPCIONAL = $cod_item";
		$query = mysqli_query(connTemp(274, ''), $sql);

		echo $sql;

		if($qrBusca = mysqli_fetch_assoc($query)){
			$sqlUpdate = "UPDATE ADORAI_PEDIDO_OPCIONAIS SET 
				VAL_REFERENCIA_OPCIONAL = '$valor'
				WHERE COD_ITEM_OPCIONAL = $cod_item";

			mysqli_query(connTemp(274,''), $sqlUpdate);
		}

	}

						
?>