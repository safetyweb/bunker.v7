<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://emoji-api.com/emojis?access_key=532a91b9554425127df2457d843dd9f71fb66d26',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: PHPSESSID=0kk3orudqbfip7ojvavbdsueq7'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo '<pre>';   
print_r(json_decode($response,true));
echo '</pre>';
