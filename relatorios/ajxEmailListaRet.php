<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);		
	$status_leitura = fnLimpaCampo($_POST['STATUS_LEITURA']);			
	$status_envio = fnLimpaCampoZero($_POST['STATUS_ENVIO']);			
	$cod_optout_ativo = fnLimpaCampoZero($_POST['COD_OPTOUT_ATIVO']);					
	// $dat_ini = fnDataSql($_POST['DAT_INI']);
	// $dat_fim = fnDataSql($_POST['DAT_FIM']);
	
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

	if($status_envio == 9){
		$andStatus = "";
	}else{
		$andStatus = "AND STATUS_ENVIO = $status_envio";
	}

	if($cod_optout_ativo == 9){
		$andOptOut = "";
	}else{
		$andOptOut = "AND COD_OPTOUT_ATIVO = $cod_optout_ativo";
	}

	if($status_leitura == '0'){
		$andLeitura = "";
	}else{
		$andLeitura = "AND DAT_LEITURA $status_leitura ''";
	}

	switch ($opcao) {

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
			       
			$sql = "SELECT DES_EMAILUS,
						   NOM_CLIENTE,
						   DAT_NASCIME,
						   dat_leitura AS DAT_LEITURA,
						   TIP_NAVEGADOR,
						   TIP_MODELO,
						   DAT_OPOUT,
						   DES_MOTIVO
					FROM EMAIL_LISTA_RET
				    WHERE COD_EMPRESA = $cod_empresa
				    AND COD_CAMPANHA = $cod_campanha 
				    $andStatus
			    	$andOptOut
				    $andLeitura
			";
					
			//fnEscreve($sql);
					
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
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;

		case 'paginar':

			$sql = "SELECT COD_LISTA FROM EMAIL_LISTA_RET
				    WHERE COD_EMPRESA = $cod_empresa
				    AND COD_CAMPANHA = $cod_campanha 
				    $andStatus
				    $andOptOut
				    $andLeitura
			";

					//fnEscreve($sql);
					
					$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
					$total_itens_por_pagina = mysqli_num_rows($retorno);
					
					$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
					
					//variavel para calcular o início da visualização com base na página atual
					$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
			
				$sql = "SELECT DES_EMAILUS,
							   NOM_CLIENTE,
							   DAT_NASCIME,
							   dat_leitura AS DAT_LEITURA,
							   TIP_NAVEGADOR,
							   TIP_MODELO,
							   DAT_OPOUT,
							   DES_MOTIVO
						FROM EMAIL_LISTA_RET
					    WHERE COD_EMPRESA = $cod_empresa
					    AND COD_CAMPANHA = $cod_campanha 
					    $andStatus
				    	$andOptOut
					    $andLeitura
					    LIMIT $inicio,$itens_por_pagina";
				
				// fnEscreve($sql);
				//fnTestesql(connTemp($cod_empresa,''),$sql);
				
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$count=0;
				
				while ($qrRet = mysqli_fetch_assoc($arrayQuery))
				{

					$count++;
					
		?>
					<tr>
						<td><small><?=$qrRet['DES_EMAILUS']?></small></td>
						<td><small><?=$qrRet['NOM_CLIENTE']?></small></td>
						<td class="text-center"><small><?=$qrRet['DAT_NASCIME']?></small></td>
						<td class="text-center"><small><?=$qrRet['DAT_LEITURA']?></small></td>
						<td><small><?=$qrRet['TIP_NAVEGADOR']?></small></td>
						<td><small><?=$qrRet['TIP_MODELO']?></small></td>
						<td class="text-center"><small><?=$qrRet['DAT_OPOUT']?></small></td>
						<td><?=$qrRet['DES_MOTIVO']?></td>
					</tr>


		<?php											
				}										

		break; 		
}
?>