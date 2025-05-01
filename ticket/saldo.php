<?php
include '../_system/_functionsMain.php';
include './funWS/buscaConsumidor.php';
setlocale(LC_ALL, 'pt_BR.utf8');

//echo fnDebug('true');
if( $_SERVER['REQUEST_METHOD']=='GET' )
{
    if($_GET['c1']!="")
    { 
        $cpf=fnLimpaDoc($_GET['c1']);
        $parametros = fnDecode($_GET['key']);
        $arrayCampos = explode(";", $parametros);
        $sql="select c.*,cat.NOM_FAIXACAT from clientes c
                left join categoria_cliente cat ON case when c.COD_CATEGORIA > 0 then  cat.COD_CATEGORIA ELSE null END  = c.COD_CATEGORIA
                where   c.COD_EMPRESA = ".$arrayCampos['4']." and c.NUM_CGCECPF='".$cpf."' or c.num_cartao='".$cpf."'";
        $result = mysqli_fetch_assoc(mysqli_query(connTemp($arrayCampos['4'],''), $sql));
        $arrayNome = explode(" ", $result['NOM_CLIENTE']);
        $nome=$arrayNome[0];
    } else {
      $parametros = fnDecode($_GET['key']);
      $arrayCampos = explode(";", $parametros);
    // echo '<pre>';
    //  print_r($arrayCampos);
    // echo '<pre>';
     
     
      $sql="SELECT CL.*, UV.NOM_FANTASI,cat.NOM_FAIXACAT from clientes CL 
      		LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
                left join categoria_cliente cat ON case when CL.COD_CATEGORIA > 0 then  cat.COD_CATEGORIA ELSE null END  = CL.COD_CATEGORIA
      		where CL.COD_EMPRESA = ".$arrayCampos['4']." and CL.NUM_CGCECPF='".$arrayCampos[7]."'";

    //   echo '<pre>';
    //  print_r($sql);
    // echo '<pre>';
      $result=mysqli_fetch_assoc(mysqli_query(connTemp($arrayCampos['4'],''), $sql));
      if($result['NUM_CGCECPF']=='')
      {
          unset($result);
          unset($sql);
          
        $sql="SELECT CL.*, UV.NOM_FANTASI,cat.NOM_FAIXACAT from clientes CL 
        	LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
                left join categoria_cliente cat ON case when CL.COD_CATEGORIA > 0 then  cat.COD_CATEGORIA ELSE null END  = CL.COD_CATEGORIA
        	where CL.COD_EMPRESA = ".$arrayCampos['4']." and CL.NUM_CARTAO='".$arrayCampos[7]."'";
        $result=mysqli_fetch_assoc(mysqli_query(connTemp($arrayCampos['4'],''), $sql));   
      } 
      
       //echo $sql;
      $arrayNome = explode(" ", $result['NOM_CLIENTE']);
      $nome=$arrayNome[0];
      $dia_nascime = $result['DIA'];
      $mes_nascime = $result['MES'];
      $ano_nascime = $result['ANO'];
      $dia_hoje = date('d');
      $mes_hoje = date('m');
      $ano_hoje = date('Y');
      $dat_atualiza = $result['DAT_ALTERAC'];
      $log_estatus = $result['LOG_ESTATUS'];
      $mostraMsgCad = "none";
      $mostraMsgAniv = "none";
    
      $sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
		LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
		where COMUNICACAO_MODELO.cod_empresa = $arrayCampos[4]  
		AND COD_TIPCOMU = '4' 
		AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
		AND COMUNICACAO_MODELO.LOG_SALDO = 'S'
		AND COD_EXCLUSA = 0 
		ORDER BY COD_COMUNIC DESC LIMIT 1
		";													
		// echo($sql);
		$arrayQuery2 = mysqli_query(connTemp($arrayCampos[4],""),$sql);

		$count=0;

		$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery2);

		$today = date("Y-m-d");

		if(mysqli_num_rows($arrayQuery2) > 0){

			switch ($qrBuscaComunicacao['COD_CTRLENV']) {

				case '6':

					$date = date("Y-m-d", strtotime($today. "-6 months"));

				break;
				
				default:

					$date = date("Y-m-d", strtotime($today. "-1 year"));

				break;

				if($dat_atualiza >= $date && $dat_atualiza <= $today){
					$mostraMsgCad = 'block';
				}

			}

		} 

		$today = date("Y-m-d");
		$date = date("Y-m-d", strtotime($today. "+6 months"));

		// echo $today."<br/>";
		// echo $date;
    

      $sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
		LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
		where COMUNICACAO_MODELO.cod_empresa = $arrayCampos[4]  
		AND COD_TIPCOMU = '4' 
		AND COMUNICACAO_MODELO.COD_COMUNICACAO = '99' 
		AND COMUNICACAO_MODELO.LOG_SALDO = 'S'
		AND COD_EXCLUSA = 0 
		ORDER BY COD_COMUNIC DESC LIMIT 1
		";													
		// echo($sql);
		$arrayQuery = mysqli_query(connTemp($arrayCampos[4],""),$sql);

		$count=0;

		$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery);

		if(mysqli_num_rows($arrayQuery) > 0){

			$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

			$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($result['NOM_CLIENTE']))));                                
			$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
			$TEXTOENVIO=str_replace('<#SALDO>', fnValor($result['CREDITO_DISPONIVEL'],$casasDec), $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#NOMELOJA>',  $result['NOM_FANTASI'], $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#ANIVERSARIO>', $result['DAT_NASCIME'], $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($result['DAT_EXPIRA']), $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#EMAIL>', $result['DES_EMAILUS'], $TEXTOENVIO); 
			$msgsbtr=nl2br($TEXTOENVIO,true);                                
			$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
			$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);

			
			switch ($qrBuscaComunicacao['COD_CTRLENV']) {

				case '1':

					if($dia_hoje == $dia_nascime){
						$mostraMsgAniv = 'block';
					}

				break;

				case '30':

					if($mes_hoje == $mes_nascime){
						$mostraMsgAniv = 'block';
					}

				break;
				
				default:

					$firstDate = strtotime($ano_hoje.'-'.$mes_nascime.'-'.$dia_nascime);
					$secondDate = strtotime($ano_hoje.'-'.$mes_hoje.'-'.$dia_hoje);

					$result = date('oW', $firstDate) === date('oW', $secondDate) && date('Y', $firstDate) === date('Y', $secondDate);

					if($result){
						$mostraMsgAniv = 'block';
					}

				break;

			}

	    }

    }

     // echo "_".$msgsbtr."_";
     // echo "_".$mostraMsg."_";

     $nom_faixacat = $result['NOM_FAIXACAT'];
     //busca empresa
        $empresa_retorno="SELECT  TIP_RETORNO FROM empresas WHERE COD_EMPRESA = '".$arrayCampos['4']."'";
        $rstip=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$empresa_retorno));
        if ($rstip['TIP_RETORNO'] == 2){$casasDec = 2;}else { $casasDec = 0; }
     //select do saldo
       $saldo = "SELECT IFNULL((SELECT SUM(b.val_credito) 
						FROM   creditosdebitos b
						WHERE  b.cod_cliente = A.cod_cliente
							AND b.cod_statuscred <> 6            
							AND b.tip_credito = 'C'),0)+ IFNULL((SELECT SUM(b.val_credito) 
						FROM   creditosdebitos_bkp b
						WHERE  b.cod_cliente = A.cod_cliente
							AND b.cod_statuscred <> 6            
							AND b.tip_credito = 'C'),0) AS TOTAL_CREDITOS,
							
						IFNULL((SELECT SUM(b.val_credito) 
						FROM   creditosdebitos b
						WHERE  b.cod_cliente = A.cod_cliente 
						    AND b.cod_statuscred <> 6      
							AND b.tip_credito = 'D'),0)+IFNULL((SELECT SUM(b.val_credito) 
						FROM   creditosdebitos_bkp b
						WHERE  b.cod_cliente = A.cod_cliente 
					    	AND b.cod_statuscred <> 6      
							AND tip_credito = 'D'),0)  AS TOTAL_DEBITOS,
            
        (SELECT SUM(b.val_saldo) 
        FROM   creditosdebitos b 
        WHERE  b.cod_cliente = A.cod_cliente 
            AND b.tip_credito = 'C' 
            AND b.COD_STATUSCRED = 1 
            AND ((b.log_expira='S' AND date(b.dat_expira) >= curdate())OR(b.log_expira='N'))) AS CREDITO_DISPONIVEL, 
            
        (SELECT SUM(b.val_credito) 
        FROM   creditosdebitos b
        WHERE  b.cod_cliente = A.cod_cliente 
            AND b.tip_credito = 'C' 
            AND b.COD_STATUSCRED = 2 
            AND b.dat_expira > Now()) AS CREDITO_ALIBERAR,
            
        (SELECT SUM(b.val_credito) 
        FROM   creditosdebitos b 
        WHERE  b.cod_cliente = A.cod_cliente 
            AND b.tip_credito = 'C' 
            AND b.COD_STATUSCRED = 3 
            AND b.dat_expira > Now()) AS CREDITO_BLOQUEADO,
            
        (SELECT SUM(b.val_saldo) 
        FROM   creditosdebitos b
        WHERE  b.cod_cliente = A.cod_cliente 
            AND b.cod_statuscred<>6 
            AND b.tip_credito = 'C' 
            AND b.DAT_EXPIRA BETWEEN NOW() AND DATE_add(NOW(), INTERVAL 30 day)) AS CREDITO_EXPIRADOS 
      
      FROM CREDITOSDEBITOS A
      WHERE COD_CLIENTE=".$result['COD_CLIENTE']."
      AND COD_EMPRESA = ".$arrayCampos['4']."
      GROUP BY COD_CLIENTE";
	  $arrayQuery =  mysqli_fetch_assoc(mysqli_query(connTemp($arrayCampos['4'],''),$saldo));
     /*echo '<pre>';
     echo $saldo;
     echo '<pre>';    
      */
      $saldodisponivel = fnValor($arrayQuery['CREDITO_DISPONIVEL'],$casasDec);
      $TOTAL_CREDITOS = fnValor($arrayQuery['TOTAL_CREDITOS'],$casasDec);
      $TOTAL_DEBITOS = fnValor($arrayQuery['TOTAL_DEBITOS'],$casasDec);
      $CREDITO_ALIBERAR = fnValor($arrayQuery['CREDITO_ALIBERAR'],$casasDec);
      $CREDITO_EXPIRADOS = fnValor($arrayQuery['CREDITO_EXPIRADOS'],$casasDec);
      unset($SALDOTOTAL);
    //  $SALDOTOTAL =(float) $arrayQuery['CREDITO_DISPONIVEL']+$arrayQuery['CREDITO_ALIBERAR'];
      $SALDOTOTAL =(float) $arrayQuery['CREDITO_DISPONIVEL'];
      $SALDOTOTAL= fnValorSQLEXtrato($SALDOTOTAL, $casasDec);
}  

