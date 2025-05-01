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

			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_tpevent = fnLimpaCampoZero($_REQUEST['COD_TPEVENT']);
			$des_tpevent = fnLimpaCampo($_REQUEST['DES_TPEVENT']);
			$abv_tpevent = fnLimpaCampo($_REQUEST['ABV_TPEVENT']);
			$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
			$des_cor = fnLimpaCampoHtml($_REQUEST['DES_COR']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){ 

				if ($opcao == 'CAD'){

				$sql = "INSERT INTO TIPO_EVENTO(
						COD_EMPRESA,
						DES_TPEVENT,
						ABV_TPEVENT,
						DES_COR,
						DES_ICONE
						) VALUES(
						$cod_empresa,
						'$des_tpevent',
						'$abv_tpevent',
						'$des_cor',
						'$des_icone'
						)";
	    		//fnEscreve($sql);
	    		mysqli_query(connTemp($cod_empresa,''),$sql);
	    		//mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

	    		}

	    		elseif ($opcao == 'EXC'){

	    		$sql = "DELETE FROM TIPO_EVENTO WHERE COD_TPEVENT = $cod_tpevent";

	    		mysqli_query(connTemp($cod_empresa,''),$sql);
	    		}

	    		else{

	    		$sql = "UPDATE TIPO_EVENTO SET 
			    		DES_TPEVENT='$des_tpevent',
			    		ABV_TPEVENT='$abv_tpevent',
			    		DES_COR='$des_cor',
			    		DES_ICONE='$des_icone'

			    		WHERE COD_TPEVENT=$cod_tpevent
			    		";

	    		mysqli_query(connTemp($cod_empresa,''),$sql);
			    		//fnTestesql($connAdm->connAdm(), $sql);

	    		}						
				 
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

?>
	<style type="text/css">
		#colorPick {
    		background: rgba(255, 255, 255, 1)!important;
    	}
    	#colorPick span {
    		color: #2c3e50!important;
    	}
    	.colorPickButton {
    		margin: 3px 3px!important;
    	}
	</style>		
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
									$abaEmpresa = 1401;	include "abasGabinete.php";
																			
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
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPEVENT" id="COD_TPEVENT" value="">
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Descrição</label>
															<input type="text" class="form-control input-sm" name="DES_TPEVENT" id="DES_TPEVENT" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>


													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="ABV_TPEVENT" id="ABV_TPEVENT" maxlength="3" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cor</label>
															<div class="picker" style="height: 35px; width: 100%;"></div>														
															<input type="hidden" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">															
														</div>														
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Ícone</label><br/>
																<button class="btn btn-sm btn-primary btnSearchIcon" id="btnIcon" style="min-height: 33px; margin-top: 1px;" data-icon=""></button>
																<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
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
													  <th>Descrição</th>
													  <th>Abreviação</th>
													  <th>ícone</th>												  
													</tr>
												  </thead>

												<tbody>
													<?php 
												
													$sql = "SELECT * FROM TIPO_EVENTO WHERE COD_EMPRESA = $cod_empresa";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {
													  	$count++;
													  ?>
														<tr>
															<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count;?>)'></td>
															<td><?php echo $qrBuscaModulos['COD_TPEVENT']; ?></td>
															<td><?php echo $qrBuscaModulos['DES_TPEVENT']; ?></td>
															<td><?php echo $qrBuscaModulos['ABV_TPEVENT']; ?></td>
															<td align='center'><span style='color: <?php echo $qrBuscaModulos['DES_COR'];?>;' class='<?php  echo $qrBuscaModulos['DES_ICONE'];?>' ></td>
														</tr>

														<input type='hidden' id='ret_COD_TPEVENT_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_TPEVENT']; ?>'>
														<input type='hidden' id='ret_DES_TPEVENT_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_TPEVENT']; ?>'>
														<input type='hidden' id='ret_ABV_TPEVENT_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['ABV_TPEVENT']; ?>'>
														<input type='hidden' id='ret_DES_COR_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_COR']; ?>'>
														<input type='hidden' id='ret_DES_ICONE_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_ICONE']; ?>'>
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
	
	<!-- <script src="js/plugins/minicolors/jquery.minicolors.min.js"></script> -->
    <!-- <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css"> -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="js/colorPick/colorPick.css">
	<script src="js/colorPick/colorPick.js"></script>
	
	
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
			// $('.pickColor').minicolors({
			// 	control: $(this).attr('data-control') || 'none',				
			// 	theme: 'bootstrap',
			// 	swatches: ["#F9EBEA","#FDEDEC","#F5EEF8","#F4ECF7","#EAF2F8","#EBF5FB","#E8F8F5","#E8F6F3","#E9F7EF","#EAFAF1","#FEF9E7","#FEF5E7","#FDF2E9","#FBEEE6","#FDFEFE","#F8F9F9","#F4F6F6","#F2F4F4","#EBEDEF","#EAECEE"]

			// });

			$(".picker").colorPick({
				'initialColor' : '#E6B0AA',
				'palette': ["#E6B0AA","#F5B7B1","#EBDEF0","#D2B4DE","#A9CCE3","#AED6F1","#A3E4D7","#A2D9CE","#A9DFBF","#ABEBC6","#F9E79F","#FAD7A0","#F5CBA7","#EDBB99","#F7F9F9","#E5E7E9","#D5DBDB","#CCD1D1","#AEB6BF","#ABB2B9"],
				'paletteLabel': 'Cores disponíveis',
				'allowRecent': false,
				'onColorSelected': function() {
					$("#DES_COR").val(this.color);
					this.element.css({'backgroundColor': this.color, 'color': this.color});
					// corChamado(<?=$qrSac['COD_CHAMADO']?>,this.color);
				}
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
			$("#formulario #COD_TPEVENT").val($("#ret_COD_TPEVENT_"+index).val());
			$("#formulario #DES_TPEVENT").val($("#ret_DES_TPEVENT_"+index).val());
			$("#formulario #ABV_TPEVENT").val($("#ret_ABV_TPEVENT_"+index).val());
			$("#formulario #DES_COR").val($("#ret_DES_COR_"+index).val());
			$(".picker").colorPick({ 'initialColor': $("#ret_DES_COR_"+index).val() });
			$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_"+index).val());
			$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	