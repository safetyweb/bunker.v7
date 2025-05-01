<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$msgRetorno = "";
$msgTipo = "";
$DestinoPg = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$ARRAY_VENDEDOR1 = "";
$ARRAY_VENDEDOR = "";
$arrayAutorizado = "";
$CarregaMaster = "";
$qrListaPersonas = "";
$NOM_ARRAY_NON_VENDEDOR = "";
$personaaAtivo = "";
$personaCongela = "";
$personaaAtualiza = "";
$lojaLoop = "";
$nomeLoja = "";
$NOM_ARRAY_UNIDADE = "";
$usuario = "";


//echo fnDebug('true');
$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(@$_GET['id']);

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



				<div class="col-md-12">

					<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo $DestinoPg; ?>&id=0">

						<table class="table table-bordered table-striped table-hover tablesorter buscavel">
							<thead>
								<tr>
									<th class="{sorter:false}"></th>
									<th>Nome da Persona</th>
									<th class="text-center"><i class='fas fa-users'></i></th>
									<th class="text-center">Unidade</th>
									<th class="text-center">Usuário Cad.</th>
									<th class="text-center {sorter:false}">Ativa</th>
									<th class="text-center {sorter:false}">Bloqueada</th>
									<th>Data de Criação</th>
									<th>Última Alteração</th>
								</tr>
							</thead>

							<tbody id="div_refreshPersona">

								<?php

								$ARRAY_UNIDADE1 = array(
									'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
									'cod_empresa' => $cod_empresa,
									'conntadm' => $connAdm->connAdm(),
									'IN' => 'N',
									'nomecampo' => '',
									'conntemp' => '',
									'SQLIN' => ""
								);
								$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

								$ARRAY_VENDEDOR1 = array(
									'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
									'cod_empresa' => $cod_empresa,
									'conntadm' => $connAdm->connAdm(),
									'IN' => 'N',
									'nomecampo' => '',
									'conntemp' => '',
									'SQLIN' => ""
								);
								$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
								$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

								$sql = "CALL SP_BUSCA_PERSONA($cod_empresa, 'S');";

								// fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


								if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
									$CarregaMaster = '1';
								} else {
									$CarregaMaster = '0';
								}
								$count = 0;
								while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

									$count++;

									$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaPersonas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
									if ("S" == "S") {
										$personaaAtivo = "<i class='fas fa-check' aria-hidden='true'></i>";
									} else {
										$personaaAtivo = "";
									}

									if ($qrListaPersonas['LOG_CONGELA'] == "S") {
										$personaCongela = "<i class='far fa-pause-circle' aria-hidden='true'></i>";
									} else {
										$personaCongela = "";
									}

									if ($qrListaPersonas['LOG_RESTRITO'] == "S") {
										$personaaAtualiza = "<i class='fas fa-check' aria-hidden='true'></i>";
									} else {
										$personaaAtualiza = "";
									}
									// fnEscreve($qrListaPersonas['LOG_RESTRITO']);
									//echo fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"],$_SESSION["SYS_COD_EMPRESA"]);
									//$qrListaPersonas['COD_UNIVED']

									$lojaLoop = $qrListaPersonas['COD_UNIVEND'];
									if ($lojaLoop == 9999) {
										$nomeLoja = "Todas";
									} else {
										$NOM_ARRAY_UNIDADE = (array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
										$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
									}

									if ($qrListaPersonas['COD_USUCADA'] == 0 || $qrListaPersonas['COD_USUCADA'] == '') {
										$usuario = "";
									} else {
										$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
									}

									if ($CarregaMaster == '1') {


								?>
										<tr>
											<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm("<?= $count ?>")'></td>
											<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
											<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
											<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
											<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
											<td class="text-center"><small><?php echo $usuario; ?></small></td>
											<td class='text-center'><?php echo $personaaAtivo; ?></td>
											<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
											<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
											<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
										</tr>
										<?php
									} else {


										if (recursive_array_search($qrListaPersonas['COD_UNIVEND'], $arrayAutorizado) !== false) {
										?>
											<tr>
												<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm("<?= $count ?>")'></td>
												<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
												<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
												<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
												<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
												<td class="text-center"><small><?php echo $usuario; ?></small></td>
												<td class='text-center'><?php echo $personaaAtivo; ?></td>
												<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
												<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
												<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
											</tr>
									<?php
										}
									}

									?>

									<input type='hidden' id="ret_IDP_<?= $count ?>" value="<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>">
									<input type='hidden' id="ret_ID_<?= $count ?>" value="<?= fnEncode($cod_empresa) ?>">

								<?php

								}

								?>

							</tbody>
						</table>
					</form>
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
		// alert();				
	}
</script>