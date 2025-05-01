<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

echo fnDebug('true');

$dias30 = "";
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));


$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$CarregaMaster = fnDecode(@$_POST['CARREGA_MASTER']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$cod_univend = @$_POST['COD_UNIVEND'];
$andUnidade = @$_POST['AND_UNIDADE'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

// echo "_ ";
// echo $CarregaMaster;
// echo " _";

//faz pesquisa por revenda (geral)
if (($cod_univend == "9999" || $cod_univend[0] == "9999") && $CarregaMaster == '0') {
	$andUnidade = "AND cod_univend IN($_SESSION[SYS_COD_UNIVEND])";
} else {
	if ($cod_univend == "9999" || $cod_univend[0] == "9999") {
		$andUnidade	= "";
	} else {
		$andUnidade	= "AND cod_univend IN($lojasSelecionadas)";
	}
}

// echo($cod_univend[0]);

// if ($cod_univend == 9999){
// 	$andUnidade	= "";
// }else{
// 	$andUnidade	= "$andUnidade";	
// }


switch ($opcao) {
	case 'exportarLojas':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		/*$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);
			*/

		$sql = "SELECT U.cod_univend AS codigo_unidade_resgate, 
						U.nom_fantasi AS nome_unidade_resgate, 
						U.num_cgcecpf AS cpfcnpj_unidade_resgate, 
						Ifnull((SELECT Sum(val_resgatado) - Sum(val_estorno) 
							   FROM   historico_resgate A, 
									  creditosdebitos B 
							   WHERE  A.cod_univend != B.cod_univend 
									  AND A.cod_credito = B.cod_credito 
									  AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
									  AND A.val_resgatado > 0 
									  AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
									  AND A.cod_univend = U.cod_univend), 0)+
						Ifnull((SELECT Sum(val_resgatado) - Sum(val_estorno) 
							   FROM   historico_resgate A, 
									  creditosdebitos_bkp B 
							   WHERE  A.cod_univend != B.cod_univend 
									  AND A.cod_credito = B.cod_credito 
									  AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
									  AND A.val_resgatado > 0 
									  AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
									  AND A.cod_univend = U.cod_univend), 0) AS  total_valor_a_receber, 
															
						Ifnull((SELECT Sum(val_resgatado) - Sum(val_estorno) 
							   FROM   historico_resgate A, 
									  creditosdebitos B 
							   WHERE  A.cod_univend != B.cod_univend 
									  AND A.cod_credito = B.cod_credito 
									  AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
									  AND A.val_resgatado > 0 
									  AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
									  AND B.cod_univend = U.cod_univend), 0)+
						 Ifnull((SELECT Sum(val_resgatado) - Sum(val_estorno) 
							   FROM   historico_resgate A, 
									  creditosdebitos_bkp B 
							   WHERE  A.cod_univend != B.cod_univend 
									  AND A.cod_credito = B.cod_credito 
									  AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
									  AND A.val_resgatado > 0 
									  AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
									  AND B.cod_univend = U.cod_univend), 0) AS total_valor_a_pagar,
									  '' AS balanco
						FROM   unidadevenda U 
						WHERE  cod_empresa = $cod_empresa
						$andUnidade
						 ";
		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$balanço = ($row['total_valor_a_receber'] - $row['total_valor_a_pagar']);

			if ($balanço != 0) {
				$row['total_valor_a_pagar'] = fnValor($row['total_valor_a_pagar'], 2);
				$row['total_valor_a_receber'] = fnValor($row['total_valor_a_receber'], 2);
				$row['balanco'] = fnValor($balanço, 2);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"');
			}
		}
		fclose($arquivo);

		/*$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();

				  $balanço = ($row['total_valor_a_receber'] - $row['total_valor_a_pagar']);
				  
				  $cont = 0;

					if($balanço != 0){
						  foreach ($row as $objeto) {

							if($cont == 3 || $cont == 4){
								array_push($newRow, fnValor($objeto, 2));
							}else if($cont == 5){
								array_push($newRow, fnValor($balanço, 2));
							}else{
								array_push($newRow, $objeto);
							}
							  
							$cont++;
						  }
					}

				$balanço = 0;
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			*/

		break;
	case 'exportarDetalhes':

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT U.COD_UNIVEND as codigo_unidade_resgate, U.NOM_FANTASI as nome_unidade_resgate, U.NUM_CGCECPF as cpfcnpj_unidade_resgate,
					IFNULL((SELECT SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO) FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B
					WHERE 
						  A.COD_UNIVEND !=B.COD_UNIVEND AND
							A.COD_CREDITO=B.COD_CREDITO AND 
						  A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
						  A.VAL_RESGATADO > 0 AND 
						  B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND
							A.COD_UNIVEND=U.COD_UNIVEND),0) as total_valor_a_receber,																	
							IFNULL((SELECT SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO) FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B
						  WHERE 
						  A.COD_UNIVEND !=B.COD_UNIVEND AND
							A.COD_CREDITO=B.COD_CREDITO AND 
						  A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
						  A.VAL_RESGATADO > 0 AND 
						  B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND
							B.COD_UNIVEND=U.COD_UNIVEND),0) AS total_valor_a_pagar																	
					FROM UNIDADEVENDA U
					WHERE cod_empresa = $cod_empresa																  
						$andUnidade	
						 ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			// $balanco = ($row['total_valor_a_receber'] - $row['total_valor_a_pagar']);

			// array_push($newRow, "");
			// array_push($newRow, "");
			// array_push($newRow, $row['cpfcnpj_unidade_resgate']);
			// array_push($newRow, fnValor($row['total_valor_a_receber'],2));
			// array_push($newRow, fnValor($row['total_valor_a_pagar'],2));
			// array_push($newRow, fnValor($balanco,2));

			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");

			$array[] = $newRow;

			$newRow = array();

			$sql3 = "SELECT  CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,NOME_UNIDADE_RESGATE,SUM(VALOR) AS VALOR FROM (
					 (
							SELECT B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
								   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
							FROM   historico_resgate A, 
								   creditosdebitos B 
							WHERE  A.cod_univend != B.cod_univend 
								   AND A.cod_credito = B.cod_credito 
								   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								   AND A.val_resgatado > 0 
								   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
								   AND A.cod_univend = $row[codigo_unidade_resgate] 
							GROUP  BY B.cod_univend 
							ORDER  BY valor
							)
							
							UNION ALL
							
							(SELECT B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
								   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
							FROM   historico_resgate A, 
								   creditosdebitos_bkp B 
							WHERE  A.cod_univend != B.cod_univend 
								   AND A.cod_credito = B.cod_credito 
								   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								   AND A.val_resgatado > 0 
								   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
								   AND A.cod_univend = $row[codigo_unidade_resgate] 
							GROUP  BY B.cod_univend 
							ORDER  BY valor)
							) B
							GROUP BY CODIGO_UNIDADE_RESGATE";
			//$sql3 = "call sp_retorna_rel_compensacao_analitico('$dat_ini', '$dat_fim', $loja, $cod_empresa)";
			//fnEscreve($sql3);

			$arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);

			while ($row3 = mysqli_fetch_assoc($arrayQuery3)) {

				$newRow = array();

				array_push($newRow, $row3['NOME_UNIDADE_ORIGEM']);
				array_push($newRow, $row3['NOME_UNIDADE_RESGATE']);
				array_push($newRow, fnValor($row3['VALOR'], 2));
				array_push($newRow, "");

				$array[] = $newRow;
			}

			$sql2 = "SELECT UNIDADE, CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,SUM(VALOR) AS VALOR FROM (
					(SELECT A.cod_univend AS UNIDADE, 
							B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
						   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
					FROM   historico_resgate A, 
						   creditosdebitos B 
					WHERE  A.cod_univend != B.cod_univend 
						   AND A.cod_credito = B.cod_credito 
						   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						   AND A.val_resgatado > 0 
						   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
						   AND B.cod_univend = $row[codigo_unidade_resgate] 
					GROUP  BY A.cod_univend) 
					UNION  all
					(SELECT A.cod_univend AS UNIDADE,
							B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
						   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
					FROM   historico_resgate A, 
						   creditosdebitos_bkp B 
					WHERE  A.cod_univend != B.cod_univend 
						   AND A.cod_credito = B.cod_credito 
						   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						   AND A.val_resgatado > 0 
						   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
						   AND B.cod_univend = $row[codigo_unidade_resgate] 
					GROUP  BY A.cod_univend 
					ORDER  BY valor DESC)
					) A
					GROUP BY UNIDADE";

			// fnEscreve($sql2);

			$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

			while ($row2 = mysqli_fetch_assoc($arrayQuery2)) {

				$newRow = array();

				array_push($newRow, $row2['NOME_UNIDADE_ORIGEM']);
				array_push($newRow, $row2['NOME_UNIDADE_RESGATE']);
				array_push($newRow, "");
				array_push($newRow, "-" . fnValor($row2['VALOR'], 2));

				$array[] = $newRow;
			}
		}

		$arrayColumnsNames = array();

		// array_push($arrayColumnsNames, "LOJA");
		array_push($arrayColumnsNames, "LOJA DE ORIGEM");
		array_push($arrayColumnsNames, "LOJA DE RESGATE");
		// array_push($arrayColumnsNames, "CNPJ");
		array_push($arrayColumnsNames, "VALOR A RECEBER (LJ. RESGATE)");
		array_push($arrayColumnsNames, "VALOR A PAGAR (LJ. ORIGEM)");
		// array_push($arrayColumnsNames, "BALANCO");


		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'exportarClientes':

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT U.COD_UNIVEND as codigo_unidade_resgate, U.NOM_FANTASI as nome_unidade_resgate, U.NUM_CGCECPF as cpfcnpj_unidade_resgate,
					IFNULL((SELECT SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO) FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B
					WHERE 
						  A.COD_UNIVEND !=B.COD_UNIVEND AND
							A.COD_CREDITO=B.COD_CREDITO AND 
						  A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
						  A.VAL_RESGATADO > 0 AND 
						  B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND
							A.COD_UNIVEND=U.COD_UNIVEND),0) as total_valor_a_pagar,																	
							IFNULL((SELECT SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO) FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B
						  WHERE 
						  A.COD_UNIVEND !=B.COD_UNIVEND AND
							A.COD_CREDITO=B.COD_CREDITO AND 
						  A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
						  A.VAL_RESGATADO > 0 AND 
						  B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND
							B.COD_UNIVEND=U.COD_UNIVEND),0) AS total_valor_a_receber																	
					FROM UNIDADEVENDA U
					WHERE cod_empresa = $cod_empresa																  
						$andUnidade	
						 ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");

			$array[] = $newRow;

			$sql3 = "SELECT  COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,NOME_UNIDADE_RESGATE,SUM(VALOR) AS VALOR,DAT_CADASTR FROM (

						(SELECT A.COD_CLIENTE,C.NOM_CLIENTE,C.NUM_CARTAO,B.COD_UNIVEND AS CODIGO_UNIDADE_RESGATE, (
													SELECT C.NOM_FANTASI
													FROM UNIDADEVENDA C
													WHERE C.COD_UNIVEND=B.COD_UNIVEND) AS NOME_UNIDADE_ORIGEM, (
													SELECT C.NOM_FANTASI
													FROM UNIDADEVENDA C
													WHERE C.COD_UNIVEND=A.COD_UNIVEND) AS NOME_UNIDADE_RESGATE, 
													(SUM(VAL_RESGATADO)- SUM(VAL_ESTORNO)) AS VALOR,
													A.DAT_CADASTR 
													FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B,CLIENTES C
													WHERE A.COD_CLIENTE=C.COD_CLIENTE AND  
													      A.COD_UNIVEND !=B.COD_UNIVEND AND 
															A.COD_CREDITO=B.COD_CREDITO AND 
															A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59'  AND 
															A.VAL_RESGATADO > 0 AND 
															B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
															A.COD_UNIVEND = $row[codigo_unidade_resgate]
													GROUP BY B.COD_UNIVEND, A.COD_CLIENTE
													HAVING (SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO))>0 
													)
						UNION ALL
						(
						SELECT A.COD_CLIENTE,C.NOM_CLIENTE,C.NUM_CARTAO,B.COD_UNIVEND AS CODIGO_UNIDADE_RESGATE, (
													SELECT C.NOM_FANTASI
													FROM UNIDADEVENDA C
													WHERE C.COD_UNIVEND=B.COD_UNIVEND) AS NOME_UNIDADE_ORIGEM, (
													SELECT C.NOM_FANTASI
													FROM UNIDADEVENDA C
													WHERE C.COD_UNIVEND=A.COD_UNIVEND) AS NOME_UNIDADE_RESGATE, 
													(SUM(VAL_RESGATADO)- SUM(VAL_ESTORNO)) AS VALOR,
													A.DAT_CADASTR 
													FROM HISTORICO_RESGATE A,CREDITOSDEBITOS_BKP B,CLIENTES C
													WHERE A.COD_CLIENTE=C.COD_CLIENTE AND  
													      A.COD_UNIVEND !=B.COD_UNIVEND AND 
															A.COD_CREDITO=B.COD_CREDITO AND 
															A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59'  AND 
															A.VAL_RESGATADO > 0 AND 
															B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
															A.COD_UNIVEND = $row[codigo_unidade_resgate]
													GROUP BY B.COD_UNIVEND, A.COD_CLIENTE
													HAVING (SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO))>0 
						)
						) B

						 GROUP BY  CODIGO_UNIDADE_RESGATE,COD_CLIENTE	
						 ORDER BY DAT_CADASTR";

			// fnEscreve($sql3);

			$arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);

			// $array[] = $newRow;

			while ($row3 = mysqli_fetch_assoc($arrayQuery3)) {

				$newRow = array();

				array_push($newRow, $row3['NOM_CLIENTE']);
				array_push($newRow, $row3['NUM_CARTAO']);
				array_push($newRow, $row3['NOME_UNIDADE_ORIGEM']);
				array_push($newRow, $row3['NOME_UNIDADE_RESGATE']);
				array_push($newRow, fnValor($row3['VALOR'], 2));
				array_push($newRow, '');
				array_push($newRow, fnDataShort($row3['DAT_CADASTR']));

				$array[] = $newRow;
			}

			$sql2 = "SELECT  COD_UNIVEND,COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,SUM(VALOR) AS VALOR,DAT_CADASTR FROM (
						(SELECT 	A.COD_UNIVEND,
						         A.COD_CLIENTE,
									C.NOM_CLIENTE,
									C.NUM_CARTAO,
									B.COD_UNIVEND AS CODIGO_UNIDADE_RESGATE, 
									(SELECT C.NOM_FANTASI FROM UNIDADEVENDA C WHERE C.COD_UNIVEND=A.COD_UNIVEND) AS NOME_UNIDADE_RESGATE, 
									(SELECT C.NOM_FANTASI FROM UNIDADEVENDA C WHERE C.COD_UNIVEND=B.COD_UNIVEND) AS NOME_UNIDADE_ORIGEM, 
									(SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO)) AS VALOR,
									A.DAT_CADASTR 
									FROM HISTORICO_RESGATE A,CREDITOSDEBITOS B,CLIENTES C
									WHERE A.COD_CLIENTE=C.COD_CLIENTE AND  
											A.COD_UNIVEND !=B.COD_UNIVEND AND 
											A.COD_CREDITO=B.COD_CREDITO AND 
											A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
											A.VAL_RESGATADO > 0 AND 
											B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
											B.COD_UNIVEND = $row[codigo_unidade_resgate] 
									GROUP BY A.COD_UNIVEND, A.COD_CLIENTE
									HAVING (SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO))>0 
									)
						UNION ALL	
						(
						SELECT 	A.COD_UNIVEND,
						         A.COD_CLIENTE,
									C.NOM_CLIENTE,
									C.NUM_CARTAO,
									B.COD_UNIVEND AS CODIGO_UNIDADE_RESGATE, 
									(SELECT C.NOM_FANTASI FROM UNIDADEVENDA C WHERE C.COD_UNIVEND=A.COD_UNIVEND) AS NOME_UNIDADE_RESGATE, 
									(SELECT C.NOM_FANTASI FROM UNIDADEVENDA C WHERE C.COD_UNIVEND=B.COD_UNIVEND) AS NOME_UNIDADE_ORIGEM, 
									(SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO)) AS VALOR,
									A.DAT_CADASTR 
									FROM HISTORICO_RESGATE A,CREDITOSDEBITOS_BKP B,CLIENTES C
									WHERE A.COD_CLIENTE=C.COD_CLIENTE AND  
											A.COD_UNIVEND !=B.COD_UNIVEND AND 
											A.COD_CREDITO=B.COD_CREDITO AND 
											A.DAT_CADASTR between '$dat_ini 00:00:00' AND  '$dat_fim 23:59:59' AND 
											A.VAL_RESGATADO > 0 AND 
											B.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
											B.COD_UNIVEND = $row[codigo_unidade_resgate] 
									GROUP BY A.COD_UNIVEND, A.COD_CLIENTE
									HAVING (SUM(VAL_RESGATADO)-SUM(VAL_ESTORNO))>0 	
						)
						) B
								GROUP BY COD_UNIVEND, COD_CLIENTE	
								ORDER BY DAT_CADASTR";

			// fnEscreve($sql2);

			$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

			while ($row2 = mysqli_fetch_assoc($arrayQuery2)) {

				$newRow = array();

				array_push($newRow, $row2['NOM_CLIENTE']);
				array_push($newRow, $row2['NUM_CARTAO']);
				array_push($newRow, $row2['NOME_UNIDADE_ORIGEM']);
				array_push($newRow, $row2['NOME_UNIDADE_RESGATE']);
				array_push($newRow, '');
				array_push($newRow, '-' . fnValor($row2['VALOR'], 2));
				array_push($newRow, fnDataShort($row2['DAT_CADASTR']));

				$array[] = $newRow;
			}
		}

		$arrayColumnsNames = array();

		array_push($arrayColumnsNames, "CLIENTE");
		array_push($arrayColumnsNames, "CARTAO");
		array_push($arrayColumnsNames, "LOJA DE ORIGEM");
		array_push($arrayColumnsNames, "LOJA DE RESGATE");
		array_push($arrayColumnsNames, "VALOR A RECEBER (LJ. RESGATE)");
		array_push($arrayColumnsNames, "VALOR A PAGAR (LJ. ORIGEM)");
		array_push($arrayColumnsNames, "DT. RESGATE");


		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'abreDetail':

		$cod_empresa = $_GET['cod_empresa'];
		$dat_ini = fnDataSql($_GET['DAT_INI']);
		$dat_fim = fnDataSql($_GET['DAT_FIM']);
		$loja = $_GET['loja'];

		$sql4 = "SELECT  CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,NOME_UNIDADE_RESGATE,SUM(VALOR) AS VALOR FROM (
					 (
							SELECT B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
								   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
							FROM   historico_resgate A, 
								   creditosdebitos B 
							WHERE  A.cod_univend != B.cod_univend 
								   AND A.cod_credito = B.cod_credito 
								   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								   AND A.val_resgatado > 0 
								   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
								   AND A.cod_univend = $loja 
							GROUP  BY B.cod_univend 
							ORDER  BY valor
							)
							
							UNION ALL
							
							(SELECT B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
								   (SELECT C.nom_fantasi 
									FROM   unidadevenda C 
									WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
								   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
							FROM   historico_resgate A, 
								   creditosdebitos_bkp B 
							WHERE  A.cod_univend != B.cod_univend 
								   AND A.cod_credito = B.cod_credito 
								   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								   AND A.val_resgatado > 0 
								   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
								   AND A.cod_univend = $loja 
							GROUP  BY B.cod_univend 
							ORDER  BY valor)
							) B
							GROUP BY CODIGO_UNIDADE_RESGATE";



		//$sql3 = "call sp_retorna_rel_compensacao_analitico('$dat_ini', '$dat_fim', $loja, $cod_empresa)";
		//fnEscreve($sql4);

		$arrayQuery4 = mysqli_query(connTemp($cod_empresa, ''), $sql4);

