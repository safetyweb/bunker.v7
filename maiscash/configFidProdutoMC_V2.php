<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_campaprod = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;

		$cod_campaprod = fnLimpaCampoZero($_POST['COD_CAMPAPROD']);
		$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);

		$cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
		$cod_subcate = fnLimpaCampoZero($_REQUEST['COD_SUBCATE']);
		$cod_fornecedor = fnLimpaCampoZero($_REQUEST['COD_FORNECEDOR']);
		$cod_vantage = fnLimpaCampoZero($_REQUEST['COD_VANTAGE']);

		$tip_calculo = 2;

		$val_pontuacao = $_REQUEST['VAL_PONTUACAO'];
		$val_pontoext = $_REQUEST['VAL_PONTOEXT'];
		$tip_pontuacao = $_REQUEST['TIP_PONTUACAO'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		// fnEscreve($val_pontuacao);

		$cod_externo = 0;
		$des_categor = fnLimpaCampo($_REQUEST['DES_CATEGOR']);
		$des_abrevia = "CAT";
		$des_icones = "";


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// ADICIONANDO NOVO GRUPO DE PRODUTO
			$sql = "CALL SP_ALTERA_CATEGORIA (
				 '" . $cod_categor . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_externo . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $cod_usucada . "', 
				 '" . $opcao . "'  
				) ";

			//echo $sql;

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

			if ($cod_categor == 0 || $opcao == 'CAD') {

				$sqlCodCategor = "SELECT MAX(COD_CATEGOR) COD_CATEGOR 
										FROM CATEGORIA 
										WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";

				$arrayCat = mysqli_query(connTemp($cod_empresa, ''), trim($sqlCodCategor));
				$qrCat = mysqli_fetch_assoc($arrayCat);
				$cod_categor = $qrCat['COD_CATEGOR'];
			}

			$sql = "CALL SP_ALTERA_CAMPANHAPRODUTO (
				 '" . $cod_campaprod . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . fnValorSql($val_pontuacao) . "',
				 '" . fnValorSql($val_pontoext) . "',
				 '" . $tip_pontuacao . "',
				 '" . $cod_usucada . "',
				 '" . $cod_vantage . "',
                 '" . $tip_calculo . "',
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sql);				
			//fntesteSql(connTemp($cod_empresa,''),trim($sql));
			//exit();

			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

			$sqlCampRegra = "UPDATE CAMPANHAREGRA SET LOG_PRODUTO = 'S' WHERE COD_CAMPANHA = $cod_campanha";
			mysqli_query(connTemp($cod_empresa, ''), trim($sqlCampRegra));

			$cod_produto = 0;
			$cod_subcate = 0;
			$cod_externo = $cod_categor;
			$cod_fornecedor = 0;
			$cod_ean = 0;
			$des_produto = "Mais.Cash $des_categor";
			$atributo1 = 0;
			$atributo2 = 0;
			$atributo3 = 0;
			$atributo4 = 0;
			$atributo5 = 0;
			$atributo6 = 0;
			$atributo7 = 0;
			$atributo8 = 0;
			$atributo9 = 0;
			$atributo10 = 0;
			$atributo11 = 0;
			$atributo12 = 0;
			$atributo13 = 0;
			$des_imagem = "";
			$log_prodpbm = 'N';
			$log_habitexc = 'N';
			$log_nresgate = 'N';
			$log_pontuar = '1';
			$sku = "";
			$url_img_prod = "";

			$sqlCodProd = "SELECT COD_PRODUTO 
								FROM PRODUTOCLIENTE 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CATEGOR = $cod_categor";

			$arrayProd = mysqli_query(connTemp($cod_empresa, ''), trim($sqlCodProd));
			$qrProd = mysqli_fetch_assoc($arrayProd);
			if (isset($qrProd['COD_PRODUTO'])) {
				$cod_produto = fnLimpaCampoZero($qrProd['COD_PRODUTO']);
			} else {
				$cod_produto = 0;
			}
			$opcaoProd = $opcao;

			if ($cod_produto == 0) {

				// $sqlCodProd = "SELECT MAX(COD_PRODUTO) COD_PRODUTO 
				// 					FROM PRODUTOCLIENTE 
				// 					WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";

				// $arrayProd = mysqli_query(connTemp($cod_empresa,''),trim($sqlCodProd));
				// $qrProd = mysqli_fetch_assoc($arrayProd);
				// $cod_campaprod = $qrProd[COD_PRODUTO];

				$opcaoProd = 'CAD';
			}


			$sql = "CALL SP_ALTERA_PRODUTOCLIENTE (
				 '" . $cod_produto . "', 
				 '" . $cod_externo . "', 
				 '" . $cod_empresa . "',				
				 '" . $cod_ean . "',				
				 '" . $des_produto . "',				
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . $atributo1 . "',
				 '" . $atributo2 . "',
				 '" . $atributo3 . "',
				 '" . $atributo4 . "',
				 '" . $atributo5 . "',
				 '" . $atributo6 . "',
				 '" . $atributo7 . "',
				 '" . $atributo8 . "',
				 '" . $atributo9 . "',
				 '" . $atributo10 . "',
				 '" . $atributo11 . "',
				 '" . $atributo12 . "',
				 '" . $atributo13 . "',				 
				 '" . $des_imagem . "',				 
				 '" . $cod_usucada . "',
				 '" . $log_prodpbm . "',
				 '" . $log_habitexc . "',
				 '" . $log_pontuar . "',
				 '" . $log_nresgate . "',
				 '" . $sku . "',
				 '" . $url_img_prod . "',
				 '" . $opcaoProd . "'   
				); ";

			fnEscreve($sql);
			//fnTesteSql(connTemp($cod_empresa,""),$sql);
			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

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

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "S";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
		$abaVantagemComp = "active ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$sql1 = "SELECT * FROM CAMPANHA where COD_CAMPANHA = $cod_campanha";

