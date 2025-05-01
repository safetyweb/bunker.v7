<?php
include '../_system/_functionsMain.php';
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$numCartao = "";
	$nomCliente = "";
	$tipoVenda = "T";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$dias30 = fnFormatDate(date("Y-m-d"));
	$cod_univend = "9999"; //todas revendas - default
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$numCartao = $_POST['NUM_CARTAO'];
			$nomCliente = $_POST['NOM_CLIENTE'];
			$cod_vendapdv = $_POST['COD_VENDAPDV'];
			$tipoVenda = $_POST['tipoVenda'];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}	

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	
	$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";
	
	//fnEscreve($sql);

	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 

	
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
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																	
										<fieldset>
											<legend>Filtros</legend> 
											
												<div class="row">
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
														</div>														
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
															<?php include "unidadesAutorizadasCombo.php"; ?>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
																				
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>	
													
												<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
												<input type="hidden" name="opcao" id="opcao" value="">
												<input type="hidden" name="opcao" id="opcao" value="">
												<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
													
												</div>
													
										</fieldset>	
										
										</form>	
										
										<div class="push20"></div>										
										

										<div>
											<div class="row">
												<div class="col-lg-12">

													<div class="no-more-tables">
													
														<form name="formLista" id="formLista" method="post" action="">
																										 
																										   
														<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
															<thead>
															<tr>
															  <th><small>Nome</small></th>
															  <th><small>Cartão</small></th>
															  <th><small>CPF</small></th>
															  <th><small>e-Mail</small></th>
															  <th><small>Celular</small></th>
															  <th class="{ sorter: false }"><small>Sexo</small></th>
															  <th><small>Nascimento</small></th>
															  <th style="min-width: 80px;"><small>Saldo</small></th>
															  <th style="min-width: 80px;"><small>A liberar</small></th>
															  <th><small>Cadastro</small></th>
															</tr>
															</thead>
															
															<tbody id="relatorioConteudo">
																										  
															<?php
															
																$sql = "SELECT COUNT(DISTINCT A.COD_CLIENTE) as CONTADOR
																		FROM CLIENTES A, VENDAS B 
																		WHERE 
																		A.COD_CLIENTE=B.COD_CLIENTE AND
																		A.COD_EMPRESA = $cod_empresa 
																		AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
																		AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
																		AND A.LOG_AVULSO='N'
																		AND B.COD_UNIVEND IN($lojasSelecionadas) ";
																//fnEscreve($sql);
																
																$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
																
																$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
																
																//variavel para calcular o início da visualização com base na página atual
																$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
																		
																$sql = "SELECT  DISTINCT  A.COD_CLIENTE, 
																		   A.NUM_CARTAO, 
																		   A.NUM_CGCECPF, 
																		   A.NOM_CLIENTE, 
																		   A.DES_EMAILUS, 
																		   A.DAT_CADASTR, 
																		   A.DAT_NASCIME, 
																		   A.NUM_CELULAR, 
																		   A.COD_SEXOPES,
																			(SELECT ifnull(SUM(VAL_SALDO),0)
																			FROM CREDITOSDEBITOS 
																			WHERE COD_CLIENTE=A.COD_CLIENTE AND
																			TIP_CREDITO='C' AND
																			COD_STATUSCRED=1 AND
																			(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N'))
																			) AS CREDITO_DISPONIVEL,
																			(SELECT  ifnull(SUM(VAL_SALDO),0)
																			FROM CREDITOSDEBITOS 
																			WHERE COD_CLIENTE=A.cod_cliente AND
																			TIP_CREDITO='C' AND
																			COD_STATUSCRED=2 AND
																			(DAT_EXPIRA > NOW() or(LOG_EXPIRA='N')) ) AS CREDITO_LIBERAR 																			
																		FROM CLIENTES A, VENDAS B 
																		WHERE 
																		A.COD_CLIENTE=B.COD_CLIENTE AND
																		A.COD_EMPRESA = $cod_empresa 
																		AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') >= '$dat_ini' 
																		AND DATE_FORMAT(B.DAT_CADASTR_WS, '%Y-%m-%d') <= '$dat_fim' 
																		AND A.LOG_AVULSO='N'
																		AND B.COD_UNIVEND IN($lojasSelecionadas)
																		order by A.NOM_CLIENTE limit $inicio, $itens_por_pagina ";
																		
																//fnEscreve($sql);
																
																$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																
																$count=0;
																while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																  {														  
																	$count++;
																								
																	 if ($qrListaPersonas['COD_SEXOPES'] == 1){		
																			$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
																		}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
																								
																	echo"
																		<tr>
																		  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
																		  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
																		  <td><small>".$qrListaPersonas['NUM_CGCECPF']."</small></td>
																		  <td><small><div style='width: 200px; word-wrap: break-word;'>".$qrListaPersonas['DES_EMAILUS']."</div></small></td>
																		  <td><small>".$qrListaPersonas['NUM_CELULAR']."</small></td>
																		  <td class='text-center'>".$mostraSexo."</td>
																		  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
																		  <td class='text-center'><small>".$qrListaPersonas['CREDITO_DISPONIVEL']."</small></td>
																		  <td class='text-center'><small>".$qrListaPersonas['CREDITO_LIBERAR']."</small></td>
																		  <td><small>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</small></td>
																		</tr>
																		"; 
																	  }											
																
															?>
																
															</tbody>
															
															<tfoot>
																<tr>
																	<th colspan="100">
																		<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
																	</th>
																</tr>														
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
										</div>
										
										<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>
				
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script type="text/javascript">	
	
	
		$(document).ready(function(){	

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}
			
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
											url: "relatorios/ajxRelClientesCompras.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											//console.log(response);
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
				url: "relatorios/ajxRelClientesCompras.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
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
	