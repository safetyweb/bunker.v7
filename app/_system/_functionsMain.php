<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//ini_set('output_buffering',4092);
//ini_set('post_max_size', '128M');
//ini_set('max_execution_time', '120');
//ini_set('max_input_vars', '30000');
//ini_set('innodb_lock_wait_timeout = 120');
//ini_set('upload_max_filesize', '10M');

clearstatcache();
ignore_user_abort(true);
//ini_set("default_socket_timeout", 10); 

set_time_limit(300);
session_start();
date_default_timezone_set('America/Sao_Paulo');
ini_set('default_charset', 'UTF-8');

include 'Class_conn.php';

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
  mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
}
function logwebservice($get)
{
  return $escreve = '$weblog=REQUEST:\n"' . $get;
  $logqueryinsert = "insert into log (DATA,LOG_COL) 
                                          values
                                         ('" . DATE("H:i:s") . "','" . $escreve . "');";
  mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
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
//------------FIM
function fnNocachePage()
{

  $u_agent = $_SERVER['HTTP_USER_AGENT'];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version = "";


  if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  } elseif (preg_match('/Firefox/i', $u_agent)) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  } elseif (preg_match('/Chrome/i', $u_agent)) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  } elseif (preg_match('/AppleWebKit/i', $u_agent)) {
    $bname = 'AppleWebKit';
    $ub = "Opera";
  } elseif (preg_match('/Safari/i', $u_agent)) {
    $bname = 'Apple Safari';
    $ub = "Safari";
  } elseif (preg_match('/Netscape/i', $u_agent)) {
    $bname = 'Netscape';
    $ub = "Netscape";
  }
}


///////////////////
//---------Carrega a Pagina
function carregaPagina($vf)
{
  if ($vf == 'true') {

    ob_start();
  } elseif ($vf = 'false') {

    ob_end_flush();
    ob_flush();
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
  $campo = trim($campo); //limpa espaços vazio
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
  return mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, $timeseg);
  // return mysqli_options($conn,MYSQLi_OPT_COMPRESS,9);
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
  mysqli_query($conn, $sqlinsert) or die(mysqli_error());
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
  $strcount = date('Y-m-d', strtotime($data));
  return $strcount;
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
    $datre = strtotime($str);
    $dateretorno = date('d/m/Y', $datre);
    return $dateretorno;
  } else {
    $dateretorno = '';
    return $dateretorno;
  }
}

function fnValor($Num, $Dec)
{
  if (!is_numeric($Num) || empty($Num) || is_null($Num)) {
    $Numero = 0;
  } else {
    $Numero = $Num;
  }
  $valor = number_format($Numero, $Dec, ",", ".");
  //echo $valor; //retorna o valor formatado para apresentação em tela  
  return $valor;
}

function fnValorSql($Num)
{
  if (empty($Num) || is_null($Num)) {
    $Numero = 0;
  } else {
    $Numero = $Num;
  }
  $valor = str_replace(".", "", $Numero);
  $valor = str_replace(",", ".", $valor);
  return $valor; //retorna o valor formatado para gravar no banco 
}

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
function fnEscreve($Texto)
{

  echo $escreveTexto = "<h1>_" . $Texto . "_</h1>";
  return $escreveTexto;
}
function fnEscreveArray($Texto)
{
  echo "<pre>";
  return print_r($Texto);
  echo "<pre>";
}
function fnSessionSegura()
{
  //criar aqui a verificação de autenticação
}

