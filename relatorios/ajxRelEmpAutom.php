<?php

include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


$opcao = $_GET['opcao'];

if (isset($_GET['itens_por_pagina'])) {
    $itens_por_pagina = $_GET['itens_por_pagina'];
}
if (isset($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
}
if (isset($_REQUEST['NOM_FANTASI'])) {
    $nom_fantasi = fnLimpaCampo($_REQUEST['NOM_FANTASI']);
}
if (isset($_REQUEST['NOM_EMPRESA'])) {
    $nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
}
if (isset($_REQUEST['NUM_CGCECPF'])) {
    $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
}
if (isset($_REQUEST['DAT_INI'])) {
    $dat_ini = fnDataSql($_POST['DAT_INI']);
}
if (isset($_REQUEST['DAT_FIM'])) {
    $dat_fim = fnDataSql($_POST['DAT_FIM']);
}
if (isset($_REQUEST['idr'])) {
    $cod_registro = fnLimpaCampoZero($_REQUEST['idr']);
}

switch ($opcao) {

    case 'paginar':

        $andFantasi = "";
        $andEmpresa = "";
        $andCnpj = "";
        $andData = "";

        if ($nom_fantasi != "") {
            $andFantasi = "AND EMP.NOM_FANTASI = '$nom_fantasi'";
        }

        if ($nom_empresa != "") {
            $andEmpresa = "AND EMP.NOM_EMPRESA = '$nom_empresa'";
        }

        if ($num_cgcecpf != "") {
            $andCnpj = "AND EMP.NUM_CGCECPF = '$num_cgcecpf'";
        }

        if ($dat_ini != "" && $dat_fim != "") {
            $andData = "AND AUD.DAT_CADASTR BETWEEN '$dat_ini' AND '$dat_fim'";
        }

        $sql = "SELECT 
                1
                FROM auditoria_empresa AS AUD
                INNER JOIN empresas AS EMP ON AUD.COD_EMPRESA = EMP.COD_EMPRESA
                WHERE 1=1
                $andFantasi
                $andEmpresa
                $andCnpj
                $andData
                ";

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
                AUD.FASE1, 
                AUD.FASE2, 
                AUD.FASE3, 
                AUD.FASE4, 
                AUD.FASE5,
                AUD.COD_EMPRESA,
                EMP.NOM_EMPRESA,
                EMP.NOM_FANTASI,
                EMP.NUM_CGCECPF,
                EMP.DAT_CADASTR,
                AUD.DAT_FINALIZA
                FROM auditoria_empresa AS AUD
                INNER JOIN empresas AS EMP ON AUD.COD_EMPRESA = EMP.COD_EMPRESA
                WHERE 1=1
                    $andFantasi
                    $andEmpresa
                    $andCnpj
                    $andData
            LIMIT $inicio, $itens_por_pagina
            ";

        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $count = 0;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
            $status = '<span class="label label-danger">Em Andamento</span>';
            if ($qrListaVendas['FASE1'] == 'S' && $qrListaVendas['FASE2'] == 'S' && $qrListaVendas['FASE3'] == 'S' && $qrListaVendas['FASE4'] == 'S' && $qrListaVendas['FASE5'] == 'S') {
                $status = '<span class="label label-success">Concluído</span>';
            }

?>
            <tr>
                <td class="text-center"><a href="action.php?mod=<?= fnEncode(2091) ?>&id=<?= fnEncode($qrListaVendas['COD_EMPRESA']) ?>"><?= $qrListaVendas['COD_EMPRESA']; ?></a></td>
                <td><?= $qrListaVendas['NOM_EMPRESA']; ?></td>
                <td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
                <td><?= fnformatCnpjCpf($qrListaVendas['NUM_CGCECPF']); ?></td>
                <td><?= $status; ?></td>
                <td><?= fnDataShort($qrListaVendas['DAT_CADASTR']); ?></td>
                <td><?= fnDataShort($qrListaVendas['DAT_FINALIZA']); ?></td>
                <td></td>
            </tr>
<?php

            $count++;
        }
        break;

    case 'excluir':

        $sql = "SELECT 
        EMP.COD_EMPRESA,
        B.NOM_DATABASE
        FROM EMPRESAS as EMP
        LEFT JOIN tab_database B ON B.cod_empresa=EMP.COD_EMPRESA
        WHERE EMP.COD_EMPRESA = $cod_registro";

        $query = mysqli_query($connAdm->connAdm(), $sql);
        if (mysqli_num_rows($query) > 0) {
            while ($qrResult = mysqli_fetch_assoc($query)) {
                if ($qrResult['NOM_DATABASE'] != "") {

                    $sqlReg = "SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE table_schema='" . $qrResult['NOM_DATABASE'] . "' AND COLUMN_NAME='COD_EMPRESA' GROUP BY table_name";
                    $queryTables = mysqli_query(connTemp($qrResult['COD_EMPRESA'], ''), $sqlReg);

                    while ($qrtables = mysqli_fetch_assoc($queryTables)) {
                        $sql = "DELETE FROM " . $qrtables['TABLE_NAME'] . " WHERE COD_EMPRESA = " . $qrResult['COD_EMPRESA'];
                        mysqli_query(connTemp($qrResult['COD_EMPRESA'], ''), $sql);
                    }
                }

                $sqlReg = "SELECT TABLE_NAME FROM information_schema.COLUMNS WHERE table_schema='webtools' AND COLUMN_NAME='COD_EMPRESA' AND TABLE_NAME NOT IN ('LOG_CPF', 'CLIP_HELP') GROUP BY table_name";
                $queryAdm = mysqli_query($connAdm->connAdm(), $sqlReg);
                if (mysqli_num_rows($queryAdm) > 0) {

                    while ($qrAdm = mysqli_fetch_assoc($queryAdm)) {
                        $sqlAdm = "DELETE FROM " . $qrAdm['TABLE_NAME'] . " WHERE COD_EMPRESA = " . $qrResult['COD_EMPRESA'] . ";";
                        mysqli_query($connAdm->connAdm(), $sqlAdm);
                    }
                }
            }
        }



        break;
}
?>