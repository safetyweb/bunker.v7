<?php

include '../_system/_functionsMain.php';

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);
$cod_campanha = fnDecode(@$_GET['idc']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_POST['LOJAS'];

if (isset($_REQUEST['COD_CAMPANHA']) && $_REQUEST['COD_CAMPANHA'] != "") {
    $cod_campanha = fnLimpaArray($_REQUEST['COD_CAMPANHA']);
} else {
    $cod_campanha = "";
}

$andCampanha = "";
if ($cod_campanha != "") {
    $andCampanha = "AND CP.COD_CAMPANHA IN ($cod_campanha)";
}

$andData = "";
if (isset($dat_ini) && $dat_ini != "") {
    $andData = "AND CL.DAT_CADASTR >= '$dat_ini 00:00:00' AND CL.DAT_CADASTR <= '$dat_fim 23:59:59'";
}

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT 
        CL.COD_CLIENTE,
        CL.NOM_CLIENTE,
        CL.DAT_CADASTR,
        CD.COD_CAMPANHA,
        CL.COD_UNIVEND,
        SUM(CASE WHEN CD.TIP_CREDITO = 'C' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_CREDITOS,
        SUM(CASE WHEN CD.TIP_CREDITO = 'D' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_DEBITOS,
        SUM(V.VAL_TOTVENDA) AS TOT_VENDAS
    FROM creditosdebitos AS CD
    INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
    LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE  AND  V.COD_VENDA=CD.COD_VENDA
    WHERE 
    CD.COD_CLIENTE IN (SELECT 
                        CD.COD_CLIENTE   
                    FROM campanha AS CP
                    INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                    WHERE CP.TIP_CAMPANHA = 23
                    AND CD.COD_STATUSCRED != 6
                    AND CD.COD_EMPRESA=$cod_empresa
                    $andCampanha
                    GROUP BY CD.Cod_cliente)
    AND CL.COD_UNIVEND IN ($lojasSelecionadas)
    $andData
    GROUP BY CD.COD_CLIENTE";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        $CABECHALHO = ['COD_CLIENTE', 'CLIENTE', 'CREDITO_GANHO', 'CREDITO_RESGATADO', 'TOTAL_COMPRAS', 'CAMPANHA', 'CHAVE_CAMPANHA', 'DATA_CADASTRO_CLIENTE'];
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        $arrayLista = [];

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $sqlCampanha = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = " . $row['COD_CAMPANHA'] . " AND COD_EMPRESA = $cod_empresa";
            $queryCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

            $nom_camp = "";
            if ($qrResult = mysqli_fetch_assoc($queryCamp)) {
                $nom_camp = $qrResult['DES_CAMPANHA'];
            }

            $sqlChaveCamp = "SELECT DES_CHAVECAMP FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = " . $row['COD_CAMPANHA'] . " AND COD_UNIVEND_PREF = " . $row['COD_UNIVEND'];
            $queryChave = mysqli_query(connTemp($cod_empresa, ''), $sqlChaveCamp);

            $des_chave = "";
            if ($qrChave = mysqli_fetch_assoc($queryChave)) {
                $des_chave = $qrChave['DES_CHAVECAMP'];
            }

            $arrayLista = [
                $row['COD_CLIENTE'],
                $row['NOM_CLIENTE'],
                fnValor(@$row['TOT_CREDITOS'], 2),
                fnValor(@$row['TOT_DEBITOS'], 2),
                fnValor(@$row['TOT_VENDAS'], 2),
                $nom_camp,
                $des_chave,
                fnDataFull(@$row['DAT_CADASTR'])
            ];

            $array = array_map("utf8_decode", $arrayLista);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        break;
    case 'paginar':

        $sql = "SELECT 
        1
    FROM creditosdebitos AS CD
    INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
    LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE  AND  V.COD_VENDA=CD.COD_VENDA
    WHERE 
    CD.COD_CLIENTE IN (SELECT 
                        CD.COD_CLIENTE   
                    FROM campanha AS CP
                    INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                    WHERE CP.TIP_CAMPANHA = 23
                    AND CD.COD_STATUSCRED != 6
                    AND CD.COD_EMPRESA=$cod_empresa
                    $andCampanha
                    GROUP BY CD.Cod_cliente)
    AND CL.COD_UNIVEND IN ($lojasSelecionadas)
    $andData
    GROUP BY CD.COD_CLIENTE";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        // fnescreve($sql);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        // fnEscreve($numPaginas);
        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT 
        CL.COD_CLIENTE,
        CL.NOM_CLIENTE,
        CL.DAT_CADASTR,
        CD.COD_CAMPANHA,
        CL.COD_UNIVEND,
        SUM(CASE WHEN CD.TIP_CREDITO = 'C' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_CREDITOS,
        SUM(CASE WHEN CD.TIP_CREDITO = 'D' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_DEBITOS,
        SUM(V.VAL_TOTVENDA) AS TOT_VENDAS
    FROM creditosdebitos AS CD
    INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
    LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE  AND  V.COD_VENDA=CD.COD_VENDA
    WHERE 
    CD.COD_CLIENTE IN (SELECT 
                        CD.COD_CLIENTE   
                    FROM campanha AS CP
                    INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                    WHERE CP.TIP_CAMPANHA = 23
                    AND CD.COD_STATUSCRED != 6
                    AND CD.COD_EMPRESA=$cod_empresa
                    $andCampanha
                    GROUP BY CD.Cod_cliente)
    AND CL.COD_UNIVEND IN ($lojasSelecionadas)
    $andData
    GROUP BY CD.COD_CLIENTE limit $inicio,$itens_por_pagina ";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

            $sqlCampanha = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = " . $qrListaVendas['COD_CAMPANHA'] . " AND COD_EMPRESA = $cod_empresa";
            $queryCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

            $nom_camp = "";
            if ($qrResult = mysqli_fetch_assoc($queryCamp)) {
                $nom_camp = $qrResult['DES_CAMPANHA'];
            }

            $sqlChaveCamp = "SELECT DES_CHAVECAMP FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = " . $qrListaVendas['COD_CAMPANHA'] . " AND COD_UNIVEND_PREF = " . $qrListaVendas['COD_UNIVEND'];
            $queryChave = mysqli_query(connTemp($cod_empresa, ''), $sqlChaveCamp);

            $des_chave = "";
            if ($qrChave = mysqli_fetch_assoc($queryChave)) {
                $des_chave = $qrChave['DES_CHAVECAMP'];
            }
?>
            <tr>
                <td><?php echo $qrListaVendas['COD_CLIENTE']; ?></td>
                <td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
                <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_CREDITOS'], 2); ?></small></td>
                <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_DEBITOS'], 2); ?></small></td>
                <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_VENDAS'], 2); ?></small></td>
                <td><?php echo $nom_camp; ?></td>
                <td><?php echo $des_chave; ?></td>
                <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
            </tr>
        <?php
        }
        break;

    case 'comboChave':
        ?>
        <div class="form-group">
            <label for="inputName" class="control-label">Chave Campanha</label>
            <select data-placeholder="Selecione a chave de campanha" name="COD_REGISTR[]" id="COD_REGISTR" multiple="multiple" class="chosen-select-deselect">
                <option value="">Todas Chaves</option>
                <?php
                $sql = "SELECT * FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
                //fnEscreve($sql);
                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                while ($qrListaGrupoWork = mysqli_fetch_assoc($arrayQuery)) {

                    if (!empty($_REQUEST['COD_REGISTR']) && is_array($_REQUEST['COD_REGISTR'])) {
                        if (recursive_array_search($qrListaGrupoWork['COD_UNIVEND_PREF'], array_filter($_REQUEST['COD_REGISTR'])) !== false) {
                            $selecionado = "selected";
                        } else {
                            $selecionado = "";
                        }
                    } else {
                        $selecionado = "";
                    }

                    echo "<option value='" . $qrListaGrupoWork['COD_UNIVEND_PREF'] . "' " . $selecionado . " >" . $qrListaGrupoWork['DES_CHAVECAMP'] . "</option>";
                }
                ?>
            </select>
        </div>
<?php

        break;
}
?>

<script language=javascript>
    $(".chosen-select-deselect").chosen({
        allow_single_deselect: true
    });
</script>