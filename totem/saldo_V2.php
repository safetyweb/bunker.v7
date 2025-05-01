<?php
include "../_system/_functionsMain.php";
include_once './funWS/atualizacadastro.php';
// include_once './funWS/TKT.php';
//echo fnDebug('true');
 $parametros = fnDecode($_GET['key']);
 $arrayCampos = explode(";", $parametros);
 // array_pop($arrayCampos);
 
 // print_r ($arrayCampos);

$url_index = "http://".$_SERVER["HTTP_HOST"]."/consulta_V2.do?key=".$_GET["key"]."&".date("Ymdhis").round(microtime(true) * 1000);
include "noback.php";
 
 if( $_SERVER['REQUEST_METHOD']=='GET' )
 {
    // $cpf = $_GET['c1'];                                               
    // $buscaconsumidor = fnconsulta($cpf, $arrayCampos);
 }   

$cod_univend = $arrayCampos[2];
$cod_empresa = $arrayCampos[4];
$cod_players = $arrayCampos[7];
$dias30="";
$dat_ini="";
$dat_fim="";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

$dat_ini = fnDataSql($dias30); 
$dat_fim = fnDataSql($hoje);
        

$hashLocal = mt_rand();	

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	$request = md5( implode( $_POST ) );

	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;

		$canal = 2;
		$tipoAtiv = 2;
		$ativacao = 1;

		$_POST[COD_UNIVEND] = $cod_univend;

		// WEBSERVICE DE CADASTRO MAIS.CASH
		include 'cadastroMaisCashWS.php';
		
		// $urlTKT = geratkt($dadosatualiza,$arrayCampos);                        
                       
		// if($urlTKT['coderro']==16)
		// {$tktOff = "disabled";}
		// else {$tktOff = "";};	

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];



	}
            
    
}
                      
                                          
//busca dados do layout
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_totem = $qrBuscaSiteTotem['COD_TOTEM'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
	$des_imgback_mob = $qrBuscaSiteTotem['DES_IMGBACK_MOB'];
	if($des_imgback_mob == ""){
		$des_imgback_mob = $des_imgback;
	}
    $cod_layout = $qrBuscaSiteTotem['COD_LAYOUT'];

    if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
        $check_CORPERS = '';
    } else {
        $check_CORPERS = "checked";
    }

    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
    $cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];
	
    $des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index"){$destinoHome = "";}
	else {$destinoHome = "banner.do";}
    
	$val_inativo = $qrBuscaSiteTotem['VAL_INATIVO'];

	//fnMostraForm();

	//fnEscreve($tip_contabil);	
}

$sqlPlayer = "SELECT * FROM TOTEM_PLAYERS WHERE COD_EMPRESA = $cod_empresa AND COD_PLAYERS = $cod_players";
$arrayQueryPlayer = mysqli_query(connTemp($cod_empresa, ''), $sqlPlayer);
$qrBuscaTotemPlayer = mysqli_fetch_assoc($arrayQueryPlayer);

