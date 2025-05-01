<?php

	//echo fnDebug('true');

$hashLocal = mt_rand();	

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

		$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];			

		if ($opcao != ''){
			
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

	//busca dados da empresa
	//$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];
	//echo "<h5>"."oiiii"."</h5>" ;
	//echo "<h5>sistema - ".$_SESSION["SYS_COD_SISTEMA"]."</h5>" ;
	//echo "<h5>usuario - ".$_SESSION["SYS_COD_USUARIO"]."</h5>" ;
$cod_empresa = fnDecode($_GET['id']);
if ($_SESSION["SYS_COD_SISTEMA"] == 18){
	$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];
}

$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)){
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
}

	//liberação das abas
$abaPersona	= "S";
$abaCampanha = "S";
$abaVantagem = "N";
$abaRegras = "N";
$abaComunica = "N";
$abaAtivacao = "N";
$abaResultado = "N";

$abaPersonaComp = "active ";
$abaCampanhaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";

	//revalidada na aba de regras	
$abaAtivacaoComp = "";

	//Busca módulos autorizados
$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
COD_SISTEMA = 18
AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

	//echo($qrAut['COD_MODULOS']);

	//echo "<pre>";	
	//print_r($modsAutorizados);	
	//echo "</pre>";

	//echo(fnControlaAcesso("1049",$modsAutorizados));

	//fnEscreve($cod_empresa);
	//echo($cod_empresa);
	//echo("<br>");
	//echo($_SESSION["SYS_COD_SISTEMA"]);

?>

<style>
	.fa-1dot5x{
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}
	
	.tile.tile-default {
	  border: unset;
	}

	/*.tile.tile-default:hover {
	  box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.25);
	-webkit-box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.25);
	-moz-box-shadow: 0px 1px 6px 1px rgba(0,0,0,0.25);
	}*/
	
</style>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div> 

<div class="row">				

	<div class="col-md12 margin-bottom-30">

		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-title">
				<div class="caption">
					<i class="far fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php 
									//$formBack = "1048";
				include "atalhosPortlet.php"; ?>

			</div>								

			<div class="push10"></div> 

			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>	
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="push20"></div>

				<div class="row">


					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-users-medical"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">3</div>
								<div class="widget-title">clientes</div>
								<div class="widget-subtitle">Cedentes de Garantias</div>
							</div>      
						</div>                            

					</div>

					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-file-search"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">48</div>
								<div class="widget-title">Garantias</div>
								<div class="widget-subtitle">Em Análise</div>
							</div>      
						</div>                            

					</div>

					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-hand-holding-box"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">8</div>
								<div class="widget-title">Garantias</div>
								<div class="widget-subtitle">Ativas</div>
							</div>      
						</div>                            

					</div>

					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-coins"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">100.000</div>
								<div class="widget-title">Tokens</div>
								<div class="widget-subtitle">Gerados</div>
							</div>      
						</div>                            

					</div>

					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-users-class"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">48</div>
								<div class="widget-title">Usuários</div>
								<div class="widget-subtitle">Compradores</div>
							</div>      
						</div>                            

					</div>

					<div class="col-md-2">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-hand-holding-usd"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count">10.500</div>
								<div class="widget-title">Circulação</div>
								<div class="widget-subtitle">Tokens Vendidos</div>
							</div>      
						</div>                            

					</div>



					<div class="push30"></div>

					<h3 style="margin: 0 0 30px 15px;"><b>Dia a Dia:</b> Quick <strong>Links</strong></h3>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1691",$modsAutorizados) === true) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1691)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-users fa-1dot5x"></span>
							<p style="height: 40px;">Clientes</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1693",$modsAutorizados) === true) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1693)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-chart-line fa-1dot5x"></span>
							<p style="height: 40px;">Relatórios</p>                            
						</a>                        
					</div>	

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1694",$modsAutorizados) === true) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1694)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-dollar-sign fa-1dot5x"></span>
							<p style="height: 40px;">Resultados</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1695",$modsAutorizados) === true) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1695)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-cogs fa-1dot5x"></span>
							<p style="height: 40px;">Configurações</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1696",$modsAutorizados) === true) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1696)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-cart-plus fa-1dot5x"></span>
							<p style="height: 40px;">Campanhas</p>                            
						</a>                        
					</div>

					<?php if($cod_empresa == 274) { ?>												
						<div class="col-md-2 col-xs-6">
							<a href="action.do?mod=<?php echo fnEncode(1820)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
								<div class="push10"></div>
								<span class="fal fa-calendar-alt fa-1dot5x"></span>
								<p style="height: 40px;">Central de Reservas</p>                            
							</a>                        
						</div>
					<?php }?>

				</div>

				<div class="push20"></div>

				<?php
				$dias = 31;
				$diasDoMes = array();

				$i = 1;
				while ($i <= $dias) {
					$diasDoMes[] = $i;
					$i++;
				}

				for ($i = 1; $i <= $dias; $i++) {
					$valor = rand(100, 1000) / 100;
					$listaValoresMonetarios[] = $valor;
				}
				?>

				

				<div class="row ">

					<div class="col-md-9">

						<h3 style="margin: 0 0 30px 0;">Histórico <b>Mensal</b> de Valoração dos <strong>Tokens</strong></h3>

						<div class="push20"></div>
						<div style="height: 550px; width:100%;">
							<canvas id="lineChart"></canvas>
						</div>
						
					</div>
					
					<div class="col-md-3 text-center">
						<div class="push50"></div>
						<div class="push20"></div>
						<i class="fas fa-caret-up fa-3x text-success"></i> &nbsp;&nbsp;		
						<span class="f26"><span class="f16">R$</span> 999,99</span> &nbsp;&nbsp; +2,34 (0,4%)	  

					</div>

					<div class="push50"></div>
					
				</div>
								
							
			</div>
		</div>	
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

