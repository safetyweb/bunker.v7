<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);


$lojasSelecionadas = @$_POST['LOJAS'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);


//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}


switch ($opcao) {

    case 'exportar':
        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        // Inicia o arquivo CSV
        $arquivo = fopen($arquivoCaminho, 'w', 0);

        // Cabeçalhos do CSV
        $cabecalho = ['Unidade', 'Codigo', 'Nome', 'Data de Cadastro', 'Quantidade de Indicacoes'];
        fputcsv($arquivo, $cabecalho, ';', '"');

        // Consulta para obter os dados de clientes indicados por loja
        $sql = "SELECT UNV.NOM_FANTASI, UNV.COD_UNIVEND, COUNT(CI.COD_CLIENTE) AS QTD_CLIENTES_IDICA 
                FROM unidadevenda AS UNV
                INNER JOIN clientes_indicados AS CI ON UNV.COD_UNIVEND = CI.COD_UNIVEND
                WHERE CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                AND CI.DAT_CADASTR <= '$dat_fim 23:59:59'
                AND UNV.cod_empresa = $cod_empresa
                AND UNV.COD_UNIVEND IN($lojasSelecionadas)
                GROUP BY UNV.COD_UNIVEND
                ORDER BY UNV.NOM_FANTASI ASC";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        while ($qrCupom = mysqli_fetch_assoc($arrayQuery)) {
            // Linha para a unidade de venda
            $linha = [$qrCupom['NOM_FANTASI'], '', '', '', $qrCupom['QTD_CLIENTES_IDICA']];
            fputcsv($arquivo, $linha, ';', '"');

            // Consulta para obter os indicadores de cada loja
            $sqlIndica = "SELECT COUNT(CI.COD_INDICAD) AS QTD_INDICA, CL.NOM_CLIENTE, CL.COD_CLIENTE, CI.COD_INDICAD 
                          FROM clientes_indicados AS CI
                          LEFT JOIN CLIENTES AS CL ON CI.COD_INDICAD = CL.COD_CLIENTE
                          WHERE CI.COD_UNIVEND = " . $qrCupom['COD_UNIVEND'] . " 
                          AND CI.COD_EMPRESA = $cod_empresa
                          AND CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                          AND CI.DAT_CADASTR <= '$dat_fim 23:59:59' 
                          GROUP BY CI.COD_INDICAD";

            $queryIndica = mysqli_query(connTemp($cod_empresa, ''), $sqlIndica);

            while ($qrIndica = mysqli_fetch_assoc($queryIndica)) {
                // Linha para o indicador

                $nom_cliente = $qrIndica['NOM_CLIENTE'];
                if ($nom_cliente == "") {
                    $nom_cliente = 'Cliente Invalido';
                }
                $linhaIndica = ['', $qrIndica['COD_CLIENTE'], $nom_cliente, '', $qrIndica['QTD_INDICA']];
                fputcsv($arquivo, $linhaIndica, ';', '"');

                // Consulta para obter os clientes indicados por cada indicador
                $sqlIndicado = "SELECT CL.NOM_CLIENTE, CL.COD_CLIENTE, CL.DAT_CADASTR 
                                FROM clientes_indicados AS CI
                                INNER JOIN CLIENTES AS CL ON CI.COD_CLIENTE = CL.COD_CLIENTE
                                WHERE CI.COD_UNIVEND = " . $qrCupom['COD_UNIVEND'] . " 
                                AND CI.COD_EMPRESA = $cod_empresa
                                AND CI.COD_INDICAD = " . $qrIndica['COD_CLIENTE'] . "
                                AND CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                                AND CI.DAT_CADASTR <= '$dat_fim 23:59:59' 
                                GROUP BY CI.COD_INDICAD";

                $queryIndicado = mysqli_query(connTemp($cod_empresa, ''), $sqlIndicado);

                while ($qrIndicado = mysqli_fetch_assoc($queryIndicado)) {
                    // Linha para o cliente indicado
                    $linhaIndicado = ['', $qrIndicado['COD_CLIENTE'], $qrIndicado['NOM_CLIENTE'], fnDataShort($qrIndicado['DAT_CADASTR']), ''];
                    fputcsv($arquivo, $linhaIndicado, ';', '"');
                }
            }
        }

        fclose($arquivo);

        break;
}
