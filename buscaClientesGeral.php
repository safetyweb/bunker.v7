<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{

		//$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
		//$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
		$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
		$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
		$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
	 
	}else{ 

		//$cod_empresa = 0;
		//$cod_empresaCode = 0;
		$cod_cliente  = 0;
		$nom_cliente  = "";

	} 
	
	$cod_empresa = fnDecode($_GET['id']);

	if (isset($_GET['op'])) {
			$opcao = fnLimpacampo($_GET['op']);
			$cod_indicado = fnLimpacampoZero(fnDecode($_GET['idC']));
		}else{
			$cod_indicado = 0;
		}	
  
  //fnEscreve($cod_indicado);
  //fnEscreve($nom_cliente); 	
  //fnMostraForm();

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
								
									<div class="login-form">
                                        
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>"> 
																				
										<fieldset>
											<legend>Dados para Pesquisa</legend> 
											
												<div class="row">
							
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm"  name="COD_CLIENTE" id="COD_CLIENTE" value="">
														</div>
													</div>
	
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Cliente</label>
															<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">CPF/CNPJ</label>
															<input type="text" class="form-control input-sm" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Número do Cartão</label>
                                                             <input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="18">
															<div class="help-block with-errors"></div>
														</div>
													</div>

																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
											
										</div>
										
										<?php
										if (!is_null($RedirectPg)) {
											$DestinoPg = fnEncode($RedirectPg);		
										}else {
											$DestinoPg = "";		
											}
										?>											
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<?php 
											
											if ($_SERVER['REQUEST_METHOD']=='POST'){
											//if ($cod_empresa != 0 ){
												
												$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
													
												if ($cod_cliente!=0){
													$andCodigo = 'and cod_cliente='.$cod_cliente; }
													else { $andCodigo = ' ';}

												if ($num_cartao!=0){
													$andNumCartao = 'and num_cartao='.$num_cartao; }
													else { $andNumCartao = ' ';}

												if ($num_cgcecpf!=0){
													$andcpf = 'and num_cgcecpf='.$num_cgcecpf; }
													else { $andcpf = ' ';}
																								  
												if ($nom_cliente!=''){ 
													 $andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';	} 
													else {$andNome = ' '; } 
												$sql = "select count(COD_CLIENTE) as CONTADOR from  $connUser->DB.clientes where cod_empresa = ".$cod_empresa." 
                                                                                                                                                    ".$andCodigo."
                                                                                                                                                    ".$andNome."
                                                                                                                                                    ".$andNumCartao."
                                                                                                                                                    ".$andcpf."
                                                                                                                                                    order by NOM_CLIENTE ";
											//fnEscreve($sql);
											
											$resPagina = mysqli_query($connUser ->connUser(),$sql) or die(mysqli_error());
											$total = mysqli_fetch_assoc($resPagina);
											//seta a quantidade de itens por página, neste caso, 2 itens
											$registros =100;
                                                                                        //fnEscreve($total['CONTADOR']);
											//calcula o número de páginas arredondando o resultado para cima
											$numPaginas = ceil($total['CONTADOR']/$registros);
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($registros*$pagina)-$registros;
											
											} else {
											$numPaginas = 1;	
											}		
											
										if ($_SERVER['REQUEST_METHOD']=='POST'){	
										?>
		
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												<form name="formLista" id="formLista" method="post" action="">
									
												<table class="table table-bordered table-striped table-hover" id="tablista">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Cartão</th>
													  <th>Nome do Cliente</th>
													  <th>e-Mail</th>
													  <th>CPF</th>
													</tr>
												  </thead>
												<tbody>
												  												  
												<?php
												if ($_SERVER['REQUEST_METHOD']=='POST'){
												//if ($cod_empresa != 0 ){
                                                                                                    
													if ($cod_cliente!=0){
														$andCodigo = 'and cod_cliente='.$cod_cliente;
                                                        }
                                                                                                      
													if ($nom_cliente!=''){ 
														 $andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';														
													} 
													$sql = "SELECT COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, NUM_CGCECPF from clientes where cod_empresa = ".$cod_empresa." 
                                                                                                                ".$andCodigo."
                                                                                                                ".$andNome."
                                                                                                                ".$andNumCartao."
                                                                                                                ".$andcpf."
                                                                                                                order by NOM_CLIENTE limit $inicio,$registros";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																										
													$count=0;
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														?>								  
														
															<tr>
															  <td><a href='action.php?mod=<?php echo fnEncode(1346)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrListaEmpresas['COD_CLIENTE'])?>&pop=true' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
															  <td><?=$qrListaEmpresas['COD_CLIENTE']?></td>
															  <td></td>
															  <td><?=$qrListaEmpresas['NOM_CLIENTE']?></td>
															  <td><?=$qrListaEmpresas['DES_EMAILUS']?></td>
															  <td><?=$qrListaEmpresas['NUM_CGCECPF']?></td>
															</tr>
															<input type='hidden' id='ret_ENCODE_<?=$count?>' value='<?=fnEncode($qrListaEmpresas['COD_CLIENTE'])?>'>
															<input type='hidden' id='ret_COD_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['COD_CLIENTE']?>'>
															<input type='hidden' id='ret_NOM_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['NOM_CLIENTE']?>'>
															<input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value='<?=$cod_empresa?>'>
															<?php
														  }											
												}	
												?>
													
												</tbody>
												<?php if ($cod_empresa != 0) {  ?>
												<tfoot>
													<tr>
													  <th colspan="100"><ul class="pagination pagination-sm pull-right">
													  <?php 
														for($i = 1; $i < $numPaginas + 1; $i++) {
														echo "<li class='pagination'><a href='{$_SERVER['PHP_SELF']}?mod=NN7xULiFM88¢&pagina=$i' style='text-decoration: none;'>".$i."</a></li>";   
														}													  
													  ?></ul>
													  </th>
													</tr>
												</tfoot>
												<?php }   ?>

												</table>
												
												<div class="push"></div>
												
												</form>

											</div>
											
										</div>
										<?php }   ?>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>
				

	<script type="text/javascript">	

		$(document).keypress(function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        $('#BUS').click();  
		    }
		});
	
	
		$(document).ready(function(){			
	
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
		
			//table sorter
			$(function() { 
			  var tabelaFiltro = $('table.tablesorter')
			  tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function(){
				$(this).prev().find(":checkbox").click()
			  });
			  $("#filter").keyup(function() {
				$.uiTableFilter( tabelaFiltro, this.value );
			  })
			  $('#formLista').submit(function(){
				tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
				return false;
			  }).focus();
			}); 

			//pesquisa table sorter
			$('.filter-all').on('input', function(e) {
				if('' == this.value) {
				var lista = $("#filter").find("ul").find("li");  
				filtrar(lista, "");
				}
			});			
				
		});	
			
		
		function retornaForm(index){
				
			$('#formulario').attr('action', 'action.php?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_COD_EMPRESA_"+index).val()+'&idC='+$("#ret_COD_CLIENTE_"+index).val());					
			$("#formulario #hHabilitado").val('S');
			$( "#formulario" )[0].submit();   			
			
		}
		
		function downForm(index){

			if("<?=$opcao?>" == "IND"){
				cod_cliente = <?=$cod_indicado?>,
				cod_indicador = $("#ret_COD_CLIENTE_"+index).val();

				$.ajax({
					type: "POST",
					url: "ajxClienteIndicador.php?id="+<?=$cod_empresa?>,
					data: {COD_INDICADOR:cod_indicador,COD_CLIENTE:cod_cliente},
					success:function(data){
						//console.log(data);

						try { 
							parent.$('#NOM_INDICA').val($("#ret_NOM_CLIENTE_"+index).val());
							parent.$('#NOM_INDICA').attr("readonly", "readonly");
							parent.$('#NOM_INDICA').addClass('leitura');
						} catch(err) {}		
						try { 
							parent.$('#COD_INDICA').val($("#ret_COD_CLIENTE_"+index).val()); 
							parent.$('#btnBuscaInd').hide();
						} catch(err) {}
						try { 
							parent.$('#DAT_INDICA').val(data); 
						} catch(err) {}
						$(this).removeData('bs.modal');	
						//console.log('entrou' + index);
						parent.$('#popModal').modal('hide');
													
					},
					error:function(){
						alert('Algo deu errado :(');
					}
				});

			}else{
				try { parent.$('#NOM_USUARIO').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}		
				try { parent.$('#COD_USUARIO').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}		
				try { parent.$('#NOM_CLIENTE').val($("#ret_NOM_CLIENTE_"+index).val()); } catch(err) {}			
				try { parent.$('#COD_CLIENTE').val($("#ret_COD_CLIENTE_"+index).val()); } catch(err) {}	
				try { parent.$('#NOVO_CLIENTE').val($("#ret_ENCODE_"+index).val()); } catch(err) {}	
				try { parent.$('#REFRESH_CLIENTE').val("S"); } catch(err) {}
				$(this).removeData('bs.modal');	
				//console.log('entrou' + index);
				parent.$('#popModal').modal('hide');
			}
			
					
		}	
	</script>
	
	