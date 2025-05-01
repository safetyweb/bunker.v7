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
			$cod_univend = fnLimpaCampoZero($_POST['COD_UNIVEND']);
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);

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
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
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
	// include "unidadesAutorizadas.php"; 
	
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

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasCombo.php"; ?>
										</div>
									</div>

								<!-- </div>

								<div class="row"> -->

									<!-- <div class="col-md-2">
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
									</div> -->

									<!-- <div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Apoiador</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" value="<?=$nom_cliente?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?=$num_cgcecpf?>">
											<div class="help-block with-errors"></div>
										</div>
									</div> -->
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
														
									
								</div>
									
						</fieldset>	

						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">				
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						</form>
						
						<div class="push20"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<?php

										if($nom_cliente != ""){
											$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
										}else{
											$andNome = "";
										}

										if($num_cgcecpf != ""){
											$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
										}else{
											$andCpf = "";
										}
										// Filtro por Grupo de Lojas
										// include "filtroGrupoLojas.php";
										
										$sql = "SELECT 
												COUNT(B.COD_CLIENTE) AS QTD_CLIENTE,
												SUM(a.VAL_CONTRAT) VAL_CONTRAT, 
												SUM(IFNULL((SELECT SUM(val_credito)
												FROM caixa
												WHERE caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) VAL_PAGO, 
												SUM((a.VAL_CONTRAT- IFNULL((SELECT SUM(val_credito)
												FROM caixa
												WHERE caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0))) AS VALOR_APAGAR
												FROM CONTRATO_ELEITORAL A
												INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
												WHERE A.COD_UNIVEND = $cod_univend 
												AND A.COD_EMPRESA = $cod_empresa 
												AND A.COD_EXCLUSA = 0
												AND A.TIP_CONTRAT NOT IN(4,5)";
												   
										// fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);	
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										$qrTotais = mysqli_fetch_assoc($arrayQuery);
										$qtd_cliente = $qrTotais['QTD_CLIENTE'];
										$val_contrat = $qrTotais['VAL_CONTRAT'];
										$val_pago = $qrTotais['VAL_PAGO'];
										$valor_apagar = $qrTotais['VALOR_APAGAR'];
										
									?>									
									
									<table class="table table-hover">
									
									  <thead>
										<tr>
										  <th class="text-center text-info">Apoiadores<b> &nbsp; <?php echo fnValor($qtd_cliente,0); ?></b></th>
										  <th class="text-center text-info">Vl. Contratos<b> &nbsp; <?php echo fnValor($val_contrat,2); ?></b></th>
										  <th class="text-center text-info">Vl. Pago<b> &nbsp; <?php echo fnValor($val_pago,2); ?></b></th>
										  <th class="text-center text-info">Vl. a Pagar  &nbsp; <b>R$ <?php echo fnValor($valor_apagar,2); ?></b></th>
										</tr>
									  </thead>
									  
									</table>
									
									<div class="push10"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th>Cod. Externo</th>
											<th>Nome</th>
											<th>CPF/CNPJ</th>
											<th>Tp. Contrato</th>
											<th class="text-right">Vl. Contrato</th>
											<th class="text-right">Vl. Pago</th>
											<th class="text-right">Vl. a Pagar</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";
									
										$sql = "SELECT a.COD_CLIENTE,
													   b.COD_EXTERNO,
													   b.NOM_CLIENTE,
													   b.NUM_CGCECPF,
														case when a.TIP_CONTRAT=1 then
														'Genérico'
														when a.TIP_CONTRAT=2 then
														'Cabo Eleitoral'
														when a.TIP_CONTRAT=3 then
														'Coordenador Cabo Eleitoral'
														when a.TIP_CONTRAT=4 then
														'Cessão Serviços'
														when a.TIP_CONTRAT=5 then
														'Cessão Gratuita de Veículos'
														END AS TIP_CONTRATO,
														a.VAL_CONTRAT,
														IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)VAL_PAGO,
														(a.VAL_CONTRAT-
														IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) AS VALOR_APAGAR

												FROM CONTRATO_ELEITORAL A
												INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
												WHERE A.COD_UNIVEND = $cod_univend
												AND A.COD_EMPRESA = $cod_empresa
												AND A.COD_EXCLUSA = 0
												AND A.TIP_CONTRAT NOT IN(4,5)";
										//fnTestesql(connTemp($cod_empresa,''),$sql);		
										// fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";

										$sql = "SELECT a.COD_CLIENTE,
													   b.COD_EXTERNO,
													   b.NOM_CLIENTE,
													   b.NUM_CGCECPF,
													   b.NUM_CGCECPF,
													   b.LOG_JURIDICO,
														case when a.TIP_CONTRAT=1 then
														'Genérico'
														when a.TIP_CONTRAT=2 then
														'Cabo Eleitoral'
														when a.TIP_CONTRAT=3 then
														'Coordenador Cabo Eleitoral'
														when a.TIP_CONTRAT=4 then
														'Cessão Serviços'
														when a.TIP_CONTRAT=5 then
														'Cessão Gratuita de Veículos'
														END AS TIP_CONTRATO,
														a.VAL_CONTRAT,
														IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)VAL_PAGO,
														(a.VAL_CONTRAT-
														IFNULL((SELECT SUM(val_credito) from caixa WHERE  caixa.cod_contrat=a.COD_CONTRAT AND caixa.TIP_LANCAME='D' and caixa.cod_cliente=b.COD_CLIENTE AND caixa.cod_exclusa=0 AND caixa.cod_empresa=a.cod_empresa),0)) AS VALOR_APAGAR

												FROM CONTRATO_ELEITORAL A
												INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
												WHERE A.COD_UNIVEND = $cod_univend
												AND A.COD_EMPRESA = $cod_empresa
												AND A.COD_EXCLUSA = 0
												AND A.TIP_CONTRAT NOT IN(4,5)
												LIMIT $inicio, $itens_por_pagina";
										//fnEscreve($sql);
                                                                                //fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															  
										$count=0;
										while ($qrContrat = mysqli_fetch_assoc($arrayQuery))
										  {		

											$letraPessoa = "F";

											if($qrContrat[LOG_JURIDICO] == "S"){
											    $letraPessoa = "J";
											}						

											$count++;	
											echo"
												<tr>
												  <td>".$qrContrat['COD_EXTERNO']."</td>
												  <td>".$qrContrat['NOM_CLIENTE']."</td>
												  <td class='cpfcnpj'>".fnCompletaDoc($qrContrat['NUM_CGCECPF'],$letraPessoa)."</td>
												  <td>".$qrContrat['TIP_CONTRATO']."</td>
												  <td class='text-right'>R$ ".fnValor($qrContrat['VAL_CONTRAT'],2)."</td>
												  <td class='text-right'>R$ ".fnValor($qrContrat['VAL_PAGO'],2)."</td>
												  <td class='text-right'>R$ ".fnValor($qrContrat['VALOR_APAGAR'],2)."</td>
												</tr>
												"; 
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
						</div>
						
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
											url: "relatorios/ajxRelCaixaCampanha.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
				url: "relatorios/ajxRelCaixaCampanha.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
					console.log(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	

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

		
	</script>	
   