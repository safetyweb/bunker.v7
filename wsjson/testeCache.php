<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Cache-Control: max-age=3600");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 3600) . " GMT"); // Exemplo: expira em 1 hora
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
ob_start("ob_gzhandler");


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

$Capturajson= json_decode(file_get_contents("php://input"),true);
$usuario = preg_replace('/[^0-9]/', '',htmlspecialchars(fnLimpaCampo($Capturajson['CPF']), ENT_QUOTES, 'UTF-8'));
$senha = htmlspecialchars($Capturajson['SENHA'], ENT_QUOTES, 'UTF-8');

$nom_arquivo= base64_encode($usuario.''.$senha);
// Definir o tempo de expiração do cache (em segundos)
$cache_time = 3600;
// Verificar se há cache disponível
$cache_file = './cache/'.$nom_arquivo.'.txt';
if (file_exists($cache_file) && time() - $cache_time < filemtime($cache_file)) {
    // Ler os dados do cache
    $cache_data = file_get_contents($cache_file);
    
    // Exibir os dados do cache
    echo $cache_data;
} else {
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
        //$user=mysqli_fetch_assoc($buscauser);
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
       

        $sql = "SELECT COD_CLIENTE,NUM_CGCECPF,DES_EMAILUS,DES_SENHAUS,COD_ENTIDAD FROM CLIENTES WHERE NUM_CGCECPF = '$usuario' AND DES_SENHAUS='". fnEncode($senha)."' AND COD_EMPRESA = ".$arraydadosaut['4']." LIMIT 1";
        $result = mysqli_query($conexaotmp,trim($sql));	
        if($result->num_rows >=1)
        {    
            $dadoslogin=mysqli_fetch_assoc($result);
            //consulta de placa
            $placas="SELECT DES_PLACA as PLACAS FROM veiculos WHERE COD_CLIENTE=".$dadoslogin['COD_CLIENTE']." AND cod_empresa=".$arraydadosaut['4'];
            $dadosplcas=mysqli_query($conexaotmp, $placas);
            $pdadosPlaca=mysqli_fetch_all($dadosplcas,MYSQLI_ASSOC);

            $usuEncrypt = fnEncode($dadoslogin["NUM_CGCECPF"]);
            $key = base64_encode(fnEncode($arraydadosaut['4']));
            $idL = base64_encode(fnEncode($dadoslogin["NUM_CGCECPF"])."|".$dadoslogin['DES_SENHAUS']);

            $ARRAYdados=array('cod_cliente'=>$dadoslogin['COD_CLIENTE'],
                              'auth'=>true,
                              'key'=> $key, 
                              'idU'=> $usuEncrypt, 
                              'idL'=> $idL, 
                              'entidade'=> $dadoslogin['COD_ENTIDAD'], 
                              'veiculos'=>$pdadosPlaca
                              );
            $data= json_encode($ARRAYdados,JSON_PRETTY_PRINT);
            // Gerar os dados para serem armazenados no cache
            // Salvar os dados no cache
            file_put_contents($cache_file, $data);
           // Exibir os dados gerados
            echo $data;
            exit();

        }else{
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
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
}