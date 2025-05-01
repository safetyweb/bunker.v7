<?php
$host = '127.0.0.1';
$port = 12345;

// Cria um socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    die('Could not create socket: ' . socket_strerror(socket_last_error()));
}

// Liga o socket ao endereço e porta
$result = socket_bind($socket, $host, $port);
if ($result === false) {
    die('Could not bind to socket: ' . socket_strerror(socket_last_error($socket)));
}

// Começa a ouvir no socket
$result = socket_listen($socket, 5);
if ($result === false) {
    die('Could not listen on socket: ' . socket_strerror(socket_last_error($socket)));
}

while (true) {
    // Aceita uma conexão de entrada
    $client = socket_accept($socket);
    if ($client === false) {
        echo 'Could not accept connection: ' . socket_strerror(socket_last_error($socket)) . "\n";
        continue;
    }

    // Lê dados do cliente
    $input = socket_read($client, 2048);
    if ($input === false) {
        echo 'Could not read input: ' . socket_strerror(socket_last_error($socket)) . "\n";
        socket_close($client);
        continue;
    }

    $json_array = json_decode($input, true);
    if ($json_array === null && json_last_error() !== JSON_ERROR_NONE) {
        echo 'Failed to decode JSON: ' . json_last_error_msg() . "\n";
        socket_close($client);
        continue;
    }

    // Caminho do arquivo
    $arquivo = './retorno/' . $json_array['correlationId'] . '_' . uniqid() . '_arquivo.json';

    if (file_exists($arquivo)) {
        file_put_contents($arquivo, PHP_EOL . $input, FILE_APPEND);
    } else {
        file_put_contents($arquivo, $input);
    }

    echo ' [x] Processed ', $input, "\n";

    // Fecha a conexão com o cliente
    socket_close($client);
}

// Fecha o socket principal
socket_close($socket);
