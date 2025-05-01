<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_campanha_principal = "";
$msgRetorno = "";
$msgTipo = "";
$DestinoPg = "";
$qrListaCampanha = "";
$campanhaAtivo = "";
$campanhaAtualiza = "";
$RedirectPg = "";


$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(@$_GET['id']);

$sql = "SELECT NOM_FANTASI, TIP_CAMPANHA
	FROM empresas where COD_EMPRESA = $cod_empresa ";

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
$tip_campanha_principal = $qrBuscaEmpresa['TIP_CAMPANHA'];


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

				<div class="col-lg-12">

					<div class="no-more-tables">

						<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">

							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>Nome da Campanha</th>
										<th class="text-center"><i class='fas fa-users'></i></th>
										<th class="text-center">Ativa</th>
										<th class="text-center">At. Automática</th>
										<th>Data de Criação</th>
										<th>Última Alteração</th>
										<th></th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "select A.*,
															IFNULL((SELECT B.NUM_PESSOAS FROM CAMPANHAREGRA B where B.COD_CAMPANHA = A.COD_CAMPANHA),0) as NUM_PESSOAS
															from campanha A where A.cod_empresa = " . $cod_empresa . " 
															and A.tip_campanha != " . $tip_campanha_principal . " 
															order by A.DES_CAMPANHA ";
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if ($qrListaCampanha['LOG_ATIVO'] == "S") {
											$campanhaAtivo = "<i class='fas fa-check' aria-hidden='true'></i>";
										} else {
											$campanhaAtivo = "";
										}

										if ($qrListaCampanha['LOG_ATUALIZA'] == "S") {
											$campanhaAtualiza = "<i class='fas fa-check' aria-hidden='true'></i>";
										} else {
											$campanhaAtualiza = "";
										}

									?>

										<tr>
											<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA'];; ?></td>
											<td class="text-center"><small><?php echo number_format($qrListaCampanha['NUM_PESSOAS'], 0, ",", "."); ?></td>
											<td class='text-center'><?php echo $campanhaAtivo; ?></td>
											<td class='text-center'><?php echo $campanhaAtualiza; ?></td>
											<td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
											<td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
											<td class='text-center'>
												<?php if ($qrListaCampanha['TIP_CAMPANHA'] == 20) { ?>
													<a class='btn btn-xs btn-success' href="action.do?mod=<?php echo fnEncode(1413); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>">Acessar Relatórios </a>
												<?php } ?>
											</td>
										</tr>

									<?php
									}

									?>

								</tbody>
							</table>

							<div class="push50"></div>

							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="codBusca" id="codBusca" value="">
							<input type="hidden" name="nomBusca" id="nomBusca" value="">

						</form>

					</div>

				</div>

				<div class="push"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>

<?php
if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}
?>

<script type="text/javascript">
	function retornaForm(index) {

		$("#codBusca").val($("#ret_ID_" + index).val());
		$("#codBusca").val($("#ret_IDP_" + index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_ID_" + index).val() + '&idP=' + $("#ret_IDP_" + index).val());
		$('#formLista').submit();
	}
</script>