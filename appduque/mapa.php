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
		@$tituloPagina = "Mapa";
		include "menu.php"; 
		?>	

			<div class="push20"></div> 
            <iframe id="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d730.6639713989896!2d-46.69487108650956!3d-23.610642789191363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce50cb8acd5ae9%3A0x282e74c2932645f!2sAv.+Engenheiro+Lu%C3%ADs+Carlos+Berrini%2C+1585+-+Cidade+Mon%C3%A7%C3%B5es%2C+S%C3%A3o+Paulo+-+SP%2C+04571-011!5e0!3m2!1sen!2sbr!4v1505790951118" frameborder="0" style="border:0" allowfullscreen ></iframe>
	   

		<?php include "jsLib.php"; ?>		

    </body>
	<script type="text/javascript">
		jQuery(document).ready(function( $ ) {
			$('#mapa').height($(document).height() - $(".navbar").height());
		});
	</script> 
</html>