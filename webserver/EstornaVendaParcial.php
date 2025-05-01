<?php
//=================================================================== GetURLTktMania ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'EstornaVendaParcialResult',
    'complexType',
    'struct',
    'sequence',
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
         array('id_item' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_item', 'type' => 'xsd:integer'),
               'codigoproduto' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigoproduto', 'type' => 'xsd:string'),
               'quantidade' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'quantidade', 'type' => 'xsd:string')
              
             )
);
$server->wsdl->addComplexType(
    'ArrayOfEstornoItem',
    'complexType',
    'struct',
    'sequence',
    '',
         array('EstornoItem' =>array('minOccurs'=>'0', 'maxOccurs'=>'unbounded','name' => 'EstornoItem', 'type' => 'tns:EstornoItem'))
);



$server->wsdl->addComplexType(
    'DadosEstornoParcial',
    'complexType',
    'struct',
    'sequence',
    '',
         array('id_vendapdv' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'id_vendapdv', 'type' => 'xsd:string'),
               'cartao' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'cartao', 'type' => 'xsd:string'),
               'items' =>array('minOccurs'=>'0','maxOccurs'=>'1','name' => 'ArrayOfEstornoItem', 'type' => 'tns:ArrayOfEstornoItem')
                )
);



 $server->register('EstornaVendaParcial',
			array(
                              'Estorno'=>'tns:DadosEstornoParcial',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('EstornaVendaResult' => 'tns:EstornaVendaParcialResult'),  //output
			 $ns,         						// namespace
                        "$ns/EstornaVendaParcial",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'EstornaVendaParcial'         		// documentation
                    );

function EstornaVendaParcial($Estorno,$dadosLogin) {
     include '../_system/Class_conn.php';
     include './func/function.php'; 
     $msg=valida_campo_vazio($Estorno['id_vendapdv'],'id_vendapdv','string');
     if(!empty($msg)){return array('EstornaVendaResult'=>array('msgerro' => $msg));}
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    
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
    
     //limpa campos cartao/cpf
     $CPFCARTAOLIMPO=fnlimpaCPF($Estorno['cartao']); 
     //url extrato
    
 //'url'=>"http://extrato.bunker.mk?key=$urlextrato",
    
    
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
          //verifica se a empresa ta ativa  
           if($row['LOG_ATIVO']!='S')
            {
                return array('EstornaVendaResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
                exit();
            } 
$xmlteste=addslashes(file_get_contents("php://input"));
$saida = preg_replace('/\s+/',' ', $xmlteste);
$inserarray='INSERT INTO origemestornavenda (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA)values
            ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
             "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$Estorno['id_vendapdv'].'","'.$CPFCARTAOLIMPO.'","'.$saida.'")';
$arraP=mysqli_query($connUser->connUser(),$inserarray);

   if (!$arraP)
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
        try {mysqli_query($connUser->connUser(),$inserarray);} 
        catch (mysqli_sql_exception $e) {$msgsql= $e;} 
        $msg="Error description insrirlog: $msgsql";
        $xamls= addslashes($msg);
        Grava_log($connUser->connUser(),1,$xamls);
        return array('EstornaVendaResult'=>array( 'msgerro'=> $msg));
        exit();
    }

 
//memoria
$cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'EstornaVendaParcial',$row['COD_EMPRESA']);

/*$dec=$row['NUM_DECIMAIS'];
if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}     
  */
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
           //carrega dados do cliente
            //  $dadosbase=fn_consultaBase($connUser->connUser(),'','',trim($CPFCARTAOLIMPO),'','',$row['COD_EMPRESA']);   
              
           
            //verifica cod empresa
            if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
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
                                                             '".$Estorno['items']['EstornoItem']['codigoproduto']."',    
                                                             '".fnFormatvalor($Estorno['items']['EstornoItem']['quantidade'],$dec)."', 
                                                             '".$row['COD_USUARIO']."',
                                                             '".$dadosLogin['idloja']."',    
                                                             'EXC'     
                                                           );"; 
                       
                       mysqli_query($connUser->connUser(),$cad_venda); 
                      //COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,MENSSAGEM
                    }
                    else
                    {    
                        for($i=0;$i < count($Estorno['items']['EstornoItem']);$i++){

                            $cad_venda= "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                                   '".$Estorno['id_vendapdv']."',
                                                                   '".$Estorno['items']['EstornoItem'][$i]['id_item']."',
                                                                   '".$Estorno['items']['EstornoItem'][$i]['codigoproduto']."',       
                                                                   '".fnFormatvalor($Estorno['items']['EstornoItem'][$i]['quantidade'],$dec)."', 
                                                                   '".$row['COD_USUARIO']."',
                                                                   '".$dadosLogin['idloja']."',
                                                                   'EXC'     
                                                                 );"; 
                            //Executa query

                        mysqli_query($connUser->connUser(),$cad_venda); 
                        }                               
                        
                        
                    }
                    
                       mysqli_free_result($dadoscli);
                       mysqli_next_result($connUser->connUser());
                       mysqli_close($connUser->connUser());
                       
                     $dadosclientes="select  b.COD_CLIENTE as COD_CLIENTE,b.NOM_CLIENTE as NOM_CLIENTE,b.NUM_CARTAO as NUM_CARTAO,'gerado com sucesso' as MENSSAGEM from vendas a,clientes b
                                    where 
                                    a.cod_cliente=b.cod_cliente and
                                    a.COD_EMPRESA=b.cod_empresa and
                                    a.cod_vendapdv='".$Estorno['id_vendapdv']."' and
                                    a.COD_EMPRESA='".$row['COD_EMPRESA']."';";
                     $retornodados=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dadosclientes));
                   //retorna saldo 
                    $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$retornodados['COD_CLIENTE'].');';
                    $SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
                    $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                 
                    
                     $comprovante='CLIENTE: '.$retornodados['NOM_CLIENTE'].'
                                    Cartão: '.$retornodados['NUM_CARTAO'].'
                                    DATA: '.date("Y-m-d H:i:s").'
                                    SALDO ACULUMADO: '.fnFormatvalor($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 
                     //memoria log
                     $urlextrato=fnEncode($dadosLogin['login'].';'
                        .$dadosLogin['senha'].';'
                        .$dadosLogin['idloja'].';'
                        .$dadosLogin['idmaquina'].';'
                        .$row['COD_EMPRESA'].';'
                        .$dadosLogin['codvendedor'].';'
                        .$dadosLogin['nomevendedor'].';'
                        .$retornodados['NUM_CARTAO']
                         );
                     
                     fnmemoriafinal($connUser->connUser(),$cod_men); 
                     mysqli_close($connAdm->connAdm());   
                     mysqli_close($connUser->connUser()); 
                     return  array('EstornaVendaResult'=>array(
                                                    'nome'=>$dadoscli['NOM_CLIENTE'],
                                                    'cartao'=>$dadoscli['NUM_CARTAO'],
                                                    'saldo'=>(float)fnFormatvalor($rowSALDO_CLIENTE['TOTAL_CREDITO'],$decimal),
                                                    'comprovante'=>$comprovante,
                                                    'url'=> "http://extrato.bunker.mk?key=$urlextrato",
                                                    'msgerro'=> 'OK'
                                ));
                }else {  return  array('EstornaVendaResult'=>array( 'msgerro'=>'idcliente não confere com o cadastrado!'));}                          
        }else {  return  array('EstornaVendaResult'=>array( 'msgerro'=>'Erro no usuario ou senha!')); }
}     
