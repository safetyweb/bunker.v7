<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();
	
	$cod_geral = 0;
	
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
			
			$cod_controle = fnLimpaCampoZero($_POST['COD_CONTROLE']);			
			$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_categoria = fnLimpaCampoZero($_POST['COD_CATEGORIA']);			
			$qtd_extracat = fnLimpaCampo($_POST['QTD_EXTRACAT']);
			$tip_extracat = fnLimpaCampo($_POST['TIP_EXTRACAT']);
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){
			
				$sql = "CALL SP_ALTERA_CATEGORIA_CLIENTE_CAMPANHA (
				 '".$cod_controle."', 
				 '".$cod_empresa."', 
				 '".$cod_campanha."', 
				 '".$cod_categoria."', 
				 '".fnValorSql($qtd_extracat)."',
				 '".$tip_extracat."', 
				 '".$cod_usucada."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;	
				mysqli_query(connTemp($cod_empresa,''),trim($sql));	
				//fnEscreve($sql2); 

				//busca quantidade total de itens	
				$sql2 = "select count(*) as TEMFAIXA from CATEGORIA_CLIENTE_CAMPANHA where COD_EMPRESA = '".$cod_empresa."' and  COD_CAMPANHA = '".$cod_campanha."'  ";													
				//fnEscreve($sql2);

				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2);
				$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
				$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];				
				//fnEscreve($temfaixa);
				
				$sql3 = "update VANTAGEMEXTRA set QTD_CATEGOR = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " " ;
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql3);
				
				//atualiza lista iframe				
				?>
				<script>
					try { parent.$('#REFRESH_CAT').val("S"); } catch(err) {}
					//alert(parent.$('#REFRESH_CAT').val())
				</script>						
				<?php					
									
				//}
				
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
	
	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);	
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];		
	}	
 		
	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
		$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
		$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
		$label_1 = $qrBuscaTpCampanha['LABEL_1'];
		$label_2 = $qrBuscaTpCampanha['LABEL_2'];
		$label_3 = $qrBuscaTpCampanha['LABEL_3'];
		$label_4 = $qrBuscaTpCampanha['LABEL_4'];
		$label_5 = $qrBuscaTpCampanha['LABEL_5'];
	}
	
	//busca dados da regra 
	$sql = "SELECT NOM_VANTAGE FROM CAMPANHAREGRA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	}
	
	//fnMostraForm();
	
