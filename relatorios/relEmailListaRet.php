<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	
	//Página default
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$status_envio = 9;
	$cod_optout_ativo = 9;
	$status_leitura = 0;
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
			$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);		
			$status_leitura = fnLimpaCampo($_POST['STATUS_LEITURA']);			
			$status_envio = fnLimpaCampoZero($_POST['STATUS_ENVIO']);			
			$cod_optout_ativo = fnLimpaCampoZero($_POST['COD_OPTOUT_ATIVO']);			
			// $dat_ini = fnDataSql($_POST['DAT_INI']);
			// $dat_fim = fnDataSql($_POST['DAT_FIM']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			// fnEscreve($cod_campanha);
						
			if ($opcao != ''){
				
				
			}  

		}
	}

	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		// $cod_campanha = fnDecode($_GET['idc']);	
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

									<!-- <div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Final</label>
											
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div> -->

									<div class="col-md-2">
										<div class="form-group">
										
											<label for="inputName" class="control-label">Campanha</label>
												<select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
													<option value=""></option>						
													<?php

														$sql = "SELECT COD_CAMPANHA, DES_CAMPANHA FROM CAMPANHA 
																WHERE COD_EMPRESA = $cod_empresa 
																AND COD_EXT_CAMPANHA IS NOT NULL";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
														while($qrCamp = mysqli_fetch_assoc($arrayQuery)){
													?>

															<option value="<?=$qrCamp[COD_CAMPANHA]?>"><?=$qrCamp['DES_CAMPANHA']?></option>

													<?php
														}
													?>												
												</select>	
											<div class="help-block with-errors"></div>
											<script type="text/javascript">$("#formulario #COD_CAMPANHA").val('<?=$cod_campanha?>').trigger("chosen:updated");</script>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
										
											<label for="inputName" class="control-label">Leitura</label>
												<select data-placeholder="Selecione o status" name="STATUS_LEITURA" id="STATUS_LEITURA" class="chosen-select-deselect">
													<option value="9">Todos os emails</option>						
													<option value="1">Lidos</option>						
													<option value="0">Não lidos</option>												
												</select>	
											<div class="help-block with-errors"></div>
											<script type="text/javascript">$("#formulario #STATUS_LEITURA").val('<?=$status_leitura?>').trigger("chosen:updated");</script>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
										
											<label for="inputName" class="control-label">Status</label>
												<select data-placeholder="Selecione o status" name="STATUS_ENVIO" id="STATUS_ENVIO" class="chosen-select-deselect">
													<option value="9">Todos os emails</option>						
													<option value="1">Entregue</option>						
													<option value="2">Softbounce</option>						
													<option value="3">Hardbounce</option>						
												</select>	
											<div class="help-block with-errors"></div>
											<script type="text/javascript">$("#formulario #STATUS_ENVIO").val('<?=$status_envio?>').trigger("chosen:updated");</script>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
										
											<label for="inputName" class="control-label">Opt-Out</label>
												<select data-placeholder="Selecione o status" name="COD_OPTOUT_ATIVO" id="COD_OPTOUT_ATIVO" class="chosen-select-deselect">					
													<option value="9">Todos os emails</option>						
													<option value="1">Com Opt-Out</option>						
													<option value="0">Sem Opt-Out</option>						
												</select>	
											<div class="help-block with-errors"></div>
											<script type="text/javascript">$("#formulario #COD_OPTOUT_ATIVO").val('<?=$cod_optout_ativo?>').trigger("chosen:updated");</script>
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
							<div class="col-md-12">

								<div class="push20"></div>
								
								<table class="table table-bordered table-hover tablesorter">
								
								<thead>
									<tr>
									  <th><small>Email</small></th>
									  <th><small>Cliente</small></th>
									  <th><small>Dt. Nascime.</small></th>
									  <th><small>Dt. Leitura</small></th>
									  <th><small>Navegador</small></th>
									  <th><small>Modelo</small></th>
									  <th><small>Dt. Opt-Out</small></th>
									  <th><small>Motivo</small></th>
									</tr>
								</thead>
								<tbody id="relatorioConteudo">

								<?php

									if($status_envio == 9){
										$andStatus = "";
									}else{
										$andStatus = "AND STATUS_ENVIO = $status_envio";
									}

									if($cod_optout_ativo == 9){
										$andOptOut = "";
									}else{
										$andOptOut = "AND COD_OPTOUT_ATIVO = $cod_optout_ativo";
									}

									if($status_leitura == 9){
										$andLeitura = "";
									}else{
										$andLeitura = "AND COD_LEITURA = $status_leitura";
									}

									// if($cod_tpusuario != '' && $cod_tpusuario != 0){
									// 	$andTipoUsu = "AND U.COD_TPUSUARIO = $cod_tpusuario ";
									// }else{
									// 	$andTipoUsu = "";
									// }

									// if($log_estatus != '' && $log_estatus !='I'){
									// 	$andEstatus = "AND U.LOG_ESTATUS = '$log_estatus' ";
									// }else if($log_estatus =='I'){
									// 	$andEstatus = "AND (U.LOG_ESTATUS = '' OR U.LOG_ESTATUS IS NULL)  ";
									// }else{
									// 	$andEstatus = "";
									// }

									$sql = "SELECT COD_LISTA FROM EMAIL_LISTA_RET
										    WHERE COD_EMPRESA = $cod_empresa
										    AND COD_CAMPANHA = $cod_campanha 
										    $andStatus
										    $andOptOut
										    $andLeitura
									";

											//fnEscreve($sql);
											
											$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
											$total_itens_por_pagina = mysqli_num_rows($retorno);
											
											$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
											
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
									
										$sql = "SELECT DES_EMAILUS,
													   NOM_CLIENTE,
													   DAT_NASCIME,
													   dat_leitura AS DAT_LEITURA,
													   TIP_NAVEGADOR,
													   TIP_MODELO,
													   DAT_OPOUT,
													   DES_MOTIVO
												FROM EMAIL_LISTA_RET
											    WHERE COD_EMPRESA = $cod_empresa
											    AND COD_CAMPANHA = $cod_campanha 
											    $andStatus
										    	$andOptOut
											    $andLeitura
											    LIMIT $inicio,$itens_por_pagina";
										
										// fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);
										
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										
										$count=0;
										
										while ($qrRet = mysqli_fetch_assoc($arrayQuery))
										{

											$count++;
											
								?>
											<tr>
												<td><small><?=$qrRet['DES_EMAILUS']?></small></td>
												<td><small><?=$qrRet['NOM_CLIENTE']?></small></td>
												<td class="text-center"><small><?=$qrRet['DAT_NASCIME']?></small></td>
												<td class="text-center"><small><?=$qrRet['DAT_LEITURA']?></small></td>
												<td><small><?=$qrRet['TIP_NAVEGADOR']?></small></td>
												<td><small><?=$qrRet['TIP_MODELO']?></small></td>
												<td class="text-center"><small><?=$qrRet['DAT_OPOUT']?></small></td>
												<td><?=$qrRet['DES_MOTIVO']?></td>
											</tr>


								<?php											
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
												
						<input type="hidden" name="opcao" id="opcao" value="">
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
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {

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
											url: "relatorios/ajxEmailListaRet.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
				url: "relatorios/ajxEmailListaRet.do?opcao=paginar&id=<?=fnEncode($cod_empresa)?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(data){
					console.log(data);
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	
		
	</script>	
   