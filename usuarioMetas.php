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
$cod_consumo = "";
$cod_categor = "";
$cod_entidad = "";
$qtd_limite = 0;
$cod_tpunida = "";
$tip_limite = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$des_logo = "";
$des_imgback = "";
$cor_backbar = "";
$cor_backpag = "";
$cor_titulos = "";
$cor_textos = "";
$cor_botao = "";
$cor_botaoon = "";
$cor_fullpag = "";
$cor_textfull = "";
$cod_app = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaUnidade = "";
$nom_entidad = "";
$qrBusca = "";
$qtd_tipo_1 = 0;
$qtd_tipo_2 = 0;
$comboTexto = "";
$popUp = "";
$abaMetas = "";
$lbl = "";
$check_metaprod = "";
$check_metadest = "";
$check_alertmin = "";
$check_fideliz = "";
$linha = "";
$combo = "";
$arrayQueryUni = [];
$qrListaUniVendas = "";
$log_ativo = "";
$log_ativo_u = "";
$check = "";
$td = "";
$a = "";
$el = "";

$hashLocal = mt_rand();

$cod_univend = "";
$nom_fantasi = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_consumo = fnLimpaCampoZero(@$_POST['COD_CONSUMO']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_categor = fnLimpaCampoZero(@$_POST['COD_CATEGOR']);
		$cod_entidad  = fnLimpaCampoZero(@$_POST['COD_ENTIDAD']);
		$qtd_limite = fnLimpaCampo(@$_POST['QTD_LIMITE']);
		$cod_tpunida  = fnLimpaCampoZero(@$_POST['COD_TPUNIDA']);
		$tip_limite  = fnLimpaCampo(@$_POST['TIP_LIMITE']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			if ($opcao == 'CAD') {
				$sql = "INSERT INTO totem_app(
								COD_EMPRESA, 
								DES_LOGO, 
								DES_IMGBACK, 
								COR_BACKBAR, 
								COR_BACKPAG, 
								COR_TITULOS, 
								COR_TEXTOS, 
								COR_BOTAO, 
								COR_BOTAOON, 
								COR_FULLPAG, 
								COR_TEXTFULL) 
								VALUES (
								'$cod_empresa', 
								'$des_logo', 
								'$des_imgback', 
								'$cor_backbar', 
								'$cor_backpag', 
								'$cor_titulos', 
								'$cor_textos', 
								'$cor_botao', 
								'$cor_botaoon', 
								'$cor_fullpag', 
								'$cor_textfull'
								)";
				mysqli_query(connTemp($cod_empresa, ""), $sql);
				fnEscreve($sql);
			}

			if ($opcao == 'ALT') {
				$sql = "UPDATE TOTEM_APP SET 
								DES_LOGO = '$des_logo', 
								DES_IMGBACK = '$des_imgback', 
								COR_BACKBAR = '$cor_backbar', 
								COR_BACKPAG = '$cor_backpag', 
								COR_TITULOS = '$cor_titulos', 
								COR_TEXTOS = '$cor_textos', 
								COR_BOTAO = '$cor_botao', 
								COR_BOTAOON = '$cor_botaoon', 
								COR_FULLPAG = '$cor_fullpag', 
								COR_TEXTFULL = '$cor_textfull'
								WHERE COD_APP = $cod_app and COD_EMPRESA = $cod_empresa ";
				mysqli_query(connTemp($cod_empresa, ""), $sql);
				fnEscreve($sql);
			}



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

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}


	//busca dados da entidade
	if (@$_GET['idU'] <> "") {
		$cod_univend = fnDecode(@$_GET['idU']);
	}

	$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and COD_UNIVEND = '0" . $cod_univend . "'";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	//fnEscreve($sql);

	$qrBuscaUnidade = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaUnidade)) {
		$cod_univend = $qrBuscaUnidade['COD_UNIVEND'];
		$nom_fantasi = $qrBuscaUnidade['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$cod_entidad = 0;
	$nom_entidad = "";
}

//fnMostraForm();
//fnEscreve($cod_empresa);


$sql = "select COUNT(0) QTD from PRODUTO_META M
		inner join produtocliente P on P.cod_produto = M.cod_produto
		WHERE LOG_DESTAQUE != 'S'
	order by P.des_produto ";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBusca = mysqli_fetch_assoc($arrayQuery);
$qtd_tipo_1 = $qrBusca["QTD"];

$sql = "select COUNT(0) QTD from PRODUTO_META M
		inner join produtocliente P on P.cod_produto = M.cod_produto
		WHERE LOG_DESTAQUE = 'S'
	order by P.des_produto ";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBusca = mysqli_fetch_assoc($arrayQuery);
$qtd_tipo_2 = $qrBusca["QTD"];


// $comboTexto = array('(vendas unitárias)'=>'Itens/Litros', 
// 					'(sobre faturamento)'=>'Valor venda (Reais) ', 
// 				    '(sobre qtd. vendas)'=>'Qtd. Vendas');

$comboTexto = array(
	'4' => 'Itens/Litros',
	'5' => 'Valor venda (Reais)',
	'6' => 'Qtd. Vendas'
);

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.popover-content {
		z-index: 99 !important
	}
</style>


<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					$abaMetas = 1302;
					include "abasUsuariosMetas.php";
					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


							<div class="row">

								<?php
								if (@$cod_univend != "") {
								?>
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_FANTASI" id="NOM_FANTASI" value="<?php echo $nom_fantasi; ?>">
											<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend; ?>">
										</div>
									</div>
								<?php } ?>

							</div>

							<input type="hidden" name="LOG_HABITKT" id="LOG_HABITKT" value="N">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

							<div class="push30"></div>

							<div class="col-lg-12">

								<?php
								$sql = "SELECT * FROM CONTROLE_METAS_DESC WHERE COD_EMPRESA = $cod_empresa";
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
								$lbl = array();
								$check_metaprod = "checked";
								$check_metadest = "checked";
								$check_alertmin = "checked";
								$check_fideliz = "checked";

								while ($linha = mysqli_fetch_assoc($arrayQuery)) {

									$lbl[$linha["COD_DESCRICAO"]] = $linha["NOM_DESCRICAO"];

									switch (@$linha["DES_COMBO"]) {
										case '4':
											$combo[$linha["COD_DESCRICAO"]] = "Itens/Litros";
											break;

										case '5':
											$combo[$linha["COD_DESCRICAO"]] = "Valor venda (Reais)";
											break;

										default:
											$combo[$linha["COD_DESCRICAO"]] = "Qtd. Vendas";
											break;
									}

									if ($linha["COD_DESCRICAO"] == "VAL_METAPROD") {
										if (@$linha["LOG_STATUS"] == "N") {
											$check_metaprod = "";
										}
									} else if ($linha["COD_DESCRICAO"] == "VAL_METADEST") {
										if ($linha["LOG_STATUS"] == "N") {
											$check_metadest = "";
										}
									} else if ($linha["COD_DESCRICAO"] == "VAL_ALERTMIN") {
										if ($linha["LOG_STATUS"] == "N") {
											$check_alertmin = "";
										}
									} else {
										if ($linha["LOG_STATUS"] == "N") {
											$check_fideliz = "";
										}
									}
								}


								$lbl["VAL_METAPROD"] = (@$lbl["VAL_METAPROD"] <> "" ? $lbl["VAL_METAPROD"] : "ABASTECIMENTOS");
								$lbl["VAL_METADEST"] = (@$lbl["VAL_METADEST"] <> "" ? $lbl["VAL_METADEST"] : "% ADITIVADA");
								$lbl["VAL_ALERTMIN"] = (@$lbl["VAL_ALERTMIN"] <> "" ? $lbl["VAL_ALERTMIN"] : "+ 20 LITROS");
								$lbl["QTD_FIDELIZ"] = (@$lbl["QTD_FIDELIZ"] <> "" ? $lbl["QTD_FIDELIZ"] : "FIDELIDADE");

								$combo["VAL_METAPROD"] = (@$combo["VAL_METAPROD"] <> "" ? $combo["VAL_METAPROD"] : "Itens/Litros");
								$combo["VAL_METADEST"] = (@$combo["VAL_METADEST"] <> "" ? $combo["VAL_METADEST"] : "Valor venda (Reais)");
								$combo["VAL_ALERTMIN"] = (@$combo["VAL_ALERTMIN"] <> "" ? $combo["VAL_ALERTMIN"] : "Qtd. Vendas");
								$combo["QTD_FIDELIZ"] = (@$combo["QTD_FIDELIZ"] <> "" ? $combo["QTD_FIDELIZ"] : "Itens/Litros");

								?>

								<div class="no-more-tables">


									<table id="edit" class="table table-bordered table-striped table-hover">
										<thead>

											<tr>
												<th colspan="2" class="text-nowrap" style="position: sticky;top:0px;z-index:10;background-color:#D5DBDB;"></th>
												<th class="text-center text-nowrap" style="position: sticky;top:0px;z-index:10;background-color:#E5E7E9;">
													<div class="form-group">
														<label class="switch">
															<input type="checkbox" name="LOG_METAPROD" id="LOG_METAPROD" class="switch" value="S" <?= $check_metaprod ?> onchange='toggleCol("VAL_METAPROD",this)'>
															<span></span>
														</label>
													</div>
												</th>
												<th class="text-center text-nowrap" style="position: sticky;top:0px;z-index:10;background-color:#E5E7E9;">
													<div class="form-group">
														<label class="switch">
															<input type="checkbox" name="LOG_METADEST" id="LOG_METADEST" class="switch" value="S" <?= $check_metadest ?> onchange='toggleCol("VAL_METADEST",this)'>
															<span></span>
														</label>
													</div>
												</th>
												<th class="text-center text-nowrap" style="position: sticky;top:0px;z-index:10;background-color:#E5E7E9;">
													<div class="form-group">
														<label class="switch">
															<input type="checkbox" name="LOG_ALERTMIN" id="LOG_ALERTMIN" class="switch" value="S" <?= $check_alertmin ?> onchange='toggleCol("VAL_ALERTMIN",this)'>
															<span></span>
														</label>
													</div>
												</th>
												<th class="text-center text-nowrap" style="position: sticky;top:0px;z-index:10;background-color:#E5E7E9;">
													<div class="form-group">
														<label class="switch">
															<input type="checkbox" name="LOG_FIDELIZ" id="LOG_FIDELIZ" class="switch" value="S" <?= $check_fideliz ?> onchange='toggleCol("QTD_FIDELIZ",this)'>
															<span></span>
														</label>
													</div>
												</th>
											</tr>

											<tr>
												<th colspan="2" class="text-nowrap" style="position: sticky;top:42px;z-index:10;background-color:#D5DBDB;"></th>
												<th class="text-center text-nowrap" style="position: sticky;top:42px;z-index:10;background-color:#E5E7E9;"><a class="btn btn-sm btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1304) ?>&id=<?= @$_GET["id"] ?>&pop=true&tipo=1" data-title="Abastecimentos"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Cesta 1 (<span data-tipo=1><?= $qtd_tipo_1 ?></span>)</a></th>
												<th class="text-center text-nowrap" style="position: sticky;top:42px;z-index:10;background-color:#E5E7E9;"><a class="btn btn-sm btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1304) ?>&id=<?= @$_GET["id"] ?>&pop=true&tipo=2" data-title="Aditivada"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Cesta 2 (<span data-tipo=2><?= $qtd_tipo_2 ?></span>)</a></th>
												<th class="text-center text-nowrap" style="position: sticky;top:42px;z-index:10;background-color:#E5E7E9;"><!--<a class="btn btn-sm btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1897) ?>&id=<?= @$_GET["id"] ?>&pop=true" data-title="Configuração de comparativo"><i class="fas fa-cogs" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Comparativo </a>--></th>
												<th class="text-center text-nowrap" style="position: sticky;top:42px;z-index:10;background-color:#E5E7E9;"></th>
											</tr>

										</thead>
										<?php
										$count = 0;
										$sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa" .
											(@$cod_univend != "" ? " AND COD_UNIVEND IN ($cod_univend)" : "") .
											" AND LOG_ESTATUS = 'S'
														AND (COD_EXCLUSA IS NULL OR COD_EXCLUSA = 0) ORDER BY TRIM(NOM_FANTASI)";
										//fnEscreve($sql);
										$arrayQueryUni = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;
										while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQueryUni)) {
											$count++;
											$cod_univend = $qrListaUniVendas['COD_UNIVEND'];
											$nom_fantasi = $qrListaUniVendas['NOM_FANTASI'];
											$cod_empresa = $qrListaUniVendas['COD_EMPRESA'];
											$log_ativo = $qrListaUniVendas['LOG_ATIVOMETA'];
											$log_ativo_u = $log_ativo;
											if ($log_ativo == 'S') $check = "checked";
											else $check = "";
										?>

											<thead>
												<?php if (@$_GET["idU"] == "") { ?>
													<tr>
														<th style="background-color:#BBB;position: sticky;top:84;z-index:10;">
															<label class='switch switch-small'>
																<input data-switch-univend='<?= $cod_univend ?>' type='checkbox' name='LOG_ATIVO_<?php echo $count; ?>' id='LOG_ATIVO_<?php echo $count; ?>' class='switch switch-univend  switch-small' value='S' onchange="verificaTabela(<?php echo $count ?>)" <?php echo $check; ?>>
																<span></span>
															</label>
														</th>
														<th colspan=10 style="background-color:#BBB;position: sticky;top:84;z-index:10;"><?= $cod_univend . " - " . $nom_fantasi ?></th>
													</tr>
													<input type='hidden' id='ret_COD_EMPRESA_<?php echo $count; ?>' name='COD_EMPRESA_<?php echo $count; ?>' value='<?php echo $cod_empresa ?>'>
													<input type='hidden' id='ret_DAT_CADASTR_<?php echo $count; ?>' name='COD_UNIVEND_<?php echo $count; ?>' value='<?php echo $cod_univend ?>'>
												<?php } ?>
												<tr data-univend='<?= $cod_univend ?>' class='<?= ($log_ativo_u == "S" ? "" : "hidden") ?>'>
													<th colspan="2" class="text-nowrap" style="position: sticky;top:126px;z-index:10;background-color:#D5DBDB;">METAS DIÁRIAS</th>
													<th class="text-center text-nowrap" style="position: sticky;top:126px;z-index:10;background-color:#E5E7E9;">
														<a href="#" class="editable" style="color:#000"
															data-type='text'
															data-title='Editar'
															data-name="VAL_METAPROD"
															data-action='tr'
															data-empresa="<?php echo $cod_empresa ?>">
															<?= $lbl["VAL_METAPROD"] ?>
														</a>
														<!-- <br><span class="f12">(vendas unit&aacute;rias)</span> -->
														<br>
														<span class="f12">
															<a href="#" class="editable" style="color:#000"
																data-type='select'
																data-title='Editar'
																data-name="VAL_METAPROD_COMBO"
																data-action='combo'
																data-value="<?= $combo["VAL_METAPROD"] ?>"
																data-source='<?= json_encode($comboTexto) ?>'
																data-empresa="<?php echo $cod_empresa ?>">
																<?= $combo["VAL_METAPROD"] ?>
															</a>
														</span>
													</th>
													<th class="text-center text-nowrap" style="position: sticky;top:126px;z-index:10;background-color:#E5E7E9;">
														<a href="#" class="editable" style="color:#000"
															data-type='text'
															data-title='Editar'
															data-name="VAL_METADEST"
															data-action='tr'
															data-empresa="<?php echo $cod_empresa ?>">
															<?= $lbl["VAL_METADEST"] ?>
														</a>
														<br>
														<!-- <span class="f12">(sobre faturamento)</span></th> -->
														<span class="f12">
															<a href="#" class="editable" style="color:#000"
																data-type='select'
																data-title='Editar'
																data-name="VAL_METADEST_COMBO"
																data-action='combo'
																data-value="<?= $combo["VAL_METADEST"] ?>"
																data-source='<?= json_encode($comboTexto) ?>'
																data-empresa="<?php echo $cod_empresa ?>">
																<?= $combo["VAL_METADEST"] ?>
															</a>
														</span>
													<th class="text-center text-nowrap" style="position: sticky;top:126px;z-index:10;background-color:#E5E7E9;">
														<a href="#" class="editable" style="color:#000"
															data-type='text'
															data-title='Editar'
															data-name="VAL_ALERTMIN"
															data-action='tr'
															data-empresa="<?php echo $cod_empresa ?>">
															<?= $lbl["VAL_ALERTMIN"] ?>
														</a>
														<br>
														<!-- <span class="f12">(sobre qtd. vendas)</span></th> -->
														<span class="f12">
															<a href="#" class="editable" style="color:#000"
																data-type='select'
																data-title='Editar'
																data-name="VAL_ALERTMIN_COMBO"
																data-action='combo'
																data-value="<?= $combo["VAL_ALERTMIN"] ?>"
																data-source='<?= json_encode($comboTexto) ?>'
																data-empresa="<?php echo $cod_empresa ?>">
																<?= $combo["VAL_ALERTMIN"] ?>
															</a>
														</span>
													<th class="text-center text-nowrap" style="position: sticky;top:126px;z-index:10;background-color:#E5E7E9;">
														<a href="#" class="editable" style="color:#000"
															data-type='text'
															data-title='Editar'
															data-name="QTD_FIDELIZ"
															data-action='tr'
															data-empresa="<?php echo $cod_empresa ?>">
															<?= $lbl["QTD_FIDELIZ"] ?>
														</a>
														<br>
														<!-- <span class="f12">(vendas unit&aacute;rias)</span></th> -->
														<span class="f12">
															<a href="#" class="editable" style="color:#000"
																data-type='select'
																data-title='Editar'
																data-name="QTD_FIDELIZ_COMBO"
																data-action='combo'
																data-value="<?= $combo["QTD_FIDELIZ"] ?>"
																data-source='<?= json_encode($comboTexto) ?>'
																data-empresa="<?php echo $cod_empresa ?>">
																<?= $combo["QTD_FIDELIZ"] ?>
															</a>
														</span>
												</tr>
											</thead>

											<tbody id="relatorioConteudo" data-load="false" class='<?= ($log_ativo_u == "S" ? "" : "hidden") ?>'>
												<tr data-univend='<?= $cod_univend ?>' class='<?= ($log_ativo_u == "S" ? "" : "hidden") ?>'>
													<td colspan=10>
														Carregando dados...
													</td>
												</tr>

											</tbody>
										<?php
										}
										?>
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

								</div>

						</form>

					</div>

					<div class="push"></div>

				</div>

				</div>
			</div>
			<!-- fim Portlet -->
	</div>

