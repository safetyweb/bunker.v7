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
$tipoVenda = "";
$lojasSelecionadas = "";
$email = "";
$cpf = "";
$celular = "";
$autoriza = "";
$dias30 = "";
$hoje = "";
$des_email = "";
$andEmail = "";
$num_cgcecpf = "";
$andCpf = "";
$num_celular = "";
$andCelular = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$arrayQuery = "";
$countLinha = "";
$qrListaVendas = "";
$mostraAtivo = "";
$vendaIni = "";
$temToken = "";
$queryToken = "";
$statusToken = "";
$andTipo = "";
$nomeRel = "";
$arquivoCaminho = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = "";



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$tipoVenda = @$_POST['tipoVenda'];
$lojasSelecionadas = @$_POST['LOJAS'];
$email = @$_POST['DES_EMAIL'];
$cpf = @$_POST['NUM_CGCECPF'];
$celular = @$_POST['NUM_CELULAR'];

$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

// print_r(@$_POST);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}


switch ($opcao) {
	case 'paginar':

		if ($des_email != "") {
			$andEmail = "AND T.DES_EMAIL = '$des_email'";
		} else {
			$andEmail = "";
		}

		if ($num_cgcecpf != "") {
			$andCpf = "AND T.NUM_CGCECPF = '$num_cgcecpf'";
		} else {
			$andCpf = "";
		}

		if ($num_celular != "") {
			$andCelular = "AND T.NUM_CELULAR = '$num_celular'";
		} else {
			$andCelular = "";
		}

		$sql = "SELECT count(*) as contador FROM tokenapp T
						WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
						$andEmail
						AND T.COD_EMPRESA = $cod_empresa
						AND T.COD_UNIVEND IN($lojasSelecionadas)";

		// fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		$sql = "SELECT 
					V.NOM_FANTASI,
					T.NOM_CLIENTE,
					T.NUM_CGCECPF,
					T.NUM_CELULAR,
					T.DES_EMAIL,
					T.LOG_USADO,
					T.DAT_CADASTR
					FROM tokenapp T
					INNER JOIN unidadevenda V ON V.COD_UNIVEND = T.COD_UNIVEND
					WHERE 
					  T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59' AND
					  T.COD_EMPRESA = $cod_empresa
					  AND T.COD_UNIVEND IN($lojasSelecionadas)
					  order by T.COD_TOKEN desc
					  LIMIT $inicio, $itens_por_pagina
					  ";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
			if ($qrListaVendas['LOG_USADO'] == '2') {
				$mostraAtivo = '<i class="fa fa-check" aria-hidden="true" style="color:#32cd32"></i>';
			} elseif ($qrListaVendas['LOG_USADO'] == '1') {
				$mostraAtivo = '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>';
			}

			if ($countLinha == 1) {
				$vendaIni = $qrListaVendas['DAT_CADASTR'];
			}

			$sql = "SELECT V.NOM_FANTASI, 
						T.NOM_CLIENTE, 
						T.NUM_CGCECPF, 
						T.NUM_CELULAR, 
						T.DES_EMAIL, 
						T.LOG_USADO, 
						T.DAT_CADASTR,
						ret.DES_STATUS,
						ret.CHAVE_CLIENTE
				FROM tokenapp T
				INNER JOIN unidadevenda V ON V.COD_UNIVEND = T.COD_UNIVEND
				left JOIN sms_lista_ret ret ON ret.COD_CLIENTE=T.COD_CLIENTE 
											AND DATE(ret.DAT_CADASTR)=DATE(T.DAT_CADASTR) 
											AND T.NUM_CELULAR!=''
				WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
				$andEmail
				$andCpf
				$andCelular
				AND T.COD_EMPRESA = $cod_empresa 
				AND T.COD_UNIVEND IN($lojasSelecionadas)
				ORDER BY T.COD_TOKEN DESC
				LIMIT $inicio, $itens_por_pagina";


			// if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
			// 	$temToken = ""; }

			// if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
			// 	$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
			// 	$statusToken = "Cliente não cadastrado"; } 


?>
			<tr>
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<?php
				if ($autoriza == 1) {
				?>
					<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['NOM_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
				<?php
				} else {
				?>
					<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
				<?php
				}
				?>
				<!-- <td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td> -->
				<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NUM_CELULAR']; ?></small></td>
				<td><small><?php echo $qrListaVendas['DES_EMAIL']; ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
				<!-- <td><small><?php echo $qrListaVendas['LOG_USADO']; ?></small></td> -->
				<td align='center'><small><?php echo $mostraAtivo; ?></small></td>

			</tr>
<?php

		}
		break;

	default:

		$andTipo = "";

		if ($opcao == "sms") {
			$andTipo = "AND T.TIP_TOKEN=1";
		} else if ($opcao == "email") {
			$andTipo = "AND T.TIP_TOKEN=2";
		}

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// fnEscreve($arquivoCaminho);

		$sql = "
						SELECT 
						V.NOM_FANTASI as Loja,
						T.NOM_CLIENTE as Cliente,
						T.NUM_CGCECPF as CPF,
						T.NUM_CELULAR as Celular,
						T.DES_EMAIL as Email,
						T.LOG_USADO as Log,
						T.DAT_CADASTR as Data
						FROM tokenapp T
						INNER JOIN unidadevenda V ON V.COD_UNIVEND = T.COD_UNIVEND
						WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59' 
						  $andTipo
						  AND T.COD_EMPRESA = $cod_empresa
						  AND T.COD_UNIVEND IN($lojasSelecionadas)
						  order by T.COD_TOKEN desc
						  ";
		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		// fnEscreve($sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}

		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $textolimpo, ';', '"');
		}

		fclose($arquivo);

		break;
}
?>