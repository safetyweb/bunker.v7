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

			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
			$dat_nascime = fnLimpaCampo($_REQUEST['DAT_NASCIME']);
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
			$cod_sexopes = fnLimpaCampoZero($_REQUEST['COD_SEXOPES']);
			$des_emailus = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
			$num_telefon = fnLimpaCampo($_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpaCampo($_REQUEST['NUM_CELULAR']);
			$num_comerci = fnLimpaCampo($_REQUEST['NUM_COMERCI']);

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO CLIENTES(
											COD_EMPRESA,
											NOM_CLIENTE,
											DAT_NASCIME,
											NUM_CGCECPF,
											COD_SEXOPES,
											DES_EMAILUS,
											NUM_TELEFON,
											NUM_CELULAR,
											NUM_COMERCI,
											LOG_ESTATUS,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											'$nom_cliente',
											'$dat_nascime',
											'$num_cgcecpf',
											$cod_sexopes,
											'$des_emailus',
											'$num_telefon',
											'$num_celular',
											'$num_comerci',
											'S',
											$cod_usucada
											)";
						
						// fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),$sql);

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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-xs-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Apoiador</label>
															<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?=$nom_cliente?>" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data de Nascimento</label>
                                                            <input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?php echo fnDataShort($dat_nascime);?>" id="DAT_NASCIME" maxlength="10">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">CNPJ/CPF</label>
                                                             <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf,'F');?>" maxlength="18" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-xs-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Sexo</label>
																<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
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

												<div class="push10"></div>

												<div class="row">

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">e-Mail</label>
                                                            <input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus;?>" maxlength="100" value="" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Principal</label>
                                                            <input type="text" class="form-control input-sm sp_celphones" name="NUM_TELEFON" value="<?php fnCorrigeTelefone($num_telefon); ?>" id="NUM_TELEFON" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
														
													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Celular</label>
                                                            <input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php fnCorrigeTelefone($num_celular); ?>" id="NUM_CELULAR" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Comercial</label>
                                                            <input type="text" class="form-control input-sm sp_celphones" name="NUM_COMERCI" value="<?php fnCorrigeTelefone($num_comerci); ?>" id="NUM_COMERCI" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
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
						
					<div class="push20"></div> 
	
	<script type="text/javascript">

		$(function(){
			var SPMaskBehavior = function (val) {
			  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
			  onKeyPress: function(val, e, field, options) {
				  field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

			$('.sp_celphones').on('input propertychange paste', function (e) {
			    var reg = /^0+/gi;
			    if (this.value.match(reg)) {
			        this.value = this.value.replace(reg, '');
			    }
			});
			
			$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
		});
		
		// function retornaForm(index){
		// 	$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
		// 	$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
		// 	$('#formulario').validator('validate');			
		// 	$("#formulario #hHabilitado").val('S');						
		// }
		
	</script>	