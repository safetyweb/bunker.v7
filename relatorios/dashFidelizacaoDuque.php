<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$lojasSelecionadas = "";
$qrBuscaIndFideliza = "";
$indice_fidelizacao = "";
$sqlVF = "";
$qrBuscaVendasTotais = "";
$transacoes = "";
$transacoes_fidelizacao = "";
$valor_total_venda = 0;
$valor_total_venda_fidelizado = 0;
$tot_qtd_produto_av = 0;
$tot_qtd_produto_fid = 0;
$pct_fidelizado = "";
$data_venda = "";
$pct_diario = "";
$dia_venda = "";
$listaDiarioDias = "";
$listaDiarioPct = "";
$listaTotalFideliz = "";
$qrBuscaVendasFideliz = "";
$data_venda_fideliz = "";
$pct_diario_total = 0;
$pct_diario_fideliz = "";
$dia_venda_fideliz = "";
$contaIndiceDiario = "";
$listaDiarioDiasFideliz = "";
$listaDiarioTot = "";
$listaDiarioFideliz = "";
$tempValor = "";
$qrBuscaResgates = "";
$data_resgates = "";
$val_resgates = "";
$dia_resgates = "";
$listaDiasResgates = "";
$listaValorResgates = "";
$data_fim = "";
$cod_univend = '';

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 7 days')));

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

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

//índice de fidelização
$sql = "SELECT ((SUM(QTD_TOTFIDELIZ)/SUM(QTD_TOTVENDA))*100) AS INDICE_FIDELIZACAO 
			FROM VENDAS_DIARIAS  
			WHERE 
			  DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			  COD_EMPRESA = $cod_empresa AND 
			 COD_UNIVEND IN($lojasSelecionadas)
			  ";

//fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaIndFideliza = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaIndFideliza)) {
	$indice_fidelizacao = $qrBuscaIndFideliza['INDICE_FIDELIZACAO'];
}


//ticket médio - homens / mulheres


//Vendas X Fidelização / Resgates
//select 1
/*
	$sql = " SELECT  SUM(QTD_TOTVENDA) AS TRANSACOES, 
			   SUM(QTD_TOTFIDELIZ) AS TRANSACOES_FIDELIZACAO, 
			   SUM(VAL_TOTVENDA) AS VALOR_TOTAL_VENDA, 
			   SUM(VAL_TOTFIDELIZ) AS VALOR_TOTAL_VENDA_FIDELIZADO, 
			   SUM(QTD_RESGATE) AS QTD_RESGATES, 
			   SUM(VAL_RESGATE) AS VAL_RESGATE, 
			   SUM(VAL_CREDITOGERADO) AS VAL_CREDITOGERADO 
			FROM VENDAS_DIARIAS  
			WHERE 
			  DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' 
			  AND COD_EMPRESA = $cod_empresa 
			  AND COD_UNIVEND IN($lojasSelecionadas) ";		
	*/

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

$sqlVF = "SELECT Sum(A.qtd_totvenda) AS TRANSACOES, 
				   Sum(A.qtd_totfideliz) AS TRANSACOES_FIDELIZACAO, 
				   Sum(A.val_totvenda)   AS VALOR_TOTAL_VENDA, 
				   Sum(A.val_totfideliz) AS VALOR_TOTAL_VENDA_FIDELIZADO, 
				   Sum(A.val_compra_masc) / ( Sum(qtd_compra_masc) ) MEDIO_MASC, 
				   Sum(A.val_compra_femi) / ( Sum(qtd_compra_femi) ) MEDIO_FEM,
				   (SELECT SUM(i.QTD_PRODUTO) AS QTD_PRODUTO FROM itemvenda i
					INNER JOIN vendas v ON v.COD_VENDA=i.COD_VENDA
					WHERE i.dat_cadastr BETWEEN '$dat_ini' AND '$dat_fim'
					AND v.COD_EMPRESA=19 AND i.cod_cliente='58272'  ) AS  SUM_QTD_PRODUTO_AV,
					(SELECT SUM(i.QTD_PRODUTO) AS QTD_PRODUTO FROM itemvenda i
					INNER JOIN vendas v ON v.COD_VENDA=i.COD_VENDA
					WHERE i.dat_cadastr BETWEEN '2019-09-01 00:00:00' AND '2019-09-01 23:59:59'
					AND v.COD_EMPRESA=19 AND i.cod_cliente!='58272') AS  SUM_QTD_PRODUTO_FID
			FROM   vendas_diarias A 
			WHERE  A.dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' 
				   AND A.cod_empresa = $cod_empresa 
				   AND A.cod_univend IN($lojasSelecionadas) ";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlVF);
