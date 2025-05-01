<?php

// function getClientIP()
// {
//     if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//         // Retorna o primeiro IP da lista
//         $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
//         return trim($ips[0]);
//     } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//         return $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
//         return $_SERVER['REMOTE_ADDR'];
//     }
//     return 'IP não encontrado';
// }
// echo getClientIP();

// if (getClientIp() ==  '177.104.209.219') {
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
// }



clearstatcache();
set_time_limit(300);
//set_time_limit(30);
//setlocale(LC_ALL, 'pt_BR.utf8');
ignore_user_abort(true);
ini_set("default_socket_timeout", 10);
//ini_set('output_buffering','4092');
//ini_set('memory_limit', '4096M');
//ini_set('post_max_size', '2048M');
//ini_set('max_input_vars', '3000');
ini_set('default_week_format', '0');
if (strpos($_SERVER['HTTP_HOST'], 'localhost') === false) {
    ini_set('session.save_path', '/srv/www/htdocs/_system/tmp');
}
//ini_set("zlib.output_compression", 4096);
//ini_set("zlib.output_compression_level", 9);
//ini_set('max_execution_time', '990');
//ini_set('max_input_time', '3600');
//ini_set('max_input_nesting_level', '64');

//@ini_set('innodb_lock_wait_timeout', '120');
//@ini_set('upload_max_filesize', '2048M');

// Has PHP been set with an upload_tmp_dir?
if (ini_get('upload_tmp_dir')) {
    $directories[] = ini_get('upload_tmp_dir');
}

// Determine based on operating system.
if (substr(PHP_OS, 0, 3) == 'WIN') {
    $directories[] = @$_ENV['TEMP'];
    // Windows env var TEMP may not exist, so try other common locations too.
    $directories[] = 'c:\\windows\\temp';
    $directories[] = 'c:\\winnt\\temp';
    if (function_exists('variable_get')) {
        $directories[] = variable_get('file_directory_path', 'files') . '\\tmp';
    }
} else {
    $directories[] = '/srv/www/htdocs/_system/tmp';
}

foreach ($directories as $directory) {
    if (is_dir($directory)) {
        // echo $directory;
    }
}

//ini_set('session.save_handler', 'memcache');
//ini_set('session.save_path', 'tcp://127.0.0.1:11211');
//ini_set('memcache.allow_failover', '1');
//ini_set('memcache.redundancy', '1');
//ini_set('smemcache.session_redundancy', '2');


//echo ini_get('mysqli.allow_persistent');
session_cache_expire(300);
session_start();
//date_default_timezone_set('America/Sao_Paulo');
date_default_timezone_set('Etc/GMT+3');

@ini_set('default_charset', 'UTF-8');

//include 'Class_conn.php'; 
require_once 'Class_conn.php';

//desabilitar o case sensitive mysql
//$nocase = "lower_case_table_names = 1";
//mysqli_query($connAdm->connAdm(),$nocase);


//---------LOG DATABASE



function LOG_DB($connerro)
{

    if (mysqli_ping($connerro)) {
        $baseOK = "Connection is ok!";
        $logerro = mysqli_error($connerro);
        $escreve = 'Conexão bem sucedida na Base !"' . date("d-m-Y H:i:s") . '"--IP--"' . $_SERVER['REMOTE_ADDR'] . '":"' . $_SERVER['REMOTE_PORT'] . '" : "' . $baseOK . '" ---- database name :"' . $_SESSION["BD"] . '"';
        $logqueryinsert = "insert into log (DATA,LOG_COL) values ('" . DATE("d/m/Y H:i:s") . "','" . $escreve . "');";
        mysqli_query($connerro, $logqueryinsert);
    } else {

        $escreve = 'Erro ao Logar na base de dados!"' . date("d-m-Y H:i:s") . '"--IP--"' . $_SERVER['REMOTE_ADDR'] . '":"' . $_SERVER['REMOTE_PORT'] . '" : ERRO : ---- database name :"' . $_SESSION["BD"] . '"';

        $logqueryinsert = "insert into log (DATA,LOG_COL) values ('" . DATE("d/m/Y H:i:s") . "','" . $escreve . "');";
        mysqli_query($connerro, $logqueryinsert);
    }
}

//---------FIM

//-------------LOG QUERY   
function logquery($comand, $resultado, $linha, $conn, $pagina)
{
    $escreve = '"' . addslashes($comand) . '"+"' . $resultado . '"++
                  "' . $linha . '"+++"' . $_SERVER['REMOTE_ADDR'] . '":
                  "' . $_SERVER['REMOTE_PORT'] . '"+++++"' . $pagina . '"';
    $logqueryinsert = "insert log (DATA,LOG_COL) values
                                                      ('" . DATE("H:i:s") . "','" . $escreve . "');";
    mysqli_query($conn, $logqueryinsert);
}
function logwebservice($get)
{
    return $escreve = '$weblog=REQUEST:\n"' . $get;
    $logqueryinsert = "insert into log (DATA,LOG_COL) 
                                          values
                                         ('" . DATE("H:i:s") . "','" . $escreve . "');";
    mysqli_query($conn, $logqueryinsert);
}
//-------------FIM
//

//-------------ENCRYPT
function fnEncode($pure_string)
{
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, '123456', utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv_size);
    $encrypted_string = base64_encode($encrypted_string);
    return trim(str_replace($dirty, $clean, $encrypted_string));
}
//-----------FIM
//
//-------------DECRYPT       
function fnDecode($encrypted_string)
{
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $string = base64_decode(str_replace($clean, $dirty, $encrypted_string));
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, '123456', $string, MCRYPT_MODE_ECB, $iv_size);
    return trim($decrypted_string);
}
/*
function make_openssl_blowfish_key($key)
{
    if("$key" === '')
        return $key;

    $len = (16+2) * 4;
    while(strlen($key) < $len) {
        $key .= $key;
    }
    $key = substr($key, 0, $len);
    return $key;
}
 
function fnEncode($str)
{
    $dirty = array("+", "/", "=","==");
    $clean = array("p£", "s£", "¢","¢");

    $blockSize = 8;
    $len = strlen($str);
    $paddingLen = intval(($len + $blockSize - 1) / $blockSize) * $blockSize - $len;
    $padding = str_repeat("\0", $paddingLen);
    $data = $str . $padding;
    $key = make_openssl_blowfish_key('123456');
    $encrypted = openssl_encrypt($data, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
    $encrypted= utf8_decode(base64_encode($encrypted));
    return str_replace($dirty, $clean, $encrypted);

}
function fnDecode($hex)
{
    $dirty = array("+", "/", "=","==");
    $clean = array("p£", "s£", "¢","¢");
    $key = make_openssl_blowfish_key('123456');
    $hex = base64_decode(str_replace($clean, $dirty, $hex));
    $decrypted = openssl_decrypt($hex, 'BF-ECB', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
    return rtrim($decrypted, "\0");
}
*/

//------------FIM
function fnNocachePage()
{

    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    /* 
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      
    }
    elseif(preg_match('/AppleWebKit/i',$u_agent))
    {
        $bname = 'AppleWebKit';
        $ub = "Opera";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }

    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    */
}


///////////////////
//---------Carrega a Pagina
function carregaPagina($vf)
{
    if ($vf == 'true') {
        /*
        $seconds_to_cache = 2;
        $ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
        header("Expires: $ts");     
        // Set a valid header so browsers pick it up correctly.
        header('Content-type: text/html; charset=utf-8');
        // Emulate the header BigPipe sends so we can test through Varnish.        
        header('Surrogate-Control: BigPipe/1.0');        
        header("Cache-Control: public, must-revalidate,max-age=$seconds_to_cache");
        header("Pragma: cache");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Last-Modified: $ts");
        header("Cache-Control: max-age=$seconds_to_cache");*/
        // ob_start();
        // ob_end_clean();
        //header("Connection: close\r\n");
        // header("Content-Encoding: none\r\n");

        ob_end_clean();
        ob_start();
    } elseif ($vf == 'false') {
        // $content = ob_get_contents();
        // $length = strlen($content);
        //  header('Content-Length: '.$length);
        ob_end_flush();
        ob_flush();
        flush();
        // ob_get_contents();

    } else {
        echo "Você esqueceu de carregar a variavel com True para inicio e false para final";
    }
}

function fnLimpaCampoEmail($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|\*|\\\\)/i", "", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}

function fnLimpaCampo($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|#|\*|--|\\\\)/i", "", $campo);
    $campo = trim(@$campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}
function fnLimpaCampoNoTrim($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|#|\*|--|\\\\)/i", "", $campo);
    // $campo = trim($campo);//limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}
function fnLimpaCampoHtml($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|\*|--|\\\\)/i", "", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        //$campo = addslashes($campo);
        return $campo;
}
function anti_injection($input)
{
    // Remove caracteres perigosos
    $input = stripslashes($input);
    $input = strip_tags($input, '<br><b><i>\n\r');
    $input = htmlentities($input, ENT_QUOTES, 'UTF-8');
    return $input;
}
function fnLimpaCampoZero($campo)
{

    if ($campo == "" || is_int($campo) || empty($campo) || !is_numeric($campo) || is_null($campo)) {

        //return $campo= (int)0;

        $campo = 0;
        return $campo;
    } else {

        return $campo;
    }
}
function fnVlVazio($campo)
{
    $campo = fnValorSql($campo);
    if ($campo == "" || is_int($campo) || empty($campo) || !is_numeric($campo) || is_null($campo)) {

        //return $campo= (int)0;

        $campo1 = 0;
    } else {

        return $campo;
    }
}


function fnNocache($conn)
{
    return  $nocache = "SET SESSION query_cache_type=0";
    return  $result0 = mysqli_query($conn, $nocache);
    return  $sql1 = "FLUSH TABLES";
    return  $result0 = mysqli_query($conn, $sql1);
    return  $sql2 = "RESET QUERY CACHE";
    return  $result0 = mysqli_query($conn, $sql2);
}

function fncompress($conn, $timeseg)
{
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, $timeseg);

    // Habilita compressão na conexão
    // return mysqli_real_connect($conn, null, null, null, null, null, null, MYSQLI_CLIENT_COMPRESS);
    return true;
}

function cache_query($conn, $timeseg)
{
    if (mysqli_ping($conn)) {

        return  $sql0 = "flush query cache";
        return  $sql1 = "FLUSH TABLES";
        return  $sql2 = "RESET QUERY CACHE";
        return  $sql3 = "flush hosts,logs;";

        return $result0 = mysqli_query($conn, $sql0);
        return $row0 = mysqli_fetch_assoc($result0);
        return $row0;
        return $result1 = mysqli_query($conn, $sql1);
        return $row1 = mysqli_fetch_assoc($result1);
        return $row1;

        return $result2 = mysqli_query($conn, $sql2);
        return $row2 = mysqli_fetch_assoc($result2);

        return $row3;
        return $result3 = mysqli_query($conn, $sql3);
        return $row3 = mysqli_fetch_assoc($result3);
        return $row3;
        return mysqli_free_result();
        return mysqli_close($conn);
        unset($conn);
    } else {
        $logerro = mysqli_error($conn);
    }
}
function set_timeout($time)
{
    //shell_exec("%SystemRoot%\\system32\\net.exe stop mysql");
    shell_exec("%SystemRoot%\\system32\\net.exe start mysql");
    sleep($time);
}
function process_kill($conn)
{
    $t_id = mysqli_thread_id($conn);
    $teste = mysqli_kill($conn, $t_id);
    return $t_id;
}

function tempoinicial()
{
    return time();
}
function tempofinal($tempoinicial, $conn)
{
    $tt = time() - $tempoinicial;
    $sqlinsert = "insert into log (DATA,LOG_COL) values ('" . DATE("H:i:s") . "','" . $tt . "seg');";
    mysqli_query($conn, $sqlinsert);
}
function REPLACE_STD_SET($BASE)
{
    return eval('return ' . str_replace(array('stdClass::__set_state(', '))'), array('', ')'), $BASE) . ';');
}
function LIMPA_DOC($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    $valor = str_replace(" ", "", $valor);
    return trim($valor);
}
function fnLimpaDoc($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    $valor = str_replace(" ", "", $valor);
    return trim($valor);
}

