<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;
		$cod_opcional = fnLimpaCampo($_REQUEST['COD_OPCIONAL']);
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		$des_opcional = fnLimpaCampo($_REQUEST['DES_OPCIONAL']);
		$abv_opcional = fnLimpaCampo($_REQUEST['ABV_OPCIONAL']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		$qtd_maxopcional = fnLimpaCampoZero($_REQUEST['QTD_MAXOPCIONAL']);
		$val_valor = fnLimpaCampo(fnValorSql($_REQUEST['VAL_VALOR']));
		$NUM_ORDENAC = fnLimpaCampoZero($_REQUEST['NUM_ORDENAC']);
		$tip_calculo = fnLimpaCampo($_REQUEST['TIP_CALCULO']);
		$cod_propriedade = fnLimpaCampo($_REQUEST['COD_PROPRIEDADE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_comment = fnLimpaCampo($_REQUEST['DES_COMMENT']);
		$val_efetivo = fnLimpaCampoZero(fnValorSql($_REQUEST['VAL_EFETIVO']));

		if (empty($_REQUEST['LOG_CORTESIA'])) {
			$log_cortesia = 'N';
		} else {
			$log_cortesia = $_REQUEST['LOG_CORTESIA'];
		}

		if (empty($_REQUEST['LOG_QTD'])) {
			$log_qtd = 'N';
		} else {
			$log_qtd = $_REQUEST['LOG_QTD'];
		}

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "SELECT IFNULL(MAX(NUM_ORDENAC),0)+1 ORDEM FROM OPCIONAIS_ADORAI ";
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$qrModulo = mysqli_fetch_assoc($arrayQuery);
					$NUM_ORDENAC = $qrModulo["ORDEM"];


					$sql = "INSERT INTO OPCIONAIS_ADORAI(
						COD_EMPRESA,
						DES_OPCIONAL,
						ABV_OPCIONAL,
						DES_IMAGEM,
						VAL_VALOR,
						NUM_ORDENAC,
						TIP_CALCULO,
						COD_PROPRIEDADE,
						LOG_CORTESIA,
						LOG_QTD,
						DES_COMMENT,
						VAL_EFETIVO,
						QTD_MAXOPCIONAL,
						COD_USUCADA
					)
					VALUES(
						$cod_empresa,
						'$des_opcional',
						'$abv_opcional',
						'$des_imagem',
						$val_valor,
						$NUM_ORDENAC,
						'$tip_calculo',
						$cod_propriedade,
						'$log_cortesia',
						'$log_qtd',
						'$des_comment',
						'$val_efetivo',
						$qtd_maxopcional,
						$cod_usucada
						)
					 ";
					//fnEscreve($sql);
					//fnTestesql(connTemp($cod_empresa, ''),$sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {
						$cod_error = Log_error_comand($connAdm->connAdm(), $connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					}
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					$sql = "UPDATE OPCIONAIS_ADORAI SET 
						DES_OPCIONAL = '$des_opcional' ,
						ABV_OPCIONAL = '$abv_opcional',
						DES_IMAGEM = '$des_imagem',
						VAL_VALOR = $val_valor,
						NUM_ORDENAC = $NUM_ORDENAC,
						COD_ALTERAC = $cod_usucada,
						COD_PROPRIEDADE = $cod_propriedade,
						TIP_CALCULO = '$tip_calculo',
						LOG_CORTESIA = '$log_cortesia',
						DES_COMMENT = '$des_comment',
						LOG_QTD = '$log_qtd',
						VAL_EFETIVO = '$val_efetivo',
						QTD_MAXOPCIONAL = $qtd_maxopcional,
						DAT_ALTERAC = NOW()
						WHERE COD_OPCIONAL = $cod_opcional
					";
					// retornaForm();
					// fnEscreve($sql);
					//fnTestesql(connTemp($cod_empresa, ''),$sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$sql = "UPDATE OPCIONAIS_ADORAI SET 
						COD_EXCLUSA = $cod_usucada,
						DAT_EXCLUSA = NOW()
						WHERE 
						COD_OPCIONAL = $cod_opcional AND COD_PROPRIEDADE = $cod_propriedade
					";

					$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);
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
	$cod_empresa = 274;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}



