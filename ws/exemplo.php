
<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================

 //Registro para parassar os dados pra a função inserir venda
$server->register('EstornaVenda',
			array(
                               'id_vendapdv'=>'xsd:string',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:dadosvenda'),  //output
			'urn:server',   //namespace
			'urn:server#EstornaVenda',  //soapaction
			'rpc', //document
			'literal', // literal
			'InserirVenda');  //description

function EstornaVenda ($id_vendapdv,$dadoslogin) {
 
      // ini_set('display_errors', 1);
      // ini_set('display_startup_errors', 1);
       //error_reporting(E_ALL);
  
     
    include './func/conexao.php'; 
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
   
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
                      
         
    
            
                            return array(
                                            'nome'=>'diogo souza',
                                            'cartao'=>'000000000',
                                            'saldo'=>'2000',
                                            'saldoresgate'=>'200',
                                            'comprovante'=>'20000',
                                            'comprovante_resgate'=>'2000',
                                            'url'=>'dfsdf',
                                            'msgerro'=>'Ah que pena que voce não quiz levar nossos otimos produtos'
                                        );
                

                       
                        
                         
                         
        }else{
            return array('MSG'=>'Erro Na autenticação');

        }   
     
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>

