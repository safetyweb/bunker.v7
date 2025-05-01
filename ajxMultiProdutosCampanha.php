<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_externo = fnLimpacampo($_POST['COD_EXTERNO']);
	$des_produto = fnLimpacampo($_POST['DES_PRODUTO']);

	//fnEscreve($des_produto);
	//fnEscreve($cod_externo);
	
	switch ($opcao) {

		case 'paginar':

			if ($des_produto != "" ){
				$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
                                                                            
				else { $andProduto = ' ';}
				
			if ($cod_externo  != "" ){
				$andExterno = "AND A.COD_EXTERNO = '$cod_externo' "; }
				else { $andExterno = ' ';}
												
			$sql="SELECT COUNT(*) as contador from PRODUTOCLIENTE A 
					left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
					left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
					where A.COD_EMPRESA='".$cod_empresa."' 
					".$andProduto."
					".$andExterno." 
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
			$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);										
				
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
		                                                        
			$sql1="SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
				LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
				LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				".$andProduto."
				".$andExterno." 
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";
                                                                
			//fnEscreve($sql1);
			//fnEscreve($cod_empresa);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
			
			$count=0;
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;
				echo "
					<tr>
					  <td class='text-center'><input type='checkbox' name='radio_$count'>&nbsp;</td>
					  <td>".$qrListaProduto['COD_PRODUTO']."</td>
					  <td>".$qrListaProduto['COD_EXTERNO']."</td>
					  <td>".$qrListaProduto['GRUPO']."</td>
					  <td>".$qrListaProduto['SUBGRUPO']."</td>
					  <td>".$qrListaProduto['DES_PRODUTO']."</td>
					</tr>
					<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
					<input type='hidden' id='ret_COD_EXTERNO_".$count."' value='".$qrListaProduto['COD_EXTERNO']."'>
					<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
					<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaProduto['COD_CATEGOR']."'>
					<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaProduto['COD_SUBCATE']."'>
					";                                                
            }											

			break;  		
	}
?>