<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea



$server->wsdl->addComplexType(
            'profissoes',
            'complexType',
            'struct',
            'sequence',
            '',
             array(
                 'profissao' =>  array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'profissao', 'type' => 'tns:profissao')
                  
                 )
  );

               
$server->wsdl->addComplexType(
    'profissao',
    'complexType',
    'struct',
    'all',
    '',
        array('descricao' => array( 'minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'descricao', 'type' => 'xsd:string'),
              'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'codigo', 'type' => 'xsd:string'),
              'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
            )
);

  
$server->register('ListaProfissoes',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('profissoes' => 'tns:profissoes'),  //output
			 $ns,         						// namespace
                        "$ns/ListaProfissoes",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ListaProfissoes'         		// documentation
                    );
function ListaProfissoes($dadosLogin) {
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
           return  array('profissoes'=>array('profissao'=>array('msgerro'=>'LOJA DESABILITADA',
                                                           'coderro'=>'80')));
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
                 //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
           fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',' A empresa foi desabilitada por algum motivo',$row['LOG_WS']); 
           return  array('profissoes'=>array('profissao'=>array('msgerro'=>'Oh não! Usuario foi desabilitado ;-[!',
                                                                  'coderro'=>'44')));
           exit();
        }
    //////////////////////=================================================================================================================
    
        if($row['LOG_ATIVO']=='S')
        { 
            if( $passou!=1)
            {
                    $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Lista Profissoes',$row['COD_EMPRESA']);
                    


                       $sqlProfi="select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa='".$row['COD_EMPRESA']."'  order by DES_PROFISS;";           
                       $prof=mysqli_query($connUser->connUser(),$sqlProfi);
                       if(mysqli_num_rows($prof)<=0){
                        $sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
                        $prof = mysqli_query($connAdm->connAdm(),$sql);
                            
                        }

                         while ($rsprof = mysqli_fetch_array($prof))
                            { 

                                 $itn[]=array('descricao'=>$rsprof['DES_PROFISS'],
                                              'codigo'=>$rsprof['COD_PROFISS']
                                             );
                             }   
                        mysqli_free_result($prof);
                        
                        ob_end_flush();
                        ob_flush();
                       fnmemoriafinal($connUser->connUser(),$cod_men); 
                       return array('profissoes'=>
                                                 array('profissao'=>$itn)
                                    );
                       
                     
            }      
            else{
                
                return array('profissoes'=>array('profissao'=>array('msgerro'=>'Id_cliente não confere com o cadastro!',
                                                                    'coderro'=>'4')));
            }
        }else{
            
            return array('profissoes'=>array('profissao'=>array('msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[',
                                                                'coderro'=>'43')));
        }    
    }else{
      
            return array('profissoes'=>array('profissao'=>array('msgerro'=>'Erro Na autenticação!',
                                                                'coderro'=>'48')));
       
    } 
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
