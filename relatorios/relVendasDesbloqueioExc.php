<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$tipoVenda = "";
$tipo_opcao = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$nom_cliente = "";
$cod_vendapdv = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$tip_retorno = "";
$casasDec = "";
$arrayParamAutorizacao = [];
$autoriza = "";
$andNome = "";
$condicaoVendaPDV = "";
$andData = "";
$andTipo = "";
$lojasSelecionadas = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$content = "";
$itens_por_pagina = 50;
$pagina = 1;
$tipoVenda = "T";
$tipo_opcao = "todas";
$hashLocal = mt_rand();

$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 month')));


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
		$nom_cliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
		$cod_vendapdv = fnLimpaCampo(@$_POST['COD_VENDAPDV']);
		$tipo_opcao = fnLimpaCampo(@$_POST['TIPO_OPCAO']);

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

		if ($tip_retorno == 1) {
			$casasDec = 0;
		} else {
			$casasDec = 2;
		}
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	// $dat_ini = fnDataSql($dias30); 
	$dat_ini = "";
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
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cliente</label>
										<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Venda PDV</label>
										<input type="text" class="form-control input-sm" name="COD_VENDAPDV" id="COD_VENDAPDV" value="<?php echo $cod_vendapdv; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo da Venda</label>
										<select name="TIPO_OPCAO" id="TIPO_OPCAO" class="chosen-select-deselect" style="width:100%;">
											<option value="todas">Todas</option>
											<option value="des">Desbloqueadas</option>
											<option value="exc">Excluídas</option>
										</select>
										<script>
											$("#TIPO_OPCAO").val("<?= $tipo_opcao ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
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
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>

									<table class="table table-bordered table-hover tablesorter">

										<thead>
											<tr>
												<th><small>Cliente</small></th>
												<th><small>Loja</small></th>
												<th><small>Usuário</small></th>
												<th><small>Situação</small></th>
												<th class="text-center"><small>Cod. PDV</small></th>
												<th class="text-center"><small>Tipo da Venda</small></th>
												<th class="text-center"><small>Data/Hora</small></th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											if ($nom_cliente == "") {
												$andNome = " ";
											} else {
												$andNome = "AND C.NOM_CLIENTE LIKE '%" . trim($nom_cliente) . "%' ";
											}

											if ($cod_vendapdv == "") {
												$condicaoVendaPDV = " ";
											} else {
												$condicaoVendaPDV = "AND D.COD_VENDAPDV = '" . $cod_vendapdv . "' ";
											}

											if ($dat_ini != '' && $dat_ini != 0) {
												$andData = "AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
											} else {
												$andData = "";
											}

											if ($tipo_opcao != "todas" && $tipo_opcao != "") {
												$andTipo = "AND a.TIPO_OPCAO ='$tipo_opcao'";
											} else {
												$andTipo = "";
											}

											// Filtro por Grupo de Lojas
											include "filtroGrupoLojas.php";

											$sql = "SELECT 1 
													  FROM historico_venda a, webtools.usuarios b,clientes c, vendas D
													WHERE a.cod_usucada=b.cod_usuario 
													AND a.cod_cliente=c.cod_cliente AND C.LOG_AVULSO = 'N'
													AND D.COD_VENDA=a.COD_VENDA
													AND a.cod_empresa=$cod_empresa
													AND A.TIP_CAD='S'
													AND D.COD_UNIVEND IN($lojasSelecionadas)
													$andTipo
													$andNome
													$condicaoVendaPDV
													$andData
													ORDER BY A.DAT_CADASTR DESC
											";

											//fnEscreve($sql);
											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$totalitens_por_pagina = mysqli_num_rows($retorno);
											$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											// Filtro por Grupo de Lojas
											include "filtroGrupoLojas.php";

											$sql = "SELECT c.NOM_CLIENTE, a.*,b.NOM_USUARIO, D.COD_VENDAPDV, E.NOM_FANTASI,
													(case when A.cod_venda = 0 then
														'Todas as vendas desbloqueadas deste cliente'
														ELSE
														'Somente venda selecionada'
														END) AS SITUACAO 
													  FROM historico_venda a
													INNER JOIN webtools.usuarios B ON B.COD_USUARIO = A.COD_USUCADA
													INNER JOIN clientes C ON C.cod_cliente = A.cod_cliente AND C.LOG_AVULSO = 'N'
													LEFT JOIN vendas D ON D.COD_VENDA = A.COD_VENDA
													LEFT JOIN unidadevenda e ON e.COD_UNIVEND = D.COD_UNIVEND
													WHERE a.cod_empresa = $cod_empresa
													AND A.TIP_CAD='S'
													AND D.COD_UNIVEND IN($lojasSelecionadas)
													$andTipo
													$andNome
													$condicaoVendaPDV
													$andData
													ORDER BY A.DAT_CADASTR DESC
													limit $inicio,$itens_por_pagina ";

											// fnEscreve($sql);	
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

												if (strtolower($qrListaVendas['TIPO_OPCAO']) == 'exc') {
													$tipoVenda = "Excluída";
												} else {
													$tipoVenda = "Desbloqueada";
												}

											?>
												<tr>
													<?php
													if ($autoriza == 1) {
													?>
														<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
													<?php
													} else {
													?>
														<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
													<?php
													}
													?>
													<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
													<td><small><?php echo $qrListaVendas['SITUACAO']; ?></small></td>
													<td class="text-center"><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
													<td class="text-center"><small><?php echo $tipoVenda; ?></small></td>
													<td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
												</tr>
											<?php


												$countLinha++;
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

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
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
										url: "relatorios/ajxVendasDesbloqueioExc.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
			url: "relatorios/ajxVendasDesbloqueioExc.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				// $(".tablesorter").trigger("updateAll");										
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>