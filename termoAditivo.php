<?php
	
	echo fnDebug('true');
 
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
			
			$cod_aditivo = fnLimpaCampoZero($_REQUEST['COD_ADITIVO']);
			$tip_aditivo = fnLimpaCampo($_REQUEST['TIP_ADITIVO']);
			$tip_tipadit = fnLimpaCampo($_REQUEST['TIP_TIPADIT']);
			$cod_tipmoti = fnLimpaCampoZero($_REQUEST['COD_TIPMOTI']);
			$des_observa = fnLimpaCampo($_REQUEST['DES_OBSERVA']);
			$dat_inicial = fnLimpaCampo($_REQUEST['DAT_INICIAL']);
			$dat_final = fnLimpaCampo($_REQUEST['DAT_FINAL']);
			$val_conveni = fnLimpaCampo($_REQUEST['VAL_CONVENI']);
			$val_contrap = fnLimpaCampo($_REQUEST['VAL_CONTRAP']);
			$val_totalgl = fnLimpaCampo($_REQUEST['VAL_TOTALGL']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_TERMOADITIVO (
				 '".$cod_aditivo."', 
				 '".$tip_aditivo."',
				 '".$tip_tipadit."',
				 '".$cod_tipmoti."', 
				 '".$des_observa."', 
				 '".fnDataSql($dat_inicial)."', 
				 '".fnDataSql($dat_final)."', 
				 '".fnValorSql2($val_conveni)."', 
				 '".fnValorSql2($val_contrap)."',
				 '".fnValorSql2($val_totalgl)."',
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
				
					<?php $abaFormalizacao = 1085; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
					<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">
							
								<!-- Tipo do aditivo-->
								<input type="hidden" class="form-control input-sm" name="TIP_TIPADIT" id="TIP_TIPADIT" value="CNV">
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ADITIVO" id="COD_ADITIVO" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Termo Aditivo</label>
											<select data-placeholder="Selecione" name="TIP_ADITIVO" id="TIP_ADITIVO" class="chosen-select-deselect">
												<option value=""></option>					
												<option value="P">A Prazo</option> 
												<option value="V">Valor</option> 							
											</select>
											<script>$("#formulario #TIP_ADITIVO").val("<?php echo $tip_aditivo; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
									<div class="help-block with-errors"></div>
								</div> 								


								<div class="col-md-7">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Motivo</label>
											<select data-placeholder="Selecione" name="COD_TIPMOTI" id="COD_TIPMOTI" class="chosen-select-deselect">
												<option value=""></option>
												<?php																	
													$sql = "select * from TIPOMOTIVO order by cod_tipmoti ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
												
													while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
													  {													
														echo"
															  <option value='".$qrListaTipoEntidade['COD_TIPMOTI']."'>".$qrListaTipoEntidade['DES_TPMOTIV']."</option> 
															"; 
														  }											
												?>	
											</select>	
											<script>$("#formulario #COD_TIPMOTI").val("<?php echo $cod_tipmoti; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>	


							</div>
							<div class="row">
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>
										<input type="text" class="form-control input-sm data" name="DAT_INICIAL" id="DAT_INICIAL" value="" data-mask="00/00/0000">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>
										<input type="text" class="form-control input-sm data" name="DAT_FINAL" id="DAT_FINAL" value="" data-mask="00/00/0000">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Convênio</label>
										<input type="text" class="form-control input-sm money" name="VAL_CONVENI" id="VAL_CONVENI" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Contrapartida</label>
										<input type="text" class="form-control input-sm money" name="VAL_CONTRAP" id="VAL_CONTRAP" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Global</label>
										<input type="text" class="form-control input-sm money" name="VAL_TOTALGL" id="VAL_TOTALGL" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-9">
									<div class="form-group">
										<label for="inputName" class="control-label required">Usuário Cadastrado</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUCADA" id="COD_USUCADA" value="<?php echo $nom_usuario?>" required> 
										<input type="hidden" class="form-control input-sm" name="COD_USUCADA" id="COD_USUCADA" value="<?php echo $cod_usucada ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>     

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Observação</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" value="" maxlength="250"></textarea>
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
									  <th>Tipo do Motivo</th>
									  <th>Data Inicial</th>
									  <th>Data Final</th>
									  <th>Valor Convênio</th>
									  <th>Valor Global</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "select TERMOADITIVO.COD_ADITIVO,"
												 ."TERMOADITIVO.TIP_ADITIVO,"
												 ."TERMOADITIVO.TIP_TIPADIT,"
												 ."TERMOADITIVO.COD_TIPMOTI,"
												 ."TERMOADITIVO.DES_OBSERVA,"
												 ."TERMOADITIVO.DAT_INICIAL,"
												 ."TERMOADITIVO.DAT_FINAL,"
												 ."TERMOADITIVO.VAL_CONVENI,"
												 ."TERMOADITIVO.VAL_CONTRAP,"
												 ."TERMOADITIVO.VAL_TOTALGL,"
												 ."TERMOADITIVO.COD_USUCADA,"
												 ."TIPOMOTIVO.DES_TPMOTIV,"
												 ."USUARIOS.NOM_USUARIO "
											."from TERMOADITIVO " 
												."left join $connAdm->DB.TIPOMOTIVO ON TIPOMOTIVO.COD_TIPMOTI = TERMOADITIVO.COD_TIPMOTI "
												."left join $connAdm->DB.USUARIOS ON USUARIOS.COD_USUARIO = TERMOADITIVO.COD_USUCADA "
											."where TERMOADITIVO.TIP_TIPADIT = 'CNV' "
											."order by COD_ADITIVO";
											
									//echo $sql;
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_ADITIVO']."</td>
											  <td>".$qrBuscaModulos['DES_TPMOTIV']."</td>
											  <td>".date_time($qrBuscaModulos['DAT_INICIAL'])."</td>
											  <td>".date_time($qrBuscaModulos['DAT_FINAL'])."</td>
											  <td class='money' data-mask='#.##0,00' data-mask-reverse='true'>".$qrBuscaModulos['VAL_CONVENI']."</td>
											  <td class='money' data-mask='#.##0,00' data-mask-reverse='true'>".$qrBuscaModulos['VAL_TOTALGL']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_ADITIVO_".$count."' value='".$qrBuscaModulos['COD_ADITIVO']."'>
											<input type='hidden' id='ret_TIP_ADITIVO_".$count."' value='".$qrBuscaModulos['TIP_ADITIVO']."'>
											<input type='hidden' id='ret_COD_TIPMOTI_".$count."' value='".$qrBuscaModulos['COD_TIPMOTI']."'>
											<input type='hidden' id='ret_DES_OBSERVA_".$count."' value='".$qrBuscaModulos['DES_OBSERVA']."'>
											<input type='hidden' id='ret_DAT_INICIAL_".$count."' value='".date_time($qrBuscaModulos['DAT_INICIAL'])."'>
											<input type='hidden' id='ret_DAT_FINAL_".$count."' value='".date_time($qrBuscaModulos['DAT_FINAL'])."'>
											<input type='hidden' id='ret_VAL_CONVENI_".$count."' value='".$qrBuscaModulos['VAL_CONVENI']."'>
											<input type='hidden' id='ret_VAL_CONTRAP_".$count."' value='".$qrBuscaModulos['VAL_CONTRAP']."'>
											<input type='hidden' id='ret_VAL_TOTALGL_".$count."' value='".$qrBuscaModulos['VAL_TOTALGL']."'>
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
			$("#formulario #COD_ADITIVO").val($("#ret_COD_ADITIVO_"+index).val());
			$("#formulario #TIP_ADITIVO").val($("#ret_TIP_ADITIVO_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_TIPMOTI").val($("#ret_COD_TIPMOTI_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$("#formulario #DAT_INICIAL").val($("#ret_DAT_INICIAL_"+index).val());
			$("#formulario #DAT_FINAL").val($("#ret_DAT_FINAL_"+index).val());
			$("#formulario #VAL_CONVENI").unmask().val($("#ret_VAL_CONVENI_"+index).val());
			$("#formulario #VAL_CONTRAP").unmask().val($("#ret_VAL_CONTRAP_"+index).val());
			$("#formulario #VAL_TOTALGL").unmask().val($("#ret_VAL_TOTALGL_"+index).val());
			$("#formulario #COD_USUCADA").val($("#ret_COD_USUCADA_"+index).val());

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	