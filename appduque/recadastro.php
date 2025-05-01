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
                @$_SESSION["COD_RETORNO"]=$row['retorno'];
                @$cod_retorno=fnEncode($row['retorno']);
		@$_SESSION["LOGIN"]=$login;
             
		if($row['retorno']!='invalido')
                {
                                    
                   //header("Location:https://www.rededuque.com.br/app/home.php?secur=$cod_retorno&log=1");
                   header("Location: https://adm.bunker.mk/appduque/home.php?secur=$cod_retorno&log=1");
                                
                }else{
                                          
                         $msgErro =  'usuario e senha invalido'; 
                      //   header("Location: http://bunker.mk/appduque/login.php");
                }    
                
                
	}
	
    //Rotina de logoff
	if($_GET['logoff']=='1'){
        //session_destroy();
        session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
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
		$tituloPagina = "Cadastro";
		include "menu.php"; 
		?>	
		
        <div class="container">

            <div class="push10"></div> 
			
			<?php			
			//http://www.rededuque.com.br/motoristas/verifica_cpf_app.php
			/*
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
			curl_setopt($ch, CURLOPT_URL, 'https://www.rededuque.com.br/motoristas/verifica_inicial_app.php');
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			echo curl_exec($ch);
			*/
			?>
			
			<iframe width="100%"  height="2500" frameborder="0" src="https://www.rededuque.com.br/motoristas/verifica_inicial_app.php?bunker=1"></iframe>			

           <div class="push30"></div> 

        </div> <!-- /container -->	
		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>
// $("iframe").load(function(){
  
//   $(this).contents().appendTo('body');

// });

</script>