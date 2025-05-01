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
			
			$cod_docchec = fnLimpaCampoZero($_REQUEST['COD_DOCCHEC']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_checkli = fnLimpaCampoZero($_REQUEST['COD_CHECKLI']);
			$cod_documen = fnLimpaCampoZero($_REQUEST['COD_DOCUMEN']);
			if (empty($_REQUEST['LOG_OBRIGAT'])) {$log_obrigat='N';}else{$log_obrigat=$_REQUEST['LOG_OBRIGAT'];}
			$qtd_validad = fnLimpaCampoZero($_REQUEST['QTD_VALIDAD']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){			
				
				$sql = "CALL SP_ALTERA_DOCUMENTOSCHECKLIST (
				 '".$cod_docchec."', 
				 '".$cod_empresa."',
				 '".$cod_checkli."', 
				 '".$cod_documen."', 
				 '".$log_obrigat."',
				 '".$qtd_validad."',
				 '".$opcao."'    
			        );";
					
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
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

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
				
					<?php if ($popUp != "true"){ $abaFormalizacao = 1078; include "abasFormalizacaoEmp.php"; } ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DOCCHEC" id="COD_DOCCHEC" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>	      
						 
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">CheckList</label>
												<select data-placeholder="Selecione um CheckList" name="COD_CHECKLI" id="COD_CHECKLI" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php																	
														$sql = "select * from CHECKLIST WHERE COD_EMPRESA = $cod_empresa order by COD_CHECKLI";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrListaCheckList = mysqli_fetch_assoc($arrayQuery))
														  {													
															echo"
																  <option value='".$qrListaCheckList['COD_CHECKLI']."'>".$qrListaCheckList['DES_DESCRIC']."</option> 
																"; 
															  }											
													?>	
												</select>	
												<script>$("#formulario #COD_CHECKLI").val("<?php echo $cod_checkli; ?>").trigger("chosen:updated"); </script>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Documento</label>
												<select data-placeholder="Selecione um Documento" name="COD_DOCUMEN" id="COD_DOCUMEN" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php																	
														$sql = "select * from DOCUMENTOS WHERE COD_EMPRESA = $cod_empresa  AND COD_EXCLUSA = 0 order by COD_DOCUMEN ";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
														  {													
															echo"
																  <option value='".$qrListaTipoEntidade['COD_DOCUMEN']."'>".$qrListaTipoEntidade['NOM_DOCUMEN']."</option> 
																"; 
															  }											
													?>	
												</select>	
												<script>$("#formulario #COD_DOCUMEN").val("<?php echo $cod_documen; ?>").trigger("chosen:updated"); </script>
											<div class="help-block with-errors"></div>
										</div>
									</div>
						
									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label required">Validade</label>
											<input type="text" class="form-control input-sm" name="QTD_VALIDAD" id="QTD_VALIDAD" value="" required>
										</div>
										<span class="help-block"><b>(em dias)</b></span>
										<div class="help-block with-errors"></div>
									</div>       
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Documento Obrigatório?</label>
											<label class="switch">
											<input type="checkbox" name="LOG_OBRIGAT" id="LOG_OBRIGAT" class="switch" value="S" <?php echo $log_obrigat; ?>/>
											<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>				
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
									  <th>Empresa</th>
									  <th>CheckList</th>
									  <th>Nome do Documento</th>
									  <th>Doc. Obrigatório</th>
									  <th>Validade</th>
									</tr>
								  </thead>
								<tbody id="relatorioConteudo">
								
								<?php 

									$sql = "SELECT DOCUMENTOSCHECKLIST.COD_DOCCHEC,
													DOCUMENTOSCHECKLIST.COD_EMPRESA,
													DOCUMENTOSCHECKLIST.COD_CHECKLI,
													DOCUMENTOSCHECKLIST.COD_DOCUMEN,
													DOCUMENTOSCHECKLIST.LOG_OBRIGAT,
													DOCUMENTOSCHECKLIST.QTD_VALIDAD,
													EMPRESAS.NOM_EMPRESA,
													CHECKLIST.DES_DESCRIC,
													DOCUMENTOS.NOM_DOCUMEN
										FROM DOCUMENTOSCHECKLIST
											LEFT JOIN $connAdm->DB.empresas ON DOCUMENTOSCHECKLIST.COD_EMPRESA = empresas.COD_EMPRESA
											LEFT JOIN CHECKLIST ON DOCUMENTOSCHECKLIST.COD_CHECKLI = CHECKLIST.COD_CHECKLI
											LEFT JOIN DOCUMENTOS ON DOCUMENTOSCHECKLIST.COD_DOCUMEN = DOCUMENTOS.COD_DOCUMEN
										WHERE empresas.COD_EMPRESA = $cod_empresa";
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_DOCCHEC']."</td>
											  <td>".$qrBuscaModulos['NOM_EMPRESA']."</td>
											  <td>".$qrBuscaModulos['DES_DESCRIC']."</td>
											  <td>".$qrBuscaModulos['NOM_DOCUMEN']."</td>
											  <td>".$qrBuscaModulos['LOG_OBRIGAT']."</td>
											  <td>".$qrBuscaModulos['QTD_VALIDAD']." dias</td>
											</tr>
											
											<input type='hidden' id='ret_COD_DOCCHEC_".$count."' value='".$qrBuscaModulos['COD_DOCCHEC']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
											<input type='hidden' id='ret_COD_CHECKLI_".$count."' value='".$qrBuscaModulos['COD_CHECKLI']."'>
											<input type='hidden' id='ret_COD_DOCUMEN_".$count."' value='".$qrBuscaModulos['COD_DOCUMEN']."'>
											<input type='hidden' id='ret_LOG_OBRIGAT_".$count."' value='".$qrBuscaModulos['LOG_OBRIGAT']."'>
											<input type='hidden' id='ret_QTD_VALIDAD_".$count."' value='".$qrBuscaModulos['QTD_VALIDAD']."'>
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

		$(document).ready(function(){
			var popup = "<?=$popUp?>";

			if(popup == 'true'){
				$('#COD_CHECKLI').change(function(){
					cod_checkli = $('#COD_CHECKLI').val();
					$.ajax({
						type: "POST",
						url: "ajxDocChecklist.php",
						data: { COD_EMPRESA:<?=$cod_empresa ?>, COD_CHECKLI:cod_checkli },
						beforeSend:function(){
							$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
						},
						success:function(data){
							console.log(data);
							$('#relatorioConteudo').html(data);								
						},
						error:function(){
							$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Ops, algo de errado aconteceu... :(</p>');
						}
					});
				});
			}
		});
		
		function retornaForm(index){
			$("#formulario #COD_DOCCHEC").val($("#ret_COD_DOCCHEC_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_CHECKLI").val($("#ret_COD_CHECKLI_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_DOCUMEN").val($("#ret_COD_DOCUMEN_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_OBRIGAT_"+index).val() == 'S'){$('#formulario #LOG_OBRIGAT').prop('checked', true);}else{$('#formulario #LOG_OBRIGAT').prop('checked', false);}
			$("#formulario #QTD_VALIDAD").val($("#ret_QTD_VALIDAD_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	