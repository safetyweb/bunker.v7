<?php
include "../_system/_functionsMain.php";
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';

//habilitando o cors
// header("Access-Control-Allow-Origin: *");


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

// echo "$siteGo";

//se carrega site
if ($siteGo == "OK") {

	//fnEscreve($siteGo);
	//fnEscreve($cod_empresa);

	//busca nome da empresa
	$sql2 = "select NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B, LOG_ALTERAHS from EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2);
	$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);
	$nom_fantasi = $qrBuscaDadosEmpresa['NOM_FANTASI'];
	$qtd_chartkn = $qrBuscaDadosEmpresa['QTD_CHARTKN'];
	$tip_token = $qrBuscaDadosEmpresa['TIP_TOKEN'];
	$log_alterahs = $qrBuscaDadosEmpresa['LOG_ALTERAHS'];

	if ($qrBuscaEmpresa['TIP_RETORNO'] == 1) {
		$casasDec = 0;
	} else {
		$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
		$pref = "R$ ";
	}

	if ($tip_token == 2) {
		$type = "number";
	} else {
		$type = "text";
	}

	$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

	// echo "_".$cod_cliente;
	// exit();

	//busca dados da tabela
	$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		//fnEscreve("entrou if");
		$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
		$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
		$cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];
		$des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
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
		$tp_ordenac = $qrBuscaSiteExtrato['TP_ORDENAC'];
	}

	list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf("#" . $cor_site, "#%02x%02x%02x");

	if ($r_cor_backpag > 50) {
		$r = ($r_cor_backpag - 50);
	} else {
		$r = ($r_cor_backpag + 50);
		if ($r_cor_backpag < 30) {
			$r = $r_cor_backpag;
		}
	}
	if ($g_cor_backpag > 50) {
		$g = ($g_cor_backpag - 50);
	} else {
		$g = ($g_cor_backpag + 50);
		if ($g_cor_backpag < 30) {
			$g = $g_cor_backpag;
		}
	}
	if ($b_cor_backpag > 50) {
		$b = ($b_cor_backpag - 50);
	} else {
		$b = ($b_cor_backpag + 50);
		if ($b_cor_backpag < 30) {
			$b = $b_cor_backpag;
		}
	}

	if ($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50) {
		$r = ($r_cor_backpag + 40);
		$g = ($g_cor_backpag + 40);
		$b = ($b_cor_backpag + 40);
	}
}

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];

$des_img = $qrControle['DES_IMG'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO = 10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
		  WHERE COD_EMPRESA = $cod_empresa 
		  AND LOG_ESTATUS = 'S' 
		  ORDER BY 1 ASC LIMIT 1";

$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
$qrLista = mysqli_fetch_assoc($arrayUn);

$idlojaKey = $qrLista['COD_UNIVEND'];
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = $log_usuario . ';'
	. $des_senhaus . ';'
	. $idlojaKey . ';'
	. $idmaquinaKey . ';'
	. $cod_empresa . ';'
	. $codvendedorKey . ';'
	. $nomevendedorKey;

$arrayCampos = explode(";", $urltotem);

$urlWebservice = $arrayCampos;

if ($cod_cliente != 0) {

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND COD_CLIENTE = $cod_cliente";

	// echo($sqlCli);

	$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

	$qrCli = mysqli_fetch_assoc($arrayCli);

	$cpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
	$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
	$celular = $qrCli['NUM_CELULAR'];
	$cartao = $qrCli['NUM_CARTAO'];
	$externo = $qrCli['NUM_CARTAO'];
	$log_termo = $qrCli['LOG_TERMO'];
	$des_token = $qrCli['DES_TOKEN'];
}


$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN, LOG_DAT_NASCIME FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

// echo($sqlCampos);

$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayFields);


//adicionado por Lucas referente ao chamado 6045 controle de data de nascimento
$log_dat_nascime = $qrCampos['LOG_DAT_NASCIME'];
if ($log_dat_nascime == 'S') {

	$sql = "SELECT * FROM controle_alterac_cli WHERE COD_EMPRESA = '$cod_empresa' AND NUM_CGCECPF = '$cpf'";
	$array = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrResult = mysqli_fetch_assoc($array);
	$qtd_alterac = $qrResult['QTD_ALTERAC'];

	if ($qtd_alterac >= 1) {
		$per_altera_dat = "disabled";
		$msg_altera_dat = "Esse campo já foi alterado e não permite novas alterações, consulte o gestor de sua empresa";
	} else {
		$per_altera_dat = "";
		$msg_altera_dat = "";
	}
}


$log_cadtoken = $qrCampos['LOG_CADTOKEN'];

// echo '_'.$cpf;
// echo '_'.$qrCampos[COD_CHAVECO];

switch ($qrCampos['COD_CHAVECO']) {

	case 2:
		$chave = $cartao;
		$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], fnLimpaDoc($chave), $arrayCampos);
		break;

	case 3:
		$chave = $celular;
		$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], fnLimpaDoc($chave), $arrayCampos);
		break;

	case 4:
		$chave = $externo;
		$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], fnLimpaDoc($chave), $arrayCampos);
		break;

	default:

		if (strlen($cpf) <= '11') {

			// echo '<pre>';

			$buscaconsumidor = fnconsulta(fnCompletaDoc($cpf, 'F'), $arrayCampos);

			// print_r($buscaconsumidor);

			// echo '</pre>';

		} else {

			// echo 'else';

			$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($cpf, 'J'), $arrayCampos);
		}

		break;
}

