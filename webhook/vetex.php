<?php


include '../_system/_functionsMain.php';
$connAdm=$connAdm->connAdm();
$sql="SELECT * from WEBHOOK WHERE TIP_WEBHOOK=2 AND LOG_ESTATUS='S'";
$execute= mysqli_query($connAdm, $sql);
while($result= mysqli_fetch_assoc($execute))
{
    if($_GET['id']!='')
    {
      $cod_empresa=$_GET['id'];
    } else {
        $cod_empresa=$result['COD_EMPRESA'];
    }   
   
    if(isset($cod_empresa))
    { 
        $conntemp= connTemp($cod_empresa, ''); 
        $xmlteste=file_get_contents("php://input");
        //-======json to array
        $array= json_decode($xmlteste,TRUE);
       
        
        //=========================
        if($xmlteste!='')
        {    
            $insertLOG="INSERT INTO log_webhookvetex (  COD_EMPRESA, 
                                                        PROCESSADO, 
                                                        CPF,                                                    
                                                        COD_VENDA_VETEX,
                                                        LOG) 
                                                        VALUES ('$cod_empresa', 
                                                                '0', 
                                                                '0', 
                                                                '".$array['OrderId']."', 
                                                                '".addslashes($xmlteste)."');";
            mysqli_query($conntemp, $insertLOG);
            //Chamar a url para inserir o pedido.
            $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "http://externo.bunker.mk/vtex/setup.do?PEDIDO=".$array['OrderId']."&empresa=".$cod_empresa."",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "",
                  CURLOPT_HTTPHEADER => array(
                    "Postman-Token: 2c86697e-5fc0-467e-8995-283e231168bb",
                    "cache-control: no-cache"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                  echo $response;
                }
            //
        }else{
            
            //reprocessarvendas
             //Chamar a url para inserir o pedido.
            $cod=1;
            if($cod==12)
            {    
                $repro='SELECT COD_VENDA_VETEX from log_webhookvetex WHERE cod_empresa=101';
                
                $rsrepro=mysqli_query($conntemp, $repro);
                while ($rtrepro= mysqli_fetch_assoc($rsrepro))
                {      
                    
                        $curl = curl_init();

                            curl_setopt_array($curl, array(
                              CURLOPT_URL => "http://externo.bunker.mk/vtex/setup.do?PEDIDO=".$rtrepro['COD_VENDA_VETEX']."&empresa=".$cod_empresa."",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "POST",
                              CURLOPT_POSTFIELDS => "",
                              CURLOPT_HTTPHEADER => array(
                                "Postman-Token: 2c86697e-5fc0-467e-8995-283e231168bb",
                                "cache-control: no-cache"
                              ),
                            ));

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            curl_close($curl);

                            if ($err) {
                              echo "cURL Error #:" . $err;
                            } else {
                              echo $response;
                    }
                }    
                //====fim
                 echo 'não a vendas1';
            }
           echo 'não a vendas';
       }
    }else{
      echo 'EMPRESA NÃO CADASTRADA1!';  
    }    
    
}

IF(mysqli_num_rows($execute)==FALSE)
{
    ECHO 'EMPRESA NÃO CADASTRADA2!';  
}    
mysqli_close($conntemp);
mysqli_close($connAdm);
