<?php

//=================================================================== GetURLTktMania ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'GetURLTktManiaRetorno',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'urltktmania' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltktmania', 'type' => 'xsd:string')

        )
        );
//inserir dados


     
$server->register('GetURLTktMania',
			array(
                              'CPFCARTAO'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:GetURLTktManiaRetorno'),  //output
			'urn:server',   //namespace
			'urn:server#GetURLTktMania',  //soapaction
			'rpc', //document
			'literal', // literal
			'GetURLTktMania');  //description

 function GetURLTktMania($CPFCARTAO,$dadoslogin) {
 
       //ini_set('display_errors', 1);
     //  ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
  
     
     include '../_system/Class_conn.php';
     include './func/function.php'; 
   
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    //verifica cod empresa
    if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
        {
          $passou=1;
        } else {
        
        }
    
     //limpa campos cartao/cpf
     $CPFCARTAOLIMPO=fnlimpaCPF($CPFCARTAO); 
      $dadosbase=fn_consultaBase($connUser->connUser(),'','',$CPFCARTAOLIMPO,'','');
       if($dadosbase[0]['COD_CLIENTE']=="")
       {
         return array( 'msgerro'=> 'Cliente não cadastrado!' );
         exit();
         
       }    
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']) )
        {
          //memoria
          fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'GetUTLTktMania');
  
           if($row['LOG_ATIVO']!='S')
            {
                return array( 'msgerro'=> 'A empresa foi desabilitada!' );   
                exit();
            }else{
            
            }     
                if($passou!=1)
                {    
                            //busca cliente por cpf
                            $buscaCPF='SELECT * FROM clientes where NUM_CARTAO="'.$CPFCARTAOLIMPO.'"';
                            $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
                            $id=fnEncode($row1['COD_EMPRESA'].';'.$CPFCARTAOLIMPO);
                            $msg='bem vindo ao tktmania ';
                            //fim da busca

//memoria log
fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                                          return array(

                                                'msgerro'=> $msg,
                                                'urltktmania'=>'http://ticket.fidelidade.mk/?tkt='.$id 

                                         );
                                      
                } else {
                    return array( 'msgerro'=>'idcliente não confere com o cadastrado!');
                }                          
        }else{
            return array('msgerro'=>'Erro Na autenticação');

        }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
