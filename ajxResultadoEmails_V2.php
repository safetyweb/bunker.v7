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

		$sql = "SELECT DISTINCT(COUNT(links.cod_link)) AS CLIQUES, 
					temp.DES_LINK,
					links.COD_LINK
					FROM click_links links
					INNER JOIN link_template temp ON links.COD_LINK=temp.COD_LINK
					 INNER JOIN  email_lote lot ON lot.COD_DISPARO_EXT=links.cod_disparo

					WHERE links.COD_EMPRESA = $cod_empresa AND LOT.COD_CAMPANHA = $cod_campanha
					GROUP BY links.COD_LINK";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			array_push($newRow, $qrGraph["DES_LINK"]);

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "");
			array_push($newRow, "CÓDIGO");
			array_push($newRow, "NOME");
			array_push($newRow, "EMAIL");
			array_push($newRow, "CPF");
			// array_push($newRow, "COD. CLIENTE");
			array_push($newRow, "QTD. CLIQUES");

			$array[] = $newRow;
			$newRow = array();

			$sqlLink = "SELECT CLK.COD_CLIENTE, 
									CL.NOM_CLIENTE,
									CL.COD_CLIENTE,
									CL.DES_EMAILUS,
									CL.NUM_CGCECPF AS CPF,
									COUNT(CLK.ID) AS QTD_CLIQUES 
							FROM click_links CLK
							INNER JOIN clientes CL ON CL.COD_CLIENTE = CLK.COD_CLIENTE AND CL.COD_EMPRESA = $cod_empresa
							INNER JOIN  email_lote lot ON lot.COD_DISPARO_EXT=CLK.cod_disparo
							WHERE CLK.COD_LINK = $qrGraph[COD_LINK]
							AND LOT.COD_CAMPANHA = $cod_campanha
							GROUP BY CLK.COD_CLIENTE";

			$arrayLinks = mysqli_query(connTemp($cod_empresa, ''), $sqlLink);

			//fnEscreve($sqlLink);

			$totCliques = "";

			while ($qrLink = mysqli_fetch_assoc($arrayLinks)) {

				array_push($newRow, "");
				array_push($newRow, $qrLink["COD_CLIENTE"]);
				array_push($newRow, $qrLink["NOM_CLIENTE"]);
				array_push($newRow, $qrLink["DES_EMAILUS"]);
				array_push($newRow, $qrLink["CPF"]);
				array_push($newRow, $qrLink["QTD_CLIQUES"]);
				$totCliques += $qrLink["QTD_CLIQUES"];

				$array[] = $newRow;
				$newRow = array();
			}

			array_push($newRow, '');
			array_push($newRow, 'TOTAL');
			array_push($newRow, $totCliques);

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "");
			array_push($newRow, "");
			array_push($newRow, "");

			$array[] = $newRow;
			$newRow = array();
		}

		break;

	case 'lidos':
	case 'spams':
	case 'nlidos':
	case 'hbounce':
	case 'sbounce':
	case 'optout':
	case 'sent':
	case 'all':

		if ($opcao == 'lidos') {
			$andFiltroOpcao = "AND ELR.COD_LEITURA = 1";
		} else if ($opcao == 'nlidos') {
			$andFiltroOpcao = "and ELR.ENTREGUE =1
							      AND ELR.COD_LEITURA =0
									AND ELR.COD_OPTOUT_ATIVO=0
								   ";
		} else if ($opcao == 'hbounce') {
			$andFiltroOpcao = "AND ELR.BOUNCE = 1";
		} else if ($opcao == 'sbounce') {
			$andFiltroOpcao = "AND ELR.BOUNCE = 2";
		} else if ($opcao == 'optout') {
			$andFiltroOpcao = "AND ELR.COD_OPTOUT_ATIVO = 1";
		} else if ($opcao == 'spam') {
			$andFiltroOpcao = "AND ELR.SPAM = 1";
		} else if ($opcao == 'sent') {
			$andFiltroOpcao = "AND ELR.ENTREGUE = 1";
		} else if ($opcao == 'spams') {
			$andFiltroOpcao = "AND ELR.SPAM = 1";
		} else {
			$andFiltroOpcao = "";
		}

		$sqlRel = "SELECT ELR.COD_CLIENTE, 
								 CL.NOM_CLIENTE,
								 CL.DES_EMAILUS,
								 CL.NUM_CGCECPF AS CPF,
								 ELR.DAT_CADASTR AS DAT_ENVIO,
								 UNV.NOM_FANTASI
						FROM EMAIL_LISTA_RET ELR
						INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = ELR.COD_CLIENTE
						LEFT JOIN UNIDADEVENDA AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
						WHERE ELR.COD_EMPRESA = $cod_empresa
						AND ELR.COD_CAMPANHA = $cod_campanha
						$andFiltroOpcao
						ORDER BY ELR.NOM_CLIENTE";

		//fnEscreve($sqlRel);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlRel);

		$array = array();

		$arrayColumnsNames = array();

		array_push($arrayColumnsNames, "COD_CLIENTE");
		array_push($arrayColumnsNames, "NOME");
		array_push($arrayColumnsNames, "EMAIL");
		array_push($arrayColumnsNames, "CPF");
		array_push($arrayColumnsNames, "DT. ENVIO");
		array_push($arrayColumnsNames, "LOJA CADASTRO");

		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			array_push($newRow, $qrGraph['COD_CLIENTE']);
			array_push($newRow, $qrGraph['NOM_CLIENTE']);
			array_push($newRow, $qrGraph['DES_EMAILUS']);
			array_push($newRow, $qrGraph['CPF']);
			array_push($newRow, fnDataFull($qrGraph['DAT_ENVIO']));
			array_push($newRow, $qrGraph['NOM_FANTASI']);


			$array[] = $newRow;
		}

		break;

	default:

		$sql = "SELECT
					SUM(CEM.QTD_CONTATOS) AS QTD_CONTATOS,
					SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
					SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
					SUM(CEM.QTD_EXCLUSAO) AS QTD_EXCLUSAO,
					SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
					SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
					SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
					SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
					SUM(CEM.ERROR_PERM) AS QTD_HARD,
					SUM(CEM.ERROR_TEMP) AS QTD_SOFT,
					SUM(CEM.SPAN) AS QTD_SPAM,
					SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
					SUM(EL.QTD_LISTA) as QTD_LISTA,
					EL.LOG_TESTE
					FROM EMAIL_LOTE EL
					LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
					LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
					AND COD_TEMPLATE=(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL TE WHERE  TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S')
					WHERE EL.COD_EMPRESA = $cod_empresa
					AND EL.COD_CAMPANHA = $cod_campanha
					AND EL.LOG_ENVIO = 'S'
					AND CEM.COD_DISPARO IS NOT NULL
					GROUP BY CEM.COD_CAMPANHA 
					ORDER BY EL.cod_controle ASC";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($qrGraph = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$lista_gerada = $qrGraph['QTD_LISTA'];
			$graph_hard = $qrGraph['QTD_HARD'];
			$graph_soft = $qrGraph['QTD_SOFT'];
			$graph_spam = $qrGraph['QTD_SPAM'];
			$contatos_graph = $qrGraph['QTD_LISTA'];
			$exclusao_graph = $qrGraph['QTD_EXCLUSAO'];
			$disparados_graph = $qrGraph['QTD_DISPARADOS'];
			$sucesso_graph = $qrGraph['QTD_SUCESSO'];
			$falha_graph = $qrGraph['QTD_CONTATOS'] - $qrGraph['QTD_SUCESSO'];
			$lidos_graph = $qrGraph['QTD_LIDOS'];
			$nlidos_graph = $qrGraph['QTD_NLIDOS'];
			$optout_graph = $qrGraph['QTD_OPTOUT'];
			$cliques_graph = $qrGraph['QTD_CLIQUES'];
			$ncliques_graph = $qrGraph['QTD_CONTATOS'] - $qrGraph['QTD_CLIQUES'];
			$perc_sucesso = fnValor(($sucesso_graph / $contatos_graph) * 100, 2);
			$perc_falha = fnValor(($falha_graph / $contatos_graph) * 100, 2);
			$perc_lidos = fnValor(($lidos_graph / $contatos_graph) * 100, 2);
			$perc_nlidos = fnValor(($nlidos_graph / $contatos_graph) * 100, 2);
			$perc_cliques = fnValor(($cliques_graph / $contatos_graph) * 100, 2);
			$perc_ncliques = fnValor(($ncliques_graph / $contatos_graph) * 100, 2);
			$perc_optout = fnValor(($optout_graph / $contatos_graph) * 100, 2);
			$perc_hard = fnValor(($graph_hard / $contatos_graph) * 100, 2);
			$perc_soft = fnValor(($graph_soft / $contatos_graph) * 100, 2);
			$perc_spam = fnValor(($graph_spam / $contatos_graph) * 100, 2);

			array_push($newRow, "LISTA DE ENVIO");
			array_push($newRow, $contatos_graph);
			array_push($newRow, "100%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "ENTREGUES");
			array_push($newRow, $sucesso_graph);
			array_push($newRow, $perc_sucesso . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "LIDOS");
			array_push($newRow, $lidos_graph);
			array_push($newRow, $perc_lidos . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "CLIENTES C/ CLIQUES");
			array_push($newRow, $cliques_graph);
			array_push($newRow, $perc_cliques . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "OPT OUT");
			array_push($newRow, $optout_graph);
			array_push($newRow, $perc_optout . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "SOFT BOUNCE");
			array_push($newRow, $graph_soft);
			array_push($newRow, $perc_soft . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "HARD BOUNCE");
			array_push($newRow, $graph_hard);
			array_push($newRow, $perc_hard . "%");

			$array[] = $newRow;
			$newRow = array();

			array_push($newRow, "SPAM");
			array_push($newRow, $graph_spam);
			array_push($newRow, $perc_spam . "%");

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

		array_push($newRow, "LINK");
		array_push($newRow, "CLIQUES");

		$array[] = $newRow;
		$newRow = array();

		$sql = "SELECT DISTINCT(COUNT(links.cod_link)) AS CLIQUES, temp.DES_LINK
					FROM click_links links
					INNER JOIN link_template temp ON links.COD_LINK=temp.COD_LINK
					 INNER JOIN  email_lote lot ON lot.COD_DISPARO_EXT=links.cod_disparo

					WHERE links.COD_EMPRESA = $cod_empresa AND LOT.COD_CAMPANHA = $cod_campanha
					GROUP BY links.COD_LINK";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		$alturaTela = 600;
		while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

			array_push($newRow, $qrBuscaModulos['DES_LINK']);
			array_push($newRow, $qrBuscaModulos['CLIQUES']);

			$array[] = $newRow;
			$newRow = array();
		}

		break;
}

if ($arrayColumnsNames != "") {

	$writer->addRow($arrayColumnsNames);
}
$writer->addRows($array);

$writer->close();
