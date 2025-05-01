<?php
	
	//echo fnDebug('true');
	
	$log_obrigat = "N";
 
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
			
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
			$num_process = fnLimpaCampo($_REQUEST['NUM_PROCESS']);
			$num_conveni = fnLimpaCampo($_REQUEST['NUM_CONVENI']);
			$nom_conveni = fnLimpaCampo($_REQUEST['NOM_CONVENI']);
			$nom_abrevia = fnLimpaCampo($_REQUEST['NOM_ABREVIA']);
			$des_descric = fnLimpaCampo($_REQUEST['DES_DESCRIC']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
			$dat_inicinv = fnLimpaCampo($_REQUEST['DAT_INICINV']);
			$dat_fimconv = fnLimpaCampo($_REQUEST['DAT_FIMCONV']);
			$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){			
				
				$sql = "CALL SP_ALTERA_CONVENIO (
				 '".$cod_conveni."', 
				 '".$cod_empresa."',
				 '".$cod_entidad."', 
				 '".$num_process."', 
				 '".$num_conveni."',
				 '".$nom_conveni."',
				 '".$nom_abrevia."',
				 '".$des_descric."',
				 '".fnValorSql2($val_valor)."',
				 '".fnValorSql2($val_contpar)."',
				 '".fnDataSql($dat_inicinv)."',
				 '".fnDataSql($dat_fimconv)."',
				 '".fnDataSql($dat_assinat)."',
				 '".$opcao."'    
			        );";
					
				//fnEscreve($sql);
                mysqli_query(connTemp($cod_empresa,''),$sql);				
				
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
	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['cod_conveni'])))){
            
		//busca dados do convênio
		$cod_conveni = fnDecode($_GET['cod_conveni']);	
		$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaConvenio = mysqli_fetch_assoc($arrayQuery);
			
			
			
		if (isset($qrBuscaConvenio)){
			$cod_entidad = $qrBuscaConvenio['COD_ENTIDAD'];
			$num_process = $qrBuscaConvenio['NUM_PROCESS'];
			$num_conveni = $qrBuscaConvenio['NUM_CONVENI'];
			$nom_conveni = $qrBuscaConvenio['NOM_CONVENI'];
			$nom_abrevia = $qrBuscaConvenio['NOM_ABREVIA'];
			$des_descric = $qrBuscaConvenio['DES_DESCRIC'];
			$val_valor = $qrBuscaConvenio['VAL_VALOR'];
			$val_contpar = $qrBuscaConvenio['VAL_CONTPAR'];
			$dat_inicinv = $qrBuscaConvenio['DAT_INICINV'];
			$dat_fimconv = $qrBuscaConvenio['DAT_FIMCONV'];
			$dat_assinat = $qrBuscaConvenio['DAT_ASSINAT'];
		}
		
	}else {	
		$num_process = "";
		$num_conveni = "";
		$nom_conveni = "";
		$nom_abrevia = "";
		$des_descric = "";
		$val_valor = "";
		$val_contpar = "";
		$dat_inicinv = "";
		$dat_fimconv = "";
		$dat_assinat = "";
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

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
				
					<?php $abaFormalizacao = 1083; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONVENI" id="COD_CONVENI" value="<?php echo $cod_conveni ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>														
								</div>           

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Entidade</label>
											<select data-placeholder="Selecione uma entidade" name="COD_ENTIDAD" id="COD_ENTIDAD" class="chosen-select-deselect">
												
												<?php																	
													$sql = "select * from ENTIDADE order by COD_ENTIDAD ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													fnEscreve($cod_entidad);
													while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
													  {													
														echo"
															  <option value='".$qrListaTipoEntidade['COD_ENTIDAD']."'>".$qrListaTipoEntidade['NOM_ENTIDAD']."</option> 
															"; 
														  }											
												?>	
											</select>	
											<script>$("#formulario #COD_ENTIDAD").val("<?php echo $cod_entidad; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Processo</label>
										<input type="text" class="form-control input-sm" name="NUM_PROCESS" id="NUM_PROCESS" value="<?php echo $num_process ?>" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Convênio</label>
										<input type="text" class="form-control input-sm" name="NUM_CONVENI" id="NUM_CONVENI" value="<?php echo $num_conveni ?>" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>  
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome</label>
										<input type="text" class="form-control input-sm" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>  

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="NOM_ABREVIA" id="NOM_ABREVIA" value="<?php echo $nom_abrevia ?>" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>  


								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Exemplo</label>
											<select data-placeholder="Selecione uma entidade" name="COD_ENTIDAD" id="COD_ENTIDAD" class="chosen-select-deselect" multiple>
												
												<?php																	
													$sql = "select * from ENTIDADE order by COD_ENTIDAD ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													fnEscreve($cod_entidad);
													while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
													  {													
														echo"
															  <option value='".$qrListaTipoEntidade['COD_ENTIDAD']."'>".$qrListaTipoEntidade['NOM_ENTIDAD']."</option> 
															"; 
														  }											
												?>	
											</select>	
											<script>$("#formulario #COD_ENTIDAD").val("<?php echo $cod_entidad; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>								

													
					
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRIC" id="DES_DESCRIC" maxlength="250"><?php echo $des_descric ?></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor</label>
										<input type="text" class="form-control input-sm money" name="VAL_VALOR" id="VAL_VALOR" value="<?php echo $val_valor ?>" data-mask="#.##0,00" data-mask-reverse="true">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor de Contrapartida</label>
										<input type="text" class="form-control input-sm money" name="VAL_CONTPAR" id="VAL_CONTPAR" value="<?php echo $val_contpar ?>" data-mask="#.##0,00" data-mask-reverse="true">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>
										<input type="text" class="form-control input-sm data" name="DAT_INICINV" id="DAT_INICINV" value="<?php echo date_time($dat_inicinv) ?>" data-mask="00/00/0000">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>
										<input type="text" class="form-control input-sm data" name="DAT_FIMCONV" id="DAT_FIMCONV" value="<?php echo date_time($dat_fimconv) ?>" data-mask="00/00/0000">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data de Assinatura</label>
										<input type="text" class="form-control input-sm data" name="DAT_ASSINAT" id="DAT_ASSINAT" value="<?php echo date_time($dat_assinat) ?>" data-mask="00/00/0000">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
						
							</div>
							
							<div class="push10"></div>
							
					</fieldset>
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  <!--<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> -->
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
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
	
		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	<!-- $('#formulario').validator();

		});
	
		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		} -->
		
	</script>	