<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$cod_blklist = $_GET['COD_BLKLIST'];
$andFiltro = $_POST['AND_FILTRO'];

//fnEscreve($filtro);


switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT B.DES_PRODUTO, A.* 
				FROM BLACKLISTTKTPROD A, PRODUTOCLIENTE B
				where A.COD_BLKLIST = '$cod_blklist' AND A.COD_EMPRESA = $cod_empresa and A.COD_PRODUTO = B.COD_PRODUTO $andFiltro ORDER BY TRIM(DES_PRODUTO)";

		fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				array_push($newRow, $objeto);
				// Colunas que são double converte com fnValor

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
		break;
	case 'paginar':

		$sql = "select count(*) as CONTADOR from BLACKLISTTKTPROD
				  where COD_BLKLIST = $cod_blklist AND COD_EMPRESA = $cod_empresa  ";


		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT B.DES_PRODUTO, A.* 
				FROM BLACKLISTTKTPROD A, PRODUTOCLIENTE B
				where A.COD_BLKLIST = '$cod_blklist' AND A.COD_EMPRESA = $cod_empresa and A.COD_PRODUTO = B.COD_PRODUTO $andFiltro ORDER BY TRIM(DES_PRODUTO) LIMIT $inicio,$itens_por_pagina";

		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		$countLinha = 1;
		while ($qrBuscaProdutoHab = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			echo "
					<tr>
					  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
					  <td>" . $qrBuscaProdutoHab['COD_PRODHAB'] . "</td>
					  <td>" . $qrBuscaProdutoHab['COD_PRODUTO'] . "</td>
					  <td>" . $qrBuscaProdutoHab['DES_PRODUTO'] . "</td>
					  <td>" . fnDataFull($qrBuscaProdutoHab['DAT_CADASTR']) . "</td>
					</tr>
					<input type='hidden' id='ret_COD_PRODHAB_" . $count . "' value='" . $qrBuscaProdutoHab['COD_PRODHAB'] . "'>
					<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaProdutoHab['COD_PRODUTO'] . "'>
					<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaProdutoHab['DES_PRODUTO'] . "'>
					";

			$countLinha++;
		}

		break;
}
