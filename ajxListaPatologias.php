<?php
include '_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$id_patologia = "";
$opcao = "";
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

$id_patologia = @$_REQUEST['patolSelecio'];
$cod_empresa = fnDecode(@$_GET['id']);

$opcao = fnLimpaCampo(@$_GET['opcao']);
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];


if ($id_patologia == "") {
	$andPatologia = " ";
} else {
	$andPatologia = "WHERE ID_PATOLOGIA = '$id_patologia'";
}


switch ($opcao) {

	case 'paginar':

		$sqlCount = "SELECT COUNT(*) as CONTADOR FROM produtocontinuo
	$andPatologia
	ORDER BY NOM_PATOLOGIA
	";
		// fnEscreve($sql);

		$retorno = mysqli_query($prod_continuo->connUser(), $sqlCount);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		//consulta principal da tabela.
		$sql =  "SELECT ID_PATOLOGIA, NOM_PATOLOGIA FROM patologia 
	$andPatologia
	ORDER BY NOM_PATOLOGIA
	LIMIT $inicio,$itens_por_pagina";
		//fnEscreve($sql);

		$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);


		$count = 0;
		while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			echo "
		<tr>
		<td>" . fnformatCnpjCpf($qrBuscaMedicamento['CNPJ']) . "</td>
		<td>" . $qrBuscaMedicamento['LABORATORIO'] . "</td>
		</tr>
		";
		}

		break;



	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql =  "SELECT ID_PATOLOGIA, NOM_PATOLOGIA FROM patologia 
	$andPatologia
	ORDER BY NOM_PATOLOGIA";
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