if (isset($qrBuscaTotemPlayer)) {

	$log_ticket = $qrBuscaTotemPlayer['LOG_TICKET'];
	$log_nps = $qrBuscaTotemPlayer['LOG_NPS'];
    $val_inativo = $qrBuscaTotemPlayer['VAL_INATIVO'];
    $des_paghome = $qrBuscaTotemPlayer['DES_PAGHOME'];
	$destinoHome = "";

	if($des_paghome == "index"){
		$destinoHome = "";
	}else if($des_paghome == "nps"){
		$destinoHome = "pesquisa.do";
	}else if($des_paghome == "cad"){
		$destinoHome = "consulta_V2.do";
	}else{
		$destinoHome = "banner.do";
	}

}


	//busca cliente
	// $sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CGCECPF = ".$dadosatualiza['cpf']." ";
	// $qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	// $cod_cliente = $qrBuscaCliente['COD_CLIENTE'];

	// busca info empresa
	$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS, NUM_DECIMAIS_B, COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

	if($qrEmp['TIP_RETORNO'] == 1){
		$casasDec = 0;
		$pref = "Pontos: ";
	}else{
		$casasDec = $qrEmp['NUM_DECIMAIS_B'];
		$pref = "R$ ";
	}

	
	//busca saldo do cliente
	$sqlSaldo = "SELECT 
        
			        (SELECT Sum(val_saldo) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL,
			      
					 (SELECT max(dat_libera) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS DAT_LIBERA,
			            
					(SELECT min(dat_expira) 
			        FROM   creditosdebitos 
			        WHERE  cod_cliente = A.cod_cliente 
			            AND tip_credito = 'C' 
			            AND COD_STATUSCRED IN(1,2) 
			            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS DAT_MINIMA
			     
			       
			      
			      FROM CREDITOSDEBITOS A
			      WHERE COD_CLIENTE = $cod_cliente
			      AND COD_EMPRESA = $cod_empresa
			      GROUP BY COD_CLIENTE";

	// echo $sqlSaldo;

    $row = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);
	$qrBuscaSaldo = mysqli_fetch_assoc($row);
    // fnEscreveArray($qrBuscaSaldo);
	$credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'],$casasDec);
	$dat_libera = fnDataShort($qrBuscaSaldo['DAT_LIBERA']); 
	$dat_minima = fnDataShort($qrBuscaSaldo['DAT_MINIMA']); 

	// echo $credito_disponivel;

	if($qrBuscaSaldo['CREDITO_DISPONIVEL'] > 0){
		$tem_saldo = 1;
	}else{
		$tem_saldo = 0;
	}

	$sqlNome = "SELECT * FROM CLIENTES 
				WHERE COD_CLIENTE = $cod_cliente 
				AND COD_EMPRESA = $cod_empresa";

	$arrayNom = mysqli_query(connTemp($cod_empresa,''),$sqlNome);
	$qrNom = mysqli_fetch_assoc($arrayNom);

	$nom_cliente = explode(" ", $qrNom['NOM_CLIENTE']);
	$nom_cliente = ucfirst($nom_cliente[0]);
	$log_ofertas = $qrNom['LOG_OFERTAS'];
	$key_cod_externo = $qrNom[KEY_COD_EXTERNO];
 	$num_cartao = $qrNom[NUM_CARTAO];
 	$num_cgcecpf = $qrNom[NUM_CGCECPF];
 	$num_celular = $qrNom[NUM_CELULAR];
	$des_emailus = $qrNom[DES_EMAILUS];
	$dat_nascime = $qrNom[DAT_NASCIME];
	$log_termo = $qrNom[LOG_TERMO];

	switch ($qrEmp[COD_CHAVECO]) {

		case 2:
			$campoChave = 'KEY_NUM_CARTAO';
			$chaveCampo = $num_cartao;
		break;
		case 3:
			$campoChave = 'KEY_NUM_CELULAR';
			$chaveCampo = $num_celular;
		break;

		default:
			$campoChave = 'KEY_NUM_CGCECPF';
			$chaveCampo = $num_cgcecpf;
		break;

	}
	
	//fnEscreve($credito_disponivel);
	//fnEscreve($credito_aliberar);
	//fnEscreve($sqlSaldo);
	
	//echo "<pre>";
	//print_r($buscaconsumidor);
	//echo "</pre>"; 

	//fnEscreveArray($cod_univend);
	// fnEscreve($cpf);

	$dev = $_GET['dev'];

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

	$des_img = $qrControle['DES_IMG'];
	$des_img_g = $qrControle['DES_IMG_G'];
	$des_imgmob = $qrControle['DES_IMGMOB'];

	// busca ticket
	$sqlTkt = "SELECT COUNT(COD_PRODTKT) AS NRO_PRODUTOS FROM PRODUTOTKT 
	WHERE COD_EMPRESA = $cod_empresa
	AND	DAT_INIPTKT <= '$dat_ini 00:00:00' 
	AND	DAT_FIMPTKT >= '$dat_fim 23:59:59'";
	$arrayQueryTkt = mysqli_query(connTemp($cod_empresa,''),$sqlTkt);
	$qrBuscaTkt = mysqli_fetch_assoc($arrayQueryTkt);

	$sql = "SELECT DES_DOMINIO, COD_DOMINIO from SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
                //fnEscreve($sql);
    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
    $qrBuscaDesEmpresa = mysqli_fetch_assoc($arrayQuery);
    //fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
    $des_dominio = $qrBuscaDesEmpresa['DES_DOMINIO'];

    if($qrBuscaDesEmpresa[COD_DOMINIO] == 2){
		$extensaoDominio = ".fidelidade.mk";
	}else{
		$extensaoDominio = ".mais.cash";
	}

	$sqlImg = "SELECT DES_IMAGEM, DES_IMAGEM_MOB FROM BANNER_TOTEM WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend AND LOG_ATIVO = 'S' AND COD_EXCLUSA = 0";
	$arrayImg = mysqli_query(connTemp($cod_empresa,""),$sqlImg);
	$qrImg = mysqli_fetch_assoc($arrayImg);

	if($qrImg['DES_IMAGEM'] != ""){
		$des_img = $qrImg['DES_IMAGEM'];
		$des_img_g = $qrImg['DES_IMAGEM'];
	}

	if($qrImg['DES_IMAGEM_MOB'] != ""){
		$des_imgmob = $qrImg['DES_IMAGEM_MOB'];
	}
	
	
?>
	

<html lang="pt">
    <head>

	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9"/>
	<meta http-equiv="X-UA-Compatible" content="IE=10"/>
	<meta http-equiv="X-UA-Compatible" content="IE=11"/>
	
	<title>Totem</title>
		
	<link href="https://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
	<script src="https://bunker.mk/js/jquery.min.js"></script>
	
	<!-- JQUERY-CONFIRM -->
	<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
	
	<!-- extras -->
	<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
	
    <!-- complement -->
	<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
	<script src="https://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!--[if IE]>
	  <link rel="stylesheet" type="text/css" href="https://bunker.mk/css/totem.css" />
	<![endif]-->	

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico"/>

	<?php if($check_CORPERS == "checked"){ include "customCss.php"; } ?>
	
	<style>
	
	.input-lg {
		font-size: 28px;
		line-height: 1.5;
	}
	body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; */
	  background: #fff;
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

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
		width: 100%!important;
	    margin: 0!important;
	    padding: 0!important;
	}

	#caixaForm{
		overflow: auto;
	}

	#caixaImg, #caixaForm{
		height: 100vh;
	}

	#caixaImg{
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center; 
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
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
	  background: #fff;
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
	  background: #fff;
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
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
	  background: #fff;
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#caixaImg{
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
		 padding: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
	  background: #fff;
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
		background: #FFF url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  /*background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
	  background: #fff;
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

