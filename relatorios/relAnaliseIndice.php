<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$itens_por_pagina = 50;
$pagina = 1;
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

$meses = '';


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
		$cod_univend = $_POST['COD_UNIVEND'];
		// $dat_ini = "01/".$_REQUEST['DAT_INI'];
		// $dat_fim = $_REQUEST['DAT_FIM'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);

		// $array_dat_fim  = explode("/", $dat_fim);

		// $dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1])."/".$dat_fim;

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

?>
			<script>
				$(function() {
					$("#relAnalise").fadeIn("fast");
				});
			</script>
<?php

		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_campanha = fnDecode(@$_GET['idc']);
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

include "unidadesAutorizadas.php";

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

	.activeRel {
		text-decoration: underline !important;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

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

						<div class="push20"></div>

						<div>
							<div class="row" id="relAnalise">

								<div class="push50"></div>

								<div class="col-md-3 linksNav">

									<h3 style="margin-top:0;">Filtros</h3>

									<div class="push10"></div>

									<a class="activeRel" href="javascript:void(0)" onclick="geraRelAnalise('ticketMedio',this)">&rsaquo; Ticket Médio </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('quantidadeVendas',this)">&rsaquo; Quantidade de vendas </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('clientesCompras',this)">&rsaquo; Clientes únicos que efetuaram compras </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('totalVendas',this)">&rsaquo; Valor total em vendas </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('totalCreditosExpirados',this)">&rsaquo; Valor total de créditos expirados </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('saldoCreditos',this)">&rsaquo; Saldo de créditos </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('qtdItensAtendimento',this)">&rsaquo; Quantidade de itens por atendimento </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('totalCreditosResgatados',this)">&rsaquo; Valor total de créditos resgatados </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('clientesCadastrados',this)">&rsaquo; Clientes cadastrados </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('clientesPrimeiraCompra',this)">&rsaquo; Clientes primeira compra </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('clientesUltimaCompra',this)">&rsaquo; Clientes última compra </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('quantidadeResgates',this)">&rsaquo; Quantidade de resgates </a>
									<div class="push5"></div>

									<a href="javascript:void(0)" onclick="geraRelAnalise('clientesResgates',this)">&rsaquo; Clientes que efetuaram resgates </a>
									<div class="push5"></div>

								</div>

								<div class="col-md-9">

									<div class="no-more-tables" id="relatorioConteudo">

										<table class="table table-bordered table-striped table-hover tablesorter">
											<thead>
												<tr>
													<th><small>Loja</small></th>
													<th class="text-center"><small>Valor TM</small></th>
													<?php

													for ($i = 0; $i < $meses; $i++) {
													?>
														<th class="text-center"><small><?= date('m/Y', strtotime($dat_ini . "+ " . $i . "months")) ?></small></th>
													<?php
													}

													?>
												</tr>
											</thead>
											<tbody>

												<?php

												// Filtro por Grupo de Lojas
												include "filtroGrupoLojas.php";

												$sql = "CALL SP_RELAT_ANALISE_INDICE ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas' , $cod_empresa, 'ticketMedio' )";
												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												$countMeses = 0;
												$loja = "";
												$totValor = 0;

												while ($qrAnalise = mysqli_fetch_assoc($arrayQuery)) {
													$count++;
													$countMeses++;


													if ($loja != $qrAnalise['LOJA']) {

														echo "
														<tr>
														  <td><small>" . $qrAnalise['LOJA'] . "</small></td>
														  <td class='text-right'><small>R$ " . fnValor($qrAnalise['VALOR'], 2) . "</small></td>
														";
														$loja = $qrAnalise['LOJA'];
													} else {

														echo "<td class='text-right'><small>R$ " . fnValor($qrAnalise['VALOR'], 2) . "</small>.</td>";
													}

													$totValor += $qrAnalise['VALOR'];

													if ($countMeses == $meses) {
														echo "</tr>";
														$countMeses = 0;
													}
												}

												?>


											</tbody>

											<tfoot>
												<tr>
													<th></th>
													<th class='text-right'><b>R$ <?= fnValor($totValor, 2) ?></b></th>
												</tr>
												<script type="text/javascript">
													$(function() {
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
																						url: "relatorios/ajxRelAnaliseIndice.do?acao=exportar&opcao=ticketMedio&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
											</tfoot>

										</table>

									</div>

								</div>

							</div>

						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push50"></div>

						<div class="push"></div>

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

<script>
	//datas
	$(function() {

		$("#COD_UNIVEND,#COD_GRUPOTR,#COD_TIPOREG").change(function() {
			$("#relAnalise").fadeOut("fast");
		});

		$.tablesorter.addParser({
			id: "moeda",
			is: function(s) {
				return true;
			},
			format: function(s) {
				return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g), ""));
			},
			type: "numeric"
		});

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
										url: "relatorios/ajxRelCupons.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelCupons.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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

	function geraRelAnalise(tipoRel, link) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelAnaliseIndice.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=" + tipoRel,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				$('.linksNav a').removeClass('activeRel');
				$(link).addClass('activeRel');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").tablesorter();
				$(".tablesorter").trigger("updateAll");
				// console.log(data);										
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