<?php
include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$mostraXml = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$dias30 = "";
$hoje = "";
$num_cgcecpf = "";
$andCpf = "";
$cod_vendapdv = "";
$andVendaPDV = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$temUnivend = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$NOM_ARRAY_UNIDADE = [];
$NOM_ARRAY_NON_VENDEDOR = [];
$objeto = "";
$tipo = "";
$arrayColumnsNames = [];
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$sql2 = "";
$countLinha = "";
$qrListaVendas = "";



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$mostraXml = @$_GET['mostrarXML'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}


if ($num_cgcecpf == "") {
	$andCpf = " ";
} else {
	$andCpf = "AND A.NUM_CGCECPF = $num_cgcecpf ";
}

if ($cod_vendapdv == "") {
	$andVendaPDV = " ";
} else {
	$andVendaPDV = "A.COD_PDV = '" . $cod_vendapdv . "' AND ";
}

//============================
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
$ARRAY_VENDEDOR1 = array(
	'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

switch ($opcao) {
	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		if ($num_cgcecpf == "") {
			$andCpf = " ";
		} else {
			$andCpf = "AND A.NUM_CGCECPF = $num_cgcecpf";
		}

		if ($cod_vendapdv == "") {
			$andVendaPDV = " ";
		} else {
			$andVendaPDV = "AND A.COD_PDV = '" . $cod_vendapdv . "'";
		}

		$sql = "SELECT CL.NOM_CLIENTE,
							A.DAT_USUCADA AS DAT_EXCLUSA,
							A.VAL_EXCLUIDO,
							A.LOG_TOTAL AS TIPO,
							A.COD_USUCADA,
							A.COD_UNIVEND
					FROM VENDAS_EXC A, CLIENTES CL
					WHERE A.COD_CLIENTE=CL.COD_CLIENTE AND
					A.DAT_USUCADA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND A.COD_EMPRESA = $cod_empresa
					$andCpf
					";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();
		while ($row = mysqli_fetch_assoc($arrayQuery)) {
			$newRow = array();

			$NOM_ARRAY_UNIDADE = (array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
			$NOM_ARRAY_NON_VENDEDOR = (array_search($row['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

			$count = 0;
			foreach ($row as $objeto) {

				if ($count == 1) {
					array_push($newRow, fnDataFull($objeto));
				} else if ($count == 2) {
					array_push($newRow, "R$ " . fnValor($objeto, 2));
				} else if ($count == 3) {
					if ($objeto == 'S') {
						$tipo = "Total";
					} else {
						$tipo = "Parcial";
					}
					array_push($newRow, $tipo);
				} else if ($count == 4) {
					array_push($newRow, $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']);
				} else if ($count == 5) {
					array_push($newRow, $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
				} else {
					array_push($newRow, $objeto);
				}
				$count++;
			}
			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;
	case 'paginar':

		//fnEscreve(date('Y-m-d'));	
		//fnEscreve($dat_fim);

		$sql = "SELECT A.COD_ORIGEM
					FROM ORIGEMESTORNAVENDA A
					WHERE A.COD_EMPRESA = $cod_empresa
					AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andCpf
					$andVendaPDV
					";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);
		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


		$sql2 = "SELECT A.COD_UNIVEND,
							A.COD_PDV,
							A.NUM_CGCECPF,
							A.DAT_CADASTR,
							A.COD_ORIGEM
					FROM ORIGEMESTORNAVENDA A
					WHERE A.COD_EMPRESA = $cod_empresa
					AND A.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andCpf
					$andVendaPDV
					ORDER BY DAT_CADASTR
					LIMIT $inicio, $itens_por_pagina";

		// fnEscreve($sql2);	

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);

		$countLinha = 1;
		while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

			$NOM_ARRAY_UNIDADE = (array_search(@$qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
			$NOM_ARRAY_NON_VENDEDOR = (array_search(@$qrListaVendas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

?>
			<tr>
				<td><small><?php echo $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']; ?></small></td>
				<td><small><?php echo $qrListaVendas['COD_PDV']; ?></small></td>
				<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
				<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
				<td class="text-center"><a class="btn btn-xs btn-default addBox" data-url="action.php?mod=<?php echo fnEncode(1244); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idE=<?php echo fnEncode($qrListaVendas['COD_ORIGEM']); ?>&pop=true" data-title="XML Recebido"><small><i class="fa fa-code"></i></small></a></td>
			</tr>
<?php

			$countLinha++;
		}

		break;
}
?>