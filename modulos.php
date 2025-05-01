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
$cod_modulos = "";
$des_modulos = "";
$nom_modulos = "";
$des_command = "";
$log_autoriza = "";
$log_acessos = "";
$log_sensivel = "";
$des_tabelas = "";
$des_procedu = "";
$des_icones = "";
$cod_destino = "";
$des_observa = "";
$tip_modulos = "";
$filtro = "";
$val_pesquisa = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$esconde = "";
$arrayQuery = [];
$qrListaEempresas = "";
$qrListaTipoModulos = "";
$qrBuscaModulos = "";
$mostraRestrito = "";
$mostraPublico = "";
$mostraAutoriza = "";
$mostraAcessos = "";
$mostraSensivel = "";
$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;


		$cod_modulos = (int) @$_REQUEST['ID'];
		$des_modulos = @$_REQUEST['DES_MODULOS'];
		$nom_modulos = @$_REQUEST['NOM_MODULOS'];
		$des_command = @$_REQUEST['DES_COMMAND'];
		if (empty(@$_REQUEST['LOG_AUTORIZA'])) {
			$log_autoriza = 'N';
		} else {
			$log_autoriza = @$_REQUEST['LOG_AUTORIZA'];
		}
		if (empty(@$_REQUEST['LOG_ACESSOS'])) {
			$log_acessos = 'N';
		} else {
			$log_acessos = @$_REQUEST['LOG_ACESSOS'];
		}
		if (empty(@$_REQUEST['LOG_SENSIVEL'])) {
			$log_sensivel = 'N';
		} else {
			$log_sensivel = @$_REQUEST['LOG_SENSIVEL'];
		}
		$des_tabelas = @$_REQUEST['DES_TABELAS'];
		$des_procedu = @$_REQUEST['DES_PROCEDU'];
		$des_icones = @$_REQUEST['DES_ICONES'];
		if (empty(@$_REQUEST['COD_DESTINO'])) {
			$cod_destino = 'NULL';
		} else {
			$cod_destino = @$_REQUEST['COD_DESTINO'];
		}
		$des_observa = @$_REQUEST['DES_OBSERVA'];
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$tip_modulos = fnLimpacampo(@$_REQUEST['TIP_MODULOS']);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_MODULOS (
				 '" . $cod_modulos . "', 
				 '" . $des_modulos . "', 
				 '" . $des_command . "', 
				 '" . $log_autoriza . "', 
				 '" . $des_tabelas . "', 
				 '" . $des_procedu . "', 
				 '" . $des_observa . "', 
				 '" . $nom_modulos . "', 
				  $cod_destino, 
				 '" . $opcao . "', 
				 '" . $des_icones . "',    
				 '" . $log_acessos . "',
				 '" . $log_sensivel . "',
				 '" . $cod_empresa . "',    
				 '" . $tip_modulos . "'    
				) ";

			//echo $sql;				

			$arrayProc = mysqli_query($adm, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}

