<?php

include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');
fnEscreveArray($_REQUEST);

$opcao = $_GET['opcao'];
$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
$andAtivo = "AND LOG_ATIVO = 'S'";

if ($filtro != "") {
	$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
} else {
	$andFiltro = " ";
}


$nomeRel = $_GET['nomeRel'];
$arquivo = 'media/excel/3_' . $nomeRel . '.csv';

fnEscreve($arquivo);

$writer = WriterFactory::create(Type::CSV);
$writer->setFieldDelimiter(';');
$writer->openToFile($arquivo);

if ($_SESSION["SYS_COD_MASTER"] == "2") {
	$sql = "SELECT STATUSSISTEMA.DES_STATUS, empresas.*,
                                                (select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE,
                                                (select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
                                                (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
                                                (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
                                                (SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
                                                B.COD_DATABASE, 
                                                B.NOM_DATABASE 
                                                FROM empresas  
                                                LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
                                                LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
                                                WHERE empresas.COD_EMPRESA <> 1 
                                                $andFiltro
                                                $andAtivo
                                                ORDER by NOM_FANTASI";
} else {
	$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.*,
                                                (select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE, 
                                                (select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
                                                (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
                                                (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
                                                (SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
                                                B.COD_DATABASE, 
                                                B.NOM_DATABASE 
                                                FROM empresas  
                                                LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
                                                LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
                                                WHERE empresas.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
                                                $andFiltro
                                                $andAtivo
                                                ORDER by NOM_FANTASI";
}

fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

$array = array();
while ($row = mysqli_fetch_assoc($arrayQuery)) {
	$newRow = array();

	$cont = 0;
	foreach ($row as $objeto) {

		array_push($newRow, $objeto);


		$cont++;
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
