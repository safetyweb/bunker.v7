<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$buscaAjx4 = fnLimpacampo($_GET['ajx4']);



if ($buscaAjx3 == "EXC" ){
	
	$sql = "CALL SP_ALTERA_AUXRESGATE (
	 '".$buscaAjx4."', 
	 '".$buscaAjx2."', 
	 '0',
	 '0',
	 '0', 
	 '0',
	 'EXC'    
	) ";
	
	//echo $sql;				
	
	//fnEscreve($sql);
	mysqli_query(connTemp($buscaAjx1,''),trim($sql)) or die(mysqli_error());
	
	
}

if ($buscaAjx3 == "EXC_MANUAL" ){
	
	$sql = "CALL SP_ALTERA_AUXRESGATE (
	'".$buscaAjx4."',
	'".$buscaAjx2."', 
	  '0',
	 '0', 
	 '0',
	 '0',
	 'EXC_MANUAL'    
	) ";
	
	//echo $sql;				
	
	//fnEscreve($sql);
	mysqli_query(connTemp($buscaAjx1,''),trim($sql)) or die(mysqli_error());
	
	
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
												
													$sql = "select B.DES_PRODUTO,A.* from AUXRESGATE A,PRODUTOPROMOCAO B
															where 
															A.COD_PRODUTO=B.COD_PRODUTO AND
															A.COD_RESGATE = '".$buscaAjx2."' order by A.COD_ITEM	";
													
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql) or die(mysqli_error());
													
													$count = 0;
													$valorTotal = 0;
													$excManual = '"EXC_MANUAL"';
													$valorTotalResg = 0;
													
													while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														$valorTotalProd = $qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO'];	
					
														$valorTotal += $valorTotalProd;

														$valorTotalResg = $valorTotal;
														
														echo"
															<tr>
															  <td class='text-center'><a href='javascript:void(0);' onclick='RefreshProdutos(".$qrBuscaProdutos['COD_EMPRESA'].",".$qrBuscaProdutos['COD_RESGATE'].",".$excManual.",".$qrBuscaProdutos['COD_ITEM'].")'><i class='fas fa-times text-danger' aria-hidden='true'></i></a></td>
															  <td>".$qrBuscaProdutos['COD_ITEM']."</td>
															  <td>".$qrBuscaProdutos['DES_PRODUTO']."</td>												
															  <td class='text-center'>".fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDecimais)."</td>
															  <td class='text-right'>".fnValor($qrBuscaProdutos['VAL_UNITARIO'],$casasDecimais)."</td>
															  <td class='text-right'>".fnValor($valorTotalProd,$casasDecimais)."</td>
															</tr>
															<input type='hidden' id='COD_PRODUTO' value='".$qrBuscaProdutos['COD_PRODUTO']."'>
															"; 
														  }											
														  //fnEscreve($valorTotalResg);
												?>
															
												</tbody>

												<script type="text/javascript">
													calcularTotal(<?=$valorTotalResg?>);
												</script>
												
												</table>
												
												<!-- <input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>"> -->	
