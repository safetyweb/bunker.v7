<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$tem_prodaux = "";
$itens_carregar_mais = "";
$msgRetorno = "";
$msgTipo = "";
$cod_venda = "";
$cod_orcamento = "";
$cod_cliente = "";
$cod_lancamen = "";
$cod_ocorren = "";
$cod_formapa = "";
$val_totprodu = "";
$val_resgate = "";
$val_desconto = "";
$val_totvenda = "";
$cod_vendapdv = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sql1 = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaCliente = "";
$nom_cliente = "";
$num_cartao = "";
$num_cgcecpf = "";
$log_estatus = "";
$log_ativcadLGPD = "";
$log_cadokLGPD = "";
$log_termoLGPD = "";
$bloqueiaDesbloqueio = "";
$popUp = "";
$abaEmpresa = "";
$abaCli = "";
$qrBusca = "";
$dt_resgate = "";
$STATUS = "";


$hashLocal = mt_rand();

$tem_prodaux = "";

$itens_carregar_mais = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_venda = fnLimpacampoZero(@$_REQUEST['COD_VENDA']);
		$cod_orcamento = fnLimpacampoZero(@$_REQUEST['COD_ORCAMENTO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpacampoZero(@$_REQUEST['COD_CLIENTE']);
		$cod_lancamen = fnLimpacampoZero(@$_REQUEST['COD_LANCAMEN']);
		$cod_ocorren = fnLimpacampoZero(@$_REQUEST['COD_OCORREN']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_formapa = fnLimpacampoZero(@$_REQUEST['COD_FORMAPA']);
		$tem_prodaux = fnLimpacampoZero(@$_REQUEST['TEM_PRODAUX']);

		$val_totprodu = fnLimpacampo(@$_REQUEST['VAL_TOTPRODU']);
		$val_resgate = fnLimpacampo(@$_REQUEST['VAL_RESGATE']);
		$val_desconto = fnLimpacampo(@$_REQUEST['VAL_DESCONTO']);
		$val_totvenda = fnLimpacampo(@$_REQUEST['VAL_TOTVENDA']);
		$cod_vendapdv = fnLimpacampo(@$_REQUEST['COD_VENDAPDV']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


			//echo $sql1;	

			mysqli_query(connTemp(fnDecode(@$_GET['key']), ''), $sql1);

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

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_cliente = fnDecode(@$_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO, TIP_CAMPANHA, NUM_DECIMAIS_B, LOG_ATIVCAD FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE, LOG_FUNCIONA, LOG_ESTATUS, LOG_CADOK,	LOG_TERMO FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

//echo "<h3>Chegou...</h3>";

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
}

if ($log_estatus == "N") {
	$msgRetorno = 'Cliente <strong>inativo</strong>.';
	$msgTipo = 'alert-warning';
}

//novas verificações LGPD
if ($log_ativcadLGPD == "S" && $log_cadokLGPD == "N" && $log_termoLGPD == "N") {
	$bloqueiaDesbloqueio = "S";
} else {
	$bloqueiaDesbloqueio = "N";
}

//fnEscreve2("<h4>Chegou tb...</h4>");

include "moduloControlaAcesso.php";

//fnMostraForm();
//fnEscreve2($cod_cliente);
//fnEscreve2("<h4>Chegou tb...</h4>");



?>
<style>
	.widget .widget-title {
		font-size: 14px;
	}

	.widget .widget-int {
		font-size: 18px;
		padding: 0 0 10px 0;
	}

	.widget .widget-item-left .fa,
	.widget .widget-item-right .fa,
	.widget .widget-item-left .glyphicon,
	.widget .widget-item-right .glyphicon {
		font-size: 35px;
	}

	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.widget-item-left span {
		font-size: 32px;
	}

	.badge {
		display: table;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		text-align: center;
		color: white;
		font-size: 11px;
		margin-right: auto;
		margin-left: auto;
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
	}

	/*.notify-badge span{
	margin: 0 auto;
}*/
</style>
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<?php
				//menu superior - cliente
				$abaEmpresa = 2080;
				$abaCli = 2080;
				// echo $_SESSION["SYS_COD_SISTEMA"];								
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasClienteDuque.php";
						break;
					case 13: //sh manager
						include "abasIntegradoraCli.php";
						break;
					case 18: //mais cash
						include "abasMaisCashCli.php";
					case 21: //gestão garantias
						include "abasGestaoGarantiasCli.php";
						break;
					default;
						include "abasClienteConfig.php";
						break;
				}

				?>

				<div class="push30"></div>

				<div class="portlet-body">

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código do Cliente</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
										</div>
									</div>

									<div class="col-md-5">
										<label for="inputName" class="control-label required">Nome do Usuário</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
											<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número do Cartão</label>
											<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push30"></div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-12" id="div_Produtos">


									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th></th>
												<th>Código Campanha</th>
												<th>Campanha</th>
												<th>Produto</th>
												<th>Qtd. Produtos</th>
												<th>Data Indicação</th>
												<th>Data Expiração</th>
												<th>Data Resgate</th>
												<th>Status Atual</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php

											$sql = "SELECT 
																		CP.COD_CAMPANHA,
																		CP.DES_CAMPANHA, 
																		PC.DES_PRODUTO, 
																		BE.QTD_PRODUTO, 
																		BE.DAT_CADASTR, 
																		BE.DAT_EXPIRA, 
																		BE.DAT_RESGATE, 
																		BE.COD_STATUS,
																		ST.DES_STATUSCRED
																		FROM brindeextra AS BE
																		INNER JOIN campanha AS CP ON BE.COD_CAMPANHA = CP.COD_CAMPANHA
																		INNER JOIN produtocliente AS PC ON BE.COD_PRODUTO = PC.COD_PRODUTO
																		INNER JOIN STATUSCREDITO AS ST ON BE.COD_STATUS = ST.COD_STATUSCRED
																		WHERE BE.COD_CLIENTE = $cod_cliente";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while (@$qrBusca = mysqli_fetch_assoc($arrayQuery)) {

												$count++;

												$dt_resgate = "";

												if ($qrBusca['DAT_RESGATE'] != "") {
													$STATUS = "<span class='fal fa-gift'></span>";
													$dt_resgate = fnDataShort($qrBusca['DAT_RESGATE']);
												} else {
													$STATUS = $qrBusca['DES_STATUSCRED'];
												}

												echo "
																	<tr id=" . "cod_credito_" . $qrBusca['COD_CAMPANHA'] . ">															
																	<td></td>
																	<td>" . $qrBusca['COD_CAMPANHA'] . "</td>
																	<td>" . $qrBusca['DES_CAMPANHA'] . "</td>
																	<td><small>" . $qrBusca['DES_PRODUTO'] . "</small></td>
																	<td>" . $qrBusca['QTD_PRODUTO'] . "</td>												
																	<td>" . fnDataShort($qrBusca['DAT_CADASTR']) . "</td>												
																	<td>" . fnDataShort($qrBusca['DAT_EXPIRA']) . "</td>												
																	<td>" . $dt_resgate . "</td>												
																	<td>" . $STATUS . "</td>											
																	</tr>";
											}

											?>

										</tbody>
									</table>
								</div>
							</div>
							<!-- <tfoot>
							<tr>
								<th colspan="100">
									<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
								</th>
							</tr>
						</tfoot> -->

							<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
							<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						</form>
					</div>
				</div>
				</div>
				<!-- fim Portlet -->
			</div>
	</div>

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog">
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
		$(document).ready(function() {

			$('.modal').on('hidden.bs.modal', function() {

				if ($('#REFRESH_CLIENTE').val() == "S") {
					var newCli = $('#NOVO_CLIENTE').val();
					window.location.href = "action.php?mod=<?php echo fnEncode(2080); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
					$('#REFRESH_PRODUTOS').val("N");
				}

			});

		});
	</script>