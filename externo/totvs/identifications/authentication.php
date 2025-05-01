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

$passmarka = getallheaders();
if (!array_key_exists('authorizationCode', $passmarka)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
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
include '../../../_system/_functionsMain.php';
include '../funcao.php';
$autoriz = fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut = explode(';', $autoriz);

if (!array_key_exists('4', $arraydadosaut)) {

    http_response_code(400);
    $erroinformation = '{"errors": [
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

$cod_empresa = $arraydadosaut[4];
$cod_univend = $arraydadosaut[2];
$connadm = $connAdm->connAdm();
$conncliente = connTemp($cod_empresa, '');
$Capturajson = file_get_contents("php://input");
//==============fim=================
$arrayjson = json_decode($Capturajson, true);



if (empty($arrayjson[authentication][code])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                             {
                              "field": "code",
                              "message": "Por favor preencha o Token!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                       }';
    echo $erroinformation;
    exit();
}
if (empty($arrayjson[sale][netSaleValue])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                             {
                              "field": "netSaleValue",
                              "message": "Por favor preencha o Valor R$!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                       }';
    echo $erroinformation;
    exit();
}
if ((float)$arrayjson[sale][netSaleValue] <= '0.00') {
    http_response_code(400);
    $erroinformation = '{"errors": [
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


if (empty($arrayjson[identification][identificationCode])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                             {
                              "field": "identificationCode",
                              "message": "Por favor preencha o Telefone!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';
    echo $erroinformation;
    exit();
}


if (empty($arrayjson[identification][costumerId])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                             {
                              "field": "costumerId",
                              "message": "Por favor preencha costumerId!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             },
                             {
                              "field": "costumerId",
                              "message": "Por favor preencha costumerId!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             },
                            ]
                        }';
    echo $erroinformation;
    exit();
}
$json = "SELECT * FROM log_tots WHERE cod_empresa=$cod_empresa AND CPF='" . $arrayjson[identification][costumerId] . "' ORDER BY ID desc  LIMIT 1;";
$retujson = mysqli_fetch_assoc(mysqli_query($conncliente, $json));
$dadoscad = json_decode(unserialize($retujson[LOG_JSON]), true);

$xml_validatoken = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                    <soapenv:Header/>
                    <soapenv:Body>
                       <fid:validaToken>
                          <tipoGeracao>1</tipoGeracao>
                          <token>' . $arrayjson[authentication][code] . '</token>
                          <celular>' . $dadoscad[identification][phone] . '</celular>
                          <cpf>' . $dadoscad[identification][document] . '</cpf>
                          <dadosLogin>
                            <login>' . $arraydadosaut[0] . '</login>
                            <senha>' . $arraydadosaut[1] . '</senha>
                            <idloja>' . $arraydadosaut[2] . '</idloja>
                            <idcliente>' . $arraydadosaut[4] . '</idcliente>
                        </dadosLogin>
                       </fid:validaToken>
                    </soapenv:Body>
                 </soapenv:Envelope>';
$returnvalidatoken = VALIDATOKEN($xml_validatoken);


/*$file = '../rel_log/auth' . date('YmdHis') . '.txt';
file_put_contents($file, $xml_validatoken);*/


//validando token OK
if ($returnvalidatoken[body][envelope][body][validatokenresponse][retornatoken][coderro] == '39') {
    //buscar dados do json gravado na base de dados.
    //inserir via webservice atualiza cadastro no marka junto com o token.
    //verificação de sexo
    if ($dadoscad[identification][gender] == 'Masculino') {
        $sexo = 1;
    } elseif ($dadoscad[identification][gender] == 'Feminino') {
        $sexo = 2;
    } else {
        $sexo = 3;
    }
    $atendente = $dadoscad[identification][operatorCode] . '-' . $dadoscad[identification][operatorName];
    //converter a data de nascimento
    $newDatensc = date("d/m/Y", strtotime($dadoscad[identification][birthday]));
    $cadws = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                    <soapenv:Header/>
                    <soapenv:Body>
                            <fid:AtualizaCadastro>
                                    <fase>fase1</fase>
                                    <cliente>
                                            <nome>' . fnAcentos($dadoscad[identification][name]) . '</nome>
                                            <cartao>' . $dadoscad[identification][document] . '</cartao>
                                            <cpf>' . $dadoscad[identification][document] . '</cpf>
                                            <sexo>' . $sexo . '</sexo>
                                            <cnpj>' . $dadoscad[identification][document] . '</cnpj>
                                            <datanascimento>' . $newDatensc . '</datanascimento>
                                            <telcelular>' . $dadoscad[identification][phone] . '</telcelular>
                                            <email>' . $dadoscad[identification][email] . '</email>
                                            <tokencadastro>' . $retujson[TOKEN] . '</tokencadastro>
                                            <canal>1</canal>
                                            <adesao>CT</adesao>    
                                            <tipocliente>F</tipocliente>
                                            <codatendente>' . $atendente . '</codatendente>
                                    </cliente>
                                    <dadosLogin>
                                            <login>' . $arraydadosaut[0] . '</login>
                                            <senha>' . $arraydadosaut[1] . '</senha>
                                            <idloja>' . $arraydadosaut[2] . '</idloja>
                                            <idcliente>' . $arraydadosaut[4] . '</idcliente>
                                    </dadosLogin>
                            </fid:AtualizaCadastro>
                    </soapenv:Body>
            </soapenv:Envelope>';
    $cadastr = fncadastro($cadws);

    //deletar log 
    $zeralog = "DELETE FROM log_tots WHERE  cod_empresa=$cod_empresa AND CPF='" . $dadoscad[identification][document] . "'";
    mysqli_query($conncliente, $zeralog);
    //********************************************************
    //VALIDAR O SALDO ANTES DE ENVIAR PARA A BONUS OU FINALIZAÇÃO DA VENDA
    $resgate = $cadastr[body][envelope][body][atualizacadastroresponse][atualizacadastroresponse][acao_h_saldo][saldodisponivel];
    if ($arrayjson[sale][netSaleValue] <= $cadastr[body][envelope][body][atualizacadastroresponse][atualizacadastroresponse][acao_h_saldo][saldodisponivel]) {
        $resgate = $arrayjson[sale][netSaleValue];
    }

    for ($i = 1; $i <= '2'; $i++) {
        sleep(0.25);
        $descontovenda = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                            <soapenv:Header/>
                            <soapenv:Body>
                               <fid:ValidaDescontos>
                                    <cpfcnpj>' . $arrayjson[identification][costumerId] . '</cpfcnpj>
                                    <cartao>' . $arrayjson[identification][costumerId] . '</cartao> 
                                    <valortotalliquido>' . $arrayjson[sale][netSaleValue] . '</valortotalliquido>
                                    <valor_resgate>' . $resgate . '</valor_resgate>
                                    <dadosLogin>
                                                <login>' . $arraydadosaut[0] . '</login>
                                                <senha>' . $arraydadosaut[1] . '</senha>
                                                <idloja>' . $arraydadosaut[2] . '</idloja>
                                                <idcliente>' . $arraydadosaut[4] . '</idcliente>
                                    </dadosLogin>
                                </fid:ValidaDescontos>
                            </soapenv:Body>
                         </soapenv:Envelope>';
        $descontos = fnvalidaconsumidor($descontovenda);
        if ($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro] == '49') {
            $resgate = $descontos[body][envelope][body][validadescontosresponse][validadescontos][minimoresgate];
        }
    }
    if ($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro] == '52') {
        $bonus = '{
                        "partnerCode": "1002",
                        "nextStep": "bonus",
                        "customerText": "",
                        "operatorText": "Selecione um Bônus",
                        "identification":{
                                        "costumerId": "' . $arrayjson[identification][costumerId] . '"
                                     }
                    }';
    } else {
        $bonus = '{
                    "partnerCode": "1002",
                    "nextStep": "finalize",
                    "customerText": "",
                    "operatorText": "Voce pode concluir a venda e continuar acumulando",
                     "identification":{
                                            "costumerId": "' . $arrayjson[identification][costumerId] . '"
                                      }
                }';
    }

    //**************************************************************
    echo $bonus;
} else {
    //verificar com o ricardo caso seja negativo como retornar
    //  echo '400 Bad Request';
    http_response_code(400);
    /*$bonus='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "'.$returnvalidatoken[body][envelope][body][validatokenresponse][retornatoken][msgerro].'",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                   }';    
     echo $bonus;*/
    echo $returnvalidatoken[body][envelope][body][validatokenresponse][retornatoken][msgerro];
}


//PRIMEIRO PASSO VERIFICAR SE O TOKEN ESTA ATIOVO PARA O ENVIO

/*
    0- VALIDAR O TOKEN
    1- INSERIR CADASTRO NO MARKA 
    2- IR PARA O BONUS
  
   */

//VALIDATOKEN($xmlbusca)    
