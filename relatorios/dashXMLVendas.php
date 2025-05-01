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
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$lojasSelecionadas = "";
$qrBuscaIndiceDiario = "";
$data_venda = "";
$pct_diario = "";
$dia_venda = "";
$listaDiarioDias = "";
$listaDiarioPct = "";
$listaTotalFideliz = "";
$qrBuscaVendasFideliz = "";
$data_venda_fideliz = "";
$pct_diario_total = 0;
$pct_diario_fideliz = "";
$dia_venda_fideliz = "";
$contaIndiceDiario = "";
$listaDiarioDiasFideliz = "";
$listaDiarioTot = "";
$listaDiarioFideliz = "";
$tempValor = "";
$qrBuscaResgates = "";
$data_resgates = "";
$val_resgates = "";
$dia_resgates = "";
$listaDiasResgates = "";
$listaValorResgates = "";
$data_fim = "";
$cod_univend = '';

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
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//indice diário - loop
$sql = "SELECT A.DAT_MOVIMENTO AS DATA_VENDA, 
			  ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2) AS PCT_DIARIO 
			FROM VENDAS_DIARIAS A 
			WHERE   DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			  A.COD_EMPRESA = $cod_empresa AND 
			  A.COD_UNIVEND IN($lojasSelecionadas) 
			  GROUP BY DAT_MOVIMENTO ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaIndiceDiario = mysqli_fetch_assoc($arrayQuery)) {
	$data_venda = $qrBuscaIndiceDiario['DATA_VENDA'];
	$pct_diario = $qrBuscaIndiceDiario['PCT_DIARIO'];
	$dia_venda = date('d', strtotime($data_venda));
	$listaDiarioDias =  $listaDiarioDias . "'" . $dia_venda . "',";
	$listaDiarioPct =  $listaDiarioPct . $pct_diario . ",";
	//fnEscreve($data_venda);
}

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca vendas fidelização - loop
$sql = "SELECT DAT_MOVIMENTO, SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ 
			FROM VENDAS_DIARIAS  
			WHERE 
			 DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			COD_EMPRESA = $cod_empresa AND 
			COD_UNIVEND IN($lojasSelecionadas) 
			GROUP BY DAT_MOVIMENTO 
			ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$listaTotalFideliz = 0;

while ($qrBuscaVendasFideliz = mysqli_fetch_assoc($arrayQuery)) {
	$data_venda_fideliz = $qrBuscaVendasFideliz['DAT_MOVIMENTO'];
	$pct_diario_total = $qrBuscaVendasFideliz['QTD_TOTAVULSA'];
	$pct_diario_fideliz = $qrBuscaVendasFideliz['QTD_TOTFIDELIZ'];
	//fnEscreve($qrBuscaIndiceDiario['PCT_DIARIO']);
	$dia_venda_fideliz = date('d', strtotime($data_venda_fideliz));
	//fnEscreve($contaIndiceDiario." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	//fnEscreve($dia_venda." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	$listaDiarioDiasFideliz =  $listaDiarioDiasFideliz . "'" . $dia_venda_fideliz . "',";
	$listaDiarioTot =  $listaDiarioTot . $pct_diario_total . ",";
	$listaDiarioFideliz =  $listaDiarioFideliz . $pct_diario_fideliz . ",";

	$tempValor = $pct_diario_total + $pct_diario_fideliz;
	if ($tempValor > $listaTotalFideliz) {
		$listaTotalFideliz = $tempValor;
	}
}

