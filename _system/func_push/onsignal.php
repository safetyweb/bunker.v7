<?php

function fnonsignal($app_id, $include_player_ids, $contents, $Authorization, $headings)
{

  $include_player_ids = str_replace(',', '","', $include_player_ids);

  /*echo '{
          "app_id": "' . $app_id . '",
          "data": {"foo": "bar"},
          "contents": {"en": "' . $contents . '"},
          "headings": {"en": "' . $headings . '"},
          "target_channel": "push",
          "include_subscription_ids": ["' . $include_player_ids . '"]    
          }';*/


  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://onesignal.com/api/v1/notifications',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_MAXREDIRS => 1000,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '
                            {
                            "app_id": "' . $app_id . '",
                            "data": {"foo": "bar"},
                            "contents": {"en": "' . $contents . '"},
                            "headings": {"en": "' . $headings . '"},
                            "target_channel": "push",
                            "include_subscription_ids": ["' . $include_player_ids . '"]    
                            }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: Basic ' . $Authorization
    ),
  ));

  $response = curl_exec($curl);

  if (curl_errno($curl)) {
    $response = 'Erro cURL: ' . curl_error($curl);
  }
  curl_close($curl);
  return $response;
}
/*
$app_id='3761ab4e-4c3b-432e-a69e-f8b792543e44';
$include_player_ids='c6187e6f-0510-418f-820e-4a453cd76734';
$contents='Não garantiu ainda os seus R$20 de bônus aqui na Duque? Basta comprar a partir de $120 ate o dia 18/11 e usa-lo na prox. compra!';
$Authorization='MjBiZTM4M2UtZTA5OS00NjVhLWJiOTYtN2E1MDFhNmJhMGQ5';
$headings='REDE DUQUE - BÔNUS DE R$20';
 
echo  fnonsignal($app_id,$include_player_ids,$contents,$Authorization,$headings);   
 * 
 */
