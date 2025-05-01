<?php include "_system/_functionsMain.php"; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
	$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
	$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
	
	//inicialização
	$tabela = "";
	$campo = "";

	//tabela do update
	switch ($buscaAjx2) {
		case 1://faixas
			$tabela = "CATEGORIATKT";
			$campo = "COD_CATEGORTKT";
			break; 
		case 2://modelo template
			$tabela = "MODELOTEMPLATETKT";
			$campo = "COD_REGISTR";
			break;  				
		case 3://grupo desconto
			$tabela = "DESCONTOTKT";
			$campo = "COD_DESCTKT";
			break;  				
		case 4://faq
			$tabela = "PERGUNTAS";
			$campo = "COD_PERGUNTA";
			break;  						
		case 5://categorização
			$tabela = "CATEGORIA_CLIENTE";
			$campo = "COD_CATEGORIA";
			break;  	
		case 6://modelo template pesquisa
			$tabela = "MODELOPESQUISA";
			$campo = "COD_REGISTR";
			break;
		case 7://Categoria de Clientes
			$tabela = "CATEGORIA_CLIENTE";
			$campo = "COD_CATEGORIA";
			break; 
		case 8://Ocorrência dos filtros
			$tabela = "TIPO_FILTRO";
			$campo = "COD_TPFILTRO";
			break;	
		case 9://Automação de Emails
			$tabela = "TEMPLATE_AUTOMACAO";
			$campo = "COD_TEMPLATE";
			break;
		case 10://Bloco de termos
			$tabela = "BLOCO_TERMOS";
			$campo = "COD_BLOCO";
			break;
		case 11://Template de documento
			$tabela = "TEMPLATE_DOCUMENTO";
			$campo = "COD_TEMPLATE";
			break;
		case 12://Opcionais Adorai
			$tabela = "OPCIONAIS_ADORAI";
			$campo = "COD_OPCIONAL";
			break;
		case 13://FAQ Adorai Acomodações
			$tabela = "PERGUNTAS_ADORAI";
			$campo = "COD_PERGUNTA";
			break;	
	}
	
	$campoId = explode(",", $buscaAjx1);
	//$campoId = explode(",", "3,2,4");
	$categories = "";
	$montaUpdate = "";
	$contaLoop = 1;
	foreach($campoId as $ordem) {
		$campoId = trim($ordem);
		if(!is_numeric($campoId)){
			$campoId = fnDecode($campoId);
		}
		$montaUpdate .= "update " . $tabela . " set num_ordenac = " . $contaLoop . " where " . $campo . " =  " . $campoId . "; \r\n". PHP_EOL ;
		$contaLoop ++;
	}

	//update da ordenação
	$sql2 = $montaUpdate;
	// fnEscreve($sql2);		
	$arrayQuery2 = mysqli_multi_query(connTemp($buscaAjx3,''),$sql2) or die(mysqli_error());
	//$qrOrdena = mysqli_fetch_assoc($arrayQuery2);		
		
?>

