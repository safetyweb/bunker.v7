<?php 

include "_system/_functionsMain.php";

$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);

//setando locale da data
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');	

$sql2 = "SELECT FC.*, CA.DES_CLASSIFICA FROM FOLLOW_CLIENTE FC 
LEFT JOIN CLASSIFICA_ATENDIMENTO CA ON CA.COD_CLASSIFICA = FC.COD_CLASSIFICA 
WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = $cod_cliente
ORDER BY FC.DAT_CADASTR DESC";

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2);
while($qrFollow = mysqli_fetch_assoc($arrayQuery)){

	if($qrFollow['COD_DESAFIO'] != 0){
		$titulo = $qrFollow['DES_CLASSIFICA'];
	}else{
		$titulo = $qrFollow['NOM_FOLLOW'];
	}

	$mes = strtoupper(strftime('%B', strtotime($qrFollow['DAT_CADASTR'])));
	$mes = substr("$mes", 0, 3);
?>


<li class="email">
  <time><small><?php echo strftime('%d ', strtotime($qrFollow['DAT_CADASTR']))."".$mes; ?></small> <big><?php echo date("H:i", strtotime($qrFollow['DAT_CADASTR'])); ?></big></time>
  <article>
    <h3><?=$titulo?></h3>

    <p>
    <?=$qrFollow['DES_COMENT']?>
    </p>
  </article>
</li>

<?php 
}

?>