	
<?php

	//echo fnDebug('true');
	
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
			
			$cod_usuario = fnLimpacampoZero($_REQUEST['COD_USUARIO']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$nom_usuario = fnLimpacampo($_REQUEST['NOM_USUARIO']);
			$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
			$num_rgpesso = fnLimpacampo($_REQUEST['NUM_RGPESSO']);
			$dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
			$cod_estaciv = fnLimpacampoZero($_REQUEST['COD_ESTACIV']);
			$cod_sexopes = fnLimpacampoZero($_REQUEST['COD_SEXOPES']);
			$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
	

			//fnEscreve($cod_perfils);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
	          			
			if ($opcao != ''){
                           
				$sql = "CALL SP_ALTERA_DADOS_USUARIOS (
				 '".$cod_usuario."', 
				 '".$nom_usuario."', 
				 '".$num_cgcecpf."', 
				 '".$num_rgpesso."', 
				 '".$num_telefon."', 
				 '".$num_celular."', 				 
				 '".$des_emailus."', 
				 '".fnDataSql($dat_nascime)."', 
				 '".$cod_estaciv."', 
				 '".$cod_sexopes."', 
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
	
	//busca dados da empresa - session usuário
	$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];	
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, DES_SUFIXO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		 
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaEmpresa)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
	}
	
	//busca dados do usuário - session usuário											
	$sql = "select * from usuarios where COD_EMPRESA = '".$_SESSION["SYS_COD_EMPRESA"]."' and COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' and DAT_EXCLUSA is null ";
	
	//fnTestesql($connAdm->connAdm(),$sql);
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrDadosUsuario = mysqli_fetch_assoc($arrayQuery);

	$loginLimpo =  str_replace('.'.$des_sufixo, '', $qrDadosUsuario['LOG_USUARIO']);

	$cod_usuario = $qrDadosUsuario['COD_USUARIO'];
	$nom_usuario = $qrDadosUsuario['NOM_USUARIO'];
	$log_usuario = $qrDadosUsuario['LOG_USUARIO'];
	$des_emailus = $qrDadosUsuario['DES_EMAILUS'];
	$nom_usuario = $qrDadosUsuario['NOM_USUARIO'];

	$log_estatus = $qrDadosUsuario['LOG_ESTATUS'];
	$des_emailus = $qrDadosUsuario['DES_EMAILUS'];
	$num_cgcecpf = $qrDadosUsuario['NUM_CGCECPF'];
	$num_rgpesso = $qrDadosUsuario['NUM_RGPESSO'];

	$dat_nascime = $qrDadosUsuario['DAT_NASCIME'];
	$cod_estaciv = $qrDadosUsuario['COD_ESTACIV'];
	$cod_sexopes = $qrDadosUsuario['COD_SEXOPES'];

	$num_telefon = $qrDadosUsuario['NUM_TELEFON'];
	$num_celular = $qrDadosUsuario['NUM_CELULAR'];
	
											
	//fnMostraForm();
	//fnEscreve($cod_univend);
		
