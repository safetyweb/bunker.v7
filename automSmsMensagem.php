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
			
			$cod_mensagem = fnLimpaCampoZero($_REQUEST['COD_MENSAGEM']);
			$cod_template_sms = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE_SMS']);
			$cod_template_bloco = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE_BLOCO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
                      
			if ($opcao != ''){

				$sqlLog = "SELECT 
								CASE 
									WHEN (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_template_bloco) = (SELECT MIN(NUM_ORDENAC) FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_CAMPANHA = $cod_campanha AND COD_BLTEMPL = 25) 
									THEN 'S' 
									ELSE 'N' 
								END 
						  AS LOG_PRINCIPAL";

				// fnescreve($sqlLog);
				$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLog));

				$log_principal = $qrLog['LOG_PRINCIPAL'];
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO MENSAGEM_SMS(
												COD_TEMPLATE_SMS,
												COD_TEMPLATE_BLOCO,
												COD_EMPRESA,
												COD_CAMPANHA,
												NUM_ORDENAC,
												LOG_PRINCIPAL,
												COD_USUCADA
											) VALUES(
												$cod_template_sms,
												$cod_template_bloco,
												$cod_empresa,
												$cod_campanha,
												(SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_template_bloco),
												'$log_principal',
												$cod_usucada
											  )";
							
						// fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE MENSAGEM_SMS SET
												COD_TEMPLATE_SMS = $cod_template_sms,
												LOG_PRINCIPAL = '$log_principal'
								WHERE COD_EMPRESA = $cod_empresa AND COD_MENSAGEM = $cod_mensagem";
							
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			

				?>
					<script>
						parent.mudaAba(parent.$('#conteudoAba').attr('src')+"&rnd="+Math.random());
					</script>
				<?php
				
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_template_bloco = fnDecode($_GET['idt']);	
		$cod_campanha = fnDecode($_GET['idc']);	

		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	$sql = "SELECT COD_MENSAGEM, COD_TEMPLATE_SMS FROM MENSAGEM_SMS 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha 
			AND COD_TEMPLATE_BLOCO = $cod_template_bloco";

	$qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$cod_mensagem = fnlimpaCampoZero($qrTempl['COD_MENSAGEM']);
	$cod_template_sms = $qrTempl['COD_TEMPLATE_SMS'];

	
	

?>
	
		<?php if ($popUp != "true"){  ?>							
		<div class="push30"></div> 
		<?php } ?>
		
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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
													
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">
					
								<div class="col-md-4 col-md-offset-4">					
									<div class="form-group">
										<label for="inputName" class="control-label">Template</label>
										<select data-placeholder="Selecione a template desejada" name="COD_TEMPLATE_SMS" id="COD_TEMPLATE_SMS" class="chosen-select-deselect" tabindex="1">
											<option value=""></option>
											<?php

												$sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S'";																		
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);																
												while ($qrTemplate = mysqli_fetch_assoc($arrayQuery))
										        {
                                                                                                                    
												echo"
													  <option value='".$qrTemplate['COD_TEMPLATE']."'>".$qrTemplate['NOM_TEMPLATE']. "</option> 
													";    
										        }

											?>								
										</select>                                                   
									</div>   
									<script>$("#COD_TEMPLATE_SMS").val("<?=$cod_template_sms?>").trigger("chosen:updated");</script>
								</div>           
							</div>

						</fieldset>
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							  <?php
								if($cod_mensagem == 0){
									?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
									<?php
								}else{
									?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php
								}
							  ?>
							  
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
						</div>
						
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_MENSAGEM" id="COD_MENSAGEM" value="<?=$cod_mensagem?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
						<input type="hidden" name="COD_TEMPLATE_BLOCO" id="COD_TEMPLATE_BLOCO" value="<?=$cod_template_bloco?>">
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
	
		// if($( "#LOG_ATIVO" ).val() === 'S'){
		// 	$( "#LOG_ATIVO" ).trigger( "click" );
		// }
	
		// $( "#LOG_ATIVO" ).change(function() {
		// 	if($(this).val() === 'N'){
		// 		$(this).val('S');
		// 	}else{
		// 		$(this).val('N');
		// 	}
		// });
	
		function retornaForm(index){
			/*
			$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
			$("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
			$("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
			*/
		}
		
	</script>	