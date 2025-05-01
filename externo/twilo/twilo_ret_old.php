<?php
include_once '../../_system/_functionsMain.php';
/*$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S'";

$rwempresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlempresa));
*/
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
   
       
    
    
 
       $empresa="select * from empresas emp
                INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
                INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S' $COD_EMPRESAURL;";

       $rwempresa=mysqli_query($connAdm->connAdm(), $empresa);
       while($rsempresa= mysqli_fetch_assoc($rwempresa)){
           
            $token =base64_encode($rsempresa[DES_USUARIO].':'.$rsempresa[DES_AUTHKEY]);
            $sidcount=$rsempresa[DES_USUARIO];
           
            /*   $contemporaria= connTemp($rsempresa['COD_EMPRESA'], '');
               $limpablk="DELETE FROM blacklist_sms WHERE WHERE NUM_CELULAR IS NULL;";
               mysqli_query($contemporaria, $limpablk);
               //verificar na sms lista ret os disparos sem retorno
               //e consultar no metodo novo
                $alterar="UPDATE sms_lista_ret SET BOUNCE=1  WHERE BOUNCE=0 AND COD_LEITURA=0 AND CHAVE_CLIENTE='' and COD_EMPRESA=".$rsempresa[COD_EMPRESA];
                mysqli_query($contemporaria, $alterar);  
                echo '<br>lista se chave de consultas<br>';*/
                
           //11111111111  
            if($_GET[reprocessamento]=='1'){ $bounce="ret.BOUNCE=0 AND";}
            
               $buscaatualizacao="SELECT  ret.cod_cliente, ret.CHAVE_CLIENTE,ret.CHAVE_GERAL,ret.COD_EMPRESA,g.COD_CAMPANHA,g.TIP_GATILHO     FROM sms_lista_ret ret
                                left JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA                             
                                WHERE 
                                ret.COD_EMPRESA=$rsempresa[COD_EMPRESA] AND 
				$bounce
                                DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL  10  DAY)) 
                                    and case  when      ret.COD_OPTOUT_ATIVO='0' 
                                                        AND ret.COD_LEITURA='0'
                                                        AND ret.COD_NRECEBIDO='0'
                                                        AND ret.COD_CCONFIRMACAO='0'
                                                        AND  ret.COD_SCONFIRMACAO='0' 
                                                        then '1' ELSE '0' END IN (1) 
                                    and case when ret.CHAVE_CLIENTE is not NULL AND ret.CHAVE_CLIENTE != '0' then '1'  ELSE '0' end IN (1)
                                     ;";
									 
               $rwbuscaatualizacao=mysqli_query(connTemp($rsempresa[COD_EMPRESA],''), $buscaatualizacao); 
	       if($rwbuscaatualizacao->num_rows > '0')
               {
                    while($rsbuscaatualizacao= mysqli_fetch_assoc($rwbuscaatualizacao)){  
                        
                        if($rsbuscaatualizacao[TIP_GATILHO] == 'tokenCad')
                        {    
                            if($rsbuscaatualizacao[cod_cliente]!='')
                            {    
                                    $clie= "update geratoken  ger  
                                                                INNER JOIN rel_geratoken rel ON rel.COD_GERATOKEN=ger.COD_TOKEN
                                                                INNER JOIN sms_lista_ret  ret ON ret.CHAVE_CLIENTE=rel.CHAVE_CLIENTE AND ret.CHAVE_GERAL=rel.CHAVE_GERAL
                                                                set ret.COD_CLIENTE=ger.COD_CLIENTE 
                                                                WHERE 
                                                                ger.COD_EMPRESA=".$rsempresa['COD_EMPRESA']." AND ger.LOG_USADO='2' 
                                                                AND ger.COD_EXCLUSA=0 AND ret.COD_CLIENTE='0'";
                                      $rwupdatecliente= mysqli_query(connTemp(7,''), $clie);
                            }
                        }
                        //toda atualização no sms lista ret vai acontecer aqui.
                        
                          
                            $sidmsg=$rsbuscaatualizacao[CHAVE_CLIENTE]; 
                            $ret=Retorno_twilo($sidmsg,$token,$sidcount);
                                   //alteração offline dos status vazio
                                    if($ret[status]=='failed')
                                    {
                                        
                                           $sqlbounce="UPDATE sms_lista_ret SET BOUNCE='1',COD_LEITURA='0',COD_CCONFIRMACAO='0',COD_SCONFIRMACAO='0',COD_NRECEBIDO='0' WHERE CHAVE_CLIENTE='".$rsbuscaatualizacao[CHAVE_CLIENTE]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsbuscaatualizacao[COD_CAMPANHA];";
                                           mysqli_query(connTemp($rsempresa[COD_EMPRESA],''),$sqlbounce);
                                    }
                                    if($ret[status]=='delivered' || $ret[status]=='received' || $ret[status]=='sent')
                                    {	
                                        $sqlleitura1="UPDATE sms_lista_ret SET  BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0' WHERE CHAVE_CLIENTE='".$rsbuscaatualizacao[CHAVE_CLIENTE]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsbuscaatualizacao[COD_CAMPANHA];";
                                        mysqli_query(connTemp($rsempresa[COD_EMPRESA],''),$sqlleitura1);
                                     //   echo $sqlleitura1.'<br>';
                                    }

                                   
                                    if($ret[status]=='undelivered')
                                    {	
                                        
                                        $sqlleitura3="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'  WHERE CHAVE_CLIENTE='".$rsbuscaatualizacao[CHAVE_CLIENTE]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsbuscaatualizacao[COD_CAMPANHA];";
                                        mysqli_query(connTemp($rsempresa[COD_EMPRESA],''),$sqlleitura3);
                                    //    echo $sqlleitura3.'<br>';
                                    }
                            
                        
                       /* echo '<pre>';
                        print_r($rsbuscaatualizacao);
                        echo '</pre>';
                        * 
                        */
                    }
                        
               }    
                        
            //contadores da lista
           $sqlcontadores="SELECT   TIP_GATILHO,
                                    LOG_TESTE,
                                    ID_DISPARO,
                                    COD_CAMPANHA,
                                    DATA_CADASTRO ,
                                    sum(COD_OPTOUT_ATIVO) COD_OPTOUT_ATIVO,
                                    sum(BOUNCE) BOUNCE,
                                    sum(COD_NRECEBIDO) COD_NRECEBIDO,
                                    sum(COD_CCONFIRMACAO) COD_CCONFIRMACAO,
                                    sum(COD_OPTOUT_ATIVO)+ sum(BOUNCE) + sum(COD_NRECEBIDO)+ sum(COD_CCONFIRMACAO) TOTAL,
                                    sum(SUB_TOTAL)   SUB_TOTAL

                            FROM (
                                    SELECT g.TIP_GATILHO,
                                            ret.LOG_TESTE,
                                            ret.ID_DISPARO,
                                            ret.COD_CAMPANHA,
                                            date(ret.DAT_CADASTR) DATA_CADASTRO ,
                                            case when ret.COD_OPTOUT_ATIVO='1' then '1'  ELSE '0' END COD_OPTOUT_ATIVO,
                                            case when ret.BOUNCE='1' then '1'  ELSE '0' END BOUNCE,
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
                                    DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL 10 DAY))
                                                            ) tmpsms
                           GROUP BY LOG_TESTE, COD_CAMPANHA,ID_DISPARO -- DATE(DATA_CADASTRO);"; 
            $rwcontadores=mysqli_query(connTemp($rsempresa[COD_EMPRESA],''), $sqlcontadores);
            $rscontadore= mysqli_fetch_assoc($rwcontadores);
          
            /*
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
                        $rwlote= mysqli_query($contemporaria, $lote);
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
                                     mysqli_query($contemporaria, $inslote);   
                             }
                        
                        
                        while ($row = mysqli_fetch_assoc($rwlote)) {
                          
                             if($rwlote->num_rows > '0')
                             {    
                                 $uplote="UPDATE sms_lote SET QTD_LISTA='".$total."'  WHERE LOG_TESTE='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO_EXT='".$rscontadore[ID_DISPARO]."'";
                               //  echo $uplote.'<br>';
                                 mysqli_query($contemporaria, $uplote);
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
                                     mysqli_query($contemporaria, $inslote);   
                             }
                        }    
                        //inserir contadores do relatorio
                        $entregasms="SELECT * FROM controle_entrega_sms WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO='".$rscontadore[ID_DISPARO]."'";
                       $rwentregasms= mysqli_query($contemporaria, $entregasms);
                      
                     //   echo $entregasms.'<br>';
                        if($rwentregasms->num_rows > '0')
                        {
                            $updateentrega="UPDATE controle_entrega_sms 
                                                SET  qtd_disparados=$total, 
                                                     qtd_sucesso=$rscontadore[COD_CCONFIRMACAO], 
                                                     qtd_falha= $rscontadore[BOUNCE],
                                                     QTD_AGUARADANDO='0',
                                                     QTD_CCONFIRMACAO=$rscontadore[COD_CCONFIRMACAO],
                                                     QTD_NRECEBIDO=$rscontadore[COD_NRECEBIDO],
                                                     QTD_OPTOUT=$rscontadore[COD_OPTOUT_ATIVO],
                                                     log_teste='$rscontadore[LOG_TESTE]'
                                            WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='".$rsempresa['COD_EMPRESA']."' AND COD_CAMPANHA='".$rscontadore[COD_CAMPANHA]."' AND COD_DISPARO='".$rscontadore[ID_DISPARO]."';";
                            mysqli_query($contemporaria,$updateentrega);
                           //echo $updateentrega.'<br>';
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
                                                                                log_teste) 
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
                                                                                '$rscontadore[LOG_TESTE]'
                                                                                );";
		               mysqli_query($contemporaria,$entregue);
                             //echo $entregue.'<br>';
                        }
                }
            }
        }
       */
       
     //  mysqli_close($rwupdatecliente);
     echo 'EXECUTADO EM TODAS AS EMPRESAS'; 
}
    
    
   ?>