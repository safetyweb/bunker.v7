<?php 

include "_system/_functionsMain.php"; 
function fnMemoria($Opcao,$conn){
  $datahora=DATE("d/m/Y H:i:s");
    If($Opcao=='true')
    { 
        $mem_usage = memory_get_usage(true); 
       
       
            $unit=array('b','kb','mb','gb','tb','pb');
            $MEN=@round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
            $logqueryinsert="insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ('".$MEN."','".$_GET['mod']."','".$datahora."','diogo');";
            mysqli_query($conn,$logqueryinsert) or die(mysqli_error());
       
 }elseif ($Opcao=='false')
            {      
                $unit=array('b','kb','mb','gb','tb','pb');
                $MEN=@round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
                $SqlUpdate="UPDATE log_men SET NEM_PICO='".$MEN."',ativo=1 WHERE  PAGINA='".$_GET['mod']."' and DATA_HORA='".$datahora."'";
                mysqli_query($conn,$SqlUpdate) or die(mysqli_error());
                }


                //Memoria final
               $mem_usageFIN = memory_get_usage(true); 
                $unit2=array('b','kb','mb','gb','tb','pb');
                $MEN2=@round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit2[$i];
                $SqlUpdate="UPDATE log_men SET NEM_FINAL='".$MEN2."' WHERE    ativo=1 and PAGINA='".$_GET['mod']."' and DATA_HORA='".$datahora."'";
                mysqli_query($conn,$SqlUpdate) or die(mysqli_error());
             
               
} 

fnMemoria('true',$connAdm->connAdm());
     
     ?>