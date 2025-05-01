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
/*
 {
    "COD_CLIENTE":"690594",
    "PLACA":"sdsfsdfsdf",
    "CPF": "01734200014"
}
  $conexaotmp= connTemp($arraydadosaut['4'], '');
 */

$Capturajson= json_decode(file_get_contents("php://input"),true);
$Capturajson['COD_CLIENTE'];

if($Capturajson['TIP_TOKEN'] == "RESGATE"){

    $sqlToken = "SELECT * FROM token_resgate 
                 WHERE NUM_CGCECPF = '$Capturajson[NUM_CGCECPF]'
                 AND COD_EMPRESA = $arraydadosaut[4]
                 AND COD_MSG = 0";
    $arrayToken = mysqli_query(connTemp($arraydadosaut[4],''), $sqlToken);

    $des_placa = fnLimpaCampo(fnDecode($_GET['idp']));
    // $placa = fnDecode($_GET['idp']);

    if(mysqli_num_rows($arrayToken) == 0){

      // fnEscreve($des_placa);

        do {

            $senha = fngeraSenha(6, true, true, true);
            
            $sqlToken = "SELECT 1 FROM token_resgate 
                        WHERE DES_TOKEN = '$senha'
                        AND COD_EMPRESA = $arraydadosaut[4]";

            $arrayToken = mysqli_query(connTemp($arraydadosaut[4],''),$sqlToken);

            $existeTkn = mysqli_num_rows($arrayToken);

        } while ($existeTkn > 0);

        $gravatokem="INSERT INTO token_resgate
                   (DES_TOKEN, 
                   num_cgcecpf,
                   cod_empresa, 
                   dat_cadastr,
                   des_placa,
                   cod_msg
                   ) 
                   VALUES ('".addslashes($senha)."', 
                            '".$Capturajson['NUM_CGCECPF']."', 
                            $arraydadosaut[4],
                            '".date('Y-m-d H:i:s')."',
                            '".$Capturajson[DES_PLACA]."',
                            0   
                            );";
        // echo($gravatokem);
        mysqli_query(connTemp($arraydadosaut[4],''), $gravatokem); 


    }else{

        $qrToken = mysqli_fetch_assoc($arrayToken);

        $gravatokem="UPDATE token_resgate SET des_placa = '$des_placa' 
        WHERE DES_TOKEN = '$qrToken[DES_TOKEN]' 
        AND NUM_CGCECPF = '$Capturajson[NUM_CGCECPF]'
        AND COD_EMPRESA = $arraydadosaut[4]";

        mysqli_query(connTemp($arraydadosaut[4],''), $gravatokem);

        $senha = array('DES_TOKEN'=>$qrToken['DES_TOKEN']);

        echo json_encode($senha,true);

    }

}else{

    $sqlproc="CALL SP_VERIFICA_TOKEN('$arraydadosaut[4]', '".$Capturajson['COD_CLIENTE']."')";
    $returnproc=mysqli_fetch_assoc(mysqli_query(connTemp($arraydadosaut[4],''), $sqlproc));
     //fnEscreve($sqlproc);
    if($returnproc['v_RESULTADO']=='S' && $Capturajson['NUM_CGCECPF'] != 0 && $Capturajson['NUM_CGCECPF'] != ""){    

      $sqlToken = "SELECT * FROM TOKEM WHERE COD_CLIENTE = '".$Capturajson['NUM_CGCECPF']."' AND LOG_USADO = 'N'";
      $arrayToken = mysqli_query(connTemp($arraydadosaut[4],''), $sqlToken);

      $des_placa = fnLimpaCampo(fnDecode($_GET['idp']));

      if(mysqli_num_rows($arrayToken) == 0){

        // fnEscreve($des_placa);

        do {

          $senha = fngeraSenha(6, true, true, true);
          
          $sqlToken = "SELECT 1 FROM tokem WHERE DES_TOKEM = '$senha'";

          $arrayToken = mysqli_query(connTemp($arraydadosaut[4],''),$sqlToken);

          $existeTkn = mysqli_num_rows($arrayToken);

        } while ($existeTkn > 0);



        $gravatokem="INSERT INTO tokem 
                     (des_tokem, 
                     cod_cliente, 
                     dat_cadastr, 
                     cod_loja,
                     des_placa
                     ) 
                     VALUES ('".addslashes($senha)."', 
                              '".$Capturajson['NUM_CGCECPF']."', 
                              '".date('Y-m-d H:i:s')."', 
                              '".$Capturajson['COD_ENTIDAD']."',
                              '".$Capturajson['DES_PLACA']."'    
                              );";

        // fnEscreve($gravatokem);
        mysqli_query(connTemp($arraydadosaut[4],''), $gravatokem);

      }else{

        $qrToken = mysqli_fetch_assoc($arrayToken);

        $gravatokem="UPDATE tokem SET des_placa = '$Capturajson[DES_PLACA]' 
                     WHERE DES_TOKEM = '$qrToken[des_tokem]' 
                     AND COD_CLIENTE = '".$Capturajson['NUM_CGCECPF']."'";
        /*if($Capturajson['NUM_CGCECPF']=='01734200014')
        {
            echo $gravatokem;
        }*/
        mysqli_query(connTemp($arraydadosaut[4],''), $gravatokem);

        $senha = array('DES_TOKEN'=>$qrToken['des_tokem']);

        echo json_encode($senha,true);

      }
    
}}