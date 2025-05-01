<?php 

	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	// if($cod_empresa == 11){
	// 	$cod_empresa = 3;
	// }
	$texto_envio = $_POST['DES_TEMPLATE_ENVIO'];
	$des_emailus = fnLimpaCampo($_POST['DES_EMAILUS']);
	$des_assunto = fnLimpaCampo($_POST['DES_ASSUNTO_ENVIO']);

	// fnEscreve($des_assunto);
	// fnEscreve($texto_envio);

	//MONTAGEM DO E-MAIL
	include './externo/email/envio_sac.php';

	// echo "<pre>";
	// fnEscreve($emailsEnvolv);
	// echo "</pre>";

	$emails = explode(';', $des_emailus);

	if(count($emails) > 0){
		for ($i=1; $i < count($emails) ; $i++) { 
			$emailsCopia .= $emails[$i].";";
		}
		$emailsCopia = rtrim($emailsCopia,';');
	}else{
		$emailsCopia = "";
	}

	//destinatários
	$emailDestino = array('email1'=>$emails[0], 'email2'=>'', 'email3'=>'', 'email4'=>'', 'email5'=>$emailsCopia);
	// $dtEnvio = new DateTime();

	// echo "<pre>";
	// print_r($emailDestino);
	// echo "</pre>";

	if($des_assunto == ""){
		$des_assunto = "Sem assunto";
	}

	// echo 'fnsacmail(
	// 		'.$emailDestino.',
	// 		"Suporte Marka",
	// 		"<html><head></head><body>'.$texto_envio.'</body></html>",
	// 		"[TESTE] '.$des_assunto.'",
	// 		"Teste de Envio",
	// 		$connAdm->connAdm(),
	// 		connTemp($cod_empresa,""),"3",false,false)';

	$retorno = fnsacmail(
			$emailDestino,
			'Suporte Marka',
			"<html><head></head><body>$texto_envio</body></html>",
			"[TESTE] $des_assunto",
			'Teste de Envio',
			$connAdm->connAdm(),
			connTemp($cod_empresa,""),'3',false,false);

	// fnEscreve($retorno);

?>