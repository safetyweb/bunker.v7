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
		                    '0' AS QTD_NRECEBIDO,
		                    CP.COD_CAMPANHA,
		                    SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS,
		                    SUM(CEM.QTD_SUCESSO) + SUM(CEM.QTD_NRECEBIDO) AS QTD_SUCESSO,
		                    SUM(CEM.QTD_FALHA) AS QTD_FALHA,
		                    SUM(CEM.QTD_LIDOS) AS QTD_LIDOS,
		                    SUM(CEM.QTD_NLIDOS) AS QTD_NLIDOS,
		                    SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT,
		                    SUM(CEM.QTD_CLIQUES) AS QTD_CLIQUES,
		                    SUM(CEM.CANCELADO) AS CANCELADO,
		                    -- CEM.DAT_ENVIO,
		                    TE.NOM_TEMPLATE,
		                    SUM(EL.QTD_LISTA) AS QTD_LISTA,
		                    EL.DES_PATHARQ,
		                    EL.COD_GERACAO,
		                    EL.COD_CONTROLE,
		                    EL.COD_LOTE,
		                    CP.DES_CAMPANHA,
		                    MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO
						FROM SMS_LOTE EL
							LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO 
					                                            AND CEM.cod_empresa=EL.COD_EMPRESA 
					                                            AND CEM.cod_campanha=EL.COD_CAMPANHA
					                                            AND CEM.LOG_TESTE=EL.LOG_TESTE
							LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
							LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
						WHERE EL.COD_EMPRESA = $cod_empresa 
							AND EL.LOG_ENVIO = 'S'
							$andCampanha
							$andData
						GROUP BY  CEM.LOG_TESTE, CEM.LOG_CAMPANHA
						ORDER BY EL.COD_CONTROLE DESC
						";

			// fnEscreve($sql);

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
							CEM.CANCELADO,
							CEM.DAT_ENVIO,
							TE.NOM_TEMPLATE,
							EL.QTD_LISTA,
							EL.DES_PATHARQ,
							EL.COD_GERACAO,
							EL.COD_CONTROLE,
							EL.COD_LOTE,
							EL.LOG_TESTE
						FROM SMS_LOTE EL
						LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO 
				                                            AND CEM.cod_empresa=EL.COD_EMPRESA 
				                                            AND CEM.cod_campanha=EL.COD_CAMPANHA
				                                            AND CEM.LOG_TESTE=EL.LOG_TESTE
						LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
						WHERE EL.COD_EMPRESA = $cod_empresa
						AND EL.COD_CAMPANHA = $cod_campanha
						AND EL.LOG_ENVIO = 'S'
						GROUP BY  CEM.LOG_TESTE, CEM.LOG_CAMPANHA
						$andData
						ORDER BY EL.COD_CONTROLE DESC";

			// fnEscreve($sql);

		}

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$pct_sucesso = ($row['QTD_SUCESSO'] / $row['QTD_DISPARADOS']) * 100;
			$pct_falha = ($row['QTD_FALHA'] / $row['QTD_DISPARADOS']) * 100;
			$pct_lidos = ($row['QTD_LIDOS'] / $row['QTD_DISPARADOS']) * 100;
			$pct_nlidos = ($row['QTD_NLIDOS'] / $row['QTD_DISPARADOS']) * 100;
			$pct_cancelado = ($row['CANCELADO'] / $row['QTD_DISPARADOS']) * 100;

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
			array_push($newRow, fnValor($row['CANCELADO'], 0));
			array_push($newRow, fnValor($pct_cancelado, 0));

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
		array_push($arrayColumnsNames, "Cancelados ");
		array_push($arrayColumnsNames, "% Cancelados ");

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	default:

		$andData = fnDecode($_REQUEST['DATA']);

		$sql = "SELECT
						EL.COD_DISPARO_EXT,
						CEM.QTD_AGUARADANDO AS QTD_AGUARDANDO,
						CEM.QTD_EXCLUSAO,
						CEM.COD_DISPARO,
						CASE 
							WHEN CEM.QTD_SUCESSO > 0 THEN CEM.QTD_SUCESSO 
							ELSE '0'
						END  + CEM.QTD_NRECEBIDO AS QTD_SUCESSO,
						'0' AS QTD_NRECEBIDO,
						CEM.QTD_OPTOUT AS QTD_OPTOUT,
						CEM.QTD_FALHA AS QTD_FALHA,
						CEM.QTD_AGUARADANDO AS QTD_AGUARDANDO,
						CEM.CANCELADO AS CANCELADO,
						TE.NOM_TEMPLATE,
						EL.QTD_LISTA,
						EL.DES_PATHARQ,
						EL.COD_GERACAO,
						EL.COD_CONTROLE,
						EL.COD_LOTE,
						EL.LOG_TESTE,
						EL.DAT_CADASTR,
						EL.DAT_AGENDAMENTO AS DAT_ENVIO
					FROM SMS_LOTE EL
					LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO 
			                                            AND CEM.cod_empresa=EL.COD_EMPRESA 
			                                            AND CEM.cod_campanha=EL.COD_CAMPANHA
			                                            AND CEM.LOG_TESTE=EL.LOG_TESTE
					LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
					WHERE EL.COD_EMPRESA = $cod_empresa
					AND EL.COD_CAMPANHA = $cod_campanha
					AND EL.LOG_ENVIO = 'S'
					$andData
					ORDER BY EL.LOG_TESTE DESC, EL.COD_CONTROLE DESC";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		if (mysqli_num_rows($arrayQuery) > 0) {

			$count = 0;
			while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
				$count++;

				if ($qrBuscaModulos['DES_PATHARQ'] != '') {
					$urlAnexo = '<a href="' . $qrBuscaModulos['DES_PATHARQ'] . '" download><span class="fa fa-download"></span></a>';
				} else {
					$urlAnexo = '';
				}

				$dat_envio = fnDataFull($qrBuscaModulos['DAT_ENVIO']);

				if ($qrBuscaModulos['COD_GERACAO'] != '') {
					$pref = $qrBuscaModulos['COD_GERACAO'];
				} else {
					if ($qrBuscaModulos['LOG_TESTE'] != 'S') {
						$pref = 'ENVIO';
						// $data_envio = fnDataFull($qrBuscaModulos['DAT_AGENDAMENTO']);
					} else {
						$pref = 'TESTE';
						$dat_envio = fnDataFull($qrBuscaModulos['DAT_CADASTR']);
					}
				}

				$contatos_graph = $qrBuscaModulos['QTD_LISTA'];
				$sucesso_graph = $qrBuscaModulos['QTD_SUCESSO'];
				$nrecebidos_graph = $qrBuscaModulos['QTD_NRECEBIDO'];
				$optout_graph = $qrBuscaModulos['QTD_OPTOUT'];
				$falha_graph = $qrBuscaModulos['QTD_FALHA'];
				$cancelado = $qrBuscaModulos['CANCELADO'];
				$aguardo_graph = $qrBuscaModulos['QTD_AGUARDANDO'];

				$perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
				$perc_nrecebidos = fnValorSql(fnValor(($nrecebidos_graph / $contatos_graph) * 100, 2));
				$perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
				$perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
				$perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));
				$perc_cancelado = fnValorSql(fnValor(($cancelado / $contatos_graph) * 100, 2));

				// fnEscreve($qrBuscaModulos['COD_DISPARO']);

