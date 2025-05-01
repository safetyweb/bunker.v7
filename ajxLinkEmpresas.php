<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];

switch ($opcao) {
	case 'filtra':

		$corLojaAtv = '';

		if (empty($_REQUEST['LOG_TODAS'])) {
			$log_todas = 'N';
		} else {
			$log_todas = $_REQUEST['LOG_TODAS'];
		}
		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

		if ($log_todas == 'S') {
			$andAtivo = "";
		} else {
			$andAtivo = "AND LOG_ATIVO = 'S'";
		}

		// filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
		if ($filtro != "") {
			$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
		} else {
			$andFiltro = " ";
		}
		// --------------------------------------------------------------------------------------------------------------------------------------------------------

		if ($_SESSION["SYS_COD_MASTER"] == "2") {
			$sql = "SELECT STATUSSISTEMA.DES_STATUS, empresas.*,
			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE,
			(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
			(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
			(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
			(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
			B.COD_DATABASE, 
			B.NOM_DATABASE 
			FROM empresas  
			LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
			LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
			WHERE empresas.COD_EMPRESA <> 1 
			$andFiltro
			$andAtivo
			ORDER by NOM_FANTASI
	";
			//fnEscreve("1");
		} else {
			$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.*,
			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE,
			(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
			(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
			(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
			(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
			B.COD_DATABASE, 
			B.NOM_DATABASE 
			FROM empresas  
			LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
			LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
			WHERE empresas.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
			$andFiltro
			$andAtivo
			ORDER by NOM_FANTASI
	";
			//fnEscreve("2");
			//fnEscreve($_SESSION["SYS_COD_MULTEMP"]);
		}

		// fnEscreve($sql);

		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		$count = 0;
		while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
			$count++;
			//  if ($qrListaEmpresas['LOG_ESTATUS'] == 'S'){		
			// 	$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
			// }else{ $mostraAtivo = ''; }

			if ($qrListaEmpresas['LOG_INTEGRADORA'] == 'S') {
				$mostraSH = '<i class="fas fa-check" aria-hidden="true"></i>';
			} else {
				$mostraSH = '';
			}

			if ($qrListaEmpresas['COD_DATABASE'] > 0) {
				if ($qrListaEmpresas['NOM_DATABASE'] == "db_host1" || $qrListaEmpresas['NOM_DATABASE'] == "db_host2") {
					$mostraAtivoBD = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
					$mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";
				} else {
					$mostraAtivoBD = '<i class="fa fa-check" aria-hidden="true"></i>';
					$mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";
				}
			} else {
				$mostraAtivoBD = '';
				$mostraEmpresa = $qrListaEmpresas['NOM_FANTASI'];
			}

			if ($qrListaEmpresas['COD_DATABASE'] > 0) {
				$mostraBD = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
			} else {
				$mostraBD = '';
			}

			if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {
				$tem_sistema = "tem";
			} else {
				$tem_sistema = "nao";
			}

			if ($qrListaEmpresas['LOG_ATIVO'] == 'S') {
				$mostraAtivo = '<i class="fas fa-check" aria-hidden="true"></i>';
				$radioAcesso = "<input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>";
			} else {
				$mostraAtivo = '';
				$radioAcesso = "";
				if ($_SESSION["SYS_COD_MASTER"] == "2") {
					$radioAcesso = "<a href='action.do?mod=" . fnEncode(1729) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'><span class='fal fa-external-link' style='font-size: 11px'></span></a>";
				}
			}
			echo "
			<tr>
			  <td class='text-center'>$radioAcesso</th>
			  <td class='text-center'>" . $qrListaEmpresas['COD_EMPRESA'] . "</td>
			  <td>" . $mostraEmpresa . "</td>
			  <td>" . $qrListaEmpresas['NOM_RESPONS'] . "</td>
			  <td style='display:none;'>" . $qrListaEmpresas['NUM_TELEFON'] . " / " . $qrListaEmpresas['NUM_CELULAR'] . "</td>
			  <td>" . $qrListaEmpresas['NOM_CONSULTOR'] . "</td>
			  <td>" . $qrListaEmpresas['NOM_INTEGRADORA'] . "</td>
			  <td>" . fnValor($qrListaEmpresas['PCT_PARCEIRO'], 2) . "</td>
			  <td align='center'>" . $qrListaEmpresas['LOJAS'] . "</td>
			  <td align='center'><span class='" . $corLojaAtv . "'>" . $qrListaEmpresas['LOJAS_ATIVAS'] . "</td>
			  <td align='center'>" . $mostraSH . "</td>
			  <td align='center'>" . $mostraAtivo . "</td>
			  <td align='center'>" . $mostraAtivoBD . "</td>
			  <td align='center'>" . $qrListaEmpresas['DES_STATUS'] . "</td>
			  <!-- <td align='center'>" . $mostraBD . "</td> -->
			  <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO']) . "</small></td>
			</tr>
			<input type='hidden' id='ret_IDC_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>
			<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>
			<input type='hidden' id='ret_NOM_EMPRESA_" . $count . "' value='" . $qrListaEmpresas['NOM_EMPRESA'] . "'>
			";
		}
		break;

	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/7_' . $nomeRel . '.csv';

		$arraydados = array();

		$cliente = "SELECT E.COD_EMPRESA, E.NOM_FANTASI,E.NUM_CGCECPF as CNPJ, E.DAT_CADASTR, E.DAT_PRODUCAO,us.NOM_USUARIO as CONSULTOR, E.LOG_ATIVO
						FROM empresas E
						INNER JOIN tab_database TB ON TB.COD_EMPRESA=E.COD_EMPRESA
						left JOIN usuarios us ON us.COD_USUARIO= e.COD_CONSULTOR
						WHERE E.COD_MASTER NOT IN (2)
						AND E.COD_SEGMENT NOT IN (3, 20) 
						AND E.LOG_ATIVO = 'S'
						AND E.LOG_INTEGRADORA = 'N'
						ORDER BY E.NOM_FANTASI";
		$rs = mysqli_query($connAdm->connAdm(), $cliente);

		while ($row = mysqli_fetch_assoc($rs)) {
			$codEmpresa = $row['COD_EMPRESA'];

			// Inicializa o array para o COD_EMPRESA atual
			$arraydados[$codEmpresa] = $row;

			// Consulta as vendas para o COD_EMPRESA atual
			$vendas = "SELECT 
						(SELECT COD_VENDA FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA ASC LIMIT 1) AS primeira_venda,
						(SELECT DATE_FORMAT(DAT_CADASTR_WS, '%d/%m/%Y %H:%i:%s') FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA ASC LIMIT 1) AS data_primeira_venda,
						(SELECT FORMAT(VAL_TOTVENDA, 2, 'pt_BR') FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA ASC LIMIT 1) AS val_primeira_venda,
						
						(SELECT COD_VENDA FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA DESC LIMIT 1) AS ultima_venda,
						(SELECT DATE_FORMAT(DAT_CADASTR_WS, '%d/%m/%Y %H:%i:%s') FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA DESC LIMIT 1) AS data_ultima_venda,
						(SELECT FORMAT(VAL_TOTVENDA, 2, 'pt_BR') FROM vendas WHERE cod_empresa = $codEmpresa ORDER BY COD_VENDA DESC LIMIT 1) AS val_ultima_venda
					FROM vendas
					LIMIT 1
					";
			$rs1 = mysqli_query(connTemp($codEmpresa, ''), $vendas);

			// Se houver resultados, mescla com o array existente
			if ($row1 = mysqli_fetch_assoc($rs1)) {
				$arraydados[$codEmpresa] = array_merge($arraydados[$codEmpresa], $row1);
			}
		}

		// Nome do arquivo CSV
		// $filename = 'export.csv';

		// Abre o arquivo para escrita
		$file = fopen($arquivoCaminho, 'w');

		// Define o cabeçalho do CSV
		$header = array('COD_EMPRESA', 'NOM_FANTASI', 'CNPJ', 'DAT_CADASTR', 'DAT_PRODUCAO', 'CONSULTOR', 'ATIVA', 'COD_PRIMEIRA_VENDA', 'DAT_PRIMEIRA_CADASTR_WS', 'VAL_PRIMEIRA_TOTVENDA', 'COD_ULTIMA_VENDA', 'DAT_ULTIMA_CADASTR_WS', 'VAL_ULTIMA_TOTVENDA');
		fputcsv($file, $header, ';');

		// Escreve os dados no arquivo CSV
		foreach ($arraydados as $row) {
			fputcsv($file, $row, ';');
		}

		// Fecha o arquivo
		fclose($file);
		break;
}
