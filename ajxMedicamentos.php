<?php

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	// echo fnDebug('true');
	// fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$adm = $Cdashboard->connAdm();
	// print_r($adm);

	$cod_empresa = fnDecode($_GET['id']);
	$opcao = fnLimpaCampo(@$_GET['opcao']);
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	echo $cod_empresa;
	echo $itens_por_pa1gina;
	echo $opcao;

	$c1od_usucada = $_SESSION["SYS_COD_USUARIO"];

	switch($opcao){

		case 'paginar':

			$filtro = fnLimpaCampo($_REQUEST['VAL_PESQUISA']);
			$val_pesquisa = fnLimpaCampo($_REQUEST['INPUT']);

			if ($filtro != "") {
				if ($filtro == "CODIGO_BARRA") {
					$andFiltro = " AND A.CODIGO_BARRA = '$val_pesquisa' ";
				} else if ($filtro == "ID") {
					$andFiltro = " AND A.ID = '$val_pesquisa' ";
				} else if ($filtro == "NOM_MEDICAMENTO") {
					$andFiltro = " AND A.NOM_MEDICAMENTO = '$val_pesquisa' ";
				} else if ($filtro == "DURACAO") {
					$andFiltro = " AND A.DURACAO = '$val_pesquisa' ";
				} else {
					$andFiltro = " AND A.$filtro LIKE '%$val_pesquisa%' ";
				}
			} else {
				$andFiltro = " ";
			}

			//contador
			$sqlCount = "SELECT COUNT(*) as CONTADOR from PRODUTOS_MARKA_TO A  
						WHERE A.COD_EMPRESA=$cod_empresa
						$andFiltro
						AND A.COD_EXCLUSA=0 ORDER BY A.ID";
			// fnEscreve($sql);

			$retorno = mysqli_query($adm, $sqlCount);
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

			$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			//consulta principal da tabela.
			$sql =  "SELECT * FROM PRODUTOS_MARKA_TO A
						WHERE A.COD_EMPRESA =$cod_empresa
						$andFiltro 
						AND A.COD_EXCLUSA = 0 ORDER BY A.ID LIMIT $inicio,$itens_por_pagina";
			// fnEscreve($sql);

			$arrayQuery = mysqli_query($adm, $sql);

			$count = 0;
			while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
				$count++;
				echo "
					<tr>
						<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
						<td>" . $qrBuscaMedicamento['ID'] . "</td>
						<td>" . $qrBuscaMedicamento['NOM_MEDICAMENTO'] . "</td>
						<td>" . $qrBuscaMedicamento['CODIGO_BARRA'] . "</td>
						<td>" . $qrBuscaMedicamento['DURACAO'] . "</td>
					</tr>
					<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrBuscaMedicamento['ID'] . "'>
					<input type='hidden' id='ret_NOM_MEDICAMENTO_" . $count . "' value='" . $qrBuscaMedicamento['NOM_MEDICAMENTO'] . "'>
					<input type='hidden' id='ret_CODIGO_BARRA_" . $count . "' value='" . $qrBuscaMedicamento['CODIGO_BARRA'] . "'>
					<input type='hidden' id='ret_DURACAO_" . $count . "' value='" . $qrBuscaMedicamento['DURACAO'] . "'>
					";
			}

		break;
	}
?>