// $sql = "CALL total_wallet('$result[COD_CLIENTE]', '$arrayCampos[4]')";
						
// //fnEscreve($sql);
						
// $arrayQuery = mysqli_query(connTemp($arrayCampos[4],''),$sql);
// $qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
                      
	
// $saldodisponivel = fnValor($qrBuscaTotais['CREDITO_DISPONIVEL'],2);
// $TOTAL_CREDITOS = fnValor($qrBuscaTotais['TOTAL_CREDITOS'],2);
// $TOTAL_DEBITOS = fnValor($qrBuscaTotais['TOTAL_DEBITOS'],2);
// $CREDITO_ALIBERAR = fnValor($qrBuscaTotais['CREDITO_ALIBERAR'],2);
// $CREDITO_EXPIRADOS = fnValor($qrBuscaTotais['CREDITO_EXPIRADOS'],2);

// unset($SALDOTOTAL);
// //  $SALDOTOTAL =(float) $arrayQuery['CREDITO_DISPONIVEL']+$arrayQuery['CREDITO_ALIBERAR'];
// $SALDOTOTAL =(float) $qrBuscaTotais['CREDITO_DISPONIVEL'];
// $SALDOTOTAL= fnValorSQLEXtrato($SALDOTOTAL, $casasDec);

if($log_estatus == "N"){
	$SALDOTOTAL = 0;
}
       /* if($arrayCampos[7]=='01734200014')
        {  
            
            
            
            echo $arrayQuery['CREDITO_DISPONIVEL'].'<br>'.$arrayQuery['CREDITO_ALIBERAR'].'<br>';
            echo fnValor($SALDOTOTAL,$casasDec).'******'.$casasDec.'****'.$SALDOTOTAL;
        }  */  
