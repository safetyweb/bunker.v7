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
			
			// $val_totalgl = $val_conveni + $val_contrap; 


			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_TERMOADITIVO (
				 '".$cod_aditivo."', 
				 '".$cod_empresa."', 
				 '".$cod_conveni."', 
				 '".$tip_aditivo."',
				 '".$tip_tipadit."',
				 '".$cod_tipmoti."', 
				 '".$des_observa."', 
				 '".fnDataSql($dat_inicial)."', 
				 '".fnDataSql($dat_final)."', 
				 '".fnValorSql($val_conveni)."', 
				 '".fnValorSql($val_contrap)."',
				 '".fnValorSql($val_totalgl)."',
				 '".$cod_usucada."',
				 '".$opcao."'    
			        );";
				
				//fnEscreve($sql);				
                mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());			
				
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
	
	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
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
	//fnEscreve($cod_conveni);

?>
	
	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
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
					<div class="push30"></div> 			
					<?php } ?>	

					<?php 
					//menu superior - convenios
					$abaConvenio = 1093;										
					include "abasConvenio.php";
					?>		

					<div class="push30"></div>			
					
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
					<fieldset>
						<legend>Dados do Aditivo</legend> 
					
							<div class="row">
							
								<!-- Tipo do aditivo-->
								<input type="hidden" class="form-control input-sm" name="TIP_TIPADIT" id="TIP_TIPADIT" value="CON">
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ADITIVO" id="COD_ADITIVO" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
									</div>														
								</div> 
									
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Termo Aditivo</label>
											<select data-placeholder="Selecione" name="TIP_ADITIVO" id="TIP_ADITIVO" class="chosen-select-deselect requiredChk" required >
												<option value=""></option>					
												<option value="P">A Prazo</option> 
												<option value="V">Valor</option> 							
											</select>
											<script>$("#formulario #TIP_ADITIVO").val("<?php echo $tip_aditivo; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo do Motivo</label>
											<select data-placeholder="Selecione" name="COD_TIPMOTI" id="COD_TIPMOTI" class="chosen-select-deselect requiredChk" required >
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
					
								<div class="col-md-4">
									<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INICIAL" id="DAT_INICIAL" data-error="Data inválida. Preencha este campo." value="<?=$dat_inicial?>" <?=$leitura?> required/>
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
												<input type='text' class="form-control input-sm data" name="DAT_FINAL" id="DAT_FINAL" data-error="Data inválida/menor que inicial. Preencha este campo." value="<?=$dat_final?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
								</div>

							</div>

							<div class="row" id="blocoValor">
					
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Concedente</label>
										<input type="text" class="form-control input-sm money text-right" name="VAL_CONVENI" id="VAL_CONVENI" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Contrapartida</label>
										<input type="text" class="form-control input-sm money text-right" name="VAL_CONTRAP" id="VAL_CONTRAP" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Global</label>
										<input type="text" class="form-control input-sm money text-right leituraOff" name="VAL_TOTALGL" readonly id="VAL_TOTALGL" value="" data-mask="#.##0,00" data-mask-reverse="true" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="row">      

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
						
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?php echo $cod_conveni; ?>">
						<input type="hidden" name="COD_USUCADA" id="COD_USUCADA" value="<?php echo $cod_usucada; ?>">
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
									  <th class="text-right">Valor Concedente</th>
									  <th class="text-right">Valor Contrapartida</th>
									  <th class="text-right">Valor Global</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "select TA.COD_ADITIVO, 
												 TA.TIP_ADITIVO, 
												 TA.TIP_TIPADIT, 
												 TA.COD_TIPMOTI, 
												 TA.DES_OBSERVA, 
												 TA.DAT_INICIAL, 
												 TA.DAT_FINAL,  
												 TA.VAL_CONVENI, 
												 TA.VAL_CONTRAP, 
												 TA.VAL_TOTALGL, 
												 TA.COD_USUCADA, 
												 TM.DES_TPMOTIV
											from TERMOADITIVO TA
											left join $connAdm->DB.TIPOMOTIVO TM ON TM.COD_TIPMOTI = TA.COD_TIPMOTI 
											where TA.TIP_TIPADIT = 'CON' 
											and TA.COD_EMPRESA = $cod_empresa
											order by TA.COD_ADITIVO";
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									//and TA.COD_CONVENI = $cod_conveni
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;

										if($qrBuscaModulos['TIP_ADITIVO'] == 'P'){
											$termoAditivo = "A Prazo";
										}else{
											$termoAditivo = "Valor";
										}

										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_ADITIVO']."</td>
											  <td>".$termoAditivo."</td>
											  <td>".date_time($qrBuscaModulos['DAT_INICIAL'])."</td>
											  <td>".date_time($qrBuscaModulos['DAT_FINAL'])."</td>
											  <td class='money text-right'>".fnValor($qrBuscaModulos['VAL_CONVENI'],2)."</td>
											  <td class='money text-right'>".fnValor($qrBuscaModulos['VAL_CONTRAP'],2)."</td>
											  <td class='money text-right'>".fnValor($qrBuscaModulos['VAL_TOTALGL'],2)."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_ADITIVO_".$count."' value='".$qrBuscaModulos['COD_ADITIVO']."'>
											<input type='hidden' id='ret_TIP_ADITIVO_".$count."' value='".$qrBuscaModulos['TIP_ADITIVO']."'>
											<input type='hidden' id='ret_COD_TIPMOTI_".$count."' value='".$qrBuscaModulos['COD_TIPMOTI']."'>
											<input type='hidden' id='ret_DES_OBSERVA_".$count."' value='".$qrBuscaModulos['DES_OBSERVA']."'>
											<input type='hidden' id='ret_DAT_INICIAL_".$count."' value='".date_time($qrBuscaModulos['DAT_INICIAL'])."'>
											<input type='hidden' id='ret_DAT_FINAL_".$count."' value='".date_time($qrBuscaModulos['DAT_FINAL'])."'>
											<input type='hidden' id='ret_VAL_CONVENI_".$count."' value='".fnValor($qrBuscaModulos['VAL_CONVENI'],2)."'>
											<input type='hidden' id='ret_VAL_CONTRAP_".$count."' value='".fnValor($qrBuscaModulos['VAL_CONTRAP'],2)."'>
											<input type='hidden' id='ret_VAL_TOTALGL_".$count."' value='".fnValor($qrBuscaModulos['VAL_TOTALGL'],2)."'>
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
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
<script type="text/javascript">
    $(function(){
        $('.upload').prop('disabled',true);

        $('.datePicker').datetimepicker({
                 format: 'DD/MM/YYYY',
                }).on('changeDate', function(e){
                        $(this).datetimepicker('hide');
                });

        //chosen obrigatório
        //$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        //$('#formulario').validator();

        $("#TIP_ADITIVO").change(function(){
                if($(this).val() == 'P'){
                        $('#blocoValor').fadeOut(1);
                        $("#VAL_CONVENI").val('').prop('required',false);
                        $("#VAL_CONTRAP").val('').prop('required',false);
                        $("#VAL_TOTALGL").val('').prop('required',false);
                }else{
                        $('#blocoValor').fadeIn(1);
                        $("#VAL_CONVENI").prop('required',true);
                        $("#VAL_CONTRAP").prop('required',true);
                        $("#VAL_TOTALGL").prop('required',true);
                }
                $('#formulario').validator('validate');
        });

        $('#VAL_CONTRAP,#VAL_CONVENI,#VAL_TOTALGL').keyup(function(){

                $('#VAL_TOTALGL').unmask();

                if($('#VAL_CONTPAR').val() != ''){

                        val_contrap = $('#VAL_CONTRAP').unmask().val();
                        $("#VAL_CONVENI").prop('required',false);

                }else{

                        val_contrap = 0;
                        $("#VAL_CONVENI").prop('required',true);

                }

                if($('#VAL_CONVENI').val() != ''){

                        val_conveni = $('#VAL_CONVENI').unmask().val();
                        $("#VAL_CONTRAP").prop('required',false);

                }else{

                        val_conveni = 0;
                        $("#VAL_CONTRAP").prop('required',true);

                }

                total = Number(val_contrap) + Number(val_conveni);

                $('#VAL_TOTALGL').val(total);

                $('#formulario').validator('validate');

        });
        $('.datePicker').datetimepicker({
                 format: 'DD/MM/YYYY',
                 maxDate : 'now',
                }).on('changeDate', function(e){
                        $(this).datetimepicker('hide');
                });

        $("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
		$('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);

		$("#DAT_FIM_GRP").on("dp.error", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").date(null);
        });
          $("#DAT_INI_GRP").on("dp.change", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date).date(null);
        });
        $("#DAT_FIM_GRP").on("dp.change", function (e) {
                $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
        });
});