function ALTERAR_PONTO($PONTOVIRGULA)
{
    $PONTOVIRGULA = trim($PONTOVIRGULA);
    $strtemp = str_replace(".", ",", $PONTOVIRGULA);
    return $strtemp;
}

function ALTERAR_QUERY($QUERY)
{
    $QUERY = trim($QUERY);
    $QUERY = str_replace("'", "", $QUERY);
    $QUERY = str_replace(".", "", $QUERY);
    $QUERY = str_replace(".", "", $QUERY);
    $QUERY = str_replace(":", "", $QUERY);
    $QUERY = str_replace("/", "", $QUERY);
    return $QUERY;
}

///SOMA DIAS UTEIS
function Feriados($ano, $posicao)
{
    $dia = 86400;
    $datas = array();
    $datas['pascoa'] = easter_date($ano);
    $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
    $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
    $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
    $feriados = array(
        '01/01',
        '02/02', // Navegantes
        date('d/m', $datas['carnaval']),
        date('d/m', $datas['sexta_santa']),
        date('d/m', $datas['pascoa']),
        '21/04',
        '01/05',
        date('d/m', $datas['corpus_cristi']),
        '12/10',
        '02/11',
        '15/11',
        '25/12',
    );

    return $feriados[$posicao] . "/" . $ano;
}
function dataToTimestamp($data)
{
    $ano = substr($data, 6, 4);
    $mes = substr($data, 3, 2);
    $dia = substr($data, 0, 2);
    return mktime(0, 0, 0, $mes, $dia, $ano);
}
function Soma1dia($data)
{
    $ano = substr($data, 6, 4);
    $mes = substr($data, 3, 2);
    $dia = substr($data, 0, 2);
    return date("d/m/Y", mktime(0, 0, 0, $mes, $dia + 1, $ano));
}

function SomaDiasUteis($xDataInicial, $xSomarDias)
{
    for ($ii = 1; $ii <= $xSomarDias; $ii++) {

        $xDataInicial = Soma1dia($xDataInicial); //SOMA DIA NORMAL

        //VERIFICANDO SE EH DIA DE TRABALHO
        if (date("w", dataToTimestamp($xDataInicial)) == "0") {
            //SE DIA FOR DOMINGO OU FERIADO, SOMA +1
            $xDataInicial = Soma1dia($xDataInicial);
        } else if (date("w", dataToTimestamp($xDataInicial)) == "6") {
            //SE DIA FOR SABADO, SOMA +2
            $xDataInicial = Soma1dia($xDataInicial);
            $xDataInicial = Soma1dia($xDataInicial);
        } else {
            //senaum vemos se este dia eh FERIADO
            for ($i = 0; $i <= 12; $i++) {
                if ($xDataInicial == Feriados(date("Y"), $i)) {
                    $xDataInicial = Soma1dia($xDataInicial);
                }
            }
        }
    }
    return $xDataInicial;
}
//////////////////////////FIM
function array_alinha()
{
    echo "<pre>";
    print_r($ARRAY1);
    echo "</pre>";
    $ARRAY1 = array_keys($ARRAY1);
    echo "<pre>";
    print_r($ARRAY1);
    echo "</pre>";
}

function date_time($str)
{
    $timestamp = strtotime($str);
    return $strcount = date('d/m/Y', $timestamp);
}

function fnDataSql($str)
{
    $data = str_replace("/", "-", $str);
    $tp = strtotime($data);
    if ($tp === false) {
        $strcount = FALSE;
    } else {
        $strcount = date('Y-m-d', $tp);
    }
    return $strcount;
}
function fnmesanosql($str)
{
    $data = str_replace("/", "-", $str);
    $tp = strtotime($data);
    if ($tp === false) {
        $strcount = FALSE;
    } else {
        $strcount = date('Y-m', $tp);
    }
    return $strcount;
}
function fnDataSqlNull($datastr = FALSE)
{
    if ($datastr != '') {
        $data = str_replace("/", "-", $datastr);
        $strcount = "'" . date('Y-m-d', strtotime($data)) . "'";
    } else {
        $strcount = 'NULL';
    }

    return $strcount;
}
function fnDateSql($datastr = FALSE)
{
    if ($datastr != '') {
        $data = str_replace("/", "-", $datastr);
        $strcount = "'" . date('Y-m-d H:i:s', strtotime($data)) . "'";
    } else {
        $strcount = 'NULL';
    }

    return $strcount;
}
function fnDateRetornonull($str)
{

    if ($str != '') {
        $data = str_replace("/", "-", $str);
        $datre = strtotime($data);
        $dateretorno = "'" . date('Y-m-d', $datre) . "'";
    } else {
        $dateretorno = 'NULL';
    }
    return $dateretorno;
}
function fnDataTimeSql($str)
{

    $data = str_replace("/", "-", $str);
    $strcount = date('Y-m-d h:i:s', strtotime($data));
    return $strcount;
}
function fnDateRetorno($str)
{

    if ($str <> "") {
        $data = str_replace("-", "/", $str);
        $datre = strtotime($data);
        $dateretorno = date('d/m/Y', $datre);
    } elseif ($str == '31/12/1969') {
        $dateretorno = '';
    } else {
        $dateretorno = '';
    }
    return $dateretorno;
}

function fnValor($Num, $Dec)
{
    if (empty($Num) || is_null($Num)) {
        $Numero = 0;
    } else {
        $Numero = $Num;
    }
    /*
   $valor1=number_format($Numero, $Dec, ',', '.'); // retorna R$100.000,50
    return $valor1; //retorna o valor formatado para gravar no banco 
   * 
   */
    if ($Dec == '0') {
        //$valor = bcmul($Num, '100', $Dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
        //$valor = bcdiv($valor, '100', $Dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
        // $valor=number_format ($Numero,$Dec,",",".");
        $valor = bcmul($Numero, '100', $Dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
        $valor = bcdiv($valor, '100', $Dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
        $valor = number_format($valor, $Dec, ",", ".");
    } else {
        $valor = bcmul($Numero, '100', $Dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
        $valor = bcdiv($valor, '100', $Dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
        $valor = number_format($valor, $Dec, ",", ".");
    }
    return  $valor;
}

function fnValorSql($get_valor)
{
    if ($get_valor == '') {
        $valor = 0;
    } else {


        $source = array('.', ',');
        $replace = array('', '.');
        $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
    }

    return $valor; //retorna o valor formatado para gravar no banco
}

function fnValorSQLEXtrato($Num, $Dec)
{
    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $Num);
    $valor = bcmul($valor, '100', $Dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
    $valor = bcdiv($valor, '100', $Dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)

    //echo $valor; //retorna o valor formatado para apresentação em tela  
    return $valor;
}
/*
function fnValorSql($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}		
  $valor = str_replace(",", ".", $Numero); 
  $valor = number_format ($valor,2,".",",");
  $valor = str_replace(",", "", $valor); 
  
  return $valor; //retorna o valor formatado para gravar no banco 
}
*/
function fnExecSql($conn, $SQLCOMMAND, $retornoquery, $retornoerro)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {

        if ($retornoquery = 'true') {


            $rwrotorno = mysqli_query($conn, $SQLCOMMAND);
            $rwarray = mysqli_fetch_assoc($rwrotorno);

            //msg
            $insert = "insert into log_sis (DAT_HORA,COD_EMPRESA,USUARIO,URL,MSG)
                        value('" . date('Y-m-d H:i:s') . "',
                               '0',
                               '0',
                               '0',
                               'Comando sql valido')";

            mysqli_query($conn, $insert);

            return $rwarray;
        } else {
            mysqli_query($conn, $SQLCOMMAND);
            $insert = "insert into log_sis (DAT_HORA,COD_EMPRESA,USUARIO,URL,MSG)
                        value('" . date('Y-m-d H:i:s') . "',
                               '0',
                               '0',
                               '0',
                               'Comando sql valido')";
            mysqli_query($conn, $insert);
        }
        //retorna msg do comando
        if ($retornoerro = 'true') {
            $ok = 'Comando SQL VALIDADO';
            echo $ok;
            return $ok;
        }
        //////////////////////////////////////////////////////////////////////////////////////    
    } catch (mysqli_sql_exception $e) {

        $insert = "insert into log_sis (DAT_HORA,COD_EMPRESA,USUARIO,URL,MSG)
                        value('" . date('Y-m-d H:i:s') . "',
                               '0',
                               '0',
                               '0',
                               '$e')";
        mysqli_query($conn, $insert);

        if ($retornoerro = 'true') {
            echo $e;
            return $e;
        }
    }
}
function fnMostraForm()
{
    foreach ($_REQUEST as $nome_campo => $valor_campo) {
        //Exibi o campo e o valor contido
        if (is_array($valor_campo)) {
            echo $nome_campo . " => ";
            print_r($valor_campo);
            echo "<br />";
        } else {
            echo $nome_campo . " => " . $valor_campo . "<br />";
        }
    }
}

function fnCorrigeTelefone($telefone)
{
    $telLimpo = preg_replace("/[^0-9]/", "", $telefone);
    $telefoneNovo = $telLimpo[0];
    if ($telefoneNovo == "0") {
        $telefoneNovo = substr($telLimpo, 1);
    } else {
        $telefoneNovo = $telLimpo;
    }

    echo $telefoneNovo;
}

function fnConsole($var)
{
    $var = preg_replace('/\s+/', ' ', $var);
    echo '
    <script>
      console.log("_' . $var . '_");
    </script>
  ';
}

function fnEscreve($Texto)
{
    if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
        echo $escreveTexto = "<h1>_" . $Texto . "_</h1>";
        return $escreveTexto;
    }
}
function fnEscreve2($Texto)
{
    echo $escreveTexto = "<h1>_" . $Texto . "_</h1>";
    return $escreveTexto;
}
function fnEscreveArray($Texto)
{
    if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
        echo "<pre>";
        return print_r($Texto);
        echo "<pre>";
    }
}
function fnSessionSegura()
{
    //criar aqui a verificação de autenticação
}
function fnCompletaDoc($cpfcnpj, $tipo)
{
    $tipo = strtoupper($tipo);
    if ($tipo == 'F') {
        $retun = str_pad($cpfcnpj, 11, '0', STR_PAD_LEFT); // Resultado: 00009   
        return $retun;
    } elseif ($tipo == 'J') {
        $retun = str_pad($cpfcnpj, 14, '0', STR_PAD_LEFT); // Resultado: 00009   
        return $retun;
    } else {
        return $cpfcnpj;
    }
}

function recursive_array_search($needle, $haystack)
{
    if (!empty($haystack)) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value or (is_array($value) && recursive_array_search($needle, $value) !== false)) {
                return $current_key;
            }
        }
    }
    return false;
}
function gravapos()
{
    foreach ($_REQUEST as $nome_campo => $valor_campo) {
        //Exibi o campo e o valor contido
        if (is_array($valor_campo)) {
            $return[] = array($nome_campo => $valor_campo);
        } else {
            $return[] = array($nome_campo => $valor_campo);
        }
    }

    return $return;
}
function fnMemInicial($conn, $opcao, $user, $DADOSPOST)
{
    $datahora = date("Y-m-d H:i:s");
    $mod = (@$_GET['mod'] <> "" ? fnDecode(@$_GET['mod']) : "0");
    $cod_empresa_page = (@$_GET['id'] <> "" ? fnDecode(@$_GET['id']) : "0");
    $cod_user = @$_SESSION["SYS_COD_USUARIO"];

    $request = json_encode($_REQUEST);
    $post = json_encode($_POST);
    $get = json_encode($_GET);
    $opcao_form = @$_POST["opcao"];
    $ip = getIPAddress();

    // $finaltime1=(microtime(TRUE) - $time);
    if ($opcao == "true") {


        $mem_usage = memory_get_usage(true);

        $desc_mem = "";
        if ($mem_usage < 1024) {
            $desc_mem = $mem_usage . " bytes";
        } elseif ($mem_usage < 1048576) {
            $desc_mem = round($mem_usage / 1024, 2) . " kilobytes";
        } else {
            $desc_mem = round($mem_usage / 1048576, 2) . " megabytes";
        }

        $logqueryinsert = "insert into log_men (MEM_INICIAL,COD_PAGINA,PAGINA,DATA_HORA,COD_USUARIO,USUARIO,COD_EMPRESA,COD_EMPRESA_PAGE,REQUEST,`GET`,POST,OPCAO_FORM,IP,NAVEGADOR)"
            . "values ('" . $desc_mem . "','" . $mod . "','" . @$_GET['mod'] . "','" . $datahora . "','0" . $cod_user . "','" . $user . "','" . $_SESSION["SYS_COD_EMPRESA"] . "','" . $cod_empresa_page . "','" . $request . "','" . $get . "','" . $post . "','" . $opcao_form . "','" . $ip . "','" . @$_SERVER['HTTP_USER_AGENT'] . "');";
        mysqli_query($conn, $logqueryinsert);

        return $logqueryinsert;
    } elseif ($opcao = 'false') {


        // $finaltime1=(microtime(TRUE) - $time);

        $mem_usage = memory_get_usage(true);
        $desc_mem_f = "";
        if ($mem_usage < 1024) {
            $desc_mem_f = $mem_usage . " bytes";
        } elseif ($mem_usage < 1048576) {
            $desc_mem_f = round($mem_usage / 1024, 2) . " kilobytes";
        } else {
            $desc_mem_f = round($mem_usage / 1048576, 2) . " megabytes";
        }

        $mem_usage = memory_get_peak_usage(true);
        $desc_mem_p = "";
        if ($mem_usage < 1024) {
            $desc_mem_p = $mem_usage . " bytes";
        } elseif ($mem_usage < 1048576) {
            $desc_mem_p = round($mem_usage / 1024, 2) . " kilobytes";
        } else {
            $desc_mem_p = round($mem_usage / 1048576, 2) . " megabytes";
        }

        $tempo_carregamento = microtime(TRUE);
        -$_SERVER['REQUEST_TIME'];

        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "',  MEM_FINAL='" . $desc_mem_f . "',ATIVO=1 WHERE  PAGINA='" . $_GET['mod'] . "' and ATIVO='0'";
        mysqli_query($conn, $SqlUpdate);

        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', MEM_PICO='" . $desc_mem_p . "',MEN_PICO=1 WHERE  PAGINA='" . $_GET['mod'] . "' and MEN_PICO='0'";
        mysqli_query($conn, $SqlUpdate);
    }
}

