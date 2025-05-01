<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_usuario = 	fnLimpaCampoZero(fnDecode($_REQUEST['COD_USUARIO']));
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$cod_vendedor = fnLimpaCampoZero($_REQUEST['COD_USUARIO_2']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$tip_ordenac = fnLimpaCampo($_REQUEST['TIP_ORDENAC']);

			// fnEscreve($cod_vendedor);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		if ($opcao != '') {
		}
	}
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$cod_desafio = fnDecode($_GET['idD']);

	// fnEscreve($cod_desafio);

	$sql = "SELECT NOM_FANTASI,
	(select NOM_DESAFIO from DESAFIO_V2 where cod_desafio = $cod_desafio) as NOM_DESAFIO,
	(select VAL_METADES from DESAFIO_V2 where cod_desafio = $cod_desafio) as VAL_METADES
	FROM ".$connAdm->DB.".empresas where COD_EMPRESA = '".$cod_empresa."' 		
	";			
		//fnEscreve($sql);

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$nom_desafio = $qrBuscaEmpresa['NOM_DESAFIO'];
	$val_metades = $qrBuscaEmpresa['VAL_METADES'];

} 


//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}


//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();
//fnEscreve($cod_cliente);

?>

<style type="text/css">
	.no-side-padding{
		padding-left: 0;
		padding-right: 0;
	}

	.shadow2{
		margin: 0;
	}
	.no-weight *{
		font-weight: 500!important;
	}
</style>

<script src="js/pie-chart.js"></script>

<div class="push30"></div>

