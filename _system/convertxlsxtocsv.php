<?php

/**
 * Converte um arquivo Excel (XLSX ou XLS) para CSV.
 * Antes de converter, move o arquivo para o diretório de destino.
 *
 * @param string $inputFile Caminho atual do arquivo (por exemplo, o tmp do upload).
 * @param string $outputDir Diretório onde o arquivo será movido e o CSV será salvo.
 * @param string $delimiter Delimitador para o CSV (padrão: ';').
 * @return mixed Caminho do CSV convertido ou false em caso de erro.
 */
function convertExcelToCsv($inputFile, $outputDir = '/srv/www/htdocs/tmp', $delimiter = ';')
{
    if (!file_exists($inputFile)) {
        error_log("Arquivo não encontrado: $inputFile");
        return false;
    }

    // Verifica a extensão do arquivo (deve ser xlsx ou xls)
    $extension = strtolower(pathinfo($inputFile, PATHINFO_EXTENSION));
    if (!in_array($extension, ['xlsx', 'xls'])) {
        error_log("O arquivo não é Excel: $inputFile");
        return false;
    }

    // Cria o diretório de destino, se não existir
    if (!file_exists($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Move o arquivo para o diretório de destino, mantendo o nome original
    $destinationFile = rtrim($outputDir, DIRECTORY_SEPARATOR) . '/' . basename($inputFile);
    if ($inputFile !== $destinationFile) {
        if (!rename($inputFile, $destinationFile)) {
            error_log("Falha ao mover o arquivo de $inputFile para $destinationFile");
            return false;
        }
        // Atualiza o caminho de entrada para o arquivo movido
        $inputFile = $destinationFile;
    }

    // Gera o nome do arquivo CSV de saída
    $baseName   = pathinfo($inputFile, PATHINFO_FILENAME);
    $outputFile = rtrim($outputDir, DIRECTORY_SEPARATOR) . '/' . $baseName . '.csv';

    // Método 1: Tentar usar LibreOffice/OpenOffice (se disponível)
    // Converte o delimitador para seu valor ASCII e monta a string de filtro.
    // Exemplo: para ';' (ASCII 59) a string fica "59,34,76,1" (34 = aspas duplas)
    $ascii = ord($delimiter);
    $filterOptions = "{$ascii},34,76,1";

    $officeCommands = [
        "libreoffice",
        "soffice",
        "openoffice",
        "/usr/bin/libreoffice",
        "/usr/bin/soffice"
    ];
    foreach ($officeCommands as $cmd) {
        exec("which $cmd 2>/dev/null", $output, $returnVar);
        if ($returnVar === 0) {
            $outdir = escapeshellarg($outputDir);
            // Passa o filtro para definir o delimitador desejado
            exec("$cmd --headless --convert-to csv:\"Text - txt - csv (StarCalc):$filterOptions\" --outdir $outdir " . escapeshellarg($inputFile) . " 2>/dev/null", $output, $returnVar);
            if (file_exists($outputFile)) {
                return $outputFile;
            }
        }
    }

    // Método 2: Usar ZipArchive para processar XLSX (método básico)
    if ($extension === 'xlsx' && class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($inputFile) === TRUE) {
            $xmlData = $zip->getFromName('xl/worksheets/sheet1.xml');
            $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
            if ($xmlData !== false && $sharedStringsXml !== false) {
                $sharedStrings = [];
                $xmlStrings = simplexml_load_string($sharedStringsXml);
                foreach ($xmlStrings->si as $i => $si) {
                    $text = '';
                    if (isset($si->t)) {
                        $text = (string)$si->t;
                    } elseif (isset($si->r)) {
                        foreach ($si->r as $r) {
                            if (isset($r->t)) {
                                $text .= (string)$r->t;
                            }
                        }
                    }
                    $sharedStrings[$i] = $text;
                }

                $xmlSheet = simplexml_load_string($xmlData);
                $outputHandle = fopen($outputFile, 'w');
                if (!$outputHandle) {
                    error_log("Não foi possível abrir o arquivo de saída: $outputFile");
                    $zip->close();
                    return false;
                }

                // Escreve a BOM UTF-8 para auxiliar na exibição correta de caracteres especiais
                fwrite($outputHandle, "\xEF\xBB\xBF");

                // Processa as linhas e força a escrita com aspas para todos os campos
                foreach ($xmlSheet->sheetData->row as $row) {
                    $csvRow = [];
                    foreach ($row->c as $c) {
                        $value = '';
                        if (isset($c->v)) {
                            if (isset($c['t']) && (string)$c['t'] === 's') {
                                $index = (int)$c->v;
                                $value = isset($sharedStrings[$index]) ? $sharedStrings[$index] : '';
                            } else {
                                $value = (string)$c->v;
                            }
                        }
                        // Escapa aspas internas e envolve o valor em aspas
                        $csvRow[] = '"' . str_replace('"', '""', $value) . '"';
                    }
                    fwrite($outputHandle, implode($delimiter, $csvRow) . "\n");
                }

                fclose($outputHandle);
                $zip->close();
                return $outputFile;
            }
            $zip->close();
        }
    }

    return false;
}

/**
 * Função wrapper que encapsula o processo de conversão e retorna uma resposta JSON.
 * Após a conversão bem-sucedida, deleta o arquivo Excel original.
 *
 * @param string $inputPath Caminho para o arquivo Excel.
 * @param string $delimiter Delimitador para o CSV (padrão: ',').
 * @param string $destinationDir Diretório onde o arquivo será movido antes da conversão.
 * @return string JSON com o resultado da conversão.
 */
function importProdSystem($inputPath, $delimiter = ',', $destinationDir = '/srv/www/htdocs/tmp')
{
    $convertedFile = convertExcelToCsv($inputPath, $destinationDir, $delimiter);
    if (!$convertedFile) {
        return json_encode([
            'status'  => 'error',
            'message' => 'Falha ao converter o arquivo'
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    // Define o caminho do arquivo Excel que foi movido para o destino
    $excelFile = rtrim($destinationDir, DIRECTORY_SEPARATOR) . '/' . pathinfo($inputPath, PATHINFO_BASENAME);
    if (file_exists($excelFile)) {
        unlink($excelFile); // Deleta o arquivo Excel original
    }

    return json_encode([
        'status'  => 'success',
        'message' => 'Processamento realizado com sucesso.',
        'data'    => [
            'arquivo_csv' => basename($convertedFile),
            'caminho_csv' => realpath($convertedFile)
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
