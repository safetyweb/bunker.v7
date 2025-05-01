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
			
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
			$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_SERVIDORES (
				 '".$cod_servidor."', 
				 '".$des_servidor."', 
				 '".$des_abrevia."', 
				 '".$cod_operacional."', 
				 '".$des_geral."', 
				 '".$des_observa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				//fnEscreve($cod_submenus);
	
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
		$cod_servidor = fnDecode($_GET['id']);
		//$cod_pagina = $_GET['mod'];

		if ($cod_servidor != 0 ) {
		
			$sql = "select * from servidores where COD_SERVIDOR = '".$cod_servidor."' ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($arrayQuery)){
				$cod_servidor = $qrBuscaEmpresa['COD_SERVIDOR'];
				$des_servidor = $qrBuscaEmpresa['DES_SERVIDOR'];
				$des_abrevia = $qrBuscaEmpresa['DES_ABREVIA'];
				$des_geral = $qrBuscaEmpresa['DES_GERAL'];
				$cod_operacional = $qrBuscaEmpresa['COD_OPERACIONAL'];
				$des_observa = $qrBuscaEmpresa['DES_OBSERVA'];	
			}
		} else{
			
			$cod_servidor = 0;
			$des_servidor = "";
			$des_abrevia = "";
			$des_geral = "";
			$cod_operacional = 0;
			$des_observa = "";	
		}
												
	}else {
		
		$cod_servidor = 0;
		$des_servidor = "";
		$des_abrevia = "";
		$des_geral = "";
		$cod_operacional = 0;
		$des_observa = "";	
		//fnEscreve('entrou else');
	}      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

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
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=<?php echo fnEncode(1028)."&id=".fnEncode($cod_servidor)."&pop=true"; ?>">
																				
										<fieldset>
											<legend>Dados Gerais </legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura"  name="COD_SERVIDOR" id="COD_SERVIDOR" value="<?php echo $cod_servidor; ?>">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Servidores</label>
															<input type="text" class="form-control input-sm" name="DES_SERVIDOR" id="DES_SERVIDOR" maxlength="50" value="<?php echo $des_servidor; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Abrev. do Servidor</label>
															<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="20" value="<?php echo $des_abrevia; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Overview</label>
															<input type="text" class="form-control input-sm" name="DES_GERAL" id="DES_GERAL" maxlength="100"  value="<?php echo $des_geral; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Sistema Operacional</label>
																<select data-placeholder="Selecione um sistema operacional" name="COD_OPERACIONAL" id="COD_OPERACIONAL" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php																	
																		$sql = "select COD_OPERACIONAL, DES_OPERACIONAL from sistemaOperacional order by DES_OPERACIONAL ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaOperacional = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaOperacional['COD_OPERACIONAL']."'>".$qrListaOperacional['DES_OPERACIONAL']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
																<script>$("#formulario #COD_OPERACIONAL").val("<?php echo $cod_operacional; ?>").trigger("chosen:updated"); </script>																
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="push5"></div>
													
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
											<?php if ($cod_servidor == "0") { ?>	
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
	
		$(document).ready(function(){
			

		});		
	
		function retornaForm(index){
			$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val());
			$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_"+index).val());
			$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	