<div class="row">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					$formBack = "1015";
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

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo de Lojas</label>
											<?php include "grupoLojasComboMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Região</label>
											<?php include "grupoRegiaoMulti.php"; ?>
										</div>
									</div>

									<div class="push10"></div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
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
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Ordenação</label>
											<div id="divId_usu">
												<select data-placeholder="Selecione um tipo" name="TIP_ORDENAC" id="TIP_ORDENAC" class="chosen-select-deselect">
													<option value="0"></option>					
													<option value="ALFA">Ordem Alfabética</option>					
													<option value="BEST">performance</option>			
												</select>	
											</div>	
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>


								</div>

							</fieldset>

							<!-- <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
							<input type="hidden" name="AUTH" id="AUTH" value="<?php echo fnEncode($auth); ?>" />
							<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>"> -->
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						</form>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row">

							<div class="push20"></div>

							<?php 

								$sql2 = "SELECT count(1) as hitsDesafio from DESAFIO_CONTROLE_V2 A 
								INNER JOIN CLIENTES B 
								ON A.COD_CLIENTE = B.COD_CLIENTE 
								AND A.COD_EMPRESA = B.COD_EMPRESA
								where A.COD_DESAFIO = $cod_desafio";

						//fnEscreve($sql2);	
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2) or die(mysqli_error());
								$qrTotalDesafio = mysqli_fetch_assoc($arrayQuery);
								$total_desafio = $qrTotalDesafio['hitsDesafio'];

					//fnEscreve($total_desafio);

						//totais
								$sql2 = "SELECT count(COD_CONTROLE) as hitsFeitos from DESAFIO_CONTROLE_V2 A
								INNER JOIN CLIENTES B 
								ON A.COD_CLIENTE = B.COD_CLIENTE 
								AND A.COD_EMPRESA = B.COD_EMPRESA
								WHERE COD_DESAFIO = $cod_desafio
								AND LOG_CONCLUIDO = 'S'";

						//fnEscreve($sql2);	
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2) or die(mysqli_error());
								$qrTotalFeitos = mysqli_fetch_assoc($arrayQuery);
								$totalFeitos = $qrTotalFeitos['hitsFeitos'];

								$sql = "SELECT  
											Z.VAL_METADES,
											COUNT(A.COD_CLIENTE),

											IFNULL((SELECT COUNT(DISTINCT C.COD_CLIENTE) FROM VENDAS C,DESAFIO_CONTROLE_V2 D 
												WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
												D.COD_DESAFIO=A.COD_DESAFIO AND 
												C.DAT_CADASTR_WS  >= '$dat_ini 00:00:00' AND 
												C.DAT_CADASTR_WS <= '$dat_fim 23:59:59' 
												AND C.COD_STATUSCRED != 6
												),0) QTD_CLIENTE,

											IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS C,DESAFIO_CONTROLE_V2 D 
												WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
												D.COD_DESAFIO=A.COD_DESAFIO AND  
												C.DAT_CADASTR_WS >= '$dat_ini 00:00:00' AND 
												C.DAT_CADASTR_WS <= '$dat_fim 23:59:59' 
												AND C.COD_STATUSCRED != 6
												),0) VAL_TOTVENDA,

											IFNULL((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS D,DESAFIO_CONTROLE_V2 E 
												WHERE D.COD_CLIENTE=E.COD_CLIENTE AND
												E.COD_DESAFIO=A.COD_DESAFIO AND 
												D.TIP_CREDITO='D' 
												AND D.DAT_REPROCE >= '$dat_ini 00:00:00' 
												AND D.DAT_REPROCE <= '$dat_fim 23:59:59' 
												AND D.COD_STATUSCRED != 6
												),0) VAL_RESGATE,

											IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS E ,CREDITOSDEBITOS F, DESAFIO_CONTROLE_V2 G  
												WHERE 
												E.COD_VENDA=F.COD_VENDA AND 
												F.COD_CLIENTE=G.COD_CLIENTE AND
												G.COD_DESAFIO=A.COD_DESAFIO AND 
												F.TIP_CREDITO='D' AND
												F.DAT_REPROCE >= '$dat_ini 00:00:00' 
												AND F.DAT_REPROCE <= '$dat_fim 23:59:59' 
												AND E.COD_STATUSCRED != 6
												AND F.COD_STATUSCRED != 6
												),0) VAL_VENDAS_VINCULADAS 

										FROM DESAFIO_CONTROLE_V2 A
										INNER JOIN CLIENTES B 
										ON A.COD_CLIENTE = B.COD_CLIENTE 
										AND A.COD_EMPRESA = B.COD_EMPRESA
										INNER JOIN DESAFIO_V2 Z ON A.COD_DESAFIO = Z.COD_DESAFIO
										WHERE A.COD_DESAFIO = $cod_desafio 
										AND A.COD_EMPRESA = $cod_empresa";

															//echo($sql);
								// fnEscreve($sql);

								$qrMeta = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								$objetivoDesafio = ($val_metades/100)*$total_desafio;																								
								$clientesFaltam  = $objetivoDesafio - $totalFeitos;
								$totalProjetado = ($qrMeta['VAL_TOTVENDA'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'];
								$resgateProjetado = ($qrMeta['VAL_RESGATE'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'];
								$vvrProjetado = ($qrMeta['VAL_VENDAS_VINCULADAS'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'];
								$clientesComCompras = $qrMeta['QTD_CLIENTE'];
															//fnEscreve($qrMeta['VAL_TOTVENDA']);
															//fnEscreve($objetivoDesafio);
															//fnEscreve($qrMeta['QTD_CLIENTE']);

								$sql = "SELECT DF.* FROM DESAFIO_V2 DF 
								LEFT JOIN DESAFIO_CONTROLE_V2 A ON A.COD_DESAFIO = DF.COD_DESAFIO
								INNER JOIN CLIENTES B 
								ON A.COD_CLIENTE = B.COD_CLIENTE 
								AND A.COD_EMPRESA = B.COD_EMPRESA
								WHERE DF.COD_DESAFIO = $cod_desafio AND DF.COD_EMPRESA = $cod_empresa";

								$qrMeta2 = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								// $dat_ini = $qrMeta2['DAT_INI'];
								// $dat_fim = $qrMeta2['DAT_FIM'];

							?>

							<div class="text-center col-md-4 col-lg-4 " style="min-height: 512px">

								<div class="col-md-12 shadow2" style="min-height: 512px">

									<h4><b>Objetivo</b></h4>
									<div class="push20"></div>

									<div style="max-height: 200px; max-width:100%;">
										<canvas id="chart-area1" style="height: 100%"></canvas>
									</div>

									
									<div class="push30"></div><div class="push50"></div>

									<div class="col-md-12 top-content">
										<div class="row">
											<div class="col-md-7 no-side-padding">
												<h5 style="margin-top: 0;">Clientes na Lista: </h5>
											</div>
											<div class="col-md-5">
												<b class="f18 ml-auto"><?= fnValor($total_desafio, 0); ?></b>&nbsp;&nbsp;<small class="text-muted f14">100%</small>
											</div>
										</div>

										<div class="row">
											<div class="col-md-7 no-side-padding">
												<h5 style="margin-top: 0;">Meta: </h5>
											</div>
											<div class="col-md-5">
												<b class="f18 ml-auto"><?=fnValor($objetivoDesafio,0)?></b>&nbsp;&nbsp;<small class="text-muted f14"><?=fnValor($qrMeta2['VAL_METADES'],2)?>%</small>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Faltam: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18"><?=fnValor($clientesFaltam,0)?></b>
												</div>
											</div>
										</div>

									</div>

								</div>

								<div class="text-center col-md-4 col-lg-4" style="min-height: 512px">

									<div class="col-md-12 shadow2" style="min-height: 512px">

										<h4><b>Potencial da Meta</b></h4>
										<div class="push20"></div>

										<div style="max-height: 200px; max-width:100%;">
											<canvas id="chart-area2" style="height: 100%"></canvas>
										</div>

										<div class="push50"></div>
										<div class="push30"></div>

										<div class="col-md-12 top-content">
											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Clientes <small>únicos</small> com compras: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18 ml-auto"><?php echo fnValor($objetivoDesafio,0); ?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Valor total: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18 ml-auto">R$ <?php echo fnValor($totalProjetado,2); ?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Resgates: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18">R$ <?php echo fnValor($resgateProjetado,2); ?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">VVR: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18">R$ <?php echo fnValor($vvrProjetado,2); ?></b>
												</div>
											</div>
										</div>

									</div>

								</div>

								<div class="text-center col-md-4 col-lg-4" style="min-height: 512px">

									<div class="col-md-12 shadow2" style="min-height: 512px">

										<h4><b>Alcançado</b></h4>
										<div class="push20"></div>

										<div style="max-height: 200px; max-width:100%;">
											<canvas id="chart-area3" style="height: 100%"></canvas>
										</div>

										<div class="push50"></div>
										<div class="push30"></div>

										<div class="col-md-12 top-content">
											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Clientes <small>únicos</small> com compras: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18 ml-auto"><?=$qrMeta['QTD_CLIENTE']?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Valor total: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18 ml-auto">R$ <?=fnValor($qrMeta['VAL_TOTVENDA'],2)?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">Resgates: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18">R$ <?=fnValor($qrMeta['VAL_RESGATE'],2)?></b>
												</div>
											</div>

											<div class="row">
												<div class="col-md-7 no-side-padding">
													<h5 style="margin-top: 0;">VVR: </h5>
												</div>
												<div class="col-md-5">
													<b class="f18">R$ <?=fnValor($qrMeta['VAL_VENDAS_VINCULADAS'],2)?></b>
												</div>
											</div>
										</div>


									</div>

								</div>

							</div>

							<div class="push50"></div>
						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row">

							<div class="col-lg-12">

								<div class="no-more-tables">

									<form name="formLista">

										<table class="table table-bordered table-striped table-hover tableSorter">
											<thead>
												<tr>
													<th class="{ sorter: false }" width="40"></th>
													<th>Unidade/Responsável</th>
													<th>Vendedor Comunicação</th>
													<th class="text-center">Clientes Alcançados</th>
													<th class="text-right">Vendas</th>
													<th class="text-right">Último Vendedor</th>
												</tr>
											</thead>
											<tbody>

												<?php

													$sql = "SELECT DISTINCT UV.NOM_FANTASI, UV.COD_UNIVEND 
																FROM UNIDADEVENDA UV
																INNER JOIN DESAFIO_CONTROLE_V2 DC2 ON DC2.COD_UNIVEND = UV.COD_UNIVEND AND DC2.LOG_CONCLUIDO = 'S'
																WHERE UV.COD_EMPRESA = $cod_empresa
																AND UV.COD_UNIVEND IN($lojasSelecionadas)
																AND UV.LOG_ESTATUS = 'S'
																ORDER BY NOM_FANTASI";

													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													// fnEscreve($sql);

													$count=0;
													while ($qrListaUni = mysqli_fetch_assoc($arrayQuery)){
														$count++;
													
														echo"
						                                  <tr id='UNIVEND_".$qrListaUni['COD_UNIVEND']."'>                              
						                                    <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(".$qrListaUni['COD_UNIVEND'].")'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
						                                    <td>".$qrListaUni['NOM_FANTASI']."</td>
						                                    <td></td>
						                                    <td></td>
						                                    <td></td>
						                                    <td></td>
						                                  </tr>                      
						                                    ";
						                                  
						                              echo"
						                                  <thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_".$qrListaUni['COD_UNIVEND']."'>
						                                   
						                                  </thead>                             
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
				<!-- fim Portlet -->
			</div>
		
	</div>

	<div class="push20"></div>


	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script src="js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
	<script src="js/pie-chart.js"></script>
	<script src="js/plugins/Chart_Js/utils.js"></script>

	<script>

		<?php $sobra = $total_desafio - $objetivoDesafio ?>

		var config = {
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			type: 'doughnut',
			data: {
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#3BB0B0',
								'#2692DB'
								],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
							}
						},
					<?php } ?>
					data: [
						"<?= $sobra ?>",
						"<?= $objetivoDesafio ?>",
						],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
						],
					label: 'Dataset 1',
				}],
				labels: [
					"Total da lista - <?= fnValor($total_desafio, 0) ?>",
					"Meta - <?= fnValor($objetivoDesafio, 0) ?>",
					]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				animation: {
					animateScale: true,
					animateRotate: true,
					onComplete: function() {
						$("input[name=chartarea]").val(myDoughnut.toBase64Image());
						// botaoPDF();
					}
				}
			}
		};

		var ctx = document.getElementById("chart-area1").getContext("2d");
		var myDoughnut = new Chart(ctx, config);

		var config2 = {
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			type: 'doughnut',
			data: {
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#3BB0B0',
								'#2692DB'
								],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
							}
						},
					<?php } ?>
					data: [
						<?= $sobra ?>,
						<?= $objetivoDesafio ?>
						],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
						],
					label: 'Dataset 1',
				}],
				labels: [
					"Total Lista - <?= fnValor($total_desafio, 0) ?> ",
					"Potencial  - <?= fnValor($objetivoDesafio, 0) ?>",
					]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				animation: {
					animateScale: true,
					animateRotate: true,
					onComplete: function() {
						$("input[name=chartarea]").val(myDoughnut.toBase64Image());
						// botaoPDF();
					}
				}
			}
		};

		var ctx = document.getElementById("chart-area2").getContext("2d");
		var myDoughnut = new Chart(ctx, config2);

		var config3 = {
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			type: 'doughnut',
			data: {
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
								'#3BB0B0',
								'#2692DB'
								],
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
							}
						},
					<?php } ?>
					data: [
						"<?= $total_desafio - $clientesComCompras ?>",
						"<?= $clientesComCompras ?>",
						],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
						],
					label: 'Dataset 1',
				}],
				labels: [
					"Não Alcançados - <?= fnValor($total_desafio - $clientesComCompras, 0) ?>",
					"Alcançados - <?php echo fnValor($clientesComCompras,0); ?>",
					]
			},
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						},
					}
				},
				responsive: true,
				legend: {
					position: 'bottom',
				},
				animation: {
					animateScale: true,
					animateRotate: true,
					onComplete: function() {
						$("input[name=chartarea]").val(myDoughnut.toBase64Image());
						// botaoPDF();
					}
				}
			}
		};

		var ctx = document.getElementById("chart-area3").getContext("2d");
		var myDoughnut = new Chart(ctx, config3);


	//datas
		$(function() {

			$('.datePicker').datetimepicker({
				format: 'DD/MM/YYYY',
				maxDate: 'now',
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

			$("#DAT_INI_GRP").on("dp.change", function(e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});

			$("#DAT_FIM_GRP").on("dp.change", function(e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
		});

		function abreDetail(codUnivend){
			refreshDesafio(<?php echo $cod_empresa; ?>, codUnivend);
		}

		function refreshDesafio(idEmp, codUnivend) {
			var idItem = $('#abreDetail_'+codUnivend);

			if (!idItem.is(':visible')){
				$.ajax({
					type: "POST",
					url: "relatorios/ajxDashDesafio.do",
					data: { COD_EMPRESA:idEmp, COD_UNIVEND:codUnivend, COD_DESAFIO: <?=$cod_desafio?>, TIP_ORDENAC: "<?=$tip_ordenac?>", DAT_INI: "<?=$dat_ini?>", DAT_FIM: "<?=$dat_fim?>" },
					beforeSend:function(){
						$("#abreDetail_"+codUnivend).html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						$("#abreDetail_"+codUnivend).html(data); 
					},
					error:function(data){
						$("#abreDetail_"+codUnivend).html(data);
		          // $("#mostraDetail_"+codUnivend).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

				idItem.show();

				$('#UNIVEND_'+codUnivend).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#UNIVEND_'+codUnivend).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}

	</script>