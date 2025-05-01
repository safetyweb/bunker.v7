<?php

$server->wsdl->addComplexType(
    'item',
    'complexType',
    'struct',
    'sequence',
    '',
        array('nomecampo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomecampo', 'type' => 'xsd:string'),
              'tipocampo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'tipocampo', 'type' => 'xsd:string'),
               'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
            )  
);

$server->wsdl->addComplexType(
    'CampoObrigatorioreturn',
    'complexType',
    'struct',
    'sequence',
    '',
      array('item' => array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'item', 'type' => 'tns:item'),
            'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'))
     
);
//'minOccurs'=>'0', 'maxOccurs'=>'unbounded',
$server->register('CampoObrigatorio',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('listaCampo' => 'tns:CampoObrigatorioreturn'),  //output
			 $ns1,         						// namespace
                        "$ns1/ListaOcorrencia",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ListaOcorrencia'         		// documentation
                    );
function CampoObrigatorio($dadosLogin) {
    
     include_once '../_system/Class_conn.php';
     include_once './func/function.php'; 
     ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  //return array('listaCampo'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
   //                                                 'coderro'=>'3'));
   //          exit();
   // return array('listaCampo'=>array('item'=>array('msgerro'=>'Id_cliente não confere com o cadastro!')));
    //        exit();
     //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' Loja desabilidata',$row['LOG_WS']); 
           return  array('listaCampo'=>array('msgerro'=>'LOJA DESABILITADA',
                                           'coderro'=>'80'));
           exit();   
        }  
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
           {}else{

                   return array('listaCampo'=>array('msgerro'=>'Erro Na autenticação!',
                                                          'coderro'=>'3' ));
                   exit();
           }
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
             return array('listaCampo'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                    'coderro'=>'3'));
             exit();
        }
             //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('listaCampo'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!'));
           exit();
        }
    //////////////////////=================================================================================================================
    
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
  
    
        if($row['LOG_ATIVO']=='S')
        {}else{
            
            return array('listaCampo'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',
                                              'coderro'=>'43'));
            exit();
        }    
            
            
       
            
                    //$cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Lista Profissoes',$row['COD_EMPRESA']);
                      
                       $sqlProfi=" select KEY_CAMPOOBG,DES_CAMPOOBG,INTEGRA_CAMPOOBG.TIP_CAMPOOBG  from matriz_campo_integracao                         
                                    inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
                                    where matriz_campo_integracao.COD_EMPRESA=".$row['COD_EMPRESA']."
                                    and matriz_campo_integracao.TIP_CAMPOOBG ='OBG' AND KEY_CAMPOOBG != 'tokencadastro'";
                       
                       $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);
                       while ($rsprof = mysqli_fetch_array($prof))
                       { 
                           
                           $itn[]=array('nomecampo'=>$rsprof['KEY_CAMPOOBG'],
                                        'tipocampo'=>$rsprof['TIP_CAMPOOBG']);
                        } 
                      
                     
ob_end_flush();
ob_flush();
//fnmemoriafinal($connUser->connUser(),$cod_men);  
                       // print_r($itn);                                         
                     return array('listaCampo'=>array('item'=>$itn));
                     //return array('listaCampo'=>array('item'=>array('nomecampo'=>'teste')));                          
                       
                     
           
         
     
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
}

