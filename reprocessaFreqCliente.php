<?php
// fnDebug('true');

$hashLocal = mt_rand();	
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
$dias30 = date('Y-m-d', strtotime($dias30. '- 30 days'));
$lastMonth = date('Y-m-d', strtotime('- 30 days'));


if(isset($_GET['msg'])){
	
		$msgRetorno = "$_GET[msg] processado com <strong>sucesso!</strong>";
		$msgTipo = 'alert-success';
	
}
	
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

		$dat_ini = fnmesanosql("01/".$_REQUEST['DAT_INI']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];	
					
		if ($opcao != ''){
							
			
			//mensagem de retorno
			switch ($opcao)
			{
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
					break;
				break;
			}			
			$msgTipo = 'alert-success';
			
		}  

	}
}
      
	
//busca dados da url	
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
	//fnEscreve('entrou else');
}


if (strlen($dat_ini) == "" || $dat_ini == "1969-12-31" ){
	$dat_ini = $dias30; 
}else{
	$dat_ini = fnDatasql($dat_ini);
}

// fnEscreve($dat_ini);



//busca revendas do usuário
include "unidadesAutorizadas.php";

$sqlPeriodos = "SELECT DISTINCT MESANO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa order by MESANO desc ";
$arrayQueryPeriodos = mysqli_query(connTemp($cod_empresa,""),trim($sqlPeriodos));
//fnEscreve($sqlPeriodos);

$sqlPeriodo = "SELECT COD_FILTRO, DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa order by DT_FILTRO desc ";
$arrayQueryPeriodo = mysqli_query(connTemp($cod_empresa,""),trim($sqlPeriodo));

$sql = "SELECT MAX(MESANO) AS DT_FILTRO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa";
// $sql = "SELECT MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa";
$qrDt = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sql)));
$dt_filtro = fnmesanosql($qrDt['DT_FILTRO']);
$mesAno = $qrDt['DT_FILTRO'];

// Selecionando o último período configurado da tabela de filtros
$sql = "SELECT DISTINCT QTD_MESCLASS FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa AND DT_FILTRO = (SELECT MAX(DT_FILTRO) FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa)";

$qrMes = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

$qtd_mesclass = $qrMes['QTD_MESCLASS'];

$mesQuestaoFiltro = date("m", strtotime($dt_filtro));
$anoQuestaoFiltro = date("Y", strtotime($dt_filtro));

switch ($qtd_mesclass) {
	case 12: 
		$classifica = "Anual";
		$dt_filtro_ini = date("Y", strtotime($dt_filtro))."-01-01";
		$dt_filtro_fim = date("Y", strtotime($dt_filtro))."-12-31";
		break;    
	case 6: 
		$classifica = "Semestral";
		
		if($mesQuestaoFiltro <= 6){
			$dt_filtro_ini = $anoQuestaoFiltro."-01-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-06-30";
		}else{
			$dt_filtro_ini = $anoQuestaoFiltro."-07-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-12-31";
		}
		break;    
	case 4:
		$classifica = "Quadrimestral";
		if($mesQuestaoFiltro <= 4){
			$dt_filtro_ini = $anoQuestaoFiltro."-01-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-04-30";
		}else if($mesQuestaoFiltro <= 8){
			$dt_filtro_ini = $anoQuestaoFiltro."-05-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-08-31";
		}else{
			$dt_filtro_ini = $anoQuestaoFiltro."-09-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-12-31";
		}
		break;    
	case 3: 
		$classifica = "Trimestral";
		if($mesQuestaoFiltro <= 3){
			$dt_filtro_ini = $anoQuestaoFiltro."-01-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-03-31";
		}else if($mesQuestaoFiltro <= 6){
			$dt_filtro_ini = $anoQuestaoFiltro."-04-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-06-30";
		}else if($mesQuestaoFiltro <= 9){
			$dt_filtro_ini = $anoQuestaoFiltro."-07-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-09-30";
		}else{
			$dt_filtro_ini = $anoQuestaoFiltro."-10-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-12-31";
		}
		break;    
	case 2: 
		$classifica = "Bimestral";
		if($mesQuestaoFiltro <= 2){
			$dt_filtro_ini = $anoQuestaoFiltro."-01-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-02-".cal_days_in_month(CAL_GREGORIAN, $anoQuestaoFiltro, '02');;
		}else if($mesQuestaoFiltro <= 4){
			$dt_filtro_ini = $anoQuestaoFiltro."-03-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-04-30";
		}else if($mesQuestaoFiltro <= 6){
			$dt_filtro_ini = $anoQuestaoFiltro."-05-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-06-30";
		}else if($mesQuestaoFiltro <= 8){
			$dt_filtro_ini = $anoQuestaoFiltro."-07-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-08-31";
		}else if($mesQuestaoFiltro <= 10){
			$dt_filtro_ini = $anoQuestaoFiltro."-09-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-10-31";
		}else{
			$dt_filtro_ini = $anoQuestaoFiltro."-11-01";
			$dt_filtro_fim = $anoQuestaoFiltro."-12-31";
		}
		break;    
	case 1: 
		$classifica = "Mensal";
		$dt_filtro_ini = $dt_filtro."-01";
		$dt_filtro_fim = date("Y-m-t", strtotime($dt_filtro));
		break;    
	case 0: 
		$classifica = "Online (a cada venda)";
		$dt_filtro_ini = $dt_filtro."-01";
		$dt_filtro_fim = date("Y-m-t", strtotime($dt_filtro));
		break;    
}


