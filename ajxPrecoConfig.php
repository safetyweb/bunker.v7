<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_preco = fnLimpaCampoZero($_POST['pk']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);
	$qtd = fnLimpaCampo($_POST['qtd']);

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	if (strpos($valor, ',') !== false) {
	    $valor = fnValorSql($valor);
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($campo);
	// fnEscreve($valor);

	if($qtd != 0){

		$sql = "UPDATE COMUNICACAO_PRECO SET $campo='$valor', VAL_TOTAL = ('$qtd'*'$valor'), COD_ALTERAC = $cod_usucada, DAT_ALTERAC = NOW() WHERE COD_PRECO = $cod_preco";
		// fnEscreve($sql);
		mysqli_query($connAdm->connAdm(),$sql);

		$sql = "SELECT VAL_TOTAL FROM COMUNICACAO_PRECO WHERE COD_PRECO = $cod_preco";
		// fnEscreve($sql);
		$qrTot = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

		echo fnValor($qrTot['VAL_TOTAL'],2);

	}else{

		$sql = "UPDATE COMUNICACAO_PRECO SET $campo='$valor', COD_ALTERAC = $cod_usucada, DAT_ALTERAC = NOW() WHERE COD_PRECO = $cod_preco";
		// fnEscreve($sql);
		mysqli_query($connAdm->connAdm(),$sql);

		$sql = "SELECT $campo FROM COMUNICACAO_PRECO WHERE COD_PRECO = $cod_preco";
		// fnEscreve($sql);
		$qrTot = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

		echo fnValor($qrTot[$campo],0);

	}


?>