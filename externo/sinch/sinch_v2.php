<?php
$host = '127.0.0.1';
$port = 12345;

// Cria um socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    die('Could not create socket: ' . socket_strerror(socket_last_error()));
}

// Conecta ao servidor
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    die('Could not connect to socket: ' . socket_strerror(socket_last_error($socket)));
}

// Lê o JSON da solicitação HTTP
$JSONRETORNO = file_get_contents("php://input");
if ($JSONRETORNO === false) {
    die('Failed to read input');
}

// Envia o JSON para o socket
socket_write($socket, $JSONRETORNO, strlen($JSONRETORNO));

// Fecha o socket
socket_close($socket);

http_response_code(200);
echo 'Success';