<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_campanha = "";
$dat_ini = "";
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dias30 = "";
$hoje = "";
$qrListaCamp = "";
$disabled = "";
$mostraChecadoTC = "";
$andUnivend = "";
$andUnivendCred = "";
$data_ini = "";
$data_fim = "";
$andData = "";
$andDataBkp = "";
$countLinha = "";
$qrListaVendas = "";
$qtdConcedido = 0;
$valorCredito = "";
$semRetorno = "";
$comRetorno = "";
$valResgatado = "";
$valCompras = "";
$content = "";


$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = fnLimpaArray(@$_POST['COD_UNIVEND']);
		$cod_campanha = fnLimpaArray(@$_POST['COD_CAMPANHA']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$hor_ini = fnLimpaCampo(@$_REQUEST['HOR_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$hor_fim = fnLimpaCampo(@$_REQUEST['HOR_FIM']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
	}
}


//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($hor_ini == 0 || $hor_ini == " ") {
	$hor_ini = "00:00:00";
	$hor_fim = "23:59:00";
}

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
										<label for="inputName" class="control-label required">Campanha</label>

										<select data-placeholder="Selecione uma ou mais unidades" name="COD_CAMPANHA[]" id="COD_CAMPANHA" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
											<?php
											$sql = "SELECT * FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND tip_campanha=13";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaCamp = mysqli_fetch_assoc($arrayQuery)) {

												echo "
												<option value='" . $qrListaCamp['COD_CAMPANHA'] . "'" . $disabled . ">" . ucfirst($qrListaCamp['DES_CAMPANHA']) . "</option> 
												";
											}

											?>
										</select>

										<div class="help-block with-errors"></div>

										<!--<a class="btn btn-default btn-sm" id="iAll_COD_CAMPANHA" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
											<a class="btn btn-default btn-sm" id="iNone_COD_CAMPANHA" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>-->
									</div>
								</div>

								<div class="col-md-8">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>


							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1" style="width: 150px;">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Início</label>

										<div class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm hora-obrigatoria" name="HOR_INI" id="HOR_INI" value="<?php echo $hor_ini; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<div class="form-group">
											<label for="inputName" class="control-label ">Data Final</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data hora-obrigatoria" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<div class="col-md-1" style="width: 150px;">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Final</label>

										<div class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm hora-obrigatoria" name="HOR_FIM" id="HOR_FIM" <?php if ($mostraChecadoTC == "checked") {
																																				echo "readonly value=''";
																																			} else {
																																				echo "value='" . $hor_fim . "'";
																																			} ?> />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
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
										<th class="{ sorter: false }"></th>
										<th><small>Unidade</small></th>
										<th><small>Campanha</small></th>
										<th class="text-center"><small>Crédito Concedido</small></th>
										<th class="text-center"><small>Valor Crédito</small></th>
										<th class="text-center"><small>Qtd. Sem Retorno</small></th>
										<th class='text-center {sorter: "valorBr"}'><small>Qtd. Com Retorno</small></th>
										<th class='text-right {sorter: "valorBr"}'><small>Total Resgatado</small></th>
										<th class='text-right {sorter: "valorBr"}'><small>Total Compras <small>R$</small></small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php
									if ($cod_univend != "" && $cod_univend != 9999) {
										$andUnivend = " creditosdebitos_bkp.cod_univend IN ($cod_univend) AND";
										$andUnivendCred = "creditosdebitos.cod_univend IN ($cod_univend) AND";
									} else {
										$andUnivend = " ";
										$andUnivendCred = "";
									}

									if ($dat_ini != '' && $dat_ini != 0) {
										$data_ini = $dat_ini . ' ' . $hor_ini;
										$data_fim = $dat_fim . ' ' . $hor_fim;
										$andData = "AND creditosdebitos.dat_cadastr >= " . fnDateSql($data_ini) . " AND 
            										creditosdebitos.dat_cadastr <= " . fnDateSql($data_fim);

										$andDataBkp = "AND creditosdebitos_bkp.dat_cadastr >=" . fnDateSql($data_ini) . " AND 
													   creditosdebitos_bkp.dat_cadastr <=" . fnDateSql($data_fim);
									} else {
										$andData = " ";
										$andDataBkp = " ";
									}



									$sql = "SELECT  

									NOM_FANTASI,
									DES_CAMPANHA,
									cod_univend,
									sum(qtd_credito_concedido) qtd_credito_concedido,
									SUM(valor_credito) valor_credito,
									sum(sem_retono) sem_retono,
									sum(com_retorno) com_retorno,
									sum(val_resgatado) as val_resgatado,
									sum(val_compras) val_compras


									FROM (
									SELECT 
									unidadevenda.NOM_FANTASI,
									campanha.DES_CAMPANHA,
									creditosdebitos.cod_univend,
									COUNT(*) qtd_credito_concedido,
									SUM(val_credito) valor_credito,
									sum(case when val_credito=val_saldo then 1 ELSE 0 END) as sem_retono,
									sum(case when val_credito!=val_saldo then 1 ELSE 0 END) as com_retorno,
									sum(case when val_credito!=val_saldo then val_saldo-val_credito ELSE 0 END) as val_resgatado,
									sum(case when val_credito!=val_saldo then
											(SELECT sum(val_totprodu)
											FROM historico_resgate a, vendas b
											WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND 
											      a.COD_CREDITO=creditosdebitos.cod_credito AND 
											      a.COD_UNIVEND=creditosdebitos.cod_univend
													) END) val_compras
									FROM creditosdebitos , campanha , unidadevenda
									WHERE creditosdebitos.cod_campanha IN ($cod_campanha) AND
									creditosdebitos.cod_campanha=campanha.cod_campanha  AND 
									creditosdebitos.cod_univend=unidadevenda.cod_univend AND 
									$andUnivendCred
									creditosdebitos.COD_EMPRESA=$cod_empresa
									$andData
									GROUP BY creditosdebitos.cod_univend

									UNION

									SELECT 
									unidadevenda.NOM_FANTASI,
									campanha.DES_CAMPANHA,
									creditosdebitos_bkp.cod_univend,
									COUNT(*) qtd_credito_concedido,
									SUM(val_credito) valor_credito,
									sum(case when val_credito=val_saldo then 1 ELSE  0 END) as sem_retono,
									sum(case when val_credito!=val_saldo then 1 ELSE 0 END) as com_retorno,
									sum(case when val_credito!=val_saldo then val_saldo-val_credito ELSE  0 END) as val_resgatado,
									sum(case when val_credito!=val_saldo then
											(SELECT sum(val_totprodu)
											FROM historico_resgate a, vendas_bkp b
											WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND 
											      a.COD_CREDITO=creditosdebitos_bkp.cod_credito AND 
													b.COD_UNIVEND= creditosdebitos_bkp.cod_univend ) END) val_compras

									 FROM creditosdebitos_bkp , campanha, unidadevenda
									WHERE creditosdebitos_bkp.cod_campanha IN ($cod_campanha) AND
									creditosdebitos_bkp.cod_campanha=campanha.cod_campanha  AND 
									creditosdebitos_bkp.cod_univend=unidadevenda.cod_univend AND 
									$andUnivend 
									creditosdebitos_bkp.COD_EMPRESA=$cod_empresa
									$andDataBkp

									)credt
									WHERE credt.NOM_FANTASI IS NOT null
									GROUP BY NOM_FANTASI";

									//fnEscreve($sql);

									$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									while (@$qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									?>
										<tr id='UNIVEND_<?php echo $qrListaVendas['cod_univend']; ?>'>
											<td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(<?php echo $qrListaVendas['cod_univend']; ?>)'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
											<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
											<td><small><?php echo $qrListaVendas['DES_CAMPANHA']; ?></small></td>
											<td class="text-center"><b><small><?php echo $qrListaVendas['qtd_credito_concedido']; ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['valor_credito'], 2); ?></small></b></td>
											<td class="text-right"><b><small><?php echo $qrListaVendas['sem_retono']; ?></small></b></td>
											<td class="text-right"><b><small><?php echo $qrListaVendas['com_retorno']; ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_resgatado'], 2); ?></small></b></td>
											<td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_compras'], 2); ?></small></b></td>
										</tr>

										<thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_<?php echo $qrListaVendas['cod_univend']; ?>'>
										</thead>

									<?php

										$qtdConcedido += intval($qrListaVendas['qtd_credito_concedido']);
										$valorCredito += floatval($qrListaVendas['valor_credito']);
										$semRetorno += intval($qrListaVendas['sem_retono']);
										$comRetorno += intval($qrListaVendas['com_retorno']);
										$valResgatado += floatval($qrListaVendas['val_resgatado']);
										$valCompras += floatval($qrListaVendas['val_compras']);

										$countLinha++;
									}
									?>
									<tr>
										<td><b>Total</b></td>
										<td></td>
										<td></td>
										<td class="text-center"><b><?php echo $qtdConcedido; ?></b></td>
										<td class="text-right"><b><?php echo fnValor($valorCredito, 2); ?></b></td>
										<td class="text-right"><b><?php echo $semRetorno; ?></b></td>
										<td class="text-right"><b><?php echo $comRetorno; ?></b></td>
										<td class="text-right"><b><?php echo fnValor($valResgatado, 2); ?></b></td>
										<td class="text-right"><b><?php echo fnValor($valCompras, 2); ?></b></td>
									</tr>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />


