<!DOCTYPE html>
<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>

Texto: <input id="text" type="text" style="width:150px" value="Texto Aqui">
Largura: <input id="width" style="width:50px" type="number" value=100>
Altura: <input id="height" style="width:50px" type="number" value=100>
<button onClick="geraQRCode()">Gerar QR Code</button>

<br>
<br>

<div id="qrcodeCanvas"></div>
<script>
	geraQRCode();
	function geraQRCode(){
		$("#qrcodeCanvas").html("");
		jQuery('#qrcodeCanvas').qrcode({
			text: $("#text").val(),
			width: $("#width").val(),
			height: $("#height").val()
		});	
	}
</script>

</body>
</html>
