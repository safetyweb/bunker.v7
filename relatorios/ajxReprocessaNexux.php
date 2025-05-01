<?php

  include '../_system/_functionsMain.php';

  $cod_empresa = fnDecode($_POST['COD_EMPRESA']);
  $cod_campanha = fnDecode($_POST['COD_CAMPANHA']);
  $cod_disparo = fnDecode($_POST['COD_DISPARO']);

  // fnEscreve($cod_empresa);
  // fnEscreve($cod_campanha);
  // fnEscreve($cod_disparo);

  $curl = curl_init();
  curl_setopt_array($curl, array(
     CURLOPT_URL => 'http://externo.bunker.mk/nexux/contabiliza_dados.php?empresa='.$cod_empresa.'&disparo='.$cod_disparo,
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => "",
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 18000,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => "POST",
     CURLOPT_POSTFIELDS => "",
     CURLOPT_HTTPHEADER => array(
       "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
       "cache-control: no-cache"
   ),
 ));

 $response = curl_exec($curl);
 $err = curl_error($curl);

 curl_close($curl);

 // if ($err) {
 //   echo "cURL Error #:" . $err;
 // } else {
 //   echo $response;
 // }

?>