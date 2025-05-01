<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';

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

/*$teste= Array
                (
                   'ws.rca1',
                   '@rca1',
                   '97413',
                   'webhook',
                   '264'
                );
*/
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
                                     "coderro": "400"
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
                                     "coderro": "400"
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);

//validar formato data
function fnvalidateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
if(!empty($arrayjson['dataHoraInicial']) || !empty($arrayjson['dataHoraFinal']))
{        
        $datahoraInicial=fnvalidateDate($arrayjson['dataHoraInicial']);
	$datahoraFinal=fnvalidateDate($arrayjson['dataHoraFinal']);	        
        if($datahoraInicial!=1 || $datahoraFinal!=1)
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                                            {
                                             "message": "Formato date/time inválido! AAAA-MM-DD",
                                             "coderro": "400"
                                             }
                                        ]
                                }';    
                echo $erroinformation;
                exit();  
        } 
}        
//verificar se pelomenos um filtro esta sendo enviado.
if(empty($arrayjson['dataHoraFinal']) || empty($arrayjson['dataHoraInicial']))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "É necessario informar periodo!",
                                     "coderro": "400"
                                     }
                                ]
                        }';    
        echo $erroinformation;
        exit();  
}    

//Quantidade maxima da lista   		
if($arrayjson['quantidadeLista']>'100'){
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Limite maximo da lista é 100",
                                     "coderro": "400"
                                     }
                                ]
                        }';    
        echo $erroinformation;
        exit();           
}          
// limitar a consulta 30 dias
               
$entrada = new DateTime($arrayjson['dataHoraInicial']);
$saida = new DateTime($arrayjson['dataHoraFinal']);
$intervalo = $entrada->diff($saida);
if($intervalo->days > '30')
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Intervalo de consulta nao pode ultrapassar o periodo de 10 dias",
                                     "coderro": "400"
                                     }
                                ]
                        }';    
        echo $erroinformation;
        exit(); 
}
//Dafault paginação   		
if($arrayjson['proximaPagina']=='' && $arrayjson['proximaPagina']=='0'){
    $proximaPagina='1';	
}else{
        $proximaPagina=$arrayjson['proximaPagina'];
}
//quantidade default da lista
if($arrayjson['quantidadeLista']<='0' || $arrayjson['quantidadeLista']=='')
{
        $quantidadeLista='50';
}else{
        $quantidadeLista=$arrayjson['quantidadeLista'];
}
 
//Filtro adcional
if(!empty($arrayjson[codCliente]))
{
    $andwhere.="AND COD_CLIENTE in ($arrayjson[codCliente])";
} 

        //carregar o contador de cliente  na lista total
	$SQLQTDCLIENTE="SELECT COUNT(*) QTD_LISTA FROM CLIENTES
                        WHERE DAT_CADASTR BETWEEN '".$arrayjson[dataHoraInicial]."' AND '".$arrayjson[dataHoraFinal]."'
                        and COD_EMPRESA=".$arraydadosaut[4]."
                        $andwhere
                        AND CASE
                                WHEN num_cartao > '0' THEN '1'
                                WHEN num_CGCECPF>'0' THEN '1'
                        ELSE '0' end IN ( 1, 1 )";
	$rwQTDCLIENTE=mysqli_fetch_assoc(mysqli_query($conexaotmp,$SQLQTDCLIENTE));	
	//verificando a quantidade da consulta
	$inicio = ($proximaPagina * $quantidadeLista) - $quantidadeLista;	

  $sqlresultado="SELECT
                       COD_CLIENTE,
                       NOM_CLIENTE,
		       NUM_CARTAO,
		       NUM_CGCECPF,
                       TIP_CLIENTE,
		       COD_SEXOPES,
		       NUM_RGPESSO,
		       DAT_NASCIME,
		       COD_ESTACIV, 
		       NUM_TELEFON, 
		       NUM_COMERCI,
		       NUM_CELULAR,
		       DES_EMAILUS, 
                       COD_PROFISS, 
		       DAT_CADASTR,
		       DES_ENDEREC,
		       DES_BAIRROC,
		       DES_COMPLEM, 
		       NOM_CIDADEC,
		       COD_ESTADOF,
		       NUM_CEPOZOF,													  
		       DAT_ALTERAC,
		       DES_TOKEN,
                       LOG_TERMO,
                       LOG_FIDELIZADO
                FROM CLIENTES
                WHERE DAT_CADASTR BETWEEN '".$arrayjson[dataHoraInicial]."' AND '".$arrayjson[dataHoraFinal]."'
                and COD_EMPRESA=".$arraydadosaut[4]."
                $andwhere
                AND CASE
                         WHEN num_cartao > '0' THEN '1'
                         WHEN num_CGCECPF>'0' THEN '1'
                         ELSE '0' end IN ( 1, 1 ) 
                         order by DAT_CADASTR desc
                limit $inicio ,$quantidadeLista";
