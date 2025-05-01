<?php
$JSONRETORNO=file_get_contents("php://input");

//salvar em arquivo
// Decodifica o JSON para um array associativo
$json_array = json_decode($JSONRETORNO, true);

// Caminho do arquivo

$arquivo = '/srv/www/htdocs/externo/vps2d/retorno/'. uniqid() . '_arquivo.json';
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