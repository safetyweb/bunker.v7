<?php 

	include '_system/_functionsMain.php'; 
	include "_system/whatsapp/wsp.php";

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_senhaparc = fnLimpaCampoZero($_POST['COD_SENHAPARC']);
	$opcao = fnLimpaCampo($_GET['opcao']);

	$sql = "SELECT SENHAS_WHATSAPP.*
            from SENHAS_WHATSAPP
            WHERE COD_SENHAPARC = $cod_senhaparc";

    // fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

    $count = 0;
    $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

    $session = $cod_empresa;

    if($qrBuscaModulos[COD_UNIVEND] != 0 && $qrBuscaModulos[COD_UNIVEND] != ""){
        $session = $cod_empresa."_".$qrBuscaModulos[COD_UNIVEND];
    }

    $des_token = $qrBuscaModulos[DES_TOKEN];
    $des_authkey = $qrBuscaModulos[DES_AUTHKEY];
    $log_login = $qrBuscaModulos[LOG_LOGIN];

	// fnEscreve($cod_empresa);
	// fnEscreve($des_token);
	// fnEscreve($opcao);

	switch ($opcao) {
		case 'encerrar':

			// {
			//     "status": true,
			//     "message": "Session successfully closed"
			// }

			$connectQR=FnCloseSessao($session,$des_token);

			if($connectQR[status]){

				$sql = "UPDATE SENHAS_WHATSAPP
			            SET LOG_LOGIN = 'N',
			            SET DAT_LOGOUT = NOW()
			            WHERE COD_SENHAPARC = $cod_senhaparc";
			    mysqli_query($connAdm->connAdm(), $sql);

			}

			echo $connectQR[status];

		break;
		case 'status':

			// array(2) (
			//   [status] => (bool) false
			//   [message] => (string) Disconnected
			// )
			$connectQR=FnIniciacheckconnection($session,$des_token);
			echo $connectQR[status];

		break;
		case 'qrcode':

			$connectQR=FnIniciaStatusSessao($session,$des_token);

			if(!$connectQR[qrCode]){

				$connect=FnIniciaSessao($session,"$des_authkey");
            	$des_token = $connect[token];

				$sql = "UPDATE SENHAS_WHATSAPP
			            SET DES_TOKEN = '$des_token',
			            SET LOG_LOGIN = 'S',
			            SET DAT_LOGIN = NOW()
			            WHERE COD_SENHAPARC = $cod_senhaparc";
			    mysqli_query($connAdm->connAdm(), $sql);

			    $connectQR=FnGeraQRCODESessao($session,$des_token);

			    echo $connectQR[qrcode];

			}else{
				echo $geraQR[qrcode];
			}


			

			// echo "<pre>";
			// print_r($connectQR);
			// print_r($connect);
			// print_r($geraQR);
			// echo "</pre>";
			
			
		break;
		
		default:
			// code...
		break;
	}

	// echo "<pre>";
	// print_r($connectQR);
	// echo "</pre>";

?>