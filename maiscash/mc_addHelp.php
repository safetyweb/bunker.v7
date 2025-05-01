<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_passo = $_REQUEST['step'];
// fnEscreve($cod_passo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_registro = fnLimpaCampoZero($_REQUEST['COD_REGISTRO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_sobre = addslashes(htmlentities($_REQUEST['DES_SOBRE']));
		$des_aviso1 = fnLimpaCampo($_REQUEST['DES_AVISO1']);
		$cor_aviso1 = fnLimpaCampo($_REQUEST['COR_AVISO1']);
		$des_aviso2 = fnLimpaCampo($_REQUEST['DES_AVISO2']);
		$cor_aviso2 = fnLimpaCampo($_REQUEST['COR_AVISO2']);
		$des_aviso3 = fnLimpaCampo($_REQUEST['DES_AVISO3']);
		$cor_aviso3 = fnLimpaCampo($_REQUEST['COR_AVISO3']);
		$des_img_g = fnLimpaCampo($_REQUEST['DES_IMG_G']);
		$des_img = fnLimpaCampo($_REQUEST['DES_IMG']);
		$des_imgmob = fnLimpaCampo($_REQUEST['DES_IMGMOB']);
		// if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		//fnEscreve($nom_submenus);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					// CREATE TABLE MC_CONFIG(
					// 		COD_REGISTRO INT PRIMARY KEY AUTO_INCREMENT,
					// 		COD_EMPRESA INT,
					// 		COD_PASSO INT,
					// 		DES_IMG_G VARCHAR(250),
					// 		DES_IMG VARCHAR(250),
					// 		DES_IMGMOB VARCHAR(250),
					// 		DES_AVISO1 VARCHAR(200),
					// 		COR_AVISO1 VARCHAR(10),
					// 		DES_AVISO2 VARCHAR(200),
					// 		COR_AVISO2 VARCHAR(10),
					// 		DES_AVISO3 VARCHAR(200),
					// 		COR_AVISO3 VARCHAR(10),
					// 		DES_SOBRE TEXT,
					// 		COD_USUCADA INT,
					// 		DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
					// 		COD_ALTERAC INT,
					// 		DAT_ALTERAC DATETIME
					// 		);

					$sql = "INSERT INTO MC_CONFIG(
												COD_EMPRESA,
												COD_PASSO,
												DES_IMG_G,
												DES_IMG,
												DES_IMGMOB,
												DES_AVISO1,
												COR_AVISO1,
												DES_AVISO2,
												COR_AVISO2,
												DES_AVISO3,
												COR_AVISO3,
												DES_SOBRE,
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												$cod_passo,
												'$des_img_g',
												'$des_img',
												'$des_imgmob',
												'$des_aviso1',
												'$cor_aviso1',
												'$des_aviso2',
												'$cor_aviso2',
												'$des_aviso3',
												'$cor_aviso3',
												'$des_sobre',
												$cod_usucada
											)";

					// fnEscreve($sql);				
					// fnTestesql(connTemp($cod_empresa, ''), $sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE MC_CONFIG SET
												DES_IMG_G = '$des_img_g',
												DES_IMG = '$des_img',
												DES_IMGMOB = '$des_imgmob',
												DES_AVISO1 = '$des_aviso1',
												COR_AVISO1 = '$cor_aviso1',
												DES_AVISO2 = '$des_aviso2',
												COR_AVISO2 = '$cor_aviso2',
												DES_AVISO3 = '$des_aviso3',
												COR_AVISO3 = '$cor_aviso3',
												DES_SOBRE = '$des_sobre',
												COD_ALTERAC = '$cod_usucada',
												DAT_ALTERAC = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_REGISTRO = $cod_registro";

					// fnEscreve($sql);				
					// fnTestesql(connTemp($cod_empresa, ''), $sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';

?>
			<script>
				parent.$('#REFRESH_TERMO').val('S');
			</script>
<?php

		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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


$sqlConfig = "SELECT * FROM MC_CONFIG 
				 WHERE COD_EMPRESA = $cod_empresa 
				 AND COD_PASSO = $cod_passo";

$arrayConfig = mysqli_query(connTemp($cod_empresa, ''), $sqlConfig);
$qrConfig = mysqli_fetch_assoc($arrayConfig);

