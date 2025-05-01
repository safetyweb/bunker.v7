<?php
include "_system/_functionsMain.php"; 

header("Content-Type: application/json");

function anti_injection_($sql){
   //$sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "" ,$sql);
   $sql = trim($sql);
   $sql = strip_tags($sql);
   $sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
   return $sql;
}

//echo fnDebug('true');
$tipo = anti_injection_($_REQUEST["tipo"]);
$cod_empresa = anti_injection_($_REQUEST["cod_empresa"]);
$cidades = anti_injection_($_REQUEST["cidades"]);
$cod_mapa_tipo = anti_injection_(@$_REQUEST["cod"]);


$where = "1=1 ";
$where .= " AND cod_empresa=$cod_empresa ";
$where .= " AND LAT != '' AND LNG != '' ";

$where_cidades = "";
$c = explode("|",$cidades);
foreach($c as $cidade){
	if ($cidade <> ""){
		$where_cidades .= ($where_cidades <> ""?",":"")."'".$cidade."'";
	}
}
$where .= ($where_cidades <> ""?" AND CONCAT(nom_cidadec,':',cod_estadof) IN ($where_cidades)":"");

if ($tipo == "cli"){

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
		$qrGeo["nom_cliente"] = preg_replace("/[^a-zA-Z0-9 ]/","", fnMascaraCampo($qrGeo["nom_cliente"]));
		$retorno[] = $qrGeo;
	}

	echo json_encode($retorno);


}elseif ($tipo == "uni"){

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
	
}elseif ($tipo == "tip"){

	$sql = "SELECT
				cod_mapa_tipo_item cod,cod_mapa_tipo tipo,nom_nome,des_enderec,num_enderec,nom_cidadec,cod_estadof,lat,lng
			FROM mapas_tipos_itens
			WHERE $where AND COD_MAPA_TIPO = $cod_mapa_tipo AND LOG_EXCLUSAO='N'";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$retorno = array();
	while($qrGeo = mysqli_fetch_assoc($arrayQuery)){
		$retorno[] = $qrGeo;
	}
	//$retorno["sql"] = $sql;
	echo json_encode($retorno);
	
}elseif ($tipo == "grava_item"){
	
	$cod_mapa_tipo_item = anti_injection_($_REQUEST["cod"]);
	$cod_mapa_tipo = anti_injection_($_REQUEST["tipo_item"]);
	$cod_mapa = anti_injection_($_REQUEST["cod_mapa"]);
	$nom_nome = anti_injection_($_REQUEST["nome"]);
	$lat = anti_injection_($_REQUEST["lat"]);
	$lng = anti_injection_($_REQUEST["lng"]);

	if ($cod_mapa_tipo_item <= 0){
		$end = FnGeoLatLng($lat,$lng);
		$sql = "INSERT INTO mapas_tipos_itens (
					cod_mapa_tipo,cod_mapa,nom_nome,lat,lng,
					des_logradouro,des_enderec,des_complem,des_bairroc,num_cepozof,nom_cidadec,cod_estadof,
					cod_empresa,cod_usucada,dt_cadastr
				)VALUES(
					'$cod_mapa_tipo','$cod_mapa','$nom_nome','$lat','$lng',
					'".@$end["tipo_logradouro"]."','".@$end["logradouro"]."','".@$end["complemento"]."','".@$end["bairro"]."','".@$end["cep"]."','".@$end["cidade"]."','".@$end["uf"]."',
					$cod_empresa,'".$_SESSION["SYS_COD_USUARIO"]."',NOW()
				)";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		$sql = "SELECT MAX(cod_mapa_tipo_item) cod_mapa_tipo_item FROM mapas_tipos_itens where COD_EMPRESA = '".$cod_empresa."' ";
		$rs = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
		$cod_mapa_tipo_item = $rs["cod_mapa_tipo_item"];
	}else{
		$sql = "UPDATE mapas_tipos_itens SET
					cod_mapa_tipo='$cod_mapa_tipo',
					nom_nome='$nom_nome',
					cod_usualte='".$_SESSION["SYS_COD_USUARIO"]."',
					dt_alterac=NOW()
				WHERE cod_mapa_tipo_item = $cod_mapa_tipo_item AND cod_empresa=$cod_empresa";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	}
	

	$retorno["cod"] = $cod_mapa_tipo_item;
	echo json_encode($retorno);
	
}elseif ($tipo == "apaga_item"){

	$cod_mapa_tipo_item = anti_injection_($_REQUEST["cod"]);

	$sql = "UPDATE mapas_tipos_itens SET
				LOG_EXCLUSAO='S',
				cod_usuexc='".$_SESSION["SYS_COD_USUARIO"]."',
				dt_exclusao=NOW()
			WHERE cod_mapa_tipo_item = $cod_mapa_tipo_item AND cod_empresa=$cod_empresa";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$retorno["cod"] = $cod_mapa_tipo_item;
	echo json_encode($retorno);
	
}elseif ($tipo == "move_item"){

	$cod_mapa_tipo_item = anti_injection_($_REQUEST["cod"]);
	$lat = anti_injection_($_REQUEST["lat"]);
	$lng = anti_injection_($_REQUEST["lng"]);

	$end = FnGeoLatLng($lat,$lng);
	$sql = "UPDATE mapas_tipos_itens SET
				lat='$lat',
				lng='$lng',
				des_logradouro = '".@$end["tipo_logradouro"]."',
				des_enderec = '".@$end["logradouro"]."',
				des_complem = '".@$end["complemento"]."',
				des_bairroc = '".@$end["bairro"]."',
				num_cepozof = '".@$end["cep"]."',
				nom_cidadec = '".@$end["cidade"]."',
				cod_estadof = '".@$end["uf"]."',
				cod_usualte='".$_SESSION["SYS_COD_USUARIO"]."',
				dt_alterac=NOW()
			WHERE cod_mapa_tipo_item = $cod_mapa_tipo_item AND cod_empresa=$cod_empresa";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$retorno["cod"] = $cod_mapa_tipo_item;
	echo json_encode($retorno);
	
}