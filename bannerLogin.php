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

			$cod_banner = fnLimpaCampoZero($_REQUEST['COD_BANNER']);
			$cod_segment = fnLimpaCampoZero($_REQUEST['COD_SEGMENT']);
			$des_banner = fnLimpaCampo($_REQUEST['DES_BANNER']);
			$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
			$des_imagem_mob = fnLimpaCampo($_REQUEST['DES_IMAGEM_MOB']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

					$sql = "INSERT INTO BANNER_LOGIN(
										LOG_ATIVO,
										COD_SEGMENT,
										DES_BANNER,
										DES_IMAGEM,
										DES_IMAGEM_MOB
										) VALUES(
										'$log_ativo',
										$cod_segment,
										'$des_banner',
										'$des_imagem',
										'$des_imagem_mob'
										)";

					//fnEscreve($sql);

					mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

					break;

					case 'ALT':

					$sql = "UPDATE BANNER_LOGIN SET
									LOG_ATIVO='$log_ativo',
									COD_SEGMENT=$cod_segment,
									DES_BANNER='$des_banner',
									DES_IMAGEM='$des_imagem',
									DES_IMAGEM_MOB='$des_imagem_mob'
							WHERE COD_BANNER = $cod_banner";

					//fnEscreve($sql);

					mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";	

					break;

					case 'EXC':

					$sql = "DELETE FROM BANNER_LOGIN WHERE COD_BANNER = $cod_banner";

					mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Ativo</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S">
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Segmento</label>
																<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_SEGMENT']."'>".$qrLista['NOM_SEGMENT']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título da Imagem</label>
															<input type="text" class="form-control input-sm" name="DES_BANNER" id="DES_BANNER" maxlength="40" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Imagem Horizontal</label>
															<div class="input-group">
																<span class="input-group-btn">
																	<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
																</span>
																<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="">
															</div>																
															<span class="help-block">(.jpg, .png 1920px X 1080px)</span>															
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Imagem Mobile (vertical)</label>
															<div class="input-group">
																<span class="input-group-btn">
																	<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM_MOB" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
																</span>
																<input type="text" name="DES_IMAGEM_MOB" id="DES_IMAGEM_MOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="">
															</div>																
															<span class="help-block">(.jpg, .png 1080px X 1920px)</span>															
														</div>
													</div>
																				
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
										<input type="hidden" name="COD_BANNER" id="COD_BANNER" value="" />	
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
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
													  <th>Cód.</th>
													  <th>Ativo</th>
													  <th>Segmento</th>
													  <th>Nome do Banner</th>
													  <th>Imagem</th>
													  <th>Imagem Mobile</th>
													</tr>
												  </thead>
												<tbody>

													<?php
														$count=0;

														$sql = "SELECT BL.*, SM.NOM_SEGMENT FROM BANNER_LOGIN BL
														LEFT JOIN SEGMENTOMARKA SM ON SM.COD_SEGMENT = BL.COD_SEGMENT";
														$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());
														while($qrBanner = mysqli_fetch_assoc($arrayQuery)){

															$count++;

															if($qrBanner['LOG_ATIVO'] == "S"){ $icone = '<span class="far fa-check text-success"></span>'; }
															else{ $icone = '<span class="far fa-times text-danger"></span>';}

															?>

															<tr>
																<td><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></td>
																<td><?=$qrBanner['COD_BANNER']?></td>
																<td><?=$icone?></td>
																<td><?=$qrBanner['NOM_SEGMENT']?></td>
																<td><?=$qrBanner['DES_BANNER']?></td>
																<td><?=$qrBanner['DES_IMAGEM']?></td>
																<td><?=$qrBanner['DES_IMAGEM_MOB']?></td>
															</tr>

															<input type="hidden" id="ret_COD_BANNER_<?=$count?>" value="<?=$qrBanner['COD_BANNER']?>">
															<input type="hidden" id="ret_LOG_ATIVO_<?=$count?>" value="<?=$qrBanner['LOG_ATIVO']?>">
															<input type="hidden" id="ret_COD_SEGMENT_<?=$count?>" value="<?=$qrBanner['COD_SEGMENT']?>">
															<input type="hidden" id="ret_DES_BANNER_<?=$count?>" value="<?=$qrBanner['DES_BANNER']?>">
															<input type="hidden" id="ret_DES_IMAGEM_<?=$count?>" value="<?=$qrBanner['DES_IMAGEM']?>">
															<input type="hidden" id="ret_DES_IMAGEM_MOB_<?=$count?>" value="<?=$qrBanner['DES_IMAGEM_MOB']?>">
															<?php
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
		
		function retornaForm(index){
			$("#formulario #COD_SEGMENT").val($("#ret_COD_SEGMENT_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_BANNER").val($("#ret_COD_BANNER_"+index).val());
			$("#formulario #DES_BANNER").val($("#ret_DES_BANNER_"+index).val());
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_"+index).val());
			$("#formulario #DES_IMAGEM_MOB").val($("#ret_DES_IMAGEM_MOB_"+index).val());
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		function uploadFile(idField, typeFile) {
	        var formData = new FormData();
	        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

	        formData.append('arquivo', $('#' + idField)[0].files[0]);
	        formData.append('diretorio', '../media/login');
	        formData.append('id', 0);
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
	                	console.log(data);
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