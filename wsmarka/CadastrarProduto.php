<?php
//=================================================================== GetURLTktMania ====================================================================
//retorno dados

$server->wsdl->addComplexType(
    'CadastroResult',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:integer')
    )
);

$server->wsdl->addComplexType(
    'Produto',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'nome' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'nome', 'type' => 'xsd:string'),
        'codigo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'codigo', 'type' => 'xsd:string'),
        'grupo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'grupo', 'type' => 'xsd:string'),
        'subgrupo' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'subgrupo', 'type' => 'xsd:string'),
        'pbm' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'BPM', 'type' => 'xsd:string'),
        'pontuar' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'pontuar', 'type' => 'xsd:string'),
        'fornecedor' => array('minOccurs' => '0', 'maxOccurs' => '1', 'fornecedor' => 'ean', 'type' => 'xsd:string'),
        'ean' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'ean', 'type' => 'xsd:string'),
        'atributo1' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo1', 'type' => 'xsd:string'),
        'atributo2' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo2', 'type' => 'xsd:string'),
        'atributo3' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo3', 'type' => 'xsd:string'),
        'atributo4' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo4', 'type' => 'xsd:string'),
        'atributo5' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo5', 'type' => 'xsd:string'),
        'atributo6' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo6', 'type' => 'xsd:string'),
        'atributo7' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo7', 'type' => 'xsd:string'),
        'atributo8' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo8', 'type' => 'xsd:string'),
        'atributo9' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo9', 'type' => 'xsd:string'),
        'atributo10' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo10', 'type' => 'xsd:string'),
        'atributo11' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo11', 'type' => 'xsd:string'),
        'atributo12' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo12', 'type' => 'xsd:string'),
        'atributo13' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'atributo13', 'type' => 'xsd:string'),
    )
);



