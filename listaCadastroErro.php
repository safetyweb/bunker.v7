<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 100;
	$pagina  = "1";	
	
	$hashLocal = mt_rand();	
	
	if(isset($_POST['COD_EMPRESA']))
	{
	 
	}else{ 
		$cod_empresa = "";
		$cod_empresaCode = "";
		$cod_cliente  = "";
		$nom_cliente  = "";
		

		if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
			
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
             
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($qrBuscaEmpresa)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];			
		}
			
			
		}   
		
	} 
	
  //fnEscreve($cod_empresa); 	
  //fnEscreve($cod_persona); 	
  //fnMostraForm();

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

	table a:not(.btn), .table a:not(.btn) {
		text-decoration: none;
	}
	table a:not(.btn):hover, .table a:not(.btn):hover {
		text-decoration: underline;
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
										<span class="text-primary"><?php echo $NomePg; ?>  <?php echo $nom_empresa; ?> </span>
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
										<div class="push20"></div>

										<div id="relatorioConteudo">
											<div class="row">
												<div class="col-lg-12">

													<div class="no-more-tables">
													
														<form name="formLista" id="formLista" method="post" action="">
																										 
																										   
														<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
															<thead>
															<tr>
															  <th>Nome</th>
															  <th>Cartão</th>
															  <th>CPF</th>
															  <th>e-Mail</th>
															  <th>Sexo</th>
															  <th>Nascimento</th>
															  <th>Cadastro</th>
															  <th>Origem</th>
															</tr>
															</thead>
															
															<tbody>
																										  
														<?php
															$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
																	WHERE 
																	B.LOG_AVULSO='N' AND
																	B.COD_EMPRESA = $cod_empresa AND
																	B.COD_SEXOPES = 3 ";
																	
															//fnEscreve($sql);
															
															$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
															
															$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
															
															//variavel para calcular o início da visualização com base na página atual
															$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
																								
															
															$sql = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
																	B.DES_EMAILUS,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES, C.NOM_UNIVEND 
																	FROM CLIENTES B, $connAdm->DB.unidadevenda C
																	WHERE 
																	B.COD_UNIVEND=C.COD_UNIVEND AND
																	--B.LOG_AVULSO='N' AND
																	B.COD_EMPRESA = $cod_empresa AND
																	(  B.COD_SEXOPES = 3  or 
																	DATE_FORMAT(str_to_date(B.DAT_NASCIME,'%d/%m/%Y'), '%Y-%m-%d') >  DATE_FORMAT(CURRENT_DATE() , '%Y-%m-%d'))
																	order by B.NOM_CLIENTE limit $inicio,$itens_por_pagina";
																	
															//fnEscreve($sql);
															
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															
															$count=0;
															while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
															  {														  
																$count++;
																							
																 if ($qrListaPersonas['COD_SEXOPES'] == 1){		
																		$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
																	}

																 if ($qrListaPersonas['COD_SEXOPES'] == 2){		
																		$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';	
																	}	

																 if ($qrListaPersonas['COD_SEXOPES'] == 3){		
																		$mostraSexo = '<i class="fa fa-venus-mars" aria-hidden="true"></i>';	
																	}	
																	
																echo"
																	<tr>
																	  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
																	  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
																	  <td><small>".$qrListaPersonas['NUM_CGCECPF']."</small></td>
																	  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
																	  <td class='text-center'>".$mostraSexo."</td>
																	  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
																	  <td><small>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</small></td>
																	  <td><small>".$qrListaPersonas['NOM_UNIVEND']."</small></td>
																	</tr>
																	";
																  }											
															
														?>
															
														</tbody>
														
														<tfoot>
															<tr>
															  <th class="" colspan="100"><ul class="pagination pagination-sm">
															  <?php
																for($i = 1; $i < $numPaginas + 1; $i++) {
																	if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
																echo "<li class='pagination $paginaAtiva'><a href='#' onclick='reloadPage($i);' style='text-decoration: none;'>".$i."</a></li>";   
																}													  
															  ?></ul>
															  </th>
															</tr>
														</tfoot>

														</table>
														
														<div class="push"></div>
														
														</form>
																								 
													</div>
													
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
				

	<script type="text/javascript">	
	
	
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
			
		$(document).on('change', '#COD_EMPRESA', function(){ 
		   $("#dKey").val($("#COD_EMPRESA").val());
		});	

	
		function page(index){
			$("#pagina").val(index);
			$( "#formulario" )[0].submit();   			
			//alert(index);		
		}
		
		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxListaCadastroErro.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
				
			$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_COD_EMPRESA_"+index).val()+'&idC='+$("#ret_COD_CLIENTE_"+index).val());					
			$("#formulario #hHabilitado").val('S');
			$( "#formulario" )[0].submit();   			
			
		}
	
	</script>
	