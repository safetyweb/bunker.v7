<?php 

	include '_system/_functionsMain.php';
	include "_system/whatsapp/wstAdorai.php";

	$cod_senhaparc = fnLimpaCampoZero($_POST['COD_SENHAPARC']);
	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$sql = "SELECT SENHAS_WHATSAPP.*
            from SENHAS_WHATSAPP
            WHERE COD_SENHAPARC = $cod_senhaparc";

    // fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

    $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

	switch ($opcao) {
		case 'qrcode':

			$connection = fnconnectionState("$qrBuscaModulos[NOM_SESSAO]", "$qrBuscaModulos[DES_AUTHKEY]", "$qrBuscaModulos[PORT_SERVICAO]");
			$statusCreate = 1;

			if($connection['message'] != 'HTTP Error: 404'){
				$delete = Fndelete("$qrBuscaModulos[NOM_SESSAO]","$qrBuscaModulos[DES_AUTHKEY]","$qrBuscaModulos[PORT_SERVICAO]");
				if($delete['status'] != 'SUCCESS'){
					$statusCreate = 0;
				}
			}

			if($statusCreate == 1){

				$session = Fncreate("$qrBuscaModulos[NOM_SESSAO]","$qrBuscaModulos[DES_AUTHKEY]",$qrBuscaModulos['CELULAR'],"$qrBuscaModulos[TIP_INTEGRACAO]","$qrBuscaModulos[PORT_SERVICAO]");
				 
				//$session = Fncreate("$nom_sessao","$des_authkey",$celular,"$tip_integracao","$port_servicao");

				if($session['instance']['status'] == 'created'){
					$des_base64 = $session['qrcode']['base64'];

					$sql = "UPDATE SENHAS_WHATSAPP SET
									DES_BASE64='$des_base64',
									COD_USUALT=$cod_usucada,
									DAT_ALTERAC=NOW()
							WHERE COD_SENHAPARC = $cod_senhaparc";
					mysqli_query($connAdm->connAdm(), $sql);

					echo $des_base64;
				}else{
					echo 0;
				}
				
	        }else{
				echo 0;
			}
			// echo "<pre>";
			// echo 'verificando instancia: <br>';
			// print_r($connection);
			// echo 'se deletou: <br>';
			// print_r($delete);
			// echo 'se criou: <br>';
			// print_r($session);
			// echo "</pre>";
			
			
		break;

		case 'reconect':
			
			$connect = fnReconect("$qrBuscaModulos[NOM_SESSAO]","$qrBuscaModulos[DES_AUTHKEY]","$qrBuscaModulos[PORT_SERVICAO]");
			// echo "<pre>";			
			// print_r($connect);			
			// echo "</pre>";			
			$des_base64 = $connect['base64'];

			if($des_base64 != ""){
				echo $des_base64;
			}else{
				echo 0;
			}
		break;

		default:
			// code...
		break;
	}

	// echo "<pre>";
	// print_r($connectQR);
	// echo "</pre>";

?>