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
$cod_resgate = "";
$tip_momresg = "";
$num_diasrsg = "";
$qtd_validad = 0;
$tip_diasvld = "";
$qtd_inativo = 0;
$num_inativo = "";
$num_minresg = "";
$pct_maxresg = "";
$qtd_fraudes = 0;
$tip_fraudes = "";
$tip_libfunc = "";
$tip_libclie = "";
$tip_relinfo = "";
$hor_relinfo = "";
$cod_mailusu = "";
$Arr_COD_MAILUSU = "";
$i = 0;
$cod_acesusu = "";
$Arr_COD_ACESUSU = "";
$cod_program = "";
$nom_empresa = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$abaPersona = "";
$abaVantagem = "";
$abaRegras = "";
$abaComunica = "";
$abaAtivacao = "";
$abaResultado = "";
$abaPersonaComp = "";
$abaCampanhaComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaAtivacaoComp = "";
$abaResultadoComp = "";
$cod_campanha = "";
$qrBuscaCampanha = "";
$log_ativo = "";
$des_campanha = "";
$abr_campanha = "";
$des_icone = "";
$tip_campanha = "";
$log_realtime = "";
$cod_ext_campanha = "";
$log_processa = "";
$qrBuscaTpCampanha = "";
$nom_tpcampa = "";
$abv_tpcampa = "";
$des_iconecp = "";
$label_1 = "";
$label_2 = "";
$label_3 = "";
$label_4 = "";
$label_5 = "";
$syncSms = "";
$syncPush = "";
$qrPfl = "";
$sqlAut = "";
$qrAut = "";
$modsAutorizados = "";
$temEmail = "";
$temSms = "";
$temPush = "";
$tooltip_push = "";
$tooltip_sms = "";
$tooltip_email = "";
$sql1 = "";
$arrayQuery1 = [];
$qrAcessoIntegracao = "";
$sql2 = "";
$arrayQuery2 = [];
$qrAcessoIntegracao2 = "";
$integracaoWhats = "";
$temWhats = "";
$tooltip_whats = "";
$formBack = "";
$abaCampanhas = "";
$nom_vantagem = "";
$num_pessoas = "";
$sqlPesq = "";
$arrayPesq = [];
$qtd_pesq = 0;
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_resgate = fnLimpaCampoZero(@$_REQUEST['COD_RESGATE']);
		$tip_momresg = fnLimpaCampo(@$_REQUEST['TIP_MOMRESG']);
		$num_diasrsg = fnLimpaCampoZero(@$_REQUEST['NUM_DIASRSG']);
		$qtd_validad = fnLimpaCampoZero(@$_REQUEST['QTD_VALIDAD']);
		$tip_diasvld = fnLimpaCampo(@$_REQUEST['TIP_DIASVLD']);
		$qtd_inativo = fnLimpaCampoZero(@$_REQUEST['QTD_INATIVO']);
		$num_inativo = fnLimpaCampo(@$_REQUEST['NUM_INATIVO']);
		$num_minresg = fnLimpaCampo(@$_REQUEST['NUM_MINRESG']);
		$pct_maxresg = fnLimpaCampo(@$_REQUEST['PCT_MAXRESG']);
		$qtd_fraudes = fnLimpaCampoZero(@$_REQUEST['QTD_FRAUDES']);
		$tip_fraudes = fnLimpaCampo(@$_REQUEST['TIP_FRAUDES']);
		$tip_libfunc = fnLimpaCampo(@$_REQUEST['TIP_LIBFUNC']);
		$tip_libclie = fnLimpaCampo(@$_REQUEST['TIP_LIBCLIE']);
		$tip_relinfo = fnLimpaCampo(@$_REQUEST['TIP_RELINFO']);
		$hor_relinfo = fnLimpaCampo(@$_REQUEST['HOR_RELINFO']);

		//$cod_mailusu = fnLimpaCampo(@$_REQUEST['COD_MAILUSU']);			
		//array das usuários email
		if (isset($_POST['COD_MAILUSU'])) {
			$Arr_COD_MAILUSU = @$_POST['COD_MAILUSU'];
			//print_r($Arr_COD_MAILUSU);			 
			for ($i = 0; $i < count($Arr_COD_MAILUSU); $i++) {
				$cod_mailusu = $cod_mailusu . $Arr_COD_MAILUSU[$i] . ",";
			}
			$cod_mailusu = substr($cod_mailusu, 0, -1);
		} else {
			$cod_mailusu = "0";
		}

		//$cod_acesusu = fnLimpaCampo(@$_REQUEST['COD_ACESUSU']);
		//array das usuários de acesso
		if (isset($_POST['COD_ACESUSU'])) {
			$Arr_COD_ACESUSU = @$_POST['COD_ACESUSU'];
			//print_r($Arr_COD_ACESUSU);			 
			for ($i = 0; $i < count($Arr_COD_ACESUSU); $i++) {
				$cod_acesusu = $cod_acesusu . $Arr_COD_ACESUSU[$i] . ",";
			}
			$cod_acesusu = substr($cod_acesusu, 0, -1);
		} else {
			$cod_acesusu = "0";
		}

		$cod_program = fnLimpaCampoZero(@$_REQUEST['COD_PROGRAM']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo(@$_REQUEST['NOM_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
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
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "completed ";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da campanha
$cod_campanha = fnDecode(@$_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaCampanha)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
	$cod_ext_campanha = $qrBuscaCampanha['COD_EXT_CAMPANHA'];
	$log_processa = $qrBuscaCampanha['LOG_PROCESSA'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaTpCampanha)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

$sql = "SELECT COD_LISTA FROM SMS_PARAMETROS where COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$syncSms = mysqli_num_rows($arrayQuery);

$sql = "SELECT COD_LISTA FROM PUSH_PARAMETROS where COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$syncPush = mysqli_num_rows($arrayQuery);
//fnMostraForm();	
//fnEscreve($num_minresg);
// fnEscreve($cod_ext_campanha);
// fnEscreve($log_processa);

//Busca módulos autorizados
$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
			   COD_SISTEMA = 4 
			   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlAut));

$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

//VERIFICA SE EXISTE SENHAS DE COMUNICAÇÃO PARA A EMPRESA, SE NÃO TIVER DESABILITA O BOTÃO
$temEmail = "pointer-events: none; cursor: default; opacity: 0.5;";
$temSms = "pointer-events: none; cursor: default; opacity: 0.5;";
$temPush = "pointer-events: none; cursor: default; opacity: 0.5;";
$tooltip_push = "data-toggle='tooltip' data-placement='bottom' data-original-title='É preciso Habilitar o canal de comunicação Push para sua empresa. Entre em contato com seu consultor'";
$tooltip_sms = "data-toggle='tooltip' data-placement='bottom' data-original-title='É preciso Habilitar o canal de comunicação Sms para sua empresa. Entre em contato com seu consultor'";
$tooltip_email = "data-toggle='tooltip' data-placement='bottom' data-original-title='É preciso Habilitar o canal de comunicação Email para sua empresa. Entre em contato com seu consultor'";


$sql1 = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
			WHERE apar.COD_EMPRESA=$cod_empresa 
			AND apar.LOG_ATIVO='S'";

$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);

