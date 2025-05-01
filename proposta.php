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
			
			$cod_proposta = fnLimpaCampoZero($_REQUEST['COD_PROPOSTA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_objeto = fnLimpaCampoZero($_REQUEST['COD_OBJETO']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

			$sqlLicita = "SELECT COD_LICITAC FROM LICITACAO_OBJETO WHERE COD_OBJETO = $cod_objeto";
			$arrayLicita = mysqli_query(connTemp($cod_empresa,''),$sqlLicita);
			$qrLicita = mysqli_fetch_assoc($arrayLicita);

			$cod_licitac = $qrLicita['COD_LICITAC'];

			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			if (empty($_REQUEST['LOG_STATUS'])) {$log_status='N';}else{$log_status=$_REQUEST['LOG_STATUS'];}
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO PROPOSTA(
										COD_EMPRESA,
										COD_CONVENI,
										COD_LICITAC,
										COD_OBJETO,
										COD_CLIENTE,
										VAL_VALOR,
										LOG_STATUS,
										COD_USUCADA
										) VALUES(
										$cod_empresa,
										$cod_conveni,
										$cod_licitac,
										$cod_objeto,
										$cod_cliente,
										'".fnValorsql($val_valor)."',
										'$log_status',
										$cod_usucada
										)";
						// fnEscreve($sql);
						
	                	mysqli_query(connTemp($cod_empresa,''),$sql);

	                	if($cod_proposta == 0){

							$sqlCod = "SELECT MAX(COD_PROPOSTA) COD_PROPOSTA FROM PROPOSTA WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
							$qrCod = mysqli_fetch_assoc($arrayQuery);
							$cod_proposta = $qrCod[COD_PROPOSTA];

							$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

							if(mysqli_num_rows($arrayCont) > 0){
								$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_PROPOSTA = $cod_proposta, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
								mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
							}

						}else{
							// $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
							// mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE PROPOSTA SET
										COD_EMPRESA=$cod_empresa,
										COD_CONVENI=$cod_conveni,
										COD_LICITAC=$cod_licitac,
										COD_OBJETO=$cod_objeto,
										COD_CLIENTE=$cod_cliente,
										VAL_VALOR='".fnValorsql($val_valor)."',
										LOG_STATUS='$log_status',
										COD_ALTERAC=$cod_usucada,
										DAT_ALTERAC=NOW()
										WHERE COD_PROPOSTA = $cod_proposta";
							//fnEscreve($sql);
						
	               		mysqli_query(connTemp($cod_empresa,''),$sql);

	               		$sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROPOSTA = $cod_proposta AND LOG_STATUS = 'N'";
						mysqli_query(connTemp($cod_empresa,''),$sqlUpd);

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

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);
			
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);	
			$sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
			
			}			
			
			
		}
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

	$tp_cont = 'Anexo da Proposta';
	$tp_anexo = 'COD_PROPOSTA';
	$cod_tpanexo = 'COD_PROPOSTA';
	$cod_busca = $cod_proposta;

	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROPOSTA != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);
	
	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];

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
									<i class="fal fa-terminal"></i>
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

					<?php 
					//menu superior - empresas
					$abaProposta = 1091;										
					include "abasPropostas.php";								
					
					?>
					
					<div class="push30"></div>		
					
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROPOSTA" id="COD_PROPOSTA" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Convênio</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
										</div>														
									</div>        
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Objeto da Licitação</label>
												<select data-placeholder="Selecione um objeto" name="COD_OBJETO" id="COD_OBJETO" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php																	
														$sql = "SELECT * FROM LICITACAO_OBJETO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
														  {													
															echo"
																  <option value='".$qrListaTipoEntidade['COD_OBJETO']."'>".$qrListaTipoEntidade['NOM_OBJETO']."</option> 
																"; 
															  }											
													?>	
												</select>	
												<!-- <script>$("#formulario #COD_LICITAC").val("<?php echo $cod_licitac; ?>").trigger("chosen:updated"); </script> -->
											<div class="help-block with-errors"></div>
										</div>

									</div>

								</div>

								<div class="row">
									
									
									<div class="col-md-6">
										<label for="inputName" class="control-label required">Nome do Fornecedor/Responsável</label>
										<div class="input-group">
										<span class="input-group-btn">
										<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1762)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Busca Fornecedor"><i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
										</span>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" readonly value="" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="">
										</div>
										<div class="help-block with-errors"></div>														
									</div> 								
						
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor da Proposta</label>
											<input type="text" class="form-control input-sm money" name="VAL_VALOR" id="VAL_VALOR" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">   
										<div class="form-group">
											<label for="inputName" class="control-label">Vencedora?</label> 
											<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_STATUS" id="LOG_STATUS" class="switch" value="S">
											<span></span>
											</label>
										</div>
									</div>									
							
								</div>
								
								<div class="push10"></div>

								<?php include "uploadConvenio.php"; ?>
								
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
						
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
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
									  <th>Código</th>
									  <th>Fornecedor/Responsável</th>
									  <th>Objeto</th>
									  <th>Valor</th>
									  <th>Vencedora</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT PPT.*, LCT.NOM_LICITAC, CL.NOM_CLIENTE, LCO.NOM_OBJETO FROM PROPOSTA PPT 
									LEFT JOIN LICITACAO LCT ON LCT.COD_LICITAC = PPT.COD_LICITAC
									LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = PPT.COD_OBJETO
									LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PPT.COD_CLIENTE
									WHERE PPT.COD_EMPRESA = $cod_empresa AND PPT.COD_CONVENI = $cod_conveni";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {
										if($qrBuscaModulos['LOG_STATUS']=='S'){ 
											$status = "<span class='fas fa-check' style='color:#18bc9c;'></span>"; 
										}else{ 
											$status = "<span class='fas fa-times' style='color:red;'></span>"; 
										}												  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_PROPOSTA']."</td>
											  <td>".$qrBuscaModulos['NOM_CLIENTE']."</td>
											  <td>".$qrBuscaModulos['NOM_OBJETO']."</td>
											  <td>".fnValor($qrBuscaModulos['VAL_VALOR'],2)."</td>
											  <td class='text-center'>".$status."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_PROPOSTA_".$count."' value='".$qrBuscaModulos['COD_PROPOSTA']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
											<input type='hidden' id='ret_COD_OBJETO_".$count."' value='".$qrBuscaModulos['COD_OBJETO']."'>
											<input type='hidden' id='ret_COD_CONVENI_".$count."' value='".$qrBuscaModulos['COD_CONVENI']."'>
											<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrBuscaModulos['COD_CLIENTE']."'>
											<input type='hidden' id='ret_NOM_CLIENTE_".$count."' value='".$qrBuscaModulos['NOM_CLIENTE']."'>
											<input type='hidden' id='ret_LOG_STATUS_".$count."' value='".$qrBuscaModulos['LOG_STATUS']."'>
											<input type='hidden' id='ret_VAL_VALOR_".$count."' value='".fnValor($qrBuscaModulos['VAL_VALOR'],2)."'>
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

	<!-- modal -->									
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->				
		
	<div class="push20"></div>

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
    <script type="text/javascript">

    $(document).ready(function(){

        $('.upload').prop('disabled',true);

        $('.datePicker').datetimepicker({
                 format: 'DD/MM/YYYY',
                }).on('changeDate', function(e){
                        $(this).datetimepicker('hide');
                });

        //chosen obrigatório
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();


    });

    function retornaForm(index){
        $("#formulario #COD_PROPOSTA").val($("#ret_COD_PROPOSTA_"+index).val());
        $("#formulario #COD_OBJETOANEXO").val($("#ret_COD_PROPOSTA_"+index).val());
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
        $("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val());
        $("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
        $("#formulario #COD_OBJETO").val($("#ret_COD_OBJETO_"+index).val()).trigger("chosen:updated");
        $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
        $("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_"+index).val());
        $("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());

        if ($("#ret_LOG_STATUS_"+index).val() == 'S'){$('#formulario #LOG_STATUS').prop('checked', true);} 
        else {$('#formulario #LOG_STATUS').prop('checked', false);}

        $('.upload').prop('disabled',false).removeAttr('disabled');

        $('#formulario').validator('validate');			
        $("#formulario #hHabilitado").val('S');

        refreshUpload();		
    }

</script>

	<?php include 'jsUploadConvenio.php'; ?>	