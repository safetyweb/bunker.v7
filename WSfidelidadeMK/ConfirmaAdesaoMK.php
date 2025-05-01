<?php

//=================================================================== ConfirmaAdesao ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'ConfirmaAdesaoRetorno',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
       
        )
        );
//inserir dados


     
$server->register('ConfirmaAdesao',
			array(
                              'cartao'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:ConfirmaAdesaoRetorno'),  //output
			'urn:server',   //namespace
			'urn:server#ConfirmaAdesao',  //soapaction
			'rpc', //document
			'literal', // literal
			'ConfirmaAdesao');  //description

 function ConfirmaAdesao($cartao,$dadoslogin) {
 
       //ini_set('display_errors', 1);
       //ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
  
     
   include './func/conexao.php'; 
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    //$connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
   // $buscaCPF='SELECT * FROM clientes where NUM_CGCECPF="'.$CPF.'"';
   // $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
   
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
        
                   
                          return array(
                              
                                'msgerro'=>'Aceitou participar com grande virtude'

                         );
                                   
                         
                         
        }else{
            return array('msgerro'=>'Erro Na autenticação');

        }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================

?>
