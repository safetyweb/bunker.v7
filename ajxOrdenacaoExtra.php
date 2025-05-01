<?php include "_system/_functionsMain.php"; 

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

	$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
	$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
	$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
	$buscaAjx4 = fnLimpacampo($_GET['ajx4']);
	
	//inicialização
	$campo = "";
	
	switch ($buscaAjx4) {
		case 1://faixa de valor
			$campo = "VAL";
			break;     
		case 2://qtd de itens
			$campo = "ITM";
			break; 		
 	
	}
	
	$campoId = explode(",", $buscaAjx1);
	//$campoId = explode(",", "3,2,4");
	$categories = "";
	$montaUpdate = "";
	$contaLoop = 1;
	foreach($campoId as $ordem) {
		$campoId = trim($ordem);
		$montaUpdate .= "update VANTAGEMEXTRAFAIXA set num_ordenac = " . $contaLoop . " where cod_campanha = " . $buscaAjx2 . " and tip_faixas = '" . $campo . "' and cod_geral =  " . $campoId . "; \r\n". PHP_EOL ;
		$contaLoop ++;
	}

	//update da ordenação
	$sql2 = $montaUpdate;	
	$arrayQuery2 = mysqli_multi_query(connTemp($buscaAjx3,''),$sql2) or die(mysqli_error());
	
	//fnEscreve($montaUpdate);	
	//fnEscreve($buscaAjx1);		
	//fnEscreve($buscaAjx2);		
	//fnEscreve($buscaAjx3);

	
		
?>

