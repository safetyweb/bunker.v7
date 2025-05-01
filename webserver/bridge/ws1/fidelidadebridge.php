<?php
require_once "ConsultaCadastro.php";
require_once 'AtualizaCadastro.php';
require_once 'CadastrarProduto.php';
require_once 'EstornaVenda.php';
require_once 'EstornaVendaParcial.php';
require_once 'GetURLTktMania.php';
require_once 'InserirVenda.php';
require_once 'ListaProfissoes.php';
require_once 'VerificaVenda.php';
require_once 'TrocadeCartao.php';
$options = array(
	'uri' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
        'location' => 'http://ws.bunker.mk/bridge/ws1/fidelidadebridge.do',
        'trace' => true,
        'encoding'=>'UTF-8',
        'soap_version' => SOAP_1_2
);

$server = new SoapServer("../../WSLD/ws1.wsdl",$options);
$server->addFunction("ConsultaCadastro");
$server->addFunction("AtualizaCadastro");
$server->addFunction("CadastrarProduto");
$server->addFunction("EstornaVenda");
$server->addFunction("EstornaVendaParcial");
$server->addFunction("GetURLTktMania");
$server->addFunction("InserirVenda");
$server->addFunction("ListaProfissoes");
$server->addFunction("VerificaVenda");
$server->addFunction("TrocadeCartao");

ob_start();
$server->handle();
$soap = ob_get_contents();
ob_end_clean();
$soap = str_replace('ns1:', '', $soap);
$soap = str_replace(':ns1', '', $soap);
$soap = str_replace(':ns1', '', $soap);
$soap = str_replace('<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns="Linker20">','<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">', $soap);
$soap = str_replace('<SOAP-ENV:Body>','<soap:Body>', $soap); 
$soap = str_replace('</SOAP-ENV:Body></SOAP-ENV:Envelope>','</soap:Body></soap:Envelope>', $soap);
$soap = str_replace('<ConsultaCadastroResponse>',  '<ConsultaCadastroResponse xmlns="Linker20">', $soap);

$soap = str_replace('<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns="Linker20">','<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">', $soap);
$soap = str_replace('<SOAP-ENV:Body>','<soap:Body>', $soap); 
$soap = str_replace('</SOAP-ENV:Body></SOAP-ENV:Envelope>','</soap:Body></soap:Envelope>', $soap);
$soap = str_replace('<AtualizaCadastroResponse>',  '<AtualizaCadastroResponse xmlns="Linker20">', $soap);
$length = strlen($soap);
header("Content-Length: ".$length);
echo $soap;
 