$server->register(
    'CadastrarProduto',
    array(
        'Produto' => 'tns:Produto',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('Cadastro' => 'tns:CadastroResult'),  //output
    $ns,                                 // namespace
    "$ns/CadastrarProduto",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'EstornaVendaParcial'                 // documentation
);

function CadastrarProduto($CadastrarProduto, $dadosLogin)
{
    include_once '../_system/Class_conn.php';
    include_once './func/function.php';
    ob_start();
    /*8 $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
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

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
        return  array('Cadastro' => array(
            'msgerro' => 'LOJA DESABILITADA',
            'coderro' => '80'
        ));
        exit();
    }
    //conn user


    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        //$cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'CadastrarProduto',$dadosLogin['idcliente']);

        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        //$trimmed_array=array_map('trim',$xamls);	  
        $arraynormal = str_replace(" ", "", $xamls);
        $xmlteste = addslashes(file_get_contents("php://input"));
        $saida = preg_replace('/\s+/', ' ', $xmlteste);
        $inserarray = 'INSERT INTO origemCadProduto (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                 ("' . date("Y-m-d H:i:s") . '","' . $_SERVER['REMOTE_ADDR'] . '","' . $_SERVER['REMOTE_PORT'] . '",
                                  "' . $row['COD_USUARIO'] . '","' . $dadosLogin['login'] . '","' . $row['COD_EMPRESA'] . '","' . $dadosLogin['idloja'] . '","' . $dadosLogin['idmaquina'] . '","' . $CadastrarProduto['codigo'] . '",0,"' . $saida . '","' . $arralogin . '")';
        $arraP = mysqli_query($connUser->connUser(), $inserarray);
        if (!$arraP) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                mysqli_query($connUser->connUser(), $inserarray);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = "Error Cadastro de produto: $msgsql";
            $xamls = addslashes($msg);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', $xamls, $row['LOG_WS']);
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'PRODUTO OK!', $row['LOG_WS']);
        }
        // mysqli_free_result($arraP);
        // mysqli_next_result($connUser->connUser());               
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return  array('Cadastro' => array('msgerro' => 'Id_cliente não confere com o cadastro!'));
            exit();
        }
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('Cadastro' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('Cadastro' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '0'
            ));
            exit();
        }
        //////////////////////=================================================================================================================

    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('Cadastro' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '0'
        ));
        exit();
    }

    // checa se a categoria existe na base dados    
    $p_COD_CATEGOR = "select * from categoria where  COD_EMPRESA='" . $row['COD_EMPRESA'] . "' and  DES_CATEGOR='" . $CadastrarProduto['grupo'] . "'";
    $arrcategor = mysqli_query($connUser->connUser(), $p_COD_CATEGOR);
    if (!$arrcategor) {
        $msg = "Error description categoria: " . mysqli_error($connUser->connUser());
        $msg = addslashes($msg);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', $msg, $row['LOG_WS']);
        return array('Cadastro' => array('msgerro' => 'Erro ao cadastrar categoria'));
        exit();
    } else {
        $returcodcategor = mysqli_fetch_assoc($arrcategor);
        if ($returcodcategor['COD_EXTERNO'] == "") {
            if ($CadastrarProduto['grupo'] != '') {
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'categoria inserida!', $row['LOG_WS']);

                //cadastra categoria na base de dados
                $insert2 = 'insert into categoria(COD_EXTERNO,COD_EMPRESA,DES_CATEGOR,COD_USUCADA,DAT_CADASTR)
                                                                   VALUE("' . $CadastrarProduto['codigo'] . '",
                                                                         ' . $row['COD_EMPRESA'] . ',
                                                                         "' . $CadastrarProduto['grupo'] . '",
                                                                         ' . $row['COD_USUARIO'] . ',
                                                                         "' . date('Y-m-d H:m:s') . '");';
                mysqli_query($connUser->connUser(), $insert2);
                //   $cod_categor=$CadastrarProduto['codigo'];
            }
            $ID_COD_CATEGOR = "SELECT last_insert_id(COD_CATEGOR) as COD_CATEGOR from categoria ORDER by COD_CATEGOR DESC limit 1;";
            $COD_CATEGOR = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_COD_CATEGOR));
            $ID_CATEGOR = $COD_CATEGOR['COD_CATEGOR'];
        } else {
            $ID_CATEGOR = $returcodcategor['COD_CATEGOR'];
        }

        $msg = 'OK';
    }

    // checa se a SUBCATEGORIA existe na base dados    
    $p_COD_SUBCATE = "SELECT * FROM SUBCATEGORIA where  cod_empresa='" . $dadosLogin['idcliente'] . "' and  DES_SUBCATE='" . addslashes($CadastrarProduto['subgrupo']) . "'";
    $arrSUBCATE = mysqli_query($connUser->connUser(), $p_COD_SUBCATE);
    if ($arrSUBCATE->num_rows <= '0') {
        $ID_SUBCATE = '0';
    }
    if (!$arrSUBCATE) {
        $msg = "Error description Subcategoria: " . mysqli_error($connUser->connUser());
        $msg = addslashes($msg);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', $msg, $row['LOG_WS']);
        return array('Cadastro' => array('msgerro' => 'Erro ao cadastrar SUBCATEGORIA'));
        exit();
    } else {
        $returSUBCATE = mysqli_fetch_assoc($arrSUBCATE);

        if (!$returSUBCATE['DES_SUBCATE']) {
            if ($CadastrarProduto['subgrupo'] !== '') {
                //cadastra categoria na base de dados
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'cadastra sub-categoria na base de dados', $row['LOG_WS']);

                $insert3 = 'insert into subcategoria(COD_CATEGOR,COD_SUBEXTE,COD_EMPRESA,DES_SUBCATE,COD_USUCADA,DAT_CADASTR)
																   VALUE(' . $ID_CATEGOR . ',
																		 "' . $CadastrarProduto['codigo'] . '",
																		 ' . $row['COD_EMPRESA'] . ',
																		 "' . $CadastrarProduto['subgrupo'] . '",
																		 ' . $row['COD_USUARIO'] . ',
																		 "' . date('Y-m-d H:m:s') . '");';
                mysqli_query($connUser->connUser(), $insert3);

                //$cod_SUBCATE=$CadastrarProduto['codigo'];
                // if($CadastrarProduto['subgrupo']=='TESTE_SUB_GRUPO'){
                //		return array('Cadastro'=>array('msgerro' =>$insert));
                //	exit();
                // }
                $ID_COD_SUBCATE = "SELECT last_insert_id(COD_SUBCATE) as COD_SUBCATE from  subcategoria where cod_empresa='" . $dadosLogin['idcliente'] . "' ORDER by COD_SUBCATE DESC limit 1;";
                $COD_SUBCATE = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $ID_COD_SUBCATE));
                $ID_SUBCATE = $returSUBCATE['COD_SUBCATE'];
            }
        } else {
            $ID_SUBCATE = $returSUBCATE['COD_SUBCATE'];
        }

        $msg = 'OK';
    }
    // checa se a FORNECEDEO existe na base dados    
    $p_COD_FORNECEDOR = "SELECT * FROM FORNECEDORMRKA where  cod_empresa='" . $dadosLogin['idcliente'] . "' and  NOM_FORNECEDOR='" . $CadastrarProduto['fornecedor'] . "'";
    $arrFORNECEDOR = mysqli_query($connUser->connUser(), $p_COD_FORNECEDOR);
    if (!$arrFORNECEDOR) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($connUser->connUser(), $p_COD_FORNECEDOR);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = addslashes($msgsql);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', $msg, $row['LOG_WS']);
        return array('Cadastro' => array('msgerro' => 'Erro ao cadastrar FORNECEDOR'));
        exit();
    } else {
        $returFORNECEDOR = mysqli_fetch_assoc($arrFORNECEDOR);

        if ($returFORNECEDOR['COD_FORNECEDOR'] == "") {
            if ($CadastrarProduto['fornecedor'] != '') {
                //cadastra categoria na base de dados
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', 'FORNECEDORMRKA cadastrado!', $row['LOG_WS']);

                $insert1 = 'insert into FORNECEDORMRKA(COD_EXTERNO,COD_EMPRESA,NOM_FORNECEDOR,COD_USUCADA,DAT_CADASTR)
                                                               VALUE("' . $CadastrarProduto['codigo'] . '",
                                                                     ' . $row['COD_EMPRESA'] . ',
                                                                     "' . $CadastrarProduto['fornecedor'] . '",
                                                                     ' . $row['COD_USUARIO'] . ',
                                                                     "' . date('Y-m-d H:m:s') . '");';

                mysqli_query($connUser->connUser(), $insert1);
            }
            $COD_FORNECEDOR = "SELECT last_insert_id(COD_FORNECEDOR) as COD_FORNECEDOR from FORNECEDORMRKA where cod_empresa='" . $dadosLogin['idcliente'] . "' ORDER by COD_FORNECEDOR DESC limit 1;";
            $COD_SUBCATE = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $COD_FORNECEDOR));
            $cod_FORNECEDOR = $COD_SUBCATE['COD_FORNECEDOR'];
        } else {
            $p_COD_FORNECEDOR1 = "SELECT * FROM FORNECEDORMRKA where  cod_empresa='" . $dadosLogin['idcliente'] . "' and  NOM_FORNECEDOR='" . $CadastrarProduto['fornecedor'] . "'";
            $arrFORNECEDOR1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $p_COD_FORNECEDOR1));
            $cod_FORNECEDOR = $arrFORNECEDOR1['COD_FORNECEDOR'];
        }
        if ($CadastrarProduto['fornecedor'] == '') {
            $cod_FORNECEDOR = '0';
        }

        $msg = 'OK';
    }

    $sql = 'CALL SP_INSERE_PRODUTOCLIENTE_WS(
                    0,
                    "' . $CadastrarProduto['codigo'] . '",
                    ' . $row['COD_EMPRESA'] . ',
                    "' . $CadastrarProduto['ean'] . '",
                    "' . $CadastrarProduto['nome'] . '",
                    ' . $ID_CATEGOR . ',    
                    ' . $ID_SUBCATE . ',
                    ' . $cod_FORNECEDOR . ',
                    "' . $CadastrarProduto['atributo1'] . '",
                    "' . $CadastrarProduto['atributo2'] . '",
                    "' . $CadastrarProduto['atributo3'] . '",
                    "' . $CadastrarProduto['atributo4'] . '",
                    "' . $CadastrarProduto['atributo5'] . '",
                    "' . $CadastrarProduto['atributo6'] . '",
                    "' . $CadastrarProduto['atributo7'] . '",
                    "' . $CadastrarProduto['atributo8'] . '",
                    "' . $CadastrarProduto['atributo9'] . '",
                    "' . $CadastrarProduto['atributo10'] . '",
                    "' . $CadastrarProduto['atributo11'] . '",
                    "' . $CadastrarProduto['atributo12'] . '",
                    "' . $CadastrarProduto['atributo13'] . '",
                    " ",
                    ' . $row['COD_USUARIO'] . ',
                    "' . $CadastrarProduto['pbm'] . '",   
                   "CAD"
                    );';
    //    return array('Cadastro'=>array('msgerro' =>$sql));
    $rsPRODUTOCLIENTE = mysqli_query($connUser->connUser(), $sql);
    if (!$rsPRODUTOCLIENTE) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($connUser->connUser(), $sql);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = addslashes($msgsql);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'CadastrarProduto', $msg, $row['LOG_WS']);
        return array('Cadastro' => array('msgerro' => $msg));
        exit();
    } else {
        $rsRetorno = mysqli_fetch_assoc($rsPRODUTOCLIENTE);
        $rt = $rsRetorno['COD_PRODUTO'];
    }

    //grupo atualizacao                    
    $atualizadadosprod = "SELECT COUNT(1) qtd FROM produtocliente 
						WHERE 
						  cod_empresa='" . $row['COD_EMPRESA'] . "' 
						  AND cod_externo='" . $CadastrarProduto['codigo'] . "' 
						  AND COD_CATEGOR='" . $ID_CATEGOR . "'";
    $wsprodatualiza = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $atualizadadosprod));

    if ($wsprodatualiza['qtd'] <= '0') {
        $updateprod = "UPDATE produtocliente SET COD_CATEGOR='" . $ID_CATEGOR . "' 
		                                WHERE COD_EMPRESA='" . $row['COD_EMPRESA'] . "'  
						and cod_externo='" . $CadastrarProduto['codigo'] . "';";
        mysqli_query($connUser->connUser(), $updateprod);
    }

    //atualizacao de subcategor
    $atualizadadosprodsub = "SELECT COUNT(1) qtd FROM produtocliente 
                                                    WHERE 
                                                      cod_empresa='" . $row['COD_EMPRESA'] . "' 
                                                      AND cod_externo='" . $CadastrarProduto['codigo'] . "' 
                                                      AND COD_SUBCATE='" . $ID_SUBCATE . "';";
    $wsprodatualiza1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $atualizadadosprodsub));
    if ($wsprodatualiza1['qtd'] <= '0') {

        $updateprodsub = "UPDATE produtocliente SET COD_SUBCATE='" . $ID_SUBCATE . "' 
                                                WHERE COD_EMPRESA='" . $row['COD_EMPRESA'] . "'  
                                                 and cod_externo='" . $CadastrarProduto['codigo'] . "';";
        mysqli_query($connUser->connUser(), $updateprodsub);
    }
    //fornecedor
    $atualizadadosprodsub = "SELECT COUNT(1) qtd FROM produtocliente 
                                                    WHERE 
                                                      cod_empresa='" . $row['COD_EMPRESA'] . "' 
                                                      AND cod_externo='" . $CadastrarProduto['codigo'] . "' 
                                                      AND COD_FORNECEDOR='" . $cod_FORNECEDOR . "';";
    $wsprodatualiza1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $atualizadadosprodsub));
    if ($wsprodatualiza1['qtd'] <= '0') {

        $updateprodsub = "UPDATE produtocliente SET COD_FORNECEDOR='" . $cod_FORNECEDOR . "' 
								WHERE COD_EMPRESA='" . $row['COD_EMPRESA'] . "'  
								  and cod_externo='" . $CadastrarProduto['codigo'] . "';";
        mysqli_query($connUser->connUser(), $updateprodsub);
    }
    //==========================================ativar produto excluido
    $atualizadadosprodsub = "SELECT COUNT(1) qtd FROM produtocliente 
                                            WHERE 
                                              cod_empresa='" . $row['COD_EMPRESA'] . "' 
                                              AND cod_externo='" . $CadastrarProduto['codigo'] . "' 
                                              AND COD_EXCLUSA > 0;";
    $wsprodatualiza1 = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $atualizadadosprodsub));
    if ($wsprodatualiza1['qtd'] > '0') {

        $updateprodsub = "UPDATE produtocliente SET COD_EXCLUSA='0', 
                                                          DAT_EXCLUSA = NULL
                                WHERE COD_EMPRESA='" . $row['COD_EMPRESA'] . "'  
                                and cod_externo='" . $CadastrarProduto['codigo'] . "';";
        mysqli_query($connUser->connUser(), $updateprodsub);
    }

    //==========================================Atualização de trodutos
    $atualizadadosprod = "SELECT COUNT(1) qtd FROM produtocliente 
                                    WHERE 
                                          cod_empresa='" . $row['COD_EMPRESA'] . "' 
                                          AND cod_externo='" . $CadastrarProduto['codigo'] . "';";
    $wsAt_Prod = mysqli_query($connUser->connUser(), $atualizadadosprod);
    if ($wsAt_Prod->num_rows > '0') {

        if ($CadastrarProduto['pontuar'] != '' && $CadastrarProduto['pontuar'] == 'S') {
            $logpontuar = '0';
        } else {
            $logpontuar = '1';
        }
        $upprod = "UPDATE produtocliente SET    
                                                            EAN ='" . $CadastrarProduto['ean'] . "',
                                                            DES_PRODUTO='" . $CadastrarProduto['nome'] . "' ,
                                                            LOG_PONTUAR= $logpontuar,
                                                            LOG_PRODPBM='" . $CadastrarProduto['pbm'] . "'
                                WHERE COD_EMPRESA='" . $row['COD_EMPRESA'] . "'  
                                and cod_externo='" . $CadastrarProduto['codigo'] . "';";
        mysqli_query($connUser->connUser(), $upprod);
    }

    //=============================================================================
    //fnmemoriafinal($connUser->connUser(),$cod_men);
    ob_end_flush();
    ob_flush();
    return  array(
        'Cadastro' =>
        array(
            'msgerro' => $msg,
            'coderro' => '0'
        )
    );
}
