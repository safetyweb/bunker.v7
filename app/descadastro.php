<?php 

include 'header.php'; 
$tituloPagina = "Cadastro";
include "navegacao.php"; 

include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';
include_once '../totem/funWS/saldo.php';
// $cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_cliente = $usuario;

$sqlCampos = "SELECT COD_CHAVECO, LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);

// echo($sqlCampos);

$lastField = "";

$qrCampos = mysqli_fetch_assoc($arrayFields);

$log_cadtoken = $qrCampos[LOG_CADTOKEN];
$cod_chaveco = $qrCampos[COD_CHAVECO];

// echo($cod_cliente);
//busaca clientes por cpf

//habilitando o cors
header("Access-Control-Allow-Origin: *");

//busca usuário modelo	
// $sql = "SELECT * FROM  USUARIOS
// 		WHERE LOG_ESTATUS='S' AND
// 			  COD_EMPRESA = $cod_empresa AND
// 			  COD_TPUSUARIO=10  limit 1  ";
// //fnEscreve($sql);
// $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
// $qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
				
// if (isset($arrayQuery)) {
// 	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
// 	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
// }

// $sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
// 		  WHERE COD_EMPRESA = $cod_empresa 
// 		  AND LOG_ESTATUS = 'S' 
// 		  ORDER BY 1 ASC LIMIT 1";

// $arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
// $qrLista = mysqli_fetch_assoc($arrayUn);

// $idlojaKey = $qrLista['COD_UNIVEND'];
// $idmaquinaKey = 0;
// $codvendedorKey = 0;
// $nomevendedorKey = 0;

// $urltotem = $log_usuario.';'
// 			.$des_senhaus.';'
// 			.$idlojaKey.';'
// 			.$idmaquinaKey.';'
// 			.$cod_empresa.';'
// 			.$codvendedorKey.';'
// 			.$nomevendedorKey;

// $arrayCampos = explode(";", $urltotem);

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

if($cod_cliente == 0){

	$sqlCli = "SELECT * FROM CLIENTES 
		       WHERE COD_EMPRESA = $cod_empresa
		       AND ($whereSql)
		       ORDER BY 1 LIMIT 1";

	$sqlCampos = "SELECT COD_CHAVECO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";

	$arrayFields = mysqli_query($connAdm->connAdm(),$sqlCampos);

	$lastField = "";

	$qrCampos = mysqli_fetch_assoc($arrayFields);

	$cod_chaveco = $qrCampos[COD_CHAVECO];

}else{

	if(isset($usuario) && $usuario != ""){

		$chave = $usuario;

		switch ($qrCampos[COD_CHAVECO]) {

			case 2:
				$campo = "NUM_CARTAO";
			break;

			case 3:
				$campo = "NUM_CELULAR";
			break;

			case 4:
				$campo = "COD_EXTERNO";
			break;

			default:
				$campo = "NUM_CGCECPF";
			break;

		}

		$sqlCli = "SELECT * FROM CLIENTES 
			       WHERE COD_EMPRESA = $cod_empresa
			       AND $campo = '$chave'";

	}
	
}

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cpf = fnLimpaDoc($qrCli[NUM_CGCECPF]);
$cod_cliente = fnLimpaCampoZero($qrCli[COD_CLIENTE]);
$celular = $qrCli[NUM_CELULAR];
$cartao = $qrCli[NUM_CARTAO];
$externo = $qrCli[NUM_CARTAO];
$log_termo = $qrCli[LOG_TERMO];
$des_token = $qrCli[DES_TOKEN];

if($cpf != ""){
	$k_num_cgcecpf = $cpf;
}

switch ($qrCampos[COD_CHAVECO]) {

	case 2:
		echo "cartao";
		$chave = $cartao;
		$buscaconsumidor = fnconsulta_V2($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
	break;

	case 3:
		echo "celular";
		$chave = $celular;
		$buscaconsumidor = fnconsulta_V2($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
	break;

	case 4:
		echo "externo";
		$chave = $externo;
		$buscaconsumidor = fnconsulta_V2($qrCampos[COD_CHAVECO], fnLimpaDoc($chave), $arrayCampos);
	break;

	default:

		if(strlen($k_num_cgcecpf) <= '11'){

			echo "cpf";

			// echo '<pre>';

            $buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf,'F'), $arrayCampos);

            // print_r($buscaconsumidor);

            // echo '</pre>';
            
        }else{

        	echo "cnpj";

        	// echo 'else';

            $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf,'J'), $arrayCampos); 
            
		}

	break;

}

