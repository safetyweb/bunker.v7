<?php

include '_system/_functionsMain.php';
//echo fnDebug('true');

$cod_empresa = fnDecode($_GET['id']);
$cod_fluxo = fnLimpacampoZero($_REQUEST['COD_FLUXO']);
$cod_modulos = fnLimpacampoZero($_REQUEST['COD_MODULOS']);
$cod_node = fnLimpacampo($_REQUEST['COD_NODE']);

$jsn_fluxo_export = $_REQUEST['JSN_FLUXO_EXPORT'];
$jsn_fluxo_export = str_replace("\\\"", "\\\\\\\"", $jsn_fluxo_export);
$jsn_fluxo_export = str_replace("'", "\'", $jsn_fluxo_export);

$des_itens = $_REQUEST['DES_ITENS'];
$des_itens = str_replace("\\\"", "\\\\\\\"", $des_itens);
$des_itens = str_replace("'", "\'", $des_itens);

$des_fluxo_modulos = $_REQUEST['DES_FLUXO_MODULOS'];
$des_fluxo_modulos = str_replace("\\\"", "\\\\\\\"", $des_fluxo_modulos);
$des_fluxo_modulos = str_replace("'", "\'", $des_fluxo_modulos);

$hHabilitado = $_REQUEST['hHabilitado'];
$hashForm = $_REQUEST['hashForm'];


$sql = "UPDATE FLUXO_DADOS SET
            JSN_FLUXO_EXPORT = '" . $jsn_fluxo_export . "',
            DES_ITENS = '" . $des_itens . "',
            DES_FLUXO_MODULOS = '" . $des_fluxo_modulos . "',
            COD_MODULOS = '" . $cod_modulos . "',
            COD_NODE = '" . $cod_node . "'
        WHERE COD_FLUXO = '" . $cod_fluxo . "'";

if (mysqli_query(conntemp($cod_empresa, ""), trim($sql))) {
    $response = array('success' => true, "sql" => $sql);
} else {
    $response = array('success' => false, 'error' => mysqli_error(conntemp($cod_empresa, "")), "sql" => $sql);
}

header('Content-Type: application/json');
echo json_encode($response);
