<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once '../../_system/_functionsMain.php';

if($_SERVER[REQUEST_METHOD]!='POST')
{
     http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "field": "identificationCode",
                                     "message": "O metodo para capturar deve ser POST",
                                     "locationType": "body"
                                    }
                                ]
                     }';    
     echo $erroinformation;
     exit();
}

$Capturajson=json_decode(file_get_contents("php://input"),true);

if(empty($Capturajson))
{
   $erroinformation='{"errors": [
                                    {
                                     "field": "identificationCode",
                                     "message": "Por favor Enviar a chave na consulta",
                                     "locationType": "body"
                                    }
                                ]
                     }';    
     echo $erroinformation;
     exit(); 
}    
 $conadmf=$connAdm->connAdm ();   
 $sqlfin="SELECT DATA_CADASTRO,ID_PRODUTO FROM hclient_sback_prod WHERE ID_COOKIE=$Capturajson[ID_COOKIE] AND  DATE (DATA_CADASTRO)<=CURDATE() AND STATUS_CAR=0 order BY DATA_CADASTRO desc LIMIT 15";
 $rwfin=mysqli_query($conadmf, $sqlfin);
 $rsfin=mysqli_fetch_all($rwfin, MYSQLI_ASSOC);
 echo json_encode($rsfin,JSON_PRETTY_PRINT);