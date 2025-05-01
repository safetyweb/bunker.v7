<?php
	
	$hashLocal = mt_rand();	


?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg ?></span>
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
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista" id="formLista" method="post">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Nome do Sistema</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select * from sistemas order by des_sistema";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_SISTEMA']."</td>
															  <td>".$qrBuscaModulos['DES_SISTEMA']."</td>
															</tr>
															<input type='hidden' id='ret_codBusca_".$count."' value='".$qrBuscaModulos['COD_SISTEMA']."'>
															<input type='hidden' id='ret_nomBusca_".$count."' value='".$qrBuscaModulos['DES_SISTEMA']."'>
															"; 
														  }											

												?>
													
												</tbody>
												</table>
												
												<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
												<input type="hidden" name="codBusca" id="codBusca" value="">
												<input type="hidden" name="nomBusca" id="nomBusca" value="">	
												
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

	<?php
	if (!is_null($RedirectPg)) {
		$DestinoPg = fnEncode($RedirectPg);		
	}else {
		$DestinoPg = "";		
		}	
	?>					

	<script type="text/javascript">	
	
		function retornaForm(index){
			
			$("#codBusca").val($("#ret_codBusca_"+index).val());			
			$("#nomBusca").val($("#ret_nomBusca_"+index).val());
			$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>');					
			$('#formLista').submit();					
		}
	
	</script>		