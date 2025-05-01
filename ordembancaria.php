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
			
			$cod_ordemba = fnLimpaCampoZero($_REQUEST['COD_ORDEMBA']);
			$log_tipordem = fnLimpaCampo($_REQUEST['LOG_TIPORDEM']);
			$num_ordemba = fnLimpaCampo($_REQUEST['NUM_ORDEMBA']);
			$dat_ordemba = fnLimpaCampo($_REQUEST['DAT_ORDEMBA']);
			$val_ordemba = fnLimpaCampo($_REQUEST['VAL_ORDEMBA']);
			$num_ordemcontr = fnLimpaCampo($_REQUEST['NUM_ORDEMCONTR']);
			$dat_aportecontr = fnLimpaCampo($_REQUEST['DAT_APORTECONTR']);
			$val_aportecontr = fnLimpaCampo($_REQUEST['VAL_APORTECONTR']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_ORDEMBANCARIA (
				 '".$cod_ordemba."', 
				 '".$log_tipordem."',
				 '".$num_ordemba."', 
				 '".fnDataSql($dat_ordemba)."', 
				 '".fnValorSql2($val_ordemba)."', 
				 '".$num_ordemcontr."',
				 '".fnDataSql($dat_aportecontr)."',
				 '".fnValorSql2($val_aportecontr)."',
				 '".$cod_usucada."',
				 '".$opcao."'    
			        );";
				
				$cod_empresa = fnDecode($_GET['id']);
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
	
	//busca dados do usuário
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = ".$cod_usucada;	
			
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaUsuario)){
		$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

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
				
					<?php $abaFormalizacao = 1086; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ORDEMBA" id="COD_ORDEMBA" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div> 

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Tipo</label>
												<select data-placeholder="Selecione" name="LOG_TIPORDEM" id="LOG_TIPORDEM" class="chosen-select-deselect">
													<option value=""></option>					
													<option value="T">Total</option> 
													<option value="P">Parcial</option> 							
												</select>
												<script>$("#formulario #LOG_TIPORDEM").val("<?php echo $log_tipordem; ?>").trigger("chosen:updated"); </script>
											<div class="help-block with-errors"></div>
										</div>
										<div class="help-block with-errors"></div>
									</div> 										      
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número</label>
											<input type="text" class="form-control input-sm" name="NUM_ORDEMBA" id="NUM_ORDEMBA" value="" maxlength="40" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data</label>
											<input type="text" class="form-control input-sm data" name="DAT_ORDEMBA" id="DAT_ORDEMBA" value="" data-mask="00/00/0000">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor</label>
											<input type="text" class="form-control input-sm money" name="VAL_ORDEMBA" id="VAL_ORDEMBA" value="" data-mask="#.##0,00" data-mask-reverse="true">
										</div>
										<div class="help-block with-errors"></div>
									</div> 

								</div>
								
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número Contrapartida</label>
											<input type="text" class="form-control input-sm" name="NUM_ORDEMCONTR" id="NUM_ORDEMCONTR" value="" maxlength="40">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Contrapartida</label>
											<input type="text" class="form-control input-sm data" name="DAT_APORTECONTR" id="DAT_APORTECONTR" value="" data-mask="00/00/0000">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor Contrapartida</label>
											<input type="text" class="form-control input-sm money" name="VAL_APORTECONTR" id="VAL_APORTECONTR" value="" data-mask="#.##0,00" data-mask-reverse="true">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Usuário Cadastrado</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUCADA" id="COD_USUCADA" value="<?php echo $nom_usuario ?>">
											<input type="hidden" class="form-control input-sm" name="COD_USUCADA" id="COD_USUCADA" value="<?php echo $cod_usucada ?>">
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
							  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
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
								
								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Data</th>
									  <th>Valor</th>
									  <th>Data Contrapartida</th>
									  <th>Valor Contrapartida</th>
									</tr>
								  </thead>
								<tbody>							
								
								<?php 
									$sql = "select ORDEMBANCARIA.COD_ORDEMBA,"
												 ."ORDEMBANCARIA.LOG_TIPORDEM,"
												 ."ORDEMBANCARIA.NUM_ORDEMBA,"
												 ."ORDEMBANCARIA.DAT_ORDEMBA,"
												 ."ORDEMBANCARIA.VAL_ORDEMBA,"
												 ."ORDEMBANCARIA.NUM_ORDEMCONTR,"
												 ."ORDEMBANCARIA.DAT_APORTECONTR,"
												 ."ORDEMBANCARIA.VAL_APORTECONTR,"
												 ."ORDEMBANCARIA.COD_USUCADA,"
												 ."USUARIOS.NOM_USUARIO "
											."from ORDEMBANCARIA "
												."left join $connAdm->DB.USUARIOS ON USUARIOS.COD_USUARIO = ORDEMBANCARIA.COD_USUCADA "
											."order by COD_ORDEMBA";
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_ORDEMBA']."</td>
											  <td>".date_time($qrBuscaModulos['DAT_ORDEMBA'])."</td>
											  <td class='money' data-mask='#.##0,00' data-mask-reverse='true'>".$qrBuscaModulos['VAL_ORDEMBA']."</td>
											  <td>".date_time($qrBuscaModulos['DAT_APORTECONTR'])."</td>
											  <td class='money' data-mask='#.##0,00' data-mask-reverse='true'>".$qrBuscaModulos['VAL_APORTECONTR']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_ORDEMBA_".$count."' value='".$qrBuscaModulos['COD_ORDEMBA']."'>
											<input type='hidden' id='ret_LOG_TIPORDEM_".$count."' value='".$qrBuscaModulos['LOG_TIPORDEM']."'>
											<input type='hidden' id='ret_NUM_ORDEMBA_".$count."' value='".$qrBuscaModulos['NUM_ORDEMBA']."'>
											<input type='hidden' id='ret_DAT_ORDEMBA_".$count."' value='".date_time($qrBuscaModulos['DAT_ORDEMBA'])."'>
											<input type='hidden' id='ret_VAL_ORDEMBA_".$count."' value='".$qrBuscaModulos['VAL_ORDEMBA']."'>
											<input type='hidden' id='ret_NUM_ORDEMCONTR_".$count."' value='".$qrBuscaModulos['NUM_ORDEMCONTR']."'>
											<input type='hidden' id='ret_DAT_APORTECONTR_".$count."' value='".date_time($qrBuscaModulos['DAT_APORTECONTR'])."'>
											<input type='hidden' id='ret_VAL_APORTECONTR_".$count."' value='".$qrBuscaModulos['VAL_APORTECONTR']."'>
											<input type='hidden' id='ret_COD_USUCADA_".$count."' value='".$qrBuscaModulos['NOM_USUARIO']."'>
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
		
		function retornaForm(index){
			$("#formulario #COD_ORDEMBA").val($("#ret_COD_ORDEMBA_"+index).val());
			$("#formulario #LOG_TIPORDEM").val($("#ret_LOG_TIPORDEM_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_ORDEMBA").val($("#ret_NUM_ORDEMBA_"+index).val());
			$("#formulario #DAT_ORDEMBA").val($("#ret_DAT_ORDEMBA_"+index).val());
			$("#formulario #VAL_ORDEMBA").unmask().val($("#ret_VAL_ORDEMBA_"+index).val());
			$("#formulario #NUM_ORDEMCONTR").val($("#ret_NUM_ORDEMCONTR_"+index).val());
			$("#formulario #DAT_APORTECONTR").val($("#ret_DAT_APORTECONTR_"+index).val());
			$("#formulario #VAL_APORTECONTR").unmask().val($("#ret_VAL_APORTECONTR_"+index).val());
			$("#formulario #COD_USUCADA").val($("#ret_COD_USUCADA_"+index).val());

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	