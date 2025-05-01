<?php

include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$cod_empresa = "";
if (isset($_REQUEST['COD_EMPRESA'])) {
    $cod_empresa = fnLimpaArray($_REQUEST['COD_EMPRESA']);
}

if (isset($_REQUEST['COD_UNIVEND'])) {
    $cod_univend = fnLimpaArray($_REQUEST['COD_UNIVEND']);
}

if (empty($_REQUEST['LOG_TODAS'])) {
    $log_todas = 'N';
} else {
    $log_todas = $_REQUEST['LOG_TODAS'];
}
$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

if ($log_todas == 'S') {
    $andAtivo = "AND LOG_ATIVO = 'N'";
} else {
    $andAtivo = "AND LOG_ATIVO = 'S'";
}

// filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
if ($filtro != "") {
    $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
} else {
    $andFiltro = " ";
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------

switch ($opcao) {
    case 'ativa':


        if ($_SESSION["SYS_COD_MASTER"] == "2") {
            $sql = "SELECT STATUSSISTEMA.DES_STATUS, E.*,
                            (select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = E.COD_EMPRESA) as COD_DATABASE,
                            (select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=E.cod_consultor) as NOM_CONSULTOR, 
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA) AS LOJAS,	
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,
                            (SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=E.COD_INTEGRADORA  ) NOM_INTEGRADORA,
                            B.COD_DATABASE, 
                            B.NOM_DATABASE,
                            E.DAT_ALTERAC 
                            FROM empresas  E
                            LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=E.COD_STATUS
                            INNER JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA 
                            WHERE E.COD_EMPRESA <> 1 
                            $andFiltro
                            $andAtivo
                            ORDER by NOM_FANTASI";
        } else {
            $sql = "SELECT STATUSSISTEMA.DES_STATUS,E.*,
                            (select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = E.COD_EMPRESA) as COD_DATABASE, 
                            (select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=E.cod_consultor) as NOM_CONSULTOR, 
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA) AS LOJAS,	
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,
                            (SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = E.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,	
                            (SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=E.COD_INTEGRADORA  ) NOM_INTEGRADORA,
                            B.COD_DATABASE, 
                            B.NOM_DATABASE,
                            E.DAT_ALTERAC 
                            FROM empresas E
                            LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=E.COD_STATUS
                            INNER JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA 
                            WHERE E.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
                            $andFiltro
                            $andAtivo
                            ORDER by NOM_FANTASI";
        }
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $count = 0;
        $qtd_vendas = 0;
        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
            $count++;

            $sqlVendas = "SELECT count(*) as QTD_VENDAS FROM VENDAS WHERE COD_EMPRESA = " . $qrListaEmpresas['COD_EMPRESA'] . " AND DAT_CADASTR >= '" . fnDataSql($data_inicial) . "' AND DAT_CADASTR <= '" . fnDataSql($data_final) . " '";
            $queryVendas = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlVendas);
            if ($qrVendas = mysqli_fetch_assoc($queryVendas)) {
                $qtd_vendas = $qrVendas['QTD_VENDAS'];
            }

            if ($qrListaEmpresas['LOG_ATIVO'] == 'S') {
                // $mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';	
                $mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
                $radioAcesso = "<input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>";
            } else {
                $mostraAtivo = '';
                $radioAcesso = "";
            }

            if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {
                $tem_sistema = "tem";
            } else {
                $tem_sistema = "nao";
            }

            $mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";

            echo "
        <tr id='bloco_" . $qrListaEmpresas['COD_EMPRESA'] . "'>
            <th width='3%' class='{sorter:false} text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrListaEmpresas['COD_EMPRESA'] . ")' style='padding:10px;'><i class='fa fa-angle-right' aria-hidden='true'></i></a></th>
            <td class='text-center'>" . $qrListaEmpresas['COD_EMPRESA'] . "</td>
            <td>" . $mostraEmpresa . "</td>
            <td class='text-center'>" . $qtd_vendas . "</td>
            <td style='display:none;'>" . $qrListaEmpresas['NUM_TELEFON'] . " / " . $qrListaEmpresas['NUM_CELULAR'] . "</td>
            <td>" . $qrListaEmpresas['NOM_CONSULTOR'] . "</td>
            <td>" . $qrListaEmpresas['NOM_INTEGRADORA'] . "</td>
            <td align='center'>" . $qrListaEmpresas['LOJAS'] . "</td>
            <td align='center'><span class='" . $corLojaAtv . "'>" . $qrListaEmpresas['LOJAS_ATIVAS'] . "</td>
            <td align='center'>" . fnValor($qrListaEmpresas['COBRANCA_ATIVA'], 0) . "</td>
            <td align='center'>" . $mostraAtivo . "</td>
            <td align='center'>" . $qrListaEmpresas['DES_STATUS'] . "</td>
            <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO']) . "</small></td>
            <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_ALTERAC']) . "</small></td>
        </tr>
        <input type='hidden' id='ret_IDC_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>
        <input type='hidden' id='ret_ID_" . $count . "' value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>
        <input type='hidden' id='ret_NOM_EMPRESA_" . $count . "' value='" . $qrListaEmpresas['NOM_EMPRESA'] . "'>
        ";

            $sqlUnv = "SELECT UNV.*, EMP.NOM_FANTASI as NOM_INTEGRADORA, UP.COD_INTEGRADORA
    FROM UNIDADEVENDA AS UNV 
    LEFT JOIN UNIDADES_PARAMETRO AS UP ON UNV.COD_UNIVEND = UP.COD_UNIVENDA AND UP.COD_EMPRESA = UNV.COD_EMPRESA
    LEFT JOIN EMPRESAS AS EMP ON UP.COD_INTEGRADORA = EMP.COD_EMPRESA AND EMP.LOG_INTEGRADORA = 'S'
    WHERE UNV.COD_EMPRESA = '" . $qrListaEmpresas['COD_EMPRESA'] . "' 
    ORDER BY UNV.NOM_FANTASI";

            $query = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlUnv);

            $qtd_vendUnv = 0;
            while ($qrUnv = mysqli_fetch_assoc($query)) {

                $sqlVendUni = "SELECT count(*) as QTD_VENDAS FROM VENDAS WHERE COD_EMPRESA = " . $qrListaEmpresas['COD_EMPRESA'] . " AND COD_UNIVEND = " . $qrUnv['COD_UNIVEND'] . " AND DAT_CADASTR >= '" . fnDataSql($data_inicial) . "' AND DAT_CADASTR <= '" . fnDataSql($data_final) . " '";
                $queryVendUnv = mysqli_query(connTemp($qrListaEmpresas['COD_EMPRESA'], ''), $sqlVendUni);
                if ($qrVendUnv = mysqli_fetch_assoc($queryVendUnv)) {
                    $qtd_vendUnv = $qrVendUnv['QTD_VENDAS'];
                }

                if ($qrUnv['LOG_ESTATUS'] == 'S') {
                    $ativo = '<i class="fal fa-check" aria-hidden="true"></i>';
                } else {
                    $ativo = '';
                }

                if ($qrUnv['LOG_COBRANCA'] == 'S') {
                    $cobranca = '<i class="fal fa-check" aria-hidden="true"></i>';
                } else {
                    $cobranca = '';
                }

                if ($qrUnv['COD_INTEGRADORA'] != '' && $qrUnv['COD_INTEGRADORA'] == '0') {
                    $nomSh = $qrUnv['NOM_INTEGRADORA'];
                } else {
                    $nomSh = "";
                }


                echo "
        <tr style='background-color: #fff; display: none;' class='abreDetail_" . $qrListaEmpresas['COD_EMPRESA'] . "'>
            <td width='40'></td>
            <td class='text-center'>
            " . $qrUnv['COD_UNIVEND'] . "
                
            </td>
            <td>
            " . $qrUnv['NOM_FANTASI'] . "
            </td>
            <td class='text-center'>" . $qtd_vendUnv . "</td>
            <td></td>
            <td>" . $qrUnv['NOM_INTEGRADORA'] . "</td>
            <td></td>
            <td></td>
            <td align='center'>" . $cobranca . "</td>
            <td align='center'>" . $ativo . "</td>
            <td align='center'>Produção</td>
            <td></td>
            <td>" . fnDateRetorno($qrListaEmpresas['DAT_ALTERAC']) . "</td>
        </tr>";
            }
        }
        # code...
        break;

    case 'univend':
        $sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA IN ($cod_empresa) AND LOG_ESTATUS = 'S' ORDER BY COD_EMPRESA DESC";

        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
?>
        <select data-placeholder='Selecione a unidade' name='COD_UNIVEND[]' id='COD_UNIVEND' multiple="multiple" class='chosen-select-deselect'>
            <option value=''>&nbsp;</option>
            <?php
            while ($qrUniv = mysqli_fetch_assoc($arrayQuery)) {

                if (isset($cod_univend) && is_array($cod_univend)) {
                    if (recursive_array_search($qrUniv['COD_UNIVEND'], array_filter($cod_univend)) !== false) {
                        $selecionado = "selected";
                    } else {
                        $selecionado = "";
                    }
                } else {
                    $selecionado = "";
                }

            ?>
                <option value='<?= $qrUniv['COD_UNIVEND'] ?>' <?= $selecionado ?>><?= $qrUniv['COD_UNIVEND'] ?> - <?= $qrUniv['NOM_FANTASI'] ?></option>
            <?php
            }
            ?>
        </select>


        <script language=javascript>
            $(".chosen-select-deselect").chosen({
                allow_single_deselect: true
            });
        </script>

<?php
        break;
}
