<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$cod_empresa = fnLimpaCampoZero($_GET['cod_empresa']);

switch ($_GET['acao']) {
	case 'addProdutos':
	
		$array = json_decode( $_POST['listaProdutos'], true );

		foreach($array as $item) { 
			$sql = "CALL SP_ALTERA_PERSONAS_PRODUTOS (
			 '".$item['COD_PERPROD']."', 
			 '".$item['COD_PRODUTO']."',				 
			 '".$item['COD_FORNECEDOR']."', 
			 '".$item['COD_CATEGOR']."', 
			 '".$item['COD_SUBCATE']."', 
			 '".$item['COD_PERSONA']."', 
			 '".$item['COD_EMPRESA']."', 
			 '".$item['OPCAO']."'
			) ";
			
			fnEscreve($sql);
			//fnEscreve($cod_empresa);
			mysqli_query(connTemp($cod_empresa,''),$sql);
		}

		break;

	case 'excProdutos':
	
		$array = json_decode( $_POST['listaProdutos'], true );

		foreach($array as $item => $valor) {
			$sql .= "DELETE FROM PERSONAS_PRODUTOS WHERE COD_PERPROD = $valor AND COD_EMPRESA =".$cod_empresa.";";
		}

		mysqli_multi_query(connTemp($cod_empresa,''),$sql);

	break;

	case 'proc':
	
		$sql = "CALL SP_ALTERA_PERSONAS_PRODUTOS (
		 '".$_GET['COD_PERPROD']."', 
		 '".$_GET['COD_PRODUTO']."',				 
		 '".$_GET['BL2_COD_FORNECEDOR']."', 
		 '".$_GET['BL2_COD_CATEGOR']."', 
		 '".(empty($_GET['BL2_COD_SUBCATE']) ? 0 : $_GET['BL2_COD_SUBCATE'])."', 
		 '".$_GET['COD_PERSONA']."', 
		 '".$cod_empresa."', 
		 '".$_GET['opcao']."'
		) ";
		
		mysqli_query(connTemp($cod_empresa,''),$sql);

		//fnEscreve($sql);
		//fnMostraForm(true);

		break;     
	case 'consulta':
	
		$cod_persona = $_GET['cod_persona'];
	
		$sql = "SELECT 
				personas_produtos.COD_PERPROD,
				personas_produtos.COD_PRODUTO,
				personas_produtos.COD_FORNECEDOR,
				personas_produtos.COD_CATEGOR,
				personas_produtos.COD_SUBCATE,
				personas_produtos.DES_CHAVE,

				(SELECT DES_PRODUTO
				 FROM produtocliente
				WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as DES_PRODUTO,
				
				(SELECT COD_EXTERNO
				 FROM produtocliente
				WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as COD_EXTERNO,
				
				(SELECT NOM_FORNECEDOR
				 FROM fornecedormrka
				WHERE fornecedormrka.COD_FORNECEDOR = personas_produtos.COD_FORNECEDOR) as NOM_FORNECEDOR,
				 
				(SELECT DES_CATEGOR
				 FROM categoria
				WHERE categoria.COD_CATEGOR = personas_produtos.COD_CATEGOR) as DES_CATEGOR,  	 
				 
				(SELECT DES_SUBCATE
				 FROM subcategoria
				WHERE subcategoria.COD_SUBCATE = personas_produtos.COD_SUBCATE) as DES_SUBCATE     

			FROM personas_produtos where COD_PERSONA = $cod_persona ";
				
		//fnEscreve($sql);
		//fnEscreve($cod_busca);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															
		$count=0;
		while ($qrListaPersonasProdutos = mysqli_fetch_assoc($arrayQuery))
		  {														  
			$count++;	
			echo"
				<tr>
				  <td class='text-center'><input type='checkbox' name='radio_$count' onclick='retornaFormPersonas(".$count.")'>&nbsp;</td>
				  <td><small>".$qrListaPersonasProdutos['COD_PERPROD']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['COD_EXTERNO']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['DES_PRODUTO']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['NOM_FORNECEDOR']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['DES_CATEGOR']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['DES_SUBCATE']."</small></td>
				  <td><small>".$qrListaPersonasProdutos['DES_CHAVE']."</small></td>
				</tr>
				<input type='hidden' id='ret_COD_PERPROD_".$count."' value='".$qrListaPersonasProdutos['COD_PERPROD']."'>
				<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaPersonasProdutos['COD_PRODUTO']."'>
				<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaPersonasProdutos['DES_PRODUTO']."'>
				<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrListaPersonasProdutos['COD_FORNECEDOR']."'>
				<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaPersonasProdutos['COD_CATEGOR']."'>
				<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaPersonasProdutos['COD_SUBCATE']."'>
				<input type='hidden' id='ret_DES_CHAVE_".$count."' value='".$qrListaPersonasProdutos['DES_CHAVE']."'>
				"; 
			  }

		break;

		default:

			$cod_empresa = fnLimpacampoZero(fnDecode($_GET['id']));

			$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
			$cod_categor = fnLimpacampoZero($_REQUEST['COD_CATEGOR']);
			$cod_subcate = fnLimpacampoZero($_REQUEST['COD_SUBCATE']);
			$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
			$cod_laborat = fnLimpacampo($_REQUEST['COD_LABORAT']);
			$des_produto = fnLimpacampo($_REQUEST['DES_PRODUTO']);

			// ROTINA DE ATRIBUTOS ADICIONADA 24/01/2022 - Ricardo

			for ($i=1; $i<=13 ; $i++) { 
				$ATRIBUTO = "ATRIBUTO{$i}";
     			$$ATRIBUTO = $_REQUEST["AJX_ATRIBUTO".$i];
     			// fnEscreve($$ATRIBUTO);
			}

			$itens_por_pagina = $_GET['itens_por_pagina'];	
			$pagina = $_GET['idPage'];

			if ($des_produto != "" ){
				$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
                                                                            
				else { $andProduto = ' ';}
				
			if ($cod_produto  != "" ){
				$andCod = "AND A.COD_PRODUTO = '$cod_produto' "; }
				else { $andCod = ' ';}

			if ($cod_externo  != "" ){
				$andExterno = "AND A.COD_EXTERNO = '$cod_externo' "; }
				else { $andExterno = ' ';}

			// ROTINA DE ATRIBUTOS ADICIONADA 24/01/2022 - Ricardo

			$andAtributos = "";

			for ($i=1; $i<=13 ; $i++) { 
				$ATRIBUTO = "ATRIBUTO{$i}";
     			if($$ATRIBUTO != ""){
     				$andAtributos .= " AND $ATRIBUTO = ".$$ATRIBUTO;
     			} 
			}
												
			$sql="SELECT COUNT(*) as contador from PRODUTOCLIENTE A 
					left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
					left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
					where A.COD_EMPRESA='".$cod_empresa."' 
					".$andCod."
					".$andProduto."
					".$andExterno." 
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
			$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);										
				
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
		                                                        
			$sql1="SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
				LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
				LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
				where A.COD_EMPRESA='".$cod_empresa."' 
				".$andCod."
				".$andProduto."
				".$andExterno." 
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";
                                                                
			//fnEscreve($sql1);
			//fnEscreve($cod_empresa);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);
			
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



