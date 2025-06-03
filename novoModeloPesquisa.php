<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$tipoAlert = "msgRetorno";
$disableBtn = "";
$cod_campanha = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_categortkt = fnLimpaCampoZero($_REQUEST['COD_CATEGORTKT']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_categor = fnLimpaCampo($_REQUEST['DES_CATEGOR']);
		$des_abrevia = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
		$des_icones = fnLimpaCampo($_REQUEST['DES_ICONES']);
		if (empty($_REQUEST['LOG_DESTAK'])) {
			$log_destak = 'N';
		} else {
			$log_destak = $_REQUEST['LOG_DESTAK'];
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CATEGORIATKT (
				 '" . $cod_categortkt . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $log_destak . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";


			//fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idP'])))) {

	//busca dados do convênio
	$cod_pesquisa = fnDecode($_GET['idP']);
	$sql = "SELECT * FROM PESQUISA WHERE COD_PESQUISA = " . $cod_pesquisa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_pesquisa = $qrBuscaTemplate['COD_PESQUISA'];
		$log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
		if ($log_ativo == "S") {
			$mostraLog_ativo = "checked";
		} else {
			$mostraLog_ativo = "";
		}
		$des_pesquisa = $qrBuscaTemplate['DES_PESQUISA'];
		$abr_pesquisa = $qrBuscaTemplate['ABR_PESQUISA'];
	}
} else {
	$cod_pesquisa = "";
	$log_ativo = "";
	$des_pesquisa = "";
	$abr_pesquisa = "";
}

$sql = "SELECT DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
// fnEscreve($sql);
$qrDom = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

$des_dominio = $qrDom['DES_DOMINIO'];

// fnEscreve($des_dominio);


//liberação das abas
$abaPersona	= "S";
$abaVantagem = "S";
$abaRegras = "S";
$abaComunica = "N";
$abaAtivacao = "N";
$abaResultado = "N";

//$abaPersonaComp = "completed ";
$abaPersonaComp = " ";
$abaVantagemComp = " ";
$abaRegrasComp = " ";
$abaComunicaComp = "active";
$abaAtivacaoComp = "";
$abaResultadoComp = "";


//fnMostraForm();
//fnEscreve($sql);
if ($des_dominio == "") {
	$msgRetorno = "Hotsite não <b>configurado</b>! <a href='action.php?mod=" . fnEncode(1165) . "&id=" . fnEncode($cod_empresa) . "' target='_blank' style='color: #fff;'>Clique aqui</a> para configurar.";
	$msgTipo = 'alert-danger';
	$tipoAlert = "";
	$disableBtn = "disabled";
}

$tinyUrl =  file_get_contents("http://tinyurl.com/api-create.php?url=" . "https://" . $des_dominio . ".fidelidade.mk/pesquisa?idP=" . fnEncode($cod_pesquisa));
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	$parte = explode("/", $tinyUrl);
	$chave_encurtada = end($parte);
	$url_original = "https://" . $des_dominio . ".fidelidade.mk/pesquisa?idP=" . fnEncode($cod_pesquisa);

	$sql = "SELECT * FROM TAB_ENCURTADOR WHERE URL_ORIGINAL = '$url_original' AND COD_EMPRESA = $cod_empresa";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	if (mysqli_num_rows($arrayQuery) == 0) {
		$titulo = "Pesquisa NPS - " . $des_pesquisa . " #" . $cod_pesquisa;
		$funcao = fnEncurtador($titulo, $chave_encurtada, $tinyUrl, $url_original, 'NPS', $cod_empresa, $connAdm->connAdm(), $cod_campanha);
		if ($funcao) {
			$urlEncurtada = "tkt.far.br/" . $funcao;
		} else {
			$urlEncurtada = "";
			$msgRetorno = "Ocorreu um erro ao encurtar a URL, se persistir entre em contato com o suporte.";
			$msgTipo = 'alert-danger';
			$tipoAlert = "alert-danger";
		}
	} else {
		$qrResult = mysqli_fetch_assoc($arrayQuery);
		$urlEncurtada = "tkt.far.br/" . short_url_encode($qrResult['id']);
	}
}

?>

<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">

