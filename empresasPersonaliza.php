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
$cod_cadastr = "";
$cod_layout = "";
$des_barra = "";
$des_tela = "";
$des_logomain = "";
$des_logo = "";
$des_imgback = "";
$cod_sistemas = "";
$des_sufixo = "";
$cod_estatus = "";
$log_ativo = "";
$log_precuni = "";
$log_estoque = "";
$cod_master = "";
$cod_segment = "";
$tip_logo = "";
$site = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$nom_empresa = "";
$des_abrevia = "";
$nom_respons = "";
$num_cgcecpf = "";
$num_escrica = "";
$nom_fantasi = "";
$num_telefon = "";
$num_celular = "";
$des_enderec = "";
$num_enderec = "";
$des_complem = "";
$des_bairroc = "";
$num_cepozof = "";
$nom_cidadec = "";
$cod_estadof = "";
$nr_candidato = "";
$ano_eleicao = "";
$cd_cargo = "";
$cod_estado = "";
$cod_municipio_e = "";
$val_mbruta = "";
$sqlUpdt = "";
$arrayUpdate = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$checadoLog_ativo = "";
$checadoLog_precuni = "";
$tem_precuni = "";
$checadoLog_estoque = "";
$tem_estoque = "";
$tem_sistemas = "";
$des_status = "";
$log_consext = "";
$tem_consext = "";
$log_autocad = "";
$tem_autocad = "";
$tip_contabil = "";
$sqlControle = "";
$arrayControle = [];
$qrControle = "";
$des_img_g = "";
$des_img = "";
$des_imgmob = "";
$arrayDom = [];
$qrDom = "";
$des_dominio = "";
$cod_dominio = "";
$extensaoDominio = "";
$linkCode = "";
$linkCode2 = "";
$popUp = "";
$abaEmpresa = "";
$qrLayout = "";
$nome = "";

$hashLocal = mt_rand();

