<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//inicio do cadastro
function SyncUsuario($dados)
{ 
      
       
        $client = new SoapClient($dados['URLWSDL'],
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
            if ($dados['DEBUG']==5) 
            {
                echo '<pre>';
                return print_r($client->__getFunctions()); 
                return print_r($client->__getTypes()); 
                echo '</pre>';
            }
        $function = $dados['FUNCTION'];
        $arguments= array($dados['FUNCTION']=>array("token" => $dados['SENHA']));
        $options = array('location' => $dados['URL']);
        $result = $client->__soapCall($function, $arguments, $options);
        //=======================debug
            IF($dados['DEBUG']==1)
            {   
                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders());

            }elseif ($dados['DEBUG']==2) {

                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());

            }elseif ($dados['DEBUG']==3) {

                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";

            } elseif ($dados['DEBUG']==4) {

                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders()); 
                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());
                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";
                echo "========= RESPONSE Headers=========" . PHP_EOL;
                var_dump(htmlentities($client->__getLastResponseHeaders()));
            }    
        //================================================================================ 
        foreach ($result->GetItemsResult->IntegracaoUsuarioInfo as $key => $value) 
        {
            if($key>=0)
            {  
                //Antes de inserri o log de cadastro na base de dados vou verificar se exite
                $checkuser="select COD_EXT_USER from log_integration_user where COD_EXT_USER=".rtrim(trim($value->UsuarioId))." and COD_EMPRESA=".$dados['cod_empresa'];
                $rsnum=mysqli_query($dados['conntemp'], $checkuser);
                if(mysqli_num_rows($rsnum)<=0)
                { 
                    if(fnLimpaDoc($value->CPF=='')){$CPFCNPJ=$value->CNPJ;}else{$CPFCNPJ=$value->CPF;};
                        $xamls= utf8_encode(addslashes(str_replace(array("\n",""),array(""," "), var_export($value,true))));
                        $insertolog="INSERT INTO log_integration_user 
                                                             (COD_EXT_USER, 
                                                              COD_EMPRESA,
                                                              NUM_CGCECPF,
                                                              DES_VENDA,
                                                              COD_INSERT) 
                                                              VALUES 
                                                              (
                                                              ".rtrim(trim($value->UsuarioId)).", 
                                                               ". $dados['cod_empresa'].", 
                                                               '".fnLimpaDoc($CPFCNPJ)."', 
                                                               '$xamls', 
                                                               '0'
                                                               );";
                             mysqli_query($dados['conntemp'], $insertolog);
                } else {
                       $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($value,true)));
                       $updatelog="UPDATE log_integration_user SET DES_VENDA='".$xamls."',
                                                                   COD_INSERT='0' 
                             WHERE COD_EXT_USER=".rtrim(trim($value->UsuarioId))." and
                                   COD_INSERT='1' and
                                   COD_EMPRESA=".$dados['cod_empresa'];
                       mysqli_query($dados['conntemp'], $updatelog);
                    
                }             
               
                $UsuarioId[$key]=array('UsuarioId'=>rtrim(trim($value->UsuarioId)));
            }
        }
        return $UsuarioId;
}
function SyncUsuario_complete($dados){ 
    $client = new SoapClient($dados['URLWSDL'],
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
            if ($dados['DEBUG']==5) 
            {
                echo '<pre>';
                return print_r($client->__getFunctions()); 
                return print_r($client->__getTypes()); 
                echo '</pre>';
            }
        $function = $dados['FUNCTION'];
        $arguments= array($dados['FUNCTION']=>array("token" => $dados['SENHA'],
                                                    "usuarioId"=>$dados['usuarioId']                            
                                                    ));
        $options = array('location' => $dados['URL']);
        $result = $client->__soapCall($function, $arguments, $options);
        //=======================debug
            IF($dados['DEBUG']==1)
            {   
                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders());

            }elseif ($dados['DEBUG']==2) {

                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());

            }elseif ($dados['DEBUG']==3) {

                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";

            } elseif ($dados['DEBUG']==4) {

                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders()); 
                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());
                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";
                echo "========= RESPONSE Headers=========" . PHP_EOL;
                var_dump(htmlentities($client->__getLastResponseHeaders()));
            } 
}
//fim do cadastro
//inicio da venda

