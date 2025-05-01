<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	

	$log_externo = 'N';
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
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
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
			$nom_indicador = fnLimpaCampo($_REQUEST['NOM_INDICADOR']);
			if (empty($_REQUEST['LOG_EXTERNO'])) {$log_externo='N';}else{$log_externo=$_REQUEST['LOG_EXTERNO'];}

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
		$cod_campanha = fnDecode($_GET['idc']);
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

	if($log_externo == 'S'){
		$check_externo = 'checked';
	}else{
		$check_externo = '';
	}
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojasAut);
	//fnEscreve($usuReportAdm);
	//fnEscreve($lojasReportAdm);
	
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
	
	<div class="row" id="div_Report">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					
					<?php 
					//$formBack = "1015";
					include "atalhosPortlet.php"; 
					?>	
					
				</div>

				<?php 
		          $abaListaApoiador = 1598;
		          include "abasListaApoiador.php";
	            ?>

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

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Apoiador</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" value="<?=$nom_cliente?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Indicador</label>
											<input type="text" class="form-control input-sm" name="NOM_INDICADOR" id="NOM_INDICADOR" maxlength="50" value="<?=$nom_indicador?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">   
										<div class="form-group">
											<label for="inputName" class="control-label">Somente Cadastros Importados</label> 
											<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="LOG_EXTERNO" id="LOG_EXTERNO" class="switch" value="S" <?=$check_externo?>>
											<span></span>
											</label>
										</div>
									</div>

								</div>

								<div class="row">
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>					
									
								</div>
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<?php if ($log_externo == 'N'){ ?>
											<th class="text-center {sorter:false}" width="40"><small>Todos</small><br><input type='checkbox' onclick="$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);attListaClientes();" id="selectAll"></th>
											<?php } ?>
											<th>Cod. Externo</th>
											<th>Apoiador</th>
											<th>Dt. Nascimen.</th>
											<th>Dt. Cadastro</th>
											<th>Indicador</th>
											<?php if ($log_externo == 'N'){ ?>
											<th class="{sorter:false}"></th>
											<?php } ?>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										if($nom_cliente != ""){
											$andCliente = "AND NOM_CLIENTE LIKE '%$nom_cliente%'";
										}else{
											$andCliente = "";
										}

										if($nom_indicador != ""){
											$andIndicador = "AND NOM_INDICADOR LIKE '%$nom_indicador%'";
										}else{
											$andIndicador = "";
										}

										if($cod_indicad != ""){
											$andCodIndicador = "AND COD_INDICAD = $cod_indicad";
										}else{
											$andCodIndicador = "";
										}

										if ($log_externo == 'S'){ 													
											$andImport = "AND LOG_IMPORT = 'S'"; 
										}else {
											$andImport = "AND LOG_IMPORT = 'N'"; 
										}
									
										$sql = "SELECT COD_CLIENTE 
												FROM CLIENTES_EXTERNO 
												WHERE COD_EMPRESA = $cod_empresa
												$andImport
												$andCliente
												$andIndicador
												$andCodIndicador
												";
										//fnTestesql(connTemp($cod_empresa,''),$sql);		
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";

										$sql = "SELECT COD_CLIENTE,
													   NOM_CLIENTE,
													   DAT_NASCIME,
													   DAT_CADASTR,
													   NOM_INDICADOR
												FROM CLIENTES_EXTERNO 
												WHERE COD_EMPRESA = $cod_empresa
												$andImport
												$andCliente
												$andIndicador
												$andCodIndicador
												order by NOM_CLIENTE desc 
												LIMIT $inicio,$itens_por_pagina
												";
										
										// echo($sql);
                                                                               
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															  
										$count=0;
										while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
										{								

											$count++;

										?>
												<tr>
													<?php if ($log_externo == 'N'){ ?>
													<td class='text-center'><input type='checkbox' name='radio_<?=$count?>' onclick='attListaClientes()'>&nbsp;</td>
													<?php } ?>
													<td><?=$qrApoia['COD_CLIENTE']?></td>
													<td><?=$qrApoia['NOM_CLIENTE']?></td>
													<td><?=$qrApoia['DAT_NASCIME']?></td>
													<td><?=fnDataFull($qrApoia['DAT_CADASTR'])?></td>
													<td><?=$qrApoia['NOM_INDICADOR']?></td>
													<?php if ($log_externo == 'N'){ ?>
													<td class="text-center">
														<small>
															<div class="btn-group dropdown dropleft">
																<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	ações &nbsp;
																	<span class="fas fa-caret-down"></span>
																</button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li><a href='javascript:void(0)' class="addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($qrApoia[COD_CLIENTE])?>&pop=true&op=LISTA" data-title="Busca Indicador">Buscar Indicador</a></li>
																	<li><a href='javascript:void(0)' onclick='importaCliente("<?=fnEncode($qrApoia[COD_CLIENTE])?>","unico")'>Importar Apoiador</a></li>
																	<li class="divider"></li>
																	<li><a href='javascript:void(0)' onclick='importaCliente("<?=fnEncode($qrApoia[COD_CLIENTE])?>","exc")'>Excluir Apoiador</a></li>
																	<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																</ul>
															</div>
														</small>
													</td>
													<?php } ?>
												</tr>

												<input type="hidden" name="ret_COD_CLIENTE_<?=$count?>" id="ret_COD_CLIENTE_<?=$count?>" value="<?=$qrApoia[COD_CLIENTE]?>">
										<?php

										}											
                                                                                         
									?>
										</tbody>

										<tfoot>
											<?php if ($log_externo == 'N'){ ?>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm" id="addCadImport" disabled> <i class="fal fa-save" aria-hidden="true"></i>&nbsp; Importar Vários Apoiadores</a>
												</th>
											</tr>
											<?php } ?>													
											<tr>
											  <th class="" colspan="100">
												<center><ul id="paginacao" class="pagination-sm"></ul></center>
											  </th>
											</tr>
										</tfoot>
										
									</table>
																					
								</div>
							
								
							</div>
						</div>
							
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?=$cod_indicad?>">
						<input type="hidden" name="LOG_INDICACAO" id="LOG_INDICACAO" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
					<div class="push50"></div>									
					
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
	
    <script>
	
		var listaClientes = [],
			current_page = 1;

		//datas
		$(function () {

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			jQuery('#paginacao').on('page',function(event, page) {
			    current_page = page;
			});
			
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

			$("#addCadImport").click(function(){
				if(!$(this).is("[disabled]")){
					importaCliente(JSON.stringify(listaClientes),"multiplo")
				}
			});

			//modal close
			$('.modal').on('hidden.bs.modal', function () {

				reloadPage(current_page);

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
											url: "relatorios/ajxRelCupons.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxListaApoiadorExterno.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
					// console.log(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

		function importaCliente(cod_cliente,opcao) {
			$.ajax({
				type: "POST",
				url: "ajxListaApoiadorExterno.do?opcao="+opcao,
				data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_CLIENTE: cod_cliente},
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){		
					window.location.reload();						
					reloadPage(current_page);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

		function attListaClientes(){

			listaClientes = [];

			$("table tr").each(function(index) {

				if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){

					var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');

					listaClientes.push($("#ret_COD_CLIENTE_"+index).val());

				}

			});
			
				
	        if (listaClientes.length === 0) {

	        	$('#addCadImport').attr('disabled',true);

	        }else{
	        	
	        	$('#addCadImport').removeAttr('disabled');

	        }


		    // console.log(listaClientes);
			
		}

		
	</script>	
   