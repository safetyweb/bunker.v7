<?php
function AtualizaCadastro ($dados) {
    rtrim(trim(require_once('../../../_system/Class_conn.php')));
    rtrim(trim(require_once('../../func/function.php'))); 
     include './functionbridge/functionbridge.php';
    // limpa doc
    @$cpf=trim(rtrim(fnlimpaCPF($dados->cliente->cpf)));
    @$cartao=trim(rtrim(fnlimpaCPF($dados->cliente->cartao)));
    if($cpf==''){$cpflog=$cartao;}else{$cpflog=$cpf;}
    //=========================================================================
    $sql1 = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql1);
    $row = mysqli_fetch_assoc($buscauser);
   
    
 
    
    //=============================================== 
    $msg=valida_campo_vazio($dados->dadoslogin->idloja,'idloja','numeric');
    if(!empty($msg)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($dados->dadoslogin->idmaquina,'idmaquina','string');
    if(!empty($msg)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg));} 
    //=================================================================================
        $msg=valida_campo_vazio($dados->cliente->cartao,'cartao','numeric');
        if(!empty($msg)||!empty($msg1)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg));}
        $msg1=valida_campo_vazio($dados->cliente->nome,'nome','string');
        if(!empty($msg1)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg1));}
       $msg2=valida_campo_vazio($dados->cliente->cpf,'cpf','numeric');
        if(!empty($msg2)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg2));}
        $msg3=valida_campo_vazio($dados->cliente->sexo,'sexo','string');
        
         if(!empty($msg4)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg4));}
        $msg5=valida_campo_vazio(rtrim(trim($dados->cliente->email)),'email','string');
        if(!empty($msg5)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg5));}

    //===================================================================================
    //verifica a senha
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
          //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
     //Grava Log de envio do xml
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>'0',
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$cpflog,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemcadastro',
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
      
      
        //verifica CPF se for valido
         if ( !valida_cpf($dados->cliente->cpf) ) {
            Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'CPF'.$dados->cliente->cpf .' inválido');
            return array('AtualizaCadastroResult'=>array('msgerro' =>'CPF'.$dados->cliente->cpf .' inválido'));}
        //=============================
        
        
            //verifica se o usuario esta ativo
           if($row['LOG_ESTATUS']=='N')
           {
               fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Usuario foi desabilitado!',$row['LOG_WS']); 
               return array('AtualizaCadastroResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
           } 
 
    }else{
       if ($row['LOG_WS']==''){$LOG_WS='S';} else {$LOG_WS=$row['LOG_WS'];}
       fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','login e senha invalido-'.addslashes($sql1),$LOG_WS); 
       return array('AtualizaCadastroResult'=>array('msgerro' => 'login e senha invalido'));
    }
    //confere Id_empresa com o cadastrado na base de dados
    if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
    {
        fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Empresa nao confere!',$row['LOG_WS']); 
        return array('AtualizaCadastroResult'=>array('msgerro' => 'Empresa nao confere!'));
    }
    // verifica se a empresa esta desabilitada
    if($row['LOG_ATIVO']!='S')
    {   
          fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
          return array('AtualizaCadastroResult'=>array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
    }  
    //========================================================================
    //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
        fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Usuario foi desabilitado!',$row['LOG_WS']); 
        return array('AtualizaCadastroResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
    } 
    //===============================================  
    


    if($cpf==''){$cpfcartaourl=$cartao;}else{$cpfcartaourl=$cpf;}    
   
    //calculo de idade
    //url extrato
    $urlextrato=fnEncode($dados->dadoslogin->login.';'
                        .$dados->dadoslogin->senha.';'
                        .$dados->dadoslogin->idloja.';'
                        .$dados->dadoslogin->idmaquina.';'
                        .$row['COD_EMPRESA'].';'
                        .$dados->dadoslogin->codvendedor.';'
                        .$dados->dadoslogin->nomevendedor.';'
                        .$cpfcartaourl
                         );
    //======================================================================
    //verifica loja    
    $lojas=fnconsultaLoja($connAdm->connAdm(),$connUser->connUser(),$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$row['COD_EMPRESA']);
    //==============================================================================================
    if($cartao==0 || $cpf==0){
        $MSG='Cliente 0 Não pode ser atualizado ou Inserido';
        Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,$MSG);
        fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro',$MSG,$row['LOG_WS']); 
        return array('AtualizaCadastroResult'=>array('msgerro' => $MSG));    
    }
    //busca dados do cliente
    $dadosbase=fn_consultaBase($connUser->connUser(),$cpf,'',$cartao,rtrim(trim($dados->cliente->email)),$dados->cliente->telcelular,$row['COD_EMPRESA']);   
    
    //identificação das chaves
    //COD_CHAVECO=1 CPF/CNPJ
    //COD_CHAVECO=5 CPF/CNPJ+CARTAO
    if($row['COD_CHAVECO']=='1' || $row['COD_CHAVECO']=='5')
    {
        $geracartao="select  
                            (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
                             cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=".$row['COD_EMPRESA'];
        $rsgeracartao=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$geracartao));

        if(($rsgeracartao['contador']==0) && 
           ($row['COD_CHAVECO']=='5') && 
           (strlen($cartao)!=11)&&
           (strlen($cartao)!=14))
        {
              Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'Cartão invalido!');
             fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Cartão invalido!',$row['LOG_WS']); 
            return array('AtualizaCadastroResult'=>array('msgerro' => 'Cartão invalido!'));    
        }    
        //====================================================================================
        //verifica o cartão e faz update 
        if($row['COD_CHAVECO']=='5' && strlen($cartao) == $rsgeracartao['NUM_TAMANHO'])
        {  

            if($dadosbase[0]['cpf'] != '' || $dadosbase[0]['cartao'] !='')
            {  
                if( (int)$cpf != $dadosbase[0]['cpf'] || (int) $cartao!= $dadosbase[0]['cartao'])
                {
                    Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'Cartao Já cadastrato');
                     fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Cartao Já cadastrato',$row['LOG_WS']); 
                     return array('AtualizaCadastroResult'=>array('msgerro' => 'Cartao Já cadastrato'));   
                } 
                if($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='N') 
                {
                  //novo cartao - insere
                    //update na tabela de cartoes
                    $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$dadosbase[0]['cartao']; 
                    mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$updatecartao));
                }elseif ($rsgeracartao['contador']==0) 
                {
                  //cartao inválido - não existe na base
                  Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'Cartão inválido!');  
                  fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Cartão inválido!',$row['LOG_WS']); 
                  return array('AtualizaCadastroResult'=>array('msgerro' => 'Cartão inválido!'));                           
                }elseif ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='S' ){
                  //cartao válido - mas já utilizado
                   fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Cartão já utilizado!',$row['LOG_WS']); 
                   return array('AtualizaCadastroResult'=>array('msgerro' => 'Cartão já utilizado!'));                          
                }
            }                      
        }          
    }   
