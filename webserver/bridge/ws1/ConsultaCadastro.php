<?php 
function ConsultaCadastro($dados) {
include('../../../_system/Class_conn.php');
include('../../func/function.php');
include '../ws2/functionbridge/functionbridge.php';

$connAdmVAR=$connAdm->connAdm();

    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdmVAR,$sql);
    $row = mysqli_fetch_assoc($buscauser);
     mysqli_free_result($row);
    //verifica usuario ou senha
     if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
        return array('ConsultaCadastroResult'=> array('msgerro'=>'Usuario ou senha invalido!'));  
    }  
    //termina validação de usuario
     $dec=$row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$decimal = 0;}  
    
    
    //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
        return array('ConsultaCadastroResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
    }
    
    if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
    {
        return array('ConsultaCadastroResult'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
    } 
    //verifica se a empresa esta ativa
     if($row['LOG_ATIVO']!='S')
    {  
         return array('ConsultaCadastroResult'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')); 
    } 
       
   if(fnlimpaCPF($dados->cliente->cartao)!='')
   {
       $num_cgcecpf=$dados->cliente->cartao; 
   }elseif (fnlimpaCPF($dados->cliente->cpf)!='') {
       $num_cgcecpf=fnlimpaCPF($dados->cliente->cpf);       
   }elseif ($dados->cliente->cnpj!='') {
          $num_cgcecpf=fnlimpaCPF($dados->cliente->cnpj);    
   }elseif ($dados->cliente->telcelular!='') {
          $num_cgcecpf=fnlimpaCPF($dados->cliente->telcelular);    
   }else {
	   if($dados->dadoslogin->idloja !='97153')
	   {	   
            return array('ConsultaCadastroResult'=> array('msgerro'=>'Por favor usar os campos CPF,CARTÃO,telcelular ou CNPJ'));   
	   }else{
		   $num_cgcecpf='0';
	   }
   } 
    
//conn user
$connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
$connUservar=$connUser->connUser();

 


 //grava LOG
    $arraydados1= array('CONN'=>$connUservar,
                        'DATA_HORA'=>date("Y-m-d H:i:s"),
                        'IP'=>$_SERVER['REMOTE_ADDR'],
                        'PORT'=>$_SERVER['REMOTE_PORT'],
                        'COD_USUARIO'=>$row['COD_USUARIO'],
                        'LOGIN'=>$dados->dadoslogin->login,
                        'COD_EMPRESA'=>$row['COD_EMPRESA'],
                        'IDLOJA'=>$dados->dadoslogin->idloja,
                        'IDMAQUINA'=>$dados->dadoslogin->idmaquina,
                        'CPF'=>$num_cgcecpf,
                        'XML'=>file_get_contents("php://input"),
                        'URL'=>'WS1-COMPATIBILIDADE'      
                      );
    $LOG=fngravaxmlbusca($arraydados1);


    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><ConsultaCadastroResult></ConsultaCadastroResult>");

    //consulta de cliente
    $dadosbase=fn_consultaBase($connUservar,trim($num_cgcecpf),trim($num_cgcecpf),trim($num_cgcecpf),'','',$row['COD_EMPRESA']);   
   
   //consultar o loja que o cliente pertence
    $unidadevenda="SELECT * FROM unidadevenda WHERE cod_univend='".$dadosbase[0]['codunivend']."' AND cod_empresa='".$row['COD_EMPRESA']."'";
    $univenda=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $unidadevenda));
    
    
   
    if($dadosbase[0]['sexo']=='1'){$sexo='Masculino';}else{$sexo='Feminino';}  
     //url extrato
    $urlextrato=fnEncode($dados->dadoslogin->login.';'
                        .$dados->dadoslogin->senha.';'
                        .$dados->dadoslogin->idloja.';'
                        .$dados->dadoslogin->idmaquina.';'
                        .$row['COD_EMPRESA'].';'
                        .$dados->dadoslogin->codvendedor.';'
                        .$dados->dadoslogin->nomevendedor.';'
                        .fncompletadoc($dadosbase[0]['cpf'])
                         ); 
    $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$dadosbase[0]['COD_CLIENTE'].")";
    $retSaldo = mysqli_fetch_assoc(mysqli_query($connUservar,$consultasaldo));
