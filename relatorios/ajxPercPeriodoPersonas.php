<?php

include '../_system/_functionsMain.php';
// require_once '../js/plugins/Spout/Autoloader/autoload.php';

// use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Common\Type;	

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = "";
$cod_usucada = "";
$dat_ini = "";
$dat_fim = "";
$dat_ini2 = "";
$dat_fim2 = "";
$tip_relat = "";
$lojasSelecionadas = "";
$cod_persona = "";
$Arr_COD_PERSONA = "";
$i = "";
$dias30 = "";
$hoje = "";
$completaSql = "";
$objeto = "";
$objConsulta = "";
$colspan = "";
$colspanCli = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$PERC_TRANSACAO = "";
$PERC_ITENS = "";
$PERC_CLIENTE = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$VAL_TICKET_MEDIO_P1 = "";
$VAL_TICKET_MEDIO_P2 = "";
$tot_FATURAMENTO_P1 = "";
$tot_VAL_TICKET_MEDIO_P1 = "";
$tot_QTD_TRANSACAO_P1 = "";
$tot_QTD_ITENS_P1 = "";
$tot_QTD_CLIENTE_P1 = "";
$tot_FATURAMENTO_P2 = "";
$tot_VAL_TICKET_MEDIO_P2 = "";
$tot_QTD_TRANSACAO_P2 = "";
$tot_QTD_ITENS_P2 = "";
$tot_QTD_CLIENTE_P2 = "";
$tot_PERC_FATURAMENTO = "";
$tot_PERC_TICKET_MEDIO = "";
$tot_PERC_TRANSACAO = "";
$tot_PERC_ITENS = "";
$tot_PERC_CLIENTE = "";
$tot_CLI_PERSONAS = "";
$tot_PES_UNICAS = "";
$tot_PES_UNICAS_P1 = "";
$tot_PES_UNICAS_P2 = "";
$VAR_PERC_FATURAMENTO = "";
$VAR_PERC_TICKET_MEDIO = "";
$VAR_PERC_TRANSACAO = "";
$VAR_PERC_ITENS = "";
$VAR_PERC_CLIENTE = "";
$tot_VAL_RESGATE_P1 = "";
$tot_VAL_RESGATE_P2 = "";
$tot_QTD_RESGATE_P1 = "";
$tot_QTD_RESGATE_P2 = "";
$tot_VVR_P1 = "";
$tot_VVR_P2 = "";
$tot_VALOR_VINCULADO_P1 = "";
$tot_VALOR_VINCULADO_P2 = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$query = "";
$qrResult = "";
$linhaCliP1 = "";
$linhaCliP2 = "";
$emailAndCel = "";
$id = "";


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
$dat_ini2 = fnDataSql(@$_REQUEST['DAT_INI2']);
$dat_fim2 = fnDataSql(@$_REQUEST['DAT_FIM2']);
// $tip_relat = fnLimpaCampo(@$_REQUEST['TIP_RELAT']);
$tip_relat = fnLimpaCampo(@$_REQUEST['TIP_RELAT']);
$lojasSelecionadas = @$_REQUEST['LOJAS'];
if (isset($_REQUEST['COD_PERSONA'])) {
	$cod_persona = "";
	$Arr_COD_PERSONA = @$_REQUEST['COD_PERSONA'];

	for ($i = 0; $i < count($Arr_COD_PERSONA); $i++) {
		$cod_persona = $cod_persona . $Arr_COD_PERSONA[$i] . ",";
	}

	$cod_persona = ltrim(rtrim($cod_persona, ','), ',');
} else {
	$cod_persona = "0";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" && strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
	$dat_ini2 = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31" && strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
	$dat_fim2 = fnDataSql($hoje);
}

