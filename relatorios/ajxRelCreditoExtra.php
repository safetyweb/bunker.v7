<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$opcao = "";
$tipo = "";
$itens_por_pagina = "";
$pagina = "";
$casasDec = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$cod_status = "";
$num_cartao = "";
$autoriza = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$andCodStatus = "";
$andCartao = "";
$sql = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$newRow = "";
$objeto = "";
$NOM_ARRAY_NON_VENDEDOR = "";
$ARRAY_VENDEDOR = "";
$arrayColumnsNames = "";
$writer = "";
$retorno = "";
$total_itens_por_pagina = "";
$inicio = "";
$teste = "";
$countLinha = "";
$qrListaVendas = "";
$vendedor = "";
$NOM_ARRAY_UNIDADE = "";
$ARRAY_UNIDADE = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}



include "../filtroGrupoLojas.php";


$opcao = getInput($_GET, 'opcao');
$tipo = getInput($_GET, 'tipo');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));
$casasDec = $_REQUEST['CASAS_DEC'];
$cod_univend = getInput($_POST, 'COD_UNIVEND');
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$lojasSelecionadas = getInput($_POST, 'LOJAS');
$cod_status = getInput($_POST, 'COD_STATUS');
$num_cartao = fnLimpacampo(getInput($_POST, 'CARTAO'));

$autoriza = fnLimpaCampoZero(getInput($_POST, 'AUTORIZA'));

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

if ($cod_status == 9999) {
	$andCodStatus = "AND A.COD_STATUS IN(1,2,3,4,5,7,8,9,10,11,12)";
	//fnEscreve("if");
} else {
	$andCodStatus = "AND A.COD_STATUS = $cod_status ";
	//fnEscreve("else");
}
if ($num_cartao != "") {
	$andCartao = "AND C.NUM_CARTAO = $num_cartao";
} else {
	$andCartao = "";
}


