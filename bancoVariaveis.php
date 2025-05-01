<?php
	
	//echo fnDebug('true');

	include "_system/func_dinamiza/Function_dinamiza.php";
	
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

			$cod_bancovar = fnLimpaCampoZero($_REQUEST['COD_BANCOVAR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$cod_listvar = fnLimpaCampo($_REQUEST['COD_LISTVAR']);
			$nom_bancovar = fnLimpaCampo($_REQUEST['NOM_BANCOVAR']);
			$abv_bancovar = fnLimpaCampo($_REQUEST['ABV_BANCOVAR']);
			$key_bancovar = $_REQUEST['KEY_BANCOVAR'];
			$des_bancovar = fnLimpaCampo($_REQUEST['DES_BANCOVAR']);
			$num_tamsms = fnLimpaCampo($_REQUEST['NUM_TAMSMS']);
			$num_ordenac = fnLimpaCampo($_REQUEST['NUM_ORDENAC']);
			
			if (empty($_REQUEST['LOG_EMAIL'])) {$log_email='N';}else{$log_email=$_REQUEST['LOG_EMAIL'];}
			if (empty($_REQUEST['LOG_SMS'])) {$log_sms='N';}else{$log_sms=$_REQUEST['LOG_SMS'];}
			if (empty($_REQUEST['LOG_PUSH'])) {$log_push='N';}else{$log_push=$_REQUEST['LOG_PUSH'];}
			if (empty($_REQUEST['LOG_WHATSAPP'])) {$log_whatsapp='N';}else{$log_whatsapp=$_REQUEST['LOG_WHATSAPP'];}
					
			$opcao = $_REQUEST['opcao'];
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				if($opcao == 'CAD'){
					$cod_bancovar = 0;
				}
			
				$sql = "CALL SP_ALTERA_VARIAVEIS (
				 '".$cod_bancovar."', 
				 '".$cod_empresa."', 
				 '".$cod_listvar."', 
				 '".$nom_bancovar."', 
				 '".$abv_bancovar."', 
				 '".$key_bancovar."', 
				 '".$des_bancovar."', 
				 '".$num_tamsms."', 
				 '".$log_email."', 
				 '".$log_sms."', 
				 '".$log_push."', 
				 '".$log_whatsapp."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				//fntestesql($connAdm->connAdm(),trim($sql));
				//fnEscreve($sql);
				mysqli_query($connAdm->connAdm(),trim($sql));

				if($log_email == 'S'){

					include "autenticaDinamize.php";
					// retorna $_SESSION[COD_LISTA] E $_SESSION[AUTH_DINAMIZE]

					$retornoLista = ListaVariavel($key_bancovar,$_SESSION['AUTH_DINAMIZE'], $_SESSION[COD_LISTA]);

					// fnEscreve($key_bancovar);
					// fnEscreve($_SESSION['AUTH_DINAMIZE']);

				}


				// echo "<pre>";
				// print_r($retornoLista);
				// echo "</pre>";
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

							$sqlCod = "SELECT COD_BANCOVAR 
									   FROM VARIAVEIS
									   WHERE COD_EMPRESA = $cod_empresa
									   ORDER BY 1 DESC 
									   LIMIT 1";

							// fnEscreve($sqlCod);

							$arrayCod = mysqli_query($connAdm->connAdm(),trim($sqlCod));
							$qrCod = mysqli_fetch_assoc($arrayCod);

							$cod_bancovar = $qrCod[COD_BANCOVAR];

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

				if($log_email == 'S'){

					if($opcao != 'EXC'){

						if($retornoLista['body']['itens'][0]['code'] == ""){

							$retornoAdd = AddVariavel($key_bancovar, 'VC', $_SESSION['AUTH_DINAMIZE'], $_SESSION[COD_LISTA]);
							// fnEscreve('add');

							// echo "<pre>";
							// print_r($retornoAdd);
							// echo "</pre>";

							$cod_externo = $retornoAdd['body']['code'];							

								$sql = "UPDATE VARIAVEIS_DINAMIZE SET
										COD_EXTERNO = $cod_externo,
										DES_EXTERNO = '{{cmp$cod_externo}}'
										WHERE COD_EMPRESA = $cod_empresa 
										AND COD_BANCOVAR = $cod_bancovar";

								// fnEscreve($sql);
								mysqli_query($connAdm->connAdm(),trim($sql));

						}else{

							$sqlCod = "SELECT COD_EXTERNO 
									   FROM VARIAVEIS_DINAMIZE
									   WHERE COD_EMPRESA = $cod_empresa
									   AND COD_BANCOVAR = $cod_bancovar";

							$arrayCod = mysqli_query($connAdm->connAdm(),trim($sqlCod));
							$qrCod = mysqli_fetch_assoc($arrayCod);

							$cod_externo = $qrCod['COD_EXTERNO'];

							$retornoAtt = AtualizaVariavel($_SESSION['AUTH_DINAMIZE'], $cod_externo ,$key_bancovar, $_SESSION[COD_LISTA]);

							// fnEscreve('att');

							// echo "<pre>";
							// print_r($retornoAtt);
							// echo "</pre>";

						}

						$sqlCount = "SELECT COD_VARIAVEL FROM VARIAVEIS_DINAMIZE 
									WHERE COD_BANCOVAR = $cod_bancovar 
									AND COD_EMPRESA = $cod_empresa";

						$qrCount = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sqlCount)));

						if($qrCount['COD_VARIAVEL'] != ""){

							$sql = "INSERT INTO VARIAVEIS_DINAMIZE(
													COD_EMPRESA,
													COD_BANCOVAR,
													COD_EXTERNO,
													DES_EXTERNO,
													COD_USUCADA
												) VALUES(
												   	$cod_empresa,
												   	$cod_bancovar,
												   	$cod_externo,
												   	'{{cmp$cod_externo}}',
												   	$cod_usucada
												)";

							// fnEscreve($sql);

							mysqli_query($connAdm->connAdm(),trim($sql));

						}

					}

				}

				$msgTipo = 'alert-success';
				
			}  
			

		}
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
									<?php include "atalhosPortlet.php"; ?>
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
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BANCOVAR" id="COD_BANCOVAR" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
																<select data-placeholder="Selecione a empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" required >
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_EMPRESA, NOM_FANTASI from empresas where LOG_ATIVO = 'S' order by NOM_FANTASI";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																	
																		while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery))
																		  {	
																	  
																			echo"
																				  <option value='".$qrListaIntegradora['COD_EMPRESA']."'>".$qrListaIntegradora['NOM_FANTASI']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Lista</label>
																<select data-placeholder="Selecione a lista" name="COD_LISTVAR" id="COD_LISTVAR" class="chosen-select-deselect requiredChk" required >
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select * from LISTAVAR order by DES_LISTAVAR";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaVar = mysqli_fetch_assoc($arrayQuery))
																		  {	
																	  
																			echo"
																				  <option value='".$qrListaVar['COD_LISTAVAR']."'>".$qrListaVar['DES_LISTAVAR']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Campo</label>
															<input type="text" class="form-control input-sm" name="NOM_BANCOVAR" id="NOM_BANCOVAR" maxlength="30" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="push10"></div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Abreviação do Campo</label>
															<input type="text" class="form-control input-sm" name="ABV_BANCOVAR" id="ABV_BANCOVAR" maxlength="30" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Chave do Campo </label>
															<input type="text" class="form-control input-sm" name="KEY_BANCOVAR" id="KEY_BANCOVAR" maxlength="30" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tabela X Campo </label>
															<input type="text" class="form-control input-sm" name="DES_BANCOVAR" id="DES_BANCOVAR" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tamanho (SMS) </label>
															<input type="text" class="form-control input-sm" name="NUM_TAMSMS" id="NUM_TAMSMS" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">e-Mail</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?php echo $checkLOG_EMAIL; ?> >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Sms</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?php echo $checkLOG_SMS; ?> >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Push</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_PUSH" id="LOG_PUSH" class="switch" value="S" <?php echo $checkLOG_PUSH; ?> >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Whats</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_WHATSAPP" id="LOG_WHATSAPP" class="switch" value="S" <?php echo $checkLOG_WHATSAPP; ?> >
																<span></span>
																</label>
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

										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">
										
										<div id="divId_sub">
										</div>

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Lista</th>
													  <th>Descrição</th>
													  <th>Chave</th>
													  <th>Tamanho (SMS)</th>
													  <th>e-Mail</th>
													  <th>Sms</th>
													  <th>Push</th>
													  <th>Whats</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select DES_LISTAVAR,VARIAVEIS.* from VARIAVEIS
															LEFT JOIN LISTAVAR A ON A.COD_LISTAVAR = VARIAVEIS.COD_LISTVAR
															order by NUM_ORDENAC ";
															
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery))
													  {	
														$count++;

														  if ($qrBuscaFases['LOG_EMAIL'] == 'S'){		
																$mostraLOG_EMAIL = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraLOG_EMAIL = ' '; }

														  if ($qrBuscaFases['LOG_SMS'] == 'S'){		
																$mostraLOG_SMS = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraLOG_SMS = ' '; }	
															
														  if ($qrBuscaFases['LOG_PUSH'] == 'S'){		
																$mostraLOG_PUSH = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraLOG_PUSH = ' '; }
															
														  if ($qrBuscaFases['LOG_WHATSAPP'] == 'S'){		
																$mostraLOG_WHATSAPP = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraLOG_WHATSAPP = ' '; }									

														
														echo"
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='".$qrBuscaFases['COD_BANCOVAR']."'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaFases['COD_BANCOVAR']."</td>
															  <td>".$qrBuscaFases['DES_LISTAVAR']."</td>
															  <td>".$qrBuscaFases['ABV_BANCOVAR']."</td>
															  <td>".$qrBuscaFases['KEY_BANCOVAR']."</td>
															  <td class='text-center'>".$qrBuscaFases['NUM_TAMSMS']."</td>
															  <td class='text-center'>".$mostraLOG_EMAIL."</td>
															  <td class='text-center'>".$mostraLOG_SMS."</td>
															  <td class='text-center'>".$mostraLOG_PUSH."</td>
															  <td class='text-center'>".$mostraLOG_WHATSAPP."</td>
															</tr>
															<input type='hidden' id='ret_COD_BANCOVAR_".$count."' value='".$qrBuscaFases['COD_BANCOVAR']."'>
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaFases['COD_EMPRESA']."'>
															<input type='hidden' id='ret_COD_LISTVAR_".$count."' value='".$qrBuscaFases['COD_LISTVAR']."'>
															<input type='hidden' id='ret_NOM_BANCOVAR_".$count."' value='".$qrBuscaFases['NOM_BANCOVAR']."'>
															<input type='hidden' id='ret_ABV_BANCOVAR_".$count."' value='".$qrBuscaFases['ABV_BANCOVAR']."'>
															<input type='hidden' id='ret_KEY_BANCOVAR_".$count."' value='".$qrBuscaFases['KEY_BANCOVAR']."'>
															<input type='hidden' id='ret_DES_BANCOVAR_".$count."' value='".$qrBuscaFases['DES_BANCOVAR']."'>
															<input type='hidden' id='ret_NUM_TAMSMS_".$count."' value='".$qrBuscaFases['NUM_TAMSMS']."'>
															<input type='hidden' id='ret_NUM_ORDENAC_".$count."' value='".$qrBuscaFases['NUM_ORDENAC']."'>
															
															<input type='hidden' id='ret_LOG_EMAIL_".$count."' value='".$qrBuscaFases['LOG_EMAIL']."'>
															<input type='hidden' id='ret_LOG_SMS_".$count."' value='".$qrBuscaFases['LOG_SMS']."'>
															<input type='hidden' id='ret_LOG_PUSH_".$count."' value='".$qrBuscaFases['LOG_PUSH']."'>
															<input type='hidden' id='ret_LOG_WHATSAPP_".$count."' value='".$qrBuscaFases['LOG_WHATSAPP']."'>
															
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
	
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
 	<script>
		$(function() {
			
			$( ".table-sortable tbody" ).sortable();
				
            $('.table-sortable tbody').sortable({
                handle: 'span'
            });

		   $(".table-sortable tbody").sortable({
		   
					stop: function(event, ui) {
						
						var Ids = "";
						$('table tr').each(function( index ) {
							if(index != 0){
									Ids =  Ids + $(this).children().find('span.glyphicon').attr('data-id') +",";
									//console.log($(this).children().find('span.glyphicon'));
							}
						});
						
						//update ordenação
						//console.log(Ids.substring(0,(Ids.length-1)));
						
						var arrayOrdem = Ids.substring(0,(Ids.length-1));
						//alert(arrayOrdem);
						execOrdenacao(arrayOrdem,11);
					
						
					
						function execOrdenacao(p1,p2) {
							//alert(p1);
							$.ajax({
								type: "GET",
								url: "ajxOrdenacao.php",
								data: { ajx1:p1,ajx2:p2},
								beforeSend:function(){
									//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
								},
								success:function(data){
									//$("#divId_sub").html(data); 
								},
								error:function(){
									$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
								}
							});		
						}
						
					}
					
			});
					

			$( ".table-sortable tbody" ).disableSelection();		
			
		});
	</script>
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});	
				
		function retornaForm(index){
			$("#formulario #COD_BANCOVAR").val($("#ret_COD_BANCOVAR_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_LISTVAR").val($("#ret_COD_LISTVAR_"+index).val()).trigger("chosen:updated");
			$("#formulario #NOM_BANCOVAR").val($("#ret_NOM_BANCOVAR_"+index).val());
			$("#formulario #ABV_BANCOVAR").val($("#ret_ABV_BANCOVAR_"+index).val());
			$("#formulario #KEY_BANCOVAR").val($("#ret_KEY_BANCOVAR_"+index).val());
			$("#formulario #DES_BANCOVAR").val($("#ret_DES_BANCOVAR_"+index).val());
			$("#formulario #NUM_TAMSMS").val($("#ret_NUM_TAMSMS_"+index).val());
			$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_"+index).val());
	
			if ($("#ret_LOG_EMAIL_"+index).val() == 'S'){$('#formulario #LOG_EMAIL').prop('checked', true);} 
			else {$('#formulario #LOG_EMAIL').prop('checked', false);}			
			
			if ($("#ret_LOG_SMS_"+index).val() == 'S'){$('#formulario #LOG_SMS').prop('checked', true);} 
			else {$('#formulario #LOG_SMS').prop('checked', false);}
						
			if ($("#ret_LOG_PUSH_"+index).val() == 'S'){$('#formulario #LOG_PUSH').prop('checked', true);} 
			else {$('#formulario #LOG_PUSH').prop('checked', false);}			
								
			if ($("#ret_LOG_WHATSAPP_"+index).val() == 'S'){$('#formulario #LOG_WHATSAPP').prop('checked', true);} 
			else {$('#formulario #LOG_WHATSAPP').prop('checked', false);}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   