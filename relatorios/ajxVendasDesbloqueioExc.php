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
$nomCliente = "";
$cod_vendapdv = "";
$tipo_opcao = "";
$lojasSelecionadas = "";
$autoriza = "";
$dias30 = "";
$hoje = "";
$nom_cliente = "";
$andNome = "";
$condicaoVendaPDV = "";
$andData = "";
$andTipo = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$tipoVenda = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$writer = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

// echo fnDebug('true');

// fnEscreve('entrou');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$nomCliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
$cod_vendapdv = fnLimpaCampo(@$_POST['COD_VENDAPDV']);
$tipo_opcao = fnLimpaCampo(@$_POST['TIPO_OPCAO']);
$lojasSelecionadas = fnLimpaCampo(@$_POST['LOJAS']);
$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);
// $lojasSelecionadas = @$_POST['LOJAS'];


//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	// $dat_ini = fnDataSql($dias30); 
	$dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($nom_cliente == "") {
	$andNome = " ";
} else {
	$andNome = "AND C.NOM_CLIENTE LIKE '%" . trim($nom_cliente) . "%' ";
}

if ($cod_vendapdv == "") {
	$condicaoVendaPDV = " ";
} else {
	$condicaoVendaPDV = "AND D.COD_VENDAPDV = '" . $cod_vendapdv . "' ";
}

if ($dat_ini != '' && $dat_ini != 0) {
	$andData = "AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
} else {
	$andData = "";
}

if ($tipo_opcao != "todas" && $tipo_opcao != "") {
	$andTipo = "AND a.TIPO_OPCAO ='$tipo_opcao'";
} else {
	$andTipo = "";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT C.NOM_CLIENTE, 
						   e.NOM_FANTASI LOJA,
						   B.NOM_USUARIO,
					(case when A.cod_venda = 0 then
						'Todas as vendas desbloqueadas deste cliente'
						ELSE
						'Somente venda selecionada'
						END) AS SITUACAO, 
					D.COD_VENDAPDV,
					A.TIPO_OPCAO AS TIPO_VENDA,
					A.DAT_CADASTR
					  FROM historico_venda a
					INNER JOIN webtools.usuarios B ON B.COD_USUARIO = A.COD_USUCADA
					INNER JOIN clientes C ON C.cod_cliente = A.cod_cliente AND C.LOG_AVULSO = 'N'
					LEFT JOIN vendas D ON D.COD_VENDA = A.COD_VENDA
					LEFT JOIN unidadevenda e ON e.COD_UNIVEND = D.COD_UNIVEND
					WHERE a.cod_empresa = $cod_empresa
					AND A.TIP_CAD='S'
					AND D.COD_UNIVEND IN($lojasSelecionadas)
					$andTipo
					$andNome
					$condicaoVendaPDV
					$andData
					ORDER BY A.DAT_CADASTR DESC";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		while ($headers = mysqli_fetch_field($arrayQuery)) {
			$CABECHALHO[] = $headers->name;
		}
		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {


			if (strtolower($row['TIPO_VENDA']) == "exc") {
				$tipoVenda = "Excluída";
			} else {
				$tipoVenda = "Desbloqueada";
			}
			$row['TIPO_VENDA'] = $tipoVenda;

			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');

			//echo "<pre>";
			//print_r($row);
			//echo "<pre>";
		}
		fclose($arquivo);
		/*
			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 5){

						if(strtolower($objeto) == 'exc'){
					  		$tipoVenda = "Excluída";
					  	}else{
					  		$tipoVenda = "Desbloqueada";
					  	}

						array_push($newRow, $tipoVenda);

					}if($cont == 6){

						array_push($newRow, fnDataFull($objeto));

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

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT 1 
					  FROM historico_venda a, webtools.usuarios b,clientes c, vendas D
					WHERE a.cod_usucada=b.cod_usuario 
					AND a.cod_cliente=c.cod_cliente AND C.LOG_AVULSO = 'N'
					AND D.COD_VENDA=a.COD_VENDA
					AND a.cod_empresa=$cod_empresa
					AND A.TIP_CAD='S'
					AND D.COD_UNIVEND IN($lojasSelecionadas)
					$andTipo
					$andNome
					$condicaoVendaPDV
					$andData
					ORDER BY A.DAT_CADASTR DESC
			";

		//fnEscreve($sql);
		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// Filtro por Grupo de Lojas
		include "../filtroGrupoLojas.php";

		$sql = "SELECT c.NOM_CLIENTE, a.*,b.NOM_USUARIO, D.COD_VENDAPDV, E.NOM_FANTASI,
					(case when A.cod_venda = 0 then
						'Todas as vendas desbloqueadas deste cliente'
						ELSE
						'Somente venda selecionada'
						END) AS SITUACAO 
					  FROM historico_venda a
					INNER JOIN webtools.usuarios B ON B.COD_USUARIO = A.COD_USUCADA
					INNER JOIN clientes C ON C.cod_cliente = A.cod_cliente AND C.LOG_AVULSO = 'N'
					LEFT JOIN vendas D ON D.COD_VENDA = A.COD_VENDA
					LEFT JOIN unidadevenda e ON e.COD_UNIVEND = D.COD_UNIVEND
					WHERE a.cod_empresa = $cod_empresa
					AND A.TIP_CAD='S'
					AND D.COD_UNIVEND IN($lojasSelecionadas)
					$andTipo
					$andNome
					$condicaoVendaPDV
					$andData
					ORDER BY A.DAT_CADASTR DESC
					limit $inicio,$itens_por_pagina ";

		// fnEscreve($sql);	
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

			if (strtolower($qrListaVendas['TIPO_OPCAO']) == 'exc') {
				$tipoVenda = "Excluída";
			} else {
				$tipoVenda = "Desbloqueada";
			}

?>
			<tr>
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
				<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
				<td><small><?php echo $qrListaVendas['SITUACAO']; ?></small></td>
				<td class="text-center"><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
				<td class="text-center"><small><?php echo $tipoVenda; ?></small></td>
				<td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
			</tr>
<?php


			$countLinha++;
		}

		break;
}
?>