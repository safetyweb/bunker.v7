<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	$cod_univend = "9999";
	
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

		
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, DAT_CADASTR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}
		
	//Vendas e Fidelização
	$sql = "SELECT SUM(QTD_TOTVENDA) AS TRANSACOES, 
			   SUM(QTD_TOTFIDELIZ) AS TRANSACOES_FIDELIZACAO, 
			   SUM(VAL_TOTVENDA) AS VALOR_TOTAL_VENDA, 
			   SUM(VAL_TOTFIDELIZ) AS VALOR_TOTAL_VENDA_FIDELIZADO 
			FROM VENDAS_DIARIAS 
			WHERE 
			DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' AND 
			DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim' AND 
			COD_EMPRESA = $cod_empresa AND
			  (('S'='$temUnivend' AND COD_UNIVEND='$cod_univend')OR('N'='$temUnivend' AND COD_UNIVEND IS NOT NULL ))";	
	  
	//fnEscreve($sql);
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaIndicadorVendas = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaIndicadorVendas)){
		$transacoes = $qrBuscaIndicadorVendas['TRANSACOES'];
		$transacoes_fidelizacao = $qrBuscaIndicadorVendas['TRANSACOES_FIDELIZACAO'];
		$valor_total_venda = $qrBuscaIndicadorVendas['VALOR_TOTAL_VENDA'];
		$valor_total_venda_fidelizado = $qrBuscaIndicadorVendas['VALOR_TOTAL_VENDA_FIDELIZADO'];
	}

	//Resgates
	$sql = "SELECT COUNT(*) QTD_RESGATE,
					SUM(VAL_RESGATE) AS VAL_RESGATES     
			FROM VENDAS_DIARIAS
			WHERE DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' AND 
				  DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim' AND 
				  COD_EMPRESA = $cod_empresa AND
				  VAL_RESGATE > 0 AND
				  (('S'='$temUnivend' AND COD_UNIVEND='$cod_univend')OR('N'='$temUnivend' AND COD_UNIVEND IS NOT NULL ))";
			
	//fntestesql(connTemp($cod_empresa,''),$sql);
	//fnEscreve($sql);
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaResgate = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaResgate)){
		$qt_resgates_tot = $qrBuscaResgate['QTD_RESGATE'];
		$val_resgates_tot = $qrBuscaResgate['VAL_RESGATES'];
	}
		
	//busca vendas fidelização - loop
	$sql = "SELECT DAT_MOVIMENTO, 
			   SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, 
			   SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ
			FROM VENDAS_DIARIAS 
			WHERE DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' AND 
			      DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim' AND
			      COD_EMPRESA = $cod_empresa AND
			      (('S'='$temUnivend' AND COD_UNIVEND='$cod_univend')OR('N'='$temUnivend' AND COD_UNIVEND IS NOT NULL )) 
			 GROUP BY DAT_MOVIMENTO
			 ORDER BY DAT_MOVIMENTO";	
		
	//fnEscreve($sql);	
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$listaTotalFideliz = 0;
	
	while ($qrBuscaVendasFideliz = mysqli_fetch_assoc($arrayQuery))
	  {	
		$data_venda_fideliz = $qrBuscaVendasFideliz['DAT_MOVIMENTO'];
		$pct_diario_total = $qrBuscaVendasFideliz['QTD_TOTAVULSA'];
		$pct_diario_fideliz = $qrBuscaVendasFideliz['QTD_TOTFIDELIZ'];
		//fnEscreve($qrBuscaIndiceDiario['PCT_DIARIO']);
		$dia_venda_fideliz = date('d', strtotime($data_venda_fideliz));
		//fnEscreve($contaIndiceDiario." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
		//fnEscreve($dia_venda." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
		$listaDiarioDiasFideliz =  $listaDiarioDiasFideliz."'".$dia_venda_fideliz."',";
		$listaDiarioTot =  $listaDiarioTot.$pct_diario_total.",";
		$listaDiarioFideliz =  $listaDiarioFideliz.$pct_diario_fideliz.",";
		
		$tempValor = $pct_diario_total + $pct_diario_fideliz;
		if($tempValor > $listaTotalFideliz){
			$listaTotalFideliz = $tempValor;
		}
		
		//$contaIndiceDiario++;
	   }

	//fnEscreve($pct_diario_fideliz);	
	//fnEscreve($pct_diario_total);	
	//fnEscreve($listaTotalFideliz);	
	//fnEscreve($listaDiarioPct);
	
	//busca resgates - loop
	$sql = "SELECT DAT_MOVIMENTO,SUM(VAL_RESGATE) AS VAL_RESGATE 
			FROM VENDAS_DIARIAS 
			WHERE DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' AND 
				  DATE_FORMAT(DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim' AND 
			  COD_EMPRESA = $cod_empresa  AND
			  (('S'='$temUnivend' AND COD_UNIVEND='$cod_univend')OR('N'='$temUnivend' AND COD_UNIVEND IS NOT NULL )) 
			GROUP BY DAT_MOVIMENTO  
			ORDER BY DAT_MOVIMENTO";	
	
	//fnEscreve($sql);	
	
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	
	while ($qrBuscaResgates = mysqli_fetch_assoc($arrayQuery))
	  {	
		$data_resgates = $qrBuscaResgates['DAT_MOVIMENTO'];
		$val_resgates = $qrBuscaResgates['VAL_RESGATE'];
		$dia_resgates = date('d', strtotime($data_resgates));
		$listaDiasResgates =  $listaDiasResgates."'".$dia_resgates."',";
		$listaValorResgates =  $listaValorResgates.$val_resgates.",";
		//$contaIndiceDiario++;
		}

	
	//fnMostraForm();
	//fnEscreve(substr($listaDiarioDiasFideliz,0,-1));
	//fnEscreve($hoje);
	//fnEscreve($dias30);
	//fnEscreve(strlen($dat_ini));
	//fnEscreve(strlen($dat_fim));
	//fnEscreve($data_fim);
	//fnEscreve($cod_univend);
	

?>

			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
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
																<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<?php
																		if ($cod_univend == "9999"){
																		echo "<option value='9999' selected>Todas Unidades</option>";
																		} else {
																		echo "<option value='9999'>Todas Unidades</option>";
																		}																	
	
																		$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '".$cod_empresa."' order by NOM_UNIVEND ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery))
																		  {
																			if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";}	
																			echo"
																				  <option value='".$qrListaUnidades['COD_UNIVEND']."' ".$selecionado.">".$qrListaUnidades['NOM_FANTASI']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
															<div class="help-block with-errors"></div>
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
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
																				
												</div>
												
										</fieldset>																					
										
										<div class="push50"></div>
										
										<div class="row text-center">											
														
											<div class="form-group text-center col-lg-6">
											<h4>Vendas Vinculdas com Créditos Resgatados</h4>
												
												<div class="push20"></div>
												
												<canvas id="Stacked"></canvas>
	   
											</div>										
												
											<div class="form-group text-center col-lg-6">
											<h4>Aproveitamento da Carteira e Vendas para Fidelizados</h4>
											
												<div class="push20"></div>
												
												<canvas id="lineChart2"></canvas>
	   
											</div>
											
										</div>
										
										<div class="push50"></div>
	
										<div class="row text-center">											
														
											<div class="form-group text-center col-lg-6">
											
												<h4>Estatísticas dos clientes que efetuaram resgates</h4>
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover">							
												  
													<tr>
													  <td><span class="fa fa-cart-arrow-down"></span>&nbsp; Quantidade de vendas com resgate</td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>
													
													<tr>
													  <td><span class="fa fa-users"></span>&nbsp; Quantidade de clientes que resgataram</td>
													  <td class="text-right"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>
													
													<tr>
													  <td><span class="fa fa-cart-plus"></span>&nbsp; Total de venda vinculado a resgates</td>
													  <td class="text-right"><b class="f14 text-info"> R$ <?php echo fnValor($valor_total_venda,2); ?></b></b></td>
													</tr>	
													
													<tr>
													  <td><span class="fa fa-user-plus"></span>&nbsp; Total de resgate efetuado por esses clientes </td>
													  <td class="text-right"><b class="f14 text-info"> R$ <?php echo fnValor($valor_total_venda,2); ?> </b></td>
													</tr>	
													
													<tr>
													  <td><span class="fa fa-ticket"></span>&nbsp; Ticket médio das vendas com resgate </td>
													  <td class="text-right"><b class="f14 text-info"> <?php echo fnValor($transacoes,0); ?> </b></td>
													</tr>													
													
													<tr>
													  <td><span class="fa fa-cart-arrow-down"></span>&nbsp; Percentual de vendas em relação aos resgates </td>
													  <td class="text-right"><b class="f14 text-info"> <?php echo fnValor($transacoes,0); ?> % </b></td>
													</tr>													
													
													<tr>
													  <td><span class="fa fa-usd"></span>&nbsp; Gasto médio acumulado dos clientes que resgataram </td>
													  <td class="text-right"><b class="f14 text-info"> R$ <?php echo fnValor($valor_total_venda,2); ?> </b></td>
													</tr>													
													
													<tr>
													  <td><span class="fa fa-tags"></span>&nbsp; Quantidade de peças por atendimento das vendas com resgate </td>
													  <td class="text-right"><b class="f14 text-info"> <?php echo fnValor($transacoes,0); ?> </b></td>
													</tr>													
													
													<tr>
													  <td><span class="fa fa-bar-chart-o"></span>&nbsp; Preço médio por produto das vendas com resgate </td>
													  <td class="text-right"><b class="f14 text-info"> R$ <?php echo fnValor($valor_total_venda,2); ?> </b></td>
													</tr>
															
												</tbody>
												</table>												
												
											</div>

												
											<div class="form-group text-center col-lg-6">
												<h4>Visão Geral dos Cadastros</h4>
												<div class="push20"></div>
												
											
												<table class="table table-bordered table-hover">

													<thead>
													<tr>
													  <th class="f12 text-center"><b><span class="fa fa-calendar"></span>&nbsp; Período</b></th>
													  <th class="f12 text-center"><b><span class="fa fa-cart-plus"></span>&nbsp; Total de venda</b>s</th>
													  <th class="f12 text-center"><b><span class="fa fa-address-card-o"></span>&nbsp; Vendas clientes <br> já cadastrados</b></th>
													  <th class="f12 text-center"><b><span class="fa fa-user"></span>&nbsp; Vendas clientes únicos</b></th>
													  <th class="f12 text-center"><b><span class="fa fa-id-card-o"></span>&nbsp; Cadastros</b></th>
													</tr>
													</thead>
													
													<tr>
													  <td>06/2017</td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>
													
													<tr>
													  <td>07/2017</td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>
													
													<tr>
													  <td>08/2017</td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>	
													
													<tr>
													  <td>09/2017</td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													  <td class="text-right" width="130"><b class="f14 text-info"><?php echo fnValor($transacoes,0); ?></b></td>
													</tr>
															
												</tbody>
												</table>												
	   
											</div>

											
											
										</div>

										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push30"></div>
				
									
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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>    
    <script src="js/plugins/Chart_Js/utils.js"></script>
    	
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
				

		});	
		
		//graficos
        $(document).ready( function() {
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
		
			var MultiChartData = {
				labels: [<?php echo substr($listaDiarioDiasFideliz,0,-1); ?>],
				datasets: [{
					label: 'Fidelizados',
					borderColor: "rgba(20, 143, 119, 0.80)",
					pointBorderColor: "rgba(3, 88, 106, 0.70)",
					pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
					pointHoverBackgroundColor: "rgba(3, 88, 106, 0.70)",
					pointHoverBorderColor: "rgba(3, 88, 106, 0.70)",
					pointBorderWidth: 1,
					fill: false,
					data: [<?php echo substr($listaDiarioFideliz,0,-1); ?>]
				}, {
					label: 'Avulsos',
					borderColor: "#ff6699",
					pointBorderColor: "#cc0044",
					pointBackgroundColor: "#cc0044",
					pointHoverBackgroundColor: "#cc0044",
					pointHoverBorderColor: "#cc0044",
					pointBorderWidth: 1,
					fill: false,
					data: [<?php echo substr($listaDiarioTot,0,-1); ?>]
				}]

			};			
			
			
			// MultiLine chart
			var ctx = document.getElementById("lineChart2");
			var MultiLineChart = new Chart(ctx, {
				type: 'line',
				data: MultiChartData,
				options: {
					bezierCurve: false,
					legend: {
						display: false
					},			
					elements: {
						line: {
							tension: 0
						}
					},				
										
					events: ['click'],
					maintainAspectRatio: true
				},			
			});	
					
			var barChartData = {
				labels: [<?php echo substr($listaDiarioDiasFideliz,0,-1); ?>],
				datasets: [{
					label: 'Fidelizados',
					backgroundColor: window.chartColors.red,
					data: [<?php echo substr($listaDiarioFideliz,0,-1); ?>]
				}, {
					label: 'Avulsos',
					backgroundColor: window.chartColors.blue,
					data: [<?php echo substr($listaDiarioTot,0,-1); ?>]
				}]

			};
			
			var newScale = <?php echo ($listaTotalFideliz + 10) ?>;
			
			var ctx2 = document.getElementById("Stacked").getContext("2d");
			window.myBar = new Chart(ctx2, {
				type: 'bar',
				data: barChartData,
				options: {
					legend: {
						display: true,
						position: 'bottom'
					},					
					events: ['click'],
					maintainAspectRatio: true,
					animation: {
						duration: 2000,
						onComplete: function(animation) {
							var chartInstance = this.chart;
							ctx = chartInstance.ctx;
							
							ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
							ctx.textAlign = 'center';
							ctx.textBaseline = 'bottom';

							this.data.datasets.forEach(function (dataset, i) {
								var meta = chartInstance.controller.getDatasetMeta(i);
								meta.data.forEach(function (bar, index) {
									var data = dataset.data[index];                            
									ctx.fillText(data, bar._model.x, bar._model.y - 5);
								});
							});
						}
					},
					scales: {						
						yAxes: [{						
							stacked: true,
							ticks: {
								suggestedMax: newScale
							},							
						}],
						xAxes: [{
							stacked: true
						}],						
					},	
					tooltips: {
						enabled: false,
						intersect: false
					}
				}		
			});
			
			
		

			
        });

	</script>	
   