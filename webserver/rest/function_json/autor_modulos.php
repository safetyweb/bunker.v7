<?php
function fnVendedorjson ($conn,$NOM_USUARIO,$COD_MULTEMP,$COD_UNIVEND,$cod_externo)
{ 
     if($NOM_USUARIO!=''){$nome_user=" or NOM_USUARIO='$NOM_USUARIO'";}else{$nome_user='';}
     
    $sqlbusca="select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST from usuarios where COD_EMPRESA='$COD_MULTEMP' and  COD_EXTERNO='$cod_externo' $nome_user";
    $result=mysqli_fetch_assoc( mysqli_query($conn, $sqlbusca));
     
    if($result['exist']==0){
    //dat_cadastr      
   //NOM_USUARIO, COD_TPUSUARIO = 7, COD_MULTEMP = COD_EMPRESA, COD_UNIVEND = LOJA, COD_DEFSIST = 4
    $sql='insert into usuarios (dat_cadastr,NOM_USUARIO,COD_EMPRESA,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,COD_EXTERNO)
                                values
                                (
                                "'.date('Y-m-d H:i:s').'",
                                "'.$NOM_USUARIO.'",
                                "'.$COD_MULTEMP.'",    
                                "7",
                                "'.$COD_MULTEMP.'",
                                "'.$COD_UNIVEND.'",
                                "7",
                                "'.$cod_externo.'"
                                ) ';
        mysqli_query($conn, $sql);
        $COD_VENDEDOR= mysqli_insert_id($conn);
        // $ID_LOG="SELECT last_insert_id(COD_USUARIO) as COD_USUARIO from usuarios ORDER by COD_USUARIO DESC limit 1;";
        // $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
        //$COD_VENDEDOR=$LOG['COD_USUARIO'];

        //return $COD_VENDEDOR;
       return $COD_VENDEDOR;
        }
        else
        {
            
         $COD_VENDEDOR=$result['COD_USUARIO'];
         return $COD_VENDEDOR;
        }
        
} 
function fnValorSQLjson($Num,$Dec)
{
  $valor = str_replace(",", ".", $Num); 
  $valor = number_format ($valor,$Dec,".",",");
  //echo $valor; //retorna o valor formatado para apresentação em tela  
  return $valor;
}
function fnvalorretornojson($Num,$dec)
{

    $valor = str_replace(",", ".", $Num); 
    $valor = bcmul($valor, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
    $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
    $valor=number_format ($valor,$dec,",",".");
    return $valor; //retorna o valor formatado para gravar no banco  
}
function fnbusca($array){
    
     //consulta cliente 
   ///////////////////////////////////////////////////////////////////////////////////////////////////
        
                     if(trim($array['cpf'])!=""){$CPF=' and NUM_CGCECPF="'.$array['cpf'].'"';}else{$CPF='';}
                     if(trim($array['cartao'])!=""){$cartao=' and NUM_CARTAO="'.$array['cartao'].'"';} else{$cartao='';}
                     if(trim($array['cpf'])==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];} 
                     $sqlconsultaBase="SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where  COD_EMPRESA='".$array['empresa']."' $cartao $CPF"; 
                     $execbusca=mysqli_query($array['ConnB'],$sqlconsultaBase);
                     
                    if (!$sqlconsultaBase)
                    {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                        try {mysqli_query($array['ConnB'],$sqlconsultaBase);} 
                        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                        $msg="Error description Cadastro Automatico Erro: $msgsql";
                        $xamls= addslashes($msg);
                      
                    } else {
                      $row1 = mysqli_fetch_assoc($execbusca);   
                    }
                //PERGUNTA SE O DADOS DO CLIENTES VEM DA BASE OU IFARO
                   if($row1['contador']>=1)
                   {
                     //VOU CARREGAR DA BASE DE DADOS  
                       
                       $nome= $row1['NOM_CLIENTE'];
                       $cpf=$row1['NUM_CGCECPF'];
                       $cnpj=$row1['NUM_CGCECPF'];
                       $NUM_RGPESSO=$row1['NUM_RGPESSO'];
                       $sexo=$row1['COD_SEXOPES'];
                       $dt_nascime=$row1['DAT_NASCIME'];
                       $COD_CLIENTE=$row1['COD_CLIENTE'];
                       $cartao1=$row1['NUM_CARTAO'];
                       $TIP_CLIENTE=$row1['TIP_CLIENTE'];
                       $NOM_CLIENTE=$row1['NOM_CLIENTE'];
                       $COD_ESTACIV=$row1['COD_ESTACIV'];
                       $NUM_TELEFON=$row1['NUM_TELEFON'];
                       $DES_EMAILUS=$row1['DES_EMAILUS'];
                       $COD_PROFISS=$row1['COD_PROFISS'];
                       $DAT_CADASTR=$row1['DAT_CADASTR'];
                       $DES_ENDEREC=$row1['DES_ENDEREC'];
                       $NUM_ENDEREC=$row1['NUM_ENDEREC'];
                       $DES_BAIRROC=$row1['DES_BAIRROC'];
                       $DES_COMPLEM=$row1['DES_COMPLEM'];
                       $NOM_CIDADEC=$row1['NOM_CIDADEC'];
                       $COD_ESTADOF=$row1['COD_ESTADOF'];
                       $NUM_CEPOZOF=$row1['NUM_CEPOZOF'];
                       $NUM_CARTAO=$row1['NUM_CARTAO'];
                       $DAT_ALTERAC=$row1['DAT_ALTERAC'];
                       $NUM_CELULAR=$row1['NUM_CELULAR'];
                       
                       

                       $msg='Cliente localizado na base de dados!';
                       $cod_msg='14';
                    //   fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpf,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                    }else{
                    //AQUI VOU CARREGAR IFARO    

                       if($array['consultaativa'] == 'S'){

                                   if ( valida_cpf($array['cpf']) ) 
                                    {
                                       //busco no log cadastrado da ifaro
                                       //================================================
                                       
                                       $sqlifaro="select count(CPF) as TEM,log_cpf.* from log_cpf where CPF = '".$array['cpf']."'";
                                       $resultifaro=mysqli_query($array['conn'], $sqlifaro);
                                       $rowifaro=mysqli_fetch_assoc($resultifaro);
                                        if($rowifaro['TEM'] != 0)
                                        {
                                            $nome=$rowifaro['NOME'];
                                            $cpf=$rowifaro['CPF'];
                                            if($rowifaro['SEXO']=='M'){$sexo='1';}else{$sexo='2';}  
                                            $dt_nascime=$rowifaro['DT_NASCIMENTO'];
                                            //if($rowifaro['COD_EMPRESA']!=$array['empresa'])
                                           // {    
                                            $intermediaria1="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                  VALUES 
                                                                                  ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf','$nome' ,'$sexo', '$dt_nascime', '".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."');";
                                            mysqli_query($array['conn'],$intermediaria1);
                                            //}              
                                            
                                            $msg='Consulta Interna OK!';
                                            $cod_msg='0';
                                          //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                       
                                            
                                        }else{    
                                                    //buscar na base de erro se o cpf ja existe.
                                                    $sql="select * from cpf_invalido where CPF='".$array['cpf']."'";
                                                    $retornocpf=mysqli_fetch_assoc(mysqli_query($array['conn'], $sql));
                                                    if($retornocpf['CPF']=='')
                                                    {    
                                                         //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                                         include '../../wsmarka/func/func_ifaro.php';  
                                                         $resultIfaro=ifaro($array['cpf']);
                                                         $resultIfaro=array_filter($resultIfaro);
                                                          if ($resultIfaro['msg']==1){
                                                            //grava log de erro 
                                                            $insertlog="INSERT INTO cpf_invalido (IP,CPF,COD_EMPRESA)VALUES ('".$_SERVER['REMOTE_ADDR']."','".$array['cpf']."','".$array['empresa']."')";
                                                            mysqli_query($array['conn'], $insertlog);  
                                                            $msg='Nenhum cadastro encontrado';
                                                        //    fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                            return  array( 'msgerro' => $msg,
                                                                           'coderro'=>'13');
                                                            exit(); 

                                                          }else{	

                                                                  $nome=$resultIfaro['nome'];
                                                                  $cpf=$resultIfaro['cpf'];
                                                                  if($resultIfaro['sexo']=='M'){$sexo='1';}else{$sexo='2';}  


                                                                if($resultIfaro['coderro']=='250')
                                                                {
                                                                    $msg=$resultIfaro['msg'];
                                                                    $cod_msg='250';
                                                                    $dt_nascime=$resultIfaro['datanascimento'];

                                                                    $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) values
                                                                                              ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','0','0','".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."','".$resultIfaro['timeCo']."','".$resultIfaro['msg']."','".$resultIfaro['sexo']."','".$resultIfaro['datanascimento']."')";
                                                                     mysqli_query($array['conn'],$sql);
                                                                }else{  
                                                                        $dt_nascime=$resultIfaro['datanascimento'];
                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) value
                                                                                                  ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro['cpf']."','".$resultIfaro['nome']."','".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."','".$resultIfaro['timeCo']."','".$resultIfaro['msg']."','".$resultIfaro['sexo']."','".$resultIfaro['datanascimento']."')";
                                                                        mysqli_query($array['conn'],$sql);
                                                                        $intermediaria="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                        VALUES 
                                                                                                        ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '".$resultIfaro['cpf']."','".$resultIfaro['nome']."' ,'".$resultIfaro['sexo']."', '".$resultIfaro['datanascimento']."', '".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."');";
                                                                        mysqli_query($array['conn'],$intermediaria);
                                                                        $msg=$resultIfaro['msg'];
                                                                        $cod_msg='12';
                                                                }
                                                              //   fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor','CONSULTA IFARO',$array['LOG_WS']);
                                                                }
                                                    }elseif ($retornocpf['CPF']==$array['cpf']) {
                                                            $msg='Nenhum cadastro encontrado';
                                                          ///  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                            return  array( 'msgerro' => $msg,
                                                                           'coderro'=>'13');    
                        
                                                    }    
                                                            
                                          //===================================================================                  
                                        }
                                        
                                    }else{
                                        $msg='Nenhum cadastro encontrado';
                                      //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                        return  array( 'msgerro' => $msg,
                                                       'coderro'=>'13');
                                                               
                                    }    
                        } 
                   }  
           
