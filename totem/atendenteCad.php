<?php
include "../_system/_functionsMain.php";
include_once './funWS/buscaConsumidor.php';
include_once './funWS/buscaConsumidorCNPJ.php';
include_once 'funWS/saldo.php';
//echo fnDebug('true');
$parametros = fnDecode($_GET['key']);
$verificaCad = fnDecode($_GET['v']);
$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idc']));
$arrayCampos = explode(";", $parametros);
// array_pop($arrayCampos);

$url_index = "http://" . $_SERVER["HTTP_HOST"] . "/atendente.do?key=" . $_GET["key"] . "&" . date("Ymdhis") . round(microtime(true) * 1000);
include "noback.php";

// print_r ($arrayCampos);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	// $cpf = $_GET['c1'];                                               
	// $buscaconsumidor = fnconsulta($cpf, $arrayCampos);
}

$cod_univend = $arrayCampos[2];
$cod_empresa = $arrayCampos[4];
$cod_players = $arrayCampos[7];
$c10 = "";

//echo "<h1>".$_GET['param']."</h1>";

//Array ( [nome] => DIOGO LIMA DE SOUZA [cpf] => 01734200014 [sexo] => 1 [datanascimento] => 01/12/1986 [msg] => OK )

//$buscaconsumidor['nome']
//fnEscreve("totem");

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $cod_cliente != 0) {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';

?>
		<script type="text/javascript">
			window.location.href = "atendente.do?key=<?php echo $_GET['key']; ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>";
		</script>
<?php
	} else {
		$_SESSION['last_request']  = $request;

		$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
		$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
		$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
		$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
		$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
		$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

		$whereSql = "";

		if ($k_num_cartao != "") {
			$whereSql .= "OR NUM_CARTAO = '$k_num_cartao' ";
		}

		if ($k_num_celular != "") {
			$whereSql .= "OR NUM_CELULAR = '$k_num_celular' ";
		}

		if ($k_cod_externo != "") {
			$whereSql .= "OR COD_EXTERNO = '$k_cod_externo' ";
		}

		if ($k_num_cgcecpf != "") {
			$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
		}

		if ($k_dat_nascime != "") {
			$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
		}

		if ($k_des_emailus != "") {
			$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
		}

		$whereSql = trim(ltrim($whereSql, "OR"));

		if ($cod_cliente != 0) {
			$sqlCli = "SELECT * FROM CLIENTES 
			       WHERE COD_EMPRESA = $cod_empresa
			       AND COD_CLIENTE = $cod_cliente";
		} else {
			$sqlCli = "SELECT * FROM CLIENTES 
			       WHERE COD_EMPRESA = $cod_empresa
			       AND ($whereSql)
			       ORDER BY 1 LIMIT 1";
		}

		// echo $sqlCli;
		// exit();	

		$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

		$qrCli = mysqli_fetch_assoc($arrayCli);

		if ($qrCli['NUM_CGCECPF'] != "") {
			$k_num_cgcecpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
		}

		if ($qrCli['NUM_CGCECPF'] != "") {
			$k_num_cartao = fnLimpaDoc($qrCli['NUM_CARTAO']);
		}

		if ($qrCli['NUM_CGCECPF'] != "") {
			$k_num_celular = fnLimpaDoc($qrCli['NUM_CELULAR']);
		}

		$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
		$log_termo = $qrCli['LOG_TERMO'];
		$des_token = $qrCli['DES_TOKEN'];

		// $cpf = $k_num_cgcecpf;

		$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

		$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

		// echo($sqlCampos);

		$lastField = "";

		$qrCampos = mysqli_fetch_assoc($arrayFields);

		$log_cadtoken = $qrCampos['LOG_CADTOKEN'];

		// fnconsulta_V2($qrCampos[COD_CHAVECO], $dado, $arrayCampos);

		switch ($qrCampos['COD_CHAVECO']) {

			case 2:
				$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], $k_num_cartao, $arrayCampos);
				break;
			case 3:
				$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], fnLimpaDoc($k_num_celular), $arrayCampos);
				break;

			default:

				if (strlen($k_num_cgcecpf) <= '11') {

					// echo '<pre>';

					$buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf, 'F'), $arrayCampos);

					// print_r($buscaconsumidor);

					// echo '</pre>';

				} else {

					// echo 'else';

					$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf, 'J'), $arrayCampos);
				}

				break;
		}

		// if($cod_empresa = 7){

		// echo '<pre>';
		// print_r($buscaconsumidor);
		// // print_r($buscaconsumidor);
		// echo '</pre>';
		// exit();

		// }

		// $dado_consulta = "<cpf>$cpf</cpf>\r\n\t";

		if ($buscaconsumidor['cpf'] != '00000000000') {

			$cpf = $buscaconsumidor['cpf'];
		} else {
			$cpf = $k_num_cgcecpf;
			$buscaconsumidor['nome'] = "";
		}

		// echo("_".$cpf."_");
		// exit();

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$parametros = fnDecode($_GET['key']);
		$arrayCampos = explode(";", $parametros);



		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

