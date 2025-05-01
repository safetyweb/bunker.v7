<?php
function FncreateToken($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'apikey: ' . $apikey . ''
            ],
            'content' => '{
                                        "instanceName": "' . $instanceName . '",
                                        "qrcode": true
                                    }',
        ],
    ];

    $context = stream_context_create($options);

    $handle = fopen("$port/instance/create", "r", false, $context);

    if (! is_resource($handle)) {
        echo 'sorry';
        exit;
    }

    while (!feof($handle)) {
        @$contents .= fread($handle, 8192);
    }
    fclose($handle);
    $connect = json_decode($contents, true);
    return $connect;
}
/*$resultcreate=FncreateToken('marcelo010','0wtHuA4ABQTlKxbKXdebeWjQSaAPwM9A');
echo '<pre>';
print_r($resultcreate);
echo '</pre>';
 * 
 */
//"token": "'.$token.'",
function Fncreate($instanceName, $apikey, $cel, $integration = 'WHATSAPP-BAILEYS', $port = 'https://api1.webbix.com.br')
{

    $https = $port . '/instance/create';
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $https,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                                "instanceName": "' . $instanceName . '",
                                "token": "", 
                                "qrcode": true,
                                "mobile": false,
                                "number": "' . $cel . '", 
                                "integration": "' . $integration . '" 
                            }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'apikey:  ' . $apikey
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $connect = json_decode($response, true);

    /*
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$port/instance/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 360,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                                "instanceName": "' . $instanceName . '",
                                "token": "", 
                                "qrcode": true,
                                "mobile": false,
                                "number": "' . $cel . '", 
                                "integration": "' . $integration . '" 
                            }',
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
    ));

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Verifica se ocorreu algum erro com o cURL
    if ($curlError) {
        return array('error' => true, 'message' => 'cURL Error: ' . $curlError);
    }

    // Verifica se o código HTTP indica um erro
    if ($httpCode >= 400) {
        return array('error' => true, 'message' => 'HTTP Error: ' . $httpCode, 'response' => $response);
    }

    // Tenta decodificar a resposta JSON
    $connect = json_decode($response, true);

    // Verifica se a decodificação do JSON foi bem-sucedida
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array('error' => true, 'message' => 'JSON Decode Error: ' . json_last_error_msg(), 'response' => $response);
    }*/

    // Retorna a resposta decodificada se não houver erros
    return $connect;
}
/*$resultcreate=Fncreate('marcelo01','0wtHuA4ABQTlKxbKXdebeWjQSaAPwM9A','87F3F7D0-4B8A-45D0-8618-7399E4AD6469');
echo '<pre>';
print_r($resultcreate);
echo '</pre>';
*/

function Fnlogout($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'apikey: ' . $apikey . ''
            ],
        ],
    ];
    $context = stream_context_create($options);
    $handle = fopen("$port/instance/logout/$instanceName", "r", false, $context);

    if (! is_resource($handle)) {
        echo 'sorry';
        exit;
    }

    while (!feof($handle)) {
        @$contents .= fread($handle, 8192);
    }
    fclose($handle);
    $connect = json_decode($contents, true);
    return $connect;
}
/*$resultcreate=Fnlogout($instanceName,$apikey);
echo '<pre>';
print_r($resultcreate);
echo '</pre>';
*/

function Fndelete($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{
    // Construção do comando cURL com redirecionamento de erro
    $command = "curl --location --request DELETE '$port/instance/logout/$instanceName' --header 'apikey: $apikey' -k -v 2>&1";

    // Executa o comando cURL
    $output = shell_exec($command);

    // Verifica se houve algum erro ao executar o comando
    if ($output === null) {
        echo "Erro ao executar o comando cURL.";
        exit;
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$port/instance/delete/" . $instanceName,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
    ));

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Verifica se ocorreu algum erro com o cURL
    if ($curlError) {
        return array('error' => true, 'message' => 'cURL Error: ' . $curlError);
    }

    // Verifica se o código HTTP indica um erro
    if ($httpCode >= 400) {
        return array('error' => true, 'message' => 'HTTP Error: ' . $httpCode, 'response' => $response);
    }

    // Tenta decodificar a resposta JSON
    $connect = json_decode($response, true);

    // Verifica se a decodificação do JSON foi bem-sucedida
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array('error' => true, 'message' => 'JSON Decode Error: ' . json_last_error_msg(), 'response' => $response);
    }

    // Retorna a resposta decodificada se não houver erros
    return $connect;
}

//Fndelete($instanceName,$apikey)

