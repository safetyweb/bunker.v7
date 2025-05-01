<?php
include '../../_system/_functionsMain.php';
$admc=$connAdm->connAdm ();
$contmp= connTemp('125','');

$basecliente="select num_cgcecpf from CLIENTES where num_cgcecpf not in ('00000000000','00000000001')";
$rsw=mysqli_query($contmp, $basecliente);
$Contador = 1;

while ($rsc= mysqli_fetch_assoc($rsw))
{ 
    $dataql="select * from log_cpf where  CPF='".fnCompletaDoc($rsc['num_cgcecpf'],'F')."'";
    $rscpf=mysqli_fetch_assoc(mysqli_query($admc, $dataql));
          
    $Contador ++;
    echo   $Contador.'<br>';
}
echo '2';