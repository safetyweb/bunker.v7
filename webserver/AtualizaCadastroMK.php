<?php

//=================================================================== AtualizaCadastro ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'AtualizaCadastroRetorno',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'msgcampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgcampanha', 'type' => 'xsd:string'),
        'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url', 'type' => 'xsd:string'),
        'ativacampanha' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'ativacampanha', 'type' => 'xsd:string'),
        'dadosextras' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dadosextras', 'type' => 'xsd:string')
   )
);
//inserir dados
$server->wsdl->addComplexType(
    'FichadeCadastro',
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
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcelular', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'telcomercial', 'type' => 'xsd:string'),
        )
);

     
$server->register('AtualizaCadastroMK',
			array(
                              'cliente'=>'tns:FichadeCadastro',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:AtualizaCadastroRetorno'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#AtualizaCadastro',  //soapaction
			'rpc', //document
			'literal', // literal
			'AtualizaCadastro');  //description

function AtualizaCadastroMK($cliente,$dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php';  
     
     //valida campo
        
        $msg=valida_campo_vazio($cliente['cartao'],'cartao','numeric');
        if(!empty($msg)||!empty($msg1)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['nome'],'nome','string');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['tipocliente'],'tipocliente','string');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['cpf'],'cpf','numeric');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['sexo'],'sexo','numeric');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['datanascimento'],'datanascimento','DATA_BR');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['estadocivil'],'estadocivil','numeric');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($cliente['email'],'email','string');
        if(!empty($msg)){return array('msgerro' => $msg);}
        $msg=valida_campo_vazio($dadoslogin['codvendedor'],'codvendedor','numeric');
        if(!empty($msg)){return array('msgerro' => $msg);}
        
        
     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
   
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
if($row['LOG_ATIVO']=='S')
{ 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
      fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'atualiza Cadastro');  
        $dadosbase=fn_consultaBase($connUser->connUser(),$cliente['cpf'],$cliente['cnpj'],$cliente['cartao'],$cliente['email'],$cliente['telcelular']);   
      
        //atualiza por cpf
        if($row['COD_CHAVECO']==1){
             $dadosbase=fn_consultaBase($connUser->connUser(),$cliente['cpf'],$cliente['cnpj'],$cliente['cartao'],$cliente['email'],$cliente['telcelular']);   
      
                if($dadosbase[0]['COD_CLIENTE'] != 0){
                    if (valida_cpf($cliente['cpf'])){
                        //atualiza cliente se existir na base de dados!
                        if($cliente['cpf']!="")
                        {
                         $cpfcnpj=$cliente['cpf'];
                         
                        }else{
                         $cpfcnpj=$cliente['cnpj'];

                        }

                        //inserir cliente se nao existe na base de dados

                        try {
                           if($cliente['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                           $sql1 = " update clientes  
                                                      set NUM_CARTAO='".$cliente['cartao']."',
                                                          TIP_CLIENTE='".$cliente['tipocliente']."',
                                                          NOM_CLIENTE='".$cliente['nome']."',
                                                          NUM_CGCECPF=".$cpfcnpj.",
                                                          NUM_RGPESSO='".$cliente['rg']."',
                                                          COD_SEXOPES='".$sexo."',
                                                          DAT_NASCIME='".$cliente['datanascimento']."',
                                                          COD_ESTACIV='".fnLimpaCampoZero($cliente['estadocivil'])."',
                                                          DES_EMAILUS='".$cliente['email']."',
                                                          DAT_ALTERAC='".fnDataSql(is_Date($cliente['dataalteracao']))."',
                                                          COD_PROFISS='".fnLimpaCampoZero($cliente['profissao'])."',
                                                          DAT_CADASTR='".fnDataSql(is_Date($cliente['clientedesde']))."',
                                                          DES_ENDEREC='".$cliente['endereco']."',
                                                          NUM_ENDEREC='".$cliente['numero']."',
                                                          DES_COMPLEM='".$cliente['complemento']."',
                                                          DES_BAIRROC='".$cliente['bairro']."',
                                                          NOM_CIDADEC='".$cliente['cidade']."',
                                                          COD_ESTADOF='".$cliente['estado']."',
                                                          NUM_CEPOZOF='".$cliente['cep']."',
                                                          NUM_TELEFON='".$cliente['telresidencial']."',
                                                          NUM_CELULAR='".$cliente['telcelular']."',
                                                          COD_ALTERAC='".$dadoslogin['codvendedor']."'     
                                                      where COD_CLIENTE=".$dadosbase[0]['COD_CLIENTE'];

                           mysqli_query($connUser->connUser(),$sql1);
                           fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                         } catch (mysqli_sql_exception $e) {
                          return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');  
                         }


                        return array(
                           'msgerro' => 'Parabens seus dados foram atualizados!',
                           'msgcampanha'=>'Registro atualizado!',
                           'url' => '',
                           'ativacampanha' => 'sim',
                           'dadosextras' => ''
                        );
                    }else{ return array('msgerro' => ';-O Oh não! CPF digitado não é valido!');}

                }else{
                    
                    //valida cpf
                    if (valida_cpf($cliente['cpf'])){
                        //atualiza cliente se existir na base de dados!
                        if($cliente['cpf']!="")
                        {
                         $cpfcnpj=$cliente['cpf'];

                        }else{
                         $cpfcnpj=$cliente['cnpj'];

                        }
                  

                        //inserir cliente se nao existe na base de dados

                        try {
                           if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                           $sql1 = "insert into clientes (NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,DAT_NASCIME,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                          DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA
                                                          )values(
                                                          '".$cliente['cartao']."','".$cliente['tipocliente']."','".$cliente['nome']."','".$cpfcnpj."','".$cliente['rg']."','".$sexo."','".is_Date($cliente['datanascimento'])."',
                                                          '".fnLimpaCampoZero($cliente['estadocivil'])."','".$cliente['email']."','".fnDataSql(is_Date($cliente['dataalteracao']))."','".fnLimpaCampoZero($cliente['profissao'])."',
                                                          '".fnDataSql(is_Date($cliente['clientedesde']))."','".$cliente['endereco']."','".$cliente['numero']."','".$cliente['complemento']."',
                                                          '".$cliente['bairro']."','".$cliente['cidade']."','".$cliente['estado']."','".$cliente['cep']."' ,'".$cliente['telresidencial']."', 
                                                          '".$cliente['telcelular']."',".$row['COD_EMPRESA'].")";
                           mysqli_query($connUser->connUser(),$sql1);
                           fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                        } catch (mysqli_sql_exception $e) {
                             
                           return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');   
                        }
                    }else{ return array('msgerro' => ';-O Oh não! CPF digitado não é valido!');}

                        return array(
                                        'msgerro' => 'Parabens o cadastro foi realizado sucesso! :-)' ,
                                        'msgcampanha' =>'Seja muito bem vindo :-)',
                                        'url' => $row['COD_CHAVECO'],
                                        'ativacampanha' => 'sim',
                                        'dadosextras' => '');
                } 
                
        //fim da atualiza por cpf                                                    
        }elseif($row['COD_CHAVECO']==2){
            $dadosbase=fn_consultaBase($connUser->connUser(),'','',$cliente['cartao'],'','');   
      
           //atualiza cartão
         
            if($dadosbase[0]['COD_CLIENTE'] != 0){
                
                if(trim($dadosbase[0]['cpf']) != 0){
                    if (valida_cpf($cliente['cpf'])){
                        //atualiza cliente se existir na base de dados!
                        if($cliente['cpf']!="")
                        {
                         $cpfcnpj=$cliente['cpf'];
                          $tipopssoa=$cliente['tipocliente'];
                        }else{
                         $cpfcnpj=$cliente['cnpj'];
                         $tipopssoa=$cliente['tipocliente'];
                        }
                        }else{ return array('msgerro' => ';-O Oh não! CPF digitado não é valido!');} 
                }else{$cpfcnpj=0;}
                    //inserir cliente se nao existe na base de dados
                 
                    try {
                       
                            $sql1 = " update clientes  
                                                  set NUM_CARTAO='".$cliente['cartao']."',
                                                      TIP_CLIENTE='".$tipopssoa."',
                                                      NOM_CLIENTE='".$cliente['nome']."',
                                                      NUM_CGCECPF=".$cpfcnpj.",
                                                      NUM_RGPESSO='".$cliente['rg']."',
                                                      COD_SEXOPES='".$cliente['sexo']."',
                                                      DAT_NASCIME='".is_Date($cliente['datanascimento'])."',
                                                      COD_ESTACIV='".fnLimpaCampoZero($cliente['estadocivil'])."',
                                                      DES_EMAILUS='".$cliente['email']."',
                                                      DAT_ALTERAC='".fnDataSql(is_Date($cliente['dataalteracao']))."',
                                                      COD_PROFISS='".fnLimpaCampoZero($cliente['profissao'])."',
                                                      DAT_CADASTR='".fnDataSql(is_Date($cliente['clientedesde']))."',
                                                      DES_ENDEREC='".$cliente['endereco']."',
                                                      NUM_ENDEREC='".$cliente['numero']."',
                                                      DES_COMPLEM='".$cliente['complemento']."',
                                                      DES_BAIRROC='".$cliente['bairro']."',
                                                      NOM_CIDADEC='".$cliente['cidade']."',
                                                      COD_ESTADOF='".$cliente['estado']."',
                                                      NUM_CEPOZOF='".$cliente['cep']."',
                                                      NUM_TELEFON='".$cliente['telresidencial']."',
                                                      NUM_CELULAR='".$cliente['telcelular']."',
                                                      COD_ALTERAC='".$dadoslogin['codvendedor']."'    
                                                  where COD_CLIENTE=".$dadosbase[0]['COD_CLIENTE'];

                       mysqli_query($connUser->connUser(),$sql1);
                       fnmemoria($connUser->connUser(),'false',$dadoslogin['login']); 
                     } catch (mysqli_sql_exception $e) {
                      return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');  
                     }
                     //update na tabela de cartoes
                    $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$dadosbase[0]['cartao']; 
                    mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));
                    fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                    return array(
                           'msgerro' => 'Parabens seus dados foram atualizados!',
                           'msgcampanha'=>'Registro atualizado!',
                           'url' => '',
                           'ativacampanha' => 'sim',
                           'dadosextras' => ''
                    );
            

            }else{
                
                if($cliente['cpf']!="")
                {
                     $cpfcnpj=$cliente['cpf'];
                     $tipopssoa=$cliente['tipocliente'];
                 }else{
                     $cpfcnpj=$cliente['cnpj'];
                     $tipopssoa=$cliente['tipocliente'];       
                 }

                    //inserir cliente se nao existe na base de dados

                    try {
                        if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                       $sql1 = "insert into clientes (NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,DAT_NASCIME,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                      DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA
                                                      )values(
                                                      '".$cliente['cartao']."','".$cliente['tipocliente']."','".$cliente['nome']."','".$cpfcnpj."','".$cliente['rg']."','".$sexo."','".is_Date($cliente['datanascimento'])."',
                                                      '".fnLimpaCampoZero($cliente['estadocivil'])."','".$cliente['email']."','".fnDataSql(is_Date($cliente['dataalteracao']))."','".fnLimpaCampoZero($cliente['profissao'])."',
                                                      '".fnDataSql(is_Date($cliente['clientedesde']))."','".$cliente['endereco']."','".$cliente['numero']."','".$cliente['complemento']."',
                                                      '".$cliente['bairro']."','".$cliente['cidade']."','".$cliente['estado']."','".$cliente['cep']."' ,'".$cliente['telresidencial']."', 
                                                      '".$cliente['telcelular']."',".$row['COD_EMPRESA'].")";
                       mysqli_query($connUser->connUser(),$sql1);
                       fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                     } catch (mysqli_sql_exception $e) {
                       return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');   
                     }
                        
                           //update na tabela de cartoes
                            $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$dadosbase[0]['cartao']; 
                            mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));
                    fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);    
                    return array(
                                    'msgerro' => 'Parabens o cadastro foi realizado sucesso! :-)' ,
                                    'msgcampanha' =>'Seja muito bem vindo :-)',
                                    'url' => '',
                                    'ativacampanha' => 'sim',
                                    'dadosextras' => '');
            } 
        //fim da atualiza por cartao  
           }
        
     
         
        }else{
            return array('msgerro'=>'Oh não :-o!  erro Na autenticação :-[ ');

        }   
}else{
  return array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
}    
        
      mysqli_close($connUserws->connUser()); 
      mysqli_close($connAdm->connAdm()); 
     
}


//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
