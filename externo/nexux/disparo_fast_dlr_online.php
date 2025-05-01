<?php
include '../../_system/_functionsMain.php';
fnDebug('TRUE');
// AND apar.cod_empresa=219
$stringname='TOKEN';
$conadmmysql=$connAdm->connAdm();
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='17' AND apar.LOG_ATIVO='S'";
$rwempresa = mysqli_query($conadmmysql, $sqlempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)){
     unset($contadorinf);
     unset($arraydadosenvio);
     unset($dadoschavecli);
    //unset($arraydadosenvio); 
    $contemporaria= connTemp($rsempresa[COD_EMPRESA], '');
    $intervalo=3;
    $contadorpagina=1;
    //colcoar em bounce sem chave de consulta na base da nexux
    $alterar="UPDATE sms_lista_ret SET BOUNCE=1  WHERE BOUNCE=0 AND COD_LEITURA=0 AND CHAVE_CLIENTE='' and COD_EMPRESA=".$rsempresa[COD_EMPRESA];
    mysqli_query($contemporaria, $alterar);  
    echo '<br>lista se chave de consultas<br>';
               $buscaatualizacao="SELECT    ret.COD_LISTA,ret.CHAVE_CLIENTE,ret.COD_EMPRESA,g.COD_CAMPANHA,g.TIP_GATILHO     FROM sms_lista_ret ret
                                left JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA                             
                                WHERE 
                                ret.COD_EMPRESA=$rsempresa[COD_EMPRESA] AND 
				DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL  $intervalo  DAY))
				and ret.CHAVE_CLIENTE!='' 		
                                    and case  when        ret.COD_OPTOUT_ATIVO='0' 
                                                        AND ret.COD_LEITURA='0'
                                                        AND ret.COD_NRECEBIDO='0'
                                                        AND ret.COD_CCONFIRMACAO='0'
                                                        AND ret.BOUNCE='0'
                                                        AND  ret.COD_SCONFIRMACAO='0' 
                                                        then '1' ELSE '0' END IN (1) 
                                    and case when ret.CHAVE_CLIENTE is not NULL AND ret.CHAVE_CLIENTE != '0' then '1'  ELSE '0' end IN (1) order by COD_LISTA asc limit 999 ;";
									
	       $rwbuscaatualizacao=mysqli_query($contemporaria, $buscaatualizacao); 
	       if($rwbuscaatualizacao->num_rows > '0')
           {
                				   
                    $timecount='1'; 
                    $contadorlooping='1';  
                    while ($rsbuscaatualizacao=mysqli_fetch_assoc($rwbuscaatualizacao))
                    {
						/*	echo '<pre>';
						   print_r($rsbuscaatualizacao);
						   echo '</pre>';*/
			 			$COD_LISTA=$rsbuscaatualizacao[COD_LISTA];
                        $autenticacao=$rsempresa["DES_AUTHKEY"];
                        //|| $rsbuscaatualizacao[TIP_GATILHO]!='senhaApp'
                        if($rsbuscaatualizacao[TIP_GATILHO]!='tokenCad')
                        {   
                                                      
                             $autenticacao=$rsempresa["DES_AUTHKEY2"]; 
                        }
			
                        if(strstr($rsbuscaatualizacao[TIP_GATILHO],'token'))
                        {      
			                 $tipoenvio=true;
                            if($contadorlooping <= '1')
                            {
				                $clie= "update geratoken  ger  
                                                            INNER JOIN rel_geratoken rel ON rel.COD_GERATOKEN=ger.COD_TOKEN
                                                            INNER JOIN sms_lista_ret  ret ON ret.CHAVE_CLIENTE=rel.CHAVE_CLIENTE AND ret.CHAVE_GERAL=rel.CHAVE_GERAL
                                                            set ret.COD_CLIENTE=ger.COD_CLIENTE 
                                                            WHERE 
                                                            ger.COD_EMPRESA=".$rsempresa['COD_EMPRESA']." AND ger.LOG_USADO='2' 
                                                            AND ger.COD_EXCLUSA=0 AND ret.COD_CLIENTE='0'";
                                  $rwupdatecliente= mysqli_query($contemporaria, $clie);
			    }    
                            $contadorlooping ++;
                        }
                        //construir array com as senha de cada comunicação
                        $arraydadosenvio[$autenticacao][]=array('CHAVE_CLIENTE'=>$rsbuscaatualizacao[CHAVE_CLIENTE],
                                                                'COD_EMPRESA'=>$rsbuscaatualizacao[COD_EMPRESA],
                                                                'COD_CAMPANHA'=> $rsbuscaatualizacao[COD_CAMPANHA]  ,
                                                                'TIP_GATILHO'=>$rsbuscaatualizacao[TIP_GATILHO]
                                                                 );
                   
                     unset($autenticacao);
                     if($contadorpagina == $rwbuscaatualizacao->num_rows)
                     {
                          
 //######################################################################################
          	              /* echo '<pre>';
						   print_r($arraydadosenvio);
						   echo '</pre>'; 
						*/
                         //===============================================================================================================
                         foreach ($arraydadosenvio as $key50 => $dadoschavecli) {
						
                             unset($cheve);
                            foreach ($dadoschavecli as $key51 => $dadoschavecliconsulta) {
                                   $cheve.='"'.$dadoschavecliconsulta[CHAVE_CLIENTE].'",';
                            }
                           
                                    $cheve=rtrim($cheve,',');
                                
                                    //aqui é a execução curl
                              //   echo 'https://sms.nexuscomunicacao.com/api/sms/getstatus.aspx?chave='.$key50.'<br>';
							//	 echo '['.$cheve.']<br>';
								 
                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                      CURLOPT_SSL_VERIFYPEER=> false,
                                      CURLOPT_URL => 'https://sms.nexuscomunicacao.com/api/sms/getstatus.aspx?chave='.$key50,
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => '',
                                      CURLOPT_MAXREDIRS => 9000,
                                      CURLOPT_TIMEOUT => 360,
                                      CURLOPT_FOLLOWLOCATION => true,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => 'POST',
                                      CURLOPT_POSTFIELDS =>'['.$cheve.']',
                                      CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json'
                                      ),
                                    ));

                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);
                                    curl_close($curl);
                                     if ($err) {
                                      echo "cURL Error #:" . $err;
                                     }
                                      
                                   
                                   //=======================================================================================
                                                   $json_array=json_decode($response,true); 
                                                   echo '<pre>';
                                                   print_r($json_array);
                                                   echo '</pre>';
                                                  
                                                
                                                    foreach($json_array[Mensagens] as $KEY2 => $dados)
                                                    {
                                                        unset($dadoscampanha);
                                                        $dadoscampanha=explode('||',$dados[Cliente_ref]);
                                                        $EMPRESA=$dadoscampanha[1];
                                                        $CAMPANHA=$dadoscampanha[0];
                                                       
                                                            if($dados[Situacao]=='9' || $dados[Situacao]=='99')
                                                            {
                                                                 $sqlbounceARRAY[$dados[IdMensagem]]=ARRAY('CHAVE_CLIENTE'=>$dados[IdMensagem],
																											'cod_empresa'=>$EMPRESA,
																											'cod_campanha'=>$CAMPANHA,
																											'ERRO'=>'1'  );
															    echo 'bounce<br>';
                                                               
                                                            }
                                                            if($dados[Situacao]=='10' || $dados[Situacao]=='2')
                                                            {	
                                                               $sqlleitura1ARRAY[$dados[IdMensagem]]=ARRAY('CHAVE_CLIENTE'=>$dados[IdMensagem],
                                                                                                            'cod_empresa'=>$EMPRESA,
                                                                                                            'cod_campanha'=>$CAMPANHA,
                                                                                                            'ERRO'=>'2'  );
																	echo 'recebido<br>';
                                                            }

                                                            if($dados[Situacao]=='0' || $dados[Situacao]=='1' || $dados[Situacao]=='3' || $dados[Situacao]=='8')
                                                            {	
                                                                  $sqlNRECEBIDOARRAY[$dados[IdMensagem]]=ARRAY('CHAVE_CLIENTE'=>$dados[IdMensagem],
																												'cod_empresa'=>$EMPRESA,
																												'cod_campanha'=>$CAMPANHA,
																												'ERRO'=>'2'  );
																echo 'Não recebido<br>';												
                                                                  
                                                            }
                                                    }   
                                                  //********************************bounce************************************************************************************
                                                     $total1 = count(array_keys($sqlbounceARRAY)); //total items in array 
                                                    if($total1 > 0){
                                                        $limit1 =100; //per page    
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
                                                                                                        cod_empresa=$value1[cod_empresa]";
																		echo $sqlbounce.'<br>';								
                                                                         $testeerro=mysqli_query($contemporaria,$sqlbounce);  
                                                                         mysqli_next_result($contemporaria);
                                                                          unset($sqlbounce);
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
                                                                                                                cod_empresa=$value1[cod_empresa];";
																				echo $sqlbounce.'<br>';								
                                                                               $testeerro=mysqli_query($contemporaria,$sqlbounce);                                                                       
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
                                                   $total2 = count(array_keys($sqlleitura1ARRAY));//total items in array 
                                                   if($total2 > 0){
                                                        $limit2 =100; //per page    
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
                                                                       $sqlleitura="UPDATE sms_lista_ret SET  BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0'
                                                                                                       WHERE CHAVE_CLIENTE = '$value2[CHAVE_CLIENTE]' AND
                                                                                                       cod_empresa=$value2[cod_empresa] and 
                                                                                                       cod_campanha=$value2[cod_campanha];";
																		echo $sqlleitura.'<br>';							   
                                                                        $testeerro=mysqli_query($contemporaria,$sqlleitura);  
                                                                        mysqli_next_result($contemporaria);
                                                                        unset($sqlleitura);
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
                                                                                                               cod_empresa=$value2[cod_empresa]"; 
																			echo $sqlleitura1.'<br>';									   
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

                                                   $total23 = count(array_keys($sqlNRECEBIDOARRAY)); 
                                                    if($total23>0){
                                                        $limit23 =100; //per page    
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
                                                                        $sqlNRECEBIDO1="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                                                                        WHERE CHAVE_CLIENTE = '$value23[CHAVE_CLIENTE]' AND
                                                                                                        cod_empresa=$value23[cod_empresa] and 
                                                                                                        cod_campanha=$value23[cod_campanha];";
																		echo $sqlNRECEBIDO1.'<br>';								
                                                                         $testeerro=mysqli_query($contemporaria,$sqlNRECEBIDO1);  
                                                                        // mysqli_next_result($contemporaria);
                                                                     } else{   
                                                                         if($COUNTPAGA23 <= $limit23)
                                                                         {    
                                                                             $CLIENTENEXUX23.= "'".$value23[CHAVE_CLIENTE]."',";
                                                                             unset($sqlNRECEBIDOARRAY[$key23]); 
                                                                             $COUNTPAGA23++; 
                                                                         } 
                                                                          if($limit23==$COUNTPAGA23){ 
                                                                             $CLIENTENEXUX23= rtrim($CLIENTENEXUX23,',');
                                                                               $sqlNRECEBIDO1="UPDATE sms_lista_ret SET  BOUNCE='0', COD_LEITURA='0',COD_NRECEBIDO='1',COD_SCONFIRMACAO='0',COD_CCONFIRMACAO='0'
                                                                                             WHERE CHAVE_CLIENTE IN ($CLIENTENEXUX23) AND
                                                                                             cod_empresa=$value23[cod_empresa] ;";
																				echo $sqlNRECEBIDO1.'<br>';					 
                                                                             $testeerro=mysqli_query($contemporaria,$sqlNRECEBIDO1); 
                                                                              if(!$testeerro){echo 'erro no NAO RECEBIDO::::   <br>'.$sqlNRECEBIDO; } 
                                                                            // mysqli_next_result($contemporaria);
                                                                             $sobraarray23 = count(array_keys($sqlNRECEBIDOARRAY)); //total items in array 
                                                                             unset($sqlNRECEBIDO1);
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

                                    //=================================================================================
                                    unset($cheve);
                                    unset($OK);
                                }   
               
         //#########################################################################################      
     
                            $contadorpagina=1;
                      
                     }    
                     $contadorpagina++;
                   }
                  $contadorlooping='1';
               } 
    $contadorinf++;             
}
echo 'chegou no fim';
echo 'empresa:'. $rsempresa[COD_EMPRESA].'<br>'; 
/*
if($_GET['contador'] <= 100)
{
	$contador=$_GET['contador']+1;
	$cod_ini=$_GET['cod_ini'];
	$cod_ini=$_GET['codfim'];
	
	
	$COD_LISTAfim=$COD_LISTA+999;
	echo 'http://externo.bunker.mk/nexux/disparo_fast_dlr_online.php?contador='.$COD_LISTA.'-'.$COD_LISTAfim;
	header("Refresh: 3; url=http://externo.bunker.mk/nexux/disparo_fast_dlr_online.php?contador=".$contador);
}	
?>*/