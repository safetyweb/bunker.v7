<?php include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);	
?>

<div class="row">
	<div class="col-md-12" id="div_Produtos">

		<div class="push20"></div>
		
		<table class="table table-bordered table-hover">
		
		  <thead>
			<tr>
			  <th><small>Nome</small></th>
			  <th><small>CPF</small></th>
			  <th><small>Cartão</small></th>
			  <th><small>Sexo</small></th>
			  <th><small>Data Nascimento</small></th>
			  <th><small>Celular</small></th>
			  <th><small>e-Mail</small></th>
			  <th><small>Data/Hora</small></th>
			  <th><small>Integrado Procfit</small></th>
			  
			</tr>
		  </thead>
			
			<?php
			
				$numPaginas = ceil(500/$itens_por_pagina);
				
				//variavel para calcular o início da visualização com base na página atual
				$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;										

				$hostname = "173.212.201.183";
				$dbname = "INTEGRACAO_CLUBE_SO";
				$username = "Marka_so";
				$pw = "@icbso#$%*";
				$con = mssql_connect ($hostname, $username, $pw);
				mssql_select_db ($dbname, $con);
				$mssql= "select top 500 * from dbo.CLIENTES_CLUBE_SO order by DATA_HORA desc limit $inicio,$itens_por_pagina";
				$rs= mssql_query ($mssql,$con) or DIE("Table unavailable");
				$rssql=mssql_fetch_assoc($rs);       											
														
				$countLinha = 1;
				while ($rssql=mssql_fetch_assoc($rs))
				  {
					  
					 if ($rssql['SEXO'] == "M"){		
							$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
						}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
					  
					 if ($rssql['LIDO_PROCFIT'] == "S"){		
							$mostraLido = '<i class="fa fa-check text-success" aria-hidden="true"></i>';	
						}else{ $mostraLido = '<i class="fa fa-times text-danger" aria-hidden="true"></i>'; }	
					  
					?>
						<tr style="background-color: #fff;">
						  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $rssql['NOME']; ?></a></td>
						  <td><?php echo $rssql['CPF']; ?></td>
						  <td><small><?php echo $rssql['NUMERO_CARTAO']; ?></small></td>
						  <td class="text-center"><small><?php echo $mostraSexo; ?></small></td>
						  <td><small><?php echo fnDataShort($rssql['DATA_NASCIMENTO']); ?></small></td>
						  <td><small><?php echo $rssql['CELULAR']; ?></small></td>
						  <td><small><?php echo $rssql['EMAIL']; ?></small></td>
						  <td><small><?php echo fnDataFull($rssql['DATA_HORA']); ?></small></td>
						  <td class="text-center"><small><?php echo $mostraLido; ?></small></td>
						
						</tr>
					<?php
					
				  
				  $vendaFim = $qrListaVendas['DAT_CADASTR'];
				  $countLinha++;	
				  }			
				  mssql_close ($con);
			?>	
		
			</tbody>

			<tfoot>
				<tr>
				  <th class="" colspan="100"><ul class="pagination pagination-sm">
				  <?php
					for($i = 1; $i < $numPaginas + 1; $i++) {
						if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
					echo "<li class='pagination $paginaAtiva'><a href='#' onclick='reloadPage($i);' style='text-decoration: none;'>".$i."</a></li>";   
					}													  
				  ?></ul>
				  </th>
				</tr>
			</tfoot>
			
		</table>
														
	</div>
	
	<?php
	
	function fullDateDiff($date1, $date2)
	{		
		$date1=strtotime($date1);
		$date2=strtotime($date2); 
		$diff = abs($date1 - $date2);
		
		$day = $diff/(60*60*24); // in day
		$dayFix = floor($day);
		$dayPen = $day - $dayFix;
		if($dayPen > 0)
		{
			$hour = $dayPen*(24); // in hour (1 day = 24 hour)
			$hourFix = floor($hour);
			$hourPen = $hour - $hourFix;
			if($hourPen > 0)
			{
				$min = $hourPen*(60); // in hour (1 hour = 60 min)
				$minFix = floor($min);
				$minPen = $min - $minFix;
				if($minPen > 0)
				{
					$sec = $minPen*(60); // in sec (1 min = 60 sec)
					$secFix = floor($sec);
				}
			}
		}
		$str = "";
		if($dayFix > 0)
			$str.= $dayFix."d ";
		if($hourFix > 0)
			$str.= $hourFix."h ";
		if($minFix > 0)
			$str.= $minFix."m ";
		if($secFix > 0)
			$str.= $secFix."s ";
		return $str;
	}
									
		//fnEscreve($vendaIni);
		//fnEscreve(fnDataFull($vendaIni));
		//fnEscreve(fnFormatDateTime($vendaIni));
		//fnEscreve($vendaFim);
		//fnEscreve(fnDataFull($vendaFim));
		//fnEscreve(fullDateDiff($vendaIni, $vendaFim));
		//fnEscreve(fnValor($totalVenda,2));
		//fnEscreve(fnValor($totalVenda,2));
		
		//$to_time = strtotime("2008-12-13 10:42:00");
		//$from_time = strtotime("2008-12-13 10:21:00");
		//fnEscreve(round(abs($vendaFim - $vendaini) / 60,2). " minute");									
		
									
	?>
	
	
</div>