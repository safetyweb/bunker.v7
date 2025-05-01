<?php 

include 'header.php'; 
$tituloPagina = "Alterar Senha";
include "navegacao.php"; 

$cod_cliente = fnLimpaCampo(fnDecode($_POST['COD_CLIENTE']));
$num_cgcecpf = fnLimpaCampo(fnDecode($_POST['NUM_CGCECPF']));
$des_token = fnLimpaCampo(fnDecode($_POST['DES_TOKEN']));
$des_senhaus = fnEncode($_POST['DES_SENHAUS']);
//busaca clientes por cpf

//CAMPO DE LOGIN
$campoLogin = fnDecode($_POST['fkey']);
// VALOR DE LOGIN
$dadoLogin = fnDecode($_POST['vkey']);

$sqlPwd = "SELECT DES_SENHAUS FROM CLIENTES 
			WHERE COD_EMPRESA = $cod_empresa
			AND COD_CLIENTE = $cod_cliente";
$arrPwd = mysqli_query(connTemp($cod_empresa,''),$sqlPwd);

$qrPwd = mysqli_fetch_assoc($arrPwd);

$sqlControle = "INSERT INTO CONT_PWD(
							COD_EMPRESA,
							COD_CLIENTE,
							DES_SENHAUS,
							COD_USUCADA
						) VALUES(
							$cod_empresa,
							$cod_cliente,
							'".$qrPwd[DES_SENHAUS]."',
							9999
						)";

mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$sqlSenha = "UPDATE CLIENTES SET 
						DES_SENHAUS = '$des_senhaus',
						COD_ALTERAC = 9999,
						DAT_ALTERAC = NOW()
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CLIENTE = $cod_cliente";

mysqli_query(connTemp($cod_empresa,''),$sqlSenha);


//if($num_cgcecpf == "01734200014"){
	
	$sqlUpdt = "UPDATE TOKENAPP SET 
							LOG_USADO = 2,
                            DAT_USADO=NOW()
			WHERE COD_EMPRESA = $cod_empresa
			AND NUM_CGCECPF = '$num_cgcecpf'
			AND DES_TOKEN = '$des_token'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdt);
	
//}

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

?>

<form data-toggle="validator" role="form2" method="post" id="formulario" action="sucessoSenha.do?key=<?=$_GET["key"]?>">
	
    <div class="container">

		<div class="row">
			
			<div class="col-md-6 col-xs-12" id="caixaImg">
				<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
			</div>
			<div class="col-md-6 col-xs-12" id="caixaForm" style="background-color: #FFF;">

				<div class="push20"></div>
				<div class="push50"></div>

				<div class="text-center">
					<h3>Sua senha foi configurada!</h3>
					<p>Faça o <b>login</b> para <b>atualizar seu cadastro</b> e acessar mais opções!</p>
					<a href="app.do?key=<?=$_GET["key"]?>&t=<?=$rand?>" class="btn btn-info btn-block">Fazer login</a>				
				</div>

			</div>

		</div>
        

    </div> <!-- /container -->

</form>

<?php include 'footer.php'; ?>