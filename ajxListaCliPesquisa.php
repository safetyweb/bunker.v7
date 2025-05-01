<?php 

	include '_system/_functionsMain.php'; 
	require_once 'js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = fnLimpaCampo($_GET['opcao']);
	$des_dominio = fnLimpaCampo($_REQUEST['DES_DOMINIO']);
	$cod_empresa = fnDecode($_GET['id']);			
	$cod_pesquisa = fnDecode($_GET['idp']);			

	
	switch ($opcao) {
		case 'exportar':
		
			$nomeRel = $_GET['nomeRel'];
			$arquivo = 'media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';
		
			$writer = WriterFactory::create(Type::CSV);
			$writer->setFieldDelimiter(';');
			$writer->openToFile($arquivo); 
                               
			$sql = "SELECT COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, COD_CLIENTE AS LINK_PESQUISA 
					FROM NPS_LISTA
					WHERE COD_PESQUISA = $cod_pesquisa";
					
			fnEscreve($sql);
					
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

			$array = array();
			while($row = mysqli_fetch_assoc($arrayQuery)){
				  $newRow = array();
				  
				  $cont = 0;
				  foreach ($row as $objeto) {
					  
					if($cont == 3){
						array_push($newRow, file_get_contents("http://tinyurl.com/api-create.php?url="."https://".$des_dominio.".fidelidade.mk/pesquisa?idP=".fnEncode($cod_pesquisa)."&idc=".fnEncode($objeto)));
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
		
	}
?>