if ($buscaconsumidor['cartao'] != "") {
	$cartao = $buscaconsumidor['cartao'];
	$c10 = $buscaconsumidor['cartao'];
}


//busca dados do layout
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
	//fnEscreve("entrou if");

	$cod_totem = $qrBuscaSiteTotem['COD_TOTEM'];
	$des_logo = $qrBuscaSiteTotem['DES_LOGO'];
	$des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
	$des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
	$des_imgback_mob = $qrBuscaSiteTotem['DES_IMGBACK_MOB'];
	if ($des_imgback_mob == "") {
		$des_imgback_mob = $des_imgback;
	}
	$cod_layout = $qrBuscaSiteTotem['COD_LAYOUT'];

	if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
		$check_CORPERS = '';
	} else {
		$check_CORPERS = "checked";
	}

	$cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
	$cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
	$cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
	$cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
	$cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
	$cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];

	$des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index") {
		$destinoHome = "";
	} else {
		$destinoHome = "banner.do";
	}

	$val_inativo = $qrBuscaSiteTotem['VAL_INATIVO'];

	//fnMostraForm();

	//fnEscreve($tip_contabil);	
} else {
	// $cor_backbar = "34495e";
	//    $cor_backpag = "f2f3f4";
	//    $cor_titulos = "#34495e";
	//    $cor_textos = "#34495e";
	//    $cor_botao = "#0092d8";
	//    $cor_botaoon = "#48c9b0";
}

$sqlPlayer = "SELECT * FROM TOTEM_PLAYERS WHERE COD_EMPRESA = $cod_empresa AND COD_PLAYERS = $cod_players";
$arrayQueryPlayer = mysqli_query(connTemp($cod_empresa, ''), $sqlPlayer);
$qrBuscaTotemPlayer = mysqli_fetch_assoc($arrayQueryPlayer);

if (isset($qrBuscaTotemPlayer)) {

	$log_ticket = $qrBuscaTotemPlayer['LOG_TICKET'];
	$log_nps = $qrBuscaTotemPlayer['LOG_NPS'];
	$val_inativo = $qrBuscaTotemPlayer['VAL_INATIVO'];
	$des_paghome = $qrBuscaTotemPlayer['DES_PAGHOME'];
	$destinoHome = "";

	if ($des_paghome == "index") {
		$destinoHome = "";
	} else if ($des_paghome == "nps") {
		$destinoHome = "pesquisa.do";
	} else if ($des_paghome == "cad") {
		$destinoHome = "consulta_V2.do";
	} else {
		$destinoHome = "banner.do";
	}

	$destinoHome = "atendente.do";
}


// busca info empresa
$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS, NUM_DECIMAIS_B, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

if ($qrEmp['TIP_RETORNO'] == 1) {
	$casasDec = 0;
} else {
	$casasDec = $qrEmp['NUM_DECIMAIS_B'];
}

$log_cadtoken = $qrEmp['LOG_CADTOKEN'];

$dev = $_GET['dev'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];

