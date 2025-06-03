<?php

include_once 'header.php';
$tituloPagina = "Cadastro";
include_once "navegacao.php";

include_once './totem/funWS/buscaConsumidor.php';
include_once './totem/funWS/buscaConsumidorCNPJ.php';
// include_once '../totem/funWS/saldo.php';
// $cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_cliente = $usuario;

$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

// echo($sqlCampos);

$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayFields);

$log_cadtoken = $qrCampos[LOG_CADTOKEN];
$cod_chaveco = $qrCampos[COD_CHAVECO];

// echo($cod_cliente);
//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO=10  
			  AND COD_EXCLUSA = 0 limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
		  WHERE COD_EMPRESA = $cod_empresa 
		  AND LOG_ESTATUS = 'S' 
		  ORDER BY 1 ASC LIMIT 1";

$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
$qrLista = mysqli_fetch_assoc($arrayUn);

$idlojaKey = $qrLista['COD_UNIVEND'];
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = $log_usuario . ';'
	. $des_senhaus . ';'
	. $idlojaKey . ';'
	. $idmaquinaKey . ';'
	. $cod_empresa . ';'
	. $codvendedorKey . ';'
	. $nomevendedorKey;

$arrayCampos = explode(";", $urltotem);

$urlWebservice = $arrayCampos;

$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

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

if ($k_num_cgcecpf != "") {
	$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
}

if ($k_dat_nascime != "") {
	$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
}

if ($k_des_emailus != "") {
	$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
}

$whereSql = trim(ltrim($whereSql, "OR"));

if ($cod_cliente == 0) {

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND ($whereSql)
		       ORDER BY 1 LIMIT 1";

	$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

	$arrayFields = mysqli_query($connAdm->connAdm(), $sqlCampos);

	$lastField = "";

	$qrCampos = mysqli_fetch_assoc($arrayFields);

	$cod_chaveco = $qrCampos[COD_CHAVECO];
} else {

	if (isset($usuario) && $usuario != "") {

		$chave = $usuario;

		switch ($qrCampos[COD_CHAVECO]) {

			case 2:
				$campo = "NUM_CARTAO";
				break;

			case 3:
				$campo = "NUM_CELULAR";
				break;

			case 4:
				$campo = "COD_EXTERNO";
				break;

			default:
				$campo = "NUM_CGCECPF";
				break;
		}

		$sqlCli = "SELECT * FROM CLIENTES 
			       WHERE COD_EMPRESA = $cod_empresa
			       AND $campo = '$chave'";
	}
}

$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cpf = fnLimpaDoc($qrCli[NUM_CGCECPF]);
$cod_cliente = fnLimpaCampoZero($qrCli[COD_CLIENTE]);
$celular = $qrCli[NUM_CELULAR];
$cartao = $qrCli[NUM_CARTAO];
$externo = $qrCli[NUM_CARTAO];
$log_termo = $qrCli[LOG_TERMO];
$des_token = $qrCli[DES_TOKEN];

if ($cpf != "") {
	$k_num_cgcecpf = $cpf;
}

switch ($qrCampos[COD_CHAVECO]) {

	case 2:
		// echo "cartao";
		$chave = $cartao;
		$buscaconsumidor = fnconsulta_V3($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
		break;

	case 3:
		// echo "celular";
		$chave = $celular;
		$buscaconsumidor = fnconsulta_V3($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
		break;

	case 4:
		// echo "externo";
		$chave = $externo;
		$buscaconsumidor = fnconsulta_V3($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
		break;

	default:

		if (strlen($k_num_cgcecpf) <= '11') {

			// echo "cpf";

			// echo '<pre>';

			$buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf, 'F'), $arrayCampos);

			// print_r($buscaconsumidor);

			// echo '</pre>';

		} else {

			// echo "cnpj";

			// echo 'else';

			$buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf, 'J'), $arrayCampos);
		}

		break;
}

// echo '<pre>';
// print_r($buscaconsumidor);
// echo '</pre>';

if ($buscaconsumidor['cpf'] != '00000000000') {

	$cpf = $buscaconsumidor['cpf'];
} else {
	$cpf = $k_num_cgcecpf;
	$buscaconsumidor['nome'] = "";
}

if ($buscaconsumidor['cartao'] != "") {
	$cartao = $buscaconsumidor['cartao'];
	$c10 = $buscaconsumidor['cartao'];
}

