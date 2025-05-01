<?php 
 
//settings
@$cache_ext  = '.php'; //file extension
@$cache_time     = '3600';  //Cache file expires afere these seconds (1 hour = 3600 sec)
@$cache_folder   = 'cache/'; //folder to store Cache files
@$ignore_pages   = array('', '');

@$dynamic_url    = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; // requested dynamic page (full url)
@$cache_file     = $cache_folder.md5($dynamic_url).$cache_ext; // construct a cache file
@$ignore = (in_array($dynamic_url,$ignore_pages))?true:false; //check if url is in ignore list

if (!$ignore && file_exists($cache_file) && time() - $cache_time < filemtime($cache_file)) 
{
   
    if( $_SERVER['REQUEST_METHOD']!='POST')
    {
       //check Cache exist and it's not expired.
        ob_start('ob_gzhandler'); //Turn on output buffering, "ob_gzhandler" for the compressed page with gzip.
        readfile($cache_file); //read Cache file
        echo '<!-- cached page - '.date('l jS \of F Y h:i:s A', filemtime($cache_file)).', Page : '.$dynamic_url.' -->';
        ob_end_flush(); //Flush and turn off output buffering
        exit(); //no need to proceed further, exit the flow.
    } else {
       $_SERVER['REQUEST_METHOD']='GET'; 
       unset($_POST);
       unlink($cache_file);
     
    }
}

//Turn on output buffering with gzip compression.
ob_start('ob_gzhandler'); 

require_once "_system/_functionsMain.php";
//fnMostraForm();
  
if($_GET['security']!='OFF')
{
    fn_url (); 
    fnLogin ();

    fncompress($connAdm->connAdm(),'30');
    fncompress($connUser->connUser(),'30');
    $arraypost=addslashes(str_replace(array("\n",""),array(""," "), var_export(gravapos(),true)));
     fnMemInicial($connAdm->connAdm(),'true',$_SESSION["usuario"],$arraypost);
    $i=tempoinicial();
    //carregaPagina('true');
}		
//verifica se tela é pop up
if (isset($_REQUEST['pop']))
{
	$popUp = $_REQUEST['pop'];
	if ($popUp != "true"){  
		$tipoPortlet = "portlet-bordered";
	} else {
		$tipoPortlet = "";
	} 				
} else { $popUp = "false"; }

		
//muda sistema
if (isSet($_REQUEST['sys']))
{
	unset($_SESSION["SYS_MODUL_AUTOR"]);
	unset($_SESSION["SYS_COD_SISTEMA"]);
	unset($_SESSION["SYS_LOG_MULTEMPRESA"]);
	$_SESSION["SYS_COD_SISTEMA"] = fnDecode($_REQUEST['sys']);
	//altera session de multiempresa
	$sql = "select LOG_MULTEMPRESA from SISTEMAS WHERE COD_SISTEMA = '".fnDecode($_REQUEST['sys'])."'";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrVerificaMultiEmpresa = mysqli_fetch_assoc($arrayQuery);
	$_SESSION["SYS_LOG_MULTEMPRESA"] = $qrVerificaMultiEmpresa['LOG_MULTEMPRESA'];
	
}
        //selecionar os perfis do usuario
        $sqluserperfil="SELECT cod_perfils from usuarios where cod_empresa=".$_SESSION["SYS_COD_EMPRESA"]." and cod_usuario=".$_SESSION["SYS_COD_USUARIO"];
        $rsuserperfil=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqluserperfil));
     
        $sqlperfil="select cod_perfils, cod_modulos,cod_sistema from perfil where cod_sistema= ".$_SESSION["SYS_COD_SISTEMA"]." and cod_perfils in (".$rsuserperfil['cod_perfils'].")" ;
        $rsperfil= mysqli_query($connAdm->connAdm(), $sqlperfil);
        
        while ($resultperfil= array_unique(mysqli_fetch_assoc($rsperfil)))
        {
            $SYS_MODUL_AUTOR.=$resultperfil['cod_modulos'].',';
        }
        $_SESSION["SYS_MODUL_AUTOR"]=explode(",", $SYS_MODUL_AUTOR);         
    //echo $_SESSION["SYS_MODUL_AUTOR"];
       
