<?php 

include 'header.php'; 
$tituloPagina = "Validação";
include "navegacao.php"; 

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

// if(mysqli_num_rows($arrayControle) == 0){

// 	$sqlIns = "INSERT INTO CONTROLE_TERMO(
// 						      COD_EMPRESA,
// 						      TXT_ACEITE,
// 							  TXT_COMUNICA,
// 							  LOG_SEPARA,
// 							  COD_USUCADA
// 						   ) VALUES(
// 						   	  $cod_empresa,
// 						   	  'Estou ciente e de acordo com os termos, e desejo me cadastrar:',
// 						   	  'Comunicação',
// 						   	  'N',
// 						   	  $_SESSION[SYS_COD_USUARIO]
// 						   )";

// 	mysqli_query(connTemp($cod_empresa,''),$sqlIns);

// 	$sqlContole = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// 	$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

// }

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

$whereSql = "";

if($k_num_cartao != ""){
	$whereSql .= "OR NUM_CARTAO = '$k_num_cartao' ";
}

if($k_num_celular != ""){
	$whereSql .= "OR NUM_CELULAR = '$k_num_celular' ";
}

if($k_cod_externo != ""){
	$whereSql .= "OR COD_EXTERNO = '$k_cod_externo' ";
}

if($k_num_cgcecpf != ""){
	$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
}

if($k_dat_nascime != ""){
	$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
}

if($k_des_emailus != ""){
	$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
}

$whereSql = trim(ltrim($whereSql, "OR"));

// if($cod_cliente == 0){

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND ($whereSql)
		       ORDER BY 1 LIMIT 1";

	$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

	$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);

	$lastField = "";

	$qrCampos = mysqli_fetch_assoc($arrayFields);

	$cod_chaveco = $qrCampos[COD_CHAVECO];

// }else{

// 	$sqlCli = "SELECT * FROM CLIENTES 
// 		       WHERE COD_EMPRESA = $cod_empresa
// 		       AND COD_CLIENTE = $cod_cliente";

// 	$cod_chaveco = 0;

// }

// echo $sqlCli;

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cpf = fnLimpaDoc($qrCli[NUM_CGCECPF]);
$cod_cliente = fnLimpaCampoZero($qrCli[COD_CLIENTE]);
$celular = $qrCli[NUM_CELULAR];
$cartao = $qrCli[NUM_CARTAO];
$externo = $qrCli[NUM_CARTAO];
$log_termo = $qrCli[LOG_TERMO];
$des_token = $qrCli[DES_TOKEN];

$urlAlteraSenha = "alteraSenha.do?key=".$_GET[key]."&t=".$rand;

if($cod_empresa == 19){
	$urlAlteraSenha = "alteraSenhaDuque.do?key=".$_GET[key]."&t=".$rand;
}

?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?=$urlAlteraSenha?>">
	
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
							Por favor, selecione e confirme um dos dados abaixo para prosseguir:
						</h3>
					

						<ul style="padding-left: 0;">


							 <?php

							 	$sqlCli = "SELECT NUM_CELULAR, DES_EMAILUS, DAT_NASCIME FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
							 	// echo "$sqlCli";
							 	$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCli));

							 	$num_celular = $qrCli[NUM_CELULAR];
								$des_emailus = $qrCli[DES_EMAILUS];
								$dat_nascime = $qrCli[DAT_NASCIME];

							 	if($num_celular != ""){
							 ?>
							 	<li style="list-style: none;">
							 		<input type="radio" name="TIPO_DADO" id="val_celular" value="NUM_CELULAR" style="height: 18px; width: 18px;" onclick='$("#DADO_CONFIRM").attr("placeholder", "Celular").val("").removeClass("data").unmask().removeAttr("maxlength");$("#blocoValida").fadeIn("fast");'>
							 		<label for="val_celular">&nbsp;&nbsp;<?=fnMascaraCampo($num_celular)?> (<b>celular</b>)</label>
							 	</li>
							 <?php 
							 	}

							 	if($des_emailus != ""){
							 ?>
							 	<li style="list-style: none;">
							 		<input type="radio" name="TIPO_DADO" id="val_email" value="DES_EMAILUS" style="height: 18px; width: 18px;" onclick='$("#DADO_CONFIRM").attr("placeholder", "Email").val("").removeClass("data").unmask().removeAttr("maxlength");$("#blocoValida").fadeIn("fast");'>
							 		<label for="val_email">&nbsp;&nbsp;<?=fnMascaraCampo($des_emailus)?> (<b>email</b>)</label>
							 	</li>
							 <?php 
							 	}

							 	if($dat_nascime != ""){
							 ?>
							 	<li style="list-style: none;">
							 		<input type="radio" name="TIPO_DADO" id="val_data" value="DAT_NASCIME" style="height: 18px; width: 18px;" onclick='$("#DADO_CONFIRM").attr("placeholder", "Dt. Nascimento").val("").addClass("data");$("#blocoValida").fadeIn("fast");'>
							 		<label for="val_data">&nbsp;&nbsp;<?=fnMascaraCampo($dat_nascime)?> (<b>dt. nascimento</b>)</label>
							 	</li>
							 <?php 
							 	}

							 ?>

						</ul>

					</div>

					<div id="blocoValida" style="display: none;">

						<div class="col-md-12 col-xs-12">
							<div class="form-group">
								<input type="text" placeholder="Celular" style="font-size: 36px;" class="form-control input-hg input-lg text-center" name="DADO_CONFIRM" id="DADO_CONFIRM" required>
								<div class="help-block with-errors"></div>
							</div>
						</div>

						<div class="col-xs-12">
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Validar</button>
						</div>

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
						<a href="app.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-info btn-block">Já tenho cadastro. Fazer login</a>
						<div class="push5"></div>
						<div class="text-muted f12">OU</div>
						<div class="push5"></div>
						<a href="consulta_V2.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-default btn-block" style="margin-top: 0;">Cadastrar-se</a>					
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

			// let tipo_dado = $('input[name="TIPO_DADO"]:checked').val(),
			// 	dado_confirm = $("#DADO_CONFIRM").val().trim(),
			// 	num_celular = "<?=$num_celular?>",
			// 	des_emailus = "<?=$des_emailus?>",
			// 	dat_nascime = "<?=$dat_nascime?>",
			// 	valida = 0;

			// 	if(tipo_dado == "num_celular"){
			// 		if(num_celular == dado_confirm){
			// 			valida = 1;
			// 		}
			// 	}else if(tipo_dado == "des_emailus"){
			// 		if(des_emailus == dado_confirm){
			// 			valida = 1;
			// 		}
			// 	}else if(tipo_dado == "dat_nascime"){
			// 		if(dat_nascime == dado_confirm){
			// 			valida = 1;
			// 		}
			// 	}

			$.ajax({
				method: 'POST',
				url: '../ticket/ajxValidaDados.do?id=<?=fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>',
				data: $("#formulario").serialize(),
				success:function(data){
					if(data == '0'){
						$.alert({
							title: 'Atenção!',
							color: 'danger',
							content: 'Os dados informados não conferem com os dados de cadastro!',
						});
					}else{
						$("#formulario").submit();
					}
				},
				error:function(){

				}
			});

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