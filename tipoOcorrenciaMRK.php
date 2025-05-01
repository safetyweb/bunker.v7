<?php
	
	//echo fnDebug('true');

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

			$cod_ocorren = fnLimpaCampoZero($_POST['COD_OCORREN']);			
			$cod_tipocor = fnLimpaCampoZero($_POST['COD_TIPOCOR']);			
			$des_ocorren = $_POST['DES_OCORREN'];
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				$sql = "CALL SP_ALTERA_OCORRENCIAMARKA (
				 '".$cod_ocorren."', 
				 '".$cod_tipocor."', 
				 '".$des_ocorren."', 
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
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_OCORREN" id="COD_OCORREN" value="">
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Destino da Ocorrência </label>
																<select data-placeholder="Selecione o destino da ocorrência" name="COD_TIPOCOR" id="COD_TIPOCOR" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php 																	
																		$sql = "SELECT COD_TIPOCOR,DES_TIPOCOR FROM  TIPOOCORRENCIAMARKA order by DES_TIPOCOR ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaOcorrencia = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaOcorrencia['COD_TIPOCOR']."'>".$qrListaOcorrencia['DES_TIPOCOR']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome da Ocorrência</label>
															<input type="text" class="form-control input-sm" name="DES_OCORREN" id="DES_OCORREN" maxlength="50" required>
															<div class="help-block with-errors"></div>
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

										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">
										
										<div id="divId_sub">
										</div>

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Tipo</th>
													  <th class="bg-primary">Nome da Ocorrência</th>													  
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT A.*,B.DES_TIPOCOR FROM OCORRENCIAMARKA A , TIPOOCORRENCIAMARKA B WHERE A.COD_TIPOCOR=B.COD_TIPOCOR order by B.DES_TIPOCOR, A.DES_OCORREN ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaOcorrencia = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaOcorrencia['COD_OCORREN']."</td>
															  <td>".$qrBuscaOcorrencia['DES_TIPOCOR']."</td>
															  <td>".$qrBuscaOcorrencia['DES_OCORREN']."</td>
															</tr>
															<input type='hidden' id='ret_COD_OCORREN_".$count."' value='".$qrBuscaOcorrencia['COD_OCORREN']."'>
															<input type='hidden' id='ret_COD_TIPOCOR_".$count."' value='".$qrBuscaOcorrencia['COD_TIPOCOR']."'>
															<input type='hidden' id='ret_DES_OCORREN_".$count."' value='".$qrBuscaOcorrencia['DES_OCORREN']."'>
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
			$("#formulario #COD_OCORREN").val($("#ret_COD_SEGMENT_"+index).val());
			$("#formulario #COD_TIPOCOR").val($("#ret_COD_TIPOCOR_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OCORREN").val($("#ret_DES_OCORREN_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   