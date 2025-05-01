<?php
function fnconsultaCPF($CPF,$EMPRESA,$LOGIN,$SENHA)
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
      CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:fid=\"fidelidade\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <fid:ConsultaCadastroPorCPF>\r\n         <!--You may enter the following 2 items in any order-->\r\n         <CPF>".$CPF."</CPF>\r\n         <dadosLogin>\r\n            <!--You may enter the following 7 items in any order-->\r\n            <!--Optional:-->\r\n            <fid:login>".$LOGIN."</fid:login>\r\n            <!--Optional:-->\r\n            <fid:senha>".$SENHA."</fid:senha>\r\n            <!--Optional:-->\r\n            <fid:idloja></fid:idloja>\r\n            <!--Optional:-->\r\n            <fid:idmaquina></fid:idmaquina>\r\n            <!--Optional:-->\r\n            <fid:idcliente>".$EMPRESA."</fid:idcliente>\r\n            <!--Optional:-->\r\n            <fid:codvendedor>?</fid:codvendedor>\r\n            <!--Optional:-->\r\n            <fid:nomevendedor>?</fid:nomevendedor>\r\n         </dadosLogin>\r\n      </fid:ConsultaCadastroPorCPF>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml",
        "postman-token: 2b0075e3-9bf1-91d8-ccf6-a519eeca3c33"
      ),
    ));
 
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
            
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
        $estadocivil = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->estadocivil;
        $email = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->email;
        $cartaotitular = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cartaotitular;
        $nomeportador = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->nomeportador;
        $grupo = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->grupo;
        $profissao = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->profissao;
        $clientedesde = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->clientedesde;
        $endereco = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->endereco;
        $numero = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->numero;
        $complemento = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->complemento;
        $bairro = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->bairro;
        $cidade = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cidade;
        $estado = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->estado;
        $cep = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cep;
        $telresidencial = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telresidencial;
        $telcelular = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telcelular;
        $telcomercial = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telcomercial;
        $saldo = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->saldo;
        $msgerro = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->msgerro;
        $retornodnamais = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->retornodnamais;
      
        
       return array (
                      'cartao' => $cartao,
                      'tipocliente' => $tipocliente,
                      'nome' => $nome,
                      'cpf' => $cpf,
                      'cnpj' => $cnpj,
                      'rg' => $rg,
                      'sexo' => $sexo,
                      'datanascimento' => $datanascimento,
                      'estadocivil' => $estadocivil,
                      'email' => $email,
                      'cartaotitular' => $cartaotitular,
                      'nomeportador' => $nomeportador,
                      'profissao' => $profissao,
                      'clientedesde' => $clientedesde,
                      'endereco' => $endereco,
                      'numero' => $numero,
                      'complemento' => $complemento,
                      'bairro' => $bairro,
                      'cidade' => $cidade,
                      'estado' => $estado,
                      'cep' => $cep,
                      'telresidencial' => $telresidencial,
                      'telcelular' => $telcelular,
                      'telcomercial' => $telcomercial,
                      'saldo' => $saldo,
                      'msgerro' => $msgerro,
                      'retornodnamais' => $retornodnamais,
                    );
       
         
         
    }
 
} 