if ($buscaconsumidor['cpf'] != '00000000000') {

	$cpf = $buscaconsumidor['cpf'];
} else {
	$cpf = $k_num_cgcecpf;
	$buscaconsumidor['nome'] = "";
}

if ($buscaconsumidor['cartao'] != "") {
	$cartao = $buscaconsumidor['cartao'];
	$c10 = $buscaconsumidor['cartao'];
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if ($cod_cliente != 0) {

	$arrayNome = explode(" ", $result['NOM_CLIENTE']);
	$nome = $arrayNome[0];
	$dia_nascime = $result['DIA'];
	$mes_nascime = $result['MES'];
	$ano_nascime = $result['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $result['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_TOTEM = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery2);

	$today = date("Y-m-d");

	if (mysqli_num_rows($arrayQuery2) > 0) {

		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '6':

				$date = date("Y-m-d", strtotime($today . "-6 months"));

				break;

			default:

				$date = date("Y-m-d", strtotime($today . "-1 year"));

				break;

				if ($dat_atualiza >= $date && $dat_atualiza <= $today) {
					$mostraMsgCad = 'block';
				}
		}
	}

	$today = date("Y-m-d");
	$date = date("Y-m-d", strtotime($today . "+6 months"));

	// echo $today."<br/>";
	// echo $date;


	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '99' 
	AND COMUNICACAO_MODELO.LOG_TOTEM = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery);

	if (mysqli_num_rows($arrayQuery) > 0) {

		$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

		$NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($qrCli['NOM_CLIENTE']))));
		$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
		$TEXTOENVIO = str_replace('<#SALDO>', fnValor($qrCli['CREDITO_DISPONIVEL'], $casasDec), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#NOMELOJA>',  $qrCli['NOM_FANTASI'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#ANIVERSARIO>', $qrCli['DAT_NASCIME'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#DATAEXPIRA>', fnDataShort($qrCli['DAT_EXPIRA']), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#EMAIL>', $qrCli['DES_EMAILUS'], $TEXTOENVIO);
		$msgsbtr = nl2br($TEXTOENVIO, true);
		$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
		$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '7':

				if ($dia_hoje == $dia_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			case '30':

				if ($mes_hoje == $mes_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			default:

				$firstDate = strtotime($ano_hoje . '-' . $mes_nascime . '-' . $dia_nascime);
				$secondDate = strtotime($ano_hoje . '-' . $mes_hoje . '-' . $dia_hoje);

				$result = date('oW', $firstDate) === date('oW', $secondDate) && date('Y', $firstDate) === date('Y', $secondDate);

				if ($result) {
					$mostraMsgAniv = 'block';
				}

				break;
		}
	}
}


$readonly = "";

$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";
if ($cod_cliente != 0) {
	$andOpc = "";
}

if ($cod_dominio == 2) {
	$extensaoDominio = ".fidelidade.mk";
} else {
	$extensaoDominio = ".mais.cash";
}

