<?php
//inserir venda
//=================================================================== InserirVenda ==================================================================================
//retorno dados venda
$server->wsdl->addComplexType(
    'dadosdavenda',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'nome' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'nome', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:string'),
        'saldo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldo', 'type' => 'xsd:string'),
        'saldoresgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'saldoresgate', 'type' => 'xsd:string'),
        'creditovenda' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'creditovenda', 'type' => 'xsd:string'),
        'comprovante' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'comprovante', 'type' => 'xsd:string'),
        'comprovante_resgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'comprovante_resgate', 'type' => 'xsd:string'),
        'url' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'url', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string')
    )
);

// venda
$server->wsdl->addComplexType(
    'venda',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'id_vendapdv' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'id_vendapdv', 'type' => 'xsd:string'),
        'datahora' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'datahora', 'type' => 'xsd:string'),
        'cartao' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartao', 'type' => 'xsd:string'),
        'valortotal' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valortotal', 'type' => 'xsd:string'),
        'valor_resgate' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valor_resgate', 'type' => 'xsd:string'),
        'cupom' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cupom', 'type' => 'xsd:string'),
        'formapagamento' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'formapagamento', 'type' => 'xsd:string'),
        'cartaoamigo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'cartaoamigo', 'type' => 'xsd:string'),
        'pontosextras' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'pontosextras', 'type' => 'xsd:string'),
        'naopontuar' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'naopontuar', 'type' => 'xsd:string'),
        'entrega' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'entrega', 'type' => 'xsd:string'),
        'codatendente' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codatendente', 'type' => 'xsd:string'),
        'codvendedor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codvendedor', 'type' => 'xsd:string'),
        'pontostotal' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'pontostotal', 'type' => 'xsd:string'),
        'items' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'items', 'type' => 'tns:items')
    )
);
//array de itens
$server->wsdl->addComplexType(
    'items',
    'complexType',
    'struct',
    'sequence',
    '',
    array('vendaitem' => array('minOccurs' => '0', 'maxOccurs' => 'unbounded', 'name' => 'vendaitem', 'type' => 'tns:vendaitem'))

);




// itens da venda array ^

$server->wsdl->addComplexType(
    'vendaitem',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'id_item' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'id_item', 'type' => 'xsd:string'),
        'produto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'produto', 'type' => 'xsd:string'),
        'codigoproduto' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigoproduto', 'type' => 'xsd:string'),
        'quantidade' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'quantidade', 'type' => 'xsd:string'),
        'valor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'valor', 'type' => 'xsd:string'),
        'naopontuar' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'naopontuar', 'type' => 'xsd:string')

    )
);



