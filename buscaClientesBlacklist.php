<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	
	// Página default
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' ){

		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request ){
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}else{

			//$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
			//$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
			$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
			$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
			$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
	 
		}
	}
	
	$cod_empresa = fnDecode($_GET['id']);
	$cod_blklist = fnDecode($_GET['idm']);
	$pagina_parent = $_GET['idp'];

	$sql = "SELECT DES_EMAIL FROM BLACKLIST_EMAIL WHERE COD_BLKLIST = $cod_blklist";
	$qrMail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
	$des_emailus = trim($qrMail['DES_EMAIL']);


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

											<a href="javascript:void(0)" class="btn btn-info pull-left btn-exc" onclick="limparTodos()" style="display: none;"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Limpar Todos</a>
											
											<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
											
										</div>										
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="DES_EMAILUS" id="DES_EMAILUS" value="<?=$des_emailus?>">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>

										<div class="row">
		
											<div class="col-lg-12">

												<div class="no-more-tables">
												
													<form name="formLista" id="formLista" method="post" action="">
										
													<table class="table table-bordered table-striped table-hover" id="tablista">
													  <thead>
														<tr>
														  <th>Código</th>
														  <th>Nome do Cliente</th>
														  <th>e-Mail</th>
														  <th>CPF</th>
														</tr>
													  </thead>
													<tbody id="relatorioConteudo">
													  												  
													<?php
													
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

														$sql = "SELECT COD_CLIENTE
																FROM  CLIENTES 
																WHERE COD_EMPRESA = $cod_empresa
																AND DES_EMAILUS = '$des_emailus'
			                                                    $andCodigo
			                                                    $andNome
			                                                    $andNumCartao
			                                                    $andcpf
			                                                    ORDER by NOM_CLIENTE ";
														//fnEscreve($sql);
														
														$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
														$total_itens_por_pagina = mysqli_num_rows($retorno);
														
														$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);
														// fnescreve($total_itens_por_pagina);	
														
														//variavel para calcular o início da visualização com base na página atual
														$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;	
	                                                                                                    
	 
														$sql = "SELECT COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, NUM_CGCECPF 
																FROM CLIENTES 
																WHERE COD_EMPRESA = $cod_empresa
																AND DES_EMAILUS = '$des_emailus'
	                                                            $andCodigo
	                                                            $andNome
	                                                            $andNumCartao
	                                                            $andcpf
	                                                            ORDER BY NOM_CLIENTE 
	                                                            LIMIT $inicio,$itens_por_pagina
	                                                    ";

	                                                    // fnEscreve($sql);

														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																											
														$count=0;
														while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
														  {														  
															$count++;
															?>								  
															
																<tr>
																  <td><?=$qrListaEmpresas['COD_CLIENTE']?></td>
																  <td>
																  	<a href='action.php?mod=<?php echo fnEncode(1024)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrListaEmpresas['COD_CLIENTE'])?>&pop=true'>
																  		<?=$qrListaEmpresas['NOM_CLIENTE']?>
																  	</a>
																  </td>
																  <td>
																  	<a href="#" class="editable" 
																	  	data-type='text' 
																	  	data-title='Editar email' 
																	  	data-pk="<?php echo $qrListaEmpresas[COD_CLIENTE]; ?>" 
																	  	data-name="DES_EMAILUS"  
																	  	data-codempresa="<?=$cod_empresa?>" >

																	  	<?=$qrListaEmpresas['DES_EMAILUS']?>
																  		
																  	</a>
																  </td>
																  <td><?=$qrListaEmpresas['NUM_CGCECPF']?></td>
																</tr>
																<input type='hidden' id='ret_ENCODE_<?=$count?>' value='<?=fnEncode($qrListaEmpresas['COD_CLIENTE'])?>'>
																<input type='hidden' id='ret_COD_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['COD_CLIENTE']?>'>
																<input type='hidden' id='ret_NOM_CLIENTE_<?=$count?>' value='<?=$qrListaEmpresas['NOM_CLIENTE']?>'>
																<input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value='<?=$cod_empresa?>'>
																<?php
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
													
													<div class="push"></div>
													
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
				

	<script type="text/javascript">	

		var num_cadastro = "<?=$total_itens_por_pagina?>";

		$(document).keypress(function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        $('#BUS').click();  
		    }
		});

		$(function(){
		    $('.editable').editable({ 
		    	emptytext: '_______________',
		        url: 'ajxEditaClienteBlacklist.php',
        		ajaxOptions:{type:'post'},
        		params: function(params) {
			        params.codempresa = $(this).data('codempresa');
			        return params;
			    },
        		success:function(data){
        			parent.reloadPage("<?=$pagina_parent?>");
					location.reload();
				}
		    });
		});
	
	
		$(document).ready(function(){

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			if(num_cadastro > 0){
				$(".btn-exc").show();
			}			
	
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

		function limparTodos() {
			msg = "";

			if(num_cadastro > 1){
				msg = "Deseja mesmo apagar o email destes <b>"+num_cadastro+"</b> cadastros?";
			}else{
				msg = "Deseja mesmo apagar o email deste cadastro?";
			}

			$.alert({
				icon: 'fal fa-exclamation-triangle',
                title: "Aviso",
                content: msg,
                backgroundDismiss: true,
                type: 'orange',
                buttons: {
		            Cancelar: function () {

					},
		            "Confirmar": {
		            	btnClass: 'btn-primary',
		            	action: function(){
		            		$.ajax({
								type: "POST",
								url: "ajxEditaClienteBlacklist.do?id=<?=fnEncode($cod_empresa)?>&opcao=exc",
								data: {DES_EMAILUS:"<?=fnEncode($des_emailus)?>"},
								success:function(data){

									parent.reloadPage("<?=$pagina_parent?>");

									$.alert({
										icon: 'fal fa-check',
						                title: "Sucesso",
						                content: "Ação concluída.",
						                type: 'green',
						                buttons: {
								            Ok: function () {
												location.reload();
											}
								        }
						            });

								},

								error:function(){
									$.alert('Algo deu errado!');
								}
								
							});	
		            	}
		            }
		        }
            });
		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxEditaClienteBlacklist.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idp=<?=$pagina_parent?>",
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
	
	