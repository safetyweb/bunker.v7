<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$msgRetorno = "";
$msgTipo = "";
$qrBuscaLista = "";
$mostraAtivo = "";
$mostraPrincipal = "";

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
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
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
										<th width="40"></th>
										<th>Pesquisa </th>
										<th>Validade</th>
										<th>Respostas</th>
										<th>Última visita</th>
										<th>Ativo</th>
										<th>NPS Principal</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "SELECT 
																PESQUISA.*,
																(SELECT 
																		COUNT(*)
																	FROM
																		DADOS_PESQUISA_ITENS
																	WHERE
																		COD_PERGUNTA = (SELECT 
																				COD_REGISTR
																			FROM
																				MODELOPESQUISA
																			WHERE
																				COD_TEMPLATE = PESQUISA.COD_PESQUISA AND LOG_PRINCIPAL = 'S' AND COD_EXCLUSA IS NULL)) AS RESPOSTAS,
																(SELECT MAX(DT_HORAINICIAL) FROM DADOS_PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_PESQUISA = PESQUISA.COD_PESQUISA) AS ULT_VISITA
															FROM
																PESQUISA
															WHERE
																COD_EMPRESA = $cod_empresa
															ORDER BY PESQUISA.DES_PESQUISA";
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrBuscaLista = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if ($qrBuscaLista['LOG_ATIVO'] == 'S') {
											$mostraAtivo = '<i class="fa fa-check" aria-hidden="true"></i>';
										} else {
											$mostraAtivo = '';
										}


										if ($qrBuscaLista['LOG_PRINCIPAL'] == 'S') {
											$mostraPrincipal = '<i class="fa fa-check" aria-hidden="true"></i>';
										} else {
											$mostraPrincipal = '';
										}


										echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaLista['DES_PESQUISA'] . "</td>
															  <td>" . fnFormatDate($qrBuscaLista['DAT_INI']) . " a " . fnFormatDate($qrBuscaLista['DAT_FIM']) . "</td>
															  <td>" . $qrBuscaLista['RESPOSTAS'] . "</td>
															  <td>" . fndatashort($qrBuscaLista['ULT_VISITA']) . "</td>
															  <td class='text-center'>" . $mostraAtivo . "</td>
															  <td class='text-center'>" . $mostraPrincipal . "</td>
															</tr>
															<input type='hidden' id='ret_IDP_" . $count . "' value='" . fnEncode($qrBuscaLista['COD_PESQUISA']) . "'>
															<input type='hidden' id='ret_ID_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
															";
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

<?php

if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}

?>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#codBusca").val($("#ret_ID_" + index).val());
		$("#codBusca").val($("#ret_IDP_" + index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_ID_" + index).val() + '&idP=' + $("#ret_IDP_" + index).val());
		$('#formLista').submit();
	}
</script>