//Registro para parassar os dados pra a função inserir venda
$server->register(
    'InserirVenda',
    array(
        'venda' => 'tns:venda',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('InserirVendaResult' => 'tns:dadosdavenda'),  //output
    $ns,                                 // namespace
    "$ns/InserirVenda",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'InserirVenda'
);  //description


function InserirVenda($InserirVenda, $dadosLogin)
{

    include_once '../_system/Class_conn.php';
    include_once './func/function.php';

    fnAcentos(rtrim(trim($InserirVenda['codatendente'])));
    $Cartaows = fnlimpaCPF($InserirVenda['cartao']);
    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><InserirVendaResult></InserirVendaResult>");

    if ($dadosLogin['idcliente'] != '21' && $dadosLogin['idcliente'] != '202' && $dadosLogin['idcliente'] != '495' && $dadosLogin['idcliente'] != '598') {
        $dataformat1 = date('Y-m-d', strtotime(str_replace('/', '-', $InserirVenda['datahora'])));
        $datahora1 = DateTime::createFromFormat('Y-m-d', $dataformat1);
        $datahora1 = $datahora1->format('Y-m-d');
        $date1 = new DateTime($datahora1);
        $date2 = new DateTime(date('Y-m-d'));

        $interval = $date1->diff($date2);
        if ($interval->days >= '2') {

            return array('InserirVendaResult' => array(
                'nome' => 'OK',
                'cartao' => '0',
                'saldo' => '0',
                'creditovenda' => '0,00',
                'comprovante' => '0',
                'comprovante_resgate' => '0',
                'url' => '0',
                'msgerro' => 'OK'
            ));
        }
    }



    // echo count($InserirVenda['items']['vendaitem']);

    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
    $row = mysqli_fetch_assoc($buscauser);

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        return  array('InserirVendaResult' => array('msgerro' => 'LOJA DESABILITADA'));
        exit();
    }



    //compara os id_cliente com o cod_empresa
    /* $dec=$row['NUM_DECIMAIS'];
   if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}    */
    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
    $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
                                                    COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
                                                    COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";

    $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
    if (!$RSCONFIGUNI) {
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
            $COD_DATAWS1 = $RWCONFIGUNI['COD_DATAWS'];
        } else {
            //aqui pega da controle de licença
            $dec = $row['NUM_DECIMAIS'];
            if ($row['TIP_RETORNO'] == 2) {
                $decimal = '2';
            } else {
                $decimal = '0';
            }
            $LOG_CADVENDEDOR = $row['LOG_CADVENDEDOR'];
            $COD_DATAWS1 = $row['COD_DATAWS'];
        }
    }

    //memoria log
    $urlextrato = fnEncode(
        $dadosLogin['login'] . ';'
            . $dadosLogin['senha'] . ';'
            . $dadosLogin['idloja'] . ';'
            . $dadosLogin['idmaquina'] . ';'
            . $row['COD_EMPRESA'] . ';'
            . $dadosLogin['codvendedor'] . ';'
            . $dadosLogin['nomevendedor'] . ';'
            . $Cartaows
    );
    //'url'=>"http://extrato.bunker.mk?key=$urlextrato",


    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        if ($row['LOG_ATIVO'] == 'S') {
        } else {
            return array('InserirVendaResult' => array('msgerro' => 'Oh não! Por algum motivo seu login foi desabilitado. Por favor entre em contato com o suporte :-['));
            exit();
        }
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
            'PDV' => $InserirVenda['id_vendapdv'],
            'CPF' => $Cartaows,
            'XML' => file_get_contents("php://input"),
            'cupom' => $InserirVenda['cupom']
        );
        $LOG = fngravaxmlvendas($arraydados1);
        //verifica lojas e maquinas 
        $lojas = fnconsultaLoja($connAdm->connAdm(), $connUser->connUser(), $dadosLogin['idloja'], $dadosLogin['idmaquina'], $row['COD_EMPRESA']);
        // print_r($lojas);


        // EMPRESAS.LOG_ATIVO = > S OU N ATIVA FULL WEBSERVICES,
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            return array('InserirVendaResult' => array('msgerro' => "Id_cliente não confere com o cadastro!"));
            exit();
        }

        // Venda avulsa Desabilitada,
        if ($Cartaows == 0 || $Cartaows == '') {
            if ($row['LOG_AVULSO'] == "N") {
                Grava_log($connUser->connUser(), $LOG, 'Venda avulsa Desabilitada');
                return array('InserirVendaResult' => array('msgerro' => "Venda avulsa Desabilitada"));
                exit();
            }
        }

        $cod_men = fnmemoria($connUser->connUser(), 'true', $dadosLogin['login'], 'Venda', $row['COD_EMPRESA']);


        //verifica se o profissão existe
        $formap = utf8_encode(limitarTextoLimpo(fnAcentos(addslashes($InserirVenda['formapagamento'])), '150'));
        $form = "call SP_VERIFICA_FORMAPAGAMENTO(" . $row['COD_EMPRESA'] . ",'$formap');";
        $formaret = mysqli_query($connUser->connUser(), $form);
        $formapretorno = mysqli_fetch_assoc($formaret);

        /*if($dadosLogin['idcliente'!='7'])
           {  
           $COD_DATAWS="select FORMATO_WS from dataws  where cod_dataws=".$row['COD_DATAWS'];
            $COD_DATAWS=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $COD_DATAWS));
            $datahora=DateTime::createFromFormat($COD_DATAWS['FORMATO_WS'], $InserirVenda['datahora'])->format('Y-m-d H:i:s');
           }*/

        $COD_DATAWS = "select FORMATO_WS from dataws  where cod_dataws=" . $COD_DATAWS1;
        $COD_DATAWS = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $COD_DATAWS));
        $datahora = DateTime::createFromFormat($COD_DATAWS['FORMATO_WS'], $InserirVenda['datahora']);

        if ($datahora === false) {
            Grava_log($connUser->connUser(), $LOG, 'Data configurada deve ser ' . $COD_DATAWS['FORMATO_WS']);
            return array('InserirVendaResult' => array('msgerro' => 'Data configurada deve ser ' . $COD_DATAWS['FORMATO_WS']));
        } else {
            $datahora = $datahora->format('Y-m-d H:i:s');
        }




        $msg1 = valida_campo_vazio($InserirVenda['formapagamento'], 'formapagamento', 'string');
        if (!empty($msg1) || !empty($msg11)) {
            Grava_log($connUser->connUser(), $LOG, $msg1);
            return array('InserirVendaResult' => array('msgerro' => $msg1));
        }
        $msg2 = valida_campo_vazio($dadosLogin['login'], 'login', 'string');
        if (!empty($msg2)) {
            Grava_log($connUser->connUser(), $LOG, $msg2);
            return array('InserirVendaResult' => array('msgerro' => $msg2));
        }

        $msg3 = valida_campo_vazio($dadosLogin['senha'], 'senha', 'string');
        if (!empty($msg3)) {
            Grava_log($connUser->connUser(), $LOG, $msg3);
            return array('InserirVendaResult' => array('msgerro' => $msg3));
        }

        $msg4 = valida_campo_vazio($InserirVenda['id_vendapdv'], 'id_vendapdv', 'string');
        if (!empty($msg4)) {
            Grava_log($connUser->connUser(), $LOG, $msg4);
            return array('InserirVendaResult' => array('msgerro' => $msg4));
        }

        $msg5 = valida_campo_vazio($dadosLogin['idloja'], 'idloja', 'numeric');
        if (!empty($msg5)) {
            Grava_log($connUser->connUser(), $LOG, $msg5);
            return array('InserirVendaResult' => array('msgerro' => $msg5));
        }

        $msg6 = valida_campo_vazio($dadosLogin['idmaquina'], 'idmaquina', 'string');
        if (!empty($msg6)) {
            Grava_log($connUser->connUser(), $LOG, $msg6);
            return array('InserirVendaResult' => array('msgerro' => $msg6));
        }

        if (fndate($datahora) > date("Y-m-d")) {
            Grava_log($connUser->connUser(), $LOG, 'Data da venda maior que a data atual!');
            return array('InserirVendaResult' => array('msgerro' => 'Data da venda maior que a data atual!'));
        }
        //$produto=addslashes($InserirVenda['items']['vendaitem']['produto']);


        //se o cliente nao existir não gera a venda
        $dadosbase = fn_consultaBase($connUser->connUser(), $Cartaows, '', $Cartaows, '', '', $row['COD_EMPRESA']);
        if ($row['LOG_CONSEXT'] == 'S') {
            $doc = fnCompletaDoc($Cartaows);
            if ($Cartaows != 0 || $Cartaows != "00000000000" || strlen($doc) <= 11) {
                if ($dadosbase[0]['COD_CLIENTE'] <= 0 || $dadosbase[0]['COD_CLIENTE'] == "") {
                    if ($row['COD_CHAVECO'] == 1 || $row['COD_CHAVECO'] == 5) {

                        include './func/FunCadAuto.php';
                        $DadosBusCad = array(
                            'cartao' => $doc,
                            'login' => $dadosLogin['login'],
                            'senha' => $dadosLogin['senha'],
                            'idloja' => $dadosLogin['idloja'],
                            'idmaquina' => $dadosLogin['idmaquina'],
                            'idcliente' => $dadosLogin['idcliente'],
                            'codatendente' => $InserirVenda['codatendente'],
                            'codvendedor' => $dadosLogin['codvendedor'],
                            'nomevendedor' => $dadosLogin['nomevendedor'],
                            'connuser' => $connUser->connUser()
                        );
                        $dadosbase = FnCadAuto($DadosBusCad);
                        if (valida_cpf($doc)) {
                            Grava_log($connUser->connUser(), $LOG, ' Cadastro inserido automaticamente na venda....');
                        } else {
                            Grava_log($connUser->connUser(), $LOG, 'CPF DIGITADO É INVALIDO....');
                        }
                    }
                }
            }
        }

        if ($row['LOG_AUTOCAD'] == 'N') {
            if ($dadosbase[0]['contador'] == 0) {
                $msg = 'Cliente não cadastrado';
                $xamls = addslashes($msg);
                Grava_log($connUser->connUser(), $LOG, $xamls);
                return array('InserirVendaResult' => array('msgerro' => 'Cliente não cadastrado'));
                exit();
            }
        }
        //verifica se o saldo resgate é  maior que o disponivel

        if ((float)fnFormatvalor($InserirVenda['valor_resgate'], $dec) > '0.00') {

            if ($Cartaows > 0) {

                //=====busca saldo do clientes 
                $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $dadosbase[0]['COD_CLIENTE'] . ")";
                $rsrown = mysqli_query($connUser->connUser(), $consultasaldo);
                $retSaldo = mysqli_fetch_assoc($rsrown);
                mysqli_free_result($retSaldo);
                mysqli_next_result($connUser->connUser());


                //============================================================================
                //busca valor de configurados para resgates
                $regraresgate = 'SELECT round(min(CR.NUM_MINRESG),2) as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                            INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                            WHERE LOG_ATIVO="S" AND C.cod_empresa=' . $dadosLogin['idcliente'];
                $resgresult = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $regraresgate));
                //==========================================================================
                $arrayvalorres = array(
                    'vl_venda' => fnFormatvalor($InserirVenda['valortotal'], $dec),
                    'PCT_MAXRESG' => $resgresult['PCT_MAXRESG']
                );
                //calcula porcentagem de resgate
                $percentual = ($arrayvalorres['vl_venda'] * $arrayvalorres['PCT_MAXRESG']) / 100;



                if (fnFormatvalor($InserirVenda['valor_resgate'], $dec) > $percentual) {

                    $return = array('InserirVendaResult' => array('msgerro' => 'Valor Resgate maior que o permitido'));
                    array_to_xml($return, $xml_user_info);
                    Grava_log($connUser->connUser(), $LOG, 'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));
                    return $return;
                }
                if (fnFormatvalor($InserirVenda['valor_resgate'], $dec) < $resgresult['NUM_MINRESG']) {
                    $return = array('InserirVendaResult' => array('msgerro' => 'Valor Resgate não pode ser menor que o permitido'));
                    array_to_xml($return, $xml_user_info);
                    Grava_log($connUser->connUser(), $LOG, 'Valor Resgate não pode ser menor que o permitido', addslashes($xml_user_info->asXML()));
                    return $return;
                }
                //saldo menor que o disponivel 
                if (fnFormatvalor($InserirVenda['valor_resgate'], $dec) > fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {
                    $return = array('InserirVendaResult' => array('msgerro' => 'Valor Resgate maior que o disponivel'));
                    array_to_xml($return, $xml_user_info);
                    Grava_log($connUser->connUser(), $LOG, 'Valor Resgate maior que o disponivel', addslashes($xml_user_info->asXML()));
                    return $return;
                }

                //====================================================================================
            }
        }



        //------------------------------------------------------------------------------                       



        ////Loja não cadastrada 
        if ($lojas[0]['msg'] != 1) {

            $msg = 'loja nao cadastrada';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG, $xamls);
            return array('InserirVendaResult' => array('msgerro' => 'Loja não cadastrada!'));
            exit();
        }


        //calcula valor do itens + quantida e verifica se o valor total dos itens e igual  
        $retorno = fn_calValor($InserirVenda, $dec);
        //Menssagem de erro do sistema criticas de campos
        /* if($Cartaows=='01734200014')
                       {
                           return array('InserirVendaResult'=>array('msgerro' =>print_r($retorno)));
                         exit();
                       }*/
        if ($retorno != 1) {
            //$retorno = 1 Valor da soma dos itens igual ao total
            $msg = ';o A soma dos itens não correspode ao valor total!';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG, $xamls);
            $vendaerrovalor = "INSERT INTO venda_divergente 
                                                            (
                                                            COD_EMPRESA,
                                                            COD_INTERNO_XML, 
                                                            USUARIO, 
                                                            PDV,
                                                            COD_VENDEDOR, 
                                                            NOM_VENDEDOR, 
                                                            COD_UNIVEND,
                                                            MSG
                                                            ) 
                                                            VALUES 
                                                            ('" . $row['COD_EMPRESA'] . "',
                                                             '$LOG', 
                                                             '" . $row['COD_USUARIO'] . "',
                                                             '" . $InserirVenda['id_vendapdv'] . "', 
                                                             '" . $dadosLogin['codvendedor'] . "', 
                                                             '" . fnAcentos($dadosLogin['nomevendedor']) . "', 
                                                             '" . $lojas[0]['COD_UNIVEND'] . "',
                                                              '$msg');";
            mysqli_query($connUser->connUser(), $vendaerrovalor);
        }

        //CODIGO PDV igual não passa
        $CODPDV = "SELECT COUNT(*) as venda FROM VENDAS WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and COD_VENDAPDV='" . $InserirVenda['id_vendapdv'] . "'";
        $row_CODPDV = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $CODPDV));

        if ($row_CODPDV['venda'] != 0) {
            $msg = 'Id_vendaPdv ja existe, tente outro codigo por favor!';
            $xamls = addslashes($msg);
            Grava_log($connUser->connUser(), $LOG, $xamls);
            return array('InserirVendaResult' => array('msgerro' => $msg));
            exit();
        }


        //     $dadosbase=fn_consultaBase($connUser->connUser(),$Cartaows,'',$Cartaows,'','',$row['COD_EMPRESA']);
        if ($InserirVenda['cartao'] != 0) {
            //não critica data hora se for igual a 2 
            if ($row['TIP_REGVENDA'] == '1') {  //verifica se a data/hora ja foi cadastrada
                $dataH = 'SELECT count(*) as DAT_HORA from vendas where  COD_EMPRESA="' . $dadosLogin['idcliente'] . '" and
                                    COD_CLIENTE=' . $dadosbase[0]['COD_CLIENTE'] . ' and 
                                    cast(DAT_CADASTR_WS as datetime)="' . $datahora . '"';

                $row_DATAH = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $dataH));


                if ($row_DATAH['DAT_HORA'] != 0) {
                    $msg = 'Oh não! Ja existe uma venda na mesma data e Horas! :(  ';
                    $xamls = addslashes($msg);
                    Grava_log($connUser->connUser(), $LOG, $xamls);
                    return array('InserirVendaResult' => array('msgerro' => $msg));
                    exit();
                }
            }
        }



        //verifica vendedor
        if ($LOG_CADVENDEDOR == '2') {
            if ($InserirVenda['codvendedor'] != "") {
                $nomevendedor = $InserirVenda['codvendedor'];
                $codvendedor_externo = $InserirVenda['codvendedor'];
            } else {
                $nomevendedor = '0';
                $codvendedor_externo = '0';
            }
        } elseif ($LOG_CADVENDEDOR == '1') {
            if ($dadosLogin['nomevendedor'] != "") {
                $nomevendedor = fnAcentos($dadosLogin['nomevendedor']);
                $codvendedor_externo = $dadosLogin['codvendedor'];
            } else {
                $nomevendedor = '0';
                $codvendedor_externo = '0';
            }
        }


        //===================================================== 
        //atendente
        $cod_atendente = fnatendente($connAdm->connAdm(), $InserirVenda['codatendente'], $dadosLogin['idcliente'], $dadosLogin['idloja'], $InserirVenda['codatendente']);
        //vendedor
        $NOM_USUARIO = addslashes($nomevendedor);
        $NOM_USUARIO = str_replace("'", "", $NOM_USUARIO);
        $cod_vendedor = fnVendedor(
            $connAdm->connAdm(),
            $NOM_USUARIO,
            $dadosLogin['idcliente'],
            $dadosLogin['idloja'],
            $codvendedor_externo
        );


        // return array('InserirVendaResult'=>array('msgerro' => $cod_vendedor));
        //                         exit();
        /* if($NOM_USUARIO!="" && $dadosLogin['idcliente']==42)
                          {    
                            $insererro="INSERT INTO log_teste (SQL_TESTE,PDV,cod_vend) VALUES ('".$InserirVenda['codvendedor']."','".$InserirVenda['id_vendapdv']."',$cod_vendedor);";
                             mysqli_query($connUser->connUser(), $insererro);
                          }*/
        //DAT_CADASTR
        //$retorno = 1 Valor da soma dos itens igual ao total
        //$row_CODPDV['venda']== 0 não existe essa venda no banco de dados
        if ($row_CODPDV['venda'] == 0  && $row_DATAH['DAT_HORA'] == 0) {

            //log marcando inicio da venda
            $msg = "Inicio do processo de venda.";
            $xamls = addslashes($msg);
            // Grava_log($connUser->connUser(),$LOG,$xamls);

            // verifica se o dados cpf,cartão,emaile celular existe na base de dados
            //  $dadosbase=fn_consultaBase($connUser->connUser(),$Cartaows,'',$Cartaows,'','');
            //Carregar os dados do cliente
            ////////////////////////////////////////////// 
            if ($dadosbase[0]['contador'] == 1) {

                $nome = $dadosbase[0]['nome'];
                $COD_CLIENTE = $dadosbase[0]['COD_CLIENTE'];
                $datanascimento = $dadosbase[0]['datanascimento'];
                $sexo = $dadosbase[0]['sexo'];
                $cpf = $dadosbase[0]['cpf'];
                $cartao = $dadosbase[0]['cartao'];
                $telefone = $dadosbase[0]['telcelular'];
            } else {

                //se o cadastro automatico for ativo 
                if ($row['LOG_AUTOCAD'] == 'S') {
                    //busca de cpf se tiver auto cad com cpf


                    $cartao = $InserirVenda['cartao'];
                    $datanascimento = is_Date(date('d/m/Y'));
                    $sexo = 0;
                    $nome = "cliente " . $cartao;



                    //cadastrastro de cliente que nao existe
                    $cad_cliente = "CALL SP_ALTERA_CLIENTES_WS('" . $row['COD_EMPRESA'] . "',
                                                                                         '" . $nome . "',
                                                                                         '" . $row['COD_USUARIO'] . "',
                                                                                         '" . $cartao . "',
                                                                                         '" . $datanascimento . "',
                                                                                         '" . $sexo . "',
                                                                                         '" . $cartao . "',
                                                                                         'F',
                                                                                         '" . $cod_vendedor . "',
                                                                                         'CAD'
                                                                                      )";
                    $rsCliente = mysqli_query($connUser->connUser(), $cad_cliente);

                    if (!$rsCliente) {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                        try {
                            mysqli_query($connUser->connUser(), $cad_cliente);
                        } catch (mysqli_sql_exception $e) {
                            $msgsql = $e;
                        }
                        $msg = "Error description SP_ALTERA_CLIENTES_WS: $msgsql";
                        $xamls = addslashes($msg);
                        Grava_log($connUser->connUser(), $LOG, $xamls);
                        return array('InserirVendaResult' => array('msgerro' => 'Erro ao inserir clientes!'));
                        exit();
                    } else {
                        $row_cliente = mysqli_fetch_assoc($rsCliente);

                        $COD_CLIENTE = $row_cliente['COD_CLIENTE'];
                        $msg = 'Cliente inserido ';
                        $xamls = addslashes($msg);
                        // Grava_log($connUser->connUser(),$LOG,$xamls);
                        $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $cartao;
                        mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));

                        $msg = 'cartao alterado';
                        $xamls = addslashes($msg);
                        // Grava_log($connUser->connUser(),$LOG,$xamls);
                    }

                    //se o cadastro automatico for inativo      
                } elseif ($row['LOG_AUTOCAD'] == 'N') {
                    $COD_CLIENTE = $dadosbase[0]['COD_CLIENTE'];
                }
            }

            //Fim da carga do cliente
            //inicio do inserir venda
            if ($row['TIP_CONTABIL'] == '') {
                $TIP_CONTABIL = 0;
            } else {
                $TIP_CONTABIL = $row['TIP_CONTABIL'];
            }
            if ($InserirVenda['cartao'] == 0) {

                ////////////////////////////////////////////////
                $cad_venda = "CALL SP_INSERE_VENDA_WS_AVULSO( 0,
                                                                         0,
                                                                         '" . $row['COD_EMPRESA'] . "', 
                                                                         '" . $row['COD_CLIENTE_AV'] . "',
                                                                         '1',
                                                                         '3',
                                                                         '" . $lojas[0]['COD_UNIVEND'] . "',
                                                                         '" . $formapretorno['COD_FORMAPA'] . "',
                                                                         '" . fnFormatvalor($InserirVenda['valortotal'], $dec) . "',
                                                                         0,
                                                                         '" . fnFormatvalor($InserirVenda['valor_resgate'], $dec) . "',
                                                                         0,
                                                                         '" . $InserirVenda['id_vendapdv'] . "',
                                                                         '" . $row['COD_USUARIO'] . "',
                                                                         '" . $TIP_CONTABIL . "',
                                                                         " . $lojas[0]['COD_MAQUINA'] . ",
                                                                         '" . $InserirVenda['cupom'] . "',
                                                                         '" . $cod_vendedor . "',
                                                                         '" . $datahora . "',
                                                                         '" . $cod_atendente . "'    
                                                                         );";

                $rewsinsert = mysqli_query($connUser->connUser(), $cad_venda);
                if (!$rewsinsert) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    try {
                        mysqli_query($connUser->connUser(), $cad_venda);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }

                    $msg = "Error description venda avulsa: $msgsql";
                    $xamls = addslashes($msg);
                    Grava_log($connUser->connUser(), $LOG, $xamls);
                } else {
                    $row_venda = mysqli_fetch_assoc($rewsinsert);
                }

                // echo $cad_venda;
                $msg = "Processo de venda avulso concluido!";
                //$msg.=$cad_venda;
                $xamls = addslashes($msg);
                //  Grava_log($connUser->connUser(),$LOG,$xamls); 

            } else {


                $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                                         '" . $row['COD_EMPRESA'] . "', 
                                                                         '" . $COD_CLIENTE . "',
                                                                         '1',
                                                                         '3',
                                                                         '" . $lojas[0]['COD_UNIVEND'] . "',
                                                                         '" . $formapretorno['COD_FORMAPA'] . "',
                                                                         '" . fnFormatvalor($InserirVenda['valortotal'], $dec) . "',
                                                                         0,
                                                                         '" . fnFormatvalor($InserirVenda['valor_resgate'], $dec) . "',
                                                                         0,
                                                                         '" . $InserirVenda['id_vendapdv'] . "',
                                                                        '" . $row['COD_USUARIO'] . "',
                                                                         '" . $TIP_CONTABIL . "',
                                                                         " . $lojas[0]['COD_MAQUINA'] . ",
                                                                         '" . $InserirVenda['cupom'] . "',
                                                                         '" . $cod_vendedor . "',
                                                                         '" . $datahora . "',
                                                                          '" . $cod_atendente . "'
                                                                          );";
                /* if($Cartaows=='10469011769')
                                   {
                                        return array('InserirVendaResult'=>array('msgerro' => $cad_venda));
                                   } */
                $rewsinsert = mysqli_query($connUser->connUser(), $cad_venda);
                if (!$rewsinsert) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    try {
                        mysqli_query($connUser->connUser(), $cad_venda);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }
                    $msg = "Error description venda: $msgsql";
                    $xamls = addslashes($msg);
                    Grava_log($connUser->connUser(), $LOG, $xamls);
                    return array('InserirVendaResult' => array('msgerro' => 'Ops temos problemas com os dados da venda'));
                    exit();
                } else {
                    $row_venda = mysqli_fetch_assoc($rewsinsert);
                    // echo $cad_venda;
                    $msg = "Processo de venda concluido!";
                    $xamls = addslashes($msg);
                    //Grava_log($connUser->connUser(),$LOG,$xamls);
                }
                //
            }
            if ($InserirVenda['entrega'] == 'S') {
                $cod_creditou = "UPDATE VENDAS SET COD_CREDITOU=4 WHERE COD_VENDA='" . $row_venda['COD_VENDA'] . "'";
                mysqli_query($connUser->connUser(), $cod_creditou);
            }
            //fim do inserir venda

            //se item venda for menor que um.      
            if (count($InserirVenda['items']['vendaitem']['codigoproduto']) == 1) {

                $VAL_TOTITEM = fnFormatvalor($InserirVenda['items']['vendaitem']['quantidade'], $dec) * fnFormatvalor($InserirVenda['items']['vendaitem']['valor'], $dec);
                $produto = addslashes(utf8_encode(fnAcentos($InserirVenda['items']['vendaitem']['produto'])));
                $produto = limitarTexto($produto, 150);
                $itemvendainsert = "call SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                                                                '" . $row['COD_EMPRESA'] . "',
                                                                                                '" . $InserirVenda['items']['vendaitem']['id_item'] . "',
                                                                                                 " . $row_venda['COD_VENDA'] . ",
                                                                                                 '" . $InserirVenda['items']['vendaitem']['codigoproduto'] . "',
                                                                                                 '" . $produto . "',    
                                                                                                 0,
                                                                                                 '" . fnFormatvalor($InserirVenda['items']['vendaitem']['quantidade'], $dec) . "',
                                                                                                 '" . fnFormatvalor($InserirVenda['items']['vendaitem']['valor'], $dec) . "',
                                                                                                 '" . $VAL_TOTITEM . "',
                                                                                                 '" . $dadosLogin['idloja'] . "',
                                                                                                 '0'  
                                                                                                  )";
                $itemteste = mysqli_query($connUser->connUser(), $itemvendainsert);
                if (!$itemteste) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    try {
                        mysqli_query($connUser->connUser(), $itemvendainsert);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }
                    $msg = "Error description venda: $msgsql";
                    $xamls = addslashes($msg);
                    Grava_log($connUser->connUser(), $LOG, $xamls);
                    return array('InserirVendaResult' => array('msgerro' => 'Ops temos problemas com os dados do item'));
                    exit();
                }
            } else {

                for ($i = 0; $i < count($InserirVenda['items']['vendaitem']); $i++) {


                    $VAL_TOTITEM = fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['quantidade'], $dec) * fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['valor'], $dec);

                    $NOM_PROD = "";
                    $NOM_PROD = addslashes(utf8_encode(fnAcentos($InserirVenda['items']['vendaitem'][$i]['produto'])));
                    $NOM_PROD = limitarTexto($NOM_PROD, 150);
                    $itemvendainsert = "CALL SP_INSERE_ITENS_WS($COD_CLIENTE,
                                                                                                        '" . $row['COD_EMPRESA'] . "',
                                                                                                        '" . $InserirVenda['items']['vendaitem'][$i]['id_item'] . "',
                                                                                                        " . $row_venda['COD_VENDA'] . ",
                                                                                                        '" . $InserirVenda['items']['vendaitem'][$i]['codigoproduto'] . "',
                                                                                                        '" . $NOM_PROD . "',   
                                                                                                          0,
                                                                                                        '" . fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['quantidade'], $dec) . "',
                                                                                                        '" . fnFormatvalor($InserirVenda['items']['vendaitem'][$i]['valor'], $dec) . "',
                                                                                                        '" . $VAL_TOTITEM . "',
                                                                                                        '" . $dadosLogin['idloja'] . "',
                                                                                                        '0'      
                                                                                                         );";

                    $itemteste = mysqli_query($connUser->connUser(), $itemvendainsert);
                    if (!$itemteste) {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                        try {
                            mysqli_query($connUser->connUser(), $itemvendainsert);
                        } catch (mysqli_sql_exception $e) {
                            $msgsql = $e;
                        }
                        $msg = "Error description venda: $msgsql";
                        $xamls = addslashes($msg);
                        Grava_log($connUser->connUser(), $LOG, $xamls);
                        return array('InserirVendaResult' => array('msgerro' => 'Ops temos problemas com os dados do items'));
                        exit();
                    }
                }
                // mysqli_multi_query($connUser->connUser(), $itemvendainsert);

            }

            if ($InserirVenda['cartao'] == 0) {

                $msg = 'Venda avulso nao gerar credito!';
                $xamls = addslashes($msg);
                //Grava_log($connUser->connUser(),$LOG,$xamls);
                $msg = 'VENDA AVULSA OK';
                $xamls = addslashes($msg);
                Grava_log($connUser->connUser(), $LOG, $xamls);
            } else {

                if ($dadosbase[0]['funcionario'] == 'S' && $row['LOG_PONTUAR'] == 'N' || $dadosbase[0]['LOG_ESTATUS'] == 'N') {


                    Grava_log($connUser->connUser(), $LOG, 'Fucionario não Gera Creditos');
                    // 1= não pontua 
                    // 0 or vazio = pontua
                    $cod_creditou = "UPDATE VENDAS SET COD_CREDITOU=4 WHERE COD_VENDA='" . $row_venda['COD_VENDA'] . "'";
                    mysqli_query($connUser->connUser(), $cod_creditou);
                } else {
                    if ((trim($InserirVenda['entrega']) == 'N' ||
                            trim($InserirVenda['entrega'] == '')) &&
                        (trim($InserirVenda['naopontuar']) == '' ||
                            trim($InserirVenda['naopontuar']) == '0' ||
                            fnFormatvalor($InserirVenda['naopontuar'], $dec) == '0.00')
                    ) {
                        if ($row['LOG_CREDAVULSO'] == 'S') {
                            //creditos e debitos na rotina de credito fixo "credito enviado pelo cliente"
                            //executa a campanha a tiva e creditos extras 
                            /*$sql_credito1 = "CALL SP_INSERE_CREDITOS_WS('".$row_venda['COD_VENDA']."',
                                                                                                      0,      
                                                                                                      '".$row['COD_EMPRESA']."',
                                                                                                      '".$COD_CLIENTE."',    
                                                                                                      1,    
                                                                                                      1,
                                                                                                      '".$lojas[0]['COD_UNIVEND']."',
                                                                                                      '".$formapretorno['COD_FORMAPA']."',
                                                                                                      '".fnFormatvalor($InserirVenda['valortotal'],$dec)."',
                                                                                                      '".fnFormatvalor('0,00',$dec)."',
                                                                                                      0,
                                                                                                      '".$InserirVenda['id_vendapdv']."',
                                                                                                      '".$row['COD_USUARIO']."',
                                                                                                      '".$cod_vendedor."'    
                                                                                                      )";
                                                        mysqli_query($connUser->connUser(), $sql_credito1);*/
                            // return array('InserirVendaResult'=>array('msgerro' => $sql_credito1));

                            //=========================================================================    
                            $sql_credito = " CALL SP_CREDITO_FIXO(    '" . $COD_CLIENTE . "', 
                                                                                                '" . fnFormatvalor($InserirVenda['pontostotal'], $dec) . "', 
                                                                                                '$datahora', 
                                                                                                '" . $row['COD_USUARIO'] . "', 
                                                                                                'Venda OK', 
                                                                                                '1', 
                                                                                                '" . $lojas[0]['COD_UNIVEND'] . "', 
                                                                                                '" . $row['COD_EMPRESA'] . "',
                                                                                                'VEN',
                                                                                               '" . $row_venda['COD_VENDA'] . "',
                                                                                               '" . $cod_vendedor . "',
                                                                                                '" . fnFormatvalor($InserirVenda['valor_resgate'], $dec) . "', 
                                                                                                'CAD' )";
                        } else {
                            //Calcula creditos e pontos extras
                            $sql_credito = "CALL SP_INSERE_CREDITOS_WS('" . $row_venda['COD_VENDA'] . "',
                                                                                                      0,      
                                                                                                      '" . $row['COD_EMPRESA'] . "',
                                                                                                      '" . $COD_CLIENTE . "',    
                                                                                                      1,    
                                                                                                      1,
                                                                                                      '" . $lojas[0]['COD_UNIVEND'] . "',
                                                                                                      '" . $formapretorno['COD_FORMAPA'] . "',
                                                                                                      '" . fnFormatvalor($InserirVenda['valortotal'], $dec) . "',
                                                                                                      '" . fnFormatvalor($InserirVenda['valor_resgate'], $dec) . "',
                                                                                                      0,
                                                                                                      '" . $InserirVenda['id_vendapdv'] . "',
                                                                                                      '" . $row['COD_USUARIO'] . "',
                                                                                                      '" . $cod_vendedor . "'    
                                                                                                      )";
                        }
                        /*  if($Cartaows=='73137200849')
                                                        {
                                                             return array('InserirVendaResult'=>array('msgerro' => $sql_credito));
                                                        }*/
                        //exibir saldo cliente
                        $SALDO_CLIENTE = mysqli_query($connUser->connUser(), $sql_credito);

                        // return array('InserirVendaResult'=>array('msgerro' => $sql_credito1));
                        if (!$SALDO_CLIENTE) {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                            try {
                                mysqli_query($connUser->connUser(), $sql_credito);
                            } catch (mysqli_sql_exception $e) {
                                $msgsql = $e;
                            }
                            $msg = "Error description venda: $msgsql";
                            $xamls = addslashes($msg);
                            Grava_log($connUser->connUser(), $LOG, $xamls);
                            return array('InserirVendaResult' => array('msgerro' => 'ERRO AO INSERIR CREDITO!'));
                            exit();
                        } else {
                            $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                            $msg = "OK";
                            $xamls = addslashes($msg);
                            Grava_log($connUser->connUser(), $LOG, $xamls);
                            //===================msg de resgate=================

                        }
                    } else {
                        $cod_creditou = "UPDATE VENDAS SET COD_CREDITOU=4 WHERE COD_VENDA='" . $row_venda['COD_VENDA'] . "'";
                        mysqli_query($connUser->connUser(), $cod_creditou);

                        $sql_credito = " CALL SP_CREDITO_FIXO(    '" . $COD_CLIENTE . "', 
                                                                                                '0.00', 
                                                                                                '$datahora', 
                                                                                                '" . $row['COD_USUARIO'] . "', 
                                                                                                'Venda OK', 
                                                                                                '1', 
                                                                                                '" . $lojas[0]['COD_UNIVEND'] . "', 
                                                                                                '" . $row['COD_EMPRESA'] . "',
                                                                                                'VEN',
                                                                                                '" . $row_venda['COD_VENDA'] . "',
                                                                                                '" . $cod_vendedor . "',
                                                                                                '" . fnFormatvalor($InserirVenda['valor_resgate'], $dec) . "', 
                                                                                                'CAD')";
                        /* if($Cartaows=='73137200849')
                                                        {
                                                             return array('InserirVendaResult'=>array('msgerro' => $sql_credito));
                                                        }*/
                        $resavulso = mysqli_query($connUser->connUser(), $sql_credito);
                        if (!$resavulso) {
                            return array('InserirVendaResult' => array('msgerro' => "OPS! Não foi possivel efetuar o resgate avulso!"));
                            exit();
                        }

                        $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $COD_CLIENTE . ")";
                        $retSaldo = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $consultasaldo));
                        $rowSALDO_CLIENTE['TOTAL_CREDITO'] = $retSaldo['TOTAL_CREDITO'];
                        $rowSALDO_CLIENTE['CREDITO_VENDA'] = '0.00';

                        $msg = "OK";
                        $xamls = addslashes($msg);
                        Grava_log($connUser->connUser(), $LOG, $xamls);
                    }
                }
            }
        } else {
        }

        //memoria log
        fnmemoriafinal($connUser->connUser(), $cod_men);


        //RETORNO DA WEB SERVICE 
        //GERA COMPROVANTE


        if ($msg == 'OK') {

            //atualizar informação de recebinento de sms/email 
            //================================================================
            if ($COD_CLIENTE > 0 && $cartao != 0) {

                $array = array(
                    'WHERE' => "WHERE g.TIP_GATILHO in ('venda','resgate','credVen') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                    'TABLE' => array(
                        'gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha',
                        'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha'
                    )
                );
                foreach ($array['TABLE'] as $KEY => $dadostable) {
                    unset($sqlgatilho_email);
                    $sqlgatilho_email .= "SELECT * FROM $dadostable
							inner JOIN campanha c ON c.COD_CAMPANHA=p.COD_CAMPANHA AND c.LOG_ATIVO='S'
		                    $array[WHERE]";
                    $rwgatilho_email = mysqli_query($connUser->connUser(), $sqlgatilho_email);
                    while ($rsgatilho_email = mysqli_fetch_assoc($rwgatilho_email)) {
                        if ($rsgatilho_email['TIP_GATILHO'] != '') {
                            if ($KEY == '0') {
                                if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                    $gatilho = '5';
                                }
                                if ($rsgatilho_email['TIP_GATILHO'] == 'venda') {
                                    $gatilho = '6';
                                }
                                if ($rsgatilho_email['TIP_GATILHO'] == 'credVen') {
                                    $gatilho = '9';
                                }
                            } else {
                                if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                    $gatilho = '7';
                                }
                                if ($rsgatilho_email['TIP_GATILHO'] == 'venda') {
                                    $gatilho = '8';
                                }
                                if ($rsgatilho_email['TIP_GATILHO'] == 'credVen') {
                                    $gatilho = '10';
                                }
                            }

                            $cod_campanha = $rsgatilho_email['COD_CAMPANHA'];
                            $TIP_MOMENTO = $rsgatilho_email['TIP_MOMENTO'];
                            $TIP_GATILHO = $rsgatilho_email['TIP_GATILHO'];
                            $COD_PERSONAS = $rsgatilho_email['COD_PERSONAS'];
                            if ((float)fnFormatvalor($InserirVenda['valor_resgate'], $dec) <= '0.00') {
                                $valorresgate = '0.00';
                            } else {
                                $valorresgate = fnFormatvalor($InserirVenda['valor_resgate'], $dec);
                            }

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
                                                                   VAL_CRED_ACUMULADO,
                                                                   VAL_RESGATE,
                                                                   SEMANA,
                                                                   TIP_CONTROLE,
                                                                   MES,
																   CRED_VENDA
                                                                   ) VALUES 
                                                                   ('" . $row['COD_EMPRESA'] . "', 
                                                                   '" . $dadosLogin['idloja'] . "', 
                                                                   '" . $COD_CLIENTE . "', 
                                                                   '" . $dadosbase[0]['cpf'] . "', 
                                                                   '" . addslashes(fnAcentos($dadosbase[0]['nome'])) . "', 
                                                                   '" . $dadosbase[0]['datanascimento'] . "', 
                                                                   '" . trim($dadosbase[0]['email']) . "',
                                                                   '" . $dadosbase[0]['telcelular'] . "',    
                                                                   '" . $dadosbase[0]['sexo'] . "', 
                                                                   '" . $cod_campanha . "', 
                                                                   '" . $TIP_MOMENTO . "',
                                                                   '$gatilho',
                                                                   '$TIP_GATILHO',
                                                                   '" . fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal) . "',
                                                                   '" . $valorresgate . "',
                                                                   '" . date("W", strtotime("-2 day", strtotime(date('Y-m-d H:i:s')))) . "',
                                                                   $rsgatilho_email[TIP_CONTROLE],
                                                                   '" . date('m') . "',
								   '" . fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal) . "'
                                                                   );";
                            /* if($cartao=='01734200014')
                            {    
                                 return array('InserirVendaResult'=>array('nome'=>$sqlgatilho_email ));    
                            }*/
                            //if($dadosbase[0]['telcelular']!='')
                            //{    
                            if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                if ((float)fnFormatvalor($InserirVenda['valor_resgate'], $dec) > '0.00') {
                                    mysqli_query($connUser->connUser(), $sqlfila);
                                }
                            }
                            if ($rsgatilho_email['TIP_GATILHO'] == 'venda') {
                                mysqli_query($connUser->connUser(), $sqlfila);
                            }

                            if ($rsgatilho_email['TIP_GATILHO'] == 'credVen') {
                                if (fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal) != '0,00') {
                                    mysqli_query($connUser->connUser(), $sqlfila);
                                }
                            }

                            //}    
                            unset($sqlfila);
                        }
                        /*if($cartao=='01734200014')
			{    
				 return array('InserirVendaResult'=>array('nome'=>$sqlfila ));    
			}*/
                        $clas = "CALL SP_PERSONA_CLASSIFICA_CADASTRO($COD_CLIENTE, " . $row['COD_EMPRESA'] . ", $cod_campanha, '" . $COD_PERSONAS . "',0)";
                        $testesql = mysqli_query($connUser->connUser(), $clas);
                    }
                }
            }

            mysqli_close($connAdm->connAdm());
            mysqli_close($connUser->connUser());
            //==================================================================                    

            if ($dadosLogin['idcliente'] == 46) {
                $cartao = '';
            }
            $comprovante = 'CLIENTE: ' . fnMascaraCampo($nome) . '
