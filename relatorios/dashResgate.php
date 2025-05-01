<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$array_dat_fim = [];
$log_labels = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$listaPctFatura = "";
$listaPctTotFat = "";
$listaPctFideliz = "";
$listaPctTot = "";
$listaPctEngaja = "";
$listaPctVVR = "";
$listaTotVVR = "";
$listaTotResg = "";
$lojasSelecionadas = "";
$qrBuscaFaturamento = "";
$qrBuscaTransacoes = "";
$qrBuscaEngajamentoResgate = "";
$mes_extenso = "";
$MES = "";
$checkLabels = "";


$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = "01/" . @$_REQUEST['DAT_INI'];
		$dat_fim = @$_REQUEST['DAT_FIM'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$array_dat_fim  = explode("/", $dat_fim);

		$dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim['0'], $array_dat_fim['1']) . "/" . $dat_fim;

		if (empty(@$_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = @$_REQUEST['LOG_LABELS'];
		}

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

$listaPctFatura = [];
$listaPctTotFat = [];
$listaPctFideliz = [];
$listaPctTot = [];
$listaPctEngaja = [];
$listaPctVVR = [];
$listaTotVVR = [];
$listaTotResg = [];


$sql = " CALL SP_RELAT_INDICE_FIDELIDADE_VVR ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while (@$qrBuscaFaturamento = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctVVR, $qrBuscaFaturamento['PERCENTUAL_VVR']);
	array_push($listaTotVVR, $qrBuscaFaturamento['TOTAL_VVR']);
	array_push($listaTotResg, $qrBuscaFaturamento['TOTAL_RESGATE']);
}

$sql = " CALL SP_RELAT_INDICE_RESGATE_FATURAMENTO ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while (@$qrBuscaFaturamento = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctFatura, $qrBuscaFaturamento['PERCENTUAL_ENGAJAMENTO']);
	array_push($listaPctTotFat, $qrBuscaFaturamento['PERCENTUAL_ENGAJAMENTO_TOTAL']);
}





$sql = " CALL SP_RELAT_INDICE_RESGATE_TRANSACOES ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while (@$qrBuscaTransacoes = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctFideliz, $qrBuscaTransacoes['PERCENTUAL_FIDELIZADO']);
	array_push($listaPctTot, $qrBuscaTransacoes['PERCENTUAL_TOTAL']);
}





///indice diário - loop
$sql = " CALL SP_RELAT_INDICE_ENGAJAMENTO_RESGATE ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while (@$qrBuscaEngajamentoResgate = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctEngaja, $qrBuscaEngajamentoResgate['PERCENTUAL_ENGAJAMENTO']);

	switch ($qrBuscaEngajamentoResgate['MES']) {

		case '1':
			$mes_extenso = '"Janeiro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '2':
			$mes_extenso = '"Fevereiro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '3':
			$mes_extenso = '"Março/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '4':
			$mes_extenso = '"Abril/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '5':
			$mes_extenso = '"Maio/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '6':
			$mes_extenso = '"Junho/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '7':
			$mes_extenso = '"Julho/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '8':
			$mes_extenso = '"Agosto/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '9':
			$mes_extenso = '"Setembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '10':
			$mes_extenso = '"Outubro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '11':
			$mes_extenso = '"Novembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '12':
			$mes_extenso = '"Dezembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		default:
			$mes_extenso = '"Mês não encontrado"';
			break;
	}

	$MES .= $mes_extenso . ',';
}

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}

$dat_ini = fnDatasql($dat_ini);
$dat_fim = fnDatasql($dat_fim);

//fnEscreve('teste 2');


?>


<div class="push30"></div>

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm" data-mask="00/0000" name="DAT_INI" id="DAT_INI" value="<?= date('m/Y', strtotime($dat_ini)) ?>" required />
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
											<input type='text' class="form-control input-sm" data-mask="00/0000" name="DAT_FIM" id="DAT_FIM" value="<?= date('m/Y', strtotime($dat_fim)) ?>" required />
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

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class=" portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-12">

								<div class="push20"></div>
								<h4>Índice de Engajamento Clientes com Resgate</h4>
								<div class="push20"></div>

								<div style="height: 550px; width:100%;">
									<canvas id="lineChart"></canvas>
								</div>

							</div>


							<div class="push50"></div>


							<div class="form-group text-center col-lg-12">


								<div class="push20"></div>
								<h4>Índice de Resgate no Faturamento Fidelizado</h4>
								<div class="push20"></div>

								<div style="height: 550px; width:100%;">
									<canvas id="lineChart3"></canvas>
								</div>

							</div>

							<div class="push50"></div>

							<div class="form-group text-center col-lg-12">

								<div class="push20"></div>
								<h4>Índice de Resgates nas Transações Totais e Fidelizadas</h4>
								<div class="push20"></div>

								<div style="height: 550px; width:100%;">
									<canvas id="lineChart2"></canvas>
								</div>

							</div>

							<div class="push50"></div>

							<div class="push30"></div>

							<div class="form-group text-center col-lg-12">

								<h4>Índice de Venda Vinculada ao Resgate</h4>
								<div class="push20"></div>

								<div>
									<canvas id="Stacked" style="height: 700px; width:100%;"></canvas>
								</div>

							</div>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push50"></div>


					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

