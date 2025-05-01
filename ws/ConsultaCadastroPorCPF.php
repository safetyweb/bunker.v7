<?php
//função que captura os dados da pagina "soap"
//=================================================================== ConsultaCadastroPorCPF ====================================================================
$server->wsdl->addComplexType(
    'FichadeCadastroRetorno',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:int'),
        'tipocliente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tipocliente', 'type' => 'xsd:string'),
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cpf' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cpf', 'type' => 'xsd:string'),
        'cnpj'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cnpj', 'type' => 'xsd:int'),
        'rg' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'rg', 'type' => 'xsd:string'),
        'sexo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'sexo', 'type' => 'xsd:string'),
        'datanascimento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datanascimento', 'type' => 'xsd:date'),
        'estadocivil' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estadocivil', 'type' => 'xsd:string'),
        'email' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'email', 'type' => 'xsd:string'),
        'dataalteracao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataalteracao', 'type' => 'xsd:string'),
        'cartaotitular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartaotitular', 'type' => 'xsd:int'),
        'nomeportador'=>array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomeportador', 'type' => 'xsd:string'),
        'grupo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'grupo', 'type' => 'xsd:string'),
        'profissao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'profissao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'clientedesde', 'type' => 'xsd:date'),
        'endereco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'endereco', 'type' => 'xsd:string'),
        'numero' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'numero', 'type' => 'xsd:int'),
        'complemento' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'complemento', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bairro', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcelular', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcomercial', 'type' => 'xsd:string'),
        'saldo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoresgate', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'msgcampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgcampanha', 'type' => 'xsd:string'),
        'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url', 'type' => 'xsd:string'),
        'ativacampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ativacampanha', 'type' => 'xsd:string'),
        'dadosextras' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dadosextras', 'type' => 'xsd:string'),
        
        'bloqueado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bloqueado', 'type' => 'xsd:string'),
        'motivo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'motivo', 'type' => 'xsd:string'),
        'adesao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'adesao', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codatendente', 'type' => 'xsd:string'),
        'senha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'senha', 'type' => 'xsd:string'),
        'urlextrato' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urlextrato', 'type' => 'xsd:string'),
        'retornodnamais' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'retornodnamais', 'type' => 'xsd:string'),
        
        )
);


