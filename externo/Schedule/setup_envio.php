<?php
require '../../_system/_functionsMain.php';


ob_start();
date_default_timezone_set('Etc/GMT+3');
//carga ma lista blacklist sms
$horaatual = date('H:i:s');
$horaatual1 = date('d H:i');
echo '<br>' . $horaatual . '<br>';

//vencimento de creditos
$venci = curl_init();
curl_setopt_array($venci, array(
  CURLOPT_URL => 'http://externo.bunker.mk/Schedule/Validade_cred_comunicacao.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($venci);
$err = curl_error($venci);

curl_close($venci);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

/*$curl = curl_init();
             curl_setopt_array($curl, array(
               CURLOPT_URL => 'http://externo.bunker.mk/nexux/LISTA_DE_INVALIDOS.php',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 30,
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

             if ($err) {
               echo "cURL Error #:" . $err;
             } else {
               echo $response;
             }
*/
//envio twilo
/*$ret=shell_exec('curl --location --request POST "http://externo.bunker.mk/dinamize/ENVIO_SMS_TWILO.php"');         
    echo $ret;
   */

//envio sms nexux
/*  $curl = curl_init();
             curl_setopt_array($curl, array(
               CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_SMS.php',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => "",
               CURLOPT_MAXREDIRS => 100,
               CURLOPT_TIMEOUT => 180000,
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

             if ($err) {
               echo "cURL Error #:" . $err;
             } else {
               echo $response;
             }
  */


//relatorios email

$momentoenvio = '03:00:00,23:30:00';
$dadostime = explode(',', $momentoenvio);
foreach ($dadostime as $timeN) {
  $horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
  $horamaior1 = date('H:i:s', strtotime('+20 minute', strtotime(rtrim(trim($timeN)))));

  //echo "<br>".strtotime($horamenor1).'<='.strtotime($horaatual).'&&'.strtotime($horamaior1).'>='.strtotime($horaatual);
  if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/carga_relatorio.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 300,
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

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  } else {
    echo '<br> não é hora de atualizar relatorios<br>';
  }
  ob_end_flush();
  ob_flush();
  flush();
}
//relatorios email
$momentoenvio = '03:00:00,23:00:00';
$dadostime = explode(',', $momentoenvio);
foreach ($dadostime as $timeN) {
  $horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
  $horamaior1 = date('H:i:s', strtotime('+20 minute', strtotime(rtrim(trim($timeN)))));

  //echo "<br>".strtotime($horamenor1).'<='.strtotime($horaatual).'&&'.strtotime($horamaior1).'>='.strtotime($horaatual);
  if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://externo.bunker.mk/dinamize/CLCK_URL.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 300,
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

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  } else {
    echo '<br> não é hora de atualizar relatorios<br>';
  }
  ob_end_flush();
  ob_flush();
  flush();
}



#CARGA_ANALYTICS

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://externo.bunker.mk/Schedule/CARGA_ANALYTICS.php',
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

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
//envio do analitcs

//https://externo.bunker.mk/analitycs/envio_analytics.php
if ($horaatual1 == '02 05:00') {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://externo.bunker.mk/analitycs/envio_analytics.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
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

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
}

#ATUALIZACAO DO SEXO

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://externo.bunker.mk/atualizacao_sexo_cliente.php',
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

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

//relatorios NOVA VERSÃO SMS TOKEN VELOZES E FURIOSOS
//relatorios NOVA VERSÃO SMS TOKEN VELOZES E FURIOSOS

