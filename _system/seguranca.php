<?php

//include 'MENCACHED.php';
include_once "_functionsMain.php";

$_SESSION["cod_url"] = 1;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  header("Location:" . fn_url() . "/index.do");
  session_destroy();
  session_unset();
  unset($_SESSION["URL"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $senha = fnEncode(fnLimpaCampo($_REQUEST['password']));
  $usuario = fnLimpaCampo($_REQUEST['login']);

  $sql = "CALL SP_VERIFICA_USUARIO('$usuario', '$senha')";

  // echo $sql;
  // exit();

  $result = mysqli_query($connAdm->connAdm(), trim($sql));
  $row = mysqli_fetch_row($result);
  // if($row[15]==113){
  //     echo $sql;
  //     exit();
  // }
  $now = time();
  $_SESSION["ipport"] = $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'];
  //bloqueio de empresa LOG_ATIVO 23
  if ($row[23] == 'N') {
    $msg = '<div class="alert alert-danger" role="alert" id="msgRetorno">Empresa bloqueada!</div>';
    $_SESSION["MSG"] = $msg;
    header("Location:" . fnurl() . "/index.do?msg=2");
    exit();
  }

  //Bloqueio de usuario LOG_ESTATUS 22 
  if ($row[22] == 'N') {
    //$msg= '<div class="alert alert-danger" role="alert" id="msgRetorno">Usuario Bloqueado!</div>';
    $msg = '<div class="alert alert-danger" role="alert" id="msgRetorno">Usuario ou Senha invalidos!</div>';

    $_SESSION["MSG"] = $msg;
    header("Location:" . fnurl() . "/index.do?msg=3");
    exit();
  }

  if (isset($row[4]) && isset($row[5])) {
    $_SESSION["testee"] = 'diogo';
    $_SESSION["tkt"] = 1;
    //dados data base
    $_SESSION["servidor"] = $row[16];
    $_SESSION["userBD"] = $row[17];
    $_SESSION["SenhaBD"] = fnDecode($row[18]);
    $_SESSION["BD"] = $row[19];

    $_SESSION["SYS_COD_SISTEMA"] = $row[6];
    $_SESSION["SYS_COD_EMPRESA"] = $row[15];
    $_SESSION["SYS_DES_CSSBASE"] = $row[10];
    $_SESSION["SYS_COD_MASTER"] = $row[8];
    $_SESSION["SYS_COD_MULTEMP"] = $row[7];
    $_SESSION["SYS_COD_USUARIO"] = $row[2];
    $_SESSION["SYS_NOM_USUARIO"] = $row[3];
    $_SESSION["SYS_COD_SISTEMAS"] = $row[9];
    $_SESSION["SYS_MENU_PRI"] = $row[11];
    $_SESSION["SYS_LOG_MULTEMPRESA"] = $row[21];
    $_SESSION["DATETIMELOGIN"] = date("Y-m-d H:i:s");
    $_SESSION["SYS_COD_SEGMENT"] = $row[25];
    $_SESSION["SYS_COD_TPUSUARIO"] = $row[26];
    $_SESSION["SYS_COD_UNIVEND"] = $row[27];
    $_SESSION["SYS_DES_CSSAUX"] = $row[28];
    $_SESSION["SYS_COD_PERFILS"] = $row[29];
    //caregando os modulo de acesso.
    $modsql = "SELECT COD_MODULOS
                FROM PERFIL,SISTEMAS
                WHERE PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND PERFIL.COD_PERFILS IN($row[29])";
    $arrMod1 = mysqli_query($connAdm->connAdm(), $modsql);
    $moduloasAcesso = "";
    while ($mod1 = mysqli_fetch_assoc($arrMod1)) {
      $moduloasAcesso .= $mod1['COD_MODULOS'] . ',';
    }


    $modsql2 = "SELECT  COD_MODULOS 
                FROM modulos  
                WHERE LOG_AUTORIZA='N'";
    $arrMod2 = mysqli_query($connAdm->connAdm(), $modsql2);
    $moduloasAcesso2 = "";
    while ($mod2 = mysqli_fetch_assoc($arrMod2)) {
      $moduloasAcesso2 .= $mod2['COD_MODULOS'] . ',';
    }

    $moduloasAcesso = ltrim(rtrim($moduloasAcesso, ','), ',');
    $moduloasAcesso2 = ltrim(rtrim($moduloasAcesso2, ','), ',');
    $_SESSION["SYS_COD_MOD"] = "$moduloasAcesso,$moduloasAcesso2";
    // echo "<br>$_SESSION[SYS_COD_MOD]";
    // exit();

    // variaveis de pop up de entrada
    $andNotice = "";
    $getNotice = "";

    if ($_SESSION["SYS_COD_SISTEMAS"] != 12 && $_SESSION["SYS_COD_SISTEMAS"] != 16) {
      $andNotice = "&notice=true";
      $getNotice = "?notice=true";
    }

    // echo($andNotice);
    // echo($getNotice);
    // exit();

    // variaveis de pop up de entrada - não exibe quando criadas nessas linhas
    // $andNotice = "";
    // $getNotice = "";

    //verifica se tem página home
    $sql = "";

    $sql = "SELECT S.COD_HOME,M.DES_COMMAND FROM sistemas S LEFT JOIN modulos M ON M.COD_MODULOS=S.COD_HOME WHERE cod_sistema=" . $_SESSION["SYS_COD_SISTEMA"] . "  ";
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrPaginaHome = mysqli_fetch_assoc($arrayQuery);
    //fnTestesql($connAdm->connAdm(),$sql);
    $_SESSION["SYS_COD_HOME"] = $qrPaginaHome['COD_HOME'];
    $_SESSION["SYS_PAG_HOME"] = $qrPaginaHome['DES_COMMAND'];
    //TROCA da primeira  senha

    $selsenha = "SELECT COD_USUARIO, COD_EMPRESA,SENHA_INI FROM webtools.usuarios WHERE COD_TPUSUARIO NOT IN (10,12,3) AND COD_USUARIO='" . $row[2] . "' and cod_empresa='" . $row[15] . "'";
    $dadosprimeirasenha = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $selsenha));
    if ($dadosprimeirasenha['SENHA_INI'] == '0') {
      $prieirasenhamod = '?mod=RXY4H5Q8bHA¢' . $andNotice;
    } else {
      $prieirasenhamod = $getNotice;
    }



    //https://adm.bunker.mk/action.do?mod=RXY4H5Q8bHA%C2%A2
    //=============================

    //insert LOG
    $log_insert = "INSERT INTO log_acesso 
                       (IP_ACESSO, 
                        PORTA_ACESSO,                     
                        COD_EMPRESA, 
                        COD_USUARIO, 
                        NOM_USUARIO, 
                        NUM_ACESSO,
                        DATA_ACESSO) 
                     VALUES 
                        ('" . $_SERVER['REMOTE_ADDR'] . "', 
                          '" . $_SERVER['REMOTE_PORT'] . "',                    
                          '" . $row[15] . "', 
                          '" . $row[2] . "', 
                          '" . $row[4] . "', 
                          '1',
                          '" . $_SESSION["DATETIMELOGIN"] . "');";
    mysqli_query($connAdm->connAdm(), $log_insert);

    // Set custom handlers 
    //session_set_save_handler ($row[2]); 

    //usuario em session
    $_SESSION["usuario"] = $row[4];
    //Time session
    $_SESSION['discard_after'] = $now + 120;
    if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
      $updatlogoff = "UPDATE log_acesso SET COD_ALTERACAO='2' 
                                        WHERE  COD_EMPRESA='" . $_SESSION["SYS_COD_EMPRESA"] . "' AND 
                                               COD_USUARIO = '" . $_SESSION["SYS_COD_USUARIO"] . "' AND 
                                               COD_ALTERACAO=1 and 
                                               DATA_ACESSO='" . $_SESSION["DATETIMELOGIN"] . "'";
      mysqli_query($connAdm->connAdm(), $updatlogoff);
      session_unset();
      session_destroy();
      session_start();
    }

    if (!isset($_SESSION["URL"])) {
      if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        header("Location:http://" . $_SERVER['HTTP_HOST'] . "/action.php" . $prieirasenhamod, true, 301);
      } else {
        header("Location:https://" . $_SERVER['HTTP_HOST'] . "/action.do" . $prieirasenhamod, true, 301);
      }
    } else {

      //echo  $_SESSION["URLLIMPO"];
      // fnEscreve($_SESSION["URLLIMPO"]);
      // exit();

      if (strstr($_SESSION["URLLIMPO"], '?')) {

        header("Location:" . $_SESSION["URLLIMPO"], true, 301) . $getNotice;
      } else {

        header("Location:" . $_SESSION["URLLIMPO"], true, 301) . $andNotice;
      }
    }
  } else {

    // verifica se o cliente tem base de dados.
    if ($row[0] != 'senha inválida!') {
      //$row[0]
      $msg = '<div class="alert alert-danger" role="alert" id="msgRetorno">Configuração de usuario invalida!</div>';
      $_SESSION["MSG"] = $msg;
      header("Location:" . fnurl() . "/index.do?msg=3");
      exit();
    }
    //$row[0]

    $msg = '<div class="alert alert-danger" role="alert" id="msgRetorno">' . $row[0] . '</div>';
    $_SESSION["MSG"] = $msg;
    header("Location: /index.do?msg=1");

    exit();
  }
} else {

  if ($_GET['logoff'] == '1') {

    $updatlogoff = "UPDATE log_acesso SET COD_ALTERACAO='2' 
                                        WHERE  COD_EMPRESA='" . $_SESSION["SYS_COD_EMPRESA"] . "' AND 
                                               COD_USUARIO = '" . $_SESSION["SYS_COD_USUARIO"] . "' AND 
                                               COD_ALTERACAO=1 and 
                                               DATA_ACESSO='" . $_SESSION["DATETIMELOGIN"] . "'";
    mysqli_query($connAdm->connAdm(), $updatlogoff);
    session_destroy();
    session_unset();
    unset($_SESSION["URL"]);
    unset($_POST);
    unset($_GET);
    mysqli_close($connAdm->connAdm());

    // header("Location:" . $_SERVER['SERVER_NAME']);

    // echo $_SERVER['SERVER_NAME'];
    // echo $_SERVER ['REQUEST_URI'];
    exit;
  }
}
