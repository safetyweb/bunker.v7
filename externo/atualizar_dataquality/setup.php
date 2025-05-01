<?php
include '../../_system/_functionsMain.php';
$admc=$connAdm->connAdm ();
$SQL_BASES="	SELECT * FROM TAB_DATABASE tab
				INNER JOIN empresas emp ON emp.COD_EMPRESA = tab.COD_EMPRESA  and emp.LOG_ATIVO='S'
				where tab.NOM_DATABASE not in ('demo_novo','db_multc','db_drogaleste','db_ultrafarma','db_campea','db_procfit','db_retiro') group by tab.NOM_DATABASE";
$BASES_RS=mysqli_query($admc, $SQL_BASES);
WHILE($BASERW= mysqli_fetch_assoc($BASES_RS))
{
    $connUser_ATUAL = new BD($BASERW['IP'],
                             $BASERW['USUARIODB'],
                             fnDecode($BASERW['SENHADB']),
                            $BASERW['NOM_DATABASE']);
    $tmpc=$connUser_ATUAL->connUser();
  //pegar  o cliente e começar a checar
    $sqlcliente="SELECT NOM_CLIENTE,
                        num_cgcecpf,
                        DAT_NASCIME,
                        DES_EMAILUS,
                        NUM_CELULAR,
                        CASE
                           WHEN cod_sexopes = 1 THEN 'M'
                           WHEN cod_sexopes = 2 THEN 'F'
                           ELSE 'I' END AS sexo
            FROM clientes
            WHERE num_cgcecpf !='0'";
        $clrs1= mysqli_query($tmpc, $sqlcliente);
        while ($clrs= mysqli_fetch_assoc($clrs1))
        {
            //base data quality
            $dataql="select * from log_cpf where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."'";
            $rscpf=mysqli_fetch_assoc(mysqli_query($admc, $dataql));
           
                    ob_start();
                if($rscpf['DT_NASCIMENTO']=="")
                {
                        if($rscpf['cpf']!="")
                        { 
                            if($clrs['DAT_NASCIME']<>'')
                            {


                                            $updatedata="update log_cpf set DT_NASCIMENTO ='".$clrs['DAT_NASCIME']."' where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."';";
                                            mysqli_query($admc, $updatedata);
                                            echo'|CPF::>'.fnCompletaDoc($clrs['num_cgcecpf'],'F');
                                            echo 'datanascime era :>'.$rscpf['DT_NASCIMENTO'].'<BR> Passou a ser > '.$clrs['DAT_NASCIME'].'<BR>';
                                          //  echo $updatedata.'<BR>';

                                }else{
                                 // echo'data nascimento na base do dataqualit nao esta vazio <br>';
                                }
                        }    
                    }else{
                       // echo'data nascimento na base do cliente esta vazio <br>';
                    }
                    //atualiza sexo
                    if($clrs['sexo']!='I')
                    {
                        if($rscpf['SEXO']=='' || $rscpf['SEXO']=='I')
                        {    
                            
                                if($rscpf['SEXO']!=="M")
                                {
                                    $updatedata="update log_cpf set sexo ='".$clrs['sexo']."' where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."';";
                                        mysqli_query($admc, $updatedata);

                                      //  echo 'Sexo atualizado --'.$rscpf['SEXO'].'--.<BR>';
                                      //  echo $updatedata.'<br>';
                                }elseif($rscpf['SEXO']!='F')
                                {
                                    $updatedata="update log_cpf set sexo ='".$clrs['sexo']."' where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."';";
                                        mysqli_query($admc, $updatedata);

                                       // echo 'Sexo atualizado --'.$rscpf['SEXO'].'--.<BR>';
                                       // echo $updatedata.'<br>';
                                }else{
                                   //  echo 'Sexo IGUAL de F/M.=== '.$rscpf['SEXO'].'<BR>'; 
                                } 
                        }    
                    }else{
                        //  echo 'Sexo e iGUAL I=>'.$clrs['sexo'].'<BR>'; 
                    }
                    
                    //inserir na base de dados se nao exitir
                    if($rscpf['CPF']=="")
                    {
                       if($clrs['sexo']!='I') 
                       { 
                           IF(fnvalidacpf(fnCompletaDoc($clrs['num_cgcecpf'], 'F')))
                           {    
                                $insql="INSERT INTO log_cpf (DATA_HORA,
                                                             CPF,
                                                             NOME,
                                                             SEXO,
                                                             DT_NASCIMENTO,
                                                             COD_EMPRESA) VALUE
                                                             (NOW(),
                                                             '".fnCompletaDoc($clrs['num_cgcecpf'], 'F')."',
                                                             '".strtoupper($clrs['NOM_CLIENTE'])."',     
                                                             '".strtoupper($clrs['sexo'])."',                                                      
                                                             '".$clrs['DAT_NASCIME']."',
                                                              7   
                                                              )";   
                                //echo $insql.';<br>';
                                //mysqli_query($admc, $insql) ;
                               
                           }else{
                            // echo "CPF INVALIDO ".fnCompletaDoc($clrs['num_cgcecpf'],'F').'<br>';
                           }
                       }else{
                         //  echo 'Dados inconsistentes =='.$clrs['num_cgcecpf'].'<br>';
                       }
                    }else{
                      //  Echo 'Ja existe na base dataqualit<br>';
                    }    
                    //incluir email
                     //inserir na base de dados se nao exitir
                    if($rscpf['email']=="")
                    {
                       if($clrs['DES_EMAILUS']!='') 
                       { 
                               
                            $updatedata="update log_cpf set email ='".$clrs['DES_EMAILUS']."' where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."';";
                            mysqli_query($admc, $updatedata);
                            //echo $updatedata.'<br>';
                               
                           
                       }else{
                          // echo 'Dados inconsistentes =='.$clrs['DES_EMAILUS'].'<br>';
                       }
                    }else{
                        //Echo 'Ja existe na base dataqualit<br>';
                    }    
                    //celular
                    
                     if($rscpf['celular']=="")
                    {
                       if($clrs['NUM_CELULAR']!='') 
                       { 
                               
                             $updatedata="update log_cpf set celular ='".$clrs['NUM_CELULAR']."' where CPF='".fnCompletaDoc($clrs['num_cgcecpf'],'F')."';";
                             mysqli_query($admc, $updatedata);
                            // echo $updatedata.'<br>';
                        }else{
                         //  echo 'Dados inconsistentes =='.$clrs['num_cgcecpf'].'<br>';
                       }
                    }else{
                      //  Echo 'Ja existe na base dataqualit<br>';
                    }    
                    
                        ob_end_flush();
                        ob_flush();
                        flush();
        }        
   
   
  echo 'Database name ===='.$BASERW['NOM_DATABASE'].'----<BR>'  ;
  set_time_limit(18000);  
    
}        