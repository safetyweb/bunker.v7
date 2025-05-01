
<?php 

	include './_system/_functionsMain.php';	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];	
	$cod_empresa = $_POST['COD_EMPRESA'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);

	$itens_por_pagina = $_GET['itens_por_pagina'];
			
	$cod_univend = fnDecode($_GET['cod_univend']);
	$cod_cliente = $_POST['COD_CLIENTE'];
	$tipoVenda = "C";

	$lojasSelecionadas = $_POST['LOJAS_SELECIONADAS'];
	
	switch ($opcao) { 
	
		case 'tokens':
		
				if($cod_univend  == 9999){ $ANDcodUnivend = " AND A.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND A.COD_UNIVEND IN($cod_univend) "; }

				if ($tipoVenda == "T"){
					$andCreditos = " "; 
				}else{
					$andCreditos = "AND B.NUM_CARTAO != 0 "; 
				}
						  
				$sql = "SELECT 
						 A.COD_VENDA,														
						 A.COD_VENDAPDV,														
						 A.COD_MAQUINA,														
						 A.COD_VENDEDOR,														
						 A.COD_CUPOM,														
						 B.COD_CLIENTE,
						 B.NOM_CLIENTE,
						 B.NUM_CARTAO,
						  F.cod_cliente, 
						 D.NOM_FANTASI,
						 A.DAT_CADASTR,
						 A.VAL_TOTVENDA,                                                   
						 C.NOM_USUARIO AS VENDEDOR,
						 E.NOM_USUARIO AS OPERADOR,
						F.DES_TOKEM,
						I.DES_PARAM2,
                                                I.DES_PARAM1,
						G.NOM_ENTIDAD
						 FROM VENDAS A 
						 INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
						  LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR 
						  LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND 
						  LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA 
						  left JOIN tokem F ON F.COD_PDV = A.COD_VENDAPDV  
						  LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 
						  inner join  itemvenda I on I.DES_PARAM2=F.des_tokem
						 WHERE 
						   A.DAT_CADASTR between '$dat_ini 00:00' AND '$dat_fim 23:59:59' 
							 AND A.COD_EMPRESA = 19 
							 AND A.COD_UNIVEND IN($lojasSelecionadas) 
							 AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8) 
							 AND A.COD_CLIENTE != 58272 
						AND F.DES_TOKEM is not null 
						AND B.NUM_CARTAO != 0 
						order by A.DAT_CADASTR desc limit $itens_por_pagina,5 ";
				
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$countLinha = 1;
				while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
				  {
					if ($countLinha == 1){
						$vendaIni = $qrListaVendas['DAT_CADASTR'];													
					}
					
					$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];

							$temToken = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
							$statusToken = "Token válido";
					
																	
					if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
						$temToken = ""; }
					
					if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
						$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
						$statusToken = "Cliente não cadastrado"; }
						
					?>
						<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
						  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
						  <td class="text-center"><small><?php echo $statusToken; ?></small></td>
						  <td><small><?php echo $qrListaVendas['DES_TOKEM']; ?> </small></td>
						  <td class="text-center"><small><?php echo $temToken; ?></small></td>
						  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
						  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
						  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
						  <td><small><?php echo $qrListaVendas['DES_PARAM1']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
						</tr>
					<?php
					
				  $vendaFim = $qrListaVendas['DAT_CADASTR'];
				  $countLinha++;	
				  }			

		break; 
	
		case 'tokensNOK':
						
					if($cod_univend  == 9999){ $ANDcodUnivend = " AND a.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND a.COD_UNIVEND IN($cod_univend) "; }
										
					$sql = "SELECT
							A.COD_VENDA,														
							A.COD_VENDAPDV,														
							A.COD_MAQUINA,														
							A.COD_VENDEDOR,														
							A.COD_CUPOM,														
							B.COD_CLIENTE,
							B.NOM_CLIENTE,
							B.NUM_CARTAO,
							D.NOM_FANTASI,
							A.DAT_CADASTR,
							A.VAL_TOTVENDA,                                                   
							C.NOM_USUARIO AS VENDEDOR,
							E.NOM_USUARIO AS OPERADOR,
							F.DES_TOKEM,
							G.NOM_ENTIDAD
							FROM VENDAS A
							INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
							LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR
							LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
							LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA	
							LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv 	
							LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 															
							WHERE                                                 
							  A.DAT_CADASTR between '$dat_ini 00:00' AND '$dat_fim 23:59:59' 
							  AND A.COD_EMPRESA = $cod_empresa
							 $ANDcodUnivend
							  AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8)
							  AND A.COD_CLIENTE != 58272 													  
							  order by  A.DAT_CADASTR desc limit $itens_por_pagina, 5 ";
					
					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
					
					$countLinha = 1;
					while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
					  {
						if ($countLinha == 1){
							$vendaIni = $qrListaVendas['DAT_CADASTR'];													
						}
						
						$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];
	
						$sqlToken="select 
									itemvenda.COD_VENDA,								
									itemvenda.DES_PARAM1,
									itemvenda.DES_PARAM2,
									tokem.des_tokem,
									tokem.COD_PDV,
									tokem.cod_cliente,
									max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
									from itemvenda 
									left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
									where 
									cod_venda='".$qrListaVendas['COD_VENDA']."' limit 1 ";
								
						$tokenExec=mysqli_query(connTemp($cod_empresa,''),$sqlToken);
						$queryToken=mysqli_fetch_assoc($tokenExec);
						
						$colunaEspecial = $queryToken['DES_PARAM2'];
						if($queryToken['temToken']=='S')
						{
							if ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
								$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
								$statusToken = "Token já utilizado";
								
							}else {
								$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
								$statusToken = "Token inválido";
								}

							if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente'] ){																																												//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
									$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
									$statusToken = "Token pertence a outro usuario";
							}
							
						}elseif (!empty($qrListaVendas['NUM_CARTAO']) &&
									($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])) {
										$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
										$statusToken = "Token inexistente";
						}else {
							$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
							
									if (!empty($queryToken['DES_PARAM1'])){
									$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
									$statusToken = "Token não informado";
									} else {$statusToken = "";}
								}
						
																		
						if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
							$temToken = ""; }
						
						if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
							$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
							$statusToken = "Cliente não cadastrado"; }
							
						?>
							<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
							  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
							  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
							  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
							  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
							  <td><small><?php echo $queryToken['DES_PARAM1']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small></td>
							  <td><small><?php echo $queryToken['DES_PARAM2']; ?> </small></td>
							  <td class="text-center"><small><?php echo $temToken; ?></small></td>
							  <td class="text-center"><small><?php echo $statusToken; ?></small></td>
							</tr>
						<?php
						
					  $vendaFim = $qrListaVendas['DAT_CADASTR'];
					  $countLinha++;	
					  }		
		break; 

		case 'overview':

			if($cod_univend  == 9999){ $ANDcodUnivend = " AND uni.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND uni.COD_UNIVEND IN($cod_univend) "; }

					//busca resgates - loop															
					$sql = "SELECT 
							uni.COD_UNIVEND, 
							uni.NOM_FANTASI, 
							Sum(Case When ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 Else 0 end) as VENDA_TOTAL,
						  
							(0) TOTAL_CLIENTE,
					   
							count(distinct case when ven.COD_UNIVEND = uni.COD_UNIVEND and cli.LOG_AVULSO='N'  Then  cli.COD_CLIENTE  else 0 end) as CLIENTES_COMPRA,          
						
							sum(case when cli.LOG_AVULSO = 'S' and ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 else 0 end) as AVULSO
																					
					  
						from webtools.unidadevenda uni
						Inner join vendas ven
								on ven.COD_EMPRESA = uni.COD_EMPRESA
							   and ven.COD_UNIVEND = uni.COD_UNIVEND
							   $ANDcodUnivend											   
							   and ven.DAT_CADASTR_WS >= '$dat_ini  00:00' 
							   and ven.DAT_CADASTR_WS <= '$dat_fim  23:59'        
							   AND ven.DAT_CADASTR < NOW()
						Inner join clientes cli 
								on cli.COD_CLIENTE = ven.COD_CLIENTE 
						where uni.COD_EMPRESA = $cod_empresa
						 
						group by uni.cod_univend 

						order by uni.NOM_UNIVEND limit $itens_por_pagina,5; ";
					
					//fnEscreve($sql);	
					
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
					//$arrayQuery = fnTestesql($connAdm->connAdm($cod_empresa,''), $sql);
					
					while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery))
					  {	
						$nom_univend = $qrBuscaDados['NOM_FANTASI'];
						$venda_total = $qrBuscaDados['VENDA_TOTAL'];
						$clientes_compra = $qrBuscaDados['CLIENTES_COMPRA'];
						//$total_cliente = $qrBuscaDados['TOTAL_CLIENTE'];
						$clientes = $qrBuscaDados['CLIENTES'];
						$avulso = $qrBuscaDados['AVULSO'];
						$clientes_outras = $qrBuscaDados['CLIENTES_OUTRAS'];
						
						$masculino = $qrBuscaDados['MASCULINO'];
						$feminino = $qrBuscaDados['FEMININO'];
						$indefinido = $qrBuscaDados['INDEFINIDO'];
						$total_cliente = $masculino + $feminino + $indefinido;
						
						$totalVenda = $totalVenda + $venda_total;
						$totalFidelizado = $totalFidelizado + ($venda_total-$avulso);
						$totalAvulso = $totalAvulso + $avulso;
						$totalCliCompra = $totalCliCompra + $clientes_outras;
						$totalCliente = $totalCliente + $total_cliente;
						$totalMasculino = $totalMasculino + $masculino;
						$totalFeminino = $totalFeminino + $feminino;
						$totalIndefinido = $totalIndefinido + $indefinido;
						?>
						
						<tr>
						  <td><?php echo $nom_univend; ?></td>
						  <td class="text-right"><b class="f14 text-info"><?php echo fnValor($venda_total,0); ?></b></td>
						  <td class="text-right"><b class="f14 text-info"><?php echo fnValor(($venda_total-$avulso),0); ?></b></td>
						  <td class="text-right"><b class="f14 text-info"><?php echo fnValor($avulso,0); ?></b></td>
						</tr>

	<?php }

		break;


		case 'vendas':

			if($cod_univend  == 9999){ $ANDcodUnivend = " AND a.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND a.COD_UNIVEND IN($cod_univend) "; }
									
					$sql = "SELECT
							A.COD_VENDA,														
							A.COD_VENDAPDV,														
							A.COD_MAQUINA,														
							A.COD_VENDEDOR,														
							A.COD_CUPOM,														
							B.COD_CLIENTE,
							B.NOM_CLIENTE,
							B.NUM_CARTAO,
							D.NOM_FANTASI,
							A.DAT_CADASTR,
							A.VAL_TOTVENDA,                                                   
							C.NOM_USUARIO AS VENDEDOR,
							E.NOM_USUARIO AS OPERADOR,
							F.DES_TOKEM,
							G.NOM_ENTIDAD
							FROM VENDAS A
							INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
							LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR
							LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
							LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA	
							LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv 	
							LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 															
							WHERE                                                 
							  DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
							  AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
							  AND A.COD_EMPRESA = $cod_empresa
							  $ANDcodUnivend
							  AND A.COD_STATUSCRED in (0,1,2,3,4,5,7,8) 
							  order by  A.DAT_CADASTR desc  limit $itens_por_pagina,10";
					
					
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
					
					$countLinha = 1;
					while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
					  {
						if ($countLinha == 1){
							$vendaIni = $qrListaVendas['DAT_CADASTR'];													
						}
						
						$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];

						$sqlToken="select 
									itemvenda.COD_VENDA,								
									itemvenda.DES_PARAM1,
									itemvenda.DES_PARAM2,
									tokem.des_tokem,
									tokem.COD_PDV,
									tokem.cod_cliente,
									max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
									from itemvenda 
									left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
									where 
									cod_venda='".$qrListaVendas['COD_VENDA']."' limit 1 ";
								
						$tokenExec=mysqli_query(connTemp($cod_empresa,''),$sqlToken);
						$queryToken=mysqli_fetch_assoc($tokenExec);
						
						$colunaEspecial = $queryToken['DES_PARAM2'];
						if($queryToken['temToken']=='S')
						{
							if($qrListaVendas['COD_VENDAPDV'] == $queryToken['COD_PDV']){
								$temToken = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
								$statusToken = "Token válido";
							}elseif ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
								$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
								$statusToken = "Token já utilizado";
								
							}else {
								$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
								$statusToken = "Token inválido";
								}

							if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente'] ){																																												//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
									$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
									$statusToken = "Token pertence a outro usuario";
							}
							
						}elseif (!empty($qrListaVendas['NUM_CARTAO']) &&
									($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])) {
										$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
										$statusToken = "Token inexistente";
						}else {
							$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
							
									if (!empty($queryToken['DES_PARAM1'])){
									$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
									$statusToken = "Token não informado";
									} else {$statusToken = "";}
								}
						
																		
						if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
							$temToken = ""; }
						
						if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
							$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
							$statusToken = "Cliente não cadastrado"; }
							
						?>
							<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
							  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
							  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
							  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
							  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
							  <td><small><?php echo $queryToken['DES_PARAM1']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
							  <td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small></td>
							  <td><small><?php echo $queryToken['DES_PARAM2']; ?> </small></td>
							  <td class="text-center"><small><?php echo $temToken; ?></small></td>
							  <td class="text-center"><small><?php echo $statusToken; ?></small></td>
							</tr>
						<?php
						
					  $vendaFim = $qrListaVendas['DAT_CADASTR'];
					  $countLinha++;	
					  }

		break;


		case 'clientes':

			if($cod_univend  == 9999){ $ANDcodUnivend = " AND b.COD_UNIVEND IN($lojasSelecionadas) "; } else { $ANDcodUnivend = " AND b.COD_UNIVEND IN($cod_univend) "; }
																				   
				$sql = "SELECT DISTINCT A.COD_CLIENTE, 
						  A.NUM_CARTAO, 
						  A.NOM_CLIENTE, 
						  C.NOM_ENTIDAD,
						  A.DES_EMAILUS, 
						  CASE 
						   WHEN A.COD_SEXOPES IS NULL THEN
							'I'
						   WHEN A.COD_SEXOPES=0 THEN
							'I'
						   WHEN A.COD_SEXOPES=1 THEN
							'M'
						   WHEN A.COD_SEXOPES=2 THEN
							'F'
						   WHEN A.COD_SEXOPES=3 THEN
							'I'
						  END SEXO, 
						  SUM(VAL_TOTVENDA) as VAL_TOTVENDA 

						FROM CLIENTES A, VENDAS B, ENTIDADE C
						WHERE A.COD_CLIENTE = B.COD_CLIENTE AND 
						   A.COD_ENTIDAD=C.COD_ENTIDAD AND 
						B.COD_EMPRESA = $cod_empresa AND
						B.DAT_CADASTR_WS between '$dat_ini 00:00' AND '$dat_fim 23:59' AND																		
						A.LOG_AVULSO = 'N'
						$ANDcodUnivend 
						GROUP BY COD_CLIENTE
						ORDER BY  SUM(VAL_TOTVENDA) DESC
						LIMIT $itens_por_pagina, 10 ";
						
				//fnEscreve($sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;																																		
					switch ($qrListaPersonas['SEXO']) {
						case "I": //indefinido
							$mostraSexo = '<i class="fa fa-venus-mars f12" aria-hidden="true"></i>';
							break;    
						case "M": //masculino
							$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
							break;	
						case "F": //feminino
							$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
							break;
					}	
												
					echo"
						<tr>
						  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
						  <td><small>".$qrListaPersonas['NOM_ENTIDAD']."</small></td>
						  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
						  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
						  <td class='text-center'>".$mostraSexo."</td>
						  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
						  <td class='text-center'><small>".fnvalor($qrListaPersonas['VAL_TOTVENDA'],2)."</small></td>
						</tr>
						"; 
					  }	

		break;
	}
?>