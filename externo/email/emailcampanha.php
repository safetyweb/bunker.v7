<?php

include '../../_system/_functionsMain.php'; 
 include "../../_system/EMAIL/PHPMailer/PHPMailerAutoload.php"; 

 function fncaracter($string)
{
   // matriz de entrada
    $what = array( '"','!','@','#','$','%','¨','&','*','(',')','_','-','+','=','[',']','{','}','º','^','~',':',';','.','>','<',',','?','/','|','/','...','..');

    // matriz de saída
    $by   = array( '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');

    // devolver a string
    return str_replace($what, $by, $string);
       
}
  ob_start(); 
$conAdm=$connAdm->connAdm(); 
//verificar a configuração
$verificar="select * from webhook WHERE TIP_WEBHOOK=4 AND LOG_ESTATUS='S'";
$rsverif=mysqli_query($conAdm, $verificar);
while ($dadosverif=mysqli_fetch_assoc($rsverif))
{ 

    if($_GET['id']=='')
    {
         $cod_empresa=$dadosverif['COD_EMPRESA'];
    }else{
         $cod_empresa=$_GET['id'];  
    } 
    $conTemp= connTemp($cod_empresa, '');
  
        //busca de envendo e configuração smtp
        $confSmtp="SELECT * from SENHAS_SMTP WHERE LOG_ATIVO='S' and cod_empresa=".$cod_empresa." ORDER BY RAND()*".date('s')." LIMIT 1";       
        $rsSmtp=mysqli_query($conAdm, $confSmtp);
        if(mysqli_num_rows($rsSmtp)<=0)
        {
            echo 'SMTP DESABILITADO';
            exit();
        }    
        while ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {              
                 
            $clienteenvio="SELECT * FROM envio_simples_email WHERE cod_empresa=".$cod_empresa." limit 15";
            $rsdado= mysqli_query($conTemp, $clienteenvio);
            
            while ($clinters= mysqli_fetch_assoc($rsdado))
            { 
               
                if($clinters['COD_SEXOPES']=='1')
                {
                    $sexo='Masculino';
                }elseif ($clinters['COD_SEXOPES']=='2') {
                    $sexo='Feminino';
                }else{
                    $sexo='';
                }    
               $primeiro_nome= explode(" ",  fncaracter($clinters['NOM_CLIENTE']));
               //loja que o cliente participa
               $sqlunidade="SELECT NOM_FANTASI,DES_ENDEREC,NUM_ENDEREC,DES_BAIRROC,NUM_TELEFON from unidadevenda where cod_empresa=".$cod_empresa." and cod_univend=".$clinters['COD_UNIVEND']; 
               $rsnom_univenda=mysqli_fetch_assoc(mysqli_query($conAdm, $sqlunidade));
               //@endereco,  @numero 
               
               
               
                $model_email="SELECT * FROM modelo_email 
                              WHERE cod_modelo='".$clinters['COD_MODELO']."' AND 
                                    cod_empresa=".$cod_empresa;
                $rsmodelo=mysqli_fetch_assoc(mysqli_query($conTemp, $model_email)); 
                    
                    $DES_TEMPLATE=html_entity_decode($rsmodelo['DES_TEMPLATE']);
                    $DES_TEMPLATE1=str_replace('#NOME',$primeiro_nome[0],$DES_TEMPLATE);
                    $DES_TEMPLATE1=str_replace('#CUPOMSORTEIO',$cupom,$DES_TEMPLATE1);
                    $DES_TEMPLATE1=str_replace('#SEXO',$sexo,$DES_TEMPLATE1);                    
                    
                    $DES_TEMPLATE1=str_replace('#NOMELOJA',$rsnom_univenda['NOM_FANTASI'],$DES_TEMPLATE1);
                    $DES_TEMPLATE1=str_replace('#ENDERECOLOJA',$rsnom_univenda['DES_ENDEREC'],$DES_TEMPLATE1);
                    $DES_TEMPLATE1=str_replace('#NUMEROLOJA',$rsnom_univenda['NUM_ENDEREC'],$DES_TEMPLATE1);
                    $DES_TEMPLATE1=str_replace('#BAIRROLOJA',$rsnom_univenda['DES_BAIRROC'],$DES_TEMPLATE1);
                    $DES_TEMPLATE1=str_replace('#TELEFONELOJA',$rsnom_univenda['NUM_TELEFON'],$DES_TEMPLATE1);
                    //assunto pegando variavel
                    
                    $DES_ASSUNTO=str_replace('#NOME',$primeiro_nome[0],$rsmodelo['DES_ASSUNTO']);

                   // Inicia a classe PHPMailer 
                   $mail = new PHPMailer(); 

                       $DES_PORT=$resultSmtp['DES_PORT'];
                       $DES_CERTIFICADO=$resultSmtp['DES_CERTIFICADO'];
                       $TIP_DEBUG=$resultSmtp['TIP_DEBUG'];
                       $DES_EMAIL=$resultSmtp['DES_EMAIL'];
                       $DES_SENHA=$resultSmtp['DES_SENHA'];
                       $DES_SMTP=$resultSmtp['DES_SMTP'];
                       $email_add=$emailcliente['DES_EMAILUS'];
                       $nom_cli=$emailcliente['NOM_CLIENTE'];
                   // Método de envio 
                   $mail->IsSMTP(); 

                   // Enviar por SMTP 
                   //$mail->Host = "smtp.gmail.com"; 
                   $mail->Host = "$DES_SMTP";
                   // Você pode alterar este parametro para o endereço de SMTP do seu provedor 
                   $mail->Port = $DES_PORT; 
                   $mail->SMTPSecure = "$DES_CERTIFICADO"; 

                   // Usar autenticação SMTP (obrigatório) 
                   $mail->SMTPAuth = true; 

                   // Usuário do servidor SMTP (endereço de email) 
                   // obs: Use a mesma senha da sua conta de email 
                   $mail->Username = "$DES_EMAIL"; 
                   $mail->Password = "$DES_SENHA"; 
                   // Configurações de compatibilidade para autenticação em TLS 
                   //$mail->SMTPOptions = array( 'TLS' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) ); 
                   $mail->SMTPDebug = $TIP_DEBUG;
                   //$mail->SMTPDebug = 0;
                   $mail->From = "$DES_EMAIL"; 
                   $mail->FromName = $rsmodelo['DES_REMET']; 

                   $mail->AddAddress($clinters['DES_EMAILUS'], $clinters['NOM_CLIENTE']); 
                   //$mail->AddAddress("diogo_tank@hotmail.com", "depyl");  
                   //$mail->AddCC('rone.all@gmail.com', 'rone'); 
                   // $mail->AddBCC('roberto@gmail.com', 'Roberto');  
                   $mail->IsHTML(true);  
                   // Charset (opcional) 
                   $mail->CharSet = 'UTF-8';  
                   // Assunto da mensagem 
                   $mail->Subject = "$DES_ASSUNTO";  
                   // Corpo do email 
                   $mail->Body = "<html>".$DES_TEMPLATE1."</html>";  
                   // Opcional: Anexos 
                   // $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf");  
                   // Envia o e-mail 
                   $enviado = $mail->Send();  

                   // Exibe uma mensagem de resultado 
                   if ($enviado) 
                   { 
                       echo "<br>Seu email foi enviado com sucesso!<br>"; 
                       //inserir no registro OK
                       $insertOK="insert into enviado_simples_email ()
                                 select * from envio_simples_email where 
                                 cod_cliente='".$clinters['COD_CLIENTE']."' and cod_empresa=".$cod_empresa;
                       
                       $rsins=mysqli_query($conTemp, $insertOK);
                       if($rsins)
                       {
                          $rsdel="DELETE FROM envio_simples_email WHERE   
                                                           cod_cliente='".$clinters['COD_CLIENTE']."' and cod_empresa=".$cod_empresa;
                          $rsdel=mysqli_query($conTemp, $rsdel);
                          if($rsdel)
                          {
                            echo '<br>Deletado '.$clinters['COD_CLIENTE'].' com Sucesso<br>';      
                          }    
                       }else{
                           echo "<br>PROBLEMA NO INSERT LOG <br>";
                         
                       }    
                       
                       
                      

                   } else { 

                      echo "<br>Houve um erro enviando o email: ".$mail->ErrorInfo."<br>"; 
                      $email=$clinters['DES_EMAILUS'];
                      
                            if(!ereg('^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})',"$email")){

                              $insertOK="insert into envio_simples_recusado ()
                                         select * from envio_simples_email where 
                                         cod_cliente='".$clinters['COD_CLIENTE']."' and cod_empresa=".$cod_empresa;

                               $rsins=mysqli_query($conTemp, $insertOK);
                               if($rsins)
                               {

                                   if(!ereg('^([a-zA-Z0-9.-_])*([@])([a-z0-9]).([a-z]{2,3})',"$email")){

                                    $rsdel="DELETE FROM envio_simples_email WHERE   
                                                 cod_cliente='".$clinters['COD_CLIENTE']."' and cod_empresa=".$cod_empresa;
                                         $rsdel=mysqli_query($conTemp, $rsdel);
                                        if($rsdel)
                                        {
                                          echo '<br>email PROB<EMATICO Deletado '.$clinters['COD_CLIENTE'].' com Sucesso<br>';      
                                        }
                                    }else{

                                    }


                               } 
                           }   
                       //================

                   }                
                ob_end_flush();  
            }    
        }

         echo '<br>FIM do LOOPING configuracao do SMTP <br> <br>'; 
   
}