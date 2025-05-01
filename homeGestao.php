<?php

	//echo fnDebug('true');

$hashLocal = mt_rand();	

$usuario = $_SESSION['SYS_COD_USUARIO'];
$sistema = $_SESSION['SYS_COD_SISTEMA'];

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
				
				<?php 
					
					//busca dados do usuário - session usuário
															
					//$sql = "select * from usuarios where COD_EMPRESA = '".$_SESSION["SYS_COD_EMPRESA"]."' and COD_USUARIO = '".$_SESSION["SYS_COD_USUARIO"]."' and DAT_EXCLUSA is null ";
					
					$sql = "SELECT CLIEN.TOT_CLIENTE,
								   -- CLIEN.QTD_MASC,
								   -- CLIEN.QTD_FEMI,
								   -- CLIEN.QTD_IND,
								   ATEND.TOT_ATENDIMENTO,
								   ATEND.QTD_ATEND_REALIZADO,
								   ATEND.QTD_ATEND_PENDENTE,
								   ATEND.QTD_ATEND_NAO_REALIZADO,
								   ATEND.QTD_ATEND_INICIADO 
							FROM 
							(
							SELECT COUNT(*) AS TOT_CLIENTE /*,
								ifnull(SUM( case when  COD_SEXOPES=1 then
								   1
								END),0)  QTD_MASC,

								ifnull(SUM( case when  COD_SEXOPES=2 then
								   1
								END),0)  QTD_FEMI,
								ifnull(SUM( case when  COD_SEXOPES=3 then
								   1
								END),0)  QTD_IND */

							 FROM CLIENTES
							WHERE cod_empresa=$cod_empresa
							) AS CLIEN,
							(SELECT COUNT(*) AS TOT_ATENDIMENTO, 
								ifnull(SUM( case when  COD_STATUS=17 then
								   1
								END),0)  QTD_ATEND_REALIZADO,
								ifnull(SUM( case when  COD_STATUS=18 then
								   1
								END),0)  QTD_ATEND_PENDENTE,
								ifnull(SUM( case when  COD_STATUS=19 then
								   1
								END),0)  QTD_ATEND_NAO_REALIZADO,	
								ifnull(SUM( case when  COD_STATUS=20 then
								   1
								END),0)  QTD_ATEND_INICIADO

							FROM atendimento_chamados
							WHERE COD_EMPRESA=$cod_empresa

							)AS ATEND";
					
					//fnTestesql($connAdm->connAdm(),$sql);
					//fnEscreve2($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
					$qrDadosGestao = mysqli_fetch_assoc($arrayQuery);

					$tot_cliente = $qrDadosGestao['TOT_CLIENTE'];
					$tot_atendimento = $qrDadosGestao['TOT_ATENDIMENTO'];
					$qtd_atend_realizado = $qrDadosGestao['QTD_ATEND_REALIZADO'];
					$qtd_atend_pendente = $qrDadosGestao['QTD_ATEND_PENDENTE'];
					$qtd_atend_nao_realizado = $qrDadosGestao['QTD_ATEND_NAO_REALIZADO'];
					$qtd_atend_iniciado = $qrDadosGestao['QTD_ATEND_INICIADO'];

					//fnMostraForm();
					//fnEscreve($cod_usuario);
					// fnEscreve(fnEncode("multi"));
						
				?>				
				

				<div class="row">

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-users"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $tot_cliente; ?></div>
								<div class="widget-title">clientes</div>
								<div class="widget-subtitle">Cadastros Ativos</div>
							</div>      
						</div>                            

					</div>

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-user-edit"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $tot_atendimento; ?></div>
								<div class="widget-title">ATENDIMENTOS</div>
								<div class="widget-subtitle">Total Registrados</div>
							</div>      
						</div>                            

					</div>

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-comment-check"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $qtd_atend_realizado; ?></div>
								<div class="widget-title">REALIZADOS</div>
								<div class="widget-subtitle">Atendimentos Concluídos</div>
							</div>      
						</div>                            

					</div>

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-times"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $qtd_atend_nao_realizado; ?></div>
								<div class="widget-title">NÃO REALIZADOS</div>
								<div class="widget-subtitle">Atendimentos Falhos</div>
							</div>      
						</div>                            

					</div>

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-calendar-edit"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $qtd_atend_iniciado; ?></div>
								<div class="widget-title">INICIADOS</div>
								<div class="widget-subtitle">Novos em Andamento</div>
							</div>      
						</div>                            

					</div>

					<div class="col-lg-2 col-md-6 col-sm-6">

						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-user-clock"></span>
							</div>                             
							<div class="widget-data">
								<div class="widget-int num-count"><?= $qtd_atend_pendente; ?></div>
								<div class="widget-title">PENDENTES</div>
								<div class="widget-subtitle">Em Atendimento</div>
							</div>      
						</div>                            

					</div>

					<div class="push30"></div>

					<h3 style="margin: 0 0 30px 15px;"><b>Workspace</b></h3>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1424",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1424)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-users fa-1dot5x"></span>
							<p style="height: 40px;">Clientes</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1400",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1400)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-calendar-alt fa-1dot5x"></span>
							<p style="height: 40px;">Agenda</p>                            
						</a>                        
					</div>	

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1435",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1435)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-user-edit fa-1dot5x"></span>
							<p style="height: 40px;">Atendimentos</p>                            
						</a>                        
					</div>	

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1429",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1429)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-file-chart-line fa-1dot5x"></span>
							<p style="height: 40px;">Relatórios</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1549",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1549)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">

							<div class="push10"></div>
							<span class="fal fa-map-marker-alt fa-1dot5x"></span>
							<p style="height: 40px;">Geo Referência</p>                            
						</a>                        
					</div>

					<div class="col-md-2 col-xs-6">

						<?php if(!fnControlaAcesso("1886",$modsAutorizados) === false) { ?>
							<div class="disabledBlock"></div>
						<?php } ?>
						<a href="action.do?mod=<?php echo fnEncode(1886)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
							<div class="push10"></div>
							<span class="fal fa-users-class fa-1dot5x"></span>
							<p style="height: 40px;">Perfil Dinâmico</p>                            
						</a>                        
					</div>

					<?php

						$sqlVerifica = "SELECT * FROM LINKS_WORKSPACE 
										WHERE COD_USUARIO = '$usuario' 
										AND COD_SISTEMA = '$sistema'";

						$arrVerifica = mysqli_query(connTemp($_SESSION['SYS_COD_EMPRESA'],''), $sqlVerifica);
						while($qrVerifica = mysqli_fetch_assoc($arrVerifica)){

							$mod = $qrVerifica['COD_MODULO'];
							$sqlMod = "SELECT * FROM MODULOS WHERE COD_MODULOS = '$mod'";
							$arrMod = mysqli_query($connAdm->connAdm(), $sqlMod);
							$qrMod = mysqli_fetch_assoc($arrMod);
							$nom_modulo = $qrMod['NOM_MODULOS'];
					?>

						<div class="col-md-2 col-xs-6">

						<a href="<?php echo $qrVerifica['DES_URL']; ?>" class="tile tile-default shadow" style="color: #2c3e50;">
							<div class="push10"></div>
							<span class="fal fa-thumbtack fa-1dot5x"></span>
							<p style="height: 40px;"><?=$nom_modulo;?></p>                            
						</a>                        
						</div>

					<?php
						}
					
					?>

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

						<!--<h3 style="margin: 0 0 30px 0;">Histórico <b>Mensal</b> de Valoração dos <strong>Tokens</strong></h3>-->

						<div class="push100"></div>
						
					</div>
					
					<div class="col-md-3 text-center">
						<div class="push100"></div>

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