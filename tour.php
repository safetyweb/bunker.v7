<?php

$cod_modulo = fnDecode($_GET['id']);

$hashLocal = mt_rand();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$des_objeto = ($_REQUEST['DES_OBJETO']);
		$cod_tour = fnLimpaCampoZero($_REQUEST['COD_TOUR']);
		$nom_tour = fnLimpaCampo($_REQUEST['NOM_TOUR']);
		$des_dica = addslashes($_REQUEST['DES_DICA']);
		$des_tour = addslashes($_REQUEST['DES_TOUR']);
		$NUM_ORDENAC = fnLimpaCampo($_REQUEST['NUM_ORDENAC']);

		if ($opcao != '') {

			switch ($opcao) {
				case 'CAD':
				$sql = "SELECT IFNULL(MAX(NUM_ORDENAC),0)+1 ORDEM FROM TOUR where COD_MODULOS = 0" . $cod_modulo;
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				$qrModulo = mysqli_fetch_assoc($arrayQuery);
				$NUM_ORDENAC = $qrModulo["ORDEM"];

				$sql = "INSERT INTO TOUR(
					COD_MODULOS,
					DES_OBJETO,
					NOM_TOUR,
					DES_TOUR,
					DES_DICA,
					NUM_ORDENAC
					)VALUES(
					'$cod_modulo',
					'$des_objeto',
					'$nom_tour',
					'$des_tour',
					'$des_dica',
					'$NUM_ORDENAC'
				)";

					//fnEscreve($sql);                             

					mysqli_query($connAdm->connAdm(), trim($sql));

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

					case 'ALT':
					$sql = "UPDATE TOUR SET 
					DES_OBJETO='$des_objeto',
					NOM_TOUR='$nom_tour',
					DES_TOUR='$des_tour',
					DES_DICA='$des_dica',
					NUM_ORDENAC='$NUM_ORDENAC'
					WHERE COD_TOUR=$cod_tour AND COD_MODULOS=$cod_modulo
					";
					// fnEscreve($sql);

					mysqli_query($connAdm->connAdm(), trim($sql));


					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;

					case 'EXC':
					$sql = "DELETE FROM TOUR WHERE COD_TOUR=$cod_tour AND COD_MODULOS=$cod_modulo;";
					//fnEscreve($sql);
					mysqli_query($connAdm->connAdm(), trim($sql));
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
				}
				$msgTipo = 'alert-success';
				unset($_POST);
			}
		}
	}

