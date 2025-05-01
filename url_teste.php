<?php
$server = $_SERVER['SERVER_NAME'];
        $endereco = $_SERVER ['REQUEST_URI'];
        $urlori=$server.$endereco;
        $result = str_replace($server, " ", $urlori);
    echo '<pre>';
    echo $result.'<br>';
    print_r($_SERVER);
     echo '</pre>';