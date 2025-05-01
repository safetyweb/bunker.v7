<?php 
include '../_system/_functionsMain.php'; 

$url = $_POST['URL'];
$p = parse_url($url);

if (@$p["query"] <> ""){
	foreach (explode('&', @$p["query"]) as $chunk) {
		$param = explode("=", $chunk);

		if ($param) {
			$p["url_params"][urldecode($param[0])]=urldecode($param[1]);
		}
	}
}

$p["cod_modulo"] = "";
if (@$p["url_params"]["mod"] <> ""){
	$p["cod_modulo"] = fnDecode(($p["url_params"]["mod"]));
}

$p["cod_empresa"] = "";
if (@$p["url_params"]["id"] <> ""){
	$p["cod_empresa"] = fnDecode(($p["url_params"]["id"]));
}
echo json_encode($p);