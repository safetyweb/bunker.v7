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
			$cod_versaointegra = fnLimpaCampoZero($_REQUEST['COD_VERSAOINTEGRA']);
			$des_versaointegra = fnLimpaCampo($_REQUEST['DES_VERSAOINTEGRA']);
			$abv_versaointegra = fnLimpaCampo($_REQUEST['ABV_VERSAOINTEGRA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){ 

				if ($opcao == 'CAD'){

				$sql = "INSERT INTO SAC_VERSAOINTEGRA(
						DES_VERSAOINTEGRA,
						ABV_VERSAOINTEGRA) 
						VALUES(
						'$des_versaointegra',
						'$abv_versaointegra'
						)";
	    		//fnEscreve($sql);
	    		mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
	    		//mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

	    		}

	    		elseif ($opcao == 'EXC'){

	    		$sql = "DELETE FROM SAC_VERSAOINTEGRA WHERE COD_VERSAOINTEGRA = $cod_versaointegra";

	    		mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());

	    		}

	    		else{

	    		$sql = "UPDATE SAC_VERSAOINTEGRA SET 
			    		DES_VERSAOINTEGRA='$des_versaointegra',
			    		ABV_VERSAOINTEGRA='$abv_versaointegra' 
			    		WHERE COD_VERSAOINTEGRA=$cod_versaointegra
			    		";

	    		mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());

	    		}							
				
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
									
									<?php $abaInfoSuporte = 1271; include "abasSuporteConfig.php"; ?> 
									
									<div class="push30"></div>
			
								<div class="login-form">
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Integração</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA" value="">
														</div>
													</div>

													<div class="col-md-5">
														<div class="form-group">
															<label for="inputName" class="control-label">Descrição</label>
															<input type="text" class="form-control input-sm" name="DES_VERSAOINTEGRA" id="DES_VERSAOINTEGRA" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>


													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="ABV_VERSAOINTEGRA" id="ABV_VERSAOINTEGRA" maxlength="20" required>
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
										
										<!--<input type="hidden" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA" value="<?php echo $cod_plataf; ?>">-->
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
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Descrição</th>
													  <th class="bg-primary">Abreviação</th>													  
													</tr>
												  </thead>

												<tbody>
													<?php 
												
													$sql = "SELECT * FROM SAC_VERSAOINTEGRA";
													$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
													  	$count++;
													  ?>
														<tr>
															<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
															<td><?php echo $qrBuscaModulos['COD_VERSAOINTEGRA']; ?></td>
															<td><?php echo $qrBuscaModulos['DES_VERSAOINTEGRA']; ?></td>
															<td><?php echo $qrBuscaModulos['ABV_VERSAOINTEGRA']; ?></td>
														</tr>

														<input type='hidden' id='ret_COD_VERSAOINTEGRA_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_VERSAOINTEGRA']; ?>'>
														<input type='hidden' id='ret_DES_VERSAOINTEGRA_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_VERSAOINTEGRA']; ?>'>
														<input type='hidden' id='ret_ABV_VERSAOINTEGRA_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['ABV_VERSAOINTEGRA']; ?>'>
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
		
	//$(document).ready(function(){

	//});
		function retornaForm(index){
			$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_"+index).val());
			$("#formulario #DES_VERSAOINTEGRA").val($("#ret_DES_VERSAOINTEGRA_"+index).val());
			$("#formulario #ABV_VERSAOINTEGRA").val($("#ret_ABV_VERSAOINTEGRA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>

	<!--$sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
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
														  }*/										

												?>-->