//if($dados->cliente->cartao=='35497')
//{
//	 return array('ConsultaCadastroResult'=> array('msgerro'=>$consultasaldo));  
//}

    //busca retorno profissão
     $bus_PROFISS = "select * from profissoes where COD_PROFISS=".$dadosbase[0]['profissao'];
     $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdmVAR,$bus_PROFISS));  
    //verifica se a consulta retornou algum cliente
    if ($dadosbase[0]['cpf']=='' || $dadosbase[0]['cartao']=='')
    {
        $return=array('ConsultaCadastroResult'=> array('msgerro'=>'Nenhum cadastro encontrado'));   
        array_to_xml($return,$xml_user_info);
        Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'Nenhum cadastro encontrado',addslashes($xml_user_info->asXML()));
        return $return;
      
    }
//----------------------------
    
    if(strlen(fncompletadoc($dadosbase[0]['cnpj']))=='14')
    {
        $npj=fncompletadoc($dadosbase[0]['cnpj']);
    }else{
        $npj='';
    }
    if(strlen(fncompletadoc($dadosbase[0]['cpf']))<='11')
    {
        $cpf=fncompletadoc($dadosbase[0]['cnpj']);
    }else{
        $cpf='';
    }
    if($dadosbase[0]['estadocivil']=='1')
    { $civil='Casado';}   
    if($dadosbase[0]['estadocivil']=='2')
    { $civil='Solteiro';}   
    if($dadosbase[0]['estadocivil']=='3')
    { $civil='Viuvo';}   
    if($dadosbase[0]['estadocivil']=='4')
    { $civil='Divorciado';}   
    if($dadosbase[0]['estadocivil']=='5'  || $dadosbase[0]['estadocivil']=='0')
    { $civil='';}    
    if($dadosbase[0]['tipocliente']=='F'){$tipoclient='Pessoa Física';} else {$tipoclient='Pessoa Juridica';}     
   
       //convert data nascimento
    if($dadosbase[0]['datanascimento']!='')
    {    
        $RDATNASCIME = str_replace('/', '-', $dadosbase[0]['datanascimento']);
        $DATNASCIME = date("Y-m-d", strtotime($RDATNASCIME));
    }else{
        $DATNASCIME='';
    }
    $return=array('ConsultaCadastroResult'=> array(
                                    'cartao' =>$dadosbase[0]['cartao'] ,
                                    'tipocliente' =>$tipoclient,
                                    'nome' => $dadosbase[0]['nome'],
                                    'cpf' =>$cpf,
                                    'cnpj'=>$npj,
                                    'rg' => $dadosbase[0]['rg'],
                                    'sexo' =>$sexo,
                                    'datanascimento' => $DATNASCIME,
                                    'estadocivil' => $civil,
                                    'email' => $dadosbase[0]['email'],
                                    'profissao' =>$profiss_ret['DES_PROFISS'],
                                    'dataalteracao' =>$dadosbase[0]['dataalteracao'] ,
                                    'cartaotitular' =>'',
                                    'bloqueado'=>'',
                                    'motivo'=>'',
                                    'nomeportador'=>'',
                                    'grupo' => $dadosbase[0]['grupo'],                                    
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
                                    'adesao'=>'',
                                    'saldopontos'=>fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],$decimal),
                                    'saldocreditos' => fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],$decimal),
                                    'saldoresgate' => fnformatavalorretorno($retSaldo['CREDITO_DISPONIVEL'],$decimal),
                                    'creditoaniversario'=>'',
                                    'creditoextra'=>'',
                                    'lojapreferencia'=>$univenda['NOM_FANTASI'],   
                                    'atendente'=>'',
                                    'cpf_atendente'=>'',
                                    'fidelizador'=>'',
                                    'cpf_fidelizador'=>'',
                                    'msgerro' => 'OK',
                                    'urlextrato' =>"http://extrato.bunker.mk?key=$urlextrato",
                                    'autoriza_sms'=>'',
                                    'autoriza_email'=>'',
                                    'msgcampanha'=>'')
        );
    array_to_xml($return,$xml_user_info);
    Grava_log_msgxml($connUser->connUser(),'msg_busca',$LOG,'OK',addslashes($xml_user_info->asXML()));
   
    return $return;
}