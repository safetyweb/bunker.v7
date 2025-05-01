<?php
function estornovenda($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml; charset=utf-8",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
         // $cod_retorno_busca=$array['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['coderro']; 
          return  $array;
          }
}
function VALIDATOKEN($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
         // $cod_retorno_busca=$array['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['coderro']; 
          return  $array;
          }
}
function TOKEN($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
         // $cod_retorno_busca=$array['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['coderro']; 
          return  $array;
          }
}
function fncadastro($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
         // $cod_retorno_busca=$array['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_a_cadastro']['coderro']; 
          return  $array;
          }
}
function fnvalidaconsumidor($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
          return  $array;
          }
}
function fnvenda($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
          return  $array;
          }
}
function fnCadvendedor($xmlbusca)
{
    $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 100,
          CURLOPT_TIMEOUT => 500,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xmlbusca,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
                            
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $array= json_decode(json_encode($xml), true);
          return  $array;
          }
}
?>