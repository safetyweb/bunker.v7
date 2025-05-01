<?php
function AtualizaCadastro ($dados) {
require_once('../../../_system/Class_conn.php');
include '../../../wsmarka/func/function.php';

/*if($dados->cliente->cpf=='01734200014')
{        
$dados=Utf8_ansi2($dados);
}*/
    // limpa doc
    @$cpf=trim(rtrim(fnlimpaCPF($dados->cliente->cpf)));
    @$cartao=trim(rtrim(fnlimpaCPF($dados->cliente->cartao)));
    @$cnpj=trim(rtrim(fnlimpaCPF($dados->cliente->cnpj)));
    if($cpf==''){$cpflog=$cartao;}else{$cpflog=$cpf;}
    
    
    
    //=========================================================================
    $connAdmvar=$connAdm->connAdm();
   
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdmvar,$sql);
    $row = mysqli_fetch_assoc($buscauser);
    mysqli_next_result($connAdmvar);
   
        
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
        return array('AtualizaCadastroResult'=>'Usuario ou senha invalido!');  
    }  
   
    //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
        return array('AtualizaCadastroResult'=>'Usuario foi desabilitado!');  
    }
    
    if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
    {
        return array('AtualizaCadastroResult'=>'Id_cliente não confere com o cadastro!');
    } 
    //verifica se a empresa esta ativa
     if($row['LOG_ATIVO']!='S')
    {  
         return array('AtualizaCadastroResult'=> 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['); 
    } 

    if($cartao==0 || $cpf==0){
        return array('AtualizaCadastroResult'=> 'Cliente 0 Não pode ser atualizado ou Inserido');    
    }
      //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    $connUservar=$connUser->connUser();
     
    //Grava Log de envio do xml
    $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                     'login'=>$dados->dadoslogin->login,
                     'cod_empresa'=>$row['COD_EMPRESA'],
                     'pdv'=>'0',
                     'cupom'=>'0',
                     'idloja'=>$dados->dadoslogin->idloja,
                     'idmaquina'=>$dados->dadoslogin->idmaquina,
                     'cpf'=>$cpflog,     
                     'xml'=>addslashes(file_get_contents("php://input")),
                     'tables'=>'origemcadastro',
                     'conn'=>$connUservar
                 );
    $cod_log=fngravalogxml($arrylog);
  
    
    //consulta cliente
  $arrayconsulta=array('ConnB'=>$connUservar,
                        'conn'=>$connAdmvar,
                        'database'=>$row['NOM_DATABASE'],
                        'empresa'=>$row['COD_EMPRESA'],
                        'fase'=> '',
                        'cartao'=>$cartao,
                        'cpf'=>$cpf,
                        'cnpj'=>$cnpj,
                        'login'=>$dados->dadoslogin->login,
                        'senha'=>$dados->dadoslogin->senha,
                        'idloja'=>$dados->dadoslogin->idloja,
                        'idmaquina'=>$dados->dadoslogin->idmaquina,
                        'codvendedor'=>'',
                        'nomevendedor'=>'',
                        'COD_USUARIO'=>$row['COD_USUARIO'],
                        'pagina'=>'BuscaConsumidor',
                        'COD_UNIVEND'=>$dados->dadoslogin->idloja,
                        'venda'=>'nao',
                        'generico'=>'',
                        'LOG_WS'=>$row['LOG_WS']
                        );  
            $arraybusca[]=fn_consultaBase($arrayconsulta); 
           
           if($dados->cliente->datanascimento!='')
           {    
                $dataformat=date('d/m/Y', strtotime(str_replace('/','-',$dados->cliente->datanascimento))); 
                $datahora=DateTime::createFromFormat('d/m/Y', $dataformat);
                if($datahora===false){
                   return array('AtualizaCadastroResult'=>'datanascimento deve ser ANO-MES-DIA');
                } else{ 
                     $datahora=$datahora->format('d/m/Y');
                     $idadecalc=calc_idade($datahora);
                }
           }else{
               $datahora='';
               $idadecalc='0';
               
           }
     
     //busca dados do cliente
   // return array('AtualizaCadastroResult'=>$arraybusca[0]['COD_CLIENTE']);
     
    //identificação das chaves
    //COD_CHAVECO=1 CPF/CNPJ
    //COD_CHAVECO=5 CPF/CNPJ+CARTAO
    if($row['COD_CHAVECO']=='1' || $row['COD_CHAVECO']=='5')
    {
        $maxnumtamanho="SELECT MAX(NUM_TAMANHO) AS NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=".$row['COD_EMPRESA'];
        $rsnum_tamanho=mysqli_fetch_assoc(mysqli_query($connUservar,$maxnumtamanho));
        
        $geracartao="select  
                            (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
                             cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=".$row['COD_EMPRESA'];
        $rsgeracartao=mysqli_fetch_assoc(mysqli_query($connUservar,$geracartao));
        $NUM_TAMANHO=$rsnum_tamanho['NUM_TAMANHO'];
        
        if(($rsgeracartao['contador']==0) && 
           ($row['COD_CHAVECO']=='5') && 
           (strlen($cartao) <= $NUM_TAMANHO))
        {
            return array('AtualizaCadastroResult'=> 'Cartão invalido!');    
        }    
        //====================================================================================
        //verifica o cartão e faz update 
        if($row['COD_CHAVECO']=='5' && strlen($cartao) == $rsgeracartao['NUM_TAMANHO'])
        {  

            if($arraybusca[0]['cpf'] != '' || $arraybusca[0]['cartao'] !='')
            {  
                if( (int)$cpf != $arraybusca[0]['cpf'] || (int) $cartao!= $arraybusca[0]['cartao'])
                {
                     return array('AtualizaCadastroResult'=>'Cartao Já cadastrato');   
                } 
                if($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='N') 
                {
                  //novo cartao - insere
                    //update na tabela de cartoes
                    $updatecartao="update  geracartao set log_usado='S',cod_USUALTE=".$row['COD_USUARIO']." where num_cartao=".$arraybusca[0]['cartao']; 
                    mysqli_fetch_assoc(mysqli_query($connUservar,$updatecartao));
                }elseif ($rsgeracartao['contador']==0) 
                {
                  //cartao inválido - não existe na base
                  return array('AtualizaCadastroResult'=>'Cartão inválido!');                           
                }elseif ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado']=='S' ){
                  //cartao válido - mas já utilizado
                    if( (int)$cpf != $arraybusca[0]['cpf'] || (int) $cartao!= $arraybusca[0]['cartao'])
                    {
                        return array('AtualizaCadastroResult'=>'Cartão já utilizado!'); 
                    }
                }
            }                      
        }          
    }  
       
//============================================================================================================    
           
            
    if(
       ($dados->cliente->sexo===1)||
       ($dados->cliente->sexo==='M') || 
       ($dados->cliente->sexo==='Masculino')||
       ($dados->cliente->sexo==='masculino'))
    {$sexo=1;}elseif ($dados->cliente->sexo===0 ||
                        $dados->cliente->sexo==='F' || 
                        $dados->cliente->sexo==='feminino'||
                        $dados->cliente->sexo==='Feminino')
    {$sexo=2;}else{$sexo=3;}
    
    if(trim($dados->cliente->tipocliente)=='PF' || 
       trim($dados->cliente->tipocliente)=='') {$TP_CLIENTE='F';}
    elseif ($dados->cliente->tipocliente=='PJ'){$TP_CLIENTE='J';}
    
    //pergunda se o atendente for multcoisas
    
    if($dados->dadoslogin->idcliente=='77')
    {   
      
        $cod_atendente=fnatendente($connAdmvar,
                                    fnAcentos(addslashes($dados->cliente->fidelizador)),
                                    $dados->dadoslogin->idcliente,
                                    $dados->dadoslogin->idloja,
                                    $dados->cliente->cpf_fidelizador);    
        
    }else{    
    $cod_atendente=fnatendente($connAdmvar,
                               $dados->cliente->atendente,
                               $dados->dadoslogin->idcliente,
                               $dados->dadoslogin->idloja,
                               $dados->cliente->atendente);  
    }
     
    //atualiza cliente se ja existe na base de dados
    $arraydata=explode("/", $datahora);
     
     
    if($arraydata[0]=='')
    {   
        $idadecalc=0;
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
    // $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($InserirVendaMK,true)));
    // $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($arraydata,true)));
        
     
    
    
    //==================estado civil=====================================
  if($dados->cliente->estadocivil=='' || $dados->cliente->estadocivil=='?'){
      $estadocivil='0';
    }
    else{
        $sqlestadocivil="select * from estadocivil where DES_ESTACIV like '%".$dados->cliente->estadocivil."%'";
        $sqlresult=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlestadocivil));
        $estadocivil=$sqlresult['COD_ESTACIV'];
    }  
   
     // =============profissao=======================
    if($dados->cliente->profissao=='' || $dados->cliente->profissao=='?'){
        
        $profissao='0';
    }else{
            //busca retorno profissão
        $bus_PROFISS = "select * from profissoes where  DES_PROFISS='".$dados->cliente->profissao."'";
        $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$bus_PROFISS)); 
        if($profiss_ret['COD_PROFISS']==''){
            $profissao=0;                         
        }
        else{            
            $profissao=$profiss_ret['COD_PROFISS'];
        }
    }    
                   
  /*if($cartao=='01734200014')
 {    
   return array('AtualizaCadastroResult'=> var_export($arraybusca,true)); 
 } */
    
 

