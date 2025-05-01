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
									
									<?php 
									$formBack = "1036";
									include "atalhosPortlet.php"; ?>
									
								</div>								
									
								<div class="push30"></div> 
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
				
									<div class="row">
									
										<div class="col-md-2">

											<div class="panelBox borda">
											<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(1040)?>&pop=true" data-title="Campanhas">
											<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 75px 0 75px 0;"></i>
											</div>											
											</div> 
											
										</div>
										
										<div class="col-md-2">
										
											<div class="panel">
											<a href="action.php?mod=<?php echo fnEncode(1022)?>">
											<div class="top primary"><i class="fa fa-car fa-3x iwhite" aria-hidden="true"></i>
											<h6>Milhas de Vantagem</h6>    	     
											</div>
											<div class="bottom">
											<h2>17720</h2>
											<h6>clientes participantes</h6>
											</div>
											</a>
											</div>

										</div>

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top warning"><i class="fa fa-flag-checkered fa-3x iwhite" aria-hidden="true"></i>
												<h6>Meu Objetivo</h6>
												</div>
												<div class="bottom">
												<h2>21000</h2>
												<h6>clientes participantes</h6>
												</div>
												</a>
											</div>
										
										</div>							

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top success"><i class="fa fa-gift fa-3x iwhite" aria-hidden="true"></i>
												<h6>Fim de Ano</h6>
												</div>
												<div class="bottom">
												<h2>2034</h2>
												<h6>Prêmios Distribuídos</h6>
												</div>
												</a>
											</div>

										</div>							

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top danger"><i class="fa fa-comments fa-3x iwhite" aria-hidden="true"></i>
												<h6>Canal Direto</h6>
												</div>
												<div class="bottom">
												<h2>259633</h2>
												<h6>clientes comunicados</h6>
												</div>
												</a>
											</div>
										
										</div>							

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top"><i class="fa fa-tachometer fa-3x iwhite" aria-hidden="true"></i>
												<h6>Top Client</h6>
												</div>
												<div class="bottom">
												<h2>5551</h2>
												<h6>Clientes Promovidos</h6>
												</div>
												</a>
											</div>

										</div>
												
									</div>
									
									<div class="push50"></div>
									
									<div class="row">
										
										<h4 style="margin-left: 15px;"><i class="fa fa-archive"></i> Arquivo</h4>
										
										<div class="push20"></div>

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top success"><i class="fa fa-comments fa-3x iwhite" aria-hidden="true"></i>
												<h6>Canal Direto</h6>
												</div>
												<div class="bottom">
												<h2>159.633</h2>
												<h6>clientes participantes</h6>
												</div>
												</a>
											</div>
										
										</div>							

										<div class="col-md-2">

											<div class="panel">
												<a href="action.php?mod=<?php echo fnEncode(1022)?>">
												<div class="top"><i class="fa fa-tachometer fa-3x iwhite" aria-hidden="true"></i>
												<h6>Top Client</h6>
												</div>
												<div class="bottom">
												<h2>5.551</h2>
												<h6>clientes participantes</h6>
												</div>
												</a>
											</div>

										</div>			

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