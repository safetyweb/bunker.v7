<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

// echo fnDebug('true');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);

$cod_univend = $_POST['COD_UNIVEND'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$lojasSelecionadas = $_POST['LOJAS'];

// fnEscreve($lojasSelecionadas);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		$hor_ini = " 00:00:00";
		$hor_fim = " 23:59:59";

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "select A.COD_UNIVEND,
					A.NOM_FANTASI,
					COUNT(COD_VENDA) TOTAL_VENDAS,
					SUM(CASE WHEN C.LOG_AVULSO='N' THEN
					1
					ELSE
					0
					END) VENDAS_CLIENTES,
					SUM(CASE WHEN C.LOG_AVULSO='S' THEN
					1
					ELSE
					0
					END) VENDAS_AVULSA,
				   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' ) TOT_CLIENTE,
				   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=1) TOT_MASCULINO,
				   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=2) TOT_FEMININO,
				   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=3) TOT_INDEFINIDO
			
			from unidadevenda A
			LEFT   JOIN VENDAS B ON B.COD_UNIVEND=A.COD_UNIVEND AND B.DAT_CADASTR_WS between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND B.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9)
			LEFT   JOIN CLIENTES C ON B.COD_CLIENTE=C.COD_CLIENTE
			WHERE A.COD_EMPRESA = $cod_empresa
			AND A.COD_UNIVEND IN($lojasSelecionadas)
			AND A.cod_exclusa = 0
			GROUP BY A.COD_UNIVEND
			ORDER by A.NOM_FANTASI; ";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

		$array = array();

		$total_vendas = 0;
		$vendas_clientes = 0;
		$vendas_avulsa = 0;
		$tot_cliente = 0;
		$tot_masculino = 0;
		$tot_feminino = 0;
		$tot_indefinido = 0;

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$array[] = $row;
			$total_vendas += $row['TOTAL_VENDAS'];
			$vendas_clientes += $row['VENDAS_CLIENTES'];
			$vendas_avulsa += $row['VENDAS_AVULSA'];
			$tot_cliente += $row['TOT_CLIENTE'];
			$tot_masculino += $row['TOT_MASCULINO'];
			$tot_feminino += $row['TOT_FEMININO'];
			$tot_indefinido += $row['TOT_INDEFINIDO'];
		}

		/* ADICIONANDO TOTALIZADOR AO RELATÓRIO */
		array_push($array, array(
			'COD_UNIVEND' => "",
			"NOM_FANTASI" => "Total",
			'TOTAL_VENDAS'  => $total_vendas,
			'VENDAS_CLIENTES' => $vendas_clientes,
			'VENDAS_AVULSA' => $vendas_avulsa,
			'TOT_CLIENTE' => $tot_cliente,
			'TOT_MASCULINO' => $tot_masculino,
			'TOT_FEMININO' => $tot_feminino,
			'TOT_INDEFINIDO' => $tot_indefinido
		));

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'paginar':

		break;
}
