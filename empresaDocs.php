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
$cod_documento = "";
$nom_documento = "";
$nom_arquivo = "";
$cod_usucada = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$qrInsert = "";
$cod_erro = "";
$sqlUpdate = "";
$qrUpdate = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$personas = "";
$campanhas = "";
$log_configu = "";
$abaEmpresa = "";
$qrDocs = "";


//echo fnDebug('true');

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

		$cod_documento = fnLimpaCampoZero(@$_REQUEST['COD_DOCUMENTO']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$nom_documento = fnLimpaCampo(@$_REQUEST['NOM_DOCUMENTO']);
		$nom_arquivo = fnLimpaCampo(@$_REQUEST['NOM_ARQUIVO']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

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

					$sql = "INSERT INTO DOCUMENTOS_EMPRESA(
											COD_EMPRESA,
											NOM_DOCUMENTO,
											NOM_ARQUIVO,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											'$nom_documento',
											'$nom_arquivo',
											$cod_usucada
											)";

					//echo $sql;

					$qrInsert = mysqli_query($conn, trim($sql));

					if (!$qrInsert) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

					break;
				case 'ALT':

					$sqlUpdate = "UPDATE DOCUMENTOS_EMPRESA SET
											NOM_DOCUMENTO='$nom_documento',
											NOM_ARQUIVO='$nom_arquivo',
											COD_USUCADA=$cod_usucada
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_DOCUMENTO = $cod_documento";

					// fnEscreve($sqlUpdate);

					$qrUpdate = mysqli_query($conn, trim($sqlUpdate));

					if (!$qrUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

					break;
				case 'EXC':

					$sqlUpdate = "DELETE FROM DOCUMENTOS_EMPRESA 
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_DOCUMENTO = $cod_documento";

					// fnEscreve($sqlUpdate);

					$qrUpdate = mysqli_query($conn, trim($sqlUpdate));
					if (!$qrUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
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
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}


//fnEscreve($personas);
//fnEscreve($campanhas);	
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

				<?php
				if ($log_configu == "N") {
				?>
					<div class="alert alert-warning top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Sua empresa <b>ainda não está </b> totalmente configurada e pronta pra uso. <br />
					</div>
				<?php
				}
				?>

				<?php
				//menu superior - empresas
				$abaEmpresa = 1488;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasEmpresaDuque.php";
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DOCUMENTO" id="COD_DOCUMENTO" value="">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Documento</label>
										<input type="text" class="form-control input-sm" name="NOM_DOCUMENTO" id="NOM_DOCUMENTO" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<label for="inputName" class="control-label required">Anexo</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ARQUIVO" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="ARQUIVO" id="ARQUIVO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="">
									</div>
									<span class="help-block">Tamanho máximo: 20MB</span>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" maxlength="100">

						<div class="push5"></div>

					</form>

					<div class="push30"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter buscavel">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Nome do Documento</th>
											<th>Data de Cadastro</th>
											<th>Usuário</th>
											<th>Nome do Arquivo</th>
											<th class="tab { sorter: false }" width="40"></th>
										</tr>
									</thead>
									<tbody>
										<?php

										$sql = "SELECT DE.*, US.NOM_USUARIO FROM DOCUMENTOS_EMPRESA DE
									                INNER JOIN WEBTOOLS.USUARIOS US ON US.COD_USUARIO = DE.COD_USUCADA
									                WHERE DE.COD_EMPRESA = $cod_empresa";

										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;

										while ($qrDocs = mysqli_fetch_assoc($arrayQuery)) {
											$nomArquivo = isset($qrDocs['NOM_ARQUIVO']) && $qrDocs['NOM_ARQUIVO'] != ''
												? fnBase64DecodeImg($qrDocs['NOM_ARQUIVO'])
												: 0;

											echo "
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
													<td>" . $qrDocs['NOM_DOCUMENTO'] . "</td>
													<td>" . fnDataShort($qrDocs['DAT_CADASTR']) . "</td>
													<td>" . $qrDocs['NOM_USUARIO'] . "</td>
													<td>" . $nomArquivo . "</td>
													<td class='text-center'>
														<a href='media/clientes/" . $cod_empresa . "/documentos/" . $qrDocs['NOM_ARQUIVO'] . "' target='_blank'>
															<span class='fas fa-download'></span>
														</a>
													</td>
												</tr>
												<input type='hidden' id='ret_COD_DOCUMENTO_" . $count . "' value='" . $qrDocs['COD_DOCUMENTO'] . "'>
												<input type='hidden' id='ret_NOM_DOCUMENTO_" . $count . "' value='" . $qrDocs['NOM_DOCUMENTO'] . "'>
												<input type='hidden' id='ret_NOM_ARQUIVO_" . $count . "' value='" . ($qrDocs['NOM_ARQUIVO'] != '' ? fnBase64DecodeImg($qrDocs['NOM_ARQUIVO']) : '') . "'>
												";

											$count++;
										}


										?>



									</tbody>
								</table>

							</form>

						</div>

					</div>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
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
		formData.append('diretorioAdicional', 'documentos');
		formData.append('id', <?php echo json_encode($cod_empresa); ?>);
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
			success: function(response) {
				// Parse the JSON response
				var data = JSON.parse(response);

				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});

				if (data.success) {
					$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
					$('#NOM_ARQUIVO').val(data.nome_arquivo);
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
				$('#btnUploadFile').removeClass('disabled');
				$('.progress-bar').css('width', "0%");
				$('.progress-bar > span').html("0%");
			},
			error: function() {
				$.alert({
					title: "Erro",
					content: "Erro ao comunicar com o servidor.",
					type: 'red'
				});
				$('#btnUploadFile').removeClass('disabled');
				$('.progress-bar').css('width', "0%");
				$('.progress-bar > span').html("0%");
			}
		});
	}


	function retornaForm(index) {
		$("#formulario #COD_DOCUMENTO").val($("#ret_COD_DOCUMENTO_" + index).val());
		$("#formulario #NOM_DOCUMENTO").val($("#ret_NOM_DOCUMENTO_" + index).val());
		$("#formulario #ARQUIVO").val($("#ret_NOM_ARQUIVO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>