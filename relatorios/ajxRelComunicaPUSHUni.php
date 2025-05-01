<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$lojasSelecionadas = "";
$andData = "";
$cod_campanha = "";
$andCampanha = "";
$dat_ini = "";
$dat_fim = "";
$andUnid = "";
$log_detalhes = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$qrCampanhasEmail = "";
$dat_envio = "";
$qrBuscaModulos = "";
$contatos_graph = "";
$sucesso_graph = "";
$nrecebidos_graph = "";
$optout_graph = "";
$cancelado = "";
$falha_graph = "";
$perc_sucesso = "";
$perc_nrecebidos = "";
$perc_optout = "";
$perc_falha = "";
$perc_aguardo = "";
$aguardo_graph = "";
$perc_cancela = "";
$urlAnexo = "";

// require_once '../js/plugins/Spout/Autoloader/autoload.php';

// use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Common\Type;

$opcao = @$_GET['opcao'];
$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
$cod_univend = fnLimpaCampoZero(@$_GET['idu']);
$lojasSelecionadas = fnDecode(@$_REQUEST['LOJAS']);
$andData = fnDecode(@$_REQUEST['DATA']);
$cod_campanha = fnDecode(@$_REQUEST['COD_CAMPANHA']);
$andCampanha = fnDecode(@$_REQUEST['AND_CAMPANHA']);

//fnEscreve($andCampanha);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

if (@$_REQUEST['COD_UNIVEND'][0] == 9999) {
	$andUnid = " ";
} else {
	$andUnid = "AND ret.COD_UNIVEND IN(0,9999,$lojasSelecionadas)";
}

switch ($opcao) {

	case 'exportar':

		$log_detalhes = fnLimpaCampo(@$_GET['detalhes']);
		// fnEscreve(@$_GET['detalhes']);

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// fnEscreve($arquivoCaminho);

		// $sql = "SELECT 
		// 			LOJA,
		// 			CAMPANHA,
		// 			DATA_CADASTRO,
		// 			OPTOUT,
		// 			BOUNCE,
		// 			RECEBIDO,
		// 			CONFIRMADO,
		// 			SUB_TOTAL
		// 		FROM (
		// 			SELECT
		// 				1 ordenacao,
		// 				uni.NOM_FANTASI AS LOJA,
		// 				uni.COD_UNIVEND,
		// 				'' CAMPANHA, 
		// 				'' DATA_CADASTRO, 
		// 				SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
		// 				SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
		// 				SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
		// 				SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
		// 				SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
		// 			FROM unidadevenda uni
		// 			INNER JOIN push_lista_ret ret ON ret.COD_UNIVEND=uni.COD_UNIVEND
		// 			WHERE ret.CHAVE_CLIENTE IS NOT NULL AND uni.COD_EMPRESA=$cod_empresa
		// 			$andCampanha 
		// 			AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
		// 			AND uni.COD_UNIVEND IN($lojasSelecionadas)
		// 			GROUP BY uni.cod_univend 

		// 		UNION ALL
		// 			SELECT
		// 				2 ordenacao,
		// 				'' LOJA,
		// 				ret.COD_UNIVEND,
		// 				cap.DES_CAMPANHA CAMPANHA, 
		// 				DATE(ret.DAT_CADASTR) DATA_CADASTRO, 
		// 				SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
		// 				SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
		// 				SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
		// 				SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
		// 				SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
		// 			FROM push_lista_ret ret
		// 			INNER JOIN gatilho_push g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
		// 			INNER JOIN campanha cap ON cap.COD_CAMPANHA=ret.cod_campanha
		// 			WHERE ret.CHAVE_CLIENTE IS NOT NULL AND ret.COD_EMPRESA=$cod_empresa 
		// 			$andCampanha
		// 			AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
		// 			AND ret.COD_UNIVEND IN($lojasSelecionadas)
		// 			GROUP BY log_teste, cap.COD_CAMPANHA,ret.cod_univend)tmpuni

		// 		ORDER BY COD_UNIVEND,ordenacao ASC";

		$sql = "SELECT 
	tmppush.ID_DISPARO,
	tmppush.NOM_FANTASI,
	tmppush.COD_UNIVEND,
	tmppush.COD_CAMPANHA,
	CP.DAT_AGENDAMENTO DAT_ENVIO,
	tmppush.COD_OPTOUT_ATIVO,
	tmppush.BOUNCE,
	tmppush.CANCELADO,
	tmppush.COD_NRECEBIDO,
	tmppush.COD_CCONFIRMACAO,
	tmppush.SUB_TOTAL

	FROM (
		SELECT 
		ret.ID_DISPARO,
		uni.NOM_FANTASI, 
		uni.COD_UNIVEND,
		ret.COD_CAMPANHA,
		SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
		SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
		SUM(CASE WHEN ret.BOUNCE='2' THEN '1' ELSE '0' END) CANCELADO, 
		SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
		SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
		SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
		FROM push_lista_ret ret 
		LEFT JOIN  unidadevenda uni ON ret.COD_UNIVEND = uni.COD_UNIVEND
		WHERE ret.CHAVE_CLIENTE IS NOT NULL
		$andCampanha 
		AND ret.COD_EMPRESA = $cod_empresa 
		AND DATE(ret.dat_cadastr)  BETWEEN '$dat_ini' AND '$dat_fim' 
		$andUnid
		GROUP BY uni.cod_univend 
		ORDER BY  uni.cod_univend ASC
		)tmppush
	INNER JOIN push_LOTE CP ON CP.COD_CAMPANHA = tmppush.COD_CAMPANHA AND CP.COD_DISPARO_EXT = tmppush.ID_DISPARO
	GROUP BY tmppush.COD_CAMPANHA,tmppush.COD_UNIVEND
	ORDER BY CP.DAT_AGENDAMENTO desc";

		fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}

		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}

		fclose($arquivo);

		break;

	default:

		$andData = fnDecode(@$_REQUEST['DATA']);

		$sql = "SELECT  
	ret.COD_UNIVEND, 
	ret.COD_UNIVEND COD_UNIVEND_LISTA, 
	g.TIP_GATILHO, 
	ret.LOG_TESTE, 
	ret.ID_DISPARO, 
	ret.COD_CAMPANHA, 
	cap.DES_CAMPANHA, 
	DATE(ret.DAT_CADASTR) DATA_CADASTRO, 
	SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
	SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
	SUM(CASE WHEN ret.BOUNCE='2' THEN '1' ELSE '0' END) CANCELADO, 
	SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
	SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
	SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
	FROM push_lista_ret ret
	INNER JOIN gatilho_push g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
	INNER JOIN campanha cap ON cap.COD_CAMPANHA=ret.cod_campanha
	WHERE ret.CHAVE_CLIENTE IS NOT NULL 
	$andCampanha
	AND ret.COD_EMPRESA=$cod_empresa
	AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim' 
	AND ret.COD_UNIVEND = $cod_univend
	GROUP BY log_teste, COD_CAMPANHA, DATE(DATA_CADASTRO), ret.cod_univend
	ORDER BY DATE(DATA_CADASTRO) DESC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			$dat_envio = fnDataFull($qrBuscaModulos['DAT_ENVIO']);

			$contatos_graph = $qrCampanhasEmail['SUB_TOTAL'];
			$sucesso_graph = $qrCampanhasEmail['COD_CCONFIRMACAO'];
			$nrecebidos_graph = $qrCampanhasEmail['COD_NRECEBIDO'];
			$optout_graph = $qrCampanhasEmail['COD_OPTOUT_ATIVO'];
			$cancelado = $qrCampanhasEmail['CANCELADO'];
			$falha_graph = $qrCampanhasEmail['BOUNCE'];

			$perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
			$perc_nrecebidos = fnValorSql(fnValor(($nrecebidos_graph / $contatos_graph) * 100, 2));
			$perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
			$perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
			$perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));
			$perc_cancela = fnValorSql(fnValor(($cancelado / $contatos_graph) * 100, 2));

			// fnEscreve($qrBuscaModulos['COD_DISPARO']);

