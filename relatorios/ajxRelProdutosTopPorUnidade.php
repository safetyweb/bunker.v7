<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$andProdutos = '';
$andCategor = '';
$andProdutos = '';
$andCategor = '';

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_categor = fnLimpaCampoZero(@$_POST['COD_CATEGOR']);
$cod_subcate = fnLimpaCampoZero(@$_POST['COD_SUBCATE']);
$lojasSelecionadas = @$_POST['LOJAS'];

$cod_produto = @$_POST['COD_PRODUTO'];
$des_produto = @$_POST['DES_PRODUTO'];

$cod_produtos = ltrim(rtrim(fnlimpacampo(@$_POST['MULTI_PROD']), ","), ",");

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

$groupBy = "GROUP BY A.COD_UNIVEND,B.COD_PRODUTO";

if ($cod_produtos != 0) {
	$cod_produto = $cod_produtos;
	$groupBy = "GROUP BY B.COD_PRODUTO";
}

if ($cod_produtos != 0 && $cod_produtos != "") {
	$cod_produto = str_replace(',,', ',', $cod_produtos);
	$andProdutos = "AND B.COD_PRODUTO IN ( $cod_produto )  ";
}


if ($cod_categor != 0 && $cod_categor != "") {
	$andCategor = "AND C.COD_CATEGOR = $cod_categor";
}