?>
			
			
					<?php if ($popUp != "true"){  ?>							
					<div class="push30"></div> 
					<?php } ?>
					
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
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend>  
															
												<div class="row">

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Faixa de Cliente</label>
																<select data-placeholder="Selecione a vantagem extra" name="COD_CATEGORIA" id="COD_CATEGORIA" class="chosen-select-deselect">
																	<option value="">...</option>					
												
																<?php 
														
																$sql1 = "select
																		A.COD_CATEGORIA,
																		A.COD_EMPRESA,
																		A.NOM_FAIXACAT,
																		A.VAL_FAIXAINI,
																		A.VAL_FAIXAFIM,
																		A.NUM_ORDENAC
																		from
																		CATEGORIA_CLIENTE A
																		WHERE
																		A.COD_EMPRESA = $cod_empresa 
																		order by
																		NUM_ORDENAC";

																//fnEscreve($sql1);
																//fnTestesql(connTemp($cod_empresa,''),$sql);
																$arrayQuery1 = mysqli_query(connTemp($cod_empresa, ''), $sql1);

																$count=0;
																while ($qrBuscaCategoria = mysqli_fetch_assoc($arrayQuery1))
																{														  
																$count++;
																//fnEscreve();
																?>
																	<option value="<?php echo $qrBuscaCategoria['COD_CATEGORIA']; ?>"><?php echo $qrBuscaCategoria['NOM_FAIXACAT']; ?> (<?php echo fnValor($qrBuscaCategoria['VAL_FAIXAINI'],2); ?> a <?php echo fnValor($qrBuscaCategoria['VAL_FAIXAFIM'],2); ?>)</option>					

																<?php																	
																}
																?>	
																</select>
																<script>$("#TIP_EXTRACAD").val("<?php echo $tip_extracad; ?>").trigger("chosen:updated"); </script>				
															<div class="help-block with-errors"></div>
														</div>
													</div>
												
													<div class="col-md-2">
													<h5 class="text-center" style="padding-top: 13px;">GANHA</h5>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Qtd. Extra</label>
															<input type="text" class="form-control input-sm text-center money" name="QTD_EXTRACAT" id="QTD_EXTRACAT" maxlength="20" value="" required>
															<span class="help-block">valor</span>
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo da Vantagem Extra</label>
																<select data-placeholder="Selecione a vantagem extra" name="TIP_EXTRACAT" id="TIP_EXTRACAT" class="chosen-select-deselect">
																	<option value="">...</option>					
																	<option value="PCT">Percentual sobre a venda</option>					
																	<option value="ABS"><?php echo $nom_tpcampa; ?></option>					
																</select>
																<script>$("#TIP_EXTRACAD").val("<?php echo $tip_extracad; ?>").trigger("chosen:updated"); </script>				
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
										</fieldset>	
													
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="COD_CONTROLE" id="COD_CONTROLE" value="<?php echo $COD_CONTROLE; ?>">
										<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
	
										<!-- modal -->									
										<div class="modal fade" id="popModalAux" tabindex='-1'>
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
										
										
										<div class="push50"></div>
										
										<div id="div_Ordena"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Faixa de Cliente</th>
													  <th>Tipo de Vantagem</th>
													  <th>Ganho</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
															
													//$sql="select * from CATEGORIA_CLIENTE_CAMPANHA where cod_campanha = $cod_campanha ";
													$sql="select 
															B.NOM_FAIXACAT,
															A.COD_CONTROLE,
															A.COD_CATEGORIA,
															A.QTD_EXTRACAT,
															A.TIP_EXTRACAT
														from CATEGORIA_CLIENTE_CAMPANHA A
														left join CATEGORIA_CLIENTE B ON A.COD_CATEGORIA=B.COD_CATEGORIA 

														where A.COD_CAMPANHA = $cod_campanha AND
														A.COD_EMPRESA = $cod_empresa ";
													
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
									
													$count=0;
													
													while ($qrBuscaCatLimite = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														if ($qrBuscaCatLimite['TIP_FAIXEXT'] == "ABS") { $tipoGanho = $nom_tpcampa; }
														else { $tipoGanho = "%"; }

														switch ($qrBuscaCatLimite['TIP_EXTRACAT']) {
															case "PCT": //percentual sobre venda
																$tipoVantagem = "Percentual sobre venda";
																break;      
															case "ABS": //valor fixo em cash back ou pontos 
																$tipoVantagem = "Percentual sobre credito próxima compra";
																break;	
														}														
												
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaCatLimite['COD_CONTROLE']."</td>
															  <td>".$qrBuscaCatLimite['NOM_FAIXACAT']."</td>
															  <td>".$tipoVantagem."</td>
															  <td>".number_format ($qrBuscaCatLimite['QTD_EXTRACAT'],2,",",".")." ".$tipoGanho."</td>															
															</tr>
															<input type='hidden' id='ret_COD_CONTROLE_".$count."' value='".$qrBuscaCatLimite['COD_CONTROLE']."'>
															<input type='hidden' id='ret_COD_CATEGORIA_".$count."' value='".$qrBuscaCatLimite['COD_CATEGORIA']."'>
															<input type='hidden' id='ret_QTD_EXTRACAT_".$count."' value='".number_format ($qrBuscaCatLimite['QTD_EXTRACAT'],2,",",".")."'>
															<input type='hidden' id='ret_TIP_EXTRACAT_".$count."' value='".$qrBuscaCatLimite['TIP_EXTRACAT']."'>
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
					
 	<script>
		
        $(document).ready( function() {
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
        });

				
		function retornaForm(index){
			
			$("#formulario #COD_CONTROLE").val($("#ret_COD_CONTROLE_"+index).val());
			$("#formulario #COD_CATEGORIA").val($("#ret_COD_CATEGORIA_"+index).val()).trigger("chosen:updated");
			$("#formulario #QTD_EXTRACAT").val($("#ret_QTD_EXTRACAT_"+index).val());
			$("#formulario #TIP_EXTRACAT").val($("#ret_TIP_EXTRACAT_"+index).val()).trigger("chosen:updated");
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   