///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        $arraydadosBase=array(  'COD_CLIENTE'=>$row1['COD_CLIENTE'],
                                'nome' => $nome, 
                                'cartao'=>$cartao1,
                                'cpf' => $cpf,
                                'rg'=>$NUM_RGPESSO,
                                'tipocliente'=>$TIP_CLIENTE,
                                'cnpj'=>$cnpj,
                                'nomeportador'=>$NOM_CLIENTE,
                                'grupo'=>'',
                                'sexo'=>$sexo,
                                'datanascimento'=>$dt_nascime,
                                'estadocivil'=>$COD_ESTACIV,
                                'telresidencial'=>$NUM_TELEFON,
                                'telcomercial'=>'',
                                'telcelular'=>$NUM_CELULAR,
                                'email'=>$DES_EMAILUS,
                                'profissao'=>$COD_PROFISS,
                                'clientedesde'=>$DAT_CADASTR,
                                'endereco'=>$DES_ENDEREC,
                                'numero'=>$NUM_ENDEREC,
                                'bairro'=>$DES_BAIRROC,
                                'complemento'=>$DES_COMPLEM,
                                'cidade'=>$NOM_CIDADEC,
                                'estado'=>$COD_ESTADOF,
                                'cep'=>$NUM_CEPOZOF,
                                'cartaotitular'=>$NUM_CARTAO,
                                'bloqueado'=>'',
                                'motivo'=>'',
                                'dataalteracao'=>$DAT_ALTERAC,
                                'adesao'=>'',
                                'codatendente'=>'',
                                'senha'=>$xamls,
                                'fontedados'=>'',
                                'retornoGenerico'=>'',
                                'coderro'=> $cod_msg,
                                'msgerro' => $msg
                                ); 
    
                             
