

<?php


	include '../_system/_functionsMain.php';

	$opcao = fnLimpaCampoZero(fnDecode($_GET['opcao']));
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));	

	// echo "$opcao";

	switch($opcao){

		case 0:

			$sqlRegra = "SELECT DES_REGRAS FROM SITE_EXTRATO 
						 WHERE COD_EMPRESA = $cod_empresa 
						 LIMIT 1";

			// echo($sqlRegra);

			$arrayRegra = mysqli_query(connTemp($cod_empresa,''), $sqlRegra);
			$qrRegra = mysqli_fetch_assoc($arrayRegra);

			$des_regras = $qrRegra['DES_REGRAS'];

			echo html_entity_decode($des_regras);

		break;

		default:

				$cod_termo = $opcao;

				$sqlTermo = "SELECT DES_TERMO FROM TERMOS_EMPRESA 
							 WHERE COD_EMPRESA = $cod_empresa 
							 AND COD_TERMO = $cod_termo";

				$arrayTermo = mysqli_query(connTemp($cod_empresa,''), $sqlTermo);
				$qrTermo = mysqli_fetch_assoc($arrayTermo);

				$des_termo = $qrTermo['DES_TERMO'];

				echo html_entity_decode($des_termo);
			
		break;
	}

?>