Cartão: ' . fnMascaraCampo($cartao) . '
DATA: ' . date("d/m/Y H:i:s") . '
SALDO ACUMULADO: ' . fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal) . '

*. COMPROVANTE NÃO FISCAL.*';
            if ((float)fnFormatvalor($InserirVenda['valor_resgate'], $dec) > '0.00') {

                if ($dadosLogin['idcliente'] == 46) {
                    $cartao = '';
                }
                $comprovante_resgate = 'Cliente: ' . fnMascaraCampo($nome) . '
Cartao: ' . fnMascaraCampo($cartao) . '
Valor debitado: R$ ' . fnValor($InserirVenda['valor_resgate'], $decimal) . '
Data:' . date("d/m/Y H:i:s") . '
Reconheco e autorizo o debito
_____________________________
ASSINATURA DO CLIENTE
SALDO ACUMULADO: R$ ' . fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal) . '

COMPROVANTE NAO FISCAL.';
            }
        }


        $return = array('InserirVendaResult' => array(
            'nome' => fnMascaraCampo($nome),
            'cartao' => fnMascaraCampo($cartao),
            'saldo' => fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal),
            'creditovenda' => fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal),
            'comprovante' => rtrim(trim($comprovante)),
            'comprovante_resgate' => rtrim(trim($comprovante_resgate)),
            'url' => "http://extrato.bunker.mk?key=" . rawurlencode($urlextrato),
            'msgerro' => $msg
        ));
        //function call to convert array to xml
        array_to_xml($return, $xml_user_info);
        Grava_log($connUser->connUser(), $LOG, 'XML_RETORNO_OK', addslashes($xml_user_info->asXML()));


        return $return;
    } else {
        $msg = 'Oh Não! Seu Usuario ou senha está errado. Se tiver a necessidade entre  em contato com o Administrador do sistema.';
        return array('InserirVendaResult' => array('msgerro' => $msg));
    }
}

//=================================================================== Fim InserirVenda =================================================================================