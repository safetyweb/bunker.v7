<?php
function Retorno_twilo($sidmsg,$token,$sidcount)
    {
        $options = [
                    'http' => [
                        'method' => 'GET',
                         'header' => [
                                        'Content-Type: application/json',
                                         'Authorization: Basic '.$token 
                                    ]
                    ],
        ];
        $context = stream_context_create($options);
        $response= file_get_contents("https://api.twilio.com/2010-04-01/Accounts/$sidcount/Messages/$sidmsg.json", false, $context);
        if ($response !== false) {
            $responseCode = explode(' ', $http_response_header[0])[1];

            if ($responseCode == '200') {

                  $connect=json_decode ($response,true); 
            } else {
                // Handle non-200 response
                $connect= "erro";
            }
        } else {
            // Error occurred

             $connect= "erro";
        } 
        return $connect;
}
include  '../../_system/_functionsMain.php';

$empresa="select * from empresas emp
         INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
         WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU in ('22','23','24') AND apar.LOG_ATIVO='S'";

$rwempresa=mysqli_query($connAdm->connAdm(), $empresa);
while($rsempresa= mysqli_fetch_assoc($rwempresa)){          
   // $contemporaria= connTemp($rsempresa['COD_EMPRESA'], '');

    $testeinsert="SELECT * FROM log_nuxux WHERE LOG_PROCESSADO='N' and TIP_LOG in ('22') AND COD_EMPRESA=".$rsempresa['COD_EMPRESA'];
    $rw=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$testeinsert);
    while($rs= mysqli_fetch_assoc($rw))
    {
        // Converter a string em um array
        parse_str($rs['LOG_JSON'], $arraydadosret);
   
       //alteração offline dos status vazio
        if($arraydadosret[SmsStatus]=='failed')
        {
            $temalteracao='1';
            $sqlbounce12="UPDATE sms_lista_ret SET DES_STATUS='".$arraydadosret[SmsStatus]."', BOUNCE='1',COD_LEITURA='0',COD_CCONFIRMACAO='0',COD_SCONFIRMACAO='0',COD_NRECEBIDO='0' WHERE CHAVE_CLIENTE='".$arraydadosret[MessageSid]."' AND cod_empresa=$rsempresa[COD_EMPRESA]";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlbounce12);

        } 
        if($arraydadosret[SmsStatus]=='delivered' || $arraydadosret[SmsStatus]=='received' || $arraydadosret[SmsStatus]=='sent')
        {	
            $temalteracao='1';
            $sqlleitura11="UPDATE sms_lista_ret SET  DES_STATUS='".$arraydadosret[SmsStatus]."', BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0' WHERE CHAVE_CLIENTE='".$arraydadosret[MessageSid]."' AND cod_empresa=$rsempresa[COD_EMPRESA]";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleitura11);
        }
        if($arraydadosret[SmsStatus]=='undelivered')
        {	
            $temalteracao='1';
            $sqlleitura13="UPDATE sms_lista_ret SET DES_STATUS='".$arraydadosret[SmsStatus]."', BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'  WHERE CHAVE_CLIENTE='".$arraydadosret[MessageSid]."' AND cod_empresa=$rsempresa[COD_EMPRESA]";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleitura13);
        }
        if($arraydadosret[SmsStatus]=='canceled')
        {	
            $temalteracao='1';
            $sqlleitura15="UPDATE sms_lista_ret SET DES_STATUS='".$arraydadosret[SmsStatus]."', BOUNCE='2', COD_LEITURA='0',COD_NRECEBIDO='0',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'  WHERE CHAVE_CLIENTE='".$arraydadosret[MessageSid]."' AND cod_empresa=$rsempresa[COD_EMPRESA]";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleitura15);
        }
        
            $ALTTOKENDADOS.="UPDATE rel_geratoken SET DES_MSG='$arraydadosret[SmsStatus]' WHERE  CHAVE_CLIENTE='$arraydadosret[MessageSid]' AND cod_empresa=$rsempresa[COD_EMPRESA];";
            $ALTTOKENDADOS.="UPDATE SMS_LISTA_RET SET DES_STATUS='$arraydadosret[SmsStatus]' WHERE  CHAVE_CLIENTE='$arraydadosret[MessageSid]' AND cod_empresa=$rsempresa[COD_EMPRESA];";
            $upe=mysqli_multi_query(connTemp($rsempresa['COD_EMPRESA'], ''),$ALTTOKENDADOS);
            unset($ALTTOKENDADOS);
            if(!$upe)
            {
             echo 'erro: '.$ALTTOKENDADOS .'<br>'; 
            }    
         if($temalteracao=='1')
         {
             $UPlog="UPDATE log_nuxux SET LOG_PROCESSADO='S' WHERE ID_LOG=".$rs[ID_LOG];
              mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$UPlog);
         }    
    } 
    //iniciar a consulta de log Offline
    
    $token =base64_encode($rsempresa[DES_USUARIO].':'.$rsempresa[DES_AUTHKEY]);
    $sidcount=$rsempresa[DES_USUARIO];
    
    //atualizar os status do log de envio de   token
    $dadostoken="SELECT * FROM rel_geratoken WHERE  DAT_CADAST>=DATE_SUB(NOW(), INTERVAL 1 day)  AND COD_EMPRESA=$rsempresa[COD_EMPRESA] AND TIP_ENVIO=1";
    $rwdadostoken=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $dadostoken); 
    if($rwdadostoken->num_rows > '0')
    {
        while($rsdadostoken= mysqli_fetch_assoc($rwdadostoken)){
            $sidmsg=$rsdadostoken[CHAVE_CLIENTE]; 
            $ret=Retorno_twilo($sidmsg,$token,$sidcount);
            //alteração offline dos status vazio
            if($rsdadostoken[DES_MSG]!=$ret[status])
            {    
            $ALTTOKENDADOS.="UPDATE rel_geratoken SET DES_MSG='$ret[status]' WHERE  CHAVE_CLIENTE='$sidmsg' AND cod_empresa=$rsempresa[COD_EMPRESA];";
            $ALTTOKENDADOS.="UPDATE SMS_LISTA_RET SET DES_STATUS='$ret[status]' WHERE  CHAVE_CLIENTE='$sidmsg' AND cod_empresa=$rsempresa[COD_EMPRESA];";
            }
        }
           if($ALTTOKENDADOS!='')
           {    
            mysqli_multi_query(connTemp($rsempresa['COD_EMPRESA'], ''),$ALTTOKENDADOS);
           }
    }
    
    
    
    if($_GET[reprocessamento]=='1'){ $bounce="ret.BOUNCE=0 AND";}

       $buscaatualizacao="SELECT  ret.cod_cliente, ret.CHAVE_CLIENTE,ret.CHAVE_GERAL,ret.COD_EMPRESA,g.COD_CAMPANHA,g.TIP_GATILHO, DATE(ret.dat_cadastr) data_verificacao   FROM sms_lista_ret ret
                        left JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA                             
                        WHERE 
                    
                        ret.idContatosMailing in ('22','23','24') AND
                        ret.CHAVE_CLIENTE!='' AND
                        ret.COD_EMPRESA=$rsempresa[COD_EMPRESA] AND 
                        $bounce
                        DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL  30  DAY)) 
                            and case  when      ret.COD_OPTOUT_ATIVO='0' AND 
                                                ret.COD_LEITURA='0' AND 
                                                ret.COD_NRECEBIDO='0' AND 
                                                ret.COD_CCONFIRMACAO='0' AND 
                                                ret.COD_SCONFIRMACAO='0' AND 
                                                ret.BOUNCE='0'
                                                then '1' ELSE '0' END IN (1) 
                            and case when ret.CHAVE_CLIENTE is not NULL AND ret.CHAVE_CLIENTE != '0' then '1'  ELSE '0' end IN (1) limit 10000;";
       $rwbuscaatualizacao=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $buscaatualizacao); 
       if($rwbuscaatualizacao->num_rows > '1' )
       {    
            while($rsbuscaatualizacao= mysqli_fetch_assoc($rwbuscaatualizacao)){
              
             
               
                if(date('i')>='00' && date('i')<='10')
                {
                    if($rsbuscaatualizacao[TIP_GATILHO] == 'tokenCad')
                    {    
                        if($rsbuscaatualizacao[cod_cliente]!='')
                        {    

                                $clie= "update geratoken  ger  
                                                            INNER JOIN rel_geratoken rel ON rel.COD_GERATOKEN=ger.COD_TOKEN
                                                            INNER JOIN sms_lista_ret  ret ON ret.CHAVE_CLIENTE=rel.CHAVE_CLIENTE AND ret.CHAVE_GERAL=rel.CHAVE_GERAL AND ret.CHAVE_CLIENTE!=''
                                                            INNER JOIN clientes c ON c.NUM_CGCECPF=ger.NUM_CGCECPF
                                                            set ret.COD_CLIENTE=ger.COD_CLIENTE 
                                                            WHERE 
                                                            ger.COD_EMPRESA=".$rsempresa['COD_EMPRESA']." AND ger.LOG_USADO='2' 
                                                            AND ger.COD_EXCLUSA=0 AND ret.COD_CLIENTE='0'
                                                            AND ret.idContatosMailing in ('22','23','24')";
                                  $rwupdatecliente= mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $clie);
                            echo 'MIN:'.date('i').'<br>';
                        }
                    }
                } 
            
                //atualizar se existir na data antiga
                if($rsbuscaatualizacao[data_verificacao] < date('Y-m-d'))
                {
                    //toda atualização no sms lista ret vai acontecer aqui.
                    $sidmsg=$rsbuscaatualizacao[CHAVE_CLIENTE]; 
                    $ret=Retorno_twilo($sidmsg,$token,$sidcount);
                    
                    /*echo '<pre>';
                    print_r($ret);
                    echo '</pre>';*/
                  
                           //alteração offline dos status vazio
                            if($ret[status]=='failed')
                            {
                                 $statusb='failed';
                                 $CHAVE_CLIENTEb.='"'.$rsbuscaatualizacao[CHAVE_CLIENTE].'",';                                
                            }
                            if($ret[status]=='delivered')
                            {	
                                 $statusd='delivered';
                                 $CHAVE_CLIENTEd.='"'.$rsbuscaatualizacao[CHAVE_CLIENTE].'",';                               
                            }
                            
                            if($ret[status]=='received')
                            {	
                                 $statusre='received';
                                 $CHAVE_CLIENTEre.='"'.$rsbuscaatualizacao[CHAVE_CLIENTE].'",';                               
                            }
                            if($ret[status]=='sent')
                            {	
                                 $statussen='sent';
                                 $CHAVE_CLIENTEsen.='"'.$rsbuscaatualizacao[CHAVE_CLIENTE].'",';                               
                            }

                            if($ret[status]=='undelivered')
                            {	
                                $statusu='undelivered';
                                $CHAVE_CLIENTEu.='"'.$rsbuscaatualizacao[CHAVE_CLIENTE].'",';                                
                            }
                            if($ret[status]=='canceled')
                            {	
                                $temalteracao='1';
                                $canceladoD="UPDATE sms_lista_ret SET DES_STATUS='".$ret[status]."', BOUNCE='2', COD_LEITURA='0',COD_NRECEBIDO='0',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'  WHERE CHAVE_CLIENTE='".$rsbuscaatualizacao[CHAVE_CLIENTE]."' AND cod_empresa=$rsempresa[COD_EMPRESA]";
                                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$canceladoD);
                            }
                }            
            }
            if($CHAVE_CLIENTEsen!='')
            {    
                $CHAVE_CLIENTEsen=rtrim($CHAVE_CLIENTEsen,',');
                $sqlleiturasen="UPDATE sms_lista_ret SET 
                                                    DES_STATUS='$statussen',
                                                    BOUNCE='0',
                                                    COD_NRECEBIDO='0',
                                                    COD_LEITURA='1',
                                                    COD_CCONFIRMACAO='1',
                                                    COD_SCONFIRMACAO='0'
                         WHERE CHAVE_CLIENTE in ($CHAVE_CLIENTEsen) AND cod_empresa=$rsempresa[COD_EMPRESA];";
                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleiturasen);
                echo $sqlleiturasen.'<br>';
            }
            if($CHAVE_CLIENTEre!='')
            {    
                $CHAVE_CLIENTEre=rtrim($CHAVE_CLIENTEre,',');
                $sqlleiturare="UPDATE sms_lista_ret SET 
                                                    DES_STATUS='$statusre',
                                                    BOUNCE='0',
                                                    COD_NRECEBIDO='0',
                                                    COD_LEITURA='1',
                                                    COD_CCONFIRMACAO='1',
                                                    COD_SCONFIRMACAO='0'
                         WHERE CHAVE_CLIENTE in ($CHAVE_CLIENTEre) AND cod_empresa=$rsempresa[COD_EMPRESA];";
                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleiturare);
               
            }            
            if($CHAVE_CLIENTEb!='')
            {    
                $CHAVE_CLIENTEb=rtrim($CHAVE_CLIENTEb,',');                
                $sqlbounce="UPDATE sms_lista_ret SET 
                                                    DES_STATUS='$statusb',
                                                    BOUNCE='1',
                                                    COD_LEITURA='0',
                                                    COD_CCONFIRMACAO='0',
                                                    COD_SCONFIRMACAO='0',
                                                    COD_NRECEBIDO='0' 
                           WHERE CHAVE_CLIENTE in ($CHAVE_CLIENTEb) AND cod_empresa=$rsempresa[COD_EMPRESA];";
                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlbounce);
               
            }                     
            if($CHAVE_CLIENTEd!='')
            {    
                $CHAVE_CLIENTEd=rtrim($CHAVE_CLIENTEd,',');
                $sqlleitura1="UPDATE sms_lista_ret SET 
                                                    DES_STATUS='$statusd',
                                                    BOUNCE='0',
                                                    COD_NRECEBIDO='0',
                                                    COD_LEITURA='1',
                                                    COD_CCONFIRMACAO='1',
                                                    COD_SCONFIRMACAO='0'
                         WHERE CHAVE_CLIENTE in ($CHAVE_CLIENTEd) AND cod_empresa=$rsempresa[COD_EMPRESA];";
                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleitura1);
                
            }
            if($CHAVE_CLIENTEu!='')
            {    
                $CHAVE_CLIENTEu=rtrim($CHAVE_CLIENTEu,',');
                $sqlleitura3="UPDATE sms_lista_ret SET 
                                                    DES_STATUS='$statusu',
                                                    BOUNCE='0', 
                                                    COD_LEITURA='0',
                                                    COD_NRECEBIDO='1',
                                                    COD_SCONFIRMACAO='0',
                                                    COD_CCONFIRMACAO='0' 
                          WHERE CHAVE_CLIENTE in ($CHAVE_CLIENTEu) AND cod_empresa=$rsempresa[COD_EMPRESA];";
                mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$sqlleitura3);
                
            }
            
       }     
       
       //verifica se tem registro sem chave e altera
       
       $semchave="UPDATE sms_lista_ret SET BOUNCE=1 WHERE  idContatosMailing in ('22','23','24') AND cod_empresa=".$rsempresa['COD_EMPRESA']." AND CHAVE_CLIENTE=''";
       $rwcontadores=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $semchave);
       // iniciar os contadores para atualização dos relatorios contabeis
                  
            //contadores da lista
           $sqlcontadores="SELECT   TIP_GATILHO,
                                    LOG_TESTE,
                                    ID_DISPARO,
                                    COD_CAMPANHA,
                                    DATA_CADASTRO ,
                                    sum(COD_OPTOUT_ATIVO) COD_OPTOUT_ATIVO,
                                    sum(BOUNCE) BOUNCE,
                                    sum(CANCELADO) CANCELADO,
                                    sum(COD_NRECEBIDO) COD_NRECEBIDO,
                                    sum(COD_CCONFIRMACAO) COD_CCONFIRMACAO,
                                    sum(COD_OPTOUT_ATIVO)+ sum(BOUNCE) + sum(COD_NRECEBIDO)+ sum(COD_CCONFIRMACAO) TOTAL,
                                    sum(SUB_TOTAL)- sum(CANCELADO)   SUB_TOTAL

                            FROM (
                                    SELECT g.TIP_GATILHO,
                                            ret.LOG_TESTE,
                                            ret.ID_DISPARO,
                                            ret.COD_CAMPANHA,
                                            date(ret.DAT_CADASTR) DATA_CADASTRO ,
                                            case when ret.COD_OPTOUT_ATIVO='1' then '1'  ELSE '0' END COD_OPTOUT_ATIVO,
                                            case when ret.BOUNCE='1' then '1'  ELSE '0' END BOUNCE,
                                            case when ret.BOUNCE='2' then '1'  ELSE '0' END CANCELADO,
                                            case when ret.COD_NRECEBIDO='1' then '1'  ELSE '0' END COD_NRECEBIDO,
                                            case when ret.COD_CCONFIRMACAO='1' then '1'  ELSE '0' END COD_CCONFIRMACAO,
                                            case when ret.COD_CCONFIRMACAO='0' then '1'																	   
                                                 when ret.COD_NRECEBIDO='0' then '1'																	    
                                                 when ret.BOUNCE='0' then '1'																	  
                                                 when ret.COD_OPTOUT_ATIVO='0' then '1'
                                                 ELSE '1' END SUB_TOTAL
                                                    
                                    FROM sms_lista_ret ret
                                    INNER JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
                                    WHERE 
                                     ret.CHAVE_CLIENTE is not NULL and 
                                    ret.COD_EMPRESA=".$rsempresa['COD_EMPRESA']." AND 
                                    DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL 120 DAY))
                                                            ) tmpsms
                           GROUP BY LOG_TESTE, COD_CAMPANHA,ID_DISPARO;"; 
          
            $rwcontadores=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $sqlcontadores);
            while ($rscontadore= mysqli_fetch_assoc($rwcontadores))
            {
            
              
                if(!strstr($rsbuscaatualizacao[TIP_GATILHO],'token'))
                {   

                    $tipoenvio=true;
                }
                
                if($rwcontadores->num_rows > '0')
                {
                    $total=$rscontadore[SUB_TOTAL];
                        
                    //VERIFICAR SE O LOTE EXISTE PARA NÃO INSERIR DUPLICADO 
                        $lote="SELECT * FROM sms_lote WHERE LOG_TESTE='".$rscontadore[LOG_TESTE]."' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO_EXT='".$rscontadore[ID_DISPARO]."'";
                     //   echo $lote.'<br>';
                        $rwlote= mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $lote);
                      if($rwlote->num_rows <='0')
                      {    
                               
                                  if(!$tipoenvio)
                                  {
                                    $stringname=$rsbuscaatualizacao[TIP_GATILHO];  
                                  }


                                    $inslote="INSERT INTO SMS_LOTE(
                                                     COD_CAMPANHA,
                                                     COD_EMPRESA,						
                                                     COD_LOTE,
                                                     QTD_LISTA,
                                                     NOM_ARQUIVO,
                                                     DES_PATHARQ,                                                   
                                                     LOG_ENVIO,
                                                     COD_USUCADA,
                                                     COD_DISPARO_EXT,
                                                     DAT_AGENDAMENTO,
                                                     LOG_TESTE
                                             ) VALUES(
                                                     $rscontadore[COD_CAMPANHA],
                                                     $rsempresa[COD_EMPRESA],						
                                                     0,
                                                      $total,
                                                     '$stringname',
                                                     '$stringname',                                                    
                                                     'S',
                                                     0,
                                                     $rscontadore[ID_DISPARO],
                                                     '".$rscontadore[DATA_CADASTRO]."',
                                                     '".$rscontadore[LOG_TESTE]."'    
                                             );";
                            // echo $inslote.'<br>';    
                                     mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $inslote);   
                             }
                        
                        
                        while ($row = mysqli_fetch_assoc($rwlote)) {
                          
                             if($rwlote->num_rows > '0')
                             {    
                                 $uplote="UPDATE sms_lote SET QTD_LISTA='".$total."'  WHERE LOG_TESTE='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO_EXT='".$rscontadore[ID_DISPARO]."'";
                               //  echo $uplote.'<br>';
                                 mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $uplote);
                             }else{
                                  if(!$tipoenvio)
                                  {
                                    $stringname='venda';  
                                  }


                                    $inslote="INSERT INTO SMS_LOTE(
                                                     COD_CAMPANHA,
                                                     COD_EMPRESA,						
                                                     COD_LOTE,
                                                     QTD_LISTA,
                                                     NOM_ARQUIVO,
                                                     DES_PATHARQ,                                                    
                                                     LOG_ENVIO,
                                                     COD_USUCADA,
                                                     COD_DISPARO_EXT,
                                                     DAT_AGENDAMENTO,
                                                     LOG_TESTE
                                             ) VALUES(
                                                     $rscontadore[COD_CAMPANHA],
                                                     $rsempresa[COD_EMPRESA],						
                                                     0,
                                                      $total,
                                                     '$stringname',
                                                     '$stringname',                                                     
                                                     'S',
                                                     0,
                                                     $rscontadore[ID_DISPARO],
                                                     '".$rscontadore[DATA_CADASTRO]."',
                                                     '".$rscontadore[LOG_TESTE]."'    
                                             );";
                                 // echo $inslote.'<br>';    
                                     mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $inslote);   
                             }
                        }    
                        //inserir contadores do relatorio
                        $entregasms="SELECT * FROM controle_entrega_sms WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO='".$rscontadore[ID_DISPARO]."'";
                       $rwentregasms= mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $entregasms);
                      
                     //   echo $entregasms.'<br>';
                        if($rwentregasms->num_rows > '0')
                        {
                            $updateentrega="UPDATE controle_entrega_sms 
                                                SET  qtd_disparados=$total, 
                                                     qtd_sucesso=$rscontadore[COD_CCONFIRMACAO], 
                                                     qtd_falha= $rscontadore[BOUNCE],
                                                     QTD_AGUARADANDO='0',
                                                     CANCELADO=$rscontadore[CANCELADO],
                                                     QTD_CCONFIRMACAO=$rscontadore[COD_CCONFIRMACAO],
                                                     QTD_NRECEBIDO=$rscontadore[COD_NRECEBIDO],
                                                     QTD_OPTOUT=$rscontadore[COD_OPTOUT_ATIVO],
                                                     log_teste='$rscontadore[LOG_TESTE]'
                                            WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO='".$rscontadore[ID_DISPARO]."';";
                            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$updateentrega);
                         //  echo $updateentrega.'<br>';
                        }else{
                            
                               $entregue="INSERT INTO controle_entrega_sms (cod_empresa, 
                                                                                cod_campanha_ext,                                                                                
                                                                                cod_campanha, 
                                                                                dat_cadastr, 
                                                                                cod_disparo, 
                                                                                dat_envio,
                                                                                qtd_disparados, 
                                                                                qtd_sucesso, 
                                                                                qtd_falha,                                                                         
                                                                                QTD_CCONFIRMACAO,
                                                                                QTD_SCONFIRMACAO,
                                                                                QTD_NRECEBIDO,
                                                                                QTD_OPTOUT,
                                                                                QTD_AGUARADANDO,
                                                                                log_teste,
                                                                                CANCELADO) 
                                                                VALUES 
                                                                                ($rsempresa[COD_EMPRESA], 
                                                                                '$rscontadore[ID_DISPARO]',                                                                                
                                                                                '$rscontadore[COD_CAMPANHA]', 
                                                                                '$rscontadore[DATA_CADASTRO]', 
                                                                                '$rscontadore[ID_DISPARO]', 
                                                                                '$rscontadore[DATA_CADASTRO]',
                                                                                $total, 
                                                                                $rscontadore[COD_CCONFIRMACAO], 
                                                                                $rscontadore[BOUNCE],                                                                                
                                                                                $rscontadore[COD_CCONFIRMACAO],
                                                                                0,
                                                                                $rscontadore[COD_NRECEBIDO],
                                                                                $rscontadore[COD_OPTOUT_ATIVO],
                                                                                '0',
                                                                                '$rscontadore[LOG_TESTE]',
                                                                                $rscontadore[CANCELADO]    
                                                                                );";
		               $insertsms=mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''),$entregue);
                               if(!$insertsms)
                               {
                                    $file='./aquivosX/error_insert'.date('YmdHis').'.txt';
                                  //  file_put_contents($file, $entregue);
                               } else{
                                   $file='./aquivosX/OK_insert'.date('YmdHis').'.txt';
                                 //   file_put_contents($file, $entregue);
                               }   
                             //echo $entregue.'<br>';
                        }
                }
            }
}
echo 'LOG Processado...';
?>