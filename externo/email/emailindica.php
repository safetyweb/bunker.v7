<?php
/*
include '../../_system/_functionsMain.php'; 

$conAdm=$connAdm->connAdm();
 
//busca de envendo e configuração smtp
$confSmtp="SELECT * from SENHAS_SMTP WHERE LOG_ATIVO='S'";
$rsSmtp=mysqli_query($conAdm, $confSmtp);
while ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {
    //CONFIGURAÇÕES DE VARIAVEIS
   
    
    
   echo 'Entrou no LOOPING configuracao do SMTP';
        $cod_empresa=$resultSmtp['COD_EMPRESA'];
        //Conexão Ao cliente   
        $conTemp=connTemp($cod_empresa,''); 

        //capturar o cliente,tamplate codigo gerado  para o sorteio
        //while de cartoes gerados
        $cartaogerado="SELECT COD_CLIENTE,COD_VENDA from geracupom  WHERE COD_EMPRESA=$cod_empresa AND 
                                                      COD_VENDA > 0 AND 
                                                      COD_CLIENTE > 0 AND 
                                                      LOG_INDICA=1    AND
                                                      EMAIL_ENVIADO=0 group by cod_cliente";
        $rscartaogerado= mysqli_query($conTemp, $cartaogerado);
        while ($rwcartoesgerado = mysqli_fetch_assoc($rscartaogerado)) {
                     
            $COD_CLIENTE=$rwcartoesgerado['COD_CLIENTE'];
            
            //looping para pegar a lista de cupons do cliente
            $cartaogeradolist="SELECT * from geracupom  WHERE COD_EMPRESA=$cod_empresa AND 
                                                     COD_VENDA > 0 AND 
                                                     COD_CLIENTE > 0 AND 
                                                     LOG_INDICA=1   AND
                                                     EMAIL_ENVIADO=0 and 
                                                     cod_cliente=".$COD_CLIENTE;
           $rscartaogeradolist= mysqli_query($conTemp, $cartaogeradolist);
           while ($rwlist = mysqli_fetch_assoc($rscartaogeradolist)) {
                @$cupom.=$rwlist['NUM_CUPOM'].',';               
           }
           //fim do acumulo de cupons do clientes
            $cupom=trim ($cupom, ",");                       

                                    echo 'Entrou no while de busca de cliente';
                                    
                                    //aqui vou ter cod_cliente
                                    //buscar o email do cliente na table de clientes
                                    $buscaEmail="select DES_EMAILUS,NOM_CLIENTE from clientes where cod_cliente=$COD_CLIENTE";
                                    $emailcliente=mysqli_fetch_assoc(mysqli_query($conTemp, $buscaEmail));
                                    //verifuca se tem email para envio
                                    if($emailcliente['DES_EMAILUS']!=''){
                                        //todo envio acontece aqui dentro  
                                       $MODELOCOMUNICACAO= "SELECT M.COD_MODMAIL from COMUNICACAO_MODELO M
                                                            LEFT JOIN CAMPANHA C ON C.COD_CAMPANHA= M.COD_CAMPANHA
                                                            WHERE M.COD_EMPRESA=$cod_empresa AND M.COD_TIPCOMU  AND M.COD_EXCLUSA = 0  AND C.TIP_CAMPANHA=20 and COD_COMUNICACAO=4"; 
                                        $rsmodelocomu= mysqli_fetch_assoc(mysqli_query($conTemp, $MODELOCOMUNICACAO));
                                            if($rsmodelocomu['COD_MODMAIL']!='')
                                            {
                                                    //pegar o modelo de email

                                                    $modeloevio="SELECT * FROM modelo_email WHERE COD_MODELO=".$rsmodelocomu['COD_MODMAIL']." AND 
                                                                                                  cod_empresa=$cod_empresa";
                                                    
                                                    $rsmodeloenvio=mysqli_fetch_assoc(mysqli_query($conTemp, $modeloevio));
                                                    $DES_TEMPLATE=html_entity_decode($rsmodeloenvio['DES_TEMPLATE']);
                                                    $DES_TEMPLATE1=str_replace('%nome%',$emailcliente['NOM_CLIENTE'],$DES_TEMPLATE);
                                                    $DES_TEMPLATE1=str_replace('%data%',$cupom,$DES_TEMPLATE1);

                                                    //função de envio


                                                    //require '../_system/PHPMailer/class.phpmailer.php';
                                                     include "../../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";  

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
                                                    $mail->FromName = "Depyl Action"; 

                                                    $mail->AddAddress("$email_add", "$nom_cli"); 
                                                    //$mail->AddAddress("diogo_tank@hotmail.com", "depyl");  
                                                    //$mail->AddCC('rone.all@gmail.com', 'rone'); 
                                                    // $mail->AddBCC('roberto@gmail.com', 'Roberto');  
                                                    $mail->IsHTML(true);  
                                                    // Charset (opcional) 
                                                    $mail->CharSet = 'UTF-8';  
                                                    // Assunto da mensagem 
                                                    $mail->Subject = "Depyl Action";  
                                                    // Corpo do email 
                                                    $mail->Body = "<html>".$DES_TEMPLATE1."</html>";  
                                                    // Opcional: Anexos 
                                                    // $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf");  
                                                    // Envia o e-mail 
                                                    $enviado = $mail->Send();  
                                                    
                                                    // Exibe uma mensagem de resultado 
                                                    if ($enviado) 
                                                    { 
                                                        echo "Seu email foi enviado com sucesso!"; 
                                                      
                                                        //LOG_ENVIO OK
                                                        $inlogOK="INSERT INTO log_envioemail (
                                                                                              COD_EMPRESA, 
                                                                                              COD_CLIENTE, 
                                                                                              MSG_ENVIO,
                                                                                              COD_CUPOM,
                                                                                              COD_VENDA) 
                                                                                              VALUES
                                                                                              ('$cod_empresa', 
                                                                                              '$COD_CLIENTE', 
                                                                                              'OK',
                                                                                              '".$cupom."',
                                                                                              '".$rwcartoesgerado['COD_VENDA']."');";
                                                        
                                                       mysqli_query($conTemp, $inlogOK);
                                                      
                                                         $UPdate="UPDATE geracupom SET EMAIL_ENVIADO='1'
                                                                                                 WHERE 
                                                                                                COD_VENDA='".$rwcartoesgerado['COD_VENDA']."' AND 
                                                                                                        COD_EMPRESA=$cod_empresa AND 
                                                                                                        COD_CLIENTE=$COD_CLIENTE";
                                                        
                                                        mysqli_query($conTemp, $UPdate);
                                                        
                                                    } else { 
                                                        //email erro de envio
                                                        $inlogerro="INSERT INTO log_envioemail (
                                                                                              COD_EMPRESA, 
                                                                                              COD_CLIENTE, 
                                                                                              MSG_ENVIO,
                                                                                              COD_CUPOM,
                                                                                              COD_VENDA) 
                                                                                              VALUES
                                                                                              ('$cod_empresa', 
                                                                                              '$COD_CLIENTE', 
                                                                                              '".addslashes($mail->ErrorInfo)."',
                                                                                              '".$cupom."',
                                                                                              '".$rwcartoesgerado['COD_VENDA']."');";
                                                        mysqli_query($conTemp, $inlogerro);                                                       
                                                        echo "Houve um erro enviando o email: ".$mail->ErrorInfo; 
                                                        
                                                    } 
                                                     
                                                    //fim do envio
                                            }else{
                                                echo 'Nao Existe comunicacao modelo';
                                            }    
                                    }else{
                                       //email erro de envio
                                                        $inlonoemail="INSERT INTO log_envioemail (
                                                                                              COD_EMPRESA, 
                                                                                              COD_CLIENTE, 
                                                                                              MSG_ENVIO,
                                                                                              COD_CUPOM,
                                                                                              COD_VENDA) 
                                                                                              VALUES
                                                                                              ('$cod_empresa', 
                                                                                              '$COD_CLIENTE', 
                                                                                              'Cliente nao tem email cadastrado',
                                                                                              '".$cupom."',
                                                                                               '".$rwcartoesgerado['COD_VENDA']."' );";
                                                         mysqli_query($conTemp, $inlonoemail);                                                          
                                      echo 'cod_cliente '.$COD_CLIENTE.' nao tem email <br> <br>'; 
                                      $UPdate="UPDATE geracupom SET EMAIL_ENVIADO='1'
                                                                                                 WHERE 
                                                                                                COD_VENDA='".$rwcartoesgerado['COD_VENDA']."' AND 
                                                                                                        COD_EMPRESA=$cod_empresa AND 
                                                                                                        COD_CLIENTE=$COD_CLIENTE";
                                                        echo $UPdate;  
                                                        mysqli_query($conTemp, $UPdate);
                                    }
            //zerando variaveis
            unset($cupom);
            unset($COD_CLIENTE);
            unset($DES_PORT);
            unset($DES_CERTIFICADO);
            unset($TIP_DEBUG);
            unset($DES_EMAIL);
            unset($DES_SENHA);
            unset($DES_SMTP);
            unset($email_add);
            unset($nom_cli);
            unset($DES_SMTP);
            unset($DES_SMTP);
        }
        echo 'Fim do busca de cliente <br> <br>';
        
        
        echo 'OK';
      
}

 echo 'FIM do LOOPING configuracao do SMTP <br> <br>'; 
*/