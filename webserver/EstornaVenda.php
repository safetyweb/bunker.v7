<?php
//inserir venda
//=================================================================== EstornaVenda ==================================================================================
$server->wsdl->addComplexType(
    'EstornaVendaResult',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1','name' => 'cartao', 'type' => 'xsd:string'),
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
			array('EstornaVendaResult' => 'tns:EstornaVendaResult'),  //output
			 $ns,         						// namespace
                        "$ns/EstornaVenda",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'EstornaVenda' );  //description

function EstornaVenda ($id_vendapdv,$dadosLogin) {
 
     include_once '../_system/Class_conn.php';
     include_once './func/function.php'; 
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
 /* $dec=$row['NUM_DECIMAIS'];
  if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}  */
   
       //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           return  array('EstornaVendaResult'=>array('msgerro'=>'LOJA DESABILITADA'));
           exit();   
        }   
    
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
            
			$CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dadosLogin['idcliente']." AND 
														  COD_UNIVENDA=".$dadosLogin['idloja']." AND LOG_STATUS='S'";
													  
			$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
			if(!$RSCONFIGUNI)
			{		  
	         
			}else{
					if($RCCONFIGUNI=mysqli_num_rows($RSCONFIGUNI) > 0)
					{
						//aqui pega da unidade
						$RWCONFIGUNI=mysqli_fetch_assoc($RSCONFIGUNI);
						$dec=$RWCONFIGUNI['NUM_DECIMAIS']; 
						if ($RWCONFIGUNI['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}
						$LOG_CADVENDEDOR=$RWCONFIGUNI['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$RWCONFIGUNI['COD_DATAWS'];
					}else{
						//aqui pega da controle de licença
						$dec=$row['NUM_DECIMAIS'];			
						if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$decimal = '0';}			
						$LOG_CADVENDEDOR=$row['LOG_CADVENDEDOR'];
						$COD_DATAWS1=$row['COD_DATAWS'];
					}   
			}
    $msg=valida_campo_vazio($id_vendapdv['id_vendapdv'],'id_vendapdv','string');
    if(!empty($msg)){return array('EstornaVendaResult'=>array('msgerro' => $msg));}
       //Empresa desabilitada
        if($row['LOG_ATIVO']=='N')
        {
             return array('EstornaVendaResult'=>array('msgerro' => 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));  
             exit();
            
        }
        //VERIFICA COD EMPRESA
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
        {
           return array('EstornaVendaResult'=>array('msgerro' => 'Id_cliente não confere com o cadastro'));  
           exit();
          //$passou=1;
        } 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {
       
            
        
            //memoria
           $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'EstornaVEnda',$row['COD_EMPRESA']);
              
$xmlteste=addslashes(file_get_contents("php://input"));
$saida = preg_replace('/\s+/',' ', $xmlteste);
$inserarray='INSERT INTO origemestornavenda (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
            ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
             "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$id_vendapdv.'","0","'.$saida.'","'.$arralogin.'")';
 mysqli_query($connUser->connUser(),$inserarray);
// return array('EstornaVendaResult'=>array( 'msgerro'=> $inserarray));   
//    exit();
            
            
            //compara os id_cliente com o cod_empresa
            if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
            {
               return array('EstornaVendaResult'=>array('msgerro' => 'Id_cliente não confere com o cadastro'));  
               exit();
              //$passou=1;
            } 
                    //VENDA WS   
                   $SQLVENDA_WS = "CALL SP_ESTORNA_VENDA_WS('".$row['COD_EMPRESA']."', '".$row['COD_USUARIO']."', '".$id_vendapdv."','".$dadosLogin['idloja']."')" ;
                   $VENDA_WS=mysqli_query($connUser->connUser(),$SQLVENDA_WS);
                   $row_estornaV=mysqli_fetch_assoc($VENDA_WS); 
                   // $LOG1e="INSERT INTO teste (des_teste,cod_empresa) VALUES ('". addslashes($SQLVENDA_WS)."','".$row['COD_EMPRESA']."');";
                  //   mysqli_query($connUser->connUser(), $LOG1e);
                
                   
                    if($row_estornaV['msgerro']=='OK')
                    {   
                        //consulta saldo cliente
                        $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$row_estornaV['v_COD_CLIENT'].')';
                        $rowprocsaldo=mysqli_query($connUser->connUser(),$procsaldo);
                        $rowSALDO_CLIENTE = mysqli_fetch_assoc($rowprocsaldo);
                      
                        
                        
                        //saldo cliente
                        $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal);
                        $saldoresgate=fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$decimal);

                        //busca cliente 
                        $sql2="SELECT * FROM clientes where COD_CLIENTE=".$row_estornaV['v_COD_CLIENT']; 
                        $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$sql2));  

                        $msg=$row_estornaV['msgerro'];


                         $comprovante='CLIENTE: '.$row1['NOM_CLIENTE'].'
                                     Cartão: '.$row1['NUM_CARTAO'].'
                                     DATA: '.date("Y-m-d H:i:s").'
                                     SALDO ACULUMADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 

                    $urlextrato=fnEncode($dadosLogin['login'].';'
                                        .$dadosLogin['senha'].';'
                                        .$dadosLogin['idloja'].';'
                                        .$dadosLogin['idmaquina'].';'
                                        .$row['COD_EMPRESA'].';'
                                        .$dadosLogin['codvendedor'].';'
                                        .$dadosLogin['nomevendedor'].';'
                                        .$row1['NUM_CARTAO']
                                         );
                 //' 'url' =>"http://extrato.bunker.mk?key=$urlextrato",
                    }
                    else
                    {
                     $msg=$row_estornaV['msgerro'];    
                    }    
              
//memoria log
fnmemoriafinal($connUser->connUser(),$cod_men); 
mysqli_close($connAdm->connAdm());   
mysqli_close($connUser->connUser()); 
                            return array('EstornaVendaResult'=>array(
                                                                    'nome'=>$row1['NOM_CLIENTE'],
                                                                    'cartao'=>$row1['NUM_CARTAO'],
                                                                    'saldo'=>$saldo,
                                                                    'saldoresgate'=>$saldoresgate,
                                                                    'comprovante'=>$comprovante,
                                                                    'comprovante_resgate'=> '',
                                                                    'url'=>"http://extrato.bunker.mk?key=$urlextrato",
                                                                    'msgerro'=>$msg
                                        ));
                

                       
                        
                         
                         
        }else{
            return array('EstornaVendaResult'=>array('msgerro'=>'Erro Na autenticação'));

        }   
}
?>
