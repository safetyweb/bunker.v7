<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	$itens_por_pagina=50;
	if(isset($_POST['COD_EMPRESA']))
	{

		$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
		$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
		$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
		$num_cartao  = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
		$num_celular  = fnLimpacampo($_REQUEST['NUM_CELULAR']);	
		$des_emailus  = fnLimpacampo(trim($_REQUEST['DES_EMAILUS']));	
		$num_cgcecpf  = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));	
		$pagina  = fnLimpacampo($_REQUEST['pagina']);	
	 
	}else{ 

		$cod_empresa = "";
		$cod_empresaCode = "";
		$cod_cliente  = "";
		$nom_cliente  = "";
		$pagina  = "1";

		if (is_numeric(fnLimpacampo(fnDecode($_GET['idE'])))){
			//busca dados da empresa
			$cod_empresa = fnDecode($_GET['idE']);	
		}      
		
	}
	
	
  
  //fnEscreve($cod_cliente); 	
  //fnMostraForm();

?> 
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"><?php echo $NomePg ?></span>
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
															<label for="inputName" class="control-label required">Empresa</label>
                                                                                                                        
																<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" required="required" >
																	<option value=""></option>					
																	<?php																	
																		
																		if ($_SESSION["SYS_COD_MASTER"] == 2 ) {
																			$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																			from empresas A where A.cod_empresa <> 1 and A.cod_exclusa = 0 order by A.NOM_FANTASI 
																			";
                                                                                                                                                      
																		}else {
																			$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																			from empresas A where A.COD_EMPRESA IN (1,".$_SESSION["SYS_COD_MULTEMP"].") and A.cod_exclusa = 0 order by A.NOM_FANTASI 
																			";
																		}																	
																		
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																																	
																		while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			if ((int)$qrListaEmpresa['COD_DATABASE'] == 0){ $desabilitado = "disabled";}
																			else {$desabilitado = "";}
																			
																			echo"
																				  <option value='".fnEncode($qrListaEmpresa['COD_EMPRESA'])."' ".$desabilitado." >".$qrListaEmpresa['NOM_FANTASI']."</option> 
																				"; 
																		  }											
																	?>	
																</select>
                                                                                                         
																<script>$("#formulario #COD_EMPRESA").val("<?php echo $cod_empresaCode; ?>").trigger("chosen:updated"); </script>	
																<div class="help-block with-errors"></div>																
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Interno</label>
															<input type="text" class="form-control input-sm"  name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
														</div>
													</div>
	
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cartão</label>
															<input type="text" class="form-control input-sm"  name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
														</div>
													</div>
	
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">CPF/CNPJ</label>
															<input type="text" class="form-control input-sm cpfcnpj"  name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Cliente</label>
															<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Email</label>
															<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>

												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Celular</label>
                                                            <input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php fnCorrigeTelefone($num_celular); ?>" id="NUM_CELULAR" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="ADD" id="ADD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Novo Cliente</button>
											  <button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
											
										</div>
										
										<?php
										if (!is_null($RedirectPg)) {
											$DestinoPg = fnEncode($RedirectPg);		
										}else {
											$DestinoPg = "";		
										}

										if($cod_empresa == 136){
											$DestinoPg = fnEncode(1423);
										}
										?>											
										
										<input type="hidden" name="dId" id="dId" value="K2xr0lE3UHI¢">
										<input type="hidden" name="dKey" id="dKey" value="<?php echo $cod_empresaCode; ?>">
										<input type="hidden" name="dUrl" id="dUrl" value="<?php echo $DestinoPg; ?>">
										<input type="hidden" name="pagina" id="pagina" value="<?php echo $pagina; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<?php 
											if ($cod_empresa != 0 ){
												
												$pagina = (isset($_REQUEST['pagina']))? $_REQUEST['pagina'] : 1;
													
												if ($cod_cliente!=0){
													$andCodigo = 'and cod_cliente='.$cod_cliente; }
													else { $andCodigo = ' ';}
																								  
												if ($nom_cliente!=''){ 
													 $andNome = 'and nom_cliente like "'.$nom_cliente.'%"';	} 
													else {$andNome = ' '; } 
													
												if ($num_cartao!=''){ 													
													 $andCartao = 'and num_cartao='.$num_cartao; }
													else {$andCartao = ' '; } 
													
												if ($num_cgcecpf!=''){ 
													 $andCpf = 'and num_cgcecpf ='.$num_cgcecpf; }
													else {$andCpf = ' '; } 
													
												$sql = "SELECT 1 FROM  ".connTemp($cod_empresa,'true').".clientes where cod_empresa = ".$cod_empresa." 
                                                                                                        ".$andCodigo."
                                                                                                        ".$andNome."
                                                                                                        ".$andCartao."
                                                                                                        ".$andCpf."
                                                                                                        ORDER BY NOM_CLIENTE ";
                                            //fnEscreve($sql);
											//fnEscreve($sql);
											$resPagina = mysqli_query(connTemp($cod_empresa,''),$sql);
											$total = mysqli_num_rows($resPagina);
											//seta a quantidade de itens por página, neste caso, 2 itens
                                                                                        //fnEscreve($total['CONTADOR']);
											//calcula o número de páginas arredondando o resultado para cima
											$numPaginas = ceil($total/$itens_por_pagina);
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina*$pagina)-$itens_por_pagina;
											
											}		
										
										?>
	

