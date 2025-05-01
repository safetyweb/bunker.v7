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
$nomeRel = "";
$arquivoCaminho = "";
$andPersonas = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$retorno = "";
$inicio = "";
$qrListaVendas = "";

function getInput($array, $key, $default = '')
{
    return isset($array[$key]) ? $array[$key] : $default;
}


$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));

// echo fnDebug('true');

$opcao = getInput($_GET, 'opcao');
$tipo = getInput($_GET, 'tipo');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$lojasSelecionadas = getInput($_POST, 'LOJAS');
$autoriza = getInput($_POST, 'AUTORIZA');
$cod_grupotr = getInput($_REQUEST, 'COD_GRUPOTR');
$cod_tiporeg = getInput($_REQUEST, 'COD_TIPOREG');
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
@$cod_personas = fnLimpaCampoArray(@$_POST['COD_PERSONA']);

switch ($opcao) {
    case 'exportar':

        $nomeRel = getInput($_GET, 'nomeRel');
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT 
                        CL.COD_CLIENTE CODIGO,
                        CL.NOM_CLIENTE NOME,
                        CL.NUM_CGCECPF CPF,
                        CL.NUM_CARTAO NUM_CARTAO,
                        CL.DES_EMAILUS EMAIL,
                        CL.NUM_CELULAR CELULAR
                    FROM clientes CL
                        WHERE CL.COD_EMPRESA = $cod_empresa
                        AND CL.LOG_AVULSO = 'N'
                        AND CL.LOG_ESTATUS = 'S'
                        AND CL.COD_UNIVEND IN($lojasSelecionadas)
                        $andPersonas
                        AND CL.COD_CLIENTE NOT IN(
                                        SELECT COD_CLIENTE FROM vendas
                                        WHERE COD_EMPRESA = CL.COD_EMPRESA
                                        AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                            )";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);


        break;

    case 'paginar':

        $andPersonas = "";

        if ($cod_personas != '' && $cod_personas != 0) {
            $andPersonas = "AND CL.COD_CLIENTE IN(SELECT COD_CLIENTE FROM PERSONACLIENTES 
                                                        WHERE COD_EMPRESA = $cod_empresa 
                                                        AND COD_PERSONA IN($cod_personas))";
        }

        $sql = "SELECT 1
                    FROM clientes CL
                    WHERE CL.COD_EMPRESA = $cod_empresa
                    AND CL.LOG_AVULSO = 'N'
                    AND CL.LOG_ESTATUS = 'S'
                    AND CL.COD_UNIVEND IN($lojasSelecionadas)
                    $andPersonas
                    AND CL.COD_CLIENTE NOT IN(
                                    SELECT COD_CLIENTE FROM vendas
                                    WHERE COD_EMPRESA = CL.COD_EMPRESA
                                    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                    )";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT 
                            CL.COD_CLIENTE,
                            CL.NOM_CLIENTE,
                            CL.NUM_CGCECPF,
                            CL.NUM_CARTAO,
                            CL.DES_EMAILUS,
                            CL.NUM_CELULAR
                        FROM clientes CL
                            WHERE CL.COD_EMPRESA = $cod_empresa
                            AND CL.LOG_AVULSO = 'N'
                            AND CL.LOG_ESTATUS = 'S'
                            AND CL.COD_UNIVEND IN($lojasSelecionadas)
                            $andPersonas
                            AND CL.COD_CLIENTE NOT IN(
                                            SELECT COD_CLIENTE FROM vendas
                                            WHERE COD_EMPRESA = CL.COD_EMPRESA
                                            AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                                )
                    LIMIT $inicio, $itens_por_pagina";

        // FNeSCREVE($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
?>

            <tr>
                <td><small><?php echo $qrListaVendas['COD_CLIENTE']; ?></small></td>
                <?php
                if ($autoriza == 1) {
                ?>
                    <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                <?php
                } else {
                ?>
                    <td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
                <?php
                }
                ?>
                <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
                <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
                <td><small><?php echo $qrListaVendas['DES_EMAILUS']; ?></small></td>
                <td><small><?php echo fnCorrigeTelefone($qrListaVendas['NUM_CELULAR']); ?></small></td>
            </tr>

<?php
        }

        break;
}
?>