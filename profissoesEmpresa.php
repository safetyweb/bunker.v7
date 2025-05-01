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
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$delete = "";
$selected = "";
$part = "";
$part1 = "";
$part2 = "";
$insertprof = "";
$arrayInsert = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$sqllista = "";
$arrayQuerylista = [];
$countProfi = "";
$qrBuscaProfissaoAutlista = "";
$arrayRetorno = [];
$countLinha = "";
$qrBuscaProfissaoAut = "";
$checado = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//if ($opcao != '' ){
		if ($opcao != '') {

			//limpa dados anterior tabela
			$delete = "DELETE FROM profissoes_empresa WHERE COD_EMPRESA = $cod_empresa";
			mysqli_query($conn, $delete);

			//monta array - novos campos escolhidos
			foreach (@$_REQUEST['COD_PROFISS'] as $selected) {
				$part = explode('_', $selected);
				list($part1, $part2) = explode('_', $selected);
				$insertprof .= "INSERT INTO profissoes_empresa (COD_PROFISS,DES_PROFISS, COD_EMPRESA) VALUES ('$part1','$part2', '$cod_empresa');";
			}

			//grava novos campos
			$arrayInsert = mysqli_multi_query($conn, $insertprof);

			if (!$arrayInsert) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $insertprof, $nom_usuario);
			}

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnMostraForm();

?>
<style>
	.bigCheck {
		width: 20px;
		height: 20px;
		margin-top: 5px
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

				<?php $abaEmpresa = 1261;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th colspan="4">Lista de Profissões</th>
										</tr>
									</thead>
									<tbody>
										<tr>

											<?php

											$sqllista = "select * from profissoes_empresa order by DES_PROFISS";
											$arrayQuerylista = mysqli_query($conn, $sqllista);

											$countProfi = 0;
											while (@$qrBuscaProfissaoAutlista = mysqli_fetch_assoc($arrayQuerylista)) {
												$countProfi++;
												$arrayRetorno[] = $qrBuscaProfissaoAutlista['COD_PROFISS'];
											}

											$sql = "select * from PROFISSOES order by DES_PROFISS";
											$arrayQuery = mysqli_query($adm, $sql);

											$count = 0;
											$countLinha = 0;
											while ($qrBuscaProfissaoAut = mysqli_fetch_assoc($arrayQuery)) {
												if (recursive_array_search($qrBuscaProfissaoAut['COD_PROFISS'], array_filter($arrayRetorno)) !== false) {
													$checado = "checked";
												} else {
													$checado = "";
												}

												$count++;
												$countLinha++;
											?>
												<td>
													<input type="checkbox" name="COD_PROFISS[]" class="bigCheck" value="<?php echo $qrBuscaProfissaoAut['COD_PROFISS']; ?>_<?php echo $qrBuscaProfissaoAut['DES_PROFISS']; ?>" <?php echo $checado; ?>> &nbsp;
													<span><?php echo $qrBuscaProfissaoAut['DES_PROFISS']; ?></span>
												</td>
												<?php
												if ($countLinha == 4) {
													echo "</tr>";
													$countLinha = 0;
												}
												?>


											<?php
											}
											?>


									</tbody>
								</table>
							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($countProfi == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

					</form>


				</div>

				<div class="push50"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_TIPOREG").val($("#ret_COD_TIPOREG_" + index).val());
		$("#formulario #DES_TIPOREG").val($("#ret_DES_TIPOREG_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$("#CAD").click(function(e) {
		e.preventDefault();

		var peloMenosUmSelecionado = $("input[name='COD_PROFISS[]']:checked").length > 0;

		if (!peloMenosUmSelecionado) {

			$.alert({
				title: "Erro ao efetuar o Registro",
				content: "Por Favor, selecione pelo menos um item da lista",
				type: 'red'
			});
		} else {
			$("#formulario").submit();
		}
	});
</script>