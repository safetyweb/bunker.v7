<?php
// Define os cabeçalhos para saída em CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="relatorio_tabelas.csv"');

// Abre o fluxo de saída (STDOUT)
$output = fopen('php://output', 'w');

// Escreve a linha de cabeçalho do CSV com delimitador ";"
// Colunas:
// 1. Empresa
// 2. Banco de Dados
// 3. Tabela
// 4. Registros (Total)
// 5. Registros Específicos
// 6. Tamanho Formatado
// 7. Tamanho (MB)
// 8. Consumo Disco (MB)
fputcsv($output, [
    'Empresa',
    'Banco de Dados',
    'Tabela',
    'Registros (Total)',
    'Registros Específicos',
    'Tamanho Formatado',
    'Tamanho (MB)',
    'Consumo Disco (MB)'
], ';');

// Inclui as funções principais e estabelece a conexão administrativa
include '../../_system/_functionsMain.php';
$admc = $connAdm->connAdm();

// Consulta para obter as empresas e os bancos de dados associados (agrupando códigos de empresa)
$query = "SELECT GROUP_CONCAT(DISTINCT e.COD_EMPRESA SEPARATOR ',') AS COD_EMPRESA, t.NOM_DATABASE
          FROM empresas e
          INNER JOIN tab_database t ON e.COD_EMPRESA = t.COD_EMPRESA
          WHERE e.LOG_ATIVO='N'
          GROUP BY t.NOM_DATABASE";
$result = mysqli_query($admc, $query);
if (!$result) {
    die("Erro ao consultar empresas: " . mysqli_error($admc));
}

$companies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $companies[] = [
        'cod_empresa'  => $row['COD_EMPRESA'],  // Ex: "1,2,3"
        'nom_database' => $row['NOM_DATABASE']
    ];
}
mysqli_free_result($result);

// Variáveis para acumular os totais globais
$grandTotalRegistrosEspec = 0;     // Soma da coluna 5 (Registros Específicos)
$grandTotalConsumoMB      = 0;     // Soma da coluna 8 (Consumo Disco (MB))
$grandTotalTamanhoMBAll   = 0;     // Soma da coluna 7 (Tamanho (MB)) de todas as tabelas
$totalLinhasExibidas      = 0;

