
<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_anexo = fnLimpaCampoZero($_REQUEST['COD_ANEXO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$tip_doc = fnLimpaCampo($_REQUEST['TIP_DOC']);
		$des_doc = fnLimpaCampo($_REQUEST['DES_DOC']);
		$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$conn = conntemp($cod_empresa,"");
		$adm = $connAdm->connAdm();

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {

				case 'CAD':

					$sql = "INSERT INTO ANEXO_DOCUMENTO(
											COD_EMPRESA,
											COD_CLIENTE,
											TIP_DOC,
											DES_DOC,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											$cod_cliente,
											'$tip_doc',
											'$des_doc',
											$cod_usucada	
										)";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if($cod_recebim == 0){

						$sqlCod = "SELECT MAX(COD_ANEXO) COD_ANEXO FROM ANEXO_DOCUMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_anexo = $qrCod[COD_ANEXO];

						$sqlArquivos = "SELECT 1 FROM ANEXO_DOC WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						// fnEscreve($sqlArquivos);
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_DOC SET COD_ANEXO = $cod_anexo, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

					}else{
						// $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
						// mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

				break;

				case 'ALT':

					$sql = "UPDATE ANEXO_DOCUMENTO SET
											TIP_DOC = '$tip_doc',
											DES_DOC = '$des_doc',
											COD_ALTERAC = $cod_usucada,
											DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_ANEXO = $cod_anexo";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

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

					$sql = "UPDATE ANEXO_DOCUMENTO SET
											COD_EXCLUSA = $cod_usucada,
											DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_ANEXO = $cod_anexo";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

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
	$cod_cliente = fnDecode($_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	//echo($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

} else {
	$cod_empresa = 0;
}

$sqlCli = "SELECT CL.COD_UNIVEND, CL.NUM_CARTAO, CL.NOM_CLIENTE FROM CLIENTES CL
			LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
			LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
			WHERE CL.COD_EMPRESA = $cod_empresa 
			AND CL.COD_CLIENTE = $cod_cliente";

$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_univend = $qrCli[COD_UNIVEND];
$num_cartao = $qrCli[NUM_CARTAO];
$nom_cliente = $qrCli[NOM_CLIENTE];

//fnMostraForm();

$tp_cont = 'Anexo de Documentos';
$tp_anexo = 'COD_ANEXO';
$cod_tpanexo = 'COD_ANEXO';
$cod_busca = $cod_anexo;

$sqlUpdtCont = "DELETE FROM ANEXO_DOC WHERE COD_EMPRESA = $cod_empresa AND COD_ANEXO = 0 AND LOG_STATUS = 'N'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";

// fnEscreve($sqlCont);
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
$num_contador = $qrCont['NUM_CONTADOR'];

?>

<style type="text/css">
	.badge{
	    display: table;
	    border-radius: 30px 30px 30px 30px;
	    width: 20px;
	    height: 20px;
	    text-align: center;
	    color:white;
	    font-size:8px;
	    margin-right: auto;
	    margin-left: auto;
	    /*cursor: help;*/
	}

	.txtBadge{
		display: table-cell;
		vertical-align: middle;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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
				$abaCli = 1818;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 19:
					case 20:
						include "abasClienteRH.php";
						break;
					default:
						include "abasClienteConfig.php";
						break;
				}
				?>				
				
				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código do Cliente</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-5">
										<label for="inputName" class="control-label">Nome do Colaborador</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
											<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Cartão</label>
											<input type="text" class="form-control input-sm text-right leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Documento</label>

										<select data-placeholder="Selecione um tipo" name="TIP_DOC" id="TIP_DOC" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
											<option value=""></option>
											<option value="RG">RG</option>
											<option value="CPF">CPF</option>
											<option value="CON">Contrato</option>
											<option value="END">Comprovante de Endereço</option>
											<option value="RCB">Recibo</option>
											<option value="CHQ">Cheque</option>
										</select>
										
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!-- <div class="col-md-3">
									<label for="inputName" class="control-label required">Documento</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_DOC" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_DOC" id="DES_DOC" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100">
									</div>
									<span class="help-block">Até 2mb</span>
								</div> -->

							</div>

								
							<div class="push10"></div>

							<?php include "uploadDocumentos.php"; ?>
							
							<div class="push10"></div>


						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente ?>">
						<input type="hidden" name="COD_ANEXO" id="COD_ANEXO" value="">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />

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
											<th class="text-center">Status</th>
											<th>Tipo</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM ANEXO_DOCUMENTO 
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_CLIENTE = $cod_cliente 
												AND COD_EXCLUSA IS NULL";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

											$mostra_status = "";
											$cor = "";
											$badge = "badge";
											$txtBadge = "txtBadge";
											$tooltip = "";
											$mostraAprovar = "block";
											$textoReprova = "Reprovar";

											$sqlReprova = "SELECT COD_STATUS FROM ANEXO_DOC 
															WHERE COD_EMPRESA = $cod_empresa 
															AND COD_CLIENTE = $cod_cliente
															AND COD_ANEXO = $qrBuscaModulos[COD_ANEXO]
															AND COD_STATUS = 3
															ORDER BY COD_STATUS";

											// FNeSCREVE($sqlReprova);
											$arrayReprova = mysqli_query(connTemp($cod_empresa,''), $sqlReprova);

											$numReprova = mysqli_num_rows($arrayReprova);

											if($numReprova > 0){

												$cor = "background:red; color:white;";
												$mostra_status = "<span class='fas fa-info'></span>";
												$textoReprova = "Reprovado";

												$status = "<span class='".$badge."' style='".$cor."'><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

											}else{


												$sqlAnalise = "SELECT COD_STATUS FROM ANEXO_DOC 
																WHERE COD_EMPRESA = $cod_empresa 
																AND COD_CLIENTE = $cod_cliente
																AND COD_ANEXO = $qrBuscaModulos[COD_ANEXO]
																AND COD_STATUS NOT IN(2,3)
																ORDER BY COD_STATUS";

												// FNeSCREVE($sqlAnalise);
												$arrayAnalise = mysqli_query(connTemp($cod_empresa,''), $sqlAnalise);

												$numAnalise = mysqli_num_rows($arrayAnalise);

												if($numAnalise > 0){

													$cor = "background:blue; color:white;";
													$mostra_status = "<span class='fas fa-sync'></span>";
													$textoReprova = "Em análise";

													$status = "<span class='".$badge."' style='".$cor."'><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

												}else{


													$sqlAprova = "SELECT COD_STATUS FROM ANEXO_DOC 
																	WHERE COD_EMPRESA = $cod_empresa 
																	AND COD_CLIENTE = $cod_cliente
																	AND COD_ANEXO = $qrBuscaModulos[COD_ANEXO]
																	AND COD_STATUS = 2
																	ORDER BY COD_STATUS";

													// FNeSCREVE($sqlAprova);
													$arrayAprova = mysqli_query(connTemp($cod_empresa,''), $sqlAprova);

													$numAprova = mysqli_num_rows($arrayAprova);

													if($numAprova > 0){

														$cor = "background:#18bc9c;";
														$mostra_status = "<span class='fas fa-check'></span>";
														$mostraAprovar = "none";

														$status = "<span class='".$badge."' style='".$cor."'><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

													}else{

														$cor = "background:blue; color:white;";
														$mostra_status = "<span class='fas fa-sync'></span>";
														$textoReprova = "Em análise";

														$status = "<span class='".$badge."' style='".$cor."'><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

													}


												}


											}

											$count++;
											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_ANEXO'] . "</td>
														<td>" . $status . "</td>
														<td>" . $qrBuscaModulos['TIP_DOC'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_ANEXO_" . $count . "' value='" . $qrBuscaModulos['COD_ANEXO'] . "'>
													<input type='hidden' id='ret_DES_DOC_" . $count . "' value='" . $qrBuscaModulos['DES_DOC'] . "'>
													<input type='hidden' id='ret_TIP_DOC_" . $count . "' value='" . $qrBuscaModulos['TIP_DOC'] . "'>
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		// $('.upload').on('click', function(e) {
		// 	var idField = 'arqUpload_' + $(this).attr('idinput');
		// 	var typeFile = $(this).attr('extensao');

		// 	$.dialog({
		// 		title: 'Arquivo',
		// 		content: '' +
		// 			'<form method = "POST" enctype = "multipart/form-data">' +
		// 			'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
		// 			'<div class="progress" style="display: none">' +
		// 			'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
		// 			'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
		// 			'</div>' +
		// 			'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
		// 			'</form>'
		// 	});
		// });

	});

	// function uploadFile(idField, typeFile) {
 //        var formData = new FormData();
 //        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

 //        formData.append('arquivo', $('#' + idField)[0].files[0]);
 //        formData.append('diretorio', '../media/clientes/');
 //        formData.append('id', <?php echo $cod_empresa ?>);
 //        formData.append('typeFile', typeFile);

 //        $('.progress').show();
 //        $.ajax({
 //            xhr: function () {
 //                var xhr = new window.XMLHttpRequest();
 //                $('#btnUploadFile').addClass('disabled');
 //                xhr.upload.addEventListener("progress", function (evt) {
 //                    if (evt.lengthComputable) {
 //                        var percentComplete = evt.loaded / evt.total;
 //                        percentComplete = parseInt(percentComplete * 100);
 //                        if (percentComplete !== 100) {
 //                            $('.progress-bar').css('width', percentComplete + "%");
 //                            $('.progress-bar > span').html(percentComplete + "%");
 //                        }
 //                    }
 //                }, false);
 //                return xhr;
 //            },
 //            url: '../uploads/uploaddoc.php',
 //            type: 'POST',
 //            data: formData,
 //            processData: false, // tell jQuery not to process the data
 //            contentType: false, // tell jQuery not to set contentType
 //            success: function (data) {
 //                $('.jconfirm-open').fadeOut(300, function () {
 //                    $(this).remove();
 //                });
 //                if (!data.trim()) {
 //                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
 //                    $.alert({
 //                        title: "Mensagem",
 //                        content: "Upload feito com sucesso",
 //                        type: 'green'
 //                    });

 //                } else {
 //                    $.alert({
 //                        title: "Erro ao efetuar o upload",
 //                        content: data,
 //                        type: 'red'
 //                    });
 //                }
 //            }
 //        });
 //    }

	function retornaForm(index) {
		$("#formulario #COD_ANEXO").val($("#ret_COD_ANEXO_" + index).val());
		$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_ANEXO_" + index).val());
		$("#formulario #DES_DOC").val($("#ret_DES_DOC_" + index).val());
		$("#formulario #TIP_DOC").val($("#ret_TIP_DOC_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

		refreshUpload();
	}
</script>
