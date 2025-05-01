<?php
include '../../../_system/_functionsMain.php';
include '../funcao.php';

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
                         "field": "identificationCode",
                         "message": "O metodo para capturar deve ser POST",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                   }';    
     echo $erroinformation;
     exit();
}  
if($_SERVER[REQUEST_METHOD]!='POST')
{
     http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "O metodo para capturar deve ser POST",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
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
                         "field": "identificationCode",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                   }';    
     echo $erroinformation;
     exit();  
}    

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);
if(!array_key_exists('4', $arraydadosaut))
{

    http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "Informe uma chave de acesso valida!",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                    }';    
     echo $erroinformation;
     exit();  
}

$cod_empresa=$arraydadosaut[4];
$cod_univend=$arraydadosaut[2];
$connadm=$connAdm->connAdm();
$conncliente= connTemp($cod_empresa, '');
$Capturajson=file_get_contents("php://input");

$file='../aquivosX/identificatio'.date('YmdHis').'.txt';
file_put_contents($file, $Capturajson);
//==============fim=================
$arrayjson=json_decode($Capturajson,true);

/*$arrayString = print_r($arrayjson, true);
$file='../log/teste_t123456.txt';
file_put_contents($file, $arrayString);*/
//================fim================
//abrir conexão

//verificar se todos os campos obrigatoris estão sendo prenchidos.


$CAMPOSSQL="select 
                 KEY_CAMPOOBG,  
                 case emp.COD_CHAVECO 
                                    when 1 then 'CPF/CNPJ'  
                                    when 2 then 'CARTÃO PRE CADASTRADO'	
                                    when 3 then 'CELULAR'	
                                    when 4 then 'CODIGO EXTERNO'	
                                    when 5 then 'CPF/CNPJ+CARTAO'	
                                    when 6 then 'CPF/CNPJ/NASC/CEL/EMAIL'			
                                 ELSE 'outros' END CHAVE,
                                 emp.COD_CHAVECO 

                  from matriz_campo_integracao  
                   INNER JOIN empresas emp ON emp.COD_EMPRESA= matriz_campo_integracao.COD_EMPRESA                    
                   inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
                   where matriz_campo_integracao.COD_EMPRESA=".$cod_empresa."
                   and matriz_campo_integracao.TIP_CAMPOOBG IN('OBG','OPC') GROUP BY KEY_CAMPOOBG;
                ";

$CAMPOQUERY= mysqli_query($connadm, $CAMPOSSQL);
while ($CAMPOROW= mysqli_fetch_assoc($CAMPOQUERY))
{ 

  $campos[$CAMPOROW[KEY_CAMPOOBG]]=$CAMPOROW[KEY_CAMPOOBG];
  $chave=$CAMPOROW[COD_CHAVECO];
}

