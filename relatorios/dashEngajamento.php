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
$lojasSelecionadas = "";
$pctEngaja = "";
$cadBase = "";
$totCli = "";
$totUnicos = "";
$totNovos = "";
$qrBuscaIndiceDiario = "";
$mes_extenso = "";
$MES = "";
$maxEvo = "";
$maxComp = "";
$checkLabels = "";
$hashLocal = mt_rand();

//fnMostraForm();
//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
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

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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

//indice diário - loop
$sql = "CALL SP_RELAT_INDICE_ENGAJAMENTO ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";
// fnEscreve($sql);
//echo($sql);

@$arrayQuery = mysqli_query($conn, $sql);

$pctEngaja = [];
$cadBase = [];
$totCli = [];
$totUnicos = [];
$totNovos = [];

while (@$qrBuscaIndiceDiario = mysqli_fetch_assoc($arrayQuery)) {

	array_push($pctEngaja, $qrBuscaIndiceDiario['PERCENTUAL_ENGAJAMENTO']);
	array_push($cadBase, $qrBuscaIndiceDiario['TOTAL_CLIENTES_JA_CADASTRADOS']);
	array_push($totCli, $qrBuscaIndiceDiario['TOTAL_CLIENTES_COMPRA']);
	array_push($totUnicos, $qrBuscaIndiceDiario['TOTAL_UNICOS_ATIVOS']);
	array_push($totNovos, $qrBuscaIndiceDiario['TOTAL_CLIENTES_CADASTRADOS_MES']);



	switch ($qrBuscaIndiceDiario['MES']) {

		case '1':
			$mes_extenso = '"Janeiro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '2':
			$mes_extenso = '"Fevereiro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '3':
			$mes_extenso = '"Março/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '4':
			$mes_extenso = '"Abril/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '5':
			$mes_extenso = '"Maio/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '6':
			$mes_extenso = '"Junho/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '7':
			$mes_extenso = '"Julho/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '8':
			$mes_extenso = '"Agosto/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '9':
			$mes_extenso = '"Setembro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '10':
			$mes_extenso = '"Outubro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '11':
			$mes_extenso = '"Novembro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		case '12':
			$mes_extenso = '"Dezembro/' . $qrBuscaIndiceDiario['ANO'] . '"';
			break;

		default:
			$mes_extenso = '"Mês não encontrado"';
			break;
	}

	$MES .= $mes_extenso . ',';
}

@$maxEvo = max(max($cadBase), max($totCli));
@$maxComp = max(max($totNovos), max($totCli), max($totUnicos));

if ($maxEvo < 20000) {
	$maxEvo = (ceil($maxEvo / 10000) * 1000) * 1.2;
} else {
	$maxEvo = (ceil($maxEvo / 10000) * 10000) * 1.2;
}
if ($maxComp < 20000) {
	$maxComp = (ceil($maxComp / 10000) * 1000) * 1.2;
} else {
	$maxComp = (ceil($maxComp / 10000) * 10000) * 1.2;
}

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}

// fnEscreve($maxComp);
// fnEscreve($maxEvo);

$dat_ini = fnDatasql($dat_ini);
$dat_fim = fnDatasql($dat_fim);

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

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-12">

								<h4>Índice de Engajamento Mensal</h4>

								<div class="push20"></div>

								<div style="height: 300px; width:100%;">
									<canvas id="lineChart"></canvas>
								</div>

							</div>


							<div class="push50"></div>


							<div class="form-group text-center col-lg-12">

								<h4>Evolução do Engajamento Mensal</h4>

								<div class="push20"></div>


								<div style="height: 300px; width:100%;">
									<canvas id="lineChart2"></canvas>
								</div>

							</div>

							<div class="push50"></div>

							<div class="form-group text-center col-lg-12">

								<h4>Composição dos Clientes no Engajamento Mensal</h4>

								<div class="push20"></div>

								<div style="height: 300px; width:100%;">
									<canvas id="lineChart3"></canvas>
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

		var log_labels = "<?= $log_labels ?>";

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
					data: <?php echo json_encode($pctEngaja) ?>
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
							anchor: 'start',
							borderRadius: 6,
							backgroundColor: '#36A2EB',
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
					label: "Total de Clientes Com Compras no Mês",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#36A2EB",
					pointBorderColor: "#36A2EB",
					pointBackgroundColor: "#fff",
					pointHoverBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totCli) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#4BC0C0',
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
					label: "Clientes Cadastrados na Base",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#4BC0C0",
					pointBorderColor: "#4BC0C0",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($cadBase) ?>
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
							backgroundColor: '#36A2EB',
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
					label: "Total de Clientes Com Compras no Mês",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#36A2EB",
					pointBorderColor: "#36A2EB",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totCli) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'start',
							borderRadius: 6,
							backgroundColor: '#CC2738',
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
					label: "Clientes Únicos Ativos",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#CC2738",
					pointBorderColor: "#CC2738",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totUnicos) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'start',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#F1C40F',
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
					label: "Novos Clientes",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#F1C40F",
					pointBorderColor: "#F1C40F",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totNovos) ?>
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

	});
</script>