// busca info empresa
$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS, NUM_DECIMAIS_B, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

if ($qrEmp['TIP_RETORNO'] == 1) {
	$casasDec = 0;
} else {
	$casasDec = $qrEmp['NUM_DECIMAIS_B'];
}

$log_cadtoken = $qrEmp['LOG_CADTOKEN'];

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

$campoLogin = "";
$dadoLogin = "";

if ($k_num_cartao != "") {
	$buscaconsumidor['cartao'] = $k_num_cartao;
	$campoLogin = "KEY_NUM_CARTAO";
	$dadoLogin = $k_num_cartao;
} else {
	$k_num_cartao = $buscaconsumidor['cartao'];
}

if ($k_num_celular != "") {
	$buscaconsumidor['telcelular'] = $k_num_celular;
	$campoLogin = "KEY_NUM_CELULAR";
	$dadoLogin = $k_num_celular;
} else {
	$k_num_celular = $buscaconsumidor['telcelular'];
}

if ($k_num_cgcecpf != "") {
	$buscaconsumidor['cpf'] = $k_num_cgcecpf;
	$campoLogin = "KEY_NUM_CGCECPF";
	$dadoLogin = $k_num_cgcecpf;
} else {
	$k_num_cgcecpf = $buscaconsumidor['cpf'];
}

if ($k_dat_nascime != "") {
	$buscaconsumidor['datanascimento'] = $k_dat_nascime;
	$campoLogin = "KEY_DAT_NASCIME";
	$dadoLogin = $k_dat_nascime;
} else {
	$k_dat_nascime = $buscaconsumidor['datanascimento'];
}

if ($k_des_emailus != "") {
	$buscaconsumidor['email'] = $k_des_emailus;
	$campoLogin = "KEY_DES_EMAILUS";
	$dadoLogin = $k_des_emailus;
} else {
	$k_des_emailus = $buscaconsumidor['email'];
}

if ($buscaconsumidor['cpf'] == "00000000000") {
	$buscaconsumidor['cpf'] = "";
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if ($cod_cliente != 0) {

	$arrayNome = explode(" ", $qrBuscaCliente['NOM_CLIENTE']);
	$nome = $arrayNome[0];
	$dia_nascime = $qrBuscaCliente['DIA'];
	$mes_nascime = $qrBuscaCliente['MES'];
	$ano_nascime = $qrBuscaCliente['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $qrBuscaCliente['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery2);

	$today = date("Y-m-d");

	if (mysqli_num_rows($arrayQuery2) > 0) {

		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '6':

				$date = date("Y-m-d", strtotime($today . "-6 months"));

				break;

			default:

				$date = date("Y-m-d", strtotime($today . "-1 year"));

				break;

				if ($dat_atualiza >= $date && $dat_atualiza <= $today) {
					$mostraMsgCad = 'block';
				}
		}
	}

	$today = date("Y-m-d");
	$date = date("Y-m-d", strtotime($today . "+6 months"));

	// echo $today."<br/>";
	// echo $date;


	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '99' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery);

	if (mysqli_num_rows($arrayQuery) > 0) {

		$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

		$NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($qrCli['NOM_CLIENTE']))));
		$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
		$TEXTOENVIO = str_replace('<#SALDO>', fnValor($qrCli['CREDITO_DISPONIVEL'], $casasDec), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#NOMELOJA>',  $qrCli['NOM_FANTASI'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#ANIVERSARIO>', $qrCli['DAT_NASCIME'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#DATAEXPIRA>', fnDataShort($qrCli['DAT_EXPIRA']), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#EMAIL>', $qrCli['DES_EMAILUS'], $TEXTOENVIO);
		$msgsbtr = nl2br($TEXTOENVIO, true);
		$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
		$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '7':

				if ($dia_hoje == $dia_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			case '30':

				if ($mes_hoje == $mes_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			default:

				$firstDate = strtotime($ano_hoje . '-' . $mes_nascime . '-' . $dia_nascime);
				$secondDate = strtotime($ano_hoje . '-' . $mes_hoje . '-' . $dia_hoje);

				$result = date('oW', $firstDate) === date('oW', $secondDate) && date('Y', $firstDate) === date('Y', $secondDate);

				if ($result) {
					$mostraMsgAniv = 'block';
				}

				break;
		}
	}
}

if ($log_sombra != "S") {
	$log_sombra = "N";
}
if ($log_linha != "S") {
	$log_linha = "N";
}
if ($log_round != "S") {
	$log_round = "N";
}

