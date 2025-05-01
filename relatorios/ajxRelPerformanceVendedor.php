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

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);


//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

$cod_univend = implode(', ', $cod_univend);

if ($cod_univend != "" && $cod_univend != 9999) {
    $andUnivend = "AND a.COD_UNIVEND  in ($cod_univend)";
} else {
    $andUnivend = " ";
}

switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT 
    b.NOM_USUARIO,
    uv.NOM_FANTASI,
    SUM(a.qtd_totvenda) AS qtd_totvenda,
    SUM(a.qtd_totavulsa) AS qtd_totavulsa,
    SUM(a.qtd_totfideliz) AS qtd_totfideliz,
    (SUM(a.qtd_totfideliz) / SUM(a.qtd_totvenda)) * 100 AS pct_fidelizado,
    (SUM(a.val_totvenda) / SUM(a.qtd_totvenda)) as tkt_medio,
    SUM(a.val_totvenda) AS val_totvenda,
    SUM(a.val_totfideliz) AS val_totfideliz
    FROM vendas_diarias a
    INNER JOIN usuarios b ON a.cod_vendedor = b.cod_usuario
    INNER JOIN unidadevenda uv ON uv.cod_univend = b.cod_univend
    WHERE a.dat_movimento BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
    $andUnivend
    AND a.cod_empresa = $cod_empresa
    GROUP BY a.cod_vendedor
    ORDER BY tkt_medio DESC
    ";

        // fnEscreve($sql); 

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['NOM_USUARIO'] = $row['NOM_USUARIO'];
            $row['NOM_FANTASI'] = $row['NOM_FANTASI'];
            $row['qtd_totvenda'] = $row['qtd_totvenda'];
            $row['qtd_totavulsa'] = $row['qtd_totavulsa'];
            $row['qtd_totfideliz'] = $row['qtd_totfideliz'];
            $row['pct_fidelizado'] = fnValor($row['pct_fidelizado'], 2);
            $row['tkt_medio'] = fnValor($row['tkt_medio'], 2);
            $row['val_totvenda'] = fnValor($row['val_totvenda'], 2);
            $row['val_totfideliz'] = fnValor($row['val_totfideliz'], 2);

            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        break;

    case 'paginar':

        $sql = "SELECT 
b.NOM_USUARIO,
uv.NOM_FANTASI,
SUM(a.qtd_totvenda) AS qtd_totvenda,
SUM(a.qtd_totavulsa) AS qtd_totavulsa,
SUM(a.qtd_totfideliz) AS qtd_totfideliz,
(SUM(a.qtd_totfideliz) / SUM(a.qtd_totvenda)) * 100 AS pct_fidelizado,
(SUM(a.val_totvenda) / SUM(a.qtd_totvenda)) as tkt_medio,
SUM(a.val_totvenda) AS val_totvenda,
SUM(a.val_totfideliz) AS val_totfideliz
FROM vendas_diarias a
INNER JOIN usuarios b ON a.cod_vendedor = b.cod_usuario
INNER JOIN unidadevenda uv ON uv.cod_univend = b.cod_univend
WHERE a.dat_movimento BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
$andUnivend
AND a.cod_empresa = $cod_empresa
GROUP BY a.cod_vendedor
ORDER BY tkt_medio DESC
";

        //fnEscreve($sql);
        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT 
b.NOM_USUARIO,
uv.NOM_FANTASI,
SUM(a.qtd_totvenda) AS qtd_totvenda,
SUM(a.qtd_totavulsa) AS qtd_totavulsa,
SUM(a.qtd_totfideliz) AS qtd_totfideliz,
(SUM(a.qtd_totfideliz) / SUM(a.qtd_totvenda)) * 100 AS pct_fidelizado,
(SUM(a.val_totvenda) / SUM(a.qtd_totvenda)) as tkt_medio,
SUM(a.val_totvenda) AS val_totvenda,
SUM(a.val_totfideliz) AS val_totfideliz
FROM vendas_diarias a
INNER JOIN usuarios b ON a.cod_vendedor = b.cod_usuario
INNER JOIN unidadevenda uv ON uv.cod_univend = b.cod_univend
WHERE a.dat_movimento BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
$andUnivend
AND a.cod_empresa = $cod_empresa
GROUP BY a.cod_vendedor
ORDER BY tkt_medio DESC
limit $inicio,$itens_por_pagina";

        // fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


        $countLinha = 1;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
?>
            <tr>
                <td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
                <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
                <td class="text-center"><small><?php echo $qrListaVendas['qtd_totvenda']; ?></small></td>
                <td class="text-center"><small><?php echo $qrListaVendas['qtd_totavulsa']; ?></small></td>
                <td class="text-center"><b><small><?php echo $qrListaVendas['qtd_totfideliz']; ?></small></b></td>
                <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['pct_fidelizado'], 2); ?></small></b></td>
                <td class="text-right"><b><small><?php echo fnValor($qrListaVendas['tkt_medio'], 2); ?></small></b></td>
                <td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_totvenda'], 2); ?></small></b></td>
                <td class="text-right"><b><small><?php echo fnValor($qrListaVendas['val_totfideliz'], 2); ?></small></b></td>
            </tr>>
<?php

            $countLinha++;
        }

        break;
}
?>