//============================================================================================================    
    //verifica o sexo
    if(
       ($dados->cliente->sexo==1)||
       ($dados->cliente->sexo=='M') || 
       ($dados->cliente->sexo=='Masculino')||
       ($dados->cliente->sexo=='masculino')){$sexo=1;}
       elseif (($dados->cliente->sexo==0) ||
               ($dados->cliente->sexo=='F') || 
               ($dados->cliente->sexo=='feminino')||
               ($dados->cliente->sexo=='Feminino'))
               {$sexo=2;} else {$sexo=3;}  
    //========================================    
    
    //==============================
    if($dados->dadoslogin->codvendedor=='' || 
       $dados->dadoslogin->codvendedor=='?' || 
       $dados->dadoslogin->codvendedor=='??')
    {$codvendedor=0;} else {$codvendedor=$dados->dadoslogin->codvendedor;}
    $cod_atendente=fnatendente($connAdm->connAdm(),$dados->cliente->codatendente,$dados->dadoslogin->idcliente,$dados->dadoslogin->idloja,$dados->cliente->codatendente);

    if(trim($dados->cliente->tipocliente)=='PF' || 
       trim($dados->cliente->tipocliente)=='')
    {
		$TP_CLIENTE='F';
		//formata data de nascimento
       $datenascime=fnformatadate($dados->cliente->datanascimento);
    
		}
    elseif ($dados->cliente->tipocliente=='PJ') 
    {
		$TP_CLIENTE='J';
		if($dados->cliente->datanascimento!='')
		{	
	      $datenascime=fnformatadate($dados->cliente->datanascimento);
		}else{
			$datenascime=date('d/m/Y');
		}
	}
    
    ///data de alteração
    if($dados->cliente->dataalteracao!=''){$date_alterac=$dados->cliente->dataalteracao;}else{$date_alterac=date('Y-m-d H:i:s');}
    //==================================
