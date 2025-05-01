<?php 

	include '../_system/_functionsMain.php'; 
	//require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
//use Box\Spout\Writer\WriterFactory;
//use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnDecode($_GET['id']);			
	
	$tip_prodtkt = fnLimpaCampoZero($_POST['TIP_PRODTKT']);
	$log_tkunivend = fnLimpaCampo($_POST['LOG_TKTUNIVEND']);
	$cod_categortkt = fnLimpaCampoZero($_POST['COD_CATEGORTKT']);
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$lojasSelecionadas = $_POST['LOJAS'];
	$tip_ordenac = fnLimpaCampoZero($_POST['TIP_ORDENAC']);
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

	if ($tip_prodtkt == 2) {
		$andProdTkt = "AND LOG_ATIVOTK = 'S'";
	} else if ($tip_prodtkt == 3) {
		$andProdTkt = "AND LOG_ATIVOTK = 'N'";
	} else {
		$andProdTkt = "";
	}

	if($log_tkunivend == "S"){
		$andUnivend = "AND PRODUTOTKT.COD_UNIVEND IN ($lojasSelecionadas)";
	}else{
	    $lojasSelecionadas=str_replace(',', "|", $lojasSelecionadas);
		$andUnivend = "AND CONCAT(',', produtotkt.COD_UNIVEND_AUT, ',') REGEXP ',(0|$lojasSelecionadas),'";
	}

	if($cod_categortkt != 0){
		$andCategor = "AND PRODUTOTKT.COD_CATEGORTKT = $cod_categortkt";
	}else{
		$andCategor = "";
	}
	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';	
           			       
			$sql = " SELECT PRODUTOCLIENTE.COD_EXTERNO AS 'Cód. Ext.',
							produtotkt.NOM_PRODTKT AS 'Produto Ticket',
							produtotkt.LOG_ATIVOTK AS 'Ativo',  
							categoriatkt.DES_CATEGOR AS 'Categoria',
							produtotkt.VAL_PRODTKT AS 'De',
							produtotkt.VAL_PROMTKT AS 'Por',
							IF( produtotkt.COD_UNIVEND_AUT <> '0','S','N') AS 'Un. Aut.',
							IF( produtotkt.COD_UNIVEND_BLK <> '0','S','N') AS 'Un. Não Aut.'
					FROM PRODUTOTKT 
					left join categoriatkt on categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT 
					left join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO 
					WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
					$andUnivend
					$andProdTkt
					$andCategor
					AND	PRODUTOTKT.COD_EMPRESA = $cod_empresa 
					order by DES_CATEGOR, NOM_PRODTKT ";
					  
					
			// fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			
				
			$arquivo = fopen($arquivoCaminho, 'w',0);
                    
			while($headers=mysqli_fetch_field($arrayQuery)){
				 $CABECHALHO[]=$headers->name;
			}
			fputcsv ($arquivo,$CABECHALHO,';','"','\n');
		  
			while ($row=mysqli_fetch_assoc($arrayQuery)){  	
				$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                //$textolimpo = json_decode($limpandostring, true);
                $array = array_map("utf8_decode", $row);
                fputcsv($arquivo, $array, ';', '"', '\n');	
			}
			fclose($arquivo);
			/*$array = array();
			{
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					// Colunas que são double converte com fnValor
					if($cont == 12 || $cont == 13){

						array_push($newRow, fnValor($objeto, 2));

					}else if($cont == 14){

						array_push($newRow, fnValor($objeto, $casasDec));

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
			*/

			break;      
		case 'paginar':

													

		break; 		
	}
?>