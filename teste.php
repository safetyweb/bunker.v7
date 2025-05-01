 <meta charset="UTF-8">
<?php
// Função para executar um comando no shell e retornar a saída
/*function execCommand($command) {
    $output = [];
    $returnValue = 0;
    exec($command, $output, $returnValue);
    return [
        'output' => $output,
        'return_value' => $returnValue
    ];
}

// Comando para listar processos zumbis
$commandListZombies = "ps aux | grep 'Z' | awk '{print $2}'";
$result = execCommand($commandListZombies);

if (!empty($result['output'])) {
    echo "Processos zumbis encontrados:<br>";
    foreach ($result['output'] as $pid) {
        echo "PID: $pid <br>";
        
        // Tenta matar o processo zumbi
        execCommand("kill -9 $pid");
        echo "Tentando matar o processo PID: $pid <br>";
    }
} else {
    echo "Nenhum processo zumbi encontrado. <br>";
}
exit();*/
//phpinfo();
// Verifica se a extensão Memcached está carregada

/*if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    echo "<pre>";
    print_r($status);
    echo "</pre>";
} else {
    echo "OPcache não está habilitado.";
}*/



include_once './_system/_functionsMain.php';

//echo fnEncode('opws.gbs;marka;97845;webhook;109').'<br>';
echo fnEncode('!8=GpbNq5uhG0V').'<br>';
echo fnDecode('B2krMU06pu00a3GUu00a2').'<br>';
               
echo '<br>aqui2:: '. base64_decode('VVNOMFZHeldxMkVJR1JCWmZkNVpxNGZFM2tjZUZacWhyaVNnNHVuNUhSOEk1eFJPTEFqdHZRwqLCog==').'<br>';

fnEscreve2(fnDecode('QhHM3Pjvmlfw8ItzWus£oVA¢¢'));

 /*<dadoslogin>
	<login>ws.macab</login>
	<senha>macabu@mrk</senha>
	<idloja>98048</idloja>
	<idmaquina></idmaquina>
	<idcliente>391</idcliente>*/


$senha='ws.farmaseven;S1gm@Mk;98080;webhook;444';



$autoriz= base64_encode(fnEncode($senha));
echo '<br>nova chavea<br>'.$autoriz;

exit();


ECHO '<br> aqui<br>';
$autoriz= base64_decode('Y3pBYU5ybjhFck1YNXFpWWpPelNvYm1WWHBZbEhrMEJKN3PCo3dEODVpN0M5QzZkZXRvUTVXdUHCosKi');
echo $autoriz;
ECHO '<br>';

$arraydadosaut=explode(';',$autoriz);
echo '<pre>';
print_r($arraydadosaut);
echo '</pre>';
