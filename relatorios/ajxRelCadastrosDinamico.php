<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$mostraXml = "";
$dat_ini = "";
$dat_fim = "";
$cod_usuario = "";
$lojasSelecionadas = "";
$camposCAD = "";
$NOM_CAMPOOBGCAD = "";
$NOM_CAMPOOBGTAB = "";
$includsql = "";
$campoSQL = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$andUsuario = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = "";
$arquivoCaminho = "";
$headers = "";
$row = "";
$qualidade = "";
$limpandostring = "";
$textolimpo = "";
$array = "";
$newRow = "";
$countCampos = "";
$objeto = "";
$arrayColumnsNames = "";
$somadesult = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$mostraXml = @$_GET['mostrarXML'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_usuario = @$_POST['COD_USUARIO'];
$lojasSelecionadas = fnLimpaCampo(@$_POST['LOJAS']);

$camposCAD = fnQualidadeCampos($connAdm->connAdm(), $cod_empresa);

$NOM_CAMPOOBGCAD = explode(',', $camposCAD['NOM_CAMPOOBG']);
$NOM_CAMPOOBGTAB = explode(',', $camposCAD['DES_CAMPOOBG']);

$includsql = "";

foreach ($NOM_CAMPOOBGTAB as $campoSQL) {
	if ($campoSQL == "COD_SEXOPES") {

		$includsql .=  " IFNULL(sum(
							case when A.$campoSQL IS NULL  then
							0
							when A.$campoSQL = '3' then
							0
							else
							1
							END),0) $campoSQL,";
	} else {

		$includsql .=  " IFNULL(sum(
							case when A.$campoSQL IS NULL  then
							0
							when A.$campoSQL = '' then
							0
							else
							1
							END),0) $campoSQL,";
		//$includsql.=  "ifnull(SUM(if(A.$campoSQL IS NOT NULL,1,0)),0) AS  $campoSQL,";

	}
}

$includsql = rtrim(ltrim($includsql, ','), ',');

// fnEscreve($includsql);


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

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

if ($cod_usuario != "" && $cod_usuario != 0) {
	$andUsuario = "AND C.COD_USUARIO = $cod_usuario";
} else {
	$andUsuario = "";
}

switch ($opcao) {
	case 'exportarDetalhado':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT DISTINCT 
			        A.COD_EMPRESA,
			        A.COD_UNIVEND,
			        -- B.NOM_UNIVEND,
			        B.NOM_FANTASI,
			        C.NOM_USUARIO,
			        COUNT(COD_CLIENTE) QTD_CADASTRO,
			        $includsql
			        FROM CLIENTES A
			        LEFT JOIN WEBTOOLS.unidadevenda B ON B.COD_UNIVEND = A.COD_UNIVEND
			        LEFT JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO = A.COD_ATENDENTE
			        WHERE 
			        A.DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' and 
			        A.LOG_AVULSO='N' AND
			        A.COD_EMPRESA = $cod_empresa AND
			        A.COD_UNIVEND IN($lojasSelecionadas)
			        $andUsuario
			        GROUP BY A.COD_ATENDENTE,A.COD_UNIVEND
			        ORDER BY B.NOM_FANTASI,C.NOM_USUARIO 
					";
		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
		/*
			$arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){

				$qualidade += $row[$campoSQL];
				
				$row['QUALIDADE']= fnValor(($qualidade  / count($NOM_CAMPOOBGTAB)*100) / $row['QTD_CADASTRO'],2)."%";
				
				
				$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
				$textolimpo=json_decode($limpandostring,true);
				$array = array_map("utf8_decode", $row);
				fputcsv ($arquivo,$textolimpo,';','"','\n');
				
				//echo "<pre>";
				//print_r($qualidade);
				//echo "</pre>";	
			}
			fclose($arquivo);
			*/

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$cont = 0;
			$countCampos = 0;
			$qualidade = 0;

			foreach ($row as $objeto) {

				if ($cont > 4) {
					$qualidade += $objeto;
					$countCampos++;
				}

				array_push($newRow, $objeto);

				$cont++;
			}

			array_push($newRow, fnValor((($qualidade / $countCampos) * 100) / $row['QTD_CADASTRO'], 2) . "%");
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		array_push($arrayColumnsNames, "QUALIDADE");

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	case 'exportarGeral':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT DISTINCT 
			        -- A.COD_EMPRESA,
			        -- A.COD_UNIVEND,
			        -- B.NOM_UNIVEND,
			        B.NOM_FANTASI Loja,
			        -- C.NOM_USUARIO,
			        COUNT(COD_CLIENTE) Cadastro,
			        $includsql,
			        '' AS QUALIDADE
			        FROM CLIENTES A
			        LEFT JOIN WEBTOOLS.unidadevenda B ON B.COD_UNIVEND = A.COD_UNIVEND
			        LEFT JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO = A.COD_ATENDENTE
			        WHERE 
			        A.DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' and 
			        A.LOG_AVULSO='N' AND
			        A.COD_EMPRESA = $cod_empresa AND
			        A.COD_UNIVEND IN($lojasSelecionadas)
			        $andUsuario
			        GROUP BY A.COD_UNIVEND
			        ORDER BY B.NOM_FANTASI,C.NOM_USUARIO 
					";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			// resetando soma
			$somadesult = 0;

			// somando todos os valores dos campos dinâmicos
			foreach ($NOM_CAMPOOBGTAB as $campoSQL) {
				$somadesult += $row[$campoSQL];
			}

			// fazendo a média da soma baseada na quantidade de cadastros feitos
			$qualidade = ((($somadesult) / count($NOM_CAMPOOBGTAB)) * 100) / $row['Cadastro'];

			// formatando a percentual e inserindo no alias vazio da consulta
			$row['QUALIDADE'] = fnvalor($qualidade, 2) . "%";

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		break;
}