function FnsendText($instanceName, $apikey, $number, $text, $delay = 20, $port = 'https://api1.webbix.com.br')
{


    $proxyHost = "http://p.webshare.io";
    $proxyPort = "80";
    $proxyUsername = "ihakjfrv-BR-rotate";
    $proxyPassword = "haqrvntza9gf";
    // Configura os dados para envio
    $postDataArray = array(
        'number' => $number,
        'textMessage' => array(
            'text' => $text
        ),
        'options' => array(
            'delay' => $delay,
            'presence' => 'composing',
            'linkPreview' => true
        )
    );
    $postData = json_encode($postDataArray);

    // Inicializa o cURL
    $ch = curl_init();

    // Configurações da requisição cURL
    curl_setopt($ch, CURLOPT_URL, "$port/message/sendText/$instanceName");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    // Configuração do proxy
    curl_setopt($ch, CURLOPT_PROXY, $proxyHost);
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);

    // Envia as credenciais de autenticação do proxy
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxyUsername:$proxyPassword");

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // Ignora verificação do host SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Ignora verificação do certificado SSL

    // Cabeçalhos da requisição
    $headers = [
        'Content-Type: application/json; charset=UTF-8',
        'apikey: ' . $apikey
    ];

    // Definindo os headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Executa a requisição
    $response = curl_exec($ch);

    // Verifica erros
    if (curl_errno($ch)) {
        echo 'Erro: ' . curl_error($ch);
    }

    // Fecha o cURL
    curl_close($ch);

    // Decodifica a resposta JSON
    $connect = json_decode($response, true);

    // Retorna a resposta decodificada
    return $connect;
}
/*
$resultcreate=FnsendText('marcelo01','0wtHuA4ABQTlKxbKXdebeWjQSaAPwM9A','15988034772','olá amiguinho','3');
echo '<pre>';
print_r($resultcreate);
echo '</pre>';
*/

function sendMedia($instanceName, $apikey, $number, $delay = 3, $mediatype, $fileName, $caption, $media, $port = 'https://api1.webbix.com.br')
{
    $proxyHost = "http://p.webshare.io";
    $proxyPort = "80";
    $proxyUsername = "ihakjfrv-BR-rotate";
    $proxyPassword = "haqrvntza9gf";
    // Configura os dados para enviO
    /* $postData = '{
                    "number": "' . $number . '",
                    "mediaMessage": {
                        "mediatype": "' . $mediatype . '",
                        "fileName": "' . $fileName . '",
                        "caption": "' . $caption . '",
                        "media": "' . $media . '"
                    },
                    "options": {
                        "delay": ' . $delay . ',
                        "presence": "composing",
                        "linkPreview": true
                    }
                }';

    $jsonDecoded = json_decode($postData, true);
    if (json_last_error() === JSON_ERROR_NONE) {
    } else {
        echo "JSON inválido: " . json_last_error_msg();
        echo $postData;
    }*/

    // Escapa somente a variável $caption
    // $escapedCaption = json_encode($caption, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Gera o JSON com a variável $caption escapada
    $postData = json_encode([
        "number" => $number,
        "mediaMessage" => [
            "mediatype" => $mediatype,
            "fileName" => $fileName,
            "caption" => $caption,  // Usa o valor escapado
            "media" => $media
        ],
        "options" => [
            "delay" => $delay,
            "presence" => "composing",
            "linkPreview" => true
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Decodifica o JSON para verificar a validade
    $jsonDecoded = json_decode($postData, true);

    // Verifica se o JSON é válido
    if (is_array($jsonDecoded) && json_last_error() === JSON_ERROR_NONE) {
        // JSON válido
    } else {
        echo "JSON inválido: " . json_last_error_msg();
        echo $postData;
    }

    // Inicializa o cURL
    $ch = curl_init();

    // Configurações da requisição cURL
    curl_setopt($ch, CURLOPT_URL, "$port/message/sendMedia/$instanceName");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    // Configuração do proxy
    curl_setopt($ch, CURLOPT_PROXY, $proxyHost);
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);

    // Envia as credenciais de autenticação do proxy
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxyUsername:$proxyPassword");

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // Ignora verificação do host SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Ignora verificação do certificado SSL

    // Cabeçalhos da requisição
    $headers = [
        'Content-Type: application/json; charset=UTF-8',
        'apikey: ' . $apikey
    ];

    // Definindo os headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Executa a requisição
    $response = curl_exec($ch);

    // Verifica erros
    if (curl_errno($ch)) {
        echo 'Erro: ' . curl_error($ch);
    }

    // Fecha o cURL
    curl_close($ch);

    // Decodifica a resposta JSON
    $connect = json_decode($response, true);

    // Retorna a resposta decodificada
    return $connect;
}
/*$resultcreate=sendMedia('marcelo01','0wtHuA4ABQTlKxbKXdebeWjQSaAPwM9A','15988034772',3,'image','ME2AxRRoygUyFPCDe0jQ/3.png','imagem teste','https://s2-techtudo.glbimg.com/JsE244mucjKWLYtNgeiDyfVYlJQ=/0x129:1024x952/888x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_08fbf48bc0524877943fe86e43087e7a/internal_photos/bs/2023/7/i/ME2AxRRoygUyFPCDe0jQ/3.png');

echo '<pre>';
print_r($resultcreate);
echo '</pre>';
 * 
 */
function fnconnectionState($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "$port/instance/connectionState/" . $instanceName,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
    ));

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Verifica se ocorreu algum erro com o cURL
    if ($curlError) {
        return array('error' => true, 'message' => 'cURL Error: ' . $curlError);
    }

    // Verifica se o código HTTP indica um erro
    if ($httpCode >= 400) {
        return array('error' => true, 'message' => 'HTTP Error: ' . $httpCode, 'response' => $response);
    }

    // Tenta decodificar a resposta JSON
    $connect = json_decode($response, true);

    // Verifica se a decodificação do JSON foi bem-sucedida
    if (json_last_error() !== JSON_ERROR_NONE) {
        return array('error' => true, 'message' => 'JSON Decode Error: ' . json_last_error_msg(), 'response' => $response);
    }

    // Retorna a resposta decodificada se não houver erros
    return $connect;
}

