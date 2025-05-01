<?php
include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$opcao = "";
$itens_por_pagina = "";
$pagina = "";
$cod_empresa = "";
$casasDec = "";
$cod_univend = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$log_aceite = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$andAceite = "";
$nomeRel = "";
$arquivoCaminho = "";
$sql = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$sqlCel = "";
$arrayCel = "";
$qrCel = "";
$limpandostring = "";
$textolimpo = "";
$newRow = "";
$cont = "";
$objeto = "";
$NOM_ARRAY_UNIDADE = "";
$ARRAY_UNIDADE = "";
$loja = "";
$qrClientesTop100 = "";
$sexo = "";
$sqlTotal = "";
$arrayTotal = "";
$qrBuscaTotais = "";
$arrayColumnsNames = "";
$writer = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}




//echo fnDebug('true');

$opcao = getInput($_GET, 'opcao');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));
$casasDec = $_REQUEST['CASAS_DEC'];
$cod_univend = getInput($_POST, 'COD_UNIVEND');
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$lojasSelecionadas = getInput($_POST, 'LOJAS');

if (empty($_REQUEST['LOG_ACEITE'])) {
	$log_aceite = 'N';
} else {
	$log_aceite = $_REQUEST['LOG_ACEITE'];
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

$andAceite = "";

if ($log_aceite == 'S') {
	$andAceite = "AND CLI.LOG_TERMO = 'S'";
}


switch ($opcao) {
	case 'exportar':

		$nomeRel = getInput($_GET, 'nomeRel');
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


		/*$sql = "CALL SP_RELAT_TOP100CLIENTES(
				".$cod_empresa.",
				'".fnDataSql($dat_ini)."',
				'".fnDataSql($dat_fim)."',
				'".$lojasSelecionadas."'  
				) ";*/

		$sql = "SELECT COD_CLIENTE,
						NUM_CARTAO CARTAO,
						NUM_CGCECPF CPF,
						NOM_CLIENTE CLIENTE,   
						DES_EMAILUS EMAIL,
						DAT_NASCIME NASCIMENTO,
						'0' AS NUM_CELULAR,  
						COD_SEXOPES SEXO,
						COD_UNIVEND,
						NOM_FANTASI UNIDADE,					      
						SUM(VAL_TOTPRODU) AS VAL_COMPRAS,
						COUNT(DISTINCT(COD_VENDA)) AS QTD_COMPRAS,
						'0' AS TKT_MEDIO,
						SUM(VAL_CREDITO) VAL_CREDITO,						
						LOG_TERMO
						FROM (
								SELECT 
										CLI.COD_CLIENTE,
										CLI.NUM_CARTAO,
										CLI.NUM_CGCECPF,
										CLI.NOM_CLIENTE,   
										CLI.DES_EMAILUS,
										CLI.DAT_NASCIME,
										'0' AS NUM_CELULAR,  
										CLI.COD_SEXOPES,
										UNI.COD_UNIVEND,
										UNI.NOM_FANTASI,					      
										(SELECT  Min(CRED.dat_reproce) FROM creditosdebitos CRED 
										WHERE CRED.cod_venda = VEN.cod_venda
										AND CRED.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
										AND CRED.tip_credito = 'C' )  AS DAT_REPROCE,
										(SELECT val_credito FROM creditosdebitos CRED 
										WHERE CRED.cod_venda = VEN.cod_venda
										AND CRED.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
										AND CRED.tip_credito = 'C' GROUP BY CRED.COD_VENDA)  VAL_CREDITO,
										VEN.COD_VENDA,
										'0' AS TKT_MEDIO,
										VEN.VAL_TOTPRODU,
										CLI.LOG_TERMO																			 
								FROM vendas VEN
								INNER JOIN CLIENTES CLI ON CLI.COD_CLIENTE=VEN.COD_CLIENTE AND CLI.COD_EMPRESA=VEN.COD_EMPRESA
								LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=VEN.COD_UNIVEND											                                 
								WHERE DATE(DAT_CADASTR_WS) BETWEEN '" . fnDataSql($dat_ini) . "' AND '" . fnDataSql($dat_fim) . "'
								AND VEN.COD_EMPRESA=$cod_empresa
								AND VEN.COD_AVULSO=2
								AND  VEN.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) 
								AND VEN.COD_UNIVEND IN ($lojasSelecionadas)
								$andAceite
								) TMP_VENDA
								GROUP  BY COD_CLIENTE
								ORDER  BY VAL_COMPRAS DESC  
								LIMIT 100";

		fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			switch ($row['SEXO']) {
				case 1:
					$row['SEXO'] = "Masculino";
					break;
				case 2:
					$row['SEXO'] = "Feminino";
					break;
				default:
					$row['SEXO'] = "Indefinido";
					break;
			}
			$sqlCel = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $row[COD_CLIENTE]";
			$arrayCel = mysqli_query(connTemp($cod_empresa, ''), $sqlCel);
			$qrCel = mysqli_fetch_assoc($arrayCel);

			$row['NUM_CELULAR'] = $qrCel['NUM_CELULAR'];

			$row['TKT_MEDIO'] = fnvalor($row['VAL_COMPRAS'] / $row['QTD_COMPRAS'], 2);
			$row['VAL_CREDITO'] = fnValor($row['VAL_CREDITO'], 2);
			$row['VAL_COMPRAS'] = fnValor($row['VAL_COMPRAS'], 2);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		/*

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 10) {

					array_push($newRow, fnValor($objeto, 0));
				} else if ($cont == 13) {

					//$NOM_ARRAY_UNIDADE=(array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
					//$loja = "";
					//if($objeto != 0 && $objeto != ""){
					//$loja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
					$loja = $qrClientesTop100['NOM_FANTASI'];
					//}
					array_push($newRow, $loja);
				} else if ($cont == 6) {

					switch ($objeto) {

						case 1:
							$sexo = "Masculino";
							break;

						case 2:
							$sexo = "Feminino";
							break;

						default:
							$sexo = "Indefinido";
							break;
					}

					array_push($newRow, $sexo);
				} else if ($cont == 9) {

					array_push($newRow, fnValor($objeto, 2));
				
				}else if ($cont == 11){
					
					array_push($newRow,'R$'.fnValor($objeto, 2));
				} 
				else if ($cont == 4) {

					array_push($newRow, $objeto);

					$sqlCel = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $row['COD_CLIENTE']";
					$arrayCel = mysqli_query(connTemp($cod_empresa, ''), $sqlCel);
					$qrCel = mysqli_fetch_assoc($arrayCel);

					array_push($newRow, $qrCel['NUM_CELULAR']);
				} else if ($cont == 12) {

					$sqlTotal = "CALL total_wallet('$objeto', '$cod_empresa')";

					$arrayTotal = mysqli_query(connTemp($cod_empresa, ''), $sqlTotal);
					$qrBuscaTotais = mysqli_fetch_assoc($arrayTotal);

					array_push($newRow, fnvalor($qrBuscaTotais['CREDITO_DISPONIVEL'],2));
				} else {

					array_push($newRow, $objeto);
				}

				$cont++;
			}
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$cont = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {
			// Colunas que precisam mudar o seu título
			if ($cont == 0) {
				array_push($arrayColumnsNames, 'COD. CLIENTE');
			} else if ($cont == 4) {
				array_push($arrayColumnsNames, $row->name);
				array_push($arrayColumnsNames, 'CELULAR');
			} else {
				array_push($arrayColumnsNames, $row->name);
			}

			$cont++;
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();
		*/
		break;
	case 'paginar':

		break;
}
