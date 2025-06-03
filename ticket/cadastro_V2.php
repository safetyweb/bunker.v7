<?php

include "../_system/_functionsMain.php";
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
include_once '../totem/funWS/saldo.php';
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idc']));
$chaveCampanha = fnLimpaCampo($_GET['campanha']);

// echo($cod_empresa);
// exit();
// echo($cod_cliente);
//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO=10  limit 1  ";
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

if ($cod_cliente == 0) {

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND ($whereSql)
		       ORDER BY 1 LIMIT 1";

	$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

	$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

	// echo($sqlCampos);

	$lastField = "";

	$qrCampos = mysqli_fetch_assoc($arrayFields);

	$cod_chaveco = $qrCampos['COD_CHAVECO'];
} else {

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND COD_CLIENTE = $cod_cliente";

	$cod_chaveco = 0;
}



$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);



$cpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
$celular = $qrCli['NUM_CELULAR'];
$cartao = $qrCli['NUM_CARTAO'];
$externo = $qrCli['NUM_CARTAO'];
$log_termo = $qrCli['LOG_TERMO'];
$des_token = $qrCli['DES_TOKEN'];


// $sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
$sqlCampos = "SELECT NOM_FANTASI, 
						LOG_CADTOKEN,
						COD_CHAVECO,
		                TIP_RETORNO, 
		                LOG_BLOQUEIAPJ, 
		                TIP_SENHA, 
		                MIN_SENHA, 
		                MAX_SENHA, 
		                REQ_SENHA,
		                TIP_ENVIO,
		                LOG_RECUPERA
		        FROM empresas 
		        where COD_EMPRESA = $cod_empresa";

$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

// echo($sqlCampos);

$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayFields);

$log_cadtoken = $qrCampos['LOG_CADTOKEN'];
$cod_chaveco = $qrCampos['COD_CHAVECO'];
$tip_retorno = $qrCampos['TIP_RETORNO'];
$log_bloqueiapj = $qrCampos['LOG_BLOQUEIAPJ'];
$tip_senha = $qrCampos['TIP_SENHA'];
$min_senha = $qrCampos['MIN_SENHA'];
$max_senha = $qrCampos['MAX_SENHA'];
$req_senha = $qrCampos['REQ_SENHA'];
$tip_envio = $qrCampos['TIP_ENVIO'];
$log_recupera = $qrCampos['LOG_RECUPERA'];

// echo $cpf;

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

// if(strlen($cpf) <= '11'){

// echo '<pre>';
// print_r($buscaconsumidor);
// echo '</pre>';

//           $buscaconsumidor = fnconsulta(fnCompletaDoc($cpf,'F'), $arrayCampos);

//           // print_r($buscaconsumidor);

//           // echo '</pre>';

//       }else{

//       	// echo 'else';

//           $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($cpf,'J'), $arrayCampos); 

// }

// $dado_consulta = "<cpf>$cpf</cpf>\r\n\t";

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

