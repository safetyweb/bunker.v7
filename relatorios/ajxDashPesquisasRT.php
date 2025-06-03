<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$cod_pesquisa = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$andUnidades = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$tipo = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$med_ponderada = "";
$total_clientes = "";
$arrayQuery2 = [];
$total = "";
$qrBusca = "";
$i = "";
$pcRand = "";
$pct_detratores = "";
$pct_neutros = "";
$pct_promotores = "";
$nps = "";
$NOM_ARRAY_UNIDADE = "";
$unidade = "";
$arrayColumnsNames = [];
$objeto = "";
$categoria = "";
$contador = "";
$sql2 = "";
$qrPergunta = "";
$arrayPer = [];
$qrPer = "";
$respostas = "";
$respostaConcat = "";
$qrBusca2 = "";
$data = "";
$nome = "";
$email = "";
$celular = "";
$cpf = "";
$loja = "";
$resposta = "";
$finalizado = "";
$pergunta = "";
$valor = "";
$cod_registr = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

// echo fnDebug('true');

$opcao = fnLimpaCampo(@$_GET['opcao']);
$cod_pesquisa = fnLimpaCampoZero(@$_REQUEST['COD_PESQUISA']);
$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
$cod_univend = @$_REQUEST['COD_UNIVEND'];
$lojasSelecionadas = @$_REQUEST['LOJAS'];

if ($cod_univend == "9999" || $cod_univend['0'] == "9999") {
	$andUnidades = "";
} else {
	$andUnidades = "AND DP.COD_UNIVEND IN($lojasSelecionadas)";
}