$sql = "SELECT COD_FILTRO FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa AND DT_FILTRO BETWEEN '$dt_filtro_ini' AND '$dt_filtro_fim'";

// fnEscreve($sql);

$qrSpan = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

if($qrSpan['COD_FILTRO'] != ""){
	
$cod_filtro = $qrSpan['COD_FILTRO'];	
	
//busca dados do filtro
$sql = "SELECT COD_FILTRO , QTD_DIASHIST , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];

$mes = date("m",strtotime($dt_filtro));;      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
$ano = date("Y",strtotime($dt_filtro)); // Ano atual
$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
$ultima_data = $ultimo_dia."/".$mes."/".$ano;

$dias_periodo = $qtd_diashist + 1;
$dt_filtroMenor = date('Y-m-d', strtotime($dt_filtro. '-'.$dias_periodo.' days'));

}

$sqlDatVenda = "SELECT MIN(DAT_CADASTR) AS DAT_VENDA FROM VENDAS WHERE COD_EMPRESA = $cod_empresa";
$qrDtVenda = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sqlDatVenda)));
$dat_venda = $qrDtVenda['DAT_VENDA'];

// fnEscreve($cod_empresa);

//fnMostraForm();

        
?>

<style type="text/css">
	#blocker
	{
	    display:none; 
		position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    opacity: .8;
	    background-color: #fff;
	    z-index: 1000;
	}
	    
	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>
					<meta http-equiv=“Pragma” content=”no-cache”>
					<meta http-equiv=“Expires” content=”-1″>
					<meta http-equiv=“CACHE-CONTROL” content=”NO-CACHE”>

					<div id="blocker">
				       <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
				    </div>
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>									
													
									<div class="login-form">

										<div class="row text-center">			
													
											<div class="form-group text-center col-lg-12">
											
												<h5>Dados do Ciclo de Recompra</h5>
											
											</div>
											
											<div class="push30"></div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-cart-arrow-down fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=fnDataShort($dat_venda)?> </b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Primeira venda</small>
											</div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-calendar-check fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=date("m/Y",strtotime($mesAno."-01"))?> </b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Última Atualização</small>
											</div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-calendar-alt fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=fnDataShort($dt_filtroMenor)?> </b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Clientes cadastrados anterior a esta data</small>
											</div>
											
											<div class="col-md-2 text-center text-info">
												<i class="fal fa-sync fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=$classifica; ?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Periodicidade configurada para atualização <br/><small>(base ref. 01/jan)</small></small>
											</div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-shopping-cart fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=fnDataShort($dt_filtroMenor)?> a <?=fnDataShort($dt_filtro); ?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Com compras neste período</small>
											</div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-history fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=$qtd_diashist?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Período previsto para retorno do cliente <br/><small>(dias)</small></small>
											</div>	
											
										</div>

										<div class="col-xs-5">

											<h4>Analytics</h4>

											<div class="push10"></div>
									
											<form method="post" id="formularioAnalytics" action="action.php?mod=<?php echo $DestinoPg; ?>">

												<fieldset>
													<legend>Dados Gerais</legend>

													<div class="row">
														<?php /*
														<div class="col-md-9">
															<div class="form-group">
																<label for="inputName" class="control-label required">Unidade de Atendimento</label>
																<?php include "unidadesAutorizadasComboMulti.php"; ?>
															</div>
														</div>
*/?>
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Período</label>
																
																<div class="input-group date datePicker" id="DAT_INI_GRP">
																	<input type='text' class="form-control input-sm" data-mask="00/0000" name="DAT_INI_ANALYTICS" id="DAT_INI_ANALYTICS" value="<?= date('m/Y',strtotime($dat_ini)) ?>" required/>
																	<span class="input-group-addon">
																		<span class="glyphicon glyphicon-calendar"></span>
																	</span>
																</div>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-6">
															<div class="push20"></div>
															<a href="javascript:void(0)" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block" onclick="ajxReprocessa('Analytics');"><i class="fas fa-cogs" aria-hidden="true"></i>&nbsp; Processar Carga</a>
														</div>

													</div>