$qrBuscaVendasTotais = mysqli_fetch_assoc($arrayQuery);
//fnEscreve($sqlVF);

if (isset($qrBuscaVendasTotais)) {
	$transacoes = $qrBuscaVendasTotais['TRANSACOES'];
	$transacoes_fidelizacao = $qrBuscaVendasTotais['TRANSACOES_FIDELIZACAO'];
	$valor_total_venda = $qrBuscaVendasTotais['VALOR_TOTAL_VENDA'];
	$valor_total_venda_fidelizado = $qrBuscaVendasTotais['VALOR_TOTAL_VENDA_FIDELIZADO'];

	$tot_qtd_produto_av = $qrBuscaVendasTotais['SUM_QTD_PRODUTO_AV'];
	$tot_qtd_produto_fid = $qrBuscaVendasTotais['SUM_QTD_PRODUTO_FID'];

	$valor_ticket_fidelizado_hom = $qrBuscaVendasTotais['MEDIO_MASC'];
	$valor_ticket_fidelizado_mul = $qrBuscaVendasTotais['MEDIO_FEM'];

	$valor_ticket_avulso = $transacoes - $transacoes_fidelizacao != 0 ? ($valor_total_venda - $valor_total_venda_fidelizado / $transacoes - $transacoes_fidelizacao) : 0;
	$valor_ticket_fidelizado = $transacoes_fidelizacao != 0 ? ($valor_total_venda_fidelizado / $transacoes_fidelizacao) : 0;
}

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

$sqlRGT = " SELECT Sum(A.qtd_resgate) AS QTD_RESGATES, 
				   Sum(A.val_resgate)  AS VAL_RESGATE, 
				   Sum(A.val_credito_gerado) AS VAL_CREDITOGERADO 
			FROM   creditosdebitos_diarias A 
			WHERE  A.dat_movimento BETWEEN '$dat_ini' AND '$dat_fim' 
				   AND A.cod_empresa = $cod_empresa
				   AND A.cod_univend IN($lojasSelecionadas) ";

//fntestesql(connTemp($cod_empresa,''),$sql);
//fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlRGT);
$qrBuscaResgatesTotais = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaResgatesTotais)) {

	$qt_resgates_tot = $qrBuscaResgatesTotais['QTD_RESGATES'];
	$pct_fidelizado =  $valor_total_venda != 0 ? ($valor_total_venda_fidelizado * 100) / $valor_total_venda : 0;

	$val_resgates_tot = $qrBuscaResgatesTotais['VAL_RESGATE'];
	$val_gerados_tot = $qrBuscaResgatesTotais['VAL_CREDITOGERADO'];
}

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//indice diário - loop
$sql = "SELECT A.DAT_MOVIMENTO AS DATA_VENDA, 
			  ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2) AS PCT_DIARIO 
			FROM VENDAS_DIARIAS A 
			WHERE   DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			  A.COD_EMPRESA = $cod_empresa AND 
			  A.COD_UNIVEND IN($lojasSelecionadas) 
			  GROUP BY DAT_MOVIMENTO ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaIndiceDiario = mysqli_fetch_assoc($arrayQuery)) {
	$data_venda = $qrBuscaIndiceDiario['DATA_VENDA'];
	$pct_diario = $qrBuscaIndiceDiario['PCT_DIARIO'];
	$dia_venda = date('d', strtotime($data_venda));
	$listaDiarioDias =  $listaDiarioDias . "'" . $dia_venda . "',";
	$listaDiarioPct =  $listaDiarioPct . $pct_diario . ",";
	//fnEscreve($data_venda);
}

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca vendas fidelização - loop
$sql = "SELECT DAT_MOVIMENTO, SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ 
			FROM VENDAS_DIARIAS  
			WHERE 
			 DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			COD_EMPRESA = $cod_empresa AND 
			COD_UNIVEND IN($lojasSelecionadas) 
			GROUP BY DAT_MOVIMENTO 
			ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$listaTotalFideliz = 0;

