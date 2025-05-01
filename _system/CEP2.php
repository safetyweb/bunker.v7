<?php
function consulta_cep($ARRAYDADOS)
{
    if($ARRAYDADOS['CEP']!='')
    {
      $url="https://viacep.com.br/ws/".$ARRAYDADOS['CEP']."/json/";  
    }else{
     $url="https://viacep.com.br/ws/".$ARRAYDADOS['ESTADO']."/".$ARRAYDADOS['CIDADE']."/".$ARRAYDADOS['RUA']."/json/";   
    }    
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_SSL_VERIFYPEER => false,  
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_POSTFIELDS => "",
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic Lolae-1d6af44d-cc0c-4bd7-9448-9774400222de",
                "Postman-Token: 0ac725e9-20dd-4c12-a181-7a761e9eead9",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              if($ARRAYDADOS['CEP']!='')
              {    
                $responsearray[]= json_decode($response,TRUE); 
              } else {
                $responsearray= json_decode($response,TRUE); 
              }              
              return $responsearray;
            }
}

  
?>
