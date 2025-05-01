<?php
function GetURLTktMania($dados) {
    rtrim(trim(require_once('../../../_system/Class_conn.php')));
    rtrim(trim(require_once('../../func/function.php'))); 
    include './functionbridge/functionbridge.php';
   
    //limpa campos cartao/cpf
    @$CPFCARTAO= fnlimpaCPF($dados->CPFCARTAO);
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
 //verifica se o usuario esta ativo
    if($row['LOG_ESTATUS']=='N')
    {
         return array('GetURLTktManiaResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
    } 
 
    //valida usuario
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']) )
    {
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    //verifica cod empresa
    $dadosbase=fn_consultaBase($connUser->connUser(),$CPFCARTAO,'',$CPFCARTAO,'','',$row['COD_EMPRESA']);
        //Grava Log de envio do xml
    $arrylog=array(  'cod_usuario'=>$row['COD_USUARIO'],
                     'login'=>$dados->dadoslogin->login,
                     'cod_empresa'=>$row['COD_EMPRESA'],
                     'pdv'=>'0',
                     'idloja'=>$dados->dadoslogin->idloja,
                     'idmaquina'=>$dados->dadoslogin->idmaquina,
                     'cpf'=>$CPFCARTAO,     
                     'xml'=>addslashes(file_get_contents("php://input")),
                     'tables'=>'log_tkt',
                     'conn'=>$connUser->connUser()
                 );
    $cod_log=fngravalogxml($arrylog);

    
    
    
    } else {
        return array('GetURLTktManiaResult'=>array('msgerro'=>'Login ou senha invalidos!'));
    }
    //valida empresa
    if ($row['COD_EMPRESA'] != $dados->dadoslogin->idcliente)
    {
       return array('GetURLTktManiaResult'=>array('msgerro'=>'Empresa nao confere com o cadastrado!'));
    }
    
    //verifica se a loja esta cadstrada
     $cod_univend=fnConsultaLojaGET($connAdm->connAdm(),$dados->dadosLogin->idloja);
    //verifica se o cliente exixtes 
    if($dadosbase[0]['COD_CLIENTE']!='')
    {    
           // print_r($cod_univend);
            //busca cliente por cpf
            $buscaCPF='SELECT * FROM clientes where NUM_CARTAO="'.$CPFCARTAO.'"';
            $row1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$buscaCPF));
            $id=fnEncode($row['COD_EMPRESA'].';'.$CPFCARTAO.';'.$cod_univend[0]['COD_UNIVEND']);

            $msg='OK';

            $arrayDados=array('cod_empresa'=>$row['COD_EMPRESA'],
                                'idloja'=>$dados->dadoslogin->idloja,
                                'idmaquina'=>$dados->dadoslogin->idmaquina,
                                'cpf'=>$CPFCARTAO,
                                'cartao'=>$CPFCARTAO,
                                'cnpj'=>'',
                                'id_cliente'=>$dadosbase[0]['COD_CLIENTE'],
                                'login'=>$dados->dadoslogin->login,
                                'codvendedor'=>$dados->dadosLogin->codvendedor,
                                'nomevendedor'=>$dados->dadosLogin->nomevendedor,
                                'pagina'=>'Busca_antiga',
                                'connadm'=>$connAdm->connAdm(),
                                'connempresa'=>$connUser->connUser(),
                                'cod_user'=>$row['COD_USUARIO'],
                                'DECIMAL'=>$row['NUM_DECIMAIS']

                                 );
            $fngeratkt=fngeratkt($arrayDados);
            $url= 'http://ticket.fidelidade.mk/?tkt='.$id;   
            
            
    }else{$msg='Cliente não cadastrado!';}

    
    return array('GetURLTktManiaResult'=>array(
                                            'msgerro'=> $msg ,
                                            'urltktmania'=>$url
                                            ));
  }
