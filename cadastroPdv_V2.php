<?php
	//echo fnDebug('true');
	include_once '.totem/funWS/buscaConsumidor.php';
	include_once '.totem/funWS/buscaConsumidorCNPJ.php';
	include_once '.totem/funWS/saldo.php';	
	$hashLocal = mt_rand();
	$tem_prodaux = "";
   

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

        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);

        $sql = "SELECT * FROM  USUARIOS
				WHERE LOG_ESTATUS='S' AND
					  COD_EMPRESA = $cod_empresa AND
					  COD_TPUSUARIO=10  limit 1  ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
						
		if (isset($arrayQuery)) {
			$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
			$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
		}

		$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
				  WHERE COD_EMPRESA = $cod_empresa 
				  AND LOG_ESTATUS = 'S' 
				  ORDER BY 1 ASC LIMIT 1";

		$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
		$qrLista = mysqli_fetch_assoc($arrayUn);

		$idlojaKey = $qrLista['COD_UNIVEND'];
		$idmaquinaKey = 0;
		$codvendedorKey = 0;
		$nomevendedorKey = 0;

		$urltotem = $log_usuario.';'
					.$des_senhaus.';'
					.$idlojaKey.';'
					.$idmaquinaKey.';'
					.$cod_empresa.';'
					.$codvendedorKey.';'
					.$nomevendedorKey;

		$arrayCampos = explode(";", $urltotem);

        $k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
		$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
		$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
		$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
		$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
		$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

		 // fnEscreve($_REQUEST['KEY_NUM_CGCECPF']);
		 fnEscreve($k_num_cgcecpf);

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

					fnEscreve(fnCompletaDoc($k_num_cgcecpf,'F'));

					echo '<pre>';

		            print_r($arrayCampos);
		            $buscaconsumidor = fnconsulta(fnCompletaDoc($k_num_cgcecpf,'F'), $arrayCampos);


		            echo '</pre>';
		            
		        }else{

		        	// echo 'else';

		            $buscaconsumidor = fnconsultacnpf(fnCompletaDoc($k_num_cgcecpf,'J'), $arrayCampos); 
		            
        		}

			break;

		}

		// if($cod_empresa = 7){

			echo '<pre>';
			print_r($buscaconsumidor);
			// print_r($buscaconsumidor);
			echo '</pre>';
			// exit();
            
		// }

		// $dado_consulta = "<cpf>$cpf</cpf>\r\n\t";

        if($buscaconsumidor['cpf']!='00000000000'){

			$cpf=$buscaconsumidor['cpf'];

        }else{
        	$cpf = $k_num_cgcecpf;
        	$buscaconsumidor['nome'] = "";
        }

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
		
		$_SESSION["USU_COD_EMPRESA"] = $cod_empresa;
		$_SESSION["USU_COD_USUARIO"] = $_REQUEST['COD_USUARIO'];
		$_SESSION["USU_COD_UNIVEND"] = $_REQUEST['COD_UNIVEND'];


        if ($opcao != '')
        {

            $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
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
	
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);			
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
			
		}else{
			$casasDec = 2;
		}
		
		/*
		$sql = "select  A.*,B.NOM_FANTASI from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '".$cod_empresa."' ";		
		
		//fnEscreve($sql);
		
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
 
		if (isset($arrayQuery)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			
		}
		*/
			
												
	}else {
		$cod_empresa = 0;
		$casasDec = 2;	
		
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

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if($cod_cliente != 0){

	$arrayNome = explode(" ", $result['NOM_CLIENTE']);
	$nome=$arrayNome[0];
	$dia_nascime = $result['DIA'];
	$mes_nascime = $result['MES'];
	$ano_nascime = $result['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $result['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_TOTEM = 'S'
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
	AND COMUNICACAO_MODELO.LOG_TOTEM = 'S'
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

	// switch ($casasDec) {

	//    	case 3:
	//    		$money = "money3";
	//    	break;

	//    	case 4:
	//    		$money = "money4";
	//    	break;

	//    	case 5:
	//    		$money = "money5";
	//    	break;
	   	
	//    	default:
	//    		$money = "money";
	//    	break;

	//    }
	
	//fnMostraForm();
	//fnEscreve($cod_orcamento);
	
?>

<script src="httpss://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
<link href="httpss://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
<!-- JQUERY-CONFIRM -->
<link href="httpss://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>

<style>
.widget .widget-title {
    font-size: 14px;
}
.widget .widget-int {
    font-size: 20px;
	padding: 0 0 10px 0;
}
.widget .widget-item-left .fa, .widget .widget-item-right .fa, .widget .widget-item-left .glyphicon, .widget .widget-item-right .glyphicon {
    font-size: 35px;
}


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
	
	/*-- choosen --*/

	#sexo_chosen, #COD_ATENDENTE_chosen {
		font-size: 18px;
	}
	
	#sexo_chosen > a, #COD_ATENDENTE_chosen > a {
		height: 66px;
		padding: 18px 27px;		
	}
	
	#COD_UNIVEND_chosen {
		font-size: 15px;
	}
	
	#COD_UNIVEND_chosen > a {
		height: 45px;
		padding: 5px 15px;	
	}

	#COD_USUARIO_chosen {
		font-size: 15px;
	}
	
	#COD_USUARIO_chosen > a {
		height: 45px;
		padding: 5px 15px;		
	}

	#COD_FORMAPA_chosen {
		font-size: 15px;
	}
	
	#COD_FORMAPA_chosen > a {
		height: 45px;
		padding: 5px 15px;		
	}	
	
	.chosen-container{
		width:100% !important;
	}
	
	.chosen-container-single .chosen-single abbr {
		top: 28px;
	}
	
	.chosen-container-single .chosen-single div b {
		background: url(css/chosen-sprite.png) no-repeat 0 7px;
	}	

	/*-- choosen --*/
	
		
	/* TILES */
	.tile {
	  width: 100%;
	  float: left;
	  margin: 0px;
	  list-style: none;
	  text-decoration: none;
	  font-size: 38px;
	  font-weight: 300;
	  color: #FFF;
	  -moz-border-radius: 5px;
	  -webkit-border-radius: 5px;
	  border-radius: 5px;
	  padding: 10px;
	  margin-bottom: 20px;
	  min-height: 100px;
	  position: relative;
	  border: 1px solid #D5D5D5;
	  text-align: center;
	}
	.tile.tile-valign {
	  line-height: 75px;
	}
	.tile.tile-default {
	  background: #FFF;
	  color: #656d78;
	}
	.tile.tile-default:hover {
	  background: #FAFAFA;
	}
	.tile.tile-primary {
	  background: #33414e;
	  border-color: #33414e;
	}
	.tile.tile-primary:hover {
	  background: #2f3c48;
	}
	.tile.tile-success {
	  background: #95b75d;
	  border-color: #95b75d;
	}
	.tile.tile-success:hover {
	  background: #90b456;
	}
	.tile.tile-warning {
	  background: #fea223;
	  border-color: #fea223;
	}
	.tile.tile-warning:hover {
	  background: #fe9e19;
	}
	.tile.tile-danger {
	  background: #b64645;
	  border-color: #b64645;
	}
	.tile.tile-danger:hover {
	  background: #af4342;
	}
	.tile.tile-info {
	  background: #3fbae4;
	  border-color: #3fbae4;
	}
	.tile.tile-info:hover {
	  background: #36b7e3;
	}
	.tile:hover {
	  text-decoration: none;
	  color: #FFF;
	}
	.tile.tile-default:hover {
	  color: #656d78;
	}
	.tile .fa {
	  font-size: 52px;
	  line-height: 74px;
	}
	.tile p {
	  font-size: 14px;
	  margin: 0px;
	}
	.tile .informer {
	  position: absolute;
	  left: 5px;
	  top: 5px;
	  font-size: 12px;
	  color: #FFF;
	  line-height: 14px;
	}
	.tile .informer.informer-default {
	  color: #FFF;
	}
	.tile .informer.informer-primary {
	  color: #33414e;
	}
	.tile .informer.informer-success {
	  color: #95b75d;
	}
	.tile .informer.informer-info {
	  color: #3fbae4;
	}
	.tile .informer.informer-warning {
	  color: #fea223;
	}
	.tile .informer.informer-danger {
	  color: #b64645;
	}
	.tile .informer .fa {
	  font-size: 14px;
	  line-height: 16px;
	}
	.tile .informer.dir-tr {
	  left: auto;
	  right: 5px;
	}
	.tile .informer.dir-bl {
	  top: auto;
	  bottom: 5px;
	}
	.tile .informer.dir-br {
	  left: auto;
	  top: auto;
	  right: 5px;
	  bottom: 5px;
	}
	/* EOF TILES */
	

