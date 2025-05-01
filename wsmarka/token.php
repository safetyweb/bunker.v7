<?php
//verificar o porque quando esta sem a placa não retorno msg 
//tratar isso.
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
    'token',
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
$server->register('token',
                       array('id_token'=>'xsd:string',
                              'id_key'=>'xsd:string',
                             'dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('retornatoken' => 'tns:token'),  //output
			 $ns,         						// namespace
                        "$ns/verificavenda",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'verificavenda'         		// documentation
                    );
function token ($id_token,$id_key,$dadosLogin) {
     
    include_once '../_system/Class_conn.php';
    include_once './func/function.php'; 
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
                      return array('retornatoken'=>array('msgerro'=>'Id_cliente não confere com o cadastro!'));
                       exit();
                }

                //VERIFICA SE O USUARIO FOI DESABILITADA
                if($row['LOG_ESTATUS']=='N'){
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
                   return  array('retornatoken'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
                   exit();
                }
            //////////////////////=================================================================================================================
               //empresa desabilidatada

                if($row['LOG_ATIVO']!='S')
                { 
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
                     return array('retornatoken'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
                    exit();
                }

        }else{
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Erro Na autenticação!',$row['LOG_WS']); 
                    return array('retornatoken'=>array('msgerro'=>'Erro Na autenticação!'));
        }
$selecionaplaca="SELECT * FROM clientes where cod_tpcliente in (7,8,9,10,11) and 
                 (FIND_IN_SET('".$dadosLogin['idloja']."',cod_multemp));";
$retornocli=mysqli_query($connUser->connUser(), $selecionaplaca);
//busca nome unidade de venda
$busunidade="select nom_fantasi from webtools.unidadevenda where cod_univend=".$dadosLogin['idloja'];
$rsunidade=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $busunidade));
//===============================

        
        $sqltokem="select * from tokem where des_tokem='".addslashes($id_token)."'";
        $queryprincipal=mysqli_query($connUser->connUser(), $sqltokem);
        $retuntokem=mysqli_fetch_assoc($queryprincipal);
//MENSAGEM PUSH EM 3 LINHAS (Token Inválido / Posto / Data hora)    
   $id_key=fnplacamercosul($id_key);
        
