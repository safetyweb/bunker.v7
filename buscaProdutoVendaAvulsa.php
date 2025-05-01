<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$cod_externo = 0;
$des_produto = "";
$cod_orcamento = fnDecode($_GET['idO']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_venda = 0;
		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
		$casasDec = fnLimpacampo($_REQUEST['CASAS_DEC']);


		$atributo1 = fnLimpacampo($_REQUEST['ATRIBUTO1']);
		$atributo2 = fnLimpacampo($_REQUEST['ATRIBUTO2']);

		if ($_REQUEST['opcao'] != "CAD") {
			$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
			$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);
			$cod_categor = fnLimpacampoZero($_REQUEST['COD_CATEGOR']);
			$cod_subcate = fnLimpacampoZero($_REQUEST['COD_SUBCATE']);
		}

		$qtd_produto = fnLimpacampo($_REQUEST['QTD_PRODUTO']);
		$val_unitario = fnLimpacampo($_REQUEST['VAL_UNITARIO']);
		$val_descontoun = fnLimpacampo($_REQUEST['VAL_UNIDESC']);
		$val_liquido = fnLimpacampo($_REQUEST['VAL_LIQUIDO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sql = "CALL SP_ALTERA_AUXVENDA_CDESC (
					 '" . $cod_venda . "', 
					 '" . $cod_orcamento . "', 
					 '" . $cod_produto . "',
					 '" . fnValorSQLEXtrato($qtd_produto, $casasDec) . "', 
					 '" . fnValorSQLEXtrato($val_unitario, $casasDec) . "',
					 '" . fnValorSQLEXtrato($val_descontoun, $casasDec) . "',
					 '" . fnValorSQLEXtrato($val_liquido, $casasDec) . "',
					 '" . $cod_empresa . "',
					 '" . $opcao . "'    
					) ";

				//echo $sql;	
				mysqli_query(connTemp($cod_empresa, ''), trim($sql));
			}
			//echo $sql;

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
?>
					<script>
						try {
							parent.$('#REFRESH_PRODUTOS').val('S');
						} catch (err) {}
						try {
							parent.$('#VAL_TOTPRODU').prop('required', false);
						} catch (err) {}
					</script>

<?php
					break;
				case 'BUS':
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NUM_DECIMAIS_B, NUM_DECIMAIS FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
		if ($cod_empresa == 19) {
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS'];
		}
	} else {
		$casasDec = 2;
	}

	$sql = "select  A.*,B.NOM_EMPRESA as  NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '" . $cod_empresa . "' ";


	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	$casasDec = 2;
}

switch ($casasDec) {

	case 3:
		$money = "money3";
		break;

	case 4:
		$money = "money4";
		break;

	case 5:
		$money = "money5";
		break;

	default:
		$money = "money";
		break;
}