<style type="text/css">
	.template {
		margin: 0 auto;
		height: auto !important;
		width: 100%;
		margin-top: 50px;

	}

	.connectedSortable {
		list-style-type: none;
		padding: 0;
	}

	.connectedSortable li:not(.normal) {
		min-height: 60px;
		text-align: center;
		width: 80px;
		height: auto !important;
		overflow: hidden;
	}

	#drag-elements {
		float: left;
	}

	#sortable3 {
		float: right;
	}

	#drag-elements li,
	#sortable3 li {
		margin-top: 20px;
		border-radius: 5px;
		background-color: transparent;
		font-size: 25px !important;
	}

	#drop-target {
		float: left;
		margin: 4px;
		min-height: 700px;
		height: auto !important;
		border: 3px dashed #cecece;
		padding: 10px;
		border-radius: 5px;
		width: 100%;
	}

	#drop-target li {
		width: auto;
		background-color: #ffffff;
		border: none;
	}

	.ui-state-default {
		border: 1px solid #c5c5c5;
		background: #f6f6f6;
		font-weight: normal;
		color: #454545;
	}

	.ui-sortable-handle {
		touch-action: none;
	}

	.ui-state-default {
		border: none;
	}

	.ui-state-default a {
		color: #454545;
		text-decoration: none;
	}

	.descricaobloco {
		font-size: 11px;
	}

	.template i {
		margin-top: 10px;
	}

	hr {
		width: 100%;
		border-top: 2px solid #161616;
	}

	hr.divisao {
		width: 100%;
		border-top: 1px dashed #cecece;
		margin: 5px 0;
	}

	.excluirBloco:hover {
		color: #ff4a4a !important;
		cursor: pointer;
	}

	.addImagem {
		position: absolute;
		top: 20px;
		right: 0px;
		font-size: 16px;
		margin-right: 5px;
		color: #cccccc !important;
	}

	.addImagem:hover {
		color: #18bc9c !important;
		cursor: pointer;
	}

	.imagemTicket {
		height: auto;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 10px;
		padding-right: 20px;
	}

	/****** Style Star Rating Widget *****/

	.rating.rate10 {
		border: none;
		float: right;
		width: 280px;
		text-align: right;
	}

	.rating.rate5 {
		border: none;
		float: right;
		width: 230px;
		text-align: right;
	}

	.rating>input {
		display: none;
	}

	.rating>label:before {
		margin: 4px;
		font-size: 16px;
		font-family: 'Font Awesome\ 5 Free';
		font-weight: 900;
		display: inline-block;
		content: "\f005";
	}

	.rating>label.radioType:before {
		margin: 4px;
		font-size: 16px;
		font-family: 'Font Awesome\ 5 Free';
		font-weight: 900;
		display: inline-block;
		content: "\f192";
	}

	.rating>.half:before {
		content: "\f089";
		position: absolute;
	}

	.rating>label {
		color: #ddd;
		float: right;
	}

	/***** CSS Magic to Highlight Stars on Hover *****/

	.rating>input:checked~label,
	/* show gold star when clicked */
	.rating:not(:checked)>label:hover,
	/* hover current star */
	.rating:not(:checked)>label:hover~label {
		color: #FFD700;
	}

	/* hover previous stars in list */

	.rating>input:checked~label.radioType,
	/* show gold star when clicked */
	.rating:not(:checked)>label.radioType:hover,
	/* hover current star */
	.rating:not(:checked)>label.radioType:hover~label {
		color: #4286f4;
	}

	/* hover previous stars in list */

	.rating>input:checked+label:hover,
	/* hover current star when changing rating */
	.rating>input:checked~label:hover,
	.rating>label:hover~input:checked~label,
	/* lighten current selection */
	.rating>input:checked~label:hover~label {
		color: #FFED85;
	}

	.rating>input:checked+label.radioType:hover,
	/* hover current star when changing rating */
	.rating>input:checked~label.radioType:hover,
	.rating>label:hover~input.radioType:checked~label,
	/* lighten current selection */
	.rating>input:checked~label.radioType:hover~label {
		color: #87b2f8;
	}

	.bloco {
		padding: 15px 0;
	}

	.jconfirm .jconfirm-box {
		overflow: inherit;
	}

	.jconfirm .jconfirm-box div.jconfirm-content-pane {
		overflow: inherit;
	}

	.delCondicao:hover {
		color: #2c3e50;
	}

	.blocoTexto,
	.blocoPergunta,
	.blocoAvaliacao {
		cursor: pointer;
	}

	.viewBody {
		float: left;
		margin: 4px;
		height: 500px;
		border: 3px dashed #4eb71d;
		padding: 10px;
		border-radius: 5px;
		width: 100%;
		margin-top: 18px;
	}

	/****** Style Star Rating Widget *****/

	.rating>label.totem {
		font-size: 40px;
	}

	.rating.rate10.totem {
		border: none;
		float: right;
		width: 585px;
	}

	.rating.rate5.totem {
		border: none;
		float: right;
		width: 430px;
	}

	.rating>input {
		display: none;
	}

	.rating>label:before {
		margin: 4px;
		font-size: 16px;
		font-family: 'Font Awesome\ 5 Free';
		font-weight: 900;
		display: inline-block;
		content: "\f005";
	}

	.rating>label.radioType:before {
		margin: 4px;
		font-size: 16px;
		font-family: 'Font Awesome\ 5 Free';
		font-weight: 900;
		display: inline-block;
		content: "\f192";
	}

	.rating>.half:before {
		content: "\f089";
		position: absolute;
	}

	.rating>label {
		color: #ddd;
		float: right;
		text-align: center
	}

	/***** CSS Magic to Highlight Stars on Hover *****/

	.rating>input:checked~label,
	/* show gold star when clicked */
	.rating:not(:checked)>label:hover,
	/* hover current star */
	.rating:not(:checked)>label:hover~label {
		color: #FFD700;
	}

	/* hover previous stars in list */

	.rating>input:checked~label.radioType,
	/* show gold star when clicked */
	.rating:not(:checked)>label.radioType:hover,
	/* hover current star */
	.rating:not(:checked)>label.radioType:hover~label {
		color: #4286f4;
	}

	/* hover previous stars in list */

	.rating>input:checked+label:hover,
	/* hover current star when changing rating */
	.rating>input:checked~label:hover,
	.rating>label:hover~input:checked~label,
	/* lighten current selection */
	.rating>input:checked~label:hover~label {
		color: #FFED85;
	}

	.rating>input:checked+label.radioType:hover,
	/* hover current star when changing rating */
	.rating>input:checked~label.radioType:hover,
	.rating>label:hover~input.radioType:checked~label,
	/* lighten current selection */
	.rating>input:checked~label.radioType:hover~label {
		color: #87b2f8;
	}

	hr {
		width: 100%;
		border-top: 2px solid #161616;
	}

	hr.divisao {
		width: 100%;
		border-top: 1px dashed #cecece;
		margin: 5px 0;
	}

	#footer {
		position: fixed;
		bottom: 0;
		width: 100%;
	}

	.numero {
		font-size: 16px;
		margin-top: -10px;
		text-align: center;
	}

	@media only screen and (min-width: 761px) and (max-width: 1281px) {

		/* 10 inch tablet enter here */
		.lead.titulo {
			margin-top: 50px;
		}
	}

	@media only screen and (max-width: 760px) {

		/* For mobile phones: */
		section#contact {
			padding: 10px 0;
		}

		.lead {
			margin-bottom: 10px;
		}

		#footer .bottom-menu,
		#footer .bottom-menu-inverse {
			padding: 10px 0 0;
			height: 60px;
		}

		.rating>label {
			font-size: 15px;
		}

		.rating.rate10 {
			border: none;
			float: right;
			width: 315px;
		}

		.numero {
			margin-top: -10px;
		}
	}

	#btnAtualizarPesquisa {
		margin-left: 5px;
	}

	.addBox:hover {
		cursor: pointer;
	}

	/*abas */


	/* Tabs panel */
	.tabbable-panel {
		border: 0;
		padding: 10px;
	}

	/* Default mode */
	.tabbable-line>.nav-tabs {
		border: none;
		margin: 0px;
	}

	.tabbable-line>.nav-tabs>li {
		margin-right: 2px;
	}

	.tabbable-line>.nav-tabs>li>a {
		border: 0;
		margin-right: 0;
		color: #737373;
	}

	.tabbable-line>.nav-tabs>li>a>i {
		color: #a6a6a6;
	}

	.tabbable-line>.nav-tabs>li.open,
	.tabbable-line>.nav-tabs>li:hover {
		border-bottom: 4px solid #fbcdcf;
	}

	.tabbable-line>.nav-tabs>li.open>a,
	.tabbable-line>.nav-tabs>li:hover>a {
		border: 0;
		background: none !important;
		color: #333333;
	}

	.tabbable-line>.nav-tabs>li.open>a>i,
	.tabbable-line>.nav-tabs>li:hover>a>i {
		color: #a6a6a6;
	}

	.tabbable-line>.nav-tabs>li.open .dropdown-menu,
	.tabbable-line>.nav-tabs>li:hover .dropdown-menu {
		margin-top: 0px;
	}

	.tabbable-line>.nav-tabs>li.active {
		border-bottom: 4px solid #18bc9c;
		position: relative;
	}

	.tabbable-line>.nav-tabs>li.active>a {
		border: 0;
		color: #333333;
	}

	.tabbable-line>.nav-tabs>li.active>a>i {
		color: #404040;
	}

	.tabbable-line>.tab-content {
		margin-top: -3px;
		background-color: #fff;
		border: 0;
		border-top: 1px solid #eee;
		padding: 15px 0;
	}

	.portlet .tabbable-line>.tab-content {
		padding-bottom: 0;
	}

	/* Below tabs mode */

	.tabbable-line.tabs-below>.nav-tabs>li {
		border-top: 4px solid transparent;
	}

	.tabbable-line.tabs-below>.nav-tabs>li>a {
		margin-top: 0;
	}

	.tabbable-line.tabs-below>.nav-tabs>li:hover {
		border-bottom: 0;
		border-top: 4px solid #fbcdcf;
	}

	.tabbable-line.tabs-below>.nav-tabs>li.active {
		margin-bottom: -2px;
		border-bottom: 0;
		border-top: 4px solid #f3565d;
	}

	.tabbable-line.tabs-below>.tab-content {
		margin-top: -10px;
		border-top: 0;
		border-bottom: 1px solid #eee;
		padding-bottom: 15px;
	}

	.btn-scale {
		/*min-width: 44px;*/
		width: 7.5% !important;
		padding-top: 1%;
		padding-bottom: 1%;
		border-radius: 3px;
		display: inline-block;
		text-align: center;
		font-weight: bold;
		color: black;
		font-family: 'Lato', sans-serif;
	}

	.btn-scale-desc-0,
	.btn-scale-asc-10 {
		background-color: #F44336;
	}

	.btn-scale-desc-0:hover,
	.btn-scale-asc-10:hover {
		background-color: #F44336;
	}

	.btn-scale-desc-1,
	.btn-scale-asc-9 {
		background-color: #FF5722;
	}

	.btn-scale-desc-1:hover,
	.btn-scale-asc-9:hover {
		background-color: #FF5722;
	}

	.btn-scale-desc-2,
	.btn-scale-asc-8 {
		background-color: #FF9800;
	}

	.btn-scale-desc-2:hover,
	.btn-scale-asc-8:hover {
		background-color: #FF9800;
	}

	.btn-scale-desc-3,
	.btn-scale-asc-7 {
		background-color: #FFC107;
	}

	.btn-scale-desc-3:hover,
	.btn-scale-asc-7:hover {
		background-color: #FFC107;
	}

	.btn-scale-desc-4,
	.btn-scale-asc-6 {
		background-color: #FDD835;
	}

	.btn-scale-desc-4:hover,
	.btn-scale-asc-6:hover {
		background-color: #FDD835;
	}

	.btn-scale-desc-5,
	.btn-scale-asc-5 {
		background-color: #FFEB3B;
	}

	.btn-scale-desc-5:hover,
	.btn-scale-asc-5:hover {
		background-color: #FFEB3B;
	}

	.btn-scale-desc-6,
	.btn-scale-asc-4 {
		background-color: #EEFF41;
	}

	.btn-scale-desc-6:hover,
	.btn-scale-asc-4:hover {
		background-color: #EEFF41;
	}

	.btn-scale-desc-7,
	.btn-scale-asc-3 {
		background-color: #C6FF00;
	}

	.btn-scale-desc-7:hover,
	.btn-scale-asc-3:hover {
		background-color: #C6FF00;
	}

	.btn-scale-desc-8,
	.btn-scale-asc-2 {
		background-color: #AEEA00;
	}

	.btn-scale-desc-8:hover,
	.btn-scale-asc-2:hover {
		background-color: #AEEA00;
	}

	.btn-scale-desc-9,
	.btn-scale-asc-1 {
		background-color: #64DD17;
	}

	.btn-scale-desc-9:hover,
	.btn-scale-asc-1:hover {
		background-color: #64DD17;
	}

	.btn-scale-desc-10,
	.btn-scale-asc-0 {
		background-color: #00C853;
	}

	.btn-scale-desc-10:hover,
	.btn-scale-asc-0:hover {
		background-color: #00C853;
	}

	.iphone_bg {
		width: 440px;
		height: 840px;
		background: url(../images/phone_bg.png) no-repeat top;
		background-size: 430px 790px;
		margin: auto;
	}

	.mobile_wrap {
		width: 360px !important;
		height: 640px !important;
		margin: 70px 0 0 38px;
		overflow: hidden;
	}

	.container-wrap {
		overflow-y: auto;
		height: 100%;
		-ms-overflow-style: none !important;
		/* Internet Explorer 10+ */
		scrollbar-width: none !important;
		/* Firefox */
	}

	.container-wrap::-webkit-scrollbar {
		display: none !important;
		/* Safari and Chrome */
	}


	.icon_negativo,
	.icon_positivo {
		border: 2px solid #EEE;
		border-bottom-width: 4px;
		padding: 4px 12px;
		border-radius: 15px;
	}

	.icon_negativo {
		border-right-width: 1px;
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
		padding-left: 16px;
	}

	.icon_positivo {
		border-left-width: 1px;
		border-top-left-radius: 0;
		border-bottom-left-radius: 0;
		padding-right: 16px;
	}

	.icon_negativo i {
		color: #dc3545;
	}

	.icon_positivo i {
		color: #28a745;
	}