if (empty($companies)) {
    fputcsv($output, ['Nenhuma empresa encontrada!'], ';');
} else {
    foreach ($companies as $company) {
        $cod_empresa  = $company['cod_empresa']; // ex: "1,2,3"
        $nom_database = $company['nom_database'];

        // Tenta obter a conexão temporária até 3 vezes, aguardando 3 segundos entre cada tentativa
        $contmp = false;
        $max_attempts = 3;
        $attempt = 0;
        while ($attempt < $max_attempts && !$contmp) {
            $contmp = connTemp($cod_empresa, '');
            if (!$contmp) {
                $attempt++;
                if ($attempt < $max_attempts) {
                    sleep(3);
                }
            }
        }

        if (!$contmp) {
            $error = mysqli_connect_error();
            if (strpos($error, "Unknown database") !== false) {
                $message = "Banco de dados '$nom_database' não encontrado. Ignorando.";
            } elseif (stripos($error, "timed out") !== false) {
                $message = "Erro na conexão: Connection timed out. Seguindo para a próxima base de dados. (Tentativas: $attempt)";
            } else {
                $message = "Erro na conexão: " . $error . " (Tentativas: $attempt)";
            }
            // Escreve uma linha de erro e continua para a próxima empresa
            fputcsv($output, [$cod_empresa, $nom_database, $message, '', '', '', '', ''], ';');
            continue;
        }

        // Consulta as informações das tabelas (information_schema)
        $query_tables = "
            SELECT 
                TABLE_NAME,
                IFNULL(TABLE_ROWS, 0) AS total_registros,
                IFNULL(DATA_LENGTH, 0) AS data_length,
                IFNULL(INDEX_LENGTH, 0) AS index_length,
                ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2) AS tamanho_mb,
                CASE 
                    WHEN (DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024 >= 1024 
                    THEN CONCAT(ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024 / 1024, 2), ' GB')
                    ELSE CONCAT(ROUND((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024, 2), ' MB')
                END AS tamanho_formatado
            FROM information_schema.TABLES
            WHERE TABLE_SCHEMA = '$nom_database'
              AND TABLE_NAME NOT IN ('blocopesquisa', 'campanharegra', 'clientes_adilson', 'comunicacao', 'comunicacao_disparo')
            ORDER BY TABLE_NAME
        ";
        $result_tables = mysqli_query($contmp, $query_tables);
        if (!$result_tables) {
            fputcsv($output, [$cod_empresa, $nom_database, "Erro ao consultar tabelas: " . mysqli_error($contmp), '', '', '', '', ''], ';');
            mysqli_close($contmp);
            continue;
        }

        while ($table = mysqli_fetch_assoc($result_tables)) {
            $table_name     = $table['TABLE_NAME'];
            $total_registros = (float)$table['total_registros'];
            $tamanho_mb     = (float)$table['tamanho_mb']; // Tamanho total da tabela em MB

            // SELECT COUNT(*) para os registros específicos das empresas listadas em $cod_empresa
            $query_count = "SELECT COUNT(*) AS registros_especificos 
                            FROM `$nom_database`.`$table_name` 
                            WHERE cod_empresa IN ($cod_empresa)";
            $result_count = mysqli_query($contmp, $query_count);
            $count_registros = 0;
            if ($result_count) {
                $row_count = mysqli_fetch_assoc($result_count);
                $count_registros = (float)$row_count['registros_especificos'];
                mysqli_free_result($result_count);
            }

            // Cálculo do consumo de disco (MB) para os registros específicos
            $consumo_disco_mb = 0;
            if ($total_registros > 0) {
                $total_bytes = (float)$table['data_length'] + (float)$table['index_length'];
                $ratio = $count_registros / $total_registros;
                $consumo_disco_bytes = $ratio * $total_bytes;
                $consumo_disco_mb    = round($consumo_disco_bytes / 1024 / 1024, 2);
            }

            // Escreve a linha no CSV
            fputcsv($output, [
                $cod_empresa,
                $nom_database,
                $table_name,
                $total_registros,
                $count_registros,
                $table['tamanho_formatado'],
                $tamanho_mb,
                $consumo_disco_mb
            ], ';');

            // Acumula os totais
            $grandTotalRegistrosEspec += $count_registros;   // soma dos registros específicos (coluna 5)
            $grandTotalConsumoMB      += $consumo_disco_mb;  // soma do consumo de disco (coluna 8)
            $grandTotalTamanhoMBAll   += $tamanho_mb;        // soma do Tamanho (MB) (coluna 7)
            $totalLinhasExibidas++;
        }
        mysqli_free_result($result_tables);
        mysqli_close($contmp);
    }
}

// Se nenhuma linha foi exibida, zera os totais
if ($totalLinhasExibidas == 0) {
    $grandTotalRegistrosEspec = 0;
    $grandTotalConsumoMB      = 0;
    $grandTotalTamanhoMBAll   = 0;
}

// Agora, calculamos o "Tamanho Formatado" total, baseado no $grandTotalTamanhoMBAll
if ($grandTotalTamanhoMBAll >= 1024) {
    $grandTotalFormatadoAll = round($grandTotalTamanhoMBAll / 1024, 2) . ' GB';
} else {
    $grandTotalFormatadoAll = number_format($grandTotalTamanhoMBAll, 2) . ' MB';
}

// Linha final de totalização
// Preenchemos as colunas conforme desejado:
// 1. 'Total'
// 2. (vazio)
// 3. (vazio)
// 4. (vazio) -> Registros (Total) não está sendo somado
// 5. $grandTotalRegistrosEspec
// 6. $grandTotalFormatadoAll (Tamanho Formatado total)
// 7. $grandTotalTamanhoMBAll (Tamanho (MB) total)
// 8. $grandTotalConsumoMB (Consumo Disco total)
fputcsv($output, [
    'Total',
    '',
    '',
    '',
    number_format($grandTotalRegistrosEspec),
    $grandTotalFormatadoAll,
    number_format($grandTotalTamanhoMBAll, 2),
    number_format($grandTotalConsumoMB, 2)
], ';');

fclose($output);
exit;
