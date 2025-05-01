<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";
	$tipo = @$_GET["tipo"];

	$hashLocal = mt_rand();
	
	$cod_geral = 0;
	
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
			
			$cod_meta = fnLimpaCampoZero($_POST['COD_META']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_tpunida  = fnLimpaCampoZero($_POST['COD_TPUNIDA']);			
			$cod_produto  = fnLimpacampo($_REQUEST['COD_PRODUTO']);	
			if (empty($_REQUEST['LOG_DESTAQUE'])) {$log_destaque='N';}else{$log_destaque=$_REQUEST['LOG_DESTAQUE'];}
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
						
				//INSERT INTO `demo_novo`.`produto_meta` (`COD_EMPRESA`, `COD_PRODUTO`, `COD_EXTERNO`, `COD_TIPOUNIDADE`, `DATAHORA`) VALUES ('7', '7', '7', '7', '2018-08-12 22:25:08');
				
				$agora = date("Y-m-d H:i:s");
				
				if ($opcao == 'CAD'){
					$sql = "INSERT INTO produto_meta(
								COD_EMPRESA, 
								COD_PRODUTO, 
								COD_TIPOUNIDADE, 
								LOG_DESTAQUE, 
								DATAHORA) 
								VALUES (
								'$cod_empresa', 
								'$cod_produto', 
								'$cod_tpunida',
								'$log_destaque',
								'$agora'
								)";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());				
				}
				
				if ($opcao == 'ALT'){
					$sql = "UPDATE produto_meta SET 
								COD_PRODUTO = '$cod_produto', 
								COD_TIPOUNIDADE = '$cod_tpunida', 
								LOG_DESTAQUE = '$log_destaque'
								WHERE COD_META = $cod_meta and COD_EMPRESA = $cod_empresa ";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());				
				}					

				if ($opcao == 'EXC'){
					$sql = "DELETE FROM produto_meta 
								WHERE COD_META = $cod_meta and COD_EMPRESA = $cod_empresa ";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());				
				}	
				//echo $sql;				
				//fnEscreve($sql);
	
				
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
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}		
     
	//fnMostraForm();

?>

<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
									
