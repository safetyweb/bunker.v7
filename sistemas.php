<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	$adm = $connAdm->connAdm();
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
			
			$cod_sistema = fnLimpaCampoZero($_REQUEST['ID']);
			$cod_home = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$des_sistema = fnLimpaCampo($_POST['DES_SISTEMA']);
			$des_logo_lgt = fnLimpaCampo($_POST['DES_LOGO_LGT']);
			$des_logo_drk = fnLimpaCampo($_POST['DES_LOGO_DRK']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$log_multempresa = fnLimpaCampo($_POST['LOG_MULTEMPRESA']);
			if (empty($_REQUEST['LOG_MULTEMPRESA'])) {$log_multempresa='N';}else{$log_multempresa=$_REQUEST['LOG_MULTEMPRESA'];}
			
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				// $sql = "CALL SP_ALTERA_SISTEMAS (
				//  '".$cod_sistema."', 
				//  '".$cod_home."', 
				//  '".$cod_empresa."', 
				//  '".$des_sistema."', 
				//  '".$des_abrevia."', 
				//  '".$log_multempresa."', 
				//  '".$opcao."'    
				// ) ";
				
				// // fnEscreve($sql);
				// //fnEscreve($cod_submenus);
	
				// mysqli_query($connAdm->connAdm(),trim($sql));				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO SISTEMAS(
									 COD_HOME,
									 COD_EMPRESA,
									 DES_SISTEMA,
									 DES_ABREVIA,
									 DES_LOGO_LGT,
									 DES_LOGO_DRK,
									 LOG_MULTEMPRESA
								) VALUES(
									 $cod_home, 
									 $cod_empresa, 
									 '$des_sistema', 
									 '$des_abrevia',
									 '$des_logo_lgt', 
									 '$des_logo_drk', 
									 '$log_multempresa'  
								) ";
						
			
						$qrInsert = mysqli_query($connAdm->connAdm(),trim($sql));

						if (!$qrInsert) {
							$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
						}else{

							$sqlSis = "SELECT MAX(COD_SISTEMA) AS COD_SISTEMA FROM SISTEMAS";

							$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sqlSis));
							$qrBusca = mysqli_fetch_assoc($arrayQuery);

							$cod_sistema = $qrBusca['COD_SISTEMA'];

							$SqlInsPerfil = "SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA FROM PERFIL,SISTEMAS WHERE PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND PERFIL.COD_SISTEMA IN(12,20,16,22,21) AND PERFIL.COD_EMPRESA IS NULL UNION SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA FROM PERFIL,SISTEMAS WHERE PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND PERFIL.COD_EMPRESA=11";

							$query = mysqli_query($adm, $SqlInsPerfil);
							
							while($qrResult = mysqli_fetch_assoc($query)){
								$des_perfils = $qrResult['DES_PERFILS'];
								$cod_modulos = $qrResult['COD_MODULOS'];

								$SqlInsert = "INSERT INTO PERFIL(
															DES_PERFILS,
															COD_MODULOS,
															COD_CADASTR,
															COD_SISTEMA,
															DAT_CADASTR
															)VALUES(
															'$des_perfils',
															'$cod_modulos',
															9999,
															$cod_sistema,
															NOW()
															)";
								$qrInsert = mysqli_query($adm, $SqlInsert);

								if (!$qrInsert) {
									$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $qrInsert,$nom_usuario);
								}
							}

						}


						if ($cod_erro == 0 || $cod_erro ==  "") {
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						} else {
							$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
						}

						break;
					case 'ALT':

						$sql = "UPDATE SISTEMAS SET
									 COD_HOME = $cod_home,
									 COD_EMPRESA = '$cod_empresa',
									 DES_SISTEMA = '$des_sistema',
									 DES_ABREVIA = '$des_abrevia',
									 DES_LOGO_LGT = '$des_logo_lgt',
									 DES_LOGO_DRK = '$des_logo_drk',
									 LOG_MULTEMPRESA = '$log_multempresa'
								WHERE COD_SISTEMA = $cod_sistema";

						// fnEscreve($sql);
			
						mysqli_query($connAdm->connAdm(),trim($sql));

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

						break;
					case 'EXC':

						// $sql = "DELETE FROM SISTEMAS
						// 		WHERE COD_SISTEMA = $cod_sistema";
			
						// mysqli_query($connAdm->connAdm(),trim($sql));

						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  
			

		}
	}	
		
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
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
								
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura"  name="ID" id="ID" value="">
														</div>
													</div>
										
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Sistema</label>
															<input type="text" class="form-control input-sm" name="DES_SISTEMA" id="DES_SISTEMA" maxlength="25" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Abreviação do Sistema</label>
															<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="5" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
                                                        <label for="inputName" class="control-label">Home</label>
                                                        <div class="input-group">
                                                        <span class="input-group-btn">
                                                        <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477)?>&id=<?php echo fnEncode($cod_modulos)?>&pop=true" data-title="Busca Categoria"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
                                                        </span>
                                                        <input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                                        <input type="hidden"name="COD_MODULOS" id="COD_MODULOS" value="">
                                                        </div>
                                                        <div class="help-block with-errors"></div>                                                      
                                                    </div>

                                                    <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
																<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_EMPRESA, NOM_EMPRESA from empresas where COD_EMPRESA IN (1,2,3) order by NOM_EMPRESA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																	
																		while ($qrListaEempresas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																																
																			echo"
																				  <option value='".$qrListaEempresas['COD_EMPRESA']."'>".$qrListaEempresas['NOM_EMPRESA']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Acessa Multi Empresas</label> <br/>
															<label class="switch switch-small">
															<input type="checkbox" name="LOG_MULTEMPRESA" id="LOG_MULTEMPRESA" class="switch" value="S" <?php echo $log_multempresa; ?> />
															<span></span>
															</label> 								
															<div class="help-block with-errors"></div>
														</div>
																				
													</div>

												</div>

												<div class="row">
													
													<div class="col-md-3">
														<label for="inputName" class="control-label">Logotipo (Tema Claro)</label>
														<div class="input-group">
															<span class="input-group-btn">
																<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO_LGT" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
															</span>
															<input type="text" name="DES_LOGO_LGT" id="DES_LOGO_LGT" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
														</div>																
														<span class="help-block">(.png 300px X 80px)</span>
													</div>

													<div class="col-md-3">
														<label for="inputName" class="control-label">Logotipo (Tema Escuro)</label>
														<div class="input-group">
															<span class="input-group-btn">
																<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO_DRK" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
															</span>
															<input type="text" name="DES_LOGO_DRK" id="DES_LOGO_DRK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
														</div>																
														<span class="help-block">(.png 300px X 80px)</span>
													</div>

												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Nome do Sistema</th>
													  <th>Abreviação do Sistema</th>
													  <th>Home</th>
													  <th>Multi Empresas</th>
													  <th>Empresa</th>
													  <th>Logo Clara</th>
													  <th>Logo Escura</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT SS.*, MD.NOM_MODULOS FROM SISTEMAS SS 
															LEFT JOIN MODULOS MD ON MD.COD_MODULOS = SS.COD_HOME
															ORDER BY SS.DES_SISTEMA";
													//fnEscreve($sql);
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;

														if ($qrBuscaModulos['LOG_MULTEMPRESA'] == 'S'){		
															$mostraMulti = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $mostraMulti = ''; }

														if ($qrBuscaModulos['DES_LOGO_LGT'] != ''){		
															$logoLight = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $logoLight = ''; }	

														if ($qrBuscaModulos['DES_LOGO_DRK'] != ''){		
															$logoDark = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $logoDark = ''; }		

														switch ($qrBuscaModulos['COD_EMPRESA']) {
															case 1: //todas
																$mostraEmpresa = "Todas";
																break;
															case 2: //dw
																$mostraEmpresa = "Dw";
																break;
															case 3: //marka
																$mostraEmpresa = "Marka";
																break;
														}	
														
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_SISTEMA']."</td>
															  <td>".$qrBuscaModulos['DES_SISTEMA']."</td>
															  <td>".$qrBuscaModulos['DES_ABREVIA']."</td>
															  <td><small>".$qrBuscaModulos['COD_HOME']."</small> ".$qrBuscaModulos['NOM_MODULOS']."</td>
															  <td>".$mostraMulti."</td>
															  <td>".$mostraEmpresa."</td>
															  <td>".$logoLight."</td>
															  <td>".$logoDark."</td>
															</tr>
															<input type='hidden' id='ret_ID_".$count."' value='".$qrBuscaModulos['COD_SISTEMA']."'>
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
															<input type='hidden' id='ret_NOM_MODULOS_".$count."' value='".$qrBuscaModulos['NOM_MODULOS']."'>
															<input type='hidden' id='ret_COD_MODULOS_".$count."' value='".$qrBuscaModulos['COD_MODULOS']."'>
															<input type='hidden' id='ret_DES_SISTEMA_".$count."' value='".$qrBuscaModulos['DES_SISTEMA']."'>
															<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrBuscaModulos['DES_ABREVIA']."'>
															<input type='hidden' id='ret_DES_LOGO_LGT_".$count."' value='".$qrBuscaModulos['DES_LOGO_LGT']."'>
															<input type='hidden' id='ret_DES_LOGO_DRK_".$count."' value='".$qrBuscaModulos['DES_LOGO_DRK']."'>
															<input type='hidden' id='ret_LOG_MULTEMPRESA_".$count."' value='".$qrBuscaModulos['LOG_MULTEMPRESA']."'>
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

					<!-- modal -->                                  
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
	
	<script type="text/javascript">

		$(function(){

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

		function uploadFile(idField, typeFile) {
	        var formData = new FormData();
	        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

	        formData.append('arquivo', $('#' + idField)[0].files[0]);
	        formData.append('diretorio', '../media/clientes/');
	        formData.append('diretorioAdicional', 'logoSistema');
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
	                    $(this).remove();
	                });
	                if (!data.trim()) {
	                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);

	                    $.ajax({
							type: "POST",
							url: "ajxImgTermos.php",
							data: { COD_EMPRESA: "<?=fnEncode(0)?>", NOM_ARQ: nomeArquivo, CAMPO: idField},
							success:function(data){
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
		
		function retornaForm(index){
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
			$("#formulario #NOM_MODULOS").val($("#ret_NOM_MODULOS_"+index).val());
			$("#formulario #DES_SISTEMA").val($("#ret_DES_SISTEMA_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_LOGO_LGT").val($("#ret_DES_LOGO_LGT_"+index).val());
			$("#formulario #DES_LOGO_DRK").val($("#ret_DES_LOGO_DRK_"+index).val());
			if ($("#ret_LOG_MULTEMPRESA_"+index).val() == 'S'){$('#formulario #LOG_MULTEMPRESA').prop('checked', true);} 
			else {$('#formulario #LOG_MULTEMPRESA').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	