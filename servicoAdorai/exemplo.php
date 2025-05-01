<?php
include '../_system/_functionsMain.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

http_response_code(400);
$erroinformation = '{"errors": [
                                     {
                                      "message": "Empresa inexistente ou desabilitada"
                                     }
                                 ]
                      }';
$erroinformation = json_decode(json_encode($erroinformation));
echo $erroinformation;
exit();

$erroinformation = '{"errors": [
                                {
                                 "message": "OK"
                                }
                               ]
                      }';
$erroinformation = json_decode(json_encode($erroinformation));