// echo '<pre>';
// print_r($buscaconsumidor);
// echo '</pre>';

if($buscaconsumidor['cpf']!='00000000000'){

	$cpf=$buscaconsumidor['cpf'];

}else{
	$cpf = $k_num_cgcecpf;
	$buscaconsumidor['nome'] = "";
}

if($buscaconsumidor['cartao'] != ""){
	$cartao = $buscaconsumidor['cartao'];
	$c10 = $buscaconsumidor['cartao'];
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

$sql = "SELECT LOG_TERMOS FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
$log_termos = $qrLog['LOG_TERMOS'];

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

$campoLogin = ""; 
$dadoLogin = "";

if($k_num_cartao != ""){
	$buscaconsumidor['cartao'] = $k_num_cartao;
	$campoLogin ="KEY_NUM_CARTAO"; 
	$dadoLogin =$k_num_cartao; 
}else{
	$k_num_cartao = $buscaconsumidor['cartao'];
}

if($k_num_celular != ""){
	$buscaconsumidor['telcelular'] = $k_num_celular;
	$campoLogin = "KEY_NUM_CELULAR";
	$dadoLogin = $k_num_celular;
}else{
	$k_num_celular = $buscaconsumidor['telcelular'];
}

if($k_num_cgcecpf != ""){
	$buscaconsumidor['cpf'] = $k_num_cgcecpf;
	$campoLogin = "KEY_NUM_CGCECPF";
	$dadoLogin = $k_num_cgcecpf;
}else{
	$k_num_cgcecpf = $buscaconsumidor['cpf'];
}

if($k_dat_nascime != ""){
	$buscaconsumidor['datanascimento'] = $k_dat_nascime;
	$campoLogin = "KEY_DAT_NASCIME";
	$dadoLogin = $k_dat_nascime;
}else{
	$k_dat_nascime = $buscaconsumidor['datanascimento'];
}

if($k_des_emailus != ""){
	$buscaconsumidor['email'] = $k_des_emailus;
	$campoLogin = "KEY_DES_EMAILUS";
	$dadoLogin = $k_des_emailus;
}else{
	$k_des_emailus = $buscaconsumidor['email'];
}

if($buscaconsumidor['cpf'] == "00000000000"){
	$buscaconsumidor['cpf'] = "";
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if($cod_cliente != 0){

	$arrayNome = explode(" ", $qrBuscaCliente['NOM_CLIENTE']);
	$nome=$arrayNome[0];
	$dia_nascime = $qrBuscaCliente['DIA'];
	$mes_nascime = $qrBuscaCliente['MES'];
	$ano_nascime = $qrBuscaCliente['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $qrBuscaCliente['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";													
	// echo($sql);
	$arrayQuery2 = mysqli_query(connTemp($cod_empresa,""),$sql);

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
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '99' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";													
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);

	$count=0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery);

	if(mysqli_num_rows($arrayQuery) > 0){

		$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

		$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($qrCli['NOM_CLIENTE']))));                                
		$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
		$TEXTOENVIO=str_replace('<#SALDO>', fnValor($qrCli['CREDITO_DISPONIVEL'],$casasDec), $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#NOMELOJA>',  $qrCli['NOM_FANTASI'], $TEXTOENVIO);
		$TEXTOENVIO=str_replace('<#ANIVERSARIO>', $qrCli['DAT_NASCIME'], $TEXTOENVIO); 
		$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($qrCli['DAT_EXPIRA']), $TEXTOENVIO); 
		$TEXTOENVIO=str_replace('<#EMAIL>', $qrCli['DES_EMAILUS'], $TEXTOENVIO); 
		$msgsbtr=nl2br($TEXTOENVIO,true);                                
		$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
		$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);

		
		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '7':

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

?>
	
<div class="container">

	<div class="row text-center">

		<div class="col-xs-12">
			<h3>Descadastro realizado com sucesso</h3>
			<div class="push20"></div>
			<a href="intro.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-primary">Voltar à tela inicial</a>
		</div>
		

	</div>
    

</div> <!-- /container -->




<?php include 'footer.php'; ?>

<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>

