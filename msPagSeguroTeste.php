<?php
	
	//echo "<h5>_".$opcao."</h5>";
        //pagseguro
	echo sessions_PagSeguro();

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
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1019";
									include "atalhosPortlet.php"; 
									?>	

								</div>
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
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Número do Cartão</label>
															<input type="text" class="form-control input-sm" name="NRO_CARTAO" id="NRO_CARTAO" maxlength="50" value="4111111111111111" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Bandeira</label>
															<input type="text" class="form-control input-sm" name="DES_BANDE" id="DES_BANDE" maxlength="50" value="visa" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">CVV</label>
															<input type="text" class="form-control input-sm" name="CVV" id="CVV" maxlength="50" value="123" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Ano</label>
															<input type="text" class="form-control input-sm" name="DES_ANO" id="DES_ANO" maxlength="50" value="2030" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Mês</label>
															<input type="text" class="form-control input-sm" name="DES_MES" id="DES_MES" maxlength="50" value="12" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="PAY" id="PAY" class="btn btn-primary getBtn"><i class="fa fa-dollar-sign" aria-hidden="true"></i>&nbsp; Pagar</button>
											  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
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
													  <th>Código</th>
													  <th>Nome do Grupo</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_GRUPOTR']."</td>
															  <td>".$qrBuscaModulos['DES_GRUPOTR']."</td>
															</tr>
															<input type='hidden' id='ret_COD_GRUPOTR_".$count."' value='".$qrBuscaModulos['COD_GRUPOTR']."'>
															<input type='hidden' id='ret_DES_GRUPOTR_".$count."' value='".$qrBuscaModulos['DES_GRUPOTR']."'>
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

	<!-- <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script> -->
	<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

	<script>
		
		PagSeguroDirectPayment.setSessionId(<?php echo $ID['body']['session']['id'];?>);

		// linkar num evento de click de pagar
		// Identificador=PagSeguroPayment.getSenderHash();

		$(function(){

			// alert($("#NUM_CARTAO").val());


			$("#PAY").click(function(e){
				e.preventDefault();
				var param = {
					cardNumber: $("#NUM_CARTAO").val(),
					brand: $("#DES_BANDE").val(),
					cvv: $("#CVV").val(),
					expirationMonth: $("#DES_MES").val(),
					expirationYear: $("#DES_ANO").val(),
					success:function(data){ 
						console.log('token: '+data);
					},
					error:function(data){
						console.log(data);
					},
					complete:function(data){
						console.log(data);
					}
				};
				PagSeguroDirectPayment.createCardToken(param);
				// idTransacao = PagSeguroPayment.getSenderHash();
				// console.log("id da transação: "+idTransacao);
			});

		});

	</script>