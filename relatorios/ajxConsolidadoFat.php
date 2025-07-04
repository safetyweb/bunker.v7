<?php

include '../_system/_functionsMain.php';
echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);

$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
$dat_ini = "01/" . $_REQUEST['DAT_INI'];
$dat_fim = $_REQUEST['DAT_FIM'];
$lojasSelecionadas = $_POST['LOJAS'];
$CABECHALHO = [];

$array_dat_fim  = explode("/", $dat_fim);

$dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1]) . "/" . $dat_fim;

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		//============================

		$sql = "CALL REL_CONSOLIDADO_FATURAMENTO ( '" . fnmesanosql($dat_ini) . "' , '" . fnmesanosql($dat_fim) . "' , '$lojasSelecionadas', $cod_empresa)";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VALOR_TOTAL_VENDA'] = fnValor($row['VALOR_TOTAL_VENDA'], 2);
			$row['VALOR_TOTAL_VENDA_FIDELIZADO'] = fnValor($row['VALOR_TOTAL_VENDA_FIDELIZADO'], 2);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"', '\n');
		}
		fclose($arquivo);
		/*
			$array = array();

			while($row = mysqli_fetch_assoc($arrayQuery)){

				$newRow = array();
				  
				$cont = 0;
				foreach ($row as $objeto) {

					if($cont == 4 || $cont == 5){
						array_push($newRow, "R$ ".fnValor($objeto,2));
					}else if($cont >= 6 && $cont <= 8){
						array_push($newRow, fnValor($objeto,0));
					}else{
						array_push($newRow, $objeto);
					}
					
					$cont++;

				}

				$array[] = $newRow;

			echo "<pre>";
			print_r($row);
			echo "</pre>";

			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			*/
		break;

	case 'paginar':


		break;
}
