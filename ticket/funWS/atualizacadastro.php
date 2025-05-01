<?php
function atualizacadastro($arraydadoscad,$dadoslogin)
{
   if($arraydadoscad['sexo']=='M')
     {$sexo=1;}else{$sexo=2;}
     
   if($arraydadoscad['sexo']=='I')
     {$sexo=3;}
   
    
    
    $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">\r\n"
                                    ."<soap:Body>\r\n"
                                        . "<AtualizaCadastro xmlns=\"fidelidade\">\r\n"
                                            . "<fase xmlns=\"\">fase1</fase>\r\n"
                                                . "<cliente xmlns=\"\">\r\n"
                                                    . "<nome>".$arraydadoscad['nome']."</nome>\r\n"
                                                    . "<cartao>".$arraydadoscad['cpf']."</cartao>\r\n"
                                                    . "<cpf>".$arraydadoscad['cpf']."</cpf>\r\n"
                                                    . "<sexo>".$sexo."</sexo>\r\n"
                                                    . "<rg />\r\n"
                                                    . "<cnpj />\r\n"
                                                    . "<nomeportador></nomeportador>\r\n"
                                                    . "<grupo />\r\n"
                                                    . "<datanascimento>".$arraydadoscad['dt_nascimento']."</datanascimento>\r\n"
                                                    . "<estadocivil />\r\n"
                                                    . "<telresidencial />\r\n"
                                                    . "<telcomercial />\r\n"
                                                    . "<telcelular>".$arraydadoscad['telefone']."</telcelular>\r\n"
                                                    . "<email>".$arraydadoscad['email']."</email>\r\n"
                                                    . "<profissao />\r\n"
                                                    . "<clientedesde />\r\n"
                                                    . "<tipocliente>F</tipocliente>\r\n"
                                                    . "<endereco>NOSSA SENHORA DO RESGATE, 044</endereco>\r\n"
                                                    . "<numero />\r\n"
                                                    . "<bairro />\r\n"
                                                    . "<complemento />\r\n"
                                                    . "<cidade />\r\n"
                                                    . "<estado />\r\n"
                                                    . "<cep />\r\n"
                                                    . "<cartaotitular />\r\n"
                                                    . "<bloqueado />\r\n"
                                                    . "<motivo />\r\n"
                                                    . "<dataalteracao />\r\n"
                                                    . "<adesao />\r\n"
                                                    . "<codatendente />\r\n"
                                                    . "<senha />\r\n"
                                                    . "<fontedados>loja</fontedados>\r\n"
                                                    . "<coderro />\r\n"
                                                    . "</cliente>\r\n"
                                                        . "<dadosLogin xmlns=\"\">\r\n"
                                                            . "<login>".$dadoslogin['0']."</login>\r\n"
                                                            . "<senha>".$dadoslogin['1']."</senha>\r\n "
                                                            . "<idloja>".$dadoslogin['2']."</idloja>\r\n "
                                                            . "<idmaquina>".$dadoslogin['3']."</idmaquina>\r\n"
                                                            . "<idcliente>".$dadoslogin['4']."</idcliente>\r\n"
                                                            . "<codvendedor>".$dadoslogin['5']."</codvendedor>\r\n" 
                                                            . "<nomevendedor>".$dadoslogin['6']."</nomevendedor>\r\n" 
                                                            . "</dadosLogin>\r\n"
                                        . "</AtualizaCadastro>\r\n"
                                ."</soap:Body>\r\n"
                            . "</soap:Envelope>",
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
          $msg= $xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresponse->msgerro;
          return $msg;    
           
        }
}         