?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}

	tr {
		border-bottom: none !important;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando...</div>
</div>

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
				$abaAdorai = 2006;
				include "abasAdorai.php";

				$abaManutencaoAdorai = fnDecode($_GET['mod']);
				//echo $abaUsuario;

				//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_OPCIONAL" id="COD_OPCIONAL" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Propriedades</label>
										<select data-placeholder="Selecione a Propriedade" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect" required>
											<option value="9999">Todas Propriedades</option>
											QTD_MAXOPCIONAL
											<?php
											$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
											$arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

											while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
											?>
												<option value="<?= $qrHotel['COD_EXTERNO'] ?>"><?= $qrHotel['NOM_FANTASI'] ?></option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_OPCIONAL" id="DES_OPCIONAL" value="" maxlength="100" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_OPCIONAL" id="ABV_OPCIONAL" value="" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipos Cálculo</label>
										<select data-placeholder="Selecione o Tipo" name="TIP_CALCULO" id="TIP_CALCULO" class="chosen-select-deselect" required>
											<option value=""></option>
											<option value="UNI">Única</option>
											<option value="DIA">Por dia de hospedagem</option>
											<option value="HXD">Por hóspede x hospedagem</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor</label>
											<input type="text" class="form-control input-sm money" name="VAL_VALOR" id="VAL_VALOR" maxlength="100" value="" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Efetivo</label>
										<input type="text" class="form-control input-sm money" name="VAL_EFETIVO" id="VAL_EFETIVO" maxlength="20">
										<div class="help-block with-errors">Custo</div>
									</div>
								</div>

								<div class="col-md-3">
									<label for="inputName" class="control-label required">Imagem</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" maxlength="100" value="">
										<input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="" required>
									</div>
									<span class="help-block">(.png 300px X 80px)</span>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">É cortesia</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_CORTESIA" id="LOG_CORTESIA"
												class="switch switch-small" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Possui Quantidade</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_QTD" id="LOG_QTD"
												class="switch switch-small" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-3" style="display:none;" id="QTD_MAXOPCIONAL_DIV">
									<div class="form-group">
										<label for="inputName" class="control-label required">Qtd. máxima</label>
										<select data-placeholder="Selecione a Quantidade" name="QTD_MAXOPCIONAL" id="QTD_MAXOPCIONAL" class="chosen-select-deselect">
											<option value="9999">Sem Limite</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10">10</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Comentário</label>
										<textarea class="form-control input-sm" name="DES_COMMENT" id="DES_COMMENT" maxlength="1000" rows="2" value="" data-error="Campo obrigatório" required></textarea>
										<div class="help-block with-errors">Texto "Saiba +" checkout</div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>

						<div class="form-group text-right col-lg-8 col-lg-offset-4">

							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							</div>
						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>



					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-center' width='50'></th>
										<th class='{ sorter: false } text-center'></th>
										<th>Código</th>
										<th>Propriedade</th>
										<th>Descrição</th>
										<th>Abreviação</th>
										<th>Valor</th>
										<th>Tip. Cálculo</th>
										<th class='text-center'>Cortesia</th>
										<th class='text-center'>Possui Quantidade</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "SELECT a.*,
													UNI.NOM_FANTASI
													FROM opcionais_adorai AS a
													LEFT JOIN unidadevenda AS UNI ON UNI.COD_EXTERNO = A.COD_PROPRIEDADE
													WHERE A.COD_EXCLUSA IS NULL order by a.NUM_ORDENAC";
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;
									//fnEscreve($sql);
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										if ($qrBusca['TIP_CALCULO'] == 'UNI') {
											$tip_calc = 'Única';
										} else if ($qrBusca['TIP_CALCULO'] == 'DIA') {
											$tip_calc = 'Por dia de hospedagem';
										} else {
											$tip_calc = 'Por Hóspede x Hospedagem';
										}

										if ($qrBusca['LOG_CORTESIA'] == 'S') {
											$iconeCortesia = '<i class="fal fa-check" aria-hidden="true"></i>';
										} else {
											$iconeCortesia = '';
										}

										if ($qrBusca['LOG_QTD'] == 'S') {
											$iconeQtd = '<i class="fal fa-check" aria-hidden="true"></i>';
										} else {
											$iconeQtd = '';
										}
										// fnEscreve($qrBusca['COD_PROPRIEDADE']);
										echo "
												<tr>
													<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBusca['COD_OPCIONAL'] . "'></span></td>
													<td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrBusca['COD_OPCIONAL'] . "</td>";

										if ($qrBusca['COD_PROPRIEDADE'] == 9999) {
											echo "<td>Todas Propriedades</td>";
										} else {
											echo "<td>" . $qrBusca['NOM_FANTASI'] . "</td>";
										}

										echo "
													<td>" . $qrBusca['DES_OPCIONAL'] . "</td>														
													<td>" . $qrBusca['ABV_OPCIONAL'] . "</td>														
													<td>" . fnValor($qrBusca['VAL_VALOR'], 2) . "</td>							
													<td>" . $tip_calc . "</td>							
													<td class='text-center'>$iconeCortesia</td>														
													<td class='text-center'>$iconeQtd</td>														
												</tr>
												<input type='hidden' id='ret_COD_OPCIONAL_" . $count . "' value='" . $qrBusca['COD_OPCIONAL'] . "'>
												<input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . $qrBusca['COD_PROPRIEDADE'] . "'>
												<input type='hidden' id='ret_DES_OPCIONAL_" . $count . "' value='" . $qrBusca['DES_OPCIONAL'] . "'>
												<input type='hidden' id='ret_ABV_OPCIONAL_" . $count . "' value='" . $qrBusca['ABV_OPCIONAL'] . "'>
												<input type='hidden' id='ret_VAL_VALOR_" . $count . "' value='" . fnValor($qrBusca['VAL_VALOR'], 2) . "'>
												<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBusca['NUM_ORDENAC'] . "'>
												<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBusca['DES_IMAGEM'] . "'>
												<input type='hidden' id='ret_IMAGEM_" . $count . "' value='" . fnBase64DecodeImg($qrBusca['DES_IMAGEM']) . "'>
												<input type='hidden' id='ret_TIP_CALCULO_" . $count . "' value='" . $qrBusca['TIP_CALCULO'] . "'>
												<input type='hidden' id='ret_LOG_CORTESIA_" . $count . "' value='" . $qrBusca['LOG_CORTESIA'] . "'>
												<input type='hidden' id='ret_VAL_EFETIVO_" . $count . "' value='" . fnValor($qrBusca['VAL_EFETIVO'], 2) . "'>
												<input type='hidden' id='ret_QTD_MAXOPCIONAL_" . $count . "' value='" . $qrBusca['QTD_MAXOPCIONAL'] . "'>
												<input type='hidden' id='ret_LOG_QTD_" . $count . "' value='" . $qrBusca['LOG_QTD'] . "'>
												<input type='hidden' id='ret_DES_COMMENT_" . $count . "' value='" . $qrBusca['DES_COMMENT'] . "'>
												";
									}
									?>
								</tbody>
							</table>

						</form>

					</div>

					<div class="push20"></div>

					<div id="AREACODE_OFF" style="display: none;">
						<textarea id="AREACODE"></textarea>
					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	$('#TIP_CALCULO').change(function() {
		var res = $('#TIP_CALCULO').val();
	});

	$('#LOG_QTD').change(function() {
		if ($(this).is(':checked')) {
			$('#QTD_MAXOPCIONAL_DIV').show();
			$('#QTD_MAXOPCIONAL').attr('required', 'required');
		} else {
			$('#QTD_MAXOPCIONAL_DIV').hide();
			$('#QTD_MAXOPCIONAL').removeAttr('required');
		}
	});

	$(function() {

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 12, '<?= $cod_empresa ?>');

				function execOrdenacao(p1, p2, p3) {
					//alert(p2);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacaoEmp.php",
						data: {
							ajx1: p1,
							ajx2: p2,
							ajx3: p3
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// $("#divId_sub").html(data); 
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});

		$(".table-sortable tbody").disableSelection();

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

	});


	//-----------------------------------------------------------------------------------
	$(function() {

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
		});


	});



	function retornaForm(index) {
		$("#formulario #COD_OPCIONAL").val($("#ret_COD_OPCIONAL_" + index).val());
		$("#formulario #DES_OPCIONAL").val($("#ret_DES_OPCIONAL_" + index).val());
		$("#formulario #ABV_OPCIONAL").val($("#ret_ABV_OPCIONAL_" + index).val());
		$("#formulario #DES_COMMENT").val($("#ret_DES_COMMENT_" + index).val());
		$("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #IMAGEM").val($("#ret_IMAGEM_" + index).val());
		$("#formulario #VAL_EFETIVO").val($("#ret_VAL_EFETIVO_" + index).val());
		$("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_CALCULO").val($("#ret_TIP_CALCULO_" + index).val()).trigger("chosen:updated");
		if ($("#ret_QTD_MAXOPCIONAL_" + index).val() == '') {
			$("#formulario #QTD_MAXOPCIONAL").val('9999').trigger("chosen:updated");
		} else {
			$("#formulario #QTD_MAXOPCIONAL").val($("#ret_QTD_MAXOPCIONAL_" + index).val()).trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

		if ($("#ret_LOG_CORTESIA_" + index).val() == 'S') {
			$('#formulario #LOG_CORTESIA').prop('checked', true);
		} else {
			$('#formulario #LOG_CORTESIA').prop('checked', false);
		}

		if ($("#ret_LOG_QTD_" + index).val() == 'S') {
			$('#formulario #LOG_QTD').prop('checked', true);
			$('#QTD_MAXOPCIONAL_DIV').show();
			$('#QTD_MAXOPCIONAL').attr('required', 'required');
		} else {
			$('#formulario #LOG_QTD').prop('checked', false);
			$('#QTD_MAXOPCIONAL_DIV').hide();
			$('#QTD_MAXOPCIONAL').removeAttr('required');
		}
	}
	/*****************************
	 * ESCRIPT PARA UPLOAD IMAGEM 
	 ******************************/
	$('.upload').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	function uploadFile(idField, typeFile) {

		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		if (nomeArquivo.indexOf(' ') > 0) {
			$.alert({
				title: "Erro ao efetuar o upload",
				content: "O nome do arquivo não pode conter espaços, renomeie o arquivo e faça o upload novamente",
				type: 'red'
			});
		} else {

			var formData = new FormData();

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes');
			formData.append('id', <?php echo $cod_empresa ?>);
			formData.append('typeFile', typeFile);

			$('.progress').show();
			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					$('#btnUploadFile').addClass('disabled');
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							if (percentComplete !== 100) {
								$('.progress-bar').css('width', percentComplete + "%");
								$('.progress-bar > span').html(percentComplete + "%");
							}
						}
					}, false);
					return xhr;
				},
				url: '../uploads/uploaddoc.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {

					var data = JSON.parse(data);

					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (data.success) {
						$('#IMAGEM').val(nomeArquivo);
						$('#DES_IMAGEM').val(data.nome_arquivo);

						$.alert({
							title: "Mensagem",
							content: "Upload feito com sucesso",
							type: 'green'
						});

					} else {
						$.alert({
							title: "Erro ao efetuar o upload",
							content: data,
							type: 'red'
						});
					}
				}
			});
		}
	}
</script>