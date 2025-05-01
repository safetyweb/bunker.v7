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
$cod_banner = "";
$des_banner = "";
$des_link = "";
$des_imagem = "";
$cod_modulos = "";
$nom_modulos = "";
$log_ativo = "";
$log_preferencia = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaUsuTeste = "";
$log_usuario = "";
$des_senhaus = "";
$des_produto = "";
$log_linkwhats = "";
$popUp = "";
$abaEmpresa = "";
$sql1 = "";
$qrListaProduto = "";
$mostraLOG_PREFERENCIA = "";
$mostraDES_IMAGEM = "";


$hashLocal = mt_rand();

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_banner = fnLimpacampoZero(@$_REQUEST['COD_BANNER']);
		$des_banner = fnLimpacampo(@$_REQUEST['DES_BANNER']);
		$des_link = fnLimpacampo(@$_REQUEST['DES_LINK']);
		$des_imagem = fnLimpacampo(@$_REQUEST['DES_IMAGEM']);
		$cod_modulos = fnLimpacampo(@$_REQUEST['COD_MODULOS']);
		$nom_modulos = fnLimpacampo(@$_REQUEST['NOM_MODULOS']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = "S";
		}
		if (empty(@$_REQUEST['LOG_PREFERENCIA'])) {
			$log_preferencia = 'N';
		} else {
			$log_preferencia = "S";
		}


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO BANNER_MARKA(
							DES_BANNER,
							COD_EMPRESA,
							DES_IMAGEM,
							COD_MODULO,
							LOG_ATIVO,
							LOG_PREFERENCIA,
							COD_USUCADA,
							DAT_CADASTR
							)VALUES(
							'$des_banner',
							'$cod_empresa',
							'$des_imagem',
							'$cod_modulos',
							'$log_ativo',
							'$log_preferencia',
							'$cod_usucada',
							 NOW())";

					//fnEscreve($sql);
					mysqli_query($adm, $sql);
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;
				case 'ALT':

					$sql = "UPDATE BANNER_MARKA SET 
							DES_BANNER = '$des_banner',
							DES_IMAGEM = '$des_imagem',
							COD_MODULO = $cod_modulos,
							LOG_ATIVO = '$log_ativo',
							LOG_PREFERENCIA ='$log_preferencia',
							COD_ALTERAC = $cod_usucada,
							DAT_ALTERAC	= NOW()		
							WHERE COD_BANNER = $cod_banner";

					//fnescreve($sql);
					mysqli_query($adm, $sql);
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;
				case 'EXC':
					$sql = "UPDATE BANNER_MARKA SET 
							DAT_EXCLUSA = NOW(),
							COD_EXCLUSA = '$cod_usucada'
							WHERE COD_BANNER = $cod_banner";
					//fnEscreve($sql);
					mysqli_query($adm, $sql);
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	/*
		$sql = "SELECT A.*,
		(select B.NOM_EMPRESA FROM empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA
		FROM EMPRESACOMPLEMENTO A where A.COD_EMPRESA = '".$cod_empresa."' ";
		*/
	$sql = "SELECT  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = $cod_empresa ";


	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}


//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO=10  limit 1 ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($adm, $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$cod_banner = "";
$des_produto = "";
$log_ativo = "";
$log_linkwhats = "";
$des_imagem = "";
$des_link = "";


//fnMostraForm();
//fnEscreve($cod_empresa);
//fnEscreve(fnDecode(@$_GET['idPrd']));


