<?php

//=================================================================== AtualizaCadastro ====================================================================
//retorno dados
$server->wsdl->addComplexType(
    'AtualizaCadastroRetorno',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
        'msgcampanha' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgcampanha', 'type' => 'xsd:string'),
        'url' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'url', 'type' => 'xsd:string'),
        'ativacampanha' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ativacampanha', 'type' => 'xsd:string'),
        'dadosextras' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'dadosextras', 'type' => 'xsd:string')
    )
);
//inserir dados
$server->wsdl->addComplexType(
    'FichadeCadastro',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:string'),
        'tipocliente' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'tipocliente', 'type' => 'xsd:string'),
        'nome' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'nome', 'type' => 'xsd:string'),
        'cpf' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cpf', 'type' => 'xsd:string'),
        'cnpj' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cnpj', 'type' => 'xsd:string'),
        'rg' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'rg', 'type' => 'xsd:string'),
        'sexo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'sexo', 'type' => 'xsd:string'),
        'datanascimento' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'datanascimento', 'type' => 'xsd:string'),
        'estadocivil' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'estadocivil', 'type' => 'xsd:string'),
        'email' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'email', 'type' => 'xsd:string'),
        'dataalteracao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'dataalteracao', 'type' => 'xsd:string'),
        'cartaotitular' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartaotitular', 'type' => 'xsd:string'),
        'nomeportador' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'nomeportador', 'type' => 'xsd:string'),
        'grupo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'grupo', 'type' => 'xsd:string'),
        'profissao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'profissao', 'type' => 'xsd:string'),
        'clientedesde' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'clientedesde', 'type' => 'xsd:string'),
        'endereco' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'endereco', 'type' => 'xsd:string'),
        'numero' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'numero', 'type' => 'xsd:string'),
        'complemento' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'complemento', 'type' => 'xsd:string'),
        'bairro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'bairro', 'type' => 'xsd:string'),
        'cidade' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cidade', 'type' => 'xsd:string'),
        'estado' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'estado', 'type' => 'xsd:string'),
        'cep' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cep', 'type' => 'xsd:string'),
        'telresidencial' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'telresidencial', 'type' => 'xsd:string'),
        'telcelular' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'telcelular', 'type' => 'xsd:string'),
        'telcomercial' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'telcomercial', 'type' => 'xsd:string'),
        'saldo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldoresgate', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
        'msgcampanha' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgcampanha', 'type' => 'xsd:string'),
        'url' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'url', 'type' => 'xsd:string'),
        'ativacampanha' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ativacampanha', 'type' => 'xsd:string'),
        'dadosextras' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'dadosextras', 'type' => 'xsd:string'),
        'bloqueado' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'bloqueado', 'type' => 'xsd:string'),
        'motivo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'motivo', 'type' => 'xsd:string'),
        'adesao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'adesao', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codatendente', 'type' => 'xsd:string'),
        'senha' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'senha', 'type' => 'xsd:string'),
        'urlextrato' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'urlextrato', 'type' => 'xsd:string'),
        'retornodnamais' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'retornodnamais', 'type' => 'xsd:string'),

    )
);


