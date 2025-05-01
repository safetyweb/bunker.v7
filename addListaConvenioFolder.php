<?php
	
	//echo fnDebug('true');
	//echo fnDecode($_GET["id"]);
	
	$log_obrigat = "N";
	$cod_conveni = "";

	$cod_conveni = fnLimpaCampoZero(fnDecode($_GET['idC']));
	
	if (@$_GET["upload"] == true){
		include("uploadArquivos.php");
		exit;
	}else{
	 
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
				$cod_tpconveni = fnLimpaCampoZero($_REQUEST['COD_TPCONVENI']);
				$nom_conveni = fnLimpaCampo($_REQUEST['NOM_CONVENI']);
				$nom_abrevia = fnLimpaCampo($_REQUEST['NOM_ABREVIA']);
				if (empty($_REQUEST['LOG_LICITACAO'])) {$log_licitacao='N';}else{$log_licitacao=$_REQUEST['LOG_LICITACAO'];}
				$des_descric = fnLimpaCampo($_REQUEST['DES_DESCRIC']);
				$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
				$val_conced = fnLimpaCampo($_REQUEST['VAL_CONCED']);
				$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
				$dat_inicinv = fnLimpaCampo($_REQUEST['DAT_INICINV']);
				$dat_fimconv = fnLimpaCampo($_REQUEST['DAT_FIMCONV']);
				$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);
				$des_anexo = fnLimpaCampo($_REQUEST['DES_ANEXO']);
				$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
				
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
					 '".$cod_tpconveni."',
					 '".$nom_conveni."',
					 '".$nom_abrevia."',
					 '".$des_descric."',
					 '".fnValorSql($val_valor)."',
					 '".fnValorSql($val_conced)."',
					 '".fnValorSql($val_contpar)."',
					 '".fnDataSql($dat_inicinv)."',
					 '".fnDataSql($dat_fimconv)."',
					 '".fnDataSql($dat_assinat)."',
					 '".$des_anexo."',
					 '".$opcao."'    
						);";
						
					//fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),$sql);

					$cod_provisorio = $num_contador;

					if($cod_conveni == 0){

						$sqlCod = "SELECT MAX(COD_CONVENI) COD_CONVENI FROM CONVENIO WHERE COD_EMPRESA = $cod_empresa";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_conveni = $qrCod[COD_CONVENI];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_CONVENI = $cod_conveni, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

					}else{
						$sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni AND COD_PROVISORIO = $cod_provisorio";
						mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
					}

					//atualiza lista iframe				
					?>
					<script>
						try { parent.$('#REFRESH_CONVENIOS').val("S"); } catch(err) {}
					</script>						
					<?php				
					
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
	$cod_tipo = fnDecode($_GET['tipo']);
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


if ($cod_conveni != 0){

	//busca dados do convênio
		
	$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	

	// fnEscreve($cod_conveni);

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaTemplate)){
		$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
		$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
		$num_process = $qrBuscaTemplate['NUM_PROCESS'];
		$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
		$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
		$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
		$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
		$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
		$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
		$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
		$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
		$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
		$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
		$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
		$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
	
	}

	if($cod_conveni == 0){
		$leitura = "";
	}else{
		$leitura = "disabled";
	}
	
}else{

	$cod_conveni = 0;
	$cod_entidad = "";
	$num_process = "";
	$num_conveni = "";
	$cod_tpconveni = "";
	$nom_conveni = "";
	$nom_abrevia = "";
	$des_descric = "";
	$val_valor = "";
	$val_conced = "";
	$val_contpar = "";
	$dat_inicinv = "";
	$dat_fimconv = "";
	$dat_assinat = "";
	$log_licitacao = "";
	$leitura = "";

}
	  
//fnMostraForm();
//fnEscreve($cod_checkli);

$tp_cont = 'Anexo do Convênio';
$tp_anexo = 'COD_CONVENI';
$cod_tpanexo = 'COD_CONVENI';
$cod_busca = $cod_conveni;

$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND LOG_STATUS = 'N'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
$num_contador = $qrCont['NUM_CONTADOR'];