</div>

<!-- modal -->
<div class="modal fade" id="popModalAux" tabindex='-1'>
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

<style>
	th [data-type=all] {
		display: none;
	}
</style>
<script>
	$(document).ready(function() {
		ready();
	});

	function toggleCol(coluna, el) {

		let status = "N";

		if ($(el).prop("checked")) {
			status = "S";
		}

		$.ajax({
			type: "POST",
			url: "ajxUsuarioMetas.php?tipo=coluna",
			data: {
				COD_EMPRESA: "<?= $cod_empresa ?>",
				COLUNA: coluna,
				STATUS: status
			},
			success: function(data) {
				console.log(data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Ocorreu um erro. Tente novamente mais tarde.");
			}
		});

	}

	function ready() {

		var v_load = setInterval(carregaUsuarios, 100);

		$.fn.editable.defaults.mode = 'popup';
		$.fn.editableform.buttons =
			'<button type="button" class="btn btn-primary btn-sm editable-submit" title="Alterar para este usuário" data-type="one"><i class="fas fa-check"></i></button>' +
			'<button type="button" class="btn btn-primary btn-sm editable-submit" title="Alterar para todos os usuário da unidade" data-type="all" style="margin-left:7px;"><i class="fas fa-check-double"></i></button>' +
			'<button type="button" class="btn btn-default btn-sm editable-cancel"><i class="glyphicon glyphicon-remove"></i></button>';

		$('.edit-int .editable-input .input-sm[type=text]').mask('000.000.000.000.000', {
			reverse: true
		});
		$('.edit-decimal .editable-input .input-sm[type=text]').mask('000.000.000.000.000,00', {
			reverse: true
		});

		$(document).on('click', '.editable-submit', function() {
			$(".editable-cancel").click();
			acao = ($(this).attr("data-type"));

			var $td = $(this).closest("span");
			var $a = $td.find("a");
			var v_name = $a.attr("data-name");

			if (v_name == undefined) {

				var $td = $(this).closest("td");
				var $a = $td.find("a");
				var v_name = $a.attr("data-name");

				if (v_name == undefined) {
					$td = $(this).closest("th");
					var $a = $td.find("a");
					var v_name = $a.attr("data-name");
				}
			}

			var v_pk = $a.attr("data-pk");
			var v_type = $a.attr("data-type");

			var v_value = $td.find(".editable-input .form-control").val();
			var v_empresa = $a.attr("data-empresa");
			var v_univend = $a.attr("data-univend");
			var v_action = $a.attr("data-action");

			// alert(v_action);

			if (acao == "one") {

				data = "pk=" + v_pk + "&name=" + v_name + "&value=" + v_value + "&empresa=" + v_empresa + "&univend=" + v_univend + "&action=" + v_action;
				if (v_type == "select") {
					var v_source_str = $a.attr("data-source");
					var v_source = JSON.parse(v_source_str);
					$a.html(v_source[v_value]);
				} else {
					$a.html(v_value);
				}
				$a.addClass("text-warning");

				$.ajax({
					method: 'POST',
					url: 'ajxUsuarioMetas.php?tipo=edit',
					data: data,
					success: function(data) {
						console.log(data);
						$a.removeClass("text-warning");
						if (v_action == "tr") {
							$("th [data-name=" + v_name + "]").html(v_value);
						}
					}
				});

			} else {

				$("tr[data-univend=" + v_univend + "] a.editable[data-name=" + v_name + "]").each(function() {
					var $a = $(this);
					var v_pk = $a.attr("data-pk");

					data = "pk=" + v_pk + "&name=" + v_name + "&value=" + v_value + "&empresa=" + v_empresa + "&univend=" + v_univend;
					if (v_type == "select") {
						var v_source_str = $a.attr("data-source");
						var v_source = JSON.parse(v_source_str);
						$a.html(v_source[v_value]);
					} else {
						$a.html(v_value);
					}
					$a.addClass("text-warning");

					$.ajax({
						method: 'POST',
						url: 'ajxUsuarioMetas.php?tipo=edit',
						data: data,
						success: function(data) {
							console.log(data);
							$a.removeClass("text-warning");
						}
					});

				});

			}



		});

		$('.editable').editable({
			url: '/ajxUsuarioMetas.php?tipo=edit',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				params.univend = $(this).data('univend');
				params.count = $(this).data('count');
				params.empresa = $(this).data('empresa');
				return params;
			},
			success: function(data) {
				//alert(data);
			}
		});

		// $('.ed_combo').editable({
		// 	url: '/ajxUsuarioMetas.php?tipo=combo',
		// 	ajaxOptions:{type:'post'},
		// 	placement: 'bottom',
		// 	source: [
		//       {value: '(vendas unitárias)', text: '(vendas unitárias)'},
		//       {value: '(sobre faturamento)', text: '(sobre faturamento)'},
		//       {value: '(sobre qtd. vendas)', text: '(sobre qtd. vendas)'}
		//    ],
		// 	params: function(params) {
		//         params.univend = $(this).data('univend');
		//         params.empresa = $(this).data('empresa');
		//         return params;
		//     },
		// 	success:function(data){
		// 		//alert(data);
		// 		console.log(data);
		// 	}
		// });


		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();
	}

	function retornaForm(index) {

		$("#formulario #COD_CONSUMO").val($("#ret_COD_CONSUMO_" + index).val());
		$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val());
		$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
		$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val());
		$("#formulario #QTD_LIMITE").val($("#ret_QTD_LIMITE_" + index).val());
		$("#formulario #COD_TPUNIDA").val($("#ret_COD_TPUNIDA_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_LIMITE").val($("#ret_TIP_LIMITE_" + index).val()).trigger("chosen:updated");

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	function verificaTabela(index) {
		switch_univend();
		$.ajax({
			method: 'POST',
			url: 'ajxUsuarioMetas.php?tipo=radio&count=' + index,
			data: $('#formulario').serialize(),
			success: function(data) {
				console.log(data);
			}
		});
	}

	function switch_univend() {
		$(".switch-univend").each(function() {
			var cod_univend = $(this).attr("data-switch-univend");
			var name = $(this).attr("name");
			if ($("[name=" + name + "]:checked").val() == "S") {
				$("[data-univend=" + cod_univend + "]").removeClass("hidden");
				$("[data-univend=" + cod_univend + "]").parent().removeClass("hidden");
			} else {
				$("[data-univend=" + cod_univend + "]").addClass("hidden");
				//$("[data-univend="+cod_univend+"]").parent().addClass("hidden");
			}
		});
	}
	switch_univend();


	function carregaUsuarios() {
		if ($("[data-load=loading]").length > 0) {
			return false;
		}
		if ($("[data-load=false]:not(.hidden)").length <= 0) {
			return false;
		}

		$el = $("[data-load=false]:not(.hidden)").first();
		$el.attr("data-load", "loading");

		var cod_univend = $el.find("tr").attr("data-univend");
		//console.log($el.find("tr").attr("data-univend"));

		$.ajax({
			type: "POST",
			url: "ajxCarregaUsuarioMetas.php",
			data: "cod_empresa=<?= $cod_empresa ?>&cod_univend=" + cod_univend,
			success: function(data) {
				$el.html(data);
				$el.attr("data-load", "true");
				ready();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$el.html("<tr><td>" + errorThrown + "</td></tr>");
				$el.attr("data-load", "true");
			}
		});


	}
</script>