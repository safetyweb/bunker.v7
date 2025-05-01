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
 $sqlfechacar="UPDATE hclient_sback_prod SET STATUS_CAR='1' WHERE ID_COOKIE=$Capturajson[ID_COOKIE] and ID_CARRINHO='$Capturajson[ID_CARRINHO]' and ID_PRODUTO in ($Capturajson[ID_PRODUTO]);";
 $rwfechacar=mysqli_query($conadmf, $sqlfechacar);
if(!$rwfechacar)
{
    $erroinformation='{"errors": [
                                          {
                                           "field": "identificationCode",
                                           "message": "Erro ao inserir a hclient_sback_con",
                                           "locationType": "body"
                                          }
                                      ]
                           }';    
        echo $erroinformation;
        exit(); 
}    
$erroinformation='{"identification": [
                                                {
                                                 "Menssagem": "OK",
                                                 "COD": "200"
                                                }
                                            ]
                        }';    
echo $erroinformation;
exit(); 