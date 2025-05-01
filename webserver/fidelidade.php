<?php
require_once('lib/nusoap.php'); 


$server = new soap_server();
$ns1  = 'bunker.mk';
$ns='Linker20';
//$server->debug_flag=false;
$server->configureWSDL($ns1, 'Linker20', false, 'document');
$server->soap_defencoding = 'utf-8';
$server->encode_utf8 = true;
$server->decode_utf8 = false;


$server->wsdl->schemaTargetNamespace = $ns;
$server->wsdl->addComplexType(
    'LoginInfo',
    'complexType',
    'struct',
    'sequence',
    '',
         array('login' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'login', 'type' => 'xsd:string'),
               'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
               'idloja' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idloja', 'type' => 'xsd:string'),
               'idmaquina' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idmaquina', 'type' => 'xsd:string'),
               'idcliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'idcliente', 'type' => 'xsd:string'),
               'codvendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codvendedor', 'type' => 'xsd:string'),
               'nomevendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomevendedor', 'type' => 'xsd:string'),
               'rawdata' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'rawdata', 'type' => 'xsd:string')
            )
);



require_once 'AtualizaCadastro.php';
require_once 'ConsultaCadastroPorCPF.php';
require_once 'InserirVenda.php';
require_once 'ListaProfissoes.php';
require_once 'ConsultaCadastroPorCNPJ.php';
require_once 'CadastrarProduto.php';
require_once 'GetURLTktMania.php';
require_once 'ConsultaCadastroPorCartao.php';
require_once 'EstornaVenda.php';
require_once 'EstornaVendaParcial.php';
require_once 'GetProdutosTicket.php';
require_once 'listacupom.php';
require_once 'ConsultaFidelizadosMarka.php';

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

 
?>