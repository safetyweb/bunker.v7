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
$cod_blklist = "";
$tip_blklist = "";
$nom_blklist = "";
$abv_blklist = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
$temCategoria = "";
$sql1 = "";
$arrayQuery = [];
$qrVerificaHabito = "";
$okProcessar = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$esconde = "";
$popUp = "";
$abaModulo = "";
$andFiltro = "";
$qrBuscaProdutos = "";
$mostraCat = "";
$PaginaAcao = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_blklist = fnLimpaCampoZero(@$_REQUEST['COD_BLKLIST']);
		$tip_blklist = fnLimpaCampo(@$_REQUEST['TIP_BLKLIST']);
		$nom_blklist = fnLimpaCampo(@$_REQUEST['NOM_BLKLIST']);
		$abv_blklist = fnLimpaCampo(@$_REQUEST['ABV_BLKLIST']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			//verifica se já existe categoria cadastrada	
			$temCategoria = 0;
			if ($opcao == 'CAD') {
				$sql1 = "SELECT count(COD_BLKLIST) as temCategoria
							FROM blacklisttkt where COD_EMPRESA = $cod_empresa and tip_blklist = '$tip_blklist'  and COD_EXCLUSA = 0 ";
				//fnEscreve($sql1);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql1);
				$qrVerificaHabito = mysqli_fetch_assoc($arrayQuery);
				$temCategoria = $qrVerificaHabito['temCategoria'];
			}
			if ($temCategoria == 0) {
				$okProcessar = "OK";
			} else {
				$okProcessar = "";
			}

			//fnEscreve($temCategoria);
			//fnEscreve($tip_blklist);
			if ($okProcessar == "OK") {
				$sql = "CALL SP_ALTERA_BLACKLISTTKT (
					 '" . $cod_blklist . "', 
					 '" . $tip_blklist . "', 
					 '" . $nom_blklist . "', 
					 '" . $abv_blklist . "', 
					 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
					 '" . $cod_empresa . "', 
					 '" . $opcao . "'    
					) ";
				//echo $sql;
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
			} else {
				$msgRetorno = "Essa <strong>categoria</strong> já existe. <br/> Para manutenção, selecione a categoria desejada e altere.";
				$msgTipo = 'alert-warning';
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
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}


//fnMostraForm();

?>

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

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php $abaModulo = 1110;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BLKLIST" id="COD_BLKLIST" value="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo da Blacklist</label>
											<select data-placeholder="Selecione um grupo" name="TIP_BLKLIST" id="TIP_BLKLIST" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<option value="CAT">Categoria</option>
												<option value="PRD">Produto</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome da Blacklist</label>
											<input type="text" class="form-control input-sm" name="NOM_BLKLIST" id="NOM_BLKLIST" maxlength="50" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Abreviação</label>
											<input type="text" class="form-control input-sm" name="ABV_BLKLIST" id="ABV_BLKLIST" maxlength="20" required>
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
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Tipo</th>
												<th>Nome</th>
												<th>Abreviação</th>
												<th>Última Atualização</th>
												<th></th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT BLK.COD_BLKLIST,
												   BLK.TIP_BLKLIST,
												   BLK.NOM_BLKLIST,
												   BLK.ABV_BLKLIST,
												   MAX(BLP.DAT_CADASTR) AS ULTIMA_ATUALIZACAO
											FROM BLACKLISTTKT BLK 
											LEFT JOIN BLACKLISTTKTPROD BLP ON BLP.COD_BLKLIST = BLK.COD_BLKLIST
											LEFT JOIN PRODUTOCLIENTE PDC ON PDC.COD_PRODUTO = BLP.COD_PRODUTO
											WHERE BLK.COD_EMPRESA = $cod_empresa 
											AND BLK.COD_EXCLUSA = 0
											$andFiltro
											GROUP BY BLK.COD_BLKLIST";

											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												switch ($qrBuscaProdutos['TIP_BLKLIST']) {
													case "CAT":
														$mostraCat = "Categoria";
														$PaginaAcao = 1117;
														break;
													case "PRD":
														$mostraCat = "Produtos";
														$PaginaAcao = 1192;
														break;
													default:
														$mostraCat = "";
														break;
												}


												echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
											  <td>" . $qrBuscaProdutos['COD_BLKLIST'] . "</td>
											  <td>" . $mostraCat . "</td>
											  <td>" . $qrBuscaProdutos['NOM_BLKLIST'] . "</td>
											  <td>" . $qrBuscaProdutos['ABV_BLKLIST'] . "</td>
											  <td>" . fnDataFull($qrBuscaProdutos['ULTIMA_ATUALIZACAO']) . "</td>
											  <td class='text-center'>
												<a class='btn btn-xs btn-info addBox' href='#' data-url='action.do?mod=" . fnEncode($PaginaAcao) . "&id=" . fnEncode($cod_empresa) . "&idB=" . fnEncode($qrBuscaProdutos['COD_BLKLIST']) . "&pop=true' data-title='Hábitos de Consumo - Exclusão'><i class='fa fa-pencil'></i> Editar </a>
										      </td>
											</tr>
											<input type='hidden' id='ret_COD_BLKLIST_" . $count . "' value='" . $qrBuscaProdutos['COD_BLKLIST'] . "'>
											<input type='hidden' id='ret_TIP_BLKLIST_" . $count . "' value='" . $qrBuscaProdutos['TIP_BLKLIST'] . "'>
											<input type='hidden' id='ret_NOM_BLKLIST_" . $count . "' value='" . $qrBuscaProdutos['NOM_BLKLIST'] . "'>
											<input type='hidden' id='ret_ABV_BLKLIST_" . $count . "' value='" . $qrBuscaProdutos['ABV_BLKLIST'] . "'>
											";
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
		$(document).ready(function() {

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();


		});

		function retornaForm(index) {
			$("#formulario #COD_BLKLIST").val($("#ret_COD_BLKLIST_" + index).val());
			$("#formulario #TIP_BLKLIST").val($("#ret_TIP_BLKLIST_" + index).val()).trigger("chosen:updated");
			$("#formulario #NOM_BLKLIST").val($("#ret_NOM_BLKLIST_" + index).val());
			$("#formulario #ABV_BLKLIST").val($("#ret_ABV_BLKLIST_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>