<style>

	input[type="search"]::-webkit-search-cancel-button {
		height: 16px;
		width: 16px;
		background: url(images/close-filter.png) no-repeat right center;
		position: relative;
		cursor: pointer;
	}
	
	input.tableFilter {
		border: 0px;
		background-color: #fff;
	}
	
			
</style>

									
									
	
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												<form name="formLista" id="formLista" method="post" action="">
                                                                                                 
                                                                                                   
												<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
												  <!--
												  <thead> 
													<tr>
													  <td class="bg-primary" colspan="100"><input type="search" name="filter" id="filter" class="input-sm tableFilter text-primary pull-right" style="height: 25px;" value=""></td>
													</tr>
												  </thead>
												  -->
                                                <thead>
													<tr>
													  <th class="{sorter:false}" width="40"></th>
													  <th >Código</th>
													  <th >Cartão</th>
													  <th >Nome do Cliente</th>
													  <th >e-Mail</th>
													  <th >CPF</th>
													</tr>
												    </thead>
                                                <tbody id="relatorioConteudo">
												  												  
												<?php
												
													if ($cod_empresa != 0 ){
																										
													if ($cod_cliente!=0){
														$andCodigo = 'and cod_cliente='.$cod_cliente; }
														else { $andCodigo = ' ';}
																									  
													if ($nom_cliente!=''){ 
														 $andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';	} 
														else {$andNome = ' '; } 
														
													if ($num_cartao!=''){ 													
														 $andCartao = 'and num_cartao='.$num_cartao; }
														else {$andCartao = ' '; }

													if ($des_emailus!=''){ 													
														 $andEmail = 'and des_emailus="'.$des_emailus.'"'; }
														else {$andEmail = ' '; }

													if ($num_celular!=''){ 													
														 $andCelular = 'and (num_celular="'.fnLimpaDoc($num_celular).'" or num_celular="'.$num_celular.'")'; }
														else {$andCelular = ' '; } 
														
													if ($num_cgcecpf!=''){ 
														 $andCpf = 'and num_cgcecpf ='.$num_cgcecpf; }
														else {$andCpf = ' '; } 
													
													$sql = "select * from clientes where cod_empresa = ".$cod_empresa." 
                                                                                                        ".$andCodigo."
                                                                                                        ".$andNome."
                                                                                                        ".$andCartao."
                                                                                                        ".$andCpf."
                                                                                                        ".$andEmail."
                                                                                                        ".$andCelular."
											                             order by NOM_CLIENTE limit $inicio,$itens_por_pagina";
																		 
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													// fnEscreve($sql);
                                                    //echo "___".$sql."___";
													$count=0;
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;                                                                                                              					  
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrListaEmpresas['COD_CLIENTE']."</td>
															  <td>".fnMascaraCampo($qrListaEmpresas['NUM_CARTAO'])."</td>
															  <td>".fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE'])."</td>
															  <td>".fnMascaraCampo($qrListaEmpresas['DES_EMAILUS'])."</td>
															  <td>".fnMascaraCampo(fnformatCnpjCpf(fnCompletaDoc($qrListaEmpresas['NUM_CGCECPF'],$qrListaEmpresas['TIP_CLIENTE'])))."</td>
															</tr>
															<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".fnEncode($qrListaEmpresas['COD_CLIENTE'])."'>
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".fnEncode($cod_empresa)."'>
															"; 
														  }											
												}	
												?>
													
												</tbody>
												<?php if ($cod_empresa != 0) {  ?>							
                                                                                                        <tr>
                                                                                                            <th class="" colspan="100">
                                                                                                                 <center><ul id="paginacao" class="pagination-sm"></ul></center>
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                </tfoot>
												<?php }  //fnEscreve($cod_empresa); ?>

												</table>
												
												<div class="push"></div>
												
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
                        var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			

			$(document).keypress(function(event){
			    var keycode = (event.keyCode ? event.keyCode : event.which);
			    if(keycode == '13'){
			        $('#BUS').click();   
			    }
			});

			var SPMaskBehavior = function (val) {
			  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
			  onKeyPress: function(val, e, field, options) {
				  field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};			
			
			$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
	
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
			
		$(document).on('change', '#COD_EMPRESA', function(){ 
		   $("#dKey").val($("#COD_EMPRESA").val());
		});	

	
		function page(index){
			
			$("#pagina").val(index);
			$( "#formulario" )[0].submit();   			
			//alert(index);	
				
		}
		
		function retornaForm(index){
				
			$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_COD_EMPRESA_"+index).val()+'&idC='+$("#ret_COD_CLIENTE_"+index).val());					
			$("#formulario #hHabilitado").val('S');
			$( "#formulario" )[0].submit();   			
			
		}
                function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxLinkEmpresasClientes.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);
					$(".tablesorter").trigger("updateAll");										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}
	
	</script>
	