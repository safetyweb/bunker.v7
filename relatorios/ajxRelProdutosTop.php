<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
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

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

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

function encodeCsv($valor)
{
	return mb_convert_encoding($valor, 'ISO-8859-1', 'UTF-8');
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

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		if ($log_detalhes == "N") {

			$CABECHALHO = ['Produto', 'Cod', 'Cod. Ext', 'Qtd. Produto', 'Qtd. Fidel', '% Qtd. Fidel', 'Tot. Vendas', 'Tot. Vendas Fidel', '% Vendas', 'VM. Total', 'VM. Fidel', 'Clientes Fidel', 'Tot. Cli. Personas', '% Cli. Personas', 'Tot. Produtos', 'Qtd. Vendas', 'QTD_FIDELIZADO_TOTAL', 'VAL_FIDELIZA_TOTAL', 'QTD_VENDA_RESGATE', 'VAL_RESGATE'];

			fputcsv($arquivo, $CABECHALHO, ';', '"');

			while ($row = mysqli_fetch_assoc($arrayQuery)) {

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

				$linhaCSV = [
					encodeCsv($row['V_ID_SESSION']),
					encodeCsv($row['DES_PRODUTO']),
					encodeCsv($row['COD_PRODUTO']),
					encodeCsv($row['COD_EXTERNO']),
					encodeCsv($row['TOTAL_QTD_PROD']),
					encodeCsv($row['TOTAL_QTD_FIDE']),
					encodeCsv($row['PERC_QTDFIDELIZADO']),
					encodeCsv($row['TOT_VENDAS']),
					encodeCsv($row['TOT_VENDAS_FIDEL']),
					encodeCsv($row['PERC_VOLUME']),
					encodeCsv($row['VPM_GERAL']),
					encodeCsv($row['VPM_FIDE']),
					encodeCsv($row['TOTAL_CLIENTES_FIDEL']),
					encodeCsv($row['TOTAL_CLI_PERSONAS']),
					encodeCsv($row['PERC_CLI_PERSONAS']),
					encodeCsv($row['QTD_PRODUTO_TOTAL']),
					encodeCsv($row['QTD_VENDA']),
					encodeCsv($row['QTD_FIDELIZADO_TOTAL']),
					encodeCsv($row['VAL_FIDELIZA_TOTAL']),
					encodeCsv($row['QTD_VENDA_RESGATE']),
					encodeCsv($row['VAL_RESGATE'])
				];



				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				// $array = array_map("utf8_decode", $row);
				// fputcsv($arquivo, $array, ';', '"');
				fputcsv($arquivo, $linhaCSV, ';', '"');
			}
		} else {

			while ($headers = mysqli_fetch_field($arrayQuery)) {
				$CABECALHODETALHES[] = $headers->name;
			}

			fputcsv($arquivo, $CABECALHODETALHES, ';', '"');

			while ($row = mysqli_fetch_assoc($arrayQuery)) {

				$row['VOLUME_VENDA'] = fnValor($row['VOLUME_VENDA'], 2);

				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"');


				//echo "<pre>";
				//print_r($row);
				//echo "</pre>";
			}
		}

		fclose($arquivo);
		/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					// Colunas que são double converte com fnValor
					 
					if ($log_detalhes == "N"){	
						//retorno simples
						if($cont == 5 || $cont == 8 || $cont == 13){
							array_push($newRow, fnValor($objeto, 2).'%');
						}else if($cont == 6 || $cont == 7 || $cont == 9 || $cont == 10){
							array_push($newRow, 'R$ '.fnValor($objeto, 2));
						}else{
							array_push($newRow, $objeto);
						}
					}else{

						if($cont == 4){
							array_push($newRow, 'R$ '.fnValor($objeto, 2));
						}else if($cont == 3){
							array_push($newRow, fnValor($objeto, 0));
						}else{
							//retorno completo
							array_push($newRow, $objeto);
						}
						
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			if ($log_detalhes == "N"){
				array_push($arrayColumnsNames, "Produto");
				array_push($arrayColumnsNames, "Cód.");
				array_push($arrayColumnsNames, "Cód. Ext.");
				array_push($arrayColumnsNames, "Qtd. Produto");
				array_push($arrayColumnsNames, "Qtd. Fidel.");
				array_push($arrayColumnsNames, "% Qtd. Fidel.");
				array_push($arrayColumnsNames, "Tot. Vendas");
				array_push($arrayColumnsNames, "Tot. Vendas Fidel.");
				array_push($arrayColumnsNames, "% Vendas");
				array_push($arrayColumnsNames, "VM. Total");
				array_push($arrayColumnsNames, "VM. Fidel.");
				array_push($arrayColumnsNames, "Clientes Fidel.");
				array_push($arrayColumnsNames, "Tot. Cli. Personas");
				array_push($arrayColumnsNames, "% Cli. Personas");
				array_push($arrayColumnsNames, "Tot. Produtos");
				array_push($arrayColumnsNames, "Qtd. Vendas");
			}else{
				while($row = mysqli_fetch_field($arrayQuery))
				{
					array_push($arrayColumnsNames, $row->name);
				}
			}		

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
*/
		break;
	case 'paginar':

		break;
}
