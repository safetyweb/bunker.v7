<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';

$passmarka = getallheaders();
if (!array_key_exists('authorizationCode', $passmarka)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}

$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
$arraydadosaut = explode(';', $autoriz);

// Validação do usuário
$admconn = $connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut[0]."', '".fnEncode($arraydadosaut[1])."','','','".$arraydadosaut[4]."','','')";
$buscauser = mysqli_query($admconn, $sql);
if (empty($buscauser->num_rows)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}

$user = mysqli_fetch_assoc($buscauser);

//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp = connTemp($arraydadosaut[4], '');
//====fim da conexão com a empresa

if (!array_key_exists(4, $arraydadosaut)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}

// Limitar tamanho do arquivo a 100 MB
$maxFileSize = 100 * 1024 * 1024; // 100 MB

// Extensões de arquivo permitidas
$allowedExtensions = ['txt', 'csv'];

$target_dir = "ArquivosX/";
$target_file = $target_dir . basename($_FILES["FILE"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Verifica o tamanho do arquivo
if ($_FILES["FILE"]["size"] > $maxFileSize) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "O arquivo excede o tamanho máximo permitido de 100 MB.",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}

// Verifica a extensão do arquivo
if (!in_array($imageFileType, $allowedExtensions)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Formato de arquivo não permitido. Apenas arquivos .txt e .csv são aceitos.",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}

// Check Virus
$arquivo = array(
    'CAMINHO_TMP' => $_FILES["FILE"]["tmp_name"],
    'CONADM' => ''
);
$retorno = fnScan($arquivo);
if ($retorno['RESULTADO'] == 0) {
    // Continuar o processamento do arquivo, se necessário
} else {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Arquivo infectado por: ' . $retorno['MSG'] . '",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}
$dadoscliven=array();

// Move o arquivo enviado para o diretório alvo
if (move_uploaded_file($_FILES["FILE"]["tmp_name"], $target_file)) {
    if (($file = fopen($target_file, "r")) !== FALSE) {
        // Ignorar a primeira linha
        fgetcsv($file, 24000, ";", '"', "\r\n");

        // Processar as linhas restantes
        while (($linha = fgetcsv($file, 24000, ";", '"', "\r\n")) !== FALSE) {
            
            if($linha['6'] >= '1' )
            {    
                //verifica se a venda ja esta no marka pelo numero da nota
                $venda="SELECT * FROM vendas WHERE cod_empresa=".$arraydadosaut['4']." AND cod_cupom='".$linha['5']."' AND cod_univend IN (SELECT COD_UNIVEND FROM unidadevenda WHERE cod_empresa=".$arraydadosaut['4']." and  REPLACE(REPLACE(REPLACE(REPLACE(num_cgcecpf, '.', ''), '/', ''), '-', ''), ' ', '')='".$linha['2']."')";
                $dadosvendaresult=mysqli_query($conexaotmp,$venda);
                if($dadosvendaresult->num_rows <='0')
                {   
                    $dadoscliven[$linha["2"]][$linha["10"]][$linha[0]]=array(    $linha[0],
                                                                                $linha[1],
                                                                                $linha[2],
                                                                                $linha[3],
                                                                                $linha[4],
                                                                                $linha[5],
                                                                                $linha[6],
                                                                                floatval(str_replace(',', '.', $linha[7])),
                                                                                floatval(str_replace(',', '.', $linha[8])) / $linha[6],
                                                                                preg_replace('/^-/', '', floatval(str_replace(',', '.', $linha[9]))) ,
                                                                                $linha[10],
                                                                                $linha[11],
                                                                                $linha[12],
                                                                                $linha[13]
                                                                            );
                }
            }
       }
       fclose($file);
    } else {
        http_response_code(400);
        $erroinformation = '{"errors": [
                                        {
                                         "message": "Não foi possível abrir o arquivo.",
                                         "coderro": "400"
                                         }
                                    ]
                       }';    
        echo $erroinformation;
        exit();  
    }
} else {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Falha ao mover o arquivo enviado.",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    echo $erroinformation;
    exit();  
}
//montar as partes do xml
foreach($dadoscliven as $key => $dados)
{   
    
    $unidade=array();
    $sqlunidade="SELECT COD_UNIVEND,num_cgcecpf FROM unidadevenda WHERE cod_empresa=".$arraydadosaut['4']." and  REPLACE(REPLACE(REPLACE(REPLACE(num_cgcecpf, '.', ''), '/', ''), '-', ''), ' ', '')='".$key."'";
    $COD_UNIVEND=mysqli_fetch_assoc(mysqli_query($conexaotmp,$sqlunidade));    
    $unidade[]=$COD_UNIVEND['COD_UNIVEND'];
    if($COD_UNIVEND['COD_UNIVEND'] !='')
    {    
      
        foreach ($dados as $key0 => $dados0) {
            
            //verificar se o cliente ja esta cadastrado antes de enviar a venda
            $tokencad="SELECT DES_TOKEN,NUM_CELULAR FROM geratoken WHERE cod_empresa=".$arraydadosaut['4']." AND num_cgcecpf='".$key0."'"; 
            $rstokencad=mysqli_query($conexaotmp,$tokencad);
            if($rscli->num_rows >= 0)
            {    
                $rwtokencad=mysqli_fetch_assoc($rstokencad);
                $cli="select * from clientes where cod_empresa=".$arraydadosaut['4']." AND num_cgcecpf='".$key0."'";
                $rscli=mysqli_query($conexaotmp,$cli);
                if($rscli->num_rows <= 0)
                {
                    //verificar os dados na com adm
                    $cpf="SELECT * from log_cpf WHERE CPF='".$key0."' LIMIT 1";
                    $rscpf=mysqli_query($connAdm->connAdm(),$cpf);
                    if($rscpf->num_rows >= 0)
                    {
                        $rwcpf=mysqli_fetch_assoc($rscpf);
                        $XMLCAD='<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                                <soap:Body>
                                  <AtualizaCadastro xmlns="fidelidade">
                                    <fase xmlns="">fase1</fase>
                                    <cliente xmlns="">
                                      <nome>'.$rwcpf['NOME'].'</nome>
                                      <cartao>'.$rwcpf['CPF'].'</cartao>
                                      <cpf>'.$rwcpf['CPF'].'</cpf>
                                      <sexo>'.$rwcpf['SEXO'].'</sexo>
                                      <datanascimento>'.$rwcpf['DT_NASCIMENTO'].'</datanascimento>
                                      <telcelular>'.$rstokencad['NUM_CELULAR'].'</telcelular>
                                      <tokencadastro>'.$rstokencad['DES_TOKEN'].'</tokencadastro>
                                      <canal>2</canal>
                                      <adesao>CT</adesao>
                                    </cliente>
                                    <dadoslogin>
                                        <login>'.$arraydadosaut[0].'</login>
                                        <senha>'.$arraydadosaut[1].'</senha>
                                        <idloja>'.$unidade['0'].'</idloja>
                                        <idcliente>'.$arraydadosaut[4].'</idcliente>
                                        <codvendedor>99999</codvendedor>
                                        <nomevendedor>ImportacaoManual</nomevendedor>
                                        <idmaquina>import</idmaquina>
                                </dadoslogin>
                                  </AtualizaCadastro>
                                </soap:Body>
                              </soap:Envelope>';
                       echo $XMLCAD;
                    }
                }    
            }
            
            
            foreach($dados0 as $key1 => $dados1){   

                        $itm.='<vendaitem>
                                    <id_item>'.$dados1[0].'</id_item>
                                    <produto>'.$dados1[1].'</produto>
                                    <codigoproduto>'.$dados1[0].'</codigoproduto>
                                    <quantidade>'.$dados1[6].'</quantidade>
                                    <valorbruto>'.number_format($dados1[7], 2, ',', '.').'</valorbruto>
                                    <descontovalor>'.number_format($dados1[9], 2, ',', '.').'</descontovalor>
                                    <valorliquido>'.number_format($dados1[8], 2, ',', '.').'</valorliquido>
                            </vendaitem>';
                          $soma +=$dados1[8] - $dados1[9]; 
                          $cupom=$dados1[5];
            }
                if($key0=='22222222222'){$cpf='0';}else{$cpf=$key0;}    
                    $xml='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                              xmlns:fid="fidelidade">
                                            <soapenv:Body>
                                                    <InsereVenda xmlns="Linker20">
                                                            <fase>fase6</fase>
                                                            <venda>
                                                                    <id_vendapdv>'.$arraydadosaut[4].'-'.$unidade['0'].'-'.microtime().'</id_vendapdv>
                                                                    <datahora>'.date('Y-m-d H:i:s').'</datahora>
                                                                    <cartao>'.$cpf.'</cartao>
                                                                    <valortotalbruto>'.number_format($soma, 2, ',', '.').'</valortotalbruto>
                                                                    <descontototalvalor>0.00</descontototalvalor>
                                                                    <valortotalliquido>'.number_format($soma, 2, ',', '.').'</valortotalliquido>
                                                                    <valor_resgate>0,00</valor_resgate>
                                                                    <cupomfiscal>'.$cupom.'</cupomfiscal>
                                                                    <cupomdesconto></cupomdesconto>
                                                                    <formapagamento>DINHEIRO</formapagamento>
                                                                    <codatendente>ImportacaoManual</codatendente>
                                                                    <codvendedor>99999</codvendedor>
                                                                    <itens>
                                                                            '.$itm.'
                                                                    </itens>
                                                            </venda>
                                                            <dadoslogin>
                                                                    <login>'.$arraydadosaut[0].'</login>
                                                                    <senha>'.$arraydadosaut[1].'</senha>
                                                                    <idloja>'.$unidade['0'].'</idloja>
                                                                    <idcliente>'.$arraydadosaut[4].'</idcliente>
                                                                    <codvendedor>99999</codvendedor>
                                                                    <nomevendedor>ImportacaoManual</nomevendedor>
                                                                    <idmaquina>import</idmaquina>
                                                            </dadoslogin>
                                                    </InsereVenda>
                                            </soapenv:Body>
                                    </soapenv:Envelope>';
            //=================================================
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => $xml,
                          CURLOPT_HTTPHEADER => array(
                                                        "Cache-Control: no-cache",
                                                        "Content-Type: text/xml",
                                                    ),
                        ));


                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        curl_close($curl);

                        if ($err) {
                          echo "cURL Error #:" . $err;
                        } else {
                           echo  $response;
                        }
                    
            unset($itm);
            unset($soma);
        }  
    }    
}





?>
