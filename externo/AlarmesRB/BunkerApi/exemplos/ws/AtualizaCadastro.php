<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @autho Franklin de Paula Gonçalves <franklinpgoncalves@gmail.com>
 * @example Classe BunkerMK. 
 * EXEMPLO DE COMO ATUALIZAR CADASTROS DE CLIENTES
 */

include_once '../../config.php';
include_once '../../BunkerMK.php';

$bunker = new BunkerMK();

$bunker->getEstrutura('FichadeCadastro');
/*
$cliente['cartao'] = '551140101080';
$cliente['tipocliente'] = 'PF';
$cliente['nome'] = 'Teste de Integração';
$cliente['cpf'] = '01235649644';
$cliente['cnpj'] = '';
$cliente['rg']= '';
$cliente['sexo']= 'M';
$cliente['datanascimento']='1980-01-01';
$cliente['estadocivil']='';
$cliente['email']='';
$cliente['dataalteracao']='';
$cliente['cartaotitular']='';
$cliente['dataalteracao']='';
$cliente['nomeportador']='';
$cliente['grupo']='';
$cliente['profissao']='';	
$cliente['clientedesde']='';
$cliente['endereco']='';
$cliente['numero']='';	
$cliente['complemento']='';
$cliente['bairro']='';
$cliente['cidade']='';	
$cliente['estado']='';
$cliente['cep']='';
$cliente['telresidencial']='';	
$cliente['telcelular']='';
$cliente['telcomercial']='';
$cliente['saldo']='';
$cliente['saldoresgate']='';
$cliente['msgerro']='';
$cliente['msgcampanha']='';
$cliente['url']='';		
$cliente['ativacampanha']='';
$cliente['dadosextras']='';
$cliente['bloqueado']='';
$cliente['motivo']='';	
$cliente['adesao']='';
$cliente['codatendente']='';
$cliente['senha']='';
$cliente['urlextrato']='';
$cliente['retornodnamais']='';

$dados['cliente'] = $cliente;
//$bunker->debug($dados);

$retorno = $bunker->enviar($dados, 'AtualizaCadastro');
//$bunker->debug($retorno);

//$bunker->getXmlEnvio();
$bunker->getXmlRetorno();

?>
