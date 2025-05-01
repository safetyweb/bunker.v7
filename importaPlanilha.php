<?php 
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			/*$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);*/

			if(isset($_POST["btnUploadFile"])){
		

				fnEscreve($_FILES["file"]["tmp_name"]);
			}

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
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
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1276";
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
									
									<?php $abaSuporte = 1276; include "abasSuporteConfig.php"; ?>
									
									<div class="push30"></div> 
			
									<div class="login-form">

										<!--<div class="control-group">
													<div class="control-label">
														<label>CSV/Excel File:</label>
													</div>
													<div class="controls">
														<input type="file" name="file" id="file" class="input-large">
													</div>
												</div>
												
												<div class="control-group">
													<div class="controls">
													<button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Upload</button>
													</div>
												</div>-->
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Importar Arquivo CSV/Excel</legend>

												<div class="col-md-5">
													<div class="input-group">
														<span class="input-group-btn">
															<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ANEXO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
														</span>
														<input type="text" name="ARQUIVO_UP" id="UP_ARQUIVO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100">
													</div>
													<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
												</div>
												
										</fieldset>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
									</form>
										
									<div class="push50"></div>
										
								<div class="col-lg-12">

									<div class="no-more-tables">
								
										<form name="formLista">
										
										<table class="table table-bordered table-striped table-hover">
										  <thead>
											<tr>
											  <th class="bg-primary" width="40"></th>
											  <th class="bg-primary">Código</th>
											  <th class="bg-primary">Nome do Grupo</th>
											</tr>
										  </thead>
										<tbody>
										  
										<?php 
										
											$sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
											$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
											
											$count=0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
											  {														  
												$count++;	
												echo"
													<tr>
													  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
													  <td>".$qrBuscaModulos['COD_GRUPOTR']."</td>
													  <td>".$qrBuscaModulos['DES_GRUPOTR']."</td>
													</tr>
													<input type='hidden' id='ret_COD_GRUPOTR_".$count."' value='".$qrBuscaModulos['COD_GRUPOTR']."'>
													<input type='hidden' id='ret_DES_GRUPOTR_".$count."' value='".$qrBuscaModulos['DES_GRUPOTR']."'>
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

		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		$('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                    '<form ID="UPLOAD_CSV" method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
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
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
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
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
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