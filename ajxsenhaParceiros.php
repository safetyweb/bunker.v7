<?php
include '_system/_functionsMain.php'; 
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$cod_senhaparc = fnLimpaCampoZero($_REQUEST['COD_SENHAPARC']);
$des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
$des_authkey2 = fnLimpaCampo($_REQUEST['DES_AUTHKEY2']);
$des_usuario = fnLimpaCampo($_REQUEST['DES_USUARIO']);
$des_cliext = fnLimpaCampoZero($_REQUEST['DES_CLIEXT']);
$cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
$msg_retorno = fnLimpaCampo($_REQUEST['MSG_RETORNO']);
$cod_usucada = fnLimpaCampo($_REQUEST['COD_USUCADA']);
$des_usuario = fnLimpaCampo($_REQUEST['DES_USUARIO']);
$cod_listaext = fnLimpaCampoZero($_REQUEST['COD_LISTAEXT']);

$opcao =fnlimpaCampo($_GET['opcao']);
$itens_por_pagina = fnlimpaCampo($_GET['itens_por_pagina']);
$pagina =fnlimpaCampo($_GET['idPage']);
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$andFiltro =fnlimpaCampo($_REQUEST['AND_FILTRO']);



switch($opcao){
    
    case 'paginar':

        $sql = "SELECT 1 from SENHAS_PARCEIRO WHERE 1=1
        $andFiltro
        ";

    $retorno =mysqli_query($connAdm->connAdm(), $sql);
    $totalitens_por_pagina = mysqli_num_rows($retorno);
    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

    // fnEscreve($numPaginas);

    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        
    $sql = "SELECT SENHAS_PARCEIRO.*,
                EMPRESAS.NOM_FANTASI 
            from SENHAS_PARCEIRO
            left join empresas ON SENHAS_PARCEIRO.COD_EMPRESA = empresas.COD_EMPRESA
            WHERE 1=1
            $andFiltro
            LIMIT $inicio,$itens_por_pagina";

    //echo($sql);
    
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);


    $count = 0;
    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

    $count++;
    $tipo="";
   
    
        echo"
            <tr>
                <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                <td>" . $qrBuscaModulos['COD_SENHAPARC'] . "</td>
                <td>" . $qrBuscaModulos['NOM_FANTASI'] . "</td>
                <td>" . $qrBuscaModulos['COD_EMPRESA'] . "</td>
                <td>" . $qrBuscaModulos['DES_USUARIO'] . "</td>
                <td>" . $qrBuscaModulos['MSG_RETORNO'] . "</td>
            </tr>

            <input type='hidden' id='ret_COD_SENHAPARC_" . $count . "' value='" . $qrBuscaModulos['COD_SENHAPARC'] . "'>
            <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
            <input type='hidden' id='ret_COD_PARCOMU_" . $count . "' value='" . $qrBuscaModulos['COD_PARCOMU'] . "'>
            <input type='hidden' id='ret_DES_USUARIO_" . $count . "' value='" . $qrBuscaModulos['DES_USUARIO'] . "'>
            <input type='hidden' id='ret_DES_AUTHKEY_" . $count . "' value='" . $qrBuscaModulos['DES_AUTHKEY'] . "'>
            <input type='hidden' id='ret_DES_AUTHKEY2_" . $count . "' value='" . $qrBuscaModulos['DES_AUTHKEY2'] . "'>
            <input type='hidden' id='ret_DES_CLIEXT_" . $count . "' value='" . $qrBuscaModulos['DES_CLIEXT'] . "'>
            <input type='hidden' id='ret_COD_LISTAEXT_" . $count . "' value='" . $qrBuscaModulos['COD_LISTAEXT'] . "'>
            <input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaModulos['LOG_ATIVO'] . "'>
            ";
    }
    break;
}