$conn = conntemp(@$cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
		$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
		$cod_layout = fnLimpacampo(@$_REQUEST['COD_LAYOUT']);
		$des_barra = fnLimpacampo(@$_REQUEST['DES_BARRA']);
		$des_tela = fnLimpacampo(@$_REQUEST['DES_TELA']);
		$des_logomain = fnLimpacampo(@$_REQUEST['DES_LOGOMAIN']);
		$des_logo = fnLimpacampo(@$_REQUEST['DES_LOGO']);
		$des_imgback = fnLimpacampo(@$_REQUEST['DES_IMGBACK']);
		//bloco versão tela simplificada
		$cod_sistemas = "";
		$des_sufixo = "";
		$cod_estatus = 0;
		$log_ativo = "";
		$log_precuni = '';
		$log_estoque = '';
		$cod_master = '';

		$cod_layout = fnLimpacampoZero(@$_REQUEST['COD_LAYOUT']);
		$cod_segment = fnLimpacampoZero(@$_REQUEST['COD_SEGMENT']);
		$tip_logo = fnLimpacampoZero(@$_REQUEST['TIP_LOGO']);
		$site = fnLimpacampo(@$_REQUEST['SITE']);

		//fnEscreve($cod_master);			
		if ($cod_master ==  0 || $cod_master ==  "") {
			$cod_master = $_SESSION["SYS_COD_EMPRESA"];
		}

		//fnEscreve($cod_sistemas);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];


		if ($opcao != '') {

			// p_NR_CANDIDATO
			// p_ANO_ELEICAO
			// p_CD_CARGO
			// p_COD_ESTADO
			// p_COD_MUNICIPIO_E
			// P_VAL_MBRUTA

			// $sql = "CALL SP_ALTERA_EMPRESAS_OWNER (
			//  '".$cod_empresa."', 
			//  '".$cod_cadastr."', 
			//  '".$nom_empresa."', 
			//  '".$des_abrevia."', 
			//  '".$nom_respons."', 
			//  '".$num_cgcecpf."', 
			//  '".$num_escrica."', 
			//  '".$nom_fantasi."', 
			//  '".$num_telefon."', 
			//  '".$num_celular."', 
			//  '".$des_enderec."', 
			//  '".$num_enderec."', 
			//  '".$des_complem."', 
			//  '".$des_bairroc."', 				 
			//  '".$num_cepozof."',				 
			//  '".$nom_cidadec."',    
			//  '".$cod_estadof."',    
			//  '".$cod_layout."',    
			//  '".$cod_segment."',
			//  '".$site."',
			//  '".$nr_candidato."',
			//  '".$ano_eleicao."',
			//  '".$cd_cargo."',
			//  '".$cod_estado."',
			//  '".$cod_municipio_e."',
			//  '".$val_mbruta."',
			//  '".$opcao."'    
			// ) ";

			// //echo $sql;

			// mysqli_query($connAdm->connAdm(),trim($sql));	

			$sqlUpdt = "UPDATE EMPRESAS SET
							COD_LAYOUT = '$cod_layout',
							-- DES_BARRA = '$des_barra',
							-- DES_TELA = '$des_tela',
							TIP_LOGO = $tip_logo,
							DES_LOGO = '$des_logo',
							DES_LOGOMAIN = '$des_logomain',
							DES_IMGBACK = '$des_imgback'
							WHERE COD_EMPRESA = $cod_empresa";

			// fnEscreve($sqlUpdt);

			$arrayUpdate = mysqli_query($adm, trim($sqlUpdt));

			if (!$arrayUpdate) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt, $nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
		$log_ativo = $qrBuscaEmpresa['LOG_ATIVO'];
		$tip_logo = $qrBuscaEmpresa['TIP_LOGO'];
		if ($log_ativo == 'S') {
			$checadoLog_ativo = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$checadoLog_ativo = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		if ($log_precuni == 'S') {
			$checadoLog_precuni = 'checked';
		} else {
			$checadoLog_precuni = '';
		}
		if ($log_precuni == 'S') {
			$tem_precuni = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_precuni = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		if ($log_estoque == 'S') {
			$checadoLog_estoque = 'checked';
		} else {
			$checadoLog_estoque = '';
		}
		if ($log_estoque == 'S') {
			$tem_estoque = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_estoque = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
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
		$des_logo = fnBase64DecodeImg($qrBuscaEmpresa['DES_LOGO']);
		$des_logomain = fnBase64DecodeImg($qrBuscaEmpresa['DES_LOGOMAIN']);
		$des_imgback = fnBase64DecodeImg($qrBuscaEmpresa['DES_IMGBACK']);
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}


$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$sql = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$arrayDom = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

$qrDom = mysqli_fetch_assoc($arrayDom);

$des_dominio = $qrDom['DES_DOMINIO'];
$cod_dominio = $qrDom['COD_DOMINIO'];

if ($cod_dominio == 2) {
	$extensaoDominio = ".fidelidade.mk";
} else {
	$extensaoDominio = ".mais.cash";
}

$linkCode = "https://" . $des_dominio . $extensaoDominio;
$linkCode2 = "https://" . $des_dominio . $extensaoDominio . "/token";


//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
//fnEscreve(fnDecode(@$_GET['ID']));	
//fnEscreve(fnDecode(@$_GET['id']));		
//fnMostraForm();


?>

<style type="text/css">
	body {
		/*overflow: hidden;*/
	}
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
						$abaEmpresa = 1340;

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

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>

								<legend>Imagens Área de cadastro (Totem/Hotsite)</legend>

								<div class="row">


									<div class="col-md-3">
										<label for="inputName" class="control-label required">Imagem Desktop (G)</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_G" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_IMG_G" id="DES_IMG_G" maxlength="100" value="<?php echo $des_img_g; ?>">
											<input type="text" name="IMG_G" id="IMG_G" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img_g); ?>">
										</div>
										<span class="help-block">(.jpg 940px X 845px)</span>
									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label required">Imagem Tablet (M)</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_IMG" id="DES_IMG" maxlength="100" value="<?php echo $des_img; ?>">
											<input type="text" name="IMG" id="IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_img); ?>">
										</div>
										<span class="help-block">(.jpg 680px X 675px)</span>
									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label required">Imagem Mobile (P)</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGMOB" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_IMGMOB" id="DES_IMGMOB" maxlength="100" value="<?php echo $des_imgmob; ?>">
											<input type="text" name="IMGMOB" id="IMGMOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imgmob); ?>">
										</div>
										<span class="help-block">(.jpg 360px X 360px)</span>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Dados Gerais </legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Layout</label>
											<select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php

												$sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrLayout['COD_LAYOUT'] . "'>" . $qrLayout['DES_LAYOUT'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_LAYOUT").val("<?php echo $cod_layout; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Logo de Sistema</label>
											<select data-placeholder="Selecione um tipo" name="TIP_LOGO" id="TIP_LOGO" class="chosen-select-deselect">
												<option value="0">Tema Claro</option>
												<option value="1">Tema Escuro</option>
											</select>
											<script type="text/javascript">
												$("#TIP_LOGO").val("<?= $tip_logo ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Telas do Sistema</label>
																<select data-placeholder="Selecione um estado" name="DES_TELA" id="DES_TELA" class="chosen-select-deselect">
																	<option value="WIDE">Wide</option>												
																	<option value="BOXED">Boxed</option>												
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

									<div class="col-md-2">
										<label for="inputName" class="control-label">Logotipo</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload2" idinput="DES_LOGOMAIN" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_LOGOMAIN" id="DES_LOGOMAIN" maxlength="100" value="<?php echo $des_logomain; ?>">
											<input type="text" name="LOGOMAIN" id="LOGOMAIN" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logomain; ?>">
										</div>
										<span class="help-block">(.png 200px X 35px ou na mesma proporção)</span>
									</div>

									<div class="col-md-2">
										<label for="inputName" class="control-label">Logo de relatório</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload2" idinput="DES_LOGO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_LOGO" id="DES_LOGO" maxlength="100" value="<?php echo $des_logo; ?>">
											<input type="text" name="LOGO" id="LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
										</div>
										<span class="help-block">(.png 200px X 35px ou na mesma proporção)</span>
									</div>

									<div class="col-md-2">
										<label for="inputName" class="control-label">Imagem da Barra</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload2" idinput="DES_IMGBACK" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="DES_IMGBACK" id="DES_IMGBACK" maxlength="100" value="<?php echo $des_imgback; ?>">
											<input type="text" name="IMGBACK" id="IMGBACK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgback; ?>">
										</div>
										<span class="help-block">(.jpg 1900px X 150px)</span>
									</div>

								</div>

								<?php if ($des_dominio != '') { ?>

									<div class="row">

										<div class="col-md-3">

											<div class="push20"></div>

											<div id="qrcodeCanvas"></div>
											<div id="qrcodeCanvas_save" style="display:none;"></div>

											<div class="push10"></div>

										</div>

										<div class="col-md-3">

											<div class="push20"></div>

											<div id="qrcodeCanvas2"></div>
											<div id="qrcodeCanvas_save2" style="display:none;"></div>

											<div class="push10"></div>

										</div>

									</div>

									<div class="row">

										<div class="col-md-3">
											<div class="push5"></div>
											<h5>QrCode acesso Hotsite</h5>
											<div class="push20"></div>
											<a href="javascript:void(0)" class="btn btn-info" id="saveQr"><span class="fal fa-save"></span>&nbsp;Salvar imagem</a>
										</div>

										<div class="col-md-3">
											<div class="push5"></div>
											<h5>QrCode acesso Token</h5>
											<div class="push20"></div>
											<a href="javascript:void(0)" class="btn btn-info" id="saveQr2"><span class="fal fa-save"></span>&nbsp;Salvar imagem</a>
										</div>

									</div>

								<?php } ?>

								<div class="push10"></div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group col-lg-6">
								<!-- <button class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp; Preview</button> -->
							</div>
							<div class="form-group text-right col-lg-6">

								<button type="reset" class="btn btn-default"><i class="fal fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_empresa == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button>
								<?php } ?>
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="NR_CANDIDATO" id="NR_CANDIDATO" value="<?= $nr_candidato ?>">
							<input type="hidden" name="ANO_ELEICAO" id="ANO_ELEICAO" value="<?= $ano_eleicao ?>">
							<input type="hidden" name="CD_CARGO" id="CD_CARGO" value="<?= $cd_cargo ?>">
							<input type="hidden" name="COD_ESTADO" id="COD_ESTADO" value="<?= $cod_estado ?>">
							<input type="hidden" name="COD_MUNICIPIO_E" id="COD_MUNICIPIO_E" value="<?= $cod_municipio_e ?>">
							<input type="hidden" name="VAL_MBRUTA" id="VAL_MBRUTA" value="<?= $val_mbruta ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push10"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push100"></div>
	<div class="push100"></div>
	<div class="push100"></div>
	<div class="push100"></div>

	<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
	<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>

	<script type="text/javascript">
		parent.$("#conteudoAba4").css("height", ($(".portlet").height() + 50) + "px");

		$(document).ready(function() {

			geraQRCode();
			geraQRCode2();

			$("#saveQr").click(function() {
				this.href = $('#qrcodeCanvas_save canvas')[0].toDataURL(); // Change here
				this.download = 'qrCode_' + "<?= $nome ?>" + '.jpg';
			});

			$("#saveQr2").click(function() {
				this.href = $('#qrcodeCanvas_save2 canvas')[0].toDataURL(); // Change here
				this.download = 'qrCode_' + "<?= $nome ?>" + '.jpg';
			});

			$(".nav-tabs li").on("click", function(e) {
				if ($(this).hasClass("disabled")) {
					e.preventDefault();
					return false;
				}
			});

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

			$('.upload2').on('click', function(e) {
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
						'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile2(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
						'</form>'
				});
			});

		});

		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes/');
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
						$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
						$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);

						$.ajax({
							type: "POST",
							url: "ajxImgTermos.php",
							data: {
								COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
								NOM_ARQ: data.nome_arquivo,
								CAMPO: idField
							},
							success: function(data) {
								console.log(data);
								$.alert({
									title: "Mensagem",
									content: "Upload feito com sucesso",
									type: 'green'
								});
							}
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


		function uploadFile2(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes');
			formData.append('diretorioAdicional', 'logotipo');
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
						$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
						$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);

						$.ajax({
							type: "POST",
							url: "ajxImgTermos.php",
							data: {
								COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
								NOM_ARQ: data.nome_arquivo,
								CAMPO: idField
							},
							success: function(data) {
								$.alert({
									title: "Mensagem",
									content: "Upload feito com sucesso",
									type: 'green'
								});
							}
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

		function geraQRCode() {
			$("#qrcodeCanvas").html("");
			jQuery('#qrcodeCanvas').qrcode({
				text: "<?= $linkCode ?>",
				width: 150,
				height: 150
			});
			$("#qrcodeCanvas_save").html("");
			jQuery('#qrcodeCanvas_save').qrcode({
				text: "<?= $linkCode ?>",
				width: 500,
				height: 500
			});
		}

		function geraQRCode2() {
			$("#qrcodeCanvas2").html("");
			jQuery('#qrcodeCanvas2').qrcode({
				text: "<?= $linkCode2 ?>",
				width: 150,
				height: 150
			});
			$("#qrcodeCanvas_save2").html("");
			jQuery('#qrcodeCanvas_save2').qrcode({
				text: "<?= $linkCode2 ?>",
				width: 500,
				height: 500
			});
		}
	</script>