<?php
function fnscanV($dadosstrig)
{

    foreach ($_REQUEST as $key => $value) {
    
        $descriptors = array(
                                0 => array('pipe', 'r'), // Descritor de arquivo para a entrada
                                1 => array('pipe', 'w'), // Descritor de arquivo para a saída
                                2 => array('pipe', 'w'), // Descritor de arquivo para a saída de erro
                            );

        $process = proc_open('clamdscan --fdpass -', $descriptors, $pipes);

        if (is_resource($process)) {
            // Envia o texto para o descritor de arquivo de entrada
            fwrite($pipes[0], $value);
            fclose($pipes[0]);

            // Lê a saída do descritor de arquivo de saída
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Lê a saída de erro do descritor de arquivo de saída de erro
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Obtém o código de status do processo
            $status = proc_close($process);

            // Exibe a saída e o erro (se houver)
           if($status==1)
           {     
               return [$output,$error,$status];
           }
        }
   }
}

$teste=fnscanV($dadosstrig);

echo '<pre>';
print_r($teste);
echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulário de Contato</title>
</head>
<body>
    <h1>Formulário de Contato</h1>
    <form action="teste_antvirus.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="mensagem">Mensagem:</label><br>
        <textarea id="mensagem" name="mensagem" rows="5" required></textarea><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>
</html>