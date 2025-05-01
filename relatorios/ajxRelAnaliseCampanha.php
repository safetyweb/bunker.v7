<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	// $itens_por_pagina = $_GET['itens_por_pagina'];	
	// $pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);				
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);

	$lojasSelecionadas = fnLimpaCampo($_REQUEST['LOJAS']);

	if (isset($_POST['COD_PERSONA'])){
		$cod_persona = "";
		$Arr_COD_PERSONA = $_POST['COD_PERSONA'];			 
		 
		   for ($i=0;$i<count($Arr_COD_PERSONA);$i++) 
		   { 
			$cod_persona = $cod_persona.$Arr_COD_PERSONA[$i].",";
		   } 
		   
		   $cod_persona = ltrim(rtrim($cod_persona,','),',');
			
	}else{$cod_persona = "0";}
	
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}
	

	switch ($opcao) {

		case 'exportar':

			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);	
                               
			$sql = "CALL SP_RELAT_ANALISE_RESGATE_ANIVERSARIO ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas', '$cod_persona', $cod_empresa)";
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 4 || $cont == 5 || $cont == 8){
						array_push($newRow, fnValor($objeto, 2)."%");
					}else if($cont == 1 || $cont == 2 || $cont == 3 || $cont == 6 || $cont == 7){
						array_push($newRow, "R$ ".fnValor($objeto, 2));
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
				
			}

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

		break;
		    
		case 'paginar':
                               
			$sql = "CALL SP_RELAT_ANALISE_RESGATE_ANIVERSARIO ( '$dat_ini' , '$dat_fim' , '$lojasSelecionadas', '$cod_persona', $cod_empresa)";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			while($row = mysqli_fetch_assoc($arrayQuery)){
				
			}

		break;
		    	 		
	}

?>