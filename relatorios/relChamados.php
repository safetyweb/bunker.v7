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
$log_labels = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$checkLabels = "";
$sqlEmpresa = "";
$arrayEmpresa = [];
$arrEmp = "";
$arrPct = "";
$arrCor = "";
$num = "";
$hash = "";
$qrEmpresa = "";
$cores = "";
$total_chamados = 0;
$sqlTipo = "";
$arrayTipo = [];
$qrTipo = "";
$arrTipo = "";
$arrCor2 = "";
$i = 0;
$cores2 = "";
$pct_suporte = "";
$pct_dev = "";
$pct_duvida = "";
$pct_implata = "";
$pct_falha = "";
$pct_melhoria = "";
$pct_outros = "";
$sqlBarras = "";
$arrayBar = [];
$arrMes = "";
$arrAbertoGeral = "";
$arrAbertoMes = "";
$arrConcluidoGeral = "";
$arrConcluidoMes = "";
$arrCorBar = "";
$mesesAno = "";
$qrBar = "";
$data = "";
$mes = "";
$arrDayOne = "";
$arrLastDay = "";
$sqlSemana = "";
$arrSemana = "";
$qrSemana = "";
$arrDayOneGeral = "";
$totLastDay = "";
$arrNumSemana = "";
$arrLastDayGeral = "";
$sqlFechados = "";
$arrFechados = "";
$qrClose = "";
$dia = "";
$s = "";
$time = "";
$start = "";
$end = "";
$format = "";
$start_day = "";
$end_day = "";
$periodo = "";
$arrPeriodo = [];
$domingo = "";
$sabado = "";
$diaSemana = "";
$semana = "";
$arrSemanaPassada = "";
$arrSemanaAtual = "";
$sqlBalance = "";
$sqlCod = "";
$arrCod = "";
$qrCod = "";
$cod_chamado_aberto = "";
$sqlCod2 = "";
$arrCod2 = "";
$qrCod2 = "";
$cod_chamado_fechado = "";
$qrTotal = "";
$listaFatLmp = "";
$listaFatAv = "";
$content = "";
$cod_univend = '';



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

		if (empty(@$_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = @$_REQUEST['LOG_LABELS'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
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

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}



// ==============================================================================

// ########################### EMPRESAS ##########################################

$sqlEmpresa = "SELECT 
							'Outros' NOM_FANTASI , 
							TOTAL_GERAL, 
							sum(QTD_TOTAL) QTD_TOTAL,
						   ROUND(SUM(PCT_CHAMADOS),2) PCT_CHAMADOS 
							
					 FROM ( 
					 
					       SELECT em.NOM_FANTASI, 
						   (SELECT SUM(1) FROM sac_chamados CH2 WHERE DATE(ch2.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim') TOTAL_GERAL,
						   SUM(CASE WHEN DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_TOTAL, 
						    
						   (SUM(CASE WHEN DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) * 100) /
							(SELECT SUM(1) FROM sac_chamados CH2 WHERE DATE(ch2.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim') 
							   PCT_CHAMADOS 
							FROM sac_chamados ch 
						   INNER JOIN sac_status st ON st.COD_STATUS=ch.COD_STATUS 
						   INNER JOIN sac_tpsolicitacao tp ON tp.COD_TPSOLICITACAO=ch.COD_TPSOLICITACAO 
						   INNER JOIN webtools.EMPRESAS em ON em.COD_EMPRESA=ch.COD_EMPRESA WHERE YEAR(ch.dat_cadastr) BETWEEN YEAR('$dat_ini') AND YEAR('$dat_fim') GROUP BY ch.COD_EMPRESA 
						
						   )tmpporcentotres 
						   WHERE PCT_CHAMADOS <='3' 
						   
						   UNION 
						   
							SELECT
					              NOM_FANTASI , 
						           TOTAL_GERAL, 
								     QTD_TOTAL, 
								  ROUND(PCT_CHAMADOS,2) PCT_CHAMADOS 
					     FROM ( 
						             SELECT em.NOM_FANTASI,
										(SELECT SUM(1) FROM sac_chamados CH2 WHERE DATE(ch2.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim') TOTAL_GERAL, 
										SUM(CASE WHEN DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_TOTAL,
										
										(SUM(CASE WHEN DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) *100) /(SELECT SUM(1) FROM sac_chamados CH2 WHERE ch2.dat_cadastr BETWEEN '$dat_ini' AND '$dat_fim') PCT_CHAMADOS 
							
							
								FROM sac_chamados ch 
								INNER JOIN sac_status st ON st.COD_STATUS=ch.COD_STATUS 
								INNER JOIN sac_tpsolicitacao tp ON tp.COD_TPSOLICITACAO=ch.COD_TPSOLICITACAO 
								INNER JOIN webtools.EMPRESAS em ON em.COD_EMPRESA=ch.COD_EMPRESA WHERE YEAR(ch.dat_cadastr) BETWEEN YEAR('$dat_ini') AND YEAR('$dat_fim') 
								GROUP BY ch.COD_EMPRESA 
								
								)tmpporcento 
								WHERE PCT_CHAMADOS >'3' 
								ORDER BY PCT_CHAMADOS DESC";

$arrayEmpresa = mysqli_query($connAdmSAC->connAdm(), $sqlEmpresa);

// fnEscreve($sqlEmpresa);

$arrEmp = array();
$arrPct = array();
$arrCor = array();

function getColor($num)
{
	$hash = md5('color' . $num); // modify 'color' to get a different palette
	return array(
		hexdec(substr($hash, 0, 2)), // r
		hexdec(substr($hash, 2, 2)), // g
		hexdec(substr($hash, 4, 2))
	); //b
}

while ($qrEmpresa = mysqli_fetch_assoc($arrayEmpresa)) {

	if ($qrEmpresa['QTD_TOTAL'] > 0) {

		array_push($arrEmp, $qrEmpresa['NOM_FANTASI']);
		array_push($arrPct, $qrEmpresa['PCT_CHAMADOS']);
		$cores = getColor(rand());
		array_push($arrCor, "rgba($cores[0],$cores[1],$cores[2],0.4)");

		$total_chamados = $qrEmpresa['TOTAL_GERAL'];
	}
}

// ==============================================================================

// ########################### TIPO ##########################################

$sqlTipo = "SELECT 
				               agrupador, 
									COD_EMPRESA,
								   DAT_CADASTRO, 
									TIMESTAMPDIFF(WEEK,'$dat_ini','$dat_fim') SEMANA, 
									TIMESTAMPDIFF(MONTH,'$dat_ini','$dat_fim') +1 MES, 
									SUM(QTD_CONCLUIDO) QTD_CONCLUIDO, 					
									SUM(QTD_TOTAL) QTD_TOTAL,					
									SUM(QTD_ABERTO) QTD_ABERTO,
									SUM(QTD_SUPORTE) QTD_SUPORTE,
									SUM(QTD_DEV) QTD_DEV, 
									SUM(QTD_DUVIDA) QTD_DUVIDA,
									SUM(QTD_IMPLATA) QTD_IMPLATA, 
									SUM(QTD_FALHA) QTD_FALHA, 
									SUM(QTD_MELHORIA) QTD_MELHORIA,
									SUM(QTD_COMUNICACAO) QTD_COMUNICACAO,
									SUM(QTD_OUTROS) QTD_OUTROS, 
									SUM(QTD_CONCLUIDO_ANO) QTD_CONCLUIDO_ANO, 
									SUM(QTD_ABERTO_ANO) QTD_ABERTO_ANO, 
									SUM(QTD_SUPORTE_ANO) QTD_SUPORTE_ANO, 
									SUM(QTD_DEV_ANO) QTD_DEV_ANO, 
									SUM(QTD_DUVIDA_ANO) QTD_DUVIDA_ANO, 
									SUM(QTD_IMPLATA_ANO) QTD_IMPLATA_ANO,
									SUM(QTD_FALHA_ANO) QTD_FALHA_ANO,
									SUM(QTD_MELHORIA_ANO) QTD_MELHORIA_ANO,
									SUM(QTD_COMUNICACAO_ANO) QTD_COMUNICACAO_ANO,
									SUM(QTD_OUTROS_ANO) QTD_OUTROS_ANO
				FROM (
							SELECT 
									1 agrupador, 
									ch.COD_EMPRESA,
									DATE(ch.DAT_CADASTR) DAT_CADASTRO, 
									TIMESTAMPDIFF(WEEK,'$dat_ini','$dat_fim') SEMANA, 
									TIMESTAMPDIFF(MONTH,'$dat_ini','$dat_fim') +1 MES, 
									SUM(CASE WHEN ch.COD_STATUS IN (6,10) AND DATE(dat_cadastr)BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_CONCLUIDO, 
									
									SUM(CASE WHEN DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_TOTAL,
									
									SUM(CASE WHEN ch.COD_STATUS NOT IN (6,10) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_ABERTO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (5) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_SUPORTE,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (3) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_DEV, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (1) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_DUVIDA,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (7) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_IMPLATA, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (6) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_FALHA, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (8) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_MELHORIA,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (24) AND DATE(ch.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_COMUNICACAO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO NOT IN (5,3,1,7,6,8,24) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' THEN '1' ELSE 0 END) QTD_OUTROS, 
									SUM(CASE WHEN ch.COD_STATUS IN (6,10) THEN '1' ELSE 0 END) QTD_CONCLUIDO_ANO, 
									SUM(CASE WHEN ch.COD_STATUS NOT IN (6,10) THEN '1' ELSE 0 END) QTD_ABERTO_ANO, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (5) THEN '1' ELSE 0 END) QTD_SUPORTE_ANO, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (3) THEN '1' ELSE 0 END) QTD_DEV_ANO, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (1) THEN '1' ELSE 0 END) QTD_DUVIDA_ANO, 
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (7) THEN '1' ELSE 0 END) QTD_IMPLATA_ANO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (6) THEN '1' ELSE 0 END) QTD_FALHA_ANO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (8) THEN '1' ELSE 0 END) QTD_MELHORIA_ANO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO IN (8) THEN '1' ELSE 0 END) QTD_COMUNICACAO_ANO,
									SUM(CASE WHEN ch.COD_TPSOLICITACAO NOT IN (5,3,1,7,6,8,24) THEN '1' ELSE 0 END) QTD_OUTROS_ANO
							FROM sac_chamados ch
							
							INNER JOIN sac_status st ON st.COD_STATUS=ch.COD_STATUS
							INNER JOIN sac_tpsolicitacao tp ON tp.COD_TPSOLICITACAO=ch.COD_TPSOLICITACAO
							WHERE YEAR(ch.dat_cadastr) BETWEEN YEAR('$dat_ini') AND YEAR('$dat_fim')
							GROUP BY ch.COD_STATUS

				) tmpstatus
				GROUP BY agrupador";

$arrayTipo = mysqli_query($connAdmSAC->connAdm(), $sqlTipo);
//fnEscreve($sqlTipo);

$qrTipo = mysqli_fetch_assoc($arrayTipo);

$arrTipo = array();
$arrCor2 = array();

for ($i = 0; $i < 9; $i++) {
	$cores2 = getColor($i);
	array_push($arrCor2, "rgba($cores2[0],$cores2[1],$cores2[2],0.4)");
}



$pct_suporte = ($qrTipo["QTD_SUPORTE"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_dev = ($qrTipo["QTD_DEV"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_duvida = ($qrTipo["QTD_DUVIDA"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_implata = ($qrTipo["QTD_IMPLATA"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_falha = ($qrTipo["QTD_FALHA"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_melhoria = ($qrTipo["QTD_MELHORIA"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_comunicacao = ($qrTipo["QTD_COMUNICACAO"] * 100) / $qrTipo["QTD_TOTAL"];
$pct_outros = ($qrTipo["QTD_OUTROS"] * 100) / $qrTipo["QTD_TOTAL"];

array_push($arrTipo, fnValorSql(fnValor($pct_suporte, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_dev, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_duvida, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_implata, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_falha, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_melhoria, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_comunicacao, 2)));
array_push($arrTipo, fnValorSql(fnValor($pct_outros, 2)));


// ########################### APENAS FINALIZADOS ##########################################

$sqlFechados = "SELECT SUM(QTD_FECHADOS) QTD_FECHADOS, DAT_INTERAC FROM (
						SELECT case when ch.COD_STATUS IN (6,10) then '1' ELSE 0 END QTD_FECHADOS,
						(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = ch.COD_CHAMADO) AS DAT_INTERAC
						FROM sac_chamados ch
					) tmpInterac
					WHERE date(DAT_INTERAC) between '$dat_ini' AND '$dat_fim'";

//  fnEscreve($sqlFechados);
$arrFechados = mysqli_query($connAdmSAC->connAdm(), $sqlFechados);

$qrClose = mysqli_fetch_assoc($arrFechados);

// ==============================================================================


ob_start();

function inicioFimSemana($data, $dia)
{
	$s = $data;
	$time = strtotime($s);
	$start = strtotime('last sunday, 12pm', $time);
	$end = strtotime('next saturday, 11:59am', $time);
	$format = 'Y-m-d';
	$start_day = date($format, $start);
	$end_day = date($format, $end);

	// Remover o header daqui, pois isso pode interferir na renderização
	return ($dia == "Saturday") ? $start_day : $end_day;
}

ob_end_flush(); // Envia a saída armazenada no buffer


$periodo = floor((strtotime($dat_fim) - strtotime($dat_ini)) / (24 * 60 * 60));

$arrPeriodo = array();
$domingo = "";
$sabado = "";


for ($i = 0; $i < $periodo; $i++) {

	$diaSemana = date('l', strtotime("$dat_ini +$i day"));

	if (in_array($diaSemana, ["Sunday", "Saturday"])) {

		$data = date('Y-m-d', strtotime("$dat_ini +$i day")) . "\n";

		if ($i == 0) {

			if ($diaSemana == "Saturday") {

				$domingo = inicioFimSemana($dat_ini, $diaSemana);
				$sabado = $data;
			} else {
				$sabado = inicioFimSemana($dat_ini, $diaSemana);
				$domingo = $data;
			}

			$arrPeriodo[] = array(0 => "$domingo", 1 => "$sabado");
		} else {

			if ($diaSemana == "Saturday") {

				$sabado = $data;
				$arrPeriodo[] = array(0 => "$domingo", 1 => "$sabado");
			} else {

				$domingo = $data;
			}
		}
	}
}



$ultimoElemento = end($arrPeriodo);
$ultimoValor = end($ultimoElemento);

if ($dat_fim > $ultimoValor) {
	$domingo = inicioFimSemana($dat_fim, "Saturday");
	$sabado = inicioFimSemana($dat_fim, "Sunday");
	$arrPeriodo[] = array(0 => "$domingo", 1 => "$sabado");
}

if ($arrPeriodo['0'][0] == "") {
	$arrPeriodo['0'][0] = inicioFimSemana($dat_ini, "Saturday");
}

$arrDayOneGeral = array();
$arrDayOne = array();
$arrLastDayGeral = array();
$arrLastDay = array();
$arrNumSemana = array();
$totLastDay = 0;

foreach ($arrPeriodo as $semana) {

	$arrSemanaPassada = array();
	$arrSemanaAtual = array();

	$sqlBalance = "SELECT 

					ifnull(SUM( case when A.DAT_CADASTR < '$semana[0]'then
						   1
						END),0)+(SELECT 
											COUNT(DISTINCT A.COD_CHAMADO)
												
											FROM sac_comentario A,sac_chamados B
											WHERE A.cod_status IN(6,10) AND 
											      A.COD_CHAMADO=B.COD_CHAMADO AND 
											      A.DAT_CADASTRO >='$semana[0]' AND 
											      B.DAT_CADASTR < '$semana[0]') SEMENA_ANTERIOR ,
											      
					ifnull(SUM( case when A.DAT_CADASTR < '$semana[0]'then
						   1
						END),0) +						      
					ifnull(SUM( case when A.DAT_CADASTR >= '$semana[0]' AND  A.DAT_CADASTR <= '$semana[1]'then
						   1
						END),0) SEMANA_ATUAL
						
						
					FROM sac_chamados A
					WHERE cod_status NOT IN(6,10) AND 
					      cod_tpsolicitacao NOT IN(21)";
	// fnEscreve($sqlBalance);
	$arrSemana = mysqli_query($connAdmSAC->connAdm(), $sqlBalance);

	$qrSemana = mysqli_fetch_assoc($arrSemana);


	array_push($arrDayOneGeral, $qrSemana['SEMENA_ANTERIOR']);
	array_push($arrLastDayGeral, $qrSemana['SEMANA_ATUAL']);
	array_push($arrNumSemana, "Semana " . fnDataShort($semana['0']) . " a " . fnDataShort($semana['1']));


	// $arrDayOneGeral[] = $arrSemanaPassada;
	// $arrLastDayGeral[] = $arrSemanaAtual;


}

$sqlCod = "SELECT COD_CHAMADO
			FROM sac_chamados ch
			WHERE date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim'";
//fnEscreve($sqlBalance);
$arrCod = mysqli_query($connAdmSAC->connAdm(), $sqlCod);

while ($qrCod = mysqli_fetch_assoc($arrCod)) {
	$cod_chamado_aberto .= $qrCod['COD_CHAMADO'] . ",";
}

$cod_chamado_aberto = rtrim($cod_chamado_aberto, ",");

// =================================================================================

$sqlCod2 = "SELECT COD_CHAMADO, DAT_INTERAC FROM (
					SELECT ch.COD_CHAMADO,
					(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = ch.COD_CHAMADO) AS DAT_INTERAC
					FROM sac_chamados ch
					where ch.COD_STATUS IN (6,10)
				) tmpInterac
				WHERE date(DAT_INTERAC) between '$dat_ini' AND '$dat_fim'";

$arrCod2 = mysqli_query($connAdmSAC->connAdm(), $sqlCod2);
//fnEscreve($sqlCod2);
while ($qrCod2 = mysqli_fetch_assoc($arrCod2)) {
	$cod_chamado_fechado .= $qrCod2['COD_CHAMADO'] . ",";
}

$cod_chamado_fechado = rtrim($cod_chamado_fechado, ",");

?>

<style>
	canvas {
		margin: 0 auto;
	}

	.shadow2 {
		padding-top: 6px;
		margin: unset !important;
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
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?>>
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

			<div class="push30"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row">

							<?php

							$sql = "SELECT 
											DATE(ch.DAT_CADASTR) DAT_CADASTRO,
											TIMESTAMPDIFF(WEEK,'$dat_ini','$dat_fim') SEMANA,
											TIMESTAMPDIFF(MONTH,'$dat_ini','$dat_fim') +1 MES ,
											sum(case when ch.COD_STATUS IN (6,10) AND  date(dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_CONCLUIDO,
											sum(case when date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_ABERTO,
											sum(case when ch.COD_TPSOLICITACAO IN (5) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_SUPORTE,
											sum(case when ch.COD_TPSOLICITACAO IN (3) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_DEV,
											sum(case when ch.COD_TPSOLICITACAO IN (1) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_DUVIDA,
											sum(case when ch.COD_TPSOLICITACAO IN (7) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_IMPLATA,
											sum(case when ch.COD_TPSOLICITACAO IN (6) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_FALHA,
											sum(case when ch.COD_TPSOLICITACAO IN (8) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_MELHORIA,
											sum(case when ch.COD_TPSOLICITACAO IN (24) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_COMUNICACAO,
											sum(case when ch.COD_TPSOLICITACAO NOT IN (5,3,1,7,6,8,24) AND  date(ch.dat_cadastr) between '$dat_ini' AND '$dat_fim' then '1' ELSE NULL END) QTD_OUTROS,
											sum(case when ch.COD_STATUS IN (6,10) then '1' ELSE NULL END) QTD_CONCLUIDO_ANO,
											sum(1) QTD_ABERTO_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (5) then '1' ELSE NULL END) QTD_SUPORTE_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (3) then '1' ELSE NULL END) QTD_DEV_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (1) then '1' ELSE NULL END) QTD_DUVIDA_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (7) then '1' ELSE NULL END) QTD_IMPLATA_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (6) then '1' ELSE NULL END) QTD_FALHA_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (8) then '1' ELSE NULL END) QTD_MELHORIA_ANO,
											sum(case when ch.COD_TPSOLICITACAO IN (24) then '1' ELSE NULL END) QTD_COMUNICACAO_ANO,
											sum(case when ch.COD_TPSOLICITACAO NOT IN (5,3,1,7,6,8,24) then '1' ELSE NULL END) QTD_OUTROS_ANO
										FROM sac_chamados ch
										INNER join sac_status st ON st.COD_STATUS=ch.COD_STATUS
										INNER JOIN sac_tpsolicitacao tp ON tp.COD_TPSOLICITACAO=ch.COD_TPSOLICITACAO
										WHERE YEAR(ch.dat_cadastr) between YEAR('$dat_ini') AND YEAR('$dat_fim')";

							// fnEscreve($sql);

							$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

							$qrTotal = mysqli_fetch_assoc($arrayQuery);

							?>

							<div class="col-md-3">

								<div class="shadow2">

									<h4 class="text-center">Total Período</h4>
									<div class="push20"></div>
									<div class="row">

										<div class="col-md-5 col-md-offset-1">

											<p>Chamados Abertos</p>
											<p>Chamados Fechados</p>
											<p>Somente Fechados</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= fnValor($qrTotal['QTD_ABERTO'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_CONCLUIDO'], 0) ?></p>
											<p><?= fnValor($qrClose['QTD_FECHADOS'], 0) ?></p>

										</div>

										<div class="push30"></div>

										<div class="col-md-5 col-md-offset-1">

											<p>Suporte</p>
											<p>Desenvolvimento</p>
											<p>Comunicação</p>
											<p>Dúvida</p>
											<p>Implantação</p>
											<p>Falha de sistema</p>
											<p>Melhoria</p>
											<p>Outros</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= fnValor($qrTotal['QTD_SUPORTE'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_DEV'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_COMUNICACAO'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_DUVIDA'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_IMPLATA'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_FALHA'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_MELHORIA'], 0) ?></p>
											<p><?= fnValor($qrTotal['QTD_OUTROS'], 0) ?></p>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3">

								<div class="shadow2">

									<h4 class="text-center">Semanal</h4>
									<div class="push20"></div>
									<div class="row">

										<div class="col-md-5 col-md-offset-1">

											<p>Chamados Abertos</p>
											<p>Chamados Fechados</p>
											<p>&nbsp;</p>

										</div>

										<div class="col-md-5 text-right">
											<?php
											if ($qrTotal['QTD_ABERTO'] != 0 && $qrTotal['SEMANA'] != 0) {
												$tot_abertos = fnValor(($qrTotal['QTD_ABERTO'] / $qrTotal['SEMANA']), 1);
											} else {
												$tot_abertos = '0,0';
											}

											if ($qrTotal['QTD_CONCLUIDO'] != 0 && $qrTotal['SEMANA'] != 0) {
												$tot_concluidos = fnValor(($qrTotal['QTD_CONCLUIDO'] / $qrTotal['SEMANA']), 1);
											} else {
												$tot_concluidos = '0,0';
											}
											?>

											<p><?= $tot_abertos; ?></p>
											<p><?= $tot_concluidos; ?></p>
											<p>&nbsp;</p>

										</div>

										<div class="push30"></div>

										<div class="col-md-5 col-md-offset-1">

											<p>Suporte</p>
											<p>Desenvolvimento</p>
											<p>Comunicação</p>
											<p>Dúvida</p>
											<p>Implantação</p>
											<p>Falha de sistema</p>
											<p>Melhoria</p>
											<p>Outros</p>

										</div>

										<div class="col-md-5 text-right">

											<?php
											if ($qrTotal['QTD_SUPORTE'] != 0 && $qrTotal['SEMANA'] != 0) {
												$suporte = fnValor(($qrTotal['QTD_SUPORTE'] / $qrTotal['SEMANA']), 1);
											} else {
												$suporte = '0,0';
											}
											if ($qrTotal['QTD_DEV'] != 0 && $qrTotal['SEMANA'] != 0) {
												$desenvolvimento = fnValor(($qrTotal['QTD_DEV'] / $qrTotal['SEMANA']), 1);
											} else {
												$desenvolvimento = '0,0';
											}
											if ($qrTotal['QTD_DUVIDA'] != 0 && $qrTotal['SEMANA'] != 0) {
												$duvida = fnValor(($qrTotal['QTD_DUVIDA'] / $qrTotal['SEMANA']), 1);
											} else {
												$duvida = '0,0';
											}
											if ($qrTotal['QTD_IMPLATA'] != 0 && $qrTotal['SEMANA'] != 0) {
												$implantacao = fnValor(($qrTotal['QTD_IMPLATA'] / $qrTotal['SEMANA']), 1);
											} else {
												$implantacao = '0,0';
											}
											if ($qrTotal['QTD_FALHA'] != 0 && $qrTotal['SEMANA'] != 0) {
												$falhas = fnValor(($qrTotal['QTD_FALHA'] / $qrTotal['SEMANA']), 1);
											} else {
												$falhas = '0,0';
											}
											if ($qrTotal['QTD_MELHORIA'] != 0 && $qrTotal['SEMANA'] != 0) {
												$melhoria = fnValor(($qrTotal['QTD_MELHORIA'] / $qrTotal['SEMANA']), 1);
											} else {
												$melhoria = '0,0';
											}
											if ($qrTotal['QTD_COMUNICACAO'] != 0 && $qrTotal['SEMANA'] != 0) {
												$comunicacao = fnValor(($qrTotal['QTD_COMUNICACAO'] / $qrTotal['SEMANA']), 1);
											} else {
												$comunicacao = '0,0';
											}
											if ($qrTotal['QTD_OUTROS'] != 0 && $qrTotal['SEMANA'] != 0) {
												$outros = fnValor(($qrTotal['QTD_OUTROS'] / $qrTotal['SEMANA']), 1);
											} else {
												$outros = '0,0';
											}


											?>

											<p><?= $suporte ?></p>
											<p><?= $desenvolvimento ?></p>
											<p><?= $comunicacao ?></p>
											<p><?= $duvida ?></p>
											<p><?= $implantacao ?></p>
											<p><?= $falhas ?></p>
											<p><?= $melhoria ?></p>
											<p><?= $outros ?></p>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3">

								<div class="shadow2">

									<h4 class="text-center">Mensal</h4>
									<div class="push20"></div>
									<div class="row">

										<div class="col-md-5 col-md-offset-1">

											<p>Chamados Abertos</p>
											<p>Chamados Fechados</p>
											<p>&nbsp;</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= fnValor(($qrTotal['QTD_ABERTO'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_CONCLUIDO'] / $qrTotal['MES']), 0) ?></p>
											<p>&nbsp;</p>

										</div>

										<div class="push30"></div>

										<div class="col-md-5 col-md-offset-1">

											<p>Suporte</p>
											<p>Desenvolvimento</p>
											<p>Comunicação</p>
											<p>Dúvida</p>
											<p>Implantação</p>
											<p>Falha de sistema</p>
											<p>Melhoria</p>
											<p>Outros</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= fnValor(($qrTotal['QTD_SUPORTE'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_DEV'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_COMUNICACAO'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_DUVIDA'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_IMPLATA'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_FALHA'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_MELHORIA'] / $qrTotal['MES']), 0) ?></p>
											<p><?= fnValor(($qrTotal['QTD_OUTROS'] / $qrTotal['MES']), 0) ?></p>

										</div>

									</div>

								</div>

							</div>

							<div class="col-md-3">

								<div class="shadow2">

									<h4 class="text-center">Anual</h4>
									<div class="push20"></div>
									<div class="row">

										<div class="col-md-5 col-md-offset-1">

											<p>Chamados Abertos</p>
											<p>Chamados Fechados</p>
											<p>&nbsp;</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= $qrTotal['QTD_ABERTO_ANO'] ?></p>
											<p><?= $qrTotal['QTD_CONCLUIDO_ANO'] ?></p>
											<p>&nbsp;</p>

										</div>

										<div class="push30"></div>

										<div class="col-md-5 col-md-offset-1">

											<p>Suporte</p>
											<p>Desenvolvimento</p>
											<p>Comunicação</p>
											<p>Dúvida</p>
											<p>Implantação</p>
											<p>Falha de sistema</p>
											<p>Melhoria</p>
											<p>Outros</p>

										</div>

										<div class="col-md-5 text-right">

											<p><?= $qrTotal['QTD_SUPORTE_ANO'] ?></p>
											<p><?= $qrTotal['QTD_DEV_ANO'] ?></p>
											<p><?= $qrTotal['QTD_COMUNICACAO_ANO'] ?></p>
											<p><?= $qrTotal['QTD_DUVIDA_ANO'] ?></p>
											<p><?= $qrTotal['QTD_IMPLATA_ANO'] ?></p>
											<p><?= $qrTotal['QTD_FALHA_ANO'] ?></p>
											<p><?= $qrTotal['QTD_MELHORIA_ANO'] ?></p>
											<p><?= $qrTotal['QTD_OUTROS_ANO'] ?></p>

										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="push20"></div>
						<td class="text-left">
							<small>
								<div class="btn-group dropdown left">
									<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
										&nbsp; Exportar &nbsp;
										<span class="fas fa-caret-down"></span>
									</button>
									<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
										<li><a class="btn btn-sm exportarCSV" style="text-align: left" onclick="exportarCSV(this)" value="N">&nbsp; Exportar</a></li>
										<li><a class="btn btn-sm exportarCSV2" style="text-align: left" onclick="exportarCSV(this)" value="S">&nbsp; Exportar (Somente Fechados)</a></li>
										<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
									</ul>
								</div>
							</small>
						</td>

					</div>

				</div>

			</div>

			<div class="push30"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-6 col-xs-12">

								<div class="shadow2">

									<h4>Percentual por Empresa</h4>
									<div class="push20"></div>

									<div style="position: relative; width:100%">
										<canvas id="pieChart"></canvas>
									</div>

								</div>

							</div>

							<div class="form-group text-center col-lg-6 col-xs-12">

								<div class="shadow2">

									<h4>Percentual por Solicitação</h4>
									<div class="push20"></div>

									<div style="position: relative; width:100%">
										<canvas id="pieChart2"></canvas>
									</div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

			<div class="push30"></div>

			<!-- <div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

							<div class="row text-center">
			
												
								<div class="form-group text-center col-lg-12">
									
									<h4>Evolução do Engajamento Mensal</h4>
									
									<div class="push20"></div>
									
									
									<div style="height: 300px; width:100%;">
										<canvas id="lineChart2"></canvas>			
									</div>

								</div>					
								
							</div>			
						</div>
					</div>
				</div>

				<div class="push30"></div> -->

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-12">

								<div class="shadow2">

									<h4>Task Balance</h4>
									<div class="push20"></div>

									<div style="height: 300px; width:100%;">
										<canvas id="bar-chart-grouped"></canvas>
									</div>

								</div>

							</div>



						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="COD_CHAMADO_ABERTO" id="COD_CHAMADO_ABERTO" value="<?= fnEncode($cod_chamado_aberto) ?>">
						<input type="hidden" name="COD_CHAMADO_FECHADO" id="COD_CHAMADO_FECHADO" value="<?= fnEncode($cod_chamado_fechado) ?>">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

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

	//graficos
	$(document).ready(function() {


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
				labels: <?= json_encode($arrNumSemana, true) ?>,
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#b64645',
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
					label: "Início",
					borderColor: '#b64645',
					backgroundColor: 'rgba(182,70,69,0.4)',
					borderWidth: 1,
					data: <?= json_encode($arrDayOneGeral, true) ?>
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#18BC9C',
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
					label: "Fim",
					borderColor: '#18BC9C',
					backgroundColor: 'rgba(24,188,156,0.4)',
					borderWidth: 1,
					data: <?= json_encode($arrLastDayGeral, true) ?>
				}, ]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				maintainAspectRatio: false,
				title: {
					// display: true,
					// text: 'TMs e GMs Fidelizados [CR e SR] vs Avulsos'
				},
				tooltips: {
					callbacks: {
						label: function(t, d) {
							return t.yLabel
						}
					}
				},
				scales: {
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
						}
					}]
				},

			}
		});



		var data = {
			labels: <?= json_encode($arrEmp, true) ?>,
			datasets: [{

				backgroundColor: <?= json_encode($arrCor, true) ?>,
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: <?= json_encode($arrCor, true) ?>,
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value + '%';
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				data: <?= json_encode($arrPct, true) ?>,
				// Notice the borderColor 
				// borderColor: ['black', 'black'],
				borderWidth: [1, 1]
			}]
		};

		// Notice the rotation from the documentation.


		// Chart declaration:
		var mypieChart = new Chart(document.getElementById("pieChart").getContext('2d'), {
			type: 'pie',
			data: data,
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']] + '%';
						},
						// afterLabel: function(tooltipItem, data) {
						//   var dataset = data['datasets'][0];
						//   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
						//   return '(' + percent + '%)';
						// }
					}
				},
				rotation: -0.3 * Math.PI,
				title: {
					display: true,
					position: 'bottom',
					text: "<?= fnValor($total_chamados, 0) ?> Chamados no período"
					// , "Limpo: R$<?= fnValor($listaFatLmp, 2) ?>" , "Avulso: R$<?= fnValor($listaFatAv, 2) ?>"
				},
			}
		});

		var data = {
			labels: ["Suporte", "DEV", "Dúvida", "Implantação", "Falha", "Melhoria", "Comunicação", "Outros"],
			datasets: [{

				backgroundColor: <?= json_encode($arrCor2, true) ?>,
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: <?= json_encode($arrCor2, true) ?>,
						color: '#fff',
						formatter: function(value) {
							if (parseInt(value) >= 1000) {
								return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							} else {
								return value + '%';
							}
							// eq. return ['line1', 'line2', value]
						}
					},
				<?php } ?>
				data: <?= json_encode($arrTipo, true) ?>,
				// Notice the borderColor 
				// borderColor: ['black', 'black'],
				borderWidth: [1, 1]
			}]
		};

		// Notice the rotation from the documentation.


		// Chart declaration:
		var mypieChart2 = new Chart(document.getElementById("pieChart2").getContext('2d'), {
			type: 'pie',
			data: data,
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItem, data) {
							return data['labels'][tooltipItem[0]['index']];
						},
						label: function(tooltipItem, data) {
							return data['datasets'][0]['data'][tooltipItem['index']] + '%';
						},
						// afterLabel: function(tooltipItem, data) {
						//   var dataset = data['datasets'][0];
						//   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
						//   return '(' + percent + '%)';
						// }
					}
				},
				rotation: -0.3 * Math.PI,
				title: {
					display: true,
					position: 'bottom',
					text: " "
					// , "Limpo: R$<?= fnValor($listaFatLmp, 2) ?>" , "Avulso: R$<?= fnValor($listaFatAv, 2) ?>"
				},
			}
		});

		// Line chart 2
		// var ctx = document.getElementById("lineChart2");
		// var lineChart = new Chart(ctx, {
		// type: 'line',
		// data: {
		//   labels: ["teste 1","teste 2",],
		//   datasets: [{
		//   	<?php if ($log_labels == 'S') { ?>
		//   	datalabels: {
		// 		clamp: true,
		// 		align: 'start',
		// 		anchor: 'start',
		// 		borderRadius: 6,
		// 		backgroundColor: '#36A2EB',
		// 		color: '#fff',
		// 		formatter: function(value) {
		// 		    if(parseInt(value) >= 1000){
		//                 return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		//               } else {
		//                 return value;
		//               }
		// 		    // eq. return ['line1', 'line2', value]
		// 		}
		// 	},
		// 	<?php } ?>
		// 	label: "Total de Clientes Com Compras no Mês",
		// 	backgroundColor: "rgba(3, 88, 106, 0)",
		// 	borderColor: "#36A2EB",
		// 	pointBorderColor: "#36A2EB",
		// 	pointBackgroundColor: "#fff",
		// 	pointHoverBackgroundColor: "#fff",
		// 	pointRadius: 4,
		// 		pointBorderWidth: 3,
		// 	data: [99,99]
		//   }, {
		//   	<?php if ($log_labels == 'S') { ?>
		//   	datalabels: {
		// 		clamp: true,
		// 		align: 'start',
		// 		anchor: 'end',
		// 		borderRadius: 6,
		// 		backgroundColor: '#4BC0C0',
		// 		color: '#fff',
		// 		formatter: function(value) {
		// 		    if(parseInt(value) >= 1000){
		//                 return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		//               } else {
		//                 return value;
		//               }
		// 		    // eq. return ['line1', 'line2', value]
		// 		}
		// 	},
		// 	<?php } ?>
		// 	label: "Clientes Cadastrados na Base",
		// 	backgroundColor: "rgba(3, 88, 106, 0)",
		// 	borderColor: "#4BC0C0",
		// 	pointBorderColor: "#4BC0C0",
		// 	pointBackgroundColor: "#fff",
		// 	pointRadius: 4,
		// 		pointBorderWidth: 3,
		// 	data: [45,45]
		//   }]
		// },
		// // plugins: [ChartDataLabels],
		// options: {
		// 	legend: {
		// 				display: true,
		// 				position: 'bottom'
		// 			},					
		// 	maintainAspectRatio: false,
		// 	animation: {
		// 		duration: 2000,
		// 	},
		// 	scales: {
		// 		yAxes: [{
		// 			ticks: {
		// 				beginAtZero: true,
		// 				callback: function(value, index, values) {
		// 		              if(parseInt(value) >= 1000){
		// 		                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 		              } else {
		// 		                return value;
		// 		              }
		// 		            }
		// 			},
		// 			afterTickToLabelConversion : function(object){
		// 				for(var tick in object.ticks){
		// 					object.ticks[tick];
		// 				}
		// 			}							
		// 		}],
		// 	},
		// 	tooltips: {
		// 	     callbacks: {
		// 			    label: function (t, d) {
		// 			        if (parseInt(t.yLabel)>=1000) {
		// 			            return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		// 			        } else{
		// 			            return t.yLabel;
		// 			        }
		// 			    }
		// 			}
		// 	    },
		// }

		// });	

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
					action: function() {
						var nome = this.$content.find('.nome').val();
						if (!nome) {
							$.alert('Por favor, insira um nome');
							return false;
						}

						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxRelSuporte.do?opcao=exportar&tipo=opn&nomeRel=" + nome,
									data: $('#formulario').serialize(),
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '0_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function() {
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
								});
							},
							buttons: {
								fechar: function() {
									//close
								}
							}
						});
					}
				},
				cancelar: function() {
					//close
				},
			}
		});
	});

	$(".exportarCSV2").click(function() {
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
					action: function() {
						var nome = this.$content.find('.nome').val();
						if (!nome) {
							$.alert('Por favor, insira um nome');
							return false;
						}

						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxRelSuporte.do?opcao=exportar&tipo=cls&nomeRel=" + nome,
									data: $('#formulario').serialize(),
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '0_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function() {
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
								});
							},
							buttons: {
								fechar: function() {
									//close
								}
							}
						});
					}
				},
				cancelar: function() {
					//close
				},
			}
		});
	});
</script>