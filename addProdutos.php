	
<?php

	echo fnDebug('true');

	$hashLocal = mt_rand();	
	$cod_externo = 0;
	$des_produto = "";
	
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
			$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);

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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
 
		if (isset($arrayQuery)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
			
												
	}else {
		$cod_empresa = 0;		
		
	}      
	
	//fnMostraForm();
	//fnEscreve($des_produto);
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
																	
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													
													<div class="col-md-9">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Produto</label>
															<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="50" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
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
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																		
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
												
										</fieldset>	
																			
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
													  <th class="bg-primary" width="50"></th>
													  <th class="bg-primary">Código</th>
													  <th class="bg-primary">Cod. Externo</th>
													  <th class="bg-primary">Grupo</th>
													  <th class="bg-primary">Sub Grupo</th>
													  <th class="bg-primary">Descrição</th>
													</tr>
												  </thead>
												<tbody>	
												
												
												<?php 
													$pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
														
													if ($des_produto != "" ){
														$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
                                                                                                                    
														else { $andProduto = ' ';}
														
													if ($cod_externo  != "" ){
														$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
														else { $andExterno = ' ';}
																						
													$sql="select COUNT(*) as contador from PRODUTOCLIENTE A 
															left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
															left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
															where A.COD_EMPRESA='".$cod_empresa."' 
															".$andProduto."
															".$andExterno." 
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
													
													$resPagina = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													$total = mysqli_fetch_assoc($resPagina);
													//seta a quantidade de itens por página, neste caso, 2 itens
													$registros =100;
													//calcula o número de páginas arredondando o resultado para cima
													$numPaginas = ceil($total['contador']/$registros);
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($registros*$pagina)-$registros;
												                                                        
													$sql1="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
														LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='".$cod_empresa."' 
														".$andProduto."
														".$andExterno." 
														AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$registros";
                                                                                                        
													//fnEscreve($sql1);
													//fnEscreve($cod_empresa);
													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
													
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
															";                                                
                                                    }
													
												?>
													
												</tbody>
												
												<tfoot>
													<tr>
													  <th colspan="100" style="text-align: justify;"><ul class="pagination pagination-sm pull-right">
													  <?php 
														for($i = 1; $i < $numPaginas + 1; $i++) {
															if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
														echo "<li class='pagination $paginaAtiva'><a href='{$_SERVER['PHP_SELF']}?mod=".fnEncode(1062)."&id=".fnEncode($cod_empresa)."&pagina=$i&pop=true' style='text-decoration: none;'>".$i."</a></li>";   
														}													  
													  ?></ul>
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
	
		$(document).ready(function(){
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			$("#addProdutos").click(function() {
				var listaProdutos = new Array();
				$("table tr").each(function(index) {
					if($(this).find("input[type='checkbox']").is(':checked')){
						var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
						listaProdutos.push(retornaForm(codigo));
					}
				});			
				
				$.ajax({
					type: "GET",
					url: "ajxPersonasProdutos.php",
					data:'listaProdutos='+JSON.stringify(listaProdutos)+'&acao=addProdutos&cod_empresa=<?php echo $cod_empresa; ?>',
					success:function(data){
						$.confirm({
							title: '<small>Sucesso</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Produtos incluídos com sucesso!',							
							buttons: {
								fechar: function () {
									//atualizarTable();
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
			newProduto.COD_FORNECEDOR = parent.$("#BL2_COD_FORNECEDOR").val();
			newProduto.COD_CATEGOR = parent.$("#BL2_COD_CATEGOR").val(); 
			newProduto.COD_SUBCATE = parent.$("#BL2_COD_SUBCATE").val();
			newProduto.COD_PERSONA = 0;
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
		
		
	</script>	