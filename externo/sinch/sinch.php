<?php
   // Lê o JSON da solicitação HTTP
$JSONRETORNO = file_get_contents("php://input");

// Decodifica o JSON para um array associativo
$json_array = json_decode($JSONRETORNO, true);

// Verifica se a decodificação foi bem-sucedida
if ($json_array === null && json_last_error() !== JSON_ERROR_NONE) {
    die('Failed to decode JSON: ' . json_last_error_msg());
}

// Caminho do arquivo
$arquivo = './retorno/' . $json_array['correlationId'] . '_' . $json_array['id'] . '_arquivo.json';

// Verifica se o arquivo existe
if (file_exists($arquivo)) {
    // Obtém o conteúdo atual do arquivo
    $conteudoAtual = file_get_contents($arquivo);
    // Acrescenta o novo conteúdo na última linha
    $novoConteudo = $conteudoAtual . PHP_EOL . $JSONRETORNO;

    // Escreve o novo conteúdo no arquivo
    file_put_contents($arquivo, $novoConteudo);
} else {
    // Cria o arquivo e escreve o conteúdo nele
    file_put_contents($arquivo, $JSONRETORNO);
}