//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
		$sql = "SELECT * FROM modulos where COD_MODULOS = 0" . $cod_modulo;
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrModulo = mysqli_fetch_assoc($arrayQuery);
		$des_modulo = $qrModulo['DES_MODULOS'];
		$cod_modulo = $qrModulo['COD_MODULOS'];

		if (@$qrModulo["COD_MODULOS"] == "") {
			$url = "action.php?mod=" . fnEncode(1775);
			header("location: " . $url);
			echo "<script>window.location='" . $url . "';</script>";
			exit;
		}
	} else {
		$url = "action.php?mod=" . fnEncode(1775);
		header("location: " . $url);
		echo "<script>window.location='" . $url . "';</script>";
		exit;
	}

	$file = __DIR__;
	if ($qrModulo["TIP_MODULOS"] == "2") {
		$file .= "/relatorios";
	}
	$file .= "/" . $qrModulo["DES_COMMAND"];
	if (!file_exists($file)) {
		echo "Arquivo $file não encontrado!";
		exit;
	}
	$html = file_get_contents($file);

	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$xp = new DOMXPath($dom);
	$ids = $xp->query("//@id");
	$idList = [];
	foreach ($ids as $id) {
		$idList[] = $id->nodeValue;
	}

	$idList = array_unique($idList);

	$ids = $xp->query("//@class");
	$classList = [];
	foreach ($ids as $id) {
		$classList = array_merge($classList, explode(" ", $id->nodeValue));
	}

	$classList = array_unique($classList);

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

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?= $cod_modulo ?> - <?php echo @$qrModulo["DES_MODULOS"] ?></span>
					</div>

				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php

					$abaTour = fnDecode($_GET['mod']);

					echo ('<div class="push20"></div>');
					include "abasTour.php";

					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="push20"></div>

							<div class="row" style="display: flex;align-items: stretch;">

								<div class="col-md-5">

									<fieldset>
										<legend>Elementos na Página</legend>

										<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->

										<!-- <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>"> -->

											<div class="col-md-10 col-md-offset-1 col-xs-12">
												<div class="push20"></div>

												<div class="input-group activeItem">
													<div class="input-group-btn search-panel">
														<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
															<span id="search_concept">Sem filtro</span>&nbsp;
															<span class="far fa-angle-down"></span>
														</button>
														<ul class="dropdown-menu" role="menu">
															<li class="divisor"><a href="#">Sem filtro</a></li>
															<!-- <li class="divider"></li> -->
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

											<!-- <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	 -->
											<!-- <input type="hidden" name="COD_SISTEMAS" id="COD_SISTEMAS" value="" /> -->
											<!-- <input type="hidden" name="hHabilitado" id="hHabilitado" value="S"> -->

											<!-- </form> -->


											<div class="push30"></div>

											<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

											<div style="overflow: auto;height: 304px;">

												<table class="table table-bordered table-hover table-sortable buscavel" style="width: 100%;">
													<tbody>
														<?php
														function add_linha($item, $tp)
														{
															if (
																trim($item) == ""
																|| strripos($item, "<") !== false
																|| strripos($item, ">") !== false
																|| strripos($item, "$") !== false
																|| trim($item) == "echo"
															) {
																return false;
															}
															$badge = "";
															if ($tp == "id") {
																$simb = "#";
														//$badge = "<span class='label bg-info'>ID</span>";
															} elseif ($tp == "class") {
																$simb = ".";
														//$badge = "<span class='label bg-warning'>CLASS</span>";
															}
															echo "<tr>";
															echo "<td><a href='javascript:' onClick=\"sel_obj('" . $simb . $item . "');\"> " . $simb . $item . " " . $badge . "</a></td>";
															echo "<td><a href='javascript:' onClick=\"sel_obj('" . $simb . $item . "');\" data-el-id='" . $item . "'></a></td>";
															echo "</tr>";
														}
														foreach ($idList as $item) {
															add_linha($item, "id");
														}
														foreach ($classList as $item) {
													//add_linha($item,"class");
														}
														?>
													</tbody>
												</table>

											</div>
											<div class="row" style='margin:20px'>
												<div class="col-md-6"></div>
												<div class="col-md-6">
													<a class="btn btn-info btn-block addBox" data-url="action.do?mod=<?php echo fnEncode(2003)?>&codMod=<?=fnEncode($cod_modulo)?>&pop=true" data-title="Clip Help / <?php echo $des_modulo; ?>"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i>Adicionar Clippy Help</a>
												</div>
											</div>
												
										</fieldset>

									</div>
									<div class="col-md-7">
										<fieldset>
											<legend>Dados</legend>

											<div class="row">

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label required">Código</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TOUR" id="COD_TOUR" value="">
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Elemento</label>
														<input type='text' name='DES_OBJETO' id='DES_OBJETO' class="form-control input-sm" value="<?= @$_POST["DES_OBJETO"] ?>" required />
													</div>
												</div>
												<div class="col-md-7">
													<div class="form-group">
														<label for="inputName" class="control-label required">Título</label>
														<input type='text' name='NOM_TOUR' id='NOM_TOUR' class="form-control input-sm" value="<?= @$_POST["NOM_TOUR"] ?>" required />
													</div>
												</div>

											</div>

											<div class="push10"></div>

											<div class="row">

												<div class="col-lg-12">
													<div class="form-group">
														<label for="inputName" class="control-label required">Descrição: </label>
														<textarea class="editor form-control input-sm" rows="6" name="DES_TOUR" id="DES_TOUR" required><?= @$_POST["DES_TOUR"] ?></textarea>
														<div class="help-block with-errors"></div>
													</div>
												</div>

											</div>

											<div class="push10"></div>

											<div class="row">

												<div class="col-md-12">
													<div class="form-group">
														<label for="inputName" class="control-label">Dica</label>
														<input type='text' name='DES_DICA' id='DES_DICA' class="form-control input-sm" value="<?= @$_POST["DES_DICA"] ?>" />
													</div>
												</div>

											</div>

											<div class="push10"></div>

										</fieldset>
									</div>
								</div>

								<div class="push10"></div>
								<hr>
								<div class="form-group text-right col-lg-12">

									<a class="btn btn-default" href="action.php?mod=<?= fnEncode(1775) ?>"><i class="fal fas fa-arrow-left"></i>&nbsp; Voltar</a>
									<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

								</div>

								<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

								<div class="push5"></div>

							</form>
						</div>
					</div>
				</div>

				<div class="push30"></div>

				<div class="portlet portlet-bordered">
					<div class="portlet-body">

						<div class="login-form">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<table id="table" class="table table-bordered table-hover table-sortable tablesorter">

										<thead>
											<tr>
												<th class='{ sorter: false } text-center' width="40"></th>
												<th class="{sorter:false}"></th>
												<th><small>ID</small></th>
												<th><small>Objeto</small></th>
												<th><small>Título</small></th>
										<?php /*
									  <th class="{sorter:false}"></th>
									  */ ?>
									</tr>
								</thead>

								<tbody>

									<?php

									$sql = "select * from TOUR WHERE COD_MODULOS='$cod_modulo' order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

									$count = 0;
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										echo "
										<tr>
										<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBusca['COD_TOUR'] . "'></span></td>
										<td class='text-center' ><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
										<td>" . $qrBusca['COD_TOUR'] . "</td>
										<td>" . $qrBusca['DES_OBJETO'] . "</td>
										<td>" . $qrBusca['NOM_TOUR'] . "</td>
										<td>" . $qrBusca['NUM_ORDENAC'] . "</td>
										</tr>
										<input type='hidden' id='ret_COD_TOUR_" . $count . "' value='" . $qrBusca['COD_TOUR'] . "'>
										<input type='hidden' id='ret_DES_OBJETO_" . $count . "' value='" . $qrBusca['DES_OBJETO'] . "'>
										<input type='hidden' id='ret_NOM_TOUR_" . $count . "' value='" . $qrBusca['NOM_TOUR'] . "'>
										<input type='hidden' id='ret_DES_TOUR_" . $count . "' value='" . $qrBusca['DES_TOUR'] . "'>
										<input type='hidden' id='ret_DES_DICA_" . $count . "' value='" . $qrBusca['DES_DICA'] . "'>
										<input type='hidden' id='ret_NUM_ORDEM_" . $count . "' value='" . $qrBusca['NUM_ORDENAC'] . "'>
										";
									}

									?>

								</tbody>

							</table>

						</div>

					</div>
				</div>

				<div class="push50"></div>

				<div class="push"></div>

			</div>

		</div>
		<!-- fim Portlet -->
	</div>

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


