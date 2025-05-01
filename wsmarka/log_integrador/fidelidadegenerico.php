<?php
require_once('../lib/nusoap.php'); 

$server = new soap_server();
$ns  = 'fidelidadegenerico'; //or any test url
$ns1  = 'fidelidadegenerico';
//$server->debug_flag=false;
$server->configureWSDL('fidelidadegenerico', $ns1, false, 'document');
$server->soap_defencoding = 'utf-8';
$server->encode_utf8 = true;
$server->decode_utf8 = false;
$server->wsdl->schemaTargetNamespace = $ns1;
//$server->xml_encoding = "utf-8";
$server->wsdl->addComplexType(
    'LoginInfo',
    'complexType',
    'struct',
    'all',
    '',
         array('login' => array('name' => 'login', 'type' => 'xsd:string'),
               'senha' => array('name' => 'senha', 'type' => 'xsd:string'),
               'idloja' => array('name' => 'idloja', 'type' => 'xsd:string'),
               'idmaquina' => array('name' => 'idmaquina', 'type' => 'xsd:string'),
               'idcliente' => array('name' => 'idcliente', 'type' => 'xsd:string'),
               'codvendedor' => array('name' => 'codvendedor', 'type' => 'xsd:string'),
               'nomevendedor' => array('name' => 'nomevendedor', 'type' => 'xsd:string')
            )
);
include './Consultavendawsdl.php';
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>