function getIPAddress()
{
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function fn_url()
{

    if (@$_SESSION["cod_url"] != 1) {

        $server = $_SERVER['SERVER_NAME'];
        $endereco = $_SERVER['REQUEST_URI'];
        $_SESSION["URL"] = $server . $endereco;
        $result = str_replace($server, " ", $_SESSION["URL"]);
        $_SESSION["URLLIMPO"] = $result;
    } else {
    }
}
function fnurl()
{


    $server = $_SERVER['SERVER_NAME'];
    $endereco = $_SERVER['REQUEST_URI'];
    $urlori = $server . $endereco;
    $result = str_replace($server, " ", $urlori);
    return $result;
}

function fnLogin()
{
    if (!isset($_SESSION["usuario"])) {

        require_once 'index.php';
    } else {
    }
}
function  fncomparaPerfil($perfilGeral, $cod_perfil, $cod_multemp, $cod_empresa, $cod_sistema)
{

    $retPER = 1;

    if (preg_match("~\b2\b~", $cod_multemp) == 0) {

        // if($cod_sistema == 4 || $cod_sistema == 18){
        if ($cod_sistema == 4) {

            $retEMP = preg_match("~\b$cod_empresa\b~", $cod_multemp);

            if ($retEMP > 0) {
                $retPER = preg_match("~\b$cod_perfil\b~", $perfilGeral);
            } else {
                $retPER = '0';
            }
        }
    }


    // return [$perfilGeral,$cod_perfil,$cod_multemp,$cod_empresa,$cod_sistema];
    return $retPER;
}
function fnDataFull($str)
{
    if (($timestamp = strtotime($str)) === false) {
        $date = '';
        return $date;
    } else {
        return date('d/m/Y H:i:s', $timestamp);
    }
}


function fnDataShort($str)
{
    if (($timestamp = strtotime($str)) === false) {
        $date = '';
        return $date;
    } else {
        return date('d/m/Y', $timestamp);
    }
}

function fnFormatDate($data)
{
    if (($timestamp = strtotime($data)) === false) {
        $date = '';
        return $date;
    } else {
        return date('d/m/Y', $timestamp);
    }
}
function fnFormatDateTime($data)
{
    if (($timestamp = strtotime($data)) === false) {

        $date = '';
        return $date;
    } else {
        return date('d/m/Y H:i:s', $timestamp);
    }
}
function fnCalculaporcento($vlinicial, $vlfinal)
{
    $vl = ($vlinicial / $vlfinal) * 100;
    echo  number_format($vl, 2);

    return $vl;
}

function fnupload($nom_cliente, $nomecampo)
{
    $target_dir = "/srv/www/htdocs/media/clientes/$nom_cliente/";

    $uploadfile = $target_dir . $_FILES[$nomecampo]['name'];

    $arquivo = array(
        'CAMINHO_TMP' => $uploadfile,
        'CONADM' => $connAdm->connAdm()
    );

    fnScan($arquivo);

    $imageFileType = pathinfo($uploadfile, PATHINFO_EXTENSION);


    $upload = 1;
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $upload = 0;
    }

    if ($upload == 0) {
        echo "Formato do arquivo nao permitido!";
    } else {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
        }
        if (move_uploaded_file($_FILES[$nomecampo]['tmp_name'], $uploadfile)) {
            echo "<img width='200px' src='upload/" . $uploadfile . "' class='preview'>";
        } else {
            echo "Houve um problema no upload do arquivo.";
        }
    }
}


function fnDebug($param)
{
    if ($_SESSION['SYS_COD_EMPRESA'] == 2 && $_SESSION['SYS_COD_USUARIO'] == 127937) {
        if ($param == 'true') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL & ~E_NOTICE);
        } elseif ($param == 'false') {
        }
    }
}
function fnDateDif($datainicial, $datafinal)
{
    if (count(explode("/", $datainicial)) > 1) {
        $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
        if ($datafinal != null && preg_match($format, $datainicial, $partes)) {
            $datainicial = $partes[3] . '-' . $partes[2] . '-' . $partes[1];
        }

        if ($datafinal != null && preg_match($format, $datafinal, $partes)) {
            $datafinal = $partes[3] . '-' . $partes[2] . '-' . $partes[1];
        }
    }


    // Define os valores a serem usados

    // Usa a função strtotime() e pega o timestamp das duas datas:
    $time_inicial = strtotime($datainicial);
    $time_final = strtotime($datafinal);
    // Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos
    // Calcula a diferença de dias
    $dias = (int)floor($diferenca / (60 * 60 * 24)); // 225 dias
    // Exibe uma mensagem de resultado:
    return $dias;
    // A diferença entre as datas 23/03/2009 e 04/11/2009 é de 225 dias
}

function fnTestesql($conn, $sql)
{
    if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($conn, $sql);
            $ok = 'Comando SQL VALIDADO';
            echo $ok;
            return $ok;
        } catch (mysqli_sql_exception $e) {
            echo $e;
            return $e;
        }
    } else {
        mysqli_query($conn, $sql);
    }
}
function fnRel($conn, $sql)
{

    $sqlcomand = mysqli_query($conn, $sql);
    $dados1 .= '<table border="1" width="300" height="100" align="center">';

    $dados3 .= '<tr>';
    while ($namecampos = mysqli_fetch_field($sqlcomand)) {

        $dados3 .= '<td>' . $namecampos->name . '</td>';
    }

    while ($sqldadosr = mysqli_fetch_assoc($sqlcomand)) {

        $dados4 .= '<tr>';
        foreach ($sqldadosr as  $campo => $fils) {
            $dados4 .= '<td><pre>' . htmlentities($fils) . '</pre></td>';
        }
        $dados4 .= '</tr>';
    }

    $dados3 .= '</tr>';
    $dados2 .= '</table>';

    echo $dados1;
    echo $dados3;
    echo (string)$dados4;
    echo $dados2;
    return $dados1;
    return $dados3;
    return $dados4;
    return $dados2;
}

function fnRelCliente($conn, $sql)
{

    $sqlcomand = mysqli_query($conn, $sql);
    $dados1 .= '<table class="table table-bordered table-hover">';
    $countLinha = 1;


    $dados3 .= '<tr>';

    while ($namecampos = mysqli_fetch_field($sqlcomand)) {

        $dados3 .= '<td>' . $namecampos->name . '</td>';
    }

    while ($sqldadosr = mysqli_fetch_assoc($sqlcomand)) {

        $dados4 .= '<tr>';
        foreach ($sqldadosr as  $campo => $fils) {
            //$dados4.= '<td> '.$countLinha.' - '.htmlentities($fils).'</td>';
            $dados4 .= '<td> ' . htmlentities($fils) . '</td>';
        }
        $dados4 .= '</tr>';
        $countLinha++;
    }

    $dados3 .= '</tr>';
    $dados2 .= '</table>';

    echo $dados1;
    echo $dados3;
    echo (string)$dados4;
    echo $dados2;
    return $dados1;
    return $dados3;
    return $dados4;
    return $dados2;
}
function fnformatCnpjCpf($value)
{
    $cnpj_cpf = preg_replace("/\D/", '', $value);

    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    }

    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}
function fnconsultaBase($conn, $CPF, $mepresa, $login, $conn2)
{
    $sql = "SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where NUM_CGCECPF='" . $CPF . "' and Cod_empresa=" . $mepresa;
    $row1 = mysqli_fetch_assoc(mysqli_query(connTemp($conn, ''), $sql));

    if ($row1['contador'] == 1) {
        $arraydadosBase = array();
        array_push($arraydadosBase, array(
            'contador' => $row1['contador'],
            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => ''

        ));
        return $arraydadosBase;
    } else {

        //consultar na base local primeiro
        $sqlifaro = "select count(CPF) as TEM,log_cpf.* from log_cpf where CPF = '" . $CPF . "'";
        $rowifaro = mysqli_fetch_assoc(mysqli_query($conn2, $sqlifaro));
        if ($rowifaro['TEM'] != 0) {
            $NOME = $rowifaro['NOME'];
            $CPF = $rowifaro['CPF'];
            $sexo = $rowifaro['SEXO'];
            $datanascimento = $rowifaro['DT_NASCIMENTO'];
        } else {


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://ws.ifaro.com.br/WSDados.svc?wsdl=",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                                              xmlns:tem=\"http://tempuri.org/\" >\r\n   
                                              <soapenv:Header/>\r\n   <soapenv:Body>\r\n     
                                              <tem:ConsultaPessoaSimplificado>\r\n         
                                              <tem:cpf>" . $CPF . "</tem:cpf>\r\n         
                                              <tem:login>TUFSS0E=</tem:login>\r\n         
                                              <tem:senha>c21hZWJSQXExNw==</tem:senha>\r\n     
                                              </tem:ConsultaPessoaSimplificado>\r\n   
                                              </soapenv:Body>\r\n
                                              </soapenv:Envelope>",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: text/xml",
                    "postman-token: fca2049c-8e80-9cd1-a290-bed88bcf2c4e",
                    "soapaction: http://tempuri.org/IWSDados/ConsultaPessoaSimplificado"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                //  $response;
                $doc = new DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($response);
                libxml_clear_errors();
                $xml = $doc->saveXML($doc->documentElement);
                $xml = simplexml_load_string($xml);
                $NOME = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->nome;
                $CPF = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->cpf;
                $sexor = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->sexo;
                $datanascimento = $xml->body->envelope->body->consultapessoasimplificadoresponse->consultapessoasimplificadoresult->datanascimento;

                $sql1 = "insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,SEXO,DT_NASCIMENTO) value
                        ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $CPF . "','" . $NOME . "','" . $mepresa . "','" . $login . "','0','0','" . $sexor . "','" . $datanascimento . "')";
                mysqli_query($conn2, $sql1);
            }

            // echo $sql1;
            // return $sql1;



        }
        $arraycpf = array();
        array_push($arraycpf, array("nome" => $NOME, "cpf" => $CPF, 'sexo' => $sexor, 'datanascimento' => $datanascimento));
        return $arraycpf;
    }
}
require_once 'PHPMailer/class.phpmailer.php';
define('GUSER', 'fidelidade@markafidelizacao.com.br');    // <-- Insira aqui o seu GMail
define('GPWD', 'Mud@r2015');        // <-- Insira aqui a senha do seu GMail