<?php /*
													<div class="row">

														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">Período Analytics</label>
																	<select data-placeholder="Selecione o período" name="DT_FILTRO" id="DT_FILTRO" class="chosen-select-deselect">
																		<option value="0">Novo Período</option>					
																		<?php
																		
																			while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodos)){														
																				echo"
																					  <option value='".fnmesanosql($qrListaFiltro['MESANO']."-01")."'>".date("m/Y",strtotime($qrListaFiltro['MESANO']."-01"))."</option> 
																					"; 
																			}											
																		?>	
																	</select>
	                                                                <!-- <script>$("#formulario #DT_FILTRO").val("<?php echo $dt_filtro; ?>").trigger("chosen:updated"); </script> -->                                                       
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-3">
															<div class="push20"></div>
															<a href="javascript:void(0)" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block" onclick="ajxReprocessa('Analytics');"><i class="fas fa-cogs" aria-hidden="true"></i>&nbsp; Processar Carga</a>
														</div>

													</div>
*/?>
												</fieldset>

												<input type="hidden" name="LOJAS_Analytics" id="LOJAS_Analytics" value="<?php echo $lojasSelecionadas; ?>" />
																								
											</form>

											<div class="push20"></div>

											<div class="row">
																								
												<div class="col-xs-12">

													<div class="no-more-tables">
												
														<form name="formLista">
														
															<table class="table table-bordered table-striped table-hover tableSorter">

																<thead>
																	<tr>
																		<th>Períodos Analytics</th>
																	</tr>
																</thead>

																<tbody id="relatorioAnalytics">
																  
																<?php 

																	$arrayQueryPeriodos2 = mysqli_query(connTemp($cod_empresa,""),trim($sqlPeriodos));
																	
																	$count=0;
																	while ($qrListaFiltro2 = mysqli_fetch_assoc($arrayQueryPeriodos2)){

																		$count++;

																		echo"
																			<tr>
																			  <td>".fnDataShort($qrListaFiltro2['MESANO'].'-01')."</td>
																			</tr>
																		";

																	}											

																?>
																	
																</tbody>

															</table>
														
														</form>

													</div>
													
												</div>

											</div>

										</div>

										<div class="col-xs-5 col-xs-offset-2">

											<h4>Funil</h4>

											<div class="push10"></div>
									
											<form method="post" id="formularioFunil" action="action.php?mod=<?php echo $DestinoPg; ?>">

												<fieldset>
													<legend>Dados Gerais</legend>

													<div class="row">
