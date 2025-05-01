<?php

	//echo fnDebug('true');
	$hashLocal = mt_rand();

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	
	$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";
	
	//fnEscreve($sql);

	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	

?>			
					
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
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
										
										<div class="push30"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
										
												<table class="table table-bordered table-striped table-hover tablesorter">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Persona</th>
													  <th>Abreviação</th>
													  <th>Ativo</th>
													  <th>Hits</th>
													  <th>Data de Criação</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
													
													$sql = "select * from persona where cod_empresa = ".$cod_empresa." and LOG_ATIVO = 'S' order by DES_PERSONA ";
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														 if ($qrListaPersonas['LOG_ATIVO'] == "S"){		
																$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ''; }															 
																							
															//$sqlPersonas = "CALL SP_BUSCA_PERSONA(".$qrListaPersonas['COD_PERSONA'].",".$cod_empresa."); ";
															
															$sqlPersonas = " SELECT  COUNT(A.COD_CLIENTE)  as TOTALCLI,
															(SELECT COUNT(B.COD_CLIENTE) FROM PERSONACLASSIFICA B WHERE B.COD_PERSONA = ".$qrListaPersonas['COD_PERSONA']." AND 
															B.COD_EMPRESA=A.COD_EMPRESA) AS TOTAL_PERSONA
															FROM CLIENTES A
															WHERE A.COD_EMPRESA = $cod_empresa ";
															//fnEscreve($sqlPersonas);
															$sqlPersonasquery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());
															$ListaPersonas = mysqli_fetch_assoc($sqlPersonasquery);
														
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td><a class='btn btn-xs btn-info' style='border:0; padding: 3px 5px;  background-color: #".$qrListaPersonas['DES_COR']."; color: #fff;' ><i class='fa ".$qrListaPersonas['DES_ICONE']." ' aria-hidden='true'></i></a> <small>
															  ".$qrListaPersonas['DES_PERSONA']."</td>
															  <td align='center'>".$qrListaPersonas['ABR_PERSONA']."</td>
															  <td align='center'>".$mostraAtivo."</td>
															  <td class='text-center'>".fnValor($ListaPersonas['TOTAL_PERSONA'],0)."</td>
															  <td>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</td>
															</tr>
															<input type='hidden' id='ret_IDP_".$count."' value='".fnEncode($qrListaPersonas['COD_PERSONA'])."'>
															<input type='hidden' id='ret_ID_".$count."' value='".fnEncode($qrListaPersonas['COD_EMPRESA'])."'>
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
			$("#codBusca").val($("#ret_IDP_"+index).val());			
			$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_ID_"+index).val()+'&idP='+$("#ret_IDP_"+index).val());					
			$('#formLista').submit();					
		}
	
	</script>
	