if ($tip_relat == 1) {
	$completaSql = "";
	$objeto = "Loja";
	$objConsulta = 'LOJA';
	$colspan = "5";
	$colspanCli = "";
} else {

	$completaSql = "_CLIENTE";
	$objeto = "Cliente";
	$objConsulta = 'NOME';
	$colspan = "4";
	$colspanCli = "3";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";
		//============================

		$sql = "CALL SP_RELAT_PERCENTUAL_PERIODO_VENDA_PERSONAS$completaSql ( '$dat_ini' , '$dat_fim'  , '$dat_ini2' , '$dat_fim2', '$lojasSelecionadas' , $cod_empresa, '$cod_persona' )";
		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$PERC_TRANSACAO = ($row['QTD_TRANSACAO_P1'] != 0) ? (($row['QTD_TRANSACAO_P2'] - $row['QTD_TRANSACAO_P1']) / $row['QTD_TRANSACAO_P1']) * 100 : 0;
			$PERC_ITENS = ($row['QTD_ITENS_P1'] != 0) ? (($row['QTD_ITENS_P2'] - $row['QTD_ITENS_P1']) / $row['QTD_ITENS_P1']) * 100 : 0;
			$PERC_CLIENTE = ($row['QTD_CLIENTE_P1'] != 0) ? (($row['QTD_CLIENTE_P2'] - $row['QTD_CLIENTE_P1']) / $row['QTD_CLIENTE_P1']) * 100 : 0;

			$row['VAL_TICKET_MEDIO_P2'] = fnValor($row['VAL_TICKET_MEDIO_P2'], 2);
			$row['VAL_TICKET_MEDIO_P1'] = fnValor($row['VAL_TICKET_MEDIO_P1'], 2);
			$row['FATURAMENTO_P1'] = fnValor($row['FATURAMENTO_P1'], 2);
			$row['FATURAMENTO_P2'] = fnValor($row['FATURAMENTO_P2'], 2);
			$row['PERC_TRANSACAO'] = $PERC_TRANSACAO;
			$row['PERC_ITENS'] = $PERC_ITENS;
			$row['PERC_CLIENTE'] = $PERC_CLIENTE;
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');
		}

		fclose($arquivo);

		break;

	case 'paginar':

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";
		//============================

		$VAL_TICKET_MEDIO_P1 = "";
		$VAL_TICKET_MEDIO_P2 = "";
		$tot_FATURAMENTO_P1 = "";
		$tot_VAL_TICKET_MEDIO_P1 = "";
		$tot_QTD_TRANSACAO_P1 = "";
		$tot_QTD_ITENS_P1 = "";
		$tot_QTD_CLIENTE_P1 = "";
		$tot_FATURAMENTO_P2 = "";
		$tot_VAL_TICKET_MEDIO_P2 = "";
		$tot_QTD_TRANSACAO_P2 = "";
		$tot_QTD_ITENS_P2 = "";
		$tot_QTD_CLIENTE_P2 = "";
		$tot_PERC_FATURAMENTO = "";
		$tot_PERC_TICKET_MEDIO = "";
		$tot_PERC_TRANSACAO = "";
		$tot_PERC_ITENS = "";
		$tot_PERC_CLIENTE = "";
		$tot_CLI_PERSONAS = "";
		$tot_PES_UNICAS = "";
		$tot_PES_UNICAS_P1 = "";
		$tot_PES_UNICAS_P2 = "";
		$VAR_PERC_FATURAMENTO = "";
		$VAR_PERC_TICKET_MEDIO = "";
		$VAR_PERC_TRANSACAO = "";
		$VAR_PERC_ITENS = "";
		$VAR_PERC_CLIENTE = "";
		$tot_VAL_RESGATE_P1 = "";
		$tot_VAL_RESGATE_P2 = "";
		$tot_QTD_RESGATE_P1 = "";
		$tot_QTD_RESGATE_P2 = "";
		$tot_VVR_P1 = "";
		$tot_VVR_P2 = "";
		$tot_VALOR_VINCULADO_P1 = "";
		$tot_VALOR_VINCULADO_P2 = "";
		$count = 0;

		$sql = "SELECT 1 FROM PCT_VENDA_PERSONA_AUX 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_USUARIO = $cod_usucada";

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT * FROM PCT_VENDA_PERSONA_AUX 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_USUARIO = $cod_usucada
					LIMIT $inicio, $itens_por_pagina";

		$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

		while ($qrResult = mysqli_fetch_assoc($query)) {

			$VAL_TICKET_MEDIO_P1 = ($qrResult['QTD_TRANSACAO_P1'] != 0) ? ($qrResult['FATURAMENTO_P1'] / $qrResult['QTD_TRANSACAO_P1']) : 0; // ou outro valor padrão
			$VAL_TICKET_MEDIO_P2 = ($qrResult['QTD_TRANSACAO_P2'] != 0) ?  ($qrResult['FATURAMENTO_P2'] / $qrResult['QTD_TRANSACAO_P2']) : 0;

			$tot_FATURAMENTO_P1 += $qrResult['FATURAMENTO_P1'];
			$tot_VAL_TICKET_MEDIO_P1 += $VAL_TICKET_MEDIO_P1;
			$tot_QTD_TRANSACAO_P1 += $qrResult['QTD_TRANSACAO_P1'];
			$tot_QTD_ITENS_P1 += $qrResult['QTD_ITENS_P1'];
			$tot_QTD_CLIENTE_P1 += $qrResult['QTD_CLIENTE_P1'];
			$tot_FATURAMENTO_P2 += $qrResult['FATURAMENTO_P2'];
			$tot_VAL_TICKET_MEDIO_P2 += $VAL_TICKET_MEDIO_P2;
			$tot_QTD_TRANSACAO_P2 += $qrResult['QTD_TRANSACAO_P2'];
			$tot_QTD_ITENS_P2 += $qrResult['QTD_ITENS_P2'];
			$tot_QTD_CLIENTE_P2 += $qrResult['QTD_CLIENTE_P2'];
			$tot_PERC_FATURAMENTO += $qrResult['PERC_FATURAMENTO'];
			$tot_PERC_TICKET_MEDIO += $qrResult['PERC_TICKET_MEDIO'];
			$tot_PERC_TRANSACAO += $qrResult['PERC_TRANSACAO'];
			$tot_PERC_ITENS += $qrResult['PERC_ITENS'];
			$tot_PERC_CLIENTE += $qrResult['PERC_CLIENTE'];
			$tot_CLI_PERSONAS = $qrResult['TOTAL_CLI_PERSONAS'];
			$tot_PES_UNICAS = $qrResult['TOTAL_CLIENTES_UNICOS'];

			$tot_PES_UNICAS_P1 = $qrResult['TOTAL_CLIENTES_UNICOS_P1'];
			$tot_PES_UNICAS_P2 = $qrResult['TOTAL_CLIENTES_UNICOS_P2'];

			$VAR_PERC_FATURAMENTO = ($qrResult['FATURAMENTO_P1'] != 0) ? (($qrResult['FATURAMENTO_P2'] - $qrResult['FATURAMENTO_P1']) / $qrResult['FATURAMENTO_P1']) * 100 : 0;
			$VAR_PERC_TICKET_MEDIO = ($VAL_TICKET_MEDIO_P1 != 0) ? (($VAL_TICKET_MEDIO_P2 - $VAL_TICKET_MEDIO_P1) / $VAL_TICKET_MEDIO_P1) * 100 : 0;
			$VAR_PERC_TRANSACAO = ($qrResult['QTD_TRANSACAO_P1'] != 0) ?  (($qrResult['QTD_TRANSACAO_P2'] - $qrResult['QTD_TRANSACAO_P1']) / $qrResult['QTD_TRANSACAO_P1'])  * 100 : 0;
			$VAR_PERC_ITENS = ($qrResult['QTD_ITENS_P1'] != 0) ? (($qrResult['QTD_ITENS_P2'] - $qrResult['QTD_ITENS_P1']) / $qrResult['QTD_ITENS_P1']) * 100 : 0;
			$VAR_PERC_CLIENTE = ($qrResult['QTD_CLIENTE_P1'] != 0) ? (($qrResult['QTD_CLIENTE_P2'] - $qrResult['QTD_CLIENTE_P1']) / $qrResult['QTD_CLIENTE_P1']) * 100 : 0;

			if ($objeto == "Loja") {
				$linhaCliP1 = "<td class='p1'><small>" . $qrResult['QTD_CLIENTE_P1'] . "</small></td>";
				$linhaCliP2 = "<td><small>" . $qrResult['QTD_CLIENTE_P2'] . "</small></td>";
				$emailAndCel = "";
			} else {
				$linhaCliP1 = "";
				$linhaCliP2 = "";
				$emailAndCel = "<td><small>" . $qrResult['EMAIL'] . "</small></td>
												<td><small>" . $qrResult['CELULAR'] . "</small></td>";
			}

			$tot_VAL_RESGATE_P1 += $qrResult['TOTAL_VAL_RESGATE_P1'];
			$tot_VAL_RESGATE_P2 += $qrResult['TOTAL_VAL_RESGATE_P2'];

			$tot_QTD_RESGATE_P1 += $qrResult['TOTAL_QTD_RESGATE_P1'];
			$tot_QTD_RESGATE_P2 += $qrResult['TOTAL_QTD_RESGATE_P2'];

			$tot_VVR_P1 += $qrResult['TOTAL_VVR_P1'];
			$tot_VVR_P2 += $qrResult['TOTAL_VVR_P2'];

			$tot_VALOR_VINCULADO_P1 += $qrResult['VALOR_VINCULADO_P1'];
			$tot_VALOR_VINCULADO_P2 += $qrResult['VALOR_VINCULADO_P2'];

			$count++;

			$id = fnEncode($count);

			echo "
				<tr id='" . $id . "'>
				<td>
				<small>
				<a href='javascript:void(0)' style='padding:5px;' data-toggle='tooltip' data-placement='top' data-original-title='Esconder " . $objeto . "' onclick='$(\"#" . $id . "\").fadeOut(\"fast\");'>
				<i class='fal fa-times'></i>
				</a>&nbsp;
				" . $qrResult[$objConsulta] . "
				</small>
				</td>
				" . $emailAndCel . "
				<td class='text-right p1'><small> " . fnValor($qrResult['FATURAMENTO_P1'], 2) . "</small></td>
				<td class='text-right p1'><small> " . fnValor($VAL_TICKET_MEDIO_P1, 2) . "</small></td>
				<td class='p1'><small>" . $qrResult['QTD_TRANSACAO_P1'] . "</small></td>
				<td class='p1'><small>" . $qrResult['QTD_ITENS_P1'] . "</small></td>
				" . $linhaCliP1 . "
				<td></td>
				<td class='text-right'><small> " . fnValor($qrResult['FATURAMENTO_P2'], 2) . "</small></td>
				<td class='text-right'><small> " . fnValor($VAL_TICKET_MEDIO_P2, 2) . "</small></td>
				<td><small>" . $qrResult['QTD_TRANSACAO_P2'] . "</small></td>
				<td><small>" . $qrResult['QTD_ITENS_P2'] . "</small></td>
				" . $linhaCliP2 . "
				<td></td>
				<td class='text-right p1'><small>" . fnValor($VAR_PERC_FATURAMENTO, 2) . "%</small></td>
				<td class='text-right p1'><small>" . fnValor($VAR_PERC_TICKET_MEDIO, 2) . "%</small></td>
				<td class='text-right p1'><small>" . fnValor($VAR_PERC_TRANSACAO, 2) . "%</small></td>
				<td class='text-right p1'><small>" . fnValor($VAR_PERC_ITENS, 2) . "%</small></td>
				<td class='text-right p1'><small>" . fnValor($VAR_PERC_CLIENTE, 2) . "%</small></td>
				</tr>
				";
		}

		break;
}
