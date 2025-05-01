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
 $ID_CARRINHO='';
if($Capturajson[ID_CARRINHO]!='0')
{
    $ID_CARRINHO="ID_CARRINHO=$Capturajson[ID_CARRINHO] and ";
}    
 
$buscaprodcookie="select * from hclient_sback_prod where $ID_CARRINHO ID_COOKIE ='$Capturajson[ID_COOKIE]' and ID_PRODUTO=$Capturajson[ID_PRODUTO]";
$rwbuscaprodcookie= mysqli_query($conadmf, $buscaprodcookie);
if($rwbuscaprodcookie->num_rows <= 0)
{    
    
    $cookieinsert="INSERT INTO hclient_sback_prod (DATA_CADASTRO, ID_CARRINHO, ID_COOKIE, ID_PRODUTO)
                  VALUES ('".date('Y-m-d H:i:s')."', 
                          '$Capturajson[ID_CARRINHO]',
                          '$Capturajson[ID_COOKIE]', 
                          '$Capturajson[ID_PRODUTO]');";
    $cookierw=mysqli_query($conadmf, $cookieinsert);
    if(!$cookierw)
    {    
        $erroinformation='{"errors": [
                                          {
                                           "field": "identificationCode",
                                           "message": "Erro ao inserir a hclient_sback_prod",
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
}else{
   $rsbuscaprodcookie=mysqli_fetch_assoc($rwbuscaprodcookie);
   $QTD_CONSULTA=$rsbuscaprodcookie[QTD_CONSULTA]+1;
   mysqli_next_result($conadmf);
   $updatekookie= "UPDATE hclient_sback_prod SET DATA_VISITA=now() ,QTD_CONSULTA='$QTD_CONSULTA' where ID_COOKIE ='$Capturajson[ID_COOKIE]' and ID_PRODUTO=$Capturajson[ID_PRODUTO]";
   $rwupdatekookie= mysqli_query($conadmf, $updatekookie);
    if(!$rwupdatekookie)
    {    
        $erroinformation='{"errors": [
                                          {
                                           "field": "identificationCode",
                                           "message": "Erro ao inserir a hclient_sback_prod",
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
        
}