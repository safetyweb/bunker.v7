<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$cod_categor = "";
$cod_subcate = "";
$cod_persona = "";
$cod_usuario = "";
$lojasSelecionadas = "";
$cod_produto = "";
$des_produto = "";
$cod_produtos = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$groupBy = "";
$andProdutos = "";
$andCategor = "";
$andSubCate = "";
$andVendedor = "";
$innerPersonas = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$unitarioMedio = "";


//echo fnDebug('true');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$cod_categor = fnLimpaCampoZero(@$_POST['COD_CATEGOR']);
$cod_subcate = fnLimpaCampoZero(@$_POST['COD_SUBCATE']);
$cod_persona = fnLimpaCampoZero(@$_POST['COD_PERSONA']);
$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO']);
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

if ($cod_produtos != 0 && $cod_produtos != "") {
	$cod_produto = $cod_produtos;
	$andProdutos = "AND B.COD_PRODUTO IN ( $cod_produto )  ";
}

if ($cod_categor != 0 && $cod_categor != "") {
	$andCategor = "AND C.COD_CATEGOR = $cod_categor";
}

if ($cod_subcate != 0 && $cod_subcate != "") {
	$andSubCate = "AND C.COD_SUBCATE = $cod_subcate";
}

if ($cod_usuario != 0 && $cod_usuario != "") {
	$andVendedor = "AND V.COD_USUARIO = $cod_usuario";
}

