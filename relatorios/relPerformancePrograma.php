<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$dat_ini = '';
$dat_fim = '';
$dias30 = '';
$hoje = '';
$cod_univend = '';
$log_labels = '';
$MES = '';
$totQdtCliFide = 0;

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

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

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

$listaPctEngaja = [];
$listaPctVVR = [];
$listaTotVVR = [];
$listaTotResg = [];


$sql = " CALL SP_RELAT_INDICE_TM_GM ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$qrBuscaTmGm = mysqli_fetch_assoc($arrayQuery);

$listaTotTmResgate = $qrBuscaTmGm['TM_RESGATE'];
$listaTotTmSem = $qrBuscaTmGm['TM_SEM_RESGATE'];
$listaTotTmAvulso = $qrBuscaTmGm['TM_AVULSO'];
$listaTotGmResgate = $qrBuscaTmGm['GM_RESGATE'];
$listaTotGmSem = $qrBuscaTmGm['GM_SEM_RESGATE'];
$listaTotGmAvulso = $qrBuscaTmGm['V_VALORGM_AVULSO'];



$sql = " CALL SP_RELAT_ROSCA_TRANSACOES ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$qrBuscaTransac = mysqli_fetch_assoc($arrayQuery);

$listaTotTransacResgate = $qrBuscaTransac['TRANSACOES_RESGATE'];
$listaTotTransacSem = $qrBuscaTransac['TRANSACOES_SEM_RESGATE'];
$listaTotTransacAvulso = $qrBuscaTransac['TRANSACOES_AVULSO'];
$qtdTotTransacResgate = $qrBuscaTransac['QTD_TRANSACOES_RESGATE'];
$qtdTotTransacSemRes = $qrBuscaTransac['QTD_TRANSACOES_SEM_RESGATE'];
$qtdTotTransacAvulso = $qrBuscaTransac['QTD_TRANSACOES_AVULSAS'];



$sql = " CALL SP_RELAT_ABSOLUTO_FATURAMENTO ( '" . fnDataSql($dat_ini) . "' , '" . fnDataSql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$qrBuscaFaturamento = mysqli_fetch_assoc($arrayQuery);

$listaFatFid = $qrBuscaFaturamento['PERCENTUAL_FIDELIZADO'];
$listaPctFatAv = $qrBuscaFaturamento['PERCENTUAL_AVULSO'];
$listaFatRes = $qrBuscaFaturamento['PERCENTUAL_RESGATE'];

$listaFatTot = $qrBuscaFaturamento['FATURAMENTO_TOTAL'];
$listaFatTotRes = $qrBuscaFaturamento['VALOR_TOTAL_RESGATE'];
$listaFatLmp = $qrBuscaFaturamento['FATURAMENTO_LIMPO'];
$listaFatAv = $qrBuscaFaturamento['FATURAMENTO_AVULSO'];
$novoTotalGeral = $qrBuscaFaturamento['VALOR_TOTAL_RESGATE'] + $qrBuscaFaturamento['FATURAMENTO_LIMPO'] + $qrBuscaFaturamento['FATURAMENTO_AVULSO'];



$sql = " CALL SP_RELAT_INDICE_FIDELIDADE_VVR ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaVendaResgate = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctVVR, $qrBuscaVendaResgate['PERCENTUAL_VVR']);
	array_push($listaTotVVR, $qrBuscaVendaResgate['TOTAL_VVR']);
	array_push($listaTotResg, $qrBuscaVendaResgate['TOTAL_RESGATE']);
}


///indice diário - loop
$sql = " CALL SP_RELAT_INDICE_ENGAJAMENTO_RESGATE ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas',$cod_empresa)";

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaEngajamentoResgate = mysqli_fetch_assoc($arrayQuery)) {

	array_push($listaPctEngaja, $qrBuscaEngajamentoResgate['PERCENTUAL_ENGAJAMENTO']);

	switch ($qrBuscaEngajamentoResgate['MES']) {

		case '1':
			$mes_extenso = '"Janeiro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '2':
			$mes_extenso = '"Fevereiro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '3':
			$mes_extenso = '"Março/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '4':
			$mes_extenso = '"Abril/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '5':
			$mes_extenso = '"Maio/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '6':
			$mes_extenso = '"Junho/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '7':
			$mes_extenso = '"Julho/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '8':
			$mes_extenso = '"Agosto/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '9':
			$mes_extenso = '"Setembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '10':
			$mes_extenso = '"Outubro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '11':
			$mes_extenso = '"Novembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		case '12':
			$mes_extenso = '"Dezembro/' . $qrBuscaEngajamentoResgate['ANO'] . '"';
			break;

		default:
			$mes_extenso = '"Mês não encontrado"';
			break;
	}

	$MES .= $mes_extenso . ',';
}

