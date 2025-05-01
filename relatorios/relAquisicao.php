<?php

//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$dat_ini2 = "";
$dat_fim2 = "";
$cod_persona = 0;
$hashLocal = mt_rand();
$tip_relat = 1;

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();
$connboard=$Cdashboard->connUser();

//inicialização de variáveis

$lastDay_2 = date("Y-m-1");
$lastDay_2 = date("Y-m-d", strtotime($lastDay_2 . '- 1 days'));
$dias30_2 = date('Y-m-1', strtotime($lastDay_2 . '- 30 days'));
$lastDay_1 = date('Y-m-d', strtotime($dias30_2 . '- 1 days'));
$dias30_1 = date('Y-m-1', strtotime($lastDay_1));

// fnEscreve($lastDay_1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_univend = $_POST['COD_UNIVEND'];
		$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
		$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);

		if (empty($_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = $_REQUEST['LOG_LABELS'];
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}


//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = $dias30_1;
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = $lastDay_1;
}

if (strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31") {
	$dat_ini2 = $dias30_2;
}
if (strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31") {
	$dat_fim2 = $lastDay_2;
}


// fnEscreve($dat_ini);
// fnEscreve($dat_fim);
// fnEscreve($dat_ini2);
// fnEscreve($dat_fim2);

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

$sqlGrafico = "SELECT PERIODO, 
					  qtd_cliente_novo_compra, 
					  val_cliente_novo_compra, 
					  qtd_cliente_antigo_compra, 
					  val_cliente_antigo_compra, 
					  DAT_MOVIMENTO, 
					  NOM_FANTASI
				FROM(
					SELECT 'P1' PERIODO, 
							NOM_FANTASI, 
							SUM(qtd_cliente_novo_compra) qtd_cliente_novo_compra, 
							SUM(val_cliente_novo_compra) val_cliente_novo_compra, 
							SUM(qtd_cliente_antigo_compra)qtd_cliente_antigo_compra, 
							SUM(val_cliente_antigo_compra)val_cliente_antigo_compra, 
							DAT_MOVIMENTO
					FROM vendas_diarias A, unidadevenda B
					WHERE A.COD_UNIVEND = B.COD_UNIVEND 
					AND A.COD_EMPRESA = $cod_empresa
					and dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' AND A.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY DAT_MOVIMENTO 
					
					UNION
					
					SELECT 'P2' PERIODO, 
							NOM_FANTASI, 
							SUM(qtd_cliente_novo_compra) qtd_cliente_novo_compra, 
							SUM(val_cliente_novo_compra) val_cliente_novo_compra, 
							SUM(qtd_cliente_antigo_compra)qtd_cliente_antigo_compra, 
							SUM(val_cliente_antigo_compra)val_cliente_antigo_compra, 
							DAT_MOVIMENTO
					FROM vendas_diarias A, unidadevenda B
					WHERE A.COD_UNIVEND = B.COD_UNIVEND 
					AND A.COD_EMPRESA = $cod_empresa
					AND dat_movimento BETWEEN '$dat_ini2' AND '$dat_fim2' AND A.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY DAT_MOVIMENTO
				)TMPPERIODO
				ORDER BY DAT_MOVIMENTO ASC";

// fnEscreve($sqlGrafico);
		
$arrGrafico = mysqli_query(connTemp($cod_empresa, ''), $sqlGrafico);

$arrDatas = [];
$arrValores = [];
$arrUnidades = [];
$arrValoresNovos = [];
$arrValoresAntigos = [];
$arrClientesNovos = [];
$arrClientesAntigos = [];
$countArr = 0;
$trocaPeriodo = 0;