if ($cod_persona != 0 && $cod_persona != "") {
	$innerPersonas = "INNER JOIN personaclassifica p ON p.COD_CLIENTE=A.COD_CLIENTE AND p.COD_EMPRESA=A.COD_EMPRESA AND p.COD_PERSONA=$cod_persona";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 
	Count(*)  AS QTD_VENDA,
	A.DAT_CADASTR_WS,
	V.NOM_USUARIO NOM_VENDEDOR,
	ATEN.NOM_USUARIO NOM_ATENDENTE,
	D.NOM_CLIENTE,
	D.COD_CLIENTE,
	D.NUM_CGCECPF,
	A.COD_UNIVEND,
	E.NOM_FANTASI,
	Sum(B.QTD_PRODUTO)   QTD_PRODUTO,
	B.COD_PRODUTO,
	C.DES_PRODUTO,
	C.COD_EXTERNO,
	Sum(B.VAL_TOTITEM)  VAL_TOTITEM,
	Count(DISTINCT CASE  WHEN log_avulso = 'N' THEN A.COD_CLIENTE ELSE NULL end) NUM_CLIENTE,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.VAL_TOTITEM, 0)), 0) AS VAL_FIDELIZA,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.QTD_PRODUTO, 0)), 0) AS QTD_FIDELIZ
	FROM   VENDAS A
	INNER JOIN ITEMVENDA B ON B.COD_VENDA = A.COD_VENDA  AND A.COD_EMPRESA = B.COD_EMPRESA
	INNER JOIN produtocliente C  ON B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN CLIENTES D  ON A.COD_CLIENTE = D.COD_CLIENTE AND D.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN UNIDADEVENDA E  ON A.COD_UNIVEND = E.COD_UNIVEND AND E.COD_EMPRESA = A.COD_EMPRESA
	LEFT JOIN USUARIOS V ON V.COD_USUARIO=A.COD_VENDEDOR
	LEFT JOIN USUARIOS ATEN ON ATEN.COD_USUARIO=A.COD_ATENDENTE
	
	$innerPersonas
	
	WHERE  A.COD_EMPRESA = $cod_empresa
	AND Date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
	AND A.COD_UNIVEND IN($lojasSelecionadas)
	$andProdutos
	$andCategor
	$andSubCate
	$andVendedor
	GROUP  BY A.COD_CLIENTE
	ORDER  BY A.COD_VENDA DESC, A.COD_CLIENTE";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['COD_CLIENTE'] = $row['COD_CLIENTE'];
			$row['NOM_CLIENTE'] = $row['NOM_CLIENTE'];
			$row['NUM_CGCECPF'] = $row['NUM_CGCECPF'];
			$row['NOM_FANTASI'] = $row['NOM_FANTASI'];
			$row['NOM_VENDEDOR'] = $row['NOM_VENDEDOR'];
			$row['COD_PRODUTO'] = $row['COD_PRODUTO'];
			$row['COD_EXTERNO'] = $row['COD_EXTERNO'];
			$row['DES_PRODUTO'] = $row['DES_PRODUTO'];
			$row['QTD_PRODUTO'] = fnValor($row['QTD_PRODUTO'], 0);
			$row['VAL_TOTITEM'] = fnValor($row['VAL_TOTITEM'], 2);
			$row['unitarioMedio'] = fnValor(@$row['unitarioMedio'], 2);


			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);


		break;


	case 'detalhes':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 
	Count(*)  AS QTD_VENDA,
	A.DAT_CADASTR_WS,
	V.NOM_USUARIO NOM_VENDEDOR,
	ATEN.NOM_USUARIO NOM_ATENDENTE,
	D.NOM_CLIENTE,
	D.COD_CLIENTE,
	D.NUM_CGCECPF,
	A.COD_UNIVEND,
	E.NOM_FANTASI,
	Sum(B.QTD_PRODUTO)   QTD_PRODUTO,
	B.COD_PRODUTO,
	C.DES_PRODUTO,
	C.COD_EXTERNO,
	Sum(B.VAL_TOTITEM)  VAL_TOTITEM,
	Count(DISTINCT CASE  WHEN log_avulso = 'N' THEN A.COD_CLIENTE ELSE NULL end) NUM_CLIENTE,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.VAL_TOTITEM, 0)), 0) AS VAL_FIDELIZA,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.QTD_PRODUTO, 0)), 0) AS QTD_FIDELIZ
	FROM   VENDAS A
	INNER JOIN ITEMVENDA B ON B.COD_VENDA = A.COD_VENDA  AND A.COD_EMPRESA = B.COD_EMPRESA
	INNER JOIN produtocliente C  ON B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN CLIENTES D  ON A.COD_CLIENTE = D.COD_CLIENTE AND D.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN UNIDADEVENDA E  ON A.COD_UNIVEND = E.COD_UNIVEND AND E.COD_EMPRESA = A.COD_EMPRESA
	LEFT JOIN USUARIOS V ON V.COD_USUARIO=A.COD_VENDEDOR
	LEFT JOIN USUARIOS ATEN ON ATEN.COD_USUARIO=A.COD_ATENDENTE
	
	$innerPersonas
	
	WHERE  A.COD_EMPRESA = $cod_empresa
	AND Date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
	AND A.COD_UNIVEND IN($lojasSelecionadas)
	$andProdutos
	$andCategor
	$andSubCate
	$andVendedor
	GROUP  BY A.COD_VENDA , B.COD_PRODUTO
	ORDER BY D.NOM_CLIENTE, A.COD_VENDA DESC";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['COD_CLIENTE'] = $row['COD_CLIENTE'];
			$row['NOM_CLIENTE'] = $row['NOM_CLIENTE'];
			$row['NUM_CGCECPF'] = $row['NUM_CGCECPF'];
			$row['NOM_FANTASI'] = $row['NOM_FANTASI'];
			$row['NOM_VENDEDOR'] = $row['NOM_VENDEDOR'];
			$row['COD_PRODUTO'] = $row['COD_PRODUTO'];
			$row['COD_EXTERNO'] = $row['COD_EXTERNO'];
			$row['DES_PRODUTO'] = $row['DES_PRODUTO'];
			$row['QTD_PRODUTO'] = fnValor($row['QTD_PRODUTO'], 0);
			$row['VAL_TOTITEM'] = fnValor($row['VAL_TOTITEM'], 2);
			$row['unitarioMedio'] = fnValor(@$row['unitarioMedio'], 2);


			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
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
	$innerPersonas
	WHERE A.COD_EMPRESA = $cod_empresa
	AND date(A.DAT_CADASTR_WS) between '$dat_ini' AND '$dat_fim' 
	AND A.COD_UNIVEND IN($lojasSelecionadas)
	$andProdutos
	$andCategor
	$andSubCate
	$andVendedor
	GROUP BY A.COD_UNIVEND,B.COD_PRODUTO";

		//fnEscreve($sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT 
	Count(*)  AS QTD_VENDA,
	A.DAT_CADASTR_WS,
	V.NOM_USUARIO NOM_VENDEDOR,
	ATEN.NOM_USUARIO NOM_ATENDENTE,
	D.NOM_CLIENTE,
	D.COD_CLIENTE,
	D.NUM_CGCECPF,
	A.COD_UNIVEND,
	E.NOM_FANTASI,
	Sum(B.QTD_PRODUTO)   QTD_PRODUTO,
	B.COD_PRODUTO,
	C.DES_PRODUTO,
	C.COD_EXTERNO,
	Sum(B.VAL_TOTITEM)  VAL_TOTITEM,
	Count(DISTINCT CASE  WHEN log_avulso = 'N' THEN A.COD_CLIENTE ELSE NULL end) NUM_CLIENTE,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.VAL_TOTITEM, 0)), 0) AS VAL_FIDELIZA,
	Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.QTD_PRODUTO, 0)), 0) AS QTD_FIDELIZ
	FROM   VENDAS A
	INNER JOIN ITEMVENDA B ON B.COD_VENDA = A.COD_VENDA  AND A.COD_EMPRESA = B.COD_EMPRESA
	INNER JOIN produtocliente C  ON B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN CLIENTES D  ON A.COD_CLIENTE = D.COD_CLIENTE AND D.COD_EMPRESA = A.COD_EMPRESA
	INNER JOIN UNIDADEVENDA E  ON A.COD_UNIVEND = E.COD_UNIVEND AND E.COD_EMPRESA = A.COD_EMPRESA
	LEFT JOIN USUARIOS V ON V.COD_USUARIO=A.COD_VENDEDOR
	LEFT JOIN USUARIOS ATEN ON ATEN.COD_USUARIO=A.COD_ATENDENTE

	$innerPersonas

	WHERE  A.COD_EMPRESA = $cod_empresa
	AND Date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
	AND A.COD_UNIVEND IN($lojasSelecionadas)
	$andProdutos
	$andCategor
	$andSubCate
	$andVendedor
	GROUP  BY A.COD_CLIENTE
	ORDER  BY A.COD_VENDA DESC, A.COD_CLIENTE
	LIMIT $inicio,$itens_por_pagina";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			$unitarioMedio = $qrListaVendas['VAL_FIDELIZA'] / $qrListaVendas['QTD_FIDELIZ'];
?>
			<tr>
				<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_VENDEDOR']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['DES_PRODUTO']; ?></small></td>
				<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'], 0); ?></small></b></td>
				<td class="text-center"><b><small><small>R$</small><?php echo fnValor($qrListaVendas['VAL_TOTITEM'], 2); ?></small></b></td>
				<td class="text-center"><small><small>R$</small> <?php echo fnValor($unitarioMedio, 2); ?></small></td>
				<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_VENDA'], 0); ?></small></td>
				<td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
			</tr>
<?php

			$countLinha++;
		}

		break;
}
?>