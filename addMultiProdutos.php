	
<?php

	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	//$cod_externo = 0;
	$des_produto = "";
	$itens_por_pagina = 100;
	$pagina = 1;
	
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
			
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
			$cod_categor = fnLimpacampoZero($_REQUEST['COD_CATEGOR']);
			$cod_subcate = fnLimpacampoZero($_REQUEST['COD_SUBCATE']);
			$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
			$cod_laborat = fnLimpacampo($_REQUEST['COD_LABORAT']);
			$des_produto = $_REQUEST['DES_PRODUTO'];
			if (empty($_REQUEST['LOG_EXATO'])) {$log_exato='N';}else{$log_exato=$_REQUEST['LOG_EXATO'];}

			// ROTINA DE ATRIBUTOS ADICIONADA 24/01/2022 - Ricardo

			for ($i=1; $i<=13 ; $i++) { 
				$ATRIBUTO = "ATRIBUTO{$i}";
     			$$ATRIBUTO = $_REQUEST["ATRIBUTO".$i];
			}

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){
			
				//echo $sql;
					
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Pesquisa realizada com <strong>sucesso!</strong>";		
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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
		
		/*
		$sql = "SELECT A.*,
		(select B.NOM_EMPRESA FROM empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA
		FROM EMPRESACOMPLEMENTO A where A.COD_EMPRESA = '".$cod_empresa."' ";
		*/
		$sql = "select  A.*,B.NOM_EMPRESA as  NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '".$cod_empresa."' ";		
		
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
 
		if (isset($arrayQuery)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
			
												
	}else {
		$cod_empresa = 0;		
		
	}

	if($log_exato == 'S'){
		$checkExato = "checked";
	}else{
		$checkExato = "";
	}

	// if(isset($_GET['P']) || isset($_GET['exP'])){
	// 	$des_produto = fnDecode($_GET['P']);
	// 	$cod_externo = fnDecode($_GET['exP']);
	// 	fnEscreve($des_produto);
	// 	fnEscreve($cod_externo);

	// }
	
	//fnMostraForm();
	// fnEscreve($des_produto);
	//fnEscreve($cod_externo);
		
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
									
									<div class="push10"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
								
										<ul class="nav nav-tabs">
										  <li class="active"><a data-toggle="tab" href="#produtos">Produtos</a></li>
										  <li><a data-toggle="tab" id="BTN_INFO" href="#info">Informações Adicionais</a></li>
										</ul>
										
										<div class="tab-content">										
										
											<!-- aba produtos -->
											<div id="produtos" class="tab-pane active">
											
												<div class="push30"></div>
																
												<div class="row">
													
													<div class="col-md-8">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Produto</label>
															<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50" data-error="Campo obrigatório" value="<?=$des_produto?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="250" data-error="Campo obrigatório" value="<?=$cod_externo?>">
															<div class="help-block with-errors">para pesquisa múltipla, utilizar virgula ", " ex: 123, 456, 789 </div>
														</div>
													</div>
													
												</div>

												<div class="row">
													
													<div class="col-md-4">   
														<div class="form-group">
															<input type="checkbox" name="LOG_EXATO" id="LOG_EXATO" class="switch" value="S" <?=$checkExato?>>
															<label for="LOG_EXATO" class="control-label">Buscar pelo produto exato</label> 
															<!-- <div class="push5"></div> -->
																<!-- <label class="switch"> -->
																<!-- <span></span> -->
																<!-- </label> -->
														</div>
													</div>

												</div>
												
												<div style="display: none;">	
												<div class="row">
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Grupo do Produto</label>
																<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>											  
																	<?php
																		$sql = "select * from CATEGORIA order by DES_CATEGOR";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																		
																		while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaCategoria['COD_CATEGOR']."'>".$qrListaCategoria['DES_CATEGOR']."</option> 
																				"; 
																			  }	
																	?>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Sub Grupo do Produto</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																</select>	
																</div>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
															
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Fornecedor</label>
															<input type="text" class="form-control input-sm" name="COD_LABORAT" id="COD_LABORAT" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
												</div>																																		
												</div>															
														
														
																	
											</div>
										
											<!-- aba info adicional -->
											<div id="info" class="tab-pane fade">
											
																											
																		
											</div>
										
										
										</div>	
							
																			
										<div class="push10"></div>
										<hr>
					
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp; Todos os Produtos</button>
													<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>														
												</div>
											</div>											
											
											<div class="col-md-6">
												<div class="form-group text-right">
													<button type="button" name="addProdutos" id="addProdutos" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar produtos</button>
												</div>
											</div>											
										</div>										
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<?php
											// ROTINA DE ATRIBUTOS ADICIONADA 24/01/2022 - Ricardo
											for ($i=1; $i<=13 ; $i++) { 
												$ATRIBUTO = "ATRIBUTO{$i}";
								     	?>
								     			<input type="hidden" name="AJX_<?=$ATRIBUTO?>" id="AJX_<?=$ATRIBUTO?>" value="<?=$$ATRIBUTO?>">
								     	<?php 
											}
										?>
										<input type="hidden" name="LOAD_INFO" id="LOAD_INFO" value="S">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th class="text-center" width="50"><input type='checkbox' id="selectAll"></th>
													  <th>Código</th>
													  <th>Cod. Externo</th>
													  <th>Grupo</th>
													  <th>Sub Grupo</th>
													  <th>Descrição</th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">	
												
												
												<?php 
														
													if ($des_produto != "" ){
														$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
                                                                                                                    
														else { $andProduto = ' ';}
														
													if ($cod_externo  != "" ){
														$andExterno = "AND A.COD_EXTERNO IN($cod_externo)"; }
														else { $andExterno = ' ';}

													if($log_exato == 'S'){
														$andProduto = "AND A.DES_PRODUTO = '$des_produto'";
													}

													// ROTINA DE ATRIBUTOS ADICIONADA 24/01/2022 - Ricardo

													$andAtributos = "";

													for ($i=1; $i<=13 ; $i++) { 
														$ATRIBUTO = "ATRIBUTO{$i}";
										     			if($$ATRIBUTO != ""){
										     				$andAtributos .= " AND $ATRIBUTO = ".$$ATRIBUTO;
										     			} 
													}
																																
													$sql="SELECT COUNT(*) as contador from PRODUTOCLIENTE A 
															left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
															left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
															where A.COD_EMPRESA='".$cod_empresa."' 
															".$andProduto."
															".$andExterno." 
															".$andAtributos." 
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
													
													$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
													$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
													$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);										
														
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
												                                                        
													$sql1="SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
														LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='".$cod_empresa."' 
														".$andProduto."
														".$andExterno." 
														".$andAtributos." 
														AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";
                                                                                                        
													// fnEscreve($sql1);
													//fnEscreve($cod_empresa);
													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);
													
													$count=0;
													while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
													{														  
														$count++;
														echo "
															<tr>
															  <td class='text-center'><input type='checkbox' name='radio_$count'>&nbsp;</td>
															  <td>".$qrListaProduto['COD_PRODUTO']."</td>
															  <td>".$qrListaProduto['COD_EXTERNO']."</td>
															  <td>".$qrListaProduto['GRUPO']."</td>
															  <td>".$qrListaProduto['SUBGRUPO']."</td>
															  <td>".$qrListaProduto['DES_PRODUTO']."</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
															<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrListaProduto['COD_EXTERNO']."'>
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
															<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaProduto['COD_CATEGOR']."'>
															<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaProduto['COD_SUBCATE']."'>
															<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrListaProduto['COD_FORNECEDOR']."'>
															";                                                
                                                    }
													
												?>
													
												</tbody>
												
												<tfoot>
													<tr>
													  <th class="" colspan="100">
														<center><ul id="paginacao" class="pagination-sm"></ul></center>
													  </th>
													</tr>
												</tfoot>
																								
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

	
	<script type="text/javascript">

		$("#BTN_INFO").click(function(){
			if($("#LOAD_INFO").val() == "S"){
				$.ajax({
					type: "POST",
					url: "ajxInfoProdPersona.do?id=<?=fnEncode($cod_empresa)?>",
					// data:$("#formulario").serialize(),
					beforeSend:function(){
						$('#info').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						$("#info").html(data);
						$("#LOAD_INFO").val("N");					
					},
					error:function(){
					}
				});
			}
		});
	
		$(document).ready(function(){
			
		// $("#ATRIBUTO1").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO2").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO3").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO4").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO5").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO6").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO7").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO8").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO9").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO10").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO11").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO12").chosen({ width: "100%" }); 			
		// $("#ATRIBUTO13").chosen({ width: "100%" });		
			
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			$("#addProdutos").click(function() {
				var listaProdutos = new Array();
				$("table tr").each(function(index) {
					if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){
						var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
						listaProdutos.push(retornaForm(codigo));
						console.log(listaProdutos);
					}
				});			
				
				$.ajax({
					type: "POST",
					url: "ajxPersonasProdutos.php?acao=addProdutos&cod_empresa=<?php echo $cod_empresa; ?>",
					data:{listaProdutos:JSON.stringify(listaProdutos)},
					success:function(data){
						//console.log(data);
						$.confirm({
							title: '<small>Sucesso</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Produtos incluídos com sucesso!',							
							buttons: {
								fechar: function () {
									parent.atualizarTable();
								}									
							}
						});	
					},
					error:function(){
					}
				});
				
				/**
				$.ajax({
					type: "GET",
					url: "ajxPersonasProdutos.php",
					data: $('#formulario').serialize() + '&acao=proc&opcao=' + $(this).attr('name') + '&cod_empresa=' + $("#COD_EMPRESA").val(),
					beforeSend:function(){
						//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						//console.log(data); 
						//alert('cadastro feito com sucesso');
						$.confirm({
							title: '<small>Produtos da Persona</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Registro atualizado com sucesso!',							
							buttons: {
								fechar: function () {
									atualizarTable();
								}									
							}
						});	
						
					},
					error:function(){
						//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});**/
				
			});			
		});	
				
		// ajax
		$("#COD_CATEGOR").change(function () {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca,0,codBusca3);
		});

		$('#selectAll').click(function () {
		    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		});

		function buscaSubCat(idCat,idSub,idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupo.php",
				data: { ajx1:idCat,ajx2:idSub,ajx3:idEmp},
				beforeSend:function(){
					$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_sub").html(data); 
				},
				error:function(){
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
		
		function retornaForm(index){
			var newProduto = new Object()
			newProduto.COD_PERPROD = 0;
			newProduto.COD_PRODUTO = $("#ret_COD_PRODUTO_"+index).val();
			newProduto.COD_CATEGOR = $("#ret_COD_CATEGOR_"+index).val();
			newProduto.COD_SUBCATE = $("#ret_COD_SUBCATE_"+index).val();
			newProduto.COD_FORNECEDOR = $("#ret_COD_FORNECEDOR_"+index).val();
			newProduto.COD_PERSONA = parent.$("#COD_PERSONA").val();
			newProduto.COD_EMPRESA = <?php echo $cod_empresa; ?>;
			newProduto.OPCAO = 'CAD';
			
			return newProduto;
		}		
		
		function downForm(index){
			try { parent.$('#NOM_PRODTKT').val($("#ret_DES_PRODUTO_"+index).val()); } catch(err) {}		
			try { parent.$('#DES_PRODUTO').val($("#ret_DES_PRODUTO_"+index).val()); } catch(err) {}		
			try { parent.$('#COD_PRODUTO').val($("#ret_COD_PRODUTO_"+index).val()); } catch(err) {}	
			try { parent.carregarCombo($("#ret_COD_CATEGOR_"+index).val()); } catch(err) {}
			try { parent.$("#BL2_COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger("chosen:updated"); } catch(err) {}	
			try { parent.$("#COD_SUBCATE").val($("#ret_COD_SUBCATE_"+index).val()).trigger("chosen:updated"); } catch(err) {}	
			$(this).removeData('bs.modal');	
			//console.log('entrou' + index);
			parent.$('#popModalAux').modal('hide');
		}	

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxAddMultiProdutos.php?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(data){
					//console.log(data);
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					$("#relatorioConteudo").append(data);	
				}
			});		
		}
		
		
	</script>	