<?php 
function ConsultaCadastroPorCPF($DADOSLOGIN) {

rtrim(trim(require_once('../../../_system/Class_conn.php')));
rtrim(trim(require_once('../../func/function.php')));
include './functionbridge/functionbridge.php';
$xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><ConsultaCadastroPorCPFResponse></ConsultaCadastroPorCPFResponse>");
                             
    $CPF=fnlimpaCPF($DADOSLOGIN->CPF);
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->login,'login','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->senha,'senha','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=>array('msgerro' => $msg));} 
    $msg=valida_campo_vazio($DADOSLOGIN->CPF,'CPF','numeric');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=> array('msgerro' => $msg));}
  
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$DADOSLOGIN->dadoslogin->login."', '".fnEncode($DADOSLOGIN->dadoslogin->senha)."','','','".$DADOSLOGIN->dadoslogin->idcliente."','','')";
  
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //verifica se o usuario esta ativo
    /*  $dec=$row['NUM_DECIMAIS'];
       if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/
    if($row['LOG_ESTATUS']=='N')
    {
         fnlogmsg($connAdm->connAdm(),$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$DADOSLOGIN->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'CONSULTACADASTRO','Usuario foi desabilitado!',$row['LOG_WS']);  
         return array('InserirVendaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
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
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
         fnlogmsg($connAdm->connAdm(),$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$DADOSLOGIN->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$DADOSLOGIN->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'CONSULTACADASTRO','Erro Na autenticação!',$row['LOG_WS']); 
            
        return  array('ConsultaCadastroPorCPFResult'=> array('msgerro'=>'Erro Na autenticação')); 
    }
         //valida campo
        $cod_men=fnmemoria($connUser->connUser(),'true',$DADOSLOGIN->dadoslogin->login,'Consulta CPF',$row['COD_EMPRESA']);
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////    
    //inserir venda inteira na base de dados 
   $arraydados1= array('CONN'=>$connUser->connUser(),
                       'DATA_HORA'=>date("Y-m-d H:i:s"),
                       'IP'=>$_SERVER['REMOTE_ADDR'],
                       'PORT'=>$_SERVER['REMOTE_PORT'],
                       'COD_USUARIO'=>$row['COD_USUARIO'],
                       'LOGIN'=>$DADOSLOGIN->dadoslogin->login,
                       'COD_EMPRESA'=>$row['COD_EMPRESA'],
                       'IDLOJA'=>$DADOSLOGIN->dadoslogin->idloja,
                       'IDMAQUINA'=>$DADOSLOGIN->dadoslogin->idmaquina,
                       'CPF'=>$CPF,
                       'URL'=>$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                       'XML'=>file_get_contents("php://input")
                      );
    $LOG=fngravaxmlbusca($arraydados1);
//verifica a empresa está ativa    
if ($row['COD_EMPRESA'] != $DADOSLOGIN->dadoslogin->idcliente)
{
    $return=array('ConsultaCadastroPorCPFResult'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
    array_to_xml($return,$xml_user_info);
    Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'Id_cliente não confere com o cadastro!',addslashes($xml_user_info->asXML()));
    return $return;
}    

//=====================================================================
        if($row['LOG_ATIVO']!='S')
        {  
            fnlogmsg($connAdm->connAdm(),$DADOSLOGIN->dadoslogin->login,$DADOSLOGIN->dadoslogin->idcliente,$CPF,$DADOSLOGIN->dadoslogin->idloja,$DADOSLOGIN->dadoslogin->idmaquina,$DADOSLOGIN->dadoslogin->codvendedor,$DADOSLOGIN->dadoslogin->nomevendedor,'CONSULTACADASTRO','Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']);  
          
             $return=array('ConsultaCadastroPorCPFResult'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')); 
             array_to_xml($return,$xml_user_info);
             Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'Por algum motivo seu login foi desabilitado.',addslashes($xml_user_info->asXML()));
             return $return;
        }         
                               $dadosbase=fn_consultaBase($connUser->connUser(),trim($CPF),'','','','',$row['COD_EMPRESA']);   
                                if($row['COD_CHAVECO'] == 1 || $row['COD_CHAVECO'] == 5)
                                {    
                                    if($dadosbase[0]['contador'] == 1){   
                                       //consulta creditos
                                               $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                                               $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                                             

                                               //busca retorno profissão
                                                $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                                                $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS));  
                                               
                                                $xamls= 'Consulta Cadastro cod 2!';
                                                Grava_log_consulta($connUser->connUser(),$LOG,$xamls);
                                                

                                                       //Carrega dados da base de dados 
                                                    if ($dadosbase[0]['cpf']=='' || $dadosbase[0]['cartao']==''){$msg='Nenhum cadastro encontrado';}else{$msg='OK';}
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
                                                       if($dadosbase[0]['senha']=='') {$senhacli="gdKgip5aBK4¢";}else{$senhacli=$dadosbase[0]['senha'];}
                                                       
                                                       //======================
                                                       fnmemoriafinal($connUser->connUser(),$cod_men); 
                                                        $return=array('ConsultaCadastroPorCPFResult'=> array(
                                                                                        'cartao' =>$dadosbase[0]['cartao'] ,
                                                                                        'tipocliente' =>$tipoclient,
                                                                                        'nome' => $dadosbase[0]['nome'],
                                                                                        'cpf' => fncompletadoc($dadosbase[0]['cpf']),
                                                                                        'cnpj'=>$dadosbase[0]['cnpj'],
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
                                                                                        'saldo' => fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],$decimal),
                                                                                        'saldoresgate' => fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                                                                        'senha'=> fnDecode($senhacli),
                                                                                        'msgerro' => $msg,
                                                                                        'ativacampanha' => '',
                                                                                        'dadosextras' => '',
                                                                                        'bloqueado' => '0',
                                                                                        'motivo' =>'',
                                                                                        'urlextrato' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                                        'retornodnamais' => $dnamais
                                                        ));
                                                        // if($DADOSLOGIN->CPF=='41380930871')
                                                        //{
                                                        //  return array('ConsultaCadastroPorCPFResult'=>array('msgerro' => var_export($return, true)));    
                                                       // }     
                                                array_to_xml($return,$xml_user_info);
                                                Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'OK',addslashes($xml_user_info->asXML()));
                                                return $return;      
                                                         
                                                    // return  $return;  
                                                     //   print_r($returnarray);
                                    }else{
                                         
                                                    //desabilita consulta na ifaro
                                                    // Inclui o arquivo com a função valida_cpf
                                                    // Verifica o CPF
                                                if($row['LOG_CONSEXT'] == 'S'){
                                                    if($CPF!=''){
                                                        if ( valida_cpf($CPF) ) {
                                                            //consultar na base local primeiro
                                                                $sqlifaro="select count(CPF) as TEM,log_cpf.* from log_cpf where CPF = '".$CPF."'";
                                                                $rowifaro=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlifaro));
                                                                
                                                                if($rowifaro['TEM'] != 0)
                                                                {
                                                                     $nome=$rowifaro['NOME'];
                                                                     $cpf1=$rowifaro['CPF'];
                                                                     $sexo3=$rowifaro['SEXO'];  
                                                                     $dtnasc=$rowifaro['DT_NASCIMENTO'];
                                                                     $msg='OK';
                                                                     //GRAVA NA BASE DA QUANTIDADE SE A EMPRESA DE CONSULTA FOR DIFERENTE DA PESQUISA
                                                                    if($rowifaro['COD_EMPRESA']!=$row['COD_EMPRESA'])
                                                                    {    
                                                                    $intermediaria="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                          VALUES 
                                                                                                          ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf1','$nome' ,'$sexo3', '$dtnasc', '".$row['COD_EMPRESA']."', '".$DADOSLOGIN->dadoslogin->login."','".$DADOSLOGIN->dadoslogin->idloja."','".$DADOSLOGIN->dadoslogin->idmaquina."');";
                                                                       mysqli_query($connAdm->connAdm(),$intermediaria);
                                                                    } 
                          
                                                                      Grava_log_consulta($connUser->connUser(),$LOG,'Consulta na base intermediaria!');           

                                                                }else{
                                                            
                                                            
                                                                            //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                                                            include '../../func/func_ifaro.php';  
                                                                            $resultIfaro=ifaro($CPF);
                                                                                if ($resultIfaro[0]['cpf'][0]=='')
                                                                                {$msg='Nenhum cadastro encontrado';
                                                                                        fnmemoriafinal($connUser->connUser(),$cod_men); 
                                                                                        $return=array('ConsultaCadastroPorCPFResult'=> array(
                                                                                                                                            'msgerro' => $msg,
                                                                                                                                            'retornodnamais' => '0'    
                                                                                                    )); 
                                                                                        array_to_xml($return,$xml_user_info);
                                                                                        Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,$msg,addslashes($xml_user_info->asXML()));
                                                                                        return $return;
                                                                                        exit();
                                                                                }else{	
                                                                                 //timeout na ifaro
                                                                                    if($resultIfaro[0]['cod_erro']=='0057643')
                                                                                    {
                                                                                       if($resultIfaro[0]['msg']!='OK'){$ifmsg=$resultIfaro[0]['msg'];}else{$ifmsg='OK';}   
                                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,msg,Time_consulta,SEXO,DT_NASCIMENTO) value
                                                                                             ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."',0,'".$ifmsg."','".$row['COD_EMPRESA']."','".$DADOSLOGIN->dadoslogin->login."','".$DADOSLOGIN->dadoslogin->idloja."','".$DADOSLOGIN->dadoslogin->idmaquina."','".$ifmsg."','".$resultIfaro[0]['timeCo']."','0','0')";
                                                                                              mysqli_query($connAdm->connAdm(),$sql);

                                                                                        $msg='Consulta automatica indisponivel!';
                                                                                        Grava_log_consulta($connUser->connUser(),$LOG,$msg);
                                                                                        
                                                                                           
                                                                                    }else{  
                                                                                        //ifaro OK
                                                                                        if($resultIfaro[0]['msg']!='OK'){$ifmsg=$resultIfaro[0]['msg'];}else{$ifmsg='OK';}   
                                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,msg,Time_consulta,SEXO,DT_NASCIMENTO) value
                                                                                             ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro[0]['cpf'][0]."','".$resultIfaro[0]['nome'][0]."','".$row['COD_EMPRESA']."','".$DADOSLOGIN->dadoslogin->login."','".$DADOSLOGIN->dadoslogin->idloja."','".$DADOSLOGIN->dadoslogin->idmaquina."','".$ifmsg."','".$resultIfaro[0]['timeCo']."','".$resultIfaro[0]['sexo'][0]."','".$resultIfaro[0]['datanascimento'][0]."')";
                                                                                              mysqli_query($connAdm->connAdm(),$sql);

                                                                                                $msg='OK';
                                                                                                $nome=$resultIfaro[0]['nome'][0];
                                                                                                $cpf1=$resultIfaro[0]['cpf'][0];
                                                                                                $sexo3=$resultIfaro[0]['sexo'][0];
                                                                                                $dtnasc=$resultIfaro[0]['datanascimento'][0];
                                                                                                //GRAVA NA BASE DA QUANTIDADE A PRIMEIRA CONSULTA
                                                                                                $intermediaria="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                          VALUES 
                                                                                                          ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf1','$nome' ,'$sexo3', '$dtnasc', '".$row['COD_EMPRESA']."', '".$DADOSLOGIN->dadoslogin->login."','".$DADOSLOGIN->dadoslogin->idloja."','".$DADOSLOGIN->dadoslogin->idmaquina."');";
                                                                                                mysqli_query($connAdm->connAdm(),$intermediaria);
                                                                         
                                                                                                $xamls='Consulta Cadastro cod 1!';
                                                                                                Grava_log_consulta($connUser->connUser(),$LOG,$xamls);
                                                                                            }
                                                                                }
                                                                }            
                                                                                      
                                                             fnmemoriafinal($connUser->connUser(),$cod_men);   
                                                            $return= array('ConsultaCadastroPorCPFResult'=> array(
                                                                                            'nome' =>$nome ,
                                                                                            'sexo' =>$sexo3,
                                                                                            'datanascimento' =>$dtnasc,
                                                                                            'msgerro' => $msg,
                                                                                            'url' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                                            'retornodnamais' => '1'    
                                                                                            ));
                                                            array_to_xml($return,$xml_user_info);
                                                            Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,$msg,addslashes($xml_user_info->asXML()));
                                                            return $return;
                                                           
                                                       }else{
                                                           $return=array('ConsultaCadastroPorCPFResult'=> array('msgerro' => 'Nenhum cadastro encontrado',
                                                                                                               'retornodnamais' => '0'));
                                                        array_to_xml($return,$xml_user_info);
                                                        Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'CPF digitado não é valido!',addslashes($xml_user_info->asXML()));
                                                        return $return;   
                                                       }
                                                       
                                                   }

                                                }else{
                                                    
                                                     fnmemoriafinal($connUser->connUser(),$cod_men);   
                                                     $return=array('ConsultaCadastroPorCPFResult'=> array('msgerro' => 'Nenhum cadastro encontrado',
                                                                                                         'retornodnamais' => '0'));
                                                     array_to_xml($return,$xml_user_info);
                                                     Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'Nenhum cadastro encontrado',addslashes($xml_user_info->asXML()));
                                                     return $return; 
                                                }        
                                       }
                                }else{
                                    $return=array('ConsultaCadastroPorCPFResult'=> array('msgerro' => ';-O Oh não! Consulta invalida tente outros campos ! :-['));
                                    array_to_xml($return,$xml_user_info);
                                    Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'-O Oh não! Consulta invalida tente outros campos ! :-[',addslashes($xml_user_info->asXML()));
                                    return $return; 
                                }
}
?>