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
	
	//echo $sql;				
	
	mysqli_query(connTemp($buscaAjx1,''),trim($sql)) or die(mysqli_error());
	
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
	
	
	<table id="prodBloq" class="table" style="width: auto;"> 
		<tr>
		<th><small>Data</small></th>
		<th><small>ID Venda</small></th>
		<th><small>Tipo</small></th>
		<th><small>Pagamento</small></th>
		<!--<th><small>Motivo</small></th>-->
		<th class="text-left"><small>Vl. Total</small></th>
		<th class="text-left"><small>Vl. Resgate</small></th>
		<th class="text-left"><small>Vl. Desconto</small></th>
		<th class="text-left"><small>Vl. Venda</small></th>
		<th class="text-left"><small>Vl. Bloqueado </small></th>
		</tr>
		
		<?php
			$sql = "SELECT    f.NOM_CLIENTE,
					b.DES_LANCAMEN, 
					c.DES_OCORREN, 
					d.NOM_UNIVEND, 
					e.DES_FORMAPA, 
					(SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS
					WHERE COD_VENDA =a.COD_VENDA AND
					TIP_CREDITO='C') AS VAL_CREDITOS, 
					a.* 
					FROM      vendas a 
					INNER JOIN clientes f ON f.COD_CLIENTE=A.COD_CLIENTE
					LEFT JOIN webtools.tipolancamentomarka b ON        b.cod_lancamen = a.cod_lancamen 
					LEFT JOIN webtools.ocorrenciamarka c ON        c.cod_ocorren = a.cod_ocorren 
					LEFT JOIN webtools.unidadevenda d ON        d.cod_univend = a.cod_univend 
					LEFT JOIN formapagamento e ON        e.cod_formapa = a.cod_formapa
					WHERE    a.COD_STATUSCRED=6 AND
					A.COD_CLIENTE = $buscaAjx2
			";
			
			$totalDetalhe = 0;
			
			$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
			
			//fnEscreve($sql);
			
			while ($qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery))
			  {
				  
				$totalProduto = $totalProduto + $qrListaDetalheVenda['VAL_TOTPRODU'];
				$totalCreditos = $totalCreditos + $qrListaDetalheVenda['VAL_CREDITOS'];
				
			?>																			  
			<tr cod_venda="<?php echo $qrListaDetalheVenda['COD_VENDA'];?>">
			<td><small><?php echo fnDataFull($qrListaDetalheVenda['DAT_CADASTR']);?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['COD_VENDAPDV'];?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['DES_LANCAMEN'];?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['DES_FORMAPA'];?></small></td>
			<!--<td><small><?php echo $qrListaDetalheVenda['DES_OCORREN'];?></small></td>-->
			<td class="text-right"><small><div class="prodBloqLinha"><?php echo fnValor($qrListaDetalheVenda['VAL_TOTPRODU'],2);?></div></small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_RESGATE'],2);?></small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_DESCONTO'],2);?></small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_TOTVENDA'],2);?></small></td>
			<td class="text-right"><small><b><?php echo fnValor($qrListaDetalheVenda['VAL_CREDITOS'],2);?></b></small></td>
			</tr>
		
					
		<?php 																				
				  }											
		?>																	
			<tr>
			<td><small><b>Total</b></small></td>
			<td class="text-right" colspan="4"><small><b><div class="subtotalProdBloq"><?php echo fnValor($totalProduto,2);?></div></b></small></td>
			<td class="text-right" colspan="4"><small><b><?php echo fnValor($totalCreditos,2);?></b></small></td>
			</tr>
																			
	</table>