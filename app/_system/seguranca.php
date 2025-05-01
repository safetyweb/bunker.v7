<?php

//include 'MENCACHED.php';
include "_functionsMain.php";

        
     $_SESSION["cod_url"]=1;

     if( $_SERVER['REQUEST_METHOD']=='GET' )
    {
      header("Location:".fnurl ()."/index.do"); 
      session_destroy();
      session_unset();
      unset($_SESSION["URL"]);
    }
     
    
    if( $_SERVER['REQUEST_METHOD']=='POST' )
    {
         
        $senha=fnEncode(fnLimpaCampo($_REQUEST['password']));
        $usuario= fnLimpaCampo($_REQUEST['login']);
            
        $sql = "CALL SP_VERIFICA_USUARIO('$usuario', '$senha')";
        
        $result=mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());
        $row=mysqli_fetch_row($result);
        $now = time();
        $_SESSION["ipport"] = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];
    //bloqueio de empresa LOG_ATIVO 23
     if($row[23]=='N')
     {
        $msg= '<div class="alert alert-danger" role="alert" id="msgRetorno">Empresa bloqueada!</div>';
        $_SESSION["MSG"]=$msg;
        header("Location:".fnurl ()."/index.do");
        exit();
     } 
          
     //Bloqueio de usuario LOG_ESTATUS 22 
     if($row[22]=='N')
     {
        $msg= '<div class="alert alert-danger" role="alert" id="msgRetorno">Usuario Bloqueado!</div>';
        $_SESSION["MSG"]=$msg;
        header("Location:".fnurl ()."/index.do");
        exit();
        
     }
        
    if(isset($row[4]) && isset($row[5]))
    {
        
       
        $_SESSION["testee"]='diogo';
        $_SESSION["tkt"] =1;
        //dados data base
        $_SESSION["servidor"]=$row[16];
        $_SESSION["userBD"]=$row[17];
        $_SESSION["SenhaBD"]= fnDecode($row[18]);
        $_SESSION["BD"]=$row[19];
		        
        $_SESSION["SYS_COD_SISTEMA"]=$row[6];
        $_SESSION["SYS_COD_EMPRESA"]=$row[15];
        $_SESSION["SYS_DES_CSSBASE"]=$row[10];
        $_SESSION["SYS_COD_MASTER"]=$row[8];
        $_SESSION["SYS_COD_MULTEMP"]=$row[7]; 
        $_SESSION["SYS_COD_USUARIO"]=$row[2];
        $_SESSION["SYS_NOM_USUARIO"]=$row[3];
        $_SESSION["SYS_COD_SISTEMAS"]=$row[9];
        $_SESSION["SYS_MENU_PRI"]=$row[11];
        $_SESSION["SYS_LOG_MULTEMPRESA"]=$row[21];
	// Set custom handlers 
        //session_set_save_handler ($row[2]); 
        
        //usuario em session
        $_SESSION["usuario"]=$row[4];
        //Time session
        $_SESSION['discard_after'] = $now + 120;
            if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
                session_unset();
                session_destroy();
                session_start();
            }
          
            if(!isset($_SESSION["URL"]))
             {  
           
             header("Location:".fnurl ()."/action.do");    
            }else{
               //echo  $_SESSION["URLLIMPO"];
              header("Location:". $_SESSION["URLLIMPO"]);
             
            }
          
    } else {
        //$row[0]
        $msg= '<div class="alert alert-danger" role="alert" id="msgRetorno">'.$row[0].'</div>';
        $_SESSION["MSG"]=$msg;
        header("Location:".fnurl ()."/index.do");
         
        exit();
    }
  
    
}else{  
    
    if($_GET['logoff']=='1'){
        session_destroy();
        session_unset();
        unset($_SESSION["URL"]);
        unset($_POST);
        unset($_GET);
        
        mysqli_close($connAdm->connAdm());
           
         //      header("Location:".$_SERVER['SERVER_NAME']);
        
       // echo $_SERVER['SERVER_NAME'];
       // echo $_SERVER ['REQUEST_URI'];
        exit;
    }
   
}
    

 ?>