//====================================================================    
 $atualiza="CALL SP_INSERE_CLIENTES_WS(
                                        0,
                                     '".$row['COD_EMPRESA']."',
                                     '".addslashes(fnAcentos($dados->cliente->nome))."',
                                     '".$dados->cliente->senha."',
                                     '',
                                     '".trim($dados->cliente->email)."',
                                     '".$row['COD_USUARIO']."',
                                     '".$cpf."',
                                     'S',
                                     '".$dados->cliente->rg."',
                                     '".$datahora."',
                                     '".$estadocivil."',
                                     '".$sexo."',
                                     '".$dados->cliente->telresidencial."',
                                     '".$dados->cliente->telcelular."',
                                     '".$dados->cliente->telcomercial."',
                                     '',
                                     '".$cartao."',
                                     1,
                                     '".addslashes(limitarTexto(fnAcentos($dados->cliente->endereco),40))."',
                                     '".limitarTexto($dados->cliente->numero,9)."',
                                     '".limitarTexto(fnAcentos(addslashes($dados->cliente->complemento)),20)."',
                                     '".addslashes(limitarTexto(fnAcentos($dados->cliente->bairro),20))."',
                                     '".fnlimpaCEP($dados->cliente->cep)."',
                                     '".addslashes(fnAcentos($dados->cliente->cidade))."',
                                     '".fnAcentos($dados->cliente->estado)."',
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
 /*if($cartao=='18796609036')
 {    
   return array('AtualizaCadastroResult'=>$atualiza); 
 } */
