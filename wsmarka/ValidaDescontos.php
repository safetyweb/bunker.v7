<?php
$server->wsdl->addComplexType(
    'ValidaDescontos',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'saldo_disponivel' => array('name' => 'saldo_disponivel', 'type' => 'xsd:string'),
        'valor_resgate' => array('name' => 'valor_resgate', 'type' => 'xsd:string'),
        'minimoresgate' => array('name' => 'minimoresgate', 'type' => 'xsd:string'),
        'maximoresgate' => array('name' => 'maximoresgate', 'type' => 'xsd:string'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:integer')
    )
);

$server->register(
    'ValidaDescontos',
    array(
        'cpfcnpj' => 'xsd:string',
        'cartao' => 'xsd:string',
        'valortotalliquido' => 'xsd:string',
        'valor_resgate' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('ValidaDescontos' => 'tns:ValidaDescontos'),  //output
    $ns,                                 // namespace
    "$ns/ValidaDescontos",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'ValidaDescontos'                 // documentation
);


function ValidaDescontos($cpfcnpj, $cartao, $valortotalliquido, $valor_resgate, $dadosLogin)
{
    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';

    $cpf = fnlimpaCPF($cpfcnpj);
    $cartao = fnlimpaCPF($cartao);

    /*    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($buscauser);*/
    // Define o diretório onde o arquivo será salvo
    $cacheDir = '/srv/www/htdocs/wsmarka/config_empresa';

    // Verifica se o diretório existe; se não, cria-o (com permissões 0755)
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    // Define o caminho completo do arquivo de cache, usando o idcliente para personalizar o nome
    $cacheFile = $cacheDir . "/config_empresa_" . $dadosLogin['idcliente'] . ".txt";

    // Define o tempo de validade do cache: 15 minutos (15 * 60 = 900 segundos)
    $cacheTime = 900;

    // Verifica se o arquivo de cache existe e se ainda está dentro do período válido
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        // O arquivo existe e não expirou: carrega os dados salvos
        $row = json_decode(file_get_contents($cacheFile), true);
    } else {
        // O arquivo não existe ou expirou: executa a query para obter as informações atualizadas
        $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
        $buscauser = mysqli_query($connAdm->connAdm(), $sql);
        // Obtém o resultado da query
        $row = mysqli_fetch_assoc($buscauser);

        // Salva os dados obtidos no arquivo em formato JSON (sobrescrevendo o que estava lá)
        //file_put_contents($cacheFile, json_encode($row));
        // Só salva os dados no arquivo se o retorno não for nulo
        if ($row !== null) {
            file_put_contents($cacheFile, json_encode($row));
        } else {
            return  array('BuscaConsumidorResponse' => array(
                'msgerro' => 'Dados Login Invalidos!',
                'coderro' => '80'
            ));
        }
    }
    //compara os id_cliente com o cod_empresa

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
        return  array('ValidaDescontos' => array(
            'msgerro' => 'LOJA DESABILITADA',
            'coderro' => '80'
        ));
        exit();
    }
    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        $cod_men = fnmemoria($connUser->connUser(), 'true', $dadosLogin['login'], 'ValidaDescontos', $dadosLogin['idcliente']);

        $xmlteste = addslashes(file_get_contents("php://input"));
        $arrylog = array(
            'cod_usuario' => $row['COD_USUARIO'],
            'login' => $dadosLogin['login'],
            'cod_empresa' => $row['COD_EMPRESA'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'cpf' => $cpf,
            'xml' => $xmlteste,
            'tables' => 'origemdescontos',
            'conn' => $connUser->connUser()

        );

        $cod_log = fngravalogxml($arrylog);


        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><ValidaDescontos></ValidaDescontos>");
        //array_to_xml($return,$xml_user_info);
        //Grava_log($connUser->connUser(),$LOG,'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));




        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'ValidaDescontos', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);

            return  array('ValidaDescontos' => array(
                'msgerro' => 'Id_cliente não confere com o cadastro!',
                'coderro' => '4'
            ));
            exit();
        }
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'ValidaDescontos', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('ValidaDescontos' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'ValidaDescontos', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('ValidaDescontos' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '44'
            ));
            exit();
        }
        //////////////////////=================================================================================================================

    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'ValidaDescontos', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('ValidaDescontos' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }


    //busca cliente  na base de dados    
    $arraydadosbusca = array(
        'empresa' => $dadosLogin['idcliente'],
        'cpf' => $cpf,
        'cartao' => $cartao,
        'venda' => '',
        'ConnB' => $connUser->connUser()
    );

    $cliente_cod = fn_consultaBase($arraydadosbusca);
    /*  if($cartao=='04381597800')
     {    
           return array('ValidaDescontos'=>array('saldo_disponivel'=>print_r($arraydadosbusca)));
           exit;
     }*/
    //   return array('ValidaDescontos'=>array('saldo_disponivel'=>print_r($arraydadosbusca)));
    ob_start();
    /* $dec=$row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$decimal = 0;}*/

    //nova regra de casas decimais 
    $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
														  COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
														  COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";
    $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
    if (!$RSCONFIGUNI) {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'BuscaConsumidor', 'erro na pre-venda dados unidade', $row['LOG_WS']);
    } else {
        if ($RCCONFIGUNI = mysqli_num_rows($RSCONFIGUNI) > 0) {
            //aqui pega da unidade
            $RWCONFIGUNI = mysqli_fetch_assoc($RSCONFIGUNI);
            $dec = $RWCONFIGUNI['NUM_DECIMAIS'];
            if ($RWCONFIGUNI['TIP_RETORNO'] == 2) {
                $decimal = '2';
            } else {
                $decimal = '0';
            }

            $LOG_CADVENDEDOR = $RWCONFIGUNI['LOG_CADVENDEDOR'];
        } else {
            //aqui pega da controle de licença
            $dec = $row['NUM_DECIMAIS'];
            if ($row['TIP_RETORNO'] == 2) {
                $decimal = '2';
            } else {
                $decimal = '0';
            }
            $LOG_CADVENDEDOR = $row['LOG_CADVENDEDOR'];
        }
    }

    if ($cliente_cod['COD_CLIENTE'] != '') {
        //verifica se o saldo resgate é  maior que o disponivel
        if ($valor_resgate > '0.00' || $valor_resgate > 0 || fnFormatvalor($valor_resgate, $dec) >= '0.00' || fnFormatvalor($valor_resgate, $dec) >= '0.000' || fnFormatvalor($valor_resgate, $dec) >= '0.0000' || fnFormatvalor($valor_resgate, $dec) >= '0.00000') {

            if ($cartao > 0 || $cartao != '' || $cpf > 0 || $cpf != '') {

                //=====busca saldo do clientes 
                $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $cliente_cod['COD_CLIENTE'] . ")";
                $rsrown = mysqli_query($connUser->connUser(), $consultasaldo);
                $retSaldo = mysqli_fetch_assoc($rsrown);
                mysqli_free_result($retSaldo);
                mysqli_next_result($connUser->connUser());

                //============================================================================
                //busca valor de configurados para resgates
                $regraresgate = "SELECT round(min(CR.NUM_MINRESG)," . $dec . ") as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                            INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                            WHERE LOG_ATIVO='S' and c.LOG_REALTIME='S' AND
                                c.COD_EXCLUSA=0 AND 
                                ((C.LOG_CONTINU='S'AND CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) OR

                                        ((C.LOG_CONTINU='N') AND

                                            (CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) AND

                                            (CONCAT(C.DAT_FIM,' ', C.HOR_FIM) > NOW()) 

                                            ))  AND C.cod_empresa=" . $dadosLogin['idcliente'];


                $resgresult = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $regraresgate));
                //==========================================================================
                $arrayvalorres = array(
                    'vl_venda' => fnFormatvalor($valortotalliquido, $dec),
                    'PCT_MAXRESG' =>  $resgresult['PCT_MAXRESG']
                );
                $percentual = fnVerificasaldo($arrayvalorres);
                /*if($cpf=='18008297875')
                     {
                        return array('ValidaDescontos'=>array('saldo_disponivel'=> $percentual
                                                   ));
                     }*/
                //calcula porcentagem de resgate


                $VALORMINVENDA1 = (fnFormatvalor($resgresult['NUM_MINRESG'], $dec) * 100) / $resgresult['PCT_MAXRESG'];

                if (fnValorSQL($valor_resgate, 2) <= '0.00') {
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno('0.00', $decimal),
                        'maximoresgate' => fnvalorretorno('0.00', $decimal),
                        'minimoresgate' => fnvalorretorno('0.00', $decimal),
                        'msgerro' => 'Resgate minimo R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . ' com valor minimo de compra R$ ' . fnvalorretorno($VALORMINVENDA1, $decimal),
                        'coderro' => '49'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }

                if (fnFormatvalor($valortotalliquido, $dec) < fnFormatvalor($VALORMINVENDA1, $dec)) {

                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'maximoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Resgate minimo R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . ' com valor minimo de compra R$ ' . fnvalorretorno($VALORMINVENDA1, $decimal),
                        'coderro' => '49'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }
                /*  if($cpf=='39697627908')
                     {
                        return array('ValidaDescontos'=>array('saldo_disponivel'=> $VALORMINVENDA1
                                                   ));
                     }	*/

                if (fnFormatvalor($valortotalliquido, $dec) < fnFormatvalor($resgresult['NUM_MINRESG'], $dec)) {

                    $VALORMINVENDA = (fnFormatvalor($resgresult['NUM_MINRESG'], $dec) * 100) / $resgresult['PCT_MAXRESG'];
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'maximoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Resgate minimo R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . ' com valor minimo de compra R$ ' . fnvalorretorno($VALORMINVENDA, $decimal),
                        'coderro' => '49'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }


                if (fnFormatvalor($valor_resgate, $dec) < fnFormatvalor($resgresult['NUM_MINRESG'], $dec)) {

                    $VALORMINVENDA = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'maximoresgate' => fnvalorretorno($VALORMINVENDA, $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Valor Resgate não pode ser menor que o permitido R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . '',
                        'coderro' => '49'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }

                if (fnFormatvalor($valor_resgate, $dec) > fnFormatvalor($valortotalliquido, $dec)) {

                    $VALORMINVENDA = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'maximoresgate' => fnvalorretorno($VALORMINVENDA, $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Valor Resgate R$ ' . fnvalorretorno($valor_resgate, $dec) . ' não pode ser maior que o valor de venda R$ ' . fnvalorretorno($valortotalliquido, $dec) . '!',
                        'coderro' => '49'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }

                if (fnFormatvalor($valor_resgate, $dec) > fnFormatvalor($percentual, $dec)) {


                    //perguntar se o valor esta maior que o credito disponivel
                    if (fnFormatvalor($valor_resgate, $dec) >  fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {
                        $VALORMINVENDA = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o disponivel', $row['LOG_WS']);
                        fnmemoriafinal($connUser->connUser(), $cod_men);

                        $return = array('ValidaDescontos' => array(
                            'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                            'maximoresgate' => fnvalorretorno($VALORMINVENDA, $decimal),
                            'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                            'msgerro' => 'Valor Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' maior que o credito disponivel  R$' . fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal) . '',
                            'coderro' => '49'
                        ));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' maior que o credito disponivel  R$' . fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal) . '', addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                    if (fnvalorretorno($percentual, $decimal) < fnFormatvalor($resgresult['NUM_MINRESG'], $dec)) {
                        $VALORMINVENDA = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;

                        /* if($cpf=='8359070000142')
                     {
                        return array('ValidaDescontos'=>array('saldo_disponivel'=> fnvalorretorno($VALORMINVENDA,$decimal)
                                                   ));
                     }*/
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                        fnmemoriafinal($connUser->connUser(), $cod_men);
                        $return = array('ValidaDescontos' => array(
                            'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                            'maximoresgate' => fnvalorretorno($VALORMINVENDA, $decimal),
                            'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                            'msgerro' => 'Resgate minimo R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . ' com valor minimo de compra R$ ' . fnvalorretorno($VALORMINVENDA, $decimal),
                            'coderro' => '49'
                        ));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                        return $return;
                    } else {

                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                        fnmemoriafinal($connUser->connUser(), $cod_men);
                        $return = array('ValidaDescontos' => array(
                            'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                            'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                            'maximoresgate' => fnvalorretorno($percentual, $decimal),
                            'msgerro' => 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '',
                            'coderro' => '49'
                        ));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' esta  maior que o permitido de R$' . fnvalorretorno($percentual, $decimal) . '', addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                }

                if (fnFormatvalor($valor_resgate, $dec) < $resgresult['NUM_MINRESG']) {


                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate não pode ser menor que o permitido', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);
                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Valor Resgate não pode ser menor que o permitido R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . '',
                        'coderro' => '50'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor Resgate não pode ser menor que o permitido R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }
                //saldo menor que o disponivel 
                if (fnFormatvalor($valor_resgate, $dec) >  fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {
                    /*if($cpf=='35196685804')
				{
				   return array('ValidaDescontos'=>array('saldo_disponivel'=>fnFormatvalor($valor_resgate,$dec).'maior'.fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'],$dec)
                                                              ));
				}*/
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o disponivel', $row['LOG_WS']);
                    fnmemoriafinal($connUser->connUser(), $cod_men);

                    $return = array('ValidaDescontos' => array(
                        'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                        'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                        'msgerro' => 'Valor Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' maior que o credito disponivel  R$' . fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal) . '',
                        'coderro' => '51'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' maior que o credito disponivel  R$' . fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal) . '', addslashes($xml_user_info->asXML()));
                    return $return;
                }

                //====================================================================================
            } else {


                $return = array('ValidaDescontos' => array(
                    'msgerro' => 'Não CPF ou cartao nao especificado',
                    'coderro' => '208'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Não CPF ou cartao nao especificado', addslashes($xml_user_info->asXML()));
                return $return;
            }

            if (
                $dadosLogin['idcliente'] == '265' ||
                $dadosLogin['idcliente'] == '306' ||
                $dadosLogin['idcliente'] == '377' ||
                $dadosLogin['idcliente'] == '310' ||
                $dadosLogin['idcliente'] == '355' ||
                $dadosLogin['idcliente'] == '430' ||
                $dadosLogin['idcliente'] == '66'  ||
                $dadosLogin['idcliente'] == '218' ||
                $dadosLogin['idcliente'] == '34'  ||
                $dadosLogin['idcliente'] == '602'
            ) {

                $valormaxresgate = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;
                $arrayreturn = array('ValidaDescontos' => array(
                    'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                    'valor_resgate' => fnvalorretorno($valor_resgate, $decimal),
                    'maximoresgate' => fnvalorretorno($valormaxresgate, $decimal),
                    'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                    'msgerro' => 'Valor de R$' . fnvalorretorno($valor_resgate, $decimal) . ' pode ser resgatado com sucesso',
                    'coderro' => '52'
                ));
                array_to_xml($arrayreturn, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de R$' . fnvalorretorno($valor_resgate, $decimal) . ' pode ser resgatado com sucesso', addslashes($xml_user_info->asXML()));
            } elseif ($dadosLogin['idcliente'] == '219') {

                $valormaxresgate = (fnFormatvalor($valortotalliquido, $dec) * $resgresult['PCT_MAXRESG']) / 100;
                $arrayreturn = array('ValidaDescontos' => array(
                    'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                    'valor_resgate' => fnvalorretorno($valor_resgate, $decimal),
                    'maximoresgate' => fnvalorretorno($valormaxresgate, $decimal),
                    'minimoresgate' => fnvalorretorno($resgresult['NUM_MINRESG'], $decimal),
                    'msgerro' => 'Valor de R$' . fnvalorretorno($valormaxresgate, $decimal) . ' pode ser resgatado com sucesso',
                    'coderro' => '52'
                ));
                array_to_xml($arrayreturn, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de R$' . fnvalorretorno($valor_resgate, $decimal) . ' pode ser resgatado com sucesso', addslashes($xml_user_info->asXML()));
            } else {
                $arrayreturn = array('ValidaDescontos' => array(
                    'saldo_disponivel' => fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal),
                    'valor_resgate' => fnvalorretorno($valor_resgate, $decimal),
                    'msgerro' => 'Valor de R$' . fnvalorretorno($valor_resgate, $decimal) . ' pode ser resgatado com sucesso',
                    'coderro' => '52'
                ));
                array_to_xml($arrayreturn, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor de R$' . fnvalorretorno($valor_resgate, $decimal) . ' pode ser resgatado com sucesso', addslashes($xml_user_info->asXML()));
            }
        } else {
            fnmemoriafinal($connUser->connUser(), $cod_men);

            $return = array('ValidaDescontos' => array(
                'msgerro' => 'Valor valortotalliquido ou valor_resgate não preenchido!',
                'coderro' => '53'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Valor valortotalliquido ou valor_resgate não preenchido!', addslashes($xml_user_info->asXML()));
            return $return;
        }
    } else {
        $arrayreturn = array('ValidaDescontos' => array(
            'msgerro' => 'Cliente nao faz parte do fidelidade',
            'coderro' => '54'
        ));
        array_to_xml($arrayreturn, $xml_user_info);
        Grava_log_msgxml($connUser->connUser(), 'msg_desconto', $cod_log, 'Cliente nao faz parte do fidelidade', addslashes($xml_user_info->asXML()));
    }


    //------------------------------------------------------------------------------



    ob_end_flush();
    ob_flush();
    fnmemoriafinal($connUser->connUser(), $cod_men);
    return  $arrayreturn;
}
