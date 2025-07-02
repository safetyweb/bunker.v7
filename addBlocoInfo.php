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
$cod_bloco = "";
$des_bloco = "";
$log_obriga = "";
$cod_usucada = "";
$Arr_COD_TERMO = "";
$i = 0;
$cod_termo = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_verifica = "";
$sqlTipo = "";
$qrTipo = "";
$sqlTip = "";
$qrTip = "";
$sqlOrdena = "";
$qrOrdem = "";
$num_ordenac = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlBloco = "";
$arrayBloco = [];
$qrBloco = "";
$checkObriga = "";
$popUp = "";
$sqlTermo = "";
$arrayTermos = [];
$qrTermo = "";
$varTermo = "";
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

		$cod_bloco = fnLimpaCampoZero(@$_REQUEST['COD_BLOCO']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_bloco = addslashes(@$_REQUEST['DES_BLOCO']);
		if (empty(@$_REQUEST['LOG_OBRIGA'])) {
			$log_obriga = 'N';
		} else {
			$log_obriga = @$_REQUEST['LOG_OBRIGA'];
		}
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		//array das empresas multiacesso
		if (isset($_POST['COD_TERMO'])) {
			$Arr_COD_TERMO = @$_POST['COD_TERMO'];
			//print_r($Arr_COD_TERMO);			 

			for ($i = 0; $i < count($Arr_COD_TERMO); $i++) {
				$cod_termo = $cod_termo . $Arr_COD_TERMO[$i] . ",";
			}

			$cod_termo = rtrim(ltrim(trim($cod_termo), ','), ',');
		} else {
			$cod_termo = "0";
		}

		// fnEscreve($des_bloco);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_verifica = explode(',', $cod_termo);

		$sqlTipo = "SELECT COD_TIPO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND COD_TERMO = $cod_verifica[0]";

		$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlTipo));

		$sqlTip = "SELECT TIP_CLASSIFICA FROM TIPO_TERMOS WHERE COD_TIPO = $qrTipo[COD_TIPO]";

		$qrTip = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlTip));

		// fnEscreve($qrTip['TIP_CLASSIFICA']);

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlOrdena = "SELECT MAX(NUM_ORDENAC)+1 AS NUM_ORDENAC FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa";

					$qrOrdem = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlOrdena));

					$num_ordenac = $qrOrdem['NUM_ORDENAC'];

					if (trim($num_ordenac) == "") {
						$num_ordenac = 1;
					}

					$sqlInsert = "INSERT INTO BLOCO_TERMOS(
												COD_EMPRESA,
												COD_TERMO,
												DES_BLOCO,
												LOG_OBRIGA,
												TIP_TERMO,
												COD_USUCADA, 
												NUM_ORDENAC
											) VALUES(
												'$cod_empresa',
												'$cod_termo',
												'$des_bloco',
												'$log_obriga',
												'$qrTip[TIP_CLASSIFICA]',
												'$cod_usucada',
												$num_ordenac
											)";

					// fnEscreve($sqlInsert);
					// fnTESTESQL(connTemp($cod_empresa, ''), $sql);
					$arrayInsert = mysqli_query(connTemp($cod_empresa, ''), $sqlInsert);

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($adm, connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sqlUpdate = "UPDATE BLOCO_TERMOS SET
												COD_TERMO = '$cod_termo',
												DES_BLOCO = '$des_bloco',
												LOG_OBRIGA = '$log_obriga',
												TIP_TERMO = '$qrTip[TIP_CLASSIFICA]',
												COD_ALTERAC = '$cod_usucada'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_BLOCO = $cod_bloco";

					// fnEscreve($sql);				
					// fnTestesql(connTemp($cod_empresa, ''), $sql);
					$arrayUpdate = mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
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
				parent.$('#REFRESH_BLOCO').val('S');
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

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idb'])))) {

	$cod_bloco = fnLimpaCampoZero(fnDecode(@$_GET['idb']));

	$sqlBloco = "SELECT * FROM BLOCO_TERMOS 
					 WHERE COD_EMPRESA = $cod_empresa 
					 AND COD_BLOCO = $cod_bloco";

	$arrayBloco = mysqli_query(connTemp($cod_empresa, ''), $sqlBloco);
	$qrBloco = mysqli_fetch_assoc($arrayBloco);

	$des_bloco = $qrBloco['DES_BLOCO'];
	$cod_termo = $qrBloco['COD_TERMO'];
	if ($qrBloco['LOG_OBRIGA'] == 'S') {
		$checkObriga = 'checked';
	} else {
		$checkObriga = '';
	}
} else {

	$cod_bloco = 0;
	$des_bloco = '';
	$cod_termo = '';
	$checkObriga = '';
}

