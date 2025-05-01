<?php
//verificar o porque quando esta sem a placa não retorno msg 
//tratar isso.
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
    'token_regate',
    'complexType',
    'struct',
    'sequence',
    '',
        array('token' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'token', 'type' => 'xsd:string'),
              'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
              'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
              'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:string')
            
            )
);
$server->register('tokenresgate',
                       array('id_token'=>'xsd:string',
                             'cpf'=>'xsd:string',
                             'id_vendapdv'=>'xsd:string',
                             'dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('retorna_token' => 'tns:token_regate'),  //output
			 $ns,         						// namespace
                        "$ns/token_regate",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'token_regate'         		// documentation
                );

function tokenresgate ($id_token,$cpf,$id_vendapdv,$dadosLogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
    ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
           $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'listaEstadoCivil',$row['COD_EMPRESA']);

              //verifica id_empresa
              if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
                {
                     fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Id_cliente não confere com o cadastro!',$row['LOG_WS']); 
                      return array('retorna_token'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
                       exit();
                }

                //VERIFICA SE O USUARIO FOI DESABILITADA
                if($row['LOG_ESTATUS']=='N'){
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
                   return  array('retorna_token'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
                   exit();
                }
            //////////////////////=================================================================================================================
               //empresa desabilidatada

                if($row['LOG_ATIVO']!='S')
                { 
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
                     return array('retorna_token'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
                    exit();
                }

        }else{
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Erro Na autenticação!',$row['LOG_WS']); 
                    return array('retorna_token'=>array('msgerro'=>'Erro Na autenticação!'));
        }
        //VERIFICAR SE O CPF ESTA PREENCHIDO
        IF(trim(rtrim($cpf)!=''))
        {
            $andcpf="AND NUM_CGCECPF=$cpf";
        }else{
            $andcpf='';
        }    
//busca de tokem do cliente
        $SQLTOKEN="SELECT * FROM TOKEN_RESGATE WHERE COD_EMPRESA='".$dadosLogin['idcliente']."' AND DES_TOKEN='".$id_token."' $andcpf";
        $RSTOKEN= mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $SQLTOKEN));
        //if($cpf=='01734200014' || $cpf=='42147177830' || $cpf=='41752419812' || $cpf=='41119557895')
        //{	
                if($RSTOKEN['COD_UNIPREF']!= $dadosLogin['idloja'])
                {
                        $ret=array( 'token'=>$id_token,
                                                        'msgerro'=>" Esse token $id_token pertence a outra unidade!",
                                                        'coderro'=>'503');
                        return array('retorna_token'=>$ret);
                        exit();				
                }
                if($RSTOKEN['COD_VENDAPDV']=='EXPIRADO')
                {
                    $ret=array( 'token'=>$id_token,
                                                    'msgerro'=>"token EXPIRADO por gere um novo token!",
                                                    'coderro'=>'503');
                    return array('retorna_token'=>$ret);
                    exit();				
                }
        //}
        
        if($RSTOKEN['DES_TOKEN']=='')
        {    
            if($id_token=='')
            {    
                $ret=array( 'token'=>$id_token,
                            'msgerro'=>"Token não preenchido!",
                            'coderro'=>'504');
            }else{               
                $ret=array( 'token'=>$id_token,
                            'msgerro'=>"Token $id_token não existe!",
                            'coderro'=>'503');
            }
            
        }elseif ($RSTOKEN['DES_TOKEN']!='') {
            $ret=array( 'token'=>$id_token,
                        'msgerro'=>"OK",
                        'coderro'=>'0',
                        'cartao'=> fncompletadoc($RSTOKEN['NUM_CGCECPF'])); 
            //alterar cod_pedv
            if($RSTOKEN['COD_MSG']!='0')
            {
                 $ret=array( 'token'=>$id_token,
                             'msgerro'=>"Token $id_token ja utilizado!",
                             'coderro'=>'502'); 
            }
            if($cpf!='')
            {    
                if($RSTOKEN['COD_MSG']=='0' && fncompletadoc($RSTOKEN['NUM_CGCECPF'])!= fncompletadoc(fnlimpaCPF($cpf)))
                {
                        $ret=array( 'token'=>$id_token,
                                    'msgerro'=>"Token $id_token pertence a outro cliente!",
                                    'coderro'=>'501');
                }
            }
       }            
       
    
ob_end_flush();
ob_flush();
fnmemoriafinal($connUser->connUser(),$cod_men);  
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 

// print_r($itn);                                         
return array('retorna_token'=>$ret);
}
?>