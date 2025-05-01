<?php include "_system/_functionsMain.php"; 
include_once './totem/funWS/buscaConsumidor.php';
include_once './totem/funWS/buscaConsumidorCNPJ.php';
include_once './totem/funWS/saldo.php';
//echo fnDebug('true');
//fnMostraForm();

$cartao = "";
@$cpf=$_REQUEST['c1'];
if($_REQUEST['c10'] && $_REQUEST['c10'] != ""){
	$cpf = $_REQUEST['c10'];
	$cartao = "true";
}
@$COD_UNIVEND=$_REQUEST['COD_UNIVEND'];
@$cod_empresa=$_REQUEST['COD_EMPRESA'];

@$COD_USUARIO=$_SESSION["SYS_COD_USUARIO"];
@$NOM_USUARIO=$_SESSION["SYS_NOM_USUARIO"];

$sql = "select LOG_USUARIO, DES_SENHAUS,COD_UNIVEND from usuarios where cod_empresa = ".$cod_empresa." AND COD_TPUSUARIO=10 and DAT_EXCLUSA is null limit 1 ";
//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
$log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
$des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];	
	
$arrayCampos=array( '0'=>$log_usuario,
                    '1'=> fnDecode($des_senhaus),
                    '2'=>$COD_UNIVEND,
                    '3'=>$_SESSION["USU_COD_USUARIO"],
                    '4'=>$cod_empresa
                    );

$urltotem = fnEncode(implode(';', $arrayCampos));

// fnEscreve($cpf); 
                   
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

if($cod_cliente != 0){
	$sqlCli = "SELECT * FROM CLIENTES 
	       WHERE COD_EMPRESA = $cod_empresa
	       AND COD_CLIENTE = $cod_cliente";
}else{
	$sqlCli = "SELECT * FROM CLIENTES 
	       WHERE COD_EMPRESA = $cod_empresa
	       AND ($whereSql)
	       ORDER BY 1 LIMIT 1";
}		

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

if ($qrCli[NUM_CGCECPF] != "") {
	$k_num_cgcecpf = fnLimpaDoc($qrCli[NUM_CGCECPF]);
}

if ($qrCli[NUM_CGCECPF] != "") {
	$k_num_cartao = fnLimpaDoc($qrCli[NUM_CARTAO]);
}

if ($qrCli[NUM_CGCECPF] != "") {
	$k_num_celular = fnLimpaDoc($qrCli[NUM_CELULAR]);
}

$cod_cliente = fnLimpaCampoZero($qrCli[COD_CLIENTE]);
$log_termo = $qrCli[LOG_TERMO];
$des_token = $qrCli[DES_TOKEN];

// $cpf = $k_num_cgcecpf;

$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);


$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayFields);

$log_cadtoken = $qrCampos[LOG_CADTOKEN];

// fnconsulta_V2($qrCampos[COD_CHAVECO], $dado, $arrayCampos);

switch ($qrCampos[COD_CHAVECO]) {

	case 2:
		$buscaconsumidor = fnconsulta_V2($qrCampos[COD_CHAVECO], $k_num_cartao, $arrayCampos);
	break;
	case 3:
		$buscaconsumidor = fnconsulta_V2($qrCampos[COD_CHAVECO], fnLimpaDoc($k_num_celular), $arrayCampos);
	break;

	default:

		if(strlen($k_num_cgcecpf) <= '11'){

			// echo 'if def';

            $buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf,'F'), $arrayCampos);
            
        }else{

        	// echo 'else def';

            $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf,'J'), $arrayCampos); 
            
		}

	break;

}

// if($cod_empresa = 7){

	// echo '<pre>';
	// print_r($buscaconsumidor);
	// // print_r($buscaconsumidor);
	// echo '</pre>';
	// exit();
    
// }

// $dado_consulta = "<cpf>$cpf</cpf>\r\n\t";

if($buscaconsumidor['cpf']!='00000000000'){

	$cpf=$buscaconsumidor['cpf'];

}else{
	$cpf = $k_num_cgcecpf;
	$buscaconsumidor['nome'] = "";
} 


if($buscaconsumidor['msg']=='CPF digitado é inválido!')
{
//  header("Refresh:0;url=http://adm.bunker.mk/action.do?mod=apC2A333ahM1VYcC2A2&id=QunXraEOVrgC2A2&erro=1");   
echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.fnurl ().'/action.do?mod='. fnEncode('1240').'&id='.fnEncode($cod_empresa).'&erro=1">';

} 
   
// busca info empresa
$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS, NUM_DECIMAIS_B, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

if($qrEmp['TIP_RETORNO'] == 1){
	$casasDec = 0;
}else{
	$casasDec = $qrEmp['NUM_DECIMAIS_B'];
}

$log_cadtoken = $qrEmp['LOG_CADTOKEN'];

$dev = $_GET['dev'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];

