<?php
include_once 'header.php';
$tituloPagina = "Descadastro";
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
?>

<style>
	#COD_UNIVEND_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_UNIVEND_chosen > a {
		height: 56px;
		padding: 12px 16px;		
	}

	#COD_SEXOPES_chosen {
		font-size: 22px;
		margin-bottom: 20px;
		margin-top: 20px;
	}

	#COD_SEXOPES_chosen > a {
		height: 56px;
		padding: 12px 16px;		
	}

	.chosen-single{
		background-color: #ecf0f1!important;
		border: none!important;
	}

	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	.f15{
		font-size: 15px;
	}

	.mb-5{
		margin-bottom: 5px;
	}
</style>

<script src="libs/chosen.jquery.min.js"></script>
<link href="libs/chosen-bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/jquery-confirm.min.css">

<form data-toggle="validator" role="form2" method="POST" id="formulario">
		
	<div class="container">

		<div class="push50"></div>

        <div class="row" style="padding-left: 15px; padding-right: 15px;">
			<div class="col-xs-12" style="background-color: #F39C12; color: #fff; border-radius: 15px; padding-top: 15px; padding-bottom: 5px;">
				<p class="f14">Ao prosseguir com a exclusão, em <b>3 dias</b>, sua conta <b>não existirá mais</b>, e seus dados serão <b>permanentemente excluídos</b>.<div class='push10'></div> Usaremos este seu email <b><?=fnMascaraCampo($buscaconsumidor['email'])?></b> para prosseguir com a sua solicitação. Se você <b>não tiver mais acesso</b> a este email, <b>entre em contato</b> com a nossa central pelo WhatsApp:<div class='push'></div><b>(11) 3087-9697</p>
			</div>
			<div class="push20"></div>
		</div>

		<div class="row">

			<div class="col-xs-12">	
			
				<label>Nome</label>
				<input type="text" id="NOM_CLIENTE" name="NOM_CLIENTE" value="<?=$buscaconsumidor['nome']?>" class="form-control input-hg" placeholder="Nome" readonly/>
				<div class="errorNome" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">

				<label>Email</label>
				<input type="text" id="DES_EMAILUS" name="DES_EMAILUS" value="<?=$buscaconsumidor['email']?>" class="form-control input-hg mb-5" placeholder="e-Mail" readonly/>
				<div class="errorEmail" style="color: red; font-size: 14px; display: none; margin-top: -5px; margin-bottom: 10px;">Campo obrigatório</div>

			</div>

			<div class="col-xs-12">

				<label>Celular</label>
				<input type="text" id="NUM_CELULAR" name="NUM_CELULAR" value="<?=$buscaconsumidor['telcelular']?>" class="form-control input-hg mb-5 text-center sp_celphones" placeholder="Tel. Celular" readonly/>
				<!-- <div class="errorCeluar" style="color: red; font-size: 14px; display: none; margin-top: 0px; margin-bottom: 10px;">Campo obrigatório</div> -->

			</div>

            <div class="push20"></div>

            <div class="col-xs-12">
                <a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastraDq("<?= fnEncode($cod_cliente) ?>")' class="btn btn-danger btn-hg btn-block">Excluir Cadastro</a>
            </div>

		</div>	
		
		<div class="push50"></div>	
	</div><!-- /container -->
		
	<input type="hidden" name="KEY" id="KEY" value="<?=fnEncode($_SESSION["EMPRESA_COD"])?>">
	<input type="hidden" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?=$num_cgcecpf?>">
	<!-- <input type="hidden" name="opcao" id="opcao" value=""> -->
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

</form>


<?php include 'footer.php'; ?>

<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>

<script>

	$(document).ready(function(){
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#COD_SEXOPES").chosen();		
		$("#COD_UNIVEND").chosen();
		$('.cpf').mask('000.000.000-00', {reverse: true});
		$('.data').mask('00/00/0000');

		var SPMaskBehavior = function (val) {
		  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
		  onKeyPress: function(val, e, field, options) {
			  field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};

		$('.sp_celphones').on('input propertychange paste', function (e) {
		    var reg = /^0+/gi;
		    if (this.value.match(reg)) {
		        this.value = this.value.replace(reg, '');
		    }
		});

		$("#DES_EMAILUS").keypress(function(e) {
	      if(e.which === 32) 
	        return false;
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
			cod_cliente = "<?=$cod_cliente?>",
			log_termos = "<?=$log_termos?>",
			aceito = "S";

			if(cod_cliente != 0){
				opcao = "atualizar";
			}else{
				opcao = "cadastrar";
			}

			// alert(opcao);

			if(log_termos == 'S' && opcao == "cadastrar"){
				if(!$('#LOG_TERMOS').prop('checked')){
					aceito = "N";
					$('.errorTermos').show();
				}else{
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

			if(senha == con_senha && nome != "" && nasc != "" && email != "" && sexo != "" && univend != "" && senha != "" && cpf != ""){

				if(aceito == "S"){

					// alert(opcao);

					$.ajax({
						type: "POST",
						url: "ajxValidaCadastro.do?id=<?=fnEncode($cod_empresa)?>&opcao="+opcao,
						data: $("#formulario").serialize(),
						beforeSend:function(){
							$('#loadStep').html('<div class="loading"></div>');
						},				
						success:function(data){
							console.log(data);

							if(data.trim() == "1"){
								alert('Cadastro realizado com sucesso!');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else if(data.trim() == "3"){
								alert('Cadastro atualizado com sucesso!');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else if(data.trim() == "2"){
								alert('Cliente já é cadastrado.');
								window.location.replace("app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
							}
							else{
								alert("Erro ao cadastrar. Contate o suporte.");
								console.log(data);
							}
						},
						error: function(){
							$('#loadStep').html('Oops... Ocorreu um erro. Contate o suporte.');
						}
					});
				}

			}

			if(senha != con_senha || senha == ""){
				$('.errorLogin').show();
			}else{
				$('.errorLogin').hide();
			}

			if(cpf == ""){
				$('.errorCpf').show();
			}else{
				$('.errorCpf').hide();
			}

			if(nome == ""){
				$('.errorNome').show();
			}else{
				$('.errorNome').hide();
			}

			if(nasc == ""){
				$('.errorNascimen').show();
			}else{
				$('.errorNascimen').hide();
			}

			if(email == ""){
				$('.errorEmail').show();
			}else{
				$('.errorEmail').hide();
			}

			if(univend == ""){
				$('.errorUnivend').show();
			}else{
				$('.errorUnivend').hide();
			}

			if(sexo == ""){
				$('.errorSexo').show();
			}else{
				$('.errorSexo').hide();
			}
				
		});

	});

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

	// $('#formulario').validator().on('submit', function (e) {
	//   if (!e.isDefaultPrevented()) {
	//   	e.preventDefault();
	//     $.ajax({
	// 		method: 'POST',
	// 		url: 'ajxValidaCadastro.php',
	// 		data: $('#formulario').serialize(),
	// 		success:function(data){
	// 			console.log(data);

	// 			if(data.trim() == "Registro inserido!"){
	// 				alert('Cadastro realizado com sucesso!');
	// 				window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
	// 			}
	// 			else if(data.trim() == "Cadastro Atualizado !"){
	// 				alert('Cadastro atualizado com sucesso!');
	// 				window.location.replace("http://adm.bunker.mk/app/app.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>");
	// 			}
	// 			else{
	// 				alert(data);
	// 			}
	// 		},
	// 		error:function(){
				
	// 		}
	// 	});

	//   }
	// });

</script>


	