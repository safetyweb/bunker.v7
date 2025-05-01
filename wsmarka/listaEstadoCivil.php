<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea



$server->wsdl->addComplexType(
            'lista',
            'complexType',
            'struct',
            'sequence',
            '',
             array(
                 'EstadoCivil' =>  array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'EstadoCivil', 'type' => 'tns:EstadoCivil')
                  
                 )
  );

               
$server->wsdl->addComplexType(
    'EstadoCivil',
    'complexType',
    'struct',
    'all',
    '',
        array('descricao' => array( 'minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
              'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigo', 'type' => 'xsd:integer'),
              'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
            )
);

  
$server->register('listaEstadoCivil',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('EstadoCivil' => 'tns:lista'),  //output
			 $ns,         						// namespace
                        "$ns/listaEstadoCivil",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'listaEstadoCivil'         		// documentation
                    );
function listaEstadoCivil($dadosLogin) {
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
           return   array('EstadoCivil'=>array('EstadoCivil'=>array('msgerro'=>'LOJA DESABILITADA')));
           exit();   
        }            
    
 
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else { }
        
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);    
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
    $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'listaEstadoCivil',$row['COD_EMPRESA']);
       
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'listaEstadoCivil',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('EstadoCivil'=>array('EstadoCivil'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!')));
           exit();
        }
    //////////////////////=================================================================================================================
    
        if($row['LOG_ATIVO']=='S')
        { 
            if( $passou!=1)
            {
                       $sqlProfi="SELECT *  from estadocivil";           
                       $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);

                  
                       while ($rsprof = mysqli_fetch_array($prof))
                       { 
                           
                     //      $d['Profissao']=array('descricao'=>$rsprof['DES_PROFISS'],'codigo'=>$rsprof['COD_PROFISS']);
                       // print_r($d);
                            
                            $itn[]=array('descricao'=>$rsprof['DES_ESTACIV'],
                             'codigo'=>$rsprof['COD_ESTACIV']);
                        } 
                         fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'listaEstadoCivil','ok',$row['LOG_WS']); 
          
                        ob_end_flush();
                        ob_flush();
                      fnmemoriafinal($connUser->connUser(),$cod_men); 
                       // print_r($itn);                                         
                      return array('EstadoCivil'=>array('EstadoCivil'=>$itn)
                                              );
                       
                     
            }      
            else{
                
                return array('EstadoCivil'=>array('EstadoCivil'=>array('msgerro'=>'Id_cliente não confere com o cadastro!')));
            }
        }else{
            
            return array('EstadoCivil'=>array('EstadoCivil'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[')));
        }    
    }else{
      
            return array('EstadoCivil'=>array('EstadoCivil'=>array('msgerro'=>'Erro Na autenticação!')));
       
    } 
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
