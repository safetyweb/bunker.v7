<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$cod_template = "";

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
		$des_titulo = fnLimpaCampo($_REQUEST['DES_TITULO']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		// $des_video = fnLimpaCampo($_REQUEST['DES_VIDEO']);

		// fnEscreve(fnCHRHTML('<>/""~ç[]´'));
		// fnEscreve($des_template);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO TEMPLATE_WHATSAPP(
					COD_EMPRESA,
					LOG_ATIVO,
					NOM_TEMPLATE,
					DES_TITULO,
					ABV_TEMPLATE,
					DES_IMAGEM,
					COD_USUCADA
					)VALUES( 
					$cod_empresa,
					'$log_ativo',
					'$nom_template',
					'$des_titulo',
					'$abv_template',
					'$des_imagem',
					$cod_usucada
				)";

					mysqli_query(connTemp($cod_empresa, ''), $sql);
					//mysqli_query(connTemp($cod_empresa,''),$sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;

				case 'ALT':

					$sql = "UPDATE TEMPLATE_WHATSAPP SET
					LOG_ATIVO='$log_ativo',
					NOM_TEMPLATE='$nom_template',
					DES_TITULO='$des_titulo',
					ABV_TEMPLATE='$abv_template',
					DES_IMAGEM='$des_imagem',
					DAT_ALTERAC=NOW(),
					COD_ALTERAC=$cod_usucada
					WHERE COD_EMPRESA = $cod_empresa
					AND COD_TEMPLATE=$cod_template";

					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlCod = "SELECT COD_TEMPLATE FROM TEMPLATE SMS WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1";
					$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCod));
					$cod_template = $qrCod[COD_TEMPLATE];

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;

				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_TEMPLATES').val("S");
				} catch (err) {}
				// alert('atualiza parent');
			</script>
<?php
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_tipo = fnDecode($_GET['tipo']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idT'])))) {
	$cod_template = fnDecode($_GET['idT']);
}

$des_template = array();

if ($cod_template != "") {

	//busca dados do convênio
	$sql = "SELECT * FROM TEMPLATE_WHATSAPP WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";

	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);


	if (isset($qrBuscaTemplate)) {
		$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
		if ($qrBuscaTemplate['LOG_ATIVO'] == 'S') {
			$checkAtivo = "checked";
		} else {
			$checkAtivo = "";
		}
		$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
		$des_titulo = $qrBuscaTemplate['DES_TITULO'];
		$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
		// $des_template[0] = $qrBuscaTemplate['DES_TEMPLATE'];		
		// $des_template[1] = $qrBuscaTemplate['DES_TEMPLATE2'];		
		// $des_template[2] = $qrBuscaTemplate['DES_TEMPLATE3'];		
		// $des_template[3] = $qrBuscaTemplate['DES_TEMPLATE4'];		
		// $des_template[4] = $qrBuscaTemplate['DES_TEMPLATE5'];
		$des_imagem = $qrBuscaTemplate['DES_IMAGEM'];
		// $des_video = $qrBuscaTemplate['DES_VIDEO'];	
	}
} else {
	$checkAtivo = "checked";
	$nom_template = "";
	$des_titulo = "";
	$abv_template = "";
	$des_imagem = "";
	// $des_template[0] = $qrBuscaTemplate['DES_TEMPLATE'];
}

?>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<style type="text/css">
	.f9 {
		font-size: 9px;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #f2f2f2;
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

	.whatsapp {
		/*		  width: 300px;*/
		/*		  margin: 50px auto;*/
		border-radius: 15px;
		background: #00a884;
		color: #fff;
		padding: 20px;
		font-weight: 500;
		font-family: Helvetica;
		position: relative;
		border: none !important;
		overflow: hidden;
	}


	/* speech bubble 13 */

	.sb13:before {
		content: "";
		width: 0px;
		height: 0px;
		position: absolute;
		border-left: 15px solid #00a884;
		border-right: 15px solid transparent;
		border-top: 15px solid #00a884;
		border-bottom: 15px solid transparent;
		right: 0px;
		top: 0px;
	}


	/* speech bubble 14 */

	.sb14:before {
		content: "";
		width: 0px;
		height: 0px;
		position: absolute;
		border-left: 15px solid transparent;
		border-right: 15px solid #00a884;
		border-top: 15px solid #00a884;
		border-bottom: 15px solid transparent;
		left: -16px;
		top: 0px;
	}
</style>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-3">
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Título da Template</label>
										<input type="text" class="form-control input-sm" name="DES_TITULO" id="DES_TITULO" value="<?php echo $des_titulo ?>" maxlength="199">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<label for="inputName" class="control-label">Imagem da mensagem/Video</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?= $des_imagem ?>">
									</div>
									<span class="help-block">Caso houver</span>
								</div>

							</div>

							<div class="push10"></div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

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

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>
	<!-- modal -->
	<div class="modal fade" id="popModalEnvio" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<form id="envioTeste" action="">
						<fieldset>
							<legend>Dados do envio</legend>

							<div class="row">

								<div class="col-md-10">
									<div class="form-group">
										<label for="inputName" class="control-label">Celulares (com DDD)</label>
										<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="400">
										<div class="help-block with-errors">Separar múltiplos celulares com ";"</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push10"></div>
									<div class="push5"></div>
									<a href="javascript:void(0)" id="dispararTeste" class="btn btn-primary btn-sm btn-block getBtn" style="margin-top: 2px;"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Envio de teste</a>
								</div>

								<input type="hidden" name="COD_TEMPLATE_ENVIO" id="COD_TEMPLATE_ENVIO" value="<?= $cod_template ?>">
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</div>

						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script type="text/javascript">
		// let valor = "",
		// 	campo = "";

		$(function() {






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
			formData.append('diretorio', '../media/clientes');
			formData.append('diretorioAdicional', 'wpp');
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
					console.log(data);
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