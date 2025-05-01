<?php
include '../_system/Class_conn.php';
include '../wsmarka/func/function.php';    
//if($_SERVER['REQUEST_METHOD'] == "POST"){
  

 // Get data
 @$login = $_REQUEST['login'];
 @$password = $_REQUEST['senha'];
 @$idempresa = $_REQUEST['id_empresa'];
 @$idloja = $_REQUEST['id_loja'];
 @$cartao = $_REQUEST['cartao'];
 
  
 //// login senha
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($password)."','','','','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //verifica login
    
 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
        //conn user
         $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
         //=====================================================================================================

        $sql2="SELECT *  FROM clientes   WHERE NUM_CARTAO='".$cartao."' and COD_EMPRESA=".$row['COD_EMPRESA'];
        $Exec=mysqli_query($connUser->connUser(),$sql2);
        $qrBuscaCliente = mysqli_fetch_assoc($Exec);  
        $nome_cliente=$qrBuscaCliente['NOM_CLIENTE'];
        $cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
       
    
	$sql3="select NOM_ENTIDAD,COD_EXTERNO from ENTIDADE where COD_ENTIDAD = $cod_entidad";
	$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$sql3));		
	//fnEscreve($sql3);	
	$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD']; 
        
        $sql1 = "select veiculos.DES_PLACA, MARCA.COD_MARCA, veiculos.COD_EXTERNO, MARCA.NOM_MARCA, modelo.NOM_MODELO from veiculos 
                                left join MARCA on MARCA.COD_MARCA=veiculos.COD_MARCA 
                                left join modelo on modelo.COD_MODELO=veiculos.COD_MODELO where veiculos.COD_CLIENTE_EXT = $cartao "; 
             
            $arrayQuery = mysqli_query(connTemp(19,''),$sql1) or die(mysqli_error());
          
                     
             
             while ($qrListaVeiculo = mysqli_fetch_assoc($arrayQuery))
              {
            
             $json1=array("PLACA" => $qrListaVeiculo['DES_PLACA'], 
                            "MARCA"  => $qrListaVeiculo['DES_PLACA'],
                            "MODELO"  => $qrListaVeiculo['NOM_MODELO']);

                } 
         
               
         // retorno da dados
          $json = array("NOME" => $qrBuscaCliente['NOM_CLIENTE'], 
                          "EMAIL" => $qrBuscaCliente['DES_EMAILUS'],
                          "CPF"=> fncompletadoc($qrBuscaCliente['NUM_CGCECPF'], 'F'),
                          "TIP_CLIENTE"=>$qrBuscaCliente['TIP_CLIENTE'],
                          "DAT_NASCIME"=>$qrBuscaCliente['DAT_NASCIME'],
                          "SEXO"=>$qrBuscaCliente['COD_SEXOPES'],
                          "CELULAR"=>$qrBuscaCliente['NUM_CELULAR'],
                          "CARTAO"=>$qrBuscaCliente['NUM_CARTAO'],
                          "Dados_veiculos"=> $json1 
                          );
                        /* Output header */
         // print_r($json);
             header('Content-type: application/json');
             echo json_encode($json);
              
    }
    else
    {
      $json = array("id_loja" =>'Usuario ou senha invalido!',
                    "cod_erro"=>'1');
          /* Output header */
        header('Content-type: application/json');
        echo json_encode($json);
        exit();
    }    
    

//}
 

?>