<?php

//echo fnDebug('true');

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
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_anexo = fnLimpaCampoZero($_REQUEST['COD_ANEXO']);
		$cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
		$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
		$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
		$caminho_anexo = fnLimpaCampo($_REQUEST['CAMINHO_ANEXO']);
		$cod_usucada = $_SESSION[SYS_COD_USUARIO];
		$des_anexo = fnLimpaCampo($_REQUEST['DES_ANEXO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao != '') {

			switch ($opcao) {
				case 'CAD':
				$sql = "INSERT INTO ANEXO_AVALIABEM (
					COD_EMPRESA,
					COD_CLIENTE,
					COD_BEM,
					DES_ANEXO,
					CAMINHO_ANEXO,
					COD_USUCADA
					)values(
					$cod_empresa,
					$cod_cliente,
					$cod_bem,
					'$des_anexo',
					'$caminho_anexo',
					$cod_usucada
				)";
					mysqli_query($conn,$sql);

					$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt,$nom_usuario);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;


					case 'ALT':

					$sql = "UPDATE ANEXO_AVALIABEM SET
					DES_ANEXO = '$des_anexo',
					CAMINHO_ANEXO = '$caminho_anexo',
					COD_ALTERAC = '$cod_usucada',
					DAT_ALTERAC = NOW()
					WHERE COD_ANEXO = '$cod_anexo' 
					AND COD_EMPRESA = '$cod_empresa' 
					AND COD_BEM = '$cod_bem' 
					AND COD_CLIENTE = '$cod_cliente'
					";

					mysqli_query($conn,$sql);

					$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt,$nom_usuario);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;


					case 'EXC':

					$sql = "UPDATE ANEXO_AVALIABEM SET
					COD_EXCLUSA = '$cod_usucada',
					DAT_EXCLUSA = NOW()
					WHERE COD_ANEXO = '$cod_anexo' 
					AND COD_EMPRESA = '$cod_empresa' 
					AND COD_BEM = '$cod_bem' 
					AND COD_CLIENTE = '$cod_cliente'
					";

					mysqli_query($conn,$sql);

					$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt,$nom_usuario);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
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
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}

	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}

	if(is_numeric(fnLimpaCampoZero(fnDecode($_GET['idC'])))){

		$cod_cliente = fnDecode($_GET['idC']);
    //fnEscreve2($cod_cliente);
		$sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES where COD_CLIENTE = '". $cod_cliente . "' AND COD_EMPRESA = '". $cod_empresa . "' ";

    //fnEscreve2($sql);
		$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$qrBuscaCliente = mysqli_fetch_assoc($query);

		if(isset($query)) {
			$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
			$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
		}else{
			$cod_cliente = 0;
		}
	}

	if(is_numeric(fnLimpaCampoZero(fnDecode($_GET['idBem'])))){

		$cod_bem = fnDecode($_GET['idBem']);
	//fnEscreve($cod_bem);

		$sql = "SELECT * FROM BENS_CLIENTE WHERE COD_BEM = '$cod_bem' AND COD_CLIENTE = '$cod_cliente' AND COD_EMPRESA = '$cod_empresa'";

		$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$qrBusca = mysqli_fetch_assoc($query);

		if(isset($query)) {
			$cod_bem = $qrBusca['COD_BEM'];
			$nom_bem = $qrBusca['DES_NOMEBEM'];
		}else {
			$cod_bem = 0;
		}

	}

	?>

	<div class="push30"></div>

	<div class="row">

		<div class="col-md12 margin-bottom-30">
			<div class="portlet portlet-bordered">

				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?></span>
					</div>
					<?php include "atalhosPortlet.php"; ?>
				</div>

				<?php
				$abaBens = 1927;
				include "abasBens.php";
				?>
				<div class="push10"></div>
				<?php 
				$abaAvaliaBens = 1989;
				include "abasAvaliaBens.php";
				?>
				<div class="push20"></div>
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

							<?php include "bensHeader.php"; ?>

							<div class="push10"></div>

							<fieldset>

								<legend>Documentos do Bem</legend>

								<div class="row">
									<div class="col-md-3">
										<label for="inputName" class="control-label required">Arquivo</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ANEXO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_ANEXO" id="DES_ANEXO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="">
										</div>
										<span class="help-block">(.jpg 940px X 845px)</span>
									</div>
								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group col-lg-6">
							</div>
							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							</div>

							<div class="push20"></div>
							<div class="push20"></div>
							<div>
								<div class="row">
									<div class="col-md-12">

										<div class="push20"></div>

										<table class="table table-bordered table-hover tablesorter">

											<thead>
												<tr>
													<th class="{sorter:false}" width="40"></th>
													<th>Cód Anexo</th>
													<th>Imagem</th>
													<th>Data de Envio</th>
												</tr>
											</thead>

											<tbody id="relatorioConteudo">

												<?php

												$sql = "SELECT * FROM ANEXO_AVALIABEM
												WHERE COD_BEM = '$cod_bem' AND
												COD_EMPRESA = '$cod_empresa' AND
												COD_CLIENTE = '$cod_cliente' AND
												DAT_EXCLUSA IS NULL	
												";

												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

												$count=0;
												while ($qrAnexo = mysqli_fetch_assoc($arrayQuery))
												{
													$count++;		

													echo"
													<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
													<td class=''>".$qrAnexo['COD_ANEXO']."</td>
													<td class=''>".$qrAnexo['DES_ANEXO']."</td>
													<td class=''>".fnFormatDate($qrAnexo['DAT_CADASTR'])."</td>
													<input type='hidden' id='ret_COD_ANEXO_" . $count . "' value='" . $qrAnexo['COD_ANEXO'] . "'>
													<input type='hidden' id='ret_CAMINHO_ANEXO_" . $count . "' value='" . $qrAnexo['CAMINHO_ANEXO'] . "'>
													<input type='hidden' id='ret_DES_ANEXO_" . $count . "' value='" . $qrAnexo['DES_ANEXO'] . "'>
													</tr>
													"; 
												}											

												?>
											</tbody>

											<tfoot>
												<tr>
													<th class="" colspan="100">
														<center>
															<ul id="paginacao" class="pagination-sm"></ul>
														</center>
													</th>
												</tr>
											</tfoot>

										</table>

									</div>

								</div>
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="CAMINHO_ANEXO" id="CAMINHO_ANEXO" value="<?php echo $caminho_anexo; ?>" />
							<input type="hidden" name="COD_ANEXO" id="COD_ANEXO" value="<?php echo $cod_anexo; ?>" />
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" />
							<input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push10"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>

	<div class="push100"></div>

	<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
	<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {

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

		function retornaForm(index) {
			$("#formulario #COD_ANEXO").val($("#ret_COD_ANEXO_" + index).val());
			$("#formulario #CAMINHO_ANEXO").val($("#ret_CAMINHO_ANEXO_" + index).val());
			$("#formulario #DES_ANEXO").val($("#ret_DES_ANEXO_" + index).val());
		}


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

					//guardando caminho do upload para usar depois
					var pathLogo = 'media/clientes/<?php echo $cod_empresa ?>/' + nomeArquivo;

					$('#CAMINHO_ANEXO').val(pathLogo);
					//--------------------------------------------------
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