<?php

function generateUUID($lenght = 32)
{
    if (function_exists("random_bytes")) {
        $data = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $data = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }

    return substr(bin2hex($data), 0, $lenght);
}

function formatarData($data)
{
    // Verifica se a data está no formato "DD/MM/YYYY"
    if (strpos($data, '/') !== false) {
        // Converte para o formato "YYYY-MM-DD"
        $timestamp = strtotime($data);
        return date("Y-m-d", $timestamp);
    }
    // Se a data já estiver no formato "YYYY-MM-DD", retorna a data original
    return $data;
}
