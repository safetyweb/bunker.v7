<?php

include './_system/_functionsMain.php';
require_once './js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['id']);
$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
$cod_disparo = fnLimpaCampoZero($_REQUEST['COD_DISPARO']);

$nomeRel = $_GET['nomeRel'];
$arquivo = './media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

$writer = WriterFactory::create(Type::CSV);
$writer->setFieldDelimiter(';');
$writer->openToFile($arquivo);

switch ($opcao) {

	case 'links':

		$arrayColumnsNames = "";

		$sql = "SELECT LT.DES_LINK, 
						   CLK.COD_LINK 
					FROM link_template LT
					INNER JOIN click_links CLK ON CLK.COD_LINK = LT.COD_LINK
					WHERE CLK.COD_EMPRESA = $cod_empresa
					AND CLK.COD_CAMPANHA = $cod_campanha
					GROUP BY LT.COD_LINK";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			array_push($newRow, $qrGraph["DES_LINK"]);

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "NOME");
			// array_push($newRow, "COD. CLIENTE");
			array_push($newRow, "QTD. CLIQUES");

			$array[] = $newRow;
			$newRow = array();

			$sqlLink = "SELECT CLK.COD_CLIENTE, 
									CL.NOM_CLIENTE, 
									COUNT(CLK.ID) AS QTD_CLIQUES 
							FROM click_links CLK
							INNER JOIN clientes CL ON CL.COD_CLIENTE = CLK.COD_CLIENTE AND CL.COD_EMPRESA = $cod_empresa
							WHERE CLK.COD_LINK = $qrGraph[COD_LINK]
							GROUP BY CLK.COD_CLIENTE";

			$arrayLinks = mysqli_query(connTemp($cod_empresa, ''), $sqlLink);

			while ($qrLink = mysqli_fetch_assoc($arrayLinks)) {

				array_push($newRow, $qrLink["NOM_CLIENTE"]);
				// array_push($newRow, $qrLink["COD_CLIENTE"]);
				array_push($newRow, $qrLink["QTD_CLIQUES"]);

				$array[] = $newRow;
				$newRow = array();
			}

			array_push($newRow, "");
			// array_push($newRow, "");
			array_push($newRow, "");

			$array[] = $newRow;
			$newRow = array();
		}

		break;

	case 'sent':
	case 'notsent':
	case 'all':
	case 'optout':
	case 'bounce':
	case 'wait':

		if ($opcao == 'sent') {
			$andFiltroOpcao = "AND CASE
							        WHEN ELR.cod_cconfirmacao = '1' THEN '1'
							        WHEN ELR.cod_sconfirmacao = '1' THEN '1'
							        ELSE '0'
							        END IN ( 1, 1 )";
		} else if ($opcao == 'notsent') {
			$andFiltroOpcao = "AND ELR.COD_NRECEBIDO = 1";
		} else if ($opcao == 'optout') {
			$andFiltroOpcao = "AND ELR.COD_OPTOUT_ATIVO = 1";
		} else if ($opcao == 'bounce') {
			$andFiltroOpcao = "AND ELR.BOUNCE = 1";
		} else if ($opcao == 'wait') {
			$andFiltroOpcao = "AND ELR.COD_LEITURA = 0 
								   AND ELR.BOUNCE = 0
								   AND ELR.COD_OPTOUT_ATIVO = 0";
		} else {
			$andFiltroOpcao = "";
		}

		$sqlRel = "SELECT ELR.COD_CLIENTE, 
								 ELR.NOM_CLIENTE,
								 CL.DES_EMAILUS,
								 ELR.NUM_CELULAR,
								 CL.NUM_CGCECPF AS CPF,
								 ELR.DAT_CADASTR AS DAT_ENVIO,
								 UNV.NOM_FANTASI
						FROM SMS_LISTA_RET ELR
						LEFT JOIN clientes CL ON CL.COD_CLIENTE = ELR.COD_CLIENTE AND CL.COD_EMPRESA = $cod_empresa
						LEFT JOIN UNIDADEVENDA AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
						WHERE ELR.COD_EMPRESA = $cod_empresa
						AND ELR.COD_CAMPANHA = $cod_campanha
						AND STATUS_ENVIO='S'
						$andFiltroOpcao
						ORDER BY CL.NOM_CLIENTE";

		fnEscreve($sqlRel);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlRel);

		$array = array();

		$arrayColumnsNames = array();

		array_push($arrayColumnsNames, "COD_CLIENTE");
		array_push($arrayColumnsNames, "NOME");
		array_push($arrayColumnsNames, "EMAIL");
		array_push($arrayColumnsNames, "CELULAR");
		array_push($arrayColumnsNames, "CPF");
		array_push($arrayColumnsNames, "DT.ENVIO");
		array_push($arrayColumnsNames, "LOJA CADASTRO");


		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			array_push($newRow, $qrGraph['COD_CLIENTE']);
			array_push($newRow, $qrGraph['NOM_CLIENTE']);
			array_push($newRow, $qrGraph['DES_EMAILUS']);
			array_push($newRow, $qrGraph['NUM_CELULAR']);
			array_push($newRow, $qrGraph['CPF']);
			array_push($newRow, fnDataFull($qrGraph['DAT_ENVIO']));
			array_push($newRow, $qrGraph['NOM_FANTASI']);

			$array[] = $newRow;
		}

		break;

	default:

		$sql = "SELECT SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS, 
							SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO, 
							SUM(CEM.QTD_NRECEBIDO) AS QTD_NENTREGUES,  
							SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT, 
							SUM(CEM.QTD_FALHA) AS BOUNCE,
							SUM(CEM.QTD_AGUARADANDO) AS QTD_AGUARDO,
							SUM(EL.QTD_LISTA) AS QTD_LISTA
							FROM SMS_LOTE EL
							LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
							WHERE CEM.COD_EMPRESA = $cod_empresa
							AND CEM.COD_CAMPANHA = $cod_campanha 
							AND EL.LOG_ENVIO = 'S' 
							AND EL.LOG_TESTE = 'N' 
							AND CEM.COD_DISPARO IS NOT NULL";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$lista_gerada = $qrGraph['QTD_LISTA'];

			$contatos_graph = $qrGraph['QTD_LISTA'];
			$nentregues_graph = $qrGraph['QTD_NENTREGUES'];
			$disparados_graph = $qrGraph['QTD_DISPARADOS'];
			$sucesso_graph = $qrGraph['QTD_SUCESSO'];
			$falha_graph = $qrGraph['BOUNCE'];
			$optout_graph = $qrGraph['QTD_OPTOUT'];
			$aguardo_graph = $qrGraph['QTD_AGUARDO'];

			$perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
			$perc_nentregue = fnValorSql(fnValor(($nentregues_graph / $contatos_graph) * 100, 2));
			$perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
			$perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
			$perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));

			array_push($newRow, "LISTA DE ENVIO");
			array_push($newRow, $contatos_graph);
			array_push($newRow, "100.00%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "ENTREGUES");
			array_push($newRow, $sucesso_graph);
			array_push($newRow, $perc_sucesso . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "OPT OUT");
			array_push($newRow, $optout_graph);
			array_push($newRow, $perc_optout . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "FALHAS");
			array_push($newRow, $falha_graph);
			array_push($newRow, $perc_falha . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "EM AGUARDO");
			array_push($newRow, $aguardo_graph);
			array_push($newRow, $perc_aguardo . "%");

			$array[] = $newRow;
			$newRow = array();
		}

		array_push($newRow, "");
		array_push($newRow, "");
		array_push($newRow, "");

		$array[] = $newRow;
		$newRow = array();

		$arrayColumnsNames = array();

		array_push($arrayColumnsNames, "TIPO");
		array_push($arrayColumnsNames, "QUANTIDADE");
		array_push($arrayColumnsNames, "PERCENTUAL");

		break;
}

if ($arrayColumnsNames != "") {

	$writer->addRow($arrayColumnsNames);
}
$writer->addRows($array);

$writer->close();
