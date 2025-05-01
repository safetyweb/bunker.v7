<div class="actions">
	<?php
	if (isset($formBack)) {
		if (isset($cod_empresa)) {
			echo '<a href="action.do?mod=' . fnEncode($formBack) . '&id=' . fnEncode($cod_empresa) . ' " class="shortCut" data-toggle="tooltip" data-placement="top" data-original-title="Voltar" id="shortBCK"><i class="fal fa-arrow-left" aria-hidden="true"></i></a>';
		} else {
			echo '<a href="action.do?mod=' . fnEncode($formBack) . '" class="shortCut" data-toggle="tooltip" data-placement="top" data-original-title="Voltar" id="shortBCK"><i class="fal fa-arrow-left" aria-hidden="true"></i></a>';
		}
	}
	?>
	<a href="<?php echo $cmdPage; ?>" class="shortCut" data-toggle="tooltip" data-placement="top" data-original-title="Atualizar" id="shortRFH"><i class="fal fa-sync" aria-hidden="true"></i></a>

	<!--
	<a href="javascript:;" class="shortCut" data-toggle="tooltip" data-placement="top" data-original-title="Favoritos" id="shortFAV"><i class="fal fa-star" aria-hidden="true"></i></a>
	-->

	<?php
	if ($_SESSION["SYS_COD_HOME"] != 0) {
	?>
		<a href="action.do?mod=<?php echo fnEncode($_SESSION["SYS_COD_HOME"]); ?>" class="shortCut" data-toggle="tooltip" data-placement="top" data-original-title="Home" id="shortHOM"><i class="fal fa-home" aria-hidden="true"></i></a>
	<?php
	}
	$modPin = fnDecode($_GET['mod']);
	$sqlPin = "SELECT * FROM LINKS_WORKSPACE WHERE COD_USUARIO = '{$_SESSION['SYS_COD_USUARIO']}' AND COD_SISTEMA = '{$_SESSION['SYS_COD_SISTEMA']}' AND COD_MODULO = '{$modPin}'";

	$arrPin = mysqli_query(connTemp($_SESSION['SYS_COD_EMPRESA'], ''), $sqlPin);
	if ($arrPin) {
		$qrPin = mysqli_fetch_assoc($arrPin);
		$cod_link = fnLimpaCampoZero(@$qrPin['COD_LINK']);
		if ($cod_link == 0) {
			$TEXTO = "Fixar na Home";
			$ICONE = "fal fa-thumbtack";
		} else {
			$TEXTO = "Remover da Home";
			$ICONE = "fas fa-thumbtack";
		}
	} else {
		$TEXTO = "Remover da Home";
		$ICONE = "fas fa-thumbtack";
	}

	?>

	<a href="action.do?mod=<?php echo fnEncode(1479); ?>" class="shortCut" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="Ajuda" id="shortHELP"><i class="fal fa-question-circle" aria-hidden="true"></i></a>
	<a href="javascript:void(0)" onclick="pin_workspace()" class="shortCut" id="btnPin" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $TEXTO; ?>"><i class="<?php echo $ICONE; ?>" aria-hidden="true" id="iconePin"></i></a>
</div>
<style>
	a.shortCut {
		color: #2c3e50;
		margin: 0 3px 0 3px;
	}
</style>