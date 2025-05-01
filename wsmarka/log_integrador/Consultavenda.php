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
    
$gravalog= array('consultaVenda'=> array('dadoslogin'=> array(
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

    @$login=$dadosenvio['consultaVenda']['dadoslogin']['login'];
    @$senha=$dadosenvio['consultaVenda']['dadoslogin']['senha'];
    @$cod_empresa=$dadosenvio['consultaVenda']['dadoslogin']['idcliente'];
    @$idloja=$dadosenvio['consultaVenda']['dadoslogin']['idloja'];
    @$idmaquina=$dadosenvio['consultaVenda']['dadoslogin']['idmaquina'];
    @$codvendedor=$dadosenvio['consultaVenda']['dadoslogin']['codvendedor'];
    @$nomevendedor=$dadosenvio['consultaVenda']['dadoslogin']['nomevendedor'];    
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
        $sqlvenda="SELECT v.DAT_CADASTR_WS,
                            v.COD_CUPOM,
                            v.COD_VENDAPDV,
                            c.NUM_CGCECPF,
                            c.NOM_CLIENTE 
                    FROM vendas v
        inner  JOIN  clientes c ON c.cod_cliente=v.COD_CLIENTE
        WHERE v.COD_UNIVEND='96796'  
          AND v.DAT_CADASTR_WS IN  (select MAX(vas.DAT_CADASTR_WS) FROM vendas vas WHERE vas.COD_UNIVEND=v.COD_UNIVEND and vas.cod_empresa=v.cod_empresa)
        AND v.cod_empresa='45';";
        $resultdados=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlvenda));
        
     
         echo  json_encode(array('ConsultaVendaResponse'=>array('data_venda'=>$resultdados['DAT_CADASTR_WS'],
                                                                'cupom'=>$resultdados['COD_CUPOM'],
                                                                'id_vendapdv'=>$resultdados['COD_VENDAPDV'],
                                                                'cartao'=>$resultdados['NUM_CGCECPF'],
                                                                'nome'=>$resultdados['NOM_CLIENTE'],
                                                                'coderro'=>'6')));
       exit();
    }