function fnconsultaCNPJ($CPF,$EMPRESA,$LOGIN,$SENHA)
{

 //Array ( [0] => ws.maisfarma [1] => marka [2] => 28 [3] => ? [4] => [5] => ? [6] => ? [7] => 32560345862 [8] => )
   
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lin="Linker20">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                       <lin:ConsultaCadastroPorCNPJ>
                                          <CNPJ>'.$CPF.'</CNPJ>
                                          <dadosLogin>
                                             <lin:login>'.$LOGIN.'</lin:login>
                                             <lin:senha>'.$SENHA.'</lin:senha>
                                             <lin:idloja></lin:idloja>
                                             <lin:idmaquina>?</lin:idmaquina>
                                             <lin:idcliente>'.$EMPRESA.'</lin:idcliente>
                                             <lin:codvendedor></lin:codvendedor>
                                             <lin:nomevendedor></lin:nomevendedor>
                                             <lin:rawdata></lin:rawdata>
                                          </dadosLogin>
                                       </lin:ConsultaCadastroPorCNPJ>
                                    </soapenv:Body>
                                 </soapenv:Envelope>',
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
        $estadocivil = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->estadocivil;
        $email = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->email;
        $cartaotitular = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cartaotitular;
        $nomeportador = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->nomeportador;
        $grupo = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->grupo;
        $profissao = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->profissao;
        $clientedesde = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->clientedesde;
        $endereco = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->endereco;
        $numero = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->numero;
        $complemento = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->complemento;
        $bairro = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->bairro;
        $cidade = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cidade;
        $estado = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->estado;
        $cep = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->cep;
        $telresidencial = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telresidencial;
        $telcelular = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telcelular;
        $telcomercial = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->telcomercial;
        $saldo = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->saldo;
        $msgerro = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->msgerro;
        $retornodnamais = $xml->body->envelope->body->consultacadastroporcpfresponse->consultacadastroporcpfresult->retornodnamais;
      
        
       return array (
                      'cartao' => $cartao,
                      'tipocliente' => $tipocliente,
                      'nome' => $nome,
                      'cpf' => $cpf,
                      'cnpj' => $cnpj,
                      'rg' => $rg,
                      'sexo' => $sexo,
                      'datanascimento' => $datanascimento,
                      'estadocivil' => $estadocivil,
                      'email' => $email,
                      'cartaotitular' => $cartaotitular,
                      'nomeportador' => $nomeportador,
                      'profissao' => $profissao,
                      'clientedesde' => $clientedesde,
                      'endereco' => $endereco,
                      'numero' => $numero,
                      'complemento' => $complemento,
                      'bairro' => $bairro,
                      'cidade' => $cidade,
                      'estado' => $estado,
                      'cep' => $cep,
                      'telresidencial' => $telresidencial,
                      'telcelular' => $telcelular,
                      'telcomercial' => $telcomercial,
                      'saldo' => $saldo,
                      'msgerro' => $msgerro,
                      'retornodnamais' => $retornodnamais,
                    );
        }
}

function GetURLTktMania ($array)
{
    //=================================================
        $curl = curl_init();
 
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:lin='Linker20'>
                    <soapenv:Header/>
                    <soapenv:Body>
                       <lin:GetURLTktMania>
                        <CPFCARTAO>".$array['cpf']."</CPFCARTAO>
                          <dadosLogin>
                              <lin:login>".$array['login']."</lin:login>
                             <lin:senha>".$array['senha']."</lin:senha>
                             <lin:idloja>".$array['loja']."</lin:idloja>
                             <lin:idmaquina></lin:idmaquina>
                             <lin:idcliente>".$array['cod_empresa']."</lin:idcliente>
                             <lin:codvendedor></lin:codvendedor>
                             <lin:nomevendedor></lin:nomevendedor>
                             <lin:rawdata>?</lin:rawdata>
                          </dadosLogin>
                       </lin:GetURLTktMania>
                    </soapenv:Body>
                 </soapenv:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
        "Content-Type: text/xml",
      
      ),
    ));

   
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
       // return $response;
    }

    //====================================================
  
}  

