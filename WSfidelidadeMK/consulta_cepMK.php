<?php

$server->wsdl->addComplexType(
    'RetornoEndereco',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'endereco' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'endereco', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'bairro', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cep', 'type' => 'xsd:string'),
        
        )
);
$server->register('ConsultaCEPMK',
			array('cep'=>'xsd:string',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:RetornoEndereco'),  //output
			'urn:fidelidade',   //namespace
			'urn:fidelidade#ConsultaCEP',  //soapaction
			'rpc', //document
			'literal', // literal
			'Consulta de endereço');  //description

function ConsultaCEPMK($cep,$dadoslogin) {
    include '../_system/Class_conn.php';
    include './func/function.php';
    include './func/cep.php';
    
    $msg=valida_campo_vazio($cep,'cep','numeric');
    if(!empty($msg)){return array('msgerro' => $msg);}
        
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);  
  
if($row['LOG_ATIVO']=='S')
{    
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
    fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'Consulta Cep');       
     
        //verificar se existe na base de dados
        $conCEP= mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),"select * from cep where CEP=".$cep)); 
        
        if($conCEP['CEP'] !=''){
           
            $endereco = $conCEP['RUA'];
            $bairro = $conCEP['BAIRO'];
            $cidade = $conCEP['CIDADE'];
            $estado = $conCEP['UF'];
            $cep = $conCEP['CEP'];
     
            
        }else{
            $cep=consulta_cep($cep);
            $endereco = $cep['0']['RUA'];
            $bairro = $cep['0']['bairro'];
            $cidade = $cep['0']['cidade'];
            $estado = $cep['0']['uf'];
            $cep = $cep['0']['cep'];
            
         
            $endereco=addslashes($endereco);
            $cepinsert= "insert into cep (CEP,RUA,CIDADE,UF,BAIRRO)value($cep,'".$endereco."','$cidade','$estado','$bairro')";
            mysqli_query($connAdm->connAdm(),$cepinsert);
          
        }
        fnmemoria($connUser->connUser(),'false',$dadoslogin['login']); 
        return array(
            'endereco' => $endereco,
            'bairro' => $bairro,
            'cidade' => $cidade,
            'estado' => $estado,
            'cep' => $cep,
           
             );   
  
        
        
        
    }else{
        return array('msgerro'=>'Erro Na autenticação');
    } 
}else{
  return array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
}    
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
         
     
}