</style>


</style>

<div class="push30"></div>

<div class="row">

	<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
	<!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">	 -->

	<link href='https://bevacqua.github.io/dragula/dist/dragula.min.css' rel='stylesheet' type='text/css' />

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1108";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissiblet top30 bottom30" role="alert" id="<?= $tipoAlert ?>">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaCampanhas = 1254;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">

							<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0;">

								<div class="col-md-4 col-sm-6 col-xs-6 ">
									<h4>Pesquisa #<?php echo $cod_pesquisa; ?>: <?php echo $des_pesquisa; ?></h4>
									<input type="hidden" name="COD_PESQUISA" id="COD_PESQUISA" value="<?php echo $cod_pesquisa ?>">
								</div>

								<div class="col-md-1 col-sm-1 col-xs-1 pull-right ">
									<div class="disabledBlock"></div>
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_DESTAK" id="LOG_DESTAK" class="switch" value="S" <?php echo $mostraLog_ativo; ?>>
											<span></span>
										</label>
									</div>
								</div>

							</div>
						</div>

						<div class="push10"></div>

						<div class="tabbable-line">

							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#pesquisa">Configuração da Pesquisa</a></li>
								<li><a data-toggle="tab" href="#lista">Exportação da Lista</a></li>
								<li><a data-toggle="tab" href="#usuarios">Usuários Relatório</a></li>
							</ul>

						</div>

						<div class="push50"></div>

						<div class="tab-content">

							<!-- aba pesquisa -->
							<div id="pesquisa" class="tab-pane active">

								<div class="row">
									<div class="col-md-4 col-sm-6 col-xs-6">
										<h4>Monte aqui sua pesquisa</h4>

										<div class="push10"></div>
										<div class="push15"></div>

										<div class="template">
											<div class="row">
												<div class="col-md-2 col-sm-2 col-xs-2">
													<ul id="drag-elements" class="connectedSortable">
														<?php
														$sql = "SELECT * FROM BLOCOPESQUISA WHERE COD_BLPESQU ORDER BY NUM_ORDENAC";
														$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

														while ($qrListaBlocos = mysqli_fetch_assoc($arrayQuery)) {
														?>
															<li class="ui-state-default shadow grabbable" cod-registr="" id="<?php echo $qrListaBlocos['COD_BLPESQU'] ?>">
																<i class="<?php echo $qrListaBlocos['DES_ICONE'] ?>" aria-hidden="true"></i>
																<div class="descricaobloco"><?php echo $qrListaBlocos['ABV_BLPESQU'] ?></div>
															</li>
														<?php
														}
														?>
													</ul>
												</div>
												<div class="col-md-10 col-sm-10 col-xs-10">
													Clique e arraste os blocos ao lado para montar sua pesquisa
													<ul id="drop-target" class="connectedSortable">
														<?php
														$sql = "SELECT * FROM modelopesquisa
																WHERE COD_EMPRESA = $cod_empresa 
																AND COD_TEMPLATE = $cod_pesquisa
																AND COD_EXCLUSA is null
																ORDER BY NUM_ORDENAC";

														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery)) {
														?>
															<li class="ui-state-default movable" id="BLOCO_<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>" cod-registr="<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>">
																<?php
																switch ($qrListaModelos['COD_BLPESQU']) {
																	case 1: // TEXTO INFORMATIVO
																?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>","Texto")'>
																					<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
																					<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos["COD_REGISTR"]) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 2: // PERGUNTA
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2 blocoPergunta" onclick='alteraTexto(this, "<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>","Pergunta")'>
																					<label for="inputName" class="control-label"><?php echo $qrListaModelos['DES_PERGUNTA']; ?></label>
																					<?php
																					if ($qrListaModelos['DES_OPCOES'] <> "") {
																						$opcoes = json_decode($qrListaModelos['DES_OPCOES'], true);
																					} else {
																						$opcoes = array();
																					}
																					if ($qrListaModelos['DES_TIPO_RESPOSTA'] == "R") {
																						echo "<div class='push10'></div>";
																						echo "<div style='text-align:left'>";
																						foreach ($opcoes as $k =>  $v) {
																							echo "<input name='opc_" . $qrListaModelos['COD_REGISTR'] . "' type='radio'> $v<br>";
																						}
																						echo "</div>";
																					} elseif ($qrListaModelos['DES_TIPO_RESPOSTA'] == "C") {
																						echo "<div class='push10'></div>";
																						echo "<div style='text-align:left'>";
																						foreach ($opcoes as $k =>  $v) {
																							echo "<input name='opc_" . $qrListaModelos['COD_REGISTR'] . "' type='checkbox'> $v<br>";
																						}
																						echo "</div>";
																					} elseif (
																						$qrListaModelos['DES_TIPO_RESPOSTA'] == "RB" ||
																						$qrListaModelos['DES_TIPO_RESPOSTA'] == "CB"
																					) {
																						echo "<div class='push10'></div>";
																						echo "<div style='line-height:36px;'>";
																						foreach ($opcoes as $k =>  $v) {
																							echo "<a style='border:2px solid #CCC;border-radius:6px;padding:5px;white-space:nowrap;' href='javascript:'>$v</a> &nbsp;";
																						}
																						echo "</div>";
																					} elseif ($qrListaModelos['DES_TIPO_RESPOSTA'] == "A") {
																						echo "<div class='push10'></div>";
																						echo "<div style='line-height:36px;'>";
																						foreach ($opcoes as $k =>  $v) {
																							echo "<div style='display:flex;flex-wrap: nowrap;'>";
																							echo "<div style='flex-basis: 100%;text-align:left;'>$v</div>";
																							echo "<div style='text-align:right;'><a class='icon_negativo'><i class='far fa-thumbs-down'></i></a></div>";
																							echo "<div style='text-align:left;'><a class='icon_positivo'><i class='far fa-thumbs-up'></i></a></div>";
																							echo "</div>";
																							echo "<div class='push1'></div>";
																						}
																						echo "</div>";
																					} else {
																					?>
																						<input type="text" class="form-control input-sm" value="">
																					<?php }
																					if ($qrListaModelos['DES_IMAGEM'] <> "") {
																						echo "<div class='push30'></div>";
																						echo "<img style='width:100%' src='media/clientes/" . $cod_empresa . "/pesquisa/" . $qrListaModelos['DES_IMAGEM'] . "'>";
																					}
																					?>
																					<input type="hidden" class="des_pergunta" value="<?php echo $qrListaModelos['DES_PERGUNTA']; ?>">
																					<input type="hidden" class="des_tipo_resposta" value="<?php echo $qrListaModelos['DES_TIPO_RESPOSTA']; ?>">
																					<input type="hidden" class="num_opcoes" value="<?php echo $qrListaModelos['NUM_OPCOES']; ?>">
																					<input type="hidden" class="des_imagem" value="<?php echo $qrListaModelos['DES_IMAGEM']; ?>">
																					<textarea style="display:none;" class="des_opcoes"><?php echo $qrListaModelos['DES_OPCOES']; ?></textarea>
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 3: // SALDO DE PONTOS
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
																					<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
																					<h6>Número Cartão: 1234 5678 9012 3456</h6>
																					<h6>Saldo: R$ 0,18 31/05/2017</h6>
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 4: // IMAGEM
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
																					<div class="div-imagem">
																						<?php
																						if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
																						?>
																							<div class="imagemTicket">
																								<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
																								<input type="file" cod_registr='<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																							</div>
																						<?php
																						} else {
																						?>
																							<div class="imagemTicket">
																								<img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
																								<input type="file" cod_registr='<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																							</div>
																						<?php
																						}
																						?>
																					</div>
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 5: // AVALIAÇÃO
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-2 col-sm-2 col-xs-2 col-xs-offset-10">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																				<div class="col-md-12 col-sm-12 col-xs-12 blocoAvaliacaoComentado">
																					<a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1509) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idr=<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>&pop=true" data-title="Bloco de Avaliação">
																						<?php

																						if ($qrListaModelos['DES_PERGUNTA'] != '') {
																						?>
																							<h5><?php echo $qrListaModelos['DES_PERGUNTA']; ?></h5>
																							<?php
																							$contador = 0;
																							if ($qrListaModelos['TIP_BLOCO'] != "estrela") {
																							?>
																								<div class="chart-scale">
																									<?php
																									while ($contador <= $qrListaModelos['NUM_QUANTID']) {
																									?>
																										<!-- <input type="radio" name="rating" value="5" /><label class= "star<?php echo $contador; ?> <?php echo $qrListaModelos['TIP_BLOCO']; ?>Type full" for="star"></label> -->
																										<div class="btn-scale btn-scale-desc-<?= $contador ?>"><?= $contador ?></div>
																									<?php
																										$contador++;
																									}
																									?>
																								</div>
																							<?php
																								$sql = "SELECT * FROM TIPO_ROTULO_AVALIACAO_PESQUISA WHERE COD_ROTULO=0" . $qrListaModelos['COD_ROTULO'];
																								$rotulo = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
																								echo "<table style='width:100%'>";
																								echo "<td style='font-size:11px;text-align:left'>" . $rotulo["DES_ROTULO_MIN"] . "</td>";
																								echo "<td style='font-size:11px;text-align:right'>" . $rotulo["DES_ROTULO_MAX"] . "</td>";
																								echo "</table>";
																							} else {
																							?>
																								<div class="row">
																									<div id="rateYo_<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>" style="margin-left: auto; margin-right: auto;"></div>
																								</div>
																								<script>
																									$(function() {
																										$("#rateYo_<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>").rateYo({
																											numStars: "<?= $qrListaModelos['NUM_QUANTID'] ?>",
																											rating: "70%",
																											starWidth: "17px",
																											spacing: "4px",
																											halfStar: false,
																											fullStar: true
																										});
																									});
																								</script>
																							<?php
																							}

																							?>


																						<?php
																						} else {
																						?>
																							<h5><span class="fas fa-star-half-alt"></span> Clique para configurar a <b>avaliação</b> <span class="fas fa-star-half-alt"></span></h5>
																						<?php
																						}
																						?>
																					</a>
																				</div>
																				<div class="push10"></div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 6: // LOGIN
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
																					<header>
																						<p class="lead">Faça seu login para responder nossas pesquisas!</p>
																					</header>
																					<div class="row">
																						<div class="col-md-12 col-sm-12 col-md-xs">
																							<input type="text" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
																							<div class="push10"></div>
																							<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" °>Fazer login</button>
																							<div class="push10"></div>
																							<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 7: // LOGIN COM SENHA
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2">
																					<header>
																						<p class="lead">Faça seu login para responder nossas pesquisas!</p>
																					</header>
																					<div class="row">
																						<div class="col-md-12 col-sm-12 col-md-xs">
																							<input type="text" name="cpf" class="form-control input-hg" placeholder="Seu CPF" maxlength="14">
																							<div class="push10"></div>
																							<input type="password" id="senha" name="senha" class="form-control input-hg" placeholder="Sua Senha">
																							<div class="push10"></div>
																							<button type="button" class="btn btn-primary btn-hg btn-block" name="btLogin" °>Fazer login</button>
																							<div class="push10"></div>
																							<div class="errorLogin" style="color: red; text-align: center; display: none">Usuário/senha inválidos.</div>
																						</div>
																					</div>
																				</div>
																				<div class="col-md-2 col-sm-2 col-xs-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																	<?php
																		break;
																	case 8: // SMART LOGIN
																	?>
																		<center class="bloco">
																			<div class="row">
																				<div class="col-xs-8 col-xs-offset-2 text-center">
																					<span class="fal fa-key fa-2x"></span>
																				</div>
																				<div class="col-md-2">
																					<a class="excluirBloco" onclick='excBloco("<?= fnEncode($qrListaModelos['COD_REGISTR']) ?>")'><i class="far fa-trash-alt text-danger" style="margin: 0" aria-hidden="true"></i></a>
																				</div>
																			</div>
																		</center>
																		<hr class="divisao" />
																<?php
																		break;
																}

																?>

															</li>
														<?php
														}
														?>

														<div class="row not-movable" id="trigger">
															<?php
															$sqlFinal = "SELECT DES_FINALIZA FROM PESQUISA 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND COD_PESQUISA = $cod_pesquisa";
															$qrFinal = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlFinal));
															?>

															<center class="bloco">
																<div class="row">
																	<div class="col-md-8 col-sm-8 col-md-xs col-xs-offset-2" onclick='alteraFinalizacao(this, "Texto")'>
																		<label for="inputName" class="control-label"><?php echo $qrFinal['DES_FINALIZA']; ?></label>
																		<input type="hidden" class="des_pergunta" value="<?php echo $qrFinal['DES_FINALIZA']; ?>">
																	</div>
																</div>
															</center>
															<!-- <hr class="divisao"/> -->
														</div>

													</ul>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-8 col-sm-6 col-xs-6">
										<div class="col-md-12">
											<h4>Visualize sua pesquisa
												<!--<span class="pull-right">\dag sgd</span>-->
											</h4>
											<div class="push10"></div>
										</div>

										<div class="row">
											<div class="col-md-8">

												<div class="col-xs-4">
													<button type="button" class="btn btn-info" id="btnAtualizarPesquisa"><i class="fal fa-redo" aria-hidden="true"></i>&nbsp; Atualizar Pesquisa</button>
												</div>

												<div class="col-xs-5">
													<input type="text" id="linkPesquisa" class="form-control input-md pull-right text-center" value='<?= $urlEncurtada ?>' readonly>
													<input type="hidden" id="LINK_SEMCLI" value='<?= $urlEncurtada ?>'>
												</div>

												<div class="col-xs-3">
													<input type="text" id="COD_CLIENTE" class="form-control input-md pull-right text-center int" placeholder="Cód. Cliente" value="<?= $urlEncurtada ?>" onkeyup="shortenUrl($(this).val())">
												</div>

											</div>

											<div class="col-md-4">

												<div class="col-md-6">
													<button type="button" class="btn btn-default" id="btnPesquisa" <?= $disableBtn ?>><i class="fas fa-copy" aria-hidden="true"></i>&nbsp; Copiar Link</button>
													<script type="text/javascript">
														$("#btnPesquisa").click(function() {
															if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
																var el = $("#linkPesquisa").get(0);
																var editable = el.contentEditable;
																var readOnly = el.readOnly;
																el.contentEditable = true;
																el.readOnly = false;
																var range = document.createRange();
																range.selectNodeContents(el);
																var sel = window.getSelection();
																sel.removeAllRanges();
																sel.addRange(range);
																el.setSelectionRange(0, 999999);
																el.contentEditable = editable;
																el.readOnly = readOnly;
															} else {
																$("#linkPesquisa").select();
															}
															document.execCommand('copy');
															$("#linkPesquisa").blur();
															$("#btnPesquisa").text("Link Copiado");
															setTimeout(function() {
																$("#btnPesquisa").html("<i class='fas fa-copy' aria-hidden='true'></i>&nbsp; Copiar Link");
															}, 2000);
														});
													</script>
												</div>

												<?php
												// echo $urlEncurtada; 
												?>

												<div class="col-md-6">
													<a href='https://<?= $urlEncurtada ?>' id="btnLink" <?= $disableBtn ?> class="btn btn-default pull-right" target="_blank"><i class="fas fa-arrow-right" aria-hidden="true"></i>&nbsp; Acessar Pesquisa</a>
												</div>

											</div>
										</div>

										<div class="push20"></div>

										<div class="col-md-12">

											<div class="viewBody" style="height: 1000px;">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<header>
														<h4 class="lead titulo">
															<b>Pesquisa</b>
															<span class="pull-right">
																<a href="javascript:void(0)" class="previewMobile"><i class="fal fa-desktop"></i></a> &nbsp;&nbsp;
																<a href="javascript:void(0)" class="previewMobile"><i class="fal fa-mobile"></i></a></span>
														</h4>
													</header>
													<hr class="divisao" />
													<div class="row">

														<div class="col-xs-12">
															<iframe frameborder="0" id="blocoPesquisa" src="https://<?= $des_dominio ?>.fidelidade.mk/pesquisa?idP=<?= fnEncode($cod_pesquisa) ?>&idc=<?= fnEncode('preview') ?>" style="width: 100%; height: 800px;"></iframe>
														</div>

														<div class="iphone_bg" style="display: none">
															<div class="row">
																<div class="col-md-12">
																	<div class="mobile_wrap">
																		<div class="container-wrap col-xs-12">
																			<iframe frameborder="0" id="blocoPesquisaMob" src="https://<?= $des_dominio ?>.fidelidade.mk/pesquisa?idP=<?= fnEncode($cod_pesquisa) ?>&idc=<?= fnEncode('preview') ?>" style="width: 100%; height: 640px;"></iframe>
																		</div>
																	</div>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>

										</div>

									</div>

								</div>

							</div>

							<!-- aba lista -->
							<div id="lista" class="tab-pane fade">
								<iframe frameborder="0" id="conteudoAba" src="action.php?mod=<?php echo fnEncode(1511) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&idp=<?= fnEncode($cod_pesquisa) ?>&idd=<?= fnEncode($des_dominio) ?>&pop=true" style="width: 100%; min-height: 70vh;"></iframe>
							</div>

							<!-- aba usuários -->
							<div id="usuarios" class="tab-pane fade">
								<iframe frameborder="0" id="conteudoAba" src="action.php?mod=<?php echo fnEncode(1610) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&idp=<?= fnEncode($cod_pesquisa) ?>&idd=<?= fnEncode($des_dominio) ?>&pop=true" style="width: 100%; min-height: 70vh;"></iframe>
							</div>

						</div>


						<div class="100"></div>

						<!-- <input type="hidden" name="REFRESH_CONDICAO" id="REFRESH_CONDICAO" value="N" data-id=""> -->
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push100"></div>



				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>
