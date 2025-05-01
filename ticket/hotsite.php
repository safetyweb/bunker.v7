<?php
include "../_system/_functionsMain.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//echo "<h3>".$_GET['param']."</h3>";

// $url = $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

// $tipo = explode("#", $url);

// echo "<pre>";
// echo($_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
// print_r($tipo);
// echo "</pre>";

// $instaCad = '';

// if($tipo[1] != ""){
// 	$instaCad = $tipo[1];
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {

		$cod_cliente = 0;
		$log_totem = 'N';
	} else {

		$_SESSION['last_request'] = $request;
		$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['idc']));
		$log_totem = fnLimpaCampo(fnDecode($_POST['t']));
		$rand = $_POST['rand'];
	}
}

// echo $log_totem;
// echo $cod_cliente;
// exit();

//echo fnDebug('true');

//busca dados da url	
if (fnLimpacampo($_GET['param']) != "") {
	//busca codigo da empresa
	$cod_busca = strtolower(fnLimpacampo($_GET['param']));
	$sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$cod_busca' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaCodEmpresa = mysqli_fetch_assoc($arrayQuery);
	//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
	$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
	//$nom_fantasi = $qrBuscaCodEmpresa['NOM_FANTASI'];

	if (isset($qrBuscaCodEmpresa)) {
		$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
		$siteGo = "OK";
	} else {
		$siteGo = "NOK";
	}
}

//se carrega site
if ($siteGo == "OK") {

	//fnEscreve($cod_empresa);

	//busca nome da empresa
	$sql2 = "SELECT NOM_FANTASI, COD_CHAVECO, LOG_BLOQUEIAPJ from EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2);
	$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);
	$nom_fantasi = $qrBuscaDadosEmpresa['NOM_FANTASI'];
	$cod_chaveco = $qrBuscaDadosEmpresa['COD_CHAVECO'];
	$bloqueiaPj = $qrBuscaDadosEmpresa['LOG_BLOQUEIAPJ'];

	if (isset($_GET['preview'])) {
		$table = 'SITE_EXTRATO_PREVIEW';
		//fnEscreve('preview');
	} else {
		$table = 'SITE_EXTRATO';
	}

	//busca dados da tabela
	$sql = "SELECT * FROM $table WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		//fnEscreve("entrou if");
		$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
		$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
		$cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];
		$des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
		$des_icolog = $qrBuscaSiteExtrato['DES_ICOLOG'];
		$des_banner = $qrBuscaSiteExtrato['DES_BANNER'];
		$des_email = $qrBuscaSiteExtrato['DES_EMAIL'];
		$log_home = $qrBuscaSiteExtrato['LOG_HOME'];
		$destino_home = $qrBuscaSiteExtrato['DESTINO_HOME'];
		$log_vantagem = $qrBuscaSiteExtrato['LOG_VANTAGEM'];
		$txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
		$log_regula = $qrBuscaSiteExtrato['LOG_REGULA'];
		$txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
		$log_lojas = $qrBuscaSiteExtrato['LOG_LOJAS'];
		$txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
		$log_faq = $qrBuscaSiteExtrato['LOG_FAQ'];
		$txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
		$log_premios = $qrBuscaSiteExtrato['LOG_PREMIOS'];
		$txt_premios = $qrBuscaSiteExtrato['TXT_PREMIOS'];
		$log_extrato = $qrBuscaSiteExtrato['LOG_EXTRATO'];
		$txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
		$log_contato = $qrBuscaSiteExtrato['LOG_CONTATO'];
		$log_cadastro = $qrBuscaSiteExtrato['LOG_CADASTRO'];
		$txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
		$cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
		$cor_barra = $qrBuscaSiteExtrato['COR_BARRA'];
		$cor_txtbarra = $qrBuscaSiteExtrato['COR_TXTBARRA'];
		$cor_site = $qrBuscaSiteExtrato['COR_SITE'];
		$cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
		$cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
		$cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
		$cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
		$cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
		$cor_txtbotao = $qrBuscaSiteExtrato['COR_TXTBOTAO'];
		$des_vantagem = $qrBuscaSiteExtrato['DES_VANTAGEM'];
		$des_urlios = $qrBuscaSiteExtrato['DES_URLIOS'];
		$des_urlandro = $qrBuscaSiteExtrato['DES_URLANDRO'];
		$ico_bloco1 = $qrBuscaSiteExtrato['ICO_BLOCO1'];
		$ico_bloco2 = $qrBuscaSiteExtrato['ICO_BLOCO2'];
		$ico_bloco3 = $qrBuscaSiteExtrato['ICO_BLOCO3'];
		$tit_bloco1 = $qrBuscaSiteExtrato['TIT_BLOCO1'];
		$des_bloco1 = $qrBuscaSiteExtrato['DES_BLOCO1'];
		$tit_bloco2 = $qrBuscaSiteExtrato['TIT_BLOCO2'];
		$des_bloco2 = $qrBuscaSiteExtrato['DES_BLOCO2'];
		$tit_bloco3 = $qrBuscaSiteExtrato['TIT_BLOCO3'];
		$des_bloco3 = $qrBuscaSiteExtrato['DES_BLOCO3'];
		$des_regras = $qrBuscaSiteExtrato['DES_REGRAS'];
		$des_programa = $qrBuscaSiteExtrato['DES_PROGRAMA'];
		$log_sobre = $qrBuscaSiteExtrato['LOG_SOBRE'];
		$txt_sobre = $qrBuscaSiteExtrato['TXT_SOBRE'];
		$txt_cadastro = $qrBuscaSiteExtrato['TXT_CADASTRO'];
		$des_sobre = $qrBuscaSiteExtrato['DES_SOBRE'];
		$tam_texto = $qrBuscaSiteExtrato['TAM_TEXTO'];
		$tp_ordenac = $qrBuscaSiteExtrato['TP_ORDENAC'];
		$log_contraste = $qrBuscaSiteExtrato['LOG_CONTRASTE'];

		// echo "_".$log_home;
		// exit();

		if ($log_home == 'S') {
			if ($destino_home != '') {
				$destino = "href='" . $destino_home . "' target='_blank'";
			} else {
				$destino = "href='#home'";
			}
		} else {
			$destino = "href='#home'";
		}
	}

	if ($cod_dominio == 2) {
		$extensaoDominio = ".fidelidade.mk";
	} else {
		$extensaoDominio = ".mais.cash";
	}

	if (!verifica_https()) {
		header("Location:https://" . $des_dominio . $extensaoDominio);
	}

	$sqlLgpd = "SELECT LOG_LGPD FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";
	$arrayLgpd = mysqli_query(connTemp($cod_empresa, ''), $sqlLgpd);
	$qrLgpd = mysqli_fetch_assoc($arrayLgpd);

	$log_lgpd = $qrLgpd['LOG_LGPD'];

	if ($log_totem == 'S' && $cod_cliente != 0) {
		$sqlCli = "SELECT NUM_CARTAO, DES_SENHAUS FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
		$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);
		$qrCli = mysqli_fetch_assoc($arrayCli);

		$keyCli = fnLimpaDoc($qrCli['NUM_CARTAO']);
		$senha = fnDecode($qrCli['DES_SENHAUS']);
	}

	// busca dados campanha 22
	$sql = "SELECT * FROM campanha 
							WHERE 
							    LOG_ATIVO = 'S' 
							    AND COD_EMPRESA = $cod_empresa 
							    AND TIP_CAMPANHA IN (22,23) 
							    AND (
							        LOG_CONTINU = 'S' 
							        OR (
							            LOG_CONTINU = 'N' 
							            AND (
							                DAT_FIM > CURRENT_DATE 
							                OR (DAT_FIM >= CURRENT_DATE AND HOR_FIM >= CURRENT_TIME)
							            )
							        )
							    )";
	//echo $sql;
	$array = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$arrayCampanhas22 = "";
	while ($qrCampanha = mysqli_fetch_assoc($array)) {


		$sqlCampanha = "SELECT * FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = " . $qrCampanha['COD_CAMPANHA'] . " AND COD_EXCLUSA IS NULL";
		$arrayCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

		while ($qrCamp = mysqli_fetch_assoc($arrayCamp)) {
			$arrayCampanhas22 .= $qrCamp['DES_CHAVECAMP'] . ',' . $qrCamp['IMG_BANNERMAIN'] . ',' . $qrCamp['TXT_BANNERMAIN'] . ',' . $qrCamp['IMG_BANNERLOG'] . ';';
		}
	}

