<?php

include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$cod_persona = fnDecode($_GET['codPersona']);

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// if ($_SESSION['SYS_COD_EMPRESA'] != 2) {
		// 	$sql = "call SP_GERA_EXCEL_PERSONA( '$cod_persona' , $cod_empresa);";

		// 	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		// 	$arquivo = fopen($arquivoCaminho, 'w', 0);

		// 	while ($headers = mysqli_fetch_field($arrayQuery)) {
		// 		$CABECHALHO[] = $headers->name;
		// 	}

		// 	fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

		// 	while ($row = mysqli_fetch_assoc($arrayQuery)) {

		// 		$array = array_map("utf8_decode", $row);
		// 		fputcsv($arquivo, $array, ';', '"', '\n');
		// 	}
		// 	fclose($arquivo);
		// } else {
		$itens_por_pagina = 100000;

		$sql = "SELECT COUNT(*) as CONTADOR FROM PERSONACLASSIFICA A, CLIENTES B
				WHERE A.COD_CLIENTE = B.COD_CLIENTE 
				AND B.LOG_AVULSO='N' 
				AND A.COD_PERSONA = $cod_persona 
				AND A.COD_EMPRESA = $cod_empresa";

		$retorno = mysqli_query(conntemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		$arquivo = fopen($arquivoCaminho, 'w', 0);
		$CABECHALHO = [];

		for ($pagina = 1; $pagina <= $numPaginas; $pagina++) {

			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			$sql = "SELECT B.COD_CLIENTE,
						B.NUM_CARTAO,
						B.NUM_CGCECPF,
						B.NOM_CLIENTE,
						B.DES_EMAILUS,
						B.NUM_CELULAR,
						B.DAT_CADASTR,
						B.DAT_NASCIME,
						B.COD_SEXOPES,
						PE.DES_PROFISS,
						C.NOM_UNIVEND
				FROM PERSONACLASSIFICA A
				INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
				INNER JOIN $connAdm->DB.unidadevenda C ON B.COD_UNIVEND = C.COD_UNIVEND
				LEFT JOIN $connAdm->DB.profissoes PE ON PE.COD_PROFISS = B.COD_PROFISS
				WHERE A.COD_PERSONA = $cod_persona
					AND A.COD_EMPRESA = $cod_empresa
					AND B.LOG_AVULSO = 'N'
				ORDER BY B.NOM_CLIENTE limit $inicio,$itens_por_pagina";

			$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

			if ($pagina == 1) {
				while ($headers = mysqli_fetch_field($arrayQuery)) {
					$CABECHALHO[] = $headers->name;
				}
				fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');
			}

			while ($row = mysqli_fetch_assoc($arrayQuery)) {
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');
			}
		}

		fclose($arquivo);
		// }

		break;

	case 'exportar2':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		/*$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 	*/

		// $sql = "call SP_GERA_EXCEL_PERSONA( '$cod_persona' , $cod_empresa);";
		$sql = "call SP_GERA_EXCEL_PERSONA_LIMIT('$cod_persona' , $cod_empresa, '" . str_replace(',', ";", $_SESSION['SYS_COD_UNIVEND']) . "')";
		echo ($sql);

		//SELECT da lista, não é o mesmo do export

		/*$sql = "SELECT B.COD_CLIENTE,B.NUM_CARTAO,B.NUM_CGCECPF,B.NOM_CLIENTE,
					B.DES_EMAILUS,B.NUM_CELULAR,B.DAT_CADASTR,B.DAT_NASCIME ,B.COD_SEXOPES, C.NOM_UNIVEND 
					FROM PERSONACLASSIFICA A, CLIENTES B, $connAdm->DB.unidadevenda C
					WHERE 
					A.COD_CLIENTE=B.COD_CLIENTE AND
					B.COD_UNIVEND=C.COD_UNIVEND AND
					B.LOG_AVULSO='N' AND
					A.COD_PERSONA = $cod_persona AND 
					A.COD_EMPRESA = $cod_empresa ";
			*/
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			//echo "<pre>";
			//print_r($row);
			//echo "</pre>";

			/*$row[VALOR_COMPRA]=fnvalor($row[VALOR_COMPRA],2);
					$row[VALOR_RESGATE]=fnvalor($row[VALOR_RESGATE],2);
					$row[CREDITO_TOTAL]=fnvalor($row[CREDITO_TOTAL],2);
					$row[CREDITO_DISPONIVEL]=fnvalor($row[CREDITO_DISPONIVEL],2);
					$row[CREDITO_EXPIRA_30]=fnvalor($row[CREDITO_EXPIRA_30],2);
					$row[VAL_TICKET_MEDIO]=fnvalor($row[VAL_TICKET_MEDIO],2);
					$row[VAL_TICKET_MEDIO]=fnvalor($row[VAL_TICKET_MEDIO],2);*/


			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"', '\n');
		}
		fclose($arquivo);
		/*$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
                        $array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 16 || $cont == 19 || $cont == 21 || ($cont >= 28 && $cont <= 31) || $cont == 37 || $cont == 38){
						array_push($newRow, fnValor($objeto, 2));
					}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
            }			
                        
			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();*/
		break;

	case 'paginar':

		$sql = "SELECT COUNT(*) as CONTADOR FROM PERSONACLASSIFICA A, CLIENTES B
					WHERE 
					A.COD_CLIENTE = B.COD_CLIENTE AND
					B.LOG_AVULSO='N' AND
					A.COD_PERSONA = $cod_persona AND 
					A.COD_EMPRESA = $cod_empresa ";
		//fnEscreve($sql);

		$retorno = mysqli_query(conntemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		$sql = "SELECT B.COD_CLIENTE,
					       B.NUM_CARTAO,
					       B.NUM_CGCECPF,
					       B.NOM_CLIENTE,
					       B.DES_EMAILUS,
					       B.NUM_CELULAR,
					       B.DAT_CADASTR,
					       B.DAT_NASCIME,
					       B.COD_SEXOPES,
					       PE.DES_PROFISS,
					       C.NOM_UNIVEND
					FROM PERSONACLASSIFICA A
					INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
					INNER JOIN $connAdm->DB.unidadevenda C ON B.COD_UNIVEND = C.COD_UNIVEND
					LEFT JOIN $connAdm->DB.profissoes PE ON PE.COD_PROFISS = B.COD_PROFISS
					WHERE A.COD_PERSONA = $cod_persona
					  AND A.COD_EMPRESA = $cod_empresa
					  AND B.LOG_AVULSO = 'N'
					order by B.NOM_CLIENTE limit $inicio,$itens_por_pagina";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			if ($qrListaPersonas['COD_SEXOPES'] == 1) {
				$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
			} else {
				$mostraSexo = '<i class="fa fa-female" style="color:pink" aria-hidden="true"></i>';
			}

			echo "
					<tr>
						<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</a></td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</small></td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CGCECPF']) . "</small></td>
						<td class='text-center'>" . $mostraSexo . "</td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['DES_EMAILUS']) . "</small></td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CELULAR']) . "</small></td>
						<td><small>" . fnMascaraCampo($qrListaPersonas['DAT_NASCIME']) . "</small></td>
						<td><small>" . $qrListaPersonas['DES_PROFISS'] . "</small></td>
						<td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
						<td><small>" . $qrListaPersonas['NOM_UNIVEND'] . "</small></td>
					</tr>
				";
		}

		break;
}
