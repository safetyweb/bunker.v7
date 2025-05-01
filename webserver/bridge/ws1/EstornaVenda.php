<?php
function EstornaVenda ($dados) {
    require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
        
       $connAdmVar=$connAdm->connAdm();
       $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
       $buscauser=mysqli_query($connAdmVar,$sql);
       $row = mysqli_fetch_assoc($buscauser);
       mysqli_next_result($connAdmVar);
        $cartao= fnlimpaCPF($dados->venda->cartao);
       //Numero de decimal da integradora
       $dec=$row['NUM_DECIMAIS'];
       //verifica se a empresa foi desabilitada
        if($row['LOG_ATIVO']!='S'){
            return array('EstornaVendaResult'=>array('msgerro'=>'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
        }   
        //verifica se o usuario esta ativo.
        if($row['LOG_ESTATUS']=='N'){
            return array('EstornaVendaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
        }
     
        //===============================================      
        if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
        {
            
            //conn user
            $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
           $connUserVar=$connUser->connUser(); 

            $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                         'login'=>$dados->dadoslogin->login,
                         'cod_empresa'=>$row['COD_EMPRESA'],
                         'pdv'=>$dados->venda->id_venda,
                         'idloja'=>$dados->dadoslogin->idloja,
                         'idmaquina'=>$dados->dadoslogin->idmaquina,
                         'cpf'=>$cartao,     
                         'xml'=>addslashes(file_get_contents("php://input")),
                         'tables'=>'origemestornavenda',
                         'cupom'=>'0',   
                         'conn'=>$connUserVar
                     );
        $cod_log=fngravalogxml($arrylog); 
    
         
        } else {
           return array('EstornaVendaResult'=>array('msgerro' => 'Usuario ou senha invalida!'));  
        }
                //aqui começa o processo de estornar venda
                   $SQLVENDA_WS = "CALL SP_ESTORNA_VENDA_WS('".$row['COD_EMPRESA']."', '".$row['COD_USUARIO']."', '".$dados->venda->id_venda."','".$dados->dadoslogin->idloja."')" ;
                   $VENDA_WS=mysqli_query($connUserVar,$SQLVENDA_WS);
                   $row_estornaV=mysqli_fetch_assoc($VENDA_WS); 
                   mysqli_next_result($connUserVar);
                   // return array('EstornaVendaResult'=>array('msgerro' => $SQLVENDA_WS ));  
                   if($row_estornaV['msgerro']=='OK')
                    {   
                        //consulta saldo cliente
                        $procsaldo='CALL SP_CONSULTA_SALDO_CLIENTE ('.$row_estornaV['v_COD_CLIENT'].')';
                        $rowprocsaldo=mysqli_query($connUserVar,$procsaldo);
                        $rowSALDO_CLIENTE = mysqli_fetch_assoc($rowprocsaldo);
                        mysqli_next_result($connUserVar);
                        //saldo cliente
                        $saldo=fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'],$dec);
                        $saldoresgate=fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'],$dec);

                        //busca cliente 
                        $sql2="SELECT * FROM clientes where COD_CLIENTE=".$row_estornaV['v_COD_CLIENT']; 
                        $row1 = mysqli_fetch_assoc(mysqli_query($connUserVar,$sql2));  
                        mysqli_next_result($connUserVar);
                        $msg=$row_estornaV['msgerro'];
                        if($row1['NUM_CARTAO']!=0)
                        {
                            $saldoresgatevl=$rowSALDO_CLIENTE['TOTAL_CREDITO'];
                        }else{
                            $saldoresgatevl=0.00;
                        }    


$comprovante='
OPERACAO DE ESTORNO
PROGRAMA FIDELIDADE 
________________________________

CLIENTE: '.$row1['NOM_CLIENTE'].'
Cartão: '.$row1['NUM_CARTAO'].'
DATA: '.date("Y-m-d H:i:s").'
SALDO DE CREDITOS: R$ '. fnformatavalorretorno($saldoresgatevl,$dec).'

COMPROVANTE NÃO FISCAL.'; 

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
                    mysqli_query($connUserVar,$update);  
                    mysqli_next_result($connUserVar);
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
                                        'saldopontos'=>$saldo,                                        
                                        'comprovante'=>$comprovante,                                        
                                        'url'=>"http://extrato.bunker.mk?key=$urlextrato",
                                        'msgerro'=>$msg
                                        )
            );
}