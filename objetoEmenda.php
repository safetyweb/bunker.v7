
<?php
	
	//echo "<h5>_".$opcao."</h5>";

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

			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_objeto = fnLimpaCampoZero($_REQUEST['COD_OBJETO']);
			$des_objeto = fnLimpaCampo($_REQUEST['DES_OBJETO']);
			$abv_objeto = fnLimpaCampo($_REQUEST['ABV_OBJETO']);

			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){ 				
				 
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO OBJETO_EMENDA(
									COD_EMPRESA,
									DES_OBJETO,
									ABV_OBJETO,
									COD_USUCADA
								)VALUES(
									'$cod_empresa',
									'$des_objeto',
									'$abv_objeto',
									$cod_usucada
								)";
			    		//fnEscreve($sql);
			    		mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE OBJETO_EMENDA SET 
					    		DES_OBJETO='$des_objeto',
					    		ABV_OBJETO='$abv_objeto'

					    		WHERE COD_OBJETO=$cod_objeto
					    		AND COD_EMPRESA = $cod_empresa
					    		";

			    		mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';

?>
				<script>parent.$('#REFRESH_COMBO').val('S');</script>
<?php 
				
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
									
									<div class="push30"></div>
			
								<div class="login-form">
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Categoria</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_OBJETO" id="COD_OBJETO" value="">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Descrição</label>
															<input type="text" class="form-control input-sm" name="DES_OBJETO" id="DES_OBJETO" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>


													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="ABV_OBJETO" id="ABV_OBJETO" maxlength="20" required>
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
										
										<!--<input type="hidden" name="COD_OBJETO" id="COD_OBJETO" value="<?php echo $cod_plataf; ?>">-->
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
													  <th>Descrição</th>
													  <th>Abreviação</th>												  
													</tr>
												  </thead>

												<tbody>
													<?php 
												
													$sql = "SELECT * FROM OBJETO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
													  	$count++;
													  ?>
														<tr>
															<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
															<td><?php echo $qrBuscaModulos['COD_OBJETO']; ?></td>
															<td><?php echo $qrBuscaModulos['DES_OBJETO']; ?></td>
															<td><?php echo $qrBuscaModulos['ABV_OBJETO']; ?></td>
														</tr>

														<input type='hidden' id='ret_COD_OBJETO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_OBJETO']; ?>'>
														<input type='hidden' id='ret_DES_OBJETO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_OBJETO']; ?>'>
														<input type='hidden' id='ret_ABV_OBJETO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['ABV_OBJETO']; ?>'>
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
			$("#formulario #COD_OBJETO").val($("#ret_COD_OBJETO_"+index).val());
			$("#formulario #DES_OBJETO").val($("#ret_DES_OBJETO_"+index).val());
			$("#formulario #ABV_OBJETO").val($("#ret_ABV_OBJETO_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	