<?php 
include_once '_system/_functionsMain.php';

$key_url = $_GET['key'];

if(is_numeric(fnDecode($key_url))){
  $key_url = base64_encode($key_url);
}

if(is_numeric(fnDecode(base64_decode($key_url))))
{    
    
   $key = fnDecode(base64_decode($key_url));
   // $_SESSION["EMPRESA_COD"]= fnDecode(base64_decode($key_url));

   // if($key == 103){
   //  echo "valle";
   //  exit();
   // }

   // fnEscreve($_SESSION["EMPRESA_COD"]);
  //pegar um usuario senha e loja para montar a string da chave;
   $sqlBUSCA="SELECT COD_USUARIO,
                LOG_USUARIO,
                DES_SENHAUS,
                COD_UNIVEND,
                COD_EMPRESA
         FROM usuarios 
         WHERE cod_empresa=$key
               AND COD_TPUSUARIO=10 AND 
               COD_EXCLUSA = 0 LIMIT 1";
   $resultuser=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlBUSCA));
   $COD_UNIVENDARRAY = explode(",", $resultuser['COD_UNIVEND']); 
   
   $arrayCampos=$resultuser['LOG_USUARIO'].';'.fnDecode($resultuser['DES_SENHAUS']).';'.$resultuser['COD_EMPRESA'].';'.$COD_UNIVENDARRAY['0'];
   $key= fnEncode($arrayCampos);
   // $_SESSION["KEY"]= fnDecode($key);
 
    $timeout = 60; // Tempo da sessao em segundos 
    // Verifica se existe o parametro timeout
    // if(isset($_SESSION['timeout'])) {
        // Calcula o tempo que ja se passou desde a cricao da sessao
        // $duracao = time() - (int) $_SESSION['timeout'];  
      	// Verifica se ja expirou o tempo da sessao
        // if($duracao > $timeout) {
            // Destroi a sessao e cria uma nova
            //session_destroy();
           // session_start();
            
        // }
    // }
     
    // Atualiza o timeout.
    // $_SESSION['timeout'] = time();

// fnEscreve('if');
      
}else{

  // fnEscreve('else');
      
       // $_SESSION["KEY"]= fnDecode('Ms£KMIDVxjfNMbzY20SH4LsnjBwg8QB80yME7pKkfoEo¢');
                   
}


/// echo '<pre>';
// print_r($key);
// echo '</pre>';
//opws.valleposto;marka;103;96476



$arrayCampos = explode(";", $arrayCampos);

$dadoslogin = array(
	'0'=>$arrayCampos[0],
	'1'=>$arrayCampos[1],
	'2'=>$arrayCampos[3],
	'3'=>'maquina',
	'4'=>$arrayCampos[2]
);

// echo "<pre>";
// print_r($dadoslogin);
// echo "</pre>";

$cod_empresa = $arrayCampos[2];

$sql = "SELECT NOM_FANTASI, 
                TIP_RETORNO, 
                LOG_BLOQUEIAPJ, 
                TIP_CAMPANHA, 
                TIP_SENHA, 
                MIN_SENHA, 
                MAX_SENHA, 
                REQ_SENHA,
                TIP_ENVIO,
                LOG_RECUPERA
        FROM empresas 
        where COD_EMPRESA = $cod_empresa";
// fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
  $tip_campanha = $qrBuscaEmpresa['TIP_CAMPANHA'];
  $nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
  $log_bloqueiapj = $qrBuscaEmpresa['LOG_BLOQUEIAPJ'];
  $tip_senha = $qrBuscaEmpresa['TIP_SENHA'];
  $min_senha = $qrBuscaEmpresa['MIN_SENHA'];
  $max_senha = $qrBuscaEmpresa['MAX_SENHA'];
  $req_senha = $qrBuscaEmpresa['REQ_SENHA'];
  $tip_envio = $qrBuscaEmpresa['TIP_ENVIO'];
  $log_recupera = $qrBuscaEmpresa['LOG_RECUPERA'];
	if ($tip_retorno == 2){
		$casasDec = 2;
	}else { 
		$casasDec = 0; 
	}
}

