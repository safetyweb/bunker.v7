<?php
//soap enc array java  import wsld netbea
$server->wsdl->addComplexType(
    'vendedor_atendente',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:string')

    )
);
$server->register(
    'InsereVendedor',
    array(
        'Cod_Atendente' => 'xsd:string',
        'Nome_atendente' => 'xsd:string',
        'Cod_vendedor' => 'xsd:string',
        'Nome_vendedor' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('retorna_vendedor' => 'tns:vendedor_atendente'),  //output
    $ns,                                 // namespace
    "$ns/vendedor_atendente",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'vendedor_atendente'                 // documentation
);

function InsereVendedor($Cod_Atendente, $Nome_atendente, $Cod_vendedor, $Nome_vendedor, $dadosLogin)
{
    $encodingat = mb_detect_encoding($Nome_atendente, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    // Se a codificação não for UTF-8, converte automaticamente
    if ($encodingat && $encodingat !== 'UTF-8') {
        $Nome_atendente = mb_convert_encoding($Nome_atendente, 'UTF-8', $encodingat);
    }

    $encodingve = mb_detect_encoding($Nome_vendedor, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    // Se a codificação não for UTF-8, converte automaticamente
    if ($encodingve && $encodingve !== 'UTF-8') {
        $Nome_vendedor = mb_convert_encoding($Nome_vendedor, 'UTF-8', $encodingve);
    }
    include_once '../_system/Class_conn.php';
    include_once './func/function.php';
    ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);


    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        //  $cod_men= fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'listaEstadoCivil',$row['COD_EMPRESA']);

        //verifica id_empresa
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return array('retorna_vendedor' => array('msgerro' => 'Id_cliente não confere com o cadastro!'));
            exit();
        }

        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('retorna_vendedor' => array('msgerro' => 'Oh não! Usuario foi desabilitado ;-[!'));
            exit();
        }
        //////////////////////=================================================================================================================
        //empresa desabilidatada

        if ($row['LOG_ATIVO'] != 'S') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[', $row['LOG_WS']);
            return array('retorna_vendedor' => array('msgerro' => 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
            exit();
        }
    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Erro Na autenticação!', $row['LOG_WS']);
        return array('retorna_vendedor' => array(
            'msgerro' => 'Erro Na autenticação!',
            'coderro' => '48'
        ));
    }
    //grava log
    $xmlteste = addslashes(file_get_contents("php://input"));
    $arrylog = array(
        'cod_usuario' => $row['COD_USUARIO'],
        'login' => $dadosLogin['login'],
        'cod_empresa' => $row['COD_EMPRESA'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'cpf' => '0',
        'xml' => $xmlteste,
        'tables' => 'origemvendedor',
        'conn' => $connUser->connUser()
    );
    $cod_log = fngravalogxml($arrylog);
    //fim
    //verificar os atendente.
    if ($Cod_Atendente != '' || $Cod_Atendente != '0' && fnAcentos($Nome_atendente) !== '') {
        $sqlatendente = "select COD_DEFSIST
            from usuarios where COD_EMPRESA='" . $row['COD_EMPRESA'] . "' and  COD_TPUSUARIO =11  and  COD_EXTERNO='" . $Cod_Atendente . "' and FIND_IN_SET('" . $dadosLogin['idloja'] . "',COD_UNIVEND)";
        $rwatendente = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlatendente));
        if ($rwatendente['COD_DEFSIST'] == '') {
            $COD_DEFSIST = '4';
        } else {
            $COD_DEFSIST = $rwatendente['COD_DEFSIST'];
        }
        //cadastrar o atendente
        $Atendente = "CALL SP_ALTERA_VENDEDOR('" . $row['COD_EMPRESA'] . "', '" . fnAcentos($Nome_atendente) . "', '11', '" . $dadosLogin['idloja'] . "', '" . $dadosLogin['idloja'] . "', '" . $COD_DEFSIST . "', '" . $Cod_Atendente . "');";
        $rwAtendente = mysqli_query($connAdm->connAdm(), $Atendente);
        if (!$rwAtendente) {
            return array('retorna_vendedor' => array(
                'msgerro' => 'Problema na atualizacao do vendedor ou inserção do registro' . $Atendente,
                'coderro' => '87'
            ));
        }
    }
    if ($Cod_vendedor != '' || $Cod_vendedor != '0' && fnAcentos($Nome_vendedor) !== '') {
        //cadastrar o vendedor
        $sqlatendente = "select COD_DEFSIST
            from usuarios where COD_EMPRESA='" . $row['COD_EMPRESA'] . "' and COD_TPUSUARIO =7  and   COD_EXTERNO='" . $Cod_vendedor . "' and FIND_IN_SET('" . $dadosLogin['idloja'] . "',COD_UNIVEND)";
        $rwatendente = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlatendente));
        if ($rwatendente['COD_DEFSIST'] == '') {
            $COD_DEFSIST = '4';
        } else {
            $COD_DEFSIST = $rwatendente['COD_DEFSIST'];
        }

        //cadastrar o atendente
        $vendedor = "CALL SP_ALTERA_VENDEDOR('" . $row['COD_EMPRESA'] . "', '" . fnAcentos($Nome_vendedor) . "', '7', '" . $dadosLogin['idloja'] . "', '" . $dadosLogin['idloja'] . "', '" . $COD_DEFSIST . "', '" . $Cod_vendedor . "');";
        $rwvendedor = mysqli_query($connAdm->connAdm(), $vendedor);
        if (!$rwvendedor) {
            return array('retorna_vendedor' => array(
                'msgerro' => 'Problema na atualizacao do atendente ou inserção do registro' . $vendedor,
                'coderro' => '87'
            ));
        }
    }

    ob_end_flush();
    ob_flush();
    //fnmemoriafinal($connUser->connUser(),$cod_men);  
    mysqli_close($connAdm->connAdm());
    mysqli_close($connUser->connUser());

    // print_r($itn);                                         
    return array('retorna_vendedor' => array(
        'msgerro' => 'OK',
        'coderro' => '39'
    ));
}