// fnEscreve($MES);

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}


$listaTotTmGeral = ($qtdTotTransacSemRes != 0) ? $listaFatLmp / ($qtdTotTransacResgate + $qtdTotTransacSemRes) : 0; // TM médio total Fidelizado
$listaTotTmGeral = fnValorSql(fnValor($listaTotTmGeral, 2));

$start    = (new DateTime($dat_ini))->modify('first day of this month');
$end      = (new DateTime($dat_fim))->modify('first day of next month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);
$unionDatas = "";
$countDatas = 0;

foreach ($period as $dt) {
	// fnEscreve($dt->format("Y-m-01"));
	if ($countDatas == 0) {
		$unionDatas .= "SELECT '" . $dt->format("Y-m-01") . "' AS mesOrdenacao ";
		$countDatas++;
	} else {
		$unionDatas .= "UNION SELECT '" . $dt->format("Y-m-01") . "' ";
	}
}

?>


<div class="push30"></div>

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

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

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Exibir Legendas</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?> checked>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-5 col-lg-offset-1">

								<h4>Tickets e Gastos Médios Limpos</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped" style="height: 100%"></canvas>
								</div>

								<?php

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								$sql = "SELECT 	A.cod_univend COD_UNIVEND,
                                                                                        uni.nom_fantasi,
                                                                                        Sum(A.qtd_totfideliz) QTD_TOTFIDELIZ, 
                                                                                        Round(Sum(A.val_totfideliz), 2) VAL_TOTFIDELIZ, 
                                                                                        Sum(qtd_cliente_fideliz) QTD_CLIENTE_FIDELIZ,
                                                                                        Round(SUM(D.VAL_CREDITO_GERADO), 2) VAL_CREDITOGERADO,
                                                                                        Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_totfideliz)  ), 2) VAL_TKTMEDIO, 
                                                                                        Round(( Round(Sum(A.val_totfideliz), 2) / Sum(A.qtd_cliente_fideliz)  ), 2) VAL_CLIENTE
                                                                                FROM vendas_diarias A
										LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA AND D.COD_UNIVEND=A.COD_UNIVEND AND D.COD_VENDEDOR=A.COD_VENDEDOR AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
                                                                                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
													WHERE A.dat_movimento BETWEEN '$dat_ini 00:00' AND '$dat_fim 23:59' AND 
													A.cod_empresa = $cod_empresa AND 
													A.cod_univend IN ($lojasSelecionadas) 
													GROUP BY A.cod_univend 
													ORDER BY A.cod_univend";

								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									$totQdtCliFide += $qrListaVendas['QTD_CLIENTE_FIDELIZ'];
								}

								// fnEscreve($totQdtCliFide);
								$listaTotGmGeral = ($totQdtCliFide != 0) ? $listaFatLmp / $totQdtCliFide : 0; // GM médio total Fidelizado
								$listaTotGmGeral = fnValorSql(fnValor($listaTotGmGeral, 2));

								?>

							</div>

							<div class="form-group text-center col-lg-5">

								<h4>Composição das Transações</h4>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="donut" style="height: 100%"></canvas>
								</div>


							</div>

							<div class="push100"></div>
							<div class="push100"></div>

							<div class="form-group text-center col-lg-12">

								<div class="push20"></div>

								<h4>Evolução Comparativa de Performance</h4>

								<div style="height: 500px;">
									<canvas id="Stacked" style="width: 100%;"></canvas>
								</div>

								<div class="push100"></div>

								<!-- <h4>Resgate Sobre o Total Vendido</h4>

								<div style="height: 500px;">
									<canvas id="Stacked2" style="width: 100%;"></canvas>
								</div>

								<div class="push10"></div>
								<div class="push30"></div> -->

								<h4>Resgate Sobre Faturamento Total</h4>

								<div style="height: 500px;">
									<canvas id="Stacked3" style="width: 100%;"></canvas>
								</div>

								<?php

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								$totFaturmLimpMensal = [];
								$totFatAvulso = [];
								$totRegasteFatMensal = [];
								$pctGeralFatMensal = [];

								// fnEscreve($sql);

								// $sql ="SELECT SUM(VAL_TOTFIDELIZ) AS FATURAMENTO_LIMPO ,
								// 	SUM(VAL_TOTVENDA) AS FATURAMENTO_TOTAL ,
								// 	SUM(VAL_TOTVENDA) - SUM(VAL_TOTFIDELIZ) AS FATURAMENTO_AVULSO,
								// 	CONCAT(YEAR(DAT_MOVIMENTO),'-',month(DAT_MOVIMENTO)) ANO_MES,
								// 	YEAR(DAT_MOVIMENTO) ANO, 
								// 	month(DAT_MOVIMENTO) MES
								// 	FROM VENDAS_DIARIAS
								// 	WHERE COD_EMPRESA = $cod_empresa
								// 	AND cod_univend IN ($lojasSelecionadas)
								// 	AND DAT_MOVIMENTO BETWEEN '$dat_ini 00:00'  AND  '$dat_fim 23:59'
								// 	GROUP BY EXTRACT(YEAR_MONTH FROM DAT_MOVIMENTO)
								// 	";

								/*$sql ="WITH meses AS (
												$unionDatas
											)
										SELECT
										IFNULL(Sum(val_totfideliz), 0) AS FATURAMENTO_LIMPO,
										IFNULL(Sum(val_totvenda), 0)   AS FATURAMENTO_TOTAL,
										IFNULL(Sum(val_totvenda), 0) - IFNULL(Sum(val_totfideliz), 0) AS FATURAMENTO_AVULSO,
										DATE_FORMAT(mesOrdenacao, '%Y-%m') AS ANO_MES,
										YEAR(mesOrdenacao) AS ANO,
										MONTH(mesOrdenacao) AS MES
										FROM meses
										LEFT JOIN
										vendas_diarias ON DATE_FORMAT(vendas_diarias.dat_movimento, '%Y-%m') = DATE_FORMAT(mesOrdenacao, '%Y-%m')
										AND cod_empresa = $cod_empresa
										AND cod_univend IN ($lojasSelecionadas)
										AND vendas_diarias.dat_movimento BETWEEN '$dat_ini 00:00'  AND  '$dat_fim 23:59'
										GROUP BY mesOrdenacao
										ORDER BY mesOrdenacao;
								";*/
								$sql = "SELECT 
                                                                            IFNULL(SUM(val_totfideliz), 0) AS FATURAMENTO_LIMPO,
                                                                            IFNULL(SUM(val_totvenda), 0) AS FATURAMENTO_TOTAL,
                                                                            IFNULL(SUM(val_totvenda), 0) - IFNULL(SUM(val_totfideliz), 0) AS FATURAMENTO_AVULSO,
                                                                            DATE_FORMAT(mesOrdenacao, '%Y-%m') AS ANO_MES,
                                                                            YEAR(mesOrdenacao) AS ANO,
                                                                            MONTH(mesOrdenacao) AS MES
                                                                        FROM (
                                                                               $unionDatas
                                                                              ) meses_temp
                                                                        LEFT JOIN vendas_diarias ON 
                                                                            DATE_FORMAT(vendas_diarias.dat_movimento, '%Y-%m') = DATE_FORMAT(mesOrdenacao, '%Y-%m')
                                                                            AND cod_empresa = $cod_empresa
                                                                            AND cod_univend IN ($lojasSelecionadas)
                                                                            AND vendas_diarias.dat_movimento BETWEEN '$dat_ini 00:00' AND '$dat_fim 23:59'
                                                                        GROUP BY mesOrdenacao
                                                                        ORDER BY mesOrdenacao;";
								//fnEscreve($sql);

								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

								while ($qrFaturamentoLimp = mysqli_fetch_assoc($arrayQuery)) {

									$sql2 =	"SELECT
											SUM(VAL_CREDITO) VAL_CREDITO,
											CONCAT(YEAR(DAT_REPROCE),'-',month(DAT_REPROCE)) ANO_MES,
											YEAR(DAT_REPROCE) ANO, 
											month(DAT_REPROCE) MES
											FROM CREDITOSDEBITOS 
											WHERE COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) 
											AND TIP_CREDITO = 'D' 
											AND COD_EMPRESA = $cod_empresa
											AND cod_univend IN ($lojasSelecionadas)
											AND COD_VENDA > 0
											AND DATE_FORMAT(DAT_REPROCE, '%Y-%m') = '$qrFaturamentoLimp[ANO_MES]'
											GROUP BY EXTRACT(YEAR_MONTH FROM DAT_REPROCE)
											";

									// fnEscreve($sql2);
									$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

									$cred = '0.00';

									while ($qrRegasteFatMensal = mysqli_fetch_assoc($arrayQuery2)) {

										if ($qrRegasteFatMensal['VAL_CREDITO'] != "") {
											$cred = $qrRegasteFatMensal['VAL_CREDITO'];
										} else {
											$cred = '0.00';
										}
									}

									array_push($totRegasteFatMensal, $cred);
									array_push($totFaturmLimpMensal, $qrFaturamentoLimp['FATURAMENTO_LIMPO']);
									array_push($totFatAvulso, $qrFaturamentoLimp['FATURAMENTO_AVULSO']);
								}
								// print_r($totRegasteFatMensal);


								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";




								for ($i = 0; $i < count($totFaturmLimpMensal); $i++) {
									$resultado = ($totFaturmLimpMensal[$i] + $totRegasteFatMensal[$i] + $totFatAvulso[$i] != 0) ? $totRegasteFatMensal[$i] / ($totFaturmLimpMensal[$i] + $totRegasteFatMensal[$i] + $totFatAvulso[$i]) : 0;
									$resultado_porcentagem = $resultado * 100;
									$resultado_formatado = number_format($resultado_porcentagem, 2);
									array_push($pctGeralFatMensal, $resultado_formatado);
									// fnEscreve("Para o mês " . ($i+1) . ", o resultado é: " . $resultado_formatado . "<br>");
								}

								// echo "<pre>";
								// print_r($totRegasteFatMensal);
								// echo "</pre>";

								?>

							</div>

							<div class="push100"></div>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

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

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>



