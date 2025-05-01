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
$cod_modulmk = "";
$nom_modulmk = "";
$abv_modulmk = "";
$des_modulmk = "";
$cod_grupomodmk = "";
$url_modulmk = "";
$des_icone = "";
$des_cor = "";
$des_imagem = "";
$des_extras = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$abaModulo = "";
$arrayQuery = [];
$qrListaSexo = "";
$qrBuscaModulos = "";


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

		$cod_modulmk = fnLimpaCampoZero(@$_REQUEST['COD_MODULMK']);
		$nom_modulmk = fnLimpaCampo(@$_REQUEST['NOM_MODULMK']);
		$abv_modulmk = fnLimpaCampo(@$_REQUEST['ABV_MODULMK']);
		$des_modulmk = fnLimpaCampo(@$_REQUEST['DES_MODULMK']);
		$cod_grupomodmk = fnLimpaCampo(@$_REQUEST['COD_GRUPOMODMK']);
		$url_modulmk = fnLimpaCampo(@$_REQUEST['URL_MODULMK']);
		$des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampoHtml(@$_REQUEST['DES_COR']);
		$des_imagem = fnLimpaCampo(@$_REQUEST['DES_IMAGEM']);
		$des_extras = addslashes(htmlentities(@$_REQUEST['DES_EXTRAS']));

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_MODULOSMARKA (
				 '" . $cod_modulmk . "', 
				 '" . $nom_modulmk . "', 
				 '" . $abv_modulmk . "', 
				 '" . $des_icone . "', 
				 '" . $des_cor . "', 
				 '" . $des_modulmk . "', 
				 '" . $url_modulmk . "', 
				 '" . $cod_grupomodmk . "', 
				 '" . $des_imagem . "', 
				 '" . $des_extras . "', 
				 '" . $opcao . "'    
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

//fnMostraForm();

//só pra fixar o upload
$cod_empresa = 7;

?>


<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode: "textareas",
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
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				<?php $abaModulo = 1115;
				include "abasModulosMarka.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_MODULMK" id="COD_MODULMK" value="">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Módulo</label>
										<input type="text" class="form-control input-sm" name="NOM_MODULMK" id="NOM_MODULMK" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_MODULMK" id="ABV_MODULMK" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-sm btn-primary btnSearchIcon" id="btnIcon" style="min-height: 33px; margin-top: 1px;" data-icon=""></button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Certificações</label>
										<select data-placeholder="Selecione a certificação" name="COD_GRUPOMODMK" id="COD_GRUPOMODMK" class="chosen-select-deselect">
											<option value="">&nbsp;</option>
											<?php
											$sql = "SELECT * FROM GRUPOMODULOSMARKA ORDER BY NUM_ORDENAC ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																				  <option value='" . $qrListaSexo['COD_GRUPOMODMK'] . "'>" . $qrListaSexo['NOM_GRUPOMODMK'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Url</label>
										<input type="text" class="form-control input-sm" name="URL_MODULMK" id="URL_MODULMK" maxlength="200" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_MODULMK" id="DES_MODULMK" maxlength="1000" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-6">
									<label for="inputName" class="control-label">Imagem</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="">
									</div>
									<span class="help-block">(.png 300px X 80px)</span>
								</div>

							</div>



							<div class="row">

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Detalhes</label>
										<textarea name="DES_EXTRAS" id="DES_EXTRAS" style="width: 100%; height: 240px;"></textarea>
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
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable">
									<thead>
										<tr>
											<th width="40"></th>
											<th width="40"></th>
											<th>Código</th>
											<th>Certificação</th>
											<th>Nome do Módulo</th>
											<th>Abreviação</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select A.*, 
															(select B.NOM_GRUPOMODMK from GRUPOMODULOSMARKA B where B.COD_GRUPOMODMK = A.COD_GRUPOMODMK) as NOM_GRUPOMODMK	
															from MODULOSMARKA A order by A.NUM_ORDENAC";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
															<tr>
															  <td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaModulos['COD_MODULMK'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaModulos['COD_MODULMK'] . "</td>
															  <td>" . $qrBuscaModulos['NOM_GRUPOMODMK'] . "</td>
															  <td>" . $qrBuscaModulos['NOM_MODULMK'] . "</td>
															  <td>" . $qrBuscaModulos['ABV_MODULMK'] . "</td>
															  <td align='center'><span style='color:" . $qrBuscaModulos['DES_COR'] . ";' class='fal  " . $qrBuscaModulos['DES_ICONE'] . "' ></td>															  
															</tr>
															<input type='hidden' id='ret_COD_MODULMK_" . $count . "' value='" . $qrBuscaModulos['COD_MODULMK'] . "'>
															<input type='hidden' id='ret_NOM_MODULMK_" . $count . "' value='" . $qrBuscaModulos['NOM_MODULMK'] . "'>
															<input type='hidden' id='ret_ABV_MODULMK_" . $count . "' value='" . $qrBuscaModulos['ABV_MODULMK'] . "'>
															<input type='hidden' id='ret_DES_MODULMK_" . $count . "' value='" . $qrBuscaModulos['DES_MODULMK'] . "'>
															<input type='hidden' id='ret_COD_GRUPOMODMK_" . $count . "' value='" . $qrBuscaModulos['COD_GRUPOMODMK'] . "'>
															<input type='hidden' id='ret_URL_MODULMK_" . $count . "' value='" . $qrBuscaModulos['URL_MODULMK'] . "'>
															<input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrBuscaModulos['DES_COR'] . "'>
															<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBuscaModulos['DES_ICONE'] . "'>
															<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaModulos['NUM_ORDENAC'] . "'>
															<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBuscaModulos['DES_IMAGEM'] . "'>
															<input type='hidden' id='ret_DES_EXTRAS_" . $count . "' value='" . $qrBuscaModulos['DES_EXTRAS'] . "'>
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

<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(function() {

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					id = $(this).children().find('span.fa-equals').attr('data-id');
					if (index != 0 && id) {
						Ids += id + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 5);

				function execOrdenacao(p1, p2) {
					//alert(p1);
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
							// console.log(data); 
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});


		$(".table-sortable tbody").disableSelection();

	});
</script>

<script type="text/javascript">
	$(document).ready(function() {

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});

		//icon picker
		$('.btnSearchIcon').iconpicker({
			cols: 8,
			iconset: 'fontawesome',
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});

		$('.btnSearchIcon').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

	});


	function retornaForm(index) {
		$("#formulario #COD_MODULMK").val($("#ret_COD_MODULMK_" + index).val());
		$("#formulario #NOM_MODULMK").val($("#ret_NOM_MODULMK_" + index).val());
		$("#formulario #ABV_MODULMK").val($("#ret_ABV_MODULMK_" + index).val());
		$("#formulario #DES_MODULMK").val($("#ret_DES_MODULMK_" + index).val());
		$("#formulario #COD_GRUPOMODMK").val($("#ret_COD_GRUPOMODMK_" + index).val()).trigger("chosen:updated");
		$("#formulario #URL_MODULMK").val($("#ret_URL_MODULMK_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		tinyMCE.get('DES_EXTRAS').setContent($("#ret_DES_EXTRAS_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
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