switch ($opcao) {
	case 'exportar':

		$cod_empresa = fnLimpaCampo(fnDecode(@$_GET['id']));

		// fnEscreve($cod_empresa);		

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$tipo = fnLimpaCampo(@$_GET['tipo']);

		// fnEscreve($tipo);

		$ARRAY_UNIDADE1 = array(
			'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

		// fnEscreve($cod_pesquisa);

		if ($tipo == 'lojas') {

			$sql = "SELECT DPI.COD_UNIVEND, 

							(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 3
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_PROMOTORES,

							(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 2
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_NEUTROS,

							 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 1
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_DETRATORES

						FROM DADOS_PESQUISA_ITENS DPI
						INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
						AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
						WHERE DP.COD_REGISTRO = DPI.COD_REGISTRO
						AND DPI.COD_EMPRESA = $cod_empresa
						$andUnidades
						AND DPI.COD_PESQUISA = $cod_pesquisa
						GROUP BY DPI.COD_UNIVEND";

			// fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$array = array();
			while ($row = mysqli_fetch_assoc($arrayQuery)) {

				$newRow = array();

				$sql = "SELECT DPI.* FROM DADOS_PESQUISA_ITENS DPI
							INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
							WHERE DPI.COD_PERGUNTA IN (
														SELECT COD_REGISTR FROM MODELOPESQUISA 
														WHERE COD_TEMPLATE = $cod_pesquisa 
														AND COD_BLPESQU = 5 
														AND COD_EXCLUSA IS NULL
													)
							AND DP.COD_REGISTRO = DPI.COD_REGISTRO
							AND DP.COD_UNIVEND = $row[COD_UNIVEND]
							AND DPI.COD_EMPRESA = $cod_empresa
							AND DPI.COD_PESQUISA = $cod_pesquisa";

				// fnEscreve($sql);
				$med_ponderada = 0;
				$total_clientes = 0;
				$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$total = array();

				$cont = 0;
				while ($qrBusca = mysqli_fetch_assoc($arrayQuery2)) {
					if (@$qrBusca['resposta_numero'] == 0) {
						@$total['0']++;
					} else if (@$qrBusca['resposta_numero'] == 1) {
						@$total['1']++;
					} else if (@$qrBusca['resposta_numero'] == 2) {
						@$total['2']++;
					} else if (@$qrBusca['resposta_numero'] == 3) {
						@$total['3']++;
					} else if (@$qrBusca['resposta_numero'] == 4) {
						@$total['4']++;
					} else if (@$qrBusca['resposta_numero'] == 5) {
						@$total['5']++;
					} else if (@$qrBusca['resposta_numero'] == 6) {
						@$total['6']++;
					} else if (@$qrBusca['resposta_numero'] == 7) {
						@$total['7']++;
					} else if (@$qrBusca['resposta_numero'] == 8) {
						@$total['8']++;
					} else if (@$qrBusca['resposta_numero'] == 9) {
						@$total['9']++;
					} else if (@$qrBusca['resposta_numero'] == 10) {
						@$total['10']++;
					}
					$cont++;
				}

				for ($i = 0; $i <= 10; $i++) {
					$pcRand	= $total[$i];
					$med_ponderada += $pcRand * $i;
					$total_clientes += $pcRand;
				}

				$med_ponderada = $med_ponderada / $total_clientes;

				$pct_detratores = ($row['TOTAL_DETRATORES'] / $total_clientes) * 100;
				$pct_neutros = ($row['TOTAL_NEUTROS'] / $total_clientes) * 100;
				$pct_promotores = ($row['TOTAL_PROMOTORES'] / $total_clientes) * 100;

				$nps = $pct_promotores - $pct_detratores;

				// fnEscreve($med_ponderada);

				$NOM_ARRAY_UNIDADE = (array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
				$unidade = "Sem loja";

				if ($row['COD_UNIVEND'] != 0) {
					$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
				}

				array_push($newRow, $unidade);
				array_push($newRow, $row['TOTAL_PROMOTORES']);
				array_push($newRow, fnValor(($row['TOTAL_PROMOTORES'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, $row['TOTAL_NEUTROS']);
				array_push($newRow, fnValor(($row['TOTAL_NEUTROS'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, $row['TOTAL_DETRATORES']);
				array_push($newRow, fnValor(($row['TOTAL_DETRATORES'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, fnValor($med_ponderada, 2));
				array_push($newRow, $nps);

				$array[] = $newRow;
			}

			$arrayColumnsNames = array();
			array_push($arrayColumnsNames, "LOJA");
			array_push($arrayColumnsNames, "QTD. PROMOTORES");
			array_push($arrayColumnsNames, "% PROMOTORES");
			array_push($arrayColumnsNames, "QTD. NEUTROS");
			array_push($arrayColumnsNames, "% NEUTROS");
			array_push($arrayColumnsNames, "QTD. DETRATORES");
			array_push($arrayColumnsNames, "% DETRATORES");
			array_push($arrayColumnsNames, "MEDIA");
			array_push($arrayColumnsNames, "NPS");
		} else if ($tipo == 'clientes') {

			$sql = "SELECT CL.NOM_CLIENTE, 	
							   CL.NUM_CGCECPF, 	
							   DPI.COD_UNIVEND, 	
							   CL.NUM_CELULAR, 	
							   CL.DES_EMAILUS, 	
							   DPI.COD_NPSTIPO,
							   DPI.RESPOSTA_NUMERO,
							   DP.DT_HORAFINAL AS FINALIZADO
						FROM DADOS_PESQUISA_ITENS DPI
						INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = DP.COD_CLIENTE
						INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
						WHERE COD_NPSTIPO != 0
						$andUnidades
						AND DPI.COD_PESQUISA = $cod_pesquisa
						AND DPI.COD_EMPRESA = $cod_empresa
						ORDER BY NOM_CLIENTE
						";
			// fnescreve($sql);
			$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$newRow = [];

			$arrayColumnsNames = array();

			array_push($arrayColumnsNames, 'NOME');
			array_push($arrayColumnsNames, 'CPF');
			array_push($arrayColumnsNames, 'LOJA');
			array_push($arrayColumnsNames, 'CELULAR');
			array_push($arrayColumnsNames, 'EMAIL');
			array_push($arrayColumnsNames, 'CATEGORIA');
			array_push($arrayColumnsNames, 'NOTA');
			array_push($arrayColumnsNames, 'FINALIZADO');

			while ($row = mysqli_fetch_assoc($arrayQuery2)) {

				$newRow = array();

				$cont = 0;
				foreach ($row as $objeto) {

					// Colunas que são double converte com fnValor
					if ($cont == 2) {
						$NOM_ARRAY_UNIDADE = (array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
						$unidade = "Sem loja";

						if ($objeto != 0 && $objeto != '') {
							$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
						}
						array_push($newRow, $unidade);
					} else if ($cont == 5) {
						switch ($objeto) {

							case 1:
								$categoria = "Detrator";
								break;

							case 2:
								$categoria = "Neutro";
								break;

							default:
								$categoria = "Promotor";
								break;
						}
						array_push($newRow, $categoria);
					} else if ($cont == 7) {
						if ($objeto != '' && $objeto != 0) {
							$objeto = 'S';
						} else {
							$objeto = 'N';
						}
						array_push($newRow, $objeto);
					} else {
						array_push($newRow, $objeto);
					}

					$cont++;
				}

				$array[] = $newRow;
			}
		} else if ($tipo == 'geral') {

			$sql = "SELECT DISTINCT CL.NOM_CLIENTE, CL.NUM_CGCECPF, UV.NOM_FANTASI, DP.COD_CLIENTE FROM dados_pesquisa_itens DPI
						INNER JOIN clientes CL ON CL.COD_CLIENTE = DP.COD_CLIENTE
						INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = DPI.COD_UNIVEND
						WHERE DPI.COD_EMPRESA = $cod_empresa 
						AND DPI.COD_PESQUISA = $cod_pesquisa 
						AND DP.COD_CLIENTE != 0";
			// fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$arrayColumnsNames = array();
			$array = array();

			$contador = 0;
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {

				// array_push($arrayColumnsNames, $qrBusca['DES_PERGUNTA']);

				$sql2 = "SELECT MP.DES_PERGUNTA, GROUP_CONCAT(DPI.resposta_numero SEPARATOR ' || ') AS resposta_numero, GROUP_CONCAT(DPI.resposta_texto SEPARATOR ' || ') AS resposta_texto, DPI.cod_npstipo FROM dados_pesquisa_itens DPI
							INNER JOIN modelopesquisa MP ON MP.COD_REGISTR = DPI.COD_PERGUNTA
							WHERE DPI.COD_EMPRESA = $cod_empresa 
							AND DPI.COD_PESQUISA = $cod_pesquisa 
							AND DP.COD_CLIENTE = $qrBusca[COD_CLIENTE]
							GROUP BY COD_PERGUNTA
							ORDER BY COD_NPSTIPO DESC, MP.COD_REGISTR";

				// fnescreve($sql2);
				if ($contador == 0) {

					$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);


					array_push($arrayColumnsNames, "NOME");
					array_push($arrayColumnsNames, "CPF/CNPJ");
					array_push($arrayColumnsNames, "LOJA");

					while ($qrPergunta = mysqli_fetch_assoc($arrayQuery2)) {
						array_push($arrayColumnsNames, $qrPergunta['DES_PERGUNTA']);
					}
				}

				unset($newRow);

				$newRow = [];

				$arrayPer = mysqli_query(connTemp($cod_empresa, ''), $sql2);

				array_push($newRow, $qrBusca['NOM_CLIENTE']);
				array_push($newRow, $qrBusca['NUM_CGCECPF']);
				array_push($newRow, $qrBusca['NOM_FANTASI']);

				while ($qrPer = mysqli_fetch_assoc($arrayPer)) {


					if ($qrPer['cod_npstipo'] != 0) {

						array_push($newRow, $qrPer['resposta_numero']);
					} else {

						if (strpos($qrPer['resposta_texto'], '||') !== false) {

							if (strpos($qrPer['resposta_texto'], '{"') !== false) {

								$respostas = explode("||", $qrPer['resposta_texto']);
								$respostaConcat = "";

								// print_r($respostas);
								// echo count($respostas);

								for ($i = 0; $i < count($respostas); $i++) {

									print_r(json_decode($respostas[$i], true));

									$respostaConcat .= implode(", ", json_decode($respostas[$i], true)) . " || ";

									// fnEscreve($respostaConcat);

								}

								$respostaConcat = rtrim($respostaConcat, " || ");

								array_push($newRow, $respostaConcat);
							} else {

								array_push($newRow, $qrPer['resposta_texto']);
							}
						} else {

							if (strpos($qrPer['resposta_texto'], '{"') !== false) {

								array_push($newRow, implode(", ", json_decode($qrPer['resposta_texto'], true)));
							} else {

								array_push($newRow, $qrPer['resposta_texto']);
							}
						}
					}
				}

				$array[] = $newRow;

				$contador++;
			}
		} else if ($tipo == 'novosLojas') {

			$sql = "SELECT DPI.COD_UNIVEND, 

							(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 3
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_PROMOTORES,

							(SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 2
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_NEUTROS,

							 (SELECT COUNT(*) FROM DADOS_PESQUISA_ITENS
						     WHERE COD_PERGUNTA = (SELECT COD_REGISTR FROM MODELOPESQUISA 
						     					   WHERE COD_TEMPLATE = $cod_pesquisa 
						     					   AND LOG_PRINCIPAL = 'S'
												   AND COD_EXCLUSA IS NULL) 
							 AND COD_NPSTIPO = 1
							 	AND COD_UNIVEND = DPI.COD_UNIVEND
							 ) AS TOTAL_DETRATORES

						FROM DADOS_PESQUISA_ITENS DPI, CLIENTES CL
						INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
						WHERE DP.COD_REGISTRO = DPI.COD_REGISTRO
						AND DPI.COD_EMPRESA = $cod_empresa
						$andUnidades
						AND DPI.COD_PESQUISA = $cod_pesquisa
						AND DP.COD_CLIENTE = CL.COD_CLIENTE
						AND CL.LOG_CADTOTEM = 'S'
						AND LOG_PRINCIPAL = 'S'
						GROUP BY DPI.COD_UNIVEND";

			// fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$array = array();
			while ($row = mysqli_fetch_assoc($arrayQuery)) {

				$newRow = array();

				$sql = "SELECT DPI.* FROM DADOS_PESQUISA_ITENS DPI
							INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
							WHERE DPI.COD_PERGUNTA IN (
														SELECT COD_REGISTR FROM MODELOPESQUISA 
														WHERE COD_TEMPLATE = $cod_pesquisa 
														AND COD_BLPESQU = 5 
														AND COD_EXCLUSA IS NULL
													)
							AND DP.COD_REGISTRO = DPI.COD_REGISTRO
							AND DP.COD_UNIVEND = $row[COD_UNIVEND]
							AND DPI.COD_EMPRESA = $cod_empresa
							AND DPI.COD_PESQUISA = $cod_pesquisa";

				// fnEscreve($sql);
				$med_ponderada = 0;
				$total_clientes = 0;
				$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$total = array();

				$cont = 0;
				while ($qrBusca = mysqli_fetch_assoc($arrayQuery2)) {
					if ($qrBusca['resposta_numero'] == 0) {
						$total['0']++;
					} else if ($qrBusca['resposta_numero'] == 1) {
						$total['1']++;
					} else if ($qrBusca['resposta_numero'] == 2) {
						$total['2']++;
					} else if ($qrBusca['resposta_numero'] == 3) {
						$total['3']++;
					} else if ($qrBusca['resposta_numero'] == 4) {
						$total['4']++;
					} else if ($qrBusca['resposta_numero'] == 5) {
						$total['5']++;
					} else if ($qrBusca['resposta_numero'] == 6) {
						$total['6']++;
					} else if ($qrBusca['resposta_numero'] == 7) {
						$total['7']++;
					} else if ($qrBusca['resposta_numero'] == 8) {
						$total['8']++;
					} else if ($qrBusca['resposta_numero'] == 9) {
						$total['9']++;
					} else if ($qrBusca['resposta_numero'] == 10) {
						$total['10']++;
					}
					$cont++;
				}

				for ($i = 0; $i <= 10; $i++) {
					$pcRand	= $total[$i];
					$med_ponderada += $pcRand * $i;
					$total_clientes += $pcRand;
				}

				$med_ponderada = $med_ponderada / $total_clientes;

				$pct_detratores = ($row['TOTAL_DETRATORES'] / $total_clientes) * 100;
				$pct_neutros = ($row['TOTAL_NEUTROS'] / $total_clientes) * 100;
				$pct_promotores = ($row['TOTAL_PROMOTORES'] / $total_clientes) * 100;

				$nps = $pct_promotores - $pct_detratores;

				// fnEscreve($med_ponderada);

				$NOM_ARRAY_UNIDADE = (array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
				$unidade = "Sem loja";

				if ($row['COD_UNIVEND'] != 0) {
					$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
				}

				array_push($newRow, $unidade);
				array_push($newRow, $row['TOTAL_PROMOTORES']);
				array_push($newRow, fnValor(($row['TOTAL_PROMOTORES'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, $row['TOTAL_NEUTROS']);
				array_push($newRow, fnValor(($row['TOTAL_NEUTROS'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, $row['TOTAL_DETRATORES']);
				array_push($newRow, fnValor(($row['TOTAL_DETRATORES'] / $total_clientes) * 100, 2) . "%");
				array_push($newRow, fnValor($med_ponderada, 2));
				array_push($newRow, $nps);

				$array[] = $newRow;
			}

			$arrayColumnsNames = array();
			array_push($arrayColumnsNames, "LOJA");
			array_push($arrayColumnsNames, "QTD. PROMOTORES");
			array_push($arrayColumnsNames, "% PROMOTORES");
			array_push($arrayColumnsNames, "QTD. NEUTROS");
			array_push($arrayColumnsNames, "% NEUTROS");
			array_push($arrayColumnsNames, "QTD. DETRATORES");
			array_push($arrayColumnsNames, "% DETRATORES");
			array_push($arrayColumnsNames, "MEDIA");
			array_push($arrayColumnsNames, "NPS");
		} else if ($tipo == 'novosClientes') {

			$sql = "SELECT CL.NOM_CLIENTE, 	
							   CL.NUM_CGCECPF, 	
							   DPI.COD_UNIVEND, 	
							   CL.NUM_CELULAR, 	
							   CL.DES_EMAILUS, 	
							   DPI.COD_NPSTIPO,
							   DPI.RESPOSTA_NUMERO,
							   DP.DT_HORAFINAL AS FINALIZADO
						FROM DADOS_PESQUISA_ITENS DPI
						INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = DP.COD_CLIENTE
						INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
						WHERE AND COD_NPSTIPO != 0
						$andUnidades
						AND DPI.COD_EMPRESA = $cod_empresa
						AND DPI.COD_PESQUISA = $cod_pesquisa
						AND CL.LOG_CADTOTEM = 'S'
						AND LOG_PRINCIPAL = 'S'
						ORDER BY NOM_CLIENTE
						";
			// fnescreve($sql);
			$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$newRow = [];

			$arrayColumnsNames = array();

			array_push($arrayColumnsNames, 'NOME');
			array_push($arrayColumnsNames, 'CPF');
			array_push($arrayColumnsNames, 'LOJA');
			array_push($arrayColumnsNames, 'CELULAR');
			array_push($arrayColumnsNames, 'EMAIL');
			array_push($arrayColumnsNames, 'CATEGORIA');
			array_push($arrayColumnsNames, 'NOTA');
			array_push($arrayColumnsNames, 'FINALIZADO');

			while ($row = mysqli_fetch_assoc($arrayQuery2)) {

				$newRow = array();

				$cont = 0;
				foreach ($row as $objeto) {

					// Colunas que são double converte com fnValor
					if ($cont == 2) {
						$NOM_ARRAY_UNIDADE = (array_search($objeto, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
						$unidade = "Sem loja";

						if ($objeto != 0 && $objeto != '') {
							$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
						}
						array_push($newRow, $unidade);
					} else if ($cont == 5) {
						switch ($objeto) {

							case 1:
								$categoria = "Detrator";
								break;

							case 2:
								$categoria = "Neutro";
								break;

							default:
								$categoria = "Promotor";
								break;
						}
						array_push($newRow, $categoria);
					} else if ($cont == 7) {
						if ($objeto != '' && $objeto != 0) {
							$objeto = 'S';
						} else {
							$objeto = 'N';
						}
						array_push($newRow, $objeto);
					} else {
						array_push($newRow, $objeto);
					}

					$cont++;
				}

				$array[] = $newRow;
			}
		} else {


			$sql = "SELECT * FROM MODELOPESQUISA WHERE COD_TEMPLATE = $cod_pesquisa AND COD_BLPESQU = 2";
			// fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$arrayColumnsNames = array();
			$array = array();

			$contador = 0;
			while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {

				// array_push($arrayColumnsNames, $qrBusca['DES_PERGUNTA']);

				$sql = "SELECT DP.DT_HORAINICIAL, 
								   CL.NOM_CLIENTE, 
								   CL.NUM_CGCECPF,
								   CL.NUM_CELULAR, 	
							   	   CL.DES_EMAILUS, 
								   DPI.COD_UNIVEND, 
								   DPI.RESPOSTA_TEXTO,
								   DP.DT_HORAFINAL
							FROM DADOS_PESQUISA_ITENS DPI
							INNER JOIN  DADOS_PESQUISA DP ON DP.COD_REGISTRO = DPI.COD_REGISTRO 
							AND date(DP.DT_HORAINICIAL) BETWEEN '$dat_ini' AND '$dat_fim'
							LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = DP.COD_CLIENTE
							WHERE COD_PERGUNTA = $qrBusca[COD_REGISTR]
							$andUnidades
							AND DPI.COD_EMPRESA = $cod_empresa
							AND DPI.COD_PESQUISA = $cod_pesquisa
							ORDER BY DP.DT_HORAINICIAL DESC";
				// fnescreve($sql);
				$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$newRow = [];

				if (mysqli_num_rows($arrayQuery2) > 0) {
					$newRow = [];

					$newRow[] = array(
						"",
						"DATA" => "",
						"NOME" => "",
						"CPF" => "",
						"LOJA" => "",
						"RESPOSTA" => ""
					);
					$array[] = $newRow['0'];

					unset($newRow);

					$newRow[] = array(
						$qrBusca['DES_PERGUNTA'],
						"DATA",
						"NOME",
						"EMAIL",
						"CELULAR",
						"CPF",
						"LOJA",
						"RESPOSTA",
						"FINALIZADO"
					);
					$array[] = $newRow['0'];
				}

				$count = 0;


				while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery2)) {

					$data = fnDataFull($qrBusca2['DT_HORAINICIAL']);
					$nome = $qrBusca2['NOM_CLIENTE'];
					$email = $qrBusca2['DES_EMAILUS'];
					$celular = $qrBusca2['NUM_CELULAR'];
					$cpf = $qrBusca2['NUM_CGCECPF'];
					$loja = $qrBusca2['COD_UNIVEND'];

					if (strpos($qrBusca2['RESPOSTA_TEXTO'], '{"') !== false) {

						$resposta =  implode(", ", json_decode($qrBusca2['RESPOSTA_TEXTO'], true));
					} else {

						$resposta = $qrBusca2['RESPOSTA_TEXTO'];
					}

					if ($qrBusca2['DT_HORAINICIAL'] != "") {
						$finalizado = 'S';
					} else {
						$finalizado = 'N';
					}

					$pergunta = $qrBusca['DES_PERGUNTA'];

					$NOM_ARRAY_UNIDADE = (array_search($loja, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
					$unidade = "Sem loja";

					if ($loja != 0 && $loja != '') {
						$unidade = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
					}

					$newRow = [];

					$newRow[$pergunta][] = array(
						" ",
						"DATA" => $data,
						"NOME" => $nome,
						"EMAIL" => $email,
						"CELULAR" => $celular,
						"CPF" => $cpf,
						"LOJA" => $unidade,
						"RESPOSTA" => $resposta,
						"FINALIZADO" => $finalizado
					);

					$array[] = $newRow[$pergunta][0];

					$count++;
				}




				$contador++;
			}
		}

		// print_r($array);

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	default:

		$valor = fnLimpacampoZero(@$_GET['valor']);
		$cod_empresa = fnLimpacampoZero(@$_GET['cod_empresa']);
		$cod_registr = fnLimpacampoZero(@$_GET['cod_registr']);
		$dat_ini = @$_GET['datIni'];
		$dat_fim = @$_GET['datFim'];

		$sql = "SELECT DPI.*, DP.DT_HORAINICIAL 
					FROM DADOS_PESQUISA_ITENS DPI, DADOS_PESQUISA DP
					WHERE COD_PERGUNTA = $cod_registr
					AND DPI.COD_REGISTRO = DP.COD_REGISTRO
					AND DP.DT_HORAINICIAL BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					ORDER BY DP.DT_HORAINICIAL DESC 
					LIMIT $valor, 5";
		// FNESCREVE($sql);
		$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql);

		while ($qrBusca2 = mysqli_fetch_assoc($arrayQuery2)) {
?>
			<p style="text-align: left;"><?php echo $qrBusca2['resposta_texto']; ?></p>
			<div class="push5"></div>
			<p style="text-align: left; font-size: 14px;"><small><?php echo fnDataFull($qrBusca2['DT_HORAINICIAL']); ?></small></p>
			<hr>
<?php
		}

		break;
}



?>