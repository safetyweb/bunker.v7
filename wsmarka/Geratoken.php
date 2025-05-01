<?php
//msg de envio sms

$server->wsdl->addComplexType(
    'tokenreturn',
    'complexType',
    'struct',
    'sequence',
    '',
    array(
        'token' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'token', 'type' => 'xsd:string'),
        'msgerro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'msgerro', 'type' => 'xsd:string'),
        'coderro' => array('minOccurs' => '0', 'maxOccurs' => '1', 'name' => 'coderro', 'type' => 'xsd:string')

    )
);
$server->register(
    'Geratoken',
    array(
        'tipoGeracao' => 'xsd:string',
        'nome' => 'xsd:string',
        'cpf' => 'xsd:string',
        'celular' => 'xsd:string',
        'email' => 'xsd:string',
        'dadosLogin' => 'tns:LoginInfo'
    ),
    array('retornatoken' => 'tns:tokenreturn'),
    $ns,
    "$ns/verificavenda",
    'document',
    'literal',
    'verificavenda'
);
function Geratoken($tipoGeracao, $nome, $cpf, $celular, $email, $dadosLogin)
{
    if (empty($celular) && empty($email)) {
        // Nenhum dos dois foi preenchido
        return  array('retornatoken' => array(
            'msgerro' => 'Por favor, preencha o celular ou o email.',
            'coderro' => '89'
        ));
        exit();
    }

    //TIP_ENVIO = 1 SMS
    //TIP_ENVIO = 2 email
    //verificar se o cliente ja esta cadastrado e não enviar o tokem
    $concador = 1 + floor(rand() * 5);
    include_once '../_system/Class_conn.php';
    include_once 'func/function.php';
    /*if($cpf=='01734200014')
{
  // date_default_timezone_set('Etc/GMT+3');
    return  array('retornatoken'=>array('token'=>'0',
                                                            'msgerro'=>date('Y-m-d H:i:s'),
                                                            'coderro'=>'89'));
}*/
    if ($dadosLogin['idcliente'] == '80') {
        $testecon = file_get_contents("php://input");
        $arquivo = './log_txt/' . $dadosLogin['idloja'] . '_sms_arquivo.txt';
        if (file_exists($arquivo)) {
            // Obtém o conteúdo atual do arquivo
            $conteudoAtual = file_get_contents($arquivo);
            // Acrescenta o novo conteúdo na última linha
            $novoConteudo = $conteudoAtual . PHP_EOL . $testecon;
            // Escreve o novo conteúdo no arquivo
            file_put_contents($arquivo, $novoConteudo);
        } else {
            // Cria o arquivo e escreve o conteúdo nele
            file_put_contents($arquivo, $testecon);
        }
    }
    if ($tipoGeracao == '1' || $tipoGeracao == '2') {
    } else {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'O campo tipoGeracao precisa ser preenchido com 1 para Cadastro 2 para resgate',
            'coderro' => '89'
        ));
        exit();
    }

    $celular = preg_replace("/[^0-9]/", "", $celular);

    /*$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $dadosLogin['login'] . "', '" . fnEncode($dadosLogin['senha']) . "','','','" . $dadosLogin['idcliente'] . "','','')";
    $row = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
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
            return  array('retornatoken' => array(
                'msgerro' => 'Dados Login Invalidos!',
                'coderro' => '80'
            ));
        }
    }

    if (!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS'])) {
        fngravalogMSG($connAdm->connAdm(), $dadosLogin['login'], $dadosLogin['idcliente'], fnlimpaCPF($cpf), $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'geratoken', 'Usuario ou senha Inválido!', $row['LOG_WS']);

        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'Usuario ou senha Inválido!',
            'coderro' => '5'
        ));
        exit();
    }

    //VERIFICA SE A EMPRESA FOI DESABILITADA
    if ($row['LOG_ATIVO'] == 'N') {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'Oh não! A empresa foi desabilitada por algum motivo ;-[!',
            'coderro' => '6'
        ));
        exit();
    }
    //VERIFICA SE O USUARIO FOI DESABILITADA
    if ($row['LOG_ESTATUS'] == 'N') {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'Oh não! Usuario foi desabilitado ;-[!',
            'coderro' => '44'
        ));
        exit();
    }
    //$row[COD_CHAVECO]
    if ($row['COD_CHAVECO'] == '1' || $row['COD_CHAVECO'] == '2' || $row['COD_CHAVECO'] == '5') {
        if ($cpf != '') {
            $cpfconsulta = " and NUM_CGCECPF='" . fnlimpaCPF($cpf) . "'";
        } else {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Campo CPF Não Pode ser Vazio',
                'coderro' => '90'
            ));
            exit();
        }
    }
    if ($row['COD_CHAVECO'] == '3') {
        if ($celular != '') {
            $numcelular = " and NUM_CELULAR='" . fnlimpaCPF($celular) . "'";
        } else {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Campo Celular Não Pode ser Vazio',
                'coderro' => '91'
            ));
            exit();
        }
    } elseif (fnlimpaCPF($celular) != '') {
        $numcelularFUL = " and NUM_CELULAR='" . fnlimpaCPF($celular) . "'";
    } elseif (empty($celular) && $tipoGeracao == '1') {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'Campo celular Não Pode ser Vazio',
            'coderro' => '92'
        ));
        exit();
    }


    if ($row['COD_CHAVECO'] == '7') {
        if ($email != '') {
            $selemail = " and DES_EMAIL='" . $email . "'";
        } else {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Campo E-mail Não Pode ser Vazio',
                'coderro' => '92'
            ));
            exit();
        }
    }



    //sleep(1);
    $connUser = new BD($row['IP'], $row['USUARIODB'], fnDecode($row['SENHADB']), $row['NOM_DATABASE']);
    $CONTEMPFIXA = $connUser->connUser();
    $xmlteste = addslashes(file_get_contents("php://input"));
    $arrylog = array(
        'cod_usuario' => $row['COD_USUARIO'],
        'login' => $dadosLogin['login'],
        'cod_empresa' => $row['COD_EMPRESA'],
        'idloja' => $dadosLogin['idloja'],
        'idmaquina' => $dadosLogin['idmaquina'],
        'cpf' => fnlimpaCPF($cpf),
        'xml' => $xmlteste,
        'tables' => 'origemtoken',
        'conn' => $connUser->connUser(),
        'pdv' => '0'
    );

    $cod_log = fngravalogxml($arrylog);

    //===verificar se a campanha esta ativa
    //envio sms/whatsapp
    if ($celular != '') {
        //verificar blk sms
        $sqlblk = "SELECT 1 AS temnao FROM blacklist_sms WHERE cod_empresa='" . $row['COD_EMPRESA'] . "' AND num_celular='$celular'";
        $rwblk = mysqli_query($connUser->connUser(), $sqlblk);
        if ($rwblk->num_rows > '0') {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Esse numero de celular é invalido ou foi bloqueado pelo gestor de sua empresa!',
                'coderro' => '91'
            ));
            exit();
        }
        //contador de numeros 
        if (strlen($celular) < 11) {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Numero invalido!',
                'coderro' => '91'
            ));
            exit();
        }



        //aqui vai ser para pegar a chave SMS/whatsapp para verificar qual campo vai ser preenchido para envio da comunicação
        if ($tipoGeracao == '1') {
            unset($campanhaSMS);

            $campanhaSMS = "SELECT G.COD_EMPRESA,
                                                        G.COD_CAMPANHA,
                                                        G.LOG_STATUS,
                                                        G.LOG_PROCESS,
                                                        G.HOR_ESPECIF,
                                                        T.DES_TEMPLATE,
                                                        C.LOG_PROCESSA_SMS,
                                                        NULL LOG_PROCESSA_WHATSAPP,
                                                        C.LOG_ATIVO,
                                                        C.LOG_CONTINU,
                                                        C.DAT_INI,
                                                        C.DAT_FIM,
                                                        C.DES_CAMPANHA	
                                                            FROM gatilho_sms G
                                                            INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                                            WHERE 
                                                            G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                            AND G.TIP_GATILHO='tokenCad'
                                                            AND C.LOG_ATIVO='S'
                                                            AND C.LOG_PROCESSA_SMS='S'
                                                 UNION all
                                                    SELECT G.COD_EMPRESA,
                                                        G.COD_CAMPANHA,
                                                        G.LOG_STATUS,
                                                        G.LOG_PROCESS,
                                                        G.HOR_ESPECIF,
                                                        CASE
                                                            WHEN W.DES_TEMPLATE2 != '' AND @concador = 2 THEN W.DES_TEMPLATE2
                                                            WHEN W.DES_TEMPLATE3 != '' AND @concador = 3 THEN W.DES_TEMPLATE3
                                                            WHEN W.DES_TEMPLATE4 != '' AND @concador = 4 THEN W.DES_TEMPLATE4
                                                            WHEN W.DES_TEMPLATE5 != '' AND @concador = 5 THEN W.DES_TEMPLATE5
                                                            ELSE W.DES_TEMPLATE
                                                        END AS DES_TEMPLATE,
                                                        null LOG_PROCESSA_SMS,
                                                        C.LOG_PROCESSA_WHATSAPP,     
                                                        C.LOG_ATIVO,
                                                        C.LOG_CONTINU,
                                                        C.DAT_INI,
                                                        C.DAT_FIM,
                                                        C.DES_CAMPANHA	
                                                            FROM gatilho_whatsapp G
                                                            INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN mensagem_whatsapp wm ON wm.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN template_whatsapp W ON W.COD_TEMPLATE=wm.COD_TEMPLATE_WHATSAPP
                                                            WHERE 
                                                            G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                            AND G.TIP_GATILHO='tokenCad'
                                                            AND C.LOG_ATIVO='S'
                                                            AND C.LOG_PROCESSA_WHATSAPP='S';";
        }
        if ($tipoGeracao == '2') {
            unset($campanhaSMS);
            $campanhaSMS = "SELECT G.COD_EMPRESA,
                                                        G.COD_CAMPANHA,
                                                        G.LOG_STATUS,
                                                        G.LOG_PROCESS,
                                                        G.HOR_ESPECIF,
                                                        T.DES_TEMPLATE,
                                                        C.LOG_PROCESSA_SMS,
                                                        NULL LOG_PROCESSA_WHATSAPP,
                                                        C.LOG_ATIVO,
                                                        C.LOG_CONTINU,
                                                        C.DAT_INI,
                                                        C.DAT_FIM,
                                                        C.DES_CAMPANHA	
                                                            FROM gatilho_sms G
                                                            INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                                            INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                                            WHERE 
                                                            G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                            AND G.TIP_GATILHO='tokenVen'
                                                            AND C.LOG_ATIVO='S'
                                                            AND C.LOG_PROCESSA_SMS='S'
                                                     UNION all
                                                        SELECT G.COD_EMPRESA,
                                                            G.COD_CAMPANHA,
                                                            G.LOG_STATUS,
                                                            G.LOG_PROCESS,
                                                            G.HOR_ESPECIF,
                                                            CASE
                                                                WHEN W.DES_TEMPLATE2 != '' AND @concador = 2 THEN W.DES_TEMPLATE2
                                                                WHEN W.DES_TEMPLATE3 != '' AND @concador = 3 THEN W.DES_TEMPLATE3
                                                                WHEN W.DES_TEMPLATE4 != '' AND @concador = 4 THEN W.DES_TEMPLATE4
                                                                WHEN W.DES_TEMPLATE5 != '' AND @concador = 5 THEN W.DES_TEMPLATE5
                                                                ELSE W.DES_TEMPLATE
                                                            END AS DES_TEMPLATE,
                                                            null LOG_PROCESSA_SMS,
                                                            C.LOG_PROCESSA_WHATSAPP,     
                                                            C.LOG_ATIVO,
                                                            C.LOG_CONTINU,
                                                            C.DAT_INI,
                                                            C.DAT_FIM,
                                                            C.DES_CAMPANHA	
                                                                FROM gatilho_whatsapp G
                                                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                                INNER JOIN mensagem_whatsapp wm ON wm.COD_CAMPANHA=G.COD_CAMPANHA
                                                                INNER JOIN template_whatsapp W ON W.COD_TEMPLATE=wm.COD_TEMPLATE_WHATSAPP
                                                                WHERE 
                                                                G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                                AND G.TIP_GATILHO='tokenVen'
                                                                AND C.LOG_ATIVO='S'
                                                                AND C.LOG_PROCESSA_WHATSAPP='S';";
        }
        $rwcampanhaATIVA = mysqli_query($connUser->connUser(), $campanhaSMS);

        //  if($celular=='48996243831')
        //  {
        if ($rwcampanhaATIVA->num_rows <= '0') {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Não Tem campanha para esse tipo de envio configurada!',
                'coderro' => '96'
            ));
            exit();
        }
        // }    
    }
    //==================================================

    $sqlverificatoken =  "SELECT 
					case when TIP_TOKEN='$tipoGeracao' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then NUM_CELULAR ELSE null END NUM_CELULAR_COMPARAR,
					case when TIP_TOKEN='$tipoGeracao' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then DAT_VALIDADE ELSE null END DATA_VALIDADE,
					case when TIP_TOKEN='$tipoGeracao' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then QTD_REENVIO_CONTROLE ELSE 1 END QTD_REENVIO_CONTROLE,
					case when TIP_TOKEN='$tipoGeracao' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then QTD_REENVIO ELSE 1 END QTD_REENVIO,
					case when TIP_TOKEN='$tipoGeracao' $numcelularFUL  $numcelular $selemail  $cpfconsulta   then COD_TOKEN ELSE NULL END COD_TOKEN,					 
				        LOG_USADO,
					DES_TOKEN,
					NUM_CELULAR,   
					DAT_CADASTR									
                                        FROM geratoken 
                                        WHERE COD_EMPRESA= '" . $dadosLogin['idcliente'] . "' AND 
								  LOG_USADO=1 and
								  COD_EXCLUSA=0 and
								  TIP_TOKEN='" . $tipoGeracao . "' 	
								  $numcelular
								  $selemail
								  $cpfconsulta
								 order by COD_TOKEN desc";

    $rwlogtoken = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqlverificatoken));

    //trava o reenvio para o mesmo numero em 5 min 
    //solicitação do maurice
    $temporeenvio = '5';
    //if($dadosLogin['idcliente']!='264')
    //{ 
    //limites de envio
    $limitdeenvio = 3;
    if ($rwlogtoken['QTD_REENVIO_CONTROLE'] >= $limitdeenvio) {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'limite de envio excedido',
            'coderro' => '93'
        ));
        exit();
    }
    //trava o reenvio para o mesmo numero em 5 min 
    //solicitação do maurice
    //$temporeenvio='5';

    if ($rwlogtoken['NUM_CELULAR_COMPARAR'] == fnlimpaCPF($celular)) {

        if ($rwlogtoken['DATA_VALIDADE'] > date('Y-m-d H:i:s')) {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => 'Por favor aguarda ' . $temporeenvio . ' min para refazer o envio ou tente um novo numero!',
                'coderro' => '93'
            ));
            exit();
        }
    }
    //  }			

    //defult quantidade de senha gerada
    if ($row['QTD_CHARTKN'] == ' ') {
        $QTD_CHARTKN = '6';
    } else {
        $QTD_CHARTKN = $row['QTD_CHARTKN'];
    }

    if ($rwlogtoken['LOG_USADO'] == '') {

        if ($row['TIP_TOKEN'] == '1') {
            $TIP_TOKEN = true;
        } else {
            $TIP_TOKEN = false;
        }
        //gerando token

        //inserir registro
        //se o token ja existir gerar um novo
        //verificar se o token ja foi utilizado e gerar um novo
        do {

            $senha = fngeraSenha($QTD_CHARTKN, $TIP_TOKEN, true, true);
            $sqlTokenvl = "SELECT 1 FROM geratoken WHERE 
  					                                        COD_EXCLUSA=0 
                                                                                and COD_EMPRESA = '" . $dadosLogin['idcliente'] . "' 
                                                                                AND DES_TOKEN = '$senha' $cpfconsulta";
            $arrayTokenvl = mysqli_query($connUser->connUser(), $sqlTokenvl);
            $existeTknvl = mysqli_num_rows($arrayTokenvl);

            /*if($cpf=='01734200014')
                                  {    
                                      echo $sqlTokenvl;
                                  }*/
        } while ($existeTknvl > 0);
        $dataValidade = date('Y-m-d H:i:s', strtotime('+' . $temporeenvio . ' minutes'));

        $sqlinsert1 = "INSERT INTO geratoken (COD_EMPRESA, 
                                                                    DAT_CADASTR, 
                                                                    DES_TOKEN, 
                                                                    NOM_CLIENTE, 
                                                                    NUM_CGCECPF, 
                                                                    NUM_CELULAR, 
                                                                    DES_EMAIL,
                                                                    TIP_TOKEN,
                                                                    COD_UNIVEND,
                                                                    COD_USUCADA,
                                                                    DAT_VALIDADE) 
                                                                    VALUES 
                                                                    ('" . $dadosLogin['idcliente'] . "', 
                                                                    '" . date('Y-m-d H:i:s') . "', 
                                                                    '" . $senha . "', 
                                                                    '" . fnAcentos($nome) . "', 
                                                                    '" . fnlimpaCPF($cpf) . "', 
                                                                    '" . fnlimpaCPF($celular) . "', 
                                                                    '" . $email . "',
                                                                    '" . $tipoGeracao . "',
                                                                    '" . $dadosLogin['idloja'] . "',
                                                                    '" . $row['COD_USUARIO'] . "',
                                                                     '" . $dataValidade . "')";

        $regera = mysqli_query($CONTEMPFIXA, $sqlinsert1);
        if (!$regera) {
            return  array('retornatoken' => array(
                'token' => '0',
                'msgerro' => "Por favor verifique o nome ou numero do envio.",
                'coderro' => '104'
            ));
            exit();
        }
        $COD_TOKEN = mysqli_insert_id($CONTEMPFIXA);
    } else {

        //token atual
        $senha = $rwlogtoken['DES_TOKEN'];
        $COD_TOKEN = $rwlogtoken['COD_TOKEN'];
        //inserir novo reenvio caso exista numero de telefone difetentes
        if ($rwlogtoken['NUM_CELULAR_COMPARAR'] != fnlimpaCPF($celular)) {

            $sqlinsert = "INSERT INTO geratoken (COD_EMPRESA, 
                                                                                    DAT_CADASTR, 
                                                                                    DES_TOKEN, 
                                                                                    NOM_CLIENTE, 
                                                                                    NUM_CGCECPF, 
                                                                                    NUM_CELULAR, 
                                                                                    DES_EMAIL,
                                                                                    TIP_TOKEN,
                                                                                    COD_UNIVEND,
                                                                                    COD_USUCADA,
                                                                                    DAT_VALIDADE) 
                                                                                    VALUES 
                                                                                    ('" . $dadosLogin['idcliente'] . "', 
                                                                                    '" . date('Y-m-d H:i:s') . "', 
                                                                                    '" . $senha . "', 
                                                                                    '" . fnAcentos($nome) . "', 
                                                                                    '" . fnlimpaCPF($cpf) . "', 
                                                                                    '" . fnlimpaCPF($celular) . "', 
                                                                                    '" . $email . "',
                                                                                    '" . $tipoGeracao . "',
                                                                                    '" . $dadosLogin['idloja'] . "',
                                                                                    '" . $row['COD_USUARIO'] . "',
                                                                                    NOW() + INTERVAL " . $temporeenvio . " MINUTE)";

            $regera = mysqli_query($CONTEMPFIXA, $sqlinsert);
            if (!$regera) {
                return  array('retornatoken' => array(
                    'token' => '0',
                    'msgerro' => "Por favor verifique o nome ou numero do envio.",
                    'coderro' => '104'
                ));
                exit();
            }
            $COD_TOKEN = mysqli_insert_id($CONTEMPFIXA);
        }
        //fim da gravação do reenvio
    }


    //=================================================================
    //capturando dominio inicial
    $sqldominio = "SELECT DES_DOMINIO,COD_DOMINIO from site_extrato WHERE cod_empresa='" . $dadosLogin['idcliente'] . "'";
    $rsdominio = mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $sqldominio));
    $DES_DOMINIO = $rsdominio['DES_DOMINIO'];
    $COD_DOMINIO = $rsdominio['COD_DOMINIO'];

    //envio sms
    if ($celular != '') {
        //aqui vai ser para pegar a chave SMS para verificar qual campo vai ser preenchido para envio da comunicação
        if ($tipoGeracao == '1') {
            unset($campanhaSMS);
            $campanhaSMS = "SELECT 	G.COD_EMPRESA,
                                                                G.COD_CAMPANHA,
                                                                G.LOG_STATUS,
                                                                G.LOG_PROCESS,
                                                                G.HOR_ESPECIF,
                                                                T.DES_TEMPLATE,
                                                                C.LOG_ATIVO,
                                                                C.DAT_INI,
                                                                C.DAT_FIM,
                                                                C.DES_CAMPANHA,
                                                                C.LOG_PROCESSA_SMS,
                                                                NULL LOG_PROCESSA_WHATSAPP
								FROM gatilho_sms G
								INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
								INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
								INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
								WHERE G.cod_empresa='" . $dadosLogin['idcliente'] . "' AND G.TIP_GATILHO='tokenCad'
                                                                    AND C.LOG_ATIVO='S'
                                                                    AND C.LOG_PROCESSA_SMS='S'
                                                       UNION all
                                                            SELECT G.COD_EMPRESA,
                                                                G.COD_CAMPANHA,
                                                                G.LOG_STATUS,
                                                                G.LOG_PROCESS,
                                                                G.HOR_ESPECIF,
                                                                CASE
                                                                    WHEN W.DES_TEMPLATE2 != '' AND @concador = 2 THEN W.DES_TEMPLATE2
                                                                    WHEN W.DES_TEMPLATE3 != '' AND @concador = 3 THEN W.DES_TEMPLATE3
                                                                    WHEN W.DES_TEMPLATE4 != '' AND @concador = 4 THEN W.DES_TEMPLATE4
                                                                    WHEN W.DES_TEMPLATE5 != '' AND @concador = 5 THEN W.DES_TEMPLATE5
                                                                    ELSE W.DES_TEMPLATE
                                                                END AS DES_TEMPLATE,
                                                                C.LOG_ATIVO,
                                                                C.DAT_INI,
                                                                C.DAT_FIM,
                                                                C.DES_CAMPANHA,
                                                               NULL LOG_PROCESSA_SMS,
                                                               C.LOG_PROCESSA_WHATSAPP
                                                                    FROM gatilho_whatsapp G
                                                                    INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                                    INNER JOIN mensagem_whatsapp wm ON wm.COD_CAMPANHA=G.COD_CAMPANHA
                                                                    INNER JOIN template_whatsapp W ON W.COD_TEMPLATE=wm.COD_TEMPLATE_WHATSAPP
                                                                    WHERE 
                                                                    G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                                    AND G.TIP_GATILHO='tokenCad'
                                                                    AND C.LOG_ATIVO='S'
                                                                    AND C.LOG_PROCESSA_WHATSAPP='S';";
        }
        if ($tipoGeracao == '2') {
            unset($campanhaSMS);
            $campanhaSMS = "SELECT 	G.COD_EMPRESA,
                                                                G.COD_CAMPANHA,
                                                                G.LOG_STATUS,
                                                                G.LOG_PROCESS,
                                                                G.HOR_ESPECIF,
                                                                T.DES_TEMPLATE,
                                                                C.LOG_ATIVO,
                                                                C.DAT_INI,
                                                                C.DAT_FIM,
                                                                C.DES_CAMPANHA,
                                                                 C.LOG_PROCESSA_SMS,
                                                                NULL LOG_PROCESSA_WHATSAPP
								FROM gatilho_sms G
								INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
								INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
								INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
								WHERE G.cod_empresa='" . $dadosLogin['idcliente'] . "' AND G.TIP_GATILHO='tokenVen'
                                                                AND C.LOG_ATIVO='S'
                                                                AND C.LOG_PROCESSA_SMS='S'
                                                        UNION all
                                                            SELECT G.COD_EMPRESA,
                                                                G.COD_CAMPANHA,
                                                                G.LOG_STATUS,
                                                                G.LOG_PROCESS,
                                                                G.HOR_ESPECIF,
                                                                CASE
                                                                    WHEN W.DES_TEMPLATE2 != '' AND @concador = 2 THEN W.DES_TEMPLATE2
                                                                    WHEN W.DES_TEMPLATE3 != '' AND @concador = 3 THEN W.DES_TEMPLATE3
                                                                    WHEN W.DES_TEMPLATE4 != '' AND @concador = 4 THEN W.DES_TEMPLATE4
                                                                    WHEN W.DES_TEMPLATE5 != '' AND @concador = 5 THEN W.DES_TEMPLATE5
                                                                    ELSE W.DES_TEMPLATE
                                                                END AS DES_TEMPLATE,
                                                                C.LOG_ATIVO,
                                                                C.DAT_INI,
                                                                C.DAT_FIM,
                                                                C.DES_CAMPANHA,
                                                                NULL LOG_PROCESSA_SMS,
                                                               C.LOG_PROCESSA_WHATSAPP
                                                                    FROM gatilho_whatsapp G
                                                                    INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                                                    INNER JOIN mensagem_whatsapp wm ON wm.COD_CAMPANHA=G.COD_CAMPANHA
                                                                    INNER JOIN template_whatsapp W ON W.COD_TEMPLATE=wm.COD_TEMPLATE_WHATSAPP
                                                                    WHERE 
                                                                    G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
                                                                    AND G.TIP_GATILHO='tokenVen'
                                                                    AND C.LOG_ATIVO='S'
                                                                    AND C.LOG_PROCESSA_WHATSAPP='S';";
        }

        $rwcampanhaSMS = mysqli_query($connUser->connUser(), $campanhaSMS);

        while ($rscampanhaSMS = mysqli_fetch_assoc($rwcampanhaSMS)) {
            if ($rscampanhaSMS['LOG_ATIVO'] != "") {
                $naoCampanha = '1';
                if ($rscampanhaSMS['LOG_ATIVO'] == 'S') {
                    if ($celular == '') {
                        return  array('retornatoken' => array(
                            'token' => '0',
                            'msgerro' => 'Campo Celular precisam ser preenchidos!',
                            'coderro' => '94'
                        ));
                        exit();
                    }
                }
                //===================================================================		
                //foi mudado dia 26/05/2021 codigo 1 passou a ser 2 
                //foi mudado dia 28/05/2021 voltou a ser o primrito movimento. 

                //alterar o variavel peolo texto
                $TEXTOENVIO = str_replace('<#TOKEN>', $senha, $rscampanhaSMS['DES_TEMPLATE']);
                // $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'http://'.$DES_DOMINIO.'.mais.cash/ativacao.do?id='.$COD_TOKEN, $TEXTOENVIO);
                if ($COD_DOMINIO == '1') {
                    $TEXTOENVIO = str_replace('<#LINKTOKEN>', 'https://' . $DES_DOMINIO . '.mais.cash/ativacao.do', $TEXTOENVIO);
                }
                if ($COD_DOMINIO == '2') {
                    $TEXTOENVIO = str_replace('<#LINKTOKEN>', 'https://' . $DES_DOMINIO . '.fidelidade.mk/ativacao.do', $TEXTOENVIO);
                }


                $NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($nome))));
                $TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);


                //===================================================
                if ($rscampanhaSMS['LOG_PROCESSA_SMS'] == 'S') {
                    include_once '../_system/func_nexux/func_transacional.php';

                    //senha para autenticar o envio

                    $sqlsenhasms = "SELECT * FROM senhas_parceiro apar
                                                                INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                                                WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU in ('17','19','22','23','24') AND apar.LOG_ATIVO='S'
                                                                AND apar.COD_EMPRESA = '" . $dadosLogin['idcliente'] . "'";
                    $rssenhasms = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlsenhasms));

                    $id_campanha1 = date('His');
                    //verificar sem tem saldo disponivel
                    $sqlcomdebt = "SELECT 
                                                            pedido.TIP_LANCAMENTO 
                                                           ,pedido.COD_VENDA
                                                           ,pedido.COD_PRODUTO 
                                                           ,emp.NOM_EMPRESA
                                                           ,pedido.DAT_CADASTR 
                                                           , pedido.COD_ORCAMENTO
                                                           , canal.DES_CANALCOM
                                                           , canal.COD_CANALCOM
                                                           ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                                           ,SUM(round(pedido.QTD_SALDO_ATUAL,0)) QTD_SALDO_ATUAL
                                                           , pedido.VAL_UNITARIO
                                                           , pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                                           , if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                                  FROM pedido_marka pedido 
                                                        INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                                        INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                                        INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                                        WHERE pedido.COD_ORCAMENTO > 0 AND 
                                                               pedido.COD_EMPRESA =" . $dadosLogin['idcliente'] . " AND
                                                                PAG_CONFIRMACAO='S' and
                                                                canal.COD_TPCOM=2
                                                                AND pedido.QTD_SALDO_ATUAL > 0  AND 
                                                                pedido.DAT_VALIDADE IS NOT NULL and
                                                                pedido.TIP_LANCAMENTO ='C' 
                                                            GROUP BY  pedido.TIP_LANCAMENTO	            
                                                       ORDER BY pedido.TIP_LANCAMENTO desc";
                    $rwarraysql = mysqli_query($connAdm->connAdm(), $sqlcomdebt);
                    if ($rwarraysql->num_rows <= 0) {
                        $saldorestante = '0';

                        //include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
                        include_once '../_system/PHPMailer/class.phpmailer.php';
                        include_once '../externo/email/envio_sac.php';
                        $emailDestino = array(
                            'email1' => 'diogo_tank@hotmail.com',
                            'email5' => 'rone.all@gmail.com;coordenacaoti@markafidelizacao.com.br;marcio@markafidelizacao.com.br;maurice@markafidelizacao.com.br'
                        );
                        fnsacmail(
                            $emailDestino,
                            "Suporte Marka",
                            "<html>Saldo insuficiente<br> COD_EMPRESA: " . $dadosLogin['idcliente'] . "</html>",
                            "ERRO NO ENVIO DE SMS",
                            "SMS_PROBLEMA",
                            $connAdm->connAdm(),
                            $connUser->connUser(),
                            "3"
                        );

                        return  array('retornatoken' => array(
                            'token' => '0',
                            'msgerro' => 'Saldo Insuficiente avise o Administrador do sistema!',
                            'coderro' => '103'
                        ));
                        exit();
                    } else {
                        while ($rssaldo = mysqli_fetch_assoc($rwarraysql)) {
                            $saldorestante = $rssaldo['QTD_SALDO_ATUAL'];
                        }
                        if ($saldorestante <= '1') {


                            //include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
                            include_once '../_system/PHPMailer/class.phpmailer.php';
                            include_once '../externo/email/envio_sac.php';
                            $emailDestino = array(
                                'email1' => 'diogo_tank@hotmail.com',
                                'email5' => 'rone.all@gmail.com;coordenacaoti@markafidelizacao.com.br;marcio@markafidelizacao.com.br;maurice@markafidelizacao.com.br'
                            );
                            fnsacmail(
                                $emailDestino,
                                "Suporte Marka",
                                "<html>Saldo insuficiente<br> COD_EMPRESA: " . $dadosLogin['idcliente'] . "</html>",
                                "ERRO NO ENVIO DE SMS",
                                "SMS_PROBLEMA",
                                $connAdm->connAdm(),
                                $connUser->connUser(),
                                "3"
                            );

                            return  array('retornatoken' => array(
                                'token' => '0',
                                'msgerro' => 'Saldo Insuficiente avise o Administrador do sistema!',
                                'coderro' => '103'
                            ));
                            exit();
                        }
                    }
                }
                //===========
                //novo envio de funcão

                $nom_camp_msg = $rscampanhaSMS['COD_CAMPANHA'] . '||' . $rscampanhaSMS['COD_EMPRESA'] . '||0';
                $nom_camp_envio = $rscampanhaSMS['DES_CAMPANHA'] . '||' . $rscampanhaSMS['COD_CAMPANHA'] . '||' . $rscampanhaSMS['COD_EMPRESA'];
                if ($rssenhasms['COD_PARCOMU'] == '17') {
                    $CLIE_SMS_L[] = array(
                        "numero" => $celular,
                        "mensagem" => $TEXTOENVIO,
                        "DataAgendamento" => '' . date('Y-m-d H:i:s') . '',
                        "Codigo_cliente" => "$nom_camp_msg"
                    );

                    $testefast = EnvioSms_fast($rssenhasms["DES_AUTHKEY"], $nom_camp_envio, json_encode($CLIE_SMS_L));
                    //  $testefast= EnvioSms_fast_file_get($rssenhasms["DES_AUTHKEY"],$nom_camp_envio,json_encode($CLIE_SMS_L));

                    $cod_erro_nexux = $testefast['Resultado']['CodigoResultado'];
                    if ($cod_erro_nexux == '0') {
                        $CHAVE_GERAL = $testefast['Resultado']['Chave'];
                        $CHAVE_CLIENTE = $testefast['Mensagens'][0]['UniqueID'];
                    }
                    $msgenvio = $testefast['Resultado']['Mensagem'];

                    //brasil fone inicia aqui  
                } else {
                    if ($rscampanhaSMS['LOG_PROCESSA_SMS'] == 'S') {

                        // if($cpf=='01734200014')
                        // {
                        if ($rssenhasms['COD_PARCOMU'] == 22) {
                            $rssenhasms['COD_LISTA'] = 'basic ' . base64_encode($rssenhasms['DES_USUARIO'] . ':' . $rssenhasms['DES_AUTHKEY']);
                        }
                        //nova função de encio 
                        $array = array(
                            'PROVEDOR' => $rssenhasms['COD_PARCOMU'],
                            'URL' => $rssenhasms['URL_API'],
                            'METHOD' => 'POST',
                            'Authorization' => $rssenhasms['COD_LISTA'],
                            'Usuario' => $rssenhasms['DES_CLIEXT'],
                            'COD_EMPRESA' => $dadosLogin['idcliente'],
                            'SEND' => array(
                                array(
                                    'Body' => $TEXTOENVIO,
                                    'From' => $rssenhasms['DES_CLIEXT'],
                                    'To' => '+55' . $celular,
                                    'Codigointerno' => 0,
                                    'COD_CLIENTE' => 0
                                )
                            )
                        );
                        $responsetwilo = fnenviosms($array);
                        //  }
                        /*else{
                                                    
                                                    $CLIE_SMS_L[]=array("from"=>$rssenhasms['DES_CLIEXT'],
                                                                        "to" =>'+55'.$celular, 
                                                                        "mensagem"=>$TEXTOENVIO,                   
                                                                        "DataAgendamento"=> date('Y-m-d H:i:s'),
                                                                        "Codigointerno"=> base64_encode($nom_camp_msg)
                                                                       );  
                                                    $base64= base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);
                                                     
                                                    $responsetwilo=sms_twilo($base64,$CLIE_SMS_L,$rssenhasms[DES_USUARIO],$rssenhasms[DES_AUTHKEY]);
                                                    }   */
                        $cod_erro_nexux = '0';
                        if ($cod_erro_nexux == '0') {
                            $CHAVE_GERAL = $responsetwilo[0]['account_sid'];
                            $CHAVE_CLIENTE = $responsetwilo[0]['sid'];
                        }
                        $msgenvio = $responsetwilo[0]['status'];

                        $codinternoParcomu = 22;
                    }
                    //enviar a mesma msg via whatsapp
                    if ($rscampanhaSMS['LOG_PROCESSA_WHATSAPP'] == 'S') {
                        include_once '../_system/whatsapp/wstAdorai.php';
                        $sqlsenhasms = "
                                                                   SELECT 
                                                                            sp.COD_SENHAPARC,
                                                                            sp.COD_EMPRESA,
                                                                            case when sp.COD_UNIVEND ='' then '9999' ELSE sp.COD_UNIVEND END COD_UNIVEND ,
                                                                            sp.DES_AUTHKEY,
                                                                            sp.NUM_CELULAR,
                                                                            sp.COD_PARCOMU,
                                                                            sp.LOG_ATIVO,
                                                                            sp.COD_USUCADA,
                                                                            sp.DAT_CADASTR,
                                                                            sp.COD_USUALT,
                                                                            sp.DAT_ALTERAC,
                                                                            sp.COD_EXCLUSA,
                                                                            sp.DAT_EXCLUSA,
                                                                            sp.DES_BASE64,
                                                                            sp.DES_STATUS,
                                                                            sp.NOM_SESSAO,
                                                                            sp.DES_TOKEN,
                                                                            sp.LOG_LOGIN,
                                                                            sp.DAT_LOGOUT,
                                                                            sp.PORT_SERVICAO
                                                                    from SENHAS_WHATSAPP sp
                                                                    WHERE sp.COD_EMPRESA = '" . $rscampanhaSMS['COD_EMPRESA'] . "' AND  sp.COD_UNIVEND IN(" . $dadosLogin['idloja'] . ",9999) 
                                                                    LIMIT 1";
                        $rwwts = mysqli_query($connAdm->connAdm(), $sqlsenhasms);
                        if ($rwwts->num_rows > 0) {
                            $rssenhasms = mysqli_fetch_assoc($rwwts);
                            $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $TEXTOENVIO);

                            $responsewts = FnsendText($rssenhasms['NOM_SESSAO'], $rssenhasms['DES_AUTHKEY'], '+55' . $celular, $msgsbtr, 3, $rssenhasms['PORT_SERVICAO']);

                            $CHAVE_GERAL = $responsewts['key']['id'];
                            $CHAVE_CLIENTE = $responsewts['key']['id'];
                            $msgenvio = $responsewts['status'];

                            $codinternoParcomu = 21;
                            //não gera a cobrança
                            $cod_erro_nexux = '1';
                            $listaret = 1;
                        } else {
                            break;
                        }
                    }
                }
                //  print_r($testefast);
                $jsonputo = json_encode($testefast);
                //$enviosmsmsg[infomacoes][0]=='SMS enviado' || 
                if ($cod_erro_nexux == '0') {
                    //==========envio de debitos				
                    $arraydebitos = array(
                        'quantidadeEmailenvio' => '1',
                        'COD_EMPRESA' => $dadosLogin['idcliente'],
                        'PERMITENEGATIVO' => 'N',
                        'COD_CANALCOM' => '2',
                        'CONFIRMACAO' => 'S',
                        'COD_CAMPANHA' => $rscampanhaSMS['COD_CAMPANHA'],
                        'LOG_TESTE' => 'N',
                        'DAT_CADASTR' => date('Y-m-d H:i:s'),
                        'CONNADM' => $connAdm->connAdm()
                    );
                    $retornoDeb = FnDebitosWS($arraydebitos);
                }

                //inserindo log de registro 
                //TIP_ENVIO = 1 SMS
                //TIP_ENVIO = 2 email


                $sqlinsertlog = "INSERT INTO rel_geratoken (TOKEN, COD_EMPRESA, TIP_ENVIO,COD_GERATOKEN,DES_MSG,DES_MSG_ENVIADA,DES_JSON,CHAVE_GERAL,CHAVE_CLIENTE) 
                                                                                                    VALUES 
                                                                                                    ('" . $senha . "', '" . $dadosLogin['idcliente'] . "', '1',$COD_TOKEN,'" . addslashes($msgenvio) . "','" . addslashes($TEXTOENVIO) . "','" . addslashes($jsonputo) . "','" . $CHAVE_GERAL . "','" . $CHAVE_CLIENTE . "');";
                mysqli_query($connUser->connUser(), $sqlinsertlog);

                unset($enviosmsmsg);
            } else {
                $naoCampanha = '2';
            }
            //gravando registro para pegar  o retorno do sms
            if ($tipoGeracao == '1') {
                if ($cod_erro_nexux == '0' || $listaret == 1) {
                    $sqlInsertRel = "INSERT INTO SMS_LISTA_RET(
                                                                                        COD_EMPRESA,
                                                                                        COD_CAMPANHA,                                                                               
                                                                                        NOM_CLIENTE,
                                                                                        COD_UNIVEND,
                                                                                        NUM_CELULAR,                                                                               
                                                                                        STATUS_ENVIO,
                                                                                        ID_DISPARO,
                                                                                        DES_MSG_ENVIADA	,
                                                                                        CHAVE_GERAL,
                                                                                        CHAVE_CLIENTE,
                                                                                        DES_STATUS,
                                                                                        idContatosMailing                                                                               
                                                                                        )values
                                                                                ('" . $row['COD_EMPRESA'] . "',
                                                                                 '" . $rscampanhaSMS['COD_CAMPANHA'] . "',       
                                                                                 '" . $NOM_CLIENTE[0] . "',
                                                                                 '" . $dadosLogin['idloja'] . "',
                                                                                 '" . $celular . "',
                                                                                 'S',
                                                                                 '" . date('Ymd') . "',
                                                                                 '" . $TEXTOENVIO . "',
                                                                                 '" . $CHAVE_GERAL . "',
                                                                                 '" . $CHAVE_CLIENTE . "',
                                                                                 '" . $msgenvio . "' ,
                                                                                 '" . $rssenhasms['COD_PARCOMU'] . "'    
                                                                                ) ; ";
                    mysqli_query($CONTEMPFIXA, $sqlInsertRel);
                }
            }
        }
    }
    //envio email
    if ($email != '') {
        //verificar tamplate de email
        if ($tipoGeracao == '1') {
            unset($sqltemplateemail);
            $sqltemplateemail = "SELECT 	G.COD_EMPRESA,
												G.COD_CAMPANHA,
												G.LOG_STATUS,
												G.HOR_ESPECIF,
												MODEL.DES_TEMPLATE,
												MODEL.DES_ASSUNTO,
												MODEL.DES_REMET,
												C.LOG_ATIVO,
												C.DAT_INI,
												C.DAT_FIM	
									FROM gatilho_email G
										INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
										INNER JOIN mensagem_email M ON M.COD_CAMPANHA=G.COD_CAMPANHA
										INNER JOIN template_email T ON T.COD_TEMPLATE=M.COD_TEMPLATE_EMAIL
										INNER JOIN modelo_email MODEL ON MODEL.COD_TEMPLATE=T.COD_TEMPLATE
									WHERE G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
									AND G.TIP_GATILHO='tokenCad' 
									AND C.LOG_ATIVO='S'";
        }
        if ($tipoGeracao == '2') {
            unset($sqltemplateemail);
            $sqltemplateemail = "SELECT 	G.COD_EMPRESA,
												G.COD_CAMPANHA,
												G.LOG_STATUS,
												G.HOR_ESPECIF,
												MODEL.DES_TEMPLATE,
												MODEL.DES_ASSUNTO,
												MODEL.DES_REMET,
												C.LOG_ATIVO,
												C.DAT_INI,
												C.DAT_FIM	
									FROM gatilho_email G
										INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
										INNER JOIN mensagem_email M ON M.COD_CAMPANHA=G.COD_CAMPANHA
										INNER JOIN template_email T ON T.COD_TEMPLATE=M.COD_TEMPLATE_EMAIL
										INNER JOIN modelo_email MODEL ON MODEL.COD_TEMPLATE=T.COD_TEMPLATE
									WHERE G.cod_empresa='" . $dadosLogin['idcliente'] . "' 
									AND G.TIP_GATILHO='tokenVen' 
									AND C.LOG_ATIVO='S'";
        }
        $rwcampanhaemail = mysqli_query($connUser->connUser(), $sqltemplateemail);
        $rscampanhaemail = mysqli_fetch_assoc($rwcampanhaemail);
        if ($rscampanhaemail['LOG_ATIVO'] != "") {
            $naoCampanha = '1';
            if ($rscampanhaemail['LOG_ATIVO'] == 'S') {
                if ($email == '') {
                    return  array('retornatoken' => array(
                        'token' => '0',
                        'msgerro' => 'Campo Email precisam ser preenchidos!',
                        'coderro' => '95'
                    ));
                    exit();
                }
            }

            //edição da menssagem de envio.
            $TEXTOENVIO = str_replace('{{cmp10}}', $senha, $rscampanhaemail['DES_TEMPLATE']);
            $TEXTOENVIO = str_replace(' {{cmp11}}', 'http://' . $DES_DOMINIO . '.mais.cash/ativacao.do?id=' . $COD_TOKEN, $TEXTOENVIO);
            $NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($nome))));
            $TEXTOENVIO = str_replace('{{cmp2}}', $NOM_CLIENTE[0], $TEXTOENVIO);
            //====================================

            include '../_system/PHPMailer/class.phpmailer.php';
            include '../externo/email/envio_sac.php';
            $emailenvio['email1'] = $email;

            fnsacmail(
                $emailenvio,
                $rscampanhaemail['DES_REMET'],
                '<HTML>' . $TEXTOENVIO . '</HTML>',
                $rscampanhaemail['DES_ASSUNTO'],
                $rscampanhaemail['DES_ASSUNTO'],
                $connAdm->connAdm(),
                $connUser->connUser(),
                '3'
            );
            $sqlinsertlogemail = "INSERT INTO rel_geratoken (TOKEN, COD_EMPRESA, TIP_ENVIO,COD_GERATOKEN) 
												VALUES 
												('" . $senha . "', '" . $dadosLogin['idcliente'] . "', '2',$COD_TOKEN);";

            mysqli_query($connUser->connUser(), $sqlinsertlogemail);
        } else {
            if ($naoCampanha == '2') {
                $naoCampanha = '2';
            }
        }
    }

    if ($naoCampanha == '2') {
        return  array('retornatoken' => array(
            'token' => '0',
            'msgerro' => 'Não Tem campanha para esse tipo de envio configurada!',
            'coderro' => '96'
        ));
        exit();
    }
    //alterando o limite de envio
    if ($rwlogtoken['NUM_CELULAR_COMPARAR'] == fnlimpaCPF($celular)) {
        $qtdreenvio = $rwlogtoken['QTD_REENVIO'] + 1;
        $qtdreenvioCONTROLE = $rwlogtoken['QTD_REENVIO_CONTROLE'] + 1;
    } else {
        $qtdreenvio = 1;
        $qtdreenvioCONTROLE = 1;
    }
    $sqlalterlimit = "UPDATE geratoken SET DAT_VALIDADE=NOW() + INTERVAL " . $temporeenvio . " MINUTE, QTD_REENVIO='" . $qtdreenvio . "',QTD_REENVIO_CONTROLE='" . $qtdreenvioCONTROLE . "' 
			                 WHERE  COD_TOKEN='" . $COD_TOKEN . "' and COD_EMPRESA='" . $dadosLogin['idcliente'] . "';";
    mysqli_query($CONTEMPFIXA, $sqlalterlimit);


    //alterando token para gravar o codigo
    if ($cod_log != '') {
        $log = "UPDATE origemtoken SET COD_PDV='$COD_TOKEN' WHERE cod_empresa=$row[COD_EMPRESA] AND cod_origem=$cod_log";
        mysqli_query($CONTEMPFIXA, $log);
    }
    mysqli_close($CONTEMPFIXA);
    return  array('retornatoken' => array(
        'token' => $senha,
        'msgerro' => 'OK',
        'coderro' => '39'
    ));
    exit();
}