if (isset($qrConfig)) {

	$cod_registro = $qrConfig['COD_REGISTRO'];
	$des_img_g = $qrConfig['DES_IMG_G'];
	$des_img = $qrConfig['DES_IMG'];
	$des_imgmob = $qrConfig['DES_IMGMOB'];
	$des_aviso1 = $qrConfig['DES_AVISO1'];
	$cor_aviso1 = $qrConfig['COR_AVISO1'];
	$des_aviso2 = $qrConfig['DES_AVISO2'];
	$cor_aviso2 = $qrConfig['COR_AVISO2'];
	$des_aviso3 = $qrConfig['DES_AVISO3'];
	$cor_aviso3 = $qrConfig['COR_AVISO3'];
	$des_sobre = $qrConfig['DES_SOBRE'];
	// if ($qrTermo['LOG_ATIVO'] == 'S') {$checkAtivo='checked';}else{$checkAtivo='';}

} else {

	$cod_registro = 0;
	$des_img_g = '';
	$des_img = '';
	$des_imgmob = '';
	$des_aviso1 = '';
	$cor_aviso1 = '';
	$des_aviso2 = '';
	$cor_aviso2 = '';
	$des_aviso3 = '';
	$cor_aviso3 = '';
	$des_sobre = '';
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
		//theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
		theme_advanced_buttons1: "formatselect,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
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

<!-- <div class="push30"></div>  -->

<div class="row">

	<div class="col-md-12">
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

					<!-- <div class="push30"></div>  -->

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">

								<div class="col-md-12">

									<fieldset>
										<legend>Dados do Termo</legend>

										<div class="row">

											<div class="col-md-4">
												<label for="inputName" class="control-label required">Imagem Grande (G)</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_G" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
													</span>
													<!-- <input type="text" name="DES_IMG_G" id="DES_IMG_G" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo @$des_img_g; ?>"> -->

													<input type="hidden" name="DES_IMG_G" id="DES_IMG_G" maxlength="100" value="<?php echo $des_img_g; ?>">
													<input type="text" name="IMG_G" id="IMG_G" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img_g); ?>">
												</div>
												<span class="help-block">(.jpg 940px X 940px)</span>
											</div>

											<div class="col-md-4">
												<label for="inputName" class="control-label required">Imagem Média (M)</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
													</span>
													<!-- <input type="text" name="DES_IMG" id="DES_IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo @$des_img; ?>"> -->

													<input type="hidden" name="DES_IMG" id="DES_IMG" maxlength="100" value="<?php echo $des_img; ?>">
													<input type="text" name="IMG" id="IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img); ?>">
												</div>
												<span class="help-block">(.jpg 540px X 960px)</span>
											</div>

											<div class="col-md-4">
												<label for="inputName" class="control-label required">Imagem Pequena (P)</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGMOB" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
													</span>
													<!-- <input type="text" name="DES_IMGMOB" id="DES_IMGMOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgmob; ?>"> -->

													<input type="hidden" name="DES_IMGMOB" id="DES_IMGMOB" maxlength="100" value="<?php echo $des_imgmob; ?>">
													<input type="text" name="IMGMOB" id="IMGMOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imgmob); ?>">
												</div>
												<span class="help-block">(.jpg 360px X 360px)</span>
											</div>

										</div>

										<div class="row">

											<div class="col-md-9">
												<div class="form-group">
													<label for="inputName" class="control-label">Aviso 1</label>
													<input type="text" class="form-control input-sm" name="DES_AVISO1" id="DES_AVISO1" value="<?php echo $des_aviso1; ?>">
													<!-- <textarea class="form-control input-sm" rows="2" name="DES_AVISO1" id="DES_AVISO1"><?= $des_aviso1 ?></textarea> -->
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label <?= $rqrCOD_ESTADOF ?>">Cor do Aviso 1</label>
													<select data-placeholder="Selecione uma cor" name="COR_AVISO1" id="COR_AVISO1" class="chosen-select-deselect">
														<option value="success">Verde</option>
														<option value="warning">Amarelo</option>
													</select>
													<script>
														$("#formulario #COR_AVISO1").val("<?php echo $cor_aviso1; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

										<div class="row">

											<div class="col-md-9">
												<div class="form-group">
													<label for="inputName" class="control-label">Aviso 2</label>
													<input type="text" class="form-control input-sm" name="DES_AVISO2" id="DES_AVISO2" value="<?php echo $des_aviso2; ?>">
													<!-- <textarea class="form-control input-sm" rows="2" name="DES_AVISO2" id="DES_AVISO2"><?= $des_aviso2 ?></textarea> -->
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label <?= $rqrCOD_ESTADOF ?>">Cor do Aviso 2</label>
													<select data-placeholder="Selecione uma cor" name="COR_AVISO2" id="COR_AVISO2" class="chosen-select-deselect">
														<option value="success">Verde</option>
														<option value="warning">Amarelo</option>
													</select>
													<script>
														$("#formulario #COR_AVISO2").val("<?php echo $cor_aviso2; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

										<div class="row">

											<div class="col-md-9">
												<div class="form-group">
													<label for="inputName" class="control-label">Aviso 3</label>
													<input type="text" class="form-control input-sm" name="DES_AVISO3" id="DES_AVISO3" value="<?php echo $des_aviso3; ?>">
													<!-- <textarea class="form-control input-sm" rows="2" name="DES_AVISO3" id="DES_AVISO3"><?= $des_aviso3 ?></textarea> -->
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label <?= $rqrCOD_ESTADOF ?>">Cor do Aviso 3</label>
													<select data-placeholder="Selecione uma cor" name="COR_AVISO3" id="COR_AVISO3" class="chosen-select-deselect">
														<option value="success">Verde</option>
														<option value="warning">Amarelo</option>
													</select>
													<script>
														$("#formulario #COR_AVISO3").val("<?php echo $cor_aviso3; ?>").trigger("chosen:updated");
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

										<div class="push10"></div>

										<div class="row">

											<div class="col-md-12">
												<div class="form-group">
													<label for="inputName" class="control-label required">Descrição do Bloco</label>
													<textarea name="DES_SOBRE" id="DES_SOBRE" style="width: 100%; height: 240px;"><?php echo $des_sobre; ?></textarea>
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
								<?php if ($cod_registro == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>
								<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_REGISTRO" id="COD_REGISTRO" value="<?= $cod_registro ?>">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<!-- <div class="push5"></div>  -->

						</form>

						<!-- <div class="push50"></div> -->

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<!-- <div class="push20"></div>  -->

	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {





		});

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

					var data = JSON.parse(data);

					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (data.success) {
						$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
						$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);
						// $("#formulario #DES_IMG").val("fgsdg");
						$.alert({
							title: "Mensagem",
							content: "Upload feito com sucesso",
							type: 'green'
						});

						// console.log(data);

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