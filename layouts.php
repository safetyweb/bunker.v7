<?php


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	
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
			
			$cod_layout = (int) $_REQUEST['COD_LAYOUT'];
			$des_layout = $_REQUEST['DES_LAYOUT'];
			$des_cssbase = $_REQUEST['DES_CSSBASE'];
			$des_cssaux = $_REQUEST['DES_CSSAUX'];
			$des_logotip = $_REQUEST['DES_LOGOTIP'];
			$des_observa = $_REQUEST['DES_OBSERVA'];
			$des_corbase = $_REQUEST['DES_CORBASE'];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_LAYOUTS (
				 '".$cod_layout."', 
				 '".$des_layout."', 
				 '".$des_cssbase."', 
				 '".$des_cssaux."', 
				 '".$des_logotip."', 
				 '".$des_observa."', 
				 '".$des_corbase."', 
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
      
	//fnMostraForm();
	//fnEscreve($_SESSION["SYS_DES_CSSBASE"]);

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
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
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_LAYOUT" id="COD_LAYOUT" value="">
														</div>
													</div>
																				
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Layout</label>
															<input type="text" class="form-control input-sm" name="DES_LAYOUT" id="DES_LAYOUT" value="" required>
														</div>														
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cor Base</label>
															<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_CORBASE" id="DES_CORBASE" value="">															
														</div>														
													</div>
													
												</div>
												
												<div class="row">													
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Css Base</label>
															<input type="text" class="form-control input-sm" name="DES_CSSBASE" id="DES_CSSBASE" value="" required>
														</div>														
													</div>
		
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Css Complemento</label>
															<input type="text" class="form-control input-sm" name="DES_CSSAUX" id="DES_CSSAUX" value="">
														</div>														
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Logotipo</label>
															<input type="text" class="form-control input-sm" name="DES_LOGOTIP" id="DES_LOGOTIP" value="">
														</div>														
													</div>
													
												</div>
												
												<div class="row">													
													
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Observações do Layout</label><br/>
																<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="500"><?php echo $des_observa ?></textarea>
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
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Layout</th>
													  <th class="bg-primary">Cor Base</th>
													  <th class="bg-primary">Css Base</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select * from LAYOUTS order by DES_LAYOUT";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrLista = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrLista['COD_LAYOUT']."</td>
															  <td>".$qrLista['DES_LAYOUT']."</td>
															  <td>".$qrLista['DES_CORBASE']."</td>
															  <td>".$qrLista['DES_CSSBASE']."</td>
															</tr>
															
															<input type='hidden' id='ret_COD_LAYOUT_".$count."' value='".$qrLista['COD_LAYOUT']."'>
															<input type='hidden' id='ret_DES_LAYOUT_".$count."' value='".$qrLista['DES_LAYOUT']."'>
															<input type='hidden' id='ret_DES_CSSBASE_".$count."' value='".$qrLista['DES_CSSBASE']."'>
															<input type='hidden' id='ret_DES_CSSAUX_".$count."' value='".$qrLista['DES_CSSAUX']."'>
															<input type='hidden' id='ret_DES_LOGOTIP_".$count."' value='".$qrLista['DES_LOGOTIP']."'>
															<input type='hidden' id='ret_DES_CORBASE_".$count."' value='".$qrLista['DES_CORBASE']."'>
															<input type='hidden' id='ret_DES_OBSERVA_".$count."' value='".$qrLista['DES_OBSERVA']."'>
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
					
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
    
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
		});	
		
		function retornaForm(index){
			$("#formulario #COD_LAYOUT").val($("#ret_COD_LAYOUT_"+index).val());
			$("#formulario #DES_LAYOUT").val($("#ret_DES_LAYOUT_"+index).val());
			$("#formulario #DES_CSSBASE").val($("#ret_DES_CSSBASE_"+index).val());
			$("#formulario #DES_CSSAUX").val($("#ret_DES_CSSAUX_"+index).val());
			$("#formulario #DES_LOGOTIP").val($("#ret_DES_LOGOTIP_"+index).val());
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$("#formulario #DES_CORBASE").val($("#ret_DES_CORBASE_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>
 <?php

 ?>       