<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';

if($_SERVER[REQUEST_METHOD]!='POST')
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "O metodo para capturar deve ser POST",
                                     "coderro": "400"
                                    }
                                ]
                     }';   
    $erroinformation=json_decode(json_encode($erroinformation));                 
    echo $erroinformation;
    exit();
}  
 $passmarka= getallheaders();
if(!array_key_exists('authorizationCode', $passmarka))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
}    

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);

//validação do usuario
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut['0']."', '".fnEncode($arraydadosaut['1'])."','','','".$arraydadosaut['4']."','','')";
$buscauser=mysqli_query($connAdm->connAdm(),$sql);
if(empty($buscauser->num_rows)) 
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400"
                                     }
                                ]
                   }';
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
} 
//abrindo a com temporaria
$conexaotmp= connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);