function smtpmailer($para, $de, $de_nome, $assunto, $corpo)
{
    global $error;
    $mail = new PHPMailer();
    $mail->IsSMTP();        // Ativar SMTP
    $mail->SMTPDebug = 0;        // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
    $mail->SMTPAuth = true;        // Autenticação ativada
    $mail->SMTPSecure = 'ssl';    // SSL REQUERIDO pelo GMail
    $mail->Host = 'smtp.gmail.com';    // SMTP utilizado
    $mail->Port = 465;          // A porta 587 deverá estar aberta em seu servidor
    $mail->Username = GUSER;
    $mail->Password = GPWD;
    $mail->SetFrom($de, $de_nome);
    $mail->Subject = $assunto;
    $mail->Body = $corpo;
    $mail->AddAddress($para);
    if (!$mail->Send()) {
        $error = 'Mail error: ' . $mail->ErrorInfo;
        return false;
    } else {
        $error = 'Mensagem enviada!';
        return true;
    }
}
function fnAcentos($string)
{
    // matriz de entrada
    $what = array(
        'ä',
        'ã',
        'à',
        'á',
        'â',
        'ê',
        'ë',
        'è',
        'é',
        'ï',
        'ì',
        'í',
        'ö',
        'õ',
        'ò',
        'ó',
        'ô',
        'ü',
        'ù',
        'ú',
        'û',
        'ç',
        'ñ',
        'Ä',
        'Ã',
        'À',
        'Á',
        'Â',
        'Ê',
        'Ë',
        'È',
        'É',
        'Ï',
        'Ì',
        'Í',
        'Ö',
        'Õ',
        'Ò',
        'Ó',
        'Ô',
        'Ü',
        'Ù',
        'Ú',
        'Û',
        'Ç',
        'Ñ',
        '...'
    );

    // matriz de saída
    $by   = array(
        'a',
        'a',
        'a',
        'a',
        'a',
        'e',
        'e',
        'e',
        'e',
        'i',
        'i',
        'i',
        'o',
        'o',
        'o',
        'o',
        'o',
        'u',
        'u',
        'u',
        'u',
        'c',
        'n',
        'A',
        'A',
        'A',
        'A',
        'A',
        'E',
        'E',
        'E',
        'E',
        'I',
        'I',
        'I',
        'O',
        'O',
        'O',
        'O',
        'O',
        'U',
        'U',
        'U',
        'U',
        'C',
        'N',
        ''
    );

    //replace
    $string = str_replace($what, $by, $string);
    $string = utf8_decode(str_replace($what, $by, utf8_encode($string)));
    $string = utf8_encode(str_replace($what, $by, utf8_decode($string)));

    //remove caracteres estranhos
    $string = preg_replace('/[^\p{L}\p{N}\s",.<>;:?!\/|[\]{}()\-_=+@#$%*]/u', ' ', $string);

    // devolver a string
    return $string;
}

function fnCHRHTML($string)
{

    $what = array(
        'ä',
        'ã',
        'à',
        'á',
        'â',
        'ê',
        'ë',
        'è',
        'é',
        'ï',
        'ì',
        'í',
        'ö',
        'õ',
        'ò',
        'ó',
        'ô',
        'ü',
        'ù',
        'ú',
        'û',
        'À',
        'Á',
        'É',
        'Í',
        'Ó',
        'Ú',
        'ñ',
        'Ñ',
        'ç',
        'Ç',
        'Õ',
        'ß',
        'æ',
        'Æ',
        'ø',
        'Ø',
        '«',
        '»',
        '©',
        '¶',
        'ª',
        '°',
        '®',
        '@',
        'Ô',
        'Ì',
        'Ã',
        '•',
        '(',
        ')',
        '{',
        '}',
        '%',
        '$',
        '²',
        '³',
        '-',
        ',',
        '?',
        '!',
        '[',
        ']',
        '=',
        '&nbsp;',
        '<br>',
        '"',
        '|',
        '&shy'
    );
    // matriz de saída
    $by   = array(
        '&#228;',
        '&#227;',
        '&#224;',
        '&#225;',
        '&#226;',
        '&#234;',
        '&#235;',
        '&#232;',
        '&#233;',
        '&#239;',
        '&#236;',
        '&#237;',
        '&#246;',
        '&#245;',
        '&#242;',
        '&#243;',
        '&#244;',
        '&#252;',
        '&#249;',
        '&#250;',
        '&#251;',
        '&#192;',
        '&#193;',
        '&#201;',
        '&#205;',
        '&#211;',
        '&#218;',
        '&#241;',
        '&#209;',
        '&#231;',
        '&#199;',
        '&#213;',
        '&#223;',
        '&#230;',
        '&#198;',
        '&#248;',
        '&#216;',
        '&#171;',
        '&#187;',
        '&#169;',
        '&#182;',
        '&#170;',
        '&#176;',
        '&#174;',
        '&#64;',
        '&#212;',
        '&#204;',
        '&#195;',
        '&#149;',
        '&#40;',
        '&#41;',
        '&#123;',
        '&#125;',
        '&#37;',
        '&#36;',
        '&#178;',
        '&#179;',
        '&#45;',
        '&#44;',
        '&#63;',
        '&#33;',
        '&#91;',
        '&#93;',
        '&#61;',
        '&#160;',
        '</br>',
        '&#34;',
        '&#124;',
        '&#173;'
    );

    // devolver a string
    return str_replace($what, $by, $string);
}


function  localiza($end)
{
    sleep(2.3);
    $end = rawurlencode($end);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $end,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token: 5503ff05-568f-9a79-64a7-c5b943634469",
            "key: AIzaSyDitmaoz34QFDyaT2IPgf5xsT62_6RSWFA"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response, TRUE);
    }
}
function fnControlaAcesso($codRelatorio, $paramAutRelatorio)
{


    //se sistema adm marka
    if ($_SESSION["SYS_COD_SISTEMA"] == 3) {
        $retornoAut = TRUE;
    } else {
        if (recursive_array_search($codRelatorio, $paramAutRelatorio) !== false) {
            $retornoAut = TRUE;
        } else {
            $retornoAut = FALSE;
        }
    }
    return $retornoAut;
}


function fnUnivend($arraydados)
{
    if ($arraydados['IN'] == 'S') {
        $rs = mysqli_query($arraydados['conntemp'], $arraydados['SQLIN']);
        $return = mysqli_fetch_all($rs, MYSQLI_ASSOC);
        $contador = count($return) - 1;
        for ($i = 0; $i < $contador; ++$i) {
            $nomecampo = $arraydados['nomecampo'];
            $campos .= $return[$i]["$nomecampo"] . ',';
        }

        return  $campos;
    } else {
        $rs = mysqli_query($arraydados['conntadm'], $arraydados['sql']);
        $return = mysqli_fetch_all($rs, MYSQLI_ASSOC);
        return  $return;
    }
}
function fnUniVENDEDOR($arraydados)
{
    if ($arraydados['IN'] == 'S') {
        $rs = mysqli_query($arraydados['conntemp'], $arraydados['SQLIN']);
        $return = mysqli_fetch_all($rs, MYSQLI_ASSOC);
        $contador = count($return) - 1;
        for ($i = 0; $i < $contador; ++$i) {
            $nomecampo = $arraydados['nomecampo'];
            $campos .= $return[$i]["$nomecampo"] . ',';
        }

        return  $campos;
    } else {
        $rs = mysqli_query($arraydados['conntadm'], $arraydados['sql']);
        $return = mysqli_fetch_all($rs, MYSQLI_ASSOC);
        return  $return;
    }
}
function limitarTexto($texto, $limite)
{
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
    $texto = mb_strimwidth($texto, 0, $limite, "...");
    return $texto;
}
function limitarTextoComunicacao($texto, $limite)
{
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
    $texto = mb_strimwidth($texto, 0, $limite, "");
    return $texto;
}


function fnLimitaTexto($texto, $limite)
{
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
    $texto = mb_strimwidth($texto, 0, $limite, "...");
    return $texto;
}
function fnvalidacpf($cpf = false)
{
    // Exemplo de CPF: 025.462.884-23

    /**
     * Multiplica dígitos vezes posições 
     *
     * @param string $digitos Os digitos desejados
     * @param int $posicoes A posição que vai iniciar a regressão
     * @param int $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int Os dígitos enviados concatenados com o último dígito
     *
     */
    if (! function_exists('calc_digitos_posicoes')) {
        function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0)
        {
            // Faz a soma dos dígitos com a posição
            // Ex. para 10 posições: 
            //   0    2    5    4    6    2    8    8   4
            // x10   x9   x8   x7   x6   x5   x4   x3  x2
            //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
            for ($i = 0; $i < strlen($digitos); $i++) {
                $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                $posicoes--;
            }

            // Captura o resto da divisão entre $soma_digitos dividido por 11
            // Ex.: 196 % 11 = 9
            $soma_digitos = $soma_digitos % 11;

            // Verifica se $soma_digitos é menor que 2
            if ($soma_digitos < 2) {
                // $soma_digitos agora será zero
                $soma_digitos = 0;
            } else {
                // Se for maior que 2, o resultado é 11 menos $soma_digitos
                // Ex.: 11 - 9 = 2
                // Nosso dígito procurado é 2
                $soma_digitos = 11 - $soma_digitos;
            }

            // Concatena mais um dígito aos primeiro nove dígitos
            // Ex.: 025462884 + 2 = 0254628842
            $cpf = $digitos . $soma_digitos;

            // Retorna
            return $cpf;
        }
    }

    // Verifica se o CPF foi enviado
    if (! $cpf) {
        return false;
    }

    // Remove tudo que não é número do CPF
    // Ex.: 025.462.884-23 = 02546288423
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se o CPF tem 11 caracteres
    // Ex.: 02546288423 = 11 números
    if (strlen($cpf) != 11) {
        return false;
    }

    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    $digitos = substr($cpf, 0, 9);

    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    $novo_cpf = calc_digitos_posicoes($digitos);

    // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    $novo_cpf = calc_digitos_posicoes($novo_cpf, 11);

    // Verifica se o novo CPF gerado é idêntico ao CPF enviado
    if ($novo_cpf === $cpf) {
        // CPF válido
        return true;
    } else {
        // CPF inválido
        return false;
    }
}
function calc_idade($data_nasc)
{

    $data_nasc = explode("/", $data_nasc);

    $data = date("d/m/Y");

    $data = explode("/", $data);

    $anos = $data[2] - $data_nasc[2];

    if ($data_nasc[1] > $data[1]) {

        return $anos - 1;
    }
    if ($data_nasc[1] == $data[1]) {

        if ($data_nasc[0] <= $data[0]) {

            return $anos;
        } else {

            return $anos - 1;
        }
    }
    if ($data_nasc[1] < $data[1]) {

        return $anos;
    }
}
function fnorderby_array()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function fnIniciais($string)
{
    $output = null;
    $token  = strtok($string, ' ');
    while ($token !== false) {
        $output .= $token[0];
        $token = strtok(' ');
    }
    return substr($output, 0, 2);
}

