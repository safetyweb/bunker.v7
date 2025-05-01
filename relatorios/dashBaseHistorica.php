<?php
//https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp%C2%A30%C2%A2&id=GLtHxidZjko%C2%A2

//setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
//date_default_timezone_set('America/Sao_Paulo');

// echo fnDebug('true');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$log_labels = "";
$qtd_clientes = 0;
$qtd_juridico = 0;
$qtd_transacoes = 0;
$ticket_medio = 0;
$qtd_clientes_novos = 0;
$vl_faturamento = 0;


$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

$dt_filtro = "";
$mes = ucfirst(strftime('%B', strtotime($hoje)));
$mes_nome = ucfirst(strftime('%B', strtotime($hoje)));
$mesAnt = ucfirst(strftime('%B', strtotime($hoje)));

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
		$dt_filtro = @$_REQUEST['DT_FILTRO'];
		$dt_exibe = $dt_filtro . "-01";
		$mes = ucfirst(strftime('%B', strtotime($dt_exibe)));
		$mes_nome = ucfirst(strftime('%B', strtotime($dt_exibe)));
		$mesAnt = ucfirst(strftime('%B', strtotime($dt_exibe)));

		$dat_ini = fnDatasql($_REQUEST['DAT_INI']);
		$dat_fim = fnDatasql($_REQUEST['DAT_FIM']);

		if (empty($_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = $_REQUEST['LOG_LABELS'];
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		// fnescreve($dt_exibe);
		// fnescreve($dt_filtro);

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
// $log_labels = 'S';

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}


$sqlRef = "SELECT MAX(DAT_CADASTR) AS DAT_REF_MAX, MIN(DAT_CADASTR) AS DAT_REF_MIN FROM VENDAS_BKP WHERE COD_EMPRESA = $cod_empresa";
$qrRef = mysqli_fetch_assoc(mysqli_query($conn, $sqlRef));
$dat_ref_max = $qrRef['DAT_REF_MAX'];
$dat_ref_min = $qrRef['DAT_REF_MIN'];

// fnEscreve($dat_ref_min);
// fnEscreve($dat_ref_max);
//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = "";
}

// fnEscreve($dat_ini);
// fnEscreve($dat_fim);

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

// $array_dat_fim  = explode("/", $dat_fim);

// $dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1])."/".$dat_fim;	

// $dat_ini = fnDatasql($dat_ini);
// $dat_fim = fnDatasql($dat_fim);

?>

<style>
	.circle {
		display: block;
		border-radius: 50%;
		height: 75px;
		width: 75px;
		margin: auto;
		padding: 24px 0;
	}

	.circle span {
		font-size: 20px;
		color: #ffffff;
		font-weight: bold;
	}

	.circle2 {
		display: block;
		border-radius: 50%;
		height: 75px;
		width: 75px;
		margin: auto;
		padding: 28px 0;
	}

	.circle2 span {
		font-size: 17px;
		color: #ffffff;
		font-weight: bold;
	}

	.corBase {
		background: #F8F9F9;
	}

	.cor1 {
		background: #EC7063;
	}

	.cor2 {
		background: #F4D03F;
	}

	.cor3 {
		background: #58D68D;
	}

	.cor4 {
		background: #5DADE2;
	}

	.cor5 {
		background: #909497;
	}

	.fCor1 {
		color: #EC7063;
	}

	.fCor2 {
		color: #F4D03F;
	}

	.fCor3 {
		color: #58D68D;
	}

	.fCor4 {
		color: #5DADE2;
	}

	.fCor5 {
		color: #909497;
	}

	.cor1on {
		background: #CB4335;
		font-size: 18px !important;
	}

	.cor2on {
		background: #D4AC0D;
		font-size: 18px !important;
	}

	.cor3on {
		background: #239B56;
		font-size: 18px !important;
	}

	.cor4on {
		background: #2874A6;
		font-size: 18px !important;
	}

	.bar {
		font-size: 16px;
		line-height: 50px;
		height: 50px;
		border-radius: 5px;
		color: #ffffff;
		font-weight: bold;
		text-align: left;
		margin: auto;
	}

	.f30 {
		font-size: 30px;
		font-weight: bold;
	}

	.bar span {
		background: rgba(255, 255, 255, 0.3);
		padding: 6px 9px;
		border-radius: 4px;
		margin-left: 15px;
		font-size: 18px;
	}

	.tooltip.top .tooltip-inner {
		color: #3c3c3c;
		min-width: 140px;
		min-height: 60px;
		padding-top: 10px;
		font-size: 16px;
		background-color: white;
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
		-webkit-box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
		-moz-box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
		box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
	}

	.tooltip.top .tooltip-arrow {
		border-top-color: white;
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
	}

	.tooltip.in {
		opacity: 0.97 !important;
		filter: alpha(opacity=97) !important;
	}
