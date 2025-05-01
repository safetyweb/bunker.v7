<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$cod_erro = '';

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_rateio = fnLimpaCampoZero($_REQUEST['COD_RATEIO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$val_rateio = fnLimpaCampo($_REQUEST['VAL_RATEIO']);
		$tip_periodo = fnLimpaCampo($_REQUEST['TIP_PERIODO']);
		$tip_divisao = fnLimpaCampo($_REQUEST['TIP_DIVISAO']);
		$pct_destaque = fnLimpaCampoZero($_REQUEST['PCT_DESTAQUE']);
		$pct_produtos = fnLimpaCampoZero($_REQUEST['PCT_PRODUTOS']);
		$pct_objetivo = fnLimpaCampoZero($_REQUEST['PCT_OBJETIVO']);
		$pct_fidelidade = fnLimpaCampoZero($_REQUEST['PCT_FIDELIDADE']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "";

			if ($opcao == "CAD") {
				$sqlInsert = "INSERT INTO VALOR_RATEIO(
								COD_EMPRESA,
								COD_UNIVEND,
								COD_USUARIO, 
								DAT_INICIO,
								DAT_FIM,
								VAL_RATEIO,
								TIP_PERIODO,
								TIP_DIVISAO
								) VALUES(
								$cod_empresa,
								$cod_univend,
								$cod_usucada,
								'$dat_ini',
								'$dat_fim',
								" . fnValorSql($val_rateio) . ",
								'$tip_periodo',
								'$tip_divisao'
								)";
				$arrayInsert = mysqli_query($conn, $sqlInsert);
				// mysqli_query(connTemp($cod_empresa,""),$sql);

				if (!$arrayInsert) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
				}


				$sql = "SELECT MAX(COD_RATEIO) COD_RATEIO FROM VALOR_RATEIO where COD_EMPRESA = '" . $cod_empresa . "' ";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
				$qrBusca = mysqli_fetch_assoc($arrayQuery);
				$cod_rateio = $qrBusca["COD_RATEIO"];
			} else if ($opcao == "ALT") {
				$sqlUpdate = "UPDATE VALOR_RATEIO SET
								COD_UNIVEND='$cod_univend',
								DAT_ALTERAC=now(),
								DAT_INICIO='$dat_ini',
								DAT_FIM='$dat_fim',
								VAL_RATEIO=" . fnValorSql($val_rateio) . ",
								TIP_PERIODO='$tip_periodo',
								TIP_DIVISAO='$tip_divisao',
								COD_USUALTE = $cod_usucada
								WHERE COD_RATEIO = $cod_rateio
								";
				$arrayUpdate = mysqli_query($conn, $sqlUpdate);

				if (!$arrayUpdate) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
				}
			} else if ($opcao == 'EXC') {
				$slqExc = "DELETE FROM VALOR_RATEIO 
								WHERE COD_RATEIO = $cod_rateio
								";
				$arrayExc = mysqli_query($conn, $slqExc);

				if (!$arrayExc) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $slqExc, $nom_usuario);
				}
			}


			$sql = "SELECT COUNT(0) QTD FROM MATRIZ_RATEIO where COD_EMPRESA = '" . $cod_empresa . "' AND COD_RATEIO=$cod_rateio";
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
			$qrBusca = mysqli_fetch_assoc($arrayQuery);
			if ($opcao == 'EXC') {
				$slqExcMatriz = "DELETE FROM MATRIZ_RATEIO 
								WHERE COD_RATEIO = $cod_rateio
								";
				$arrayExcMatriz = mysqli_query($conn, $slqExcMatriz);

				if (!$arrayExcMatriz) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $slqExcMatriz, $nom_usuario);
				}
			} elseif ($qrBusca["QTD"] > 0) {

				$sqlMatriz = "UPDATE MATRIZ_RATEIO SET
										COD_ALTERAC = $cod_usucada,
										DAT_ALTERAC = NOW(),
										PCT_DESTAQUE = " . fnValorSql($pct_destaque) . ",
										PCT_PRODUTOS = " . fnValorSql($pct_produtos) . ",
										PCT_OBJETIVO = " . fnValorSql($pct_objetivo) . ",
										PCT_FIDELIDADE = " . fnValorSql($pct_fidelidade) . "
										WHERE COD_RATEIO = $cod_rateio";
				$arrayUpdateMatriz = mysqli_query($conn, $sqlMatriz);

				if (!$arrayUpdateMatriz) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlMatriz, $nom_usuario);
				}
			} else {

				$sqlInsertMatriz = "INSERT INTO MATRIZ_RATEIO(
											COD_RATEIO,
											COD_EMPRESA, 
											COD_UNIVEND, 
											COD_USUARIO,
											PCT_DESTAQUE,
											PCT_PRODUTOS,
											PCT_OBJETIVO,
											PCT_FIDELIDADE
											) VALUES(
											$cod_rateio,
											$cod_empresa,
											$cod_univend,
											$cod_usucada,
											" . fnValorSql($pct_destaque) . ",
											" . fnValorSql($pct_produtos) . ",
											" . fnValorSql($pct_objetivo) . ",
											" . fnValorSql($pct_fidelidade) . "
											)";
				//echo $sql;
				$arrayInsertMatriz = mysqli_query($conn, $sqlInsertMatriz);
				// mysqli_query(connTemp($cod_empresa,""),$sql);

				if (!$arrayInsertMatriz) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsertMatriz, $nom_usuario);
				}
			}

			//mysqli_query($connAdm->connAdm(),trim($sql));				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idU'])))) {
	//busca dados da empresa
	$cod_univend = fnDecode($_GET['idU']);
} else {
	$cod_univend = 0;
}

