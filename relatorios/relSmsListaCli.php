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
$cod_campanha = "";
$dat_ini = "";
$dat_fim = "";
$num_cgcecpf = "";
$nom_cliente = "";
$num_celular = "";
$log_optout = "";
$log_retorno = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$arrayParamAutorizacao = [];
$autoriza = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$qrCamp = "";
$rqrNUM_CELULAR = "";
$lojasSelecionadas = "";
$andCpf = "";
$andCelular = "";
$andCampanha = "";
$andOpt = "";
$andData = "";
$andRetorno = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrRetorno = "";
$recebido = "";
$confirmacao = "";
$bounce = "";
$optout = "";
$colCliente = "";
$content = "";



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
		$cod_campanha = fnLimpaCampoZero(@$_POST['COD_CAMPANHA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);
		$num_celular = fnLimpaCampo(@$_REQUEST['NUM_CELULAR']);
		if (empty(@$_REQUEST['LOG_OPTOUT'])) {
			$log_optout = 'N';
		} else {
			$log_optout = @$_REQUEST['LOG_OPTOUT'];
		}
		if (empty(@$_REQUEST['LOG_RETORNO'])) {
			$log_retorno = 'N';
		} else {
			$log_retorno = @$_REQUEST['LOG_RETORNO'];
		}

		// fnEscreve($cod_campanha);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	// $cod_campanha = fnDecode(@$_GET['idc']);
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


//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

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

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
		cursor: wait;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
</div>

<div class="push30"></div>

<div class="row" id="div_Report">

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


				<div class="push5"></div>

				<div class="alert alert-danger alert-dismissible" role="alert" id="msgExclusa">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fal fa-history"></i>&nbsp;&nbsp; <b>ATENÇÃO:</b> Os dados a seguir são completamente <b>deletados</b> após o periodo de <b>6 meses</b> da geração dos mesmos, sugerimos que exporte e salve seus dados.
				</div>


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

										<label for="inputName" class="control-label">Campanha</label>
										<select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											$sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
																WHERE COD_EMPRESA = $cod_empresa 
																AND LOG_PROCESSA_SMS = 'S'
																AND LOG_ATIVO = 'S'";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {
											?>

												<option value="<?= $qrCamp['COD_CAMPANHA'] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#formulario #COD_CAMPANHA").val('<?= $cod_campanha ?>').trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_CELULAR ?>">Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?= $num_celular ?>" id="NUM_CELULAR" maxlength="20">
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
									<div class="form-group">
										<label for="inputName" class="control-label">Somente OptOut</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_OPTOUT" id="LOG_OPTOUT" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

						<div class="push5"></div>

					</form>

				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">

				<div class="login-form">

					<div class="row">

						<div class="col-md-12">
							<a class="btn btn-info btn-sm  pull-right" onclick="reprocessa();"><i class="fal fa-repeat" aria-hidden="true"></i>&nbsp; Reprocessar</a>
						</div>

					</div>

					<div class="row">

						<div class="col-md-12">

							<div class="push30"></div>

							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th>Cliente</th>
										<th>CPF</th>
										<th>Celular</th>
										<th>Campanha</th>
										<th>Loja</th>
										<th class="text-center">Dt. Envio</th>
										<th>Enviado</th>
										<!-- REMOVIDO DE ACORDO COM O CHAMADO 4593 25/11/2022 -->
										<!-- <th>Com confirmação</th>
											<th>Bounce</th>
											<th>OptOut</th> -->
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
										$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
									} else {
										$andCpf = "";
									}

									if ($num_celular != '') {
										$andCelular = "AND SLR.NUM_CELULAR = '" . fnLimpaDoc($num_celular) . "'";
									} else {
										$andCelular = "";
									}

									if ($cod_campanha != 0 && $cod_campanha != '') {
										$andCampanha = "AND SLR.COD_CAMPANHA = $cod_campanha";
									} else {
										$andCampanha = "";
									}

									if ($log_optout == 'S') {
										$andOpt = "AND SLR.COD_OPTOUT_ATIVO = 1";
										//$andData = "";
									} else {
										$andOpt = "";
									}

									if ($log_retorno == 'S') {
										$andRetorno = "AND SLR.DES_MOTIVO != ''";
										//$andData = "";
									} else {
										$andRetorno = "";
									}

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "SELECT SLR.COD_LISTA FROM SMS_LISTA_RET SLR
												INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
												left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
												WHERE SLR.COD_EMPRESA = $cod_empresa
												AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
												AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
												$andCampanha
												$andCpf
												$andCelular
												$andOpt
												$andRetorno";
									//fnTestesql(connTemp($cod_empresa,''),$sql);		
									//fnEscreve($sql);

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$totalitens_por_pagina = mysqli_num_rows($retorno);

									$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "SELECT  SLR.DAT_CADASTR,
														CL.COD_CLIENTE,
														SLR.NOM_CLIENTE,
														CL.NUM_CGCECPF,
														SLR.NUM_CELULAR,
														SLR.DES_MOTIVO,
														SLR.DES_STATUS,
														SLR.COD_OPTOUT_ATIVO,
														SLR.COD_CCONFIRMACAO,
														SLR.BOUNCE,
														SLR.COD_NRECEBIDO,
														SLR.DES_MSG_ENVIADA, 
														CP.DES_CAMPANHA, 
														UV.NOM_FANTASI 
												FROM SMS_LISTA_RET SLR
												INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
												LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
												left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
												WHERE SLR.COD_EMPRESA = $cod_empresa
												AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
												AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
												$andCpf
												$andCelular
												$andCampanha
												$andOpt
												$andRetorno
												ORDER BY SLR.DAT_CADASTR DESC
												LIMIT $inicio, $itens_por_pagina";

									//fnEscreve($sql);

									//fnTestesql(connTemp($cod_empresa,''),$sql);											
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrRetorno = mysqli_fetch_assoc($arrayQuery)) {

										$recebido = "<span class='fal fa-times text-danger'></span>";
										$confirmacao = "";
										$bounce = "";
										$optout = "";

										if ($qrRetorno['COD_NRECEBIDO'] == 0) {
											$recebido = "<span class='fal fa-check'></span>";
										}

										if ($qrRetorno['COD_CCONFIRMACAO'] == 1) {
											$confirmacao = "<span class='fal fa-check'></span>";
										}

										if ($qrRetorno['BOUNCE'] == 1) {
											$bounce = "<span class='fal fa-check'></span>";
										}

										if ($qrRetorno['COD_OPTOUT_ATIVO'] == 1) {
											$optout = "<span class='fal fa-check'></span>";
										}

										$count++;


										if ($autoriza == 1) {
											$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrRetorno['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</a></small></td>";
										} else {
											$colCliente = "<td><small>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</small></td>";
										}

										echo "
												<tr>
												  " . $colCliente . "
												  <!-- <td><small>" . fnMascaraCampo($qrRetorno['NUM_CGCECPF']) . "</small></td> -->
												  <td><small>" . $qrRetorno['NUM_CGCECPF'] . "</small></td>
												  <td><small class='sp_celphones'>" . $qrRetorno['NUM_CELULAR'] . "</small></td>
												  <td><small>" . $qrRetorno['DES_CAMPANHA'] . "</small></td>
												  <td><small>" . $qrRetorno['NOM_FANTASI'] . "</small></td>
												  <td class='text-center'><small>" . fnDataFull($qrRetorno['DAT_CADASTR']) . "</small></td>
												  <td class='text-center'><small>" . $recebido . "</small></td>
												</tr>
												";
									}

									?>
								</tbody>

								<tfoot>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
										</th>
									</tr>
									<tr>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
									</tr>
								</tfoot>

							</table>

						</div>


					</div>
				</div>


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

<script>
	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

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
										url: "relatorios/ajxSmsListaCli.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function reprocessa() {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxSmsListaCli.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=reprocessar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#blocker').show();
			},
			success: function(data) {
				$('#blocker').hide();
				if (data == 1) {
					$.alert({
						title: "Sucesso",
						content: "Mensagens reprocessadas.",
						type: 'green',
						backgroundDismiss: true
					});
					location.reload();
				} else {
					$.alert({
						title: "Falha",
						content: "Erro ao reprocessar.",
						type: 'orange',
						backgroundDismiss: true
					});
				}
				console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxSmsListaCli.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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
</script>