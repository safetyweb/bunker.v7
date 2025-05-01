<?php

include "../_system/_functionsMain.php";
$codEmpresa = fnLimpaCampo($_GET['codEmpresa']);
//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//echo fnDebug('true');
if (isset($_GET['idC'])) {
	$cod_cliente = fnDecode($_GET['idC']);
	// fnEscreve($cod_cliente);
	$sql = "SELECT * FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente";
	$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($codEmpresa, ''), $sql));

	$num_cgcecpf = $qrCli['NUM_CGCECPF'];
	$nom_cliente = $qrCli['NOM_CLIENTE'];
	$dat_nascime = $qrCli['DAT_NASCIME'];
	$des_emailus = $qrCli['DES_EMAILUS'];
	$cod_sexopes = $qrCli['COD_SEXOPES'];
	$num_celular = $qrCli['NUM_CELULAR'];
	$cod_univend = $qrCli['COD_UNIVEND'];

	if ($qrCli['LOG_SMS'] == 'S') {
		$checkSms = "checked";
	} else {
		$checkSms = "";
	}

	if ($qrCli['LOG_EMAIL'] == 'S') {
		$checkEmail = "checked";
	} else {
		$checkEmail = "";
	}
} else {
	$cod_cliente = 0;
	$num_cgcecpf = "";
	$nom_cliente = "";
	$dat_nascime = "";
	$des_emailus = "";
	$cod_sexopes = "";
	$num_celular = "";
	$checkSms = "";
	$checkEmail = "";
	$cod_univend = "";
}

if (isset($_GET['idCpf'])) {
	$num_cgcecpf = fnDecode($_GET['idCpf']);
} else {
	$num_cgcecpf = "";
}

if ($cod_cliente != 0) {
	$num_cgcecpf = $qrCli['NUM_CGCECPF'];
}

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $codEmpresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($codEmpresa, ''), $sql));
$log_termos = $qrLog['LOG_TERMOS'];

// fnEscreve($sql);

?>

<link href="css/main.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/chosen-bootstrap.css" rel="stylesheet" />
<script src="js/jquery.min.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.mask.min.js"></script>

<style>
	body {
		background-color: transparent;
		overflow: visible !important;
	}

	#COD_UNIVEND_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_UNIVEND_chosen>a {
		height: 56px;
		padding: 12px 16px;
	}

	#COD_SEXOPES_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_SEXOPES_chosen>a {
		height: 56px;
		padding: 12px 16px;
	}

	.chosen-single {
		background-color: #ecf0f1 !important;
		border: none !important;
	}

	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	.f15 {
		font-size: 15px;
	}

	.mb-5 {
		margin-bottom: 5px;
	}
</style>