$des_img = $qrControle['DES_IMG'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_imgmob = $qrControle['DES_IMGMOB'];

if($cod_cliente == 0){
	$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";
}else{
	$andOpc = "";
}


if($k_num_cartao != ""){
	$buscaconsumidor['cartao'] = $k_num_cartao;
}else{
	$k_num_cartao = $buscaconsumidor['cartao'];
}

if($k_num_celular != ""){
	$buscaconsumidor['telcelular'] = $k_num_celular;
}else{
	$k_num_celular = $buscaconsumidor['telcelular'];
}

if($k_num_cgcecpf != ""){
	$buscaconsumidor['cpf'] = $k_num_cgcecpf;
}else{
	$k_num_cgcecpf = $buscaconsumidor['cpf'];
}

if($k_dat_nascime != ""){
	$buscaconsumidor['datanascimento'] = $k_dat_nascime;
}else{
	$k_dat_nascime = $buscaconsumidor['datanascimento'];
}

if($k_des_emailus != ""){
	$buscaconsumidor['email'] = $k_des_emailus;
}else{
	$k_des_emailus = $buscaconsumidor['email'];
}

if($buscaconsumidor['cpf'] == "00000000000"){
	$buscaconsumidor['cpf'] = "";
}

unset($_POST);

?>


<div class="push"></div>

<!-- -------------- bloco saldo  --------------- -->
<?php
        //busca saldos de resgate
        $cod_clientesql="select COD_CLIENTE from clientes where cod_empresa=$cod_empresa and NUM_CGCECPF='".fnLimpaDoc($cpf)."'";
       // fnEscreve($cod_clientesql);
        $cod_cliereturn=mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''), $cod_clientesql));
	$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('".$cod_cliereturn['COD_CLIENTE']."') ";
	//fnEscreve($sql);
	$qrBuscaSaldoResgate = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
        
        
	if (isset($qrBuscaSaldoResgate)){		
		$credito_disponivel = $qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $credito_aliberar = $qrBuscaSaldoResgate['TOTAL_CREDITO']-$qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
                $saldototal =$qrBuscaSaldoResgate['TOTAL_CREDITO'];  
	}

   
?>

<div class="blkSaldo row">
	<div class="col-md-3 "></div>

	<div class="col-md-6" style="padding: 0 25px;">
			<?php 
				if ( !empty($buscaconsumidor['datanascimento'])){
					$niver = $buscaconsumidor['datanascimento'];
					$arrayNiver = explode('/', $niver);    
					$mes_atual = date("m");
					$mes_niver = $arrayNiver[1];
					//fnEscreve($mes_niver);
					//fnEscreve($mes_atual);
				}
				
				if ($mes_atual == $mes_niver && $cod_cliente != 0){
			?>
			
			<div class="alert alert-warning top30" role="alert" id="msgRetorno">
				<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-gift fa-2x" aria-hidden="true"></i> &nbsp; <span class="f18">Mês de aniversário do cliente </span>
			</div>
			
			<?php 
				}	
			?>
			
			<div class="push"></div>
	
			<div class="col-md-4 blkSaldo-left">
				<h3 style="color: white; margin: auto;" class=""><?php echo fnValor($credito_disponivel,$casasDec);?></h3>						
				<span>Saldo Disponível</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-middle">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($credito_aliberar,$casasDec); ?></h3> 						
				<span  class="resgatado">Saldo a Liberar</span>
			</div>
			
			<div class="col-md-4 blkSaldo-left blkSaldo-lost">
				<h3 style="color: white; margin: auto;"><?php echo fnValor($saldototal,$casasDec); ?></h3> 			   
			   <span class="liberar">Saldo Total</span>
			</div>						
	</div>
</div>	

<div class="push20"></div>

<!-- -------------- bloco saldo  --------------- -->	

<style type="text/css">
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center; 
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
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
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
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
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
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
		 padding: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; */
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
		background: #FFF url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		-webkit-background-size: 100% 100%;
		height: 360px;
	}
}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  /*background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; */
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

	.input-sm, .chosen-single{
		font-size: 20px!important;
	}

	.logo-center{
		margin-left: auto;
		margin-right: auto;
	}
</style>

<script type="text/javascript">
	$(".chosen-select-deselect").chosen();
</script>						

