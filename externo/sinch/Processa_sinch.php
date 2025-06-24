<?php
// Caminho para o diretório que contém os arquivos
include '../../_system/_functionsMain.php';
$dir = '/srv/www/htdocs/externo/sinch/retorno/';


// Obtém a lista de arquivos no diretório usando a função glob
$arquivos = glob($dir . '*.json');

// Verifica se foram encontrados arquivos
if (!empty($arquivos)) {
    // Itera sobre cada arquivo encontrado

    foreach ($arquivos as $arquivo) {
        // captura o nome de arquivo 
        $partes = explode('/', $arquivo);
        $nomeArquivo = end($partes);
        // Remove a extensão do arquivo
        $nomeArquivoSemExtensao = pathinfo($nomeArquivo, PATHINFO_FILENAME);
        // Explode a string pelo caractere de sublinhado (_)
        $partesExplodidas = explode('_', $nomeArquivoSemExtensao);
        $contemporaria = connTemp($partesExplodidas[0], '');

        //abrindo o arquivo para leitura

        $handle = fopen($arquivo, 'r');

        if ($handle) {
            // Lê o conteúdo do arquivo linha por linha usando fgets
            while (($linha = fgets($handle)) !== false) {
                //  echo $linha; // Ou faça algo com o conteúdo da linha
                /*echo '<pre>';
                print_r(json_decode($linha, true));
                echo '</pre>';*/
                $dadosret = json_decode($linha, true);
                //inserir na base de dados os valores para fechamento
                $testeinsert = "INSERT INTO log_nuxux (COD_CAMPANHA,COD_EMPRESA, TIP_LOG, LOG_JSON,DAT_CADASTR,CHAVE_GERAL,CHAVE_CLIENTE) VALUES (0,$partesExplodidas[0], '23', '" . addslashes($linha) . "','" . date('Y-m-d') . "','" . $dadosret['id'] . "','" . $dadosret['id'] . "');";
                $t = mysqli_query($contemporaria, $testeinsert);
                // retorno da inserção na base de dados for sucess excluir o arquivos
                if ($t) {
                    // Consulta foi bem-sucedida, agora vamos excluir o arquivo
                    if (unlink($arquivo)) {
                        echo "Arquivo deletado com sucesso: $arquivo<br><br>";
                    } else {
                        echo "Não foi possível deletar o arquivo: $arquivo<br><br>";
                    }
                }
            }

            // Fecha o arquivo após a leitura
            fclose($handle);
        } else {
            // echo "Não foi possível abrir o arquivo: $arquivo<br>";
        }
    }
} else {
    echo "Nenhum arquivo encontrado no diretório.";
}

//iniciar processo de alterarção do status na sms_lista_ret.

$empresa = "SELECT * FROM empresas emp
             INNER JOIN senhas_parceiro apar ON apar.cod_empresa = emp.COD_EMPRESA
             INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU = apar.COD_PARCOMU
             WHERE emp.log_ativo = 'S' AND par.COD_TPCOM = '2' AND apar.LOG_ATIVO = 'S'";