<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

<?php
}
?>

<script>
	//datas
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: moment().subtract(1, 'days'),
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


	//graficos
	$(document).ready(function() {


		//chosen
		// $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		// $('#formulario').validator();

		//grouped
		new Chart(document.getElementById("bar-chart-grouped"), {
			type: 'bar',
			data: {
				labels: ["Ticket Médio", "Gasto Médio"],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#FF3784',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Fidelizado",
					borderColor: '#FF3784',
					backgroundColor: 'rgba(255,55,132,0.7)',
					borderWidth: 1,
					data: [<?= $listaTotTmGeral ?>, <?= $listaTotGmGeral ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#606060',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Não Fidelizado",
					borderColor: '#606060',
					backgroundColor: 'rgba(96,96,96,0.7)',
					borderWidth: 1,
					data: [<?= $listaTotTmAvulso ?>, <?= $listaTotGmAvulso ?>]
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				title: {
					display: true,
					text: 'TMs e GMs Fidelizados [CR e SR] vs Não Fidelizados'
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return 'R$ ' + t.yLabel.toFixed(2)
						}
					}
				},
				scales: {
					yAxes: [{
						ticks: {
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
							}
						}
					}]
				},

			}
		});

		//donut
		// setup 
		const dataDonut = {
			labels: [" COM Resgate",
				" SEM Resgate",
				" Não Fidelizado"
			],
			datasets: [{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: [
							'#CC2738',
							'#FF3784',
							'#606060',
						],
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value.toFixed(2).replace('.', ',') + '%';
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				data: [
					<?= $listaTotTransacResgate ?>,
					<?= $listaTotTransacSem ?>,
					<?= $listaTotTransacAvulso ?>

				],
				amount: [
					<?= $qtdTotTransacResgate ?>,
					<?= $qtdTotTransacSemRes ?>,
					<?= $qtdTotTransacAvulso ?>

				],
				backgroundColor: [
					'rgba(204,39,56,0.7)',
					'rgba(255,55,132,0.7)',
					'rgba(96,96,96,1)',
				],
				borderColor: [
					'#CC2738',
					'#FF3784',
					'#606060',
				],
				borderWidth: [1, 1, 0],
			}]
		};

		//config
		const configDonut = {
			type: 'doughnut',
			data: dataDonut,
			options: {
				title: {
					display: true,
					text: 'Transações Fidelizadas [CR e SR] vs Não Fidelizadas'
				},
				responsive: true,
				enabled: false,
				mode: 'index',
				position: 'outside',
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function(tooltipItem, data) {
							var amountData = data['datasets'][0]['amount'][tooltipItem['index']];
							return amountData.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " - " + data['datasets'][0]['data'][tooltipItem['index']] + '%';
						}
					}
				}
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels]
			<?php } ?>
		};

		// render init block
		const myChart = new Chart(
			document.getElementById('donut'),
			configDonut
		);




		// stackeds graphs
		var newScale = <?php echo (0 + 10) ?>;

		new Chart(document.getElementById("Stacked"), {
			type: 'bar',
			data: {
				labels: [<?php echo substr($MES, 0, -1); ?>],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#F1C40F',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
								} else {
									return value + '%';
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: '% VVR',
					yAxisID: 'porcentagem',
					borderColor: '#F1C40F',
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($listaPctVVR); ?>,
					type: 'line',
					fill: false
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#CC2738',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: 'Resgate Total',
					borderColor: '#CC2738',
					backgroundColor: 'rgba(204,39,56,0.3)',
					borderWidth: 3,
					order: 2,
					data: <?php echo json_encode($totRegasteFatMensal); ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#4BC0C0',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: 'VVR',
					borderColor: '#4BC0C0',
					backgroundColor: 'rgba(75,192,192,0.7)',
					borderWidth: 3,
					order: 1,
					data: <?php echo json_encode($listaTotVVR); ?>
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
				scales: {
					yAxes: [{
						stacked: true,
						ticks: {
							suggestedMax: newScale,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$' + value;
								}
							}
						}
					}, {
						id: 'porcentagem',
						type: 'linear',
						position: 'right',
						ticks: {
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
					xAxes: [{
						stacked: true
					}],
				},
				tooltips: {
					// enabled: true,
					// intersect: false,
					mode: 'index',
					callbacks: {
						label: function(t, d) {
							var xLabel = d.datasets[t.datasetIndex].label;
							var yLabel = t.yLabel;
							// if line chart
							if (t.datasetIndex === 0) return xLabel + ': ' + yLabel.toFixed(2) + '%';
							else return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						}
					}
				},
			}
		});


		// new Chart(document.getElementById("Stacked2"), {
		// 	  type: 'bar',
		// 	  data: {
		// 		labels: [<?php echo substr($MES, 0, -1); ?>],
		// 		datasets: [{
		// 			<?php if ($log_labels == 'S') { ?>
		// 				datalabels: {
		// 					clamp: true,
		// 					align: 'middle',
		// 					anchor: 'end',
		// 					borderRadius: 4,
		// 					backgroundColor: '#F1C40F',
		// 					color: '#fff',
		// 					formatter: function(value) {
		// 						if (parseInt(value) >= 1000) {
		//							return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
		//						} else {
		//							return value + '%';
		//						}
		// 						// eq. return ['line1', 'line2', value]
		// 					}
		// 				},
		// 			<?php } ?>
		// 			label: '% VVR',
		// 			yAxisID: 'porcentagem',
		// 			borderColor: '#F1C40F',
		// 			pointBackgroundColor: "#fff",
		// 			pointRadius: 4,
		// 			pointBorderWidth: 3,
		// 			data: <?php echo json_encode($listaPctVVR); ?>,
		// 			type: 'line',
		// 			fill: false
		// 		}, {
		// 			<?php if ($log_labels == 'S') { ?>
		// 				datalabels: {
		// 					clamp: true,
		// 					align: 'middle',
		// 					anchor: 'end',
		// 					borderRadius: 4,
		// 					backgroundColor: '#4180ab',
		// 					color: '#fff',
		// 					formatter: function(value) {
		// 						if (parseInt(value) >= 1000) {
		// 							return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 						} else {
		// 							return 'R$ ' + value;
		// 						}
		// 						// eq. return ['line1', 'line2', value]
		// 					}
		// 				},
		// 			<?php } ?>
		// 			label: 'VVR',
		// 			borderColor: '#4180ab',
		// 			backgroundColor: 'rgba(38, 143, 190,0.7)',
		// 			borderWidth: 3,
		// 			order: 2,
		// 			data: <?php echo json_encode($listaTotVVR); ?>
		// 		}, {
		// 			<?php if ($log_labels == 'S') { ?>
		// 				datalabels: {
		// 					clamp: true,
		// 					align: 'middle',
		// 					anchor: 'end',
		// 					borderRadius: 4,
		// 					backgroundColor: '#CC2738',
		// 					color: '#fff',
		// 					formatter: function(value) {
		// 						if (parseInt(value) >= 1000) {
		// 							return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 						} else {
		// 							return 'R$ ' + value;
		// 						}
		// 						// eq. return ['line1', 'line2', value]
		// 					}
		// 				},
		// 			<?php } ?>
		// 			label: 'Valor do Resgate',
		// 			borderColor: '#CC2738',
		// 			backgroundColor: 'rgba(204,39,56,0.3)',
		// 			borderWidth: 3,
		// 			order: 1,
		// 			data: <?php echo json_encode($listaTotResg); ?>
		// 		}]
		// 	},
		// 	<?php if ($log_labels == 'S') { ?>
		// 		plugins: [ChartDataLabels],
		// 	<?php } ?>
		// 	options: {
		// 		legend: {
		// 			display: true,
		// 			position: 'bottom'
		// 		},
		// 		maintainAspectRatio: false,
		// 		animation: {
		// 			duration: 2000,
		// 		},
		// 		scales: {
		// 			yAxes: [{
		// 				stacked: true,
		// 				ticks: {
		// 					suggestedMax: newScale,
		// 					callback: function(value, index, values) {
		// 						if (parseInt(value) >= 1000) {
		// 							return 'R$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 						} else {
		// 							return 'R$' + value;
		// 						}
		// 					}
		// 				}
		// 			},{
		// 				id: 'porcentagem',
		// 				type: 'linear',
		// 				position: 'right',
		// 				ticks: {
		// 					suggestedMin: 0
		// 				},
		// 				afterTickToLabelConversion: function(object) {
		// 					for (var tick in object.ticks) {
		// 						object.ticks[tick] += '%';
		// 					}
		// 				}
		// 			}],
		// 			xAxes: [{
		// 			stacked: true
		// 			}],
		// 		},
		// 		tooltips: {
		// 			enabled: true,
		// 			intersect: false,
		// 			mode: 'index',
		// 			callbacks: {
		// 				label: function(t, d) {
		// 					var xLabel = d.datasets[t.datasetIndex].label;
		// 					var yLabel = t.yLabel;
		// 					// if line chart
		// 					if (t.datasetIndex === 0) return xLabel + ': ' + yLabel.toFixed(2) + '%';
		// 					// if bar chart
		// 					// else if (t.datasetIndex === 1) return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 					else return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 				}
		// 			}
		// 		},
		// 	}
		// });


		new Chart(document.getElementById("Stacked3"), {
			type: 'bar',
			data: {
				labels: [<?php echo substr($MES, 0, -1); ?>],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#F1C40F',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
								} else {
									return value + '%';
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: '% Sobre o Faturamento Total',
					yAxisID: 'porcentagem',
					borderColor: '#F1C40F',
					pointBackgroundColor: "#fff",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: <?php echo json_encode($pctGeralFatMensal); ?>,
					type: 'line',
					fill: false
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#CC2738',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: 'Resgate Total',
					borderColor: '#CC2738',
					backgroundColor: 'rgba(204,39,56,0.3)',
					borderWidth: 3,
					order: 1,
					data: <?php echo json_encode($totRegasteFatMensal); ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#f77c3e',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: 'Fideliz. Limpo',
					borderColor: '#f77c3e',
					backgroundColor: 'rgba(250, 186, 102,0.7)',
					borderWidth: 3,
					order: 2,
					data: <?php echo json_encode($totFaturmLimpMensal); ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#606060',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$ ' + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: 'Avulso',
					borderColor: '#606060',
					backgroundColor: 'rgba(96,96,96,0.7)',
					borderWidth: 3,
					order: 2,
					data: <?php echo json_encode($totFatAvulso); ?>
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
				scales: {
					yAxes: [{
						stacked: true,
						ticks: {
							suggestedMax: newScale,
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return 'R$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return 'R$' + value;
								}
							}
						}
					}, {
						id: 'porcentagem',
						type: 'linear',
						position: 'right',
						ticks: {
							suggestedMin: 0
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
					xAxes: [{
						stacked: true
					}],
				},
				tooltips: {
					enabled: true,
					intersect: false,
					mode: 'index',
					callbacks: {
						label: function(t, d) {
							var xLabel = d.datasets[t.datasetIndex].label;
							var yLabel = t.yLabel;
							// if line chart
							if (t.datasetIndex === 0) return xLabel + ': ' + yLabel.toFixed(2) + '%';
							// if bar chart
							// else if (t.datasetIndex === 1) return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							else return xLabel + ': R$ ' + yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						}
					}
				},
			}
		});

	});
</script>