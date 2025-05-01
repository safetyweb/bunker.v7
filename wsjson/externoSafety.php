<?php
include '../_system/_functionsMain.php';
header("Content-Type: application/json; charset=utf-8");

$tipo = $_GET['tipo'];

$cod_empresa = 2;

switch ($tipo) {

    case '1': // envio de email

        // echo json_encode("teste service");
        
        $nome = base64_decode(fnLimpacampo($_REQUEST['nome']));
        $fromMail = base64_decode(fnLimpacampo($_REQUEST['fromMail']));
        $mensagem = base64_decode(fnLimpacampo($_REQUEST['mensagem']));
        $telefone = base64_decode(strtoupper(fnLimpacampo($_REQUEST['telefone'])));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';

        $texto='NOME: '.$nome.
        '<br>EMAIL: '.$fromMail.       
        '<br>TELEFONE: '.$telefone.       
        '<br>MENSAGEM:<br>'.$mensagem;

        $email['email1'] = 'adilson.silva@safetyweb.com.br';

        $retorno = fnsacmail(
                              $email,
                              'Suporte Marka',
                              "<html>".$texto."</html>",
                              "[$nome] Fale Conosco - Safety Digital",
                              "Safety Digital",
                              $connAdm->connAdm(),
                              connTemp($cod_empresa,""),$cod_empresa
                            );

        // echo "<pre>";
        // print_r($retorno);
        // echo "</pre>";

        echo json_encode($retorno);

    break;

    

}
