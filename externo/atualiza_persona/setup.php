<?php
include '../../_system/_functionsMain.php';
$admc=$connAdm->connAdm ();

$sqlempresa="select cod_empresa,nom_empresa from empresas WHERE LOG_ATIVO='S' AND COD_EMPRESA NOT IN (2,3,136,11)";
$rwempresa= mysqli_query($admc, $sqlempresa);
while($rsempresa= mysqli_fetch_assoc($rwempresa))
{
    
    $contt=connTemp($rsempresa['cod_empresa'], '');
    $sqlatualiza='CALL SP_CARGA_CLIENTES('.$rsempresa['cod_empresa'].')';
    $OK=mysqli_query($contt, $sqlatualiza);
    if(!$OK)
    {
        echo '<br>erro: '.$sqlatualiza.'<br>';
    } else {
         echo '<br>OK :'.$sqlatualiza.'<br>'; 
    }   
}        
