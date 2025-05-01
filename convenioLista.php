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
				
					<?php $abaFormalizacao = 1079; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						
						<h4>- Criar nova tela de tipo de convenio (fluxo) </h4> 
						<!-- <h4>- Colocar combo nessa tela </h4> --> 
						<!-- <h4>- Colocar ícone e cor </h4> --> 
						<!-- <h4>- Arrumar chosen </h4>  -->
						<!-- <h4>- Arrumar calendários </h4> --> 
						<h4>- Arrumar tela única </h4> 
																
						<fieldset>
						<legend>Dados Gerais</legend> 
					
							<div class="row">
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONVENI" id="COD_CONVENI" value="">
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
												<option value=""></option>
												<?php																	
													$sql = "select * from ENTIDADE order by COD_ENTIDAD ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
												
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
										<input type="text" class="form-control input-sm" name="NUM_PROCESS" id="NUM_PROCESS" value="" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Convênio</label>
										<input type="text" class="form-control input-sm" name="NUM_CONVENI" id="NUM_CONVENI" value="" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
	                                <div class="form-group">
	                                    <label for="inputName" class="control-label">Tipo de Convênio</label>
	                                    <select data-placeholder="Selecione um tipo" name="COD_TPCONVENI" id="COD_TPCONVENI" class="chosen-select-deselect">
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
	                                </div>
	                            </div> 
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome</label>
										<input type="text" class="form-control input-sm" name="NOM_CONVENI" id="NOM_CONVENI" value="" maxlength="60">
									</div>
									<div class="help-block with-errors"></div>
								</div>  

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="NOM_ABREVIA" id="NOM_ABREVIA" value="" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">															
									</div>														
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br/>
											<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" 
												data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" 
												data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
											</button>
											<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div> 
								</div>  								

							</div>

							<div class="row">					
					
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRIC" id="DES_DESCRIC" value="" maxlength="250"></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div> 

							</div>

							<div class="row">      
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor</label>
										<input type="text" class="form-control input-sm money" name="VAL_VALOR" id="VAL_VALOR" value="" data-mask="#.##0,00" data-mask-reverse="true">
									</div>
									<div class="help-block with-errors"></div>
								</div>       
					
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor de Contrapartida</label>
										<input type="text" class="form-control input-sm money" name="VAL_CONTPAR" id="VAL_CONTPAR" value="" data-mask="#.##0,00" data-mask-reverse="true">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_INICINV" id="DAT_INICINV" value=""/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>      

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_FIMCONV" id="DAT_FIMCONV" value=""/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>     

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data de Assinatura</label>
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_ASSINAT" id="DAT_ASSINAT" value=""/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
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
									  <th>Nome</th>
									  <th>Entidade</th>
									  <th>Nome do Processo</th>
									  <th>Descriçao</th>
									  <th>Valor</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT  CONVENIO.COD_CONVENI,
													CONVENIO.COD_EMPRESA,
													CONVENIO.COD_ENTIDAD,
													CONVENIO.NUM_PROCESS,
													CONVENIO.NUM_CONVENI,
													CONVENIO.NOM_CONVENI,
													CONVENIO.NOM_ABREVIA,
													CONVENIO.DES_DESCRIC,
													CONVENIO.VAL_VALOR,
													CONVENIO.VAL_CONTPAR,
													CONVENIO.DAT_INICINV,
													CONVENIO.DAT_FIMCONV,
													CONVENIO.DAT_ASSINAT,
													EMPRESAS.NOM_EMPRESA,
													ENTIDADE.NOM_ENTIDAD 
										FROM CONVENIO
											LEFT JOIN $connAdm->DB.empresas ON CONVENIO.COD_EMPRESA = empresas.COD_EMPRESA
											LEFT JOIN ENTIDADE ON CONVENIO.COD_ENTIDAD = ENTIDADE.COD_ENTIDAD
										WHERE empresas.COD_EMPRESA = $cod_empresa
										ORDER BY COD_CONVENI";
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									$cod_conveni = 
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_CONVENI']."</td>
											  <td><a href='action.php?mod=".fnEncode(1083)."&id=".fnEncode($cod_empresa)."&cod_conveni=".fnEncode($qrBuscaModulos['COD_CONVENI'])."'>".$qrBuscaModulos['NOM_CONVENI']."</a></td>
											  <td>".$qrBuscaModulos['NOM_ENTIDAD']."</td>
											  <td>".$qrBuscaModulos['NUM_PROCESS']."</td>
											  <td>".$qrBuscaModulos['DES_DESCRIC']."</td>
											  <td class='money' data-mask='#.##0,00' data-mask-reverse='true'>".$qrBuscaModulos['VAL_VALOR']." dias</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CONVENI_".$count."' value='".$qrBuscaModulos['COD_CONVENI']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
											<input type='hidden' id='ret_COD_ENTIDAD_".$count."' value='".$qrBuscaModulos['COD_ENTIDAD']."'>
											<input type='hidden' id='ret_NUM_PROCESS_".$count."' value='".$qrBuscaModulos['NUM_PROCESS']."'>
											<input type='hidden' id='ret_NUM_CONVENI_".$count."' value='".$qrBuscaModulos['NUM_CONVENI']."'>
											<input type='hidden' id='ret_NOM_CONVENI_".$count."' value='".$qrBuscaModulos['NOM_CONVENI']."'>
											<input type='hidden' id='ret_NOM_ABREVIA_".$count."' value='".$qrBuscaModulos['NOM_ABREVIA']."'>
											<input type='hidden' id='ret_DES_DESCRIC_".$count."' value='".$qrBuscaModulos['DES_DESCRIC']."'>
											<input type='hidden' id='ret_VAL_VALOR_".$count."' value='".$qrBuscaModulos['VAL_VALOR']."'>
											<input type='hidden' id='ret_VAL_CONTPAR_".$count."' value='".$qrBuscaModulos['VAL_CONTPAR']."'>
											<input type='hidden' id='ret_DAT_INICINV_".$count."' value='".date_time($qrBuscaModulos['DAT_INICINV'])."'>
											<input type='hidden' id='ret_DAT_FIMCONV_".$count."' value='".date_time($qrBuscaModulos['DAT_FIMCONV'])."'>
											<input type='hidden' id='ret_DAT_ASSINAT_".$count."' value='".date_time($qrBuscaModulos['DAT_ASSINAT'])."'>
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

	<!-- <script type="text/javascript" src="js/plugins/chosenImage/chosenImage.jquery.js"></script>
	<script type="text/javascript" src="js/plugins/chosenImage/chosenImage.css"></script> -->
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript">	

		$(document).ready(function(){
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});

			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});

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