function sessions_PagSeguro()
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email=marcelo@markafidelizacao.com.br&token=19A6483822DC43B4A1B9AAB04DCFEFF0",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Accept-Encoding: gzip, deflate",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Content-Length: ",
            "Content-Type: application/x-www-form-urlencoded",
            "Host: ws.sandbox.pagseguro.uol.com.br",
            "Postman-Token: b6015d68-7d8a-42ad-8625-4481ac63e4a6,5ed45e94-1033-46df-bf37-0ae93737cc16",
            "User-Agent: PostmanRuntime/7.16.3",
            "cache-control: no-cache",
            "encoding: ISO-8859-1"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($response);
        libxml_clear_errors();
        $xml = $doc->saveXML($doc->documentElement);
        //$xml = simplexml_load_string($xml);
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $ID = json_decode(json_encode($xml), TRUE);
        $ID1 = $ID['body']['session']['id'];
        return $ID1;
    }
}

function fnAutMaster($codTpUsuario, $codEmpresa)
{
    //$arrayCompara = $paramAutRelatorio['MODULOS_AUT'];	
    //se sistema adm marka
    if (($codEmpresa == 3) || ($codEmpresa == 2)) {
        $retornoAut = '1';
    } else {
        if (($codTpUsuario == 16) || ($codTpUsuario == 9)) {
            $retornoAut = '1';
        } else {
            $retornoAut = '0';
        }
    }
    return $retornoAut;
}


function fnAcessaModulo($codRelatorio, $paramAutRelatorio)
{
    $arrayCompara = $paramAutRelatorio['MODULOS_AUT'];
    //se sistema adm marka
    if ($paramAutRelatorio['COD_SISTEMA'] == 3) {
        $retornoAut = true;
    } else {
        if (recursive_array_search($codRelatorio, $arrayCompara) !== false) {
            $retornoAut = true;
        } else {
            $retornoAut = false;
        }
    }
    return $retornoAut;
}

function fnConsultaMULT($arraybusca)
{
    $sqlbusca = "SELECT  TABLE_SCHEMA,COLUMN_NAME FROM information_schema.`COLUMNS` 
    WHERE  TABLE_SCHEMA='db_prefeitura' AND TABLE_NAME ='CLIENTES'";
    $result = mysqli_query($arraybusca['conn'], $sqlbusca);
    while ($dados = mysqli_fetch_assoc($result)) {
        $sql_buscadados = "SELECT CL.COD_CLIENTE 
                             FROM CLIENTES CL
                             WHERE CL.COD_EMPRESA = $arraybusca[cod_empresa]
                             AND CL." . $dados['COLUMN_NAME'] . ' ' . $arraybusca['param_busca'] . " '" . $arraybusca['TextoConsulta'] . "'
                             ";
        // fnEscreve($sql_buscadados);
        $dadosconsulta = mysqli_query($arraybusca['conn'], $sql_buscadados);
        if (mysqli_num_rows($dadosconsulta) >= 1) {
            while ($row_return = mysqli_fetch_assoc($dadosconsulta)) {

                $arraycod_cliente .= $row_return['COD_CLIENTE'] . ',';
            }
        }
    }
    $arraycod_cliente = rtrim($arraycod_cliente, ',');

    $buscaoficial = "SELECT CL.*,
                       $arraybusca[colunasAdicionais]
                       (SELECT A.NOM_CLIENTE FROM CLIENTES A WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR
                       FROM CLIENTES CL
                       $arraybusca[joinFiltros]
                       WHERE CL.COD_EMPRESA = $arraybusca[cod_empresa]
                       AND CL.COD_CLIENTE IN ($arraycod_cliente) 
                       GROUP BY CL.COD_CLIENTE
                       $arraybusca[limite]";

    if ($arraybusca['tipo'] == 'consulta') {

        $rsrw = mysqli_query($arraybusca['conn'], $buscaoficial);
        while ($dadosrs = mysqli_fetch_assoc($rsrw)) {
            $array_return['DADOS'][] = $dadosrs;
        }
        return $array_return;
    } else if ($arraybusca['tipo'] == 'count') {

        $rsrw = mysqli_query($arraybusca['conn'], $buscaoficial);
        $countLinhas = mysqli_num_rows($rsrw);
        return $countLinhas;
    } else if ($arraybusca['tipo'] == 'export') {

        $buscaoficial = "SELECT CL.COD_CLIENTE AS COD_APOIADOR,
                         CL.NOM_CLIENTE AS NOM_APOIADOR,
                         CL.DAT_NASCIME AS NASCIMENTO,
                         CL.DAT_NASCIME AS IDADE,
                         CL.DES_EMAILUS AS EMAIL,
                         CL.DES_ENDEREC AS ENDERECO,
                         CL.NUM_ENDEREC AS NUMERO,
                         CL.DES_BAIRROC AS BAIRRO,
                         CL.NUM_CEPOZOF AS CEP,
                         CL.NOM_CIDADEC AS CIDADE,
                         CL.COD_ESTADOF AS ESTADO,
                         (SELECT A.NOM_CLIENTE FROM CLIENTES A WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR,       
                         CL.DAT_CADASTR,
                         $arraybusca[colunasAdicionais]
                         CL.NUM_CELULAR,
                         CL.NUM_TELEFON
                         FROM CLIENTES CL
                         $arraybusca[joinFiltros]
                         WHERE CL.COD_EMPRESA = $arraybusca[cod_empresa]
                         AND CL.COD_CLIENTE IN ($arraycod_cliente) 
                         GROUP BY CL.COD_CLIENTE
                         ";
        $rsrw = mysqli_query($arraybusca['conn'], $buscaoficial);
        return $rsrw;
    }
}

function fngravacvs($texto, $destino, $nome)
{
    $contadorarray = count($texto);

    $countinicial = 1;
    foreach ($texto as $dados) {

        $f = fopen($destino . $nome, "a+", 0);
        if ($countinicial < $contadorarray) {
            $sepador = "\n";
        } else {
            $sepador = '';
        }
        $linha = $dados . $sepador;
        fwrite($f, $linha, strlen($linha));
        fclose($f);
        $countinicial++;
    }
}

function procpalavras($frase, $connadm)
{
    $sqldados = "SELECT KEY_BANCOVAR from VARIAVEIS WHERE	
                                                        LOG_EMAIL='S' OR
                                                        LOG_SMS='S' OR
                                                        LOG_PUSH='S' OR
                                                        LOG_WHATSAPP='S'";
    $dadosrw = mysqli_query($connadm, $sqldados);
    while ($rs = mysqli_fetch_assoc($dadosrw)) {
        $dadosvari = trim($rs['KEY_BANCOVAR'], '<');
        $dadosvari = rtrim($dadosvari, '>');
        $palavrasbase[] = $dadosvari;
    };
    foreach ($palavrasbase as $key => $value) {
        $pos = strpos($frase, $value);
        if ($pos !== false) {
            $dadosconsulta .= '<' . $value . '>,';
        }
    }
    $dadosconsulta = rtrim($dadosconsulta, ',');
    return $dadosconsulta;
}
function procpalavrasV2($frase, $connadm, $COD_EMPRESA)
{
    $sqldados = "SELECT VD.DES_EXTERNO from variaveis OV
					INNER JOIN variaveis_dinamize VD ON VD.COD_BANCOVAR=OV.COD_BANCOVAR 
					WHERE	VD.COD_EMPRESA='$COD_EMPRESA' AND VD.DES_EXTERNO IS NOT null and VD.COD_BANCOVAR NOT IN ('21','3');";

    $dadosrw = mysqli_query($connadm, $sqldados);
    while ($rs = mysqli_fetch_assoc($dadosrw)) {
        $dadosvari = trim($rs['DES_EXTERNO'], '{{');
        $dadosvari = rtrim($dadosvari, '}}');
        $palavrasbase[] = $dadosvari;
    };
    foreach ($palavrasbase as $key => $value) {
        $pos = strpos($frase, $value);
        if ($pos !== false) {
            $dadosconsulta .= '{{' . $value . '}},';
        }
    }
    $dadosconsulta = rtrim($dadosconsulta, ',');
    return $dadosconsulta;
}



function fnQualidadeCampos($conn, $COD_EMPRESA)
{
    $arraysqlcampo =  '';
    $arraysql = '';

    $CAMPOSSQL = "select DISTINCT NOM_CAMPOOBG,KEY_CAMPOOBG,DES_CAMPOOBG,INTEGRA_CAMPOOBG.TIP_CAMPOOBG  from matriz_campo_integracao                         
                    inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
                    where matriz_campo_integracao.COD_EMPRESA=" . $COD_EMPRESA . "
                    and matriz_campo_integracao.TIP_CAMPOOBG ='CAD'
                 ";
    $CAMPOQUERY = mysqli_query($conn, $CAMPOSSQL);
    while ($CAMPOROW = mysqli_fetch_assoc($CAMPOQUERY)) {

        $arraysqlcampo .= $CAMPOROW['NOM_CAMPOOBG'] . ',';
        $arraysql .= $CAMPOROW['DES_CAMPOOBG'] . ',';
    }
    $arraysql = rtrim($arraysql, ',');
    $arraysqlcampo = rtrim($arraysqlcampo, ',');
    $arrraureturn = array(
        'NOM_CAMPOOBG' => $arraysqlcampo,
        'DES_CAMPOOBG' => $arraysql
    );
    return $arrraureturn;
}

function FnDebitos($arraydebitos, $consulta = false)
{
    //executar o ssql
    $sqlcomdebt = "SELECT                 pedido.TIP_LANCAMENTO 
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
                                   pedido.COD_EMPRESA =" . $arraydebitos['COD_EMPRESA'] . " AND
                                    PAG_CONFIRMACAO='S' and
                                    canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . "
                                    AND pedido.QTD_SALDO_ATUAL > 0  AND 
	                            pedido.DAT_VALIDADE IS NOT NULL and
                                    pedido.TIP_LANCAMENTO ='C' 
                            	GROUP BY  pedido.TIP_LANCAMENTO	            
                           ORDER BY pedido.TIP_LANCAMENTO desc ";
    $rwarraysql = mysqli_query($arraydebitos['CONNADM'], $sqlcomdebt);
    if ($rwarraysql->num_rows <= 0) {
        $DebSaldo = '0';
    } else {
        while ($rssaldo = mysqli_fetch_assoc($rwarraysql)) {
            //if($rssaldo['TIP_LANCAMENTO']=='D'){$DebSaldo=  $rssaldo['QTD_PRODUTO'];}
            //if ($rssaldo['TIP_LANCAMENTO']=='C') {$CredSaldo= $rssaldo['QTD_PRODUTO'];}  
            $DebSaldo = $rssaldo['QTD_SALDO_ATUAL'];
        }
    }
    // $saldorestante=bcsub($CredSaldo, $DebSaldo);
    $saldorestante = $DebSaldo;
    $saldoDiferenca = abs(bcsub($arraydebitos['quantidadeEmailenvio'], $CredSaldo));

    if ($consulta) {
        return array(
            'saldorestante' => $saldorestante,
            'qtd_envio' => $arraydebitos['quantidadeEmailenvio'],
            'saldo_apos_envio' => ($saldorestante - $arraydebitos['quantidadeEmailenvio']),
            'tem_saldo' => ($saldorestante >= $arraydebitos['quantidadeEmailenvio'])
        );
        exit;
    }

    //================================
    if ($arraydebitos['PERMITENEGATIVO'] == 'S') {
        //inserir debito
        $sqlinDebito = "INSERT INTO pedido_marka (COD_ORCAMENTO, 
                                                    COD_PRODUTO, 
                                                    QTD_PRODUTO, 
                                                    VAL_UNITARIO, 
                                                    COD_EMPRESA, 
                                                    COD_UNIVEND, 
                                                    ID_SESSION_PAGSEGURO, 
                                                    PAG_CONFIRMACAO, 
                                                    TIP_LANCAMENTO, 
                                                    COD_CAMPANHA,
                                                    LOG_TESTE,
                                                    DAT_CADASTR)
                                                    VALUES ('" . date('ymdHis') . "', 
                                                            '" . $arraydebitos['COD_CANALCOM'] . "', 
                                                            '" . $arraydebitos['quantidadeEmailenvio'] . "', 
                                                            '0.000000', 
                                                            '" . $arraydebitos['COD_EMPRESA'] . "', 
                                                            '1', 
                                                            'DEBITO',
                                                             'S', 
                                                             'D',
                                                             '" . $arraydebitos['COD_CAMPANHA'] . "',
                                                             '" . $arraydebitos['LOG_TESTE'] . "',
                                                             '" . $arraydebitos['DAT_CADASTR'] . "');";
        mysqli_query($arraydebitos['CONNADM'], $sqlinDebito);

        $DebSaldo = $DebSaldo + $arraydebitos['quantidadeEmailenvio'];
        $msg = 'Permitindo negativos!';
        $cod_msg = '3';
        $QTDUPDATE = $arraydebitos['quantidadeEmailenvio'];
        $QTDUPDATE1 = $arraydebitos['quantidadeEmailenvio'];
        unset($sqlinDebito);
        //dabitos dentro da validade
        $sql = "SELECT           pedido.TIP_LANCAMENTO 
                                  ,pedido.COD_VENDA
                                  ,pedido.COD_PRODUTO 
                                  ,emp.NOM_EMPRESA
                                  ,pedido.DAT_CADASTR 
                                  ,pedido.DAT_VALIDADE
                                  ,pedido.COD_ORCAMENTO
                                  ,canal.DES_CANALCOM
                                  ,canal.COD_CANALCOM
                                  ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                  ,pedido.QTD_SALDO_ATUAL
                                  ,pedido.VAL_UNITARIO
                                  ,pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                  ,if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                      FROM pedido_marka pedido 
                                       INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                       INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                       INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                       WHERE pedido.COD_ORCAMENTO > 0 AND 
                                              pedido.COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND
                                              PAG_CONFIRMACAO IN ('S') AND
                                              pedido.TIP_LANCAMENTO ='C' AND  
                                              pedido.QTD_SALDO_ATUAL > 0 AND
                                              canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . " AND
                                              pedido.DAT_VALIDADE IS not null
                                           GROUP BY pedido.COD_VENDA         
                                      ORDER BY  pedido.DAT_VALIDADE ASC ";
        $rwsql = mysqli_query($arraydebitos['CONNADM'], $sql);
        if ($rwsql->num_rows > 0) {
            while ($rssql = mysqli_fetch_assoc($rwsql)) {

                if ($QTDUPDATE >= $rssql['QTD_SALDO_ATUAL']) {
                    if ($QTDUPDATE1 == '1') {
                        $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                        mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    } else {
                        $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                        mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    }
                    $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                } else {
                    $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'" . $QTDUPDATE1 . "' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                    mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                }
                if ($QTDUPDATE1 <= '0') {
                    break;
                }
            }
        }
    } else {


        if ($saldorestante >= $arraydebitos['quantidadeEmailenvio']) {
            //$QTDUPDATE='0'; 
            // $msg='voce ainda possui saldo';
            //$cod_msg='4';
            if ($arraydebitos['CONFIRMACAO'] == 'S') {
                $alterac = 'Cobranca gerada e debitada';
                $cod_msg = '1';
                $QTDUPDATE = $arraydebitos['quantidadeEmailenvio'];
                $QTDUPDATE1 = $arraydebitos['quantidadeEmailenvio'];
                //inserir debito
                if ($arraydebitos['COD_CAMPANHA'] == '0') {
                    $MsgText = 'Debito Manual';
                } else {
                    $MsgText = 'DEBITO';
                }
                $sqlinDebito = "INSERT INTO pedido_marka (COD_ORCAMENTO, 
                                                       COD_PRODUTO, 
                                                       QTD_PRODUTO, 
                                                       VAL_UNITARIO, 
                                                       COD_EMPRESA, 
                                                       COD_UNIVEND, 
                                                       ID_SESSION_PAGSEGURO, 
                                                       PAG_CONFIRMACAO, 
                                                       TIP_LANCAMENTO, 
                                                       COD_CAMPANHA,
                                                       LOG_TESTE,
                                                       DAT_CADASTR)
                                                       VALUES ('" . date('ymdHis') . "', 
                                                               '" . $arraydebitos['COD_CANALCOM'] . "', 
                                                               '" . $arraydebitos['quantidadeEmailenvio'] . "', 
                                                               '0.000000', 
                                                               '" . $arraydebitos['COD_EMPRESA'] . "', 
                                                               '1', 
                                                               '$MsgText',
                                                                'S', 
                                                                'D',
                                                                '" . $arraydebitos['COD_CAMPANHA'] . "',
                                                                '" . $arraydebitos['LOG_TESTE'] . "',
                                                                '" . $arraydebitos['DAT_CADASTR'] . "');";
                mysqli_query($arraydebitos['CONNADM'], $sqlinDebito);
                // $DebSaldo=$DebSaldo+$arraydebitos['quantidadeEmailenvio']; 
                $saldoDiferenca = abs(bcsub($arraydebitos['quantidadeEmailenvio'], $CredSaldo));
                unset($sqlinDebito);
                //dabitos dentro da validade
                $sql = "SELECT           pedido.TIP_LANCAMENTO 
                                              ,pedido.COD_VENDA
                                              ,pedido.COD_PRODUTO 
                                              ,emp.NOM_EMPRESA
                                              ,pedido.DAT_CADASTR 
                                              ,pedido.DAT_VALIDADE
                                              ,pedido.COD_ORCAMENTO
                                              ,canal.DES_CANALCOM
                                              ,canal.COD_CANALCOM
                                              ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                              ,pedido.QTD_SALDO_ATUAL
                                              ,pedido.VAL_UNITARIO
                                              ,pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                              ,if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                                  FROM pedido_marka pedido 
                                                   INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                                   INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                                   INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                                   WHERE pedido.COD_ORCAMENTO > 0 AND 
                                                          pedido.COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND
                                                          PAG_CONFIRMACAO IN ('S') AND
                                                          pedido.TIP_LANCAMENTO ='C' AND 
                                                          pedido.QTD_SALDO_ATUAL > 0 AND
                                                          canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . " AND
                                                          pedido.DAT_VALIDADE IS not null
                                                       GROUP BY pedido.COD_VENDA         
                                                  ORDER BY  pedido.DAT_VALIDADE ASC ";
                $rwsql = mysqli_query($arraydebitos['CONNADM'], $sql);
                //verificar se existe vencimento
                if ($rwsql->num_rows > 0) {
                    while ($rssql = mysqli_fetch_assoc($rwsql)) {

                        if ($QTDUPDATE1 >= $rssql['QTD_SALDO_ATUAL']) {
                            if ($QTDUPDATE1 == '1') {
                                $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                                mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            } else {
                                $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                                mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            }
                            $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                        } else {
                            $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'" . $QTDUPDATE1 . "' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                            mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                        }
                        if ($QTDUPDATE1 <= '0') {
                            break;
                        }
                    }
                }
            } else {
                $alterac = 'Não foram gerados debitos';
                $cod_msg = '2';
            }
        } else {
            $QTDUPDATE = '0';
            $msg = 'Nao Possui mais saldo';
            $cod_msg = '5';
        }
    }
    //debitos dentro da 


    return array(
        'QTD_AFETADA' => $QTDUPDATE,
        'MSG' => $msg,
        'cod_msg' => $cod_msg,
        'MSG_ATERACAO' => $alterac,
        'cod_altera' => $cod_alterac,
        'DEBT' => $DebSaldo,
        'Cred' => $CredSaldo,
        'Diferenca' => $saldoDiferenca,
        'Saldorestante' => abs($saldorestante)
    );
}
function fnmasktelefone($number)
{
    $number = "(" . substr($number, 0, 2) . ") " . substr($number, 2, -4) . " - " . substr($number, -4);
    // primeiro substr pega apenas o DDD e coloca dentro do (), segundo subtr pega os números do 3º até faltar 4, insere o hifem, e o ultimo pega apenas o 4 ultimos digitos
    return $number;
}
function fnScan($arquivo)
{
    //testando o antivirus
    $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
    if (socket_connect($socket, '/var/run/clamav/clamd-socket')) {
        socket_send($socket, "PING", strlen(@$file) + 5, 0);
        socket_recv($socket, $PING, 20000, 0);
        socket_close($socket);
        if (rtrim(trim($PING)) == 'PONG') {
            chmod($arquivo['CAMINHO_TMP'], 00644);
            $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
            if (socket_connect($socket, '/var/run/clamav/clamd-socket')) {
                $result = "";
                socket_send($socket, "SCAN " . $arquivo['CAMINHO_TMP'], strlen($arquivo['CAMINHO_TMP']) + 5, 0);
                socket_recv($socket, $result, 20000, 0);
                $quebradelina = explode(':', $result);

                if (rtrim(trim($quebradelina['1'])) == 'OK') {
                    return array(
                        'RESULTADO' => 0,
                        'MSG' => 'N'
                    );
                } else {
                    return array(
                        'RESULTADO' => 1,
                        'MSG' => $quebradelina['1']
                    );
                    unlink($arquivo['CAMINHO_TMP']);
                }
            }
            socket_close($socket);
        }
    }
}

