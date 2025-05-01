<?php

//=================================================================== GetURLTktMania ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'GetURLTktManiaRetorno',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'urltktmania' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltktmania', 'type' => 'xsd:string')

        )
        );
//retorno dados

//inserir dados
//4419

     
$server->register('GetURLTktMania',
			array(
                              'CPFCARTAO'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('GetURLTktManiaResult' => 'tns:GetURLTktManiaRetorno'),  //output
                    $ns,         						// namespace
                    "$ns/GetURLTktMania",     						// soapaction
                    'document',                         // style
                    'literal',                          // use
                    'GetURLTktMania'  );  //description

 function GetURLTktMania($CPFCARTAO,$dadosLogin) {
 
       //ini_set('display_errors', 1);
     //  ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
  
     
     include_once '../_system/Class_conn.php';
     include_once './func/function.php'; 
   
        if(fnlimpaCPF($CPFCARTAO) ==0){
           return  array('GetURLTktManiaResult'=>array('msgerro'=>'Cliente avulso nao gerara ticket!'));
           exit();
        }
        
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
   
    
     //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           return  array('GetURLTktManiaResult'=>array('msgerro'=>'LOJA DESABILITADA'));
           exit();   
        }   
   
        
       
        
    //GRAVA LOG
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else {
        
        }
    
     //limpa campos cartao/cpf
     $CPFCARTAOLIMPO=fnlimpaCPF($CPFCARTAO); 
       
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']) )
        {
                    //conn user
           $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
           //verifica cod empresa
            $dadosbase=fn_consultaBase($connUser->connUser(),$CPFCARTAOLIMPO,'',$CPFCARTAOLIMPO,'','',$row['COD_EMPRESA']);

                //return array('GetURLTktManiaResult'=> array( 'msgerro'=> print_r($dadosbase)));
              if($dadosbase[0]['COD_CLIENTE']=="")
               {
                 return array('GetURLTktManiaResult'=> array( 'msgerro'=> 'Cliente não cadastrado!' ));
                 exit();

               } 
            //grava log
            //
            $xmlteste=addslashes(file_get_contents("php://input"));
            $inserarray='INSERT INTO log_tkt (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                 ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                  "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","0","'.$CPFCARTAO.'","'.$xmlteste.'","'.$arralogin.'")';
            mysqli_query($connUser->connUser(),$inserarray);
            //
            //

          //memoria
          $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'GetUTLTktMania',$row['COD_EMPRESA']);
  
          
           if($row['LOG_ATIVO']!='S')
            {
                return array('GetURLTktManiaResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
                exit();
            }
                 
                if($passou!=1)
                {    
                         
                     $cod_univend=fnConsultaLojaGET($connAdm->connAdm(),$dadosLogin['idloja']);
                    if($dadosbase[0]['COD_CLIENTE']!='')
                    {    
                           // print_r($cod_univend);
                            //busca cliente por cpf
                            $buscaCPF='SELECT * FROM clientes where NUM_CARTAO="'.$CPFCARTAOLIMPO.'"';
                            $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
                            $id=fnEncode($row['COD_EMPRESA'].';'.$CPFCARTAOLIMPO.';'.$cod_univend[0]['COD_UNIVEND']);
                             
                            
                            $classfpers="call SP_CLASSIFICA_PERSONA_TKT('".$dadosbase[0]['COD_CLIENTE']."','".$row['COD_EMPRESA']."')";
                            $exec=mysqli_query($connUser->connUser(), $classfpers);
               
                            $msg='OK';
                                 /*if($dadosLogin['idcliente']==21){
                                  return array('GetURLTktManiaResult'=> array( 'msgerro'=> 'Cliente não cadastrado!' ));
                                  exit();
                                } */
                            $arrayDados=array('cod_empresa'=>$row['COD_EMPRESA'],
                                                'idloja'=>$dadosLogin['idloja'],
                                                'idmaquina'=>$dadosLogin['idmaquina'],
                                                'cpf'=>$CPFCARTAO,
                                                'cartao'=>$CPFCARTAO,
                                                'cnpj'=>'',
                                                'id_cliente'=>$dadosbase[0]['COD_CLIENTE'],
                                                'login'=>$dadosLogin['login'],
                                                'codvendedor'=>$dadosLogin['codvendedor'],
                                                'nomevendedor'=>$dadosLogin['nomevendedor'],
                                                'pagina'=>'Busca_antiga',
                                                'connadm'=>$connAdm->connAdm(),
                                                'connempresa'=>$connUser->connUser(),
                                                'cod_user'=>$row['COD_USUARIO'],
                                                'DECIMAL'=>$row['NUM_DECIMAIS']

                                                 );
                              
                            $fngeratkt=fngeratkt($arrayDados);
                            if($fngeratkt['msgerro']=='Não existe configuração no TICKET!')
                            {
                              return  array('GetURLTktManiaResult'=>array('msgerro'=>'Não existe configuração no TICKET!'));   
                            }  
                            //if($dadosLogin['idcliente']==122){
                           // return array('GetURLTktManiaResult'=> array( 'msgerro'=> $fngeratkt ));
                            //exit();
                            //    } 
                }else{$msg='Cliente não cadastrado!';}        //fim da busca

//memoria log   
//===================================================================================================                            
fnmemoriafinal($connUser->connUser(),$cod_men); 
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 

                                          return array('GetURLTktManiaResult'=>array(
                                                                        'msgerro'=> $msg ,
                                                                        'urltktmania'=>'http://ticket.fidelidade.mk/?tkt='.$id 
                                                                      //  'urltktmania'=>'http://fidelidade.mk/?tkt='.$id     

                                            ));
                                      
                } else {
                    return array('GetURLTktManiaResult'=>array( 'msgerro'=>'idcliente não confere com o cadastrado!'));
                }                          
        }else{
            return array('GetURLTktManiaResult'=>array('msgerro'=>'Erro Na autenticação'));

        }   
      
     
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
