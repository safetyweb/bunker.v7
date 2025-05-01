<?php
include '../_system/_functionsMain.php';
$hashLocal = mt_rand();
$msgRetorno = "";

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
include '../externo/email/envio_sac.php';
$texto='NOME: '.$_REQUEST['NOM_CLIENTE'].
       '<br>EMAIL: '.$_REQUEST['EMAIL'].
       '<br>Celular: '.$_REQUEST['Celular'].        
       '<br>Menssagem :<br>'.$_REQUEST['Soli'];

$email['email1']='sac@rededuque.com.br';
fnsacmail($email,
          $_REQUEST['NOM_CLIENTE'],
          '<html>'.$texto.'<html>',
          $_REQUEST['NOM_CLIENTE'],
          'REDE DUQUE',
          $connAdm->connAdm(),
          connTemp('19',''),'19');
$msgRetorno = "MENSAGEM ENVIADA COM <b>SUCESSO</b>!";
$msgTipo = 'alert-success';
unset($_POST);
}
?>

﻿<!DOCTYPE html>
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
		@$tituloPagina = "Contato";
		include "menu.php"; 
		?>	

        <div class="container">

            <div class="push50"></div>

            <?php if ($msgRetorno != ""){
            ?>
                <div class="alert <?=$msgTipo?>" role="alert">
                <?php echo $msgRetorno; ?>
                </div>          
            <?php   
            }else{
            ?>
                <div class="push20"></div>
                <div style="height: 3px;"></div>
            <?php
            }
            ?>

            <form id="formulario" class="form-signin" method="post" action="contato.php">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control" placeholder="Nome" required="" autofocus="">
                <div class="push10"></div>                 
                <input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="E-MAIL" required="">
                <div class="push10"></div>                 
                <input type="text" name="Celular" id="Celular" class="form-control" placeholder="Celular" required="">
                <div class="push10"></div> 
                <textarea class="form-control" name ="Soli" id='Soli' placeholder="Solicitação"></textarea>
                <div class="push20"></div> 	

                <button type="submit" class="btn btn-primary btn-block" id="CAD" disabled>ENVIAR MENSAGEM</button>
            </form> 
	   
	   
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>

    <script type="text/javascript">

      $(".form-control").keyup(function(){

        var nome = $("#NOM_CLIENTE").val(),
            email = $("#EMAIL").val(),
            cel = $("#Celular").val(),
            soli = $("#Soli").val();

        if(nome != "" && email != "" && cel != "" && soli != ""){
          $("#CAD").prop('disabled',false);
        }else{
          $("#CAD").prop('disabled',true);
        }

      });

      $("#CAD").click(function(e){
        if($("#CAD").is(":disabled")){
          e.preventDefault();
        }else{
          $("#NOM_CLIENTE").attr('readonly');
          $("#EMAIL").attr('readonly');
          $("#Celular").attr('readonly');
          $("#Soli").attr('readonly');
          $(this).html('<div class="loading" style="width:100%"></div>').addClass('clicked').attr('disabled',true);
          $("#formulario").submit();
        }
      });
    </script>

    </body>
</html>