.p-top10{
	padding-top: 10.5px;
}

	.logo-center{
		margin-left: auto;
		margin-right: auto;
	}


    /*.chosen-single { 
        height: 66px!important; 
        line-height: 2!important;
        font-size: 28px;
    }*/

	
	</style>
	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>

	<!-- top nav bar -->	
	<nav class="navbar navbar-default menuCentral " style="border-radius: 0;">
	  <div class="container-fluid">
		<div id="navbar" class="navbar-collapse">
			<div style="text-align: center;">
				<div class="push20"></div>
				<!--<a href="/?key=<?php echo $_GET['key'] ;?>"><img class="logo-<?php echo $des_alinham; ?>" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>"></a>-->
				<?php if($des_logo != ""){ ?>
					<a href="<?=$destinoHome?>?key=<?php echo $_GET['key'] ;?>"><img class="logo-<?php echo $des_alinham; ?> img-responsive" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" style="max-width: 250px;"></a>
				<?php } ?>				<div class="push20"></div>
			<div>
		</div><!--/.nav-collapse -->
	  </div>
	</nav> 
	<!-- end top nav bar -->	
		
		<div class="row" id="corpoForm">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro_V2.do?key=<?php echo $_GET['key'] ;?>">

				<div class="col-md-6 col-xs-12" id="caixaImg">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img_g?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_imgmob?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
				</div>

				<div class="col-md-6 col-xs-12 text-center" id="caixaForm" style="background-color: #FFF;">

					<div class="push30"></div>
					
					<div class="col-md-10 col-md-offset-1">
						<!--Adicionado por Lucas referente ao chamado 6435 -->
						<?php if($atualiza != "Programa restrito para maiores de 18 anos!"){ ?>
							<h4><b>Parabéns</b>, <?=$nom_cliente?>.</h4>
							<h5>Seu cadastro foi <?=$atualiza?></h5>
						<?php }else{ ?>
							<h5><?=$atualiza?></h5>
						<?php } ?>

					</div>

					<div class="push20"></div>

					<?php if($log_novocli == N){ ?>

					<div class="col-md-10 col-md-offset-1">

						<div class="col-xs-12 gray-round">
							
							<div class="col-xs-6 text-left">
								
								<p class="p-top10" style="color:#fff; font-size:12px;">SALDO ACUMULADO</p>

							</div>

							<div class="col-xs-6 text-right">
								
								<p class="p-top10" style="color:#fff; font-size:11px;"><?=$pref?><b style="font-size:16px;"><?=$credito_disponivel?></b></p>

							</div>

						</div>
						
						<div class="push20"></div>

						<?php if($tem_saldo != 0){ ?>

						<div class="col-xs-12 gray-round">
							
							<div class="col-xs-6 text-left">
								
								<p class="p-top10" style="color:#fff; font-size:12px;">USE SEU SALDO TOTAL NO PERÍODO:</p>

							</div>

							<div class="col-xs-6 text-right">
								
								<p class="p-top10" style="color:#fff; font-size:11px;">DE <b style="font-size:16px;"><?=$dat_libera?></b> A <b style="font-size:16px;"><?=$dat_minima?></b></p>

							</div>

						</div>
						
						<!-- <div class="push20"></div>

						<div class="col-xs-12 gray-round">
							
							<div class="col-xs-6 text-left">
								
								<p class="p-top10" style="color:#fff; font-size:12px;">DESCONTO</p>

							</div>

							<div class="col-xs-6 text-right">
								
								<p class="p-top10" style="color:#fff; font-size:11px;"><a href="javascript:void(0)" class="btn btn-xs btn-info" id="btnExtrato">Ver Extrato</a></p>

							</div>

						</div> -->

						<script type="text/javascript">
							$("#btnExtrato").click(function(){
								$("#formCliente").submit();
							});
						</script>
						
						<div class="push20"></div>

						<?php } ?>
						
					</div>

					<?php } ?>

					<!-- <div class="col-md-4 col-md-offset-7 col-xs-12 text-right">
						<a href="javascript:void(0)">EXTRATO DETALHADO DE LIBERAÇÃO E VENCIMENTO DOS CRÉDITOS</a>
					</div> -->

					<div class="push10"></div>

					<?php 
					if ($log_ticket == 'S' && $log_nps == 'N' && $log_ofertas == 'S'){

						if($qrBuscaTkt['NRO_PRODUTOS'] == 0){ $disable = 'disabled'; }else{ $disable = " "; }
						//fnEscreve($disable);							
					?>	
					
						<div class="col-md-10 col-md-offset-1">
							<a href="ticket_V2.do?key=<?php echo $_GET['key']; ?>&idc=<?=fnEncode($cod_cliente)?>&ch=3" name="TKT" id="TKT" <?=$disable?> class="btn btn-info btn-lg btn-block <?php echo $tktOff; ?> <?=$disable?>" tabindex="5"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp; Visualizar Minhas Ofertas</a>
						</div>
						
						<div class="push10"></div>
									
					<?php 
					}

					// if($cod_empresa = 7){

					?>

						<div class="col-md-10 col-md-offset-1">
							<!-- <a href="validaDados.do?key=<?=$_GET['key']?>&idc=<?=fnEncode($cod_cliente)?>&b=<?=fnEncode(1)?>" class="btn btn-default btn-lg btn-block"><i class="fal fa-user-edit fa-2x" aria-hidden="true"></i>&nbsp; Atualizar/Excluir Cadastro</a> -->
							<a href="javascript:void(0)" id="BUS" class="btn btn-default btn-lg btn-block"><i class="fal fa-user-edit fa-2x" aria-hidden="true"></i>&nbsp; Atualizar/Excluir Cadastro</a>
						</div>

						<div class="push10"></div>

						<div class="col-md-10 col-md-offset-1">
							<a href="consulta_V2.do?key=<?=$_GET['key']?>" class="btn btn-primary btn-lg btn-block"><i class="" aria-hidden="true"></i>&nbsp; Sair</a>
						</div>

					<?php 

					// }
					
					if ($log_nps == 'S'){

						$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
						//fnEscreve($sql);
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
						$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

						if (isset($arrayQuery)) {
							//fnEscreve("entrou if");
							$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
							$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];

						}

					?>	
						
						<div class="col-md-12 col-xs-12">
						
							<iframe frameborder="0" id="blocoPesquisa" src="https://<?=$des_dominio.$extensaoDominio?>/pesquisa?idP=<?=fnEncode($cod_pesquisa)?>&idc=<?=fnEncode($cod_cliente)?>&cod_players=<?=fnEncode($cod_players)?>" style="width: 100%; height: 50vh;" sandbox="allow-top-navigation allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox"></iframe>
							
						</div>
						
						<div class="push10"></div>
									
					<?php 
					}
					?>

					<div class="push100"></div>

				</div>
		
				
				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
				<input type="hidden" name="<?=$campoChave?>" id="<?=$campoChave?>" value="<?=$chaveCampo?>">
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
				
			</form>

			<form method="POST" id="formCliente" action="https://<?=$des_dominio.$extensaoDominio?>" target="_blank" style="width: 0px; height: 0px; margin: 0px; padding: 0px;">
				<input type="hidden" name="idc" value="<?=fnEncode($cod_cliente)?>">
				<input type="hidden" name="t" value="<?=fnEncode('S')?>">
			</form>
			
		</div><!-- /container -->

		<!-- modal -->									
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
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
	
	<script src="https://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
	<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="https://bunker.mk/js/plugins/jquery.placeholder.js"></script>	
	<script>
	
	<?php if ($val_inativo != "0"){ ?>
	var timer;
	window.onload= document.onmousemove= document.onkeypress= function(){
		clearTimeout(timer);
		timer=setTimeout(function(){location= '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key'] ;?>'},<?php echo $val_inativo ;?>000);
	}	

	window.addEventListener('touchstart', function() {
  		clearTimeout(timer);
		timer=setTimeout(function(){location= '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key'] ;?>'},<?php echo $val_inativo ;?>000);
	});
	
	<?php } ?>

	$(function(){
	
		$('input, textarea').placeholder();	

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

	});

	$("#BUS").on('click', function(e){
		e.preventDefault();
		document.getElementById("formulario").submit()
	});

	function cadastraTotem(){

		$.ajax({
			type: "POST",
			url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>",
			data: $('#formulario').serialize(),
			beforeSend:function(){
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#relatorioConteudo").html(data);		
				console.log(data);
				data=0;	
				window.location.href = "saldo_V2.do?key=<?php echo $_GET['key'] ;?>&idc="+data+"msg=OK";				
			},
			error:function(){
			    $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});

	}

	<?php include "nobackJS.js"; ?>

	</script>
	
    </body>
	
</html>
	