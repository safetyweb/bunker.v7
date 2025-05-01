<?php
function geratkt($arraydadoscad,$dadoslogin)
{
    $cpfcnpj=$arraydadoscad['cpf'];
     if(strlen($cpfcnpj)=='11')
        {    
           $cpfcnpj= "<cpf>$cpfcnpj</cpf>";
        }else{
            $cpfcnpj="<cnpj>$cpfcnpj</cnpj>";
        }
      $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version='1.0' encoding='UTF-8'?>
                                <S:Envelope xmlns:S='http://schemas.xmlsoap.org/soap/envelope/'>
                                    <S:Body>
                                        <ns2:BuscaConsumidor xmlns:ns2='fidelidade'>
                                            <fase>fase2</fase>
                                            <opcoesbuscaconsumidor>
                                              $cpfcnpj
                                            </opcoesbuscaconsumidor>
                                           <dadosLogin>
                                            <login>".$dadoslogin['0']."</login>
                                            <senha>".$dadoslogin['1']."</senha>
                                            <idloja>".$dadoslogin['2']."</idloja>
                                            <idmaquina>".$dadoslogin['3']."</idmaquina>
                                            <idcliente>".$dadoslogin['4']."</idcliente>
                                            <codvendedor>".$dadoslogin['5']."</codvendedor>
                                            <nomevendedor>".$dadoslogin['6']."</nomevendedor> 
                                            </dadosLogin>
                                        </ns2:BuscaConsumidor>
                                    </S:Body>
                                </S:Envelope>",
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
          $url= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_b_ticket_de_ofertas->url_ticketdeofertas;
          $msg= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->msgerro;
          $coderro= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->coderro;
        
          $arrydadosreturn=array(
                                 'url'=> $url,
                                 'msg'=>$msg,
                                 'coderro'=>$coderro
                                 );
           
          return $arrydadosreturn;    
        } 
}    