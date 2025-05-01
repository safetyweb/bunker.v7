<?php
	
  //echo fnDebug('true');  
  $campoIco = $_GET['field'];
  
  $arrIcones = array("android","android1","app-store","arrow","art","bag","basket","book","bowling","box","brush","building",
					 "bulb","button","calculator","calendar","camera","car","card","cashback","chair","chat","clipboard","clocks",
					 "compas","converse","cup","dj","donut","dude","dynamite","earth","egg","eye","file","fit","flag",
					 "flask","flower","games","gift-box","girl","goal","google ","graph","icecream","imac","ipad",
					 "iphone","key","lettersymbol","lock","loop","macbook","magic","magicmouse","mail","map","medal","mic",
					 "money","mortarboard","mountain","news","ofertas","paper-bag","pc","pencil","pencils","picture","pig","pills","play",
					 "printer","responsive","retina","ring","rocket","rss","safe","saldo","save","search","settings","shield","shirt",
					 "skateboard","spray","storage","support","ticket","touch","trash","trip-bag","trunk","ubmrella","video",
					 "weather","wi-fi","wine","yinyang");
  
  //fnEscreve($cod_empresa); 	
  //fnEscreve($campoIco); 	
  //fnMostraForm();
  
					 
?> 
			
					<?php if ($popUp != "true"){  ?>							
					<div class="push30"></div> 
					<?php } ?>
					
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
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

												<?php
												   foreach($arrIcones as $count=>$imagem){
													?>   
													<div class="col-md-2 text-center" style="height: 170px;">   
														<img src="ticket/images/icons/<?php echo $imagem; ?>">
														<div class="push5"></div>
														<small><?php echo $imagem; ?></small>
														<div class="push5"></div>
														<input type="radio" name="icone" onclick="downForm('icons/<?php echo $imagem; ?>.svg');">
														
													</div>	
													<?php 
													}
												
												?>																		 
																						
											</div>
													
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>										
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>
				

	<script type="text/javascript">
	
		$(document).ready(function(){
				
		});	
		
		function downForm(image){			
			//try { parent.$('#NOM_CLIENTE').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}		
			try { parent.$('#<?php echo $campoIco; ?>').val(image); } catch(err) {}		
			//alert(image);
			$(this).removeData('bs.modal');	
			//console.log('entrou' + index);
			parent.$('#popModal').modal('hide');
		}
		
	</script>
	