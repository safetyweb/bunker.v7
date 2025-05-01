<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$log_obrigat = "N";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
		$num_process = fnLimpaCampo($_REQUEST['NUM_PROCESS']);
		$num_conveni = fnLimpaCampo($_REQUEST['NUM_CONVENI']);
		$nom_conveni = fnLimpaCampo($_REQUEST['NOM_CONVENI']);
		$nom_abrevia = fnLimpaCampo($_REQUEST['NOM_ABREVIA']);
		$des_descric = fnLimpaCampo($_REQUEST['DES_DESCRIC']);
		$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
		$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
		$dat_inicinv = fnLimpaCampo($_REQUEST['DAT_INICINV']);
		$dat_fimconv = fnLimpaCampo($_REQUEST['DAT_FIMCONV']);
		$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		//if ($opcao != ''){			
		if ($opcao == '999') {

			$sql = "CALL SP_ALTERA_CONVENIO (
				 '" . $cod_conveni . "', 
				 '" . $cod_empresa . "',
				 '" . $cod_entidad . "', 
				 '" . $num_process . "', 
				 '" . $num_conveni . "',
				 '" . $nom_conveni . "',
				 '" . $nom_abrevia . "',
				 '" . $des_descric . "',
				 '" . fnValorSql($val_valor) . "',
				 '" . fnValorSql($val_contpar) . "',
				 '" . fnDataSql($dat_inicinv) . "',
				 '" . fnDataSql($dat_fimconv) . "',
				 '" . fnDataSql($dat_assinat) . "',
				 '" . $opcao . "'    
			     );";

			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), $sql);

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

//fnMostraForm();
//fnEscreve($cod_checkli);

?>

<link rel="stylesheet" href="css/widgets.css" />
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary">Certificações e Módulos da Jornada de Fidelização Marka</span>
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

				<div class="push20"></div>

				<style>
					.change-icon>.fa+.fa,
					.change-icon:hover>.fa {
						display: none;
					}

					.change-icon:hover>.fa+.fa {
						display: inherit;
					}

					.fa-edit:hover {
						color: #18bc9c;
					}

					.item {
						padding-top: 0;
					}

					.folder {
						height: 30px;
					}

					a,
					a:hover {
						text-decoration: none;
					}
				</style>

				<h3 style="margin: 0 0 40px 15px;">Como vamos melhorar os resultados do seu negócio hoje?</h3>

				<?php

				$sql = "SELECT * FROM GRUPOMODULOSMARKA ORDER BY NUM_ORDENAC";

				$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

				$count = 0;
				while ($qrBuscaCertificacao = mysqli_fetch_assoc($arrayQuery)) {
					$count++;
					$cod_looping = $qrBuscaCertificacao['COD_GRUPOMODMK'];
				?>

					<div class="row">
						<!--
												<div class="col-md-1 text-right">
													<div class="push10"></div>
													<i class="fa <?php echo $qrBuscaCertificacao['DES_ICONE']; ?> fa-lg" style="font-size: 50px"></i>
												</div>
											-->
						<!-- <div class="col-md-12">
							<h3 style="margin: 0 0 5px 20px;"><?php echo $qrBuscaCertificacao['NOM_GRUPOMODMK']; ?></h3>
							<h5 style="margin: 0 0 20px 20px;"><?php echo $qrBuscaCertificacao['DES_GRUPOMODMK']; ?></h5>
						</div> -->
					</div>

					<?php

					$sql1 = "select * from MODULOSMARKA where COD_GRUPOMODMK = $cod_looping order by NUM_ORDENAC";
					$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);

					$count = 0;
					while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery1)) {
						$count++;
					?>
						<div class="col-md-2">

							<div class='tile tile-default shadow change-icon' style='background-color: <?php echo $qrBuscaModulos['DES_COR']; ?>; font-size: 15px;'>
								<a href="javascript:void(0);" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1186); ?>&id=<?php echo $qrBuscaModulos['COD_MODULMK']; ?>&pop=true" data-title="<?php echo $qrBuscaCertificacao['NOM_GRUPOMODMK']; ?>">&nbsp;<i class="fa fa-plus" style="font-size: 15px; line-height: 4px; color: #fff; float: right; margin: 5px 0 0 0;"></i>&nbsp;</a>
								<div class="push"></div>
								<a href="javascript:void(0);" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1186); ?>&id=<?php echo $qrBuscaModulos['COD_MODULMK']; ?>&pop=true" data-title="<?php echo $qrBuscaCertificacao['NOM_GRUPOMODMK']; ?>" style='color: #fff; border: none'>

									<i class="fa <?php echo $qrBuscaModulos['DES_ICONE']; ?> fa-3x" style="line-height: 40px; margin-bottom: 25px; "></i>

									<p class="folder" style="margin-bottom: 5px; font-size: 12px;"><?php echo $qrBuscaModulos['NOM_MODULMK']; ?> </p>
									<p style="font-size: 12px; height: 60px;"><?php echo $qrBuscaModulos['DES_MODULMK']; ?> </p>
								</a>
							</div>

						</div>

					<?php
					}
					?>

					<div class="push30"></div>
				<?php
				}
				?>

			</div>

			<div class="push50"></div>

		</div>

	</div>
</div>
<!-- fim Portlet -->
</div>

</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
		$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_" + index).val());
		$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_" + index).val());
		$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_" + index).val());
		$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_" + index).val());
		$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_" + index).val());
		$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_" + index).val());
		$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_" + index).val());
		$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_" + index).val());
		$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_" + index).val());
		$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>