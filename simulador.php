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

<style>


.tile-progress {
background-color: #303641;
color: #fff;
}
.tile-progress {
background: #00a65b;
color: #fff;
margin-bottom: 20px;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
-webkit-background-clip: padding-box;
-moz-background-clip: padding;
background-clip: padding-box;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
}
.tile-progress .tile-header {
padding: 15px 20px;
padding-bottom: 40px;
}
.tile-progress .tile-progressbar {
height: 2px;
background: rgba(0,0,0,0.18);
margin: 0;
}
.tile-progress .tile-progressbar span {
background: #fff;
}
.tile-progress .tile-progressbar span {
display: block;
background: #fff;
width: 0;
height: 100%;
-webkit-transition: all 1.5s cubic-bezier(0.230,1.000,0.320,1.000);
-moz-transition: all 1.5s cubic-bezier(0.230,1.000,0.320,1.000);
-o-transition: all 1.5s cubic-bezier(0.230,1.000,0.320,1.000);
transition: all 1.5s cubic-bezier(0.230,1.000,0.320,1.000);
}
.tile-progress .tile-footer {
padding: 20px;
text-align: right;
background: rgba(0,0,0,0.1);
-webkit-border-radius: 0 0 3px 3px;
-webkit-background-clip: padding-box;
-moz-border-radius: 0 0 3px 3px;
-moz-background-clip: padding;
border-radius: 0 0 3px 3px;
background-clip: padding-box;
-webkit-border-radius: 0 0 3px 3px;
-moz-border-radius: 0 0 3px 3px;
border-radius: 0 0 3px 3px;
}
.tile-progress.tile-red {
background-color: #f56954;
color: #fff;
}
.tile-progress {
background-color: #303641;
color: #fff;
}
.tile-progress.tile-blue {
background-color: #0073b7;
color: #fff;
}
.tile-progress.tile-aqua {
background-color: #00c0ef;
color: #fff;
}
.tile-progress.tile-green {
background-color: #00a65a;
color: #fff;
}
.tile-progress.tile-cyan {
background-color: #00b29e;
color: #fff;
}
.tile-progress.tile-purple {
background-color: #ba79cb;
color: #fff;
}
.tile-progress.tile-pink {
background-color: #ec3b83;
color: #fff;
}

</style>

			
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
								
								<?php $abaEmpresa = 1032; include "abasEmpresaConfig.php"; ?>
								
								<div class="push30"></div> 
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
				
									<div class="row">
									
									<div class="push30"></div> 
									
										<div class="col-sm-3">
											<div class="tile-progress tile-primary">
												<div class="tile-header">
													<h3>Regra Default</h3>
													<span>Descrição da regra geral e variáveis de cálculo</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="65.5%" style="width: 65.5%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">65.5</span>% crescimento
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-red">
												<div class="tile-header">
													<h3>Regra Adicional 1</h3>
													<span>Descrição da regra geral e variáveis de cálculo</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="23.2%" style="width: 23.2%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">23.2</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-blue">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="78%" style="width: 78%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">78</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-aqua">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="22%" style="width: 22%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">22</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-3">
											<div class="tile-progress tile-green">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="94%" style="width: 94%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">94</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-cyan">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="45.9%" style="width: 45.9%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">45.9</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-purple">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="27%" style="width: 27%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">27</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="tile-progress tile-pink">
												<div class="tile-header">
													<h3>Visitors</h3>
													<span>so far in our blog, and our website.</span>
												</div>
												<div class="tile-progressbar">
													<span data-fill="3" style="width: 3%;"></span>
												</div>
												<div class="tile-footer">
													<h4>
														<span class="pct-counter">3</span>% increase
													</h4>
													<span>so far in our blog and our website</span>
												</div>
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