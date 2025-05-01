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
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$andNome = "";
$andCpf = "";
$lojasSelecionadas = "";
$qrMov = "";
$content = "";


$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;


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

//busca revendas do usuário
include "unidadesAutorizadas.php";

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
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>

									<?php

									if ($nom_cliente != '') {
										$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
									} else {
										$andNome = "";
									}

									if ($num_cgcecpf != '') {
										$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
									} else {
										$andCpf = "";
									}
									?>

									<table class="table table-bordered table-hover">

										<thead>
											<tr>
												<th></th>
												<th>Loja</th>
												<th>Tot. Cli.</th>
												<th>Cli. Loja</th>
												<th>Cli. Outras Ljs.</th>
												<th>% Cli. Outras Ljs.</th>
												<th>Fat. Outras Ljs.</th>
												<th>Tot. Cli. Emigração</th>
												<th>Fat. Emigração</th>
												<th>Balanço Final</th>
											</tr>
										</thead>

										<!-- <tbody id="relatorioConteudo"> -->

										<?php

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "CALL SP_RELAT_MOVIMENTACAO_CLIENTE ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas', $cod_empresa)";
										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										@$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										while (@$qrMov = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											echo "
											<thead>
												<tr id='bloco_" . $qrMov['COD_UNIVEND'] . "'>
												  <th style='font-weight:400;' width='3%' class='text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrMov['COD_UNIVEND'] . ")' style='padding:10px;'><i class='fa fa-angle-right' aria-hidden='true'></i></a></th>
												  <th style='font-weight:400;'>" . $qrMov['LOJA'] . "</th>
												  <th style='font-weight:400;' class='text-center'>" . $qrMov['TOTAL_CLIENTES'] . "</th>
												  <th style='font-weight:400;' class='text-center'>" . fnValor($qrMov['CLIENTES_LOJA'], 0) . "</th>
												  <th style='font-weight:400;' class='text-center'>" . fnValor($qrMov['CLIENTES_OUTRAS_LOJAS'], 0) . "</th>
												  <th style='font-weight:400;' class='text-center'>" . fnValor($qrMov['PERC_CLI_OUTRAS_LOJAS'], 2) . "%</th>
												  <th style='font-weight:400;' class='text-right'>R$ " . fnValor($qrMov['FATURAMENTO_OTR_LOJAS'], 2) . "</th>
												  <th style='font-weight:400;' class='text-center'>" . fnValor($qrMov['TOTAL_CLIENTES_EMIGRACAO'], 0) . "</th>
												  <th style='font-weight:400;' class='text-right'>R$ " . fnValor($qrMov['FATURAMENTO_EMIGRACAO'], 2) . "</th>
												  <th style='font-weight:400;' class='text-right'>R$ " . fnValor($qrMov['BALANCO_FINAL'], 2) . "</th>
												</tr>
											</thead>
											<tr style='background-color: #fff; display: none;' class='abreDetail_" . $qrMov['COD_UNIVEND'] . "'>
											  <td></td>
											  <td colspan='20'>
											  <a class='btn btn-info btn-sm exportarCSV' tipo='imi_loja' pk='" . fnEncode($qrMov['COD_UNIVEND']) . "'> <i class='fa fa-file-excel' aria-hidden='true'></i>&nbsp; Imigração Lojas </a> &nbsp;
											  <a class='btn btn-info btn-sm exportarCSV' tipo='imi_cliente' pk='" . fnEncode($qrMov['COD_UNIVEND']) . "'> <i class='fa fa-file-excel' aria-hidden='true'></i>&nbsp; Imigração Clientes </a> &nbsp;
											  <a class='btn btn-default btn-sm exportarCSV' tipo='emi_loja' pk='" . fnEncode($qrMov['COD_UNIVEND']) . "'> <i class='fa fa-file-excel' aria-hidden='true'></i>&nbsp; Emigração Lojas</a> &nbsp;
											  <a class='btn btn-default btn-sm exportarCSV' tipo='emi_cliente' pk='" . fnEncode($qrMov['COD_UNIVEND']) . "'> <i class='fa fa-file-excel' aria-hidden='true'></i>&nbsp; Emigração Clientes</a>
											  </td>
											</tr>
												";
										}

										?>
										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV" tipo='geral' pk='<?= fnEncode(0) ?>'> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
			tipo = $(this).attr('tipo');
			pk = $(this).attr('pk');
			// alert(tipo+' - '+pk);
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
										url: "relatorios/ajxRelMovimentaCli.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&idU=" + pk + "&opcao=" + tipo,
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