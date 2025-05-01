	<?php

	if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
		echo fnDebug('true');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	$log_ativo = "";
	$popUp = "";
	$cod_template = "";
	$hashLocal = "";
	$msgRetorno = "";
	$msgTipo = "";
	$nom_template = "";
	$abv_template = "";
	$des_template = "";
	$opcao = "";
	$hHabilitado = "";
	$hashForm = "";
	$cod_usucada = "";
	$cod_campanha = "";
	$arrayQuery = [];
	$qrBuscaEmpresa = "";
	$nom_empresa = "";
	$retorno = "";
	$joinTempl = "";
	$inicio = "";
	$qrBuscaModulos = "";
	$sincronia = "";


	// definir o numero de itens por pagina
	$itens_por_pagina = 20;
	$pagina  = "1";

	$log_ativo = 'N';

	if (isset($_GET['pop'])) {
		$popUp = fnLimpaCampo(@$_GET['pop']);
	} else {
		$popUp = '';
	}

	$cod_template = "";

	$hashLocal = mt_rand();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = md5(serialize($_POST));

		if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		} else {
			$_SESSION['last_request']  = $request;

			$cod_template = fnLimpaCampoZero(@$_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
			if (empty(@$_REQUEST['LOG_ATIVO'])) {
				$log_ativo = 'N';
			} else {
				$log_ativo = @$_REQUEST['LOG_ATIVO'];
			}
			$nom_template = fnLimpaCampo(@$_REQUEST['NOM_TEMPLATE']);
			$abv_template = fnLimpaCampo(@$_REQUEST['ABV_TEMPLATE']);
			$des_template = fnLimpaCampo(@$_REQUEST['DES_TEMPLATE']);

			$opcao = @$_REQUEST['opcao'];
			$hHabilitado = @$_REQUEST['hHabilitado'];
			$hashForm = @$_REQUEST['hashForm'];

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			if ($opcao != '' && $opcao != 0) {

				//mensagem de retorno
				switch ($opcao) {
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
						break;
						break;
				}
				$msgTipo = 'alert-success';
			}
		}
	}

	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

		//busca dados da empresa
		$cod_empresa = fnDecode(@$_GET['id']);
		$cod_campanha = fnDecode(@$_GET['idc']);
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaEmpresa)) {
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
	} else {
		$nom_empresa = "";
	}


	$sql = "SELECT DISTINCT TE.COD_TEMPLATE FROM TEMPLATE_EMAIL TE
	INNER JOIN TEMPLATE_EMAIL_CAMPANHA TEC ON TEC.COD_TEMPLATE = TE.COD_TEMPLATE AND TEC.COD_CAMPANHA = $cod_campanha
	WHERE TE.COD_EMPRESA = $cod_empresa 
	AND TE.LOG_ATIVO = 'S'";

	//fnEscreve($sql);
	@$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
	@$total_itens_por_pagina = mysqli_num_rows($retorno);

	if ($total_itens_por_pagina > 0) {
		$joinTempl = "INNER JOIN TEMPLATE_EMAIL_CAMPANHA TEC ON TEC.COD_TEMPLATE = TE.COD_TEMPLATE AND TEC.COD_CAMPANHA = $cod_campanha";
	} else {
		$joinTempl = "";
	}

	//fnMostraForm();
	//fnEscreve($cod_campanha);

	?>

	<style>
		body {
			overflow: hidden;
		}

		.change-icon .fa+.fa,
		.change-icon:hover .fa:not(.fa-edit) {
			display: none;
		}

		.change-icon:hover .fa+.fa:not(.fa-edit) {
			display: inherit;
		}

		.fa-edit:hover {
			color: #18bc9c;
			cursor: pointer;
		}

		.tile {
			cursor: pointer;
		}

		.item {
			padding-top: 0;
		}
	</style>

	<link rel="stylesheet" href="css/widgets.css" />

	<div class="row">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true") {  ?>
				<div class="portlet portlet-bordered">
				<?php } else { ?>
					<div class="portlet" style="padding: 0 20px 20px 20px;">
					<?php } ?>

					<?php if ($popUp != "true") {  ?>
						<div class="portlet-title">
							<div class="caption">
								<i class="glyphicon glyphicon-calendar"></i>
								<span class="text-primary"><?php echo $NomePg; ?></span>
							</div>
							<?php include "atalhosPortlet.php"; ?>
						</div>
					<?php } ?>
					<div class="portlet-body">

						<?php if ($msgRetorno <> '') { ?>
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<?php echo $msgRetorno; ?>
							</div>
						<?php } ?>

						<div class="row">

							<div class="col-md-12">
								<h4 style="margin: 0 0 5px 0;"><span class="bolder">Lista de Templates</span></h4>
							</div>

						</div>

						<div class="push10"></div>

						<div class="row">

							<div class="col-md-6">
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Mostrar todas as templates</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ALL" id="LOG_ALL" class="switch" value="S" onchange="reloadPage(1)" <?= ($joinTempl != '' ? '' : 'checked') ?>>
											<span></span>
										</label>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<a href="javascript:void(0)" class="btn btn-xs btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1409) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&tipo=<?php echo fnEncode('CAD') ?>&pop=true" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class="fa fa-plus fa-2x" aria-hidden="true" style="padding: 5px 5px;"></i></a>
							</div>

						</div>

						<div class="push20"></div>

						<div class="col-md-12">

							<table class="table table-bordered table-striped table-hover tablesorter">

								<thead>
									<tr>
										<th>Nome da Template</th>
										<th>Data de Criação</th>
										<th>Última Alteração</th>
										<th>Sincronizado</th>
										<th class="{sorter:false}"></th>
									</tr>
								</thead>

								<tbody id="listaTemplates">

									<?php

									if ($joinTempl == "") {

										$sql = "SELECT DISTINCT TE.COD_TEMPLATE FROM TEMPLATE_EMAIL TE
											WHERE TE.COD_EMPRESA = $cod_empresa 
											AND TE.LOG_ATIVO = 'S'";

										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);
									}

									$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "SELECT DISTINCT TE.* FROM TEMPLATE_EMAIL TE
												$joinTempl
												WHERE TE.COD_EMPRESA = $cod_empresa 
												AND TE.LOG_ATIVO = 'S'
												ORDER BY TE.DAT_CADASTR DESC
												LIMIT $inicio,$itens_por_pagina";

									// fnEscreve($sql);

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if (@$qrBuscaModulos['COD_EXT_TEMPLATE'] != "") {
											$sincronia = "<span class='fas fa-check text-success' style='padding: 5px 5px;'></span>";
										} else {
											$sincronia = "<span class='fas fa-times text-danger' style='padding: 5px 5px;'></span>";
										}

									?>

										<tr>
											<td><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></td>
											<td><small><?php echo fnDataFull($qrBuscaModulos['DAT_CADASTR']); ?></td>
											<td><small><?php echo fnDataFull($qrBuscaModulos['DAT_ALTERAC']); ?></td>
											<td class='text-center'>
												<?= $sincronia ?>
											</td>
											<td class="text-center">
												<small>
													<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
														</button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a data-url="action.php?mod=<?php echo fnEncode(1409) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']); ?>&idc=<?= fnEncode($cod_campanha) ?>&tipo=<?php echo fnEncode('ALT') ?>&pop=true&rnd=<?= rand() ?>" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'>Editar</a></li>
															<li><a data-url='action.php?mod=<?php echo fnEncode(1411) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true&rnd=<?= rand() ?>' data-title="Template: <?= $qrBuscaModulos['NOM_TEMPLATE'] ?>" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"lg")} catch(err) {}'>Acessar</a></li>
															<li class="divider"></li>
															<li><a href="javascript:void(0)" onclick='excTemplate("<?= fnEncode($qrBuscaModulos['COD_TEMPLATE']) ?>")'>Excluir</a></li>
														</ul>
													</div>
												</small>
											</td>
										</tr>
									<?php
									}
									?>

								</tbody>

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



						<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

						<!-- modal -->
						<!-- <div class="modal fade" id="popModal" tabindex='-1'>
							<div class="modal-dialog" style="">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title"></h4>
									</div>
									<div class="modal-body">
										<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
									</div>		
								</div>
							</div>
						</div>	 -->

						<div class="push20"></div>

						<script type="text/javascript">
							parent.$("#conteudoAba").css("height", ($(".portlet").height() + 50) + "px");

							var current_page = 1;

							$(document).ready(function() {

								var numPaginas = <?php echo $numPaginas; ?>;
								if (numPaginas != 0) {
									carregarPaginacao(numPaginas);
								}


								jQuery('#paginacao').on('page', function(event, page) {
									current_page = page;
									// console.log('current_page', current_page);
								});

								//modal close
								parent.$('.modal').on('hidden.bs.modal', function() {
									reloadPage(current_page);
									parent.$("#conteudoAba").css("height", ($(".portlet").height() + 50) + "px");
								});

							});

							function reloadPage(idPage) {

								var log_all;

								if ($('#LOG_ALL').prop('checked')) {
									log_all = 'S';
								} else {
									log_all = 'N';
								}

								$.ajax({
									type: "POST",
									url: "ajxListaTemplateEmail.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
									data: {
										LOG_ALL: log_all
									},
									beforeSend: function() {
										$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
									},
									success: function(data) {
										$("#listaTemplates").html(data);
										parent.$("#conteudoAba").css("height", ($(".portlet").height() + 50) + "px");
										console.log(data);
									},
									error: function() {
										$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
									}
								});
							}

							function excTemplate(idTemp) {
								parent.$.alert({
									title: "Confirmação",
									content: "Deseja mesmo excluir a template?",
									type: 'red',
									buttons: {
										"Excluir": {
											btnClass: 'btn-danger',
											action: function() {
												$.ajax({
													type: "POST",
													url: "ajxListaTemplateEmail.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>&idPage=" + current_page + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
													data: {
														COD_TEMPLATE: idTemp
													},
													beforeSend: function() {
														$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
													},
													success: function(data) {
														$("#listaTemplates").html(data);
														parent.$("#conteudoAba").css("height", ($(".portlet").height() + 50) + "px");
													},
													error: function() {
														$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
													}
												});
											}
										},
										"Cancelar": {
											action: function() {

											}
										}
									}
								});
							}

							function retornaForm(index) {
								$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
								$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
								$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger("chosen:updated");
								$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_" + index).val());
								$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_" + index).val());
								$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_" + index).val());
								$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_" + index).val());
								$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_" + index).val());
								$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_" + index).val());
								$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_" + index).val());
								$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_" + index).val());
								$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_" + index).val());
								$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_" + index).val());
								$('#formulario').validator('validate');
								$("#formulario #hHabilitado").val('S');
							}
						</script>