?>	

<html lang="pt">
    <head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

	<title>Webtools</title>

	<?php require_once "cssLib.php"; ?>	
	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>
	
	<?php if ($popUp != "true"){ require_once "header.php"; } ?>	

	<?php if ($popUp != "true"){ require_once  "menu.php"; } ?> 
	
	<?php if ($popUp != "true"){ $containerOut = "outContainer"; } else {$containerOut = "outContainerPop"; } ?>	
	
    <div class="<?php echo $containerOut; ?>">
	
	<?php if ($popUp != "true"){ $container = "containerfluid"; } else {$container = "containerfluid"; } ?>	
	
		<div class="<?php echo $container; ?>">
		
		
		<?php if ($popUp != "true"){  ?>
			<!-- <div class="push50"></div> -->
		<?php } ?>
		
		<?php 	
                if(!empty($_GET['mod']))
                    {
                   $qrModulBUsca="select * from modulos where COD_MODULOS='".fnDecode($_GET['mod'])."'";
                   $QrCarregaModul= mysqli_query($connAdm->connAdm(),$qrModulBUsca) or die(mysqli_error());
                   $QrLinhaModul = mysqli_fetch_assoc($QrCarregaModul);
                   $NomePg=$QrLinhaModul['NOM_MODULOS'];
                   $tip_modulos=$QrLinhaModul['TIP_MODULOS'];
                   $ActionPg=$QrLinhaModul['DES_COMMAND'];
                   
                   if (!is_null($QrLinhaModul['COD_DESTINO']) || !empty($QrLinhaModul['COD_DESTINO'])){
					$RedirectPg = $QrLinhaModul['COD_DESTINO'];   
				   }
                   $filename = $ActionPg;
                  
                    if($tip_modulos==2)
                    {
                         
                         if (file_exists('./relatorios/'.$filename)) {
                            require_once "./relatorios/".$ActionPg."";
                        } else {
                            echo ":( O módulo <b> ".$QrLinhaModul['NOM_MODULOS']." </b> não existe, você esta sendo redirecionado automaticamente";
                            echo '<meta http-equiv="refresh" content="5; url=action.do" />';
                        }
                         
                    }else{
                        if (file_exists($filename)) {
                            require_once "$ActionPg";
                        } else {
                            echo ":( O módulo <b> ".$QrLinhaModul['NOM_MODULOS']." </b> não existe, você esta sendo redirecionado automaticamente";
                            echo '<meta http-equiv="refresh" content="5; url=action.do" />';
                        }
                    }
                    
                   }else{}
                   
                   
                   
                ?>	

		<?php if ($popUp != "true"){   ?>
			<div class="push100"></div> 
		<?php } ?>
		
		<a href="#0" class="cd-top">Top</a> 
		 
		</div>
		<!-- end container -->    
	
	</div>
	<!-- end outContainer -->
	
	<?php require_once "jsLib.php"; ?>

    </body>
	
</html>
<?php 

     
if(@$_GET['security']!='OFF')
{    
//carregaPagina('false');
tempofinal($i,$connAdm->connAdm());
fnMemInicial($connAdm->connAdm(),'false',$_SESSION["usuario"],$arraypost);
 
LOG_DB($connAdm->connAdm(),$connAdm->connAdm());
//LOG_DB($connUser->connUser(),$connUser->connUser());
process_kill($connAdm->connAdm());
process_kill($connUser->connUser());
cache_query ($connAdm->connAdm(),1);
cache_query ($connUser->connUser(),1);
//cache_query (connTemp($_SESSION["SYS_COD_EMPRESA"],''),1);
}

######## Your Website Content Ends here #########
  if (!is_dir($cache_folder)) { //create a new folder if we need to
        mkdir($cache_folder);
        chmod ($cache_folder, '777');
        chown($cache_folder, 'adm');
    }
    if(!$ignore){
        $fp = fopen($cache_file, 'w');  //open file for writing
        fwrite($fp, ob_get_contents()); //write contents of the output buffer in Cache file
        fclose($fp); //Close file pointer
    }
    ob_end_flush(); //Flush and turn off output buffering

  
?>
