﻿<?php 
include './_system/_functionsMain.php';	
$msgErro = "";

$cod_empresa = 19;

//echo fnDebug('true');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
        
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
			
		 if($_REQUEST['keep'] == 'on' || $_COOKIE['keep']=='on')
                {
                  /*setcookie("keep",$_REQUEST['keep']); 
                  setcookie("ativo",'checked');  
                  setcookie("login",$_REQUEST['login']);
                  setcookie("senha",$_REQUEST['senha']);
                  */
                  $_COOKIE['keep']=$_REQUEST['keep'];
                  $_COOKIE['ativo']='checked';
	          $_COOKIE['login']=$_REQUEST['login'];
                  $_COOKIE['senha']=$_REQUEST['senha'];	
                  @$login = $_COOKIE['login'];
                  @$senha = $_COOKIE['senha'];
                             
                }else{
                    @$login = $_REQUEST['login'];
                    @$senha = $_REQUEST['senha']; 
                }	
		
               if($_REQUEST['keep'] != 'on')
                {
                             setcookie("keep",''); 
                             setcookie("ativo",'');  
                             setcookie("login",'');
                             setcookie("senha",'');
                } 
               
		$sql = "CALL sp_mk_login('$login','$senha')";
                  $result=mysqli_query($connDUQUE->connDUQUE(),$sql);             
                
                   /* try 
                    {
                     $result=mysqli_query($connDUQUE->connDUQUE(),$sql);
                     
                    } 
                    catch (mysqli_sql_exception $e) 
                    {
                       echo  $e;
                       
                      
                    } */
 

              /*  if(!$result)
                {
                 echo "erro ao efetuar o login";   
                }    
                */
                
                $row= mysqli_fetch_assoc($result);
                
                

                @$_SESSION["COD_RETORNO"]=$row['retorno'];
                @$cod_retorno=fnEncode($row['retorno']);
				@$_SESSION["LOGIN"]=$login;
             
		if($row['retorno']!='invalido')
                {
                                    
                 //header("Location:https://www.rededuque.com.br/app/home.php?secur=$cod_retorno&log=1");
                 header("Location: https://adm.bunker.mk/appduque/home.php?secur=$cod_retorno&log=1");
                                
                }else{
                                          
                         $msgErro =  'usuario e senha invalido'; 
                         //header("Location: http://bunker.mk/appduque/login.php");
                }    
                
                
	}
	
    //Rotina de logoff
	if($_GET['logoff']=='1'){
        //session_destroy();
        session_unset();
        unset($_SESSION["login"]);
      
        
       //header("Location:https://www.rededuque.com.br/app/");
       header("Location:http://adm.bunker.mk/appduque/"); 
        
    }
	
	
?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <style>
      .logo{
        margin-top: 25px;
        margin-bottom: 25px;
      }
      #senha{
        border-radius: 0px; 
      }

      .bgColor {
          background-color: #03204F;
          /*background-image: url(img/bg_intro.jpg);*/
          background-position: center; 
          background-size: cover;
      }

      .btn-primary{
        background: #84B1F5!important;
        border-color: #84B1F5!important
      }

      .text-white{
          color: #fff!important;
      }

    </style>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		// $tituloPagina = "Faça seu login";
		// include "menu.php"; 
		?>	
		
  <div class="container">

     <div class="row">
      <div class="col-xs-3 text-center">
        <a href="index.php"><i class="fa fa-arrow-left fa-2x text-white" aria-hidden="true"></i></a>
      </div>
    </div>  
    
        <div class="container">

          <div class="push30"></div>

            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/logo_intro_big.png" width="80%">
                </a>
            </div>

            <div class="push10"></div> 

            <form class="form-signin" method="post" action="login.php">
                <label for="login" class="sr-only">Email</label>
                <input type="email" name="login" id="login" value="<?php echo @$_COOKIE['login'];?>" class="form-control" placeholder="e-Mail" required="" autofocus="">
                <label for="senha" class="sr-only">Senha</label>
                <div class="push10"></div> 	
                <input type="password" name="senha" id="senha"  value="<?php echo @$_COOKIE['senha'];?>" class="form-control" placeholder="Senha" required="">

                <button class="btn btn-primary btn-block" type="submit">ENTRAR</button>
				<!--<div class="push10"></div> -->
				<!--<input type="checkbox" name="keep" id="keep" class="bigCheck" <?php //echo @$_COOKIE['ativo']; ?>> <span class="">Manter logado</span>-->

            </form>
			
            <div class="push10"></div>
			
            <center><a href="login2.php?param=OFF" class="btn btn-default">Esqueci a senha</a></center>
			
            <div class="push20"></div>
			<!--
            <div class="push50"></div>
            <div class="push30"></div>
            <div class="mytextdiv">
                <div class="mytexttitle">problemas</div>
                <div class="divider"></div>
            </div>
            <div class="text-center">
                <a href="cadastro.html" class="form-signin-heading" style="float: left">Quero me cadastrar</a>
                <a class="btn btn-primary btn-lg-spc" style="float: right">Esqueci a senha</a>
            </div>
			-->

        </div> <!-- /container -->	
		<?php include 'jsLib.php';?>		
    </body>
</html>