<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../../_system/_functionsMain.php';
//include '../../_system/PHPMailer/class.phpmailer.php';
include '../email/envio_sac.php';

/*$months = array("02", "03","04","05","06","07","08","09");*/
$months = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
foreach ($months as $month) {
    echo "2023-" . $month . '<br>';


    if ($_GET['id'] == '1') {
        $execatual = date('d H:i');
        // $dateexec = date('Y-m', strtotime('-1 month', strtotime(date('Y-m'))));
        $dateexec = date('Y-m', strtotime('-1 month', strtotime("2024-" . $month)));
        if ($_REQUEST['empresa'] != '') {
            $codempresa = " and cod_empresa=" . $_REQUEST['empresa'];
        } else {
            $codempresa = '';
        }
    } else {

        $execatual = '01 02:20';
        $dateexec = date('Y-m', strtotime('-1 month', strtotime(date('Y-m'))));
    }



    $emailDestino = array(
        'email1' => 'diogo_tank@hotmail.com',
        'email5' => 'rone.all@gmail.com;coordenacaoti@markafidelizacao.com.br;'
    );



    $horamenor1 = date('d H:i', strtotime('-3 minute'));
    $horamaior1 = date('d H:i', strtotime('+3 minute'));
    if ($horamenor1 <= $execatual && $horamaior1 >= $execatual) {
        echo 'Hora inicial1:' . $horamenor1 . '<br>';
        echo 'hora servidor1:' . $horamaior1 . '<br>';

        //if(date('d H i')==$execatual)
        //{

        //$dateexec=date('Y-m', strtotime('-1 month', strtotime(date('Y-m'))));
        $conadmmysql = $connAdm->connAdm();
        //capturando as empresas com a comunicação
        $sqlEmpresa = "SELECT COD_EMPRESA,NOM_FANTASI from empresas where LOG_ATIVO='S' and cod_empresa NOT IN (136,514) AND  LOG_INTEGRADORA = 'N' $codempresa";
        $rwempresas = mysqli_query($conadmmysql, $sqlEmpresa);
        while ($rsempresas = mysqli_fetch_assoc($rwempresas)) {
            ob_start();
            $contemporaria = connTemp($rsempresas['COD_EMPRESA'], '');
            //pegar o periodo na com TEMP
            //executar todo domingo se for Igual a S
            $sqlparamentros = "SELECT * FROM FREQUENCIA_CLIENTE WHERE COD_EMPRESA=" . $rsempresas['COD_EMPRESA'];
            $rsparamentros = mysqli_fetch_assoc(mysqli_query($contemporaria, $sqlparamentros));

            if ($rsparamentros['COD_FREQUENCIA'] != '') {
                sleep(2);
                echo 'DATA EXEC: ' . $dateexec . '<br>';
                unset($sqlcategoria);
                $sqlcategoria = "CALL SP_CARGA_ANALYTICS ('" . $dateexec . "' , '" . $rsempresas['COD_EMPRESA'] . "');";
                $execsql = mysqli_query($contemporaria, $sqlcategoria);

                echo '</br>.............................................</br>';
                echo 'dataatual:' . date('d H i') . '<br>';
                echo 'dataexecucao:' . $execatual . '<br>';
                echo 'Nome empresa: ' . $rsempresas['NOM_FANTASI'] . '<br>';
                echo 'Executado na empresa:' . $rsempresas['COD_EMPRESA'] . '</br>';
                echo '.............................................</br>';

                if (!$execsql) {
                    echo 'ErroSQLanalitcs:' . $sqlcategoria . '<br>';
                } else {
                    $rsexecsql = mysqli_fetch_assoc($execsql);
                    mysqli_free_result($execsql);
                    mysqli_next_result($contemporaria);
                    if ($rsexecsql['RETORNO'] == 'N') {
                        echo "iniciando segunda fase";
                        sleep(5);
                        $sqlidsesion = "SELECT MAX(ID_SESSION) V_ID_SESSION 
								FROM VENDABASETMP
								WHERE COD_EMPRESA =" . $rsempresas['COD_EMPRESA'];
                        $rwsqlidsesion = mysqli_query($contemporaria, $sqlidsesion);
                        $rssqlidsesion = mysqli_fetch_assoc($rwsqlidsesion);
                        mysqli_free_result($rwsqlidsesion);
                        mysqli_next_result($contemporaria);

                        $sqlCLIEXPIRAR = "CALL SP_CARGA_CLIEXPIRAR ( '" . $dateexec . "' , '" . $rsempresas['COD_EMPRESA'] . "' , '" . $rssqlidsesion['V_ID_SESSION'] . "');";
                        $execCLIEXPIRAR = mysqli_query($contemporaria, $sqlCLIEXPIRAR);
                        mysqli_free_result($execCLIEXPIRAR);
                        mysqli_next_result($contemporaria);
                        if (!$execCLIEXPIRAR) {
                            echo 'ErroSQLanalitcs' . $sqlCLIEXPIRAR . '<br>';
                            $errosqlexe = $sqlCLIEXPIRAR;
                        } else {
                            echo '...................................<br>';
                            echo 'Nome empresa: ' . $rsempresas['NOM_FANTASI'] . '<br>';
                            echo 'Executado na empresa:' . $rsempresas['COD_EMPRESA'] . ' </br>';
                            echo 'EXECUTOU' . $sqlCLIEXPIRAR . '<br>';
                            echo 'Free result proc Inicio<br>';
                            //mysqli_next_result($contemporaria);
                            echo 'Free result proc FIM<br>';
                            echo '...................................<br>';
                            $errosqlexe = 'OK';
                        }
                    }
                }
                $td .= "<tr>
                            <td>$rsempresas[NOM_FANTASI]</td>
                            <td>$rsempresas[COD_EMPRESA]</td>
                            <td>" . date('d/m/Y H:m:s') . "</td>
                            <td>" . $sqlCLIEXPIRAR . "</td> 
                            <td>" . $errosqlexe . "</td>     
                        </tr>";
            }


            ob_end_flush();
            ob_flush();
            flush();
        }
        fnsacmail(
            $emailDestino,
            "Suporte Marka",
            "<html>
                                        <head>
                                            <title>Carga Analytics</title>
                                            <meta charset='UTF-8'>
                                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                        </head>
                                        <body>
                                            <table border='1'>
                                               <tr>
                                                <th>NOME EMPRESA</th>
                                                <th>Executado na empresa</th>
                                                <th>data execucao</th>
                                                <th>SQL</th>
                                                <th>ERRO</th>
                                              </tr>
                                              $td
                                            </table>    
                                        </body>
                                    </html>
                             ",
            "Carga no analytics",
            "Carga no analytics",
            $connAdm->connAdm(),
            $contemporaria,
            "3"
        );
        mysqli_close($contemporaria);
    } else {
        echo 'FORA do Periodo analytics</br>';
    }
}