while ($indiceGrafico = mysqli_fetch_assoc($arrGrafico)) {

	if($trocaPeriodo == 0 && $indiceGrafico['PERIODO'] == "P2"){ 
		$countArr = 0;
		$trocaPeriodo = 1;
	}

	$dat_mov = fnDataShort($indiceGrafico['DAT_MOVIMENTO']);

	$arrDatas[$indiceGrafico['PERIODO']][$countArr] = $dat_mov;

	$arrValoresNovos[$indiceGrafico['PERIODO']][$countArr] = $indiceGrafico['val_cliente_novo_compra'];
	$arrValoresAntigos[$indiceGrafico['PERIODO']][$countArr] = $indiceGrafico['val_cliente_antigo_compra'];
	$arrClientesNovos[$indiceGrafico['PERIODO']][$countArr] = $indiceGrafico['qtd_cliente_novo_compra'];
	$arrClientesAntigos[$indiceGrafico['PERIODO']][$countArr] = $indiceGrafico['qtd_cliente_antigo_compra'];

	$arrValores[$indiceGrafico['PERIODO']][val_cliente_novo_compra] += $indiceGrafico['val_cliente_novo_compra'];
	$arrValores[$indiceGrafico['PERIODO']][val_cliente_antigo_compra] += $indiceGrafico['val_cliente_antigo_compra'];
	$arrValores[$indiceGrafico['PERIODO']][qtd_cliente_novo_compra] += $indiceGrafico['qtd_cliente_novo_compra'];
	$arrValores[$indiceGrafico['PERIODO']][qtd_cliente_antigo_compra] += $indiceGrafico['qtd_cliente_antigo_compra'];

	$countArr++;

}

$sqlGrafico = "SELECT PERIODO, 
					  qtd_cliente_novo_compra, 
					  val_cliente_novo_compra, 
					  qtd_cliente_antigo_compra, 
					  val_cliente_antigo_compra, 
					  DAT_MOVIMENTO, 
					  NOM_FANTASI
				FROM(
					SELECT 'P1' PERIODO, 
							NOM_FANTASI, 
							SUM(qtd_cliente_novo_compra) qtd_cliente_novo_compra, 
							SUM(val_cliente_novo_compra) val_cliente_novo_compra, 
							SUM(qtd_cliente_antigo_compra)qtd_cliente_antigo_compra, 
							SUM(val_cliente_antigo_compra)val_cliente_antigo_compra, 
							DAT_MOVIMENTO
					FROM vendas_diarias A, unidadevenda B
					WHERE A.COD_UNIVEND = B.COD_UNIVEND 
					AND A.COD_EMPRESA = $cod_empresa
					and dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' AND A.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY A.COD_UNIVEND 
					
					UNION
					
					SELECT 'P2' PERIODO, 
							NOM_FANTASI, 
							SUM(qtd_cliente_novo_compra) qtd_cliente_novo_compra, 
							SUM(val_cliente_novo_compra) val_cliente_novo_compra, 
							SUM(qtd_cliente_antigo_compra)qtd_cliente_antigo_compra, 
							SUM(val_cliente_antigo_compra)val_cliente_antigo_compra, 
							DAT_MOVIMENTO
					FROM vendas_diarias A, unidadevenda B
					WHERE A.COD_UNIVEND = B.COD_UNIVEND 
					AND A.COD_EMPRESA = $cod_empresa
					AND dat_movimento BETWEEN '$dat_ini2' AND '$dat_fim2' AND A.COD_UNIVEND IN($lojasSelecionadas)
					GROUP BY A.COD_UNIVEND
				)TMPPERIODO
				ORDER BY NOM_FANTASI ASC";

// fnEscreve($sqlGrafico);
		
$arrGrafico = mysqli_query(connTemp($cod_empresa, ''), $sqlGrafico);


while ($indiceGrafico = mysqli_fetch_assoc($arrGrafico)) {

	$arrUnidades[$indiceGrafico['NOM_FANTASI']][$indiceGrafico['PERIODO']][val_cliente_novo_compra] += $indiceGrafico['val_cliente_novo_compra'];
	$arrUnidades[$indiceGrafico['NOM_FANTASI']][$indiceGrafico['PERIODO']][val_cliente_antigo_compra] += $indiceGrafico['val_cliente_antigo_compra'];
	$arrUnidades[$indiceGrafico['NOM_FANTASI']][$indiceGrafico['PERIODO']][qtd_cliente_novo_compra] += $indiceGrafico['qtd_cliente_novo_compra'];
	$arrUnidades[$indiceGrafico['NOM_FANTASI']][$indiceGrafico['PERIODO']][qtd_cliente_antigo_compra] += $indiceGrafico['qtd_cliente_antigo_compra'];

}

