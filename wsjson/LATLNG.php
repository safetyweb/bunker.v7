<?php
exit();
include '../_system/Class_conn.php';
include '../wsmarka/func/function.php';  
@$lat=$_REQUEST['lat'];
@$long=$_REQUEST['long'];
@$UserID=$_REQUEST['UserID'];
$dateatual=date('Y-m-d H:i:s');

//verifica se existe
 $verifica="select * from location where COD_CARTAO=$UserID";
 $rsverifica=mysqli_query(connTemp(19, ''), $verifica);
 $rsresult= mysqli_fetch_assoc($rsverifica);
 mysqli_free_result($rsverifica);
 $cod_externo=date('Y-m-d H:i:s', strtotime('+'.$rsresult['TIME_ATUALIZA'].' minute', strtotime($rsresult['DATE_TIME']))); 
 $d1 = date_parse ($cod_externo);
 $d2 = date_parse ($dateatual);
     
 if($d1['minute'] <= $d2['minute'])
 { 
  
    if($rsresult['COD_CARTAO']!='')
    {
      //update
        
       $cod_externo=date('Y-m-d H:i:s', strtotime('+'.$rsresult['TIME_ATUALIZA'].' minute', strtotime($dateatual))); 
       $update="UPDATE location SET LAT='$lat', `LNG`='$long',DATE_TIME='$cod_externo',LOG_ATIVO=1,IP='".$_SERVER['REMOTE_ADDR']."' WHERE  COD_CARTAO='$UserID';";
       $rsverifica=mysqli_query(connTemp(19, ''), $update);
       mysqli_free_result($rsverifica);
       unset($_REQUEST);
       unset($_POST);
       unset($_GET);  
    }else{
      //insert into   
      $insert="INSERT INTO location (LAT, LNG, COD_CARTAO,DATE_TIME,IP) VALUES ('$lat', '$long', '$UserID',now(),'".$_SERVER['REMOTE_ADDR']."');";          
      $rsverifica=mysqli_query(connTemp(19, ''), $insert);
      mysqli_free_result($rsverifica);
      unset($_REQUEST);
      unset($_POST);
      unset($_GET);
    }    

  
 }
 
 
          $json = array("lat" =>$lat, 
                        "long"=>$long,
                        "UserID"=>$UserID
                       );   
              
            header('Content-type: application/json');
            echo  json_encode($json);
                    
            ?>