<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_contrato = fnLimpaCampoZero($_POST['pk']);
	$cod_empresa = fnLimpaCampoZero($_POST['codempresa']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);

	$sql = "SELECT COD_CONTRATO FROM EMPRESA_CONTRATO WHERE COD_EMPRESA = $cod_empresa";
	$arrayCod = mysqli_query($connAdm->connAdm(),$sql);

	if(mysqli_num_rows($arrayCod) > 0){

		$updtDat = "";

		if($campo == "TIP_CONTRATO"){
			$updtDat = ", DAT_ALTERAC_TIP = NOW()";
		}else if($campo == "VL_CONTRATO"){
			$updtDat = ", DAT_ALTERAC_VAL = NOW()";
		}

		$sql = "UPDATE EMPRESA_CONTRATO SET $campo='$valor' $updtDat WHERE COD_CONTRATO = $cod_contrato";
		fnEscreve($sql);
		fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

	}else{

		$sql = "INSERT INTO EMPRESA_CONTRATO(COD_EMPRESA, $campo) VALUES($cod_empresa, '$valor')";
		fnEscreve($sql);
		fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

	}
						
?>