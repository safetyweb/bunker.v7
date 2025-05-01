<?php
//ini_set('display_errors', 1);
 //      ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
       
set_time_limit(18000);
include './FunctionMain.php';
 
function fnconsultaCPF($CPF,$EMPRESA,$LOGIN,$SENHA,$idloja,$idmaquina)
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
      CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                            <soapenv:Header/>
                            <soapenv:Body>
                            <fid:ConsultaCadastroPorCPF>
                            <CPF>'.$CPF.'</CPF>
                            <dadosLogin>
                             <fid:login>'.$LOGIN.'</fid:login>
                            <fid:senha>'.$SENHA.'</fid:senha>
                            <fid:idloja>'.$idloja.'</fid:idloja>
                            <fid:idmaquina>'.$idmaquina.'</fid:idmaquina>
                              <fid:idcliente>'.$EMPRESA.'</fid:idcliente>
                             </dadosLogin>    
                             </fid:ConsultaCadastroPorCPF>   
                             </soapenv:Body></soapenv:Envelope>',
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
function atualizacadastro ($arraydadosCli)
{
        $curl = curl_init();
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
                                           <cartao xmlns=\"Linker20\">".@$arraydadosCli['NUM_CARTAO']."</cartao>
                                           <tipocliente>PF</tipocliente>    
                                           <nome xmlns=\"Linker20\">".@$arraydadosCli['NOM_CLIENTE']."</nome>
                                           <cpf xmlns=\"Linker20\">".@$arraydadosCli['NUM_CGCECPF']."</cpf>
                                           <sexo xmlns=\"Linker20\">".@$arraydadosCli['COD_SEXOPES']."</sexo>
                                            <datanascimento xmlns=\"Linker20\">".@$arraydadosCli['DAT_NASCIME']."</datanascimento>
                                            <email xmlns=\"Linker20\">".@$arraydadosCli['DES_EMAILUS']."</email>
                                           <telcelular xmlns=\"Linker20\">".@$arraydadosCli['NUM_CELULAR']."</telcelular>
                                               
                                    </cliente>
                                   <dadosLogin xmlns=\"\">
                                    <login xmlns=\"Linker20\">".@$arraydadosCli['login']."</login>
                                     <senha xmlns=\"Linker20\">".@$arraydadosCli['senha']."</senha>
                                     <idloja xmlns=\"Linker20\">".@$arraydadosCli['COD_UNIVEND']."</idloja>
                                      <idmaquina xmlns=\"Linker20\">".@$arraydadosCli['idmaquina']."</idmaquina>
                                      <idcliente xmlns=\"Linker20\">".@$arraydadosCli['COD_EMPRESA']."</idcliente>
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
        }else{
            return $response; 
        } 
}
/*
    $dadoscliente=fnconsultaCPF($cartao,$idcliente,$login,$senha,$idloja,$idmaquina);
    $cad=array('NUM_CARTAO'=>$dadoscliente['cpf'][0],
                'NOM_CLIENTE'=>$dadoscliente['nome'][0],
                'NUM_CGCECPF'=>$dadoscliente['cpf'][0],
                'COD_SEXOPES'=>$dadoscliente['sexo'][0],
                'DAT_NASCIME'=>$dt,
                'login'=>$login,
                'senha'=>$senha,
                'COD_UNIVEND'=>$idloja,
                'idmaquina'=>$idmaquina,
                'COD_EMPRESA'=>$idcliente,
                );
    $rst= atualizacadastro ($cad);
 
*/