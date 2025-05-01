<?php

//echo "<h5>_".$opcao."</h5>";

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$dias30="";
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_origem = fnLimpaCampo($_REQUEST['DES_ORIGEM']);
		$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			
			
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}



//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

				<?php 
					$abaAdorai = 2006;
					include "abasAdorai.php"; 

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">
									
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
					

					<div class="row">
						<div class="services-list buscavel">
						
							<div class="row" style="margin: 0 0 0 1px;">
							
								<div class="col-sm-6 col-md-4">
									<div class="service-block" style="visibility: visible;">
										<div class="text-block">
											<div class="text">Pagamento</div>
											<div class="push10"></div>
											
											<a href="https://roteirosadorai.com.br/checkout.php" target="_blank">&rsaquo; Checkout</a> <br/>
											<a href="https://roteirosadorai.com.br/checkout.php?datI=29/04/2024&datF=30/04/2024&idh=977&idc=7715546&numC=15981146246&iv=ODAw&infQ=eyJpZEhvdGVsIjoiOTc3IiwiaWRRdWFydG8iOiI3NzE1NTQ2IiwidWYiOiIiLCJjb2RWZW5kZWRvciI6IiIsImNoYWxlIjoiRmFtXHUwMGVkbGlhIiwibG9jYWwiOiIiLCJkaWFyaWEiOiI0MDAiLCJkaWFyaWFzIjp7IjIwMjQtMDQtMjZfMjAyNC0wNC0yNyI6IjQwMCIsIjIwMjQtMDQtMjZfMjAyNC0wNC0yOCI6IjQwMCJ9LCJ0b3RhbCI6ODAwLCJkYXRhTWluIjoiMjAyNC0wNC0yNiIsImRhdGFNYXgiOiIyMDI0LTA0LTI4Iiwic2VtYW5hSW5pIjoiU2V4dGEiLCJzZW1hbmFGaW0iOiJEb21pbmdvIiwibnJvRGlhcmlhcyI6MiwibnJvUGVzc29hcyI6MiwiZGVzY3JpY2FvIjoiVGVzdGUiLCJpbWFnZW0iOiJodHRwczpcL1wvaW1nLmJ1bmtlci5ta1wvbWVkaWFcL2NsaWVudGVzXC9mb3Rvc19jaGFsZVwvQ2hhbGVfMzAyXC9DaGFsZV8zMDJfLV9DYXNhbF9uYV9waXNjaW5hLmpwZyIsInZpZGVvIjoiaHR0cHM6XC9cL2ltZy5idW5rZXIubWtcL21lZGlhXC9jbGllbnRlc1wvZm90b3NfY2hhbGVcL0NoYWxlXzIwXC9DaGFsZV8yMF8tX3ZpZGVvLm1wNCJ9" target="_blank">&rsaquo; Checkout c/ parâmetros</a> <br/>
											
											
										</div>
									</div>
								</div>	

							</div>
							
						</div>
						
					</div>

									

					
					</form>
					
					<div class="push50"></div>
				
				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">

	

</script>