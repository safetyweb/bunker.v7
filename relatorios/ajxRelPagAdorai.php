<?php

include '../_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$des_owner = fnLimpacampo($_REQUEST['DES_OWNER']);
$cod_pedido = fnLimpaCampoZero($_REQUEST['COD_PEDIDO']);


if ($des_owner != "9999") {
    $andowner = "AND CX.DES_OWNER = '$des_owner'";
    $andOwn = "AND C.DES_OWNER = '$des_owner'";
} else {
    $andowner = "";
    $andOwn = "";
}

switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        //============================

        $sql = "SELECT 
                    SUM(CASE WHEN CX.COD_TIPO = 1 THEN VAL_CREDITO ELSE 0 END) AS VAL_CREDITOS,
                    SUM(CASE WHEN CX.COD_TIPO = 2 THEN VAL_CREDITO ELSE 0 END) AS VAL_DEBITOS,
                    (SUM(CASE WHEN CX.COD_TIPO = 1 THEN VAL_CREDITO ELSE 0 END) - SUM(CASE WHEN CX.COD_TIPO = 2 THEN VAL_CREDITO ELSE 0 END)) AS SALDO_TOTAL,
                    AC.NOM_QUARTO,
                    UNV.NOM_UNIVEND
                    FROM caixa AS CX
                    INNER JOIN adorai_pedido_items AS AP ON  AP.COD_PEDIDO = CX.COD_CONTRAT
                    INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AP.COD_CHALE
                    INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AP.COD_PROPRIEDADE
                    WHERE AP.COD_EMPRESA = 274
                    AND CX.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                    GROUP BY CX.COD_CONTRAT";

        //fnEscreve($sql);
        // echo $sql;
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['VAL_CREDITOS'] = fnValor($row['VAL_CREDITOS'], 2);
            $row['VAL_DEBITOS'] = fnValor($row['VAL_DEBITOS'], 2);
            $row['SALDO_TOTAL'] = fnValor($row['SALDO_TOTAL'], 2);
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            // $textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);
        break;

    case 'exportar2':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        //============================

        $sql = "SELECT
                (SELECT SUM(C.val_credito)
                FROM caixa AS c
                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                WHERE p.COD_EMPRESA = 274
                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                    AND c.cod_contrat = AP.COD_PEDIDO
                    AND TC.TIP_OPERACAO = 'C'
                    $andOwn
                    AND C.DAT_CADASTR BETWEEN '2024-11-01 00:00:00' AND '2024-11-01 23:59:59' ) AS VAL_CREDITOS,

                (SELECT SUM(val_credito)
                FROM caixa AS c
                INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                WHERE p.COD_EMPRESA = 274
                    AND p.COD_PEDIDO = AP.COD_PEDIDO
                    AND c.cod_contrat = AP.COD_PEDIDO
                    AND TC.TIP_OPERACAO = 'D'
                    $andOwn
                    AND C.DAT_CADASTR BETWEEN '2024-11-01 00:00:00' AND '2024-11-01 23:59:59' ) AS VAL_DEBITOS,
                    AC.COD_HOTEL,
                    tp.ABV_TIPO,
                    CB.NOM_BANCO
                FROM caixa AS CX
                INNER JOIN tip_credito AS tp ON tp.COD_TIPO = cx.COD_TIPO
                INNER JOIN adorai_pedido_items AS AP ON AP.COD_PEDIDO = CX.COD_CONTRAT
                INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AP.COD_CHALE
                INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AP.COD_PROPRIEDADE
                LEFT JOIN CONTABANCARIA AS CB ON CB.COD_CONTA = CX.COD_CONTA
                WHERE AP.COD_EMPRESA = 274
                AND CX.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                $andowner";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row['VAL_CREDITOS'] = fnValor($row['VAL_CREDITOS'], 2);
            $row['VAL_DEBITOS'] = fnValor($row['VAL_DEBITOS'], 2);
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            // $textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        break;

    case 'conciliar':

        if ($cod_pedido != "") {
            $sql = "UPDATE ADORAI_PEDIDO SET
                    LOG_CONCILIADO = 'S'
                    WHERE COD_PEDIDO = $cod_pedido";
            $arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

            if ($arrayProc) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }

        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Opção inválida.']);
        break;
}
