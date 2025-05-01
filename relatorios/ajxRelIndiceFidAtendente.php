<?php

include '../_system/_functionsMain.php';

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		if (@$_POST['LOG_ONLINE'] == 'S') {
			$sql = "SELECT COD_ATENDENTE,
										NOM_ATENDENTE,
										COD_UNIVEND,
										NOM_FANTASI,
										COD_VENDEDOR,
										COD_EXTERNO,
										SUM(QTD_TOTAVULSA) + SUM(QTD_TOTFIDELIZ) AS QTD_TOTVENDA,
										SUM(QTD_TOTAVULSA) QTD_TOTAVULSA,
										SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
										round(((SUM(QTD_TOTFIDELIZ) / (SUM(QTD_TOTAVULSA) + sum(QTD_TOTFIDELIZ))) * 100),2) AS PCT_FIDELIZADO,
										IFNULL(TRUNCATE(SUM(VAL_TOTFIDELIZ), 2), 0) VAL_TOTFIDELIZ,
										IFNULL(TRUNCATE(SUM(VAL_TOTVENDA), 2), 0) VAL_TOTVENDA,
										IFNULL(TRUNCATE(SUM(VAL_RESGATE), 2), 0) VAL_RESGATE,
										IFNULL(TRUNCATE(SUM(QTD_RESGATE), 2), 0) QTD_RESGATE,
										IFNULL(TRUNCATE(SUM(VAL_VINCULADO), 2), 0) VAL_VINCULADO,
										IFNULL(TRUNCATE(SUM(CREDITOS_GERADO), 2), 0) CREDITOS_GERADO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC), 2), 0) VAL_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC), 2), 0) QTD_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC_INATIVO), 2), 0) QTD_VENDA_CLIENTE_INATIVO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC_INATIVO), 2), 0) VAL_VENDA_CLIENTE_INATIVO

										FROM
										(
											SELECT A.COD_ATENDENTE,
											US.NOM_USUARIO AS NOM_ATENDENTE,
											A.COD_UNIVEND,
											U.NOM_FANTASI,
											A.COD_VENDEDOR,
											US.COD_EXTERNO,
											'0' QTD_TOTVENDA,
											CASE
											WHEN A.COD_AVULSO = 1 THEN A.QTD_VENDA
											ELSE '0'
											END AS QTD_TOTAVULSA,
											CASE
											WHEN A.COD_AVULSO = 2 THEN 1
											ELSE '0'
											END AS QTD_TOTFIDELIZ,
											CASE
											WHEN A.COD_AVULSO = 2 THEN A.VAL_TOTVENDA
											ELSE '0.00'
											END AS VAL_TOTFIDELIZ,
											'0.00' PCT_FIDELIZADO,
											A.VAL_TOTVENDA,

											(SELECT SUM(VLR.VAL_CREDITO)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS VAL_RESGATE,

											(SELECT IFNULL(count(VLR.COD_VENDA), 0)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS QTD_RESGATE,

											(SELECT VLR.VAL_VINCULADO
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS VAL_VINCULADO,

											(SELECT VLR.val_credito
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'C'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS CREDITOS_GERADO,

											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTPRODU ELSE 0 END   VAL_TOTPRODU_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' AND c.LOG_ESTATUS='N' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC_INATIVO,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTVENDA  and c.LOG_ESTATUS='N' ELSE 0 END   VAL_TOTPRODU_FUNC_INATIVO

											FROM VENDAS A
											INNER JOIN unidadevenda U ON U.COD_UNIVEND=A.COD_UNIVEND
											left JOIN clientes c ON c.COD_CLIENTE=A.COD_CLIENTE
											LEFT JOIN usuarios US ON (US.COD_USUARIO=A.COD_ATENDENTE)
											WHERE date(A.DAT_CADASTR_WS) BETWEEN CURDATE() AND CURDATE()
											AND A.COD_STATUSCRED IN(0,
												1,
												2,
												3,
												4,
												5,
												7,
												8,
												9)
											AND A.COD_EMPRESA = $cod_empresa
											AND A.COD_UNIVEND IN ($lojasSelecionadas)

											) TMPVENDAS
										WHERE COD_ATENDENTE >= 0
										GROUP BY COD_ATENDENTE,
										COD_UNIVEND
										ORDER BY COD_UNIVEND,
										PCT_FIDELIZADO DESC";
		} else {

			// $sql = "CALL SP_RELAT_INDICE_FIDELIZACAO_ATENDENTE ('$dat_ini', '$dat_fim', $cod_empresa, '$lojasSelecionadas')";		
			$sql = "SELECT COD_ATENDENTE,
										NOM_ATENDENTE,
										COD_UNIVEND,
										NOM_FANTASI,
										COD_VENDEDOR,
										COD_EXTERNO,
										SUM(QTD_TOTAVULSA) + SUM(QTD_TOTFIDELIZ) AS QTD_TOTVENDA,
										SUM(QTD_TOTAVULSA) QTD_TOTAVULSA,
										SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
										round(((SUM(QTD_TOTFIDELIZ) / (SUM(QTD_TOTAVULSA) + sum(QTD_TOTFIDELIZ))) * 100),2) AS PCT_FIDELIZADO,
										IFNULL(TRUNCATE(SUM(VAL_TOTFIDELIZ), 2), 0) VAL_TOTFIDELIZ,
										IFNULL(TRUNCATE(SUM(VAL_TOTVENDA), 2), 0) VAL_TOTVENDA,
										IFNULL(TRUNCATE(SUM(VAL_RESGATE), 2), 0) VAL_RESGATE,
										IFNULL(TRUNCATE(SUM(QTD_RESGATE), 2), 0) QTD_RESGATE,
										IFNULL(TRUNCATE(SUM(VAL_VINCULADO), 2), 0) VAL_VINCULADO,
										IFNULL(TRUNCATE(SUM(CREDITOS_GERADO), 2), 0) CREDITOS_GERADO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC), 2), 0) VAL_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC), 2), 0) QTD_VENDA_CLIENTE_FUNCIONARIO,
										IFNULL(TRUNCATE(SUM(QTD_VENDA_FUNC_INATIVO), 2), 0) QTD_VENDA_CLIENTE_INATIVO,
										IFNULL(TRUNCATE(SUM(VAL_TOTPRODU_FUNC_INATIVO), 2), 0) VAL_VENDA_CLIENTE_INATIVO

										FROM
										(
											SELECT A.COD_ATENDENTE,
											US.NOM_USUARIO AS NOM_ATENDENTE,
											A.COD_UNIVEND,
											U.NOM_FANTASI,
											A.COD_VENDEDOR,
											US.COD_EXTERNO,
											'0' QTD_TOTVENDA,
											CASE
											WHEN A.COD_AVULSO = 1 THEN A.QTD_VENDA
											ELSE '0'
											END AS QTD_TOTAVULSA,
											CASE
											WHEN A.COD_AVULSO = 2 THEN 1
											ELSE '0'
											END AS QTD_TOTFIDELIZ,
											CASE
											WHEN A.COD_AVULSO = 2 THEN A.VAL_TOTVENDA
											ELSE '0.00'
											END AS VAL_TOTFIDELIZ,
											'0.00' PCT_FIDELIZADO,
											A.VAL_TOTVENDA,

											(SELECT SUM(VLR.VAL_CREDITO)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS VAL_RESGATE,

											(SELECT IFNULL(count(VLR.COD_VENDA), 0)
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS QTD_RESGATE,

											(SELECT VLR.VAL_VINCULADO
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'D'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS VAL_VINCULADO,

											(SELECT VLR.val_credito
												FROM CREDITOSDEBITOS VLR
												WHERE VLR.COD_EMPRESA=A.COD_EMPRESA
												AND VLR.COD_VENDA=A.COD_VENDA
												AND VLR.TIP_CREDITO = 'C'
												AND VLR.COD_STATUSCRED IN(0,
													1,
													2,
													3,
													4,
													5,
													7,
													8,
													9)
												GROUP BY VLR.COD_VENDA) AS CREDITOS_GERADO,

											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTPRODU ELSE 0 END   VAL_TOTPRODU_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' AND c.LOG_ESTATUS='N' then A.QTD_VENDA ELSE 0 END   QTD_VENDA_FUNC_INATIVO,
											case when  A.COD_AVULSO= 2 AND c.LOG_FUNCIONA='S' then A.VAL_TOTVENDA  and c.LOG_ESTATUS='N' ELSE 0 END   VAL_TOTPRODU_FUNC_INATIVO

											FROM VENDAS A
											INNER JOIN unidadevenda U ON U.COD_UNIVEND=A.COD_UNIVEND
											left JOIN clientes c ON c.COD_CLIENTE=A.COD_CLIENTE
											LEFT JOIN usuarios US ON (US.COD_USUARIO=A.COD_ATENDENTE)
											WHERE date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
											AND A.COD_STATUSCRED IN(0,
												1,
												2,
												3,
												4,
												5,
												7,
												8,
												9)
											AND A.COD_EMPRESA = $cod_empresa
											AND A.COD_UNIVEND IN ($lojasSelecionadas)

											) TMPVENDAS
										WHERE COD_ATENDENTE >= 0
										GROUP BY COD_ATENDENTE,
										COD_UNIVEND
										ORDER BY COD_UNIVEND,
										PCT_FIDELIZADO DESC";
		}

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		// fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VAL_VINCULADO'] = fnValor($row['VAL_VINCULADO'], 2);
			$row['VAL_TOTFIDELIZ'] = fnValor($row['VAL_TOTFIDELIZ'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			$row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);
			$row['CREDITOS_GERADOS'] = fnValor(@$row['CREDITOS_GERADOS'], 2);
			$row['VAL_VENDA_CLIENTE_FUNCIONARIO'] = fnValor($row['VAL_VENDA_CLIENTE_FUNCIONARIO'], 2);
			$row['VAL_VENDA_CLIENTE_INATIVO'] = fnValor($row['VAL_VENDA_CLIENTE_INATIVO'], 2);
			$row['PCT_FIDELIZADO'] = fnValor($row['PCT_FIDELIZADO'], 2) . "%";
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			// fputcsv($arquivo, $array, ';', '"', '\n');
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		/*$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 9 || $cont == 10 || $cont == 11 || $cont == 12 || $cont == 14 || $cont == 15){
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

			$writer->close();
			*/
		break;
}
