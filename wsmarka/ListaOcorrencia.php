<?php
$server->wsdl->addComplexType(
    'ocorrencias',
    'complexType',
    'struct',
    'sequence',
    '',
        array('nomeocorrencia' => array( 'minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nomeocorrencia', 'type' => 'xsd:string'),
              'codocorrencia' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codocorrencia', 'type' => 'xsd:string'),
              'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
            )
);
$server->wsdl->addComplexType(
    'listaocorrencias1',
    'complexType',
    'struct',
    'sequence',
    '',
        array( 'ocorrencias' =>  array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'ocorrencias', 'type' => 'tns:ocorrencias')
              
            )
); 


$server->register('ListaOcorrencia',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('listaocorrencias' => 'tns:listaocorrencias1'),  //output
			 $ns,         						// namespace
                        "$ns/ListaOcorrencia",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ListaOcorrencia'         		// documentation
                    );
function ListaOcorrencia($dadosLogin) {
    
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  
     //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' Loja desabilidata',$row['LOG_WS']); 
           return  array('listaocorrencias'=>array('msgerro'=>'LOJA DESABILITADA',
                                                           'coderro'=>'80'));
           exit();   
        }  
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
             return array('listaocorrencias'=>array('ocorrencias'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                    'coderro'=>'3')));
             exit();
        }
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']); 
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {}else{
      
            return array('listaocorrencias'=>array('ocorrencias'=>array('msgerro'=>'Erro Na autenticação!',
                                                   'coderro'=>'3' )));
            exit();
    }
    
        if($row['LOG_ATIVO']=='S')
        {}else{
            
            return array('listaocorrencias'=>array('ocorrencias'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')));
            exit();
        }    
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('listaocorrencias'=>array('ocorrencias'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!')));
           exit();
        }
    //////////////////////=================================================================================================================
        
            
            
            
            
                   $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Lista Profissoes',$row['COD_EMPRESA']);
                     
                    $sqlProfi="select * from  ocorrenciamarka
                                    left join tipoocorrenciamarka on ocorrenciamarka.COD_TIPOCOR=tipoocorrenciamarka.COD_TIPOCOR
                                 WHERE tipoocorrenciamarka.COD_TIPOCOR=3   
                                 ";           
                       $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);

                  
                       while ($rsprof = mysqli_fetch_array($prof))
                       { 
                           
                           $itn[]=array('nomeocorrencia'=>$rsprof['DES_OCORREN'],
                                          'codocorrencia'=>$rsprof['COD_OCORREN']);
                        } 
         ob_end_flush();
         ob_flush();
         fnmemoriafinal($connUser->connUser(),$cod_men);  
          mysqli_close($connAdm->connAdm());   
          mysqli_close($connUser->connUser());
                       // print_r($itn);                                         
                      $returnlista= array('listaocorrencias'=>array('ocorrencias'=>$itn));
                      return    $returnlista;     
                     
           
         
    
      
}

