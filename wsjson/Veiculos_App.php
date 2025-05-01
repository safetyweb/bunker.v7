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
      echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);
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
      echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);
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
       echo json_decode(json_encode($erroinformation),JSON_PRETTY_PRINT);
     exit();  
}
$Capturajson= json_decode(file_get_contents("php://input"),true);
$usuario = preg_replace('/[^0-9]/', '',htmlspecialchars(mysqli_real_escape_string($conexaotmp,fnLimpaCampo($Capturajson['CPF'])), ENT_QUOTES, 'UTF-8'));
$COD_CLI = preg_replace('/[^0-9]/', '',htmlspecialchars(mysqli_real_escape_string($conexaotmp,fnLimpaCampo($Capturajson['COD_CLIENTE'])), ENT_QUOTES, 'UTF-8'));
if(empty($COD_CLI))
{
  $cpfc="v.COD_CLIENTE_EXT=$usuario"; 
}else{
   $cpfc="c.COD_CLIENTE=$COD_CLI"; 
}    

$placas="SELECT v.DES_PLACA,v.COD_CLIENTE,v.COD_CLIENTE_EXT,c.COD_ENTIDAD FROM veiculos v
        INNER JOIN clientes c ON c.COD_CLIENTE=v.COD_CLIENTE
       WHERE $cpfc AND v.cod_empresa=".$arraydadosaut['4'];
$dadosplcas=mysqli_query($conexaotmp, $placas);
$pdadosPlaca=mysqli_fetch_all($dadosplcas,MYSQLI_ASSOC);
echo json_encode($pdadosPlaca,JSON_PRETTY_PRINT);

// Verifica se houve algum erro durante a decodificação
/*if (json_last_error() === JSON_ERROR_NONE) {
    // A string JSON é válida
    echo 'JSON válido:';
    print_r('OK');
} else {
    // A string JSON não é válida
    echo 'JSON inválido. Erro: ' . json_last_error_msg();
}*/