//fnMostraForm();

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

											<div class="col-md-12">
												<fieldset>
													<legend>Banco de Termos <small>(<b>Clique e arraste</b> a tag na área desejada ou <b>clique na tag para copiar</b>)</small> </legend>

													<!-- <h5>Código do termo - pode ser o nome abreviado - ref. para gravar o código na tabela de grupo de termo ou bloco de termo
													<br/>

													</h5>
													<br/>	 -->


													<?php

													$sqlTermo = "SELECT COD_TERMO, ABV_TERMO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S'";

													$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermo);

													while ($qrTermo = mysqli_fetch_assoc($arrayTermos)) {

														$varTermo = strtoupper($qrTermo['ABV_TERMO']);

													?>
														<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;" dragTagName="<#<?= $varTermo ?>>" onclick="$(function(){quickCopy('<#<?= $varTermo ?>>')});">
															<span><?= $qrTermo['ABV_TERMO'] ?></span>
														</a>
													<?php

													}

													?>

												</fieldset>
											</div>

										</div>

										<div class="push10"></div>

										<div class="row">

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Checagem Obrigatória</label>
													<div class="push5"></div>
													<label class="switch">
														<input type="checkbox" name="LOG_OBRIGA" id="LOG_OBRIGA" class="switch" value="S" <?= $checkObriga ?>>
														<span></span>
													</label>
												</div>
											</div>

											<div class="col-md-10">
												<div class="form-group">
													<label for="inputName" class="control-label">Termos</label>
													<select data-placeholder="Selecione o termo" name="COD_TERMO[]" id="COD_TERMO" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1" required>
														<option value=""></option>
														<?php

														$sqlTermo = "SELECT COD_TERMO, ABV_TERMO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S'";

														$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermo);

														while ($qrTermo = mysqli_fetch_assoc($arrayTermos)) {

														?>
															<option value="<?= $qrTermo['COD_TERMO'] ?>"><?= $qrTermo['ABV_TERMO'] ?></option>
														<?php

														}

														?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
												<script>
													var cod_termos = "<?= $cod_termo ?>";
													if (cod_termos != 0 && cod_termos != "") {
														//retorno combo multiplo - USUARIOS_AGE
														$("#formulario #COD_TERMO").val('').trigger("chosen:updated");

														var sistemasUni = cod_termos;
														var sistemasUniArr = sistemasUni.split(',');
														//opções multiplas
														for (var i = 0; i < sistemasUniArr.length; i++) {
															$("#formulario #COD_TERMO option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
														}
														$("#formulario #COD_TERMO").trigger("chosen:updated");
													}
												</script>
											</div>

										</div>

										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="inputName" class="control-label required">Texto da Template</label>
													<input type="text" class="form-control input-sm" name="DES_BLOCO" id="DES_BLOCO" maxlength="500" value='<?= $des_bloco ?>' required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

									</fieldset>

								</div>

							</div>


							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_bloco == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>
								<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_BLOCO" id="COD_BLOCO" value="<?= $cod_bloco ?>">
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
			//arrastar 
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