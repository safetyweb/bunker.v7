<?php

function GetURLTktMania ($arraydados)
{
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:lin=\"Linker20\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\r\n<soapenv:Header/>\r\n   <soapenv:Body>\r\n<lin:GetURLTktMania xmlns=\"Linker20\">\r\n<!--You may enter the following 2 items in any order-->\r\n         <CPFCARTAO xmlns=\"\">01734200014</CPFCARTAO>\r\n         <dadosLogin>\r\n                <login>diogo.farmacia</login>\r\n                <senha>tankd12</senha>\r\n                <idloja>725</idloja>\r\n                <idmaquina>PCASDSAD</idmaquina>\r\n                <idcliente>7</idcliente>\r\n                <codvendedor></codvendedor>\r\n                <nomevendedor></nomevendedor>\r\n                <rawdata></rawdata>\r\n            </dadosLogin>\r\n      </lin:GetURLTktMania>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
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
        }
}        