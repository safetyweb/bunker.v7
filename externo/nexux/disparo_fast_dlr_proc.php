<?php
include '../../_system/_functionsMain.php';
// AND apar.cod_empresa=219
$stringname='TOKEN';
$conadmmysql=$connAdm->connAdm();
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='17' AND apar.LOG_ATIVO='S'";
$rwempresa = mysqli_query($conadmmysql, $sqlempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)){
    ob_start();
    $contemporaria= connTemp($rsempresa[COD_EMPRESA], '');
    echo 'Empresa :'. $rsempresa[COD_EMPRESA].'<br>';    
    
        $sqllog="SELECT LOG_JSON,ID_LOG FROM log_nuxux WHERE  cod_empresa=$rsempresa[COD_EMPRESA] AND TIP_LOG='3' and LOG_PROCESSADO='N' ";
        $rwlog= mysqli_query($contemporaria, $sqllog);
        while ($rslog = mysqli_fetch_assoc($rwlog)) {
            
                $json_array=json_decode($rslog[LOG_JSON],true);                  
                    foreach($json_array as $KEY => $dados)
                    {

                        $dadoscampanha=explode('||',$dados[Cliente_ref]);
                        //$contemporaria= connTemp($empresa, '');
                        $EMPRESA=$dadoscampanha[1];
                        $CAMPANHA=$dadoscampanha[0];

                            if($dados[Situacao]=='9' || $dados[Situacao]=='99')
                            {
                                 $sqlbounceARRAY[$dados[MensagemUniqueId]]=ARRAY('CHAVE_CLIENTE'=>$dados[MensagemUniqueId],
                                                                                'cod_empresa'=>$EMPRESA,
                                                                                'cod_campanha'=>$CAMPANHA,
                                                                                'ERRO'=>'1'  );
                                 //  $sqlbounce="UPDATE sms_lista_ret SET BOUNCE='1',COD_LEITURA='0',COD_CCONFIRMACAO='0',COD_SCONFIRMACAO='0' WHERE CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' AND cod_empresa=$EMPRESA and cod_campanha=$CAMPANHA;";
                                //   mysqli_query($contemporaria,$sqlbounce);
                            }
                            if($dados[Situacao]=='10' || $dados[Situacao]=='2')
                            {	
                               $sqlleitura1ARRAY[$dados[MensagemUniqueId]]=ARRAY('CHAVE_CLIENTE'=>$dados[MensagemUniqueId],
                                                                                    'cod_empresa'=>$EMPRESA,
                                                                                    'cod_campanha'=>$CAMPANHA,
                                                                                    'ERRO'=>'2'  );

                                //    $sqlleitura1="UPDATE sms_lista_ret SET  BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0' WHERE CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' AND cod_empresa=$EMPRESA and cod_campanha=$CAMPANHA;";
                               //   mysqli_query($contemporaria,$sqlleitura1);
                            }

                            if($dados[Situacao]=='0' || $dados[Situacao]=='1' || $dados[Situacao]=='3' || $dados[Situacao]=='8')
                            {	
                                  $sqlNRECEBIDOARRAY[$dados[MensagemUniqueId]]=ARRAY('CHAVE_CLIENTE'=>$dados[MensagemUniqueId],
                                                                                    'cod_empresa'=>$EMPRESA,
                                                                                    'cod_campanha'=>$CAMPANHA,
                                                                                    'ERRO'=>'2'  );
                                //$sqlleitura3="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'  WHERE CHAVE_CLIENTE='".$dados[MensagemUniqueId]."' AND cod_empresa=$EMPRESA and cod_campanha=$CAMPANHA;";
                                //mysqli_query($contemporaria,$sqlleitura3);
                            }
                                       
                    }       
                //********************************bounce************************************************************************************

                    $total1 = count(array_keys($sqlbounceARRAY)); //total items in array 
                    if($total1 > 0){
                        $limit1 =500; //per page    
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
                                   $sqlbounce="UPDATE sms_lista_ret SET
                                                                    BOUNCE='1',
                                                                   COD_LEITURA='0',
                                                                   COD_CCONFIRMACAO='0',
                                                                   COD_SCONFIRMACAO='0',
                                                                   COD_NRECEBIDO='0' 
                                                                   WHERE CHAVE_CLIENTE = '$value1[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value1[cod_empresa] and 
                                                                   cod_campanha=$value1[cod_campanha];";
                                    $testeerro=mysqli_query($contemporaria,$sqlbounce);  
                                    mysqli_next_result($contemporaria);
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
                                                                            BOUNCE='1',
                                                                           COD_LEITURA='0',
                                                                           COD_CCONFIRMACAO='0',
                                                                           COD_SCONFIRMACAO='0',
                                                                           COD_NRECEBIDO='0' 
                                                                           WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX1) AND
                                                                           cod_empresa=$value1[cod_empresa] and 
                                                                           cod_campanha=$value1[cod_campanha];";
                                          $testeerro=mysqli_query($contemporaria,$sqlbounce); 
                                          if(!$testeerro){echo 'erro no bounce<br>: '.$sqlbounce; }    
                                          mysqli_next_result($contemporaria);
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
                        $limit2 =500; //per page    
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
                                   $sqlleitura1="UPDATE sms_lista_ret SET  BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0'
                                                                   WHERE CHAVE_CLIENTE = '$value2[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value2[cod_empresa] and 
                                                                   cod_campanha=$value2[cod_campanha];";
                                    $testeerro=mysqli_query($contemporaria,$sqlleitura1);  
                                    mysqli_next_result($contemporaria);
                                } else{   
                                    if($COUNTPAGA2 <= $limit2)
                                    {    
                                        $CLIENTENEXUX2.= "'".$value2[CHAVE_CLIENTE]."',";
                                        unset($sqlleitura1ARRAY[$key2]); 
                                        $COUNTPAGA2++; 
                                    } 
                                     if($limit2==$COUNTPAGA2){ 
                                          $CLIENTENEXUX2= rtrim($CLIENTENEXUX2,',');
                                          $sqlleitura1="UPDATE sms_lista_ret SET BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0'
                                                                           WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX2) AND
                                                                           cod_empresa=$value2[cod_empresa] and 
                                                                           cod_campanha=$value2[cod_campanha];";
                                        $testeerro=mysqli_query($contemporaria,$sqlleitura1);
                                           if(!$testeerro){echo 'erro no ENTREGUE::  <br>'.$sqlleitura1; }  
                                        mysqli_next_result($contemporaria);
                                        $sobraarray2 = count(array_keys($sqlleitura1ARRAY)); //total items in array 
                                        unset($sqlleitura1);
                                        unset($CLIENTENEXUX2);
                                       
                                        break;    
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
                        $limit23 =500; //per page    
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
                                   $sqlNRECEBIDO="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                                   WHERE CHAVE_CLIENTE = '$value23[CHAVE_CLIENTE]' AND
                                                                   cod_empresa=$value23[cod_empresa] and 
                                                                   cod_campanha=$value23[cod_campanha];";
                                    $testeerro=mysqli_query($contemporaria,$sqlNRECEBIDO);  
                                    mysqli_next_result($contemporaria);
                                } else{   
                                    if($COUNTPAGA23 <= $limit23)
                                    {    
                                        $CLIENTENEXUX23.= "'".$value23[CHAVE_CLIENTE]."',";
                                        unset($sqlNRECEBIDOARRAY[$key23]); 
                                        $COUNTPAGA23++; 
                                    } 
                                     if($limit23==$COUNTPAGA23){ 
                                        $CLIENTENEXUX23= rtrim($CLIENTENEXUX23,',');
                                          $sqlNRECEBIDO="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                        WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX23) AND
                                                        cod_empresa=$value23[cod_empresa] and 
                                                        cod_campanha=$value23[cod_campanha];";
                                        $testeerro=mysqli_query($contemporaria,$sqlNRECEBIDO); 
                                         if(!$testeerro){echo 'erro no NAO RECEBIDO::::   <br>'.$sqlNRECEBIDO; } 
                                        mysqli_next_result($contemporaria);
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
                   $lidosalt= "UPDATE log_nuxux SET LOG_PROCESSADO='S' WHERE  ID_LOG=$rslog[ID_LOG]";
                    mysqli_query($contemporaria,$lidosalt); 
            }
                 
                //++++++++++++++++++++++++++++++++++++++++++++++++++++++++    
                mysqli_close( $contemporaria);
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
?>