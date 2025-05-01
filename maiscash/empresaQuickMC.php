<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
		$nom_empresa = fnLimpacampo($_REQUEST['NOM_EMPRESA']);
		$des_abrevia = fnLimpacampo($_REQUEST['DES_ABREVIA']);
		$nom_respons = fnLimpacampo($_REQUEST['NOM_RESPONS']);
		$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
		$num_escrica = fnLimpacampo($_REQUEST['NUM_ESCRICA']);
		$nom_fantasi = fnLimpacampo($_REQUEST['NOM_FANTASI']);
		$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
		$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
		$des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
		$num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
		$des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
		$des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
		$num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
		$nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
		$cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
		//bloco versão tela simplificada
		$cod_sistemas = "";
		$des_sufixo = "";
		$cod_estatus = 0;
		$log_ativo = "";
		$log_precuni = '';
		$log_estoque = '';
		$cod_master = '';

		$cod_layout = fnLimpacampoZero($_REQUEST['COD_LAYOUT']);
		$cod_segment = fnLimpacampoZero($_REQUEST['COD_SEGMENT']);
		$site = fnLimpacampo($_REQUEST['SITE']);

		$nr_candidato = fnLimpacampoZero($_REQUEST['NR_CANDIDATO']);
		$ano_eleicao = fnLimpacampoZero($_REQUEST['ANO_ELEICAO']);
		$cd_cargo = fnLimpacampoZero($_REQUEST['CD_CARGO']);
		$cod_estado = fnLimpacampoZero($_REQUEST['COD_ESTADO']);
		if (isset($_REQUEST['COD_MUNICIPIO_E'])) {
			$cod_municipio_e = fnLimpacampoZero($_REQUEST['COD_MUNICIPIO_E']);
		} else {
			$cod_municipio_e = "";
		}

		$val_mbruta = fnLimpaCampo($_REQUEST['VAL_MBRUTA']);


		//fnEscreve($cod_master);			
		if ($cod_master ==  0 || $cod_master ==  "") {
			$cod_master = $_SESSION["SYS_COD_EMPRESA"];
		}

		//fnEscreve($cod_sistemas);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_EMPRESAS_OWNER (
				 '" . $cod_empresa . "', 
				 '" . $cod_cadastr . "', 
				 '" . $nom_empresa . "', 
				 '" . $des_abrevia . "', 
				 '" . $nom_respons . "', 
				 '" . $num_cgcecpf . "', 
				 '" . $num_escrica . "', 
				 '" . $nom_fantasi . "', 
				 '" . $num_telefon . "', 
				 '" . $num_celular . "', 
				 '" . $des_enderec . "', 
				 '" . $num_enderec . "', 
				 '" . $des_complem . "', 
				 '" . $des_bairroc . "', 				 
				 '" . $num_cepozof . "',				 
				 '" . $nom_cidadec . "',    
				 '" . $cod_estadof . "',    
				 '" . $cod_layout . "',    
				 '" . $cod_segment . "',
				 '" . $site . "',
				 '" . $nr_candidato . "',
				 '" . $ano_eleicao . "',
				 '" . $cd_cargo . "',
				 '" . $cod_estado . "',
				 '" . $cod_municipio_e . "',
				 '" . fnValorSql($val_mbruta) . "',
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			mysqli_query($connAdm->connAdm(), trim($sql));

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
	//fnEscreve('entrou if');

	$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.* FROM empresas  
				LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
				where COD_EMPRESA = '" . $cod_empresa . "' 
		";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$cod_cadastr = $qrBuscaEmpresa['COD_CADASTR'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$des_abrevia = $qrBuscaEmpresa['DES_ABREVIA'];
		$nom_respons = $qrBuscaEmpresa['NOM_RESPONS'];
		$num_cgcecpf = $qrBuscaEmpresa['NUM_CGCECPF'];
		$cod_estatus = $qrBuscaEmpresa['COD_STATUS'];
		// $log_ativo = $qrBuscaEmpresa['LOG_ATIVO'];
		// if ($log_ativo == 'S'){$checadoLog_ativo = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';} else {$checadoLog_ativo = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';}
		// if ($log_precuni == 'S'){$checadoLog_precuni = 'checked';} else {$checadoLog_precuni = '';}
		// if ($log_precuni == 'S') {
		// 	$tem_precuni = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		// } else {$tem_precuni = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';}
		// if ($log_estoque == 'S'){$checadoLog_estoque = 'checked';} else {$checadoLog_estoque = '';}
		// if ($log_estoque == 'S') {
		// 	$tem_estoque = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		// } else {$tem_estoque = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';}
		$num_escrica = $qrBuscaEmpresa['NUM_ESCRICA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
		$num_telefon = $qrBuscaEmpresa['NUM_TELEFON'];
		$num_celular = $qrBuscaEmpresa['NUM_CELULAR'];
		$des_enderec = $qrBuscaEmpresa['DES_ENDEREC'];
		$num_enderec = $qrBuscaEmpresa['NUM_ENDEREC'];
		$des_complem = $qrBuscaEmpresa['DES_COMPLEM'];
		$des_bairroc = $qrBuscaEmpresa['DES_BAIRROC'];
		$num_cepozof = $qrBuscaEmpresa['NUM_CEPOZOF'];
		$nom_cidadec = $qrBuscaEmpresa['NOM_CIDADEC'];
		$cod_estadof = $qrBuscaEmpresa['COD_ESTADOF'];
		$cod_sistemas = $qrBuscaEmpresa['COD_SISTEMAS'];
		if (!empty($cod_sistemas)) {
			$tem_sistemas = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_sistemas = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		$cod_master = $qrBuscaEmpresa['COD_MASTER'];
		$cod_layout = $qrBuscaEmpresa['COD_LAYOUT'];
		$cod_segment = $qrBuscaEmpresa['COD_SEGMENT'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
		$des_status = $qrBuscaEmpresa['DES_STATUS'];
		$log_consext = $qrBuscaEmpresa['LOG_CONSEXT'];
		if ($log_consext == 'S') {
			$tem_consext = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_consext = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];
		if ($log_autocad == 'S') {
			$tem_autocad = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_autocad = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}

		$tip_contabil = $qrBuscaEmpresa['TIP_CONTABIL'];
		if ($tip_contabil == 'RESG') {
			$tip_contabil = 'Resgate';
		} else {
			$tip_contabil = 'Desconto';
		}
		$site = $qrBuscaEmpresa['SITE'];

		$nr_candidato = $qrBuscaEmpresa['NR_CANDIDATO'];
		$ano_eleicao = $qrBuscaEmpresa['ANO_ELEICAO'];
		$cd_cargo = $qrBuscaEmpresa['CD_CARGO'];
		$cod_estado = $qrBuscaEmpresa['COD_ESTADO'];
		$cod_municipio_e = $qrBuscaEmpresa['COD_MUNICIPIO_E'];
		$val_mbruta = $qrBuscaEmpresa['VAL_MBRUTA'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}


//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
//fnEscreve(fnDecode($_GET['ID']));	
//fnMostraForm();


?>

<style type="text/css">
	body {
		overflow: hidden;
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


					<?php
					//manu superior - empresas
					if ($popUp != "true") {

						$abaEmpresa = 1020;

						//mais cash										
						if (fnLimpacampo(fnDecode($_GET['mod'])) == 1698) {
							$abaEmpresa = 1698;
						}

						//rh									
						if (fnLimpacampo(fnDecode($_GET['mod'])) == 1701) {
							$abaEmpresa = 1701;
						}

						//echo $mod;

						switch ($_SESSION["SYS_COD_SISTEMA"]) {
							case 14: //rede duque
								include "abasEmpresaDuque.php";
								break;
							case 15: //quiz
								include "abasEmpresaQuiz.php";
								break;
							case 16: //gabinete
								include "abasGabinete.php";
								break;
							case 18: //mais cash
								include "abasMaisCash.php";
								break;
							case 19: //rh
								include "abasRH.php";
								break;
							default;
								include "abasEmpresaConfig.php";
								break;
						}
					}
					?>

					<!-- <div class="push30"></div>  -->

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais </legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome da Empresa</label>
											<input type="text" class="form-control input-sm" name="NOM_EMPRESA" id="NOM_EMPRESA" maxlength="100" value="<?php echo $nom_empresa; ?>" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome Fantasia</label>
											<input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" maxlength="249" value="<?php echo $nom_fantasi; ?>" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CNPJ/CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?php echo $num_cgcecpf; ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Segmento</label>
											<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php

												$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrLista['COD_SEGMENT'] . "'>" . $qrLista['NOM_SEGMENT'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_SEGMENT").val("<?php echo $cod_segment; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Responsável</label>
											<input type="text" class="form-control input-sm" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Celular</label>
											<input type="text" class="form-control input-sm cel" name="NUM_CELULAR" id="NUM_CELULAR" value="<?php echo $num_celular; ?>" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Cargo</label>
											<select data-placeholder="Selecione o cargo" name="CD_CARGO" id="CD_CARGO" class="chosen-select-deselect">
												<option value=""></option>
												<?php

												$sql = "SELECT * FROM CARGO_ELEICAO";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrLista['CD_CARGO'] . "'>" . $qrLista['DS_CARGO'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #CD_CARGO").val("<?php echo $cd_cargo; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push20"></div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_empresa == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button>
								<?php } ?>
							</div>

							<input type="hidden" name="NR_CANDIDATO" id="NR_CANDIDATO" value="<?php echo $nr_candidato; ?>" />
							<input type="hidden" name="ANO_ELEICAO" id="ANO_ELEICAO" value="<?php echo $ano_eleicao; ?>" />
							<!-- <input type="hidden" name="CD_CARGO" id="CD_CARGO" value="<?php echo $cd_cargo; ?>" />	 -->
							<input type="hidden" name="COD_ESTADO" id="COD_ESTADO" value="<?php echo $cod_estado; ?>" />
							<input type="hidden" name="CDO_MUNICIPIO_E" id="COD_MUNICIPIO_E" value="<?php echo $cod_municipio_e; ?>" />
							<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?php echo $cod_estadof; ?>">
							<input type="hidden" name="COD_LAYOUT" id="COD_LAYOUT" value="<?php echo $cod_layout; ?>">
							<input type="hidden" name="COD_SEGMENT" id="COD_SEGMENT" value="<?php echo $cod_segment; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="NOM_EMPRESA" id="NOM_EMPRESA" maxlength="100" value="<?php echo $nom_empresa; ?>">
							<input type="hidden" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="5" value="<?php echo $des_abrevia; ?>">
							<input type="hidden" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" value="<?php echo $num_escrica; ?>">
							<input type="hidden" name="NUM_TELEFON" id="NUM_TELEFON" value="<?php echo $num_telefon; ?>" maxlength="20">
							<input type="hidden" name="DES_ENDEREC" id="DES_ENDEREC" value="<?php echo $des_enderec; ?>" maxlength="40">
							<input type="hidden" name="NUM_ENDEREC" id="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" maxlength="10">
							<input type="hidden" name="DES_COMPLEM" id="DES_COMPLEM" value="<?php echo $des_complem; ?>" maxlength="99">
							<input type="hidden" name="DES_BAIRROC" id="DES_BAIRROC" value="<?php echo $des_bairroc; ?>" maxlength="20">
							<input type="hidden" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" maxlength="9">
							<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>" maxlength="40">
							<input type="hidden" name="SITE" id="SITE" value="<?php echo $site; ?>" maxlength="100">
							<input type="hidden" name="VAL_MBRUTA" id="VAL_MBRUTA" value="<?php echo $val_mbruta; ?>" maxlength="10">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>


					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<script type="text/javascript">
		parent.$("#conteudoAba1").css("height", ($(".portlet").height() + 50) + "px");

		$(document).ready(function() {

			$(".nav-tabs li").on("click", function(e) {
				if ($(this).hasClass("disabled")) {
					e.preventDefault();
					return false;
				}
			});


		});
	</script>