$arrayQuery1 = mysqli_query(connTemp($cod_empresa, ''), $sql1);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery1);

$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
$des_icone = $qrBuscaCampanha['DES_ICONE'];
$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];


//busca dados do tipo da campanha
$sql2 = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";

$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery2);

$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
$label_1 = $qrBuscaTpCampanha['LABEL_1'];
$label_2 = $qrBuscaTpCampanha['LABEL_2'];
$label_3 = $qrBuscaTpCampanha['LABEL_3'];
$label_4 = $qrBuscaTpCampanha['LABEL_4'];
$label_5 = $qrBuscaTpCampanha['LABEL_5'];


//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];
} else {

	$cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}




?>

<style type="text/css">
	body {
		overflow: hidden;
	}

	.portlet {
		max-height: 80vh;
		/* Ajuste conforme necessário */
		overflow-y: auto;
	}
</style>
<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
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


					<!-- <div class="push30"></div>  -->

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais </legend>

								<div class="row">

									<!-- <div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo do Produto</label>
												<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>											  
													<?php
													$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																  <option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
																";
													}
													?>
												</select>	
											<div class="help-block with-errors"></div>
										</div>
									</div> -->

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Produto</label>
											<input type="text" class="form-control input-sm" name="DES_CATEGOR" id="DES_CATEGOR" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" name="COD_CATEGOR" id="COD_CATEGOR" value="">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required"><?php echo $nom_tpcampa; ?> Normais</label>
											<div class="col-md-9" style="margin:0; padding: 0;">
												<input type="text" class="form-control text-center input-sm money" name="VAL_PONTUACAO" id="VAL_PONTUACAO" maxlength="6" value="" data-error="Campo obrigatório">
											</div>
											<span style="margin:0; padding: 5px 0 0 5px; font-size: 18px;" class="col-md-2 pull-left">%<span>
													<div class="help-block with-errors"></div>
										</div>
									</div>

									<!-- <div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required"><?php echo $nom_tpcampa; ?> Extra</label>
											<div class="col-md-9" style="margin:0; padding: 0;">
												<input type="text" class="form-control text-center input-sm money" name="VAL_PONTOEXT" id="VAL_PONTOEXT" maxlength="6" value="" data-error="Campo obrigatório">
											</div>
											<span style="margin:0; padding: 5px 0 0 5px; font-size: 18px;" class="col-md-2 pull-left">%<span>
											<div class="help-block with-errors"></div>
										</div>
									</div> -->

								</div>

							</fieldset>

							<div class="push20"></div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="TIP_PONTUACAO" id="TIP_PONTUACAO" value="PCT">
							<input type="hidden" name="VAL_PONTOEXT" id="VAL_PONTOEXT" value="0">
							<input type="hidden" name="COD_CAMPAPROD" id="COD_CAMPAPROD" value="">
							<input type="hidden" name="COD_SUBCATE" id="COD_SUBCATE" value="">
							<input type="hidden" name="COD_FORNECEDOR" id="COD_FORNECEDOR" value="">
							<input type="hidden" name="COD_VANTAGE" id="COD_VANTAGE" value="">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push10"></div>

						<div class="col-md-12">

							<table class="table table-bordered table-striped table-hover table-sortable">

								<thead>

									<tr>
										<th width="40"><small></small></th>
										<th><small>Produto</small></th>
										<th><small><?php echo $nom_tpcampa; ?> Normais</small></th>
										<!-- <th><small><?php echo $nom_tpcampa; ?> Extras</small></th> -->
										<th><small>Ganho</small></th>
									</tr>

								</thead>

								<tbody>

									<?php
									$sql = "SELECT CATEGORIA.DES_CATEGOR,
													SUBCATEGORIA.DES_SUBCATE, 
													FORNECEDORMRKA.NOM_FORNECEDOR,
													CAMPANHAPRODUTO.* 
													FROM CAMPANHAPRODUTO
													LEFT JOIN CATEGORIA  ON CATEGORIA.COD_CATEGOR=CAMPANHAPRODUTO.COD_CATEGOR
													LEFT JOIN SUBCATEGORIA  ON SUBCATEGORIA.COD_SUBCATE=CAMPANHAPRODUTO.COD_SUBCATE
													LEFT JOIN FORNECEDORMRKA  ON FORNECEDORMRKA.COD_FORNECEDOR=CAMPANHAPRODUTO.COD_FORNECEDOR
													WHERE CAMPANHAPRODUTO.COD_CAMPANHA = $cod_campanha
													AND COD_EXCLUSAO = 0 
													ORDER BY DES_CATEGOR, DES_SUBCATE, NOM_FORNECEDOR  												
									";

									// fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$qtdNotifica = mysqli_num_rows($arrayQuery);

									$count = 0;
									$tipoVantagem = "";

									while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if ($qrBuscaCampanhaExtra['TIP_PONTUACAO'] == "ABS") {
											$tipoGanho = $nom_tpcampa;
										} else {
											$tipoGanho = "Percentual";
										}

										if ($qrBuscaCampanhaExtra['COD_VANTAGE'] == 1) {
											$tipoVantagem = "<span class='f12'><b> por R$ </b></span>";
										}
										if ($qrBuscaCampanhaExtra['COD_VANTAGE'] == 3) {
											$tipoVantagem = "<span class='f12'><b> por Qtd. </b></span>";
										}

										echo "
										<tr>
										<td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
										<td><small>" . $qrBuscaCampanhaExtra['DES_CATEGOR'] . "</small></td>
										<td><small>" . number_format($qrBuscaCampanhaExtra['VAL_PONTUACAO'], 2, ",", ".") . "</small></td>
										<!-- <td><small>" . number_format($qrBuscaCampanhaExtra['VAL_PONTOEXT'], 2, ",", ".") . "</small></td> -->
										<td><small>" . $tipoGanho . $tipoVantagem . "</small></td>
										</tr>
										<input type='hidden' id='ret_COD_CAMPAPROD_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_CAMPAPROD'] . "'>
										<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_CATEGOR'] . "'>
										<input type='hidden' id='ret_DES_CATEGOR_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_CATEGOR'] . "'>
										<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_SUBCATE'] . "'>
										<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_FORNECEDOR'] . "'>
										<input type='hidden' id='ret_VAL_PONTUACAO_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_PONTUACAO'], 2, ",", ".") . "'>
										<input type='hidden' id='ret_VAL_PONTOEXT_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_PONTOEXT'], 2, ",", ".") . "'>
										<input type='hidden' id='ret_TIP_PONTUACAO_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_PONTUACAO'] . "'>
										<input type='hidden' id='ret_COD_VANTAGE_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_VANTAGE'] . "'>
										";
									}

									?>

								</tbody>
							</table>

						</div>
					</div>

				</div>

				</div>

			</div>
	</div>
	<!-- fim Portlet -->
</div>


<script type="text/javascript">
	parent.$("#conteudoAba1").css("height", ($(".portlet").height() + 50) + "px");
	parent.$("#notificaVantagens").html("<?= $qtdNotifica ?>");

	$(document).ready(function() {

		$(".nav-tabs li").on("click", function(e) {
			if ($(this).hasClass("disabled")) {
				e.preventDefault();
				return false;
			}
		});


	});

	function retornaForm(index) {

		$("#formulario #COD_CAMPAPROD").val($("#ret_COD_CAMPAPROD_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val());
		$("#formulario #COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val());
		$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
		$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #VAL_PONTUACAO").val($("#ret_VAL_PONTUACAO_" + index).val());
		$("#formulario #VAL_PONTOEXT").val($("#ret_VAL_PONTOEXT_" + index).val());
		$("#formulario #TIP_PONTUACAO").val($("#ret_TIP_PONTUACAO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_VANTAGE").val($("#ret_COD_VANTAGE_" + index).val()).trigger("chosen:updated");

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

	}
</script>