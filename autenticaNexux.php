<?php

	// 18/02/2022 - MUDADO DE DES_AUTHKEY2 PARA DES_AUTHKEY
	// 22/02/2022 - ADICIONADO LÓGICA PARA DEFINIR SE DES_AUTHKEY OU DES_AUTHKEY2
	// 03/07/2024 - ADICIONADO NOVOS PARCEIROS 23/24 E ADICIONADO RELACIONAMENTO NA BUSCA PARA TRAZER A URL_API

	$authkey = "APAR.DES_AUTHKEY";

	if($otp=='desativado'){
		$authkey = "APAR.DES_AUTHKEY2";
	}

	$sqlNexux = "SELECT APAR.DES_USUARIO, $authkey AS DES_AUTHKEY, APAR.DES_CLIEXT, APAR.COD_PARCOMU, PAR.URL_API
					FROM SENHAS_PARCEIRO APAR
					INNER JOIN PARCEIRO_COMUNICACAO PAR ON PAR.COD_PARCOMU=APAR.COD_PARCOMU
					WHERE APAR.COD_EMPRESA = $cod_empresa 
					AND PAR.COD_TPCOM=2
					-- AND APAR.COD_PARCOMU IN(17,22,23,24)
					AND APAR.LOG_ATIVO = 'S'
					ORDER BY APAR.COD_PARCOMU DESC 
					LIMIT 1";

	$arrayNexux = mysqli_query($connAdm->connAdm(),trim($sqlNexux));
	$qrNexux = mysqli_fetch_assoc($arrayNexux);

	if($qrNexux['DES_USUARIO'] != "" && $qrNexux['DES_AUTHKEY'] != ""){
		
		$usuario = $qrNexux['DES_USUARIO'];
		$senha = $qrNexux['DES_AUTHKEY'];
		$url_api = $qrNexux['URL_API'];
		$cliente_externo = $qrNexux['DES_CLIEXT'];
		$cod_parcomu_auth = $qrNexux['COD_PARCOMU'];
		$parc_cadastrado = 1;

		if($cod_parcomu_auth==22){
			$senha='basic '. base64_encode($qrNexux[DES_USUARIO].':'.$qrNexux[DES_AUTHKEY]);
			$usuario = $qrNexux['DES_CLIEXT'];
		}

	}else{

		$usuario = "";
		$senha = "";
		$cliente_externo = "";
		$parc_cadastrado = 0;

	}

?>