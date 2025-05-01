<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$tipoVenda = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$cod_vendapdv = "";
$tip_ordenac = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$tip_retorno = "";
$casasDec = "";
$checkTodas = "";
$checkCreditos = "";
$lojasSelecionadas = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$andDataRetro = "";
$retorno = "";
$content = "";
$condicaoCartao = "";
$condicaoVendaPDV = "";
$andNome = '';
$andCreditos = '';

//fnMostraForm();
// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = 1;
$tipoVenda = "T";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
//$cod_univend = "9999"; //todas revendas - default

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
		$numCartao = @$_POST['NUM_CARTAO'];
		$nomCliente = @$_POST['NOM_CLIENTE'];
		$cod_vendapdv = @$_POST['COD_VENDAPDV'];
		$tipoVenda = @$_POST['tipoVenda'];
		$tip_ordenac = fnLimpaCampoZero(@$_POST['TIP_ORDENAC']);

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
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($tipoVenda == "T") {
	$checkTodas = "checked";
	$checkCreditos = "";
} else {
	$checkTodas = "";
	$checkCreditos = "checked";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($lojasSelecionadas);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($tipoVenda);

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

					<form data-toggle="validator" onsubmit="return validateForm()" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12">

								<div class="push20"></div>

								<?php

								if ($dat_fim == date('Y-m-d')) {
									$andDataRetro = " ";
								} else {
									$andDataRetro = "AND A.DAT_CADASTR < NOW() ";
								}

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								$sql = "SELECT
													(
														SELECT COUNT(0) QTD FROM
															(SELECT COUNT(0) FROM vendas  V
															INNER JOIN itemvenda i ON V.COD_VENDA=.i.COD_VENDA
															INNER JOIN clientes CLI ON CLI.COD_CLIENTE=V.COD_CLIENTE
															LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=V.COD_UNIVEND
															LEFT JOIN  statuscredito st ON V.COD_STATUSCRED =st.COD_STATUSCRED
															WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(i.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
															AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
															AND UNI.COD_UNIVEND IN($lojasSelecionadas)
															GROUP BY i.COD_VENDA) TB
													) QTD_VENDAS,
													(
														SELECT COUNT(0) QTD FROM vendas  V
														INNER JOIN itemvenda i ON V.COD_VENDA=.i.COD_VENDA
														LEFT JOIN produtocliente prod ON prod.COD_PRODUTO=i.COD_PRODUTO
														INNER JOIN clientes CLI ON CLI.COD_CLIENTE=V.COD_CLIENTE
														LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=V.COD_UNIVEND
														LEFT JOIN  statuscredito st ON V.COD_STATUSCRED =st.COD_STATUSCRED
														WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(i.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
														AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
														AND UNI.COD_UNIVEND IN($lojasSelecionadas)
													) QTD_ITENS,
													(
														SELECT COUNT(0) QTD FROM vendas  V
														left join formapagamento fp ON fp.COD_FORMAPA=V.COD_FORMAPA
														WHERE V.cod_empresa=$cod_empresa AND V.COD_CLIENTE > '1' AND DATE(V.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
														AND V.COD_CLIENTE IN (SELECT COD_CLIENTE FROM clientes WHERE cod_empresa=$cod_empresa AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim')
														AND V.COD_UNIVEND IN($lojasSelecionadas)
													) QTD_PGTO";

								//fnEscreve($sql);
								$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
								@$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
								$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);


								?>
							</div>
						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


						<div class="push5"></div>

					</form>

				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="row text-center">

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totalitens_por_pagina['QTD_VENDAS'], 0); ?></span></p>
								<p><b>Qtd. Vendas</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totalitens_por_pagina['QTD_ITENS'], 0); ?></span></p>
								<p><b>Qtd. Vendas Itens</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totalitens_por_pagina['QTD_PGTO'], 0); ?></span></p>
								<p><b>Qtd. Formas de Pagamento</b></p>

								<div class="push20"></div>

							</div>

							<div class="push20"></div>

							<div class="form-group text-left col-md-6 col-lg-6">
								<td class="text-left">
									<small>
										<div class="btn-group dropdown left">
											<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
												&nbsp; Exportar &nbsp;
												<span class="fas fa-caret-down"></span>
											</button>
											<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
												<li><a class="btn btn-sm exportarCSV" rel="vendas" style="text-align: left">&nbsp; Exportar Vendas</a></li>
												<li><a class="btn btn-sm exportarCSV" rel="itens" style="text-align: left">&nbsp;Exportar Vendas Itens</a></li>
												<li><a class="btn btn-sm exportarCSV" rel="pgto" style="text-align: left">&nbsp; Exportar Forma de Pagamento</a></li>
												<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
											</ul>
										</div>
									</small>
								</td>

							</div>


						</div>

					</div>
					<!-- fim Portlet -->
				</div>

			</div>

		</div>

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
			var rel = $(this).attr("rel");
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
										url: "relatorios/ajxExportaVendas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&rel=" + rel,
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
			url: "relatorios/ajxVendasGeral.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").trigger("updateAll");
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


	function validateForm() {
		var mdy = $("#DAT_INI").val().split('/');
		first = new Date(mdy[2], mdy[1] - 1, mdy[0]);
		var mdy = $("#DAT_FIM").val().split('/');
		second = new Date(mdy[2], mdy[1] - 1, mdy[0]);
		var diff = Math.round((second - first) / (1000 * 60 * 60 * 24));
		if (diff > 30) {
			alert("O período não pode ser superior a 30 dias!");
			return false;
		} else {
			return true;
		}
	}
</script>