if ($k_num_cartao != "" && $buscaconsumidor['nome'] == "") {

	$sql = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

	$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
	$cod_dominio = $qrBuscaSiteExtrato['COD_DOMINIO'];

	if ($cod_dominio == 2) {
		$extensaoDominio = ".fidelidade.mk";
	} else {
		$extensaoDominio = ".mais.cash";
	}
?>
	<script type="text/javascript">
		parent.$.alert({
			title: 'Aviso',
			content: 'Cartão digitado não encontrado.',
		});
		window.location.href = "https://<?= $des_dominio . $extensaoDominio ?>/consulta_V2.do?id=<?= fnEncode($cod_empresa) ?>&pop=true";
	</script>
<?php
	exit();
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

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

$campoLogin = "";
$dadoLogin = "";

if ($k_num_cartao != "") {
	$buscaconsumidor['cartao'] = $k_num_cartao;
	$campoLogin = "KEY_NUM_CARTAO";
	$dadoLogin = $k_num_cartao;
} else {
	$k_num_cartao = $buscaconsumidor['cartao'];
}

if ($k_num_celular != "") {
	$buscaconsumidor['telcelular'] = $k_num_celular;
	$campoLogin = "KEY_NUM_CELULAR";
	$dadoLogin = $k_num_celular;
} else {
	$k_num_celular = $buscaconsumidor['telcelular'];
}

if ($k_num_cgcecpf != "") {
	$buscaconsumidor['cpf'] = $k_num_cgcecpf;
	$campoLogin = "KEY_NUM_CGCECPF";
	$dadoLogin = $k_num_cgcecpf;
} else {
	$k_num_cgcecpf = $buscaconsumidor['cpf'];
}

if ($k_dat_nascime != "") {
	$buscaconsumidor['datanascimento'] = $k_dat_nascime;
	$campoLogin = "KEY_DAT_NASCIME";
	$dadoLogin = $k_dat_nascime;
} else {
	$k_dat_nascime = $buscaconsumidor['datanascimento'];
}

if ($k_des_emailus != "") {
	$buscaconsumidor['email'] = $k_des_emailus;
	$campoLogin = "KEY_DES_EMAILUS";
	$dadoLogin = $k_des_emailus;
} else {
	$k_des_emailus = $buscaconsumidor['email'];
}

if ($buscaconsumidor['cpf'] == "00000000000") {
	$buscaconsumidor['cpf'] = "";
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if ($cod_cliente != 0) {

	$arrayNome = explode(" ", $qrBuscaCliente['NOM_CLIENTE']);
	$nome = $arrayNome[0];
	$dia_nascime = $qrBuscaCliente['DIA'];
	$mes_nascime = $qrBuscaCliente['MES'];
	$ano_nascime = $qrBuscaCliente['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $qrBuscaCliente['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
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
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
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

if ($chaveCampanha != "") {

	// busca dados campanha 22
	$sqlCampanha = "SELECT * 
	FROM CAMPANHA_HOTSITE 
	WHERE COD_EMPRESA = $cod_empresa 
	AND DES_CHAVECAMP = '#" . $chaveCampanha . "' 
	AND COD_EXCLUSA IS NULL 
	AND CURRENT_DATE BETWEEN DAT_MIN AND DAT_MAX";

	$arrayCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);
	$qrCamp = mysqli_fetch_assoc($arrayCamp);

	if ($qrCamp) {
		$img_bannercad = $qrCamp['IMG_BANNERCAD'];
		$codunivend_campanha = $qrCamp['COD_UNIVEND_PREF'];
	} else {
		$img_bannercad = "";
		$chaveCampanha = "";
		$codunivend_campanha = "";
	}
}
?>

<link href="css/main.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/chosen-bootstrap.css" rel="stylesheet" />
<script src="js/jquery.min.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.mask.min.js"></script>

<style>
	.input-lg {
		font-size: 28px;
		line-height: 1.5;
	}

	body {
		background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
		overflow: hidden;
		padding: 0;
	}

	/* espa?adores */
	.push {
		clear: both;
	}

	.push1 {
		height: 1px;
		clear: both;
	}

	.push5 {
		height: 5px;
		clear: both;
	}

	.push10 {
		height: 10px;
		clear: both;
	}

	.push13 {
		height: 13px;
		clear: both;
	}

	.push15 {
		height: 15px;
		clear: both;
	}

	.push20 {
		height: 20px;
		clear: both;
	}

	.push25 {
		height: 25px;
		clear: both;
	}

	.push30 {
		height: 30px;
		clear: both;
	}

	.push50 {
		height: 50px;
		clear: both;
	}

	.push100 {
		height: 100px;
		clear: both;
	}

	.top20 {
		margin-top: 20px;
	}

	.bottom20 {
		margin-bottom: 20px;
	}

	.top30 {
		margin-top: 30px;
	}

	.bottom30 {
		margin-bottom: 30px;
	}

	.espacer15 {
		padding: 0 0 0 15px;
	}

	.borda {
		border: 1px solid #000;
	}

	.leituraOff {
		background-color: #F2F3F4 !important;
	}

	/*.leituraOff {background-color: #e5e7e9 !important;}*/

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

	#corpoForm {
		width: 100vw !important;
	}

	#caixaForm {
		overflow: auto;
	}

	#caixaImg,
	#caixaForm {
		height: 100vh;
	}

	#caixaImg {
		background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center;
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
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}

	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
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
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
		}



		.navbar img {
			margin-top: 0;
		}

		#caixaImg {
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
			padding: 0;
		}

	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}

	}

	@media (max-height: 824px) and (max-width: 416px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed;
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
			background: #4C4C58 url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
			height: 360px;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {
		body {
			background: #<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed;
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

	.required:after {
		color: #e32;
		content: ' *';
		display: inline;
		font-size: 13px;
		font-weight: bold;
	}

	.help-block {
		font-size: 10px;
		border: 0;
		padding: 0;
		margin: 0
	}
</style>

<!-- Favicons -->
<link rel="icon" href="images/favicon.ico">

</head>

<body>

	<div id="blocker">
		<div style="text-align: center;"><img src="../../images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
	</div>


	<div class="row" id="corpoForm">
		<form data-toggle="validator" role="form2" method="post" id="formulario" action="concluiCadastro_V2.do?id=<?= fnEncode($cod_empresa) ?>&campanha=<?= $chaveCampanha ?>" autocomplete="off">
			<?php
			if ($img_bannercad != "") {
				$des_img_g = $img_bannercad;
				$des_img = $img_bannercad;
				$des_imgmob = $img_bannercad;
			}

			if ($cod_cliente == 0) {
				$mostraSenha = 1;
				include '../totem/includeMaisCash.php';
			} else {

			?>

				<div class="col-md-6 col-xs-12" id="caixaImg">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img_g ?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_img ?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?= $des_imgmob ?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
				</div>
				<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

					<div class="push20"></div>
					<div class="push50"></div>

					<div class="text-center">
						<?php

						if ($log_termo == 'S') {
							$msgInfoCad = "Você já é cadastrado.";
						} else {
							$msgInfoCad = "Você precisa atualizar seu cadastro.";
						}

						?>
						<h3><?= $msgInfoCad ?></h3>
						<p>Para atualizar o seu cadastro, você precisa estar logado.</p>
						<a href="javascript:void(0)" class="btn btn-info btn-block" onclick=' parent.$("#<?= $campoLogin ?>").val("<?= $dadoLogin ?>"); 
																						parent.$("#popModal").modal("toggle"); 
																						parent.$("#senha").focus();
																						parent.$("html,body").animate({scrollTop: (parent.$("#extrato").position().top - 120)},"slow");'>Já tenho senha de acesso. Fazer login</a>
						<div class="push5"></div>
						<div class="text-muted f12">OU</div>
						<div class="push5"></div>
						<a href="recuperarSenha.do?id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&fkey=<?= fnEncode($campoLogin) ?>&vkey=<?= fnEncode($dadoLogin) ?>" class="btn btn-default btn-block" style="margin-top: 0;">Não tenho senha. Primeiro acesso ou Esqueci minha senha</a>
					</div>

				</div>

			<?php
			}
			?>
		</form>
	</div><!-- /container -->

	<script src="https://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<!-- <script src="https://bunker.mk/js/plugins/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script> -->
	<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="https://bunker.mk/js/plugins/jquery.placeholder.js"></script>

	<script type="text/javascript">
		$(function() {

			// $('input, textarea').placeholder();	

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

			parent.$.alert({
				title: "Confirmação",
				content: "Deseja excluir seus dados de forma <b>definitiva</b>?",
				type: 'red',
				buttons: {
					"EXCLUIR": {
						btnClass: 'btn-danger',
						action: function() {

							parent.$.alert({
								title: "Aviso!",
								content: "<b>Todos</b> os dados serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
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
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN&logS=<?= fnEncode($mostraSenha) ?>&campanha=<?= $codunivend_campanha ?>",
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
						console.log(data);
						$("#relatorioToken").html(data);
						$("#formulario").validator('destroy').validator();
						// $("#formulario").validator("validate");	
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
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT&campanha=<?= $codunivend_campanha ?>",
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
					url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD&campanha=<?= $codunivend_campanha ?>",
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
						console.log(data);

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

						}

					},
					error: function() {
						console.log('Erro');
					}
				});

			}

		}
	</script>