<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$dat_ini2="";
	$dat_fim2="";
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
			$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
			$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];

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
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" && strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31"){
		$dat_ini = fnDataSql($dias30); 
		$dat_ini2 = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31" && strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
		$dat_fim2 = fnDataSql($hoje); 
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

									<div class="col-md-5">

										<fieldset>
											<legend>1º Período</legend>

											<div class="col-md-6">
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
											
											<div class="col-md-6">
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

										</fieldset>

									</div>

									<div class="col-md-5">

										<fieldset>
											<legend>2º Período</legend>

											<div class="col-md-6">
												<div class="form-group">
													<label for="inputName" class="control-label required">Data Inicial</label>
													
													<div class="input-group date datePicker" id="DAT_INI_GRP2">
														<input type='text' class="form-control input-sm data" name="DAT_INI2" id="DAT_INI2" value="<?php echo fnFormatDate($dat_ini2); ?>" required/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
													<div class="help-block with-errors"></div>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<label for="inputName" class="control-label required">Data Final</label>
													
													<div class="input-group date datePicker" id="DAT_FIM_GRP2">
														<input type='text' class="form-control input-sm data" name="DAT_FIM2" id="DAT_FIM2" value="<?php echo fnFormatDate($dat_fim2); ?>" required/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</fieldset>

									</div>
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
														
									
								</div>
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div class="row">
							
							<div class="col-md-4">

								<fieldset>
									<legend>1º Período</legend>

									<table class="table table-hover">
										
										 <thead>

											<tr>
											  	<th class="text-center text-info"><small><small>Fat.<br></small><b> &nbsp; <span id="tot_FATURAMENTO_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>TM<br></small><b> &nbsp; <span id="tot_VAL_TICKET_MEDIO_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Tran. <br></small><b> &nbsp; <span id="tot_QTD_TRANSACAO_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_QTD_ITENS_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_QTD_CLIENTE_P1"></span></b></small></th>
										  	</tr>

										 </thead>

									</table>

								</fieldset>

							</div>

							<div class="col-md-4">

								<fieldset>
									<legend>2º Período</legend>

									<table class="table table-hover">
										
										 <thead>

											<tr>
											  	<th class="text-center text-info"><small><small>Fat.<br></small><b> &nbsp; <span id="tot_FATURAMENTO_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>TM<br></small><b> &nbsp; <span id="tot_VAL_TICKET_MEDIO_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Tran. <br></small><b> &nbsp; <span id="tot_QTD_TRANSACAO_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_QTD_ITENS_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_QTD_CLIENTE_P2"></span></b></small></th>
										  	</tr>

										 </thead>

									</table>

								</fieldset>

							</div>

							<div class="col-md-4">

								<fieldset>
									<legend>Variação Percentual</legend>

									<table class="table table-hover">
										
										<thead>
											<tr>
											  	<th class="text-center text-info"><small><small>Fat.<br></small><b> &nbsp; <span id="tot_PERC_FATURAMENTO"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>TM<br></small><b> &nbsp; <span id="tot_PERC_TICKET_MEDIO"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Tran. <br></small><b> &nbsp; <span id="tot_PERC_TRANSACAO"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_PERC_ITENS"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_PERC_CLIENTE"></span>%</b></small></th>
											</tr>
										</thead>

									</table>

								</fieldset>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">
								
								<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th><small><small>Loja</small></small></th>
											<th class="text-right"><small><small>Fat.</small></small></th>
											<th class="text-right"><small><small>Tkt. Med.</small></small></th>
											<th><small><small>Tran.</small></small></th>
											<th><small><small>Itens</small></small></th>											
											<th><small><small>Clientes</small></small></th>
											<th class="{sorter:false}"></th>
											<th class="text-right"><small><small>Fat.</small></small></th>
											<th class="text-right"><small><small>Tkt. Med.</small></small></th>
											<th><small><small>Tran.</small></small></th>
											<th><small><small>Itens</small></small></th>											
											<th><small><small>Clientes</small></small></th>											
											<th class="{sorter:false}"></th>											
											<th><small><small>%Fat.</small></small></th>											
											<th><small><small>%Tkt.</small></small></th>											
											<th><small><small>%Tran.</small></small></th>											
											<th><small><small>%Itens</small></small></th>											
											<th><small><small>%Cli.</small></small></th>											
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "CALL SP_RELAT_PERCENTUAL_PERIODO_VENDA ( '$dat_ini' , '$dat_fim'  , '$dat_ini2' , '$dat_fim2', '$lojasSelecionadas' , $cod_empresa )";

										$tot_FATURAMENTO_P1 = "";
										$tot_VAL_TICKET_MEDIO_P1 = "";
										$tot_QTD_TRANSACAO_P1 = "";
										$tot_QTD_ITENS_P1 = "";
										$tot_QTD_CLIENTE_P1 = "";
										$tot_FATURAMENTO_P2 = "";
										$tot_VAL_TICKET_MEDIO_P2 = "";
										$tot_QTD_TRANSACAO_P2 = "";
										$tot_QTD_ITENS_P2 = "";
										$tot_QTD_CLIENTE_P2 = "";
										$tot_PERC_FATURAMENTO = "";
										$tot_PERC_TICKET_MEDIO = "";
										$tot_PERC_TRANSACAO = "";
										$tot_PERC_ITENS = "";
										$tot_PERC_CLIENTE = "";
										
										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
														
										$count=0;
										while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
										  {	

										  	$tot_FATURAMENTO_P1 += $qrCupom['FATURAMENTO_P1'];
											$tot_VAL_TICKET_MEDIO_P1 += $qrCupom['VAL_TICKET_MEDIO_P1'];
											$tot_QTD_TRANSACAO_P1 += $qrCupom['QTD_TRANSACAO_P1'];
											$tot_QTD_ITENS_P1 += $qrCupom['QTD_ITENS_P1'];
											$tot_QTD_CLIENTE_P1 += $qrCupom['QTD_CLIENTE_P1'];
											$tot_FATURAMENTO_P2 += $qrCupom['FATURAMENTO_P2'];
											$tot_VAL_TICKET_MEDIO_P2 += $qrCupom['VAL_TICKET_MEDIO_P2'];
											$tot_QTD_TRANSACAO_P2 += $qrCupom['QTD_TRANSACAO_P2'];
											$tot_QTD_ITENS_P2 += $qrCupom['QTD_ITENS_P2'];
											$tot_QTD_CLIENTE_P2 += $qrCupom['QTD_CLIENTE_P2'];
											$tot_PERC_FATURAMENTO += $qrCupom['PERC_FATURAMENTO'];
											$tot_PERC_TICKET_MEDIO += $qrCupom['PERC_TICKET_MEDIO'];
											$tot_PERC_TRANSACAO += $qrCupom['PERC_TRANSACAO'];
											$tot_PERC_ITENS += $qrCupom['PERC_ITENS'];
											$tot_PERC_CLIENTE += $qrCupom['PERC_CLIENTE'];

											$PERC_FATURAMENTO = (( $qrCupom['FATURAMENTO_P2'] - $qrCupom['FATURAMENTO_P1'] ) / $qrCupom['FATURAMENTO_P1'] ) * 100;
											$PERC_TICKET_MEDIO = (( $qrCupom['VAL_TICKET_MEDIO_P2'] - $qrCupom['VAL_TICKET_MEDIO_P1'] ) / $qrCupom['VAL_TICKET_MEDIO_P1'] ) * 100;
											$PERC_TRANSACAO = (($qrCupom['QTD_TRANSACAO_P2'] - $qrCupom['QTD_TRANSACAO_P1']) / $qrCupom['QTD_TRANSACAO_P1']) * 100;
											$PERC_ITENS = (( $qrCupom['QTD_ITENS_P2'] - $qrCupom['QTD_ITENS_P1'] ) / $qrCupom['QTD_ITENS_P1'] ) * 100;
											$PERC_CLIENTE = (( $qrCupom['QTD_CLIENTE_P2'] - $qrCupom['QTD_CLIENTE_P1'] ) / $qrCupom['QTD_CLIENTE_P1'] ) * 100;						

											$count++;	
											echo"
												<tr>
												  <td><small>".$qrCupom['LOJA']."</small></td>
												  <td class='text-right'><small> ".fnValor($qrCupom['FATURAMENTO_P1'],2)."</small></td>
												  <td class='text-right'><small> ".fnValor($qrCupom['VAL_TICKET_MEDIO_P1'],2)."</small></td>
												  <td><small>".$qrCupom['QTD_TRANSACAO_P1']."</small></td>
												  <td><small>".$qrCupom['QTD_ITENS_P1']."</small></td>
												  <td><small>".$qrCupom['QTD_CLIENTE_P1']."</small></td>
												  <td></td>
												  <td class='text-right'><small> ".fnValor($qrCupom['FATURAMENTO_P2'],2)."</small></td>
												  <td class='text-right'><small> ".fnValor($qrCupom['VAL_TICKET_MEDIO_P2'],2)."</small></td>
												  <td><small>".$qrCupom['QTD_TRANSACAO_P2']."</small></td>
												  <td><small>".$qrCupom['QTD_ITENS_P2']."</small></td>
												  <td><small>".$qrCupom['QTD_CLIENTE_P2']."</small></td>
												  <td></td>
												  <td class='text-right'><small>".fnValor($PERC_FATURAMENTO,2)."%</small></td>
												  <td class='text-right'><small>".fnValor($PERC_TICKET_MEDIO,2)."%</small></td>
												  <td class='text-right'><small>".fnValor($PERC_TRANSACAO,2)."%</small></td>
												  <td class='text-right'><small>".fnValor($PERC_ITENS,2)."%</small></td>
												  <td class='text-right'><small>".fnValor($PERC_CLIENTE,2)."%</small></td>
												</tr>
												"; 
											  }

											  	$tot_VAL_TICKET_MEDIO_P1 = $tot_FATURAMENTO_P1/$tot_QTD_TRANSACAO_P1;
											  	$tot_VAL_TICKET_MEDIO_P2 = $tot_FATURAMENTO_P2/$tot_QTD_TRANSACAO_P2;
											  	$tot_PERC_FATURAMENTO = (( $tot_FATURAMENTO_P2 - $tot_FATURAMENTO_P1 ) / $tot_FATURAMENTO_P1 ) * 100;
											  	$tot_PERC_TICKET_MEDIO = (( $tot_VAL_TICKET_MEDIO_P2 - $tot_VAL_TICKET_MEDIO_P1 ) / $tot_VAL_TICKET_MEDIO_P1 ) * 100;
											  	$tot_PERC_TRANSACAO = (($tot_QTD_TRANSACAO_P2 - $tot_QTD_TRANSACAO_P1 ) / $tot_QTD_TRANSACAO_P1 ) * 100;
											  	$tot_PERC_ITENS = (( $tot_QTD_ITENS_P2 - $tot_QTD_ITENS_P1 ) / $tot_QTD_ITENS_P1 ) * 100;
											  	$tot_PERC_CLIENTE = (( $tot_QTD_CLIENTE_P2 - $tot_QTD_CLIENTE_P1 ) / $tot_QTD_CLIENTE_P1 ) * 100;

									?>


										</tbody>

										<script type="text/javascript">
											
											$("#tot_FATURAMENTO_P1").text('<?=fnValor($tot_FATURAMENTO_P1,2)?>');
											$("#tot_VAL_TICKET_MEDIO_P1").text('<?=fnValor($tot_VAL_TICKET_MEDIO_P1,2)?>');
											$("#tot_QTD_TRANSACAO_P1").text('<?=fnValor($tot_QTD_TRANSACAO_P1,0)?>');
											$("#tot_QTD_ITENS_P1").text('<?=fnValor($tot_QTD_ITENS_P1,0)?>');
											$("#tot_QTD_CLIENTE_P1").text('<?=fnValor($tot_QTD_CLIENTE_P1,0)?>');
											$("#tot_FATURAMENTO_P2").text('<?=fnValor($tot_FATURAMENTO_P2,2)?>');
											$("#tot_VAL_TICKET_MEDIO_P2").text('<?=fnValor($tot_VAL_TICKET_MEDIO_P2,2)?>');
											$("#tot_QTD_TRANSACAO_P2").text('<?=fnValor($tot_QTD_TRANSACAO_P2,0)?>');
											$("#tot_QTD_ITENS_P2").text('<?=fnValor($tot_QTD_ITENS_P2,0)?>');
											$("#tot_QTD_CLIENTE_P2").text('<?=fnValor($tot_QTD_CLIENTE_P2,0)?>');
											$("#tot_PERC_FATURAMENTO").text('<?=fnValor($tot_PERC_FATURAMENTO,2)?>');
											$("#tot_PERC_TICKET_MEDIO").text('<?=fnValor($tot_PERC_TICKET_MEDIO,2)?>');
											$("#tot_PERC_TRANSACAO").text('<?=fnValor($tot_PERC_TRANSACAO,2)?>');
											$("#tot_PERC_ITENS").text('<?=fnValor($tot_PERC_ITENS,2)?>');
											$("#tot_PERC_CLIENTE").text('<?=fnValor($tot_PERC_CLIENTE,2)?>');


										</script>

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
											url: "relatorios/ajxCompVendaPeriodo.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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

		// function reloadPage(idPage) {
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "relatorios/ajxRelCupons.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
		// 		data: $('#formulario').serialize(),
		// 		beforeSend:function(){
		// 			$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
		// 		},
		// 		success:function(data){
		// 			$("#relatorioConteudo").html(data);										
		// 		},
		// 		error:function(){
		// 			$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
		// 		}
		// 	});		
		// }	

		// function abreDetail(idBloco){
		// 	var idItem = $('.abreDetail_' + idBloco)
		// 	if (!idItem.is(':visible')){
		// 		idItem.show();
		// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		// 	}else{
		// 		idItem.hide();
		// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		// 	}
		// }
		
	</script>	
   