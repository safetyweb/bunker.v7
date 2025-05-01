<?php include "_system/_functionsMain.php"; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
	$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
	
	$campoId = explode(",", $buscaAjx1);
	$categories = "";
	$montaUpdate = "";
	$contaLoop = 1;
	foreach($campoId as $ordem) {
		$campoId = trim($ordem);
		if(!is_numeric($campoId)){
			$campoId = fnDecode($campoId);
		}
		$montaUpdate .= "update cat_comunicacao set num_ordenac = " . $contaLoop . " where cod_comunicacao = " . $campoId . "; \r\n". PHP_EOL ;
		$contaLoop ++;
	}

	//update da ordenação
	$sql2 = $montaUpdate;
	// fnEscreve($sql2);		
	$arrayQuery2 = mysqli_multi_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
		
?>

