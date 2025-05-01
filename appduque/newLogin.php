﻿<?php 
include './_system/_functionsMain.php';	
$msgErro = "";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 
        
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
			
		@$login = $_REQUEST['login'];
		@$senha = $_REQUEST['senha'];
               
		$sql = "CALL sp_mk_login('$login','$senha')";
              
                
                   
                    try 
                    {
                     $result=mysqli_query($connDUQUE->connDUQUE(),$sql);
                     
                    } 
                    catch (mysqli_sql_exception $e) 
                    {
                       echo  $e;
                       
                      
                    } 

                if(!$result)
                {
                 echo "erro ao efetuar o login";   
                }    
                
                
                $row= mysqli_fetch_assoc($result);
                if($row['retorno']!='invalido')
                {
                 @$_SESSION["COD_RETORNO"]=$row['retorno'];
                 @$cod_retorno=fnEncode($row['retorno']);
		 @$_SESSION["LOGIN"]=$login;                    
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
     
        
       // header("Location:https://www.rededuque.com.br/app/");
        header("Location:http://adm.bunker.mk/appduque/"); 
        
    }
	
	
?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Faça seu login";
		include "menu.php"; 
		?>	
		
        <div class="container">

            <div class="push10"></div> 
			<h4>
			Área exclusiva para beneficiários dos parceiros REDE DUQUE.
			
			<div class="push5"></div> 
			
			Saiba mais em: 
			</h4>
			
			<a href="parceiro.php" class="btn btn-primary btn-xs" style="font-weight: normal;font-size: 13px;"> Seja nosso parceiro </a>
            
			<div class="push20"></div> 
			
			<?php if ($msgErro != ""){
			?>
				<div class="push20"></div>
				<div class="alert alert-danger" role="alert">
				<?php echo $msgErro; ?>
				</div>			
			<?php	
			}
			?>	
			
			
		
            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/user_icon.png">
                </a>
            </div>

            <div class="push10"></div> 

            <form class="form-signin" method="post" action="login.php">
                <label for="login" class="sr-only">Email</label>
                <input type="email" name="login" id="login" class="form-control" placeholder="e-Mail" required="" autofocus="">
                <label for="senha" class="sr-only">Senha</label>
                <div class="push10"></div> 	
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required="">

                <button class="btn btn-primary btn-block" type="submit">ENTRAR</button>
            </form>
			
            <div class="push10"></div>
			
            <center><a href="login2.php" class="btn btn-default">Esqueci a senha</a></center>
			
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
			
            <div class="push10"></div> 
			<h5>
			Se você já é um beneficiário mas não tem login
			
			</h5>
			<div class="push5"></div> 			
			<a href="recadastro.php" class="btn btn-primary btn-xs" style="font-weight: normal;font-size: 13px;"> Crie agora </a>

            <div class="push30"></div> 

        </div> <!-- /container -->	
		<?php include 'jsLib.php';?>		
    </body>
</html>