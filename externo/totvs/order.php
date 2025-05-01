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
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(400);
    $erroinformation = '{"errors": [
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

function fnvalorlocal($brl, $casasDecimais = 2)
{
    // Se já estiver no formato USD, retorna como float e formatado
    if (preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}



include_once '../../_system/_functionsMain.php';
include_once 'funcao.php';
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
$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
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

$Capturajson = file_get_contents("php://input");
/*
$arrayString = $Capturajson;
$directory = './log/';  // Caminho do diretório
$file1 = $directory . 'teste_123456799125.txt';

// Verifica se o diretório existe, se não, cria-o
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);  // Cria o diretório com permissões
}

// Agora escreve o conteúdo no arquivo
if (file_put_contents($file1, $arrayString) !== false) {
  //  echo "Arquivo criado e conteúdo salvo com sucesso!";
} else {
  //  echo "Erro ao salvar o arquivo!";
}
*/




$file = '../aquivosX/order' . date('YmdHis') . '.txt';
file_put_contents($file, $Capturajson);
$arrayjson = json_decode($Capturajson, true);


foreach ($arrayjson['sale']['paymentMethods'] as $key => $value) {
    $pagamento .= $value['description'] . '|-|';
}
$pagamento = rtrim($pagamento, '|-|');

if (empty($arrayjson['sale']['paymentMethods'][0]['description'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                         {
                          "field": "description",
                          "message": "paymentMethods  description deve ser preenchido",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                        ]
                    }';
    echo $erroinformation;
    exit();
}

if (empty($arrayjson['sale']['paymentMethods'][0]['paymentMethodId'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                         {
                          "field": "paymentMethodId",
                          "message": "paymentMethods  paymentMethodId deve ser preenchido",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                        ]
                       }';
    echo $erroinformation;
    exit();
}
if (!array_key_exists('items', $arrayjson['sale'])) {

    http_response_code(400);
    $erroinformation = '{"errors": [
                        {
                         "field": "sale",
                         "message": "Objecto do item é obrigatorio!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                      }';
    echo $erroinformation;
    exit();
}
if (!array_key_exists('items', $arrayjson['sale'])) {

    http_response_code(400);
    $erroinformation = '{"errors": [
                        {
                         "field": "sale",
                         "message": "Objecto do item é obrigatorio!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                      }';
    echo $erroinformation;
    exit();
}

foreach ($arrayjson['sale']['items'] as $key => $dadositem) {
    //validar campos obrigatorios
    if (empty($dadositem['productDescription'])) {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    if (empty($dadositem['productCode'])) {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    if (empty($dadositem['quantityItems']) || $dadositem['quantityItems'] <= '0') {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    if (empty($dadositem['grossSaleValue']) || $dadositem['grossSaleValue'] <= '0.00') {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    $vlitem = $dadositem['grossSaleValue'] / $dadositem['quantityItems'];

    //não existe resgate para esse cliente
    //valor bruto do item 
    //valor de desconto no item
    $lvliqitem = $dadositem['netSaleValue'] / $dadositem['quantityItems'];
    //diferenteça de valor para aplicar no desconto   
    $desconto = $vlitem - $lvliqitem;

    $itens .= '<vendaitem>
                <id_item>' . $key . '</id_item>
                <produto>' . $dadositem['productDescription'] . '</produto>
                <codigoproduto>' . $dadositem['productCode'] . '</codigoproduto>
                <quantidade>' . $dadositem['quantityItems'] . '</quantidade>
                <valorbruto>' . fnvalorlocal($vlitem, 2) . '</valorbruto>
                <descontovalor>' . fnvalorlocal($desconto, 2) . '</descontovalor>
                <valorliquido>' . fnvalorlocal($lvliqitem, 2) . '</valorliquido>
            </vendaitem>';
    $valvenda[] = $lvliqitem * $dadositem['quantityItems'];
}
//campos obrigatorios na venda

if (empty($arrayjson['sale']['externalSaleId'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
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
if (empty($arrayjson['identification']['operatorCode']) || empty($arrayjson['identification']['operatorName'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                         {
                          "field": "operatorCode",
                          "message": "Atendente deve ser preenchido",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         },
                         {
                          "field": "operatorName",
                          "message": "Atendente deve ser preenchido",
                          "locationType": "body",
                          "location": "https://homol.marka.com/pages/api_inicio"
                         }
                        ]
                    }';
    echo $erroinformation;
    exit();
}
if (empty($arrayjson['sale']['sellerName'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
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
if (empty($arrayjson['sale']['posCode'])) {
    http_response_code(400);
    $erroinformation = '{"errors": [
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
//$vendedor=$arrayjson[sale][posCode].'-'.$arrayjson[sale][sellerName];
$vendedor = $arrayjson['sale']['sellerName'];
$atendente = $arrayjson['identification']['operatorCode'] . '-' . $arrayjson['identification']['operatorName'];
$valvendaxmltotal = array_sum($valvenda);
unset($valvenda);
$descontoTOTAL = '0.00';
// print_r($arrayjson['sale']['paymentMethods']);
foreach ($arrayjson['sale']['paymentMethods'] as $key => $valuecred) {

    if ($valuecred['description'] === 'CREDEV') {
        $descontoTOTAL += $valuecred['netSaleValue'];
    }
}
if ($descontoTOTAL > 0) {
    $valvendaxmlLiquido = $valvendaxmltotal - $descontoTOTAL;
} else {
    $valvendaxmlLiquido = $valvendaxmltotal;
}

$pdvcod = $arrayjson['sale']['externalSaleId'] . '-' . $arrayjson['sale']['posCode'];
if ($arrayjson['identification']['costumerId'] == '') {
    $cartao = '0';
} else {
    $cartao = $arrayjson['identification']['costumerId'];
}
$vendaxml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                <soapenv:Header/>
                <soapenv:Body>
                <fid:InsereVenda>
                    <fase>fase6</fase>
                    <venda>
                        <id_vendapdv>' . $pdvcod . '</id_vendapdv>
                        <datahora>' . date('Y-m-d H:i:s') . '</datahora>
                        <cartao>' . $cartao . '</cartao>
                        <valortotalbruto>' . fnvalorlocal($valvendaxmltotal, 2) . '</valortotalbruto>
                        <descontototalvalor>' . fnvalorlocal($descontoTOTAL, 2) . '</descontototalvalor>
                        <valortotalliquido>' . fnvalorlocal($valvendaxmlLiquido, 2) . '</valortotalliquido>
                        <valor_resgate>' . fnvalorlocal($arrayjson['bonus']['bonusAmountUsed'], 2) . '</valor_resgate>
                        <cupomfiscal></cupomfiscal>
                        <cupomdesconto></cupomdesconto>
                        <formapagamento>' . $pagamento . '</formapagamento>
                        <codatendente>' . $atendente . '</codatendente>
                        <codvendedor>' . $vendedor . '</codvendedor>
                        <itens>
                            ' . $itens . '
                        </itens>
                    </venda>
                    <dadosLogin>
                            <login>' . $arraydadosaut[0] . '</login>
                            <senha>' . $arraydadosaut[1] . '</senha>
                            <idloja>' . $arraydadosaut[2] . '</idloja>
                            <idcliente>' . $arraydadosaut[4] . '</idcliente>
                                
                    </dadosLogin>
                </fid:InsereVenda>
                </soapenv:Body>
            </soapenv:Envelope>';
//print_r($vendaxml);
/*$arrayString = $vendaxml;
$directory = './log/';  // Caminho do diretório
$file1 = $directory . 'teste_123456799125.txt';

// Verifica se o diretório existe, se não, cria-o
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);  // Cria o diretório com permissões
}

// Agora escreve o conteúdo no arquivo
if (file_put_contents($file1, $arrayString) !== false) {
  //  echo "Arquivo criado e conteúdo salvo com sucesso!";
} else {
  //  echo "Erro ao salvar o arquivo!";
}
*/
$vendamarka = fnvenda($vendaxml);

if ($vendamarka['body']['envelope']['body']['inserevendaresponse']['inserevendaresponse']['coderro'] == '19') {
    $saldodisponivel = $vendamarka['body']['envelope']['body']['inserevendaresponse']['inserevendaresponse']['acao_h_saldo']['saldodisponivel'];
    $creditovenda = $vendamarka['body']['envelope']['body']['inserevendaresponse']['inserevendaresponse']['acao_h_saldo']['creditovenda'];
    echo '{
          "nextStep": null,
          "transactionId": "' . $arrayjson['sale']['externalSaleId'] . '",
          "customerText":"\r\n Saldo Disponivel : R$ ' . $saldodisponivel . ' \r\nSaldo Acumulado na venda : R$ ' . $creditovenda . ' \r\n"
          }';
} else {
    if ($vendamarka['body']['envelope']['body']['inserevendaresponse']['inserevendaresponse']['coderro'] == '79') {
        echo '{
              "nextStep": null,
              "transactionId": "' . $arrayjson['sale']['externalSaleId'] . '"
              }';
    } else {
        http_response_code(400);
        $erroinformation = '{"errors": [
                                        {
                                         "field": "externalSaleId",
                                         "message": "' . $vendamarka['body']['envelope']['body']['inserevendaresponse']['inserevendaresponse']['msgerro'] . '",
                                         "locationType": "body",
                                         "location": "https://homol.marka.com/pages/api_inicio"
                                        }
                            ]
                        }';
        echo $erroinformation;
        exit();
    }
}
