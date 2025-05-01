<?php
function fnofertas($cpf,$dadoslogin)
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
          CURLOPT_POSTFIELDS => "
                                <soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:lin='Linker20'>
                                   <soapenv:Header/>
                                   <soapenv:Body>
                                      <lin:GetProdutosTicket>
                                         <CPFCARTAO>$cpf</CPFCARTAO>
                                        <dadosLogin>                
                                            <login>".$dadoslogin['0']."</login>                
                                            <senha>".$dadoslogin['1']."</senha>                
                                            <idloja>".$dadoslogin['2']."</idloja>               
                                            <idmaquina>".$dadoslogin['3']."</idmaquina>              
                                            <idcliente>".$dadoslogin['4']."</idcliente>       
                                        </dadosLogin>
                                      </lin:GetProdutosTicket>
                                   </soapenv:Body>
                                </soapenv:Envelope>",
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
            $arraycpf= array('msg' => $msg);
        } else {
                $doc = new DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($response);
                libxml_clear_errors();
                $xml = $doc->saveXML($doc->documentElement);
                $xml = simplexml_load_string($xml);
                $ofertas['oferta0']=$xml->body->envelope->body->getprodutosticketresponse->listaticket->ofertasticket;
                $ofertas['oferta1']=$xml->body->envelope->body->getprodutosticketresponse->listaticket->ofertashabito;
                $ofertas['oferta2']=$xml->body->envelope->body->getprodutosticketresponse->listaticket->ofertaspromocao;
                $json_string = json_encode($ofertas);    
                $result_array = json_decode($json_string, TRUE);
                return $result_array;
        }
}