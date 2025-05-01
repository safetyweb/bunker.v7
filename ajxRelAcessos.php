<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$log_unifica = "";
$groupUnifica = "";
$andEmpresa = "";
$nomeRel = "";
$arquivoCaminho = "";
$selectUnifica = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$retorno = "";
$inicio = "";
$qrAcesso = "";
$status = "";


include '_system/_functionsMain.php';



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = fnLimpaCampo(@$_POST['LOJAS']);
if (empty(@$_REQUEST['LOG_UNIFICA'])) {
	$log_unifica = 'N';
} else {
	$log_unifica = @$_REQUEST['LOG_UNIFICA'];
}

//fnEscreve($dat_ini);

if ($log_unifica == "S") {
	$groupUnifica = "GROUP BY LA.COD_USUARIO";
} else {
	$groupUnifica = "";
}

if ($cod_empresa != '' && $cod_empresa != 0) {
	$andEmpresa = "AND LA.COD_EMPRESA = $cod_empresa ";
} else {
	$andEmpresa = "";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		if ($log_unifica == "S") {
			$selectUnifica = "COUNT(LA.ID_CESSO) QTD_ACESSOS, 
						ifnull(TIMESTAMPDIFF(MINUTE ,DATA_ACESSO,DATA_LOGOFF),0) TEMPO_CONECTADO, ";
		}

		$sql = "SELECT UV.NOM_FANTASI AS LOJA, 
						   LA.NOM_USUARIO AS USUARIO,
						   TU.DES_TPUSUARIO AS TIP_USUARIO,
						   $selectUnifica
						   LA.DATA_ACESSO,
						   LA.IP_ACESSO,
						   LA.PORTA_ACESSO
					FROM LOG_ACESSO LA
					INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
					INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
					LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
					LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
					WHERE
					LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andEmpresa
					AND US.COD_UNIVEND IN($lojasSelecionadas)
					$groupUnifica
					ORDER BY LA.DATA_ACESSO DESC";

		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

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

			//echo "<pre>";
			//print_r($row);
			//echo "<pre>";
		}
		fclose($arquivo);

		break;

	default:

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT LA.ID_CESSO FROM LOG_ACESSO LA
					INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
					INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
					LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
					LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
					WHERE
					LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andEmpresa
					AND US.COD_UNIVEND IN($lojasSelecionadas)
					$groupUnifica
				";

		//fnEscreve($sql);

		$retorno = mysqli_query($connAdm->connAdm(), $sql);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT LA.*, ";

		if ($log_unifica == "S") {
			$sql .= "COUNT(LA.ID_CESSO) QTD_ACESSOS, 
							ifnull(TIMESTAMPDIFF(MINUTE ,DATA_ACESSO,DATA_LOGOFF),0) TEMPO_CONECTADO, ";
		}

		$sql .= "TU.DES_TPUSUARIO, EM.NOM_FANTASI, UV.NOM_FANTASI AS UNIDADE FROM LOG_ACESSO LA
						INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
						INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
						LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND	
						LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
						WHERE
						LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
						$andEmpresa
						AND US.COD_UNIVEND IN($lojasSelecionadas)
						$groupUnifica
						ORDER BY LA.DATA_ACESSO DESC
						LIMIT $inicio,$itens_por_pagina";

		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);										

		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		$count = 0;

		while ($qrAcesso = mysqli_fetch_assoc($arrayQuery)) {

			if ($qrAcesso['COD_ALTERACAO'] == 1) {
				$status = "<span class='fas fa-circle text-success'></span>";
			} else {
				$status = "<span class='fas fa-circle text-danger'></span>";
			}

			$count++;

?>
			<tr>
				<td><small><?= $qrAcesso['UNIDADE'] ?></small></td>
				<td><small><?= $qrAcesso['NOM_USUARIO'] ?></small></td>
				<td><small><?= $qrAcesso['QTD_ACESSOS'] ?></small></td>
				<td><small><?= $qrAcesso['DES_TPUSUARIO'] ?></small></td>
				<td class="text-center"><small><?= fnDataFull($qrAcesso['DATA_ACESSO']) ?></small></td>
				<td><small><?= $qrAcesso['TEMPO_CONECTADO'] ?></small></td>
				<td><small><?= $qrAcesso['IP_ACESSO'] ?></small></td>
				<td><small><?= $qrAcesso['PORTA_ACESSO'] ?></small></td>
				<!-- <td><small><?= $qrAcesso['DATA_LOGOFF'] ?></small></td> -->
				<td class="text-center"><small><?= $status ?></small></td>
			</tr>


<?php
		}

		break;
}

?>