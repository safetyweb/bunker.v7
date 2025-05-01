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
$log_labels = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$lojasSelecionadas = "";
$qrBuscaTmGm = "";
$listaTotTmResgate = "";
$listaTotTmSem = "";
$listaTotTmAvulso = "";
$listaTotGmResgate = "";
$listaTotGmSem = "";
$listaTotGmAvulso = "";
$qrBuscaTransac = "";
$listaTotTransacResgate = "";
$listaTotTransacSem = "";
$listaTotTransacAvulso = "";
$qtdTotTransacResgate = 0;
$qtdTotTransacSemRes = 0;
$qtdTotTransacAvulso = 0;
$qrBuscaResgate = "";
$listaResgateTransac = "";
$listaResgateCli = "";
$qrBuscaFaturamento = "";
$listaFatFid = "";
$listaPctFatAv = "";
$listaFatRes = "";
$listaFatTot = "";
$listaFatTotRes = "";
$listaFatLmp = "";
$listaFatAv = "";
$novoTotalGeral = "";
$qrBuscaAbsoluto = "";
$listaPctVVR = "";
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

$sql = " CALL SP_RELAT_INDICE_TM_GM ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

@$qrBuscaTmGm = mysqli_fetch_assoc($arrayQuery);

$listaTotTmResgate = $qrBuscaTmGm['TM_RESGATE'];
$listaTotTmSem = $qrBuscaTmGm['TM_SEM_RESGATE'];
$listaTotTmAvulso = $qrBuscaTmGm['TM_AVULSO'];
$listaTotGmResgate = $qrBuscaTmGm['GM_RESGATE'];
$listaTotGmSem = $qrBuscaTmGm['GM_SEM_RESGATE'];
$listaTotGmAvulso = $qrBuscaTmGm['V_VALORGM_AVULSO'];


$sql = " CALL SP_RELAT_ROSCA_TRANSACOES ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

@$qrBuscaTransac = mysqli_fetch_assoc($arrayQuery);

$listaTotTransacResgate = $qrBuscaTransac['TRANSACOES_RESGATE'];
$listaTotTransacSem = $qrBuscaTransac['TRANSACOES_SEM_RESGATE'];
$listaTotTransacAvulso = $qrBuscaTransac['TRANSACOES_AVULSO'];
$qtdTotTransacResgate = $qrBuscaTransac['QTD_TRANSACOES_RESGATE'];
$qtdTotTransacSemRes = $qrBuscaTransac['QTD_TRANSACOES_SEM_RESGATE'];
$qtdTotTransacAvulso = $qrBuscaTransac['QTD_TRANSACOES_AVULSAS'];


$sql = " CALL SP_RELAT_BARRA_RESGATES ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

@$qrBuscaResgate = mysqli_fetch_assoc($arrayQuery);

$listaResgateTransac = $qrBuscaResgate['RESGATE_TRANSACAO'];
$listaResgateCli = $qrBuscaResgate['RESGATE_CLIENTE'];


$sql = " CALL SP_RELAT_ABSOLUTO_FATURAMENTO ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

@$qrBuscaFaturamento = mysqli_fetch_assoc($arrayQuery);

$listaFatFid = $qrBuscaFaturamento['PERCENTUAL_FIDELIZADO'];
$listaPctFatAv = $qrBuscaFaturamento['PERCENTUAL_AVULSO'];
$listaFatRes = $qrBuscaFaturamento['PERCENTUAL_RESGATE'];

$listaFatTot = $qrBuscaFaturamento['FATURAMENTO_TOTAL'];
$listaFatTotRes = $qrBuscaFaturamento['VALOR_TOTAL_RESGATE'];
$listaFatLmp = $qrBuscaFaturamento['FATURAMENTO_LIMPO'];
$listaFatAv = $qrBuscaFaturamento['FATURAMENTO_AVULSO'];
$novoTotalGeral = $qrBuscaFaturamento['VALOR_TOTAL_RESGATE'] + $qrBuscaFaturamento['FATURAMENTO_LIMPO'] + $qrBuscaFaturamento['FATURAMENTO_AVULSO'];


$sql = " CALL SP_RELAT_ABSOLUTO_VVR ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

@$qrBuscaAbsoluto = mysqli_fetch_assoc($arrayQuery);

$listaPctVVR = $qrBuscaAbsoluto['PERCENTUAL_VVR'];



if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}



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
										<label for="inputName" class="control-label">Exibir Legendas</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?> checked>
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

							<div class="form-group text-center col-lg-5 col-lg-offset-1">

								<h4>Tickets e Gastos Médios Limpos</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped" style="height: 100%"></canvas>
								</div>

							</div>

							<div class="form-group text-center col-lg-5">

								<h4>Composição das Transações</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="donut" style="height: 100%"></canvas>
								</div>


							</div>

							<div class="push100"></div>
							<div class="push100"></div>
							<div class="push100"></div>


							<div class="form-group text-center col-lg-5 col-lg-offset-1">

								<h4>Resgate Médio no Programa Fidelização</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="horizontal" style="height: 100%"></canvas>
								</div>

							</div>

							<div class="form-group text-center col-lg-5">

								<h4>% Venda Vinculada ao Resgate</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="horizontal2" style="height: 100%"></canvas>
								</div>

							</div>


							<div class="push100"></div>
							<div class="push100"></div>
							<div class="push100"></div>


							<div class="form-group text-center col-lg-6 col-lg-offset-3">

								<h4>Participação do Resgate no Faturamento</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="pieChart" style="height: 100%"></canvas>
								</div>

							</div>


							<div class="push100"></div>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push100"></div>
						<div class="push100"></div>


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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

<?php
}
?>

