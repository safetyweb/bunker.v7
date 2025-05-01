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
$des_grupotr = "";
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
$qrBuscaUsuario = "";
$sqlGatilho = "";
$arrGatilho = "";
$qtd_usuarios = 0;
$qrGatilho = "";


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

		$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo(@$_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($adm, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
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

				<?php $abaEmpresa = 1584;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<div class="push50"></div>

					<div class="col-lg-12">

						<a class="btn btn-info addBox" href="javascript:void(0)" data-title="Adicionar Usuário ADM" data-url="action.do?mod=<?php echo fnEncode(1904) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true&btn=1">Adicionar Usuário Administrador</a>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Cod. Usuário</th>
											<th>Usuário</th>
											<th>Unidade</th>
											<th>Email</th>
											<th>Dt. Restrição</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT RUS.COD_REGISTRO,
														RUS.COD_USUARIO,
														GROUP_CONCAT(DISTINCT uni.NOM_FANTASI ORDER BY uni.NOM_FANTASI DESC SEPARATOR '| |') NOM_FANTASI, 
														US.NOM_USUARIO,
														US.DES_EMAILUS,
														RUS.TIP_RESTRIC, 
														RUS.DAT_CADASTR
												FROM USUARIOS_RESTRITOS RUS
												INNER JOIN USUARIOS US ON US.COD_USUARIO = RUS.COD_USUARIO
												INNER JOIN unidadevenda uni ON FIND_IN_SET(uni.COD_UNIVEND, US.COD_UNIVEND)
												WHERE RUS.COD_EMPRESA = $cod_empresa
												AND TIP_RESTRIC = 'ADM'
												GROUP BY RUS.COD_USUARIO
												ORDER BY RUS.COD_REGISTRO";
										$arrayQuery = mysqli_query($adm, $sql);

										// echo '<pre>';
										// print_r($arrayQuery);
										// echo '</pre>';

										$count = 0;
										while ($qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											echo "
													<tr>
														<td>" . $qrBuscaUsuario['COD_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_FANTASI'] . "</td>
														<td>" . $qrBuscaUsuario['DES_EMAILUS'] . "</td>
														<td>" . fnDataFull($qrBuscaUsuario['DAT_CADASTR']) . "</td>
														<td><a href='javascript:void(0)' class='btn btn-xs btn-danger transparency' onclick='btnExc(\"" . fnEncode($qrBuscaUsuario['COD_REGISTRO']) . "\")'><i class='fal fa-times'></i></a></td>

													</tr>";
										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<a class="btn btn-info addBox" href="javascript:void(0)" data-title="Adicionar Usuário Restrito" data-url="action.do?mod=<?php echo fnEncode(1904) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true&btn=2">Adicionar Usuário Restrito</a>

						<div class="no-more-tables">

							<form name="formLista">


								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Cod. Usuário</th>
											<th>Usuário</th>
											<th>Unidade</th>
											<th>Email</th>
											<th>Dt. Restrição</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT RUS.COD_REGISTRO,
														RUS.COD_USUARIO,
														GROUP_CONCAT(DISTINCT uni.NOM_FANTASI ORDER BY uni.NOM_FANTASI DESC SEPARATOR '| |') NOM_FANTASI, 
														US.NOM_USUARIO,
														US.DES_EMAILUS,
														RUS.TIP_RESTRIC, 
														RUS.DAT_CADASTR
												FROM USUARIOS_RESTRITOS RUS
												INNER JOIN USUARIOS US ON US.COD_USUARIO = RUS.COD_USUARIO
												INNER JOIN unidadevenda uni ON FIND_IN_SET(uni.COD_UNIVEND, US.COD_UNIVEND)
												WHERE RUS.COD_EMPRESA = $cod_empresa
												AND TIP_RESTRIC = 'RES'
												GROUP BY RUS.COD_USUARIO
												ORDER BY RUS.COD_REGISTRO";
										$arrayQuery = mysqli_query($adm, $sql);

										// echo '<pre>';
										// print_r($arrayQuery);
										// echo '</pre>';

										$count = 0;
										while ($qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											echo "
													<tr>
														<td>" . $qrBuscaUsuario['COD_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_FANTASI'] . "</td>
														<td>" . $qrBuscaUsuario['DES_EMAILUS'] . "</td>
														<td>" . fnDataFull($qrBuscaUsuario['DAT_CADASTR']) . "</td>
														<td><a href='javascript:void(0)' class='btn btn-xs btn-danger transparency' onclick='btnExc(\"" . fnEncode($qrBuscaUsuario['COD_REGISTRO']) . "\")'><i class='fal fa-times'></i></a></td>

													</tr>";
										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<?php

						$sqlGatilho = "SELECT RUS.DES_GATILHO
											FROM USUARIOS_RESTRITOS RUS
											INNER JOIN USUARIOS US ON US.COD_USUARIO = RUS.COD_USUARIO
											INNER JOIN unidadevenda uni ON FIND_IN_SET(uni.COD_UNIVEND, US.COD_UNIVEND)
											WHERE RUS.COD_EMPRESA = $cod_empresa
											AND TIP_RESTRIC = 'SLD'
											GROUP BY RUS.COD_USUARIO
											ORDER BY RUS.COD_REGISTRO
											LIMIT 1";
						$arrGatilho = mysqli_query($adm, $sqlGatilho);
						$qtd_usuarios = mysqli_num_rows($arrGatilho);
						$qrGatilho = mysqli_fetch_assoc($arrGatilho);

						?>

						<div class="row">
							<div class="col-md-2">
								<a class="btn btn-info addBox" href="javascript:void(0)" data-title="Adicionar Usuário ADM" data-url="action.do?mod=<?php echo fnEncode(1904) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true&btn=3">Adicionar Usuário Saldo</a>
							</div>
							<div class="col-md-10 inteiro f21">
								<?php if ($qtd_usuarios > 0) { ?>

									Gatilho:&nbsp;
									<!-- MAURICE PEDIU PRA MUDAR DE % PRA QUANTITATIVO 26/09/2023 -->
									Quando o saldo for igual ou menor que <a href="#" class="editable"
										data-type='text'
										data-title='Editar Valor' data-pk="SLD"
										data-name="DES_GATILHO"
										data-codempresa="<?= $cod_empresa ?>"><?= fnValor($qrGatilho['DES_GATILHO'], 0) ?>

									</a>

								<?php } ?>
							</div>
						</div>


						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Cod. Usuário</th>
											<th>Usuário</th>
											<th>Unidade</th>
											<th>Email</th>
											<th>Dt. Restrição</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT RUS.COD_REGISTRO,
														RUS.COD_USUARIO,
														GROUP_CONCAT(DISTINCT uni.NOM_FANTASI ORDER BY uni.NOM_FANTASI DESC SEPARATOR '| |') NOM_FANTASI, 
														US.NOM_USUARIO,
														US.DES_EMAILUS,
														RUS.TIP_RESTRIC, 
														RUS.DES_GATILHO, 
														RUS.DAT_CADASTR
												FROM USUARIOS_RESTRITOS RUS
												INNER JOIN USUARIOS US ON US.COD_USUARIO = RUS.COD_USUARIO
												INNER JOIN unidadevenda uni ON FIND_IN_SET(uni.COD_UNIVEND, US.COD_UNIVEND)
												WHERE RUS.COD_EMPRESA = $cod_empresa
												AND TIP_RESTRIC = 'SLD'
												GROUP BY RUS.COD_USUARIO
												ORDER BY RUS.COD_REGISTRO";
										$arrayQuery = mysqli_query($adm, $sql);
										$qtd_usuarios = mysqli_num_rows($arrayQuery);

										$count = 0;
										while ($qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											echo "
													<tr>
														<td>" . $qrBuscaUsuario['COD_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_USUARIO'] . "</td>
														<td>" . $qrBuscaUsuario['NOM_FANTASI'] . "</td>
														<td>" . $qrBuscaUsuario['DES_EMAILUS'] . "</td>
														<td>" . fnDataFull($qrBuscaUsuario['DAT_CADASTR']) . "</td>
														<td><a href='javascript:void(0)' class='btn btn-xs btn-danger transparency' onclick='btnExc(\"" . fnEncode($qrBuscaUsuario['COD_REGISTRO']) . "\")'><i class='fal fa-times'></i></a></td>

													</tr>";
										}

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

<script type="text/javascript">
	$(function() {

		// $('.inteiro .editable-input .input-sm').mask('000');

		$('.editable').editable({
			emptytext: '_______________',
			url: 'ajxGravaUsuarioRes.php',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				params.codempresa = $(this).data('codempresa');
				return params;
			},
			success: function(data) {
				console.log(data);
			}
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}


	function btnExc(id) {

		$.ajax({
			method: 'POST',
			url: "ajxGravaUsuarioRes.do?id=<?= fnEncode($cod_empresa) ?>&opcao=exc",
			data: {
				COD_REGISTRO: id
			},
			success: function(data) {
				console.log(data);
				location.reload();
			},
			error: function() {
				console.log("erro 500");
				// console.log(data);
			}
		});
	}
</script>