//busca dados da tabela
$cod_empresa = $arrayCampos['4'];
$sql = "SELECT * FROM SITE_SALDO WHERE COD_EMPRESA = $cod_empresa ";
// echo($cod_empresa);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_saldo = $qrBuscaSiteTotem['COD_SALDO'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
	
	$log_totganho = $qrBuscaSiteTotem['LOG_TOTGANHO'];    
    $cor_totganho = $qrBuscaSiteTotem['COR_TOTGANHO'];
		
	$log_totresga = $qrBuscaSiteTotem['LOG_TOTRESGA'];    
    $cor_totresga = $qrBuscaSiteTotem['COR_TOTRESGA'];
			
	$log_liberar = $qrBuscaSiteTotem['LOG_LIBERAR'];    
    $cor_liberar = $qrBuscaSiteTotem['COR_LIBERAR'];

	$log_expirar = $qrBuscaSiteTotem['LOG_EXPIRAR'];    
    $cor_expirar = $qrBuscaSiteTotem['COR_EXPIRAR'];
		
	
} else {
    //default se vazio
    //fnEscreve("entrou else");
    
	$cod_saldo = 0;
	$des_logo = "";
	$des_alinham = "left";
	$des_imgback = "";

    $cor_backbar = "";
    $cor_backpag = "#f2f3f4";
    $cor_textos = "34495e";
	
	$log_totganho = "S";
	$cor_totganho = "1a4e95";

	$log_totresga = "S";
	$cor_totresga = "35aadc";

	$log_liberar = "S";
	$cor_liberar = "cc324b";

	$log_expirar = "S";
	$cor_expirar = "193042";

}

