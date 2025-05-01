<?php 

	include '../_system/_functionsMain.php'; 
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	use Box\Spout\Writer\WriterFactory;
	use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	if($opcao == "Imigra"){$consumo = "_CONSUMO (";}else{$consumo = " (8,";}
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
		
	$nomeRel = $_GET['nomeRel'];
	$arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

	$writer = WriterFactory::create(Type::CSV);
	$writer->setFieldDelimiter(';');
	$writer->openToFile($arquivo); 

	// Filtro por Grupo de Lojas
	include "filtroGrupoLojas.php";			
                //============================
                       
	$sql = "CALL SP_RELAT_CLIENTE_VENDAS".$consumo." '$dat_ini' , '$dat_fim' , '$lojasSelecionadas', $cod_empresa) ";
			
	// fnEscreve($sql);
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$array = array();
	while($row = mysqli_fetch_assoc($arrayQuery)){
		  $newRow = array();
		  
		  $cont = 0;
		  foreach ($row as $objeto) {
			  
			// Colunas que são double converte com fnValor
			if($cont == 4 || $cont == 8 || $cont == 9 || $cont == 10){
				array_push($newRow, "R$ ".fnValor($objeto,2));
			}else if($cont == 3){
				array_push($newRow, fnValor($objeto,2)."%");
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

		
?>