.input-xs {
  height: 26px;
  padding: 2px 5px;
  font-size: 12px;
  line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */
  border-radius: 3px;
  border: 0;
}
</style>	
		
			
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
								
									<?php 
									if ($tipo == ""){
										$abaMetas = 1304;
										include "abasUsuariosMetas.php";
									}
									?>									
									
									<div class="push10"></div> 
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
										<div class="push20"></div>
											
										<fieldset>
											<legend>Dados do Produto para Controle</legend>  
															
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_META" id="COD_META" value="">
														</div>
													</div>
																									
													<div class="col-md-6">
														<label for="inputName" class="control-label required">Produto </label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="Produtos para Controle - Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
														</span>
														<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto para controle...">
														<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
														</div>																
													</div>
													
													<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo da Meta</label>
																<select data-placeholder="Selecione uma unidade de medida" name="COD_TPUNIDA" id="COD_TPUNIDA" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>											  
																	<?php
																		$sql = "select * from TIPOUNIDADEMEDIDA where COD_TPUNIDA in (4,5,6) order by COD_TPUNIDA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		
																		while ($qrListaMedida = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaMedida['COD_TPUNIDA']."'>".$qrListaMedida['DES_TIPONOME']."</option> 
																				"; 
																			  }	
																	?>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

													<div class="col-md-2" <?=($tipo <> ""?"style='display:none;'":"")?>>
														<div class="form-group">
															<label for="inputName" class="control-label">Produto de Incentivo</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_DESTAQUE" id="LOG_DESTAQUE" class="switch" value="S" <?=($tipo == "2"?"checked":"")?>>
																<span></span>
																</label>
														</div>
														<div class="help-block with-errors"></div>
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
										
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_TPUNIDA" id="COD_TPUNIDA" value="0">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
	
										<!-- modal -->									
										<div class="modal fade" id="popModalAux" tabindex='-1'>
											<div class="modal-dialog" style="">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title"></h4>
													</div>
													<div class="modal-body">
														<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
													</div>		
												</div><!-- /.modal-content -->
											</div><!-- /.modal-dialog -->
										</div><!-- /.modal -->	
										
										
										<div class="push50"></div>
										
										<div id="div_Ordena"></div>
										
										<div>
											<div class="col-lg-12">

												<div class="no-more-tables222">
											
													<form name="formLista">
													
														<table class="table table-bordered table-striped table-hover table-sortable">
														  <thead>
															<tr>
															  <th width="40"></th>
															  <th>Código</th>
															  <th>Cód. Produto</th>
															  <th>Cód. Externo</th>
															  <th>Produto</th>
															  <th>Tipo da Meta</th>
															  <?=($tipo == ""?"<th>Incentivo</th>":"")?>
															</tr>
														  </thead>
														<tbody id="relatorioConteudo">
														  
														<?php 	
														
															//$sql="select * from produto_meta where cod_empresa = $cod_empresa  ";
															$sql="select *, P.cod_externo, P.des_produto 
																from PRODUTO_META M
																inner join produtocliente P on P.cod_produto = M.cod_produto
																".($tipo == 1?"WHERE LOG_DESTAQUE != 'S'":"")."
																".($tipo == 2?"WHERE LOG_DESTAQUE = 'S'":"")."
																order by P.des_produto ";
															
															//fnEscreve($sql);
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															//fnTesteSql(connTemp($cod_empresa,''),$sql);
											
															$count=0;
															$countLinha = 1;
															while ($qrBuscaProduto = mysqli_fetch_assoc($arrayQuery))
															  {														  
																$count++;

																switch ($qrBuscaProduto['COD_TIPOUNIDADE']) {
																	case 3: 
																		$tipoMeta = "Unidade";
																		break;
																	case 4: 
																		$tipoMeta = "Litros";
																		break;
																	case 5: 
																		$tipoMeta = "Reais";
																		break;
																	case 6: 
																		$tipoMeta = "Venda";
																		break;
																	}

																if ($qrBuscaProduto['LOG_DESTAQUE'] == 'S'){		
																	$mostraDestaque = '<i class="fa fa-check" aria-hidden="true"></i>';	
																}else{ $mostraDestaque = ' '; }	
																
																echo"
																	<tr>
																	  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
																	  <td>".$qrBuscaProduto['COD_META']."</td>
																	  <td>".$qrBuscaProduto['COD_PRODUTO']."</td>
																	  <td>".$qrBuscaProduto['COD_EXTERNO']."</td>
																	  <td><a href='action.do?mod=".fnEncode(1046)."&id=".fnEncode($cod_empresa)."&idP=".$qrBuscaProduto['COD_EXTERNO']."'>".$qrBuscaProduto['DES_PRODUTO']."</a></td>
																	  <td>".$tipoMeta."</td>
																	  ".($tipo == ""?"<td align='center'>".$mostraDestaque."</td>":"")."
																	</tr>
																	<input type='hidden' id='ret_COD_META_".$count."' value='".$qrBuscaProduto['COD_META']."'>
																	<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaProduto['COD_PRODUTO']."'>
																	<input type='hidden' id='ret_COD_TPUNIDA_".$count."' value='".$qrBuscaProduto['COD_TIPOUNIDADE']."'>
																	<input type='hidden' id='ret_LOG_DESTAQUE_".$count."' value='".$qrBuscaProduto['LOG_DESTAQUE']."'>
																	<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaProduto['DES_PRODUTO']."'>
																	"; 
																	
																	$countLinha++;
																  }											

														?>
															
														</tbody>
														
														<!--
														<tfoot>
															<tr>
															  <th class="" colspan="100">
																<center><ul id="paginacao" class="pagination-sm"></ul></center>
															  </th>
															</tr>
														</tfoot>
														-->
														
														</table>
													
													</form>

												</div>
												
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
					
 	<script>
		$('[data-tipo=<?=$tipo?>]', window.parent.document).html("<?=$count?>");		
        $(document).ready( function() {
			
			//var numPaginas = <?php echo $numPaginas; ?>;
			var numPaginas = 100;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			
        });
			

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxConfigFidProdExtra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&cod_campanha=<?php echo fnEncode($cod_campanha); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

				
		function retornaForm(index){
			
			$("#formulario #COD_META").val($("#ret_COD_META_"+index).val());
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_"+index).val());			
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_"+index).val());
			$("#formulario #COD_TPUNIDA").val($("#ret_COD_TPUNIDA_"+index).val()).trigger("chosen:updated");
			
			if ($("#ret_LOG_DESTAQUE_"+index).val() == 'S'){$('#formulario #LOG_DESTAQUE').prop('checked', true);} 
			else {$('#formulario #LOG_DESTAQUE').prop('checked', false);}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   