<?php

//=================================================================== GetURLTktMania ====================================================================
//retorno dados
/*$server->wsdl->addComplexType(
    'GetURLTktManiaRetorno',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'urltktmania' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'urltktmania', 'type' => 'xsd:string')

        )
        );
//retorno dados

//inserir dados
//4419

     
$server->register('GetURLTktMania',
			array(
                              'CPFCARTAO'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('GetURLTktManiaResult' => 'tns:GetURLTktManiaRetorno'),  //output
                    $ns,         						// namespace
                    "$ns/GetURLTktMania",     						// soapaction
                    'document',                         // style
                    'literal',                          // use
                    'GetURLTktMania'  );  //description

 function GetURLTktMania($CPFCARTAO,$dadosLogin) {
 
       //ini_set('display_errors', 1);
     //  ini_set('display_startup_errors', 1);
      // error_reporting(E_ALL);
  
     
     include '../_system/Class_conn.php';
     include './func/function.php'; 
   
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    //verifica cod empresa
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
          $passou=1;
        } else {
        
        }
    
     //limpa campos cartao/cpf
     $CPFCARTAOLIMPO=fnlimpaCPF($CPFCARTAO); 
      $dadosbase=fn_consultaBase($connUser->connUser(),'','',$CPFCARTAOLIMPO,'','',$row['COD_EMPRESA']);
       if($dadosbase[0]['COD_CLIENTE']=="")
       {
         return array('GetURLTktManiaResult'=> array( 'msgerro'=> 'Cliente não cadastrado!' ));
         exit();
         
       }    
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']) )
        {
          //memoria
          fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'GetUTLTktMania',$row['COD_EMPRESA']);
  
           if($row['LOG_ATIVO']!='S')
            {
                return array('GetURLTktManiaResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
                exit();
            }else{
            
            }     
                if($passou!=1)
                {    
                         
                            $cod_univend=fnConsultaLojaGET($connAdm->connAdm(),$dadosLogin['idloja']);
                            
                           // print_r($cod_univend);
                            //busca cliente por cpf
                            $buscaCPF='SELECT * FROM clientes where NUM_CARTAO="'.$CPFCARTAOLIMPO.'"';
                            $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
                            $id=fnEncode($row['COD_EMPRESA'].';'.$CPFCARTAOLIMPO.';'.$cod_univend[0]['COD_UNIVEND']);
                            $msg='bem vindo ao tktmania ';
                            //fim da busca

//memoria log
fnmemoria($connUser->connUser(),'false',$dadosLogin['login']);
                                          return array('GetURLTktManiaResult'=>array(
                                                                        'msgerro'=> $msg ,
                                                                        'urltktmania'=>'http://ticket.fidelidade.mk/?tkt='.$id 
                                                                      //  'urltktmania'=>'http://fidelidade.mk/?tkt='.$id     

                                            ));
                                      
                } else {
                    return array('GetURLTktManiaResult'=>array( 'msgerro'=>'idcliente não confere com o cadastrado!'));
                }                          
        }else{
            return array('GetURLTktManiaResult'=>array('msgerro'=>'Erro Na autenticação'));

        }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================
*/
?>
