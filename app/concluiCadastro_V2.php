<?php 

include 'header.php'; 
$tituloPagina = "Cadastro";
include "navegacao.php"; 

include_once '../totem/funWS/atualizacadastro.php';
include_once '../totem/funWS/TKT.php';
$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO = 10 
			  AND COD_EXCLUSA = 0 limit 1  ";
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

$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);

if($cod_univend == 0){
	$cod_univend = $qrLista['COD_UNIVEND'];
}

$idlojaKey = $cod_univend;
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

// definindo que os dados vem da app
$isApp = true;
// WEBSERVICE DE CADASTRO MAIS.CASH
include '../totem/cadastroMaisCashWS.php';


$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_img = $qrControle['DES_IMG'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$des_img_g = $des_img;

?>

<div class="push30"></div>

<style>
	body{
		overflow: hidden!important;
		height: 100vh!important;
	}
	.container{
		max-height: 350px!important;
	}
</style>
	
<div class="container" style="padding-top: 1100px;">

	<div class="row" id="corpoForm">

		<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro_V2.do?id=<?=$_GET[key]?>&pop=true" autocomplete="off">

			<div class="col-md-6 col-xs-12" id="caixaImg">
				<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
			</div>

			<div class="col-md-6 col-xs-12 text-center" id="caixaForm" style="background-color: #FFF;">

				<div class="push20"></div>
				<div class="push50"></div>
				
				<h3>Cadastro <?=$atualiza?></h3>

				<a href="app.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-primary btn-block">Fazer login</a>
				<div class="push5"></div>
				<div class="text-muted f12">OU</div>
				<div class="push5"></div>
				<a href="intro.do?key=<?=$_GET[key]?>&t=<?=$rand?>" class="btn btn-default btn-block" style="margin-top: 0;">Voltar ao início</a>
				<div class="push20"></div>

				<?php if($usuario != ""){ ?>

					<!-- <a href="novoMenu.do?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="btn btn-info btn-block">Ir para a home</a> -->

				<?php }else{ ?>

					

				<?php } ?>

			</div>

			
			<input type="hidden" name="opcao" id="opcao" value="">
			<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
			<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
			
		</form>
		
	</div><!-- /container -->
    

</div> <!-- /container -->



<?php include 'footer.php'; ?>

<script>
	window.scrollTo({ top: 0, behavior: 'smooth' });
	document.body.style.overflow='hidden';
</script>