function SyncPedidoVenda_GetItems2($dados)
{
     $client = new SoapClient($dados['URLWSDL'],
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
            if ($dados['DEBUG']==5) 
            {
                echo '<pre>';
                return print_r($client->__getFunctions()); 
                return print_r($client->__getTypes()); 
                echo '</pre>';
            }
        $function = $dados['FUNCTION'];
        $arguments= array($dados['FUNCTION']=>array("token" => $dados['SENHA']));
        $options = array('location' => $dados['URL']);
        $result = $client->__soapCall($function, $arguments, $options);
        
        //=======================debug
            IF($dados['DEBUG']==1)
            {   
                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders());

            }elseif ($dados['DEBUG']==2) {

                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());

            }elseif ($dados['DEBUG']==3) {

                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";

            } elseif ($dados['DEBUG']==4) {

                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders()); 
                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());
                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";
                echo "========= RESPONSE Headers=========" . PHP_EOL;
                var_dump(htmlentities($client->__getLastResponseHeaders()));
            }    
        //================================================================================ 
        foreach ($result->GetItems2Result->IntegracaoPedidoVendaInfo2 as $key => $value) 
        {
            if($key>=0)
            {        
                //statudo do pedido OK 
                //insere na base de dados
                if($value->Status ==1 || 
                   $value->Status ==9 || 
                   $value->Status ==10 ||
                   $value->Status ==14 ||     
                   $value->Status ==16){  
                            //Antes de inserri o log de cadastro na base de dados vou verificar se exite
                            $checkuser="select COD_EXT_USER from log_integration_venda where COD_EXT_VEN=".rtrim(trim($value->PedidoId))." and COD_EMPRESA=".$dados['cod_empresa'];
                            $rsnum=mysqli_query($dados['conntemp'], $checkuser);
                            if(mysqli_num_rows($rsnum)<=0)
                            { 
                                      $startDate = time();
                                    $datverifi=date('Y-m-d H:i:s', strtotime('+3 day', $startDate));
                                    
                                    $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($value,true)));
                                    $insertolog="INSERT INTO log_integration_venda 
                                                                         (COD_EXT_VEN,
                                                                          COD_EXT_USER, 
                                                                          COD_EMPRESA,
                                                                          DES_VENDA,
                                                                          STATUS_PEDIDO,
                                                                          COD_INSERT,
                                                                          DATA_VERIFICA) 
                                                                          VALUES 
                                                                          (
                                                                         ".rtrim(trim($value->PedidoId)).",
                                                                         ".rtrim(trim($value->UsuarioId)).", 
                                                                         ".$dados['cod_empresa'].", 
                                                                         '$xamls', 
                                                                         '".rtrim(trim($value->Status))."',     
                                                                         '0',
                                                                         '".$datverifi."'
                                                                           );";

                                         mysqli_query($dados['conntemp'], $insertolog);

                            } else {
                                   $xamls= utf8_encode(addslashes(str_replace(array("\n",""),array(""," "), var_export($value,true))));
                                   $updatelog="UPDATE log_integration_venda SET DES_VENDA='".$xamls."',
                                                                               COD_INSERT='0' 
                                         WHERE COD_EXT_VEN=".rtrim(trim($value->PedidoId))." and
                                               COD_INSERT='1' and
                                               COD_EMPRESA=".$dados['cod_empresa'];
                                   mysqli_query($dados['conntemp'], $updatelog);
                            }
                    $PedidoId[$key]=array('PedidoId'=>rtrim(trim($value->PedidoId)));
                }else{
                 //complete
                    $dados1=array(  'URLWSDL'=>$dados['URLWSDL'],
                                    'URL'=>$dados['URL'],
                                    'SENHA'=>$dados['SENHA'],
                                    'DEBUG'=>$dados['DEBUG'],
                                    'FUNCTION'=>'Complete',
                                    'pedidoId'=>rtrim(trim($value->PedidoId)),
                                    'cod_empresa'=>$dados['cod_empresa'],     
                                    'conntemp'=>$dados['conntemp']  
                                   ); 
                   $PedidoId=SyncPedidoVenda_complete($dados1);  
                }   
            }
        }
        return $PedidoId;
}
 
function SyncPedidoVenda_complete($dados)
{
  $client = new SoapClient($dados['URLWSDL'],
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
            if ($dados['DEBUG']==5) 
            {
                echo '<pre>';
                return print_r($client->__getFunctions()); 
                return print_r($client->__getTypes()); 
                echo '</pre>';
            }
        $function = $dados['FUNCTION'];
        $arguments= array($dados['FUNCTION']=>array("token" => $dados['SENHA'],
                                                    "pedidoId"=>$dados['pedidoId']                            
                                                    ));
        $options = array('location' => $dados['URL']);
        $result = $client->__soapCall($function, $arguments, $options);
        //=======================debug
            IF($dados['DEBUG']==1)
            {   
                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders());

            }elseif ($dados['DEBUG']==2) {

                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());

            }elseif ($dados['DEBUG']==3) {

                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";

            } elseif ($dados['DEBUG']==4) {

                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders()); 
                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());
                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";
                echo "========= RESPONSE Headers=========" . PHP_EOL;
                var_dump(htmlentities($client->__getLastResponseHeaders()));
            }  
}
function SyncPedidoVenda_select($dados)
{
     $client = new SoapClient($dados['URLWSDL'],
                                 array( 
                                        'trace'=>true,
                                        'exceptions'=>true,
                                        'connection_timeout' => 10,
                                        'cache_wsdl' => WSDL_CACHE_NONE,
                                        'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                        'encoding' => 'UTF-8' 
                                    ));
            if ($dados['DEBUG']==5) 
            {
                echo '<pre>';
                return print_r($client->__getFunctions()); 
                return print_r($client->__getTypes()); 
                echo '</pre>';
            }
        $function = $dados['FUNCTION'];
        $arguments= array($dados['FUNCTION']=>array("token" => $dados['SENHA'],
                                                    "pedidoId"=>$dados['pedidoId']));
        $options = array('location' => $dados['URL']);
        $result = $client->__soapCall($function, $arguments, $options);
        
        //=======================debug
            IF($dados['DEBUG']==1)
            {   
                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders());

            }elseif ($dados['DEBUG']==2) {

                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());

            }elseif ($dados['DEBUG']==3) {

                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";

            } elseif ($dados['DEBUG']==4) {

                echo "====== REQUEST HEADERS =====" . PHP_EOL;
                var_dump($client->__getLastRequestHeaders()); 
                echo "========= REQUEST ==========" . PHP_EOL;
                var_dump($client->__getLastRequest());
                echo "========= RESPONSE =========" . PHP_EOL;
                echo htmlentities($client->__getLastResponse())."\n";
                echo "========= RESPONSE Headers=========" . PHP_EOL;
                var_dump(htmlentities($client->__getLastResponseHeaders()));
            }    
        //================================================================================ 
           $ARRAYPAG1=array('Status'=>$result->SelectResult->Status,
                 'DataPagamento'=>$result->SelectResult->DataPagamento,
                 'DataCancelamento'=>$result->SelectResult->DataCancelamento,
                 'PedidoId'=>$result->SelectResult->PedidoId
                 );
        return $ARRAYPAG1;
}