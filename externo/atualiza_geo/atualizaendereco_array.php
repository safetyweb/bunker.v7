<?php
include '../../_system/_functionsMain.php';

function FnAtualizaGEO($dados_array)
{
    $contador = 0;
    $cepsAtualizados = []; // Lista de CEPs que já foram atualizados

    // Carregar a lista de CEPs já atualizados a partir do arquivo CSV
    $file = 'ceps_atualizados.csv';
    if (file_exists($file)) {
        $cepsAtualizados = array_map('str_getcsv', file($file));
        $cepsAtualizados = array_column($cepsAtualizados, 0); // Converte para uma lista simples
    }

    if ($dados_array['COD_EMPRESA'] == 'all') {
        $where = '';
        $cod_empresa = '';
    } else {
        $where = 'WHERE LOG_ATIVO="S" and cod_empresa=' . $dados_array['COD_EMPRESA'];
        $cod_empresa = "COD_EMPRESA=$dados_array[COD_EMPRESA] and";
    }

    if ($dados_array['CEP'] == 'all') {
        $whereCEP = "WHERE $cod_empresa REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')  > 0 ";
    } else {
        $whereCEP = "WHERE $cod_empresa REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')  in('" . $dados_array['CEP'] . "')";
    }

    if ($dados_array['LIMITCONSULTA'] == 'all') {
        $limit = "";
    } else {
        $limit = "LIMIT $batch_size OFFSET $offset ";
    }

    //empresa que irá fazer a atualização
    $sqlempresa = "SELECT * FROM empresas $where";
    $rwemprea = mysqli_query($dados_array['CONadm'], $sqlempresa);
    
    while ($rsemopresa = mysqli_fetch_assoc($rwemprea)) {
        $contempmysql = connTemp($rsemopresa['COD_EMPRESA'], '');

        ob_start();
        // capturar dados do cliente
        $sqlclintes = "SELECT COD_EMPRESA, 
                              REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '') as NUM_CEPOZOF, 
                              LAT, 
                              LNG, 
                              DES_ENDEREC, 
                              DES_BAIRROC, 
                              COD_ESTADOF 
                       FROM clientes 
                       $whereCEP 
                           GROUP BY REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')
                       $limit";
        echo $sqlclintes . '<br>';
        $rwclientes = mysqli_query($contempmysql, $sqlclintes);

        while ($rsclientes = mysqli_fetch_assoc($rwclientes)) {
            $cepAtual = $rsclientes['NUM_CEPOZOF'];

            // Verifica se o CEP já foi atualizado
            if (in_array($cepAtual, $cepsAtualizados)) {
                continue; // Pula para o próximo se o CEP já foi atualizado
            }

            // capturar dados de cep
            $sqlCEP = "SELECT * FROM cepbr_cidade C
                        INNER JOIN cepbr_bairro B  ON C.id_cidade = B.id_cidade
                        INNER JOIN cepbr_estado S ON C.uf = S.uf
                        INNER JOIN cepbr_endereco E ON E.id_cidade = C.id_cidade AND E.id_bairro = B.id_bairro
                        INNER JOIN cepbr_geo G ON G.cep = E.cep
                        WHERE E.cep = $cepAtual OR C.cep = $cepAtual OR G.cep = $cepAtual";
            $rs_controle = mysqli_query($dados_array['connCEP'], $sqlCEP);
            $rwcepbase = mysqli_fetch_assoc($rs_controle);

            // Faz o UPDATE
            $sqlupdate = "UPDATE clientes SET LAT='" . $rwcepbase['latitude'] . "', 
                                              LNG='" . $rwcepbase['longitude'] . "', 
                                              DES_ENDEREC=CASE WHEN DES_ENDEREC = '' OR DES_ENDEREC IS NULL THEN '" . addslashes($rwcepbase['logradouro']) . "' ELSE DES_ENDEREC END, 
                                              DES_BAIRROC='" . addslashes($rwcepbase['bairro']) . "', 
                                              COD_ESTADOF=CASE WHEN COD_ESTADOF = '' OR COD_ESTADOF IS NULL THEN '" . $rwcepbase['uf'] . "' ELSE COD_ESTADOF END, 
                                              NOM_CIDADEC=CASE WHEN NOM_CIDADEC = '' OR NOM_CIDADEC IS NULL THEN '" . addslashes($rwcepbase['cidade']) . "' ELSE NOM_CIDADEC END 
                          WHERE REPLACE(REPLACE(REPLACE(NUM_CEPOZOF, '-', ''), ' ', ''), '.', '')='" . $cepAtual . "'";
            mysqli_query($contempmysql, $sqlupdate);

            // Adiciona o CEP à lista de CEPs atualizados
            $cepsAtualizados[] = $cepAtual;

            // Adiciona o CEP ao arquivo CSV
            $fp = fopen($file, 'a'); // Abre o arquivo no modo "append"
            fputcsv($fp, [$cepAtual]);
            fclose($fp);

            $contador++;
        }

        ob_end_flush();
        ob_flush();
        flush();
    }

    return array('SQL' => $contador);
}

$dados_array = array(
    'COD_EMPRESA' => '502',
    'CEP' => 'all',
    'LIMITCONSULTA' => 'all',
    'connCEP' => $DADOS_CEP->connUser(),
    'CONadm' => $connAdm->connAdm()
);

$teste = FnAtualizaGEO($dados_array);
echo '<pre>';
print_r($teste);
echo '</pre>';

echo $teste;
?>