?>

	<!DOCTYPE html>
	<html lang="pt-br">

	<head>
		<meta charset="utf-8">
		<title><?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?></title>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="_globalsign-domain-verification" content="yolf3ppAMrD3-asK1-tvhRmysHC9Xo3PTrzF5oDZev" />

		<link href="css/main.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">

		<!-- SISTEMA -->
		<link href="css/jquery-confirm.min.css" rel="stylesheet" />
		<link href="css/jquery.webui-popover.min.css" rel="stylesheet" />
		<link href="css/chosen-bootstrap.css" rel="stylesheet" />
		<link href="css/font-awesome.min.css" rel="stylesheet" />

		<!-- complement -->
		<link href="css/default.css" rel="stylesheet" />

		<?php
		if ($cod_empresa == 124) {
		?>
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-NFM46VG905"></script>
			<script>
				window.dataLayer = window.dataLayer || [];

				function gtag() {
					dataLayer.push(arguments);
				}
				gtag('js', new Date());
				gtag('config', 'G-NFM46VG905');
			</script>
		<?php

		}

		?>

		<!--
			<link href="../css/checkMaster.css" rel="stylesheet" />
			-->

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
		<!--[if lt IE 9]>
			  <script src="js/html5shiv.js"></script>
			<![endif]-->

	</head>

	<!--///////////////////////////////////////// PARALLAX BACKGROUND ////////////////////////////////////////-->
	<style>
		body {
			width: 100vw;
			background: #<?= $cor_site ?> !important;
		}

		section {
			padding-top: 50px;
			background: #<?= $cor_site ?> !important;
		}

		#header .navbar a.navbar-brand {
			padding: 8px 0 !important;
		}

		.input-chave {
			font-size: 36px;
		}

		.info-section-white {
			background: #<?= $cor_site ?> !important;
		}

		.WordSection1 {
			background: #<?= $cor_site ?> !important;
		}

		.navbar,
		.navbar-inner {
			background: #<?= $cor_barra ?> !important;
		}

		.borda-responsiva {
			border-radius: 13px 0px 0px 13px;
		}

		#parallax {
			height: 652px;
			width: 100vw;
			top: 72px;
			position: fixed;
			background: url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_banner; ?>') top no-repeat;
			background-size: contain;
		}

		.logo-img {
			height: 90px !important;
		}

		.avatarLogin {
			height: 30%;
			width: 30%;
			margin-top: 0px;
			margin-bottom: 0px;
			margin-left: auto;
			margin-right: auto;
		}

		#prog p span {
			font-size: 18px;
		}

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 36px;
		}

		.f24 {
			font-size: 24px;
		}

		#features {
			padding: unset;
		}

		.lead {
			font-size: 24px !important;
		}

		.mobile {
			display: block;
			height: 185px !important;
		}

		.desktop {
			display: none;
		}

		@media only screen and (device-width: 320px) and (orientation: portrait) {

			html,
			body {
				overflow-x: hidden;
			}

			#parallax {
				height: 240px;
				width: 100vw;
				/* top: 150px!important;*/
				position: fixed;
				background: url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_banner; ?>') bottom no-repeat;
				background-size: contain;
			}

			.logo-img {
				height: auto !important;
				width: 80px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}

			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			section {
				padding-top: 0;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		/* (320x480) iPhone (Original, 3G, 3GS) */
		@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		/* (320x480) Smartphone, Portrait */
		@media only screen and (device-width: 320px) and (orientation: portrait) {
			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		/* (320x480) Smartphone, Landscape */
		@media only screen and (device-width: 480px) and (orientation: landscape) {
			.borda-responsiva {
				border-radius: 13px 0px 0px 13px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}


		}

		/* (1024x768) iPad 1 & 2, Landscape */
		@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
			.borda-responsiva {
				border-radius: 13px 0px 0px 13px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		/* (1280x800) Tablets, Portrait */
		@media only screen and (max-width: 800px) and (orientation : portrait) {
			body {
				background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: 103%;
			}

			.navbar img {
				margin-top: -10px;
			}

			.logo-img {
				height: auto !important;
				width: 90px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}


		}

		/* (768x1024) iPad 1 & 2, Portrait */
		@media only screen and (max-width: 768px) and (orientation : portrait) {
			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			.logo-img {
				height: auto !important;
				width: 90px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		/* (2048x1536) iPad 3 and Desktops*/
		@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
			.borda-responsiva {
				border-radius: 13px 0px 0px 13px;
			}

			.logo-img {
				height: auto !important;
				width: 120px;
				margin-top: 15px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 36px;
			}

			.avatarLogin {
				height: 100%;
				width: 100%;
				margin-top: 10px;
				margin-left: auto;
				margin-right: auto;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 24px !important;
			}

			.desktop {
				display: block;
			}

			.mobile {
				display: none;
			}

		}

		@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			.logo-img {
				height: auto !important;
				width: 90px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			.avatarLogin {
				height: 100%;
				width: 100%;
				margin-top: 10px;
				margin-left: auto;
				margin-right: auto;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}

		@media (max-height: 824px) and (max-width: 416px) {
			.borda-responsiva {
				border-radius: 13px 13px 0px 0px;
			}

			.logo-img {
				height: auto !important;
				width: 90px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}

			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}


		}

		/* (320x480) iPhone (Original, 3G, 3GS) */
		@media (max-device-width: 737px) and (max-height: 416px) {
			.borda-responsiva {
				border-radius: 13px 0px 0px 13px;
			}

			.logo-img {
				height: auto !important;
				width: 80px;
			}

			h1,
			h2,
			h3,
			h4,
			h5 {
				font-size: 24px;
				margin-bottom: 10px;
			}

			section {
				padding: 15px;
			}

			#hero {
				padding: 0;
				padding-bottom: 10px;
			}

			#prog p span,
			p {
				font-size: 14px;
			}

			.lead {
				font-size: 18px !important;
			}

			.input-chave {
				font-size: 23px;
			}


			.mobile {
				display: block;
				height: 185px !important;
			}

			.desktop {
				display: none;
			}

		}


		}

		#hero {
			/*background: transparent!important;*/
		}

		h1,
		h2,
		h3,
		h4,
		h5,
		h6,
		.h1,
		.h2,
		.h3,
		.h4,
		.h5,
		.h6 {
			color: #<?php echo $cor_titulos; ?>;
		}

		p,
		p.lead {
			color: #<?php echo $cor_textos; ?>;
		}

		.bottom-menu-inverse {
			background-color: #<?php echo $cor_rodapebg; ?>;
			color: #<?php echo $cor_rodape; ?>;
		}

		.fFooter {
			color: #<?php echo $cor_rodape; ?>;
		}

		.navbar .nav>li>a {
			color: #<?php echo $cor_txtbarra; ?>;
		}

		.btn-primary,
		.label {
			background-color: #<?php echo $cor_botao; ?>;
		}

		.btn-primary:hover,
		.label:hover {
			background-color: #<?php echo $cor_botaoon; ?>;
		}

		p {
			font-size: 12px;
			margin: 0;
			padding: 0 0 3px 0;
		}

		.f18 {
			font-size: 18px;
		}

		<?php if ($cod_empresa != 7) { ?>
		/* modal */
		/*.modal-dialog {
				width: 95%;
				max-width: 500px;
				margin-top: 10px;
				margin-bottom: 10px;
				max-height: 500px !important;
			}*/

		/*.modal-content {
				height: 700px;
			}	*/

		#popModal iframe {
			display: block;
			margin: 0 auto;
			overflow-y: auto !important;
		}

		/*.modal-body {
				position: relative;
				padding: 20px;
				height: 700px;
				overflow-y:auto;
			}*/

		.modal {
			overflow-y: auto;
		}

		<?php } ?>#contato-info {
			color: green;
			margin-top: 25px;
			text-align: center;
		}

		#cadastro {
			height: 1px !important;
		}

		.select .btn {
			width: 100%;
		}

		.btn {
			border-radius: 25px;
		}

		.btn-hollow {
			color: #<?php echo $cor_textos; ?>;
			background: #<?= $cor_site ?> !important;
			border: 2px solid #<?php echo $cor_botao; ?>;
			border-radius: 25px;
		}

		.btn-hollow:hover {
			color: #<?php echo $cor_txtbarra; ?>;
		}

		@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
			.desktop {
				display: block !important;
			}
		}
	</style>


	<!-- Scrollspy set in the body -->

	<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">
		<!-- <div id="parallax"></div> -->

		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->
		<section id="header">

			<nav class="navbar navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
					<div class="container">

						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

						<?php

						if ($cod_dominio == 2) {

						?>

							<!-- Logo goes here - replace the image with yours -->
							<a href="." class="navbar-brand"><!-- <div class="push20"></div> --><img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" class="logo-img img-responsive" width="120px" alt="logo_<?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?>" title="Home <?php echo $des_programa; ?>"></a>

						<?php
						} else {
						?>

							<a href="." class="navbar-brand"><!-- <div class="push20"></div> --><img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" class="logo-img img-responsive" width="120px" alt="logo_<?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?>" title="Home <?php echo $des_programa; ?>"></a>

						<?php
						}
						?>

						<div class="collapse navbar-collapse main-nav" id="navigation">

							<ul class="nav pull-right">
								<!-- Menu -->
								<li class='active'><a <?= $destino ?>>Home</a></li>
								<?php
								if ($log_sobre == "S") {
									echo "<li><a href='#sobre'>$txt_sobre</a></li>";
								}
								// if ($log_vantagem == "S"){echo "<li><a href='#features'>$txt_vantagem</a></li>";}
								if ($log_cadastro == "S" || $cod_empresa == 7) {
									if ($cod_empresa == 124) {
										echo '<li><a href="#cadastro" class="addBox" data-url="https://' . $des_dominio . $extensaoDominio . '/cadastrarSe2.do?codEmpresa=' . $cod_empresa . '&pop=true" data-title="Crie seu acesso" id="cad_cli">' . $txt_cadastro . '</a></li>';
									} else if ($cod_empresa == 7) {
										echo '<li><a href="#cadastro" id="BTN_CADASTRO" class="addBox" data-url="https://' . $des_dominio . $extensaoDominio . '/consulta_V2.do?id=' . fnEncode($cod_empresa) . '&pop=true" data-title="Crie seu acesso" id="cad_cli">' . $txt_cadastro . '</a></li>';
									} else {
										// echo '<li><a href="#cadastro" class="addBox" data-url="https://'.$des_dominio.$extensaoDominio.'/cadastrarSe.do?codEmpresa='.$cod_empresa.'&pop=true" data-title="Crie seu acesso" id="cad_cli">'.$txt_cadastro.'</a></li>';
										echo '<li><a href="#cadastro" id="BTN_CADASTRO" class="addBox" data-url="https://' . $des_dominio . $extensaoDominio . '/consulta_V2.do?id=' . fnEncode($cod_empresa) . '&pop=true" data-title="Crie seu acesso" id="cad_cli">' . $txt_cadastro . '</a></li>';
									}
								}
								if ($log_extrato == "S") {
									echo "<li><a href='#extrato'>$txt_extrato</a></li>";
								}
								if ($log_regula == "S") {
									echo "<li><a href='#info'>$txt_regula</a></li>";
								}
								if ($log_lojas == "S") {
									echo "<li><a href='#gallery'>$txt_lojas</a></li>";
								}
								if ($log_faq == "S") {
									echo "<li><a href='#faq'>$txt_faq</a></li>";
								}
								if ($log_premios == "S") {
									echo "<li><a href='#premios'>$txt_premios</a></li>";
								}
								if ($log_contato == "S") {
									echo "<li><a href='#contact'>$txt_contato</a></li>";
								}
								?>
								<!-- <li><a href=".">Sair</a></li>-->

							</ul>

						</div><!-- /nav-collapse -->
					</div><!-- /container -->
				</div><!-- /navbar-inner -->
			</nav>
		</section>
		<!--/////////////////////////////////////// BANNER ////////////////////////////////////////-->
		<?php
		if ($des_banner != "") {
		?>
			<section id="hero" style="height: unset;">
				<div class="container">
					<div class="row">
						<!-- <div class="col-md-6 intro">
					</div> -->
						<img id="img_banner" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_banner; ?>" class="img-responsive" width="100%" height="600px">
					</div>
				</div>
			</section>

		<?php //fnEscreve($log_vantagem); 
		}
		?>

		<!--/////////////////////////////////////// PROGRAMA ////////////////////////////////////////-->
		<?php
		if ($log_sobre == "S") {

			$backContraste = "";
			$txtContraste = "";

			if ($log_contraste == "S") {
				$backContraste = "background-color: #" . $cor_botao;
				$txtContraste = "color: #fff!important";
			}
		?>
			<section id="sobre">

				<div class="info-section-white">

					<div class="container" style="">

						<div class="col-md-12" style="border-radius: 13px;<?= $backContraste ?>">

							<header>
								<div class="push20"></div>
								<h2 style="<?= $txtContraste ?>"><?php echo $txt_sobre; ?></h2>
							</header>

							<div class="row">

								<div class="col-md-12" style="max-height:300px; overflow-y: auto;" id="prog">

									<?php echo html_entity_decode($des_sobre); ?>

								</div>

							</div><!-- /row -->

						</div>

					</div><!-- /container -->

				</div><!-- /info-section-white -->

			</section>
		<?php } ?>

		<!--/////////////////////////////////////// VANTAGEM ////////////////////////////////////////-->
		<?php if ($log_vantagem == "S") { ?>
			<section id="features">

				<div class="container">

					<header>
						<h3><?php echo $txt_vantagem; ?></h3>
						<p class="lead"><?php echo $des_vantagem; ?></p>
					</header>

					<div class="row">

						<!-- Feature Item 1 -->
						<div class="col-md-4 text-center">
							<div class="col-xs-12" style="border: 1px solid #DBDBDB; border-radius: 13px; min-height: 400px;">
								<div class="feature-icon">
									<?php
									if (strlen(strstr($ico_bloco1, 'icons/')) > 0) { ?>
										<img src="images/<?php echo $ico_bloco1; ?>" alt="" title="" />
									<?php } else { ?>
										<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $ico_bloco1; ?>" alt="" title="" />
									<?php } ?>
								</div>
								<h4 class="f21"><?php echo $tit_bloco1; ?></h4>
								<p class="f18"><?php echo $des_bloco1; ?></p>
							</div>
							<div class="push5"></div>
						</div>

						<!-- Feature Item 2 -->
						<div class="col-md-4 text-center">
							<div class="col-xs-12" style="border: 1px solid #DBDBDB; border-radius: 13px; min-height: 400px;">
								<div class="feature-icon">
									<?php
									if (strlen(strstr($ico_bloco2, 'icons/')) > 0) { ?>
										<img src="images/<?php echo $ico_bloco2; ?>" alt="" title="" />
									<?php } else { ?>
										<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $ico_bloco2; ?>" alt="" title="" />
									<?php } ?>
								</div>
								<h4 class="f21"><?php echo $tit_bloco2; ?></h4>
								<p class="f18"><?php echo $des_bloco2; ?></p>
							</div>
							<div class="push5"></div>
						</div>

						<!-- Feature Item 3 -->
						<div class="col-md-4 text-center">
							<div class="col-xs-12" style="border: 1px solid #DBDBDB; border-radius: 13px; min-height: 400px;">
								<div class="feature-icon">
									<?php
									if (strlen(strstr($ico_bloco3, 'icons/')) > 0) { ?>
										<img src="images/<?php echo $ico_bloco3; ?>" alt="" title="" />
									<?php } else { ?>
										<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $ico_bloco3; ?>" alt="" title="" />
									<?php } ?>
								</div>
								<h4 class="f21"><?php echo $tit_bloco3; ?></h4>
								<p class="f18"><?php echo $des_bloco3; ?></p>
							</div>
						</div>

					</div><!-- /row -->

					<?php
					if ($des_urlios != "" || $des_urlandro != "") {
						if ($cod_empresa == 103) {
					?>
							<!-- Facebook Pixel Code -->
							<script>
								! function(f, b, e, v, n, t, s) {
									if (f.fbq) return;
									n = f.fbq = function() {
										n.callMethod ?
											n.callMethod.apply(n, arguments) : n.queue.push(arguments)
									};
									if (!f._fbq) f._fbq = n;
									n.push = n;
									n.loaded = !0;
									n.version = '2.0';
									n.queue = [];
									t = b.createElement(e);
									t.async = !0;
									t.src = v;
									s = b.getElementsByTagName(e)[0];
									s.parentNode.insertBefore(t, s)
								}(window, document, 'script',
									'https://connect.facebook.net/en_US/fbevents.js');
								fbq('init', '264119538134831');
								fbq('track', 'PageView');
							</script>
							<noscript><img height="1" width="1" style="display:none"
									src="https://www.facebook.com/tr?id=264119538134831&ev=PageView&noscript=1" /></noscript>
							<!-- End Facebook Pixel Code -->
						<?php
						}
						?>

						<div class="push30"></div>
						<div class="row">
							<div class="col-md-12">
								<header>
									<p class="lead">APP exclusivo, baixe agora</p>
								</header>
							</div>
						</div>

						<div class="row">
							<div class="push10"></div>

							<?php

							if ($des_urlios != "" && $des_urlandro != "") {
							?>
								<div class="col-md-3 col-md-offset-3">
									<a href="<?= $des_urlandro ?>" target="_blank"><img class="img-responsive" width="200px" src="images/btn_googleplay.png" style="margin-left: auto; margin-right: auto;" /></a>
								</div>
								<div class="col-md-3">
									<a href="<?= $des_urlios ?>" target="_blank"><img class="img-responsive" width="200px" src="images/btn_appstore.png" style="margin-left: auto; margin-right: auto;" /></a>
								</div>
							<?php
							} else if ($des_urlios != "") {
							?>
								<div class="col-md-4 col-md-offset-4">
									<a href="<?= $des_urlios ?>" target="_blank"><img class="img-responsive" width="200px" src="images/btn_appstore.png" style="margin-left: auto; margin-right: auto;" /></a>
								</div>
							<?php
							} else {
							?>
								<div class="col-md-4 col-md-offset-4">
									<a href="<?= $des_urlandro ?>" target="_blank"><img class="img-responsive" width="200px" src="images/btn_googleplay.png" style="margin-left: auto; margin-right: auto;" /></a>
								</div>
							<?php
							}
							?>
						</div>

					<?php
					}
					?>

				</div><!-- /container -->

			</section>
		<?php } //fnEscreve($log_regula)
		?>

		<!--/////////////////////////////////////// EXTRATO ////////////////////////////////////////-->
		<?php if ($log_extrato == "S") {
			if ($cod_empresa != 77) {
				if ($log_cadastro == "S") {
		?>

					<!-- <section id="cadastro"></section> -->

			<?php
				}
			}
			?>
			<a name="cadastro"></a>
			<section id="extrato" style="background-color: #fff;">

				<div class="container" id="containerExtrato">

					<div class="row" style="border: 1px solid #DBDBDB; border-radius: 13px;">

						<div class="col-md-4" style="margin: 0px; padding: 0px;">

							<?php

							$imgLogin = "images/icons/storage.svg";
							$imgClass = "";

							if ($des_icolog != "") {
								$imgLogin = "https://img.bunker.mk/media/clientes/" . $cod_empresa . "/" . $des_icolog;
								// $imgClass = "avatarLogin";
							}

							?>
							<img id="img_login" class="img-responsive borda-responsiva avatarLogin" src="<?php echo $imgLogin; ?>" width="100%" />

						</div>

						<div class="col-md-8">

							<div class="push30"></div>

							<div class="row">
								<div class="col-md-12">
									<h2>Atualizar cadastro/<?php echo $txt_extrato; ?></h2>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<p class="lead f21">
										Faça login e atualize seu cadastro ou visualize seu extrato!
									</p>
								</div>
							</div>

							<div style="display:none" class="row camp22">
								<div class="col-md-12">
									<p id="txt_login" class="lead f21">
									</p>
								</div>
							</div>

							<div class="row">

								<form id="contatoForm">
									<!-- <input type="text" id="cpf" name="cpf" class="form-control input-hg cpfcnpj" placeholder="Seu CPF/CNPJ" /> -->
									<?php

									switch ($cod_chaveco) {

										case 2:

									?>
											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">Cartão</label> -->
													<input type="number" placeholder="SEU CARTÃO/CPF" style="color: #34495E!important;" class="form-control input-hg input-lg text-center campo2 input-chave int" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" required>
													<div class="help-block with-errors">Caso nao possua um número de cartão válido do programa, digite o seu CPF (somente números)</div>
												</div>
											</div>

											<input type="hidden" class="campo1" value="">
											<input type="hidden" class="campo3" value="">
											<input type="hidden" class="campo4" value="">

										<?php

											break;

										case 3:

										?>
											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">Celular</label> -->
													<input type="tel" style="color: #34495E!important;" placeholder="seu celular" class="form-control input-hg input-lg text-center campo2 input-chave sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<input type="hidden" class="campo1" value="">
											<input type="hidden" class="campo3" value="">
											<input type="hidden" class="campo4" value="">

										<?php

											break;

										case 4:

										?>
											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">Código Externo</label> -->
													<input type="tel" style="color: #34495E!important;" placeholder="SEU CÓDIGO" class="form-control input-hg input-lg text-center campo2 input-chave" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<input type="hidden" class="campo1" value="">
											<input type="hidden" class="campo3" value="">
											<input type="hidden" class="campo4" value="">

										<?php

											break;

										case 5:

										?>
											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">CPF/CNPJ</label> -->
													<input type="tel" style="color: #34495E!important;" placeholder="seu cpf/cnpj" class="form-control input-hg input-lg text-center campo1 input-chave cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<!-- <div class="push10"></div> -->

											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">Cartão</label> -->
													<input type="tel" style="color: #34495E!important;" placeholder="seu cartão" class="form-control input-hg input-lg text-center campo2 input-chave" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" data-error="ou este" maxlenght="10" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<input type="hidden" class="campo3" value="">
											<input type="hidden" class="campo4" value="">

										<?php

											break;

										default:

											$label = "seu cpf/cnpj";
											$charLenght = "18";

											if ($bloqueiaPj == 'S') {
												$label = "seu cpf";
												$charLenght = "14";
											}

										?>
											<div class="col-md-12 col-xs-12">
												<div class="form-group">
													<!-- <label for="inputName" class="control-label required">CPF/CNPJ</label> -->
													<input type="tel" style="color: #34495E!important;" placeholder="<?= $label ?>" class="form-control input-hg input-lg text-center campo1 input-chave cpfcnpj" maxlength="<?= $charLenght ?>" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<input type="hidden" class="campo2" value="">
											<input type="hidden" class="campo3" value="">
											<input type="hidden" class="campo4" value="">

									<?php

											break;
									}

									?>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<!-- <label for="inputName" class="control-label required">Senha</label> -->
											<input type="password" style="color: #34495E!important;" placeholder="sua senha" class="form-control input-hg input-lg text-center input-chave" name="senha" id="senha" maxlength="6" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<!-- <input type="password" maxlength="8" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha" /> -->
									<div class="col-md-8">


										<?php
										if ($cod_empresa == 124) {
										?>
											<a href="javascript:void(0)" class="btn btn-primary btn-hollow btn-block addBox" id="cad_cli2" onclick="" data-url="cadastrarSe.do?codEmpresa=<?= $cod_empresa ?>&pop=true" data-title="Crie seu acesso">Primeiro acesso? Esqueceu a senha? <b style="color: #<?php echo $cor_botao; ?>;">Clique aqui</b>.</a>
											<!-- <a style="cursor: pointer; color: #<?php echo $cor_botao; ?>;" class="addBox" id="cad_cli2" onclick="" data-url="cadastrarSe2.do?codEmpresa=<?= $cod_empresa ?>&pop=true" data-title="Crie seu acesso"><b>Clique aqui</b></a><br> -->
										<?php
										} else {
										?>
											<!-- <a style="cursor: pointer; color: #<?php echo $cor_botao; ?>;" class="addBox" data-url="cadastrarSe.do?codEmpresa=<?php echo $cod_empresa ?>&pop=true" data-title="Crie sua senha">Clique aqui</a><br> -->
											<a href="javascript:void(0)" class="btn btn-primary btn-hollow btn-block addBox" id="cad_cli2" data-url="https://<?= $des_dominio . $extensaoDominio ?>/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&pop=true" data-title="Crie seu acesso">Primeiro acesso? Esqueceu a senha? <b style="color: #<?php echo $cor_botao; ?>;">Clique aqui</b>.</a>
										<?php
										}
										?>
										<!-- Ainda não é cadastrado? <br>
		                        		<a style="cursor: pointer" class="addBox" data-url="cadastrarSe2.do?codEmpresa=<?php echo $cod_empresa ?>&pop=true" data-title="Crie seu acesso" id="cad_cli">Cadastre-se</a><span class="f14"> (beta)</span> -->

									</div>

									<div class="col-md-4">

										<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" id="btLogin">Fazer login</button>

									</div>

									<div class="push10"></div>
									<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
									<div class="errorInat" style="color: red; text-align: center; display: none">Este cliente foi desativado em nossas bases.</div>
									<div class="col-xs-12 text-center ">

									</div>
									<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
									<input type="hidden" name="ARRAYCAMPANHAS22" id="ARRAYCAMPANHAS22" value="<?= $arrayCampanhas22 ?>">
								</form>

							</div>

						</div>

					</div>

				</div>

			</section>

		<?php
		} ?>

		<!--/////////////////////////////////////// REGULAMENTO ////////////////////////////////////////-->
		<section id="info">
			<div class="row text-center" style="margin: 10px 15px;">

				<header>
					<h3><?php echo $txt_regula; ?></h3>
				</header>

				<?php

				if ($log_lgpd == "S") {

					$sqlTermos = "SELECT COD_TERMO, NOM_TERMO, LOG_ATIVO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 ASC LIMIT 3";
					$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

					while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {

						if ($qrTermos['LOG_ATIVO'] == 'S') {

				?>

							<a href="javascript:void(0)" class="btn btn-primary btn-hollow addBox" data-url="infoBox.do?id=<?= fnEncode($cod_empresa) ?>&opcao=<?= fnEncode($qrTermos['COD_TERMO']) ?>&pop=true" data-title="<?= $qrTermos['NOM_TERMO'] ?>"><?= $qrTermos['NOM_TERMO'] ?></a>

						<?php

						}
					}
				} else {

					if ($log_regula == "S") {

						?>

						<a href="javascript:void(0)" class="btn btn-primary btn-hollow addBox" data-url="infoBox.do?id=<?= fnEncode($cod_empresa) ?>&opcao=<?= fnEncode(0) ?>&pop=true" data-title="<?php echo $txt_regula; ?>"><?php echo $txt_regula; ?></a>

				<?php

					}
				}

				?>

			</div>
		</section>
		<!--/////////////////////////////////////// GALLERY SECTION ////////////////////////////////////////-->
		<?php if ($log_lojas == "S") { ?>
			<section id="gallery" style="background-color: #fff;">

				<div class="container">

					<header>
						<h3><?php echo $txt_lojas; ?></h3>
						<p class="lead">Encontre nossa unidade mais próxima de você.</p>
					</header>

					<!--////////// fitros //////////-->
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-3">
							<div id="filtersGrupo" class="gallery-filter">
								<select data-placeholder="Todas as Regiões" name="GRUPO" id="GRUPO" class="chosen-select-deselect">
									<option value="*">Todas as Regiões</option>
									<?php
									$safe_word = array("-", " ");

									$sql = "select * from regiao_grupo where cod_empresa = $cod_empresa order by des_tiporeg";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

									$count = 0;
									while ($qrLista = mysqli_fetch_assoc($arrayQuery)) { ?>
										<option value=".<?php echo $qrLista['COD_TIPOREG']; ?>"><?php echo $qrLista['DES_TIPOREG']; ?></option>

									<?php }	 ?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div id="filters" class="gallery-filter">
								<select data-placeholder="Todas as Unidades" name="LOJAS" id="LOJAS" class="chosen-select-deselect">
									<option value="*">Todas as Unidades</option>
									<?php
									$safe_word = array("-", " ");

									$sql1 = "select distinct(NOM_CIDADEC) from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and LOG_ESTATUS = 'S' and LOG_ATIVOHS = 'S' AND DAT_EXCLUSA IS NULL order by NOM_CIDADEC ";
									$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);

									$count = 0;
									while ($qrListaUniBairros = mysqli_fetch_assoc($arrayQuery1)) { ?>
										<option value=".<?php echo str_replace($safe_word, "_", $qrListaUniBairros['NOM_CIDADEC']); ?>"><?php echo $qrListaUniBairros['NOM_CIDADEC']; ?></option>

									<?php }	 ?>
								</select>
								<!-- <select id="LOJAS">
								<option value="*">Todas as Unidades</option>
								<?php
								$safe_word = array("-", " ");

								$sql1 = "select distinct(NOM_CIDADEC) from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and LOG_ESTATUS = 'S' and LOG_ATIVOHS = 'S' AND DAT_EXCLUSA IS NULL order by NOM_CIDADEC ";
								$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);

								$count = 0;
								while ($qrListaUniBairros = mysqli_fetch_assoc($arrayQuery1)) { ?>														  
								<option value=".<?php echo str_replace($safe_word, "_", $qrListaUniBairros['NOM_CIDADEC']); ?>"><?php echo $qrListaUniBairros['NOM_CIDADEC']; ?></option>

								<?php }	 ?>
							</select> -->
							</div>
						</div>
					</div>

					<div id="gallery-items">

						<?php
						$sql = "select * from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and LOG_ESTATUS = 'S' and LOG_ATIVOHS = 'S' AND DAT_EXCLUSA IS NULL order by NOM_FANTASI, NOM_CIDADEC ";
						$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

						$count = 0;
						while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) { ?>

							<div class="col-lg-3 col-md-4 col-xs-6 gallery-item gallery-popup all <?php echo str_replace($safe_word, "_", $qrListaUniVendas['NOM_CIDADEC']); ?> <?php echo $qrListaUniVendas['COD_TIPOREG']; ?> <?php echo $qrListaUniVendas['COD_TIPOREG']; ?><?php echo str_replace($safe_word, "_", $qrListaUniVendas['NOM_CIDADEC']); ?>">
								<b>▸ <?php echo $qrListaUniVendas['NOM_FANTASI']; ?> </b> <br />
								<?php if ($qrListaUniVendas['NUM_TELEFON'] != "") {
									echo $qrListaUniVendas['NUM_TELEFON'] . "<br/>";
								} ?>
								<?php
								$celLimp = str_replace(" ", "", str_replace("-", "", str_replace(")", "", str_replace("(", "", $qrListaUniVendas['NUM_WHATSAPP']))));
								if ($qrListaUniVendas['NUM_WHATSAPP'] != "") {
									echo "<a href='https://api.whatsapp.com/send?phone=55" . $celLimp . "' target='_blank'>" . $qrListaUniVendas['NUM_WHATSAPP'] . "</a>&nbsp;<img src='images/whats_icon.png'/> <br/>";
								} ?>
								<?php if ($qrListaUniVendas['DES_ENDEREC'] != "") {
									echo $qrListaUniVendas['DES_ENDEREC'] . ", " . $qrListaUniVendas['NUM_ENDEREC'] . "<br/>";
								} ?>
								<?php if ($qrListaUniVendas['DES_BAIRROC'] != "") {
									echo $qrListaUniVendas['DES_BAIRROC'] . "<br/>";
								} ?>
								<?php if ($qrListaUniVendas['NOM_CIDADEC'] != "") {
									echo $qrListaUniVendas['NOM_CIDADEC'] . " - " . $qrListaUniVendas['COD_ESTADOF'] . "<br/>";
								} ?>
								<?php if ($qrListaUniVendas['DES_HORATEND'] != "") {
									echo "<small>" . $qrListaUniVendas['DES_HORATEND'] . "</small> <br/>";
								} ?>
								<?php if ($qrListaUniVendas['LOG_DELIVERY'] == "S") {
									echo "<p class='label' style='background: url(images/delivery.png) no-repeat center left 10px #E5E8E8; background-size: 70px; padding: 10px 15px 10px 85px; color: #616A6B;'><b>DELIVERY</b></p><br/>";
								} else {
									echo "<div class='push20'></div>";
								} ?>
							</div>

						<?php }	 ?>

					</div>

				</div><!-- /container -->

			</section>
		<?php } ?>

		<!--/////////////////////////////////////// FAQ SECTION ////////////////////////////////////////-->
		<?php if ($log_faq == "S") { ?>
			<section id="faq" style="background-color: #fff;">

				<div class="container">

					<header>
						<h3><?php echo $txt_faq; ?></h3>
						<p class="lead">Dúvidas sobre o programa? Veja na lista abaixo as dúvidas mais comuns.</p>
					</header>

					<div class="row">
						<!--////////// Accordion Toggle //////////-->
						<div class="panel-group" id="accordion">

							<?php

							$sql = "select * from PERGUNTAS WHERE COD_EMPRESA = $cod_empresa order by NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

							$count = 0;
							while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {
								$count++;
							?>

								<div class="col-md-6">

									<!-- PANEL 1 -->
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $count; ?>">
													<?php echo $qrBuscaFAQ['DES_PERGUNTA']; ?>
												</a>
											</h4>
										</div>

										<div id="collapse<?php echo $count; ?>" class="panel-collapse collapse">
											<div class="panel-body">
												<p><?php echo htmlspecialchars_decode($qrBuscaFAQ['DES_RESPOSTA']); ?></p>
											</div>
										</div>
									</div>

								</div><!-- /col-md-6 -->

							<?php
							}

							?>


						</div>
						<!--////////// end of Accordion Toggle //////////-->


					</div>
				</div>

			</section>
		<?php }  //fnEscreve($log_premios)
		?>

		<!--/////////////////////////////////////// PREMIOS ////////////////////////////////////////-->
		<?php if ($log_premios == "S") { ?>
			<section id="premios" style="background-color: #fff;">

				<div class="container">

					<header>
						<h3><?php echo $txt_premios; ?></h3>

						<?php // if ($cod_empresa != 91){ 
						?>
						<p class="lead">Confira alguns dos prêmios exclusivos do programa.</p>
						<?php // } else { 
						?>
						<!-- <p class="lead">Acesse nosso site de prêmios para <b>trocar seus pontos</b>.</p>
					<br/><br/>
					<a href="http://markapontos.com.br/default.asp?key=dzm" target="_blank" class="btn btn-hg btn-primary btn-embossed text-center">Acessar Site de Prêmios <span class="fui-arrow-right"></span> </a> -->
						<?php // } 
						?>

					</header>



					<!--////////// Sortable Gallery Filters //////////-->
					<!--
            <div id="filters" class="gallery-filter">
                <select>
                    <option value="*">All</option>
                    <option value=".kittens">Kittens</option>
                    <option value=".food-drinks">Food & Drinks</option>
                    <option value=".urban">Urban</option>
                    <option value=".sports">Sports</option>
                </select>    
            </div>
			-->
					<!--////////// end of Sortable Gallery Filters //////////-->

					<style>
						figure {
							display: block;
							position: relative;
							overflow: hidden;
						}

						figcaption {
							position: absolute;
							background: rgba(255, 255, 255, 0.75);
							color: #000;
							padding: 8px 15px 0 15px;
							width: 100%;
							text-align: center;
							font-size: 14px;
							font-weight: bold;
							line-height: 12px;
							letter-spacing: -0.05rem;

							opacity: 0;
							bottom: 0;
							/*left: -30%;*/
							-webkit-transition: all 0.6s ease;
							-moz-transition: all 0.6s ease;
							-o-transition: all 0.6s ease;
						}

						figure:hover figcaption {
							opacity: 1;
							left: 0;
						}

						figure:hover:before {
							opacity: 0;
						}

						.cap-bot:before {
							bottom: 15px;
							left: 10px;
						}

						.cap-bot figcaption {
							left: 0;
							bottom: 0;
							opacity: 1;
						}
					</style>

					<!--			
			//transição pra direita
			.cap-left figcaption { bottom: 0; left: -30%; }
			.cap-left:hover figcaption { left: 0; }

			//transição pra esquerda
			.cap-right figcaption { bottom: 0; right: -30%; }
			.cap-right:hover figcaption { right: 0; }
			//transição pra baixo
			.cap-top figcaption { left: 0; top: -30%; }
			.cap-top:hover figcaption { top: 0; }

			//transição pra cima
			.cap-bot figcaption { left: 0; bottom: -30%;}
			.cap-bot:hover figcaption { bottom: 0; }			
			-->

					<div id="gallery-items">

						<?php
						$sql1 = "SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOPROMOCAO A 
					LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
					LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
					where A.COD_EMPRESA=$cod_empresa 
					AND A.COD_EXCLUSA=0 
					AND log_markapontos = 1
					AND LOG_ATIVO = 'S' order by A.NUM_PONTOS $tp_ordenac
					 ";

						//fnEscreve($sql1);
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql1);

						$count = 0;
						while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
							$count++;
							$des_imagem = $qrListaProduto['DES_IMAGEM'];
							$des_produto = $qrListaProduto['DES_PRODUTO'];
							$num_pontos = $qrListaProduto['NUM_PONTOS'];

							// fnEscreve($qrListaProduto['DES_IMAGEM']);

						?>

							<div class="col-lg-3 col-md-4 col-xs-6 gallery-item gallery-popup all kittens " style="padding: 15px;">
								<figure class="cap-bot">
									<a href="#" class="zoom">
										<?php if ($des_imagem != '') { ?>
											<div class="desktop" style='background: url("https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/produtospromo/<?php echo $des_imagem; ?>") center center no-repeat; background-size: auto 190px; width:257px; height: 190px;'></div>
											<div class="mobile"><img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/produtospromo/<?php echo $des_imagem; ?>"></div>
											<!-- <img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/produtospromo/<?php echo $des_imagem; ?>" style="width:257px; height: 190px;" alt="gallery-image" title="gallery-image" class="img-responsive" /> -->
											<figcaption>
												<?php echo $des_produto; ?> <br>
												<?php echo $num_pontos; ?> <small>Pontos</small>
											</figcaption>
										<?php } else {
										?>
											<div class="row text-center">
												<p style="font-weight: bolder; font-size: 12px;">
													<?php echo $des_produto; ?> <br>
													<?php echo $num_pontos; ?> <small>Pontos</small><br><br><br>
												</p>
											</div>
										<?php } ?>
									</a>
								</figure>
							</div>

						<?php
						}
						?>

					</div>

				</div><!-- /container -->

			</section>

		<?php } ?>

		<!--/////////////////////////////////////// CONTATO ////////////////////////////////////////-->
		<?php if ($log_contato == "S") { ?>
			<section id="contact">

				<div class="container">

					<header>
						<h3><?php echo $txt_contato; ?></h3>
						<p class="lead">Dúvidas? Reclamações? Envie sua mensagem para nós!</p>
					</header>

					<div class="row">

						<div class="col-md-6 col-md-offset-3">

							<!--////////// CONTACT FORM //////////-->
							<form>
								<select data-placeholder="Assunto" name="DES_ASSUNTO" id="DES_ASSUNTO" class="chosen-select-deselect">
									<!-- <option value=""></option> -->
									<option value="Dúvida">Dúvida</option>
									<option value="Reclamação">Reclamação</option>
									<option value="Divergência">Cadastro Divergente</option>
									<option value="Exclusão">Exclusão de Cadastro</option>
								</select>
								<div class="col-md-12 text-center text-success" id="msgExc" style="display: none;">
									<p class="f14"><b>Você</b> mesmo pode <b>excluir</b> seu cadastro após logado, na aba "Meu Cadastro".</p>
									<p class="f14">Caso queira que nós façamos para você, informe seu <b>cpf na mensagem</b>.</p>
									<p class="f14"><a href="#extrato"><b>Fazer login</b></a></p>
								</div>
								<input type="text" id="name" name="name" class="form-control input-hg" placeholder="Seu nome" />
								<input type="text" id="email" name="email" class="form-control input-hg" placeholder="Seu email" />
								<textarea class="form-control input-hg" rows="4" id="message" name="message" placeholder="Sua mensagem"></textarea>
								<div class="push20"></div>
								<center>
									<div class="g-recaptcha" data-sitekey="6LecLDUnAAAAABsd8i7O-PkbZBTIzCgvziBKGZMK"></div>
								</center>
								<button type="button" class="btn btn-primary btn-hg btn-block" name="btMensagem" id="btMensagem">Enviar Mensagem</button>
							</form>

							<div id="contato-info"></div>
							<div id="contact-error"></div>
							<!--////////// end CONTACT FORM ///////////-->

						</div><!-- /col-md-6-->

					</div>
				</div>

			</section>
		<?php } ?>

		<div style="height: 80px; clear:both;"></div>

		<!--//////////////////////////////////////// FOOTER SECTION ////////////////////////////////////////-->
		<section id="footer">
			<div class="bottom-menu-inverse">

				<div class="container">

					<div class="row">
						<div class="col-md-6">
							<p class="fFooter"><?php echo $nom_fantasi; ?> - &copy; Todos os direitos reservados. <br />
								Solução: &nbsp; <a href="https://marka.mk" class="fFooter" target="_blank">Marka Soluções em Fidelização</a>.</p>
						</div>

						<div class="col-md-6 social">
							<ul class="bottom-icons">
								<?php

								$sql = "select * 
											from rede_sociais RS
											inner join $connAdm->DB.tipo_redes_sociais TRD on TRD.COD_REDES = RS.COD_REDES
											WHERE 
											RS.COD_EMPRESA = $cod_empresa 
											ORDER BY TRD.NOM_REDES ";

								//fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$count = 0;
								while ($qrBuscaRedesSociais = mysqli_fetch_assoc($arrayQuery)) {
									$count++;
								?>

									<li>
										<a href="<?php echo $qrBuscaRedesSociais['DES_REDESOC']; ?>" target="_blank"><i class="fa  fa-lg <?php echo $qrBuscaRedesSociais['DES_ICONE']; ?>"></i></a>
									</li>

								<?php
								}

								?>

							</ul>
						</div>
					</div>

				</div><!-- /row -->
			</div><!-- /container -->

		</section>

		<!-- <form id="loginForm">
			<input type="hidden" name="">
		</form> -->

		<!-- modal -->
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<?php if ($cod_empresa != 7) { ?>
							<iframe id="ifrCad" frameborder="0" style="width: 100%; height: 600px !important"></iframe>
						<?php } else { ?>
							<iframe id="ifrCad" frameborder="0" style="height: 75vh; width: 100%;"></iframe>
						<?php } ?>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery.mask.min.js"></script>
		<script src="js/iframeResizer.min.js"></script>
		<script src="js/jquery-confirm.min.js"></script>

		<script type="text/javascript">
			let idc = "<?= $cod_cliente ?>",
				log_totem = "<?= $log_totem ?>",
				bloqueiaPj = "<?= $bloqueiaPj ?>";

			$(document).ready(function() {

				let url = window.location.href,
					codEmpresa = "<?= $cod_empresa ?>";

				instaCad = url.split("#");

				if (instaCad[1] != "" && instaCad[1] == "cadastre-se") {

					popLink = "";
					popTitle = "Crie seu acesso";

					if (codEmpresa == 124) {
						popLink = "cadastrarSe.do?codEmpresa=<?= $cod_empresa ?>&pop=true";
					} else {
						popLink = "https://<?= $des_dominio . $extensaoDominio ?>/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&pop=true";
					}

					// console.log(popLink);

					$(".modal iframe").not('#popModalNotifica iframe').attr({
						'src': popLink
					});
					if (popTitle) {
						$(".modal-title").not('#popModalNotifica .modal-title').text(popTitle);
					} else {
						$(".modal-title").not('#popModalNotifica .modal-title').text("");
					}

					$('.modal').not('#popModalNotifica').appendTo("body").modal('show');
					// scrollTop: $("#extrato").offset().top
					document.getElementById('extrato').scrollIntoView({
						behavior: 'smooth'
					});

				} else if (instaCad[1] != "" && instaCad[1] == "cadastro") {
					// scrollTop: $("#extrato").offset().top
					document.getElementById('extrato').scrollIntoView({
						behavior: 'smooth'
					});
				}


				//verificando campanha tipo 22 adicionado por Lucas 26/04/2024

				var arrayCampanha22 = $('#ARRAYCAMPANHAS22').val();
				var array_campanhas = arrayCampanha22.split(';');

				for (var i = 0; i < array_campanhas.length; i++) {
					var teste = array_campanhas[i].split(',');
					var chaveCamp = teste[0].replace('#', "");
					var banner = teste[1];
					var txt = teste[2];
					var bannerLogin = teste[3];

					//console.log(array_campanhas[i]);

					if (chaveCamp == instaCad[1]) {
						$('.camp22').show();
						$('#txt_login').text(txt);

						//Banner principal
						if (banner != "") {
							let linkBanner = "https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/" + banner;
							$('#img_banner').attr('src', linkBanner);
						}

						//Banner login
						if (bannerLogin != "") {
							let linkLogin = "https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/" + bannerLogin;
							$('#img_login').attr('src', linkLogin);
						}


						//adicionando chavecampanha na abertura do modal de cadastro
						let linkCampanha = "https://<?= $des_dominio . $extensaoDominio ?>/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&pop=true&campanha=" + instaCad[1];
						$('#cad_cli2').attr('data-url', linkCampanha);


						//abrindo modal de cadastro ao carregar tela 
						popTitle = "Crie seu acesso";
						$(".modal iframe").not('#popModalNotifica iframe').attr({
							'src': linkCampanha
						});
						if (popTitle) {
							$(".modal-title").not('#popModalNotifica .modal-title').text(popTitle);
						} else {
							$(".modal-title").not('#popModalNotifica .modal-title').text("");
						}

						$('.modal').not('#popModalNotifica').appendTo("body").modal('show');
						document.getElementById('extrato').scrollIntoView({
							behavior: 'smooth'
						});

						break;
					}
				}

				//fim verifica campanha 22

				if (log_totem == 'S' && idc != 0) {

					// var pCpf = "<?= $cpf ?>";

					$.ajax({
						type: "POST",
						url: "ajxLogin.do",
						data: {
							KEY_NUM_CARTAO: "<?= $keyCli ?>",
							senha: "<?= $senha ?>",
							COD_EMPRESA: "<?= $cod_empresa ?>"
						},
						success: function(msg) {
							if (msg.trim() != 'sem_resultado' && msg.trim() != 'inativo') {
								$('#containerExtrato').html(msg);
								// $("#BTN_CADASTRO").attr("data-url","https://"+'<?= $des_dominio . $extensaoDominio ?>'+"/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&idc="+pCpf+"&pop=true");
							} else if (msg.trim() == 'inativo') {
								$('.errorInat').show();
							} else {
								$('.errorLogin').show();
							}

							$('html, body').animate({
								scrollTop: $("#extrato").offset().top
							}, 1000);
							//console.log(msg);
							// $("#extrato").click();
						}
					});

				}

				// <?php if ($cod_empresa != 7) { ?>

				// $(".addBox").click(function(){
				// 	if($(this).attr("id") == "cad_cli" || $(this).attr("id") == "cad_cli2"){
				// 		$('#popModal').find('.modal-content').css({
				//               'height':'80vh',
				//               'marginLeft':'auto',
				//               'marginRight':'auto'
				//        });
				// 		$('#popModal').find('iframe').css({
				//               'height':'150vh'
				//        });
				// 	}else{
				// 		$('#popModal').find('.modal-content').css({
				//               'height':'670px'
				//        });
				// 	   $('#popModal').find('iframe').css({
				//               'height':'600px'
				//        });
				// 	}
				// });

				// <?php } ?>

				// $('.modal').on('hidden.bs.modal', function () {
				// 	location.reload();
				// } );

				var SPMaskBehavior = function(val) {
						return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
					},
					spOptions = {
						onKeyPress: function(val, e, field, options) {
							field.mask(SPMaskBehavior.apply({}, arguments), options);
						}
					};

				$('.sp_celphones').mask(SPMaskBehavior, spOptions);

				$(document).keypress(function(event) {
					var keycode = (event.keyCode ? event.keyCode : event.which);
					if (keycode == '13') {
						$('#btLogin').click();
					}
				});

				$('#DES_ASSUNTO').change(function() {
					if ($(this).val() == 'Exclusão') {
						$("#msgExc").fadeIn('fast');
					} else {
						$("#msgExc").fadeOut('fast');
					}
				});


				$('#filtersGrupo').change(function() {
					var grupo = $('#GRUPO').val();
					var cod_empresa = '<?php echo $cod_empresa ?>';

					$.ajax({
						method: 'POST',
						url: 'ajxBuscaLoja.php',
						data: {
							GRUPO: grupo,
							COD_EMPRESA: cod_empresa
						},
						success: function(data) {
							//console.log(data);
							$('#LOJAS').html(data);
						},
						error: function() {
							aletr('erro');
						}
					});
					//alert($('#GRUPO').val());
				});

				var anchor = "<?php echo fnLimpacampo($_GET['tab']); ?>";
				if (anchor != "" && anchor != "cadastre-se") {
					var aTag = $("#" + anchor);
					$('html,body').animate({
						scrollTop: (aTag.position().top - 0)
					}, 'slow');
				}

				//modal
				$("body").on("click", ".addBox", function() {
					var popLink = $(this).attr("data-url");
					var popTitle = $(this).attr("data-title");
					//alert(popLink);	
					setIframe(popLink, popTitle);
					$('.modal').appendTo("body").modal('show');
				});

				if ($('.cpfcnpj').val() != undefined) {
					mascaraCpfCnpj($('.cpfcnpj'));
				}

				$('#btLogin').click(function() {

					// var pCpf = $('#cpf').val().replace(/[^0-9]/g, '');
					var pSenha = $('#senha').val().trim();

					if (pSenha != "") {

						$.ajax({
							type: "POST",

							url: "ajxLogin.do",
							data: $("#contatoForm").serialize(),
							success: function(msg) {
								if (msg.trim() != 'sem_resultado' && msg.trim() != 'inativo') {
									$('#containerExtrato').html(msg);
									// $("#BTN_CADASTRO").attr("data-url","https://<?= $des_dominio . $extensaoDominio ?>/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&idc="+pCpf+"&pop=true");
								} else if (msg.trim() == 'inativo') {
									$('.errorInat').show();
								} else {
									$('.errorLogin').show();
								}
								//console.log(msg);
							}
						});

					}

				});

				$('#btMensagem').click(function() {

					var nome = $('#name').val();
					var email = $('#email').val();
					var mensagem = $('#message').val();

					if (grecaptcha.getResponse() != "") {

						if (nome != "" && email != "" && mensagem != "") {
							$("#btMensagem").attr("disabled", true);
							$.ajax({
								type: "POST",
								url: "ajxEnviarMensagem.do",
								data: {
									nome: $('#name').val(),
									email: $('#email').val(),
									mensagem: $('#message').val(),
									assunto: $('#DES_ASSUNTO').val(),
									codEmpresa: <?php echo $cod_empresa; ?>,
									programa: '<?php echo $des_programa; ?>',
									g_token: grecaptcha.getResponse()
								},
								success: function(msg) {
									$('#contato-info').html(msg);
									$("#btMensagem").fadeOut('fast');
									//console.log(msg);
								}
							});
						} else {
							alert('Por favor, preencha todos os campos.');
						}

					} else {
						alert('Selecione a caixa de verificação "Não sou um robô".');
					}

				});

			});

			function mascaraCpfCnpj(cpfCnpj) {
				var optionsCpfCnpj = {
					onKeyPress: function(cpf, ev, el, op) {
						if (bloqueiaPj != 'S') {
							var masks = ['000.000.000-000', '00.000.000/0000-00'],
								mask = (cpf.length >= 15) ? masks[1] : masks[0];
						} else {
							var mask = '000.000.000-00';
						}
						cpfCnpj.mask(mask, op);
					}
				}

				var masks = ['000.000.000-000', '00.000.000/0000-00'];
				mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];

				cpfCnpj.mask(mask, optionsCpfCnpj);
			}

			function loginCliente(chaveCliente, pwd) {
				$.ajax({
					type: "POST",
					url: "ajxLogin.do",
					data: {
						KEY_NUM_CARTAO: chaveCliente,
						senha: pwd,
						COD_EMPRESA: "<?= $cod_empresa ?>"
					},
					success: function(msg) {
						if (msg.trim() != 'sem_resultado' && msg.trim() != 'inativo') {
							$('#containerExtrato').html(msg);
							// $("#BTN_CADASTRO").attr("data-url","https://"+'<?= $des_dominio . $extensaoDominio ?>'+"/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&idc="+pCpf+"&pop=true");
						} else if (msg.trim() == 'inativo') {
							$('.errorInat').show();
						} else {
							$('.errorLogin').show();
						}

						$('html, body').animate({
							scrollTop: $("#extrato").offset().top
						}, 1000);
						//console.log(msg);
						// $("#extrato").click();
					}
				});
			}

			//call modal
			//$('#popModal').iFrameResize({closedCallback:function(){$('#popModal').modal('hide');}});
			function setIframe(src, title) {
				$(".modal iframe").attr({
					'src': src
				}).iFrameResize({
					messageCallback: function() {
						$('#popModal').modal('hide');
					}
				});
				if (title) {
					$(".modal-title").text(title);
				} else {
					$(".modal-title").text("");
				}
				$("#ifrCad").prop("scrolling", "yes").css("overflow", "auto");
			}
		</script>

	</body>

	</html>