// echo "<pre>";
// print_r($arrUnidades);
// echo "</pre>";

if($log_labels == 'S'){
	$checkLabels = "checked";
}else{
	$checkLabels = "";
}

?>

<style>

.circle {
  width: 120px;
  margin: 6px 6px 20px;
  display: inline-block;
  position: relative;
  text-align: center;
  line-height: 1.2;
}

.circle canvas {
  vertical-align: top;
  width: 120px !important;
}

.circle strong {
  position: absolute;
  top: 23.5%;
	  left: 0;
  width: 100%;
  text-align: center;
  line-height: 40px;
  font-size: 16px;
  font-weight: normal!important;
  color: #17202A;
}

.circle strong i {
  font-style: normal;
  font-size: 0.6em;
  font-weight: normal;
}

.circle span {
  display: block;
  color: #aaa;
  margin-top: 12px;
}

table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
.table-bordered:hover{
    z-index: 2;
}
.p1  {
    background-color: #f8f9f9;
	z-index: 0;
}

tr:hover{
	background-color: #ECF0F1!important;

}

tr:hover td {
    background-color: transparent; /* or #000 */
}
/*.drop-shadow {
    -webkit-box-shadow: 0 0 5px 2px #ECEFF2;
    box-shadow: 0 0 5px 2px #ECEFF2;
    border-radius:5px;
}*/

