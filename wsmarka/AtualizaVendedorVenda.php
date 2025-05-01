<?php
$server->wsdl->addComplexType(
    'AtualizaVendedorVenda',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);
$server->register(
    'AtualizavendedorVenda',
    array(
        'id_vendapdv' => 'xsd:string',
        'CPFCNPJ'     => 'xsd:string',
        'cartao'        => 'xsd:string',
        'codatendente' => 'xsd:string',
        'codvendedor' => 'xsd:string',
        'cupomfiscal' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('AtualizavendedorVendaResponse' => 'tns:AtualizavendedorVenda'),  //output
    $ns,                                 // namespace
    "$ns/AtualizavendedorVenda",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'AtualizavendedorVenda'                 // documentation
);


function AtualizavendedorVenda($id_vendapdv, $CPFCNPJ, $cartao, $dadosLogin)
{

    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';
    //file_get_contents("php://input")

    ob_start();
    $cartao = fnlimpaCPF($cartao);
    /*$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
        $buscauser=mysqli_query($connAdm->connAdm(),$sql);
        $row = mysqli_fetch_assoc($buscauser);
*/
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




    $dec = $row['NUM_DECIMAIS'];
    if ($row['TIP_RETORNO'] == 2) {
        $decimal = 2;
    } else {
        $casasDec = 0;
    }

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
        return  array('AtualizaVendaResponse' => array(
            'msgerro' => 'LOJA DESABILITADA',
            'coderro' => '80'
        ));
        exit();
    }

    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        //conn user
        $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

        $xmlteste = addslashes(file_get_contents("php://input"));
        $arrylog = array(
            'cod_usuario' => $row['COD_USUARIO'],
            'login' => $dadosLogin['login'],
            'cod_empresa' => $row['COD_EMPRESA'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'cpf' => $cartao,
            'xml' => $xmlteste,
            'tables' => 'origem_atualizavenda',
            'conn' => $connUser->connUser()
        );
        $cod_log = fngravalogxml($arrylog);

        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><AtualizaVendaResponse></AtualizaVendaResponse>");
        //array_to_xml($return,$xml_user_info);
        //Grava_log($connUser->connUser(),$LOG,'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));


        //  $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'AtualizaVenda',$dadosLogin['idcliente']);

        //================================================================================================================ 
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            $return = array('AtualizaVendaResponse' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'Oh não! A empresa foi desabilitada por algum motivo ;-[!', addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            $return = array('AtualizaVendaResponse' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '5'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'A empresa foi desabilitada por algum motivo', addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }
        //Permite alteração de venda
        if ($row['LOG_ALTVENDA'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Oh não! A empresa não permite alteração na venda ;-[!', $row['LOG_WS']);
            $return = array('AtualizaVendaResponse' => array(
                'msgerro' => 'Oh não! A empresa não permite alteração na venda ;-[!',
                'coderro' => '75'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'Oh não! A empresa não permite alteração na venda ;-[!', addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }


        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return  array('AtualizaVendaResponse' => array(
                'msgerro' => 'Id_cliente não confere com o cadastro!',
                'coderro' => '4'
            ));
            exit();
        }
    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('AtualizaVendaResponse' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }
    if ($cartao == '0' || $cartao == '') {

        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Não possivel alterar venda avulsa!', $row['LOG_WS']);
        $return = array('AtualizaVendaResponse' => array(
            'msgerro' => 'Não possivel alterar venda avulsa!',
            'coderro' => '76'
        ));
        array_to_xml($return, $xml_user_info);
        Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'Não possivel alterar venda avulsa!', addslashes($xml_user_info->asXML()));
        return $return;

        exit();
    }
    //verifica se a venda existe    
    $checkvenda = "select v.COD_CLIENTE as cod_clien_venda,c.COD_CLIENTE,v.COD_VENDA from vendas v 
                left join clientes c on c.COD_CLIENTE = v.cod_cliente
                where v.COD_VENDAPDV='$id_vendapdv'";
    $restunvenda = mysqli_query($connUser->connUser(), $checkvenda);
    if (mysqli_num_rows($restunvenda) != 1) {

        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'id_vendapdv ' . $id_vendapdv . ' não exite!', $row['LOG_WS']);
        $return = array('AtualizaVendaResponse' => array(
            'msgerro' => 'id_vendapdv ' . $id_vendapdv . ' não exite!',
            'coderro' => '77'
        ));
        array_to_xml($return, $xml_user_info);
        Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'id_vendapdv ' . $id_vendapdv . ' não exite!', addslashes($xml_user_info->asXML()));
        return $return;

        exit();
    } else {
        $rsvenda = mysqli_fetch_assoc($restunvenda);
    }

    //busca cliente  na base de dados    
    $arraydadosbusca = array(
        'empresa' => $dadosLogin['idcliente'],
        'cartao' => $cartao,
        'cpf' => $cartao,
        'venda' => 'venda',
        'ConnB' => $connUser->connUser()
    );
    $cliente_cod = fn_consultaBase($arraydadosbusca);

    if ($rsvenda['COD_CLIENTE'] != "") {

        if ($cliente_cod['COD_CLIENTE'] != $rsvenda['COD_CLIENTE']) {

            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaVenda', 'Venda não pertence a esse cliente', $row['LOG_WS']);
            $return = array('AtualizaVendaResponse' => array(
                'msgerro' => 'Venda não pertence a esse cliente',
                'coderro' => '78'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'Venda não pertence a esse cliente', addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }
    }

    $clientealterproc = mysqli_query($connUser->connUser(),  "CALL SP_ATUALIZA_VENDA('$cartao', '$id_vendapdv')");
    if (!$clientealterproc) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($connUser->connUser(), "CALL SP_ATUALIZA_VENDA('$cartao', '$id_vendapdv')");
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "Error description Altera venda Erro: $msgsql";
        $xamls = addslashes($msg);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Alteravenda', $xamls, $row['LOG_WS']);
    }

    //fnmemoriafinal($connUser->connUser(),$cod_men);   
    ob_end_flush();
    ob_flush();

    $return = array('AtualizaVendaResponse' => array(
        'msgerro' => 'OK',
        'coderro' => '39'
    ));
    array_to_xml($return, $xml_user_info);
    Grava_log_msgxml($connUser->connUser(), 'msg_atualizavenda', $cod_log, 'OK', addslashes($xml_user_info->asXML()));
    return $return;
}
