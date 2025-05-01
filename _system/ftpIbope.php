<?php

function ibopeftp($arraydados)
{
    ini_set("default_charset", "UTF-8");
    $ftp_server = "ftp.dtmmkt.com.br";
    $ftp_username   = "dtmsp\\effmail.marka";
    $ftp_password   =  "A9tavMvH3Cmp";


    $conn_id = ftp_connect($ftp_server) or die("could not connect to $ftp_server");

    // login
    if (@ftp_login($conn_id, $ftp_username, $ftp_password))
    {
       $msg='connectado';  
       $codmsgup=1;
    }
    else
    {
      $msg='erro ao connectado';     
      $codmsgup=2;
    }

    //$file = $_FILES["ftpdados"]["dados.cvs"];
    $local_arquivo = $arraydados['arqlocal']; // Localização (local)
    $ftp_pasta = '/ftpdtmmail/marka/'; // Pasta (externa)
    $ftp_arquivo = $arraydados['nomearq'] ; // Nome do arquivo (externo)
    ftp_pasv($conn_id, true); // habilitar o modo passivo do FTP...
    
    if (ftp_put($conn_id, $ftp_pasta.$ftp_arquivo, $local_arquivo,FTP_ASCII)) {
        $msgup='arquivo enviado';  
        $codmsg=3;
    } else {
         $msgup='falha no Upload';     
         $codmsg=4;
    }

     ftp_close($conn_id);

     return array('conexao_msg'=>$msg,
                  'id_conexao'=>$codmsgup,
                  'upload_msg'=>$msgup,
                  'uploadcod'=> $codmsg);
}
