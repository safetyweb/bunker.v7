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
$lojasSelecionadas = "";
$num_cgcecpf = "";
$nom_cliente = "";
$dias30 = "";
$hoje = "";
$andNome = "";
$andCpf = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = "";
$array = "";
$row = "";
$newRow = "";
$cod_fantasi = "";
$num_cupom = "";
$objeto = "";
$arrayColumnsNames = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrCupom = "";
$sqlUni = "";
$qrEmp = "";

require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_POST['NUM_CGCECPF']));
$nom_cliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);


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

if ($nom_cliente != '' && $nom_cliente != 0) {
	$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
} else {
	$andNome = "";
}

if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
	$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
} else {
	$andCpf = "";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";
		//============================

		$sql = "SELECT distinct UN.COD_FANTASI, 
							GC.NUM_CUPOM, 
							CL.NOM_CLIENTE AS CLIENTE, 
							CL.NUM_CGCECPF AS CPF,
							UN.NOM_FANTASI AS LOJA,
							GC.DAT_COMPRA,
							VD.VAL_TOTVENDA,
							VD.QTD_VENDA AS QTD_ATENDIMENTOS
					FROM GERACUPOM GC 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = GC.COD_CLIENTE 
					LEFT JOIN CUPOM_CLIENTE_VENDA VD ON VD.COD_VENDA = GC.COD_VENDA 
					LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = GC.COD_UNIVEND 
					WHERE GC.DAT_COMPRA 
					BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
					GC.COD_UNIVEND IN($lojasSelecionadas) AND 
					GC.COD_EMPRESA = $cod_empresa
					$andNome
					$andCpf
					order by GC.DAT_COMPRA desc";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$cod_fantasi = $row['COD_FANTASI'];
			$num_cupom = "$row[NUM_CUPOM]";

			$cont = 0;
			foreach ($row as $objeto) {

				// Colunas que são double converte com fnValor
				if ($cont == 6) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else if ($cont == 0) {
				} else if ($cont == 1) {
					// $cod_fantasi .= ".";
					$objeto = $cod_fantasi . " " . $num_cupom;
					//fnescreve($objeto);
					array_push($newRow, $objeto);
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		$count = 0;
		while ($row = mysqli_fetch_field($arrayQuery)) {
			if ($count == 0) {
			} else if ($count == 1) {
				array_push($arrayColumnsNames, "NUM_CUPOM");
			} else {
				array_push($arrayColumnsNames, $row->name);
			}

			$count++;
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	case 'paginar':

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT distinct	GC.*, 
								CL.NOM_CLIENTE, 
								CL.NUM_CGCECPF,
								VD.VAL_TOTVENDA,
								VD.QTD_VENDA
						FROM GERACUPOM GC 
						LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = GC.COD_CLIENTE 
						LEFT JOIN CUPOM_CLIENTE_VENDA VD ON VD.COD_VENDA = GC.COD_VENDA 
						WHERE GC.DAT_COMPRA 
						BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
						GC.COD_UNIVEND IN($lojasSelecionadas) AND 
						GC.COD_EMPRESA = $cod_empresa
						$andNome
						$andCpf
						";
		//fnTestesql(connTemp($cod_empresa,''),$sql);		
		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT distinct	GC.*, 
								CL.NOM_CLIENTE, 
								CL.NUM_CGCECPF, 
								VD.VAL_TOTVENDA,
								VD.QTD_VENDA
						FROM GERACUPOM GC 
						LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = GC.COD_CLIENTE 
						LEFT JOIN CUPOM_CLIENTE_VENDA VD ON VD.COD_VENDA = GC.COD_VENDA 
						WHERE GC.DAT_COMPRA 
						BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
						GC.COD_UNIVEND IN($lojasSelecionadas) AND 
						GC.COD_EMPRESA = $cod_empresa
						$andNome
						$andCpf
						order by GC.DAT_COMPRA desc 
						LIMIT $inicio,$itens_por_pagina
						";

		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);											
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrCupom = mysqli_fetch_assoc($arrayQuery)) {

			$sqlUni = "SELECT COD_FANTASI, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_UNIVEND=" . $qrCupom['COD_UNIVEND'];

			$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUni));

			$count++;
			echo "
						<tr>
						  <td>" . $qrEmp['COD_FANTASI'] . "." . $qrCupom['NUM_CUPOM'] . "</td>
						  <td>" . $qrCupom['NOM_CLIENTE'] . "</td>
						  <td>" . $qrCupom['NUM_CGCECPF'] . "</td>
						  <td>" . $qrEmp['NOM_FANTASI'] . "</td>
						  <td>" . fnDataFull($qrCupom['DAT_COMPRA']) . "</td>
						  <td>" . $qrCupom['QTD_VENDA'] . "</td>
						  <td class='text-right'>R$ " . fnValor($qrCupom['VAL_TOTVENDA'], 2) . "</td>
						</tr>
						";
		}

		break;
}
