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

     
$server->register('AtualizaCadastro',
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

function AtualizaCadastro($cliente,$dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php';  
     
     
      
    $msg=valida_campo_vazio($dadoslogin['idloja'],'idloja','numeric');
    if(!empty($msg)){return array('msgerro' => $msg);}
    $msg=valida_campo_vazio($dadoslogin['idmaquina'],'idmaquina','string');
    if(!empty($msg)){return array('msgerro' => $msg);}                            
  
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
     //compara os id_cliente com o cod_empresa
  
        if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
        {
          $passou=1;
        } else {
        
        }
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    //verifica lojas e maquinas 
    
   // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
    //limpa CPF
    $cpfcnpjinicial=fnlimpaCPF($cliente['cpf']);
  
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            if($row['LOG_ATIVO']=='S')
            {
                    if( $passou!=1)
                    {  
                        
                        fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'atualiza Cadastro',$row['COD_EMPRESA']);  
                        
                            //inserir venda inteira na base de dados 
                            $dados_login= addslashes(str_replace(array("\n",""),array(""," "), var_export($dadoslogin,true)));
                            $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($cliente,true)));
                            $inserarray='INSERT INTO origemcadastro (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                        ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                         "'.$row['COD_USUARIO'].'","'.$dadoslogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadoslogin['idloja'].'","'.$dadoslogin['idmaquina'].'","'.$cliente['cpf'].'","'.$xamls.'","'.$dados_login.'")';
                             mysqli_query($connUser->connUser(),$inserarray);
                             //Pegar o id da venda para inserir as messagens no log
                             $ID_LOG="SELECT last_insert_id(COD_ORIGEM) as ID_LOG from origemcadastro ORDER by COD_ORIGEM DESC limit 1;";
                             $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
                        
                          $lojas=fnconsultaLoja($connAdm->connAdm(),$connUser->connUser(),$dadoslogin['idloja'],$dadoslogin['idmaquina'],$row['COD_EMPRESA']);
                          $dadosbase=fn_consultaBase($connUser->connUser(),$cpfcnpjinicial,$cliente['cnpj'],$cliente['cartao'],$cliente['email'],$cliente['telcelular'],$row['COD_EMPRESA']);   
                           //busca retorno profissão
                          $bus_PROFISS = "select * from profissoes where  DES_PROFISS='".$cliente['profissao']."'";
                          $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS)); 
                          
                          if($profiss_ret['COD_PROFISS']=='')
                          {
                           $PROFISSAO=0;                         
                          }
                          else
                          {
                          $PROFISSAO=$profiss_ret['COD_PROFISS'];   
                          }    
                          
                          //verifica se o cliente e pj ou pf
                          if($cliente['tipocliente']!='PJ')
                          {    
                                  //atualiza por cpf
                                  if($row['COD_CHAVECO']==1){
                                       $dadosbase=fn_consultaBase($connUser->connUser(),$cpfcnpjinicial,$cliente['cnpj'],$cliente['cartao'],$cliente['email'],$cliente['telcelular'],$row['COD_EMPRESA']);   
                                        //valida campo


                                  $msg=valida_campo_vazio($cliente['cartao'],'cartao','numeric');
                                  if(!empty($msg)||!empty($msg1)){return array('msgerro' => $msg);}
                                  $msg=valida_campo_vazio($cliente['nome'],'nome','string');
                                  if(!empty($msg)){return array('msgerro' => $msg);}
                                 
                                  //$msg=valida_campo_vazio($cliente['profissao'],'profissao','string');
                                  // if(!empty($msg)){return array('msgerro' => $msg);}
                                  $msg=valida_campo_vazio($cpfcnpjinicial,'cpf','numeric');
                                  if(!empty($msg)){return array('msgerro' => $msg);}
                                  $msg=valida_campo_vazio($cliente['sexo'],'sexo','string');
                                  if(!empty($msg)){return array('msgerro' => $msg);}
                                  $msg=valida_campo_vazio($cliente['datanascimento'],'datanascimento','DATA_US');
                                  if(!empty($msg)){return array('msgerro' => $msg);}
                                  $msg=valida_campo_vazio($cliente['email'],'email','string');
                                  if(!empty($msg)){return array('msgerro' => $msg);}
                                
                                 
                                   $datenascime= fnDataBR($cliente['datanascimento']);

                                          if($dadosbase[0]['COD_CLIENTE'] != 0){
                                              if (valida_cpf($cpfcnpjinicial)){
                                                  //atualiza cliente se existir na base de dados!
                                                  if($cpfcnpjinicial!="")
                                                  {
                                                   $cpfcnpj=$cpfcnpjinicial;

                                                  }else{
                                                   $cpfcnpj=$cliente['cnpj'];

                                                  }

                                                  //atualiza cliente se ja existe na base de dados

                                                  try {
                                                     
                                                    if($cliente['sexo']=='M'){$sexo='1';}else{$sexo='2';}  
                                                    if($cliente['sexo'] === '1'){$sexo = '1';}
                                                    if($cliente['sexo'] === '2'){$sexo='2';}
                                                     
                                                    
                                                     if($dadoslogin['codvendedor']=='' || $dadoslogin['codvendedor']=='?'){$codvendedor=0;} else {$codvendedor=$dadoslogin['codvendedor'];}
                                                     if($cliente['tipocliente']=='PF' || $cliente['tipocliente']=='')  {$TP_CLIENTE='F';}
                                                     if($cliente['dataalteracao']!=''){$date_alterac=$cliente['dataalteracao'];}else{$date_alterac=date('Y-m-d H:m:s');}
                                                 
                                                 // $lojas[0]['COD_MAQUINA']
                                                     $sql1 = " update clientes  
                                                                                set NUM_CARTAO='".$cliente['cartao']."',
                                                                                    TIP_CLIENTE='".$TP_CLIENTE."',
                                                                                    NOM_CLIENTE='".$cliente['nome']."',
                                                                                    NUM_CGCECPF=".$cpfcnpj.",
                                                                                    NUM_RGPESSO='".$cliente['rg']."',
                                                                                    COD_SEXOPES='".$sexo."',
                                                                                    TIP_CLIENTE='".$TP_CLIENTE."',    
                                                                                    DAT_NASCIME='".$datenascime."',
                                                                                    COD_ESTACIV='".fnLimpaCampoZero($cliente['estadocivil'])."',
                                                                                    DES_EMAILUS='".$cliente['email']."',
                                                                                    DAT_ALTERAC='".fnDataSql(is_Date($date_alterac))."',
                                                                                    COD_PROFISS='".$PROFISSAO."',
                                                                                    DAT_CADASTR='".fnDataSql(is_Date($cliente['clientedesde']))."',
                                                                                    DES_ENDEREC='".$cliente['endereco']."',
                                                                                    NUM_ENDEREC='".$cliente['numero']."',
                                                                                    DES_COMPLEM='".$cliente['complemento']."',
                                                                                    DES_BAIRROC='".$cliente['bairro']."',
                                                                                    NOM_CIDADEC='".$cliente['cidade']."',
                                                                                    COD_ESTADOF='".$cliente['estado']."',
                                                                                    NUM_CEPOZOF='".fnlimpaCEP($cliente['cep'])."',
                                                                                    COD_USUCADA='".$row['COD_USUARIO']."',    
                                                                                    NUM_TELEFON='".$cliente['telresidencial']."',
                                                                                    NUM_CELULAR='".$cliente['telcelular']."',
                                                                                    COD_UNIVEND= '". $lojas[0]['COD_UNIVEND']."',
                                                                                    COD_MAQUINA='".$lojas[0]['COD_MAQUINA']."', 
                                                                                    COD_ALTERAC='".$codvendedor."'     
                                                                                where COD_CLIENTE=".$dadosbase[0]['COD_CLIENTE'];



                                                     mysqli_query($connUser->connUser(),$sql1);
                                                       $msg='PF atualizado com sucesso!';
                                                        $xamls= addslashes($msg);
                                                        Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                     fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                                   } catch (mysqli_sql_exception $e) {
                                                       
                                                    return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');  
                                                   }


                                                  return array(
                                                     'msgerro' => 'OK',
                                                     'msgcampanha'=>'Registro atualizado!',
                                                     'url' =>'',
                                                     'ativacampanha' => 'sim',
                                                     'dadosextras' => ''
                                                  );
                                              }else{ return array('msgerro' => ';-O Oh não! CPF digitado não é valido! :-[');}

                                          }else{

                                              //valida cpf
                                              if (valida_cpf($cpfcnpjinicial)){
                                                  //atualiza cliente se existir na base de dados!
                                                  if($cpfcnpjinicial!="")
                                                  {
                                                   $cpfcnpj=$cpfcnpjinicial;

                                                  }else{
                                                   $cpfcnpj=$cliente['cnpj'];

                                                  }


                                                  //inserir cliente se nao existe na base de dados

                                                  try { 
                                                                                     
                                                     if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                                                     if($cliente['tipocliente']=='PF')  {$TP_CLIENTE='F';}
                                                     $sql1 = "insert into clientes (NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,DAT_NASCIME,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                                                    DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA,COD_UNIVEND,COD_MAQUINA,
                                                                                    COD_USUCADA,LOG_ESTATUS
                                                                                    )values(
                                                                                    '".$cliente['cartao']."','".$TP_CLIENTE."','".$cliente['nome']."','".$cpfcnpj."','".$cliente['rg']."','".$sexo."','".$datenascime."',
                                                                                    '".fnLimpaCampoZero($cliente['estadocivil'])."','".$cliente['email']."','".fnDataSql(is_Date($cliente['dataalteracao']))."','".$PROFISSAO."',
                                                                                    '".fnDataSql(is_Date($cliente['clientedesde']))."','".$cliente['endereco']."','".$cliente['numero']."','".$cliente['complemento']."',
                                                                                    '".$cliente['bairro']."','".$cliente['cidade']."','".$cliente['estado']."','".fnlimpaCEP($cliente['cep'])."' ,'".$cliente['telresidencial']."', 
                                                                                    '".$cliente['telcelular']."',".$row['COD_EMPRESA'].",".$lojas[0]['COD_UNIVEND'].",".$lojas[0]['COD_MAQUINA'].",'".$row['COD_USUARIO']."','S')";
                                                     mysqli_query($connUser->connUser(),$sql1);
                                                        $ID_CLIENTE="SELECT last_insert_id(COD_CLIENTE) as COD_CLIENTE from clientes ORDER by COD_CLIENTE DESC limit 1;";
                                                        $COD_CLIENTEULT = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_CLIENTE));
                                                        $COD_CLIENTERET=$COD_CLIENTEULT['COD_CLIENTE']; 
                                                     
                                                      $class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(
                                                                                                     ".$COD_CLIENTERET.",
                                                                                                     ".$row['COD_EMPRESA']."
                                                                                                     )";
                                                       mysqli_query($connUser->connUser(),$class_cad);
                                                        $msg='PF inserido  com sucesso!';
                                                        $xamls= addslashes($msg);
                                                        Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                       
                                                     fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                                  } catch (mysqli_sql_exception $e) {

                                                     return array('msgerro' =>'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');   
                                                  }
                                              }else{ return array('msgerro' => ';-O Oh não! CPF digitado não é valido!:-[');}

                                                  return array(
                                                                  'msgerro' => 'OK' ,
                                                                  'msgcampanha' =>'Seja muito bem vindo :-)',
                                                                  'url' => '',
                                                                  'ativacampanha' => '',
                                                                  'dadosextras' => '');
                                          } 

                                  //fim da atualiza por cpf                                                    
                                  }elseif($row['COD_CHAVECO']==2){
                                      $dadosbase=fn_consultaBase($connUser->connUser(),'','',$cliente['cartao'],'','',$row['COD_EMPRESA']);   

                                     //atualiza cartão

                                      if($dadosbase[0]['COD_CLIENTE'] != 0){

                                          if(trim($dadosbase[0]['cpf']) != 0){
                                              if (valida_cpf($cpfcnpjinicial)){
                                                  //atualiza cliente se existir na base de dados!
                                                  if($cpfcnpjinicial!="")
                                                  {
                                                   $cpfcnpj=$cpfcnpjinicial;
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
                                                                                DAT_NASCIME='".$datenascime."',
                                                                                COD_ESTACIV='".fnLimpaCampoZero($cliente['estadocivil'])."',
                                                                                DES_EMAILUS='".$cliente['email']."',
                                                                                DAT_ALTERAC='".fnDataSql(is_Date($cliente['dataalteracao']))."',
                                                                                COD_PROFISS='".$PROFISSAO."',
                                                                                DAT_CADASTR='".fnDataSql(is_Date($cliente['clientedesde']))."',
                                                                                DES_ENDEREC='".$cliente['endereco']."',
                                                                                NUM_ENDEREC='".$cliente['numero']."',
                                                                                DES_COMPLEM='".$cliente['complemento']."',
                                                                                DES_BAIRROC='".$cliente['bairro']."',
                                                                                NOM_CIDADEC='".$cliente['cidade']."',
                                                                                COD_ESTADOF='".$cliente['estado']."',
                                                                                NUM_CEPOZOF='".fnlimpaCEP($cliente['cep'])."',
                                                                                NUM_TELEFON='".$cliente['telresidencial']."',
                                                                                NUM_CELULAR='".$cliente['telcelular']."',
                                                                                COD_USUCADA='".$row['COD_USUARIO']."',
                                                                                COD_UNIVEND= '". $lojas[0]['COD_UNIVEND']."',
                                                                                COD_MAQUINA='".$lojas[0]['COD_MAQUINA']."',    
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
                                                $msg='PF gera cartao!';
                                                $xamls= addslashes($msg);
                                                Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                              fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                              return array(
                                                     'msgerro' => 'OK',
                                                     'msgcampanha'=>'Registro atualizado!',
                                                     'url' => '',
                                                     'ativacampanha' => 'sim',
                                                     'dadosextras' => ''
                                              );


                                      }else{

                                          if($cpfcnpjinicial!="")
                                          {
                                               $cpfcnpj=$cpfcnpjinicial;
                                               $tipopssoa=$cliente['tipocliente'];
                                           }else{
                                               $cpfcnpj=$cliente['cnpj'];
                                               $tipopssoa=$cliente['tipocliente'];       
                                           }

                                              //inserir cliente se nao existe na base de dados

                                              try {
                                                  
                                                  if($cliente['tipocliente']=='PF')  {$TP_CLIENTE='F';}        
                                                  if($resultIfaro[0]['sexo'][0]=='M'){$sexo=1;}else{$sexo=2;}
                                                 $sql1 = "insert into clientes (NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,DAT_NASCIME,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                                                DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA,COD_UNIVEND,COD_MAQUINA,
                                                                                COD_USUCADA,LOG_ESTATUS
                                                                                )values(
                                                                                '".$cliente['cartao']."','". $TP_CLIENTE."','".$cliente['nome']."','".$cpfcnpj."','".$cliente['rg']."','".$sexo."','".$datenascime."',
                                                                                '".fnLimpaCampoZero($cliente['estadocivil'])."','".$cliente['email']."','".fnDataSql(is_Date($cliente['dataalteracao']))."','".$PROFISSAO."',
                                                                                '".fnDataSql(is_Date($cliente['clientedesde']))."','".$cliente['endereco']."','".$cliente['numero']."','".$cliente['complemento']."',
                                                                                '".$cliente['bairro']."','".$cliente['cidade']."','".$cliente['estado']."','".fnlimpaCEP($cliente['cep'])."' ,'".$cliente['telresidencial']."', 
                                                                                '".$cliente['telcelular']."',".$row['COD_EMPRESA'].",".$lojas[0]['COD_UNIVEND'].",".$lojas[0]['COD_MAQUINA'].",'".$row['COD_USUARIO']."','S')";
                                                 mysqli_query($connUser->connUser(),$sql1);
                                                 $msg='PF cadastrado com sucesso!';
                                                        $xamls= addslashes($msg);
                                                        Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                 fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                               } catch (mysqli_sql_exception $e) {
                                                 return array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ ');   
                                               }

                                                     //update na tabela de cartoes
                                                      $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$dadosbase[0]['cartao']; 
                                                      mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));
                                              fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);    
                                              return array(
                                                              'msgerro' => 'OK' ,
                                                              'msgcampanha' =>'Seja muito bem vindo :-)',
                                                              'url' => '',
                                                              'ativacampanha' => 'sim',
                                                              'dadosextras' => '');
                                      } 
                                  //fim da atualiza por cartao  
                                     }
                            }
                            else
                            {
                              //cadastro de cnpj
                              /////////////////////////////////////////
                                  try {
                                      $dadosbase=fn_consultaBase($connUser->connUser(),'',$cliente['cnpj'],'','','',$row['COD_EMPRESA']);  


                                      if($dadosbase[0]['COD_CLIENTE'] != 0)
                                      {    
                                          if($cliente['tipocliente']=='PJ')
                                              {$TP_CLIENTE='P';}   
                                          if($cliente['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                                          if($dadoslogin['codvendedor']==''){$codvendedor=0;} else {$codvendedor=$dadoslogin['codvendedor'];}

                                                                     $sql1 = " update clientes  
                                                                                  set NUM_CARTAO='".$cliente['cartao']."',
                                                                                      TIP_CLIENTE='".$TP_CLIENTE."',
                                                                                      NOM_CLIENTE='".$cliente['nome']."',
                                                                                      NUM_CGCECPF=".$cliente['cnpj'].",
                                                                                      NUM_RGPESSO='".$cliente['rg']."',
                                                                                      COD_SEXOPES='".$sexo."',
                                                                                      COD_ESTACIV='".fnLimpaCampoZero($cliente['estadocivil'])."',
                                                                                      DES_EMAILUS='".$cliente['email']."',
                                                                                      DAT_ALTERAC='".fnDataSql(is_Date($cliente['dataalteracao']))."',
                                                                                      COD_PROFISS='".$PROFISSAO."',
                                                                                      DAT_CADASTR='".fnDataSql(is_Date($cliente['clientedesde']))."',
                                                                                      DES_ENDEREC='".$cliente['endereco']."',
                                                                                      NUM_ENDEREC='".$cliente['numero']."',
                                                                                      DES_COMPLEM='".$cliente['complemento']."',
                                                                                      DES_BAIRROC='".$cliente['bairro']."',
                                                                                      NOM_CIDADEC='".$cliente['cidade']."',
                                                                                      COD_ESTADOF='".$cliente['estado']."',
                                                                                      NUM_CEPOZOF='".fnlimpaCEP($cliente['cep'])."',
                                                                                      COD_USUCADA='".$row['COD_USUARIO']."',    
                                                                                      NUM_TELEFON='".$cliente['telresidencial']."',
                                                                                      NUM_CELULAR='".$cliente['telcelular']."',
                                                                                      COD_UNIVEND= '". $lojas[0]['COD_UNIVEND']."',
                                                                                    COD_MAQUINA='".$lojas[0]['COD_MAQUINA']."',    
                                                                                      COD_ALTERAC='".$codvendedor."'    
                                                                                  where COD_CLIENTE=".$dadosbase[0]['COD_CLIENTE'];


                                                       mysqli_query($connUser->connUser(), rtrim(trim($sql1)));
                                                       $msg='PJ atualizado com sucesso!';
                                                       $xamls= addslashes($msg);
                                                       Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                       fnmemoria($connUser->connUser(),'false',$dadoslogin['login']); 
                                      } else {
                                          if($cliente['sexo']=='M'){$sexo=1;}else{$sexo=2;}
                                          if($cliente['tipocliente']=='PJ')
                                              {$TP_CLIENTE='P';}
                                               
                                                 $sql1 = "insert into clientes (NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                                                DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA,COD_UNIVEND,COD_MAQUINA
                                                                                ,COD_USUCADA,LOG_ESTATUS
                                                                                )values(
                                                                                '".$cliente['cartao']."','".$TP_CLIENTE."','".$cliente['nome']."','".$cliente['cnpj']."','".$cliente['rg']."','".$sexo."',
                                                                                '".fnLimpaCampoZero($cliente['estadocivil'])."','".$cliente['email']."','".fnDataSql(is_Date($cliente['dataalteracao']))."','".$PROFISSAO."',
                                                                                '".fnDataSql(is_Date($cliente['clientedesde']))."','".$cliente['endereco']."','".$cliente['numero']."','".$cliente['complemento']."',
                                                                                '".$cliente['bairro']."','".$cliente['cidade']."','".$cliente['estado']."','".fnlimpaCEP($cliente['cep'])."' ,'".$cliente['telresidencial']."', 
                                                                                '".$cliente['telcelular']."',".$row['COD_EMPRESA'].",".$lojas[0]['COD_UNIVEND'].",".$lojas[0]['COD_MAQUINA'].",'".$row['COD_USUARIO']."','S')";

                                                mysqli_query($connUser->connUser(), rtrim(trim($sql1)));
                                                $msg='PJ cadastrado com sucesso!';
                                                $xamls= addslashes($msg);
                                                Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                                                 fnmemoria($connUser->connUser(),'false',$dadoslogin['login']); 
                                      }                 
                                                     } catch (mysqli_sql_exception $e) { echo $e;  }
                                                      return array(
                                                              'msgerro' => 'OK' ,
                                                              'msgcampanha' =>'Seja muito bem vindo :-)',
                                                              'url' => '',
                                                              'ativacampanha' => 'sim',
                                                              'dadosextras' =>'');
                            }
                    }      
                    else{
                        return array('msgerro' =>"Id_cliente não confere com o cadastro!");

                    }
            }else{
              return array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
            }                
                  
            }else{
                return array('msgerro'=>'Oh não :-o!  erro Na autenticação :-[ ');

            }   
      mysqli_close($connUserws->connUser()); 
      mysqli_close($connAdm->connAdm()); 
     
}


//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
