<?php

// if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
// 	echo fnDebug('true');
// 	ini_set('display_errors', 1);
// 	ini_set('display_startup_errors', 1);
// 	error_reporting(E_ALL);
// }

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$num_cgcecpf = "";
$cod_vendapdv = "";
$cod_univend = '';
$hoje = '';

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
$cod_univend = "9999"; //todas revendas - default

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
		$num_cgcecpf = @$_POST['NUM_CGCECPF'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (is_array($cod_univend)) {
	$cod_univend = implode('', $cod_univend); // Concatena os elementos se for um array
}

if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}


//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($usuReportAdm);
//fnEscreve($mostraXml);

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

								<div class="col-md-3">
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

								<div class="col-md-3">
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
										<label for="inputName" class="control-label">CPF</label>
										<input type="text" class="form-control input-sm" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
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

									<div class="push10"></div>

									<table class="table table-bordered table-hover tablesorter">

										<thead>
											<tr>
												<th>Data</th>
												<th>Loja</th>
												<th>Cliente</th>
												<th>CPF</th>
												<th>Cód. Usuário</th>
												<th>Usuário</th>
												<th>Créditos/Pontos</th>
												<th>Comentário</th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											$pref = "";

											if ($casasDec != 0) {
												$pref = "R$";
											}

											if ($num_cgcecpf == "") {
												$andCpf = " ";
											} else {
												$andCpf = "cli.NUM_CGCECPF = $num_cgcecpf AND ";
											}

											$sql = "SELECT count(*) as contador from CREDITOSDEBITOS cred
												left join CLIENTES cli on cred.COD_CLIENTE=cli.COD_CLIENTE 
												LEFT JOIN WEBTOOLS.usuarios usu ON cred.COD_USUCADA=usu.COD_USUARIO 
												WHERE cred.TIP_CREDITO='D'
												AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
												AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
												AND cred.COD_EMPRESA=$cod_empresa
												AND CRED.COD_VENDA=0
												$andCpf
												AND cred.COD_UNIVEND IN($lojasSelecionadas)";

											// fnEscreve($sql);

											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
											$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


											$sql2 = "SELECT 
													cred.COD_CREDITO,
													cred.DAT_CADASTR,
													uni.NOM_FANTASI,
													cli.NOM_CLIENTE,
													cli.NUM_CGCECPF,
													cred.COD_USUCADA,
													usu.NOM_USUARIO,
													cred.VAL_CREDITO,
													vdi.DES_COMENTA
												FROM creditosdebitos cred  
												INNER JOIN clientes cli  on cred.COD_CLIENTE=cli.COD_CLIENTE 
												LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=cred.COD_UNIVEND
												LEFT JOIN usuarios usu ON usu.COD_USUARIO=cred.COD_USUCADA
												LEFT join venda_info vdi ON vdi.COD_VENDA=cred.COD_CREDITO
												WHERE cred.TIP_CREDITO='D'
												AND DATE(cred.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
												AND cred.COD_EMPRESA=$cod_empresa
												AND CRED.COD_VENDA=0
												$andCpf
												AND cred.COD_UNIVEND IN($lojasSelecionadas)
												ORDER BY COD_CREDITO DESC
												limit $inicio,$itens_por_pagina
												";

											//fnEscreve($sql2);	

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											?>
												<tr>
													<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
													<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													<td><small><?php echo fnMascaraCampo($qrListaVendas['NOM_CLIENTE']); ?></small></td>
													<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
													<td><small><?php echo $qrListaVendas['COD_USUCADA']; ?></small></td>
													<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
													<td><small><?= $pref ?> <?php echo fnValor($qrListaVendas['VAL_CREDITO'], $casasDec); ?></small></td>
													<td><small><?php echo $qrListaVendas['DES_COMENTA']; ?></small></td>
												</tr>
											<?php

												$countLinha++;
											}

											?>

										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
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
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

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
										url: "relatorios/ajxRelResgatesManuais.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
			url: "relatorios/ajxRelResgatesManuais.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>