<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * @autho Franklin de Paula Gonçalves <franklinpgoncalves@gmail.com>
 * @example Classe BunkerMK. 
 * EXEMPLO DE COMO ATUALIZAR CADASTROS DE CLIENTES
 */

include_once '../config.php';
include_once '../BunkerMK.php';

$bunker = new BunkerMK();

$bunker->getEstrutura();

