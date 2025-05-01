<?php
include '../../_system/_functionsMain.php';
$dir = '../../wsmarka/rot_sql/';

$retryLimit = 100; // Número máximo de tentativas de atualização
$retry = 0; // Contador de tentativas
$sleep=1;
$startTime = time(); // Tempo inicial
//
// Obtém a lista de arquivos no diretório usando a função glob
$arquivos = glob($dir . '*.sql');

// Verifica se foram encontrados arquivos
if (!empty($arquivos)) {
    // Itera sobre cada arquivo encontrado
    
    foreach ($arquivos as $arquivo) {
        // Abre o arquivo em modo de leitura
        $dados= explode('__',$arquivo);
        $dadosempresa=explode('/',$dados[0]);        
        $contemporaria= connTemp($dadosempresa[4], ''); 
      
        $handle = fopen($arquivo, 'r');
       unset($dadossqlexec);
        if ($handle) {
            // Lê o conteúdo do arquivo linha por linha usando fgets
            
           while (($linha = fgets($handle)) !== false) {
          
                //  $dadossqlexec[] = explode(';', $linha);
              // Executa as procedures em uma única chamada
               $dadossqlexec.= $linha;
              // echo PHP_EOL.$linha.PHP_EOL;
            }
           
            
            if (mysqli_multi_query($contemporaria, $dadossqlexec)) {
                do {
                    // Armazena o resultado atual ou avança para o próximo conjunto de resultados
                    if ($result = mysqli_store_result($contemporaria)) {
                        mysqli_free_result($result); // Libera a memória do conjunto de resultados
                    }
                } while (mysqli_next_result($contemporaria)); // Avança para o próximo conjunto de resultados, se houver
            } else {
                    // Se houve um erro ao executar as procedures
                    echo 'arquivo:<br>'.$arquivos.'<br>';
                    echo "Erro ao executar as procedures: " . mysqli_error($contemporaria).'<br>';
                    echo 'call:<br>'.$dadossqlexec.'<br>';
                    // Deleta o arquivo
                   if (unlink($arquivo)) {
                        echo "Arquivo deletado com sucesso: $arquivo<br><br>";
                    } else {
                        echo "Não foi possível deletar o arquivo: $arquivo<br><br>";
                    }
                  break;
            } 
              
            // Fecha o arquivo após a leitura
            fclose($handle);
         
            // Deleta o arquivo
           if (unlink($arquivo)) {
               echo "Arquivo deletado com sucesso: $arquivo<br><br>";
           } else {
               echo "Não foi possível deletar o arquivo: $arquivo<br><br>";
           }
        } else {
           // echo "Não foi possível abrir o arquivo: $arquivo<br>";
        }
    }
} else {
   // echo "Nenhum arquivo encontrado no diretório.";
}




