<?php

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$hashLocal = mt_rand();

$hoje = '';
$dias30 = '';
$dat_ini = '';
$dat_fim = '';
$MES = '';
$log_labels = '';

//inicialização de variáveis
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
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = "01/" . @$_REQUEST['DAT_INI'];
		$dat_fim = @$_REQUEST['DAT_FIM'];
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$array_dat_fim  = explode("/", $dat_fim);

		$dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1]) . "/" . $dat_fim;

		if (empty($_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = $_REQUEST['LOG_LABELS'];
		}

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//indice diário - loop
$sql = " CALL REL_CONSOLIDADO_FATURAMENTO ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas', $cod_empresa)";

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$totFat = [];
$totFat_fid = [];
$totQtdCompras = [];
$totQtdCompras_fid = [];
$totQtdClientes = [];

while ($qrFaturamento = mysqli_fetch_assoc($arrayQuery)) {

	// fnEscreve($qrFaturamento['TOTAL_FATURAMENTO']);

	array_push($totFat, $qrFaturamento['VALOR_TOTAL_VENDA']);
	array_push($totFat_fid, $qrFaturamento['VALOR_TOTAL_VENDA_FIDELIZADO']);
	array_push($totQtdCompras, $qrFaturamento['TRANSACOES']);
	array_push($totQtdCompras_fid, $qrFaturamento['TRANSACOES_FIDELIZACAO']);
	array_push($totQtdClientes, $qrFaturamento['QTD_CLIENTES']);


	switch ($qrFaturamento['MES']) {

		case '1':
			$mes_extenso = '"Janeiro/' . $qrFaturamento['ANO'] . '"';
			break;

		case '2':
			$mes_extenso = '"Fevereiro/' . $qrFaturamento['ANO'] . '"';
			break;

		case '3':
			$mes_extenso = '"Março/' . $qrFaturamento['ANO'] . '"';
			break;

		case '4':
			$mes_extenso = '"Abril/' . $qrFaturamento['ANO'] . '"';
			break;

		case '5':
			$mes_extenso = '"Maio/' . $qrFaturamento['ANO'] . '"';
			break;

		case '6':
			$mes_extenso = '"Junho/' . $qrFaturamento['ANO'] . '"';
			break;

		case '7':
			$mes_extenso = '"Julho/' . $qrFaturamento['ANO'] . '"';
			break;

		case '8':
			$mes_extenso = '"Agosto/' . $qrFaturamento['ANO'] . '"';
			break;

		case '9':
			$mes_extenso = '"Setembro/' . $qrFaturamento['ANO'] . '"';
			break;

		case '10':
			$mes_extenso = '"Outubro/' . $qrFaturamento['ANO'] . '"';
			break;

		case '11':
			$mes_extenso = '"Novembro/' . $qrFaturamento['ANO'] . '"';
			break;

		case '12':
			$mes_extenso = '"Dezembro/' . $qrFaturamento['ANO'] . '"';
			break;

		default:
			$mes_extenso = '"Mês não encontrado"';
			break;
	}

	$MES .= $mes_extenso . ',';
}

$dat_ini = fnDatasql($dat_ini);
$dat_fim = fnDatasql($dat_fim);

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}

// $totFat = [];
// $totQtdCompras = [];
// $totQtdClientes = [];

// array_push($totFat,4000,3500,8000,6500,5000);
//    array_push($totQtdCompras,30,25,70,60,40);
//    array_push($totQtdClientes,25,30,60,50,30);
// print_r($totFat);

//fnMostraForm();
//fnEscreve($cod_cliente);

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
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					$formBack = "1015";
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

					<div class="push30"></div>

					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

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

								<div class="push10"></div>

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

						<div class="row">

							<div class="form-group text-center col-lg-12">

								<h4>Visão Geral Vendas e Faturamento</h4>

								<div class="push20"></div>

								<div style="height: 450px; width:100%;">
									<canvas id="lineChart"></canvas>
								</div>

							</div>

							<div class="push20"></div>

							<div class="col-md-12">
								<a class="btn btn-info exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
							</div>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push20"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

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
							align: 'end',
							anchor: 'end',
							borderRadius: 6,
							backgroundColor: '#1A242F',
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
					label: "Faturamento Total Limpo",
					backgroundColor: "rgba(26, 36, 47, 0)",
					borderColor: "#1A242F",
					pointBorderColor: "#1A242F",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totFat) ?>
				}, {
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
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Faturamento Fideliz. Limpo",
					backgroundColor: "rgba(93, 173, 226, 0)",
					borderColor: "#36A2EB",
					pointBorderColor: "#36A2EB",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totFat_fid) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'end',
							anchor: 'start',
							borderRadius: 6,
							backgroundColor: '#606060',
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
					label: "Tot. Vendas",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#606060",
					pointBorderColor: "#606060",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totQtdCompras) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'end',
							anchor: 'start',
							borderRadius: 6,
							backgroundColor: '#A6ACAF',
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
					label: "Vendas Fideliz.",
					backgroundColor: "rgba(166, 172, 175, 0)",
					borderColor: "#A6ACAF",
					pointBorderColor: "#A6ACAF",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totQtdCompras_fid) ?>
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
					label: "Clientes Fideliz.",
					backgroundColor: "rgba(3, 88, 106, 0)",
					borderColor: "#CC2738",
					pointBorderColor: "#CC2738",
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($totQtdClientes) ?>
				}]
			},
			options: {
				layout: {
					padding: {
						left: 60,
						right: 60,
						top: 50,
						bottom: 0
					}
				},
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
						type: 'logarithmic',
						ticks: {
							beginAtZero: true,
							callback: function(value, index, values) {
								if (value === 50000000) return "50M";
								if (value === 10000000) return "10M";
								if (value === 1000000) return "1M";
								if (value === 100000) return "100K";
								if (value === 10000) return "10K";
								if (value === 1000) return "1K";
								if (value === 100) return "100";
								if (value === 10) return "10";
								if (value === 0) return "0";
								return null;
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

		$(".exportarCSV").click(function() {
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
										url: "relatorios/ajxConsolidadoFat.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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