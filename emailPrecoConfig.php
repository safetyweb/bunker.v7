<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abasComunicacao = "";
$sqlSis = "";
$arraySistemas = [];
$qrBuscaSistemas = "";
$arrayQueryTipo = [];
$qrTipo = "";
$personaliza = "";
$sqlLinha = "";
$arrayQueryLinha = [];
$qrLinha = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				//menu Configuração
				$abasComunicacao = 1458;
				include "abasComunicacao.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<div class="push20"></div>

					<?php

					$sqlSis = "SELECT distinct CP.COD_SISTEMA, SIS.DES_SISTEMA from COMUNICACAO_PRECO CP
													INNER JOIN SISTEMAS SIS ON SIS.COD_SISTEMA = CP.COD_SISTEMA";
					$arraySistemas = mysqli_query($connAdm->connAdm(), $sqlSis);

					$count = 0;
					while ($qrBuscaSistemas = mysqli_fetch_assoc($arraySistemas)) {

					?>

						<div class="row">

							<div class="col-md-12">
								<h3><?= $qrBuscaSistemas['DES_SISTEMA'] ?></h3>
								<hr>
							</div>

							<?php



							$sql = "SELECT COD_CANALCOM, DES_CANALCOM, LOG_PERSONALIZA FROM CANAL_COMUNICACAO ORDER BY NUM_ORDENAC ";
							$arrayQueryTipo = mysqli_query($connAdm->connAdm(), trim($sql));
							while ($qrTipo = mysqli_fetch_assoc($arrayQueryTipo)) {

								if ($qrTipo['LOG_PERSONALIZA'] == 'S') {
									$personaliza = "<span class='fas fa-check text-success'></span>";
								} else {
									$personaliza = "<span class='fas fa-times text-danger'></span>";
								}
							?>



								<div class="col-md-4">

									<div class="col-md-12">

										<div class="no-more-tables">

											<table class="table table-bordered table-striped table-hover">


												<thead>
													<tr>
														<th class="text-center" rowspan="2">QUANTIDADE</th>
														<th class="text-center" colspan="2"><?= $qrTipo['DES_CANALCOM'] ?></th>
													</tr>
													<tr>
														<th class="text-center">UNITÁRIO</th>
														<th class="text-center">VALIDADE</th>
														<th class="text-center">TOTAL</th>
													</tr>
												</thead>

												<tbody align="center">
													<?php

													$sqlLinha = "SELECT CF.NOM_FAIXA,
															    							(CF.NUM_FAIXAFIM - CF.NUM_FAIXAINI) AS QTD_TOTAL,
															    							CP.VAL_UNITARIO,
															    							CP.COD_PRECO,
															    							CP.VAL_TOTAL,
															    							CP.QTD_DIASVALID
															    					 FROM COMUNICACAO_PRECO CP
															    					 LEFT JOIN COMUNICACAO_FAIXAS CF ON CF.COD_COMFAIXA = CP.COD_COMFAIXA
															    					 WHERE CP.COD_CANALCOM = $qrTipo[COD_CANALCOM]
															    					 AND CP.COD_SISTEMA = $qrBuscaSistemas[COD_SISTEMA]";
													//echo $sqlLinha;		
													$arrayQueryLinha = mysqli_query($connAdm->connAdm(), trim($sqlLinha));
													// var_dump($sqlLinha);
													while ($qrLinha = mysqli_fetch_assoc($arrayQueryLinha)) {

														// fnEscreve($qrLinha['LOG_PERSONALIZA']);


													?>


														<tr>

															<td><b><?= $qrLinha['NOM_FAIXA'] ?></b></td>

															<td class="text-center vl">
																<b>
																	<a href="#" class="editable"
																		data-type='text'
																		data-title='Editar Valor' data-pk="<?= $qrLinha['COD_PRECO'] ?>"
																		data-name="VAL_UNITARIO"
																		data-qtd="<?= $qrLinha['QTD_TOTAL'] ?>"
																		data-count="1"><?= fnValor($qrLinha['VAL_UNITARIO'], 3) ?>
																	</a>
																</b>
															</td>

															<td class="text-center">
																<b>
																	<a href="#" class="editable"
																		data-type='text'
																		data-title='Editar Validade' data-pk="<?= $qrLinha['COD_PRECO'] ?>"
																		data-name="QTD_DIASVALID"
																		data-qtd="0"
																		data-count="1"><?= fnValor($qrLinha['QTD_DIASVALID'], 0) ?>
																	</a>
																</b> <small>dias</small>
															</td>

															<td class="text-right">R$ <span id="VAL_TOTAL_<?= $qrLinha['COD_PRECO'] ?>"><?= fnValor($qrLinha['VAL_TOTAL'], 2) ?></span></td>

														</tr>

													<?php
													}
													?>

												</tbody>

												<tfoot>

													<tr>
														<th class="text-center">PERSONALIZAÇÃO</th>
														<th class="text-center" colspan="2"><?= $personaliza ?></th>
													</tr>
												</tfoot>

											</table>

										</div>

									</div>

								</div>

							<?php
								$personaliza = "";
							}
							?>

						</div>

						<div class="push20"></div>

					<?php

					}

					?>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$(function() {

		$('.vl .editable-input .input-sm').mask('000.000.000.000.000,000', {
			reverse: true
		});

		$('.editable').editable({
			url: 'ajxPrecoConfig.php',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				params.qtd = $(this).data('qtd');
				return params;
			},
			success: function(data) {
				id = $(this).data('pk');
				$('#VAL_TOTAL_' + id).text(data);
				// console.log(data);
			}
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		if ($("#ret_LOG_PERSONALIZA_" + index).val() == 'S') {
			$('#formulario #LOG_PERSONALIZA').prop('checked', true);
		} else {
			$('#formulario #LOG_PERSONALIZA').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>