<style>
	.jqte {
		border: #dce4ec 2px solid !important;
		border-radius: 3px !important;
		-webkit-border-radius: 3px !important;
		box-shadow: 0 0 2px #dce4ec !important;
		-webkit-box-shadow: 0 0 0px #dce4ec !important;
		-moz-box-shadow: 0 0 3px #dce4ec !important;
		transition: box-shadow 0.4s, border 0.4s;
		margin-top: 0px !important;
		margin-bottom: 0px !important;
	}

	.jqte_toolbar {
		background: #fff !important;
		border-bottom: none !important;
	}

	.jqte_focused {
		/*border: none!important;*/
		box-shadow: 0 0 3px #00BDFF;
		-webkit-box-shadow: 0 0 3px #00BDFF;
		-moz-box-shadow: 0 0 3px #00BDFF;
	}

	.jqte_titleText {
		border: none !important;
		border-radius: 3px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		word-wrap: break-word;
		-ms-word-wrap: break-word
	}

	.jqte_tool,
	.jqte_tool_icon,
	.jqte_tool_label {
		border: none !important;
	}

	.jqte_tool_icon:hover {
		border: none !important;
		box-shadow: 1px 5px #EEE;
	}
</style>
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


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
	$(function() {

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
		});

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						if ($(this).children().find('span.fa-equals').attr('data-id') !== undefined)
							Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
					}
				});
				console.log(Ids);
				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 21);

				function execOrdenacao(p1, p2) {
					//alert(p2);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacao.php",
						data: {
							ajx1: p1,
							ajx2: p2
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// $("#divId_sub").html(data);
							console.log(data);
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});

		$(".table-sortable tbody").disableSelection();

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

	});

	function sel_obj(el) {
		$("#DES_OBJETO").val(el);

		let titulo = $(`[data-el-id='${el.replace("#","")}']`).html();
		$("#NOM_TOUR").val(titulo);
	}

	function retornaForm(index) {
		$("#formulario #COD_TOUR").val($("#ret_COD_TOUR_" + index).val());
		$("#formulario #DES_OBJETO").val($("#ret_DES_OBJETO_" + index).val());
		$("#formulario #NOM_TOUR").val($("#ret_NOM_TOUR_" + index).val());
		$("#formulario #DES_TOUR").val($("#ret_DES_TOUR_" + index).val());
		$("#formulario #DES_DICA").val($("#ret_DES_DICA_" + index).val());
		$("#formulario .editor").jqteVal($("#ret_DES_TOUR_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDEM_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}



	<?php
	$html = str_replace("script", "", $html);
	$html = str_replace("style", "", $html);
	$html = str_replace("'", '"', $html);
	$html = str_replace("\\", "", $html);
	?>
	var html = "";
	$(document).ready(function(e) {
		html = `<?= $html ?>`;

		//Busca de informações no HTML
		$("[data-el-id]").each(function(index, element) {
			let el_id = `#${$(element).attr("data-el-id")}`;
			let label = $(html).find(el_id).parent().find("label").html();
			if ($(html).find(el_id).parent().find("label").length == 1) {
				$(element).html(label.replace(/<[^>]*>/g, ''));
				return;
			}

			label = $(html).find(el_id).parent().parent().find("label").html();
			if ($(html).find(el_id).parent().parent().find("label").length == 1) {
				$(element).html(label.replace(/<[^>]*>/g, ''));
				return;
			}

			if ($(html).find(el_id).parent().hasClass("switch")) {
				label = $(html).find(el_id).parent().parent().find("label").html();
				$(element).html(label.replace(/<[^>]*>/g, ''));
				return;
			}


		});
	});
</script>