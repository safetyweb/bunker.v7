<?php

include "_system/_functionsMain.php";
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));


?>
<center>
    <p>Apresente seu código a um atendente para resgatar seu prêmio</p>
    <div id="qrcodeCanvas"></div>
</center>


<script type="text/javascript" src="libs/jquery-qrcode-master/src/jquery.qrcode.js"></script>
<script type="text/javascript" src="libs/jquery-qrcode-master/src/qrcode.js"></script>
<script>
    $("#qrcodeCanvas").html("");
    jQuery('#qrcodeCanvas').qrcode({
        text: "<?= $linkCode ?>",
        width: 150,
        height: 150
    });
</script>