<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_geral = "";
$msgRetorno = "";
$msgTipo = "";
$cod_produto = "";
$filtro = "";
$val_pesquisa = "";
$cod_usucada = "";
$cod_formapa = "";
$hHabilitado = "";
$hashForm = "";
$sqlRes = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_blklist = "";
$esconde = "";
$popUp = "";
$formBack = "";
$abaCampanhas = "";
$abaCli = "";
$cod_campanha = "";
$andFiltro = "";
$retorno = "";
$inicio = "";
$countLinha = "";
$qrBuscaProdutoHab = "";

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$hashLocal = mt_rand();

$cod_geral = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$cod_formapa = 0;

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sqlRes = "INSERT INTO PRODUTO_SEM_RESGATE(
											COD_EMPRESA,
											COD_PRODUTO,
											COD_USUCADA
										 ) VALUES(
										 	$cod_empresa,
										 	$cod_produto,
										 	$cod_usucada
										 );";

				$sqlRes .= "UPDATE PRODUTOCLIENTE SET
								LOG_NRESGATE = 'S' 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_PRODUTO = $cod_produto; ";

				mysqli_multi_query(connTemp($cod_empresa, ""), trim($sqlRes));
			} else if ($opcao == 'EXC') {

				$sqlRes = "DELETE FROM PRODUTO_SEM_RESGATE
							   WHERE COD_EMPRESA = $cod_empresa
							   AND COD_PRODUTO = $cod_produto; ";

				$sqlRes .= "UPDATE PRODUTOCLIENTE SET
								LOG_NRESGATE = 'N' 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_PRODUTO = $cod_produto; ";

				mysqli_multi_query(connTemp($cod_empresa, ""), trim($sqlRes));
			}

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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
}

$cod_blklist = fnDecode(@$_GET['idB']);

//fnEscreve(fnDecode(@$_GET['idB']));
//fnMostraForm();

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

?>


<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1048";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
					<div class="push10"></div>
				<?php } ?>

				<?php
				if ($_SESSION['SYS_COD_EMPRESA'] != 3 && $_SESSION['SYS_COD_EMPRESA'] != 2) {
				?>
					<div class="alert alert-warning top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Opções de preenchimento e alterações são <b>exclusivas do seu consultor</b>. <br />
						Entre em contato para mais informações.
					</div>
				<?php } ?>

				<?php $abaCampanhas = 1022;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<?php $abaCli = 1612;
				include "abasRegrasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados do Produto Para Exclusão</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRODHAB" id="COD_PRODHAB" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-7">
									<label for="inputName" class="control-label required">Produto </label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Hábitos de Exclusão - Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
										<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<!--<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>-->
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="COD_BLKLIST" id="COD_BLKLIST" value="<?php echo $cod_blklist; ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

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
											<li><a href="#PRD.DES_PRODUTO">Nome do produto</a></li>
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

					<!-- modal -->
					<div class="modal fade" id="popModalAux" tabindex='-1'>
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

					<div id="div_Ordena"></div>

					<div>
						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Cód. Produto</th>
												<th>Cód. Externo</th>
												<th>Nome do Produto</th>
												<th>Data Inclusão</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php

											if ($filtro != "") {
												$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
											} else {
												$andFiltro = " ";
											}

											$sql = "SELECT count(*) AS CONTADOR FROM PRODUTO_SEM_RESGATE
																  WHERE COD_EMPRESA = $cod_empresa";


											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql = "SELECT PRD.DES_PRODUTO, 
																		   PRD.COD_EXTERNO, 
																		   PSR.COD_PRODUTO, 
																		   PSR.DAT_CADASTR
																    FROM PRODUTO_SEM_RESGATE PSR
																    INNER JOIN PRODUTOCLIENTE PRD ON PRD.COD_PRODUTO = PSR.COD_PRODUTO
																    WHERE PSR.COD_EMPRESA = $cod_empresa
																	$andFiltro 
																	ORDER BY TRIM(PRD.DES_PRODUTO) 
																	LIMIT $inicio,$itens_por_pagina";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$countLinha = 1;
											while ($qrBuscaProdutoHab = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												echo "
																	<tr>
																	  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
																	  <td>" . $qrBuscaProdutoHab['COD_PRODUTO'] . "</td>
																	  <td>" . $qrBuscaProdutoHab['COD_EXTERNO'] . "</td>
																	  <td>" . $qrBuscaProdutoHab['DES_PRODUTO'] . "</td>
																	  <td>" . fnDataFull($qrBuscaProdutoHab['DAT_CADASTR']) . "</td>
																	</tr>
																	<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaProdutoHab['COD_PRODUTO'] . "'>
																	<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaProdutoHab['DES_PRODUTO'] . "'>
																	";

												$countLinha++;
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
					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script>
	$(document).ready(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

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


	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxBlackListTicketProd.php?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&cod_blklist=<?php echo $cod_blklist; ?>",
			data: $('#formLista2').serialize(),
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


	function retornaForm(index) {

		$("#formulario #COD_PRODHAB").val($("#ret_COD_PRODHAB_" + index).val());
		$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
		$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>