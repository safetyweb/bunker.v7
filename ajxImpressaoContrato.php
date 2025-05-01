<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_contrat = fnLimpaCampoZero(fnDecode($_GET['idc']));

	$sql = "UPDATE CONTRATO_ELEITORAL SET 
					NUM_IMPRESSAO = (NUM_IMPRESSAO+1)
			WHERE COD_EMPRESA = $cod_empresa
			AND COD_CONTRAT = $cod_contrat";

	fnescreve($sql);

	mysqli_query(connTemp($cod_empresa,''), $sql);

?>