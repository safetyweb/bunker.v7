<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/JSON; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$seconds_to_cache = 3600;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Last-Modified: $ts");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
if($_SERVER[REQUEST_METHOD]!='POST')
{
     http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "O metodo para capturar deve ser POST",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                    }';    
     echo $erroinformation;
     exit();
}  

function fnvalorlocal($brl, $casasDecimais = 2) {
    // Se já estiver no formato USD, retorna como float e formatado
    if(preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}



include '../../../_system/_functionsMain.php';
include '../funcao.php';   
$passmarka= getallheaders();

if(!array_key_exists('authorizationCode', $passmarka))
{
   http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                   }';    
     echo $erroinformation;
     exit();  
} 

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);
if(!array_key_exists('4', $arraydadosaut))
{

    http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                   }';    
     echo $erroinformation;
     exit();  
}

$cod_empresa=$arraydadosaut[4];
$cod_univend=$arraydadosaut[2];
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);
$pdvcod=$arrayjson[externalSaleCode].'-'.$arrayjson[posCode];
//capturar o item e colocar no xml marka
//campos obrigatorios na venda
if(array_key_exists('items', $arrayjson) && !empty($arrayjson[items]))
{
    //verificar se tem item
    foreach ( $arrayjson[items] as $chaveproduto => $DADOSPRODUTO) {
        
        
        if(empty($DADOSPRODUTO[grossSaleValue]) || $DADOSPRODUTO[grossSaleValue] < '0')
        {
             
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "grossSaleValue",
                                  "message": "Campos devem ser preenchidos grossSaleValue",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit();  
        }
        if(empty($DADOSPRODUTO[itenID]))
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "itenID",
                                  "message": "Campos devem ser preenchidos itenID",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit();  
        }
        if(empty($DADOSPRODUTO[productDescription]))
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "productDescription",
                                  "message": "Campos devem ser preenchidos productDescription",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit();  
        }
        if(empty($DADOSPRODUTO[productCode]))
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "productCode",
                                  "message": "Campos devem ser preenchidos productCode",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit();  
        }
        if(empty($DADOSPRODUTO[quantityItems]) || $DADOSPRODUTO[quantityItems] <= '0')
        {
             
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "quantityItems",
                                  "message": "Campos devem ser preenchidos quantityItems",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit();  
        }
         $itemestorno.='<vendaitem>
                            <id_item>'.$DADOSPRODUTO[itenID].'</id_item>
                            <produto>'.$DADOSPRODUTO[productDescription].'</produto>
                            <codigoproduto>'.$DADOSPRODUTO[productCode].'</codigoproduto>
                            <quantidade>'.$DADOSPRODUTO[quantityItems].'</quantidade>
                       </vendaitem>' ; 
         $aray_retorno[items][]=array('itenID'=>$DADOSPRODUTO[itenID],
                                    'productDescription'=>$DADOSPRODUTO[productDescription],
                                    'productCode'=>$DADOSPRODUTO[productCode],
                                    'QuantityItems'=>$DADOSPRODUTO[quantityItems]
                                    );
    }
   
    
    if(empty($arrayjson[posCode]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "posCode",
                                         "message": "Campos devem ser preenchidos posCode",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }
   if(empty($arrayjson[externalSaleCode]))
   {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "externalSaleCode",
                                         "message": "Campos devem ser preenchidos externalSaleCode",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }
   if(empty($arrayjson[identification][customerDocument]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "customerDocument",
                                         "message": "Campos devem ser preenchidos customerDocument",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }
  

    $estorno='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                <soapenv:Header/>
                <soapenv:Body>
                   <fid:EstornaVendaParcial>
                      <fase>fase7</fase>
                      <estorno>
                         <id_vendapdv>'.$pdvcod.'</id_vendapdv>
                         <cartao>'.$arrayjson[identification][customerDocument].'</cartao>
                         <itens>
                            '.$itemestorno.'
                         </itens>
                      </estorno>
                    <dadosLogin>
                        <login>'.$arraydadosaut[0].'</login>
                        <senha>'.$arraydadosaut[1].'</senha>
                        <idloja>'.$arraydadosaut[2].'</idloja>
                        <idcliente>'.$arraydadosaut[4].'</idcliente>
                       <idmaquina>'.$arrayjson[posCode].'</idmaquina>    
                  </dadosLogin>
                   </fid:EstornaVendaParcial>
                </soapenv:Body>
             </soapenv:Envelope>';
   $retornoestrno= estornovenda($estorno);
   
   echo '{
            "transactionId": "'.$arrayjson[externalSaleCode].'",
            "items":'.json_encode($aray_retorno[items],JSON_PRETTY_PRINT).',
            "status":false,
            "message":"Estorno concluido!"    
        }'; 
   
   
   
   
}else{    
   
    if(empty($arrayjson[posCode]) || empty($arrayjson[externalSaleCode]) || empty($arrayjson[identification][customerDocument]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "posCode",
                              "message": "Campos devem ser preenchidos externalSaleCode,customerDocument e posCode",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             },
                             {
                              "field": "externalSaleCode",
                              "message": "Campos devem ser preenchidos externalSaleCode,customerDocument e posCode",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             },
                             {
                              "field": "customerDocument",
                              "message": "Campos devem ser preenchidos externalSaleCode,customerDocument e posCode",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }

    $estorno='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                <soapenv:Header/>
                <soapenv:Body>
                   <fid:EstornaVenda>
                      <fase>fase7</fase>
                      <id_vendapdv>'.$pdvcod.'</id_vendapdv>
                       <dadosLogin>
                             <login>'.$arraydadosaut[0].'</login>
                            <senha>'.$arraydadosaut[1].'</senha>
                            <idloja>'.$arraydadosaut[2].'</idloja>
                            <idcliente>'.$arraydadosaut[4].'</idcliente>
                           <idmaquina>'.$arrayjson[posCode].'</idmaquina>    
                      </dadosLogin>
                   </fid:EstornaVenda>
                </soapenv:Body>
             </soapenv:Envelope>';

   $retornoestrno= estornovenda($estorno);
   echo '{
            "transactionId": "'.$arrayjson[externalSaleCode].'",
            "status":false,
            "message":"Estorno concluido!"    
        }'; 
}        
?>