<?php

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

	}
}

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
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
	$cod_empresa = 274;
}


?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}
	tr{
		border-bottom: none!important;
	}
	#blocker
	{
		display:none; 
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}

	/*Menu DropDown*/
	.menu{
		top: 0 !important;
		left: -100px !important;
		width: 100px !important;
		z-index: 9999999;
		font-size: 13px !important;
	}



	.menu li a{
		color: #3c3c3c!important;
	}



	.menu-down-right,.menu-down-left,.menu.menu--right{
		transform-origin: top left !important;	
	}
	
	@media screen and (max-width:778px){
		.dropleft ul{
			right: inherit !important;
		}
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				$abaManutencaoAdorai = 2019;
					//echo $abaUsuario;

					//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">					

					<div class="no-more-tables">

						<form name="formLista">
							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class="text-center { sorter: false }" width="50"></th>
										<th class="text-center">Cód. Devolução</th>
										<th class="text-center">Cód. Reserva</th>
										<th class="text-center">Valor Devolução</th>
										<th class='text-center'>Data Limite</th>
										<th class='text-center'>Data de Devolução</th>
										<th class='text-center'>Forma de Devolução</th>
										<th class='text-center'>Status</th>
										<th class='text-center'>Data de Solicitação</th>
										<th class='text-center { sorter: false }'>Comprovante</th>
										<th class='text-center { sorter: false }'></th>
									</tr>
								</thead>


								<tbody id='div_refreshCarrinho'>

									<?php
									

									$sqlLote = "SELECT 
													AD.*, 
													ATD.DES_TIPDEVE 
													FROM adorai_devolucoes AS AD
													INNER JOIN adorai_tipo_devolucao AS ATD ON ATD.cod_tipdeve = AD.TIP_DEVOLUCAO
													WHERE COD_EMPRESA = $cod_empresa";
									$arrLote = mysqli_query(connTemp($cod_empresa,''),$sqlLote);

									while ($qrBusca = mysqli_fetch_assoc($arrLote)) {
										$count++;

										if($qrBusca['COD_STATUS'] == 0){
											$status = "Aberto";
										}else{
											$status = "Liquidada";
										}

										?>
										<tr>
											<td width="50"></td>
											<td class='text-center'><small><?=$qrBusca['COD_DEVOLUCAO']?></small></td>
											<td class='text-center'><small><?=$qrBusca['COD_PEDIDO']?></small></td>
											<td class='text-center'><small><?=fnValor($qrBusca['VAL_DEVOLUCAO'],2)?></small></td>
											<td class='text-center'><small><?=fnDataShort($qrBusca['DAT_LIMITE'])?></small></td>
											<td class='text-center'><small><?=fnDataShort($qrBusca['DAT_DEVOLUCAO'])?></small></td>
											<td class='text-center'><small><?=$qrBusca['DES_TIPDEVE']?></small></td>
											<td class='text-center'><small><?=$status?></small></td>
											<td class='text-center'><small><?=fnDataShort($qrBusca['DAT_CADASTR'])?></small></td>
											<?php if($qrBusca['DES_IMAGEM']){ ?>
												<td class='text-center'><a style="margin-right: 12px;" href="https://adm.bunker.mk/media/clientes/<?=$cod_empresa?>/<?=$qrBusca['DES_IMAGEM']?>" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span></a><a href="https://adm.bunker.mk/media/clientes/<?=$cod_empresa?>/<?=$qrBusca['DES_IMAGEM']?>" download><span class="fa fa-download"></span></a></td>
											<?php }else{ ?>
												<td></td>
											<?php } ?>	
											<td width='40' class='text-center transparency'>
												<small>
													<div class='btn-group dropdown dropleft'>
														<a href='javascript:void(0)' class="btn btn-xs btn-info" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
															&nbsp;&nbsp;<span class="fal fa-cog"></span>&nbsp;&nbsp;
														</a>
														<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
															<li>
																<a href='javascript:void(0)' data-url='action.do?mod=<?= fnEncode(2036)?>&id=<?= fnEncode($cod_empresa)?>&dev=<?= fnEncode($qrBusca['COD_DEVOLUCAO'])?>&pop=true' class='addBox' data-title='Consolidar Estorno'>
																	<div class="row">
																		<div class="col-xs-2 text-center" style="padding: 0;">
																			<div class="push5"></div>
																			<span class="fal fa-dollar-sign"></span>
																		</div>
																		<div class="col-xs-9" style="padding: 0;">
																			&nbsp;&nbsp;Pagar
																		</div>
																	</div>
																</a>
															</li>
														</ul>
													</div>
												</small>
											</td>							
										</tr>
										<?php 

									}
									?>	

								</tbody>

								<div class="push20"></div>
								
								<tfoot>
									<tr>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
									</tr>
								</tfoot>

							</table>
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

	<div class="modal fade" id="popModal"  tabindex='-1'>
		<div class="modal-dialog " style="max-width: 60% !important;">
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

</div>

	<style type="text/css">
	
/* The Overlay (background) */
.overlay {
  /* Height & width depends on how you want to reveal the overlay (see JS below) */    
  height: 100%;
  width: 100%;
  position: fixed; /* Stay in place */
  left: 0;
  top: 0;
  background-color: rgba(0,0,0, 0.9); /* Black w/opacity */
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
  display: none;
  z-index: 9999;
}

/* Position the content inside the overlay */
.overlay-content {
  position: relative;
  top: 0; /* 5% from the top */
  width: 80%; /* 100% width */
  text-align: center; /* Centered text/links */
  margin-left: auto;
  margin-right: auto;
}

/* Position the close button (top right corner) */
.overlay .closebtn {
  position: absolute;
  top: 60px;
  right: 45px;
  font-size: 60px;
}

.modal-dialog2{
    width: 100vw;
    height: 100vh;
   
}

.modal-content2{
	width: 100vw;
	height: 100vh;
	border-radius: 0;
}


</style>

<!-- The overlay -->
<div id="myNav" class="overlay">

  <!-- Button to close the overlay navigation -->
  <div class="push50"></div>
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

  <!-- Overlay content -->
  <div class="overlay-content">
   	<iframe name="files" id="files" src='' width='100%' height='100%' frameborder='0'></iframe>
  </div>

</div>


<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">

	function openNav() {
		$('#myNav').show();
		try {
			$('.modal-dialog').attr("class", 'modal-dialog2');
			$('.modal-content').attr("class", 'modal-content2');
		} catch(err) {}
	}

	/* Close */
	function closeNav() {
		$('#myNav').hide();
		try { 
			$('.modal-dialog2').attr("class", 'modal-dialog');
			$('.modal-content2').attr("class", 'modal-content');
		} catch(err) {}
		$('#files').attr('src', '');
	}
</script>