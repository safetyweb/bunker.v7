<?php
require_once('lib/nusoap.php'); 

$server = new nusoap_server;
$server->debug_flag=false;
$server->configureWSDL('fidelidade', 'urn:fidelidade');
$server->soap_defencoding = 'utf-8';
$server->decode_utf8 = false;
$server->wsdl->schemaTargetNamespace = 'urn:fidelidade';


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
               'nomevendedor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomevendedor', 'type' => 'xsd:string')
            )
);
require_once 'ConsultaCadastroMK.php';
require_once 'InserirVendaMK.php';
require_once 'AtualizaCadastroMK.php';
require_once 'CadastrarProdutoMK.php';
require_once 'ListaProfissoesMK.php';
require_once 'consulta_cepMK.php';

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';

$server->service($HTTP_RAW_POST_DATA);

 
?>