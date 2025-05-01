<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$buscaAjx4 = fnLimpacampo($_GET['ajx4']);

if (isset($_GET['CASAS_DEC'])) {
	$casasDec = $_GET['CASAS_DEC'];
}

if ($buscaAjx3 == "EXC") {

	$sql = "CALL SP_ALTERA_AUXVENDA (
	 '" . $buscaAjx4 . "', 
	 '" . $buscaAjx2 . "', 
	 '0',
	 '0',
	 '0', 
	 '" . $buscaAjx1 . "',
	 'EXC'    
	) ";

	//echo $sql;				

	//fnEscreve($sql);
	mysqli_query(connTemp($buscaAjx1, ''), trim($sql));
}

if ($buscaAjx3 == "EXC_MANUAL") {

	$sql = "CALL SP_ALTERA_AUXVENDA (
	'" . $buscaAjx4 . "',
	'" . $buscaAjx2 . "', 
	  '0',
	 '0', 
	 '0',
	 '" . $buscaAjx1 . "',
	 'EXC_MANUAL'    
	) ";

	//echo $sql;				

	//fnEscreve($sql);
	mysqli_query(connTemp($buscaAjx1, ''), trim($sql));
}

?>

<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="40" class="text-center"><i class='fa fa-trash' aria-hidden='true'></i></th>
			<th>Código</th>
			<th>Nome do Produto </th>
			<th class="text-center">Qtd.</th>
			<th class="text-right">Valor Unitário</th>
			<th class="text-right">Valor Total</th>
		</tr>
	</thead>
	<tbody>

		<?php

		$sql = "select 
		B.DES_PRODUTO, 
		ROUND(A.VAL_UNITARIO, $casasDec) as VAL_UNITARIO,
		ROUND(A.VAL_DESCONTOUN, $casasDec) as VAL_DESCONTOUN,
		ROUND(A.VAL_LIQUIDO, $casasDec) as VAL_LIQUIDO,
		A.COD_EMPRESA,
		A.QTD_PRODUTO,
		A.COD_PRODUTO,
		A.COD_ORCAMENTO,
		A.COD_PDV,
		A.COD_VENDA  
		from AUXVENDA A,PRODUTOCLIENTE B
			where 
			A.COD_PRODUTO=B.COD_PRODUTO AND
			A.COD_ORCAMENTO = '" . $buscaAjx2 . "' order by A.COD_VENDA";

		// fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($buscaAjx1, ''), $sql);

		$count = 0;
		$valorTotal = 0;

		while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {

			$count++;

			if ($qrBuscaProdutos['VAL_LIQUIDO'] > 0) {
				$valorTotalProd = $qrBuscaProdutos['VAL_LIQUIDO'];
			} else {
				$valorTotalProd = ($qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO']) - $qrBuscaProdutos['VAL_DESCONTOUN'];
			}



			$valorTotal = $valorTotal + $valorTotalProd;

			// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
			// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
			// fnEscreve(fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDec));


			echo "
						<tr>
						<td class='text-center'><a href='javascript:void(0);' onclick='deleteProd(" . $buscaAjx2 . "," . $qrBuscaProdutos['COD_VENDA'] . ")'><i class='fal fa-trash-alt text-danger' aria-hidden='true'></i></a></td>
						<td>" . $qrBuscaProdutos['COD_PRODUTO'] . "</td>
						<td>" . $qrBuscaProdutos['DES_PRODUTO'] . "</td>												
						<td class='text-center'>" . fnValor($qrBuscaProdutos['QTD_PRODUTO'], $casasDec) . "</td>
						<td class='text-right'>" . fnValor($qrBuscaProdutos['VAL_UNITARIO'], $casasDec) . "</td>
						<td class='text-right'>" . fnValor($qrBuscaProdutos['VAL_DESCONTOUN'], $casasDec) . "</td>
						<td class='text-right'>" . fnValor($valorTotalProd, 2) . "</td>
						</tr>
						<input type='hidden' id='COD_PRODUTO' value='" . $qrBuscaProdutos['COD_PRODUTO'] . "'>
					";
		}

		$valorFormatado = floor($valorTotal * 100) / 100;
		if ($buscaAjx1 == 19) {
			$valorFormatado = number_format($valorFormatado, 2, ',', '');
		} else {
			$valorFormatado = fnValor($valorTotal, $casasDec);
		}
		?>

	</tbody>
</table>

<div class="row">

	<div class="col-md-2 pull-right">
		<div class="form-group">
			<label for="VAL_TOTPRODU" class="control-label">Total de Produtos <span class="extSmall">(A)</span></label>
			<input type="text" class="form-control input-sm text-right calcula leituraOff" readonly="readonly" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="<?php echo $valorFormatado; ?>">
		</div>
	</div>

</div>

<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">

<script type="text/javascript">
	try {
		recalcula();
	} catch (err) {}
</script>