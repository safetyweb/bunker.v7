<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @autho Franklin de Paula Gonçalves <franklinpgoncalves@gmail.com>
 * @example Classe BunkerMK. 
 * EXEMPLO DE COMO INSERIR VENDAS NO BUNKER
 */

include_once '../../config.php';
include_once '../../BunkerMK.php';

$bunker = new BunkerMK();

//$bunker->getEstrutura('login');
$dados['fase'] = 4;

$venda['id_vendapdv'] = '5t';
$venda['datahora'] = date('Y-m-d H:i:s');
$venda['cartao'] = '551140101080';
$venda['valortotalbruto'] = '165';
$venda['valortotalliquido'] = '';
$venda['valor_resgate'] = '';
$venda['cupom'] = '';
$venda['formapagamento'] = 'ponto';
$venda['indicador'] = '';
$venda['codatendente'] = '';
$venda['codvendedor'] = '';
$venda['idcliente'] = '';

// Inicio do loopping de produtos
$item['id_item'] = '1';
$item['produto'] = 'Produto de teste';
$item['codigoproduto'] = '551140101080';
$item['quantidade'] = '1';
$item['valor'] ='165';
$item['naopontuar'] = '';

$genereico['param1'] = NULL;
$genereico['param2'] = NULL;
$item['envioGenerico'] = $genereico;

$items['vendaitem'][] = $item;

// Fim do looping

$venda['itens']= $items;
$dados['venda'] = $venda;



//$bunker->debug($dados);

$retorno = $bunker->enviar($dados, 'InsereVenda');
//$bunker->debug($retorno);
if($retorno->InsereVendaResponse->msgerro){
    echo '<br>id_vendapdv: '.$dados['venda']['id_vendapdv'].'   Erro: '.$retorno->InsereVendaResponse->msgerro;
}

//$bunker->getXmlEnvio();
//$bunker->getXmlRetorno();




