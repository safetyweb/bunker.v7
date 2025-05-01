<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();
$tip_prodtkt = 2;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$tip_prodtkt = fnLimpaCampoZero($_POST['TIP_PRODTKT']);
		$cod_categortkt = fnLimpaCampoZero($_POST['COD_CATEGORTKT']);
		$cod_univend = $_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI,LOG_TKTUNIVEND FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_tkunivend = $qrBuscaEmpresa['LOG_TKTUNIVEND'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$log_tkunivend = "";
}

//echo $log_tkunivend;

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
									<div class="push10"></div>
									<?php
									if($log_tkunivend == 'S'){
									?>
									<div class="help-block with-errors" style="color:red">Unidade de Referência</div>
									<?php }else{ 
									?>
									<div class="help-block with-errors" style="color:red">Unidades Autorizadas</div>
									<?php }?>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo de produto</label>
										<select data-placeholder="Selecione um tipo" name="TIP_PRODTKT" id="TIP_PRODTKT" class="chosen-select-deselect" style="width:100%;">
											<option value="1">Todos</option>
											<option value="2">Somente ativos</option>
											<option value="3">Somente inativos</option>
										</select>
										<script>
											$("#TIP_PRODTKT").val("<?= $tip_prodtkt ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Categoria</label>
										<select data-placeholder="Selecione um tipo" name="COD_CATEGORTKT" id="COD_CATEGORTKT" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>
											<?php

												$sqlCat = "SELECT COD_CATEGORTKT, DES_CATEGOR 
														   FROM CATEGORIATKT 
														   WHERE COD_EMPRESA = $cod_empresa 
														   AND COD_CATEGORTKT IN(SELECT DISTINCT COD_CATEGORTKT
														   					  FROM PRODUTOTKT
														   					  WHERE COD_EMPRESA = $cod_empresa)";
												$arrayCat = mysqli_query(connTemp($cod_empresa, ''), $sqlCat);

												while ($qrCat = mysqli_fetch_assoc($arrayCat)){
											?>
												<option value="<?=$qrCat['COD_CATEGORTKT']?>"><?=$qrCat['DES_CATEGOR']?></option>
											<?php 
												}

											?>
										</select>
										<script>
											$("#COD_CATEGORTKT").val("<?= $cod_categortkt ?>").trigger("chosen:updated");
										</script>
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
							<div class="col-md-2">
								<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
							</div>
						</div>

						<div class="row">

							<div class="col-md-12">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Ativo </small></th>
											<th><small>Cód. Ext. </small></th>
											<th><small>Produto Ticket </small></th>
											<th><small>Categoria </small></th>
											<th><small>De </small></th>
											<th><small>Por </small></th>
											<th><small>Un. Aut. </small></th>
											<th><small>Un. Não Aut.</small></th>
										</tr>
									</thead>
									<tbody>

										<?php

										if ($tip_prodtkt == 2) {
											$andProdTkt = "AND LOG_ATIVOTK = 'S'";
										} else if ($tip_prodtkt == 3) {
											$andProdTkt = "AND LOG_ATIVOTK = 'N'";
										} else {
											$andProdTkt = "";
										}

										if($log_tkunivend == "S"){
											$andUnivend = "AND PRODUTOTKT.COD_UNIVEND IN ($lojasSelecionadas)";
										}else{
                                            $lojasSelecionadas=str_replace(',', "|", $lojasSelecionadas);
											$andUnivend = "AND CONCAT(',', produtotkt.COD_UNIVEND_AUT, ',') REGEXP ',(0|$lojasSelecionadas),'";
										}

										if($cod_categortkt != 0){
											$andCategor = "AND PRODUTOTKT.COD_CATEGORTKT = $cod_categortkt";
										}else{
											$andCategor = "";
										}

										//fnEscreve($tip_prodtkt);

										$sql = " SELECT PRODUTOCLIENTE.DES_PRODUTO,
												  PRODUTOCLIENTE.COD_EXTERNO,													
											   IF( PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
											   PRODUTOTKT.*,
											   categoriatkt.*
											FROM PRODUTOTKT 
											left join categoriatkt on categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT 
											left join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO 
											WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
											$andUnivend
											$andProdTkt
											$andCategor
											AND	PRODUTOTKT.COD_EMPRESA = $cod_empresa 
											order by DES_CATEGOR, NOM_PRODTKT ";

										// fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

										$count = 0;

										while ($qrBuscaProdutosTkt = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "S") {
												$mostraLOG_ATIVOTK = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraLOG_ATIVOTK = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											if ($qrBuscaProdutosTkt['LOG_PRODTKT'] == "S") {
												$mostraLOG_PRODTKT = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraLOG_PRODTKT = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											if ($qrBuscaProdutosTkt['COD_UNIVEND_AUT'] != "0") {
												$mostraCOD_UNIVEND_AUT = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraCOD_UNIVEND_AUT = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											if ($qrBuscaProdutosTkt['COD_UNIVEND_BLK'] != "0") {
												$mostraCOD_UNIVEND_BLK = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraCOD_UNIVEND_BLK = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											if ($qrBuscaProdutosTkt['TEM_IMAGEM'] == "S") {
												$mostraDES_IMAGEM = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraDES_IMAGEM = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											if ($qrBuscaProdutosTkt['LOG_OFERTAS'] == "S") {
												$mostraOFERTAS = '<i class="fas fa-check text-success" aria-hidden="true"></i>';
											} else {
												$mostraOFERTAS = '<i class="fas fa-times text-danger" aria-hidden="true"></i>';
											}

											echo "
											<tr>
											  <td class='text-center'><small>" . $mostraLOG_ATIVOTK . "</small></td>
											  <td><small>" . $qrBuscaProdutosTkt['COD_EXTERNO'] . "</small></td>
											  <td><a href='action.do?mod=" . fnEncode(1046) . "&id=" . fnEncode($cod_empresa) . "&idP=" . fnencode($qrBuscaProdutosTkt['COD_PRODUTO']) . "&idN=".fnencode($qrBuscaProdutosTkt['NOM_PRODTKT'])."&idC=".fnencode($qrBuscaProdutosTkt['COD_EXTERNO'])."'></small>" . $qrBuscaProdutosTkt['NOM_PRODTKT'] . "</small></a></td>
											  <td><small>" . $qrBuscaProdutosTkt['DES_CATEGOR'] . "</small></td>
											  <td class='text-center'><small>" . fnValor($qrBuscaProdutosTkt['VAL_PRODTKT'], 2) . "</small></td>
											  <td class='text-center'><small>" . fnValor($qrBuscaProdutosTkt['VAL_PROMTKT'], 2) . "</small></td>
											  <td class='text-center'><small>" . $mostraCOD_UNIVEND_AUT . "</small></td>
											  <td class='text-center'><small>" . $mostraCOD_UNIVEND_BLK . "</small></td>
											</tr>
											";
										}

										?>
									</tbody>

								</table>

							</div>


						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="LOG_TKTUNIVEND" id="LOG_TKTUNIVEND" value="<?php echo $log_tkunivend; ?>" />
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
										url: "relatorios/ajxRelProdutosTicket.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	// function reloadPage(idPage) {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "relatorios/ajxRelProdutosTicket.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
	// 		data: $('#formulario').serialize(),
	// 		beforeSend: function() {
	// 			$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
	// 		},
	// 		success: function(data) {
	// 			$("#relatorioConteudo").html(data);
	// 		},
	// 		error: function() {
	// 			$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
	// 		}
	// 	});
	// }
</script>