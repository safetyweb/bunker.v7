<?php
include '../_system/_functionsMain.php';
$sql_empresa='SELECT COD_EMPRESA FROM empresas WHERE log_ativo="S"';
$rwempresa= mysqli_query($connAdm->connAdm(), $sql_empresa);
$contado='0';
$dat_inicial='2007-01-01 00:00:00';
$dat_final='2007-01-31 23:59:59';
    
while ($rsempresa= mysqli_fetch_assoc($rwempresa))
{
    
    
    $contemp= connTemp($rsempresa['COD_EMPRESA'], '');
    $begin = new DateTime($dat_inicial);
    $end = new DateTime($dat_final);

        $interval = DateInterval::createFromDateString('+1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            //echo $dt->format(" Y-m-d").'<br>';
            $sqlcliente="SELECT * FROM clientes  WHERE DAT_CADASTR BETWEEN '$dat_inicial' AND '$dat_final'";
            echo'<br>'.$sqlcliente.'<br>';
            
        }
     
    $dat_inicial=date('Y-m-d', strtotime('+1 days', strtotime($dat_final))).' 00:00:00';
    $dat_final=date('Y-m-d', strtotime('+30 days', strtotime($dat_inicial))).' 23:59:59';
    
   $contado ++;
}        
