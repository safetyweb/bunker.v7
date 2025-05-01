<?php
	
	//echo fnDebug('true');

	$itens_por_pagina = 50;	
	$pagina  = "1";
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	$qtd_produto = 10;
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);			
			$des_produto = fnLimpaCampo($_REQUEST['DES_PRODUTO']);	
			$cod_externo = fnLimpaCampoZero($_REQUEST['COD_EXTERNO']);			

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
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
		$nom_empresa = "";
	}
		
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	
?>
	
<style>
	
	.prod:hover, .prod:visited, .prod:link, .prod:active
	{
	    text-decoration: none!important;
	    color: inherit;
	}

</style>	
					<div class="push30"></div> 
					
					<div class="row">
                                            
                                            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa;?></span>
									</div>
									
									<?php 
									$formBack = "1015";
									include "atalhosPortlet.php"; 
									?>	
									
								</div>
								<div class="portlet-body">
			
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>						
										
									<div class="push30"></div> 
			
									<div class="login-form">
																	
										<fieldset>
											<legend>Filtros</legend> 
											
												<div class="row">
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Campanha</label>
																<select data-placeholder="Selecione a Campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php

																		$sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa and LOG_ATIVO = 'S'";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																		
																		while($qrCampanha = mysqli_fetch_assoc($arrayQuery)){
																		?>

																			<option value="<?=$qrCampanha['COD_CAMPANHA']?>"><?=$qrCampanha['DES_CAMPANHA']?></option>
																		
																		<?php 
																		}
																	?>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Produto</label>
															<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>

												</div>
													
										</fieldset>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                        <div class="push20"></div>

                                                        <div class="portlet portlet-bordered">

                                                                    <div class="portlet-body">

                                                                        <div class="login-form">
										
										<div class="push20"></div>

										<div class="row">
									
											<div class="col-md-12">

												<div class="push20"></div>									
										
												
												<table class="table table-bordered table-striped table-hover table-sortable buscavel">
												  <thead>
													<tr>
													  <th>Código</th>
													  <th>Cód. Externo</th>
													  <th>Campanha</th>
													  <th>Produto</th>
													  <th>Ganha</th>
													  <th>Limite</th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">
												  
												<?php 	

													
													
													//pesquisa no form local
													$andExternoTkt = ' ';

													if($cod_campanha == 0){
														$andCampanha = "";
													}else{
														$andCampanha = "AND A.COD_CAMPANHA = $cod_campanha";
													}

													if($cod_externo == 0){
														$andExterno = "";
													}else{
														$andExterno = "AND P.COD_EXTERNO = '$cod_externo'";
													}

													if($des_produto == ""){
														$andProduto = "";
													}else{
														$andProduto = "AND P.DES_PRODUTO = '$des_produto'";
													}

													if($filtro != ""){
														$andFiltro = " AND P.$filtro LIKE '%$val_pesquisa%' ";
													}else{
														$andFiltro = " ";
													}
													
													//se pesquisa dos produtos do ticket
													if (!empty($_GET['idP'])) {$andExterno = 'AND A.COD_EXTERNO = "'.$_GET['idP'].'"';}
											
													$sql="SELECT count(*) as CONTADOR from VANTAGEMEXTRAFAIXA A
															LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
															LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
															WHERE A.TIP_FAIXAS = 'PRD'
															AND A.COD_EMPRESA = $cod_empresa
															$andCampanha
															$andExterno
															$andProduto
															";	

													//fnEscreve($sql);
													
													$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
													
													$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);															
															
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
												
													$sql="SELECT A.*,B.DES_CAMPANHA as NOM_CAMPANHA,P.DES_PRODUTO,P.COD_EXTERNO, 
															IFNULL(P.COD_PRODUTO,0) as COD_PRODUTO from VANTAGEMEXTRAFAIXA A
															LEFT join CAMPANHA B on A.COD_CAMPANHA= B.COD_CAMPANHA
															LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
															WHERE A.TIP_FAIXAS = 'PRD'
															AND A.COD_EMPRESA = $cod_empresa
															$andCampanha
															$andExterno
															$andProduto
															order by A.COD_CAMPANHA, P.DES_PRODUTO limit $inicio,$itens_por_pagina";
													
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
													$count=0;
													$countLinha = 1;
													while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") { $tipoGanho = $nom_tpcampa; }
														else { $tipoGanho = "%"; }
												
														echo"
															<tr>
															  <td>".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."</td>
															  <td>".$qrBuscaCampanhaExtra['COD_EXTERNO']."</td>
															  <td>".$qrBuscaCampanhaExtra['NOM_CAMPANHA']."</td>
															  <td><a class='prod' href='action.do?mod=".fnEncode(1046)."&id=".fnEncode($cod_empresa)."&idP=".$qrBuscaCampanhaExtra['COD_EXTERNO']."'>".$qrBuscaCampanhaExtra['DES_PRODUTO']."</a></td>
															  <td>".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")." ".$tipoGanho."</td>															
															  <td>".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."</td>
															</tr>
															<input type='hidden' id='ret_COD_GERAL_".$count."' value='".$qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA']."'>
															<input type='hidden' id='ret_VAL_FAIXINI_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXINI'],2,",",".")."'>
															<input type='hidden' id='ret_VAL_FAIXFIM_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_FAIXFIM'],2,",",".")."'>
															<input type='hidden' id='ret_QTD_FAIXEXT_".$count."' value='".number_format ($qrBuscaCampanhaExtra['QTD_FAIXEXT'],2,",",".")."'>
															<input type='hidden' id='ret_TIP_FAIXEXT_".$count."' value='".$qrBuscaCampanhaExtra['TIP_FAIXEXT']."'>
															<input type='hidden' id='ret_QTD_FAIXLIM_".$count."' value='".$qrBuscaCampanhaExtra['QTD_FAIXLIM']."'>
															<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['COD_PRODUTO']."'>
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaCampanhaExtra['DES_PRODUTO']."'>
															"; 
															
															$countLinha++;
														  }											

												?>
													
												</tbody>
												
												<tfoot>
													<tr>
														<th colspan="100">
															<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
														</th>
													</tr>
													<tr>
													  <th class="" colspan="100">
														<center><ul id="paginacao" class="pagination-sm"></ul></center>
													  </th>
													</tr>
												</tfoot>
												
												</table>
												
											</div>

										</div>

										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />						
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<div class="push5"></div> 
									
									
										
									<div class="push50"></div>									
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
                                            </form>	
					</div>					
	
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
					
					<div class="push20"></div>
					
	
														
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>

    	$(document).ready( function() {
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
        });
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});			

		});	
	

		function abreDetail(idBloco){
			var idItem = $('.abreDetail_' + idBloco)
			if (!idItem.is(':visible')){
				idItem.show();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxPrecosDifCampanhas.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(data){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					console.log(data);
				}
			});		
		}

		$(".exportarCSV").click(function() {
				$.confirm({
					title: 'Exportação',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
					'</div>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Gerar',
							btnClass: 'btn-blue',
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}
								
								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxPrecosDifCampanhas.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},							
									buttons: {
										fechar: function () {
											//close
										}									
									}
								});								
							}
						},
						cancelar: function () {
							//close
						},
					}
				});				
			});	
		
	</script>	
   