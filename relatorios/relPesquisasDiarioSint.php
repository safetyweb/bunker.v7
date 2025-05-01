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
$dat_ini = "";
$msgRetorno = "";
$msgTipo = "";
$dat_fim = "";
$log_labels = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_pesquisa = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$qrBuscaPesquisa = "";
$des_pesquisa = "";
$ini_pesq = "";
$fim_pesq = "";
$andUnidades = "";
$lojasSelecionadas = "";
$ticketgerado1 = "";
$hor_ini = "";
$hor_fim = "";
$sqlGraph = "";
$arrayGraph = [];
$arrayDatas = [];
$arrayQtd = [];
$arrayNps = [];
$arrayNeutros = [];
$arrayPromotores = [];
$countPerc = "";
$promoPeriodo = "";
$detraPeriodo = "";
$votosPeriodo = "";
$npsCumula = "";
$qrGraph = "";
$percPromo = "";
$percDetra = "";
$qtdVotos = 0;
$med_ponderada = "";
$total_clientes = "";
$qrBusca = "";
$pcRand = "";
$arrayCount = [];
$TOTAL_PROMOTORES = "";
$TOTAL_NEUTROS = "";
$TOTAL_DETRATORES = "";
$total_avalia = "";
$corMed = "";
$texto = "";
$icone = "";
$pct_detratoresGeral = "";
$pct_neutrosGeral = "";
$pct_promotoresGeral = "";
$nps = "";
$corNps = "";
$arrayCountAvalia = [];
$qrBuscaAvalia = "";
$TOTAL_PROMOTORESav = "";
$TOTAL_NEUTROSav = "";
$TOTAL_DETRATORESav = "";
$pct_detratoresAvalia = "";
$pct_neutrosAvalia = "";
$pct_promotoresAvalia = "";
$row = "";
$content = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hojeSql = date("Y-m-d");
//$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));

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

		if ($opcao != '' && $opcao != 0) {
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

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca dados da pesquisa
$sql = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa and COD_PESQUISA = $cod_pesquisa order by DES_PESQUISA";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaPesquisa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$des_pesquisa = $qrBuscaPesquisa['DES_PESQUISA'];
	$ini_pesq = $qrBuscaPesquisa['DAT_INI'];
	$fim_pesq = $qrBuscaPesquisa['DAT_FIM'];
}

$andUnidades = "";

if ($cod_univend != "9999") {
	$andUnidades = "AND DPI.COD_UNIVEND IN($lojasSelecionadas)";
}

//fnEscreve($ticketgerado1);
//fnEscreve($lojasSelecionadas);

$hor_ini = " 00:00";
$hor_fim = " 23:59";

$sqlGraph = "SELECT DP.DT_HORAINICIAL,
					COUNT(1) AS TOTAL_VOTOS,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 1 then DPI.COD_NPSTIPO END),0) AS DETRATORES,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 2 then DPI.COD_NPSTIPO END),0) AS NEUTROS,
					ifnull(COUNT( case when DPI.COD_NPSTIPO = 3 then DPI.COD_NPSTIPO END),0) AS PROMOTORES
					FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP 
					WHERE DPI.COD_PERGUNTA IN 
					( SELECT COD_REGISTR FROM MODELOPESQUISA 
						WHERE COD_TEMPLATE = $cod_pesquisa 
						AND COD_BLPESQU = 5 
						AND COD_EXCLUSA IS NULL ) 
					AND DP.COD_REGISTRO = DPI.COD_REGISTRO 
					AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andUnidades
					GROUP BY DATE_FORMAT(DT_HORAINICIAL,'%Y-%M-%D')";

// fnEscreve($sqlGraph);

$arrayGraph = mysqli_query(connTemp($cod_empresa, ''), $sqlGraph);

$arrayDatas = [];
$arrayQtd = [];
$arrayNps = [];
// $arrayNeutros = [];
// $arrayPromotores = [];
$countPerc = 1;

$promoPeriodo = 0;
$detraPeriodo = 0;
$votosPeriodo = 0;
$npsCumula = 0;