if ($req_senha != "") {
	$arrReq = explode(",", @$req_senha);
	$infoMessage = "A senha deve conter:<br>";
	$reqMin = "false";
	$reqLetra = "false";
	$reqNum = "false";
	$reqEsp = "false";
	
} else {
	$reqMin = "6";
	$helpSenha = "Mínimo de 6 dígitos";
	$reqLetra = "false";
	$reqNum = "false";
	$reqEsp = "false";
}

if ($tip_senha == "2") {
	$classeSenha = "int";
} else {
	$classeSenha = "";
	if (in_array('1', $arrReq)) {
		$reqMin = "true";
		$infoMessage .= "<br>- Pelo menos " . $min_senha . " caracteres";
	}
	if (in_array('2', $arrReq)) {
		$reqLetra = "true";
		$infoMessage .= "<br>- 1 letra maíuscula";
	}
	if (in_array('3', $arrReq)) {
		$reqNum = "true";
		$infoMessage .= "<br>- 1 número";
	}
	if (in_array('4', $arrReq)) {
		$reqEsp = "true";
		$infoMessage .= "<br>- 1 caracter especial";
	}
}

$msgAlteraCampo = "";
// $tip_envio vem da header.php - query empresa
if ($tip_envio == 1) {
	$colunasValida = "#NUM_CELULAR";
	// $msgAlteraCampo = "Para alterar seu celular, entre em contato via whatsapp <a href='https://wa.me/5511941591303'>aqui</a>";
} else if ($tip_envio == 2) {
	$colunasValida = "#DES_EMAILUS";
	// $msgAlteraCampo = "Para alterar seu email, entre em contato via whatsapp <a href='https://wa.me/5511941591303'>aqui</a>";
} else if ($tip_envio == 3) {
	$colunasValida = "#NUM_CELULAR, #DES_EMAILUS, #NOM_CLIENTE, #DAT_NASCIME";
	// $msgAlteraCampo = "Para alterar este dado, entre em contato via whatsapp <a href='https://wa.me/5511941591303'>aqui</a>";
} else {
	$colunasValida = ".vazio";
	$msgAlteraCampo = "";
}

if($cod_empresa == 219){
	// $log_cadtoken = 'N';
}

?>

<link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/jquery-confirm.min.css">
<link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/chosen-bootstrap.css">

<style>
	label {
		font-weight: 500 !important;
	}

	.form-control {
		border-width: 1px !important;
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

	<?php
	if ($log_sombra == "S") {
	?>.form-control {
		-webkit-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		-moz-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		width: 100%;
		border-radius: 5px;
	}

	<?php
	}

	if ($log_linha == "S") {
	?>.form-control {
		border-top: none !important;
		border-left: none !important;
		border-right: none !important;
	}

	<?php
	}

	if ($log_round == "S") {
	?>.form-control {
		border-radius: 30px !important;
	}

	<?php
	} ?><?php if ($cod_empresa == 19) { ?>
	/*#blocoNascimento{
	    		display: none;
	    	}

	    	#blocoSexo{
	    		display: none;
	    	}*/

	<?php } ?>
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="img/loading2.gif"><br /> Aguarde. Cadastro processando... Isto pode demorar alguns segundos ;-)</div>
</div>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="concluiCadastro_V2.do?key=<?=$_GET[key]?>&idU=<?=$_GET[idU]?>&t=<?=$rand?>">

	<div class="container">

		<div class="row">

			<div class="push20"></div>
			<div class="push10"></div>

			<?php

			$mostraSenha = 1;
			$isApp = true;
			include '../totem/includeMaisCash.php';

			?>


		</div>


	</div> <!-- /container -->

</form>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog">
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


<!-- <script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script> -->

<?php include 'footer.php'; ?>
<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
<link rel="stylesheet" href="libs/pwdRequirements/css/jquery.passwordRequirements.css" />
<script src="libs/pwdRequirements/js/jquery.passwordRequirements.js"></script>

