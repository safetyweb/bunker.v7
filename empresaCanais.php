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
$des_tiporeg = "";
$nom_canal = "";
$cod_externo = "";
$cod_canal = "";
$cod_usucada = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$qrBuscaEmpresa = "";
$arrayQuery = [];
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrLista = "";


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

		//$cod_tiporeg = fnLimpaCampoZero(@$_REQUEST['COD_TIPOREG']);
		//$des_tiporeg = fnLimpaCampo(@$_REQUEST['DES_TIPOREG']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$nom_canal = fnLimpacampo(@$_REQUEST['NOM_CANAL']);
		$cod_externo = fnLimpaCampo(@$_REQUEST['COD_EXTERNO']);
		$cod_canal = fnLimpacampo(@$_REQUEST['COD_CANAL']);
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			/*$sql = "CALL SP_ALTERA_REGIAO_GRUPO (
				 '".$cod_tiporeg."', 
				 '".$des_tiporeg."', 
				 '".$cod_externo."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";*/

			//echo $sql;

			//fnTestesql(connTemp($cod_empresa),$sql);								

			//mensagem de retorno
			switch ($opcao) {


				case 'CAD':
					$sqlInsert = "INSERT INTO  empresa_canais   ( 
                                                               COD_EMPRESA,  
                                                               NOM_CANAL,  
                                                               COD_EXTERNO,  
                                                               COD_USUCADA,
                                                               DAT_ALTERAC
                                                               ) 
                                                               VALUES (
                                                               '$cod_empresa',
                                                               '$nom_canal',
                                                               '$cod_externo',
                                                               '$cod_usucada',
                                                               now()
                                                               )";
					//fnescreve($sql);

					$arrayInsert = mysqli_query($conn, $sqlInsert);

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					$sqlUpdate = "UPDATE empresa_canais SET 
                                                           NOM_CANAL = '$nom_canal',
                                                           COD_EXTERNO = '$cod_externo', 
                                                           COD_USUALT = $cod_usucada,
                                                           DAT_ALTERAC = now()                   
                                WHERE COD_EMPRESA = $cod_empresa
                                AND COD_CANAL = $cod_canal";
					//fnTestesql(connTemp($cod_empresa,""), $sql);


					$arrayUpdate = mysqli_query($conn, $sqlUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}
					//fnEscreve($sqlUpdate);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					/*$sql = "DELETE FROM empresa_canais
                                                        
                                                        WHERE COD_EMPRESA = $cod_empresa
                                                        AND COD_CANAL = $cod_canal";    
                                        
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;*/
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
	$sql = "SELECT NOM_EMPRESA,COD_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;
	//fnEscreve($sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($adm, $sql));
	//$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);


	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	}
} else {
	$nom_empresa = "";
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

				<?php $abaEmpresa = 1736;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código Canal</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CANAL" id="COD_CANAL" value="">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome Canal</label>
										<input type="text" class="form-control input-sm" name="NOM_CANAL" id="NOM_CANAL" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm int" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="20">
										<div class="help-block with-errors"></div>
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
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

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
											<th>Código Canal</th>
											<th>Nome Canal</th>
											<th>Código Externo</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT COD_CANAL, NOM_CANAL, COD_EXTERNO FROM empresa_canais WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_CANAL";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
										<tr>
										  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
										  <td>" . $qrLista['COD_CANAL'] . "</td>
										  <td>" . $qrLista['NOM_CANAL'] . "</td>
										  <td>" . $qrLista['COD_EXTERNO'] . "</td>
										</tr>
										<input type='hidden' id='ret_COD_CANAL_" . $count . "' value='" . $qrLista['COD_CANAL'] . "'>
										<input type='hidden' id='ret_NOM_CANAL_" . $count . "' value='" . $qrLista['NOM_CANAL'] . "'>
										<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrLista['COD_EXTERNO'] . "'>
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

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_CANAL").val($("#ret_COD_CANAL_" + index).val());
		$("#formulario #NOM_CANAL").val($("#ret_NOM_CANAL_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>