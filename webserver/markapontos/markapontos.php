<?php
require_once './resgatepremio.php';
require_once './validacliente.php';

//'uri' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
//  'encoding'=>'UTF-8',
$options = array(
	'uri' =>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
        'location' => 'http://ws.bunker.mk/markapontos/markapontos.do',
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 
        'trace' => true,
        'soap_version' => SOAP_1_2,
        'style' => SOAP_DOCUMENT,
        'use' => SOAP_LITERAL
    );

$server = new SoapServer("./wsdl/markapontos.wsdl",$options);
$server->addFunction("resgatepremio");
$server->addFunction("validacliente");
 
 
//$server->handle(); 
ob_start();
$server->handle();
$soap = ob_get_contents();
ob_end_clean();
$soap = str_replace('ns1:', '', $soap);
$soap = str_replace(':ns1', '', $soap);
$soap = str_replace('mar:', '', $soap);
$soap = str_replace(':mar', '', $soap);

$length = strlen($soap);
header("Content-Length: ".$length);
echo $soap;