function remove_emoji($string)
{

    // Match Emoticons
    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clear_string = preg_replace($regex_emoticons, '', $string);

    // Match Miscellaneous Symbols and Pictographs
    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clear_string = preg_replace($regex_symbols, '', $clear_string);

    // Match Transport And Map Symbols
    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clear_string = preg_replace($regex_transport, '', $clear_string);

    // Match Miscellaneous Symbols
    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
    $clear_string = preg_replace($regex_misc, '', $clear_string);

    // Match Dingbats
    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

    return $clear_string;
}

function verifica_https()
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        return true;
    }
    if (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
        return true;
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ('https' == $_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        return true;
    }
    return false;
}

function fnPrevisaoSAC($esteira = false, $qrUser = array(), $qrSac = array())
{
    if (!$esteira) {
        return "";
    }

    // Verificar se "INICIO" existe e possui um valor válido antes de dividir
    if (isset($qrSac["INICIO"]) && !empty($qrSac["INICIO"]) && strpos($qrSac["INICIO"], " ") !== false) {
        list($data, $hora) = explode(" ", $qrSac["INICIO"]);
    } else {
        return "";  // Retorna vazio caso "INICIO" não seja válido
    }

    // Verificar datas inválidas
    if ($data == "1969-12-31" || $data == "0000-00-00" || $data == "") {
        return "";
    }

    // Verificar se as horas de desenvolvimento são maiores que zero
    if (@$qrUser["HOR_DEVDIAS"] <= 0 && @$qrUser["HOR_DEVFDS"] <= 0) {
        return "";
    }

    // Verificar a previsão
    if (@$qrSac["DES_PREVISAO"] <= 0 || @$qrSac["DES_PREVISAO"] == "") {
        return "";
    }

    $prev = @$qrSac["DES_PREVISAO"];
    $dh = @$qrSac["INICIO"];
    $dh_final = @$qrSac["INICIO"];
    $hr_util = @$qrUser["HOR_ENTRADA"];
    $hr_fds = @$qrUser["HOR_ENTRADA"];
    $qtd_hr_util = @$qrUser["HOR_DEVDIAS"];
    $qtd_hr_fds = @$qrUser["HOR_DEVFDS"];
    $c = 0;
    $dif = 0;

    while ($prev > 0) {
        $c++;
        $dif = 0;
        $w = date('w', strtotime($dh));
        $ano = date('Y', strtotime($dh));
        $feriado = fnFeriados($ano);

        // Ignorar Sábados
        if ($w == 6) {
            $dh = date('Y-m-d', strtotime("+1 days", strtotime($dh))) . " $hr";
            continue;
        }

        // Ignorar Feriados
        if (@$feriado[date('Y-m-d', strtotime($dh))] != "") {
            $dh = date('Y-m-d', strtotime("+1 days", strtotime($dh))) . " $hr";
            continue;
        }

        // Ajustar hora e quantidade de horas para domingos ou dias úteis
        if ($w == 0) {
            // Domingo
            $hr = $hr_fds;
            $qtd_hr = $qtd_hr_fds;
        } else {
            // Dia útil
            $hr = $hr_util;
            $qtd_hr = $qtd_hr_util;
        }

        if ($c == 1) {
            // Calcular diferença de horas no primeiro ciclo
            $dif = strtotime(date("Y-m-d " . $hora)) - strtotime(date("Y-m-d " . $hr));
            $dif = $dif / 60 / 60;
            if ($dif > 0) {
                $qtd_hr = $qtd_hr - $dif;
            }
        }

        $hr_sub = min($prev, $qtd_hr);
        $dh_final = date('Y-m-d H:i:s', strtotime("+" . ($hr_sub * 60) . " minute", strtotime($dh)));
        $dh = date('Y-m-d', strtotime("+1 days", strtotime($dh))) . " $hr";
        $prev = $prev - $hr_sub;
    }

    return $dh_final;
}
function fnFeriados($ano = null)
{
    if ($ano === null) {
        $ano = intval(date('Y'));
    }

    $pascoa     = easter_date($ano); // Limite entre 1970 a 2037 conforme http://www.php.net/manual/pt_BR/function.easter-date.php
    $dia_pascoa = date('j', $pascoa);
    $mes_pascoa = date('n', $pascoa);
    $ano_pascoa = date('Y', $pascoa);

    $feriados = array(
        // Datas Fixas dos feriados brasileiros
        date("Y-m-d", mktime(0, 0, 0, 1,  1,   $ano)) => 'Ano Novo', // Confraternização Universal - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 4,  21,  $ano)) => 'Tiradentes', // Tiradentes - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 5,  1,   $ano)) => 'Dia do Trabalhador', // Dia do Trabalhador - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 9,  7,   $ano)) => 'Independência do Brasil', // Dia da Independência - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 10,  12, $ano)) => 'Nossa Senhora Aparecida', // N. S. Aparecida - Lei nº 6802, de 30/06/80
        date("Y-m-d", mktime(0, 0, 0, 11,  2,  $ano)) => 'Finados', // Todos os santos - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 11, 15,  $ano)) => 'Proclamação da República', // Proclamação da republica - Lei nº 662, de 06/04/49
        date("Y-m-d", mktime(0, 0, 0, 12, 25,  $ano)) => 'Natal', // Natal - Lei nº 662, de 06/04/49

        // Essas datas dependem da páscoa
        date("Y-m-d", mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa)) => 'Segunda de Carnaval', //2ºferia Carnaval
        date("Y-m-d", mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa)) => 'Terça de Carnaval', //3ºferia Carnaval	
        date("Y-m-d", mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2,  $ano_pascoa)) => 'Sexta-feira da Paixão', //6ºfeira Santa  
        date("Y-m-d", mktime(0, 0, 0, $mes_pascoa, $dia_pascoa,  $ano_pascoa)) => 'Páscoa', //Pascoa
        date("Y-m-d", mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa)) => 'Corpus Christi', //Corpus Cirist
    );

    asort($feriados);

    return $feriados;
}

