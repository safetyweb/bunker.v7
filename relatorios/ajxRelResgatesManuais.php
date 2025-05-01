<?php 

	include '../_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$mostraXml = $_GET['mostrarXML'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$num_cgcecpf = $_POST['NUM_CGCECPF'];
	$casasDec = $_POST['CASAS_DEC'];

	

	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}

	if($num_cgcecpf == ""){
		$andCpf = " ";
	}else {
		$andCpf = "cli.NUM_CGCECPF = $num_cgcecpf AND ";
	}
	
	if($cod_vendapdv == ""){
		$andVendaPDV = " ";
	}else {
		$andVendaPDV = "COD_PDV = '".$cod_vendapdv."' AND ";
	}
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$sql = "SELECT 
						cred.COD_CREDITO,
						cred.DAT_CADASTR,
						uni.NOM_FANTASI LOJA,
						cli.NOM_CLIENTE CLIENTE,
						cli.NUM_CGCECPF 'CPF/CNPJ',
						cred.COD_USUCADA,
						usu.NOM_USUARIO 'USUARIO CAD',
						cred.VAL_CREDITO,
						vdi.DES_COMENTA
					FROM creditosdebitos cred  
					INNER JOIN clientes cli  on cred.COD_CLIENTE=cli.COD_CLIENTE 
					LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=cred.COD_UNIVEND
					LEFT JOIN usuarios usu ON usu.COD_USUARIO=cred.COD_USUCADA
					LEFT join venda_info vdi ON vdi.COD_VENDA=cred.COD_CREDITO
					WHERE cred.TIP_CREDITO='D'
					AND DATE(cred.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
					AND cred.COD_EMPRESA=$cod_empresa
					AND cred.COD_UNIVEND
					AND cred.COD_VENDA=0
					$andCpf
					AND cred.COD_UNIVEND IN($lojasSelecionadas)
					ORDER BY COD_CREDITO DESC";
			
			//echo($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$arquivo = fopen($arquivoCaminho, 'w',0);
                
			while($headers=mysqli_fetch_field($arrayQuery)){
				$CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
	
			while ($row=mysqli_fetch_assoc($arrayQuery)){  	
				$row[VAL_CREDITO] = fnValor($row['VAL_CREDITO'],2);
				//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
				//$textolimpo = json_decode($limpandostring, true);
				$array = array_map("utf8_decode", $row);
				fputcsv($arquivo, $array, ';', '"', '\n');	
			}
			fclose($arquivo);

			break;     
		case 'paginar':

			$pref = "";

			if($casasDec != 0){
				$pref = "R$";
			}
					
			$sql = "SELECT count(*) as contador from CREDITOSDEBITOS cred
					left join CLIENTES cli on cred.COD_CLIENTE=cli.COD_CLIENTE 
					LEFT JOIN WEBTOOLS.usuarios usu ON cred.COD_USUCADA=usu.COD_USUARIO 
					WHERE cred.TIP_CREDITO='D'
					AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
					AND cred.COD_EMPRESA=$cod_empresa
					$andCpf
					AND cred.COD_UNIVEND IN($lojasSelecionadas)";
					  
			// fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
			$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);										
				
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
			
					
			$sql2 = "SELECT 
						cred.COD_CREDITO,
						cred.DAT_CADASTR,
						uni.NOM_FANTASI,
						cli.NOM_CLIENTE,
						cli.NUM_CGCECPF,
						cred.COD_USUCADA,
						usu.NOM_USUARIO,
						cred.VAL_CREDITO,
						vdi.DES_COMENTA
					FROM creditosdebitos cred  
					INNER JOIN clientes cli  on cred.COD_CLIENTE=cli.COD_CLIENTE 
					LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=cred.COD_UNIVEND
					LEFT JOIN usuarios usu ON usu.COD_USUARIO=cred.COD_USUCADA
					LEFT join venda_info vdi ON vdi.COD_VENDA=cred.COD_CREDITO
					WHERE cred.TIP_CREDITO='D'
					AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(cred.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
					AND cred.COD_EMPRESA=$cod_empresa
					$andCpf
					AND cred.COD_UNIVEND IN($lojasSelecionadas)
					ORDER BY COD_CREDITO DESC
					limit $inicio,$itens_por_pagina
					";
						 
				// fnEscreve($sql2);	
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2);
				
				$countLinha = 1;
				while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)){
					
					?>
						<tr>
						  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
						  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
						  <td><small><?php echo fnMascaraCampo($qrListaVendas['NOM_CLIENTE']); ?></small></td>
						  <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
						  <td><small><?php echo $qrListaVendas['COD_USUCADA']; ?></small></td>
						  <td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
						  <td><small><?=$pref?> <?php echo fnValor($qrListaVendas['VAL_CREDITO'],$casasDec); ?></small></td>
						  <td><small><?php echo $qrListaVendas['DES_COMENTA']; ?></small></td>
						</tr>
					<?php
				  
				  $countLinha++;	
				  }											

			break;  		
	}
?>