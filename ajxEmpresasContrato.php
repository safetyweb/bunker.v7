<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	

	$sql = "INSERT INTO EMPRESA_CONTRATO(
						COD_EMPRESA,
						QTD_LOJA
						) VALUES(
						$cod_empresa,
						0
						)";

	// fnEscreve($sql);
	mysqli_query($connAdm->connAdm(),$sql);
						
?>