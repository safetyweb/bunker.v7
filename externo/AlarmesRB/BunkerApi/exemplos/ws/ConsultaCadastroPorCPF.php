<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../config.php';
include_once '../BunkerMK.php';

$bunker = new BunkerMK();

//$bunker->getEstrutura('ConsultaCadastroPorCPF');

$dados['CPF'] ='01235649644';
$retorno = $bunker->enviar($dados, 'ConsultaCadastroPorCPF');
//$bunker->debug($retorno);

//$bunker->getXmlEnvio();
$bunker->getXmlRetorno();
