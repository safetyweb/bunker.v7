<?php
//retorno geral
$server->wsdl->addComplexType(
    'listaTicket',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'ofertasTicket' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'produtoTicket', 'type' => 'tns:produtoTicket'),
        'ofertasHabito' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'produtoHabito', 'type' => 'tns:produtoHabito'),
        'ofertasPromocao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'produtoPromocao', 'type' => 'tns:produtoPromocao'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);
//=======================================================
//Ofertas em destaque retorno
$server->wsdl->addComplexType(
    'produtoPromocao',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoPromocao' => array('minOccurs' => '0', 'maxOccurs' => 'unbounded', 'name' => 'RetornoProdutosOfertas', 'type' => 'tns:RetornoProdutosOfertas'))
);
$server->wsdl->addComplexType(
    'RetornoProdutosOfertas',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cdigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);
//============================
//HABITO DE COMPRAS retorno
$server->wsdl->addComplexType(
    'produtoHabito',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoHabito' => array('minOccurs' => '0', 'maxOccurs' => 'unbounded', 'name' => 'RetornoHabitos', 'type' => 'tns:RetornoHabitos'))
);
$server->wsdl->addComplexType(
    'RetornoHabitos',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'descricao', 'type' => 'xsd:string'),
        'preco' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);
//========================
//Retorno tkt
$server->wsdl->addComplexType(
    'produtoTicket',
    'complexType',
    'struct',
    'sequence',
    '',
    array('produtoTicket' => array('minOccurs' => '0', 'maxOccurs' => 'unbounded', 'name' => 'RetornoTKT', 'type' => 'tns:RetornoTKT'))
);
$server->wsdl->addComplexType(
    'RetornoTKT',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'codigoexterno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigoexterno', 'type' => 'xsd:integer'),
        'codigointerno' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cdigointerno', 'type' => 'xsd:integer'),
        'ean' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ean', 'type' => 'xsd:integer'),
        'descricao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'descricao', 'type' => 'xsd:string'),
        'categoria' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'categoria', 'type' => 'xsd:string'),
        'preco' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'preco', 'type' => 'xsd:string'),
        'valorcomdesconto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valorcomdesconto', 'type' => 'xsd:string'),
        'imagem' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'imagem', 'type' => 'xsd:string'),
        'msgpromocional' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgpromocional', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);

//==============================================================
$server->register(
    'GetProdutosTicket',
    array(
        'CPFCARTAO' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('listaTicket' => 'tns:listaTicket'),  //output
    $ns,                                 // namespace
    "$ns/OfertaProduto",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'OfertaProduto'                 // documentation
);


function GetProdutosTicket($CPFCARTAO, $dadosLogin)
{
    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';

    //limpa campo cpfcartao
    $cartao = fnlimpaCPF($CPFCARTAO);
    // if ( valida_cpf($cartao) ) {}else{ return  array('listaTicket'=>array('msgerro'=>'Erro na lista do Ticket!'));exit();}
    if ($cartao != "") {
        $cartao = fnlimpaCPF($CPFCARTAO);
    } else {
        $cartao = 0;
    }

    if ($cartao == 0) {
        return  array('listaTicket' => array('msgerro' => 'Cliente avulso nao gerara ticket!'));
        exit();
    }

    //  ob_start();
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        return  array('listaTicket' => array('msgerro' => 'LOJA DESABILITADA'));
        exit();
    }

    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);


    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {


        $cod_men = fnmemoria($connUser->connUser(), 'true', $dadosLogin['login'], 'OfertasLista', $row['COD_EMPRESA']);


        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {

            return  array('listaTicket' => array('msgerro' => 'Id_cliente não confere com o cadastro!'));
            exit();
        }
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            return  array('listaTicket' => array('msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!'));
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {

            return  array('listaTicket' => array('msgerro' => 'Oh não! Usuario foi desabilitado ;-[!'));
            exit();
        }
        //////////////////////=================================================================================================================

    } else {

        return  array('listaTicket' => array('msgerro' => 'Usuario ou senha Inválido!'));
        exit();
    }

    //busca de cliente
    $dadosbase = fn_consultaBase($connUser->connUser(), trim($cartao), '', trim($cartao), '', '', $row['COD_EMPRESA']);
    //=========================

    ////////////////////////////////////////////////////////////////////////////

    if ($dadosbase[0]['COD_CLIENTE'] != '') {

        $arrayDados = array(
            'cod_empresa' => $row['COD_EMPRESA'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'cpf' => $cartao,
            'cartao' => $cartao,
            'cnpj' => '',
            'id_cliente' => $dadosbase[0]['COD_CLIENTE'],
            'login' => $dadosLogin['login'],
            'codvendedor' => $dadosLogin['codvendedor'],
            'nomevendedor' => $dadosLogin['nomevendedor'],
            'pagina' => 'Busca_antiga',
            'connadm' => $connAdm->connAdm(),
            'connempresa' => $connUser->connUser(),
            'cod_user' => $row['COD_USUARIO'],
            'DECIMAL' => $row['NUM_DECIMAIS']
        );
        $fngeratkt = fngeratktlista($arrayDados);
        if ($fngeratkt['produtoHabito'] == '') {
            $fngeratkt['produtoHabito'][] = array(
                'codigoexterno' => 'null',
                'codigointerno' => 'null',
                'descricao' => 'null',
                'msgerro' => 'Nao ha habito de compras!'
            );
            $t .= 1;
        }
        if ($fngeratkt['produtoTicket'] == '') {
            $fngeratkt['produtoTicket'][] = array(
                'num_ordenac' => 'null',
                'codigoexterno' => 'null',
                'codigointerno' => 'null',
                'descricao' => 'null',
                'categoria' => 'null',
                'ean' => 0,
                'preco' => 'null',
                'valorcomdesconto' => 'null',
                'desconto' => 'null',
                'imagem' => 'null',
                'msgerro' => 'Nao ha produtos em oferta!'

            );
            $t .= 1;
        }
        if ($fngeratkt['produtoPromocao'] == '') {
            $fngeratkt['produtoPromocao'][] = array(
                'codigoexterno' => 'null',
                'codigointerno' => 'null',
                'descricao' => 'null',
                'ean' => 0,
                'preco' => 0,
                'valorcomdesconto' => 0,
                'imagem' => 'null',
                'msgerro' => 'Nao ha produtos em Destaque!'
            );
            $t .= 1;
        }
        if ($t == '111') {
            $msg = 'Não foram encontrador produtos!';
        }
    } else {
        fnmemoriafinal($connUser->connUser(), $cod_men);
        return  array('listaTicket' => array('msgerro' => 'Cliente não cadastrado!'));
        exit();
    }


    ///////////////////////////////////                           
    //================================================================                
    ob_end_flush();
    ob_flush();
    fnmemoriafinal($connUser->connUser(), $cod_men);

    // return $fngeratkt;  
    return  array(
        'listaTicket' => array(
            'ofertasHabito' => array('produtoHabito' => $fngeratkt['produtoHabito']),
            'ofertasTicket' => array('produtoTicket' => $fngeratkt['produtoTicket']),
            'ofertasPromocao' => array('produtoPromocao' => $fngeratkt['produtoPromocao'])
        ),
        'msgerro' => $msg,
        'coderro' => '1'

    );
}
