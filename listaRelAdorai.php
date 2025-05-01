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
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">
									
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
					

					<div class="row">
						<div class="services-list buscavel">
						
							<div class="row" style="margin: 0 0 0 1px;">
							
								<div class="col-sm-6 col-md-4">
									<div class="service-block" style="visibility: visible;">
										<div class="ico fal fa-chart-pie highlight"></div>
										<div class="text-block">
											<h4>Dash Board</h4>
											<div class="text">Infográficos incríveis</div>
											<div class="push10"></div>
											
											<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1862)."&id=".fnEncode($cod_empresa); ?>">&rsaquo; Consultas</a> <br/>
											<a href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1900)."&id=".fnEncode($cod_empresa); ?>">&rsaquo; Leads Reserva</a> <br/>
											<a class="disabled" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1865)."&id=".fnEncode($cod_empresa); ?>">&rsaquo; Dash consulta - Em desenvolvimento </a> <br/>
											
											
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