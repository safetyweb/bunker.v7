<?php
date_default_timezone_set('Etc/GMT+3');
require '../../_system/_functionsMain.php';
//capturar as empresa para executar a rotina de unidade referencia
$empresasSql = "select * from empresas where LOG_ATIVO='S' and cod_empresa NOT IN (136,514)";
$rwempresas = mysqli_query($connAdm->connAdm(), $empresasSql);
while ($rsempresas = mysqli_fetch_assoc($rwempresas)) {
    $timeN = '15:12';
    $horaatual = date('H:i');
    $dia = date('d H:i');
    $ANO = date('m-d H:i');
    $conncliente = connTemp(@$rsempresas['COD_EMPRESA'], '');
    $sqlclientes = "SELECT * FROM EMPRESA_CLASSIFICA WHERE COD_EMPRESA =$rsempresas[COD_EMPRESA];";
    $rwclientes = mysqli_query($conncliente, $sqlclientes);
    while ($rscliente = mysqli_fetch_assoc($rwclientes)) {
        if (@$rscliente['QTD_MESCLASS'] == '0') {
            //executar diariamente
            $horamenor1 = date('H:i', strtotime('-3 minute', strtotime($timeN)));
            $horamaior1 = date('H:i', strtotime('+5 minute', strtotime($timeN)));

            //echo "<br>".strtotime($horamenor1).'<='.strtotime($horaatual).'&&'.strtotime($horamaior1).'>='.strtotime($horaatual);
            if (strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1) >= strtotime($horaatual)) {

                $callproc = "CALL SP_DEFINE_UNIVEND_PREF($rscliente[COD_EMPRESA])";
                $execprco = mysqli_query($conncliente, $callproc);
                if (!$execprco) {
                    echo '<pre>';
                    print_r($execprco);
                    echo '</pre>';
                }
            }
        }
        //roda mensal 
        if (@$rscliente['QTD_MESCLASS'] == '1' && $dia = '02 23:00') {

            $callproc = "CALL SP_DEFINE_UNIVEND_PREF($rscliente[COD_EMPRESA])";
            $execprco = mysqli_query($conncliente, $callproc);
            if (!$execprco) {
                echo '<pre>';
                print_r($execprco);
                echo '</pre>';
            }
        }
        //roda anual
        //roda mensal 
        if (@$rscliente['QTD_MESCLASS'] == '12' && $ANO = '01-02 23:00') {

            $callproc = "CALL SP_DEFINE_UNIVEND_PREF($rscliente[COD_EMPRESA])";
            $execprco = mysqli_query($conncliente, $callproc);
            if (!$execprco) {
                echo '<pre>';
                print_r($execprco);
                echo '</pre>';
            }
        }
    }
}
