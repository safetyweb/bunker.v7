<?php 
function ConsultaCadastroPorCartao($DADOSLOGIN) {
rtrim(trim(require_once('../../../_system/Class_conn.php')));
rtrim(trim(require_once('../../func/function.php'))); 
include './functionbridge/functionbridge.php';
$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><ConsultaCadastroPorCartaoResponse></ConsultaCadastroPorCartaoResponse>");
              
    $CPF=fnlimpaCPF($DADOSLOGIN->cartao);
   
     
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->login,'login','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->senha,'senha','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => $msg));} 
    $msg=valida_campo_vazio($DADOSLOGIN->cartao,'CPF','numeric');
    if(!empty($msg)){return  array('ConsultaCadastroPorCartaoResult'=> array('msgerro' => $msg));}
    $connAdm=$connAdm->connAdm();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$DADOSLOGIN->dadoslogin->login."', '".fnEncode($DADOSLOGIN->dadoslogin->senha)."','','','".$DADOSLOGIN->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm,$sql);
    $row = mysqli_fetch_assoc($buscauser);
    
    //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
        fnlogmsg($connAdm,$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$dados->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$DADOSLOGIN->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'ConsultaCadastroPorcpf','Usuario foi desabilitado!',$row['LOG_WS']); 
         
         return array('ConsultaCadastroPorCartaoResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
    } 
    //===============================================     
     //conn user
      $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
	    $CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$DADOSLOGIN->dadoslogin->idcliente." AND 
														  COD_UNIVENDA=".$DADOSLOGIN->dadoslogin->idloja." AND LOG_STATUS='S'";
		$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
		if(!$RSCONFIGUNI)
		{		  
			// fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','erro no novo parametro da unidade'.$sql,$row['LOG_WS']); 
		 
		}else{
				if($RCCONFIGUNI=mysqli_num_rows($RSCONFIGUNI) > 0)
				{
					//aqui pega da unidade
					$RWCONFIGUNI=mysqli_fetch_assoc($RSCONFIGUNI);
					$dec=$RWCONFIGUNI['NUM_DECIMAIS']; 
					if ($RWCONFIGUNI['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}
					$LOG_CADVENDEDOR=$RWCONFIGUNI['LOG_CADVENDEDOR'];
					$COD_DATAWS1=$RWCONFIGUNI['COD_DATAWS'];
				}else{
					//aqui pega da controle de licença
					$dec=$row['NUM_DECIMAIS'];			
					if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}			
					$LOG_CADVENDEDOR=$row['LOG_CADVENDEDOR'];
					$COD_DATAWS1=$row['COD_DATAWS'];
				}   
		}
				
	
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
         fnlogmsg($connAdm,$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$DADOSLOGIN->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$DADOSLOGIN->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'ConsultaCadastroPorcpf','Erro Na autenticação',$row['LOG_WS']); 
        return  array('ConsultaCadastroPorCartaoResult'=> array('msgerro'=>'Erro Na autenticação')); 
    }
    $cod_men=fnmemoria($connUser->connUser(),'true',$DADOSLOGIN->dadoslogin->login,'Consulta Por Cartao',$row['COD_EMPRESA']);
    //url extrato
    $urlextrato=fnEncode($DADOSLOGIN->dadoslogin->login.';'
                        .$DADOSLOGIN->dadoslogin->senha.';'
                        .$DADOSLOGIN->dadoslogin->idloja.';'
                        .$DADOSLOGIN->dadoslogin->idmaquina.';'
                        .$row['COD_EMPRESA'].';'
                        .$DADOSLOGIN->dadoslogin->codvendedor.';'
                        .$DADOSLOGIN->dadoslogin->nomevendedor.';'
                        .$CPF
                         );  
    $dadosbase=fn_consultaBase($connUser->connUser(),'','',trim($CPF),'','',$row['COD_EMPRESA']);   
               
   //       return  array('ConsultaCadastroPorCartaoResult'=> array('msgerro'=>print_r($dadosbase)));                                                
     //Grava Log de envio do xml
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$DADOSLOGIN->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>'0',
                         'idloja'=>$DADOSLOGIN->dadoslogin->idloja,
                         'idmaquina'=>$DADOSLOGIN->dadoslogin->idmaquina,
                         'cpf'=>$CPF,     
                         'URL'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origembusca',
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
         //valida campo
            
    ///////////////////////////////////////////////////////////////////////////////////////////////////////    
   
if ($row['COD_EMPRESA'] != $DADOSLOGIN->dadoslogin->idcliente)
{
    Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,'Id_cliente não confere com o cadastro!');
    fnmemoriafinal($connUser->connUser(),$cod_men);
    return array('ConsultaCadastroPorCartaoResult'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
} 
  