<div class="push20"></div>

<form id="formModal">					
	<input type="hidden" class="input-sm" name="REFRESH_CAMPANHA" id="REFRESH_CAMPANHA" value="N"> 
	<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N"> 					
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<script src="js/pie-chart.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script type="text/javascript">
	
	$(document).ready(function(){

			//modal close
		$('#popModal').on('hidden.bs.modal', function () {

			if ($('#REFRESH_PERSONA').val() == "S"){
				//alert("atualiza");
				RefreshPersona("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_PERSONA').val("N");				
			}	

			if ($('#REFRESH_CAMPANHA').val() == "S"){
				//alert("atualiza");
				RefreshCampanha("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_CAMPANHA').val("N");				
			}

		});

	});

	// Line chart
	var ctx = document.getElementById("lineChart");
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: <?php echo json_encode($diasDoMes); ?>,
			datasets: [{
				<?php if ($log_labels == 'S') { ?>
					datalabels: {
						clamp: true,
						align: 'start',
						anchor: 'end',
						borderRadius: 6,
						backgroundColor: '#36A2EB',
						color: '#fff',
						formatter: function(value) {
							return '$' + (value * 1000).toFixed(2);
						}
					},
				<?php } ?>
				label: "Valor atingido",
				backgroundColor: "rgba(93, 173, 226, 0)",
				borderColor: "#36A2EB",
				pointBorderColor: "#36A2EB",
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?php echo json_encode($listaValoresMonetarios) ?>,
				lineTension: 0
			}]
		},
		<?php if ($log_labels == 'S') { ?>
			plugins: [ChartDataLabels],
		<?php } ?>
		options: {
			legend: {
				display: true,
				position: 'bottom'
			},
			maintainAspectRatio: false,
			animation: {
				duration: 2000,
			},
			scales: {
				yAxes: [{
					ticks: {
						suggestedMin: 0,
						callback: function(value) {
							return 'R$' + (value * 1000).toLocaleString();
						}
					}
				}],
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						return 'R$' + (tooltipItem.yLabel * 1000).toFixed(2);
					}
				}
			},
		}
	});


	function RefreshPersona(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshPersona.do",
			data: { ajx1:idEmp},
			beforeSend:function(){
				$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#div_refreshPersona").html(data); 
			},
			error:function(){
				$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});		
	}

	function RefreshCampanha(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshCampanha.do#campanha",
			data: { ajx1:idEmp},
			beforeSend:function(){
				$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#div_refreshCampanha").html(data); 
			},
			error:function(){
				$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});		
	}		

	function retornaForm(index){
		$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val());
		$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_"+index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
		$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_"+index).val());
		$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_"+index).val()).trigger("chosen:updated");
		$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');						
	}

</script>	