<script>
	$(document).ready(function() {
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
		}).on('dp.change', function(e) {
			$("#DAT_INI").trigger("change");
		});

		$('.clockPicker').datetimepicker({
			format: 'LT',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});
	});

	$("#DAT_INI").change(function() {

		console.log("Evento DAT_INI change disparado!");
		if ($(this).val() !== "") {
			console.log("Data inicial preenchida. Tornando campos de hora obrigatórios.");
			$(".hora-obrigatoria").attr("required", true);
		} else {
			console.log("Data inicial vazia. Tornando campos de hora opcionais.");
			$(".hora-obrigatoria").prop("required", false);
		}
	});

	function abreDetail(idUnidade) {
		RefreshCampanha(<?= $cod_empresa; ?>, idUnidade);
	}

	function RefreshCampanha(idEmp, idUnidade) {
		var idItem = $('#abreDetail_' + idUnidade);
		console.log(idItem);

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelCampanhaDetalhado.do?idu=" + idUnidade + "&id=<?php echo fnEncode($cod_empresa); ?>",
				data: $("#formulario").serialize(),
				beforeSend: function() {
					$("#abreDetail_" + idUnidade).html('<div class="loading" style="width: 100%;"></div>');

				},
				success: function(data) {
					$("#abreDetail_" + idUnidade).html(data);
					//console.log(data);
				},
				error: function(data) {
					$("#abreDetail_" + idUnidade).html(data);
				}
			});

			idItem.show();

			$('#CAMPANHA_' + idUnidade).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#CAMPANHA_' + idUnidade).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}

	$(function() {

		$(document).ready(function() {
			var selectedValues = localStorage.getItem('selectedValues');

			if (selectedValues) {
				selectedValues = selectedValues.split(',');

				$('#COD_CAMPANHA').val(selectedValues);

				$('#COD_CAMPANHA').trigger('chosen:updated');
			}

			$('#COD_CAMPANHA').on('change', function() {
				var selectedValues = $(this).val();

				localStorage.setItem('selectedValues', selectedValues);
			});

			$('#iAll_COD_CAMPANHA').on('click', function() {
				$('#COD_CAMPANHA').find('option').prop('selected', true);
				$('#COD_CAMPANHA').trigger('chosen:updated');
				localStorage.setItem('selectedValues', $('#COD_CAMPANHA').val());
			});

			$('#iNone_COD_CAMPANHA').on('click', function() {
				$('#COD_CAMPANHA').find('option').prop('selected', false);
				$('#COD_CAMPANHA').trigger('chosen:updated');
				localStorage.setItem('selectedValues', $('#COD_CAMPANHA').val());
			});
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
										url: "relatorios/ajxRelCampanhaDetalhado.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function(data) {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										console.log(data);
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
			url: "relatorios/ajxRelPerformanceVendedor.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				//console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>