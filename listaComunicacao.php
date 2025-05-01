<?php

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];


?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="push30"></div>

				<div class="col-md-4">

					<h3 style="margin-top:0;">Canais de Comunicação</h3>

					<div class="push10"></div>

					<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>

					<?php } ?>
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1663); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Entregabilidade E-Mail </a> <br />
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1662); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Entregabilidade SMS </a> <br />
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1799); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Entregabilidade SMS - Unidade </a> <br />
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1770); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Status de Envio SMS </a> <br />
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1591); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Histórico de SMS </a> <br />
					<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1835); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Histórico de SMS <small>(Campanhas Desativadas)</small> <br />
						<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1999); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Histórico de Envio WhastApp </a> <br />
						<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1883); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Histórico de Push </a> <br />
						<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(2071); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Entregabilidade PUSH </a> <br />
						<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(2070); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Entregabilidade PUSH - Unidade </a> <br />
						<a class="activeRel" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(2089); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Fila de Envio </a> <br />
						<div class="push5"></div>

				</div>

				<div class="col-md-4">

					<h3 style="margin-top:0;">Blacklist</h3>

					<div class="push10"></div>

					<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1546); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; E-Mail </a>
					<div class="push5"></div>

				</div>

				<div class="col-md-4">

					<h3 style="margin-top:0;">Opt Out</h3>

					<div class="push10"></div>

					<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1662); ?>&id=<?php echo fnEncode($cod_empresa); ?>">&rsaquo; SMS </a>
					<div class="push5"></div>

					<?php
					if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {
					?>

						<a class="activeRel" href="action.do?mod=<?php echo fnEncode(1666); ?>">&rsaquo; SMS (ADM) </a>
						<div class="push5"></div>

					<?php
					}
					?>

				</div>

				<div class="push100"></div>
				<div class="push100"></div>
				<div class="push100"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#codBusca").val($("#ret_ID_" + index).val());
		$("#codBusca").val($("#ret_IDC_" + index).val());

		if (index == 0) {
			$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1234); ?>&id=' + $("#ret_ID_" + index).val() + '&idC=' + $("#ret_IDC_" + index).val());
		} else if (index == 1) {
			$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1235); ?>&id=' + $("#ret_ID_" + index).val() + '&idC=' + $("#ret_IDC_" + index).val());
		}


		$('#formLista').submit();
	}
</script>