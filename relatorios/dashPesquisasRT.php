<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$hojeSql = "";
$dias30 = "";
$andUnidades = "";
$dat_ini = "";
$msgRetorno = "";
$msgTipo = "";
$dat_fim = "";
$log_labels = "";
$hHabilitado = "";
$hashForm = "";
$cod_pesquisa = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$lojasSelecionadas = "";
$andUnidadesDp = "";
$qrBuscaPesquisa = "";
$des_pesquisa = "";
$ini_pesq = "";
$fim_pesq = "";
$ticketgerado1 = "";
$hor_ini = "";
$hor_fim = "";
$sqlGraph = "";
$arrayGraph = [];
$arrayDatas = [];
$arrayDetratores = [];
$arrayNeutros = [];
$arrayPromotores = [];
$arrayQtd = [];
$qrGraph = "";
$qtdVotos = 0;
$checkLabels = "";
$qrBusca = "";
$TOTAL_VISITAS = "";
$TOTAL_FINALIZADAS = "";
$TOTAL_COMPUTADOR = "";
$TOTAL_MOBILE = "";
$TOTAL_TABLET = "";
$TOTAL_TOTEM = "";
$MED_RESPOSTA = "";
$med_ponderada = "";
$total_clientes = 0;
$total = 0;
$i = 0;
$pcRand = "";
$TOTAL_PROMOTORES = "";
$TOTAL_NEUTROS = "";
$TOTAL_DETRATORES = "";
$total_avalia = 0;
$corMed = "";
$texto = "";
$icone = "";
$pct_detratoresGeral = "";
$pct_neutrosGeral = "";
$pct_promotoresGeral = "";
$nps = "";
$corNps = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$qrBuscaModulos = "";
$arrayQuery2 = [];
$NOM_ARRAY_UNIDADE = [];
$unidade = "";
$cor = "";
$pct_detratores = "";
$pct_neutros = "";
$pct_promotores = "";
$contador = "";
$qrBusca2 = "";
$qtd_masculino = 0;
$qtd_feminino = 0;
$content = "";
$cod_univend = '';

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hojeSql = date("Y-m-d");
//$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
$andUnidades = "";

