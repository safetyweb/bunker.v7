<?php
$server->wsdl->addComplexType(
    'ValidaDescontoItemRequest',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'ValorTotalVendaResgate' => array('name' => 'ValorTotalVendaResgate', 'type' => 'xsd:string'),
        'saldo_disponivel' => array('name' => 'saldo_disponivel', 'type' => 'xsd:string'),
        'valor_resgate' => array('name' => 'valor_resgate', 'type' => 'xsd:string'),
        'minimoresgate' => array('name' => 'minimoresgate', 'type' => 'xsd:string'),
        'maximoresgate' => array('name' => 'maximoresgate', 'type' => 'xsd:string'),
        'ItemValido' => array('minOccurs' => '0', 'maxOccurs' => 'unbounded', 'name' => 'ItemValido', 'type' => 'tns:ItemValido'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:string')
    )
);
$server->wsdl->addComplexType(
    'ItemValido',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ItemResgate' => array('name' => 'ItemResgate', 'type' => 'xsd:string'),
        'CodigoExternoItem' => array('name' => 'CodigoExternoItem', 'type' => 'xsd:string'),
        'ValorItem' => array('name' => 'ValorItem', 'type' => 'xsd:string'),
        'Quantidade' => array('name' => 'Quantidade', 'type' => 'xsd:string'),
        'ValorResgateItem' => array('name' => 'ValorResgateItem', 'type' => 'xsd:string'),
        'msgerro' => array('name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('name' => 'coderro', 'type' => 'xsd:string')
    )
);
$server->register(
    'ValidaDescontoItem',
    array(
        'id_token' => 'xsd:string',
        'cpf' => 'xsd:string',
        'id_vendapdv' => 'xsd:string',
        'valor_resgate' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('ValidaDescontoItemRequest' => 'tns:ValidaDescontoItemRequest'),  //output
    $ns,                                 // namespace
    "$ns/ValidaDescontoItem",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'token_regate'                 // documentation
);

function ValidaDescontoItem($id_token, $cpf, $id_vendapdv, $valor_resgate, $dadosLogin)
{
    include_once '../_system/Class_conn.php';
    include_once './func/function.php';
    ob_start();
    $cpf = fnlimpaCPF($cpf);
    $cartao = fnlimpaCPF($cpf);
    /* $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
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

    //$dec=$row['NUM_DECIMAIS']; 
    //compara os id_cliente com o cod_empresa
    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);


    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        $cod_men = fnmemoria($connUser->connUser(), 'true', $dadosLogin['login'], 'listaEstadoCivil', $row['COD_EMPRESA']);

        //verifica id_empresa
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return array('ValidaDescontoItemRequest' => array('msgerro' => 'Id_cliente não confere com o cadastro!'));
            exit();
        }

        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('ValidaDescontoItemRequest' => array('msgerro' => 'Oh não! Usuario foi desabilitado ;-[!'));
            exit();
        }
        //////////////////////=================================================================================================================
        //empresa desabilidatada

        if ($row['LOG_ATIVO'] != 'S') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-[', $row['LOG_WS']);
            return array('ValidaDescontoItemRequest' => array('msgerro' => 'Oh não :-o! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
            exit();
        }
    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'verificavenda', 'Erro Na autenticação!', $row['LOG_WS']);
        return array('ValidaDescontoItemRequest' => array('msgerro' => 'Erro Na autenticação!'));
    }
    $xmlteste = addslashes(file_get_contents("php://input"));
    $arrylog = array(
        'cod_usuario' => $row['COD_USUARIO'],
        'login' => $dadosLogin['login'],
        'cod_empresa' => $row['COD_EMPRESA'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'cpf' => $cpf,
        'xml' => $xmlteste,
        'tables' => 'origemdescontoitem',
        'conn' => $connUser->connUser()
    );
    $cod_log = fngravalogxml($arrylog);
    //Consultando cliente 
    //busca cliente  na base de dados    
    $arraydadosbusca = array(
        'empresa' => $dadosLogin['idcliente'],
        'cartao' => '',
        'cpf' => $cpf,
        'venda' => '',
        'ConnB' => $connUser->connUser()
    );

    $cliente_cod = fn_consultaBase($arraydadosbusca);
    if ($cliente_cod['COD_CLIENTE'] == '' || $cliente_cod['COD_CLIENTE'] == '0') {
        return array('ValidaDescontoItemRequest' => array(
            'msgerro' => 'CPF ou cartao nao especificado!',
            'coderro' => '208'
        ));
    }

    /*if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;} */
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

    //começa a buscar os itens para somatorio
    $sumitens = "SELECT 
                ven.cod_univend,
                item.COD_VENDA,
                item.cod_produto,
                prc.DES_PRODUTO,
                Item.cod_externo,
                item.cod_cliente,
                item.qtd_produto,
                item.val_unitario,
                truncate(item.val_totitem,2) val_totitem     
        FROM VENDAS_DESC ven
        INNER JOIN ITEMVENDA_DESC item ON item.cod_venda=ven.cod_venda
        INNER JOIN produtocliente prc ON prc.COD_PRODUTO=item.cod_produto
        WHERE ven.cod_empresa=" . $dadosLogin['idcliente'] . " 
             AND ven.cod_vendapdv='" . $id_vendapdv . "'
             AND ven.cod_univend='" . $dadosLogin['idloja'] . "'
             AND item.cod_produto NOT IN (SELECT cod_produto from PRODUTO_SEM_RESGATE WHERE cod_empresa=" . $dadosLogin['idcliente'] . ")";
    $rwsumitem = mysqli_query($connUser->connUser(), $sumitens);
    while ($rssumitem = mysqli_fetch_assoc($rwsumitem)) {
        //valor de venda resgate
        $val_totitem += $rssumitem['val_totitem'];

        if (isset($rssumitem['val_totitem'])) {
            $item[] = array(
                'ItemResgate' => $rssumitem['DES_PRODUTO'],
                'CodigoExternoItem' => $rssumitem['cod_externo'],
                'ValorItem' => fnvalorretorno($rssumitem['val_unitario'], $dec),
                'Quantidade' => fnvalorretorno($rssumitem['qtd_produto'], $dec),
                'ValorResgateItem' => '0,00',
                'msgerro' => 'Valor pode ser resgatado!',
                'coderro' => '84'
            );
        } else {
            $item[] = array(
                'msgerro' => 'Ops, nao a itens validos para resgate!',
                'coderro' => '85'
            );
        }
    }

    //=================validar o resgate====================
    if ($id_token != '') {
        //revalidar token
        $SQLTOKEN = "SELECT * FROM TOKEN_RESGATE WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND DES_TOKEN='" . $id_token . "' $andcpf";
        $RSTOKEN = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $SQLTOKEN));
        if ($id_token == '') {
            return array('ValidaDescontoItemRequest' => array(
                'msgerro' => "Token não preenchido!",
                'coderro' => '504'
            ));
        }
        if ($RSTOKEN['COD_MSG'] != '0') {
            return array('ValidaDescontoItemRequest' => array(
                'msgerro' => "Token $id_token ja utilizado!",
                'coderro' => '502'
            ));
        }
        if ($RSTOKEN['COD_MSG'] == '0' && fncompletadoc($RSTOKEN['NUM_CGCECPF']) != fncompletadoc(fnlimpaCPF($cpf))) {
            return array('ValidaDescontoItemRequest' => array(
                'msgerro' => "Token $id_token pertence a outro cliente!",
                'coderro' => '501'
            ));
        }
        $TOKEMSQL = "DES_TOKEN=$id_token";
    } else {
        $num_cgcecpf = "NUM_CGCECPF=$cpf";
        $TOKEMSQL = '';
    }




    $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $cliente_cod['COD_CLIENTE'] . ")";
    $rsrown = mysqli_query($connUser->connUser(), $consultasaldo);
    $retSaldo = mysqli_fetch_assoc($rsrown);
    mysqli_free_result($retSaldo);
    mysqli_next_result($connUser->connUser());


    //busca valor de configurados para resgates
    $regraresgate = 'SELECT round(min(CR.NUM_MINRESG),' . $dec . ') as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
              INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
              WHERE LOG_ATIVO="S" AND C.cod_empresa=' . $dadosLogin['idcliente'];
    $resgresult = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $regraresgate));
    //==========================================================================
    $arrayvalorres = array(
        'vl_venda' => fnValorSQL($val_totitem, $dec),
        'PCT_MAXRESG' =>  $resgresult['PCT_MAXRESG']
    );
    $percentual = fnVerificasaldo($arrayvalorres);

    if (fnFormatvalor($valor_resgate, $dec) >  fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {

        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o disponivel', $row['LOG_WS']);
        $vl_msg['ValorTotalVendaResgate'] = fnFormatvalor($val_totitem, $dec);
        $vl_msg['saldo_disponivel'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec) - '0.01';
        $vl_msg['valor_resgate'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec);
        $vl_msg['maximoresgate'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec);
        $vl_msg['minimoresgate'] = fnFormatvalor($resgresult['NUM_MINRESG'], $dec);
        $vl_msg['msgerro'] = 'Valor Resgate R$' . fnvalorretorno($valor_resgate, $decimal) . ' maior que o credito disponivel  R$' . fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $decimal) . '';
        $vl_msg['coderro'] = '51';
    } elseif (fnValorSQL($valor_resgate, $dec) > fnValorSQL($percentual, $dec)) {

        if (fnValorSQL($percentual, $dec) > fnValorSQL($resgresult['NUM_MINRESG'], $dec)) {

            $VALORMAXVENDA = (fnFormatvalor($val_totitem, $dec) * $resgresult['PCT_MAXRESG']) / 100;

            // if(fncompletadoc(fnlimpaCPF($cpf))=='40332253821')
            //{
            if ($VALORMAXVENDA > fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {
                $VALORMAXVENDA = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec);
                // return array('ValidaDescontoItemRequest'=>array('ValorTotalVendaResgate'=>$VALORMAXVENDA.'--'. fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'],$dec)));
            }
            //}   


            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
            $vl_msg['ValorTotalVendaResgate'] = fnFormatvalor($val_totitem, $dec);
            $vl_msg['saldo_disponivel'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec) - '0.01';
            $vl_msg['valor_resgate'] = $VALORMAXVENDA;
            $vl_msg['maximoresgate'] = $VALORMAXVENDA;
            $vl_msg['minimoresgate'] = fnFormatvalor($resgresult['NUM_MINRESG'], $dec);

            //$vl_msg['msgerro']='Resgate  permitido para essa compra é de R$'. fnvalorretorno(fnFormatvalor($VALORMAXVENDA,$decimal),$decimal).'';
            $vl_msg['msgerro'] = 'Valor de R$ ' . fnvalorretorno($VALORMAXVENDA, $decimal) . ' pode ser resgatado com sucesso';
            $vl_msg['coderro'] = '52';
            $monitorvalor = "UPDATE token_resgate SET VAL_RESGATE='" . $valor_resgate . "' WHERE COD_MSG=0 and COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND  $num_cgcecpf $TOKEMSQL;";
            mysqli_query($connUser->connUser(), $monitorvalor);
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
            $vl_msg['ValorTotalVendaResgate'] = fnValorSQL($val_totitem, $dec);
            $vl_msg['saldo_disponivel'] = fnValorSQL($retSaldo['CREDITO_DISPONIVEL'], $dec) - '0.01';
            $vl_msg['valor_resgate'] = fnValorSQL($valor_resgate, $dec);
            $vl_msg['maximoresgate'] = fnValorSQL('0.00', $dec);
            $vl_msg['minimoresgate'] = fnValorSQL($resgresult['NUM_MINRESG'], $dec);
            $vl_msg['msgerro'] = 'Resgate Fora do padrão';
            $vl_msg['coderro'] = '49';
        }
    } elseif (fnValorSQL($valor_resgate, $dec) < $resgresult['NUM_MINRESG']) {

        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate não pode ser menor que o permitido', $row['LOG_WS']);
        $vl_msg['ValorTotalVendaResgate'] = fnValorSQL($val_totitem, $dec);
        $vl_msg['saldo_disponivel'] = fnValorSQL($retSaldo['CREDITO_DISPONIVEL'], $dec);
        $vl_msg['valor_resgate'] = fnValorSQL($valor_resgate, $dec);
        $vl_msg['maximoresgate'] = fnValorSQL($percentual, $dec);
        $vl_msg['minimoresgate'] = fnValorSQL($resgresult['NUM_MINRESG'], $dec);
        $vl_msg['msgerro'] = 'Valor Resgate não pode ser menor que o permitido R$ ' . fnvalorretorno($resgresult['NUM_MINRESG'], $decimal);
        $vl_msg['coderro'] = '50';
    } else {
        if (fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $decimal) > fnFormatvalor($val_totitem, $decimal)) {
            $VALORMAXVENDA = (fnFormatvalor($val_totitem, $dec) * $resgresult['PCT_MAXRESG']) / 100;
            $vl_msg['ValorTotalVendaResgate'] = fnFormatvalor($val_totitem, $dec);
            $vl_msg['saldo_disponivel'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec) - '0.01';
            $vl_msg['valor_resgate'] = $VALORMAXVENDA;
            $vl_msg['maximoresgate'] = $VALORMAXVENDA;
            $vl_msg['minimoresgate'] = fnValorSQL($resgresult['NUM_MINRESG'], $dec);
            $retornosaldo = fnFormatvalor($VALORMAXVENDA, $decimal);
            $vl_msg['msgerro'] = 'Valor1 de R$ ' . fnvalorretorno($retornosaldo, $decimal) . ' pode ser resgatado com sucesso';
            $vl_msg['coderro'] = '52';
            //alteração no valor de resgate
            $monitorvalor = "UPDATE token_resgate SET VAL_RESGATE='" . $valor_resgate . "' WHERE COD_MSG=0 and COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND  $num_cgcecpf $TOKEMSQL;";
            mysqli_query($connUser->connUser(), $monitorvalor);
        } else {

            $vl_msg['ValorTotalVendaResgate'] = fnFormatvalor($val_totitem, $decimal);
            $vl_msg['saldo_disponivel'] = fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $decimal) - '0.01';
            $vl_msg['valor_resgate'] = fnFormatvalor($valor_resgate, $decimal);
            $vl_msg['maximoresgate'] = fnFormatvalor($valor_resgate, $decimal);
            $vl_msg['minimoresgate'] = fnFormatvalor($resgresult['NUM_MINRESG'], $decimal);
            $vl_msg['msgerro'] = 'Valor de R$' . fnvalorretorno(fnFormatvalor($valor_resgate, $decimal), $decimal) . ' pode ser resgatado com sucesso';
            $vl_msg['coderro'] = '52';
            //alteração no valor de resgate
            $monitorvalor = "UPDATE token_resgate SET VAL_RESGATE='" . fnValorSQL($valor_resgate, $decimal) . "' WHERE COD_MSG=0 and COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND  $num_cgcecpf $TOKEMSQL;";
            mysqli_query($connUser->connUser(), $monitorvalor);
        }
    }

    ob_end_flush();
    ob_flush();
    fnmemoriafinal($connUser->connUser(), $cod_men);
    mysqli_close($connAdm->connAdm());
    mysqli_close($connUser->connUser());

    // print_r($itn);                                         
    return array(
        'ValidaDescontoItemRequest' => array(
            'ValorTotalVendaResgate' => fnvalorretorno($vl_msg['ValorTotalVendaResgate'], $decimal),
            'saldo_disponivel' => fnvalorretorno($vl_msg['saldo_disponivel'], $decimal),
            'valor_resgate' => fnvalorretorno($vl_msg['valor_resgate'], $decimal),
            'minimoresgate' => fnvalorretorno($vl_msg['minimoresgate'], $decimal),
            'maximoresgate' => fnvalorretorno($vl_msg['maximoresgate'], $decimal),
            'ItemValido' => $item,
            'msgerro' => $vl_msg['msgerro'],
            'coderro' => $vl_msg['coderro']
        )

    );
}
