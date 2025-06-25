<?php
$itens_carregar_mais = "";
$cod_cliente = "";
$valorTTotal = "";
$valorTRegaste = "";
$valorTDesconto = "";
$valorTvenda = "";
$idVenda = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$sqlitems = "";
$CABECHALHOITEM = "";
$arrayitem = [];
$qrListaItem = "";
$limpandostringItem = "";
$textolimpoItem = "";
$valorTResgate = "";
$valorResgate = "";
$classeExc = "";
$qrBuscaProdutos = "";
$classeExc2 = "";
$mostraItemExcluido = "";
$colunaEspecial = "";
$tokem = "";
$tokemexec = "";
$rwtokem = "";
$colunaEspecialPlaca = "";
$colunaPlaca = "";


include '_system/_functionsMain.php';

$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);

$itens_carregar_mais = @$_GET['itens_carregar_mais'];
$cod_cliente = @$_POST['COD_CLIENTE'];
$valorTTotal = @$_POST['VL_TOTAL'];
$valorTRegaste = @$_POST['VL_REGASTE'];
$valorTDesconto = @$_POST['VL_DESCONTO'];
$valorTvenda = @$_POST['VL_VENDA'];
$idVenda = fnLimpacampo(@$_GET['idVenda']);

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$sql = "CALL LISTA_COMPRA('$cod_cliente', '$cod_empresa', 0, 99999)";
		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$arquivo = fopen($arquivoCaminho, 'w', 0);

		// while($headers=mysqli_fetch_field($arrayQuery)){
		// 	$CABECHALHO[]=$headers->name;
		// }

		$CABECHALHO = ['Data', 'ID', 'ID_Venda', 'Cupom', 'Tipo', 'Motivo', 'Loja', 'Vl_Total', 'Vl_Resgate', 'Vl_Desconto', 'Vl_Venda', 'Pagamento', 'Vendedor', 'Atendente'];

		fputcsv($arquivo, $CABECHALHO, ';', '"');

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$row['VAL_TOTPRODU'] = fnValor($row['VAL_TOTPRODU'], 2);
			$row['VAL_DESCONTO'] = fnValor($row['VAL_DESCONTO'], 2);
			$row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);
			$row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
			unset($row['EXCLUIDO']);
			unset($row['COD_CREDITOU']);
			//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
			//$textolimpo = json_decode($limpandostring, true);
			$array = array_map("utf8_decode", $row);
			fputcsv($arquivo, $array, ';', '"');

			$sqlitems = "SELECT '',A.COD_PRODUTO,B.COD_EXTERNO,B.DES_PRODUTO,a.QTD_PRODUTO,a.VAL_UNITARIO,a.VAL_TOTITEM,a.VAL_TOTLIQUI   
							FROM itemvenda a
							LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
							WHERE a.COD_VENDA = $row[COD_VENDA]
							UNION				
							SELECT '',A.COD_PRODUTO,B.COD_EXTERNO,B.DES_PRODUTO,a.QTD_PRODUTO,a.VAL_UNITARIO,a.VAL_TOTITEM,a.VAL_TOTLIQUI    
							FROM itemvenda_bkp a
							LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
							WHERE a.COD_VENDA = $row[COD_VENDA]
				";

			$CABECHALHOITEM = ["", "", "", "", "", "", "", ""];
			fputcsv($arquivo, $CABECHALHOITEM, ';', '"');

			$arrayitem = mysqli_query(connTemp($cod_empresa, ''), $sqlitems);
			//fnEscreve($sqlitems);

			$CABECHALHOITEM = ['Codigo', 'Cod_Ext', 'Nome_do_Produto', 'Qtd', 'Vl_Unitario', 'Vl_Total'];
			fputcsv($arquivo, $CABECHALHOITEM, ';', '"');

			while ($qrListaItem = mysqli_fetch_assoc($arrayitem)) {
				$qrListaItem['VAL_UNITARIO'] = fnValor($qrListaItem['VAL_UNITARIO'], 2);
				$qrListaItem['VAL_TOTITEM'] = fnValor($qrListaItem['VAL_TOTITEM'], 2);
				$qrListaItem['VAL_TOTLIQUI'] = fnValor($qrListaItem['VAL_TOTLIQUI'], 2);
				$qrListaItem['QTD_PRODUTO'] = fnValor($qrListaItem['QTD_PRODUTO'], 2);
				unset($qrListaItem['VAL_TOTLIQUI']);
				//$limpandostringItem= fnAcentos(Utf8_ansi(json_encode($qrListaItem)));
				//$textolimpoItem=json_decode($limpandostringItem,true);
				$array = array_map("utf8_decode", $qrListaItem);
				fputcsv($arquivo, $array, ';', '"');
			}

			$CABECHALHOITEM = ["", "", "", "", "", "", "", ""];
			fputcsv($arquivo, $CABECHALHOITEM, ';', '"');

			// echo "<pre>";
			// print_r($row);
			// echo "</pre>";

		}

		fclose($arquivo);

		break;

	case 'carregarMais':

		$sql = "CALL LISTA_COMPRA('$cod_cliente', '$cod_empresa', '$itens_carregar_mais', '15')";

		//fnEscreve(fnDecode("7T3jekr0Xfk¢"));
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;
		$valorTTotal = 0;
		$valorTResgate = 0;
		$valorResgate = 0;
		$valorTDesconto = 0;
		$valorTvenda = 0;
		$classeExc = "";

		while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
			$count++;
			if (@$qrBuscaProdutos['EXCLUIDO'] == 3) {
				$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
				$valorTResgate = $valorTResgate + $qrBuscaProdutos['VAL_RESGATE'];
				$valorResgate = $qrBuscaProdutos['VAL_RESGATE'];
				$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
				$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
				$classeExc = "";
			} else if (@$qrBuscaProdutos['EXCLUIDO'] == 1) {
				$classeExc = "text-danger";
			} else {
				$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
				$valorTResgate = $valorTResgate + $qrBuscaProdutos['VAL_RESGATE'];
				$valorResgate = $qrBuscaProdutos['VAL_RESGATE'];
				$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
				$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
				$classeExc = "text-warning";
			}

			$count++;
			if ($qrBuscaProdutos['EXCLUIDO'] == 3) {
				$classeExc2 = "";
				$mostraItemExcluido = "";
			} else if ($qrBuscaProdutos['EXCLUIDO'] == 1) {
				$classeExc2 = "text-danger";
				$mostraItemExcluido = "<i class='fal fa-minus-circle' aria-hidden='true' $tooltip></i>";
			} else {
				$classeExc2 = "text-warning";
				$mostraItemExcluido = "<i class='fal fa-minus-circle' aria-hidden='true' $tooltip></i>";
			}


			if ($cod_empresa != 19) {
				if ($qrBuscaProdutos['EXCLUIDO'] != 1) {
					$colunaEspecial = $qrBuscaProdutos['DES_OCORREN'];
					if ($qrBuscaProdutos['COD_CREDITOU'] == 4) {
						$colunaEspecial = "Pontuação Inativa";
					}
				} else {
					$colunaEspecial = "venda estornada";
					if ($qrBuscaProdutos['COD_CREDITOU'] == 4) {
						$colunaEspecial = "Pontuação Inativa";
					}
				}
			} else {
				$tokem = "select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
									  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
									  from itemvenda 
								inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
								where vendas.COD_VENDA='" . $qrBuscaProdutos['COD_VENDA'] . "'";
				$tokemexec = mysqli_query(connTemp($cod_empresa, ''), $tokem);
				$rwtokem = mysqli_fetch_assoc($tokemexec);
				$colunaEspecial = $rwtokem['DES_PARAM2'];
				$colunaEspecialPlaca = $rwtokem['DES_PARAM1'];
				if ($colunaEspecial == '') {
					$colunaEspecial = '<i class="fal fa-times text-danger fa-2x" aria-hidden="true"></i>';
				}
			}
			if ($cod_empresa == 19) {
				$colunaPlaca = "<td class='" . $classeExc . "'  class='text-center'><small>" . $colunaEspecialPlaca . "</small></td>";
			}

			echo "
					<tr id=" . "cod_venda_" . $qrBuscaProdutos['COD_VENDA'] . ">															
					  <td class='text-center " . $classeExc . "'><a href='javascript:void(0);' onclick='abreDetail(" . $qrBuscaProdutos['COD_VENDA'] . ",\"" . $qrBuscaProdutos['NOM_VENDEDOR'] . "\",\"" . $qrBuscaProdutos['NOM_ATENDENTE'] . "\")'><i class='expande fa fa-plus' aria-hidden='true'></i></a></td>
					  <td class='text-center " . $classeExc2 . "'>" . $mostraItemExcluido . "</td>
					  <td class='" . $classeExc . "'><small>" . fnFormatDateTime($qrBuscaProdutos['DAT_CADASTR_WS']) . "</small></td>
					  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_VENDA'] . "</td>												
					  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_VENDAPDV'] . "</td>												
					  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_CUPOM'] . "</td>												
					  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['DES_LANCAMEN'] . "</td>												
					  <td class='" . $classeExc . "'  class='text-center'><small>" . $colunaEspecial . "</small></td>
					  $colunaPlaca											
					  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['NOM_FANTASI'] . "</td>												
					  <td class='" . $classeExc . " text-right'><b>" . fnValor($qrBuscaProdutos['VAL_TOTPRODU'], 2) . "</b></td>
					  <td class='" . $classeExc . " text-right'>" . fnValor($valorResgate, 2) . "</td>
					  <td class='" . $classeExc . " text-right'>" . fnValor($qrBuscaProdutos['VAL_DESCONTO'], 2) . "</td>
					  <td class='" . $classeExc . " text-right'>" . fnValor($qrBuscaProdutos['VAL_TOTVENDA'], 2) . "</td>
					  <td class='" . $classeExc . "' >" . fnAcentos($qrBuscaProdutos['DES_FORMAPA']) . "</td>												
					</tr>
					
				  <tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrBuscaProdutos['COD_VENDA'] . "'>
					<td></td>
					<td colspan='13'>
					<div id='mostraDetail_" . $qrBuscaProdutos['COD_VENDA'] . "'>

					
					</div>
					</td>
				  </tr>
				  
					";
		}


		break;
}
?>

<script>
	// alert('<?= $valorTTotal ?>');
	$("#VL_TOTAL").val(<?= $valorTTotal ?>);
	$("#VL_TOTAL_TFOOT").text("<?= fnValor($valorTTotal, 2) ?>");
	$("#VL_REGASTE").val(<?= $valorTRegaste ?>);
	$("#VL_REGASTE_TFOOT").text("<?= fnValor($valorTRegaste, 2) ?>");
	$("#VL_DESCONTO").val(<?= $valorTDesconto ?>);
	$("#VL_DESCONTO_TFOOT").text("<?= fnValor($valorTDesconto, 2) ?>");
	$("#VL_VENDA").val(<?= $valorTvenda ?>);
	$("#VL_VENDA_TFOOT").text("<?= fnValor($valorTvenda, 2) ?>");
</script>