<?php 

include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];	
$pagina = $_GET['idPage'];
$mostraXml = $_GET['mostrarXML'];
$cod_empresa = fnDecode($_GET['id']);

	//fnEscreve('chega');		

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$numCartao = $_POST['NUM_CARTAO'];
$nomCliente = $_POST['NOM_CLIENTE'];
$lojasSelecionadas = $_POST['LOJAS'];

	//fnEscreve('chega');


	//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
	$dat_ini = fnDataSql($dias30); 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
	$dat_fim = fnDataSql($hoje); 
}	
if (strlen($cod_univend ) == 0){
	$cod_univend = "9999"; 
}

$sql = "SELECT TIP_RETORNO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

	if($tip_retorno == 1){
		$casasDec = 0;
	}else{
		$casasDec = 2;
	}
}
	// //faz pesquisa por revenda (geral)
	// if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}	

switch ($opcao) {
	case 'exportar':
	
	$nomeRel = $_GET['nomeRel'];
	$arquivoCaminho = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

	if($nomCliente == ""){
		$andNome = " ";
	}else {
				//$andNome = "NOM_CLIENTE LIKE '%".$nomCliente."%' AND ";
		$andNome = "AND A.NOM_CLIENTE LIKE '%".$nomCliente."%' ";
		
	}							
	
	if($numCartao == ""){
		$andCartao = " ";
	}else {
				//$condicaoCartao = "B.NUM_CARTAO = $numCartao AND ";
		$andCartao = "AND A.NUM_CARTAO='$numCartao'";
	}
	
	$sql = "SELECT *,
	(SELECT SUM(VAL_CREDITO) 
		FROM CREDITOSDEBITOS
		WHERE COD_CLIENTE=tmpvendascreditos.COD_CLIENTE
		AND COD_STATUSCRED=3
		AND TIP_CREDITO='C'AND  FIND_IN_SET (COD_VENDA,COD_VENDAS)) AS VAL_CREDITOS


	FROM (
		SELECT B.dat_cadastr_ws,
		A.COD_CLIENTE,
		A.LOG_TERMO,
		A.NOM_CLIENTE,
		A.NUM_CARTAO,
		A.LOG_FUNCIONA,
		MIN(B.dat_cadastr_ws) AS DAT_CADASTR,
		SUM(VAL_TOTPRODU) AS VAL_TOTPRODU,
		SUM(B.VAL_TOTVENDA) AS VAL_TOTVENDA,
		GROUP_CONCAT( DISTINCT B.COD_VENDA SEPARATOR ',') COD_VENDAS,
		SUM(VAL_RESGATE) AS VAL_RESGATE,
		COUNT(*) AS QTD_VENDAS,
		d.COD_UNIVEND,
		d.NOM_FANTASI
		FROM CLIENTES A, VENDAS B
		LEFT JOIN unidadevenda d ON d.cod_univend = b.cod_univend
		WHERE A.COD_CLIENTE=B.COD_CLIENTE
		AND B.COD_STATUSCRED=3
		AND A.COD_EMPRESA = $cod_empresa
		AND cod_avulso = 2
		AND B.cod_univend IN($lojasSelecionadas)
		AND B.dat_cadastr_ws >= '$dat_ini 00:00:00'
		AND B.dat_cadastr_ws <= '$dat_fim 23:59:59'
		GROUP BY A.COD_CLIENTE
		ORDER BY B.dat_cadastr_ws DESC
	)tmpvendascreditos";
	
			//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

	$arquivo = fopen($arquivoCaminho, 'w',0);
	
	while($headers=mysqli_fetch_field($arrayQuery)){
		$CABECHALHO[]=$headers->name;
	}
	fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
	while ($row=mysqli_fetch_assoc($arrayQuery)){ 
		
		
		$row[VAL_TOTPRODU] = fnValor($row['VAL_TOTPRODU'],2);
		$row[VAL_TOTVENDA] = fnValor($row['VAL_TOTVENDA'],2);
		$row[VAL_QTD_RESGATE] = fnValor($row['VAL_QTD RESGATE'],2);
		$row['CREDITOS_PONTOS'] = fnValor($row['CREDITOS_PONTOS'],2);
		
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
		$array = array_map("utf8_decode", $row);
		fputcsv($arquivo, $array, ';', '"', '\n');
		
	}
	fclose($arquivo);

			/*

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 4 || $cont == 5){
						array_push($newRow, fnValor($objeto, 2));
					// Coloca # para o campos CODVENDAPDV
					}else if($cont == 6 || $cont == 7){
						array_push($newRow, fnValor($objeto, $casasDec));
					// Coloca # para o campos CODVENDAPDV
					}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
				echo "<pre>";
				print_r($row);
			}	echo "</pre>";
			
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
	?>