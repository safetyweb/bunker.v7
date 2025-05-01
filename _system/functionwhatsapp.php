<?php
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
    
    return $qr['qr'];
}

