<?php

	$sqlPush = "SELECT DES_USUARIO, 
					   DES_AUTHKEY, 
					   COD_LISTAEXT AS DES_AUTHKEY_IOS, 
					   DES_CLIEXT AS DES_USUARIO_IOS 
				FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE apar.COD_EMPRESA = $cod_empresa
				AND par.COD_TPCOM='5' 
				AND apar.COD_PARCOMU='18' 
				AND apar.LOG_ATIVO='S'";

	$arrayPush = mysqli_query($connAdm->connAdm(),trim($sqlPush));
	$qrPush = mysqli_fetch_assoc($arrayPush);

	$app_id = array();
	$Authorization = array();
		
	$app_id['Android'] = $qrPush['DES_USUARIO'];
	$app_id['iOS'] = $qrPush['DES_USUARIO_IOS'];
	$Authorization['Android'] = $qrPush['DES_AUTHKEY'];	
	$Authorization['iOS'] = $qrPush['DES_AUTHKEY_IOS'];	

?>