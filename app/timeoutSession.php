<script>
	let timeout = setTimeout(timeOutApp, 1800000);;
	

	function timeOutApp() {
		alert("Sua sessão expirou. Faça o login novamente.");
		window.location.replace("https://<?=$_SERVER['SERVER_NAME']?>/app/intro.do?key=<?=$_GET['key']?>");
	}
</script>