<?php

function ifaro($CPF)
{
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;
    if($CPF !='' || strlen($CPF)>='6')
    { 
            $curl = curl_init();

          curl_setopt_array($curl, array(
            //CURLOPT_URL => "http://177.70.122.226/WSDados.svc?wsdl=",
            CURLOPT_URL => "http://ws.ifaro.com.br/WSDados.svc?wsdl=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                                   xmlns:tem=\"http://tempuri.org/\" >\r\n   
                                   <soapenv:Header/>\r\n   <soapenv:Body>\r\n     
                                   <tem:ConsultaPessoaSimplificado>\r\n         
                                   <tem:cpf>".$CPF."</tem:cpf>\r\n         
                                   <tem:login>TUFSS0E=</tem:login>\r\n         
                                   <tem:senha>c21hZWJSQXExNw==</tem:senha>\r\n     
                                   </tem:ConsultaPessoaSimplificado>\r\n   
                                   </soapenv:Body>\r\n
                                   </soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: text/xml",
              "postman-token: fca2049c-8e80-9cd1-a290-bed88bcf2c4e",
              "soapaction: http://tempuri.org/IWSDados/ConsultaPessoaSimplificado"
            ),
          ));


          $response = curl_exec($curl);
          $err = curl_error($curl);

          curl_close($curl);
         list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $total1 = round($script_end - $script_start, 5);


          if ($err) {
           // $msg="cURL Error #:" . $err;
             $msg= "Consulta automatica indisponivel!";
             $arraycpf=array();
             array_push($arraycpf, array("nome" => $NOME,"cpf" => $CPF,'sexo'=>$sexor,'datanascimento'=>$datanascimento,'timeCo'=>$total1,'msg' => $msg,"cod_erro"=>'0057643'));
             return $arraycpf; 

          } else {
           //  $response;
                  $doc = new DOMDocument();
                  libxml_use_internal_errors(true);
                  $doc->loadHTML($response);
                  libxml_clear_errors();
                  $xml = $doc->saveXML($doc->documentElement);
                  $xml = simplexml_load_string($xml);
                  $NOME = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->nome;
                  $CPF = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->cpf;
                  $sexor = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->sexo;
                  $datanascimento = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->datanascimento;


                $msg="OK";  
                $arraycpf=array();
                array_push($arraycpf, array("nome" => $NOME,"cpf" => $CPF,'sexo'=>$sexor,'datanascimento'=>$datanascimento,'timeCo'=>$total1,'msg' => $msg,"cod_erro"=>'0057644'));
                return $arraycpf;  

          } 
    }else{
                            $msg= "CPF COM VALOR VAZIO!";                        
                            $arraycpf=array();
                            array_push($arraycpf, array("nome" => $NOME,"cpf" => $CPF,'sexo'=>$sexor,'datanascimento'=>$datanascimento,'timeCo'=>$total1,'msg' => $msg,"cod_erro"=>'0057643'));
                            return $arraycpf;   
   }       
}
?>
