<?php
// Caminho para o arquivo JSON de entrada

$json_file = './02.json';

// Arquivo CSV de saída
$csv_file = 'output.csv';

// Abre o arquivo CSV para escrita
$csv_handle = fopen($csv_file, 'w');

// Verifica se o arquivo CSV foi aberto com sucesso
if ($csv_handle === false) {
    die("Erro ao abrir o arquivo CSV para escrita.");
}

// Escreve o cabeçalho do CSV
fputcsv($csv_handle, array("to", "messaging_service_sid", "status", "num_segments", "date_sent", "price", "error_code", "from", "sid", "date_created", "tags", "body", "date_updated", "account_sid", "direction", "num_media"),';');

// Lê o conteúdo do arquivo JSON linha por linha
$lines = file($json_file);

// Itera sobre cada linha do JSON
foreach ($lines as $line) {
    // Decodifica a linha JSON em um array associativo
    $data = json_decode($line, true);
    
    // Escreve os dados no arquivo CSV
    fputcsv($csv_handle, $data, ';');
}

// Fecha o arquivo CSV
fclose($csv_handle);

echo "JSON convertido para CSV com sucesso. Nome do arquivo: $csv_file";