<?php

//fnDebug(true);
$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		
		$tip_tarifa = fnLimpaCampoZero($_REQUEST['tip_tarifa']);
		$des_tarifa= fnLimpaCampo($_REQUEST['des_tarifa']);

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
				$sql = "INSERT INTO ADORAI_TARIFA(
					des_tarifa
					)
				VALUES(
					'$des_tarifa'
					)
				";

				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				break;

				case 'ALT':
				$sql = "UPDATE ADORAI_TARIFA SET 
				des_tarifa = '$des_tarifa'
				WHERE 
				tip_tarifa = $tip_tarifa";

				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
				break;
				case 'EXC':
				$sql = "DELETE FROM ADORAI_TARIFA WHERE tip_tarifa = $tip_tarifa";

				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
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
}

$conn = conntemp($cod_empresa,"");



?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}
	tr{
		border-bottom: none!important;
	}
	#blocker
	{
		display:none; 
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div
	{
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
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
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

				$abaManutencaoAdorai = 2019;
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
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="tip_tarifa" id="tip_tarifa" value="">
									</div>
								</div>								

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Abreviação da Tarifa</label>
										<input type="text" class="form-control input-sm" name="des_tarifa" id="des_tarifa" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>

						<div class="form-group text-right col-lg-12">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<div class="push5"></div>

					</form>

					<div class="push50"></div>



					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-center'></th>
										<th>Código</th>
										<th>Descrição</th>
										<th class='{ sorter: false } text-center'></th>
										<th class='{ sorter: false } text-center'></th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "SELECT * FROM ADORAI_TARIFA";
													//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										echo "
										<tr>
											<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></td>
											<td>".$qrBusca['tip_tarifa']."</td>
											<td>".$qrBusca['des_tarifa']."</td>
											<td></td>
											<td></td>
										</tr>
										<input type='hidden' id='ret_tip_tarifa_" . $count . "' value='" . $qrBusca['tip_tarifa'] . "'>
										<input type='hidden' id='ret_des_tarifa_" . $count . "' value='" . $qrBusca['des_tarifa'] . "'>
										";
									}
									?>

								</tbody>
							</table>
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/daterangepicker-master/daterangepicker.js"></script>
<link rel="stylesheet" href="js/daterangepicker-master/daterangepicker.css" />

<script type="text/javascript">

	function retornaForm(index) {
		$("#formulario #tip_tarifa").val($("#ret_tip_tarifa_" + index).val());
		$("#formulario #des_tarifa").val($("#ret_des_tarifa_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}


</script>