$des_img = $qrControle['DES_IMG'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_imgmob = $qrControle['DES_IMGMOB'];

if ($cod_cliente == 0) {
	$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";
} else {
	$andOpc = "";
}


if ($k_num_cartao != "") {
	$buscaconsumidor['cartao'] = $k_num_cartao;
} else {
	$k_num_cartao = $buscaconsumidor['cartao'];
}

if ($k_num_celular != "") {
	$buscaconsumidor['telcelular'] = $k_num_celular;
} else {
	$k_num_celular = $buscaconsumidor['telcelular'];
}

if ($k_num_cgcecpf != "") {
	$buscaconsumidor['cpf'] = $k_num_cgcecpf;
} else {
	$k_num_cgcecpf = $buscaconsumidor['cpf'];
}

if ($k_dat_nascime != "") {
	$buscaconsumidor['datanascimento'] = $k_dat_nascime;
} else {
	$k_dat_nascime = $buscaconsumidor['datanascimento'];
}

if ($k_des_emailus != "") {
	$buscaconsumidor['email'] = $k_des_emailus;
} else {
	$k_des_emailus = $buscaconsumidor['email'];
}

if ($buscaconsumidor['cpf'] == "00000000000") {
	$buscaconsumidor['cpf'] = "";
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if ($cod_cliente != 0) {

	$arrayNome = explode(" ", $qrCli['NOM_CLIENTE']);
	$nome = $arrayNome[0];
	$dia_nascime = $qrCli['DIA'];
	$mes_nascime = $qrCli['MES'];
	$ano_nascime = $qrCli['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $qrCli['DAT_ALTERAC'];

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

$sqlImg = "SELECT DES_IMAGEM, DES_IMAGEM_MOB FROM BANNER_TOTEM WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend AND LOG_ATIVO = 'S' AND COD_EXCLUSA = 0";
$arrayImg = mysqli_query(connTemp($cod_empresa, ""), $sqlImg);
$qrImg = mysqli_fetch_assoc($arrayImg);

if ($qrImg['DES_IMAGEM'] != "") {
	$des_img = $qrImg['DES_IMAGEM'];
	$des_img_g = $qrImg['DES_IMAGEM'];
}

if ($qrImg['DES_IMAGEM_MOB'] != "") {
	$des_img_mob = $qrImg['DES_IMAGEM_MOB'];
}

// echo $k_num_cgcecpf;
// exit();


?>


<html lang="pt">

<head>

	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0" />

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<meta http-equiv="X-UA-Compatible" content="IE=10" />
	<meta http-equiv="X-UA-Compatible" content="IE=11" />

	<title>Totem</title>

	<link href="https://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
	<script src="https://bunker.mk/js/jquery.min.js"></script>

	<!-- JQUERY-CONFIRM -->
	<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet" />

	<!-- extras -->
	<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />

	<!-- complement -->
	<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />

	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
	<script src="https://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!--[if IE]>
	  <link rel="stylesheet" type="text/css" href="https://bunker.mk/css/totem.css" />
	<![endif]-->

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico" />

	<?php if ($check_CORPERS == "checked") {
		include "customCss.php";
	} ?>

	<style>
		.input-lg {
			font-size: 28px;
			line-height: 1.5;
		}

		body {
			/*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; */
			background: #fff;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			overflow: hidden;
		}



		.navbar img {
			width: 360px;
			margin-top: -15px;
		}

		.logo-center {
			float: none;
		}

		.logo-center-page {
			float: none;
			width: 160px;
		}

		.logo-left {
			float: left;
		}

		.logo-right {
			float: right;
		}

		<?php if ($cor_backbar == "") { ?>.navbar {
			background-color: transparent;
			background: transparent;
			border-color: transparent;
		}

		<?php } else { ?>.navbar {
			background-color: #<?php echo $cor_backbar; ?>;
			background: #<?php echo $cor_backbar; ?>;
			border-color: #<?php echo $cor_backbar; ?>;
		}

		<?php } ?>
		/*-- bloco saldos --*/

		.blkSaldo {
			margin-top: 1.5em;
		}

		.blkSaldo-left {
			background: #1B4F72;
			background-image: url(../images/lighten.png);
			text-align: center;
			padding: 15px 0 0 0px;
			border-bottom-left-radius: 0.3em;
			-o-border-bottom-left-radius: 0.3em;
			-moz-border-bottom-left-radius: 0.3em;
			border-top-left-radius: 0.3em;
			-o-border-top-left-radius: 0.3em;
			-moz-border-top-left-radius: 0.3em;

		}

		.blkSaldo-middle {
			background: #2874A6;
			background-image: url('../images/lighten.png');
			border-radius: 0;
		}

		.blkSaldo-right {
			background: #cc324b;
			background-image: url('../images/lighten.png');
			border-radius: 0;
		}

		.blkSaldo-lost {
			background: #3498DB;
			background-image: url('../images/lighten.png');
			border-radius: 0;
			border-bottom-right-radius: 0.3em;
			-o-border-bottom-right-radius: 0.3em;
			-moz-border-bottom-right-radius: 0.3em;
			-webkit-border-bottom-right-radius: 0.3em;
			border-top-right-radius: 0.3em;
			-o-border-top-right-radius: 0.3em;
			-moz-border-top-right-radius: 0.3em;
			-webkit-border-top-right-radius: 0.3em;

		}

		.blkSaldo-left span {
			display: block;
			font-size: 15px;
			font-weight: 400;
			color: #fff;
			background-color: #1B4F72;
			padding: 8px 0;
			margin-top: 15px;
			border-bottom-left-radius: 0.3em;
			-o-border-bottom-left-radius: 0.3em;
			-moz-border-bottom-left-radius: 0.3em;

		}

		span.resgatado {
			background-color: #2874A6;
			border-radius: 0;
		}

		span.liberar {
			background-color: #3498DB;
			border-bottom-right-radius: 0.3em;
		}

		span.expirar {
			background-color: #3498DB;
			border-bottom-right-radius: 0.3em;
			-o-border-bottom-right-radius: 0.3em;
			-moz-border-bottom-right-radius: 0.3em;
			-webkit-border-bottom-right-radius: 0.3em;
		}

		.blkSaldo img {
			text-align: center;
			margin: 0 auto;
		}

		/*-- bloco saldo --*/

		.wrapper404 {
			text-align: center;
		}

		.wrapper404 h2 {
			font-family: 'Lato', sans-serif;
			font-weight: 900;
			letter-spacing: -8px;
			font-size: 60px;
			margin: 0;
		}

		.wrapper404 p {
			font-weight: 700;
			font-size: 1.9em;
			margin: 0;
		}

		.wrapper404 span {
			font-size: 0.6em;
		}

		#c7_chosen {
			font-size: 28px;
		}

		#c7_chosen>a {
			height: 66px;
			padding: 18px 27px;
		}

		#c5_chosen {
			font-size: 28px;
		}

		#c5_chosen>a {
			height: 66px;
			padding: 18px 27px;
		}

		#COD_ATENDENTE_chosen {
			font-size: 28px;
		}

		#COD_ATENDENTE_chosen>a {
			height: 66px;
			padding: 18px 27px;
		}

		.chosen-container-single .chosen-single abbr {
			top: 28px;
		}

		.chosen-container-single .chosen-single div b {
			background: url(chosen-sprite.png) no-repeat 0 7px;
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

	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">

</head>

<body>
	<div id="blocker">
		<div style="text-align: center;"><img src="../../images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
	</div>

	<!-- top nav bar -->
	<nav class="navbar navbar-default menuCentral " style="border-radius: 0;">
		<div class="container-fluid">
			<div id="navbar" class="navbar-collapse">
				<div style="text-align: center;">
					<div class="push20"></div>
					<!--<a href="/?key=<?php echo $_GET['key']; ?>"><img class="logo-<?php echo $des_alinham; ?>" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>"></a>-->
					<?php if ($des_logo != "") { ?>
						<a href="<?= $destinoHome ?>?key=<?php echo $_GET['key']; ?>"><img class="logo-<?php echo $des_alinham; ?> img-responsive" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" style="max-width: 250px;"></a>
					<?php } ?>
					<div class="push20"></div>
					<div>
					</div><!--/.nav-collapse -->
				</div>
	</nav>
	<!-- end top nav bar -->


	<div class="row" id="corpoForm">
		<form data-toggle="validator" role="form2" method="post" id="formulario" action="saldoAtendente.do?key=<?php echo $_GET['key']; ?>" autocomplete="off">
			<?php

			// echo('cliente: '.$cod_cliente.'<BR>');
			// echo('cadtoken: '.$log_cadtoken.'<BR>');
			// echo('termos: '.$log_termo.'<BR>');
			// exit();

			if ($cod_cliente != 0 && $verificaCad == 1 && $log_termo == 'S') {
				// echo('https://totem.bunker.mk/preSaldo_V2.do?key='.$_GET['key'].'&idc='.fnEncode($cod_cliente));
			?>
				<script>
					window.location.href = 'preSaldoAtendente.do?key=<?= $_GET['key'] ?>&idc=<?= fnEncode($cod_cliente) ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>';
				</script>
			<?php
			} else if ($cod_cliente != 0 && $verificaCad == 1 && $log_termo == 'N') {

				// echo('https://totem.bunker.mk/validaDados.do?key='.$_GET['key'].'&idc='.fnEncode($cod_cliente));
			?>
				<script>
					// window.location.href = 'validaDados.do?key=<?= $_GET['key'] ?>&idc=<?= fnEncode($cod_cliente) ?>';
					window.location.href = 'preSaldoAtendente.do?key=<?= $_GET['key'] ?>&idc=<?= fnEncode($cod_cliente) ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>';
				</script>
			<?php
			} else if ($cod_cliente == 0 && $log_cadtoken == 'N' && 1 == 0) {

			?>
				<style type="text/css">
					#corpoForm {

						width: 100% !important;
						margin: 0 !important;
						padding: 0 !important;
					}

					#caixaForm {
						overflow: auto;
					}

					#caixaImg,
					#caixaForm {
						height: 100vh;
					}

					#caixaImg {
						background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center;
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
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}

					}

					/* (320x480) Smartphone, Portrait */
					@media only screen and (device-width: 320px) and (orientation: portrait) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}

					}

					/* (320x480) Smartphone, Landscape */
					@media only screen and (device-width: 480px) and (orientation: landscape) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
							background: #fff;
							-webkit-background-size: cover;
							-moz-background-size: cover;
							-o-background-size: cover;
							background-size: cover;
						}

					}

					/* (1024x768) iPad 1 & 2, Landscape */
					@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
							background: #fff;
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
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}

					}

					/* (768x1024) iPad 1 & 2, Portrait */
					@media only screen and (max-width: 768px) and (orientation : portrait) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}

					}

					/* (2048x1536) iPad 3 and Desktops*/
					@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
							background: #fff;
							-webkit-background-size: cover;
							-moz-background-size: cover;
							-o-background-size: cover;
							background-size: cover;
						}



						.navbar img {
							margin-top: 0;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
							padding: 0;
						}

					}

					@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}

					}

					@media (max-height: 824px) and (max-width: 416px) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
							background: #fff;
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
							height: unset;
						}

						#caixaImg {
							background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
							-webkit-background-size: 100% 100%;
							height: 360px;
						}
					}

					/* (320x480) iPhone (Original, 3G, 3GS) */
					@media (max-device-width: 737px) and (max-height: 416px) {
						body {
							/*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
							background: #fff;
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
				</style>

				<div class="col-md-6 col-xs-12" id="caixaImg">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img_g ?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img ?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_imgmob ?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
				</div>

				<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

					<div class="push20"></div>
					<div class="col-md-10 col-md-offset-1 col-xs-12 text-left">

						<div class="alert alert-warning" role="alert" style="margin-bottom: 0px;" id="alertaCadAtendente">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<span class="fal fa-exclamation-triangle"></span>&nbsp;&nbsp;&nbsp; Cliente ainda não cadastrado. Direcione o cliente para os canais de cadastro.
							<div class="push5"></div>
							- Totem
							<div class="push"></div>
							- qrCode
							<div class="push"></div>
							- Hotsite
						</div>

					</div>

					<div class="push10"></div>

					<div class="col-md-10 col-md-offset-1">
						<a href="atendente.do?key=<?= $_GET['key'] ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>" class="btn btn-primary btn-lg btn-block"><i class="fal fa-home" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Voltar para o início</a>
					</div>

				</div>

				<?php
			} else {
				if ($cod_cliente == 0) {
				?>

					<div class="col-md-6 col-md-offset-6">
						<div class="push20"></div>
						<div class="col-md-12 col-xs-12 text-left">

							<div class="alert alert-warning" role="alert" style="margin-bottom: 0px;" id="alertaCadAtendente">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<span class="fal fa-exclamation-triangle"></span>&nbsp;&nbsp;&nbsp;Cliente ainda não cadastrado. Preencha os campos abaixo para cadastrar.
							</div>

						</div>
					</div>
			<?php
				}
				$atendente = 1;
				include 'includeMaisCash.php';
			}


			?>

			<input type="hidden" name="URL_TOTEM" id="URL_TOTEM" value="<?php echo $_GET['key']; ?>">
			<input type="hidden" name="TOKEN_ENVIADO" id="TOKEN_ENVIADO" value="N">
			<input type="hidden" name="TOKEN_VALIDADO" id="TOKEN_VALIDADO" value="N">

		</form>
	</div><!-- /container -->

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script src="https://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="https://bunker.mk/js/plugins/jquery.placeholder.js"></script>
	<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />
	<script>
		<?php if ($val_inativo != "0") { ?>
			var timer;
			window.onload = document.onmousemove = document.onkeypress = function() {
				clearTimeout(timer);
				timer = setTimeout(function() {
					location = '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key']; ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>'
				}, <?php echo $val_inativo; ?>000);
			}

			window.addEventListener('touchstart', function() {
				clearTimeout(timer);
				timer = setTimeout(function() {
					location = '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key']; ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>'
				}, <?php echo $val_inativo; ?>000);
			});

		<?php } ?>

		let log_cadtoken_var = "<?= $log_cadtoken ?>",
			cod_cliente_var = "<?= $cod_cliente ?>";

		$(window).on('load', function() {
			$('#formulario').validator('destroy').validator().validator('update');
		});

		$(function() {

			// $('input, textarea').placeholder();	

			$(document).on('keypress', function(e) {
				if (e.which == 13) {
					e.preventDefault();
					if (log_cadtoken_var == 'S') {
						if ($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'N') {
							console.log("enviado não validado");
							ajxValidaTkn();
						} else if ($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'S' && !$("#CAD").hasClass('disabled')) {
							console.log("enviado validado e campos preenchidos");
							$("#CAD").click();
						} else if ($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'S' && $("#CAD").hasClass('disabled')) {
							$("#formulario").validator('validate');
							$.alert({
								title: "Aviso!",
								content: "É necessário preencher todos os campos obrigatórios!",
								type: 'orange'
							});
						} else {
							if (cod_cliente_var == 0) {
								ajxToken();
							} else {
								ajxTokenAlt();
							}
						}
					}
				}
			});

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

			$("#CAD").click(function(e) {
				$("#formulario").validator('update').validator('validate');
				if ($('#formulario').validator('validate').has('.has-error').length > 0) {
					e.preventDefault();
				}
			});

		});

		if ($('.cpfcnpj').val() != undefined) {
			mascaraCpfCnpj($('.cpfcnpj'));
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
												url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>",
												data: {
													COD_CLIENTE: cod_cliente
												},
												beforeSend: function() {
													$("#blocker").show();
												},
												success: function(data) {
													window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>&<?= date("Ymdhis") . round(microtime(true) * 1000); ?>";
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

			var nom_cliente = $("#NOM_CLIENTE").val(),
				num_celular = $("#NUM_CELULAR").val(),
				cad_num_celular = $("#CAD_NUM_CELULAR").val(),
				key_num_celular = $("#KEY_NUM_CELULAR").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val(),
				cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
				key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val(),
				urltotem = "<?= $_GET['key'] ?>";

			if (num_celular != "" && num_cgcecpf != "") {

				if (num_celular.length == 15) {

					$.ajax({
						type: "POST",
						url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN",
						data: {
							NOM_CLIENTE: nom_cliente,
							NUM_CELULAR: num_celular,
							CAD_NUM_CELULAR: cad_num_celular,
							KEY_NUM_CELULAR: key_num_celular,
							NUM_CGCECPF: num_cgcecpf,
							CAD_NUM_CGCECPF: cad_num_cgcecpf,
							KEY_NUM_CGCECPF: key_num_cgcecpf,
							URL_TOTEM: urltotem,
							LOG_LGPD: "<?= fnEncode($log_lgpd) ?>"
						},
						beforeSend: function() {
							$("#blocker").show();
						},
						success: function(data) {
							$("#relatorioToken").html(data);
							$("#formulario").validator('destroy').validator();
							$("#blocker").hide();
							$("#TOKEN_ENVIADO").val('S');
							// window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>";				
						},
						error: function() {
							console.log('Erro');
						}
					});

				}

			}

		}

		function ajxTokenAlt() {

			var nom_cliente = $("#NOM_CLIENTE").val(),
				num_celular = $("#NUM_CELULAR").val(),
				cad_num_celular = $("#CAD_NUM_CELULAR").val(),
				key_num_celular = $("#KEY_NUM_CELULAR").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val(),
				cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
				key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val(),
				urltotem = "<?= $_GET['key'] ?>";

			if (num_celular != "" && num_cgcecpf != "") {

				if (num_celular.length == 15) {

					$.ajax({
						type: "POST",
						url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT",
						data: {
							NOM_CLIENTE: nom_cliente,
							NUM_CELULAR: num_celular,
							CAD_NUM_CELULAR: cad_num_celular,
							KEY_NUM_CELULAR: key_num_celular,
							NUM_CGCECPF: num_cgcecpf,
							CAD_NUM_CGCECPF: cad_num_cgcecpf,
							KEY_NUM_CGCECPF: key_num_cgcecpf,
							URL_TOTEM: urltotem,
							LOG_LGPD: "<?= fnEncode($log_lgpd) ?>"
						},
						beforeSend: function() {
							$("#blocker").show();
						},
						success: function(data) {
							$("#relatorioToken").html(data);
							$("#formulario").validator('destroy').validator();
							$("#blocker").hide();
							$("#TOKEN_ENVIADO").val('S');
							// window.location.href = "descadastro.do?key=<?php echo $_GET['key']; ?>";				
						},
						error: function() {
							console.log('Erro');
						}
					});

				}

			}

		}

		function ajxValidaTkn() {

			var num_celular = $("#NUM_CELULAR").val(),
				nom_cliente = $("#NOM_CLIENTE").val(),
				des_token = $("#DES_TOKEN").val(),
				num_cgcecpf = $("#NUM_CGCECPF").val(),
				urltotem = "<?= $_GET['key'] ?>";

			if (num_celular != "" && num_cgcecpf != "" && des_token != "") {

				$.ajax({
					type: "POST",
					url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD",
					data: {
						NOM_CLIENTE: nom_cliente,
						NUM_CELULAR: num_celular,
						NUM_CGCECPF: num_cgcecpf,
						DES_TOKEN: des_token,
						URL_TOTEM: urltotem
					},
					beforeSend: function() {
						$("#blocker").show();
					},
					success: function(data) {

						$("#blocker").hide();
						// console.log(data.trim());

						if (data.trim() == "validado") {

							$("#KEY_DES_TOKEN").val($("#DES_TOKEN").val());

							$("#camposToken").fadeOut('fast', function() {
								$("#btnCad").fadeIn('fast');
								$("#formulario").validator("validate");
							});

							$("#formulario").validator('destroy').validator();
							$("#TOKEN_VALIDADO").val('S');

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
		<?php include "nobackJS.js"; ?>
	</script>

</body>

</html>