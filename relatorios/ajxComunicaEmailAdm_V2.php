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

$opcao = $_GET['opcao'];
$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA_COMBO']);
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);

$andData = "AND (EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' /*OR CEM.DAT_ENVIO IS NULL*/)";

if ($cod_empresa != 0) {
	$andEmpresa = "AND apar.COD_EMPRESA = $cod_empresa";
} else {
	$andEmpresa = "";
}

// fnEscreve('chega aqui');
// fnEscreve($opcao);

// exit();

switch ($opcao) {

	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/3_' . $nomeRel . '.csv';

		// fnEscreve($arquivo);

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sqlEmp = "SELECT EMP.COD_EMPRESA, EMP.NOM_FANTASI FROM senhas_parceiro apar
	                    INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
	                    INNER JOIN webtools.EMPRESAS EMP  ON EMP.COD_EMPRESA=apar.COD_EMPRESA
	                    WHERE par.COD_TPCOM='1' 
	                    AND apar.LOG_ATIVO='S'
	                    $andEmpresa";

		$arrayEmp = mysqli_query($connAdm->connAdm(), $sqlEmp);
		// exit();

		$count = 0;
		$array = array();
		while ($qrEmp = mysqli_fetch_assoc($arrayEmp)) {


			$sql = "SELECT
	                        '' AS EMPRESA,
	                        MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO,
	                        SUM(EL.QTD_LISTA) AS QTD_LISTA,
	                        SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
	                        SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
	                        SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
	                        SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
	                        SUM(CEM.ERROR_TEMP) AS QTD_SOFT,
	                        (SUM(CEM.ERROR_PERM)+SUM(CEM.QTD_IMPORT_ERRO)) AS QTD_HARD,
	                        SUM(CEM.SPAN) AS QTD_SPAM
	                      FROM EMAIL_LOTE EL
	                      LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
	                      WHERE EL.LOG_ENVIO = 'S'
	                      AND EL.LOG_TESTE = 'N'
	                      AND EL.COD_EMPRESA = $qrEmp[COD_EMPRESA]
	                      $andData
	                      GROUP BY EL.COD_EMPRESA
	                      ORDER BY EL.COD_CONTROLE DESC";


			$arrayQuery = mysqli_query(connTemp($qrEmp['COD_EMPRESA'], ''), $sql);

			$newRow = array();

			while ($row = mysqli_fetch_assoc($arrayQuery)) {

				$cont = 0;
				foreach ($row as $objeto) {

					if ($cont == 0) {

						array_push($newRow, $qrEmp['NOM_FANTASI']);
					} else {

						array_push($newRow, $objeto);
					}

					$cont++;
				}
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	default:

		break;
}
