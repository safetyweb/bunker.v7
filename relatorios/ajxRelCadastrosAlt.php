<?php 

	include '../_system/_functionsMain.php'; 
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// use Box\Spout\Writer\WriterFactory;
	// use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));			
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);

	if (isset($_POST['COD_INDICAD'])){
		$Arr_COD_INDICAD = $_POST['COD_INDICAD'];
		// print_r($Arr_COD_INDICAD);			 
	 
	   for ($i=0;$i<count($Arr_COD_INDICAD);$i++) 
	   { 
	   	if($Arr_COD_INDICAD[$i] != 0){
	   		$cod_indicad = $cod_indicad.$Arr_COD_INDICAD[$i].",";
	   	}
	   } 
	   
	   $cod_indicad = ltrim(rtrim($cod_indicad,','),',');
		
	}else{$cod_indicad = "0";}

	if($cod_indicad != 0){
		$andIndica = "AND COD_INDICAD IN($cod_indicad)";
	}else{
		$andIndica = "";
	}

	switch ($opcao) {

		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
                               
			$sql = "";
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				$newRow = array();

				$cont = 0;

				foreach ($row as $objeto) {

					array_push($newRow, $objeto);
					
					$cont++;
				}
				  
				$array[] = $newRow;
			}

			$cont = 0;
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				
				array_push($arrayColumnsNames, $row->name);
				$cont++;

			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();
			
		break;
		    
		case 'paginar':
			
			$sql = "SELECT COD_CLIENTE
					FROM CLIENTES_COM_ALTERACAO
					WHERE DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andIndica
					";
			//fnTestesql(connTemp($cod_empresa,''),$sql);		
			//fnEscreve($sql);

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
			$totalitens_por_pagina = mysqli_num_rows($retorno);

			$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

			// Filtro por Grupo de Lojas
			//include "filtroGrupoLojas.php";

			$sql = "SELECT COD_CLIENTE,
						   NOM_CLIENTE,
						   DAT_ALTERAC,
						   IDADE,
						   DES_EMAILUS,
						   NUM_CELULAR,
						   DES_ENDEREC,
						   NUM_ENDEREC,
						   DES_COMPLEM,
						   DES_BAIRROC,
						   NUM_CEPOZOF,
						   NOM_CIDADEC,
						   COD_ESTADOF,
						   NOM_INDICADOR 
					FROM CLIENTES_COM_ALTERACAO
					WHERE DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andIndica
					LIMIT $inicio,$itens_por_pagina
					";
			
			// fnEscreve($sql);
                                                   
			//fnTestesql(connTemp($cod_empresa,''),$sql);											
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
								  
			$count=0;

			while ($qrApoia = mysqli_fetch_assoc($arrayQuery)){								

			  	$count++;

			  	$endereco = "";

			  	if($qrApoia['DES_ENDEREC'] != ""){
			  		$endereco .= $qrApoia['DES_ENDEREC'].', ';
			  	}

			  	if($qrApoia['NUM_ENDEREC'] != ""){
			  		$endereco .= $qrApoia['NUM_ENDEREC'].', ';
			  	}

			  	if($qrApoia['DES_COMPLEM'] != ""){
			  		$endereco .= "(".$qrApoia['DES_COMPLEM'].'), ';
			  	}

			  	if($qrApoia['DES_BAIRROC'] != ""){
			  		$endereco = rtrim(rtrim($endereco,' '),',');
			  		$endereco .= " - ".$qrApoia['DES_BAIRROC'].', ';
			  	}

			  	if($qrApoia['NUM_CEPOZOF'] != ""){
			  		$endereco = rtrim(rtrim($endereco,' '),',');
			  		$endereco .= "<br>".$qrApoia['NUM_CEPOZOF'].' - ';
			  	}

			  	if($qrApoia['NOM_CIDADEC'] != ""){
			  		$endereco .= $qrApoia['NOM_CIDADEC'].'/';
			  	}

			  	if($qrApoia['COD_ESTADOF'] != ""){
			  		$endereco .= $qrApoia['COD_ESTADOF'];
			  	}


			  	

?>

				<tr>
					<td><a href="action.do?mod=<?=fnEncode(1423)?>&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($qrApoia[COD_CLIENTE])?>" class="f14" target="_blank"><?=$qrApoia['NOM_CLIENTE']?></a></td>
					<td><small><?=fnDataShort($qrApoia['DAT_ALTERAC'])?></small></td>
					<td><?=$qrApoia['IDADE']?></td>
					<td><?=$qrApoia['DES_EMAILUS']?></td>
					<td><?=$qrApoia['NUM_CELULAR']?></td>
					<td class="text-center"><small><?=$endereco?></small></td>
					<td><?=$qrApoia['NOM_INDICADOR']?></td>
				</tr>

<?php

			} 									

		break; 		
	}
?>