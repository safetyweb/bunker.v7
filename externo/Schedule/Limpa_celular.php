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
   
   
   if (strlen($cellimpo) <= 11 && strlen($cellimpo)>=10){
       $cellimpo1 = substr($cellimpo, -8,1);
       if($cellimpo1 <= '3')
       {
           $cellimpo_err = 2;
       } 
       if(strlen($cellimpo) == 11)
       {    
                $cellimpo2 = substr($cellimpo, -9,1);
                if($cellimpo2 <= '8')
                {
                    $cellimpo_err = 2;
                }  
       }
  }else{
    $cellimpo_err = 2;
  }
  if (strlen($cellimpo) == 10) {
     $cellimpo=substr_replace($cellimpo, '9', 2, 0);
  }
    $celularcount = count_chars(substr($cellimpo, 2), 1);
    foreach ($celularcount as $key => $value) {
        if($value >= 8){
            $cellimpo_err = 2;
        }  
    }

    return array('cel' => $cellimpo, 'erro' => $cellimpo_err);
}

// Definição das variáveis necessárias
$batch_size = 100; // Tamanho do lote
$offset = 0; // Offset inicial

do {
    $sqlcelular = "SELECT * FROM telefone_carlos WHERE cod_scan = 0 LIMIT $batch_size OFFSET $offset";   
    $rw = mysqli_query($contmp, $sqlcelular);

    // Verifica se a consulta retornou algum registro
    if (mysqli_num_rows($rw) == 0) {
        break; // Sai do loop se não houver registros
    }

    while ($rs = mysqli_fetch_assoc($rw)) {
        // Dividir os números pelo separador :::
        $num_celular = str_replace(' ', '', $rs['num_celular']);
        if (strpos($num_celular, ':::') !== false) {
            $celulares = explode(':::', $num_celular);

            foreach ($celulares as $celular) {
                $OK = fnlimpacelular_beta($celular);

                if ($OK['erro'] == 2) {
                    // Gravar erro
                    $update = "UPDATE telefone_carlos SET cel_scan='" . $OK['cel'] . "', cod_erro=2, cod_scan=1 WHERE ID=" . $rs['ID'];
                    mysqli_query($contmp, $update);
                } else {
                    // Inserir novo registro para celular válido com separador
                    $insert = "INSERT INTO telefone_carlos (nom_cliente, num_celular, cel_scan, cod_erro, cod_scan)
                               VALUES ('" . $rs['nom_cliente'] . "', '" . $celular . "', '" . $OK['cel'] . "', 1, 1)";
                    mysqli_query($contmp, $insert);
                }
            }
        } else {
            $OK = fnlimpacelular_beta($rs['num_celular']);

            if ($OK['erro'] == 2) {
                // Gravar erro
                $update = "UPDATE telefone_carlos SET cel_scan='" . $OK['cel'] . "', cod_erro=2, cod_scan=1 WHERE ID=" . $rs['ID'];
                mysqli_query($contmp, $update);
            } else {
                // Gravar OK para celular sem ':::'
                $update = "UPDATE telefone_carlos SET cel_scan='" . $OK['cel'] . "', cod_erro=1, cod_scan=1 WHERE ID=" . $rs['ID'];
                mysqli_query($contmp, $update);
            }
        }
    }

    // Incrementa o offset para pegar o próximo lote
    $offset += $batch_size;
} while (true); // Continua até que a condição de parada seja satisfeita

echo 'OK';
