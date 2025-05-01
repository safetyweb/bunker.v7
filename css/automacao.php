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
	
	//fnMostraForm();

?>

<style>

	.update-nag{
	  display: inline-block;
	  font-size: 16px;
	  text-align: left;
	  background-color: #fff;
	  height: 50px;
	  -webkit-box-shadow: 1px 2px 2px 1px rgba(0,0,0,.2);
	  box-shadow: 1px 2px 2px 1px rgba(0,0,0,.1);
	  margin-bottom: 20px;
	  border: 1px solid #F2F3F4;
	  border-radius: 5px;
	}

	.update-nag:hover{
		cursor: pointer;
-webkit-box-shadow: 3px 3px 21px 0px rgba(50, 50, 50, 0.54);
-moz-box-shadow:    3px 3px 21px 0px rgba(50, 50, 50, 0.54);
box-shadow:         3px 3px 21px 0px rgba(50, 50, 50, 0.54);
	}

	.update-nag > .update-split{
	  background: #337ab7; 
	  width: 63px;
	  float: left;
	  color: #fff!important;
	  height: 100%;
	  text-align: center;
	  border-radius: 5px 0 0 5px; 
	}

	.update-nag > .update-split > .glyphicon{
	  position:relative;
	  top: calc(50% - 9px)!important; /* 50% - 3/4 of icon height */
	}
	.update-nag > .update-split.update-success{
	  background: #48C9B0!important;
	}

	.update-nag > .update-split.update-danger{
	  background: #EC7063!important;
	}

	.update-nag > .update-split.update-info{
	  background: #F4D03F!important;
	}
	
	.update-nag > .update-text{
	  line-height: 19px;
	  padding-top: 15px;
	  padding-left: 85px;
	  padding-right: 20px;
	}

</style>
			
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
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									
									<div class="push10"></div> 
									
									<ul class="nav nav-tabs">
										<li><a href="action.php?mod=<?php echo fnEncode(1020)."&id=".fnEncode($cod_empresa); ?>">Empresa</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1017)."&id=".fnEncode($cod_empresa); ?>">Usuários</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1025)."&id=".fnEncode($cod_empresa); ?>">Grupo de Trabalho</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1023)."&id=".fnEncode($cod_empresa); ?>">Unidades</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1024)."&id=".fnEncode($cod_empresa); ?>">Clientes</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1018)."&id=".fnEncode($cod_empresa); ?>">Perfil</a></li>
										<li class="active"><a href="action.php?mod=<?php echo fnEncode(1021)."&id=".fnEncode($cod_empresa); ?>">Automação</a></li>
										<li><a href="action.php?mod=<?php echo fnEncode(1022)."&id=".fnEncode($cod_empresa); ?>">Modelo de Negócio</a></li>
									</ul>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
														</div>														
													</div>
																				
												</div>
												
												<div class="push50"></div>
												
												<div class="row">
												
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-success"><i class="glyphicon fa fa-database fa-lg" aria-hidden="true"></i></div>
														<div class="update-text">Database criado com sucesso </div>
													  </div>
													</div>
													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-success"><i class="glyphicon fa fa-cogs fa-lg" aria-hidden="true"></i></i></div>
														<div class="update-text">Acesso ao sistema criado com sucesso</div>
													  </div>
													</div>	
													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-success"><i class="glyphicon fa fa-unlock-alt fa-lg" aria-hidden="true"></i></i></div>
														<div class="update-text">Perfil master criado com sucesso</div>
													  </div>
													</div>
													
													<!--													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split"><i class="glyphicon glyphicon-refresh"></i></div>
														<div class="update-text">Cms v0.2.5 is available! <a href="#">Update Now</a> </div>
													  </div>
													</div>
													-->
													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-danger"><i class="glyphicon fa fa-user-plus fa-lg"></i></div>
														<div class="update-text">Falha na criação do usuário master &nbsp;&nbsp;<a href="#">Processar novamente</a></div>
													  </div>
													</div>	
													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-danger"><i class="glyphicon fa fa-users fa-lg"></i></div>
														<div class="update-text">Falha na criação de grupo default &nbsp;&nbsp;<a href="#">Processar novamente</a></div>
													  </div>
													</div>

													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-info"><i class="glyphicon fa fa-street-view fa-lg"></i></div>
														<div class="update-text">Nenhuma unidade de atendimento cadastrada &nbsp;&nbsp;<a href="#">Ir para página</a> </div>
													  </div>
													</div>

													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-info"><i class="glyphicon fa fa-cart-plus fa-lg"></i></div>
														<div class="update-text">Nenhuma regra de pontuação adicional criada &nbsp;&nbsp;<a href="#">Ir para página</a> </div>
													  </div>
													</div>
													
													<div class="col-md-12">
													  <div class="update-nag">
														<div class="update-split update-info"><i class="glyphicon fa fa-ticket fa-lg"></i></div>
														<div class="update-text">Ticket de ofertas não configurado &nbsp;&nbsp;<a href="#">Ir para página</a> </div>
													  </div>
													</div>
													
												</div>												
												
												<div class="push100"></div>
												
										</fieldset>	
				
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
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