$rsresultado= mysqli_query($conexaotmp,$sqlresultado);	
while($rwresultado= mysqli_fetch_assoc($rsresultado))
{
    //termos e aceites
      


   $sqltermos= " SELECT 
                        GROUP_CONCAT(DISTINCT  b.COD_TERMO SEPARATOR ',' ) COD_TERMO,
                        GROUP_CONCAT(DISTINCT b.DES_BLOCO SEPARATOR '||' ) DES_BLOCO
                FROM bloco_termos  b
                WHERE b.COD_TERMO IN (SELECT COD_TERMOS FROM clientes_termos WHERE COD_CLIENTE=$rwresultado[COD_CLIENTE] AND b.LOG_OBRIGA='S')"; 
   $rstermos= mysqli_fetch_all(mysqli_query($conexaotmp,$sqltermos), MYSQLI_ASSOC);	
   
   $bancovar="SELECT  GROUP_CONCAT(DISTINCT concat('<#',upper(ABV_TERMO),'>') SEPARATOR '||' ) ABV_TERMOV,  
                GROUP_CONCAT(DISTINCT ABV_TERMO SEPARATOR '||' ) ABV_TERMOT FROM  termos_empresa t where  t.COD_TERMO IN (".$rstermos[0]['COD_TERMO'].")  AND t.LOG_ATIVO='S'";
   $rsbancovar= mysqli_fetch_all(mysqli_query($conexaotmp,$bancovar), MYSQLI_ASSOC);
   $bancovariavel=explode('||', $rsbancovar[0][ABV_TERMOV]);
   $bancoTexto=explode('||', $rsbancovar[0][ABV_TERMOT]);
   $textolimpo=str_replace($bancovariavel, $bancoTexto, $rstermos[0][DES_BLOCO]);
   $textolimpo=explode('||',$textolimpo);   
   $resultadoarr[]=array(
                         'COD_CLIENTE'=>$rwresultado[COD_CLIENTE],
                         'NOM_CLIENTE'=>$rwresultado[NOM_CLIENTE],
                         'NUM_CARTAO'=>$rwresultado[NUM_CARTAO],
                         'NUM_CGCECPF'=>$rwresultado[NUM_CGCECPF],
                         'TIP_CLIENTE'=>$rwresultado[TIP_CLIENTE],
                         'COD_SEXOPES'=>$rwresultado[COD_SEXOPES],
                         'NUM_RGPESSO'=>$rwresultado[NUM_RGPESSO],
                         'DAT_NASCIME'=>$rwresultado[DAT_NASCIME],
                         'COD_ESTACIV'=>$rwresultado[COD_ESTACIV], 
                         'NUM_TELEFON'=>$rwresultado[NUM_TELEFON], 
                         'NUM_COMERCI'=>$rwresultado[NUM_COMERCI],
                         'NUM_CELULAR'=>$rwresultado[NUM_CELULAR],
                         'DES_EMAILUS'=>$rwresultado[DES_EMAILUS], 
                         'COD_PROFISS'=>$rwresultado[COD_PROFISS], 
                         'DAT_CADASTR'=>$rwresultado[DAT_CADASTR],
                         'DES_ENDEREC'=>$rwresultado[DES_ENDEREC],
                         'DES_BAIRROC'=>$rwresultado[DES_BAIRROC],
                         'DES_COMPLEM'=>$rwresultado[DES_COMPLEM], 
                         'NOM_CIDADEC'=>$rwresultado[NOM_CIDADEC],
                         'COD_ESTADOF'=>$rwresultado[COD_ESTADOF],
                         'NUM_CEPOZOF'=>$rwresultado[NUM_CEPOZOF],													  
                         'DAT_ALTERAC'=>$rwresultado[DAT_ALTERAC],
                         'DES_TOKEN'=>$rwresultado[DES_TOKEN],
                         'LOG_TERMO'=>$rwresultado[LOG_TERMO],
                         'LOG_FIDELIZADO'=>$rwresultado[LOG_FIDELIZADO],
                         'TermosAceites'=>$textolimpo
                          );
}    



$total_paginas = Ceil($rwQTDCLIENTE['QTD_LISTA'] / $quantidadeLista);
if($total_paginas > $proximaPagina)
{		
    $msg="TRUE";
}else{
    $msg="FALSE";
}				  
    $return =array(
                'msgerro' => 'OK',
                'coderro'=>'200',
                'quantidaregistrototal'=>$rwQTDCLIENTE['QTD_LISTA'],
                'quantidaderegistrolista'=>$quantidadeLista,
                'paginaatual'=>$proximaPagina,
                'paginacao'=>$msg,				
                'informacoesdocliente'=>$resultadoarr);
														   
echo json_encode($return); 
