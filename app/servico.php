<?php
include '../_system/Class_conn.php';
include '../wsmarka/func/function.php';    
if($_SERVER['REQUEST_METHOD'] == "POST"){
  

 // Get data
 @$login = $_REQUEST['login'];
 @$password = $_REQUEST['senha'];
 @$idempresa = $_REQUEST['idcliente'];
 @$idloja = $_REQUEST['id_loja'];
 
 
$connAdmvar=$connAdm->connAdm();  
 
 //// login senha
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($password)."','','',$idempresa,'','')";
    $buscauser=mysqli_query($connAdmvar,$sql);
    $row = mysqli_fetch_assoc($buscauser);
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
        
        
         //VERIFICA SE A EMPRESA FOI DESABILITADA
        if($row['LOG_ATIVO']=='N'){
            
            $json = array( "Messagem" => 'Oh não! A empresa foi desabilitada por algum motivo',
                           "Codigo"=>'1');
                        /* Output header */
            header('Content-type: application/json');
            echo json_encode($json);
            exit();
        }
        
        //VERIFICA SE O USUARIO FOI DESABILITADA
        if($row['LOG_ESTATUS']=='N'){
            
           $json = array( "Messagem" => 'Oh não! Usuario foi desabilitado ;-[!',
                           "Codigo"=>'2');
                        /* Output header */
            header('Content-type: application/json');
            echo json_encode($json); 
            exit();
        }
        
        
        //conn user
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
        $connUserADM=$connUser->connUser();
        //=====================================================================================================
           $urlextrato=fnEncode($login.';'
                                .$password.';'
                                .$idempresa.';'
                                .$idloja);
         // retorno da dados
          $json = array("URL" =>"http://adm.bunker.mk/app/app.do?key=$urlextrato", 
                        "Messagem" => 'OK',
                        "Codigo"=>'3');
                        /* Output header */
         // print_r($json);
             header('Content-type: application/json');
             echo json_encode($json);
              
    }else{
      $json = array("Messagem" =>'Usuario ou senha invalido!',
                    "Codigo"=>'4');
          /* Output header */
        header('Content-type: application/json');
        echo json_encode($json);
        exit();
    } 
} else {
     $json = array("Messagem" =>'seja bem vindo!',
                    "Codigo"=>'5');
          /* Output header */
        header('Content-type: application/json');
        echo json_encode($json);
        exit();
}    
?>