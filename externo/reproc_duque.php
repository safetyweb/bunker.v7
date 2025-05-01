<?php
include '../_system/_functionsMain.php';
$connimport=$Import->connAdm ();
$lista="select * from import.duque_repro WHERE COD_CREDITO=0";
$rw=mysqli_query($connimport, $lista);
while ($rs = mysqli_fetch_assoc($rw)) {
    
    $co= "CALL sp_refaz_resgate('".$rs['pdv']."', '19',".$rs['resgate'].");";
    mysqli_query(connTemp(19, ''), $co);
    sleep(2);
    
}
//CALL sp_refaz_resgate('12921488737', '19',35.91);
//CALL SP_REPROCESSA_CREDITO_NOTURNO_ADILSON_MANUAL('12')