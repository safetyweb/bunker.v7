<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	
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
			
			$cod_database = fnLimpaCampoZero($_REQUEST['COD_DATABASE']);
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$des_database = fnLimpaCampo($_POST['DES_DATABASE']);
			
			$ip = fnLimpaCampo($_POST['IP']);
			$usuariodb = fnLimpaCampo($_POST['USUARIODB']);
			$senhadb = fnLimpaCampo(fnEncode($_POST['SENHADB']));
			$nom_database = fnLimpaCampo($_POST['NOM_DATABASE']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_TAB_DATABASE (
				 '".$cod_database."', 
				 '".$cod_servidor."', 
				 '".$des_database."', 
				 '".$cod_empresa."', 
				 '".$ip."', 
				 '".$usuariodb."', 
				 '".$senhadb."', 
				 '".$nom_database."', 
				 '".$des_observa."', 
				 '".$opcao."'    
				) ";
					
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
		$cod_database = fnDecode($_GET['id']);
		//$cod_pagina = $_GET['mod'];

		if ($cod_database != 0 ) {
		
			$sql = "select A.*,
					(select C.DES_SERVIDOR from servidores C where C.COD_SERVIDOR = A.COD_SERVIDOR ) as NOM_SERVIDOR,													
					(select B.NOM_EMPRESA from empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA													
					from tab_database A  where COD_DATABASE = '".$cod_database."'  ";										
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			$qrBuscaDatabase = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($arrayQuery)){				
				
				$cod_database = $qrBuscaDatabase['COD_DATABASE'];
				$cod_servidor = $qrBuscaDatabase['COD_SERVIDOR'];
				$des_database = $qrBuscaDatabase['DES_DATABASE'];
				$cod_empresa = $qrBuscaDatabase['COD_EMPRESA'];
				$ip = $qrBuscaDatabase['IP'];
				$usuariodb = $qrBuscaDatabase['USUARIODB'];
				$senhadb = trim(fnDecode($qrBuscaDatabase['SENHADB']));
				$nom_database = $qrBuscaDatabase['NOM_DATABASE'];
				$des_observa = $qrBuscaDatabase['DES_OBSERVA'];
			}
		} else{
			
			$cod_database = 0;
			$cod_servidor = 0;
			$des_database = "";
			$cod_empresa = 0;
			$ip = "";
			$usuariodb = "";
			$senhadb = "";
			$nom_database = "";
			$des_observa = "";
	}
												
	}else {
		$cod_database = 0;
		$cod_servidor = 0;
		$des_database = "";
		$cod_empresa = 0;
		$ip = "";
		$usuariodb = "";
		$senhadb = "";
		$nom_database = "";
		$des_observa = "";
		//fnEscreve('entrou else');
	}      
	//fnMostraForm();
	//fnEscreve($cod_database);
	
?>
					<?php if ($popUp != "true"){ ?>
						<div class="push30"></div> 
					<?php } ?>
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet <?php echo $tipoPortlet; ?>">
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
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=<?php echo fnEncode(1027)."&id=".fnEncode($cod_database)."&pop=true"; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura"  name="COD_DATABASE" id="COD_DATABASE" value="<?php echo $cod_database; ?>">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Servidor</label>
																<select data-placeholder="Selecione um servidor" name="COD_SERVIDOR" id="COD_SERVIDOR" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<?php																	
																		$sql = "select COD_SERVIDOR, DES_SERVIDOR from servidores order by des_servidor ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaServidor = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaServidor['COD_SERVIDOR']."'>".$qrListaServidor['DES_SERVIDOR']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
																<script>$("#formulario #COD_SERVIDOR").val("<?php echo $cod_servidor; ?>").trigger("chosen:updated"); </script>	
																<div class="help-block with-errors"></div>																
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
																<select data-placeholder="Selecione um servidor" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<?php																	
																		$sql = "select COD_EMPRESA, NOM_FANTASI from empresas where cod_empresa <> 1 order by NOM_FANTASI ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaEmpresa['COD_EMPRESA']."'>".$qrListaEmpresa['NOM_FANTASI']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
																<script>$("#formulario #COD_EMPRESA").val("<?php echo $cod_empresa; ?>").trigger("chosen:updated"); </script>	
																<div class="help-block with-errors"></div>																
														</div>
													</div>
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Descrição</label>
															<input type="text" class="form-control input-sm" name="DES_DATABASE" id="DES_DATABASE" maxlength="20" value="<?php echo $des_database; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>													
												
												<div class="row">													
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Database</label>
															<input type="text" class="form-control input-sm" name="NOM_DATABASE" id="NOM_DATABASE" maxlength="20" value="<?php echo $nom_database; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Usuário</label>
															<input type="text" class="form-control input-sm" name="USUARIODB" id="USUARIODB" maxlength="20" value="<?php echo $usuariodb; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">IP</label>
															<input type="text" class="form-control input-sm" name="IP" id="IP" maxlength="20" value="<?php echo $ip; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Senha</label>
															<input type="text" class="form-control input-sm" name="SENHADB" id="SENHADB" maxlength="100"  value="<?php echo $senhadb; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
																		
													
												</div>													
												
												<div class="row">													
													
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Observações</label>
																<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="500"><?php echo $des_observa; ?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>														
													
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<?php if ($cod_database == "0") { ?>	
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											<?php } else {?>											  
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											<?php } ?>						
										
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
																			
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		$(document).ready(function(){
		   $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk'
		   $('#formulario').validator();

		});	
		
		function retornaForm(index){
			$("#formulario #COD_DATABASE").val($("#ret_COD_DATABASE_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_DATABASE").val($("#ret_DES_DATABASE_"+index).val());
			$("#formulario #IP").val($("#ret_IP_"+index).val());
			$("#formulario #USUARIODB").val($("#ret_USUARIODB_"+index).val());
			$("#formulario #SENHADB").val($("#ret_SENHADB_"+index).val());
			$("#formulario #NOM_DATABASE").val($("#ret_NOM_DATABASE_"+index).val());
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	