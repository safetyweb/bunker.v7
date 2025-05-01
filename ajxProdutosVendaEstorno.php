<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);

if ($buscaAjx3 == "EXC" ){
	
	$sql = "CALL SP_ALTERA_AUXVENDA (
	 '".$buscaAjx4."', 
	 '".$buscaAjx2."', 
	 '0',
	 '0', 
	 '0',
	 'EXC'    
	) ";
	
	echo $sql;				
	
	mysqli_query(connTemp($buscaAjx1,''),trim($sql)) or die(mysqli_error());
	
}

?>	
	<table class="table" style="width: auto;">
		<tr>
		<td colspan="2"><small><a class="btn btn-danger btn-sm" href="#" onClick="estornarVenda(this, <?php echo $buscaAjx1;?>, <?php echo $buscaAjx2;?>);"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp; Estornar venda</a></small></td>
		<td colspan="4"></td>
		</tr>
	
		<tr>
		<th><small>Nome do Produto</small></th>
		<th class="text-right"><small>Qtd.</small></th>
		<th class="text-right"><small>Vl. Unitário</small></th>
		<th class="text-right"><small>Vl. Total</small></th>
		</tr>
		
		<?php 																	
			$sql = "SELECT B.DES_PRODUTO ,a.*   
					FROM itemvenda a
					LEFT JOIN produtocliente b ON b.COD_PRODUTO = a.COD_PRODUTO 
					WHERE COD_VENDA = '".$buscaAjx2."'
			";
			
			$totalDetalhe = 0;
			
			$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
			
			//fnEscreve($sql);
			
			while ($qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery))
			  {
				  
				$totalDetalhe = $totalDetalhe + $qrListaDetalheVenda['VAL_TOTITEM'];
			?>																			  
			<tr codItemVenda="<?php echo $qrListaDetalheVenda['COD_ITEMVEN'];?>">
			<td><small><?php echo $qrListaDetalheVenda['DES_PRODUTO'];?></small></td>
			<td class="text-right"><small><div class="prodQtdeLinha"><?php echo fnValor($qrListaDetalheVenda['QTD_PRODUTO'],2);?></div></small></td>
			<td class="text-right"><small><div class="prodValorUnitLinha"><?php echo fnValor($qrListaDetalheVenda['VAL_UNITARIO'],2);?></div></small></td>
			<td class="text-right"><small><div class="prodValorLinha"><?php echo fnValor($qrListaDetalheVenda['VAL_TOTITEM'],2);?></div></small></td>
			<!--<td class="text-right"><small><a class="btn btn-danger btn-sm btSmall" onClick="excluirVenda(this, <?php echo $buscaAjx1;?>, <?php echo $buscaAjx2;?>);"> Estornar item </a></small></td>-->
			<td class="text-right"><small><a class="btn btn-danger btn-sm btSmall" onClick="estornarItem(this, <?php echo $buscaAjx1;?>, <?php echo $buscaAjx2;?>, <?php echo $qrListaDetalheVenda['COD_ITEMVEN'];?>, <?php echo $qrListaDetalheVenda['QTD_PRODUTO'];?>);"> Estornar item </a></small></td>
			</tr>
		
					
		<?php 																				
				  }											
		?>																	
			<tr>
			<td><small><b>Total</b></small></td>
			<td class="text-right" colspan="3"><small><b><div class="subtotalProd"><?php echo fnValor($totalDetalhe,2);?></div></b></small></td>
			</tr>
																			
	</table>