//$momentoenvio='07:00:00,08:30:00,09:00:00,09:30:00,10:00:00,11:00:00,12:00:00,15:00:00,17:00:00,20:00:00,22:00:00,23:30:00';
/*$momentoenvio='09:00:00,11:00:00,15:00:00,23:00:00';
$dadostime=explode(',',$momentoenvio);
foreach($dadostime as $timeN)
{

	$horamenor1= date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
	$horamaior1= date('H:i:s', strtotime('+5 minute', strtotime(rtrim(trim($timeN)))));    
	if(strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1)>= strtotime($horaatual))
	{
          
                $curlproc = curl_init();
                 curl_setopt_array($curlproc, array(
                   CURLOPT_URL => 'http://externo.bunker.mk/nexux/disparo_fast_dlr_online.php',
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 100,
                   CURLOPT_TIMEOUT => 300,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "POST",
                   CURLOPT_POSTFIELDS => "",
                   CURLOPT_HTTPHEADER => array(
                         "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
                         "cache-control: no-cache"
                   ),
                 ));

                 $responseproc = curl_exec($curlproc);
                 $err = curl_error($curlproc);

                 curl_close($curlproc);

                 if ($err) {
                   echo "cURL Error #:" . $err;
                 } else {
                   echo $responseproc;
                 }
	}	
}



//$momentoenvio='07:00:00,08:30:00,09:00:00,09:30:00,10:00:00,11:00:00,12:00:00,15:00:00,17:00:00,20:00:00,22:00:00,23:30:00';
$momentoenvio='09:00:00,10:00:00,14:00:00,16:00:00,23:00:00';
$dadostime=explode(',',$momentoenvio);
foreach($dadostime as $timeN)
{
   
	$horamenor1= date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
	$horamaior1= date('H:i:s', strtotime('+5 minute', strtotime(rtrim(trim($timeN)))));    
	if(strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1)>= strtotime($horaatual))
	{
          
                $curlproc = curl_init();
                 curl_setopt_array($curlproc, array(
                   CURLOPT_URL => 'http://externo.bunker.mk/nexux/disparo_fast_dlr_proc.php',
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 100,
                   CURLOPT_TIMEOUT => 300,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "POST",
                   CURLOPT_POSTFIELDS => "",
                   CURLOPT_HTTPHEADER => array(
                         "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
                         "cache-control: no-cache"
                   ),
                 ));

                 $responseproc = curl_exec($curlproc);
                 $err = curl_error($curlproc);

                 curl_close($curlproc);

                 if ($err) {
                   echo "cURL Error #:" . $err;
                 } else {
                   echo $responseproc;
                 }
		
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'http://externo.bunker.mk/nexux/disparo_fast_dlr.php',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 100,
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

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                  echo $response;
                }
        }	
}
 * 
 */
//++++++++++++++++++++++++++++++++renovar chave acesso KOMMO - Adorai
$momentoenvio = '13:15:00';
$horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($momentoenvio)))));
$horamaior1 = date('H:i:s', strtotime('+15 minute', strtotime(rtrim(trim($momentoenvio)))));

if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

  $subdomain = 'reservasroteirosadorai'; //Subdomain of the account
  $link = 'https://' . $subdomain . '.kommo.com/oauth2/access_token'; //Creating URL for request

  $sqlTkn = "SELECT REFRESH_TOKEN FROM TOKENS_KOMMO
      ORDER BY COD_TOKEN DESC LIMIT 1";
  $qrTkn = mysqli_fetch_assoc(mysqli_query(connTemp(274, ''), $sqlTkn));


  /** Gathering data for request */
  $data = [
    'client_id' => '70b4296c-fd45-4252-882a-0b1c32838c14',
    'client_secret' => 'puyJL8mjBY5VzcGzPBg7ztEvN2NRMunib0wLGbIMIQLDxZZE5ZR9kgUhKlxyeATW',
    'grant_type' => 'refresh_token',
    'refresh_token' => $qrTkn['REFRESH_TOKEN'],
    'redirect_uri' => 'https://roteirosadorai.com.br/api/callback.php',
  ];

  /**
   * We need to initiate the request to the server.
   * Let’s use the library with cURL.
   * You can also use cross-platform cURL if you don’t code on PHP.
   */
  $curl = curl_init(); //Saving descriptor cURL
  /** Installing required options for session cURL */
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Kommo-oAuth-client/1.0');
  curl_setopt($curl, CURLOPT_URL, $link);
  curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
  $out = curl_exec($curl); //Initiating request to API and saving response to variable
  $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  /** 
   * Now we can process responses from the server. 
   * It’s an example, you can process this data the way you want. 
   */
  $code = (int)$code;
  $errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
  ];
  try {
    /** If code of the response is not successful - return message of error */
    if ($code < 200 || $code > 204) {
      throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
  } catch (\Exception $e) {
    die('Error: ' . $e->getMessage() . PHP_EOL . 'Error code: ' . $e->getCode());
  }
  /** 
   * Data will be received in JSON, that’s why to get readable data, 
   * we need to parse data that PHP will understand 
   */
  $response = json_decode($out, true);

  $access_token = $response['access_token']; //Access token 
  $refresh_token = $response['refresh_token']; //Refresh token 
  $token_type = $response['token_type']; //Type of token 
  $expires_in = $response['expires_in']; //After how long does the token expire 

  $dat_expira = date("Y-m-d H:i:s", strtotime("+1 day"));

  $sqlIns = "INSERT INTO TOKENS_KOMMO(
              TOKEN_TYPE,
              ACCESS_TOKEN,
              REFRESH_TOKEN,
              DAT_EXPIRA
            )VALUES(
              '$response[token_type]',
              '$response[access_token]',
              '$response[refresh_token]',
              '$dat_expira'
            )";
  mysqli_query(connTemp(274, ''), $sqlIns);
}

