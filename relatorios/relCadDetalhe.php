<?php
	
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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

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
		$cod_univend = fnLimpaCampoZero(fnDecode($_GET['cod_univend']));	
		$dat_ini = $_GET['dat_ini'];
		$dat_fim = $_GET['dat_fim'];
		$cod_atendente = fnDecode($_GET['cod_vendedor']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = $cod_empresa";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	//rotina de controle de acessos por módulo
	include "moduloControlaAcesso.php";

	// fnEscreve($dat_ini);
	// fnEscreve($dat_fim);
	// fnEscreve($cod_atendente);
	
	//fnMostraForm();

?>
					
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
										<span class="text-primary"><?php echo $NomePg; ?></span>
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
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th>Cliente</th>
													  <th>Nascimento</th>
													  <th>Celular</th>
													  <th>Telefone</th>
													  <th>E-mail</th>
													  <th>Endereço</th>
													  <th>CEP</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT          
																A.COD_EMPRESA, 
												            	A.COD_UNIVEND, 
																A.COD_CLIENTE,
																A.NOM_CLIENTE,
																A.DAT_NASCIME,
																A.NUM_CELULAR,
																A.NUM_TELEFON,
																A.DES_EMAILUS,
																A.DES_ENDEREC,
																A.NUM_CEPOZOF,
																A.COD_ATENDENTE 
															FROM CLIENTES A 
															WHERE A.COD_ATENDENTE = $cod_atendente
															AND A.COD_UNIVEND = $cod_univend  
															AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
															AND A.LOG_AVULSO='N' 
															AND A.COD_EMPRESA = $cod_empresa 
															ORDER BY A.NOM_CLIENTE";

													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrDetalhe = mysqli_fetch_assoc($arrayQuery)){					

													  	if(fnControlaAcesso("1024",$arrayParamAutorizacao) === true) { 
										                    $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrDetalhe['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrDetalhe['NOM_CLIENTE']) . "</a></small></td>";
										                }else{ 
										                    $colCliente = "<td><small>" . fnMascaraCampo($qrDetalhe['NOM_CLIENTE']) . "</small></td>";
										                }

														$count++;	
														echo"
															<tr>
															  <!--<td>".$qrDetalhe['COD_CLIENTE']."</td>-->
															  ".$colCliente."
															  <td>".$qrDetalhe['DAT_NASCIME']."</td>
															  <td>".$qrDetalhe['NUM_CELULAR']."</td>
															  <td>".$qrDetalhe['NUM_TELEFON']."</td>
															  <td>".$qrDetalhe['DES_EMAILUS']."</td>
															  <td>".$qrDetalhe['DES_ENDEREC']."</td>
															  <td>".$qrDetalhe['NUM_CEPOZOF']."</td>
															</tr>
															
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
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	