?>

				<tr>
					<!-- <td class="text-center"><small><?= $urlAnexo ?></small></td> -->
					<td></td>
					<td><small><small><?= $pref ?></small>&nbsp;Geração do lote #<?= $qrBuscaModulos['COD_CONTROLE'] ?>/<?= $qrBuscaModulos['COD_LOTE'] ?></small>&nbsp;<span class="f10"><?= $qrBuscaModulos['COD_DISPARO_EXT'] ?></span></td>
					<td><small><?= $dat_envio ?></small></td>
					<td class='text-right'><small><?= fnValor($contatos_graph, 0) ?>
					<td class='text-right'><small><?= fnValor($sucesso_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_sucesso, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($nrecebidos_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_nrecebidos, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($optout_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_optout, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($falha_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_falha, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($aguardo_graph, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_aguardo, 2) ?>%</span></small></td>
					<td class='text-right'><small><?= fnValor($cancelado, 0) ?><br /><span class="text-muted" style="font-size: 10px;"><?= fnValor($perc_cancelado, 2) ?>%</span></small></td>
					<?php if ($qrBuscaModulos['COD_DISPARO'] != "" && $_SESSION['SYS_COD_EMPRESA'] == 2) { ?>
						<td class='text-center'><a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick='reprocessaDisparo("<?= fnEncode($cod_campanha) ?>","<?= fnEncode($qrBuscaModulos['COD_DISPARO']) ?>", this)'><span class="fal fa-cogs"></span></a></td>
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