<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

// echo $cod_empresa;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request'] = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$cod_atestado = fnLimpacampoZero($_REQUEST['COD_ATESTADO']);
		$des_img_atestado = fnLimpaCampo($_REQUEST['DES_IMG_ATESTADO']);

        //print_r($_REQUEST);exit;
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

		$nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

        //fnEscreve($des_icones);

		if ($opcao != '') {

            //mensagem de retorno
			switch ($opcao) {

				case 'CAD':

				$sql = "INSERT INTO atestados_colaborador (
					COD_EMPRESA,
					DAT_INI,
					DAT_FIM,
					DES_IMG_ATESTADO,
					COD_CLIENTE,
					COD_USUCADA,
					DAT_CADASTR
					) VALUES (
					$cod_empresa,
					'$dat_ini',
					'$dat_fim',
					'$des_img_atestado',
					$cod_cliente,
					$cod_usucada,
					NOW()
				)";

					$array = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));


					if (!$array) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
					case 'ALT':

					$sql = "UPDATE atestados_colaborador SET
					DAT_INI = '$dat_ini',
					DAT_FIM = '$dat_fim',
					DES_IMG_ATESTADO = '$des_img_atestado',
					COD_ALTERAC = $cod_usucada,
					DAT_ALTERAC = NOW()
					WHERE COD_ATESTADO = $cod_atestado AND COD_EMPRESA = $cod_empresa
					";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;

					case 'EXC':

					$sql = "UPDATE atestados_colaborador SET
					COD_EXCLUSA = $cod_usucada,
					DAT_EXCLUSA = NOW()
					WHERE COD_ATESTADO = $cod_atestado";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
				}
				$msgTipo = 'alert-success';

			}
		}
	}

