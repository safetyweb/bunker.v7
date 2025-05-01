<?php

	if(!isset($_SESSION['AUTH_DINAMIZE'])){

		$sqlDinamize = "SELECT DES_USUARIO, DES_AUTHKEY, DES_CLIEXT, COD_LISTA
						FROM SENHAS_PARCEIRO 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_PARCOMU = 15
						ORDER BY 1 DESC 
						LIMIT 1";

		// fnEscreve($sqlDinamize);

		$arrayDinamize = mysqli_query($connAdm->connAdm(),trim($sqlDinamize));
		$qrDinamize = mysqli_fetch_assoc($arrayDinamize);

		if($qrDinamize['DES_USUARIO'] != "" && $qrDinamize['DES_AUTHKEY'] != "" && $qrDinamize['DES_CLIEXT'] != "" ){
			$autentica = autenticacao_dinamiza( $qrDinamize['DES_USUARIO'] ,$qrDinamize['DES_AUTHKEY'] ,$qrDinamize['DES_CLIEXT']);

			// echo "<pre>".print_r($autentica)."</pre>";

			$_SESSION += ['AUTH_DINAMIZE' => $autentica['body']['auth-token']];
			$_SESSION += ['COD_LISTA' => $qrDinamize['COD_LISTA']];

		}

	}

?>