<?php
}
?>
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-piechart-outlabels"></script>  -->
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	// Chart.plugins.unregister(ChartDataLabels);

	//datas
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		$('.datePicker').datetimepicker({
			viewMode: 'years',
			format: 'MM/YYYY',
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

		$('#demo-pie-1').pieChart({
			barColor: '#3bb2d0',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();


		// Line chart
		var ctx = document.getElementById("lineChart");
		var lineChart = new Chart(ctx, {
			type: 'line',
			onAnimationComplete: new function() {

			},
			data: {
				labels: [<?php echo substr($MES, 0, -1); ?>],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#36A2EB',
							color: '#fff',
							formatter: function(value) {
								return value + '%';
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Percentual atingido",
					backgroundColor: "rgba(93, 173, 226, 0)",
					borderColor: "#36A2EB",
					pointBorderColor: "#36A2EB",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctEngaja) ?>
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
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
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.yLabel.toFixed(2) + '%';
						}
					}
				},
			}
		});


		// Line chart 3
		var ctx = document.getElementById("lineChart3");
		var lineChart = new Chart(ctx, {
			type: 'line',
			onAnimationComplete: new function() {

			},
			data: {
				labels: [<?php echo substr($MES, 0, -1); ?>],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#CC2738',
							color: '#fff',
							formatter: function(value) {
								return value + '%';
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "%VR | Fat. Fidelizado",
					backgroundColor: "rgba(231, 76, 60, 0)",
					borderColor: "#CC2738",
					pointBorderColor: "#CC2738",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctFatura); ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#00A8C6',
							color: '#fff',
							formatter: function(value) {
								return value + '%';
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "%VR | Fat. Total",
					backgroundColor: "rgba(0,0,0,0)",
					borderColor: "#00A8C6",
					pointBorderColor: "#00A8C6",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctTotFat); ?>
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
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
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.yLabel.toFixed(2) + '%';
						}
					}
				},
			}
		});


		// Line chart 2
		var ctx = document.getElementById("lineChart2");
		var lineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: [<?php echo substr($MES, 0, -1); ?>],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#4BC0C0',
							color: '#fff',
							formatter: function(value) {
								return value + '%';
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "%R | Trans. Fidelizadas",
					backgroundColor: "rgba(0,0,0,0)",
					borderColor: "#4BC0C0",
					pointBorderColor: "#4BC0C0",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctFideliz) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#00A8C6',
							color: '#fff',
							formatter: function(value) {
								return value + '%';
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "%R | Trans. Totais",
					backgroundColor: "rgba(0,0,0,0)",
					borderColor: "#00A8C6",
					pointBorderColor: "#00A8C6",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctTot) ?>
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
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
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.yLabel.toFixed(2) + '%';
						}
					}
				},
			}

		});



		//---------------------------------------------------------------------

		var barChartData = {
			labels: [<?php echo substr($MES, 0, -1); ?>],
			datasets: [{ //----------------------------------------------------------Gráfico de linha------------------
				label: '% VVR',
				yAxisID: 'porcentagem',
				borderColor: '#F1C40F',
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?php echo json_encode($listaPctVVR); ?>,
				type: 'line',
				fill: false
			}, {
				label: ' VVR',
				yAxisID: 'valor',
				borderColor: '#4BC0C0',
				backgroundColor: 'rgba(75,192,192,0.3)',
				borderWidth: 3,
				data: <?php echo json_encode($listaTotVVR); ?>
			}, {
				label: 'Valor do Resgate',
				yAxisID: 'valor',
				borderColor: '#CC2738',
				backgroundColor: 'rgba(204,39,56,0.3)',
				borderWidth: 3,
				data: <?php echo json_encode($listaTotResg); ?>
			}] //---------------------------------------------------------------------------------------------

		}


		var newScale = <?php echo (0 + 10) ?>;

		// Chart.plugins.unregister(ChartDataLabels);

		var ctx2 = document.getElementById("Stacked").getContext("2d");
		window.myBar = new Chart(ctx2, {
			type: 'bar',
			data: barChartData,
			options: {
				legend: {
					display: true,
					position: 'bottom'
				},
				maintainAspectRatio: true,
				animation: {
					duration: 2000
				},
				scales: {
					yAxes: [{
						id: 'valor',
						type: 'linear',
						position: 'left',
						stacked: true,
						ticks: {
							suggestedMax: newScale,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$' + value;
								}
							}
						},
					}, {
						id: 'porcentagem',
						type: 'linear',
						position: 'right',
						ticks: {
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
					xAxes: [{
						stacked: true
					}],
				},
				tooltips: {
					enabled: true,
					intersect: false,
					mode: 'index',
					callbacks: {
						label: function(t, d) {
							var xLabel = d.datasets[t.datasetIndex].label;
							var yLabel = t.yLabel;
							// if line chart
							if (t.datasetIndex === 0) return xLabel + ': ' + yLabel.toFixed(2) + '%';
							// if bar chart
							// else if (t.datasetIndex === 1) return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							else return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						}
					}
				}
			}
		});

		//------------------------------------------------------------------

	});
</script>