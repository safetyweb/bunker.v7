<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		$request = md5(implode( $_POST ));
		
		if(isset($_SESSION['last_request']) && $_SESSION['last_request']== $request)
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$dat_admissao = fnDataSql($_POST['DAT_ADMISSAO']);
			$qtd_diasoff = fnLimpaCampoZero($_REQUEST['QTD_DIASOFF']);
			$qtd_cadastros = fnLimpaCampoZero($_REQUEST['QTD_CADASTROS']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){ 						
				 
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE CLIENTES SET 
					    		DAT_ADMISSAO='$dat_admissao',
					    		QTD_DIASOFF='$qtd_diasoff',
					    		QTD_CADASTROS='$qtd_cadastros'
					    		WHERE COD_CLIENTE=$cod_cliente
					    		AND COD_EMPRESA = $cod_empresa
					    		";

			    		mysqli_query(connTemp($cod_empresa,''),$sql);

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
    
	//echo($sql);	
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	// fnEscreve($cod_empresa);
	
	//fnMostraForm(); 
function mask($val, $mask){
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++) {
   if($mask[$i] == '#') {
     if(isset($val[$k]))
       $maskared .= $val[$k++];
       } else {
       if(isset($mask[$i]))  
         $maskared .= $mask[$i];
       }
   }
 return $maskared;
}
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
									
									<?php 
									$formBack = "1019";
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php 
									//manu superior - empresas
									$abaEmpresa = 1524;	include "abasGabinete.php";
																			
									?>
									
									<div class="push30"></div>
			
								<div class="login-form">
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Categoria</legend> 
											
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Indicador</label>
															<input type="text" class="form-control input-sm leituraOff " name="NOM_CLIENTE" id="NOM_CLIENTE" readonly required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Data de Admissão</label>
															<input type="text" class="form-control input-sm" name="DAT_ADMISSAO" id="DAT_ADMISSAO" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
                                                                                                    <script>$("#DAT_ADMISSAO").mask("99/99/9999"); </script>
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Treinamento</label>
																<select data-placeholder="Selecione um grupo" name="QTD_DIASOFF" id="QTD_DIASOFF" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<option value="10"> 10 dias</option>					
																	<option value="20"> 20 dias</option>					
																	<option value="30"> 30 dias</option>					
																	<option value="45"> 45 dias</option>					
																	<option value="60"> 60 dias</option>					
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cadastros por dia</label>
															<input type="text" class="form-control input-sm text-center int" name="QTD_CADASTROS" id="QTD_CADASTROS" maxlength="5" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													

												</div>

										</fieldset>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <!-- <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> -->
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<!--<input type="hidden" name="COD_TPEVENT" id="COD_TPEVENT" value="<?php echo $cod_plataf; ?>">-->
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
									</form>

										<div class="push5"></div>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Indicador</th>
													  <th>Data Admissão</th>
													</tr>
												  </thead>

												<tbody>
													<?php 
													$sql = "SELECT COD_CLIENTE,NOM_CLIENTE, DAT_ADMISSAO, QTD_DIASOFF,QTD_CADASTROS 
															FROM clientes
															WHERE cod_cliente IN(
															SELECT distinct cod_indicad FROM clientes
															WHERE cod_empresa = $cod_empresa
															)
															AND cod_empresa = $cod_empresa
															ORDER BY nom_cliente ";
													
													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
													  	$count++;
													  ?>
														<tr>
															<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
															<td><?php echo $qrBuscaModulos['COD_CLIENTE']; ?></td>
															<td><?php echo $qrBuscaModulos['NOM_CLIENTE']; ?></td>
															<td><?php echo fnDataFull($qrBuscaModulos['DAT_ADMISSAO']); ?></td>
														</tr>

														<input type='hidden' id='ret_COD_CLIENTE_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_CLIENTE']; ?>'>
														<input type='hidden' id='ret_NOM_CLIENTE_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['NOM_CLIENTE']; ?>'>
														<input type='hidden' id='ret_QTD_DIASOFF_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['QTD_DIASOFF']; ?>'>
														<input type='hidden' id='ret_QTD_CADASTROS_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['QTD_CADASTROS']; ?>'>
														<input type='hidden' id='ret_DAT_ADMISSAO_<?php echo $count; ?>' value='<?php echo fnDataFull($qrBuscaModulos['DAT_ADMISSAO']); ?>'>
														<?php }?>
													
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
	<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
	<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
	
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
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
						execOrdenacao(arrayOrdem,5);
					
						function execOrdenacao(p1,p2) {
							//alert(p1);
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
			
			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
			//icon picker
			$('.btnSearchIcon').iconpicker({ 
				cols: 8,
				iconset: 'fontawesome',   
				rows: 6,
				searchText: 'Procurar  &iacute;cone'
			});	
			
			$('.btnSearchIcon').on('change', function(e) { 
				//console.log(e.icon);
				$("#DES_ICONE").val(e.icon);		
			});	
			
        });

		function retornaForm(index){
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
			$("#formulario #DAT_ADMISSAO").val($("#ret_DAT_ADMISSAO_"+index).val());
			$("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_"+index).val());
			$("#formulario #QTD_DIASOFF").val($("#ret_QTD_DIASOFF_"+index).val()).trigger("chosen:updated");
			$("#formulario #QTD_CADASTROS").val($("#ret_QTD_CADASTROS_"+index).val());
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	