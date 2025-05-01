<?php 

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$nom_cliente = $_POST['NOM_CLIENTE'];
	$num_cartao = $_POST['NUM_CARTAO'];	
	$des_produto = fnLimpacampo($_POST['DES_PRODUTO']);
	$ean = fnLimpacampo($_POST['EAN']);

	// fnEscreve($des_produto);
	
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
	
	switch ($opcao) {   
		case 'paginar':

			if ($ean != "" ){
				$andEan = "AND A.EAN = '$ean'";
			}else { 
				$andEan = ' ';
			}

			if ($des_produto != "" ){
				$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
																			
				else { $andProduto = ' ';}
				
			if ($cod_externo  != "" && $cod_externo != 0){
				$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
				else { $andExterno = ' ';}
												
			$sql="select COUNT(*) as CONTADOR from PRODUTOCLIENTE A 
					left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
					left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
					where A.COD_EMPRESA='".$cod_empresa."' 
					".$andProduto."
					".$andExterno." 
					".$andEan." 
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
			
			//fnEscreve($sql);
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
														
		                                                        
			$sql="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
				LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
				LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				".$andProduto."
				".$andExterno." 
				".$andEan." 
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";
                                                                
			//fnEscreve($sql);
			//fnEscreve($cod_empresa);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

			//$teste = mysqli_num_rows($arrayQuery);
	
			//fnEscreve($teste);
			
			$count=0;
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;
				if ($qrListaProduto['LOG_PRODPBM'] == "N" || is_null($qrListaProduto['LOG_PRODPBM']) || $qrListaProduto['LOG_PRODPBM'] == "" ) {$mostraDown = "<a href='javascript: downForm($count)' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a>";}
					else {$mostraDown = "&nbsp;<i class='fa fa-times-circle' style='color:red;' aria-hidden='true' data-toggle='tooltip' data-placement='top' data-original-title='pbm'></i>";}
		?>

					<tr>
					  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm("<?=$count?>")'>&nbsp;
					  <?=$mostraDown?>
					  </td>
					  <td><?=$qrListaProduto['COD_PRODUTO']?></td>
					  <td><?=$qrListaProduto['EAN']?></td>
					  <td><?=$qrListaProduto['COD_EXTERNO']?></td>
					  <td><?=$qrListaProduto['GRUPO']?></td>
					  <td><?=$qrListaProduto['SUBGRUPO']?></td>
					  <td><?=$qrListaProduto['DES_PRODUTO']?></td>
					</tr>
					<input type='hidden' id='ret_COD_PRODUTO_<?=$count?>' value="<?=$qrListaProduto[COD_PRODUTO]?>">  
					<input type='hidden' id='ret_COD_EXTERNO_<?=$count?>' value="<?=$qrListaProduto[COD_EXTERNO]?>">
					<input type='hidden' id='ret_DES_PRODUTO_<?=$count?>' value="<?=$qrListaProduto[DES_PRODUTO]?>">
					<input type='hidden' id='ret_COD_CATEGOR_<?=$count?>' value="<?=$qrListaProduto[COD_CATEGOR]?>">
					<input type='hidden' id='ret_COD_SUBCATE_<?=$count?>' value="<?=$qrListaProduto[COD_SUBCATE]?>">

		<?php                                              
            }										

			break; 		
	}
?>