<?php /*														
														<div class="col-md-9">
															<div class="form-group">
																<label for="inputName" class="control-label required">Unidade de Atendimento</label>
																<?php include "unidadesAutorizadasComboMulti.php"; ?>
															</div>
														</div>
*/ ?>
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Período</label>
																
																<div class="input-group date datePicker" id="DAT_INI_GRP">
																	<input type='text' class="form-control input-sm" data-mask="00/0000" name="DAT_INI_FUNIL" id="DAT_INI_FUNIL" value="<?= date('m/Y',strtotime($dat_ini)) ?>" required/>
																	<span class="input-group-addon">
																		<span class="glyphicon glyphicon-calendar"></span>
																	</span>
																</div>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-6">
															<div class="push20"></div>
															<a href="javascript:void(0)" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block" onclick="ajxReprocessa('Funil');"><i class="fas fa-cogs" aria-hidden="true"></i>&nbsp; Processar Carga</a>
														</div>

													</div>
<?php /*
													<div class="row">

														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label">Período Funil</label>
																	<select data-placeholder="Selecione o período" name="COD_FILTRO" id="COD_FILTRO" class="chosen-select-deselect">
																		<option value="0">Novo Período</option>					
																		<?php
																		
																			while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodo))
																			  {														
																				echo"
																					  <option value='".$qrListaFiltro['COD_FILTRO']."'>".date("m/Y",strtotime($qrListaFiltro['DT_FILTRO']))."</option> 
																					"; 
																				  }											
																		?>	
																	</select>
	                                                                <script>$("#formulario #COD_FILTRO").val("<?php echo $cod_filtro; ?>").trigger("chosen:updated"); </script>                                                       
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-3">
															<div class="push20"></div>
															<a href="javascript:void(0)" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block" onclick="ajxReprocessa('Funil');"><i class="fas fa-cogs" aria-hidden="true"></i>&nbsp; Processar Carga</a>
														</div>

													</div>
*/?>
												</fieldset>

												<input type="hidden" name="LOJAS_Funil" id="LOJAS_Funil" value="<?php echo $lojasSelecionadas; ?>" />
													
											</form>

											<div class="push20"></div>

											<div class="row">
																								
												<div class="col-xs-12">

													<div class="no-more-tables">
												
														<form name="formLista">
														
															<table class="table table-bordered table-striped table-hover tableSorter">

																<thead>
																	<tr>
																		<th>Períodos Funil</th>
																	</tr>
																</thead>

																<tbody id="relatorioFunil">
																  
																<?php 
																
																	$sqlListaF = "SELECT COD_FILTRO, DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa order by DT_FILTRO desc ";
    																$arrayQueryListaF = mysqli_query(connTemp($cod_empresa,""),trim($sqlListaF)) or die(mysqli_error());
																	
																	$count=0;
																	while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryListaF)){

																		$count++;

																		echo"
																			<tr>
																			  <td>".fnDataShort($qrListaFiltro['DT_FILTRO'])."</td>
																			</tr>
																		";

																	}											

																?>
																	
																</tbody>

															</table>
														
														</form>

													</div>
													
												</div>

											</div>

										</div>

									</div>
									
								</div>

							<span style="color:#fff;"><?php echo($count); ?></span>
							
							<div class="push10"></div>
							
							</div>								
						
						</div>
					</div>
					<!-- fim Portlet -->					
						
					<div class="push20"></div>

					<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
					<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
					<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
					<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		$(function(){
			$('.datePicker').datetimepicker({
				viewMode: 'years',
				format: 'MM/YYYY',
				maxDate: '<?=$lastMonth?>'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
		});

		function ajxReprocessa(tipo){
			$.ajax({
				type: "POST",
				url: "ajxProcessaCarga.do?opcao="+tipo+"&id=<?php echo fnEncode($cod_empresa); ?>",
				data: $('#formulario'+tipo).serialize(),
				beforeSend: function () {
					$('#blocker').show();
				},
				success: function (data) {
					console.log(data);
					if(data.link != undefined){
						//$('#iframe',window.parent.document).attr("src", $('#iframe',window.parent.document).attr("src"));
						$('#iframe',window.parent.document).attr("src", data.link);
		            }
					$('#blocker').fadeOut();
				},
				error: function (data) {
					$("#relatorio"+tipo).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					console.log(data);
					$('#blocker').fadeOut();
				}
		    });
		}
		
	</script>	