$server->register(
    'AtualizaCadastro',
    array(
        'cliente' => 'tns:FichadeCadastro',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('AtualizaCadastroResult' => 'tns:AtualizaCadastroRetorno'),  //output
    $ns,  // namespace
    "$ns/AtualizaCadastro", // soapaction
    'document',                         // style
    'literal',                          // use
    'Add Parameters'                    // documentation
);

function AtualizaCadastro($cliente, $dadosLogin)
{

    //    error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    include_once '../_system/Class_conn.php';
    include_once './func/function.php';

    $msg = valida_campo_vazio($dadosLogin['idloja'], 'idloja', 'numeric');
    if (!empty($msg)) {
        return array('AtualizaCadastroResult' => array('msgerro' => $msg));
    }
    $msg = valida_campo_vazio($dadosLogin['idmaquina'], 'idmaquina', 'string');
    if (!empty($msg)) {
        return array('AtualizaCadastroResult' => array('msgerro' => $msg));
    }

    /*$msg=valida_campo_vazio($cliente['tipocliente'],'tipocliente','string');
    if(!empty($msg)){return array('AtualizaCadastroResult'=>array('msgerro' => $msg));} 
  */
    sleep(0.25);
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        return  array('AtualizaCadastroResult' => array('msgerro' => 'LOJA DESABILITADA'));
        exit();
    }


    if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
        $passou = 1;
    } else {
    }
    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
    //verifica lojas e maquinas 

    // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
    //limpa CPF
    $cpfcnpjinicial = trim(rtrim(fnlimpaCPF($cliente['cpf'])));
    $cartao = trim(rtrim(fnlimpaCPF($cliente['cartao'])));
    if ($cpfcnpjinicial == '') {
        $cpfcartaourl = $cartao;
    } else {
        $cpfcartaourl = $cpfcnpjinicial;
    }



    //calculo de idade
    //url extrato
    $urlextrato = fnEncode(
        $dadosLogin['login'] . ';'
            . $dadosLogin['senha'] . ';'
            . $dadosLogin['idloja'] . ';'
            . $dadosLogin['idmaquina'] . ';'
            . $row['COD_EMPRESA'] . ';'
            . $dadosLogin['codvendedor'] . ';'
            . $dadosLogin['nomevendedor'] . ';'
            . $cpfcartaourl
    );
    //'url'=>"http://extrato.bunker.mk?key=$urlextrato",


    // Declara a data! :P
    $data = $cliente['datanascimento'];
    $idadecalc = calc_idade($data);
    //atualiza cliente se ja existe na base de dados
    $arraydata = explode("-", $cliente['datanascimento']);

    //===============================================      
    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        if ($row['LOG_ATIVO'] == 'S') {
        } else {
            return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
        }

        $cod_men = fnmemoria($connUser->connUser(), 'true', $dadosLogin['login'], 'atualiza Cadastro', $row['COD_EMPRESA']);

        //inserir venda inteira na base de dados 
        $arraydados1 = array(
            'CONN' => $connUser->connUser(),
            'DATA_HORA' => date("Y-m-d H:i:s"),
            'IP' => $_SERVER['REMOTE_ADDR'],
            'PORT' => $_SERVER['REMOTE_PORT'],
            'COD_USUARIO' => $row['COD_USUARIO'],
            'LOGIN' => $dadosLogin['login'],
            'COD_EMPRESA' => $row['COD_EMPRESA'],
            'IDLOJA' => $dadosLogin['idloja'],
            'IDMAQUINA' => $dadosLogin['idmaquina'],
            'CPF' => $cpfcartaourl,
            'XML' => file_get_contents("php://input")
        );
        $LOG = fngravaxmlatualiza($arraydados1);

        /////////////////////============================================================================================                                      
        $msg = valida_campo_vazio($cartao, 'cartao', 'numeric');
        if (!empty($msg) || !empty($msg1)) {
            Grava_log_cad($connUser->connUser(), $LOG, $msg);
            return array('AtualizaCadastroResult' => array('msgerro' => $msg));
        }


        $msg1 = valida_campo_vazio(utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))), 'nome', 'string');
        if (!empty($msg1)) {
            Grava_log_cad($connUser->connUser(), $LOG, $msg1);
            return array('AtualizaCadastroResult' => array('msgerro' => $msg1));
        }

        if ($cliente['tipocliente'] == '' || $cliente['tipocliente'] == 'F' || $cliente['tipocliente'] == 'PF') {
            $msg2 = valida_campo_vazio($cpfcnpjinicial, 'cpf', 'numeric');
            if (!empty($msg2)) {
                Grava_log_cad($connUser->connUser(), $LOG, $msg2);
                return array('AtualizaCadastroResult' => array('msgerro' => $msg2));
            }
        } else {
            $msg2 = valida_campo_vazio($cliente['tipocliente'], 'cnpj', 'string');
            if (!empty($msg2)) {
                Grava_log_cad($connUser->connUser(), $LOG, $msg2);
                return array('AtualizaCadastroResult' => array('msgerro' => $msg2));
            }
        }
        $msg3 = valida_campo_vazio($cliente['sexo'], 'sexo', 'string');

        if (!empty($msg3)) {
            Grava_log_cad($connUser->connUser(), $LOG, $msg3);
            return array('AtualizaCadastroResult' => array('msgerro' => $msg3));
        }
        $msg4 = valida_campo_vazio($cliente['datanascimento'], 'datanascimento', 'DATA_US');

        if (!empty($msg4)) {
            Grava_log_cad($connUser->connUser(), $LOG, $msg4);
            return array('AtualizaCadastroResult' => array('msgerro' => $msg4));
        }
        $msg5 = valida_campo_vazio(rtrim(trim($cliente['email'])), 'email', 'string');
        if (!empty($msg5)) {
            Grava_log_cad($connUser->connUser(), $LOG, $msg5);
            return array('AtualizaCadastroResult' => array('msgerro' => $msg5));
        }

        //Qualidade de cadastro
        if ($row['LOG_QUALICAD'] == "S") {
            $arrayconn = array(
                'connadm' => $connAdm->connAdm(),
                'connuser' => $connUser->connUser()
            );
            $campolimpo = str_replace('-', '', $cliente['cep']);
            if (
                array_key_exists("cep", $cliente) && (strlen($campolimpo) == '5') ||
                (strlen($campolimpo) == '8')
            ) {
                $msg = FnQualidade_cad($arrayconn, $row['COD_EMPRESA'], $cliente, $LOG, $dadosLogin, $dadosLogin['idloja']);
            } elseif (!array_key_exists("cep", $cliente)) {
                $msg = FnQualidade_cad($arrayconn, $row['COD_EMPRESA'], $cliente, $LOG, $dadosLogin, $dadosLogin['idloja']);
            }
        }

        //========================================

        //nao atualiza cliente avulso
        if ($row['LOG_ATIVO'] == 'S') {

            //vendedor
            $NOM_USUARIO = utf8_encode(fnAcentos(rtrim(trim($cliente['codatendente']))));
            $NOM_VENDEDOR = utf8_encode(fnAcentos(rtrim(trim($dadosLogin['nomevendedor']))));
            $cod_atendente = fnatendente($connAdm->connAdm(), $NOM_USUARIO, $dadosLogin['idcliente'], $dadosLogin['idloja'], $NOM_USUARIO);

            $NOM_USUARIO = utf8_encode(fnAcentos(rtrim(trim($dadosLogin['codvendedor']))));
            $cod_vendedor = fnVendedor($connAdm->connAdm(), $NOM_VENDEDOR, $dadosLogin['idcliente'], $dadosLogin['idloja'], $NOM_USUARIO);

            //=====  


            if ($passou != 1) {
                //////////////////////////////////////////////////////////////// 
                $lojas = fnconsultaLoja($connAdm->connAdm(), $connUser->connUser(), $dadosLogin['idloja'], $dadosLogin['idmaquina'], $row['COD_EMPRESA']);
                //consulta base de dados
                $dadosbase = fn_consultaBase($connUser->connUser(), $cpfcnpjinicial, $cliente['cnpj'], $cartao, rtrim(trim($cliente['email'])), $cliente['telcelular'], $row['COD_EMPRESA']);


                //busca retorno profissão
                $bus_PROFISS = "select * from profissoes where  DES_PROFISS='" . $cliente['profissao'] . "'";
                $profiss_ret = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $bus_PROFISS));
                ////////////////

                if ($profiss_ret['COD_PROFISS'] == '') {
                    $PROFISSAO = 0;
                } else {
                    $PROFISSAO = $profiss_ret['COD_PROFISS'];
                }

                //verifica se o cliente e pj ou pf
                if ($cliente['tipocliente'] != 'PJ' || strlen($cpfcnpjinicial) == '11') {
                    if ($cartao == 0 || $cpfcnpjinicial == 0) {
                        $MSG = 'Cliente 0 Não pode ser atualizado ou Inserido';
                        Grava_log_cad($connUser->connUser(), $LOG, $MSG);
                        return array('AtualizaCadastroResult' => array('msgerro' => $MSG));
                        exit();
                    }


                    //atualiza por cpf
                    if ($row['COD_CHAVECO'] == 1 || $row['COD_CHAVECO'] == '5') {
                        $dadosbase = fn_consultaBase($connUser->connUser(), $cpfcnpjinicial, '', $cartao, rtrim(trim($cliente['email'])), $cliente['telcelular'], $row['COD_EMPRESA']);
                        //valida campo
                        // mysqli_set_charset($connUser->connUser(), "utf8");


                        $geracartao = "select  
                    (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
                     cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=" . $row['COD_EMPRESA'];
                        $rsgeracartao = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $geracartao));

                        if (($rsgeracartao['contador'] == 0) &&
                            ($row['COD_CHAVECO'] == '5') &&
                            (strlen($cartao) != 11) &&
                            (strlen($cartao) != 14)
                        ) {
                            Grava_log_cad($connUser->connUser(), $LOG, 'Cartão invalido!');
                            return array('AtualizaCadastroResult' => array('msgerro' => 'Cartão invalido!'));
                        }
                        //====================================================================================
                        if ($row['COD_CHAVECO'] == '5' && strlen($cartao) == $rsgeracartao['NUM_TAMANHO']) {

                            if ($dadosbase[0]['cpf'] != '' || $dadosbase[0]['cartao'] != '') {


                                /*if( (int)$cpfcnpjinicial != $dadosbase[0]['cpf'] || (int) $cartao!= $dadosbase[0]['cartao'])
        {
           Grava_log_cad($connUser->connUser(),$LOG,'Cartao Já cadastrato');
           return array('AtualizaCadastroResult'=>array('msgerro' => $cartao.'!='. $dadosbase[0]['cartao']));   
        }*/

                                if ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado'] == 'N') {
                                    //novo cartao - insere
                                    //update na tabela de cartoes
                                    $updatecartao = "update  geracartao set log_usado='S',COD_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao='" . $cartao . "'";
                                    mysqli_query($connUser->connUser(), $updatecartao);
                                    Grava_log_cad($connUser->connUser(), $LOG, 'Cartão OK...');
                                    $trocacartao = " update clientes  set NUM_CARTAO= $cartao where  COD_EMPRESA=" . $row['COD_EMPRESA'] . " and COD_CLIENTE=" . $dadosbase[0]['COD_CLIENTE'];
                                    mysqli_query($connUser->connUser(), $trocacartao);
                                } elseif ($rsgeracartao['contador'] == 0) {
                                    //cartao inválido - não existe na base
                                    Grava_log_cad($connUser->connUser(), $LOG, 'Cartão inválido!');
                                    return array('AtualizaCadastroResult' => array('msgerro' => 'Cartão inválido!'));
                                } elseif ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado'] == 'S') {
                                    $dadosbase1 = fn_consultaBase($connUser->connUser(), '', '', $cartao, rtrim(trim($cliente['email'])), $cliente['telcelular'], $row['COD_EMPRESA']);

                                    //cartao válido - mas já utilizado
                                    if ($dadosbase1[0]['cpf'] != $cpfcnpjinicial) {
                                        Grava_log_cad($connUser->connUser(), $LOG, 'Cartão já utilizado!');
                                        return array('AtualizaCadastroResult' => array('msgerro' => 'Cartão já utilizado!'));
                                    }
                                }
                            }
                        }



                        $datenascime = fnDataBR($cliente['datanascimento']);

                        if ($dadosbase[0]['COD_CLIENTE'] != 0) {

                            //atualiza cliente se existir na base de dados!
                            if ($cpfcnpjinicial != "") {
                                $cpfcnpj = $cpfcnpjinicial;
                            } else {
                                $cpfcnpj = $cliente['cnpj'];
                            }



                            if (
                                ($cliente['sexo'] == 1) ||
                                ($cliente['sexo'] == 'M') ||
                                ($cliente['sexo'] == 'Masculino') ||
                                ($cliente['sexo'] == 'masculino')
                            ) {
                                $sexo = 1;
                            } elseif (($cliente['sexo'] == 2) ||
                                ($cliente['sexo'] == 'F') ||
                                ($cliente['sexo'] == 'feminino') ||
                                ($cliente['sexo'] == 'Feminino')
                            ) {
                                $sexo = 2;
                            } else {
                                $sexo = 3;
                            }



                            if ($dadosLogin['codvendedor'] == '' || $dadosLogin['codvendedor'] == '?' || $dadosLogin['codvendedor'] == '??') {
                                $codvendedor = 0;
                            } else {
                                $codvendedor = $dadosLogin['codvendedor'];
                            }
                            if (trim($cliente['tipocliente']) == 'PF' || trim($cliente['tipocliente']) == '') {
                                $TP_CLIENTE = 'F';
                            }
                            if ($cliente['dataalteracao'] != '') {
                                $date_alterac = $cliente['dataalteracao'];
                            } else {
                                $date_alterac = date('Y-m-d H:i:s');
                            }


                            $sql1 = " update clientes  
                                                                                set NOM_CLIENTE='" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "',
                                                                                    NUM_RGPESSO='" . $cliente['rg'] . "',
                                                                                    COD_SEXOPES='" . $sexo . "',
                                                                                    TIP_CLIENTE='" . $TP_CLIENTE . "',    
                                                                                    DAT_NASCIME='" . $datenascime . "',
                                                                                    COD_ESTACIV='" . fnLimpaCampoZero($cliente['estadocivil']) . "',
                                                                                    DES_SENHAUS='" . fnEncode($cliente['senha']) . "',    
                                                                                    DES_EMAILUS='" . rtrim(trim($cliente['email'])) . "',
                                                                                    DAT_ALTERAC='" . date('Y-m-d H:i:s') . "',
                                                                                    COD_PROFISS='" . addslashes($PROFISSAO) . "',
                                                                                    DES_ENDEREC='" . utf8_decode(utf8_encode(addslashes($cliente['endereco']))) . "',
                                                                                    NUM_ENDEREC='" . $cliente['numero'] . "',
                                                                                    DES_COMPLEM='" . utf8_decode(utf8_encode(addslashes($cliente['complemento']))) . "',
                                                                                    DES_BAIRROC='" . utf8_decode(utf8_encode(addslashes($cliente['bairro']))) . "',
                                                                                    NOM_CIDADEC='" . utf8_decode(utf8_encode(addslashes($cliente['cidade']))) . "',
                                                                                    COD_ESTADOF='" . $cliente['estado'] . "',
                                                                                    NUM_CEPOZOF='" . fnlimpaCEP($cliente['cep']) . "',
                                                                                    NUM_TELEFON='" . $cliente['telresidencial'] . "',
                                                                                    NUM_CELULAR='" . fnLimpaSTRING($cliente['telcelular']) . "',
                                                                                    COD_ALTERAC='" . $codvendedor . "',
                                                                                    ANO=$arraydata[0],
                                                                                    MES=$arraydata[1],
                                                                                    DIA=$arraydata[2],
                                                                                    IDADE=$idadecalc
                                                                                    where  COD_EMPRESA=" . $row['COD_EMPRESA'] . " and COD_CLIENTE=" . $dadosbase[0]['COD_CLIENTE'];



                            $arraP1 = mysqli_query($connUser->connUser(), $sql1);
                            if (!$arraP1) {
                                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                try {
                                    mysqli_query($connUser->connUser(), $sql1);
                                } catch (mysqli_sql_exception $e) {
                                    $msgsql = $e;
                                }

                                $msg = "Erro ao atualizar cadastro $msgsql";
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                            } else {
                                $msg = 'PF atualizado com sucesso!';
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                fnmemoriafinal($connUser->connUser(), $cod_men);

                                //$ID_CLIENTE="SELECT COD_CLIENTE from clientes where (NUM_CGCECPF= $cpfcnpjinicial and NUM_CARTAO=$cartao) and cod_empresa=".$row['COD_EMPRESA'];
                                //$COD_CLIENTEULT = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_CLIENTE));
                                //$COD_CLIENTERET=$COD_CLIENTEULT['COD_CLIENTE']; 

                                $class_cad = "CALL SP_PERSONA_CLASSIFICA_CADASTRO(" . $dadosbase[0]['COD_CLIENTE'] . ", " . $row['COD_EMPRESA'] . ", 0, '','1')";
                                $class = mysqli_query($connUser->connUser(), $class_cad);
                                if (!$class) {
                                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                    try {
                                        mysqli_query($connUser->connUser(), $class_cad);
                                    } catch (mysqli_sql_exception $e) {
                                        $msgsql = $e;
                                    }

                                    $msg = "Erro ao inserir cadastro $msgsql";
                                    $xamls = addslashes($msg);
                                    Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                    return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                                }
                            }





                            return array('AtualizaCadastroResult' => array(
                                'msgerro' => 'OK',
                                'msgcampanha' => 'Registro atualizado!',
                                'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                'ativacampanha' => 'sim',
                                'dadosextras' => ''
                            ));
                        } else {

                            //valida cpf

                            //atualiza cliente se existir na base de dados!
                            if ($cpfcnpjinicial != "") {
                                $cpfcnpj = $cpfcnpjinicial;
                            } else {
                                $cpfcnpj = $cliente['cnpj'];
                            }


                            //inserir cliente se nao existe na base de dados



                            if (
                                ($cliente['sexo'] == 1) ||
                                ($cliente['sexo'] == 'M') ||
                                ($cliente['sexo'] == 'Masculino') ||
                                ($cliente['sexo'] == 'masculino')
                            ) {
                                $sexo = 1;
                            } elseif (($cliente['sexo'] == 2) ||
                                ($cliente['sexo'] == 'F') ||
                                ($cliente['sexo'] == 'feminino') ||
                                ($cliente['sexo'] == 'Feminino')
                            ) {
                                $sexo = 2;
                            } else {
                                $sexo = 3;
                            }
                            if (trim($cliente['tipocliente']) == 'PF' || trim($cliente['tipocliente']) == '') {
                                $TP_CLIENTE = 'F';
                            }
                            $cod_categoria = "SELECT IFNULL(B.COD_CATEGORIA,0) COD_CATEGORIA
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "'	AND
                                                                                        NUM_ORDENAC=(SELECT MIN(NUM_ORDENAC)
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "')";
                            $rswc = mysqli_query($connUser->connUser(), $cod_categoria);


                            if (!$rswc) {
                                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                try {
                                    mysqli_query($connUser->connUser(), $cod_categoria);
                                } catch (mysqli_sql_exception $e) {
                                    $msgsql = $e;
                                }

                                $msg = "Erro ao inserir cadastro $msgsql";
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                            } else {
                                $rscod_categor = mysqli_fetch_assoc($rswc);
                                if ($rscod_categor['COD_CATEGORIA'] == '' || $rscod_categor['COD_CATEGORIA'] == '0') {
                                    $rscod_categor = '0';
                                } else {
                                    $rscod_categor = $rscod_categor['COD_CATEGORIA'];
                                }
                            }

                            $sql1 = "insert into clientes (COD_CATEGORIA,
                                                                                    NUM_CARTAO,
                                                                                    TIP_CLIENTE,
                                                                                    NOM_CLIENTE,
                                                                                    NUM_CGCECPF,
                                                                                    NUM_RGPESSO,
                                                                                    COD_SEXOPES,
                                                                                    DAT_NASCIME,
                                                                                    COD_ESTACIV,
                                                                                    DES_EMAILUS,
                                                                                    DAT_ALTERAC,
                                                                                    COD_PROFISS,
                                                                                    DAT_CADASTR,
                                                                                    DES_ENDEREC,
                                                                                    NUM_ENDEREC,
                                                                                    DES_COMPLEM,
                                                                                    DES_BAIRROC,
                                                                                    NOM_CIDADEC,
                                                                                    COD_ESTADOF,
                                                                                    NUM_CEPOZOF,
                                                                                    NUM_TELEFON,
                                                                                    NUM_CELULAR,
                                                                                    COD_EMPRESA,
                                                                                    COD_UNIVEND,
                                                                                    COD_MAQUINA,
                                                                                    COD_USUCADA,
                                                                                    LOG_ESTATUS,
                                                                                    ANO,
                                                                                    MES,
                                                                                    DIA,
                                                                                    IDADE,
                                                                                    DES_SENHAUS,
                                                                                    COD_ATENDENTE,
                                                                                    COD_VENDEDOR
                                                                                    )values(
                                                                                    '" . $rscod_categor . "','" . $cartao . "','" . $TP_CLIENTE . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "','" . $cpfcnpj . "','" . $cliente['rg'] . "','" . $sexo . "','" . $datenascime . "',
                                                                                    '" . fnLimpaCampoZero($cliente['estadocivil']) . "','" . rtrim(trim($cliente['email'])) . "','" . fnDataSql(is_Date($cliente['dataalteracao'])) . "','" . $PROFISSAO . "',
                                                                                    '" . date('Y-m-d H:i:s') . "','" . utf8_decode(utf8_encode(addslashes($cliente['endereco']))) . "','" . $cliente['numero'] . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['complemento'])))) . "',
                                                                                    '" . utf8_decode(utf8_encode(addslashes($cliente['bairro']))) . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['cidade'])))) . "','" . utf8_decode(utf8_encode(addslashes($cliente['estado']))) . "','" . fnlimpaCEP($cliente['cep']) . "' ,'" . $cliente['telresidencial'] . "', 
                                                                                    '" . fnLimpaSTRING($cliente['telcelular']) . "','" . $row['COD_EMPRESA'] . "','" . $lojas[0]['COD_UNIVEND'] . "','" . $lojas[0]['COD_MAQUINA'] . "','" . $row['COD_USUARIO'] . "','S',$arraydata[0],$arraydata[1],$arraydata[2],$idadecalc,'" . fnEncode($cliente['senha']) . "','" . $cod_atendente . "','" . $cod_atendente . "');";



                            $arraP1 = mysqli_query($connUser->connUser(), $sql1);

                            if (!$arraP1) {
                                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                try {
                                    mysqli_query($connUser->connUser(), $sql1);
                                } catch (mysqli_sql_exception $e) {
                                    $msgsql = $e;
                                }

                                $msg = "Erro ao inserir cadastro $msgsql";
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                            } else {

                                $ID_CLIENTE = "SELECT COD_CLIENTE from clientes where NUM_CGCECPF= $cpfcnpjinicial and NUM_CARTAO=$cartao and cod_empresa=" . $row['COD_EMPRESA'];
                                $COD_CLIENTEULT = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_CLIENTE));
                                $COD_CLIENTERET = $COD_CLIENTEULT['COD_CLIENTE'];
                                // inserir ativação 
                                $canal = "INSERT INTO log_canal 
																					(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
																					VALUES ('" . $row['COD_EMPRESA'] . "', 
																							'" . $dadosLogin['idloja'] . "', 
																							'" . $COD_CLIENTERET . "', 
																							'1');";
                                $RWcanal = mysqli_query($connUser->connUser(), $canal);
                                //===================================
                                //$class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(".$COD_CLIENTERET.",".$row['COD_EMPRESA'].", 0, '','1')"; 
                                $class_cad = "CALL SP_PERSONA_CLASSIFICA_CADASTRO($COD_CLIENTERET, " . $row['COD_EMPRESA'] . ", 0, '','1')";
                                $class = mysqli_query($connUser->connUser(), $class_cad);
                                if (!$class) {
                                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                    try {
                                        mysqli_query($connUser->connUser(), $class_cad);
                                    } catch (mysqli_sql_exception $e) {
                                        $msgsql = $e;
                                    }

                                    $msg = "Erro ao inserir cadastro $msgsql";
                                    $xamls = addslashes($msg);
                                    Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                    return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                                }

                                $msg = 'PF inserido  com sucesso!';
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);

                                //agenda email disparo
                                if ($cpfcnpjinicial != '0' && $cartao != '0') {
                                    $array = array(
                                        'WHERE' => "WHERE g.TIP_GATILHO in ('cadastro') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                                        'TABLE' => array(
                                            'gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA="S"',
                                            'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA_SMS="S"'
                                        )
                                    );
                                    foreach ($array['TABLE'] as $KEY => $dadostable) {
                                        if ($KEY == '0') {
                                            $gatilho = '2';
                                        } else {
                                            $gatilho = '3';
                                        }

                                        $sqlgatilho_email = "SELECT * FROM $dadostable $array[WHERE]   ORDER BY COD_LISTA DESC   LIMIT 1";



                                        $rwgatilho_email = mysqli_query($connUser->connUser(), $sqlgatilho_email);
                                        if (mysqli_num_rows($rwgatilho_email) >= 1) {
                                            $rsgatilho_email = mysqli_fetch_assoc($rwgatilho_email);
                                            $cod_campanha = $rsgatilho_email['COD_CAMPANHA'];
                                            $TIP_MOMENTO = $rsgatilho_email['TIP_MOMENTO'];
                                            $TIP_GATILHO = $rsgatilho_email['TIP_GATILHO'];
                                            $COD_PERSONAS = $rsgatilho_email['COD_PERSONAS'];
                                            $TIP_CONTROLE = $rsgatilho_email['TIP_CONTROLE'];

                                            if (trim($cliente['email']) != '' || trim($cliente['telcelular']) != '') {
                                                $sqlfila = "INSERT INTO email_fila ( COD_EMPRESA, 
                                                        COD_UNIVEND, 
                                                        COD_CLIENTE, 
                                                        NUM_CGCECPF,
                                                        NOM_CLIENTE, 
                                                        DT_NASCIME, 
                                                        DES_EMAILUS,
                                                        NUM_CELULAR,
                                                        COD_SEXOPES, 
                                                        COD_CAMPANHA,
                                                        TIP_MOMENTO,
                                                        TIP_FILA,
                                                        TIP_GATILHO,
                                                        TIP_CONTROLE
                                                        ) VALUES 
                                                        ('" . $row['COD_EMPRESA'] . "', 
                                                        '" . $dadosLogin['idloja'] . "', 
                                                        '" . $COD_CLIENTERET . "', 
                                                        '" . $cpfcnpj . "', 
                                                        '" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "', 
                                                        '" . $datenascime . "', 
                                                        '" . trim($cliente['email']) . "',
                                                        '" . $cliente['telcelular'] . "', 
                                                        '" . $sexo . "',    
                                                        '" . $cod_campanha . "', 
                                                        '" . $TIP_MOMENTO . "', 
                                                        '" . $gatilho . "',
                                                        '$TIP_GATILHO',
                                                        '$TIP_CONTROLE'    
                                                        );";
                                                $testesql = mysqli_query($connUser->connUser(), $sqlfila);
                                                $clas = "CALL SP_PERSONA_CLASSIFICA_CADASTRO($COD_CLIENTERET, " . $row['COD_EMPRESA'] . ", $cod_campanha, '" . $COD_PERSONAS . "','0')";
                                                $testesql = mysqli_query($connUser->connUser(), $clas);
                                            }
                                        }
                                    }
                                }

                                //==============================================================================   
                                fnmemoriafinal($connUser->connUser(), $cod_men);
                            }








                            $reuturn = array('AtualizaCadastroResult' => array(
                                'msgerro' => 'OK',
                                'msgcampanha' => 'Seja muito bem vindo :-)',
                                'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                'ativacampanha' => '',
                                'dadosextras' => ''
                            ));
                            // $xamls12= addslashes(str_replace(array("\n",""),array(""," "), var_export($cliente,true)));
                            //  Grava_log_cad($connUser->connUser(),$LOG['ID_LOG'],$xamls12);
                            return $reuturn;
                        }

                        //fim da atualiza por cpf                                                    
                    } elseif ($row['COD_CHAVECO'] == 2) {
                        $dadosbase = fn_consultaBase($connUser->connUser(), $cpfcnpjinicial, '', $cartao, '', '', $row['COD_EMPRESA']);

                        //atualiza cartão

                        if ($dadosbase[0]['COD_CLIENTE'] != 0) {

                            if (trim($dadosbase[0]['cpf']) != 0) {

                                //atualiza cliente se existir na base de dados!
                                if ($cpfcnpjinicial != "") {
                                    $cpfcnpj = $cpfcnpjinicial;
                                    $tipopssoa = $cliente['tipocliente'];
                                } else {
                                    $cpfcnpj = $cliente['cnpj'];
                                    $tipopssoa = $cliente['tipocliente'];
                                }
                            } else {
                                $cpfcnpj = 0;
                            }
                            //inserir cliente se nao existe na base de dados

                            try {
                                if (
                                    ($cliente['sexo'] == 1) ||
                                    ($cliente['sexo'] == 'M') ||
                                    ($cliente['sexo'] == 'Masculino') ||
                                    ($cliente['sexo'] == 'masculino')
                                ) {
                                    $sexo = 1;
                                } elseif (($cliente['sexo'] == 2) ||
                                    ($cliente['sexo'] == 'F') ||
                                    ($cliente['sexo'] == 'feminino') ||
                                    ($cliente['sexo'] == 'Feminino')
                                ) {
                                    $sexo = 2;
                                } else {
                                    $sexo = 3;
                                }
                                if ($dadosLogin['codvendedor'] == '' || $dadosLogin['codvendedor'] == '?' || $dadosLogin['codvendedor'] == '??') {
                                    $codvendedor = 0;
                                } else {
                                    $codvendedor = $dadosLogin['codvendedor'];
                                }


                                //if($cliente['sexo'] === '1'){$sexo = '1';}
                                //if($cliente['sexo'] === '2'){$sexo='2';}
                                $sql1 = " update clientes  
                                                                            set NUM_CARTAO='" . $cartao . "',
                                                                                TIP_CLIENTE='" . $tipopssoa . "',
                                                                                NOM_CLIENTE='" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "',
                                                                                NUM_CGCECPF=" . $cpfcnpj . ",
                                                                                NUM_RGPESSO='" . $cliente['rg'] . "',
                                                                                COD_SEXOPES='" . $sexo . "',
                                                                                DAT_NASCIME='" . $datenascime . "',
                                                                                COD_ESTACIV='" . fnLimpaCampoZero($cliente['estadocivil']) . "',
                                                                                DES_EMAILUS='" . $cliente['email'] . "',
                                                                                DAT_ALTERAC='" . fnDataSql(is_Date($cliente['dataalteracao'])) . "',
                                                                                COD_PROFISS='" . $PROFISSAO . "',
                                                                                DAT_CADASTR='" . fnDataSql(is_Date($cliente['clientedesde'])) . "',
                                                                                DES_ENDEREC='" . addslashes($cliente['endereco']) . "',
                                                                                NUM_ENDEREC='" . $cliente['numero'] . "',
                                                                                DES_COMPLEM='" . $cliente['complemento'] . "',
                                                                                DES_BAIRROC='" . addslashes($cliente['bairro']) . "',
                                                                                NOM_CIDADEC='" . $cliente['cidade'] . "',
                                                                                COD_ESTADOF='" . $cliente['estado'] . "',
                                                                                NUM_CEPOZOF='" . fnlimpaCEP($cliente['cep']) . "',
                                                                                NUM_TELEFON='" . $cliente['telresidencial'] . "',
                                                                                NUM_CELULAR='" . fnLimpaSTRING($cliente['telcelular']) . "',
                                                                                ANO=$arraydata[0],
                                                                                MES=$arraydata[1],
                                                                                DIA=$arraydata[2],
                                                                                IDADE=$idadecalc    
                                                                            where COD_CLIENTE=" . $dadosbase[0]['COD_CLIENTE'];

                                mysqli_query($connUser->connUser(), $sql1);
                                fnmemoriafinal($connUser->connUser(), $cod_men);
                                //classifica persona
                                $ID_CLIENTE = "SELECT COD_CLIENTE from clientes where NUM_CGCECPF= $cpfcnpjinicial and NUM_CARTAO=$cartao and cod_empresa=" . $row['COD_EMPRESA'];
                                $COD_CLIENTEULT = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_CLIENTE));
                                $COD_CLIENTERET = $COD_CLIENTEULT['COD_CLIENTE'];

                                /*$class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(
                                                                                                         ".$COD_CLIENTERET.",
                                                                                                         ".$row['COD_EMPRESA']."
                                                                                                         )";*/
                                $class_cad = "CALL SP_PERSONA_CLASSIFICA_CADASTRO($COD_CLIENTERET, " . $row['COD_EMPRESA'] . ", 0, '','1')";

                                $class = mysqli_query($connUser->connUser(), $class_cad);
                                if (!$class) {
                                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                    try {
                                        mysqli_query($connUser->connUser(), $class_cad);
                                    } catch (mysqli_sql_exception $e) {
                                        $msgsql = $e;
                                    }

                                    $msg = "Erro ao inserir cadastro $msgsql";
                                    $xamls = addslashes($msg);
                                    Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                    return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                                }
                                // ========
                            } catch (mysqli_sql_exception $e) {
                                return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                            }
                            //update na tabela de cartoes
                            $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $dadosbase[0]['cartao'];
                            mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));
                            $msg = 'PF gera cartao!';
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                            fnmemoriafinal($connUser->connUser(), $cod_men);
                            return array('AtualizaCadastroResult' => array(
                                'msgerro' => 'OK',
                                'msgcampanha' => 'Registro atualizado!',
                                'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                'ativacampanha' => 'sim',
                                'dadosextras' => ''
                            ));
                        } else {

                            if ($cpfcnpjinicial != "") {
                                $cpfcnpj = $cpfcnpjinicial;
                                $tipopssoa = $cliente['tipocliente'];
                            } else {
                                $cpfcnpj = $cliente['cnpj'];
                                $tipopssoa = $cliente['tipocliente'];
                            }

                            //inserir cliente se nao existe na base de dados

                            try {

                                if ($cliente['tipocliente'] == 'PF') {
                                    $TP_CLIENTE = 'F';
                                }
                                if ($resultIfaro[0]['sexo'][0] == 'M') {
                                    $sexo = 1;
                                } elseif ($resultIfaro[0]['sexo'][0] == 'F') {
                                    $sexo = 2;
                                } else {
                                    $sexo = 3;
                                }
                                $cod_categoria = "SELECT IFNULL(B.COD_CATEGORIA,0) COD_CATEGORIA
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "'	AND
                                                                                        NUM_ORDENAC=(SELECT MIN(NUM_ORDENAC)
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "')";
                                $rswc = mysqli_query($connUser->connUser(), $cod_categoria);


                                if (!$rswc) {
                                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                    try {
                                        mysqli_query($connUser->connUser(), $cod_categoria);
                                    } catch (mysqli_sql_exception $e) {
                                        $msgsql = $e;
                                    }

                                    $msg = "Erro ao inserir cadastro $msgsql";
                                    $xamls = addslashes($msg);
                                    Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                    return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                                } else {
                                    $rscod_categor = mysqli_fetch_assoc($rswc);
                                    if ($rscod_categor['COD_CATEGORIA'] == '' || $rscod_categor['COD_CATEGORIA'] == '0') {
                                        $rscod_categor = '0';
                                    } else {
                                        $rscod_categor = $rscod_categor['COD_CATEGORIA'];
                                    }
                                }

                                $sql1 = "insert into clientes (COD_CATEGORIA,NUM_CARTAO,TIP_CLIENTE,NOM_CLIENTE,NUM_CGCECPF,NUM_RGPESSO,COD_SEXOPES,DAT_NASCIME,COD_ESTACIV,DES_EMAILUS,DAT_ALTERAC,COD_PROFISS,DAT_CADASTR,
                                                                                DES_ENDEREC,NUM_ENDEREC,DES_COMPLEM,DES_BAIRROC,NOM_CIDADEC,COD_ESTADOF,NUM_CEPOZOF,NUM_TELEFON,NUM_CELULAR,COD_EMPRESA,COD_UNIVEND,COD_MAQUINA,
                                                                                COD_USUCADA,LOG_ESTATUS,ANO,
                                                                                    MES,
                                                                                    DIA,
                                                                                    IDADE,
                                                                                    COD_ATENDENTE,
                                                                                    COD_VENDEDOR
                                                                                   )values(
                                                                                '" . $rscod_categor . "','" . $cartao . "','" . $TP_CLIENTE . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "','" . $cpfcnpj . "','" . $cliente['rg'] . "','" . $sexo . "','" . $datenascime . "',
                                                                                '" . fnLimpaCampoZero($cliente['estadocivil']) . "','" . $cliente['email'] . "','" . fnDataSql(is_Date($cliente['dataalteracao'])) . "','" . $PROFISSAO . "',
                                                                                '" . fnDataSql(is_Date($cliente['clientedesde'])) . "','" . addslashes($cliente['endereco']) . "','" . $cliente['numero'] . "','" . $cliente['complemento'] . "',
                                                                                '" . addslashes($cliente['bairro']) . "','" . $cliente['cidade'] . "','" . $cliente['estado'] . "','" . fnlimpaCEP($cliente['cep']) . "' ,'" . $cliente['telresidencial'] . "', 
                                                                                '" . fnLimpaSTRING($cliente['telcelular']) . "'," . $row['COD_EMPRESA'] . "," . $lojas[0]['COD_UNIVEND'] . "," . $lojas[0]['COD_MAQUINA'] . ",'" . $row['COD_USUARIO'] . "','S',$arraydata[0],$arraydata[1],$arraydata[2],$idadecalc,$cod_atendente,$cod_atendente)";
                                mysqli_query($connUser->connUser(), $sql1);
                                $msg = 'PF cadastrado com sucesso!';
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                fnmemoriafinal($connUser->connUser(), $cod_men);
                            } catch (mysqli_sql_exception $e) {
                                return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                            }
                            //ativação do canala
                            $dadosbaseativacao = fn_consultaBase($connUser->connUser(), $cpfcnpjinicial, '', $cartao, '', '', $row['COD_EMPRESA']);
                            $canal = "INSERT INTO log_canal 
																			(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
																			VALUES ('" . $row['COD_EMPRESA'] . "', 
																			'" . $dadosLogin['idloja'] . "', 
																			'" . $dadosbaseativacao[0]['COD_CLIENTE'] . "', 
																			'1');";
                            $RWcanal = mysqli_query($connUser->connUser(), $canal);
                            //==============	
                            //update na tabela de cartoes
                            $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $dadosbase[0]['cartao'];
                            mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));
                            fnmemoriafinal($connUser->connUser(), $cod_men);
                            return array('AtualizaCadastroResult' => array(
                                'msgerro' => 'OK',
                                'msgcampanha' => 'Seja muito bem vindo :-)',
                                'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                'ativacampanha' => 'sim',
                                'dadosextras' => ''
                            ));
                        }
                        //fim da atualiza por cartao  
                    }
                }
                //aqui inicia o PJ
                else {

                    //cadastro de cnpj
                    /////////////////////////////////////////
                    $cpfcnpj = trim(rtrim(fnlimpaCPF($cliente['cnpj'])));
                    $dadosbase = fn_consultaBase($connUser->connUser(), $cpfcnpj, $cpfcnpj, '', '', '', $row['COD_EMPRESA']);
                    if ($cliente['datanascimento'] != '') {
                        $datenascime = fnDataBR($cliente['datanascimento']);
                    } else {
                        $datenascime = date('d/m/Y');
                    }
                    if (trim($cliente['tipocliente']) == 'PJ' || trim($cliente['tipocliente']) == '') {
                        $TP_CLIENTE = 'J';
                    }

                    if ($dadosbase[0]['COD_CLIENTE'] != 0) {

                        if (($cliente['sexo'] == 1) ||
                            ($cliente['sexo'] == 'M') ||
                            ($cliente['sexo'] == 'Masculino') ||
                            ($cliente['sexo'] == '') ||
                            ($cliente['sexo'] == 'U')
                        ) {
                            $sexo = 1;
                        } elseif (($cliente['sexo'] == 2) ||
                            ($cliente['sexo'] == 'F') ||
                            ($cliente['sexo'] == 'feminino')
                        ) {
                            $sexo = 2;
                        } else {
                            $sexo = 3;
                        }
                        //if($cliente['sexo'] === '1'){$sexo = '1';}
                        //if($cliente['sexo'] === '2'){$sexo='2';}
                        if ($dadosLogin['codvendedor'] == '') {
                            $codvendedor = 0;
                        } else {
                            $codvendedor = $dadosLogin['codvendedor'];
                        }

                        $sql1 = " update clientes  
                                                                                  set NUM_CARTAO='" . $cartao . "',
                                                                                      TIP_CLIENTE='" . $TP_CLIENTE . "',
                                                                                      NOM_CLIENTE='" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "',
                                                                                      NUM_CGCECPF=" . $cpfcnpj . ",
                                                                                      NUM_RGPESSO='" . $cliente['rg'] . "',
                                                                                      COD_SEXOPES='" . $sexo . "',
                                                                                      COD_ESTACIV='" . fnLimpaCampoZero($cliente['estadocivil']) . "',
                                                                                      DES_EMAILUS='" . $cliente['email'] . "',
                                                                                      DAT_ALTERAC='" . fnDataSql(is_Date($cliente['dataalteracao'])) . "',
                                                                                      COD_PROFISS='" . $PROFISSAO . "',
                                                                                      DAT_CADASTR='" . fnDataSql(is_Date($cliente['clientedesde'])) . "',
                                                                                      DES_ENDEREC='" . addslashes($cliente['endereco']) . "',
                                                                                      NUM_ENDEREC='" . $cliente['numero'] . "',
                                                                                      DES_COMPLEM='" . fnAcentos($cliente['complemento']) . "',
                                                                                      DES_BAIRROC='" . addslashes($cliente['bairro']) . "',
                                                                                      NOM_CIDADEC='" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['cidade'])))) . "',
                                                                                      COD_ESTADOF='" . $cliente['estado'] . "',
                                                                                      NUM_CEPOZOF='" . fnlimpaCEP($cliente['cep']) . "',
                                                                                      NUM_TELEFON='" . $cliente['telresidencial'] . "',
                                                                                      NUM_CELULAR='" . fnLimpaSTRING($cliente['telcelular']) . "',
                                                                                      ANO=$arraydata[0],
                                                                                        MES=$arraydata[1],
                                                                                        DIA=$arraydata[2],
                                                                                        IDADE=$idadecalc        
                                                                                  where COD_CLIENTE=" . $dadosbase[0]['COD_CLIENTE'];
                        $arraP1 = mysqli_query($connUser->connUser(), $sql1);
                        if (!$arraP1) {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            try {
                                mysqli_query($connUser->connUser(), $sql1);
                            } catch (mysqli_sql_exception $e) {
                                $msgsql = $e;
                            }

                            $msg = "Erro ao atualizar cadastro PJ $msgsql";
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                        } else {
                            $msg = 'PJ atualizado com sucesso!';
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                            fnmemoriafinal($connUser->connUser(), $cod_men);
                            //classifica persona
                            // $ID_CLIENTE="SELECT COD_CLIENTE from clientes where NUM_CGCECPF= $cpfcnpj and cod_empresa=".$row['COD_EMPRESA'];
                            //  $COD_CLIENTEULT = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_CLIENTE));
                            //  $COD_CLIENTERET=$COD_CLIENTEULT['COD_CLIENTE']; 

                            /*  $class_cad="call SP_CLASSIFICA_PERSONA_CADASTRO(
                                                                                                         ".$dadosbase[0]['COD_CLIENTE'].",
                                                                                                         ".$row['COD_EMPRESA']."
                                                                                                         )";*/
                            $class_cad = "CALL SP_PERSONA_CLASSIFICA_CADASTRO(" . $dadosbase[0]['COD_CLIENTE'] . ", " . $row['COD_EMPRESA'] . ", 0, '','1')";
                            $class = mysqli_query($connUser->connUser(), $class_cad);


                            if (!$class) {
                                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                                try {
                                    mysqli_query($connUser->connUser(), $class_cad);
                                } catch (mysqli_sql_exception $e) {
                                    $msgsql = $e;
                                }

                                $msg = "Erro ao inserir cadastro $msgsql";
                                $xamls = addslashes($msg);
                                Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                                return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                            }
                            // ========
                            return array('AtualizaCadastroResult' => array(
                                'msgerro' => 'OK',
                                'msgcampanha' => 'PJ atualizado com sucesso!',
                                'url' => "http://extrato.bunker.mk?key=$urlextrato",
                                'ativacampanha' => 'sim',
                                'dadosextras' => ''
                            ));
                        }
                    } else {
                        if (
                            ($cliente['sexo'] == 1) ||
                            ($cliente['sexo'] == 'M') ||
                            ($cliente['sexo'] == 'Masculino') ||
                            ($cliente['sexo'] == '') ||
                            ($cliente['sexo'] == 'U')
                        ) {
                            $sexo = 1;
                        } elseif (($cliente['sexo'] == 2) ||
                            ($cliente['sexo'] == 'F') ||
                            ($cliente['sexo'] == 'feminino')
                        ) {
                            $sexo = 2;
                        } else {
                            $sexo = 3;
                        }         //if($cliente['sexo'] === '1'){$sexo = '1';}
                        if (trim($cliente['tipocliente']) == 'PJ' || trim($cliente['tipocliente']) == '') {
                            $TP_CLIENTE = 'J';
                        }

                        $cod_categoria = "SELECT IFNULL(B.COD_CATEGORIA,0) COD_CATEGORIA
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "'	AND
                                                                                        NUM_ORDENAC=(SELECT MIN(NUM_ORDENAC)
                                                                                        FROM CATEGORIA_CLIENTE B
                                                                                        WHERE 
                                                                                        B.COD_EMPRESA='" . $row['COD_EMPRESA'] . "')";
                        $rswc = mysqli_query($connUser->connUser(), $cod_categoria);


                        if (!$rswc) {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            try {
                                mysqli_query($connUser->connUser(), $cod_categoria);
                            } catch (mysqli_sql_exception $e) {
                                $msgsql = $e;
                            }

                            $msg = "Erro ao inserir cadastro $msgsql";
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                            return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por favor confira os dados e tente novamente! :-[ '));
                        } else {
                            $rscod_categor = mysqli_fetch_assoc($rswc);
                            if ($rscod_categor['COD_CATEGORIA'] == '' || $rscod_categor['COD_CATEGORIA'] == '0') {
                                $rscod_categor = '0';
                            } else {
                                $rscod_categor = $rscod_categor['COD_CATEGORIA'];
                            }
                        }

                        $sql1 = "insert into clientes (DAT_NASCIME,
												                                COD_CATEGORIA,
												                                NUM_CARTAO,
																				TIP_CLIENTE,
																				NOM_CLIENTE,
																				NUM_CGCECPF,
																				NUM_RGPESSO,
																				COD_SEXOPES,
																				COD_ESTACIV,
																				DES_EMAILUS,
																				DAT_ALTERAC,
																				COD_PROFISS,
																				DAT_CADASTR,
                                                                                DES_ENDEREC,
																				NUM_ENDEREC,
																				DES_COMPLEM,
																				DES_BAIRROC,
																				NOM_CIDADEC,
																				COD_ESTADOF,
																				NUM_CEPOZOF,
																				NUM_TELEFON,
																				NUM_CELULAR,
																				COD_EMPRESA,
																				COD_UNIVEND,
																				COD_MAQUINA,
                                                                                COD_USUCADA,
																				LOG_ESTATUS,
																				ANO,
                                                                                MES,
                                                                                DIA,
                                                                                IDADE,
                                                                                COD_ATENDENTE,
                                                                                COD_VENDEDOR
                                                                                )values(
                                                                                '" . $datenascime . "','" . $rscod_categor . "','" . $cartao . "','" . $TP_CLIENTE . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "','" . rtrim(trim(fnlimpaCPF($cliente['cnpj']))) . "','" . $cliente['rg'] . "','" . $sexo . "',
                                                                                '" . fnLimpaCampoZero($cliente['estadocivil']) . "','" . $cliente['email'] . "','" . fnDataSql(is_Date($cliente['dataalteracao'])) . "','" . $PROFISSAO . "',
                                                                                '" . fnDataSql(is_Date($cliente['clientedesde'])) . "','" . addslashes(fnAcentos($cliente['endereco'])) . "','" . $cliente['numero'] . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['complemento'])))) . "',
                                                                                '" . addslashes($cliente['bairro']) . "','" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['cidade'])))) . "','" . $cliente['estado'] . "','" . fnlimpaCEP($cliente['cep']) . "' ,'" . $cliente['telresidencial'] . "', 
                                                                                '" . fnLimpaSTRING($cliente['telcelular']) . "'," . $row['COD_EMPRESA'] . "," . $lojas[0]['COD_UNIVEND'] . "," . $lojas[0]['COD_MAQUINA'] . ",'" . $row['COD_USUARIO'] . "','S',$arraydata[0],$arraydata[1],$arraydata[2],$idadecalc,$cod_atendente,$cod_atendente)";
                        /*if($cartao=='33860435000165')
													{
													  return array('AtualizaCadastroResult'=>array('msgerro' => $sql1));
													}	
													*/
                        $arraP1 = mysqli_query($connUser->connUser(), $sql1);
                        if (!$arraP1) {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            try {
                                mysqli_query($connUser->connUser(), $sql1);
                            } catch (mysqli_sql_exception $e) {
                                $msgsql = $e;
                            }
                            //  return array('AtualizaCadastroResult'=>array('msgerro' =>$sql1));  

                            $msg = "Erro ao atualizar cadastro PJ $msgsql";
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                        } else {
                            //ativação do canala
                            $dadosbaseativacao = fn_consultaBase($connUser->connUser(), $cpfcnpj, $cpfcnpj, '', '', '', $row['COD_EMPRESA']);

                            $canal = "INSERT INTO log_canal 
																			(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
																			VALUES ('" . $row['COD_EMPRESA'] . "', 
																			'" . $dadosLogin['idloja'] . "', 
																			'" . $dadosbaseativacao[0]['COD_CLIENTE'] . "', 
																			'1');";
                            $RWcanal = mysqli_query($connUser->connUser(), $canal);
                            //==============	


                            $msg = 'PJ cadastrado com sucesso!';
                            $xamls = addslashes($msg);
                            Grava_log_cad($connUser->connUser(), $LOG, $xamls);
                            fnmemoriafinal($connUser->connUser(), $cod_men);

                            //agenda email disparo
                            if ($cpfcnpj != '0' && $cartao != '0') {
                                $array = array(
                                    'WHERE' => "INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa 
                                                           AND p.COD_CAMPANHA=g.cod_campanha
                     WHERE TIP_GATILHO='cadastro' AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                                    'TABLE' => array(
                                        'gatilho_EMAIL g',
                                        'gatilho_sms g'
                                    )
                                );
                                foreach ($array['TABLE'] as $KEY => $dadostable) {
                                    if ($dadostable == 'gatilho_EMAIL') {
                                        $gatilho = '2';
                                    } else {
                                        $gatilho = '3';
                                    }

                                    $sqlgatilho_email = "SELECT * FROM $dadostable $array[WHERE] group by g.COD_CAMPANHA ORDER BY COD_LISTA DESC limit 1";

                                    $rwgatilho_email = mysqli_query($connUser->connUser(), $sqlgatilho_email);
                                    if (mysqli_num_rows($rwgatilho_email) >= 1) {
                                        $rsgatilho_email = mysqli_fetch_assoc($rwgatilho_email);
                                        $cod_campanha = $rsgatilho_email['COD_CAMPANHA'];
                                        $TIP_MOMENTO = $rsgatilho_email['TIP_MOMENTO'];
                                        $TIP_GATILHO = $rsgatilho_email['TIP_GATILHO'];
                                        $COD_PERSONAS = $rsgatilho_email['COD_PERSONAS'];
                                        if (trim($cliente['email']) != '' || trim($cliente['telcelular'])) {
                                            $sqlfila = "INSERT INTO email_fila ( COD_EMPRESA, 
                                                        COD_UNIVEND, 
                                                        COD_CLIENTE, 
                                                        NUM_CGCECPF,
                                                        NOM_CLIENTE, 
                                                        DT_NASCIME, 
                                                        DES_EMAILUS,
                                                        NUM_CELULAR,
                                                        COD_SEXOPES, 
                                                        COD_CAMPANHA,
                                                        TIP_MOMENTO,
                                                        TIP_FILA,
                                                        TIP_GATILHO
                                                        ) VALUES 
                                                        ('" . $row['COD_EMPRESA'] . "', 
                                                        '" . $dadosLogin['idloja'] . "', 
                                                        '" . $dadosbase[0]['COD_CLIENTE'] . "', 
                                                        '" . $cartao . "', 
                                                        '" . utf8_decode(utf8_encode(addslashes(fnAcentos($cliente['nome'])))) . "', 
                                                        '" . date('d/m/Y') . "', 
                                                        '" . trim($cliente['email']) . "',
                                                        '" . $cliente['telcelular'] . "', 
                                                        '" . $sexo . "',    
                                                        '" . $cod_campanha . "', 
                                                        '" . $TIP_MOMENTO . "', 
                                                        '" . $gatilho . "',
                                                        '$TIP_GATILHO'   
                                                        );";

                                            $testesql = mysqli_query($connUser->connUser(), $sqlfila);
                                            if (!$testesql) {
                                                $clas = "CALL SP_PERSONA_CLASSIFICA_CADASTRO(" . $dadosbase[0]['COD_CLIENTE'] . ", " . $row['COD_EMPRESA'] . ", $cod_campanha, '" . $COD_PERSONAS . "','0')";
                                                $Classf = mysqli_query($connUser->connUser(), $clas);
                                            }
                                        }
                                    }
                                }
                            }
                            //==============================================================================                        

                        }
                    }

                    return array('AtualizaCadastroResult' => array(
                        'msgerro' => 'OK',
                        'msgcampanha' => 'Seja muito bem vindo :-)',
                        'url' => "http://extrato.bunker.mk?key=$urlextrato",
                        'ativacampanha' => 'sim',
                        'dadosextras' => ''
                    ));
                }
            } else {
                return array('AtualizaCadastroResult' => array('msgerro' => "Id_cliente não confere com o cadastro!"));
            }
        } else {
            return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
        }
    } else {

        Grava_log_cad($connUser->connUser(), $LOG, 'Erro Na autenticação');
        return array('AtualizaCadastroResult' => array('msgerro' => 'Oh não :-o!  erro Na autenticação :-[ '));
    }
}

//=================================================================== Fim ConsultaCadastroPorCPF ====================================================================
