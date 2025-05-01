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
										<i class="fal fa-terminal"></i>
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
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="">
		
											<div class="container">
												<div class="row">

													<?php
														switch ($cod_empresa) {
															case 121: //águia postos
															case 91: //renaza 
															case 143: //águia postos
															case 176: // posto amigao
															case 190: // viplac
															case 198: // itapoan
																$mostrac10 = "style='display: block;'";
																$disabled = "";
															break;

															default:
																$mostrac10 = "style='display: none;'";
																$disabled = "disabled";
															break;
														}
													?>	
												
													<div class="col-md-3 col-sm-1">
													</div>	
													
													<div class="col-md-6 col-sm-10">
														<div class="form-group">
															<label for="inputName" class="control-label required"></label>
															<input type="text" class="form-control input-lg text-center cpfcnpj" name="c1" id="c1" value="" placeholder="Informe seu CPF/CNPJ">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
													</div>	
													
													<div class="push30"></div> 
				

													<div class="col-md-3">
													</div>	

													<div class="col-md-6 col-sm-10 f21 text-center" <?=$mostrac10?>>OU</div>			
													
													<div class="col-md-3">
													</div>	

													<div class="push30"></div> 				

													<div class="col-md-3 col-sm-1">
													</div>	
													
													<div class="col-md-6 col-sm-10">
														<div class="form-group">
															<label for="inputName" class="control-label required"></label>
															<input type="text" class="form-control input-lg text-center cartao" name="c10" id="c10" value="" maxlength="10" autocomplete="off" placeholder="Número do Cartão" <?=$mostrac10?> <?=$disabled?>>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
													</div>
													
													<div class="push30"></div> 				
													
													<div class="col-md-3 col-sm-1">
													</div>	
													
													<div class="col-md-6 col-sm-10">
														<a name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</a>
													</div>
													
													<div class="col-md-3">
													</div>			
													
												</div><!-- /row -->
												
											</div><!-- /container -->
											
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
											
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">

		$('#CAD').click(function(){

			var cpf = $("#c1").val(),
			    cartao = $("#c10").val();

			    if(cpf != "" || cartao != ""){

					$.ajax({
						method: 'POST',
						url: 'ajxClienteResgate.php',
						data: $('#formulario').serialize(),
						success:function(data){
							if(data.trim() != 0){
								window.location.replace("https://adm.bunker.mk/action.do?mod=<?=fnEncode(1388)?>&id=<?=fnEncode($cod_empresa)?>&idC="+data);
								console.log(data);
							}else{
								console.log(data);
								$.alert({
			                        title: "Cadastro não encontrado.",
			                        content: "O CPF informado não existe na base de clientes.",
			                        type: 'red'
			                    });
							}			
						}
					});

				}else{

					$.alert({
                        title: "Consulta não realizada.",
                        content: "O campo para consulta precisa ser preenchido!",
                        type: 'orange'
                    });

				}
		});
		
	</script>	