<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];	
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}
	
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}	
	
	switch ($opcao) {
		case 'exportarLojas':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 			

			$sql = "call sp_retorna_rel_compensacao('$dat_ini', '$dat_fim','$lojasSelecionadas',$cod_empresa) ";
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					//if($cont >= 2 && $cont <= 4){
					if($cont > 2){
						array_push($newRow, fnValor($objeto, 2));
					}else{
						array_push($newRow, $objeto);
					}
					  
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
		case 'exportarDetalhes':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 			
			
			$sql = "call sp_retorna_rel_compensacao_analitico_all('$dat_ini', '$dat_fim', $cod_empresa, '$lojasSelecionadas')";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
                         
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 4){
						array_push($newRow, fnValor($objeto, 2));
					}else{
						array_push($newRow, $objeto);
					}
					  
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
		case 'abreDetail':	

			$cod_empresa = $_GET['cod_empresa'];	
			$dat_ini = fnDataSql($_GET['DAT_INI']);
			$dat_fim = fnDataSql($_GET['DAT_FIM']);
			$loja = $_GET['loja'];

			$sql3 = "call sp_retorna_rel_compensacao_analitico('$dat_ini', '$dat_fim', $loja, $cod_empresa)";
			//fnEscreve($sql3);
			
			$arrayQuery3 = mysqli_query(connTemp($cod_empresa,''),$sql3) or die(mysqli_error());																

			?>
				<tr style="background-color: #fff;" class="detail_<?php echo $loja; ?>">
				  <th></th>
				  <th>Loja de Origem</th>
				  <th>Loja de Resgate</th>
				  <th colspan="3"></th>
				</tr>
			<?php
			while ($qrListaUnive2 = mysqli_fetch_assoc($arrayQuery3))
			
			  {
				
				if ($qrListaUnive2['VALOR'] < 0){
					$colReceber	= "<small>R$</small> ".fnValor($qrListaUnive2['VALOR'],2);
					$colPagar	= "";
				}else {
					$colReceber	= "";
					$colPagar	= "<small>R$</small> ".fnValor($qrListaUnive2['VALOR'],2);
				}	
				
			?>

				<tr style="background-color: #fff;" class="detail_<?php echo $loja; ?>">
				  <td width="5%"></td>
				  <td width="19%"><small><?php echo $qrListaUnive2['NOME_UNIDADE_ORIGEM']; ?></small></td>
				  <td width="25%"><small><?php echo $qrListaUnive2['NOME_UNIDADE_RESGATE']; ?></small></td>
				  <td width="10%" class="text-center"><small class="qtde_col2_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colReceber; ?></small></td>
				  <td width="19%" class="text-center"><small></small><small class="qtde_col4_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"><?php echo $colPagar; ?></small></td>
				  <td width="19%" class="text-center"><small></small><small class="qtde_col5_<?php echo $qrListaUnive2['CODIGO_UNIDADE_RESGATE']; ?>"></small></td>	
				</tr>		

			<?php													
			  }			

			break; 		
	}
?>







	
	
	