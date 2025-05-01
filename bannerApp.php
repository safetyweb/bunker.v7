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
$log_ativo = "";
$log_linkwhats = "";
$log_parceiro = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaUsuTeste = "";
$log_usuario = "";
$des_senhaus = "";
$des_produto = "";
$popUp = "";
$abaEmpresa = "";
$sql1 = "";
$qrListaProduto = "";
$mostraLOG_ATIVO = "";
$mostraLOG_WHATS = "";
$mostraDES_IMAGEM = "";


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

		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_banner = fnLimpacampoZero(@$_REQUEST['COD_BANNER']);
		$des_banner = fnLimpacampo(@$_REQUEST['DES_BANNER']);
		$des_link = fnLimpacampo(@$_REQUEST['DES_LINK']);
		$des_imagem = fnLimpacampo(@$_REQUEST['DES_IMAGEM']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = "S";
		}
		if (empty(@$_REQUEST['LOG_LINKWHATS'])) {
			$log_linkwhats = 'N';
		} else {
			$log_linkwhats = "S";
		}
		$log_parceiro = 'N';

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_BANNER_APP (
				 '" . $cod_banner . "', 
				 '" . $cod_empresa . "',				
				 '" . $log_ativo . "',				
				 '" . $log_parceiro . "',				
				 '" . $des_banner . "',				
				 '" . $log_linkwhats . "', 
				 '" . $des_link . "', 
				 '" . $des_imagem . "',				 			 
				 '" . $cod_usucada . "',
				 '" . $opcao . "'   
				) ";

			// fnEscreve($sql);
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
	$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
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

					<?php $abaEmpresa = 1607;
					include "abasEmpresaConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<div class="row">


									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Banner</label>
											<input type="text" class="form-control input-sm" name="DES_BANNER" id="DES_BANNER" value="" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors">Mensagem WhatsApp</div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Imagem</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" required>
												<input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="" required>
											</div>
											<span class="help-block">(.jpg, .png 1260px X 1750px recomendado)</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Banner Ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch">
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Link Whatsapp</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_LINKWHATS" id="LOG_LINKWHATS" class="switch">
												<span></span>
											</label>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Link / Url</label>
											<input type="text" class="form-control input-sm" name="DES_LINK" id="DES_LINK" value="" maxlength="200" data-error="Campo obrigatório">
											<div class="help-block with-errors">Telefone WhatsApp (sem pontos/traços)</div>
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

							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_BANNER" id="COD_BANNER" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tableSorter">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Código</th>
												<th>Bannner</th>
												<th>Url</th>
												<th class="{sorter:false} text-center">Ativo</th>
												<th class="{sorter:false} text-center">Imagem</th>
												<th class="{sorter:false} text-center">WhatsApp</th>
											</tr>
										</thead>
										<tbody>


											<?php

											$sql1 = "select A.* from BANNER_APP A 
														where A.COD_EMPRESA='" . $cod_empresa . "'  
														AND A.LOG_PARCEIRO = 'N'
														AND A.COD_EXCLUSA=0 order by A.COD_BANNER ";

											//fnEscreve($sql);
											$arrayQuery = mysqli_query($conn, $sql1);

											$count = 0;
											while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												// fnEscreve($qrListaProduto['LOG_LINKWHATS']);

												if ($qrListaProduto['LOG_ATIVO'] == "S") {
													$mostraLOG_ATIVO = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraLOG_ATIVO = '';
												}

												if ($qrListaProduto['LOG_LINKWHATS'] == "S") {
													$mostraLOG_WHATS = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraLOG_WHATS = '';
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
															<td>" . $qrListaProduto['DES_LINK'] . "</td>
															<td class='text-center'>" . $mostraLOG_ATIVO . "</td>
															<td class='text-center'>" . $mostraDES_IMAGEM . "</td>
															<td class='text-center'>" . $mostraLOG_WHATS . "</td>
														</tr>
														<input type='hidden' id='ret_COD_BANNER_" . $count . "' value='" . $qrListaProduto['COD_BANNER'] . "'>  
														<input type='hidden' id='ret_DES_BANNER_" . $count . "' value='" . $qrListaProduto['DES_BANNER'] . "'>
														<input type='hidden' id='ret_DES_LINK_" . $count . "' value='" . $qrListaProduto['DES_LINK'] . "'>
														<input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrListaProduto['LOG_ATIVO'] . "'>
														<input type='hidden' id='ret_LOG_LINKWHATS_" . $count . "' value='" . $qrListaProduto['LOG_LINKWHATS'] . "'>
														<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . fnBase64DecodeImg($qrListaProduto['DES_IMAGEM']) . "'>
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

			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
			$("#formulario #IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());

			if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
				$('#formulario #LOG_ATIVO').prop('checked', true);
			} else {
				$('#formulario #LOG_ATIVO').prop('checked', false);
			}

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
			formData.append('id', <?php echo $cod_empresa ?>);
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

					var data = JSON.parse(data);

					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (data.success) {
						$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
						$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);

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