<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);

	$andFiltro = fnDecode($_POST['FILTROS']);

	// if($filtro != ""){
	// 	$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
	// }else{
	// 	$andFiltro = " ";
	// }

	// fnEscreve($andFiltro);

	$sqlCount = "SELECT COD_BLKLIST FROM BLACKLIST_EMAIL 
				 WHERE COD_EMPRESA = $cod_empresa
				 $andFiltro";
	// fnEscreve($sqlCount);
	
	$retorno =  mysqli_query(connTemp($cod_empresa,''),trim($sqlCount));
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);

	// fnEscreve($numPaginas);	

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sql = "SELECT * FROM BLACKLIST_EMAIL 
			WHERE COD_EMPRESA = $cod_empresa
			$andFiltro
			LIMIT $inicio,$itens_por_pagina";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));
	
	$count=0;
	while ($qrBlklist = mysqli_fetch_assoc($arrayQuery))
	  {														  
		$count++;	
		echo"
			<tr>
			  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
			  <td>".$qrBlklist['DES_EMAIL']."</td>
			  <td>".fnDataShort($qrBlklist['DAT_CADASTR'])."</td>
			</tr>
			<input type='hidden' id='ret_COD_BLKLIST_".$count."' value='".$qrBlklist['COD_BLKLIST']."'>
			<input type='hidden' id='ret_DES_EMAIL_".$count."' value='".$qrBlklist['DES_EMAIL']."'>
			"; 
		  }											

?>

	