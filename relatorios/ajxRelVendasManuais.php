<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$mostraXml = @$_GET['mostrarXML'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$num_cgcecpf = @$_POST['NUM_CGCECPF'];


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

if ($num_cgcecpf == "") {
	$andCpf = " ";
} else {
	$andCpf = "B.NUM_CGCECPF = $num_cgcecpf AND ";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql2 = "SELECT A.DAT_CADASTR,
						A.COD_VENDAPDV PDV,
						A.COD_UNIVEND,
						uni.NOM_FANTASI Nome_Loja,
						A.COD_MAQUINA,															
						B.NUM_CGCECPF CPF,
						A.COD_USUCADA,
						C.NOM_USUARIO Nome_Usuario,
						A.COD_CUPOM Cupom,
						SUM(case when CRE.TIP_CREDITO='C' THEN CRE.VAL_CREDITO ELSE 0 END) CREDITO,
						A.VAL_TOTVENDA
				FROM VENDAS A
				left join CLIENTES B on A.COD_CLIENTE=B.COD_CLIENTE
				LEFT JOIN usuarios C ON A.COD_USUCADA=C.COD_USUARIO
				LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND		
				LEFT JOIN creditosdebitos CRE ON CRE.COD_VENDA=A.COD_VENDA											
				WHERE A.COD_ORCAMENTO >0 
				AND DATE(A.DAT_CADASTR) between '$dat_ini' and '$dat_fim' AND 
				$andCpf
				A.COD_UNIVEND IN($lojasSelecionadas) AND
				A.COD_EMPRESA=$cod_empresa
				GROUP BY A.COD_VENDA
				ORDER BY A.DAT_CADASTR DESC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			$row['CREDITO'] = fnValor($row['CREDITO'], 2);

			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}
		fclose($arquivo);

		break;
	case 'paginar':

		$sql = "SELECT count(*) as contador from VENDAS 
	WHERE 
	COD_ORCAMENTO >0 AND
	DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
	AND DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' AND
	COD_UNIVEND IN($lojasSelecionadas) AND
	COD_EMPRESA=$cod_empresa
	ORDER BY DAT_CADASTR DESC
	";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
		$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		//$itens_por_pagina += $inicio;

		$sql2 = "SELECT A.DAT_CADASTR,
															A.COD_VENDAPDV,
															A.COD_UNIVEND,
															uni.NOM_FANTASI,
															A.COD_MAQUINA,
															A.COD_CUPOM,
															B.NUM_CGCECPF,
															A.COD_USUCADA,
															C.NOM_USUARIO,
															SUM(case when CRE.TIP_CREDITO='C' THEN CRE.VAL_CREDITO ELSE 0 END) VAL_CREDITO,
															A.VAL_TOTVENDA
													FROM VENDAS A
													left join CLIENTES B on A.COD_CLIENTE=B.COD_CLIENTE
													LEFT JOIN usuarios C ON A.COD_USUCADA=C.COD_USUARIO
													LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND		
													LEFT JOIN creditosdebitos CRE ON CRE.COD_VENDA=A.COD_VENDA											
													WHERE A.COD_ORCAMENTO >0 
												    AND DATE(A.DAT_CADASTR) between '$dat_ini' and '$dat_fim' AND 
													$andCpf
													A.COD_UNIVEND IN($lojasSelecionadas) AND
													A.COD_EMPRESA=$cod_empresa
													GROUP BY A.COD_VENDA
													ORDER BY A.DAT_CADASTR DESC
													limit $inicio,$itens_por_pagina
												";

		//fnEscreve($sql2);	

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			//$NOM_ARRAY_UNIDADE = (array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

?>
			<tr>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
				<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_CUPOM']; ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_USUCADA']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
				<td><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITO'], 2); ?></small></td>
				<td><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
			</tr>
<?php

			$countLinha++;
		}

		break;
}
?>