while ($qrGraph = mysqli_fetch_assoc($arrayGraph)) {

	$promoPeriodo += $qrGraph['PROMOTORES'];
	$detraPeriodo += $qrGraph['DETRATORES'];
	$votosPeriodo += $qrGraph['TOTAL_VOTOS'];


	$percPromo = $votosPeriodo != 0 ? (($promoPeriodo) / $votosPeriodo) * 100 : 0;
	$percDetra = $votosPeriodo != 0 ? (($detraPeriodo) / $votosPeriodo) * 100 : 0;

	$npsCumula = $percPromo - $percDetra;

	// fnEscreve("promotores = ".$percPromo);
	// fnEscreve("detratores = ".$percDetra);
	// fnEscreve("votos = ".$votosPeriodo);

	array_push($arrayDatas, fnDataShort($qrGraph['DT_HORAINICIAL']));
	array_push($arrayQtd, $qrGraph['TOTAL_VOTOS']);
	array_push($arrayNps, fnValor($npsCumula, 0));
}

$qtdVotos = max($arrayQtd);

$contador = 1;


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

	.slim {
		height: 23px;
	}

	.progress {
		border-radius: 3px;
		height: 13px;
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

	.progress-bar {
		text-shadow: -0.5px 0 1.4px #000 !important;
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

	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}

	.progress-meter {
		min-height: 15px;
		border-bottom: 1px solid rgb(215, 219, 221);
	}

	.progress-meter>.meter {
		position: relative;
		float: left;
		min-height: 15px;
		border-width: 0px;
		border-style: solid;
		border-color: rgb(160, 160, 160);
	}

	.progress-meter>.meter-left {
		border-left-width: 2px;
	}

	.progress-meter>.meter-right {
		float: right;
		border-right-width: 2px;
	}

	.progress-meter>.meter-right:last-child {
		border-left-width: 2px;
	}

	.progress-meter>.meter>.meter-text {
		position: absolute;
		display: inline-block;
		bottom: -20px;
		width: 100%;
		font-weight: 700;
		font-size: 0.85em;
		color: rgb(160, 160, 160);
		text-align: left;
	}

	.progress-meter>.meter.meter-right>.meter-text {
		text-align: right;
	}

	.progress-meter>.meter {
		position: relative;
		float: left;
		min-height: 15px;
		border-width: 0px;
		border-style: solid;
		border-color: rgb(229, 231, 233);
	}

	.progress-meter>.meter-left {
		color: 0ff;
		border-left-width: 1px;
	}

	.progress-meter>.meter-right {
		float: right;
		border-right-width: 1px;
	}

	.progress-meter>.meter-right:last-child {
		border-left-width: 1px;
	}

	.progress-meter>.meter>.meter-text {
		position: absolute;
		display: inline-block;
		bottom: -20px;
		width: 100%;
		font-weight: 300;
		font-size: 0.85em;
		color: rgb(215, 219, 221);
		text-align: left;

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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Pesquisa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_PESQUISA" id="DES_PESQUISA" value="<?php echo $des_pesquisa ?>">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Validade</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DATA_PESQUISA" id="DATA_PESQUISA" value="<?php echo  fnFormatDate($dat_ini) ?> a <?php echo  fnFormatDate($dat_fim) ?>">
									</div>
								</div>

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
								<div class="col-md-3">
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

								<div class="col-md-3">
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

								<div class="col-md-3">
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn">
										<i class="fa fa-filter" aria-hidden="true"></i>
										&nbsp; Filtrar
									</button>
								</div>

							</div>

						</fieldset>

				</div>


				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
				<input type="hidden" name="COD_PESQUISA" id="COD_PESQUISA" value="<?= $cod_pesquisa ?>">
				<input type="hidden" name="LOJAS" id="LOJAS" value='<?= $lojasSelecionadas ?>'>
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

				<div class="push5"></div>

				</form>


				<input type="hidden" name="lineChart" id="inptLineChart" value="">
				<input type="hidden" name="myGauge" id="inptMyGauge" value="">

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>

<?php

$sql = "SELECT DPI.* FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
								WHERE DPI.COD_PERGUNTA IN (
															SELECT COD_REGISTR FROM MODELOPESQUISA 
															WHERE COD_TEMPLATE = $cod_pesquisa 
															AND COD_BLPESQU = 5 
															AND COD_EXCLUSA IS NULL
														)
								AND DP.COD_REGISTRO = DPI.COD_REGISTRO
								AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ";

// fnEscreve($sql);
$med_ponderada = 0;
$total_clientes = 0;
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$total = array();

$cont = 0;
while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
	if (@$qrBusca['resposta_numero'] == 0) {
		@$total['0']++;
	} else if (@$qrBusca['resposta_numero'] == 1) {
		@$total['1']++;
	} else if (@$qrBusca['resposta_numero'] == 2) {
		@$total['2']++;
	} else if (@$qrBusca['resposta_numero'] == 3) {
		@$total['3']++;
	} else if (@$qrBusca['resposta_numero'] == 4) {
		@$total['4']++;
	} else if (@$qrBusca['resposta_numero'] == 5) {
		@$total['5']++;
	} else if (@$qrBusca['resposta_numero'] == 6) {
		@$total['6']++;
	} else if (@$qrBusca['resposta_numero'] == 7) {
		@$total['7']++;
	} else if (@$qrBusca['resposta_numero'] == 8) {
		@$total['8']++;
	} else if (@$qrBusca['resposta_numero'] == 9) {
		@$total['9']++;
	} else if (@$qrBusca['resposta_numero'] == 10) {
		@$total['10']++;
	}
	$cont++;
}

for ($i = 10; $i >= 0; $i--) {
	$pcRand	= @$total[$i];
	$med_ponderada += $pcRand * $i;
	$total_clientes += $pcRand;

	if ($pcRand == '') {
		$pcRand = 0;
	}
}

$med_ponderada = $med_ponderada / $total_clientes;

$sql = "SELECT DES_PERGUNTA,
									(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
									 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EXCLUSA IS NULL) 
									 AND DPI.COD_NPSTIPO = 3
									 $andUnidades
									 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
									 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										) AS TOTAL_PROMOTORES,

									(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
									 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EXCLUSA IS NULL) 
									 AND DPI.COD_NPSTIPO = 2
									 $andUnidades
									 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
									 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										) AS TOTAL_NEUTROS,

									 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
									 WHERE DPI.COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
														   WHERE COD_TEMPLATE = $cod_pesquisa 
														   AND LOG_PRINCIPAL = 'S'
														   AND COD_EXCLUSA IS NULL) 
									 AND DPI.COD_NPSTIPO = 1
									 $andUnidades
									 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
									 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										) AS TOTAL_DETRATORES
										FROM MODELOPESQUISA 
									   WHERE COD_TEMPLATE = $cod_pesquisa 
									   AND LOG_PRINCIPAL = 'S'
									   AND COD_EXCLUSA IS NULL
									";
