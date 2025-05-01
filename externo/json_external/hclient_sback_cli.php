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
    $updatecli="UPDATE hclient_sback SET ID_CLIENTE='$Capturajson[ID_CLIENTE]',
                                         TELEFONE='$Capturajson[TELEFONE]',
										 EMAIL='$Capturajson[EMAIL]'
                                  	WHERE  ID_COOKIE=$Capturajson[ID_COOKIE];";
    $rwupdatecli=mysqli_query($conadmf,$updatecli);
    if(!$rwupdatecli)
    {    
        $erroinformation='{"errors": [
                                        {
                                         "field": "identificationCode",
                                         "message": "Erro ao inserir a hclient_sback_cli",
                                         "locationType": '.$updatecli.'
                                        }
                                    ]
                         }';    
         echo $erroinformation;
         exit(); 
    }else{
         $erroinformation='{"identification": [
                                                {
                                                 "Menssagem": "OK",
                                                 "COD": "200"
                                                }
                                            ]
                        }';    
         echo $erroinformation;
         exit(); 
    }