//fnMostraForm();
//fnEscreve($des_produto);
//fnEscreve($cod_categor);
//fnEscreve($cod_orcamento);

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
					<?php } ?>

					<div class="push10"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">


								<div class="col-md-8">
									<div id="accordion">
										<div class="card">
											<div class="card-header" id="headingOne">
												<h5 class="mb-0">
													<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
														Adicionais
													</button>
												</h5>
											</div>

											<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
												<div class="card-body">

													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">Cor</label>
																<select data-placeholder="Selecione o grupo" name="ATRIBUTO1" id="ATRIBUTO1" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>
																	<?php
																	$sql = "SELECT DISTINCT ATRIBUTO1 FROM PRODUTOCLIENTE WHERE COD_EMPRESA = $cod_empresa ORDER BY ATRIBUTO1";
																	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

																	while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
																		echo "
																										<option value='" . $qrListaCategoria['ATRIBUTO1'] . "'>" . $qrListaCategoria['ATRIBUTO1'] . "</option> 
																										";
																	}
																	?>
																</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">Tamanho</label>
																<select data-placeholder="Selecione o grupo" name="ATRIBUTO2" id="ATRIBUTO2" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>
																	<?php
																	$sql = "SELECT DISTINCT ATRIBUTO2 FROM PRODUTOCLIENTE WHERE COD_EMPRESA = $cod_empresa ORDER BY ATRIBUTO2";
																	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

																	while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
																		echo "
																										<option value='" . $qrListaCategoria['ATRIBUTO2'] . "'>" . $qrListaCategoria['ATRIBUTO2'] . "</option> 
																										";
																	}
																	?>
																</select>
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>

												</div>
											</div>

										</div>

									</div>

								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-12">

									<fieldset>
										<legend>Dados Gerais / Pesquisa</legend>

										<div class="row">

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Cód. Externo</label>
													<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="50">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Grupo do Produto</label>
													<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
														<option value="">&nbsp;</option>
														<?php
														$sql = "select COD_CATEGOR, DES_CATEGOR from CATEGORIA WHERE COD_EMPRESA = $cod_empresa order by DES_CATEGOR  ";
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

											<div class="col-md-4">
												<div class="form-group">
													<label for="inputName" class="control-label">Nome do Produto</label>
													<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50">
													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>

									</fieldset>

								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-12">

									<fieldset>
										<legend>Dados do Lançamento</legend>

										<div class="row">

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Qtd.</label>
													<input type="text" class="form-control input-sm text-center <?= $money ?>" name="QTD_PRODUTO" id="QTD_PRODUTO" maxlength="50" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Valor Unitário Original</label>
													<input type="text" class="form-control input-sm text-right <?= $money ?>" name="VAL_UNITARIO" id="VAL_UNITARIO" maxlength="50" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Desconto Unitário</label>
													<input type="text" class="form-control input-sm text-right <?= $money ?>" name="VAL_DESCONTOUN" id="VAL_DESCONTOUN" maxlength="50" data-error="Campo obrigatório" value="0">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Valor Unitário Líquido</label>
													<input type="text" class="form-control input-sm text-right leitura <?= $money ?>" name="VAL_UNIDESC" id="VAL_UNIDESC" maxlength="50" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Total Líquido</label>
													<input type="text" class="form-control input-sm text-right leitura <?= $money ?>" readonly name="VAL_LIQUIDO" id="VAL_LIQUIDO" maxlength="50" data-error="Campo obrigatório">
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

									</fieldset>

								</div>

							</div>


							<div class="push10"></div>
							<hr>
							<div class="form-group text-left col-lg-12">

								<div class="pull-left">
									<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp; Todos os Produtos</button>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
								</div>

								<div class="pull-right">
									<button type="submit" name="CAD" id="CAD" class="btn btn-info getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Produto ao Lançamento</button>
								</div>

							</div>

							<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
							<input type="hidden" name="cod_venda" id="cod_venda" value="">
							<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?php echo $casasDec; ?>">
							<input type="hidden" name="cod_orcamento" id="cod_orcamento" value="<?php echo $cod_orcamento; ?>">
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
												<th width="40"></th>
												<th>Código</th>
												<th>Cod. Externo</th>
												<th>Grupo</th>
												<th>Sub Grupo</th>
												<th>Descrição</th>
												<th>Valor</th>
											</tr>
										</thead>
										<tbody>

											<?php
											$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

											if ($des_produto != "") {
												$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
											} else {
												$andProduto = ' ';
											}

											if ($cod_externo  != "") {
												$andExterno = 'AND A.COD_EXTERNO = "' . $cod_externo . '"';
											} else {
												$andExterno = ' ';
											}

											if ($cod_categor  != "") {
												$andCategoria = 'AND A.COD_CATEGOR = "' . $cod_categor . '"';
											} else {
												$andCategoria = ' ';
											}

											if ($cod_subcate  != "") {
												$andSubCategoria = 'AND A.COD_SUBCATE = "' . $cod_subcate . '"';
											} else {
												$andSubCategoria = ' ';
											}

											if ($atributo1  != "") {
												$andAtributo1 = 'AND A.ATRIBUTO1 = "' . $atributo1 . '"';
											} else {
												$andAtributo1 = ' ';
											}

											if ($atributo2  != "") {
												$andAtributo2 = 'AND A.ATRIBUTO2 = "' . $atributo2 . '"';
											} else {
												$andAtributo2 = ' ';
											}

											$sql = "select COUNT(*) as contador from PRODUTOCLIENTE A 
															left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
															left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
															where A.COD_EMPRESA='" . $cod_empresa . "' 
															" . $andCategoria . "
															" . $andSubCategoria . "
															" . $andProduto . "
															" . $andExterno . " 
															" . $andAtributo1 . " 
															" . $andAtributo2 . " 
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";


											$resPagina = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$total = mysqli_fetch_assoc($resPagina);
											//seta a quantidade de itens por página, neste caso, 2 itens
											$registros = 50;
											//calcula o número de páginas arredondando o resultado para cima
											$numPaginas = ceil($total['contador'] / $registros);
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($registros * $pagina) - $registros;

											$sql1 = "select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
														LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='" . $cod_empresa . "'
														" . $andCategoria . "
														" . $andSubCategoria . "
														" . $andProduto . "
														" . $andExterno . " 
														" . $andAtributo1 . " 
														" . $andAtributo2 . " 
														AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$registros";

											//fnEscreve($sql);
											//fnEscreve($sql1);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql1);

											$count = 0;
											while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												echo "
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>&nbsp;
															  </th>
															  <td>" . $qrListaProduto['COD_PRODUTO'] . "</td>
															  <td>" . $qrListaProduto['COD_EXTERNO'] . "</td>
															  <td>" . $qrListaProduto['GRUPO'] . "</td>
															  <td>" . $qrListaProduto['SUBGRUPO'] . "</td>
															  <td>" . $qrListaProduto['DES_PRODUTO'] . "</td>
															  <td>" . fnValor($qrListaProduto['VAL_PRECO'], 2) . "</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrListaProduto['COD_PRODUTO'] . "'>  
															<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaProduto['COD_EXTERNO'] . "'>
															<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrListaProduto['DES_PRODUTO'] . "'>
															<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrListaProduto['COD_CATEGOR'] . "'>
															<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrListaProduto['COD_SUBCATE'] . "'>
															<input type='hidden' id='ret_ATRIBUTO1_" . $count . "' value='" . $qrListaProduto['ATRIBUTO1'] . "'>
															<input type='hidden' id='ret_ATRIBUTO2_" . $count . "' value='" . $qrListaProduto['ATRIBUTO2'] . "'>
															<input type='hidden' id='ret_VAL_PRECO_" . $count . "' value='" . fnValor($qrListaProduto['VAL_PRECO'], $casasDec) . "'>
															";
											}

											?>

										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<ul class="pagination pagination-sm pull-right">
														<?php
														for ($i = 1; $i < $numPaginas + 1; $i++) {
															echo "<li class='pagination'><a href='{$_SERVER['PHP_SELF']}?mod=" . fnEncode(1070) . "&id=" . fnEncode($cod_empresa) . "&idO=" . fnEncode($cod_orcamento) . "&pagina=$i&pop=true' style='text-decoration: none;'>" . $i . "</a></li>";
														}
														?></ul>
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
		let casasDec = "<?= $casasDec ?>";

		$(document).ready(function() {
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$('.money3').mask("#.##0,000", {
				reverse: true
			});
			$('.money4').mask("#.##0,0000", {
				reverse: true
			});
			$('.money5').mask("#.##0,00000", {
				reverse: true
			});

			function formatDecimal(input) {
				let value = input.value.replace(',', '.');
				if (value.indexOf('.') === -1) {
					value = (parseFloat(value) * 0.001).toFixed(3);
				} else {
					value = parseFloat(value).toFixed(3);
				}
				input.value = value.replace('.', ',');
			}

			$('#QTD_PRODUTO,#VAL_UNITARIO,#VAL_DESCONTOUN').on('blur', function() {
				let cod_empresa = $("#COD_EMPRESA").val(),
					qtd_produto = limpaValor($("#QTD_PRODUTO").val()),
					val_unitario = limpaValor($("#VAL_UNITARIO").val()),
					val_descontoun = limpaValor($("#VAL_DESCONTOUN").val()),
					val_unidesc = 0;

				if (qtd_produto == "") {
					qtd_produto = 0
				}
				if (val_unitario == "") {
					val_unitario = 0
				}
				if (val_descontoun == "") {
					val_descontoun = 0
				}


				val_unidesc = val_unitario - val_descontoun;
				val_liq = val_unidesc * qtd_produto;

				$("#VAL_UNIDESC").val(converterValorTela(val_unidesc, casasDec));
				$("#VAL_LIQUIDO").val(converterValorTela(val_liq, casasDec));

				if (cod_empresa == 19) {
					// formatDecimal(this);
					// formatDecimal(document.getElementById("VAL_UNIDESC"));
					// formatDecimal(document.getElementById("VAL_LIQUIDO"));
				}
			});

			// $('#QTD_PRODUTO,#VAL_UNITARIO,#VAL_DESCONTOUN').on('change', function() {
			// 	let cod_empresa = $("#COD_EMPRESA").val(),
			// 		qtd_produto = $("#QTD_PRODUTO").val().replace(",","."), 
			// 		val_unitario = $("#VAL_UNITARIO").val().replace(",","."),
			// 		val_descontoun = $("#VAL_DESCONTOUN").val().replace(",","."),
			// 		val_unidesc = 0;

			// 	if(qtd_produto == ""){qtd_produto = 0}
			// 	if(val_unitario == ""){val_unitario = 0}
			// 	if(val_descontoun == ""){val_descontoun = 0}

			// 	val_unidesc = val_unitario - val_descontoun;

			// 	$("#VAL_UNIDESC").val(val_unidesc).mask('0.000');
			// 	$("#VAL_LIQUIDO").val(val_unidesc*qtd_produto).mask('0.000');

			// 	if (cod_empresa == 19) {
			// 		formatDecimal(this);
			// 	}

			// });

			// $('#VAL_UNITARIO').on('blur', function() {
			// 	let cod_empresa = $("#COD_EMPRESA").val();
			// 	if (cod_empresa == 19) {
			// 		formatDecimal(this);
			// 	}
			// });

			$("#CAD").on("click", function(e) {
				if ($("#COD_PRODUTO").val().trim() == "") {
					e.preventDefault();

					parent.$.alert({
						title: "Aviso",
						content: "Nenhum produto selecionado",
						type: 'orange',
						buttons: {
							"Ok": {
								action: function() {

								}
							}
						},
						backgroundDismiss: function() {
							return 'Ok';
						}
					});
				}
			});
		});

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
		});

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
			$("#formulario #COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val()).trigger("chosen:updated");
			$("#formulario #ATRIBUTO1").val($("#ret_ATRIBUTO1_" + index).val()).trigger("chosen:updated");
			$("#formulario #ATRIBUTO2").val($("#ret_ATRIBUTO2_" + index).val()).trigger("chosen:updated");
			$('#QTD_PRODUTO').val('1');
			$("#formulario #VAL_UNITARIO").val($("#ret_VAL_PRECO_" + index).val());

			var codCat = $("#ret_COD_CATEGOR_" + index).val();
			var codSub = $("#ret_COD_SUBCATE_" + index).val();
			buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function downForm(index) {

			try {
				parent.$('#DES_PRODUTO').val($("#ret_DES_PRODUTO_" + index).val());
			} catch (err) {}
			try {
				parent.$('#COD_PRODUTO').val($("#ret_COD_PRODUTO_" + index).val());
			} catch (err) {}
			$(this).removeData('bs.modal');
			parent.$('#popModalAux').modal('hide');

		}
	</script>