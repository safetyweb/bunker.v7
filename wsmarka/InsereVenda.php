<?php
$server->register(
    'InsereVenda',
    array(
        'fase' => 'xsd:string',
        'venda' => 'tns:venda',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('InsereVendaResponse' => 'tns:acao'),  //output
    $ns,                                 // namespace
    "$ns/InsereVenda",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'InsereVenda'                 // documentation
);


function InsereVenda($fase, $venda, $dadosLogin)
{

    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';
    //file_get_contents("php://input")


    ob_start();
    $cartao = fnlimpaCPF($venda['cartao']);
    $dadosLogin['idloja'] = preg_replace('/\s+/', '', $dadosLogin['idloja']);
    //|| $cartao == '02631868900'
    if ($cartao == '69660031076'  || $cartao == '31448359864') {
        http_response_code(400);
        /*   $arquivo = fopen('new_teste.txt', 'w');
        fwrite($arquivo, file_get_contents("php://input"));
        fclose($arquivo);
        sleep(40);
        return array('InsereVendaResponse' => array(
            'msgerro' => 'Valor Resgate maior que o permitido',
            'coderro' => '45'
        ));*/
        exit();
    }
    //  $cartao=$venda['cartao'];

    /* $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $buscauser = mysqli_query($connAdm->connAdm(), $sql);
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

    //conn user
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {


        //if($dadosLogin['idcliente']=='7')
        // {		
        $CONFIGUNI = "SELECT * FROM unidades_parametro WHERE 
						  COD_EMPRESA=" . $dadosLogin['idcliente'] . " AND 
						COD_UNIVENDA=" . $dadosLogin['idloja'] . " AND LOG_STATUS='S'";
        $RSCONFIGUNI = mysqli_query($connUser->connUser(), $CONFIGUNI);
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
        //}

        /*  
	   // mudar aqui
	   $dec=$row['NUM_DECIMAIS']; 
	   // mudar aqui
       if ($row['TIP_RETORNO']== 2){$decimal = '2';}else {$casasDec = '0';}
	   */


        // $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'InsereVenda',$dadosLogin['idcliente']);

        //pegar dados do cliente rede duque

        if ($dadosLogin['idloja'] != '96986') {
            if ($dadosLogin['idcliente'] == '19') {
                //busca cliente  na base de dados

                if (count($venda['itens']['vendaitem']['quantidade']) == 1) {
                    $des_placav = fnplacamercosul($venda['itens']['vendaitem']['atributo1']);
                    $des_tokenv = $venda['itens']['vendaitem']['atributo2'];
                } else {
                    $des_placav = fnplacamercosul($venda['itens']['vendaitem'][0]['atributo1']);
                    $des_tokenv = $venda['itens']['vendaitem'][0]['atributo2'];
                }
                if ($des_placav != '' || $des_tokenv != '') {
                    $arraydadosbusca = array(
                        'empresa' => $dadosLogin['idcliente'],
                        'generico' => $des_placav,
                        'tokem' => $des_tokenv,
                        'venda' => 'venda',
                        'ConnB' => $connUser->connUser()
                    );
                    $cliente_cod = fn_consultaBase($arraydadosbusca);
                    if ($cliente_cod['cartao'] != '') {
                        $cartao = $cliente_cod['cartao'];
                    }
                }
            }
        }


        //================================================================================================================ 
        if ($venda['cartao'] == '') {

            $cartao = '0';

            //log xml venda
            //file_get_contents("php://input")  

            $cod_log = fnGravaArrayvenda($connUser->connUser(), $connAdm->connAdm(), $venda, $dadosLogin, $row['COD_USUARIO'], file_get_contents("php://input"), $row['LOG_WS']);


            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Cartao nao pode ser Vazio.......', $row['LOG_WS']);
            Grava_log($connUser->connUser(), $cod_log, 'O cartao esta vazio! A venda entrar como avulsa.');
            //return array('InsereVendaResponse'=>array('msgerro' => 'O cartao esta vazio!',
            //                                          'coderro'=>'81'));
        } else {
            //log xml venda
            //file_get_contents("php://input")  
            //  $xmllimpo=file_get_contents("php://input");
            $cod_log = fnGravaArrayvenda($connUser->connUser(), $connAdm->connAdm(), $venda, $dadosLogin, $row['COD_USUARIO'], file_get_contents("php://input"), $row['LOG_WS']);

            /*if($cartao=='47737865829')
                {
                   return array('InsereVendaResponse'=>array('msgerro' => $cod_log,
                                                             'coderro'=>'8'));  

                }*/

            //  $cod_log=fnGravaArrayvenda($connUser->connUser(),$connAdm->connAdm(),$venda,$dadosLogin,$row['COD_USUARIO'],$xmllimpo,$row['LOG_WS']);

        }
        /*  if($venda['cartao']=='01734200014')
        {
        return array('InsereVendaResponse'=>array('msgerro' =>   $cod_log ,
                                                                                           'coderro'=>'75'));	
        }*/
        //==============================================================================================================
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><InsereVendaResponse></InsereVendaResponse>");

        //compara os id_cliente com o cod_empresa
        //valida campos
        $msg = valida_campo_vazio($venda['formapagamento'], 'formapagamento', 'string', $row['LOG_WS']);
        if (!empty($msg) || !empty($msg1)) {

            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            Grava_log($connUser->connUser(), $cod_log, 'Campo formapagamento precisa ser preenchido!');
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '21'
            ));
        }


        $msg = valida_campo_vazio($dadosLogin['login'], 'login', 'string', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '22'
            ));
        }

        $msg = valida_campo_vazio($dadosLogin['senha'], 'senha', 'string', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '23'
            ));
        }

        $msg = valida_campo_vazio($venda['id_vendapdv'], 'id_vendapdv', 'string', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '23'
            ));
        }

        $msg = valida_campo_vazio($dadosLogin['idloja'], 'idloja', 'numeric', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '20'
            ));
        }
        //$msg=valida_campo_vazio($dadosLogin['idmaquina'],'idmaquina','string');
        //if(!empty($msg)){return array('BuscaConsumidorResponse'=>array('msgerro' => $msg,
        //                                                                'coderro'=>'24' ));} 
        $msg = valida_campo_vazio($dadosLogin['idcliente'], 'idcliente', 'numeric', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '26'
            ));
        }
        $msg = valida_campo_vazio($venda['cartao'], 'cartao', 'string', $row['LOG_WS']);
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            return array('InsereVendaResponse' => array(
                'msgerro' => $msg,
                'coderro' => '27'
            ));
        }

        $validadatetime = validateDate($venda['datahora']);
        if ($validadatetime != 1) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Formato date/time inválido! AAAA-MM-DD HH:i:s', $row['LOG_WS']);
            $return = array('InsereVendaResponse' => array(
                'msgerro' => 'Formato date/time inválido! AAAA-MM-DD HH:i:s',
                'coderro' => '0'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Formato date/time inválido! AAAA-MM-DD HH:i:s', addslashes($xml_user_info->asXML()));
            return $return;
        }


        if ($venda['valor_resgate'] == '' || $venda['valor_resgate'] == '?') {
            $valor_resgate = '0';
        } else {
            $valor_resgate = fnFormatvalor($venda['valor_resgate'], $dec);
        }

        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('InsereVendaResponse' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            //Grava_log($connUser->connUser(),$cod_log,"Oh não! Usuario foi desabilitado ;-[!");   
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            $return =  array('InsereVendaResponse' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '5'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Oh não! Usuario foi desabilitado ;-[!', addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }
        if ($dadosLogin['idloja'] != '') {

            if ($dadosLogin['idloja'] != '0') {
                $lojasql = 'SELECT LOG_ESTATUS,LOG_TOKEN  FROM unidadevenda
                         WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
                $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
                if ($lojars['LOG_ESTATUS'] != 'S') {
                    // Grava_log($connUser->connUser(),$cod_log,"LOJA DESABILITADA");  
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Inserir venda', ' Loja desabilidata', $row['LOG_WS']);
                    $return =  array('InsereVendaResponse' => array(
                        'msgerro' => 'LOJA DESABILITADA',
                        'coderro' => '80'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'LOJA DESABILITADA', addslashes($xml_user_info->asXML()));
                    return $return;
                    exit();
                }
            }
        }

        //==============================================================================================  
        //verifica se a data e hora da venda nao é maior que a atual
        if (fndate($venda['datahora']) > date("Y-m-d")) {


            // Grava_log($connUser->connUser(),$cod_log,'Data da venda maior que a data atual!');
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Data da venda maior que a data atual!', $row['LOG_WS']);

            $return = array('InsereVendaResponse' => array(
                'msgerro' => 'Data da venda maior que a data atual!',
                'coderro' => '0'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Data da venda maior que a data atual!', addslashes($xml_user_info->asXML()));
            return $return;
        }

        //////////////////////=================================================================================================================

        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return  array('InsereVendaResponse' => array(
                'msgerro' => 'Id_cliente não confere com o cadastro!',
                'coderro' => '4'
            ));
            exit();
        }
        // Venda avulsa Desabilitada,
        if ($cartao == 0 || $cartao == '') {
            if ($row['LOG_AVULSO'] == 'N') {
                //Grava_log($connUser->connUser(),$cod_log,"Venda avulsa Desabilitada"); 
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Venda avulsa Desabilitada', $row['LOG_WS']);
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => "Venda avulsa Desabilitada",
                    'coderro' => '74'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Venda avulsa Desabilitada', addslashes($xml_user_info->asXML()));
                return $return;
                exit();
            }
        }
        /*
        //verificar se o produto é PBM
       
            if (count($venda['itens']['vendaitem']['id_item'])==1){
                 $sqlPBM="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
                            LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
                            LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
                            where A.COD_EXTERNO='".$venda['itens']['vendaitem']['id_item']."' A.COD_EMPRESA='".$dadosLogin['idcliente']."' AND A.COD_EXCLUSA=0 
                ";
                 $execsqlPBM=mysqli_query($connUser->connUser(), $sqlPBM);
                 $RWPBM= mysqli_fetch_assoc($execsqlPBM);
            }else{
               

                foreach ($venda['itens']['vendaitem'] as $key => $chave)
                {
                    $sqlPBM="select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
                              LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
                              LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
                              where A.COD_EXTERNO='".$chave['id_item']."' and A.COD_EMPRESA='".$dadosLogin['idcliente']."' AND A.COD_EXCLUSA=0 ;
                  "; 
                   $execsqlPBM=mysqli_query($connUser->connUser(), $sqlPBM);
                   $RWPBM= mysqli_fetch_assoc($execsqlPBM);
                   $LOG_PRODPBM=$RWPBM['LOG_PRODPBM'].',';
                   $COD_PRODUTO=$RWPBM['COD_PRODUTO'].',';
                   $todos.=$COD_PRODUTO.' '. $LOG_PRODPBM;
                
               
                }  
            }
            return array('InsereVendaResponse'=>array('msgerro' =>$todos,
                                                             'coderro'=>'6'));
            exit();
        */



        //============================================================================
        //====================================================================================================================================
        //verifica loja
        $lojas = fnconsultaLoja($connAdm->connAdm(), $connUser->connUser(), $dadosLogin['idloja'], $dadosLogin['idmaquina'], $row['COD_EMPRESA']);

        // mudar aqui    

        //verifica vendedor
        //verifica vendedor

        if ($LOG_CADVENDEDOR == '2') {
            if ($venda['codvendedor'] != "") {
                $nomevendedor = $venda['codvendedor'];
                $codvendedor_externo = $venda['codvendedor'];
            } else {
                $nomevendedor = '0';
                $codvendedor_externo = '0';
            }
        } elseif ($LOG_CADVENDEDOR == '1') {
            if (fnAcentos($dadosLogin['codvendedor']) != "") {
                $nomevendedor = $dadosLogin['nomevendedor'];
                $codvendedor_externo = $dadosLogin['codvendedor'];
            } else {
                $nomevendedor = '0';
                $codvendedor_externo = '0';
            }
        }
        //
        $NOM_USUARIO = utf8_encode(fnAcentos(rtrim(trim($nomevendedor))));
        $NOM_USUARIO = str_replace("'", "", $NOM_USUARIO);
        $cod_vendedor = fnVendedor(
            $connAdm->connAdm(),
            $NOM_USUARIO,
            $row['COD_EMPRESA'],
            $dadosLogin['idloja'],
            $codvendedor_externo,
            $row['COD_USUARIO']
        );
        //$row['COD_USUARIO']
        //vendedor
        $cod_atendente = fnatendente($connAdm->connAdm(), $venda['codatendente'], $dadosLogin['idcliente'], $dadosLogin['idloja'], $venda['codatendente']);


        // if($row['COD_EMPRESA']==45){
        // return array('InsereVendaResponse'=>array('msgerro' => $cod_vendedor));
        // }
        //========================================================================================================================================
        //===verifica valor
        //calcula valor do itens + quantida e verifica se o valor total dos itens e igual  

        if ($row['COD_CHAVECO'] == 3) {
        } else {

            if ($dadosLogin['idcliente'] == '19' || $dadosLogin['idcliente'] == '70') {
                $retorno = fn_calValor($venda, $dec);


                //Menssagem de erro do sistema criticas de campos
                if ($retorno != 1) {
                    //$retorno = 1 Valor da soma dos itens igual ao total
                    $msg = ';o A soma dos itens não correspode ao valor total!';
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
                    Grava_log($connUser->connUser(), $cod_log, $xamls);
                }

                //===================================================================================================================
                //ALTER TABLE token_resgate ADD COLUMN `VAL_RESGATE` FLOAT(15,4) NULL DEFAULT '0' AFTER `COD_VENDA`;
                //verificar venda com token_desconto

                //calcula venda com desconto
                $retornovenda = fn_calvenda($venda, $dec);

                if ($retornovenda != 1) {
                    //$retorno = 1 Valor da soma dos itens igual ao total
                    $msg = 'valortotalbruto da venda menos o descontototalvalor tem que ser igual ao valortotalliquido!';
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
                    Grava_log($connUser->connUser(), $cod_log, $xamls);
                }
            } else {


                if ($row['LOG_TOKEN'] == 'S') {
                    if ($dadosLogin['idcliente'] == '19' || $dadosLogin['idcliente'] == '70') {
                        if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
                            if ($venda['token_resgate'] != '') {
                                $SQLTOKEN = "SELECT * FROM TOKEN_RESGATE WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND DES_TOKEN='" . $venda['token_resgate'] . "'";
                                $RSTOKEN = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $SQLTOKEN));
                                if ($RSTOKEN['DES_TOKEN'] == '') {
                                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Token $venda[token_resgate] não existe!", $row['LOG_WS']);
                                    return array('InsereVendaResponse' => array(
                                        'msgerro' => "Token não existe!",
                                        'coderro' => '503'
                                    ));
                                } else {
                                    if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > $RSTOKEN['VAL_RESGATE']) {
                                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Verifique o valor de resgate ou tokem digitado!", $row['LOG_WS']);
                                        return array('InsereVendaResponse' => array(
                                            'msgerro' => "Verifique o valor de resgate ou tokem digitado!",
                                            'coderro' => '86'
                                        ));
                                    }
                                }
                                if ($RSTOKEN['COD_MSG'] != '0') {
                                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Token $venda[token_resgate] ja utilizado!", $row['LOG_WS']);
                                    return array('InsereVendaResponse' => array(
                                        'msgerro' => "Token $venda[token_resgate] ja utilizado!",
                                        'coderro' => '502'
                                    ));
                                }
                            } else {
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Token não preenchido!", $row['LOG_WS']);
                                return array('InsereVendaResponse' => array(
                                    'msgerro' => 'Token não preenchido!',
                                    'coderro' => '504'
                                ));
                            }
                        }
                    } else {
                        if ($lojars['LOG_TOKEN'] == 'S') {
                            //token de resgate para outras empresas
                            if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
                                if ($venda['token_resgate'] == '') {
                                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Token Não preenchido!", $row['LOG_WS']);
                                    $return = array('InsereVendaResponse' => array(
                                        'msgerro' => 'Token não preenchido!',
                                        'coderro' => '504'
                                    ));
                                    array_to_xml($return, $xml_user_info);
                                    Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Token não preenchido!', addslashes($xml_user_info->asXML()));
                                    return $return;
                                    exit();
                                }
                            }
                        }
                    }
                }

                $retorno = fn_calValor($venda, $dec);

                //Menssagem de erro do sistema criticas de campos
                if ($retorno != 1) {
                    //$retorno = 1 Valor da soma dos itens igual ao total
                    $msg = ';o A soma dos itens não correspode ao valor total!';
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
                    // Grava_log($connUser->connUser(),$cod_log,$xamls);
                    if (!empty($msg)) {
                        $return = array('InsereVendaResponse' => array(
                            'msgerro' => $xamls,
                            'coderro' => '7'
                        ));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                    exit();
                }

                /* if($cartao=='01734200014')
			{
			return array('InsereVendaResponse'=>array('msgerro' => $retorno,
													   'coderro'=>'7'));	
			}*/
                //===================================================================================================================
                //calcula venda com desconto
                $retornovenda = fn_calvenda($venda, $dec);

                if ($retornovenda != 1) {
                    //$retorno = 1 Valor da soma dos itens igual ao total
                    $msg = 'valortotalbruto da venda menos o descontototalvalor tem que ser igual ao valortotalliquido!';
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
                    //  Grava_log($connUser->connUser(),$cod_log,$xamls);
                    if (!empty($msg)) {
                        $return = array('InsereVendaResponse' => array(
                            'msgerro' => $xamls,
                            'coderro' => '7'
                        ));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                        return $return;
                    }
                    exit();
                }
            }
            // echo $retornovenda; 



        }
    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('InsereVendaResponse' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }


    //verificar PDV 
    //CODIGO PDV igual não passa
    if ($row['TIP_REGVENDA'] == '3') {
        //$row_CODPDV['venda']='1';

    } elseif ($row['TIP_REGVENDA'] == '1') {
        $CODPDV = "SELECT COUNT(*) as venda FROM VENDAS WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and COD_VENDAPDV='" . $venda['id_vendapdv'] . "'";

        $pdvcheck = mysqli_query($connUser->connUser(), $CODPDV);
        while ($row_CODPDV = mysqli_fetch_assoc($pdvcheck)) {
            //retornar OK para o pdv entender que avenda ja esta no marka
            if ($dadosLogin['idcliente'] == '161' || $dadosLogin['idcliente'] == '173') {
                if ($row_CODPDV['venda'] != 0) {
                    //  $msg='Id_vendaPdv ja existe, tente outro codigo por favor!';
                    //  $xamls= addslashes($msg);
                    $return = array('InsereVendaResponse' => array(
                        'msgerro' => "Processo de venda concluido!",
                        'coderro' => '19'
                    ));
                    // array_to_xml($return,$xml_user_info);
                    // Grava_log_msgxml($connUser->connUser(),'msg_venda',$cod_log,$xamls,addslashes($xml_user_info->asXML()));
                    return $return;
                    exit();
                }
            } else {
                if ($row_CODPDV['venda'] != 0) {
                    $msg = 'Id_vendaPdv ja existe, tente outro codigo por favor!';
                    $xamls = addslashes($msg);
                    $return = array('InsereVendaResponse' => array(
                        'msgerro' => $msg,
                        'coderro' => '8'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                    return $return;
                    exit();
                }
            }
            //mysqli_free_result($pdvcheck);
        }
    } else {
        $CODPDV = "SELECT COUNT(*) as venda FROM VENDAS WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and COD_VENDAPDV='" . $venda['id_vendapdv'] . "'";
        $pdvcheck = mysqli_query($connUser->connUser(), $CODPDV);
        while ($row_CODPDV = mysqli_fetch_assoc($pdvcheck)) {

            if ($row_CODPDV['venda'] != 0) {
                $msg = 'Id_vendaPdv ja existe, tente outro codigo por favor!';
                $xamls = addslashes($msg);
                /* if($cod_log!="")
                    {    
                        Grava_log($connUser->connUser(),$cod_log,$xamls);
                        mysqli_free_result($pdvcheck);
                    }*/
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => $msg,
                    'coderro' => '8'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                return $return;
                exit();
            }
            mysqli_free_result($pdvcheck);
        }
    }




    ////////////////////
    //busca cliente  na base de dados    

    $arraydadosbusca = array(
        'empresa' => $dadosLogin['idcliente'],
        'cartao' => $cartao,
        'cpf' => $cartao,
        'venda' => 'venda',
        'ConnB' => $connUser->connUser()
    );
    $cliente_cod = fn_consultaBase($arraydadosbusca);

    $doc = fncompletadoc($cartao, 'F');
    /*  $cadastroautomatico='N';
	
	if($row['LOG_CONSEXT'] == 'S' && strlen($doc) =='11' && $row['LOG_CADTOKEN']=='N' &&  $cadastroautomatico=='S')
	{ 

        if($cartao!='0' || $cartao!="00000000000" || strlen($doc)<='11')
        {  

            if($cliente_cod['COD_CLIENTE'] <= '0' || $cliente_cod['COD_CLIENTE']=="" )
            {
                if($row['COD_CHAVECO'] == '1' || $row['COD_CHAVECO'] == '5')
                {
                    if($dadosLogin['idmaquina']=='')
                    {
                     $maquina='string';   
                    }else{
                     $maquina=$dadosLogin['idmaquina'];      
                    }    
                    include './func/FunCadAutonovo.php';
                    $DadosBusCad=array( 'cartao'=>$doc,
                                        'login'=>$dadosLogin['login'],
                                        'senha'=>$dadosLogin['senha'],
                                        'idloja'=>$dadosLogin['idloja'],
                                        'idmaquina'=>$maquina,
                                        'idcliente'=>$dadosLogin['idcliente'],
                                        'codatendente'=>$venda['codatendente'],
                                        'codvendedor'=>$dadosLogin['codvendedor'],
                                        'nomevendedor'=>fnAcentos($dadosLogin['nomevendedor']),
                                        'connuser'=>$connUser->connUser()
                                       ); 
                    $cliente_cod=FnCadAuto($DadosBusCad);
                    if ( valida_cpf($cartao) ) {
                        Grava_log($connUser->connUser(),$cod_log,' Cadastro inserido automaticamente na venda....'.$doc);  
                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda',' Cadastro inserido automaticamente na venda....',$row['LOG_WS']);


                     }else{
                        Grava_log($connUser->connUser(),$cod_log,'CPF DIGITADO É INVALIDO....'.$doc);  
                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda','CPF DIGITADO É INVALIDO....',$row['LOG_WS']);

                     }   


                }
            } 
        }    
    }

*/

    //verifica se o saldo resgate é  maior que o disponivel
    if ($venda['valor_resgate'] > 0 || (float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
        if ($cartao > 0) {

            //=====busca saldo do clientes 
            $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $cliente_cod['COD_CLIENTE'] . ")";
            $rsrown = mysqli_query($connUser->connUser(), $consultasaldo);
            $retSaldo = mysqli_fetch_assoc($rsrown);
            mysqli_free_result($retSaldo);
            mysqli_next_result($connUser->connUser());
            //============================================================================
            //busca valor de configurados para resgates
            $regraresgate = 'SELECT round(min(CR.NUM_MINRESG),' . $dec . ') as NUM_MINRESG,MAX(CR.PCT_MAXRESG) as PCT_MAXRESG,C.LOG_ATIVO FROM campanha C
                            INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
                            WHERE LOG_ATIVO="S" AND C.cod_empresa=' . $dadosLogin['idcliente'];
            $resgresult = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $regraresgate));
            //==========================================================================
            $arrayvalorres = array(
                'vl_venda' => fnFormatvalor($venda['valortotalliquido'], $dec),
                'PCT_MAXRESG' =>  $resgresult['PCT_MAXRESG']
            );
            $percentual = fnVerificasaldo_venda($arrayvalorres);
            //calcula porcentagem de resgate

            if (fnFormatvalor($venda['valor_resgate'], $dec) > $percentual) {
                //  Grava_log($connUser->connUser(),$cod_log,'Valor Resgate maior que o permitido');  
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o permitido', $row['LOG_WS']);
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => 'Valor Resgate maior que o permitido',
                    'coderro' => '45'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));
                return $return;
            }
            if (fnFormatvalor($venda['valor_resgate'], $dec) < $resgresult['NUM_MINRESG']) {
                // Grava_log($connUser->connUser(),$cod_log,'Valor Resgate não pode ser menor que o permitido');  
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate não pode ser menor que o permitido', $row['LOG_WS']);
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => 'Valor Resgate não pode ser menor que o permitido',
                    'coderro' => '46'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'Valor Resgate não pode ser menor que o permitido', addslashes($xml_user_info->asXML()));
                return $return;
            }
            //saldo menor que o disponivel 
            if ((float) fnFormatvalor($venda['valor_resgate'], $dec) > (float) fnFormatvalor($retSaldo['CREDITO_DISPONIVEL'], $dec)) {

                $msg = 'Valor Regate maior que o disponivel';
                $xamls = addslashes($msg);
                //Grava_log($connUser->connUser(),$cod_log,$xamls);
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Valor Regate maior que o disponivel', $row['LOG_WS']);
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => 'Valor Resgate maior que o disponivel',
                    'coderro' => '47'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                return $return;
            }

            //====================================================================================
        }
    }



    //------------------------------------------------------------------------------


    //return array('BuscaConsumidorResponse'=>array('msgerro' => print_r($cliente_cod),
    //                                                           'coderro'=>'8'));
    //exit();
    //cadastro automatico========================================================================================


    if ($row['LOG_AUTOCAD'] == 'S') {
        if ($cliente_cod['COD_CLIENTE'] == '') {
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
                $msg = "Error description Cadastro Automatico Erro: $msgsql";
                $xamls = addslashes($msg);
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
            } else {
                $row_cliente = mysqli_fetch_assoc($rsCliente);
                $COD_CLIENTE = $row_cliente['COD_CLIENTE'];
                $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $cartao;
                mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Cliente inserido e Cartao Alterado!', $row['LOG_WS']);
            }
        } else {
            $COD_CLIENTE = $cliente_cod['COD_CLIENTE'];
        }
    }
    if ($row['LOG_AUTOCAD'] == 'N') {
        $COD_CLIENTE = $cliente_cod['COD_CLIENTE'];
    }




    //==========================================================================================================
    if ($dadosLogin['idcliente'] != '45') {
        //DATA E HORA VERIFICAR
        if ($venda['cartao'] != 0) {
            //verifica se a data/hora ja foi cadastrada
            $dataH = 'SELECT count(*) as DAT_HORA from vendas where  COD_EMPRESA="' . $dadosLogin['idcliente'] . '" and
                    COD_CLIENTE=' . $COD_CLIENTE . '  and  
                   cast(DAT_CADASTR_WS as datetime)="' . $venda['datahora'] . '"';
            $tdigual = mysqli_query($connUser->connUser(), $dataH);
            while ($row_DATAH = mysqli_fetch_assoc($tdigual)) {

                if ($row_DATAH['DAT_HORA'] > 0) {
                    $msg = 'Oh não! Ja existe uma venda na mesma data e Horas! :(';
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
                    // Grava_log($connUser->connUser(),$cod_log,$xamls);
                    mysqli_free_result($pdvcheck);
                    $return = array('InsereVendaResponse' => array(
                        'msgerro' => $msg,
                        'coderro' => '9'
                    ));
                    array_to_xml($return, $xml_user_info);
                    Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
                    return $return;
                    exit();
                }
            }
        }
    } else {
    }
    //========================================================================================================     

    //Verifica se o cliente exite na base de dados
    if ($row['LOG_AUTOCAD'] == 'N') {

        if ($COD_CLIENTE == '') {
            $msg = 'Cliente não cadastrado';
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
            $xamls = addslashes($msg);
            // Grava_log($connUser->connUser(),$cod_log,$xamls);
            $return = array('InsereVendaResponse' => array(
                'msgerro' => 'Cliente não cadastrado',
                'coderro' => '10'
            ));
            array_to_xml($return, $xml_user_info);
            Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
            return $return;
            exit();
        }
    }
    //=============================================================================================================

    //loja verifica se a loja está cadastrada
    if ($lojas['msg'] != 1) {

        $msg = 'loja nao cadastrada';
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $msg, $row['LOG_WS']);
        $xamls = addslashes($msg);
        //   Grava_log($connUser->connUser(),$cod_log,$xamls);
        $return = array('InsereVendaResponse' => array(
            'msgerro' => 'Loja não cadastrada!',
            'coderro' => '11'
        ));
        array_to_xml($return, $xml_user_info);
        Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
        return $return;
        exit();
    }
    ////////////forma de pagamento
    $formapag = fnFormaPAG($connUser->connUser(), limitarTextoLimpo(fnAcentos($venda['formapagamento']), '150'), $row['COD_EMPRESA']);
    //===========================================================================================================


    $arrayspinservenda = array(
        'cartao' => $cartao,
        'cod_cliente' => $COD_CLIENTE,
        'cod_empresa' => $row['COD_EMPRESA'],
        'cod_avulso' => $row['COD_CLIENTE_AV'],
        'cod_univend' => $lojas['COD_UNIVEND'],
        'formapag' => $formapag,
        'valortotalbruto' => $venda['valortotalbruto'], //valor da venda
        'descontototalbruto' => $venda['descontototalvalor'], //desconto geral da venda
        'valortotalLiquido' => $venda['valortotalliquido'], //valor total venda com o valor de desconto
        'idpdv' => $venda['id_vendapdv'],
        'TIP_CONTABIL' => $row['TIP_CONTABIL'],
        'COD_MAQUINA' => $lojas['COD_MAQUINA'],
        'cupom' => $venda['cupomfiscal'],
        'cod_vendedor' => $cod_vendedor,
        'valor_resgate' => $valor_resgate,
        'COD_USUARIO' => $row['COD_USUARIO'],
        'conn' => $connAdm->connAdm(),
        'connB' => $connUser->connUser(),
        'db_name' => $row['NOM_DATABASE'],
        'datatimews' => $venda['datahora'],
        'log' => $row['LOG_WS'],
        'COD_ATENDENTE' => $cod_atendente
    );
    $cod_venda = venda_avulsa($arrayspinservenda, $dadosLogin, $connAdm->connAdm(), $dec);


    /////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    //update no token da venda

    if ($row['LOG_TOKEN'] == 'S') {
        if ($dadosLogin['idcliente'] == '19') {

            if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
                if ($venda['token_resgate'] != '') {
                    $updatetoken = "UPDATE token_resgate 
								   SET COD_VENDAPDV='$venda[id_vendapdv]', 
									   COD_VENDA='$cod_venda',
									   COD_MSG='1'    
							WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND DES_TOKEN='" . $venda['token_resgate'] . "';";
                    $rwupdatetk = mysqli_query($connUser->connUser(), $updatetoken);
                    if (!$rwupdatetk) {
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Problema no Update do tokem verificar a venda!", $row['LOG_WS']);
                    }
                }
            }
        } else {
            if ($lojars['LOG_TOKEN'] == 'S') {
                $atualizastaatustoken = "UPDATE geratoken SET LOG_USADO='2', 
                                    COD_CLIENTE='" . $COD_CLIENTE . "', 
                                    COD_VENDA='" . $cod_venda . "',
                                    DAT_USADO='" . date('Y-m-d H:i:s') . "' 
                WHERE  DES_TOKEN='" . $venda['token_resgate'] . "' and TIP_TOKEN='2' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "';";
                mysqli_query($connUser->connUser(), $atualizastaatustoken);
                //update na venda 
                $vendatokupdate = "UPDATE vendas SET DES_TOKEN='" . $venda['token_resgate'] . "' WHERE  COD_VENDA='" . $cod_venda . "';";
                mysqli_query($connUser->connUser(), $vendatokupdate);
            }
        }
    }

    //=================================================================================================================                      
    //inserir venda ====================================================================================================

    if (count($venda['itens']['vendaitem']['quantidade']) == 1) {


        $NOM_PROD = addslashes($venda['itens']['vendaitem']['produto']);
        //Pegar o tokem quando tiver mais de um item
        if ($row['COD_EMPRESA'] == 19 || $row['COD_EMPRESA'] == 70) {
            $PLACA_VEICULO = fnplacamercosul($venda['itens']['vendaitem']['atributo1']);
            $TOKEN_GERADO = $venda['itens']['vendaitem']['atributo2'];
        }
        $NOM_PROD = limitarTexto($NOM_PROD, 150);

        ///se for telefone      
        if ($row['COD_CHAVECO'] == 3) {
            $VAL_TOTITEM = fnFormatvalor($venda['itens']['vendaitem']['valorliquido'], $dec);
        } else {
            $VAL_TOTITEM = fnFormatvalor($venda['itens']['vendaitem']['quantidade'], $dec) * fnFormatvalor($venda['itens']['vendaitem']['valorliquido'], $dec);
        }


        if (
            $venda['itens']['vendaitem']['estoque'] == '' ||
            $venda['itens']['vendaitem']['estoque'] == '?'
        ) {
            $estoque = 0;
        } else {
            $estoque = $venda['itens']['vendaitem']['estoque'];
        }
        if ($venda['itens']['vendaitem']['atributo1'] == '') {
            $atributo1 = '';
        } else {
            $atributo1 = $venda['itens']['vendaitem']['atributo1'];
        }
        if ($venda['itens']['vendaitem']['atributo2'] == '') {
            $atributo2 = '';
        } else {
            $atributo2 = addslashes(fnAcentos($venda['itens']['vendaitem']['atributo2']));
        }
        if ($venda['itens']['vendaitem']['atributo3'] == '') {
            $atributo3 = '';
        } else {
            $atributo3 = $venda['itens']['vendaitem']['atributo3'];
        }
        if ($venda['itens']['vendaitem']['atributo4'] == '') {
            $atributo4 = '';
        } else {
            $atributo4 = $venda['itens']['vendaitem']['atributo4'];
        }
        if ($venda['itens']['vendaitem']['atributo5'] == '') {
            $atributo5 = '';
        } else {
            $atributo5 = addslashes(fnAcentos($venda['itens']['vendaitem']['atributo5']));
        }
        if ($venda['itens']['vendaitem']['atributo6'] == '') {
            $atributo6 = '';
        } else {
            $atributo6 = $venda['itens']['vendaitem']['atributo6'];
        }
        if ($venda['itens']['vendaitem']['atributo7'] == '') {
            $atributo7 = '';
        } else {
            $atributo7 = $venda['itens']['vendaitem']['atributo7'];
        }
        if ($venda['itens']['vendaitem']['atributo8'] == '') {
            $atributo8 = '';
        } else {
            $atributo8 = $venda['itens']['vendaitem']['atributo8'];
        }
        if ($venda['itens']['vendaitem']['atributo9'] == '') {
            $atributo9 = '';
        } else {
            $atributo9 = $venda['itens']['vendaitem']['atributo9'];
        }
        if ($venda['itens']['vendaitem']['atributo10'] == '') {
            $atributo10 = '';
        } else {
            $atributo10 = $venda['itens']['vendaitem']['atributo10'];
        }
        if ($venda['itens']['vendaitem']['atributo11'] == '') {
            $atributo11 = '';
        } else {
            $atributo11 = $venda['itens']['vendaitem']['atributo11'];
        }
        if ($venda['itens']['vendaitem']['atributo12'] == '') {
            $atributo12 = '';
        } else {
            $atributo12 = $venda['itens']['vendaitem']['atributo12'];
        }
        if ($venda['itens']['vendaitem']['atributo13'] == '') {
            $atributo13 = '';
        } else {
            $atributo13 = $venda['itens']['vendaitem']['atributo13'];
        }

        //verificar se existe produtos com brindes
        $verifica_brind = "Select b.* FROM brindeextra b  
                                    INNER JOIN produtocliente prod ON prod.COD_PRODUTO=b.cod_produto
                                    WHERE   prod.COD_EXTERNO='" . $venda['itens']['vendaitem']['codigoproduto'] . "' AND 
                                            b.cod_cliente=$COD_CLIENTE AND 
                                            b.COD_STATUS=1 AND 
                                            b.COD_EMPRESA='" . $row['COD_EMPRESA'] . "' and
                                            date(dat_expira) >= date(NOW())";
        $rsprodbrind = mysqli_query($connUser->connUser(), $verifica_brind);
        if ($rsprodbrind->num_rows > 0) {
            $rowbrind = mysqli_fetch_assoc($rsprodbrind);
            $alterbrind = "UPDATE brindeextra SET COD_STATUS=2, COD_VENDA='" . $cod_venda . "', DAT_RESGATE='" . $venda['datahora'] . "',COD_UNIVEND='" . $dadosLogin['idloja'] . "', COD_VENDEDOR='" . $dadosLogin['codvendedor'] . "' WHERE  ID='" . $rowbrind['ID'] . "' and  COD_EMPRESA='" . $row['COD_EMPRESA'] . "';";
            mysqli_query($connUser->connUser(), $alterbrind);
        }


        $itemvendainsert = "call SP_INSERE_ITENS_SOAP($COD_CLIENTE,
                                                        '" . $row['COD_EMPRESA'] . "',
                                                         '" . $venda['itens']['vendaitem']['id_item'] . "',
                                                         '" . $cod_venda . "',
                                                         '" . $venda['itens']['vendaitem']['codigoproduto'] . "',
                                                         '" . fnAcentos($NOM_PROD) . "',     
                                                          '0',
                                                         '" . fnFormatvalor($venda['itens']['vendaitem']['quantidade'], $dec) . "',
                                                         '" . fnFormatvalor($venda['itens']['vendaitem']['valorbruto'], $dec) . "',
                                                         '" . fnFormatvalor($VAL_TOTITEM, $dec) . "',
                                                         '" . fnFormatvalor($venda['itens']['vendaitem']['descontovalor'], $dec) . "',
                                                         '" . fnFormatvalor($venda['itens']['vendaitem']['valorliquido'], $dec) . "',  
                                                         '" . $atributo1 . "',
                                                         '" . $atributo2 . "', 
                                                         '" . $atributo3 . "', 
                                                         '" . $atributo4 . "', 
                                                         '" . $atributo5 . "', 
                                                         '" . $atributo6 . "', 
                                                         '" . $atributo7 . "', 
                                                         '" . $atributo8 . "', 
                                                         '" . $atributo9 . "', 
                                                         '" . $atributo10 . "', 
                                                         '" . $atributo11 . "', 
                                                         '" . $atributo12 . "',
                                                         '" . $atributo13 . "',
                                                         '" . $dadosLogin['idloja'] . "',
                                                         '" . fnFormatvalor($estoque, $dec) . "'  
                                                          )";

        $itens = mysqli_query($connUser->connUser(), $itemvendainsert);
        /*if($cartao=='01734200014')
{
   return array('InsereVendaResponse'=>array('msgerro' => $itemvendainsert,
                                                    'coderro'=>'11'));
      exit();  
} */
        if (!$itens) {
            try {
                mysqli_query($connUser->connUser(), $itemvendainsert);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = "Error description itens Automatico Erro: $msgsql";

            $xamls = addslashes($msg);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', addslashes($itemvendainsert), $row['LOG_WS']);
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Itens inserido OK!', $row['LOG_WS']);
            //  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda', addslashes($itemvendainsert),$row['LOG_WS']);

        }

        unset($itemvendainsert);
    } else {

        for ($i = 0; $i <= count($venda['itens']['vendaitem']) - 1; $i++) {
            $NOM_PROD = addslashes(fnAcentos($venda['itens']['vendaitem'][$i]['produto']));
            //Pegar o tokem quando tiver mais de um item
            if ($row['COD_EMPRESA'] == 19 || $row['COD_EMPRESA'] == 70) {
                $PLACA_VEICULO = fnplacamercosul($venda['itens']['vendaitem'][$i]['atributo1']);
                $TOKEN_GERADO = $venda['itens']['vendaitem'][$i]['atributo2'];
            }


            $NOM_PROD = limitarTexto($NOM_PROD, 150);
            $VAL_TOTITEM = fnFormatvalor($venda['itens']['vendaitem'][$i]['quantidade'], $dec) * fnFormatvalor($venda['itens']['vendaitem'][$i]['valorliquido'], $dec);
            if (
                $venda['itens']['vendaitem'][$i]['estoque'] == '' ||
                $venda['itens']['vendaitem'][$i]['estoque'] == '?'
            ) {
                $estoque = 0;
            } else {
                $estoque = $venda['itens']['vendaitem'][$i]['estoque'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo1'] == '') {
                $atributo1 = '';
            } else {
                $atributo1 = $venda['itens']['vendaitem'][$i]['atributo1'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo2'] == '') {
                $atributo2 = '';
            } else {
                addslashes(fnAcentos($atributo2 = $venda['itens']['vendaitem'][$i]['atributo2']));
            }
            if ($venda['itens']['vendaitem'][$i]['atributo3'] == '') {
                $atributo3 = '';
            } else {
                $atributo3 = $venda['itens']['vendaitem'][$i]['atributo3'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo4'] == '') {
                $atributo4 = '';
            } else {
                $atributo4 = $venda['itens']['vendaitem'][$i]['atributo4'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo5'] == '') {
                $atributo5 = '';
            } else {
                addslashes(fnAcentos($atributo5 = $venda['itens']['vendaitem'][$i]['atributo5']));
            }
            if ($venda['itens']['vendaitem'][$i]['atributo6'] == '') {
                $atributo6 = '';
            } else {
                $atributo6 = $venda['itens']['vendaitem'][$i]['atributo6'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo7'] == '') {
                $atributo7 = '';
            } else {
                $atributo7 = $venda['itens']['vendaitem'][$i]['atributo7'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo8'] == '') {
                $atributo8 = '';
            } else {
                $atributo8 = $venda['itens']['vendaitem'][$i]['atributo8'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo9'] == '') {
                $atributo9 = '';
            } else {
                $atributo9 = $venda['itens']['vendaitem'][$i]['atributo9'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo10'] == '') {
                $atributo10 = '';
            } else {
                $atributo10 = $venda['itens']['vendaitem'][$i]['atributo10'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo11'] == '') {
                $atributo11 = '';
            } else {
                $atributo11 = $venda['itens']['vendaitem'][$i]['atributo11'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo12'] == '') {
                $atributo12 = '';
            } else {
                $atributo12 = $venda['itens']['vendaitem'][$i]['atributo12'];
            }
            if ($venda['itens']['vendaitem'][$i]['atributo13'] == '') {
                $atributo13 = '';
            } else {
                $atributo13 = $venda['itens']['vendaitem'][$i]['atributo13'];
            }
            //verificar se existe produtos com brindes
            $verifica_brind = "Select b.* FROM brindeextra b  
                                            INNER JOIN produtocliente prod ON prod.COD_PRODUTO=b.cod_produto
                                            WHERE   prod.COD_EXTERNO= '" . $venda['itens']['vendaitem'][$i]['codigoproduto'] . "' AND 
                                                    b.cod_cliente=$COD_CLIENTE AND 
                                                    b.COD_STATUS=1 AND 
                                                    b.COD_EMPRESA='" . $row['COD_EMPRESA'] . "' and
                                                    date(dat_expira) >= date(NOW())";
            $rsprodbrind = mysqli_query($connUser->connUser(), $verifica_brind);
            if ($rsprodbrind->num_rows > 0) {
                $rowbrind = mysqli_fetch_assoc($rsprodbrind);
                $alterbrind = "UPDATE brindeextra SET COD_STATUS=2, COD_VENDA='" . $cod_venda . "', DAT_RESGATE='" . $venda['datahora'] . "',COD_UNIVEND='" . $dadosLogin['idloja'] . "', COD_VENDEDOR='" . $dadosLogin['codvendedor'] . "' WHERE  ID='" . $rowbrind['ID'] . "' and  COD_EMPRESA='" . $row['COD_EMPRESA'] . "';";
                mysqli_query($connUser->connUser(), $alterbrind);
            }


            if ($row['COD_EMPRESA'] != '161' || $row['COD_EMPRESA'] != '405') {
                $itemvendainsert = "CALL SP_INSERE_ITENS_SOAP($COD_CLIENTE,
                                                                         '" . $row['COD_EMPRESA'] . "',
                                                                         '" . $venda['itens']['vendaitem'][$i]['id_item'] . "',
                                                                         '" . $cod_venda . "',
                                                                         '" . $venda['itens']['vendaitem'][$i]['codigoproduto'] . "',
                                                                         '" . $NOM_PROD . "', 
                                                                         '0',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['quantidade'], $dec) . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['valorbruto'], $dec) . "',
                                                                         '" . $VAL_TOTITEM . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['descontovalor'], $dec) . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['valorliquido'], $dec) . "',  
                                                                        '" . $atributo1 . "',
                                                                        '" . $atributo2 . "', 
                                                                        '" . $atributo3 . "', 
                                                                        '" . $atributo4 . "', 
                                                                        '" . $atributo5 . "', 
                                                                        '" . $atributo6 . "', 
                                                                        '" . $atributo7 . "', 
                                                                        '" . $atributo8 . "', 
                                                                        '" . $atributo9 . "', 
                                                                        '" . $atributo10 . "', 
                                                                        '" . $atributo11 . "', 
                                                                        '" . $atributo12 . "',
                                                                        '" . $atributo13 . "',
                                                                        '" . $dadosLogin['idloja'] . "',
                                                                        '" . fnFormatvalor($estoque, $dec) . "'    
                                                                          );";

                /* if($cartao=='52701437040')
                            {    
                            return array('InsereVendaResponse'=>array('msgerro' => $itemvendainsert,
                                                    'coderro'=>'11'));
                            }*/
                $itens = mysqli_query($connUser->connUser(), $itemvendainsert);

                if (!$itens) {
                    try {
                        mysqli_query($connUser->connUser(), $itemvendainsert);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }
                    $msg = "Error description itens Automatico Erro: $msgsql";
                    $xamls = addslashes($msg);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', addslashes($itemvendainsert), $row['LOG_WS']);
                } else {
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'venda e itens do loop OK!', $row['LOG_WS']);
                    //  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda', addslashes($itemvendainsert),$row['LOG_WS']);

                }
            } else {
                $itemvendainsert .= "CALL SP_INSERE_ITENS_SOAP($COD_CLIENTE,
                                                                         '" . $row['COD_EMPRESA'] . "',
                                                                         '" . $venda['itens']['vendaitem'][$i]['id_item'] . "',
                                                                         '" . $cod_venda . "',
                                                                         '" . $venda['itens']['vendaitem'][$i]['codigoproduto'] . "',
                                                                         '" . $NOM_PROD . "', 
                                                                         '0',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['quantidade'], $dec) . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['valorbruto'], $dec) . "',
                                                                         '" . $VAL_TOTITEM . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['descontovalor'], $dec) . "',
                                                                         '" . fnFormatvalor($venda['itens']['vendaitem'][$i]['valorliquido'], $dec) . "',  
                                                                        '" . $atributo1 . "',
                                                                        '" . $atributo2 . "', 
                                                                        '" . $atributo3 . "', 
                                                                        '" . $atributo4 . "', 
                                                                        '" . $atributo5 . "', 
                                                                        '" . $atributo6 . "', 
                                                                        '" . $atributo7 . "', 
                                                                        '" . $atributo8 . "', 
                                                                        '" . $atributo9 . "', 
                                                                        '" . $atributo10 . "', 
                                                                        '" . $atributo11 . "', 
                                                                        '" . $atributo12 . "',
                                                                        '" . $atributo13 . "',
                                                                        '" . $dadosLogin['idloja'] . "',
                                                                        '" . fnFormatvalor($estoque, $dec) . "'    
                                                                          );";
            }
        }
        /* if($dadosLogin['idmaquina']=='teste_diogo')
					{
					return array('InsereVendaResponse'=>array('msgerro' => print_r($itemvendainsert1),
															  'coderro'=>'8'));
					exit();
					}*/
        //  mysqli_multi_query($connUser->connUser(),$itemvendainsert);
        if ($row['COD_EMPRESA'] == '161' || $row['COD_EMPRESA'] == '405') {
            $itens = mysqli_multi_query($connUser->connUser(), $itemvendainsert);
        }
    }

    //==================================================================================================================== 
    // Procedure de inserir credito
    if ($venda['cartao'] == 0) {
        $cod_erro = 79;
        $msg = 'VENDA AVULSA OK';
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Clientes avulso nao gera credito!', $row['LOG_WS']);
        $xamls = addslashes($msg);
        Grava_log($connUser->connUser(), $cod_log, $xamls);
    } else {

        if ($cliente_cod['funcionario'] == 'S' && $row['LOG_PONTUAR'] == 'N' || $cliente_cod['LOG_ESTATUS'] == 'N') {
            $cod_creditou = "UPDATE VENDAS SET COD_CREDITOU=4 WHERE COD_VENDA='" . $cod_venda . "'";
            mysqli_query($connUser->connUser(), $cod_creditou);
            Grava_log($connUser->connUser(), $cod_log, 'Fucionario ou clientes inativos não Geram Creditos');
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Fucionario não Gera Creditos', $row['LOG_WS']);
        } else {
            if ($row['LOG_CREDAVULSO'] == 'S') {
                //creditos e debitos na rotina de credito fixo "credito enviado pelo cliente"
                //executa a campanha a tiva e creditos extras 
                /* $sql_credito1 = "CALL SP_INSERE_CREDITOS_WS('".$cod_venda."',
                                                                       0,      
                                                                       '".$row['COD_EMPRESA']."',
                                                                       '".$COD_CLIENTE."',    
                                                                       1,    
                                                                       1,
                                                                       '".$dadosLogin['idloja']."',
                                                                       '".$formapag."',
                                                                       '".fnFormatvalor($venda['valortotalbruto'],$dec)."',
                                                                       '0.00',
                                                                       '".fnFormatvalor($venda['descontototalvalor'],$dec)."',
                                                                       '".$venda['id_vendapdv']."',
                                                                       '".$row['COD_USUARIO']."',
                                                                       '".$cod_vendedor."'    
                                                                       );";
                          mysqli_query($connUser->connUser(), $sql_credito1);*/
                //=========================================================================    
                $sql_credito = " CALL SP_CREDITO_FIXO(    '" . $COD_CLIENTE . "', 
                                                                  '" . fnFormatvalor($venda['pontostotal'], $dec) . "', 
                                                                  '" . $venda['datahora'] . "', 
                                                                  '" . $row['COD_USUARIO'] . "', 
                                                                  'Venda OK', 
                                                                  '1', 
                                                                  '" . $dadosLogin['idloja'] . "', 
                                                                  '" . $row['COD_EMPRESA'] . "',
                                                                  'VEN',
                                                                 '" . $cod_venda . "',
                                                                 '" . $cod_vendedor . "',
                                                                  '" . $valor_resgate . "', 
                                                                  'CAD' );";
                /*if($cartao=='01734200014')
                        {
                           return array('InsereVendaResponse'=>array('msgerro' => $sql_credito,
                                                                            'coderro'=>'11'));
                              exit();  
                        } */
            } else {


                $sql_credito = "CALL SP_INSERE_CREDITOS_WS('" . $cod_venda . "',
                                                                       0,      
                                                                       '" . $row['COD_EMPRESA'] . "',
                                                                       '" . $COD_CLIENTE . "',    
                                                                       1,    
                                                                       1,
                                                                       '" . $dadosLogin['idloja'] . "',
                                                                       '" . $formapag . "',
                                                                       '" . fnFormatvalor($venda['valortotalbruto'], $dec) . "',
                                                                       '" . $valor_resgate . "',
                                                                       '" . fnFormatvalor($venda['descontototalvalor'], $dec) . "',
                                                                       '" . $venda['id_vendapdv'] . "',
                                                                       '" . $row['COD_USUARIO'] . "',
                                                                       '" . $cod_vendedor . "'    
                                                                       );";
            }


            //rotina de pontuação da rede duque
            /*
					Se não cupomdesconto não estiver preenchido vai entrar na rotina de pontuação
					*/


            if ($dadosLogin['idcliente'] == 19 || $dadosLogin['idcliente'] == 70 || $dadosLogin['idcliente'] == 7) {
                /*if($dadosLogin['idloja']=='697' || 
                                           $dadosLogin['idloja']=='96905' || 
                                           $dadosLogin['idloja']=='681' || 
                                           $dadosLogin['idloja']=='97620' || 
                                           $dadosLogin['idloja']=='673' ||
                                           $dadosLogin['idloja']=='682' ||
                                           $dadosLogin['idloja']=='97637' ||
                                           $dadosLogin['idloja']=='96904')
					{*/
                //if($venda['cupomdesconto']=='' || $dadosLogin['idloja']=='97385')
                if ($venda['cupomdesconto'] == '') {
                    //aqui esta pontuando
                    $SALDO_CLIENTE = mysqli_query($connUser->connUser(), $sql_credito);
                } else {


                    //aqui não esta pontuando
                    $cod_creditou = "UPDATE VENDAS SET COD_CREDITOU=5 WHERE COD_VENDA='" . $cod_venda . "'";
                    mysqli_query($connUser->connUser(), $cod_creditou);
                    $SALDO_CLIENTE = true;
                    // || $dadosLogin['idloja']=='97397'
                    if ($row['COD_EMPRESA'] == '19') {
                        //alterar os status da venda S e contabilizar os limits
                        $desconto_venda = "UPDATE desconto_venda SET LOG_VENDA='S', COD_VENDA_PROD=$cod_venda WHERE COD_VENDAPDV='" . $venda['id_vendapdv'] . "' and COD_EMPRESA=" . $row['COD_EMPRESA'] . ";";
                        $rsdesconto = mysqli_query($connUser->connUser(), $desconto_venda);
                        /*if(!$rsdesconto)
                                                                    {
                                                                         return array('InsereVendaResponse'=>array('msgerro' => 'OPS Erro ao processar os creditos!',
                                                                         'coderro'=>'0'));  
                                                                    } */
                    }
                }
            } else {
                $SALDO_CLIENTE = mysqli_query($connUser->connUser(), $sql_credito);
            }

            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Rotina de Gera credito OK...', $row['LOG_WS']);
            if (!$SALDO_CLIENTE) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                try {
                    mysqli_query($connUser->connUser(), $sql_credito);
                } catch (mysqli_sql_exception $e) {
                    $msgsql = $e;
                }
                $msgerro = "Error creditos e debitos: $msgsql";
                $xamls = addslashes($msgerro . '-----' . $sql_credito);
                // Grava_log($connUser->connUser(),$cod_log,'OPS Erro ao processar os creditos avulso!');
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);
                $return = array('InsereVendaResponse' => array(
                    'msgerro' => 'OPS Erro ao processar os creditos!',
                    'coderro' => '0'
                ));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, 'OPS Erro ao processar os creditos avulso!', addslashes($xml_user_info->asXML()));

                $xamls = addslashes($msgerro . '-----' . $sql_credito);
                // Grava_log($connUser->connUser(),$cod_log,'OPS Erro ao processar os creditos avulso!');
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $row['LOG_WS']);

                return $return;
                exit();
            } else {
                //exibir saldo cliente
                $rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
                mysqli_next_result($SALDO_CLIENTE);


                //atualizar informação de recebinento de sms/email 
                //================================================================
                if ($COD_CLIENTE > 0 && $cartao != 0) {

                    $array = array(
                        'WHERE' => "WHERE g.TIP_GATILHO in ('venda','resgate','credVen','credPush','resgPush') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                        'TABLE' => array(
                            'gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha',
                            'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha',
                            'gatilho_push g INNER  JOIN push_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha'
                        )
                    );
                    foreach ($array['TABLE'] as $KEY => $dadostable) {
                        $sqlgatilho_email = "SELECT * FROM $dadostable 
                    inner JOIN campanha c ON c.COD_CAMPANHA=p.COD_CAMPANHA AND c.LOG_ATIVO='S'
                    $array[WHERE]";
                        $rwgatilho_email = mysqli_query($connUser->connUser(), $sqlgatilho_email);

                        while ($rsgatilho_email = mysqli_fetch_assoc($rwgatilho_email)) {
                            if ($rsgatilho_email['TIP_GATILHO'] != '') {
                                if ($KEY == '0') {
                                    // gatilho de email
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                        $gatilho = '5';
                                    }
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'venda') {
                                        $gatilho = '6';
                                    }
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'credVen') {
                                        $gatilho = '9';
                                    }
                                }
                                if ($KEY == '1') {
                                    // gatilho de sms
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
                                if ($KEY == '2') {
                                    // gatilho de push
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'credPush') {
                                        $gatilho = '11';
                                    }
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'resgPush') {
                                        $gatilho = '12';
                                    }
                                }
                                if ($KEY == '3') {
                                    // gatilho de whatsapp
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                        $gatilho = '13';
                                    }
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'venda') {
                                        $gatilho = '14';
                                    }
                                    if ($rsgatilho_email['TIP_GATILHO'] == 'credVen') {
                                        $gatilho = '15';
                                    }
                                }

                                $cod_campanha = $rsgatilho_email['COD_CAMPANHA'];
                                $TIP_MOMENTO = $rsgatilho_email['TIP_MOMENTO'];
                                $TIP_GATILHO = $rsgatilho_email['TIP_GATILHO'];
                                $COD_PERSONAS = $rsgatilho_email['COD_PERSONAS'];

                                if ((float)fnFormatvalor($venda['valor_resgate'], $dec) <= '0.00') {
                                    $valorresgate = '0.00';
                                } else {
                                    $valorresgate = fnFormatvalor($venda['valor_resgate'], $dec);
                                }

                                if (fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal) == '') {
                                    $CREDITO_VENDA = '0,00';
                                } else {
                                    $CREDITO_VENDA = fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal);
                                }
                                if (fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal) == '') {
                                    $TOTAL_CREDITO = '0,00';
                                } else {
                                    $TOTAL_CREDITO = fnformatavalorretorno($rowSALDO_CLIENTE['TOTAL_CREDITO'], $decimal);
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
								  CRED_VENDA,
                                                                  COD_VENDA
                                                                   ) VALUES 
                                                                   ('" . $row['COD_EMPRESA'] . "', 
                                                                   '" . $dadosLogin['idloja'] . "', 
                                                                   '" . $COD_CLIENTE . "', 
                                                                   '" . $cliente_cod['cpf'] . "', 
                                                                   '" . addslashes(fnAcentos($cliente_cod['nome'])) . "', 
                                                                   '" . $cliente_cod['datanascimento'] . "', 
                                                                   '" . trim($cliente_cod['email']) . "',
                                                                   '" . $cliente_cod['telcelular'] . "',    
                                                                   '" . $cliente_cod['sexo'] . "', 
                                                                   '" . $cod_campanha . "', 
                                                                   '" . $TIP_MOMENTO . "',
                                                                   '$gatilho',
                                                                   '$TIP_GATILHO',
                                                                   '" . $TOTAL_CREDITO . "',
                                                                   '" . $valorresgate . "',
                                                                   '" . date("W", strtotime("-2 day", strtotime(date('Y-m-d H:i:s')))) . "',
                                                                   $rsgatilho_email[TIP_CONTROLE],
                                                                   '" . date('m') . "',
								   '" . $CREDITO_VENDA . "',
                                                                   $cod_venda    
                                                                   );";


                                //if($dadosbase[0]['telcelular']!='')
                                //{    
                                if ($rsgatilho_email['TIP_GATILHO'] == 'resgate') {
                                    if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
                                        mysqli_query($connUser->connUser(), $sqlfila);
                                    }
                                }
                                if ($rsgatilho_email['TIP_GATILHO'] == 'resgPush') {
                                    if ((float)fnFormatvalor($venda['valor_resgate'], $dec) > '0.00') {
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

                                if ($rsgatilho_email['TIP_GATILHO'] == 'credPush') {
                                    if (fnformatavalorretorno($rowSALDO_CLIENTE['CREDITO_VENDA'], $decimal) != '0,00') {
                                        mysqli_query($connUser->connUser(), $sqlfila);
                                    }
                                }
                                //}

                                unset($sqlfila);
                            }
                            /*$clas="CALL SP_PERSONA_CLASSIFICA_CADASTRO($COD_CLIENTE, ".$row['COD_EMPRESA'].", $cod_campanha, '".$COD_PERSONAS."',0)";
                 $testesql=mysqli_query($connUser->connUser(), $clas);  */
                        }
                    }
                }
                /*if($cartao=='01734200014')
                        {
                           return array('InsereVendaResponse'=>array('msgerro' => $sqlfila1,
                                                                            'coderro'=>'11'));
                              exit();  
                        } */

                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Creditos OK!', $row['LOG_WS']);
                //===================msg de resgate=================
                $cod_erro = 19;
                $msg = "Processo de venda concluido!";
                $xamls = addslashes($msg);

                //  Grava_log($connUser->connUser(),$cod_log,$xamls);  

                /* $sqlsaldo="select B.COD_STATUS,
                                                 B.DES_STATUS,
                                                 A.VAL_RESGATE_ORIG,
                                                 (SELECT VAL_CREDITO FROM CREDITOSDEBITOS
                                                 WHERE cod_venda=A.COD_VENDA AND
                                                 TIP_CREDITO='D') AS VAL_RESGATADO
                                                 from HISTORICOSTATUS A,STATUSMARKA B
                                                 where A.COD_STATUS=B.COD_STATUS AND
                                                 A.cod_status in(2,3,4) and
                                                 A.cod_venda=".$cod_venda;
									$rwsaldo=mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$sqlsaldo));
                              
     								if($rwsaldo['COD_STATUS']==4)
                                    {
                                        $VAL_RESGATE_ORIG=$rwsaldo['VAL_RESGATE_ORIG'];
                                        $VAL_RESGATADO=$rwsaldo['VAL_RESGATADO'];

                                        $msg="Valor Resgate menor que o permitido";
                                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda',$statusvenda,$row['LOG_WS']);

                                    }
                                    if ($rwsaldo['COD_STATUS']==2) {

                                        $VAL_RESGATE_ORIG=$rwsaldo['VAL_RESGATE_ORIG'];
                                        $VAL_RESGATADO=$rwsaldo['VAL_RESGATADO'];

                                        $msg="Valor Resgate Superior R$  $VAL_RESGATE_ORIG ao Permitido! : R$ $VAL_CONFIG";
                                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda',$statusvenda,$row['LOG_WS']);


                                    }    
                                     if ($rwsaldo['COD_STATUS']==3) {

                                        $VAL_RESGATE_ORIG=$rwsaldo['VAL_RESGATE_ORIG'];
                                        $VAL_RESGATADO=$rwsaldo['VAL_RESGATADO'];

                                        $msg="Valor Resgate Superior ao Saldo Disponível!";
                                        fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'InsereVenda',$statusvenda,$row['LOG_WS']);

                                     }*/

                //===============================================
            }
            //========================================aqui fim do else   
        }
        if ($dadosLogin['idcliente'] == '109') {
            $wholeUrl = 'https://api.hook.app.br/bw8/viacaogarcia/api/dados';
            $POSTJASON = '{ 
                           "conversion_identifier": "COD_VENDAS", 
                           "payload": {
                                           "dataHoraInicial": "",
                                           "dataHoraFinal": "",
                                           "codCliente": "",
                                           "codVenda": "' . $cod_venda . '",
                                           "cupom":"",
                                           "vendasFid":"S",
                                           "quantidadeLista": "100",
                                           "proximaPagina": "1"
                                       } 
                       }';
            $command = '/usr/bin/curl -H \'Content-Type: application/json\' -d \'' . $POSTJASON . '\' --url \'' . $wholeUrl . '\' >> /srv/www/htdocs/_system/log/request.log 2> /dev/null &';
            shell_exec($command);
        }
        //finda venda fideliza        
    }
    //faz o update na tokem da empresa 19 rede duque
    if ($row['COD_EMPRESA'] == 19 || $row['COD_EMPRESA'] == 70) {
        //$PLACA_VEICULO

        $atributo2 = $TOKEN_GERADO;
        $pdv = $venda['id_vendapdv'];
        $data_hora = date('Y-m-d H:i:s');
        if (trim($atributo2) != "") {
            $verifica = "select COD_PDV from tokem WHERE des_tokem='$atributo2'";
            $retornoverifica = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $verifica));

            if ($retornoverifica['COD_PDV'] == '') {
                $sqlupdate = "UPDATE tokem  SET 
                                         log_usado='S',
                                         COD_PDV='$pdv',
                                         dat_usado='$data_hora'
                               WHERE des_tokem='$atributo2'";
                $rwtokem = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlupdate));

                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'Tokem usado para essa venda!', $row['LOG_WS']);
            }
        }
    }

    //inserir canais de venda 

    if ($venda['canalvendas'] != '') {
        $canal_vendas = "INSERT INTO canal_vendas (
																COD_EMPRESA, 
																COD_UNIVEND,
																COD_VENDA, 
																COD_CLIENTE, 
																COD_CANAL_EXT
																) 
																VALUES 
																('" . $dadosLogin['idcliente'] . "', 
																'" . $dadosLogin['idloja'] . "',
																'" . $cod_venda . "', 
																'" . $COD_CLIENTE . "', 
																'" . $venda['canalvendas'] . "');";
        $rwcanal_vendas = mysqli_query($connUser->connUser(), $canal_vendas);
        if (!$rwcanal_vendas) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cartao, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'erro ao inserir o canal de vendas', $row['LOG_WS']);
        }
    }



    //   

    //======================================================================================================================     
    $arrayconsulta = array(
        'conn' => $connAdm->connAdm(),
        'ConnB' => $connUser->connUser(),
        'database' => $row['NOM_DATABASE'],
        'empresa' => $row['COD_EMPRESA'],
        'fase' => $fase,
        'cpf' => $cartao,
        'cnpj' => '',
        'cartao' =>  $cartao,
        'email' =>  '',
        'telefone' => '',
        'consultaativa' => $row['LOG_CONSEXT'],
        'login' => $dadosLogin['login'],
        'senha' => $dadosLogin['senha'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'codvendedor' => $dadosLogin['codvendedor'],
        'nomevendedor' => fnAcentos($dadosLogin['nomevendedor']),
        'cod_cliente' => $COD_CLIENTE,
        'COD_USUARIO' => $row['COD_USUARIO'],
        'pagina' => 'Inserirvenda',
        'venda' => 'venda',
        'coderro' => $cod_erro,
        'menssagem' => $msg,
        'LOG_WS' => $row['LOG_WS'],
        'dec' => $dec,
        'decimal' => $decimal,
        'creditovenda' => $rowSALDO_CLIENTE['CREDITO_VENDA'],
        'LOG_CADTOKEN' => $row['LOG_CADTOKEN']
    );
    ob_end_flush();
    ob_flush();

    //fnmemoriafinal($connUser->connUser(),$cod_men);


    $return = array('InsereVendaResponse' => fnreturn($arrayconsulta));
    array_to_xml($return, $xml_user_info);
    Grava_log_msgxml($connUser->connUser(), 'msg_venda', $cod_log, $xamls, addslashes($xml_user_info->asXML()));
    return $return;
}
