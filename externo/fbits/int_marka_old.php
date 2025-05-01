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
                                    'creditovenda' =>$result->InserirVendaResult->creditovenda
                                  );
                return $arraysaldo;
               
           //  }catch (\Exception $e){
                 
            //    echo "========= REQUEST ==========" . PHP_EOL;
             //   echo htmlentities($client->__getLastRequest())."<br>";
               
             
                
                 
            // }
   
        
      
} 