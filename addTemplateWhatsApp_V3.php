<?php 	
	$cod_empresa = $_GET['id']; 
	$cod_template = $_GET['idT'];
	$cod_desafio = $_GET['idc'];
	$agenda = $_GET['agenda'];
?>
<iframe src="/whatsapp-editor/editorTemplate.php?id=<?php echo $cod_empresa;?>&idc=<?=$cod_desafio?>&idT=<?php echo $cod_template;?>&agenda=<?=$agenda?>" frameborder="0" style="width: 100%; height: 100%" ></iframe>