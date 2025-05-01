<?php
function resgatepremio ($dados) {
    require_once('../../_system/Class_conn.php');
    require_once('../func/function.php');
    $email=$dados->dados->cliente->email;
    $senha= fnEncode($dados->dados->cliente->senha);
    $cod_empresa=$dados->dados->cliente->baseid;
    $ponto_env=$dados->dados->pontos;
    $premio_env=$dados->dados->premio;
    $quantidade_env=$dados->dados->quantidade;
    
    $ConnBAse=connTemp($cod_empresa,"");
    $sql = "SELECT COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,DES_EMAILUS,DES_SENHAUS,COD_UNIVEND FROM clientes WHERE DES_SENHAUS='$senha' AND DES_EMAILUS='$email' and cod_empresa=$cod_empresa";
    $row=mysqli_fetch_assoc(mysqli_query($ConnBAse,$sql));
    //verifica a senha
    
    if(isset($row['DES_EMAILUS']) || isset($row['DES_SENHAUS']))
    {
        //insert into PRODUTOPROMOCAO
        $marka_inset="insert into PRODUTOPROMOCAO 
                                                (cod_empresa,
                                                 des_produto,
                                                 num_pontos,
                                                 log_markapontos
                                                 )values(
                                                 $cod_empresa,
                                                 '$premio_env',
                                                 '$ponto_env',
                                                  1   
                                                 );";
        $rsCliente= mysqli_query($ConnBAse,$marka_inset);
        $COD_LOG= mysqli_insert_id($ConnBAse);
        //========================================
        //Debita
        $DEBITA = "CALL SP_DEBITA_CREDITO(
                                                ".$row['COD_CLIENTE'].",
                                                '$ponto_env',
                                                 $cod_empresa,
                                                ".$row['COD_CLIENTE'].",
                                                ".$row['COD_UNIVEND'].",
                                                $COD_LOG,
                                                '$quantidade_env',
                                                '$ponto_env',
                                                '$ponto_env'                                                    
                                                 )";
        mysqli_query($ConnBAse,$DEBITA);
        mysqli_next_result($ConnBAse);
        
        //========================================
        
        
        $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$row['COD_CLIENTE'].")";
        $retSaldo = mysqli_fetch_assoc(mysqli_query($ConnBAse,$consultasaldo));
        mysqli_free_result($retSaldo);
        mysqli_next_result($ConnBAse);
               
    }else{
        return array('resgatepremioResult'=>array('msgerro'=>'Erro de autenticação'));
    }
    
    
    return array('resgatepremioResult'=>array('autorizacao'=>$COD_LOG,
                                              'saldo'=>fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],0),
                                              'msgerro'=>'OK'
                                            )
                );

}