return $arraydadosBase; 
              
///return $sqlconsultaBase;
       
}
function fnreturn($array)
{
  
                    //permissão de modulos de retorno
                $sqlFase="select matriz_integracao.COD_ACAOINT,INTEGRA_acaomtz.KEY_ACAOINT from matriz_integracao
                      LEFT JOIN INTEGRA_VENDAMTZ ON matriz_integracao.COD_FASEVND = INTEGRA_VENDAMTZ.COD_FASEINT
                      LEFT JOIN INTEGRA_acaomtz ON matriz_integracao.COD_ACAOINT = INTEGRA_acaomtz.COD_ACAOINT
                      where matriz_integracao.cod_empresa=".$array['empresa']." 
                      and INTEGRA_VENDAMTZ.KEY_FASEINT='".$array['fase']."' order by INTEGRA_acaomtz.num_ordenac;";
                $rs=mysqli_query($array['conn'], $sqlFase);
                

                if( mysqli_num_rows($rs) == 0){ 
                      $msg='Não existe modulos configurado para retorno!'.$array['fase'];
                      //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                      //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                    
                                                 
                       return  array( 'msgerro' => 'Não existe modulos configurado para retorno!',
                                      'coderro'=>'16');
                      exit();
                } 
                        mysqli_next_result($array['ConnB']);    
                        $arraybusca=fnbusca($array);
                       
                while ($ResultFase=mysqli_fetch_assoc($rs)) {

                  if($ResultFase['COD_ACAOINT']==1)
                  {
                     if($array['cpf']!='' && $array['cpf']  <= 0 ){$cpfcartao=$array['cpf'];}else{$cpfcartao=$array['cartao'];} 
                      $msgr='acao_A_cadastro!';
                     // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                      $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                       $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                          );
                     
                         $acao1=array(
                                      'nome'=>$arraybusca['nome'],
                                      'cartao'=>$arraybusca['cartao'],
                                      'cpf'=> fncompletadoc($arraybusca['cpf'],$arraybusca['tipocliente']),
                                      'sexo'=>$arraybusca['sexo'],
                                      'rg'=>$arraybusca['rg'],
                                      'cnpj'=>fncompletadoc($arraybusca['cnpj'],$arraybusca['tipocliente']),
                                      'nomeportador'=>'',
                                      'grupo'=> '',
                                      'datanascimento'=> $arraybusca['datanascimento'],
                                      'estadocivil'=>$arraybusca['estadocivil'], 
                                      'telresidencial'=>$arraybusca['telresidencial'], 
                                      'telcomercial'=>$arraybusca['telcomercial'],
                                      'telcelular' => $arraybusca['telcelular'],
                                      'email'=>$arraybusca['email'], 
                                      'profissao'=>$arraybusca['profissao'], 
                                      'clientedesde'=>$arraybusca['clientedesde'],
                                      'tipocliente'=>$arraybusca['tipocliente'], 
                                      'endereco'=>$arraybusca['endereco'],
                                      'numero'=>$arraybusca['numero'],
                                      'bairro'=>$arraybusca['bairro'],
                                      'complemento'=>$arraybusca['complemento'], 
                                      'cidade'=>$arraybusca['cidade'],
                                      'estado'=>$arraybusca['estado'],
                                      'cep'=>$arraybusca['cep'],
                                      'cartaotitular'=>'',
                                      'bloqueado'=>'',
                                      'motivo'=>'',
                                      'dataalteracao'=>$arraybusca['dataalteracao'],
                                      'adesao'=>'',
                                      'codatendente'=>'',
                                      'senha'=>'',
                                      'fontedados'=>'',
                                      'retornoGenerico'=>'',
                                      'urltotem'=> "http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                      'coderro'=>$arraybusca['coderro'],
                                      'msgerro'=>$arraybusca['msgerro']
                                 ); 
                   }
                   
                  if($ResultFase['COD_ACAOINT']==2)
                  {
                      if($array['cpf']!='')
                      {
                          $cpfcartao=$array['cpf'];
                      }
                      elseif ($array['cnpj']!='') 
                      {
                          $cpfcartao=$array['cnpj'];
                      }
                       else{
                           $cpfcartao=$array['cartao'];
                        }   
                        
                      $id=fnEncode($array['empresa'].';'.$cpfcartao.';'.$array['COD_UNIVEND']);
                        //grava log
                        //
                        $xmlteste=addslashes(file_get_contents("php://input"));
                        $inserarray='INSERT INTO log_tkt (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                             ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                              "'.$array['COD_USUARIO'].'","'.$array['login'].'","'.$array['empresa'].'","'.$array['idloja'].'","'.$array['idmaquina'].'","0","'.$cpfcartao.'","'.$xmlteste.'","'.$xmlteste1.'")';
                        $loginputtkt=mysqli_query($array['ConnB'],$inserarray);
                        //                      
                          mysqli_free_result($loginputtkt);
                          mysqli_next_result($array['ConnB']);
                         $msgr='acao_B_Ticket_de_Ofertas!';
                         
                         $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                           );
                         
                      ////////////ofertas
                      //=========================
  
 /////////ARRAY PARA GRAVA TKT
    
                         
    if($arraybusca['COD_CLIENTE']!='')
    {       
        $selconfig="SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =".$array['empresa']."   and LOG_ATIVO_TKT = 'S'";
        $conf=mysqli_query($array['ConnB'], $selconfig);
        $rwconfig= mysqli_fetch_assoc($conf); 
        
            if($rwconfig['LOG_ATIVO_TKT']=='S')
            {    
                $arrayDados=array('cod_empresa'=>$array['empresa'],
                                  'idloja'=>$array['idloja'],
                                  'idmaquina'=>$array['idmaquina'],
                                  'cpf'=>$array['cpf'],
                                  'cartao'=>$array['cartao'],
                                  'cnpj'=>'',
                                  'id_cliente'=>$arraybusca['COD_CLIENTE'],
                                  'login'=>$array['login'],
                                  'codvendedor'=>$array['codvendedor'],
                                  'nomevendedor'=>$array['nomevendedor'],
                                  'pagina'=>$array['pagina'],
                                  'connadm'=>$array['conn'],
                                  'connempresa'=>$array['ConnB'],
                                  'cod_user'=>$array['COD_USUARIO'],
                                  'database'=>$array['database'],
                                  'LOG_WS'=>$array['LOG_WS']

                                   );
              $fngeratkt=fngeratkt($arrayDados);  
//if($array['empresa']=66)
//{
   // print_r($fngeratkt);    
//}        
              
                  //======================================================== 
              //FIM DO IF DA FLAG ATIVA OU DESATIVA



                                    //=========================================================================
                                    //'ofertasTicket'=>array('produtoTicket'=>array('descricao'=>'teste')),
                                  //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                                    $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';

                                  $acao2=array('url_ticketdeofertas'=>'http://ticket.fidelidade.mk/?tkt='.$id,
                                                'urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                                 'regrapreco'=>$regrapreco, 
                                                 'ofertasHabito'=>array('produtoHabito'=>$fngeratkt['produtoHabito']),
                                                 'ofertasTicket'=>array('produtoTicket'=>$fngeratkt['produtoTicket']), 
                                                 'ofertasPromocao'=>array('produtoPromocao'=>$fngeratkt['produtoPromocao']),
                                                 'coderro'=>'17',
                                                 'msgerro'=> 'bem vindo ao tktmania!');
            }else
            {
                $msg="tktmania não esta habilitado";
               // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                $acao2=array('coderro'=>'0', 'msgerro'=> $msg);               
            }    
            
    } else {
            $msg1="cliente não está fidelizado por esse motivo não gera ofertas !";
           // fngravalogMSG($array['conn'],$array['login'],$array['idcliente'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg1,$array['LOG_WS']);
               
            $acao2=array('coderro'=>'58',
                         'msgerro'=> $msg1);
    }                

                  } 
                  if($ResultFase['COD_ACAOINT']==3)
                  {
                      $sql='select * from site_extrato where cod_empresa='.$array['empresa'];
                      $RSSITE=mysqli_query($array['ConnB'], $sql);
                      if (!$RSSITE)
                        {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($array['conn'],$sql);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg="Error na lista de SITE: $msgsql";
                            $xamls= addslashes($msg);
                          //  fngravalogMSG($array['conn'],$array['login'],$array['idcliente'],$cartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'ACAO PRODUTO',$xamls,$array['LOG_WS']);
                          //  
                        } else {
                            $rwsite=mysqli_fetch_assoc($RSSITE);
                            $DES_DOMINIO=$rwsite['DES_DOMINIO'];

                            $msgr='acao_C_campanha!';
                          //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                            if($DES_DOMINIO!=""){
                                $site="http://$DES_DOMINIO.fidelidade.mk/";
                                $msg="Modelo Padrão de hot site";
                                $cod='59';
                                $xamls= addslashes($msg);
                               // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$xamls,$array['LOG_WS']);
                            
                            }else{
                                $site="";
                                $msg="Hot Site não cadastrado!";
                                $cod='60';
                                $xamls= addslashes($msg);
                               // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$xamls,$array['LOG_WS']);
                            
                            }
                            $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                           );    
                            $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                            $acao3=array('url_campanha'=>$site,
                                         'urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                          'coderro'=>$cod,
                                          'msgerro'=>$msg); 
                        }
                      
                      

                  } 
                  if($ResultFase['COD_ACAOINT']==4)
                  {
                       if($array['cpf']==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];}
                      $msgr='acao_D_mensagem!';
                      //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                      $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                      
                      //===================================
                      $sql="select * from comunicacao_modelo where cod_tipcomu=4 and cod_empresa=".$array['empresa']." and cod_exclusa=0";
                      $sqlexec=mysqli_query($array['connB'], $sql);
                        if (!$sqlexec)
                        {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($array['connB'],$sql);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg="Não há comunicação modelo: $msgsql";
                            $xamls= addslashes($msg);
                           // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                      

                        } else {
                             if( mysqli_num_rows($sqlexec) == 0){ 
                                        $msg='Não há mensagem cadastrada :-(!';
                                      //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                                        $acao4=  array('msgerro' => $msg,'coderro'=>'61');
                                       // exit();
                                 }else{  
                                    $sqlretorno= mysqli_fetch_assoc($sqlexec);
                                    $msg='Mensagem PDV Ativa';
                                   // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                                       
                                 }
                            
                          
                          
                          
                        }
                      
                      $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                           );
                         
                      //=====================================
                      $acao4=array('txtmensagem'=>$sqlretorno['DES_TEXTO_SMS'],
                                   'urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                   'coderro'=>62,
                                   'msgerro'=>$msg); 

                  }
                  if($ResultFase['COD_ACAOINT']==5)
                  {
                      if($array['cpf']==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];}
                        $msgr='acao_E_ListadeOfertas!';
                        //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                        $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                        
                        //Select busca configuração TKT
                        $selconfig="SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =".$array['empresa']."   and LOG_ATIVO_TKT = 'S'";
                        $rwconfig= mysqli_fetch_assoc(mysqli_query($array['ConnB'], $selconfig));
                        if($rwconfig['COD_CONFIGU'] !="")
                        {
                                $qtd_ofertas_tkt=$rwconfig['QTD_OFERTWS_TKT'];
                                $regrapreco=$rwconfig['DES_PRATPRC'];

                                 //lista de ofertas
                                $sql="SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                                    where  A.COD_EMPRESA = ".$array['empresa']." AND
                                       A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                                       A.COD_PRODUTO = C.COD_PRODUTO AND										   
                                       A.LOG_ATIVOTK = 'S' AND 
                                       A.LOG_OFERTAS = 'S' AND
                                       ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET(".$array['idloja'].",A.COD_UNIVEND_AUT))) AND
                                       ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET(".$array['idloja'].",A.COD_UNIVEND_BLK))) AND
                                       ((A.DAT_INIPTKT <= NOW()) AND (A.DAT_FIMPTKT >= NOW()) )  
                                       ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt";
                                $sqlexec=mysqli_query($array['ConnB'], $sql);

                                    if (!$sqlexec)
                                    {
                                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                                        try {mysqli_query($array['ConnB'],$sql);} 
                                        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                                        $msg="Error na lista de produto: $msgsql";
                                        $xamls= addslashes($msg);
                                      //  fngravalogMSG($array['conn'],$dadosLogin['login'],$dadosLogin['idcliente'],$cpfcartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],$array['pagina'],$xamls,$array['LOG_WS']);
                                        $acaoE= array( 'msgerro' => 'Erro na lista de produto!','coderro'=>'63');
                                        

                                    } else {
                                        //verifica se tem itens na lista de produtos
                                        if( mysqli_num_rows($sqlexec) == 0){ 
                                                $msg='Não há Itens para exibir na lista!';
                                               // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                                                $acaoE=  array( 'msgerro' => $msg,'coderro'=>'64');
                                               // exit();
                                         }else{ 

                                        // exibi itens na lista de ws    
                                            while ($sqlretorno= mysqli_fetch_assoc($sqlexec))
                                            {
                                                $cod_empresa=$array['empresa'];
                                                IF($sqlretorno['DES_IMAGEM']!="")
                                                {
                                                 $IMG="http://img.bunker.mk/media/clientes/$cod_empresa/produtos/".$sqlretorno['DES_IMAGEM']."";   
                                                }   
                                                $acaoE[]=array( 'codigoexterno'=>$sqlretorno['COD_EXTERNO'],
                                                                'codigointerno'=>$sqlretorno['COD_PRODUTO'],
                                                                'ean'=> '',
                                                                'descricao'=>$sqlretorno['NOM_PRODTKT'],
                                                                'preco'=>fnformatavalorretorno($sqlretorno['VAL_PRODTKT']),
                                                                'valorcomdesconto'=>fnformatavalorretorno($sqlretorno['VAL_PROMTKT']),
                                                                'imagem'=>$IMG,
                                                                'msgpromocional'=>$sqlretorno['DES_MENSGTKT'],
                                                                'regrapreco'=>$regrapreco,
                                                                'coderro'=>'',
                                                                'msgerro'=>$msg); 
                                            }
                                            
                                         }
                                    }
                            } else {
                             //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],'Não existe configuração de produtos do TICKET',$array['LOG_WS']);
                             $acaoE[]=array('coderro'=>65,'msgerro'=>"Não existe configuração de produtos do TICKET");     
                            }
                            $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                           );
                          $acao5=array('urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                       'listaoferta'=>$acaoE);
                                  
                                    
                   }
                  if($ResultFase['COD_ACAOINT']==6)
                  {
                      $lista='acao_F_desconto!';
                      $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                        if($array['cpf']==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];}
                     // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$lista,$array['LOG_WS']);
                      $selconfig="SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =".$array['empresa']."   and LOG_ATIVO_TKT = 'S';";
                      $rwconfig= mysqli_fetch_assoc(mysqli_query($array['ConnB'], $selconfig));
                      $qtd_ofertas_tkt=$rwconfig['QTD_OFERTWS_TKT'];
                      $regrapreco=$rwconfig['DES_PRATPRC'];
                      
                      mysqli_free_result($rwconfig);
                      mysqli_next_result($array['ConnB']);
        
                      
                      
                        $sql="SELECT * FROM DESCONTOS 
                                   WHERE  ( DAT_INIPTKT <= '".date('Y-m-d H:i:s')."' AND 
                                            DAT_FIMPTKT >= '".date('Y-m-d H:i:s')."') and 
                                            LOG_PRODTKT ='S' and COD_EMPRESA =".$array['empresa']." limit 1 " ;
                        $EXECSQL= mysqli_query($array['ConnB'],$sql); 
                        
                        
                        //=======TESTE SQL
                        if (!$EXECSQL)
                            {
                                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                                try {mysqli_query($array['ConnB'],$sql);} 
                                catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                                $msg="Error na lista de desconto: $msgsql";
                                $lista= addslashes($msg);
                             //   fngravalogMSG($array['conn'],$array['login'],$array['idcliente'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$lista,$array['LOG_WS']);
                                $acaoF=  array( 'msgerro' => $lista,'coderro'=>'0');
                                
                            } else {
                              /*
                               apresentar msg de erro da base de dados.
                                while( $rwsql1=mysqli_fetch_assoc($EXECSQL))
                                {        
                                    if($rwsql1['DAT_INIPTKT'] <= date('Y-m-d H:i:s') || $rwsql1['DAT_FIMPTKT']>= date('Y-m-d H:i:s'))
                                    {
                                        $acao6[]=array('descontosobrepercentual'=>$rwsql['PCT_DESCONTO'],
                                                        'descontosobrevalor'=>$rwsql['VAL_DESCONTO'],
                                                        'coderro'=>'0',
                                                        'msgerro'=>$rwsql['DES_ERRODESC']); 
                                    }
                                }*/
                                
                                //verifica se tem atividade
                                if( mysqli_num_rows($EXECSQL) == 0){ 
                                       
                                        $lista='Nenhum desconto ativo!';
                                      //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$lista,$array['LOG_WS']);
                                        $acaoF=array( 'msgerro' => $lista,'coderro'=>'66');
                                }else{
                                  //grava log 
                                    $lista='Lista de desconto OK!';
                                   // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$lista,$array['LOG_WS']);
                                    //==========================================
                                  
                                    //retor no da lista
                                    $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                                    while($rwsql=mysqli_fetch_assoc($EXECSQL))
                                    {        
                                        $acaoF[]=array('descontosobrepercentual'=>fnformatavalorretorno($rwsql['PCT_DESCONTO']),
                                                        'descontosobrevalor'=>fnformatavalorretorno($rwsql['VAL_DESCONTO']),
                                                        'regrapreco'=>fnformatavalorretorno($regrapreco),
                                                        'coderro'=>'67',
                                                        'msgerro'=>$rwsql['DES_MENSGTKT']); 
                                    }
                                    //============================================  
                                    
                                }  
                                //===================================================
                            }
                            $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                          );
                        $acao6=array('urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                      'desconto'=>$acaoF);
                          
                        

                  }
                 if($ResultFase['COD_ACAOINT']==7)
                  {
                     // cria chave de caatro por cartao/cpf
                      if($array['cpf']==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];}    
                      $msgr='acao_G_Cupomdesconto!';
                     // fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msgr,$array['LOG_WS']);
                       $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                       $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor']
                                          );
                        $acaoG[]=array('numcupom'=>'987654',
                                       'descontosobrepercentual'=>'10,00',
                                       'descontosobrevalor'=>'0,00',
                                        'coderro'=>'68',
                                       'msgerro'=>'Ganhe na próxima compra!'); 
                       /* $acaoG[]=array('numcupom'=>'987654',
                                       'descontosobrepercentual'=>'0.00',
                                       'descontosobrevalor'=>'15.00',
                                       'coderro'=>'0',
                                       'msgerro'=>'Ganhe na próxima compra!'); 
                        $acaoG[]=array('numcupom'=>'324247567',
                                       'descontosobrepercentual'=>'0.00',
                                       'descontosobrevalor'=>'1.20',
                                       'coderro'=>'0',
                                       'msgerro'=>'Ganhe na próxima compra!');
                        $acaoG[]=array('numcupom'=>'324247567',
                                       'descontosobrepercentual'=>'3.00',
                                       'descontosobrevalor'=>'0.20',
                                       'coderro'=>'0',
                                       'msgerro'=>'Ganhe na próxima compra!');               
                                                 */
                   $acao7=array('urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                      'cupomdesconto'=>$acaoG);
                             
                        
                  } 
                         
                  if($ResultFase['COD_ACAOINT']==8)
                  {
                        if($array['cpf']==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];}  
                        $acaoRetorno.=$ResultFase['KEY_ACAOINT'].',';
                        //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],'acao_H_saldo/Consulta saldo',$array['LOG_WS']);
                        
                     
                        if ($arraybusca['COD_CLIENTE']=="")
                            {
                                
                                $msg="Cliente não tem saldo para exibir!";
                                //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],$array['pagina'],$msg,$array['LOG_WS']);
                                $acao8=  array( 'msgerro' => $msg,'coderro'=>'69');
                                
                            }else{
                                $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$arraybusca['COD_CLIENTE'].");";
                                $sld=mysqli_query($array['ConnB'],$consultasaldo);
                                $retSaldo = mysqli_fetch_assoc($sld);
                                
                                $saldodisponivel=fnvalorretornojson($retSaldo['CREDITO_DISPONIVEL'],$array['decimal']);
                                $saldototal=fnvalorretornojson($retSaldo['TOTAL_CREDITO'],$array['decimal']);
                                $vantagemacumulada="Quanto mais você usar mais vantagens você terá :-]";
                                $msgerro='Seu saldo ;-]';
                              
                                  //fim da consulta
                                $urltotem=fnEncode($array['login'].';'
                                          .$array['senha'].';'
                                          .$array['idloja'].';'
                                          .$array['idmaquina'].';'
                                          .$array['empresa'].';'
                                          .$array['codvendedor'].';'
                                          .$array['nomevendedor'].';'
                                           .$cpfcartao
                                           );
                                $acao8= array('saldodisponivel'=>$saldodisponivel,
                                              'saldototal'=>$saldototal,
                                              'vantagemacumulada'=>$vantagemacumulada,
                                              'urltotem'=>"http://totem.bunker.mk/cadastro.do?key=$urltotem",
                                              'urlsaldo'=>"http://extrato.bunker.mk?key=$urltotem",
                                              'coderro'=>'18',
                                              'msgerro'=> $msgerro
                                                ); 
                            }
                     
                    
                     

                  }

                  };

               $acaoRetorno= substr($acaoRetorno,0,-1); 
               $cod_erro=$array['coderro'];
               $msg=$array['menssagem'];
          
                 //retorno
                //fim aqui
        return array(   'acoesfidelizacao'=>$acaoRetorno,
                        'acao_A_cadastro'=>$acao1,
                        'acao_B_Ticket_de_Ofertas'=>$acao2,
                        'acao_C_campanha'=>$acao3,
                        'acao_D_mensagem'=>$acao4,
                        'acao_E_ListadeOfertas'=>$acao5,
                        'acao_F_desconto'=>$acao6,
                        'acao_G_Cupomdesconto'=>$acao7,
                        'acao_H_saldo'=> $acao8,
                        'retornoGenerico' =>$row1['contador'],
                        'coderro' =>$cod_erro,
                        'msgerro' =>$msg
                        );

                                                                   
    
}