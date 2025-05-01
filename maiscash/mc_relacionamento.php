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

				//fnMostraForm();
				
				//echo $sql;
				
				//mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	$sql = "DELETE FROM IMPORT_BLACKLIST WHERE COD_EMPRESA = $cod_empresa";
	mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());

	//fnEscreve($cod_empresa);

?>

<style type="text/css">
	body{
		overflow: hidden;
	}
</style>
			
					<!-- <div class="push30"></div>  -->
					
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
									
									<!-- <div class="push30"></div> -->

			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
											<fieldset>
												<legend>Dados Gerais</legend> 
												
												<div class="row">

													<div class="col-md-12">
														<p><b class="f18">Cadastro</b> - Mensagem a ser enviada no momento de cadastramento do cliente</p>
														<p>(Ao setar a caixa ativa o envio da mensagem)</p>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">SMS</label>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">Email</label>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-12">
														<p><b class="f18">Vencimento do crédito = Recuperação de clientes</b></p>
														<p><b class="f18">inativos</b> - Mensagem a ser enviada no momento de cadastramento do cliente</p>
														<p>(Ao setar a caixa ativa o envio da mensagem)</p>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">SMS</label>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">Email</label>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-12">
														<p><b class="f18">Vender mais para aniversariantes</b> - Mensagem a ser enviada no momento de cadastramento do cliente</p>
														<p>(Ao setar a caixa ativa o envio da mensagem)</p>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">SMS</label>
														</div>
													</div>

												</div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<input type="checkbox">
															<label for="inputName" class="control-label">Email</label>
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
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
											
											<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
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
									<div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
                                    </div>
								</div>		
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->				
						
					<div class="push20"></div> 
	
	<script type="text/javascript">

		parent.$("#conteudoAba3").css("height", ($(".portlet").height()+50) + "px");
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

	</script>	