if (isset($_GET['dtI'])) {
	$dat_ini = fnDecode(@$_GET['dtI']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		if (empty(@$_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = @$_REQUEST['LOG_LABELS'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_pesquisa = fnDecode(@$_GET['idP']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//faz pesquisa por revenda (geral)
if (@$cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

if ($cod_univend != "9999") {
	$andUnidades = "AND DPI.COD_UNIVEND IN($lojasSelecionadas)";
	$andUnidadesDp = "AND DP.COD_UNIVEND IN($lojasSelecionadas)";
}

//busca dados da pesquisa
$sql = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa and COD_PESQUISA = $cod_pesquisa order by DES_PESQUISA";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

if ($arrayQuery) {
	$qrBuscaPesquisa = mysqli_fetch_assoc($arrayQuery);
	$des_pesquisa = $qrBuscaPesquisa['DES_PESQUISA'];
	$ini_pesq = $qrBuscaPesquisa['DAT_INI'];
	$fim_pesq = $qrBuscaPesquisa['DAT_FIM'];
}

//fnEscreve($ticketgerado1);
//fnEscreve($lojasSelecionadas);

$hor_ini = " 00:00";
$hor_fim = " 23:59";

$sqlGraph = "SELECT DP.DT_HORAINICIAL,
					COUNT(1) AS TOTAL_VOTOS,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 1 then DPI.COD_NPSTIPO END),0) AS DETRATORES,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 2 then DPI.COD_NPSTIPO END),0) AS NEUTROS,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 3 then DPI.COD_NPSTIPO END),0) AS PROMOTORES,
					((ifnull(COUNT( case when DPI.COD_NPSTIPO = 1 then DPI.COD_NPSTIPO END),0)/COUNT(1))*100) AS PERC_DETRATORES,
					((ifnull(COUNT( case when DPI.COD_NPSTIPO = 2 then DPI.COD_NPSTIPO END),0)/COUNT(1))*100) AS PERC_NEUTROS,
					((ifnull(COUNT( case when DPI.COD_NPSTIPO = 3 then DPI.COD_NPSTIPO END),0)/COUNT(1))*100) AS PERC_PROMOTORES
					FROM DADOS_PESQUISA_ITENS DPI
					INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
					AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					WHERE DPI.COD_PERGUNTA IN 
					( SELECT COD_REGISTR FROM MODELOPESQUISA 
						WHERE COD_TEMPLATE = $cod_pesquisa 
						AND COD_BLPESQU = 5 
						AND LOG_PRINCIPAL = 'S'
						AND COD_EXCLUSA IS NULL ) 
					AND DP.COD_REGISTRO = DPI.COD_REGISTRO
					AND DPI.COD_EMPRESA = $cod_empresa
					$andUnidades
					GROUP BY DATE_FORMAT(DT_HORAINICIAL,'%Y-%M-%D')";

$arrayGraph = mysqli_query(connTemp($cod_empresa, ''), $sqlGraph);

// fnEscreve($sqlGraph);

$arrayDatas = [];
$arrayDetratores = [];
$arrayNeutros = [];
$arrayPromotores = [];
$arrayQtd = [];

if ($arrayGraph) {
	while ($qrGraph = mysqli_fetch_assoc($arrayGraph)) {
		array_push($arrayDatas, fnDataShort($qrGraph['DT_HORAINICIAL']));
		array_push($arrayDetratores, fnLimpaCampoZero($qrGraph['DETRATORES']));
		array_push($arrayNeutros, fnLimpaCampoZero($qrGraph['NEUTROS']));
		array_push($arrayPromotores, fnLimpaCampoZero($qrGraph['PROMOTORES']));
		array_push($arrayQtd, $qrGraph['TOTAL_VOTOS']);
	}
}

if (!empty($arrayQtd)) {
	$qtdVotos = max($arrayQtd);
} else {
	$qtdVotos = 0; // Ou outro valor padrão apropriado
}


// fnEscreve($cod_univend);


?>

<style>
	.slim {
		height: 20px;
	}

	.progress {
		border-radius: 3px;
		height: 21px;
		white-space: nowrap;
		word-spacing: nowrap;
	}

	.skill-name {
		text-transform: uppercase;
		margin-left: 10px;
		padding-left: 10px;
		padding-top: 2.5px;
		float: left;
		font-family: 'Raleway', sans-serif;
		font-size: 1.1em;
	}

	.progress .progress-bar,
	.progress .progress-bar.progress-bar-default {
		background-color: #3498DB;
	}

	.progress .progress-bar {
		animation-name: animateBar;
		animation-iteration-count: 1;
		animation-timing-function: ease-in;
		animation-duration: 1.0s;
	}

	@keyframes animateBar {
		0% {
			transform: translateX(-100%);
		}

		100% {
			transform: translateX(0);
		}
	}

	/*media Queries*/

	@media (min-width: 768px) and (max-width: 991px) {
		#about-section h1 {
			font-size: 2.0em;
		}

		.nav-pills li a {
			font-size: 1.3em !important;
		}


	}


	@media (max-width: 767px) {
		#about-section h1 {
			margin-top: 90px !important;
			font-size: 1.5em;
		}

		.nav-pills li a {
			font-size: 1.3em !important;
		}

		.about-me-text {
			font-size: 1.0em;
		}

		.btn-tab .btn-overide {
			font-size: 0.8em;
			width: 200px;
		}

		#about-section {
			height: 750px;
		}

		#about-section h1 {
			margin-top: 50px;

		}
	}

	@media (max-width: 456px) {
		#about-section {
			height: 730px;
		}

		.nav-pills li a {
			font-size: 0.9em !important;
		}
	}

	@media(max-width: 648px) {
		#about-section {
			height: 800px;
		}
	}

	@media (min-width: 481px) and (max-width: 553px) {
		#about-section {
			height: 900px;
		}
	}

	@media (max-width: 479px) {
		.btn-hire {
			margin-top: 20px !important;

		}

		.btn-contact {
			margin-top: 10px !important;
		}

		#about-section {
			height: 950px;
		}
	}

	@media (max-width: 442px) {
		#about-section {
			height: 980px;
		}
	}

	@media (max-width: 411px) {
		#about-section {
			height: 1020px;
		}
	}

	@media (max-width: 373px) {
		#about-section {
			height: 1050px;
		}
	}

	/*.progress {
  height: 25px;
  margin-bottom: 10px;
}
.progress .skill {
  line-height: 25px;
  padding: 0;
  margin: 0 0 0 20px;
  text-shadow: 0 1px 1px rgba(0,0,0,.9);	
  text-transform: uppercase;
  font-size: 13px;
}
.progress .skill .val {
  float: right;
  font-style: normal;
  text-shadow: 0 1px 1px rgba(0,0,0,.9);
  margin: 0 20px 0 0;
}

.progress-bar {
  text-align: left;
  transition-duration: 3s;
}

.progress > .progress-completed {
	position: absolute;
	right: 0px;
	font-weight: 800;
	text-shadow: 0 1px 1px rgba(0,0,0,.9);
	padding: 3px 10px 2px;
}*/



	.panel-default>.panel-heading {
		color: #333;
		background-color: #fff;
		border-color: #e4e5e7;
		padding: 0;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	.panel-default>.panel-heading a {
		display: block;
		padding: 10px 15px;
	}

	.panel-default>.panel-heading a:after {
		content: "";
		position: relative;
		top: 1px;
		display: inline-block;
		font-family: 'Glyphicons Halflings';
		font-style: normal;
		font-weight: 400;
		line-height: 1;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		float: right;
		transition: transform .25s linear;
		-webkit-transition: -webkit-transform .25s linear;
	}

	.panel-default>.panel-heading a[aria-expanded="true"] {
		background-color: #eee;
	}

	.panel-default>.panel-heading a[aria-expanded="true"]:after {
		content: "\2212";
		-webkit-transform: rotate(180deg);
		transform: rotate(180deg);
	}

	.panel-default>.panel-heading a[aria-expanded="false"]:after {
		content: "\002b";
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
	}

	.accordion-option {
		width: 100%;
		float: left;
		clear: both;
		margin: 15px 0;
	}

	.accordion-option .title {
		font-size: 20px;
		font-weight: bold;
		float: left;
		padding: 0;
		margin: 0;
	}

	.accordion-option .toggle-accordion {
		float: right;
		font-size: 16px;
		color: #6a6c6f;
	}

	.accordion-option .toggle-accordion:before {
		content: "Abrir Todas";
		font-size: 13px;
	}

	.accordion-option .toggle-accordion.active:before {
		content: "Fechar Todas";
		font-size: 13px;
	}
</style>


<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				include "backReport.php";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Pesquisa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_PESQUISA" id="DES_PESQUISA" value="<?php echo $des_pesquisa ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Validade</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DATA_PESQUISA" id="DATA_PESQUISA" value="<?php echo  fnFormatDate($dat_ini) ?> a <?php echo  fnFormatDate($dat_fim) ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Exibir legendas</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?>>
											<span></span>
										</label>
									</div>
								</div>

								<input type="hidden" name="COD_PESQUISA" id="COD_PESQUISA" value="<?= $cod_pesquisa ?>">

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

				</div>

				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
				<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

				<div class="push5"></div>

				</form>


			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>


<div class="row">

	<!-- primeira coluna dash-->
	<div class="col-md-6">


		<div class="row">

			<div class="col-md-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<div class="login-form">

							<?php
							// $sql = "SELECT 
							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa 
							// 			-- $andUnidades
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00') AS TOTAL_VISITAS,

							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa 
							// 			-- $andUnidades
							// 			AND DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DT_HORAFINAL <= '$dat_fim 23:59:59') AS TOTAL_FINALIZADAS,

							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa
							// 			-- $andUnidades 
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DP.COD_NPSPLATAFO IN (0,1)) AS TOTAL_COMPUTADOR,

							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa
							// 			-- $andUnidades 
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DP.COD_NPSPLATAFO = 2) AS TOTAL_MOBILE,

							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa 
							// 			-- $andUnidades
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DP.COD_NPSPLATAFO = 3) AS TOTAL_TABLET,

							// 		(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa 
							// 			-- $andUnidades
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DP.COD_NPSPLATAFO = 4) AS TOTAL_TOTEM,

							// 		(SELECT AVG(DIFERENCA) FROM DADOS_PESQUISA DP 
							// 			-- INNER JOIN DADOS_PESQUISA_ITENS DPI ON DP.COD_REGISTRO = DPI.COD_REGISTRO
							// 			WHERE DP.COD_PESQUISA = $cod_pesquisa 
							// 			-- $andUnidades
							// 			AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
							// 			AND DP.DT_HORAFINAL <= '$dat_fim 23:59:59') AS MED_RESPOSTA
							// 		";

							$sql = "SELECT 
																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa 
																	$andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00'
																	AND DP.DT_HORAINICIAL <= '$dat_fim 23:59:59') AS TOTAL_VISITAS,

																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa 
																	$andUnidadesDp
																	AND DT_HORAINICIAL >= '$dat_ini 00:00:00' 
																	AND DT_HORAFINAL <= '$dat_fim 23:59:59') AS TOTAL_FINALIZADAS,

																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa
																	 $andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00'
																	AND DP.DT_HORAINICIAL <= '$dat_fim 23:59:59' 
																	AND DP.COD_NPSPLATAFO IN (0,1)) AS TOTAL_COMPUTADOR,

																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa
																	 $andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00'
																	AND DP.DT_HORAINICIAL <= '$dat_fim 23:59:59' 
																	AND DP.COD_NPSPLATAFO = 2) AS TOTAL_MOBILE,

																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa 
																	$andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00'
																	AND DP.DT_HORAINICIAL <= '$dat_fim 23:59:59' 
																	AND DP.COD_NPSPLATAFO = 3) AS TOTAL_TABLET,

																(SELECT COUNT(*) FROM DADOS_PESQUISA DP 
																	 
																	WHERE DP.COD_PESQUISA = $cod_pesquisa 
																	$andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00'
																	AND DP.DT_HORAINICIAL <= '$dat_fim 23:59:59' 
																	AND DP.COD_NPSPLATAFO = 4) AS TOTAL_TOTEM,

																(SELECT AVG(DIFERENCA) FROM DADOS_PESQUISA DP 
																	
																	WHERE DP.COD_PESQUISA = $cod_pesquisa 
																	$andUnidadesDp
																	AND DP.DT_HORAINICIAL >= '$dat_ini 00:00:00' 
																	AND DP.DT_HORAFINAL <= '$dat_fim 23:59:59') AS MED_RESPOSTA
																";

							// fnEscreve($sql);
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
							if ($arrayQuery) {
								$qrBusca = mysqli_fetch_assoc($arrayQuery);
								$TOTAL_VISITAS = $qrBusca['TOTAL_VISITAS'];
								$TOTAL_FINALIZADAS = $qrBusca['TOTAL_FINALIZADAS'];
								$TOTAL_COMPUTADOR = $qrBusca['TOTAL_COMPUTADOR'];
								$TOTAL_MOBILE = $qrBusca['TOTAL_MOBILE'];
								$TOTAL_TABLET = $qrBusca['TOTAL_TABLET'];
								$TOTAL_TOTEM = $qrBusca['TOTAL_TOTEM'];
								$MED_RESPOSTA = $qrBusca['MED_RESPOSTA'];
							} else {
								$TOTAL_VISITAS = 0;
								$TOTAL_FINALIZADAS = 0;
								$TOTAL_COMPUTADOR = 0;
								$TOTAL_MOBILE = 0;
								$TOTAL_TABLET = 0;
								$TOTAL_TOTEM = 0;
								$MED_RESPOSTA = 0;
							}

							$sql = "SELECT DPI.* FROM DADOS_PESQUISA_ITENS DPI
																LEFT JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO
																AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																WHERE DPI.COD_PERGUNTA IN (
																							SELECT COD_REGISTR FROM MODELOPESQUISA 
																							WHERE COD_TEMPLATE = $cod_pesquisa 
																							AND COD_BLPESQU = 5 
																							AND LOG_PRINCIPAL = 'S'
																							AND COD_EMPRESA = $cod_empresa
																							AND COD_EXCLUSA IS NULL
																						)
																AND DP.COD_REGISTRO = DPI.COD_REGISTRO
																$andUnidades";

							// fnEscreve($sql);
							$med_ponderada = 0;
							$total_clientes = 0;
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

							$total = array();

							$cont = 0;
							if ($arrayQuery) {
								while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
									if (@$qrBusca['resposta_numero'] == 0) {
										$total['0']++;
									} else if (@$qrBusca['resposta_numero'] == 1) {
										$total['1']++;
									} else if (@$qrBusca['resposta_numero'] == 2) {
										$total['2']++;
									} else if (@$qrBusca['resposta_numero'] == 3) {
										$total['3']++;
									} else if (@$qrBusca['resposta_numero'] == 4) {
										$total['4']++;
									} else if (@$qrBusca['resposta_numero'] == 5) {
										$total['5']++;
									} else if (@$qrBusca['resposta_numero'] == 6) {
										$total['6']++;
									} else if (@$qrBusca['resposta_numero'] == 7) {
										$total['7']++;
									} else if (@$qrBusca['resposta_numero'] == 8) {
										$total['8']++;
									} else if (@$qrBusca['resposta_numero'] == 9) {
										$total['9']++;
									} else if (@$qrBusca['resposta_numero'] == 10) {
										@$total['10']++;
									}
									$cont++;
								}
							}

							for ($i = 10; $i >= 0; $i--) {
								$pcRand	= @$total[$i];
								$med_ponderada += $pcRand * $i;
								$total_clientes += $pcRand;

								if ($pcRand == '') {
									$pcRand = 0;
								}
							}

							$med_ponderada = $total_clientes != 0 ? ($med_ponderada / $total_clientes) : 0;

							$sql = "SELECT
																		(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI
																		INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
                           												 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																		 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
																							   WHERE COD_TEMPLATE = $cod_pesquisa 
																							   AND LOG_PRINCIPAL = 'S'
																							   AND COD_EMPRESA = $cod_empresa
																							   AND COD_EXCLUSA IS NULL) 
																		 AND DPI.COD_NPSTIPO = 3
																		 AND DPI.COD_EMPRESA = $cod_empresa
																		 $andUnidades
																			) AS TOTAL_PROMOTORES,

																		(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI
																		INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
                           												 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																		 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
																							   WHERE COD_TEMPLATE = $cod_pesquisa 
																							   AND LOG_PRINCIPAL = 'S'
																							   AND COD_EMPRESA = $cod_empresa
																							   AND COD_EXCLUSA IS NULL) 
																		 AND DPI.COD_NPSTIPO = 2
																		 AND DPI.COD_EMPRESA = $cod_empresa
																		 $andUnidades
																			) AS TOTAL_NEUTROS,

																		 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI
																		 INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
                           												 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																		 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
																							   WHERE COD_TEMPLATE = $cod_pesquisa 
																							   AND LOG_PRINCIPAL = 'S'
																							   AND COD_EMPRESA = $cod_empresa
																							   AND COD_EXCLUSA IS NULL) 
																		 AND DPI.COD_NPSTIPO = 1
																		 AND DPI.COD_EMPRESA = $cod_empresa
																		 $andUnidades
																			) AS TOTAL_DETRATORES
																	";
							// fnEscreve($sql);
							$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
							if ($query) {
								$qrBusca = mysqli_fetch_assoc($query);
								$TOTAL_PROMOTORES = $qrBusca['TOTAL_PROMOTORES'];
								$TOTAL_NEUTROS = $qrBusca['TOTAL_NEUTROS'];
								$TOTAL_DETRATORES = $qrBusca['TOTAL_DETRATORES'];
							} else {
								$TOTAL_PROMOTORES = 0;
								$TOTAL_NEUTROS = 0;
								$TOTAL_DETRATORES = 0;
							}

							$total_avalia = $TOTAL_PROMOTORES + $TOTAL_NEUTROS + $TOTAL_DETRATORES;
							// Media ponderada = (Ax0 + Bx1 + Cx2 + …. + Jx10) / qtde total de clientes com respostas

							// Clientes totais com respostas = A + B + C + .... + J

							// fnEscreve($TOTAL_PROMOTORES);
							// fnEscreve($TOTAL_NEUTROS);
							// fnEscreve($TOTAL_DETRATORES);

							if ($med_ponderada < 6) {
								$corMed = "text-danger";
								$texto = "Detratores";
								$icone = "fal fa-frown";
							} else if ($med_ponderada >= 6 && $med_ponderada < 9) {
								$corMed = "text-warning";
								$texto = "Neutros";
								$icone = "fal fa-meh";
							} else {
								$corMed = "text-success";
								$texto = "Promotores";
								$icone = "fal fa-smile";
							}

							$pct_detratoresGeral = $total_clientes != 0 ? (($TOTAL_DETRATORES) / $total_clientes) * 100 : 0;
							$pct_neutrosGeral = $total_clientes != 0 ? (($TOTAL_NEUTROS) / $total_clientes) * 100 : 0;
							$pct_promotoresGeral = $total_clientes != 0 ? (($TOTAL_PROMOTORES) / $total_clientes) * 100 : 0;

							$nps = $pct_promotoresGeral - $pct_detratoresGeral;

							// fnEscreve($pct_detratoresGeral);
							// fnEscreve($pct_neutrosGeral);
							// fnEscreve($pct_promotoresGeral);
							// fnEscreve($nps);


							if ($nps < 60) {
								$corNps = "text-danger";
							} else if ($nps >= 60 && $nps < 90) {
								$corNps = "text-warning";
							} else {
								$corNps = "text-success";
							}
							?>

							<div class="row text-center">
								<h4>Visão Geral </h4>

								<div class="push50"></div>
							</div>

							<div class="flexrow text-center">

								<div class="col text-center text-info">
									<i class="fal fa-users fa-2x" aria-hidden="true"></i>
									<b><br /><?= $TOTAL_VISITAS ?></b><br />
									<small style="font-weight:normal;">visitas</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-flag-checkered fa-2x" aria-hidden="true"></i>
									<b><br /><?= $TOTAL_FINALIZADAS ?></b><br />
									<small style="font-weight:normal;">finalizados</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-check-square fa-2x" aria-hidden="true"></i>
									<b><br /><?= $total_clientes ?></b><br />
									<small style="font-weight:normal;">avaliação principal</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-clock fa-2x" aria-hidden="true"></i>
									<b> <br /><?= gmdate("H:i:s", fnValor($MED_RESPOSTA, 0)) ?></b><br />
									<small style="font-weight:normal;">resposta</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-phone-laptop fa-2x" aria-hidden="true"></i>
									<b><br /><?= ($TOTAL_COMPUTADOR + $TOTAL_TABLET) ?></b><br />
									<small style="font-weight:normal;">desktop/tablet</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-mobile-android fa-2x" aria-hidden="true"></i>
									<b><br /><?= $TOTAL_MOBILE ?></b><br />
									<small style="font-weight:normal;">mobile</small>
								</div>

								<div class="col text-center text-info">
									<i class="fal fa-tablet-android fa-2x" aria-hidden="true"></i>
									<b><br /><?= $TOTAL_TOTEM ?></b><br />
									<small style="font-weight:normal;">totem</small>
								</div>

							</div>

							<div class="row">

								<div class="push20"></div>
								<div class="push5"></div>

							</div>

						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

		</div>

	</div>


	<!-- segunda coluna dash-->
	<div class="col-md-6">

		<div class="row">

			<div class="col-md-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<div class="login-form">

							<div class="row">

								<div class="form-group text-center col-lg-12">
									<h4>Resultado <b>Geral</b> </h4>
									<div class="push20"></div>

									<div class="row text-center">

										<div class="col-md-2">
											<div class="push15"></div>
											<div class="col-md-12 text-center text-danger">
												<i class="fal fa-frown fa-3x" aria-hidden="true"></i> <b> <br />Detratores</b>
												<div class="push5"></div>
												<b><?php echo $TOTAL_DETRATORES; ?>
													<div class="push"></div>
													<span class="f14">&nbsp;<?= fnValor($pct_detratoresGeral, 2) ?>%</span>
												</b>
											</div>
											<div class="push15"></div>
										</div>

										<div class="col-md-2">
											<div class="push15"></div>
											<div class="col-md-12 text-center text-warning">
												<i class="fal fa-meh fa-3x" aria-hidden="true"></i> <b> <br />Neutros</b>
												<div class="push5"></div>
												<b><?php echo $TOTAL_NEUTROS; ?>
													<div class="push"></div>
													<span class="f14">&nbsp;<?= fnValor($pct_neutrosGeral, 2) ?>%</span>
												</b>
											</div>
											<div class="push15"></div>
										</div>

										<div class="col-md-2">
											<div class="push15"></div>
											<div class="col-md-12 text-center text-success">
												<i class="fal fa-smile fa-3x" aria-hidden="true"></i> <b><br />Promotores</b>
												<div class="push5"></div>
												<b><?php echo $TOTAL_PROMOTORES; ?>
													<div class="push"></div>
													<span class="f14">&nbsp;<?= fnValor($pct_promotoresGeral, 2) ?>%</span>
												</b>
											</div>
											<div class="push15"></div>
										</div>

										<div class="col-md-3">
											<div class="col-md-12" style="background:#ECF0F1; border-radius: 15px;">
												<div class="push15"></div>
												<div class="col-md-12 text-center <?= $corMed ?>">
													<i class="<?= $icone ?> fa-3x" aria-hidden="true"></i> <b><br /><?= $texto ?></b>
													<div class="push5"></div>
													<b><?php echo fnValor($med_ponderada, 2); ?></b>
													<div class="push"></div>
													<b>Média Final</b>
												</div>
												<div class="push15"></div>
											</div>
										</div>

										<div class="col-md-3">
											<div class="col-md-12" style="background:#ECF0F1; border-radius: 15px;">
												<div class="push"></div>
												<div class="col-md-12 text-center <?= $corNps ?>">
													<b><br />Pontuação</b>
													<div class="push"></div>
													<b style="font-size: 42px;"><?php echo fnValor($nps, 0); ?></b>
													<div class="push"></div>
													<b>Média NPS</b>
												</div>
												<div class="push20"></div>
											</div>
										</div>

									</div>

								</div>

							</div>

						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

		</div>


	</div>

