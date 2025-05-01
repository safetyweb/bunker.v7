<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$itens_por_pagina = 50;

// Página default
$pagina = "1";

$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_formapa = "";
$cod_externo = "";
$des_formapa = "";
$log_pontuar = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrBuscaPagamento = "";
$pontuarAtivo = "";


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

		$cod_formapa = fnLimpaCampoZero(@$_REQUEST['COD_FORMAPA']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_externo = fnLimpaCampoZero(@$_REQUEST['COD_EXTERNO']);
		$des_formapa = fnLimpaCampoNoTrim(@$_REQUEST['DES_FORMAPA']);

		if (empty(@$_REQUEST['LOG_PONTUAR'])) {
			$log_pontuar = 'N';
		} else {
			$log_pontuar = @$_REQUEST['LOG_PONTUAR'];
		}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_FORMAPAGAMENTO (
				 '" . $cod_formapa . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_externo . "', 
				 '" . $des_formapa . "', 
				 '" . $log_pontuar . "', 
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sql);

			$arrayProc = mysqli_query(conntemp($cod_empresa, ""), $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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
					<i class="fa fa-terminal"></i>
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

				<?php $abaEmpresa = 1068;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FORMAPA" id="COD_FORMAPA" value="">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_FORMAPA" id="DES_FORMAPA" maxlength="150" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cód. Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Pontuar?</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR" class="switch" value="S" checked>
											<span></span>
										</label>
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

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

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
											<th>Nome do Grupo</th>
											<th>Cód. Externo</th>
											<th>Pontua</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										$sql = "SELECT 1
											FROM FORMAPAGAMENTO where LOG_ATIVO='S' and COD_EMPRESA = '" . $cod_empresa . "' order by DES_FORMAPA";

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);


										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// ================================================================================

										$sql = "select * from 
										FORMAPAGAMENTO 
										where LOG_ATIVO='S' 
										and COD_EMPRESA = '" . $cod_empresa . "' 
										order by DES_FORMAPA
										LIMIT $inicio, $itens_por_pagina";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaPagamento = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaPagamento['LOG_PONTUAR'] == "S") {
												$pontuarAtivo = '<span class="fas fa-check text-success"></span>';
											} else {
												$pontuarAtivo = '<span class="fas fa-times text-danger"></span>';
											}

											echo "
											<tr>
											  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td>" . $qrBuscaPagamento['COD_FORMAPA'] . "</td>
											  <td>" . $qrBuscaPagamento['DES_FORMAPA'] . "</td>
											  <td>" . $qrBuscaPagamento['COD_EXTERNO'] . "</td>
											  <td class='text-center'>" . $pontuarAtivo . "</td>
											</tr>
											<input type='hidden' id='ret_COD_FORMAPA_" . $count . "' value='" . $qrBuscaPagamento['COD_FORMAPA'] . "'>
											<input type='hidden' id='ret_DES_FORMAPA_" . $count . "' value='" . $qrBuscaPagamento['DES_FORMAPA'] . "'>
											<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaPagamento['COD_EXTERNO'] . "'>
											<input type='hidden' id='ret_LOG_PONTUAR_" . $count . "' value='" . $qrBuscaPagamento['LOG_PONTUAR'] . "'>
											";
										}

										?>

									</tbody>

									<tfoot>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
									</tfoot>

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

<script type="text/javascript">
	$(document).ready(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}
	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxFormasPagamento.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_FORMAPA").val($("#ret_COD_FORMAPA_" + index).val());
		$("#formulario #DES_FORMAPA").val($("#ret_DES_FORMAPA_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		if ($("#ret_LOG_PONTUAR_" + index).val() == 'S') {
			$('#formulario #LOG_PONTUAR').prop('checked', true);
		} else {
			$('#formulario #LOG_PONTUAR').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>