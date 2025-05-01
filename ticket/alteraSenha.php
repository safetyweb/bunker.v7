<?php

include "../_system/_functionsMain.php";
$cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
$cod_cliente = fnLimpaCampo(fnDecode($_POST['COD_CLIENTE']));
//busaca clientes por cpf

//CAMPO DE LOGIN
$fkey = $_POST['fkey'];
// VALOR DE LOGIN
$vkey = $_POST['vkey'];

//habilitando o cors
header("Access-Control-Allow-Origin: *");


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

// fnEscreve($sql);

?>

<link href="css/main.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/chosen-bootstrap.css" rel="stylesheet" />
<script src="js/jquery.min.js"></script>
<script src="js/chosen.jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.mask.min.js"></script>

<style>
	
	.input-lg {
		font-size: 28px;
		line-height: 1.5;
	}
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  padding: 0;
	}

	/* espa?adores */
	.push {clear:both;} 
	.push1 {height: 1px; clear:both;} 
	.push5 {height: 5px; clear:both;} 
	.push10 {height: 10px; clear:both;} 
	.push13 {height: 13px; clear:both;} 
	.push15 {height: 15px; clear:both;} 
	.push20 {height: 20px; clear:both;} 
	.push25 {height: 25px; clear:both;} 
	.push30 {height: 30px; clear:both;} 
	.push50 {height: 50px; clear:both;} 
	.push100 {height: 100px; clear:both;}
	.top20 {margin-top: 20px;} 
	.bottom20 {margin-bottom: 20px;} 
	.top30 {margin-top: 30px;} 
	.bottom30 {margin-bottom: 30px;} 
	.espacer15 {padding: 0 0 0 15px;} 
	.borda {border:1px solid #000;}
	.leituraOff {background-color: #F2F3F4 !important;}
	/*.leituraOff {background-color: #e5e7e9 !important;}*/

	.navbar img{
		width: 360px;
		margin-top: -15px;
	}
	
	.logo-center {
		float: none;
	}
	
	.logo-center-page {
		float: none;
		width: 160px;
	}	
	
	.logo-left {
		float: left;
	}

	.logo-right {
		float: right;
	}	
	
	<?php if ($cor_backbar == "") { ?>
	.navbar {
	   background-color: transparent;
	   background: transparent;
	   border-color: transparent;
	}
	<?php } else { ?>
	.navbar {
	   background-color: #<?php echo $cor_backbar; ?>;
	   background: #<?php echo $cor_backbar; ?>;
	   border-color: #<?php echo $cor_backbar; ?>;
	}
	<?php } ?>
	
	/*-- bloco saldos --*/
	
	.blkSaldo {
		margin-top: 1.5em;
	}
	.blkSaldo-left{
		background:#1B4F72;
		background-image: url(../images/lighten.png);
		text-align:center;
		padding: 15px 0 0 0px;
		 border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;
		border-top-left-radius: 0.3em;
		-o-border-top-left-radius: 0.3em;
		-moz-border-top-left-radius: 0.3em;
		
	}
	.blkSaldo-middle{
		background:#2874A6;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-right{
		background:#cc324b;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-lost{
		background:#3498DB;
		background-image:url('../images/lighten.png');
		border-radius:0;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
		border-top-right-radius: 0.3em;
		-o-border-top-right-radius: 0.3em;
		-moz-border-top-right-radius: 0.3em;
		-webkit-border-top-right-radius: 0.3em;

	}
	
	.blkSaldo-left span{
		display: block;
		font-size: 15px;
		font-weight: 400;
		color: #fff;
		background-color: #1B4F72;
		padding: 8px 0;
		margin-top: 15px;
		border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;

	}
	span.resgatado {
		background-color: #2874A6;
		border-radius:0;
	}
	span.liberar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
	}
	span.expirar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
	}
	.blkSaldo img {
		text-align: center;
		margin: 0 auto;
	}
	/*-- bloco saldo --*/
	
	.wrapper404 {
		text-align: center;
	}
	
	.wrapper404 h2 {
		font-family: 'Lato', sans-serif;
		font-weight: 900;
		letter-spacing: -8px;
		font-size: 60px;
		margin: 0;
	}
	
	.wrapper404 p {
		font-weight: 700;
		font-size: 1.9em;
		margin: 0;
	}
	
	.wrapper404 span {
		font-size: 0.6em;
	}
	
	#c7_chosen {
		font-size: 28px;
	}
	
	#c7_chosen > a {
		height: 66px;
		padding: 18px 27px;		
	}
	
	#c5_chosen {
		font-size: 28px;
	}
	
	#c5_chosen > a {
		height: 66px;
		padding: 18px 27px;		
	}
	
	#COD_ATENDENTE_chosen {
		font-size: 28px;
	}
	
	#COD_ATENDENTE_chosen > a {
		height: 66px;
		padding: 18px 27px;		
	}
	
	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}
	
	.chosen-container-single .chosen-single div b {
		background: url(chosen-sprite.png) no-repeat 0 7px;
	}

	#corpoForm{
		width: 100vw!important;
	}

	#caixaForm{
		overflow: auto;
	}

	#caixaImg, #caixaForm{
		height: 100vh;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center; 
		-webkit-background-size: 100% 100%;
		  -moz-background-size: 100% 100%;
		  -o-background-size: 100% 100%;
		  background-size: 100% 100%;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#caixaImg{
		 padding: 0;
	}
		 
}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	 body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: 103%;
	}

	.navbar img{
		margin-top: -10px;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
		 padding: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	#caixaImg{
		 padding: 0;
	}

		
}

.gray-round{
	border-radius: 4px;
	background-color: #<?=$cor_textos?>;
}

.input-lg{
	height:50px!important;
}


    /*.chosen-single { 
        height: 66px!important; 
        line-height: 2!important;
        font-size: 28px;
    }*/

    .logo-center{
		margin-left: auto;
		margin-right: auto;
	}

	.required:after {
		color: #e32;
		content: ' *';
		display: inline;
		font-size: 13px;
		font-weight: bold;    
	}

	.help-block {
		font-size: 10px;
		border: 0;
		padding: 0;
		margin: 0
	}

	.field-icon {
	    float: right;
	    margin-left: -25px;
	    margin-top: -30px;
	    position: relative;
	    z-index: 2;
	}
	
	</style>
	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>
	
		
		<div class="row" id="corpoForm">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="sucessoSenha.do?id=<?=fnEncode($cod_empresa)?>" autocomplete="off">

				<div class="col-md-6 col-xs-12" id="caixaImg">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img_g?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_imgmob?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
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
		
	</form>
	
</div><!-- /container -->

<script src="https://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
<!-- <script src="https://bunker.mk/js/plugins/jquery.tablesorter.min.js" type="text/javascript"></script>
<script src="https://bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script> -->
<!-- <script src="https://bunker.mk/js/jquery-confirm.min.js"></script> -->
<script src="https://bunker.mk/js/plugins/jquery.placeholder.js"></script>

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