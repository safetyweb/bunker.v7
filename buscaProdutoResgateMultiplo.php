<?php

	//echo fnDebug('true');

	$hashLocal = mt_rand();
	$itens_por_pagina = 50;
	$pagina  = "1";

	$cod_externo = 0;
	$des_produto = "";
	$cod_resgate = fnDecode($_GET['idR']);
	
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
			
			$cod_item = 0;
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
			
			
			$atributo1 = fnLimpacampo($_REQUEST['ATRIBUTO1']);
			$atributo2 = fnLimpacampo($_REQUEST['ATRIBUTO2']);
			
			if($_REQUEST['opcao'] != "CAD"){
				$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
				$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);
				$cod_categor = fnLimpacampoZero($_REQUEST['COD_CATEGOR']);
				$cod_subcate = fnLimpacampoZero($_REQUEST['COD_SUBCATE']);		
			}
			
			$qtd_produto = fnLimpacampo($_REQUEST['QTD_PRODUTO']);
			$val_unitario = fnLimpacampo($_REQUEST['VAL_UNITARIO']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){
				
				if ($opcao == 'CAD'){
					
					$sql = "CALL SP_ALTERA_AUXRESGATE (
					 '".$cod_item."', 
					 '".$cod_resgate."', 
					 '".$cod_produto."',
					 '".fnValorSql($qtd_produto)."', 
					 '".fnValorSql($val_unitario)."',
					 '".$cod_empresa."',
					 '".$opcao."'    
					) ";
					
					//echo $sql;				
					//fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());
				
				}	
				//echo $sql;
					
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
				?>		
						<script>
						try { parent.$('#REFRESH_PRODUTOS').val('S'); } catch(err) {}
						try { parent.$('#VAL_TOTPRODU').prop('required',false); } catch(err) {}
						</script>
		
				<?php						
						break;
					case 'BUS':
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
		$cod_univend = fnLimpacampoZero($_GET['idu']);			
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, TIP_RETORNO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
			if ($tip_retorno == 2){
				$casasDec = 2;
			}else { $casasDec = 0; }
		}else{
			$casasDec = 2;
		}
		
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
		$casasDec = 2;	
		
	}      
	
	//fnMostraForm();
	//fnEscreve($des_produto);
	//fnEscreve($cod_categor);
	//fnEscreve($cod_resgate);
		
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

										<div class="row">
										
											<div class="col-md-12">
										
											<fieldset>
												<legend>Dados Gerais / Pesquisa</legend> 
												
													<div class="row">
														
														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Cód. Externo</label>
																<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="50" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Grupo do Produto</label>
																	<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
																		<option value="">&nbsp;</option>											  
																		<?php
																			$sql = "select * from CAT_PROMOCAO where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
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
														
														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">Nome do Produto</label>
																<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50" data-error="Campo obrigatório" value="<?=$des_produto?>">
																<div class="help-block with-errors"></div>
															</div>
														</div>															
													</div>																																		
													
											</fieldset>	
											
											</div>	
										
										</div>
										
										<div class="push10"></div>	
											
										<div class="row">
											
											<div class="col-md-4 col-md-offset-8">											
												
												<fieldset>
													<legend>Dados do Lançamento</legend> 
													
														<div class="row">

															<div class="col-md-4">
																<div class="form-group">
																	<label for="inputName" class="control-label">Qtd.</label>
																	<input type="text" class="form-control input-sm text-center money" name="QTD_PRODUTO" id="QTD_PRODUTO" maxlength="50" data-error="Campo obrigatório">
																	<div class="help-block with-errors"></div>
																</div>
															</div>
															
															<div class="col-md-8">
																<div class="form-group">
																	<label for="inputName" class="control-label">Valor Unitário</label>
																	<input type="text" class="form-control input-sm text-right" name="VAL_UNITARIO" id="VAL_UNITARIO" maxlength="50" readonly>
																	<div class="help-block with-errors"></div>
																</div>
															</div>
															
														</div>
														
												</fieldset>	
												
											</div>
									
																			
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-left col-lg-12">
											
											<div class="pull-left">
												<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-cubes" aria-hidden="true"></i>&nbsp; Todos os Produtos</button>
												<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
											</div>

											<div class="pull-right">
												<button type="submit" name="CAD" id="CAD" class="btn btn-info getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Produto ao Lançamento</button>
											</div>
											
										</div>
															
										<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
										<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo fnEncode($cod_univend)?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										<input type="hidden" name="cod_item" id="cod_item" value="">
										<input type="hidden" name="cod_resgate" id="cod_resgate" value="<?php echo $cod_resgate; ?>">
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
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Descrição</th>
													  <th>Pontos</th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">
												
												<?php 
														
													if ($des_produto != "" ){
														$andProduto = 'AND DES_PRODUTO like "%'.$des_produto.'%"'; }
                                                                                                                    
														else { $andProduto = ' ';}
														
													if ($cod_externo  != ""){
														$andExterno = 'AND COD_EXTERNO = "'.$cod_externo.'"'; }
														else { $andExterno = ' ';}
																						
													if ($cod_categor  != ""){
														$andCategoria = 'AND COD_CATEGOR = "'.$cod_categor.'"'; }
														else { $andCategoria = ' ';}
														
													if ($cod_subcate  != ""){
														$andSubCategoria = 'AND COD_SUBCATE = "'.$cod_subcate.'"'; }
														else { $andSubCategoria = ' ';}	

													$sqlVerifica="SELECT COUNT(*) AS CONTROLA_ESTOQUE FROM ESTOQUE_PRODUTO
																	WHERE COD_EMPRESA = $cod_empresa AND 
																	IFNULL(cod_exclusa,0)=0  ";
																	
													//fnEscreve($sqlVerifica);
													
													$arrayQuery2 = mysqli_query(connTemp($cod_empresa,""),$sqlVerifica);
													$qrVerifica = mysqli_fetch_assoc($arrayQuery2);
													
													$controla_estoque = $qrVerifica['CONTROLA_ESTOQUE'];

													if ( $controla_estoque > 0){
														//fnEscreve("controla estoque");				
														$txtControle = "controle de estoque";				
														$sqlEstoque="SELECT A.COD_PRODUTO, A.DES_PRODUTO, A.NUM_PONTOS from PRODUTOPROMOCAO A, ESTOQUE_PRODUTO B
																		WHERE A.COD_EMPRESA = $cod_empresa
																		AND A.COD_EMPRESA=B.COD_EMPRESA
																		AND A.COD_PRODUTO=B.COD_PRODUTO
																		AND B.QTD_ESTOQUE>0
																		AND A.COD_EXCLUSA = 0 
																		AND A.LOG_ATIVO = 'S'
																		AND A.NUM_PONTOS != 0
																		AND B.COD_UNIVEND = $cod_univend
																		$andCategoria
																		$andSubCategoria
																		$andProduto
																		$andExterno
																		order BY A.NUM_PONTOS ";			

													}else{
														//fnEscreve("NÃO controla estoque");				
														$txtControle = "sem controle de estoque";				
														$sqlEstoque="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
																		where COD_EMPRESA = $cod_empresa
																		AND COD_EXCLUSA = 0 
																		AND LOG_ATIVO = 'S'
																		AND NUM_PONTOS != 0
																		$andCategoria
																		$andSubCategoria
																		$andProduto
																		$andExterno
																		order by NUM_PONTOS ";			
														
													}
																						
													// $sql="SELECT COD_PRODUTO from PRODUTOPROMOCAO
													// 	where COD_EMPRESA = $cod_empresa
													// 	AND NUM_PONTOS != 0
													// 	$andCategoria
													// 	$andSubCategoria
													// 	$andProduto
													// 	$andExterno 
													// 	AND COD_EXCLUSA=0";

													$total_itens_por_pagina = mysqli_num_rows(mysqli_query(connTemp($cod_empresa,''),$sqlEstoque));
													
													//calcula o número de páginas arredondando o resultado para cima
													$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($itens_por_pagina*$pagina)-$itens_por_pagina;

													if ( $controla_estoque > 0){
														//fnEscreve("controla estoque");				
														$txtControle = "controle de estoque";				
														$sqlEstoque="SELECT A.COD_PRODUTO, A.DES_PRODUTO, A.NUM_PONTOS from PRODUTOPROMOCAO A, ESTOQUE_PRODUTO B
																		WHERE A.COD_EMPRESA = $cod_empresa
																		AND A.COD_EMPRESA=B.COD_EMPRESA
																		AND A.COD_PRODUTO=B.COD_PRODUTO
																		AND B.QTD_ESTOQUE>0
																		AND A.COD_EXCLUSA = 0 
																		AND A.LOG_ATIVO = 'S'
																		AND A.NUM_PONTOS != 0
																		AND B.COD_UNIVEND = $cod_univend
																		$andCategoria
																		$andSubCategoria
																		$andProduto
																		$andExterno
																		order BY A.NUM_PONTOS limit $inicio,$itens_por_pagina";			

													}else{
														//fnEscreve("NÃO controla estoque");				
														$txtControle = "sem controle de estoque";				
														$sqlEstoque="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
																		where COD_EMPRESA = $cod_empresa
																		AND COD_EXCLUSA = 0 
																		AND LOG_ATIVO = 'S'
																		AND NUM_PONTOS != 0
																		$andCategoria
																		$andSubCategoria
																		$andProduto
																		$andExterno
																		order by NUM_PONTOS limit $inicio,$itens_por_pagina";			
														
													}
												                                                        
													// $sql1="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
													// where COD_EMPRESA = $cod_empresa
													// AND NUM_PONTOS != 0
													// $andCategoria
													// $andSubCategoria
													// $andProduto
													// $andExterno 
													// AND COD_EXCLUSA=0 order by NUM_PONTOS limit $inicio,$itens_por_pagina";
                                                                                                        
													//fnEscreve($sql);
													//fnEscreve($sql1);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlEstoque);
													
													$count=0;
													while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
													{														  
														$count++;
														
														echo "
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'>&nbsp;
															  </th>
															  <td>".$qrListaProduto['COD_PRODUTO']."</td>
															  <td>".$qrListaProduto['DES_PRODUTO']."</td>
															  <td>".fnValor($qrListaProduto['NUM_PONTOS'],$casasDec)."</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
															<input type='hidden' id='ret_NUM_PONTOS_".$count."' value='".fnValor($qrListaProduto['NUM_PONTOS'],$casasDec)."'>
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
	
		$(document).ready(function(){			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
		});	
				
		// ajax
		$("#COD_CATEGOR").change(function () {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca,0,codBusca3);
		});

	
		function retornaForm(index){
			var codCat = $("#ret_COD_CATEGOR_"+index).val();
			var codSub = $("#ret_COD_SUBCATE_"+index).val();
			buscaSubCat(codCat,codSub,<?php echo $cod_empresa; ?>);	
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_"+index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_"+index).val());
			// $("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger("chosen:updated");

			$('#QTD_PRODUTO').val('1');

			var pontos = $("#ret_NUM_PONTOS_"+index).val();

			$("#formulario #VAL_UNITARIO").unmask();

			if("<?=$casasDec?>" == 0){

				// pontos = pontos.replace('.','');
				// pontos = pontos;
				$("#formulario #VAL_UNITARIO").val(pontos);

			}else{

				$("#formulario #VAL_UNITARIO").val(pontos).mask("#.##0,00", {reverse: true});

			}

			
					
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
		function downForm(index){
			
			try { parent.$('#DES_PRODUTO').val($("#ret_DES_PRODUTO_"+index).val()); } catch(err) {}		
			try { parent.$('#COD_PRODUTO').val($("#ret_COD_PRODUTO_"+index).val()); } catch(err) {}				
			$(this).removeData('bs.modal');	
			parent.$('#popModalAux').modal('hide');
					
		}

		function buscaSubCat(idCat,idSub,idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupoPromocao.php",
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

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxBuscaProdutoResgateMultiplo.php?opcao=paginar&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&casasDec=<?php echo $casasDec; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}
		
		
	</script>	