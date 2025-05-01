
<?php
//dados novo

 function ConsultaCadastroPorCPF($DADOSLOGIN) {

     include '../_system/Class_conn.php';
     include './func/function.php'; 
  
     //return  array('return'=> array('msgerro' => print_r($dadosLogin)));
     sleep(0.25);
    $CPF=fnlimpaCPF($DADOSLOGIN->CPF);
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->login,'login','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($DADOSLOGIN->dadoslogin->senha,'senha','');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=>array('msgerro' => $msg));} 
    $msg=valida_campo_vazio($DADOSLOGIN->CPF,'CPF','numeric');
    if(!empty($msg)){return  array('ConsultaCadastroPorCPFResult'=> array('msgerro' => $msg));}
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$DADOSLOGIN->dadoslogin->login."', '".fnEncode($DADOSLOGIN->dadoslogin->senha)."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  
        if ($row['COD_EMPRESA'] != $DADOSLOGIN->dadoslogin->idcliente)
        {
          $passou=1;
        } else {}
    //conn user
      $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);  
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
 //'url'=>"http://extrato.bunker.mk?key=$urlextrato",
    //94858993000
    //EMPRESAS.LOG_CONSEXT => S OU N  ATIVA A CONSULTA,
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
    
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
         //valida campo
        $cod_men=fnmemoria($connUser->connUser(),'true',$DADOSLOGIN->dadoslogin->login,'Consulta Cadastro',$row['COD_EMPRESA']);
                               
        //verifica se usuario esta desabilitado
    /* if($row['LOG_ESTATUS']=='N'){
           return array('ConsultaCadastroPorCPFResult'=> array( 'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!'));  
           exit();
        }*/   
    //////////////////////////////////////////////////////////////////////////////////////////////////
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
                       'XML'=>file_get_contents("php://input")
                      );
    $LOG=fngravaxmlbusca($arraydados1);
       
      Grava_log_consulta($connUser->connUser(),$LOG,'Inicio consulta CPF');
        if($row['LOG_ATIVO']=='S')
        {      
                if( $passou!=1)
                {
                                
                                
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



                                                     // if($dadosbase[0]['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                                                       if($dadosbase[0]['sexo']=='1'){$sexo='M';}else{$sexo='F';}  
                                                         
                                                        $return= array('ConsultaCadastroPorCPFResult'=> array(
                                                                                        'cartao' =>$dadosbase[0]['cartao'] ,
                                                                                        'tipocliente' =>$dadosbase[0]['tipocliente'],
                                                                                        'nome' => $dadosbase[0]['nome'],
                                                                                        'cpf' =>$dadosbase[0]['cpf'],
                                                                                        'cnpj'=>$dadosbase[0]['cnpj'],
                                                                                        'rg' => $dadosbase[0]['rg'],
                                                                                        'sexo' =>$sexo,
                                                                                        'datanascimento' => $dadosbase[0]['datanascimento'],
                                                                                        'estadocivil' => $dadosbase[0]['estadocivil'],
                                                                                        'email' => $dadosbase[0]['email'],
                                                                                        'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                                                                        'cartaotitular' =>$dadosbase[0]['cartaotitular'],
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
                                                                                        'saldo' => fnformatavalorretorno($retSaldo['TOTAL_CREDITO']),
                                                                                        'saldoresgate' => fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL']),
                                                                                        'msgerro' => $msg,
                                                                                        'msgcampanha' =>'',
                                                                                        'url' =>"http://extrato.bunker.mk?key=$urlextrato",
                                                                                        'ativacampanha' => '',
                                                                                        'dadosextras' => '',
                                                                                        'bloqueado' => '',
                                                                                        'motivo' =>'',
                                                                                        'adesao' =>'',
                                                                                        'codatendente' =>'',
                                                                                        'senha' =>'',
                                                                                        'urlextrato' =>'',
                                                                                        'retornodnamais' => '2'
                                                        ));
                                                         fnmemoriafinal($connUser->connUser(),$cod_men);   
                                                     return  $return;  
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
                                                                                                          ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf1','$nome' ,'$sexo3', '$dtnasc', '".$row['COD_EMPRESA']."', '".$dadosLogin['login']."','".$dadosLogin['idloja']."','".$dadosLogin['idmaquina']."');";
                                                                       mysqli_query($connAdm->connAdm(),$intermediaria);
                                                                    } 
                          
                                                                      Grava_log_consulta($connUser->connUser(),$LOG,'Consulta na base intermediaria!');           

                                                                }else{
                                                            
                                                            
                                                                            //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                                                            include 'func/func_ifaro.php';  
                                                                            $resultIfaro=ifaro($CPF);
                                                                                if ($resultIfaro[0]['cpf'][0]=='')
                                                                                {$msg='Nenhum cadastro encontrado';
                                                                                        return array('ConsultaCadastroPorCPFResult'=> array(
                                                                                                                                            'msgerro' => $msg,
                                                                                                                                            'retornodnamais' => '0'    
                                                                                                    ));  
                                                                                        exit();
                                                                                }else{	
                                                                                 //timeout na ifaro
                                                                                    if($resultIfaro[0]['cod_erro']=='0057643')
                                                                                    {
                                                                                       if($resultIfaro[0]['msg']!='OK'){$ifmsg=$resultIfaro[0]['msg'];}else{$ifmsg='OK';}   
                                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,msg,Time_consulta,SEXO,DT_NASCIMENTO) value
                                                                                             ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."',0,'".$ifmsg."','".$row['COD_EMPRESA']."','".$dadosLogin['login']."','".$dadosLogin['idloja']."','".$dadosLogin['idmaquina']."','".$ifmsg."','".$resultIfaro[0]['timeCo']."','0','0')";
                                                                                              mysqli_query($connAdm->connAdm(),$sql);

                                                                                        $msg='Consulta automatica indisponivel!';
                                                                                        Grava_log_consulta($connUser->connUser(),$LOG,$msg);
                                                                                        
                                                                                           
                                                                                    }else{  
                                                                                        //ifaro OK
                                                                                        if($resultIfaro[0]['msg']!='OK'){$ifmsg=$resultIfaro[0]['msg'];}else{$ifmsg='OK';}   
                                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,msg,Time_consulta,SEXO,DT_NASCIMENTO) value
                                                                                             ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro[0]['cpf'][0]."','".$resultIfaro[0]['nome'][0]."','".$row['COD_EMPRESA']."','".$dadosLogin['login']."','".$dadosLogin['idloja']."','".$dadosLogin['idmaquina']."','".$ifmsg."','".$resultIfaro[0]['timeCo']."','".$resultIfaro[0]['sexo'][0]."','".$resultIfaro[0]['datanascimento'][0]."')";
                                                                                              mysqli_query($connAdm->connAdm(),$sql);

                                                                                                $msg='OK';
                                                                                                $nome=$resultIfaro[0]['nome'][0];
                                                                                                $cpf1=$resultIfaro[0]['cpf'][0];
                                                                                                $sexo3=$resultIfaro[0]['sexo'][0];
                                                                                                $dtnasc=$resultIfaro[0]['datanascimento'][0];
                                                                                                //GRAVA NA BASE DA QUANTIDADE A PRIMEIRA CONSULTA
                                                                                                $intermediaria="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                          VALUES 
                                                                                                          ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf1','$nome' ,'$sexo3', '$dtnasc', '".$row['COD_EMPRESA']."', '".$dadosLogin['login']."','".$dadosLogin['idloja']."','".$dadosLogin['idmaquina']."');";
                                                                                                mysqli_query($connAdm->connAdm(),$intermediaria);
                                                                         
                                                                                                $xamls='Consulta Cadastro cod 1!';
                                                                                                Grava_log_consulta($connUser->connUser(),$LOG,$xamls);
                                                                                            }
                                                                                }
                                                                }            
                                                                                      
                                                               
                                                            $return= array('ConsultaCadastroPorCPFResult'=> array(
                                                                                            'nome' =>$nome ,
                                                                                            'cpf' =>$cpf1,
                                                                                            'sexo' => $sexo3,
                                                                                            'datanascimento' => $dtnasc,
                                                                                            'msgerro' => $msg,
                                                                                            'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                                                                            'retornodnamais' => '1'    
                                                                                            ));
                                                            //print_r($resultIfaro)
                                                           // $outros= addslashes(str_replace(array("\n",""),array(""," "), var_export($return,true)));
                                                           // $arralogin = str_replace(" ","",$outros);
                                                           //  Grava_log_consulta($connUser->connUser(),$LOG['ID_LOG'],$arralogin);
                                                              fnmemoriafinal($connUser->connUser(),$cod_men);
                                                            return  $return; 
                                                       }else{
                                                           Grava_log_consulta($connUser->connUser(),$LOG,'CPF digitado não é valido!');
                                                           return array('ConsultaCadastroPorCPFResult'=> array('msgerro' => ';-O Oh não! CPF digitado não é valido!'));}}

                                                }else{Grava_log_consulta($connUser->connUser(),$LOG,'consulta cpf foi desabilitado!');
                                                    return  array('ConsultaCadastroPorCPFResult'=> array('msgerro'=>';-O Oh não! consulta cpf foi desabilitado!'));}        
                                       }
                                }else{Grava_log_consulta($connUser->connUser(),$LOG,'Consulta invalida tente outros campos ');
                                    return array('ConsultaCadastroPorCPFResult'=> array('msgerro' => ';-O Oh não! Consulta invalida tente outros campos ! :-['));}
                }                      
                else
                {  Grava_log_consulta($connUser->connUser(),$LOG,'Id_cliente não confere com o cadastro!');
                    return array('ConsultaCadastroPorCPFResult'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));} 
        }else{
        Grava_log_consulta($connUser->connUser(),$LOG,'Por algum motivo seu login foi desabilitado.');    
        return array('ConsultaCadastroPorCPFResult'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
        }          

    }else{
       return  array('ConsultaCadastroPorCPFResult'=> array('msgerro'=>'Erro Na autenticação'));
    }
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//============================

$server = new SoapServer("wsdl.wsdl");
$server->addFunction("ConsultaCadastroPorCPF");
$server->addFunction("LoginInfo");
$server->handle();