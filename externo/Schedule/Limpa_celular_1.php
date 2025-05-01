<?php
require '../../_system/_functionsMain.php';
$contmp = connTemp('502', '');  
function fnlimpacelular_beta($celular=false)
{
    $cellimpo=preg_replace('/[^0-9]/', '', $celular);
   
    $ddi = strripos($cellimpo, '55');
    $val_ddi = strlen($ddi);
    if ($val_ddi >= 1) {
          if (strlen($cellimpo)>=13){
                $cellimpo=Ltrim($cellimpo,'55');
          }
    }
    $cellimpo=Ltrim($cellimpo,'0');
 
    return array('cel' => $cellimpo, 'erro' => $cellimpo_err);
}

// Definição das variáveis necessárias
$batch_size = 1000; // Tamanho do lote
$offset = 0; // Offset inicial

do {
    $sqlcelular = "SELECT NUM_TELEFON,NUM_CELULAR,COD_CLIENTE FROM clientes WHERE cod_empresa=502 LIMIT $batch_size OFFSET $offset";   
    $rw = mysqli_query($contmp, $sqlcelular);

    // Verifica se a consulta retornou algum registro
    if (mysqli_num_rows($rw) == 0) {
        break; // Sai do loop se não houver registros
    }

    while ($rs = mysqli_fetch_assoc($rw)) {
        ob_start();
                $OK = fnlimpacelular_beta($rs['NUM_TELEFON']);
                // Gravar erro
                $update = "UPDATE clientes SET NUM_TELEFON='" . $OK['cel'] . "' WHERE COD_CLIENTE=" . $rs['COD_CLIENTE'].";";
                echo $update.'<br>';
                mysqli_query($contmp, $update);
        ob_end_flush();
        ob_flush();
        flush();
    }

    // Incrementa o offset para pegar o próximo lote
    $offset += $batch_size;
} while (true); // Continua até que a condição de parada seja satisfeita

echo 'OK';