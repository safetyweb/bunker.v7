<?php
set_time_limit(18000);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @autho Franklin de Paula Gonçalves <franklinpgoncalves@gmail.com>
 * @example Classe BunkerMK. 
 * EXEMPLO DE COMO INSERIR VENDAS NO BUNKER
 */

include_once '../config.php';
include_once '../BunkerMK.php';

$bunker = new BunkerMK();

//$bunker->getEstrutura('venda');

$venda['id_vendapdv'] = '5t';
$venda['datahora'] = date('Y-m-d H:i:s');
$venda['cartao'] = '0';
$venda['valortotal'] = '10,00';
$venda['cupom'] = '';
$venda['formapagamento'] = 'dinheiro';
$venda['cartaoamigo'] = '';
$venda['pontosextras'] = '';
$venda['naopontuar'] = '';
$venda['codvendedor'] = '1';
$venda['pontostotal'] = '';

// Inicio do loopping de produtos
$item['id_item'] = '1';
$item['produto'] = 'Produto de teste';
$item['codigoproduto'] = 'tst001';
$item['quantidade'] = '1';
$item['valor'] ='10,00';
$item['naopontuar'] = '';

$items['vendaitem'][] = $item;
// Fim do looping


$venda['items']= $items;

$dados['venda'] = $venda;
$retorno = $bunker->enviar($dados, 'InserirVenda');

//$bunker->getXmlEnvio();
//$bunker->getXmlRetorno();




