<?php
include "../_system/_functionsMain.php";
include_once './funWS/buscaConsumidor.php';
include_once './funWS/buscaConsumidorCNPJ.php';
include_once 'funWS/saldo.php';
//echo fnDebug('true');
 $parametros = fnDecode($_GET['key']);
 $arrayCampos = explode(";", $parametros);
 $verificaUrl = $_GET['r'];
 // array_pop($arrayCampos);
 
$url_index = "http://".$_SERVER["HTTP_HOST"]."/atendente.do?key=".$_GET["key"]."&".date("Ymdhis").round(microtime(true) * 1000);
include "noback.php";

 // print_r ($arrayCampos);
 
 if( $_SERVER['REQUEST_METHOD']=='GET' )
 {
    // $cpf = $_GET['c1'];                                               
    // $buscaconsumidor = fnconsulta($cpf, $arrayCampos);
 }  

 if(!isset($verificaUrl)){
	
	?>
	<script type="text/javascript">
		window.location.href = "atendente.do?key=<?php echo $_GET['key'] ;?>&r=<?=date("Ymdhis").round(microtime(true) * 1000)?>";
	</script>
	<?php
} 

$cod_univend = $arrayCampos[2];
$cod_empresa = $arrayCampos[4];
$cod_players = $arrayCampos[7];
$c10 = "";
        
	//echo "<h1>".$_GET['param']."</h1>";
	
	//Array ( [nome] => DIOGO LIMA DE SOUZA [cpf] => 01734200014 [sexo] => 1 [datanascimento] => 01/12/1986 [msg] => OK )

	//$buscaconsumidor['nome']
	//fnEscreve("totem");

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
			
			$c1 = fnLimpaDoc($_REQUEST['c1']);

			if($_REQUEST['c10'] && $_REQUEST['c10'] != ""){
				$c1 = fnLimpaCampo(fnLimpaDoc($_REQUEST['c10']));
				$c10 = fnLimpaCampo(fnLimpaDoc($_REQUEST['c10']));
			}

			// fnEscreve($c1);

			// if($c10 != ""){
			// 	$c1 = $c1;
			// }
			//$cod_orcamento = fnLimpacampo($_REQUEST['COD_ORCAMENTO']);
                                                
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$parametros = fnDecode($_GET['key']);
			$arrayCampos = explode(";", $parametros); 
   //                      if(strlen($c1)<='11')
   //                      {    
   //                          $buscaconsumidor = fnconsulta($c1, $arrayCampos);
                            
   //                      }else{

   //                            $buscaconsumidor = fnconsultacnpf($c1, $arrayCampos); 
                            
   //                      }
                           
			// if($buscaconsumidor['localizacaocliente']=='13')
   //                          {
   //                            $cpf= $c1; 
   //                          }else{
   //                              if($buscaconsumidor['cpf']=='00000000000')
   //                              {   
   //                              $cpf=$c1;  
   //                              }else
   //                              {
   //                                $cpf=$buscaconsumidor['cpf'];
   //                              }    
   //                      }  
                     
                          
                           
                    if ($opcao != ''){
				
					//mensagem de retorno
					switch ($opcao)
					{
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

	// if($buscaconsumidor['cartao'] != ""){
	// 	$c1 = $buscaconsumidor['cartao'];
	// 	$c10 = $buscaconsumidor['cartao'];
	// }

	// if($c10 != ""){
	// 	$readonly = "readonly";
	// }else{
	// 	$readonly = "";
	// }

	// if($c10 == $buscaconsumidor['cpf']){
	// 	$readonly = "";
	// }

                      
                                          
//busca dados do layout
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
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

	$destinoHome = "atendente.do";

}


	//busca cliente
	// $sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CGCECPF = ".$dadosatualiza['cpf']." ";
	// $qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	// $cod_cliente = $qrBuscaCliente['COD_CLIENTE'];

	// busca info empresa
	$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS,NUM_DECIMAIS_B FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

	if($qrEmp['TIP_RETORNO'] == 1){
		$casasDec = 0;
	}else{
		$casasDec = $qrEmp['NUM_DECIMAIS_B'];
	}

	// //busca cliente
	// $sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CARTAO = ".$buscaconsumidor['cartao']." ";
	// $qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	// $cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	
	// //busca saldo do cliente
	// $sqlSaldo = "CALL SP_CONSULTA_SALDO_CLIENTE($cod_cliente);";
 //    $row = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);
	// $qrBuscaSaldo = mysqli_fetch_assoc($row);
 //    // fnEscreveArray($qrBuscaSaldo);
	// $credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'],$casasDec);
	// $credito_aliberar = fnValor(($qrBuscaSaldo['TOTAL_CREDITO']-$qrBuscaSaldo['CREDITO_DISPONIVEL']),$casasDec);
	// $saldototal = fnValor($qrBuscaSaldo['TOTAL_CREDITO'],$casasDec);  
	
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
	$des_img_g = $qrControle['DES_IMG_G'];
	$des_img = $qrControle['DES_IMG'];
	$des_imgmob = $qrControle['DES_IMGMOB'];

	$sqlImg = "SELECT DES_IMAGEM, DES_IMAGEM_MOB FROM BANNER_TOTEM WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univend AND LOG_ATIVO = 'S'";
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
	<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />
		
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

	.input-chave{
		font-size: 36px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.input-chave{
		font-size: 23px;
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

	.shadow{
       -webkit-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        -moz-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        /*width: 100%;*/
        border-radius: 5px;
    }

    .shadow2{
        -webkit-box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        -moz-box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        box-shadow: 0px 5px 8px 0px rgba(204,200,204,0.8);
        width: 100%;
        border-radius: 5px;
        margin: 10px 0;
    }

    .carousel{
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }
    .carousel-caption{
         color: <?=$cor_textos?>;
        /*background-color: rgba(0,0,0,0.2);*/
        border-radius: 30px 30px 30px 30px;
        padding-top: 5px;
        padding-bottom: 5px;
        bottom: 0px;
        left: 0;
        right: 0;
        background-color: rgba(255,255,255,0.7);
    }
    .contorno{
      /*-webkit-text-fill-color: white;  Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

    .carousel-indicators{
        z-index: 0;
    }

    .carousel-control.left, .carousel-control.right {
        background-image: none
    }

    .img-lista{
        height: 85px; 
        width: 85px;
        border-radius: 50px; 
    }

    .center{
        margin: auto;
        position:absolute;
        right: 0;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
    }

     body{
        padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);

    }

    .fa-md {
	    font-size: 32px;
	}
	
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
				<?php } ?>
				<div class="push20"></div>
			<div>
		</div><!--/.nav-collapse -->
	  </div>
	</nav> 
	<!-- end top nav bar -->
	
		
		<div class="row" id="corpoForm">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="atendenteCad.do?key=<?php echo $_GET['key'] ;?>&v=<?=fnEncode(1)?>" autocomplete="off">
				

				<div class="col-md-6 col-xs-12" id="caixaImg">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img_g?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
					<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_imgmob?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
				</div>

				<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

					<div class="push20"></div>
					<div class="push50"></div>
					
					<?php

						$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

						$arrayCampos = mysqli_query($connAdm->connAdm(),$sqlCampos);

						// echo($sqlCampos);

						$lastField = "";

						$qrCampos = mysqli_fetch_assoc($arrayCampos);

						switch ($qrCampos[COD_CHAVECO]) {

							case 2:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cartão</label>
											<input type="text" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php

							break;

							case 3:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Celular</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 4:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código Externo</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo1" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 5:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cartão</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" data-error="ou este" maxlenght="10" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php
								
							break;

							case 6:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nascimento</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo2 data" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Celular</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo3 sp_celphones" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label>&nbsp;</label>
											<label for="inputName" class="control-label required">Email</label>
											<input type="email" class="form-control input-hg input-sm campo4" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php
								
							break;							
							
							default:

								?>
									<div class="col-md-12 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">CPF/CNPJ</label>
											<input type="tel" style="color: #34495E!important;" class="form-control input-hg input-lg text-center input-chave campo1 cpfcnpj" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" class="campo2" value="">
									<input type="hidden" class="campo3" value="">
									<input type="hidden" class="campo4" value="">

								<?php

							break;

						}

					?>



				<div class="push20"></div>

				<div class="col-xs-12">
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn validaCPF" tabindex="5"><i class="fa fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
				</div>

					
				<div class="push50"></div>

				</div>
		
				
				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
				
					
				<!-- blocos coluna dupla -->

                <!-- <div id="colDupla">

                    <a href="ofertas.do?key=key=<?php echo $_GET['key'] ;?>" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>;">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-user-plus fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Cadastro</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="banner.do?key=key=<?php echo $_GET['key'] ;?>" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>;">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-usd-circle fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Consultar Saldo</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="habito.do?key=key=<?php echo $_GET['key'] ;?>" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>;">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-tags fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Ticket de Ofertas</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                </div> -->

				

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
	// var timer;
	// window.onload= document.onmousemove= document.onkeypress= function(){
	// 	clearTimeout(timer);
	// 	timer=setTimeout(function(){location= '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key'] ;?>'},<?php echo $val_inativo ;?>000);
	// }	

	// window.addEventListener('touchstart', function() {
 //  		clearTimeout(timer);
	// 	timer=setTimeout(function(){location= '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key'] ;?>'},<?php echo $val_inativo ;?>000);
	// });
	
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

	$('.validaCPF').click(function(e){

		var campo1 = $(".campo1").val(),
			campo2 = $(".campo2").val(),
			campo3 = $(".campo3").val(),
			campo4 = $(".campo4").val();

			if(campo1 != "" || campo2 != "" || campo3 != "" || campo4 != ""){

				if(campo1 != ""){

					if(!valida_cpf_cnpj($('.cpfcnpj').val())){

						e.preventDefault();
						$.alert({
							title: 'Atenção!',
							content: 'CPF/CNPJ digitado é inválido!',
						});	

					}

				}

			}else{

				e.preventDefault();
				$.alert({
					title: 'Atenção!',
					content: 'Pelo menos um dado deve ser informado!',
				});

			}

	});

	// $(".campo").keyup(function(){
	// 	alert($("").val());
	// });

	// $('.validaCPF').click(function(e){

	// 	if($('.cpfcnpj').val() != "" && $('.cartao').val() == "" || $('.cpfcnpj').val() != "" && $('.cartao').val() != "" || $('.cpfcnpj').val() == "" && $('.cartao').val() == ""){

	// 		if(!valida_cpf_cnpj($('.cpfcnpj').val())){
	// 			e.preventDefault();
	// 			$.alert({
	// 				title: 'Atenção!',
	// 				content: 'CPF/CNPJ digitado é inválido!',
	// 			});			
	// 		}

	// 	}else{

	// 		e.preventDefault();
	// 		$.ajax({
	// 			method: 'POST',
	// 			url: 'ajxConsultaCartao.php',
	// 			data: {COD_EMPRESA: <?=$cod_empresa?>, c10:$('#c10').val()},
	// 			success:function(data){

	// 				if(data != 1){

	// 					$.alert({
	// 						title: 'Atenção!',
	// 						content: data,
	// 					});

	// 				}else{

	// 					$("#formulario").submit();

	// 				}

	// 			}
	// 		});

	// 	}

	// });

	<?php include "nobackJS.js"; ?>


	</script>
	
    </body>
	
</html>
	