//aqui vem a procedure de update e cadastro    


   
    $idadecalc=fnidade($datenascime);
    //atualiza cliente se ja existe na base de dados
    $arraydata=explode("/", $datenascime);
    
    
    if($arraydata[0]=='')
    {   $idadecalc=0;
        $arraydata0=0;
        $arraydata1=0;
        $arraydata2=0;
            
    }else
    {
        $idadecalc=$idadecalc;
        $arraydata0=$arraydata[2];
        $arraydata1=$arraydata[1];
        $arraydata2=$arraydata[0]; 
    }
 //==================estado civil=====================================
  if($dados->cliente->estadocivil=='' || $dados->cliente->estadocivil=='?')
    {
      $estadocivil='0';
    }
    else
    {
        $sqlestadocivil="select * from estadocivil where DES_ESTACIV like '%".$dados->cliente->estadocivil."%'";
        $sqlresult=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlestadocivil));
        $estadocivil=$sqlresult['COD_ESTACIV'];
    }  
   
     // =============profissao=======================
    if($dados->cliente->profissao=='' || $dados->cliente->profissao=='?')
    {$profissao='0';}else{
            //busca retorno profissão
        $bus_PROFISS = "select * from profissoes where  DES_PROFISS='".$dados->cliente->profissao."'";
        $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS)); 
        if($profiss_ret['COD_PROFISS']=='')
        {
         fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Profissão não localizada',$row['LOG_WS']); 
         $profissao=0;                         
        }
        else
        {
            
        $profissao=$profiss_ret['COD_PROFISS'];
        fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro','Profissão localizada cod ='.$profissao,$row['LOG_WS']);
        }
    }    
                    
    $bairro=limitarTexto($dados->cliente->bairro,20);

//====================================================================    
 $atualiza="CALL SP_INSERE_CLIENTES_WS(
                                        0,
                                     '".$row['COD_EMPRESA']."',
                                     '".fnAcentos(addslashes($dados->cliente->nome))."',
                                     '".fnEncode($dados->cliente->senha)."',
                                     '',
                                     '".trim($dados->cliente->email)."',
                                     '".$row['COD_USUARIO']."',
                                     '".$cpf."',
                                     'S',
                                     '".$dados->cliente->rg."',
                                     '".$datenascime."',
                                     '".$estadocivil."',
                                     '".$sexo."',
                                     '".$dados->cliente->telresidencial."',
                                     '".$dados->cliente->telcelular."',
                                     '".$dados->cliente->telcomercial."',
                                     '',
                                     '".$cartao."',
                                     1,
                                     '".fnAcentos(addslashes($dados->cliente->endereco))."', 
                                     '".$dados->cliente->numero."',
                                     '".fnAcentos($dados->cliente->complemento)."',
                                     '".fnAcentos(addslashes($bairro))."',
                                     '".$dados->cliente->cep."',
                                     '".fnAcentos($dados->cliente->cidade)."',
                                     '".$dados->cliente->estado."',
                                     '',
                                     '".$profissao."',
                                     '".$dados->dadoslogin->idloja."',
                                     '".$TP_CLIENTE."',
                                     '',
                                     'S',
                                     'S',
                                     'S',
                                     '',
                                     '',
                                     '".$row['COD_CHAVECO']."',
                                    $idadecalc,
                                    $arraydata2,
                                    $arraydata1,
                                    $arraydata0,
                                     '0',
                                    $cod_atendente,
									'0',
                                    '0'									
                                     );";    
          
        $cadat=mysqli_query($connUser->connUser(),$atualiza);
        if (!$cadat)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try 
            {
                mysqli_query($connUser->connUser(),$atualiza);
            } 
            catch (mysqli_sql_exception $e) 
            {
                $msgsql= $e; 
                $msg="Error description SP_ALTERA_CLIENTES_WS: $msgsql";
                $xamls= addslashes($msg);
                Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'OPS erro em alguns dados!');  
                fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro',$xamls,$row['LOG_WS']); 
                return array('AtualizaCadastroResult'=>array('msgerro' =>'OPS erro em alguns dados!'));  
            }
        }
        else
        {
           $resultat= mysqli_fetch_assoc($cadat);  
        
            if($resultat['cod_retorno']==1)
            {
                
                $menssagem='OK'; 
                fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro',$menssagem,$row['LOG_WS']); 
                Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'OK');
                $dadosbase=fn_consultaBase($connUser->connUser(),$cpf,'',$cartao,rtrim(trim($dados->cliente->email)),$dados->cliente->telcelular,$row['COD_EMPRESA']);   
  
               // $COD_CLIENTE=$resultat['COD_CLIENTE'];
            }else{
                fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro',$menssagem,$row['LOG_WS']); 
                Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'OK'); 
                $menssagem='OK';
                //agenda email disparo