if($retuntokem['log_usado'] =='S')
{    
   
    //verifica pra quais clientes o tokem pertence
    $verificatoken="
                    SELECT 
                    itemvenda.DES_PARAM1,
                    itemvenda.DES_PARAM2,
                    tokem.des_tokem,
                    vendas.COD_VENDA,
                    vendas.COD_VENDAPDV,
                    vendas.COD_UNIVEND,
                    vendas.COD_CLIENTE,
                    vendas.COD_EMPRESA,
                    clientes.NUM_CARTAO,
                    tokem.cod_cliente,
                    tokem.log_usado
                    FROM itemvenda
                    inner join vendas on vendas.COD_VENDA=itemvenda.COD_VENDA
                    inner join clientes on clientes.COD_CLIENTE=vendas.COD_CLIENTE
                    left join tokem on tokem.des_tokem=itemvenda.DES_PARAM2
                    WHERE  itemvenda.DES_PARAM1='$id_key' and tokem.des_tokem='$id_token'
                   ";
    $resultcheck=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $verificatoken));
}       
        
        
    if($retuntokem['log_usado'] =='S')
    {        
        if($resultcheck['NUM_CARTAO']==$resultcheck['cod_cliente'])
        {
            while ($cli=mysqli_fetch_assoc($retornocli)){
                             $msg=array('msg'=>"Token $id_token ja utilizado! \n ".$rsunidade['nom_fantasi']."\n".date("d/m/Y"),
                                        'cartao'=>$cli['COD_EXTERNO'],
                                        'entidade'=>$cli['COD_ENTIDAD'],
                                        'email'=>$cli['DES_EMAILUS'],  
                                        'nome'=>$cli['NOM_CLIENTE'], 
                                        'cod_tpcliente'=>$cli['COD_TPCLIENTE']
                                 );
                           // sendMessage($msg);
                        }  
                        mysqli_free_result($retornocli);            
        $ret=array( 'token'=>$id_token ,
                    'msgerro'=>"Token $id_token ja utilizado!",
                    'coderro'=>'502');
        }elseif ($resultcheck['NUM_CARTAO']!=$resultcheck['cod_cliente']) {
            
             while ($cli=mysqli_fetch_assoc($retornocli)){
                             $msg=array('msg'=>"Token $id_token pertence a outro motorista  \n ".$rsunidade['nom_fantasi']."\n".date("d/m/Y"),
                                    'cartao'=>$cli['COD_EXTERNO'],
                                    'entidade'=>$cli['COD_ENTIDAD'],
                                    'email'=>$cli['DES_EMAILUS'],  
                                    'nome'=>$cli['NOM_CLIENTE'], 
                                    'cod_tpcliente'=>$cli['COD_TPCLIENTE']
                                 );
                          //  sendMessage($msg);
            }
            mysqli_free_result($retornocli);
            $ret=array( 'token'=>$id_token,
                        'msgerro'=>"Token $id_token pertence a outro motorista",
                        'coderro'=>'501');
        }
}   
     
    if(mysqli_num_rows($queryprincipal)<=0 || $retuntokem['log_usado']=='N' )
    {           
            $verificatoken="
               SELECT 
               itemvenda.DES_PARAM1,
               itemvenda.DES_PARAM2,
               tokem.des_tokem,
               vendas.COD_VENDA,
               vendas.COD_VENDAPDV,
               vendas.COD_UNIVEND,
               vendas.COD_CLIENTE,
               vendas.COD_EMPRESA,
               tokem.cod_cliente,
               tokem.log_usado
               FROM itemvenda
               inner join vendas on vendas.COD_VENDA=itemvenda.COD_VENDA
               inner join clientes on clientes.COD_CLIENTE=vendas.COD_CLIENTE
               left join tokem on tokem.des_tokem=itemvenda.DES_PARAM2
               WHERE  itemvenda.DES_PARAM2='$id_token'
              ";
              $resultcheck=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $verificatoken));
              
              
                   if($retuntokem['log_usado']=='N' && $id_key == $retuntokem['des_placa'])
                   { 
                     $dadoscli="SELECT COD_CLIENTE from tokem WHERE des_tokem='$id_token'";
                     $dadosclirs=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dadoscli));
                     
                      $ret=array( 'token'=>$id_token,
                                  'msgerro'=>"OK",
                                  'coderro'=>'0',
                                  'cartao'=> fncompletadoc($dadosclirs['COD_CLIENTE'])); 
                      
                   }elseif($resultcheck['DES_PARAM2']!='' && $resultcheck['des_tokem']==''){
                        while ($cli=mysqli_fetch_assoc($retornocli)){
                             $msg=array('msg'=>"Token Invalido $id_token!  \n ".$rsunidade['nom_fantasi']."\n".date("d/m/Y"),
                                        'cartao'=>$cli['COD_EXTERNO'],
                                        'entidade'=>$cli['COD_ENTIDAD'],
                                        'email'=>$cli['DES_EMAILUS'],  
                                        'nome'=>$cli['NOM_CLIENTE'], 
                                        'cod_tpcliente'=>$cli['COD_TPCLIENTE']
                                 );
                          //  sendMessage($msg);
                        }
                        mysqli_free_result($retornocli);
                        $ret=array('token'=>$id_token,
                                   'msgerro'=>"Token Invalido $id_token",
                                   'coderro'=>'500');
                   }elseif ($id_key != $retuntokem['des_placa']) {
                      
                        while ($cli=mysqli_fetch_assoc($retornocli)){
                             $msg=array('msg'=>"Token $id_token pertence a outro motorista!  \n ".$rsunidade['nom_fantasi']."\n".date("d/m/Y"),
                                    'cartao'=>$cli['COD_EXTERNO'],
                                    'entidade'=>$cli['COD_ENTIDAD'],
                                    'email'=>$cli['DES_EMAILUS'],  
                                    'nome'=>$cli['NOM_CLIENTE'], 
                                    'cod_tpcliente'=>$cli['COD_TPCLIENTE']
                                 );
                         //   $response=sendMessage($msg); 
                               
                        }
                        mysqli_free_result($retornocli);
                        $ret=array( 'token'=>$id_token,
                                    'msgerro'=>"Token $id_token pertence a outro motorista",
                                    'coderro'=>'501');
                    }  
}
    if(mysqli_num_rows($queryprincipal) <=0 )
    { 
            while ($cli=mysqli_fetch_assoc($retornocli)){
                             $msg=array('msg'=>"Token $id_token não existe!  \n ".$rsunidade['nom_fantasi']."\n".date("d/m/Y")."",
                                    'cartao'=>$cli['COD_EXTERNO'],
                                    'entidade'=>$cli['COD_ENTIDAD'],
                                    'email'=>$cli['DES_EMAILUS'],  
                                    'nome'=>$cli['NOM_CLIENTE'], 
                                    'cod_tpcliente'=>$cli['COD_TPCLIENTE']
                                 );
                        //    $response=sendMessage($msg); 
            }
                        mysqli_free_result($retornocli);
     $ret=array( 'token'=>$id_token,
                    'msgerro'=>"Token $id_token não existe!",
                    'coderro'=>'503');    
    }              
  
ob_end_flush();
ob_flush();
fnmemoriafinal($connUser->connUser(),$cod_men);  
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 

// print_r($itn);                                         
return array('retornatoken'=>$ret);
}
?>