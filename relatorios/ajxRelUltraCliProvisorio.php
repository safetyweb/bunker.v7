<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			$sql = "SELECT COD_CLIENTE, NUM_CARTAO, NUM_CGCECPF, NOM_CLIENTE,
					DES_EMAILUS, DAT_CADASTR, DAT_NASCIME , COD_SEXOPES 
					FROM CLIENTES WHERE 															
					COD_EMPRESA = $cod_empresa AND
					LENGTH(NUM_CARTAO) > 8
					order by NOM_CLIENTE";
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				$array[] = $row;
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
																		
			$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES
					WHERE COD_EMPRESA = $cod_empresa AND
					LENGTH(NUM_CARTAO) > 8 ";
			//fnEscreve($sql);
			
			$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
												
			
			$sql = "SELECT COD_CLIENTE, NUM_CARTAO, NUM_CGCECPF, NOM_CLIENTE,
					DES_EMAILUS, DAT_CADASTR, DAT_NASCIME , COD_SEXOPES 
					FROM CLIENTES WHERE 															
					COD_EMPRESA = $cod_empresa AND
					LENGTH(NUM_CARTAO) > 8
					order by NOM_CLIENTE limit $inicio,$itens_por_pagina";
					
			//fnEscreve($sql);
			
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
			$count=0;
			while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
			  {														  
				$count++;
											
				 if ($qrListaPersonas['COD_SEXOPES'] == 1){		
						$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
					}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
											
				echo"
					<tr>
					  <td><small><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrListaPersonas['COD_CLIENTE'])."' target='_blank'>".$qrListaPersonas['NOM_CLIENTE']."</a></td>
					  <td><small>".$qrListaPersonas['NUM_CARTAO']."</small></td>
					  <td><small>".$qrListaPersonas['NUM_CGCECPF']."</small></td>
					  <td><small>".$qrListaPersonas['DES_EMAILUS']."</small></td>
					  <td class='text-center'>".$mostraSexo."</td>
					  <td><small>".$qrListaPersonas['DAT_NASCIME']."</small></td>
					  <td><small>".fnDataFull($qrListaPersonas['DAT_CADASTR'])."</small></td>
					</tr>
					"; 
				  }												

			break; 		
	}
?>