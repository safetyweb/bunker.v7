<?php
include '../../_system/_functionsMain.php';

// 1-- se ja foi enviado

// get cod_empresa,cod_campanha,
// consultar na lista sms_lista_ret pelo cod_empresa e cod_companha capturar os ID de disparo e enviar para twillo
// capturar o retorno e marcar nos status do SM* como cancelado e codigo novo para contabilizar os cancelados.

//2- ainda nao foi enviado para twilo

$COD_EMPRESA=$_REQUEST['COD_EMPRESA'];
$COD_CAMPANHA=$_REQUEST['COD_CAMPANHA'];
//verificar se campanha ou  empresa estão vazios
if(isset($COD_EMPRESA) && isset($COD_EMPRESA))
{
   // connTemp($COD_EMPRESA,'');
    $sqlverifica="SELECT * FROM campanha WHERE COD_EMPRESA=$COD_EMPRESA AND COD_CAMPANHA=$COD_CAMPANHA;";
    $rwverifica=mysqli_query(connTemp($COD_EMPRESA,''), $sqlverifica);
    if($rwverifica->num_rows > 0)
    {
        //capturar a senha do comunicação
        $smsv ="SELECT * FROM senhas_parceiro apar
                              INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                              WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S' and apar.cod_empresa=$COD_EMPRESA ORDER BY cod_empresa ASC";
        $rwv= mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $smsv));
        $token =base64_encode($rwv[DES_USUARIO].':'.$rwv[DES_AUTHKEY]);
        
        //verificar se a campanha ja esta marcado para excluir
        $rsverifica= mysqli_fetch_assoc($rwverifica);
        if($rsverifica['LOG_CANCELA']=='N')
        {
            //vou alterar os dados da capmaha para execução futura
            $sqlUpdatecampanha="UPDATE campanha SET LOG_CANCELA='S', DAT_CANCELA=now() WHERE COD_EMPRESA=$COD_EMPRESA and  COD_CAMPANHA=$COD_CAMPANHA;";
            mysqli_query(connTemp($COD_EMPRESA,''), $sqlUpdatecampanha);
        }
        
        if($rsverifica['LOG_CANCELA']=='S')
        {
            //iniciar a exclusão da fila 
            $sqlfila="SELECT * FROM email_fila WHERE cod_empresa=$COD_EMPRESA AND cod_campanha=$COD_CAMPANHA limit 1";
            $rwfila=mysqli_query(connTemp($COD_EMPRESA,''), $sqlfila);
            if($rwfila->num_rows > 0)
            {
                //INICIAL A EXCLUSÃO DA FILA
                $sqldeletefila="DELETE FROM email_fila WHERE  COD_EMPRESA=$COD_EMPRESA AND COD_CAMPANHA=$COD_CAMPANHA";
                mysqli_query(connTemp($COD_EMPRESA,''), $sqldeletefila);
            }
            //CANCELAR NA TWILO OS AGENDAMENTOS
            
            $SQLRET="SELECT CHAVE_GERAL,CHAVE_CLIENTE,COD_LISTA FROM sms_lista_ret WHERE 
                                                                    cod_empresa=$COD_EMPRESA AND 
                                                                    cod_campanha=$COD_CAMPANHA AND 
                                                                    COD_LEITURA =0 AND 
                                                                    BOUNCE =0 AND 
                                                                    COD_NRECEBIDO=0 AND 
                                                                    COD_CCONFIRMACAO=0 AND 
                                                                    COD_SCONFIRMACAO=0 AND 
                                                                    IdContatosMailing=22";
            $RWRET= mysqli_query(connTemp($COD_EMPRESA,''), $SQLRET);
            if($RWRET->num_rows > 0)
            {
                 //capturar as senhas para efetuar os estorionos
                while ($rsRET= mysqli_fetch_assoc($RWRET))
                { 
                    
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://api.twilio.com/2010-04-01/Accounts/'.$rsRET['CHAVE_GERAL'].'/Messages/'.$rsRET['CHAVE_CLIENTE'].'.json',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false,  
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS => 'Status=canceled',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Authorization: Basic '.$token
                      ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    echo $response;
                    $var_cancel=json_decode($response,true);
                    //[status] => canceled
                    if($var_cancel['status']=='canceled')
                    {
                        $alterar="UPDATE sms_lista_ret SET BOUNCE=2,DES_STATUS='".$var_cancel['status']."' WHERE  COD_LISTA=".$rsRET['COD_LISTA']." and  cod_empresa=$COD_EMPRESA";
                        echo $alterar;
                        $exct=mysqli_query(connTemp($COD_EMPRESA,''), $alterar);
                        if(!$exct)
                        {
                           echo 'Problema na alteração execute manualmente a rotina de cancelamento!';
                           break;
                        }    
                    }    
                    
                    
                }
                
            }
            
        }
    }    
    
    
    
    
    
    
//UPDATE `demo_novo`.`campanha` SET `LOG_CANCELA`='S', `DAT_CANCELA`='2023-08-24 14:00:20' WHERE  `COD_CAMPANHA`=12;
    
    
}else{
    echo 'não ok';
}