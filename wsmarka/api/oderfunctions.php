<?php
function fnScan($arquivo)
{
    //testando o antivirus
    $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    if (socket_connect($socket, '/var/run/clamav/clamd-socket')) {
        socket_send($socket, "PING", strlen($arquivo['CAMINHO_TMP']) + 5, 0);
        socket_recv($socket, $PING, 20000, 0);
        socket_close($socket);
        if (rtrim(trim($PING)) == 'PONG') {
            chmod($arquivo['CAMINHO_TMP'], 00644);
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            if (socket_connect($socket, '/var/run/clamav/clamd-socket')) {
                $result = "";
                socket_send($socket, "SCAN " . $arquivo['CAMINHO_TMP'], strlen($arquivo['CAMINHO_TMP']) + 5, 0);
                socket_recv($socket, $result, 20000, 0);
                $quebradelina = explode(':', $result);

                if (rtrim(trim($quebradelina['1'])) == 'OK') {
                    return array(
                        'RESULTADO' => 0,
                        'MSG' => 'N'
                    );
                } else {
                    return array(
                        'RESULTADO' => 1,
                        'MSG' => $quebradelina['1']
                    );
                    unlink($arquivo['CAMINHO_TMP']);
                }
            }
            socket_close($socket);
        }
    }
}
function fnantinject($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|into|table|update|where|drop table|show tables|#|\*|--|\\\\)/i", "", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}
