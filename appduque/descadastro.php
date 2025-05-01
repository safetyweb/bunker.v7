<?php 
include './_system/_functionsMain.php'; 

  
  
    //Rotina de logoff
  if($_GET['logoff']=='1'){
        //session_destroy();
        //session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
  }

?>
  <script type="text/javascript">
    document.cookie = "login=";
    document.cookie = "senha=";
  </script>
<?php

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

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Descadastro";
		// include "menu.php"; 
		?>

    <style type="text/css">
      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -30px;
        position: relative;
        z-index: 2;
      }
    </style>	
		

      <form data-toggle="validator" role="form2" method="post" id="formulario">

        <div class="container">

          <div class="row text-center">

            <div class="col-xs-12">
              <h3>Descadastro realizado com sucesso</h3>
              <div class="push20"></div>
              <a href="https://adm.bunker.mk/appduque/" class="btn btn-primary">Voltar à tela inicial</a>
            </div>
            

          </div>
            

        </div> <!-- /container -->

    </form>	

		<?php include 'jsLib.php';?>
    </body>
</html>
