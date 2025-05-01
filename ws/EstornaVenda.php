<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
$server->wsdl->addComplexType(
    'Estornovendareturn',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:int'),
        'saldo' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'saldoresgate', 'type' => 'xsd:string'),
        'comprovante' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'comprovante', 'type' => 'xsd:string'),
        'comprovante_resgate' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'comprovante_resgate', 'type' => 'xsd:string'),
        'url' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'url', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'msgerro', 'type' => 'xsd:string')
    )
);
 //Registro para parassar os dados pra a função inserir venda
$server->register('EstornaVenda',
			array(
                               'id_vendapdv'=>'xsd:string',
                               'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('return' => 'tns:Estornovendareturn'),  //output
			'urn:server',   //namespace
			'urn:server#EstornaVenda',  //soapaction
			'rpc', //document
			'literal', // literal
			'Estorno de venda');  //description

function EstornaVenda ($id_vendapdv,$dadoslogin) {
 
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadoslogin['login']."', '".fnEncode($dadoslogin['senha'])."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
 
    
    
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);

    $msg=valida_campo_vazio($id_vendapdv['id_vendapdv'],'id_vendapdv','string');
    if(!empty($msg)){return array('msgerro' => $msg);}
       //Empresa desabilitada
        if($row['LOG_ATIVO']=='N')
        {
             return array('msgerro' => 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[');  
             exit();
            
        }
        //VERIFICA COD EMPRESA
        if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
        {
           return array('msgerro' => 'Id_cliente não confere com o cadastro');  
           exit();
          //$passou=1;
        } 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
       
            
        
            //memoria
            fnmemoria($connUser->connUser(),'true',$dadoslogin['login'],'EstornaVEnda',$row['COD_EMPRESA']);  
            //compara os id_cliente com o cod_empresa
            if ($row['COD_EMPRESA'] != $dadoslogin['idcliente'])
            {
               return array('msgerro' => 'Id_cliente não confere com o cadastro');  
               exit();
              //$passou=1;
            } 
                    //VENDA WS   
                   $SQLVENDA_WS = "CALL SP_ESTORNA_VENDA_WS('".$row['COD_EMPRESA']."', '".$row['COD_USUARIO']."', '".$id_vendapdv."')" ;
                   $VENDA_WS=mysqli_query($connUser->connUser(),$SQLVENDA_WS);
                   $row_estornaV=mysqli_fetch_assoc($VENDA_WS); 
 
                    if($row_estornaV['msgerro']=='OK')
                    {   
                        //consulta saldo cliente
                        $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$row_estornaV['v_COD_CLIENT'].')';
                        $rowprocsaldo=mysqli_query($connUser->connUser(),$procsaldo);
                        $rowSALDO_CLIENTE = mysqli_fetch_assoc($rowprocsaldo);

                        //saldo cliente
                        $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']);
                        $saldoresgate=fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL']);

                        //busca cliente 
                        $sql2="SELECT * FROM clientes where COD_CLIENTE=".$row_estornaV['v_COD_CLIENT']; 
                        $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$sql2));  

                        $msg=$row_estornaV['msgerro'];


                         $comprovante='CLIENTE: '.$row1['NOM_CLIENTE'].'
                                     Cartão: '.$row1['NUM_CARTAO'].'
                                     DATA: '.date("Y-m-d H:i:s").'
                                     SALDO ACULUMADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO']).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 


                    }
                    else
                    {
                     $msg=$row_estornaV['msgerro'];    
                    }    
              
//memoria log
fnmemoria($connUser->connUser(),'false',$dadoslogin['login']);        
                            return array(
                                            'nome'=>$row1['NOM_CLIENTE'],
                                            'cartao'=>$row1['NUM_CARTAO'],
                                            'saldo'=>$saldo,
                                            'saldoresgate'=>$saldoresgate,
                                            'comprovante'=>$comprovante,
                                            'comprovante_resgate'=> '',
                                            'url'=>'',
                                            'msgerro'=>$msg
                                        );
                

                       
                        
                         
                         
        }else{
            return array('msgerro'=>'Erro Na autenticação');

        }   
     
      mysqli_close($connAdm->connAdm());   
      mysqli_close($connUser->connUser()); 
     
}

//=================================================================== Fim InserirVenda =================================================================================

?>
