<?php

include "_system/_functionsMain.php";
include './_system/_FUNCTION_WS.php';


if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$idVenda = "";
$page = "";
$codItemven = "";
$nom_vendedor = "";
$nom_atendente = "";
$cod_cliente = "";
$cod_itemext = "";
$cod_pdv = "";
$qtdeDigitada = 0;
$codProduto = "";
$busca = "";
$arrayclientes = [];
$bsusr = "";
$arrayQuery = [];
$qrBuscaUsuTeste = "";
$cod_univendarray = [];
$unidadeExtorno = "";
$arraydadosCli = [];
$retorno = "";
$codPdv = "";
$qrListaDetalheVenda = "";
$vent = "";
$cod_vendapdv = "";
$dat_cadastr_ws = "";
$cod_statuscred = "";
$hojeSql = "";
$diferencaDias = "";
$diasDiff = "";
$desabilitado = "";
$totalDetalhe = 0;
$dat_extorno = "";
$classeExc = "";
$itemBold = "";
$val_unitario = "";
$val_totitem = "";
$diasEstorno = "";

@$cod_empresa = fnLimpacampoZero(@$_GET['cod_empresa']);
@$idVenda = fnLimpacampo(@$_GET['idVenda']);
//@$page = fnLimpacampo(@$_GET['page']);
@$opcao = fnLimpacampo(@$_GET['opcao']);
@$codItemven = fnLimpacampo(@$_GET['codItemven']);
$nom_vendedor = fnLimpacampo(@$_GET['NOM_VENDEDOR']);
$nom_atendente = fnLimpacampo(@$_GET['NOM_ATENDENTE']);


switch ($opcao) {
	case 'excluirItem':

		$cod_cliente = fnLimpacampo(@$_GET['cod_cliente']);
		$cod_itemext = fnLimpacampo(@$_GET['cod_itemext']);
		$cod_pdv = fnLimpacampo(@$_GET['cod_pdv']);
		$qtdeDigitada = fnLimpacampo(@$_GET['qtdeDigitada']);
		$codProduto = fnLimpacampo(@$_GET['codProduto']);
		//busca do cartao
		$busca = 'select NUM_CGCECPF,NUM_CARTAO, COD_UNIVEND from clientes where COD_CLIENTE=' . $cod_cliente;

		$arrayclientes = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $busca));

		//busca de usuario webservices
		$bsusr = "SELECT * FROM  USUARIOS
					WHERE LOG_ESTATUS='S' AND
					COD_EMPRESA = $cod_empresa AND
					COD_UNIVEND > 0 AND
					COD_TPUSUARIO =10 limit 1";
		//fnEscreve($bsusr); 
		$arrayQuery = mysqli_query($connAdm->connAdm(), $bsusr);
		$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
		$cod_univendarray = explode(',', $qrBuscaUsuTeste['COD_UNIVEND']);

		if ($cod_univendarray['0'] == 0) {
			$unidadeExtorno = $cod_univendarray['0'];
		} else {
			$unidadeExtorno = $arrayclientes['COD_UNIVEND'];
		}

		$arraydadosCli = array(
			'id_vendapdv' => $cod_pdv,
			'login' => $qrBuscaUsuTeste['LOG_USUARIO'],
			'senha' => fnDecode($qrBuscaUsuTeste['DES_SENHAUS']),
			'COD_UNIVEND' => $unidadeExtorno,
			'COD_EMPRESA' => $cod_empresa,
			'cartao' => $arrayclientes['NUM_CARTAO'],
			'id_item' => $cod_itemext,
			'codigoproduto' => $codProduto,
			'quantidade' => $qtdeDigitada
		);


		$retorno = excluivendaparcial($arraydadosCli);

		// echo("<pre>");
		// // print_r($arraydadosCli);
		// print_r($retorno);
		// echo("</pre>");
		// exit();

		break;

	case 'alterarItem':

		break;

	case 'excluirVenda':

		$codPdv = fnLimpacampo(@$_GET['cod_pdv']);
		$idVenda = fnLimpacampo(@$_GET['idVenda']);
		$codItemven = fnLimpacampo(@$_GET['codItemven']);


		$sql = "SELECT COD_UNIVEND, COD_CLIENTE 
				FROM VENDAS
				WHERE COD_VENDA = $idVenda
				AND COD_EMPRESA = $cod_empresa";

		$qrListaDetalheVenda = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

		//busca de usuario webservices
		$bsusr = "SELECT * FROM  USUARIOS
					WHERE LOG_ESTATUS='S' AND
					COD_EMPRESA = $cod_empresa AND
					COD_UNIVEND > 0 AND
					COD_TPUSUARIO =10 limit 1;";
		//fnEscreve($bsusr); 
		$arrayQuery = mysqli_query($connAdm->connAdm(), $bsusr);
		$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
		$cod_univendarray = explode(',', $qrBuscaUsuTeste['COD_UNIVEND']);

		if ($qrListaDetalheVenda['COD_UNIVEND'] == 0) {

			$busca = 'select COD_UNIVEND from clientes where COD_CLIENTE=' . $qrListaDetalheVenda['COD_CLIENTE'];

			$arrayclientes = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $busca));

			$unidadeExtorno = $arrayclientes['COD_UNIVEND'];
		} else {

			$unidadeExtorno = $qrListaDetalheVenda['COD_UNIVEND'];
		}

		$arraydadosCli = array(
			'id_vendapdv' => $codPdv,
			'login' => $qrBuscaUsuTeste['LOG_USUARIO'],
			'senha' => fnDecode($qrBuscaUsuTeste['DES_SENHAUS']),
			'COD_UNIVEND' => $unidadeExtorno,
			'COD_EMPRESA' => $cod_empresa
		);

		$vent = excluivendatotal($arraydadosCli);


		break;
}


