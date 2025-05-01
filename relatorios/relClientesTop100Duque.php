<?php
include '../_system/_functionsMain.php';
	
	//echo fnDebug('true');
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 7 days')));
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	

	$hashLocal = mt_rand();	

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	
	$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";
	
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

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}	
	
	//fnEscreve($sql);

	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

	
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
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
														</div>														
													</div>
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
															<?php include "unidadesAutorizadasComboMulti.php"; ?>
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
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>	
																		
													
												</div>
													
										</fieldset>	
										
										
										<div class="push20"></div>										
										

										<div>
											<div class="row">
												<div class="col-lg-12">

													<div class="no-more-tables">
													
																										   
														<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
															<thead>
															<tr>
															  <th><small>Nome</small></th>
															  <th><small>Empresa</small></th>
															  <th><small>Cartão</small></th>
															  <th><small>e-Mail</small></th>
															  <th><small>Sexo</small></th>
															  <th><small>Nascimento</small></th>
															  <th><small>Compras</small></th>
															</tr>
															</thead>
															
															<tbody id="relatorioConteudo">
																										  
															<?php
																																   
																$sql = "SELECT DISTINCT A.COD_CLIENTE, 
																		  A.NUM_CARTAO, 
																		  A.NOM_CLIENTE, 
																		  C.NOM_ENTIDAD,
																		  A.DES_EMAILUS, 
																		  CASE 
																		   WHEN A.COD_SEXOPES IS NULL THEN
																			'I'
																		   WHEN A.COD_SEXOPES=0 THEN
																			'I'
																		   WHEN A.COD_SEXOPES=1 THEN
																			'M'
																		   WHEN A.COD_SEXOPES=2 THEN
																			'F'
																		   WHEN A.COD_SEXOPES=3 THEN
																			'I'
																		  END SEXO, 
																		  SUM(VAL_TOTVENDA) as VAL_TOTVENDA 

																		FROM CLIENTES A, VENDAS B, ENTIDADE C
																		WHERE A.COD_CLIENTE = B.COD_CLIENTE AND 
																		   A.COD_ENTIDAD=C.COD_ENTIDAD AND 
																		B.COD_EMPRESA = $cod_empresa AND
																		B.DAT_CADASTR_WS between '$dat_ini 00:00' AND '$dat_fim 23:59' AND																		
																		A.LOG_AVULSO = 'N' AND 
																		B.COD_UNIVEND IN ($lojasSelecionadas ) 
																		GROUP BY COD_CLIENTE
																		ORDER BY  SUM(VAL_TOTVENDA) DESC
																		LIMIT 100 ";
																		
																//fnEscreve($sql);
																
																$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																
																$count=0;
																while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																  {														  
																	$count++;																																		
																	switch ($qrListaPersonas['SEXO']) {
																		case "I": //indefinido
																			$mostraSexo = '<i class="fa fa-venus-mars f12" aria-hidden="true"></i>';
																			break;    
																		case "M": //masculino
																			$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
																			break;	
																		case "F": //feminino
																			$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
																			break;
																	}	
																								
																	echo"
																		<tr>
																		  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
																		  <td><small>".$qrListaPersonas['NOM_ENTIDAD']."</small></td>
																		  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
																		  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
																		  <td class='text-center'>".$mostraSexo."</td>
																		  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
																		  <td class='text-center'><small>".fnvalor($qrListaPersonas['VAL_TOTVENDA'],2)."</small></td>
																		</tr>
																		"; 
																	  }	
																
															?>
																
															</tbody>
															
															<!--
															<tfoot>
																<tr>
																	<th colspan="100">
																		<a class="btn btn-info btn-sm exportarCSV">Exportar &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
																	</th>
																</tr>														
															</tfoot>
															-->
														
														</table>
														
														<div class="push"></div>
														
														<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
														<input type="hidden" name="opcao" id="opcao" value="">
														<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
														<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">														
														
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
											url: "relatorios/ajxRelClientesTop100.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
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
				
		});	
			
		$(document).on('change', '#COD_EMPRESA', function(){ 
		   $("#dKey").val($("#COD_EMPRESA").val());
		});	

	
		function page(index){
			
			$("#pagina").val(index);
			$( "#formulario" )[0].submit();   			
			//alert(index);	
				
		}		
		
	
	</script>
	