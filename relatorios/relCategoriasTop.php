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
$qtd_categor = 0;
$cod_persona = "";
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
$cod_cliente = "";
$formBack = "";
$qrListaPersonas = "";
$lojasSelecionadas = "";
$countLinha = "";
$qrListaVendas = "";
$unitarioMedio = "";
$totalCli = 0;
$totalVendas = 0;
$content = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$qtd_categor = 10;
$cod_persona = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_persona = fnLimpaCampoZero(@$_REQUEST['COD_PERSONA']);
		$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
		$qtd_categor = fnLimpaCampoZero(@$_REQUEST['QTD_CATEGOR']);

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

// if(isset(@$_GET['idP'])){
// 	$cod_persona = fnLimpaCampoZero(fnDecode(@$_GET['idP']));
// }else{
// 	$cod_persona = 0;
// }

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

// fnEscreve($dat_ini);
// fnEscreve($dat_fim);

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();
//fnEscreve($cod_cliente);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

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
										<label for="inputName" class="control-label">Lista de Categorias</label>
										<select data-placeholder="escolha a quantidade" name="QTD_CATEGOR" id="QTD_CATEGOR" class="chosen-select-deselect">
											<option value="0">&nbsp;</option>
											<option value="10">10</option>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="500">500</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
									<script>
										$("#formulario #QTD_CATEGOR").val(<?php echo $qtd_categor; ?>).trigger("chosen:updated");
									</script>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Persona</label>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA" id="COD_PERSONA" class="chosen-select-deselect" tabindex="1">
											<option value=""></option>
											<?php

											$sql = "select * from persona where cod_empresa = " . $cod_empresa . " and LOG_ATIVO = 'S' order by DES_PERSONA  ";
											@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while (@$qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																			  <option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
																			";
											}

											?>
										</select>
										<script>
											$('#COD_PERSONA').val(<?= $cod_persona ?>).trigger('chosen:updated')
										</script>
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

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th class="text-center"><small>Categoria</small></th>
											<th class="text-center"><small>Cód.</small></th>
											<th class="text-center"><small>Cód. Externo</small></th>
											<th class="text-center"><small>Qtd. Total</small></th>
											<th class="text-center"><small>% Prod.</small></th>
											<th class="text-center"><small>Clientes</small></th>
											<th class="text-center"><small>% Cli.</small></th>
											<th class="text-center"><small>Qtd. Fideliz.</small></th>
											<th class="text-center"><small>% Qtd. Fid.</small></th>
											<th class="text-center"><small>Vol. Vendas.</small></th>
											<th class="text-center"><small>% Volume</small></th>
											<th class="text-center"><small>Unit. Médio</small></th>

										</tr>
									</thead>

									<?php

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "CALL SP_RELAT_TOP_CATEGORIAS( $cod_empresa, '$dat_ini', '$dat_fim', '$lojasSelecionadas', $qtd_categor , $cod_persona	)";

									//fnEscreve($sql);
									@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									while (@$qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
										// $unitarioMedio = $qrListaVendas['QTD_FIDELIZ'] != 0 ? ($qrListaVendas['VAL_FIDELIZA'] / $qrListaVendas['QTD_FIDELIZ']) : 0;
									?>
										<tr>
											<td class="text-center"><small><?= $qrListaVendas['DES_CATEGOR'] ?></small></b></td>
											<td class="text-center"><small><?= $qrListaVendas['COD_CATEGOR'] ?></small></b></td>
											<td class="text-center"><small><?= $qrListaVendas['COD_EXTERNO'] ?></small></b></td>
											<td class="text-right"><b><small><?= fnValor($qrListaVendas['QTD_TOTAL'], 0) ?></small></b></td>
											<td class="text-right"><small><?= fnValor($qrListaVendas['PERC_PRODUTO'], 2) ?>%</small></b></td>
											<td class="text-center"><small><?= fnValor($qrListaVendas['TOTAL_CLIENTES'], 0) ?></small></td>
											<td class="text-right"><small><?= fnValor($qrListaVendas['PERC_CLIENTE'], 2) ?>%</small></td>
											<td class="text-center"><small><?= fnValor($qrListaVendas['QTD_FIDELIZADAS'], 0) ?></small></td>
											<td class="text-center"><small><?= fnValor($qrListaVendas['PERC_QTDFIDELIZADO'], 2) ?>%</small></td>
											<td class="text-right"><b><small>R$ <?= fnValor($qrListaVendas['VOLUME_VENDAS'], 2) ?></small></b></td>
											<td class="text-right"><b><small><?= fnValor($qrListaVendas['PERC_VOLUME'], 2) ?>%</small></b></td>
											<td class="text-right"><b><small>R$ <?= fnValor($qrListaVendas['UNITARIO_MEDIO'], 2) ?></small></b></td>
										</tr>
									<?php

										$totalCli = $totalCli + $qrListaVendas['NUM_CLIENTE'];
										$totalVendas = $totalVendas + $qrListaVendas['VAL_FIDELIZA'];

										$countLinha++;
									}


									//fnEscreve($countLinha-1);				
									?>
									<!-- <tr>
														  <td colspan="11"></td>
														  <td class="text-right"><small><b>R$ <?php echo fnValor($totalVendas, 2); ?></b></small></td>
														  <td colspan="2"></td>
														</tr> -->

									</tbody>
									<!-- <tfoot>
														<tr>
															<th colspan="100">
																<a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="N"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;
																<a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes </a>
															</th>
														</tr>													
													</tfoot> -->
								</table>

							</div>

						</div>
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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

	function exportarCSV(btn) {
		log_detalhes = $(btn).attr('value');
		// alert(id);
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
							icon: 'fa fa-check-square',
							content: function() {
								var self = this;
								return $.ajax({
									url: "relatorios/ajxRelProdutosTop.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>&log_detalhes=" + log_detalhes,
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
	}
</script>