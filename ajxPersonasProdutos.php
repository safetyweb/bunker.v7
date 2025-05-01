<?php include "_system/_functionsMain.php";

//echo fnDebug('true');
$acao = $_GET['acao'];

// fnEscreve($acao);

switch ($acao) {
	case 'addProdutos':
	
		$array = json_decode( $_POST['listaProdutos'], true );

		// print_r($array);

		foreach($array as $item) { 
			$sql = "CALL SP_ALTERA_PERSONAS_PRODUTOS (
			 '".fnLimpaCampoZero($item['COD_PERPROD'])."', 
			 '".fnLimpaCampoZero($item['COD_PRODUTO'])."',				 
			 '".fnLimpaCampoZero($item['COD_FORNECEDOR'])."', 
			 '".fnLimpaCampoZero($item['COD_CATEGOR'])."', 
			 '".fnLimpaCampoZero($item['COD_SUBCATE'])."', 
			 '".fnLimpaCampoZero($item['COD_PERSONA'])."', 
			 '".fnLimpaCampoZero($_GET['cod_empresa'])."', 
			 '".$item['OPCAO']."'
			) ";
			
			 //fnEscreve($sql);
			//fnEscreve($_GET['cod_empresa']);
			mysqli_query(connTemp($_GET['cod_empresa'],''),$sql);
		}

		break;

	case 'excProdutos':
	
		$array = json_decode( $_POST['listaProdutos'], true );

		foreach($array as $item => $valor) {
			$sql .= "DELETE FROM PERSONAS_PRODUTOS WHERE COD_PERPROD = $valor AND COD_EMPRESA =".$_GET['cod_empresa'].";";
		}

		// fnEscreve($sql);

		mysqli_multi_query(connTemp($_GET['cod_empresa'],''),$sql);

	break;

	case 'proc':
	
		$sql = "CALL SP_ALTERA_PERSONAS_PRODUTOS (
		 '".fnLimpaCampoZero($_POST['COD_PERPROD'])."', 
		 '".fnLimpaCampoZero($_POST['COD_PRODUTO'])."',				 
		 '".fnLimpaCampoZero($_POST['BL2_COD_FORNECEDOR'])."', 
		 '".fnLimpaCampoZero($_POST['BL2_COD_CATEGOR'])."', 
		 '".(empty($_POST['BL2_COD_SUBCATE']) ? 0 : $_POST['BL2_COD_SUBCATE'])."', 
		 '".fnLimpaCampoZero($_POST['COD_PERSONA'])."', 
		 '".fnLimpaCampoZero($_GET['cod_empresa'])."', 
		 '".$_GET['opcao']."'
		) ";
		
		mysqli_query(connTemp($_GET['cod_empresa'],''),$sql);

		 fnEscreve($sql);
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

			FROM personas_produtos 
			where COD_PERSONA = $cod_persona 
			AND COD_EMPRESA = $_GET[cod_empresa]
			ORDER BY COD_PERPROD DESC";
				
		// fnEscreve($sql);
		//fnEscreve($cod_busca);
		$arrayQuery = mysqli_query(connTemp($_GET['cod_empresa'],''),$sql);
															
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
}	
?>



