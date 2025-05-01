<?php
function atualizacadastro($arraydadoscad,$dadoslogin)
{
   if($arraydadoscad['sexo']=='M' ||$arraydadoscad['sexo']=='1')
     {$sexo=1;}else{$sexo=2;}
     
   if($arraydadoscad['sexo']=='I')
     {$sexo=3;}
   if($arraydadoscad['venda']=='S')
   {
    $tp_cliente=$arraydadoscad['tp_cliente'];   
   }else{
    if(strlen($arraydadoscad['cpf']) <= '11')
    {    
       $tp_cliente='F';
    }else{ 
        $tp_cliente='J';
    }   
   }   
    
    if(strlen($arraydadoscad['cpf'])=='11')
    {    
       $cpfcnpj=$arraydadoscad['cpf'];
       $cartao=$arraydadoscad['cartao'];
    }else{
        $cpfcnpj=$arraydadoscad['cpf']; 
        $cartao=$arraydadoscad['cpf']; 
    }

    if(isset($arraydadoscad['endereco']) && $arraydadoscad['endereco'] != ""){
      $endereco = "<endereco>".$arraydadoscad['endereco']."</endereco>\r\n";
    }else{
      $endereco = "<endereco />\r\n";
    }
    if(isset($arraydadoscad['numero']) && $arraydadoscad['numero'] != ""){
      $numero = "<numero>".$arraydadoscad['numero']."</numero>\r\n";
    }else{
      $numero = "<numero />\r\n";
    }
    if(isset($arraydadoscad['cep']) && $arraydadoscad['cep'] != ""){
      $cep = "<cep>".$arraydadoscad['cep']."</cep>\r\n";
    }else{
      $cep = "<cep />\r\n";
    }
    if(isset($arraydadoscad['estado']) && $arraydadoscad['estado'] != ""){
      $estado = "<estado>".$arraydadoscad['estado']."</estado>\r\n";
    }else{
      $estado = "<estado />\r\n";
    }
    if(isset($arraydadoscad['cidade']) && $arraydadoscad['cidade'] != ""){
      $cidade = "<cidade>".$arraydadoscad['cidade']."</cidade>\r\n";
    }else{
      $cidade = "<cidade />\r\n";
    }
    if(isset($arraydadoscad['bairro']) && $arraydadoscad['bairro'] != ""){
      $bairro = "<bairro>".$arraydadoscad['bairro']."</bairro>\r\n";
    }else{
      $bairro = "<bairro />\r\n";
    }
    if(isset($arraydadoscad['complemento']) && $arraydadoscad['complemento'] != ""){
      $complemento = "<complemento>".$arraydadoscad['complemento']."</complemento>\r\n";
    }else{
      $complemento = "<complemento />\r\n";
    }
    if(isset($arraydadoscad['tokencadastro']) && $arraydadoscad['tokencadastro'] != ""){
      $tokencadastro = "<tokencadastro>".$arraydadoscad['tokencadastro']."</tokencadastro>\r\n";
    }else{
      $tokencadastro = "<tokencadastro />\r\n";
    }
    if(isset($arraydadoscad['canal']) && $arraydadoscad['canal'] != ""){
      $canal = "<canal>".$arraydadoscad['canal']."</canal>\r\n";
    }else{
      $canal = "<canal />\r\n";
    }
    if(isset($arraydadoscad['adesao']) && $arraydadoscad['adesao'] != ""){
      $adesao = "<adesao>".$arraydadoscad['adesao']."</adesao>\r\n";
    }else{
      $adesao = "<adesao />\r\n";
    }

    if(isset($arraydadoscad['codIndicador']) && $arraydadoscad['codIndicador'] != ""){
      $codIndicador = "<codIndicador>".$arraydadoscad['codIndicador']."</codIndicador>\r\n";
    }else{
      $codIndicador = "<codIndicador />\r\n";
    }
    

    $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<soap:Envelope xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">\r\n"
                                    ."<soap:Body>\r\n"
                                        . "<AtualizaCadastro xmlns=\"fidelidade\">\r\n"
                                            . "<fase xmlns=\"\">fase1</fase>\r\n"
                                                . "<cliente xmlns=\"\">\r\n"
                                                    . "<nome>".$arraydadoscad['nome']."</nome>\r\n"
                                                    . "<cartao>".$cartao."</cartao>\r\n"
                                                    . "<cpf>".$cpfcnpj."</cpf>\r\n"
                                                    . "<sexo>".$sexo."</sexo>\r\n"
                                                    . "<rg />\r\n"
                                                    . "<cnpj>$cpfcnpj</cnpj>\r\n"
                                                    . "<nomeportador></nomeportador>\r\n"
                                                    . "<grupo />\r\n"
                                                    . "<datanascimento>".$arraydadoscad['dt_nascimento']."</datanascimento>\r\n"
                                                    . "<estadocivil />\r\n"
                                                    . "<telresidencial />\r\n"
                                                    . "<telcomercial />\r\n"
                                                    . "<telcelular>".$arraydadoscad['telefone']."</telcelular>\r\n"
                                                    . "<email>".$arraydadoscad['email']."</email>\r\n"
                                                    . "<profissao>".$arraydadoscad['profissao']."</profissao>"
                                                    . "<clientedesde />\r\n"
                                                    . "<tipocliente>".$tp_cliente."</tipocliente>\r\n"
                                                    . $endereco
                                                    . $numero
                                                    . $cep
                                                    . $estado
                                                    . $cidade
                                                    . $bairro
                                                    . $complemento
                                                    . $tokencadastro
                                                    . $canal
                                                    . $adesao
                                                    . $codIndicador
                                                    . "<cartaotitular />\r\n"
                                                    . "<bloqueado />\r\n"
                                                    . "<motivo />\r\n"
                                                    . "<dataalteracao />\r\n"
                                                    . "<codatendente>".$arraydadoscad['codatendente']."</codatendente>"
                                                    . "<senha>".$arraydadoscad['senha']."</senha>\r\n"
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
			"content-type: text/xml; charset=utf-8",
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