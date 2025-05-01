<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$hashLocal = mt_rand();	
	
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
		          $abaListaApoiador = 1602;
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
						
						<div>
							<div class="row">

								<!-- <div class="col-md-12">
									<a href="http://externo.bunker.mk/cadastro/cadastro.do?id=<?=fnEncode($cod_empresa)?>" target="_blank" class="btn btn-info"><span class="fal fa-external-link"></span>&nbsp; Acessar Cadastro Externo</a>
								</div> -->

								<div class="push10"></div>

								<div class="col-md-12">

									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th>Indicador</th>
											<th>Link</th>
											<th class="{sorter:false}" width="5%">Acessar</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<tr>
											<td>Cadastro Externo Gabinete</td>
											<td><input type="text" class="form-control input-sm leitura" readonly="readonly" name="ret_DES_LINK_0" id="ret_DES_LINK_0" value="http://externo.bunker.mk/cadastro/cadastro.do?id=<?=fnEncode($cod_empresa)?>"></td>
											<td class="text-center">
								           		<small>
								           			<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
													    </button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a href='javascript:void(0)' onclick="copiar(0)">Copiar Link</a></li>
															<li><a href="http://externo.bunker.mk/cadastro/cadastro.do?id=<?=fnEncode($cod_empresa)?>" target="_blank">Acessar </a></li>
															<li class="divider"></li>
															<li><a href="javascript:void(0)" class='addBox' data-url="action.do?mod=<?php echo fnEncode(1606)?>&id=<?=fnEncode($cod_empresa)?>&idi=<?=fnEncode(0)?>&pop=true" data-title="QrCode - Cadastro Externo">QrCode </a></li>
															<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
														</ul>
													</div>
								           		</small>
								           	</td>
										</tr>						

									<?php

										if($cod_indicad != ""){
											$andIndicador = "AND A.COD_INDICAD = $cod_indicad";
										}else{
											$andIndicador = "";
										}
									
										$sql = "SELECT DISTINCT A.COD_INDICAD 
												FROM CLIENTES A
												WHERE A.COD_EMPRESA = $cod_empresa
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

										$sql = "SELECT A.COD_INDICAD, 
													   B.NOM_CLIENTE
												FROM CLIENTES A
												INNER JOIN CLIENTES B ON A.COD_INDICAD = B.COD_CLIENTE
												WHERE A.COD_EMPRESA = $cod_empresa
												$andIndicador
												GROUP BY A.COD_INDICAD
												ORDER BY B.NOM_CLIENTE
												";
										
										//fnEscreve($sql);
                                                                               
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															  
										$count=0;
										while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
										{								

											$count++;

										?>
												<tr>
													<td><?=$qrApoia['NOM_CLIENTE']?></td>
													<td><input type="text" class="form-control input-sm leitura" readonly="readonly" name="ret_DES_LINK_<?=$count?>" id="ret_DES_LINK_<?=$count?>" value="http://externo.bunker.mk/cadastro/cadastro.do?id=<?=fnEncode($cod_empresa)?>&idi=<?=fnEncode($qrApoia[COD_INDICAD])?>"></td>
													<td class="text-center">
										           		<small>
										           			<div class="btn-group dropdown dropleft">
																<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	ações &nbsp;
																	<span class="fas fa-caret-down"></span>
															    </button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li><a href='javascript:void(0)' onclick="copiar(<?=$count?>)">Copiar Link</a></li>
																	<li><a href="http://externo.bunker.mk/cadastro/cadastro.do?id=<?=fnEncode($cod_empresa)?>&idi=<?=fnEncode($qrApoia[COD_INDICAD])?>" target="_blank">Acessar </a></li>
																	<li class="divider"></li>
																	<li><a href="javascript:void(0)" class='addBox' data-url="action.do?mod=<?php echo fnEncode(1606)?>&id=<?=fnEncode($cod_empresa)?>&idi=<?=fnEncode($qrApoia[COD_INDICAD])?>&pop=true" data-title="QrCode - <?=$qrApoia['NOM_CLIENTE']?>">QrCode </a></li>
																	<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																</ul>
															</div>
										           		</small>
										           	</td>
												</tr>

										<?php

										}											
                                                                                         
									?>
										</tbody>

										<!-- <tfoot>													
											<tr>
											  <th class="" colspan="100">
												<center><ul id="paginacao" class="pagination-sm"></ul></center>
											  </th>
											</tr>
										</tfoot> -->
										
									</table>
																					
								</div>
							
								
							</div>
						</div>
							
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
						<input type="hidden" name="opcao" id="opcao" value="">
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

			// //modal close
			// $('.modal').on('hidden.bs.modal', function () {

			// 	reloadPage(current_page);

			// });

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

		function copiar(index) {

			var copyText = document.getElementById("ret_DES_LINK_"+index);
			copyText.select();
			// copyText.setSelectionRange(0, 99999);
			document.execCommand("copy");

		}

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
					console.log(data);										
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
   