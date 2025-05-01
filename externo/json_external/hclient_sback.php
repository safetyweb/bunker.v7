<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once '../../_system/_functionsMain.php';

if(@$_SERVER[REQUEST_METHOD]!='POST')
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
if(empty($Capturajson['ID_COOKIE']) || $Capturajson['ID_COOKIE']=='0'){
    
    
    $DATA_VALIDADECOOKIE=date('Y-m-d H:i:s', strtotime('+1 year', strtotime(date('Y-m-d H:i:s'))));    
    @$cookieinsert="INSERT INTO hclient_sback (ID_CLIENTE, DATA_CADASTRO, DATA_VALIDADE) VALUES ('".$Capturajson[ID_CLIENTE]."', '".date('Y-m-d H:i:s')."', '".$DATA_VALIDADECOOKIE."');";
    $cookierw=mysqli_query($conadmf, $cookieinsert);
    if(!$cookierw)
    {    
        $erroinformation='{"errors": [
                                        {
                                         "field": "identificationCode",
                                         "message": "Erro ao inserir a hclient_sback",
                                         "locationType": "body"
                                        }
                                    ]
                         }';    
         echo $erroinformation;
         exit(); 
    }
     $COD_COOKIE= mysqli_insert_id($conadmf); 
     $erroinformation='{"identification": [
                                                {
                                                 "ID_COOKIE": "'.$COD_COOKIE.'",
                                                 "DATA_VALIDADE": "'.$DATA_VALIDADECOOKIE.'"
                                                }
                                            ]
                        }';    
         echo $erroinformation;
         exit(); 
        
} else {
        $ID_CLIENTE='';
        if($Capturajson[ID_CLIENTE]!='' && $Capturajson[ID_CLIENTE]!='0')
        {
           $ID_CLIENTE="ID_CLIENTE=".$Capturajson[ID_CLIENTE]." and "; 
        }    
    
       $retornoconsula="SELECT * FROM  hclient_sback  WHERE $ID_CLIENTE ID_COOKIE=".$Capturajson['ID_COOKIE'];
       $rsCOOKIE= mysqli_fetch_assoc(mysqli_query($conadmf, $retornoconsula));
       $erroinformation='{"identification": [
                                                {
                                                 "ID_COOKIE": "'.$rsCOOKIE[ID_COOKIE].'",
                                                 "DATA_VALIDADE": "'.$rsCOOKIE[DATA_VALIDADE].'"
                                                }
                                            ]
                        }';    
         echo $erroinformation;
         exit(); 
}