<div class="col-md-6 col-md-offset-3">

		<div class="push20"></div>

		
		<?php

			$andOpc = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'OPC'";

			if($cod_cliente != 0){
				$andOpc = "";
			}

			if($cod_cliente == 0 && $log_cadtoken == 'S'){

				$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG = 'TKN'";
				// $mostraSenha = 0;

			}else{

				$camposIniciais = "AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'KEY'
								   AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'CAD'
								   AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG != 'TKN'
								   $andOpc";
			}

			$sqlCampos = "SELECT NOM_CAMPOOBG, 
								 NOM_CAMPOOBG, 
								 DES_CAMPOOBG, 
								 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
								 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
								 (SELECT COUNT(DISTINCT MCI.TIP_CAMPOOBG) 
									FROM matriz_campo_integracao MCI
									WHERE MCI.TIP_CAMPOOBG IN('OBG','OPC') 
									AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
									AND MCI.COD_EMPRESA = $cod_empresa) OBRIGATORIO,
								 COL_MD, 
								 COL_XS, 
								 CLASSE_INPUT, 
								 CLASSE_DIV 
							FROM MATRIZ_CAMPO_INTEGRACAO                         
							LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
							WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
							AND MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG != 24
							$camposIniciais
							ORDER BY COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

			$arrayCampos = mysqli_query($connAdm->connAdm(),$sqlCampos);

			$nroCampos = mysqli_num_rows($arrayCampos);

			// echo($sqlCampos);

			$lastField = "";

			while($qrCampos = mysqli_fetch_assoc($arrayCampos)){

				// echo "<pre>";
				// print_r($qrCampos);
				// echo "</pre>";

				$colMd = $qrCampos[COL_MD];
				$colXs = $qrCampos[COL_XS];
				$dataError = "";

				$required = "";
				// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

				if($lastField == ""){
					$lastField = $qrCampos[NOM_CAMPOOBG];
				}else if($lastField == $qrCampos[NOM_CAMPOOBG]){
					continue;
				}else{
					$lastField = $qrCampos[NOM_CAMPOOBG];
				}

				if($qrCampos[OBRIGATORIO] > 0){
					$required = "required";
					$dataError = "data-error='Campo obrigatório'";
				}

				// echo "$qrCampos[CAT_CAMPO]";

				if($colMd == "" || $colMd == 0){
					$colMd = 12;
				}

				if($colXs == "" || $colXs == 0){
					$colXs = 12;
				}

				switch ($qrCampos[DES_CAMPOOBG]) {

					case 'NOM_CLIENTE':

						$dado = $buscaconsumidor['nome'];

					break;
					
					case 'COD_SEXOPES':

						$dado = $buscaconsumidor['sexo'];

					break;
					
					case 'DES_EMAILUS':

						$dado = $buscaconsumidor['email'];

					break;
					
					case 'NUM_CELULAR':

						$dado = $buscaconsumidor['telcelular'];

					break;
					
					case 'NUM_CARTAO':

						$dado = $buscaconsumidor['cartao'];

					break;

					case 'NUM_CGCECPF':

						$dado = $buscaconsumidor['cpf'];

					break;
					
					
					case 'DAT_NASCIME':

						$dado = $buscaconsumidor['datanascimento'];

					break;
					
					case 'COD_PROFISS':

						$dado = $buscaconsumidor['profissao'];

					break;
					
					case 'COD_ATENDENTE':

						$dado = $buscaconsumidor['codatendente'];

					break;
					
					case 'DES_SENHAUS':

						$dado = $buscaconsumidor['senha'];

					break;
					
					case 'DES_ENDEREC':

						$dado = $buscaconsumidor['endereco'];

					break;
					
					case 'NUM_ENDEREC':

						$dado = $buscaconsumidor['numero'];

					break;
					
					case 'NUM_CEPOZOF':

						$dado = $buscaconsumidor['cep'];

					break;
					
					case 'estado':

						$dado = $buscaconsumidor['estado'];

					break;
					
					case 'NOM_CIDADEC':

						$dado = $buscaconsumidor['cidade'];

					break;
					
					case 'DES_BAIRROC':

						$dado = $buscaconsumidor['bairro'];

					break;
					
					case 'DES_COMPLEM':

						$dado = $buscaconsumidor['complemento'];

					break;

					default:

						$dado = "";

					break;

				}

				switch ($qrCampos[TIPO_DADO]) {

					case 'Data':

						?>
							<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
									<input type="tel" placeholder="DD/MM/AAAA" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?> data" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" maxlenght="10" data-minlength="10" data-minlength-error="O formato da data deve ser DD/MM/AAAA" <?=$dataError?> pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d{2}" data-pattern-error="Formato inválido" <?=$required?>>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php

					break;

					case 'email':

					    $dataError = "";

						?>
							<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
									<input type="email" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$required?>>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php
						
					break;

					case 'numeric':

						if($qrCampos[DES_CAMPOOBG] == "COD_SEXOPES"){

							?>
								<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?=$required?> required">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
												<option value=""></option>					
												<?php 																	
													$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
												
													while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery))
													  {														
														echo"
															  <option value='".$qrListaSexo['COD_SEXOPES']."'>".$qrListaSexo['DES_SEXOPES']."</option> 
															"; 
														  }											
												?>	
											</select>
											<script type="text/javascript">$("#COD_SEXOPES").val("<?=$dado?>").trigger('chosen:updated');</script>    
										<div class="help-block with-errors"></div>
									</div>
								</div>

							<?php

						}else if($qrCampos[DES_CAMPOOBG] == "COD_PROFISS"){

							?>
								<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?=$required?>">Profissão </label>
											<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
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
											<script type="text/javascript">$("#COD_PROFISS").val("<?=$dado?>").trigger('chosen:updated');</script>                                                    
										<div class="help-block with-errors"></div>
									</div>
								</div>

							<?php

						}else if($qrCampos[DES_CAMPOOBG] == "COD_ESTACIV"){

							?>
								<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?=$required?>">Estado Civil</label>
											<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
												<option value=""></option>					
												<?php																	
													$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
												
													while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery))
													  {													
														echo"
															  <option value='".$qrListaEstCivil['COD_ESTACIV']."'>".$qrListaEstCivil['DES_ESTACIV']."</option> 
															"; 
														  }											
												?>	
											</select>
											<script type="text/javascript">$("#COD_ESTACIV").val("<?=$dado?>").trigger('chosen:updated');</script>
											<div class="help-block with-errors"></div>
									</div>
								</div>

							<?php

						}else{

							$type = "text";

							if($qrCampos[DES_CAMPOOBG] == "NUM_CGCECPF"){
								$nomeCampo = "CPF/CNPJ";
								$mask = "cpfcnpj";
								$type = "tel";
							}else{
								$nomeCampo = $qrCampos[NOM_CAMPOOBG];
								$mask = "";
							}

							?>
								<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?=$required?>"><?=$nomeCampo?></label>
										<input type="<?=$type?>" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?> <?=$mask?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$required?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							<?php

						}
						
					break;
					
					default:

						$type = "text";
						$validacao = "";

						if($qrCampos[DES_CAMPOOBG] == "NUM_CGCECPF"){
							$nomeCampo = "CPF/CNPJ";
							$mask = "cpfcnpj";
							$type = "tel";
						}else if($qrCampos[DES_CAMPOOBG] == "NUM_CELULAR"){
							$type = "tel";
							$validacao = 'data-minlength="15" data-minlength-error="Número incompleto" pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})" data-pattern-error="Formato inválido"';
						}else if($qrCampos[DES_CAMPOOBG] == "NUM_TELEFONE" || $qrCampos[DES_CAMPOOBG] == "NUM_CEPOZOF"){
							$type = "tel";
						}else{
							$nomeCampo = $qrCampos[NOM_CAMPOOBG];
							$mask = "";
						}

						if($qrCampos[DES_CAMPOOBG] == "COD_ESTADOF"){

							?>
								<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
									<div class="form-group">
										<label>&nbsp;</label>
										<label for="inputName" class="control-label <?=$required?>"><?=$nomeCampo?></label>
										<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$dataError?> <?=$required?>>
											<option value=""></option>					
											<option value="AC">AC</option> 
											<option value="AL">AL</option> 
											<option value="AM">AM</option> 
											<option value="AP">AP</option> 
											<option value="BA">BA</option> 
											<option value="CE">CE</option> 
											<option value="DF">DF</option> 
											<option value="ES">ES</option> 
											<option value="GO">GO</option> 
											<option value="MA">MA</option> 
											<option value="MG">MG</option> 
											<option value="MS">MS</option> 
											<option value="MT">MT</option> 
											<option value="PA">PA</option> 
											<option value="PB">PB</option> 
											<option value="PE">PE</option> 
											<option value="PI">PI</option> 
											<option value="PR">PR</option> 
											<option value="RJ">RJ</option> 
											<option value="RN">RN</option> 
											<option value="RO">RO</option> 
											<option value="RR">RR</option> 
											<option value="RS">RS</option> 
											<option value="SC">SC</option> 
											<option value="SE">SE</option> 
											<option value="SP">SP</option> 
											<option value="TO">TO</option> 							
										</select>
                                        <script>$("#formulario #COD_ESTADOF").val("<?php echo $dado; ?>").trigger("chosen:updated"); </script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							<?php

							}else{

						?>
							<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
								<div class="form-group">
									<label>&nbsp;</label>
									<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
									<input type="<?=$type?>" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$validacao?> <?=$required?>>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						<?php
						
						}

					break;

				}
				//echo $qrCampos[DES_CAMPOOBG];
		?>
				<!-- <div class="push10"></div> -->
		<?php

			}

			if($mostraSenha == 1 && $log_cadtoken == 'N'){

				if($cod_cliente == 0){

		?>

					<div class="push"></div>

					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<label>&nbsp;</label>
							<label for="inputName" class="control-label required">Loja de Cadastro</label>
							<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect input-sm" required>
								<option value=""></option>					
								<?php 																	
									$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '".$cod_empresa."' AND LOG_ESTATUS = 'S' order by NOM_FANTASI ";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
								
									while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery))
									  {														
										echo"
											  <option value='".$qrListaUnidade['COD_UNIVEND']."'>".$qrListaUnidade['NOM_FANTASI']."</option> 
											"; 
										  }											
								?>	
							</select>
		                    <script>$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated"); </script>
						</div>
					</div>

					<div class="push20"></div>

		<?php 

				}

				$senha = $buscaconsumidor[senha][0];

				if($senha == 0){
					$senha = "";
				}

		?>
			<div class="push"></div>

			<div class="col-md-12 col-xs-12">
				<div class="form-group">
					<label>&nbsp;</label>
					<label for="inputName" class="control-label required">Crie sua senha de acesso</label>
					<input type="password" value="<?=$senha?>" class="form-control input-sm input-hg int" name="DES_SENHAUS" id="DES_SENHAUS" maxlength='6' required>
					<div class="help-block with-errors f12">Máximo de 6 dígitos numéricos</div>
				</div>
			</div>

			<div class="col-md-12 col-xs-12">
				<div class="form-group">
					<label>&nbsp;</label>
					<label for="inputName" class="control-label required">Confirme sua senha</label>
					<input type="password" value="<?=$senha?>" class="form-control input-sm input-hg int" name="DES_SENHAUS_CONF" id="DES_SENHAUS_CONF" maxlength='6' data-match="#DES_SENHAUS" data-match-error="Senhas diferentes" required>
					<div class="help-block with-errors f12"></div>
				</div>
			</div>

		<?php

			}

		?>



		<div class="push20"></div>

		<?php 

			

				$displayTermos = "block";


				$mostraLgpd = 'N';

				// echo "$log_lgpd<br>";
				// echo "$log_cadtoken<br>";
				// echo "$qrControle[TXT_ACEITE]<br>";

				// if($log_lgpd == 'S' && $log_cadtoken == 'N' || $log_lgpd == 'S' && $cod_cliente != 0 && $atendente != 1){
				// 	$mostraLgpd = 'S';
				// }


				if($mostraLgpd == 'S'){

					

		?>

		<div id="relatorioPreview">

			<div class="push10"></div>

			<div class="col-xs-12">
				<p><b><?=$qrControle['TXT_ACEITE']?></b></p>
			</div>

			<div class="push10"></div>

			<?php

				if($log_separa == 'S'){

					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
					fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$count=0;
					$tipo = "";
					while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

						if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
							$obrigaChk = "required";
						}else{
							$obrigaChk = "";
						}


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
								   WHERE COD_CLIENTE = $cod_cliente
								   AND COD_CLIENTE != 0
								   AND COD_EMPRESA = $cod_empresa
								   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
								   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa,''),$sqlChk);

						$chkTermo = "";

						if(mysqli_num_rows($arrayChk) == 1){
							$chkTermo = "checked";
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
															
																<a class="addBox f16 text-success" 
																   data-url="termos.do?id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
																   data-title="'.$qrTermos['NOM_TERMO'].'"
																   style="cursor:pointer;">
																   '.$qrTermos['ABV_TERMO'].'
																</a>
															
													  	<label class="f16" for="TERMOS_'.$qrBuscaFAQ[COD_BLOCO].'">
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
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

				<?php

						$count++;

					}

					?>

					<div class="col-xs-12">
						<h5>
							<b>
								<p><?=$qrControle['TXT_COMUNICA']?></p>
							</b>
						</h5>
					</div>
					<div class="push10"></div>

					<?php 

					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
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


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
								   WHERE COD_CLIENTE = $cod_cliente
								   AND COD_CLIENTE != 0
								   AND COD_EMPRESA = $cod_empresa
								   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
								   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa,''),$sqlChk);

						$chkTermo = "";

						if(mysqli_num_rows($arrayChk) == 1){
							$chkTermo = "checked";
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
															
																<a class="addBox f16 text-success" 
																   data-url="termos.do?id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
																   data-title="'.$qrTermos['NOM_TERMO'].'"
																   style="cursor:pointer;">
																   '.$qrTermos['ABV_TERMO'].'
																</a>
															
													  	<label class="f16" for="TERMOS_'.$qrBuscaFAQ[COD_BLOCO].'">
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
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

				<?php

						$count++;

					}

				}else{
					
					$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO = 'N' ORDER BY NUM_ORDENAC";
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


						$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
								   WHERE COD_CLIENTE = $cod_cliente
								   AND COD_CLIENTE != 0
								   AND COD_EMPRESA = $cod_empresa
								   AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
								   AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
						// echo($sqlChk);
						$arrayChk = mysqli_query(connTemp($cod_empresa,''),$sqlChk);

						$chkTermo = "";

						if(mysqli_num_rows($arrayChk) == 1){
							$chkTermo = "checked";
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
															
																<a class="addBox f16 text-success" 
																   data-url="termos.do?id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
																   data-title="'.$qrTermos['NOM_TERMO'].'"
																   style="cursor:pointer;">
																   '.$qrTermos['ABV_TERMO'].'
																</a>
															
													  	<label class="f16" for="TERMOS_'.$qrBuscaFAQ[COD_BLOCO].'">
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
							<div class="push10"></div>
							<div class="push5"></div>
						</div>

					<?php

						$count++;

					}


				}	

			?>

		</div>

	<?php } ?>

		<div class="push20"></div>

		<div id="relatorioConteudo"></div>

		<input type="hidden" name="KEY_DES_TOKEN" id="KEY_DES_TOKEN" value="">
		<input type="hidden" name="KEY_NUM_CARTAO" id="KEY_NUM_CARTAO" value="<?=$k_num_cartao?>">
		<input type="hidden" name="KEY_NUM_CELULAR" id="KEY_NUM_CELULAR" value="<?=$k_num_celular?>">
		<input type="hidden" name="KEY_COD_EXTERNO" id="KEY_COD_EXTERNO" value="<?=$k_cod_externo?>">
		<input type="hidden" name="KEY_NUM_CGCECPF" id="KEY_NUM_CGCECPF" value="<?=$k_num_cgcecpf?>">
		<input type="hidden" name="KEY_DAT_NASCIME" id="KEY_DAT_NASCIME" value="<?=$k_dat_nascime?>">
		<input type="hidden" name="KEY_DES_EMAILUS" id="KEY_DES_EMAILUS" value="<?=$k_des_emailus?>">
		<input type="hidden" name="CAD_NOM_CLIENTE" id="CAD_NOM_CLIENTE" value="<?=$buscaconsumidor[nome]?>">
		<input type="hidden" name="CAD_NUM_CGCECPF" id="CAD_NUM_CGCECPF" value="<?=$buscaconsumidor[cpf]?>">
		<input type="hidden" name="CAD_COD_SEXOPES" id="CAD_COD_SEXOPES" value="<?=$buscaconsumidor[sexo]?>">
		<input type="hidden" name="CAD_NUM_CARTAO" id="CAD_NUM_CARTAO" value="<?=$buscaconsumidor[cartao]?>">
		<input type="hidden" name="CAD_DES_EMAILUS" id="CAD_DES_EMAILUS" value="<?=$buscaconsumidor[email]?>">
		<input type="hidden" name="CAD_DES_ENDEREC" id="CAD_DES_ENDEREC" value="<?=$buscaconsumidor[endereco]?>">
		<input type="hidden" name="CAD_NUM_ENDEREC" id="CAD_NUM_ENDEREC" value="<?=$buscaconsumidor[numero]?>">
		<input type="hidden" name="CAD_DES_BAIRROC" id="CAD_DES_BAIRROC" value="<?=$buscaconsumidor[bairro]?>">
		<input type="hidden" name="CAD_DES_COMPLEM" id="CAD_DES_COMPLEM" value="<?=$buscaconsumidor[complemento]?>">
		<input type="hidden" name="CAD_DES_CIDADEC" id="CAD_DES_CIDADEC" value="<?=$buscaconsumidor[cidade]?>">
		<input type="hidden" name="CAD_COD_ESTADOF" id="CAD_COD_ESTADOF" value="<?=$buscaconsumidor[estado]?>">
		<input type="hidden" name="CAD_NUM_CEPOZOF" id="CAD_NUM_CEPOZOF" value="<?=$buscaconsumidor[cep]?>">
		<input type="hidden" name="CAD_DAT_NASCIME" id="CAD_DAT_NASCIME" value="<?=$buscaconsumidor[datanascimento]?>">
		<input type="hidden" name="CAD_NUM_CELULAR" id="CAD_NUM_CELULAR" value="<?=$buscaconsumidor[telcelular]?>">
		<input type="hidden" name="CAD_COD_PROFISS" id="CAD_COD_PROFISS" value="<?=$buscaconsumidor[profissao]?>">
		<input type="hidden" name="CAD_COD_ATENDENTE" id="CAD_COD_ATENDENTE" value="<?=$buscaconsumidor[codatendente]?>">
		<input type="hidden" name="CAD_DES_SENHAUS" id="CAD_DES_SENHAUS" value="<?=fnEncode($buscaconsumidor[senha][0])?>">
		<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=fnEncode($cod_cliente)?>">
		<input type="hidden" name="LOG_NOVOCLI" id="LOG_NOVOCLI" value="<?=$log_novocli?>">
		<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
		<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

