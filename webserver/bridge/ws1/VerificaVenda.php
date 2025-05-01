<?php
function VerificaVenda ($dados) {
    require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
        
      /* $connAdmVar=$connAdm->connAdm();
       $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
       $buscauser=mysqli_query($connAdmVar,$sql);
       $row = mysqli_fetch_assoc($buscauser);
       mysqli_next_result($connAdmVar);
        $cartao= fnlimpaCPF($dados->venda->cartao);
       //Numero de decimal da integradora
       $dec=$row['NUM_DECIMAIS'];
       //verifica se a empresa foi desabilitada*/
    

    return array('VerificaVendaResult'=>array('vendainserida'=>'1',
                                              'id_venda'=>'vendasteste',
                                              'datahora'=>'18/04/2019 11:34:00',
                                              'dataservidor'=>'18/04/2019 11:34:00',
                                              'cartao'=>'1234567890',
                                              'msgerro'=>'OK'));
}