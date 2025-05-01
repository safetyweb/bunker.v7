<?php
$server->register('AtualizaCadastro',
			array(
                              'fase'=>'xsd:string',
                              'cliente'=>'tns:acao_cadastro',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('AtualizaCadastroResponse' => 'tns:acao'),  //output
			 $ns,         						// namespace
                        "$ns/AtualizaCadastro",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'AtualizaCadastro'         		// documentation
                    );


function AtualizaCadastro($fase,$cliente,$dadosLogin) {
     ob_start();
     
     include '../_system/Class_conn.php';
     include 'func/function.php'; 
    
    $cpf=fnlimpaCPF($cliente['cpf']);
    $cartao=fnlimpaCPF($cliente['cartao']); 
    if($cliente['sexo']==''){$sexo=0;}else{$sexo=$cliente['sexo'];} 
     
    
    //// login senha
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
     
     //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo'); 
           return  array('AtualizaCadastroResponse'=>array('msgerro'=>'Oh não! A empresa foi desabilitada por algum motivo ;-[!'));
           exit();
        }
    //////////////////////=================================================================================================================
    //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo'); 
           return  array('AtualizaCadastroResponse'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
           exit();
        }
    //////////////////////=================================================================================================================
   
    
    $msg=validaCampo($cliente['tipocliente'],'tipocliente','string');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$cliente['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg
                                                     )); exit();}
     $msg=validaCampo(trim($cliente['email']),'email','email');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$cliente['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                     'coderro'=>'32')); exit();}                                                 
   
    $msg=validaCampo($dadosLogin['idloja'],'idloja','numeric');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                     'coderro'=>'20')); exit();}
   
   $msg=validaCampo($dadosLogin['idcliente'],'idcliente','numeric');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                      'coderro'=>'26')); exit();}
   
   $msg=validaCampo($cliente['datanascimento'],'datanascimento','DATA_BR');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                     'coderro'=>'29')); exit();}
   
   $msg=validaCampo($cliente['sexo'],'sexo','numeric');
   if(!empty($msg)){
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg);
       return array('AtualizaCadastroResponse'=>array('msgerro' => $msg,
                                                     'coderro'=>'30')); exit();}
   
   $msg=valida_campo($connAdm->connAdm(),$cliente,$dadosLogin,'AtualizaCadastroResponse');
   if($msg !=0 ){return $msg;exit();}
    //valida cpf
   
   //COD_CHAVECO
    if($row['COD_CHAVECO']==1){
        
            if($cpf==0 || $cartao==0){
                return array('AtualizaCadastroResponse'=>array('msgerro' => 'Cliente 0 Não pode ser atualizado ou Inserido',
                                                                'coderro'=>'42'));    
                exit();
            }
       /* if($cliente['cpf']!="" )
        {    
             if(valida_cpf($cliente['cpf']))
              {}
              else{
                  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro','CPF digitado é invalido');
                  return array('AtualizaCadastroResponse'=>array('msgerro' => 'CPF digitado é invalido',
                                                                  'coderro'=>'33')); 
                  exit();
             }
         }*/
    }else{$cpf=0;}
   //==estado civil
    if($cliente['estadocivil']=='' || $cliente['estadocivil']=='?')
    {$estadocivil='0';}else{$estadocivil=$cliente['estadocivil'];}    
     // =============profissao=======================
    if($cliente['profissao']=='' || $cliente['profissao']=='?')
    {$profissao='0';}else{$profissao=$cliente['profissao'];}   
     //compara os id_cliente com o cod_empresa
  // return  array('BuscaConsumidorResponse'=>array('msgerro'=>$row['COD_CHAVECO'])); 
           
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
        fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'AtualizaCadastro',$dadosLogin['idcliente']);
       //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro','Id_cliente não confere com o cadastro!'); 
           return  array('AtualizaCadastroResponse'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                           'coderro'=>'4')); 
           exit();
        } 
       
        
   }else{ 
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro','Usuario ou senha Inválido!'); 
       return  array('AtualizaCadastroResponse'=>array('msgerro'=>'Usuario ou senha Inválido!',
                                                       'coderro'=>'5'));
       exit();
   }
   
   //grava array na base de dados
   //inserir venda inteira na base de dados 
                        $dados_login= addslashes(str_replace(array("\n",""),array(""," "), var_export($dadosLogin,true)));
                        $arralogin = str_replace(" ","",$dadosLogin);

                        $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($cliente,true)));
                        $arracad = str_replace(" ","",$xamls);
                        $xmlteste=addslashes(file_get_contents("php://input"));
                        $inserarray='INSERT INTO origemcadastro (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                    ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                     "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$cpf.'","'.$xmlteste.'","'.$arralogin.'")';
                        $arraP=mysqli_query($connUser->connUser(),$inserarray);
                        if (!$arraP)
                        {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($connUser->connUser(),$inserarray);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg="Error description inserir log atualiza cadastro: $msgsql";
                            $xamls= addslashes($msg);
                            Grava_log_cad($connUser->connUser(),1,$xamls);
                            
                        } else {
                            $ID_FORMPA="SELECT last_insert_id(COD_ORIGEM) as COD_ORIGEM from origemcadastro ORDER by COD_ORIGEM DESC limit 1;";
                            $COD_FORMAPA = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_FORMPA));
                            $COD_ORIGEM=$COD_FORMAPA['COD_ORIGEM']; 
                            Grava_log_cad($connUser->connUser(),$COD_ORIGEM,'Log atualiza cadastro OK!');
                            
                        }
                  
   //  verificar cpf/cnpj                   
   //fim da gravação
   //PROCEDURE DE GRAVAÇÂO/ALTERAÇÂO
