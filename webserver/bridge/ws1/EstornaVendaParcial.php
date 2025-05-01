<?php
function EstornaVendaParcial($dados) {
        require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
      
     $msg=valida_campo_vazio($dados->venda->id_venda,'id_venda','string');
     if(!empty($msg)){return array('EstornaVendaParcialResult'=>array('msgerro' => $msg));}
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
     //limpa campos cartao/cpf
   $CPFCARTAO=fnlimpaCPF($dados->venda->cartao); 
   $dec=$row['NUM_DECIMAIS']; 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {    
        //conn user
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
        
        
        
        
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
    //grava log xml
         //Grava Log de envio do xml
        $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->venda->id_venda,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$CPFCARTAO,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemestornavenda',
                         'conn'=>$connUser->connUser()
                     );
        $cod_log=fngravalogxml($arrylog);
    
    //==================================================================================================
     //loop de excluir venda
                    if (count($dados->venda->items->vendaitem->id_item)==1){ 
                    
                        $cad_venda = "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                             '".$dados->venda->id_venda."',
                                                             '".$dados->venda->items->vendaitem->id_item."', 
                                                             '".$dados->venda->items->vendaitem->codigoproduto."',    
                                                             '".fnFormatvalor($dados->venda->items->vendaitem->quantidade,$dec)."', 
                                                             '".$row['COD_USUARIO']."',
                                                             '".$dados->dadoslogin->idloja."',    
                                                             'EXC'     
                                                           );"; 
                      
                       mysqli_query($connUser->connUser(),$cad_venda); 
                      //COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,MENSSAGEM
                      $msg='OK';
                    }
                    else
                    {    
                        foreach ($dados->venda->items->vendaitem as $key => $value) {
                             $cad_venda= "CALL SP_EXCLUI_ITEM_WS('".$row['COD_EMPRESA']."',
                                                                   '".$dados->venda->id_venda."',
                                                                   '".$value->id_item."',
                                                                   '".$value->codigoproduto."',       
                                                                   '".fnFormatvalor($value->quantidade,$dec)."', 
                                                                   '".$row['COD_USUARIO']."',
                                                                   '".$dados->dadoslogin->idloja."',    
                                                                   'EXC'     
                                                                 );"; 
                            //Executa query
                            $estorpa=mysqli_query($connUser->connUser(),$cad_venda); 
                             mysqli_free_result($estorpa);
                             mysqli_next_result($connUser->connUser());
                        }
                       
                        
                         $msg='OK'; 
                    } 
                  
                      // mysqli_free_result($dadoscli);
                     //  mysqli_next_result($connUser->connUser());
                     //  mysqli_close($connUser->connUser());
                       
                     $dadosclientes="select  b.COD_CLIENTE as COD_CLIENTE,b.NOM_CLIENTE as NOM_CLIENTE,b.NUM_CARTAO as NUM_CARTAO,'gerado com sucesso' as MENSSAGEM from vendas a,clientes b
                                    where 
                                    a.cod_cliente=b.cod_cliente and
                                    a.COD_EMPRESA=b.cod_empresa and
                                    a.cod_vendapdv='".$dados->venda->id_venda."' and
                                    a.COD_EMPRESA='".$row['COD_EMPRESA']."';";
                     $retornodados=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dadosclientes));
                   //retorna saldo 
                    $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$retornodados['COD_CLIENTE'].');';
                    $SALDO_CLIENTE=mysqli_query($connUser->connUser(),$procsaldo);
                    $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                    $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec);
                    
$comprovante='
OPERACAO DE ESTORNO
PROGRAMA FIDELIDADE 
________________________________

CLIENTE: '.$retornodados['NOM_CLIENTE'].'
Cartão: '.$retornodados['NUM_CARTAO'].'
DATA: '.date("Y-m-d H:i:s").'
SALDO DE CREDITOS: R$ '. fnformatavalorretorno((float)$rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec).'

COMPROVANTE NÃO FISCAL.'; 
                      
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
                    return array('EstornaVendaParcialResult'=>array(
                                                    'nome'=>$retornodados['NOM_CLIENTE'],
                                                    'cartao'=>$retornodados['NUM_CARTAO'],
                                                    'saldopontos'=>$saldo,                                        
                                                    'comprovante'=>$comprovante,                                        
                                                    'url'=>"http://extrato.bunker.mk?key=$urlextrato",
                                                    'msgerro'=>$msg
                                                    )
            );
    
    
    
}     