while ($qrBuscaVendasFideliz = mysqli_fetch_assoc($arrayQuery)) {
	$data_venda_fideliz = $qrBuscaVendasFideliz['DAT_MOVIMENTO'];
	$pct_diario_total = $qrBuscaVendasFideliz['QTD_TOTAVULSA'];
	$pct_diario_fideliz = $qrBuscaVendasFideliz['QTD_TOTFIDELIZ'];
	//fnEscreve($qrBuscaIndiceDiario['PCT_DIARIO']);
	$dia_venda_fideliz = date('d', strtotime($data_venda_fideliz));
	//fnEscreve($contaIndiceDiario." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	//fnEscreve($dia_venda." - ".fnFormatDate($data_venda)." / ".$pct_diario."%");
	$listaDiarioDiasFideliz =  $listaDiarioDiasFideliz . "'" . $dia_venda_fideliz . "',";
	$listaDiarioTot =  $listaDiarioTot . $pct_diario_total . ",";
	$listaDiarioFideliz =  $listaDiarioFideliz . $pct_diario_fideliz . ",";

	$tempValor = $pct_diario_total + $pct_diario_fideliz;
	if ($tempValor > $listaTotalFideliz) {
		$listaTotalFideliz = $tempValor;
	}
}

//fnEscreve($pct_diario_fideliz);	
//fnEscreve($pct_diario_total);	
//fnEscreve($listaTotalFideliz);	
//fnEscreve($listaDiarioPct);

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//busca resgates - loop
$sql = "SELECT DAT_MOVIMENTO,SUM(VAL_RESGATE) AS VAL_RESGATE 
			FROM CREDITOSDEBITOS_DIARIAS 
			WHERE 
			DAT_MOVIMENTO BETWEEN  '$dat_ini' AND '$dat_fim' and
			COD_EMPRESA = $cod_empresa AND 
			COD_UNIVEND IN($lojasSelecionadas) 
			GROUP BY DAT_MOVIMENTO 
			ORDER BY DAT_MOVIMENTO ";

//fnEscreve($sql);	

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

while ($qrBuscaResgates = mysqli_fetch_assoc($arrayQuery)) {
	$data_resgates = $qrBuscaResgates['DAT_MOVIMENTO'];
	$val_resgates = $qrBuscaResgates['VAL_RESGATE'];
	$dia_resgates = date('d', strtotime($data_resgates));
	$listaDiasResgates =  $listaDiasResgates . "'" . $dia_resgates . "',";
	$listaValorResgates =  $listaValorResgates . $val_resgates . ",";
	//$contaIndiceDiario++;
}


//fnMostraForm();
//fnEscreve(substr($listaDiarioDiasFideliz,0,-1));
//fnEscreve($hoje);
//fnEscreve($dias30);
//fnEscreve(strlen($dat_ini));
//fnEscreve(strlen($dat_fim));
//fnEscreve($data_fim);
//fnEscreve($lojasSelecionadas);


?>

<style>
	.slim {
		height: 20px;
	}

	.progress {
		border-radius: 3px;
		height: 15px;
		white-space: nowrap;
		word-spacing: nowrap;
	}

	.skill-name {
		text-transform: uppercase;
		margin-left: 10px;
		padding-left: 10px;
		padding-top: 2.5px;
		float: left;
		font-family: 'Raleway', sans-serif;
		font-size: 1.1em;
	}

	.progress .progress-bar,
	.progress .progress-bar.progress-bar-default {
		background-color: #3498DB;
	}

	.progress .progress-bar {
		animation-name: animateBar;
		animation-iteration-count: 1;
		animation-timing-function: ease-in;
		animation-duration: 1.0s;
	}

	@keyframes animateBar {
		0% {
			transform: translateX(-100%);
		}

		100% {
			transform: translateX(0);
		}
	}
