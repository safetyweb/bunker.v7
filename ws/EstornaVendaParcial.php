<?php
//=================================================================== GetURLTktMania ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'EstornaVendaParcialResult',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
        'saldo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldo', 'type' => 'xsd:string'),
        'comprovante' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'comprovante', 'type' => 'xsd:string'),
        'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')

        )
);

$server->wsdl->addComplexType(
    'EstornoItem',
    'complexType',
    'struct',
    'sequence',
    '',
         array('id_item' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_item', 'type' => 'xsd:string'),
               'codigoproduto' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigoproduto', 'type' => 'xsd:string'),
               'quantidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'quantidade', 'type' => 'xsd:string')
              
             )
);
$server->wsdl->addComplexType(
    'Itemsarray',
    'complexType',
    'struct',
    'sequence',
    '',
         array('EstornoItem' =>array('name' => 'EstornoItem', 'type' => 'tns:EstornoItem'))
);



$server->wsdl->addComplexType(
    'EstornoArray',
    'complexType',
    'struct',
    'sequence',
    '',
         array('id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_vendapdv', 'type' => 'xsd:string'),
               'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'cartao', 'type' => 'xsd:string'),
               'items' =>array('name' => 'Itemsarray', 'type' => 'tns:Itemsarray')
                )
);



 $server->register('EstornaVendaParcial',
			array(
                              'Estorno'=>'tns:EstornoArray',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:EstornaVendaParcialResult'),  //output
			'urn:server',   //namespace
			'urn:server#EstornaVendaParcial',  //soapaction
			'rpc', //document
			'literal', // literal
			'EstornaVendaParcial');  //description

function EstornaVendaParcial($Estorno,$dadoslogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
     //limpa campos cartao/cpf
     $CPFCARTAOLIMPO=fnlimpaCPF($Estorno['cartao']); 
     
    
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            //memoria
            fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'EstornaVendaParcial',$row['COD_EMPRESA']);
    
           //carrega dados do cliente
              $dadosbase=fn_consultaBase($connUser->connUser(),'','',trim($CPFCARTAOLIMPO),'','',$row['COD_EMPRESA']);   
              
           //verifica se a empresa ta ativa  
           if($row['LOG_ATIVO']!='S')
            {
                return array( 'msgerro'=> 'A empresa foi desabilitada!' );   
                exit();
            } 
            //verifica cod empresa
            if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
            {
                $passou=1;
            }

                if($passou!=1)
                {  
                               
                    
                    //loop de excluir venda
                    if (count($Estorno['items']['EstornoItem']['id_item'])==1){ 
                    
                            $cad_venda = "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                                 '".$Estorno['id_vendapdv']."',
                                                                 '".$Estorno['items']['EstornoItem']['id_item']."',   
                                                                 '".$Estorno['items']['EstornoItem']['quantidade']."', 
                                                                 '".$row['COD_USUARIO']."',
                                                                 'EXC'     
                                                               );"; 
                            
                    }
                    else
                    {    
                      for($i=0;$i < count($Estorno['items']['EstornoItem']);$i++){
                          
                          $cad_venda.= "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                                 '".$Estorno['id_vendapdv']."',
                                                                 '".$Estorno['items']['EstornoItem'][$i]['id_item']."',   
                                                                 '".$Estorno['items']['EstornoItem'][$i]['quantidade']."', 
                                                                 '".$row['COD_USUARIO']."',
                                                                 'EXC'     
                                                               );"; 
                          //Executa query
                          // mysqli_query($connUser->connUser(),$cad_venda);
                      }                               
                        
                        
                    }    
                      //Executa query
                        mysqli_query($connUser->connUser(),$cad_venda);
                    
                    
                   //retorna saldo 
                    $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$dadosbase[0]['COD_CLIENTE'].')';
                    $SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
                    $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                    
                     $comprovante='CLIENTE: '.$dadosbase[0]['nome'].'
                                    Cartão: '.$Estorno['cartao'].'
                                    DATA: '.date("Y-m-d H:i:s").'
                                    SALDO ACULUMADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 
                     //memoria log
                     fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);
                     return array(
                                'nome'=> $dadosbase[0]['nome'],
                                'cartao'=>$Estorno['cartao'],
                                'saldo'=>fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']),
                                'comprovante'=>$comprovante,
                                'url'=> $cad_venda,
                                'msgerro'=> 'OK'
                                );
                }else {  return array( 'msgerro'=>'idcliente não confere com o cadastrado!');}                          
        }else {  return array( 'msgerro'=>'Erro no usuario ou senha!'); }
        
        
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser());         
}     
