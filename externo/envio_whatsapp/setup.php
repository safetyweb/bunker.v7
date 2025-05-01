<?php

include '../../_system/_functionsMain.php';
echo '\n'.date('H:i:s') . "\n";

//===inicio status
function fnstatuswhatsapp($arraydados)
{
        $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $arraydados['url']."/api/v1/status",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$arraydados['Authorization']
      ),
    ));

    $response = curl_exec($curl);   
    curl_close($curl);     
    $status= json_decode($response,TRUE);
    return $status;
}
//----Status 
//===reinicia inicio da instancia
function fnreloadwhatsapp($arraydados)
{
        $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $arraydados['url']."/api/v1/reload",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$arraydados['Authorization']
      ),
    ));

    $response = curl_exec($curl);   
    curl_close($curl);     
    $status= json_decode($response,TRUE);
    return $status;
}
//----FIM
//
//====inicio qrcode
function fnqrcodwhatsapp($arraydados)
{
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $arraydados['url']."/api/v1/generate_qrcode",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$arraydados['Authorization']
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $qr=json_decode($response, true);
    
    return "<img src=".$qr[qr]." />";
}
//=====fim do qr cod====

function fndisparoWatsapp($arraydados)
{

        $cel = preg_replace("/[^0-9]/", "", $arraydados['NUM_CELULAR']);

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $arraydados['url']."/api/v1/send_message",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\r\n  \"menssage\": \"$arraydados[Textoenvio]\",\r\n  \"number\": \"$cel\"\r\n}",
          CURLOPT_HTTPHEADER => array(
            "Authorization: ".$arraydados['Authorization'],
            "cache-control: no-cache"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          
         $envio=json_decode($response, true);
         return $envio;
        }
}




$verbreak=0;
$sqlconfg="SELECT * FROM  configuracao_acesso WHERE COD_PARCOMU=13 AND LOG_STATUS='S'";
$rwconfig= mysqli_query($connAdm->connAdm(), $sqlconfg);
while ($rsconfig= mysqli_fetch_assoc($rwconfig))
{  
 
        $arraydados=array('conadmin'=> $connAdm->connAdm(),
                          'conempresa'=>connTemp($rsconfig['COD_EMPRESA'],''),
                          'cod_empresa'=>$rsconfig['COD_EMPRESA'],
                          'url'=>$rsconfig['DES_EMAILUS'],
                          'Authorization'=>$rsconfig['DES_AUTHKEY'],
                          'NUM_CELULAR'=>'',
                          'Textoenvio'=>''
                         );
        $teste=fnstatuswhatsapp($arraydados);
        //echo '<pre>';
        //print_r($teste);
        //echo '</pre>';
        if($teste['connected']!='')
        {
            //parametro e msg
            // lista de cliente 
            //mover para outra base de dados.
            //deletar da principal
           $sqlconfigmsg="SELECT * 
                   FROM COMUNICAAV_PARAMETROS 
                   WHERE LOG_ATIVO = '1' and DES_MENSAGEM IS NOT NULL AND cod_empresa=".$rsconfig['COD_EMPRESA']; 
           $rwparam=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlconfigmsg);
           
            while($RSconfigmsg=mysqli_fetch_assoc($rwparam))
            {        
                $MSGTEXT=$RSconfigmsg['DES_MENSAGEM'];

                //lista de clientes para enviar
                 $sqlcliente="select * FROM  IMPORT_COMUNICAAV WHERE 
                                     cod_empresa=".$rsconfig['COD_EMPRESA']." 
                                     AND cod_lista=$RSconfigmsg[COD_LISTA]";
                 $rwcliente= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlcliente);
                 if(!$rwcliente)
                 {
                    echo 'ERRO: '.$sqlcliente; 
                 }    
                 while ($rscliente= mysqli_fetch_assoc($rwcliente))
                 {
                   //verificar se tem clientes
                    if($rscliente['COD_REGISTRO']=='' )
                    {
                        $sqllistafalse="UPDATE comunicaav_parametros SET LOG_ATIVO='0' WHERE  COD_LISTA=$RSconfigmsg[COD_LISTA]";
                        mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqllistafalse);
                    }    
                     
                     
                     
                    echo '<br>'.$sqlcliente.'<br>';

                        $primeironome=explode(' ',$rscliente['NOM_CLIENTE'] );
                         $msgsbtr=str_replace('<#NOME>',$primeironome[0],$MSGTEXT);
                         $msgsbtr=str_replace('<#CELULAR>',fnmasktelefone($rscliente['NUM_CELULAR']),$msgsbtr);
                         $msgsbtr=str_replace('<#EMAIL>',$rscliente['DES_EMAILUS'],$msgsbtr);  
                         $msgsbtr=str_replace('"',"'",$msgsbtr);  
                         $msgsbtr=nl2br($msgsbtr,true);
                        // $msgsbtr= preg_replace('/[\n|\r|\n\r|\r\n]{2,}/','<br />', $msgsbtr);
                         $msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
                         //envio de msg
                          $arraydados1=array('conadmin'=> $connAdm->connAdm(),
                                             'conempresa'=>connTemp($rsconfig['COD_EMPRESA'],''),
                                             'cod_empresa'=>$rsconfig['COD_EMPRESA'],
                                             'url'=>$rsconfig['DES_EMAILUS'],
                                             'Authorization'=>$rsconfig['DES_AUTHKEY'],
                                             'NUM_CELULAR'=>$rscliente['NUM_CELULAR'],
                                             'Textoenvio'=>$msgsbtr
                                             );


                          $vrenvioof=fndisparoWatsapp($arraydados1);
                          if($vrenvioof['status']=='1')
                         { 


                             echo '<br> entrou no envio....<br>';
                                 $sqlLOG="insert into log_import_comunicaav()
                                                                         select * FROM  IMPORT_COMUNICAAV WHERE 
                                                                        cod_empresa=".$rsconfig['COD_EMPRESA']." 
                                                                        AND cod_lista=$RSconfigmsg[COD_LISTA] AND 
                                                                       COD_REGISTRO=".$rscliente['COD_REGISTRO'];
                                $rwLOG= mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqlLOG) ;
                                 if(!$rwLOG)
                                 {
                                    echo 'erro ao inserir log';
                                 }else{

                                    $sqldeleteoficial="DELETE FROM import_comunicaav WHERE  COD_REGISTRO=".$rscliente['COD_REGISTRO'];
                                    $del=mysqli_query(connTemp($rsconfig['COD_EMPRESA'],''), $sqldeleteoficial);
                                    if(!$del)
                                    {
                                        echo '<br>ERRO: '.$del.'<br>';
                                    }else{
                                        echo '<br>'.$sqldeleteoficial.'<br>';
                                    }    
                                 } 
                         }       
                         //aguardar novo registor ser inserido.        
                         $numerseg=rand ( 1 , 5 ); 
                         unset($arraydados1);
                         $verbreak+=$numerseg;  
                         if ($verbreak >= '50') { 
                             echo date('H:i:s') . "\n";
                             flush();
                             exit;
                         }
                     sleep($numerseg);
                 }        
           }     
         //fim looping paramentros   
        } else {
             echo'Nao CONECTADO'; 
        }    
echo '\n'.date('H:i:s') . "\n";
unset($arraydados);   
}
