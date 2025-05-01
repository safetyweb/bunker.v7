<?php

include '../_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);


$lojasSelecionadas = $_POST['LOJAS'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$cod_statuspag = fnLimpaCampoArray($_REQUEST['COD_STATUSPAG']);
$cod_tipo = fnLimpaCampoArray($_REQUEST['COD_TIPO']);
$log_statusreserva = $_POST['LOG_STATUSRESERVA'];

$cod_propriedade = fnLimpaCampoZero($_POST['COD_PROPRIEDADE']);
$cod_chale = fnLimpaCampoZero($_POST['COD_CHALE']);
$cod_statuspag = fnLimpaCampoZero($_POST['COD_STATUSPAG']);
$filtro_data = fnLimpaCampo($_REQUEST['FILTRO_DATA']);


//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}


if ($cod_propriedade != "" && $cod_propriedade != '9999') {
    $andProp = "AND API.COD_PROPRIEDADE = $cod_propriedade";
} else {
    $andProp = "";
}

if ($cod_chale != "") {
    $andChale = "AND API.COD_CHALE = $cod_chale";
} else {
    $andChale = "";
}

$andStatusReserva = "";
if ($log_statusreserva != "") {
    $andStatusReserva = "AND AP.LOG_STATUSRESERVA = '$log_statusreserva'";
}

if ($cod_statuspag != "") {
    if ($cod_statuspag != 4 && $cod_statuspag != 8) {
        $andStatus = "AND AP.COD_STATUSPAG in ($cod_statuspag)";
    } else {
        $andStatusCancel = "INNER JOIN ADORAI_CANCELAMENTOS AS ACL ON ACL.COD_PEDIDO = AP.COD_PEDIDO";
    }
} else {
    $andStatus = "";
    $andStatusCancel = "";
}

if ($cod_tipo != "") {
    $andTipCredito = "INNER JOIN CAIXA AS CX ON AP.COD_PEDIDO = CX.COD_CONTRAT AND CX.COD_TIPO IN ($cod_tipo)";
} else {
    $andTipCredito = "";
}

if ($filtro_data == "") {
    $filtro_data = "RESERVA";
}

switch ($filtro_data) {
    case 'DEFAULT':
        $andDat = "API.DAT_INICIAL >= '$dat_ini 00:00:00'
            AND API.DAT_FINAL <= '$dat_fim 23:59:59'";
        break;

    case 'RESERVA':
        $andDat = "API.DAT_CADASTR >= '$dat_ini 00:00:00'
            AND API.DAT_CADASTR <= '$dat_fim 23:59:59'";
        break;

    case 'CHECKIN':
        $andDat = "API.DAT_INICIAL >= '$dat_ini 00:00:00'
            AND API.DAT_INICIAL <= '$dat_fim 23:59:59'";
        break;

    case 'CHECKOUT':
        $andDat = "API.DAT_FINAL >= '$dat_ini 00:00:00'
            AND API.DAT_FINAL <= '$dat_fim 23:59:59'";
        break;
}

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT
                                    AP.COD_PEDIDO,
                                    AP.ID_RESERVA,
                                    ASP.DES_STATUSPAG,
                                    AP.NOME,
                                    AP.SOBRENOME,
                                    API.DAT_INICIAL,
                                    API.DAT_FINAL,
                                    APP.NOM_PROPRIEDADE,
                                    AC.NOM_QUARTO,
                                    AC.VAL_EFETIVO,
                                    AP.VALOR,
                                    AP.VAL_CUPOM,
                                    AP.VAL_REFERENCIA_CHALE,
                                    AP.LOG_STATUSRESERVA,
                                    AP.VALOR_PEDIDO
                                    FROM adorai_pedido AS AP
                                    INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
                                    LEFT JOIN adorai_chales AS AC ON API.COD_CHALE = AC.COD_EXTERNO
                                    LEFT JOIN adorai_propriedades AS APP ON API.COD_PROPRIEDADE = APP.COD_HOTEL
                                    LEFT JOIN adorai_statuspag AS ASP ON AP.COD_STATUSPAG = ASP.COD_STATUSPAG
                                    $andTipCredito
                                    $andStatusCancel
                                    WHERE
                                    $andDat
                                    $andStatusReserva
                                    $andProp
                                    $andChale
                                    $andStatus
                                    GROUP BY AP.COD_PEDIDO
    ";
        echo $sql;

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        // Cabeçalhos do CSV
        $CABECHALHO = ['COD_PEDIDO', 'ID_RESERVA', 'STATUS', 'NOME', 'SOBRENOME', 'CHECKIN', 'CHECKOUT', 'PROPRIEDADE', 'CHALE', 'VALOR_PEDIDO', 'DESC_CUPOM', 'VAL_LIQUIDO', 'VAL_PAGPROPRIEDADE', 'CAFE', 'PET', 'FONDUE', 'REFEICAO', 'DECORACAO'];
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
            // Inicializando variáveis de opcionais
            $vlCafe = $vlPets = $vlFond = $vlRef = $vlDec = fnValor(0, 2);

            // Consulta para pegar os opcionais
            $sqlOpci = "SELECT APO.VALOR, OA.ABV_OPCIONAL 
                    FROM adorai_pedido_opcionais AS APO
                    INNER JOIN opcionais_adorai AS OA ON APO.COD_OPCIONAL = OA.COD_OPCIONAL 
                    WHERE COD_PEDIDO = " . $qrListaVendas['COD_PEDIDO'];

            $arrayOpcionais = mysqli_query(connTemp($cod_empresa, ''), $sqlOpci);

            // Atualiza valores opcionais
            while ($qrLista = mysqli_fetch_assoc($arrayOpcionais)) {
                if ($qrLista['ABV_OPCIONAL'] == 'Café') $vlCafe = $qrLista['VALOR'];
                if ($qrLista['ABV_OPCIONAL'] == 'Pets') $vlPets = $qrLista['VALOR'];
                if ($qrLista['ABV_OPCIONAL'] == 'Fondue') $vlFond = $qrLista['VALOR'];
                if ($qrLista['ABV_OPCIONAL'] == 'Refeição') $vlRef = $qrLista['VALOR'];
                if ($qrLista['ABV_OPCIONAL'] == 'Decoração') $vlDec = $qrLista['VALOR'];
            }

            if ($qrListaVendas['VAL_REFERENCIA_CHALE'] > 0) {
                $valEfetivo = $qrListaVendas['VAL_REFERENCIA_CHALE'];
            } else {
                $valEfetivo = $qrListaVendas['VAL_EFETIVO'];
            }

            $val_liquido = $qrListaVendas['VALOR_PEDIDO'] - $qrListaVendas['VAL_CUPOM'];

            // Monta a linha do CSV
            $linha = [
                $qrListaVendas['COD_PEDIDO'],
                $qrListaVendas['ID_RESERVA'],
                $qrListaVendas['DES_STATUSPAG'],
                $qrListaVendas['NOME'],
                $qrListaVendas['SOBRENOME'],
                fnDataShort($qrListaVendas['DAT_INICIAL']),
                fnDataShort($qrListaVendas['DAT_FINAL']),
                $qrListaVendas['NOM_PROPRIEDADE'],
                $qrListaVendas['NOM_QUARTO'],
                fnValor($qrListaVendas['VALOR_PEDIDO'], 2),
                fnValor($qrListaVendas['VAL_CUPOM'], 2),
                fnValor($val_liquido, 2),
                fnValor($valEfetivo, 2),
                fnValor($vlCafe, 2),
                fnValor($vlPets, 2),
                fnValor($vlFond, 2),
                fnValor($vlRef, 2),
                fnValor($vlDec, 2)
            ];

            // Escreve a linha no CSV
            fputcsv($arquivo, $linha, ';', '"', '\n');
        }

        fclose($arquivo);

        break;

    case 'paginar':


        $sql = "SELECT
                                    1
                                    FROM adorai_pedido AS AP
                                    INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
                                    LEFT JOIN adorai_chales AS AC ON API.COD_CHALE = AC.COD_EXTERNO
                                    LEFT JOIN adorai_propriedades AS APP ON API.COD_PROPRIEDADE = APP.COD_HOTEL
                                    LEFT JOIN adorai_statuspag AS ASP ON AP.COD_STATUSPAG = ASP.COD_STATUSPAG
                                    $andTipCredito
                                    $andStatusCancel
                                    WHERE AP.ID_RESERVA != 0 AND
                                    $andDat
                                    $andStatusReserva
                                    $andProp
                                    $andChale
                                    $andStatus
                                    GROUP BY AP.COD_PEDIDO";

        // fnEscreve($sql);
        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        // fnescreve($sql);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        // ================================================================================
        $sql = "SELECT
                                    AP.COD_PEDIDO,
                                    AP.ID_RESERVA,
                                    ASP.DES_STATUSPAG,
                                    AP.NOME,
                                    AP.SOBRENOME,
                                    API.DAT_INICIAL,
                                    API.DAT_FINAL,
                                    APP.NOM_PROPRIEDADE,
                                    AC.NOM_QUARTO,
                                    AC.VAL_EFETIVO,
                                    AP.VALOR,
                                    AP.VAL_CUPOM,
                                    AP.VAL_REFERENCIA_CHALE,
                                    AP.VALOR_PEDIDO
                                    FROM adorai_pedido AS AP
                                    INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
                                    LEFT JOIN adorai_chales AS AC ON API.COD_CHALE = AC.COD_EXTERNO
                                    LEFT JOIN adorai_propriedades AS APP ON API.COD_PROPRIEDADE = APP.COD_HOTEL
                                    LEFT JOIN adorai_statuspag AS ASP ON AP.COD_STATUSPAG = ASP.COD_STATUSPAG
                                    $andTipCredito
                                    $andStatusCancel
                                    WHERE AP.ID_RESERVA != 0 AND
                                    $andDat
                                    $andStatusReserva
                                    $andProp
                                    $andChale
                                    $andStatus
                                    GROUP BY AP.COD_PEDIDO
                                    LIMIT $inicio, $itens_por_pagina
                                    ";
        // fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $count = 0;
        $somaOpcionais = 0;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

            $sqlOpci = "SELECT 
                                        APO.VALOR, 
                                        OA.ABV_OPCIONAL,
                                        OA.LOG_CORTESIA
                                        FROM adorai_pedido_opcionais AS APO
                                        INNER JOIN opcionais_adorai AS OA ON APO.COD_OPCIONAL = OA.COD_OPCIONAL 
                                        WHERE COD_PEDIDO = " . $qrListaVendas['COD_PEDIDO'] . " AND APO.COD_EXCLUSA IS NULL ";

            $array = mysqli_query(connTemp($cod_empresa, ''), $sqlOpci);

            $temCafe = $temPet = $temFound = $temRefeicao = $temDecoracao = '';
            $tot_opcionais = 0;
            while ($qrLista = mysqli_fetch_assoc($array)) {

                if ($qrLista['ABV_OPCIONAL'] == 'Café') {
                    $tot_cafe = $tot_cafe + $qrLista['VALOR'];
                }

                if ($qrLista['ABV_OPCIONAL'] == 'Pets') {
                    $tot_pet = $tot_pet + $qrLista['VALOR'];
                }

                if ($qrLista['ABV_OPCIONAL'] == 'Fondue') {
                    $tot_fond = $tot_fond + $qrLista['VALOR'];
                }

                if ($qrLista['ABV_OPCIONAL'] == 'Refeição') {
                    $tot_ref = $tot_ref + $qrLista['VALOR'];
                }

                if ($qrLista['ABV_OPCIONAL'] == 'Decoração') {
                    $tot_dec = $tot_dec + $qrLista['VALOR'];
                }

                if ($qrLista['LOG_CORTESIA'] == 'N') {
                    $tot_opcionais = $tot_opcionais + $qrLista['VALOR'];
                    $somaOpcionais = $somaOpcionais + $qrLista['VALOR'];
                }

                switch ($qrLista['ABV_OPCIONAL']) {
                    case 'Café':
                        $temCafe .= "<i class='fal fa-coffee' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Café da Manhã - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                        break;
                    case 'Pets':
                        $temPet .= "<i class='fal fa-paw-alt' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Pet - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                        break;
                    case 'Fondue':
                        $temFound .= "<i class='fal fa-chess-queen' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Found - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                        break;
                    case 'Refeição':
                        $temRefeicao .= "<i class='fal fa-utensils' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Refeição - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                        break;
                    case 'Decoração':
                        $temDecoracao .= "<i class='fal fa-holly-berry' class='shortCut' data-toggle='tooltip' data-placement='top' data-original-title='Decoração - " . fnValor($qrLista['VALOR'], 2) . "'></i>&nbsp";
                        break;
                }
            }

            if ($qrListaVendas['VAL_REFERENCIA_CHALE'] > 0) {
                $valEfetivo = $qrListaVendas['VAL_REFERENCIA_CHALE'];
            } else {
                $valEfetivo = $qrListaVendas['VAL_EFETIVO'];
            }

            $val_liquido = ($qrListaVendas['VALOR_PEDIDO'] + $tot_opcionais) - $qrListaVendas['VAL_CUPOM'];
            $val_margem = $val_liquido - $valEfetivo;
            $pct_margem = ($val_margem / $qrListaVendas['VALOR_PEDIDO']) * 100;

            //SQL PARA VERIFICAR SE A RESERVA FOI CANCELADA
            $sqlCancela = "SELECT * FROM ADORAI_CANCELAMENTOS WHERE COD_PEDIDO = " . $qrListaVendas['COD_PEDIDO'];
            $arrayQueryCancela = mysqli_query(connTemp($cod_empresa, ''), $sqlCancela);

            if ($qrBuscaCancela = mysqli_fetch_assoc($arrayQueryCancela)) {
                $cancelada = "background-color: #fdedec;";
            } else {
                $cancelada = "";
            }

