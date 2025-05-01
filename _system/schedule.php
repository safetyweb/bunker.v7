<?php

include '_functionsMain.php';
//echo fnDebug('true');
$_SESSION["usuario"]='s';
$cmdPage='s'; 


$sql = "select * from schedule";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

while($qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery)){
     //$hora=date('H:i:s',strtotime($qrBuscaEmpresa['HORA_INI']." + 5 minutes"))."<br>";
     //$data=$qrBuscaEmpresa['DATA_INI'];
     //$fullmais5=$data.' '.$hora;
     //=======================================
     //$cad=$qrBuscaEmpresa['DATA_INI'].' '.$qrBuscaEmpresa['HORA_INI'];
     
        if($qrBuscaEmpresa['LOG_ATIVO'] == 'S'){     
    
    
           
            
            
     //echo "<br>data cad=====>".$cad." data mais5=====>". $fullmais5.'<br>';
     


                    $DES_INTERVAL=$qrBuscaEmpresa['DES_INTERVALO'];
                    $URL_SCHEDULE=$qrBuscaEmpresa['URL_SCHEDULE'];
                    $nomeArquivo=$qrBuscaEmpresa['COD_SCHEDULE'].'_'.$qrBuscaEmpresa['ABV_SCHEDULE'];

                        $mypath="/etc/cron.d";
                        $filename = $mypath."/".$nomeArquivo;
                        $somecontent =$DES_INTERVAL."  root curl -s ".$URL_SCHEDULE. " >> /testeschedule/$nomeArquivo.txt";
                    // echo $somecontent.'<BR>';
                // ob_start();
                // echo $somecontent;
                // $resultado = ob_get_contents();
                // ob_end_clean();
                // $ok = file_put_contents($filename, $resultado);
                 //if ($ok) {
                  //   print 'Arquivo gravado com sucesso.<br>';
                  //   print '<a href="cron.txt">Clique aqui para visualizar</a>';
                    // echo "echo ". "'".$somecontent."' > ".$filename."<br>";
                shell_exec("echo '".$somecontent."' > ".$filename);
                //} else {
                //    print 'Ocorreu um erro. Verifique as permissões.';
                //}        
         
        }      
    
}   
   
?>

