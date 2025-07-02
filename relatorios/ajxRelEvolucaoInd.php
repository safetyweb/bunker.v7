<?php

include '../_system/_functionsMain.php';

fnEscreveArray($_REQUEST);
exit;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("america/sao_paulo");

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
$num_cartao = fnLimpaCampo($_POST['NUM_CARTAO']);
$casasDec = $_REQUEST['CASAS_DEC'];
$dat_ini = $_POST['DATA_INI'];
$dat_fim = $_POST['DATA_FIM'];
$lojasSelecionadas = $_POST['LOJAS'];
$cod_controle = $_POST['COD_CONTROLE'];

$dat_ini = date('Y-m-d', strtotime($dat_ini));
$dat_fim = date('Y-m-t', strtotime($dat_fim));


switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $start    = new DateTime($dat_ini);
        $start->modify('first day of this month');
        $end      = new DateTime($dat_fim);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $mesesIntervalo = "";

        foreach ($period as $dt) {
            $mesesIntervalo .= $dt->format("Y-m") . ",";
        }

        $mesesIntervalo = rtrim($mesesIntervalo, ",");
        $mesesIntervalo = explode(",", $mesesIntervalo);

        $selectValues = "";
        $caseWhen = "";
        $meses = array();

        foreach ($mesesIntervalo as $mes) {

            $dataLoop = explode("-", $mes);

            $anoLoop = $dataLoop[0];
            $mesLoop = $dataLoop[1];
            $concatData = $anoLoop . "-" . $mesLoop;

            $indice = substr(ucfirst(strftime("%B", strtotime($mes . '-01'))), 0, 3) . "/" . date("Y", strtotime($mes . '-01'));

            $selectValues .= "SUM(PCT_DIARIO$anoLoop$mesLoop) '" . $indice . "',";
            $caseWhen .= "CASE WHEN DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\") = '" . $mes . "' THEN ROUND(((SUM(QTD_TOTFIDELIZ)/ SUM(QTD_TOTVENDA))*100),2) ELSE 0 END AS PCT_DIARIO$anoLoop$mesLoop,";
            array_push($meses, $indice);
        }

        $selectValues = rtrim($selectValues, ",");
        $caseWhen = rtrim($caseWhen, ",");


        $sql = "SELECT  COD_UNIVEND, 
                            NOM_FANTASI,

                            $selectValues
            FROM(
            SELECT vendas_diarias.COD_UNIVEND, uni.NOM_FANTASI, 
            $caseWhen
            FROM vendas_diarias
            INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=vendas_diarias.COD_UNIVEND
            WHERE DAT_MOVIMENTO BETWEEN '$dat_ini' AND '$dat_fim' AND uni.COD_UNIVEND IN($lojasSelecionadas)
            GROUP BY COD_UNIVEND,DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\"))tmpvendasmovi
            GROUP BY COD_UNIVEND";

        // fnEscreve($sql);
        // exit();

        $arrQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arrResult = array();

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($qrMes = mysqli_fetch_assoc($arrQuery)) {

            foreach ($mesesIntervalo as $mes) {


                $indice = substr(ucfirst(strftime("%B", strtotime($mes . '-01'))), 0, 3) . "/" . date("Y", strtotime($mes . '-01'));

                $qrMes[$indice] = fnValor($qrMes[$indice], 2);
            }

            $array = array_map("utf8_decode", $qrMes);
            fputcsv($arquivo, $array, ';', '"', '\n');
        }
        fclose($arquivo);
        break;
}
