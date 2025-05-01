<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$sist = "";
$msgRetorno = "";
$msgTipo = "";
$formBack = "";
$abaModulo = "";
$arrayQuery = [];
$qrSistema = "";
$countVersao = "";
$qrBuscaVersao = "";
$u_COD_AREABLOCK = "";
$col = "";
$qrBusca = "";
$id = "";
$qrBuscaModulos = "";
$mostraMulti = "";


$hashLocal = mt_rand();
$sist = (@$_GET["sist"] <> "" ? fnDecode(@$_GET["sist"]) : "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
	}
}

//fnMostraForm();

?>
<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">

<style>
	.transparency {
		opacity: 0.5 !important;
	}
</style>

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

				<?php $abaModulo = 1121;
				include "abasModulosMarka.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<?php if (@$sist <> "") { ?>

						<table class="table table-bordered table-hover table-header-rotated">
							<thead>
								<tr>
									<th>
										<?php
										$sql = "SELECT DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA=0" . $sist;
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
										$qrSistema = mysqli_fetch_assoc($arrayQuery);
										echo $qrSistema["DES_SISTEMA"];
										?>
									</th>
									<?php

									$sql = "select * from sistema_versao WHERE COD_SISTEMA=0" . @$sist . " order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									$countVersao = mysqli_num_rows($arrayQuery);
									while ($qrBuscaVersao = mysqli_fetch_assoc($arrayQuery)) {
										echo "<th class='text-center'>" . $qrBuscaVersao["NOM_VERSAO"] . "</th>";
									}

									?>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT A.COD_AREABLOCK,A.NOM_AREABLOCK,V.COD_VERSAO,V.NOM_VERSAO,M.COD_MATRIZ,IF(M.COD_MATRIZ IS NULL,'','checked') CHECKED
										FROM sistema_versao V
										LEFT JOIN modulosmarka_area A ON (A.COD_SISTEMA=V.COD_SISTEMA)
										LEFT JOIN matriz_bloqueio M ON (M.COD_SISTEMA=V.COD_SISTEMA AND M.COD_VERSAO=V.COD_VERSAO AND M.COD_AREABLOCK=A.COD_AREABLOCK)
										WHERE A.COD_SISTEMA=0" . @$sist . "
										ORDER BY A.NUM_ORDENAC,V.NUM_ORDENAC";

								$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

								$u_COD_AREABLOCK = 0;
								$col = 0;
								while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
									if ($u_COD_AREABLOCK <> $qrBusca["COD_AREABLOCK"]) {
										echo "<tr>";
										echo "<td>";
										echo $qrBusca["NOM_AREABLOCK"];
										echo "</td>";
										$col = 0;
									}
									$u_COD_AREABLOCK = $qrBusca["COD_AREABLOCK"];
									$col++;

									echo "<td class='text-center transparency'>";
									$id = "CHECK_" . $qrBusca["COD_VERSAO"] . "_" . $qrBusca["COD_AREABLOCK"];
								?>
									<div class="checkbox checkbox-primary">
										<input class="styled" type="checkbox" name="<?= $id ?>" id="<?= $id ?>" onclick="checkAcao(<?= $qrBusca["COD_VERSAO"] ?>,<?= $qrBusca["COD_AREABLOCK"] ?>,this);" <?= $qrBusca["CHECKED"] ?>>
										<label for="<?= $id ?>">&nbsp;</label>
									</div>
								<?php
									echo "</td>";

									if ($col >= $countVersao) {
										echo "</tr>";
									}
								}
								?>

							</tbody>
						</table>



						<div class="push10"></div>
						<hr>

						<div class="push50"></div>


					<?php } else { ?>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Nome do Sistema</th>
												<th>Abreviação do Sistema</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT SS.*, MD.NOM_MODULOS FROM SISTEMAS SS 
											LEFT JOIN MODULOS MD ON MD.COD_MODULOS = SS.COD_HOME
											ORDER BY SS.DES_SISTEMA";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaModulos['LOG_MULTEMPRESA'] == 'S') {
													$mostraMulti = '<i class="fal fa-check-square-o" aria-hidden="true"></i>';
												} else {
													$mostraMulti = '';
												}

												echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick=\"abreSistema('" . fnEncode($qrBuscaModulos['COD_SISTEMA']) . "')\"></th>
											  <td>" . $qrBuscaModulos['COD_SISTEMA'] . "</td>
											  <td>" . $qrBuscaModulos['DES_SISTEMA'] . "</td>
											  <td>" . $qrBuscaModulos['DES_ABREVIA'] . "</td>
											</tr>
											";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

					<?php } ?>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	function retornaForm(index) {
		$("#formulario #COD_AREABLOCK").val($("#ret_COD_AREABLOCK_" + index).val());
		$("#formulario #COD_GRUPOMODMK").val($("#ret_COD_GRUPOMODMK_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_AREABLOCK").val($("#ret_NOM_AREABLOCK_" + index).val());
		$("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	function abreSistema(sist) {
		window.location.href = "action.do?mod=<?= @$_GET["mod"] ?>&sist=" + sist;
	}

	function checkAcao(idVersao, idArea, campo) {
		//alert("fase: "+ idVersao +"\nação: "+ idArea );
		//alert("campo: "+ campo.id );
		var opcao = "";
		if ($(campo).prop('checked') == true) {
			//alert("selected");
			opcao = "CAD";
		} else {
			//alert("deselect");
			opcao = "EXC";
		}
		$.ajax({
			type: "GET",
			url: "ajxMatrizBloqueio.php",
			data: {
				ajx1: idVersao,
				ajx2: idArea,
				ajx3: "<?= $sist; ?>",
				ajx4: opcao
			},
			beforeSend: function() {
				//$('#div_Matriz').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				//$("#div_Matriz").html(data); 
				//alert(data);
			},
			error: function() {
				//$('#div_Matriz').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});

	}
</script>