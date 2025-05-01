<?php
setlocale(LC_ALL, 'en_US.utf8');
//---------CLASS CONN
/*
ini_set('mysqli.allow_persistent', 1);
ini_set('mysqli.max_links',1);
ini_set('mysqli.max_persistent',2);
ini_set('mysqli.allow_local_infile',1);
ini_set('mysqli.reconnect',1);
ini_set('max_execution_time', 3);
ini_set('mysqli.connect_timeout', 3);
ini_set('mysqli.cache_size', 16000); // '2000'
ini_set('mysqli.cache_type', 0);
ini_set('default_socket_timeout', 30);
 * */

class BD
{
    protected $server;
    protected $usuario;
    protected $senha;
    public $DB;

    public function __construct($server, $usuario, $senha, $DB)
    {
        $this->server = $server;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->DB = $DB;
    }

    public function connAdm()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function connUser()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function connGERADOR()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function connREL()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function connDUQUE()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function conndepylclone()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }

    public function drogaleste()
    {
        if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
            define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
        }

        $conn = mysqli_init(); // Inicializa a conexão
        mysqli_real_connect(
            $conn,
            $this->server,
            $this->usuario,
            $this->senha,
            $this->DB,
            3320, // Porta como número, não string
            null, // Caminho do socket, se necessário
            MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS // Ativa compressão e múltiplas instruções
        );
        return $conn;
    }
}

//--------FIM CLASS
/*
        user: rededuque
        senha: H+rede29.5
        ip142.44.212.134
    */
//--------CONN 
//base de dados Geral    
$connAdm = new BD('144.217.255.136', 'adminterno', 'H+admin29.5', 'webtools');

if (!$connAdm->connAdm()) {

    echo die('Tente novamente mais tarde!');
    $error_message = "Erro na conexão: " . mysqli_connect_error();
    // Gravar o erro em um arquivo de texto
    $error_file = fopen("./LOG_TXT/con_arquivo.txt", "a"); // "a" para abrir o arquivo em modo de anexação
    if ($error_file) {
        fwrite($error_file, date("Y-m-d H:i:s") . " - " . $error_message . "\n");
        fclose($error_file);
    }
}


//$import_ibope = new BD('127.0.0.1','adminterno','H+admin29.5','import_ibope');
//$import_hotmobile = new BD('127.0.0.1','adminterno','H+admin29.5','sms_hotmobile');
$connAdmSAC = new BD('54.39.103.233', 'adminterno', 'cC9x6S9G5&db', 'db_sac');
//$connREL = new BD('127.0.0.1','adminterno','H+admin29.5','db_host1');

//comentado aqui para verificação de conexão invalida
//$grduque = new BD('142.44.212.134','adminterno','H+admin29.5','grduque');
//$connDUQUE = new BD('142.44.212.134','adminterno','H+admin29.5','portalduque');
$Import = new BD('142.44.212.134', 'adminterno', 'H+admin29.5', 'import');
//
////$grduque = new BD('191.252.2.68','markaapp','Apphg56&7','grduque');
//$connDUQUE = new BD('191.252.2.68','markaapp','Apphg56&7','portalduque');


//$depyl_clone = new BD('137.74.94.231','depyl_clone','H+clone29.5','db_depyl');
$DADOS_CEP = new BD('144.217.180.50', 'adminterno', 'H+admin29.5', 'dados_cep');
$Cdashboard = new BD('144.217.180.50', 'adminterno', 'H+admin29.5', 'db_dashboard');
$prod_continuo = new BD('144.217.180.50', 'adminterno', 'H+admin29.5', 'prod_continuo');
//conexão para selecionar o db 


function connTemptkt($conn, $parametro, $retornoBDNAME)
{
    $codEmpr = "select * from tab_database
                INNER JOIN empresas ON tab_database.COD_EMPRESA=empresas.COD_EMPRESA
                where tab_database.COD_EMPRESA='" . $parametro . "'";
    $codEmprR = mysqli_query($conn, $codEmpr);
    if ($codEmprR->num_rows > 0) {
        $codEmpreretorno = mysqli_fetch_assoc($codEmprR);
        //$senha='H+admin29.5';
        $senha = fnDecode($codEmpreretorno['SENHADB']);

        if ($retornoBDNAME == 'true') {
            return $codEmpreretorno['NOM_DATABASE'];
        } else {
            return mysqli_connect($codEmpreretorno['IP'], $codEmpreretorno['USUARIODB'], $senha, $codEmpreretorno['NOM_DATABASE'], '3320');
        }
    }
}

if (@$_SESSION["usuario"] == '') {
} else {

    //base de daods do cliente
    $connUser = new BD($_SESSION["servidor"], $_SESSION["userBD"], $_SESSION["SenhaBD"], $_SESSION["BD"]);
}

