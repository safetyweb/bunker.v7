<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include '../../_system/Class_conn.php';
include '../func/function.php';
//dadoslogin
//Metodo de envio 
if( $_SERVER['REQUEST_METHOD']!='POST' )
{   
    
$gravalog= array('GravalogVenda'=>
                                array('Gravalog'=>array(    'id_vendapdv'=>'sdfsdfsdf',
                                                            'cartao'=>'60817616063',
                                                            'cupom'=>'123456', 
                                                            'valortotalbruto'=> '48,68',
                                                            'descontototalvalor'=>'6,88',
                                                            'valortotalliquido'=> '41,80',
                                                            'valor_resgate'=> '0,00',
                                                            'msg'=>'erro no envio da venda'
                                                            ),
                                    'dadoslogin'=> array(
                                                         'login'=> 'teste.teste',
                                                         'senha'=> 'teste',
                                                         'idloja'=> '13',
                                                         'idcliente'=> '7',
                                                         'codvendedor'=> '1234',
                                                         'nomevendedor'=> 'teste',
                                                         'idmaquina'=> 'home'
                               ))
            );



    echo json_encode($gravalog,JSON_PRETTY_PRINT); 
                                            
                           
                   
                     
    exit();   
} 

//===================================================================
//captura o envio
$dadosenvio = json_decode(file_get_contents("php://input"),true);
//======================================================

    @$login=$dadosenvio['GravalogVenda']['dadoslogin']['login'];
    @$senha=$dadosenvio['GravalogVenda']['dadoslogin']['senha'];
    @$cod_empresa=$dadosenvio['GravalogVenda']['dadoslogin']['idcliente'];
    @$idloja=$dadosenvio['GravalogVenda']['dadoslogin']['idloja'];
    @$idmaquina=$dadosenvio['GravalogVenda']['dadoslogin']['idmaquina'];
    @$codvendedor=$dadosenvio['GravalogVenda']['dadoslogin']['codvendedor'];
    @$nomevendedor=$dadosenvio['GravalogVenda']['dadoslogin']['nomevendedor'];    
    @$id_vendapdv=$dadosenvio['GravalogVenda']['Gravalog']['id_vendapdv'];
    @$cartao=$dadosenvio['GravalogVenda']['Gravalog']['cartao'];
    @$cupom=$dadosenvio['GravalogVenda']['Gravalog']['cupom'];
    @$valortotalbruto=$dadosenvio['GravalogVenda']['Gravalog']['valortotalbruto'];
    @$descontototalvalor=$dadosenvio['GravalogVenda']['Gravalog']['descontototalvalor'];
    @$valortotalliquido=$dadosenvio['GravalogVenda']['Gravalog']['valortotalliquido'];
    @$valor_resgate=$dadosenvio['GravalogVenda']['Gravalog']['valor_resgate'];
    @$msg=$dadosenvio['GravalogVenda']['Gravalog']['msg'];
    //autenticação
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($senha)."','','','".$cod_empresa."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //verifica login
    if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
       echo  json_encode(array('GravalogVendaResponse'=>array('msgerro'=>'Verifique os dados de dadoslogin!',
                                                            'coderro'=>'5')));
       exit();
    }else{
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']); 
 
$insertlog="INSERT INTO log_integrador (
                                       id_vendapdv,
                                       cartao,
                                       cupom,
                                       valortotalbruto,
                                       descontototalvalor,
                                       valortotalliquido,
                                       valor_resgate,
                                       msg,
                                       login,
                                       senha,
                                       idloja,
                                       idcliente,
                                       codvendedor,
                                       nomevendedor,
                                       idmaquina)VALUES(
                                       '$id_vendapdv',
                                       '$cartao',
                                       '$cupom',
                                       '$valortotalbruto',
                                       '$descontototalvalor',
                                       '$valortotalliquido',
                                       '$valor_resgate',
                                       '$msg',
                                       '$login',
                                       '$senha',
                                       '$idloja',
                                       '$cod_empresa',
                                       '$codvendedor', 
                                       '$nomevendedor',
                                       '$idmaquina');";
         $teste=mysqli_query($connUser->connUser(), trim(rtrim($insertlog)));
        if (!$teste)
        {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
            try {mysqli_query($connUser->connUser(),$insertlog);}
            
            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
            echo  json_encode(array('GravalogVendaResponse'=>array('msgerro'=>'Problema ao gravar log!',
                                                            'coderro'=>'6')));
            exit();
            
        }
        
         echo  json_encode(array('GravalogVendaResponse'=>array('msgerro'=>"Log regitrado!",
                                                            'coderro'=>'6')));
       exit();
    }