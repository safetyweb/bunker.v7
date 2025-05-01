<?php

function fnValidadesconto($arraydados,$dadoslogin)
{
           $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:fid='fidelidade'>
                                <soapenv:Header />
                                <soapenv:Body>
                                    <fid:ValidaDescontos>
                                        <cpfcnpj>".$arraydados['cpfcnpj']."</cpfcnpj>
                                        <cartao></cartao>
                                        <valortotalliquido>".$arraydados['valortotalliquido']."</valortotalliquido>
                                        <valor_resgate>".$arraydados['valor_resgate']."</valor_resgate>
                                        <dadosLogin>
                                            <login>".$dadoslogin['0']."</login>
                                            <senha>".$dadoslogin['1']."</senha>
                                            <idloja>".$dadoslogin['2']."</idloja>
                                            <idmaquina>".$dadoslogin['3']."</idmaquina>
                                            <idcliente>".$dadoslogin['4']."</idcliente>
                                            <codvendedor>".$dadoslogin['5']."</codvendedor>
                                            <nomevendedor>".$dadoslogin['6']."</nomevendedor>
                                        </dadosLogin>
                                    </fid:ValidaDescontos>
                                </soapenv:Body>
                            </soapenv:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: e16a9d3f-b132-df60-92e0-5f9c923a0baf"
          ),
        ));
          $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
         return ;
        } else {
         
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $msg= $xml->body->envelope->body->validadescontosresponse->validadescontos;
          $msg=json_decode(json_encode($msg), true);
          return $msg;    
        }
}
