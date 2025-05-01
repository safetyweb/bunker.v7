<?php
	
	// echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$request = md5(implode( $_POST ));
		
		if(isset($_SESSION['last_request']) && $_SESSION['last_request']== $request)
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			$cod_status = fnLimpaCampoZero($_REQUEST['COD_STATUS']);
			$des_status = fnLimpaCampo($_REQUEST['DES_STATUS']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){ 						
				 
				//mensagem de retorno
				switch ($opcao){

					case 'CAD':

						$sql = "INSERT INTO STATUSSISTEMA(
												DES_STATUS
											)VALUES(
												'$des_status'
											)";

			    		mysqli_query($connAdm->connAdm(),trim($sql));

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;

					case 'ALT':

						$sql = "UPDATE STATUSSISTEMA SET 
									   DES_STATUS='$des_status'
								WHERE COD_STATUS=$cod_status";

	    				mysqli_query($connAdm->connAdm(),trim($sql));

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;

					case 'EXC':

						$sql = "DELETE FROM STATUSSISTEMA 
								WHERE COD_STATUS = $cod_status";

			    		mysqli_query($connAdm->connAdm(),trim($sql));

						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

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
											<legend>Categoria</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_STATUS" id="COD_STATUS" value="">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Descrição Status</label>
															<input type="text" class="form-control input-sm" name="DES_STATUS" id="DES_STATUS" maxlength="50" required>
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
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<!--<input type="hidden" name="COD_STATUS" id="COD_STATUS" value="<?php echo $cod_plataf; ?>">-->
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
									</form>

										<div class="push5"></div>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Descrição Status</th>
													</tr>
												  </thead>

												<tbody>
													<?php 
												
													$sql = "SELECT * FROM STATUSSISTEMA";
													$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql));
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
													  	$count++;

													  ?>

														<tr>
															<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
															<td><?php echo $qrBuscaModulos['COD_STATUS']; ?></td>
															<td><?php echo $qrBuscaModulos['DES_STATUS']; ?></td>
														</tr>

														<input type='hidden' id='ret_COD_STATUS_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_STATUS']; ?>'>
														<input type='hidden' id='ret_DES_STATUS_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_STATUS']; ?>'>
														<?php }?>
													
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
			$("#formulario #COD_STATUS").val($("#ret_COD_STATUS_"+index).val());
			$("#formulario #DES_STATUS").val($("#ret_DES_STATUS_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');					
		}
		
	</script>	