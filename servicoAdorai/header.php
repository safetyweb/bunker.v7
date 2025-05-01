<?php
include '../_system/_functionsMain.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$connadmtemp = $connAdm->connAdm();

$data = [];
$data["errors"] = [];


if (@$_REQUEST["COD_EMPRESA"] == "") {
    $data["errors"]["message"] = "Parâmetro 'COD_EMPRESA' não definido!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}
$cod_empresa = fnLimpaCampoZero(@$_REQUEST["COD_EMPRESA"]);

$conexaotmp = connTemp(@$_REQUEST["COD_EMPRESA"], '');
