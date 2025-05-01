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
			
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
			$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_SERVIDORES (
				 '".$cod_servidor."', 
				 '".$des_servidor."', 
				 '".$des_abrevia."', 
				 '".$cod_operacional."', 
				 '".$des_geral."', 
				 '".$des_observa."', 
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
				
									<div class="row">
									
										<div class="col-md-2">
										
											<div class="panelBox">
												<a href="#">
												<div class="addBox" data-url="action.php?mod=MnTYhBAkY5o¢&id=K2xr0lE3UHI¢&pop=true" data-title="Servidores" >
												<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 73px 0 73px 0;"></i>
												</div> 
												</a> 
											</div> 
										
										</div>
										  
										<?php 
										
										$sql = "select * from servidores order by des_servidor";
										$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
										
										$count=0;
										while ($qrListaServer = mysqli_fetch_assoc($arrayQuery))
										  {														  
											$count++;
											if ($qrListaServer['COD_SERVIDOR'] == 1 ) {
												$tipoServer = "fa-windows";	
											}else {$tipoServer = "fa-linux";	}
											?>	
										
										<div class="col-md-2">

											<div class="panel">
												<a href="#" class="addBox" data-url="action.php?mod=MnTYhBAkY5o¢&id=<?php echo fnEncode($qrListaServer['COD_SERVIDOR']) ?>&pop=true" data-title="Servidores" >
												<div class="top primary"><i class="fa fa-server fa-3x iwhite" aria-hidden="true"></i>
												<div class="push5"></div>
												</div>
												<div class="bottom">
												<h2 style="font-size: 20px; margin: 0 0 10px 0;"><?php echo $qrListaServer['DES_ABREVIA'] ?>   </h2>
												<h6 style="padding: 10px 0 0 0;"><i class="fa  <?php echo $tipoServer ?> fa-lg" aria-hidden="true"></i></h6>
												</div>
												</a>
											</div>
										
										</div>													
											 
												
									<?php 		
											  }											

									?>		
										
									</div>
									
									<!-- modal -->									
									<div class="modal fade" id="popModal" tabindex='-1'>
										<div class="modal-dialog" style="">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title"></h4>
												</div>
												<div class="modal-body">
													<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
												</div>		
											</div><!-- /.modal-content -->
										</div><!-- /.modal-dialog -->
									</div><!-- /.modal -->								
								
												
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
	
		$(document).ready(function(){


		});		
	
		function retornaForm(index){
			$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val());
			$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_"+index).val());
			$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	