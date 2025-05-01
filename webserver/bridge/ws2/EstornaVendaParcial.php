<?php
function EstornaVendaParcial($dados) {
      rtrim(trim(require_once('../../../_system/Class_conn.php')));
      rtrim(trim(require_once('../../func/function.php'))); 
      include './functionbridge/functionbridge.php';
      
     $msg=valida_campo_vazio($dados->estorno->id_vendapdv,'id_vendapdv','string');
     if(!empty($msg)){return array('EstornaVendaParcialResult'=>array('msgerro' => $msg));}
     
     $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
     //limpa campos cartao/cpf
   $CPFCARTAO=fnlimpaCPF($dados->estorno->cartao); 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {    
        //conn user
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
		
		    $CONFIGUNI="SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=".$dados->dadoslogin->idcliente." AND 
														  COD_UNIVENDA=".$dados->dadoslogin->idloja." AND LOG_STATUS='S'";
			$RSCONFIGUNI=mysqli_query($connUser->connUser(), $CONFIGUNI);
			if(!$RSCONFIGUNI)
			{		  
				// fnlogmsg($connAdm->connAdm(),$dados->dadoslogin->login,$dados->dadoslogin->idcliente,$cartao,$dados->dadoslogin->idloja,$dados->dadoslogin->idmaquina,$dados->dadoslogin->codvendedor,$dados->dadoslogin->nomevendedor,'InserirVenda','erro no novo parametro da unidade'.$sql,$row['LOG_WS']); 
             
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
        //grava log xml
         //Grava Log de envio do xml
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->estorno->id_vendapdv,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$CPFCARTAO,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemestornavenda',
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
        
        
        
        //verifica se a empresa ta ativa  
        if($row['LOG_ATIVO']!='S')
        {
             return array('EstornaVendaParcialResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
        }
         //verifica se o usuario esta ativo
        if($row['LOG_ESTATUS']=='N')
        {
            return array('EstornaVendaParcialResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
        } 
    }else{ 
         return  array('EstornaVendaParcialResult'=>array( 'msgerro'=>'Usuario e senha invalido!')); 
    }  
    
    //==================================================================================================
     //loop de excluir venda
	 
	 //return  array('EstornaVendaParcialResult'=>array( 'msgerro'=>  print_r($dados, true))); 
             /*       if (count($dados->estorno->items->EstornoItem->id_item)==1){ 
                    
                        $cad_venda = "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                             '".$dados->estorno->id_vendapdv."',
                                                             '".$dados->estorno->items->EstornoItem->id_item."', 
                                                             '".$dados->estorno->items->EstornoItem->codigoproduto."',    
                                                             '".fnFormatvalor($dados->estorno->items->EstornoItem->quantidade,$dec)."', 
                                                             '".$row['COD_USUARIO']."',
                                                             '".$dados->dadoslogin->idloja."',    
                                                             'EXC'     
                                                           );"; 
                     
                       mysqli_query($connUser->connUser(),$cad_venda); 
                      //COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,MENSSAGEM
                      
                    }
                    else
                    {  */  
                        for($i=0;$i < count($dados->estorno->items->EstornoItem);$i++){

                            $cad_venda= "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                                   '".$dados->estorno->id_vendapdv."',
                                                                   '".$dados->estorno->items->EstornoItem[$i]->id_item."',
                                                                   '".$dados->estorno->items->EstornoItem[$i]->codigoproduto."',       
                                                                   '".fnFormatvalor($dados->estorno->items->EstornoItem[$i]->quantidade,$dec)."', 
                                                                   '".$row['COD_USUARIO']."',
                                                                   '".$dados->dadoslogin->idloja."',    
                                                                   'EXC'     
                                                                 );"; 
                            //Executa query

                            mysqli_query($connUser->connUser(),$cad_venda); 
                        }      
						// return  array('EstornaVendaParcialResult'=>array( 'msgerro'=> $cad_venda)); 	
                     //  return  array('EstornaVendaParcialResult'=>array( 'msgerro'=>  print_r($dados, true))); 
                        
                  //  }    
                  
                       
                     $dadosclientes="select  b.COD_CLIENTE as COD_CLIENTE,b.NOM_CLIENTE as NOM_CLIENTE,b.NUM_CARTAO as NUM_CARTAO,'gerado com sucesso' as MENSSAGEM from vendas a,clientes b
                                    where 
                                    a.cod_cliente=b.cod_cliente and
                                    a.COD_EMPRESA=b.cod_empresa and
                                    a.cod_vendapdv='".$dados->estorno->id_vendapdv."' and
                                    a.COD_EMPRESA='".$row['COD_EMPRESA']."';";
                     $retornodados=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dadosclientes));
                   //retorna saldo 
                    $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$retornodados['COD_CLIENTE'].');';
                    $SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
                    $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                 
                    
                     $comprovante='
                                    OPERACAO DE ESTORNO
                                    PROGRAMA FIDELIDADE
                                    __________________________________________
                                    
                                    CLIENTE: '.$retornodados['NOM_CLIENTE'].'
                                    Cartão: '.$retornodados['NUM_CARTAO'].'
                                    DATA: '.date("Y-m-d H:i:s").'
                                    SALDO ACULUMADO: '.fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 
                      
                     //memoria log
                     $urlextrato=fnEncode($dados->dadoslogin->login.';'
                        .$dados->dadoslogin->senha.';'
                        .$dados->dadoslogin->idloja.';'
                        .$dados->dadoslogin->idmaquina.';'
                        .$row['COD_EMPRESA'].';'
                        .$dados->dadoslogin->codvendedor.';'
                        .$dados->dadoslogin->nomevendedor.';'
                        .$retornodados['NUM_CARTAO']
                         );
                     return  array('EstornaVendaParcialResult'=>array(
                                                    'nome'=>$dadoscli['NOM_CLIENTE'],
                                                    'cartao'=>$dadoscli['NUM_CARTAO'],
                                                    'saldo'=>fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec),
                                                    'comprovante'=>$comprovante,
                                                    'url'=> "http://extrato.bunker.mk?key=$urlextrato",
                                                    'msgerro'=> 'OK'
                                ));
    
    
    
}     

