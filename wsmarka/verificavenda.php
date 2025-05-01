<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea



$server->wsdl->addComplexType(
            'retornovenda',
            'complexType',
            'struct',
            'sequence',
            '',
             array(
                 'retornovenda' =>  array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'verificavenda', 'type' => 'tns:verificavenda')
                  
                 )
  );

               
$server->wsdl->addComplexType(
    'verificavenda',
    'complexType',
    'struct',
    'sequence',
    '',
        array('vendainserida' => array( 'minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'vendainserida', 'type' => 'xsd:string'),
              'id_venda' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'id_venda', 'type' => 'xsd:string'),
              'datahora' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'datahora', 'type' => 'xsd:string'),
              'dataservidor' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'dataservidor', 'type' => 'xsd:string'),
              'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
              'cod_status' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cod_status', 'type' => 'xsd:string'),
              'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
              'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
            )
); 
$server->register('VerificaVenda',
                       array('id_venda'=>'xsd:string',
                             'dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('VerificaVendaResult' => 'tns:retornovenda'),  //output
			 $ns,         						// namespace
                        "$ns/verificavenda",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'verificavenda'         		// documentation
                    );
function VerificaVenda ($id_venda,$dadosLogin) {
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
        
          $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Verificavenda',$row['COD_EMPRESA']);
          
              //verifica id_empresa
              if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
                {
                     fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Id_cliente não confere com o cadastro!',$row['LOG_WS']); 
                      return array('VerificaVendaResult'=>array('retornovenda'=>
                                                                 array('msgerro'=>'Id_cliente não confere com o cadastro!')));
                       exit();
                }

                //VERIFICA SE O USUARIO FOI DESABILITADA
                if($row['LOG_ESTATUS']=='N'){
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
                   return  array('VerificaVendaResult'=>array('retornovenda'=>
                                                            array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!')));
                   exit();
                }
            //////////////////////=================================================================================================================
               //empresa desabilidatada

                if($row['LOG_ATIVO']!='S')
                { 
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',$row['LOG_WS']); 
                     return array('VerificaVendaResult'=>array('retornovenda'=>
                                                             array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')));
                    exit();
                }

        }else{
                   fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Erro Na autenticação!',$row['LOG_WS']); 
                    return array('VerificaVendaResult'=>array('retornovenda'=>
                                                         array('msgerro'=>'Erro Na autenticação!')));
        }
 
   $verificavenda="select 
                (select num_cartao from clientes where clientes.cod_cliente=vendas.COD_CLIENTE) as num_cartao,
                 vendas.* from vendas where COD_VENDAPDV='$id_venda' and cod_empresa=".$row['COD_EMPRESA'];
   $vendarw=mysqli_query($connUser->connUser(),$verificavenda);
   //cod estatus cred

       if (!$vendarw)
       {
                fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','verifique a conexão com a internet',$row['LOG_WS']); 
                return array('VerificaVendaResult'=>array('retornovenda'=>
                                                     array('msgerro'=>'verifique a conexão com a internet')));
           
       }else{
        $dadosre=mysqli_fetch_assoc($vendarw);
        if($dadosre['COD_VENDAPDV']!= "")
        {
            //status credito
            $statuscred='select * from statuscredito where COD_STATUSCRED='.$dadosre['COD_STATUSCRED'];
            $sttuscredrs=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $statuscred));
           
          fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','ok',$row['LOG_WS']); 
                $ret=array( 'vendainserida'=>'',
                            'id_venda'=>$dadosre['COD_VENDAPDV'],
                            'datahora'=>$dadosre['DAT_CADASTR_WS'],
                            'dataservidor'=>$dadosre['DAT_CADASTR'],
                            'cartao'=>$dadosre['num_cartao'],
                            'cod_status'=>$sttuscredrs['COD_STATUSCRED'],
                            'descricao'=>$sttuscredrs['DES_STATUSCRED'], 
                            'msgerro'=>'OK'
                                            );
                
       }else{
           
               fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'verificavenda','Não foi localizda vendas para o id_pdv',$row['LOG_WS']); 
                $ret=array('msgerro'=>'Não encontramos sua venda!',
                           'cod_status'=>'500' );
          
           
       }
   }          
        
        
        
    




ob_end_flush();
ob_flush();
fnmemoriafinal($connUser->connUser(),$cod_men);   
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 

// print_r($itn);                                         
return array('VerificaVendaResult'=>array('retornovenda'=>$ret));
}
?>