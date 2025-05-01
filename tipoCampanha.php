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

			$cod_tpcampa = fnLimpaCampoZero($_POST['COD_TPCAMPA']);			
			$abv_tpcampa = $_POST['ABV_TPCAMPA'];
			$nom_tpcampa = $_POST['NOM_TPCAMPA'];
			$des_icone = $_POST['DES_ICONE'];
			$num_ordenac = $_POST['NUM_ORDENAC'];
			$label_1 = $_POST['LABEL_1'];
			$label_2 = $_POST['LABEL_2'];
			$label_3 = $_POST['LABEL_3'];
			$label_4 = $_POST['LABEL_4'];
			$label_5 = $_POST['LABEL_5'];
			$cod_benefic = $_POST['COD_BENEFIC'];
			
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

			
			//fnEscreve($nom_submenus);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
			
				$sql = "CALL SP_ALTERA_TIPOCAMPANHA (
				 '".$cod_tpcampa."', 
				 '".$nom_tpcampa."', 
				 '".$abv_tpcampa."', 
				 '".$des_icone."', 
				 '".$log_ativo."', 
				 '".$label_1."', 
				 '".$label_2."', 
				 '".$label_3."', 
				 '".$label_4."', 
				 '".$label_5."', 
				 '".$cod_benefic."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				//fnEscreve($cod_submenus);
	
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPCAMPA" id="COD_TPCAMPA" value="">
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Ativo</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" checked >
																<span></span>
																</label>
														</div>
													</div>											
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Tipo da Campanha</label>
															<input type="text" class="form-control input-sm" name="NOM_TPCAMPA" id="NOM_TPCAMPA" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="ABV_TPCAMPA" id="ABV_TPCAMPA" maxlength="20">
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
													
													<div class="push10"></div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Benefício (agrupador)</label>
																<select data-placeholder="Selecione o tipo de benefício" name="COD_BENEFIC" id="COD_BENEFIC" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php 																	
																		$sql = "SELECT COD_BENEFIC, DES_BENEFIC FROM tipobeneficio order by DES_BENEFIC ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaBeneficio = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaBeneficio['COD_BENEFIC']."'>".$qrListaBeneficio['DES_BENEFIC']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
													
													
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										
										<fieldset>
											<legend>Textos Dinâmicos</legend> 
											
												<div class="row">
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Label 1</label>
															<input type="text" class="form-control input-sm" name="LABEL_1" id="LABEL_1" value="">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Label 2</label>
															<input type="text" class="form-control input-sm" name="LABEL_2" id="LABEL_2" value="">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Label 3</label>
															<input type="text" class="form-control input-sm" name="LABEL_3" id="LABEL_3" value="">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Label 4</label>
															<input type="text" class="form-control input-sm" name="LABEL_4" id="LABEL_4" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Label 5</label>
															<input type="text" class="form-control input-sm" name="LABEL_5" id="LABEL_5" value="">
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
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>

										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">
										
										<div id="divId_sub">
										</div>

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Tipo de Campanha</th>
													  <th>Abreviação</th>
													  <th>Ícone</th>
													  <th>Ativo</th>
													</tr>
												  </thead>
												<tbody> 
												  
												<?php 
												
													$sql = "select * from TIPOCAMPANHA order by NUM_ORDENAC";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {	
														$count++;	
														
														if ($qrBuscaModulos['LOG_ATIVO'] == 'S') {
															$mostraAtivo = '<i class="fa fa-check" aria-hidden="true"></i>';
														}else{ $mostraAtivo = ''; }		
														
														echo"
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='".$qrBuscaModulos['COD_TPCAMPA']."'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_TPCAMPA']."</td>
															  <td>".$qrBuscaModulos['NOM_TPCAMPA']."</td>
															  <td>".$qrBuscaModulos['ABV_TPCAMPA']."</td>
															  <td align='center'><span class='".$qrBuscaModulos['DES_ICONE']."' ></td>															  
															  <td align='center'>".$mostraAtivo."</td>															  
															</tr>
															<input type='hidden' id='ret_COD_TPCAMPA_".$count."' value='".$qrBuscaModulos['COD_TPCAMPA']."'>
															<input type='hidden' id='ret_NOM_TPCAMPA_".$count."' value='".$qrBuscaModulos['NOM_TPCAMPA']."'>
															<input type='hidden' id='ret_ABV_TPCAMPA_".$count."' value='".$qrBuscaModulos['ABV_TPCAMPA']."'>
															<input type='hidden' id='ret_DES_ICONE_".$count."' value='".$qrBuscaModulos['DES_ICONE']."'>
															<input type='hidden' id='ret_NUM_ORDENAC_".$count."' value='".$qrBuscaModulos['NUM_ORDENAC']."'>
															<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrBuscaModulos['LOG_ATIVO']."'>
															<input type='hidden' id='ret_LABEL_1_".$count."' value='".$qrBuscaModulos['LABEL_1']."'>
															<input type='hidden' id='ret_LABEL_2_".$count."' value='".$qrBuscaModulos['LABEL_2']."'>
															<input type='hidden' id='ret_LABEL_3_".$count."' value='".$qrBuscaModulos['LABEL_3']."'>
															<input type='hidden' id='ret_LABEL_4_".$count."' value='".$qrBuscaModulos['LABEL_4']."'>
															<input type='hidden' id='ret_LABEL_5_".$count."' value='".$qrBuscaModulos['LABEL_5']."'>
															<input type='hidden' id='ret_COD_BENEFIC_".$count."' value='".$qrBuscaModulos['COD_BENEFIC']."'>
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
	
	<style>
	
	.grabbable {
		cursor: move; /* fallback if grab cursor is unsupported */
		cursor: grab;
		cursor: -moz-grab;
		cursor: -webkit-grab;
	}

	/* (Optional) Apply a "closed-hand" cursor during drag operation. 
	.grabbable:active { 
		cursor: grabbing;
		cursor: -moz-grabbing;
		cursor: -webkit-grabbing;
	}
	*/
	</style>
	
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
						execOrdenacao(arrayOrdem,3);
					
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
	</script>
	
	<script type="text/javascript">
		
        $(document).ready( function() {
			
			//arrastar 
			$('.grabbable').on('change', function(e) { 
				//console.log(e.icon);
				$("#DES_ICONE").val(e.icon);		
			});	

			$(".grabbable").click(function() {
				$(this).parent().addClass('selected').siblings().removeClass('selected');

			});
			
			// //icon picker
			// $('.btnSearchIcon').iconpicker({ 
			// 	cols: 8,
			// 	iconset: 'fontawesome',   
			// 	rows: 6,
			// 	searchText: 'Procurar  &iacute;cone'
			// });	
			
			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});
			
        });

				
		function retornaForm(index){
			$("#formulario #COD_TPCAMPA").val($("#ret_COD_TPCAMPA_"+index).val());
			$("#formulario #NOM_TPCAMPA").val($("#ret_NOM_TPCAMPA_"+index).val());
			$("#formulario #ABV_TPCAMPA").val($("#ret_ABV_TPCAMPA_"+index).val());
			$('#btniconpicker').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val()); 
			$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_"+index).val());
			$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_"+index).val());
			$("#formulario #LABEL_1").val($("#ret_LABEL_1_"+index).val());
			$("#formulario #LABEL_2").val($("#ret_LABEL_2_"+index).val());
			$("#formulario #LABEL_3").val($("#ret_LABEL_3_"+index).val());
			$("#formulario #LABEL_4").val($("#ret_LABEL_4_"+index).val());
			$("#formulario #LABEL_5").val($("#ret_LABEL_5_"+index).val());
			$("#formulario #COD_BENEFIC").val($("#ret_COD_BENEFIC_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   