while ($qrAcessoIntegracao = mysqli_fetch_assoc($arrayQuery1)) {

	if ($qrAcessoIntegracao['COD_TPCOM'] == 1) {
		$temEmail = "";
		$tooltip_email = "";
	}

	if ($qrAcessoIntegracao['COD_TPCOM'] == 2) {
		$temSms = "";
		$tooltip_sms = "";
	}

	if ($qrAcessoIntegracao['COD_TPCOM'] == 5) {
		$temPush = "";
		$tooltip_push = "";
	}
}

$sql2 = "SELECT COUNT(1) AS TEMACESSO FROM SENHAS_WHATSAPP apar
			where apar.COD_EMPRESA=$cod_empresa 
			AND apar.LOG_ATIVO='S'";
$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);
$qrAcessoIntegracao2 = mysqli_fetch_assoc($arrayQuery2);

$integracaoWhats = $qrAcessoIntegracao2['TEMACESSO'];

if ($integracaoWhats == 0) {
	$temWhats = "pointer-events: none; cursor: default; opacity: 0.5;";
	$tooltip_whats = "data-toggle='tooltip' data-placement='bottom' data-original-title='É preciso Habilitar o canal de comunicação Whatsapp para sua empresa. Entre em contato com seu consultor'";
} else {
	$temWhats = "";
	$tooltip_whats = "";
}


?>

<link rel="stylesheet" href="css/widgets.css" />

