<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$seconds_to_cache = 3600;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Last-Modified: $ts");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
if($_SERVER[REQUEST_METHOD]!='POST')
{
     http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "O metodo para capturar deve ser POST"
                                     }
                                ]
                     }';    
     echo $erroinformation;
     exit();
}  
include_once '../_system/_functionsMain.php';
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);

//PRIEMIRO PASSO E VERIFICAR SE A EMPRESA PASSA EXISTE OU SE ESTA ATIVA
//conn adm
$connadm=$connAdm->connAdm();

$SQLEMPRESA="SELECT COD_EMPRESA, LOG_ATIVO,COD_EXCLUSA FROM EMPRESAS WHERE COD_EMPRESA=".$arrayjson[RD_userCompany];
$RWEMPRESA=mysqli_fetch_assoc(mysqli_query($connadm, $SQLEMPRESA));
if(empty($RWEMPRESA))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                     {
                                      "message": "Empresa inexistente ou desabilitada"
                                     }
                                 ]
                      }';    
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
      exit();
}

/*
    [RD_userId] => 12
    [RD_userCompany] => 19
    [RD_userMail] => diogo_tank@hotmail.com
    [RD_userName] => diogo
    [RD_userType] => 11
    [RD_TokenCelular] => sknsdlgsdgldglsdbglsdgnsdlgsdblghsdlgksdgjkshg
)
 */
$conn = connTemp($arrayjson[RD_userCompany], '');
//verificar se o token existe
$SQLPUSH="SELECT * FROM cliente_push WHERE COD_EMPRESA=$arrayjson[RD_userCompany] AND COD_CLIENTE='".$arrayjson[RD_userId]."' AND TIP_COMUNICACAO='1'";
$RWPUSH=mysqli_fetch_assoc(mysqli_query($conn, $SQLPUSH));

if(empty($RWPUSH))
{
    
  //INSERIR  SE O CODIGO DO CLIENTE NAO EXISTIR
    $SQLINT= "INSERT INTO cliente_push (
                                        COD_EMPRESA,
                                        COD_CLIENTE, 
                                        TOKEN, 
                                        DAT_CADASTR, 
                                        TIP_COMUNICACAO,
                                        VERSAO_SISTEMA,
                                        USER_PLAYER_ID
                                        ) VALUES(
                                        $arrayjson[RD_userCompany], 
                                        $arrayjson[RD_userId], 
                                        '".$arrayjson[RD_User_Player_Id]."', 
                                        NOW(), 
                                        1,
                                        '".$arrayjson[RD_Versao]."',
                                        '".$arrayjson[RD_TokenCelular]."'    
                                        );"; 
    $RWINT=mysqli_query($conn, $SQLINT);
    //AND TOKEN='".$arrayjson[RD_TokenCelular]."'  
}else{
    
    $SQLUP="UPDATE  cliente_push SET TOKEN='".$arrayjson[RD_User_Player_Id]."',
                                     USER_PLAYER_ID='".$arrayjson[RD_TokenCelular]."',
                                     VERSAO_SISTEMA='".$arrayjson[RD_Versao]."',   
                                     DAT_ALTERAC=now()
                           WHERE COD_EMPRESA=$arrayjson[RD_userCompany] AND 
                                 COD_CLIENTE='".$arrayjson[RD_userId]."' AND 
                                 TIP_COMUNICACAO='1';";
     $RWUP=mysqli_query($conn, $SQLUP);
}

 $erroinformation='{"errors": [
                                {
                                 "message": "OK"
                                }
                               ]
                      }';    
$erroinformation=json_decode(json_encode($erroinformation));
echo $erroinformation;


