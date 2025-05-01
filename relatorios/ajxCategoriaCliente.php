<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = "";
$itens_por_pagina = "";
$pagina = "";
$cod_empresa = "";
$cod_univend = "";
$lojasSelecionadas = "";
$cod_categoria = "";
$dat_ini = "";
$dat_fim = "";
$log_antigo = "";
$dias30 = "";
$hoje = "";
$andUnidade = "";
$andCat = "";
$groupBy = "";
$andData = "";
$andData2 = "";
$cod_cliente = "";
$sql = "";
$arrayQuery = "";
$qrCategoriaCli = "";
$cliCadastrado = "";
$reenvioTkn = "";
$nomeRel = "";
$arquivoCaminho = "";
$arquivo = "";
$headers = "";
$row = "";
$rowDet = "";
$limpandostring = "";
$textolimpo = "";
$array = "";
$arrayDet = "";
$ultimoCliente = "";
$arrItem = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$mostraCracha = "";


include '../_system/_functionsMain.php';


$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
$lojasSelecionadas = fnLimpaCampo($_REQUEST['LOJAS']);
$cod_categoria = fnLimpaCampoZero($_REQUEST['COD_CATEGORIA']);
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);


if (empty($_REQUEST['LOG_ANTIGO'])) {
	$log_antigo = 'N';
} else {
	$log_antigo = $_REQUEST['LOG_ANTIGO'];
}

// fnEscreve($opcao);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

$andUnidade = "";

if ($cod_univend != "" && $cod_univend != "9999") {
	// $andUnidade = "AND b.COD_UNIVEND = $cod_univend";
}

$andCat = "";

if ($cod_categoria != 0) {
	$andCat = "AND b.COD_CATEGORIA = $cod_categoria";
}

if ($log_antigo == "S") {
	$groupBy = "";
	$andData = "";
} else {
	$groupBy = "GROUP BY COD_CLIENTE";
	$andData = "AND DATE(a.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'";
	$andData2 = "AND DATE(dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'";
}

