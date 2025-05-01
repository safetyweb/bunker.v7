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
$cod_empresa_combo = "";
$dat_ini = "";
$dat_fim = "";
$log_opttrue = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$disableEmpresa = "";
$checkOpttrue = "";
$formBack = "";
$qrLista = "";
$qrEmpresa = "";
$andEmpresa = "";
$andOptout = "";
$retorno = "";
$inicio = "";
$qrOptOut = "";
$sqlCli = "";
$qrCli = "";
$checkOptOut = "";
$lojasSelecionadas = "";
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

		$cod_empresa_combo = fnLimpaCampoZero(@$_POST['COD_EMPRESA_COMBO']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		if (empty(@$_REQUEST['LOG_OPTTRUE'])) {
			$log_opttrue = 'N';
		} else {
			$log_opttrue = @$_REQUEST['LOG_OPTTRUE'];
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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$disableEmpresa = 1;
	}
} else {
	$cod_empresa = $cod_empresa_combo;
	$disableEmpresa = 0;
	//fnEscreve('entrou else');
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


if ($log_opttrue == 'S') {
	$checkOpttrue = 'checked';
} else {
	$checkOpttrue = "";
}

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
										<label for="inputName" class="control-label">Empresa</label>
										<?php if ($disableEmpresa == 0) { ?>
											<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA_COMBO" id="COD_EMPRESA_COMBO" class="chosen-select-deselect" style="width:100%;">
												<option value=""></option>
												<?php
												$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																FROM empresas  
																WHERE empresas.COD_EMPRESA <> 1
																AND LOG_ATIVO = 'S'
																ORDER by NOM_FANTASI
														";

												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																  <option value='" . $qrLista['COD_EMPRESA'] . "'>" . $qrLista['NOM_FANTASI'] . "</option> 
																";
												}
												?>
											</select>
										<?php } else {
											$sql = "SELECT COD_EMPRESA, NOM_FANTASI from EMPRESAS
															WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											$qrEmpresa = mysqli_fetch_assoc($arrayQuery);
										?>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FANTASI" id="NOM_FANTASI" value="<?php echo $qrEmpresa['NOM_FANTASI']; ?>">
										<?php } ?>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">
											$("#COD_EMPRESA_COMBO").val("<?= $cod_empresa ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Somente Optout</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_OPTTRUE" id="LOG_OPTTRUE" class="switch" value="S" <?= $checkOpttrue ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
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

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Cod.</small></th>
											<th><small>Mensagem</small></th>
											<th class="text-center"><small>Celular</small></th>
											<th class="text-center"><small>Dt. Cadastro</small></th>
											<th><small>Empresa</small></th>
											<th><small>Usuário Alteração</small></th>
											<th class="text-center"><small>Dt. Alteração</small></th>
											<th class="{sorter:false} text-center"><small>OptOut</small></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($cod_empresa != '' && $cod_empresa != 0) {
											$andEmpresa = "AND LO.COD_EMPRESA = $cod_empresa ";
										} else {
											$andEmpresa = "";
										}

										if ($log_opttrue == 'S') {
											$andOptout = "AND LO.DES_OPOUT = 1 ";
										} else {
											$andOptout = "";
										}

										$sql = "SELECT LO.ID FROM LISTA_OPTOUT LO
											INNER JOIN EMPRESAS EM ON EM.COD_EMPRESA = LO.COD_EMPRESA
											WHERE LO.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											$andEmpresa
											$andOptout";

										//fnEscreve($sql);

										$retorno = mysqli_query($connAdm->connAdm(), $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT LO.*, EM.NOM_FANTASI, US.NOM_USUARIO FROM LISTA_OPTOUT LO
												INNER JOIN EMPRESAS EM ON EM.COD_EMPRESA = LO.COD_EMPRESA
												LEFT JOIN USUARIOS US ON US.COD_USUARIO = LO.COD_USUCADA
												WHERE LO.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												$andEmpresa
												$andOptout
												ORDER BY DAT_CADASTR DESC
												LIMIT $inicio,$itens_por_pagina";

										// fnEscreve($sql);
										// fnTestesql(connTemp($cod_empresa,''),$sql);

										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;

										while ($qrOptOut = mysqli_fetch_assoc($arrayQuery)) {

											$sqlCli = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $qrOptOut[COD_EMPRESA] AND COD_CLIENTE = $qrOptOut[COD_CLIENTE]";
											$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($qrOptOut['COD_EMPRESA'], ''), $sqlCli));
											$count++;

											if ($qrOptOut['DES_OPOUT'] != 0) {
												$checkOptOut  = "checked";
											} else {
												$checkOptOut = "";
											}

										?>
											<tr>
												<td><small><?= $qrOptOut['ID'] ?></small></td>
												<td><small><?= $qrOptOut['MSG'] ?></small></td>
												<td class="text-right sp_celphones">
													<a href="#" class="editable"
														data-type='text'
														data-title='Editar celular' data-pk="<?= $qrOptOut['COD_CLIENTE'] ?>"
														data-name="NUM_CELULAR"
														data-tipo="edit"
														data-codempresa="<?= $cod_empresa ?>"><?= $qrCli['NUM_CELULAR'] ?>

													</a>
												</td>
												<td class="text-center"><small><?= fnDataFull($qrOptOut['DAT_CADASTR']) ?></small></td>
												<td><small><?= $qrOptOut['NOM_FANTASI'] ?></small></td>
												<td><small><?= $qrOptOut['NOM_USUARIO'] ?></small></td>
												<td class="text-center"><small><?= fnDataFull($qrOptOut['DAT_ALTERAC']) ?></small></td>
												<td class="text-center">
													<label class="switch">
														<input type="checkbox" class="switch" onchange='toggleOptOut("<?= fnEncode($qrOptOut['COD_EMPRESA']) ?>","<?= fnEncode($qrOptOut['COD_CLIENTE']) ?>","<?= fnEncode($qrOptOut['DES_OPOUT']) ?>")' <?= $checkOptOut ?>>
														<span style="height: 25px;"></span>
													</label>
												</td>
											</tr>


										<?php
										}
										?>
									</tbody>

									<tfoot>
										<!-- <tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											</th>
										</tr> -->
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

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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
	var current_page = 1;

	//datas
	$(function() {

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones .editable-input .input-sm, .sp_celphones a').mask(SPMaskBehavior, spOptions);

		// $('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('.editable').editable({
			emptytext: '(__) _____-____',
			url: './relatorios/ajxRelOptOutSms.php',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				params.codempresa = $(this).data('codempresa');
				params.tipo = $(this).data('tipo');
				return params;
			},
			success: function(data) {
				reloadPage(current_page);
			}
		});

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		jQuery('#paginacao').on('page', function(event, page) {
			current_page = page;
			// console.log('current_page', current_page);
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
										url: "ajxRelAcessos.do?opcao=exportar&nomeRel=" + nome,
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
			url: "relatorios/ajxRelOptOutSms.do?idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function toggleOptOut(idEmp, idCli, log_optout) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelOptOutSms.do?idPage=" + current_page + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&id=" + idEmp + "&idc=" + idCli + "&ido=" + log_optout,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>