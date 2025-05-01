﻿<?php 
include './_system/_functionsMain.php';	
	$msgErro = "";

       
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
			
		@$email = $_REQUEST['login'];
		
                //juliano.dalbom@dspgrupo.com.br
                //6262
		//echo "<h1>".$login."</h1>";
		//echo "<h1>".$senha."</h1>";
             
		$sql = "CALL sp_mk_recadastra_senha('$email')";
                
                
                $result=mysqli_query($connDUQUE->connDUQUE(),$sql) or die(mysqli_error());
                $row= mysqli_fetch_assoc($result);
          
                if($row['ok']!='ok'){$msgErro =  'e-mail invalido!';}else{$msgOK =  'Uma nova senha foi enviada para seu e-mail!';}    
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
		$tituloPagina = "Nova Senha";
		include "menu.php"; 
		?>	
		
        <div class="container">

            <div class="push10"></div> 
			<h4>
			Informe seu e-mail para geração de uma nova senha.
			</h4>
	<div class="push25"></div> 
			
			<?php if ($msgErro != ""){
			?>
				<div class="push20"></div>
				<div class="alert alert-danger" role="alert">
				<?php echo $msgErro; ?>
				</div>			
			<?php	
			}
			?>	
			<?php if ($msgOK != ""){
			?>
				<div class="push20"></div>
				<div class="alert alert-success" role="alert">
				<?php echo $msgOK; ?>
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

            <form class="form-signin" method="post" action="login2.php">
                <label for="login" class="sr-only">Email</label>
                <input type="email" name="login" id="login" class="form-control" placeholder="e-Mail" required="" autofocus="">
                <div class="push10"></div> 
                <button class="btn btn-primary btn-block" type="submit">ALTERAR</button>
            </form>
			
         
		
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