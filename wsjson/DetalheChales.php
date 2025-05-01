<?php

$cod_quarto = 9999; // parametrizado

$sqlDesc = "SELECT DES_QUARTO, DES_IMAGEM, DES_VIDEO FROM ADORAI_CHALES 
            WHERE COD_EXTERNO = $cod_quarto";
$arrayDesc = mysqli_query(connTemp(274,''), $sqlDesc);
$qrDesc = mysqli_fetch_assoc($arrayDesc);

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

