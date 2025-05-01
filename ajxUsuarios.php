<?php

include '_system/_functionsMain.php';

//echo fnDebug('true');

//fnMostraForm();

$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

if (empty($_REQUEST['LOG_INATIVOS'])) {
	$log_inativos = 'N';
} else {
	$log_inativos = $_REQUEST['LOG_INATIVOS'];
}

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$tipoUsuario = $_GET['tpUsu'];
$des_sufixo = $_GET['des_sufixo'];
$andFiltro = $_REQUEST['AND_FILTRO'];
$andInativos = $_REQUEST['AND_INATIVOS'];

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}


switch ($opcao) {
	case 'exportar':

		$nomeRel = $_GET['nomeRel'];
		$arquivoCaminho = './media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "SELECT 
						USUARIOS.COD_USUARIO AS CODIGO,
						USUARIOS.COD_EXTERNO AS COD_EXTERNO,
						UV.NOM_FANTASI AS UNIDADE,
						USUARIOS.NOM_USUARIO AS NOME_USUARIO,
						USUARIOS.LOG_USUARIO AS LOGIN,
						USUARIOS.DES_EMAILUS AS EMAIL,
						TIPOUSUARIO.DES_TPUSUARIO AS TIPO_USUARIO,
						USUARIOS.LOG_ESTATUS AS ATIVO
						FROM USUARIOS 
						LEFT JOIN TIPOUSUARIO ON USUARIOS.COD_TPUSUARIO = TIPOUSUARIO.COD_TPUSUARIO
						LEFT JOIN USUARIOS_AGENDA ON USUARIOS_AGENDA.COD_USUARIO = USUARIOS.COD_USUARIO
						LEFT JOIN UNIDADEVENDA UV ON USUARIOS.COD_UNIVEND = UV.COD_UNIVEND
						WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
						AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
						$andInativos 
						$andFiltro 
						ORDER BY USUARIOS.NOM_USUARIO 
						";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			// $row[VAL_TOTPRODU]= fnValor($row['VAL_TOTPRODU'],2);
			// $row[VAL_TOTVENDA]= fnValor($row['VAL_TOTVENDA'],2);

			//$limpandostring= fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo=json_decode($limpandostring,true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"', '\n');
		}
		fclose($arquivo);

		break;
	case 'paginar':

		$sql = "
						SELECT 
							count(*) as CONTADOR
						FROM
							usuarios
						WHERE
								usuarios.COD_EMPRESA = $cod_empresa 
								AND usuarios.COD_TPUSUARIO IN ($tipoUsuario)
								$andInativos
								$andFiltro
						ORDER BY usuarios.NOM_USUARIO";

		//fnEscreve($sql);
		$retorno = mysqli_query($connAdm->connAdm(), $sql);
		$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT USUARIOS.*, UV.NOM_FANTASI, TIPOUSUARIO.*, USUARIOS_AGENDA.COD_USUARIOS_AGE,
							(SELECT GROUP_CONCAT(COD_TURNO) FROM usuarios_turno WHERE usuarios_turno.COD_USUARIO=USUARIOS.COD_USUARIO) COD_TURNO
					FROM USUARIOS 
					LEFT JOIN TIPOUSUARIO ON USUARIOS.COD_TPUSUARIO = TIPOUSUARIO.COD_TPUSUARIO
					LEFT JOIN USUARIOS_AGENDA ON USUARIOS_AGENDA.COD_USUARIO = USUARIOS.COD_USUARIO
					LEFT JOIN UNIDADEVENDA UV ON USUARIOS.COD_UNIVEND = UV.COD_UNIVEND
					WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
					AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
					$andInativos 
					$andFiltro 
					ORDER BY USUARIOS.NOM_USUARIO LIMIT $inicio,$itens_por_pagina";

		//fnEscreve($sql);
		//--and log_usuario like '%arcio.fabian.mcoisas%'

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		$count = 0;

		while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {

			$count++;

			if ($qrListaUsuario['LOG_ESTATUS'] == 'S') {
				$mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraAtivo = '';
			}

			if (!empty($qrListaUsuario['COD_PERFILS'])) {
				$tem_perfil = "sim";
			} else {
				$tem_perfil = "nao";
			}

			if (!empty($qrListaUsuario['COD_UNIVEND'])) {
				$tem_unive = "sim";
			} else {
				$tem_unive = "nao";
			}

			if (!empty($qrListaUsuario['COD_MULTEMP'])) {
				$tem_master = "sim";
			} else {
				$tem_master = "nao";
			}

			if (!empty($qrListaUsuario['COD_USUARIOS_AGE'])) {
				$tem_usuarios_age = "sim";
			} else {
				$tem_usuarios_age = "nao";
			}

			$loginLimpo =  str_replace('.' . $des_sufixo, '', $qrListaUsuario['LOG_USUARIO']);
			if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
				// fnConsole($qrListaUsuario['NOM_USUARIO'] . " " . fnDecode($qrListaUsuario['DES_SENHAUS']));
			}

			if ($qrListaUsuario['COD_INDICADOR'] != '') {
				$sqlCli = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $qrListaUsuario[COD_INDICADOR]";
				$qrIndicad = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCli));
				$nom_indicad = $qrIndicad['NOM_CLIENTE'];
			} else {
				$nom_indicad = "";
			}

			$sqlPerfil = "SELECT DES_PERFILS FROM PERFIL WHERE COD_PERFILS IN ($qrListaUsuario[COD_PERFILS])";
			$arrPerfil = mysqli_query($connAdm->connAdm(), $sqlPerfil);
			$perfis = "";
			while ($qrPerfil = mysqli_fetch_assoc($arrPerfil)) {
				$perfis .= $qrPerfil['DES_PERFILS'] . ", ";
			}

			$perfis = rtrim(trim($perfis), ',');

			echo "
					<tr>
					  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
					  <td>" . $qrListaUsuario['COD_USUARIO'] . "</td>
					  <td>" . $qrListaUsuario['COD_EXTERNO'] . "</td>
					  <td>" . $qrListaUsuario['NOM_FANTASI'] . "</td>
					  <td>
						<a href='#' class='editable' 
							data-type='text' 
							data-title='Editar Nome de Usuário'
							data-pk='COD_USUARIO' 
							data-name='NOM_USUARIO'
							data-id='" . $qrListaUsuario['COD_USUARIO'] . "'
							data-codempresa='" . $cod_empresa . "' >" . $qrListaUsuario['NOM_USUARIO'] . "
						</a>
					  </td>
					  <td>" . $qrListaUsuario['LOG_USUARIO'] . "</td>
					  <td>" . $qrListaUsuario['DES_EMAILUS'] . "</td>
					  <td>" . $qrListaUsuario['DES_TPUSUARIO'] . " / " . $qrListaUsuario['NUM_CELULAR'] . "</td>
					  <td><small>" . $perfis . "</small></td>
					  <td align='center'>" . $mostraAtivo . "</td>
					</tr>
					<input type='hidden' id='ret_COD_USUARIO_" . $count . "' value='" . $qrListaUsuario['COD_USUARIO'] . "'>
					<input type='hidden' id='ret_COD_USUARIO_ENC_" . $count . "' value='" . fnEncode($qrListaUsuario['COD_USUARIO']) . "'>
					<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrListaUsuario['COD_EMPRESA'] . "'>
					<input type='hidden' id='ret_DAT_CADASTR_" . $count . "' value='" . fnFormatDateTime($qrListaUsuario['DAT_CADASTR']) . "'>
					<input type='hidden' id='ret_NOM_USUARIO_" . $count . "' value='" . $qrListaUsuario['NOM_USUARIO'] . "'>
					<input type='hidden' id='ret_LOG_USUARIO_" . $count . "' value='" . $loginLimpo . "'>
					<input type='hidden' id='ret_LOG_ESTATUS_" . $count . "' value='" . $qrListaUsuario['LOG_ESTATUS'] . "'>
					<input type='hidden' id='ret_LOG_USUDEV_" . $count . "' value='" . $qrListaUsuario['LOG_USUDEV'] . "'>
					<input type='hidden' id='ret_DES_EMAILUS_" . $count . "' value='" . $qrListaUsuario['DES_EMAILUS'] . "'>
					<input type='hidden' id='ret_COD_PERFILCOM_" . $count . "' value='" . $qrListaUsuario['COD_PERFILCOM'] . "'>
					<input type='hidden' id='ret_HOR_DEVDIAS_" . $count . "' value='" . $qrListaUsuario['HOR_DEVDIAS'] . "'>
					<input type='hidden' id='ret_HOR_DEVFDS_" . $count . "' value='" . $qrListaUsuario['HOR_DEVFDS'] . "'>
					<input type='hidden' id='ret_HOR_ENTRADA_" . $count . "' value='" . $qrListaUsuario['HOR_ENTRADA'] . "'>
					<input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrListaUsuario['NUM_CGCECPF'] . "'>
					<input type='hidden' id='ret_NUM_RGPESSO_" . $count . "' value='" . $qrListaUsuario['NUM_RGPESSO'] . "'>
					<input type='hidden' id='ret_DAT_NASCIME_" . $count . "' value='" . $qrListaUsuario['DAT_NASCIME'] . "'>
					<input type='hidden' id='ret_COD_ESTACIV_" . $count . "' value='" . $qrListaUsuario['COD_ESTACIV'] . "'>
					<input type='hidden' id='ret_COD_SEXOPES_" . $count . "' value='" . $qrListaUsuario['COD_SEXOPES'] . "'>
					<input type='hidden' id='ret_NUM_TENTATI_" . $count . "' value='" . $qrListaUsuario['NUM_TENTATI'] . "'>
					<input type='hidden' id='ret_NUM_TELEFON_" . $count . "' value='" . $qrListaUsuario['NUM_TELEFON'] . "'>
					<input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrListaUsuario['NUM_CELULAR'] . "'>
					<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaUsuario['COD_EXTERNO'] . "'>
					<input type='hidden' id='ret_COD_TPUSUARIO_" . $count . "' value='" . $qrListaUsuario['COD_TPUSUARIO'] . "'>
					<input type='hidden' id='ret_COD_PERFILS_" . $count . "' value='" . $qrListaUsuario['COD_PERFILS'] . "'>
					<input type='hidden' id='ret_COD_USUARIOS_AGE_" . $count . "' value='" . $qrListaUsuario['COD_USUARIOS_AGE'] . "'>
					<input type='hidden' id='ret_COD_USUARIOS_ATE_" . $count . "' value='" . $qrListaUsuario['COD_USUARIOS_ATE'] . "'>
					<input type='hidden' id='ret_COD_MULTEMP_" . $count . "' value='" . $qrListaUsuario['COD_MULTEMP'] . "'>
					<input type='hidden' id='ret_COD_DEFSIST_" . $count . "' value='" . $qrListaUsuario['COD_DEFSIST'] . "'>
					<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrListaUsuario['COD_UNIVEND'] . "'>
					<input type='hidden' id='ret_TEM_PERFIL_" . $count . "' value='" . $tem_perfil . "'>
					<input type='hidden' id='ret_TEM_USUARIOS_AGE_" . $count . "' value='" . $tem_usuarios_age . "'>
					<input type='hidden' id='ret_TEM_MASTER_" . $count . "' value='" . $tem_master . "'>
					<input type='hidden' id='ret_TEM_UNIVE_" . $count . "' value='" . $tem_unive . "'>
					<input type='hidden' id='ret_COD_INDICA_" . $count . "' value='" . $qrListaUsuario['COD_INDICADOR'] . "'>
					<input type='hidden' id='ret_COD_INDICA_ENC_" . $count . "' value='" . fnEncode($qrListaUsuario['COD_INDICADOR']) . "'>
					<input type='hidden' id='ret_NOM_INDICA_" . $count . "' value='" . $nom_indicad . "'>
					<input type='hidden' id='ret_COD_TURNO_" . $count . "' value='" . $qrListaUsuario['COD_TURNO'] . "'>
					";
		}

		break;

	case 'pesquisar':
		$log_usuario = fnLimpaCampo($_POST['LOG_USUARIO']);

		$sql = "SELECT * FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND LOG_USUARIO = '$log_usuario'";
		$query = mysqli_query($connAdm->connAdm(), $sql);
		$qtdRegistros = mysqli_num_rows($query);

		echo $qtdRegistros;
		break;
}
