<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$cpfteste = "";
$usuarioteste = "";
$teste = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$COD_DESCTKT = "";
$DES_DESCTKT = "";
$ABV_DESCTKT = "";
$LOG_ATIVO = "";
$log_ativo = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
$cod_desctkt = "";
$des_desctkt = "";
$abv_desctkt = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$abaModulo = "";
$showAll = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$COD_DESCTKT = fnLimpaCampoZero(@$_REQUEST['COD_DESCTKT']);
		$DES_DESCTKT = fnLimpaCampo(@$_REQUEST['DES_DESCTKT']);
		$ABV_DESCTKT = fnLimpaCampo(@$_REQUEST['ABV_DESCTKT']);
		$LOG_ATIVO = fnLimpaCampo(@$_REQUEST['LOG_ATIVO']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = @$_REQUEST['LOG_ATIVO'];
		}
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		//if ($opcao != ''){
		if ($opcao == '999') {

			$sql = "CALL SP_ALTERA_DESCONTOTKT (
				 '" . $cod_desctkt . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_desctkt . "', 
				 '" . $abv_desctkt . "', 
				 '" . $log_ativo . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//fnMostraForm();

?>

<style>
	#top {
		z-index: 3;
		display: block;
		margin: 0 auto;
		position: relative;
	}

	#bottom {
		z-index: 1;
		display: block;
		margin: 0 auto;
		position: relative;
	}

	#paper {
		position: absolute;
		top: -780px;
		left: 197px;
		z-index: 2;
		width: 400px;
		line-height: 50px;
		padding: 15px 0;
		background: #fff;
		border: 1px solid #999;
		text-align: center;
		font-weight: bold;
		font-size: 22pt;
		-webkit-box-shadow: 0 8px 6px -6px black;
		-moz-box-shadow: 0 8px 6px -6px black;
		box-shadow: 0 8px 6px -6px black;
	}

	.chosen-container {
		font-size: 17px;
	}

	.chosen-container-single .chosen-single {
		height: 60px;
	}

	.chosen-container-single .chosen-single span {
		margin-top: 13px;
	}
</style>

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
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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

					<?php $abaModulo = 1129;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div id="consultaDoc">

								<div class="row">

									<div class="push50"></div>
									<div class="push20"></div>

									<div class="col-md-4">
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Informe seu CPF</label>
											<input type="text" class="form-control input-lg text-center cpf" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
									</div>

									<div class="push20"></div>

									<div class="col-md-4">
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento </label>
											<?php $showAll = "no";
											include "unidadesAutorizadasCombo.php"; ?>
										</div>
									</div>

									<div class="col-md-4">
									</div>


									<div class="push50"></div>
									<hr>
									<div class="form-group text-right col-lg-12">

										<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
										<button type="submit" name="GETDOC" id="GETDOC" class="btn btn-primary getBtn" disabled><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Consultar CPF</button>

									</div>

									<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									<input type="hidden" name="opcao" id="opcao" value="">
									<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">


								</div>

						</form>
						<div class="push50"></div>


					</div>
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

		$('#COD_UNIVEND').on('change', function() {
			$("#GETDOC").prop("disabled", false);
		})

	});

	$("#GETDOC").click(function() {
		sendDoc($("#NUM_CGCECPF").val(), <?= $cod_empresa ?>, $("#COD_UNIVEND").val());
	});

	$("#GETDOC2").click(function() {
		getImpressao();
	});

	function sendDoc(idDoc, idEmp, univend) {
		$.ajax({
			type: "GET",
			url: "ajxConsultaDoc.do",
			data: {
				ajx1: idDoc,
				ajx2: idEmp,
				ajx3: univend
			},
			beforeSend: function() {
				$('#consultaDoc').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#consultaDoc").html(data);
				$("#COD_SEXOPES").chosen();
			},
			error: function() {
				$('#consultaDoc').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}


	function getImpressao(idDoc, idEmp, univend) {
		$.ajax({
			type: "GET",
			url: "ajxSimulaTicket.do",
			data: $("#formulario").serialize(),
			beforeSend: function() {
				$('#consultaDoc').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#consultaDoc").html(data);

			},
			error: function() {
				$('#consultaDoc').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function resizeFrame() {
		var paperTop = 780;
		var iframeAltura = 900;

		var diferenca = $('#paper').contents().height() - iframeAltura;

		$('#paper').height($('#paper').contents().height());
		$('#paper').css('top', (paperTop + diferenca + 32) * -1);
	}

	function retornaForm(index) {
		$("#formulario #COD_BLKLIST").val($("#ret_COD_BLKLIST_" + index).val());
		$("#formulario #TIP_BLKLIST").val($("#ret_TIP_BLKLIST_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_BLKLIST").val($("#ret_NOM_BLKLIST_" + index).val());
		$("#formulario #ABV_BLKLIST").val($("#ret_ABV_BLKLIST_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>