</div>


<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1' style="width: 700px; margin: auto;">
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 85%"></iframe>
			</div>
		</div>
	</div>
</div>


<div class="push20"></div>

<script src="js/jquery-ui.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

<script type="text/javascript">
	$(function() {

		var previewOverlay = 0;

		jQuery('.previewMobile').click(function() {

			if (previewOverlay == 0) {
				jQuery("#blocoPesquisa").fadeOut('fast', function() {
					jQuery(".iphone_bg").fadeIn('fast');
				});
				previewOverlay = 1;
			} else {
				jQuery(".iphone_bg").fadeOut('fast', function() {
					jQuery("#blocoPesquisa").fadeIn('fast');
				});
				previewOverlay = 0;
			}

		});

		jQuery('body').on('click', '.upload-image', function() {
			jQuery(this).siblings().click();
		});

		jQuery('body').on('change', '.image-file', function() {
			var formData = new FormData();
			formData.append('arquivo', jQuery(this)[0].files[0]);
			formData.append('id', "<?= fnEncode($cod_empresa) ?>");
			formData.append('cod_registr', jQuery(this).attr('cod_registr'));
			nomeArquivo = jQuery(this)[0].files[0].name;

			salvarImg(jQuery(this).attr('cod_registr'), nomeArquivo);

			var div_imagem = jQuery(this).parent().parent();

			jQuery.ajax({
				url: 'uploads/uploadpro.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					div_imagem.html(data);
					console.log(data);
				}
			});
		});

		function $(id) {
			return document.getElementById(id);
		}

		// DRAG'N'DROP-----------------------------------------------------------------------------

		dragula([$('drag-elements'), $('drop-target')], {
			revertOnSpill: true,

			copy: function(el, source) {
				return source === $('drag-elements');
			},
			accepts: function(el, target, handle, sibling) {

				if (sibling) {
					id_parente = sibling.id;
				} else {
					id_parente = "";
				}

				if (id_parente != "trigger") {
					return target !== $('drag-elements');
				}
			},
			moves: function(el, source, target, handle, sibling) {

				if (el.id == "trigger") {
					return false;
					console.log("false");
				} else {
					return true;
					console.log("true");
				}
			}
		}).on('drop', function(el, source, target) {

			id_elemento = el.id;

			if (jQuery.isNumeric(id_elemento)) {
				el.id = "MOVED_" + id_elemento;
			}

			if (source.id != target.id) {

				jQuery.ajax({
					method: 'POST',
					url: 'ajxBlocoNovaPesquisa.do?opcao=addBloco',
					data: {
						COD_BLPESQU: id_elemento,
						COD_PESQUISA: "<?= fnEncode($cod_pesquisa) ?>",
						COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>"
					},
					beforeSend: function() {
						$(el.id).innerHTML = '<div class="loading" style="width: 100%;"></div>';
					},
					success: function(data) {
						Ids = "";
						jQuery("#" + el.id).replaceWith(data);
						console.log(data);
					},
					error: function(data) {
						$(el.id).innerHTML = '<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Itens não encontrados...</p>';
						console.log(data);
					}
				});

			} else {

				var Ids = "";
				jQuery('#drop-target .movable').each(function(index) {
					Ids += jQuery(this).attr('id').substring(6) + ",";
				});

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));

				execOrdenacao(arrayOrdem, 6, "<?= $cod_empresa ?>");

			}

		});

		// ----------------------------------------------------------------------------------------	

	});

	// EXECUTAR ORDENAÇÃO ---------------------------------------------------------------------

	function execOrdenacao(p1, p2, p3) {
		jQuery.ajax({
			type: "GET",
			url: "ajxOrdenacaoEmp.php",
			data: {
				ajx1: p1,
				ajx2: p2,
				ajx3: p3
			},
			success: function(data) {
				console.log(data);
			},
			error: function(data) {
				console.log(data);
			}
		});
	}

	// ----------------------------------------------------------------------------------------

	// SALVAR UPLOAD DE IMAGEM ----------------------------------------------------------------

	function salvarImg(cod_registr, des_img) {
		jQuery.ajax({
			type: "POST",
			url: "ajxBlocoNovaPesquisa.do?opcao=img",
			data: {
				COD_REGISTR: cod_registr,
				DES_IMAGEM: des_img,
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>"
			},
			success: function(data) {
				console.log(data);
			},
			error: function(data) {
				console.log(data);
			}
		});
	}

	// ----------------------------------------------------------------------------------------

	// EXCLUIR BLOCO --------------------------------------------------------------------------

	function excBloco(cod_registr) {

		$.ajax({
			type: "POST",
			url: "ajxBlocoNovaPesquisa.do?opcao=exc",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_REGISTR: cod_registr
			},
			beforeSend: function() {
				$('#BLOCO_' + cod_registr).html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$('#BLOCO_' + cod_registr).remove();
				var Ids = "";
				jQuery('#drop-target .movable').each(function(index) {
					Ids += jQuery(this).attr('id').substring(6) + ",";
				});

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));

				execOrdenacao(arrayOrdem, 6, "<?= $cod_empresa ?>");
				console.log(data);
			},
			error: function(data) {
				$('#BLOCO_' + cod_registr).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				console.log(data);
			}
		});

	}

	// ----------------------------------------------------------------------------------------

	// SALVAR TEXTO DAS PERGUNTAS E BLOCOS DE TEXTO ---------------------------------------

	function alteraTexto(obj, cod_registr, tipo) {

		var thisTexto = $(obj);
		var des_pergunta = $(obj).find('.des_pergunta').val() == undefined ? "" : $(obj).find('.des_pergunta').val();
		var des_tipo_resposta = $(obj).find('.des_tipo_resposta').val() == undefined ? "T" : $(obj).find('.des_tipo_resposta').val();
		var num_opcoes = $(obj).find('.num_opcoes').val() == undefined ? "" : $(obj).find('.num_opcoes').val();
		var des_opcoes = $(obj).find('.des_opcoes').val() == undefined ? "" : $(obj).find('.des_opcoes').val();
		var des_imagem = $(obj).find('.des_imagem').val() == undefined ? "" : $(obj).find('.des_imagem').val();

		if (tipo == "Texto") {
			var icone = 'fa fa-text-height';
		} else {
			var icone = 'fa fa-question-circle';
		}

		var html = "";

		html += '<input type="text" placeholder="Seu texto" maxlength="200" class="texto form-control input-sm" value="' + des_pergunta + '" />';
		if (tipo == "Pergunta") {
			des_tipo_resposta = (des_tipo_resposta == "" ? "T" : des_tipo_resposta);
			num_opcoes = (num_opcoes == "" || num_opcoes <= 0 ? 2 : num_opcoes);

			html += "<h4>Tipo Resposta</h4>";
			html += "<select class='resposta form-control input-sm' onChange=\"alteraResposta('')\">";
			html += "<option " + (des_tipo_resposta == "TO" ? "selected" : "") + " value='TO' >Texto</option>";
			html += "<option " + (des_tipo_resposta == "T" ? "selected" : "") + " value='T' >Texto Obrigatório</option>";
			html += "<option " + (des_tipo_resposta == "R" ? "selected" : "") + " value='R' >Selecionar uma Op&ccedil;&atilde;o - Lista</option>";
			html += "<option " + (des_tipo_resposta == "RB" ? "selected" : "") + " value='RB'>Selecionar uma Op&ccedil;&atilde;o - Bloco</option>";
			html += "<option " + (des_tipo_resposta == "C" ? "selected" : "") + " value='C' >Selecionar M&uacute;ltiplas Op&ccedil;&otilde;es - Lista</option>";
			html += "<option " + (des_tipo_resposta == "CB" ? "selected" : "") + " value='CB'>Selecionar M&uacute;ltiplas Op&ccedil;&otilde;es - Bloco</option>";
			html += "<option " + (des_tipo_resposta == "A" ? "selected" : "") + " value='A' >Avalia&ccedil;&atilde;o</option>";
			html += "</select>";

			html += "<div class='resp_opcoes' style='display:none;'>";
			html += "<h5>Qtd. Op&ccedil;&otilde;es</h5>";
			html += "<select class='qtd_opcoes form-control input-sm' onChange='alteraResposta()'>";
			for (i = 2; i <= 10; i++) {
				html += "<option " + (num_opcoes == i ? "selected" : "") + " value='" + i + "'>" + i + "</option>";
			}
			html += "</select>";

			html += "<h5>Op&ccedil;&otilde;es</h5>";
			html += "<div class='list_opcoes'>";
			html += "</div>";

			html += "</div>";

			html += "<h5>Imagem</h5>";
			html += "<div class='input-group'>";
			html += "	<span class='input-group-btn'>";
			html += "		<a type='button' name='btnBusca' id='btnBusca' style='height:35px;' class='btn btn-primary upload' idinput='DES_IMAGEM' extensao='img' onClick='uploadImg(this)'><i class='fa fa-cloud-upload' aria-hidden='true'></i></a>";
			html += "	</span>";
			html += "	<input type='text' name='DES_IMAGEM' id='DES_IMAGEM' class='DES_IMAGEM form-control input-sm' style='border-radius: 0 3px 3px  0;' value='" + des_imagem + "'>";
			html += "</div>																";
			html += "<span class='help-block'>(.jpg, .png 1920px X 1080px)</span>															";

			html += "<script>alteraResposta('" + des_opcoes + "');<" + "/" + "script>";

		}


		$.confirm({
			icon: icone,
			title: 'Tipo ' + tipo,
			content: html,
			buttons: {
				formSubmit: {
					text: 'Salvar',
					btnClass: 'btn-blue',
					action: function() {
						var texto = this.$content.find('.texto').val();
						var tp_resp = this.$content.find('.resposta').val();
						var qtd_opcoes = this.$content.find('.qtd_opcoes').val();
						var img = this.$content.find('.DES_IMAGEM').val();
						if (!texto) {
							$.alert('Por favor, digite o texto!');
							return false;
						}

						var opcoes = {};
						$("input.opcoes").each(function() {
							opcoes[$(this).attr('name')] = $(this).val();
						});

						var json_opcoes = JSON.stringify(opcoes);
						thisTexto.text(texto);

						$.ajax({
							type: "POST",
							url: "ajxBlocoNovaPesquisa.do?opcao=texto",
							data: {
								COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
								COD_REGISTR: cod_registr,
								DES_PERGUNTA: texto,
								DES_TIPO_RESPOSTA: tp_resp,
								NUM_OPCOES: qtd_opcoes,
								DES_OPCOES: json_opcoes,
								DES_IMAGEM: img
							},
							beforeSend: function() {
								$('#BLOCO_' + cod_registr).html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								$('#BLOCO_' + cod_registr).replaceWith(data);
								console.log(data);
							},
							error: function(data) {
								$('#BLOCO_' + cod_registr).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								console.log(data);
							}
						});

					}
				},
				cancelar: function() {
					//close
				},
			}
		});

	}

	function alteraResposta(opc) {
		if (opc != "" && opc != undefined) {
			opc = $.parseJSON(opc);
		} else {
			opc = [];
		}
		var resp = $(".resposta").val();
		if (resp == "R" || resp == "C" || resp == "RB" || resp == "CB" || resp == "A") {
			$(".resp_opcoes").show();
		} else {
			$(".resp_opcoes").hide();
		}

		var qtd = $(".qtd_opcoes").val();
		$(".list_opcoes input").addClass("remove");
		for (i = 1; i <= qtd; i++) {
			var c = "opcao" + i;
			if (opc[c] != undefined) {
				val = opc[c];
			} else {
				val = "Op&ccedil;&atilde;o " + i;
			}
			if ($("." + c).length <= 0) {
				$(".list_opcoes").append('<input style="margin-bottom:2px;" type="text" placeholder="Op&ccedil;&atilde;o ' + i + '" class="opcoes ' + c + ' form-control input-sm" name="' + c + '" value="' + val + '" />');
			} else {
				$("." + c).removeClass("remove");
			}
		}
		$(".list_opcoes input.remove").remove();

	}

	// ---------------------------------------------------------------------------------------

	// SALVAR TEXTO DAS PERGUNTAS E BLOCOS DE TEXTO ---------------------------------------

	function alteraFinalizacao(obj, tipo) {

		var thisTexto = $(obj);
		var des_pergunta = $(obj).find('.des_pergunta').val() == undefined ? "" : $(obj).find('.des_pergunta').val();

		if (tipo == "Texto") {
			var icone = 'fa fa-text-height';
		} else {
			var icone = 'fa fa-question-circle';
		}

		$.confirm({
			icon: icone,
			title: 'Tipo ' + tipo,
			content: '' +
				'<input type="text" placeholder="Seu texto" maxlength="200" class="texto form-control input-sm" value="' + des_pergunta + '" />',
			buttons: {
				formSubmit: {
					text: 'Salvar',
					btnClass: 'btn-blue',
					action: function() {
						var texto = this.$content.find('.texto').val();
						if (!texto) {
							$.alert('Por favor, digite o texto!');
							return false;
						}
						thisTexto.text(texto);

						$.ajax({
							type: "POST",
							url: "ajxBlocoNovaPesquisa.do?opcao=finalizacao",
							data: {
								COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
								COD_PESQUISA: "<?= fnEncode($cod_pesquisa) ?>",
								DES_PERGUNTA: texto
							},
							beforeSend: function() {
								$('#trigger').html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								$('#trigger').replaceWith(data);
								console.log(data);
							},
							error: function(data) {
								$('#trigger').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								console.log(data);
							}
						});

					}
				},
				cancelar: function() {
					//close
				},
			}
		});
	}

	// ---------------------------------------------------------------------------------------

	// RECARREGAR BLOCO DE CONDIÇÃO SE HOUVER ALTERAÇÃO VIA MODAL --------------------------

	function refreshCondicao(cod_registr) {

		$.ajax({
			type: "POST",
			url: "ajxBlocoNovaPesquisa.do?opcao=rating",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_REGISTR: cod_registr
			},
			beforeSend: function() {
				$('#BLOCO_' + cod_registr).html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$('#BLOCO_' + cod_registr).replaceWith(data);
				console.log(data);
			},
			error: function(data) {
				$('#BLOCO_' + cod_registr).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				console.log(data);
			}
		});

	}

	// ---------------------------------------------------------------------------------------

	// GERAR LINK ENCURTADO --------------------------------------------------------------

	var timer = null;

	function shortenUrl(cod_cliente) {
		if (cod_cliente != "") {

			if (timer) {
				clearTimeout(timer); //cancel the previous timer.
				timer = null;
			}

			timer = setTimeout(function() {
				$.ajax({
					method: 'POST',
					url: 'ajxBlocoNovaPesquisa.do?opcao=shortenUrl',
					data: {
						COD_EMPRESA: '<?= fnEncode($cod_empresa); ?>',
						COD_CLIENTE: cod_cliente,
						COD_PESQUISA: <?= $cod_pesquisa ?>,
						DES_DOMINIO: "<?= $des_dominio ?>"
					},
					beforeSend: function() {
						$('#linkPesquisa').val('Gerando link...');
					},
					success: function(data) {
						console.log(data);
						$('#linkPesquisa').val(data);
						$('#btnLink').attr('href', data);
					}
				});
			}, 600);

		} else {
			$('#linkPesquisa').val($('#LINK_SEMCLI').val());
			$('#btnLink').attr('href', $('#LINK_SEMCLI').val());
		}
	}

	// ----------------------------------------------------------------------------------------

	// INÍCIO CÓDIGO FONTE REFERENTE AO VISUALIZADOR DE PESQUISA ------------------------------


	ajxIniciarPesquisas(<?php echo $cod_pesquisa ?>);

	jQuery('body').on('click', '.btnContinuar', function() {
		proximoBlocoSemSalvar($(this));
	});

	jQuery('body').on('click', '#btnAtualizarPesquisa', function() {
		ajxIniciarPesquisas(<?php echo $cod_pesquisa ?>);
	});

	// function proximoBlocoSemSalvar(_this){
	// 	var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
	// 	var pCodPesquisa = _this.attr('cod-pesquisa');
	// 	var pCodRegistro = _this.attr('cod-registro');						
	// 	$.ajax({
	// 		type: "GET",
	// 		url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
	// 		data: { opcao: 'proximoBlocoPesquisa', cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: <?php echo $cod_empresa; ?> },
	// 		beforeSend:function(){
	// 			$('#blocoPesquisa, #blocoPesquisaMob').html('<div class="loading" style="width: 100%;"></div>');
	// 		},						
	// 		success: function(data) {
	// 			$('#blocoPesquisa, #blocoPesquisaMob').html(data);
	// 		}
	// 	});				
	// }		

	function ajxIniciarPesquisas(pCodPesquisa) {
		var iframe = document.getElementById("blocoPesquisa");
		var iframeMob = document.getElementById("blocoPesquisaMob");
		iframe.src = iframe.src;
		iframeMob.src = iframeMob.src;
		// $.ajax({
		// 	type: "GET",
		// 	url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
		// 	data: { opcao: 'iniciarPesquisaVisualizacao', cod_pesquisa: pCodPesquisa, cod_empresa: <?php echo $cod_empresa; ?> },
		// 	beforeSend:function(){
		// 		$('#blocoPesquisa, #blocoPesquisaMob').html('<div class="loading" style="width: 100%;"></div>');
		// 	},						
		// 	success: function(data) {
		// 		$('#blocoPesquisa, #blocoPesquisaMob').html(data);
		// 	}
		// });				
	}

	// ----------------------------------------------------------------------------------------	


	function retornaForm(index) {
		$("#formulario #COD_CATEGORTKT").val($("#ret_COD_CATEGORTKT_" + index).val());
		$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
		if ($("#ret_LOG_DESTAK_" + index).val() == 'S') {
			$('#formulario #LOG_DESTAK').prop('checked', true);
		} else {
			$('#formulario #LOG_LOG_DESTAK').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}



	function uploadImg(obj) {
		var idField = 'arqUpload_' + $(obj).attr('idinput');
		var typeFile = $(obj).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data" class="upl_image">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	};

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'pesquisa');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('typeFile', typeFile);

		$('.progress').show();
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				$('#btnUploadFile').addClass('disabled');
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						if (percentComplete !== 100) {
							$('.progress-bar').css('width', percentComplete + "%");
							$('.progress-bar > span').html(percentComplete + "%");
						}
					}
				}, false);
				return xhr;
			},
			url: '../uploads/uploaddoc.php',
			type: 'POST',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function(data) {
				$(".upl_image").parent().parent().parent().parent().parent().parent().parent().parent().fadeOut(300, function() {
					$(this).remove();
				});
				if (!data.trim()) {
					$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
					$.alert({
						title: "Mensagem",
						content: "Upload feito com sucesso",
						type: 'green'
					});

				} else {
					$.alert({
						title: "Erro ao efetuar o upload",
						content: data,
						type: 'red'
					});
				}
			}
		});
	}
</script>