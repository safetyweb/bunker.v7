<?php

  include '_system/_functionsMain.php';

  $cod_empresa = fnDecode($_POST['COD_EMPRESA']);
  $cod_campanha = fnDecode($_POST['COD_CAMPANHA']);

  $sqlRefresh = "SELECT GROUP_CONCAT(COD_DISPARO_EXT) AS COD_DISPARO_EXT,
              date(DAT_AGENDAMENTO) AS DATA_INI
           FROM SMS_LOTE     
           WHERE COD_EMPRESA = $cod_empresa
           AND COD_CAMPANHA = $cod_campanha
           AND LOG_ENVIO = 'S'
           AND LOG_TESTE = 'N'        
           AND COD_DISPARO_EXT IS NOT NULL
           GROUP BY date(DAT_AGENDAMENTO), COD_CAMPANHA
           ORDER BY date(DAT_AGENDAMENTO) ASC";

  // fnEscreve($sqlRefresh);

  $arrayRefresh = mysqli_query(connTemp($cod_empresa,''),$sqlRefresh);

  $count = 1;
  $qtd_disparos = mysqli_num_rows($arrayRefresh);
  $dataini = "";
  $cod_disparo_loop = "";

  if($qtd_disparos > 0){

    while ($qrRefresh = mysqli_fetch_assoc($arrayRefresh)) {

      $cod_disparo = $qrRefresh[COD_DISPARO_EXT];
      // fnEscreve($cod_disparo);
      $dataini = $qrRefresh[DATA_INI];

      $arrayDisparo = explode(',', $cod_disparo);

      for ($i=0; $i < count($arrayDisparo); $i++) { 

          // fnEscreve('if');
          // fnEscreve('http://externo.bunker.mk/nexux/contabiliza_dados.php?empresa='.$cod_empresa.'&disparo='.$arrayDisparo[$i]);

          $curl = curl_init();
          curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://externo.bunker.mk/nexux/contabiliza_dados.php?empresa='.$cod_empresa.'&disparo='.$arrayDisparo[$i],
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
                          )
          );

          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);

          $cod_disparo_loop = "";
          $countRef += 10;

      }

      $cod_disparo = "";

      $count++;

    }

  }

  // setcookie("TEMPO_REFRESH", true, time() + (60 * 15));
  

$expiry = time() + (60 * 15);
$agoraMais15 = date("Y-m-d H:i:s");
$agoraMais15 = date("Y-m-d H:i:s", strtotime($agoraMais15." +15 minutes"));
$data = (object) array( "datExpira" => $agoraMais15);
$cookieData = (object) array( "data" => $data, "expiry" => $expiry );
setcookie( "TEMPO_REFRESH_SMS", json_encode( $cookieData ), $expiry );
// fnEscreve('executou');

?>