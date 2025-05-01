<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_usucada = "";
$hoje = "";
$dias30 = "";
$qtd_produto = 0;
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$num_cgcecpf = "";
$cod_categor = "";
$cod_subcate = "";
$log_agrupa = "";
$checked = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$qrListaCategoria = "";
$andCpf = "";
$andCat = "";
$andSub = "";
$orderBy = "";
$lojasSelecionadas = "";
$retorno = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$custo = "";
$content = "";


//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));
$qtd_produto = 10;

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
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$cod_categor = @$_REQUEST['COD_CATEGOR'];
		$cod_subcate = @$_REQUEST['COD_SUBCATE'];
		if (empty(@$_REQUEST['LOG_AGRUPA'])) {
			$log_agrupa = 'N';
			$checked = "";
		} else {
			$log_agrupa = @$_REQUEST['LOG_AGRUPA'];
			$checked = "checked";
		}

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

//fnMostraForm();
//fnEscreve($num_cgcecpf);

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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo do Produto</label>
										<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
											<option value="">&nbsp;</option>
											<?php
											$sql = "select * from CAT_PROMOCAO where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
												";
											}
											?>
										</select>
										<script>
											$("#COD_CATEGOR").val("<?php echo $cod_categor; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Sub Grupo do Produto</label>
										<div id="divId_sub">
											<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
												<option value="0">&nbsp;</option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF/CNPJ</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18">
										<div class="help-block with-errors"></div>
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
									<div class="form-group">
										<label for="inputName" class="control-label">Agrupar por Unidade</label><br />
										<label class="switch">
											<input type="checkbox" name="LOG_AGRUPA" id="LOG_AGRUPA" class="switch" value="S" <?= $checked ?> />
											<span></span>
										</label>
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

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Cliente</small></th>
											<th><small>Unidade</small></th>
											<th><small>Produto</small></th>
											<th class="text-center"><small>Cód. Produto</small></th>
											<th class="text-center"><small>Data Resgate</small></th>
											<th class="text-center"><small>Quantidade</small></th>
											<th class="text-center"><small>Valor Unitario</small></th>
											<th class="text-center"><small>Valor Total</small></th>
											<th class="text-center"><small>Custo Total</small></th>
											<th class="text-center"><small>Usuário</small></th>
											<th class="text-center"><small>Comentário</small></th>
											<th class="text-center"><small>Estornar Resgate</small></th>

										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
											$andCpf = "AND B.NUM_CGCECPF = '$num_cgcecpf' ";
										} else {
											$andCpf = "";
										}

										if ($cod_categor != '' && $cod_categor != 0) {
											$andCat = "AND C.COD_CATEGOR = $cod_categor ";
										} else {
											$andCat = "";
										}

										if ($cod_subcate != '' && $cod_subcate != 0) {
											$andSub = "AND C.COD_SUBCATE = $cod_subcate ";
										} else {
											$andSub = "";
										}

										if ($log_agrupa == 'S') {
											$orderBy = "ORDER  BY A.COD_UNIVEND, A.DAT_REPROCE DESC";
										} else {
											$orderBy = "ORDER  BY A.DAT_REPROCE DESC";
										}


										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT COUNT(*) as CONTADOR from
														CREDITOSDEBITOS A
													   INNER JOIN CLIENTES B 
															   ON A.COD_CLIENTE = B.COD_CLIENTE 
													   INNER JOIN PRODUTOPROMOCAO C 
															   ON A.COD_PRODUTO = C.COD_PRODUTO 
                                                        where
                                                                   A.COD_EMPRESA='$cod_empresa' AND
                                                                   A.TIP_CREDITO='D' AND
                                                                   A.COD_PRODUTO > 0 AND
                                                                   A.COD_UNIVEND in ($lojasSelecionadas) and
                                                                   A.DAT_REPROCE BETWEEN   '" . fnDataSql($dat_ini) . " 00:00:00' AND '" . fnDataSql($dat_fim) . " 23:59:59'
                                                                   $andCpf
															       $andCat
															       $andSub";

										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT 	B.NOM_CLIENTE,
														B.COD_CLIENTE,
														A.COD_CREDITO, 
														A.COD_UNIVEND,
														UNI.NOM_FANTASI,
														US.NOM_USUARIO, 
														A.DAT_REPROCE, 
														A.COD_PRODUTO, 
														A.COD_STATUSCRED, 
														A.COD_USUCADA, 
														C.DES_PRODUTO, 
														C.VAL_PRODUTO, 
														A.QTD_PRODUTO, 
														A.VAL_UNITARIO, 
														A.VAL_TOTPROD,
														VENINFO.DES_COMENTA 
												FROM   CREDITOSDEBITOS A 
														INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE 
														INNER JOIN PRODUTOPROMOCAO C ON A.COD_PRODUTO = C.COD_PRODUTO
														LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND 
														LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
														LEFT JOIN venda_info VENINFO ON VENINFO.COD_VENDA=A.COD_CREDITO AND VENINFO.DES_TIPO=3
												WHERE  A.COD_EMPRESA = $cod_empresa 
														AND A.TIP_CREDITO = 'D' 
														AND A.COD_PRODUTO > 0 
														AND A.COD_UNIVEND IN ( $lojasSelecionadas ) 
														AND A.DAT_REPROCE BETWEEN   '" . fnDataSql($dat_ini) . " 00:00:00' AND '" . fnDataSql($dat_fim) . " 23:59:59'
														$andCpf
														$andCat
														$andSub
														$orderBy 
												LIMIT  $inicio, $itens_por_pagina";

										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											$custo = $qrListaVendas['QTD_PRODUTO'] * $qrListaVendas['VAL_PRODUTO'];

										?>
											<tr id="<?= $qrListaVendas['COD_CREDITO'] ?>">
												<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
												<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
												<td><small><?php echo $qrListaVendas['DES_PRODUTO']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></td>
												<td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_REPROCE']); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'], 0); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_UNITARIO'], 0); ?></small></td>
												<td class="text-center"><small> <?php echo fnValor($qrListaVendas['VAL_TOTPROD'], 0); ?></small></td>
												<td class="text-center"><small> <?php echo fnValor($custo, 0); ?></small></td>
												<td class="text-center"><small><?= $qrListaVendas['NOM_USUARIO']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['DES_COMENTA']; ?></small></td>

												<?php
												if ($qrListaVendas['COD_STATUSCRED'] == 6) {
												?>

													<td class="text-center"><span class="fas fa-check" style="color: #18BC9C;"></td>
											</tr>

										<?php
												} else {
										?>

											<td class="text-center"><a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="estornaResgate(<?= $qrListaVendas['COD_CREDITO'] ?>,<?= $cod_usucada ?>,<?= $cod_empresa ?>)"><span class="fas fa-trash"></a></td>
											</tr>
									<?php
												}

												$countLinha++;
											}
											//fnEscreve($countLinha-1);				
									?>

									<script>
										function estornaResgate(cod_credito, cod_usucada) {
											$.confirm({
												title: 'Atenção!',
												animation: 'opacity',
												closeAnimation: 'opacity',
												content: 'Deseja realmente efetuar o estorno?',
												buttons: {

													confirmar: function() {
														$.ajax({
															type: "POST",
															url: "relatorios/ajxEstornaResgate.php",
															data: {
																COD_CREDITO: cod_credito,
																COD_USUCADA: cod_usucada,
																COD_EMPRESA: <?= $cod_empresa ?>
															},
															// beforeSend:function(){
															// 	$('#'+cod_credito).html('<div class="loading" style="width: 100%;"></div>');
															// },
															success: function(data) {
																// $("#"+cod_credito).html(data); 
																$("#" + cod_credito).css('background', '#FCF3CF');
																//console.log(data); 
															},
															error: function(data) {
																$('#' + cod_credito).html(data);
																console.log(data);
															}
														});

													},
													cancelar: function() {


													},
												}
											});
										}
									</script>

									</tbody>

									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
											</th>
										</tr>
									</tfoot>

									<tfoot>
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
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
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
								icon: 'fa fa-check-square',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxPremiosResgatados.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function buscaSubCat(idCat, idSub, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaSubGrupoPromocao.php",
			data: {
				ajx1: idCat,
				ajx2: idSub,
				ajx3: idEmp
			},
			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
				//console.log(data); 
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxPremiosResgatados.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				$("#relatorioConteudo").html(data);
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