<?php
include "../_system/_functionsMain.php";
include_once './funWS/buscaConsumidor.php';
include_once './funWS/buscaConsumidorCNPJ.php';
include_once 'funWS/saldo.php';
include_once './funWS/TKT.php';
//echo fnDebug('true');
 $parametros = fnDecode($_GET['key']);
 $arrayCampos = explode(";", $parametros);
 // array_pop($arrayCampos);

 	$dias30="";
	$dat_ini="";
	$dat_fim="";

	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));

	$dat_ini = fnDataSql($dias30); 
	$dat_fim = fnDataSql($hoje);
 
 // print_r ($arrayCampos);
 
 if( $_SERVER['REQUEST_METHOD']=='GET' )
 {
    $cpf = $_GET['c1']; 

    if(strlen($cpf) <= '11'){

					// echo '<pre>';

        $buscaconsumidor = fnconsulta(fnCompletaDoc($cpf,'F'), $arrayCampos);

        // print_r($buscaconsumidor);

        // echo '</pre>';
        
    }else{

    	// echo 'else';

        $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($cpf,'J'), $arrayCampos); 
        
	}                                              

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
			$buscaCartao = 0;

			if($_REQUEST['c10'] && $_REQUEST['c10'] != ""){
				$c1 = fnLimpaCampo(fnLimpaDoc($_REQUEST['c10']));
				$c10 = fnLimpaCampo(fnLimpaDoc($_REQUEST['c10']));
				$buscaCartao = 1;
			}

			// echo($c1);
			// exit();

			// if($c10 != ""){
			// 	$c1 = $c1;
			// }
			//$cod_orcamento = fnLimpacampo($_REQUEST['COD_ORCAMENTO']);
                                                
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$parametros = fnDecode($_GET['key']);
			$arrayCampos = explode(";", $parametros); 

			if($buscaCartao == 1){

				$buscaconsumidor = fnconsulta($c1, $arrayCampos);
				// echo '<pre>';

		  //       print_r($buscaconsumidor);

		  //       echo '</pre>';

			}else if(strlen($c1) <= '11'){

		        $buscaconsumidor = fnconsulta(fnCompletaDoc($c1,'F'), $arrayCampos);
		        
		    }else{

		    	// echo 'else';

		        $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($c1,'J'), $arrayCampos); 
		        
			}
                           
			if($buscaconsumidor['localizacaocliente']=='13')
                            {
                              $cpf= $c1; 
                            }else{
                                if($buscaconsumidor['cpf']=='00000000000')
                                {   
                                $cpf=$c1;  
                                }else
                                {
                                  $cpf=$buscaconsumidor['cpf'];
                                }    
                        }  
                     
                          
                           
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

	if($buscaconsumidor['cartao'] != ""){
		$c1 = $buscaconsumidor['cartao'];
		$c10 = $buscaconsumidor['cartao'];
	}

	if($c10 != ""){
		$readonly = "readonly";
	}else{
		$readonly = "";
	}

	if(trim($cpf) == ""){
		$cpf = $c1;
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

    $log_ticket = $qrBuscaSiteTotem['LOG_TICKET'];

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
	}else{
		$destinoHome = "banner.do";
	}

}


	//busca cliente
	$sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CGCECPF = ".$dadosatualiza['cpf']." ";
	$qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];

	// busca info empresa
	$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS,NUM_DECIMAIS_B FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

	if($qrEmp['TIP_RETORNO'] == 1){
		$casasDec = 0;
	}else{
		$casasDec = $qrEmp['NUM_DECIMAIS_B'];
	}

	//busca cliente
	$sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CARTAO = ".$buscaconsumidor['cartao']." ";
	$qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	
	//busca saldo do cliente
	$sqlSaldo = "CALL SP_CONSULTA_SALDO_CLIENTE($cod_cliente);";
    $row = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);
	$qrBuscaSaldo = mysqli_fetch_assoc($row);
    // fnEscreveArray($qrBuscaSaldo);
	$credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'],$casasDec);
	$credito_aliberar = fnValor(($qrBuscaSaldo['TOTAL_CREDITO']-$qrBuscaSaldo['CREDITO_DISPONIVEL']),$casasDec);
	$saldototal = fnValor($qrBuscaSaldo['TOTAL_CREDITO'],$casasDec);  
	
	//fnEscreve($credito_disponivel);
	//fnEscreve($credito_aliberar);
	//fnEscreve($sqlSaldo);
	
	// echo "<pre>";
	// print_r($buscaconsumidor);
	// echo "</pre>"; 

	//fnEscreveArray($cod_univend);
	// echo($buscaconsumidor['codatendente']); 
	// exit();

	$dev = $_GET['dev'];

	$sqlTkt = "SELECT COUNT(COD_PRODTKT) AS NRO_PRODUTOS FROM PRODUTOTKT 
	WHERE COD_EMPRESA = $cod_empresa
	AND	DAT_INIPTKT <= '$dat_ini 00:00:00' 
	AND	DAT_FIMPTKT >= '$dat_fim 23:59:59'";
	$arrayQueryTkt = mysqli_query(connTemp($cod_empresa,''),$sqlTkt);
	$qrBuscaTkt = mysqli_fetch_assoc($arrayQueryTkt);

	//dados atualiza cadastro
	$dadosatualiza=Array('nome'=>$buscaconsumidor['nome'],
						 'sexo'=>$buscaconsumidor['sexo'],
						 'email'=>$buscaconsumidor['email'],
						 'telefone'=>$buscaconsumidor['telefone'],
						 'cpf'=>fnLimpaDoc($buscaconsumidor['cpf']),
						 'cartao'=>$buscaconsumidor['cartao'],
						 'dt_nascimento'=>$buscaconsumidor['dt_nascimento'],
						 'profissao'=>$buscaconsumidor['profissao'],
						 'codatendente'=>$buscaconsumidor['codatendente'],
						 'senha'=>$buscaconsumidor['senha'],
						 'endereco' => $buscaconsumidor['endereco'],
						 'numero' => $buscaconsumidor['numero'],
						 'cep' => $buscaconsumidor['cep'],
						 'estado' => $buscaconsumidor['estado'],
						 'cidade' => $buscaconsumidor['cidade'],
						 'bairro' => $buscaconsumidor['bairro'],
						 'complemento' => $buscaconsumidor['complemento']
					   );

	$urlTKT = geratkt($dadosatualiza,$arrayCampos); 
	
	
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
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; 
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

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
	
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}
		 
}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	 body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: 103%;
	}

	.navbar img{
		margin-top: -10px;
	}

	
	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	

	.navbar img{
		margin-top: 0;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	
}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

		
	
    
}


    #c7 { 
        height: 66px; 
    }

    .logo-center{
		margin-left: auto;
		margin-right: auto;
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
					<a href="/?key=<?php echo $_GET['key'] ;?>"><img class="logo-<?php echo $des_alinham; ?> img-responsive" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>"></a>
					<div class="push20"></div>
				<div>
			</div><!--/.nav-collapse -->
		  </div>
		</nav> 
		<!-- end top nav bar -->	
	
		
		<div class="container">

			<form data-toggle="validator" role="form2" method="post" id="formulario" action="retorno.do?key=<?php echo $_GET['key'] ;?>&msg=OK">
		
				<div class="push"></div>
				
				<?php if( $buscaconsumidor['localizacaocliente'][0] == 14){ ?>
				
				<div class="blkSaldo row">
				<div class="col-md-3 ">
							</div>
					<div class="col-md-6" style="padding: 0 25px;">	
							<div class="col-md-4 blkSaldo-left">
								<h3 style="color: white; margin: auto;" class=""><?php echo $credito_disponivel;?></h3>						
								<span>Saldo Disponivel</span>
							</div>
							<div class="col-md-4 blkSaldo-left blkSaldo-middle">
								<h3 style="color: white; margin: auto;"><?php echo $credito_aliberar; ?></h3> 						
								<span  class="resgatado">Saldo a Liberar</span>
							</div>
							
							<div class="col-md-4 blkSaldo-left blkSaldo-lost">
								<h3 style="color: white; margin: auto;"><?php echo $saldototal; ?></h3> 			   
							   <span class="liberar">Saldo Total</span>
							</div>						
					</div>
				</div>	
				
				<div class="push10"></div>
				
				<?php } else { ?>
				
				<div class="push20"></div>
				
				<?php }   ?>
				
				<div class="col-md-3 " >
				</div>	
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="inputName" class="control-label"></label>
                        <input type="text" class="form-control input-lg" name="c2" id="c2" value="<?php echo $buscaconsumidor['nome'];?>" autocomplete="off" placeholder="Nome" required>
						<div class="help-block with-errors"></div>
					</div>
				</div>
				
				<div class="col-md-3">
				</div>	
				
				<div class="push10"></div> 	
				
				<div class="col-md-3">
				</div> 
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
				 <div class="form-group">
				  <label for="inputName" class="control-label"></label>
				  <input type="text" class="form-control input-lg text-center data" name="c6" id="c6" value="<?php echo $buscaconsumidor['datanascimento'];?>" autocomplete="off" placeholder="Data de Nascimento" required>
				  <div class="help-block with-errors"></div>
				 </div>
				</div>
				
				<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
					<div class="form-group">
						<select data-placeholder="Sexo" name="c7" id="c7" autocomplete="off" class="chosen-select-deselect" required>
							<option value=""></option>					
							<?php 
							
								$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by DES_SEXOPES";
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
							
								while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {														
									echo "<option value='".$qrLayout['COD_SEXOPES']."'>".$qrLayout['DES_SEXOPES']."</option>"; 
								}											
							?>	 							
						</select>
						<script>$("#formulario #c7").val("<?php echo $buscaconsumidor['sexo']; ?>").trigger("chosen:updated"); </script>	
						<div class="help-block with-errors"></div>
					</div>
				</div>				
				
				<div class="col-md-3">
				</div> 
    				
				<div class="push10"></div> 	
				
				<div class="col-md-3">
				</div>	
				
				<div class="col-md-6">
					<div class="form-group">
					<?php 
						if ($cod_empresa == 122 || $cod_empresa == 80 || $cod_empresa == 58 || $cod_empresa == 21 || $cod_empresa == 161 || $cod_empresa == 173 || $cod_empresa == 210){		
					?>
						<select data-placeholder="Selecione a profissão" name="c5" id="c5" autocomplete="off" class="chosen-select-deselect" required>
							<option value=""></option>					
							<?php 	
								$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";
								if(mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0' )
								{
								  $sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
								  $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
								}else
								{
								  $arrayQuery= mysqli_query(connTemp($cod_empresa, ''), $sql); 
								}
							
								while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
								  {														
									echo"
										  <option value='".$qrListaProfi['COD_PROFISS']."'>".$qrListaProfi['DES_PROFISS']."</option> 
										"; 
									  }											
							?>	
						</select>
						<script>$("#formulario #c5").val("<?php echo $buscaconsumidor['profissao']; ?>").trigger("chosen:updated"); </script>	
					<?php  
                                                  
						} else {
					?>
					<input type="hidden" name="c5" id="c5" value="0">
					<?php 
						} 
					?>
					</div>
				</div> 
				
				<div class="col-md-3">
				</div>	
				
				<div class="push10"></div> 	
				
				<div class="col-md-3">
				</div>	
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="inputName" class="control-label"></label>
						<input type="text" class="form-control input-lg" name="c3" id="c3" value="<?php echo $buscaconsumidor['email'];?>" autocomplete="off" placeholder="e-Mail">
						<div class="help-block with-errors"></div>
					</div>
				</div> 
				
				<div class="col-md-3">
				</div>	
								
				<div class="push10"></div> 	
				
				<div class="col-md-3">
				</div>	
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="inputName" class="control-label"></label>
						<input type="text" class="form-control input-lg text-center txttelefone" minlength=15 name="c4" id="c4" value="<?php fnCorrigeTelefone($buscaconsumidor['telcelular']); ?>" autocomplete="off" placeholder="Telefone Celular">
						<div class="help-block with-errors"></div>
					</div>
				</div>
				 
				<div class="col-md-3">
				</div>

				<div class="push10"></div> 					
				
				<div class="col-md-3">
				</div>	
				
				<div class="col-md-6">
					<div class="form-group">
					<?php
						//mostra vendedor para kibeleza					
						if ($cod_empresa == 122  || $cod_empresa == 28 || $cod_empresa == 210){	
							// fnEscreve($buscaconsumidor['codatendente']); 
					?>
						<input type="hidden" name="c8" id="c8" value="<?=$buscaconsumidor['codatendente']?>">
						<select data-placeholder="Selecione o atendente" name="COD_ATENDENTE" id="COD_ATENDENTE" autocomplete="off" class="chosen-select-deselect" required>
							<option value=""></option>					
							<?php 	
								$sql = "SELECT COD_EXTERNO, NOM_USUARIO FROM usuarios WHERE cod_tpusuario IN (11, 7) and cod_empresa = $cod_empresa AND cod_univend=$cod_univend AND cod_exclusa=0 ";
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
								// fnEscreve($sql);
								
								while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
								  {														
									echo"
										  <option value='".$qrListaProfi['COD_EXTERNO']."'>".$qrListaProfi['NOM_USUARIO']."</option> 
										"; 
									  }											
							?>	
						</select>
						<script type="text/javascript">

							if($('#c8').val() != 0){
								$('#COD_ATENDENTE').val("<?=$buscaconsumidor['codatendente']?>").trigger('chosen:updated');
								if($('#COD_ATENDENTE').val()){
									// alert($('#COD_ATENDENTE').val());
									$('#COD_ATENDENTE').prop('disabled', true);
								}
								// alert("<?=$buscaconsumidor['codatendente']?>");
							}

							$('#COD_ATENDENTE').change(function(){
								$('#c8').val($('#COD_ATENDENTE').val());
								// alert($('#c8').val());
								// alert($('#COD_ATENDENTE').val());
							});

							$('#COD_ATENDENTE').prop('required', true);

						</script>
					<?php  
                                                 
						} else {
					?>
					<input type="hidden" name="c8" id="c8" value="0">
					<?php 
						} 
					?>
					<div class="help-block with-errors"></div>
					</div>
				</div>

				<?php

					if($buscaconsumidor[numero] == "" || $buscaconsumidor[numero] == 'S/N'){
						$numero = "";
					}else{
						$numero = $buscaconsumidor[numero];
					}

					switch ($cod_empresa) {
						// case 7:
                        case 91: //Renaza


                ?>

                			<div class="col-md-3"></div>				
							
							<div class="push10"></div> 				
							
							<div class="col-md-3"></div>


							<div class="col-md-6 col-sm-10">
	                			<div class="input-group">
									<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca"  class="btn btn-primary btn-lg addBox" data-url="trocaCartao.do?key=<?php echo $_GET['key']?>&idc=<?=fnEncode($cod_cliente)?>&pop=true" data-title="Troca de Cartão"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
									</span>
									<input type="text" name="c10" id="c10" readonly maxlength="50" class="form-control input-lg" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" value="<?=$c10?>" required>
								</div>
							</div>

                <?php

                        break;
						case 121: //águia postos
						case 91: //águia postos
						case 143: //águia postos
						case 176: //águia postos
						case 190: //águia postos
						case 198: //itapoan
						case 206: //galo branco
						?>

							<div class="col-md-3"></div>				
							
							<div class="push10"></div> 				
							
							<div class="col-md-3"></div>

							<div class="col-md-6 col-sm-10">
								<div class="form-group">
									<label for="inputName" class="control-label"></label>
									<input type="text" class="form-control input-lg text-center cartao" name="c10" id="c10" value="<?=$c10?>" maxlength="10" placeholder="Número do Cartão" autocomplete="off" <?=$readonly?> required>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php 
							
						break;

						// ervadoce
						case 114:
						case 115:
						case 116:

						if($buscaconsumidor[endereco] != ""){
							$mostraEnd = "";
							$mostraBtnBusca = "display: none;";
						}else{
							$mostraEnd = "display: none;";
							$mostraBtnBusca = "";
						}


				?>

							<div id="ENDEREC" style="<?=$mostraEnd?>">

								<div class="col-md-3"></div>				
								
								<div class="push10"></div> 				
								
								<div class="col-md-3"></div>

								<div class="col-md-6 col-sm-10">
									<div class="form-group">
										<label for="inputName" class="control-label"></label>
										<input type="text" class="form-control input-lg text-center" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="150" autocomplete="off" value="<?=$buscaconsumidor[endereco]?>" placeholder="Endereço" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div id="NUMERO" style="<?=$mostraEnd?>">

								<div class="col-md-3"></div>				
								
								<div class="push10"></div> 				
								
								<div class="col-md-3"></div>

								<div class="col-md-6 col-sm-10">
									<div class="form-group">
										<label for="inputName" class="control-label"></label>
										<input type="text" class="form-control input-lg text-center int" name="NUM_ENDEREC" id="NUM_ENDEREC" maxlength="5" autocomplete="off" value="<?=$numero?>" placeholder="Número" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div id="COMPLEM" style="<?=$mostraEnd?>">

								<div class="col-md-3"></div>				
								
								<div class="push10"></div> 				
								
								<div class="col-md-3"></div>

								<div class="col-md-6 col-sm-10">
									<div class="form-group">
										<label for="inputName" class="control-label"></label>
										<input type="text" class="form-control input-lg text-center" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="150" autocomplete="off" value="<?=$buscaconsumidor[complemento]?>" placeholder="Complemento">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div id="BUSCACEP">

								<div class="col-md-3"></div>				
								
								<div class="push20"></div> 				
								
								<div class="col-md-3"></div>

								<div class="col-md-6 col-sm-10">
															
									<!-- <div class="push15"></div> -->
									<a href="javascript:void(0)"class="btn btn-info btn-block btn-sm addBox" data-url="buscaCepTotem.do?key=<?php echo $_GET['key'] ;?>"><i class="fal fa-map-marked-alt f16" aria-hidden="true"></i>&nbsp; Busca CEP/Logradouro</a>
								
								</div>

							</div>

							<input type="hidden" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?=$buscaconsumidor[cep]?>">
							<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?=$buscaconsumidor[estado]?>">
							<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?=$buscaconsumidor[cidade]?>">
							<input type="hidden" name="DES_BAIRROC" id="DES_BAIRROC" value="<?=$buscaconsumidor[bairro]?>">
							<!-- <input type="hidden" name="DES_COMPLEM" id="DES_COMPLEM" value="<?=$buscaconsumidor[complemento]?>"> -->

				<?php 

						break;

						case 209:

				?>

							<div id="BAIRRO">

								<div class="col-md-3"></div>				
								
								<div class="push10"></div> 				
								
								<div class="col-md-3"></div>

								<div class="col-md-6 col-sm-10">
									<div class="form-group">
										<label for="inputName" class="control-label"></label>
										<input type="text" class="form-control input-lg text-center" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="150" autocomplete="off" value="<?=$buscaconsumidor[bairro]?>" placeholder="Bairro" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<input type="hidden" name="DES_ENDEREC" id="DES_ENDEREC" value="<?=$buscaconsumidor[endereco]?>">
							<input type="hidden" name="NUM_ENDEREC" id="NUM_ENDEREC" value="<?=$numero?>">
							<input type="hidden" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?=$buscaconsumidor[cep]?>">
							<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?=$buscaconsumidor[estado]?>">
							<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?=$buscaconsumidor[cidade]?>">
							<input type="hidden" name="DES_COMPLEM" id="DES_COMPLEM" value="<?=$buscaconsumidor[complemento]?>">

				<?php

						break;
					}

					if($cod_empresa != 114 && $cod_empresa != 115 && $cod_empresa != 116 && $cod_empresa != 209){

				?>

					<input type="hidden" name="DES_ENDEREC" id="DES_ENDEREC" value="<?=$buscaconsumidor[endereco]?>">
					<input type="hidden" name="NUM_ENDEREC" id="NUM_ENDEREC" value="<?=$numero?>">
					<input type="hidden" name="NUM_CEPOZOF" id="NUM_CEPOZOF" value="<?=$buscaconsumidor[cep]?>">
					<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?=$buscaconsumidor[estado]?>">
					<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?=$buscaconsumidor[cidade]?>">
					<input type="hidden" name="DES_BAIRROC" id="DES_BAIRROC" value="<?=$buscaconsumidor[bairro]?>">
					<input type="hidden" name="DES_COMPLEM" id="DES_COMPLEM" value="<?=$buscaconsumidor[complemento]?>">

				<?php

					}

				?>
				
				

				<div class="col-md-3">
				</div>				
				
				<div class="push30"></div>

				<?php 
				if ($log_ticket == 'S'){

					if($qrBuscaTkt['NRO_PRODUTOS'] == 0){ $disable = 'disabled'; }else{ $disable = " "; }
					//fnEscreve($disable);							
				?>	
				
				<div class="col-md-3">
				</div>	
				
				<div class="col-md-6">
					<a href="ticket.do?key=<?php echo $_GET['key']; ?>&url=<?php echo $urlTKT['url']; ?>&ch=3" name="TKT" id="TKT" <?=$disable?> class="btn btn-info btn-lg btn-block <?php echo $tktOff; ?> <?=$disable?>" tabindex="5"><i class="fa fa-ticket" aria-hidden="true"></i>&nbsp; Ticket de Ofertas</a>
				</div>
				
				<div class="col-md-3">
				</div>
				
				<div class="push10"></div>
								
				<?php 
				}
				?> 				
				
				<div class="col-md-6 col-md-offset-3">
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp; Continuar/Atualizar Cadastro</button>
				</div>
				
				<div class="col-md-3">
				</div>			
				
			</div><!-- /row -->

			<input type="hidden" class="form-control input-lg text-center cpfcnpj" name="cpf" id="cpf" value="<?php echo $cpf;?>" placeholder="CPF/CNPJ" required>
 	
		    <input type="hidden" name="tip_pesso" id="tip_pesso" value="<?php echo $buscaconsumidor['tipocliente'];?>">
		    <input type="hidden" name="c9" id="c9" value="<?=$buscaconsumidor['senha']?>">
			<input type="hidden" name="opcao" id="opcao" value="">
			<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
			<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
				
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
	
	$('input, textarea').placeholder();	
	
	var SPMaskBehavior = function (val) {
	  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	spOptions = {
	  onKeyPress: function(val, e, field, options) {
		  field.mask(SPMaskBehavior.apply({}, arguments), options);
		}
	};

	$(".txttelefone").mask("(00) 00000-0000");

	$('.sp_celphones').on('input propertychange paste', function (e) {
	    var reg = /^0+/gi;
	    if (this.value.match(reg)) {
	        this.value = this.value.replace(reg, '');
	    }
	});
	
	$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
	$('.data').mask('00/00/0000');
	</script>
	
    </body>
	
</html>
	