// fnEscreve($sql);
$arrayCount = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBusca = mysqli_fetch_assoc($arrayCount);
$TOTAL_PROMOTORES = $qrBusca['TOTAL_PROMOTORES'];
$TOTAL_NEUTROS = $qrBusca['TOTAL_NEUTROS'];
$TOTAL_DETRATORES = $qrBusca['TOTAL_DETRATORES'];

$total_avalia = $TOTAL_PROMOTORES + $TOTAL_NEUTROS + $TOTAL_DETRATORES;
// Media ponderada = (Ax0 + Bx1 + Cx2 + …. + Jx10) / qtde total de clientes com respostas

// Clientes totais com respostas = A + B + C + .... + J

if ($med_ponderada < 6) {
	$corMed = "text-danger";
	$texto = "Detratores";
	$icone = "fa-frown";
} else if ($med_ponderada >= 6 && $med_ponderada < 9) {
	$corMed = "text-warning";
	$texto = "Neutros";
	$icone = "fa-meh";
} else {
	$corMed = "text-success";
	$texto = "Promotores";
	$icone = "fa-smile";
}

$pct_detratoresGeral = $total_clientes != 0 ? (($TOTAL_DETRATORES) / $total_clientes) * 100 : 0;
$pct_neutrosGeral = $total_clientes != 0 ? (($TOTAL_NEUTROS) / $total_clientes) * 100 : 0;
$pct_promotoresGeral = $total_clientes != 0 ? (($TOTAL_PROMOTORES) / $total_clientes) * 100 : 0;

$nps = $pct_promotoresGeral - $pct_detratoresGeral;

