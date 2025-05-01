<?php
if (isset($_SESSION['SYS_COD_USUARIO']) && $_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$nom_cliente = "";
$num_cartao = "";
$andFiltro = "";
$log_grupo = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$andGrupo = "";
$filtro = "";
$val_pesquisa = "";
$sqlCat = "";
$arrayCat = [];
$cod_categor = "";
$qrCat = "";
$andProduto = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$pontua = "";
$arrayColumnsNames = [];
$cod_externo = "";
$pesquisa = "";
$des_produto = "";
$andExternoTkt = "";
$andExterno = "";
$retorno = "";
$inicio = "";
$qrListaProduto = "";
$mostraDES_IMAGEM = "";

include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$nom_cliente = @$_POST['NOM_CLIENTE'];
$num_cartao = @$_POST['NUM_CARTAO'];
$andFiltro = @$_REQUEST['AND_FILTRO'];
if (empty(@$_REQUEST['LOG_GRUPO'])) {
	$log_grupo = 'N';
} else {
	$log_grupo = @$_REQUEST['LOG_GRUPO'];
}

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

$andGrupo = "";

if ($log_grupo == 'S') {
	$andGrupo = "AND (A.COD_CATEGOR = 0 OR A.COD_CATEGOR IS NULL)";
}
if ($filtro != '') {
	if ($filtro == "EAN" || $filtro == "COD_PRODUTO") {
		$andFiltro = " AND A.$filtro = '$val_pesquisa' ";
	} else if ($filtro == "COD_CATEGOR") {
		$sqlCat = "SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR LIKE '%$val_pesquisa%'";
		$arrayCat = mysqli_query(connTemp($cod_empresa, ''), $sqlCat);
		$cod_categor = "";
		while ($qrCat = mysqli_fetch_assoc($arrayCat)) {
			$cod_categor .= $qrCat['COD_CATEGOR'] . ",";
		}
		$cod_categor = ltrim(rtrim($cod_categor, ','), ',');
		$andFiltro = "AND B.COD_CATEGOR IN($cod_categor)";
	} else if ($filtro == "DES_PRODUTO_EQ") {
		$andFiltro = " AND A.DES_PRODUTO = '$val_pesquisa' ";
	} else {
		$andFiltro = " AND A.$filtro LIKE '%$val_pesquisa%' ";
	}
} else {
	$andFiltro = " ";
}

//se pesquisa dos produtos do ticket
if (!empty(@$_GET['idP']) && @$_GET['idP'] != "") {
	$andProduto = 'AND A.COD_PRODUTO = "' . fnDecode(@$_GET['idP']) . '"';
} else {
	$andProduto = " ";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT A.COD_PRODUTO COD,
						 A.DES_PRODUTO PRODUTO,
						 A.COD_EXTERNO,
						 A.COD_LOTE,
						 A.EAN,
						 B.COD_EXTERNO AS 'Cod_Externo',
       					 B.COD_CATEGOR AS 'Cod_Categor',
						 B.DES_CATEGOR GRUPO,
						 C.COD_SUBEXTE AS 'Cod_Externo',
      					 C.COD_SUBCATE AS 'Cod_Subcategoria',
						 C.DES_SUBCATE SUB_GRUPO,
						 A.LOG_PONTUAR NAO_PONTUAR
					from PRODUTOCLIENTE A 
	                LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR AND A.COD_EMPRESA = B.COD_EMPRESA 
	                LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE AND A.COD_EMPRESA = C.COD_EMPRESA  
	                where A.COD_EMPRESA='" . $cod_empresa . "' 
	                " . $andFiltro . "
	                " . $andProduto . " 
	                AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO ";

		fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				if ($cont == 10) {
					if ($objeto == 0) {
						$pontua = 'N';
					} else {
						$pontua = 'S';
					}
					array_push($newRow, $pontua);
				} else {
					array_push($newRow, $objeto);
				}

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

		//variáveis da pesquisa
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);

		//pesquisa no form local
		$andExternoTkt = ' ';
		if (empty(@$_REQUEST['pesquisa'])) {
			//fnEscreve("sem pesquisa");
			$andProduto = ' ';
			$andExterno = ' ';
		} else {
			//fnEscreve("com pesquisa");
			if ($des_produto != '' && $des_produto != 0) {
				$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
			} else {
				$andProduto = ' ';
			}

			if ($cod_externo != '' && $cod_externo != 0) {
				$andExterno = 'AND A.COD_EXTERNO = "' . $cod_externo . '"';
			} else {
				$andExterno = ' ';
			}
		}

		//se pesquisa dos produtos do ticket
		if (!empty(@$_GET['idP'])) {
			$andExterno = 'AND A.COD_EXTERNO = "' . @$_GET['idP'] . '"';
		}

		//fnEscreve("entrou");

		$sql = "SELECT COUNT(*) as CONTADOR from PRODUTOCLIENTE A 
					left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR AND A.COD_EMPRESA = B.COD_EMPRESA
					left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE AND A.COD_EMPRESA = C.COD_EMPRESA
					where A.COD_EMPRESA = $cod_empresa 
					$andFiltro
					$andProduto
					$andGrupo
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
				LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR AND A.COD_EMPRESA = B.COD_EMPRESA
				LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE AND A.COD_EMPRESA = C.COD_EMPRESA
				where A.COD_EMPRESA = $cod_empresa 
				$andFiltro
				$andProduto
				$andGrupo 
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";

		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

		$count = 0;
		while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			if ($qrListaProduto['DES_IMAGEM'] != "") {
				$mostraDES_IMAGEM = '<a href="https://img.bunker.mk/media/clientes/' . $cod_empresa . '/produtos/' . $qrListaProduto['DES_IMAGEM'] . '" target="_blank">Visualizar</a>';
			} else {
				$mostraDES_IMAGEM = '';
			}

			echo "
					<tr>
					  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
					  <td>" . $qrListaProduto['COD_PRODUTO'] . "</td>
					  <td>" . $qrListaProduto['COD_EXTERNO'] . "</td>
					  <td>" . $qrListaProduto['COD_LOTE'] . "</td>
					  <td>" . $qrListaProduto['EAN'] . "</td>
					  <td>" . $qrListaProduto['GRUPO'] . "</td>
					  <td>" . $qrListaProduto['SUBGRUPO'] . "</td>
					  <td>" . $qrListaProduto['DES_PRODUTO'] . "</td>
					  <td class='text-center'>" . $mostraDES_IMAGEM . "</td>
					</tr>
					<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrListaProduto['COD_PRODUTO'] . "'>  
					<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaProduto['COD_EXTERNO'] . "'>
					<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrListaProduto['DES_PRODUTO'] . "'>
					<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrListaProduto['COD_CATEGOR'] . "'>
					<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrListaProduto['COD_SUBCATE'] . "'>
					<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrListaProduto['COD_FORNECEDOR'] . "'>
					<input type='hidden' id='ret_COD_EAN_" . $count . "' value='" . $qrListaProduto['EAN'] . "'>
					<input type='hidden' id='ret_ATRIBUTO1_" . $count . "' value='" . $qrListaProduto['ATRIBUTO1'] . "'>
					<input type='hidden' id='ret_ATRIBUTO2_" . $count . "' value='" . $qrListaProduto['ATRIBUTO2'] . "'>
					<input type='hidden' id='ret_ATRIBUTO3_" . $count . "' value='" . $qrListaProduto['ATRIBUTO3'] . "'>
					<input type='hidden' id='ret_ATRIBUTO4_" . $count . "' value='" . $qrListaProduto['ATRIBUTO4'] . "'>
					<input type='hidden' id='ret_ATRIBUTO5_" . $count . "' value='" . $qrListaProduto['ATRIBUTO5'] . "'>
					<input type='hidden' id='ret_ATRIBUTO6_" . $count . "' value='" . $qrListaProduto['ATRIBUTO6'] . "'>
					<input type='hidden' id='ret_ATRIBUTO7_" . $count . "' value='" . $qrListaProduto['ATRIBUTO7'] . "'>
					<input type='hidden' id='ret_ATRIBUTO8_" . $count . "' value='" . $qrListaProduto['ATRIBUTO8'] . "'>
					<input type='hidden' id='ret_ATRIBUTO9_" . $count . "' value='" . $qrListaProduto['ATRIBUTO9'] . "'>
					<input type='hidden' id='ret_ATRIBUTO10_" . $count . "' value='" . $qrListaProduto['ATRIBUTO10'] . "'>
					<input type='hidden' id='ret_ATRIBUTO11_" . $count . "' value='" . $qrListaProduto['ATRIBUTO11'] . "'>
					<input type='hidden' id='ret_ATRIBUTO12_" . $count . "' value='" . $qrListaProduto['ATRIBUTO12'] . "'>
					<input type='hidden' id='ret_ATRIBUTO13_" . $count . "' value='" . $qrListaProduto['ATRIBUTO13'] . "'>
					<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrListaProduto['DES_IMAGEM'] . "'>
					<input type='hidden' id='ret_LOG_PRODPBM_" . $count . "' value='" . $qrListaProduto['LOG_PRODPBM'] . "'>
					<input type='hidden' id='ret_LOG_HABITEXC_" . $count . "' value='" . $qrListaProduto['LOG_HABITEXC'] . "'>
					<input type='hidden' id='ret_LOG_NRESGATE_" . $count . "' value='" . $qrListaProduto['LOG_NRESGATE'] . "'>
					<input type='hidden' id='ret_LOG_PONTUAR_" . $count . "' value='" . $qrListaProduto['LOG_PONTUAR'] . "'>
					";
		}

		break;
}
