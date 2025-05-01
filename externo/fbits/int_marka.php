<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

function atualiazacadastroMK($dados,$dadoslogin)
{
$client = new SoapClient('http://ws.bunker.mk?wsdl',
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
        $function = 'AtualizaCadastro';
        $arguments= array   ('AtualizaCadastro'=>array(  
                                                        'cliente'=>$dados,
                                                        'dadosLogin'=>$dadoslogin));
        
   
        $options = array('location' => 'http://ws.bunker.mk');
        $result = $client->__soapCall($function, $arguments, $options);
        return $result->AtualizaCadastroResult->msgerro;
} 
function inserevendaMK($dados,$dadoslogin)
{
    
$client = new SoapClient('http://ws.bunker.mk?wsdl',
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 60,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
        $function = 'InserirVenda';
        $arguments= array   ('InserirVenda'=>array(  
                                                        'venda'=>$dados,
                                                        'dadosLogin'=>$dadoslogin));
       // try{
               $options = array('location' => 'http://ws.bunker.mk');
               @$result = $client->__soapCall($function, $arguments, $options);
               $arraysaldo =array(
                                    'msgerro'=>$result->InserirVendaResult->msgerro,
                                    'creditovenda' =>trim(rtrim($result->InserirVendaResult->creditovenda))
                                  );
                return $arraysaldo;
               
           //  }catch (\Exception $e){
                 
            //    echo "========= REQUEST ==========" . PHP_EOL;
             //   echo htmlentities($client->__getLastRequest())."<br>";
               
             
                
                 
            // }
   
        
      
} 
function Estorno_venda($PDV,$dadoslogin)
{
    
$client = new SoapClient('http://ws.bunker.mk?wsdl',
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 60,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
        $function = 'EstornaVenda';
        $arguments= array   ('EstornaVenda'=>array(  
                                                      'id_vendapdv'=>$PDV['id_vendapdv'],
                                                        'dadosLogin'=>$dadoslogin));
             $options = array('location' => 'http://ws.bunker.mk');
             $result = $client->__soapCall($function, $arguments, $options);
             $arraysaldo =array(
                                    'saldo'=>$result->EstornaVendaResult->saldo,
                                    'msgerro' =>$result->EstornaVendaResult->msgerro,
                                    'saldoresgate'=>$result->EstornaVendaResult->saldoresgate
                                  );
                return $arraysaldo; 
      
}
function inserecredito($array){
          $email=urlencode(utf8_encode($array['email']));
            
            $curl = curl_init();
           
            curl_setopt_array($curl, array(
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_URL => "https://api.fbits.net/contascorrentes/$email",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
             CURLOPT_POSTFIELDS => "{\r\n  \"valor\":".$array['val_credito'].",\r\n  \"tipoLancamento\": \"Credito\",\r\n  \"observacao\": \"Credito marka\",\r\n  \"visivelParaCliente\": true\r\n}",  
  
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".$array['senha'],
                "Content-Type: application/json",
                "Postman-Token: 8fde7a9f-23fe-49fd-b9b8-ac2f08f9fe47",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
}
function Estornocredito($array){
          $email=urlencode(utf8_encode($array['email']));
            
            $curl = curl_init();
           
            curl_setopt_array($curl, array(
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_URL => "https://api.fbits.net/contascorrentes/$email",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
             CURLOPT_POSTFIELDS => "{\r\n  \"valor\":".$array['val_credito'].",\r\n  \"tipoLancamento\": \"Debito\",\r\n  \"observacao\": \"Venda_Estornada\",\r\n  \"visivelParaCliente\": true\r\n}",  
  
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".$array['senha'],
                "Content-Type: application/json",
                "Postman-Token: 8fde7a9f-23fe-49fd-b9b8-ac2f08f9fe47",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
}
function saldoatual($array){
          $email=urlencode(utf8_encode($array['email']));
            
            $curl = curl_init();
           
            curl_setopt_array($curl, array(
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_URL => "https://api.fbits.net/contascorrentes/$email",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
             CURLOPT_POSTFIELDS => "",  
  
              CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".$array['senha'],
                "Content-Type: application/json",
                "Postman-Token: 8fde7a9f-23fe-49fd-b9b8-ac2f08f9fe47",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;              
              $response=json_decode($response);
              return $response;
            }
}