</style>

	<div class="push30"></div> 
	
	<div class="row">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>
					
					<?php 
					$formBack = "1240";
					include "atalhosPortlet.php"; 
					?>	
					
				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					 <?php echo $msgRetorno; ?>
					</div>
					<?php } ?>					
					
					<div class="push30"></div> 

					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?=$actionForm?>">
							<?php

								// echo('cliente: '.$cod_cliente.'<BR>');
								// echo('cadtoken: '.$log_cadtoken.'<BR>');
								// echo('termos: '.$log_termo.'<BR>');
								// exit();

								if($cod_cliente != 0 && $verificaCad == 1 && $log_termo == 'S'){
									// echo('httpss://totem.bunker.mk/preSaldo_V2.do?key='.$_GET['key'].'&idc='.fnEncode($cod_cliente));
									fnEscreve('if 1');
								?>
									<script>
										// window.location.href = 'preSaldoAtendente.do?key=<?=$_GET['key']?>&idc=<?=fnEncode($cod_cliente)?>';
									</script>
								<?php
								}else if($cod_cliente != 0 && $verificaCad == 1 && $log_termo == 'N'){
									fnEscreve('if 2');

									// echo('httpss://totem.bunker.mk/validaDados.do?key='.$_GET['key'].'&idc='.fnEncode($cod_cliente));
								?>
									<script>
										// window.location.href = 'validaDados.do?key=<?=$_GET['key']?>&idc=<?=fnEncode($cod_cliente)?>';
										// window.location.href = 'preSaldoAtendente.do?key=<?=$_GET['key']?>&idc=<?=fnEncode($cod_cliente)?>';
									</script>
								<?php
								}else if($cod_cliente == 0 && $log_cadtoken == 'N'){
									fnEscreve('if 3');
								?>
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

										.input-sm, .chosen-single{
											font-size: 20px!important;
										}

										.logo-center{
											margin-left: auto;
											margin-right: auto;
										}
									</style>

									<div class="col-md-6 col-xs-12" id="caixaImg">
										<img src="httpss://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img_g?>" class="img-responsive desktop" style="margin-left: auto; margin-right: auto;">
										<img src="httpss://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive tablet" style="margin-left: auto; margin-right: auto;">
										<img src="httpss://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_imgmob?>" class="img-responsive mobile" style="margin-left: auto; margin-right: auto;">
									</div>

									<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

										<div class="push20"></div>
										<div class="col-md-10 col-md-offset-1 col-xs-12 text-left">

											<div class="alert alert-warning" role="alert" style="margin-bottom: 0px;" id="alertaCadAtendente">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											 	<span class="fal fa-exclamation-triangle"></span>&nbsp;&nbsp;&nbsp; Cliente ainda não cadastrado. Direcione o cliente para os canais de cadastro.
											 	<div class="push5"></div>
											 	- Totem
											 	<div class="push"></div>
												- qrCode
												<div class="push"></div>
												- Hotsite
											</div>

										</div>

										<div class="push10"></div>

										<div class="col-md-10 col-md-offset-1">
											<a href="atendente.do?key=<?=$_GET['key']?>" class="btn btn-primary btn-lg btn-block"><i class="fal fa-home" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; Voltar para o início</a>
										</div>

									</div>

								<?php						
								}else{
									fnEscreve('else');
									if($cod_cliente == 0){						
								?>

										<div class="col-md-6 col-md-offset-6">
											<div class="push20"></div>
											<div class="col-md-12 col-xs-12 text-left">

												<div class="alert alert-warning" role="alert" style="margin-bottom: 0px;" id="alertaCadAtendente">
												<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												 	<span class="fal fa-exclamation-triangle"></span>&nbsp;&nbsp;&nbsp;Cliente ainda não cadastrado. Preencha os campos abaixo para cadastrar.
												</div>

											</div>
										</div>
								<?php 
									}
									$atendente = 1;
									include '.totem/includeMaisCash.php';
								}


							?>	

							<input type="hidden" name="URL_TOTEM" id="URL_TOTEM" value="<?php echo $_GET['key'] ;?>">

						</form>										
						
					</div>
					<!-- fim Portlet -->
					
				</div>
				
			</div>					

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

	<script src="httpss://bunker.mk/js/jquery-confirm.min.js"></script>
	
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
									url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>",
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
			urltotem = "<?=$_GET[key]?>";

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN",
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
			urltotem = "<?=$_GET[key]?>";

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKNALT",
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
			urltotem = "<?=$_GET[key]?>";

		if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

			$.ajax({
				type: "POST",
				url: "ajxTokenAtendente.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD",
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