$sql = "SELECT DES_PROGRAMA FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
// echo($cod_empresa);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $des_programa = $qrBuscaSiteTotem['DES_PROGRAMA'];

}

$sql = "SELECT * FROM COMPLEMENTO_SALDO WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $arrayCampos[3]";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrComplem = mysqli_fetch_assoc($arrayQuery);

$temComplem = mysqli_num_rows($arrayQuery);

if (isset($qrComplem)) {

    $log_saldo = $qrComplem['LOG_SALDO'];

}else{

	$log_saldo = 'S';

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        
        
      <title>Saldo - Programa de Fidelidade</title>
        
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- The main CSS file -->
        <link href="css/main.css" rel="stylesheet">

        <!-- CSS file for your custom modifications -->
        <link href="css/custom.css" rel="stylesheet">

        <!--<link rel="shortcut icon" href="images/favicon.ico">-->

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->
    </head>
	
 	<!-- FONT -->
	<link href='https://fonts.googleapis.com/css?family=Lato:700,900' rel='stylesheet' type='text/css'>
 
  	<style>
	
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  color: #<?php echo $cor_textos; ?>;
	  padding: 0px;
	}
	
	/*.push5 {height: 5px; clear:both;} 
	.push10 {height: 10px; clear:both;} 
	.push20 {height: 20px; clear:both;}*/
	
	#main-content {
		background-color: transparent;
	}

	.nome{
		font-size: 50px;
	}	
	
	.categoria{
		background-color: #<?php echo $cor_textos; ?>;
		color: #ffffff;
		padding: 2px 5px 2px 5px;
		border-radius: 5px;
		font-weight: 300;
		font-size: 20px;
	}	
	
	.wrapper404 h2 {
		font-family: 'Lato', sans-serif;
		font-weight: 400;
		letter-spacing: -8px;
		font-size: 5em;
		margin: 0;
	}	

	.wrapper404 span {
		font-size: 0.6em;
	}
	
	p {
		padding: 0;
	}
	
	.wrapper404 p {
		font-weight: 700;
		font-size: 1.9em;
		margin: 0;
	}
	
	.p2 {
		font-weight: 700;
		font-size: 1.7em !important;
		margin: 0;
	}

	.p2 span {
		font-weight: 900;
		font-size:  1.1em !important;
		margin: 0;
	}
	
	#header .navbar .navbar-inner {
		background-color: #ecf0f1;
		border-radius: 0px;
	}	

	#header .navbar a.navbar-brand {
		padding: 18px 0;
		height: 121px;
	}
		
	/*-- bloco saldos --*/
	
	.blkSaldo {
		margin-top: 1.5em;
	}
	.blkSaldo-left{
		background:#<?php echo $cor_totganho; ?>;
		background-image:url('../images/lighten.png');
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
		background:#<?php echo $cor_totresga; ?>;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-right{
		background:#<?php echo $cor_liberar; ?>;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-lost{
		background:#<?php echo $cor_expirar; ?>;
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
		font-weight: 700;
		color: #fff;
		background-color: #<?php echo $cor_totganho; ?>;
		padding: 8px 0;
		margin-top: 5px;
	}

	.bottom-left-rounded{
		border-bottom-left-radius: 0.3em!important;
		-o-border-bottom-left-radius: 0.3em!important;
		-moz-border-bottom-left-radius: 0.3em!important;
	}

	span.resgatado {
		background-color: #<?php echo $cor_totresga; ?>;
		border-radius:0;
	}
	span.liberar {
		background-color: #<?php echo $cor_liberar; ?>;
		border-radius:0;
	}
	span.expirar {
		background-color: #<?php echo $cor_expirar; ?>;
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
	
	.logo-center {
		margin-left: auto;
		margin-right: auto;
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
	
	#header .navbar {
		border-bottom: 0;
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
	
	.row-centered {
		text-align:center;
	}
	.col-centered {
		display:inline-block;
		float:none;
		/* reset the text-align */
		text-align:left;
		/* inline-block space fix */
		margin-right:-4px;
		text-align: center;
		background-color: #ccc;
		border: 1px solid #ddd;
	}	
	

	<?php } ?>

<?php 
	if($cod_empresa == 206){
?>
		body {
			width: 750px!important;
			height: 580px!important
		}

		.ganho,
		.resgatado,
		.liberar,
		.expirar{
			font-size: 12px!important;
		}

		.nome{
			font-size: 8em; !important;
		}

		h2{
			font-size: 68px!important;
		}

		h3{
			font-size: 26px!important;
		}

		.voce-tem{
			font-size: 15px!important;
		}

		/*#header{
			height: 140px!important;
		}*/

		.navbar{
			margin-bottom: 20px;
		}

		#main-content{
			padding: 0!important;
		}
<?php
	}
