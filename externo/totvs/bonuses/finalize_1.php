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
                                    },
                                    {
                                     "field": "customerDocument",
                                     "message": "O metodo para capturar deve ser POST",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    },
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
                        },
                        {
                         "field": "customerDocument",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        },
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
                        },
                        {
                         "field": "customerDocument",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        },
                       ]
                      }';    
     echo $erroinformation;
     exit();  
}

$cod_empresa=$arraydadosaut[4];
$cod_univend=$arraydadosaut[2];
$Capturajson=file_get_contents("php://input");
$file='../aquivosX/finalizer'.date('Y_m_d-H:i:s');
file_put_contents($file, $Capturajson);

$arrayjson=json_decode($Capturajson,true);
$pagamento=$arrayjson[sale][paymentMethods][0][description];

//capturar o item e colocar no xml marka

if(empty($arrayjson[sale][paymentMethods][0][description]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "description",
                                     "message": "description deve ser preenchido",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                ]
                     }';    
      echo $erroinformation;
      exit();  
}

if(empty($arrayjson[sale][paymentMethods][0][paymentMethodId]))                                             
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "paymentMethodId",
                                     "message": "paymentMethodId deve ser preenchido",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                ]
                       }';    
      echo $erroinformation;
      exit();  
}
if(!array_key_exists('items', $arrayjson[sale]))
{

    http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "field": "identificationCode",
                                     "message": "Objecto do item é obrigatorio!",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                 ]
                      }';    
     echo $erroinformation;
     exit();  
}

foreach ($arrayjson[sale][items] as $key => $dadositem) {
    //validar campos obrigatorios
    if(empty($dadositem[productDescription]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "productDescription",
                              "message": "Por favor preencha a descricao do produto",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }
   if(empty($dadositem[productCode]))
   {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "productCode",
                                         "message": "Por favor preencha o codigo do produto",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                                    ]
                           }';    
          echo $erroinformation;
          exit();  
    }
   
    if(empty($dadositem[quantityItems]) || $dadositem[quantityItems] <= '0' )
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "quantityItems",
                                         "message": "Por favor informe a quantidade",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                                    ]
                        }';    
          echo $erroinformation;
          exit();  
    }
    if(empty($dadositem[grossSaleValue]) || $dadositem[grossSaleValue] <= '0.00')
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "grossSaleValue",
                                         "message": "Por favor informe o valor do item",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                                       ]
                        }';    
          echo $erroinformation;
          exit();  
    }
    if(empty($dadositem[itenID]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "field": "itenID",
                                         "message": "itenID deve ser preenchido",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                                       ]
                        }';    
          echo $erroinformation;
          exit();  
    }
    
    if(empty($arrayjson[bonus][bonusAmountUsed]))
    {
        //não existe resgate para esse cliente
        //valor bruto do item 
       $vlitem=$dadositem[grossSaleValue]/$dadositem[quantityItems];
       //valor de desconto no item
       $lvliqitem=$dadositem[netSaleValue]/$dadositem[quantityItems];
       //diferenteça de valor para aplicar no desconto   
       $desconto= $vlitem-$lvliqitem;

        $itens.='<vendaitem>
                    <id_item>'.$dadositem[itenID].'</id_item>
                    <produto>'.$dadositem[productDescription].'</produto>
                    <codigoproduto>'.$dadositem[productCode].'</codigoproduto>
                    <quantidade>'.$dadositem[quantityItems].'</quantidade>
                    <valorbruto>'.fnvalorlocal($vlitem,2).'</valorbruto>
                    <descontovalor>'.fnvalorlocal($desconto,2).'</descontovalor>
                    <valorliquido>'.fnvalorlocal($lvliqitem,2).'</valorliquido>
                </vendaitem>';
        $valvenda[]=$lvliqitem*$dadositem[quantityItems];
    }else{
       //quando existir o resgate 
       //valor bruto do item 
       $vlitem=$dadositem[grossSaleValue]/$dadositem[quantityItems];
       //valor de desconto no item
        $itens.='<vendaitem>
                    <id_item>'.$dadositem[itenID].'</id_item>
                    <produto>'.$dadositem[productDescription].'</produto>
                    <codigoproduto>'.$dadositem[productCode].'</codigoproduto>
                    <quantidade>'.$dadositem[quantityItems].'</quantidade>
                    <valorbruto>'.fnvalorlocal($vlitem,2).'</valorbruto>
                    <descontovalor>0.00</descontovalor>
                    <valorliquido>'.fnvalorlocal($vlitem,2).'</valorliquido>
                </vendaitem>';
        $valvenda[]=$vlitem*$dadositem[quantityItems];
    }  
}





//campos obrigatorios na venda

if(empty($arrayjson[sale][externalSaleId]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "externalSaleId",
                                     "message": "Por favor preencha o  id externo da venda",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                   ]
                    }';    
      echo $erroinformation;
      exit();  
}
if(empty($arrayjson[identification][costumerId]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "costumerId",
                                     "message": "o cpf deve ser enviado no campo costumerId",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                   ]
                   }';    
      echo $erroinformation;
      exit();  
}
if(empty($arrayjson[identification][operatorCode]) || empty($arrayjson[identification][operatorName]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "operatorCode",
                                     "message": "Atendente deve ser preenchido",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                   ]
                    }';    
      echo $erroinformation;
      exit();  
}

