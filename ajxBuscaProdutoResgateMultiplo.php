<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	//fnEscreve('entrou no ajax');

	$opcao = $_GET['opcao'];
	$casasDec = $_GET['casasDec'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnLimpacampoZero($_POST['COD_EMPRESA']);

	$cod_externo = fnLimpacampo($_POST['COD_EXTERNO']);
	$des_produto = fnLimpacampo($_POST['DES_PRODUTO']);
	$cod_categor = fnLimpacampoZero($_POST['COD_CATEGOR']);
	$cod_subcate = fnLimpacampoZero($_POST['COD_SUBCATE']);
	$cod_univend = fnLimpacampoZero(fnDecode($_POST['COD_UNIVEND']));

	switch ($opcao) {
		case 'paginar':
			
			if ($des_produto != "" ){
				$andProduto = 'AND DES_PRODUTO like "%'.$des_produto.'%"'; }
	                                                                        
				else { $andProduto = ' ';}
				
			if ($cod_externo  != ""){
				$andExterno = 'AND COD_EXTERNO = "'.$cod_externo.'"'; }
				else { $andExterno = ' ';}
												
			if ($cod_categor  != ""){
				$andCategoria = 'AND COD_CATEGOR = "'.$cod_categor.'"'; }
				else { $andCategoria = ' ';}
				
			if ($cod_subcate  != ""){
				$andSubCategoria = 'AND COD_SUBCATE = "'.$cod_subcate.'"'; }
				else { $andSubCategoria = ' ';}	

			$sqlVerifica="SELECT COUNT(*) AS CONTROLA_ESTOQUE FROM ESTOQUE_PRODUTO
							WHERE COD_EMPRESA = $cod_empresa AND 
							IFNULL(cod_exclusa,0)=0  ";
							
			//fnEscreve($sqlVerifica);
			
			$arrayQuery2 = mysqli_query(connTemp($cod_empresa,""),$sqlVerifica);
			$qrVerifica = mysqli_fetch_assoc($arrayQuery2);
			
			$controla_estoque = $qrVerifica['CONTROLA_ESTOQUE'];

			if ( $controla_estoque > 0){
				//fnEscreve("controla estoque");				
				$txtControle = "controle de estoque";				
				$sqlEstoque="SELECT A.COD_PRODUTO, A.DES_PRODUTO, A.NUM_PONTOS from PRODUTOPROMOCAO A, ESTOQUE_PRODUTO B
								WHERE A.COD_EMPRESA = $cod_empresa
								AND A.COD_EMPRESA=B.COD_EMPRESA
								AND A.COD_PRODUTO=B.COD_PRODUTO
								AND B.QTD_ESTOQUE>0
								AND A.COD_EXCLUSA = 0 
								AND A.LOG_ATIVO = 'S'
								AND A.NUM_PONTOS != 0
								AND B.COD_UNIVEND = $cod_univend
								$andCategoria
								$andSubCategoria
								$andProduto
								$andExterno
								order BY A.NUM_PONTOS ";			

			}else{
				//fnEscreve("NÃO controla estoque");				
				$txtControle = "sem controle de estoque";				
				$sqlEstoque="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
								where COD_EMPRESA = $cod_empresa
								AND COD_EXCLUSA = 0 
								AND LOG_ATIVO = 'S'
								AND NUM_PONTOS != 0
								$andCategoria
								$andSubCategoria
								$andProduto
								$andExterno
								order by NUM_PONTOS ";			
				
			}
												
			// $sql="SELECT COD_PRODUTO from PRODUTOPROMOCAO
			// 	where COD_EMPRESA = $cod_empresa
			// 	AND NUM_PONTOS != 0
			// 	$andCategoria
			// 	$andSubCategoria
			// 	$andProduto
			// 	$andExterno 
			// 	AND COD_EXCLUSA=0";

			$total_itens_por_pagina = mysqli_num_rows(mysqli_query(connTemp($cod_empresa,''),$sqlEstoque));
			
			//calcula o número de páginas arredondando o resultado para cima
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina*$pagina)-$itens_por_pagina;


			if ( $controla_estoque > 0){
				//fnEscreve("controla estoque");				
				$txtControle = "controle de estoque";				
				$sqlEstoque="SELECT A.COD_PRODUTO, A.DES_PRODUTO, A.NUM_PONTOS from PRODUTOPROMOCAO A, ESTOQUE_PRODUTO B
								WHERE A.COD_EMPRESA = $cod_empresa
								AND A.COD_EMPRESA=B.COD_EMPRESA
								AND A.COD_PRODUTO=B.COD_PRODUTO
								AND B.QTD_ESTOQUE>0
								AND A.COD_EXCLUSA = 0 
								AND A.LOG_ATIVO = 'S'
								AND A.NUM_PONTOS != 0
								AND B.COD_UNIVEND = $cod_univend
								$andCategoria
								$andSubCategoria
								$andProduto
								$andExterno
								order BY A.NUM_PONTOS limit $inicio,$itens_por_pagina";			

			}else{
				//fnEscreve("NÃO controla estoque");				
				$txtControle = "sem controle de estoque";				
				$sqlEstoque="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
								where COD_EMPRESA = $cod_empresa
								AND COD_EXCLUSA = 0 
								AND LOG_ATIVO = 'S'
								AND NUM_PONTOS != 0
								$andCategoria
								$andSubCategoria
								$andProduto
								$andExterno
								order by NUM_PONTOS limit $inicio,$itens_por_pagina";			
				
			}
		                                                        
			// $sql1="SELECT COD_PRODUTO, DES_PRODUTO, NUM_PONTOS from PRODUTOPROMOCAO
			// where COD_EMPRESA = $cod_empresa
			// AND NUM_PONTOS != 0
			// $andCategoria
			// $andSubCategoria
			// $andProduto
			// $andExterno 
			// AND COD_EXCLUSA=0 order by NUM_PONTOS limit $inicio,$itens_por_pagina";
	                                                            
			//fnEscreve($sql);
			//fnEscreve($sql1);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlEstoque);
			
			$count=0;
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$count++;
				
				echo "
					<tr>
					  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'>&nbsp;
					  </th>
					  <td>".$qrListaProduto['COD_PRODUTO']."</td>
					  <td>".$qrListaProduto['DES_PRODUTO']."</td>
					  <td>".fnValor($qrListaProduto['NUM_PONTOS'],$casasDec)."</td>
					</tr>
					<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaProduto['COD_PRODUTO']."'>  
					<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaProduto['DES_PRODUTO']."'>
					<input type='hidden' id='ret_NUM_PONTOS_".$count."' value='".fnValor($qrListaProduto['NUM_PONTOS'],$casasDec)."'>
					";                                                
	        }

			break;
		
		default:
			# code...
			break;
	}