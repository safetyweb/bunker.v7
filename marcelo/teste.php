<?php
require_once "CashTransactionResponse.php";
$options = array(
	'uri' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
        'location' => 'http://ws.bunker.mk/bridge/ws1/fidelidadebridge.do',
        'trace' => true,
        'encoding'=>'UTF-8',
        'soap_version' => SOAP_1_2
);
$server = new SoapServer("./wsdlmarcelo.wsdl",$options);
$server->addFunction("CashTransactionResponse");

ob_start();
$server->handle();
$soap = ob_get_contents();
ob_end_clean();
$length = strlen($soap);
header("Content-Length: ".$length);
echo $soap;
 