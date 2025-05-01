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
$cod_maquina = "";
$des_maquina = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$abaModulo = "";
$qrListaUnidades = "";
$disabled = "";

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

		$cod_maquina = fnLimpaCampoZero(@$_REQUEST['COD_MAQUINA']);
		$cod_univend = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND']);
		$des_maquina = fnLimpaCampo(@$_REQUEST['DES_MAQUINA']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_MAQUINAS (
				 '" . $cod_maquina . "', 
				 '" . $des_maquina . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_univend . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			$arrayProc = mysqli_query($conn, $sql);

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

//fnMostraForm();

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
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
				//fidelidade
				if (fnDecode(@$_GET['mod']) == 1153) {
					$formBack = "1154";
				}
				//sh manager
				if (fnDecode(@$_GET['mod']) == 1159) {
					$formBack = "1158";
				}

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
				$abaEmpresa = 1105;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 13: //sh manager
						$abaModulo = 1161;
						include "abasIntegradora.php";
						break;
					case 15: //quiz
						include "abasEmpresaQuiz.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						break;
				}

				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario">


						<fieldset>
							<legend>Dados de Acesso</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
											<option value="0"></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaUnidades['LOG_ESTATUS'] == 'N') {
													$disabled = "disabled";
												} else {
													$disabled = " ";
												}
												echo "
													<option value='" . fnEncode($qrListaUnidades['COD_UNIVEND']) . "'" . $disabled . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Usuário</label>
										<div id="divId_usu">
											<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" required>
												<option value="0"></option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Máquina</label>
										<div id="divId_maq">
											<select data-placeholder="Selecione uma máquina" name="COD_MAQUINA" id="COD_MAQUINA" class="chosen-select-deselect">
												<option value="K2xr0lE3UHI¢"></option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="button" name="BTOPEN" id="BTOPEN" class="btn btn-primary getBtn" onClick="gerarChave(this, '<?php echo fnEncode(1016) ?>', '<?php echo fnEncode($cod_empresa) ?>');">
								<i class="fal fa-unlock-alt" aria-hidden="true"></i>&nbsp; Gerar Chave de Acesso
							</button>

						</div>
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">


						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>


<!-- modal -->
<div class="modal fade" id="popModalAux" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 60%"></iframe>
				<div class="push30"></div>
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

		$('#BTOPEN').prop('disabled', true);

	});



	function gerarChave(thisObject, codModulo, codEmpresa) {
		var popLink = 'action.php?mod=' + codModulo + '&id=' + codEmpresa + '&pop=true';
		var popTitle = 'Chave de Acesso';

		var codUnivend = $('#COD_UNIVEND').val();
		var codUsuario = $('#COD_USUARIO').val();
		var codMaquina = $('#COD_MAQUINA').val();

		popLink += '&codUnivend=' + codUnivend + '&codUsuario=' + codUsuario + '&codMaquina=' + codMaquina;

		// setIframe(popLink, popTitle);

		$("#popModalAux iframe").attr('src', popLink);
		$("#popModalAux .modal-header").text(popTitle);
		$('#popModalAux').appendTo("body").modal('show');
	}

	function verificarCampos() {
		var codUnivend = $('#COD_UNIVEND').val();
		var codUsuario = $('#COD_USUARIO').val();
		var codMaquina = $('#COD_MAQUINA').val();

		if ((typeof codUnivend != 'undefined' && codUnivend != '' && codUnivend != 0) &&
			(typeof codUsuario != 'undefined' && codUsuario != '' && codUsuario != 0)) {

			$('#BTOPEN').prop('disabled', false);
		}
	}

	$('body').on('change', '#COD_UNIVEND', function() {
		verificarCampos();
	});

	$('body').on('change', '#COD_USUARIO', function() {
		verificarCampos();
	});

	$('body').on('change', '#COD_MAQUINA', function() {
		verificarCampos();
	});

	// ajax
	$("#COD_UNIVEND").change(function() {
		var codBusca = $("#COD_UNIVEND").val();
		var codBusca2 = $("#COD_EMPRESA").val();
		buscaUsuario(codBusca, codBusca2);
		buscaMaquina(codBusca, codBusca2);
	});

	function buscaUsuario(idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function buscaMaquina(idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaMaquinaChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_maq').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_maq").html(data);
			},
			error: function() {
				$('#divId_maq').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
		$("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>