<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
	if (isset($_POST['COD_PERSONA'])){
		$Arr_COD_PERSONA = $_POST['COD_PERSONA'];
		//print_r($Arr_COD_MULTEMP);			 
	 
	   for ($i=0;$i<count($Arr_COD_PERSONA);$i++) 
	   { 
		$cod_persona = $cod_persona.$Arr_COD_PERSONA[$i].",";
	   } 
	   
	   $cod_persona = ltrim(rtrim($cod_persona,','),',');
		
	}else{$cod_persona = "0";}
//	$cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);

	$sqlPersona = "SELECT BL5_UNIPREF, BL5_COD_UNIVE FROM PERSONAREGRA WHERE COD_PERSONA IN($cod_persona)";
	// fnEscreve($sqlPersona);
	$arrPers = mysqli_query(connTemp($cod_empresa,''),$sqlPersona);

	while($qrPers = mysqli_fetch_assoc($arrPers)){

		$arrayItens = explode(";",$qrPers["BL5_COD_UNIVE"]);

		$log_unipref = "N";

		if($qrPers["BL5_UNIPREF"] != ""){
			$log_unipref = $qrPers["BL5_UNIPREF"];
		}

		$arrayItens["LOG_UNIPREF"] = $log_unipref;
	}

	$arrayData['unipref'] = $arrayItens;

	$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
			INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
			where A.COD_EMPRESA = $cod_empresa ";		
	
	
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	for ($i = 1; $i <= 13; $i++){
		$lblAtributo = $qrBuscaEmpresa["ATRIBUTO".$i];
		$limit = 10;

		$sql = "SELECT GROUP_CONCAT(COD_ATRIBUTO) COD_ATRIBUTO,TIP_FILTRO, COD_PERSONA FROM ATRIBUTOS_PRODUTOPERSONA 
				WHERE COD_PERSONA IN($cod_persona)
				AND COD_EMPRESA = $cod_empresa 
				AND TIP_ATRIBUTO = $i
				GROUP BY COD_PERSONA";

		// fnescreve($sql);
		$arrayAttr = mysqli_query(connTemp($cod_empresa,''),$sql);

		while($qrAttr = mysqli_fetch_assoc($arrayAttr)){

			if ($qrAttr["COD_ATRIBUTO"] != ""){
				$arrAttr["ATRIBUTO".$i."_".$lblAtributo][$qrAttr[COD_PERSONA]] = $qrAttr["COD_ATRIBUTO"];
			}

		}

	}

	$arrayData['atributos'] = $arrAttr;

	echo json_encode($arrayData,true);
?>
