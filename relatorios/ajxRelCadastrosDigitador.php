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
	
	$cod_univend = $_POST['COD_UNIVEND'];
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$cod_usuario = $_POST['COD_USUARIO'];
	$lojasSelecionadas = $_POST['LOJAS'];

// fnEscreve($lojasSelecionadas);

	
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

	if($cod_usuario != "" && $cod_usuario != 0){
	    $andUsuario = "AND C.COD_USUARIO = $cod_usuario";
	}else{
	    $andUsuario = "";
	} 
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 			

			$sql = "SELECT DISTINCT 
			        A.COD_EMPRESA,
			        A.COD_UNIVEND,
			        -- B.NOM_UNIVEND,
			        B.NOM_FANTASI,
			        C.NOM_USUARIO,
			        COUNT(COD_CLIENTE) QTD_CADASTRO,
			        ifnull(SUM(if(A.DAT_NASCIME IS NOT NULL,1,0)),0) AS  QTD_NASCIMEN,
			        ifnull(SUM(if(A.NUM_CELULAR !='',1,0)),0) AS  QTD_CELULAR,
			        ifnull(SUM(if(A.NUM_TELEFON !='',1,0)),0) AS  QTD_TELEFON,
			        ifnull(SUM(if(A.DES_EMAILUS !='',1,0)),0) AS  QTD_EMAILUS,
			        ifnull(SUM(if(A.DES_ENDEREC !='',1,0)),0) AS  QTD_ENDEREC,
			        ifnull(SUM(if(A.NUM_CEPOZOF !='',1,0)),0) AS  QTD_CEPOZOF
			        FROM CLIENTES A
			        LEFT JOIN WEBTOOLS.unidadevenda B ON B.COD_UNIVEND = A.COD_UNIVEND
			        LEFT JOIN WEBTOOLS.usuarios C ON C.COD_USUARIO = A.COD_ATENDENTE
			        WHERE 
			        A.DAT_CADASTR between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' and 
			        A.LOG_AVULSO='N' AND
			        A.COD_EMPRESA = $cod_empresa AND
			        A.COD_UNIVEND IN($lojasSelecionadas)
			        $andUsuario
			        GROUP BY A.COD_ATENDENTE,A.COD_UNIVEND
			        ORDER BY B.NOM_FANTASI,C.NOM_USUARIO 
					";
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
				array_push($newRow, fnValor(((($row['QTD_NASCIMEN'] + $row['QTD_CELULAR'] + $row['QTD_TELEFON'] + $row['QTD_EMAILUS'] + $row['QTD_ENDEREC'] + $row['QTD_CEPOZOF']) / 6) * 100) / $row['QTD_CADASTRO'],2));
				$array[] = $newRow;
			}
			
			$arrayColumnsNames = array();
			while($row = mysqli_fetch_field($arrayQuery))
			{
				array_push($arrayColumnsNames, $row->name);
			}

			array_push($arrayColumnsNames, "QUALIDADE");			

			$writer->addRow($arrayColumnsNames);
			$writer->addRows($array);

			$writer->close();

			break;     
        }		
		
?>