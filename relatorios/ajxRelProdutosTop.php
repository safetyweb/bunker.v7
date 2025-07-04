<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$cod_persona = "";
$qtd_produto = 0;
$dias30 = "";
$hoje = "";
$temUnivend = "";
$log_detalhes = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$headers = "";
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$writer = "";
$CABECHALHO = [];

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$cod_persona = fnLimpaCampoZero(@$_POST['COD_PERSONA']);

$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}


if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		$log_detalhes = @$_GET['log_detalhes'];
		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


		$sql = "CALL SP_RELAT_TOPPRODUTOS(
				" . $cod_empresa . ",
				'" . fnDataSql($dat_ini) . "',
				'" . fnDataSql($dat_fim) . "',
				'" . $lojasSelecionadas . "',
				" . $qtd_produto . ",
				'" . $log_detalhes . "',
				" . $cod_persona . "															
				) ";


		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		if ($log_detalhes == "N") {

			$CABECHALHO = ['Produto', 'Cod', 'Cod. Ext', 'Qtd. Produto', 'Qtd. Fidel', '% Qtd. Fidel', 'Tot. Vendas', 'Tot. Vendas Fidel', '% Vendas', 'VM. Total', 'VM. Fidel', 'Clientes Fidel', 'Tot. Cli. Personas', '% Cli. Personas', 'Tot. Produtos', 'Qtd. Vendas', 'QTD_FIDELIZADO_TOTAL', 'VAL_FIDELIZA_TOTAL', 'QTD_VENDA_RESGATE', 'VAL_RESGATE'];

			fputcsv($arquivo, $CABECHALHO, ';', '"');

			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				fnEscreveArray($row);
				$arrayLinhas = [];

				$row['TOT_VENDAS'] = fnValor($row['TOT_VENDAS'], 2);
				$row['TOT_VENDAS_FIDEL'] = fnValor($row['TOT_VENDAS_FIDEL'], 2);
				$row['VPM_GERAL'] = fnValor($row['VPM_GERAL'], 2);
				$row['VPM_FIDE'] = fnValor($row['VPM_FIDE'], 2);
				$row['TOTAL_CLI_PERSONAS'] = $row['TOTAL_CLI_PERSONAS'];
				$row['VAL_FIDELIZA_TOTAL'] = fnValor($row['VAL_FIDELIZA_TOTAL'], 2);
				$row['PERC_CLI_PERSONAS'] = $row['PERC_CLI_PERSONAS'];
				$row['PERC_VOLUME'] = fnvalor($row['PERC_VOLUME'], 2) . '%';
				$row['PERC_QTDFIDELIZADO'] = fnvalor($row['PERC_QTDFIDELIZADO'], 2) . '%';
				$row['PERC_CLI_PERSONAS'] = fnvalor($row['PERC_CLI_PERSONAS'], 2) . '%';
				$row['QTD_VENDA_RESGATE'] = fnValor($row['QTD_VENDA_RESGATE'], 0);
				$row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);

				$arrayLinhas = [
					$row['DES_PRODUTO'],
					$row['COD_PRODUTO'],
					$row['COD_EXTERNO'],
					$row['TOTAL_QTD_PROD'],
					$row['TOTAL_QTD_FIDE'],
					$row['PERC_QTDFIDELIZADO'],
					$row['TOT_VENDAS'],
					$row['TOT_VENDAS_FIDEL'],
					$row['PERC_VOLUME'],
					$row['VPM_GERAL'],
					$row['VPM_FIDE'],
					$row['TOTAL_CLIENTES_FIDEL'],
					$row['TOTAL_CLI_PERSONAS'],
					$row['PERC_CLI_PERSONAS'],
					$row['QTD_PRODUTO_TOTAL'],
					$row['QTD_VENDA'],
					$row['QTD_FIDELIZADO_TOTAL'],
					$row['VAL_FIDELIZA_TOTAL'],
					$row['QTD_VENDA_RESGATE'],
					$row['VAL_RESGATE'],
				];


				$array = array_map("utf8_decode", $arrayLinhas);
				fputcsv($arquivo, $array, ';', '"');
			}
		} else {

			while ($headers = mysqli_fetch_field($arrayQuery)) {
				$CABECALHODETALHES[] = $headers->name;
			}

			fputcsv($arquivo, $CABECALHODETALHES, ';', '"');

			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				$row['VOLUME_VENDA'] = fnValor($row['VOLUME_VENDA'], 2);

				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"');
			}
		}

		fclose($arquivo);

		break;
	case 'paginar':

		break;
}