</div>


<div class="row">

	<div class="col-md-12  margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<div class="push20"></div>

					<div class="row text-center">

						<div class="form-group text-center col-md-6">
							<h4>Respostas <b>NPS</b> </h4>
							<div class="push20"></div>

							<div style="height: 450px; width:100%;">
								<canvas id="lineChart"></canvas>
							</div>

						</div>

						<div class="form-group text-center col-md-3">
							<h4>Pesquisas <b>Acessadas</b> </h4>
							<div class="push50"></div>

							<canvas id="donut"></canvas>
							<div class="push5"></div>

							Total de <b><?php echo $TOTAL_VISITAS; ?></b> pesquisas visitadas

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-3">
							<h4>Avaliação <b>Principal</b> </h4>
							<div class="push50"></div>

							<canvas id="donut2"></canvas>
							<div class="push5"></div>

							Total de <b><?php echo $total_avalia; ?></b> avaliações gerais

							<div class="push20"></div>

						</div>

					</div>

					<div class="push20"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<?php

$sql = " SELECT DPI.COD_UNIVEND,

										DPI.COD_PERGUNTA,

								(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI2
							     INNER JOIN  DADOS_PESQUISA DP2 ON DP2.COD_REGISTRO = DPI2.COD_REGISTRO 
										AND DP2.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									WHERE DPI2.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EMPRESA = $cod_empresa
														   AND COD_EXCLUSA IS NULL) 
									AND DPI2.COD_NPSTIPO = 3
									AND DPI2.COD_EMPRESA = $cod_empresa
								 	AND DPI2.COD_UNIVEND = DPI.COD_UNIVEND
								 ) AS TOTAL_PROMOTORES,

								(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI2
							     INNER JOIN  DADOS_PESQUISA DP2 ON DP2.COD_REGISTRO = DPI2.COD_REGISTRO 
										AND DP2.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									WHERE DPI2.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EMPRESA = $cod_empresa
														   AND COD_EXCLUSA IS NULL) 
									AND DPI2.COD_NPSTIPO = 2
									AND DPI2.COD_EMPRESA = $cod_empresa
								 	AND DPI2.COD_UNIVEND = DPI.COD_UNIVEND
								 ) AS TOTAL_NEUTROS,

								 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI2
							     INNER JOIN  DADOS_PESQUISA DP2 ON DP2.COD_REGISTRO = DPI2.COD_REGISTRO 
										AND DP2.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									WHERE DPI2.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EMPRESA = $cod_empresa
														   AND COD_EXCLUSA IS NULL) 
									AND DPI2.COD_NPSTIPO = 1
									AND DPI2.COD_EMPRESA = $cod_empresa
								 	AND DPI2.COD_UNIVEND = DPI.COD_UNIVEND
								 ) AS TOTAL_DETRATORES

							FROM DADOS_PESQUISA_ITENS DPI
							INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
							WHERE DP.COD_REGISTRO = DPI.COD_REGISTRO
							AND DPI.COD_EMPRESA = $cod_empresa
							AND DP.COD_PESQUISA = $cod_pesquisa
							AND DPI.COD_UNIVEND IS NOT NULL
							$andUnidades
							GROUP BY DPI.COD_UNIVEND
							";
// fnescreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

if ($arrayQuery) {

?>

	<div class="row">

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row text-center">

							<div class="col-md-12">
								<h4>Perguntas <b>NPS</b> <small>(por unidade)</small> </h4>
								<div class="push20"></div>

								<div class="col-xs-12">

									<div class="no-more-tables">

										<form name="formLista">

											<table class="table table-bordered table-striped table-hover tableSorter">
												<thead>
													<tr>
														<th>Loja</th>
														<th>Qtd. Promotores</th>
														<th>% Promotores</th>
														<th>Qtd. Neutros</th>
														<th>% Neutros</th>
														<th>Qtd. Detratores</th>
														<th>% Detratores</th>
														<th>Media</th>
														<th>NPS</th>
														<th class="{sorter:false}"></th>
													</tr>
												</thead>
												<tbody>

													<?php

													$ARRAY_UNIDADE1 = array(
														'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
														'cod_empresa' => $cod_empresa,
														'conntadm' => $connAdm->connAdm(),
														'IN' => 'N',
														'nomecampo' => '',
														'conntemp' => '',
														'SQLIN' => ""
													);
													$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

													$count = 0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

														if ($qrBuscaModulos['COD_UNIVEND'] != '') {

															$sql = "SELECT * FROM dados_pesquisa_itens DPI
																			  			INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
																						WHERE DPI.COD_PERGUNTA = $qrBuscaModulos[COD_PERGUNTA] 
																						AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																						AND DPI.COD_EMPRESA = $cod_empresa
																						AND DPI.COD_NPSTIPO IN(1,2,3) 
																						AND DPI.COD_UNIVEND = " . $qrBuscaModulos['COD_UNIVEND'];

															// fnEscreve($sql);
															$med_ponderada = 0;
															$total_clientes = 0;
															$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

															$total = array();

															$cont = 0;
															while ($qrBusca = mysqli_fetch_assoc($arrayQuery2)) {
																if (@$qrBusca['resposta_numero'] == 0) {
																	$total['0']++;
																} else if (@$qrBusca['resposta_numero'] == 1) {
																	$total['1']++;
																} else if (@$qrBusca['resposta_numero'] == 2) {
																	$total['2']++;
																} else if (@$qrBusca['resposta_numero'] == 3) {
																	$total['3']++;
																} else if (@$qrBusca['resposta_numero'] == 4) {
																	$total['4']++;
																} else if (@$qrBusca['resposta_numero'] == 5) {
																	$total['5']++;
																} else if (@$qrBusca['resposta_numero'] == 6) {
																	$total['6']++;
																} else if (@$qrBusca['resposta_numero'] == 7) {
																	$total['7']++;
																} else if (@$qrBusca['resposta_numero'] == 8) {
																	$total['8']++;
																} else if (@$qrBusca['resposta_numero'] == 9) {
																	$total['9']++;
																} else if (@$qrBusca['resposta_numero'] == 10) {
																	@$total['10']++;
																}
																$cont++;
															}

															for ($i = 0; $i <= 10; $i++) {
																$pcRand	= @$total[$i];
																$med_ponderada += $pcRand * $i;
																$total_clientes += $pcRand;
															}

															$med_ponderada = $total_clientes != 0 ? ($med_ponderada / $total_clientes) : 0;

															$NOM_ARRAY_UNIDADE = (array_search($qrBuscaModulos['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
															$unidade = "Sem loja";

															if ($qrBuscaModulos['COD_UNIVEND'] != 0) {
																$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
															}

															if ($med_ponderada < 6) {
																$cor = "text-danger";
																$texto = "Detratores";
																$icone = "far fa-frown";
															} else if ($med_ponderada >= 6 && $med_ponderada < 9) {
																$cor = "text-warning";
																$texto = "Neutros";
																$icone = "far fa-meh";
															} else {
																$cor = "text-success";
																$texto = "Promotores";
																$icone = "far fa-smile";
															}

															$pct_detratores = $total_clientes != 0 ? (($qrBuscaModulos['TOTAL_DETRATORES']) / $total_clientes) * 100 : 0;
															$pct_neutros = $total_clientes != 0 ? (($qrBuscaModulos['TOTAL_NEUTROS']) / $total_clientes) * 100 : 0;
															$pct_promotores = $total_clientes != 0 ? (($qrBuscaModulos['TOTAL_PROMOTORES']) / $total_clientes) * 100 : 0;

															$nps = $pct_promotores - $pct_detratores;

															if ($nps == 0 && $total_clientes == 0) {
																$corNps = "";
																$cor = "";
																$icone = "";
																$texto = "Sem dados";
															} else if ($nps < 60) {
																$corNps = "text-danger";
															} else if ($nps >= 60 && $nps < 90) {
																$corNps = "text-warning";
															} else {
																$corNps = "text-success";
															}

															// fnEscreve($total_clientes);

															$count++;
															echo "
																					<tr>
																					  <td>" . $unidade . "</td>
																					  <td class='text-center'>" . $qrBuscaModulos['TOTAL_PROMOTORES'] . "</td>
																					  <td class='text-center'>" . fnValor(($qrBuscaModulos['TOTAL_PROMOTORES'] / $total_clientes) * 100, 2) . "%</td>
																					  <td class='text-center'>" . $qrBuscaModulos['TOTAL_NEUTROS'] . "</td>
																					  <td class='text-center'>" . fnValor(($qrBuscaModulos['TOTAL_NEUTROS'] / $total_clientes) * 100, 2) . "%</td>
																					  <td class='text-center'>" . $qrBuscaModulos['TOTAL_DETRATORES'] . "</td>
																					  <td class='text-center'>" . fnValor(($qrBuscaModulos['TOTAL_DETRATORES'] / $total_clientes) * 100, 2) . "%</td>
																					  <td class='text-center'>" . fnValor($med_ponderada, 2) . "</td>
																					  <td class='text-center $corNps'>" . fnValor($nps, 0) . "</td>
																					  <td class='text-center'>
																					  		<div class='col-xs-12 text-center' style='padding-left: 0; padding-right: 0;'>
																					  			<span class='" . $icone . " " . $cor . "'></span>
																					  			<div class='push'></div>
																					  			<span class='" . $cor . "'>" . $texto . "</span>
																					  		</div>																		  		
																					  </td>
																					</tr>
																					<input type='hidden' id='ret_COD_GRUPOTR_" . $count . "' value='" . @$qrBuscaModulos['COD_GRUPOTR'] . "'>
																					<input type='hidden' id='ret_DES_GRUPOTR_" . $count . "' value='" . @$qrBuscaModulos['DES_GRUPOTR'] . "'>
																					";
														}
													}

													?>

												</tbody>
												<tfoot>
													<td class="text-left">
														<small>
															<div class="btn-group dropdown left">
																<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
																	&nbsp; Exportar &nbsp;
																	<span class="fas fa-caret-down"></span>
																</button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li><a class="btn btn-sm exportarCSV" id="lojas" style="text-align: left">&nbsp; Exportar (lojas) </a></li>
																	<li><a class="btn btn-sm exportarCSV" id="clientes" style="text-align: left">&nbsp; Exportar (clientes)</a></li>
																	<li><a class="btn btn-sm exportarCSV" id="novosLojas" style="text-align: left">&nbsp; Exportar Novos Cad. (lojas) </a></li>
																	<li><a class="btn btn-sm exportarCSV" id="novosClientes" style="text-align: left">&nbsp; Exportar Novos Cad. (clientes) </a></li>
																	<li><a class="btn btn-sm exportarCSV" id="geral" style="text-align: left">&nbsp; Exportar Tudo (clientes)</a></li>
																	<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																</ul>
															</div>
														</small>
													</td>

													<!--<tr>
																			<th colspan="100">
																				<a class="btn btn-info btn-sm exportarCSV" id="lojas"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar (lojas)</a>
																				<a class="btn btn-info btn-sm exportarCSV" id="clientes"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar (clientes)</a>
																				<a class="btn btn-info btn-sm exportarCSV" id="novosLojas"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Novos Cad. (lojas)</a>
																				<a class="btn btn-info btn-sm exportarCSV" id="novosClientes"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Novos Cad. (clientes)</a>
																				<a class="btn btn-info btn-sm exportarCSV" id="geral"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Tudo (clientes)</a>
																			</th>
																		</tr>-->
												</tfoot>
											</table>

									</div>

								</div>

							</div>

						</div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>

<?php } ?>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<div class="push20"></div>

					<div class="row text-center">

						<div class="col-md-12">

							<h4>Perguntas <b>Abertas</b> </h4>
							<div class="push20"></div>

							<div class="accordion-option">
								<div class="col-md-10 col-md-offset-1">
									<a href="javascript:void(0)" class="toggle-accordion" accordion-id="#accordion"></a>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php
								$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND COD_BLPESQU = 2 AND COD_EXCLUSA IS NULL";
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								$contador = 0;
								if ($arrayQuery) {
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
								?>
										<div class="row">
											<div class="col-md-10 col-md-offset-1">
												<div class="panel panel-default">
													<div class="panel-heading" role="tab" id="headingOne">
														<h4 class="panel-title">
															<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $contador; ?>" aria-expanded="true" aria-controls="collapseOne">
																<?php echo $qrBusca['DES_PERGUNTA']; ?>
															</a>
														</h4>
													</div>
													<div id="collapseOne<?php echo $contador; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
														<div class="panel-body">
															<?php
															$sql = "SELECT DPI.*, DP.DT_HORAINICIAL 
																						FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
																						WHERE COD_PERGUNTA = " . $qrBusca['COD_REGISTR'] . "
																						AND DPI.COD_REGISTRO = DP.COD_REGISTRO
																						AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
																						ORDER BY DP.DT_HORAINICIAL DESC 
																						LIMIT 5";
															// FNESCREVE($sql);
															$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

															while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery2)) {
															?>
																<p style="text-align: left;"><?php echo $qrBusca2['resposta_texto']; ?></p>
																<div class="push5"></div>
																<p style="text-align: left; font-size: 14px;"><small><?php echo fnDataFull($qrBusca2['DT_HORAINICIAL']); ?></small></p>
																<hr>
															<?php
															}
															?>
															<div></div>
															<div id="carregadorDados<?php echo $qrBusca['COD_REGISTR'] ?>"></div>
															<?php if (mysqli_num_rows($arrayQuery2) == 5) { ?>
																<button type="button" cod-limit="0" cod-registr="<?php echo $qrBusca['COD_REGISTR'] ?>" class="btn btn-default carregarMais">Carregar mais</button>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="push5"></div>
								<?php
										$contador++;
									}
								}
								?>
							</div>
							<div class="row">
								<div class="col-md-10 col-md-offset-1 text-left">
									<a class="btn btn-info btn-sm exportarCSV" id="perguntas"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar (perguntas)</a>
								</div>

							</div>
						</div>

					</div>

					<div class="push20"></div>


				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<!-- Versão compatível do chart js com as labels -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