if($cpf !='0' && $cartao!='0')
{
       $array=ARRAY('WHERE'=>"WHERE g.TIP_GATILHO in ('cadastro') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                 'TABLE'=> array('gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA="S" ',
                                 'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA_SMS="S" '
                                ));
    foreach ($array['TABLE'] as $KEY => $dadostable)
    {    
        if($KEY=='0')
        {
           $gatilho='2';
        }else{
           $gatilho='3'; 
        }
        $sqlgatilho_email="SELECT * FROM $dadostable $array[WHERE] group by g.COD_CAMPANHA ORDER BY COD_LISTA DESC limit 1";
      
        $rwgatilho_email=mysqli_query($connUser->connUser(), $sqlgatilho_email);
        if(mysqli_num_rows($rwgatilho_email)>=1)
        {
            $dadosbase1=fn_consultaBase($connUser->connUser(),$cpf,'',$cartao,rtrim(trim($dados->cliente->email)),$dados->cliente->telcelular,$row['COD_EMPRESA']);   
  
            $rsgatilho_email= mysqli_fetch_assoc($rwgatilho_email);
            $cod_campanha=$rsgatilho_email['COD_CAMPANHA'];
            $TIP_MOMENTO=$rsgatilho_email['TIP_MOMENTO'];
            $TIP_GATILHO=$rsgatilho_email['TIP_GATILHO'];
            $COD_PERSONAS=$rsgatilho_email['COD_PERSONAS'];
            if(trim($dados->cliente->email)!='' || trim($dados->cliente->telcelular))
            {  
                    $sqlfila= "INSERT INTO email_fila ( COD_EMPRESA, 
                                                        COD_UNIVEND, 
                                                        COD_CLIENTE, 
                                                        NUM_CGCECPF,
                                                        NOM_CLIENTE, 
                                                        DT_NASCIME, 
                                                        DES_EMAILUS,
                                                        NUM_CELULAR,
                                                        COD_SEXOPES, 
                                                        COD_CAMPANHA,
                                                        TIP_MOMENTO,
                                                        TIP_FILA,
                                                        TIP_GATILHO
                                                        ) VALUES 
                                                        ('".$row['COD_EMPRESA']."', 
                                                        '".$dados->dadoslogin->idloja."', 
                                                        '".$dadosbase1[0]['COD_CLIENTE']."', 
                                                        '".$cpf."', 
                                                        '".utf8_decode(utf8_encode(addslashes(fnAcentos($dadosbase1[0]['nome']))))."', 
                                                        '".$datenascime."', 
                                                        '".trim($dados->cliente->email)."',
                                                        '".$dados->cliente->telcelular."', 
                                                        '".$dadosbase1[0]['sexo']."',    
                                                        '".$cod_campanha."', 
                                                        '".$TIP_MOMENTO."', 
                                                        '".$gatilho."',
                                                        '$TIP_GATILHO'   
                                                        );";
                $testesql=mysqli_query($connUser->connUser(), $sqlfila); 
                if(!$testesql)
                {
                    $clas="CALL SP_PERSONA_CLASSIFICA_CADASTRO(".$dadosbase1[0]['COD_CLIENTE'].", ".$row['COD_EMPRESA'].", $cod_campanha, '".$COD_PERSONAS."','0')";
                    $clasf=mysqli_query($connUser->connUser(), $clas); 
                }    
            }    
        }
    }
 }  
 //==============================================================================   
              
            }
            $dadosbase=fn_consultaBase($connUser->connUser(),$cpf,'',$cartao,rtrim(trim($dados->cliente->email)),$dados->cliente->telcelular,$row['COD_EMPRESA']);   
  
          // $COD_CLIENTE=$resultat['COD_CLIENTE'];
        }    
  
    
 //=======================================================
// procedure classificação de perssonas
  /*  $class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(
                                                    ".$dadosbase[0]['COD_CLIENTE'].",
                                                    ".$row['COD_EMPRESA']."
                                                    )";*/
    $class_cad="CALL SP_PERSONA_CLASSIFICA_CADASTRO(".$dadosbase[0]['COD_CLIENTE'].", ".$row['COD_EMPRESA'].", 0, '','1')";
    $class=mysqli_query($connUser->connUser(),$class_cad);
    if (!$class)
    {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {mysqli_query($connUser->connUser(),$class_cad);} 
    catch (mysqli_sql_exception $e) {$msgsql = $e;} 
     Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,'OPS PROBLEMAS NA CLASSIFICACAO');
    $msg="Erro ao inserir cadastro $msgsql";
    fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cpflog,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'AtualizaCadastro',$msg,$row['LOG_WS']); 
                
    return array('AtualizaCadastroResult'=>array('msgerro' =>'OPS PROBLEMAS NA CLASSIFICACAO'));  
    }    
//================================================================================
    Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,$menssagem);
          
  return array('AtualizaCadastroResult'=>array('msgerro' =>$menssagem)); 
}        