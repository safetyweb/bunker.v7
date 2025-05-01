<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
$seconds_to_cache = 3600;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Last-Modified: $ts");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");


header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
if($_SERVER[REQUEST_METHOD]!='GET')
{
     http_response_code(400);
   $erroinformation='{"errors": [
                        {
                         "field": "identificationCode",
                         "message": "O metodo para capturar deve ser GET",
                         "locationType": "body",
                         "location": "https://homol.marka.com/pages/api_inicio"
                        }
                       ]
                    }';    
     echo $erroinformation;
     exit();
}  



include '../../../_system/_functionsMain.php';

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
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);

$conncliente= connTemp($cod_empresa, '');
$connadm=$connAdm->connAdm();

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
$cpf1='false';
$phone1='false';
//      
if($chave=='1' || $chave=='2' || $chave=='5'){
   $cpf1="true";
   $cpf="true";
} else {
     if( in_array( "cartao" ,$campos)){ $cpf="true";}else{ $cpf="false";} 
}
if($chave=='3'){
   $phone1="true";
} else {
    if( in_array( "telcelular" ,$campos)){ $phone="true";}else{ $phone="false";}  
}

    if( in_array( "datanascimento" ,$campos)){ $birth="true";}else{ $birth="false";}   
    if( in_array( "email" ,$campos)){ $email="true";}else{ $email="false";}   
    if( in_array( "nome" ,$campos)){ $name="true";}else{ $name="false";}   
    if( in_array( "sexo" ,$campos)){ $gender="true";}else{ $gender="false";}   
     
  
 


$retornofinal='{
                "partnerCode": "1002",
                "nextStep": "identification",
                "customerText": "",
                "operatorText": "Solicite e confirme os dados do cliente para o programa de Bônus",
                "identificationForms": [
                    {
                        "isIdentificationCode": '.$phone1.',
                        "type": "phone",
                        "operatorText": "Número do celular",
                        "customerText": "",
                        "required": '.$phone.',
                        "isPassword": true
                    },
                    {
                        "isIdentificationCode": false,
                        "type": "name",
                        "operatorText": "Nome",
                        "customerText": "",
                        "required": '.$name.',
                        "isPassword": false
                    },
                    {
                        "isIdentificationCode": false,
                        "type": "email",
                        "operatorText": "E-mail",
                        "customerText": "",
                        "required":  '.$email.',
                        "isPassword": false
                    },
                    {
                        "isIdentificationCode": false,
                        "type": "birth",
                        "operatorText": "Data de aniversário",
                        "customerText": "",
                        "required": '.$birth.',
                        "isPassword": false
                    },
                    {
                        "isIdentificationCode": '.$cpf1.',
                        "type": "document",
                        "operatorText": "documento",
                        "customerText": "",
                        "required": '.$cpf.',
                        "isPassword": false
                    },
                    {
                        "isIdentificationCode": false,
                        "type": "gender",
                        "operatorText": "Gênero",
                        "customerText": "",
                        "required": '.$gender.',
                        "isPassword": false
                    }
                ],
                "status": false,
                "message": null,
                "_expandables": []
            }';
echo $retornofinal;