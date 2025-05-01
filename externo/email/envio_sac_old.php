<?php

function fnsacmail($email, $nome, $texto, $Subject, $FromName, $conAdm, $conntemp, $cod_empresa, $caminhoanexo = FALSE, $nom_arquivo = FALSE)
{

    $confSmtp = "SELECT * from SENHAS_SMTP WHERE
                                              case when cod_empresa=3 then cod_empresa
                                                   when cod_empresa=$cod_empresa then cod_empresa END IN (cod_empresa)
                                                and LOG_ATIVO='S' ORDER BY COD_SMTP DESC  LIMIT 1";


    //busca de envendo e configuração smtp
    /* $confSmtp="SELECT * from SENHAS_SMTP WHERE cod_empresa=$cod_empresa and LOG_ATIVO='S' ORDER BY RAND() LIMIT 1";*/


    $rsSmtp = mysqli_query($conAdm, $confSmtp);
    while ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {

        $DES_EMAIL = !empty($resultSmtp['DES_FROM']) ? $resultSmtp['DES_FROM'] : $resultSmtp['DES_EMAIL'];
        $DES_USER = $resultSmtp['DES_EMAIL'];
        $DES_PORT = $resultSmtp['DES_PORT'];
        $DES_CERTIFICADO = $resultSmtp['DES_CERTIFICADO'];
        $TIP_DEBUG = $resultSmtp['TIP_DEBUG'];
        $DES_SENHA = $resultSmtp['DES_SENHA'];
        $DES_SMTP = $resultSmtp['DES_SMTP'];

        // Inicia a classe PHPMailer 
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "$DES_SMTP";
        $mail->Port = "$DES_PORT";
        $mail->SMTPSecure = "$DES_CERTIFICADO";
        $mail->SMTPAuth = true;
        $mail->Timeout = 30;
        $mail->Username = "$DES_USER";
        $mail->Password = "$DES_SENHA";
        $mail->SMTPDebug = "$TIP_DEBUG";
        $mail->From = "$DES_EMAIL";
        $mail->FromName = "$FromName";

        // Adiciona os emails
        $mail->AddAddress($email['email1'], "$nome");
        $mail->AddAddress(isset($email['email2']) ? $email['email2'] : '', "");
        $mail->AddAddress(isset($email['email3']) ? $email['email3'] : '', "");
        $mail->AddAddress(isset($email['email4']) ? $email['email4'] : '', "");


        // Verifica se email6 existe antes de explodir
        $mailarray6 = !empty($email['email6']) ? explode(';', $email['email6']) : [];
        foreach ($mailarray6 as $dados6) {
            $mail->AddAddress($dados6, "$nome");
        }

        // Verifica se email5 existe antes de explodir
        $mailarray = !empty($email['email5']) ? explode(';', $email['email5']) : [];
        foreach ($mailarray as $dados) {
            $mail->AddCC($dados, "");
        }

        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Priority = 3;
        $mail->Subject = "$Subject";
        $mail->Body = "$texto";

        // Adiciona anexo se existir
        if (!empty($caminhoanexo) && !empty($nom_arquivo)) {
            $mail->AddAttachment("$caminhoanexo", "$nom_arquivo");
        }

        // Envia o e-mail 
        $enviado = $mail->Send();

        // Registra log no banco de dados
        $msg = $enviado ? "Seu email foi enviado com sucesso!" : "Houve um erro enviando o email: " . $mail->ErrorInfo;
        $logOK = "INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG) 
              VALUES ('$cod_empresa', '" . $email['email1'] . "', '" . date('Y-m-d H:i:s') . "', '$msg')";
        mysqli_query($conntemp, $logOK);
    }




    if (mysqli_num_rows($rsSmtp) == false) {
        $logOK = " INSERT INTO log_email (COD_EMPRESA, EMAIL, DATA_HORA, MSG) 
                            VALUES ('$cod_empresa', 
                                    '" . $email['email1'] . "', 
                                    '" . date('Y-m-d H:i:s') . "', 
                                    'Conta SMTP não cadastrada ou iniciada!')";
        mysqli_query($conntemp, $logOK);
    }
    $array = array();
    return array(
        'sql' => $confSmtp,
        'SQLLOG' => $logOK,
        'msg' => @$msg,
        'COnn' => $conntemp,
        'array' => $array
    );
}
/*
if($_REQUEST['emp']=='11')
{    
    include '../../_system/_functionsMain.php'; 
    $cod_empresa='11';
    $emailDestino = array('email1'=>'diogo_tank@hotmail.com');
    $aqui=fnsacmail(
                            $emailDestino,
                            "Suporte Marka",
                            "</html>teste</html>",
                            "[TESTE] A Loja 18 tem um presente para você!!",
                            "Teste de Envio",
                            $connAdm->connAdm(),
                            connTemp($cod_empresa,""),"11");
    echo '<pre>';
    print_r($aqui);
    echo '</pre>';
} 
 */