<?php

// echo fnDebug('true');

$hashLocal = mt_rand();

$mod = fnDecode($_GET['mod']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_comunic = fnLimpaCampoZero($_REQUEST['COD_COMUNIC']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		// $cod_comunicacao = fnLimpaCampoZero($_REQUEST['COD_COMUNICACAO']);
		$cod_comunicacao = 99;
		$cod_ctrlenv = fnLimpaCampoZero($_REQUEST['COD_CTRLENV']);
		$des_texto_sms = $_REQUEST['DES_TEXTO_SMS'];
		$cod_campanha = 0;
		$cod_tipcomu = 4; //tipo sms transacional -- comunicacao_tipo
		$cod_disparo = 0;
		$cod_modmail = 0;
		$log_saldo = 'N';
		$log_totem = 'N';
		$log_web = 'N';
		$log_hotsite = 'N';

		$sql = "select * from VARIAVEIS order by NUM_ORDENAC ";
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		while ($qrListaVariaveis = mysqli_fetch_assoc($arrayQuery)) {

			if (strlen(strstr($des_texto_sms, $qrListaVariaveis['KEY_BANCOVAR'])) > 0) {
				//fnEscreve($qrListaVariaveis['NOM_BANCOVAR']);
				$cod_bancovar = $cod_bancovar . $qrListaVariaveis['COD_BANCOVAR'] . ",";
			} else {
				$cod_bancovar = "";
			}
		}

		$cod_bancovar = rtrim(ltrim($cod_bancovar, ','), ',');

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {


			$sql = "CALL SP_ALTERA_COMUNICACAO_MODELO_TKT (
				 '" . $cod_comunic . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_comunicacao . "', 
				 '" . $cod_tipcomu . "', 
				 '" . $des_texto_sms . "', 
				 '" . $cod_bancovar . "', 
				 '" . $cod_usucada . "', 
				 '" . $cod_disparo . "', 
				 '" . $cod_modmail . "', 
				 '" . $cod_ctrlenv . "',   
				 '" . $log_saldo . "',   
				 '" . $log_totem . "',   
				 '" . $log_web . "',   
				 '" . $log_hotsite . "',   
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sql);
			// fntestesql(connTemp($cod_empresa,""),trim($sql));

			// fnTestesql(connTemp($cod_empresa, ""), trim($sql));
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	if (isset($_GET['idC'])) {
		$cod_comunic = fnLimpaCampoZero(fnDecode($_GET['idC']));
	} else {
		$cod_comunic = 0;
	}

	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "N";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "completed ";
		$abaVantagemComp = "completed ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "active";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if ($cod_comunic != 0) {
	$sqlMsg = "SELECT * FROM COMUNICACAO_MODELO_TKT WHERE COD_EMPRESA = $cod_empresa AND COD_COMUNIC = $cod_comunic";
	// echo($sql);
	$arrayMsg = mysqli_query(connTemp($cod_empresa, ""), $sqlMsg);

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayMsg);
	$temMsg = mysqli_num_rows($arrayMsg);

	$cod_ctrlenv = $qrBuscaComunicacao['COD_CTRLENV'];
	$des_texto_sms = $qrBuscaComunicacao['DES_TEXTO_SMS'];
}

//fnMostraForm();	
//fnEscreve($num_minresg);