// fnEscreve($cod_conveni);


	?>

	<style>
		
	.area {
	  width: 100%;
	  padding: 7px;
	}

	#dropZone {
	  display: block;
	  border: 2px dashed #bbb;
	  -webkit-border-radius: 5px;
	  border-radius: 5px;
	  margin-left: -7px;
	}

	#dropZone p{
		font-size: 10pt;
		letter-spacing: -0.3pt;
		margin-bottom: 0px;
	}

	#dropzone .fa{
		font-size: 15pt;
	}

	</style>


		
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

							<?php 
							//menu superior - convenios
							// $abaConvenio = 1097;										
							// include "abasConvenio.php";								
							
							?>

							<!-- <div class="push30"></div>	 -->

							<div class="tabbable-line">
								<ul class="nav nav-tabs">
									<li>
										<a href="action.do?mod=<?php echo fnEncode(1563)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>" style="text-decoration: none;">
										<span class="fal fa-arrow-circle-left fa-2x"></span></a>
									</li>
								</ul>
							</div>										
							
							<div class="push20"></div> 
														
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

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Entidade</label>
												<select data-placeholder="Selecione uma entidade" name="COD_ENTIDAD" id="COD_ENTIDAD" class="chosen-select-deselect" <?=$leitura?> required>
													
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
											<label for="inputName" class="control-label required">Número do Processo</label>
											<input type="text" class="form-control input-sm" name="NUM_PROCESS" id="NUM_PROCESS" value="<?php echo $num_process; ?>" maxlength="60" <?=$leitura?> required>
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número do Convênio</label>
											<input type="text" class="form-control input-sm" name="NUM_CONVENI" id="NUM_CONVENI" value="<?php echo $num_conveni; ?>" maxlength="60" <?=$leitura?> required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Convênio</label>
											<select data-placeholder="Selecione um tipo" name="COD_TPCONVENI" id="COD_TPCONVENI" class="chosen-select-deselect" <?=$leitura?> >
												<option value=""></option>
												<?php																	
													$sql = "SELECT * FROM TIPO_CONVENIO ORDER BY DES_TPCONVENI ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													//fnEscreve($sql);
													while ($qrTpConveni = mysqli_fetch_assoc($arrayQuery))
													 {													
														echo"
															  <option value='".$qrTpConveni['COD_TPCONVENI']."'>".$qrTpConveni['DES_TPCONVENI']."</option> 
															"; 
													}											
												?>
											</select>   
											<div class="help-block with-errors"></div>
											<script>$("#formulario #COD_TPCONVENI").val("<?php echo $cod_tpconveni; ?>").trigger("chosen:updated"); </script>
										</div>
									</div>

								</div>

								<div class="row">  
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome</label>
											<input type="text" class="form-control input-sm" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni; ?>" maxlength="60" <?=$leitura?> required>
										</div>
										<div class="help-block with-errors"></div>
									</div>  

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Abreviação</label>
											<input type="text" class="form-control input-sm" name="NOM_ABREVIA" id="NOM_ABREVIA" value="<?php echo $nom_abrevia; ?>" maxlength="20" <?=$leitura?> required>
										</div>
										<div class="help-block with-errors"></div>
									</div>  

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Licitação Opcional?</label>
											<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_LICITACAO" id="LOG_LICITACAO" class="switch" value="S" <?=$leitura?>/>
											<span></span>
											</label>
												<script>
													if ('<?php echo $log_licitacao ?>' == 'S'){$('#formulario #LOG_LICITACAO').prop('checked', true);} 
													else {$('#formulario #LOG_LICITACAO').prop('checked', false);}
												</script>
											<div class="help-block with-errors"></div>
										</div>				
									</div>

								</div>

								<div class="row">			
						
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição</label>
											<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRIC" id="DES_DESCRIC" maxlength="250" <?=$leitura?>><?php echo $des_descric; ?></textarea required>
										</div>
										<div class="help-block with-errors"></div>
									</div> 

								</div>

								<div class="row">
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Concedente</label>
											<input type="text" class="form-control input-sm money" name="VAL_CONCED" id="VAL_CONCED" value="<?php echo $val_conced; ?>" data-mask="##0" data-mask-reverse="true" maxlength="11" <?=$leitura?>>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">+</span>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor de Contrapartida</label>
											<input type="text" class="form-control input-sm money" name="VAL_CONTPAR" id="VAL_CONTPAR" value="<?php echo $val_contpar; ?>" data-mask="##0" data-mask-reverse="true" maxlength="18" <?=$leitura?>>
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
									<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">=</span>
									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor Global</label>
											<input type="text" class="form-control input-sm money leituraOff" name="VAL_VALOR" id="VAL_VALOR" value="<?php echo $val_valor; ?>" readonly data-mask="##0" data-mask-reverse="true" maxlength="18" required <?=$leitura?>>
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="row">     
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INICINV" id="DAT_INICINV" data-error="Data inválida. Preencha este campo." value="<?=$dat_inicinv?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>       
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIMCONV" id="DAT_FIMCONV" data-error="Data inválida/menor que inicial. Preencha este campo." value="<?=$dat_fimconv?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>       
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data de Assinatura</label>
											<div class="input-group date datePicker" id="DAT_ASSINAT_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ASSINAT" id="DAT_ASSINAT" data-error="Data inválida/fora do intervalo. Preencha este campo." value="<?=$dat_assinat?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>       
							
								</div>

								<div class="push10"></div>

								<?php 

									if($cod_conveni != 0){
										include "uploadConvenio.php";
									}else{
								?>
										<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert" >
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										 O upload de arquivos só ficará disponível após o cadastro dos dados do convênio.
										</div>
								<?php 
									}
								?>
								
								<div class="push10"></div>
								
						</fieldset>
							
																	
							<div class="push10"></div>
							<hr>	
							<div class="form-group text-right col-lg-12">
								
								  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
								  <?php
									if($cod_tipo == 'CAD'){
										if($cod_conveni == 0 || $cod_conveni == ""){
										?>
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
										<?php
										}
									}else{
										if($cod_conveni == 0 || $cod_conveni == ""){
										?>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
										<?php 
										}
									}
								  ?>
							
							</div>
							
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
							<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
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

		<?php 

			if($cod_conveni != 0){
		?>

                refreshUpload();

                $('.upload').prop('disabled',false).removeAttr('disabled');

        <?php 

			}
		?>

                $('.datePicker').datetimepicker({
                         format: 'DD/MM/YYYY',
                        }).on('changeDate', function(e){
                                $(this).datetimepicker('hide');
                        });

                //chosen obrigatório
                $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
                $('#formulario').validator();

                $('#VAL_CONTPAR,#VAL_CONCED,#VAL_VALOR').change(function(){
    	
        if($('#VAL_CONTPAR').val() != ''){
                val_contpar = $('#VAL_CONTPAR').cleanVal();
        }else{
                val_contpar = 0;
        }

        if($('#VAL_CONCED').val() != ''){
                val_conveni = $('#VAL_CONCED').cleanVal();
        }else{
                val_conveni = 0;
        }

	    total = Number(val_contpar)+Number(val_conveni);

	    $('#VAL_VALOR').val(total).mask('000.000.000.000.000,00', {reverse: true});
        });
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate : 'now',
		}).on('changeDate', function(e){
		       $(this).datetimepicker('hide');
		});

        $("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
		$('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);
		$('#DAT_ASSINAT_GRP').data("DateTimePicker").defaultDate(false);

		$("#DAT_FIM_GRP").on("dp.error", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").date(null);
        });

        $('#DAT_ASSINAT_GRP').on("dp.error", function (e) {
               $('#DAT_ASSINAT_GRP').data("DateTimePicker").date(null);
        });

        $("#DAT_INI_GRP").on("dp.change", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date).date(null);
               $('#DAT_ASSINAT_GRP').data("DateTimePicker").minDate(e.date).date(null);
        });
        $("#DAT_FIM_GRP").on("dp.change", function (e) {
               $('#DAT_ASSINAT_GRP').data("DateTimePicker").maxDate(e.date).date(null);
        });
        //$("#DAT_ASSINAT_GRP").on("dp.change", function (e) {
               //$('#DAT_FIMCONV_GRP').data("DateTimePicker").maxDate(e.date);
        //});
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
        }

</script>

<?php
	}
?>