//echo "<!--";
//echo fnMascaraCampo("1197988615");
//echo "-->";
function fnMascaraCampo($str = "")
{
    $str = trim($str);

    $mask = preg_replace("/[0-9]/is", "0", str_ireplace(" ", "", $str));

    if ($mask == "000.000.000-00") {
        //CPF
        $chr = 6;
        $str = str_ireplace(" ", "", $str);
        $str = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($str, $chr, strlen($str)));
    } elseif ($mask == "00.000.000/0000-00") {
        //CNPJ
        $chr = 6;
        $str = str_ireplace(" ", "", $str);
        $str = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($str, $chr, strlen($str)));
    } elseif (
        $mask == "(00)0000-0000" || $mask == "(00)00000-0000" || $mask == "(00)0000-00000"
        || $mask == "0000-0000" || $mask == "00000-0000" || $mask == "0000-00000"
    ) {
        //TELEFONE
        $str = str_ireplace(" ", "", $str);
        $str = str_ireplace(")", ") ", $str);
        $e = explode("-", $str);
        $e[0] = preg_replace("/[0-9]/is", "*", @$e[0]);
        $str = implode("-", $e);
    } elseif ($mask == "00/00/0000") {
        //DATA BR
        $dt = explode("/", $str);
        $dt[1] = preg_replace("/[0-9]/is", "*", @$dt[1]);
        $dt[2] = preg_replace("/[0-9]/is", "*", @$dt[2]);
        $str = implode("/", $dt);
    } elseif ($mask == "0000-00-00") {
        //DATA EN
        $dt = explode("-", $str);
        $dt[0] = preg_replace("/[0-9]/is", "*", @$dt[0]);
        $str = implode("-", $dt);
    } elseif ($mask == "00000-000") {
        //CEP
        $e = explode("-", $str);
        $chr = 3;
        $e[0] = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($e[0], $chr, strlen($e[0])));
        $e[1] = preg_replace("/[0-9]/is", "*", $e[1]);
        $str = implode("-", $e);
    } elseif (filter_var(str_ireplace(" ", "", $str), FILTER_VALIDATE_EMAIL)) {
        //EMAIL
        $e = explode("@", $str);
        $chr = round(strlen($e[0]) / 2);
        $e[0] = substr($str, 0, $chr) . preg_replace("/[a-zA-Z0-9]/is", "*", substr($e[0], $chr, strlen($e[0])));
        $str = implode("@", $e);
    } elseif (is_numeric(str_ireplace(" ", "", $str))) {
        //NUMERO
        $str = str_ireplace(" ", "", $str);
        //$chr = round(strlen($str)/2);
        $chr = strlen($str) - 4;
        if ($chr <= 1) {
            $chr = round(strlen($str) / 2);
        }
        $part1 = substr($str, 0, $chr);
        $part2 = substr($str, $chr, strlen($str));

        $part1 = preg_replace("/[a-zA-Z0-9]/is", "*", $part1);
        $str = $part1 . $part2;
        //$str = substr($str,0,$chr).preg_replace("/[0-9]/is","*",substr($str,$chr,strlen($str)));

    } else {
        //GENÉRICO
        $e = explode(" ", $str);
        foreach ($e as $k => $v) {
            if (
                $k == 0 || strtolower($v) == "do" || strtolower($v) == "da" || strtolower($v) == "dos" || strtolower($v) == "das"
                || strtolower($v) == "de"
            ) {
                continue;
            }
            $e[$k] = substr($v, 0, 1) . ".";
        }
        $str = implode(" ", $e);
    }
    return $str;
}
function Utf8_ansi($valor = '')
{

    $utf8_ansi2 = array(
        "\u00c0" => "À",
        "\u00c1" => "Á",
        "\u00c2" => "Â",
        "\u00c3" => "Ã",
        "\u00c4" => "Ä",
        "\u00c5" => "Å",
        "\u00c6" => "Æ",
        "\u00c7" => "Ç",
        "\u00c8" => "È",
        "\u00c9" => "É",
        "\u00ca" => "Ê",
        "\u00cb" => "Ë",
        "\u00cc" => "Ì",
        "\u00cd" => "Í",
        "\u00ce" => "Î",
        "\u00cf" => "Ï",
        "\u00d1" => "Ñ",
        "\u00d2" => "Ò",
        "\u00d3" => "Ó",
        "\u00d4" => "Ô",
        "\u00d5" => "Õ",
        "\u00d6" => "Ö",
        "\u00d8" => "Ø",
        "\u00d9" => "Ù",
        "\u00da" => "Ú",
        "\u00db" => "Û",
        "\u00dc" => "Ü",
        "\u00dd" => "Ý",
        "\u00df" => "ß",
        "\u00e0" => "à",
        "\u00e1" => "á",
        "\u00e2" => "â",
        "\u00e3" => "ã",
        "\u00e4" => "ä",
        "\u00e5" => "å",
        "\u00e6" => "æ",
        "\u00e7" => "ç",
        "\u00e8" => "è",
        "\u00e9" => "é",
        "\u00ea" => "ê",
        "\u00eb" => "ë",
        "\u00ec" => "ì",
        "\u00ed" => "í",
        "\u00ee" => "î",
        "\u00ef" => "ï",
        "\u00f0" => "ð",
        "\u00f1" => "ñ",
        "\u00f2" => "ò",
        "\u00f3" => "ó",
        "\u00f4" => "ô",
        "\u00f5" => "õ",
        "\u00f6" => "ö",
        "\u00f8" => "ø",
        "\u00f9" => "ù",
        "\u00fa" => "ú",
        "\u00fb" => "û",
        "\u00fc" => "ü",
        "\u00fd" => "ý",
        "\u00ff" => "ÿ"
    );

    return strtr($valor, $utf8_ansi2);
}

function Log_error_comand($connadm, $conntemp = false, $cod_empresa, $url, $MODULO, $COD_MODULO, $SQLCOMANDO, $usuario = false)
{
    require_once('_system/PHPMailer/class.phpmailer.php');
    require_once('externo/email/envio_sac.php');
    $inscommand = "INSERT INTO err_rotinabunker (COD_EMPRESA, URL, MODULO, COD_MODULO, COMMD_SQL) VALUES 
               ('$cod_empresa', '" . addslashes($url) . "', '" . addslashes($MODULO) . "', '$COD_MODULO', '" . addslashes($SQLCOMANDO) . "');";
    mysqli_query($connadm, $inscommand);
    $COD_VENDEDOR = mysqli_insert_id($connadm);


    $emailDestino = array(
        'email1' => 'diogo_tank@hotmail.com',
        'email5' => 'rone.all@gmail.com;adilson.safety@gmail.com'
    );
    fnsacmail(
        $emailDestino,
        "Suporte",
        "<html>
                                    <head>
                                        <title>TODO supply a title</title>
                                        <meta charset='UTF-8'>
                                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                    </head>
                                    <body>
                                        <table border='1'>
                                           <tr>
                                            <th>DATA/HORA</th>
                                            <th>COD_ERRO</th>
                                            <th>COD_EMPRESA</th>
                                            <th>Nome_usuario</th>
                                            <th>URL</th>
                                            <th>MODULO</th>
                                            <th>COMANDO</th>
                                          </tr>
                                          <tr>
                                            <td>" . date('d/m/Y H:i:s') . "</td>
                                            <td>$COD_VENDEDOR</td>
                                            <td>$cod_empresa</td>
                                            <td>$usuario</td>
                                            <td>$url</td>
                                            <td>$COD_MODULO</td>
                                            <td>$SQLCOMANDO</td>
                                          </tr>
                                         </table>    
                                    </body>
                                </html>
                                ",
        "Erro na tela do Sistema",
        "Erro na tela do Sistema",
        $connadm,
        $conntemp,
        "3"
    );
    sleep(1);
    return  $COD_VENDEDOR;
}
function fnlimpacelular($celular = false)
{
    $cellimpo = preg_replace('/[^0-9]/', '', $celular);

    $ddi = strripos($cellimpo, '55');
    $val_ddi = strlen($ddi);
    if ($val_ddi >= 1) {
        if (strlen($cellimpo) >= 13) {
            $cellimpo = Ltrim($cellimpo, '55');
        }
    }
    $cellimpo = Ltrim($cellimpo, '0');


    if (strlen($cellimpo) <= 11 && strlen($cellimpo) >= 10) {
        $cellimpo1 = substr($cellimpo, -8, 1);
        if ($cellimpo1 <= '3') {
            $cellimpo = '';
        }
        if (strlen($cellimpo) == 11) {
            $cellimpo2 = substr($cellimpo, -9, 1);
            if ($cellimpo2 <= '8') {
                $cellimpo = '';
            }
        }
    } else {
        $cellimpo = '';
    }
    if (strlen($cellimpo) == 10) {
        $cellimpo = substr_replace($cellimpo, '9', 2, 0);
    }
    $celularcount = count_chars(substr($cellimpo, 2), 1);
    foreach ($celularcount as $key => $value) {
        if ($value >= 8) {
            $cellimpo = '';
        }
    }

    return $cellimpo;
}

function  fnArrayToString($array)
{
    foreach ($array as $key) {
        $stringkey .= $key . ',';
    }
    $stringkey = rtrim($stringkey, ',');
    return $stringkey;
}

