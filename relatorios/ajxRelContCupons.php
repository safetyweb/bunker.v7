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
	$lojasSelecionadas = $_POST['LOJAS'];
	
	
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
                               
			$sql = "SELECT B.NOM_FANTASI AS LOJA,
						   COUNT(DISTINCT A.COD_CLIENTE) CLIENTES,
						   (SELECT COUNT(1) FROM GERACUPOM WHERE GERACUPOM.COD_UNIVEND=A.COD_UNIVEND AND GERACUPOM.COD_VENDA>0 AND dat_COMPRA >= '$dat_ini 00:00:00'
						   AND dat_COMPRA <= '$dat_fim 23:59:59' ) CUPONS,
						   count(1) TRANSACOES,
						   SUM(A.QTD_VENDA) VENDAS,
						   SUM(A.VAL_TOTVENDA) VAL_TOTVENDA,
						   SUM(A.QTD_ITEM) ITENS,
						   (SUM(A.VAL_TOTVENDA)/SUM(A.QTD_VENDA)) TKT_MEDIO
					FROM CUPOM_CLIENTE_VENDA A  , unidadevenda b
					WHERE
					a.cod_univend = b.cod_univend AND
					a.dat_CADASTR >= '$dat_ini 00:00:00'
					AND a.dat_CADASTR <= '$dat_fim 23:59:59'
					AND A.cod_univend IN($lojasSelecionadas)
					GROUP BY A.COD_UNIVEND
					ORDER BY B.NOM_FANTASI";
					
			//fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {

				  	if($cont == 5 || $cont == 7){
				  		array_push($newRow, "R$ ".fnValor($objeto,2));
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