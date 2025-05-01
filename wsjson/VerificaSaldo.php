<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../_system/_functionsMain.php';

$passmarka= getallheaders();
if(!array_key_exists('authorizationCode', $passmarka))
{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}
$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);
//validação do usuario
$admconn=$connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut['0']."', '".fnEncode($arraydadosaut['1'])."','','','".$arraydadosaut['4']."','','')";
$buscauser=mysqli_query($admconn,$sql);
if(empty($buscauser->num_rows)) 
{
    http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}   
$user=mysqli_fetch_assoc($buscauser);
//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp= connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa
if(!array_key_exists('4', $arraydadosaut))
{

   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}
$Capturajson= json_decode(file_get_contents("php://input"),true);

$dados_cliente=mysqli_fetch_assoc(mysqli_query($conexaotmp,"select * from clientes where num_cgcecpf=".$Capturajson['CPF']." and cod_empresa=".$arraydadosaut['4'])); 

$sqlsaldo="CALL total_wallet(".$dados_cliente[COD_CLIENTE].",".$arraydadosaut['4'].")";
$rwsaldo=mysqli_fetch_assoc(mysqli_query($conexaotmp,$sqlsaldo));
print(json_encode($rwsaldo,JSON_PRETTY_PRINT));

