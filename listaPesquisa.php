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
										<i class="fal fa-terminal"></i>
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
																			
										<div class="col-md-4">
										
											<h3 style="margin-top:0;">Dashboards</h3>
											
											<div class="push10"></div>
											
											<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1273); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; Pesquisas </a> <br/>
											<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1626); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; Diário </a> <br/>
											<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1627); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; Sintético </a> <br/>
											<div class="push5"></div>
										
										</div>										
																			
										<div class="col-md-4">
										
											<h3 style="margin-top:0;">Relatórios</h3>
											
											<div class="push10"></div>
											<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1630); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; Diário</a> <br/>
											<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1631); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; Novos Cadastros</a> <br/>
											<div class="push5"></div>
										
										</div>
																
									
									<div class="push100"></div>
									<div class="push100"></div>
									<div class="push100"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>				

	<script type="text/javascript">	
	
		function retornaForm(index){
			$("#codBusca").val($("#ret_ID_"+index).val());			
			$("#codBusca").val($("#ret_IDC_"+index).val());	

			if(index == 0){
				$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1234); ?>&id='+$("#ret_ID_"+index).val()+'&idC='+$("#ret_IDC_"+index).val());					
			} else if (index == 1){
				$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1235); ?>&id='+$("#ret_ID_"+index).val()+'&idC='+$("#ret_IDC_"+index).val());					
			}
			
			
			$('#formLista').submit();					
		}
	
	</script>
	