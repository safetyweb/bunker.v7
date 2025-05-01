<?php
function validacliente ($dados) {
    require_once('../../_system/Class_conn.php');
    require_once('../func/function.php');
    $email=$dados->dados->email;
    $senha= fnEncode(trim(rtrim($dados->dados->senha)));
    $cod_empresa=trim(rtrim($dados->dados->baseid));
    $ConnBAse=connTemp($cod_empresa,"");
    
    $sql = "SELECT COD_CLIENTE,NOM_CLIENTE,NUM_CARTAO,DES_EMAILUS,DES_SENHAUS FROM clientes WHERE DES_SENHAUS='$senha' AND DES_EMAILUS='$email' and cod_empresa=$cod_empresa";
    $row=mysqli_fetch_assoc(mysqli_query($ConnBAse,$sql));
    //verifica a senha
    if(isset($row['DES_EMAILUS']) || isset($row['DES_SENHAUS']))
    {
        
        $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(".$row['COD_CLIENTE'].")";
        $retSaldo = mysqli_fetch_assoc(mysqli_query($ConnBAse,$consultasaldo));
    }else{
        return array('validaclienteResult'=>array('msgerro'=>'Erro de autenticação'));
    }
    return array('validaclienteResult'=>array('nome'=>$row['NOM_CLIENTE'],
                                              'grupocliente'=>'',
                                              'id_grupopremio'=>'',
                                              'grupopremio'=>'',
                                              'cartao'=>$row['NUM_CARTAO'],
                                              'saldopontos'=>fnformatavalorretorno($retSaldo['TOTAL_CREDITO'],0),
                                              'mensagem'=>'OK',
                                              'urlmovcompras'=>'',
                                              'urlmovresgates'=>'',
                                              'msgerro'=>'OK'
                                      ));

}