/*$resultcreate=fnconnectionState('7_9999','0wtHuA4ABQTlKxbKXdebeWjQSaAPwM9A','https://api1.webbix.com.br');

echo '<pre>';
print_r($resultcreate);
echo '</pre>';
*/
function fnSTATUSDEVICES($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "$port/instance/fetchInstances?instanceName=" . $instanceName,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey . ''
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL  
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $connect = json_decode($response, true);
    return $connect;
}

function fnReconect($instanceName, $apikey, $port = 'https://api1.webbix.com.br')
{

    // Construção do comando cURL com redirecionamento de erro
    $command = "curl --location --request DELETE '$port/instance/logout/$instanceName' --header 'apikey: $apikey' -k -v 2>&1";

    // Executa o comando cURL
    $output = shell_exec($command);

    // Verifica se houve algum erro ao executar o comando
    if ($output === null) {
        echo "Erro ao executar o comando cURL.";
        exit;
    }
    ////////==============================================================================
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "$port/instance/connect/" . $instanceName,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'apikey: ' . $apikey
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        // Capture curl errors
        $error_msg = curl_error($curl);
        curl_close($curl);
        return array('error' => true, 'message' => 'Curl error: ' . $error_msg);
    }

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $connect = json_decode($response, true);

    if ($http_code >= 400) {
        // Handle HTTP errors
        return array('error' => true, 'http_code' => $http_code, 'response' => $connect);
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decode errors
        return array('error' => true, 'message' => 'JSON decode error: ' . json_last_error_msg(), 'response' => $response);
    }

    return $connect;
    //====================================================================================  
}

function fnPROXY($instanceName, $host, $port1, $protocolo, $user, $pass, $apikey, $port = 'https://api1.webbix.com.br')
{
    $url = "$port/proxy/set/" . $instanceName;

    $data = array(
        "enabled" => true,
        "proxy" => array(
            "host" => $host,
            "port" => $port1,
            "protocol" => $protocolo,
            "username" => $user,
            "password" => $pass
        )
    );

    $data_string = json_encode($data);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'apikey: ' . $apikey
        ),
        CURLOPT_SSL_VERIFYHOST => false, // Ignora a verificação do host SSL
        CURLOPT_SSL_VERIFYPEER => false, // Ignora a verificação do certificado SSL
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        // Capture curl errors
        $error_msg = curl_error($curl);
        curl_close($curl);
        return array('error' => true, 'message' => 'Curl error: ' . $error_msg);
    }

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $connect = json_decode($response, true);

    if ($http_code >= 400) {
        // Handle HTTP errors
        return array('error' => true, 'http_code' => $http_code, 'response' => $connect);
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Handle JSON decode errors
        return array('error' => true, 'message' => 'JSON decode error: ' . json_last_error_msg(), 'response' => $response);
    }

    return $connect;
}