</div>

<div class="push30"></div> 				

<div class="col-md-3"></div>	

<div class="col-md-6">

		<?php 

			if($cod_cliente == 0){ 

				$log_novocli = "S";

		?>
		
				<?php 

					if($log_cadtoken == 'S'){ 

						if($nroCampos > 0){

				?>

					<div id="relatorioToken">
						<a href="javascript:void(0)" class="btn btn-primary btn-lg btn-block" onclick='ajxToken()'><i class="fal fa-user-unlock" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Enviar Token</a>
					</div>

					<div id="btnCad" style="display: none;">
						<button type="button" name="ATUALIZA" id="ATUALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-shopping-basket" aria-hidden="true"></i>&nbsp; Atualizar Cadastro e Continuar Compra </button>
					</div>
					
				<?php 
						}else{

				?>

					<div class="col-md-12 col-xs-12 text-left">

						<div class="alert alert-danger" role="alert">
						<a type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></a>
						 	Os campos <b>Iniciais/Token</b> não foram configurados na matriz. Contate o suporte.
						</div>

					</div>

				<?php 

						}

					}else{ 

				?>

					<!-- <button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block getBtn" tabindex="5" style="color: #fff;">Aceitar Termos e Cadastrar</button> -->
					<button type="button" name="ATUALIZA" id="ATUALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-shopping-basket" aria-hidden="true"></i>&nbsp; Cadastrar e Continuar Compra </button>

				<?php } ?>

		<?php 

			}else{ 

				$log_novocli = "N";
				$txtDescad = "Descadastrar-se";

				if($cod_empresa == 77){
					$txtDescad = "Excluir Cadastro";
				}

				if($log_cadtoken == 'S' && $des_token == 0){

		?>
					
					<div id="relatorioToken">
						<a href="javascript:void(0)" class="btn btn-primary btn-lg btn-block" onclick='ajxTokenAlt()'><i class="fal fa-user-unlock" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Enviar Token</a>
					</div>

					<div id="btnCad" style="display: none;">
						<button type="button" name="ATUALIZA" id="ATUALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-shopping-basket" aria-hidden="true"></i>&nbsp; Atualizar Cadastro e Continuar Compra </button>
					</div>

		<?php 

				}else{

		?>

					<button type="button" name="ATUALIZA" id="ATUALIZA" class="btn btn-success btn-lg btn-block getBtn" tabindex="5"><i class="fa fa-1x fa-shopping-basket" aria-hidden="true"></i>&nbsp; Atualizar Cadastro e Continuar Compra </button>

		<?php 
				}

			} 

		?>
	
	<div class="push10"></div> 
	<a href="action.do?mod=<?php echo fnEncode(1758); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="HOME3" id="HOME3" class="btn btn-info btn-lg btn-block" tabindex="5"><i class="fa fa-1x fa-home" aria-hidden="true"></i>&nbsp; Voltar Menu Principal </a>