//      
if($chave=='1' || $chave=='2' || $chave=='5'){
    
    if(!fnvalidacpf($arrayjson[identification][document]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "document",
                              "message": "Documento digitado e invalido !",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }    
    
    
   //verificar se a chave cpf esta preenchido
    if(empty($arrayjson[identification][document]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "document",
                              "message": "Por favor preencha o documento!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                       }';    
          echo $erroinformation;
          exit();  
    }    
     if(empty($arrayjson[identification][identificationCode]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "identificationCode",
                              "message": "Por favor preencha o identificationCode!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }    
   
} else {
     if( in_array( "cartao" ,$campos))
     { 
           //verificar se a chave cpf esta preenchido
            if(empty($arrayjson[identification][document]))
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                     {
                                      "field": "document",
                                      "message": "Por favor preencha o documento!",
                                      "locationType": "body",
                                      "location": "https://homol.marka.com/pages/api_inicio"
                                     }
                                    ]
                               }';    
                  echo $erroinformation;
                  exit();  
            }
            if(empty($arrayjson[identification][identificationCode]))
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                     {
                                      "field": "identificationCode",
                                      "message": "Por favor preencha o identificationCode!",
                                      "locationType": "body",
                                      "location": "https://homol.marka.com/pages/api_inicio"
                                     }
                                    ]
                              }';    
                  echo $erroinformation;
                  exit();  
            }
     
     }
}
//chave de telefone 
if($chave=='3'){
    if(empty($arrayjson[identification][phone]))
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "phone",
                              "message": "Por favor preencha o Telefone!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                       }';    
          echo $erroinformation;
          exit();  
    }    
} else {
    if( in_array( "telcelular" ,$campos))
    { 
        if(empty($arrayjson[identification][phone]))
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "phone",
                                  "message": "Por favor preencha o Telefone!",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                            }';    
              echo $erroinformation;
              exit(); 
        }
        
    }  
}

    if( in_array( "datanascimento" ,$campos))
    { 
        if(empty($arrayjson[identification][birthday]))
        {
            http_response_code(400);
           $erroinformation='{"errors": [
                                {
                                 "field": "birthday",
                                 "message": "Por favor preencha o Data de Nascimento!",
                                 "locationType": "body",
                                 "location": "https://homol.marka.com/pages/api_inicio"
                                }
                               ]
                            }';    
             echo $erroinformation;
             exit(); 
        } 
    }  
    if( in_array( "email" ,$campos))
    {
        if(empty($arrayjson[identification][email]))
        {
            http_response_code(400);
           $erroinformation='{"errors": [
                                {
                                 "field": "email",
                                 "message": "Por favor preencha o email!",
                                 "locationType": "body",
                                 "location": "https://homol.marka.com/pages/api_inicio"
                                }
                               ]
                           }';    
             echo $erroinformation;
             exit(); 
        } 
        
    } 
    if( in_array( "nome" ,$campos))
    { 
        if(empty($arrayjson[identification][name]))
        {
            http_response_code(400);
           $erroinformation='{"errors": [
                                {
                                 "field": "name",
                                 "message": "Por favor preencha o name!",
                                 "locationType": "body",
                                 "location": "https://homol.marka.com/pages/api_inicio"
                                }
                               ]
                            }';    
             echo $erroinformation;
             exit(); 
        } 
    }   
    if( in_array( "sexo" ,$campos))
    {
        if(empty($arrayjson[identification][gender]))
        {
            http_response_code(400);
           $erroinformation='{"errors": [
                                {
                                 "field": "gender",
                                 "message": "Por favor preencha o sexo!",
                                 "locationType": "body",
                                 "location": "https://homol.marka.com/pages/api_inicio"
                                }
                               ]
                           }';    
             echo $erroinformation;
             exit(); 
        }
     } 
    
    if(empty($arrayjson[sale][netSaleValue]))
    {
        http_response_code(400);
       $erroinformation='{"errors": [
                            {
                             "field": "netSaleValue",
                             "message": "Por favor preencha o netSaleValue!",
                             "locationType": "body",
                             "location": "https://homol.marka.com/pages/api_inicio"
                            }
                           ]
                          }';    
         echo $erroinformation;
         exit(); 
    }
     if($arrayjson[sale][netSaleValue]<='0.00')
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                             {
                              "field": "netSaleValue",
                              "message": "Valor total de venda deve ser maior que R$ 0.00!",
                              "locationType": "body",
                              "location": "https://homol.marka.com/pages/api_inicio"
                             }
                            ]
                        }';    
          echo $erroinformation;
          exit();  
    }
    if(empty($arrayjson[identification][operatorCode]))
    {
        http_response_code(400);
       $erroinformation='{"errors": [
                            {
                             "field": "operatorCode",
                             "message": "Por favor preencha o  operatorCode!",
                             "locationType": "body",
                             "location": "https://homol.marka.com/pages/api_inicio"
                            }
                           ]
                        }';    
         echo $erroinformation;
         exit(); 
    }
     if(empty($arrayjson[identification][operatorName]))
    {
        http_response_code(400);
       $erroinformation='{"errors": [
                            {
                             "field": "operatorName",
                             "message": "Por favor preencha o Operador!",
                             "locationType": "body",
                             "location": "https://homol.marka.com/pages/api_inicio"
                            }
                           ]
                        }';    
         echo $erroinformation;
         exit(); 
    }
 
//verificação de sexo
    if((strtoupper($arrayjson[identification][gender])!=='MASCULINO') &&
      (strtoupper($arrayjson[identification][gender])!='FEMININO') && (strtoupper($arrayjson[identification][gender])!='OUTROS'))
    {
         http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "gender",
                                  "message": "Por favor preencha o sexo com MASCULINO , FEMININO ou OUTROS!",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                          }';    
              echo $erroinformation;
              exit(); 
    }    

