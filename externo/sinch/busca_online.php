<?php
// Caminho para o diretório que contém os arquivos
include '../../_system/_functionsMain.php';
// Definindo o intervalo de tempo
$startTime = date('Y-m-d\TH:i:s\Z', strtotime('-1 hour'));
$endTime = date('Y-m-d\TH:i:s\Z');
$empresa = "SELECT * FROM empresas emp
             INNER JOIN senhas_parceiro apar ON apar.cod_empresa = emp.COD_EMPRESA
             INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU = apar.COD_PARCOMU
             WHERE emp.log_ativo = 'S' AND par.COD_TPCOM = '2' AND apar.LOG_ATIVO = 'S'";
$loopingemp = mysqli_query($connAdm->connAdm(), $empresa);
while ($rwempresa = mysqli_fetch_assoc($loopingemp)) {
    for ($index = 0; $index < 2; $index++) {

        if ($index == 0) {
            //usuario massa
            $usuario = $rwempresa['DES_USUARIO'];
            $token = $rwempresa['DES_AUTHKEY'];
        } else {
            //usuario TOKEN
            $usuario = $rwempresa['DES_CLIEXT'];
            $token = $rwempresa['COD_LISTAEXT'];
        }

        $roundCounter = 0; // Inicializa o contador de rodadas
        echo 'inicial:' . $startTime . '<br> final:' . $endTime . '<br>';

        do {
            //'https://api-messaging.wavy.global/v1/sms/status/search?limit=100&start='.$startTime.'&end='.$endTime
            ob_start();
            // Inicializa o cURL
            echo 'https://api-messaging.wavy.global/v1/sms/status/list?limit=100&start=' . $startTime . '&end=' . $endTime;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-messaging.wavy.global/v1/sms/status/list?limit=100&start=' . $startTime . '&end=' . $endTime,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'authenticationtoken: ' . $token,
                    'username: ' . $usuario,
                    'content-type: application/json'
                ),
                CURLOPT_VERBOSE => true, // Ativando verbose para depuração
            ));

            // Executa a requisição cURL
            $response = curl_exec($curl);

            // Verifica erros no cURL
            if (curl_errno($curl)) {
                echo 'Erro cURL: ' . curl_error($curl);
            } else {
                //  Exibe a resposta completa para depuração
                echo 'Resposta: ' . $response;
            }

            // Fecha a conexão cURL
            curl_close($curl);

            // Decodifica o JSON de resposta
            $json_arrayORigem = json_decode($response, true);


            // Verifica se a decodificação foi bem-sucedida
            if ($json_arrayORigem === null && json_last_error() !== JSON_ERROR_NONE) {
                die('Failed to decode JSON: ' . json_last_error_msg());
            }

            // Verifica se o campo 'smsStatuses' está vazio
            if (empty($json_arrayORigem['smsStatuses'])) {
                echo 'Não há dados no retorno para ' . $usuario . '<br>';
                $roundCounter = 0; // Inicializa o contador de rodadas
                break; // Sai do loop se não houver mais dados
            }

            // Processa os dados retornados
            foreach ($json_arrayORigem['smsStatuses'] as $JSONRETORNO) {
                // Caminho do arquivo
                $arquivo = './retorno/' . $JSONRETORNO['correlationId'] . '_' . $JSONRETORNO['id'] . '_arquivo.json';
                // Converte o retorno em JSON
                $json = json_encode($JSONRETORNO);

                // Verifica se o arquivo existe
                if (file_exists($arquivo)) {
                    // Obtém o conteúdo atual do arquivo
                    $conteudoAtual = file_get_contents($arquivo);
                    // Acrescenta o novo conteúdo na última linha
                    $novoConteudo = $conteudoAtual . PHP_EOL . $json;
                    // Escreve o novo conteúdo no arquivo
                    file_put_contents($arquivo, $novoConteudo);
                } else {
                    // Cria o arquivo e escreve o conteúdo nele
                    file_put_contents($arquivo, $json);
                }
            }

            unset($json_arrayORigem);
            unset($JSONRETORNO);
            unset($json);

            ob_end_flush();
            ob_flush();
            flush();

            // Aguarda 5 segundos antes da próxima rodada
            sleep(5);
            $roundCounter++; // Incrementa o contador de rodadas
            echo 'Round: ' . $roundCounter . '<br>';
        } while ($roundCounter < 100 && empty($json_arrayORigem['smsStatuses']));
    }
}
