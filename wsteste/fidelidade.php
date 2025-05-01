<?php
require_once("./lib/nusoap.php");
$soapserver = new nusoap_server();
$soapserver->configureWSDL('thijs.test', 'urn:thijs.test');

$server->wsdl->addSimpleType('version','xsd:string','SimpleType','struct',array());
$server->wsdl->addSimpleType('idnumber','xsd:string','SimpleType','struct');

$server->wsdl->addComplexType('systemup','complexType','struct','all','',
    array(
		'version' => array('name' => 'version', 'type' => 'tns:version'),
		'idnumber' => array('name' => 'idnumber', 'type' => 'tns:idnumber'),
		'datetime' => array('name' => 'datetime', 'type' => 'xsd:dateTime')
    )
);

$server->register('systemup', array(
        'version' => 'tns:version', 
	'idnumber' => 'tns:idnumber',
	'datetime' => 'xsd:dateTime'), array(), $namespace, $soapaction."/systemup", 'rpc', 'encoded','Check if the system is up');

    function systemup (){
        
    }
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$soapserver->service($HTTP_RAW_POST_DATA);
?>