//PRIMEIRO PASSO VERIFICAR SE O TOKEN ESTA ATIOVO PARA O ENVIO
$tokenativo="SELECT LOG_CADTOKEN FROM empresas WHERE cod_empresa=$cod_empresa";
$rwtokenativo=mysqli_fetch_assoc(mysqli_query($connadm, $tokenativo));
if($rwtokenativo['LOG_CADTOKEN']=='S')
{
     
     //verificar se o cliente tem cadastro e token
    $verificacliente="SELECT DES_TOKEN,LOG_TERMO FROM clientes WHERE  cod_empresa=$cod_empresa AND num_cgcecpf='".$arrayjson[identification][document]."'";
    $rwcliente= mysqli_fetch_assoc(mysqli_query($conncliente, $verificacliente));
    if($rwcliente[DES_TOKEN] == '0' || $rwcliente[DES_TOKEN] == '')
    {
        //verifica sexo 
       
        if(strtoupper($arrayjson[identification][gender])=='MASCULINO')
        {
            $sexo=1;
        }elseif (strtoupper($arrayjson[identification][gender])=='FEMININO') {
            $sexo=2;
        }else{
            $sexo=3; 
        }    
        
        // gerar token  submeter no retorno
        //codigo 1 e para atualizar ou inserir na authentication
        //gerar token aqui
        
        $xml_geratoken='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                        <soapenv:Header/>
                        <soapenv:Body>
                           <fid:Geratoken>
                              <tipoGeracao>1</tipoGeracao>
                              <nome>'.fnAcentos($arrayjson[identification][name]).'</nome>
                              <cpf>'.$arrayjson[identification][document].'</cpf>
                              <celular>'.$arrayjson[identification][phone].'</celular>
                              <email></email>    
                                <dadosLogin>
                                            <login>'.$arraydadosaut[0].'</login>
                                            <senha>'.$arraydadosaut[1].'</senha>
                                            <idloja>'.$arraydadosaut[2].'</idloja>
                                            <idcliente>'.$arraydadosaut[4].'</idcliente>
                                </dadosLogin>
                           </fid:Geratoken>
                        </soapenv:Body>
                     </soapenv:Envelope>'; 
    
        $tokenretorno=TOKEN($xml_geratoken);
        $tokengardo=$tokenretorno[body][envelope][body][geratokenresponse][retornatoken][token];
        $coderro=$tokenretorno[body][envelope][body][geratokenresponse][retornatoken][coderro];
        if($coderro=='39')
        {    
            $logtotvs="INSERT INTO log_tots (COD_EMPRESA, AUTENTICACAO, CPF, TOKEN, COD_TIP, LOG_JSON,CELULAR) VALUES 
                                  ('$cod_empresa', 
                                   '".$passmarka[marka]."',
                                   '".$arrayjson[identification][document]."',
                                   '".$tokengardo."',
                                   '1', 
                                  '".serialize($Capturajson)."','".$arrayjson[identification][phone]."');";
           mysqli_query($conncliente, $logtotvs);
           
           
           $autenticacao='{
                                "partnerCode": "1002",
                                "nextStep":"authentication",
                                "customerText":"",
                                "operatorText":"Solicite os dados para autenticação no programa de Bônus.",
                                "authentication":
                                                {
                                                    "type":"pin",
                                                    "code":"'.$tokengardo.'",
                                                    "operatorText":"Solicite o PIN para o Cliente.",
                                                    "customerText":"",
                                                    "isPassword":true
                                                },
                                "identification":
                                                {
                                                        "costumerId":"'.$arrayjson[identification][document].'"
                                                },
                                "bonus":null,
                                "_expandables":[]
                            }';
           echo $autenticacao;
          
        }else{
          //TOKEN NAO ENVIADO!;
            http_response_code(400);
            $erroinformation='{"errors": [
                                 {
                                  "field": "identificationCode",
                                  "message": "'.$tokenretorno[body][envelope][body][geratokenresponse][retornatoken][msgerro].'",
                                  "locationType": "body",
                                  "location": "https://homol.marka.com/pages/api_inicio"
                                 }
                                ]
                               }';    
              echo $erroinformation;
              exit(); 
           
        }
    }else{
        //atualização de cadastro tem que ser aqui.
        
        //ir para o bonus e exibir o saldo
        // consulta  cliente api
        // validar resgate 
        $xmlbusca=' <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                <soapenv:Header/>
                <soapenv:Body>
                   <fid:BuscaConsumidor>
                      <fase>fase1</fase>
                      <opcoesbuscaconsumidor>
                         <cartao>'.$arrayjson[identification][document].'</cartao>
                         <cpf>'.$arrayjson[identification][document].'</cpf>
                      </opcoesbuscaconsumidor>
                     <dadosLogin>
                        <login>'.$arraydadosaut[0].'</login>
                        <senha>'.$arraydadosaut[1].'</senha>
                        <idloja>'.$arraydadosaut[2].'</idloja>
                        <idcliente>'.$arraydadosaut[4].'</idcliente>
                    </dadosLogin>
                   </fid:BuscaConsumidor>
                </soapenv:Body>
                </soapenv:Envelope>';
        $arrayconsultacliente=fnvalidaconsumidor($xmlbusca);
    
        /*$arrayString = print_r($arrayconsultacliente, true);
        $file='../log/teste'.$arrayjson[identification][document].'.txt';
        file_put_contents($file, $arrayString); 
       */
       
        //antes de alterar verificar se tem alteração no cadastrastro
         if($arrayjson[identification][gender]=='Masculino')
            {
                $sexo=1;
            }elseif ($arrayjson[identification][gender]=='Feminino') {
                $sexo=2;
            }else{
                $sexo=3; 
            }    
        if($arrayjson[identification][name]!=$arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][nome] ||
           $arrayjson[identification][phone]!=$arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][telcelular] ||
           $arrayjson[identification][email]!=$arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][email] ||
           date("d/m/Y", strtotime($arrayjson[identification][birthday])) != $arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][datanascimento] ||
           $sexo!= $arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][sexo]
           )
        {
             $atendente=$arrayjson[identification][operatorCode].'-'.$arrayjson[identification][operatorName];
                //converter a data de nascimento
                $newDatensc = date("d/m/Y", strtotime($arrayjson[identification][birthday]));
                $cadws='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                                <soapenv:Header/>
                                <soapenv:Body>
                                        <fid:AtualizaCadastro>
                                                <fase>fase1</fase>
                                                <cliente>
                                                        <nome>'. fnAcentos($arrayjson[identification][name]).'</nome>
                                                        <cartao>'.$arrayjson[identification][document].'</cartao>
                                                        <cpf>'.$arrayjson[identification][document].'</cpf>
                                                        <sexo>'.$sexo.'</sexo>
                                                        <cnpj>'.$arrayjson[identification][document].'</cnpj>
                                                        <datanascimento>'.$newDatensc.'</datanascimento>
                                                        <telcelular>'.$arrayjson[identification][phone].'</telcelular>
                                                        <email>'.$arrayjson[identification][email].'</email>
                                                        <tipocliente>F</tipocliente>
                                                        <codatendente>'.$atendente.'</codatendente>
                                                        <tokencadastro>'.$arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_a_cadastro][tokencadastro].'</tokencadastro>
                                                        <canal>1</canal>
                                                        <adesao>CT</adesao>    
                                                </cliente>
                                                <dadosLogin>
                                                        <login>'.$arraydadosaut[0].'</login>
                                                        <senha>'.$arraydadosaut[1].'</senha>
                                                        <idloja>'.$arraydadosaut[2].'</idloja>
                                                        <idcliente>'.$arraydadosaut[4].'</idcliente>
                                                </dadosLogin>
                                        </fid:AtualizaCadastro>
                                </soapenv:Body>
                        </soapenv:Envelope>';
                            $cadastr=fncadastro($cadws);
        }        
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        if($arrayconsultacliente['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_h_saldo']['saldodisponivel']=='0,00')                                
        {  
                /*$bonus='{
                            "partnerCode": "1002",
                            "nextStep": "finalize",
                            "customerText": "",
                            "operatorText": "Voce pode concluir a venda e continuar acumulando",
                             "identification":{
                                                    "costumerId": "'.$arrayjson[identification][document].'"
                                              }
                        }';*/
            $bonus='{
                        "partnerCode": "1002",
                        "nextStep": "bonus",
                        "customerText": "",
                        "operatorText": "Selecione um Bônus",
                        "identification":{
                                        "costumerId": "'.$arrayjson[identification][document].'"
                                     }
                    }';
            /*$arrayString = print_r($bonus, true);
            $file='../log/teste'.$arrayjson[identification][document].'.txt';
            file_put_contents($file, $arrayString); */
        
        }else{
            //VALIDAR O SALDO ANTES DE ENVIAR PARA A BONUS OU FINALIZAÇÃO DA VENDA
            $resgate= $arrayconsultacliente['body']['envelope']['body']['buscaconsumidorresponse']['buscaconsumidorresponse']['acao_h_saldo']['saldodisponivel'];
            
            
            /*if($arrayjson[sale][netSaleValue]<= $arrayconsultacliente[body][envelope][body][buscaconsumidorresponse][buscaconsumidorresponse][acao_h_saldo][saldodisponivel])
            {
               $resgate= $arrayjson[sale][netSaleValue];
            } */   

            for ($i=1;$i <='2';$i++) {         
                sleep(0.25); 
                $descontovenda='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <fid:ValidaDescontos>
                                        <cpfcnpj>'.$arrayjson[identification][document].'</cpfcnpj>
                                        <cartao>'.$arrayjson[identification][document].'</cartao> 
                                        <valortotalliquido>'.$arrayjson[sale][netSaleValue].'</valortotalliquido>
                                        <valor_resgate>'.$resgate.'</valor_resgate>
                                        <dadosLogin>
                                                    <login>'.$arraydadosaut[0].'</login>
                                                    <senha>'.$arraydadosaut[1].'</senha>
                                                    <idloja>'.$arraydadosaut[2].'</idloja>
                                                    <idcliente>'.$arraydadosaut[4].'</idcliente>
                                        </dadosLogin>
                                    </fid:ValidaDescontos>
                                </soapenv:Body>
                             </soapenv:Envelope>';
               /* if($arrayjson[identification][document]=='44159787894')
                {     
                  $arrayString = print_r($arrayconsultacliente, true);
                  $file='../log/identificatio'.date('YmdHis').'.txt';
                  file_put_contents($file, $descontovenda); 
                }*/ 
                // $arrayString = print_r($arrayconsultacliente, true);
                /*    $file='../log/teste'.$arrayjson[identification][document].'.txt';
                  file_put_contents($file, $descontovenda);   
                  */
                $descontos=fnvalidaconsumidor($descontovenda);
                if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='49')
                {
                    $resgate=$descontos[body][envelope][body][validadescontosresponse][validadescontos][maximoresgate];
                } 
            }
            if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='52')
            {  
                $bonus='{
                            "partnerCode": "1002",
                            "nextStep": "bonus",
                            "customerText": "",
                            "operatorText": "Selecione um Bônus",
                            "identification":{
                                            "costumerId": "'.$arrayjson[identification][document].'"
                                         }
                        }';
            }else{
                $bonus='{
                       "partnerCode": "1002",
                        "nextStep": "finalize",
                        "customerText": "",
                        "operatorText": "Voce pode concluir a venda e continuar acumulando",
                         "identification":{
                                                "costumerId": "'.$arrayjson[identification][document].'"
                                          }
                    }';
            }
        }   
         echo $bonus;
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    }    
    
    
}else{  
   
    //verificação de sexo
    if(strtoupper($arrayjson[identification][gender])=='MASCULINO')
    {
        $sexo=1;
    }elseif (strtoupper($arrayjson[identification][gender])=='FEMININO') {
        $sexo=2;
    }else{
        $sexo=3; 
    }    

    $atendente=$arrayjson[identification][operatorCode].'-'.$arrayjson[identification][operatorName];
    //converter a data de nascimento
    $newDatensc = date("d/m/Y", strtotime($arrayjson[identification][birthday]));
    $cadws='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
                    <soapenv:Header/>
                    <soapenv:Body>
                            <fid:AtualizaCadastro>
                                    <fase>fase1</fase>
                                    <cliente>
                                            <nome>'.$arrayjson[identification][name].'</nome>
                                            <cartao>'.$arrayjson[identification][document].'</cartao>
                                            <cpf>'.$arrayjson[identification][document].'</cpf>
                                            <sexo>'.$sexo.'</sexo>
                                            <cnpj>'.$arrayjson[identification][document].'</cnpj>
                                            <datanascimento>'.$newDatensc.'</datanascimento>
                                            <telcelular>'.$arrayjson[identification][phone].'</telcelular>
                                            <email>'.$arrayjson[identification][email].'</email>
                                            <tipocliente>F</tipocliente>
                                            <codatendente>'.$atendente.'</codatendente>

                                    </cliente>
                                    <dadosLogin>
                                            <login>'.$arraydadosaut[0].'</login>
                                            <senha>'.$arraydadosaut[1].'</senha>
                                            <idloja>'.$arraydadosaut[2].'</idloja>
                                            <idcliente>'.$arraydadosaut[4].'</idcliente>
                                    </dadosLogin>
                            </fid:AtualizaCadastro>
                    </soapenv:Body>
            </soapenv:Envelope>';
    $chegou=fncadastro($cadws); 
    
    if($chegou[body][envelope][body][atualizacadastroresponse][atualizacadastroresponse][acao_h_saldo][saldodisponivel]=='0,00') 
    {  
        /*$bonus='{
                    "partnerCode": "1002",
                    "nextStep": "finalize",
                    "customerText": "",
                    "operatorText": "Voce pode concluir a venda e continuar acumulando",
                     "identification":{
                                            "costumerId": "'.$arrayjson[identification][document].'"
                                      }
                }';*/
        $bonus='{
                            "partnerCode": "1002",
                            "nextStep": "bonus",
                            "customerText": "",
                            "operatorText": "Selecione um Bônus",
                            "identification":{
                                            "costumerId": "'.$arrayjson[identification][document].'"
                                         }
                        }';
    }else{
          //VALIDAR O SALDO ANTES DE ENVIAR PARA A BONUS OU FINALIZAÇÃO DA VENDA
        $resgate= $chegou[body][envelope][body][atualizacadastroresponse][atualizacadastroresponse][acao_h_saldo][saldodisponivel];
        
        if($arrayjson[sale][netSaleValue]<= $chegou[body][envelope][body][atualizacadastroresponse][atualizacadastroresponse][acao_h_saldo][saldodisponivel])
        {
           $resgate= $arrayjson[sale][netSaleValue];
        }    
        
        for ($i=1;$i <='2';$i++) {         
            sleep(0.25); 
            $descontovenda='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
                            <soapenv:Header/>
                            <soapenv:Body>
                               <fid:ValidaDescontos>
                                    <cpfcnpj>'.$arrayjson[identification][document].'</cpfcnpj>
                                    <cartao>'.$arrayjson[identification][document].'</cartao> 
                                    <valortotalliquido>'.$arrayjson[sale][netSaleValue].'</valortotalliquido>
                                    <valor_resgate>'.$resgate.'</valor_resgate>
                                    <dadosLogin>
                                                <login>'.$arraydadosaut[0].'</login>
                                                <senha>'.$arraydadosaut[1].'</senha>
                                                <idloja>'.$arraydadosaut[2].'</idloja>
                                                <idcliente>'.$arraydadosaut[4].'</idcliente>
                                    </dadosLogin>
                                </fid:ValidaDescontos>
                            </soapenv:Body>
                         </soapenv:Envelope>';
            $descontos=fnvalidaconsumidor($descontovenda);
            if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='49')
            {
                $resgate=$descontos[body][envelope][body][validadescontosresponse][validadescontos][minimoresgate];
            }    
        }
        if($descontos[body][envelope][body][validadescontosresponse][validadescontos][coderro]=='52')
        {  
            $bonus='{
                       "partnerCode": "1002",
                        "nextStep": "bonus",
                        "customerText": "",
                        "operatorText": "Selecione um Bônus",
                        "identification":{
                                        "costumerId": "'.$arrayjson[identification][document].'"
                                     }
                    }';
        }else{
            $bonus='{
                    "partnerCode": "1002",
                    "nextStep": "finalize",
                    "customerText": "",
                    "operatorText": "Voce pode concluir a venda e continuar acumulando",
                     "identification":{
                                            "costumerId": "'.$arrayjson[identification][document].'"
                                      }
                }';
        }
    }   
     echo $bonus;
}
?>