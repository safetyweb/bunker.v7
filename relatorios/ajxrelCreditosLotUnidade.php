<?php

include '../_system/_functionsMain.php';


if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$hoje = '';

$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));


$opcao = @$_GET['opcao'];
$tipo = @$_GET['tipo'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$creditoSelecionados = @$_GET['credlot'];

$lojasSelecionadas = @$_POST['LOJAS'];
$autoriza = @$_POST['AUTORIZA'];
$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$log_resgate = @$_POST['LOG_RESGATE'];

if ($log_resgate == "S") {
    $andResgate = "WHERE VAL_RESGATADO > 0.1";
} else {
    $andResgate = " ";
}

if (empty($creditoSelecionados) || $creditoSelecionados == '9999') {
    $andCredlot = "";
} else {
    $andCredlot = "AND b.COD_CREDLOT IN ($creditoSelecionados)";
}


switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT  a.COD_CREDITO, 
    d.COD_UNIVEND, 
    d.NOM_FANTASI, 
    c.COD_CLIENTE,
    c.NOM_CLIENTE, 
    a.DAT_CADASTR, 
    b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito, 
    a.val_resgatado,
    (select val_totprodu from vendas where vendas.cod_venda=a.COD_VENDA_COM_RESGATE AND vendas.cod_statuscred IN(0,1,2,3,4,5,7,8,9)) AS venda_vinculada  
    FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d 
    WHERE a.COD_CREDITO=b.cod_credito AND 
    a.COD_UNIVEND=d.COD_UNIVEND AND 
    b.cod_empresa=$cod_empresa AND 
    a.COD_UNIVEND IN($lojasSelecionadas) AND 
    b.cod_cliente=c.COD_CLIENTE AND 
    b.cod_credlot != 0 AND 
    a.DAT_CADASTR >= '$dat_ini 00:00:00' AND 
    a.DAT_CADASTR <= '$dat_fim 23:59:59' AND 
    a.VAL_RESGATADO!=a.VAL_ESTORNO 
    $andCredlot 
    ORDER BY a.COD_CREDITO, a.COD_CLIENTE
    ";
        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['val_credito'] = fnValor($row['val_credito'], 2);
            $row['val_resgatado'] = "-" . fnValor($row['val_resgatado'], 2);
            $row['DAT_CADASTR'] = fnFormatDate($row['DAT_CADASTR'], 2);
            $row['venda_vinculada'] = fnValor($row['venda_vinculada'], 2);

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);


        break;

    case 'paginar':

        $sql = "SELECT  a.COD_CREDITO,
d.COD_UNIVEND, 
d.NOM_FANTASI, 
c.COD_CLIENTE, 
c.NOM_CLIENTE, 
a.DAT_CADASTR,
b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito,  
a.val_resgatado


FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d
WHERE a.COD_CREDITO=b.cod_credito AND 
a.COD_UNIVEND=d.COD_UNIVEND AND 
b.cod_empresa=$cod_empresa AND
a.COD_UNIVEND IN($lojasSelecionadas) AND 
b.cod_cliente=c.COD_CLIENTE AND 
b.cod_credlot != 0 AND 
a.DAT_CADASTR >= '$dat_ini 00:00:00' AND a.DAT_CADASTR <= '$dat_fim 23:59:59' AND 
a.VAL_RESGATADO!=a.VAL_ESTORNO
$andCredlot
ORDER BY a.COD_CREDITO, a.COD_CLIENTE
";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT  a.COD_CREDITO, 
d.COD_UNIVEND, 
d.NOM_FANTASI, 
c.COD_CLIENTE,
c.NOM_CLIENTE, 
a.DAT_CADASTR, 
b.VAL_CREDITO/(SELECT COUNT(*) FROM historico_resgate WHERE historico_resgate.VAL_RESGATADO!=historico_resgate.VAL_ESTORNO AND historico_resgate.cod_credito=a.cod_credito) AS val_credito, 
a.val_resgatado,
(select val_totprodu from vendas where vendas.cod_venda=a.COD_VENDA_COM_RESGATE AND vendas.cod_statuscred IN(0,1,2,3,4,5,7,8,9)) AS val_compra_vvr  
FROM historico_resgate a,creditosdebitos b,clientes c, unidadevenda d 
WHERE a.COD_CREDITO=b.cod_credito AND 
a.COD_UNIVEND=d.COD_UNIVEND AND 
b.cod_empresa=$cod_empresa AND 
a.COD_UNIVEND IN($lojasSelecionadas) AND 
b.cod_cliente=c.COD_CLIENTE AND 
b.cod_credlot != 0 AND 
a.DAT_CADASTR >= '$dat_ini 00:00:00' AND 
a.DAT_CADASTR <= '$dat_fim 23:59:59' AND 
a.VAL_RESGATADO!=a.VAL_ESTORNO 
$andCredlot 
ORDER BY a.COD_CREDITO, a.COD_CLIENTE
LIMIT $inicio, $itens_por_pagina
";

        //fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        if (mysqli_num_rows($arrayQuery) != 0) {

            // fnEscreve("if");
            $countLinha = 1;
            while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

?>
                <tr>
                    <td><small><?php echo $qrListaVendas['COD_UNIVEND']; ?></small></td>
                    <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>

                    <?php
                    if ($autoriza == 1) {
                    ?>
                        <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['COD_CLIENTE']; ?></a></td>
                    <?php
                    } else {
                    ?>
                        <td><?php echo $qrListaVendas['COD_CLIENTE']; ?></td>
                    <?php
                    }
                    ?>
                    <td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
                    <td><small><?php echo $qrListaVendas['COD_CREDITO']; ?></small></td>
                    <td class="text-right"><small><?php echo fnValor($qrListaVendas['val_credito'], 2); ?></small></td>
                    <td class="text-right"><small><?php echo fnValor(($qrListaVendas['val_resgatado'] * -1), 2); ?></small></td>
                    <td><small><?php echo fnValor($qrListaVendas['val_compra_vvr'], 2); ?></small></td>
                    <td><small><?php echo fnDataShort($qrListaVendas['DAT_CADASTR']); ?></small></td>

                </tr>

<?php
            }
        }

        break;
}
?>