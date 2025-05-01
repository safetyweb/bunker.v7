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

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT COD_EMPRESA, NOM_FANTASI, NOM_EMPRESA, DAT_CADASTR FROM empresas WHERE LOG_ATIVO = 'N'";

        //fnEscreve($sql);

        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['DAT_CADASTR'] = fnDataFull(@$row['DAT_CADASTR']);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        break;
    case 'paginar':

        $sql = "SELECT 
        1
  FROM empresas E
  LEFT JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA
  WHERE E.LOG_ATIVO = 'N' AND E.COD_MASTER = 3 AND B.NOM_DATABASE  IS not NULL	
  ORDER BY E.NOM_FANTASI asc";

        // fnEscreve($sql);
        $retorno = mysqli_query($connAdm->connAdm(), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        // fnescreve($sql);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        // fnEscreve($numPaginas);
        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        // ================================================================================
        $sql = "SELECT 
      E.COD_EMPRESA,
      E.NOM_FANTASI, 
      E.NOM_EMPRESA,
      E.DAT_CADASTR, 
      E.DAT_EXCLUSA,
      B.NOM_DATABASE
FROM empresas E
LEFT JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA
WHERE E.LOG_ATIVO = 'N' AND E.COD_MASTER = 3 AND B.NOM_DATABASE  IS not NULL	
ORDER BY E.NOM_FANTASI asc
LIMIT $inicio, $itens_por_pagina
";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $count = 0;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

?>
            <tr id="bloco_<?php echo $qrListaVendas['COD_EMPRESA']; ?>">
                <th>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="CHECK_ALL_<?= $qrListaVendas['COD_EMPRESA'] ?>" onclick="checkAll(<?= $qrListaVendas['COD_EMPRESA'] ?>)">
                    </div>
                </th>
                <th width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_EMPRESA']; ?>, '<?= $qrListaVendas['NOM_DATABASE']; ?>')" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
                <th><?= $qrListaVendas['COD_EMPRESA']; ?></th>
                <th><?= $qrListaVendas['NOM_FANTASI']; ?></th>
                <th><?= $qrListaVendas['NOM_EMPRESA']; ?></th>
                <th><?= fnDataFull($qrListaVendas['DAT_CADASTR']); ?></th>
                <th><?= fnDataFull($qrListaVendas['DAT_EXCLUSA']); ?></th>
                <th><?= $qrListaVendas['NOM_DATABASE']; ?></th>
            </tr>
            <tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_EMPRESA']; ?>">
                <td colspan="7">
                    <div class="detail-content_<?php echo $qrListaVendas['COD_EMPRESA']; ?>"></div>
                </td>
            </tr>
        <?php
        }

        break;

    case 'expandir':
        $cod_empresa = fnLimpacampoZero($_GET['id']);
        $base = fnLimpaCampo($_GET['base']);
        $token = fnEncode($_GET["token"]);
        // echo $_SESSION["TOKEN_SQL"]["TOKEN"];
        if ($token == $_SESSION["TOKEN_SQL"]["TOKEN"]) {

            $_SESSION["TOKEN_SQL"]["VALIDADO"] = true;

        ?>
            <div class="detail-content_<?php echo $cod_empresa; ?>">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td width="40"></td>
                            <td><b>Tabela</b></td>
                            <td><b>Qtd. Registros</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlReg = "SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE table_schema='" . $base . "' AND COLUMN_NAME='COD_EMPRESA' GROUP BY table_name";
                        $query = mysqli_query(connTemp($cod_empresa, ''), $sqlReg);

                        while ($qrResult = mysqli_fetch_assoc($query)) {
                            $sql = "SELECT COUNT(*) AS QTD FROM " . $qrResult['TABLE_NAME'] . " WHERE COD_EMPRESA = " . $cod_empresa;
                            $queryResult = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

                            if ($queryResult['QTD'] > 0) {
                        ?>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="CHECK_<?= $cod_empresa; ?>_<?= $qrResult['TABLE_NAME']; ?>">
                                        </div>
                                    </td>
                                    <td><?= $qrResult['TABLE_NAME']; ?></td>
                                    <td><?= $queryResult['QTD']; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
<?php
        }

        break;
}
?>