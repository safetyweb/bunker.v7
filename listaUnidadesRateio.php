<?php
 //fnDebug('true');

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


			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];	
						
			if ($opcao != ''){
								
				
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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
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
										<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
									</div>
									
									<?php 
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
								
									<?php 
										$abaMetas = 1328;
										include "abasUsuariosMetas.php";
									?>									
									
									<div class="push"></div> 
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
													
									<div class="login-form">
									
										<form method="post" id="formLista">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
																														
										<div class="col-lg-12">
										<h4>Escolha a unidade desejada</h4>
											<div class="no-more-tables">
																						
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Cód. Externo</th>
													  <th>Nome da Unidade</th>
													  <th>Nome Fantasia</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select * from unidadevenda where COD_EMPRESA = '".$cod_empresa."' 
															and LOG_ESTATUS = 'S'
															and cod_exclusa is null order by trim(NOM_FANTASI)";
                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													//fnEscreve($sql);
													
													$count=0;
													while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														  if ($qrListaUniVendas['LOG_ESTATUS'] == 'S'){		
																$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ' '; }									
														
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrListaUniVendas['COD_UNIVEND']."</td>
															  <td>".$qrListaUniVendas['COD_EXTERNO']."</td>
															  <td>".$qrListaUniVendas['NOM_UNIVEND']."</td>
															  <td>".$qrListaUniVendas['NOM_FANTASI']."</td>
															</tr>
															<input type='hidden' id='ret_COD_UNIVEND_".$count."' value='".fnEncode($qrListaUniVendas['COD_UNIVEND'])."'>
															<input type='hidden' id='ret_NOM_UNIVEND_".$count."' value='".$qrListaUniVendas['NOM_UNIVEND']."'>
															<input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrListaUniVendas['NOM_FANTASI']."'>
															"; 
														  }											
													
												?>
													
												</tbody>
												</table>
												
										</form>

											</div>
											
										</div>

									<span style="color:#fff;"><?php echo($count); ?></span>
									
									<div class="push10"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			
			//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
			$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1331); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idU='+$("#ret_COD_UNIVEND_"+index).val());					
			$('#formLista').submit();	
		
		}	
		
		
	</script>	