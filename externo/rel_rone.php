<?php
include '../_system/_functionsMain.php';

$arraydados = array();

$cliente = "SELECT E.COD_EMPRESA, E.NOM_FANTASI,E.NUM_CGCECPF as CNPJ, E.DAT_CADASTR, E.DAT_PRODUCAO,us.NOM_USUARIO as CONSULTOR
            FROM empresas E
            INNER JOIN tab_database TB ON TB.COD_EMPRESA=E.COD_EMPRESA
            left JOIN usuarios us ON us.COD_USUARIO= e.COD_CONSULTOR
            WHERE E.COD_MASTER NOT IN (2)
            AND E.COD_SEGMENT NOT IN (3, 20) 
            AND E.LOG_ATIVO = 'S'
            ORDER BY E.NOM_FANTASI";
$rs = mysqli_query($connAdm->connAdm(), $cliente);

while ($row = mysqli_fetch_assoc($rs)) {
    $codEmpresa = $row['COD_EMPRESA'];

    // Inicializa o array para o COD_EMPRESA atual
    $arraydados[$codEmpresa] = $row;

    // Consulta as vendas para o COD_EMPRESA atual
    $vendas = "SELECT COD_VENDA, DAT_CADASTR_WS, FORMAT(VAL_TOTVENDA, 2, 'pt_BR') AS VAL_TOTVENDA 
               FROM vendas 
               WHERE cod_empresa = $codEmpresa 
               LIMIT 1";
    $rs1 = mysqli_query(connTemp($codEmpresa, ''), $vendas);

    // Se houver resultados, mescla com o array existente
    if ($row1 = mysqli_fetch_assoc($rs1)) {
        $arraydados[$codEmpresa] = array_merge($arraydados[$codEmpresa], $row1);
    }
}

// Nome do arquivo CSV
$filename = 'export.csv';

// Abre o arquivo para escrita
$file = fopen($filename, 'w');

// Define o cabeçalho do CSV
$header = array('COD_EMPRESA', 'NOM_FANTASI', 'CNPJ', 'DAT_CADASTR', 'DAT_PRODUCAO', 'CONSULTOR', 'COD_VENDA', 'DAT_CADASTR_WS', 'VAL_TOTVENDA');
fputcsv($file, $header, ';');

// Escreve os dados no arquivo CSV
foreach ($arraydados as $row) {
    fputcsv($file, $row, ';');
}

// Fecha o arquivo
fclose($file);

// Força o download do arquivo CSV
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');
readfile($filename);

// Remove o arquivo após o download
unlink($filename);
