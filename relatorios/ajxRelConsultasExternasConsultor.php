<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$mostraXml = $_GET['mostrarXML'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	// $cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	// $lojasSelecionadas = $_GET['LOJAS'];
	
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
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}	
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo);

			if($num_cgcecpf == ""){
				$andCpf = " ";
			}else {
				$andCpf = "NUM_CGCECPF = $num_cgcecpf AND ";
			}
			
			if($cod_vendapdv == ""){
				$andVendaPDV = " ";
			}else {
				$andVendaPDV = "COD_PDV = '".$cod_vendapdv."' AND ";
			} 			

			$sql = "select DAT_CADASTR,
						   COD_PDV,
						   COD_UNIVEND,
						   ID_MAQUINA,
						   NUM_CGCECPF,
						   IP,
						   NOM_USUARIO,
						   COD_ORIGEM
					from origemestornavenda
					where 
					$andCpf
					$andVendaPDV    
					DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
					AND DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
					AND cod_empresa= $cod_empresa
					order by origemestornavenda.COD_ORIGEM desc
					";
					
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
	}
?>