<?php
//função que captura os dados da pagina "soap"
//=================================================================== ConsultaCadastroPorCPF ====================================================================
$server->wsdl->addComplexType(
    'ConsultaFidelizados',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'clientedesde', 'type' => 'xsd:string'),
        'status' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'status', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
         
        )
);


$server->register('clienteFidelizado',                // method name
                        array('CPF' => 'xsd:string',
                             'dadosLogin'=>'tns:LoginInfo'  
                             ),    // input parameters
                        array('ConsultaFidelizadosResult' => 'tns:ConsultaFidelizados'),    // output parameters
                        $ns,         						// namespace
                        "$ns/ConsultaFidelizados",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'ConsultaFidelizados'         		// documentation
                    );

 function clienteFidelizado($CPF,$dadosLogin) {

     include '../_system/Class_conn.php';
     include './func/function.php'; 
     $CPF=fnlimpaCPF($CPF);
      ///===================log
    $msg=valida_campo_vazio($dadosLogin['login'],'login','');
    if(!empty($msg)){return  array('ConsultaFidelizadosResult'=>array('msgerro' => $msg));}
    $msg=valida_campo_vazio($dadosLogin['senha'],'senha','');
    if(!empty($msg)){ return  array('ConsultaFidelizadosResult'=>array('msgerro' => $msg));} 
    $msg3=valida_campo_vazio($CPF,'CPF','numeric');
    if(!empty($msg3)){ return  array('ConsultaFidelizadosResult'=> array('msgerro' => $msg3));}
    //================================================================================================
    
    
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
    
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
       return  array('ConsultaFidelizadosResult'=> array('msgerro'=>'Erro Na autenticação')); 
    }
    
    //verifica se o codigo da empresa esta cadastrado.
    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
    {
     return  array('ConsultaFidelizadosResult'=> array('msgerro'=>'Id_cliente não confere com o cadastro!'));
    } 
    if($row['LOG_ATIVO']!='S')
    {  
        return array('ConsultaFidelizadosResult'=> array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
    } 
    //==================================================================================]
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
    $arraydados1= array('CONN'=>$connUser->connUser(),
                       'DATA_HORA'=>date("Y-m-d H:i:s"),
                       'IP'=>$_SERVER['REMOTE_ADDR'],
                       'PORT'=>$_SERVER['REMOTE_PORT'],
                       'COD_USUARIO'=>$row['COD_USUARIO'],
                       'LOGIN'=>$dadosLogin['login'],
                       'COD_EMPRESA'=>$row['COD_EMPRESA'],
                       'IDLOJA'=>$dadosLogin['idloja'],
                       'IDMAQUINA'=>$dadosLogin['idmaquina'],
                       'CPF'=>$CPF,
                       'XML'=>file_get_contents("php://input")
                      );
    $cod_xml=fngravaxml($arraydados1);
  
        
        if (valida_cpf($CPF)) {
            
        }else{
            Grava_log_fidelizados($connUser->connUser(),$cod_xml,'CPF digitado não é valido!');
            return array('ConsultaFidelizadosResult'=> array('msgerro' => ';-O Oh não! CPF digitado não é valido!'));
        }
//valida campo
$cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'clienteFidelizado',$row['COD_EMPRESA']);


$buscacliente="select NUM_CGCECPF,NUM_CARTAO,DAT_CADASTR,LOG_ESTATUS from clientes  where NUM_CGCECPF='$CPF' and cod_empresa=".$dadosLogin['idcliente'];
$resultbusca=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $buscacliente));

if($resultbusca['NUM_CGCECPF']!='')
{
 $return='TRUE'; 
 $cartao=$resultbusca['NUM_CARTAO'];
 $dt_cadastr=$resultbusca['DAT_CADASTR'];
 $status=$resultbusca['LOG_ESTATUS'];
 Grava_log_fidelizados($connUser->connUser(),$cod_xml,$return);
            
}else{
 $return='FALSE';    
 $cartao='FALSE';
 $dt_cadastr='FALSE';
 $status='FALSE';
 Grava_log_fidelizados($connUser->connUser(),$cod_xml,$return);
}   

fnmemoriafinal($connUser->connUser(),$cod_men);
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 
return array('ConsultaFidelizadosResult'=> array('msgerro' => $return,
                                                 'cartao'=>$cartao,
                                                 'clientedesde'=>$dt_cadastr,
                                                 'status'=> $status      
                                                 )
            );


     
}
//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================


?>