<div class="container" id="containerCadastrar">
	<form id='formulario'>
		<div id="loadStep">
			<!-- <h6 class="text-center" style="margin-bottom: 15px;">Informe os dados<h6> -->
			<?php
			if ($cod_cliente == 0) {
				$txtBtn = "Cadastrar";
			?>
				<label class="text-danger" style="margin-bottom: -15px;">*</label>
				<input type="text" id="NUM_CGCECPF" name="NUM_CGCECPF" class="form-control input-hg cpf" placeholder="CPF" value="<?= $num_cgcecpf ?>" />
				<div class="errorCpf" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div>
			<?php
			} else {
				$txtBtn = "Atualizar";
			?>
				<input type="hidden" id="NUM_CGCECPF" name="NUM_CGCECPF" value="<?= $num_cgcecpf ?>" />
			<?php
			}
			?>

			<label class="text-danger" style="margin-bottom: -15px;">*</label>
			<input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" value="<?= $nom_cliente ?>" class="form-control input-hg" placeholder="Nome" required />
			<div class="errorNome" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div>

			<label class="text-danger" style="margin-bottom: -15px;">*</label>
			<input type="text" id="DAT_NASCIME" name="DAT_NASCIME" value="<?= $dat_nascime ?>" class="form-control input-hg data" placeholder="Dt. Nascimento" required />
			<div class="errorNascimen" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div>

			<label class="text-danger" style="margin-bottom: -15px;">*</label>
			<input type="text" id="DES_EMAILUS" name="DES_EMAILUS" value="<?= $des_emailus ?>" class="form-control input-hg mb-5" placeholder="e-Mail" required />
			<div class="errorEmail" style="color: red; font-size: 14px; display: none; margin-top: -5px; margin-bottom: 10px;">Campo obrigatório</div>

			<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" <?= $checkEmail ?> class="switch" value="S">
			<label for="LOG_EMAIL" class="control-label f15">Desejo receber emails promocionais</label>

			<div class="push"></div>
			<label class="text-danger" style="margin-bottom: -35px;">*</label>
			<select data-placeholder="Sexo" name="COD_SEXOPES" id="COD_SEXOPES" autocomplete="off" class="chosen-select-deselect" required>
				<option value=""></option>
				<?php

				$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by DES_SEXOPES";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

				while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {
					echo "<option value='" . $qrLayout['COD_SEXOPES'] . "'>" . $qrLayout['DES_SEXOPES'] . "</option>";
				}
				?>
			</select>
			<script type="text/javascript">
				$("#COD_SEXOPES").val("<?= $cod_sexopes ?>").trigger("chosen:updated");
			</script>
			<div class="errorSexo" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div>

			<!-- <label class="text-danger" style="margin-bottom: -15px;">*</label> -->
			<input type="text" id="NUM_CELULAR" name="NUM_CELULAR" value="<?= $num_celular ?>" class="form-control input-hg mb-5 text-center sp_celphones" placeholder="Tel. Celular" />
			<!-- <div class="errorCeluar" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div> -->

			<input type="checkbox" name="LOG_SMS" id="LOG_SMS" <?= $checkSms ?> class="switch" value="S">
			<label for="LOG_SMS" class="control-label f15">Desejo receber SMS com promoções</label>

			<div class="push"></div>
			<label class="text-danger" style="margin-bottom: -35px;">*</label>
			<select data-placeholder="Unidade Mais Próxima" name="COD_UNIVEND" id="COD_UNIVEND" autocomplete="off" class="chosen-select-deselect" required>
				<option value=""></option>
				<?php

				$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $codEmpresa AND DAT_EXCLUSA IS NULL AND LOG_ESTATUS = 'S' AND LOG_ATIVOHS ='S' ORDER BY NOM_FANTASI ";
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
					echo "
							  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
							";
				}
				?>
			</select>
			<script type="text/javascript">
				$("#COD_UNIVEND").val("<?= $cod_univend ?>").trigger("chosen:updated");
			</script>
			<div class="errorUnivend" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">Campo obrigatório</div>


			<?php

			if ($codEmpresa == 124) {

			?>
				<label class="text-danger" style="margin-bottom: -15px;">*</label>
				<input type="number" maxlength="4" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" placeholder="Senha (4 dígitos numéricos)" required />
				<input type="number" maxlength="4" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg" style="-webkit-text-security: disc;" oninput="checkNumberFieldLength(this);" placeholder="Confirme a senha" required />
				<div class="errorLogin" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">*As senhas são diferentes/Campo senha vazio.</div>

			<?php

			} else {

			?>

				<input type="password" id="DES_SENHAUS" name="DES_SENHAUS" class="form-control input-hg" placeholder="Senha" />
				<input type="password" id="DES_SENHAUS_CONF" name="DES_SENHAUS_CONF" class="form-control input-hg" placeholder="Confirme a senha" />
				<div class="errorLogin" style="color: red; font-size: 14px; display: none; margin-top: -17px; margin-bottom: 10px;">*As senhas são diferentes/Campo senha vazio.</div>

			<?php

			}

			if ($txtBtn == "Cadastrar" && $log_termos == 'S') {

			?>

				<input type="checkbox" name="LOG_TERMOS" id="LOG_TERMOS" class="switch" value="S" required>
				<label for="LOG_TERMOS" class="control-label f15">Aceito os Termos e Condições</label><label class="text-danger" style="margin-bottom: -15px;">*</label>
				<div class="errorTermos" style="color: red; font-size: 14px; display: none; margin-top: -13px; margin-bottom: 10px;">*É necessário aceitar os Termos e Condições.</div>

			<?php
			}
			?>

			<button type="button" class="btn btn-primary btn-hg btn-block" name="btnCad" id="btnCad"><?= $txtBtn ?></button>

			<div class="push10"></div>
		</div>
	</form>