// Declara a data! :P
    $data = $cliente['datanascimento'];
    $idadecalc=calc_idade($data);
    //atualiza cliente se ja existe na base de dados
    $arraydata=explode("/", $cliente['datanascimento']);
    if($arraydata[0]=='')
    {   $idadecalc=0;
        $arraydata0=0;
        $arraydata1=0;
        $arraydata2=0;
            
    }else
    {
        $idadecalc=$idadecalc;
        $arraydata0=$arraydata[0];
        $arraydata1=$arraydata[1];
        $arraydata2=$arraydata[2]; 
    }    
   // print_r($arraydata);                
 $atualiza="CALL SP_INSERE_CLIENTES_WS(
                                        0,
                                     '".$row['COD_EMPRESA']."',
                                     '".$cliente['nome']."',
                                     '".$cliente['senha']."',
                                     '',
                                     '".trim($cliente['email'])."',
                                     '".$row['COD_USUARIO']."',
                                     '".$cpf."',
                                     'S',
                                     '".$cliente['rg']."',
                                     '".$cliente['datanascimento']."',
                                     '".$estadocivil."',
                                     '".$sexo."',
                                     '".$cliente['telresidencial']."',
                                     '".$cliente['telcelular']."',
                                     '".$cliente['telcomercial']."',
                                     '',
                                     '".$cartao."',
                                     1,
                                     '".$cliente['endereco']."',
                                     '".$cliente['numero']."',
                                     '".$cliente['complemento']."',
                                     '".$cliente['bairro']."',
                                     '".$cliente['cep']."',
                                     '".$cliente['cidade']."',
                                     '".$cliente['estado']."',
                                     '',
                                     '".$profissao."',
                                     '".$dadosLogin['idloja']."',
                                     '".$cliente['tipocliente']."',
                                     '',
                                     'S',
                                     'S',
                                     'S',
                                     '',
                                     '',
                                     '".$row['COD_CHAVECO']."',
                                    $idadecalc,
                                    $arraydata0,
                                    $arraydata1,
                                    $arraydata2    
                                     );";    
         
 $cadat=mysqli_query($connUser->connUser(),$atualiza);
    if (!$cadat)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($connUser->connUser(),$atualiza);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e; 
        $msg="Error description SP_ALTERA_CLIENTES_WS: $msgsql";
        
        $xamls= addslashes($msg);
        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro', $xamls); 
        }
        
}
else
{
   $resultat= mysqli_fetch_assoc($cadat);  
   
   if($resultat['cod_retorno']==1)
   {
    $menssagem='Cadastro Atualizado !'; 
    $cod_erro='34';
     fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro', $menssagem); 
     Grava_log_cad($connUser->connUser(),$COD_ORIGEM,$menssagem);   
   }else{
       $menssagem='Registro inserido!';
       $cod_erro='36';
       fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro', $menssagem); 
       Grava_log_cad($connUser->connUser(),$COD_ORIGEM,$menssagem); 
   }    
   $COD_CLIENTE=$resultat['COD_CLIENTE'];
}                     
                     
            
                       
       
  $arrayconsulta=array('conn'=>$connAdm->connAdm(),
                         'ConnB'=>$connUser->connUser(),
                         'cod_cliente'=> $COD_CLIENTE,
                         'empresa'=>$row['COD_EMPRESA'],
                         'fase'=> $fase,
                         'cpf'=> $cpf,
                         'cnpj'=> $cliente['cnpj'],
                         'cartao'=>  $cartao,
                         'email'=>  trim($cliente['email']),
                         'telefone'=>  $cliente['telefone'],
                         'consultaativa'=>$row['LOG_CONSEXT'],
                         'login'=>$dadosLogin['login'],
                         'senha'=>$dadosLogin['senha'],
                         'idloja'=>$dadosLogin['idloja'],
                         'idmaquina'=>$dadosLogin['idmaquina'],
                         'codvendedor'=>$dadosLogin['codvendedor'],
                         'nomevendedor'=>$dadosLogin['nomevendedor'],
                         'COD_USUARIO'=>$row['COD_USUARIO'],
                         'pagina'=>'AtualizaCadastro',
                         'menssagem'=>$menssagem,
                          'coderro' =>$cod_erro,
                          'venda'=>'nao',
                         'COD_UNIVEND'=>$dadosLogin['idloja']
            
                         );
  
  
    ob_end_flush();
    ob_flush();
    fnmemoria($connUser->connUser(),'false',$dadosLogin['login']);
    
 //    $teste=fn_consultaBase($arrayconsulta);
 //return  array('AtualizaCadastroResponse'=>array('msgerro'=>$teste,
 //                                                      'coderro'=>'5'));
    return  array('AtualizaCadastroResponse'=>fnreturn($arrayconsulta)); 
    
    
}
