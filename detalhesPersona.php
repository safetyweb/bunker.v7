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

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
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
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_persona = fnDecode($_GET['idp']);	
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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th>Código</th>
													  <th>Campanha</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT b.COD_CAMPANHA,b.DES_CAMPANHA, c.COD_TPCAMPA
															FROM campanharegra a,campanha b
															LEFT JOIN WEBTOOLS.TIPOCAMPANHA c ON c.COD_TPCAMPA = b.TIP_CAMPANHA
															WHERE a.cod_campanha=b.cod_campanha AND b.cod_empresa=$cod_empresa AND FIND_IN_SET($cod_persona,a.COD_PERSONA) AND b.COD_EXCLUSA=0
															union
															 SELECT b.COD_CAMPANHA,b.DES_CAMPANHA, c.COD_TPCAMPA from EMAIL_PARAMETROS a
															 INNER JOIN campanha b ON b.cod_campanha=a.cod_campanha
															 LEFT JOIN WEBTOOLS.TIPOCAMPANHA c ON c.COD_TPCAMPA = b.TIP_CAMPANHA
															 WHERE FIND_IN_SET($cod_persona,a.COD_PERSONAS) AND a.cod_empresa=$cod_empresa 
															 union
															 
															SELECT b.COD_CAMPANHA,b.DES_CAMPANHA, c.COD_TPCAMPA from EMAIL_CONTROLE_AUX a
															 INNER JOIN campanha b ON b.cod_campanha=a.cod_campanha
															 LEFT JOIN WEBTOOLS.TIPOCAMPANHA c ON c.COD_TPCAMPA = b.TIP_CAMPANHA
															 WHERE FIND_IN_SET($cod_persona,a.COD_PERSONAS) AND a.cod_empresa=$cod_empresa
															union

															SELECT b.COD_CAMPANHA,b.DES_CAMPANHA, c.COD_TPCAMPA FROM SMS_PARAMETROS A
															INNER JOIN campanha b ON b.cod_campanha=a.cod_campanha
															 LEFT JOIN WEBTOOLS.TIPOCAMPANHA c ON c.COD_TPCAMPA = b.TIP_CAMPANHA
															  WHERE FIND_IN_SET($cod_persona,a.COD_PERSONAS) AND a.cod_empresa=$cod_empresa
															  union
															  SELECT  b.COD_CAMPANHA,b.DES_CAMPANHA, c.COD_TPCAMPA  FROM SMS_CONTROLE_AUX A
															  INNER JOIN campanha b ON b.cod_campanha=a.cod_campanha
															 LEFT JOIN WEBTOOLS.TIPOCAMPANHA c ON c.COD_TPCAMPA = b.TIP_CAMPANHA
															  WHERE FIND_IN_SET($cod_persona,a.COD_PERSONAS) AND a.cod_empresa=$cod_empresa";
													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrDetPers = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;

														if ($qrDetPers['COD_TPCAMPA'] == 21){
															$mod = 1169;
														}
														else {
															$mod = 1022;
														}

														?>
															<tr>
															  <td><?=$qrDetPers['COD_CAMPANHA']?></td>
															  <td><a href="action.do?mod=<?php echo fnEncode($mod);?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($qrDetPers[COD_CAMPANHA]); ?>&idt=<?php echo fnEncode($qrDetPers[COD_TPCAMPA]); ?>" target="_blank"><?=$qrDetPers['DES_CAMPANHA']?></a></td>
															</tr>
														<?php 

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

		
	</script>	