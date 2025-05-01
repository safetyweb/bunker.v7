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
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$num_cgcecpf = "";
$cod_cliente = "";
$lojasSelecionadas = "";
$cod_campanha = "";
$num_celular = "";
$autoriza = "";
$log_optout = "";
$log_retorno = "";
$dias30 = "";
$hoje = "";
$andCliente = "";
$andCpf = "";
$andCelular = "";
$andCampanha = "";
$data_ini = "";
$data_fim = "";
$andData = "";
$andOpt = "";
$andRetorno = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrRetorno = "";
$colCliente = "";
$status = "";




$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$hor_ini = fnLimpaCampo(@$_POST['HOR_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$hor_fim = fnLimpaCampo(@$_POST['HOR_FIM']);
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
$cod_cliente = fnLimpaCampoZero(@$_POST['COD_CLIENTE']);
$lojasSelecionadas = @$_POST['LOJAS'];
$cod_campanha = fnLimpaCampoZero(@$_POST['COD_CAMPANHA']);
$num_celular = fnLimpaCampo(@$_REQUEST['NUM_CELULAR']);
$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

if (empty(@$_REQUEST['LOG_OPTOUT'])) {
	$log_optout = 'N';
} else {
	$log_optout = @$_REQUEST['LOG_OPTOUT'];
}
if (empty(@$_REQUEST['LOG_RETORNO'])) {
	$log_retorno = 'N';
} else {
	$log_retorno = @$_REQUEST['LOG_RETORNO'];
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($cod_cliente != '' && $cod_cliente != 0) {
	$andCliente = "AND CL.COD_CLIENTE = '$cod_cliente'";
} else {
	$andCliente = "";
}

if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
	$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
} else {
	$andCpf = "";
}

if ($num_celular != '' && $num_celular != 0) {
	$andCelular = "AND CL.NUM_CELULAR = '" . fnLimpaDoc($num_celular) . "'";
} else {
	$andCelular = "";
}

if ($cod_campanha != 0 && $cod_campanha != '') {
	$andCampanha = "AND SLR.COD_CAMPANHA = $cod_campanha";
} else {
	$andCampanha = "";
}

if ($dat_ini != '' && $dat_ini != 0) {
	$data_ini = $dat_ini . ' ' . $hor_ini;
	$data_fim = $dat_fim . ' ' . $hor_fim;
	$andData = "AND SLR.DAT_CADASTR BETWEEN " . fnDateSql($data_ini) . " AND " . fnDateSql($data_fim);
} else {
	$andData = "AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
}

if ($log_optout == 'S') {
	$andOpt = "AND SLR.COD_OPTOUT_ATIVO = 1";
	//$andData = "";
} else {
	$andOpt = "";
}

if ($log_retorno == 'S') {
	$andRetorno = "AND SLR.DES_MOTIVO != ''";
	//$andData = "";
} else {
	$andRetorno = "";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT  SLR.DAT_CADASTR,
	CL.COD_CLIENTE,
	CL.NOM_CLIENTE,
	CL.NUM_CGCECPF,
	SLR.NUM_CELULAR,
	SLR.DES_MOTIVO,
	SLR.DES_STATUS,
	SLR.DES_MSG_ENVIADA, 
	CP.DES_CAMPANHA, 
	UV.NOM_FANTASI 
	FROM  whatsapp_lista_ret SLR
	INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
	LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
	left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
	WHERE SLR.COD_EMPRESA = $cod_empresa
	$andData
	AND SLR.COD_UNIVEND IN(9999,0,$lojasSelecionadas)
	$andCampanha
	$andCliente
	$andCpf
	$andCelular
	$andOpt
	$andRetorno
	ORDER BY SLR.DAT_CADASTR DESC";

		//fnEscreve($sql);

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
	case 'paginar':

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT * FROM  whatsapp_lista_ret SLR
	INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
	LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
	left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
	WHERE SLR.COD_EMPRESA = $cod_empresa
	$andData
	AND SLR.COD_UNIVEND IN(9999,0,$lojasSelecionadas)
	$andCampanha
	$andCliente
	$andCpf
	$andCelular
	$andOpt
	$andRetorno";

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$totalitens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		$sql = "SELECT  SLR.DAT_CADASTR,
	CL.COD_CLIENTE,
	CL.NOM_CLIENTE,
	CL.NUM_CGCECPF,
	SLR.NUM_CELULAR,
	SLR.DES_MOTIVO,
	SLR.DES_STATUS,
	SLR.BOUNCE,
	SLR.ID_EXT_CELULAR AS SESSAO,
	SLR.DES_MSG_ENVIADA, 
	CP.DES_CAMPANHA, 
	UV.NOM_FANTASI 
	FROM  whatsapp_lista_ret SLR
	INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
	LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
	left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
	WHERE SLR.COD_EMPRESA = $cod_empresa
	$andData
	AND SLR.COD_UNIVEND IN(9999,0,$lojasSelecionadas)
	$andCampanha
	$andCliente
	$andCpf
	$andCelular
	$andOpt
	$andRetorno
	ORDER BY SLR.DAT_CADASTR DESC
	LIMIT $inicio, $itens_por_pagina";

		//fnEscreve($sql);

		//fnTestesql(connTemp($cod_empresa,''),$sql);											
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrRetorno = mysqli_fetch_assoc($arrayQuery)) {

			$count++;

			if ($autoriza == 1) {
				$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrRetorno['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</a></small></td>";
			} else {
				$colCliente = "<td><small>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</small></td>";
			}

			if ($qrRetorno['BOUNCE'] == 0) {
				$status = "<span class='fal fa-check text-success'></span>";
			} else {
				$status = "<span class='fal fa-times text-danger'></span>";
			}

			echo "
		<tr>
		" . $colCliente . "
		<td><small>" . $qrRetorno['COD_CLIENTE'] . "</small></td>
		<td><small>" . $qrRetorno['NUM_CGCECPF'] . "</small></td>
		<td><small class='sp_celphones'>" . $qrRetorno['NUM_CELULAR'] . "</small></td>
		<td><small>" . $qrRetorno['DES_CAMPANHA'] . "</small></td>
		<td><small>" . $qrRetorno['NOM_FANTASI'] . "</small></td>
		<td><small>" . $qrRetorno['DES_MSG_ENVIADA'] . "</small></td>
		<td class='text-center'><small>" . $status . "</small></td>
		<td class='text-center'><small>" . $qrRetorno['SESSAO'] . "</small></td>
		<td class='text-center'><small>" . fnDataFull($qrRetorno['DAT_CADASTR']) . "</small></td>
		</tr>
		";
		}

		break;
}