/*function connTemp($parametro, $retornoBDNAME)
{

    $connAdm = new BD('144.217.255.136', 'adminterno', 'H+admin29.5', 'webtools');
    $codEmpr = "SELECT * FROM tab_database
                INNER JOIN empresas ON tab_database.COD_EMPRESA = empresas.COD_EMPRESA
                WHERE tab_database.COD_EMPRESA = '" . $parametro . "' AND LOG_ATIVO = 'S'";
    $codEmprR = mysqli_query($connAdm->connAdm(), $codEmpr);

    if ($codEmprR->num_rows > 0) {
        $codEmpreretorno = mysqli_fetch_assoc($codEmprR);
        $senha = fnDecode($codEmpreretorno['SENHADB']);

        if ($retornoBDNAME == 'true') {
            return $codEmpreretorno['NOM_DATABASE'];
        } else {
            //   $mysqli = mysqli_connect($codEmpreretorno['IP'], $codEmpreretorno['USUARIODB'], $senha, $codEmpreretorno['NOM_DATABASE'], '3320');
            $mysqli = mysqli_init();

            // Verifica se houve erro na conexão
            //if (!$mysqli) {
            //    die('Erro na conexão: ' . mysqli_connect_error());
            //}
            // Conectando com a base de dados usando mysqli_real_connect e ativando compressão
            if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
                define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
            }

            if (!mysqli_real_connect(
                $mysqli,
                $codEmpreretorno['IP'],          // IP do servidor
                $codEmpreretorno['USUARIODB'],   // Usuário
                $senha,                          // Senha
                $codEmpreretorno['NOM_DATABASE'], // Nome do banco de dados
                3320,                            // Porta (número, sem aspas)
                null,                            // Caminho do socket (null se não for necessário)
                MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS  // Ativa compressão e múltiplas instruções
            )) {
                die("Erro na conexão: " . mysqli_connect_error());
            }

            // Define o conjunto de caracteres UTF-8
            if (!$mysqli->set_charset("utf8")) {
                die('Erro ao definir o conjunto de caracteres UTF-8: ' . $mysqli->error);
            }

            return $mysqli;
        }
    }
}
*/
/*function connTemp($parametro, $retornoBDNAME = false)
{
    $cacheFile = __DIR__ . "/conexao/db_config_{$parametro}.txt";
    $cacheTime = 10800; // Tempo de validade do cache (5 minutos)

    // Se o arquivo de cache existe e ainda é válido, usar os dados armazenados
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $config = json_decode(file_get_contents($cacheFile), true);

        // Decodifica os dados antes de usá-los
        $config['IP'] = fnDecode($config['IP']);
        $config['USUARIODB'] = fnDecode($config['USUARIODB']);
        $config['SENHADB'] = fnDecode($config['SENHADB']);
        $config['NOM_DATABASE'] = fnDecode($config['NOM_DATABASE']);
    } else {
        // Criar conexão com a tabela principal
        $connAdm = new BD('144.217.255.136', 'adminterno', 'H+admin29.5', 'webtools');
        $codEmpr = "SELECT * FROM tab_database
                    INNER JOIN empresas ON tab_database.COD_EMPRESA = empresas.COD_EMPRESA
                    WHERE tab_database.COD_EMPRESA = '" . $parametro . "' AND LOG_ATIVO = 'S'";
        $codEmprR = mysqli_query($connAdm->connAdm(), $codEmpr);

        if ($codEmprR->num_rows > 0) {
            $codEmpreretorno = mysqli_fetch_assoc($codEmprR);
            $config = [
                'IP' => fnEncode($codEmpreretorno['IP']),
                'USUARIODB' => fnEncode($codEmpreretorno['USUARIODB']),
                'SENHADB' => fnEncode(fnDecode($codEmpreretorno['SENHADB'])), // Garante que a senha esteja codificada corretamente
                'NOM_DATABASE' => fnEncode($codEmpreretorno['NOM_DATABASE'])
            ];

            // Salvar os dados da conexão no arquivo (codificados)
            file_put_contents($cacheFile, json_encode($config));
        } else {
            die("Erro: Nenhuma configuração encontrada para a empresa.");
        }

        // Decodificar os dados antes de usar
        $config['IP'] = fnDecode($config['IP']);
        $config['USUARIODB'] = fnDecode($config['USUARIODB']);
        $config['SENHADB'] = fnDecode($config['SENHADB']);
        $config['NOM_DATABASE'] = fnDecode($config['NOM_DATABASE']);
    }

    // Retorna apenas o nome do banco, se solicitado
    if ($retornoBDNAME == 'true') {
        return $config['NOM_DATABASE'];
    }

    // Criar conexão usando os dados armazenados
    $mysqli = mysqli_init();

    if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
        define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
    }

    if (!mysqli_real_connect(
        $mysqli,
        $config['IP'],
        $config['USUARIODB'],
        $config['SENHADB'],
        $config['NOM_DATABASE'],
        3320,
        null,
        MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS
    )) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    // Definir charset UTF-8
    if (!$mysqli->set_charset("utf8")) {
        die('Erro ao definir o conjunto de caracteres UTF-8: ' . $mysqli->error);
    }

    return $mysqli;
}*/
function connTemp($parametro, $retornoBDNAME = false)
{
    // Se $parametro for vazio, nulo ou zero, atribuir o valor padrão 7
    if (empty($parametro) || $parametro == 0) {
        $parametro = 7;
    }

    $cacheFile = __DIR__ . "/conexao/db_config_{$parametro}.txt";
    $cacheTime = 10800; // Tempo de validade do cache (3 horas)

    // Se o arquivo de cache existe e ainda é válido, usar os dados armazenados
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
        $config = json_decode(file_get_contents($cacheFile), true);

        // Decodifica os dados antes de usá-los
        $config['IP'] = fnDecode($config['IP']);
        $config['USUARIODB'] = fnDecode($config['USUARIODB']);
        $config['SENHADB'] = fnDecode($config['SENHADB']);
        $config['NOM_DATABASE'] = fnDecode($config['NOM_DATABASE']);
    } else {
        // Criar conexão com a tabela principal
        $connAdm = new BD('144.217.255.136', 'adminterno', 'H+admin29.5', 'webtools');
        $codEmpr = "SELECT * FROM tab_database
                    INNER JOIN empresas ON tab_database.COD_EMPRESA = empresas.COD_EMPRESA
                    WHERE tab_database.COD_EMPRESA = '" . $parametro . "'";
        $codEmprR = mysqli_query($connAdm->connAdm(), $codEmpr);

        if ($codEmprR->num_rows > 0) {
            $codEmpreretorno = mysqli_fetch_assoc($codEmprR);
            $config = [
                'IP'           => fnEncode($codEmpreretorno['IP']),
                'USUARIODB'    => fnEncode($codEmpreretorno['USUARIODB']),
                'SENHADB'      => fnEncode(fnDecode($codEmpreretorno['SENHADB'])), // Garante que a senha esteja codificada corretamente
                'NOM_DATABASE' => fnEncode($codEmpreretorno['NOM_DATABASE'])
            ];

            // Salvar os dados da conexão no arquivo (codificados)
            file_put_contents($cacheFile, json_encode($config));
        } else {
            // Consulta para buscar as empresas que não possuem configuração ativa de conexão
            $empresasSemConexao = [];
            $queryEmpresas = "SELECT e.COD_EMPRESA, e.NOM_FANTASI 
                              FROM empresas e 
                              LEFT JOIN tab_database t ON e.COD_EMPRESA = t.COD_EMPRESA
                              WHERE t.COD_EMPRESA IS NULL";
            $resultEmpresas = mysqli_query($connAdm->connAdm(), $queryEmpresas);
            if ($resultEmpresas) {
                while ($row = mysqli_fetch_assoc($resultEmpresas)) {
                    $empresasSemConexao[] = $row;
                }
            }

            // Monta a mensagem com as empresas sem conexão
            $empresasMsg = "";
            if (!empty($empresasSemConexao)) {
                foreach ($empresasSemConexao as $empresa) {
                    // Se estiver exibindo em HTML, substitua "\n" por "<br>"
                    $empresasMsg .= "Código: " . $empresa['COD_EMPRESA'] . " - Nome: " . $empresa['NOME_EMPRESA'] . "\n";
                }
            } else {
                // $empresasMsg = "Nenhuma empresa sem conexão encontrada.";
            }

            die("Erro:  Empresas sem conexão:\n '{$parametro}:::::'" . $empresasMsg);
        }

        // Decodificar os dados antes de usar
        $config['IP'] = fnDecode($config['IP']);
        $config['USUARIODB'] = fnDecode($config['USUARIODB']);
        $config['SENHADB'] = fnDecode($config['SENHADB']);
        $config['NOM_DATABASE'] = fnDecode($config['NOM_DATABASE']);
    }

    // Retorna apenas o nome do banco, se solicitado
    if ($retornoBDNAME == 'true') {
        return $config['NOM_DATABASE'];
    }

    // Criar conexão usando os dados armazenados
    $mysqli = mysqli_init();

    if (!defined('MYSQLI_CLIENT_MULTI_STATEMENTS')) {
        define('MYSQLI_CLIENT_MULTI_STATEMENTS', 131072);
    }

    if (!mysqli_real_connect(
        $mysqli,
        $config['IP'],
        $config['USUARIODB'],
        $config['SENHADB'],
        $config['NOM_DATABASE'],
        3320,
        null,
        MYSQLI_CLIENT_COMPRESS | MYSQLI_CLIENT_MULTI_STATEMENTS
    )) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    // Definir charset UTF-8
    if (!$mysqli->set_charset("utf8")) {
        die('Erro ao definir o conjunto de caracteres UTF-8: ' . $mysqli->error);
    }

    return $mysqli;
}

//---------FIM CONN
