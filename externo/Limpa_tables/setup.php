<?php
// Define o cabeçalho para saída em texto simples
header("Content-Type: text/plain");

// Desabilita o buffer de saída e ativa o flush automático
ob_implicit_flush(true);
ini_set('output_buffering', 'off');

// Inclui as funções principais e estabelece a conexão administrativa
include '../../_system/_functionsMain.php';
$admc = $connAdm->connAdm();

// Consulta as empresas (ajuste o nome da tabela se necessário)
// Usando alias para facilitar o acesso
$query = "SELECT e.COD_EMPRESA AS cod_empresa FROM empresas e
INNER JOIN tab_database t ON e.COD_EMPRESA=t.COD_EMPRESA 
GROUP BY t.NOM_DATABASE";
$result = mysqli_query($admc, $query);
if (!$result) {
    die("Erro ao consultar empresas: " . mysqli_error($admc));
}

$companies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $companies[] = $row['cod_empresa'];
}
mysqli_free_result($result);

if (empty($companies)) {
    echo "Nenhuma empresa encontrada!\n";
    flush();
    exit;
}

// Lista das tabelas para exclusão via conexão temporária (por empresa)
$tables = [
    // Tabelas do grupo MSG
    "msg_atualizavenda",
    "msg_busca",
    "msg_cadastra",
    "msg_desconto",
    "msg_estornavenda",
    "msg_origembuscafidelizados",
    "msg_produto",
    "msg_venda",
    "msg_trocacartao",
    // Tabelas do grupo ORIGEM
    "origem_atualizavenda",
    "origembusca",
    "origembuscafidelizados",
    "origemcadastro",
    "origencadproduto",
    "origemdescontos",
    "origemdescontoitem",
    "origemestornavenda",
    "origemofertaproduto",
    "origemtokem",
    "origemtoken",
    "origemtrocacartao",
    "origemvalidatokem",
    "origemvenda",
    "origemvendedor",
    "log_tkt",
    "email_fila",
    "sms_lista_ret",
    "email_lista_ret",
    "push_lista_ret",
    "log_push",
    "log_nuxux"
];

// Define o número de registros a serem deletados por iteração
$batchSize = 50000;

echo "Iniciando limpeza de registros...\n";
flush();

// Para cada empresa, conecta-se via connTemp e executa os deletes em blocos
foreach ($companies as $cod_empresa) {
    echo "Processando empresa: $cod_empresa\n";
    flush();

    // Obtém a conexão temporária para a empresa
    $contmp = connTemp($cod_empresa, '');
    if (!$contmp) {
        echo "Erro ao conectar para empresa $cod_empresa\n";
        flush();
        continue;
    }

    // Para cada tabela, deleta registros com mais de 6 meses em lotes
    foreach ($tables as $table) {
        echo "  Excluindo registros da tabela $table...\n";
        flush();
        do {
            // Para tabelas do grupo MSG utiliza o campo DATA_HORA; para as demais, usa dat_cadastr.
            /*   if (strpos($table, 'msg_') === 0) {
                $sql = "DELETE FROM `$table` WHERE DATA_HORA <= DATE_SUB(NOW(), INTERVAL 1 MONTH) LIMIT $batchSize";
            } else {
                $sql = "DELETE FROM `$table` WHERE dat_cadastr <= DATE_SUB(NOW(), INTERVAL 6 MONTH) LIMIT $batchSize";
            }
            mysqli_query($contmp, $sql);
            $affected = mysqli_affected_rows($contmp);
            if ($affected > 0) {
                echo "    $affected registros excluídos em $table\n";
                flush();
            }*/
            // Para tabelas do grupo MSG utiliza o campo DATA_HORA
            if (strpos($table, 'msg_') === 0) {
                $sql = "DELETE FROM `$table` WHERE DATA_HORA <= DATE_SUB(NOW(), INTERVAL 1 MONTH) LIMIT $batchSize";
            }
            // Para tabelas do grupo ORIGEM utiliza o campo DATA_REGISTRO
            elseif (strpos($table, 'origem') === 0) {
                $sql = "DELETE FROM `$table` WHERE dat_cadastr <= DATE_SUB(NOW(), INTERVAL 1 MONTH) LIMIT $batchSize";
            }
            // Para as demais tabelas, usa dat_cadastr
            else {
                $sql = "DELETE FROM `$table` WHERE dat_cadastr <= DATE_SUB(NOW(), INTERVAL 6 MONTH) LIMIT $batchSize";
            }
            usleep(100000); // 0.1 segundo
        } while ($affected > 0);
    }
    // Fecha a conexão temporária para a empresa
    mysqli_close($contmp);
}

echo "Processamento por empresa concluído.\n";
flush();

// Agora, exclui registros das tabelas administrativas usando a conexão $admc
$adminTables = [
    "gatilhos_logs_exec",
    "gatilhos_logs"
];

echo "Processando exclusão nas tabelas administrativas...\n";
flush();
foreach ($adminTables as $table) {
    do {
        // Utiliza o campo DATAHORA_INICIO para deletar registros com mais de 6 meses
        $sql = "DELETE FROM `$table` WHERE DATAHORA_INICIO <= DATE_SUB(NOW(), INTERVAL 2 MONTH) LIMIT $batchSize";
        mysqli_query($admc, $sql);
        $affected = mysqli_affected_rows($admc);
        if ($affected > 0) {
            echo "  $affected registros excluídos em $table\n";
            flush();
        }
        usleep(100000);
    } while ($affected > 0);
}

echo "Processo de limpeza concluído.\n";
flush();
