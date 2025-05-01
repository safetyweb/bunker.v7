<?php
include_once '../_system/Class_conn.php';
include_once '../wsmarka/func/function.php';
// Get data
 @$login = $_REQUEST['login'];
 @$password = $_REQUEST['senha'];
 @$COD_EMPRESA = $_REQUEST['idcliente'];
  
 //// login senha
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$login."', '".fnEncode($password)."','','',$COD_EMPRESA,'','')";
     
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //verifica login
    
 
    if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS'])){
       $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
       
       //==BUSCA LAYOUT 
       $SQLLAYOUT="select * from totem_app where cod_empresa=".$row['COD_EMPRESA'];
       $rslayout=mysqli_fetch_assoc(mysqli_query($connUser->connUser(), $SQLLAYOUT));
         
         $json = array("imgLogo" =>"http://img.bunker.mk/media/clientes/".$row['COD_EMPRESA']."/".$rslayout['DES_LOGO'], 
                        "imgFundo"=>"http://img.bunker.mk/media/clientes/".$row['COD_EMPRESA']."/".$rslayout['DES_IMGBACK'],
                        "corBarra"=>$rslayout['COR_BACKBAR'],
                        "corFundoPagina"=>$rslayout['COR_BACKPAG'],
                        "corTituloPagina"=>$rslayout['COR_TITULOS'],
                        "corContrastePagina"=>$rslayout['COR_FULLPAG'],
                        "corContrasteTexto"=>$rslayout['COR_TEXTFULL'],
                        "corTextoPagina"=>$rslayout['COR_TEXTOS'],
                        "corBotao"=>$rslayout['COR_BOTAO'],
                        "corBotaoHover"=>$rslayout['COR_BOTAOON']
                       );
    }else{
          $json = array("msg" =>'Login ou senha invalido', 
                        "coderro"=>'400'
                       );
    }
header('Content-type: application/json');
 echo  json_encode($json);  
     


           