?>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
									</div>
									
									<?php 									
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
																			 
													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Usuário Ativo</label> 
															<div class="push5"></div>
																<div class="disabledBlock"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
																														
												</div>
											
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
														</div>														
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Usuário</label>
															<input type="text" class="form-control input-sm" name="NOM_USUARIO" id="NOM_USUARIO" maxlength="50" data-error="Campo obrigatório" value="<?php echo $nom_usuario; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Login Usuário</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LOG_USUARIO" id="LOG_USUARIO" maxlength="50" data-error="Campo obrigatório" value="<?php echo $loginLimpo; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">						
														<div class="form-group">
															<label for="inputName" class="control-label">Controle Login (sufixo)</label>
															<h4>.<?php echo $des_sufixo; ?></h4>
														</div>
													</div>
																				
												</div>
												
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">CNPJ/CPF</label>
															<input type="text" class="form-control input-sm cpf" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório" value="<?php echo $num_cgcecpf; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">RG</label>
															<input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" maxlength="15" data-error="Campo obrigatório" value="<?php echo $num_rgpesso; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Principal</label>
															<input type="text" class="form-control input-sm cel" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20" value="<?php echo $num_telefon; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Celular</label>
															<input type="text" class="form-control input-sm cel" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20" value="<?php echo $num_celular; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
																	
												</div>
												
												<div class="row">									
														
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">e-Mail</label>
															<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" value="<?php echo $des_emailus; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Data de Nacimento</label>
															<input type="text" class="form-control input-sm data" name="DAT_NASCIME" id="DAT_NASCIME" maxlength="10" value="<?php echo $dat_nascime; ?>" >
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Estado Civil</label>
																<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php																	
																		$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaEstCivil['COD_ESTACIV']."'>".$qrListaEstCivil['DES_ESTACIV']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<script>$("#formulario #COD_ESTACIV").val("<?php echo $cod_estaciv; ?>").trigger("chosen:updated"); </script>			
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Sexo</label>
																<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php 																	
																		$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaSexo['COD_SEXOPES']."'>".$qrListaSexo['DES_SEXOPES']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<script>$("#formulario #COD_SEXOPES").val("<?php echo $cod_sexopes; ?>").trigger("chosen:updated"); </script>
															<div class="help-block with-errors"></div>
														</div>
													</div>											
										
													
												</div>
																																		
												
										</fieldset>	
												
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">

											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button>

										</div>
										
										<input type="hidden" name="DES_SUFIXO" id="DES_SUFIXO" value="<?php echo $des_sufixo; ?>">
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
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});	
				
		function retornaForm(index){
			$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #DAT_CADASTR").val($("#ret_DAT_CADASTR_"+index).val());
			$("#formulario #NOM_USUARIO").val($("#ret_NOM_USUARIO_"+index).val());
			$("#formulario #DES_SENHAUS").val($("#ret_DES_SENHAUS_"+index).val());
			$("#formulario #LOG_USUARIO").val($("#ret_LOG_USUARIO_"+index).val());
			if ($("#ret_LOG_ESTATUS_"+index).val() == 'S'){$('#formulario #LOG_ESTATUS').prop('checked', true);} 
			else {$('#formulario #LOG_ESTATUS').prop('checked', false);}
			$("#formulario #DES_EMAILUS").val($("#ret_DES_EMAILUS_"+index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_"+index).val());				
			$("#formulario #NUM_RGPESSO").val($("#ret_NUM_RGPESSO_"+index).val());				
			$("#formulario #DAT_NASCIME").val($("#ret_DAT_NASCIME_"+index).val());
			$("#formulario #NUM_TENTATI").val($("#ret_NUM_TENTATI_"+index).val());
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_"+index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_"+index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_"+index).val());			
			$("#formulario #COD_ESTACIV").val($("#ret_COD_ESTACIV_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEXOPES").val($("#ret_COD_SEXOPES_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_TPUSUARIO").val($("#ret_COD_TPUSUARIO_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_DEFSIST").val($("#ret_COD_DEFSIST_"+index).val()).trigger("chosen:updated");
			
			//retorno combo multiplo - perfil
			$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");
			if ($("#ret_TEM_PERFIL_"+index).val() == "sim" ){
				
				var sistemasCli = $("#ret_COD_PERFILS_"+index).val();				
				var sistemasCliArr = sistemasCli.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasCliArr.length; i++) {
				  $("#formulario #COD_PERFILS option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERFILS").trigger("chosen:updated");    
			} else {$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");}
			
			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");			
			if ($("#ret_TEM_UNIVE_"+index).val() == "sim" ){
				var sistemasUni = $("#ret_COD_UNIVEND_"+index).val();				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_UNIVEND").trigger("chosen:updated");    
			} else {$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");}
			
			//retorno combo multiplo - master
			$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
			if ($("#ret_TEM_MASTER_"+index).val() == "sim" ){
				//alert("entrou...");
				var sistemasMst = $("#ret_COD_MULTEMP_"+index).val();				
				var sistemasMstArr = sistemasMst.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasMstArr.length; i++) {
				  $("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_MULTEMP").trigger("chosen:updated");    
			} else {$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
				
	</script>	