switch ($opcao) {
	case 'exportar':

		switch ($tipo) {
			case 'exportCli':
				$sql = "SELECT 
					tmpcred.COD_CREDITO, 
					tmpcred.COD_CLIENTE,
					tmpcred.COD_UNIVEND,
					tmpcred.NOM_FANTASI,
					tmpcred.NOM_CLIENTE,
					tmpcred.NUM_CARTAO,
					tmpcred.DAT_CADASTR,
					tmpcred.VAL_CREDITO,
					SUM(V.VAL_TOTITEM) VAL_TOTITEM
					FROM (					

						SELECT 		
						A.COD_CREDITO, 
						A.COD_CLIENTE,
						A.COD_UNIVEND,
						uni.NOM_FANTASI,
						C.NOM_CLIENTE,
						C.NUM_CARTAO,
						A.DAT_CADASTR,
						SUM(A.VAL_CREDITO) VAL_CREDITO,
						A.COD_VENDA
						FROM CREDITOSDEBITOS A  					
						LEFT JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE
						LEFT JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS
						LEFT JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						LEFT JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR

						WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59'
						AND A.COD_EMPRESA = $cod_empresa
						$andCodStatus
						$andCartao						
						AND A.TIP_CREDITO='C'
						AND A.COD_UNIVEND IN($lojasSelecionadas)
						GROUP BY A.COD_CLIENTE
						)tmpcred
					left JOIN ITEMVENDA V ON V.COD_CLIENTE=tmpcred.COD_CLIENTE AND V.COD_VENDA=tmpcred.COD_VENDA						
					GROUP BY tmpcred.COD_CLIENTE";

				break;

			case 'exportVen':
				$sql = "SELECT 		
						A.COD_CREDITO, 
						A.COD_CLIENTE,
						A.COD_UNIVEND,
						uni.NOM_FANTASI,
						US.NOM_USUARIO,
						A.COD_VENDEDOR,
						C.NOM_CLIENTE,
						C.NUM_CARTAO,
						A.VAL_CREDITO,
						V.VAL_TOTITEM,
						D.DES_STATUS,
						A.DES_OPERACA,
						A.DAT_CADASTR DAT_VENDA,
						A.DAT_EXPIRA,
						F.DES_STATUSCRED
						FROM CREDITOSDEBITOS A  
						LEFT JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE
						LEFT JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS
						LEFT JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						LEFT JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR
						left JOIN ITEMVENDA V ON V.COD_CLIENTE=A.COD_CLIENTE AND V.COD_VENDA=A.COD_VENDA
						WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59'
						AND A.COD_EMPRESA = $cod_empresa
						$andCodStatus
						$andCartao
						AND A.TIP_CREDITO='C'
						AND A.COD_UNIVEND IN($lojasSelecionadas)";
				break;

			case 'exportar':
				$sql = "SELECT 		
						A.COD_UNIVEND,
						uni.NOM_FANTASI,
						SUM(A.VAL_CREDITO) VAL_CREDITO,
						SUM(V.VAL_TOTITEM) VAL_TOTITEM
						FROM CREDITOSDEBITOS A  
						LEFT JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE
						LEFT JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS
						LEFT JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						LEFT JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR
						left JOIN ITEMVENDA V ON V.COD_CLIENTE=A.COD_CLIENTE AND V.COD_VENDA=A.COD_VENDA
						WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' 
						AND A.COD_EMPRESA = $cod_empresa
						$andCodStatus
						$andCartao
						AND A.TIP_CREDITO='C'
						AND A.COD_UNIVEND IN($lojasSelecionadas)
						GROUP BY A.COD_UNIVEND";
				break;
		}

		$nomeRel = getInput($_GET, 'nomeRel');
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			if ($row['COD_VENDEDOR'] == 0) {
				$row['COD_VENDEDOR'] = "";
			} else {
				$row['COD_VENDEDOR'] = $row['COD_VENDEDOR'];
			}
			$row['VAL_CREDITO'] = fnValor($row['VAL_CREDITO'], 2);
			$row['VAL_TOTITEM'] = fnValor($row['VAL_TOTITEM'], 2);
			$row['DAT_CADASTR'] = fnDataFull($row['DAT_CADASTR'], 2);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');

			//echo "<pre>";
			//print_r($row);
			//echo "</pre>";
		}
		fclose($arquivo);

		/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				  
				  

				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
                                         if($cont == 1){
						$NOM_ARRAY_NON_VENDEDOR=(array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
						array_push($newRow, $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']);
					}else if($cont == 3){
						array_push($newRow,fnValor($objeto, $casasDec));
					}else{
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			*/
		break;

	case 'paginar':

		$sql = "SELECT 
					1
					WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' 
					AND A.COD_EMPRESA = $cod_empresa 
					$andCodStatus
					$andCartao
					AND A.TIP_CREDITO='C' 
					AND A.COD_UNIVEND IN($lojasSelecionadas)";

		//fnEscreve($sql);

		$retorno = mysqli_query($conn, $sql);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT 		
						A.COD_CREDITO, 
						A.COD_CLIENTE,
						A.COD_UNIVEND,
						uni.NOM_FANTASI,
						US.NOM_USUARIO,
						A.COD_VENDEDOR,
						C.NOM_CLIENTE,
						C.NUM_CARTAO,
						A.VAL_CREDITO,
						V.VAL_TOTITEM,
						D.DES_STATUS,
						A.DES_OPERACA,
						A.DAT_CADASTR,
						A.DAT_EXPIRA,
						F.DES_STATUSCRED
						FROM CREDITOSDEBITOS A  
						LEFT JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE
						LEFT JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS
						LEFT JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED
						LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
						LEFT JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR
						left JOIN ITEMVENDA V ON V.COD_CLIENTE=A.COD_CLIENTE AND V.COD_VENDA=A.COD_VENDA
						WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' 
						AND A.COD_EMPRESA = $cod_empresa
						$andCodStatus
						$andCartao
						AND A.TIP_CREDITO='C'
						AND A.COD_UNIVEND IN($lojasSelecionadas)
						LIMIT $inicio, $itens_por_pagina";


		$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);
		//echo "<pre>";
		//print_r($arrayQuery);
		//echo "</pre>";


		if ($teste = mysqli_num_rows($arrayQuery) != 0) {

			$countLinha = 1;
			while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

				if ($qrListaVendas['NOM_USUARIO'] == 0) {
					$vendedor = "";
				}

				/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                                                                                                                 * 
                                                                                                                                 */
				//$NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaVendas['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

?>
				<tr>
					<td><b><?= $qrListaVendas['NOM_FANTASI'] ?></b></td>
					<td><?= $vendedor ?></td>
					<?php
					if ($autoriza == 1) {
					?>
						<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
					<?php
					} else {
					?>
						<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
					<?php
					}
					?>
					<td><?= fnMascaraCampo($qrListaVendas['NUM_CARTAO']) ?></td>
					<td><small><?= fnDataShort($qrListaVendas['DAT_CADASTR']) ?></small></td>
					<td><small><?= fnValor($qrListaVendas['VAL_CREDITO'], $casasDec) ?></small></td>
					<td><small><?= fnValor($qrListaVendas['VAL_TOTITEM'], 2) ?></small></td>
					<td><?= $qrListaVendas['DES_STATUS'] ?></td>
					<td><?= $qrListaVendas['DES_OPERACA'] ?></td>
					<td><small><?= fnDataShort($qrListaVendas['DAT_EXPIRA']) ?></small></td>
				</tr>
			<?php



				$countLinha++;
			}
		} else {
			?>
			<tbody>
				<thead>
					<tr>
						<th colspan="100">
							<center>
								<div style="margin: 10px; font-size: 17px; font-weight: bold">Não há créditos extras nesse período</div>
							</center>
						</th>
					</tr>
				</thead>
			</tbody>

<?php


			break;
		}
}
?>