<?php
$server->register(
    'EstornaVenda',
    array(
        'fase' => 'xsd:string',
        'id_vendapdv' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('EstornaVendaResponse' => 'tns:acao'),  //output
    $ns,                                 // namespace
    "$ns/EstornaVenda",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'EstornaVenda'                 // documentation
);
function EstornaVenda($fase, $id_vendapdv, $dadosLogin)
{
    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';
    ob_start();

    /* $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
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

    /*	$dec=$row['NUM_DECIMAIS']; 
     if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/
    //compara os id_cliente com o cod_empresa

    //verifica se a loja foi delabilitada
    $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
    $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
    if ($lojars['LOG_ESTATUS'] != 'S') {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata', $row['LOG_WS']);
        return  array('EstornaVendaResponse' => array(
            'msgerro' => 'LOJA DESABILITADA',
            'coderro' => '80'
        ));
        exit();
    }

    ///////////log
    //==================================


    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);

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
                    $casasDec = '0';
                }

                $LOG_CADVENDEDOR = $RWCONFIGUNI['LOG_CADVENDEDOR'];
            } else {
                //aqui pega da controle de licença
                $dec = $row['NUM_DECIMAIS'];
                if ($row['TIP_RETORNO'] == 2) {
                    $decimal = '2';
                } else {
                    $casasDec = '0';
                }
                $LOG_CADVENDEDOR = $row['LOG_CADVENDEDOR'];
            }
        }


        //$cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'EstornaVenda',$dadosLogin['idcliente']);

        //grava log
        $xmlteste = addslashes(file_get_contents("php://input"));
        $saida = preg_replace('/\s+/', ' ', $xmlteste);
        /*$inserarray='INSERT INTO origemestornavenda (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
            ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
             "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$id_vendapdv.'","0","'.$saida.'","'.$arralogin.'")';
 mysqli_query($connUser->connUser(),$inserarray);
 */
        $arrylog = array(
            'cod_usuario' => $row['COD_USUARIO'],
            'login' => $dadosLogin['login'],
            'cod_empresa' => $row['COD_EMPRESA'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'cpf' => '0',
            'xml' => $saida,
            'tables' => 'origemestornavenda',
            'conn' => $connUser->connUser(),
            'pdv' => $id_vendapdv
        );

        $cod_log = fngravalogxml($arrylog);
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><EstornaVendaResponse></EstornaVendaResponse>");

        ///=========================        
        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVenda', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return  array('EstornaVendaResponse' => array(
                'msgerro' => 'Id_cliente não confere com o cadastro!',
                'coderro' => '4'
            ));
            exit();
        }
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVenda', 'A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('EstornaVendaResponse' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            exit();
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('EstornaVendaResponse' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '44'
            ));
            exit();
        }
        //////////////////////=================================================================================================================


    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], '', $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'EstornaVenda', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('EstornaVendaResponse' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }



    //=========VENDA WS=========================================================================   
    $SQLVENDA_WS = "CALL SP_ESTORNA_VENDA_WS('" . $row['COD_EMPRESA'] . "', '" . $row['COD_USUARIO'] . "', '" . $id_vendapdv . "','" . $dadosLogin['idloja'] . "')";

    try {
        $VENDA_WS = mysqli_query($connUser->connUser(), $SQLVENDA_WS);
        $row_estornaV = mysqli_fetch_assoc($VENDA_WS);

        if ($row_estornaV['msgerro'] == 'OK') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $row_estornaV['v_COD_CLIENT'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Venda', 'Estorno OK!', $row['LOG_WS']);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $row_estornaV['v_COD_CLIENT'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Venda', addslashes($SQLVENDA_WS), $row['LOG_WS']);
            $COD_CLIENTE = $row_estornaV['v_COD_CLIENT'];
            $menssagem = $row_estornaV['msgerro'];
            $cod_erro = 39;
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $row_estornaV['v_COD_CLIENT'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Venda', 'Venda Já excluida!', $row['LOG_WS']);
            $COD_CLIENTE = $row_estornaV['v_COD_CLIENT'];
            $menssagem = $row_estornaV['msgerro'];
            $cod_erro = 40;
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $row_estornaV['v_COD_CLIENT'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Venda', addslashes($SQLVENDA_WS), $row['LOG_WS']);
        }
        //=============================================================================================

    } catch (mysqli_sql_exception $e) {
        $xamls = addslashes($e);
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $row_estornaV['v_COD_CLIENT'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'Estorna Venda', $xamls, $row['LOG_WS']);
    }

    if ($dadosLogin['idloja'] == '97397') {
        $desconto_venda = "UPDATE desconto_venda SET LOG_VENDA='E'  WHERE COD_VENDAPDV='" . $id_vendapdv . "' and COD_EMPRESA=" . $row['COD_EMPRESA'] . ";";
        $rsdesconto = mysqli_query($connUser->connUser(), $desconto_venda);
    }

    $arrayconsulta = array(
        'conn' => $connAdm->connAdm(),
        'ConnB' => $connUser->connUser(),
        'database' => $row['NOM_DATABASE'],
        'cod_cliente' => $COD_CLIENTE,
        'empresa' => $row['COD_EMPRESA'],
        'fase' => $fase,
        'cpf' => $cpf,
        'cnpj' => $cliente['cnpj'],
        'cartao' =>  $cartao,
        'email' =>  $cliente['email'],
        'telefone' =>  $cliente['telefone'],
        'consultaativa' => $row['LOG_CONSEXT'],
        'login' => $dadosLogin['login'],
        'senha' => $dadosLogin['senha'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'codvendedor' => $dadosLogin['codvendedor'],
        'nomevendedor' => $dadosLogin['nomevendedor'],
        'COD_USUARIO' => $row['COD_USUARIO'],
        'pagina' => 'Estornavenda',
        'coderro' => $cod_erro,
        'venda' => 'nao',
        'menssagem' => $menssagem,
        'LOG_WS' => $row['LOG_WS'],
        'dec' => $dec,
        'decimal' => $decimal
    );

    ob_end_flush();
    ob_flush();
    // fnmemoriafinal($connUser->connUser(),$cod_men);
    $return = array('EstornaVendaResponse' => fnreturn($arrayconsulta));
    array_to_xml($return, $xml_user_info);
    Grava_log_msgxml($connUser->connUser(), 'msg_estornavenda', $cod_log, 'Estorno de Venda Concluido!', addslashes($xml_user_info->asXML()));
    return $return;
}