if (@$val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

//fnMostraForm();

//fnEscreve(fnDecode('AkBvpjcw5Vo¢'));

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

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Módulo</label>
										<input type="text" class="form-control input-sm" name="DES_MODULOS" id="DES_MODULOS" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Aliás</label>
										<input type="text" class="form-control input-sm" name="NOM_MODULOS" id="NOM_MODULOS" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Página (command)</label>
										<input type="text" class="form-control input-sm" name="DES_COMMAND" id="DES_COMMAND" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tabela Principal</label>
										<input type="text" class="form-control input-sm" name="DES_TABELAS" id="DES_TABELAS" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Procedure Principal</label>
										<input type="text" class="form-control input-sm" name="DES_PROCEDU" id="DES_PROCEDU" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label>
										<input type="text" class="form-control input-sm" name="DES_ICONES" id="DES_ICONES" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Módulo Destino</label>
										<input type="text" class="form-control input-sm" name="COD_DESTINO" id="COD_DESTINO" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php

											$sql = "select COD_EMPRESA, NOM_EMPRESA from empresas where COD_EMPRESA IN (1,2,3) order by NOM_EMPRESA";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaEempresas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
													<option value='" . $qrListaEempresas['COD_EMPRESA'] . "'>" . $qrListaEempresas['NOM_EMPRESA'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Tela</label>

										<select data-placeholder="Selecione uma empresa" name="TIP_MODULOS" id="TIP_MODULOS" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php
											$sql = "select * from TIPOMODULOS order by DES_TPMODULOS ";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaTipoModulos = mysqli_fetch_assoc($arrayQuery)) {

												echo "
													<option value='" . $qrListaTipoModulos['COD_TPMODULOS'] . "'>" . $qrListaTipoModulos['DES_TPMODULOS'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Exige Autorização</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_AUTORIZA" id="LOG_AUTORIZA" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Permite Acesso Direto</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ACESSOS" id="LOG_ACESSOS" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Dados Sens&iacute;veis</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_SENSIVEL" id="LOG_SENSIVEL" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Informações Adicionais</legend>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Observações</label>
										<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="100"></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

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
											<!-- <li><a href="#DES_PRODUTO">Nome do Produto</a></li>
											                    <li><a href="#COD_EXTERNO">Código Externo</a></li> -->
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

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
									<thead>
										<tr>
											<th class="{ sorter: false }"></th>
											<th>Código</th>
											<th>Acesso</th>
											<th>Nome do Menu</th>
											<th>Aliás</th>
											<th>Página</th>
											<th class="{ sorter: false }">Público</th>
											<th class="{ sorter: false }">Autoriz.</th>
											<th class="{ sorter: false }">Direto</th>
											<th class="{ sorter: false }">Sens.</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from modulos order by DES_MODULOS";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaModulos['COD_EMPRESA'] >= 2) {
												$mostraRestrito = '<i class="fal fa-user" aria-hidden="true"></i>';
											} else {
												$mostraRestrito = '';
											}
											if ($qrBuscaModulos['COD_EMPRESA'] == 1) {
												$mostraPublico = '<i class="fal fa-users" aria-hidden="true"></i>';
											} else {
												$mostraPublico = '';
											}
											if ($qrBuscaModulos['LOG_AUTORIZA'] == 'S') {
												$mostraAutoriza = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAutoriza = '';
											}
											if ($qrBuscaModulos['LOG_ACESSOS'] == 'S') {
												$mostraAcessos = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAcessos = '';
											}
											if ($qrBuscaModulos['LOG_SENSIVEL'] == 'S') {
												$mostraSensivel = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraSensivel = '';
											}


											echo "
													<tr>
														<td class='text-center' ><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_MODULOS'] . "</td>
														<td><a href='action.php?mod=" . fnEncode($qrBuscaModulos['COD_MODULOS']) . "' >" . fnEncode($qrBuscaModulos['COD_MODULOS']) . "</a></td>
														<td>" . $qrBuscaModulos['DES_MODULOS'] . "</td>
														<td>" . $qrBuscaModulos['NOM_MODULOS'] . "</td>
														<td>" . $qrBuscaModulos['DES_COMMAND'] . "</td>
														<td align='center'>" . $mostraPublico . $mostraRestrito . "</td>
														<td align='center'>" . $mostraAutoriza . "</td>
														<td align='center'>" . $mostraAcessos . "</td>
														<td align='center'>" . $mostraSensivel . "</td>
													</tr>
													<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrBuscaModulos['COD_MODULOS'] . "'>
													<input type='hidden' id='ret_DES_MODULOS_" . $count . "' value='" . $qrBuscaModulos['DES_MODULOS'] . "'>
													<input type='hidden' id='ret_NOM_MODULOS_" . $count . "' value='" . $qrBuscaModulos['NOM_MODULOS'] . "'>
													<input type='hidden' id='ret_DES_COMMAND_" . $count . "' value='" . $qrBuscaModulos['DES_COMMAND'] . "'>
													<input type='hidden' id='ret_LOG_AUTORIZA_" . $count . "' value='" . $qrBuscaModulos['LOG_AUTORIZA'] . "'>
													<input type='hidden' id='ret_LOG_ACESSOS_" . $count . "' value='" . $qrBuscaModulos['LOG_ACESSOS'] . "'>
													<input type='hidden' id='ret_LOG_SENSIVEL_" . $count . "' value='" . $qrBuscaModulos['LOG_SENSIVEL'] . "'>
													<input type='hidden' id='ret_DES_TABELAS_" . $count . "' value='" . $qrBuscaModulos['DES_TABELAS'] . "'>
													<input type='hidden' id='ret_DES_PROCEDU_" . $count . "' value='" . $qrBuscaModulos['DES_PROCEDU'] . "'>
													<input type='hidden' id='ret_DES_OBSERVA_" . $count . "' value='" . $qrBuscaModulos['DES_OBSERVA'] . "'>
													<input type='hidden' id='ret_DES_ICONES_" . $count . "' value='" . $qrBuscaModulos['DES_ICONES'] . "'>
													<input type='hidden' id='ret_COD_DESTINO_" . $count . "' value='" . $qrBuscaModulos['COD_DESTINO'] . "'>
													<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
													<input type='hidden' id='ret_TIP_MODULOS_" . $count . "' value='" . $qrBuscaModulos['TIP_MODULOS'] . "'>
													";
										}

										?>

									</tbody>
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	function retornaForm(index) {
		$("#formulario #ID").val($("#ret_ID_" + index).val());
		$("#formulario #DES_MODULOS").val($("#ret_DES_MODULOS_" + index).val());
		$("#formulario #NOM_MODULOS").val($("#ret_NOM_MODULOS_" + index).val());
		$("#formulario #DES_COMMAND").val($("#ret_DES_COMMAND_" + index).val());
		if ($("#ret_LOG_AUTORIZA_" + index).val() == 'S') {
			$('#formulario #LOG_AUTORIZA').prop('checked', true);
		} else {
			$('#formulario #LOG_AUTORIZA').prop('checked', false);
		}
		if ($("#ret_LOG_ACESSOS_" + index).val() == 'S') {
			$('#formulario #LOG_ACESSOS').prop('checked', true);
		} else {
			$('#formulario #LOG_ACESSOS').prop('checked', false);
		}
		if ($("#ret_LOG_SENSIVEL_" + index).val() == 'S') {
			$('#formulario #LOG_SENSIVEL').prop('checked', true);
		} else {
			$('#formulario #LOG_SENSIVEL').prop('checked', false);
		}
		$("#formulario #DES_TABELAS").val($("#ret_DES_TABELAS_" + index).val());
		$("#formulario #DES_PROCEDU").val($("#ret_DES_PROCEDU_" + index).val());
		$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_" + index).val());
		$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
		$("#formulario #COD_DESTINO").val($("#ret_COD_DESTINO_" + index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_MODULOS").val($("#ret_TIP_MODULOS_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>
<?php

?>