<?php
include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$hoje = "";
$opcao = "";
$tipo = "";
$pagina = "";
$lojasSelecionadas = "";
$autoriza = "";
$dat_ini = "";
$dat_fim = "";
$log_resgate = "";
$andResgate = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$retorno = "";
$inicio = "";
$qrListaVendas = "";

function getInput($array, $key, $default = '')
{
    return isset($array[$key]) ? $array[$key] : $default;
}


$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$opcao = getInput($_GET, 'opcao');
$tipo = getInput($_GET, 'tipo');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$lojasSelecionadas = getInput($_POST, 'LOJAS');
$autoriza = getInput($_POST, 'AUTORIZA');
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$cod_univend = getInput($_REQUEST, 'COD_UNIVEND');
$cod_grupotr = getInput($_REQUEST, 'COD_GRUPOTR');
$cod_tiporeg = getInput($_REQUEST, 'COD_TIPOREG');

/*if ($log_resgate == "S"){
        $andResgate = "WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'";
    }else {
        $andResgate = " ";
    }*/

switch ($opcao) {
    case 'exportar':

        $nomeRel = getInput($_GET, 'nomeRel');
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT * FROM (SELECT 
                A.COD_CREDITO,
                B.COD_CLIENTE,
                B.NOM_CLIENTE,
                A.DAT_CADASTR,
                ROUND(A.VAL_CREDITO,2) VAL_CREDITO,
                A.VAL_CREDITO-A.VAL_SALDO VAL_RESGATADO,
                date(IFNULL((SELECT max(dat_cadastr_ws) FROM vendas
                    WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
                        WHERE cod_credito=A.COD_CREDITO)),0)) AS DATA_RESGATE,
                IFNULL(ROUND((SELECT SUM(VAL_TOTPRODU) FROM vendas
                    WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
                        WHERE cod_credito=A.COD_CREDITO)),2),0) AS VVR,
                des_operaca
                FROM creditosdebitos a, clientes b
                WHERE a.cod_empresa=$cod_empresa 
                AND a.COD_UNIVEND IN($lojasSelecionadas) 
                AND a.cod_cliente=b.COD_CLIENTE 
                AND a.cod_credlot != 0
                AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
                )tmpcred
                WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'
                ";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        //fnEscreve($sql);


        break;

    case 'paginar':

        $sql = "SELECT 1
                        FROM creditosdebitos a, clientes b
                        WHERE a.cod_empresa=$cod_empresa
                        AND a.COD_UNIVEND IN($lojasSelecionadas)
                        AND a.cod_cliente=b.COD_CLIENTE
                        AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
                    ";
        //  estava na linha 118   --AND A.DAT_CADASTR >= '$dat_ini' AND A.DAT_CADASTR <= '$dat_fim'

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);

        // fnEscreve($retorno);

        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT * FROM (SELECT 
                A.COD_CREDITO,
                B.COD_CLIENTE,
                B.NOM_CLIENTE,
                A.DAT_CADASTR,
                ROUND(A.VAL_CREDITO,2) VAL_CREDITO,
                A.VAL_CREDITO-A.VAL_SALDO VAL_RESGATADO,
                date(IFNULL((SELECT max(dat_cadastr_ws) FROM vendas
                    WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
                        WHERE cod_credito=A.COD_CREDITO)),0)) AS DATA_RESGATE,
                IFNULL(ROUND((SELECT SUM(VAL_TOTPRODU) FROM vendas
                    WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
                        WHERE cod_credito=A.COD_CREDITO)),2),0) AS VVR,
                des_operaca
                FROM creditosdebitos A, clientes B
                WHERE A.cod_empresa=$cod_empresa 
                AND A.COD_UNIVEND IN($lojasSelecionadas) 
                AND A.cod_cliente=B.COD_CLIENTE 
                AND A.cod_credlot != 0
                AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
                )tmpcred
                WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'
                LIMIT $inicio, $itens_por_pagina";

        // estava na linha 50 -- AND DATE(A.DAT_CADASTR) >= '$dat_ini' AND DATE(A.DAT_CADASTR) <= '$dat_fim'


        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
?>

            <tr>
                <td><small><?php echo $qrListaVendas['COD_CREDITO']; ?></small></td>
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
                <td><small><?php echo fnDataShort($qrListaVendas['DAT_CADASTR']); ?></small></td>
                <td class="text-right"><small><?php echo fnValor($qrListaVendas['VAL_CREDITO'], 2); ?></small></td>
                <td class="text-right"><small><?php echo fnValor(($qrListaVendas['VAL_RESGATADO'] * -1), 2); ?></small></td>
                <td><small><?php echo fnDataShort($qrListaVendas['DATA_RESGATE']); ?></small></td>
                <td class="text-right"><small><?php echo fnValor($qrListaVendas['VVR'], 2); ?></small></td>
            </tr>

<?php
        }

        break;
}
?>