?>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

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

					<?php
					if ($popUp != "true") {
						$abaCampanhas = 1169;
						include "abasCampanhasConfig.php";
					}
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag desejada ou <b>copie</b> na área desejada)</small> </legend>

								<div class="row">

									<div class="col-md-12">
										<?php

										//fnEscreve($cod_campanha);

										//busca dados da campanha
										if (isset($_GET['idc'])) {
											$cod_campanha = fnDecode($_GET['idc']);
											$sql = "SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

											if (isset($qrBuscaCampanha)) {
												$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
											}
										} else {
											$tip_campanha = "";
										}
										//fnEscreve($tip_campanha);
										//fnEscreve(1);

										// $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
										$sql = "select * from VARIAVEIS where LOG_SMS = 'S' order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
												dragTagName="<?= $qrBuscaFases['KEY_BANCOVAR'] ?>"
												onclick="$(function(){quickCopy('<?= $qrBuscaFases['KEY_BANCOVAR'] ?>')});">
												<span><?= $qrBuscaFases['ABV_BANCOVAR'] ?></span>
											</a>

											<?php
										}

										if ($tip_campanha == 20) {

											$sql2 = "select * from VARIAVEIS where COD_BANCOVAR in (33,34) order by NUM_ORDENAC";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2);
											while ($qrBuscaFasesCupom = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
													dragTagName="<?= $qrBuscaFasesCupom['KEY_BANCOVAR'] ?>"
													onclick="$(function(){quickCopy('<?= $qrBuscaFasesCupom['KEY_BANCOVAR'] ?>')});">
													<span><?= $qrBuscaFasesCupom['ABV_BANCOVAR'] ?></span>
												</a>

										<?php
											}
										}


										?>
									</div>

								</div>

							</fieldset>

							<div class="push20"></div>

							<fieldset>
								<legend>Dados do Sms</legend>

								<div class="row">

									<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Gatilho </label>
																<select data-placeholder="Selecione o gatilho" name="COD_COMUNICACAO" id="COD_COMUNICACAO" class="chosen-select-deselect requiredChk" required >
																	<option value=""></option>											  
																	<option value="99">Aniversário</option>											  
																	<option value="98">Atualização de Cadastro</option>											  
																		
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>	 -->

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Controle de Exibição </label>
											<select data-placeholder="Selecione o controle" name="COD_CTRLENV" id="COD_CTRLENV" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<!-- <option value="0">Enviar a cada evento</option>											   -->
												<option value="1">No dia</option>
												<option value="7">Na semana</option>
												<option value="30">No mês</option>
											</select>
											<div class="help-block with-errors"></div>
											<script type="text/javascript">
												$(function() {
													$("#COD_CTRLENV").val("<?= $cod_ctrlenv ?>").trigger("chosen:updated");
												})
											</script>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-11">
										<div class="form-group">
											<label for="inputName" class="control-label required">Texto da Mensagem</label>
											<input type="text" class="form-control input-sm" name="DES_TEXTO_SMS" id="DES_TEXTO_SMS" maxlength="160" value="<?= $des_texto_sms ?>" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Caracteres</label>
											<input type="text" class="form-control input-sm text-center leitura" readonly="readonly" name="nType" id="nType" value="200">
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_comunic != 0) { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } ?>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="COD_COMUNIC" id="COD_COMUNIC" value="<?php echo $cod_comunic ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div id="div_Ordena"></div>

						<div class="push30"></div>

					</div>

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

	<script type="text/javascript">
		$(document).ready(function() {

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$("#COD_COMUNICACAO").change(function() {
				var cod = $(this).val();

				$("#COD_CTRLENV option").remove();

				if (cod == 98) {

					$("#COD_CTRLENV")
						.append('<option value=""></option>')
						.append('<option value="6">6 meses</option>')
						.append('<option value="365">12 meses</option>')
						.trigger("chosen:updated");

				} else {

					$("#COD_CTRLENV")
						.append('<option value=""></option>')
						.append('<option value="1">No dia</option>')
						.append('<option value="7">Na semana</option>')
						.append('<option value="30">No mês</option>')
						.trigger("chosen:updated");

				}

			});

		});


		$('.dragTag').on('dragstart', function(event) {
			var tag = $(this).attr('dragTagName');
			event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
			event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
		});


		$('.dragTag').on('click', function(event) {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val(" @" + $(this).text() + " ").select();
			document.execCommand("copy");
			$temp.remove();
		});




		function quickCopy(tag) {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val("@" + tag + " ").select();
			document.execCommand("copy");
			$temp.remove();
		}

		$('#DES_TEXTO_SMS').keyup(updateCount);
		$('#DES_TEXTO_SMS').keydown(updateCount);
		$('#DES_TEXTO_SMS').change(updateCount);

		function updateCount() {
			var cs = [200 - $(this).val().length];
			//var cs = [$(this).val().length];
			//$('#characters').text(cs);
			$('#nType').val(cs);
		}

		function retornaForm(index) {

			$("#formulario #COD_COMUNIC").val($("#ret_COD_COMUNIC_" + index).val());
			$("#formulario #DES_TEXTO_SMS").val($("#ret_DES_TEXTO_SMS_" + index).val());
			$("#formulario #COD_COMUNICACAO").val($("#ret_COD_COMUNICACAO_" + index).val()).trigger("chosen:updated");

			var cod = $("#ret_COD_COMUNICACAO_" + index).val();
			$("#COD_CTRLENV option").remove();

			if (cod == 98) {

				$("#COD_CTRLENV")
					.append('<option value="6">6 meses</option>')
					.append('<option value="365">12 meses</option>')
					.trigger("chosen:updated");

			} else {

				$("#COD_CTRLENV")
					.append('<option value="1">No dia</option>')
					.append('<option value="7">Na semana</option>')
					.append('<option value="30">No mês</option>')
					.trigger("chosen:updated");

			}
			$("#formulario #COD_CTRLENV").val($("#ret_COD_CTRLENV_" + index).val()).trigger("chosen:updated");

			if ($("#ret_LOG_SALDO_" + index).val() == 'S') {
				$('#formulario #LOG_SALDO').prop('checked', true);
			} else {
				$('#formulario #LOG_SALDO').prop('checked', false);
			}
			if ($("#ret_LOG_TOTEM_" + index).val() == 'S') {
				$('#formulario #LOG_TOTEM').prop('checked', true);
			} else {
				$('#formulario #LOG_TOTEM').prop('checked', false);
			}
			if ($("#ret_LOG_WEB_" + index).val() == 'S') {
				$('#formulario #LOG_WEB').prop('checked', true);
			} else {
				$('#formulario #LOG_WEB').prop('checked', false);
			}
			if ($("#ret_LOG_HOTSITE_" + index).val() == 'S') {
				$('#formulario #LOG_HOTSITE').prop('checked', true);
			} else {
				$('#formulario #LOG_HOTSITE').prop('checked', false);
			}
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>