?>
		<tr style="background-color: #fff;" class="detail_<?php echo $loja; ?>">
			<th></th>
			<th>Loja de Origem</th>
			<th>Loja de Resgate</th>
			<th colspan="3"></th>
		</tr>
		<?php

		//a pagar
		while ($qrListaUnive4 = mysqli_fetch_assoc($arrayQuery4)) {
			$colReceber	= "";
			$colPagar	= "<small>R$</small> " . fnValor($qrListaUnive4['VALOR'], 2);

		?>

			<tr style="background-color: #fff;" class="detail_<?php echo $loja; ?>">
				<td width="5%"></td>
				<td width="19%"><small><?php echo $qrListaUnive4['NOME_UNIDADE_ORIGEM']; ?></small></td>
				<td width="25%"><small><?php echo $qrListaUnive4['NOME_UNIDADE_RESGATE']; ?></small></td>
				<td width="10%" class="text-center"><small class="qtde_col2_<?php echo $qrListaUnive4['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colPagar; ?></small></td>
				<td width="19%" class="text-center"><small></small><small class="qtde_col4_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colReceber; ?></small></td>
				<td width="19%" class="text-center"><small></small><small class="qtde_col5_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"></small></td>
			</tr>

		<?php
		}

		$sql3 = "SELECT UNIDADE, CODIGO_UNIDADE_RESGATE,NOME_UNIDADE_RESGATE,NOME_UNIDADE_ORIGEM,SUM(VALOR) AS VALOR FROM (
					(SELECT A.cod_univend AS UNIDADE, 
							B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
						   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
					FROM   historico_resgate A, 
						   creditosdebitos B 
					WHERE  A.cod_univend != B.cod_univend 
						   AND A.cod_credito = B.cod_credito 
						   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						   AND A.val_resgatado > 0 
						   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
						   AND B.cod_univend = $loja 
					GROUP  BY A.cod_univend) 
					UNION  all
					(SELECT A.cod_univend AS UNIDADE,
							B.cod_univend AS CODIGO_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = A.cod_univend) AS NOME_UNIDADE_RESGATE, 
						   (SELECT C.nom_fantasi 
							FROM   unidadevenda C 
							WHERE  C.cod_univend = B.cod_univend) AS NOME_UNIDADE_ORIGEM, 
						   Sum(val_resgatado) - Sum(val_estorno)  AS VALOR 
					FROM   historico_resgate A, 
						   creditosdebitos_bkp B 
					WHERE  A.cod_univend != B.cod_univend 
						   AND A.cod_credito = B.cod_credito 
						   AND A.dat_cadastr BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						   AND A.val_resgatado > 0 
						   AND B.cod_statuscred IN( 0, 1, 2, 3, 4, 5, 7, 8, 9 ) 
						   AND B.cod_univend = $loja 
					GROUP  BY A.cod_univend 
					ORDER  BY valor DESC)
					) A
					GROUP BY UNIDADE ";



		//$sql3 = "call sp_retorna_rel_compensacao_analitico('$dat_ini', '$dat_fim', $loja, $cod_empresa)";
		//fnEscreve($sql3);

		$arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);

		//a pagar
		while ($qrListaUnive3 = mysqli_fetch_assoc($arrayQuery3)) {
			$colPagar	= "";
			$colReceber	= "<small>R$</small> -" . fnValor($qrListaUnive3['VALOR'], 2);

		?>

			<tr style="background-color: #fff;" class="detail_<?php echo $loja; ?>">
				<td width="5%"></td>
				<td width="19%"><small><?php echo $qrListaUnive3['NOME_UNIDADE_ORIGEM']; ?></small></td>
				<td width="25%"><small><?php echo $qrListaUnive3['NOME_UNIDADE_RESGATE']; ?></small></td>
				<td width="10%" class="text-center"><small class="qtde_col2_<?php echo $qrListaUnive3['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colPagar; ?></small></td>
				<td width="19%" class="text-center"><small></small><small class="qtde_col4_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colReceber; ?></small></td>
				<td width="19%" class="text-center"><small></small><small class="qtde_col5_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"></small></td>
			</tr>

<?php
		}
		break;
}
?>