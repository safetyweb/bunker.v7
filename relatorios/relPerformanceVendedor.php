<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$dias30 = "";
$dat_ini = "";
$dat_fim = "";


$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;

$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND']);
		$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO']);


		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_persona_url = fnLimpaCampoZero(@$_GET['idP']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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


?>

<div class="push30"></div>

<div class="row">


	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
				</div>

				<?php
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
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
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

					</form>
				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<div class="push20"></div>

					<div class="row">

						<div class="col-md-12" id="div_Produtos">

							<div class="push20"></div>

							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th><small>Vendedor</small></th>
										<th><small>Loja</small></th>
										<th class="text-center"><small>Qtd. Vendas</small></th>
										<th class="text-center"><small>Qtd. Total Avulsa</small></th>
										<th class="text-center"><small>Qtd. Total Fidelizado</small></th>
										<th class='text-center {sorter: "valorBr"}'><small>% Fidelizado</small></th>
										<th class='text-right {sorter: "valorBr"}'><small>Tkt. Médio <small>R$</small></small></th>
										<th class='text-right {sorter: "valorBr"}'><small>Total Vendas <small>R$</small></small></th>
										<th class='text-right {sorter: "valorBr"}'><small>Total Fidelizado <small>R$</small></small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									if ($cod_univend != "" && $cod_univend != 9999) {
										$andUnivend = "AND a.COD_UNIVEND = $cod_univend";
									} else {
										$andUnivend = " ";
									}

									$sql = "SELECT 
									b.NOM_USUARIO,
									uv.NOM_FANTASI,
									SUM(a.qtd_totvenda) AS qtd_totvenda,
									SUM(a.qtd_totavulsa) AS qtd_totavulsa,
									SUM(a.qtd_totfideliz) AS qtd_totfideliz,
									(SUM(a.qtd_totfideliz) / SUM(a.qtd_totvenda)) * 100 AS pct_fidelizado,
									(SUM(a.val_totvenda) / SUM(a.qtd_totvenda)) as tkt_medio,
									SUM(a.val_totvenda) AS val_totvenda,
									SUM(a.val_totfideliz) AS val_totfideliz
									FROM vendas_diarias a
									INNER JOIN usuarios b ON a.cod_vendedor = b.cod_usuario
									INNER JOIN unidadevenda uv ON uv.cod_univend = b.cod_univend
									WHERE a.dat_movimento BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									$andUnivend
									AND a.cod_empresa = $cod_empresa
									GROUP BY a.cod_vendedor
									ORDER BY tkt_medio DESC";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$totalitens_por_pagina = mysqli_num_rows($retorno);


									$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


									$sql = "SELECT 
									b.NOM_USUARIO,
									uv.NOM_FANTASI,
									SUM(a.qtd_totvenda) AS qtd_totvenda,
									SUM(a.qtd_totavulsa) AS qtd_totavulsa,
									SUM(a.qtd_totfideliz) AS qtd_totfideliz,
									(SUM(a.qtd_totfideliz) / SUM(a.qtd_totvenda)) * 100 AS pct_fidelizado,
									(SUM(a.val_totvenda) / SUM(a.qtd_totvenda)) as tkt_medio,
									SUM(a.val_totvenda) AS val_totvenda,
									SUM(a.val_totfideliz) AS val_totfideliz
									FROM vendas_diarias a
									INNER JOIN usuarios b ON a.cod_vendedor = b.cod_usuario
									INNER JOIN unidadevenda uv ON uv.cod_univend = b.cod_univend
									WHERE a.dat_movimento BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									$andUnivend
									AND a.cod_empresa = $cod_empresa
									GROUP BY a.cod_vendedor
									ORDER BY tkt_medio DESC
									LIMIT $inicio,$itens_por_pagina";

									// fnEscreve($sql);

									$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									?>
										<tr>
											<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
											<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
											<td class="text-center"><small><?php echo $qrListaVendas['qtd_totvenda']; ?></small></td>
											<td class="text-center"><small><?php echo $qrListaVendas['qtd_totavulsa']; ?></small></td>
											<td class="text-center"><b><small><?php echo $qrListaVendas['qtd_totfideliz']; ?></small></b></td>
											<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['pct_fidelizado'], 2); ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['tkt_medio'], 2); ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_totvenda'], 2); ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_totfideliz'], 2); ?></small></b></td>
										</tr>
									<?php

										$countLinha++;
									}

									//fnEscreve($countLinha-1);				
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

					<div class="push5"></div>



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

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('#DAT_FIM_GRP').data("DateTimePicker").maxDate(moment("<?= $dat_fim ?>"));

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			var nextMonth = e.date.add(3, 'months');
			$('#DAT_FIM_GRP').data("DateTimePicker").maxDate(nextMonth);
		});

	});


	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelPerformanceVendedor.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
									url: "relatorios/ajxRelPerformanceVendedor.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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