$ARRAY_UNIDADE1 = array(
	'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

$NOM_ARRAY_UNIDADE = (array_search($cod_univend, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
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
				$abaMetas = 1331;
				include "abasUsuariosMetas.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade</label>
										<?php
										if ($cod_univend > 0) {
										?>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_UNIVEND" id="NOM_UNIVEND" value="<?php echo $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']; ?>">
											<input type="hidden" class="form-control input-sm" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend ?>">
										<?php
										} else {
										?>
											<select data-placeholder="Selecione um Tipo" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<?php
												foreach ($ARRAY_UNIDADE as $k => $unidade) {
													echo "<option value=\"" . $unidade["COD_UNIVEND"] . "\">" . $unidade["nom_fantasi"] . "</option>";
												}
												?>
											</select>

										<?php } ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor de Rateio / Incentivo</label>
										<input type="text" class="form-control input-sm" name="VAL_RATEIO" id="VAL_RATEIO" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Uso / Divisão do Valor</label>
										<div id="relatorioSis">
											<select data-placeholder="Selecione um Tipo" name="TIP_DIVISAO" id="TIP_DIVISAO" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<option value="usu">Por Usuário (individual)</option>
												<option value="und">Por Unidade (rateio)</option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Produtos de Incentivo (%)</label>
										<input type="number" class="form-control input-sm" name="PCT_DESTAQUE" id="PCT_DESTAQUE" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Demais Produtos (%)</label>
										<input type="number" class="form-control input-sm" name="PCT_PRODUTOS" id="PCT_PRODUTOS" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Acima de 20% (%)</label>
										<input type="number" class="form-control input-sm" name="PCT_OBJETIVO" id="PCT_OBJETIVO" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Fidelidade (%)</label>
										<input type="number" class="form-control input-sm" name="PCT_FIDELIDADE" id="PCT_FIDELIDADE" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>


							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Período</label>
										<div id="relatorioSis">
											<select data-placeholder="Selecione um Período" name="TIP_PERIODO" id="TIP_PERIODO" class="chosen-select-deselect requiredChk" required>
												<option></option>
												<option value="per">Por Período</option>
												<option value="dia">Diário</option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>


						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_RATEIO" id="COD_RATEIO" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Data Cadastro</th>
											<th>Unidade</th>
											<th>Usuário</th>
											<th>Data Início</th>
											<th>Data Fim</th>
											<th>Valor</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT VR.*, US.NOM_USUARIO, MR.PCT_DESTAQUE, MR.PCT_PRODUTOS, MR.PCT_OBJETIVO, MR.PCT_FIDELIDADE
													FROM VALOR_RATEIO VR
													LEFT JOIN WEBTOOLS.USUARIOS US
													ON VR.COD_USUARIO = US.COD_USUARIO
													LEFT JOIN MATRIZ_RATEIO MR
													ON MR.COD_RATEIO = VR.COD_RATEIO
													WHERE VR.COD_EMPRESA = $cod_empresa " . ($cod_univend > 0 ? " AND VR.COD_UNIVEND = $cod_univend " : "") . "
													ORDER BY NOM_USUARIO";
										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

										$count = 0;
										while ($qrValor = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											$NOM_ARRAY_UNIDADE = (array_search($qrValor['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
										?>

											<tr>
												<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
												<td><?= $qrValor['COD_RATEIO']; ?></td>
												<td><?= fnDataFull($qrValor['DAT_CADASTRO']); ?></td>
												<td><?= $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']; ?></td>
												<td><?= $qrValor['NOM_USUARIO']; ?></td>
												<td><?= Date("d/m/Y", strtotime($qrValor['DAT_INICIO'])); ?></td>
												<td><?= Date("d/m/Y", strtotime($qrValor['DAT_FIM'])); ?></td>
												<td>R$ <?= fnValor($qrValor['VAL_RATEIO'], 2); ?></td>
											</tr>
											<input type='hidden' id="ret_COD_RATEIO_<?= $count ?>" value="<?= $qrValor['COD_RATEIO']; ?>">
											<input type='hidden' id="ret_DAT_INI_<?= $count ?>" value="<?= date('d/m/Y', strtotime($qrValor['DAT_INICIO'])); ?>">
											<input type='hidden' id="ret_DAT_FIM_<?= $count ?>" value="<?= date('d/m/Y', strtotime($qrValor['DAT_FIM'])); ?>">
											<input type='hidden' id="ret_VAL_RATEIO_<?= $count ?>" value="<?= fnValor($qrValor['VAL_RATEIO'], 2); ?>">
											<input type='hidden' id="ret_TIP_PERIODO_<?= $count ?>" value="<?= $qrValor['TIP_PERIODO']; ?>">
											<input type='hidden' id="ret_TIP_DIVISAO_<?= $count ?>" value="<?= $qrValor['TIP_DIVISAO']; ?>">
											<input type='hidden' id="ret_COD_UNIVEND_<?= $count ?>" value="<?= $qrValor['COD_UNIVEND']; ?>">
											<input type='hidden' id="ret_PCT_DESTAQUE_<?= $count ?>" value="<?= $qrValor['PCT_DESTAQUE']; ?>">
											<input type='hidden' id="ret_PCT_PRODUTOS_<?= $count ?>" value="<?= $qrValor['PCT_PRODUTOS']; ?>">
											<input type='hidden' id="ret_PCT_OBJETIVO_<?= $count ?>" value="<?= $qrValor['PCT_OBJETIVO']; ?>">
											<input type='hidden' id="ret_PCT_FIDELIDADE_<?= $count ?>" value="<?= $qrValor['PCT_FIDELIDADE']; ?>">

										<?php
											if (isset($qrValor['DAT_FIM'])) $data_limite = $qrValor['DAT_FIM'];
											else $data_limite = "";
										}
										if (@$data_limite != "") $data_limite = date("Y-m-d", strtotime(@$data_limite . "+1 days"));
										else $data_limite = "now";
										?>

									</tbody>
								</table>

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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />



<script type="text/javascript">
	var data_limite = '<?php echo $data_limite ?>';

	$(document).ready(function() {
		$('#VAL_RATEIO').mask('000.000.000.000.000,00', {
			reverse: true
		});
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: data_limite,
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});
	});

	function retornaForm(index) {
		$("#formulario #COD_RATEIO").val($("#ret_COD_RATEIO_" + index).val());
		$("#formulario #DAT_INI").val($("#ret_DAT_INI_" + index).val());
		$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$("#formulario #VAL_RATEIO").val($("#ret_VAL_RATEIO_" + index).val());
		$("#formulario #TIP_PERIODO").val($("#ret_TIP_PERIODO_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_DIVISAO").val($("#ret_TIP_DIVISAO_" + index).val()).trigger("chosen:updated");
		$("#formulario #PCT_DESTAQUE").val($("#ret_PCT_DESTAQUE_" + index).val());
		$("#formulario #PCT_PRODUTOS").val($("#ret_PCT_PRODUTOS_" + index).val());
		$("#formulario #PCT_OBJETIVO").val($("#ret_PCT_OBJETIVO_" + index).val());
		$("#formulario #PCT_FIDELIDADE").val($("#ret_PCT_FIDELIDADE_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>