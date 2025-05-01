<?php

	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
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

			$cod_empresa = fnLimpacampoZero($_REQUEST['ID']);
			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

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

	if($val_pesquisa != ""){
		$esconde = " ";
	}else{
		$esconde = "display: none;";
	}
      
	//fnMostraForm();
	//fnEscreve($filtro);

        
?>
<style>
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
										<span class="text-primary"> <?php echo $NomePg; ?></span>
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
									
									<div class="push10"></div> 
									
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										</form>
										

										<div class="row">
											<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

												<!-- <div class="col-xs-4">
													<a class='btn btn-info addBox' data-url="action.do?mod=<?php echo fnEncode(1450)?>&pop=true" data-title="Empresas"><i class='far fa-plus'></i> Adicionar Empresa </a>
												</div> -->
												
												<div class="col-xs-4 col-xs-offset-4">
												    <div class="input-group activeItem">
										                <div class="input-group-btn search-panel">
										                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
										                    	<span id="search_concept">Produção</span>&nbsp;
										                    	<span class="far fa-angle-down"></span>										                    	
										                    </button>
										                    <ul class="dropdown-menu" role="menu">
																<li><a href="#STATUS=2">Produção</a></li>
																<li><a href="#STATUS=3">Apresentação</a></li>
																<li><a href="#STATUS=1">Teste</a></li>
																<li><a href="#STATUS=4">Canceladas</a></li>
																<li><a href="#ALL">Todos Status</a></li>
										                    	<li class="divisor"></li>
											                    <li><a href="#NOM_EMPRESA">Razão social</a></li>
											                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
											                    <li><a href="#CNPJ">CNPJ</a></li>
																
										                    </ul>
										                </div>
										                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
										                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										                <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
										                	<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										                </div>
										                <div class="input-group-btn">
										                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										                </div>
										            </div>
										        </div>
										         	
										        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

											</form>
										    
										</div>

										<div class="push20"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													  <th>Coordenador</th>
													  <th>Integradora</th>
													  <!-- <th>Lojas Ativas</th> -->
													  <th>Tipo <br>Cobrança</th>
													  <!-- <th>Lojas Contrato</th> -->
													  <th>Lojas </th>
													  <th>Lojas Cobranças</th>
													  <th>Vl. Mensalidade</th>
													  <th>Imposto</th>
													  <th>Parceria</th>
													  <th width="120">Vl. Líquido</th>
													  <!-- <th class="{sorter:false}">iFaro</th> -->
													  <th>Status</th>
													  <th>Primeira Venda</th>
													  <th>Dt. Alteração</th>
													</tr>
												  </thead>
												<tbody>
												
												  
												<?php 

													if($filtro != ""){
														if (strpos($filtro, 'STATUS') !== false) {
														    $andFiltro = "AND empresas.COD_$filtro AND NOM_FANTASI LIKE '%$val_pesquisa%' ";
														}
														else if($filtro == 'ALL'){
															$andFiltro = " AND NOM_FANTASI LIKE '%$val_pesquisa%' ";
														}
														else{
															$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
														}
													}else{
														$andFiltro = " AND empresas.COD_STATUS=2 AND NOM_FANTASI LIKE '%$val_pesquisa%' ";
													}
												
													if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
													$sql = "SELECT STATUSSISTEMA.DES_STATUS, 
																		empresas.COD_EMPRESA,
																		empresas.NOM_FANTASI, 
																		empresas.NOM_EMPRESA, 
																		empresas.DAT_PRODUCAO, 
																		empresas.NUM_CGCECPF, 
																		'18.00' as PCT_IMPOSTO, 
																		(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
																		(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS, 
																		(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND LOG_COBRANCA = 'S') AS LOJAS_ATIVAS, 
																		(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA ) NOM_INTEGRADORA, 
																		B.COD_DATABASE, 
																		B.NOM_DATABASE,
																		C.QTD_LOJA,
																		C.TIP_CONTRATO,
																		C.VL_CONTRATO,
																		C.VL_PARCERIA,
																		C.DAT_ALTERAC_VAL,
																		C.DAT_ALTERAC_TIP,
																		C.COD_CONTRATO
																		
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
															LEFT JOIN EMPRESA_CONTRATO C ON C.COD_EMPRESA=empresas.COD_EMPRESA 
															WHERE 
															empresas.COD_EMPRESA <> 1 AND 
															empresas.LOG_INTEGRADORA = 'N' AND 
															empresas.COD_STATUS=2 AND 
															NOM_FANTASI LIKE '%%'
															$andFiltro															
															ORDER by NOM_FANTASI
													";
													
													}else {
													$sql = "SELECT STATUSSISTEMA.DES_STATUS, 
																		empresas.COD_EMPRESA,
																		empresas.NOM_FANTASI,
																		empresas.NOM_EMPRESA, 																		
																		empresas.DAT_PRODUCAO, 
																		empresas.NUM_CGCECPF, 
																		'18.00' as PCT_IMPOSTO, 
																		(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
																		(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS, 
																		(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND LOG_COBRANCA = 'S') AS LOJAS_ATIVAS, 
																		(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA ) NOM_INTEGRADORA, 
																		B.COD_DATABASE, 
																		B.NOM_DATABASE,
																		C.QTD_LOJA,
																		C.TIP_CONTRATO,
																		C.VL_CONTRATO,
																		C.VL_PARCERIA,
																		C.DAT_ALTERAC_VAL,
																		C.DAT_ALTERAC_TIP,
																		C.COD_CONTRATO
																		
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
															LEFT JOIN EMPRESA_CONTRATO C ON C.COD_EMPRESA=empresas.COD_EMPRESA 
															WHERE
															COD_MASTER IN (1,".$_SESSION["SYS_COD_MASTER"].",".$_SESSION["SYS_COD_EMPRESA"].") AND
															empresas.LOG_INTEGRADORA = 'N' AND 
															empresas.COD_STATUS=2 AND 
															NOM_FANTASI LIKE '%%'
															$andFiltro															
															ORDER by NOM_FANTASI
													";
													}
													
													//fnEscreve($sql);
													
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													$totLojas=0;
													$totAtivo = 0;
													$totLiquido = 0;
													
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														  if ($qrListaEmpresas['LOG_CONSEXT'] == 'S'){		
																$mostraAtivo = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ''; }	
														
														  if ($qrListaEmpresas['COD_DATABASE'] > 0){
															if ($qrListaEmpresas['NOM_DATABASE'] == "db_host1" || $qrListaEmpresas['NOM_DATABASE'] == "db_host2"){
																$mostraAtivoBD = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."' target='_blank'>".fnAcentos($qrListaEmpresas['NOM_FANTASI'])."</a>";	
																$mostraRelatorio = "<a href='action.do?mod=".fnEncode(1190)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."' target='_blank'>".trim($qrListaEmpresas['COD_EMPRESA'])."</a>";	
															}else{
																$mostraAtivoBD = '<i class="fa fa-check" aria-hidden="true"></i>';		
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."' target='_blank'>".fnAcentos($qrListaEmpresas['NOM_FANTASI'])."</a><br/><span class='f12'>".fnAcentos($qrListaEmpresas['NOM_FANTASI'])."</span>";	
																$mostraRelatorio = "<a href='action.do?mod=".fnEncode(1190)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."' target='_blank'>".trim($qrListaEmpresas['COD_EMPRESA'])."</a>";		
															}	
														  }else{ 
															$mostraAtivoBD = ''; 
															$mostraEmpresa = fnAcentos($qrListaEmpresas['NOM_FANTASI']);	
															$mostraRelatorio = "<a href='action.do?mod=".fnEncode(1190)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."' target='_blank'>".trim($qrListaEmpresas['COD_EMPRESA'])."</a><br/><span class='f12'>".fnAcentos($qrListaEmpresas['NOM_FANTASI'])."</span>";
														  }	
														
														  if (!empty($qrListaEmpresas['COD_SISTEMAS'])){
															  $tem_sistema = "tem";															  
														  }	else {$tem_sistema = "nao";}

														  if ($qrListaEmpresas['LOG_INTEGRADORA'] == 'S'){		
																$mostraSH = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraSH = ''; }
														  
                                                          $totLojas+=$qrListaEmpresas['LOJAS'];
                                                          $totAtivo+=$qrListaEmpresas['LOJAS_ATIVAS'];

                                                          if(isset($qrListaEmpresas['DAT_PRODUCAO'])){
                                                          	$data = fnDataShort($qrListaEmpresas['DAT_PRODUCAO']);
                                                          }else{
                                                          	$data = '___/___/_______';
                                                          }

                                                          if($qrListaEmpresas['DAT_ALTERAC_VAL'] > $qrListaEmpresas['DAT_ALTERAC_TIP']){
                                                          	$dataAlt = fnDataFull($qrListaEmpresas['DAT_ALTERAC_VAL']);
                                                          }else{
                                                          	$dataAlt = fnDataFull($qrListaEmpresas['DAT_ALTERAC_TIP']);
                                                          }

                                                          if($qrListaEmpresas['LOJAS'] > $qrListaEmpresas['LOJAS_ATIVAS']){
                                                          	$cor = "style='color: #F00;'";
                                                          }else{
                                                          	$cor = '';
                                                          }

														if($qrListaEmpresas['TIP_CONTRATO'] =='U'){
															$tipContrato = 'Unidade';
															$multiplicador = $qrListaEmpresas['QTD_LOJA'];
															
														}else if($qrListaEmpresas['TIP_CONTRATO'] =='C'){
                                                          	$tipContrato = 'Contrato';
															$multiplicador = 1;
                                                        }else{
                                                        	$tipContrato = null;
															$multiplicador = 1;
                                                        }

                                                        $vl_bruto = $qrListaEmpresas['VL_CONTRATO']*$multiplicador;
                                                        $vl_desconto = ($vl_bruto*0.18) + ($vl_bruto*($qrListaEmpresas['VL_PARCERIA']/100));

                                                        // if($qrListaEmpresas['QTD_LOJA'] != 0){
                                                        // 	$vl_liquido = $vl_bruto-$vl_desconto;
                                                        // }else{
                                                        // 	$vl_liquido = 0;
                                                        // }

                                                        $vl_liquido = $vl_bruto-$vl_desconto;

                                                        $totLiquido+=$vl_liquido;

														$totLojasAtivas = $qrListaEmpresas['QTD_LOJA'];
														$num_cgcecpf = $qrListaEmpresas['NUM_CGCECPF'];
															
                                                          ?>
														
															<tr>
															  <td class='text-center'><?=$mostraRelatorio;?></td>
															  <td><a href="javascript:void(0);"><i class="fas fa-user-tag f13" data-toggle="tooltip" data-placement="top" data-original-title="<?=$qrListaEmpresas['NOM_RESPONS']?>"></i></a>&nbsp; <?=$mostraEmpresa;?><br/><span class="f12 cpfcnpj"><?=$num_cgcecpf;?></span></td>

															  <td>
															  	<a href="#" class="editable-coordenador" 
																  	data-type='select' 
																  	data-title='Editar Consultor' data-pk="<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>" 
																  	data-name="COD_CONSULTOR" 
																  	data-count="<?php echo $count; ?>"><?=$qrListaEmpresas['NOM_CONSULTOR']?>
															  		
															  	</a>
															  </td>

															  <td>
															  	<a href="#" class="editable-integradora" 
																  	data-type='select' 
																  	data-title='Editar Integradora' data-pk="<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>" 
																  	data-name="COD_INTEGRADORA"  
																  	data-count="<?php echo $count; ?>" ><?=$qrListaEmpresas['NOM_INTEGRADORA']?>
															  		
															  	</a>
															  </td>

															  <!-- <td align='center' <?=$cor?>><?=$qrListaEmpresas['LOJAS_ATIVAS'];?></td> -->
															  
															  <td>
															  	<a href="#" class="editable-contrato" 
																  	data-type='select' 
																  	data-title='Editar Tipo' data-pk="<?php echo $qrListaEmpresas['COD_CONTRATO']; ?>" 
																  	data-name="TIP_CONTRATO" 
																  	data-codempresa="<?php echo $qrListaEmpresas[COD_EMPRESA]; ?>"><?=$tipContrato?>
															  		
															  	</a>
															  </td>
															  
															  <td align='center' <?=$cor?>><?=$qrListaEmpresas['LOJAS'];?></td>
															  
															  <td align='center'><a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1449)?>&id=<?php echo fnEncode($qrListaEmpresas['COD_EMPRESA'])?>&idC=<?php echo fnEncode($qrListaEmpresas['COD_CONTRATO'])?>&pop=true" data-title="Unidades do Contrato"><span id="QTD_LOJA_<?=$qrListaEmpresas[COD_CONTRATO]?>"><?=fnValor($qrListaEmpresas['QTD_LOJA'],0);?></span></a></td>

															  <td class="text-right vl">
															  	<a href="#" class="editable" 
																  	data-type='text' 
																  	data-title='Editar Valor' data-pk="<?php echo $qrListaEmpresas['COD_CONTRATO']; ?>" 
																  	data-name="VL_CONTRATO"  
																  	data-codempresa="<?php echo $qrListaEmpresas[COD_EMPRESA]; ?>" ><?=fnValor($qrListaEmpresas['VL_CONTRATO'],2)?>
															  		
															  	</a>
															  </td>

															  <td align='center'><?=fnValor($qrListaEmpresas['PCT_IMPOSTO'],2);?>%</td>
															  
															  <td class="text-right vl">
															  	<a href="#" class="editable" 
																  	data-type='text' 
																  	data-title='Editar Valor' data-pk="<?php echo $qrListaEmpresas['COD_CONTRATO']; ?>" 
																  	data-name="VL_PARCERIA"  
																  	data-codempresa="<?php echo $qrListaEmpresas[COD_EMPRESA]; ?>" ><?=fnValor($qrListaEmpresas['VL_PARCERIA'],2)?>%
															  		
															  	</a>
															  </td>

															  <td class="text-right"><span id="VAL_LIQUIDO_<?=$qrListaEmpresas[COD_CONTRATO]?>"><?=fnValor($vl_liquido,2);?></span></td>

															  <!-- <td align='center'><?=$mostraAtivo;?></td> -->

															  <td class='text-center'>
															  	<a href="#" class="editable-status" 
																  	data-type='select' 
																  	data-title='Editar Status' data-pk="<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>" 
																  	data-name="COD_STATUS" 
																  	data-count="<?php echo $count; ?>"><?=$qrListaEmpresas['DES_STATUS']?>
															  		
															  	</a>
															  </td>

															  <td class='text-center'>
															  	<a href="#" class="editable-data" 
																  	data-type='date' 
																  	data-title='Editar Data' data-pk="<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>" 
																  	data-name="DAT_PRODUCAO"
																  	data-count="<?php echo $count; ?>"><small><?=$data?></small>
															  		
															  	</a>
															  </td>

															  <td><small><?=$dataAlt?></small></td>

															</tr>
															
								

												<?php														
														  }
													
												?>
													
												</tbody>
												
												<tfoot>
													<tr>
													  <th class="text-center"><?php echo $count; ?></th>
													  <!--<th class="text-center" colspan="4"></th>
													  <th class="text-center"><?php echo $totAtivo; ?></th>-->
													  <th class="" colspan="9"></th>
													  <th class="text-right">R$ <?=fnValor($totLiquido,2);?></th>
													  <th class="" colspan="2"></th>
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
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			var status = [];
			var coordenadores = [];
			var integradoras = [];

			combos(status,coordenadores,integradoras);

			$('.vl .editable-input .input-sm').mask('000.000.000.000.000,00', {reverse: true});

			$('.input-group #VAL_PESQUISA').val('STATUS=2');
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			$.fn.editable.defaults.mode = 'popup';

    		$(function(){
			    $('.editable-contrato').editable({ 
			    	emptytext: '_______________',  
			        source: [{value: 'U', text: "Unidade"}, {value: 'C', text: "Contrato"}],
			        url: 'ajxEmpresasMarkaContrato.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.codempresa = $(this).data('codempresa');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

			$(function(){
			    $('.editable').editable({ 
			    	emptytext: '_______________',
			        url: 'ajxEmpresasMarkaContrato.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.codempresa = $(this).data('codempresa');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

			$(function(){
			    $('.editable-status').editable({ 
			    	emptytext: '_______________',  
			        source: status,
			        url: 'ajxListaEmpresasMarka.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.count = $(this).data('count');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

        	$(function(){
			    $('.editable-coordenador').editable({ 
			    	emptytext: '_______________',  
			        source: coordenadores,
			        url: 'ajxListaEmpresasMarka.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.count = $(this).data('count');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

			$(function(){
			    $('.editable-integradora').editable({ 
			    	emptytext: '_______________',  
			        source: integradoras,
			        url: 'ajxListaEmpresasMarka.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.count = $(this).data('count');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

			$(function(){
			    $('.editable-data').editable({ 
			    	viewformat: 'dd/mm/yyyy', 
			        url: 'ajxListaEmpresasMarka.php',
	        		ajaxOptions:{type:'post'},
	        		params: function(params) {
				        params.count = $(this).data('count');
				        return params;
				    },
	        		success:function(data){
						console.log(data);
					}
			    });
			});

			function combos($status,$coordenadores,$integradoras){
				<?php 
					$sqlStatus="SELECT * FROM STATUSSISTEMA";
					$arrayStatus = mysqli_query($connAdm->connAdm(),$sqlStatus) or die(mysqli_error());
					while($qrStatus = mysqli_fetch_assoc($arrayStatus))
					{
						?>
							valor = {value: "<?=$qrStatus['COD_STATUS']?>", text: "<?=$qrStatus['DES_STATUS']?>"};
							$status.push(valor);
						<?php 
					}

					$sqlCoord="SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS 
							   WHERE USUARIOS.COD_EMPRESA = 3
							   AND USUARIOS.DAT_EXCLUSA IS NULL ORDER BY  USUARIOS.NOM_USUARIO";
					$arrayCoord = mysqli_query($connAdm->connAdm(),$sqlCoord) or die(mysqli_error());
					while($qrCoord = mysqli_fetch_assoc($arrayCoord))
					{
						?>
							usuario = {value: "<?=$qrCoord['COD_USUARIO']?>", text: "<?=$qrCoord['NOM_USUARIO']?>"};
							$coordenadores.push(usuario);
						<?php 
					}

					$sqlInt="SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA <> 1 AND LOG_INTEGRADORA = 'S' ORDER BY NOM_FANTASI";
					$arrayInt = mysqli_query($connAdm->connAdm(),$sqlInt) or die(mysqli_error());
					while($qrInt = mysqli_fetch_assoc($arrayInt))
					{
						?>
							integradora = {value: "<?=$qrInt['COD_EMPRESA']?>", text: "<?=$qrInt['NOM_FANTASI']?>"};
							$integradoras.push(integradora);
						<?php 
					}
				?>
			}
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});

		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    	if("<?php echo $filtro; ?>" != ""){
		    		location.reload();
		    	}else{
		    		var value = $('#INPUT').val().toLowerCase().trim();
				    if(value){
				    	$('#CLEARDIV').show();
				    }else{
				    	$('#CLEARDIV').hide();
				    }
				    $(".buscavel tr").each(function (index) {
				        if (!index) return;
				        $(this).find("td").each(function () {
				            var id = $(this).text().toLowerCase().trim();
				            var sem_registro = (id.indexOf(value) == -1);
				            $(this).closest('tr').toggle(!sem_registro);
				            return sem_registro;
				        });
				    });
		    	}
		    });

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    	
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();
			var value = $(el).val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $(".buscavel tr").each(function (index) {
		        if (!index) return;
		        $(this).find("td").each(function () {
		            var id = $(this).text().toLowerCase().trim();
		            var sem_registro = (id.indexOf(value) == -1);
		            $(this).closest('tr').toggle(!sem_registro);
		            return sem_registro;
		        });
		    });
		}

	//-----------------------------------------------------------------------------------
		
		function retornaForm(index){
			$("#formulario #COD_SISTEMAS").val(0).trigger("chosen:updated");
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #NOM_EMPRESA").val($("#ret_NOM_EMPRESA_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #NOM_RESPONS").val($("#ret_NOM_RESPONS_"+index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_"+index).val());
			$("#formulario #COD_ESTATUS").val($("#ret_COD_ESTATUS_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}			
			if ($("#ret_LOG_PRECUNI_"+index).val() == 'S'){$('#formulario #LOG_PRECUNI').prop('checked', true);} 
			else {$('#formulario #LOG_PRECUNI').prop('checked', false);}
			if ($("#ret_LOG_ESTOQUE_"+index).val() == 'S'){$('#formulario #LOG_ESTOQUE').prop('checked', true);} 
			else {$('#formulario #LOG_ESTOQUE').prop('checked', false);}
			$("#formulario #NUM_ESCRICA").val($("#ret_NUM_ESCRICA_"+index).val());
			$("#formulario #NOM_FANTASI").val($("#ret_NOM_FANTASI_"+index).val());				
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_"+index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_"+index).val());
			$("#formulario #DES_ENDEREC").val($("#ret_DES_ENDEREC_"+index).val());
			$("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_"+index).val());
			$("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_"+index).val());
			$("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_"+index).val());
			$("#formulario #NUM_CEPOZOF").val($("#ret_NUM_CEPOZOF_"+index).val());
			$("#formulario #NOM_CIDADEC").val($("#ret_NOM_CIDADEC_"+index).val());				
			$("#formulario #COD_ESTADOF").val($("#ret_COD_ESTADOF_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_MASTER").val($("#ret_COD_MASTER_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_LAYOUT").val($("#ret_COD_LAYOUT_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEGMENT").val($("#ret_COD_SEGMENT_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_SUFIXO").val($("#ret_DES_SUFIXO_"+index).val());
			$("#formulario #TIP_RETORNO").val($("#ret_TIP_RETORNO_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_HEADER").val($("#ret_TIP_HEADER_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_ALINHAM").val($("#ret_DES_ALINHAM_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_LOGO").val($("#ret_DES_LOGO_"+index).val());
			$("#formulario #DES_IMGBACK").val($("#ret_DES_IMGBACK_"+index).val());
			$("#formulario #COD_CLIENTE_AV").val($("#ret_COD_CLIENTE_AV_"+index).val());

			$("#formulario #COD_PLATAFORMA").val($("#ret_COD_PLATAFORMA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_"+index).val()).trigger("chosen:updated");

			if ($("#ret_LOG_CONSEXT_"+index).val() == 'S'){$('#formulario #LOG_CONSEXT').prop('checked', true);} 
			else {$('#formulario #LOG_CONSEXT').prop('checked', false);}			
			if ($("#ret_LOG_AUTOCAD_"+index).val() == 'S'){$('#formulario #LOG_AUTOCAD').prop('checked', true);} 
			else {$('#formulario #LOG_AUTOCAD').prop('checked', false);}
			
			$("#formulario #TIP_CONTABIL").val($("#ret_TIP_CONTABIL_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_CONFIGU_"+index).val() == 'S'){$('#formulario #LOG_CONFIGU').prop('checked', true);} 
			else {$('#formulario #LOG_CONFIGU').prop('checked', false);}
			
			$("#formulario #COD_CHAVECO").val($("#ret_COD_CHAVECO_"+index).val()).trigger("chosen:updated");

			//retorno combo multiplo
			if ($("#ret_TEM_SISTEMAS_"+index).val() == "tem" ){
				var sistemasCli = $("#ret_COD_SISTEMAS_"+index).val();
				var sistemasCliArr = sistemasCli.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasCliArr.length; i++) {
				  $("#formulario #COD_SISTEMAS option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_SISTEMAS").trigger("chosen:updated");    
			} else {$("#formulario #COD_SISTEMAS").val('').trigger("chosen:updated");}
			
			$("#formulario #DES_PATHARQ").val($("#ret_DES_PATHARQ_"+index).val());
			if ($("#ret_LOG_INTEGRADORA_"+index).val() == 'S'){$('#formulario #LOG_INTEGRADORA').prop('checked', true);} 
			else {$('#formulario #LOG_INTEGRADORA').prop('checked', false);}			
			$("#formulario #SITE").val($("#ret_SITE_"+index).val());
			
			if ($("#ret_COD_DATABASE_"+index).val() > 0){$('#formulario #COD_DATABASE').prop('checked', true);} 
			else {$('#formulario #COD_DATABASE').prop('checked', false);}		
			
			$("#formulario #TIP_REGVENDA").val($("#ret_TIP_REGVENDA_"+index).val()).trigger("chosen:updated");
			
			$("#formulario #NUM_DECIMAIS").val($("#ret_NUM_DECIMAIS_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_DATAWS").val($("#ret_COD_DATAWS_"+index).val()).trigger("chosen:updated");
			$("#formulario #DAT_PRODUCAO").val($("#ret_DAT_PRODUCAO_"+index).val());				
			$("#formulario #COD_INTEGRADORA").val($("#RET_COD_INTEGRADORA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_CONSULTOR").val($("#ret_COD_CONSULTOR_"+index).val()).trigger("chosen:updated");
			
			if ($("#ret_LOG_WS_"+index).val() == 'S'){$('#formulario #LOG_WS').prop('checked', true);} 
			else {$('#formulario #LOG_WS').prop('checked', false);}
			
			if ($("#ret_LOG_PONTUAR_"+index).val() == 'S'){$('#formulario #LOG_PONTUAR').prop('checked', true);} 
			else {$('#formulario #LOG_PONTUAR').prop('checked', false);}
			
            if ($("#ret_LOG_ATIVCAD_"+index).val() == 'S'){$('#formulario #LOG_ATIVCAD').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVCAD').prop('checked', false);}
			
            if ($("#ret_LOG_AVULSO_"+index).val() == 'S'){$('#formulario #LOG_AVULSO').prop('checked', true);} 
			else {$('#formulario #LOG_AVULSO').prop('checked', false);}
                 
            if ($("#ret_LOG_CATEGORIA_"+index).val() == 'S'){$('#formulario #LOG_CATEGORIA').prop('checked', true);} 
			else {$('#formulario #LOG_CATEGORIA').prop('checked', false);}
            
			if ($("#ret_LOG_ALTVENDA_"+index).val() == 'S'){$('#formulario #LOG_ALTVENDA').prop('checked', true);} 
			else {$('#formulario #LOG_ALTVENDA').prop('checked', false);}
                       
			if ($("#ret_LOG_QUALICAD_"+index).val() == 'S'){$('#formulario #LOG_QUALICAD').prop('checked', true);} 
			else {$('#formulario #LOG_QUALICAD').prop('checked', false);}
			
			if ($("#ret_LOG_PDVMANU_"+index).val() == '1'){$('#formulario #LOG_PDVMANU').prop('checked', true);} 
			else {$('#formulario #LOG_PDVMANU').prop('checked', false);}
			
			$("#formulario #LOG_CADVENDEDOR").val($("#ret_LOG_CADVENDEDOR_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_INTEGRADORA").val($("#ret_COD_INTEGRADORA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_PLATAFORMA").val($("#ret_COD_PLATAFORMA_"+index).val()).trigger("chosen:updated");
			
                       
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
		
		
    $('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                    '<form method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
        });
    });

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }		
		
	</script>	