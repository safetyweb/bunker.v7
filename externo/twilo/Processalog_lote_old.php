<?php
include_once '../../_system/_functionsMain.php';
// AND apar.cod_empresa=219
$retryLimit = 10; // Número máximo de tentativas
$retry = 0;

$conadmmysql=$connAdm->connAdm();
$sqlempresa = "select * from empresas emp
         INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
         WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S'";

$rwempresa = mysqli_query($conadmmysql, $sqlempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)){
    ob_start();
    $contemporaria= connTemp($rsempresa[COD_EMPRESA], '');
    echo 'Empresa :'. $rsempresa[COD_EMPRESA].'<br>';    
    
        $sqllog="SELECT * FROM log_nuxux WHERE  cod_empresa=$rsempresa[COD_EMPRESA] AND TIP_LOG=22 and LOG_PROCESSADO='N' limit 100000";
        $rwlog= mysqli_query($contemporaria, $sqllog);
        while ($rslog = mysqli_fetch_assoc($rwlog)) {           
           
            parse_str($rslog[LOG_JSON], $json_array);
          
                if($json_array[SmsStatus]=='failed')
                {
                     $sqlbounceARRAY[$json_array[SmsSid]]=ARRAY('CHAVE_CLIENTE'=>$json_array[SmsSid],
                                                                            'cod_empresa'=>$rsempresa[COD_EMPRESA],
                                                                            'cod_campanha'=> $rslog[COD_CAMPANHA],
                                                                            'STATUS'=>$json_array[SmsStatus],
                                                                            'ERRO'=>'1'  );
                }
                if($json_array[SmsStatus]=='delivered' || $json_array[SmsStatus]=='received' || $json_array[SmsStatus]=='sent')
                {	
                   $sqlleitura1ARRAY[$json_array[SmsSid]]=ARRAY('CHAVE_CLIENTE'=>$json_array[SmsSid],
                                                                        'cod_empresa'=>$rsempresa[COD_EMPRESA],
                                                                        'cod_campanha'=>$rslog[COD_CAMPANHA],
                                                                        'STATUS'=>'delivered',
                                                                        'ERRO'=>'2'  );
               }

                if($json_array[SmsStatus]=='undelivered')
                {	
                      $sqlNRECEBIDOARRAY[$json_array[SmsSid]]=ARRAY('CHAVE_CLIENTE'=>$json_array[SmsSid],
                                                                        'cod_empresa'=>$rsempresa[COD_EMPRESA],
                                                                        'cod_campanha'=>$rslog[COD_CAMPANHA],
                                                                        'STATUS'=>$json_array[SmsStatus],
                                                                        'ERRO'=>'2'  );
               }
               
               if($json_array[SmsStatus]=='canceled')
                {	
                      $sqlcanceledARRAY[$json_array[SmsSid]]=ARRAY('CHAVE_CLIENTE'=>$json_array[SmsSid],
                                                                        'cod_empresa'=>$rsempresa[COD_EMPRESA],
                                                                        'cod_campanha'=>$rslog[COD_CAMPANHA],
                                                                        'STATUS'=>$json_array[SmsStatus],
                                                                        'ERRO'=>'2'  );
                }
               
               
               //Log que será marcado com ja processado.
                $CODLOG.=$rslog[ID_LOG].',';
            }  
            
             //atualização de log do token
                $token="SELECT * FROM log_nuxux 
                            WHERE  
                            TIP_LOG='22' AND LOG_PROCESSADO='N' and
                            CHAVE_CLIENTE IN (SELECT CHAVE_CLIENTE FROM rel_geratoken WHERE 
                                                            DAT_CADAST>=DATE_SUB(NOW(), INTERVAL 1 day) AND
                                                            COD_EMPRESA=".$rsempresa[COD_EMPRESA]." AND DES_msg IN ('queued','sent') AND TIP_ENVIO=1 )
                            LIMIT 10";
                $rwtoken= mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$token); 
                while($rstokrn= mysqli_fetch_assoc($rwtoken))
                {
                    parse_str($rstokrn[LOG_JSON], $json_token);
                    $ALTTOKENDADOS.="UPDATE rel_geratoken SET DES_MSG='$json_token[SmsStatus]' WHERE  CHAVE_CLIENTE='$json_token[SmsSid]' AND cod_empresa=$rsempresa[COD_EMPRESA];";
                    $ALTTOKENDADOS.="UPDATE SMS_LISTA_RET SET DES_STATUS='$json_token[SmsStatus]' WHERE  CHAVE_CLIENTE='$json_token[SmsSid]' AND cod_empresa=$rsempresa[COD_EMPRESA];";
                    $upe=mysqli_multi_query(connTemp($rsempresa[COD_EMPRESA], ''),$ALTTOKENDADOS);
                    if(!$upe)
                    {
                        echo '<br>'.$ALTTOKENDADOS.'<br>';
                    }   
                    unset($ALTTOKENDADOS);
                      
                }        
                
                 //********************************CANCELADO************************************************************************************

                    $total4 = count(array_keys($sqlcanceledARRAY)); //total items in array 
                    if($total4 > 0){
                        $limit1 =1000; //per page    
                        if($total4 < $limit1)
                        {
                         $limit1=$total4;    
                        }    
                        $totalPages1 = ceil($total4/$limit1); //calculate total pages
                        $COUNTPAGA1='0';
                        for ($i1 = 1; $totalPages1 ; $i1++) {
                            foreach ($sqlcanceledARRAY as $key1 => $value1) {
                               if($sobraarray=='1' || $total4=='1' )
                                {
                                   $canceled1="UPDATE sms_lista_ret SET
                                                                   DES_STATUS='".$value1[STATUS]."',    
                                                                   BOUNCE='2',
                                                                   COD_LEITURA='0',
                                                                   COD_CCONFIRMACAO='0',
                                                                   COD_SCONFIRMACAO='0',
                                                                   COD_NRECEBIDO='0' 
                                                                   WHERE CHAVE_CLIENTE = '$value1[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value1[cod_empresa] and 
                                                                   cod_campanha=$value1[cod_campanha];";
                                   echo $canceled1.'<br>';
                                    $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$canceled1);  
                                    sleep(1);
                                   // mysqli_next_result($contemporaria);
                                } else{   
                                    if($COUNTPAGA1 <= $limit1)
                                    {    
                                        $CLIENTENEXUX1.= "'".$value1[CHAVE_CLIENTE]."',";
                                        unset($sqlcanceledARRAY[$key1]); 
                                        $COUNTPAGA1++; 
                                    } 
                                     if($limit1==$COUNTPAGA1){ 
                                        $CLIENTENEXUX1= rtrim($CLIENTENEXUX1,',');
                                          $canceled="UPDATE sms_lista_ret SET
                                                                            DES_STATUS='".$value1[STATUS]."',
                                                                            BOUNCE='2',
                                                                           COD_LEITURA='0',
                                                                           COD_CCONFIRMACAO='0',
                                                                           COD_SCONFIRMACAO='0',
                                                                           COD_NRECEBIDO='0' 
                                                                           WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX1) AND
                                                                           cod_empresa=$value1[cod_empresa] and 
                                                                           cod_campanha=$value1[cod_campanha];";
                                          $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$canceled); 
                                          sleep(1);
                                          if(!$testeerro){echo 'erro no bounce<br>: '.$canceled; }    
                                         // mysqli_next_result($contemporaria);
                                        $sobraarray = count(array_keys($sqlcanceledARRAY)); //total items in array 
                                        unset($canceled);
                                        unset($CLIENTENEXUX1);
                                        
                                        break;    
                                    }
                                }    
                                    continue;
                            }

                            $COUNTPAGA1='0';
                            IF($totalPages1 <= $i1)
                            {
                                break;      
                            }    
                        }
                        unset($sqlcanceledARRAY); 
                    }
                
                
                
                
                
                //********************************bounce************************************************************************************

                    $total1 = count(array_keys($sqlbounceARRAY)); //total items in array 
                    if($total1 > 0){
                        $limit1 =1000; //per page    
                        if($total1 < $limit1)
                        {
                         $limit1=$total1;    
                        }    
                        $totalPages1 = ceil($total1/$limit1); //calculate total pages
                        $COUNTPAGA1='0';
                        for ($i1 = 1; $totalPages1 ; $i1++) {
                            foreach ($sqlbounceARRAY as $key1 => $value1) {
                               if($sobraarray=='1' || $total1=='1' )
                                {
                                   $sqlbounce1="UPDATE sms_lista_ret SET
                                                                   DES_STATUS='".$value1[STATUS]."',    
                                                                   BOUNCE='1',
                                                                   COD_LEITURA='0',
                                                                   COD_CCONFIRMACAO='0',
                                                                   COD_SCONFIRMACAO='0',
                                                                   COD_NRECEBIDO='0' 
                                                                   WHERE CHAVE_CLIENTE = '$value1[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value1[cod_empresa] and 
                                                                   cod_campanha=$value1[cod_campanha];";
                                    $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlbounce1);  
                                    sleep(1);
                                    mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                } else{   
                                    if($COUNTPAGA1 <= $limit1)
                                    {    
                                        $CLIENTENEXUX1.= "'".$value1[CHAVE_CLIENTE]."',";
                                        unset($sqlbounceARRAY[$key1]); 
                                        $COUNTPAGA1++; 
                                    } 
                                     if($limit1==$COUNTPAGA1){ 
                                        $CLIENTENEXUX1= rtrim($CLIENTENEXUX1,',');
                                          $sqlbounce="UPDATE sms_lista_ret SET
                                                                            DES_STATUS='".$value1[STATUS]."',
                                                                            BOUNCE='1',
                                                                           COD_LEITURA='0',
                                                                           COD_CCONFIRMACAO='0',
                                                                           COD_SCONFIRMACAO='0',
                                                                           COD_NRECEBIDO='0' 
                                                                           WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX1) AND
                                                                           cod_empresa=$value1[cod_empresa] and 
                                                                           cod_campanha=$value1[cod_campanha];";
                                          $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlbounce); 
                                          sleep(1);
                                          if(!$testeerro){echo 'erro no bounce<br>: '.$sqlbounce; }    
                                          mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                        $sobraarray = count(array_keys($sqlbounceARRAY)); //total items in array 
                                        unset($sqlbounce);
                                        unset($CLIENTENEXUX1);
                                        
                                        break;    
                                    }
                                }    
                                    continue;
                            }

                            $COUNTPAGA1='0';
                            IF($totalPages1 <= $i1)
                            {
                                break;      
                            }    
                        }
                        unset($sqlbounceARRAY); 
                    }
                 //**********************************ENTRGUE************************************************************************************************   

                    $total2 = count(array_keys($sqlleitura1ARRAY)); //total items in array 
                   if($total2 > 0){
                        $limit2 =1000; //per page    
                        if($total2<$limit2)
                        {
                         $limit2=$total2;    
                        }    
                        $totalPages2 = ceil($total2/$limit2); //calculate total pages
                        $COUNTPAGA2='0';
                        for ($i2 = 1; $totalPages2 ; $i2++) {
                            foreach ($sqlleitura1ARRAY as $key2 => $value2) {
                               if($sobraarray2=='1' || $total2=='1' )
                                {
                                   $sqlleitura="UPDATE sms_lista_ret SET DES_STATUS='".$value2[STATUS]."', BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0'
                                                                   WHERE CHAVE_CLIENTE = '$value2[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value2[cod_empresa] and 
                                                                   cod_campanha=$value2[cod_campanha];";
                                    $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlleitura); 
                                    unset($sqlleitura);
                                    sleep(1);
                                    mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                } else{   
                                    if($COUNTPAGA2 <= $limit2)
                                    {    
                                        $CLIENTENEXUX2.= "'".$value2[CHAVE_CLIENTE]."',";
                                        unset($sqlleitura1ARRAY[$key2]); 
                                        $COUNTPAGA2++; 
                                    } 
                                     if($limit2==$COUNTPAGA2){
                                        do { 
                                            if ($retry > 0) {
                                                    // Espera um segundo antes de tentar novamente para evitar imediatos
                                                    sleep(10);
                                                }

                                                $retry++;
                                            try { 
                                              $CLIENTENEXUX2= rtrim($CLIENTENEXUX2,',');
                                              $sqlleitura1="UPDATE sms_lista_ret SET DES_STATUS='".$value2[STATUS]."', BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0'
                                                                               WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX2) AND
                                                                               cod_empresa=$value2[cod_empresa] and 
                                                                               cod_campanha=$value2[cod_campanha];";
                                            $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlleitura1);
                                            if(!$testeerro){echo 'erro no ENTREGUE111::  <br>'.$sqlleitura1; }  

                                            mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                            $sobraarray2 = count(array_keys($sqlleitura1ARRAY)); //total items in array 
                                            unset($sqlleitura1);
                                            unset($CLIENTENEXUX2);                                       
                                            break; 
                                            } catch (mysqli_sql_exception $e) {
                                                 if (strpos($e->getMessage(), "Deadlock found when trying to get lock") !== false) {
                                                    // Lidar com o deadlock, ou seja, tentar novamente
                                                    if ($retry >= $retryLimit) {
                                                        // Número máximo de tentativas atingido
                                                        echo "Número máximo de tentativas de atualização atingido. Não foi possível evitar o deadlock.";
                                                        break;
                                                    }
                                                } else {
                                                    // Outra exceção, lidar de acordo com sua necessidade
                                                    echo "Erro: " . $e->getMessage();
                                                    echo "Número máximo de tentativas de atualização atingido. Não foi possível evitar o deadlock.";
                                                   // $mysqli->rollback();
                                                    break;
                                                }
                                            }
                                        } while (true);    
                                    }
                                }    
                                    continue;
                            }

                            $COUNTPAGA2='0';
                            IF($totalPages2 <= $i2)
                            {
                                break;      
                            }    
                        }
                        unset($sqlleitura1ARRAY);    
                   }
                //+++++++++++++++++nao recebido+++++++++++++++++++++++++

                    $total23 = count(array_keys($sqlNRECEBIDOARRAY)); //total items in array 
                    if($total23>0){
                        $limit23 =1000; //per page    
                        if($total23<$limit23)
                        {
                         $limit23=$total23;    
                        }    
                        $totalPages23 = ceil($total23/$limit23); //calculate total pages
                        $COUNTPAGA23='0';
                        for ($i23 = 1; $totalPages23 ; $i23++) {
                            foreach ($sqlNRECEBIDOARRAY as $key23 => $value23) {
                               if($sobraarray23=='1' || $total23=='1' )
                                {
                                   $sqlNRECEBIDO="UPDATE sms_lista_ret SET DES_STATUS='".$value23[STATUS]."', BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                                   WHERE CHAVE_CLIENTE = '$value23[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value23[cod_empresa] and 
                                                                   cod_campanha=$value23[cod_campanha];";
                                    $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlNRECEBIDO);  
                                    sleep(1);
                                    mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                } else{   
                                    if($COUNTPAGA23 <= $limit23)
                                    {    
                                        $CLIENTENEXUX23.= "'".$value23[CHAVE_CLIENTE]."',";
                                        unset($sqlNRECEBIDOARRAY[$key23]); 
                                        $COUNTPAGA23++; 
                                    } 
                                     if($limit23==$COUNTPAGA23){ 
                                        $CLIENTENEXUX23= rtrim($CLIENTENEXUX23,',');
                                          $sqlNRECEBIDO="UPDATE sms_lista_ret SET  DES_STATUS='".$value23[STATUS]."', BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                        WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX23) AND
                                                        cod_empresa=$value23[cod_empresa] and 
                                                        cod_campanha=$value23[cod_campanha];";
                                        $testeerro=mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$sqlNRECEBIDO); 
                                        sleep(1);
                                         if(!$testeerro){echo 'erro no NAO RECEBIDO::::   <br>'.$sqlNRECEBIDO; } 
                                        mysqli_next_result(connTemp($rsempresa[COD_EMPRESA], ''));
                                        $sobraarray23 = count(array_keys($sqlNRECEBIDOARRAY)); //total items in array 
                                        unset($sqlNRECEBIDO);
                                        unset($CLIENTENEXUX23);
                                          
                                        break;    
                                    }
                                }    
                                    continue;
                            }

                            $COUNTPAGA23='0';
                            IF($totalPages23 <= $i23)
                            {
                                break;      
                            }    
                        }
                        unset($sqlNRECEBIDOARRAY); 
                    }
                    ob_end_flush();
                    ob_flush();
                    flush();
                   $lidosalt= "UPDATE log_nuxux SET LOG_PROCESSADO='S' WHERE  ID_LOG in (".rtrim($CODLOG,',').")";
                    mysqli_query(connTemp($rsempresa[COD_EMPRESA], ''),$lidosalt); 
           
                 
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++    
                mysqli_close(connTemp($rsempresa[COD_EMPRESA], ''));
}