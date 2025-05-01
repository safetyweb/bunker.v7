<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	$mod = fnDecode($_GET['mod']);

	if($mod == 1699){
		$log_juridico = "S";
	}else{
		$log_juridico = "N";
	}
	
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

			$cod_dados = fnLimpaCampoZero($_REQUEST['COD_DADOS']);
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$num_banco = fnLimpaCampoZero($_REQUEST['NUM_BANCO']);
			$num_agencia = fnLimpaCampoZero($_REQUEST['NUM_AGENCIA']);
			$num_contaco = fnLimpacampo($_REQUEST['NUM_CONTACO']);
			$num_pix = fnLimpacampo($_REQUEST['NUM_PIX']);
			$tip_pix = fnLimpacampoZero($_REQUEST['TIP_PIX']);
			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO DADOS_BANCARIOS(
													COD_EMPRESA,
													COD_CLIENTE,
													NUM_BANCO,
													NUM_AGENCIA,
													NUM_CONTACO,
													NUM_PIX,
													TIP_PIX,
													LOG_JURIDICO,
													COD_USUCADA
												) VALUES(
													$cod_empresa,
													$cod_cliente,
													$num_banco,
													$num_agencia,
													'$num_contaco',
													'$num_pix',
													$tip_pix,
													'$log_juridico',
													$cod_usucada
											)";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,''),$sql);

						$sqlCli = "SELECT MAX(COD_DADOS) COD_DADOS FROM DADOS_BANCARIOS 
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_USUCADA = $cod_usucada
									AND COD_CLIENTE = $cod_cliente";

						$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

						$qrCli = mysqli_fetch_assoc($arrayCli);

						$cod_cliente = $qrCli[COD_CLIENTE];

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE DADOS_BANCARIOS SET
												NUM_BANCO = $num_banco,
												NUM_AGENCIA = $num_agencia,
												NUM_CONTACO = '$num_contaco',
												NUM_PIX = '$num_pix',
												TIP_PIX = $tip_pix,
												COD_ALTERAC=$cod_usucada,
												DAT_ALTERAC=NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_DADOS = $cod_dados";

						// fnEscreve();

						mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':

						$sql = "DELETE FROM DADOS_BANCARIOS
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_DADOS = $cod_dados";

						mysqli_query(connTemp($cod_empresa,''),$sql);
						
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
		$cod_cliente = fnDecode($_GET['idc']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
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
	// fnEscreve($cod_cliente);
	
	//fnMostraForm();

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Número Banco</label>
															<input type="text" class="form-control input-sm int" name="NUM_BANCO" id="NUM_BANCO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" value="<?=$num_banco?>">
														</div>
														<div class="help-block with-errors"></div>
													</div>       
										
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Agência</label>
															<input type="text" class="form-control input-sm" name="NUM_AGENCIA" id="NUM_AGENCIA" value="" maxlength="10" value="<?=$num_agencia?>">
														</div>
														<div class="help-block with-errors"></div>
													</div>       
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Conta Corrente</label>
															<input type="text" class="form-control input-sm" name="NUM_CONTACO" id="NUM_CONTACO" value="" maxlength="10" value="<?=$num_contaco?>">
														</div>
														<div class="help-block with-errors"></div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">PIX</label>
															<input type="text" class="form-control input-sm" name="NUM_PIX" id="NUM_PIX" value="" maxlength="45" value="<?=$num_pix?>">
														</div>
														<div class="help-block with-errors"></div>
													</div>

													<div class="col-xs-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo de Pix</label>
																<select data-placeholder="Selecione o tipo do PIX" name="TIP_PIX" id="TIP_PIX" class="chosen-select-deselect">
																	<option></option>
																	<option value="3">CPF/CNPJ</option>					
																	<option value="1">Celular</option>					
																	<option value="2">Email</option>
																</select>
                                                                <!-- <script>$("#formulario #TIP_PIX").val("<?php echo $tip_pix; ?>").trigger("chosen:updated"); </script> -->
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
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
										<input type="hidden" name="COD_DADOS" id="COD_DADOS" value="<?=$cod_dados?>">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Banco</th>
													  <th>Agência</th>
													  <th>CC</th>
													  <th>PIX</th>
													  <th>Tipo do PIX</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT * FROM DADOS_BANCARIOS 
															WHERE COD_EMPRESA = $cod_empresa 
															AND COD_CLIENTE = $cod_cliente
															AND LOG_JURIDICO = '$log_juridico'";

													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrDados = mysqli_fetch_assoc($arrayQuery))
													  {			

													  	switch ($qrDados['TIP_PIX']) {

													  		case 1:
												  				$tip_pix = "Celular";
												  			break;

												  			case 2:
												  				$tip_pix = "Email";
												  			break;

												  			case 3:
												  				$tip_pix = "CPF/CNPJ";
												  			break;
													  		
													  		default:
												  				$tip_pix = "";
												  			break;
													  	}

														$count++;	
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrDados['NUM_BANCO']."</td>
															  <td>".$qrDados['NUM_AGENCIA']."</td>
															  <td>".$qrDados['NUM_CONTACO']."</td>
															  <td>".$qrDados['NUM_PIX']."</td>
															  <td>".$tip_pix."</td>
															</tr>
															<input type='hidden' id='ret_COD_DADOS_".$count."' value='".$qrDados['COD_DADOS']."'>
															<input type='hidden' id='ret_NUM_BANCO_".$count."' value='".$qrDados['NUM_BANCO']."'>
															<input type='hidden' id='ret_NUM_AGENCIA_".$count."' value='".$qrDados['NUM_AGENCIA']."'>
															<input type='hidden' id='ret_NUM_CONTACO_".$count."' value='".$qrDados['NUM_CONTACO']."'>
															<input type='hidden' id='ret_NUM_PIX_".$count."' value='".$qrDados['NUM_PIX']."'>
															<input type='hidden' id='ret_TIP_PIX_".$count."' value='".$qrDados['TIP_PIX']."'>
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
			$("#formulario #COD_DADOS").val($("#ret_COD_DADOS_"+index).val());
			$("#formulario #NUM_BANCO").val($("#ret_NUM_BANCO_"+index).val());
			$("#formulario #NUM_AGENCIA").val($("#ret_NUM_AGENCIA_"+index).val());
			$("#formulario #NUM_CONTACO").val($("#ret_NUM_CONTACO_"+index).val());
			$("#formulario #NUM_PIX").val($("#ret_NUM_PIX_"+index).val());
			$("#formulario #TIP_PIX").val($("#ret_TIP_PIX_"+index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	