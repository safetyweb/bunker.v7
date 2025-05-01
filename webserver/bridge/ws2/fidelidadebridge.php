<?php
    ini_set('default_charset', 'UTF-8');
require_once 'ConsultaCadastroPorCPF.php';
require_once 'AtualizaCadastro.php';
require_once 'CadastrarProduto.php';
require_once 'ConsultaCadastroPorCNPJ.php';
require_once 'ConsultaCadastroPorCartao.php';
require_once 'ConsultaFidelizadosMarka.php';
require_once 'EstornaVenda.php';
require_once 'EstornaVendaParcial.php';
require_once 'GetURLTktMania.php';
require_once 'InserirVenda.php';
require_once 'ListaProfissoes.php';
require_once 'VerificaVenda.php';
require_once 'ConfirmaAdesao.php';
require_once 'InserirOcorrencia.php';
require_once 'ConsultaPreVenda.php';
require_once 'CadastraFuncionario.php';
require_once 'TrocadeCartao.php';
require_once 'ValidaNumeroCartao.php';
require_once 'ListaTipoOcorrencia.php';
require_once 'InserirCreditoExtra.php';
require_once 'ResgatePremio.php';
require_once 'EstornoResgatePremio.php';
require_once 'AtualizaPremio.php';
require_once 'GiftCredito.php';
require_once 'GiftDebito.php';
require_once 'GiftVenda.php';
require_once 'GiftGetInfo.php';
require_once 'GiftGetConfig.php';

 

//'uri' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
//  'encoding'=>'UTF-8',
$options = array(
	'uri' =>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
        'location' => 'http://ws.bunker.mk/bridge/ws2/fidelidadebridge.do',
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS, 
        'trace' => true,
        'soap_version' => SOAP_1_2,
        'style' => SOAP_DOCUMENT,
        'use' => SOAP_LITERAL,
        'encoding' => 'UTF-8'
    );
$server = new SoapServer("../../WSLD/wsdl.wsdl",$options);
$server->addFunction("ConsultaCadastroPorCPF");
$server->addFunction("AtualizaCadastro");
$server->addFunction("CadastrarProduto");
$server->addFunction("ConsultaCadastroPorCNPJ");
$server->addFunction("ConsultaCadastroPorCartao");
$server->addFunction("ConsultaFidelizadosMarka");
$server->addFunction("EstornaVenda");
$server->addFunction("EstornaVendaParcial");
$server->addFunction("GetURLTktMania");
$server->addFunction("InserirVenda");
$server->addFunction("ListaProfissoes");
$server->addFunction("VerificaVenda");
$server->addFunction("ConfirmaAdesao");
$server->addFunction("InserirOcorrencia");
$server->addFunction("ConsultaPreVenda");
$server->addFunction("CadastraFuncionario");
$server->addFunction("TrocadeCartao");
$server->addFunction("ValidaNumeroCartao");
$server->addFunction("ListaTipoOcorrencia");
$server->addFunction("InserirCreditoExtra");
$server->addFunction("ResgatePremio");
$server->addFunction("EstornoResgatePremio");
$server->addFunction("AtualizaPremio");
$server->addFunction("GiftCredito");
$server->addFunction("GiftDebito");
$server->addFunction("GiftVenda");
$server->addFunction("GiftGetInfo");
$server->addFunction("GiftGetConfig");
 
//$server->handle(); 
ob_start();
$server->handle();
$soap = ob_get_contents();
ob_end_clean();
$soap = str_replace('ns1:', '', $soap);
$soap = str_replace(':ns1', '', $soap);
$length = strlen($soap);
header("Content-Length: ".$length);
echo $soap;
