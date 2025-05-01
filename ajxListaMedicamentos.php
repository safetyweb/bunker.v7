<?php
include '_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$produto = "";
$ean1 = "";
$id_patologia = "";
$andMedicamento = "";
$andEan = "";
$andPatologia = "";
$sqlCount = "";
$retorno = "";
$inicio = "";
$arrayQuery = [];
$qrBuscaMedicamento = "";
$nomeRel = "";
$arquivoCaminho = "";
$arquivo = "";
$headers = "";
$row = "";
$array = [];




$produto = @$_REQUEST['PRODUTO'];
$ean1 = @$_REQUEST['EAN1'];
$id_patologia = @$_REQUEST['patolSelecio'];
$cod_empresa = fnDecode(@$_GET['id']);
$opcao = fnLimpaCampo(@$_GET['opcao']);
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];

echo $opcao;


if ($produto == "") {
	$andMedicamento = " ";
} else {
	$andMedicamento = "WHERE PRODUTO LIKE '$produto'";
}
if ($ean1 == "") {
	$andEan = " ";
} else {
	$andEan = "WHERE EAN1 = '$ean1'";
}
if ($id_patologia == "") {
	$andPatologia = " ";
} else {
	$andPatologia = "WHERE ID_PATOLOGIA = '$id_patologia'";
}


switch ($opcao) {

	case 'paginar':

		$sqlCount = "SELECT COUNT(*) as CONTADOR FROM produtocontinuo
	INNER JOIN patologia ON ID_PATOLOGIA=COD_PATOLOGICO
	$andMedicamento
	$andEan
	$andPatologia
	";
		// fnEscreve($sql);

		$retorno = mysqli_query($prod_continuo->connUser(), $sqlCount);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		//consulta principal da tabela.
		$sql =  "SELECT * FROM produtocontinuo
	INNER JOIN  patologia ON ID_PATOLOGIA=COD_PATOLOGICO
	$andMedicamento
	$andEan
	$andPatologia
	LIMIT $inicio,$itens_por_pagina";
		//fnEscreve($sql);

		$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);


		$count = 0;
		while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			echo "
		<tr>
		<td>" . $qrBuscaMedicamento['PRODUTO'] . "</td>
		<td>" . $qrBuscaMedicamento['APRESENTACAO'] . "</td>
		<td>" . $qrBuscaMedicamento['EAN1'] . "</td>
		<td>" . $qrBuscaMedicamento['SUBSTANCIA'] . "</td>
		<td>" . $qrBuscaMedicamento['LABORATORIO'] . "</td>
		<td>" . $qrBuscaMedicamento['NOM_PATOLOGIA'] . "</td>
		<td>" . $qrBuscaMedicamento['TIPO_PRODUTO'] . "</td>
		<td>" . $qrBuscaMedicamento['QUANTIDADE'] . "</td>
		</tr>
		";
		}

		break;



	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql =  "SELECT PC.SUBSTANCIA,
	PC.LABORATORIO,
	PC.EAN1,
	PC.PRODUTO,
	PC.APRESENTACAO,
	PC.TIPO_PRODUTO,
	PT.NOM_PATOLOGIA,
	PC.QUANTIDADE 
	FROM produtocontinuo AS PC
	INNER JOIN  patologia as PT ON PT.ID_PATOLOGIA=PC.COD_PATOLOGICO
	$andMedicamento
	$andEan
	$andPatologia";
		//fnEscreve($sql);

		$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}

		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		break;
}