</div>

<div class="col-md-3"></div>
<?php 
	if($cartao == 'true'){
		$cpf = "";
	} 
?>
<input type="hidden" class="form-control input-lg" name="c1" id="c1" value="<?php echo $cpf; ?>">
<input type="hidden" class="form-control input-lg" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo @$COD_UNIVEND; ?>">
<input type="hidden" name="TOKEN_ENVIADO" id="TOKEN_ENVIADO" value="N">
<input type="hidden" name="TOKEN_VALIDADO" id="TOKEN_VALIDADO" value="N">

<script type="text/javascript">	

let log_cadtoken_var = "<?=$log_cadtoken?>",
	cod_cliente_var = "<?=$cod_cliente?>";
	
$(function(){
// $('input, textarea').placeholder();	

	$(document).on('keypress',function(e) {
	    if(e.which == 13) {
	        e.preventDefault();
	        if(log_cadtoken_var == 'S'){
	        	if($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'N'){
	        		console.log("enviado não validado");
	    			ajxValidaTkn();
	    		}else if($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'S' && !$("#CAD").hasClass('disabled')){
	    			console.log("enviado validado e campos preenchidos");
	    			$("#CAD").click();
	    		}else if($("#TOKEN_ENVIADO").val() == 'S' && $("#TOKEN_VALIDADO").val() == 'S' && $("#CAD").hasClass('disabled')){
	    			$("#formulario").validator('validate');
	    			$.alert({
	                  title: "Aviso!",
	                  content: "É necessário preencher todos os campos obrigatórios!",
	                  type: 'orange'
	                });
	    		}else{
	    			if(cod_cliente_var == 0){
	    				ajxToken();
	    			}else{
	    				ajxTokenAlt();
	    			}
	    		}
	        }
	    }
	});	
		
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

$("#CAD").click(function(e){
	$("#formulario").validator('update').validator('validate');
	if($('#formulario').validator('validate').has('.has-error').length > 0){ 
		e.preventDefault();
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

function toggleAuth(obj){
	$("#relatorioPreview").fadeIn("fast");
	$(obj).fadeOut(1);
}

function ajxDescadastra(cod_cliente){

$.alert({
  title: "Confirmação",
  content: "Quero excluir meus dados de forma definitiva.",
  type: 'red',
  buttons: {
    "EXCLUIR": {
       btnClass: 'btn-danger',
       action: function(){
        
            $.alert({
              title: "Aviso!",
              content: "Não quero mais participar das vantagens do programa. <br/>Estou ciente que meus créditos ou bônus serão excluídos junto aos dados de forma irreversível.",
              type: 'red',
              buttons: {
                "EXCLUIR PERMANENTEMENTE": {
                   btnClass: 'btn-danger',
                   action: function(){
                    	$.ajax({
							type: "POST",
							url: "ajxTokenPdv.do?id=<?php echo fnEncode($cod_empresa); ?>",
							data: { COD_CLIENTE: cod_cliente },
							beforeSend:function(){
								$("#blocker").show();
							},
							success:function(data){	
								window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
							},
							error:function(){
							    console.log('Erro');
							}
						});
                   }
                },
                "CANCELAR": {
                  btnClass: 'btn-default',
                   action: function(){
                    
                   }
                }
              },
              backgroundDismiss: function(){
                  return 'CANCELAR';
              }
            });

       }
    },
    "CANCELAR": {
      btnClass: 'btn-default',
       action: function(){
        
       }
    }
  },
  backgroundDismiss: function(){
      return 'CANCELAR';
  }
});

}

function ajxToken(){

var nom_cliente = $("#NOM_CLIENTE").val(),
	num_celular = $("#NUM_CELULAR").val(),
	cad_num_celular = $("#CAD_NUM_CELULAR").val(),
	key_num_celular = $("#KEY_NUM_CELULAR").val(),
	num_cgcecpf = $("#NUM_CGCECPF").val(),
	cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
	key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val(),
	urltotem = "<?=$urltotem?>";

if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

	$.ajax({
		type: "POST",
		url: "ajxTokenPdv.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN",
		data: { 
				NOM_CLIENTE: nom_cliente, 
				NUM_CELULAR: num_celular, 
				CAD_NUM_CELULAR: cad_num_celular, 
				KEY_NUM_CELULAR: key_num_celular, 
				NUM_CGCECPF: num_cgcecpf, 
				CAD_NUM_CGCECPF: cad_num_cgcecpf, 
				KEY_NUM_CGCECPF: key_num_cgcecpf, 
				URL_TOTEM: urltotem, 
				LOG_LGPD: "<?=fnEncode($log_lgpd)?>"
		},
		beforeSend:function(){
			$("#blocker").show();
		},
		success:function(data){	
			$("#relatorioToken").html(data);
			$("#formulario").validator('destroy').validator();
			$("#blocker").hide();
			$("#TOKEN_ENVIADO").val('S');
			// window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
		},
		error:function(){
		    console.log('Erro');
		}
	});

}

}

function ajxTokenAlt(){

var nom_cliente = $("#NOM_CLIENTE").val(),
	num_celular = $("#NUM_CELULAR").val(),
	cad_num_celular = $("#CAD_NUM_CELULAR").val(),
	key_num_celular = $("#KEY_NUM_CELULAR").val(),
	num_cgcecpf = $("#NUM_CGCECPF").val(),
	cad_num_cgcecpf = $("#CAD_NUM_CGCECPF").val(),
	key_num_cgcecpf = $("#KEY_NUM_CGCECPF").val(),
	urltotem = "<?=$urltotem?>";

if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

	$.ajax({
		type: "POST",
		url: "ajxTokenPdv.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT",
		data: { 
				NOM_CLIENTE: nom_cliente, 
				NUM_CELULAR: num_celular, 
				CAD_NUM_CELULAR: cad_num_celular, 
				KEY_NUM_CELULAR: key_num_celular, 
				NUM_CGCECPF: num_cgcecpf, 
				CAD_NUM_CGCECPF: cad_num_cgcecpf, 
				KEY_NUM_CGCECPF: key_num_cgcecpf,
				URL_TOTEM: urltotem,  
				LOG_LGPD: "<?=fnEncode($log_lgpd)?>"
		},
		beforeSend:function(){
			$("#blocker").show();
		},
		success:function(data){	
			$("#relatorioToken").html(data);
			$("#formulario").validator('destroy').validator();
			$("#blocker").hide();
			$("#TOKEN_ENVIADO").val('S');
			// window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
		},
		error:function(){
		    console.log('Erro');
		}
	});

}

}

function ajxValidaTkn(){

var num_celular = $("#NUM_CELULAR").val(),
	nom_cliente = $("#NOM_CLIENTE").val(),
	des_token = $("#DES_TOKEN").val(),
	num_cgcecpf = $("#NUM_CGCECPF").val(),
	urltotem = "<?=$urltotem?>";

if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

	$.ajax({
		type: "POST",
		url: "ajxTokenPdv.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD",
		data: { NOM_CLIENTE: nom_cliente, NUM_CELULAR: num_celular, NUM_CGCECPF: num_cgcecpf, DES_TOKEN: des_token, URL_TOTEM: urltotem },
		beforeSend:function(){
			$("#blocker").show();
		},
		success:function(data){	

			$("#blocker").hide();

			if(data.trim() == "validado"){

				$("#KEY_DES_TOKEN").val($("#DES_TOKEN").val());

				$("#camposToken").fadeOut('fast',function(){
					$("#btnCad").fadeIn('fast');
					$("#formulario").validator("validate");	
				});		

				$("#formulario").validator('destroy').validator();	
				$("#TOKEN_VALIDADO").val('S');			

			}else{

				$("#erroTkn").fadeIn(1);

				// console.log(data);

			}	

		},
		error:function(){
		    console.log('Erro');
		}
	});

}

}		
</script>
		