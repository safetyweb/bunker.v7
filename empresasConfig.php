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
$cod_sistemas = "";
$des_sufixo = "";
$cod_estatus = "";
$log_ativo = "";
$log_precuni = "";
$log_estoque = "";
$cod_master = "";
$cod_layout = "";
$cod_segment = "";
$site = "";
$nr_candidato = "";
$ano_eleicao = "";
$cd_cargo = "";
$cod_estado = "";
$cod_municipio_e = "";
$val_mbruta = "";
$comis_vendedor = "";
$comis_parceiro = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
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
$popUp = "";
$abaEmpresa = "";
$qrLayout = "";
$qrLista = "";
$endObriga = "";
$arrayEstado = [];
$qrEstado = "";


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

		$cod_empresa = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
		$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
		$nom_empresa = fnLimpacampo(@$_REQUEST['NOM_EMPRESA']);
		$des_abrevia = fnLimpacampo(@$_REQUEST['DES_ABREVIA']);
		$nom_respons = fnLimpacampo(@$_REQUEST['NOM_RESPONS']);
		$num_cgcecpf = fnLimpacampo(@$_REQUEST['NUM_CGCECPF']);
		$num_escrica = fnLimpacampo(@$_REQUEST['NUM_ESCRICA']);
		$nom_fantasi = fnLimpacampo(@$_REQUEST['NOM_FANTASI']);
		$num_telefon = fnLimpacampo(@$_REQUEST['NUM_TELEFON']);
		$num_celular = fnLimpacampo(@$_REQUEST['NUM_CELULAR']);
		$des_enderec = fnLimpacampo(@$_REQUEST['DES_ENDEREC']);
		$num_enderec = fnLimpacampo(@$_REQUEST['NUM_ENDEREC']);
		$des_complem = fnLimpacampo(@$_REQUEST['DES_COMPLEM']);
		$des_bairroc = fnLimpacampo(@$_REQUEST['DES_BAIRROC']);
		$num_cepozof = fnLimpacampo(@$_REQUEST['NUM_CEPOZOF']);
		$nom_cidadec = fnLimpacampo(@$_REQUEST['NOM_CIDADEC']);
		$cod_estadof = fnLimpacampo(@$_REQUEST['COD_ESTADOF']);
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
		$site = fnLimpacampo(@$_REQUEST['SITE']);

		$nr_candidato = fnLimpacampoZero(@$_REQUEST['NR_CANDIDATO']);
		$ano_eleicao = fnLimpacampoZero(@$_REQUEST['ANO_ELEICAO']);
		$cd_cargo = fnLimpacampoZero(@$_REQUEST['CD_CARGO']);
		$cod_estado = fnLimpacampoZero(@$_REQUEST['COD_ESTADO']);
		$cod_municipio_e = fnLimpacampoZero(@$_REQUEST['COD_MUNICIPIO_E']);

		$val_mbruta = fnLimpaCampo(@$_REQUEST['VAL_MBRUTA']);
		$comis_vendedor = fnLimpaCampo(fnValorSql(@$_REQUEST['COMIS_VENDEDOR'], 2));
		$comis_parceiro = fnLimpaCampo(fnValorSql(@$_REQUEST['COMIS_PARCEIRO'], 2));


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
				 '" . $comis_vendedor . "',
				 '" . $comis_parceiro . "',
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($adm, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}
			//fnEscreve($cod_erro);

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
	$arrayQuery = mysqli_query($adm, $sql);
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
		if ($log_ativo == 'S') {
			$checadoLog_ativo = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$checadoLog_ativo = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
		}
		if ($log_precuni == 'S') {
			$checadoLog_precuni = 'checked';
		} else {
			$checadoLog_precuni = '';
		}
		if ($log_precuni == 'S') {
			$tem_precuni = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$tem_precuni = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
		}
		if ($log_estoque == 'S') {
			$checadoLog_estoque = 'checked';
		} else {
			$checadoLog_estoque = '';
		}
		if ($log_estoque == 'S') {
			$tem_estoque = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$tem_estoque = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
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
			$tem_sistemas = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$tem_sistemas = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
		}
		$cod_master = $qrBuscaEmpresa['COD_MASTER'];
		$cod_layout = $qrBuscaEmpresa['COD_LAYOUT'];
		$cod_segment = $qrBuscaEmpresa['COD_SEGMENT'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
		$des_status = $qrBuscaEmpresa['DES_STATUS'];
		$log_consext = $qrBuscaEmpresa['LOG_CONSEXT'];
		if ($log_consext == 'S') {
			$tem_consext = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$tem_consext = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
		}
		$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];
		if ($log_autocad == 'S') {
			$tem_autocad = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
		} else {
			$tem_autocad = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
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
		$comis_vendedor = $qrBuscaEmpresa['COMIS_VENDEDOR'];
		$comis_parceiro = $qrBuscaEmpresa['COMIS_PARCEIRO'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnEscreve(fnLimpaDoc($num_cgcecpf));
//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
//fnEscreve(fnDecode(@$_GET['ID']));	
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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_fantasi; ?></span>
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

						//aba default
						$abaEmpresa = 1020;

						//menu abas
						include "abasEmpresas.php";
					}
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais </legend>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ativa</label>
											<div class="push15"></div>
											<?php echo $checadoLog_ativo; ?>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Status</label>
											<div class="push10"></div>
											<b><?php echo "Em " . $des_status; ?> </b>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Contabilização</label>
											<div class="push10"></div>
											<b><?php echo $tip_contabil; ?> </b>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Consulta Externa</label>
											<div class="push15"></div>
											<b><?php echo $tem_consext; ?> </b>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cadastro Automático</label>
											<div class="push15"></div>
											<b><?php echo $tem_autocad; ?> </b>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controle de Preço por Loja</label>
											<div class="push15"></div>
											<b><?php echo $tem_precuni; ?> </b>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controla Estoque</label>
											<div class="push15"></div>
											<b><?php echo $tem_estoque; ?> </b>
										</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										</div>
									</div>

									<div class="col-md-4">
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
											<label for="inputName" class="control-label">Abreviação</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="5" value="<?php echo $des_abrevia; ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Responsável</label>
											<input type="text" class="form-control input-sm" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="50" value="<?php echo $nom_respons; ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CNPJ/CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?php echo fnLimpaDoc($num_cgcecpf); ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Inscrição Estadual</label>
											<input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" value="<?php echo $num_escrica; ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Principal</label>
											<input type="text" class="form-control input-sm fone" name="NUM_TELEFON" id="NUM_TELEFON" value="<?php echo $num_telefon; ?>" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Celular</label>
											<input type="text" class="form-control input-sm cel" name="NUM_CELULAR" id="NUM_CELULAR" value="<?php echo $num_celular; ?>" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Endereço</label>
											<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" value="<?php echo $des_enderec; ?>" maxlength="40">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número</label>
											<input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" maxlength="10">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Complemento</label>
											<input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" value="<?php echo $des_complem; ?>" maxlength="99">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Bairro</label>
											<input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" value="<?php echo $des_bairroc; ?>" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">CEP</label>
											<input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" maxlength="9">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cidade</label>
											<input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>" maxlength="40">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Estado</label>
											<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
												<option value=""></option>
												<option value="AC">AC</option>
												<option value="AL">AL</option>
												<option value="AM">AM</option>
												<option value="AP">AP</option>
												<option value="BA">BA</option>
												<option value="CE">CE</option>
												<option value="DF">DF</option>
												<option value="ES">ES</option>
												<option value="GO">GO</option>
												<option value="MA">MA</option>
												<option value="MG">MG</option>
												<option value="MS">MS</option>
												<option value="MT">MT</option>
												<option value="PA">PA</option>
												<option value="PB">PB</option>
												<option value="PE">PE</option>
												<option value="PI">PI</option>
												<option value="PR">PR</option>
												<option value="RJ">RJ</option>
												<option value="RN">RN</option>
												<option value="RO">RO</option>
												<option value="RR">RR</option>
												<option value="RS">RS</option>
												<option value="SC">SC</option>
												<option value="SE">SE</option>
												<option value="SP">SP</option>
												<option value="TO">TO</option>
											</select>
											<script>
												$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Site</label>
											<input type="text" class="form-control input-sm" name="SITE" id="SITE" value="<?php echo $site; ?>" maxlength="100">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Layout</label>
											<select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php

												$sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
												$arrayQuery = mysqli_query($adm, $sql);

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

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Segmento</label>
											<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php

												$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
												$arrayQuery = mysqli_query($adm, $sql);

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

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Fator de Margem Bruta</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_MBRUTA" id="VAL_MBRUTA" value="<?php echo $val_mbruta; ?>" maxlength="10">
											<div class="help-block with-errors">para uso na comunicação</div>
										</div>
									</div>

									<?php
									if ($cod_empresa == 274) {
									?>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Comissão de Vendedor %</label>
												<input type="text" class="form-control input-sm text-center money" name="COMIS_VENDEDOR" id="COMIS_VENDEDOR" value="<?php echo $comis_vendedor; ?>" maxlength="10">
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Comissão de Parceiros %</label>
												<input type="text" class="form-control input-sm text-center money" name="COMIS_PARCEIRO" id="COMIS_PARCEIRO" value="<?php echo $comis_parceiro; ?>" maxlength="10">
											</div>
										</div>

									<?php
									}
									?>

								</div>

							</fieldset>

							<div class="push20"></div>

							<?php
							//configurações adicionais gabinete
							if ($_SESSION["SYS_COD_MASTER"] == 2) {
							?>

								<fieldset>
									<legend>Dados Complementares </legend>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Código de Referência</label>
												<input type="text" class="form-control input-sm int" name="NR_CANDIDATO" id="NR_CANDIDATO" value="<?php echo $nr_candidato; ?>" maxlength="100">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Ano Referência</label>
												<select data-placeholder="Selecione o ano de referência" name="ANO_ELEICAO" id="ANO_ELEICAO" class="chosen-select-deselect">
													<option value=""></option>
													<?php

													$sql = "SELECT * FROM ANO_ELEICAO";
													$arrayQuery = mysqli_query($conn, $sql);

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
															<option value='" . $qrLista['ANO_ELEICAO'] . "'>" . $qrLista['ANO_ELEICAO'] . "</option> 
														";
													}
													?>
												</select>
												<script>
													$("#formulario #ANO_ELEICAO").val("<?php echo $ano_eleicao; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors">Default</div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Cargo Referência</label>
												<select data-placeholder="Selecione o cargo de referência" name="CD_CARGO" id="CD_CARGO" class="chosen-select-deselect">
													<option value=""></option>
													<?php

													$sql = "SELECT * FROM CARGO_ELEICAO";
													$arrayQuery = mysqli_query($conn, $sql);

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

										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label <?= $endObriga ?>">Estado Referência</label>
												<select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect" <?= $endObriga ?>>
													<option value=""></option>
													<?php

													$sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
													$arrayEstado = mysqli_query($conn, $sql);
													while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
													?>
														<option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
													<?php
													}

													?>
												</select>
												<script>
													$("#formulario #COD_ESTADO").val("<?php echo $cod_estado; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-xs-2" id="relatorioCidade">
											<div class="form-group">
												<label for="inputName" class="control-label <?= $endObriga ?>">Cidade Referência</label>
												<select data-placeholder="Selecione um estado" name="COD_MUNICIPIO_E" id="COD_MUNICIPIO_E" class="chosen-select-deselect" <?= $endObriga ?>>
													<option value=""></option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>
								</fieldset>

							<?php
							} else {
							?>

								<input type="hidden" name="NR_CANDIDATO" id="NR_CANDIDATO" value="<?php echo $nr_candidato; ?>" />
								<input type="hidden" name="ANO_ELEICAO" id="ANO_ELEICAO" value="<?php echo $ano_eleicao; ?>" />
								<input type="hidden" name="CD_CARGO" id="CD_CARGO" value="<?php echo $cd_cargo; ?>" />
								<input type="hidden" name="COD_ESTADO" id="COD_ESTADO" value="<?php echo $cod_estado; ?>" />
								<input type="hidden" name="CDO_MUNICIPIO_E" id="COD_MUNICIPIO_E" value="<?php echo $cod_municipio_e; ?>" />

							<?php
							}
							?>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_empresa == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<?php if (fnControlaAcesso($_GET["mod"], $_SESSION["SYS_MODUL_AUTOR"]) === true) { ?>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button>

								<?php
									}
								}
								?>
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
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

	<div class="push20"></div>

	<script type="text/javascript">
		$(document).ready(function() {

			carregaComboCidades('<?= $cod_estado ?>');

			$(".nav-tabs li").on("click", function(e) {
				if ($(this).hasClass("disabled")) {
					e.preventDefault();
					return false;
				}
			});

			$("#COD_ESTADO").change(function() {
				cod_estado = $(this).val();
				carregaComboCidades(cod_estado);
				estado = $("#COD_ESTADO option:selected").text();
				$('#COD_ESTADOF').val(estado);
				$('#NOM_CIDADEC').val('');
			});

		});

		function carregaComboCidades(cod_estado) {
			$.ajax({
				method: 'POST',
				url: 'ajxComboMunicipioEmpresa.do?id=<?= fnEncode($cod_empresa) ?>',
				data: {
					COD_ESTADO: cod_estado
				},
				beforeSend: function() {
					$('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioCidade").html(data);
					$("#formulario #COD_MUNICIPIO_E").val("<?php echo $cod_municipio_e; ?>").trigger("chosen:updated");
					// $('#formulario').validator('validate');
				}
			});
		}
	</script>