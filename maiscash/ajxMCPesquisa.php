<?php

include '../_system/_functionsMain.php';
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
// include_once '../totem/funWS/saldo.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
$opcao = fnLimpaCampo($_GET['opcao']);
$tipo = fnLimpaCampo($_GET['tip']);
$urltotem = fnDecode($_POST["URL_TOTEM"]);
$pref = fnLimpaCampo($_POST["PREF"]);
$cod_usucada = fnLimpaCampoZero(fnDecode($_POST["COD_USUCADA"]));
$casasDec = fnLimpaCampo($_POST["CASAS_DEC"]);

$arrayCampos = explode(";", $urltotem);

$urlWebservice = $arrayCampos;

$canal = 6;

$valida = 0;

if ($opcao == "SRES") {
	$opcao = "VEN";
}

switch ($opcao) {

	case "TKNCAD":


		$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));
		$qtd_chartkn = fnLimpaCampo($_POST['QTD_CHARTKN']);
		$tip_token = fnLimpaCampo($_POST['TIP_TOKEN']);
		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
		$des_emailus = fnLimpaCampo($_POST['DES_EMAILUS']);
		$cad_des_emailus = fnLimpaCampo($_POST['CAD_DES_EMAILUS']);

		if ($num_celular != "" || $des_emailus != "") {

			if ($num_celular == "") {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
			}

			if ($num_cgcecpf == "") {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
			}

			if ($num_cgcecpf == "00000000000") {
				$num_cgcecpf = $num_celular;
			}

			include_once '../totem/funWS/GeraToken.php';

			$dadosenvio = array(
				'tipoGeracao' => '1',
				'nome' => "$nom_cliente",
				'cpf' => "$num_cgcecpf",
				'celular' => "$num_celular",
				'email' => "$des_emailus"
			);

			$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

			$cod_envio = $retornoEnvio['body']['envelope']['body']['geratokenresponse']['retornatoken']['coderro'];
		} else {

			$cod_envio = 0;
		}

		if ($cod_envio == 39) {

			if ($tip_token == 2) {
				$type = "number";
			} else {
				$type = "text";
			}

?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token enviado! Peça para o cliente verificar e informar o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
		} else if ($cod_envio == 0) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token não enviado, pois não há celular/email de destino. É necessário configurar a matriz de campos.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
		} else if ($cod_envio == 2) {
		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-warning" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token não enviado, pois não há saldo.
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

		<?php
		} else {

		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					O token já havia sido enviado. Peça para o cliente verificar e informar o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>

			<script type="text/javascript">
				$("#btnCadastro").fadeOut('fast');
			</script>

			<?php

		}

		if ($cod_envio != 0) {

			if ($k_num_cartao != "") {
				$buscaconsumidor['cartao'] = $k_num_cartao;
			} else {
				$k_num_cartao = $buscaconsumidor['cartao'];
			}

			if ($k_num_celular != "") {
				$buscaconsumidor['telcelular'] = $k_num_celular;
			} else {
				$k_num_celular = $buscaconsumidor['telcelular'];
			}

			if ($k_num_cgcecpf != "") {
				$buscaconsumidor['cpf'] = $k_num_cgcecpf;
			} else {
				$k_num_cgcecpf = $buscaconsumidor['cpf'];
			}

			if ($k_dat_nascime != "") {
				$buscaconsumidor['datanascimento'] = $k_dat_nascime;
			} else {
				$k_dat_nascime = $buscaconsumidor['datanascimento'];
			}

			if ($k_des_emailus != "") {
				$buscaconsumidor['email'] = $k_des_emailus;
			} else {
				$k_des_emailus = $buscaconsumidor['email'];
			}

			if ($buscaconsumidor['cpf'] == "00000000000") {
				$buscaconsumidor['cpf'] = "";
			}

			$sqlCampos = "SELECT NOM_CAMPOOBG, 
								 NOM_CAMPOOBG, 
								 DES_CAMPOOBG, 
								 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
								 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
								 (SELECT COUNT(MCI.TIP_CAMPOOBG) 
									FROM matriz_campo_integracao MCI
									WHERE MCI.TIP_CAMPOOBG = 'OBG' 
									AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
									AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
								 COL_MD, 
								 COL_XS, 
								 CLASSE_INPUT, 
								 CLASSE_DIV 
							FROM MATRIZ_CAMPO_INTEGRACAO                         
							LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
							WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'TKN'
							AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'
							AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
							ORDER BY NUM_ORDENAC ASC, COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

			$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

			// echo($sqlCampos);

			$lastField = "";

			while ($qrCampos = mysqli_fetch_assoc($arrayCampos)) {

				$colMd = $qrCampos['COL_MD'];
				$colXs = $qrCampos['COL_XS'];
				$dataError = "";

				$required = "";
				// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

				if ($lastField == "") {
					$lastField = $qrCampos['NOM_CAMPOOBG'];
				} else if ($lastField == $qrCampos['NOM_CAMPOOBG']) {
					continue;
				} else {
					$lastField = $qrCampos['NOM_CAMPOOBG'];
				}

				if ($qrCampos['OBRIGATORIO'] > 0) {
					$required = "required";
					$dataError = "data-error='Campo obrigatório'";
				}

				// echo "$qrCampos[CAT_CAMPO]";

				if ($colMd == "" || $colMd == 0) {
					$colMd = 12;
				}

				if ($colXs == "" || $colXs == 0) {
					$colXs = 12;
				}

				switch ($qrCampos['DES_CAMPOOBG']) {

					case 'NOM_CLIENTE':

						$dado = $buscaconsumidor['nome'];

						break;

					case 'COD_SEXOPES':

						$dado = $buscaconsumidor['sexo'];

						break;

					case 'DES_EMAILUS':

						$dado = $buscaconsumidor['email'];

						break;

					case 'NUM_CELULAR':

						$dado = $buscaconsumidor['telcelular'];

						break;

					case 'NUM_CARTAO':

						$dado = $buscaconsumidor['cartao'];

						break;

					case 'NUM_CGCECPF':

						$dado = $buscaconsumidor['cpf'];

						break;


					case 'DAT_NASCIME':

						$dado = $buscaconsumidor['datanascimento'];

						break;

					case 'COD_PROFISS':

						$dado = $buscaconsumidor['profissao'];

						break;

					case 'COD_ATENDENTE':

						$dado = $buscaconsumidor['codatendente'];

						break;

					case 'DES_SENHAUS':

						$dado = $buscaconsumidor['senha'];

						break;

					case 'DES_ENDEREC':

						$dado = $buscaconsumidor['endereco'];

						break;

					case 'NUM_ENDEREC':

						$dado = $buscaconsumidor['numero'];

						break;

					case 'NUM_CEPOZOF':

						$dado = $buscaconsumidor['cep'];

						break;

					case 'estado':

						$dado = $buscaconsumidor['estado'];

						break;

					case 'NOM_CIDADEC':

						$dado = $buscaconsumidor['cidade'];

						break;

					case 'DES_BAIRROC':

						$dado = $buscaconsumidor['bairro'];

						break;

					case 'DES_COMPLEM':

						$dado = $buscaconsumidor['complemento'];

						break;

					default:

						$dado = "";

						break;
				}

				switch ($qrCampos['TIPO_DADO']) {

					case 'Data':

			?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<input type="tel" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?> data" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" maxlenght="10" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

						break;

					case 'email':

						$dataError = "";

					?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<input type="email" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
								<div class="help-block with-errors"></div>
							</div>
						</div>

						<?php

						break;

					case 'numeric':

						if ($qrCampos['DES_CAMPOOBG'] == "COD_SEXOPES") {

						?>
							<div class="col-xs-12 text-left">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
									<select data-placeholder="Sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
										<option value=""></option>
										<?php
										$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
											echo "
															  <option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
															";
										}
										?>
									</select>
									<script type="text/javascript">
										$("#COD_SEXOPES").val("<?= $dado ?>").trigger('chosen:updated');
									</script>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php

						} else if ($qrCampos['DES_CAMPOOBG'] == "COD_PROFISS") {

						?>
							<div class="col-xs-12 text-left">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
									<select data-placeholder="Profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
										<option value=""></option>
										<?php
										$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";
										if (mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0') {
											$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
										} else {
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
											echo "
															  <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
															";
										}
										?>
									</select>
									<script type="text/javascript">
										$("#COD_PROFISS").val("<?= $dado ?>").trigger('chosen:updated');
									</script>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php

						} else if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTACIV") {

						?>
							<div class="col-xs-12 text-left">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
									<select data-placeholder="Estado Civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
										<option value=""></option>
										<?php
										$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
											echo "
															  <option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
															";
										}
										?>
									</select>
									<script type="text/javascript">
										$("#COD_ESTACIV").val("<?= $dado ?>").trigger('chosen:updated');
									</script>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php

						} else {

							$type = "text";

							if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
								$nomeCampo = "CPF/CNPJ";
								$mask = "cpfcnpj";
								$type = "tel";
							} else {
								$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
								$mask = "";
							}


						?>
							<div class="col-xs-12 text-left">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
									<input type="<?= $type ?>" value="<?= $dado ?>" placeholder="<?= $nomeCampo ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?> <?= $mask ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php

						}

						break;

					default:


						$type = "text";

						if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
							$nomeCampo = "CPF/CNPJ";
							$mask = "cpfcnpj";
							$type = "tel";
						} else if ($qrCampos['DES_CAMPOOBG'] == "NUM_CELULAR" || $qrCampos['DES_CAMPOOBG'] == "NUM_TELEFONE" || $qrCampos['DES_CAMPOOBG'] == "NUM_CEPOZOF") {
							$type = "tel";
						} else {
							$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
							$mask = "";
						}

						?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<input type="<?= $type ?>" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
								<div class="help-block with-errors"></div>
							</div>
						</div>

			<?php

						break;
				}
			}



			?>

			<div class="push30"></div>

			<div class="col-md-8 col-xs-12 text-left p-r-0">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Token</label> -->
					<input type="<?= $type ?>" placeholder="Token" name="DES_TOKEN" id="DES_TOKEN" value="" maxlength="<?= $qtd_chartkn ?>" class="form-control input-lg" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 p-l-0">
				<!-- <label>&nbsp;</label> -->
				<a style="width: 100%; border-radius: 0!important;" class="btn btn-info btn-lg f18" onclick='ajxCliente("VALTKNCAD","")'>Validar Token</a>
			</div>

			<div class="push20"></div>




			<?php

		}

		break;

	case "VALTKNCAD":

		$des_token = fnLimpaCampo(fnLimpaDoc($_POST['DES_TOKEN']));
		$tipo = fnLimpaCampoZero($_GET['tip']);

		$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);
		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
		$num_cartao = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));

		if ($num_cartao == "") {
			$num_cartao = $num_celular;
		}

		if ($tipo == 0) {

			// fnEscreve('entrou if');

			include_once '../totem/funWS/GeraToken.php';

			$dadosenvio = array(
				'tipoGeracao' => '1',
				'token' => "$des_token",
				'celular' => "$num_celular",
				'cpf' => "$num_cgcecpf"
			);

			$retornoEnvio = ValidaToken($dadosenvio, $arrayCampos);

			// echo '<pre>';
			//    print_r($dadosenvio);
			//    print_r($retornoEnvio);
			//    echo '</pre>';
			// exit();

			$cod_envio = $retornoEnvio['body']['envelope']['body']['validatokenresponse']['retornatoken']['coderro'];
		} else {

			// fnEscreve('entrou else');

			$cod_envio = 99;
		}

		if ($cod_envio == 39 || $cod_envio == 99) {

			// fnEscreve($tipo);
			// fnEscreve($cod_envio);
			// exit();

			include_once '../totem/funWS/atualizacadastro.php';

			$adesao = "ST";
			// WEBSERVICE DE CADASTRO MAIS.CASH
			include '../totem/cadastroMaisCashWS.php';


			$sqlCod = "SELECT COD_CLIENTE, NOM_CLIENTE, NUM_CELULAR, COD_UNIVEND FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CARTAO = '$num_cartao'";
			$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);

			$qrCod = mysqli_fetch_assoc($arrayCod);

			// fnEscreve($sqlCod);
			// exit();

			if ($qrCod['NUM_CELULAR'] != "") {

				// include "../_system/func_nexux/Envio_online_venda.php";
				include "../_system/func_nexux/envioFast.php";
				include '../_system/func_nexux/func_transacional.php';

				$array = array(
					'CONNADM' => $connAdm->connAdm(),
					'CONNTMP' => connTemp($cod_empresa, ''),
					'COD_EMPRESA' => $cod_empresa,
					'COD_UNIVEND' => fnLimpaCampoZero($qrCod["COD_UNIVEND"]),
					'NOMECLIENTE' => $qrCod["NOM_CLIENTE"],
					'COD_CLIENTE' => $qrCod["COD_CLIENTE"],
					'TELEFONE' => fnLimpaDoc($qrCod["NUM_CELULAR"]),
					'CASAS_DEC' => $casasDec,
					'TIP_OPERACAO' => 'cadFast'
				);

				$teste1 = envio_fast_sms($array);

				// echo "<pre>";
				// print_r($teste1);
				// echo "</pre>";
				// exit();

			}



			if ($log_cadastro == 'S') {

				if ($des_token != '') {

					$sqlUpdate = "UPDATE GERATOKEN SET DES_CANAL = 3 WHERE NUM_CGCECPF = $num_cgcecpf AND COD_EMPRESA = $cod_empresa";
					mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);
				}

				if ($teste1['coderro'] != 5) {
					if ($teste1['coderro'] == 2) {
						$msgErro = '<div class="col-md-12 col-xs-12 text-left"><div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>SMS NÃO ENVIADO > SALDO SMS ZERADO > <a href="https://' . $_SERVER['HTTP_HOST'] . '/action.do?mod=' . fnEncode(1485) . '&id=' . fnEncode($cod_empresa) . '" target="_blank">RECARREGUE</a></b></div></div><div class="push10"></div>';
					} else {
						$msgErro = '<div class="col-md-12 col-xs-12 text-left"><div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>SMS não enviado: ' . $teste1["msgerro"] . '</div></div><div class="push10"></div>';
					}
				} else {
					$msgErro = "";
				}

			?>

				<script>
					$("#relatorioValidaToken").fadeOut('fast', function() {

						$("#relatorioValidaToken").html('<?= $msgErro ?><div class="col-md-12 col-xs-12 text-left">' +

							'<div class="alert alert-success" role="alert">' +
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
							'Cliente Cadastrado! Agora, entre com o valor da venda:' +
							'</div>' +

							'</div>');



						$("#relatorioValidaToken").fadeIn('fast');
						$("#btnCadastro").fadeOut('fast');

						ajxCliente("VEN", "");

						$("#CAD_NUM_CELULAR,NOM_USUARIO").attr("readonly", true);
						$("#CAD_NUM_CGCECPF,NOM_USUARIO").attr("readonly", true);

					});
				</script>

			<?php


			} else {

			?>



				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?= $atualiza ?>
					</div>

				</div>



			<?php

			}
		} else {

			?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Token inválido.
				</div>

			</div>



		<?php

		}

		break;

	case 'BUS':

		?>

		<style type="text/css">
			.alert {
				padding: 15px;
				margin-bottom: 20px;
				border: 1px solid transparent;
				border-radius: 4px;
			}

			.alert h4 {
				margin-top: 0;
				color: inherit;
			}

			.alert .alert-link {
				font-weight: bold;
			}

			.alert>p,
			.alert>ul {
				margin-bottom: 0;
			}

			.alert>p+p {
				margin-top: 5px;
			}

			.alert-dismissable,
			.alert-dismissible {
				padding-right: 35px;
			}

			.alert-dismissable .close,
			.alert-dismissible .close {
				position: relative;
				top: -2px;
				right: -21px;
				color: inherit;
			}

			.alert-success {
				background-color: #d4edda;
				border-color: #c3e6cb;
				color: #155724;
				padding-top: 10px;
				padding-bottom: 10px;
			}

			.alert-success hr {
				border-top-color: #c3e6cb;
			}

			.alert-success .alert-link,
			.alert-success .close {
				color: #0b2e13;
			}

			.alert-danger {
				background-color: #f8d7da;
				border-color: #f5c6cb;
				color: #721c24;
				padding-top: 10px;
				padding-bottom: 10px;
			}

			.alert-danger hr {
				border-top-color: #f5c6cb;
			}

			.alert-danger .alert-link,
			.alert-danger .close {
				color: #491217;
			}

			.alert-info {
				background-color: #cce5ff;
				border-color: #b8daff;
				color: #004085;
				padding-top: 10px;
				padding-bottom: 10px;
			}

			.alert-info hr {
				border-top-color: #b8daff;
			}

			.alert-info .alert-link,
			.alert-info .close {
				color: #002752;
			}

			.alert-dark {
				background-color: #d6d8d9;
				border-color: #c6c8ca;
				color: #1b1e21;
				padding-top: 10px;
				padding-bottom: 10px;
			}

			.alert-dark hr {
				border-top-color: #c6c8ca;
			}

			.alert-dark .alert-link,
			.alert-dark .close {
				color: #040505;
			}

			.alert-warning {
				background-color: #fff3cd;
				border-color: #ffeeba;
				color: #856404;
				padding-top: 10px;
				padding-bottom: 10px;
			}

			.alert-warning hr {
				border-top-color: #ffeeba;
			}

			.alert-warning .alert-link,
			.alert-warning .close {
				color: #002752;
			}
		</style>

		<?php
		if (isset($_REQUEST['KEY_NUM_CARTAO'])) {
			$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
		} else {
			$k_num_cartao = "";
		}

		if (isset($_REQUEST['KEY_NUM_CARTAO'])) {
			$c_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
		} else {
			$c_num_cartao = "";
		}

		if (isset($_REQUEST['KEY_NUM_CELULAR'])) {
			$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
		} else {
			$k_num_celular = "";
		}

		if (isset($_REQUEST['KEY_NUM_CELULAR'])) {
			$c_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
		} else {
			$c_num_celular = "";
		}

		if (isset($_REQUEST['KEY_COD_EXTERNO'])) {
			$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
		} else {
			$k_cod_externo = "";
		}

		if (isset($_REQUEST['KEY_COD_EXTERNO'])) {
			$c_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
		} else {
			$c_cod_externo = "";
		}

		if (isset($_REQUEST['KEY_NUM_CGCECPF'])) {
			$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
		} else {
			$k_num_cgcecpf = "";
		}

		if (isset($_REQUEST['KEY_NUM_CGCECPF'])) {
			$c_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
		} else {
			$c_num_cgcecpf = "";
		}

		if (isset($_REQUEST['KEY_DAT_NASCIME'])) {
			$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
		} else {
			$k_dat_nascime = "";
		}

		if (isset($_REQUEST['KEY_DAT_NASCIME'])) {
			$c_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
		} else {
			$c_dat_nascime = "";
		}

		if (isset($_REQUEST['KEY_DES_EMAILUS'])) {
			$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);
		} else {
			$k_des_emailus = "";
		}

		if (isset($_REQUEST['KEY_DES_EMAILUS'])) {
			$c_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);
		} else {
			$c_des_emailus = "";
		}


		$whereSql = "";

		if ($k_num_cartao != "") {
			$whereSql .= "OR NUM_CARTAO = '$k_num_cartao' ";
		}

		if ($k_num_celular != "") {
			$whereSql .= "OR NUM_CELULAR = '$k_num_celular' ";
		}

		if ($k_cod_externo != "") {
			$whereSql .= "OR COD_EXTERNO = '$k_cod_externo' ";
		}

		if ($k_num_cgcecpf != "" && $k_num_cgcecpf != "00000000000") {
			$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
		}

		if ($k_dat_nascime != "") {
			$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
		}

		if ($k_des_emailus != "") {
			$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
		}

		$whereSql = trim(ltrim($whereSql, "OR"));

		$sqlCli = "SELECT * FROM CLIENTES 
				       WHERE COD_EMPRESA = $cod_empresa
				       AND ($whereSql)
				       ORDER BY 1 LIMIT 1";

		// fnEscreve($sqlCli);

		$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

		$qrCli = mysqli_fetch_assoc($arrayCli);

		if (isset($qrCli)) {

			$cpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
			$k_num_cartao = fnLimpaDoc($qrCli['NUM_CARTAO']);
			$k_num_celular = $qrCli['NUM_CELULAR'];
			$k_num_cgcecpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
			$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
		} else {
			$cod_cliente = 0;
		}


		$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

		$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

		// fnEscreve($cpf);

		$lastField = "";

		$qrCampos = mysqli_fetch_assoc($arrayFields);

		$log_cadtoken = $qrCampos['LOG_CADTOKEN'];

		// fnconsulta_V2($qrCampos[COD_CHAVECO], $dado, $arrayCampos);
		// fnEscreve($qrCampos[COD_CHAVECO]."/ cartao: $k_num_cartao / celular: $k_num_celular / cpf: $k_num_cgcecpf");
		// exit();

		switch ($qrCampos['COD_CHAVECO']) {

			case 2:
				$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], $k_num_cartao, $arrayCampos);
				break;
			case 3:
				$buscaconsumidor = fnconsulta_V2($qrCampos['COD_CHAVECO'], fnLimpaDoc($k_num_celular), $arrayCampos);
				break;

			default:

				if (strlen($k_num_cgcecpf) <= '11') {

					// echo '<pre>';

					$buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf, 'F'), $arrayCampos);

					// print_r($buscaconsumidor);

					// echo '</pre>';

				} else {

					// echo 'else';

					$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf, 'J'), $arrayCampos);
				}

				break;
		}

		if ($buscaconsumidor['cpf'] != '00000000000') {

			$cpf = $buscaconsumidor['cpf'];
		} else {
			$cpf = $k_num_cgcecpf;
			// $buscaconsumidor['nome'] = "";
		}

		if ($buscaconsumidor['cartao'] != "") {
			$cartao = $buscaconsumidor['cartao'];
			$c10 = $buscaconsumidor['cartao'];
		}

		if ($buscaconsumidor['cartao'] == '0') {
			$buscaconsumidor['nome'] = "";
		}

		// echo '<pre>';
		//    print_r($buscaconsumidor);
		//    echo '</pre>';
		//    exit();

		$readonly = "";

		$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";

		if ($cod_cliente != 0) {

			// fnEscreve($cod_cliente);

			$readonly = "readonly";
			$andOpc = "";

			$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
									AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
									$andOpc";


		?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Cliente encontrado!
				</div>

			</div>



		<?php
		} else if ($cod_cliente == 0 && $log_cadtoken == 'S') {

			$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG = 'TKN'";

		?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-info" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Cliente não cadastrado. Peça ao cliente que tenha o celular em mãos para recebimento do token, e informe os campos abaixo para realizar o cadastro:
				</div>

			</div>



		<?php
		} else {

			$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
									AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
									AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'TKN'
									$andOpc";

		?>



			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-info" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Cliente não cadastrado. Informe os campos abaixo para realizar o cadastro:
				</div>

			</div>



			<?php

		}

		// DESCOMENTAR CASO HAJA ATUALIZAÇÃO DE CADASTRO
		// if($cod_cliente == 0){
		// 	$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";
		// }else{
		// 	$andOpc = "";
		// }

		if ($k_num_cartao != "") {
			$buscaconsumidor['cartao'] = $k_num_cartao;
		} else {
			$k_num_cartao = $buscaconsumidor['cartao'];
		}

		if ($k_num_celular != "") {
			$buscaconsumidor['telcelular'] = $k_num_celular;
		} else {
			$k_num_celular = $buscaconsumidor['telcelular'];
		}

		if ($k_num_cgcecpf != "") {
			$buscaconsumidor['cpf'] = $k_num_cgcecpf;
		} else {
			$k_num_cgcecpf = $buscaconsumidor['cpf'];
		}

		if ($k_dat_nascime != "") {
			$buscaconsumidor['datanascimento'] = $k_dat_nascime;
		} else {
			$k_dat_nascime = $buscaconsumidor['datanascimento'];
		}

		if ($k_des_emailus != "") {
			$buscaconsumidor['email'] = $k_des_emailus;
		} else {
			$k_des_emailus = $buscaconsumidor['email'];
		}

		if ($buscaconsumidor['cpf'] == "00000000000") {
			$buscaconsumidor['cpf'] = "";
		}

		$sqlCampos = "SELECT NOM_CAMPOOBG, 
								 NOM_CAMPOOBG, 
								 DES_CAMPOOBG, 
								 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
								 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
								 (SELECT COUNT(MCI.TIP_CAMPOOBG) 
									FROM matriz_campo_integracao MCI
									WHERE MCI.TIP_CAMPOOBG = 'OBG' 
									AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
									AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
								 COL_MD, 
								 COL_XS, 
								 CLASSE_INPUT, 
								 CLASSE_DIV 
							FROM MATRIZ_CAMPO_INTEGRACAO                         
							LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
							WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
							AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
							$camposIniciais
							ORDER BY NUM_ORDENAC ASC, COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

		$arrayCampos = mysqli_query($connAdm->connAdm(), $sqlCampos);

		// fnEscreve($sqlCampos);

		$lastField = "";

		while ($qrCampos = mysqli_fetch_assoc($arrayCampos)) {

			// echo "<pre>";
			// print_r($qrCampos);
			// echo "</pre>";

			$colMd = $qrCampos['COL_MD'];
			$colXs = $qrCampos['COL_XS'];
			$dataError = "";

			$required = "";
			// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

			if ($lastField == "") {
				$lastField = $qrCampos['NOM_CAMPOOBG'];
			} else if ($lastField == $qrCampos['NOM_CAMPOOBG']) {
				continue;
			} else {
				$lastField = $qrCampos['NOM_CAMPOOBG'];
			}

			if ($qrCampos['OBRIGATORIO'] > 0) {
				$required = "required";
				$dataError = "data-error='Campo obrigatório'";
			}

			// echo "$qrCampos[CAT_CAMPO]";

			if ($colMd == "" || $colMd == 0) {
				$colMd = 12;
			}

			if ($colXs == "" || $colXs == 0) {
				$colXs = 12;
			}

			switch ($qrCampos['DES_CAMPOOBG']) {

				case 'NOM_CLIENTE':

					$dado = $buscaconsumidor['nome'];

					break;

				case 'COD_SEXOPES':

					$dado = $buscaconsumidor['sexo'];

					break;

				case 'DES_EMAILUS':

					$dado = $buscaconsumidor['email'];

					break;

				case 'NUM_CELULAR':

					$dado = $buscaconsumidor['telcelular'];

					if ($c_num_celular != "") {
						$dado = $c_num_celular;
					}

					break;

				case 'NUM_CARTAO':

					$dado = $buscaconsumidor['cartao'];

					if ($c_num_cartao != "") {
						$dado = $c_num_cartao;
					}

					break;

				case 'NUM_CGCECPF':

					$dado = $buscaconsumidor['cpf'];

					if ($c_num_cgcecpf != "") {
						$dado = $c_num_cgcecpf;
					}

					break;


				case 'DAT_NASCIME':

					$dado = $buscaconsumidor['datanascimento'];

					break;

				case 'COD_PROFISS':

					$dado = $buscaconsumidor['profissao'];

					break;

				case 'COD_ATENDENTE':

					$dado = $buscaconsumidor['codatendente'];

					break;

				case 'DES_SENHAUS':

					$dado = $buscaconsumidor['senha'];

					break;

				case 'DES_ENDEREC':

					$dado = $buscaconsumidor['endereco'];

					break;

				case 'NUM_ENDEREC':

					$dado = $buscaconsumidor['numero'];

					break;

				case 'NUM_CEPOZOF':

					$dado = $buscaconsumidor['cep'];

					break;

				case 'estado':

					$dado = $buscaconsumidor['estado'];

					break;

				case 'NOM_CIDADEC':

					$dado = $buscaconsumidor['cidade'];

					break;

				case 'DES_BAIRROC':

					$dado = $buscaconsumidor['bairro'];

					break;

				case 'DES_COMPLEM':

					$dado = $buscaconsumidor['complemento'];

					break;

				default:

					$dado = "";

					break;
			}

			switch ($qrCampos['TIPO_DADO']) {

				case 'Data':

			?>
					<div class="col-xs-12 text-left">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
							<input type="tel" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?> data" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" maxlenght="10" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
							<div class="help-block with-errors"></div>
						</div>
					</div>

				<?php

					break;

				case 'email':

					$dataError = "";

				?>
					<div class="col-xs-12 text-left">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
							<input type="email" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<?php

					break;

				case 'numeric':

					if ($qrCampos['DES_CAMPOOBG'] == "COD_SEXOPES") {

					?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<select data-placeholder="Sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
									<option value=""></option>
									<?php
									$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
															";
									}
									?>
								</select>
								<script type="text/javascript">
									$("#COD_SEXOPES").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else if ($qrCampos['DES_CAMPOOBG'] == "COD_PROFISS") {

					?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<select data-placeholder="Profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
									<option value=""></option>
									<?php
									$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";
									if (mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0') {
										$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
									} else {
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									}

									while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
															";
									}
									?>
								</select>
								<script type="text/javascript">
									$("#COD_PROFISS").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else if ($qrCampos['DES_CAMPOOBG'] == "COD_ESTACIV") {

					?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<select data-placeholder="Estado Civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" <?= $required ?> <?= $readonly ?>>
									<option value=""></option>
									<?php
									$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
										echo "
															  <option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
															";
									}
									?>
								</select>
								<script type="text/javascript">
									$("#COD_ESTACIV").val("<?= $dado ?>").trigger('chosen:updated');
								</script>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					} else {

						$type = "text";

						if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
							$nomeCampo = "CPF/CNPJ";
							$mask = "cpfcnpj";
							$type = "tel";
						} else {
							$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
							$mask = "";
						}


					?>
						<div class="col-xs-12 text-left">
							<div class="form-group">
								<label>&nbsp;</label>
								<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
								<input type="<?= $type ?>" value="<?= $dado ?>" placeholder="<?= $nomeCampo ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?> <?= $mask ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
								<div class="help-block with-errors"></div>
							</div>
						</div>

					<?php

					}

					break;

				default:

					$type = "text";

					if ($qrCampos['DES_CAMPOOBG'] == "NUM_CGCECPF") {
						$nomeCampo = "CPF/CNPJ";
						$mask = "cpfcnpj";
						$type = "tel";
					} else if ($qrCampos['DES_CAMPOOBG'] == "NUM_CELULAR" || $qrCampos['DES_CAMPOOBG'] == "NUM_TELEFONE" || $qrCampos['DES_CAMPOOBG'] == "NUM_CEPOZOF") {
						$type = "tel";
					} else {
						$nomeCampo = $qrCampos['NOM_CAMPOOBG'];
						$mask = "";
					}

					?>
					<div class="col-xs-12 text-left">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label <?= $required ?>">&nbsp;</label>
							<input type="<?= $type ?>" value="<?= $dado ?>" placeholder="<?= $qrCampos['NOM_CAMPOOBG'] ?>" class="form-control input-lg <?= $qrCampos['CLASSE_INPUT'] ?>" name="<?= $qrCampos['DES_CAMPOOBG'] ?>" id="<?= $qrCampos['DES_CAMPOOBG'] ?>" <?= $dataError ?> <?= $required ?> <?= $readonly ?>>
							<div class="help-block with-errors"></div>
						</div>
					</div>

		<?php

					break;
			}
		}



		?>
		<input type="hidden" name="KEY_DES_TOKEN" id="KEY_DES_TOKEN" value="">

		<input type="hidden" name="CAD_NOM_CLIENTE" id="CAD_NOM_CLIENTE" value="<?= $buscaconsumidor['nome'] ?>">
		<input type="hidden" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="<?= $buscaconsumidor['cpf'] ?>">
		<input type="hidden" name="CAD_COD_SEXOPES" id="CAD_COD_SEXOPES" value="<?= $buscaconsumidor['sexo'] ?>">
		<input type="hidden" name="CAD_NUM_CARTAO" id="CAD_NUM_CARTAO" value="<?= $buscaconsumidor['cartao'] ?>">
		<input type="hidden" name="CAD_DES_EMAILUS" id="CAD_DES_EMAILUS" value="<?= $buscaconsumidor['email'] ?>">
		<input type="hidden" name="CAD_DES_ENDEREC" id="CAD_DES_ENDEREC" value="<?= $buscaconsumidor['endereco'] ?>">
		<input type="hidden" name="CAD_NUM_ENDEREC" id="CAD_NUM_ENDEREC" value="<?= $buscaconsumidor['numero'] ?>">
		<input type="hidden" name="CAD_DES_BAIRROC" id="CAD_DES_BAIRROC" value="<?= $buscaconsumidor['bairro'] ?>">
		<input type="hidden" name="CAD_DES_COMPLEM" id="CAD_DES_COMPLEM" value="<?= $buscaconsumidor['complemento'] ?>">
		<input type="hidden" name="CAD_DES_CIDADEC" id="CAD_DES_CIDADEC" value="<?= $buscaconsumidor['cidade'] ?>">
		<input type="hidden" name="CAD_COD_ESTADOF" id="CAD_COD_ESTADOF" value="<?= $buscaconsumidor['estado'] ?>">
		<input type="hidden" name="CAD_NUM_CEPOZOF" id="CAD_NUM_CEPOZOF" value="<?= $buscaconsumidor['cep'] ?>">
		<input type="hidden" name="CAD_DAT_NASCIME" id="CAD_DAT_NASCIME" value="<?= $buscaconsumidor['datanascimento'] ?>">
		<input type="hidden" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="<?= $buscaconsumidor['telcelular'] ?>">
		<input type="hidden" name="CAD_COD_PROFISS" id="CAD_COD_PROFISS" value="<?= $buscaconsumidor['profissao'] ?>">
		<input type="hidden" name="CAD_COD_ATENDENTE" id="CAD_COD_ATENDENTE" value="<?= $buscaconsumidor['codatendente'] ?>">
		<input type="hidden" name="CAD_DES_SENHAUS" id="CAD_DES_SENHAUS" value="<?= fnEncode($buscaconsumidor['senha'][0]) ?>">
		<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= fnEncode($cod_cliente) ?>">

		<div class="push30"></div>

		<script type="text/javascript">
			$("#NUM_CEPOZOF").focusout(function() {
				if ($("#NUM_CEPOZOF").val().trim() != "") {
					$.ajax({
						type: "POST",
						url: "ajxApiCep.do?id=<?= fnEncode($cod_empresa) ?>",
						data: {
							CEP: $("#NUM_CEPOZOF").val(),
							URL: "<?= fnEncode(json_encode($urlWebservice)) ?>"
						},
						beforeSend: function() {
							$("#blocker").show();
						},
						success: function(data) {
							let end = JSON.parse(data);
							$("#DES_ENDEREC").val(end.logradouro);
							$("#DES_BAIRROC").val(end.bairro);
							$("#NOM_CIDADEC").val(end.cidade);
							$("#COD_ESTADOF").val(end.uf).trigger("chosen:updated");
							// console.log(data);
							$("#blocker").hide();
						},
						error: function(data) {
							//console.log(data);

						}
					});
				}
			});
		</script>

		<?php

		if ($cod_cliente == 0) {

			// fnEscreve($log_cadtoken);

			if ($log_cadtoken == 'S') {

		?>

				<div class="col-md-12 col-xs-12" id="btnCadastro">
					<a style="width: 100%; border-radius: 0!important;" class="btn btn-success btn-lg f18" onclick='ajxCliente("TKNCAD","")'>Enviar Token</a>
				</div>

			<?php

			} else {

			?>

				<div class="col-md-12 col-xs-12" id="btnCadastro">
					<a style="width: 100%; border-radius: 0!important;" class="btn btn-success btn-lg f18" onclick='ajxCliente("VALTKNCAD",99)'>Cadastrar</a>
				</div>

			<?php

			}
		} else {

			$sqlEmpresa = "SELECT LOG_ATIVCAD FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
			$arrayEmpresa = mysqli_query($connAdm->connAdm(), $sqlEmpresa);
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayEmpresa);

			$log_ativcadLGPD = $qrBuscaEmpresa['LOG_ATIVCAD'];

			$sqlCod = "SELECT COD_CLIENTE, LOG_CADOK, LOG_TERMO FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CGCECPF = '" . $buscaconsumidor['cpf'] . "'";
			$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);

			$qrCod = mysqli_fetch_assoc($arrayCod);

			$log_cadokLGPD = $qrCod['LOG_CADOK'];
			$log_termoLGPD = $qrCod['LOG_TERMO'];

			//novas verificações LGPD
			if ($log_ativcadLGPD == "S" && $log_cadokLGPD == "N" && $log_termoLGPD == "N") {
				$bloqueiaDesbloqueio = "S";
			} else {
				$bloqueiaDesbloqueio = "N";
			}

			// fnEscreve($log_ativcadLGPD);
			// fnEscreve($log_cadokLGPD);
			// fnEscreve($log_termoLGPD);
			// fnEscreve($bloqueiaDesbloqueio);

			//busca saldo do cliente
			$sqlSaldo = "SELECT (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS SALDO_ACUMULADO, 
								 (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS CREDITO_DISPONIVEL, 
								 (
								SELECT MAX(dat_libera)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS DAT_LIBERA, (
								SELECT MIN(DATE(dat_expira))
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE() AND VAL_SALDO > 0) OR(log_expira='N'))) AS DAT_MINIMA
								FROM CREDITOSDEBITOS A
								WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa
								GROUP BY COD_CLIENTE";

			// fnEscreve($sqlSaldo);

			$row = mysqli_query(connTemp($cod_empresa, ''), $sqlSaldo);
			$qrBuscaSaldo = mysqli_fetch_assoc($row);
			$tem_saldo = mysqli_num_rows($row);
			// fnEscreveArray($qrBuscaSaldo);
			$saldo_acumulado = fnValor($qrBuscaSaldo['SALDO_ACUMULADO'], $casasDec);
			$credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'], $casasDec);
			$dat_libera = fnDataShort($qrBuscaSaldo['DAT_LIBERA']);
			$dat_minima = fnDataShort($qrBuscaSaldo['DAT_MINIMA']);

			$sqlRegra = "SELECT min(CAMPANHARESGATE.NUM_MINRESG) MINIMO_RESGATE,

									max(CAMPANHARESGATE.PCT_MAXRESG) MAXIMO_RESGATE

							 FROM CAMPANHARESGATE
							WHERE COD_EMPRESA = $cod_empresa";

			// fnEscreve($sqlRegra);

			$row2 = mysqli_query(connTemp($cod_empresa, ''), $sqlRegra);
			$qrBuscaRegra = mysqli_fetch_assoc($row2);

			$minimo_resgate = $qrBuscaRegra['MINIMO_RESGATE'];
			$maximo_resgate = $qrBuscaRegra['MAXIMO_RESGATE'];

			?>

			<div id="blocoSaldo">

				<?php

				if ($bloqueiaDesbloqueio == "S") {

					$sql = "CALL total_wallet('$cod_cliente', '$cod_empresa')";

					//fnEscreve($sql);

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


					if (isset($arrayQuery)) {

						$credito_bloqueadoLGPD = $qrBuscaTotais['CREDITO_BLOQUEADO_LGPD'];
					} else {

						$credito_bloqueadoLGPD = 0;
					}

				?>

					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-danger" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Termo LGPD desatualizado. <br />
							<?php if ($credito_bloqueadoLGPD > 0) { ?>
								Existem <span class="text-danger"><?php echo fnValor($credito_bloqueadoLGPD, $casasDec); ?></span> <?php echo $txtTipo; ?> bloqueados por desatualização.
							<?php } ?>
						</div>

					</div>

					<?php
				} else {

					if ($tem_saldo > 0) {

					?>

						<div class="col-md-12 col-xs-12 text-left">

							<div class="alert alert-warning" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Informe o crédito ao seu cliente.
							</div>

						</div>

				<?php

					}
				}

				?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						<span class="pull-left">
							Saldo Disponível:
						</span>
						<span class="pull-right" style="margin-right: 15px;">
							<small class="f12"><?= $pref ?></small><b style="font-size:19px;" class="text-success">&nbsp;<?= $credito_disponivel ?></b>
						</span>

					</div>

				</div>


				<?php
				if ($tem_saldo > 0) {
				?>

					<script type="text/javascript">
						ajxCliente("TIP", "");
					</script>

					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

							<span class="pull-left">
								Prazo para utilizar os créditos:
							</span>
							<span class="pull-right" style="margin-right: 15px; margin-top: 0px;">
								<b style="font-size:14px;"><?= $dat_minima ?></b><!--  a <b style="font-size:14px;"><?= $dat_minima ?></b> -->
							</span>

						</div>

					</div>



					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-dark" role="alert" style="margin-bottom: 0px; height: 43px;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

							<span class="pull-left">
								Regras:
							</span>
							<span class="pull-right" style="margin-right: 15px;">
								até <b style="font-size:16px;"><?= $maximo_resgate ?></b>% da compra
							</span>

						</div>

					</div>



				<?php
				} else {
				?>
					<script type="text/javascript">
						ajxCliente("VEN", "");
					</script>
			<?php
				}
			}
			?>
			</div>

			<div class="push20"></div>


			<div id="relatorioToken"></div>

			<div id="relatorioValidaToken"></div>

			<div id="relatorioTipoVenda"></div>

			<div id="relatorioResgate"></div>

			<div id="relatorioValidaResgate"></div>

			<div id="relatorioVenda"></div>

			<div id="relatorioProdutos"></div>

			<div id="relatorioPos"></div>

		<?php


		// echo '<pre>';
		//          print_r($buscaconsumidor);
		//          echo '</pre>';

		break;

	case 'TIP':

		?>



			<div id="btnResgate" class="col-md-6 col-xs-12">
				<a style="width: 100%; border-radius: 0!important;"
					href="javascript:void(0)" class="btn btn-default f14" onclick='ajxCliente("SRES","SRES")'>
					Compra sem Resgate
				</a>
				<div class="push20"></div>
			</div>

			<div id="btnResgate" class="col-md-6 col-xs-12">

				<?php

				$sqlResg = "SELECT LOG_TOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
				$arrayResg = mysqli_query($connAdm->connAdm(), $sqlResg);
				$qrResg = mysqli_fetch_assoc($arrayResg);

				$log_tknresg = $qrResg['LOG_TOKEN'];

				if ($log_tknresg == 'S') {
				?>
					<a style="width: 100%; border-radius: 0!important;"
						href="javascript:void(0)" class="btn btn-default f14" onclick='ajxCliente("RES","")'>
						Compra com Resgate
					</a>
				<?php
				} else {
				?>
					<a style="width: 100%; border-radius: 0!important;"
						href="javascript:void(0)" class="btn btn-default f14" onclick='ajxCliente("VEN","")'>
						Compra com Resgate
					</a>
				<?php
				}

				?>
				<div class="push20"></div>
			</div>


		<?php
		break;

	case 'RES':

		?>



			<div class="col-md-8 col-xs-12 text-left p-r-0">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Valor do Resgate</label> -->
					<input type="tel" placeholder="Valor do Resgate (R$)" name="VAL_RESGATE" id="VAL_RESGATE" value="" maxlength="50" class="form-control input-lg money" style="border-radius:0 3px 3px 0;">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 p-l-0">
				<!-- <label>&nbsp;</label> -->
				<a style="width: 100%; border-radius: 0!important;" class="btn btn-success btn-lg f18" onclick='ajxCliente("TKNRSG","")'>Enviar Token</a>
			</div>



			<div class="push20"></div>

		<?php
		break;

	case 'TKNRSG':

		include_once '../totem/funWS/GeraToken.php';

		$qtd_chartkn = fnLimpaCampo($_POST['QTD_CHARTKN']);
		$tip_token = fnLimpaCampo($_POST['TIP_TOKEN']);
		$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo($_POST['NOM_USUARIO']);

		if ($num_celular == "") {
			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CELULAR']));
		}

		if ($num_cgcecpf == "") {
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CGCECPF']));
		}

		$dadosenvio = array(
			'tipoGeracao' => '2',
			'nome' => "$nom_cliente",
			'cpf' => "$num_cgcecpf",
			'celular' => "$num_celular",
			'email' => ''
		);

		$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);


		// echo '<pre>';
		//    print_r($dadosenvio);
		//    print_r($retornoEnvio);
		//    echo '</pre>';
		//    exit();

		$cod_envio = $retornoEnvio['body']['envelope']['body']['geratokenresponse']['retornatoken']['coderro'];

		if ($cod_envio == 93) {
			$txtEnvio = "Um token válido já foi enviado anteriormente.";
			$tipAlert = "warning";
		} else {
			$txtEnvio = "Token enviado!";
			$tipAlert = "success";
		}

		if ($tip_token == 2) {
			$type = "number";
		} else {
			$type = "text";
		}

		?>

			<div class="col-md-12 col-xs-12 text-left">

				<div class="alert alert-<?= $tipAlert ?>" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?= $txtEnvio ?> Peça para o cliente verificar e informar o SMS recebido, e digite o token no campo abaixo:
				</div>

			</div>



			<div class="col-md-8 col-xs-12 text-left p-r-0">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Token</label> -->
					<input type="<?= $type ?>" placeholder="Token" name="TKN_RESGATE" id="TKN_RESGATE" value="" maxlength="<?= $qtd_chartkn ?>" class="form-control input-lg" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-4 col-xs-12 p-l-0">
				<!-- <label>&nbsp;</label> -->
				<a style="width: 100%; border-radius: 0!important;" class="btn btn-info btn-lg f18" onclick='ajxCliente("VALTKNRES","")'>Validar Token</a>
			</div>

			<div class="push20"></div>




			<?php

			break;

		case 'VALTKNRES':

			include_once '../totem/funWS/GeraToken.php';

			$des_token = fnLimpaCampo(fnLimpaDoc($_POST['TKN_RESGATE']));
			$nom_cliente = fnLimpaCampo($_POST['NOM_USUARIO']);
			$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));

			if ($num_celular == "") {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CELULAR']));
			}

			if ($num_cgcecpf == "") {
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['CAD_NUM_CGCECPF']));
			}

			$dadosenvio = array(
				'tipoGeracao' => '2',
				'token' => "$des_token",
				'celular' => "$num_celular",
				'cpf' => "$num_cgcecpf"
			);

			$retornoEnvio = ValidaToken($dadosenvio, $arrayCampos);

			// echo '<pre>';
			//    print_r($dadosenvio);
			//    print_r($retornoEnvio);
			//    echo '</pre>';
			//    exit();

			$cod_envio = $retornoEnvio['body']['envelope']['body']['validatokenresponse']['retornatoken']['coderro'];

			if ($cod_envio == 39) {

			?>

				<script>
					$("#HID_TKNRESG").val($("#TKN_RESGATE").val());
					$("#relatorioValidaResgate").fadeOut('fast', function() {

						$("#relatorioValidaResgate").html('<div class="col-md-12 col-xs-12 text-left">' +

							'<div class="alert alert-success" role="alert">' +
							'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
							'Token validado! Agora, entre com o valor da venda:' +
							'</div>' +

							'</div>');

						$("#relatorioValidaResgate").fadeIn('fast');

						$("#VAL_RESGATE,#TKN_RESGATE").attr("readonly", true);

						ajxCliente("VEN", "");

						// $("#CAD_NUM_CGCECPF,NOM_USUARIO").attr("readonly",true);

					});
				</script>

			<?php

			} else {

			?>



				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Token inválido.
					</div>

				</div>



			<?php

			}

			break;

		case 'VEN':

			if (isset($_REQUEST['KEY_NUM_CARTAO'])) {
				$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
			} else {
				$k_num_cartao = "";
			}

			if (isset($_REQUEST['KEY_NUM_CELULAR'])) {
				$k_num_celular = fnLimpaCampo($_REQUEST['KEY_NUM_CELULAR']);
			} else {
				$k_num_celular = "";
			}

			if (isset($_REQUEST['KEY_COD_EXTERNO'])) {
				$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
			} else {
				$k_cod_externo = "";
			}

			if (isset($_REQUEST['KEY_NUM_CGCECPF'])) {
				$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
			} else {
				$k_num_cgcecpf = "";
			}

			if (isset($_REQUEST['KEY_DAT_NASCIME'])) {
				$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
			} else {
				$k_dat_nascime = "";
			}

			if (isset($_REQUEST['KEY_DES_EMAILUS'])) {
				$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);
			} else {
				$k_des_emailus = "";
			}


			// fnEscreve('teste');

			if (trim($k_num_celular) != "") {
				// echo "if cel";
				$cpf = fnLimpaDoc($k_num_celular);
				$cartao = fnLimpaDoc($k_num_celular);
			}

			if (trim($k_cod_externo) != "") {
				// echo "if ext";
				$cpf = $k_cod_externo;
				$cartao = $k_cod_externo;
			}

			if (trim($k_dat_nascime) != "") {
				// echo "if aniv";
				$cpf = fnLimpaDoc($k_dat_nascime);
				$cartao = fnLimpaDoc($k_dat_nascime);
			}

			if (trim($k_des_emailus) != "") {
				// echo "if email";
				$email = $k_des_emailus;
				$cartao = $cpf;
			}

			$cod_orcamento = $arrayCampos[2] . $cod_empresa . microtime();

			if (!isset($num_celular) || $num_celular == "") {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			}

			if (trim($k_num_cgcecpf) != "" && trim($k_num_cgcecpf) != "00000000000") {
				// echo "if cpf";
				$cpf = $k_num_cgcecpf;
				$cartao = $k_num_cgcecpf;
			}

			if (trim($k_num_cartao) != "") {
				// echo "if card";
				if ($cpf == "") {
					$cpf = $k_num_cartao;
				}

				$cartao = $k_num_cartao;
			}

			$sqlCod = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CARTAO = '$cpf'";
			$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);

			$qrCod = mysqli_fetch_assoc($arrayCod);

			//busca saldo do cliente
			$sqlSaldo = "SELECT (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS SALDO_ACUMULADO, 
								 (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS CREDITO_DISPONIVEL, 
								 (
								SELECT MAX(dat_libera)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS DAT_LIBERA, (
								SELECT MIN(DATE(dat_expira))
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND DATE(dat_expira) >= CURDATE()) OR(log_expira='N'))) AS DAT_MINIMA
								FROM CREDITOSDEBITOS A
								WHERE COD_CLIENTE = $qrCod[COD_CLIENTE] AND COD_EMPRESA = $cod_empresa
								GROUP BY COD_CLIENTE";

			// fnEscreve($sqlSaldo);

			$row = mysqli_query(connTemp($cod_empresa, ''), $sqlSaldo);
			$qrBuscaSaldo = mysqli_fetch_assoc($row);
			$tem_saldo = mysqli_num_rows($row);


			$sqlResg = "SELECT LOG_TOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
			$arrayResg = mysqli_query($connAdm->connAdm(), $sqlResg);
			$qrResg = mysqli_fetch_assoc($arrayResg);
			// fnEscreve($sqlResg);

			$log_tknresg = $qrResg['LOG_TOKEN'];

			// echo $tipo;

			// fnEscreve($log_tknresg);
			// fnEscreve($tem_saldo);
			// fnEscreve($tipo);

			if ($log_tknresg == 'N' && $tem_saldo > 0 && $tipo != "SRES") {
			?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-warning" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Faça uma venda adicional ao oferecer o resgate!
					</div>

				</div>

				<div class="col-md-12 text-left">
					<div class="form-group">
						<!-- <label for="inputName" class="control-label required">Valor da Venda</label> -->
						<input type="tel" placeholder="Valor do Resgate (R$)" name="VAL_RESGATE" id="VAL_RESGATE" value="" maxlength="50" class="form-control input-lg money" style="border-radius:0 3px 3px 0;">
						<div class="help-block with-errors"></div>
					</div>
				</div>
				<div class="push10"></div>

			<?php
			}

			$sql = "SELECT IFNULL(MAX(COD_ORCAMENTO), 0) + 1 as COD_ORCAMENTO FROM CONTADOR ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$qrBuscaOrcamento = mysqli_fetch_assoc($arrayQuery);
			if (isset($qrBuscaOrcamento)) {

				$cod_orcamento = $qrBuscaOrcamento['COD_ORCAMENTO'];
				//fnEscreve($cod_orcamento);

				//atualiza contador do orçamento
				$sql = "UPDATE CONTADOR SET COD_ORCAMENTO = '" . $cod_orcamento . "' WHERE COD_CONTADOR = 1 ";
				mysqli_query(connTemp($cod_empresa, ''), $sql);
			}

			?>
			<style type="text/css">
				.chosen-single {
					height: 66px !important;
					font-size: 19px !important;
					padding: 18px 27px !important;
					line-height: 1.3333333 !important;
				}
			</style>

			<div class="col-md-6 col-xs-12 text-left">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Produto</label> -->
					<select data-placeholder="Selecione um produto" name="COD_PRODUTO" id="COD_PRODUTO" class="chosen-select-deselect">
						<option value=""></option>
						<?php

						$sqlOnline = "SELECT PC.COD_PRODUTO,
												 PC.DES_PRODUTO 
										  FROM PRODUTOCLIENTE PC 
										  WHERE LOG_MAISCASH = 'S' 
										  AND COD_EMPRESA = $cod_empresa";

						$arrayOnline = mysqli_query(connTemp($cod_empresa, ''), $sqlOnline);

						if ($qrOnline = mysqli_fetch_assoc($arrayOnline)) {

						?>

							<option value="<?= $qrOnline['COD_PRODUTO'] ?>" selected="selected"><?= $qrOnline['DES_PRODUTO'] ?></option>

						<?php
						}

						$sql = "SELECT PC.COD_PRODUTO,
										   CATEGORIA.DES_CATEGOR AS DES_PRODUTO
									FROM CAMPANHAPRODUTO
									INNER JOIN CATEGORIA ON CATEGORIA.COD_CATEGOR=CAMPANHAPRODUTO.COD_CATEGOR
									INNER JOIN PRODUTOCLIENTE PC ON PC.COD_CATEGOR = CAMPANHAPRODUTO.COD_CATEGOR
									WHERE CAMPANHAPRODUTO.COD_EXCLUSAO = 0 
									AND CAMPANHAPRODUTO.COD_CAMPANHA = $cod_campanha
									GROUP BY CATEGORIA.COD_CATEGOR
									ORDER BY DES_PRODUTO";

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

						$count = 0;

						while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
						?>

							<option value="<?= $qrBuscaCampanhaExtra['COD_PRODUTO'] ?>"><?= $qrBuscaCampanhaExtra['DES_PRODUTO'] ?></option>

						<?php

						}

						?>
					</select>
					<?php // fnEscreve($sql); 
					?>
					<div class="help-block with-errors"></div>
					<script type="text/javascript">
						$("#COD_PRODUTO").chosen();
					</script>
				</div>
				<div class="push20"></div>
			</div>

			<div class="col-md-4 col-xs-12 text-left">
				<div class="form-group">
					<!-- <label for="inputName" class="control-label required">Valor da Venda</label> -->
					<input type="tel" placeholder="Vl. Produto (R$)" name="VAL_UNITARIO" id="VAL_UNITARIO" value="" maxlength="50" class="form-control input-lg money text-center" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="col-md-2 col-xs-12">
				<!-- <label>&nbsp;</label> -->
				<!-- <a style="width: 100%; border-radius: 0!important; padding: 10px 27px;" class="btn btn-success btn-lg f18" onclick='ajxCliente("FIM","")'>Lançar Crédito<br/> <span style="font-size: 12px">e enviar mensagem</span></a> -->
				<a style="width: 100%;" class="btn btn-primary btn-lg f18 input-lg" onclick='ajxCliente("PROD","")'><span class="far fa-plus"></span></a>
			</div>

			<input type="hidden" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?= fnEncode($cod_orcamento) ?>">

			<div class="push20"></div>

		<?php
			break;


		case 'EXCPROD':

			$cod_produto = fnLimpaCampoZero(fnDecode($_GET['tip']));
			$cod_orcamento = fnLimpaCampoZero(fnDecode($_POST['COD_ORCAMENTO']));
			$val_resgate = fnLimpaCampoZero(fnValorSql($_POST['VAL_RESGATE']));

			$sql = "CALL SP_ALTERA_AUXVENDA (
			 '" . $cod_produto . "', 
			 '" . $cod_orcamento . "', 
			 '0',
			 '0',
			 '0', 
			 '" . $cod_empresa . "',
			 'EXC'    
			) ";

			// echo $sql;				

			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

		?>

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><i class='fa fa-trash' aria-hidden='true'></i></th>
						<th>Nome do Produto </th>
						<th class="text-right">Valor Total</th>
					</tr>
				</thead>
				<tbody>

					<?php

					$sql = "SELECT B.DES_PRODUTO,A.* from AUXVENDA A,PRODUTOCLIENTE B
						where 
						A.COD_PRODUTO=B.COD_PRODUTO AND
						A.COD_ORCAMENTO = $cod_orcamento order by A.COD_VENDA";

					// echo($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count = 0;
					$valorTotal = 0;

					while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
						$count++;

						$valorTotalProd = $qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO'];

						$valorTotal = $valorTotal + $valorTotalProd;

						// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
						// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
						// fnEscreve(fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDec));


						echo "
						<tr>
						  <td class='text-center'><a href='javascript:void(0);' onclick='ajxCliente(\"EXCPROD\",\"" . fnEncode($qrBuscaProdutos['COD_VENDA']) . "\")'><i class='fal fa-times text-danger' aria-hidden='true'></i></a></td>
						  <td style='font-size: 20px;'>" . $qrBuscaProdutos['DES_PRODUTO'] . "</td>
						  <td class='text-right' style='font-size: 20px;'>" . fnValor($valorTotalProd, $casasDec) . "</td>
						</tr>
						
						";
					}
					$total_comResg = $valorTotal - $val_resgate;
					?>

				</tbody>
			</table>

			<!-- <div class="col-md-4 col-md-offset-8 col-xs-12 pull-right">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total de Produtos</label>
					<input type="text" class="form-control input-lg text-center leituraOff" readonly="readonly" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="<?php echo fnValor($valorTotal, $casasDec); ?>">
				</div>
			</div>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total da venda com resgate</label>
					<input type="tel" placeholder="" style="font-weight: bold;" name="TOT_RESGATE" id="TOT_RESGATE" value="" maxlength="50" class="form-control text-center input-lg money leituraOff" readonly="readonly" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div> -->

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Cupom Fiscal</label>
					<input type="text" class="form-control input-lg" name="DES_CUPOM" id="DES_CUPOM" maxlength="15">
				</div>
			</div>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total de Produtos</label>
					<input type="text" class="form-control input-lg text-center leituraOff" readonly="readonly" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="<?php echo fnValor($valorTotal, $casasDec); ?>">
				</div>
			</div>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total da venda com resgate</label>
					<input type="tel" placeholder="" style="font-weight: bold;" name="TOT_RESGATE" id="TOT_RESGATE" value="<?= fnValor($total_comResg, 2) ?>" maxlength="50" class="form-control text-center input-lg money leituraOff" readonly="readonly" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="col-md-12 col-xs-12 text-left p-r-0 p-l-0">
				<div class="form-group">
					<a style="width: 100%; border-radius: 0!important; padding: 10px 27px;" class="btn btn-success btn-lg f18" onclick='ajxCliente("FIM","")'>Lançar Crédito<br /> <span style="font-size: 12px">e enviar mensagem</span></a>
				</div>
			</div>



		<?php

			break;

		case 'PROD':

			$val_unitario = $_POST['VAL_UNITARIO'];
			$cod_produto = fnLimpaCampoZero($_POST['COD_PRODUTO']);
			$cod_orcamento = fnLimpaCampoZero(fnDecode($_POST['COD_ORCAMENTO']));
			$cod_venda = 0;
			$val_resgate = fnLimpaCampoZero(fnValorSql($_POST['VAL_RESGATE']));


			$sql = "CALL SP_ALTERA_AUXVENDA (
					 '" . $cod_venda . "', 
					 '" . $cod_orcamento . "', 
					 '" . $cod_produto . "',
					 '" . fnValorSql(1) . "', 
					 '" . fnValorSql($val_unitario) . "',
					 '" . $cod_empresa . "',
					 'CAD'    
					) ";

			// echo $val_unitario."<br>";				
			// echo $sql;				
			// echo($sql);
			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

		?>

			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><i class='fa fa-trash' aria-hidden='true'></i></th>
						<th>Nome do Produto </th>
						<th class="text-right">Valor Total</th>
					</tr>
				</thead>
				<tbody>

					<?php

					$sql = "SELECT B.DES_PRODUTO,A.* from AUXVENDA A,PRODUTOCLIENTE B
						where 
						A.COD_PRODUTO=B.COD_PRODUTO AND
						A.COD_ORCAMENTO = $cod_orcamento order by A.COD_VENDA";

					// echo($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count = 0;
					$valorTotal = 0;

					while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
						$count++;

						$valorTotalProd = $qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO'];

						$valorTotal = $valorTotal + $valorTotalProd;

						// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
						// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
						// fnEscreve(fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDec));


						echo "
						<tr>
						  <td class='text-center'><a href='javascript:void(0);' onclick='ajxCliente(\"EXCPROD\",\"" . fnEncode($qrBuscaProdutos['COD_VENDA']) . "\")'><i class='fal fa-times text-danger' aria-hidden='true'></i></a></td>
						  <td style='font-size: 20px;'>" . $qrBuscaProdutos['DES_PRODUTO'] . "</td>
						  <td class='text-right' style='font-size: 20px;'>" . fnValor($valorTotalProd, $casasDec) . "</td>
						</tr>
						
						";
					}
					$total_comResg = $valorTotal - $val_resgate;
					?>

				</tbody>
			</table>

			<script type="text/javascript">
				// $("#COD_PRODUTO").val("").trigger("chosen:updated");
				$("#VAL_UNITARIO").val("");
			</script>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Cupom Fiscal</label>
					<input type="text" class="form-control input-lg" name="DES_CUPOM" id="DES_CUPOM" maxlength="15">
				</div>
			</div>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total de Produtos</label>
					<input type="text" class="form-control input-lg text-center leituraOff" readonly="readonly" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="<?php echo fnValor($valorTotal, $casasDec); ?>">
				</div>
			</div>

			<div class="col-md-4 col-xs-12">
				<div class="form-group">
					<label for="VAL_TOTPRODU" class="control-label">Total da venda com resgate</label>
					<input type="tel" placeholder="" style="font-weight: bold;" name="TOT_RESGATE" id="TOT_RESGATE" value="<?= fnValor($total_comResg, 2) ?>" maxlength="50" class="form-control text-center input-lg money leituraOff" readonly="readonly" data-error="Campo obrigatório">
					<div class="help-block with-errors"></div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="col-md-12 col-xs-12 text-left p-r-0 p-l-0">
				<div class="form-group">
					<a style="width: 100%; border-radius: 0!important; padding: 10px 27px;" class="btn btn-success btn-lg f18" onclick='ajxCliente("FIM","")' id="btnVenda">Lançar Crédito<br /> <span style="font-size: 12px">e enviar mensagem</span></a>
				</div>
			</div>

			<?php

			break;

		default:

			// <token_resgate>?</token_resgate>

			include_once '../totem/funWS/inserirvenda.php';
			include_once '../totem/funWS/Validadescontos.php';

			if (isset($_POST['COD_PRODUTO'])) {
				$cod_produto = fnLimpaCampoZero($_POST['COD_PRODUTO']);
			} else {
				$cod_produto = "";
			}

			if (isset($_POST['VAL_TOTPRODU'])) {
				$val_venda = fnValorSql($_POST['VAL_TOTPRODU']);
			} else {
				$val_venda = "";
			}

			if (isset($_POST['VAL_RESGATE'])) {
				$val_resgate = fnValorSql($_POST['VAL_RESGATE']);
			} else {
				$val_resgate = "";
			}

			if (isset($_POST['HID_TKNRESG'])) {
				$des_tokenres = fnLimpaCampo($_POST['HID_TKNRESG']);
			} else {
				$des_tokenres = "";
			}

			if (isset($_POST['DES_CUPOM'])) {
				$des_cupom = fnLimpaCampo($_POST['DES_CUPOM']);
			} else {
				$des_cupom = "";
			}

			if (isset($_REQUEST['KEY_NUM_CARTAO'])) {
				$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
			} else {
				$k_num_cartao = "";
			}

			if (isset($_REQUEST['KEY_NUM_CELULAR'])) {
				$k_num_celular = fnLimpaCampo($_REQUEST['KEY_NUM_CELULAR']);
			} else {
				$k_num_celular = "";
			}

			if (isset($_REQUEST['KEY_COD_EXTERNO'])) {
				$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
			} else {
				$k_cod_externo = "";
			}

			if (isset($_REQUEST['KEY_NUM_CGCECPF'])) {
				$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
			} else {
				$k_num_cgcecpf = "";
			}

			if (isset($_REQUEST['KEY_DAT_NASCIME'])) {
				$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
			} else {
				$k_dat_nascime = "";
			}

			if (isset($_REQUEST['KEY_DES_EMAILUS'])) {
				$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);
			} else {
				$k_des_emailus = "";
			}

			if (isset($_REQUEST['COD_CLIENTE'])) {
				$cod_cliente = fnLimpaCampo($_REQUEST['COD_CLIENTE']);
			} else {
				$cod_cliente = "";
			}

			if (isset($_POST['COD_ORCAMENTO'])) {
				$cod_orcamento = fnLimpaCampoZero(fnDecode($_POST['COD_ORCAMENTO']));
			} else {
				$cod_orcamento = "";
			}


			// fnEscreve($k_dat_nascime);

			if (trim($k_num_celular) != "") {
				// echo "if cel";
				$cpf = fnLimpaDoc($k_num_celular);
				$cartao = fnLimpaDoc($k_num_celular);
			}

			if (trim($k_cod_externo) != "") {
				// echo "if ext";
				$cpf = $k_cod_externo;
				$cartao = $k_cod_externo;
			}

			if (trim($k_dat_nascime) != "") {
				// echo "if aniv";
				$cpf = fnLimpaDoc($k_dat_nascime);
				$cartao = fnLimpaDoc($k_dat_nascime);
			}

			if (trim($k_des_emailus) != "") {
				// echo "if email";
				$email = $k_des_emailus;
				$cartao = $cpf;
			}

			$id_vendapdv = $arrayCampos[2] . $cod_empresa . date("dmYHis");

			if (!isset($num_celular) || $num_celular == "") {
				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
			}

			if (trim($k_num_cgcecpf) != "" && trim($k_num_cgcecpf) != "00000000000") {
				// echo "if cpf";
				$cpf = $k_num_cgcecpf;
				$cartao = $k_num_cgcecpf;
			}

			if (trim($k_num_cartao) != "") {
				// echo "if card";
				if ($cpf == "") {
					$cpf = $k_num_cartao;
				}

				$cartao = $k_num_cartao;
			}
			// fnEscreve($des_token);

			// $vendaitem.="<vendaitem>
			//                          <id_item>0</id_item>
			//                          <produto>Venda Online</produto>
			//                          <codigoproduto>".$cod_produto."</codigoproduto>
			//                          <quantidade>1</quantidade>
			//                          <valorbruto>".str_replace(".","",fnValor($val_venda,2))."</valorbruto>
			//                          <descontovalor>0,00</descontovalor>
			//                          <valorliquido>".str_replace(".","",fnValor($val_venda,2))."</valorliquido>
			//                      </vendaitem>";

			$sqlitemvenda = "select B.COD_EXTERNO,B.DES_PRODUTO,A.* from AUXVENDA A
                    inner join  PRODUTOCLIENTE B on 	A.COD_PRODUTO=B.COD_PRODUTO	
                    where A.COD_ORCAMENTO = '$cod_orcamento' and A.COD_ORCAMENTO <> ''  order by A.COD_VENDA";

			// fnEscreve($sqlitemvenda);

			$queryexec = mysqli_query(connTemp($cod_empresa, ''), $sqlitemvenda);
			$vendaitem = "";
			while ($row = mysqli_fetch_assoc($queryexec)) {
				// matriz de entrada
				$what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç');

				// matriz de saída
				$by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C');

				// devolver a string
				$nom_prod = str_replace($what, $by, $row['DES_PRODUTO']);
				$vendaitem .= "<vendaitem>
	                            <id_item>" . $row['COD_VENDA'] . "</id_item>
	                            <produto>" . $nom_prod . "</produto>
	                            <codigoproduto>" . $row['COD_EXTERNO'] . "</codigoproduto>
	                            <quantidade>" . str_replace(".", ",", $row['QTD_PRODUTO']) . "</quantidade>
	                            <valorbruto>" . str_replace(".", "", fnValor($row['VAL_UNITARIO'], 2)) . "</valorbruto>
	                            <descontovalor>0,00</descontovalor>
	                            <valorliquido>" . str_replace(".", "", fnValor($row['VAL_UNITARIO'], 2)) . "</valorliquido>
	                        </vendaitem>";
			}

			$arrayVenda = array(
				'id_vendapdv' => $id_vendapdv,
				'datahora' => date("Y-m-d H:i:s"),
				'cartao' => $cpf,
				'valortotalbruto' => str_replace(".", "", fnValor($val_venda, 2)),
				'descontototalvalor' => str_replace(".", "", fnValor(@$VAL_DESCONTO, 2)),
				'valortotalliquido' => str_replace(".", "", fnValor($val_venda, 2)),
				'valor_resgate' => str_replace(".", "", fnValor($val_resgate, 2)),
				// 'cupomfiscal'=>date("dmYHis"),
				'cupomfiscal' => "$des_cupom",
				'formapagamento' => 0,
				'pontostotal' => 0,
				'codatendente' => $cod_usucada,
				'codvendedor' => $cod_usucada,
				'token_resgate' => $des_tokenres
			);

			$arrayValida = array(
				'cpfcnpj' => $cpf,
				'valortotalliquido' => str_replace(".", "", fnValor($val_venda, 2)),
				'valor_resgate' => str_replace(".", "", fnValor($val_resgate, 2))
			);

			if (isset($val_resgate) && $val_resgate > 0) {

				$validaVenda = fnValidadescontoMc($arrayValida, $arrayCampos);

				$result = $validaVenda['body']['envelope']['body']['validadescontosresponse']['validadescontos'];
				$codErro = $result['coderro'];

				if ($codErro != 52) {
					$maxi_resgate = $result['maximoresgate'];
					$min_resgate = $result['minimoresgate'];
			?>

					<div class="push20"></div>
					<div class="col-md-12 col-xs-12">

						<div class="alert alert-warning" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Você pode resgatar de R$ <?= $min_resgate ?> até R$ <?= $maxi_resgate ?>
						</div>

					</div>

				<?php

					$msgVenda[0] = false;
				} else {
					$retornoVenda = inserirvenda($arrayVenda, $arrayCampos, $vendaitem);
					$msgVenda = json_decode(json_encode($retornoVenda), TRUE);
				}
			} else {
				$retornoVenda = inserirvenda($arrayVenda, $arrayCampos, $vendaitem);
				$msgVenda = json_decode(json_encode($retornoVenda), TRUE);
			}

			// echo "<pre>";
			// print_r($vendaitem);
			// echo "</pre>";

			// echo "<pre>";
			// print_r($arrayVenda);
			// echo "</pre>";
			// exit();





			// echo "<pre>";
			// print_r($retornoVenda);
			// echo "</pre>";
			// exit();



			// fnEscreve($msgVenda[0]);

			if ($msgVenda[0] == "Processo de venda concluido!") {

				?>

				<script>
					$("#relatorioValidaResgate,#relatorioResgate,#relatorioVenda,#relatorioProdutos,#relatorioTipoVenda,#msgVenda,#blocoSaldo").fadeOut('fast');
					// $("#relatorioValidaToken").html('<div class="col-md-12 col-xs-12 text-left">'+

					// 															'<div class="alert alert-success" role="alert">'+
					// 															'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					// 															 	'Cliente Cadastrado!'+
					// 															'</div>'+

					// 														'</div>');
				</script>

				<?php

				$sqlCod = "SELECT COD_CLIENTE, NOM_CLIENTE, NUM_CELULAR, COD_UNIVEND FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CARTAO = '$cpf'";
				$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);

				$qrCod = mysqli_fetch_assoc($arrayCod);

				if ($qrCod['NUM_CELULAR'] != "") {

					// include "../_system/func_nexux/Envio_online_venda.php";
					include "../_system/func_nexux/envioFast.php";
					include '../_system/func_nexux/func_transacional.php';

					$array = array(
						'CONNADM' => $connAdm->connAdm(),
						'CONNTMP' => connTemp($cod_empresa, ''),
						'COD_EMPRESA' => $cod_empresa,
						'COD_UNIVEND' => fnLimpaCampoZero($qrCod["COD_UNIVEND"]),
						'NOMECLIENTE' => $qrCod["NOM_CLIENTE"],
						'COD_CLIENTE' => $qrCod["COD_CLIENTE"],
						'TELEFONE' => fnLimpaDoc($qrCod["NUM_CELULAR"]),
						'CASAS_DEC' => $casasDec,
						'TIP_OPERACAO' => 'vendaFast',
						'CRED_VENDA' => $msgVenda[1]
					);


					$teste2 = envio_fast_sms($array);

					// fnEscreve($teste2[coderro]);

					if ($teste2['coderro'] != "5") {
						if ($teste2['coderro'] == "2") {
							$msgErro2 = '<div class="col-md-12 col-xs-12 text-left"><div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><b>SMS NÃO ENVIADO > SALDO SMS ZERADO > <a href="https://' . $_SERVER['HTTP_HOST'] . '/action.do?mod=' . fnEncode(1485) . '&id=' . fnEncode($cod_empresa) . '" target="_blank">RECARREGUE</a></b></div></div><div class="push10"></div>';
						} else {
							$msgErro2 = '<div class="col-md-12 col-xs-12 text-left"><div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>SMS não enviado: ' . $teste2['msgerro'] . '</div></div><div class="push10"></div>';
						}
					} else {
						$msgErro2 = "";
					}

					// echo "<pre>";
					// // print_r($array);
					// print_r($teste2);
					// echo "</pre>";

					// exit();

				}


				//busca saldo do cliente
				$sqlSaldo = "SELECT (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND dat_expira > NOW()) OR(log_expira='N'))) AS SALDO_ACUMULADO, 
								 (
								SELECT SUM(val_saldo)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1) AND ((log_expira='S' AND dat_expira > NOW()) OR(log_expira='N'))) AS CREDITO_DISPONIVEL, 
								 (
								SELECT MAX(dat_libera)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND dat_expira > NOW()) OR(log_expira='N'))) AS DAT_LIBERA, (
								SELECT MIN(dat_expira)
								FROM creditosdebitos
								WHERE cod_cliente = A.cod_cliente AND tip_credito = 'C' AND COD_STATUSCRED IN(1,2) AND ((log_expira='S' AND dat_expira > NOW()) OR(log_expira='N'))) AS DAT_MINIMA
								FROM CREDITOSDEBITOS A
								WHERE COD_CLIENTE = " . $qrCod['COD_CLIENTE'] . " AND COD_EMPRESA = $cod_empresa
								GROUP BY COD_CLIENTE";

				// fnEscreve($sqlSaldo);

				$row = mysqli_query(connTemp($cod_empresa, ''), $sqlSaldo);
				$qrBuscaSaldo = mysqli_fetch_assoc($row);
				$tem_saldo = mysqli_num_rows($row);
				// fnEscreveArray($qrBuscaSaldo);
				$saldo_acumulado = fnValor($qrBuscaSaldo['SALDO_ACUMULADO'], $casasDec);
				$credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'], $casasDec);
				$dat_libera = fnDataShort($qrBuscaSaldo['DAT_LIBERA']);
				$dat_minima = fnDataShort($qrBuscaSaldo['DAT_MINIMA']);

				$sqlRegra = "SELECT min(CAMPANHARESGATE.NUM_MINRESG) MINIMO_RESGATE,

									max(CAMPANHARESGATE.PCT_MAXRESG) MAXIMO_RESGATE

							 FROM CAMPANHARESGATE
							WHERE COD_EMPRESA = $cod_empresa";

				$row2 = mysqli_query(connTemp($cod_empresa, ''), $sqlRegra);
				$qrBuscaRegra = mysqli_fetch_assoc($row2);

				$minimo_resgate = $qrBuscaRegra['MINIMO_RESGATE'];
				$maximo_resgate = $qrBuscaRegra['MAXIMO_RESGATE'];

				?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert" style="margin-bottom: 5px; margin-top: 20px; height: 43px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						<span class="pull-left">
							Informe o saldo e a validade ao cliente.
						</span>

					</div>

				</div>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

						<span class="pull-left">
							Saldo acumulado:
						</span>
						<span class="pull-right" style="margin-right: 15px;">
							<small class="f12"><?= $pref ?></small><b style="font-size:19px;" class="text-success">&nbsp;<?= $saldo_acumulado ?></b>
						</span>

					</div>

				</div>

				<!-- <div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 	
						 	<span class="pull-left">
						 	     Saldo Disponível:
						 	</span>
						 	<span class="pull-right" style="margin-right: 15px;">
						 		<small class="f12"><?= $pref ?></small><b style="font-size:19px;" class="text-success">&nbsp;<?= $credito_disponivel ?></b>
						 	</span>
						
					</div>

				</div> -->


				<?php
				if ($tem_saldo > 0) {
				?>

					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

							<span class="pull-left">
								Prazo para utilizar os créditos:
							</span>
							<span class="pull-right" style="margin-right: 15px; margin-top: 0px;">
								<b style="font-size:14px;"><?= $dat_minima ?></b><!--  a <b style="font-size:14px;"><?= $dat_minima ?></b> -->
							</span>

						</div>

					</div>



					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-dark" role="alert" style="margin-bottom: 5px; height: 43px;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

							<span class="pull-left">
								Regra:
							</span>
							<span class="pull-right" style="margin-right: 15px;">
								até <b style="font-size:16px;"><?= $maximo_resgate ?></b>% da compra
							</span>

						</div>

					</div>



				<?php
				}

				?>

				<?php
				if ($msgErro2 != "") {
					echo $msgErro2;
				}
				?>

				<div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Venda realizada com <b>sucesso</b>
						<?php
						if ($msgErro2 == "") {
						?>
							e <b>SMS</b> enviado!
						<?php
						}
						?>
					</div>

				</div>

				<div class="push20"></div>

				<div class="col-md-12 col-xs-12">
					<a href="action.do?mod=<?php echo fnEncode(1680) ?>&id=<?php echo fnEncode($cod_empresa) ?>" style="width: 100%; border-radius: 0!important;" class="btn btn-primary btn-lg f18">Nova Venda</a>
				</div>



			<?php

			}
			// else {

			?>



			<!-- <div class="col-md-12 col-xs-12 text-left">

					<div class="alert alert-warning" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						
					</div>

				</div> -->



	<?php

			// }

			// fnEscreve("Fim do processo");

			break;
	}


	?>