//fnEscreve($pct_diario_fideliz);	
//fnEscreve($pct_diario_total);	
//fnEscreve($listaTotalFideliz);	
//fnEscreve($listaDiarioPct);

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca resgates - loop
$sql = "SELECT DAT_MOVIMENTO,SUM(VAL_RESGATE) AS VAL_RESGATE 
			FROM VENDAS_DIARIAS 
			WHERE 
			DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			COD_EMPRESA = $cod_empresa AND 
			COD_UNIVEND IN($lojasSelecionadas) 
			GROUP BY DAT_MOVIMENTO 
			ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaResgates = mysqli_fetch_assoc($arrayQuery)) {
	$data_resgates = $qrBuscaResgates['DAT_MOVIMENTO'];
	$val_resgates = $qrBuscaResgates['VAL_RESGATE'];
	$dia_resgates = date('d', strtotime($data_resgates));
	$listaDiasResgates =  $listaDiasResgates . "'" . $dia_resgates . "',";
	$listaValorResgates =  $listaValorResgates . $val_resgates . ",";
	//$contaIndiceDiario++;
}


//fnMostraForm();
//fnEscreve(substr($listaDiarioDiasFideliz,0,-1));
//fnEscreve($hoje);
//fnEscreve($dias30);
//fnEscreve(strlen($dat_ini));
//fnEscreve(strlen($dat_fim));
//fnEscreve($data_fim);
//fnEscreve($lojasSelecionadas);

//------------------------------------------------------------------------------------------------------------------

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca vendas fidelização - loop
$sql = "SELECT DAT_MOVIMENTO, SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ 
					FROM VENDAS_DIARIAS  
					WHERE 
					 DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
					COD_EMPRESA = $cod_empresa AND 
					COD_UNIVEND IN($lojasSelecionadas) 
					GROUP BY DAT_MOVIMENTO 
					ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$listaTotalFideliz = 0;

while ($qrBuscaVendasFideliz = mysqli_fetch_assoc($arrayQuery)) {
	$data_venda_fideliz = $qrBuscaVendasFideliz['DAT_MOVIMENTO'];
	$pct_diario_total = $qrBuscaVendasFideliz['QTD_TOTAVULSA'];
	$pct_diario_fideliz = $qrBuscaVendasFideliz['QTD_TOTFIDELIZ'];
	//fnEscreve($qrBuscaIndiceDiario['PCT_DIARIO']);
	$dia_venda_fideliz = date('d', strtotime($data_venda_fideliz));
	//fnEscreve($contaIndiceDiario." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	//fnEscreve($dia_venda." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	$listaDiarioDiasFideliz =  $listaDiarioDiasFideliz . "'" . $dia_venda_fideliz . "',";
	$listaDiarioTot =  $listaDiarioTot . $pct_diario_total . ",";
	$listaDiarioFideliz =  $listaDiarioFideliz . $pct_diario_fideliz . ",";

	$tempValor = $pct_diario_total + $pct_diario_fideliz;
	if ($tempValor > $listaTotalFideliz) {
		$listaTotalFideliz = $tempValor;
	}
}
//-------------------------------------------------------------------------------------------------------------


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

								<h4>Índice de Sucesso/Erros nas Vendas</h4>
								<div class="push20"></div>

								<div style="height: 300px; width:100%;">
									<canvas id="lineChart2"></canvas>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/pie-chart.js"></script>
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		// Line chart 2
		var ctx = document.getElementById("lineChart2");
		var lineChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: ["8h00", "9h00", "10h00", "11h00", "12h00", "13h00", "14h00"],
				datasets: [{
					label: "Vendas OK",
					borderColor: "rgba(38, 185, 154, 0.7)",
					pointBorderColor: "rgba(38, 185, 154, 0.7)",
					pointBackgroundColor: "#fff",
					pointHoverBackgroundColor: "#fff",
					pointHoverBorderColor: "rgba(220,220,220,1)",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: [31, 74, 6, 39, 20, 85, 7],
					fill: false
				}, {
					label: "Vendas com erro",
					borderColor: "#F00",
					pointBorderColor: "rgba(3, 88, 106, 0.70)",
					pointBackgroundColor: "#fff",
					pointHoverBackgroundColor: "#fff",
					pointHoverBorderColor: "rgba(151,187,205,1)",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: [82, 23, 66, 9, 99, 4, 2],
					fill: false
				}]
			},
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
							suggestedMin: 0,
							suggestedMax: 100
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
				},
				tooltips: {
					enabled: true
				}
			}

		});

	});
</script>