<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once './oderfunctions.php';
require_once '../func/function.php';
require_once '../../_system/Class_conn.php';

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
     echo $erroinformation;
     exit();  
}    

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);

$array['clientes']=array(
                          array(
                                    'nome'=>'',
                                    'cartao'=>'',
                                    'cpf'=>'',
                                    'sexo'=>'',
                                    'rg'=>'',
                                    'cnpj'=>'',
                                    'nomeportador'=>'',
                                    'grupo'=>'',
                                    'datanascimento'=>'',
                                    'estadocivil'=>'',
                                    'telresidencial'=>'',
                                    'telcomercial'=>'',
                                    'telcelular'=>'',
                                    'email'=>'',
                                    'profissao'=>'',
                                    'clientedesde'=>'',
                                    'tipocliente'=>'',
                                    'endereco'=>'',
                                    'numero'=>'',
                                    'bairro'=>'',
                                    'complemento'=>'',
                                    'cidade'=>'',
                                    'estado'=>'',
                                    'cep'=>'',
                                    'cartaotitular'=>'',
                                    'bloqueado'=>'',
                                    'motivo'=>'',
                                    'dataalteracao'=>'',
                                    'adesao'=>'',
                                    'codatendente'=>'',
                                    'tokencadastro'=>'',
                                    'funcionario'=>'',
                                    'senha'=>'',
                                    'fontedados'=>'',
                                    'canal'=>''
                                    ),
                                array(
                                            'nome'=>'',
                                            'cartao'=>'',
                                            'cpf'=>'',
                                            'sexo'=>'',
                                            'rg'=>'',
                                            'cnpj'=>'',
                                            'nomeportador'=>'',
                                            'grupo'=>'',
                                            'datanascimento'=>'',
                                            'estadocivil'=>'',
                                            'telresidencial'=>'',
                                            'telcomercial'=>'',
                                            'telcelular'=>'',
                                            'email'=>'',
                                            'profissao'=>'',
                                            'clientedesde'=>'',
                                            'tipocliente'=>'',
                                            'endereco'=>'',
                                            'numero'=>'',
                                            'bairro'=>'',
                                            'complemento'=>'',
                                            'cidade'=>'',
                                            'estado'=>'',
                                            'cep'=>'',
                                            'cartaotitular'=>'',
                                            'bloqueado'=>'',
                                            'motivo'=>'',
                                            'dataalteracao'=>'',
                                            'adesao'=>'',
                                            'codatendente'=>'',
                                            'tokencadastro'=>'',
                                            'funcionario'=>'',
                                            'senha'=>'',
                                            'fontedados'=>'',
                                            'canal'=>''
                                            )
                                );
echo json_encode($array,JSON_PRETTY_PRINT);