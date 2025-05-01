	<?php include "jsLib.php"; ?>

	<script type="text/javascript">

		<?php 
		if($_GET[cds] == ""){
			if($tituloPagina != "Faça seu login"){ 
		?>
		
			let timeout = setTimeout(timeOutApp, 1800000);
		

			function timeOutApp() {
				alert("Sua sessão expirou. Faça o login novamente.");
				window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/intro.do?key=<?=$_GET['key']?>");
			}

		<?php 
			}
		}
		?>

	</script>

    </body>
</html>