<?php
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	//carrega site vazio
} else {
?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Marka Fidelização e Relacionamento</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/main.css" rel="stylesheet">

		<link href="css/custom.css" rel="stylesheet">

		<link rel="icon" type="image/png" href="https://www.markafidelizacao.net.br/wp-content/uploads/2016/10/icone-marka.png" />

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
		<!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->
	</head>


	<!-- Scrollspy set in the body -->

	<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">


		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->
		<section id="header">

			<nav class="navbar navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
					<div class="container">

						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

						<a href="https://marka.mk" class="navbar-brand"><img class="logo-img" src="images/logo_modelo.png" alt="Marka Soluções em Fidelização - Portal do Cliente"></a>

						<div class="collapse navbar-collapse main-nav" id="navigation">


							<ul class="nav pull-right">
								<!--
                            <li><a href="index.html">&laquo; Back to Home</a></li>
							-->
							</ul>

						</div><!-- /nav-collapse -->
					</div><!-- /container -->
				</div><!-- /navbar-inner -->
			</nav>

		</section>

		<!--/////////////////////////////////////// BLOG SECTION ////////////////////////////////////////-->
		<section id="main-content">

			<div class="container">

				<div class="icon-huge">
					<img src="images/icons/compas.svg" alt="" />
				</div>

				<header>
					<h3>Ooops... Parece que você está perdido!</h3>
					<p class="lead">O <strong>site/link</strong> que você está procurando não existe ou foi desativado.</p>
					<br><!--
                <a href="index.html" class="btn btn-hg btn-primary btn-embossed text-center"><span class="fui-arrow-left"></span> Take me back</a>
				-->
				</header>

			</div>

		</section>

		<!--//////////////////////////////////////// FOOTER SECTION ////////////////////////////////////////-->
		<section id="footer">
			<div class="bottom-menu-inverse">

				<div class="container">

					<div class="row">
						<div class="col-md-6">
							<p class="fFooter">Marka Fidelização e Relacionamento - &copy; Todos os direitos reservados. <br />
								Solução: &nbsp; <a href="https://marka.mk" class="fFooter" target="_blank">Marka Soluções em Fidelização</a>.</p>
						</div>

						<!-- <div class="col-md-6 social">
                        <ul class="bottom-icons">
                            <li>
                              <a href="https://www.facebook.com/MarkaFidelizacao/" class="fui-facebook"></a>
                            </li>
                             <li>
                              <a href="https://www.youtube.com/user/marcelofidelizacao/videos" class="fui-youtube"></a>
                            </li>
                          </ul>                      
                    </div> -->
					</div>

				</div><!-- /row -->
			</div><!-- /container -->

		</section>

		<script src="js/jquery-1.8.3.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>

		<!-- SISTEMA -->
		<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>
		<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
		<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>

		<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
		<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>

	</body>

	</html>


<?php
}
?>