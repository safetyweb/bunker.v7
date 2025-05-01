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
$opcao = "";
$itens_por_pagina = "";
$pagina = "";
$cod_empresa = "";
$num_cgcecpf = "";
$num_cartao = "";
$casasDec = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$cod_controle = "";
$autoriza = "";
$andCpf = "";
$condicaoCartao = "";
$nomeRel = "";
$arquivoCaminho = "";
$sql = "";
$arrayQuery = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$retorno = "";
$total_itens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$email = "";
$sem_result = "";

function getInput($array, $key, $default = '')
{
        return isset($array[$key]) ? $array[$key] : $default;
}


if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
        echo fnDebug('true');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
}

$opcao = getInput($_GET, 'opcao');
$itens_por_pagina = getInput($_GET, 'itens_por_pagina');
$pagina = getInput($_GET, 'idPage');
$cod_empresa = fnDecode(getInput($_GET, 'id'));
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(getInput($_POST, 'NUM_CGCECPF')));
$num_cartao = fnLimpaCampo(getInput($_POST, 'NUM_CARTAO'));
$casasDec = $_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
$lojasSelecionadas = getInput($_POST, 'LOJAS');
$cod_controle = getInput($_POST, 'COD_CONTROLE');
$autoriza = fnLimpaCampoZero(getInput($_POST, 'AUTORIZA'));

if ($num_cgcecpf == "") {
        $andCpf = " ";
} else {
        $andCpf = "AND NUM_CARTAO = $num_cgcecpf ";
}

if ($num_cartao == "") {
        $condicaoCartao = " ";
} else {
        $condicaoCartao = "AND NUM_CARTAO = $num_cartao ";
}

switch ($opcao) {
        case 'exportar':

                $nomeRel = getInput($_GET, 'nomeRel');
                $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';


                $sql = "SELECT A.COD_CLIENTE,
                                        A.NOM_CLIENTE,
                                        A.NUM_CARTAO,
                                        A.DES_EMAILUS,
                                        A.NUM_CELULAR,
                                        A.COD_UNIVEND,
                                        uni.NOM_FANTASI,
                                        A.COD_VENDA,
                                        A.DAT_CADASTR,
                                        A.VAL_COMPRADO,
                                        A.CREDITOS_GERADO,
                                        A.CREDITOS_EXPIRAR,
                                        A.DAT_EXPIRA,
                                        A.SALDO_TOTAL
                        FROM credito_expira_tmp A
                        LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                        WHERE A.COD_EMPRESA = $cod_empresa 
                        AND COD_CONTROLE = $cod_controle
                        AND A.COD_UNIVEND IN($lojasSelecionadas)
                        $andCpf
                        $condicaoCartao
                        ORDER BY DAT_EXPIRA";

                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                $arquivo = fopen($arquivoCaminho, 'w', 0);

                while ($headers = mysqli_fetch_field($arrayQuery)) {
                        $CABECHALHO[] = $headers->name;
                }
                fputcsv($arquivo, $CABECHALHO, ';', '"');

                while ($row = mysqli_fetch_assoc($arrayQuery)) {

                        $row['CREDITOS_GERADO'] = fnValor($row['CREDITOS_GERADO'], 2);
                        $row['CREDITOS_EXPIRAR'] = fnValor($row['CREDITOS_EXPIRAR'], 2);
                        $row['SALDO_TOTAL'] = fnValor($row['SALDO_TOTAL'], 2);
                        //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
                        //$textolimpo = json_decode($limpandostring, true);
                        $array = array_map("utf8_decode", $row);
                        fputcsv($arquivo, $array, ';', '"');
                }
                fclose($arquivo);

                break;
        case 'paginar':

                $sql = "SELECT  1 FROM credito_expira_tmp A
                                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                                WHERE A.COD_EMPRESA = $cod_empresa 
                                AND COD_CONTROLE = $cod_controle
                                AND A.COD_UNIVEND IN($lojasSelecionadas)
                                $andCpf
                                $condicaoCartao
                                ";


                //fnEscreve($sql);
                $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                $total_itens_por_pagina = mysqli_num_rows($retorno);

                $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

                //variavel para calcular o início da visualização com base na página atual
                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                $sql = "SELECT A.COD_CLIENTE,
                                            A.NOM_CLIENTE,
                                            A.NUM_CARTAO,
                                            A.DES_EMAILUS,
                                            A.NUM_CELULAR,
                                            A.COD_UNIVEND,
                                            uni.NOM_FANTASI,
                                            A.COD_VENDA,
                                            A.DAT_CADASTR,
                                            A.VAL_COMPRADO,
                                            A.CREDITOS_GERADO,
                                            A.CREDITOS_EXPIRAR,
                                            A.DAT_EXPIRA,
                                            A.SALDO_TOTAL
                            FROM credito_expira_tmp A
                            LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                            WHERE A.COD_EMPRESA = $cod_empresa 
                            AND COD_CONTROLE = $cod_controle
                            AND A.COD_UNIVEND IN($lojasSelecionadas)
                            $andCpf
                            $condicaoCartao
                            ORDER BY DAT_EXPIRA 
                            LIMIT $inicio,$itens_por_pagina
                            ";

                // fnEscreve($sql);
                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                if (mysqli_num_rows($arrayQuery) != 0) {

                        $countLinha = 1;
                        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
                                if ($qrListaVendas['DES_EMAILUS'] == "") {
                                        $email = "e-mail não cadastrado!";
                                } else {
                                        $email = fnmascaraCampo($qrListaVendas['DES_EMAILUS']);
                                }

?>
                                <tr>
                                        <?php
                                        if ($autoriza == 1) {
                                        ?>
                                                <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                                        <?php
                                        } else {
                                        ?>
                                                <td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
                                        <?php
                                        }
                                        ?>
                                        <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
                                        <td><small><?php echo $email; ?></small></td>
                                        <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CELULAR']); ?></small></td>
                                        <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
                                        <td class="text-center"><small><?php echo $qrListaVendas['DAT_CADASTR']; ?></small></td>
                                        <td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_COMPRADO'], 2); ?></small></td>
                                        <td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITOS_GERADO'], $casasDec); ?></small></td>
                                        <td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITOS_EXPIRAR'], $casasDec); ?></small></td>
                                        <td class="text-center"><small><?php echo $qrListaVendas['DAT_EXPIRA']; ?></small></td>
                                        <!-- <td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['SALDO_TOTAL'], 2); ?></small></td> -->
                                </tr>
                        <?php

                                $countLinha++;
                        }
                } else {
                        $sem_result = "sim";
                        ?>

                        <thead>
                                <tr>
                                        <th colspan="100">
                                                <center>
                                                        <div style="margin: 10px; font-size: 17px; font-weight: bold">Não há créditos/pontos à expirar nesse período</div>
                                                </center>
                                        </th>
                                </tr>
                        </thead>


<?php
                }

                break;
}
?>