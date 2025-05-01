<?php
	
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

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

			$cod_submenus = fnLimpaCampoZero($_REQUEST['ID']);
			
			$nom_submenus = $_POST['NOM_SUBMENUS'];
			$nom_submenus = addslashes($nom_submenus);
			if (empty($_REQUEST['LOG_FINALIZA'])) {$log_finaliza='N';}else{$log_finaliza=$_REQUEST['LOG_FINALIZA'];}		
			//fnEscreve($nom_submenus);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_SUBMENUS (
				 '".$cod_submenus."', 
				 '".$nom_submenus."', 
				 '".$log_finaliza."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				//fnEscreve($cod_submenus);
	
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
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
														</div>
													</div>
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Sub Menu</label>
															<input type="text" class="form-control input-sm" name="NOM_SUBMENUS" id="NOM_SUBMENUS" maxlength="60" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Finaliza Menu</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_FINALIZA" id="LOG_FINALIZA" class="switch" value="S">
																<span></span>
																</label>
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
													  <th>Nome do Submenu</th>
													  <th>Finaliza</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT * FROM SUBMENUS ORDER BY NOM_SUBMENUS";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;

														if($qrBuscaModulos['LOG_FINALIZA'] == 'S'){
															$finaliza = "<span class='fas fa-check text-success'></span>";
														}else{
															$finaliza = "<span class='fas fa-times text-danger'></span>";
														}

														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_SUBMENUS']."</td>
															  <td>".$qrBuscaModulos['NOM_SUBMENUS']."</td>
															  <td class='text-center'>".$finaliza."</td>
															</tr>
															<input type='hidden' id='ret_ID_".$count."' value='".$qrBuscaModulos['COD_SUBMENUS']."'>
															<input type='hidden' id='ret_NOM_SUBMENUS_".$count."' value='".$qrBuscaModulos['NOM_SUBMENUS']."'>
															<input type='hidden' id='ret_LOG_FINALIZA_".$count."' value='".$qrBuscaModulos['LOG_FINALIZA']."'>
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
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #NOM_SUBMENUS").val($("#ret_NOM_SUBMENUS_"+index).val());
			if ($("#ret_LOG_FINALIZA_"+index).val() == 'S'){$('#formulario #LOG_FINALIZA').prop('checked', true);} 
			else {$('#formulario #LOG_FINALIZA').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   