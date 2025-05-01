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
$filtro = "";
$val_pesquisa = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$cod_produto = "";
$cod_externo = "";
$log_ativo = "";
$cod_ean = "";
$des_produto = "";
$cod_categor = "";
$cod_subcate = "";
$cod_fornecedor = "";
$des_disponibilidade = "";
$des_tipoentrega = "";
$num_pontos = "";
$des_imagem = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$produtoGestao = "";
$sql2 = "";
$arrayQuery2 = [];
$qrProdutoUnico = "";
$ean = "";
$check_LOG_ATIVO = "";
$check_BPM = "";
$esconde = "";
$sqlAtivo = "";
$arrayAtivo = [];
$qrAtivo = "";
$checkAtivo = "";
$sql3 = "";
$arrayQuery3 = [];
$qrUs = "";
$codUnivend_usu = "";
$popUp = "";
$abaMarkaPontos = "";
$qrListaUnidade = "";
$unidade_default = "";
$andFiltro = "";
$pesquisa = "";
$andExternoTkt = "";
$andProduto = "";
$andExterno = "";
$retorno = "";
$inicio = "";
$sql1 = "";
$qrListaProduto = "";
$mostraDES_IMAGEM = "";




$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_univend = @$_POST['COD_UNIVEND'];

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PRODUTOPROMOCAO (
				 '" . $cod_produto . "', 
				 '" . $cod_externo . "', 
				 '" . $cod_empresa . "',				
				 '" . $log_ativo . "', 
				 '" . $cod_ean . "',				
				 '" . $des_produto . "',				
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . $des_disponibilidade . "',
				 '" . $des_tipoentrega . "',
				 '" . $num_pontos . "',				 
				 '" . $des_imagem . "',				 
				 '" . $cod_usucada . "',
				 '" . $opcao . "'   
				) ";

			//echo $sql;
			//fnTesteSql(connTemp($cod_empresa,""),$sql);

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	/*
		$sql = "SELECT A.*,
		(select B.NOM_EMPRESA FROM empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA
		FROM EMPRESACOMPLEMENTO A where A.COD_EMPRESA = '".$cod_empresa."' ";
		*/
	$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '" . $cod_empresa . "' ";


	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//////////////////////// produtos/prod. gestão de ofertas  /////////////////////////////////////
$produtoGestao = fnDecode(@$_GET['idPrd']);
if (isset($produtoGestao) && fnDecode(@$_GET['mod']) == 1194) {
	//fnEscreve("tem gestão");		

	if ($produtoGestao != "0") {
		$sql2 = "select A.* from PRODUTOPROMOCAO A 
				where A.COD_EMPRESA = $cod_empresa
				AND A.COD_PRODUTO = $produtoGestao
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO ";
		//fnEscreve($sql);
		$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ""), $sql2);
		$qrProdutoUnico = mysqli_fetch_assoc($arrayQuery2);

		$cod_produto = $qrProdutoUnico['COD_PRODUTO'];
		$cod_externo = $qrProdutoUnico['COD_EXTERNO'];
		$des_produto = $qrProdutoUnico['DES_PRODUTO'];
		$cod_categor = $qrProdutoUnico['COD_CATEGOR'];
		$cod_subcate = $qrProdutoUnico['COD_SUBCATE'];
		$cod_fornecedor = $qrProdutoUnico['COD_FORNECEDOR'];
		$ean = $qrProdutoUnico['EAN'];
		$des_disponibilidade = $qrProdutoUnico['DES_DISPONIBILIDADE'];
		$des_tipoentrega = $qrProdutoUnico['DES_TIPOENTREGA'];
		$num_pontos = $qrProdutoUnico['NUM_PONTOS'];
		$des_imagem = $qrProdutoUnico['DES_IMAGEM'];

		if ($qrProdutoUnico['LOG_ATIVO'] == "N") {
			$check_LOG_ATIVO = '';
		} else {
			$check_LOG_ATIVO = "checked";
		}
	}
} else {
	//fnEscreve("não tem gestão");		
	$cod_produto = "";
	$cod_externo = "";
	$des_produto = "";
	$cod_categor = "";
	$cod_subcate = "";
	$cod_fornecedor = "";
	$ean = "";
	$des_disponibilidade = "";
	$des_tipoentrega = "";
	$num_pontos = "";
	$des_imagem = "";
	$check_BPM = "";
	$check_LOG_ATIVO = "";
}

if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

