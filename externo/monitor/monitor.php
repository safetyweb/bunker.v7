<?php

include '../../_system/_functionsMain.php';
include '../../externo/email/envio_sac.php';
$connAdmVAR=$connAdm->connAdm();
$alerta_venda = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -1400 minutes"));
//time de execução
//time de execução
$cap_emoresa="SELECT COD_EMPRESA,NOM_FANTASI from empresas e WHERE e.log_ativo='S'";

$rwempresa=mysqli_query($connAdmVAR, $cap_emoresa);
    
while ($dados_empresa=mysqli_fetch_assoc($rwempresa))
{
    ob_start();  
    $COD_EMPRESA=$dados_empresa['COD_EMPRESA'];
    $NOM_FANTASI=$dados_empresa['NOM_FANTASI'];
   //monatando a string de conexao temporaria
    $conntempvar= connTemp($dados_empresa['COD_EMPRESA'], '');
    //pegando as unidade de venda para monitorar 
    $SEL_UNIDADE="SELECT COD_UNIVEND,NOM_FANTASI FROM  unidadevenda WHERE cod_empresa='$COD_EMPRESA' AND LOG_ESTATUS='S' ";
    $UNIRW=mysqli_query($conntempvar, $SEL_UNIDADE);
    if(!$UNIRW)
	{	
    echo '<br>erro<br>';  
	}
	
    while ($COD_UNIVEND= mysqli_fetch_assoc($UNIRW))
    {
        
        
      //===============inicio da venda========================================    
        
        $ULT_COD_VENDA="SELECT * FROM vendas                     
                                 WHERE
                                 cod_empresa='$COD_EMPRESA' AND 
                                 cod_univend='".$COD_UNIVEND['COD_UNIVEND']."'
                                 order by COD_VENDA  DESC LIMIT 1;";
        $rwxmlvenda=mysqli_fetch_assoc(mysqli_query($conntempvar, $ULT_COD_VENDA));
                
        //venda monitor tip_dados=1
            $PROCURALOG="SELECT COD_MONITOR,
                                DAT_EXECUTADO,
                                DT_ULTVENDA,
                                COD_XMLVENDA,
                                DES_XMLVENDA,
                                COD_EMPRESA,
                                COD_UNIVEND,
                                tip_dados
        FROM log_monitor WHERE  tip_dados=1 and cod_empresa='".$COD_EMPRESA."' AND cod_univend=".$COD_UNIVEND['COD_UNIVEND'];
        $rsvenda=mysqli_query($conntempvar, $PROCURALOG);
        $verirw=mysqli_fetch_assoc($rsvenda);       
        if($verirw['tip_dados']=='')
        { 
            //vazio inserir registro novo.
            //=================verificando venda==================
            //origemvenda
            //pegando o ultimo codigo de venda para inserir na table de relatorios
          
            if(mysqli_num_rows($rsvenda)>='0')
            {
                if($verirw['COD_UNIVEND']!=='' || $verirw['COD_UNIVEND']!=='0')
                {    
                $sqlinsertvenda=' INSERT INTO   log_monitor (   DAT_EXECUTADO,
                                                                DT_ULTVENDA,
                                                                COD_XMLVENDA,
                                                                DES_XMLVENDA,
                                                                COD_EMPRESA,
                                                                COD_UNIVEND,
                                                                tip_dados) values
                                                            (
                                                                NOW(),
                                                               "'.$rwxmlvenda['DAT_CADASTR'].'",
                                                               "'.$rwxmlvenda['COD_VENDA'].'",
                                                               "OK",
                                                               "'.$rwxmlvenda['COD_EMPRESA'].'",
                                                               "'.$rwxmlvenda['COD_UNIVEND'].'",
                                                               "1"	
                                                            );';
                mysqli_query($conntempvar, $sqlinsertvenda);
                                
                }
                
            }
        } else {
              //atualiza regsitro
              //origemvenda
              //pegando o ultimo codigo de venda para inserir na table de relatorios
            if($rwxmlvenda['COD_ORIGEM']!=$verirw['COD_XMLVENDA'])
            {    
                $UPDATE_VENDA="UPDATE log_monitor SET DAT_EXECUTADO=NOW(),
                                                      DT_ULTVENDA='".$rwxmlvenda['DAT_CADASTR']."',
                                                      COD_XMLVENDA='".$rwxmlvenda['COD_VENDA']."',
                                                      DES_XMLVENDA='".$rwxmlvenda['MSG']."'							   
                                WHERE  COD_EMPRESA='".$rwxmlvenda['COD_EMPRESA']."' AND COD_UNIVEND='".$rwxmlvenda['COD_UNIVEND']."' AND tip_dados=1;";
                mysqli_query($conntempvar, $UPDATE_VENDA);

               //echo '<br><br><br><br>'.$UPDATE_VENDA.'<br><br><br><br>';
            }  
        }

  //===============fim da venda========================================
  //===============Inicio do Cadastro========================================
        $ULT_COD_CAD="SELECT * FROM clientes                       
                                 WHERE
                                 cod_empresa='$COD_EMPRESA' AND 
                                 cod_univend='".$COD_UNIVEND['COD_UNIVEND']."' 
                                 order by COD_CLIENTE  DESC LIMIT 1;";
        $rwxmlcad=mysqli_fetch_assoc(mysqli_query($conntempvar, $ULT_COD_CAD));
        
        
        //CADASTRO monitor tip_dados=2
            $PROCURALOGcad="SELECT COD_MONITOR,
                                DAT_EXECUTADO,
                                DT_ULTCADCLIENTE,
                                COD_XMLCLIENTE,
                                DES_XMLCLIENTE,
                                COD_EMPRESA,
                                COD_UNIVEND,
                                tip_dados

        FROM log_monitor WHERE  tip_dados=2 and cod_empresa='".$COD_EMPRESA."' AND cod_univend=".$COD_UNIVEND['COD_UNIVEND'];
		$rscad=mysqli_query($conntempvar, $PROCURALOGcad);
        $verirwcad=mysqli_fetch_assoc($rscad);
	
		
        if($verirwcad['tip_dados']=='')
        {
	        //vazio inserir registro novo.
            //pegando o ultimo codigo de venda para inserir na table de relatorios
            if(mysqli_num_rows($rscad)>='0')
            {
                //if($verirwcad['COD_UNIVEND']!=='' || $verirwcad['COD_UNIVEND']!=='0')
                //{ 
                    $sqlinsertCAD=' INSERT INTO   log_monitor (   DAT_EXECUTADO,
                                                                    DT_ULTCADCLIENTE,
                                                                    COD_XMLCLIENTE,
                                                                    DES_XMLCLIENTE,
                                                                    COD_EMPRESA,
                                                                    COD_UNIVEND,
                                                                    tip_dados) values
                                                                (
                                                                    NOW(),
                                                                   "'.$rwxmlcad['DAT_CADASTR'].'",
                                                                   "'.$rwxmlcad['COD_CLIENTE'].'",
                                                                   "OK",
                                                                   "'.$rwxmlcad['COD_EMPRESA'].'",
                                                                   "'.$rwxmlcad['COD_UNIVEND'].'",
                                                                   "2"	
                                                                ); ';
                mysqli_query($conntempvar, $sqlinsertCAD);  
               
               
                //} 
                
            }
        } else {
			
              //atualiza regsitro
              //origemvenda
              //pegando o ultimo codigo de venda para inserir na table de relatorios
			
            //if($rwxmlcad['COD_ORIGEM']!=$verirwcad['COD_XMLVENDA'])
            //{    
                $UPDATE_CAD="UPDATE log_monitor SET DAT_EXECUTADO=NOW(),
                                                      DT_ULTCADCLIENTE='".$rwxmlcad['DAT_CADASTR']."',
                                                      COD_XMLCLIENTE='".$rwxmlcad['COD_CLIENTE']."',
                                                      DES_XMLCLIENTE='".$rwxmlcad['MSG']."'							   
                                WHERE  COD_EMPRESA='".$rwxmlcad['COD_EMPRESA']."' AND COD_UNIVEND='".$rwxmlcad['COD_UNIVEND']."' AND tip_dados=2;";
                mysqli_query($conntempvar, $UPDATE_CAD);                
			  echo '<br>Fim do update cadastro!<br>';
            //}
            
        }
  //===============Fim do Cadastro=========================================      
   
    }
    
    unset($NOMFANTASI2);
    unset($NOMFANTASI1);
        $delete="delete from log_monitor where COD_UNIVEND='0'";
        mysqli_query($conntempvar, $delete);     
        if(date('H:i')>='10:00' && date('H:i')<='10:05')
        {    
                //enviar email
                $des_email="SELECT * FROM monitor_lojas WHERE cod_empresa='".$COD_EMPRESA."'";
                $rwemail=mysqli_fetch_assoc(mysqli_query($connAdmVAR,$des_email));


                $log_monitor="SELECT * FROM log_monitor WHERE cod_empresa='".$COD_EMPRESA."'";
                $LOGRS=mysqli_query(connTemp($COD_EMPRESA, ''),$log_monitor);
                while ($log_monitorrs=mysqli_fetch_assoc($LOGRS)){        


                    if($log_monitorrs['tip_dados']=='1')
                    {


                        if($alerta_venda <= $log_monitorrs['dt_ultvenda'])
                        {

                        } else{
                           $NOM_FANTATI13="SELECT * FROM unidadevenda 
                                        WHERE COD_EMPRESA=$COD_EMPRESA AND COD_UNIVEND='".$log_monitorrs['cod_univend']."'";
                            $rwfantasi=mysqli_fetch_assoc(mysqli_query($conntempvar,$NOM_FANTATI13));
                            $NOMFANTASI1.=$rwfantasi['NOM_FANTASI'].' '.','.'<br>';
                           $cod1=1; 
                        }
                    }

                    if ($log_monitorrs['tip_dados']=='2') 
                    {
                        if($alerta_venda <= $log_monitorrs['dt_ultcadcliente'])
                        {


                        } else{
                         $NOM_FANTATI24="SELECT * FROM unidadevenda 
                                    WHERE COD_EMPRESA=$COD_EMPRESA AND COD_UNIVEND='".$log_monitorrs['cod_univend']."'";
                            $rwfantasi=mysqli_fetch_assoc(mysqli_query($conntempvar,$NOM_FANTATI24));
                            $NOMFANTASI2.=$rwfantasi['NOM_FANTASI'].' '.','.'<br>'; 
                            $cod2=2;   
                        }    
                   }
                }


                if($cod1=='1')
                {

                    $FANTASI1 = substr($NOMFANTASI1, 0, -5);   
                    unset($arrayemail);
                   $arrayemail =array ('email5'=>$rwemail['DES_EMAIL']);
                    $email= fnsacmail($arrayemail,
                            $COD_EMPRESA.'-'.$NOM_FANTASI,
                            'Você tem lojas que não estão comunicando com o bunker!<br>
                            LOJAS_vendas:'.$FANTASI1,
                            $COD_EMPRESA.'-'.$NOM_FANTASI,
                            $COD_EMPRESA.'-'.$NOM_FANTASI,
                            $connAdmVAR,
                            connTemp($COD_EMPRESA, ''),
                            3);  
                    echo '<pre>';
                    print_r($email);
                   echo '</pre>'; 
				   echo '<br><br>EMAIL ENVIADO<br><br>';
                }
                if($cod2==2)
                {

                    $FANTASI2 = substr($NOMFANTASI2, 0, -5);      
                    unset($arrayemail1);
                    $arrayemail1 =array ('email5'=>$rwemail['DES_EMAIL']);

                    $email1=fnsacmail($arrayemail1,
                         $COD_EMPRESA.'-'.$NOM_FANTASI,
                         'Você tem lojas  que não estão comunicando com o bunker!<br>
                          LOJAS_cadastro:'.$FANTASI2,
                         $COD_EMPRESA.'-'.$NOM_FANTASI,
                         $COD_EMPRESA.'-'.$NOM_FANTASI,
                         $connAdmVAR,
                         connTemp($COD_EMPRESA, ''),
                         $COD_EMPRESA);            
                    echo '<pre>';
                    print_r($email1);
                    echo '</pre>';
					 echo '<br><br>EMAIL ENVIADO<br><br>';
            }
            unset($NOMFANTASI2);
            unset($NOMFANTASI1);
        }
//echo '<br>EMPRESA:'.$NOM_FANTASI.'<br>';		
ob_end_flush();
ob_flush();
flush();
}
 
//=======
echo 'Finalizando a conexao<br>';
mysqli_close($conntempvar);
mysqli_close($connAdmVAR);