$rwempresa = mysqli_query($connAdm->connAdm(), $empresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
    $contemporaria = connTemp($rsempresa['COD_EMPRESA'], '');

    $testeinsert = "SELECT * FROM log_nuxux WHERE LOG_PROCESSADO = 'N' AND TIP_LOG = '23' AND COD_EMPRESA = " . $rsempresa['COD_EMPRESA'];
    $rw = mysqli_query($contemporaria, $testeinsert);
    $totalLinhas = mysqli_num_rows($rw);
    $groupSize = 200; // Exemplo: Tamanho do grupo
    $totalGrupos = ceil($totalLinhas / $groupSize);

    $dadosordem = array();
    $bulkingUpdates = array();

    do {
        $rs = mysqli_fetch_assoc($rw);
        if ($rs) {
            $dadosjson = json_decode($rs['LOG_JSON'], true);
            /*   if (in_array($dadosjson['sentStatusCode'], ['209', '301', '202', '204', '203'])) {

                $dadosjson['deliveredStatusCode'] = $dadosjson['sentStatusCode'];
                $dadosjson['deliveredStatus'] = $dadosjson['sentStatus'];
            }*/




            if (!isset($dadosordem[$dadosjson['deliveredStatusCode']])) {
                $dadosordem[$dadosjson['deliveredStatusCode']] = array();
            }

            $lastIndex = count($dadosordem[$dadosjson['deliveredStatusCode']]) - 1;
            if ($lastIndex < 0 || count($dadosordem[$dadosjson['deliveredStatusCode']][$lastIndex]) >= $totalGrupos) {
                $dadosordem[$dadosjson['deliveredStatusCode']][] = array();
                $lastIndex++;
            }

            $dadosordem[$dadosjson['deliveredStatusCode']][$lastIndex][] = array(
                'destination' => $dadosjson['destination'],
                'sentStatusCode' => $dadosjson['sentStatusCode'],
                'sentStatus' => $dadosjson['sentStatus'],
                'carrierName' => $dadosjson['carrierName'],
                'deliveredStatusCode' => $dadosjson['deliveredStatusCode'],
                'deliveredStatus' => $dadosjson['deliveredStatus'],
                'correlationId' => $dadosjson['correlationId'],
                'id' => $dadosjson['id'],
                'extraInfo' => $dadosjson['extraInfo'],
            );

            $bulkingUpdates[] = $rs['CHAVE_CLIENTE'];
        }
    } while ($rs);

    echo '<pre>';
    print_r($dadosordem);
    echo '<pre>';

    foreach ($dadosordem as $deliveredStatusCode => $groups) {
        foreach ($groups as $group) {
            $chaveClientes = array();
            foreach ($group as $value1) {
                // Adiciona cada CHAVE_CLIENTE entre aspas simples
                $chaveClientes[] = "'{$value1['id']}'";
            }

            $setClauses = array("DES_STATUS='" . $value1['deliveredStatus'] . "'");

            if ($deliveredStatusCode == 4 || $deliveredStatusCode == 2) {
                $setClauses[] = "COD_LEITURA='1'";
                $setClauses[] = "COD_CCONFIRMACAO='1'";
                $setClauses[] = "BOUNCE='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
            } elseif ($deliveredStatusCode == 104) {
                $setClauses[] = "BOUNCE='1'";
                $setClauses[] = "COD_LEITURA='0'";
                $setClauses[] = "COD_CCONFIRMACAO='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
            } elseif ($deliveredStatusCode == 202) {
                $setClauses[] = "BOUNCE='1'";
                $setClauses[] = "COD_LEITURA='0'";
                $setClauses[] = "COD_CCONFIRMACAO='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
                /* } elseif ($deliveredStatusCode == 209 || $deliveredStatusCode == 301 || $deliveredStatusCode == 202) {
                $setClauses[] = "BOUNCE='1'";
                $setClauses[] = "COD_LEITURA='0'";
                $setClauses[] = "COD_CCONFIRMACAO='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
            } elseif ($deliveredStatusCode == 204 || $deliveredStatusCode == 203) {
                $setClauses[] = "BOUNCE='0'";
                $setClauses[] = "COD_LEITURA='0'";
                $setClauses[] = "COD_CCONFIRMACAO='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
                $setClauses[] = "COD_OPTOUT_ATIVO='1'";*/
            } else {
                $setClauses[] = "COD_LEITURA='1'";
                $setClauses[] = "COD_CCONFIRMACAO='1'";
                $setClauses[] = "BOUNCE='0'";
                $setClauses[] = "COD_NRECEBIDO='0'";
                $setClauses[] = "COD_SCONFIRMACAO='0'";
            }
            $setClauseString = implode(', ', $setClauses);
            $chaveClientesString = implode(',', $chaveClientes);

            $canceled1 = "UPDATE sms_lista_ret SET $setClauseString
                           WHERE CHAVE_CLIENTE IN ($chaveClientesString) AND
                           cod_empresa={$rsempresa['COD_EMPRESA']};";
            //idContatosMailing
            echo '<br>' . $canceled1 . '<br>';

            $testeerro = mysqli_query($contemporaria, $canceled1);
            if (!$testeerro) {
                echo '<br>' . $canceled1 . '<br>';
            }
            $bulkUpdateQuery = "UPDATE log_nuxux SET LOG_PROCESSADO = 'S' WHERE CHAVE_CLIENTE IN ($chaveClientesString)";
            $ALTLOG = mysqli_query($contemporaria, $bulkUpdateQuery);
            if (!$ALTLOG) {
                echo '<BR>' . $bulkUpdateQuery . '<BR>';
                break;
            }
        }
    }
    //alterar os token com o codigo do cliente
    $altercodtoken = "update geratoken  ger  
                        INNER JOIN rel_geratoken rel ON rel.COD_GERATOKEN=ger.COD_TOKEN
                        INNER JOIN sms_lista_ret  ret ON ret.CHAVE_CLIENTE=rel.CHAVE_CLIENTE AND ret.CHAVE_CLIENTE!=''
                        INNER JOIN clientes c ON c.NUM_CGCECPF=ger.NUM_CGCECPF
                        set ret.COD_CLIENTE=ger.COD_CLIENTE 
                        WHERE 
                        ger.cod_empresa='{$rsempresa['COD_EMPRESA']}'
                        AND ger.LOG_USADO='2' 
                        AND ger.COD_EXCLUSA=0 AND ret.COD_CLIENTE='0'
                        AND ret.idContatosMailing in ('23')";
    $rwt = mysqli_query($contemporaria, $altercodtoken);
    if (!$rwt) {
        echo '<br>' . $altercodtoken . '<br>';
    }
}
