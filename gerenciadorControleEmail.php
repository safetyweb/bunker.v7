<?php
	require('sendinblue/Email.php');
	
	//echo fnDebug('true');
 
	$cod_conface = 0;
	$cod_empresa = 0;
	$des_authkey = "";
	$des_emailus = "";
	$des_senhaus = "";
	$nom_empresa = "";
	$des_prinome = "";
	$des_ultnome = "";
	$num_emacred = 0;
	$num_smscred = 0;
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
			
			$cod_conface = fnLimpaCampoZero($_REQUEST['COD_CONFACE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
                        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
			$des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
			$des_emailus = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
			$des_senhaus = fnLimpaCampo($_REQUEST['DES_SENHAUS']);
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
			$des_prinome = fnLimpaCampo($_REQUEST['DES_PRINOME']);
			$des_ultnome = fnLimpaCampo($_REQUEST['DES_ULTNOME']);
			$num_emacred = fnLimpaCampo($_REQUEST['NUM_EMACRED']);
			$num_smscred = fnLimpaCampo($_REQUEST['NUM_SMSCRED']);
			
			if (empty($_REQUEST['LOG_STATUS'])) {$log_status='N';}else{$log_status=$_REQUEST['LOG_STATUS'];}

			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			$array = array("");
				
			if ($opcao != ''){
				
				// if($opcao == "CAD"){
				// 	$email = new Email();
				// 	$data = array( 
				// 		"child_email" => $des_emailus,
				// 		"password" => $des_senhaus,
				// 		"company_org" => $nom_empresa,
				// 		"first_name" => $des_prinome,
				// 		"last_name" => $des_ultnome,
				// 		"credits" => array( "email_credit" => $num_emacred, "sms_credit" => 0 ),
				// 		"associate_ip" => array("213.32.185.142")
				// 	);			
				// 	$array = $email->mailin->create_child_account($data);
				// 	$des_authkey = $array['data']['auth_key'];
					
				// }else if($opcao == "ALT"){
				// 	$email = new Email();
				// 	$data = array( 
				// 		"auth_key" => $des_authkey,
				// 		"child_email" => $des_emailus,
				// 		"password" => $des_senhaus,
				// 		"company_org" => $nom_empresa,
				// 		"first_name" => $des_prinome,
				// 		"last_name" => $des_ultnome,
				// 		"credits" => array( "email_credit" => $num_emacred, "sms_credit" => 0 ),
				// 		"associate_ip" => array("213.32.185.142")
				// 	);	
				// 	$array = $email->mailin->update_child_account($data);
				// }else if($opcao == "EXC"){
				// 	$email = new Email();
				// 	$data = array( "auth_key" => $des_authkey);
				// 	$array = $email->mailin->delete_child_account($data);
				// }
				
				//echo '<pre>';
				//print_r($array);
				//echo '</pre>';
				
				// if($array['code'] == "success"){
					$sql = "CALL SP_ALTERA_CONFIGURACAO_ACESSO (
					 '".$cod_conface."', 
					 '".$cod_empresa."',
                     '".$cod_parcomu."',
					 '".$des_authkey."',
					 '".$des_emailus."', 
					 '".$des_senhaus."', 
					 '".$nom_empresa."', 
					 '".$des_prinome."', 
					 '".$des_ultnome."', 
					 '".$num_emacred."',
					 '".$num_smscred."',
					 '".$log_status."',
					 '".$opcao."'    
						);";
						
					//fnEscreve($sql);
					mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());				
					
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
				// }else{
					// $msgRetorno = 'Registro gravado com <b>sucesso</b>!';					
				// }
			}                
		}
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);
	
	//$checkLOG_STATUS = 'checked';

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
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<div class="portlet-body">
					
					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>	

					<?php 
					//menu senhas comunicação
					$abaComunica = 1243;
					include "abasSenhasComunicacao.php";					
					?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
									<input type="hidden" class="form-control input-sm" name="COD_CONFACE" id="COD_CONFACE" value="<?php echo $cod_conface; ?>"> 
									<input type="hidden" class="form-control input-sm" name="NUM_SMSCRED" id="NUM_SMSCRED" maxlength="100" value="<?php echo $num_smscred; ?>">									
													
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Comunicação Ativa</label> 
											<div class="push5"></div>
												<label class="switch">
												<input type="checkbox" name="LOG_STATUS" id="LOG_STATUS" class="switch" value="S" <?php echo $checkLOG_STATUS; ?> >
												<span></span>
												</label>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
												<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php																	
														$sql = "select COD_EMPRESA, NOM_FANTASI from EMPRESAS order by NOM_EMPRESA ";
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
														while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
														  {													
															echo"
																  <option value='".$qrListaTipoEntidade['COD_EMPRESA']."'>".$qrListaTipoEntidade['NOM_FANTASI']."</option> 
																"; 
															  }											
													?>	
												</select>	
												<script>$("#formulario #COD_EMPRESA").val("<?php echo $cod_empresa; ?>").trigger("chosen:updated"); </script>
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Parceiro Comunicação</label>
											<select data-placeholder="Selecione um parceiro" name="COD_PARCOMU" id="COD_PARCOMU" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php
												$sql = "SELECT COD_PARCOMU, DES_PARCOMU FROM PARCEIRO_COMUNICACAO WHERE COD_TPCOM = 1 ORDER BY DES_PARCOMU ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

												while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
													echo"<option value='" . $qrListaTipoEntidade['COD_PARCOMU'] . "'>" . $qrListaTipoEntidade['DES_PARCOMU'] . "</option>";
												}
												?>	
											</select>	
											<script>$("#formulario #COD_PARCOMU").val("<?php echo $cod_parcomu; ?>").trigger("chosen:updated");</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>                                                                        

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome Empresa da Revenda (ref. no parceiro)</label>
											<input type="text" class="form-control input-sm" name="NOM_EMPRESA" id="NOM_EMPRESA" maxlength="100" value="<?php echo $nom_empresa; ?>" readonly>
										</div>
										<div class="help-block with-errors"></div>
									</div> 
								</div>
								<div class="row">
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Primeiro nome revendedor</label>
											<input type="text" class="form-control input-sm" name="DES_PRINOME" id="DES_PRINOME" maxlength="100" value="<?php echo $des_prinome; ?>" readonly>
										</div>
										<div class="help-block with-errors"></div>
									</div> 
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Último nome revendedor</label>
											<input type="text" class="form-control input-sm" name="DES_ULTNOME" id="DES_ULTNOME" maxlength="100" value="<?php echo $des_ultnome; ?>" readonly>
										</div>
										<div class="help-block with-errors"></div>
									</div> 

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">E-mail Revenda</label>
											<input type="email" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" value="<?php echo $des_emailus; ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div> 	

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Senha Revenda</label>
											<input type="password" class="form-control input-sm" name="DES_SENHAUS" id="DES_SENHAUS" maxlength="100" value="<?php echo $des_senhaus; ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
								</div>
								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Qtde Crédito E-mail</label>
											<input type="text" class="form-control input-sm" name="NUM_EMACRED" id="NUM_EMACRED" maxlength="100" value="<?php echo $num_emacred; ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div> 		

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Autenticador Key</label>
											<input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="100" value="<?php echo $des_authkey; ?>" readonly>
										</div>
										<div class="help-block with-errors"></div>
									</div>									

								</div>	
								
								<div class="push10"></div>
								
						</fieldset>
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
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
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-bordered table-striped table-hover">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Empresa</th>
									  <th>Emp. Comunicação</th>
									  <th>E-mail</th>
									  <th>Qtde Crédito E-mail</th>
									  <th>Autenticador Key</th>
									  <th>Ativo</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "select CONFIGURACAO_ACESSO.*,
												 (select PC.DES_PARCOMU FROM PARCEIRO_COMUNICACAO PC where PC.COD_PARCOMU = CONFIGURACAO_ACESSO.COD_PARCOMU) as DES_PARCOMU,
												 EMPRESAS.NOM_FANTASI 
											from CONFIGURACAO_ACESSO
												left join empresas ON CONFIGURACAO_ACESSO.COD_EMPRESA = empresas.COD_EMPRESA
											 where DES_ULTNOME = 'Automation' order by COD_CONFACE";
										  
									//fnEscreve($sql);
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
									
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;

										if ($qrBuscaModulos['LOG_STATUS'] == "S") {
											$mostraLOG_STATUS = '<i class="fa fa-check" aria-hidden="true"></i>';	
										}else{ $mostraLOG_STATUS = ''; }	
										
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['NOM_FANTASI']."</td>
											  <td>".$qrBuscaModulos['DES_PARCOMU']."</td>
											  <td>".$qrBuscaModulos['DES_EMAILUS']."</td>
											  <td>".$qrBuscaModulos['NUM_EMACRED']."</td>
											  <td>".$qrBuscaModulos['DES_AUTHKEY']."</td>
											  <td class='text-center'>".$mostraLOG_STATUS."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CONFACE_".$count."' value='".$qrBuscaModulos['COD_CONFACE']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
                                            <input type='hidden' id='ret_COD_PARCOMU_".$count."' value='".$qrBuscaModulos['COD_PARCOMU']."'>
											<input type='hidden' id='ret_NOM_EMPRESA_".$count."' value='".$qrBuscaModulos['NOM_FANTASI']."'>
											<input type='hidden' id='ret_DES_EMAILUS_".$count."' value='".$qrBuscaModulos['DES_EMAILUS']."'>
											<input type='hidden' id='ret_NUM_EMACRED_".$count."' value='".$qrBuscaModulos['NUM_EMACRED']."'>
											<input type='hidden' id='ret_NUM_SMSCRED_".$count."' value='".$qrBuscaModulos['NUM_SMSCRED']."'>
											<input type='hidden' id='ret_DES_AUTHKEY_".$count."' value='".$qrBuscaModulos['DES_AUTHKEY']."'>
											<input type='hidden' id='ret_DES_PRINOME_".$count."' value='".$qrBuscaModulos['DES_PRINOME']."'>
											<input type='hidden' id='ret_DES_ULTNOME_".$count."' value='".$qrBuscaModulos['DES_ULTNOME']."'>
											<input type='hidden' id='ret_LOG_STATUS_".$count."' value='".$qrBuscaModulos['LOG_STATUS']."'>
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
	
		  $('#COD_EMPRESA').on('change', function(e) {
			$.ajax({
				type: "GET",
				url: "ajxGerenciadorSendinBlue.do",
				data: {cod_empresa: $('#COD_EMPRESA').val()},
				beforeSend:function(){
					//$('#bloco').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					var array = data.split('|');
					$('#NOM_EMPRESA').val(array[0]);
					$('#DES_ULTNOME').val("Automation");
					$('#DES_PRINOME').val(array[1]);
				},
				error:function(){
					//$('#bloco').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		  
		  });	
		  
		
		function retornaForm(index){
			$("#formulario #COD_CONFACE").val($("#ret_COD_CONFACE_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
            $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_"+index).val()).trigger("chosen:updated");
			$("#formulario #NOM_EMPRESA").val($("#ret_NOM_EMPRESA_"+index).val());
			$("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_"+index).val());
			$("#formulario #DES_EMAILUS").val($("#ret_DES_EMAILUS_"+index).val());
			$("#formulario #NUM_EMACRED").val($("#ret_NUM_EMACRED_"+index).val());
			$("#formulario #NUM_SMSCRED").val($("#ret_NUM_SMSCRED_"+index).val());
			$("#formulario #DES_PRINOME").val($("#ret_DES_PRINOME_"+index).val());
			$("#formulario #DES_ULTNOME").val($("#ret_DES_ULTNOME_"+index).val());
			
			if ($("#ret_LOG_STATUS_"+index).val() == 'S'){$('#formulario #LOG_STATUS').prop('checked', true);} 
			else {$('#formulario #LOG_STATUS').prop('checked', false);}			
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	