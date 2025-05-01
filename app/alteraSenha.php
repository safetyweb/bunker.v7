<?php 

include_once 'header.php'; 
$tituloPagina = "Alterar Senha";
include_once "navegacao.php"; 

$cod_cliente = fnLimpaCampo(fnDecode($_POST['COD_CLIENTE']));

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

?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="sucessoSenha.do?key=<?=$_GET[key]?>&t=<?=$rand?>">
	
    <div class="container">

		<div class="row">
			
			<div class="col-md-6 col-xs-12" id="caixaImg">
				<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
			</div>
			<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

				<div class="push20"></div>
				<div class="push50"></div>
				
				<div class="col-md-12">

					<h3>
						Dados validados. Por favor, digite e confirme a nova senha de acesso:
					</h3>

				</div>

				<div class="col-md-12 col-xs-12">
					<div class="form-group">
						<label style="font-size: 11px;">Somente números</label>
						<input type="password" placeholder="Nova Senha" style="font-size: 36px;" class="form-control input-hg input-lg text-center int" name="DES_SENHAUS" id="DES_SENHAUS" maxlength="6" autocomplete="new-password" required>
						<span toggle="#DES_SENHAUS" class="fa fa-fw fa-eye field-icon toggle-password"></span>
						<div class="help-block with-errors">Máximo de 6 dígitos</div>
					</div>
				</div>

				<div class="col-md-12 col-xs-12">
					<div class="form-group">
						<label style="font-size: 11px;">Somente números</label>
						<input type="password" placeholder="Confirmar Senha" style="font-size: 36px;" class="form-control input-hg input-lg text-center int" name="DES_SENHAUS_CONF" id="DES_SENHAUS_CONF" maxlength="6" data-match="#DES_SENHAUS" data-match-error="Senhas diferentes" required>
						<div class="help-block with-errors"></div>
					</div>
				</div>

				<div class="col-xs-12">
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Alterar Senha</button>
				</div>

				
				<div class="push50"></div>

			</div>

			<input type="hidden" name="opcao" id="opcao" value="">
			<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=fnEncode($cod_cliente)?>">
			<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=fnEncode($cod_empresa)?>">
			<input type="hidden" name="fkey" id="fkey" value="<?=$fkey?>">
			<input type="hidden" name="vkey" id="vkey" value="<?=$vkey?>">
			<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
			<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

		</div>
        

    </div> <!-- /container -->

</form>

<?php include 'footer.php'; ?>

<script type="text/javascript">

$(function(){
	
	//chosen
	$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
	$('#formulario').validator();

	$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });

});

</script>