function atualizacadastro ($arraydadosCli)
{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                              <SOAP-ENV:Body>
                              <AtualizaCadastro xmlns=\"Linker20\">
                                  <cliente xmlns=\"\">
                                           <cartao xmlns=\"Linker20\">".$arraydadosCli['NUM_CARTAO']."</cartao>
                                           <tipocliente>PF</tipocliente>    
                                           <nome xmlns=\"Linker20\">".$arraydadosCli['NOM_CLIENTE']."</nome>
                                           <cpf xmlns=\"Linker20\">".$arraydadosCli['NUM_CGCECPF']."</cpf>
                                           <sexo xmlns=\"Linker20\">".$arraydadosCli['COD_SEXOPES']."</sexo>
                                            <datanascimento xmlns=\"Linker20\">".$arraydadosCli['DAT_NASCIME']."</datanascimento>
                                            <email xmlns=\"Linker20\">".$arraydadosCli['DES_EMAILUS']."</email>
                                           <telcelular xmlns=\"Linker20\">".$arraydadosCli['NUM_CELULAR']."</telcelular>
                                           <senha>".$arraydadosCli['senha_cli']."</senha>    
                                    </cliente>
                                   <dadosLogin xmlns=\"\">
                                    <login xmlns=\"Linker20\">".$arraydadosCli['login']."</login>
                                     <senha xmlns=\"Linker20\">".$arraydadosCli['senha']."</senha>
                                     <idloja xmlns=\"Linker20\">".$arraydadosCli['COD_UNIVEND']."</idloja>
                                      <idmaquina xmlns=\"Linker20\">".$arraydadosCli['COD_EMPRESA']."</idmaquina>
                                      <idcliente xmlns=\"Linker20\">".$arraydadosCli['COD_EMPRESA']."</idcliente>
                                      <codvendedor xmlns=\"Linker20\"></codvendedor>
                                     <nomevendedor xmlns=\"Linker20\"></nomevendedor>
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
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
          $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
         // return $xml;
       // return array (
        //              'msgerro' => $msgerro);
      //  
       // };
       return $response;
        }
}        
function excluivendatotal ($arraydadosCli)
{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:lin='Linker20' xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'>
                                <soapenv:Header/>
                               <soapenv:Body>
                                    <lin:EstornaVenda>
                                  <id_vendapdv>".$arraydadosCli['id_vendapdv']."</id_vendapdv>
                                     <dadosLogin xmlns=''>
                                            <login xmlns='Linker20'>".$arraydadosCli['login']."</login>
                                            <senha xmlns='Linker20'>".$arraydadosCli['senha']."</senha>
                                            <idloja xmlns='Linker20'>".$arraydadosCli['COD_UNIVEND']."</idloja>
                                            <idmaquina xmlns='Linker20'>".$arraydadosCli['COD_EMPRESA']."</idmaquina>
                                            <idcliente xmlns='Linker20'>".$arraydadosCli['COD_EMPRESA']."</idcliente>
                                            <codvendedor xmlns='Linker20'></codvendedor>
                                            <nomevendedor xmlns='Linker20'></nomevendedor>
                                        </dadosLogin>
                                  </lin:EstornaVenda>
                               </soapenv:Body>
                            </soapenv:Envelope>",
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
       //   $doc = new DOMDocument();
       //   libxml_use_internal_errors(true);
        //  $doc->loadHTML($response);
       //   libxml_clear_errors();
        //  $xml = $doc->saveXML($doc->documentElement);
        
         // $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
        //  $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
         
      // return $response;
        }
}        
function excluivendaparcial ($arraydadosCli)
{
    
    $xml="<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:lin='Linker20'>
   <soapenv:Header/>
   <soapenv:Body>
      <EstornaVendaParcial>
         <Estorno>
            <id_vendapdv>".$arraydadosCli['id_vendapdv']."</id_vendapdv>
            <cartao>".$arraydadosCli['cartao']."</cartao>
              <items>
                 <EstornoItem>
                      <id_item>".$arraydadosCli['id_item']."</id_item>
                      <codigoproduto>".$arraydadosCli['codigoproduto']."</codigoproduto>
                      <quantidade>".$arraydadosCli['quantidade']."</quantidade>
                  </EstornoItem>
              </items>
         </Estorno>
         <dadosLogin>
              <login>".$arraydadosCli['login']."</login>
              <senha>".$arraydadosCli['senha']."</senha>
              <idloja>".$arraydadosCli['COD_UNIVEND']."</idloja>
              <idmaquina>".$arraydadosCli['COD_EMPRESA']."</idmaquina>
              <idcliente>".$arraydadosCli['COD_EMPRESA']."</idcliente>
              <codvendedor></codvendedor>
              <nomevendedor></nomevendedor>
          </dadosLogin>
      </EstornaVendaParcial>
   </soapenv:Body>
</soapenv:Envelope>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $xml,
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
           // echo $response;
           // return $xml;
        }
}   
function IndicacaoProduto ($dadoscliente) {
                            
   $validaws="SELECT LOG_USUARIO,DES_SENHAUS,COD_UNIVEND FROM usuarios WHERE cod_empresa=".$dadoscliente['COD_EMPRESA']." AND COD_TPUSUARIO='10'";
   $rsws=mysqli_fetch_assoc(mysqli_query($dadoscliente['CNB'], $validaws)); 
   $LOG_USUARIO=$rsws['LOG_USUARIO'];
   $DES_SENHAUS= fnDecode($rsws['DES_SENHAUS']);
    if($dadoscliente['COD_UNIVEND']=='')
    {    
        $arrayunidade=explode(',', $rsws['COD_UNIVEND']);
        $COD_UNIVEND=$arrayunidade['0'];
         
    }else{
        $COD_UNIVEND=$dadoscliente['COD_UNIVEND'];
    }
    
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                    <soapenv:Header />
                                    <soapenv:Body>
                                        <fid:IndicacaoProduto>
                                            <cpfCnpj>'.$dadoscliente['CPF'].'</cpfCnpj>
                                            <cartao>'.$dadoscliente['CPF'].'</cartao>
                                            <ProdutosOrigem>
                                                <codigoproduto>'.$dadoscliente['COD_PRODUTO'].'</codigoproduto>
                                            </ProdutosOrigem>
                                            <dadoslogin>
                                                <login>'.$LOG_USUARIO.'</login>
                                                <senha>'.$DES_SENHAUS.'</senha>
                                                <idloja>'.$COD_UNIVEND.'</idloja>
                                                <idmaquina></idmaquina>
                                                <idcliente>'.$dadoscliente['COD_EMPRESA'].'</idcliente>
                                                <codvendedor>??</codvendedor>
                                                <nomevendedor>??</nomevendedor>
                                            </dadoslogin>
                                        </fid:IndicacaoProduto>
                                    </soapenv:Body>
                                </soapenv:Envelope>',
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
           // echo $response;
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($response);
            libxml_clear_errors();
            $xml = $doc->saveXML($doc->documentElement);
            $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
           $array = json_decode(json_encode($xml), TRUE);
           return $array;
        }
    
}
//valida descontos

function validaDesc ($dadoscliente) {
    $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
         // CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <fid:ValidaDescontos>
                                      <cpfcnpj>'.$dadoscliente['cpf'].'</cpfcnpj>
                                      <cartao>'.$dadoscliente['cpf'].'</cartao>
                                      <valortotalliquido>'.$dadoscliente['vl_liquido'].'</valortotalliquido>
                                      <valor_resgate>'.$dadoscliente['vl_resgate'].'</valor_resgate>
                                      <dadosLogin>
                                                     <login>'.$dadoscliente['login'].'</login>
                                                     <senha>'.$dadoscliente['senha'].'</senha>
                                                     <idloja>'.$dadoscliente['idloja'].'</idloja>
                                                     <idmaquina>manual</idmaquina>
                                                     <idcliente>'.$dadoscliente['empresa'].'</idcliente>
                                        </dadosLogin>
                                   </fid:ValidaDescontos>
                                </soapenv:Body>
                             </soapenv:Envelope>',
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
           // echo $response;
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($response);
            libxml_clear_errors();
            $xml = $doc->saveXML($doc->documentElement);
            $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
            $array = json_decode(json_encode($xml), TRUE);
            return $array;
        }
    
}

?>

