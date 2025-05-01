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
$cod_blklist = "";
$num_celular = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$registros = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$esconde = "";
$andFiltro = "";
$popUp = "";
$abaListaEmails = "";
$sqlCount = "";
$retorno = "";
$inicio = "";
$qrBlklist = "";


$hashLocal = mt_rand();

$itens_por_pagina = 15;
$pagina  = "1";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_blklist = fnLimpaCampoZero(@$_REQUEST['COD_BLKLIST']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$num_celular = fnLimpaCampo(fnLimpaDoc(ltrim(@$_REQUEST['NUM_CELULAR'], '+')));

		// - variáveis da barra de pesquisa -------------
		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);
		// ----------------------------------------------

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "SELECT COD_BLKLIST FROM BLACKLIST_SMS
								WHERE NUM_CELULAR = '$num_celular'";

					$registros = mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), trim($sql)));

					if ($registros > 0) {

						$msgRetorno = "Registro <strong>já existente</strong> na base de dados.";
						$msgTipo = 'alert-warning';
					} else {

						$sql = "INSERT INTO BLACKLIST_SMS(
												COD_EMPRESA,
												NUM_CELULAR,
												COD_USUCADA
											) VALUES(
												'$cod_empresa',
												'$num_celular',
												'$cod_usucada'
											)";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa, ''), trim($sql));

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';
					}
					break;
				case 'ALT':

					$sql = "UPDATE BLACKLIST_SMS SET
									   NUM_CELULAR = '$num_celular'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_BLKLIST = $cod_blklist";

					//echo $sql;

					mysqli_query(connTemp($cod_empresa, ''), trim($sql));

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';
					break;
				case 'EXC':

					$sql = "DELETE FROM BLACKLIST_SMS
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_BLKLIST = $cod_blklist";

					//echo $sql;

					mysqli_query(connTemp($cod_empresa, ''), trim($sql));

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';
					break;
					break;
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_campanha = fnDecode(@$_GET['idc']);
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
	//fnEscreve('entrou else');
}

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != '') {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}
// ---------------------------------------------

// filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
if ($filtro != '') {
	$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
} else {
	$andFiltro = " ";
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------

// fnEscreve($cod_empresa);
// fnEscreve($cod_campanha);

//fnMostraForm();

?>

<style>
	body {
		overflow: hidden;
	}
</style>

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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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
					<?php }
					$abaListaEmails = 1672;
					include "abasListaSms.php";
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="18" required>
										<div class="help-block with-errors">Com código do país + DDD</div>
									</div>
								</div>

							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="FILTROS" id="FILTROS" value="<?= fnEncode($andFiltro) ?>">
							<input type="hidden" name="COD_BLKLIST" id="COD_BLKLIST" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
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
												<li><a href="#NUM_CELULAR">Celular</a></li>
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>

								<!-- <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							 -->
								<!-- <input type="hidden" name="hHabilitado" id="hHabilitado" value="S"> -->

							</form>

						</div>

						<div class="push30"></div>

						<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tableSorter buscavel">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Celular</th>
												<th>Dt. Cadastro</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php



											$sqlCount = "SELECT COD_BLKLIST FROM BLACKLIST_SMS 
																 WHERE COD_EMPRESA = $cod_empresa
																 $andFiltro";
											//fnEscreve($sqlCount);

											$retorno =  mysqli_query(connTemp($cod_empresa, ''), trim($sqlCount));
											$total_itens_por_pagina = mysqli_num_rows($retorno);

											$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

											// fnEscreve($numPaginas);	

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql = "SELECT * FROM BLACKLIST_SMS 
															WHERE COD_EMPRESA = $cod_empresa
															$andFiltro
															ORDER BY DAT_CADASTR DESC
															LIMIT $inicio,$itens_por_pagina";


											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

											$count = 0;
											while ($qrBlklist = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBlklist['NUM_CELULAR'] . "</td>
															  <td>" . fnDataShort($qrBlklist['DAT_CADASTR']) . "</td>
															</tr>
															<input type='hidden' id='ret_COD_BLKLIST_" . $count . "' value='" . $qrBlklist['COD_BLKLIST'] . "'>
															<input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrBlklist['NUM_CELULAR'] . "'>
															";
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
											<th colspan="100">
												<a class="btn btn-info btn-sm" onclick="parent.exportaLista('black',0)" value="N"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar </a>
											</th>
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
		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e) {
			parent.$("#conteudoAba").css("height", ($(".portlet").height() + 50) + "px");
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

		$(function() {

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}

			var SPMaskBehavior = function(val) {
					return '(00) 00000-0000';
				},
				spOptions = {
					onKeyPress: function(val, e, field, options) {
						field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};

			$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxBlackListSms.do?opcao=paginar&id=<?= fnEncode($cod_empresa) ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
					$(".tablesorter").trigger("updateAll");
					// carregaContador(idPage);								
				},
				error: function(data) {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					$("#relatorioConteudo").html(data);
				}
			});
		}

		function retornaForm(index) {
			$("#formulario #COD_BLKLIST").val($("#ret_COD_BLKLIST_" + index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>