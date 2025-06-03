<?php
$server->register(
    'AtualizaCadastro',
    array(
        'fase' => 'xsd:string',
        'cliente' => 'tns:acao_cadastro',
        'dadosLogin' => 'tns:LoginInfo'
    ),  //parameters
    array('AtualizaCadastroResponse' => 'tns:acao'),  //output
    $ns,                                 // namespace
    "$ns/AtualizaCadastro",                             // soapaction
    'document',                         // style
    'literal',                          // use
    'AtualizaCadastro'                 // documentation
);


function AtualizaCadastro($fase, $cliente, $dadosLogin)
{
    ob_start();

    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';
    $dadosLogin['idloja'] = preg_replace('/\s+/', '', $dadosLogin['idloja']);
    /* 
     if($cliente['cpf']=='44577472899')
    {
      
           return  array('AtualizaCadastroResponse'=>array('msgerro'=> print_r($cliente),
                                                           'coderro'=>'6'));
           exit();
    } 
   */

    if (fnlimpaCPF($cliente['cnpj'] != '' && fnlimpaCPF($cliente['cpf']) == '')) {
        $cpf = fnlimpaCPF($cliente['cnpj']);
        //perguntar se data de nascimento PJ ou sexo esta vazio
        if ($cliente['tipocliente'] == 'PJ' || $cliente['tipocliente'] == 'pj' || $cliente['tipocliente'] == 'J') {
            if ($cliente['datanascimento'] == '') {
                $cliente['datanascimento'] = date('d/m/Y');
            }
            if ($cliente['sexo'] == '' || $cliente['sexo'] == 'U') {
                $cliente['sexo'] = '1';
            }
        }
    } else {
        $cpf = fnlimpaCPF($cliente['cpf']);
    }


    //$cnpj=fnlimpaCPF($cliente['cnpj']);
    $cartao = fnlimpaCPF($cliente['cartao']);
    if ($cliente['sexo'] == '' || $cliente['sexo'] == '0') {
        $sexo = 3;
    } else {
        $sexo = $cliente['sexo'];
    }

    //// login senha
    /*$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
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

    $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"  encoding=\"utf-8\"?><AtualizaCadastroResponse></AtualizaCadastroResponse>");
    //array_to_xml($return,$xml_user_info);
    //Grava_log($connUser->connUser(),$LOG,'Valor Resgate maior que o permitido', addslashes($xml_user_info->asXML()));

    /* $dec=$row['NUM_DECIMAIS']; 
     if ($row['TIP_RETORNO']== 2){$decimal = 2;}else {$casasDec = 0;}*/

    if (isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])) {
        //VERIFICA SE A EMPRESA FOI DESABILITADA
        if ($row['LOG_ATIVO'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('AtualizaCadastroResponse' => array(
                'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
                'coderro' => '6'
            ));
            exit();
        }

        //////////////////////=================================================================================================================
        if ($dadosLogin['idloja'] != '') {
            if ($dadosLogin['idloja'] != '0') {
                //verifica se a loja foi delabilitada
                $lojasql = 'SELECT LOG_ESTATUS FROM unidadevenda
                       WHERE COD_UNIVEND=' . $dadosLogin['idloja'] . ' AND cod_empresa=' . $dadosLogin['idcliente'];
                $lojars = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
                if ($lojars['LOG_ESTATUS'] != 'S') {
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' Loja desabilidata=' . $dadosLogin['idloja'], $row['LOG_WS']);
                    return  array('AtualizaCadastroResponse' => array(
                        'msgerro' => 'LOJA DESABILITADA',
                        'coderro' => '80'
                    ));
                    exit();
                }
            }
        }
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if ($row['LOG_ESTATUS'] == 'N') {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', ' A empresa foi desabilitada por algum motivo', $row['LOG_WS']);
            return  array('AtualizaCadastroResponse' => array(
                'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
                'coderro' => '44'
            ));
            exit();
        }
        //////////////////////=================================================================================================================


        $msg = validaCampo($cliente['tipocliente'], 'tipocliente', 'string');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $cliente['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '108'
            ));
            exit();
        }

        /* $msg = validaCampo(preg_replace("/\s+/", "", $cliente['email']), 'email', 'email');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $cliente['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '32'
            ));
            exit();
        }*/

        $msg = validaCampo($dadosLogin['idloja'], 'idloja', 'numeric');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '20'
            ));
            exit();
        }

        $msg = validaCampo($dadosLogin['idcliente'], 'idcliente', 'numeric');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '26'
            ));
            exit();
        }

        $msg = validaCampo($cliente['profissao'], 'profissao', 'numeric');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '26'
            ));
            exit();
        }


        $msg = validaCampo($cliente['sexo'], 'sexo', 'numeric');
        if (!empty($msg)) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '30'
            ));
            exit();
        }



        //valida cpf

        //COD_CHAVECO
        if ($row['COD_CHAVECO'] == 1 || $row['COD_CHAVECO'] == 5) {

            if ($cpf == 0 || $cartao == 0) {
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Cliente 0 Não pode ser atualizado ou Inserido', $row['LOG_WS']);
                return array('AtualizaCadastroResponse' => array(
                    'msgerro' => 'Cliente 0 Não pode ser atualizado ou Inserido',
                    'coderro' => '42'
                ));
                exit();
            }
            /* if($cliente['cpf']!="" )
        {    
             if(valida_cpf($cliente['cpf']))
              {}
              else{
                  fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro','CPF digitado é invalido');
                  return array('AtualizaCadastroResponse'=>array('msgerro' => 'CPF digitado é invalido',
                                                                  'coderro'=>'33')); 
                  exit();
             }
         }*/
        }
        //else{$cpf=0;}

        //==estado civil
        if ($cliente['estadocivil'] == '' || $cliente['estadocivil'] == '?') {
            $estadocivil = '0';
        } else {
            $estadocivil = $cliente['estadocivil'];
        }
        // =============profissao=======================
        if ($cliente['profissao'] == '' || $cliente['profissao'] == '?') {
            $profissao = '0';
        } else {
            $profissao = $cliente['profissao'];
        }
        //compara os id_cliente com o cod_empresa
        // return  array('BuscaConsumidorResponse'=>array('msgerro'=>$row['COD_CHAVECO'])); 



        //conn user
        $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
        //  $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'AtualizaCadastro',$dadosLogin['idcliente']);
        //grava array na base de dados
        //inserir venda inteira na base de dados 

        ///////////////////////////////////////////////////////
        $xmlteste = addslashes(file_get_contents("php://input"));
        $arrylog = array(
            'cod_usuario' => $row['COD_USUARIO'],
            'login' => $dadosLogin['login'],
            'cod_empresa' => $row['COD_EMPRESA'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'cpf' => $cpf,
            'xml' => $xmlteste,
            'tables' => 'origemcadastro',
            'conn' => $connUser->connUser()

        );
        $cod_log = fngravalogxml($arrylog);

        //valida campo obrigatorio
        if ($row['LOG_ATIVCAD'] == 'N' || $row['LOG_ATIVCAD'] == 'S') {
            //$row['LOG_CADTOKEN']
            //buscar o cliente

            $sqlconsumidor = "SELECT COD_CLIENTE from clientes  WHERE NUM_CGCECPF='$cpf' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";
            $rwconsumidor = mysqli_query($connUser->connUser(), $sqlconsumidor);

            if ($row['LOG_CADTOKEN'] == 'S') {
                if ($row['LOG_CADTOKEN'] == 'S' && $rwconsumidor->num_rows <= 0) {
                    //valida o campo de token somente no primeiro cadastro.
                    $msg = valida_campo($connAdm->connAdm(), $cliente, $dadosLogin, 'AtualizaCadastroResponse', $cod_log, $row['LOG_WS'], '0');
                    if ($msg != 0) {
                        Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg['AtualizaCadastroResponse']['msgerro'], $msg['AtualizaCadastroResponse']['msgerro']);
                        return $msg;
                        exit();
                    }
                } else {
                    //no update nao é requerido o campo de token
                    $msg = valida_campo($connAdm->connAdm(), $cliente, $dadosLogin, 'AtualizaCadastroResponse', $cod_log, $row['LOG_WS'], 'tokencadastro');
                    if ($msg != 0) {
                        Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg['AtualizaCadastroResponse']['msgerro'], $msg['AtualizaCadastroResponse']['msgerro']);
                        return $msg;
                        exit();
                    }
                }
            } else {

                $msg = valida_campo($connAdm->connAdm(), $cliente, $dadosLogin, 'AtualizaCadastroResponse', $cod_log, $row['LOG_WS']);
                if ($msg != 0) {
                    Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg['AtualizaCadastroResponse']['msgerro'], $msg['AtualizaCadastroResponse']['msgerro']);
                    return $msg;
                    exit();
                }
            }
        }

        $msg = validaCampo(preg_replace("/\s+/", "", $cliente['email']), 'email', 'email');
        if (!empty($msg)) {
            Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg, $msg);
            return array('AtualizaCadastroResponse' => array(
                'msgerro' => $msg,
                'coderro' => '32'
            ));
            exit();
        }


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

        //VERIFICA SE ID EMPRESA E IGUAL ENVIADO NO CAMPO
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente']) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Id_cliente não confere com o cadastro!', $row['LOG_WS']);
            return  array('AtualizaCadastroResponse' => array(
                'msgerro' => 'Id_cliente não confere com o cadastro!',
                'coderro' => '4'
            ));
            exit();
        }
    } else {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Usuario ou senha Inválido!', $row['LOG_WS']);
        return  array('AtualizaCadastroResponse' => array(
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }



    $msg = validaCampo($cliente['datanascimento'], 'datanascimento', 'DATA_BR');
    if (!empty($msg)) {
        $return = array('AtualizaCadastroResponse' => array('msgerro' => $msg, 'coderro' => '29'));
        array_to_xml($return, $xml_user_info);
        Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg, addslashes($xml_user_info->asXML()));
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
        return $return;
        exit();
    }


    ////////////////////////////////////////////////////////
    //  verificar cpf/cnpj                   
    //fim da gravação
    //PROCEDURE DE GRAVAÇÂO/ALTERAÇÂO
    // Declara a data! :P
    $data = $cliente['datanascimento'];
    $idadecalc = calc_idade($data);
    //atualiza cliente se ja existe na base de dados
    $arraydata = explode("/", $cliente['datanascimento']);
    if ($arraydata[0] == '') {
        $idadecalc = 0;
        $arraydata0 = 0;
        $arraydata1 = 0;
        $arraydata2 = 0;
    } else {
        $idadecalc = $idadecalc;
        $arraydata0 = $arraydata[0];
        $arraydata1 = $arraydata[1];
        $arraydata2 = $arraydata[2];
    }

    //não deixar cadastrar menos de <18 anos   

    if ($row['NUM_IDADEMIN'] > 0) {
        if (validarIdade($data, $row['NUM_IDADEMIN'])) {
        } else {
            //verificar se o cliente existe e ignorar a msg de retorno
            $sqlconsumidor = "SELECT COD_CLIENTE from clientes  WHERE NUM_CGCECPF='$cpf' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";
            $rwconsumidor = mysqli_query($connUser->connUser(), $sqlconsumidor);
            if ($rwconsumidor->num_rows <= 0) {
                $return = array('AtualizaCadastroResponse' => array('msgerro' => 'Programa restrito para maiores de 18 anos!', 'coderro' => '29'));
                array_to_xml($return, $xml_user_info);
                Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg, addslashes($xml_user_info->asXML()));
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
                return $return;
                exit();
            }
        }
    }

    //formata PF/PJ
    //if(trim($cliente['tipocliente'])=='PF' || trim($cliente['tipocliente'])=='')  
    //{$TP_CLIENTE='F';}else{$TP_CLIENTE='J';} 
    // print_r($arraydata);           
    //codatendente

    $cod_atendente = fnatendente($connAdm->connAdm(), $cliente['codatendente'], $dadosLogin['idcliente'], $dadosLogin['idloja'], $cliente['codatendente']);
    //atualização de cartao
    //====================================================================================
    if ($row['COD_EMPRESA'] == 221) {
        if ($row['COD_CHAVECO'] == '2') {
            $geracartao = "select  
                             (SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
                              cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=" . $row['COD_EMPRESA'];
            $rsgeracartao = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $geracartao));

            /* if(($rsgeracartao['contador']==0) && ($row['COD_CHAVECO']=='5') && (strlen($cartao)!=11) || (strlen($cartao)!=14))
        {
            $return=array('AtualizaCadastroResponse'=>array('msgerro' => 'Cartão invalido!', 'coderro'=>'29'));
            array_to_xml($return,$xml_user_info);
            Grava_log_msgxml($connUser->connUser(),'msg_cadastra',$cod_log,$msg,addslashes($xml_user_info->asXML()));
            fngravalogMSG($connAdm->connAdm(),$dadosLogin['login'],$dadosLogin['idcliente'],$cpf,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],'AtualizaCadastro',$msg,$row['LOG_WS']); 
            return $return;
            exit();
        }*/

            if (strlen($cartao) == $rsgeracartao['NUM_TAMANHO']) {
                $arrayconsulta = array(
                    'ConnB' => $connUser->connUser(),
                    'conn' => $connAdm->connAdm(),
                    'database' => $row['NOM_DATABASE'],
                    'empresa' => $row['COD_EMPRESA'],
                    'fase' => $fase,
                    'cartao' =>  fnlimpaCPF($cliente['cartao']),
                    'cpf' => fnlimpaCPF($cliente['cpf']),
                    'cnpj' => $cliente['cnpj'],
                    'login' => $dadosLogin['login'],
                    'senha' => $dadosLogin['senha'],
                    'idloja' => $dadosLogin['idloja'],
                    'idmaquina' => $dadosLogin['idmaquina'],
                    'codvendedor' => $dadosLogin['codvendedor'],
                    'nomevendedor' => $dadosLogin['nomevendedor'],
                    'COD_USUARIO' => $row['COD_USUARIO'],
                    'pagina' => 'BuscaConsumidor',
                    'COD_UNIVEND' => $dadosLogin['idloja'],
                    'venda' => 'nao',
                    'generico' => $opcoesbuscaconsumidor['genericobuscaconsumidor'],
                    'LOG_WS' => $row['LOG_WS']
                );
                $arraybuscacartao[] = fn_consultaBase($arrayconsulta);
                if ($arraybuscacartao[0]['coderro'] == '13') {

                    if ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado'] == 'N') {
                        //update na tabela de cartoes
                        $updatecartao = "update  geracartao set log_usado='S',cod_USUALTE=" . $row['COD_USUARIO'] . " where num_cartao=" . $cartao;
                        mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $updatecartao));
                        Grava_log_cad($connUser->connUser(), $LOG, 'Cartão OK...');
                    } elseif ($rsgeracartao['contador'] == 0) {
                        //cartao inválido - não existe na base
                        $return = array('AtualizaCadastroResponse' => array('msgerro' => 'Cartão inválido!', 'coderro' => '107'));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg, addslashes($xml_user_info->asXML()));
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
                        return $return;
                        exit();
                    } elseif ($rsgeracartao['contador'] > 0 && $rsgeracartao['log_usado'] == 'S') {
                        //cartao válido - mas já utilizado
                        $return = array('AtualizaCadastroResponse' => array('msgerro' => 'Cartão já utilizado!', 'coderro' => '106'));
                        array_to_xml($return, $xml_user_info);
                        Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $msg, addslashes($xml_user_info->asXML()));
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $msg, $row['LOG_WS']);
                        return $return;
                        exit();
                    }
                }
            }
        }
    }
    //==========================================
    if ($cliente['tokencadastro'] == '' || $cliente['tokencadastro'] == '?') {
        $TOKENCAD = '0';
    } else {
        $TOKENCAD = $cliente['tokencadastro'];
    }

    if ($row['COD_EMPRESA'] == 19 || $row['COD_EMPRESA'] == 219) {
        $tesr = fnCompString(fnAcentos($cliente), $row['COD_EMPRESA'], $cpf, $connAdm->connAdm(), $connUser->connUser());
        /*if($cpf=='35196685804')
    {    
     return array('AtualizaCadastroResult'=>array('msgerro' => print_r($tesr)));  
    }*/
    }


    /* if($cpf=='01734200014')
    {    
     return array('AtualizaCadastroResult'=>array('msgerro' => print_r($tesr)));  
    }*/
    //formata PF/PJ
    if (trim($cliente['tipocliente']) == 'PF' || trim($cliente['tipocliente']) == '' || trim($cliente['tipocliente']) == 'F') {
        $TP_CLIENTE = 'F';
    } else {
        $TP_CLIENTE = 'J';
    }
    // print_r($arraydata);           
    //codatendente

    if ($row['COD_EMPRESA'] != '276') {
        //if($cliente['bloqueado']=='')
        // {
        //vrificar se estana base de dados
        $sqlconsumidor = "SELECT COD_CLIENTE,LOG_ESTATUS,LOG_FIDELIDADE,LOG_FIDELIZADO,TIP_CLIENTE from clientes  WHERE NUM_CGCECPF='$cpf' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";
        $RWCLI = mysqli_query($connUser->connUser(), $sqlconsumidor);
        if ($RWCLI->num_rows > 0) {
            $rscli = mysqli_fetch_assoc($RWCLI);
            $LOG_ESTATUS = $rscli['LOG_ESTATUS'];
            //tip_cliente
            $TP_CLIENTE = $cliente['tipocliente'] == '' ? $rscli['TIP_CLIENTE'] : $cliente['tipocliente'];
        } else {
            $LOG_ESTATUS = 'S';
        }

        /* }else{
        if($cliente['bloqueado']=="N")
        {
            $LOG_ESTATUS='S';   
        }else{
            $LOG_ESTATUS='N';
        }    
        //$LOG_ESTATUS=$cliente['bloqueado'];
    }    */
    } else {
        $LOG_ESTATUS = 'S';
    }
    //verificar se o codIndicador esta preenchido
    //consultar pelo codIndicador o codigo do cliente para inserir junto
    if ($cliente['codIndicador'] != '') {
        //$rsw_indicador['COD_CLIENTE']
        $consultar_indicador = "SELECT * from CLIENTES WHERE COD_EMPRESA=" . $row['COD_EMPRESA'] . " AND COD_CLIENTE='" . $cliente['codIndicador'] . "'";
        $RSIND = mysqli_query($connUser->connUser(), $consultar_indicador);
        if ($RSIND->num_rows > 0) {
            $indcador = $cliente['codIndicador'];
        } else {
            $msg = "Error description indicacao: $msgsql";
            $xamls = addslashes($msg);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $xamls, $row['LOG_WS']);
            $indcador = 0;
            $log_ind = "INSERT INTO log_indicador (COD_EMPRESA, NUM_CGCECPF, COD_INDICADOR) VALUES ('" . $dadosLogin['idcliente'] . "','" . $cpf . "' , '" . $cliente['codIndicador'] . "');";
        }
    } else {
        $indcador = '0';
    }

    $atualiza = "CALL SP_INSERE_CLIENTES_WS(
                                        0,
                                     '" . $row['COD_EMPRESA'] . "',
                                     '" . addslashes(fnAcentos($cliente['nome'])) . "',
                                     '" . fnEncode($cliente['senha']) . "',
                                     '',
                                     '" . trim($cliente['email']) . "',
                                     '" . $row['COD_USUARIO'] . "',
                                     '" . $cpf . "',
                                     '$LOG_ESTATUS',
                                     '" . fnAcentos($cliente['rg']) . "',
                                     '" . $cliente['datanascimento'] . "',
                                     '" . $estadocivil . "',
                                     '" . $sexo . "',
                                     '" . $cliente['telresidencial'] . "',
                                     '" . preg_replace('/[^0-9]/', '', $cliente['telcelular']) . "',
                                     '" . $cliente['telcomercial'] . "',
                                     '',
                                     '" . $cartao . "',
                                     1,
                                     '" . addslashes(fnAcentos($cliente['endereco'])) . "',
                                     '" . limitarTexto($cliente['numero'], 10) . "',
                                     '" . addslashes(fnAcentos($cliente['complemento'])) . "',
                                     '" . addslashes(fnAcentos($cliente['bairro'])) . "',
                                     '" . preg_replace('/[^0-9]/', '', $cliente['cep']) . "',
                                     '" . addslashes(fnAcentos($cliente['cidade'])) . "',
                                     '" . $cliente['estado'] . "',
                                     '',
                                     '" . $profissao . "',
                                     '" . $dadosLogin['idloja'] . "',
                                     '" . $TP_CLIENTE . "',
                                     '',
                                     'S',
                                     'S',
                                     'S',
                                     '',
                                     '',
                                     '" . $row['COD_CHAVECO'] . "',
                                    $idadecalc,
                                    $arraydata0,
                                    $arraydata1,
                                    $arraydata2,
                                    '0',
                                    $cod_atendente,
				    '" . $TOKENCAD . "',
                                    '" . $indcador . "'    
                                    );";

    $cadat = mysqli_query($connUser->connUser(), $atualiza);
    /* if($cpf=="24942631823" )
        {    
                  return array('AtualizaCadastroResponse'=>array('msgerro' => $atualiza,
                                                                  'coderro'=>'33')); 
                  exit();
             
         }*/
    if (!$cadat) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($connUser->connUser(), $atualiza);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
            $msg = "Error description SP_ALTERA_CLIENTES_WS: $msgsql";

            $xamls = addslashes($msg);
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $xamls, $row['LOG_WS']);
        }
    } else {

        $resultat = mysqli_fetch_assoc($cadat);

        if ($resultat['cod_retorno'] == 1) {
            $menssagem = 'Cadastro Atualizado !';
            $cod_erro = '34';


            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $menssagem, $row['LOG_WS']);
            Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $menssagem, $menssagem);

            //verificar se o token está preenchido na webservice		
            if ($cliente['tokencadastro'] != '0' && $cliente['tokencadastro'] != '' && $cliente['tokencadastro'] != '?') {

                // verificar se o cliente ja tem token	
                $sqlclientealter = "SELECT DES_TOKEN,LOG_TERMO from clientes 
										   WHERE COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "' and
												 COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";

                $rsclientealter = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlclientealter));
                if ($rsclientealter['DES_TOKEN'] <= '0') {

                    $atualizastaatustoken = "UPDATE geratoken SET LOG_USADO='2', 
																		 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "', 
																		 DAT_USADO='" . date('Y-m-d H:i:s') . "' 
												WHERE  DES_TOKEN='" . $cliente['tokencadastro'] . "' and TIP_TOKEN='1' 
												and COD_EMPRESA='" . $dadosLogin['idcliente'] . "';";

                    mysqli_query($connUser->connUser(), $atualizastaatustoken);

                    //verificar se o cliente tem canal preenchido
                    $sqllog_canal = "SELECT COUNT(Distinct COD_CLIENTE) qtd_tem,COD_TIPO FROM log_canal 
																 WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND 
																 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                    $rslog_canal = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqllog_canal));
                    if ($rslog_canal['qtd_tem'] <= '0') {
                        //ATIVA O CANAL DE INSERÇÃO DO CLIENTES
                        if ($cliente['canal'] == '' || $cliente['canal'] == '1') {
                            $canal = "INSERT INTO log_canal 
															(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
															VALUES ('" . $dadosLogin['idcliente'] . "', 
																	'" . $dadosLogin['idloja'] . "', 
																	'" . $resultat['COD_CLIENTE'] . "', 
																	'1'
																	);";

                            $RWcanal = mysqli_query($connUser->connUser(), $canal);
                            if (!$RWcanal) {
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                            } else {
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                            }
                        } else {
                            $canal = "INSERT INTO log_canal 
															(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL,COD_TIPO,DAT_ATIV) 
															VALUES ('" . $dadosLogin['idcliente'] . "', 
																	'" . $dadosLogin['idloja'] . "', 
																	'" . $resultat['COD_CLIENTE'] . "', 
																	'" . $cliente['canal'] . "',
																	'" . $cliente['canal'] . "',
																	'" . date('Y-m-d H:i:s') . "');";
                            $RWcanal = mysqli_query($connUser->connUser(), $canal);
                            if (!$RWcanal) {
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                            } else {
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                            }
                        }
                    } else {

                        //alterar o canal enviado pelo ricardo
                        if ($rslog_canal['COD_TIPO'] != $cliente['canal'] && $cliente['canal'] != '') {
                            $atualizacanal = "UPDATE log_canal SET COD_TIPO='" . $cliente['canal'] . "',
																			 DAT_ATIV= '" . date('Y-m-d H:i:s') . "'	
																		 WHERE 
																		 COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and
																		 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                            mysqli_query($connUser->connUser(), $atualizacanal);
                        }
                    }
                    //inserir na tabela de aceites os termos do cliente table CLIENTES_TERMOS
                    $sql_termos = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA ='" . $dadosLogin['idcliente'] . "' AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC;";
                    $rwtermos = mysqli_query($connUser->connUser(), $sql_termos);
                    while ($rsternos = mysqli_fetch_assoc($rwtermos)) {
                        //inert into 
                        $valuestermos .= "('" . $dadosLogin['idcliente'] . "',
														'" . $resultat['COD_CLIENTE'] . "',
														'" . $rsternos['COD_BLOCO'] . "',
														'" . $rsternos['COD_TERMO'] . "'
														),";
                        $COD_TERMO .= $rsternos['COD_TERMO'] . ',';
                    }
                    //bulking insert
                    $valuestermos = rtrim($valuestermos, ',');
                    $instermos = "INSERT INTO CLIENTES_TERMOS(
																			COD_EMPRESA,
																			COD_CLIENTE,
																			COD_BLOCO,
																			COD_TERMOS
																			) VALUES $valuestermos";
                    $rwtermos = mysqli_query($connUser->connUser(), $instermos);
                    //alterar os aceites de comunicação do cliente.


                    $COD_TERMO = rtrim($COD_TERMO, ',');
                    $tipoaceite = "SELECT 
															SUM(email) email,
															SUM(sms) sms,
															SUM(WhatsApp) WhatsApp,
															SUM(Push) Push,
															SUM(Ofertas) Ofertas,
															SUM(Telemarketing) Telemarketing
															FROM (SELECT 						    
																	 case when COD_TIPO = 2 then '1' ELSE '0' END email,
																	 case when COD_TIPO = 3 then '1' ELSE '0' END sms,
																	 case when COD_TIPO = 4 then '1' ELSE '0' END WhatsApp,
																	 case when COD_TIPO = 5 then '1' ELSE '0' END Push, 
																	 case when COD_TIPO = 6 then '1' ELSE '0' END Ofertas,
																	 case when COD_TIPO = 7 then '1' ELSE '0' END Telemarketing
																	FROM	termos_empresa
																			WHERE COD_EMPRESA = '" . $dadosLogin['idcliente'] . "'
																				 AND LOG_ATIVO='S'
																				 AND COD_TIPO IN (2,3,4,5,6,7)		
																				 AND COD_TERMO IN($COD_TERMO))tmptermos";
                    $rwtipoaceite = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $tipoaceite));
                    if ($rwtipoaceite['email'] == '1') {
                        $aceite_email = 'S';
                    } else {
                        $aceite_email = 'N';
                    }
                    if ($rwtipoaceite['sms'] == '1') {
                        $aceite_sms = 'S';
                    } else {
                        $aceite_sms = 'N';
                    }
                    if ($rwtipoaceite['WhatsApp'] == '1') {
                        $aceite_WhatsApp = 'S';
                    } else {
                        $aceite_WhatsApp = 'N';
                    }
                    if ($rwtipoaceite['Push'] == '1') {
                        $aceite_Push = 'S';
                    } else {
                        $aceite_Push = 'N';
                    }
                    if ($rwtipoaceite['Ofertas'] == '1') {
                        $aceite_Ofertas = 'S';
                    } else {
                        $aceite_Ofertas = 'N';
                    }
                    if ($rwtipoaceite['Telemarketing'] == '1') {
                        $aceite_Telemarketing = 'S';
                    } else {
                        $aceite_Telemarketing = 'N';
                    }


                    //update para o cliente em conformidade
                    $sqlaceites = "UPDATE clientes SET 
																	LOG_TERMO='S',
																	LOG_EMAIL='" . $aceite_email . "',
																	LOG_SMS='" . $aceite_sms . "',
																	LOG_TELEMARK='" . $aceite_Telemarketing . "',
																	LOG_WHATSAPP='" . $aceite_WhatsApp . "',
																	LOG_PUSH='" . $aceite_Push . "',
																	LOG_OFERTAS='" . $aceite_Ofertas . "'		
												 WHERE 
												COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "' and 
												COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
                    mysqli_query($connUser->connUser(), $sqlaceites);

                    //alterar o token do cliente para o enviado caso ele seja vazio ou zero
                    $atualizastaatustokenCLI = "UPDATE clientes 
												  SET DES_TOKEN='" . $cliente['tokencadastro'] . "'
													WHERE  
													COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "' AND 
													DES_TOKEN='0'  and 
													COD_EMPRESA='" . $dadosLogin['idcliente'] . "';";
                    mysqli_query($connUser->connUser(), $atualizastaatustokenCLI);
                } else {

                    //if($resultat['COD_CLIENTE']=='737820')
                    //{
                    //alterando aqui
                    $sqllog_canal = "SELECT COUNT(Distinct COD_CLIENTE) qtd_tem,COD_TIPO,DAT_ATIV FROM log_canal 
																		 WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND 
																		 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                    $rslog_canal = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqllog_canal));

                    if ($cliente['canal'] == '' || $cliente['canal'] == '1') {
                        $canal = '1';
                    } else {
                        $canal = $cliente['canal'];
                    }
                    //alterar o canal enviado pelo ricardo
                    if ($rslog_canal['COD_TIPO'] != $cliente['canal'] || $rslog_canal['DAT_ATIV'] == '') {
                        if ($rslog_canal['DAT_ATIV'] == '') {
                            $atualizacanal = "UPDATE log_canal SET COD_TIPO='" . $canal . "',
																					 DAT_ATIV= '" . date('Y-m-d H:i:s') . "'	
																				 WHERE 
																				 COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and
																				 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                            mysqli_query($connUser->connUser(), $atualizacanal);
                        }
                    }
                    // }

                }
            } else {
                //verificar se o cliente tem canal preenchido
                $sqllog_canal = "SELECT COUNT(Distinct COD_CLIENTE) qtd_tem,COD_TIPO FROM log_canal 
																 WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND 
																 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                $rslog_canal = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqllog_canal));

                if ($rslog_canal['qtd_tem'] <= '0') {
                    //ATIVA O CANAL DE INSERÇÃO DO CLIENTES
                    if ($cliente['canal'] == '' || $cliente['canal'] == '1') {
                        $canal = "INSERT INTO log_canal 
															(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
															VALUES ('" . $dadosLogin['idcliente'] . "', 
																	'" . $dadosLogin['idloja'] . "', 
																	'" . $resultat['COD_CLIENTE'] . "', 
																	'1'
																	);";

                        $RWcanal = mysqli_query($connUser->connUser(), $canal);
                        if (!$RWcanal) {
                            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                        } else {
                            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                        }
                    } else {
                        $canal = "INSERT INTO log_canal 
															(COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL,COD_TIPO,DAT_ATIV) 
															VALUES ('" . $dadosLogin['idcliente'] . "', 
																	'" . $dadosLogin['idloja'] . "', 
																	'" . $resultat['COD_CLIENTE'] . "', 
																	'" . $cliente['canal'] . "',
																	'" . $cliente['canal'] . "',
																	'" . date('Y-m-d H:i:s') . "');";
                        $RWcanal = mysqli_query($connUser->connUser(), $canal);
                        if (!$RWcanal) {
                            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                        } else {
                            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                        }
                    }
                } else {

                    //alterar o canal enviado pelo ricardo
                    if ($rslog_canal['COD_TIPO'] != $cliente['canal'] && $cliente['canal'] != '') {
                        $atualizacanal = "UPDATE log_canal SET COD_TIPO='" . $cliente['canal'] . "',
																			 DAT_ATIV= '" . date('Y-m-d H:i:s') . "'	
																		 WHERE 
																		 COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and
																		 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                        mysqli_query($connUser->connUser(), $atualizacanal);
                    }
                }
            }

            //==================================


        } else {
            $menssagem = 'Registro inserido!';
            $cod_erro = '36';
            //classificação de clientes


            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', $menssagem, $row['LOG_WS']);
            Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $menssagem, $menssagem);
            //atualização do token de cadastro
            if ($cliente['tokencadastro'] != '0' && $cliente['tokencadastro'] != '' && $cliente['tokencadastro'] != '?') {
                $atualizastaatustoken = "UPDATE geratoken SET LOG_USADO='2', 
										 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "', 
										 DAT_USADO='" . date('Y-m-d H:i:s') . "' 
					WHERE  DES_TOKEN='" . $cliente['tokencadastro'] . "' and TIP_TOKEN='1' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "';";
                mysqli_query($connUser->connUser(), $atualizastaatustoken);
                //inserir na tabela de aceites os termos do cliente table CLIENTES_TERMOS
                $sql_termos = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA ='" . $dadosLogin['idcliente'] . "' AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC;";
                $rwtermos = mysqli_query($connUser->connUser(), $sql_termos);
                while ($rsternos = mysqli_fetch_assoc($rwtermos)) {
                    //inert into 
                    $valuestermos .= "('" . $dadosLogin['idcliente'] . "',
										'" . $resultat['COD_CLIENTE'] . "',
										'" . $rsternos['COD_BLOCO'] . "',
										'" . $rsternos['COD_TERMO'] . "'
										),";
                    $COD_TERMO .= $rsternos['COD_TERMO'] . ',';
                }
                //bulking insert
                $valuestermos = rtrim($valuestermos, ',');
                $instermos = "INSERT INTO CLIENTES_TERMOS(
															COD_EMPRESA,
															COD_CLIENTE,
															COD_BLOCO,
															COD_TERMOS
															) VALUES $valuestermos";
                $rwtermos = mysqli_query($connUser->connUser(), $instermos);
                //alterar os aceites de comunicação do cliente.


                $COD_TERMO = rtrim($COD_TERMO, ',');
                $tipoaceite = "SELECT 
											SUM(email) email,
											SUM(sms) sms,
											SUM(WhatsApp) WhatsApp,
											SUM(Push) Push,
											SUM(Ofertas) Ofertas,
											SUM(Telemarketing) Telemarketing
											FROM (SELECT 						    
													 case when COD_TIPO = 2 then '1' ELSE '0' END email,
													 case when COD_TIPO = 3 then '1' ELSE '0' END sms,
													 case when COD_TIPO = 4 then '1' ELSE '0' END WhatsApp,
													 case when COD_TIPO = 5 then '1' ELSE '0' END Push, 
													 case when COD_TIPO = 6 then '1' ELSE '0' END Ofertas,
													 case when COD_TIPO = 7 then '1' ELSE '0' END Telemarketing
													FROM	termos_empresa
															WHERE COD_EMPRESA = '" . $dadosLogin['idcliente'] . "'
																 AND LOG_ATIVO='S'
																 AND COD_TIPO IN (2,3,4,5,6,7)		
																 AND COD_TERMO IN($COD_TERMO))tmptermos";
                $rwtipoaceite = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $tipoaceite));
                if ($rwtipoaceite['email'] == '1') {
                    $aceite_email = 'S';
                } else {
                    $aceite_email = 'N';
                }
                if ($rwtipoaceite['sms'] == '1') {
                    $aceite_sms = 'S';
                } else {
                    $aceite_sms = 'N';
                }
                if ($rwtipoaceite['WhatsApp'] == '1') {
                    $aceite_WhatsApp = 'S';
                } else {
                    $aceite_WhatsApp = 'N';
                }
                if ($rwtipoaceite['Push'] == '1') {
                    $aceite_Push = 'S';
                } else {
                    $aceite_Push = 'N';
                }
                if ($rwtipoaceite['Ofertas'] == '1') {
                    $aceite_Ofertas = 'S';
                } else {
                    $aceite_Ofertas = 'N';
                }
                if ($rwtipoaceite['Telemarketing'] == '1') {
                    $aceite_Telemarketing = 'S';
                } else {
                    $aceite_Telemarketing = 'N';
                }


                //update para o cliente em conformidade
                $sqlaceites = "UPDATE clientes SET 
					                                LOG_TERMO='S',
													LOG_EMAIL='" . $aceite_email . "',
													LOG_SMS='" . $aceite_sms . "',
													LOG_TELEMARK='" . $aceite_Telemarketing . "',
													LOG_WHATSAPP='" . $aceite_WhatsApp . "',
													LOG_PUSH='" . $aceite_Push . "',
													LOG_OFERTAS='" . $aceite_Ofertas . "'		
					             WHERE 
								COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "' and 
								COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";

                mysqli_query($connUser->connUser(), $sqlaceites);

                $sqllog_canal = "SELECT COUNT(Distinct COD_CLIENTE) qtd_tem,COD_TIPO,DAT_ATIV FROM log_canal 
												 WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND 
												 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                $rslog_canal = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqllog_canal));

                /*if($cliente['canal']=='' || $cliente['canal']=='1'){$canal='1';}else{$canal=$cliente['canal'];}
					//alterar o canal enviado pelo ricardo
					if($rslog_canal['COD_TIPO']!=$cliente['canal'])
					{
						if($rslog_canal['DAT_ATIV']=='')
						{	
							$atualizacanal="UPDATE log_canal SET COD_TIPO='".$canal."',
																 DAT_ATIV= '".date('Y-m-d H:i:s')."'	
															 WHERE 
															 COD_EMPRESA='".$dadosLogin['idcliente']."' and
															 COD_CLIENTE='".$resultat['COD_CLIENTE']."'";
							mysqli_query($connUser->connUser(), $atualizacanal);	
						}												
					}	*/
            }

            //agenda email disparo
            if ($cpf != '0' && $cartao != '0') {
                $array = array(
                    'WHERE' => "WHERE g.TIP_GATILHO in ('cadastro','cadPush') AND g.cod_empresa=$row[COD_EMPRESA] AND g.LOG_STATUS='S'",
                    'TABLE' => array(
                        'gatilho_EMAIL g INNER  JOIN email_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA="S" ',
                        'gatilho_sms g INNER  JOIN sms_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA_SMS="S" ',
                        'gatilho_push g INNER  JOIN push_parametros  p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA_PUSH="S" ',
                        'gatilho_whatsapp g INNER  JOIN whatsapp_parametros p ON p.COD_EMPRESA=g.cod_empresa AND p.COD_CAMPANHA=g.cod_campanha  INNER JOIN campanha c ON c.COD_CAMPANHA=g.COD_CAMPANHA AND c.LOG_PROCESSA_whatsapp ="S" '
                    )
                );

                foreach ($array['TABLE'] as $KEY => $dadostable) {

                    if ($KEY == '0') {
                        //comunicação de email 
                        $gatilho = '2';
                    }
                    if ($KEY == '1') {
                        //comunicação sms
                        $gatilho = '3';
                    }
                    if ($KEY == '2') {
                        //cod_do push na fila
                        $gatilho = '4';
                    }
                    if ($KEY == '3') {
                        //cod_do whatsapp na fila
                        $gatilho = '5';
                    }
                    $sqlgatilho_email = "SELECT * FROM $dadostable $array[WHERE] ORDER BY COD_LISTA DESC";
                    $rwgatilho_email = mysqli_query($connUser->connUser(), $sqlgatilho_email);

                    if ($teste1 = mysqli_num_rows($rwgatilho_email) >= 1) {
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
                                                                                                    '" . $resultat['COD_CLIENTE'] . "', 
                                                                                                    '" . $cpf . "', 
                                                                                                    '" . utf8_decode(fnAcentos($cliente['nome'])) . "', 
                                                                                                    '" . $datahora . "', 
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
                                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO AO GRAVA FILA DE ENVIO', $row['LOG_WS']);
                            } else {
                                $clas = "CALL SP_PERSONA_CLASSIFICA_CADASTRO(" . $resultat['COD_CLIENTE'] . ", " . $row['COD_EMPRESA'] . ", $cod_campanha, '" . $COD_PERSONAS . "','0')";
                                $PERCLASS = mysqli_query($connUser->connUser(), $clas);
                                if (!$PERCLASS) {
                                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:SP_PERSONA_CLASSIFICA_CADASTRO', $row['LOG_WS']);
                                }
                                /*if($cartao=='01734200014')
                                                            {	 
                                                                     return  array('AtualizaCadastroResponse'=>array('msgerro'=>$clas));
                                                                         exit();
                                                             }*/
                            }
                        }
                    }
                }

                /*if($cartao=='01734200014')
                                {	 
                                     return  array('AtualizaCadastroResponse'=>array('msgerro'=>$sqlfila));
                                         exit();
                                }*/
                //ATIVA O CANAL DE INSERÇÃO DO CLIENTES

                if ($cliente['canal'] == '' || $cliente['canal'] == '1') {
                    $canal = "INSERT INTO log_canal 
                                                                (COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
                                                                VALUES ('" . $dadosLogin['idcliente'] . "', 
                                                                                '" . $dadosLogin['idloja'] . "', 
                                                                                '" . $resultat['COD_CLIENTE'] . "', 
                                                                                '1');";

                    $RWcanal = mysqli_query($connUser->connUser(), $canal);
                    if (!$RWcanal) {
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                    } else {
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                    }

                    if ($cliente['tokencadastro'] != '0' && $cliente['tokencadastro'] != '' && $cliente['tokencadastro'] != '?') {
                        $sqllog_canal = "SELECT COUNT(Distinct COD_CLIENTE) qtd_tem,COD_TIPO,DAT_ATIV FROM log_canal 
												 WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' AND 
												 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                        $rslog_canal = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqllog_canal));

                        if ($cliente['canal'] == '' || $cliente['canal'] == '1') {
                            $canalver = '1';
                        } else {
                            $canalver = $cliente['canal'];
                        }


                        if ($canalver == '1') {
                            if ($rslog_canal['DAT_ATIV'] == '') {
                                $atualizacanal = "UPDATE log_canal SET COD_TIPO='" . $canalver . "',
																		 DAT_ATIV= '" . date('Y-m-d H:i:s') . "'	
																	 WHERE 
																	 COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and
																	 COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                                mysqli_query($connUser->connUser(), $atualizacanal);
                            }
                        }
                    }
                } else {
                    $canal = "INSERT INTO log_canal (COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,COD_CANAL) 
                                                                              VALUES ('" . $dadosLogin['idcliente'] . "', 
                                                                                      '" . $dadosLogin['idloja'] . "', 
                                                                                      '" . $resultat['COD_CLIENTE'] . "', 
                                                                                      '" . $cliente['canal'] . "');";

                    $RWcanal = mysqli_query($connUser->connUser(), $canal);
                    if (!$RWcanal) {
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:AO INSERIR CANAL:..', $row['LOG_WS']);
                    } else {
                        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'CANAL INSERIDO COM EXITO:..', $row['LOG_WS']);
                    }
                }
            }
            //==============================================================================  
            if ($cliente['codIndicador'] != '') {
                $inser_indicados = "INSERT INTO clientes_indicados (COD_EMPRESA, COD_CLIENTE, NOM_CLIENTE, COD_UNIVEND, COD_INDICAD, DAT_CADASTR) VALUES 
                                                                ('" . $dadosLogin['idcliente'] . "', '" . $resultat['COD_CLIENTE'] . "', '" . addslashes(fnAcentos($cliente['nome'])) . "', '" . $dadosLogin['idloja'] . "','" . $cliente['codIndicador'] . "',now());";
                $rsind = mysqli_query($connUser->connUser(), $inser_indicados);

                $update_ind = "UPDATE clientes SET DAT_INDICAD= now() WHERE  COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "';";
                mysqli_query($connUser->connUser(), $update_ind);
                if (!$rsind) {
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:SP_PERSONA_CLASSIFICA_CADASTRO', $row['LOG_WS']);
                }
            }
            $clas = "CALL SP_PERSONA_CLASSIFICA_CADASTRO(" . $resultat['COD_CLIENTE'] . ", " . $row['COD_EMPRESA'] . ", 0, '','1')";
            /* if($row['COD_EMPRESA']=='312')
                {
                       return  array('AtualizaCadastroResponse'=>array('msgerro'=>$clas,
                                                       'coderro'=>'5'));
                }  */
            $PERCLASS = mysqli_query($connUser->connUser(), $clas);
            if (!$PERCLASS) {
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:SP_PERSONA_CLASSIFICA_CADASTRO', $row['LOG_WS']);
            }

            //nova rotina para pontuação no cadastro
            $QRCODEpontos = explode('_', $cliente['adesao']);
            if ($QRCODEpontos['0'] == 'QR') {
                $pontuacli = "CALL SP_CREDITOS_CADASTRO_QRCODE('" . $dadosLogin['idcliente'] . "','" . $dadosLogin['idloja'] . "',$cod_atendente, '" . $row['COD_USUARIO'] . "'," . $resultat['COD_CLIENTE'] . ",'" . $QRCODEpontos['1'] . "')";
                $rwpontcli = mysqli_query($connUser->connUser(), $pontuacli);
            } else {
                $pontuacli = "CALL SP_CREDITOS_CADASTRO('" . $dadosLogin['idcliente'] . "','" . $dadosLogin['idloja'] . "',$cod_atendente, '" . $row['COD_USUARIO'] . "'," . $resultat['COD_CLIENTE'] . ")";
                $rwpontcli = mysqli_query($connUser->connUser(), $pontuacli);
            }
        }
        $COD_CLIENTE = $resultat['COD_CLIENTE'];
    }

    //alterar o campo deacordo com a adesao
    if ($row['COD_EMPRESA'] == '109') {
        if ($cliente['adesao'] == 'N' || $cliente['adesao'] == 'n') {
            $sqladesao = "UPDATE clientes SET LOG_FIDELIDADE='N' WHERE  COD_CLIENTE='" . $COD_CLIENTE . "' and COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
            $RWadesao = mysqli_query($connUser->connUser(), $sqladesao);
            if (!$RWadesao) {
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:Na atualização da adesao', $row['LOG_WS']);
            } else {
                fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Adesao OK', $row['LOG_WS']);
            }
        }
    } else {
        if ($cliente['adesao'] == 'ST' || $cliente['adesao'] == 'st') {
            //update para o cliente não conformidade
            $sqlaceites = "UPDATE clientes SET LOG_TERMO='N' WHERE 
		                                                   COD_CLIENTE='" . $COD_CLIENTE . "' and 
		                                                   COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
            mysqli_query($connUser->connUser(), $sqlaceites);
        }
        if ($cliente['adesao'] == 'CT' || $QRCODEpontos['0'] == 'QR') {
            //update para o cliente em conformidade
            $sqlaceites = "UPDATE clientes SET LOG_TERMO='S' 
		WHERE 
		COD_CLIENTE='" . $COD_CLIENTE . "' and 
		COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
            mysqli_query($connUser->connUser(), $sqlaceites);

            if ($dadosLogin['idcliente'] == '276') {
                //verificar se o cliente ja tem termos inseridos
                $log_termoac = "SELECT COD_CLIENTE FROM CLIENTES_TERMOS WHERE cod_empresa='" . $dadosLogin['idcliente'] . "' AND COD_CLIENTE='" . $resultat['COD_CLIENTE'] . "'";
                $rwlog_termoac = mysqli_query($connUser->connUser(), $log_termoac);
                if ($rwlog_termoac->num_rows <= 0) {

                    //inserir na tabela de aceites os termos do cliente table CLIENTES_TERMOS
                    $sql_termos = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA ='" . $dadosLogin['idcliente'] . "' AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC;";
                    $rwtermos = mysqli_query($connUser->connUser(), $sql_termos);
                    while ($rsternos = mysqli_fetch_assoc($rwtermos)) {
                        //inert into 
                        $valuestermos .= "('" . $dadosLogin['idcliente'] . "',
                                                            '" . $resultat['COD_CLIENTE'] . "',
                                                            '" . $rsternos['COD_BLOCO'] . "',
                                                            '" . $rsternos['COD_TERMO'] . "'
                                                            ),";
                        $COD_TERMO .= $rsternos['COD_TERMO'] . ',';
                    }
                    //bulking insert
                    $valuestermos = rtrim($valuestermos, ',');
                    $instermos = "INSERT INTO CLIENTES_TERMOS(
                                                                COD_EMPRESA,
                                                                COD_CLIENTE,
                                                                COD_BLOCO,
                                                                COD_TERMOS
                                                                ) VALUES $valuestermos";
                    $rwtermos = mysqli_query($connUser->connUser(), $instermos);
                }
            }
        }
    }
    //==========================================================insert ou atualiza funcionario
    if ($cliente['funcionario'] == 'N') {
        $sqlfuncionario = "UPDATE clientes SET LOG_FUNCIONA='N' WHERE  COD_CLIENTE='" . $COD_CLIENTE . "' and COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
        $RWfuncionario = mysqli_query($connUser->connUser(), $sqlfuncionario);
        if (!$RWfuncionario) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:Na atualização do Funcionario', $row['LOG_WS']);
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Funcionario OK........................', $row['LOG_WS']);
        }
    }


    if ($cliente['funcionario'] == 'S') {
        $sqlfuncionario = "UPDATE clientes SET LOG_FUNCIONA='S' WHERE  COD_CLIENTE='" . $COD_CLIENTE . "' and COD_EMPRESA='" . $row['COD_EMPRESA'] . "'";
        $RWfuncionario = mysqli_query($connUser->connUser(), $sqlfuncionario);
        if (!$RWfuncionario) {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:Na atualização do Funcionario', $row['LOG_WS']);
        } else {
            fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Funcionario OK........................', $row['LOG_WS']);
        }
    }
    //===================================================================================================== 
    //atualizar os campos 
    if ($row['LOG_ATIVCAD'] == 'S') {
        $arrayconsulta = array(
            'ConnB' => $connUser->connUser(),
            'conn' => $connAdm->connAdm(),
            'database' => $row['NOM_DATABASE'],
            'empresa' => $row['COD_EMPRESA'],
            'fase' => $fase,
            'cartao' =>  fnlimpaCPF($cliente['cartao']),
            'cpf' => fnlimpaCPF($cliente['cpf']),
            'cnpj' => $cliente['cnpj'],
            'login' => $dadosLogin['login'],
            'senha' => $dadosLogin['senha'],
            'idloja' => $dadosLogin['idloja'],
            'idmaquina' => $dadosLogin['idmaquina'],
            'codvendedor' => $dadosLogin['codvendedor'],
            'nomevendedor' => $dadosLogin['nomevendedor'],
            'COD_USUARIO' => $row['COD_USUARIO'],
            'pagina' => 'BuscaConsumidor',
            'COD_UNIVEND' => $dadosLogin['idloja'],
            'venda' => 'nao',
            'generico' => $opcoesbuscaconsumidor['genericobuscaconsumidor'],
            'LOG_WS' => $row['LOG_WS']
        );
        $arraybusca[] = fn_consultaBase($arrayconsulta);

        $CAMPOSSQL = "select KEY_CAMPOOBG  from matriz_campo_integracao                         
                    inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
                    where matriz_campo_integracao.COD_EMPRESA=" . $dadosLogin['idcliente'] . "
                    and matriz_campo_integracao.TIP_CAMPOOBG IN('OBG','OPC') GROUP BY KEY_CAMPOOBG;
                 ";

        $CAMPOQUERY = mysqli_query($connAdm->connAdm(), $CAMPOSSQL);
        while ($CAMPOROW = mysqli_fetch_assoc($CAMPOQUERY)) {

            if (array_search('', array_column($arraybusca, $CAMPOROW['KEY_CAMPOOBG'])) !== false) {
                $selecionado .= 'yes,';
            } else {
                $selecionado .= "no,";
            }
        }
        $camposvazios[] = explode(',',  substr($selecionado, 0, -1));

        if (recursive_array_search('yes', $camposvazios) !== false) {
        } else {

            //VERIFICAR SE  O CLIENTE ACEITOU OS TERMOS
            $SQLTERMOSS = "SELECT COD_CLIENTE from clientes WHERE
                                                                            LOG_TERMO='S' and    						
                                                                            COD_CLIENTE='" . $COD_CLIENTE . "' and
                                                                            COD_EMPRESA='" . $dadosLogin['idcliente'] . "'";
            /*  if($cartao=='00275189732')
						 {	 
						 return  array('AtualizaCadastroResponse'=>array('msgerro'=>$SQLTERMOSS),
																	     'coderro'=>'5'));
					      exit();
						 }	*/
            $RWTERMOSS = mysqli_query($connUser->connUser(), $SQLTERMOSS);
            if ($temtermo = mysqli_num_rows($RWTERMOSS) > 0) {
                $dadosOK = "UPDATE clientes SET LOG_ATIVCAD='S', LOG_CADOK='S',LOG_TERMO='S'  WHERE COD_EMPRESA='" . $dadosLogin['idcliente'] . "' and COD_CLIENTE=$COD_CLIENTE;";
                $rwdadosOK = mysqli_query($connUser->connUser(), $dadosOK);
                if (!$rwdadosOK) {
                    fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], $cpf, $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'ERRO:LOG_ATIVCAD=S LOG_CADOK=S', $row['LOG_WS']);
                }
                $upcredito = "UPDATE  creditosdebitos SET cod_statuscred=1
                                                                    WHERE 
                                                                            cod_statuscred=7 AND 
                                                                            cod_cliente=$COD_CLIENTE AND 
                                                                            cod_empresa='" . $dadosLogin['idcliente'] . "'";
                mysqli_query($connUser->connUser(), $upcredito);
            }
        }
    }
    ////-=================================================

    $arrayconsulta = array(
        'conn' => $connAdm->connAdm(),
        'ConnB' => $connUser->connUser(),
        'cod_cliente' => $COD_CLIENTE,
        'empresa' => $row['COD_EMPRESA'],
        'fase' => $fase,
        'cpf' => $cpf,
        'cnpj' => fnlimpaCPF($cliente['cnpj']),
        'cartao' =>  $cartao,
        'email' =>  trim($cliente['email']),
        'telefone' =>  $cliente['telefone'],
        'consultaativa' => $row['LOG_CONSEXT'],
        'login' => $dadosLogin['login'],
        'senha' => $dadosLogin['senha'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'codvendedor' => $dadosLogin['codvendedor'],
        'nomevendedor' => $dadosLogin['nomevendedor'],
        'COD_USUARIO' => $row['COD_USUARIO'],
        'pagina' => 'AtualizaCadastro',
        'menssagem' => $menssagem,
        'coderro' => $cod_erro,
        'venda' => 'nao',
        'COD_UNIVEND' => $dadosLogin['idloja'],
        'LOG_WS' => $row['LOG_WS'],
        'dec' => $dec,
        'decimal' => $decimal,
        'LOG_CADTOKEN' => $row['LOG_CADTOKEN']
    );


    ob_end_flush();
    ob_flush();
    // fnmemoriafinal($connUser->connUser(),$cod_men);

    //    $teste=fn_consultaBase($arrayconsulta);
    //return  array('AtualizaCadastroResponse'=>array('msgerro'=>$teste,
    //                                                      'coderro'=>'5'));
    $return = array('AtualizaCadastroResponse' => fnreturn($arrayconsulta));
    array_to_xml($return, $xml_user_info);
    Grava_log_msgxml($connUser->connUser(), 'msg_cadastra', $cod_log, $menssagem, addslashes($xml_user_info->asXML()));
    return $return;
}
