<?php include '../_system/_functionsMain.php'; 

	echo fnDebug('true');
	//teste

	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);	
	$COD_CPF = $_POST['COD_CPF'];	
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	
	
	
?>

	<div class="row">
		<div class="col-md-12" id="div_Produtos">

			<div class="push20"></div>
			
			<table class="table table-bordered table-hover">
			
			  <thead>
				<tr>
				  <th><small>Cliente</small></th>
				  <th><small>Cartão</small></th>
				  <th><small>Loja</small></th>
				  <th><small>Data/Hora</small></th>
				  <th><small>Sexo</small></th>
				  <th><small>Dt.Nascimento</small></th>
				  <th><small>Operador</small></th>
				  <th><small>Id.Maquina</small></th>
				  <th><small>Tempo</small></th>
				</tr>
			  </thead>
				
				<?php
				
				   //filtro por cpf 
				   if($COD_CPF !='')
					{$cpfand=" and CPF='".fnLimpaDoc($COD_CPF)."'";
					 $cpfwhere=" where CPF='".fnLimpaDoc($COD_CPF)."'";
					 $filtrodata='';
					}else{
						$cpfand='';
						$cpfwhere='';
						$filtrodata=" and DATE_FORMAT(log_cpf.DATA_HORA, '%Y-%m-%d') >= '2017-12-01' AND DATE_FORMAT(log_cpf.DATA_HORA, '%Y-%m-%d') <= '2017-12-31'";    
							
					}
					//=========================================================== 
					$sql = "select count(*) as contador from log_cpf $cpfwhere";
							  
					//fnEscreve($sql);

					$retorno = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
					$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

					$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);
					
					//fnEscreve($totalitens_por_pagina['contador']);
					
					//variavel para calcular o início da visualização com base na página atual
					$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
					
					$sql = " select * from log_cpf
																		inner join unidadevenda on log_cpf.ID_LOJA=unidadevenda.COD_UNIVEND
																		where  log_cpf.COD_EMPRESA=$cod_empresa 
																		$cpfand
																		$filtrodata    
																		order by ID desc limit $inicio,$itens_por_pagina";
					
					//fnEscreve($sql);	
					
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
					
					$countLinha = 1;
					while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
					  {
						
						?>
							<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
							  <td><?php echo $qrListaVendas['NOME']; ?></a></td>
							  <td class="text-right"><small><?php echo $qrListaVendas['CPF']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
							  <td><small><?php echo fnDataFull($qrListaVendas['DATA_HORA']); ?></small></td>
							  <td class="text-right"><small><?php echo  $qrListaVendas['SEXO']; ?></small></td>
							  <td class="text-right"><small> <?php echo $qrListaVendas['DT_NASCIMENTO']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['USUARIO']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['ID_MAQUINA']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['Time_consulta']; ?></small></td>
							</tr>
						<?php
					  
					  
					  $countLinha++;	
					  }			

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