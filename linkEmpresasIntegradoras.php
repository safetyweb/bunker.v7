<?php

	//echo fnDebug('true');
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
										
										<div class="col-lg-12">

											<div class="no-more-tables"> 
											
												<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
										
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th width="40" class="{sorter:false}"></th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													  <th>Responsável</th>
													  <th>Telefones</th>
													  <th>Status</th>
													  <th class="{sorter:false}">BD</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
													$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.*,
															(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE 
															FROM empresas  
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
															WHERE 
															COD_INTEGRADORA = ".$_SESSION["SYS_COD_EMPRESA"]."  
															AND empresas.COD_STATUS <> 4 
															
															ORDER by NOM_FANTASI
													";
													//fnEscreve("2");
													//fnEscreve($_SESSION["SYS_COD_MULTEMP"]);
												
													//fnEscreve($sql);
													
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														  if ($qrListaEmpresas['LOG_ESTATUS'] == 'S'){		
																$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ''; }	
														
														 if ($qrListaEmpresas['COD_DATABASE'] > 0){		
																$mostraBD = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraBD = ''; }	
														
														  if (!empty($qrListaEmpresas['COD_SISTEMAS'])){
															  $tem_sistema = "tem";															  
														  }	else {$tem_sistema = "nao";}
														
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrListaEmpresas['COD_EMPRESA']."</td>
															  <td>".$qrListaEmpresas['NOM_FANTASI']."</td>
															  <td>".$qrListaEmpresas['NOM_RESPONS']."</td>
															  <td>".$qrListaEmpresas['NUM_TELEFON']." / ".$qrListaEmpresas['NUM_CELULAR']."</td>
															  <td align='center'>".$qrListaEmpresas['DES_STATUS']."</td>
															  <td align='center'>".$mostraBD."</td>
															</tr>
															<input type='hidden' id='ret_IDC_".$count."' value='".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>
															<input type='hidden' id='ret_ID_".$count."' value='".$qrListaEmpresas['COD_EMPRESA']."'>
															<input type='hidden' id='ret_NOM_EMPRESA_".$count."' value='".$qrListaEmpresas['NOM_EMPRESA']."'>
															"; 
														  }											
													
												?>
													
												</tbody>
												</table>
												
												<div class="push50"></div>
												
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
			
			$("#codBusca").val($("#ret_ID_"+index).val());			
			$("#codBusca").val($("#ret_IDC_"+index).val());			
			$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
			$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_IDC_"+index).val());					
			$('#formLista').submit();					
		}
	
	</script>
        <?php
       
        ?>