<?php
// Caminho para o diretório que contém os arquivos
include '../../_system/_functionsMain.php';
$dir = '/srv/www/htdocs/externo/twilo/COMANDINSERT/';


// Obtém a lista de arquivos no diretório usando a função glob
$arquivos = glob($dir . '*.txt');

// Verifica se foram encontrados arquivos
if (!empty($arquivos)) {
    // Itera sobre cada arquivo encontrado
    
    foreach ($arquivos as $arquivo) {
        // Abre o arquivo em modo de leitura
        $dados= explode('_',$arquivo);
        $dadosempresa=explode('||',base64_decode($dados[2]));        
        
        $contemporaria= connTemp($dadosempresa[1], '');  
        
        $handle = fopen($arquivo, 'r');
       
        if ($handle) {
            // Lê o conteúdo do arquivo linha por linha usando fgets
            while (($linha = fgets($handle)) !== false) {
                //  echo $linha; // Ou faça algo com o conteúdo da linha
                  
                $rw= mysqli_query($contemporaria, $linha);
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

