<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$msgRetorno = "";
$msgTipo = "";
$cod_produto = "";
$cod_categor = "";
$cod_subcate = "";
$cod_laborat = "";
$ean = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tipo = "";
$popUp = "";
$qrListaCategoria = "";
$andEan = "";
$andProduto = "";
$andExterno = "";
$retorno = "";
$inicio = "";
$teste = "";
$qrListaProduto = "";
$mostraDown = "";


$hashLocal = mt_rand();
$cod_externo = 0;
$des_produto = "";

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_produto = fnLimpacampoZero(@$_REQUEST['COD_PRODUTO']);
		$cod_categor = fnLimpacampoZero(@$_REQUEST['COD_CATEGOR']);
		$cod_subcate = fnLimpacampoZero(@$_REQUEST['COD_SUBCATE']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$cod_laborat = fnLimpacampo(@$_REQUEST['COD_LABORAT']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		$ean = fnLimpacampo(@$_REQUEST['EAN']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//echo $sql;

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";
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
}

if (isset($_GET['tipo'])) {
	$tipo = @$_GET['tipo'];
} else {
	$tipo = '';
}

//fnMostraForm();
//fnEscreve($des_produto);
//fnEscreve($cod_empresa);

?>
<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
						<div class="push10"></div>
					<?php } ?>

					<div class="push"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Produto</label>
										<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50" data-error="Campo obrigatório" value="<?= $des_produto ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">EAN</label>
										<input type="text" class="form-control input-sm" name="EAN" id="EAN" maxlength="50" data-error="Campo obrigatório" value="<?= $ean ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="50" data-error="Campo obrigatório" value="<?= $cod_externo ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div style="display: none;">
								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo do Produto</label>
											<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "select * from CATEGORIA order by DES_CATEGOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
																				";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Sub Grupo do Produto</label>
											<div id="divId_sub">
												<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
													<option value="">&nbsp;</option>
												</select>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Fornecedor</label>
											<input type="text" class="form-control input-sm" name="COD_LABORAT" id="COD_LABORAT" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>
							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-cubes" aria-hidden="true"></i>&nbsp; Todos os Produtos</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>

							</div>

							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="50"></th>
												<th>Código</th>
												<th>EAN</th>
												<th>Cod. Externo</th>
												<th>Grupo</th>
												<th>Sub Grupo</th>
												<th>Descrição</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">


											<?php

											if ($ean != '' && $ean != 0) {
												$andEan = "AND A.EAN = '$ean'";
											} else {
												$andEan = ' ';
											}

											if ($des_produto != '') {
												$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
											} else {
												$andProduto = ' ';
											}

											if ($cod_externo  != "" && $cod_externo != 0) {
												$andExterno = "AND A.COD_EXTERNO IN($cod_externo)";
											} else {
												$andExterno = ' ';
											}

											$sql = "select COUNT(*) as CONTADOR from PRODUTOCLIENTE A 
															left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
															left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
															where A.COD_EMPRESA='" . $cod_empresa . "' 
															" . $andProduto . "
															" . $andExterno . " 
															" . $andEan . " 
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";

											//fnEscreve($sql);
											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


											$sql = "select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
														LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='" . $cod_empresa . "' 
														" . $andProduto . "
														" . $andExterno . " 
														" . $andEan . " 
														AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";

											// fnEscreve($sql);
											//fnEscreve($cod_empresa);

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

											//$teste = mysqli_num_rows($arrayQuery);

											//fnEscreve($sql);

											$count = 0;
											while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												if ($qrListaProduto['LOG_PRODPBM'] == "N" || is_null($qrListaProduto['LOG_PRODPBM']) || $qrListaProduto['LOG_PRODPBM'] == "") {
													$mostraDown = "<a href='javascript: downForm($count)' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a>";
												} else {
													$mostraDown = "&nbsp;<i class='fa fa-times-circle' style='color:red;' aria-hidden='true' data-toggle='tooltip' data-placement='top' data-original-title='pbm'></i>";
												}
											?>

												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm("<?= $count ?>")'>&nbsp;
														<?= $mostraDown ?>
													</td>
													<td><?= $qrListaProduto['COD_PRODUTO'] ?></td>
													<td><?= $qrListaProduto['EAN'] ?></td>
													<td><?= $qrListaProduto['COD_EXTERNO'] ?></td>
													<td><?= $qrListaProduto['GRUPO'] ?></td>
													<td><?= $qrListaProduto['SUBGRUPO'] ?></td>
													<td><?= $qrListaProduto['DES_PRODUTO'] ?></td>
												</tr>
												<input type='hidden' id='ret_COD_PRODUTO_<?= $count ?>' value="<?= $qrListaProduto['COD_PRODUTO'] ?>">
												<input type='hidden' id='ret_COD_EXTERNO_<?= $count ?>' value="<?= $qrListaProduto['COD_EXTERNO'] ?>">
												<input type='hidden' id='ret_DES_PRODUTO_<?= $count ?>' value="<?= $qrListaProduto['DES_PRODUTO'] ?>">
												<input type='hidden' id='ret_COD_CATEGOR_<?= $count ?>' value="<?= $qrListaProduto['COD_CATEGOR'] ?>">
												<input type='hidden' id='ret_COD_SUBCATE_<?= $count ?>' value="<?= $qrListaProduto['COD_SUBCATE'] ?>">

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

								</form>

							</div>

						</div>

						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>


	<script type="text/javascript">
		$(document).ready(function() {
			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxBuscaFidProdutoExtra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}

		function buscaSubCat(idCat, idSub, idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupo.php",
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
				},
				error: function() {
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}

		function retornaForm(index) {
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");

			// alert($("#ret_DES_PRODUTO_"+index).val());

			//var codCat = $("#ret_COD_CATEGOR_"+index).val();
			//var codSub = $("#ret_COD_SUBCATE_"+index).val();
			//buscaSubCat(codCat,codSub,<?php echo $cod_empresa; ?>);	

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function downForm(index) {
			var tipo = '<?= $tipo ?>';
			// alert(tipo);
			if (tipo == 'rel') {
				// alert('1');	
				parent.$('#formulario #DES_PRODUTO').val($("#ret_DES_PRODUTO_" + index).val());
				parent.$('#formulario #COD_PRODUTO').val($("#ret_COD_PRODUTO_" + index).val());
				$(this).removeData('bs.modal');
				parent.$('#popModal').modal('hide');
				parent.$('#formulario').validator('validate');

			} else if (tipo == 'desc') {

				// alert($("#ret_COD_CATEGOR_"+index).val());
				parent.$('#NOM_PRODTKT').val($("#ret_DES_PRODUTO_" + index).val());
				parent.$('#DES_PRODUTO').val($("#ret_DES_PRODUTO_" + index).val());
				parent.$('#COD_PRODUTO').val($("#ret_COD_PRODUTO_" + index).val());
				parent.buscaSubCat($("#ret_COD_CATEGOR_" + index).val(), 0, "<?= $cod_empresa ?>");
				parent.$("#COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
				parent.$("#COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val()).trigger("chosen:updated");
				$(this).removeData('bs.modal');
				parent.$('#popModalAux').modal('hide');
				parent.$('#formulario').validator('validate');

			} else {
				//alert('3');
				try {
					parent.$('#NOM_PRODTKT').val($("#ret_DES_PRODUTO_" + index).val());
				} catch (err) {}
				try {
					parent.$('#DES_PRODUTO').val($("#ret_DES_PRODUTO_" + index).val());
				} catch (err) {}
				try {
					parent.$('#COD_PRODUTO').val($("#ret_COD_PRODUTO_" + index).val());
				} catch (err) {}
				try {
					parent.carregarCombo($("#ret_COD_CATEGOR_" + index).val());
				} catch (err) {}
				try {
					parent.$("#BL2_COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
				} catch (err) {}
				try {
					parent.$("#COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val()).trigger("chosen:updated");
				} catch (err) {}
				$(this).removeData('bs.modal');
				parent.$('#popModal').modal('hide');
				parent.$('#popModalAux').modal('hide');
				parent.$('#formulario').validator('validate');

				//alert('aqui');
			}


		}
	</script>