// echo($buscaconsumidor['sexo']);



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title><?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="css/main.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">

	<!-- SISTEMA -->
	<script src="js/jquery-3.6.0.min.js"></script>
	<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />

	<!-- complement -->
	<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />


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
		-ms-overflow-style: none !important;
		/* Internet Explorer 10+ */
		scrollbar-width: none !important;
		/* Firefox */
		/*overflow: hidden;*/
	}

	body::-webkit-scrollbar {
		display: none !important;
		/* Safari and Chrome */
	}

	#parallax {
		height: 652px;
		width: 100%;
		position: fixed;
		background: none;
		background-size: cover;
		z-index: -100;
	}

	.logo-img {
		height: 90px !important;
	}

	section {
		padding-top: 15px !important;
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
		color: #<?php echo $cor_titulos; ?>;
	}

	.btn-primary {
		background-color: #<?php echo $cor_botao; ?>;
	}

	.btn-primary:hover {
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

	#corpoForm {
		width: 100vw !important;
	}

	#caixaForm {
		/*overflow: auto;*/
	}

	#caixaImg,
	#caixaForm {
		/*height: 100vh;*/
	}

	#caixaImg {
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		-moz-background-size: 100% 100%;
		-o-background-size: 100% 100%;
		background-size: 100% 100%;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}

	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}

	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}



		.navbar img {
			margin-top: 0;
		}

		#caixaImg {
			padding: 0;
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

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}

	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}



		.navbar img {
			margin-top: 0;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}

	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}



		.navbar img {
			margin-top: 0;
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
			padding: 0;
		}

	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}



		.navbar img {
			margin-top: 0;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}

	}

	@media (max-height: 824px) and (max-width: 416px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: auto !important;
		}

		#corpoForm {
			width: unset !important;
		}

		#caixaImg,
		#caixaForm {
			/*height: unset;*/
		}

		#caixaImg {
			background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

		.logo-img {
			height: 90px !important;
		}

		.input-sm {
			margin-bottom: 0px;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

		#caixaImg {
			padding: 0;
		}


	}

	.input-sm,
	.chosen-single {
		font-size: 20px !important;
	}

	.logo-center {
		margin-left: auto;
		margin-right: auto;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<?php

// echo "_".$_GET['pop']."_";
// exit();

$atv = $_GET['atv'];

if ($atv == "") {
	$atv = 'false';
} else {
	$atv .= "&ch=4";
}

if ($_GET['pop'] != 'true') {

?>


	<!-- Scrollspy set in the body -->

	<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

		<div id="parallax"></div>

		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->

		<section id="header">

			<nav class="navbar navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
					<div class="container">

						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

						<!-- Logo goes here - replace the image with yours -->
						<a href="." class="navbar-brand"><img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" class="logo-img img-responsive" alt="<?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?>" title="Booom! Logo"></a>

						<div class="collapse navbar-collapse main-nav" id="navigation">

							<ul class="nav pull-right">
								<!-- Menu -->
								<li class='active'><a href='#home'>Home</a></li>
								<?php
								if ($log_contato == "S") {
									echo "<li><a href='#contact'>$txt_contato</a></li>";
								}
								?>
							</ul>

						</div><!-- /nav-collapse -->
					</div><!-- /container -->
				</div><!-- /navbar-inner -->
			</nav>
		</section>

		<?php

	} else {

		if ($_GET['ida'] != 'false') {
		?>

			<?php
			$abasCadastroHotsite = 2;
			include "abasCadastroHotsite.php";
			?>

	<?php
		}
	}

	?>

	<!--/////////////////////////////////////// CONTACT SECTION ////////////////////////////////////////-->

	<section id="extrato">


		<div class="row" id="corpoForm">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="cliAtivo.do?id=<?= fnEncode($cod_empresa) ?>&pop=true&atv=<?= $atv ?>" autocomplete="off">

				<!-- <div class="col-md-6 col-xs-12" id="caixaImg"> -->
				<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img ?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
				<!-- </div> -->

				<div class="col-md-6 col-md-offset-3 col-xs-12" id="caixaForm" style="background-color: #FFF;">

					<?php

					if ($cod_cliente != 0) {

						if ($_GET['pop'] != 'true') {

					?>

							<div class="col-xs-12 text-center">
								<p class="f18"><b>ATIVE AGORA OS SEUS CRÉDITOS</b></p>
								<p class="f16"><b>1 - Complete o cadastro:</b></p>
							</div>

						<?php

						}
					} else {

						?>

						<div class="col-xs-12 text-center">
							<p class="f18"><b>BEM VINDO!</b></p>
							<p class="f16"><b>Por favor, atualize seu cadastro:</b></p>
						</div>

					<?php

					}

					?>

					<!-- <div class="push20"></div> -->



					<div class="col-xs-12" style="display: <?= $mostraMsgAniv ?>">

						<div class="col-md-12 alert-warning top30 bottom30" role="alert" id="msgRetorno">
							<div class="push20"></div>
							<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
							<div class="push20"></div>
						</div>

					</div>

					<?php if ($log_alterahs == 'S') { ?>

						<div class="col-xs-12">

							<div class="col-md-12 alert-warning top30 bottom30" role="alert" id="msgRetorno">
								<div class="push20"></div>
								<div style="display: flex; flex-direction:column;">
									<span style="font-size: 16px; padding: 0 30px;">
										Caso deseje alterar seus dados ou excluir seu cadastro, entre em contato por meio deste
										<a href="javascript:void(0);" style="text-decoration: underline;" onclick="scrollToContact();">link</a>.
									</span>
									<span style="font-size: 16px; padding: 0 30px;">Ou clique em contato no menu.</span>
								</div>
								<div class="push20"></div>
							</div>

						</div>

					<?php } ?>

					<div class="col-xs-12" style="display: <?= $mostraMsgCad ?>">

						<div class="alert-warning top30 bottom30" role="alert" id="msgRetorno">
							<div class="push20"></div>
							<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
							<div class="push20"></div>
						</div>

					</div>


					<?php

					$sqlCampos = "SELECT NOM_CAMPOOBG, 
												 NOM_CAMPOOBG, 
												 DES_CAMPOOBG, 
												 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
												 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
												 (SELECT COUNT(DISTINCT MCI.TIP_CAMPOOBG) 
													FROM matriz_campo_integracao MCI
													WHERE MCI.TIP_CAMPOOBG IN('OBG','OPC') 
													AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
													AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
												 COL_MD, 
												 COL_XS, 
												 CLASSE_INPUT, 
												 CLASSE_DIV 
											FROM MATRIZ_CAMPO_INTEGRACAO                         
											LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
											WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
											AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
											AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
											AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
											$andOpc
											ORDER BY NUM_ORDENAC ASC, COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

					$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

					// echo($sqlCampos);
					// exit();

					$lastField = "";

					while ($qrCampos = mysqli_fetch_assoc($arrayCampos)) {

						// echo "<pre>";
						// print_r($qrCampos);
						// echo "</pre>";

						$colMd = $qrCampos['COL_MD'];
						$colXs = $qrCampos['COL_XS'];
						$dataError = "";

						$required = "";
						// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

						if ($lastField == "") {
							$lastField = $qrCampos['NOM_CAMPOOBG'];
						} else if ($lastField == $qrCampos['NOM_CAMPOOBG']) {
							continue;
						} else {
							$lastField = $qrCampos['NOM_CAMPOOBG'];
						}

						if ($qrCampos['OBRIGATORIO'] > 0) {
							$required = "required";
							$dataError = "data-error='Campo obrigatório'";
						}

						// echo "$qrCampos[CAT_CAMPO]";

						if ($colMd == "" || $colMd == 0) {
							$colMd = 12;
						}

						if ($colXs == "" || $colXs == 0) {
							$colXs = 12;
						}

						// echo "_$buscaconsumidor[estado]_";

						switch ($qrCampos['DES_CAMPOOBG']) {

							case 'NOM_CLIENTE':

								$dado = $buscaconsumidor['nome'];

								break;

							case 'COD_SEXOPES':

								$dado = $buscaconsumidor['sexo'];

								break;

							case 'DES_EMAILUS':

								$dado = $buscaconsumidor['email'];

								break;

							case 'NUM_CELULAR':

								$dado = $buscaconsumidor['telcelular'];

								break;

							case 'NUM_CARTAO':

								$dado = $buscaconsumidor['cartao'];

								break;

							case 'NUM_CGCECPF':

								$dado = $buscaconsumidor['cpf'];

								break;


							case 'DAT_NASCIME':

								$dado = $buscaconsumidor['datanascimento'];

								break;

							case 'COD_PROFISS':

								$dado = $buscaconsumidor['profissao'];

								break;

							case 'COD_ATENDENTE':

								$dado = $buscaconsumidor['codatendente'];

								break;

							case 'DES_SENHAUS':

								$dado = $buscaconsumidor['senha'];

								break;

							case 'DES_ENDEREC':

								$dado = $buscaconsumidor['endereco'];

								break;

							case 'NUM_ENDEREC':

								$dado = $buscaconsumidor['numero'];

								break;

							case 'NUM_CEPOZOF':

								$dado = $buscaconsumidor['cep'];

								break;

							case 'COD_ESTADOF':

								$dado = $buscaconsumidor['estado'];

								break;

							case 'NOM_CIDADEC':

								$dado = $buscaconsumidor['cidade'];

								break;

							case 'DES_BAIRROC':

								$dado = $buscaconsumidor['bairro'];

								break;

							case 'DES_COMPLEM':

								$dado = $buscaconsumidor['complemento'];

								break;

							default:

								$dado = "";

								break;
						}

						switch ($qrCampos['TIPO_DADO']) {

							case 'Data':

					?>
								<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
										<input type="tel" placeholder="<?= date('d/m/Y') ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?> data" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" maxlenght="10" data-minlength="10" data-minlength-error="O formato da data deve ser DD/MM/AAAA" <?= $per_altera_dat ?> <?= $dataError ?> <?= $required ?>>
										<div class="help-block with-errors"><?= $msg_altera_dat ?></div>
									</div>
								</div>

							<?php

								break;

							case 'email':

								$dataError = "";

							?>
								<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
										<input type="email" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<?php

								break;

							case 'numeric':

								if ($qrCampos['DES_CAMPOOBG'] == "COD_SEXOPES") {

								?>
									<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
												<option value=""></option>
												<?php
												$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																			  <option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
																			";
												}
												?>
											</select>
											<script type="text/javascript">
												$(function() {
													$("#COD_SEXOPES").val("<?= $buscaconsumidor['sexo'] ?>").trigger('chosen:updated')
												});
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php

								} else if ($qrCampos['DES_CAMPOOBG'] == "COD_PROFISS") {

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>">Profissão </label>
											<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
												<option value=""></option>
												<?php
												$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";
												if (mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0') {
													$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												} else {
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												}

												while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																			  <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
																			";
												}
												?>
											</select>
											<script type="text/javascript">
												$(function() {
													$("#COD_PROFISS").val("<?= $dado ?>").trigger('chosen:updated')
												});
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php

								} else if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTACIV") {

								?>
									<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>">Estado Civil</label>
											<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?>>
												<option value=""></option>
												<?php
												$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																			  <option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
																			";
												}
												?>
											</select>
											<script type="text/javascript">
												$(function() {
													$("#COD_ESTACIV").val("<?= $dado ?>").trigger('chosen:updated')
												});
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php

								} else {

									$type = "text";

									if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
										$nomeCampo = "CPF/CNPJ";
										$mask = "cpfcnpj";
										$type = "tel";
									} else {
										$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
										$mask = "";
									}

								?>
									<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
											<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?> <?= $mask ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php

								}

								break;

							default:

								$type = "text";

								if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
									$nomeCampo = "CPF/CNPJ";
									$mask = "cpfcnpj";
									$type = "tel";
								} else if ($qrCampos['DES_CAMPOOBG'] == "NUM_CELULAR" || $qrCampos['DES_CAMPOOBG'] == "NUM_TELEFONE" || $qrCampos['DES_CAMPOOBG'] == "NUM_CEPOZOF") {
									$type = "tel";
								} else {
									$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
									$mask = "";
								}

								if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTADOF") {

								?>
									<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>"><?= $nomeCampo ?></label>
											<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect input-sm <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $dataError ?> <?= $required ?>>
												<option value=""></option>
												<option value="AC">AC</option>
												<option value="AL">AL</option>
												<option value="AM">AM</option>
												<option value="AP">AP</option>
												<option value="BA">BA</option>
												<option value="CE">CE</option>
												<option value="DF">DF</option>
												<option value="ES">ES</option>
												<option value="GO">GO</option>
												<option value="MA">MA</option>
												<option value="MG">MG</option>
												<option value="MS">MS</option>
												<option value="MT">MT</option>
												<option value="PA">PA</option>
												<option value="PB">PB</option>
												<option value="PE">PE</option>
												<option value="PI">PI</option>
												<option value="PR">PR</option>
												<option value="RJ">RJ</option>
												<option value="RN">RN</option>
												<option value="RO">RO</option>
												<option value="RR">RR</option>
												<option value="RS">RS</option>
												<option value="SC">SC</option>
												<option value="SE">SE</option>
												<option value="SP">SP</option>
												<option value="TO">TO</option>
											</select>
											<script>
												$(function() {
													$("#formulario #COD_ESTADOF").val("<?php echo $dado; ?>").trigger("chosen:updated")
												});
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php

								} else {

								?>
									<div class="col-md-<?= $colMd ?> col-xs-<?= $colXs ?>">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label <?= $required ?>"><?= $qrCampos['NOM_CAMPOOBG'] ?></label>
											<input type="<?= $type ?>" value="<?= $dado ?>" class="form-control input-sm input-hg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

						<?php

								}

								break;
						}

						?>
						<!-- <div class="push10"></div> -->
					<?php

					}

					?>

					<!-- <div class="push20"></div> -->

					<?php



					$displayTermos = "block";


					if ($log_lgpd == 'S') {



					?>

						<div id="relatorioPreview">

							<?php

							if ($_GET['pop'] != 'true') {

							?>

								<div class="push10"></div>
								<div class="col-xs-12 text-center">
									<p class="f16"><b>2 - Aceite os termos:</b></p>
								</div>
								<div class="push10"></div>

							<?php

							}

							?>

							<div class="push10"></div>

							<div class="col-xs-12">
								<p><b><?= $qrControle['TXT_ACEITE'] ?></b></p>
							</div>

							<div class="push10"></div>

							<?php

							if ($log_separa == 'S') {

								$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
								// fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$count = 0;
								$tipo = "";
								while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

									if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
										$obrigaChk = "required";
									} else {
										$obrigaChk = "";
									}


									$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
												   WHERE COD_CLIENTE = $cod_cliente
												   AND COD_CLIENTE != 0
												   AND COD_EMPRESA = $cod_empresa
												   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
												   AND COD_TERMOS = '" . $qrBuscaFAQ['COD_TERMO'] . "'";
									// echo($sqlChk);
									$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

									$chkTermo = "";

									if (mysqli_num_rows($arrayChk) == 1) {
										$chkTermo = "checked";
									}

									$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
													  WHERE COD_EMPRESA = $cod_empresa
													  AND COD_TERMO IN(" . $qrBuscaFAQ['COD_TERMO'] . ")";

									// fnEscreve($sqlTermos);

									$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

									$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

									while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
										// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

										$des_bloco = str_replace(
											"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
											'
																		</label>
																			
																				<a class="addBox f16 text-success" 
																				   data-url="https://' . $des_dominio . $extensaoDominio . '/termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				   data-title="' . $qrTermos['NOM_TERMO'] . '"
																				   style="cursor:pointer;">
																				   ' . $qrTermos['ABV_TERMO'] . '
																				</a>
																			
																	  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																	',
											$des_bloco
										);
									}

							?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
												<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
												<label class="<?= $obrigaChk ?>"></label>
											</div>
											<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
												<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
													&nbsp;<?= $des_bloco ?>
												</label>
											</div>
										</div>
										<div class="help-block with-errors"></div>
										<div class="push10"></div>
										<div class="push5"></div>
									</div>

								<?php

									$count++;
								}

								?>

								<div class="col-xs-12">
									<h5>
										<b>
											<p><?= $qrControle['TXT_COMUNICA'] ?></p>
										</b>
									</h5>
								</div>
								<div class="push10"></div>

								<?php

								$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
								// fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								// $count=0;
								$tipo = "";
								while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

									if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
										$obrigaChk = "required";
									} else {
										$obrigaChk = "";
									}


									$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
												   WHERE COD_CLIENTE = $cod_cliente
												   AND COD_CLIENTE != 0
												   AND COD_EMPRESA = $cod_empresa
												   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
												   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
									// echo($sqlChk);
									$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

									$chkTermo = "";

									if (mysqli_num_rows($arrayChk) == 1) {
										$chkTermo = "checked";
									}

									$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
													  WHERE COD_EMPRESA = $cod_empresa
													  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

									// fnEscreve($sqlTermos);

									$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

									$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

									while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
										// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

										$des_bloco = str_replace(
											"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
											'
																		</label>
																			
																				<a class="addBox f16 text-success" 
																				   data-url="https://' . $des_dominio . $extensaoDominio . '/termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				   data-title="' . $qrTermos['NOM_TERMO'] . '"
																				   style="cursor:pointer;">
																				   ' . $qrTermos['ABV_TERMO'] . '
																				</a>
																			
																	  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																	',
											$des_bloco
										);
									}

								?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
												<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
												<label class="<?= $obrigaChk ?>"></label>
											</div>
											<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
												<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
													&nbsp;<?= $des_bloco ?>
												</label>
											</div>
										</div>
										<div class="help-block with-errors"></div>
										<div class="push10"></div>
										<div class="push5"></div>
									</div>

								<?php

									$count++;
								}
							} else {

								$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC";
								// fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$count = 0;
								$tipo = "";
								while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

									if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
										$obrigaChk = "required";
									} else {
										$obrigaChk = "";
									}


									$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
												   WHERE COD_CLIENTE = $cod_cliente
												   AND COD_CLIENTE != 0
												   AND COD_EMPRESA = $cod_empresa
												   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
												   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
									// echo($sqlChk);
									$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

									$chkTermo = "";

									if (mysqli_num_rows($arrayChk) == 1) {
										$chkTermo = "checked";
									}

									$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
													  WHERE COD_EMPRESA = $cod_empresa
													  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

									// fnEscreve($sqlTermos);

									$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

									$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

									while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
										// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

										$des_bloco = str_replace(
											"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
											'
																		</label>
																			
																				<a class="addBox f16 text-success" 
																				   data-url="https://' . $des_dominio . $extensaoDominio . '/termos.do?id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				   data-title="' . $qrTermos['NOM_TERMO'] . '"
																				   style="cursor:pointer;">
																				   ' . $qrTermos['ABV_TERMO'] . '
																				</a>
																			
																	  	<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																	',
											$des_bloco
										);
									}

								?>

									<div class="form-group">
										<div class="col-xs-12">
											<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
												<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
												<label class="<?= $obrigaChk ?>"></label>
											</div>
											<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
												<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
													&nbsp;<?= $des_bloco ?>
												</label>
											</div>
										</div>
										<div class="help-block with-errors"></div>
										<div class="push10"></div>
										<div class="push5"></div>
									</div>

							<?php

									$count++;
								}
							}

							?>

						</div>

					<?php } ?>

					<div class="push20"></div>

					<div class="col-md-12 col-xs-12">

						<?php

						$txtCad = 'Aceitar Termos <div class="push"></div> e Ativar Créditos';

						if ($cod_empresa == 124) {
							$txtCad = 'Atualizar Cadastro';
						}

						if ($cod_cliente == 0) {

							$log_novocli = "S";

							if ($log_lgpd == 'N') {
								$txtCad = 'Cadastrar-se';
							}

						?>

							<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;"><?= $txtCad ?></button>
							<?php

						} else {

							$log_novocli = "N";

							$txtDescad = "Descadastrar-se";

							if ($cod_empresa == 77) {
								$txtDescad = "Excluir Cadastro";
							}

							if ($log_lgpd == 'N') {
								$txtCad = 'Atualizar Cadastro';
							}

							// echo "<h1>_ $log_cadtoken _</h1>";
							// echo "<h1>_ $des_token _</h1>";

							if (($log_cadtoken == 'S' && $des_token == 0) && $log_alterahs == 'N') {

							?>

								<div id="relatorioToken">
									<a href="javascript:void(0)" class="btn btn-primary btn-lg btn-block" onclick='ajxTokenAlt()'>Enviar Token</a>
								</div>

								<div id="btnCad" style="display: none;">
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;">Aceitar Termos e Atualizar Cadastro</button>
								</div>

								<?php

							} else {

								if ($log_alterahs == 'N') {
								?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;"><?= $txtCad ?></button>
									<div class="push20"></div>

									<div class="col-md-12 text-center">
										<a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastra("<?= fnEncode($cod_cliente) ?>")' style="font-size: 16px;"><?= $txtDescad ?></a>
									</div>
								<?php } ?>

						<?php

							}
						}

						?>

					</div>

					<div class="push100"></div>
					<div class="push100"></div>
					<div class="push100"></div>
					<div class="push100"></div>

					<div id="relatorioConteudo"></div>

				</div>


				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" name="KEY_DES_TOKEN" id="KEY_DES_TOKEN" value="">
				<input type="hidden" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" value="<?= $k_num_cartao ?>">
				<input type="hidden" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" value="<?= $k_num_celular ?>">
				<input type="hidden" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" value="<?= $k_cod_externo ?>">
				<input type="hidden" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" value="<?= $k_num_cgcecpf ?>">
				<input type="hidden" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" value="<?= $k_dat_nascime ?>">
				<input type="hidden" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" value="<?= $k_des_emailus ?>">
				<input type="hidden" name="CAD_NOM_CLIENTE" id="CAD_NOM_CLIENTE" value="<?= $buscaconsumidor['nome'] ?>">
				<input type="hidden" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="<?= $buscaconsumidor['cpf'] ?>">
				<input type="hidden" name="CAD_COD_SEXOPES" id="CAD_COD_SEXOPES" value="<?= $buscaconsumidor['sexo'] ?>">
				<input type="hidden" name="CAD_NUM_CARTAO" id="CAD_NUM_CARTAO" value="<?= $buscaconsumidor['cartao'] ?>">
				<input type="hidden" name="CAD_DES_EMAILUS" id="CAD_DES_EMAILUS" value="<?= $buscaconsumidor['email'] ?>">
				<input type="hidden" name="CAD_DES_ENDEREC" id="CAD_DES_ENDEREC" value="<?= $buscaconsumidor['endereco'] ?>">
				<input type="hidden" name="CAD_NUM_ENDEREC" id="CAD_NUM_ENDEREC" value="<?= $buscaconsumidor['numero'] ?>">
				<input type="hidden" name="CAD_DES_BAIRROC" id="CAD_DES_BAIRROC" value="<?= $buscaconsumidor['bairro'] ?>">
				<input type="hidden" name="CAD_DES_COMPLEM" id="CAD_DES_COMPLEM" value="<?= $buscaconsumidor['complemento'] ?>">
				<input type="hidden" name="CAD_DES_CIDADEC" id="CAD_DES_CIDADEC" value="<?= $buscaconsumidor['cidade'] ?>">
				<input type="hidden" name="CAD_COD_ESTADOF" id="CAD_COD_ESTADOF" value="<?= $buscaconsumidor['estado'] ?>">
				<input type="hidden" name="CAD_NUM_CEPOZOF" id="CAD_NUM_CEPOZOF" value="<?= $buscaconsumidor['cep'] ?>">
				<input type="hidden" name="CAD_DAT_NASCIME" id="CAD_DAT_NASCIME" value="<?= $buscaconsumidor['datanascimento'] ?>">
				<input type="hidden" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="<?= $buscaconsumidor['telcelular'] ?>">
				<input type="hidden" name="CAD_COD_PROFISS" id="CAD_COD_PROFISS" value="<?= $buscaconsumidor['profissao'] ?>">
				<input type="hidden" name="CAD_COD_ATENDENTE" id="CAD_COD_ATENDENTE" value="<?= $buscaconsumidor['codatendente'] ?>">
				<input type="hidden" name="CAD_DES_SENHAUS" id="CAD_DES_SENHAUS" value="<?= fnEncode($buscaconsumidor['senha'][0]) ?>">
				<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= fnEncode($cod_cliente) ?>">
				<input type="hidden" name="LOG_NOVOCLI" id="LOG_NOVOCLI" value="<?= $log_novocli ?>">
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

			</form>

		</div><!-- /container -->

	</section>

	<?php

	if ($_GET['pop'] != 'true') {

	?>

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

	<?php

	}

	?>

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 600px !important"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="js/jquery.ui.touch-punch.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.isotope.min.js"></script>
	<script src="js/chosen.jquery.min.js"></script>
	<!-- <script src="js/bootstrap-select.js"></script> -->
	<!-- <script src="js/custom.js"></script> -->
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/validator.min.js"></script>
	<script src="js/iframeResizer.min.js"></script>
	<script src="js/jquery-confirm.min.js"></script>

	<script>
		$(function() {

			$('input').focus(function() {
				$('html, body').animate({
					scrollTop: $(this).offset().top - 200 + 'px'
				}, 'fast');

			});

			$('#popModal').on('shown.bs.modal', function(e) {
				parent.$('html, body').animate({
					scrollTop: parent.$("#extrato").offset().top
				}, 200);
			});

			//modal
			$(".addBox").click(function() {
				var popLink = $(this).attr("data-url");
				var popTitle = $(this).attr("data-title");
				//alert(popLink);	
				setIframe(popLink, popTitle);
				$('.modal').appendTo("body").modal('show');
			});

			// $('input, textarea').placeholder();	

			parent.$("#conteudoAba").css("height", ($(document).height() + 50) + "px");

			$('.data').mask('00/00/0000');

			var SPMaskBehavior = function(val) {
					return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
					onKeyPress: function(val, e, field, options) {
						field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};

			$('.sp_celphones').mask(SPMaskBehavior, spOptions);

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			$("#formulario").validator('destroy').validator();

			$("#NUM_CEPOZOF").focusout(function() {
				if ($("#NUM_CEPOZOF").val().trim() != "") {
					$.ajax({
						type: "POST",
						url: "ajxApiCep.do?id=<?= fnEncode($cod_empresa) ?>",
						data: {
							CEP: $("#NUM_CEPOZOF").val(),
							URL: "<?= fnEncode(json_encode($urlWebservice)) ?>"
						},
						beforeSend: function() {
							$("#blocker").show();
						},
						success: function(data) {
							let end = JSON.parse(data);
							$("#DES_ENDEREC").val(end.logradouro);
							$("#DES_BAIRROC").val(end.bairro);
							$("#NOM_CIDADEC").val(end.cidade);
							$("#COD_ESTADOF").val(end.uf).trigger("chosen:updated");
							// console.log(data);
							$("#blocker").hide();
						},
						error: function(data) {
							//console.log(data);

						}
					});
				}
			});

		});

		if ($('.cpfcnpj').val() != undefined) {
			mascaraCpfCnpj($('.cpfcnpj'));
		}

		//call modal
		function setIframe(src, title) {
			$(".modal iframe").not('#popModalNotifica iframe').attr({
				'src': src
			});
			if (title) {
				$(".modal-title").not('#popModalNotifica .modal-title').text(title);
			} else {
				$(".modal-title").not('#popModalNotifica .modal-title').text("");
			}
		}

		function mascaraCpfCnpj(cpfCnpj) {
			var optionsCpfCnpj = {
				onKeyPress: function(cpf, ev, el, op) {
					var masks = ['000.000.000-000', '00.000.000/0000-00'],
						mask = (cpf.length >= 15) ? masks[1] : masks[0];
					cpfCnpj.mask(mask, op);
				}
			}

			var masks = ['000.000.000-000', '00.000.000/0000-00'];
			mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];

			cpfCnpj.mask(mask, optionsCpfCnpj);
		}

		function toggleAuth(obj) {
			$("#relatorioPreview").fadeIn("fast");
			$(obj).fadeOut(1);
		}

		function scrollToContact() {
			// Acessa o parent e rola até o elemento com id "contact"
			parent.document.querySelector('#contact').scrollIntoView({

			});
		}

		function ajxDescadastra(cod_cliente) {

			$.alert({
				title: "Confirmação",
				content: "Quero excluir meus dados de forma definitiva.",
				type: 'red',
				buttons: {
					"EXCLUIR": {
						btnClass: 'btn-danger',
						action: function() {

							$.alert({
								title: "Aviso!",
								content: "Não quero mais participar das vantagens do programa. <br/>Estou ciente que meus créditos ou bônus serão excluídos junto aos dados de forma irreversível.",
								type: 'red',
								buttons: {
									"EXCLUIR PERMANENTEMENTE": {
										btnClass: 'btn-danger',
										action: function() {
											$.ajax({
												type: "POST",
												url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>",
												data: {
													COD_CLIENTE: cod_cliente
												},
												beforeSend: function() {
													$("#blocker").show();
												},
												success: function(data) {
													window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>";
												},
												error: function() {
													console.log('Erro');
												}
											});
										}
									},
									"CANCELAR": {
										btnClass: 'btn-default',
										action: function() {

										}
									}
								},
								backgroundDismiss: function() {
									return 'CANCELAR';
								}
							});

						}
					},
					"CANCELAR": {
						btnClass: 'btn-default',
						action: function() {

						}
					}
				},
				backgroundDismiss: function() {
					return 'CANCELAR';
				}
			});

		}

		function ajxToken() {

			var num_celular = $("#NUM_CELULAR").val(),
				nom_cliente = $("#NOM_CLIENTE").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val();

			if (num_celular != "" && nom_cliente != "" && num_cgcecpf != "") {

				$.ajax({
					type: "POST",
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN",
					data: {
						NOM_CLIENTE: nom_cliente,
						NUM_CELULAR: num_celular,
						NUM_CGCECPF: num_cgcecpf
					},
					beforeSend: function() {
						// $("#blocker").show();
					},
					success: function(data) {
						$("#relatorioToken").html(data);
						// window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>";				
					},
					error: function() {
						console.log('Erro');
					}
				});

			}

		}

		function ajxTokenAlt() {

			var nom_cliente = $("#NOM_CLIENTE").val(),
				num_celular = $("#NUM_CELULAR").val(),
				cad_num_celular = $("#CAD_NUM_CELULAR").val(),
				key_num_celular = $("#KEY_NUM_CELULAR").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val(),
				cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
				key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val();

			if (num_celular != "" && nom_cliente != "" && num_cgcecpf != "") {

				$.ajax({
					type: "POST",
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT",
					data: {
						NOM_CLIENTE: nom_cliente,
						NUM_CELULAR: num_celular,
						CAD_NUM_CELULAR: cad_num_celular,
						KEY_NUM_CELULAR: key_num_celular,
						NUM_CGCECPF: num_cgcecpf,
						CAD_NUM_CGCECPF: cad_num_cgcecpf,
						KEY_NUM_CGCECPF: key_num_cgcecpf,
						LOG_LGPD: "<?= fnEncode($log_lgpd) ?>"
					},
					beforeSend: function() {
						// $("#blocker").show();
					},
					success: function(data) {
						$("#relatorioToken").html(data);
						$("#formulario").validator('destroy').validator();
						// window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>";				
					},
					error: function() {
						console.log('Erro');
					}
				});

			}

		}

		function ajxValidaTkn() {

			var num_celular = $("#NUM_CELULAR").val(),
				nom_cliente = $("#NOM_CLIENTE").val(),
				des_token = $("#DES_TOKEN").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val();

			if (num_celular != "" && nom_cliente != "" && num_cgcecpf != "") {

				$.ajax({
					type: "POST",
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD",
					data: {
						NOM_CLIENTE: nom_cliente,
						NUM_CELULAR: num_celular,
						NUM_CGCECPF: num_cgcecpf,
						DES_TOKEN: des_token
					},
					beforeSend: function() {
						$("#blocker").show();
					},
					success: function(data) {

						$("#blocker").hide();

						if (data.trim() == "validado") {

							$("#KEY_DES_TOKEN").val($("#DES_TOKEN").val());

							$("#camposToken").fadeOut('fast', function() {
								$("#btnCad").fadeIn('fast');
								$("#formulario").validator("validate");
							});

							$("#formulario").validator('destroy').validator();

						} else {

							$("#erroTkn").fadeIn(1);

							// console.log(data);

						}

					},
					error: function() {
						console.log('Erro');
					}
				});

			}

		}
	</script>

	</body>

</html>