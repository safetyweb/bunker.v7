<?php
session_start();
/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
*/
$id_page = $_SERVER["REQUEST_URI"];

// pagina que será feito o redirect - colocado antes do include
// $url_index = "http://".$_SERVER["HTTP_HOST"]."/atendente.do?key=".$_GET["key"]."&".date("Ymdhis").round(microtime(true) * 1000);

function redirect($url){
    //REDIRECT PHP
    echo "<div style='display:none'>"; //oculta erros em tela caso estejam habilitados
    try {
        header("Location: ".$url);
    } catch (Exception $e) {
    }
    echo "</div>";
    

    //REDIRECT JS, EM CASO DE FALHA NO REDIRECT PHP
    echo "<script>window.location.href = \"".$url."\";</script>";
}


// SE SESSION NÃO EXISTE, É PQ EXPIROU OU É PRIMEIRO ACESSO. MANDA PARA O INICIO
if (!isset($_SESSION["PAGES_HIST"])){
    $_SESSION["PAGES_HIST"] = array();
    redirect($url_index);
    exit;
}

// SE SESSION NÃO É ARRAY, MANDA PARA O INICIO
if (!is_array(@$_SESSION["PAGES_HIST"])){
    $_SESSION["PAGES_HIST"] = array();
    redirect($url_index);
    exit;
}

// SE PAGINA ESTÁ NO SESSION, É PQ JÁ FOI VISITADA. USUARIO CLICOU EM VOLTAR. MANDA PARA O INICIO.
if (in_array($id_page,$_SESSION["PAGES_HIST"])){

    if ($id_page == end($_SESSION["PAGES_HIST"])){
        //SE CHEGOU AQUI, É PQ DEU REFRESH NA PAGINA
        //REFRESH É PERMITIDO
    }else{
        //SE CHEGOU AQUI, É PQ CLICOU EM VOLTAR
        //VOLTAR NÃO É PERMITIDO
        redirect($url_index);
        exit;
    }
}

// GRAVA PAGINA NO SESSION
$_SESSION["PAGES_HIST"][] = $id_page;
?>