</style>


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

						<div class="push20"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-3">
								<h4>Índice de Fidelização</h4>
								<div class="push20"></div>

								<canvas id="foo">guage</canvas>

								<div class="row">

									<div class="form-group text-right col-lg-4" style="padding:0 30px 0 0;">

									</div>
									<div class="form-group text-center col-lg-4">
										<h3 style="margin:10px 0 0 0;"><?php echo fnValor($indice_fidelizacao, 2); ?>%</h3>
									</div>
									<div class="form-group text-left col-lg-4" style="padding:0 0 0 20px;">

									</div>

								</div>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-lg-9">
								<h4>Índice Diário</h4>
								<div class="push20"></div>

								<div style="height: 200px; width:100%;">
									<canvas id="lineChart"></canvas>
								</div>

							</div>

							<div class="push50"></div>

							<div class="form-group text-center col-lg-7">
								<h4>Vendas X Fidelização</h4>
								<div class="push20"></div>

								<div class="col-md-2">
									<b class="f18 text-info"><?php echo fnValor($transacoes, 0); ?></b>
									<p class="small">Transações</p>
								</div>

								<div class="col-md-2">
									<b class="f18 text-info"><?php echo fnValor($transacoes_fidelizacao, 0); ?></b>
									<p class="small">Fidelizados</p>
								</div>

								<div class="col-md-3">
									<b class="f18 text-info">R$ <?php echo fnValor($valor_total_venda, 2); ?></b>
									<p class="small">Valor Total</p>
								</div>

								<div class="col-md-3">
									<b class="f18 text-info">R$ <?php echo fnValor($valor_total_venda_fidelizado, 2); ?></b>
									<p class="small">Valor Fidelizado</p>
								</div>

								<div class="col-md-2">
									<b class="f18 text-info"><?php echo fnValor($pct_fidelizado, 2); ?>%</b>
									<p class="small">Pct. Faturamento</p>
								</div>

								<div class="col-md-4">
								</div>

							</div>

							<div class="form-group text-center col-lg-5">
								<h4>Ticket Médio</h4>
								<div class="push20"></div>

								<div class="col-md-3">
									<b style="font-size: 18px;" class="text-info">R$ <?php echo fnValor($valor_ticket_fidelizado, 2); ?> </b>
									<p class="small">Fidelizado</p>
								</div>

								<div class="col-md-3">
									<b style="font-size: 18px;" class="text-info"> R$ <?php echo fnValor($valor_ticket_fidelizado_mul, 2); ?> </b>
									<p class="small">Mulheres</p>
								</div>

								<div class="col-md-3">
									<b style="font-size: 18px;" class="text-info"> R$ <?php echo fnValor($valor_ticket_fidelizado_hom, 2); ?> </b>
									<p class="small">Homens</p>
								</div>

								<div class="col-md-3">
									<b style="font-size: 18px;" class="text-info"> R$ <?php echo fnValor($valor_ticket_avulso, 2); ?> </b>
									<p class="small">Avulso</p>
								</div>

							</div>

							<div class="form-group text-center col-lg-6">

							</div>

							<div class="push30"></div>

							<div class="form-group text-center col-lg-6">

								<canvas id="Stacked"></canvas>

							</div>

							<div class="form-group text-center col-lg-6">

								<canvas id="bar-chart-grouped" style="width: 80%;"></canvas>

							</div>

							<div class="push50"></div>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


						<div class="push30"></div>


						<span class="f12" style="color: #fff;">
							<?php
							echo ($sqlVF);
							?>
							<div class="push20"></div>
							<?php
							echo ($sqlRGT);
							?>
						</span>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	//datas
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		var userDate = $('#DAT_INI').val();
		var dat_inicial = moment(userDate, "DD/MM/YYYY").add(15, 'days').format("YYYY-MM-DD");

		//alert(userDate);

		$('#DAT_INI_GRP').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: '2018-12-31'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('#DAT_FIM_GRP').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: dat_inicial
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('#DAT_INI_GRP').on('dp.change', function() {
			userDate = $('#DAT_INI').val();
			dat_inicial = moment(userDate, "DD/MM/YYYY").add(15, 'days').format("YYYY-MM-DD");
			$('#DAT_FIM_GRP').datetimepicker('destroy');
			$('#DAT_FIM_GRP').datetimepicker({
				format: 'DD/MM/YYYY',
				maxDate: dat_inicial
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

		});

		// if(cod_empresa == 77){

		// 	$('.datePicker').datetimepicker({
		// 		 format: 'DD/MM/YYYY',
		// 		 maxDate : 'now',
		// 		 minDate : '2018-12-31'
		// 	}).on('changeDate', function(e){
		// 		$(this).datetimepicker('hide');
		// 	});

		// }else{

		// 	$('.datePicker').datetimepicker({
		// 		 format: 'DD/MM/YYYY',
		// 		 maxDate : 'now',
		// 	}).on('changeDate', function(e){
		// 		$(this).datetimepicker('hide');
		// 	});

		// }

		// $("#DAT_INI_GRP").on("dp.change", function (e) {
		// 	$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		// });

		// $("#DAT_FIM_GRP").on("dp.change", function (e) {
		// 	$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		// });


	});

	//graficos
	$(document).ready(function() {

		$('#btShow').click(function() {
			$("#dvShow").hide();
			$("#dvHide").show();
			$("#expLojas").toggle("slow");
		});

		$('#btHide').click(function() {
			$("#dvHide").hide();
			$("#dvShow").show();
			$("#expLojas").toggle("slow");
		});
		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)


		$('#demo-pie-1').pieChart({
			barColor: '#3bb2d0',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//grouped
		new Chart(document.getElementById("bar-chart-grouped"), {
			type: 'bar',
			data: {
				labels: ["Fidelizados", "Avulsos"],
				datasets: [{
					backgroundColor: ['#FE6384', '#37A2EB'],
					data: [<?= $tot_qtd_produto_fid ?>, <?= $tot_qtd_produto_av ?>]
				}]
			},
			options: {
				legend: {
					display: false,
					position: 'bottom'
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.yLabel.toFixed(2)
						}
					}
				},
				scales: {
					yAxes: [{
						ticks: {
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return value;
								}
							}
						}
					}],
					xAxes: [{
						barPercentage: 0.4
					}]
				},

			}
		});

		// Line chart
		var ctx = document.getElementById("lineChart");
		var lineChart = new Chart(ctx, {
			type: 'line',
			onAnimationComplete: new function() {

			},
			data: {
				labels: [<?php echo substr($listaDiarioDias, 0, -1); ?>],
				datasets: [{
					label: "Percentual atingido",
					backgroundColor: "rgba(93, 173, 226, 0.3)",
					borderColor: "rgba(40, 116, 166, 0.80)",
					pointBorderColor: "rgba(3, 88, 106, 0.70)",
					pointBackgroundColor: "#fff",
					pointHoverBackgroundColor: "#fff",
					pointHoverBorderColor: "rgba(151,187,205,1)",
					pointRadius: 4,
					pointBorderWidth: 3,
					data: [<?php echo substr($listaDiarioPct, 0, -1); ?>]
				}]
			},
			options: {
				legend: {
					display: false
				},
				maintainAspectRatio: false,
				animation: {
					duration: 2000,
				},
				scales: {
					yAxes: [{
						ticks: {
							suggestedMin: 0,
							suggestedMax: 100
						},
						afterTickToLabelConversion: function(object) {
							for (var tick in object.ticks) {
								object.ticks[tick] += '%';
							}
						}
					}],
				},
				tooltips: {
					enabled: true
				}
			}
		});



		var barChartData = {
			labels: [<?php echo substr($listaDiarioDiasFideliz, 0, -1); ?>],
			datasets: [{
				label: ' Fidelizados',
				backgroundColor: window.chartColors.red,
				data: [<?php echo substr($listaDiarioFideliz, 0, -1); ?>]
			}, {
				label: ' Avulsos',
				backgroundColor: window.chartColors.blue,
				data: [<?php echo substr($listaDiarioTot, 0, -1); ?>]
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
				maintainAspectRatio: true,
				animation: {
					duration: 2000
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
					enabled: true,
					intersect: false,
					mode: 'index',
				}
			}
		});

		var MultiChartData = {
			labels: [<?php echo substr($listaDiarioDiasFideliz, 0, -1); ?>],
			datasets: [{
				label: 'Fidelizados',
				borderColor: "rgba(20, 143, 119, 0.80)",
				pointBorderColor: "rgba(3, 88, 106, 0.70)",
				pointBackgroundColor: "#fff",
				pointHoverBackgroundColor: "rgba(3, 88, 106, 0.70)",
				pointHoverBorderColor: "rgba(3, 88, 106, 0.70)",
				pointRadius: 4,
				pointBorderWidth: 3,
				fill: false,
				data: [<?php echo substr($listaDiarioFideliz, 0, -1); ?>]
			}, {
				label: 'Avulsos',
				borderColor: "#ff6699",
				pointBorderColor: "#cc0044",
				pointBackgroundColor: "#cc0044",
				pointHoverBackgroundColor: "#cc0044",
				pointHoverBorderColor: "#cc0044",
				pointRadius: 4,
				pointBorderWidth: 3,
				fill: false,
				data: [<?php echo substr($listaDiarioTot, 0, -1); ?>]
			}]

		};

		//Gauge
		var opts = {
			lines: 10, // The number of lines to draw
			angle: 0, // The length of each line
			lineWidth: 0.34, // The line thickness
			pointer: {
				length: 0.6, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#566573' // Fill color
			},
			colorStart: '#1ABC9C', // Colors
			colorStop: '#1ABC9C', // just experiment with them
			strokeColor: '#E0E0E0', // to see which ones work best for you
			generateGradient: true
		};

		var target = document.getElementById('foo'); // your canvas element
		var gauge = new Gauge(target);
		//alert(gauge);
		gauge.setOptions(opts); // create sexy gauge!
		gauge.maxValue = 100; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(<?php echo $indice_fidelizacao; ?>); // set actual value

	});
</script>