<script type="text/javascript">

	$(function(){

		//choosen
		$(".chosen-select-deselect").chosen({allow_single_deselect:true});

		<?php 
			if($cod_cliente != 0){
		?>
			$("<?=$colunasValida?>").attr("readonly",true);
		<?php 
			}
		?>
	
		// $('input, textarea').placeholder();
		<?php 
			if($cod_empresa == 19){
				$datNasc = $buscaconsumidor['datanascimento'];
				$sexo = $buscaconsumidor['sexo'];
				if(trim($datNasc) == ""){
					$datNasc = "01/01/2000";
				}
				if(trim($sexo) == ""){
					$sexo = "3";
				}
		?>
				// console.log("<?=$datNasc?>");
		    	// $("#DAT_NASCIME").val("<?=$datNasc?>").removeAttr("required");
		    	// $("#COD_SEXOPES").removeAttr("required");
		    	$("#COD_SEXOPES").val("<?=$sexo?>").trigger("chosen:updated");
		    	$("#formulario").validator('destroy').validator();
	    <?php 
			} 
		?>

		$(window).on('load', function() {
		 	// $('#formulario').validator('destroy').validator().validator('update');
		});	

		$('.data').mask('00/00/0000');

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};			
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);
		
		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#CAD").click(function(e){
			$("#blocker").show();
			$("#formulario").validator('validate');
			if($('#formulario').has('.has-error').length > 0){ 
				e.preventDefault();
				$("#blocker").hide();
			}
		});

		$(".pr-password").passwordRequirements({
				numCharacters: "<?=$min_senha?>",
			  useLowercase:<?=$reqLetra?>,
			  useNumbers:<?=$reqNum?>,
			  useSpecial:<?=$reqEsp?>,
			  infoMessage: "<?=$infoMessage?>"

		});

	});

	if($('.cpfcnpj').val() != undefined){
		mascaraCpfCnpj($('.cpfcnpj'));
	}
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'],
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}

	function toggleAuth(obj){
		$("#relatorioPreview").fadeIn("fast");
		$(obj).fadeOut(1);
	}

	function ajxDescadastra(cod_cliente){

		$.alert({
          title: "Confirmação",
          content: "Deseja excluir seus dados de forma <b>definitiva</b>?",
          type: 'red',
          buttons: {
            "EXCLUIR": {
               btnClass: 'btn-danger',
               action: function(){
                
                    parent.$.alert({
                      title: "Aviso!",
                      content: "<b>Todos</b> os dados serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
                      type: 'red',
                      buttons: {
                        "EXCLUIR PERMANENTEMENTE": {
                           btnClass: 'btn-danger',
                           action: function(){
                            	$.ajax({
									type: "POST",
									url: "../ticket/ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&t=<?=$rand?>",
									data: { COD_CLIENTE: cod_cliente },
									beforeSend:function(){
										$("#blocker").show();
									},
									success:function(data){	
										window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
									},
									error:function(){
									    console.log('Erro');
									}
								});
                           }
                        },
                        "CANCELAR": {
                          btnClass: 'btn-default',
                           action: function(){
                            
                           }
                        }
                      },
                      backgroundDismiss: function(){
                          return 'CANCELAR';
                      }
                    });

               }
            },
            "CANCELAR": {
              btnClass: 'btn-default',
               action: function(){
                
               }
            }
          },
          backgroundDismiss: function(){
              return 'CANCELAR';
          }
        });

	}

	<?php if($cod_empresa == 19){ ?>

		function ajxDescadastraDq(cod_cliente){

			$.alert({
	          title: "Aviso!",
	          content: "No App Rede Duque, oferecemos os melhores preços em todas as regiões. Deseja continuar com sua conta, ou prefere prosseguir com o encerramento?",
	          type: 'red',
	          buttons: {
	          	"CONTINUAR COM A MINHA CONTA": {
	              btnClass: 'btn-success',
	               action: function(){
	                
	               }
	            },
	            "PROSSEGUIR ENCERRAMENTO": {
	               btnClass: 'btn-default',
	               action: function(){
	                	$.ajax({
						type: "POST",
						url: "../ticket/ajxCadastro_V2.do?opcao=encerrarDq&id=<?php echo fnEncode($cod_empresa); ?>&t=<?=$rand?>",
						data: { COD_CLIENTE: cod_cliente },
						beforeSend:function(){
							$("#blocker").show();
						},
						success:function(data){
							$("#blocker").hide();
							console.log(data);
							$.alert({
					          title: "Sucesso",
					          content: "Sua solicitação de exclusão de conta foi confirmada. Em <b>3 dias</b>, sua conta <b>não existirá mais</b>, e seus dados serão <b>permanentemente excluídos</b>.<div class='push10'></div> Usaremos este seu email <b><?=fnMascaraCampo($buscaconsumidor['email'])?></b> para prosseguir com a sua solicitação. Se você <b>não tiver mais acesso</b> a este email, <b>entre em contato</b> com a nossa central pelo WhatsApp:<div class='push'></div><b>(11) 3087-9697</b>",
					          type: 'green'
					      });	
						},
						error:function(){
						    console.log('Erro');
						}
					});
	               }
	            }
	          },
	          backgroundDismiss: function(){
	              return 'CANCELAR';
	          }
	        });

		}

	<?php } ?>

	function ajxToken(){

		var nom_cliente = $("#NOM_CLIENTE").val(),
			num_celular = $("#NUM_CELULAR").val(),
			des_emailus = $("#DES_EMAILUS").val(),
			cad_num_celular = $("#CAD_NUM_CELULAR").val(),
			key_num_celular = $("#KEY_NUM_CELULAR").val(),
			num_cgcecpf = $("#NUM_CGCECPF").val(),
			cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
			key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val();

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "../ticket/ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN&logS=<?=fnEncode($mostraSenha)?>&t=<?=$rand?>",
				data: { 
						NOM_CLIENTE: nom_cliente, 
						NUM_CELULAR: num_celular, 
						DES_EMAILUS: des_emailus, 
						CAD_NUM_CELULAR: cad_num_celular, 
						KEY_NUM_CELULAR: key_num_celular, 
						NUM_CGCECPF: num_cgcecpf, 
						CAD_NUM_CGCECPF: cad_num_cgcecpf, 
						KEY_NUM_CGCECPF: key_num_cgcecpf, 
						ISAPP: true, 
						LOG_LGPD: "<?=fnEncode($log_lgpd)?>"
				},
				beforeSend:function(){
					// $("#blocker").show();
				},
				success:function(data){	
					$("#relatorioToken").html(data);
					// $("#formulario").validator('destroy').validator();
					// $("#formulario").validator("validate");	
					// window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
				},
				error:function(){
				    console.log('Erro');
				}
			});

		}

	}

	function ajxTokenAlt(){

		var nom_cliente = $("#NOM_CLIENTE").val(),
			num_celular = $("#NUM_CELULAR").val(),
			cad_num_celular = $("#CAD_NUM_CELULAR").val(),
			key_num_celular = $("#KEY_NUM_CELULAR").val(),
			num_cgcecpf = $("#NUM_CGCECPF").val(),
			cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
			key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val();

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "../ticket/ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT&t=<?=$rand?>",
				data: { 
						NOM_CLIENTE: nom_cliente, 
						NUM_CELULAR: num_celular, 
						CAD_NUM_CELULAR: cad_num_celular, 
						KEY_NUM_CELULAR: key_num_celular, 
						NUM_CGCECPF: num_cgcecpf, 
						CAD_NUM_CGCECPF: cad_num_cgcecpf, 
						KEY_NUM_CGCECPF: key_num_cgcecpf, 
						LOG_LGPD: "<?=fnEncode($log_lgpd)?>"
				},
				beforeSend:function(){
					// $("#blocker").show();
				},
				success:function(data){	
					$("#relatorioToken").html(data);
					// $("#formulario").validator('destroy').validator();
					// window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
				},
				error:function(){
				    console.log('Erro');
				}
			});

		}

	}

	function ajxValidaTkn(){

		var num_celular = $("#NUM_CELULAR").val(),
			nom_cliente = $("#NOM_CLIENTE").val(),
			des_token = $("#DES_TOKEN").val(),
			num_cgcecpf = $("#NUM_CGCECPF").val();

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "../ticket/ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD&t=<?=$rand?>",
				data: { NOM_CLIENTE: nom_cliente, NUM_CELULAR: num_celular, NUM_CGCECPF: num_cgcecpf, DES_TOKEN: des_token },
				beforeSend:function(){
					$("#blocker").show();
				},
				success:function(data){	

					$("#blocker").hide();

					if(data.trim() == "validado"){

						$("#KEY_DES_TOKEN").val($("#DES_TOKEN").val());

						$("#camposToken").fadeOut('fast',function(){
							$("#btnCad").fadeIn('fast');
							// $("#formulario").validator("validate");	
						});	

						// $("#formulario").validator('destroy').validator();					

					}else{

						$("#erroTkn").fadeIn(1);

					}	

				},
				error:function(){
				    console.log('Erro');
				}
			});

		}

	}

</script>