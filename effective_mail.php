<?php

$curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://www.cliente.email/effectivemail/v2/api.asmx?wsdl",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:dtm="https://www.dtmmkt.com.br/DTM_Campanhas/">
                                    <soapenv:Header>
                                       <dtm:Autenticacao>
                                          <dtm:Usuario>effmail</dtm:Usuario>
                                          <dtm:Senha>LbtHPymim6U=</dtm:Senha>
                                          </dtm:Autenticacao>
                                    </soapenv:Header>
                                    <soapenv:Body>
                                       <dtm:BuscarCampanha>
                                          <dtm:id>1</dtm:id>
                                       </dtm:BuscarCampanha>
                                    </soapenv:Body>
                                 </soapenv:Envelope>',
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
         
          $json_convert = json_encode( $xml );
          $json = json_decode( $json_convert,true );
          
          echo '<pre>';
          print_r( $json );
          echo '</pre>';
        }