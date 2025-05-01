<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 300");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  http_response_code(400);
  $erroinformation = '{"errors": [
                                            {
                                             "msgerro": "Esse metodo so aceita POST",                                
                                             "coderro": "400"
                                            }
                                         ]
                              }';
  echo $erroinformation;
  exit();
}


include '../../_system/_functionsMain.php';

$dadosenvio = json_decode(file_get_contents("php://input"), true);
$passmarka = getallheaders();
//gravando Llog

if (!array_key_exists('authorizationCode', $passmarka)) {
  http_response_code(400);
  $erroinformation = '{"errors": [
                        {
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body"
                        },
                       ]
                      }';
  echo $erroinformation;
  exit();
}
$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
$autorizlimpo = fnLimpaCampo($autoriz);
if (!$autorizlimpo) {

  http_response_code(400);
  $erroinformation = '{"errors": [
                        {
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body"
                        },
                       ]
                      }';
  echo $erroinformation;
  exit();
}
//verificar se a empresa existe no cadastro do sistema

$empresaVerifica = "SELECT COD_EMPRESA FROM empresas WHERE cod_empresa=" . $autorizlimpo . "  AND LOG_ATIVO='S'";
$rwVerifica = mysqli_query($connAdm->connAdm(), $empresaVerifica);
if ($rwVerifica->num_rows <= '0') {
  http_response_code(400);
  $erroinformation['errors'] = array(
    "message" => "Empresa não existe ou desabilitada!",
    "locationType" => "body"
  );
  echo  json_encode($erroinformation, JSON_PRETTY_PRINT);
  exit();
}
//captura dados do body
$Capturajson = json_decode(file_get_contents("php://input"), true);
//abrindo conn temporaria
//retornando o token
$Sqltoken = " SELECT g.DES_TOKEN,g.DAT_CADASTR,g.LOG_USADO,r.DES_MSG_ENVIADA FROM geratoken g
            inner join rel_geratoken r ON g.COD_TOKEN=r.COD_GERATOKEN
                            WHERE 
                            g.NUM_CGCECPF = " . $Capturajson['Cpf'] . " and 
                            g.NUM_CELULAR = " . $Capturajson['Telefone'] . " and 
                            g.cod_empresa = " . $autorizlimpo . " and 
                            g.COD_EXCLUSA = 0  and
                            g.TIP_TOKEN=1
                            order by g.COD_TOKEN desc LIMIT 1 ";
$rwtoken = mysqli_fetch_assoc(mysqli_query(connTemp($autorizlimpo, ''), $Sqltoken));
if (empty($rwtoken)) {
  http_response_code(400);
  $erroinformation['errors'] = array(
    "message" => "Ops não conseguimos localizar seu Token!",
    "locationType" => "body"
  );
  echo  json_encode($erroinformation, JSON_PRETTY_PRINT);
  exit();
}

//verificar blk sms
$sqlblk = "SELECT 1 AS temnao FROM blacklist_sms WHERE cod_empresa='" . $autorizlimpo . "' AND num_celular='" . $Capturajson['Telefone'] . "'";
$rwblk = mysqli_query(connTemp($autorizlimpo, ''), $sqlblk);
if ($rwblk->num_rows > '0') {
  http_response_code(400);
  $erroinformation['errors'] = array(
    "message" => "Esse numero de celular é invalido ou foi bloqueado pelo gestor de sua empresa!",
    "locationType" => "body"
  );
  echo  json_encode($erroinformation, JSON_PRETTY_PRINT);
  exit();
}
//contador de numeros 
if (strlen($Capturajson['Telefone']) < 11) {
  http_response_code(400);
  $erroinformation['errors'] = array(
    "message" => "Esse numero de celular é invalido!",
    "locationType" => "body"
  );
  echo  json_encode($erroinformation, JSON_PRETTY_PRINT);
  exit();
}

echo json_encode($rwtoken, JSON_PRETTY_PRINT);