if ($cod_subcate != 0 && $cod_subcate != "") {
	$andCategor = "AND C.COD_SUBCATE = $cod_subcate";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 
                    A.COD_UNIVEND, 
                    E.NOM_FANTASI, 
                    SUM(B.QTD_PRODUTO) QTD_PRODUTO,
                    B.COD_PRODUTO, 
                    C.DES_PRODUTO, 
                    C.COD_EXTERNO,
                    SUM(B.VAL_TOTITEM) VAL_TOTITEM,
                   	COUNT(DISTINCT	case when LOG_AVULSO='N' then A.COD_CLIENTE ELSE NULL END) NUM_CLIENTE,
                    IFNULL(SUM(if(D.LOG_AVULSO='N',B.VAL_TOTITEM,0)),0) AS VAL_FIDELIZA,
                    IFNULL(SUM(if(D.LOG_AVULSO='N',B.QTD_PRODUTO,0)),0) AS QTD_FIDELIZ,
                    COUNT(*) AS QTD_VENDAS
                FROM VENDAS A
                INNER JOIN itemvenda B ON B.COD_VENDA=A.COD_VENDA AND A.COD_EMPRESA=B.COD_EMPRESA
                INNER JOIN PRODUTOCLIENTE C ON 	B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA=A.COD_EMPRESA
                INNER JOIN CLIENTES D ON A.COD_CLIENTE=D.COD_CLIENTE  AND D.COD_EMPRESA=A.COD_EMPRESA
                INNER JOIN unidadevenda E ON 	A.COD_UNIVEND=E.COD_UNIVEND AND  E.COD_EMPRESA=A.COD_EMPRESA

                WHERE A.COD_EMPRESA = $cod_empresa
                AND date(A.DAT_CADASTR) between '$dat_ini' AND '$dat_fim' 
                AND A.COD_UNIVEND IN($lojasSelecionadas)
                	 $andProdutos
                     $andCategor
                GROUP BY A.COD_UNIVEND,B.COD_PRODUTO
                ORDER BY QTD_PRODUTO DESC ";

		// fnEscreve($sql);	

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['VAL_TOTITEM'] = fnValor($row['VAL_TOTITEM'], 2);
			$row['VAL_FIDELIZA'] = fnValor($row['VAL_FIDELIZA'], 2);
			$row['QTD_FIDELIZ'] = fnValor($row['QTD_FIDELIZ'], 2);
			$row['QTD_PRODUTO'] = fnValor($row['QTD_PRODUTO'], 2);

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);


		break;

	case 'detalhes':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sqlDet = "SELECT 
					       A.COD_VENDA,
					       E.nom_fantasi AS LOJA,
							 D.NOM_CLIENTE,
							 D.NUM_CGCECPF AS CPF,
							 A.DAT_CADASTR_WS AS DAT_VENDA,
							 A.VAL_TOTPRODU,
							 A.VAL_DESCONTO,
							 A.VAL_TOTVENDA,   
							 GROUP_CONCAT(B.QTD_PRODUTO SEPARATOR '|_|') QTD_PRODUTO,
							 GROUP_CONCAT(B.VAL_UNITARIO SEPARATOR '|_|') VAL_UNITARIO,
					       GROUP_CONCAT(B.VAL_TOTITEM SEPARATOR '|_|') VAL_TOTITEM,
					       GROUP_CONCAT(B.VAL_DESCONTO SEPARATOR '|_|') VAL_DESCONTO_ITEM,      
					       
							 GROUP_CONCAT(C.DES_PRODUTO SEPARATOR '|_|') DES_PRODUTO,
					       GROUP_CONCAT(C.COD_EXTERNO  SEPARATOR '|_|') COD_EXTERNO      
					       
					FROM   vendas A
					       INNER JOIN itemvenda B  ON B.cod_venda = A.cod_venda   AND A.cod_empresa = B.cod_empresa
					       INNER JOIN produtocliente C  ON B.cod_produto = C.cod_produto  AND C.cod_empresa = A.cod_empresa
					       INNER JOIN clientes D  ON A.cod_cliente = D.cod_cliente  AND D.cod_empresa = A.cod_empresa
					       INNER JOIN unidadevenda E  ON A.cod_univend = E.cod_univend AND E.cod_empresa = A.cod_empresa
					WHERE  A.cod_empresa = $cod_empresa
					       AND DATE(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
					       AND A.cod_univend IN($lojasSelecionadas)
					       AND D.LOG_AVULSO = 'N'
					       $andProdutos
                     		  $andCategor                     
					GROUP  BY A.COD_VENDA
					         
					ORDER  BY A.COD_VENDA desc";

		// fnEscreve($opcao);	
		// fnEscreve($sqlDet);	

		$arrayDet = mysqli_query(connTemp($cod_empresa, ''), $sqlDet);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayDet)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');


		while ($rowDet = mysqli_fetch_assoc($arrayDet)) {

			$arrItem = array();

			$rowDet['VAL_TOTPRODU'] = fnValor($rowDet['VAL_TOTPRODU'], 2);
			$rowDet['VAL_DESCONTO'] = fnValor($rowDet['VAL_DESCONTO'], 2);
			$rowDet['VAL_TOTVENDA'] = fnValor($rowDet['VAL_TOTVENDA'], 2);
			$rowDet['DAT_VENDA'] = fnDataFull($rowDet['DAT_VENDA']);
			$arrQTD_PRODUTO = explode('|_|', $rowDet['QTD_PRODUTO']);
			$arrVAL_UNITARIO = explode('|_|', $rowDet['VAL_UNITARIO']);
			$arrVAL_TOTITEM = explode('|_|', $rowDet['VAL_TOTITEM']);
			$arrVAL_DESCONTO_ITEM = explode('|_|', $rowDet['VAL_DESCONTO_ITEM']);
			$arrDES_PRODUTO = explode('|_|', $rowDet['DES_PRODUTO']);
			$arrCOD_EXTERNO = explode('|_|', $rowDet['COD_EXTERNO']);

			for ($i = 0; $i < count($arrQTD_PRODUTO); $i++) { // Começando com $i = 0 para acessar todos os índices corretamente

				$arrItem[] = array(
					"COD_VENDA" => isset($rowDet['COD_VENDA']) ? $rowDet['COD_VENDA'] : '',
					"LOJA" => '',
					"NOM_CLIENTE" => '',
					"CPF" => '',
					"DAT_VENDA" => '',
					"VAL_TOTPRODU" => 0,
					"VAL_DESCONTO" => isset($rowDet['VAL_DESCONTO']) ? $rowDet['VAL_DESCONTO'] : 0,
					"VAL_TOTVENDA" => 0,
					"QTD_PRODUTO" => isset($arrQTD_PRODUTO[$i]) ? fnValor($arrQTD_PRODUTO[$i], 2) : 0,
					"VAL_UNITARIO" => isset($arrVAL_UNITARIO[$i]) ? fnValor($arrVAL_UNITARIO[$i], 2) : 0,
					"VAL_TOTITEM" => isset($arrVAL_TOTITEM[$i]) ? fnValor($arrVAL_TOTITEM[$i], 2) : 0,
					"VAL_DESCONTO_ITEM" => isset($arrVAL_DESCONTO_ITEM[$i]) ? fnValor($arrVAL_DESCONTO_ITEM[$i], 2) : 0,
					"DES_PRODUTO" => isset($arrDES_PRODUTO[$i]) ? $arrDES_PRODUTO[$i] : '',
					"COD_EXTERNO" => isset($arrCOD_EXTERNO[$i]) ? $arrCOD_EXTERNO[$i] : ''
				);
			}

			if (count($arrQTD_PRODUTO) === 1) { // Verifica se há apenas um produto no array
				$rowDet['QTD_PRODUTO'] = isset($arrQTD_PRODUTO[0]) ? fnValor($arrQTD_PRODUTO[0], 2) : 0;
				$rowDet['VAL_UNITARIO'] = isset($arrVAL_UNITARIO[0]) ? fnValor($arrVAL_UNITARIO[0], 2) : 0;
				$rowDet['VAL_TOTITEM'] = isset($arrVAL_TOTITEM[0]) ? fnValor($arrVAL_TOTITEM[0], 2) : 0;
				$rowDet['VAL_DESCONTO_ITEM'] = isset($arrVAL_DESCONTO_ITEM[0]) ? fnValor($arrVAL_DESCONTO_ITEM[0], 2) : 0;
			}

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($rowDet)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $rowDet);
			fputcsv($arquivo, $array, ';', '"');

			if (@$arrQTD_PRODUTO[1]) {

				for ($i = 1; $i < count($arrQTD_PRODUTO); $i++) {
					// echo "<pre>";
					// print_r($arrItem);
					// echo "</pre>";
					fputcsv($arquivo, $arrItem[$i - 1], ';', '"');
				}
			}
		}
		fclose($arquivo);

		break;

	case 'paginar':

		$sql = "SELECT 1
                    FROM VENDAS A
                    INNER JOIN itemvenda B ON B.COD_VENDA=A.COD_VENDA AND A.COD_EMPRESA=B.COD_EMPRESA
                    INNER JOIN PRODUTOCLIENTE C ON 	B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA=A.COD_EMPRESA
                    INNER JOIN CLIENTES D ON A.COD_CLIENTE=D.COD_CLIENTE  AND D.COD_EMPRESA=A.COD_EMPRESA
                    INNER JOIN unidadevenda E ON 	A.COD_UNIVEND=E.COD_UNIVEND AND  E.COD_EMPRESA=A.COD_EMPRESA

                    WHERE A.COD_EMPRESA = $cod_empresa
                        AND date(A.DAT_CADASTR) between '$dat_ini' AND '$dat_fim' 
                        AND A.COD_UNIVEND IN($lojasSelecionadas)
                    	 $andProdutos
                         $andCategor
                    GROUP BY A.COD_UNIVEND,B.COD_PRODUTO";

		//fnEscreve($sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT 
                        COUNT(*) AS QTD_VENDA,
                        A.COD_UNIVEND, 
                        E.NOM_FANTASI, 
                        SUM(B.QTD_PRODUTO) QTD_PRODUTO,
                        B.COD_PRODUTO, 
                        C.DES_PRODUTO, 
                        C.COD_EXTERNO,
                        SUM(B.VAL_TOTITEM) VAL_TOTITEM,
                       	COUNT(DISTINCT	case when LOG_AVULSO='N' then A.COD_CLIENTE ELSE NULL END) NUM_CLIENTE,
                        IFNULL(SUM(if(D.LOG_AVULSO='N',B.VAL_TOTITEM,0)),0) AS VAL_FIDELIZA,
                        IFNULL(SUM(if(D.LOG_AVULSO='N',B.QTD_PRODUTO,0)),0) AS QTD_FIDELIZ
                    FROM VENDAS A
                    INNER JOIN itemvenda B ON B.COD_VENDA=A.COD_VENDA AND A.COD_EMPRESA=B.COD_EMPRESA
                    INNER JOIN PRODUTOCLIENTE C ON 	B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA=A.COD_EMPRESA
                    INNER JOIN CLIENTES D ON A.COD_CLIENTE=D.COD_CLIENTE  AND D.COD_EMPRESA=A.COD_EMPRESA
                    INNER JOIN unidadevenda E ON 	A.COD_UNIVEND=E.COD_UNIVEND AND  E.COD_EMPRESA=A.COD_EMPRESA

                    WHERE A.COD_EMPRESA = $cod_empresa
                        AND date(A.DAT_CADASTR) between '$dat_ini' AND '$dat_fim' 
                        AND A.COD_UNIVEND IN($lojasSelecionadas)
                    	 $andProdutos
                         $andCategor
                    GROUP BY A.COD_UNIVEND,B.COD_PRODUTO
                    ORDER BY QTD_PRODUTO DESC 
                    limit $inicio,$itens_por_pagina";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			$unitarioMedio = $qrListaVendas['VAL_FIDELIZA'] / $qrListaVendas['QTD_FIDELIZ'];
?>
			<tr>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></td>
				<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'], 0); ?></small></b></td>
				<td class="text-center"><small><?php echo fnValor($qrListaVendas['NUM_CLIENTE'], 0); ?></small></td>
				<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_FIDELIZ'], 0); ?></small></b></td>
				<td class="text-center"><b><small><small>R$</small><?php echo fnValor($qrListaVendas['VAL_TOTITEM'], 2); ?></small></b></td>
				<td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_FIDELIZA'], 2); ?></small></td>
				<td class="text-center"><small><small>R$</small> <?php echo fnValor($unitarioMedio, 2); ?></small></td>
				<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_VENDA'], 0); ?></small></td>
			</tr>
<?php

			$countLinha++;
		}

		break;
}
?>