<?php
include '../../_system/_functionsMain.php';
$conadmmysql=$connAdm->connAdm();

if($_GET[id]=='1')
{
  
     $JSONRETORNO=file_get_contents("php://input");
    $json_array=json_decode($JSONRETORNO,true);
 
    foreach ($json_array as $key => $dados) {
        
          $dadoscampanha=explode('||',$dados[Cliente_ref]);
          $EMPRESA=$dadoscampanha[1];
          $CAMPANHA=$dadoscampanha[0];
          $COD_CLINTE=$dadoscampanha[2];
          $contemporaria= connTemp($EMPRESA, '');
          
          
       /* $testeinsert="INSERT INTO log_nuxux (COD_CAMPANHA,COD_EMPRESA, TIP_LOG, LOG_JSON,DAT_CADASTR) VALUES ('$CAMPANHA','$EMPRESA', '44', '".addslashes($JSONRETORNO)."','".date('Y-m-d')."');";
        mysqli_query($contemporaria,$testeinsert);*/
       
        if($COD_CLINTE > '0')
        {    
    
    
            //verificar se ja tem OPOUT gravado
                $vopout="SELECT * FROM lista_optout WHERE MSG = '".$dados[Mensagem]."' AND DES_OPOUT='1'";
                $rwvopout=mysqli_query($conadmmysql,$vopout);
                if($rwvopout->num_rows <= '0')
                { 
                   //inserindo na table de optout.
                   $sqllistaoptout="INSERT INTO lista_optout (DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                MSG,
                                                                COD_EMPRESA,
                                                                COD_CLIENTE) 
                                                                 VALUES 
                                                                 ('".$dados[Data]."', 
                                                                 '9999', 
                                                                 '".$dados[Mensagem]."',
                                                                 '".$EMPRESA."', 
                                                                 '".$COD_CLINTE."'
                                                                 )";
                   mysqli_query($conadmmysql,$sqllistaoptout);
                }else{
                        //optou não pode ser inserido no balcklist
                        
                        //verificar se o cliente ja esta no optout
                        /*$verificaoptout="SELECT * FROM blacklist_sms WHERE cod_empresa=$EMPRESA AND cod_cliente=$COD_CLINTE;";
                        $rwclioptout=mysqli_query($contemporaria,$verificaoptout);
                        if($rwclioptout->num_rows <= '0')
                        { 
                                   
                            
                            
                                $insblacklist="INSERT INTO blacklist_sms (COD_EMPRESA, COD_CLIENTE,COD_USUCADA,DAT_CADASTR)
                                                                          VALUES 
                                                                         ('$EMPRESA','$COD_CLINTE', '999999', '$dados[Data]');";
                              
                                mysqli_query($contemporaria,$insblacklist);
                          */    
                                //marcar optout no cliente.
                                $LOG_SMS="UPDATE clientes SET LOG_SMS='N' WHERE  COD_CLIENTE=$COD_CLINTE and COD_EMPRESA='$EMPRESA';";
                                 mysqli_query($contemporaria,$LOG_SMS);
                                //inserir no retondo o cliente com OPOUT
                                $ret="UPDATE sms_lista_ret SET 
                                                            COD_OPTOUT_ATIVO='1',
                                                            DAT_OPOUT='".date('Y-m-d H:i:s')."',	
                                                            DES_MOTIVO='".$dados[Mensagem]."'     
                                WHERE  CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' and  cod_campanha='".$CAMPANHA."' and COD_EMPRESA=$EMPRESA;";
                                mysqli_query($contemporaria,$ret);	
                                
                       // }	
                }

                //inserir sms_lista_ret o retorno do cliente.
                $sqllista_ret="SELECT * FROM sms_lista_ret WHERE cod_campanha='".$CAMPANHA."' and CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' AND cod_empresa='$EMPRESA';";
                $rwlista_ret=mysqli_query($contemporaria,$sqllista_ret);
                if($rwlista_ret->num_rows >'0')
                {
                   $ret="UPDATE sms_lista_ret SET dat_leitura='".date('Y-m-d H:i:s')."', 
                                                   DES_MOTIVO='".$dados[Mensagem]."' 
                        WHERE   cod_campanha='".$CAMPANHA."' and COD_EMPRESA=$EMPRESA and CHAVE_CLIENTE='".$dados[MensagemUniqueId]."';";								 
                        mysqli_query($contemporaria,$ret);
                         
                }
    }else{
        //inserir sms_lista_ret o retorno do cliente.
                $sqllista_ret="SELECT * FROM sms_lista_ret WHERE cod_campanha='".$CAMPANHA."' and CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' AND cod_empresa='$EMPRESA';";
                $rwlista_ret=mysqli_query($contemporaria,$sqllista_ret);
                if($rwlista_ret->num_rows >'0')
                {
                   $ret="UPDATE sms_lista_ret SET 
                                                   dat_leitura='".date('Y-m-d H:i:s')."', 
                                                   DES_MOTIVO='".$dados[Mensagem]."' 
                        WHERE   cod_campanha='".$CAMPANHA."' and COD_EMPRESA=$EMPRESA and CHAVE_CLIENTE='".$dados[MensagemUniqueId]."';";								 
                        mysqli_query($contemporaria,$ret);
                         
        }
    }
    
    
    
    
  }
    
}else{
    //teste
    
    $JSONRETORNO=file_get_contents("php://input");
    $json_array=json_decode($JSONRETORNO,true);
 
    foreach ($json_array as $key => $dados) {
          $dadoscampanha=explode('||',$dados[Cliente_ref]);
          $EMPRESA=$dadoscampanha[1];
          $CAMPANHA=$dadoscampanha[0];
          $COD_CLINTE=$dadoscampanha[2];
          $contemporaria= connTemp($EMPRESA, '');
         
    
    
            //verificar se ja tem OPOUT gravado
                $vopout="SELECT * FROM lista_optout WHERE MSG = '".$dados[Mensagem]."' AND DES_OPOUT='1'";
                $rwvopout=mysqli_query($conadmmysql,$vopout);
                if($rwvopout->num_rows <= '0')
                { 
                   //inserindo na table de optout.
                   $sqllistaoptout="INSERT INTO lista_optout (DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                MSG,
                                                                COD_EMPRESA,
                                                                COD_CLIENTE) 
                                                                 VALUES 
                                                                 ('".$dados[Data]."', 
                                                                 '9999', 
                                                                 '".$dados[Mensagem]."',
                                                                 '".$EMPRESA."', 
                                                                 '".$COD_CLINTE."'
                                                                 )";
                   mysqli_query($conadmmysql,$sqllistaoptout);
                }else{
                        //verificar se o cliente ja esta no optout
                        $verificaoptout="SELECT * FROM blacklist_sms WHERE cod_empresa=$EMPRESA AND cod_cliente=$COD_CLINTE;";
                        $rwclioptout=mysqli_query($contemporaria,$verificaoptout);
                        if($rwclioptout->num_rows <= '0')
                        { 
                                   
                            
                            
                                $insblacklist="INSERT INTO blacklist_sms (COD_EMPRESA, COD_CLIENTE,COD_USUCADA,DAT_CADASTR)
                                                                          VALUES 
                                                                         ('$EMPRESA','$COD_CLINTE', '999999', '$dados[Data]');";
                              
                                mysqli_query($contemporaria,$insblacklist);
                              
                                //marcar optout no cliente.
                                $LOG_SMS="UPDATE clientes SET LOG_SMS='N' WHERE  COD_CLIENTE=$COD_CLINTE and COD_EMPRESA='$EMPRESA';";
                                 mysqli_query($contemporaria,$LOG_SMS);
                                //inserir no retondo o cliente com OPOUT
                                $ret="UPDATE sms_lista_ret SET 
                                                            COD_OPTOUT_ATIVO='1',
                                                            DAT_OPOUT='".date('Y-m-d H:i:s')."',	
                                                            DES_MOTIVO='".$dados[Mensagem]."'     
                                WHERE  CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' and  cod_campanha='".$CAMPANHA."' and COD_EMPRESA=$EMPRESA and COD_CLIENTE=$COD_CLINTE;";
                                echo $ret;
                                mysqli_query($contemporaria,$ret);	
                                
                        }	
                }

                //inserir sms_lista_ret o retorno do cliente.
                $sqllista_ret="SELECT * FROM sms_lista_ret WHERE cod_campanha='".$CAMPANHA."' and COD_CLIENTE='".$COD_CLINTE."' AND cod_empresa='$EMPRESA';";
                $rwlista_ret=mysqli_query($contemporaria,$sqllista_ret);
                if($rwlista_ret->num_rows >'0')
                {
                   $ret="UPDATE sms_lista_ret SET COD_LOTE='".$cod_disparo."', 
                                                   ID_DISPARO='".$cod_disparo."', 
                                                   dat_leitura='".date('Y-m-d H:i:s')."', 
                                                   DES_MOTIVO='".$dados[Mensagem]."' 
                        WHERE   cod_campanha='".$CAMPANHA."' and COD_EMPRESA=$EMPRESA and COD_CLIENTE=$COD_CLINTE;";								 
                        mysqli_query($contemporaria,$ret);
                      
                        
                }
    }
}
?>