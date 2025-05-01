<?php
function fnconsultacnpf($cpf,$dadoslogin)
{

 //Array ( [0] => ws.maisfarma [1] => marka [2] => 28 [3] => ? [4] => [5] => ? [6] => ? [7] => 32560345862 [8] => )
   
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:fid=\"fidelidade\">\r\n    
                                  <soapenv:Header/>\r\n   
                                    <soapenv:Body>\r\n        
                                        <fid:BuscaConsumidor>\r\n\t        
                                              <fase>fase1</fase>\r\n\t        
                                              <opcoesbuscaconsumidor>\r\n\t            
                                              <cnpj>$cpf</cnpj>\r\n\t        
                                        </opcoesbuscaconsumidor>\r\n\t\t       
                                                <dadosLogin xmlns=\"\">\r\n\t                
                                                    <login>".$dadoslogin['0']."</login>\r\n\t                
                                                    <senha>".$dadoslogin['1']."</senha>\r\n\t                
                                                    <idloja>".$dadoslogin['2']."</idloja>\r\n\t                
                                                    <idmaquina>".$dadoslogin['3']."</idmaquina>\r\n\t                
                                                    <idcliente>".$dadoslogin['4']."</idcliente>\r\n\t          
                                                </dadosLogin>\r\n      
                                         </fid:BuscaConsumidor>\r\n   
                                    </soapenv:Body>\r\n
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
         //  $response;
           
           $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
           
             
           // $array = json_decode(json_encode($xml,true), TRUE);
            //return $array;
         
            $NOME= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->nome;
            $CPF=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->cpf;
            $datanascimento=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->datanascimento;
            $sexo=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->sexo;
            $cartao=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->cartao;
            $tipocliente=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->tipocliente;
            $email=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->email;
            $endereco=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->endereco;
            $numero=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->numero;
            $bairro=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->bairro;
            $complemento=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->complemento;
            $cidade=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->cidade;
            $estado=$xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->estado;
            $cep= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->cep;
            $celular= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->telcelular;
            $msg= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->msgerro; 
            $saldo= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_h_saldo->saldodisponivel;
            $localizacaocliente= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->coderro;
            $codatendente= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->codatendente;
            $senhacliente= $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_a_cadastro->senha;
           

            
            
            
            return array('nome' => "$NOME",
                             'cpf' => "$CPF",
                             'sexo'=>"$sexo",
                             'cartao'=>"$cartao",
                             'tipocliente'=>"$tipocliente",
                             'email'=>"$email",
                             'endereco'=>"$endereco",
                             'numero'=>"$numero",
                             'bairro'=>"$bairro",
                             'complemento'=>"$complemento",
                             'cidade'=>"$cidade",
                             'estado'=>"$estado",
                             'cep'=>"$cep",
                             'datanascimento'=>"$datanascimento", 
                             'telresidencial'=>"$celular",
                             'saldo'=>$saldo,
                             'saldoresgate'=>$saldoresgate,
                             'codatendente'=>"$codatendente",
                             'senha'=>$senhacliente,           
                             'msg' => "$msg",
                             'localizacaocliente'=>$localizacaocliente
                              );
        }
}
 
?>