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
$num_cgcecpf = "";
$lojasSelecionadas = "";
$cod_campanha = "";
$num_celular = "";
$autoriza = "";
$log_optout = "";
$log_retorno = "";
$dias30 = "";
$hoje = "";
$andCpf = "";
$andCelular = "";
$andCampanha = "";
$andOpt = "";
$andData = "";
$andRetorno = "";
$curl = "";
$response = "";
$err = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$verdadeiro = "";
$arrayColumnsNames = [];
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrRetorno = "";
$recebido = "";
$confirmacao = "";
$bounce = "";
$optout = "";
$colCliente = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
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

if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
	$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
} else {
	$andCpf = "";
}

if ($num_celular != '' && $num_celular != 0) {
	$andCelular = "AND SLR.NUM_CELULAR = '" . fnLimpaDoc($num_celular) . "'";
} else {
	$andCelular = "";
}

if ($cod_campanha != 0 && $cod_campanha != '') {
	$andCampanha = "AND SLR.COD_CAMPANHA = $cod_campanha";
} else {
	$andCampanha = "";
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

	case 'reprocessar':

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://externo.bunker.mk/nexux/disparo_fast_dlr.php?COD_EMPRESA=' . $cod_empresa,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 18000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "",
			CURLOPT_HTTPHEADER => array(
				"Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		echo 1;

		break;

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT  SLR.DAT_CADASTR,
								CL.COD_CLIENTE,
								SLR.NOM_CLIENTE,
								CL.NUM_CGCECPF,
								SLR.NUM_CELULAR,
								UV.NOM_FANTASI, 
								CP.DES_CAMPANHA, 
								SLR.COD_NRECEBIDO,
								SLR.COD_CCONFIRMACAO,
								SLR.BOUNCE,
								SLR.COD_OPTOUT_ATIVO, 
						FROM SMS_LISTA_RET SLR
						INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
						LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
						left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
						WHERE SLR.COD_EMPRESA = $cod_empresa
						AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
						AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
						$andCpf
						$andCelular
						$andCampanha
						$andOpt
						$andRetorno
						ORDER BY SLR.DAT_CADASTR DESC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cont = 0;
			foreach ($row as $objeto) {

				$verdadeiro = "";

				if ($cont == 7) {

					$verdadeiro = "N";

					if ($objeto == 0) {

						$verdadeiro = "S";
					}

					array_push($newRow, $verdadeiro);
				} else if ($cont >= 8 && $cont <= 10) {

					if ($objeto == 1) {

						$verdadeiro = "S";
					}

					array_push($newRow, $verdadeiro);
				} else {

					array_push($newRow, $objeto);
				}
			}

			$cont++;
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'paginar':

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT SLR.COD_LISTA FROM SMS_LISTA_RET SLR
					INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
					left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
					WHERE SLR.COD_EMPRESA = $cod_empresa
					AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
					$andCampanha
					$andCpf
					$andCelular
					$andOpt
					$andRetorno";
		//fnTestesql(connTemp($cod_empresa,''),$sql);		
		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT  SLR.DAT_CADASTR,
							CL.COD_CLIENTE,
							SLR.NOM_CLIENTE,
							CL.NUM_CGCECPF,
							SLR.NUM_CELULAR,
							SLR.DES_MOTIVO,
							SLR.DES_STATUS,
							SLR.COD_OPTOUT_ATIVO,
							SLR.COD_CCONFIRMACAO,
							SLR.BOUNCE,
							SLR.COD_NRECEBIDO,
							SLR.DES_MSG_ENVIADA, 
							CP.DES_CAMPANHA, 
							UV.NOM_FANTASI 
					FROM SMS_LISTA_RET SLR
					INNER JOIN CAMPANHA CP ON CP.COD_CAMPANHA = SLR.COD_CAMPANHA
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = SLR.COD_CLIENTE
					left JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = SLR.COD_UNIVEND
					WHERE SLR.COD_EMPRESA = $cod_empresa
					AND SLR.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
					AND SLR.COD_UNIVEND IN(0,$lojasSelecionadas)
					$andCpf
					$andCelular
					$andCampanha
					$andOpt
					$andRetorno
					ORDER BY SLR.DAT_CADASTR DESC
					LIMIT $inicio, $itens_por_pagina";

		// fnEscreve($sql);

		//fnTestesql(connTemp($cod_empresa,''),$sql);											
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrRetorno = mysqli_fetch_assoc($arrayQuery)) {

			$recebido = "<span class='fal fa-times text-danger'></span>";
			$confirmacao = "";
			$bounce = "";
			$optout = "";

			if ($qrRetorno['COD_NRECEBIDO'] == 0) {
				$recebido = "<span class='fal fa-check'></span>";
			}

			if ($qrRetorno['COD_CCONFIRMACAO'] == 1) {
				$confirmacao = "<span class='fal fa-check'></span>";
			}

			if ($qrRetorno['BOUNCE'] == 1) {
				$bounce = "<span class='fal fa-check'></span>";
			}

			if ($qrRetorno['COD_OPTOUT_ATIVO'] == 1) {
				$optout = "<span class='fal fa-check'></span>";
			}

			$count++;

			if ($autoriza == 1) {
				$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrRetorno['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</a></small></td>";
			} else {
				$colCliente = "<td><small>" . fnMascaraCampo($qrRetorno['NOM_CLIENTE']) . "</small></td>";
			}

			echo "
					<tr>
					  " . $colCliente . "
					  <!-- <td><small>" . fnMascaraCampo($qrRetorno['NUM_CGCECPF']) . "</small></td> -->
						<td><small>" . $qrRetorno['NUM_CGCECPF'] . "</small></td>
					  <td><small class='sp_celphones'>" . $qrRetorno['NUM_CELULAR'] . "</small></td>
					  <td><small>" . $qrRetorno['DES_CAMPANHA'] . "</small></td>
					  <td><small>" . $qrRetorno['NOM_FANTASI'] . "</small></td>
					  <td class='text-center'><small>" . fnDataFull($qrRetorno['DAT_CADASTR']) . "</small></td>
					  <td class='text-center'><small>" . $recebido . "</small></td>
					</tr>
					";
		}

		break;
}
