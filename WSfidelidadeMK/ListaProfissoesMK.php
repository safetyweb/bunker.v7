<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================

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
$server->register('ListaProfissoesMK',
			array('dadosLogin'=>'tns:LoginInfo' ),  //parameters
			array('Profissao' => 'tns:ItensProfissao'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#ListaProfissoesMK',  //soapaction
			'rpc', //document
			'literal', // literal
			'Consulta Profissão');  //description



function ListaProfissoesMK($dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
 
   
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
        
        fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'Lista Profissoes');
           $sqlProfi="select * from profissoes";           
           $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);
         
          
            while ($rsprof = mysqli_fetch_assoc($prof))
            {
                
                $item[] = array('descricao' => $rsprof['DES_PROFISS'],'codigo' =>$rsprof['COD_PROFISS'],'msgerro'=>'OK');
             
            } 
            
            return $item;
         fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);        
    }else{
       return array('msgerro'=>'Erro Na autenticação');
    }   
      
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
