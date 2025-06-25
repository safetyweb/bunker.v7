<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	fnEscreve("Debug Ativado");
}

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";
$cod_usures = "";
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$dat_fim_ent = '';
$dat_ini_ent = '';
$cod_externo = "";
$cod_empresa = "";
$nom_chamado = "";
$cod_usuario = '';
$cod_tpsolicitacao = "";
$cod_status = "";
$cod_status_exc = "10,6";
$cod_tipo_exc = "21";
$cod_integradora = "";
$cod_plataforma = "";
$cod_versaointegra = "";
$cod_prioridade = "";
$cod_chamado = '';
$andFiltro = '';
$log_esteira = "";
//$log_esteira = "S";
//$_POST['LOG_ESTEIRA']=$log_esteira;

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SESSION['SYS_COD_EMPRESA'] != 2 && $_SESSION['SYS_COD_EMPRESA'] != 3) {
?>
	<script type="text/javascript">
		window.location.replace("https://adm.bunker.mk/action.do?mod=<?= fnEncode(1280) ?>&id=<?= fnEncode($_SESSION[SYS_COD_EMPRESA]) ?>");
	</script>
<?php
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		//print_r($_POST);

		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
		$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
		$cod_chamado = $_POST['COD_CHAMADO'];
		$cod_externo = $_POST['COD_EXTERNO'];
		$cod_empresa = $_POST['COD_EMPRESA'];
		$nom_chamado = $_POST['NOM_CHAMADO'];
		$log_esteira = @$_POST['LOG_ESTEIRA'];

		$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
		$cod_status = $_POST['COD_STATUS'];
		// $cod_status_exc = $_POST['COD_STATUS_EXC'];
		$cod_integradora = $_POST['COD_INTEGRADORA'];
		$cod_plataforma = $_POST['COD_PLATAFORMA'];
		$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
		$cod_prioridade = $_POST['COD_PRIORIDADE'];
		$cod_usuario = $_POST['COD_USUARIO'];
		$cod_usures = $_POST['COD_USURES'];

		if (isset($_POST['COD_STATUS_EXC'])) {
			$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
			$cod_status_exc = "";

			for ($i = 0; $i < count($Arr_COD_STATUS_EXC); $i++) {
				$cod_status_exc = $cod_status_exc . $Arr_COD_STATUS_EXC[$i] . ",";
			}

			$cod_status_exc = rtrim($cod_status_exc, ',');
		} else {
			$cod_status_exc = "0";
		}

		if (isset($_POST['COD_TIPO_EXC'])) {
			$Arr_COD_TIPO_EXC = $_POST['COD_TIPO_EXC'];
			$cod_tipo_exc = "";

			for ($i = 0; $i < count($Arr_COD_TIPO_EXC); $i++) {
				$cod_tipo_exc = $cod_tipo_exc . $Arr_COD_TIPO_EXC[$i] . ",";
			}

			$cod_tipo_exc = rtrim($cod_tipo_exc, ',');
		} else {
			$cod_tipo_exc = "0";
		}


		// fnEscreve($cod_status_exc);
		// fnEscreve($dat_fim_ent);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {


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

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (strlen($dat_ini_ent) == 0 || $dat_ini_ent == "1969-12-31") {
	$dat_ini_ent = fnDataSql($dias30);
}
if (strlen($dat_fim_ent) == 0 || $dat_fim_ent == "1969-12-31") {
	$dat_fim_ent = "";
}

if (isset($_GET['x'])) {
	$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
	$msgTipo = 'alert-success';
}

//fnEscreve($cod_empresa);	
//fnEscreve($nom_empresa);	

//fnMostraForm('#formulario');



?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> <small>(Interno)</small></span>
				</div>

				<?php
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

				<?php $abaInfoSuporte = 1282;
				include "abasInfoSuporte.php";  ?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros para Busca</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código do Chamado</label>
										<input type="text" class="form-control input-sm" name="COD_CHAMADO" id="COD_CHAMADO" maxlength="45" value="<?php echo $cod_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Empresa</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a empresa" name="COD_EMPRESA" id="COD_EMPRESA">
											<option value=""></option>
											<?php

											//$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS";
											if ($_SESSION["SYS_COD_MASTER"] == "2") {
												$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE empresas.COD_EMPRESA <> 1 
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
												//fnEscreve("1");
											} else {
												$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
												//fnEscreve("2");
											}

											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

											while ($qrEmpresa = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrEmpresa['COD_EMPRESA']; ?>"><?php echo $qrEmpresa['NOM_FANTASI']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Título do Chamado</label>
										<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo de Solicitação</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM SAC_TPSOLICITACAO";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

											while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Status</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM SAC_STATUS";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

											while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Integradora</label>
										<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											$sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

											while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																				  <option value='" . $qrListaIntegradora['COD_EMPRESA'] . "'>" . $qrListaIntegradora['NOM_FANTASI'] . "</option>
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Plataforma</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM SAC_PLATAFORMA";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

											while ($qrPlataforma = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrPlataforma['COD_PLATAFORMA']; ?>"><?php echo $qrPlataforma['DES_PLATAFORMA']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Versão da Integração</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM SAC_VERSAOINTEGRA";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

											while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Prioridade</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
											<option value=""></option>
											<?php

											$sql = "SELECT COD_PRIORIDADE, ABV_PRIORIDADE FROM SAC_PRIORIDADE";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

											while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['ABV_PRIORIDADE']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Responsável</label>
										<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;">
											<option value=""></option>
											<option value="">Todos os Responsáveis</option>
											<option value="0">Sem Responsável</option>
											<optgroup label="Usuários Marka">
												<?php

												$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																		where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
																		and usuarios.DAT_EXCLUSA is null 
																		AND COD_TPUSUARIO IN(9,6,1,3) 
																		AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																			  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																			";
												}
												?>
											</optgroup>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Solicitante</label>
										<div id="relatorioUsu">
											<select data-placeholder="Usuários Marka" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" style="width:100%;">

											</select>
										</div>
										<div class="help-block with-errors">requisito: selecionar empresa</div>
									</div>
								</div>

							</div>

							<div class="row">



								<div class="col-md-4">

									<fieldset>
										<legend>Data de Cadastro</legend>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</fieldset>

								</div>

								<div class="col-md-4">

									<fieldset>
										<legend>Prazo</legend>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI_ENT" id="DAT_INI_ENT" value="" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM_ENT" id="DAT_FIM_ENT" value="<?php echo fnFormatDate($dat_fim_ent); ?>" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</fieldset>

								</div>

								<div class="col-md-2" style="padding: 0">

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Remover Status</label>
											<select data-placeholder="Selecione o status" name="COD_STATUS_EXC[]" id="COD_STATUS_EXC" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
												<?php

												$sql = "SELECT * FROM SAC_STATUS";
												$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

												while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
												<?php
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Remover Tipo</label>
											<select data-placeholder="Selecione o status" name="COD_TIPO_EXC[]" id="COD_TIPO_EXC" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
												<?php

												$sql = "SELECT * FROM SAC_TPSOLICITACAO";
												$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql) or die(mysqli_error());

												while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrTipo['COD_TPSOLICITACAO']; ?>"><?php echo $qrTipo['DES_TPSOLICITACAO']; ?></option>
												<?php
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>



								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>

						<div class="col-md-1">
							<div class="form-group">
								<label for="inputName" class="control-label">Esteira</label><br />
								<label class="switch">
									<input type="checkbox" name="LOG_ESTEIRA" id="LOG_ESTEIRA" class="switch" value="S" <?= (@$log_esteira == "S" ? "checked" : "") ?> />
									<span></span>
								</label>
								<div class="help-block with-errors"></div>
							</div>
						</div>

						<div class="col-md-2">
							<a href="action.php?mod=<?php echo fnEncode(1267); ?>" name="ADD" id="ADD" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Criar Novo Chamado</a>
						</div>



						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

					<div class="push30"></div>


					<div class="col-md-4 col-xs-12 col-sm-12 bot_nav_esteira" style="display:none">
						<div class="push20"></div>
						<a class="btn btn-xs btn-default" href="javascript:" onclick="abreDetailTodos(false);">
							Fechar todos
						</a> &nbsp;&nbsp;

						<a class="btn btn-xs btn-default" href="javascript:" onclick="abreDetailTodos(true);">
							Expandir todos
						</a>
					</div>


					<div class="col-lg-12" style="padding:0;">

						<div id="divId_sub">
						</div>


						<div class="no-more-tables">

							<?php
							$manutencao = true;
							include("listaChamados.php")
							?>

							<div class="push10"></div>

						</div>

					</div>

					<div class="push30"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="js/plugins/menu-dropdown/menu.min.css" />
<script type="text/javascript" src="js/plugins/menu-dropdown/menu.min.js"></script>



<script type="text/javascript">
	$(document).ready(function() {

		// MASCARA NO INPUT DO CAMPO EDITÁVEL
		// INICIALIZANDO O PLUGIN EDITAVEL COM A GLOBAL POPUP
		$('.vl .editable-input .input-sm').mask('000.000.000.000.000,00', {
			reverse: true
		});
		$('.data .editable-input .input-sm').mask("99/99/9999", {
			reverse: true
		});
		$.fn.editable.defaults.mode = 'popup';

		// LOCALIZANDO O CALENDÁRIO DO EDITÁVEL
		$.fn.bdatepicker.dates['pt-br'] = {
			days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
			daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"],
			daysMin: ["D", "S", "T", "Q", "Q", "S", "S", "D"],
			months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
			monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
			today: "Hoje",
			clear: "Limpar",
			weekStart: 0
		};

		retornaForm(0);


		$('#COD_EMPRESA').val('<?= $cod_empresa ?>').trigger('chosen:updated');

		var idEmp = $('#COD_EMPRESA').val();
		buscaCombo(idEmp);

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		<?php if (@$_POST) {
			echo "if ($('#LOG_ESTEIRA').is(':checked')){";
			echo "reloadPage(1);";
			echo "}";
		} ?>
		//reloadPage(1);
		//carregaContador(1);

		$("#LOG_ESTEIRA").click(function() {
			reloadPage(1);
		});


		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				var cod_usu = 0;
				$('table tr').each(function(index) {
					if ($(this).attr("cod_usu") != undefined) {
						cod_usu = $(this).attr("cod_usu");
					}
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.grabbable').attr('data-id') + ":" + cod_usu + ",";
						if ($(this).attr("tr_usuario") == "true") {
							$(this).removeClass();
							$(this).addClass("abreDetail_" + cod_usu);
						}
					}
				});
				contador();

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 20);

				function execOrdenacao(p1, p2) {
					//alert(p1);
					$.ajax({
						type: "POST",
						url: "ajxOrdenacao.php",
						data: {
							ajx1: p1,
							ajx2: p2
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							//$("#divId_sub").html(data); 
							//console.log(data);
							atualizaEsteira();
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}
		});


	});


	function retornaForm(index) {

		var plataforma = '<?php echo $cod_plataforma; ?>';
		if (plataforma != 0 && plataforma != "") {
			$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");
		}

		var empresa = '<?php echo $cod_empresa; ?>';
		if (empresa != 0 && empresa != "") {
			$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");
		}

		var versaointegra = '<?php echo $cod_versaointegra; ?>';
		if (versaointegra != 0 && versaointegra != "") {
			$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");
		}

		var integradora = '<?php echo $cod_integradora; ?>';
		if (integradora != 0 && integradora != "") {
			$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");
		}

		var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
		if (tpsolicitacao != 0 && tpsolicitacao != "") {
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
		}

		var prioridade = '<?php echo $cod_prioridade; ?>';
		if (prioridade != 0 && prioridade != "") {
			$("#formulario #COD_PRIORIDADE").val(<?php echo $cod_prioridade; ?>).trigger("chosen:updated");
		}

		var status = '<?php echo $cod_status; ?>';
		if (status != 0 && status != "") {
			$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");
		}

		var status_exc = '<?php echo $cod_status_exc; ?>';
		if (status_exc != 0 && status_exc != "") {
			$("#formulario #COD_STATUS_EXC").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_status_exc; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_STATUS_EXC option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_STATUS_EXC").trigger("chosen:updated");
		}

		var tipo_exc = '<?php echo $cod_tipo_exc; ?>';
		if (tipo_exc != 0 && tipo_exc != "") {
			$("#formulario #COD_TIPO_EXC").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_tipo_exc; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_TIPO_EXC option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_TIPO_EXC").trigger("chosen:updated");
		}

		var usures = '<?php echo $cod_usures; ?>';
		if (usures != 0 && usures != "") {
			$("#formulario #COD_USURES").val(<?php echo $cod_usures; ?>).trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}


	$("#COD_EMPRESA").change(function() {
		var idEmp = $('#COD_EMPRESA').val();
		buscaCombo(idEmp);
	});

	function buscaCombo(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxAddSuporte.php",
			data: {
				ajxEmp: idEmp
			},
			beforeSend: function() {
				$('#relatorioUsu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				// console.log(data);	
				$('#relatorioUsu').html($('#relatorioUsuario', data));
				$('#COD_USUARIO').chosen();
				$('#COD_USUARIO').val('<?= $cod_usuario ?>').trigger('chosen:updated');
			},
			error: function() {
				$('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
			}
		});
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


	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
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