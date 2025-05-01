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
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);
			$num_cgcecpf_ind = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF_IND']));
			$nom_cliente_ind = fnLimpaCampo($_REQUEST['NOM_CLIENTE_IND']);

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
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo de Lojas</label>
											<?php include "grupoLojasComboMulti.php"; ?>
										</div>
									</div>	
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Região</label>
											<?php include "grupoRegiaoMulti.php"; ?>
										</div>
									</div>

								</div>

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

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Cliente</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?=$nom_cliente?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?=$num_cgcecpf?>" maxlength="18">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Indicado</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE_IND" id="NOM_CLIENTE_IND" value="<?=$nom_cliente_ind?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">CPF do Indicado</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF_IND" id="NUM_CGCECPF_IND" value="<?=$num_cgcecpf_ind?>" maxlength="18">
											<div class="help-block with-errors"></div>
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

						<?php

							if($nom_cliente != ""){
								$andNome = "AND C.NOM_CLIENTE LIKE '%$nom_cliente%'";
							}else{
								$andNome = "";
							}

							if($num_cgcecpf != ""){
								$andCpf = "AND C.NUM_CGCECPF = '$num_cgcecpf'";
							}else{
								$andCpf = "";
							}

							if($nom_cliente_ind != ""){
								$andNomeInd = "AND C.COD_INDICAD IN(SELECT COD_CLIENTE FROM CLIENTES WHERE NOM_CLIENTE LIKE '%$nom_cliente_ind%')";
							}else{
								$andNomeInd = "";
							}

							if($num_cgcecpf_ind != ""){
								$andCpfInd = "AND C.COD_INDICAD = (SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$num_cgcecpf_ind')";
							}else{
								$andCpfInd = "";
							}

							$sql = "SELECT 
										COUNT(BE.COD_CLIENTE) AS QTD_CLIENTES, 
										COUNT(DISTINCT C.COD_INDICAD) AS QTD_INDICAD 
										FROM BRINDEEXTRA AS BE
										LEFT JOIN CLIENTES AS C ON BE.COD_CLIENTE = C.COD_CLIENTE
										WHERE BE.COD_EMPRESA = $cod_empresa
										AND BE.dat_CADASTR >= '$dat_ini 00:00:00'
										AND BE.dat_CADASTR <= '$dat_fim 23:59:59'
										$andNome
										$andCpf
										$andNomeInd 
										$andCpfInd";

							$qrTot = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
							
						?>								
						
						<table class="table table-hover">
						
						  <thead>
							<tr>
							  <th class="text-center text-info"></th>
							  <th class="text-center text-info"></th>
							  <th class="text-center text-info">Clientes Indicados<br> <b><?=fnValor($qrTot['QTD_CLIENTES'],0)?></b></th>
							  <th class="text-center text-info">Indicadores <br> <b><?=fnValor($qrTot['QTD_INDICAD'],0)?></b></th>
							  <th class="text-center text-info"></th>
							  <th class="text-center text-info"></th>
							</tr>
						  </thead>
						  
						</table>
						
						<div class="push10"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th></th>
											<th>Cliente Indicado</th>
											<th>CPF Indicado</th>
											<th>Indicador</th>
											<th>CPF Indicador</th>
											<th>Brinde</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

									<?php

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT 
													BE.DES_OPERACA, 
													CL.NOM_CLIENTE AS CLIENTE_PRINCIPAL,
													CL.NUM_CGCECPF AS CGCECPF_PRINCIPAL,
													IC.NOM_CLIENTE AS CLIENTE_INDICADOR,
													IC.NUM_CGCECPF AS CGCECPF_INDICADOR, 
													PC.DES_PRODUTO 
												FROM 
													brindeextra AS BE 
												LEFT JOIN 
													CLIENTES AS CL ON CL.COD_CLIENTE = BE.COD_CLIENTE
												LEFT JOIN 
													CLIENTES AS IC ON IC.COD_CLIENTE = CL.COD_INDICAD
												LEFT JOIN 
													produtocliente AS PC ON PC.COD_PRODUTO = BE.COD_PRODUTO
												WHERE 
													BE.COD_EMPRESA = $cod_empresa
												AND BE.dat_CADASTR >= '$dat_ini 00:00:00'
												AND BE.dat_CADASTR <= '$dat_fim 23:59:59'
												$andNome
												$andCpf
												$andNomeInd 
												$andCpfInd
												";
										//$sql = "SELECT * FROM BRINDEEXTRA";
										
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															  
										$count=0;
										while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
										  {				
											$count++;	

											echo"
												<tr>
												  <td></td>
												  <td>".$qrCupom['CLIENTE_PRINCIPAL']."</td>
												  <td>".$qrCupom['CGCECPF_PRINCIPAL']."</td>
												  <td>".$qrCupom['CLIENTE_INDICADOR']."</td>
												  <td>".$qrCupom['CGCECPF_INDICADOR']."</td>
												  <td>".$qrCupom['DES_PRODUTO']."</td>
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
										</tfoot>
										
									</table>
																					
								</div>
							
								
							</div>
						</div>
							
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
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

			// var numPaginas = <?php echo $numPaginas; ?>;
			// if(numPaginas != 0){
			// 	carregarPaginacao(numPaginas);
			// }
			
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
											url: "relatorios/ajxRelIndicaCli.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
				url: "relatorios/ajxRelCupons.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
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
   