<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$itens_por_pagina = "";
$pagina = "";
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = "";
$hoje = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$cod_empresa = "";
$cod_usuario = "";
$cod_univend = "";
$num_cgcecpf = "";
$nom_cliente = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$sql = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_univendUsu = "";
$qtd_univendUsu = "";
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$qrLista = "";
$andNome = "";
$andCpf = "";
$andVendedor = "";
$lojasSelecionadas = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrVend = "";
$content = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}


//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
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

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$cod_usuario = fnLimpaCampoZero(getInput($_POST, 'COD_USUARIO'));
		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(getInput($_GET, 'id'))))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(getInput($_GET, 'id'));
	$cod_campanha = fnDecode(getInput($_GET, 'idc'));
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cliente</label>
										<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" value="<?= $nom_cliente ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?= $num_cgcecpf ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Vendedor</label>
										<select data-placeholder="Selecione um vendedor" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>
											<?php

											$sql = "SELECT * from USUARIOS 
	                                                            WHERE COD_EMPRESA = $cod_empresa 
	                                                            AND DAT_EXCLUSA IS NULL 
	                                                            AND COD_TPUSUARIO in(7,11) 
	                                                            ORDER BY NOM_USUARIO";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
	                                                              <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
	                                                            ";
											}
											?>

										</select>
										<script>
											$("#COD_USUARIO").val("<?= $cod_usuario ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
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

									<table class="table table-bordered table-hover tablesorter">

										<thead>
											<tr>
												<th>Loja</th>
												<th>Vendedor</th>
												<th>Cliente</th>
												<th>CPF</th>
												<th>Dt. Cadastro</th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											if ($nom_cliente != "") {
												$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
											} else {
												$andNome = "";
											}

											if ($num_cgcecpf != "") {
												$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
											} else {
												$andCpf = "";
											}

											if ($cod_usuario != "" && $cod_usuario != 0) {
												$andVendedor = "AND CL.COD_ATENDENTE = $cod_usuario";
											} else {
												$andVendedor = "";
											}

											// Filtro por Grupo de Lojas
											include "filtroGrupoLojas.php";

											$sql = "SELECT US.COD_USUARIO
												FROM usuarios US
												INNER JOIN CLIENTES CL ON CL.COD_ATENDENTE = US.COD_USUARIO
												LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
												WHERE
												CL.COD_EMPRESA=$cod_empresa
												AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												AND US.COD_UNIVEND IN($lojasSelecionadas)
												$andNome
												$andCpf
												$andVendedor
												";
											//fnTestesql(connTemp($cod_empresa,''),$sql);		
											//fnEscreve($sql);

											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$totalitens_por_pagina = mysqli_num_rows($retorno);

											$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											// Filtro por Grupo de Lojas
											include "filtroGrupoLojas.php";

											$sql = "SELECT US.COD_USUARIO, US.NOM_USUARIO,	CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.NUM_CGCECPF, CL.DAT_CADASTR, UN.NOM_FANTASI
												FROM usuarios US
												INNER JOIN CLIENTES CL ON CL.COD_ATENDENTE = US.COD_USUARIO 
												LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = CL.COD_UNIVEND
												WHERE
												CL.COD_EMPRESA=$cod_empresa
												AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
												AND US.COD_UNIVEND IN($lojasSelecionadas)
												$andNome
												$andCpf
												$andVendedor
												ORDER BY CL.DAT_CADASTR DESC
												LIMIT $inicio,$itens_por_pagina
												
												";

											// fnEscreve($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);											
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrVend = mysqli_fetch_assoc($arrayQuery)) {

												$count++;
												echo "
												<tr>
												  <td>" . $qrVend['NOM_FANTASI'] . "</td>
												  <td>" . $qrVend['NOM_USUARIO'] . "</td>
												  <td><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrVend['COD_CLIENTE']) . "' class='f14' target='_blank'>" . fnMascaraCampo($qrVend['NOM_CLIENTE']) . "</a></td>
												  <td>" . fnMascaraCampo($qrVend['NUM_CGCECPF']) . "</td>
												  <td>" . fnDatasHORT($qrVend['DAT_CADASTR']) . "</td>
												</tr>
												";
											}

											?>
										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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

		var numPaginas = <?php echo $numPaginas; ?>;
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
										url: "relatorios/ajxCadCliVendedor.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
			url: "relatorios/ajxCadCliVendedor.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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