if ($nps < 60) {
	$corNps = "text-danger";
} else if ($nps >= 60 && $nps < 90) {
	$corNps = "text-warning";
} else {
	$corNps = "text-success";
}

if (mysqli_num_rows($arrayCount) == 1) {
?>


	<div class="row">

		<!-- primeira coluna dash-->
		<div class="col-md-12">

			<div class="row">

				<div class="col-md-12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">

						<div class="portlet-body">

							<div class="login-form">

								<div class="row">

									<div class="col-md-4 col-md-offset-4">

										<div id="canvas-holder" style="width:100%">
											<canvas id="chart"></canvas>
										</div>

									</div>

								</div>

								<div class="row">

									<div class="col-md-4 col-md-offset-4 text-center">

										<div class="col-xs-4">
											<?= fnValor(round($pct_detratoresGeral), 0) ?>%
											<br />
											<span class="fas fa-frown fa-2x text-danger"></span>
											<br />
											Detratores
										</div>

										<div class="col-xs-4">
											<?= fnValor(round($pct_neutrosGeral), 0) ?>%
											<br />
											<span class="fas fa-meh fa-2x text-warning"></span>
											<br />
											Neutros
										</div>

										<div class="col-xs-4">
											<?= fnValor(round($pct_promotoresGeral), 0) ?>%
											<br />
											<span class="fas fa-smile fa-2x text-success"></span>
											<br />
											Promotores
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

	<div class="row">

		<!-- primeira coluna dash-->
		<div class="col-md-12">

			<div class="row">

				<div class="col-md-12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">

						<div class="portlet-body">

							<div class="login-form">

								<div class="row">

									<div class="col-md-10 col-md-offset-1">

										<div style="width:100%;">
											<canvas id="lineChart"></canvas>
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

	<div class="row">

		<!-- primeira coluna dash-->
		<div class="col-md-12">

			<div class="row">

				<div class="col-md-12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">

						<div class="portlet-body">

							<div class="login-form">

								<div class="row">

									<div class="form-group col-md-9">

										<div class="col-md-12">
											<h4><?= $contador ?>. <?= $qrBusca['DES_PERGUNTA'] ?></h4>
										</div>

										<div class="push20"></div>

										<div role="tabpanel" class=" tab-pane" id="skill">
											<div class="skill-section">

												<div class="row">

													<div class="col-xs-3 slim">
														<div class="col-xs-11 col-xs-offset-1">
															<div class="col-xs-3 text-right">
																<span class="fas fa-smile text-success"></span>
															</div>
															<div class="col-xs-9">
																Promotores
															</div>
														</div>
													</div>

													<div class="col-xs-7 slim">
														<div class="progress">
															<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_promotoresGeral, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #15BC9C;">
																<span class="skill-name"><strong></strong></span>
															</div>
														</div>
													</div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_PROMOTORES, 0)) ?></div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_promotoresGeral, 2)) ?>%</div>

												</div>

												<div class="row">

													<div class="col-xs-3 slim">
														<div class="col-xs-11 col-xs-offset-1">
															<div class="col-xs-3 text-right">
																<span class="fas fa-meh text-warning"></span>
															</div>
															<div class="col-xs-9">
																Neutros
															</div>
														</div>
													</div>

													<div class="col-xs-7 slim">
														<div class="progress">
															<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_neutrosGeral, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #F39C12;">
																<span class="skill-name"><strong></strong></span>
															</div>
														</div>
													</div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_NEUTROS, 0)) ?></div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_neutrosGeral, 2)) ?>%</div>

												</div>

												<div class="row">

													<div class="col-xs-3 slim">
														<div class="col-xs-11 col-xs-offset-1">
															<div class="col-xs-3 text-right">
																<span class="fas fa-frown text-danger"></span>
															</div>
															<div class="col-xs-9">
																Detratores
															</div>
														</div>
													</div>

													<div class="col-xs-7 slim">
														<div class="progress">
															<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_detratoresGeral, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #E74C3C;">
																<span class="skill-name"><strong></strong></span>
															</div>
														</div>
													</div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_DETRATORES, 0)) ?></div>

													<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_detratoresGeral, 2)) ?>%</div>

												</div>

											</div>

											<div class="row">

												<div class="col-xs-7 col-xs-offset-3">
													<div class="progress-meter">
														<div class="meter meter-left" style="width: 25%;"><span class="meter-text">0</span></div>
														<div class="meter meter-left" style="width: 25%;"><span class="meter-text">25</span></div>
														<div class="meter meter-right" style="width: 20%;"><span class="meter-text">100</span></div>
														<div class="meter meter-right" style="width: 30%;"><span class="meter-text">75</span></div>
													</div>
												</div>

											</div>
										</div>

									</div>

									<div class="col-md-3">

										<div class="col-md-6">

											<div class="push30"></div>

											<div class="col-md-12" style="background:#ECF0F1; border-radius: 15px;">
												<div class="push15"></div>
												<div class="col-md-12 text-center <?= $corMed ?>">
													<i class="fal <?= $icone ?> fa-3x" aria-hidden="true"></i>
													<div class="push5"></div>
													<b><?php echo fnValor($med_ponderada, 2); ?></b>
													<div class="push"></div>
													<b>Média Final</b>
												</div>
												<div class="push15"></div>
											</div>

										</div>

										<div class="col-md-6">

											<div class="push30"></div>

											<div class="col-md-12" style="background:#ECF0F1; border-radius: 15px;">
												<div class="push10"></div>
												<div class="col-md-12 text-center <?= $corNps ?>">
													<div class="push10"></div>
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

			</div>

		</div>

	</div>

	<?php

	$sql = "SELECT MP.DES_PERGUNTA,
										(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
										 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR 
										 AND DPI.COD_NPSTIPO = 3
										 $andUnidades
										 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
										 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											) AS TOTAL_PROMOTORES,

										(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
										 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR
										 AND DPI.COD_NPSTIPO = 2
										 $andUnidades
										 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
										 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											) AS TOTAL_NEUTROS,

										 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
										 WHERE DPI.COD_PERGUNTA = MP.COD_REGISTR 
										 AND DPI.COD_NPSTIPO = 1
										 $andUnidades
										 AND DP.COD_REGISTRO = DPI.COD_REGISTRO
										 AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											) AS TOTAL_DETRATORES
											FROM MODELOPESQUISA MP 
										   WHERE MP.COD_TEMPLATE = $cod_pesquisa 
										   AND MP.LOG_PRINCIPAL = 'N'
										   AND TIP_BLOCO = 'squares'
										   AND MP.COD_EXCLUSA IS NULL
										";
	// fnEscreve($sql);
	$arrayCountAvalia = mysqli_query(connTemp($cod_empresa, ''), $sql);

	while ($qrBuscaAvalia = mysqli_fetch_assoc($arrayCountAvalia)) {

		$contador++;

		$TOTAL_PROMOTORESav = $qrBuscaAvalia['TOTAL_PROMOTORES'];
		$TOTAL_NEUTROSav = $qrBuscaAvalia['TOTAL_NEUTROS'];
		$TOTAL_DETRATORESav = $qrBuscaAvalia['TOTAL_DETRATORES'];

		// $total_avalia = $TOTAL_PROMOTORESav + $TOTAL_NEUTROSav + $TOTAL_DETRATORES;
		// Media ponderada = (Ax0 + Bx1 + Cx2 + …. + Jx10) / qtde total de clientes com respostas

		// Clientes totais com respostas = A + B + C + .... + J

		// if($med_ponderada < 6) {
		// 	$corMed = "text-danger";
		// 	$texto = "Detratores";
		// 	$icone = "fa-frown";
		// }else if($med_ponderada >= 6 && $med_ponderada < 9){
		// 	$corMed = "text-warning";
		// 	$texto = "Neutros";
		// 	$icone = "fa-meh";
		// }else{
		// 	$corMed = "text-success";
		// 	$texto = "Promotores";
		// 	$icone = "fa-smile";
		// }

		$pct_detratoresAvalia = $total_clientes != 0 ? (($TOTAL_DETRATORESav) / $total_clientes) * 100 : 0;
		$pct_neutrosAvalia = $total_clientes != 0 ? (($TOTAL_NEUTROSav) / $total_clientes) * 100 : 0;
		$pct_promotoresAvalia = $total_clientes != 0 ? (($TOTAL_PROMOTORESav) / $total_clientes) * 100 : 0;

		// $nps = $pct_promotoresAvalia - $pct_detratoresAvalia;

		// if($nps < 60) {
		// 	$corNps = "text-danger";
		// }else if($nps >= 60 && $nps < 90){
		// 	$corNps = "text-warning";
		// }else{
		// 	$corNps = "text-success";
		// }

	?>

		<div class="row">

			<!-- primeira coluna dash-->
			<div class="col-md-12">

				<div class="row">

					<div class="col-md-12 margin-bottom-30">
						<!-- Portlet -->
						<div class="portlet portlet-bordered">

							<div class="portlet-body">

								<div class="login-form">

									<div class="row">

										<div class="form-group col-md-12">

											<div class="col-md-12">
												<h4><?= $contador ?>. <?= $qrBuscaAvalia['DES_PERGUNTA'] ?></h4>
											</div>

											<div class="push20"></div>

											<div role="tabpanel" class=" tab-pane" id="skill">
												<div class="skill-section">

													<div class="row">

														<div class="col-xs-3 slim">
															<div class="col-xs-11 col-xs-offset-1">
																<div class="col-xs-3 text-right">
																	<span class="fas fa-smile text-success"></span>
																</div>
																<div class="col-xs-9">
																	Promotores
																</div>
															</div>
														</div>

														<div class="col-xs-7 slim">
															<div class="progress">
																<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_promotoresAvalia, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #15BC9C;">
																	<span class="skill-name"><strong></strong></span>
																</div>
															</div>
														</div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_PROMOTORESav, 0)) ?></div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_promotoresAvalia, 2)) ?>%</div>

													</div>

													<div class="row">

														<div class="col-xs-3 slim">
															<div class="col-xs-11 col-xs-offset-1">
																<div class="col-xs-3 text-right">
																	<span class="fas fa-meh text-warning"></span>
																</div>
																<div class="col-xs-9">
																	Neutros
																</div>
															</div>
														</div>

														<div class="col-xs-7 slim">
															<div class="progress">
																<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_neutrosAvalia, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #F39C12;">
																	<span class="skill-name"><strong></strong></span>
																</div>
															</div>
														</div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_NEUTROSav, 0)) ?></div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_neutrosAvalia, 2)) ?>%</div>

													</div>

													<div class="row">

														<div class="col-xs-3 slim">
															<div class="col-xs-11 col-xs-offset-1">
																<div class="col-xs-3 text-right">
																	<span class="fas fa-frown text-danger"></span>
																</div>
																<div class="col-xs-9">
																	Detratores
																</div>
															</div>
														</div>

														<div class="col-xs-7 slim">
															<div class="progress">
																<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($pct_detratoresAvalia, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #E74C3C;">
																	<span class="skill-name"><strong></strong></span>
																</div>
															</div>
														</div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($TOTAL_DETRATORESav, 0)) ?></div>

														<div class="col-xs-1 text-center slim"><?= fnvalorSql(fnValor($pct_detratoresAvalia, 2)) ?>%</div>

													</div>

												</div>

												<div class="row">

													<div class="col-xs-7 col-xs-offset-3">
														<div class="progress-meter">
															<div class="meter meter-left" style="width: 25%;"><span class="meter-text">0</span></div>
															<div class="meter meter-left" style="width: 25%;"><span class="meter-text">25</span></div>
															<div class="meter meter-right" style="width: 20%;"><span class="meter-text">100</span></div>
															<div class="meter meter-right" style="width: 30%;"><span class="meter-text">75</span></div>
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

		</div>

	<?php

	}
}



