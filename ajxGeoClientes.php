<?php include "_system/_functionsMain.php"; 

header("Content-Type: application/json");

function anti_injection($sql){
   //$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "" ,$sql);
   $sql = trim($sql);
   $sql = strip_tags($sql);
   $sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
   return $sql;
}

//echo fnDebug('true');
$tipo = anti_injection($_REQUEST["tipo"]);
$cod_empresa = anti_injection($_REQUEST["cod_empresa"]);
$cod_persona = anti_injection($_REQUEST["cod_persona"]);
$cidades = anti_injection($_REQUEST["cidades"]);


$where = "1=1 ";
$where .= " AND cod_empresa=$cod_empresa ";
$where .= " AND lat!='' AND lng!='' ";

$where_cidades = "";
$c = explode("|",$cidades);
foreach($c as $cidade){
	if ($cidade <> ""){
		$where_cidades .= ($where_cidades <> ""?",":"")."'".$cidade."'";
	}
}
$where .= ($where_cidades <> ""?" AND CONCAT(nom_cidadec,':',cod_estadof) IN ($where_cidades)":"");

if ($tipo == "cli"){
	if ($cod_persona <> ""){
		$where .= " AND cod_cliente IN (SELECT cod_cliente FROM PERSONACLASSIFICA where cod_persona = $cod_persona and COD_EMPRESA = $cod_empresa) ";
	}

	$ARRAY_UNIDADE1=array(
		   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa is null",
		   'cod_empresa'=>$cod_empresa,
		   'conntadm'=>$connAdm->connAdm(),
		   'IN'=>'N',
		   'nomecampo'=>'',
		   'conntemp'=>'',
		   'SQLIN'=> ""   
		   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	$sql = "SELECT
				cod_cliente,nom_cliente,cod_sexopes,idade,cod_univend,lat,lng,
				(CASE WHEN cod_sexopes=1 THEN 'Masculino' WHEN cod_sexopes=2 THEN 'Feminino' ELSE 'Indefinido' END) sexo,
				des_enderec,num_enderec,nom_cidadec,cod_estadof
			FROM clientes WHERE $where";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$retorno = array();
	while($qrGeo = mysqli_fetch_assoc($arrayQuery)){
		$qrGeo["cod_cliente_encode"] = fnEncode($qrGeo["cod_cliente"]);
		$qrGeo["unidade"] = @$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]["nom_fantasi"]." ";
		$retorno[] = $qrGeo;
	}

	echo json_encode($retorno);


}elseif ($tipo == "uni"){

	if ($cod_persona <> ""){
		$sql = "SELECT REPLACE(BL5_COD_UNIVE,';',',') COD_UNIVEND FROM personaregra WHERE COD_PERSONA=$cod_persona";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
		$qrLinha = mysqli_fetch_assoc($arrayQuery);
		$cod_univend = @$qrLinha["COD_UNIVEND"];
		if ($cod_univend != "0" && $cod_univend != ""){
			$where .= " AND COD_UNIVEND IN ($cod_univend)";
		}
	}
	
	$where .= " AND LOG_ESTATUS = 'S' AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) ";

	$sql = "SELECT
				nom_fantasi,des_enderec,num_enderec,nom_cidadec,cod_estadof,lat,lng
			FROM unidadevenda
			WHERE $where";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

	$retorno = array();
	while($qrGeo = mysqli_fetch_assoc($arrayQuery)){
		$retorno[] = $qrGeo;
	}

	echo json_encode($retorno);
	
}