.graficoRedondo{
	position: relative; width: 100%;
}
.X{

	position: absolute;
	height: -1000px;
	margin-left: 220px;
	margin-top: 150px;
}
.bg-white{
	background-color: #fff!important;
}
.bg-white:hover{
	background-color: #fff!important;
}
canvas{
    margin: 0 auto;
    }
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

							</div>

							<div class="row">

								<div class="col-md-4" style="padding: 0;">

									<div class="col-md-6">

										<h5>1º Período</h5>

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

									<div class="col-md-6">

										<div class="push30"></div>
										<div class="push10"></div>

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

								</div>

								<div class="col-md-4" style="padding: 0;">


									<div class="col-md-6">

										<h5>2º Período</h5>

										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP2">
												<input type='text' class="form-control input-sm data" name="DAT_INI2" id="DAT_INI2" value="<?php echo fnFormatDate($dat_ini2); ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-6">

										<div class="push30"></div>
										<div class="push10"></div>

										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP2">
												<input type='text' class="form-control input-sm data" name="DAT_FIM2" id="DAT_FIM2" value="<?php echo fnFormatDate($dat_fim2); ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="col-md-1">

									<div class="push30"></div>
									<div class="push10"></div>

									<div class="form-group">
										<label for="inputName" class="control-label">Exibir Legendas</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?>>
											<span></span>
										</label>
									</div>
								</div>


								<div class="col-md-2">

									<div class="push50"></div>
									<div class="push10"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>

								</div>

							</div>

						</fieldset>

						<div class="push30"></div>

						<div class="portlet portlet-bordered">

							<div class="portlet-body">

								<div class="row">
									<table class="table table-bordered">

											<thead>
												<tr>
			                                        <th>Legendas</th>
												</tr>
											</thead>  

										   <tr>
										       <td><span class="fal fa-check-circle text-success"></span>&nbsp;&nbsp;Saudável = Cresceu em aquisição e retenção.</td>
										       <td><span class="fal fa-arrow-circle-up text-info"></span>&nbsp;&nbsp;Fortalecimento = Está retendo os clientes, porém precisa focar estratégias de aquisição de clientes.</td>
										   </tr>
										   <tr>
										       <td><span class="fal fa-minus-circle text-warning"></span>&nbsp;&nbsp;Recuperação = Está bem em aquisição, porém precisa de estratégias de retenção.</td>
										       <td><span class="fal fa-exclamation-circle text-danger"></span>&nbsp;&nbsp;UTI = Precisa trabalhar estratégias de aquisição e retenção.</td>
										   </tr>
									</table>
								</div>
							</div>
						</div>

						<?php



						?>

						<div class="push20"></div>

						<div class="portlet portlet-bordered">

							<div class="portlet-body">

								<div class="login-form">

									<div class="push30"></div>

									<div class="row">

										<div class="form-group text-center col-lg-12" >

											<h4>Aquisição x Retenção</h4>

											<div class="push20"></div>

											<h5>1° Período</h5>

											<div style="height: 300px;">
												<canvas id="lineChart1" style="width: 100%;"></canvas>
											</div>

											<div class="push30"></div>
											<div class="push10"></div>

											<h5>2° Período</h5>

											<div style="height: 300px;">
												<canvas id="lineChart2" style="width: 100%;"></canvas>
											</div>

										</div>

									</div>

									<div class="push20"></div>

									<div class="row text-center">
                    

			                    		<table class="table table-bordered">
			                    			
			                    			<thead>
												<tr>
			                                        <th class="text-center bg-white">1° Período</th>
			                                        <th class="text-center bg-white">2° Período</th>
												</tr>
											</thead>  

											<tbody>

												<tr>
													<td class="bg-white" colspan="1">

														<table class="table">
														
															<thead>
																<tr>
							                                        <th class="text-center bg-white">Compras Novos Clientes</th>
							                                        <th class="text-center bg-white">Compras Clientes Antigos</th>
																</tr>
															</thead>  

															<tbody>
																<tr>
																	<td class="text-center bg-white">R$ <?=fnValor($arrValores['P1']['val_cliente_novo_compra'],2)?></td>
																	<td class="text-center bg-white">R$ <?=fnValor($arrValores['P1']['val_cliente_antigo_compra'],2)?></td>
																</tr>
															</tbody>

														</table>

													</td>

													<td class="bg-white" colspan="1">
														
														<table class="table">
														
															<thead>
																<tr>
							                                        <th class="text-center bg-white">Compras Novos Clientes</th>
							                                        <th class="text-center bg-white">Compras Clientes Antigos</th>
																</tr>
															</thead>  

															<tbody>
																<tr>
																	<td class="text-center bg-white">R$ <?=fnValor($arrValores['P2']['val_cliente_novo_compra'],2)?></td>
																	<td class="text-center bg-white">R$ <?=fnValor($arrValores['P2']['val_cliente_antigo_compra'],2)?></td>
																</tr>
															</tbody>

														</table>

													</td>

												</tr>

											</tbody>

			                    		</table>

			                    		<table class="table table-bordered">
			                    			
			                    			<thead>
												<tr>
			                                        <th class="text-center bg-white" width="33.333333%">Diferença % Compras Período</th>
			                                        <th class="text-center bg-white" width="33.333333%">Diferença % Clientes Período</th>
			                                        <th class="text-center bg-white" width="33.333333%">Status</th>
												</tr>
											</thead>  

											<tbody>
												<tr>
													<?php 

														// fórmula aumento percentual
														// aumento = novo valor - valor original
														// % aumento = (aumento / valor original) * 100.
														// se negativo, houve decréscimo.

														$vendasPer1 = $arrValores['P1']['val_cliente_novo_compra'] + $arrValores['P1']['val_cliente_antigo_compra'];
														$vendasPer2 = $arrValores['P2']['val_cliente_novo_compra'] + $arrValores['P2']['val_cliente_antigo_compra'];
														$aumentoPer = $vendasPer2-$vendasPer1;
														$pctPeriodos = ($aumentoPer/$vendasPer1)*100;

														$cliPer1 = $arrValores['P1']['qtd_cliente_novo_compra'] + $arrValores['P1']['qtd_cliente_antigo_compra'];
														$cliPer2 = $arrValores['P2']['qtd_cliente_novo_compra'] + $arrValores['P2']['qtd_cliente_antigo_compra'];
														$aumentoCliPer = $cliPer2-$cliPer1;
														$pctCliPeriodos = ($aumentoCliPer/$cliPer1)*100;

														if($pctPeriodos > 0){
															$classePontos ="fal fa-arrow-up";
															$color = "color:green";
															$data = "Cresceu";
														}else{
															$classePontos ="fal fa-arrow-down";
															$color = "color:red";
															$data = "Diminuiu";
														}

														if($pctCliPeriodos > 0){
															$classePontosCli ="fal fa-arrow-up";
															$colorCli = "color:green";
															$dataCli = "Cresceu";
														}else{
															$classePontosCli ="fal fa-arrow-down";
															$colorCli = "color:red";
															$dataCli = "Diminuiu";
														}

														$statusGeral = "";

														if($pctPeriodos > 0 && $pctCliPeriodos > 0){
															$statusGeral = "Saudável";
															$tooltipGeral = '<small><span class="fal fa-check-circle text-success" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Cresceu em aquisição e retenção"></span></small>';
														}else if($pctCliPeriodos > 0){
															$statusGeral = "Fortalecimento";
															$tooltipGeral = '<small><span class="fal fa-arrow-circle-up text-info" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Está retendo clientes, porém precisa focar em estratégias de aquisição"></span></small>';
														}else if($pctPeriodos > 0){
															$statusGeral = "Recuperação";
															$tooltipGeral = '<small><span class="fal fa-minus-circle text-warning" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Está bem em aquisição, porém precisa de estratégias de retenção"></span></small>';
														}else{
															$statusGeral = "UTI";
															$tooltipGeral = '<small><span class="fal fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Precisa trabalhar em estratégias de aquisição e retenção"></span></small>';
														}

													?>

													<td class="text-center bg-white"><?=fnValor($pctPeriodos,2)?>% <small><span class="<?= $classePontos ?>" style="<?=$color?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?=$data?></br> Ref. a 1° Período"></span></small></td>

													<td class="text-center bg-white"><?=fnValor($pctCliPeriodos,2)?>% <small><span class="<?= $classePontosCli ?>" style="<?=$colorCli?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?=$dataCli?></br> Ref. a 1° Período"></span></small></td>
													
													<td class="text-center bg-white"><?=$tooltipGeral?>&nbsp;&nbsp;<?=$statusGeral?></td>

												</tr>
											</tbody>

										</table>
							            

							        </div>

								</div>

								<div class="push30"></div>

							</div>

						</div>

					</div>
				</div>

			</div><!-- fim Portlet -->

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push30"></div>

						<div class="row">

							<div class="col-md-12">

								<table class="table table-bordered">
			                    			
	                    			<thead>
										<tr>
	                                        <th class="text-center bg-white" width="10%">&nbsp;</th>
	                                        <th class="text-center bg-white" width="35%">1° Período</th>
	                                        <th class="text-center bg-white" width="35%">2° Período</th>
	                                        <th class="text-center bg-white" width="20%">&nbsp;</th>
										</tr>
									</thead>  

									<tbody>

										<tr>
											<td class="bg-white" colspan="4">

												<table class="table table-bordered table-hover tablesorter">
													<thead>
														<tr>
				                                            <th width="10%"><small>Unidade</small></th>
				                                            <!-- periodo 1 -->
				                                            <th class="text-center" width="8.75%"><small>Cli. Novos</small></th>
															<th class="text-right" width="8.75%"><small>Compras Novos</small></th>
															<th class="text-center" width="8.75%"><small>Cli. Antigos</small></th>
															<th class="text-right" width="8.75%"><small>Compras Antigos</small></th>
															<!-- periodo 2 -->
															<th class="text-center" width="8.75%"><small>Cli. Novos</small></th>
															<th class="text-right" width="8.75%"><small>Compras Novos</small></th>
															<th class="text-center" width="8.75%"><small>Cli. Antigos</small></th>
															<th class="text-right" width="8.75%"><small>Compras Antigos</small></th>
															<!-- status -->
															<th class="text-center bg-white" width="6.66666667%"><small>% Compras</small></th>
					                                        <th class="text-center bg-white" width="6.66666667%"><small>% Clientes</small></th>
					                                        <th class="text-center bg-white" width="6.66666667%"><small>Status</small></th>


														</tr>

													</thead>  

													<tbody>

														<?php

															foreach($arrUnidades as $unidade => $valor) {

																$vendasPer1 = $valor['P1']['val_cliente_novo_compra'] + $valor['P1']['val_cliente_antigo_compra'];
																$vendasPer2 = $valor['P2']['val_cliente_novo_compra'] + $valor['P2']['val_cliente_antigo_compra'];
																$aumentoPer = $vendasPer2-$vendasPer1;
																$pctPeriodos = ($aumentoPer/$vendasPer1)*100;

																$cliPer1 = $valor['P1']['qtd_cliente_novo_compra'] + $valor['P1']['qtd_cliente_antigo_compra'];
																$cliPer2 = $valor['P2']['qtd_cliente_novo_compra'] + $valor['P2']['qtd_cliente_antigo_compra'];
																$aumentoCliPer = $cliPer2-$cliPer1;
																$pctCliPeriodos = ($aumentoCliPer/$cliPer1)*100;

																if($pctPeriodos > 0){
																	$classePontos ="fal fa-arrow-up";
																	$color = "color:green";
																	$data = "Cresceu";
																}else{
																	$classePontos ="fal fa-arrow-down";
																	$color = "color:red";
																	$data = "Diminuiu";
																}

																if($pctCliPeriodos > 0){
																	$classePontosCli ="fal fa-arrow-up";
																	$colorCli = "color:green";
																	$dataCli = "Cresceu";
																}else{
																	$classePontosCli ="fal fa-arrow-down";
																	$colorCli = "color:red";
																	$dataCli = "Diminuiu";
																}

																$statusGeral = "";

																if($pctPeriodos > 0 && $pctCliPeriodos > 0){
																	$statusGeral = "Saudável";
																	$tooltipGeral = '<small><span class="fal fa-check-circle text-success" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Cresceu em aquisição e retenção"></span></small>';
																}else if($pctCliPeriodos > 0){
																	$statusGeral = "Fortalecimento";
																	$tooltipGeral = '<small><span class="fal fa-arrow-circle-up text-info" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Está retendo clientes, porém precisa focar em estratégias de aquisição"></span></small>';
																}else if($pctPeriodos > 0){
																	$statusGeral = "Recuperação";
																	$tooltipGeral = '<small><span class="fal fa-minus-circle text-warning" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Está bem em aquisição, porém precisa de estratégias´de aquisição"></span></small>';
																}else{
																	$statusGeral = "UTI";
																	$tooltipGeral = '<small><span class="fal fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Precisa trabalhar em estratégias de aquisição e retenção"></span></small>';
																}
															    																		
																echo"
																	<tr>
																	  <td>".$unidade."</td>

																	  <td class='text-center'><small>".fnValor($valor['P1']['qtd_cliente_novo_compra'],0)."</small></td>
																	  <td class='text-right'><small>R$ ".fnValor($valor['P1']['val_cliente_novo_compra'],2)."</small></td>
																	  <td class='text-center'><small>".fnValor($valor['P1']['qtd_cliente_antigo_compra'],0)."</small></td>
																	  <td class='text-right'><small>R$ ".fnValor($valor['P1']['val_cliente_antigo_compra'],2)."</small></td>

																	  <td class='text-center' style='background: rgba(248, 249, 249, 1);'><small>".fnValor($valor['P2']['qtd_cliente_novo_compra'],0)."</small></td>
																	  <td class='text-right' style='background: rgba(248, 249, 249, 1);'><small>R$ ".fnValor($valor['P2']['val_cliente_novo_compra'],2)."</small></td>
																	  <td class='text-center' style='background: rgba(248, 249, 249, 1);'><small>".fnValor($valor['P2']['qtd_cliente_antigo_compra'],0)."</small></td>
																	  <td class='text-right' style='background: rgba(248, 249, 249, 1);'><small>R$ ".fnValor($valor['P2']['val_cliente_antigo_compra'],2)."</small></td>

																	  <td class='text-center'><small>".fnValor($pctPeriodos,2)."%</small> <small><span class='$classePontos' style='$color' data-toggle='tooltip' data-placement='top' data-html='true' data-original-title='$data</br> Ref. a 1° Período'></span></small></td>
																	  <td class='text-center'><small>".fnValor($pctCliPeriodos,2)."%</small> <small><span class='$classePontosCli' style='$colorCli' data-toggle='tooltip' data-placement='top' data-html='true' data-original-title='$dataCli</br> Ref. a 1° Período'></span></small></td>
																	  <td class='text-center'><small>$tooltipGeral&nbsp&nbsp;$statusGeral</small></td>


																	</tr>
																";
															}

														?>

													</tbody>
													
												</table>

											</td>

										</tr>

									</tbody>

	                    		</table>

							</div>

						</div>

						<tfoot>
								<td class="text-left">
                                    <small>
                                        <div class="btn-group dropdown left">
                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
                                                &nbsp; Exportar &nbsp;
                                                <span class="fas fa-caret-down"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            	<li><a class="btn btn-sm exportarCSV" data-exportar="p1">&nbsp;Primeiro Período</a></li>
                                                <li><a class="btn btn-sm exportarCSV" data-exportar="p2">&nbsp;Segundo Período</a></li>
                                            </ul>
                                        </div>
                                    </small>
                                </td>
						</tfoot>

					</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="fhHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Script dos labels -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script> -->
