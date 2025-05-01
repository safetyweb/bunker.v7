<?php
include '../../_system/Class_conn.php';
include '../../wsmarka/func/function.php';    
include './function_json/json_func.php';
//if($_SERVER['REQUEST_METHOD'] == "POST"){
  header('Content-type: application/json'); 

 // Get data
 @$login = $_REQUEST['login'];
 @$password = $_REQUEST['senha'];
 @$idloja = $_REQUEST['id_loja'];
 @$idmaquina = $_REQUEST['idmaquina'];
 @$idcliente = $_REQUEST['idcliente'];
 @$cpf = $_REQUEST['cpf'];
 @$codvendedor = $_REQUEST['codvendedor'];
 @$nomevendedor = $_REQUEST['nomevendedor'];
  
 //// login senha
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($password)."','','','".$idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //verifica login

    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
        //conn user
         $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
        //=====================================================================================================
    
 
        $arrylog=array('cod_usuario'=>$row['COD_USUARIO'],
                        'login'=>$login,
                        'cod_empresa'=>$row['COD_EMPRESA'],
                        'idloja'=>$idloja,
                        'idmaquina'=>$idmaquina,
                        'cpf'=>$cpf,     
                        'xml'=>addslashes(str_replace(array("\n",""),array(""," "), var_export(gravajsonPOST(),true))),
                        'tables'=>'origembusca',
                        'conn'=>$connUser->connUser()
             
                      );
        $cod_log=fngravalogxml($arrylog);
                 
         $busca=array('cpf'=>$cpf,
                      'ConnB'=> $connUser->connUser(),
                      'conn'=>$connAdm->connAdm(),
                      'cartao'=>$cpf,
                      'empresa'=>$row['COD_EMPRESA'],
                      'cnpj'=>'',
                      'email'=>'',
                      'cod_cliente'=>'',
                      'consultaativa'=>$row['LOG_CONSEXT'],
                      'pagina'=>'ConsultaCadastroPorCPF'
                      );        
         $arraybusca=fnbusca($busca); 
         
          
      
        echo json_encode($arraybusca);
        
    }
    else
    {
      $json = array("msgerro" =>'Usuario ou senha invalido!',
                    "cod_erro"=>'1');
          /* Output header */
      
        echo json_encode($json);
        
    }    
