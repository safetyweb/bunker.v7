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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
		$cod_cliente = fnDecode($_GET['idC']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		$cod_cliente = 0;		
		//fnEscreve('entrou else');
	}
	
	//fnMostraForm();

?>


<style>

	body{
		overflow: hidden!important;
	}
	
	.area {
	  width: 100%;
	  padding: 7px;
	}

	#dropZone {
	  display: block;
	  border: 2px dashed #bbb;
	  -webkit-border-radius: 5px;
	  border-radius: 5px;
	  margin-left: -7px;
	}

	#dropZone p{
		font-size: 10pt;
		letter-spacing: -0.3pt;
		margin-bottom: 0px;
	}

	#dropzone .fa{
		font-size: 15pt;
	}

</style>

			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12">
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
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<div class="row">
												
												<div class="col-sm-12">
													
													<fieldset>
														<legend>Tipo da Imagem</legend> 
														
															<div class="row">
																<div class="col-sm-4 col-sm-offset-4">
																	<div class="col-sm-12">
																		
																		<div class="form-group">
																			<button class="btn btn-primary" id="foto"><span class="fal fa-camera"></span>&nbsp; Câmera</button>
																			<button class="btn btn-default pull-right" id="upar"><span class="fal fa-upload"></span>&nbsp; Upload</button>
																		</div>

																	</div>
																</div>
																							
															</div>
															
													</fieldset>

												</div>

											</div>
																				

										<div class="push30"></div>

										<div class="row">
											
											<div class="col-sm-6 col-sm-offset-3" id="my_photo_booth" style="display: none">

													<div class="col-sm-12">

														<fieldset>
															<legend>Câmera</legend>

															<div id="my_camera" style="margin-right: auto;margin-left: auto;"></div>
															<div id="results" style="margin-right: auto;margin-left: auto; display: none;"></div>

														</fieldset>

														<div class="push10"></div>

														<div class="col-sm-12 text-center" id="pre_take_buttons">
															<!-- This button is shown before the user takes a snapshot -->
															<button class="btn btn-primary" onClick="preview_snapshot()"><span class="fal fa-camera"></span>&nbsp; Tirar Foto</button>
														</div>
														
													</div>

													<div class="col-sm-12" id="post_take_buttons" style="display:none">
														<!-- These buttons are shown after a snapshot is taken -->
														<button class="btn btn-default" onClick="cancel_preview()"><span class="fal fa-arrow-left"></span>&nbsp; Tirar Outra</button>
														<button class="btn btn-success pull-right" onClick="save_photo()"><span class="fal fa-save"></span>&nbsp; <b>Salvar Foto</b></button>
													</div>

											</div>

											<div class="col-sm-6 col-sm-offset-3" id="my_upload" style="display: none">
												
												<div class="area">
												    <div id="dropZone">
													    

												    	<div class="row">

												    		<div class="push15"></div>

												    		<div class="col-sm-1"></div>

													    	<div class="col-sm-2">
																<a type="button" name="btnBusca" id="btnBusca" class="btn btn-primary upload" idinput="FOTO_PERFIL" extensao="img"><i class="fal fa-paperclip" aria-hidden="true"></i></a>
															</div>
															
															<div class="col-sm-8 text-center">
																<div class="push5"></div>
																<p>Upload de Arquivos</p>
																<input type="text" name="FOTO_PERFIL" id="FOTO_PERFIL" maxlength="100" hidden>
																<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
																<div class="push15"></div>
															</div>

															<div class="col-sm-1"></div>

														</div>

													</div>
												</div>

											</div>



										</div>
																				
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="arqUpload_DES_IMGCAM" id="arqUpload_DES_IMGCAM" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										</form>									
										
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					

	<!-- First, include the Webcam.js JavaScript Library -->
	<script type="text/javascript" src="js/plugins/webcamJs/webcam.min.js"></script>
	
	<script type="text/javascript">

		$(function(){

			$("#foto, #upar").click(function(){
				if($(this).attr("id") == "foto"){
					$("#my_upload").fadeOut('fast',function(){
						$("#my_photo_booth").fadeIn('fast');
					});
				}else{
					$("#my_photo_booth").fadeOut('fast',function(){
						$("#my_upload").fadeIn('fast');
					});
				}
			});

			Webcam.set({
				// live preview size
				width: 220,
				height: 220,
				
				// device capture size
				dest_width: 220,
				dest_height: 220,
				
				// final cropped size
				crop_width: 220,
				crop_height: 220,
				
				// format and quality
				image_format: 'jpeg',
				jpeg_quality: 90,
				
				// flip horizontal (mirror mode)
				flip_horiz: true
			});
			Webcam.attach( '#my_camera' );

			$('.upload').on('click', function (e) {
		        var idField = 'arqUpload_' + $(this).attr('idinput');
		        var typeFile = $(this).attr('extensao');

		        $.dialog({
		            title: 'Arquivo',
		            content: '' +
		                    '<form method = "POST" enctype = "multipart/form-data">' +
		                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
		                    '<div class="progress" style="display: none">' +
		                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
		                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
		                    '</div>' +
		                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
		                    '</form>'
				});
			});

		});

		function preview_snapshot() {

			// freeze camera so user can preview current frame
			Webcam.freeze();
			
			// swap button sets
			$('#pre_take_buttons').css('display','none');
			$('#post_take_buttons').css('display','block');
		}
		
		function cancel_preview() {
			// cancel preview freeze and return to live camera view
			Webcam.unfreeze();
			
			// swap buttons back to first set
			$('#pre_take_buttons').css('display','block');
			$('#post_take_buttons').css('display','none');
		}
		
		function save_photo() {
			// actually snap photo (from preview freeze) and display it
			Webcam.snap( function(data_uri) {
				
				Webcam.upload( data_uri, '../uploads/uploadWebcam.php?id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_cliente)?>', function(code, text) {
				    // console.log(text);
				    $.ajax({
				    	method: 'POST',
				    	url: 'ajxFotoApoiador.php',
				    	data: {COD_CLIENTE: '<?=$cod_cliente?>', COD_EMPRESA: '<?=$cod_empresa?>'},
				    	beforeSend:function(){
							$('#my_camera').html('<div class="loading" style="width: 100%;"></div>');
						},
				    	success:function(data){
				    		$('#my_camera').html('<br>');
				    		$.alert({
			                    title: "Foto salva.",
			                    content: "Foto de perfil salva com sucesso!",
			                    buttons: {
									Ok: function () {
										try { parent.$('#LOG_FOTO').val('S'); } catch(err) {}
						                $(this).removeData('bs.modal');	
										parent.$('#popModal').modal('hide');
									}
								}
			                });	
				    	}
				    });
				} );
				
				// shut down camera, stop capturing
				Webcam.reset();
				
			} );
		}
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		function uploadFile(idField, typeFile) {
	        var formData = new FormData();
	        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

	        formData.append('webcam', $('#' + idField)[0].files[0]);
	        formData.append('id', "<?=fnEncode($cod_empresa)?>");
	        formData.append('idC', "<?=fnEncode($cod_cliente)?>");
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
	            url: '../uploads/uploadFotoPerfil.php',
	            type: 'POST',
	            data: formData,
	            processData: false, // tell jQuery not to process the data
	            contentType: false, // tell jQuery not to set contentType
	            success: function (data) {
	                $('.jconfirm-open').fadeOut(300, function () {
	                	console.log(data);
	                    $(this).remove();
	                });
	                if (!data.trim()) {

	                	$.ajax({
					    	method: 'POST',
					    	url: 'ajxFotoApoiador.php',
					    	data: {COD_CLIENTE: '<?=$cod_cliente?>', COD_EMPRESA: '<?=$cod_empresa?>'},
					    	success:function(data){

					    		$.alert({
				                    title: "Foto salva.",
				                    content: "Foto de perfil salva com sucesso!",
				                    buttons: {
										Ok: function () {
											try { parent.$('#LOG_FOTO').val('S'); } catch(err) {}
							                $(this).removeData('bs.modal');	
											parent.$('#popModal').modal('hide');
										}
									}
				                });

				                console.log(data);
				                
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

	   
		
	</script>	