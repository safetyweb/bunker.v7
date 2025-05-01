<?php
function EstornaVenda ($dados) {
    rtrim(trim(require_once('../../../_system/Class_conn.php')));
    rtrim(trim(require_once('../../func/function.php'))); 
    include './functionbridge/functionbridge.php';
 

    $msg=valida_campo_vazio($dados->id_vendapdv,'id_vendapdv','string');
     if(!empty($msg)){return array('EstornaVendaResult'=>array('msgerro' => $msg));}
    
      $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
      $buscauser=mysqli_query($connAdm->connAdm(),$sql);
       $row = mysqli_fetch_assoc($buscauser);
       //Numero de decimal da integradora
     //  $dec=$row['NUM_DECIMAIS'];
       //verifica se a empresa foi desabilitada
       if($row['LOG_ATIVO']!='S'){
            return array('EstornaVendaResult'=>array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
            
        }   
       
       
        //===============================================      
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
			
           $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->id_vendapdv,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>'0',     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemestornavenda',
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
            
        } else {
           return array('EstornaVendaResult'=>array('msgerro' => 'Usuario ou senha invalida!'));  
        }
        //verifica se o usuario esta ativo
        if($row['LOG_ESTATUS']=='N')
        {
             return array('EstornaVendaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
        }
        //aqui começa o processo de estornar venda
        
        
                   $SQLVENDA_WS = "CALL SP_ESTORNA_VENDA_WS('".$row['COD_EMPRESA']."', '".$row['COD_USUARIO']."', '".$dados->id_vendapdv."','".$dados->dadoslogin->idloja."')" ;
                   $VENDA_WS=mysqli_query($connUser->connUser(),$SQLVENDA_WS);
                   $row_estornaV=mysqli_fetch_assoc($VENDA_WS); 
 
                    if($row_estornaV['msgerro']=='OK')
                    {   
                        //consulta saldo cliente
                        $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$row_estornaV['v_COD_CLIENT'].')';
                        $rowprocsaldo=mysqli_query($connUser->connUser(),$procsaldo);
                        $rowSALDO_CLIENTE = mysqli_fetch_assoc($rowprocsaldo);

                        //saldo cliente
                        $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec);
                        $saldoresgate=fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$dec);

                        //busca cliente 
                        $sql2="SELECT * FROM clientes where COD_CLIENTE=".$row_estornaV['v_COD_CLIENT']; 
                        $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$sql2));  

                        $msg=$row_estornaV['msgerro'];


                         $comprovante='
                                     OPERACAO DE ESTORNO
                                     PROGRAMA FIDELIDADE 
                                     ________________________________
                                     
                                     CLIENTE: '.$row1['NOM_CLIENTE'].'
                                     Cartão: '.$row1['NUM_CARTAO'].'
                                     DATA: '.date("Y-m-d H:i:s").'
                                     SALDO ACULUMADO: '. fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec).'

                                    *. COMPROVANTE NÃO FISCAL.*'; 

                    $urlextrato=fnEncode($dados->dadoslogin->login.';'
                                        .$dados->dadoslogin->senha.';'
                                        .$dados->dadoslogin->idloja.';'
                                        .$dados->dadoslogin->idmaquina.';'
                                        .$row['COD_EMPRESA'].';'
                                        .$dados->dadoslogin->codvendedor.';'
                                        .$dados->dadoslogin->nomevendedor.';'
                                        .$row1['NUM_CARTAO']
                                         );
                 //' 'url' =>"http://extrato.bunker.mk?key=$urlextrato",
                    
                  //grava no log o cpf/cartao
                    $update="UPDATE origemestornavenda SET NUM_CGCECPF='".$row1['NUM_CARTAO']."' WHERE cod_empresa=".$row['COD_EMPRESA']." and COD_ORIGEM=$cod_log;";                   
                    mysqli_query($connUser->connUser(),$update);   
                    }
                    else
                    {
                     $update="UPDATE origemestornavenda SET NUM_CGCECPF='0',DES_LOGIN='VENDA JA EXCLUIDA' WHERE cod_empresa=".$row['COD_EMPRESA']." and COD_ORIGEM=$cod_log;";                   
                       mysqli_query($connUser->connUser(),$update);      
                     $msg=$row_estornaV['msgerro'];    
                    }    
                 
                    
return array('EstornaVendaResult'=>array(
                                        'nome'=>$row1['NOM_CLIENTE'],
                                        'cartao'=>$row1['NUM_CARTAO'],
                                        'saldo'=>$saldo,
                                        'saldoresgate'=>$saldoresgate,
                                        'comprovante'=>$comprovante,
                                        'comprovante_resgate'=> '',
                                        'url'=>"http://extrato.bunker.mk?key=$urlextrato",
                                        'msgerro'=>$msg
                                        )
            );
                
        
}       
