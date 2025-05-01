<?php

include "../_system/_functionsMain.php";
// include "../_system/_FUNCTION_WS.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");
$opcao = fnLimpacampo($_REQUEST['opcao']);

$num_cgcecpf = fnLimpacampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
$nom_cliente = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
$dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
$cod_sexopes = fnLimpaCampoZero($_REQUEST['COD_SEXOPES']);
$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
$des_senhaus = fnEncode(fnLimpaCampo($_REQUEST['DES_SENHAUS']));
if (empty($_REQUEST['LOG_EMAIL'])) {$log_email='N';}else{$log_email=$_REQUEST['LOG_EMAIL'];}
if (empty($_REQUEST['LOG_SMS'])) {$log_sms='N';}else{$log_sms=$_REQUEST['LOG_SMS'];}

$tip_cliente = 'F';

if(strlen($num_cgcecpf) > 11){
	$tip_cliente = 'J';
}

$newDate = explode('/', $dat_nascime);
$dia = $newDate[0];
$mes   = $newDate[1];
$ano  = $newDate[2];

$hoje=explode("/",date("d/m/Y"));
$idade = $hoje[2]-$newDate[2];

if ($newDate[1] > $hoje[1]) {

	$idade = $idade-1;

} else if ($newDate[1] == $hoje[1]) {

	if ($newDate[2] <= $hoje[2]) {

		$idade = $idade;

	} else {

		$idade = $idade-1;



	}

} else{

	$idade = $idade;

}

$sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$num_cgcecpf' AND COD_EMPRESA = $cod_empresa";

switch($opcao){

	case "cadastrar":

		$linha = mysqli_num_rows(mysqli_query(connTemp($cod_empresa,''),$sql));

		if($linha == 0){

			$sql2 ="INSERT INTO CLIENTES(
								NUM_CGCECPF,
								NUM_CARTAO,
								COD_EMPRESA,
								NOM_CLIENTE,
								DAT_NASCIME,
								DIA, 
								MES, 
								ANO,
								IDADE,
								DES_EMAILUS,
								NUM_CELULAR,
								COD_SEXOPES,
								COD_UNIVEND,
								DES_SENHAUS,
								LOG_EMAIL,
								LOG_SMS,
								TIP_CLIENTE,
								DAT_CADASTR
								)VALUES(
								'$num_cgcecpf',
								'$num_cgcecpf',
								'$cod_empresa',
								'$nom_cliente',
								'$dat_nascime',
								'$dia',
								'$mes',
								'$ano',
								'$idade',
								'$des_emailus',
								'$num_celular',
								'$cod_sexopes',
								'$cod_univend',
								'$des_senhaus',
								'$log_email',
								'$log_sms',
								'$tip_cliente',
								NOW()
								)";

			// echo($sql2);

			if(mysqli_query(connTemp($cod_empresa,''),$sql2)){



			?>

				<div class="push50"></div>
				<div class="push100"></div>
				<div class="col-md-12 text-center">
					<h3>Cadastro efetuado com sucesso!</h3><br>
					<span class="fa fa-check-circle fa-2x"></span>
				</div>

			<?php 

			}else{

			?>

				<div class="push50"></div>
				<div class="push100"></div>
				<div class="col-md-12 text-center">
					<h3>Erro ao cadastrar.</h3><br>
					<span class="fa fa-times-circle fa-2x"></span>
				</div>

			<?php 

			}
		}else{
			?>

				<div class="push50"></div>
				<div class="push100"></div>
				<div class="col-md-12 text-center">
					<h3>Cliente já cadastrado.</h3><br>
					<span class="fa fa-exclamation-circle fa-2x text-warning"></span>
				</div>

			<?php 
		}

	break;

	case "atualizar":

		$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		$sql3 ="UPDATE CLIENTES SET
					NOM_CLIENTE='$nom_cliente',
					DAT_NASCIME='$dat_nascime',
					DIA='$dia', 
					MES='$mes', 
					ANO='$ano',
					IDADE='$idade',
					DES_EMAILUS='$des_emailus',
					NUM_CELULAR='$num_celular',
					COD_SEXOPES='$cod_sexopes',
					COD_UNIVEND='$cod_univend',
					DES_SENHAUS='$des_senhaus',
					LOG_EMAIL='$log_email',
					LOG_SMS='$log_sms',
					TIP_CLIENTE='$tip_cliente',
					DAT_ALTERAC=NOW()
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CLIENTE = $qrCli[COD_CLIENTE]";

			//fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa,''),$sql3);

			?>

				<div class="push50"></div>
				<div class="push100"></div>
				<div class="col-md-12 text-center">
					<h3>Cliente atualizado com sucesso.</h3><br>
					<span class="fa fa-check fa-2x text-success"></span>
				</div>

			<?php 

	break;

}



?>
