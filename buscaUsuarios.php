<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{

		//$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
		//$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_usuario  = fnLimpacampo($_REQUEST['COD_USUARIO']);
		$nom_usuario  = fnLimpacampo($_REQUEST['NOM_USUARIO']);	
		$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);	
		$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);	
		$num_cgcecpf = fnLimpacampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));

		// fnEscreve($num_cgcecpf);
	 
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
			$sql = "SELECT NUM_CGCECPF FROM CLIENTES WHERE COD_CLIENTE = $cod_indicado";
			$qrCpf = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
			$cpfIndicado = $qrCpf[NUM_CGCECPF];
			if(strlen($cpfIndicado) == 10){
				$cpfIndicado = "0".$cpfIndicado;
			}
		}else{
			$cod_indicado = 0;
			$cpfIndicado = 0;
		}



	$btnlista = fnLimpaCampo($_GET['btn']);

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
									<?php } 

									?>	
								
									<div class="login-form">
                                        
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>"> 
																				
										<fieldset>
											<legend>Dados para Pesquisa</legend> 
											
												<div class="row">

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Código</label>
															<input type="text" class="form-control input-sm"  name="COD_USUARIO" id="COD_USUARIO" value="">
														</div>
													</div>
	
													<div class="col-xs-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome</label>
															<input type="text" class="form-control input-sm" name="NOM_USUARIO" id="NOM_USUARIO" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label">CPF/CNPJ</label>
															<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18">
															<div class="help-block with-errors"></div>
														</div>
													</div>

																				
												</div>

													<div class="row">

														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">e-Mail</label>
	                                                            <input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS"  maxlength="100" value="" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Celular/Telefone</label>
	                                                            <input type="text" class="form-control input-sm fone" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
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
													
												$where = "";
																								
												if ($cod_usuario!=0){
													$where .= 'and cod_usuario='.$cod_usuario;
												}
																								  
												if ($nom_usuario!=''){ 
													 $where .= 'and nom_usuario like "%'.$nom_usuario.'%"';														
												}

												if ($des_emailus!=''){ 
													 $where .= 'and des_emailus like "'.$des_emailus.'%"';														
												} 

												if ($num_telefon!=''){ 
													$num_telefon = str_replace(" ","%",$num_telefon);
													$num_telefon = str_replace("(","%",$num_telefon);
													$num_telefon = str_replace(")","%",$num_telefon);
													$num_telefon = str_replace("-","%",$num_telefon);
													 $where .= 'and (num_celular like "'.$num_telefon.'%"  or num_telefon like "'.$num_telefon.'%" )';
												}

												if ($num_cgcecpf!=''){ 
													 $where .= 'and (REPLACE(REPLACE(num_cgcecpf,".",""),"-","") like "%'.$num_cgcecpf.'%")';
												}

												if ($btnlista == 1 || $btnlista == 2) {
													$where .= 'and cod_tpusuario in (9,16,6,15,1,3)';
												}

												$sql = "select COUNT(0) AS CONTADOR from usuarios where cod_empresa = ".$cod_empresa." ".$where;
												//fnEscreve($sql);
												
												$resPagina = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
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
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Nome</th>
													  <th>e-Mail</th>
													  <th>Celular/Telefone</th>
													  <th>CPF</th>
													</tr>
												  </thead>
												<tbody>
												  												  
												<?php
												if ($_SERVER['REQUEST_METHOD']=='POST'){

													// fnEscreve('teste');
													$sql = "select * from usuarios where cod_empresa = ".$cod_empresa." ".$where."
                                                                                                                order by NOM_USUARIO limit $inicio,$registros";
													// fnEscreve($sql);
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																										
													$count=0;

													while ($qrLista = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
																								  
														echo"
															<tr>
															  <td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
															  <td>".$qrLista['COD_USUARIO']."</td>
															  <td>".$qrLista['NOM_USUARIO']."</td>
															  <td>".$qrLista['DES_EMAILUS']."</td>
															  <td>".$qrLista['NUM_CELULAR']."/".$qrLista['NUM_TELEFON']."</td>
															  <td>".$qrLista['NUM_CGCECPF']."</td>
															</tr>
															<input type='hidden' id='ret_ENCODE_".$count."' value='".fnEncode($qrLista['COD_USUARIO'])."'>
															<input type='hidden' id='ret_COD_USUARIO_".$count."' value='".$qrLista['COD_USUARIO']."'>
															<input type='hidden' id='ret_NOM_USUARIO_".$count."' value='".$qrLista['NOM_USUARIO']."'>
															<input type='hidden' id='ret_DES_EMAILUS_".$count."' value='".$qrLista['DES_EMAILUS']."'>
															<input type='hidden' id='ret_NUM_CELULAR_".$count."' value='".$qrLista['NUM_CELULAR']."'>
															<input type='hidden' id='ret_NUM_TELEFON_".$count."' value='".$qrLista['NUM_TELEFON']."'>
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$cod_empresa."'>
															<input type='hidden' class='cpfcnpj' id='ret_CPF_INDICADO_".$count."' value='".$cpfIndicado."'>
															"; 
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

										<?php 
											$count = 0;
											if($pref == "S" && mysqli_num_rows($arrayQuery) == 0){
												?>
													<div class="row">
														<div class="col-md-4 col-md-offset-4 text-center">
															<a href="javascript:void(0)" data-target="action.php?mod=<?=fnEncode(1423)?>&id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode(0)?>" class="btn btn-info btnCadCli"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Cliente</a>
														</div>
													</div>
												<?php
											}

											}   
											
										?>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>

					<!-- modal -->									
					<div class="modal fade" id="popModal" tabindex='-1'>
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
				

	<script type="text/javascript">	

		$(document).keypress(function(event){
		    var keycode = (event.keyCode ? event.keyCode : event.which);
		    if(keycode == '13'){
		        $('#BUS').click();  
		    }
		});
	
	
		$(document).ready(function(){

			$(".btnCadCli").click(function(){
				parent.$('#popModal').modal('hide');
				parent.window.location.replace($(this).attr("data-target"));
			});		
	
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

			var btn = "<?=$btnlista?>";
			var data = [];
			data["COD_USUARIO"] = $("#ret_COD_USUARIO_"+index).val();
			data["NOM_USUARIO"] = $("#ret_NOM_USUARIO_"+index).val();

			if(btn != ""){

				$.ajax({
					method: 'POST',
					url: "ajxGravaUsuarioRes.do?id=<?=fnEncode($cod_empresa)?>",
					data:{COD_USUARIO: $("#ret_COD_USUARIO_"+index).val(), COD_BTN: btn},
					success:function(data){
						console.log(data);
						parent.window.location.reload();

					},
					error:function(){
						console.log("erro 500");
					}
				});

			}


			window.parent.postMessage(data, "*");

			$(this).removeData('bs.modal');	
			//console.log('entrou' + index);
			parent.$('#popModal').modal('hide');			
					
		}	
	</script>
	
	