?>
	.bloco{
		position: relative; 
		clear:both;
		margin-top: 5px;
		text-align:center;
		margin-right: auto;
		margin-left: auto;
		width: 360px;
	}

	.bloco-pai{
		background: #fff;
	}

	.form-group{
		margin-bottom: 0;
		padding-bottom: 0;
	}

	.push {clear:both;} 
	.push1 {height: 1px; clear:both;} 
	.push2 {height: 2px; clear:both;} 
	.push3 {height: 3px; clear:both;} 
	.push5 {height: 5px; clear:both;} 
	.push10 {height: 10px; clear:both;} 
	.push20 {height: 20px; clear:both;} 
	.push50 {height: 50px; clear:both;} 
	.push100 {height: 100px; clear:both;} 
	.borda {border:1px solid #000;}

	@media print 
    {	

        a[href]:after {
            content: none !important;
        }

        .hidden-print{
            display: none;
        }
    }
    section{
    	padding: 0;
    	margin: 0;
    }
    </style>
	
    <!-- Scrollspy set in the body -->
    <body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

<?php 

if($des_logo != ''){ 

?>
    
    <!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->

    <section id="header hidden-print">

		<img class="img-responsive hidden-print logo-<?php echo $des_alinham; ?>" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>">

    </section>

    <!--//////////////////////////////////////// end NAVIGATION BAR ////////////////////////////////////////-->

<?php

} 

if($log_saldo == 'S'){

?>

    

    <!--/////////////////////////////////////// CONTENT SECTION ////////////////////////////////////////-->

    <section id="main-content hidden-print">

        <div class="container hidden-print">

        	<div class="row" style="display: <?=$mostraMsgAniv?>"> 

        		<div class="col-xs-12">

					<div class="col-md-12 alert-warning top30 bottom30" role="alert" id="msgRetorno">
					<div class="push20"></div>
					 <span style="font-size: 20px; padding: 0 20px;"><?php echo $msgsbtr; ?></span>
					<div class="push20"></div>
					</div>
					
				</div>

			</div>

			<div class="row" style="display: <?=$mostraMsgCad?>">

				<div class="col-xs-12">

					<div class="alert-warning top30 bottom30" role="alert" id="msgRetorno">
					<div class="push20"></div>
					 <span style="font-size: 20px; padding: 0 20px;"><?php echo $msgsbtr; ?></span>
					<div class="push20"></div>
					</div>

				</div>

			</div>

			<div id="debug-container"></div>

            <header>
				
				<div class="wrapper404 center">
					<div class="push20"></div>
					<p>
					<!--	<span class="categoria" style="font-size: 0.4em;">cliente <?php echo $NOM_FAIXACAT; ?></span>-->
						<span class="nome" style="font-size: 0.7em;"><?php echo $nome; ?></span>
						<span class="voce-tem"> saldo disponível hoje,</span> &nbsp;
						
						<?php if($nom_faixacat != ""){ ?>
							<div style="height: 1px; clear:both;" ></div> 
							<span class="label label-info" style="font-size: .85em; padding: 2px 5px 2px 5px;">Cliente <b><?php echo $nom_faixacat; ?></b></span> 
							<div style="height: 5px; clear:both;" ></div> 
						<?php } ?>
						
					</p>
						
						<?php if($rstip['TIP_RETORNO']==2){ echo '<h2><span>R$ </span>';}
						else{echo '<h3>';}  echo  fnValor($SALDOTOTAL,$casasDec); ?></h3>
                                        
				</div>
				
				<div class="push10"></div>
					
                <!--<h3>Confira as vantagens que você <b>já ganhou</b></h3>-->
                <!--<p class="lead">The page you are trying to reach doesn't seem to exist. It might have moved or have been deleted. Let us take you back to the homepage.</p>-->
				
				<div class="blkSaldo row">
					<div class="col-lg-12">
						<?php if ($log_totganho == "S") {  ?>
						<div class="col-md-2"></div>
						<div class="col-md-2 blkSaldo-left">
							<h5 style="color: white" class=""><?php echo $TOTAL_CREDITOS;?></h5>						
							<span class="bottom-left-rounded ganho">Total Ganho</span>
						</div>
						<?php } if ($log_totresga == "S") {  ?>
						<div class="col-md-2 blkSaldo-left blkSaldo-middle">
							<h5 style="color: white"><?php echo $TOTAL_DEBITOS; ?></h5> 						
							<span  class="resgatado">Total Resgatado</span>
						</div>
						<?php } if ($log_liberar == "S") {  ?>
						 <div class="col-md-2 blkSaldo-left blkSaldo-right">
							<h5 style="color: white"><?php echo $CREDITO_ALIBERAR; ?></h5> 					   					   
						   <span class="liberar">A liberar</span>
						</div>
						<?php } if ($log_expirar == "S") {  ?>
						<div class="col-md-2 blkSaldo-left blkSaldo-lost">
							<h5 style="color: white"><?php echo $CREDITO_EXPIRADOS;?></h5>					   
						   <span class="expirar">Expirar 30 dias</span>
						</div>
						<div class="col-md-2"></div>
						<?php } ?>
						<div class="clearfix"></div>
					</div>
				</div>	
								
            </header>

        </div> 

    </section>

    <!--/////////////////////////////////////// end CONTENT SECTION ////////////////////////////////////////-->

<?php

} 

if($temComplem > 0){

?>

    <div class="bloco bloco-pai">
		<div class="bloco">

			<p><b>Termos <?=$des_programa?></b></p>

		<?php

			if($log_separa == 'S'){

				$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count=0;
				$tipo = "";
				while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

					if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
						$obrigaChk = "required";
					}else{
						$obrigaChk = "";
					}

					$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
								  WHERE COD_EMPRESA = $cod_empresa
								  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

					// fnEscreve($sqlTermos);

					$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

					$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

					while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
						// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

						$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
												'
													</label>
														
															<a class="addBox f16" 
															   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
															   data-title="'.$qrTermos['NOM_TERMO'].'"
															   style="cursor:pointer;">
															   '.$qrTermos['ABV_TERMO'].'
															</a>
														
												  	<label class="f16" for="TERMOS_'.$count.'">
												', 
												$des_bloco);
					}

			?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
								<label class="<?=$obrigaChk?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
									&nbsp;<?=$des_bloco?>
								</label>
							</div>
						</div>
						<div class="help-block with-errors"></div>
						<div class="push5"></div>
					</div>

			<?php

					$count++;

				}

				?>

				<div class="col-xs-10 col-xs-offset-1">
					<h5 data-toggle='tooltip' data-placement='bottom' data-original-title='Clique para editar'>
						<b>
							<a href="#" class="editable" 
							  	data-type='text' 
							  	data-title='Editar Texto' data-pk="<?=$cod_empresa?>" 
							  	data-name="TXT_COMUNICA"><?=$qrControle['TXT_COMUNICA']?>
						  		
						  	</a>
						</b>
					</h5>
				</div>
				<div class="push10"></div>

				<?php 

				$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				// $count=0;
				$tipo = "";
				while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

					if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
						$obrigaChk = "required";
					}else{
						$obrigaChk = "";
					}

					$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
								  WHERE COD_EMPRESA = $cod_empresa
								  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

					// fnEscreve($sqlTermos);

					$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

					$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

					while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
						// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

						$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
												'
													</label>
														
															<a class="addBox f16" 
															   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
															   data-title="'.$qrTermos['NOM_TERMO'].'"
															   style="cursor:pointer;">
															   '.$qrTermos['ABV_TERMO'].'
															</a>
														
												  	<label class="f16" for="TERMOS_'.$count.'">
												', 
												$des_bloco);
					}

			?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
								<label class="<?=$obrigaChk?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
									&nbsp;<?=$des_bloco?>
								</label>
							</div>
						</div>
						<div class="help-block with-errors"></div>
						<div class="push5"></div>
					</div>

			<?php

					$count++;

				}

			}else{
				
				$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' ORDER BY NUM_ORDENAC";
				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count=0;
				$tipo = "";
				while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

					if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
						$obrigaChk = "required";
					}else{
						$obrigaChk = "";
					}

					$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
								  WHERE COD_EMPRESA = $cod_empresa
								  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

					// fnEscreve($sqlTermos);

					$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

					$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

					while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
						// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

						$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
												'
													</label>
														
															<a class="addBox f16" 
															   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
															   data-title="'.$qrTermos['NOM_TERMO'].'"
															   style="cursor:pointer;">
															   '.$qrTermos['ABV_TERMO'].'
															</a>
														
												  	<label class="f16" for="TERMOS_'.$count.'">
												', 
												$des_bloco);
					}

				?>

					<div class="form-group">
						<div class="col-xs-12">
							<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
								<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
								<label class="<?=$obrigaChk?>"></label>
							</div>
							<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
								<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
									&nbsp;<?=$des_bloco?>
								</label>
							</div>
						</div>
						<div class="help-block with-errors"></div>
						<div class="push5"></div>
					</div>

				<?php

					$count++;

				}


			}	

		?>

			<div class="push50"></div>
			<div class="push50"></div>

			<div class="col-md-10 col-md-offset-1">
				<hr style="height: 3px!important; margin: 0px; border-color: #000!important;">
			</div>
														
			<p style="margin-bottom: 0px; "><b><?=$result[NOM_CLIENTE]?></b></p>
			<p style="margin-bottom: 0px; "><b>Cartão: <?=$result[NUM_CARTAO]?></b></p>
			<p><b><?=date("d/m/Y")?></b></p>
		</div>
	</div>

	<div class="col-md-12 text-center hidden-print">
		<a href="javascript:window.print();" class="btn btn-info" >Imprimir termos</a>
	</div>


