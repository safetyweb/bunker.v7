<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_lista = fnLimpaCampoZero($_POST['COD_LISTA']);
	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	$sql = "SELECT COD_LISTA FROM IMPORT_COMUNICAAV 
			WHERE cod_empresa = $cod_empresa 
			AND COD_LISTA = $cod_lista";	
			
	//fnEscreve($sql);
	$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$total_itens_por_pagina = mysqli_num_rows($retorno);
	
	$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

	//variavel para calcular o início da visualização com base na página atual
	$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

	$sqlProd = "SELECT * FROM IMPORT_COMUNICAAV 
				WHERE COD_EMPRESA = $cod_empresa
				AND COD_LISTA = $cod_lista
				ORDER BY NOM_CLIENTE
			    LIMIT $inicio, $itens_por_pagina;
			  ";

	$result = mysqli_query(connTemp($cod_empresa,""),trim($sqlProd)) or die(mysqli_error());
	////fnEscreve($qrLinhas['LINHAS']);

	while($qrProd = mysqli_fetch_assoc($result)){

	?>
		<tr>
			<td><?=$qrProd['NOM_CLIENTE']?></td>
			<td class="sp_celphones"><?=fnCorrigeTelefone($qrProd['NUM_CELULAR'])?></td>
			<td><?=$qrProd['DES_EMAILUS']?></td>
		</tr>
	<?php
	}

?>