if (mysqli_multi_query($connUservar,$atualiza))
{
  do
    {
        // Store first result set
        if ($cadat=mysqli_store_result($connUservar))
        {
            while ($rowclien= mysqli_fetch_assoc($cadat))
            {
                $resultat= $rowclien['COD_CLIENTE'];     
                $cod_retorno= $rowclien['cod_retorno'];
            }
            mysqli_free_result($connUservar);
        }
    }
  while (mysqli_next_result($connUservar));
}   

$arraybusca['COD_CLIENTE']=$resultat;
$resultat['cod_retorno']=$cod_retorno;

/*if($dados->cliente->cpf=='01734200014')
{        
  return array('AtualizaCadastroResult'=>$atualiza); 
}*/

//atualizar informação de recebinento de sms/email 
//================================================================

if($resultat['cod_retorno']!=1)
{
      $ $array=ARRAY('WHERE'=>"WHERE g.TIP_GATILHO in ('cadastro') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
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
      
        $rwgatilho_email=mysqli_query($connUservar, $sqlgatilho_email);
        if(mysqli_num_rows($rwgatilho_email)>=1)
        {
            $rsgatilho_email= mysqli_fetch_assoc($rwgatilho_email);
            $cod_campanha=$rsgatilho_email['COD_CAMPANHA'];
            $TIP_MOMENTO=$rsgatilho_email['TIP_MOMENTO'];
            $TIP_GATILHO=$rsgatilho_email['TIP_GATILHO'];
            $COD_PERSONAS=$rsgatilho_email['COD_PERSONAS'];
            if(trim($dados->cliente->email)!='')
            {  
                mysqli_next_result($connUservar);  
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
                                                        '".$arraybusca['COD_CLIENTE']."', 
                                                        '".$cpf."', 
                                                        '".addslashes(fnAcentos($dados->cliente->nome))."', 
                                                        '".$datahora."', 
                                                        '".trim($dados->cliente->email)."',
                                                        '".$dados->cliente->telcelular."',    
                                                        '".$sexo."', 
                                                        '".$cod_campanha."', 
                                                        '".$TIP_MOMENTO."',
                                                        '$gatilho',
                                                        '$TIP_GATILHO'    
                                                        );";
                $inse=mysqli_query($connUservar, $sqlfila);  
                if(!$inse)
                {
                    $clas="CALL SP_PERSONA_CLASSIFICA_CADASTRO(".$arraybusca['COD_CLIENTE'].", ".$row['COD_EMPRESA'].", $cod_campanha, '".$COD_PERSONAS."',0)";
                    mysqli_query($connUser->connUser(), $clas);  
                }    
            }    
        }
    }
    //nova rotina para pontuação no cadastro
    $pontuacli="CALL SP_CREDITOS_CADASTRO($row[COD_EMPRESA],'".$dados->dadoslogin->idloja."',$cod_atendente, '".$row['COD_USUARIO']."',$arraybusca[COD_CLIENTE])";
    $rwpontcli=mysqli_query($connUservar, $pontuacli);
    if(!$rwpontcli)
    {    
     Grava_log_msgxml($connUservar,'msg_cadastra',$cod_log,'erro na rotina depontuacao no cadastro!'); 
    }
}  

