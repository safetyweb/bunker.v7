<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$vendasTotal = "";
$vendasAvulsas = "";
$row = "";
$arrayColumnsNames = [];




$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

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

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "select 
						uni.COD_UNIVEND, 
						uni.NOM_FANTASI, 
						Sum(Case When ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 Else 0 end) as VENDA_TOTAL,
					  
						(0) TOTAL_CLIENTE,
				   
						count(distinct case when ven.COD_UNIVEND = uni.COD_UNIVEND and cli.LOG_AVULSO='N'  Then  cli.COD_CLIENTE  else 0 end) as CLIENTES_COMPRA,          
					
						sum(case when cli.LOG_AVULSO = 'S' and ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 else 0 end) as AVULSO
																				
				  
					from webtools.unidadevenda uni
					Inner join vendas ven
							on ven.COD_EMPRESA = uni.COD_EMPRESA
						   and ven.COD_UNIVEND = uni.COD_UNIVEND
						   and ven.DAT_CADASTR_WS >= '$dat_ini  00:00' 
						   and ven.DAT_CADASTR_WS <= '$dat_fim  23:59'        
						   AND ven.DAT_CADASTR < NOW()  
					Inner join clientes cli 
							on cli.COD_CLIENTE = ven.COD_CLIENTE 
					where uni.COD_EMPRESA = $cod_empresa
					 
					group by uni.cod_univend 

					order by uni.NOM_UNIVEND; ";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		$vendasTotal = 0;
		$vendasAvulsas = 0;
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$array[] = $row;
			$vendasTotal += $row['VENDA_TOTAL'];
			$vendasAvulsas += $row['AVULSO'];
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		/* ADICIONANDO TOTALIZADOR AO RELATÓRIO */
		array_push($array, array('COD_UNIVEND' => "", "NOM_FANTASI" => "Total", 'VENDA_TOTAL'  => $vendasTotal, 'TOTAL_CLIENTE' => "", 'CLIENTES_COMPRA' => "", 'AVULSO' => $vendasAvulsas));

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'paginar':

		break;
}
