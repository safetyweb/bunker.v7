<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

//echo fnDebug('true');
////fnEscreve('Entra no ajax');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$cod_empresa = fnLimpaCampoZero($_GET['id']);
if(isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

////fnEscreve($cod_empresa);

switch($acao){

    case "gravar": //Rotina de gravação da planilha na tabela 'temporária'

        echo "aqui foi";
    
    }