$sqlAtivo = "SELECT LOG_ATIVO FROM ESTOQUE_PRODUTO WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 LIMIT 1";
$arrayAtivo = mysqli_query(connTemp($cod_empresa, ''), $sqlAtivo);
$qrAtivo = mysqli_fetch_assoc($arrayAtivo);

$log_ativo = $qrAtivo['LOG_ATIVO'];

$checkAtivo = "";

if ($log_ativo == "S") {
	$checkAtivo = "checked";
}


//fnMostraForm();
//fnEscreve($cod_univend);
//fnEscreve(fnDecode(@$_GET['idPrd']));

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$sql3 = "SELECT COD_UNIVEND FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
$arrayQuery3 = mysqli_query($connAdm->connAdm(), $sql3);

//fnEscreve($sql3);

$qrUs = mysqli_fetch_assoc($arrayQuery3);

$codUnivend_usu = $qrUs['COD_UNIVEND'];


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
							<i class="fal fa-terminal"></i>
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

					<?php
					//menu superior - markapontos
					$abaMarkaPontos = 1372;
					include "abasMarkapontos.php";
					?>

					<?php if ($popUp != "true") {  ?>
						<div class="push30"></div>
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Unidades</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<?php

												$count = 0;

												if ($_SESSION["SYS_COD_EMPRESA"] != $cod_empresa) {
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) AND NOM_FANTASI IS NOT NULL ORDER BY NOM_UNIVEND";
												} else {
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN($codUnivend_usu) AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) AND NOM_FANTASI IS NOT NULL ORDER BY NOM_UNIVEND";
												}

												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
																				";

													if ($count == 0) {
														$unidade_default = $qrListaUnidade['COD_UNIVEND'];
													}

													$count++;
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
											<?php //fnEscreve($sql) 
											?>
										</div>
									</div>

									<!-- <div class="col-md-2">   
															<div class="form-group">
																<label for="inputName" class="control-label">Controle Ativo</label> 
																<div class="push5"></div>
																	<label class="switch">
																	<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
																	<span></span>
																	</label>
															</div>
														</div> -->

								</div>

							</fieldset>
						</form>

						<div class="push30"></div>

						<style>
							.input-xs {
								height: 26px;
								padding: 2px 5px;
								font-size: 12px;
								line-height: 1.5;
								/* If Placeholder of the input is moved up, rem/modify this. */
								border-radius: 3px;
								border: 0;
							}
						</style>


						<div class="col-lg-12">

							<div class="no-more-tables">

								<div class="push30"></div>

								<div class="row">
									<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

										<div class="col-xs-4 col-xs-offset-4">
											<div class="input-group activeItem">
												<div class="input-group-btn search-panel">
													<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
														<span id="search_concept">Sem filtro</span>&nbsp;
														<span class="far fa-angle-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu">
														<li class="divisor"><a href="#">Sem filtro</a></li>
														<!-- <li class="divider"></li> -->
														<li><a href="#DES_PRODUTO">Nome do Produto</a></li>
														<li><a href="#COD_EXTERNO">Código Externo</a></li>
													</ul>
												</div>
												<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
												<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
												<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
													<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
												</div>
												<div class="input-group-btn">
													<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
												</div>
											</div>
										</div>

										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

									</form>

								</div>

								<div class="push30"></div>


								<form name="formLista">

									<table class="table table-bordered table-striped table-hover buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Cod. Externo</th>
												<th>Descrição</th>
												<th>Pontos/Troca</th>
												<th>Qtd. Estoque</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">


											<?php

											if ($filtro != '' && $filtro != 0) {
												$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
											} else {
												$andFiltro = " ";
											}

											$pagina = (isset($_GET['pagina'])) ? @$_GET['pagina'] : 1;

											//variáveis da pesquisa
											$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
											$pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
											$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);

											//pesquisa no form local
											$andExternoTkt = ' ';
											if (empty(@$_REQUEST['pesquisa'])) {
												//fnEscreve("sem pesquisa");
												$andProduto = ' ';
												$andExterno = ' ';
											} else {
												//fnEscreve("com pesquisa");
												if ($des_produto != '' && $des_produto != 0) {
													$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
												} else {
													$andProduto = ' ';
												}

												if ($cod_externo != '' && $cod_externo != 0) {
													$andExterno = 'AND A.COD_EXTERNO = "' . $cod_externo . '"';
												} else {
													$andExterno = ' ';
												}
											}

											//se pesquisa dos produtos do ticket
											if (!empty(@$_GET['idP'])) {
												$andExterno = 'AND A.COD_EXTERNO = "' . @$_GET['idP'] . '"';
											}

											//fnEscreve("entrou");

											$sql = "SELECT COUNT(*) AS CONTADOR from PRODUTOPROMOCAO
														where COD_EMPRESA=$cod_empresa 
														$andProduto
														$andExterno 
														AND COD_EXCLUSA=0 
														-- AND log_markapontos = 0 
														$andFiltro order by DES_PRODUTO";

											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql1 = "SELECT PP.*,
														(SELECT EP.QTD_ESTOQUE FROM ESTOQUE_PRODUTO EP WHERE EP.COD_UNIVEND = $unidade_default AND EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = PP.COD_PRODUTO) AS QTD_ESTOQUE
														FROM PRODUTOPROMOCAO PP
														where PP.COD_EMPRESA=$cod_empresa 
														$andProduto
														$andExterno 
														AND PP.COD_EXCLUSA=0 
														-- AND PP.log_markapontos = 0 
														$andFiltro order by PP.DES_PRODUTO limit $inicio,$itens_por_pagina";

											//fnEscreve($unidade_default);
											//fnEscreve($sql1);

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql1);

											$count = 0;
											while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrListaProduto['DES_IMAGEM'] != "") {
													$mostraDES_IMAGEM = '<i class="fal fa-check-square-o" aria-hidden="true"></i>';
												} else {
													$mostraDES_IMAGEM = '';
												}

											?>

												<tr>
													<td><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
													<td><?= $qrListaProduto['COD_PRODUTO'] ?></td>
													<td><?= $qrListaProduto['COD_EXTERNO'] ?></td>
													<td><?= $qrListaProduto['DES_PRODUTO'] ?></td>
													<td><?= $qrListaProduto['NUM_PONTOS'] ?></td>

													<td class='text-center'>
														<a href="javascript:void(0);" class="editable-estoque"
															data-type='text'
															data-title='Editar Estoque' data-pk="<?php echo $qrListaProduto['COD_PRODUTO']; ?>"
															data-name="QTD_ESTOQUE"
															data-count="<?= $count ?>"><?= fnValor($qrListaProduto['QTD_ESTOQUE'], 0) ?>

														</a>
													</td>

												</tr>

												<input type='hidden' id='ret_COD_PRODUTO_<?= $count ?>' value='<?= $qrListaProduto['COD_PRODUTO'] ?>'>
												<input type='hidden' id='ret_COD_EXTERNO_<?= $count ?>' value='<?= $qrListaProduto['COD_EXTERNO'] ?>'>
												<input type='hidden' id='ret_DES_PRODUTO_<?= $count ?>' value='<?= $qrListaProduto['DES_PRODUTO'] ?>'>
												<input type='hidden' id='ret_COD_EAN_<?= $count ?>' value='<?= $qrListaProduto['EAN'] ?>'>
												<input type='hidden' id='ret_DES_DISPONIBILIDADE_<?= $count ?>' value='<?= $qrListaProduto['DES_DISPONIBILIDADE'] ?>'>
												<input type='hidden' id='ret_DES_TIPOENTREGA_<?= $count ?>' value='<?= $qrListaProduto['DES_TIPOENTREGA'] ?>'>
												<input type='hidden' id='ret_NUM_PONTOS_<?= $count ?>' value='<?= $qrListaProduto['NUM_PONTOS'] ?>'>
												<input type='hidden' id='ret_DES_IMAGEM_<?= $count ?>' value='<?= $qrListaProduto['DES_IMAGEM'] ?>'>
												<input type='hidden' id='ret_LOG_ATIVO_<?= $count ?>' value='<?= $qrListaProduto['LOG_ATIVO'] ?>'>

											<?php
											}
											?>
											<script>
												$(function() {
													$('.editable-estoque').editable({
														emptytext: '0',
														url: 'ajxCalculaEstoque.php',
														ajaxOptions: {
															type: 'post'
														},
														params: function(params) {
															params.count = $(this).data('count');
															params.COD_EMPRESA = <?= $cod_empresa ?>;
															params.COD_UNIVEND = $('#COD_UNIVEND').val();
															return params;
														},
														success: function(data) {
															//console.log(data);
														}
													});
												});
											</script>
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

						<input type="hidden" name="SELECTED_UNIVEND" id="SELECTED_UNIVEND" value="<?= $unidade_default ?>" />


						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript">
		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e) {
			var value = $('#INPUT').val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#", "");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
			});

			$('#CLEAR').click(function() {
				$('#INPUT').val('');
				$('#INPUT').focus();
				$('#CLEARDIV').hide();
				if ("<?= $filtro ?>" != "") {
					location.reload();
				} else {
					var value = $('#INPUT').val().toLowerCase().trim();
					if (value) {
						$('#CLEARDIV').show();
					} else {
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function(index) {
						if (!index) return;
						$(this).find("td").each(function() {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('tr').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			});

			// $('#SEARCH').click(function(){
			// 	$('#formulario').submit();
			// });


		});

		function buscaRegistro(el) {
			var filtro = $('#search_concept').text().toLowerCase();

			if (filtro == "sem filtro") {
				var value = $(el).val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		}

		//-----------------------------------------------------------------------------------

		$(document).ready(function() {

			$("#COD_UNIVEND").val(<?= $unidade_default ?>).trigger("chosen:updated");
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			<?php
			///////// produtos/prod. gestão de ofertas //////////
			if (fnDecode(@$_GET['mod']) == 1194) {  ?>
				var codCat = <?php echo $cod_categor; ?>;
				var codSub = <?php echo $cod_subcate; ?>;
				buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);
			<?php } ?>

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}


			$.fn.editable.defaults.mode = 'popup';


			$('#COD_UNIVEND').change(function() {
				unidade = $(this).val();
				$.ajax({
					method: 'POST',
					url: 'ajxCalculaEstoque.php?acao=paginar',
					data: {
						COD_EMPRESA: <?= $cod_empresa ?>,
						COD_UNIVEND: unidade
					},
					beforeSend: function() {
						$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$('#relatorioConteudo').html(data);
					}
				});
			});

			$("#LOG_ATIVO").change(function() {

				var log_ativo = "N";

				if ($(this).prop('checked')) {
					log_ativo = "S";
				}

				$.ajax({
					type: "POST",
					url: "ajxProdutosPromocao.do?opcao=ativo&id=<?php echo fnEncode($cod_empresa); ?>",
					data: {
						LOG_ATIVO: log_ativo
					},
					success: function(data) {
						// $("#divId_sub").html(data); 
					},
					error: function() {
						// $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

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
			$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");

			$("#formulario #DES_DISPONIBILIDADE").val($("#ret_DES_DISPONIBILIDADE_" + index).val()).trigger("chosen:updated");
			$("#formulario #DES_TIPOENTREGA").val($("#ret_DES_TIPOENTREGA_" + index).val()).trigger("chosen:updated");

			var codCat = $("#ret_COD_CATEGOR_" + index).val();
			var codSub = $("#ret_COD_SUBCATE_" + index).val();
			buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

			$("#formulario #NUM_PONTOS").val($("#ret_NUM_PONTOS_" + index).val());
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
			$("#formulario #COD_EAN").val($("#ret_COD_EAN_" + index).val());

			if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
				$('#formulario #LOG_ATIVO').prop('checked', true);
			} else {
				$('#formulario #LOG_ATIVO').prop('checked', false);
			}

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		$('.upload').on('click', function(e) {
			var idField = 'arqUpload_' + $(this).attr('idinput');
			var typeFile = $(this).attr('extensao');

			$.dialog({
				title: 'Arquivo',
				content: '' +
					'<form method = "POST" enctype = "multipart/form-data">' +
					'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
					'<div class="progress" style="display: none">' +
					'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
					'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
					'</div>' +
					'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
					'</form>'
			});
		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxProdutosPromocao.do?opcao=estoque&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idU=" + $('#COD_UNIVEND').val(),
				data: $('#formLista2').serialize(),
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

		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes/');
			formData.append('diretorioAdicional', 'produtospromo');
			formData.append('id', <?php echo $cod_empresa ?>);
			formData.append('typeFile', typeFile);

			$('.progress').show();
			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					$('#btnUploadFile').addClass('disabled');
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							if (percentComplete !== 100) {
								$('.progress-bar').css('width', percentComplete + "%");
								$('.progress-bar > span').html(percentComplete + "%");
							}
						}
					}, false);
					return xhr;
				},
				url: '../uploads/uploaddoc.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {
					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (!data.trim()) {
						$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
						$.alert({
							title: "Mensagem",
							content: "Upload feito com sucesso",
							type: 'green'
						});

					} else {
						$.alert({
							title: "Erro ao efetuar o upload",
							content: data,
							type: 'red'
						});
					}
				}
			});
		}
	</script>