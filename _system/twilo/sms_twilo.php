<?php
    function sms_twilo($base64,$dadosenvio,$senha)
    {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/$senha/Messages.json");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "utf-8");
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");

            foreach ($dadosenvio as  $value){

                curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query([
                                                                            'Body'=> $value[mensagem],
                                                                            'From' => $value[from],
                                                                            'statusCallback'=>"http://externo.bunker.mk/twilo/twl/$value[Codigointerno]",
                                                                            'To' => $value[to]
                                                                        ]));
                curl_setopt($curl, CURLOPT_HTTPHEADER,  array(
                                                             'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                                                             'Authorization: Basic '.$base64
                                                           ));
                 $response = curl_exec($curl);
                 $err = curl_error($curl);



                 if ($err) {
                   echo "cURL Error #:" . $err;
                 } else {
                     $dadostwiloret =json_decode($response,true);
                     $dadostwiloret['COD_CLIENTE'] =$value['COD_CLIENTE'];
                    $ret[] = $dadostwiloret;
                 }

            }
          curl_close($curl);
          return $ret;
    }
    
?>