//busca dados da url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);

		$sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
		left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
		where EMPRESAS.COD_EMPRESA = $cod_empresa ";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaEmpresa)) {
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	} else {
		$cod_empresa = 0;
	}

        //busca dados do ben
	if (is_numeric(fnLimpaCampoZero(fnDecode($_GET['idC'])))) {  

		$cod_cliente = fnDecode($_GET['idC']);
		$sqlCliente = "SELECT * FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCliente);

		if($qrBuscaCliente = mysqli_fetch_assoc($arrayQuery)){
			$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
			$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
		}else{
			$cod_cliente = 0;
			$nom_cliente = "";
		}
	}

        //inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
		$dat_ini = fnDataSql($dias30);
	}
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
		$dat_fim = fnDataSql($hoje);
	}


	?>

	<?php if ($popUp != "true"){  ?>                            
		<div class="push30"></div> 
	<?php } ?>

	<div class="row">               

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<?php if ($popUp != "true"){  ?>                            
				<div class="portlet portlet-bordered">
				<?php } else { ?>
					<div class="portlet" style="padding: 0 20px 20px 20px;" >
					<?php } ?>

					<?php if ($popUp != "true"){  ?>
						<div class="portlet-title">
							<div class="caption">
								<i class="glyphicon glyphicon-calendar"></i>
								<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
							</div>
							<?php include "atalhosPortlet.php"; ?>
						</div>
					<?php } ?>

					<div class="push10"></div>

					<div class="portlet-body">

						<?php if ($msgRetorno != '') { ?>
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<?php echo $msgRetorno; ?>
							</div>
						<?php } ?>

						<?php 
							//menu superior - cliente
							
							$abaCli = 2073;						
							include "abasClienteRH.php";

						?>


						<div class="push30"></div>


						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Dados Gerais</legend>

									<div class="row">

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Colaborador</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
											</div>														
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data Inicial</label>

												<div class="input-group date datePicker" id="DAT_INI_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Data Final</label>

												<div class="input-group date datePicker" id="DAT_FIM_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-3">
											<label for="inputName" class="control-label">Atestado</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_ATESTADO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="hidden" name="DES_IMG_ATESTADO" id="DES_IMG_ATESTADO" maxlength="100" value="">
												<input type="text" name="IMG_ATESTADO" id="IMG_ATESTADO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="">
											</div>
											<span class="help-block">(.jpg 940px X 845px)</span>
										</div>

									</div>

								</fieldset>

								<div class="push10"></div>
								<hr>
								<div class="form-group text-right col-lg-12">

									<button type="reset" class="btn btn-default" onclick="resetForm()"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

								</div>

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
								<input type="hidden" name="COD_ATESTADO" id="COD_ATESTADO" value="<?=$cod_atestado?>">
								<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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
													<th>Cód. Atestado</th>
													<th>Data Inicial</th>
													<th>Data Final</th>
													<th>Arquivo</th>
													<th class="tab { sorter: false }" width="40"></th>
												</tr>
											</thead>
											<tbody>

												<?php 


												$sql = "SELECT * FROM ATESTADOS_COLABORADOR 
																WHERE COD_EMPRESA = $cod_empresa 
																AND COD_CLIENTE = $cod_cliente
																AND COD_EXCLUSA IS NULL";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ""),$sql);

												$count=0;
												while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
												{														  
													$count++;

													echo"
													<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
													<td><small>".$qrListaUniVendas['COD_ATESTADO']."</td>
													<td><small>".fnDataShort($qrListaUniVendas['DAT_INI'])."</td>
													<td><small>".fnDataShort($qrListaUniVendas['DAT_FIM'])."</td>
													<td><small>".fnBase64DecodeImg($qrListaUniVendas['DES_IMG_ATESTADO'])."</td>
													<td class='text-center'><a href='media/clientes/" . $cod_empresa . "/" . $qrListaUniVendas['DES_IMG_ATESTADO'] . "' target='_blank'><span class='fas fa-download'></span></a></td>
													</tr>
													<input type='hidden' id='ret_COD_ATESTADO_".$count."' value='".$qrListaUniVendas['COD_ATESTADO']."'>
													<input type='hidden' id='ret_DAT_INI_".$count."' value='".fnDataShort($qrListaUniVendas['DAT_INI'])."'>
													<input type='hidden' id='ret_DAT_FIM_".$count."' value='".fnDataShort($qrListaUniVendas['DAT_FIM'])."'>
													<input type='hidden' id='ret_DES_IMG_ATESTADO_".$count."' value='".$qrListaUniVendas['DES_IMG_ATESTADO']."'>
													<input type='hidden' id='ret_IMG_ATESTADO_".$count."' value='".fnBase64DecodeImg($qrListaUniVendas['DES_IMG_ATESTADO'])."'>
													"; 
												}																	

												?>

											</tbody>

										</table>

									</form>

								</div>

							</div>

							<div class="push50"></div>

						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			</div>
			<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
			<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
			<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
			<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

		</div>

		<div class="push20"></div>

		<script type="text/javascript">
			$(document).ready(function() {

				$('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
					format: 'DD/MM/YYYY'
				}).on('changeDate', function(e) {
					$(this).datetimepicker('hide');
				});

				$("#DAT_INI_GRP").on("dp.change", function(e) {
					$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
				});

				$("#DAT_FIM_GRP").on("dp.change", function(e) {
					$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
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

								$.ajax({
									type: "POST",
									url: "ajxImgTermos.php",
									data: {
										COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
										NOM_ARQ: data.nome_arquivo,
										CAMPO: idField
									},
									success: function(data) {
										console.log(data);
										$.alert({
											title: "Mensagem",
											content: "Upload feito com sucesso",
											type: 'green'
										});
									}
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

			function retornaForm(index, idBem) {

				$("#formulario #COD_ATESTADO").val($("#ret_COD_ATESTADO_" + index).val());
				$("#formulario #DAT_INI").val($("#ret_DAT_INI_" + index).val());
				$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val());
				$("#formulario #DES_IMG_ATESTADO").val($("#ret_DES_IMG_ATESTADO_" + index).val());
				$("#formulario #IMG_ATESTADO").val($("#ret_IMG_ATESTADO_" + index).val());

				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');

			}

		</script>