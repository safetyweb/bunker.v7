<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$cod_empresa = fnDecode(@$_GET['id']);
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_termo = "";
$cod_tipo = "";
$nom_termo = "";
$abv_termo = "";
$des_termo = "";
$log_ativo = "";
$cod_usucada = "";
$nom_submenus = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlTermo = "";
$arrayTermo = [];
$qrTermo = "";
$checkAtivo = "";
$popUp = "";
$sqlTipo = "";
$arrayTipo = [];
$qrTipo = "";
$temp = "";
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

		$cod_termo = fnLimpaCampoZero(@$_REQUEST['COD_TERMO']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_tipo = fnLimpaCampoZero(@$_REQUEST['COD_TIPO']);
		$nom_termo = fnLimpaCampo(@$_REQUEST['NOM_TERMO']);
		$abv_termo = fnLimpaCampo(@$_REQUEST['ABV_TERMO']);
		$des_termo = addslashes(htmlentities(@$_REQUEST['DES_TERMO']));
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = @$_REQUEST['LOG_ATIVO'];
		}
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		//fnEscreve($nom_submenus);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlInsert = "INSERT INTO TERMOS_EMPRESA(
												COD_EMPRESA,
												COD_TIPO,
												NOM_TERMO,
												ABV_TERMO,
												LOG_ATIVO,
												DES_TERMO
											) VALUES(
												'$cod_empresa',
												'$cod_tipo',
												'$nom_termo',
												'$abv_termo',
												'$log_ativo',
												'$des_termo'
											)";

					// fnEscreve($sql);				
					// fnTestesql(connTemp($cod_empresa, ''), $sql);
					$arrayInsert = mysqli_query(connTemp($cod_empresa, ''), $sqlInsert);

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
					}

					//fnEscreve($arrayInsert);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sqlUpdate = "UPDATE TERMOS_EMPRESA SET
												COD_TIPO = '$cod_tipo',
												NOM_TERMO = '$nom_termo',
												ABV_TERMO = '$abv_termo',
												LOG_ATIVO = '$log_ativo',
												DES_TERMO = '$des_termo',
												COD_ALTERAC = '$cod_usucada'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_TERMO = $cod_termo";

					// fnEscreve($sql);				
					// fnTestesql(connTemp($cod_empresa, ''), $sql);
					$arrayUpdate = mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);

					//fnEscreve($arrayUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}
					//fnEscreve($sqlUpdate);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}

?>
			<script>
				parent.$('#REFRESH_TERMO').val('S');
			</script>
<?php

		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
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

// fnEscreve(fnDecode(@$_GET['idt']));

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idt'])))) {

	$cod_termo = fnLimpaCampoZero(fnDecode(@$_GET['idt']));

	$sqlTermo = "SELECT * FROM TERMOS_EMPRESA 
					 WHERE COD_EMPRESA = $cod_empresa 
					 AND COD_TERMO = $cod_termo";

	$arrayTermo = mysqli_query(connTemp($cod_empresa, ''), $sqlTermo);
	$qrTermo = mysqli_fetch_assoc($arrayTermo);

	$cod_tipo = $qrTermo['COD_TIPO'];
	$nom_termo = $qrTermo['NOM_TERMO'];
	$abv_termo = $qrTermo['ABV_TERMO'];
	$des_termo = $qrTermo['DES_TERMO'];
	if ($qrTermo['LOG_ATIVO'] == 'S') {
		$checkAtivo = 'checked';
	} else {
		$checkAtivo = '';
	}
} else {

	$cod_termo = 0;
	$cod_tipo = 0;
	$nom_termo = '';
	$abv_termo = '';
	$des_termo = '';
}

//fnMostraForm();

// fnEscreve($cod_termo);

?>


<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode: "textareas",
		setup: function(ed) {
			// set the editor font size
			ed.onInit.add(function(ed) {
				ed.getBody().style.fontSize = '13px';
			});
		},
		language: "pt",
		theme: "advanced",
		plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_toolbar_location: "top",
		theme_advanced_toolbar_align: "left",
		theme_advanced_statusbar_location: "bottom",
		theme_advanced_resizing: true,

		// Example content CSS (should be your site CSS)
		//content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url: "lists/template_list.js",
		external_link_list_url: "lists/link_list.js",
		external_image_list_url: "lists/image_list.js",
		media_external_list_url: "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values: {
			username: "Some User",
			staffid: "991234"
		}
	});
</script>

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

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">

								<div class="col-md-12">

									<fieldset>
										<legend>Dados do Termo</legend>

										<div class="row">

											<div class="col-md-4">
												<div class="form-group">
													<label for="inputName" class="control-label required">Nome do termo</label>
													<input type="text" class="form-control input-sm" name="NOM_TERMO" id="NOM_TERMO" maxlength="40" value="<?= $nom_termo ?>" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label required">Nome de Exibição</label>
													<input type="text" class="form-control input-sm" name="ABV_TERMO" id="ABV_TERMO" value="<?= $abv_termo ?>" maxlength="50" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="form-group">
													<label for="inputName" class="control-label required">Tipo do Termo</label>
													<select data-placeholder="Selecione o tipo" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect" required>
														<option value=""></option>
														<optgroup label="Documentação">
															<?php

															$sqlTipo = "SELECT COD_TIPO, DES_TIPO FROM TIPO_TERMOS WHERE TIP_CLASSIFICA = 'DOC'";

															$arrayTipo = mysqli_query($connAdm->connAdm(), $sqlTipo);
															while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {

															?>
																<option value="<?= $qrTipo['COD_TIPO'] ?>"><?= $qrTipo['DES_TIPO'] ?></option>
															<?php

															}

															?>

														</optgroup>
														<optgroup label="Comunicação">
															<?php

															$sqlTipo = "SELECT COD_TIPO, DES_TIPO FROM TIPO_TERMOS WHERE TIP_CLASSIFICA = 'COM'";

															$arrayTipo = mysqli_query($connAdm->connAdm(), $sqlTipo);
															while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {

															?>
																<option value="<?= $qrTipo['COD_TIPO'] ?>"><?= $qrTipo['DES_TIPO'] ?></option>
															<?php

															}

															?>
														</optgroup>
													</select>
													<div class="help-block with-errors"></div>
												</div>
												<script type="text/javascript">
													$('#COD_TIPO').val('<?= $cod_tipo ?>').trigger('chosen:updated');
												</script>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Termo Ativo</label>
													<div class="push5"></div>
													<label class="switch">
														<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
														<span></span>
													</label>
												</div>
											</div>

										</div>

										<div class="push10"></div>

										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="inputName" class="control-label required">Descrição do Termo</label>
													<textarea name="DES_TERMO" id="DES_TERMO" style="width: 100%; height: 240px;"><?php echo $des_termo; ?></textarea>
												</div>
											</div>

										</div>


									</fieldset>

								</div>

							</div>


							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_termo == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>
								<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_TERMO" id="COD_TERMO" value="<?= $cod_termo ?>">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$('.dragTag').on('dragstart', function(event) {
				var tag = $(this).attr('dragTagName');
				event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
				event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
			});


			$('.dragTag').on('click', function(event) {
				var $temp = $("<input>");
				$("#tosave").append($temp);
				$temp.val($(this).text()).select();
				document.execCommand("copy");
				$temp.remove();
			});

		});

		function quickCopy(tag) {
			var dummyContent = tag;
			var dummy = $('<input>').val(dummyContent).appendTo('body');
			dummy.select();
			document.execCommand('copy');
			dummy.remove();
		}
	</script>