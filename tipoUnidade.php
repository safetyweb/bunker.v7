<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_tipo = fnLimpaCampoZero($_REQUEST['COD_TIPO']);
		$des_tipo = fnLimpaCampo($_REQUEST['DES_TIPO']);
		$des_img = fnLimpaCampo($_REQUEST['DES_IMAGEM']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {


			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

				$sql = "INSERT INTO TIPO_UNIDADE(
												COD_EMPRESA,
												DES_TIPO,
												DES_IMG,
												COD_USUCADA
											) VALUES(
												'$cod_empresa',
												'$des_tipo',
												'$des_img',
												'$cod_usucada'
											)";

					$arrayProc = mysqli_query($adm, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sql = "UPDATE TIPO_UNIDADE SET
									DES_TIPO = '$des_tipo',
									DES_IMG = '$des_img'
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_TIPO = $cod_tipo";

					$arrayProc = mysqli_query($adm, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':

					$sql = "DELETE FROM TIPO_UNIDADE
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_TIPO = $cod_tipo";

					$arrayProc = mysqli_query($adm, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php 
									//menu superior - empresas
				$abaEmpresa = 1023;	
				
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasEmpresaDuque.php";
						break;
					case 15: //quiz
						include "abasEmpresaQuiz.php";
						break;
					case 16: //gabinete
						include "abasGabinete.php";
						break;
					case 18: //mais cash
						include "abasMaisCash.php";
						break;
					case 19: //rh
						include "abasRH.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						//$formBack = "1019";
						break;
				}
				
				?>	

				<div class="push30"></div>

				<?php

				$abaUniv = fnDecode($_GET['mod']);
				//echo $abaUsuario;
				include "abasUnidadesEmpresa.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>
								<div class="row">


									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição da Bandeira</label>
											<input type="text" class="form-control input-sm" name="DES_TIPO" id="DES_TIPO" value="" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Imagem/Logo</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="" required>
											</div>
											<span class="help-block">(.jpg, .png 55px X 55px recomendado)</span>
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
							<input type="hidden" name="COD_TIPO" id="COD_TIPO" value="">
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
											<th>Bandeira</th>
											<th>Imagem</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * from TIPO_UNIDADE where cod_empresa = $cod_empresa";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaModulos['DES_IMG'] != "") {
												$mostraDES_IMAGEM = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraDES_IMAGEM = '';
											}

											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_TIPO'] . "</td>
														<td>" . $qrBuscaModulos['DES_TIPO'] . "</td>
														<td>" . $mostraDES_IMAGEM . "</td>
													</tr>
													<input type='hidden' id='ret_COD_TIPO_" . $count . "' value='" . $qrBuscaModulos['COD_TIPO'] . "'>
													<input type='hidden' id='ret_DES_TIPO_" . $count . "' value='" . $qrBuscaModulos['DES_TIPO'] . "'>
													<input type='hidden' id='ret_DES_IMG_" . $count . "' value='" . $qrBuscaModulos['DES_IMG'] . "'>
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
	$(function(){

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

	function retornaForm(index) {
			$("#formulario #COD_TIPO").val($("#ret_COD_TIPO_" + index).val());
			$("#formulario #DES_TIPO").val($("#ret_DES_TIPO_" + index).val());
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMG_" + index).val());
			

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

</script>