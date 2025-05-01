<?php
$server->wsdl->addComplexType(
    'ValidaDescontos',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'saldo_disponivel'=> array('name' => 'saldo_disponivel', 'type' => 'xsd:string'),
        'valor_resgate'=> array('name' => 'valor_resgate', 'type' => 'xsd:string'),
        'minimoresgate' => array('name' => 'minimoresgate', 'type' => 'xsd:string'),
        'maximoresgate' => array('name' => 'maximoresgate', 'type' => 'xsd:string'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:integer')
        ));

$server->register('ValidaDescontos',
			array('cpfcnpj'=>'xsd:string',
                              'cartao'=> 'xsd:string',
                              'valortotalliquido'=>'xsd:string',
                              'valor_resgate'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('ValidaDescontos' => 'tns:ValidaDescontos'),  //output
			 $ns,         						// namespace
                        "$ns/ValidaDescontos",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ValidaDescontos'         		// documentation
                    );
 
 
function ValidaDescontos ($cpfcnpj,$cartao,$valortotalliquido,$valor_resgate,$dadosLogin) {
     include_once '../_system/Class_conn.php';
     include_once 'func/function.php'; 
     
     $cpf=fnlimpaCPF($cpfcnpj);  
     $cartao=fnlimpaCPF($cartao); 
   
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
     //compara os id_cliente com o cod_empresa
    
    
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
	
	
	
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
       $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'ValidaDescontos',$dadosLogin['idcliente']);
        
        $xmlteste=addslashes(file_get_contents("php://input"));
        $arrylog=array('cod_usuario'=>$row['COD_USUARIO'],
                        'login'=>$dadosLogin['login'],
                        'cod_empresa'=>$row['COD_EMPRESA'],
                        'idloja'=>$dadosLogin['idloja'],
                        'idmaquina'=>$dadosLogin['idmaquina'],
                        'cpf'=>$cpf,     
                        'xml'=>$xmlteste,
                        'tables'=>'origemdescontos',
                        'conn'=>$connUser->connUser()
             
                      );
        
        $cod_log=fngravalogxml($arrylog);
       
      
        
        
        
       //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'ValidaDescontos','Id_cliente não confere com o cadastro!',$row['LOG_WS']);
              
           return  array('ValidaDescontos'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                   'coderro'=>'4')); 
           exit();
        } 
       //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'ValidaDescontos','A empresa foi desabilitada por algum motivo',$row['LOG_WS']);
           return  array('ValidaDescontos'=>array('msgerro'=>'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                                                     'coderro'=>'6'));
           exit();
        }
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'ValidaDescontos',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('ValidaDescontos'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!',
                                                   'coderro'=>'44'));
           exit();
        }
    //////////////////////=================================================================================================================
    
   }else{
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],'',$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'ValidaDescontos','Usuario ou senha Inválido!',$row['LOG_WS']);
       return  array('ValidaDescontos'=>array('msgerro'=>'Usuario ou senha Inválido!',
                                              'coderro'=>'5'));
       exit();
   }
    
     
   //busca cliente  na base de dados    
    $arraydadosbusca=array('empresa'=>$dadosLogin['idcliente'],
                           'cartao'=>$cartao,
                           'cpf'=>$cpf,
                           'venda'=>'',
                           'ConnB'=>$connUser->connUser());
    
    $cliente_cod=fn_consultaBase($arraydadosbusca);    
     
    // return  array('ValidaDescontos'=>array('msgerro' => print_r($cliente_cod),
     //S                                                         'coderro'=>'0 '));
     ob_start();   
    