//==================================================================


        

mysqli_next_result($connUservar);              
 //=======================================================
// procedure classificação de perssonas
    /*$class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(
                                                    ".$arraybusca['COD_CLIENTE'].",
                                                    ".$row['COD_EMPRESA']."
                                                    )";*/
    $class_cad="call SP_PERSONA_CLASSIFICA_CADASTRO(".$arraybusca['COD_CLIENTE'].",".$row['COD_EMPRESA'].", 0, '','1')";     
    /*if($cartao=='10168503794')
    {
       return array('AtualizaCadastroResult'=>$class_cad);   
    }    */
    $class=mysqli_query($connUservar,$class_cad);
    mysqli_next_result($connUservar);
    if (!$class)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {
                 mysqli_query($connUservar,$class_cad);
                 mysqli_next_result($connUservar);
         } 
         catch (mysqli_sql_exception $e) {$msgsql = $e;} 
         Grava_log_msgxml($connUservar,'msg_cadastra',$cod_log,'OPS PROBLEMAS NA CLASSIFICACAO');
         $msg="Erro ao inserir cadastro $msgsql";
         //return array('AtualizaCadastroResult'=>'OPS PROBLEMAS NA CLASSIFICACAO');  
    }    
       $menssagem='OK';
//atualizar informação de recebinento de sms/email    
        if($dados->cliente->autoriza_sms=='')
        {    
            $ACEITE_COMUNICACAO="update  clientes set LOG_SMS='S' 
                                where cod_cliente='".$arraybusca['COD_CLIENTE']."'
                                      and COD_EMPRESA=".$row['COD_EMPRESA'].";"; 
            mysqli_fetch_assoc(mysqli_query($connUservar,$ACEITE_COMUNICACAO));
            mysqli_next_result($connUservar);                 
        }
        if ($dados->cliente->autoriza_email=='') {
                $ACEITE_COMUNICACAO="update  clientes set LOG_EMAIL='S'
                                         where cod_cliente='".$arraybusca['COD_CLIENTE']."'
                                               and COD_EMPRESA=".$row['COD_EMPRESA'].";"; 
                mysqli_fetch_assoc(mysqli_query($connUservar,$ACEITE_COMUNICACAO));
                mysqli_next_result($connUservar);
        }   
        
        if($dados->cliente->autoriza_sms=='0')
        {    
            $ACEITE_COMUNICACAO="update  clientes set LOG_SMS='N' 
                                where cod_cliente='".$arraybusca['COD_CLIENTE']."'
                                      and COD_EMPRESA=".$row['COD_EMPRESA'].";"; 
             mysqli_fetch_assoc(mysqli_query($connUservar,$ACEITE_COMUNICACAO));
             mysqli_next_result($connUservar);
        }
        if ($dados->cliente->autoriza_email=='0') {
                $ACEITE_COMUNICACAO="update  clientes set LOG_EMAIL='N'
                                         where cod_cliente='".$arraybusca['COD_CLIENTE']."'
                                               and COD_EMPRESA=".$row['COD_EMPRESA'].";"; 
                 mysqli_fetch_assoc(mysqli_query($connUservar,$ACEITE_COMUNICACAO));
                 mysqli_next_result($connUservar);
        }
                    
       mysqli_next_result($connUservar);             
//================================================================================
    Grava_log_msgxml($connUservar,'msg_cadastra',$cod_log,$menssagem);
        
    return array('AtualizaCadastroResult'=>$menssagem);
  
    
    
}