<?php
require '../_system/_functionsMain.php';
$cod_empresa = 77;
$contemporaria = connTemp($cod_empresa, '');

// Definição das variáveis necessárias
$batch_size = 100000; // Exemplo de tamanho do lote
$offset = 0; // Exemplo de offset inicial

// Array para contabilizar os resultados
$contabilizacao = [
    'TP_FISICA' => 0,
    'TP_JURIDICA' => 0,
    'MASC' => 0,
    'FEM' => 0,
    'Indefinido' => 0,
    'Total' => 0,
];
// Captura o tempo de início
$start_time = microtime(true);
do {
    // Consulta SQL para selecionar registros
    $listapapelhigienico = "SELECT 
                                CASE WHEN TIP_CLIENTE = 'F' THEN 1 ELSE 0 END TP_FISICA,
                                CASE WHEN TIP_CLIENTE = 'J' THEN 1 ELSE 0 END TP_JURIDICA, 
                                CASE WHEN COD_SEXOPES = '1' THEN 1 ELSE 0 END MASC,
                                CASE WHEN COD_SEXOPES = '2' THEN 1 ELSE 0 END FEM,
                                CASE WHEN COD_SEXOPES = '3' THEN 1 ELSE 0 END Indefinido
                            FROM clientes 
                            WHERE cod_empresa = $cod_empresa
                            LIMIT $batch_size OFFSET $offset";
    
    // Executa a consulta
    $rwlistapapelhigienico = mysqli_query($contemporaria, $listapapelhigienico);

    if ($rwlistapapelhigienico && mysqli_num_rows($rwlistapapelhigienico) > 0) {
        // Processa cada linha retornada pela consulta
        while ($rshigienico = mysqli_fetch_assoc($rwlistapapelhigienico)) {
            // Contabiliza os valores de cada campo no array
            $contabilizacao['TP_FISICA'] += intval($rshigienico['TP_FISICA']);
            $contabilizacao['TP_JURIDICA'] += intval($rshigienico['TP_JURIDICA']);
            $contabilizacao['MASC'] += intval($rshigienico['MASC']);
            $contabilizacao['FEM'] += intval($rshigienico['FEM']);
            $contabilizacao['Indefinido'] += intval($rshigienico['Indefinido']);
            // Incrementa o total de registros processados
            $contabilizacao['Total']++;
        }
    }

    // Incrementa o offset para pegar o próximo lote
    $offset += $batch_size;

} while (mysqli_num_rows($rwlistapapelhigienico) > 0); // Continua enquanto houver registros retornados
// Captura o tempo de término
$end_time = microtime(true);

// Calcula a diferença de tempo (em segundos)
$execution_time = $end_time - $start_time;

// Exibir o resultado final da contabilização
echo "<pre>";
print_r($contabilizacao);
echo "</pre>";

// Exibir o tempo de execução
echo "Tempo de execução: " . $execution_time . " segundos\n";
?>