if(empty($arrayjson[sale][sellerName]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "sellerName",
                                     "message": "Operado deve ser preenchido",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                   ]
                    }';    
      echo $erroinformation;
      exit();  
}
if(empty($arrayjson[sale][posCode]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "posCode",
                                     "message": "posCode deve ser preenchido",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                  ]
                  }';    
      echo $erroinformation;
      exit();  
}
 //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 
//somatoria de desconto na venda
//08-08 foi removido a forma de desconto do credev conforme reunião com o fernando generali
/*
foreach ($arrayjson[sale][paymentMethods] as $paymentMethodskey => $paymentMethods) {
    if($paymentMethods[paymentMethodId]=='20')
    {
       $vltroca=$paymentMethods[netSaleValue];
       //  <descontototalvalor>'.fnvalorlocal($vltroca,2).'</descontototalvalor>
    }    
}*/

if(empty($arrayjson[bonus][bonusAmountUsed]))
{
    $valorBrutoBonus='0.00';
       //capturar desconto na venda 
    $valdescvendaxml='0.00';
    //VALOR LIQUIDO DA VENDA
    $valLIQUIDOvendaxml=array_sum($valvenda);
   
    
}else{
    $valorBrutoBonus=$arrayjson[bonus][bonusAmountUsed];
    //capturar desconto na venda 
    (float)$valdescvendaxml=fnvalorlocal(array_sum($valvenda),2)- fnvalorlocal($arrayjson[bonus][bonusReferenceValue],2);
   //VALOR LIQUIDO DA VENDA
    $valLIQUIDOvendaxml=array_sum($valvenda)-$valdescvendaxml;
  
}
// $vendedor=$arrayjson[sale][posCode].'-'.$arrayjson[sale][sellerName];
$vendedor=$arrayjson[sale][sellerName];
$atendente=$arrayjson[identification][operatorCode].'-'.$arrayjson[identification][operatorName];
$valvendaxml=array_sum($valvenda);
//$valorDiferenca = $valvendaxml - $vltroca;

unset($valvenda); 
$pdvcod=$arrayjson[sale][externalSaleId].'-'.$arrayjson[sale][posCode];
$vendaxml='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
            <soapenv:Header/>
            <soapenv:Body>
               <fid:InsereVenda>
                  <fase>fase6</fase>
                  <venda>
                     <id_vendapdv>'.$pdvcod.'</id_vendapdv>
                     <datahora>'.date('Y-m-d H:i:s').'</datahora>
                     <cartao>'.$arrayjson[identification][costumerId].'</cartao>
                     <valortotalbruto>'.fnvalorlocal($valvendaxml,2).'</valortotalbruto>
                     <descontototalvalor>'.fnvalorlocal($valdescvendaxml,2).'</descontototalvalor>
                     <valortotalliquido>'.fnvalorlocal($valLIQUIDOvendaxml,2).'</valortotalliquido>
                     <valor_resgate>'.fnvalorlocal($arrayjson[bonus][bonusAmountUsed],2).'</valor_resgate>
                     <cupomfiscal></cupomfiscal>
                     <cupomdesconto></cupomdesconto>
                     <formapagamento>'.$pagamento.'</formapagamento>
                     <codatendente>'.$atendente.'</codatendente>
                     <codvendedor>'.$vendedor.'</codvendedor>
                     <itens>
                         '.$itens.'
                     </itens>
                  </venda>
                  <dadosLogin>
                        <login>'.$arraydadosaut[0].'</login>
                        <senha>'.$arraydadosaut[1].'</senha>
                        <idloja>'.$arraydadosaut[2].'</idloja>
                        <idcliente>'.$arraydadosaut[4].'</idcliente>
                        <idmaquina>'.$arrayjson[sale][posCode].'</idmaquina>    
                  </dadosLogin>
               </fid:InsereVenda>
            </soapenv:Body>
         </soapenv:Envelope>';
echo $vendaxml;
//$vendamarka=fnvenda($vendaxml);
if($vendamarka[body][envelope][body][inserevendaresponse][inserevendaresponse][coderro]=='19')
{    
    $saldodisponivel=$vendamarka[body][envelope][body][inserevendaresponse][inserevendaresponse][acao_h_saldo][saldodisponivel];
    $creditovenda=$vendamarka[body][envelope][body][inserevendaresponse][inserevendaresponse][acao_h_saldo][creditovenda];
    echo '{
          "nextStep": null,
          "transactionId": "'.$arrayjson[sale][externalSaleId].'",
          "customerText":"\r\n Saldo Disponivel : R$ '.$saldodisponivel.' \r\nSaldo Acumulado na venda : R$ '.$creditovenda.' \r\n"
          }';
}else{
   http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "field": "externalSaleId",
                                     "message": "'.$vendamarka[body][envelope][body][inserevendaresponse][inserevendaresponse][msgerro].'",
                                     "locationType": "body",
                                     "location": "https://homol.marka.com/pages/api_inicio"
                                    }
                                   ]
                   }';    
      echo $erroinformation;
      exit();    
}
?>