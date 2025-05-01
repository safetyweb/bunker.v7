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
		$cod_contrat = fnDecode($_GET['idC']);
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

	// fnEscreve($cod_empresa);
	
	//fnMostraForm();

        
?>
					<meta http-equiv=“Pragma” content=”no-cache”>
					<meta http-equiv=“Expires” content=”-1″>
					<meta http-equiv=“CACHE-CONTROL” content=”NO-CACHE”>
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
									
										<form method="post" id="formLista" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
																														
										<div class="col-lg-12">
										<h4>Escolha a unidade desejada</h4>
											<div class="no-more-tables">
																						
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th class="{ sorter: false } text-center" width="40">Ativo</th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													  <th class="{ sorter: false } text-center" width="40">Ativo</th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT UN.COD_UNIVEND, UN.NOM_FANTASI, UN.LOG_ESTATUS, UN.COD_PROPRIEDADE
															FROM UNIDADEVENDA UN
															WHERE UN.COD_EMPRESA = $cod_empresa
															AND (UN.COD_EXCLUSA IS NULL OR UN.COD_EXCLUSA = 0) ORDER BY TRIM(UN.NOM_FANTASI)";

                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
													// fnEscreve($sql);
													
													$count=0;
													while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
													{			

														if ($qrListaUniVendas['LOG_ESTATUS'] == 'S'){		
															$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
														}else{ 
															$mostraAtivo = ''; 
														}

														$sqlCont = "SELECT COD_UNICONT FROM CONTRATO_UNIDADE WHERE COD_UNIVEND = $qrListaUniVendas[COD_UNIVEND] AND COD_CONTRAT = $cod_contrat";
														$qrCont = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlCont));

														$qtd_loja = 0;

														if (!empty($qrCont['COD_UNICONT']) && isset($qrCont['COD_UNICONT']) && $qrCont['COD_UNICONT'] != '' ){		
															$univendAtiva = 'checked';	
														}else{ 
															$univendAtiva = ''; 
														}

														if($count % 2 == 0){ 
													        $abreTR = "<tr>";
													        $fechaTR = "";  
													    }else{
													    	$abreTR = "";
													        $fechaTR = "</tr>";
													    }

													    if ($qrListaUniVendas['COD_PROPRIEDADE'] == 2){
													    	$franqueado = "FRANQUIA";
													    }else if($qrListaUniVendas['COD_PROPRIEDADE'] == 1){
													    	$franqueado = "PRÓPRIA";
													    }else{
													    	$franqueado = "INDEFINIDO";
													    }

													    if($qrListaUniVendas['LOG_ESTATUS'] == "N"){
													    	$corInativa = "text-danger";
													    }else{
													    	$corInativa = "";
													    }

														// fnEscreve(($count % 2));								
														
														echo $abreTR."
															  <td class='text-center'>
															  	   
																<div class='form-group'>
																	<label class='switch'>
																		<input type='checkbox' name='LOG_UNIVEND_".$count."' id='LOG_UNIVEND_".$count."' class='switch' value='S' onchange='mudaAtivo(".$count.")' ".$univendAtiva.">
																		<span></span>
																	</label>
																</div>

															  </td>
															  <td class='$corInativa'>".$qrListaUniVendas['COD_UNIVEND']."</td>
															  <td class='$corInativa'>".$qrListaUniVendas['NOM_FANTASI']."<span class='f12'> ($franqueado)</span></td>

															  <input type='hidden' id='ret_COD_UNIVEND_".$count."' value='".fnEncode($qrListaUniVendas['COD_UNIVEND'])."'>
															  <input type='hidden' id='ret_NOM_UNIVEND_".$count."' value='".$qrListaUniVendas['NOM_UNIVEND']."'>
															  <input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrListaUniVendas['NOM_FANTASI']."'>

															".$fechaTR;

														$count++; 
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

	<?php
	if (!is_null($RedirectPg)) {
		$DestinoPg = fnEncode($RedirectPg);		
	}else {
		$DestinoPg = "";		
		}	
	?>	
	
	<script type="text/javascript">
		
		function mudaAtivo(index){

			cod_univend = $("#ret_COD_UNIVEND_"+index).val(),
			cod_contrat = '<?=fnEncode($cod_contrat)?>',
			cod_empresa = '<?=fnEncode($cod_empresa)?>',
			qtd_loja = $('input:checkbox:checked').length;

			if($("#LOG_UNIVEND_"+index).prop("checked") == true){
				var log_univend = "S";
			}else{
				var log_univend = "N";
			}

			$.ajax({
				method: 'POST',
				url: 'ajxUnidadesContrato.php',
				data: {
						COD_UNIVEND: cod_univend, 
						LOG_UNIVEND: log_univend, 
						COD_EMPRESA: cod_empresa, 
						COD_CONTRAT: cod_contrat
				},
				success:function(data){
					// console.log(data);
					try { 
						parent.$('#QTD_LOJA_<?=$cod_contrat?>').text(qtd_loja);
						parent.$('#VAL_LIQUIDO_<?=$cod_contrat?>').text(data);
					} catch(err) {}
				}
			});	
		
		}	
		
		
	</script>	