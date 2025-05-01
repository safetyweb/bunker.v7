<?php include '../_system/_functionsMain.php'; 

	echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$tipoVenda = $_POST['tipoVenda'];
	$lojasSelecionadas = $_POST['LOJAS'];
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	


switch ($opcao) {
	case 'paginar':
	
		$totalVenda = 0;
	
		if ($tipoVenda == "T"){
			$andCreditos = " "; 
		}else{
			$andCreditos = "AND B.NUM_CARTAO != 0 "; 
		}

		$sql = "
					
SELECT count(*)
 as contador 
FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)  
INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
WHERE 
DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
					AND A.COD_EMPRESA = $cod_empresa
					$andCreditos														
					AND A.COD_UNIVEND IN($lojasSelecionadas)														
				";
				  
		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

		$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);
		
		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
		
		
		$sql = "  SELECT  A.COD_VENDA, 
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
				   F.DES_TOKEM, G.NOM_ENTIDAD 
				   
				FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)  
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
				  AND A.COD_UNIVEND IN($lojasSelecionadas)
				  AND A.COD_STATUSCRED in (1,2,3,4,5,7,8) 
				  $andCreditos
				  order by  A.DAT_CADASTR desc  limit $inicio,$itens_por_pagina 												  
				  ";
		
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
			//fnEscreve($sqlToken);
			/*
			echo "<pre>";
			print_r($queryToken);
			echo "</pre>";
			*/
			
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
				
																	if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente'] ){						//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
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
																	//$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
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
				  <td><?php echo $qrListaVendas['COD_VENDA']; ?> </td>
				  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
				  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
				  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
				  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
				  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
				  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
				  <!--
				  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITOS'],2); ?></small></td>
				  <td><small><?php echo fnDataFull($qrListaVendas['DAT_EXPIRA']); ?></small></td>
				  -->
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
}
?>