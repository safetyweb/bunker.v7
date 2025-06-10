<?php

include "../_system/_functionsMain.php";

$nome = fnLimpacampo($_POST['nome']);
$fromMail = fnLimpacampo($_POST['email']);
$mensagem = fnLimpacampo($_POST['mensagem']);
$assunto = strtoupper(fnLimpacampo($_POST['assunto']));

$g_token = $_POST['g_token'];

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => [
		'secret' => '6LecLDUnAAAAANUs2utDQb9hXEkDMytLsT79P4k0',
		'response' => $g_token
	]
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$responseArr = json_decode($response, true);

if ($responseArr['success']) {

	$cod_empresa = fnLimpacampo($_POST['codEmpresa']);

	$sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
	$qrEmailCopia = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

	$sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

	$sqlSmtp = "SELECT DES_EMAIL FROM SENHAS_SMTP WHERE COD_EMPRESA = $cod_empresa";
	$arraySmtp = mysqli_query($connAdm->connAdm(), $sqlSmtp);

	if (mysqli_num_rows($arraySmtp) == 0) {

		$sqlSmtp = "SELECT DES_EMAIL FROM SENHAS_SMTP WHERE COD_EMPRESA = 3";
		$arraySmtp = mysqli_query($connAdm->connAdm(), $sqlSmtp);
	}

	$qrEmail = mysqli_fetch_assoc($arraySmtp);

	include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
	include '../externo/email/envio_sac.php';

	$texto = 'NOME: ' . $nome .
		'<br>EMAIL: ' . $fromMail .
		'<br>ASSUNTO: ' . $assunto .
		'<br>Menssagem :<br>' . $mensagem;

	// echo $qrEmp[NOM_FANTASI];

	$email['email1'] = $qrEmail['DES_EMAIL'];
	$email['email5'] = $qrEmailCopia['DES_EMAIL'];

	$retorno = fnsacmail(
		$email,
		'Suporte Marka',
		"<html>" . $texto . "</html>",
		"[$assunto] Fale Conosco - APP",
		"APP $qrEmp[NOM_FANTASI]",
		$connAdm->connAdm(),
		connTemp($cod_empresa, ""),
		3
	);
	// echo "<pre>";
	// print_r($retorno);
	// echo "</pre>";

	echo "Sua mensagem foi enviada. Obrigado!";
} else {

	echo 'A verificação do "Não sou um robô" <b>falhou</b> ou pode ter <b>expirado</b>. Por favor, tente novamente.';
}
