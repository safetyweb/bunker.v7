<?php
header("Content-Type: application/json; charset=utf-8");
/*
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$seconds_to_cache = 3600;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Last-Modified: $ts");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");*/
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
include '../_system/_functionsMain.php';
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
      echo $erroinformation;
      exit();
}

$sqlbuscli="select * from clientes where num_cgcecpf='".fnDecode(base64_decode($arrayjson[RD_userId]))."' and cod_empresa=".$arrayjson[RD_userCompany];
$rsbuscli=mysqli_fetch_assoc(mysqli_query(connTemp($arrayjson[RD_userCompany],''), $sqlbuscli));    
$retun=array(
            "RD_userId"=>      $rsbuscli[COD_CLIENTE],
            "RD_userCompany"=> $rsbuscli[COD_EMPRESA],
            "RD_userMail"=>    $rsbuscli[DES_EMAILUS],
            "RD_userName"=>    $rsbuscli[NOM_CLIENTE],
            "RD_userpass"=>    fndecode($rsbuscli[DES_SENHAUS]),
            "RD_userType"=>    $rsbuscli[COD_TPCLIENTE],
            "RD_TokenCelular"=> NULL,
            "RD_Versao"=> NULL,
            "CPF"=>  $rsbuscli[NUM_CGCECPF]
           );

// adicionando flag para tipar automaticamente índices numéricos na conversão do array pra json
echo json_encode($retun,JSON_NUMERIC_CHECK);