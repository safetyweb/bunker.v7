<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$opcao = $_GET['opcao'];
$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);

// fnEscreve('chega aqui');

switch ($opcao) {

	case 'exportar':

		$log_detalhes = fnLimpaCampo($_GET['detalhes']);
		// fnEscreve($_GET['detalhes']);

		$nomeRel = $_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$andData = "AND (EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' /*OR CEM.DAT_ENVIO IS NULL*/)";

		if ($log_detalhes == 'N') {


			if ($cod_campanha != 0) {
				$andCampanha = "AND EL.COD_CAMPANHA = $cod_campanha";
				$andData = "";
			} else {
				$andCampanha = "";
			}

			$sql = "SELECT
		                    CEM.COD_DISPARO,
		                    SUM(CEM.QTD_DIFERENCA) AS QTD_DIFERENCA,
		                    SUM(CEM.QTD_CONTATOS) AS QTD_CONTATOS,
		                    SUM(CEM.QTD_EXCLUSAO) AS QTD_EXCLUSAO,
		                    CP.COD_CAMPANHA,
		                    SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
		                    SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
		                    SUM(CEM.QTD_FALHA) AS QTD_FALHA,
		                    SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
		                    SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
		                    SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
		                    SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
		                    -- CEM.DAT_ENVIO,
		                    TE.NOM_TEMPLATE,
		                    SUM(EL.QTD_LISTA) AS QTD_LISTA,
		                    EL.DES_PATHARQ,
		                    EL.COD_GERACAO,
		                    EL.COD_CONTROLE,
		                    EL.COD_LOTE,
		                    CP.DES_CAMPANHA,
		                    MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO
						FROM EMAIL_LOTE EL
							LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
							LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S'
							AND COD_TEMPLATE=(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL TE WHERE  TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S')
							LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
						WHERE EL.COD_EMPRESA = $cod_empresa 
							AND EL.LOG_ENVIO = 'S'
							AND EL.LOG_TESTE = 'N'
							$andCampanha
							$andData
						GROUP BY EL.COD_CAMPANHA, CEM.COD_DISPARO
						ORDER BY EL.COD_CONTROLE DESC
						";
		} else {

			$cod_campanha = $log_detalhes;

			$sql = "SELECT
							CEM.COD_DISPARO,
							CEM.QTD_DIFERENCA,
							CEM.QTD_CONTATOS,
							CEM.QTD_EXCLUSAO,
							CEM.QTD_DISPARADOS,
							CEM.QTD_SUCESSO,
							CEM.QTD_FALHA,
							CEM.QTD_LIDOS,
							CEM.QTD_NLIDOS,
							CEM.QTD_OPTOUT,
							CEM.QTD_CLIQUES,
							CEM.DAT_ENVIO,
							TE.NOM_TEMPLATE,
							EL.QTD_LISTA,
							EL.DES_PATHARQ,
							EL.COD_GERACAO,
							EL.COD_CONTROLE,
							EL.COD_LOTE,
							EL.LOG_TESTE
						FROM EMAIL_LOTE EL
						LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
						LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S'
						AND COD_TEMPLATE=(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL TE WHERE  TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S')
						WHERE EL.COD_EMPRESA = $cod_empresa
						AND EL.COD_CAMPANHA = $cod_campanha
						AND EL.LOG_ENVIO = 'S'
						AND EL.LOG_TESTE = 'N'
						GROUP BY EL.COD_DISPARO_EXT
						$andData
						ORDER BY EL.COD_CONTROLE DESC";
		}

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$pct_sucesso = ($row['QTD_SUCESSO'] / $row['QTD_DISPARADOS']) * 100;
			$pct_falha = ($row['QTD_FALHA'] / $row['QTD_DISPARADOS']) * 100;
			$pct_lidos = ($row['QTD_LIDOS'] / $row['QTD_DISPARADOS']) * 100;
			$pct_nlidos = ($row['QTD_NLIDOS'] / $row['QTD_DISPARADOS']) * 100;

			if ($row['COD_GERACAO'] != '') {
				$pref = $row['COD_GERACAO'];
			} else {
				if ($row['LOG_TESTE'] != 'S') {
					$pref = 'ANIV';
				} else {
					$pref = 'TESTE';
				}
			}

			if ($row['DAT_ENVIO'] == "") {
				$dat_envio = "Em Andamento";
			} else {
				$dat_envio = fnDataFull($row['DAT_ENVIO']);
			}

			if ($log_detalhes == 'N') {
				array_push($newRow, $row['DES_CAMPANHA']);
			} else {
				array_push($newRow, $pref . " Geração do lote #" . $row['COD_CONTROLE'] . "/" . $row['COD_LOTE'] . " - " . $row['COD_DISPARO']);
				array_push($newRow, $row['NOM_TEMPLATE']);
			}

			array_push($newRow, $dat_envio);
			array_push($newRow, fnValor($row['QTD_LISTA'], 0));
			array_push($newRow, fnValor($row['QTD_CONTATOS'], 0));
			array_push($newRow, fnValor($row['QTD_EXCLUSAO'] + $row['QTD_DIFERENCA'], 0));
			array_push($newRow, fnValor($row['QTD_DISPARADOS'], 0));
			array_push($newRow, fnValor($row['QTD_SUCESSO'], 0));
			array_push($newRow, fnValor($pct_sucesso, 2));
			array_push($newRow, fnValor($row['QTD_FALHA'], 0));
			array_push($newRow, fnValor($pct_falha, 2));
			array_push($newRow, fnValor($row['QTD_LIDOS'], 0));
			array_push($newRow, fnValor($pct_lidos, 2));
			array_push($newRow, fnValor($row['QTD_NLIDOS'], 0));
			array_push($newRow, fnValor($pct_nlidos, 2));
			array_push($newRow, fnValor($row['QTD_OPTOUT'], 0));
			array_push($newRow, fnValor($row['QTD_CLIQUES'], 0));

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();

		if ($log_detalhes == 'N') {
			array_push($arrayColumnsNames, "Campanha");
		} else {
			array_push($arrayColumnsNames, "Lote");
			array_push($arrayColumnsNames, "Template");
		}

		array_push($arrayColumnsNames, "Data de Envio");
		array_push($arrayColumnsNames, "Enviados");
		array_push($arrayColumnsNames, "Filtrados");
		array_push($arrayColumnsNames, "Exclusão");
		array_push($arrayColumnsNames, "Disparados");
		array_push($arrayColumnsNames, "Sucesso");
		array_push($arrayColumnsNames, "% Sucesso ");
		array_push($arrayColumnsNames, "Falhas ");
		array_push($arrayColumnsNames, "% Falhas ");
		array_push($arrayColumnsNames, "Lidos ");
		array_push($arrayColumnsNames, "% Lidos ");
		array_push($arrayColumnsNames, "Não Lidos ");
		array_push($arrayColumnsNames, "% Não Lidos ");
		array_push($arrayColumnsNames, "Opt Out ");
		array_push($arrayColumnsNames, "Cliques ");

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	default:

		$andData = fnDecode($_REQUEST['DATA']);

		// $sql = "SELECT
		// 			CEM.COD_DISPARO,
		// 			SUM(CEM.QTD_DIFERENCA) AS QTD_DIFERENCA,
		// 			SUM(CEM.QTD_CONTATOS) AS QTD_CONTATOS,
		// 			SUM(CEM.QTD_EXCLUSAO) AS QTD_EXCLUSAO,
		// 			SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
		// 			SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO,
		// 			SUM(CEM.QTD_FALHA) AS QTD_FALHA,
		// 			SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
		// 			SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
		// 			SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
		// 			SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
		// 			(SUM(CEM.ERROR_PERM)+SUM(CEM.QTD_IMPORT_ERRO)) AS QTD_HARD,
		//                      SUM(CEM.ERROR_TEMP) AS QTD_SOFT,
		//                      SUM(CEM.SPAN) AS QTD_SPAM,
		// 			TE.NOM_TEMPLATE,
		// 			EL.QTD_LISTA,
		// 			EL.DES_PATHARQ,
		// 			EL.COD_GERACAO,
		// 			EL.COD_CONTROLE,
		// 			EL.COD_LOTE,
		// 			EL.LOG_TESTE,
		// 			EL.DAT_AGENDAMENTO AS DAT_ENVIO
		// 		FROM EMAIL_LOTE EL
		// 		LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
		// 		LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S'
		// 		WHERE EL.COD_EMPRESA = $cod_empresa
		// 		AND EL.COD_CAMPANHA = $cod_campanha
		// 		AND EL.LOG_ENVIO = 'S'
		// 		AND EL.LOG_TESTE = 'N'
		// 		$andData
		// 		GROUP BY EL.COD_DISPARO_EXT
		// 		ORDER BY EL.COD_CONTROLE DESC, CEM.DAT_ENVIO ASC";

		$sql = "SELECT
						CEM.COD_DISPARO,
						CEM.QTD_DIFERENCA AS QTD_DIFERENCA,
						CEM.QTD_CONTATOS AS QTD_CONTATOS,
						CEM.QTD_EXCLUSAO AS QTD_EXCLUSAO,
						CEM.QTD_DISPARADOS AS QTD_DISPARADOS,
						CEM.QTD_SUCESSO AS QTD_SUCESSO,
						CEM.QTD_FALHA AS QTD_FALHA,
						CEM.QTD_LIDOS AS QTD_LIDOS,
						CEM.QTD_NLIDOS AS QTD_NLIDOS,
						CEM.QTD_OPTOUT AS QTD_OPTOUT,
						CEM.QTD_CLIQUES AS QTD_CLIQUES,
						(CEM.ERROR_PERM+CEM.QTD_IMPORT_ERRO) AS QTD_HARD,
                        CEM.ERROR_TEMP AS QTD_SOFT,
                        CEM.SPAN AS QTD_SPAM,
						TE.NOM_TEMPLATE,
						EL.QTD_LISTA,
						EL.DES_PATHARQ,
						EL.COD_GERACAO,
						EL.COD_CONTROLE,
						EL.COD_LOTE,
						EL.LOG_TESTE,
						EL.DAT_AGENDAMENTO AS DAT_ENVIO
					FROM EMAIL_LOTE EL
					LEFT JOIN CONTROLE_ENTREGA_MAIL CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
					LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S'
					AND COD_TEMPLATE=(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL TE WHERE  TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S')
					WHERE EL.COD_EMPRESA = $cod_empresa
					AND EL.COD_CAMPANHA = $cod_campanha
					AND EL.LOG_ENVIO = 'S'
					$andData
					GROUP BY  case when CEM.COD_DISPARO IS NULL then   EL.COD_CONTROLE Else  EL.COD_DISPARO_EXT  END
					ORDER BY EL.COD_CONTROLE DESC, CEM.DAT_ENVIO ASC";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

		if (mysqli_num_rows($arrayQuery) > 0) {

			$count = 0;
			while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
				$count++;

				if ($qrBuscaModulos['DES_PATHARQ'] != '') {
					$urlAnexo = '<a href="' . $qrBuscaModulos['DES_PATHARQ'] . '" download><span class="fa fa-download"></span></a>';
				} else {
					$urlAnexo = '';
				}

				if ($qrBuscaModulos['COD_GERACAO'] != '') {
					$pref = $qrBuscaModulos['COD_GERACAO'];
				}

				if ($qrBuscaModulos['LOG_TESTE'] == 'S') {
					$pref = 'TESTE';
					// $data_envio = fnDataFull($qrBuscaModulos['DAT_AGENDAMENTO']);
				}

				// $pref = "";

				$lista_gerada = $qrBuscaModulos[QTD_LISTA];
				$graph_hard = $qrBuscaModulos[QTD_HARD];
				$graph_soft = $qrBuscaModulos[QTD_SOFT];
				$graph_spam = $qrBuscaModulos[QTD_SPAM];
				$contatos_graph = $qrBuscaModulos[QTD_LISTA];
				$exclusao_graph = $qrBuscaModulos[QTD_EXCLUSAO];
				$disparados_graph = $qrBuscaModulos[QTD_DISPARADOS];
				$sucesso_graph = $qrBuscaModulos[QTD_SUCESSO];
				$falha_graph = $qrBuscaModulos[QTD_CONTATOS] - $qrBuscaModulos[QTD_SUCESSO];
				$lidos_graph = $qrBuscaModulos[QTD_LIDOS];
				$nlidos_graph = $qrBuscaModulos[QTD_NLIDOS];
				$optout_graph = $qrBuscaModulos[QTD_OPTOUT];
				$cliques_graph = $qrBuscaModulos[QTD_CLIQUES];
				$ncliques_graph = $qrBuscaModulos[QTD_CONTATOS] - $qrBuscaModulos[QTD_CLIQUES];
				$perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
				$perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
				$perc_lidos = fnValorSql(fnValor(($lidos_graph / $contatos_graph) * 100, 2));
				$perc_nlidos = fnValorSql(fnValor(($nlidos_graph / $contatos_graph) * 100, 2));
				$perc_cliques = fnValorSql(fnValor(($cliques_graph / $contatos_graph) * 100, 2));
				$perc_ncliques = fnValorSql(fnValor(($ncliques_graph / $contatos_graph) * 100, 2));
				$perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
				$perc_hard = fnValorSql(fnValor(($graph_hard / $contatos_graph) * 100, 2));
				$perc_soft = fnValorSql(fnValor(($graph_soft / $contatos_graph) * 100, 2));
				$perc_spam = fnValorSql(fnValor(($graph_spam / $contatos_graph) * 100, 2));

?>

				<tr>
					<!-- <td class="text-center"><small><?= $urlAnexo ?></small></td> -->
					<td></td>
					<td></td>
					<td>
						<small><span style="font-size: 10px;"><?= $pref ?></span>&nbsp;Geração do lote #<?= $qrBuscaModulos['COD_CONTROLE'] ?>/<?= $qrBuscaModulos['COD_LOTE'] ?></small>&nbsp;<span class="f10"><?= $qrBuscaModulos['COD_DISPARO'] ?></span><br />
						<span style="font-size: 10px;">(<?= $qrBuscaModulos['NOM_TEMPLATE'] ?>)</span>
					</td>
					<td><span style="font-size: 12px;"><?= fnDataFull($qrBuscaModulos['DAT_ENVIO']) ?></span></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_LISTA'], 0) ?></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_SUCESSO'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_sucesso, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_LIDOS'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_lidos, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_CLIQUES'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_cliques, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_OPTOUT'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_optout, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_SOFT'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_soft, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_HARD'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_hard, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($qrBuscaModulos['QTD_SPAM'], 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_spam, 2) ?>%</span></small></td>
					<?php if ($qrBuscaModulos['COD_DISPARO'] != "" && $_SESSION['SYS_COD_EMPRESA'] == 2) { ?>
						<td class='text-center'><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick='reprocessaDisparo("<?= $cod_campanha ?>","<?= fnEncode($qrBuscaModulos[COD_DISPARO]) ?>", this)'><span class="fal fa-cogs"></span></a></td>
					<?php } else { ?>
						<td></td>
					<?php } ?>
				</tr>

<?php

			}
		} else {
			echo "<tr><td class='text-center' colspan='17'><h4>Sem resultados. Lista em processamento.</h4></td></tr>";
		}

		break;
}

?>