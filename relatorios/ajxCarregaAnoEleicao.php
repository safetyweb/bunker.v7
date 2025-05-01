<?php 

	include '../_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$des_ano = fnLimpaCampoZero($_REQUEST['DES_ANO']);

	$sql = "SELECT CD_CARGO, DS_CARGO FROM CARGO_ELEICAO 
			WHERE CD_CARGO IN(
			SELECT CD_CARGO FROM ano_cargo_eleicao
			WHERE ANO_ELEICAO = $des_ano)
			ORDER BY DS_CARGO";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

	while ($qrCargos = mysqli_fetch_assoc($arrayQuery)){													
		?>
			<div class="cargo">
				<a class="activeRel" href="javascript:void(0)" onclick="geraFiltro(this,'<?=$qrCargos[CD_CARGO]?>')">&rsaquo; <?php echo $qrCargos['DS_CARGO']?> </a> 
				<div class="push5"></div>
			</div>
		<?php	
	}

?>