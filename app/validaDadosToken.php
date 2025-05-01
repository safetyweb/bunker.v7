<?php 

include_once 'header.php'; 
$tituloPagina = "Validação";
include_once "navegacao.php"; 

$tipo = fnLimpaCampo($_GET["tp"]);
$placa = $_GET["idp"];
$unidadePref = $_GET["uni"];
// $usuEncrypt = fnEncode($_SESSION["usuario"]);



// if($cod_cliente == 0){

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND NUM_CARTAO = '$usuario'
		       ORDER BY 1 LIMIT 1";

	$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

	$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);

	$lastField = "";

	$qrCampos = mysqli_fetch_assoc($arrayFields);

	$cod_chaveco = $qrCampos['COD_CHAVECO'];

// }else{

// 	$sqlCli = "SELECT * FROM CLIENTES 
// 		       WHERE COD_EMPRESA = $cod_empresa
// 		       AND COD_CLIENTE = $cod_cliente";

// 	$cod_chaveco = 0;

// }

// echo $sqlCli;

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cpf = fnLimpaDoc($qrCli['NUM_CGCECPF']);
$cod_cliente = fnLimpaCampoZero($qrCli['COD_CLIENTE']);
$celular = $qrCli['NUM_CELULAR'];
$cartao = $qrCli['NUM_CARTAO'];
$externo = $qrCli['NUM_CARTAO'];
$log_termo = $qrCli['LOG_TERMO'];
$des_token = $qrCli['DES_TOKEN'];

// $urlAlteraSenha = "alteraSenha.do?key=".$_GET["key"];

if($tipo == "resg"){
	$urlToken = "geraTokenResgate.do?key=".$_GET['key']."&idp=".$placa."&uni=".$unidadePref."&idU=".$_GET['idU']."&t=".$rand;
}else{
	$urlToken = "geratoken.do?key=".$_GET['key']."&idp=".$placa."&uni=".$unidadePref."&idU=".$_GET['idU']."&t=".$rand;
}

if($tip_envio == 1){
	$colunasValida = "NUM_CELULAR";
}else if($tip_envio == 2){
	$colunasValida = "DES_EMAILUS";
}else{
	$colunasValida = "NUM_CELULAR, DES_EMAILUS";
}

?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?=$urlToken?>">
	
    <div class="container">

		<div class="row">
			
			<div class="col-md-6 col-xs-12" id="caixaImg">
				<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
			</div>
			<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

				<div class="push20"></div>
				<div class="push50"></div>

				<?php if($cod_cliente != 0){ ?>
				
					<div class="col-md-12">
						<h3>
							Por favor, confirme os 4 últimos dígitos do seu número de celular para prosseguir:
						</h3>
					</div>


					<?php
						$sqlCli = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
						// echo "$sqlCli";
						$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCli));

						$num_celular = $qrCli['NUM_CELULAR'];
						$dado_compara = substr($num_celular, -4);
					?>

					<div class="col-xs-12">
						<label for="val_celular" class="f18">&nbsp;&nbsp;<?=fnMascaraCampo($num_celular)?> (<b>celular</b>)</label>
					 	<div class="push10"></div>
					</div>

					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<input type="text" placeholder="Últimos 4 dígitos" style="font-size: 36px;" class="form-control input-hg input-lg text-center" name="DADO_CONFIRM" id="DADO_CONFIRM" required>
							<input type="hidden" name="DADO_COMPARA" id="DADO_COMPARA" value="<?=base64_encode($dado_compara)?>">
							<div class="help-block with-errors"></div>
						</div>
					</div>

					<div class="col-xs-12">
						<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Validar</button>
					</div>


					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=fnEncode($cod_cliente)?>">
					<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=fnEncode($cod_empresa)?>">
					<input type="hidden" name="fkey" id="fkey" value="<?=$fkey?>">
					<input type="hidden" name="vkey" id="vkey" value="<?=$vkey?>">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

				<?php }else{ ?>

					<div class="text-center">
						
						<p>Cadastro não encontado.</p>
						<a href="app.do?key=<?=$_GET['key']?>&idU=<?=$_GET['idU']?>&t=<?=$rand?>" class="btn btn-info btn-block">Já tenho cadastro. Fazer login</a>
						<div class="push5"></div>
						<div class="text-muted f12">OU</div>
						<div class="push5"></div>
						<a href="consulta_V2.do?key=<?=$_GET['key']?>&t=<?=$rand?>" class="btn btn-default btn-block" style="margin-top: 0;">Cadastrar-se</a>					
					</div>

				<?php } ?>

				<div class="push50"></div>

			</div>

		</div>
        

    </div> <!-- /container -->

</form>

<?php include 'footer.php'; ?>

<link href="libs/jquery-confirm.min.css" rel="stylesheet"/>
<script src="libs/jquery-confirm.min.js"></script>

<script type="text/javascript">

	$(function(){
	
		// $('input, textarea').placeholder();	

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

		$(".campo1,.campo2,.campo3,.campo4").keydown(function(){

			var campo1 = $(".campo1").val(),
				campo2 = $(".campo2").val(),
				campo3 = $(".campo3").val(),
				campo4 = $(".campo4").val();

				if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

					$(".campo1,.campo2,.campo3,.campo4").prop("required", false);
					$(".control-label").removeClass("required");

				}else{

					$(".campo1,.campo2,.campo3,.campo4").prop("required", true);
					$(".control-label").addClass("required");

				}

			// $('#formulario').validator();

		});

		$("#CAD").click(function(e){

			e.preventDefault();

			let dado_confirm = btoa($("#DADO_CONFIRM").val().trim()),
				dado_compara = $("#DADO_COMPARA").val().trim();

			if($("#DADO_CONFIRM").val().trim().length == 4){

				if(dado_confirm == dado_compara){
					$("#formulario").submit();
				}else{
					$.alert({
						title: 'Atenção!',
						color: 'danger',
						content: 'Os dados informados não conferem com os dados de cadastro!',
					});
				}

				// $.ajax({
				// 	method: 'POST',
				// 	url: 'ajxValidaCelular.do',
				// 	data: {DADO_COMPARA: "<?=fnEncode($dado_compara)?>", DADO_CONFIRM:dado_confirm},
				// 	success:function(data){
				// 		console.log(data);
				// 		if(data == 0){
				// 			$.alert({
				// 				title: 'Atenção!',
				// 				color: 'danger',
				// 				content: 'Os dados informados não conferem com os dados de cadastro!',
				// 			});
				// 		}else{
				// 			$("#formulario").submit();
				// 		}
				// 	},
				// 	error:function(){

				// 	}
				// });

			}else{
				$.alert({
					title: 'Atenção!',
					color: 'danger',
					content: 'Dados insuficientes.',
				});
			}

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

	// $('.validaCPF').click(function(e){

	// 	var campo1 = $(".campo1").val(),
	// 		campo2 = $(".campo2").val(),
	// 		campo3 = $(".campo3").val(),
	// 		campo4 = $(".campo4").val();

	// 		if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

	// 			if(campo1 != ""){

	// 				if(!valida_cpf_cnpj($('.cpfcnpj').val())){

	// 					e.preventDefault();
	// 					parent.$.alert({
	// 						title: 'Atenção!',
	// 						content: 'CPF/CNPJ digitado é inválido!',
	// 					});	

	// 				}

	// 			}

	// 		}else{

	// 			e.preventDefault();
	// 			parent.$.alert({
	// 				title: 'Atenção!',
	// 				content: 'Pelo menos um dado deve ser informado!',
	// 			});

	// 		}

	// });
</script>