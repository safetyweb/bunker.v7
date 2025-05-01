<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$opcao = @$_GET['opcao'];
// $itens_por_pagina = $_GET['itens_por_pagina'];	
// $pagina = $_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$cod_univend = fnDecode(@$_GET['idU']);

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);

$lojasSelecionadas = fnLimpaCampo($_REQUEST['LOJAS']);


//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

$nomeRel = $_GET['nomeRel'];
$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

$writer = WriterFactory::create(Type::CSV);
$writer->setFieldDelimiter(';');
$writer->openToFile($arquivo);


switch ($opcao) {

	case 'imi_loja':

		$sql = "CALL SP_IMIGRA_EXPORT_LOJA ( '$dat_ini' , '$dat_fim' , $cod_univend , $cod_empresa )";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 7 || $cont == 15) {
					array_push($newRow, fnValor($objeto, 2) . "%");
				} else if ($cont == 8 || $cont == 13 || $cont == 14) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {

			array_push($arrayColumnsNames, $row->name);
		}

		break;

	case 'emi_loja':

		$sql = "CALL SP_EMIGRA_EXPORT_LOJA ( '$dat_ini' , '$dat_fim' , $cod_univend , $cod_empresa )";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 4 || $cont == 13) {
					array_push($newRow, fnValor($objeto, 2) . "%");
				} else if ($cont == 5 || $cont == 7 || $cont == 11 || $cont == 12) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {

			array_push($arrayColumnsNames, $row->name);
		}

		break;

	case 'imi_cliente':

		$sql = "CALL SP_IMIGRA_EXPORT_CLIENTES ( '$dat_ini' , '$dat_fim' , $cod_univend , $cod_empresa )";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 16) {
					array_push($newRow, fnValor($objeto, 2) . "%");
				} else if ($cont == 8 || $cont == 10 || $cont == 14 || $cont == 15) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {

			array_push($arrayColumnsNames, $row->name);
		}

		break;


	case 'emi_cliente':

		$sql = "CALL SP_EMIGRA_EXPORT_CLIENTES ( '$dat_ini' , '$dat_fim' , $cod_univend , $cod_empresa )";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 15 || $cont == 16) {
					array_push($newRow, fnValor($objeto, 2) . "%");
				} else if ($cont == 7 || $cont == 9 || $cont == 13 || $cont == 14) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {

			array_push($arrayColumnsNames, $row->name);
		}

		break;

	default:

		$sql = "CALL SP_RELAT_MOVIMENTACAO_CLIENTE ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas', $cod_empresa)";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 4) {
					array_push($newRow, fnValor($objeto, 2) . "%");
				} else if ($cont == 5 || $cont == 7 || $cont == 8) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {

			array_push($arrayColumnsNames, $row->name);
		}

		break;
}

$writer->addRow($arrayColumnsNames);
$writer->addRows($array);

$writer->close();
