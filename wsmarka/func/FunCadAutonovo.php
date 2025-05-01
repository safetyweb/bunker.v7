<?php
function FnCadAuto($arraydadosCli)
{
    $curl = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:fid=\"fidelidade\">\r\n   <soapenv:Header/>\r\n  "
                            . "<soapenv:Body>\r\n      "
                            . "<fid:ConsultaCadastroPorCPF>\r\n  
                              <CPF>".$arraydadosCli['cartao']."</CPF>\r\n         "
                                . "<dadosLogin>\r\n           "
                                . "   <fid:login>".$arraydadosCli['login']."</fid:login>\r\n "
                                . "   <fid:senha>".$arraydadosCli['senha']."</fid:senha>\r\n          "
                                . "   <fid:idloja>".$arraydadosCli['idloja']."</fid:idloja>\r\n           "
                                . "   <fid:idmaquina>".$arraydadosCli['idmaquina']."</fid:idmaquina>\r\n     "
                                . "   <fid:idcliente>".$arraydadosCli['idcliente']."</fid:idcliente>\r\n     "
                                . "   <fid:codvendedor>".$arraydadosCli['codvendedor']."</fid:codvendedor>\r\n  "
                                . "   <fid:nomevendedor>".$arraydadosCli['nomevendedor']."</fid:nomevendedor>\r\n     "
                                . "</dadosLogin>\r\n      "
                                . "</fid:ConsultaCadastroPorCPF>\r\n  "
                                . " </soapenv:Body>\r\n</soapenv:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml"
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
            
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
        $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
        $cartao = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cartao;
        $tipocliente = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->tipocliente;
        $nome = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->nome;
        $cpf = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cpf;
        $cnpj = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cnpj;
        $rg = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->rg;
        $sexo = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->sexo;
        $datanascimento = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->datanascimento;
        $datanascimento= fnDataSql($datanascimento);
  //atualiza 
         $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                              <SOAP-ENV:Body>
                              <AtualizaCadastro xmlns=\"Linker20\">
                                    <cliente xmlns=\"\">
                                           <cartao xmlns=\"Linker20\">".$cpf."</cartao>
                                           <tipocliente>PF</tipocliente>    
                                           <nome xmlns=\"Linker20\">".$nome."</nome>
                                           <cpf xmlns=\"Linker20\">".$cpf."</cpf>
                                           <sexo xmlns=\"Linker20\">".$sexo."</sexo>
                                           <datanascimento xmlns=\"Linker20\">".$datanascimento."</datanascimento>
                                           <lin:codatendente>".$arraydadosCli['codatendente']."</lin:codatendente>  
                                    </cliente>
                                    <dadosLogin xmlns=\"\">
                                        <login xmlns=\"Linker20\">".$arraydadosCli['login']."</login>
                                        <senha xmlns=\"Linker20\">".$arraydadosCli['senha']."</senha>
                                        <idloja xmlns=\"Linker20\">".$arraydadosCli['idloja']."</idloja>
                                        <idmaquina xmlns=\"Linker20\">".$arraydadosCli['idmaquina']."</idmaquina>
                                        <idcliente xmlns=\"Linker20\">".$arraydadosCli['idcliente']."</idcliente>
                                        <codvendedor xmlns=\"Linker20\">".$arraydadosCli['codvendedor']."</codvendedor>
                                        <nomevendedor xmlns=\"Linker20\">".$arraydadosCli['nomevendedor']."</nomevendedor>
                                    </dadosLogin>
                                  </AtualizaCadastro>
                                 </SOAP-ENV:Body>
                               </SOAP-ENV:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
          ),
        ));
  
        $response = curl_exec($curl);
        curl_close($curl);

       
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
          $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
          if($msgerro=='OK')
          {
            sleep(0.3);  
            $busca="select count(COD_CLIENTE) as contador,cod_cliente from clientes where 
                                                                                        cod_empresa='".$arraydadosCli['idcliente']."'  and 
                                                                                        num_cartao='$cpf' limit 1"; 
            $row=mysqli_fetch_assoc(mysqli_query($arraydadosCli['connuser'], $busca));
            
           
          } 
       $arraydadosBase= array(    'contador'=> $row['contador'],
                                    'COD_CLIENTE'=>$row['cod_cliente'],
                                    'msg'=>$msgerro
                                   );
       
            return $arraydadosBase;   

}
/*
array('cartao'=>'',
      'login'=>'',
      'senha'=>'',
      'idloja'=>'',
      'idmaquina'=>'',
      'idcliente'=>'',
      'codvendedor'=>'',
      'nomevendedor'=>'',
      'connuser'=>''
     );
*/