function fnLimpaCampoArray($arr)
{
    foreach ($arr as $key) {
        $cod = $cod . fnLimpaCampo($key) . ",";
    }
    $cod = rtrim(ltrim(trim($cod), ","), ",");
    if ($cod == "") {
        $cod = 0;
    }
    return $cod;
}


function fnCalculaLote($id, $cod_empresa)
{
    $sql = "SELECT
            SUM(VAL_EVOLUCAO) VAL_EVOLUCAO,
            SUM(VAL_MEDICAO) VAL_MEDICAO,
            SUM(VAL_TOTAL) VAL_TOTAL
          FROM controle_recebimento WHERE COD_RECEBIM_LOTE=0" . $id;
    $rs = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $linha = mysqli_fetch_assoc($rs);


    $sql = "UPDATE CONTROLE_RECEBIMENTO SET
				VAL_EVOLUCAO = 0" . $linha["VAL_EVOLUCAO"] . ",
				VAL_MEDICAO = 0" . $linha["VAL_MEDICAO"] . ",
				VAL_TOTAL = 0" . $linha["VAL_TOTAL"] . "
			WHERE COD_RECEBIM = 0" . $id;
    $rs = mysqli_query(connTemp($cod_empresa, ''), $sql);
}

function SaldoNexux($chave)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sms.nexuscomunicacao.com/api/sms/saldo.aspx?chave=' . $chave,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $responsearray = json_decode($response, true);
    $qtd_sms = $responsearray['SaldoComLimite'] / 0.01;
    return $qtd_sms;
}

function fnCompString($arrayWs, $cod_empresa, $cpf, $CONNADM, $conntmp)
{

    $buscli = "SELECT
                    NOM_CLIENTE as NOM_USUARIO,DES_EMAILUS,NUM_CELULAR,DAT_NASCIME,DES_SENHAUS
                    FROM clientes

                     WHERE cod_empresa=$cod_empresa  and
                          case when num_cgcecpf='$cpf' then  1
                               when num_cartao='$cpf' then  2
                      ELSE 0 END  IN (1,2)";


    $sqldados = mysqli_query($conntmp, $buscli);
    while ($fields = mysqli_fetch_field($sqldados)) {
        $chaves_desejadas[$fields->name] = $fields->name;
    }
    $rsdados = mysqli_fetch_assoc($sqldados);

    $array_filtrado = array_intersect_key($arrayWs, array_flip($chaves_desejadas));
    // Comparar os valores do $request com os valores do banco de dados
    $diferencas = array_diff_assoc($array_filtrado, $rsdados);
    // Verifique as diferenças e sinalize-as
    if (empty($diferencas)) {
        echo "Não há diferenças.";
    } else {

        $insqllog = "insert INTO log_alter_clientes (	COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO ) SELECT COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO FROM clientes 
                                    WHERE cod_empresa=$cod_empresa  and
                                                 case when num_cgcecpf='$cpf' then  1
                                                      when num_cartao='$cpf' then  2
                                             ELSE 0 END  IN (1,2)";
        mysqli_query($conntmp, $insqllog);
    }
}


function fnLimpaArray($arr)
{
    foreach ($arr as $key) {
        @$cod = @$cod . fnLimpaCampo($key) . ",";
    }
    $cod = rtrim(ltrim(trim($cod), ","), ",");
    if ($cod == "") {
        $cod = 0;
    }
    return $cod;
}
/*function fnscanV($dadosstrig)
{

    foreach ($dadosstrig as $key => $value) {

        $descriptors = array(
            0 => array('pipe', 'r'), // Descritor de arquivo para a entrada
            1 => array('pipe', 'w'), // Descritor de arquivo para a saída
            2 => array('pipe', 'w'), // Descritor de arquivo para a saída de erro
        );

        $process = proc_open('clamdscan --fdpass -', $descriptors, $pipes);

        if (is_resource($process)) {
            // Envia o texto para o descritor de arquivo de entrada
            fwrite($pipes[0], $value);
            fclose($pipes[0]);

            // Lê a saída do descritor de arquivo de saída
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Lê a saída de erro do descritor de arquivo de saída de erro
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Obtém o código de status do processo
            $status = proc_close($process);

            // Exibe a saída e o erro (se houver)
            if ($status == 1) {
                return [$output, $error, $status];
            }
        }
    }
}*/
function fnscanV($dadosstrig)
{
    foreach ($dadosstrig as $key => $value) {
        // Verifica se $value é um array e o converte para string se necessário
        if (is_array($value)) {
            $value = implode("\n", $value); // Converte array em string (cada elemento separado por uma nova linha)
        }

        $descriptors = array(
            0 => array('pipe', 'r'), // Descritor de arquivo para a entrada
            1 => array('pipe', 'w'), // Descritor de arquivo para a saída
            2 => array('pipe', 'w'), // Descritor de arquivo para a saída de erro
        );

        $process = proc_open('clamdscan --fdpass -', $descriptors, $pipes);

        if (is_resource($process)) {
            // Envia o texto para o descritor de arquivo de entrada
            fwrite($pipes[0], $value);
            fclose($pipes[0]);

            // Lê a saída do descritor de arquivo de saída
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Lê a saída de erro do descritor de arquivo de saída de erro
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Obtém o código de status do processo
            $status = proc_close($process);

            // Retorna a saída e o erro se houver status 1
            if ($status == 1) {
                return [$output, $error, $status];
            }
        }
    }

    // Retorna null se não houver status 1
    return null;
}


function fnDadosMedicacao($ean)
{
    $url = 'https://consultaremedios.com.br/busca?termo=' . $ean;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        return ["error" => curl_error($curl)];
    }

    curl_close($curl);

    $pattern = '/<script type="application\/ld\+json">(.+?)<\/script>/s';
    preg_match($pattern, $response, $matches);

    if (isset($matches[1])) {
        $jsonContent = $matches[1];
    } else {
        return ["error" => "Erro ao extrair conteúdo!"];
    }

    $data = [];

    $json = json_decode($jsonContent, true);

    $data["name"] = $json["@graph"][4]["name"];
    $data["url"] = $json["@graph"][4]["offers"]["url"];
    $data["brand"] = $json["@graph"][4]["brand"]["name"];
    $data["lowPrice"] = $json["@graph"][4]["offers"]["lowPrice"];
    $data["highPrice"] = $json["@graph"][4]["offers"]["highPrice"];
    $data["priceCurrency"] = $json["@graph"][4]["offers"]["priceCurrency"];
    $data["description"] = $json["@graph"][4]["description"];
    $data["image"] = $json["@graph"][4]["image"];
    $data["sku"] = $json["@graph"][4]["sku"];

    //echo "<pre>";
    //print_r($json);
    //echo "</pre>";

    return $data;
}
//function generateRandomString($length = 10) {
function generateRandomString($length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function readVouchersFromCSV($filename)
{
    if (!file_exists($filename)) {
        return [];
    }

    $vouchers = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $vouchers[] = $data[0];
        }
        fclose($handle);
    }

    return $vouchers;
}


function generateUniqueVoucherCSV($length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $filename = '/srv/www/htdocs/_system/LOG_TXT/voucher.csv')
{
    $charactersLength = strlen($characters);
    $randomString = '';

    // $filename='./_system/LOG_TXT/voucher.txt';
    $vouchers = readVouchersFromCSV($filename);

    do {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    } while (in_array($randomString, $vouchers));

    // Adiciona o novo voucher ao arquivo CSV
    if (($handle = fopen($filename, "a")) !== FALSE) {
        fputcsv($handle, [$randomString]);
        fclose($handle);
    }

    return $randomString;
}

function gerar_chave($length = 32)
{
    // Gera uma chave segura de 32 bytes
    return bin2hex(openssl_random_pseudo_bytes($length));
}
/*

// Função de codificação
function codificar($mensagem, $chave='0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef') {
    // Cria um vetor de inicialização (IV)
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    // Codifica a mensagem
    $mensagem_codificada = openssl_encrypt($mensagem, 'aes-256-cbc', hex2bin($chave), 0, $iv);
    // Retorna a mensagem codificada concatenada com o IV codificado em base64 URL-safe
    return rtrim(strtr(base64_encode($mensagem_codificada . '::' . $iv), '+/', '-_'), '=');
}

// Função de decodificação
function decodificar($mensagem_codificada, $chave='0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef') {
    // Converte a base64 URL-safe de volta para base64 padrão
    $mensagem_codificada = base64_decode(str_pad(strtr($mensagem_codificada, '-_', '+/'), strlen($mensagem_codificada) % 4, '=', STR_PAD_RIGHT));
    // Separa a mensagem codificada e o IV
    list($mensagem_codificada, $iv) = explode('::', $mensagem_codificada, 2);
    // Decodifica a mensagem
    return openssl_decrypt($mensagem_codificada, 'aes-256-cbc', hex2bin($chave), 0, $iv);
}

*/
function codificar($string)
{
    // Remove espaços da string
    $string = str_replace(' ', '', $string);

    // Compacta a string usando gzip
    $stringCompactada = gzcompress($string, 9);

    // Verifica se a compactação foi bem-sucedida
    if ($stringCompactada === false) {
        die("Erro: Não foi possível compactar a string.");
    }

    // Codifica em Base64 e substitui os caracteres '/&$%' por '-_#'
    return strtr(base64_encode($stringCompactada), '/&$%', '-_#');
}

function decodificar($stringCompactada)
{
    // Se a string estiver vazia, retorna uma string vazia
    if (empty($stringCompactada)) {
        return "";
    }

    // Se a string contiver caracteres fora do padrão esperado para Base64 customizado,
    // assumimos que ela já está decodificada e a retornamos como está.
    if (preg_match('/[^A-Za-z0-9\-_#]/', $stringCompactada)) {
        return $stringCompactada;
    }

    // Converte os caracteres customizados de volta para os originais
    $stringCompactada = strtr($stringCompactada, '-_#', '/&$%');

    // Tenta decodificar a string Base64
    $dadosDecodificados = base64_decode($stringCompactada);
    if ($dadosDecodificados === false) {
        error_log("Erro: Não foi possível decodificar a string Base64.");
        return "";
    }

    // Tenta descompactar a string usando gzip
    $stringDescompactada = gzuncompress($dadosDecodificados);
    if ($stringDescompactada === false) {
        error_log("Erro: Não foi possível descompactar a string.");
        return "";
    }

    return $stringDescompactada;
}

function fnFormatvalor($brl, $casasDecimais = 2)
{
    // Se já estiver no formato USD, retorna como float e formatado
    if (preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}
/*function fnBase64DecodeImg($string) {
                                        // Verifica se a string está vazia ou não é uma string
    if (empty($string) || !is_string($string)) {
        return $string;
    }

                                        // Verifica o tamanho da string
    $length = strlen($string);
    if ($length % 4 !== 0) {
        return $string;
    }

                                        // Verifica se a string contém apenas caracteres válidos em uma codificação Base64
    if (!preg_match('/^[A-Za-z0-9+\/]*={0,2}$/', $string)) {
        return $string;
    }

                                        // Decodifica a string
    $decoded = decodificar($string, true);
    if ($decoded === false) {
        return $string;
    }

                                        // Re-codifica a string decodificada e verifica se é igual à string original
    if (decodificar($decoded) !== $string) {
        return $string;
    }

                                        // Se a string passou todas as verificações, é uma string Base64 válida
    return $decoded;
}
*/
/*function fnBase64DecodeImg($mensagem_codificada)
{
    $decodificada = decodificar($mensagem_codificada);
    // Verifica se a decodificação foi bem-sucedida
    if ($decodificada !== false) {
        return $decodificada;
    } else {
        return $mensagem_codificada;
    }
}*/
function fnBase64DecodeImg($mensagem_codificada)
{
    // Se a mensagem estiver vazia, retorna null
    if (empty($mensagem_codificada)) {
        return null;
    }

    $decodificada = decodificar($mensagem_codificada);

    // Retorna o valor decodificado se obtido; caso contrário, retorna o valor original
    return ($decodificada !== false) ? $decodificada : $mensagem_codificada;
}


$msgRetorno = '';
$msgTipo = '';
$cmdPage = $_SERVER['REQUEST_URI'];