</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("#COD_SEXOPES").chosen();
		$("#COD_UNIVEND").chosen();

		parent.$('#popModal').find('.modal-content').css({
			'height': '800px'
		});

		$('.cpf').mask('000.000.000-00', {
			reverse: true
		});
		$('.data').mask('00/00/0000');

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').on('input propertychange paste', function(e) {
			var reg = /^0+/gi;
			if (this.value.match(reg)) {
				this.value = this.value.replace(reg, '');
			}
		});

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('#btnCad').click(function() {

			// alert('chega');

			var nome = $('#NOM_CLIENTE').val(),
				cpf = $('#NUM_CGCECPF').val(),
				nasc = $('#DAT_NASCIME').val(),
				email = $('#DES_EMAILUS').val(),
				sexo = $('#COD_SEXOPES').val(),
				univend = $('#COD_UNIVEND').val(),
				senha = $('#DES_SENHAUS').val(),
				con_senha = $('#DES_SENHAUS_CONF').val(),
				cod_cliente = "<?= $cod_cliente ?>",
				log_termos = "<?= $log_termos ?>",
				aceito = "S";

			if (cod_cliente != 0) {
				opcao = "atualizar";
			} else {
				opcao = "cadastrar";
			}

			// alert(opcao);

			if (log_termos == 'S' && opcao == "cadastrar") {
				if (!$('#LOG_TERMOS').prop('checked')) {
					aceito = "N";
					$('.errorTermos').show();
				} else {
					$('.errorTermos').hide();
				}
			}

			// alert(senha);
			// alert(con_senha);
			// alert(nome);
			// alert(nasc);
			// alert(email);
			// alert(sexo);
			// alert(univend);
			// alert(cpf);

			if (senha == con_senha && nome != "" && nasc != "" && email != "" && sexo != "" && univend != "" && senha != "" && cpf != "") {

				if (aceito == "S") {

					// alert('entra');

					$.ajax({
						type: "POST",
						url: "ajxCadastroCli.do?id=<?= fnEncode($codEmpresa) ?>&opcao=" + opcao,
						data: $("#formulario").serialize(),
						beforeSend: function() {
							$('#loadStep').html('<div class="loading"></div>');
						},
						success: function(data) {
							parent.$('#popModal').find('iframe').css({
								'height': '200px'
							});
							$('#loadStep').html(data);
							console.log(data);
						},
						error: function() {
							$('#loadStep').html('Oops... Ocorreu um erro');
						}
					});
				}

			}

			if (senha != con_senha || senha == "") {
				$('.errorLogin').show();
			} else {
				$('.errorLogin').hide();
			}

			if (cpf == "") {
				$('.errorCpf').show();
			} else {
				$('.errorCpf').hide();
			}

			if (nome == "") {
				$('.errorNome').show();
			} else {
				$('.errorNome').hide();
			}

			if (nasc == "") {
				$('.errorNascimen').show();
			} else {
				$('.errorNascimen').hide();
			}

			if (email == "") {
				$('.errorEmail').show();
			} else {
				$('.errorEmail').hide();
			}

			if (univend == "") {
				$('.errorUnivend').show();
			} else {
				$('.errorUnivend').hide();
			}

			if (sexo == "") {
				$('.errorSexo').show();
			} else {
				$('.errorSexo').hide();
			}

		});

	});

	function checkNumberFieldLength(elem) {
		if (elem.value.length > 4) {
			elem.value = elem.value.slice(0, 4);
		}
	}
</script>