$server->register('ConsultaCadastroPorCPF',                // method name
                        array('CPF' => 'xsd:string',
                             'dadosLogin'=>'tns:LoginInfo'  
                              
                             ),    // input parameters
                        array('return' => 'tns:FichadeCadastroRetorno'),    // output parameters
                        $ns,         						// namespace
                        "$ns#ConsultaCadastroPorCPF",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'Add Parameters'            		// documentation
                    );

 function ConsultaCadastroPorCPF($CPF,$dadosLogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     
     //return  array('return'=> array('msgerro' => print_r($dadosLogin)));
    
    $CPF=fnlimpaCPF($CPF);
    $msg=valida_campo_vazio($dadosLogin['login'],'login','');
    if(!empty($msg)){return  array('return'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($dadosLogin['senha'],'senha','');
    if(!empty($msg)){return  array('return'=>array('msgerro' => $msg));} 
    $msg=valida_campo_vazio($CPF['CPF'],'CPF','numeric');
    if(!empty($msg)){return  array('return'=> array('msgerro' => $msg));}
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else {
        
       }
    //conn user
      $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);  
    
    //94858993000
    //EMPRESAS.LOG_CONSEXT => S OU N  ATIVA A CONSULTA,
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
    
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
        if($row['LOG_ATIVO']=='S')
        {      
                if( $passou!=1)
                {
                                //valida campo
                                fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Consulta Cadastro',$row['COD_EMPRESA']);
                                //inserir venda inteira na base de dados 
                            $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($CPF,true)));
                            $inserarray='INSERT INTO origemcadastro (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA)values
                                        ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                         "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$CPF.'","'.$xamls.'")';
                             mysqli_query($connUser->connUser(),$inserarray);
                             //Pegar o id da venda para inserir as messagens no log
                             $ID_LOG="SELECT last_insert_id(COD_ORIGEM) as ID_LOG from origemcadastro ORDER by COD_ORIGEM DESC limit 1;";
                             $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
                                
                                
                                $dadosbase=fn_consultaBase($connUser->connUser(),trim($CPF),'','','','',$row['COD_EMPRESA']);   
                               
                                                    
                                if($row['COD_CHAVECO'] == 1)
                                {    
                                    if($dadosbase[0]['contador'] == 1){   
                                       //consulta creditos
                                               $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                                               $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                                               fnmemoria($connUser->connUser(),'false',$dadosLogin['login']);

                                               //busca retorno profissão
                                                $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
                                                $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS));  
                                               
                                                $xamls= 'Consulta Cadastro cod 2!';
                                                Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);    

                                                       //Carrega dados da base de dados 
                                                    if ($dadosbase[0]['cpf']==''){$msg='Nenhum cadastro encontrado';}else{$msg='OK';}



                                                     // if($dadosbase[0]['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                                                       if($dadosbase[0]['sexo']=='1'){$sexo='M';}else{$sexo='F';}  
                                                       
                                                       return array('return'=> array(
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
                                                        'endereco' => $dadosbase[0]['endereco'],
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
                                                        'url' =>'',
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
                                                       
                                    }else{
                                        
                                                    //desabilita consulta na ifaro
                                                    // Inclui o arquivo com a função valida_cpf
                                                    // Verifica o CPF
                                                if($row['LOG_CONSEXT'] == 'S'){
                                                       if($CPF!=''){
                                                        if ( valida_cpf($CPF) ) {
                                                            //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                                            include './func/func_ifaro.php';  
                                                            $resultIfaro=ifaro($CPF);
                                                                if ($resultIfaro[0]['cpf'][0]=='')
                                                                        {$msg='Nenhum cadastro encontrado';
                                                                        return array('return'=> array(
                                                                                        'msgerro' => $msg,
                                                                                        'retornodnamais' => '0'    
                                                                                    ));  
                                                                        exit();
                                                                }else{	

                                                                    $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA) value
                                                                                                                 ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro[0]['cpf'][0]."','".$resultIfaro[0]['nome'][0]."','".$row['COD_EMPRESA']."','".$dadosLogin['login']."','".$dadosLogin['idloja']."','".$dadosLogin['idmaquina']."')";
                                                                                                                 mysqli_query($connAdm->connAdm(),$sql);

                                                                                 $msg='OK';
                                                                
                                                                }
                                                            $xamls='Consulta Cadastro cod 1!';
                                                            Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                            fnmemoria($connUser->connUser(),'false',$dadosLogin['login']);        

                                                            return array(
                                                            'nome' => $resultIfaro[0]['nome'][0],
                                                            'cpf' =>$resultIfaro[0]['cpf'][0],
                                                            'sexo' => $resultIfaro[0]['sexo'][0],
                                                            'datanascimento' => $resultIfaro[0]['datanascimento'][0],
                                                            'msgerro' => $msg,
                                                            'url' =>'',
                                                            'retornodnamais' => '1'    
                                                            );
                                                       }else{return array('return'=> array('msgerro' => ';-O Oh não! CPF digitado não é valido!'));}}

                                                }else{return array('msgerro'=>';-O Oh não! consulta cpf foi desabilitado!');}        
                                       }
                                }else{return array('return'=> array('msgerro' => ';-O Oh não! Consulta invalida tente outros campos ! :-['));}
                }                      
                else
                {  return array('return'=>  array('msgerro'=>'Id_cliente não confere com o cadastro!'));} 
        }else{
        return array('return'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
        }          

    }else{
       return  array('return'=> array('msgerro'=>'Erro Na autenticação'));
    }
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
