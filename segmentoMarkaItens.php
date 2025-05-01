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

			$cod_segitem = fnLimpaCampoZero($_POST['COD_SEGITEM']);			
			$cod_segment = fnLimpaCampoZero($_POST['COD_SEGMENT']);			
			$nom_segitem = $_POST['NOM_SEGITEM'];
			$abv_segitem = $_POST['ABV_SEGITEM'];
			$des_icone = $_POST['DES_ICONE'];
			$num_ordenac = $_POST['NUM_ORDENAC'];
			
			
			//bloco 1 - perfil
			if (empty($_REQUEST['BL1_MASCULINO'])) {$bl1_masculino='N';}else{$bl1_masculino=$_REQUEST['BL1_MASCULINO'];}
			if (empty($_REQUEST['BL1_FEMININO'])) {$bl1_feminino='N';}else{$bl1_feminino=$_REQUEST['BL1_FEMININO'];}
			if (empty($_REQUEST['BL1_JURIDICO'])) {$bl1_juridico='N';}else{$bl1_juridico=$_REQUEST['BL1_JURIDICO'];}
						
			$bl1_idades_ini = $_REQUEST['BL1_IDADES_INI'];			
			$bl1_idades_fim = $_REQUEST['BL1_IDADES_FIM'];			
			
			if (isset($_POST['BL1_ANIVERSARIO'])){
				$Arr_BL1_ANIVERSARIO = $_POST['BL1_ANIVERSARIO'];
				//print_r($Arr_BL1_ANIVERSARIO);			 
			   for ($i=0;$i<count($Arr_BL1_ANIVERSARIO);$i++) 
			   { 
				$bl1_aniversario = $bl1_aniversario.$Arr_BL1_ANIVERSARIO[$i].",";
			   } 			   
			   $bl1_aniversario = substr($bl1_aniversario,0,-1);				
			}else{$bl1_aniversario = "0";}

			$bl1_operaprofi = fnLimpaCampoHtml($_REQUEST['BL1_OPERAPROFI']);
			//array - profissões
			if (isset($_POST['BL1_PROFISSOES'])){
				$Arr_BL1_PROFISSOES = $_POST['BL1_PROFISSOES'];
				//print_r($Arr_BL1_PROFISSOES);			 
			   for ($i=0;$i<count($Arr_BL1_PROFISSOES);$i++) 
			   { 
				$bl1_profissoes = $bl1_profissoes.$Arr_BL1_PROFISSOES[$i].";";
			   } 			   
			   $bl1_profissoes = substr($bl1_profissoes,0,-1);				
			}else{$bl1_profissoes = "0";}
			
			//fnEscreve($nom_submenus);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				$sql = "CALL SP_ALTERA_SEGMARKAITEM (
				 '".$cod_segitem."', 
				 '".$cod_segment."', 
				 '".$nom_segitem."', 
				 '".$abv_segitem."', 
				 '".$des_icone."',
				 '".$bl1_masculino."',
				 '".$bl1_feminino."',
				 '".$bl1_juridico."',
				 '".$bl1_idades_ini."',
				 '".$bl1_idades_fim."',
				 '".$bl1_aniversario."',
				 '".$bl1_operaprofi."',
				 '".$bl1_profissoes."',
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				// fnEscreve($sql);
	
				mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
                                
				
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
      
	//fnMostraForm();

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
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_SEGITEM" id="COD_SEGITEM" value="">
														</div>
													</div>
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Item</label>
															<input type="text" class="form-control input-sm" name="NOM_SEGITEM" id="NOM_SEGITEM" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="ABV_SEGITEM" id="ABV_SEGITEM" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Segmento</label>
																<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_SEGMENT']."'>".$qrLista['NOM_SEGMENT']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
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
												
										</fieldset>	
																				
										<div class="push10"></div>
										
										<fieldset>
											<legend>Perfil (configuração) </legend> 
											
												<div class="row">
												
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Homens</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="BL1_MASCULINO" id="BL1_MASCULINO" class="switch" value="S" checked>
																<span></span>
																</label>
														</div>
													</div>
										
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Mulheres</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="BL1_FEMININO" id="BL1_FEMININO" class="switch" value="S" checked>
																<span></span>
																</label>
														</div>
													</div>
										
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Jurídico</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="BL1_JURIDICO" id="BL1_JURIDICO" class="switch" value="S" checked>
																<span></span>
																</label>
														</div>
													</div>
										
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Idade Mín.</label>
															<input type="text" class="form-control input-sm" name="BL1_IDADES_INI" id="BL1_IDADES_INI" value="0">
														</div>
													</div>
																				
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Idade Max.</label>
															<input type="text" class="form-control input-sm" name="BL1_IDADES_FIM" id="BL1_IDADES_FIM" value="100">
														</div>
													</div>
												
													<div class="col-md-7">
														<div class="form-group">
															<label for="inputName" class="control-label">Aniversário</label>
																<select data-placeholder="Selecione um mês" name="BL1_ANIVERSARIO[]" id="BL1_ANIVERSARIO" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																	<option value="1">Janeiro</option>
																	<option value="2">Fevereiro</option>
																	<option value="3">Março</option>
																	<option value="4">Abril</option>
																	<option value="5">Maio</option>
																	<option value="6">Junho</option>
																	<option value="7">Julho</option>
																	<option value="8">Agosto</option>
																	<option value="9">Setembro</option>
																	<option value="10">Outubro</option>
																	<option value="11">Novembro</option>
																	<option value="12">Dezembro</option>
																</select>
															<div class="help-block with-errors"></div>
	
														</div>
													</div>
										
												</div>
												
												<div class="row">
												
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo de uso da profissão</label>
															<select data-placeholder="Escolha a forma de uso da profissão" name="BL1_OPERAPROFI" id="BL1_OPERAPROFI" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																<option value=""></option>					
																<option value="=" >Profissões iguais a:</option> 
																<option value="!=" >Profissões diferentes de:</option> 
															</select>
														</div>
													</div>
												
													<div class="col-md-8">
														<div class="form-group">
															<label for="inputName" class="control-label">Profissões</label>
															<select data-placeholder="Escolha as profissões" name="BL1_PROFISSOES[]" id="BL1_PROFISSOES" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																<option value=""></option>					
																<?php 																	
																	$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
																	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																
																	while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
																	  {														
																		echo"
																			  <option value='".$qrListaProfi['COD_PROFISS']."'>".$qrListaProfi['DES_PROFISS']."</option> 
																			"; 
																		  }											
																?>	
															</select>										
														</div>
													</div>
												
												</div>
												
										</fieldset>										
										
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Categoria</th>
													  <th>Nome do Submenu</th>
													  <th>Ícone</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select A.*,
													(select B.NUM_ORDENAC from SEGMENTOMARKA B where B.COD_SEGMENT = A.COD_SEGMENT ) as COD_SEGMENT1,
													(select B.NOM_SEGMENT from SEGMENTOMARKA B where B.COD_SEGMENT = A.COD_SEGMENT ) as NOM_SEGMENT
													from SEGMARKAITEM A order by COD_SEGMENT1, A.NUM_ORDENAC													
													";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														echo"
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='".$qrBuscaModulos['COD_SEGITEM']."'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_SEGITEM']."</td>
															  <td>".$qrBuscaModulos['NOM_SEGMENT']."</td>
															  <td>".$qrBuscaModulos['NOM_SEGITEM']."</td>
															  <td align='center'><span class='".$qrBuscaModulos['DES_ICONE']."' ></td>
															</tr>
															<input type='hidden' id='ret_COD_SEGITEM_".$count."' value='".$qrBuscaModulos['COD_SEGITEM']."'>
															<input type='hidden' id='ret_COD_SEGMENT_".$count."' value='".$qrBuscaModulos['COD_SEGMENT']."'>
															<input type='hidden' id='ret_NOM_SEGITEM_".$count."' value='".$qrBuscaModulos['NOM_SEGITEM']."'>
															<input type='hidden' id='ret_ABV_SEGITEM_".$count."' value='".$qrBuscaModulos['ABV_SEGITEM']."'>
															<input type='hidden' id='ret_DES_ICONE_".$count."' value='".$qrBuscaModulos['DES_ICONE']."'>
															<input type='hidden' id='ret_NUM_ORDENAC_".$count."' value='".$qrBuscaModulos['NUM_ORDENAC']."'>															
															<input type='hidden' id='ret_BL1_MASCULINO_".$count."' value='".$qrBuscaModulos['BL1_MASCULINO']."'>
															<input type='hidden' id='ret_BL1_FEMININO_".$count."' value='".$qrBuscaModulos['BL1_FEMININO']."'>
															<input type='hidden' id='ret_BL1_JURIDICO_".$count."' value='".$qrBuscaModulos['BL1_JURIDICO']."'>
															<input type='hidden' id='ret_BL1_IDADES_INI_".$count."' value='".$qrBuscaModulos['BL1_IDADES_INI']."'>
															<input type='hidden' id='ret_BL1_IDADES_FIM_".$count."' value='".$qrBuscaModulos['BL1_IDADES_FIM']."'>
															<input type='hidden' id='ret_BL1_ANIVERSARIO_".$count."' value='".$qrBuscaModulos['BL1_ANIVERSARIO']."'>
															<input type='hidden' id='ret_TEM_ANIVERSARIO_".$count."' value='".$tem_aniversario."'>
															<input type='hidden' id='ret_BL1_OPERAPROFI_".$count."' value='".$qrBuscaModulos['BL1_OPERAPROFI']."'>
															<input type='hidden' id='ret_BL1_PROFISSOES_".$count."' value='".$qrBuscaModulos['BL1_PROFISSOES']."'>
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
					
	<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
	
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
 	<script>
		$(function() {
			
			$( ".table-sortable tbody" ).sortable();
				
            $('.table-sortable tbody').sortable({
                handle: 'span'
            });

		   $(".table-sortable tbody").sortable({
		   
					stop: function(event, ui) {
						
						var Ids = "";
						$('table tr').each(function( index ) {
							if(index != 0){
									Ids =  Ids + $(this).children().find('span.glyphicon').attr('data-id') +",";
							}
						});
						
						//update ordenação
						//console.log(Ids.substring(0,(Ids.length-1)));
						
						var arrayOrdem = Ids.substring(0,(Ids.length-1));
						//alert(arrayOrdem);
						execOrdenacao(arrayOrdem,2);
					
						function execOrdenacao(p1,p2) {
							//alert(p2);
							$.ajax({
								type: "GET",
								url: "ajxOrdenacao.php",
								data: { ajx1:p1,ajx2:p2},
								beforeSend:function(){
									//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
								},
								success:function(data){
									//$("#divId_sub").html(data); 
								},
								error:function(){
									$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
								}
							});		
						}
						
					}
					
			});
					

			$( ".table-sortable tbody" ).disableSelection();		
			
		});

		
        $(document).ready( function() {
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			//arrastar 
			$('.grabbable').on('change', function(e) { 
				//console.log(e.icon);
				$("#DES_ICONE").val(e.icon);		
			});	

			$(".grabbable").click(function() {
				$(this).parent().addClass('selected').siblings().removeClass('selected');

			});
			
			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});	
			
        });

				
		function retornaForm(index){
			$("#formulario #COD_SEGITEM").val($("#ret_COD_SEGITEM_"+index).val());
			$("#formulario #COD_SEGMENT").val($("#ret_COD_SEGMENT_"+index).val()).trigger("chosen:updated");
			$("#formulario #NOM_SEGITEM").val($("#ret_NOM_SEGITEM_"+index).val());
			$("#formulario #ABV_SEGITEM").val($("#ret_ABV_SEGITEM_"+index).val());
			$('#btniconpicker').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val());			
			$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_"+index).val());
			$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_"+index).val());
			
			if ($("#ret_BL1_MASCULINO_"+index).val() == 'S'){$('#formulario #BL1_MASCULINO').prop('checked', true);} 
			else {$('#formulario #BL1_MASCULINO').prop('checked', false);}
			
			if ($("#ret_BL1_FEMININO_"+index).val() == 'S'){$('#formulario #BL1_FEMININO').prop('checked', true);} 
			else {$('#formulario #BL1_FEMININO').prop('checked', false);}
			
			if ($("#ret_BL1_JURIDICO_"+index).val() == 'S'){$('#formulario #BL1_JURIDICO').prop('checked', true);} 
			else {$('#formulario #BL1_JURIDICO').prop('checked', false);}
			
			
			$("#formulario #BL1_IDADES_INI").val($("#ret_BL1_IDADES_INI_"+index).val());
			$("#formulario #BL1_IDADES_FIM").val($("#ret_BL1_IDADES_FIM_"+index).val());
			
			//retorno combo multiplo
			$("#formulario #BL1_ANIVERSARIO").val('').trigger("chosen:updated");
			//if ($("#ret_TEM_ANIVERSARIO_"+index).val() == "tem" ){
				var valorCampo = $("#ret_BL1_ANIVERSARIO_"+index).val();
			if(valorCampo != ""){
				var valorCampoArr = valorCampo.split(',');
				//opções multiplas
				for (var i = 0; i < valorCampoArr.length; i++) {
				  $("#formulario #BL1_ANIVERSARIO option[value=" + valorCampoArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #BL1_ANIVERSARIO").trigger("chosen:updated"); 
			}   
			//} else {$("#formulario #BL1_ANIVERSARIO").val('').trigger("chosen:updated");}
			
			//retorno profissões
			$("#formulario #BL1_OPERAPROFI").val($("#ret_BL1_OPERAPROFI_"+index).val()).trigger("chosen:updated");			
			$("#formulario #BL1_PROFISSOES").val('').trigger("chosen:updated");
				var valorCampo = $("#ret_BL1_PROFISSOES_"+index).val();
			if(valorCampo != ""){
				var valorCampoArr = valorCampo.split(',');
				//opções multiplas
				for (var i = 0; i < valorCampoArr.length; i++) {
				  $("#formulario #BL1_PROFISSOES option[value=" + valorCampoArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #BL1_PROFISSOES").trigger("chosen:updated");
			}			
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   