$sql = "SELECT UV.NOM_FANTASI, 
								       Count(1)                                       AS TOTAL_VOTOS, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                    END), 0)                          AS DETRATORES, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 2 THEN DPI.cod_npstipo 
								                    END), 0)                          AS NEUTROS, 
								       Ifnull(Count(CASE 
								                      WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                    END), 0)                          AS PROMOTORES, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_DETRATORES, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 2 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_NEUTROS, 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) AS PERC_PROMOTORES,
								          
								      ( ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 3 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 ) - 
								       ( ( Ifnull(Count(CASE 
								                          WHEN DPI.cod_npstipo = 1 THEN DPI.cod_npstipo 
								                        END), 0) / Count(1) ) * 100 )) AS NPS
								FROM   dados_pesquisa_itens DPI
								INNER JOIN dados_pesquisa DP ON DP.cod_registro = DPI.cod_registro
								INNER JOIN webtools.unidadevenda UV ON UV.cod_univend = DPI.cod_univend
								WHERE  DPI.cod_pergunta IN (SELECT cod_registr 
								                            FROM   modelopesquisa 
								                            WHERE  cod_template = $cod_pesquisa 
								                                   AND cod_blpesqu = 5 
								                                   AND cod_exclusa IS NULL) 
								       AND DP.cod_registro = DPI.cod_registro 
								       AND DP.dt_horainicial BETWEEN '$dat_ini 00:00:00' AND 
								                                     '$dat_fim 23:59:59' 
								       $andUnidades 
								       AND DPI.COD_EMPRESA = $cod_empresa
								GROUP  BY DPI.COD_UNIVEND
								ORDER BY NPS DESC";

// fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

if (mysqli_num_rows($arrayQuery) > 0) {

	?>

	<div class="row">

		<!-- primeira coluna dash-->
		<div class="col-md-12">

			<div class="row">

				<div class="col-md-12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">

						<div class="portlet-body">

							<div class="login-form">

								<div class="row">

									<div class="form-group col-md-12">

										<div class="col-md-12">
											<h4>Ranking por Loja</h4>
										</div>

										<div class="push20"></div>

										<div role="tabpanel" class=" tab-pane" id="skill">
											<div class="skill-section">

												<?php
												while ($row = mysqli_fetch_assoc($arrayQuery)) {
												?>

													<div class="row">

														<div class="col-xs-3 slim text-right">
															<?= $row['NOM_FANTASI'] ?>
														</div>

														<div class="col-xs-7 slim">
															<div class="progress">
																<div class="progress-bar active text-right" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($row['NPS'], 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="background-color: #207DBB;">
																	<span class="skill-name"><strong><?= fnValor($row['NPS'], 0) ?></strong></span>
																</div>
															</div>
														</div>

														<div class="col-xs-2 slim"><?= $row['TOTAL_VOTOS'] ?> respostas</div>


													</div>

												<?php

												}
												?>

											</div>

										</div>

									</div>

									<div class="row">
										<div class="col-xs-12">
											<div class="col-xs-7 col-xs-offset-3">
												<div class="progress-meter">
													<div class="meter meter-left" style="width: 25%;"><span class="meter-text">0</span></div>
													<div class="meter meter-left" style="width: 25%;"><span class="meter-text">25</span></div>
													<div class="meter meter-right" style="width: 20%;"><span class="meter-text">100</span></div>
													<div class="meter meter-right" style="width: 30%;"><span class="meter-text">75</span></div>
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

<?php

}

?>

<div class="push20"></div>

<div class="row">
	<div class="col-md-2">

		<button onClick="gera_pdf();" name="PDF" id="bt_PDF" class="btn btn-info btn-sm btn-block" disabled>
			<i class="fa fa-file-pdf" aria-hidden="true"></i>
			&nbsp; Gerar PDF
		</button>

	</div>
</div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<!-- Versão compatível do chart js com as labels -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://unpkg.com/chartjs-gauge@0.2.0/dist/chartjs-gauge.js"></script>
<?php
$log_labels = 'S';
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
					cod_registr: pCod_registr
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();


		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)


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

	//       var randomScalingFactor = function() {
	//   return Math.round(Math.random() * 100);
	// };

	var valores = function() {
		return [
			30,
			60,
			100
		];
	};

	var randomValue = function(data) {
		return Math.max.apply(null, data) * Math.random();
	};

	var data = valores();
	var value = <?= $nps ?>;

	var config = {
		<?php if ($log_labels == 'S') { ?>
			plugins: [ChartDataLabels],
		<?php } ?>
		type: 'gauge',
		data: {
			// labels: ['30', '60', '100'],
			datasets: [{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'start',
						borderRadius: 4,
						backgroundColor: [
							'#212F3D',
							'#212F3D',
							'#212F3D'
						],
						color: '#FFF'
					},
				<?php } ?>
				data: data,
				value: value,
				backgroundColor: ['#E74C3C', '#F39C12', '#15BC9C'],
				borderWidth: 2
			}]
		},
		options: {
			cutoutPercentage: 80,
			responsive: true,
			title: {
				display: true,
				text: 'Índice de Recomendação - NPS'
			},
			layout: {
				padding: {
					bottom: 30
				}
			},
			needle: {
				// Needle circle radius as the percentage of the chart area width
				radiusPercentage: 2,
				// Needle width as the percentage of the chart area width
				widthPercentage: 2,
				// Needle length as the percentage of the interval between inner radius (0%) and outer radius (100%) of the arc
				lengthPercentage: 30,
				// The color of the needle
				color: '#212F3D'
			},
			valueLabel: {
				formatter: Math.round,
				backgroundColor: '#212F3D'
			},
			animation: {
				onComplete: function() {
					$("input[name=myGauge]").val(myGauge.toBase64Image());
					botaoPDF();
				}
			}
		}
	};

	window.onload = function() {
		var ctx = document.getElementById('chart').getContext('2d');
		window.myGauge = new Chart(ctx, config);
	};

	Chart.pluginService.register({
		beforeDraw: function(chart, easing) {
			if (chart.config.options.fillColor) {
				var ctx = chart.chart.ctx;
				var chartArea = chart.chartArea;
				ctx.save();
				ctx.fillStyle = chart.config.options.fillColor;
				ctx.fillRect(chartArea.left, chartArea.top + (0), chartArea.right - chartArea.left, (chartArea.bottom - chartArea.top) / 2.5);
				ctx.restore();
			}

			if (chart.config.options.fillColor2) {
				var ctx = chart.chart.ctx;
				var chartArea = chart.chartArea;
				ctx.save();
				ctx.fillStyle = chart.config.options.fillColor2;
				ctx.fillRect(chartArea.left, chartArea.top + (33), chartArea.right - chartArea.left, (chartArea.bottom - chartArea.top) / 3);
				ctx.restore();
			}

			if (chart.config.options.fillColor3) {
				var ctx = chart.chart.ctx;
				var chartArea = chart.chartArea;
				ctx.save();
				ctx.fillStyle = chart.config.options.fillColor3;
				ctx.fillRect(chartArea.left, chartArea.top + (60), chartArea.right - chartArea.left, (chartArea.bottom - chartArea.top) / 3.7);
				ctx.restore();
			}
		}
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
						backgroundColor: '#212F3D',
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
				label: "NPS",
				borderColor: '#212F3D',
				pointBorderColor: '#212F3D',
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?php echo json_encode($arrayNps) ?>
			}]
		},
		// plugins: [ChartDataLabels],
		options: {
			fillColor: 'rgba(21,188,156,0.5)',
			fillColor2: 'rgba(243,156,18,0.5)',
			fillColor3: 'rgba(231,76,60,0.5)',
			legend: {
				display: true,
				position: 'bottom'
			},
			maintainAspectRatio: false,
			animation: {
				duration: 2000,
				onComplete: function() {
					$("input[name=lineChart]").val(lineChart.toBase64Image());
					botaoPDF();
				}
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						max: 100,
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

	function botaoPDF() {
		if (
			($("input[name=lineChart]").val() != "") &&
			($("input[name=myGauge]").val() != "")
		) {
			$("#bt_PDF").removeAttr("disabled");
		}
	}

	function gera_pdf() {
		if ($("form#formPDF").length <= 0) {
			$("body").append("<form style='display:none;' target='_blank' method='post' id='formPDF' action='relatorios/pdfRelPesquisasDiarioSint.php?id=<?= @$_GET["id"] ?>&idP=<?= @$_GET["idP"] ?>'></form>");
		}

		$("form#formPDF").html($("#formulario").html());
		$("form#formPDF").append("<input name='LOJAS' value='<?= $lojasSelecionadas ?>'>");
		$("form#formPDF").append("<input name='lineChart' value='" + $("#inptLineChart").val() + "'>");
		$("form#formPDF").append("<input name='myGauge' value='" + $("#inptMyGauge").val() + "'>");
		$("form#formPDF").submit();
	}
</script>