//++++++++++++++++++++++++++++++++excluir as tmp do fabio
$momentoenvio = '20:30:00';
$horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($momentoenvio)))));
$horamaior1 = date('H:i:s', strtotime('+15 minute', strtotime(rtrim(trim($momentoenvio)))));

if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://externo.bunker.mk/Schedule/limpa_tmp_fabio.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
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

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
}
$momentoenvio = '23:00:00';
$horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($momentoenvio)))));
$horamaior1 = date('H:i:s', strtotime('+5 minute', strtotime(rtrim(trim($momentoenvio)))));

if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://externo.bunker.mk/Schedule/unidadereferencia',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
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

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
} else {
  echo '<br> não é hora de atualizar referencia<br>';
}
//dash do consultor




if ($horaatual1 == '01 05:00') {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://externo.bunker.mk/dash_consultor/dash.do',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
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

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
} else {
  echo '<br> não é hora de dash do consultor<br>';
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++      
//executar a rotina linx de venda/estorno
//relatorios email

$momentoenvio = '04:00:00';
$horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($momentoenvio)))));
$horamaior1 = date('H:i:s', strtotime('+10 minute', strtotime(rtrim(trim($momentoenvio)))));

if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://externo.bunker.mk/linx/linxMicrivix.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
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

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }
  ///atualização de atendentes
  sleep(2);
  $curl1 = curl_init();
  curl_setopt_array($curl1, array(
    CURLOPT_URL => 'http://externo.bunker.mk/linx/atvendedor.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 100,
    CURLOPT_TIMEOUT => 18000,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
      "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
      "cache-control: no-cache"
    ),
  ));

  $response1 = curl_exec($curl1);
  $err1 = curl_error($curl1);

  curl_close($curl1);

  if ($err1) {
    echo "cURL Error #:" . $err1;
  } else {
    echo $response1;
  }
} else {
  echo '<br> não é hora de atualizar linx<br>';
}

//processa log em lote Twilo      
$curlproc = curl_init();
curl_setopt_array($curlproc, array(
  CURLOPT_URL => 'http://externo.bunker.mk/twilo/ProcessamentoTwillo_new.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10000,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
    "cache-control: no-cache"
  ),
));

$responseproc = curl_exec($curlproc);
$err = curl_error($curlproc);

curl_close($curlproc);

//processa log em lote Twilo      
$curlproc = curl_init();
curl_setopt_array($curlproc, array(
  CURLOPT_URL => 'http://externo.bunker.mk/sinch/Processa_sinch.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10000,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
    "cache-control: no-cache"
  ),
));

$responseproc = curl_exec($curlproc);
$err = curl_error($curlproc);

curl_close($curlproc);

//=======

$momentoenvio = '09:00:00,10:00:00,11:00:00,15:00:00,16:00:00,17:00:00,23:00:00';
$dadostime = explode(',', $momentoenvio);
foreach ($dadostime as $timeN) {

  $horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
  $horamaior1 = date('H:i:s', strtotime('+5 minute', strtotime(rtrim(trim($timeN)))));
  if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

    $curlproc = curl_init();
    curl_setopt_array($curlproc, array(
      CURLOPT_URL => 'http://externo.bunker.mk/Schedule/Contabiliza_SMS.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 18000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $responseproc = curl_exec($curlproc);
    $err = curl_error($curlproc);

    curl_close($curlproc);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $responseproc;
    }
  }
}

$momentoenvio = '09:00:00,12:00:00,14:00:00,16:00:00,18:00:00,23:00:00';
$dadostime = explode(',', $momentoenvio);
foreach ($dadostime as $timeN) {

  $horamenor1 = date('H:i:s', strtotime('-3 minute', strtotime(rtrim(trim($timeN)))));
  $horamaior1 = date('H:i:s', strtotime('+60 minute', strtotime(rtrim(trim($timeN)))));
  if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

    $curlproc = curl_init();
    curl_setopt_array($curlproc, array(
      CURLOPT_URL => 'http://externo.bunker.mk/Schedule/Contabiliza_PUSH.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 100,
      CURLOPT_TIMEOUT => 18000,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
        "cache-control: no-cache"
      ),
    ));

    $responseproc = curl_exec($curlproc);
    $err = curl_error($curlproc);

    curl_close($curlproc);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $responseproc;
    }
  }
}
