<?php
include '../_system/_functionsMain.php';
	
	//echo fnDebug('true');
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";

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
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
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
										
										<div class="push20"></div>										
										
										<div>
											<div class="row">
												<div class="col-lg-12">

													<div class="no-more-tables">
													
																										   
														<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
															<thead>
															<tr>
															  <th><small>Nome</small></th>
															  <th><small>Cartão</small></th>
															  <th><small>Código</small></th>
															  <th><small>Un. Autorizadas</small></th>
															</tr>
															</thead>
															
															<tbody id="relatorioConteudo">
																										  
															<?php
																
																$sql = "select COD_ENTIDAD, NOM_CLIENTE,COD_EXTERNO,NUM_CARTAO,COD_MULTEMP from clientes where COD_TPCLIENTE > 6 order by NOM_CLIENTE ";
																		
																//fnEscreve($sql);
																
																$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																
																$count=0;
																while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																  {														  
																	$count++;
																	$cod_multemp = $qrListaPersonas['COD_MULTEMP'];
																	
																	echo"
																		<tr>
																		  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
																		  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
																		  <td><small>".$qrListaPersonas['COD_EXTERNO']."</small></td>
																		  <td><small> ";

																			$sql1 = "select NOM_FANTASI from UNIDADEVENDA where COD_UNIVEND in ($cod_multemp) order by NOM_FANTASI ";
																			//fnEscreve($sql1);
																			
																			$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
																			
																			$count=0;
																			while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery1))
																			  {																										
																				echo"
																					  <span class='f12'>●".$qrListaUnidades['NOM_FANTASI']."</span> &nbsp;&nbsp;
																					  ";
																				  }
																		  
																	echo"
																		  </small></td>
																		</tr>
																		"; 
																	  }	
																
															?>
																
															</tbody>
															
															<tfoot>
																<tr>
																	<th colspan="100">
																		<a class="btn btn-info btn-sm exportarCSV">Exportar &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
																	</th>
																</tr>														
															</tfoot>
														
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
	