?>


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
							<i class="fal fa-terminal"></i>
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

					<?php $abaEmpresa = 1607; // include "abasEmpresaConfig.php"; 
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>

								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch">
												<span></span>
											</label>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Banner Preferêncial</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_PREFERENCIA" id="LOG_PREFERENCIA" class="switch">
												<span></span>
											</label>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Banner</label>
											<input type="text" class="form-control input-sm" name="DES_BANNER" id="DES_BANNER" value="<?= $des_banner ?>" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Imagem</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="" required>
											</div>
											<span class="help-block">(.jpg, .png 1900px X 150px recomendado)</span>
										</div>
									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label">Módulo</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477) ?>&id=<?php echo fnEncode($cod_modulos) ?>&pop=true" data-title="Busca Módulo"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
											<input type="hidden" name="COD_MODULOS" id="COD_MODULOS" value="">
										</div>
										<div class="help-block with-errors"></div>
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

							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_BANNER" id="COD_BANNER" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />

							<div class="push5"></div>

						</form>
					</div>
				</div>
				</div>

				<div class="push20"></div>

				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<div class="login-form">

							<div class="col-lg-12">

								<div class="no-more-tables">

									<form name="formLista">

										<table class="table table-bordered table-striped table-hover tableSorter">
											<thead>
												<tr>
													<th class="{ sorter: false }" width="10px"></th>
													<th>Código</th>
													<th>Banner</th>
													<th>Módulo</th>
													<th class="{sorter:false} text-center">Preferêncial</th>
													<th class="{sorter:false} text-center">Imagem</th>
												</tr>
											</thead>
											<tbody>
												<?php

												$sql1 = "SELECT COD_BANNER,
															COD_MODULO,
															DES_BANNER,
															DES_IMAGEM,
															LOG_ATIVO,
															LOG_PREFERENCIA,
															M.NOM_MODULOS 
													FROM BANNER_MARKA B
													LEFT JOIN MODULOS M	ON M.COD_MODULOS = B.COD_MODULO
													WHERE COD_EXCLUSA = 0
																										 
											";

												//fnEscreve($sql1);
												$arrayQuery = mysqli_query($adm, $sql1);

												$count = 0;
												while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
													$count++;
													// fnEscreve($qrListaProduto['LOG_LINKWHATS']);

													if ($qrListaProduto['LOG_PREFERENCIA'] == "S") {
														$mostraLOG_PREFERENCIA = '<i class="fal fa-check" aria-hidden="true"></i>';
													} else {
														$mostraLOG_PREFERENCIA = '';
													}
													if ($qrListaProduto['DES_IMAGEM'] != "") {
														$mostraDES_IMAGEM = '<i class="fal fa-check" aria-hidden="true"></i>';
													} else {
														$mostraDES_IMAGEM = '';
													}

													echo "
														<tr>
															<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															<td>" . $qrListaProduto['COD_BANNER'] . "</td>
															<td>" . $qrListaProduto['DES_BANNER'] . "</td>
															<td>" . $qrListaProduto['COD_MODULO'] . "</td>
															<td class='text-center'>" . $mostraLOG_PREFERENCIA . "</td>
															<td class='text-center'>" . $mostraDES_IMAGEM . "</td>

														</tr>
														<input type='hidden' id='ret_COD_BANNER_" . $count . "' value='" . $qrListaProduto['COD_BANNER'] . "'>  
														<input type='hidden' id='ret_DES_BANNER_" . $count . "' value='" . $qrListaProduto['DES_BANNER'] . "'>
														<input type='hidden' id='ret_COD_MODULO_" . $count . "' value='" . $qrListaProduto['COD_MODULO'] . "'>
														<input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrListaProduto['LOG_ATIVO'] . "'>
														<input type='hidden' id='ret_NOM_MODULOS_" . $count . "' value='" . $qrListaProduto['NOM_MODULOS'] . "'>
														<input type='hidden' id='ret_LOG_PREFERENCIA_" . $count . "' value='" . $qrListaProduto['LOG_PREFERENCIA'] . "'>
														<input type='hidden' id='ret_LOG_LINKWHATS_" . $count . "' value='" . @$qrListaProduto['LOG_LINKWHATS'] . "'>
														<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrListaProduto['DES_IMAGEM'] . "'>
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
							<div class="modal-footer">
								<button type="button" id="mymodal" class="btn btn-default" data-dismiss="modal">Close</button>

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

					});

					// ajax
					$("#COD_CATEGOR").change(function() {
						var codBusca = $("#COD_CATEGOR").val();
						var codBusca3 = $("#COD_EMPRESA").val();
						buscaSubCat(codBusca, 0, codBusca3);
					});

					function retornaForm(index) {
						$("#formulario #COD_BANNER").val($("#ret_COD_BANNER_" + index).val());
						$("#formulario #DES_BANNER").val($("#ret_DES_BANNER_" + index).val());
						$("#formulario #DES_LINK").val($("#ret_DES_LINK_" + index).val());
						$("#formulario #NOM_MODULOS").val($("#ret_NOM_MODULOS_" + index).val());

						$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());

						if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
							$('#formulario #LOG_ATIVO').prop('checked', true);
						} else {
							$('#formulario #LOG_ATIVO').prop('checked', false);
						}
						if ($("#ret_LOG_PREFERENCIA_" + index).val() == 'S') {
							$('#formulario #LOG_PREFERENCIA').prop('checked', true);
						} else {
							$('#formulario #LOG_PREFERENCIA').prop('checked', false);
						}
						console.log($("#ret_LOG_ATIVO_" + index).val())

						if ($("#ret_LOG_LINKWHATS_" + index).val() == 'S') {
							$('#formulario #LOG_LINKWHATS').prop('checked', true);
						} else {
							$('#formulario #LOG_LINKWHATS').prop('checked', false);
						}

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

					function upload_check(size) {
						var max = 1048576 / 5;

						if (size > max) {
							return 0;
						} else {
							return 1;
						}
					}

					function uploadFile(idField, typeFile) {
						var formData = new FormData();
						var nomeArquivo = $('#' + idField)[0].files[0]['name'];

						formData.append('arquivo', $('#' + idField)[0].files[0]);
						formData.append('diretorio', '../media/clientes/');
						formData.append('diretorioAdicional', 'banner');
						formData.append('id', 0);
						formData.append('typeFile', typeFile);

						if (!upload_check($('#' + idField)[0].files[0]['size'])) {

							$('#' + idField).val("");

							$.alert({
								title: "Mensagem",
								content: "O arquivo que você está tentando enviar é muito grande! Tente novamente com um arquivo de 200 KB ou menor.",
								type: 'yellow'
							});

							return false;
						}

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