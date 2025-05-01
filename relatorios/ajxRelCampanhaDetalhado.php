<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$cod_campanha = "";
$cod_univen = "";
$dat_ini = "";
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$data_ini = "";
$data_fim = "";
$andData = "";
$andDataBkp = "";
$nomeRel = "";
$arquivoCaminho = "";
$andUnivend = "";
$andUnivendCred = "";
$aux = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$cod_vend = "";
$array = [];
$sql2 = "";
$arrayQuery2 = [];
$CABECHALHO2 = "";
$row2 = "";
$array2 = [];
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$qrCampanhasEmail = "";
$bolinhaVerde = "";



$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = fnLimpaArray(@$_POST['COD_UNIVEND']);
$cod_campanha = fnLimpaArray(@$_POST['COD_CAMPANHA']);
$cod_univen = fnLimpaCampoZero(@$_GET['idu']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$hor_ini = fnLimpaCampo(@$_REQUEST['HOR_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$hor_fim = fnLimpaCampo(@$_REQUEST['HOR_FIM']);

if ($dat_ini != '' && $dat_ini != 0) {
    $data_ini = $dat_ini . ' ' . $hor_ini;
    $data_fim = $dat_fim . ' ' . $hor_fim;
    $andData = "AND creditosdebitos.dat_cadastr >= " . fnDateSql($data_ini) . " AND 
    creditosdebitos.dat_cadastr <= " . fnDateSql($data_fim);

    $andDataBkp = "AND creditosdebitos_bkp.dat_cadastr >=" . fnDateSql($data_ini) . " AND 
    creditosdebitos_bkp.dat_cadastr <=" . fnDateSql($data_fim);
} else {
    $andData = " ";
    $andDataBkp = " ";
}

switch ($opcao) {
    case 'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        if ($cod_univend != "" && $cod_univend != 9999) {
            $andUnivend = " creditosdebitos_bkp.cod_univend IN ($cod_univend) AND";
            $andUnivendCred = "creditosdebitos.cod_univend IN ($cod_univend) AND";
        } else {
            $andUnivend = " ";
        }

        $aux = 0;

        $sql = "SELECT  

    NOM_FANTASI,
    DES_CAMPANHA,
    cod_univend,
    sum(qtd_credito_concedido) qtd_credito_concedido,
    SUM(valor_credito) valor_credito,
    sum(sem_retono) sem_retono,
    sum(com_retorno) com_retorno,
    sum(val_resgatado) as val_resgatado,
    sum(val_compras) val_compras


    FROM (
        SELECT 
        unidadevenda.NOM_FANTASI,
        campanha.DES_CAMPANHA,
        creditosdebitos.cod_univend,
        COUNT(*) qtd_credito_concedido,
        SUM(val_credito) valor_credito,
        sum(case when val_credito=val_saldo then 1 ELSE 0 END) as sem_retono,
        sum(case when val_credito!=val_saldo then 1 ELSE 0 END) as com_retorno,
        sum(case when val_credito!=val_saldo then val_saldo-val_credito ELSE 0 END) as val_resgatado,
        sum(case when val_credito!=val_saldo then
            (SELECT sum(val_totprodu)
                FROM historico_resgate a, vendas b
                WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND 
                a.COD_CREDITO=creditosdebitos.cod_credito AND 
                a.COD_UNIVEND=creditosdebitos.cod_univend
                ) END) val_compras
        FROM creditosdebitos , campanha , unidadevenda
        WHERE creditosdebitos.cod_campanha IN ($cod_campanha) AND
        creditosdebitos.cod_campanha=campanha.cod_campanha  AND 
        creditosdebitos.cod_univend=unidadevenda.cod_univend AND 
        $andUnivendCred
        creditosdebitos.COD_EMPRESA=$cod_empresa
        $andData
        GROUP BY creditosdebitos.cod_univend

        UNION

        SELECT 
        unidadevenda.NOM_FANTASI,
        campanha.DES_CAMPANHA,
        creditosdebitos_bkp.cod_univend,
        COUNT(*) qtd_credito_concedido,
        SUM(val_credito) valor_credito,
        sum(case when val_credito=val_saldo then 1 ELSE  0 END) as sem_retono,
        sum(case when val_credito!=val_saldo then 1 ELSE 0 END) as com_retorno,
        sum(case when val_credito!=val_saldo then val_saldo-val_credito ELSE  0 END) as val_resgatado,
        sum(case when val_credito!=val_saldo then
            (SELECT sum(val_totprodu)
                FROM historico_resgate a, vendas_bkp b
                WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND 
                a.COD_CREDITO=creditosdebitos_bkp.cod_credito AND 
                b.COD_UNIVEND= creditosdebitos_bkp.cod_univend ) END) val_compras

        FROM creditosdebitos_bkp , campanha, unidadevenda
        WHERE creditosdebitos_bkp.cod_campanha IN ($cod_campanha) AND
        creditosdebitos_bkp.cod_campanha=campanha.cod_campanha  AND 
        creditosdebitos_bkp.cod_univend=unidadevenda.cod_univend AND 
        $andUnivend 
        creditosdebitos_bkp.COD_EMPRESA=$cod_empresa
        $andDataBkp
        )credt
    WHERE credt.NOM_FANTASI IS NOT null
    GROUP BY NOM_FANTASI";


        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['NOM_FANTASI'] = $row['NOM_FANTASI'];
            $row['DES_CAMPANHA'] = $row['DES_CAMPANHA'];
            $row['qtd_credito_concedido'] = $row['qtd_credito_concedido'];
            $row['valor_credito'] = fnValor($row['valor_credito'], 2);
            $row['sem_retono'] = $row['sem_retono'];
            $row['com_retorno'] = $row['com_retorno'];
            $row['val_resgatado'] = fnValor($row['val_resgatado'], 2);
            $row['val_compras'] = fnValor($row['val_compras'], 2);
            $cod_vend = $row['cod_univend'];

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');

            $sql2 = "SELECT        creditosdebitos.cod_cliente,
    clientes.NOM_CLIENTE,
    creditosdebitos.cod_venda,
    val_credito valor_credito, 
    case when val_credito!=val_saldo then 1 ELSE 0 END as com_retorno, 
    case when val_credito!=val_saldo then val_saldo-val_credito 
    ELSE 0 END 
    as val_resgatado, 

    ifnull(case when val_credito!=val_saldo then 
      (SELECT sum(val_totprodu) FROM historico_resgate a, vendas b 
       WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND a.COD_CREDITO=creditosdebitos.cod_credito AND a.COD_UNIVEND=creditosdebitos.cod_univend ) 
      END,0) val_compras 

    FROM creditosdebitos , campanha , unidadevenda, clientes 
    WHERE creditosdebitos.cod_campanha IN ($cod_campanha) AND 
    creditosdebitos.cod_campanha=campanha.cod_campanha AND
    creditosdebitos.cod_univend=unidadevenda.cod_univend AND 
    creditosdebitos.cod_univend=$cod_vend AND 
    creditosdebitos.COD_EMPRESA=$cod_empresa AND 
    creditosdebitos.dat_cadastr >= '$data_ini' AND 
    creditosdebitos.dat_cadastr <= '$data_fim' AND 
    creditosdebitos.cod_cliente=clientes.COD_CLIENTE
    UNION
    SELECT        creditosdebitos_bkp.cod_cliente,
    clientes.NOM_CLIENTE,
    creditosdebitos_bkp.cod_venda,
    val_credito valor_credito, 
    case when val_credito!=val_saldo then 1 ELSE 0 END as com_retorno, 
    case when val_credito!=val_saldo then val_saldo-val_credito 
    ELSE 0 END 
    as val_resgatado, 

    ifnull(case when val_credito!=val_saldo then 
      (SELECT sum(val_totprodu) FROM historico_resgate a, vendas b 
       WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND a.COD_CREDITO=creditosdebitos_bkp.cod_credito AND a.COD_UNIVEND=creditosdebitos_bkp.cod_univend ) 
      END,0) val_compras 

    FROM creditosdebitos_bkp , campanha , unidadevenda, clientes 
    WHERE creditosdebitos_bkp.cod_campanha IN ($cod_campanha) AND 
    creditosdebitos_bkp.cod_campanha=campanha.cod_campanha AND
    creditosdebitos_bkp.cod_univend=unidadevenda.cod_univend AND 
    creditosdebitos_bkp.cod_univend=$cod_vend AND 
    creditosdebitos_bkp.COD_EMPRESA=$cod_empresa AND 
    creditosdebitos_bkp.dat_cadastr >= '$data_ini' AND 
    creditosdebitos_bkp.dat_cadastr <= '$data_fim' AND 
    creditosdebitos_bkp.cod_cliente=clientes.COD_CLIENTE";

            $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

            if ($aux != $cod_vend) {

                $CABECHALHO2 = array(" ", " ", "cod_cliente", "NOM_CLIENTE", "cod_venda", "valor_credito", "com_retorno", "val_resgatado", "val_compras");
                fputcsv($arquivo, $CABECHALHO2, ';', '"');

                $aux = $cod_vend;
            }


            while ($row2 = mysqli_fetch_assoc($arrayQuery2)) {

                $row2 = array_merge(['', ''], $row2);
                $row2['cod_cliente'] = $row2['cod_cliente'];
                $row2['NOM_CLIENTE'] = $row2['NOM_CLIENTE'];
                $row2['cod_venda'] = $row2['cod_venda'];
                $row2['valor_credito'] = fnValor($row2['valor_credito'], 2);
                $row2['com_retorno'] = $row2['com_retorno'];
                $row2['val_resgatado'] = fnValor($row2['val_resgatado'], 2);
                $row2['val_compras'] = fnValor($row2['val_compras'], 2);

                $array2 = array_map("utf8_decode", $row2);
                fputcsv($arquivo, $array2, ';', '"');
            }
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
WHERE a.dat_movimento BETWEEN '$data_ini' AND '$data_fim'
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
WHERE a.dat_movimento BETWEEN '$data_ini' AND '$data_fim'
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

    default:

        $sql = "SELECT        creditosdebitos.cod_cliente,
clientes.NOM_CLIENTE,
creditosdebitos.cod_venda,
val_credito valor_credito, 
case when val_credito!=val_saldo then 1 ELSE 0 END as com_retorno, 
case when val_credito!=val_saldo then val_saldo-val_credito 
ELSE 0 END 
as val_resgatado, 

ifnull(case when val_credito!=val_saldo then 
  (SELECT sum(val_totprodu) FROM historico_resgate a, vendas b 
     WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND a.COD_CREDITO=creditosdebitos.cod_credito AND a.COD_UNIVEND=creditosdebitos.cod_univend ) 
  END,0) val_compras 

FROM creditosdebitos , campanha , unidadevenda, clientes 
WHERE creditosdebitos.cod_campanha IN ($cod_campanha) AND 
creditosdebitos.cod_campanha=campanha.cod_campanha AND
creditosdebitos.cod_univend=unidadevenda.cod_univend AND 
creditosdebitos.cod_univend IN ($cod_univen) AND 
creditosdebitos.COD_EMPRESA=$cod_empresa AND 
creditosdebitos.dat_cadastr >= '$data_ini' AND 
creditosdebitos.dat_cadastr <= '$data_fim' AND 
creditosdebitos.cod_cliente=clientes.COD_CLIENTE
UNION
SELECT        creditosdebitos_bkp.cod_cliente,
clientes.NOM_CLIENTE,
creditosdebitos_bkp.cod_venda,
val_credito valor_credito, 
case when val_credito!=val_saldo then 1 ELSE 0 END as com_retorno, 
case when val_credito!=val_saldo then val_saldo-val_credito 
ELSE 0 END 
as val_resgatado, 

ifnull(case when val_credito!=val_saldo then 
  (SELECT sum(val_totprodu) FROM historico_resgate a, vendas b 
     WHERE a.COD_VENDA_COM_RESGATE=b.cod_venda AND a.COD_CREDITO=creditosdebitos_bkp.cod_credito AND a.COD_UNIVEND=creditosdebitos_bkp.cod_univend ) 
  END,0) val_compras 

FROM creditosdebitos_bkp , campanha , unidadevenda, clientes 
WHERE creditosdebitos_bkp.cod_campanha IN ($cod_campanha) AND 
creditosdebitos_bkp.cod_campanha=campanha.cod_campanha AND
creditosdebitos_bkp.cod_univend=unidadevenda.cod_univend AND 
creditosdebitos_bkp.cod_univend IN ($cod_univen) AND 
creditosdebitos_bkp.COD_EMPRESA=$cod_empresa AND 
creditosdebitos_bkp.dat_cadastr >= '$data_ini' AND 
creditosdebitos_bkp.dat_cadastr <= '$data_fim' AND 
creditosdebitos_bkp.cod_cliente=clientes.COD_CLIENTE";

        //fnEscreve($sql);
        //fnEscreve($dat_ini);
        //fnEscreve($hor_ini);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $count = 0;
        ?>
        <tr>
            <th></th>
            <th></th>
            <th>Código Cliente</th>
            <th>Nome Cliente</th>
            <th>Código Venda</th>
            <th class="text-right">Valor Crédito</th>
            <th class="text-right">Com Retorno</th>
            <th class="text-right">Valor Resgatado</th>
            <th class="text-right">Valor Compras</th>
        </tr>

        <?php

        while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)) {
            $count++;

            $bolinhaVerde = ($qrCampanhasEmail['com_retorno'] > 0) ? '<span style="color: green; font-size: 24px;">&bull;</span>' : '';

        ?>

            <tr style="<?= ($qrCampanhasEmail['com_retorno'] > 0) ? 'background-color: #E1FEF2;' : '' ?>">
                <td></td>
                <td class="text-center"><?= $bolinhaVerde ?></td>
                <td><small style="font-weight: normal;"><?= $qrCampanhasEmail['cod_cliente'] ?></small></td>
                <td><small style="font-weight: normal;"><?= $qrCampanhasEmail['NOM_CLIENTE'] ?></small></td>
                <td class="text-center" style="font-weight: normal;"><small><?= $qrCampanhasEmail['cod_venda'] ?></small></td>
                <td class="text-right" style="font-weight: normal;"><small><?= fnValor($qrCampanhasEmail['valor_credito'], 2); ?></small></td>
                <td class="text-right" style="font-weight: normal;"><small><?= $qrCampanhasEmail['com_retorno'] ?></small></td>
                <td class="text-right" style="font-weight: normal;"><small><?= fnValor($qrCampanhasEmail['val_resgatado'], 2); ?></small></td>
                <td class="text-right" style="font-weight: normal;"><small><?= fnValor($qrCampanhasEmail['val_compras'], 2) ?></small></td>
            </tr>

<?php

        }

        break;
}
?>