$sql = "SELECT COD_VENDAPDV, DAT_CADASTR_WS, COD_STATUSCRED  
		FROM vendas
		WHERE COD_VENDA = $idVenda
		AND COD_EMPRESA = $cod_empresa";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery);
$cod_vendapdv = $qrListaDetalheVenda['COD_VENDAPDV'];
$dat_cadastr_ws = $qrListaDetalheVenda['DAT_CADASTR_WS'];
$cod_statuscred = $qrListaDetalheVenda['COD_STATUSCRED'];

//$hojeSql = date("Y-m-d 23:59:59");
$hojeSql = date("Y-m-d");
// Calcula a diferença em segundos entre as datas 
$diferencaDias = strtotime($hojeSql) - strtotime(fnDataSql($dat_cadastr_ws));
//Calcula a diferença em dias 
$diasDiff = floor($diferencaDias / (60 * 60 * 24));
//echo "A diferença é de $diasDiff entre as datas";


if ($diasDiff > 30) {
	$desabilitado = "disabled";
} else {
	$desabilitado = "";
}

?>

<style>
	.btSmall {
		padding: 3px 4px !important;
		font-size: 12px !important;
		line-height: 1.0 !important;
		border-radius: 3px !important;
	}
</style>

<table class="table" style="width: auto;">
	<?php if (($cod_statuscred != 6) && (!isset($_GET['page'])) && (fnDecode(@$_GET['mod']) == 1518)) { ?>
		<tr>
			<td colspan="2"><small><a class="btn btn-danger btn-sm <?php echo $desabilitado; ?>" href="#" onClick="excluirVenda('<?php echo $cod_vendapdv; ?>', <?php echo $idVenda; ?>);"><i class="fal fa-trash-alt" aria-hidden="true"></i>&nbsp;Estornar venda</a></small></td>
			<td colspan="4"></td>
		</tr>
	<?php } ?>

	<tr>
		<th class="text-left"><small>Vendedor</small></th>
		<th class="text-left"><small>Atendente</small></th>
	</tr>
	<tr>
		<td class="text-left"><small><?= $nom_vendedor ?></small></td>
		<td class="text-left"><small><?= $nom_atendente ?></small></td>
	</tr>
	<tr>
		<th class="text-left"><small>Código</small></th>
		<th class="text-left"><small>Cód. Ext.</small></th>
		<th><small>Nome do Produto</small></th>
		<th class="text-left"><small>Qtd.</small></th>
		<th class="text-left"><small>Vl. Unitário</small></th>
		<th class="text-left"><small>Vl. Total</small></th>
		<th class="text-left"><small>Dt. Extorno</small></th>
	</tr>

	<?php

	$sql = "SELECT B.DES_PRODUTO,B.COD_EXTERNO, a.*, c.VAL_DESCONTOUN, c.VAL_LIQUIDO   
				FROM itemvenda a
				LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
				LEFT JOIN AUXVENDA c ON c.COD_ORCAMENTO = a.COD_ORCAMENTO 
				WHERE a.COD_VENDA = $idVenda
				UNION				
				SELECT B.DES_PRODUTO,B.COD_EXTERNO, a.*, c.VAL_DESCONTOUN, c.VAL_LIQUIDO
				FROM itemvenda_bkp a
				LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
				LEFT JOIN AUXVENDA c ON c.COD_ORCAMENTO = a.COD_ORCAMENTO 
				WHERE a.COD_VENDA = $idVenda
		";

	// fnEscreve($sql);

	$totalDetalhe = 0;

	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	while ($qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery)) {

		// fnEscreve($qrListaDetalheVenda['DAT_EXCLUSA']);

		$dat_extorno = "";

		if ($qrListaDetalheVenda['DAT_EXCLUSA'] != "") {
			$dat_extorno = fnDataFull($qrListaDetalheVenda['DAT_EXCLUSA']);
		}

		$totalDetalhe = $totalDetalhe + $qrListaDetalheVenda['VAL_TOTITEM'];

		if ($qrListaDetalheVenda['COD_EXCLUSA'] == 0) {
			$classeExc = "";
		} else {
			$classeExc = "text-danger";
		}

		if ($codItemven == $qrListaDetalheVenda['COD_ITEMVEN']) {
			$itemBold = "<i class='fas fa-caret-right'></i> ";
		} else {
			$itemBold = "&nbsp; ";
		}

		$val_unitario = $qrListaDetalheVenda['VAL_UNITARIO'];
		$val_totitem = $qrListaDetalheVenda['VAL_UNITARIO'];

		if ($qrListaDetalheVenda['VAL_DESCONTOUN'] > 0) {
			$val_unitario = $qrListaDetalheVenda['VAL_DESCONTOUN'];
			$val_totitem = $qrListaDetalheVenda['VAL_LIQUIDO'];
		}

		// fnEscreve($qrListaDetalheVenda['VAL_DESCONTOUN']);
		// fnEscreve($qrListaDetalheVenda['VAL_LIQUIDO']);
	?>
		<tr class="<?php echo $classeExc; ?>">
			<td class="text-left"><small><?php echo $itemBold; ?> <?php echo $qrListaDetalheVenda['COD_PRODUTO']; ?></small></td>
			<td class="text-left"><small><?php echo $qrListaDetalheVenda['COD_EXTERNO']; ?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['DES_PRODUTO']; ?></small></td>
			<td class="text-left"><small><?php echo fnValor($qrListaDetalheVenda['QTD_PRODUTO'], 2); ?></small></td>
			<td class="text-right"><small><?php echo fnValor($val_unitario, 2); ?></small></td>
			<td class="text-right"><small><?php echo fnValor($val_totitem, 2); ?></small></td>
			<td class="text-left"><small><?php echo $dat_extorno; ?></small></td>
			<?php if (($cod_statuscred != 6) && ($qrListaDetalheVenda['QTD_PRODUTO'] > 0) && (!isset($_GET['page'])) && (fnDecode(@$_GET['mod']) == 1518)) { ?>
				<td class="text-left"><small><a class="btn btn-danger btn-sm btSmall <?php echo $desabilitado; ?>" onClick="excluirItens('<?php echo $qrListaDetalheVenda['COD_ITEMEXT']; ?>', '<?php echo $cod_vendapdv; ?>', '<?php echo $qrListaDetalheVenda['COD_EXTERNO']; ?>', <?php echo $qrListaDetalheVenda['QTD_PRODUTO']; ?>, <?php echo $idVenda; ?>)"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Estornar itens</a></small></td>
			<?php } ?>
		</tr>

	<?php
	}

	//fnEscreve($hojeSql);				  
	//fnEscreve($diasEstorno);				  
	//fnEscreve(var_dump($diasEstorno->diff($hojeSql)));

	?>
	<tr>
		<td><small><b>Total</b></small></td>
		<td class="text-right" colspan="5"><small><b><?php echo fnValor($totalDetalhe, 2); ?></b></small></td>
	</tr>
	<?php if (($cod_statuscred != 6) && (!isset($_GET['page'])) && (fnDecode($_GET['mod']) == 1518)) { ?>
		<tr>
			<td colspan="4"><small>* Vendas superiores a 30 dias não podem ser estornadas.</small></td>
		</tr>
	<?php } ?>

</table>

<?php if (($cod_statuscred != 6) && (!isset($_GET['page'])) && (isset($_GET['mod']) && fnDecode($_GET['mod']) == 1518)) { ?>
	adsfhxfg
<?php } ?>