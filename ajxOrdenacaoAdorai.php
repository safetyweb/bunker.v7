<?php include "_system/_functionsMain.php"; 

	$buscaAjx1 = fnLimpacampo($_REQUEST['ajx1']);
	$buscaAjx2 = fnLimpacampo($_REQUEST['ajx2']);
	$buscaAjx3 = fnLimpacampo($_REQUEST['ajx3']);
	
	//inicialização
	$tabela = "";
	$campo = "";

	//tabela do update
	switch ($buscaAjx2) {
		case 1://opcionais
			$tabela = "OPCIONAIS_ADORAI";
			$campo = "COD_OPCIONAL";
			break;   
	}
	
	$campoId = explode(",", $buscaAjx1);
	//$campoId = explode(",", "3,2,4");
	$categories = "";
	$montaUpdate = "";
	$contaLoop = 1;
	foreach($campoId as $ordem) {
		if ($ordem <> "" && $ordem <> "undefined"){
				$campoId = trim($ordem);
				$montaUpdate .= "update " . $tabela . " set NUM_ORDENAC = " . $contaLoop . " where " . $campo . " = " . $campoId . "; ". PHP_EOL;
			$contaLoop ++;
		}
	}	


	fnEscreve($montaUpdate);

	//update da ordenação
	$sql2 = $montaUpdate;
		$arrayQuery2 = mysqli_multi_query(connTemp($buscaAjx3, ''),$sql2) or die(mysqli_error());
?>