//busca dados da tabela
$sql = "SELECT * FROM TOTEM_APP WHERE COD_EMPRESA = $cod_empresa";
// echo($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotemApp = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotemApp)) {
  
    $cod_app = $qrBuscaSiteTotemApp['COD_APP'];
    $des_logo = $qrBuscaSiteTotemApp['DES_LOGO'];
    $des_imgback = $qrBuscaSiteTotemApp['DES_IMGBACK'];
  
  $cor_fullpag = $qrBuscaSiteTotemApp['COR_FULLPAG'];
    $cor_textfull = $qrBuscaSiteTotemApp['COR_TEXTFULL'];
  
    $cor_backbar = $qrBuscaSiteTotemApp['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotemApp['COR_BACKPAG'];
  
    $cor_titulos = $qrBuscaSiteTotemApp['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotemApp['COR_TEXTOS'];
  
    $cor_botao = $qrBuscaSiteTotemApp['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotemApp['COR_BOTAOON'];

    $log_colunas = $qrBuscaSiteTotemApp['LOG_COLUNAS'];
    $log_ofertas = $qrBuscaSiteTotemApp['LOG_OFERTAS'];
    $log_jornal = $qrBuscaSiteTotemApp['LOG_JORNAL'];
    $log_habito = $qrBuscaSiteTotemApp['LOG_HABITO'];
    $log_dados = $qrBuscaSiteTotemApp['LOG_DADOS'];
    $log_extrato = $qrBuscaSiteTotemApp['LOG_EXTRATO'];
    $log_premios = $qrBuscaSiteTotemApp['LOG_PREMIOS'];
    $log_enderecos = $qrBuscaSiteTotemApp['LOG_ENDERECOS'];
    $log_parceiros = $qrBuscaSiteTotemApp['LOG_PARCEIROS'];
    $log_comunica = $qrBuscaSiteTotemApp['LOG_COMUNICA'];
    $log_amigos = $qrBuscaSiteTotemApp['LOG_AMIGOS'];
    $log_brindes = $qrBuscaSiteTotemApp['LOG_BRINDES'];
    $log_bannerhome = $qrBuscaSiteTotemApp['LOG_BANNERHOME'];
    $log_bannerlista = $qrBuscaSiteTotemApp['LOG_BANNERLISTA'];
    $des_termosapp = $qrBuscaSiteTotemApp['DES_TERMOSAPP'];
    $log_sombra = $qrBuscaSiteTotemApp['LOG_SOMBRA'];
    $log_linha = $qrBuscaSiteTotemApp['LOG_LINHA'];
    $log_round = $qrBuscaSiteTotemApp['LOG_ROUND'];
    $log_lgpd_lt = $qrBuscaSiteTotemApp['LOG_LGPD_LT'];
    $log_expira = $qrBuscaSiteTotemApp['LOG_EXPIRA'];

    if($qrBuscaSiteTotemApp['LOG_COLUNAS']=='S'){ 
      $chk_colunas = "checked";
      $disp_dupla = "block";
      $disp_unica = "none";
    }else{ 
      $chk_colunas = ""; 
      $disp_dupla = "none";
      $disp_unica = "block";
    }

    if($qrBuscaSiteTotemApp['LOG_OFERTAS']=='S'){ 
      $chk_ofertas = "checked";
      $disp_ofertas = "block";
    }else{ 
      $chk_ofertas = ""; 
      $disp_ofertas = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_JORNAL']=='S'){ 
      $chk_jornal = "checked";
      $disp_jornal = "block";
    }else{ 
      $chk_jornal = ""; 
      $disp_jornal = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_HABITO']=='S'){ 
      $chk_habito = "checked";
      $disp_habito = "block";
    }else{ 
      $chk_habito = ""; 
      $disp_habito = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_DADOS']=='S'){ 
      $chk_dados = "checked";
      $disp_dados = "block";
    }else{ 
      $chk_dados = ""; 
      $disp_dados = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_EXTRATO']=='S'){ 
      $chk_extrato = "checked";
      $disp_extrato = "block";
    }else{ 
      $chk_extrato = ""; 
      $disp_extrato = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_PREMIOS']=='S'){ 
      $chk_premios = "checked";
      $disp_premios = "block";
    }else{ 
      $chk_premios = ""; 
      $disp_premios = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_ENDERECOS']=='S'){ 
      $chk_enderecos = "checked";
      $disp_enderecos = "block";
    }else{ 
      $chk_enderecos = ""; 
      $disp_enderecos = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_PARCEIROS']=='S'){ 
      $chk_parceiros = "checked";
      $disp_parceiros = "block";
    }else{ 
      $chk_parceiros = ""; 
      $disp_parceiros = "none";
    }

    if($qrBuscaSiteTotemApp['LOG_COMUNICA']=='S'){ 
      $chk_comunica = "checked";
      $disp_comunica = "block";
    }else{ 
      $chk_comunica = ""; 
      $disp_comunica = "none";
    }

    if ($qrBuscaSiteTotemApp['LOG_AMIGOS'] == 'S') {
      $chk_amigos = "checked";
      $disp_amigos = "block";
    } else {
      $chk_amigos = "";
      $disp_amigos = "none";
    }

    if ($qrBuscaSiteTotemApp['LOG_BRINDES'] == 'S') {
      $chk_brindes = "checked";
      $disp_brindes = "block";
    } else {
      $chk_brindes = "";
      $disp_brindes = "none";
  }

    if($qrBuscaSiteTotemApp['LOG_MENSAGEM']=='S'){ 
      $chk_mensagem = "checked";
      $disp_mensagem = "block";
    }else{ 
      $chk_mensagem = ""; 
      $disp_mensagem = "none";
    }

    
    $chk_bannerhome = "";
    $chk_bannerlista = "";
    $chk_token = "";
    $chk_veiculo = "";
    $disp_token = "none";
    $disp_veiculo = "none";

    if ($qrBuscaSiteTotemApp['LOG_BANNERHOME'] == 'S') {
      $chk_bannerhome = "checked";
    }

    if ($qrBuscaSiteTotemApp['LOG_BANNERLISTA'] == 'S') {
      $chk_bannerlista = "checked";
    }

    if ($qrBuscaSiteTotemApp['LOG_TOKEN'] == 'S') {
      $chk_token = "checked";
      $disp_token = "block";
    }

    if ($qrBuscaSiteTotemApp['LOG_VEICULO'] == 'S') {
      $chk_veiculo = "checked";
      $disp_veiculo = "block";
    }

  list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");
  list($r_cor_fullpag, $g_cor_fullpag, $b_cor_fullpag) = sscanf($cor_fullpag, "#%02x%02x%02x");

  if($r_cor_backpag > 50){
      $r = ($r_cor_backpag-50);
  }else{
      $r =($r_cor_backpag+50);
      if($r_cor_backpag < 30){
          $r = $r_cor_backpag;
      }
  }
  if($g_cor_backpag > 50){
      $g = ($g_cor_backpag-50);
  }else{
      $g =($g_cor_backpag+50);
      if($g_cor_backpag < 30){
          $g = $g_cor_backpag;
      }
  }
  if($b_cor_backpag > 50){
      $b = ($b_cor_backpag-50);
  }else{
      $b =($b_cor_backpag+50);
      if($b_cor_backpag < 30){
          $b = $b_cor_backpag;
      }
  }

  if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
      $r =($r_cor_backpag+40);
      $g =($g_cor_backpag+40);
      $b =($b_cor_backpag+40);
  }
  
}


?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">

        <!-- <title><?=$nom_fantasi?></title> -->
		
		<?php include "cssLib.php"; ?>
	</head>

  <style type="text/css">
    body{
        padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);

    }
    .tabelaCel1>.table-bordered>tbody>tr>td{
        border-color: <?=$cor_textfull?>;
    }
    .textoCel1,.texto2Cel1{
        color: <?=$cor_textfull?>;
    }
    .shadow{
        -webkit-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        -moz-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        width: 100%;
        border-radius: 5px;
    }

    .shadow2{
        -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        -moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
        width: 100%;
        border-radius: 30px;
    }

    .outline{
        border: 1px solid rgba(<?= $r_cor_fullpag ?>, <?= $g_cor_fullpag ?>, <?= $b_cor_fullpag ?>, 0.2) !important;
        background-color: rgba(<?= $r_cor_fullpag ?>, <?= $g_cor_fullpag ?>, <?= $b_cor_fullpag ?>, 0.1) !important;
        border-radius: 5px;
    }
    .outline .fal{
        color: rgba(<?= $r_cor_fullpag ?>, <?= $g_cor_fullpag ?>, <?= $b_cor_fullpag ?>, 1) !important;
    }
    .outline p{
        font-size: 16px;
    }
    .separador{
        border: unset;
        max-width: unset;
        width: unset;
        border-top: 1px solid <?=$cor_textfull?>; 
        margin: 0; 
        padding: 0; 
    }
    .bloco-saldo{
/*                              background-color: <?= $cor_fullpag ?>77;*/
        background-color: rgba(<?= $r_cor_fullpag ?>, <?= $g_cor_fullpag ?>, <?= $b_cor_fullpag ?>, 1) !important;
        filter: brightness(90%);
        color: #fff;
        padding: 15px;
        width: 100%;
    }
    .d-inline-flex{
        display: inline-flex; 
    }
    .d-flex{
        display: flex;
        flex-wrap: wrap;
    }
    .d-centered{
        align-content: center;
        align-items: center;
        justify-content: center; 
    }
    .space-between-centered{
        align-content: center; 
        justify-content: space-between; 
        align-items: center;
    }
    .btn-primary{
      background-color: <?=$cor_botao?>!important;
      border-color: <?=$cor_botao?>!important;
      color: <?=$cor_textfull?>!important;
    }
    .btn-info{
      background-color: <?=$cor_backpag?>!important;
      border-color: <?=$cor_backpag?>!important;
      color: <?=$cor_fullpag?>!important;
      font-size: 16px;
      font-weight: 600;
    }
    .btn-info .fal{
      font-size: 24px;
    }
    .btn-primary:hover{
      background-color: <?=$cor_botaoon?>!important;
      border-color: <?=$cor_botaoon?>!important;
    }
    .line-break {
      width: 100%;
    }
    .pull-right {
        float: right !important;
    }
    select.input-sm {
        width: 100%;
    }
    .f14{
        font-size: 14px;
    }
    .f14b{
        font-size: 14px;
        font-weight: 600;
    }
    .f16{
        font-size: 16px;
    }
    .f16b{
        font-size: 16px;
        font-weight: 600;
    }
    .f32b{
        font-size: 32px;
        font-weight: 600;
    }

  /* TOTEM  */

#caixaImg {
   background: unset!important;
   padding: 0!important;
}

#caixaImg, #caixaForm{
  height: unset!important;
}

#caixaForm{
  height: 84%!important; 
  overflow: auto;
}

.desktop{
  display: none!important;
}
.tablet{
  display: block!important;
}
.mobile{
  display: none!important;
}

@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  } 
    
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
 
  .desktop{
    display: none!important;
  }
  .tablet{
    display: block!important;
  }
  .mobile{
    display: none!important;
  } 
}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }

  
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }

     
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

  .desktop{
    display: block!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: none!important;
  }
     
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }


     
}

@media (max-height: 824px) and (max-width: 416px){

  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }

} 

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
  .desktop{
    display: none!important;
  }
  .tablet{
    display: none!important;
  }
  .mobile{
    display: block!important;
  }
    
}

.modal-dialog{
  padding: 0!important;
  margin: 0!important;
}

.modal-content{
  height: 100vh !important;
  width: 100vw !important;
}

.modal-body{
  height: 100% !important;
  width: 100% !important;
  /*overflow-x: hidden;*/
}

  </style>

	<body class="bgColor" data-gr-c-s-loaded="true" style="background-color: <?=$cor_backpag?>">