<?php
}
?>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	//datas
	$(function() {

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});


	});

	//graficos
	$(document).ready(function() {

		$(".carregarMais").click(function() {
			var pValor = parseInt($(this).attr('cod-limit')) + 5;
			var pCod_registr = $(this).attr('cod-registr');
			var _this = $(this);
			$.ajax({
				type: 'GET',
				url: 'relatorios/ajxDashPesquisasRT.do',
				data: {
					valor: pValor,
					cod_empresa: <?php echo $cod_empresa; ?>,
					cod_registr: pCod_registr,
					datIni: "<?= $dat_ini ?>",
					datFim: "<?= $dat_fim ?>"
				},
				beforeSend: function() {
					$('#carregadorDados' + pCod_registr).html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success: function(response) {
					$(response).insertBefore($('#carregadorDados' + pCod_registr));
					_this.attr('cod-limit', pValor);
				}
			});
		});

		// Line chart 2
		var ctx = document.getElementById("lineChart");
		var lineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: <?php echo json_encode($arrayDatas); ?>,
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'start',
							borderRadius: 6,
							backgroundColor: '#e74c3c',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Detratores",
					backgroundColor: "rgba(231, 76, 60, 0.1)",
					borderColor: "#e74c3c",
					pointBorderColor: "#e74c3c",
					pointBackgroundColor: "#fff",
					pointHoverBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($arrayDetratores) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#f39c12',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Neutros",
					backgroundColor: "rgba(243, 156, 18, 0.1)",
					borderColor: "#f39c12",
					pointBorderColor: "#f39c12",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($arrayNeutros) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#18bc9c',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Promotores",
					backgroundColor: "rgba(24, 188, 56, 0.1)",
					borderColor: "#18bc9c",
					pointBorderColor: "#18bc9c",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($arrayPromotores) ?>
				}]
			},
			// plugins: [ChartDataLabels],
			options: {
				legend: {
					display: true,
					position: 'bottom'
				},
				maintainAspectRatio: false,
				animation: {
					duration: 2000,
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
							}
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick];
							}
						}
					}],
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							if (parseInt(t.yLabel) >= 1000) {
								return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return t.yLabel;
							}
						}
					}
				},
			}

		});

		//collpase respostas
		$(".toggle-accordion").on("click", function() {
			var accordionId = $(this).attr("accordion-id"),
				numPanelOpen = $(accordionId + ' .collapse.in').length;

			$(this).toggleClass("active");

			if (numPanelOpen == 0) {
				openAllPanels(accordionId);
			} else {
				closeAllPanels(accordionId);
			}
		})

		openAllPanels = function(aId) {
			console.log("setAllPanelOpen");
			$(aId + ' .panel-collapse:not(".in")').collapse('show');
		}
		closeAllPanels = function(aId) {
			console.log("setAllPanelclose");
			$(aId + ' .panel-collapse.in').collapse('hide');
		}

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();


		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)

		//donut 
		var config = {
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			type: 'doughnut',
			data: {
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#3BB0B0',
								'#2692DB'
							],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					data: [
						"<?= ($TOTAL_VISITAS - $TOTAL_FINALIZADAS) ?>",
						"<?= $TOTAL_FINALIZADAS ?>",
						// <?= ($qtd_masculino + $qtd_feminino) ?>,
					],
					backgroundColor: [
						window.chartColors.blue,
						window.chartColors.green
						// "#E5E5E5",
					],
					label: 'Dataset 1'
				}],
				labels: [
					"Desistências",
					"Finalizadas"
				]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		};

		var ctx = document.getElementById("donut").getContext("2d");
		var myDoughnut = new Chart(ctx, config);

		//donut 
		var config3 = {
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			type: 'doughnut',
			data: {
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								window.chartColors.green,
								window.chartColors.orange,
								window.chartColors.red
							],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value.toFixed(2).replace('.', ',') + '%';
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					data: [
						<?= number_format($pct_promotoresGeral, 2, '.', '') ?>,
						<?= number_format($pct_neutrosGeral, 2, '.', '') ?>,
						<?= number_format($pct_detratoresGeral, 2, '.', '') ?>
					],
					borderColor: [
						'#fff',
						'#fff',
						'#fff',
					],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.orange,
						window.chartColors.red
					],
					borderWidth: [1, 1, 1],
				}],
				labels: [
					" Promotores",
					" Neutros",
					" Detratores"
				]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']] + '%';
						},
						// afterLabel: function(tooltipItem, data) {
						//   var dataset = data['datasets'][0];
						//   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
						//   return '(' + percent + '%)';
						// }
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				// title: {
				// 	display: true,
				// 	text: 'Percentual de Avaliações'
				// },
				animation: {
					animateScale: true,
					animateRotate: true
				},
				legend: {
					position: 'bottom',
				}
			}
		};

		window.onload = function() {
			var ctx3 = document.getElementById("donut2").getContext("2d");
			window.myDoughnut = new Chart(ctx3, config3);
		};

		$(".exportarCSV").click(function() {
			var tipo = $(this).attr("id");
			$.confirm({
				title: 'Exportação',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Gerar',
						btnClass: 'btn-blue',
						action: function() {
							var nome = this.$content.find('.nome').val();
							if (!nome) {
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxDashPesquisasRT.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&tipo=" + tipo,
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function() {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										//close
									}
								}
							});
						}
					},
					cancelar: function() {
						//close
					},
				}
			});
		});

	});
</script>