</style>

<link href="https://unpkg.com/minibarjs@latest/dist/minibar.min.css" rel="stylesheet" type="text/css">
<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> /<?php echo $nom_empresa; ?></span>
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
										<label for="inputName" class="control-label">Referência Histórica</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_REF" id="DAT_REF" value="<?= fnDataShort($dat_ref_min) ?>">
									</div>
									<div class="help-block with-errors">Data mínima</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">&nbsp;</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_REF" id="DAT_REF" value="<?= fnDataShort($dat_ref_max) ?>">
									</div>
									<div class="help-block with-errors">Data máxima</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?= fnDataShort($dat_ini) ?>" required />
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
											<input type='text' class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?= fnDataShort($dat_fim) ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!--
								<div class="col-md-1">   
									<div class="form-group">
										<label for="inputName" class="control-label">Exibir legendas</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S">
											<span></span>
											</label>
									</div>
								</div>
								-->

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					</form>

				</div>

			</div>

		</div>
		<!-- fim Portlet -->
	</div>

</div>

<?php

if ($dat_ini != "") {

	$sql = "CALL SP_RELAT_COMPARACAO_CONSOLIDADA ( '" . $dat_ini . "' , '" . $dat_fim . "' , '$lojasSelecionadas' , $cod_empresa , 'LOJA' ) ;";
	fnEscreve($sql);
	//exit;
	$arrayQuery1 = mysqli_query($conn, $sql);

	//-$qtd_consulta = mysqli_num_rows($arrayQuery);

?>

	<div class="row">

		<div class="col-md-12 col-lg-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">


					<div class="row text-center">

						<div class="form-group text-center col-md-3 col-lg-3">

							<div class="push20"></div>

							<p><span id="QTD_TRANSACOES"><?= fnValor(0, 0) ?></span></p>
							<p><b>Quantidade de compras no período</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-3 col-lg-3">

							<div class="push20"></div>

							<p><span id="QTD_CLIENTES"><?= fnValor(0, 0) ?></span></p>
							<p><b>Clientes no período</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-3 col-lg-3">

							<div class="push20"></div>

							<p>R$<span id="VL_FATURAMENTO"><?= fnValor(0, 2) ?></span></p>
							<p><b>Faturamento do grupo no período</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-3 col-lg-3">

							<div class="push20"></div>

							<p>R$<span id="TICKET_MEDIO"><?= fnValor(0, 2) ?></span></p>
							<p><b>Ticket médio do grupo no período</b></p>

							<div class="push20"></div>

						</div>

					</div>

				</div>
				<!-- fim Portlet -->
			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-md-12 col-lg-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="row text-center">

						<div class="form-group text-center col-md-12 col-lg-12 slimscroll">

							<!-- <h5>Lorem Ipsum</h5> -->
							<div class="push20"></div>
							<div class="push20"></div>

							<table class="table table-striped">

								<thead>
									<tr>
										<th scope="col">Loja</th>
										<th class="text-center"><small>Total <br />Vendas <br /> Fidelizadas</small></th>
										<th class="text-center"><small>Total <br />Vendas <br /> Fidelizadas (R$)</small></th>
										<th class="text-center"><small>Clientes com <br />Compras</small></th>
										<th class="text-center"><small>Cadastrados <br />no Período</small></th>
										<th class="text-center"><small>Ticket <br />Médio</small></th>
										<th class="text-center"><small>Valor por <br />Cliente (R$)</small></th>
										<th class="text-center"><small>Quantidade <br /> de Resgates</small></th>
										<th class="text-center"><small>Total <br /> Resgatados (R$)</small></th>
										<th class="text-center"><small>Clientes <br /> Resgates</small></th>
										<th class="text-center"><small>Qtd. Juridico</small></th>
										<th class="text-center"><small>Idade Média</small></th>
									</tr>
								</thead>

								<tbody>

									<?php

									$count = 1;

									while ($qrComp = mysqli_fetch_assoc($arrayQuery1)) {

										echo '<pre>';
										print_r($qrComp);
										echo '</pre>';

										$sql = "SELECT 
													cod_univend,
													Count(cod_credito) QTD_RESGATE,
													Count(distinct cod_CLIENTE) QTD_CLIENTE_RESGATE,
													SUM(val_credito) - SUM(val_saldo) as VL_TOTAL_RESGATE 
													FROM creditosdebitos_bkp 
													WHERE cod_empresa=$cod_empresa 
													and tip_credito='C' 
													and cod_statuscred !='6'
													and DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59'
													AND COD_UNIVEND = " . $qrComp['COD_UNIVEND'] . "
													group by cod_univend";


										$queryRes = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$arrayQuery2 = mysqli_fetch_assoc($queryRes);

										$qtd_clientes += $qrComp['QTD_CLIENTES'];
										$qtd_clientes_novos += $qrComp['QTD_CLINOVOS'];
										$qtd_juridico += $qrComp['QTD_JURIDICO'];

										$vl_faturamento += $qrComp['VL_FATURAMENTO'];
										$qtd_transacoes += $qrComp['QTD_TRANSACOES'];
										$ticket_medio += $qrComp['TICKET_MEDIO'];

									?>

										<tr>
											<td><b><small><?= $qrComp['LOJA'] ?></small></b></td>
											<td class="text-center"><small><?= fnValor($qrComp['QTD_TRANSACOES'], 0) ?></small></td>
											<td class="text-center"><small>R$<?= fnValor($qrComp['VL_FATURAMENTO'], 2) ?></small></td>
											<td class="text-center"><small><?= fnValor($qrComp['QTD_CLIENTES'], 0) ?></small></td>
											<td class="text-center"><small><?= fnValor($qrComp['QTD_CLINOVOS'], 0) ?></small></td>
											<td class="text-center"><small>R$<?= fnValor($qrComp['TICKET_MEDIO'], 2) ?></small></td>
											<td class="text-center"><small>R$<?= fnValor($qrComp['QTD_CLIENTES'] != 0  ? ($qrComp['VL_FATURAMENTO'] /  $qrComp['QTD_CLIENTES']) : 0, 2) ?></small></td>

											<td class="text-center"><small><?= fnValor($arrayQuery2['QTD_CLIENTE_RESGATE'], 0) ?></small></td>
											<td class="text-center"><small>R$<?= fnValor($arrayQuery2['VL_TOTAL_RESGATE'], 2) ?></small></td>
											<td class="text-center"><small><?= fnValor($arrayQuery2['QTD_RESGATE'], 0) ?></small></td>

											<td class="text-center"><small><?= fnValor($qrComp['QTD_JURIDICO'], 0) ?></small></td>
											<td class="text-center"><small><?= fnValor($qrComp['IDADE_MEDIA'], 0) ?></small></td>
										</tr>

									<?php

										$count++;
									}

									?>

								</tbody>

							</table>

							<div class="push50"></div>

						</div>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12">
								<a class="btn btn-info btn-sm exportarCSV pull-left"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
							</div>
						</div>

						<div class="push50"></div>

					</div>

				</div>
				<!-- fim Portlet -->
			</div>

		</div>

	</div>

<?php } ?>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />




<script>
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		$('.datepicker').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: '<?= fnDataSql($dat_ref_min) ?>',
			maxDate: '<?= fnDataSql($dat_ref_max) ?>',
			useCurrent: false,
			viewMode: 'years'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		// $('#DAT_FIM_GRP').datetimepicker({
		// 	format: 'DD/MM/YYYY',
		// 	maxDate:'<?= fnDataSql($dat_ref_max) ?>',
		// 	useCurrent: false,
		// 	viewMode: 'years'
		// }).on('changeDate', function(e){
		// 	$(this).datetimepicker('hide');
		// });


		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

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
									url: "relatorios/ajxDashBaseHistorica.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	<?php if ($dat_ini != "") { ?>

		$(document).ready(function() {

			$("#QTD_TRANSACOES").text("<?= fnValor($qtd_transacoes, 0) ?>");
			$("#QTD_CLIENTES").text("<?= fnValor($qtd_clientes, 0) ?>");
			$("#VL_FATURAMENTO").text("<?= fnValor($vl_faturamento, 2) ?>");
			$("#TICKET_MEDIO").text("<?= fnValor($ticket_medio, 2) ?>");

		});


	<?php } ?>
</script>