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
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
	$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);
	$lojasSelecionadas = $_POST['LOJAS'];
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" && strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31"){
		$dat_ini = fnDataSql($dias30); 
		$dat_ini2 = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31" && strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
		$dat_fim2 = fnDataSql($hoje); 
	}

	switch ($opcao) {

		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";			
                        //============================
                               
			$sql = "CALL SP_RELAT_PERCENTUAL_PERIODO_VENDA ( '$dat_ini' , '$dat_fim'  , '$dat_ini2' , '$dat_fim2', '$lojasSelecionadas' , $cod_empresa )";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();

				  $PERC_TRANSACAO = (($row['QTD_TRANSACAO_P2'] - $row['QTD_TRANSACAO_P1']) / $row['QTD_TRANSACAO_P1']) * 100;
				  $PERC_ITENS = (( $row['QTD_ITENS_P2'] - $row['QTD_ITENS_P1'] ) / $row['QTD_ITENS_P1'] ) * 100;
				  $PERC_CLIENTE = (( $row['QTD_CLIENTE_P2'] - $row['QTD_CLIENTE_P1'] ) / $row['QTD_CLIENTE_P1'] ) * 100;						
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 1 || $cont == 2 || $cont == 6 || $cont == 7){
				  		array_push($newRow, "R$ ".fnValor($objeto,2));
				  	}else if($cont == 11 || $cont == 12 || $cont == 13 || $cont == 14 || $cont == 15){
				  		if($cont == 13){
				  			$objeto = $PERC_TRANSACAO;
				  		}else if($cont == 14){
				  			$objeto = $PERC_ITENS;
				  		}else if($cont == 15){
				  			$objeto = $PERC_CLIENTE;
				  		}

				  		array_push($newRow, fnValor($objeto,2)."%");

				  	}else{					
						array_push($newRow, $objeto);
					}
					  
					$cont++;
				  }
					
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			$count = 0;
			while($row = mysqli_fetch_field($arrayQuery))
			{
				
				array_push($arrayColumnsNames, $row->name);
				
				$count++;
			}			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;

	}