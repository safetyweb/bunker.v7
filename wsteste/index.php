<?php
ini_set("soap.wsdl_cache_enabled", "1");

$server = new SOAPServer('linker20.wsdl', array(
    'uri'=>'http://bunker.mk/wsteste/index.php',
    'soap_version' => SOAP_1_2,
    'style' => SOAP_DOCUMENT,
    'use' => SOAP_LITERAL
));
//$server->setObject($Service);
//$server->setClass('GetURLTktMania');
$server->handle();


class GetURLTktMania {
    var $CPFCARTAO;
    var $dadoslogin;    
}


echo $CPFCARTAO->GetURLTktMania;
?>