<!-- --------------------------------------------------------------------------- -->
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<?php
}
?>

<script>

	$(".exportarCSV").click(function() {
		let opcao = $(this).attr("data-exportar");
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
									url: "relatorios/ajxAquisicao.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao="+ opcao +"&nomeRel="+nome,
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

	//datas
	$(document).ready(function() {

    $('.datePicker').datetimepicker({
        format: 'DD/MM/YYYY'
    }).on('changeDate', function(e) {
        $(this).datetimepicker('hide');
    });

	$("#DAT_INI_GRP").on("dp.change", function (e) {
		$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
	});
	
	$("#DAT_FIM_GRP").on("dp.change", function (e) {
		$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
	});

	$("#DAT_INI_GRP2").on("dp.change", function (e) {
		$('#DAT_FIM_GRP2').data("DateTimePicker").minDate(e.date);
	});
	
	$("#DAT_FIM_GRP2").on("dp.change", function (e) {
		$('#DAT_INI_GRP2').data("DateTimePicker").maxDate(e.date);
	});

	var log_labels = "<?= $log_labels ?>";

	//chosen
	$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
	$('#formulario').validator();


	//lineChart 1
	var ctx = document.getElementById("lineChart1");
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?=json_encode($arrDatas["P1"])?>,
			datasets: [{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#36A2EB',
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Clientes Novos",
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderColor: "#36A2EB",
				pointBorderColor: "#36A2EB",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrClientesNovos['P1'])?>,
				yAxisID: 'y',
			}, {
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#4BC0C0',
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Clientes Antigos",
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderColor: "#4BC0C0",
				pointBorderColor: "#4BC0C0",
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrClientesAntigos['P1'])?>,
				yAxisID: 'y',
			},{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#21618C',
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Compras C. Novos",
				backgroundColor: "rgba(39, 174, 96, 0)",
				borderColor: "#21618C",
				pointBorderColor: "#21618C",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrValoresNovos['P1'])?>,
				yAxisID: 'y1',
			},{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#196F3D',
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Compras C. Antigos",
				backgroundColor: "rgba(84, 153, 199, 0)",
				borderColor: "#196F3D",
				pointBorderColor: "#196F3D",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrValoresAntigos['P1'])?>,
				yAxisID: 'y1',
			}]
		},
		<?php if ($log_labels == 'S') { ?>
		plugins: [ChartDataLabels],
		<?php } ?>
		options: {
			legend: {
				display: true,
				position: 'bottom'
			},
			maintainAspectRatio: false,
			animation: {
				duration: 2000,
			},
			interaction: {
		      mode: 'index',
		      intersect: false,
		    },
		    stacked: false,
			scales: {
				y: {
			        type: 'linear',
			        display: true,
			        position: 'left',
			    },
			    y1: {
			        type: 'linear',
			        display: true,
			        position: 'right',

			        // grid line settings
			        grid: {
			          drawOnChartArea: false, // only want the grid lines for one axis to show up
			        },
			    },
				yAxes: [{
					ticks: {
						beginAtZero: true,
						callback: function(value, index, values) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
						}
					},
					afterTickToLabelConversion: function(object) {
						for (var tick in object.ticks) {
							object.ticks[tick];
						}
					}
				}],
			},

			tooltips: {
				callbacks: {
					label: function(t, d) {
						if (parseInt(t.yLabel) >= 1000) {
							return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						} else {
							return t.yLabel;
						}
					}
				}
			},
		}

	});

	// Line chart 2
	var ctx = document.getElementById("lineChart2");
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?=json_encode($arrDatas["P2"])?>,
			datasets: [{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#36A2EB',
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Clientes Novos",
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderColor: "#36A2EB",
				pointBorderColor: "#36A2EB",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrClientesNovos['P2'])?>,
				yAxisID: 'y',
			}, {
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#4BC0C0',
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Clientes Antigos",
				backgroundColor: "rgba(3, 88, 106, 0)",
				borderColor: "#4BC0C0",
				pointBorderColor: "#4BC0C0",
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrClientesAntigos['P2'])?>,
				yAxisID: 'y',
			},{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#21618C',
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Compras C. Novos",
				backgroundColor: "rgba(39, 174, 96, 0)",
				borderColor: "#21618C",
				pointBorderColor: "#21618C",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrValoresNovos['P2'])?>,
				yAxisID: 'y1',
			},{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#196F3D',
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return 'R$ ' + value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				label: "Compras C. Antigos",
				backgroundColor: "rgba(84, 153, 199, 0)",
				borderColor: "#196F3D",
				pointBorderColor: "#196F3D",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?=json_encode($arrValoresAntigos['P2'])?>,
				yAxisID: 'y1',
			}]
		},
		<?php if ($log_labels == 'S') { ?>
			plugins: [ChartDataLabels],
		<?php } ?>
		options: {
			legend: {
				display: true,
				position: 'bottom'
			},
			maintainAspectRatio: false,
			animation: {
				duration: 2000,
			},
			interaction: {
		      mode: 'index',
		      intersect: false,
		    },
		    stacked: false,
			scales: {
				y: {
			        type: 'linear',
			        display: true,
			        position: 'left',
			    },
			    y1: {
			        type: 'linear',
			        display: true,
			        position: 'right',

			        // grid line settings
			        grid: {
			          drawOnChartArea: false, // only want the grid lines for one axis to show up
			        },
			    },
				yAxes: [{
					ticks: {
						beginAtZero: true,
						callback: function(value, index, values) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value;
							}
						}
					},
					afterTickToLabelConversion: function(object) {
						for (var tick in object.ticks) {
							object.ticks[tick];
						}
					}
				}],
			},

			tooltips: {
				callbacks: {
					label: function(t, d) {
						if (parseInt(t.yLabel) >= 1000) {
							return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						} else {
							return t.yLabel;
						}
					}
				}
			},
		}

	});

});
		
</script>