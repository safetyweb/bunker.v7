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
			$cod_usoind = fnLimpaCampoZero($_POST['COD_USOIND']);					
			$qtd_extraind = fnLimpaCampo($_POST['QTD_EXTRAIND']);
			$tip_extraind = fnLimpaCampo($_POST['TIP_EXTRAIND']);
			$qtd_diasind = fnLimpaCampo($_POST['QTD_DIASIND']);
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){

				//busca dados da regra extra (tela) 
				$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '".$cod_campanha."' ";
				//fnEscreve($sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				$tem_extra = mysqli_num_rows($arrayQuery);

				if ($tem_extra == 0){

					$sqlExtra = "INSERT INTO VANTAGEMEXTRA(
												COD_CAMPANHA, 
												COD_USUCADA, 
												COD_EMPRESA
											 ) VALUES(
											 	$cod_campanha,
											 	$cod_usucada,
											 	$cod_empresa
											 )";

					 mysqli_query(connTemp($cod_empresa,''),$sqlExtra);

				}
			
				$sql = "CALL SP_ALTERA_INDICA_CLIENTE_CAMPANHA (
				 '".$cod_controle."', 
				 '".$cod_empresa."', 
				 '".$cod_campanha."', 
				 '".fnValorSql($qtd_extraind)."',
				 '".$tip_extraind."', 
				 '".$qtd_diasind."', 
				 '".$cod_usucada."', 
				 '".$cod_usoind."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;	
				//fnTestesql(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
				mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
				//fnEscreve($sql2); 

				//busca quantidade total de itens	
				$sql2 = "select count(*) as TEMFAIXA from INDICA_CLIENTE_CAMPANHA where COD_EMPRESA = '".$cod_empresa."' and  COD_CAMPANHA = '".$cod_campanha."'  ";													
				//fnEscreve($sql2);

				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2) or die(mysqli_error());
				$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
				$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];				
				//fnEscreve($temfaixa);
				
				$sql3 = "update VANTAGEMEXTRA set QTD_INDICA = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " " ;
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql3) or die(mysqli_error());
				
				//atualiza lista iframe				
				?>
				<script>
					try { parent.$('#REFRESH_INDICA').val("S"); } catch(err) {}
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
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$qtd_diasind = $qrBuscaCampanha['QTD_DIASIND'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];		
	}	
 		
	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
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
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	}

	//BUSCA DADOS DA INDICAÇÃO
	$sql = "SELECT * FROM INDICA_CLIENTE_CAMPANHA WHERE COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$cod_controle = $qrBuscaTpCampanha['COD_CONTROLE'];
		$qtd_extraind = $qrBuscaTpCampanha['QTD_EXTRAIND'];
		$tip_extraind = $qrBuscaTpCampanha['TIP_EXTRAIND'];
		$qtd_diasind = $qrBuscaTpCampanha['QTD_DIASIND'];
		$cod_usoind = $qrBuscaTpCampanha['COD_USOIND'];

	}else{
		$cod_controle = 0;
		$cod_usoind = 0;
		$qtd_extraind = "";
		$tip_extraind = "";
		$qtd_diasind = ""; 
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
											<legend>Dados da Configuração da Indicação</legend>  
															
												<div class="row">

													<div class="col-md-3">
													<h5 class="text-center" style="padding-top: 13px;">CLIENTE INDICADOR GANHA</h5>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Qtd. Extra</label>
															<input type="text" class="form-control input-sm text-center money" name="QTD_EXTRAIND" id="QTD_EXTRAIND" maxlength="10" value="<?=fnValor($qtd_extraind,2)?>" required>
															<span class="help-block">valor</span>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo da Vantagem Extra</label>
																<select data-placeholder="Selecione a vantagem extra" name="TIP_EXTRAIND" id="TIP_EXTRAIND" class="chosen-select-deselect" required>
																	<option value="">...</option>					
																	<option value="PCT">Percentual sobre a venda</option>					
																	<!--<option value="PCV">Percentual sobre <?php echo strtolower($nom_vantagem); ?></option>-->
																	<option value="ABS"><?php echo $nom_tpcampa; ?></option>					
																</select>
																<script>$("#TIP_EXTRACAD").val("<?php echo $tip_extracad; ?>").trigger("chosen:updated"); </script>				
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo de Uso</label>
																<select data-placeholder="Selecione a vantagem extra" name="COD_USOIND" id="COD_USOIND" class="chosen-select-deselect" required>
																	<option value="">...</option>					
																	<!--<option value="1">Única vez</option>-->
																	<option value="2">Dia </option>
																	<option value="3">Semana </option>
																	<option value="4">Mês </option>
																	<option value="5">Ilimitado </option>
																</select>
																<script>$("#COD_USOIND").val("<?php echo $cod_usoind; ?>").trigger("chosen:updated"); </script>				
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Limite de Uso</label>
															<input type="text" class="form-control input-sm text-center" name="QTD_DIASIND" id="QTD_DIASIND" maxlength="20" value="<?=$qtd_diasind?>" required>
															<span class="help-block">quantidade máxima</span>
														</div>
													</div>
													
												</div>
												
										</fieldset>	
													
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  	<?php if($cod_controle != 0){ ?>
												  	<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
												  	<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
												<?php }else{ ?>
												  	<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
												<?php } ?>
											  
											
										</div>
										
										<input type="hidden" name="COD_CONTROLE" id="COD_CONTROLE" value="<?php echo $cod_controle; ?>">
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
			retornaForm();
			
        });

				
		function retornaForm(){

			if("<?=$tip_extraind?>" != ""){
				$("#formulario #TIP_EXTRAIND").val("<?=$tip_extraind?>").trigger("chosen:updated");
			}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   