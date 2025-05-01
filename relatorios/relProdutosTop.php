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
$qtd_produto = 0;
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
$totalUnit = "";
$qrListaVendas = "";
$totalProd = "";
$totalProdFid = "";
$totalVendas = "";
$totalVendasFid = "";
$totalQtdVendas = "";
$qtd_fidelizado_total = 0;
$val_fideliza_total = "";
$content = "";



$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$qtd_produto = 10;
$cod_persona = 0;

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
		$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);
		$cod_persona = fnLimpaCampoZero(@$_POST['COD_PERSONA']);

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
										<label for="inputName" class="control-label">Lista de Produtos</label>
										<select data-placeholder="escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
											<option value="0">&nbsp;</option>
											<option value="10">10</option>
											<option value="50">50</option>
											<option value="100">100</option>
											<option value="200">200</option>
											<option value="500">500</option>
											<option value="1000">1000</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
									<script>
										$("#formulario #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");
									</script>
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

								<div class="col-sm-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Persona</label>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA" id="COD_PERSONA" class="chosen-select-deselect requiredChk">
											<option value=""></option>
											<?php

											$sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' order by DES_PERSONA  ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
												<option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
												";
											}

											?>
										</select>

									</div>
									<script>
										$("#COD_PERSONA").val("<?= $cod_persona ?>").trigger("chosen:updated")
									</script>

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

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th>
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Produto</b></small></label>
													<input type="hidden" class="form-control input-sm" name="PRODUTO" id="PRODUTO" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Cód.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="COD" id="COD" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Cód. Ext.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="COD_EXT" id="COD_EXT" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Qtd. Produto</b></small></label>
													<input type="hidden" class="form-control input-sm" name="QTD_PRODUTOS" id="QTD_PRODUTOS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Qtd. Fidel.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="QTD_FIDEL" id="QTD_FIDEL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>% Qtd. Fidel.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="PORCENT_QTD_FIDEL" id="PORCENT_QTD_FIDEL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Tot. Vendas</b></small></label>
													<input type="hidden" class="form-control input-sm" name="TOT_VENDAS" id="TOT_VENDAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Tot. Vendas Fidel</b></small></label>
													<input type="hidden" class="form-control input-sm" name="TOT_VENDAS_FIDEL" id="TOT_VENDAS_FIDEL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>% Vendas</b></small></label>
													<input type="hidden" class="form-control input-sm" name="PORCENT_VENDAS" id="PORCENT_VENDAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>VM. Total</b></small></label>
													<input type="hidden" class="form-control input-sm" name="VM_TOTAL" id="VM_TOTAL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>VM. Fidel.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="VM_FIDEL" id="VM_FIDEL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Clientes Fidel.</b></small></label>
													<input type="hidden" class="form-control input-sm" name="CLIENTES_FIDEL" id="CLIENTES_FIDEL" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>



											<th class="text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><small><b>Qtd. Vendas</b></small></label>
													<input type="hidden" class="form-control input-sm" name="QTD_VENDAS" id="QTD_VENDAS" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="control-label text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><b>Qtd. Resgate</b></label>
													<input type="hidden" class="form-control input-sm" name="QTD_RESGATE" id="QTD_RESGATE" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
											</th>

											<th class="control-label text-center">
												<div class="form-group">
													<label for="inputName" class="control-label"><b>Tot. Resgate</b></label>
													<input type="hidden" class="form-control input-sm" name="TOT_RESGATE" id="TOT_RESGATE" maxlength="100" value="">
													<div class="help-block with-errors"></div>
												</div>
										</tr>
									</thead>

									<?php

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "CALL SP_RELAT_TOPPRODUTOS(
										" . $cod_empresa . ",
										'" . fnDataSql($dat_ini) . "',
										'" . fnDataSql($dat_fim) . "',
										'" . $lojasSelecionadas . "',
										" . $qtd_produto . ",
										'N',
										" . $cod_persona . "													
									) ";

									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									$totalUnit = 0;
									$v_id_session = 0;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
										$v_id_session = $qrListaVendas['V_ID_SESSION'];

									?>
										<tr>
											<td><small><a href='action.do?mod=<?php echo fnEncode(1046); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idP=<?php echo fnEncode($qrListaVendas['COD_PRODUTO']); ?>'><?php echo $qrListaVendas['DES_PRODUTO']; ?></a></small></td>
											<td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></b></td>
											<td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></b></td>
											<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['TOTAL_QTD_PROD'], 0); ?></small></b></td>
											<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['TOTAL_QTD_FIDE'], 0); ?></small></b></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['PERC_QTDFIDELIZADO'], 2); ?>%</small></td>
											<td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['TOT_VENDAS'], 2); ?></small></b></td>
											<td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['TOT_VENDAS_FIDEL'], 2); ?></small></b></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['PERC_VOLUME'], 2); ?>%</small></td>
											<td class="text-center"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VPM_GERAL'], 2); ?></small></td>
											<td class="text-center"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VPM_FIDE'], 2); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['TOTAL_CLIENTES_FIDEL'], 0); ?></small></td>
											<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_VENDA'], 0); ?></small></b></td>
											<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_VENDA_RESGATE'], 0); ?></small></b></td>
											<td class="text-right"><b><small>R$ </small><small> <?php echo fnValor($qrListaVendas['VAL_RESGATE'], 2); ?></small></b></td>
										</tr>
									<?php

										$totalProd += $qrListaVendas['TOTAL_QTD_PROD'];
										$totalProdFid += $qrListaVendas['TOTAL_QTD_FIDE'];
										$totalVendas += $qrListaVendas['TOT_VENDAS'];
										$totalVendasFid += $qrListaVendas['TOT_VENDAS_FIDEL'];
										$totalQtdVendas += $qrListaVendas['QTD_VENDA'];

										$qtd_fidelizado_total = $qrListaVendas['QTD_FIDELIZADO_TOTAL'];
										$val_fideliza_total = $qrListaVendas['VAL_FIDELIZA_TOTAL'];

										$countLinha++;
									}


									//fnEscreve($countLinha-1);				
									?>
									<tr>
										<td colspan="3"></td>
										<td class="text-center"><small><b><?= fnValor($totalProd, 0); ?></b></small></td>
										<td class="text-center"><small><b><?= fnValor($totalProdFid, 0); ?></b></small></td>
										<td></td>
										<td class="text-right"><small><b>R$ <?= fnValor($totalVendas, 2); ?></b></small></td>
										<td class="text-right"><small><b>R$ <?= fnValor($totalVendasFid, 2); ?></b></small></td>
										<td colspan="4"></td>
										<td class="text-center"><small><b><?= fnValor($totalQtdVendas, 0); ?></b></small></td>
									</tr>

									</tbody>

									<tfoot>

										<tr>
											<th>
												<div class="push20"></div><small>Referência</small>
											</th>
											<th colspan="2" class="text-center">
												<div class="push20"></div><small>Qtd. Total Fidelizada</small>
											</th>
											<th colspan="2" class="text-center">
												<div class="push20"></div><small>Vlr. Total Fidelizado</small>
											</th>
											<th colspan="10"></th>
										</tr>

										<tr>
											<th></th>
											<th colspan="2" class="text-center"><?php echo fnValor($qtd_fidelizado_total, 0); ?></th>
											<th colspan="2" class="text-center">R$ <?php echo fnValor($val_fideliza_total, 2); ?></th>
											<th colspan="10"></th>
										</tr>
										<td class="text-left">
											<small>
												<div class="btn-group dropdown left">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
														&nbsp; Exportar &nbsp;
														<span class="fas fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<li><a class="btn btn-sm exportarCSV" style="text-align: left" onclick="exportarCSV(this)" value="N">&nbsp; Exportar</a></li>
														<li><a class="btn btn-sm exportarCSV" style="text-align: left" onclick="exportarCSV(this)" value="S">&nbsp; Exportar Detalhes</a></li>
														<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
													</ul>
												</div>
											</small>
										</td>
									</tfoot>

								</table>


							</div>

						</div>
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="V_ID_SESSION" id="V_ID_SESSION" value="<?= $v_id_session ?>">
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