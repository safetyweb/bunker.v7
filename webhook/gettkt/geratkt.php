<?php
function geratkt($cpf,$dadoslogin)
{
 
     $curl = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lin="Linker20">
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <lin:GetURLTktMania>
                                      <CPFCARTAO>'.$cpf.'</CPFCARTAO>
                                   <dadoslogin>
                                             <login>'.$dadoslogin[0].'</login>
                                             <senha>'.$dadoslogin[1].'</senha>
                                             <idloja>'.$dadoslogin[2].'</idloja>
                                             <idmaquina>'.$dadoslogin[3].'</idmaquina>
                                             <idcliente>'.$dadoslogin[4].'</idcliente>
                                             <codvendedor>tktweb</codvendedor>
                                             <nomevendedor>tktweb</nomevendedor>
                                       </dadoslogin>
                                   </lin:GetURLTktMania>
                                </soapenv:Body>
                             </soapenv:Envelope>',
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml",
        "postman-token: 2b0075e3-9bf1-91d8-ccf6-a519eeca3c33"
      ),
    ));
 
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
            
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
         
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
          $xmlarray=json_decode(json_encode($xml), True);
          $msgerro=$xmlarray['body']['envelope']['body']['geturltktmaniaresponse']['geturltktmaniaresult']['msgerro'];
          $urltktmania=$xmlarray['body']['envelope']['body']['geturltktmaniaresponse']['geturltktmaniaresult']['urltktmania'];
          
          return array(
                       'msgerro'=>$msgerro,
                       'urltktmania'=>$urltktmania
                        );
    }
    
}

