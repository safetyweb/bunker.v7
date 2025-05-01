<?php
//include './src/log/'
// Diretório onde os arquivos serão salvos
$dir = './src/log/';

// Verifica se o diretório existe e é gravável
if (!is_dir($dir) || !is_writable($dir)) {
    die("Diretório não existe ou não é gravável.");
}

// Recebe os dados da atualização de status da Twilio
$data = json_decode(file_get_contents('php://input'), true);

// Verifica se há dados recebidos
if (!empty($data)) {
    // Nome do arquivo onde os dados serão salvos (use algum formato de data/hora para evitar sobrescrever arquivos)
    $filename = $dir . 'status_' . date('Ymd_His') . '.json';

    // Salva os dados recebidos em um arquivo JSON
    file_put_contents($filename, json_encode($data));

    echo "Dados salvos com sucesso em: $filename";
} else {
    // Se não houver dados recebidos, retorna um erro
    http_response_code(400);
    echo 'Erro: Nenhum dado recebido.';
}