<style>
	.fa-1dot5x {
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}

	.tile {
		border: none;
	}

	.notify-badge {
		position: absolute;
		right: 36%;
		top: 10px;
		background: #18bc9c;
		border-radius: 30px 30px 30px 30px;
		text-align: center;
		color: white;
		font-size: 11px;
	}

	.notify-badge span {
		margin: 0 auto;
	}

	.pos {
		left: 145;
		top: -10;
		/*background: #ffbf00;*/
		font-size: 12px;
		padding-top: 7px;
	}

	.posHidden {
		display: none;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1048";
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

				<?php $abaCampanhas = 1254;
				include "abasCampanhasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Programa</label>
										<div class="push10"></div>
										<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Pessoas Atingidas</label>
										<div class="push10"></div>
										<span class="fal fa-users"></span>&nbsp; <?php echo fnValor($num_pessoas, 0); ?>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push50"></div>

						<div class="row">

							<h3 style="margin: 0 0 20px 15px;">Configure canais de <b>comunicação</b> em sua campanha</h3>

							<div class="push30"></div>

							<div class="col-md-2 text-center shortCut" <?= $tooltip_email ?>>
								<a href="action.php?mod=<?php echo fnEncode(1640) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50; <?= $temEmail ?>">
									<?php if ($cod_ext_campanha != '' && $cod_ext_campanha != 0) { ?>
										<div class="notify-badge text-center"><span class="pos fal fa-check"></span></div>
									<?php } ?>
									<span class="fal fa-envelope fa-1dot5x"></span>
									<p style="height: 40px;">e-Mail</p>
								</a>
								<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
									<!-- <a href="action.php?mod=<?php echo fnEncode(1170) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="btn btn-xs btn-danger ">Email V1</a>   -->
								<?php } ?>
							</div>

							<div class="col-md-2 text-center shortCut" <?= $tooltip_sms ?>>
								<!-- <a href="action.php?mod=<?php echo fnEncode(1171) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50;"> -->
								<a href="action.php?mod=<?php echo fnEncode(1653) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50; <?= $temSms ?>">
									<?php if ($syncSms > 0) { ?>
										<div class="notify-badge text-center"><span class="pos fal fa-check"></span></div>
									<?php } ?>
									<span class="fal fa-comment-alt fa-1dot5x"></span>
									<p style="height: 40px;">SMS</p>
								</a>
								<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
									<!-- <a href="action.php?mod=<?php echo fnEncode(1552) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="btn btn-xs btn-danger ">SMS V1</a>   -->
								<?php } ?>
							</div>

							<div class="col-md-2 shortCut" <?= $tooltip_whats ?>>
								<!-- <div class="disabledBlock"></div>		 -->
								<a href="action.php?mod=<?php echo fnEncode(1912) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50; <?= $temWhats ?>">
									<span class="fab fa-whatsapp fa-1dot5x"></span>
									<p style="height: 40px;">WhatsApp</p>
								</a>
							</div>

							<div class="col-md-2">
								<a href="action.php?mod=<?php echo fnEncode(1177) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50;">
									<span class="fal fa-file-alt fa-1dot5x"></span>
									<p style="height: 40px;">Mensagem PDV</p>
								</a>
							</div>

							<div class="col-md-2 shortCut" <?= $tooltip_push ?>>
								<!-- <div class="disabledBlock"></div>		 -->
								<a href="action.php?mod=<?php echo fnEncode(1870) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50; <?= $temPush ?>">
									<?php if ($syncPush > 0) { ?>
										<div class="notify-badge text-center"><span class="pos fal fa-check"></span></div>
									<?php } ?>
									<span class="fal fa-comment-alt-smile fa-1dot5x"></span>
									<p style="height: 40px;">Push</p>
								</a>
							</div>

							<div class="col-md-2">
								<a href="action.php?mod=<?php echo fnEncode(1628) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($cod_campanha); ?>" class="tile shadow" style="color: #2c3e50;">
									<?php

									$sqlPesq = "SELECT 1 FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha order by DES_PESQUISA";

									$arrayPesq = mysqli_query(connTemp($cod_empresa, ''), $sqlPesq);

									$qtd_pesq = mysqli_num_rows($arrayPesq);

									if ($qtd_pesq > 0) {

									?>
										<div class="notify-badge text-center"><span class="pos fal fa-check"></span></div>
									<?php

									}

									?>
									<span class="fal fa-list fa-1dot5x"></span>
									<p style="height: 40px;">Pesquisa</p>
								</a>
							</div>

						</div>

						<div class="push30"></div>

						<div class="push30"></div>

						<input type="hidden" name="COD_RESGATE" id="COD_RESGATE" value="<?php echo $cod_resgate ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>