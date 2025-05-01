<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
    'Lista',
    'complexType',
    'struct',
    'sequence',
    '',
        array(
              'descricao' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'descricao', 'type' => 'xsd:string'),
              'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:string'),
              'msgerro'=> array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:string')
             )
);
  $server->wsdl->addComplexType(
            'ItensProfissao',
            'complexType',
            'array',
            '',
            'SOAP-ENC:Array',
            array(),
            array(
              array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType' => 'tns:Lista[]')
            ),
            'tns:Lista'
          );
 //Registro para parassar os dados pra a função inserir venda
$server->register('ListaProfissoes',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('Profissao' => 'tns:ItensProfissao'),  //output
			 $ns,         						// namespace
                        "$ns#ListaProfissoes",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ListaProfissoes'         		// documentation
                    );



function ListaProfissoes($dadosLogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
  
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else { }
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
 
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
        if($row['LOG_ATIVO']=='S')
        { 
            if( $passou!=1)
            {
                    fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'Lista Profissoes',$row['COD_EMPRESA']);
                       $sqlProfi="select * from profissoes";           
                       $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);


                        while ($rsprof = mysqli_fetch_assoc($prof))
                        {

                            $item[] =array('descricao' => $rsprof['DES_PROFISS'],'codigo' =>$rsprof['COD_PROFISS'],'msgerro'=>'OK');

                        } 

                        return array('Profissao'=>$item);
                     fnmemoria($connUser->connUser(),'false',$dadosLogin['login']);  
            }      
            else{
                $item[] = array('descricao' =>'' ,'codigo' =>'','msgerro'=>'Id_cliente não confere com o cadastro!');    
                return array('Profissao'=>$item);
            }
        }else{
            $item[] = array('descricao' =>'' ,'codigo' =>'','msgerro'=>'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');    
            return array('Profissao'=>$item);
        }    
    }else{
        $item[] = array('descricao' =>'' ,'codigo' =>'','msgerro'=>'Erro Na autenticação!');    
            return array('Profissao'=>$item);
       
    }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