?>
            <tr class="addBox" style="cursor: pointer; <?= $cancelada ?>" data-url='action.do?mod=<?php echo fnEncode(2012) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idp=<?php echo fnEncode($qrListaVendas['COD_PEDIDO']) ?>&pop=true' data-title='Detalhes da Reserva'>
                <td><?= $qrListaVendas['COD_PEDIDO']; ?> - <br> <?= $qrListaVendas['ID_RESERVA']; ?></td>
                <td><?= $qrListaVendas['DES_STATUSPAG']; ?></td>
                <td><?= $qrListaVendas['NOME'] . " " . $qrListaVendas['SOBRENOME'] ?></td>
                <td><?= fnDataShort($qrListaVendas['DAT_INICIAL']) ?></td>
                <td><?= fnDataShort($qrListaVendas['DAT_FINAL']) ?></td>
                <td><?= $qrListaVendas['NOM_PROPRIEDADE']; ?></td>
                <td><?= $qrListaVendas['NOM_QUARTO']; ?></td>
                <td class="text-right"><?= fnValor($qrListaVendas['VALOR_PEDIDO'], 2); ?></td>
                <td class="text-right"><?= fnValor($qrListaVendas['VAL_CUPOM'], 2); ?></td>
                <td class="text-right"><?= fnValor($tot_opcionais, 2); ?></td>
                <td class="text-right"><?= fnValor($val_liquido, 2); ?></td>
                <td class="text-right"><?= fnValor($valEfetivo, 2); ?></td>
                <td class="text-center" width="15%">
                    <?php
                    echo $temCafe;
                    echo $temPet;
                    echo $temFound;
                    echo $temRefeicao;
                    echo $temDecoracao;
                    ?>
                </td>
                <td class="text-right"><?= fnValor($val_liquido - $valEfetivo, 2); ?></td>
                <td><?= fnValor($pct_margem, 0); ?> %</td>
            </tr>
<?php

            $count++;
        }
        break;
}
?>