<?php

} 

?>


    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/UAParser.js/0.7.12/ua-parser.min.js"></script>

    <script type="text/javascript">

  //   	$(function(){
    	
		// 	var width=800;
		// 		var height=600;
		// 		self.moveTo((screen.availwidth-width)/2,(screen.availheight-height)/2);
		// 		self.resizeTo(width,height);

		// }
		// window.moveTo(0, 0);
		// window.resizeTo(1024, 768);

    	<?php 
    		// if($cod_empresa == 206){
    	?>

    // 			var width=800;
				// var height=600;
				// self.moveTo((screen.availwidth-width)/2,(screen.availheight-height)/2);
				// self.resizeTo(width,height);


    			$(function(){
    				// getDebugInfo();
    			});

    			function getDebugInfo() {
				  var infoSections = [];
				  var parser = new UAParser();
				  var userOs = parser.getOS();
				  var userDevice = parser.getDevice();
				  var userBrowser = parser.getBrowser();
				  var debugContainer = document.getElementById("debug-container");

				  if (userOs && userOs.name && userOs.version) {
				    infoSections.push({ name: 'OS', value: userOs.name + ' ' + userOs.version});
				  }

				  if (userBrowser && userBrowser.name && userBrowser.version) {
				    infoSections.push({ name: 'Browser', value: userBrowser.name + ' ' + userBrowser.version});
				  }

				  if (userDevice && userDevice.vendor && userDevice.model) {
				    infoSections.push({ name: 'Device', value: userBrowser.vendor + ' ' + userBrowser.model});
				  } else {
				    infoSections.push({ name: 'Device', value: 'N/A'});
				  }

				  if (window) {
				    if (window.screen) {
				      infoSections.push({ name: 'Screen resolution', value: window.screen.width + 'x' + window.screen.height});
				      infoSections.push({ name: 'Available screen space', value: window.screen.availWidth + 'x' + window.screen.availHeight});
				    }

				    infoSections.push({ name: 'Browser window size', value: window.innerWidth + 'x' + window.innerHeight});
				    infoSections.push({ name: 'Device pixel ratio', value: window.devicePixelRatio });
				  }

				  //Old-school JS without jQuery or another framework, just for fun
				  while (debugContainer.hasChildNodes()) {
				    debugContainer.removeChild(debugContainer.lastChild);
				  }

				  for (var i = 0; i < infoSections.length; i++) {
				    var debugInfo = document.createElement("div");
				    debugInfo.setAttribute("class", "debug-info");
				    var debugName = document.createElement("div");
				    debugName.setAttribute("class", "debug-name");
				    debugName.appendChild(document.createTextNode(infoSections[i].name));
				    var debugValue = document.createElement("div");
				    debugValue.setAttribute("class", "debug-value");
				    debugValue.appendChild(document.createTextNode(infoSections[i].value)); 
				    debugInfo.appendChild(debugName);
				    debugInfo.appendChild(debugValue);
				    debugContainer.appendChild(debugInfo);
				  }
				}

				window.addEventListener("resize", function () {
				  // This will fire each time the window is resized
				  // Usually a good idea to wrap this in a debounce method, like https://underscorejs.org/#debounce
				  // getDebugInfo();
				}, false);

				window.addEventListener("orientationchange", function () {
				  // getDebugInfo();
				}, false);

    	<?php
    		// } 
    	?>

    </script>

  </body>
</html>
