<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
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

$file='../aquivosX/bonus'.date('YmdHis').'.txt';
file_put_contents($file, $Capturajson);

$arrayjson=json_decode($Capturajson,true);

/*$arrayString = print_r($arrayjson, true);
$file='../log/teste_teste123456.txt';
file_put_contents($file, $arrayString);
*/

//verificar se a chave cpf esta preenchido
if(empty($arrayjson[sale][netSaleValue]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                         {
                          "field": "netSaleValue",
                          "message": "Por favor preencha o Valor R$!",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                       }';    
      echo $erroinformation;
      exit();  
}
if($arrayjson[sale][netSaleValue]<='0.00')
{
    http_response_code(400);
    $erroinformation='{"errors": [
                         {
                          "field": "netSaleValue",
                          "message": "Valor total de venda deve ser maior que R$ 0.00!",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                        ]
                    }';    
      echo $erroinformation;
      exit();  
}
if(empty($arrayjson[identification][identificationCode]))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                         {
                          "field": "identificationCode",
                          "message": "Por favor preencha a identificacao do cliente !",
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
                          "message": "Por favor preencha o Documento !",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                        ]
                       }';    
      echo $erroinformation;
      exit();  
}



$xmlbusca=' <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                <soapenv:Header/>
                <soapenv:Body>
                   <fid:BuscaConsumidor>
                      <fase>fase1</fase>
                      <opcoesbuscaconsumidor>
                         <cartao>'.$arrayjson[identification][costumerId].'</cartao>
                         <cpf>'.$arrayjson[identification][costumerId].'</cpf>
                      </opcoesbuscaconsumidor>
                    <dadosLogin>
                            <login>'.$arraydadosaut[0].'</login>
                            <senha>'.$arraydadosaut[1].'</senha>
                            <idloja>'.$arraydadosaut[2].'</idloja>
                            <idcliente>'.$arraydadosaut[4].'</idcliente>
                        </dadosLogin>
                   </fid:BuscaConsumidor>
                </soapenv:Body>
                </soapenv:Envelope>';
$arrayconsultacliente=fnvalidaconsumidor($xmlbusca);


if($arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_h_saldo][saldodisponivel] != '0,00')
{
  //VALIDAR O SALDO ANTES DE ENVIAR PARA A BONUS OU FINALIZAÇÃO DA VENDA
        $resgateconsulta= fnValorSQLEXtrato($arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_h_saldo][saldodisponivel],2);
        
        if($arrayjson[sale][netSaleValue]<= $arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_h_saldo][saldodisponivel])
        {
           $resgateconsulta= $arrayjson[sale][netSaleValue];
        }    
        
        for ($i=1;$i <='2';$i++) {         
            sleep(0.25); 
            $descontovenda='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                            <soapenv:Header/>
                            <soapenv:Body>
                               <fid:ValidaDescontos>
                                    <cpfcnpj>'.$arrayjson[identification][costumerId].'</cpfcnpj>
                                    <cartao>'.$arrayjson[identification][costumerId].'</cartao> 
                                    <valortotalliquido>'.$arrayjson[sale][netSaleValue].'</valortotalliquido>
                                    <valor_resgate>'.$resgateconsulta.'</valor_resgate>
                                    <dadosLogin>
                                                <login>'.$arraydadosaut[0].'</login>
                                                <senha>'.$arraydadosaut[1].'</senha>
                                                <idloja>'.$arraydadosaut[2].'</idloja>
                                                <idcliente>'.$arraydadosaut[4].'</idcliente>
                                    </dadosLogin>
                                </fid:ValidaDescontos>
                            </soapenv:Body>
                         </soapenv:Envelope>';
           
            $descontos=fnvalidaconsumidor($descontovenda);
            if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='49')
            {
                $resgate=fnValorSQLEXtrato($descontos[body][envelope][body][validadescontosresponse][validadescontos][minimoresgate],2);
                $resgateconsulta=fnValorSQLEXtrato($descontos[body][envelope][body][validadescontosresponse][validadescontos][maximoresgate],2); 
               
            }  
             if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='52')
             {  
                 break;    
             }  
        }
        if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='52')
        {  
            
            $resgate=fnValorSQLEXtrato($descontos[body][envelope][body][validadescontosresponse][validadescontos][minimoresgate],2);  
           if((float) $descontos[body][envelope][body][validadescontosresponse][validadescontos][maximoresgate] >
               (float) $descontos[body][envelope][body][validadescontosresponse][validadescontos][saldo_disponivel])
            {
               $maxresg=fnValorSQLEXtrato($descontos[body][envelope][body][validadescontosresponse][validadescontos][saldo_disponivel],2); 
            }
       
             $bonus='{
                "partnerCode": "1002", 
                "nextStep": "finalize",
                "customerText": "",
                "operatorText": "Selecione um Bônus",
                "bonus": [
                            {
                                "type": "totalDiscount",
                                "bonusAmount": '.fnValorSQLEXtrato($descontos[body][envelope][body][validadescontosresponse][validadescontos][saldo_disponivel],2).',
                                "mandatoryUseBonuses": false,
                                "canDiscountAfterBonus": true,
                                "canUsePartialBonus": true,
                                "operatorText": "'.$descontos[body][envelope][body][validadescontosresponse][validadescontos][msgerro].'",
                                "customerText": "",
                                "bonusReferenceValue": '.$arrayjson[sale][netSaleValue].',
                                "bonusMax": '.$resgateconsulta.',
                                "bonusMin": '.$resgate.'
                            }
                        ]
            }'; 
        }else{
            $bonus='{
                "partnerCode": "1002",
                "nextStep": "finalize",
                "customerText": "",
                "operatorText": "Selecione um Bônus",
                "bonus": [
                            {
                                "type": "totalDiscount",
                                "bonusAmount": 0.00,
                                "mandatoryUseBonuses": false,
                                "canDiscountAfterBonus": true,
                                "canUsePartialBonus": true,
                                "operatorText": "'.$descontos[body][envelope][body][validadescontosresponse][validadescontos][msgerro].'",
                                "customerText": "",
                                "bonusReferenceValue": '.$arrayjson[sale][netSaleValue].',
                                "bonusMax": 0.00,
                                "bonusMin": 0.00
                            }
                        ]
            }'; 
        }
 
 //    echo $bonus;
    
    
    
}else{
    //quando tenho saldo maior que zero
    $bonus='{
                    "partnerCode": "1002",
                    "nextStep": "finalize",
                    "customerText": "",
                    "operatorText": "Voce pode concluir a venda e continuar acumulando",
                     "identification":{
                                            "costumerId": "'.$arrayjson[identification][costumerId].'"
                                      }
                }';
}
echo $bonus;