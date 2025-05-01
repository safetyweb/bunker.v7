<?php
function consulta_cep($cep)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl=",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:cli=\"http://cliente.bean.master.sigep.bsb.correios.com.br/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <cli:consultaCEP>\r\n         <!--Optional:-->\r\n         <cep>".$cep."</cep>\r\n      </cli:consultaCEP>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: 1c811616-5d03-93fa-371a-14c22575ae9a"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
      return $err;
    } else {
     
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          
          $rua= $xml->body->envelope->body->consultacepresponse->return->end;
          $bairro= $xml->body->envelope->body->consultacepresponse->return->bairro;
          $cidade= $xml->body->envelope->body->consultacepresponse->return->cidade;
          $uf= $xml->body->envelope->body->consultacepresponse->return->uf;
          $cep1= $xml->body->envelope->body->consultacepresponse->return->cep;
         
        $cep=array("rua" => $rua,
                   "bairro" => $bairro,
                    'cidade'=>$cidade,
                    'uf'=>$uf,
                    'cep'=>$cep1
                    );
                  
        return $cep; 
    }
   
}
?>