/*	$dec=$row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/
	$CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dadosLogin['idcliente']." AND 
														  COD_UNIVENDA=".$dadosLogin['idloja']." AND LOG_STATUS='S'";
													  
			$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
			if(!$RSCONFIGUNI)
			{		  
	         
			}else{
					if($RCCONFIGUNI=mysqli_num_rows($RSCONFIGUNI) > 0)
					{
						//aqui pega da unidade
						$RWCONFIGUNI=mysqli_fetch_assoc($RSCONFIGUNI);
						$dec=$RWCONFIGUNI['NUM_DECIMAIS']; 
						if ($RWCONFIGUNI['TIP_RETORNO']== 2){$decimal = '2';}else {$casasDec = '0';}
						$LOG_CADVENDEDOR=$RWCONFIGUNI['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$RWCONFIGUNI['COD_DATAWS'];
					}else{
						//aqui pega da controle de licença
						$dec=$row['NUM_DECIMAIS'];			
						if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$casasDec = '0';}			
						$LOG_CADVENDEDOR=$row['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$row['COD_DATAWS'];
					}   
			}  
	
if($cliente_cod['COD_CLIENTE']!=''){     
        //verifica se o saldo resgate é  maior que o disponivel
      if($valor_resgate > 0 || fnFormatvalor($valor_resgate,$dec) >= '0.00' ||fnFormatvalor($valor_resgate,$dec)>='0.000' ||fnFormatvalor($valor_resgate,$dec)>='0.0000' || fnFormatvalor($valor_resgate,$dec)>='0.00000')
      {
         
   
          if($cartao > 0 || $cartao!='' || $cpf > 0 || $cpf!='')
          {   

                  //=====busca saldo do clientes 
                  $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$cliente_cod['COD_CLIENTE'].")";
                  $rsrown=mysqli_query($connUser->connUser(),$consultasaldo);
                  $retSaldo = mysqli_fetch_assoc($rsrown);
                  mysqli_free_result($retSaldo);
                  mysqli_next_result($connUser->connUser());
                   
                 //============================================================================
                  //busca valor de configurados para resgates
                  $regraresgate = "SELECT round(min(CR.NUM_MINRESG)," . $dec . ") as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                  INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                  WHERE LOG_ATIVO='S' and c.LOG_REALTIME='S' AND
                      c.COD_EXCLUSA=0 AND 
                      ((C.LOG_CONTINU='S'AND CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) OR

                              ((C.LOG_CONTINU='N') AND

                                  (CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) AND

                                  (CONCAT(C.DAT_FIM,' ', C.HOR_FIM) > NOW()) 

                                  ))  AND C.cod_empresa=" . $dadosLogin['idcliente'];
                  //if($dadosLogin['idcliente']=='92')
                 // {
                 //   echo $regraresgate;  
                 // }    
                  $resgresult=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$regraresgate));
                  //==========================================================================
                  $arrayvalorres=array('vl_venda'=> fnValorSQL($valortotalliquido,$dec),
                                       'PCT_MAXRESG'=>  $resgresult['PCT_MAXRESG']);
                 $percentual=fnVerificasaldo($arrayvalorres);
                 //calcula porcentagem de resgate

                if(fnValorSQL($valor_resgate,$dec) > fnFormatvalor($percentual,$dec))
                {
                 
                  Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Valor de Resgate R$'.fnvalorretorno($valor_resgate,$decimal).' esta  maior que o permitido de R$'. fnvalorretorno ($percentual,$decimal).'');
                  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','Valor Regate maior que o permitido',$row['LOG_WS']);
                  fnmemoriafinal($connUser->connUser(),$cod_men);
                  return array('ValidaDescontos'=>array('saldo_disponivel'=>fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                        'maximoresgate'=>fnvalorretorno($percentual,$decimal),
                                                        'msgerro' => 'Valor de Resgate R$'.fnvalorretorno($valor_resgate,$decimal).' esta  maior que o permitido de R$'. fnvalorretorno ($percentual,$decimal).'',
                                                        'coderro'=>'49'));
                }
                if(fnValorSQL($valor_resgate,$dec) < $resgresult['NUM_MINRESG'])
                {
                 Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Valor Resgate não pode ser menor que o permitido R$ '.fnvalorretorno($resgresult['NUM_MINRESG'],$decimal).'');
                 fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','Valor Regate não pode ser menor que o permitido',$row['LOG_WS']);
                 fnmemoriafinal($connUser->connUser(),$cod_men);
                  return  array('ValidaDescontos'=>array('saldo_disponivel'=>fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                         'minimoresgate'=>fnvalorretorno($resgresult['NUM_MINRESG'],$decimal),
                                                         'msgerro' => 'Valor Resgate não pode ser menor que o permitido R$ '.fnvalorretorno($resgresult['NUM_MINRESG'],$decimal).'',
                                                         'coderro'=>'50'));
                } 
               //saldo menor que o disponivel 
              if( fnFormatvalor($valor_resgate,$dec) >  fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'],$dec))
              {
                  Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Valor Resgate R$'.fnvalorretorno($valor_resgate,$decimal).' maior que o credito disponivel  R$'.fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal).'');
                 
                   
                  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','Valor Regate maior que o disponivel',$row['LOG_WS']);
                  fnmemoriafinal($connUser->connUser(),$cod_men);
                  
                  return array('ValidaDescontos'=>array('saldo_disponivel'=>fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                        'msgerro' => 'Valor Resgate R$'.fnvalorretorno($valor_resgate,$decimal).' maior que o credito disponivel  R$'.fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal).'',
                                                        'coderro'=>'51' ));
              }
 
                //====================================================================================
          }else{
              Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Não CPF ou cartao nao especificado');
                 
              return array('ValidaDescontos'=>array( 'msgerro' => 'Não CPF ou cartao nao especificado',
                                                                   'coderro'=>'52' ));
          } 
           Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Valor de R$'.fnvalorretorno($valor_resgate,$decimal).' pode ser resgatado com sucesso');
             
         $arrayreturn= array('ValidaDescontos'=>array('saldo_disponivel'=>fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                      'valor_resgate'=> fnvalorretorno($valor_resgate,$decimal),
                                                      'msgerro' => 'Valor de R$'.fnvalorretorno($valor_resgate,$decimal).' pode ser resgatado com sucesso',
                                                       'coderro'=>'52' ));   
      }else{
           fnmemoriafinal($connUser->connUser(),$cod_men);
          Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Valor valortotalliquido ou valor_resgate não preenchido!');
            
         return array('ValidaDescontos'=>array('msgerro' => 'Valor valortotalliquido ou valor_resgate não preenchido!',
                                                'coderro'=>'53' ));  
      }
}else
{
     Grava_log_msgxml($connUser->connUser(),'msg_desconto',$cod_log,'Cliente nao faz parte do fidelidade');
         
     $arrayreturn= array('ValidaDescontos'=>array('msgerro' => 'Cliente nao faz parte do fidelidade',
                                                                   'coderro'=>'54' )); 
}    


//------------------------------------------------------------------------------
 
     

    ob_end_flush();
    ob_flush();
    fnmemoriafinal($connUser->connUser(),$cod_men);
    return  $arrayreturn;
}

?>
