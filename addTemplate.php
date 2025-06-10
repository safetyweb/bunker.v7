<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = $_REQUEST['LOG_ATIVO'];
		}

		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);
		$des_msgerro = $_REQUEST['DES_MSGERRO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_TEMPLATE (
				 '" . $cod_template . "', 
				 '" . $cod_empresa . "',
				 '" . $log_ativo . "', 
				 '" . $nom_template . "', 
				 '" . $abv_template . "',
				 '" . $des_template . "',
				 '" . $des_msgerro . "',
				 '" . $cod_usucada . "',
				 '" . $opcao . "'    
			        );";

			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), $sql);

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_TEMPLATES').val("S");
				} catch (err) {}
			</script>
<?php

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
	$cod_tipo = fnDecode($_GET['tipo']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idT'])))) {

	//busca dados do convênio
	$cod_template = fnDecode($_GET['idT']);
	$sql = "SELECT * FROM TEMPLATE WHERE COD_TEMPLATE = " . $cod_template;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
		$log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
		$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
		$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
		$des_template = $qrBuscaTemplate['DES_TEMPLATE'];
		$des_msgerro = $qrBuscaTemplate['DES_MSGERRO'];
	}
} else {
	$cod_template = "";
	$log_ativo = "";
	$nom_template = "";
	$abv_template = "";
	$des_template = "";
	$des_msgerro = "";
}

$sqlBusca = "SELECT * FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa AND tip_url = 'TKT'";
$arrayBusca = mysqli_query($adm, $sqlBusca);
if (mysqli_num_rows($arrayBusca) == 0) {
	$sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' LIMIT 1";
	$array = mysqli_query($conn, $sql);
	if (mysqli_num_rows($array) > 0) {
		$sqlProd = "SELECT * FROM PRODUTOTKT WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVOTK = 'S'";
		$arrayProd = mysqli_query($conn, $sqlProd);
		if (mysqli_num_rows($arrayProd) > 0) {
			$qrTkt = mysqli_fetch_assoc($array);
			$titulo = $qrTkt['NOM_TEMPLATE'] . ' #' . $qrTkt['COD_TEMPLATE'];
			fnEncurtador($titulo, '', '', '', 'TKT', $cod_empresa, $connAdm->connAdm(), $qrTkt['COD_TEMPLATE']);
		}
	}
}

// BUSCA LINK ENCURTADO
$urlEncurtada = '';
$sql = "SELECT * FROM TAB_ENCURTADOR WHERE COD_EMPRESA = " . $cod_empresa . " AND TIP_URL = 'TKT'";
// fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
if (mysqli_num_rows($arrayQuery) > 0) {
	$qrBuscaLink = mysqli_fetch_assoc($arrayQuery);
	$urlEncurtada = "tkt.far.br/" . short_url_encode($qrBuscaLink['id']);
}

?>
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
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome Template</label>
										<input type="text" class="form-control input-sm" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="50">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação Template</label>
										<input type="text" class="form-control input-sm" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="<?php echo $log_ativo ?>" />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<?php if ($urlEncurtada != '') { ?>

									<div class="col-md-2">
										<button type="button" class="btn btn-default" id="btnPesquisa" <?= $disableBtn ?>><i class="fas fa-copy" aria-hidden="true"></i>&nbsp; Copiar Link</button>
										<script type="text/javascript">
											$("#btnPesquisa").click(function() {
												if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
													var el = $("#linkPesquisa").get(0);
													var editable = el.contentEditable;
													var readOnly = el.readOnly;
													el.contentEditable = true;
													el.readOnly = false;
													var range = document.createRange();
													range.selectNodeContents(el);
													var sel = window.getSelection();
													sel.removeAllRanges();
													sel.addRange(range);
													el.setSelectionRange(0, 999999);
													el.contentEditable = editable;
													el.readOnly = readOnly;
												} else {
													$("#linkPesquisa").select();
												}
												document.execCommand('copy');
												$("#linkPesquisa").blur();
												$("#btnPesquisa").text("Link Copiado");
												setTimeout(function() {
													$("#btnPesquisa").html("<i class='fas fa-copy' aria-hidden='true'></i>&nbsp; Copiar Link");
												}, 2000);
											});
										</script>
										<input type="hidden" id="linkPesquisa" class="form-control input-md pull-right text-center" value='<?= $urlEncurtada ?>' readonly>
									</div>
								<?php } ?>

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição Template</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_TEMPLATE" id="DES_TEMPLATE" maxlength="200"><?php echo $des_template; ?></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Mensagem de Erro</label>
										<textarea type="text" class="editor form-control input-sm" rows="6" name="DES_MSGERRO" id="DES_MSGERRO" maxlength="200"><?php echo $des_msgerro; ?></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="push10"></div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							<?php
							if ($cod_tipo == 'CAD') {
							?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php
							} else {
							?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php
							}
							?>

							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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

<div class="push20"></div>


<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>

<script type="text/javascript">
	$(function() {
		var totalChars = 1000;
		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: true,
			center: true,
			color: false,
			right: true,
			strike: true,
			source: false,
			link: true,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: true,
		});

		$(document).on("keydown", ".jqte_editor", function(e) {
			el = $(this);
			if ((el.text().length > totalChars - 1) && (e.keyCode != 8)) {
				e.preventDefault();
			}
		});

	});

	if ($("#LOG_ATIVO").val() === 'S') {
		$("#LOG_ATIVO").trigger("click");
	}

	$("#LOG_ATIVO").change(function() {
		if ($(this).val() === 'N') {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});

	function retornaForm(index) {
		/*
		$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
		$("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
		$("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
		$("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
		$("#formulario #DES_MSGERRO").val($("#ret_DES_MSGERRO_"+index).val());
		if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');			
		*/
	}
</script>