function recursive_array_search($needle, $haystack)
{
  foreach ($haystack as $key => $value) {
    $current_key = $key;
    if ($needle === $value or (is_array($value) && recursive_array_search($needle, $value) !== false)) {
      return $current_key;
    }
  }
  return false;
}
function fnMemInicial($conn, $opcao, $user)
{
  $datahora = date("d/m/Y H:i:s");
  // $finaltime1=(microtime(TRUE) - $time);
  if ($opcao == "true") {


    $mem_usage = memory_get_usage(true);

    if ($mem_usage < 1024) {

      $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . $mem_usage . " bytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
      mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
    } elseif ($mem_usage < 1048576) {

      $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . round($mem_usage / 1024, 2) . " kilobytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
      mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
    } else {

      $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . round($mem_usage / 1048576, 2) . " megabytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
      mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
    }
  } elseif ($opcao = 'false') {


    // $finaltime1=(microtime(TRUE) - $time);


    $mem_usage = memory_get_usage(true);

    $tempo_carregamento = microtime(TRUE);
    -$_SERVER['REQUEST_TIME'];
    if ($mem_usage < 1024) {

      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "',  NEM_FINAL='" . $mem_usage . "',ativo=1 WHERE  PAGINA='" . $_GET['mod'] . "' and ativo='0'";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } elseif ($mem_usage < 1048576) {

      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', NEM_FINAL='" . round($mem_usage / 1024, 2) . " kilobytes" . "',ativo=1 WHERE  PAGINA='" . $_GET['mod'] . "' and ativo=0";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } else {


      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', NEM_FINAL='" . round($mem_usage / 1048576, 2) . " megabytes" . "',ativo=1 WHERE  PAGINA='" . $_GET['mod'] . "' and ativo=0";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    }
    //Picos de memoria
    $mem_usage = memory_get_peak_usage(true);

    if ($mem_usage < 1024) {


      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', NEM_PICO='" . $mem_usage . "',MEN_PICO=1 WHERE  PAGINA='" . $_GET['mod'] . "' and MEN_PICO='0'";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } elseif ($mem_usage < 1048576) {

      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', NEM_PICO='" . round($mem_usage / 1024, 2) . " kilobytes" . "',MEN_PICO=1 WHERE  PAGINA='" . $_GET['mod'] . "' and MEN_PICO=0";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } else {


      $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "',  NEM_PICO='" . round($mem_usage / 1048576, 2) . " megabytes" . "',MEN_PICO=1 WHERE  PAGINA='" . $_GET['mod'] . "' and MEN_PICO=0";
      mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    }
  }
}
function fn_url()
{

  if ($_SESSION["cod_url"] != 1) {

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
  return $renult;
}

function fnLogin()
{
  if (!isset($_SESSION["usuario"])) {

    include 'index.php';
  } else {
  }
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

  if ($param == 'true') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
  } elseif ($param == 'false') {
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

function fnAcentos($string)
{
  // matriz de entrada
  $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', "'", '...');

  // matriz de saída
  $by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', "", '');

  // devolver a string
  return str_replace($what, $by, $string);
}

function fnconsultaBase($conn, $CPF, $mepresa, $login, $conn2)
{
  $sql = "SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where NUM_CGCECPF=" . $CPF;
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
      $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

      $array = json_decode(json_encode($xml), TRUE);
      $NOME = $array['body']['envelope']['body']['consultapessoasimplificadoresponse']['consultapessoasimplificadoresult']['nome'];
      $CPF = $array['body']['envelope']['body']['consultapessoasimplificadoresponse']['consultapessoasimplificadoresult']['cpf'];
      $datanascimento = $array['body']['envelope']['body']['consultapessoasimplificadoresponse']['consultapessoasimplificadoresult']['datanascimento'];
      $sexo = $array['body']['envelope']['body']['consultapessoasimplificadoresponse']['consultapessoasimplificadoresult']['sexo'];
      $sql1 = "insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA) value
                                    ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $CPF . "','" . $NOME . "','" . $mepresa . "','" . $login . "','0','0')";
      mysqli_query($conn2, $sql1);
      // echo $sql1;
      // return $sql1;
      $arraycpf = array();
      array_push($arraycpf, array("nome" => $NOME, "cpf" => $CPF, 'sexo' => $sexo, 'datanascimento' => $datanascimento));
      return $arraycpf;
    }
  }
}
function fngeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
  //$lmin = 'abcdefghijklmnopqrstuvwxyz';
  $lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
  $num = '123456789';
  //$simb = '@#$';
  $retorno = '';
  $caracteres = '';
  $caracteres .= $lmin;
  if ($maiusculas) $caracteres .= $lmai;
  if ($numeros) $caracteres .= $num;
  if ($simbolos) $caracteres .= $simb;
  $len = strlen($caracteres);
  for ($n = 1; $n <= $tamanho; $n++) {
    $rand = mt_rand(1, $len);
    $retorno .= $caracteres[$rand - 1];
  }
  return $retorno;
}

function fnconsultaCPF_soap($array)
{
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt_array($curl, array(
    CURLOPT_URL => "http://soap.bunker.mk?wsdl",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                                <soap:Body>
                                    <BuscaConsumidor xmlns="fidelidade">
                                        <fase>fase1</fase>
                                        <opcoesbuscaconsumidor>
                                            <cpf>' . $array['cpf'] . '</cpf>                                           
                                        </opcoesbuscaconsumidor>
                                             <dadoslogin>
                                                    <login>' . $array['login'] . '</login>
                                                    <senha>' . $array['senha'] . '</senha>
                                                    <idloja>' . $array['idloja'] . '</idloja>
                                                    <idmaquina>' . $array['idmaquina'] . '</idmaquina>
                                                    <idcliente>' . $array['idcliente'] . '</idcliente>
                                                    <codvendedor>12</codvendedor>
                                                    <nomevendedor>string</nomevendedor>
                                              </dadoslogin>
                                    </BuscaConsumidor>
                                </soap:Body>
                            </soap:Envelope>',
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: text/xml",
      "postman-token: 2b0075e3-9bf1-91d8-ccf6-a519eeca3c33"
    ),
  ));


  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return $err;
  } else {

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($response);
    libxml_clear_errors();
    $xml = $doc->saveXML($doc->documentElement);
    //$xml = simplexml_load_string($xml);
    $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);


    return $response;
  }
}

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
    || $mask == "0000-0000" || $mask == "00000-0000" || $mask == "0000-00000" || $mask == "0000000-0000"
  ) {
    //TELEFONE
    $str = str_ireplace(" ", "", $str);
    $str = str_ireplace(")", ") ", $str);
    $e = explode("-", $str);
    $e[1] = preg_replace("/[0-9]/is", "*", @$e[0]);
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

    $part2 = preg_replace("/[a-zA-Z0-9]/is", "*", $part1);
    $str = "**" . substr($part1, 2) . $part2;
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

//variáveis globais
$msgRetorno = '';
$msgTipo = '';
//retorno url página
$cmdPage = $_SERVER['REQUEST_URI'];
