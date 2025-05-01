<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if( $_SERVER['REQUEST_METHOD']!='POST' )
{ 
    echo json_encode(array('msgerro'=>'Metodo de envio deve ser POST',
                           'cod_erro'=>'01')); 
	exit();   
} 
include_once '../_system/_functionsMain.php';
//include '../wsmarka/func/function.php';   
$dadosenvio = json_decode(file_get_contents("php://input"),true);

$cpf=fnLimpaCampo($dadosenvio[ConsultaSaldo][consulta_cliente][cpf]);
$DIAS_EXPIRA=fnLimpaCampo($dadosenvio[ConsultaSaldo][consulta_cliente][dias_expira]);
$login=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][login]);
$senha=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][senha]);
$idloja=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][idloja]);
$idcliente=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][idcliente]);
$codvendedor=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][codvendedor]);
$nomevendedor=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][nomevendedor]);
$idmaquina=fnLimpaCampo($dadosenvio[ConsultaSaldo][dadoslogin][idmaquina]);

	//autenticação
	$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($senha)."','','','".$idcliente."','','')";
	$buscauser=mysqli_query($connAdm->connAdm(),$sql);
	$row = mysqli_fetch_assoc($buscauser);
	//verifica login
	if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
	{
	   echo  json_encode(array('msgerro'=>'Verifique os dados de dadoslogin!',
						       'coderro'=>'5'));
	   exit();
	}
		$lojasql='SELECT LOG_ESTATUS FROM unidadevenda
							 WHERE COD_UNIVEND='.$idloja.' AND cod_empresa='.$idcliente;
		$lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
	if($lojars['LOG_ESTATUS']!='S')
	{
	   echo  json_encode(array('msgerro'=>'LOJA DESABILITADA',
				               'coderro'=>'80'));
	   exit();   
	}
	    $connUser=connTemp($idcliente,'');
	if($DIAS_EXPIRA == '' || $DIAS_EXPIRA == '0'){$DIAS_EXPIRA='30';}		
	if($cpf=='')
	{
		   echo  json_encode(array('msgerro'=>'Cpf não pode ser vazio!',
				                   'coderro'=>'211'));
	      exit();   
	
	}
	//retorno de saldo
$sqlsaldo="SELECT 
		   	FORMAT(
					((SELECT ifnull(SUM(VAL_SALDO),0)
					FROM CREDITOSDEBITOS
					WHERE COD_CLIENTE=A.COD_CLIENTE AND
					TIP_CREDITO='C' AND
					COD_STATUSCRED=1 AND
					tip_campanha = A.tip_campanha AND
					(DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
					)+
					(SELECT ifnull(SUM(VAL_SALDO),0)
					FROM CREDITOSDEBITOS
					WHERE COD_CLIENTE=A.COD_CLIENTE AND
					TIP_CREDITO='C' AND
					COD_STATUSCRED=2 AND
					tip_campanha = A.tip_campanha AND
					(DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
					)),2,'pt_BR') AS CREDITO_TOTAL,

			FORMAT((
			        SELECT ifnull(SUM(VAL_SALDO),0)
					FROM CREDITOSDEBITOS
					WHERE COD_CLIENTE=A.COD_CLIENTE AND
					TIP_CREDITO='C' AND
					COD_STATUSCRED=1 AND
					tip_campanha = A.tip_campanha AND
					(DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
					),2,'pt_BR') AS CREDITO_DISPONIVEL,
				
			FORMAT((
					SELECT ifnull(SUM(VAL_SALDO),0)
					FROM CREDITOSDEBITOS
					WHERE COD_CLIENTE=A.COD_CLIENTE AND
					TIP_CREDITO='C' AND
					COD_STATUSCRED IN(1,2) AND
					tip_campanha = A.tip_campanha AND
					DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') AND
					DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') <= DATE_FORMAT(ADDDATE( NOW(), INTERVAL + ".$DIAS_EXPIRA." DAY), '%Y-%m-%d' ) ),2,'pt_BR') AS CREDITO_EXPIRAR
						
				FROM CREDITOSDEBITOS A,empresas B,CLIENTES C  
				WHERE 
						A.cod_empresa = B.cod_empresa AND 
						A.tip_campanha = B.tip_campanha AND
						A.COD_CLIENTE=C.COD_CLIENTE AND 
						C.NUM_CGCECPF='".$cpf."' AND
					  A.COD_STATUSCRED <> 6 AND 
			         C.COD_EMPRESA=".$idcliente."
				GROUP BY A.COD_CLIENTE;";
   $rwsaldo=mysqli_fetch_assoc(mysqli_query($connUser,$sqlsaldo));
 if(!$rwsaldo)
 {
	 echo  json_encode(array('msgerro'=>'Confirme os dados enviado e tente novamente!',
				               'coderro'=>'210'));
	   exit();   
 }	 

 print(json_encode($rwsaldo,JSON_PRETTY_PRINT));
//echo $cpf;
//echo $DIAS_EXPIRA;
//echo $login;
//echo $senha;
//echo $idloja;
//echo $idcliente;
//echo $codvendedor;
//echo $nomevendedor;
//echo $idmaquina;



/*
switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo 'No errors';
        break;
    case JSON_ERROR_DEPTH:
        echo 'Maximum stack depth exceeded';
        break;
    case JSON_ERROR_STATE_MISMATCH:
        echo 'Underflow or the modes mismatch';
        break;
    case JSON_ERROR_CTRL_CHAR:
        echo 'Unexpected control character found';
        break;
    case JSON_ERROR_SYNTAX:
        echo 'Syntax error, malformed JSON';
        break;
    case JSON_ERROR_UTF8:
        echo 'Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
    default:
        echo 'Unknown error';
        break;
}*/

?>
