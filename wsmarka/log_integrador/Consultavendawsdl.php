<?php
$server->wsdl->addComplexType(
    'returnvenda',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'data_venda' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'data_venda', 'type' => 'xsd:string'),
        'cupom' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cupom', 'type' => 'xsd:string'),
        'id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'id_vendapdv', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'coderro', 'type' => 'xsd:string')
        
        )
);
        
$server->register('Consultavenda',
			array(
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('VendaResponse' => 'tns:returnvenda'),  //output
			 $ns,         						// namespace
                        "$ns/Consultavenda",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'Consultavenda'         		// documentation
                    );


function Consultavenda($dadosLogin) {
     include '../../_system/Class_conn.php';
     include '../func/function.php'; 
     ob_start();
   
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
     $buscauser=mysqli_query($connAdm->connAdm(),$sql);
     $row = mysqli_fetch_assoc($buscauser);
     if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
    {
        return array('VendaResponse'=>array('msgerro'=>'Verifique os dados de dadoslogin!',
                                                            'coderro'=>'5'));
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
        
     
         return array('VendaResponse'=>array('data_venda'=>$resultdados['DAT_CADASTR_WS'],
                                                                'cupom'=>$resultdados['COD_CUPOM'],
                                                                'id_vendapdv'=>$resultdados['COD_VENDAPDV'],
                                                                'cartao'=>$resultdados['NUM_CGCECPF'],
                                                                'nome'=>$resultdados['NOM_CLIENTE'],
                                                                'msgerro'=>'OK',
                                                                'coderro'=>'6'));
    }
     
}

/*
 * echo  json_encode(array('ConsultaVendaResponse'=>array('data_venda'=>$resultdados['DAT_CADASTR_WS'],
                                                                'cupom'=>$resultdados['COD_CUPOM'],
                                                                'id_vendapdv'=>$resultdados['COD_VENDAPDV'],
                                                                'cartao'=>$resultdados['NUM_CGCECPF'],
                                                                'nome'=>$resultdados['NOM_CLIENTE'],
                                                                'coderro'=>'6')));
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
    }*/