switch ($opcao) {
	case 'detail':

		$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

		$sql = "SELECT * FROM (
					SELECT        uni.NOM_FANTASI,
					              B.COD_CLIENTE,
					              B.COD_EMPRESA,
					              B.NOM_CLIENTE,
					              B.DAT_ULTCOMPR,
					              A.DAT_CADASTR,
					              A.VAL_COMPRAS,
					              C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR,
					              E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
					              D.NOM_FAIXACAT AS CATEGORIA_NOVA,
					              (SELECT COUNT(COD_CLIENTE) FROM HISTORICO_CLASSIFICA_CLIENTE WHERE COD_EMPRESA = A.COD_EMPRESA AND COD_CLIENTE = A.COD_CLIENTE $andData2) TEM_HISTORICO,
					              CASE
					                WHEN A.TIP_CLASSIF = 'V' THEN 'Venda'   WHEN A.TIP_CLASSIF = 'R' THEN 'Reclassificação'   END  TIP_CLASSIFICACAO
					        FROM   HISTORICO_CLASSIFICA_CLIENTE A
					              INNER JOIN CLIENTES B ON a.cod_cliente = b.COD_CLIENTE
					              LEFT JOIN CATEGORIA_CLIENTE C ON A.COD_CATEGOR = C.COD_CATEGORIA AND A.COD_EMPRESA = C.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE D ON A.COD_CATEGORIA_NOVA = D.COD_CATEGORIA AND A.COD_EMPRESA = D.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE E ON B.COD_CATEGORIA = E.COD_CATEGORIA AND A.COD_EMPRESA = E.COD_EMPRESA
					              LEFT JOIN UNIDADEVENDA UNI ON uni.COD_UNIVEND = b.COD_UNIVEND
					        WHERE  A.COD_EMPRESA = $cod_empresa
					        AND  A.COD_CLIENTE = $cod_cliente
					        $andData
					        AND B.COD_UNIVEND IN($lojasSelecionadas)
					        $andUnidade
							$andCat
					)tmpHISTORICO 
					 ORDER BY DAT_CADASTR DESC";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		// fnEscreve($cod_cliente);
		// fnEscreve($sql);
		$count = 0;
		while ($qrCategoriaCli = mysqli_fetch_assoc($arrayQuery)) {

			if ($count == 0) {
				$count++;
				continue;
			}

			//fnEscreve()

			if ($qrCategoriaCli['LOG_USADO'] == 2) {
				$cliCadastrado = "<i class='fal fa-check text-success' aria-hidden='true'></i>";
				$reenvioTkn = "";
			} else {
				$cliCadastrado = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
				$reenvioTkn = "<a class='btn btn-xs btn-info' onclick='reenvioTkn(" . $qrCategoriaCli['COD_TOKEN'] . ")'><span class='fal fa-repeat'></span> Reenviar</a>";
			}

			echo "
					<tr style='background-color: #FFFDE7;' class='detail_" . fnEncode($qrCategoriaCli['COD_CLIENTE']) . "'>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td><small>" . fnDataFull($qrCategoriaCli['DAT_CADASTR']) . "</small></td>
					  <td></td>
					  <td><small>" . $qrCategoriaCli['CATEGORIA_ANTERIOR'] . "</small></td>
					  <td><small>" . $qrCategoriaCli['CATEGORIA_NOVA'] . "</small></td>
					  <td></td>
					  <td><small>" . $qrCategoriaCli['TIP_CLASSIFICACAO'] . "</small></td>
					  <td><small><small>R$ </small>" . fnValor($qrCategoriaCli['VAL_COMPRAS'], 2) . "</small></td>
					</tr>
				";
		}

		break;

	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT * FROM (
				  SELECT B.COD_CLIENTE,
			       		  B.NOM_CLIENTE CLIENTE,
				          uni.NOM_FANTASI LOJA,
			              A.DAT_CADASTR DT_CLASSIFICACAO,
			              B.DAT_ULTCOMPR DT_ULTIMA_COMPRA,
			              C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR,
			              D.NOM_FAIXACAT AS CATEGORIA_NOVA,
			              E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
			              CASE WHEN A.TIP_CLASSIF = 'V' THEN 'Venda'   WHEN A.TIP_CLASSIF = 'R' THEN 'Reclassificação'   END  TIP_CLASSIFICACAO,
			              A.VAL_COMPRAS VENDA_CLASSIFICACAO
			        FROM   HISTORICO_CLASSIFICA_CLIENTE A
				              INNER JOIN CLIENTES B ON a.cod_cliente = b.COD_CLIENTE
				              LEFT JOIN CATEGORIA_CLIENTE C ON A.COD_CATEGOR = C.COD_CATEGORIA AND A.COD_EMPRESA = C.COD_EMPRESA
				              LEFT JOIN CATEGORIA_CLIENTE D ON A.COD_CATEGORIA_NOVA = D.COD_CATEGORIA AND A.COD_EMPRESA = D.COD_EMPRESA
				              LEFT JOIN CATEGORIA_CLIENTE E ON B.COD_CATEGORIA = E.COD_CATEGORIA AND A.COD_EMPRESA = E.COD_EMPRESA
				              LEFT JOIN UNIDADEVENDA UNI ON uni.COD_UNIVEND = b.COD_UNIVEND
				        WHERE  A.COD_EMPRESA = $cod_empresa
				        $andData
				        AND B.COD_UNIVEND IN($lojasSelecionadas)
				        $andUnidade
						$andCat
				        ORDER  BY A.COD_REGISTRO DESC
				)tmpHISTORICO
				 GROUP BY COD_CLIENTE 
				 ORDER BY NOM_CLIENTE";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$rowDet['VENDA_CLASSIFICACAO'] = fnValor($rowDet['VENDA_CLASSIFICACAO'], 2);
			$rowDet['DT_CLASSIFICACAO'] = fnDataFull($rowDet['DT_CLASSIFICACAO']);
			$rowDet['DT_ULTIMA_COMPRA'] = fnDataFull($rowDet['DT_ULTIMA_COMPRA']);

			$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $textolimpo, ';', '"');
		}
		fclose($arquivo);

		break;

	case 'exportarDetalhes':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT * FROM (
					       SELECT B.COD_CLIENTE,
					       		  B.NOM_CLIENTE CLIENTE,
						          uni.NOM_FANTASI LOJA,
					              A.DAT_CADASTR DT_CLASSIFICACAO,
					              B.DAT_ULTCOMPR DT_ULTIMA_COMPRA,
					              C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR,
					              D.NOM_FAIXACAT AS CATEGORIA_NOVA,
					              E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
					              CASE WHEN A.TIP_CLASSIF = 'V' THEN 'Venda'   WHEN A.TIP_CLASSIF = 'R' THEN 'Reclassificação'   END  TIP_CLASSIFICACAO,
					              A.VAL_COMPRAS VENDA_CLASSIFICACAO
					        FROM   HISTORICO_CLASSIFICA_CLIENTE A
					              INNER JOIN CLIENTES B ON a.cod_cliente = b.COD_CLIENTE
					              LEFT JOIN CATEGORIA_CLIENTE C ON A.COD_CATEGOR = C.COD_CATEGORIA AND A.COD_EMPRESA = C.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE D ON A.COD_CATEGORIA_NOVA = D.COD_CATEGORIA AND A.COD_EMPRESA = D.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE E ON B.COD_CATEGORIA = E.COD_CATEGORIA AND A.COD_EMPRESA = E.COD_EMPRESA
					              LEFT JOIN UNIDADEVENDA UNI ON uni.COD_UNIVEND = b.COD_UNIVEND
					        WHERE  A.COD_EMPRESA = $cod_empresa
					        $andData
					        AND B.COD_UNIVEND IN($lojasSelecionadas)
					        $andUnidade
							$andCat
					       ORDER  BY A.COD_REGISTRO DESC

					)tmpHISTORICO
					 ORDER BY CLIENTE, DT_CLASSIFICACAO";

		// fnEscreve($sql);

		$arrayDet = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayDet)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		$ultimoCliente = "";

		while ($rowDet = mysqli_fetch_assoc($arrayDet)) {

			$arrItem = array();

			$rowDet['VENDA_CLASSIFICACAO'] = fnValor($rowDet['VENDA_CLASSIFICACAO'], 2);
			$rowDet['DT_CLASSIFICACAO'] = fnDataFull($rowDet['DT_CLASSIFICACAO']);
			$rowDet['DT_ULTIMA_COMPRA'] = fnDataFull($rowDet['DT_ULTIMA_COMPRA']);

			if ($ultimoCliente == $rowDet['COD_CLIENTE']) {
				$rowDet['COD_CLIENTE'] = "";
				$rowDet['LOJA'] = "";
				$rowDet['DT_ULTIMA_COMPRA'] = "";
				$rowDet['CATEGORIA_ATUAL'] = "";
			}

			$limpandostring = fnAcentos(Utf8_ansi(json_encode($rowDet)));
			$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $rowDet);
			fputcsv($arquivo, $array, ';', '"');
			$ultimoCliente = $rowDet['COD_CLIENTE'];
		}
		fclose($arquivo);

		break;

	case 'paginar':

		$sql = "SELECT B.COD_CLIENTE
					FROM HISTORICO_CLASSIFICA_CLIENTE A
					INNER  JOIN clientes b ON a.cod_cliente=b.cod_cliente
					INNER  JOIN WEBTOOLS.UNIDADEVENDA C ON B.COD_UNIVEND=C.COD_UNIVEND
					WHERE a.cod_empresa = $cod_empresa 
					$andData
					AND b.COD_UNIVEND IN($lojasSelecionadas)
					AND b.LOG_AVULSO = 'N'
					$andUnidade
					$andCat
					$groupBy";

		// fnEscreve($sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		$sql = "SELECT * FROM (
					SELECT        uni.NOM_FANTASI,
					              B.COD_CLIENTE,
					              B.COD_EMPRESA,
					              B.NOM_CLIENTE,
					              B.DAT_ULTCOMPR,
					              A.DAT_CADASTR,
					              A.VAL_COMPRAS,
					              C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR,
					              E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
					              D.NOM_FAIXACAT AS CATEGORIA_NOVA,
					              (SELECT COUNT(COD_CLIENTE) FROM HISTORICO_CLASSIFICA_CLIENTE WHERE COD_EMPRESA = A.COD_EMPRESA AND COD_CLIENTE = A.COD_CLIENTE $andData2) TEM_HISTORICO,
					              CASE
					                WHEN A.TIP_CLASSIF = 'V' THEN 'Venda'   WHEN A.TIP_CLASSIF = 'R' THEN 'Reclassificação'   END  TIP_CLASSIFICACAO
					        FROM   HISTORICO_CLASSIFICA_CLIENTE A
					              INNER JOIN CLIENTES B ON a.cod_cliente = b.COD_CLIENTE
					              LEFT JOIN CATEGORIA_CLIENTE C ON A.COD_CATEGOR = C.COD_CATEGORIA AND A.COD_EMPRESA = C.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE D ON A.COD_CATEGORIA_NOVA = D.COD_CATEGORIA AND A.COD_EMPRESA = D.COD_EMPRESA
					              LEFT JOIN CATEGORIA_CLIENTE E ON B.COD_CATEGORIA = E.COD_CATEGORIA AND A.COD_EMPRESA = E.COD_EMPRESA
					              LEFT JOIN UNIDADEVENDA UNI ON uni.COD_UNIVEND = b.COD_UNIVEND
					        WHERE  A.COD_EMPRESA = $cod_empresa
					        $andData
					        AND B.COD_UNIVEND IN($lojasSelecionadas)
					        $andUnidade
							$andCat
					       ORDER  BY A.COD_REGISTRO DESC

					)tmpHISTORICO
					 GROUP BY COD_CLIENTE 
					 ORDER BY NOM_CLIENTE
					 LIMIT  $inicio,$itens_por_pagina";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		// fnEscreve($sql);
		$count = 0;
		while ($qrCategoriaCli = mysqli_fetch_assoc($arrayQuery)) {

			$count++;

			//fnEscreve()

			if (@$qrCategoriaCli['LOG_USADO'] == 2) {
				$cliCadastrado = "<i class='fal fa-check text-success' aria-hidden='true'></i>";
				$reenvioTkn = "";
			} else {
				$cliCadastrado = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
				$reenvioTkn = "<a class='btn btn-xs btn-info' onclick='reenvioTkn(" . @$qrCategoriaCli['COD_TOKEN'] . ")'><span class='fal fa-repeat'></span> Reenviar</a>";
			}

			if ($qrCategoriaCli['TEM_HISTORICO'] == 1) {


				echo "
						<tr>
						  <td></td>
						  <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrCategoriaCli['COD_CLIENTE']) . "' target='_blank'>" . $qrCategoriaCli['NOM_CLIENTE'] . "&nbsp;" . @$mostraCracha . "</a></small></td>
						  <td><small>" . $qrCategoriaCli['NOM_FANTASI'] . "</small></td>
						  <td><small>" . fnDataFull($qrCategoriaCli['DAT_CADASTR']) . "</small></td>
						  <td><small>" . fnDataFull($qrCategoriaCli['DAT_ULTCOMPR']) . "</small></td>
						  <td><small>" . $qrCategoriaCli['CATEGORIA_ANTERIOR'] . "</small></td>
						  <td><small>" . $qrCategoriaCli['CATEGORIA_NOVA'] . "</small></td>
						  <td><small>" . $qrCategoriaCli['CATEGORIA_ATUAL'] . "</small></td>
						  <td><small>" . $qrCategoriaCli['TIP_CLASSIFICACAO'] . "</small></td>
						  <td><small><small>R$ </small>" . fnValor($qrCategoriaCli['VAL_COMPRAS'], 2) . "</small></td>
						</tr>
						
						";
			} else {

?>

				<tr id="bloco_<?= fnEncode($qrCategoriaCli['COD_CLIENTE']) ?>">
					<td class="text-center">
						<a href="javascript:void(0);" onclick='abreDetail("<?= fnEncode($qrCategoriaCli["COD_CLIENTE"]) ?>")' style="padding:10px;">
							<i class="fa fa-angle-right" aria-hidden="true"></i>
						</a>
					</td>
					<td><small><a href="action.do?mod=<?= fnEncode(1024) ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrCategoriaCli['COD_CLIENTE']) ?>" target="_blank"><?= $qrCategoriaCli['NOM_CLIENTE'] ?>&nbsp;<?= $mostraCracha ?></a></small></td>
					<td><small><?php echo $qrCategoriaCli['NOM_FANTASI']; ?></small></td>
					<td><small><?php echo fnDataFull($qrCategoriaCli['DAT_CADASTR']); ?></small></td>
					<td><small><?php echo fnDataFull($qrCategoriaCli['DAT_ULTCOMPR']); ?></small></td>
					<td><small><?php echo $qrCategoriaCli['CATEGORIA_ANTERIOR']; ?></small></td>
					<td><small><?php echo $qrCategoriaCli['CATEGORIA_NOVA']; ?></small></td>
					<td><small><?php echo $qrCategoriaCli['CATEGORIA_ATUAL']; ?></small></td>
					<td><small><?php echo $qrCategoriaCli['TIP_CLASSIFICACAO']; ?></small></td>
					<td><small><small>R$ </small><?php echo fnValor($qrCategoriaCli['VAL_COMPRAS'], 2); ?></small></td>
				</tr>

<?php

			}
		}

		break;
}
?>