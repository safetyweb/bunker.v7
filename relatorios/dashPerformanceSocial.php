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
$num_cgcecpf = "";
$nom_cliente = "";
$cod_indicad = "";
$cod_tpfiltro = "";
$Arr_COD_INDICAD = "";
$i = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$qrIndica = "";
$disabled = "";
$qrLista = "";
$cor = "";
$cores = "";
$sql1 = "";
$arrayQuery1 = [];
$qrBuscaIndicador = "";
$nom_indicador = "";
$total_cadastro = 0;
$sql2 = "";
$arrayQuery2 = [];
$des_filtro_arr = "";
$total_filtro_arr = 0;
$qrBuscaTotalizador = "";
$lojasSelecionadas = "";



$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

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
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);

		// $cod_indicad = fnLimpaCampoZero(@$_POST['COD_INDICAD']);
		$cod_tpfiltro = fnLimpaCampoZero(@$_POST['COD_TPFILTRO']);

		if (isset($_POST['COD_INDICAD'])) {
			$Arr_COD_INDICAD = @$_POST['COD_INDICAD'];

			for ($i = 0; $i < count($Arr_COD_INDICAD); $i++) {
				$cod_indicad = $cod_indicad . $Arr_COD_INDICAD[$i] . ",";
			}

			$cod_indicad = ltrim(rtrim($cod_indicad, ','), ',');
		} else {
			$cod_indicad = "0";
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

// fnEscreve($cod_indicad);

//busca revendas do usuário
//include "unidadesAutorizadas.php"; 

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);
//fnEscreve($cod_indicad);

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
				</div>

				<?php
				//$formBack = "1015";
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
										<label for="inputName" class="control-label ">Indicador</label>
										<select class="chosen-select-deselect" data-placeholder="Selecione o indicador" name="COD_INDICAD[]" id="COD_INDICAD" multiple>
											<option value=""></option>
											<?php

											$sql = "SELECT DISTINCT A.COD_INDICAD,
																		(SELECT DISTINCT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR 
																FROM CLIENTES A 
																WHERE A.COD_EMPRESA = $cod_empresa
																ORDER BY NOM_INDICADOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrIndica = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrIndica['COD_INDICAD']; ?>"><?php echo $qrIndica['NOM_INDICADOR']; ?></option>
											<?php
											}
											?>
										</select>
										<script type="text/javascript">
											var indicad = '<?php echo $cod_indicad; ?>';
											if (indicad != 0 && indicad != "") {
												//retorno combo multiplo - lojas
												$("#formulario #COD_INDICAD").val('').trigger("chosen:updated");

												var sistemasUni = '<?php echo $cod_indicad; ?>';
												var sistemasUniArr = sistemasUni.split(',');
												//opções multiplas
												for (var i = 0; i < sistemasUniArr.length; i++) {
													$("#formulario #COD_INDICAD option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
												}
												$("#formulario #COD_INDICAD").trigger("chosen:updated");
											}
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3" id="relatorioConteudo">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo do Filtro</label>
										<select data-placeholder="Selecione um tipo" name="COD_TPFILTRO" id="COD_TPFILTRO" class="chosen-select-deselect requiredchk" style="width:100%" required <?= $disabled ?>>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
															WHERE COD_EMPRESA = $cod_empresa order by DES_TPFILTRO ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?= $qrLista['COD_TPFILTRO'] ?>"><?= $qrLista['DES_TPFILTRO'] ?></option>
											<?php
											}
											?>

											<!-- <option value="add">&nbsp;ADICIONAR NOVO</option> -->
										</select>
										<script type="text/javascript">
											$('#COD_TPFILTRO').val(<?= $cod_tpfiltro ?>).trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
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

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>

									<div class="row text-center">

										<?php

										if ($cod_indicad != "0" && $cod_indicad != "") {
											$cod_indicad = explode(',', $cod_indicad);

											$cor = [];
											$cores = array("#E6B0AA", "#D7BDE2", "#A9CCE3", "#A3E4D7", "#A9DFBF", "#F9E79F", "#F9E79F", "#CCD1D1", "#ABB2B9", "#E74C3C", "#E74C3C", "#3498DB", "#16A085", "#2ECC71", "#F39C12", "#D35400", "#7F8C8D", "#34495E");
											for ($i = 0; $i < count($cores); $i++) {
												$cor[] = array_rand($cores);
											}

											for ($i = 0; $i < count($cod_indicad); $i++) {

												$sql1 = "SELECT       
																  (SELECT A.NOM_CLIENTE FROM CLIENTES A WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR,
																 COUNT(*) as TOTAL_CADASTRO
															FROM CLIENTES CL
															WHERE CL.COD_EMPRESA = $cod_empresa AND
																	CL.LOG_AVULSO = 'N' AND 
																	CL.COD_INDICAD = $cod_indicad[$i] AND 
																	DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
																	";
												//fnEscreve($sql1);		
												$arrayQuery1 = mysqli_query(connTemp($cod_empresa, ''), $sql1);

												$count = 0;
												while ($qrBuscaIndicador = mysqli_fetch_assoc($arrayQuery1)) {

													$nom_indicador = $qrBuscaIndicador['NOM_INDICADOR'];
													$total_cadastro = $qrBuscaIndicador['TOTAL_CADASTRO'];
													// fnEscreve($nom_indicador);
													// fnEscreve($total_cadastro);

													//busca totalizadores dinâmicos
													$sql2 = "SELECT A.DES_FILTRO,
																(SELECT COUNT(*) 
																		FROM CLIENTE_FILTROS B, CLIENTES C WHERE 
																		A.COD_FILTRO=B.COD_FILTRO AND B.COD_CLIENTE=C.COD_CLIENTE AND C.COD_INDICAD = $cod_indicad[$i] and
																		C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' ) TOTAL_FILTRO

																FROM FILTROS_CLIENTE A 
																WHERE A.COD_TPFILTRO = $cod_tpfiltro  AND 
																	  A.COD_EMPRESA = $cod_empresa AND 
																(SELECT COUNT(*) 
																		FROM CLIENTE_FILTROS B, CLIENTES C WHERE 
																		A.COD_FILTRO=B.COD_FILTRO AND B.COD_CLIENTE=C.COD_CLIENTE AND C.COD_INDICAD = $cod_indicad[$i] AND
																		C.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59') > 0	";

													// fnEscreve($sql2);		
													$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

													$cont = 0;
													$des_filtro_arr = [];
													$total_filtro_arr = [];



													while ($qrBuscaTotalizador = mysqli_fetch_assoc($arrayQuery2)) {
														$des_filtro_arr[] = $qrBuscaTotalizador['DES_FILTRO'];
														$total_filtro_arr[] = $qrBuscaTotalizador['TOTAL_FILTRO'];

														$cont++;
													}



										?>

													<div class="form-group text-center col-sm-6 col-md-4">

														<!-- <h4>Composição das Transações</h4> -->
														<div class="push20"></div>

														<div style="max-height: 200px; max-width:100%;">
															<canvas id="donut_<?= $i ?>" style="height: 100%"></canvas>
														</div>

														<div class="push100"></div>
														<div class="push100"></div>
														<!-- <div class="push100"></div> -->

													</div>

													<script>
														$(function() {

															// var ctx = document.getElementById("donut_<?= $i ?>").getContext("2d");
															new Chart(document.getElementById("donut_<?= $i ?>").getContext("2d"), {
																type: 'doughnut',
																data: {
																	datasets: [{
																		data: <?= json_encode($total_filtro_arr) ?>,
																		backgroundColor: <?= json_encode($cores) ?>,
																	}],
																	labels: <?= json_encode($des_filtro_arr) ?>,
																},
																// plugins: [ChartDataLabels],
																options: {
																	tooltips: {
																		callbacks: {
																			title: function(tooltipItem, data) {
																				return ' ' + data['labels'][tooltipItem[0]['index']];
																			},
																			label: function(tooltipItem, data) {
																				var total = "<?= $total_cadastro ?>",
																					valor = data['datasets'][0]['data'][tooltipItem['index']],
																					porcentagem = (valor / total) * 100;
																				return valor + ' (' + porcentagem.toFixed(2) + '%)';
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
																	title: {
																		display: true,
																		text: "<?= $nom_indicador ?>: <?= $total_cadastro ?>"
																	},
																	animation: {
																		animateScale: true,
																		animateRotate: true
																	},
																	legend: {
																		position: 'bottom',
																	}
																}
															});

														});
													</script>

										<?php

												}
											}
										}

										?>

									</div>


								</div>


							</div>
						</div>

						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

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

<!-- <script src="js/gauge.coffee.js" type="text/javascript"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<!-- <script src="js/pie-chart.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-piechart-outlabels@0"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0"></script>  -->
<!-- <script src="js/plugins/Chart_Js/utils.js"></script>	 -->

<script>
	//datas
	$(function() {

		// Chart.plugins.unregister(ChartDataLabels);

		var numPaginas = "<?php echo @$numPaginas; ?>";
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCadApoiador.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function abreDetail(idBloco) {
		var idItem = $('.abreDetail_' + idBloco)
		if (!idItem.is(':visible')) {
			idItem.show();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>