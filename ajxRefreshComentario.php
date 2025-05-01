<?php include "_system/_functionsMain.php";

$buscaAjx1 = fnLimpacampo(@$_GET['ajx1']);
$buscaAjx2 = fnLimpacampo(@$_GET['ajx2']);

$cod_chamado = fnDecode($buscaAjx1);
$mod = fnDecode($buscaAjx2);
$cod_comentario = fnLimpaCampoZero(fnDecode(@$_GET['ajx3']));

// fnEscreve($cod_comentario);

if ($cod_comentario != 0) {
	$sql = "DELETE FROM SAC_COMENTARIO 
			WHERE COD_COMENTARIO = $cod_comentario
			AND COD_CHAMADO = $cod_chamado";

	// fnEscreve($sql);

	mysqli_query($connAdmSAC->connAdm(), $sql);
}

//setando locale da data
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if ($mod == 1289) $ANDtipo = " AND TP_COMENTARIO = 1 ";
else $ANDtipo = " ";

$sql = "SELECT SC.*, SS.DES_STATUS FROM SAC_COMENTARIO SC
							LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS = SC.COD_STATUS
							WHERE SC.COD_CHAMADO = $cod_chamado
							$ANDtipo
							ORDER BY SC.DAT_CADASTRO DESC
								";

//fnEscreve($sql);

$arrayQueryComment = mysqli_query($connAdmSAC->connAdm(), $sql);

while ($qrComment = mysqli_fetch_assoc($arrayQueryComment)) {
	$interno = "";
	//fnEscreve('entrou while');
	$mes = strtoupper(strftime('%B', strtotime($qrComment["DAT_CADASTRO"])));
	$mes = substr("$mes", 0, 3);

	$sqlUsuarios = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrComment[COD_USUARIO]";
	//fnEscreve($sqlUsuarios);
	$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));

	if ($qrComment['TP_COMENTARIO'] == 2) {
		$interno = " <span class='f12'> (INTERNO) </span>";
	}

?>
	<div class="cd-timeline__container">
		<div class="cd-timeline__block<?php echo $qrComment['COD_COR']; ?>">
			<div class="cd-timeline__img"></div>
			<div class="cd-timeline__content">

				<h2><?= $qrNomUsu['NOM_USUARIO'] . $interno ?></h2>

				<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2) { ?>
					<a href="javascript:void(0)" class="pull-right" style="margin-top: -30px;" onclick='excComentario("<?= fnEncode($qrComment["COD_COMENTARIO"]) ?>")'><span class="fas fa-times text-danger"></span></a>
				<?php } ?>

				<div class="push20"></div>
				<div class="col-sm-12">

					<?php echo html_entity_decode($qrComment['DES_COMENTARIO']); ?>

				</div>
				<span class="cd-timeline__date"><?php echo strftime('%d ', strtotime($qrComment["DAT_CADASTRO"])) . "" . $mes; ?>
					<br>
					<span class="hora"><?php echo date("H:i", strtotime($qrComment["DAT_CADASTRO"])); ?></span>
					<br>
					<span><small><b><?= @$qrComment['ABV_STATUS'] ?></b></small></span>
				</span>
			</div>
		</div>
	</div>
<?php
}
?>