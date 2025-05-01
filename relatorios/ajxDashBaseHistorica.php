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
	
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
	$cod_univend = $_POST['COD_UNIVEND'];
	$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
	$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
	$lojasSelecionadas = $_POST['LOJAS'];


	$dat_ini = fnDatasql($_REQUEST['DAT_INI']);
	$dat_fim = fnDatasql($_REQUEST['DAT_FIM']);
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";			
           
			       
			$sql = "CALL SP_RELAT_COMPARACAO_CONSOLIDADA ( '".$dat_ini."' , '".$dat_fim."' , '$lojasSelecionadas' , $cod_empresa , 'LOJA' ) ;";
					
			fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){

				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

					  if($cont > 2){

					  	if($cont == 7 || $cont == 10 || $cont == 12){
					  		array_push($newRow, fnValor($objeto,2));
					  	}else{
					  		array_push($newRow, $objeto);
					  	}
					  	


					  }		
					
				  	$cont++;

				  }
				$array[] = $newRow;

			}
			
			$cont = 0;
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				if($cont > 2){
					array_push($arrayColumnsNames, $row->name);
				}
				$cont++;

				// if($cont == 25){
				// 	break;
				// }
			}

			// array_push($arrayColumnsNames, "-17");			
			// array_push($arrayColumnsNames, "18-20");			
			// array_push($arrayColumnsNames, "21-30");			
			// array_push($arrayColumnsNames, "31-40");			
			// array_push($arrayColumnsNames, "41-50");			
			// array_push($arrayColumnsNames, "51-60");			
			// array_push($arrayColumnsNames, "61-70");			
			// array_push($arrayColumnsNames, "71-80");			
			// array_push($arrayColumnsNames, "+81");			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

			break;      
				
	}
?>