function retornaForm(index){
        $("#formulario #COD_ADITIVO").val($("#ret_COD_ADITIVO_"+index).val());
        $("#formulario #TIP_ADITIVO").val($("#ret_TIP_ADITIVO_"+index).val()).trigger("chosen:updated");
        $("#formulario #COD_TIPMOTI").val($("#ret_COD_TIPMOTI_"+index).val()).trigger("chosen:updated");
        $("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
        $("#formulario #DAT_INICIAL").val($("#ret_DAT_INICIAL_"+index).val());
        $("#formulario #DAT_FINAL").val($("#ret_DAT_FINAL_"+index).val());
        $("#formulario #VAL_CONVENI").val($("#ret_VAL_CONVENI_"+index).val());
        $("#formulario #VAL_CONTRAP").val($("#ret_VAL_CONTRAP_"+index).val());
        $("#formulario #VAL_TOTALGL").val($("#ret_VAL_TOTALGL_"+index).val());
        $("#formulario #COD_USUCADA").val($("#ret_COD_USUCADA_"+index).val());

        if($("#ret_TIP_ADITIVO_"+index).val() == 'P'){
                $('#blocoValor').fadeOut(1);
                $("#VAL_CONVENI").val('').prop('required',false);
                $("#VAL_CONTRAP").val('').prop('required',false);
                $("#VAL_TOTALGL").val('').prop('required',false);
        }else{
                $('#blocoValor').fadeIn(1);
                $("#VAL_CONVENI").prop('required',true);
                $("#VAL_CONTRAP").prop('required',true);
                $("#VAL_TOTALGL").prop('required',true);
        }

        $('#formulario').validator('validate');			
        $("#formulario #hHabilitado").val('S');			
}

</script>	
	
	<?php include 'jsUploadConvenio.php'; ?>