?>

			<tr>
				<!-- <td class="text-center"><small><?= $urlAnexo ?></small></td> -->
				<td></td>
				<td><small><small>(<?= $qrCampanhasEmail['COD_CAMPANHA'] ?>)</small>&nbsp;<?= $qrCampanhasEmail['DES_CAMPANHA'] ?></small>&nbsp;<span class="f10"><?= $qrCampanhasEmail['COD_DISPARO_EXT'] ?></span></td>
				<td><small><?= fnDatafull($qrCampanhasEmail['DATA_CADASTRO']) ?></small></td>
				<td class='text-right'><small><?= fnValor($contatos_graph, 0) ?>
				<td class='text-right'><small><?= fnValor($sucesso_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_sucesso, 2) ?>%</span></small></td>
				<td class='text-right'><small><?= fnValor($nrecebidos_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_nrecebidos, 2) ?>%</span></small></td>
				<td class='text-right'><small><?= fnValor($optout_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_optout, 2) ?>%</span></small></td>
				<td class='text-right'><small><?= fnValor($falha_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_falha, 2) ?>%</span></small></td>
				<td class='text-right'><small><?= fnValor($aguardo_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_aguardo, 2) ?>%</span></small></td>
				<td class='text-right'><small><?= fnValor($cancelado, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_cancela, 2) ?>%</span></small></td>
				<?php if ($qrCampanhasEmail['COD_DISPARO'] != "" && $_SESSION['SYS_COD_EMPRESA'] == 2) { ?>
					<!-- <td class='text-center'><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick='reprocessaDisparo("<?= fnEncode($cod_campanha) ?>","<?= fnEncode($qrCampanhasEmail['COD_DISPARO']) ?>", this)'><span class="fal fa-cogs"></span></a></td> -->
				<?php } else { ?>
					<!-- <td></td> -->
				<?php } ?>
			</tr>

<?php

		}

		break;
}

?>