<script>
	//datas
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			//maxDate: moment().subtract(1, 'days'),
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

		//grouped
		new Chart(document.getElementById("bar-chart-grouped"), {
			type: 'bar',
			data: {
				labels: ["Ticket Médio", "Gasto Médio"],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#CC2738',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Com Resgate",
					borderColor: '#CC2738',
					backgroundColor: 'rgba(204,39,56,0.7)',
					borderWidth: 1,
					data: [<?= $listaTotTmResgate ?>, <?= $listaTotGmResgate ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#FF3784',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Sem Resgate",
					borderColor: '#FF3784',
					backgroundColor: 'rgba(255,55,132,0.7)',
					borderWidth: 1,
					data: [<?= $listaTotTmSem ?>, <?= $listaTotGmSem ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#606060',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Avulso",
					borderColor: '#606060',
					backgroundColor: 'rgba(96,96,96,0.7)',
					borderWidth: 1,
					data: [<?= $listaTotTmAvulso ?>, <?= $listaTotGmAvulso ?>]
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				title: {
					display: true,
					text: 'TMs e GMs Fidelizados [CR e SR] vs Avulsos'
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return 'R$ ' + t.yLabel.toFixed(2)
						}
					}
				},
				scales: {
					yAxes: [{
						ticks: {
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
							}
						}
					}]
				},

			}
		});


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
								'#CC2738',
								'#FF3784',
								'#606060',
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
						<?= $listaTotTransacResgate ?>,
						<?= $listaTotTransacSem ?>,
						<?= $listaTotTransacAvulso ?>
					],
					amount: [

						<?= $qtdTotTransacResgate ?>,
						<?= $qtdTotTransacSemRes ?>,
						<?= $qtdTotTransacAvulso ?>
					],
					borderColor: [
						'#CC2738',
						'#FF3784',
						'#606060',
					],
					backgroundColor: [
						'rgba(204,39,56,0.7)',
						'rgba(255,55,132,0.7)',
						'rgba(96,96,96,1)',
					],
					borderWidth: [1, 1, 0],
				}],
				labels: [
					" COM Resgate",
					" SEM Resgate",
					" AVULSOS"
				]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function(tooltipItem, data) {
							var amountData = data['datasets'][0]['amount'][tooltipItem['index']];
							return amountData.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " - " + data['datasets'][0]['data'][tooltipItem['index']] + '%';
						}
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				title: {
					display: true,
					text: 'Transações Fidelizadas [CR e SR] vs Avulsas'
				},
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
			var ctx = document.getElementById("donut").getContext("2d");
			window.myDoughnut = new Chart(ctx, config);
		};


		var MeSeChart = new Chart(document.getElementById("horizontal").getContext("2d"), {
			type: 'horizontalBar',
			data: {
				labels: [
					"VR Cliente",
					"VR Transação"
				],
				datasets: [{
					labels: [
						"VR Cliente",
						"VR Transação"
					],
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#F77825',
								'#F1C40F'
							],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					data: [<?= $listaResgateCli ?>, <?= $listaResgateTransac ?>],
					borderColor: ['#F77825', '#F1C40F'],
					backgroundColor: ['rgba(247,120,37,0.3)', 'rgba(241,196,15,0.3)'],
					borderWidth: [1, 1]
					// hoverBackgroundColor: ["red", "#FCCE56"]
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return 'R$ ' + t.xLabel.toFixed(2);
						}
					}
				},
				scales: {
					xAxes: [{
						ticks: {
							min: 0,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
							}
						}
					}],
					yAxes: [{
						stacked: true
					}]
				},
				legend: {
					display: false
				},
			}
		});


		var data = {
			labels: ["Fideliz. Limpo - R$ <?= fnValor($listaFatLmp, 2) ?>", "Avulso - R$ <?= fnValor($listaFatAv, 2) ?>", "Resgate Total - R$ <?= fnValor($listaFatTotRes, 2) ?>"],
			datasets: [{

				backgroundColor: [
					'rgba(241,196,15,0.5)',
					'rgba(96,96,96,1)',
					'rgba(139,98,138,0.5)'
				],
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
							'rgba(241,196,15,1)',
							'rgba(96,96,96,1)',
							'rgba(139,98,138,1)',
						],
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value + '%';
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				data: [<?= $listaFatFid ?>, <?= $listaPctFatAv ?>, <?= $listaFatRes ?>],
				// Notice the borderColor 
				// borderColor: ['black', 'black'],
				borderWidth: [1, 1]
			}]
		};

		// Notice the rotation from the documentation.


		// Chart declaration:
		var mypieChart = new Chart(document.getElementById("pieChart").getContext('2d'), {
			type: 'pie',
			data: data,
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
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
				rotation: -0.3 * Math.PI,
				title: {
					display: true,
					position: 'bottom',
					text: "Total: R$<?= fnValor($novoTotalGeral, 2) ?>"
					// , "Limpo: R$<?= fnValor($listaFatLmp, 2) ?>" , "Avulso: R$<?= fnValor($listaFatAv, 2) ?>"
				},
			}
		});




		var MeSeChart2 = new Chart(document.getElementById("horizontal2").getContext("2d"), {
			type: 'horizontalBar',
			data: {
				labels: [
					"%VVR"
				],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#CC2738',
							],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value + '%';
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "%VVR",
					data: [<?= $listaPctVVR ?>],
					borderColor: '#CC2738',
					backgroundColor: 'rgba(204,39,56,0.7)',
					borderWidth: 1
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.xLabel.toFixed(2) + '%';
						}
					}
				},
				scales: {
					xAxes: [{
						ticks: {
							min: 0,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
								} else {
									return value + '%';
								}
							}
						}
					}],
					yAxes: [{
						stacked: true,
						barPercentage: 0.5
					}]
				},
				legend: {
					position: 'bottom',
				}

			}
		});

	});
</script>