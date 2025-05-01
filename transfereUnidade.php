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
$cod_univend_orig = "";
$cod_univend_dest = "";
$desabi_comunicacao = "";
$cod_usucada = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$sqlUpdt = "";
$array = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$popUp = "";
$abaEmpresa = "";
$qrListaUnidade = "";


$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_univend_orig = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND_ORIG']);
		$cod_univend_dest = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND_DEST']);
		$desabi_comunicacao = fnLimpaCampoZero(@$_REQUEST['DESABI_COMUNICACAO']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			if ($desabi_comunicacao == 1) {
				$sql = "UPDATE clientes SET LOG_ESTATUS='N', LOG_FIDELIDADE='N', LOG_EMAIL='N', LOG_SMS='N', LOG_TELEMARK='N', LOG_WHATSAPP='N', LOG_PUSH='N', LOG_FIDELIZADO='N' WHERE cod_empresa = $cod_empresa AND cod_univend = $cod_univend_orig";

				$arrayProc = mysqli_query(conntemp($cod_empresa, ""), $sql);
			} else {

				$sql = "CALL SP_NEGATIVA_UNIDADEVENDA (
				'" . $cod_empresa . "', 
				'" . $cod_univend_orig . "', 
				'" . $cod_univend_dest . "', 
				'" . $cod_usucada . "'    
			)";

				$arrayProc = mysqli_query(conntemp($cod_empresa, ""), $sql);
				//fnTesteSql($connAdm->connAdm(), $sql);
			}

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			} else {

				$sqlUpdt = "UPDATE UNIDADEVENDA SET 
			LOG_ESTATUS = 'N', 
			LOG_COBRANCA = 'N',
			LOG_ATIVOHS = 'N', 
			DAT_ALTERAC = NOW(), 
			DAT_EXCLUSA = NOW() 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_UNIVEND = $cod_univend_orig";

				$array = mysqli_query($connAdm->connAdm(), $sqlUpdt);
				if (!$array) {

					$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt, $nom_usuario);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Transferência realizada com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível realizar a ação : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível realizar a ação : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível realizar a ação : $cod_erro";
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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnMostraForm();

?>

<style>
	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

				<?php
				//menu superior - empresas
				if ($popUp != "true") {

					//aba default
					$abaEmpresa = 1813;

					//menu abas
					include "abasEmpresas.php";
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Origem</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND_ORIG" id="COD_UNIVEND_ORIG" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
												";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_UNIVEND_ORIG").val("<?php echo $cod_univend_orig; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors">Unidade a ser inativada</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Destino</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND_DEST" id="COD_UNIVEND_DEST" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
												";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_UNIVEND_DEST").val("<?php echo $cod_univend_dest; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors">Unidade a receber clientes</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Desabilitar Comunicações</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="DESABI_COMUNICACAO" id="DESABI_COMUNICACAO" class="chosen-select-deselect" required>
											<option value="0">Manter Status Atual da Comunicação</option>
											<option value="1">Desabilitar Comunicações</option>
										</select>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-exchange" aria-hidden="true"></i>&nbsp; Transferir e Inativar Unidade de Origem</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="CAD">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#DESABI_COMUNICACAO').change(function() {
			if ($(this).val() == '1') {
				$('#COD_UNIVEND_DEST').prop('disabled', true).trigger("chosen:updated");
				$('#COD_UNIVEND_DEST').val('').trigger("chosen:updated");
				$("#CAD").text("Inativar unidade e clientes");
			} else {
				$('#COD_UNIVEND_DEST').prop('disabled', false).trigger("chosen:updated");
				$('#COD_UNIVEND_DEST').prop('required', true).trigger("chosen:updated");
				$("#CAD").html('<i class="fal fa-exchange" aria-hidden="true"></i>&nbsp; Transferir e Inativar Unidade de Origem');
			}
		});
	});


	$(function() {

		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	$("#CAD").click(function(e) {
		e.preventDefault();
		if ($('#COD_UNIVEND_ORIG').val() != "" && $('#COD_UNIVEND_DEST').val() != "") {
			$.alert({
				title: "Atenção!",
				content: "Deseja mesmo transferir os clientes de <b>" + $('#COD_UNIVEND_ORIG option:selected').text() + "</b> para <b>" + $('#COD_UNIVEND_DEST option:selected').text() + "</b>?<br /> Essa ação <b>não poderá ser desfeita</b>!",
				type: 'orange',
				buttons: {
					"Transferir": {
						btnClass: 'btn-primary',
						action: function() {
							$("#formulario").submit();
							$("#blocker").show();
						}
					},
					"Cancelar": {
						action: function() {

						}
					}
				}
			});
		} else {
			$.alert({
				title: "Atenção!",
				content: "Deseja mesmo desativar os clientes e a unidade <b>" + $('#COD_UNIVEND_ORIG option:selected').text() + "</b>?<br /> Essa ação <b>não poderá ser desfeita</b>!",
				type: 'orange',
				buttons: {
					"Confirmar": {
						btnClass: 'btn-primary',
						action: function() {
							$("#formulario").submit();
							$("#blocker").show();
						}
					},
					"Cancelar": {
						action: function() {

						}
					}
				}
			});

		}
	});
</script>