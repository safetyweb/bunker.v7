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
        )
);


$server->register('ConsultaCadastroMK',
			array(
                              'CPF'=>'xsd:string',
                              'CNPJ'=>'xsd:string', 
                              'cartao'=> 'xsd:string',
                              'email'=>'xsd:string',
                              'telcelular'=>'xsd:string',  
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:FichadeCadastroRetorno'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#ConsultaCadastro',  //soapaction
			'rpc', //document
			'literal', // literal
			'Busca CPF');  //description

 function ConsultaCadastroMK($CPF,$CNPJ,$cartao,$email,$telcelular,$dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     
    
    $msg=valida_campo_vazio($email,'email','');
    if(!empty($msg)){return array('msgerro' => $msg);}
    $msg=valida_campo_vazio($dadoslogin['login'],'login','');
    if(!empty($msg)){return array('msgerro' => $msg);}
    $msg=valida_campo_vazio($dadoslogin['senha'],'senha','');
    if(!empty($msg)){return array('msgerro' => $msg);} 
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
      $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);  
    //94858993000
    //EMPRESAS.LOG_CONSEXT => S OU N  ATIVA A CONSULTA,
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
if($row['LOG_ATIVO']=='S')
{    
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
    //valida campo
    fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'Consulta Cadastro');
    
        $dadosbase=fn_consultaBase($connUser->connUser(),trim($CPF),trim($CNPJ),trim($cartao),trim($email),trim($telcelular));   
        
             
        if($dadosbase[0]['COD_CLIENTE'] != 0 && $row['COD_CHAVECO'] == 1){   
           //consulta creditos
                   $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                   $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                   fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
            
                           //Carrega dados da base de dados 
                           return array(
                            'cartao' =>$dadosbase[0]['cartao'] ,
                            'tipocliente' =>$dadosbase[0]['tipocliente'],
                            'nome' => $dadosbase[0]['nome'],
                            'cpf' =>$dadosbase[0]['cpf'],
                            'cnpj'=>$dadosbase[0]['cnpj'],
                            'rg' => $dadosbase[0]['rg'],
                            'sexo' =>$dadosbase[0]['sexo'],
                            'datanascimento' => $dadosbase[0]['datanascimento'],
                            'estadocivil' => $dadosbase[0]['estadocivil'],
                            'email' => $dadosbase[0]['email'],
                            'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                            'cartaotitular' =>$dadosbase[0]['cartaotitular'],
                            'nomeportador'=>$dadosbase[0]['nomeportador'],
                            'grupo' => $dadosbase[0]['grupo'],
                            'profissao' =>$dadosbase[0]['profissao'],
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
                            'saldo' =>$retSaldo['TOTAL_CREDITO'],
                            'saldoresgate' =>$retSaldo['CREDITO_DISPONIVEL'],
                            'msgerro' => '',
                            'msgcampanha' =>'',
                            'url' =>'',
                            'ativacampanha' => '',
                            'dadosextras' => ''
                            );
        }elseif($row['COD_CHAVECO']==1){
                    //desabilita consulta na ifaro
                    // Inclui o arquivo com a função valida_cpf
                    // Verifica o CPF
                if($row['LOG_CONSEXT'] == 'S'){
                       if($CPF!=''){
                        if ( valida_cpf($CPF) ) {
                            //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                            include './func/func_ifaro.php';  
                            $resultIfaro=ifaro($CPF);
                            if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                            
                           $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA) value
                                    ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro[0]['cpf'][0]."','".$resultIfaro[0]['nome'][0]."','".$row['COD_EMPRESA']."','".$dadoslogin['login']."','".$dadoslogin['idloja']."','".$dadoslogin['idmaquina']."')";
                                    mysqli_query($connAdm->connAdm(),$sql);
                            fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);        
                            
                            return array(
                            'nome' => $resultIfaro[0]['nome'][0],
                            'cpf' =>$resultIfaro[0]['cpf'][0],
                            'sexo' => $sexo,
                            'datanascimento' => $resultIfaro[0]['datanascimento'][0],
                            'saldo' =>'',
                            'saldoresgate' =>'',   
                            'msgerro' => '',
                            'msgcampanha' => '',
                            'url' =>'',
                            'ativacampanha' => '',
                            'dadosextras' => ''
                            );
                       }else{return array('msgerro' => ';-O Oh não! CPF digitado não é valido!');}}
                        
                }else{return array('msgerro'=>';-O Oh não! consulta cpf foi desabilitado!');}        
        }elseif($row['COD_CHAVECO']==2){ 
                       //busca por cartão   aqui a ifaro esta desabilitada 
                        if($dadosbase[0]['COD_CLIENTE'] !=0 && $row['COD_CHAVECO'] == 2){
                            
                            
                              $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                               $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                               fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                        //Carrega dados da base de dados 
                                       return array(
                                        'cartao' =>$dadosbase[0]['cartao'] ,
                                        'tipocliente' =>$dadosbase[0]['tipocliente'],
                                        'nome' => $dadosbase[0]['nome'],
                                        'cpf' =>$dadosbase[0]['cpf'],
                                        'cnpj'=>$dadosbase[0]['cnpj'],
                                        'rg' => $dadosbase[0]['rg'],
                                        'sexo' =>$dadosbase[0]['sexo'],
                                        'datanascimento' => $dadosbase[0]['datanascimento'],
                                        'estadocivil' => $dadosbase[0]['estadocivil'],
                                        'email' => $dadosbase[0]['email'],
                                        'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                        'cartaotitular' =>$dadosbase[0]['cartaotitular'],
                                        'nomeportador'=>$dadosbase[0]['nomeportador'],
                                        'grupo' => $dadosbase[0]['grupo'],
                                        'profissao' =>$dadosbase[0]['profissao'],
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
                                        'saldo' =>$retSaldo['TOTAL_CREDITO'],
                                        'saldoresgate' =>$retSaldo['CREDITO_DISPONIVEL'],
                                        'msgerro' => '',
                                        'msgcampanha' =>'',
                                        'url' =>'',
                                        'ativacampanha' => '',
                                        'dadosextras' => ''
                                        );   
                        } else {return array('msgerro' => ';-O Oh não! O cliente não  esta cadastrador na base de daodos ! :-[');}
                     }elseif($row['LOG_CONSEXT'] == 'N' && $row['COD_CHAVECO']==3){ 
                       //busca por telefone   aqui a ifaro esta desabilitada 
                        if($dadosbase[0]['COD_CLIENTE'] !=0 && $row['LOG_CONSEXT'] == 'N' && $row['COD_CHAVECO'] == 3){
                            
                              $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
                               $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$consultasaldo));
                                        //Carrega dados da base de dados 
                               fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                       return array(
                                        'cartao' =>$dadosbase[0]['cartao'] ,
                                        'tipocliente' =>$dadosbase[0]['tipocliente'],
                                        'nome' => $dadosbase[0]['nome'],
                                        'cpf' =>$dadosbase[0]['cpf'],
                                        'cnpj'=>$dadosbase[0]['cnpj'],
                                        'rg' => $dadosbase[0]['rg'],
                                        'sexo' =>$dadosbase[0]['sexo'],
                                        'datanascimento' => $dadosbase[0]['datanascimento'],
                                        'estadocivil' => $dadosbase[0]['estadocivil'],
                                        'email' => $dadosbase[0]['email'],
                                        'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                        'cartaotitular' =>$dadosbase[0]['cartaotitular'],
                                        'nomeportador'=>$dadosbase[0]['nomeportador'],
                                        'grupo' => $dadosbase[0]['grupo'],
                                        'profissao' =>$dadosbase[0]['profissao'],
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
                                        'saldo' =>$retSaldo['TOTAL_CREDITO'],
                                        'saldoresgate' =>$retSaldo['CREDITO_DISPONIVEL'],
                                        'msgerro' => '',
                                        'msgcampanha' =>'',
                                        'url' =>'',
                                        'ativacampanha' => '',
                                        'dadosextras' => ''
                                        );   
                        } else {return array('msgerro' => ';-O Oh não! O cliente/telefone  não  esta cadastrador na base de daodos ! :-[');}
                     }else{return array('msgerro' => ';-O Oh não! Consulta invalida tente outros campos ! :-[');}
        
        
                         
    }else{
        return array('msgerro'=>'Erro Na autenticação');
    }

    
}else{
  return array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
}    
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
