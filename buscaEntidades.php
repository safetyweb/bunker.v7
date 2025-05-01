<?php
	
//echo fnDebug('true');

$hashLocal = mt_rand();	
$itens_por_pagina = 50;
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
		$nom_entidad = fnLimpaCampo($_REQUEST['NOM_ENTIDAD']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
					
		if ($opcao != ''){			
			
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
	$cod_conveni = fnLimpaCampoZero(fnDecode($_GET['idc']));  
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
											
}else {
	$cod_empresa = 0;		
	//fnEscreve('entrou else');
}

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

													<div class="col-xs-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome da Entidade</label>
															<input type="text" class="form-control input-sm" name="NOM_ENTIDAD" id="NOM_ENTIDAD" maxlength="40" value="<?=$nom_entidad?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>
												
											</fieldset>	

											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">

												<a href="action.php?mod=<?=fnEncode(1075)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_conveni)?>&pop=true" class="btn btn-primary pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Nova Entidade</a>

												<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>

											</div>										

											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa ?>">
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

											<div class="push5"></div> 

										</form>
										
										<div class="push50"></div>
										
										<?php 													
											
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
                                                            <th>Nome da Entidade</th>
                                                            <th>Nome do Responsável</th>
                                                            <th>Cidade</th>
                                                            <th>Estado</th>

                                                        </tr>
                                                    </thead>
												<tbody>
												  												  
												<?php
												if ($_SERVER['REQUEST_METHOD']=='POST'){

													$andEntidad = "";

													if ($nom_entidad != ''){ 
														 $andEntidad = 'AND NOM_ENTIDAD like "%'.$nom_entidad.'%"';														
													}
													
													$sql = "SELECT 1 from ENTIDADE 
			                                                left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA
			                                                where webtools.empresas.COD_EMPRESA = $cod_empresa
			                                                $andEntidad";
		                                              //echo $sql;    
		                                            $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
		                                            $totalitens_por_pagina = mysqli_num_rows($retorno);
		                                            $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

		                                              // fnEscreve($numPaginas);

		                                            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

													// fnEscreve('teste');
													$sql = "SELECT ENTIDADE.COD_ENTIDAD,
	                                                                ENTIDADE.COD_GRUPOENT,
	                                                                ENTIDADE.COD_TPENTID,
	                                                                ENTIDADE.COD_EXTERNO,
	                                                                ENTIDADE.COD_EMPRESA,
	                                                                ENTIDADE.COD_MUNICIPIO,
	                                                                ENTIDADE.COD_ESTADO,
	                                                                ENTIDADE.NOM_ENTIDAD,
	                                                                ENTIDADE.NUM_CGCECPF,
	                                                                ENTIDADE.DES_ENDERC,
	                                                                ENTIDADE.NUM_ENDEREC,
	                                                                ENTIDADE.DES_BAIRROC,
	                                                                ENTIDADE.NUM_CEPOZOF,
	                                                                ENTIDADE.NOM_CIDADES,
	                                                                ENTIDADE.NOM_ESTADOS,
	                                                                ENTIDADE.NUM_TELEFONE,
	                                                                ENTIDADE.NUM_CELULAR,
	                                                                ENTIDADE.EMAIL,
	                                                                ENTIDADE.NOM_RESPON,
	                                                                ENTIDADE.QTD_MEMBROS,
	                                                                TIPOENTIDADE.DES_TPENTID,
	                                                                EMPRESAS.NOM_EMPRESA,
	                                                                A.DES_GRUPOENT
	                                                        from ENTIDADE  
	                                                        left join webtools.empresas ON ENTIDADE.COD_EMPRESA = webtools.empresas.COD_EMPRESA 
	                                                        left join webtools.tipoentidade ON entidade.COD_TPENTID = webtools.tipoentidade.COD_TPENTID
	                                                        left join entidade_grupo A ON A.COD_GRUPOENT = ENTIDADE.COD_GRUPOENT 
	                                                        where webtools.empresas.COD_EMPRESA = $cod_empresa 
	                                                        $andEntidad
	                                                        order by COD_ENTIDAD
	                                                        limit $inicio,$itens_por_pagina";

													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																										
													$count=0;

													while ($qrLista = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
																								  
														echo"
															<tr>
																<td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></td>
																<td>" . $qrLista['COD_ENTIDAD'] . "</td>
                                                                <td>" . $qrLista['NOM_ENTIDAD'] . "</td>
                                                                <td>" . $qrLista['NOM_RESPON'] . "</td>
                                                                <td>" . $qrLista['NOM_CIDADES']. "</td>
                                                                <td>" . $estado . "</td>    
                                                            </tr>

                                                                
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$cod_empresa."'>
															<input type='hidden' id='ret_COD_ENTIDAD_".$count."' value='".$qrLista['COD_ENTIDAD']."'>
															<input type='hidden' id='ret_NOM_ENTIDAD_".$count."' value='".$qrLista['NOM_ENTIDAD']."'>
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
			
		
		
		
		function downForm(index){
			
			parent.$("#COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val());
			parent.$("#NOM_ENTIDAD").val($("#ret_NOM_ENTIDAD_"+index).val());

			$(this).removeData('bs.modal');	
			//console.log('entrou' + index);
			parent.$('#popModal').modal('hide');
			
					
		}	
	</script>
	
