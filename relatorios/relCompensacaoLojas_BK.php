<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	//$hoje = fnFormatDate(date("Y-m-d"));
	$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	
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
									
									<?php 
									include "backReport.php"; 
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
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
																		
													
												</div>
													
										</fieldset>	
										
										<div class="push20"></div>
										
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover tablesorter">
												
													<thead>
														<tr>
														  <th class="{sorter:false}"></th>
														  <th class="{sorter:false}"><small></small></th>
														  <th class="{sorter:false} text-center"><small>CNPJ </small></th>
														  <th class="{sorter:false} text-center"><small>Valor a Receber</small></th>
														  <th class="{sorter:false} text-center"><small>Valor a Pagar</small></th>
														  <th class="{sorter:false} text-center"><small>Balanço</small></th>
														</tr>
													</thead>
																												
													<?php
													$sql = "call sp_retorna_rel_compensacao('$dat_ini', '$dat_fim','$lojasSelecionadas',$cod_empresa) ";
													//fnEscreve($sql);
													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
													  {
														$lojaLoop = $qrListaUnive['codigo_unidade_resgate'];

														$total_receber = $total_receber + $qrListaUnive['total_valor_a_receber'];
														$total_pagar = $total_pagar + $qrListaUnive['total_valor_a_pagar'];
														$total_balanco = $total_balanco + $qrListaUnive['Balanco'];
														
													?>
													
													<tr id="bloco_<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>">																
													  <td width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
													  <td width="19%"><small><b><?php echo $qrListaUnive['nome_unidade_resgate']; ?></b></small></th>
													  <td width="25%" class="text-center"><div id="9total_col0a_<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>"><small><?php echo $qrListaUnive['cpfcnpj_unidade_resgate']; ?></small></div></th>
													  <td width="10%" class="text-center"><b><small>R$ </small><div style="display: inline;" id="total_col4a_<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>"><?php echo fnValor($qrListaUnive['total_valor_a_receber'],2); ?></b></div></th>
													  <td width="19%" class="text-center"><b><small>R$ </small><div style="display: inline;" id="total_col4a_<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>"><?php echo fnValor($qrListaUnive['total_valor_a_pagar'],2); ?></b></div></th>
													  <td width="19%" class="text-center"><b><small>R$ </small><div style="display: inline;" id="total_col6a_<?php echo $qrListaUnive['codigo_unidade_resgate']; ?>"><?php echo fnValor($qrListaUnive['Balanco'],2); ?></b></div></th>
													</tr>
													
													<?php													
													  }	
													?>													
												
													<!--
													<tr>
													  <td></td>
													  <td></td>
													  <td></td>
													  <td class="text-center"><b><small>R$ </small><?php echo fnValor($total_receber,2); ?></b></td>
													  <td class="text-center"><b><small>R$ </small><?php echo fnValor($total_pagar,2); ?></b></td>
													  <td class="text-center"><b><small>R$ </small><?php echo fnValor($total_balanco,2); ?></b></td>
													</tr>														  
													-->	  
												
													</tbody>
													
													<tfoot>
														<tr>
															<th colspan="100">
																<a class="btn btn-info btn-sm exportarLojasCSV">Exportar Lojas &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a> &nbsp;&nbsp;
																<a class="btn btn-info btn-sm exportarDetalhesCSV">Exportar Detalhes &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a> &nbsp;&nbsp;
																<!-- <a class="btn btn-info btn-sm exportarCSV disabled">Exportar Clientes &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a> -->
															</th>
														</tr>														
													</tfoot>													
													
												</table>
																								
											</div>
											
										</div>
																
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
									<div class="push50"></div>
																		
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
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
			
			// Carregar totais de quantidade na linhas
			/*
			$("div[id^='total_col']").each(function( index ) {
				var total = 0;
				
				// Se não tiver a classe porcent faça
				if(!$(this).hasClass('porcent')){
					$(".qtde_col" + $(this).attr('id').replace('total_col','')).each(function(index, item) {
					  total += limpaValor($(this).text());
					});

					var totalVar = $('#' + $(this).attr('id'));
					totalVar.unmask();
					totalVar.text(total.toFixed(2));				 
					totalVar.mask("#.##0,00", {reverse: true});	

				}else{
					var numLinha = $(this).attr('id').replace('total_col3_', '');
					var result = limpaValor($('#total_col2_' + numLinha).text()) / (limpaValor($('#total_col1_' + numLinha).text()) + limpaValor($('#total_col2_' + numLinha).text())) * 100;
					var totalVar = $('#' + $(this).attr('id'));
					totalVar.unmask();					
					totalVar.text(result.toFixed(2));				 
					totalVar.mask("#.##0,00", {reverse: true});					
				}
			});
			*/			
			
			$("div[id^='total_col0']").each(function( index ) {
				$(this).text(parseFloat($(this).text()));
			});			

			$("div[id^='total_col1']").each(function( index ) {
				$(this).text(parseFloat($(this).text()));
			});
			
			$("div[id^='total_col2']").each(function( index ) {
				$(this).text(parseFloat($(this).text()));
			});		

			$(".exportarLojasCSV").click(function() {
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
											url: "relatorios/ajxRelCompensacaoLojas.do?opcao=exportarLojas&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>", 
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
				
			$(".exportarDetalhesCSV").click(function() {
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
											url: "relatorios/ajxRelCompensacaoLojas.do?opcao=exportarDetalhes&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
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
	   
 
		function abreDetail(idBloco){
			var idItem = $('.detail_' + idBloco);
			
			if (!idItem.is(':visible')){
				var pDataInicial = $('#DAT_INI').val();
				var pDataFinal = $('#DAT_FIM').val();
				$.ajax({
					type: "GET",
					url: "relatorios/ajxRelCompensacaoLojas.do",
					data: { DAT_INI:pDataInicial, DAT_FIM:pDataFinal, cod_empresa:<?php echo $cod_empresa; ?>, loja: idBloco, opcao : "abreDetail"},
					beforeSend:function(){
						$('#bloco_' + idBloco).after('<tr id="loadDetail"><th colspan = "6"><div class="loading" style="width: 100%;"></div></tr></th>');
					},
					success:function(data){
						$('#loadDetail').remove();
						$('#bloco_' + idBloco).after(data); 
						$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
					},
					error:function(){
						idItem.html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}
		   
	</script>	