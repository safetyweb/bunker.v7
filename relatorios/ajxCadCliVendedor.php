<?php
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
$itens_por_pagina = "";
$pagina = "";
$cod_empresa = "";
$cod_usuario = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$num_cgcecpf = "";
$nom_cliente = "";
$dias30 = "";
$hoje = "";
$cod_univend = "";
$andNome = "";
$andCpf = "";
$andVendedor = "";
$nomeRel = "";
$arquivoCaminho = "";
$writer = "";
$arquivo = "";
$sql = "";
$arrayQuery = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrVend = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}


include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = getInput($_GET, 'opcao');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$cod_usuario = fnLimpaCampoZero(getInput($_POST, 'COD_USUARIO'));
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$lojasSelecionadas = getInput($_POST, 'LOJAS');
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(getInput($_POST, 'NUM_CGCECPF')));
$nom_cliente = fnLimpaCampo(getInput($_POST, 'NOM_CLIENTE'));


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

if ($nom_cliente != "") {
	$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
} else {
	$andNome = "";
}

if ($num_cgcecpf != "") {
	$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
} else {
	$andCpf = "";
}

if ($cod_usuario != "" && $cod_usuario != 0) {
	$andVendedor = "AND CL.COD_ATENDENTE = $cod_usuario";
} else {
	$andVendedor = "";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = getInput($_GET, 'nomeRel');
		$arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		/*writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			*/
		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT 
						   UN.NOM_FANTASI LOJA,
						   US.NOM_USUARIO VENDEDOR,	
						   CL.COD_CLIENTE, 
						   CL.NOM_CLIENTE,
						   CL.NUM_CGCECPF CPF, 
						   CL.DAT_CADASTR
					FROM WEBTOOLS.usuarios US
					INNER JOIN CLIENTES CL ON CL.COD_ATENDENTE = US.COD_USUARIO 
					LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = CL.COD_UNIVEND
					WHERE
					CL.COD_EMPRESA=$cod_empresa
					AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND US.COD_UNIVEND IN($lojasSelecionadas)
					$andNome
					$andCpf
					$andVendedor
					ORDER BY CL.DAT_CADASTR DESC";


		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

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
		}
		fclose($arquivo);


		break;

	case 'paginar':

		// Filtro por Grupo de Lojas
		include "filtroGrupoLojas.php";

		$sql = "SELECT US.COD_USUARIO
					FROM WEBTOOLS.usuarios US
					INNER JOIN CLIENTES CL ON CL.COD_ATENDENTE = US.COD_USUARIO
					LEFT JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
					WHERE
					CL.COD_EMPRESA=$cod_empresa
					AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND US.COD_UNIVEND IN($lojasSelecionadas)
					$andNome
					$andCpf
					$andVendedor
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

		$sql = "SELECT US.COD_USUARIO, US.NOM_USUARIO,	CL.COD_CLIENTE, CL.NOM_CLIENTE, CL.NUM_CGCECPF, CL.DAT_CADASTR, UN.NOM_FANTASI
					FROM WEBTOOLS.usuarios US
					INNER JOIN CLIENTES CL ON CL.COD_ATENDENTE = US.COD_USUARIO
					LEFT JOIN WEBTOOLS.UNIDADEVENDA UN ON UN.COD_UNIVEND = US.COD_UNIVEND
					WHERE
					CL.COD_EMPRESA=$cod_empresa
					AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					AND US.COD_UNIVEND IN($lojasSelecionadas)
					$andNome
					$andCpf
					$andVendedor
					ORDER BY CL.DAT_CADASTR DESC
					LIMIT $inicio,$itens_por_pagina
					
					";

		// fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);											
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		while ($qrVend = mysqli_fetch_assoc($arrayQuery)) {

			$count++;
			echo "
					<tr>
					  <td>" . $qrVend['NOM_FANTASI'] . "</td>
					  <td>" . $qrVend['NOM_USUARIO'] . "</td>
					  <td><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrVend['COD_CLIENTE']) . "' class='f14' target='_blank'>" . $qrVend['NOM_CLIENTE'] . "</a></td>
					  <td>" . $qrVend['NUM_CGCECPF'] . "</td>
					  <td>" . fnDatasHORT($qrVend['DAT_CADASTR']) . "</td>
					</tr>
					";
		}

		break;
}