//=====================================================================
        if($row['LOG_ATIVO']!='S')
        {  
             fnlogmsg($connAdm,$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$DADOSLOGIN->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$DADOSLOGIN->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'ConsultaCadastroPorcpf','Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
             Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');    
            
             return array('ConsultaCadastroPorCartaoResult'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
        }         
                                 if($row['COD_CHAVECO'] ==2 || $row['COD_CHAVECO']==5)
                                {    
                                    if($dadosbase[0]['contador'] == 1){   
                                       //consulta creditos
                                               $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                                               $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                                             

                                               //busca retorno profissão
                                                $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                                                $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm,$bus_PROFISS));  
                                               
                                                 

                                                       //Carrega dados da base de dados 
                                                    if ($dadosbase[0]['cpf']=='' || $dadosbase[0]['cartao']==''){
                                                        $msg='Nenhum cadastro encontrado';
                                                        Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,$msg);
                                                    }else{
                                                      $msg='OK';
                                                      
                                                    } 
                                                    //============estado civil 
                                                    if($dadosbase[0]['estadocivil']==1)
                                                    {
                                                        $estadocivil='Casado';
                                                    }elseif ($dadosbase[0]['estadocivil']==2) {
                                                            $estadocivil='Solteiro';
                                                    }elseif($dadosbase[0]['estadocivil']==3) 
                                                    {
                                                           $estadocivil='Viuvo';
                                                    } else {
                                                        $estadocivil='Divorciado'; 
                                                    }
                                                    //tipode pessoa fisica ou juridica
                                                    if($dadosbase[0]['tipocliente']=='F'){$tipoclient='Pessoa Física';} else {$tipoclient='Pessoa Juridica';}
                                                    //=====================
                                                     // if($dadosbase[0]['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                                                       if($dadosbase[0]['sexo']=='1'){$sexo='Masculino';}else{$sexo='Feminino';}  
                                                        //=================================================================
                                                       //verifica se vem do dna+
                                                       if($dadosbase[0]['contador'] == 1){
                                                           $dnamais='0';
                                                       }else{$dnamais='1';}
                                                     
                                                        fnmemoriafinal($connUser->connUser(),$cod_men);
                                                       
                                                        //mysqli_kill($connAdm->connAdm(), $id_adm);
                                                       //======================
                                                        $return= array('ConsultaCadastroPorCartaoResult'=> array(
                                                                                                                'cartao' =>$dadosbase[0]['cartao'] ,
                                                                                                                'tipocliente' =>$tipoclient,
                                                                                                                'nome' => $dadosbase[0]['nome'],
                                                                                                                'cpf' => fncompletadoc($dadosbase[0]['cpf']),
                                                                                                                'cnpj'=>$dadosbase[0]['cnpj'],'cnpj',
                                                                                                                'rg' => $dadosbase[0]['rg'],
                                                                                                                'sexo' =>$sexo,
                                                                                                                'datanascimento' => fnDataSql($dadosbase[0]['datanascimento']),
                                                                                                                'estadocivil' =>$estadocivil,
                                                                                                                'email' => $dadosbase[0]['email'],
                                                                                                                'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                                                                                                'nomeportador'=>$dadosbase[0]['nomeportador'],
                                                                                                                'grupo' => $dadosbase[0]['grupo'],
                                                                                                                'profissao' =>$profiss_ret['DES_PROFISS'],
                                                                                                                'clientedesde' => $dadosbase[0]['clientedesde'],
                                                                                                                'endereco' =>  $dadosbase[0]['endereco'],
                                                                                                                'numero' => $dadosbase[0]['numero'],
                                                                                                                'complemento' =>$dadosbase[0]['complemento'],
                                                                                                                'bairro' =>$dadosbase[0]['bairro'],
                                                                                                                'cidade' =>$dadosbase[0]['cidade'],
                                                                                                                'estado' =>$dadosbase[0]['estado'],
                                                                                                                'cep' => $dadosbase[0]['cep'],
                                                                                                                'telresidencial' =>$dadosbase[0]['telresidencial'],
                                                                                                                'telcelular' =>$dadosbase[0]['telcelular'],
                                                                                                                'telcomercial' =>$dadosbase[0]['telcomercial'],
                                                                                                                'saldo' => fnValornovo($retSaldo['TOTAL_CREDITO'],$dec),
                                                                                                                'saldoresgate' => fnValornovo($retSaldo['CREDITO_DISPONIVEL'],$dec),
                                                                                                                'msgerro' => $msg,
                                                                                                                'ativacampanha' => '',
                                                                                                                'dadosextras' => '',
                                                                                                                'bloqueado' => '0',
                                                                                                                'motivo' =>'',
                                                                                                                'urlextrato' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                                                                'retornodnamais' => $dnamais
                                                        ));
                                        array_to_xml($return,$xml_user_info);
                                        Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,'CONSULTA POR CARTAO OK',addslashes($xml_user_info->asXML()));
                                        return $return;               
                          
                                    }else{
                                        
                                       Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,'Nenhum cadastro encontrado');
                                      
                                       return array('ConsultaCadastroPorCartaoResult'=> array('msgerro' => 'Nenhum cadastro encontrado',
                                                                                                         'retornodnamais' => '0'));}        
                                       } else {
                                        Grava_log_msgxml($connUser->connUser(),'msg_busca',$cod_log,'Nenhum cadastro encontrado');   
                                    
                                        return array('ConsultaCadastroPorCartaoResult'=> array('msgerro' => 'Nenhum cadastro encontrado',
                                                                                                         'retornodnamais' => '0'));     
                                       }
                               
}
?>