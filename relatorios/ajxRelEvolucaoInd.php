<?php

include '../_system/_functionsMain.php';
// echo $_SESSION['SYS_COD_EMPRESA'];

// if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
//     echo fnDebug('true');
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
// }

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("America/Sao_Paulo");

$opcao = $_GET['opcao'];
$itens_por_pagina = 0;
if (isset($_GET['itens_por_pagina'])) {
    $itens_por_pagina = $_GET['itens_por_pagina'];
}

$pagina = 0;
if (isset($_GET['idPage'])) {
    $pagina = $_GET['idPage'];
}

$cod_empresa = fnDecode($_GET['id']);
$num_cgcecpf = "";
if (isset($_POST['NUM_CGCECPF'])) {
    $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
}

$num_cartao = "";
if (isset($_POST['NUM_CARTAO'])) {
    $num_cartao = fnLimpaCampo($_POST['NUM_CARTAO']);
}

$casasDec = 2;
if (isset($_REQUEST['CASAS_DEC'])) {
    $casasDec = $_REQUEST['CASAS_DEC'];
}

$dat_ini = "";
if (isset($_POST['DATA_INI'])) {
    $dat_ini = $_POST['DATA_INI'];
}

$dat_fim = "";
if (isset($_POST['DATA_FIM'])) {
    $dat_fim = $_POST['DATA_FIM'];
}

$lojasSelecionadas = "";
if (isset($_POST['LOJAS'])) {
    $lojasSelecionadas = $_POST['LOJAS'];
}

$cod_controle = "";
if (isset($_POST['COD_CONTROLE'])) {
    $cod_controle = $_POST['COD_CONTROLE'];
}

$dat_ini = date('Y-m-d', strtotime($dat_ini));
$dat_fim = date('Y-m-t', strtotime($dat_fim));

if (!is_dir('../media/excel/')) {
    mkdir('../media/excel/', 0777, true);
}
$CABECHALHO = [];

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


            $dataObj = new DateTime($mes . '-01');
            $indice = substr(ucfirst($dataObj->format("F")), 0, 3) . "/" . $dataObj->format("Y");

            $selectValues .= "SUM(PCT_DIARIO$anoLoop$mesLoop) '" . $indice . "',";
            $caseWhen .= "CASE WHEN DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\") = '" . $mes . "' THEN ROUND(((SUM(QTD_TOTFIDELIZ)/ SUM(QTD_TOTVENDA))*100),2) ELSE 0 END AS PCT_DIARIO$anoLoop$mesLoop,";
            array_push($meses, $indice);
        }

        $selectValues = rtrim($selectValues, ",");
        $caseWhen = rtrim($caseWhen, ",");

        $sql = "SELECT COD_UNIVEND, 
                       NOM_FANTASI,
                       $selectValues
                FROM(
                    SELECT vendas_diarias.COD_UNIVEND, uni.NOM_FANTASI, 
                           $caseWhen
                    FROM vendas_diarias
                    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=vendas_diarias.COD_UNIVEND
                    WHERE DAT_MOVIMENTO BETWEEN '$dat_ini' AND '$dat_fim' 
                    AND uni.COD_UNIVEND IN($lojasSelecionadas)
                    GROUP BY COD_UNIVEND, DATE_FORMAT(DAT_MOVIMENTO, \"%Y-%m\")
                ) tmpvendasmovi
                GROUP BY COD_UNIVEND";

        $arrQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        if (!$arrQuery) {
            die("Erro na consulta SQL: " . mysqli_error(connTemp($cod_empresa, '')) . "<br>Query: " . $sql);
        }

        $arrResult = array();

        $arquivo = fopen($arquivoCaminho, 'w');

        while ($headers = mysqli_fetch_field($arrQuery)) {
            $CABECHALHO[] = $headers->name;
        }

        fputcsv($arquivo, $CABECHALHO, ';', '"', '"');

        // Dados
        while ($qrMes = mysqli_fetch_assoc($arrQuery)) {
            foreach ($mesesIntervalo as $mes) {
                $dataObj = new DateTime($mes . '-01');
                $indice = substr(ucfirst($dataObj->format("F")), 0, 3) . "/" . $dataObj->format("Y");
                $qrMes[$indice] = fnValor($qrMes[$indice], 2);
            }
            $array = array_map("utf8_decode", $qrMes);
            fputcsv($arquivo, $array, ';', '"', '"');
        }

        fclose($arquivo);
        break;
}
