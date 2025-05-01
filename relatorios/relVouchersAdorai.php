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
										<th class="text-center">Cód. Voucher</th>
										<th class="text-center">Valor</th>
										<th class="text-center">Ref. Reserva</th>
										<th class='text-center'>Data Cadastro</th>
										<th class='text-center'>Status</th>
										<th class='text-center'>Data Expiração</th>
										<th class='text-center { sorter: false }'></th>
									</tr>
								</thead>

								<tbody id='div_refreshCarrinho'>

									<?php
									

									$sqlLote = "SELECT * FROM adorai_voucher
													WHERE COD_EMPRESA = $cod_empresa";
									$arrLote = mysqli_query(connTemp($cod_empresa,''),$sqlLote);

									while ($qrBusca = mysqli_fetch_assoc($arrLote)) {
										$count++;

										switch ($qrBusca['LOG_STATUS']) {
											case 'D':
												$status = 'Disponivel';
												break;
											case 'L':
												$status = 'Liquidado';
												break;
											case 'C':
												$status = 'Cancelado';
												break;
											case 'E':
												$status = 'Expirado';
												break;
											default:
												$status = 'Estornado pix';
												break;
										}

										?>
										<tr>
											<td width="50"></td>
											<td class='text-center'><small><?=$qrBusca['ID_VOUCHER']?></small></td>
											<!-- <td class='text-center'><small><?=$qrBusca['DES_VOUCHER']?></small></td> -->
											<td class='text-center'><small><?=fnValor($qrBusca['VL_VOUCHER'],2)?></small></td>
											<td class='text-center'><small><?=$qrBusca['COD_PEDIDO']?></small></td>
											<td class='text-center'><small><?=fnDataShort($qrBusca['DAT_CADASTR'])?></small></td>
											<td class='text-center'><small><?=$status?></small></td>
											<td class='text-center'><small><?=fnDataShort($qrBusca['DAT_EXPIRA'])?></small></td>
											<td class='text-center'></td>
											<?php if($qrBusca['LOG_STATUS'] == 'D'){?>
												<td width='40' class='text-center transparency'>
													<small>
														<div class='btn-group dropdown dropleft'>
															<a href='javascript:void(0)' class="btn btn-xs btn-info" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
																&nbsp;&nbsp;<span class="fal fa-cog"></span>&nbsp;&nbsp;
															</a>
															<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
																<li>
																	<a href='javascript:void(0)' data-url='action.do?mod=<?= fnEncode(2038)?>&id=<?= fnEncode($cod_empresa)?>&vch=<?= fnEncode($qrBusca['ID_VOUCHER'])?>&pop=true' class='addBox' data-title='Altera Voucher'>
																		<div class="row">
																			<div class="col-xs-2 text-center" style="padding: 0;">
																				<div class="push5"></div>
																				<span class="fal fa-dollar-sign"></span>
																			</div>
																			<div class="col-xs-9" style="padding: 0;">
																				&nbsp;&nbsp;Pagar com Pix
																			</div>
																		</div>
																	</a>
																</li>
															</ul>
														</div>
													</small>
												</td>
											<?php }else{?>
												<td></td>
											<?php }?>						
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


<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">

</script>