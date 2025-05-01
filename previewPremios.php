<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$abaMetas = "";
$lbl = "";
$linha = "";
$i = "";
$txtAbast = "";
$randAbast = "";
$saldoAbast = "";
$icoAbast = "";
$totalAbast1 = 0;
$totalAbast2 = 0;
$totalAbast3 = 0;
$randAditiv = "";
$saldoAditiv = "";
$txtAditiv = "";
$icoAditiv = "";
$totalAditiv1 = 0;
$totalAditiv2 = 0;
$totalAditiv3 = 0;
$rand20Lit = "";
$saldo20Lit = "";
$txt20Lit = "";
$ico20Lit = "";
$total20Lit1 = 0;
$total20Lit2 = 0;
$total20Lit3 = 0;
$randFidel = "";
$saldoFidel = "";
$txtFidel = "";
$icoFidel = "";
$totalFidel1 = 0;
$totalFidel2 = 0;
$totalFidel3 = 0;


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {


			//mensagem de retorno
			switch ($opcao) {
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
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
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php
				$abaMetas = 1334;
				include "abasUsuariosMetas.php";
				?>

				<div class="push20"></div>

				<div class="login-form">

					<div class="col-lg-12 text-center">

						<div class="no-more-tables">

							<style>
								.membership-pricing-table {
									max-width: 1020px;
									margin: auto;
								}

								.membership-pricing-table table .icon-no,
								.membership-pricing-table table .icon-yes {
									font-size: 22px
								}

								.membership-pricing-table table .icon-no {
									color: #a93717
								}

								.membership-pricing-table table .icon-yes {
									color: #209e61
								}

								.membership-pricing-table table .plan-header {
									text-align: center;
									border: 1px solid #e2e2e2;
									padding: 15px 10px 15px 10px;
								}

								.membership-pricing-table table .plan-header-free {
									background-color: #eee;
									color: #555
								}

								.membership-pricing-table table .plan-header-blue {
									color: #fff;
									background-color: #61a1d1;
									border-color: #3989c6
								}

								.membership-pricing-table table .plan-header-standard {
									color: #fff;
									background-color: #ff9317;
									border-color: #e37900
								}

								.membership-pricing-table table td {
									text-align: center;
									padding: 7px 10px;
									/*background-color: #fafafa;*/
									background-color: #fff;
									font-size: 14px;
									-webkit-box-shadow: 0 1px 0 #fff inset;
									box-shadow: 0 1px 0 #fff inset
								}

								.membership-pricing-table table,
								.membership-pricing-table table td {
									border: 1px solid #ebebeb
								}

								.membership-pricing-table table tr td:first-child {
									background-color: transparent;
									text-align: left;
								}

								.membership-pricing-table table tr td:nth-child(5) {
									background-color: #FFF
								}

								.membership-pricing-table table tr:first-child td,
								.membership-pricing-table table tr:nth-child(2) td {
									-webkit-box-shadow: none;
									box-shadow: none
								}

								.membership-pricing-table table tr:first-child th:first-child {
									border-top-color: transparent;
									border-left-color: transparent;
									border-right-color: #e2e2e2
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-name {
									font-size: 18px;
									padding: 0 20px 0 20px;
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price {
									line-height: 35px
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price>sup {
									font-size: 45%
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price>span {
									font-size: 30%
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-period {
									font-size: 15px;
								}

								.membership-pricing-table table .header-plan-inner {
									position: relative
								}

								.membership-pricing-table table .recommended-plan-ribbon {
									box-sizing: content-box;
									background-color: #dc3b5d;
									color: #FFF;
									position: absolute;
									padding: 3px 6px;
									font-size: 11px !important;
									font-weight: 500;
									left: -6px;
									top: -22px;
									z-index: 99;
									width: 100%;
									-webkit-box-shadow: 0 -1px #c2284c inset;
									box-shadow: 0 -1px #c2284c inset;
									text-shadow: 0 -1px #c2284c
								}

								.membership-pricing-table table .recommended-plan-ribbon:before {
									border: solid;
									border-color: #c2284c transparent;
									border-width: 6px 0 0 6px;
									bottom: -5px;
									content: "";
									left: 0;
									position: absolute;
									z-index: 90
								}

								.membership-pricing-table table .recommended-plan-ribbon:after {
									border: solid;
									border-color: #c2284c transparent;
									border-width: 6px 6px 0 0;
									bottom: -5px;
									content: "";
									right: 0;
									position: absolute;
									z-index: 90
								}

								.membership-pricing-table table .plan-head {
									box-sizing: content-box;
									background-color: #ff9c00;
									border: 1px solid #cf7300;
									position: absolute;
									top: -33px;
									left: -1px;
									height: 30px;
									width: 100%;
									border-bottom: none
								}
							</style>



							<table style="max-width: 1020px; margin:auto;">

								<h3>Referência do último rateio cadastrado</h3>
								<div class="push10"></div>

								<table style="max-width: 1020px; margin:auto;" class="table table-bordered">
									<thead>
										<tr>
											<th class="header">Período</th>
											<th class="header">Valor</th>
											<th class="header">Tipo do Uso</th>
											<th class="header">Tipo do Período</th>
										</tr>
									</thead>

									<tbody>

										<tr>
											<td>06/11/2018 a 10/11/2018</td>
											<td>R$ 200,00</td>
											<td>Por unidade (rateio)</td>
											<td>Por período</td>
										</tr>

									</tbody>
								</table>

							</table>

							<div class="push50"></div>

							<?php
							$sql = "SELECT * FROM CONTROLE_METAS_DESC WHERE COD_EMPRESA = $cod_empresa";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
							$lbl = array();
							while ($linha = mysqli_fetch_assoc($arrayQuery)) {
								$lbl[$linha["COD_DESCRICAO"]] = $linha["NOM_DESCRICAO"];
							}
							$lbl["VAL_METAPROD"] = (@$lbl["VAL_METAPROD"] <> "" ? $lbl["VAL_METAPROD"] : "ABASTECIMENTOS");
							$lbl["VAL_METADEST"] = (@$lbl["VAL_METADEST"] <> "" ? $lbl["VAL_METADEST"] : "% ADITIVADA");
							$lbl["VAL_ALERTMIN"] = (@$lbl["VAL_ALERTMIN"] <> "" ? $lbl["VAL_ALERTMIN"] : "+ 20 LITROS");
							$lbl["QTD_FIDELIZ"] = (@$lbl["QTD_FIDELIZ"] <> "" ? $lbl["QTD_FIDELIZ"] : "FIDELIDADE");
							?>

							<div class="table-responsive">

								<div class="membership-pricing-table">
									<table>
										<tbody>

											<tr>
												<th></th>
												<th colspan="3" width="20%" class="plan-header plan-header-blue">
													<div class="pricing-plan-name"><?= $lbl["VAL_METAPROD"] ?><br /><span class="f12"><b>30,00%</b></span></div>
												</th>

												<th colspan="3" width="20%" class="plan-header plan-header-blue">
													<div class="pricing-plan-name"><?= $lbl["VAL_METADEST"] ?><br /><span class="f12"><b>7,50%</b></span></div>
												</th>

												<th colspan="3" width="20%" class="plan-header plan-header-blue">
													<div class="pricing-plan-name"><?= $lbl["VAL_ALERTMIN"] ?><br /><span class="f12"><b>20,00%</b></span></div>
												</th>

												<th colspan="3" width="20%" class="plan-header plan-header-standard">
													<div class="header-plan-inner">
														<div class="pricing-plan-name"><?= $lbl["QTD_FIDELIZ"] ?><br /><span class="f12"><b>20,00%</b></span></div>
													</div>
												</th>

											</tr>

											<tr>
												<th class="plan-header plan-header-free">Frentistas</th>

												<!-- abastecimentos -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Feito</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Meta</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Prêmio</div>
												</th>

												<!-- aditivada -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Feito</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Meta</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Prêmio</div>
												</th>

												<!-- 20 litros -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Feito</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Meta</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Prêmio</div>
												</th>

												<!-- fidelidade -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Feito</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Meta</div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period">Prêmio</div>
												</th>

											</tr>

											<?php
											for ($i = 1; $i <= 5; $i++) {

												//abastecimentos
												$txtAbast = "";
												$randAbast = rand(0, 120);
												$saldoAbast = rand(10, 60);
												//$saldoAbast = $randAbast + 60;				
												//if($saldoAbast > 90){
												if ($randAbast > 90) {
													$txtAbast = "text-success";
													$icoAbast = "R$";
												} else {
													$txtAbast = "";
													$icoAbast = "";
													$saldoAbast = "";
												}
												//fnEscreve($saldoAbast);				
												$totalAbast1 = $totalAbast1 + $randAbast;
												$totalAbast2 = $totalAbast2 + 90;
												$totalAbast3 = $totalAbast3 + $saldoAbast;

												//aditivada
												$randAditiv = rand(0, 60);
												//$saldoAditiv = $randAditiv + 190;
												$saldoAditiv = rand(3, 15);
												if ($randAditiv > 40) {
													$txtAditiv = "text-success";
													$icoAditiv = "R$";
												} else {
													$txtAditiv = "";
													$icoAditiv = "";
													$saldoAditiv = "";
												}
												$totalAditiv1 = $totalAditiv1 + $randAditiv;
												$totalAditiv2 = $totalAditiv2 + 40;
												$totalAditiv3 = $totalAditiv3 + $saldoAditiv;


												//20 litros
												$rand20Lit = rand(0, 40);
												//$saldo20Lit = $rand20Lit + 180;
												$saldo20Lit = rand(5, 40);
												if ($rand20Lit > 15) {
													$txt20Lit = "text-success";
													$ico20Lit = "R$";
												} else {
													$txt20Lit = "";
													$ico20Lit = "";
													$saldo20Lit = "";
												}
												$total20Lit1 = $total20Lit1 + $rand20Lit;
												$total20Lit2 = $total20Lit2 + 15;
												$total20Lit3 = $total20Lit3 + $saldo20Lit;

												//fidelizados
												$randFidel = rand(0, 90);
												//$saldoFidel = $randFidel + 160;
												$saldoFidel = rand(5, 40);
												if ($randFidel > 60) {
													$txtFidel = "text-success";
													$icoFidel = "R$";
												} else {
													$txtFidel = "";
													$icoFidel = "";
													$saldoFidel = "";
												}
												$totalFidel1 = $totalFidel1 + $randFidel;
												$totalFidel2 = $totalFidel2 + 60;
												$totalFidel3 = $totalFidel3 + $saldoFidel;

											?>

												<tr>
													<td class="f21 text-left" style="padding: 15px 10px;">Usuário #<?php echo $i; ?></td>

													<td style="background-color: #F8F9F9;" class="f18 <?php echo $txtAbast; ?>"><b><?php echo $randAbast; ?></span></b></td>
													<td style="background-color: #F8F9F9;" class="f18"><b>90</b></td>
													<td style="background-color: #F8F9F9;" class="f18 <?php echo $txtAbast; ?>"><?php echo $icoAbast; ?> <?php echo $saldoAbast; ?></span></td>

													<td class="f18 <?php echo $txtAditiv; ?>"><b><?php echo $randAditiv; ?></span></b></td>
													<td class="f18"><b>40</b></td>
													<td class="f18 <?php echo $txtAditiv; ?>"><?php echo $icoAditiv; ?> <?php echo $saldoAditiv; ?></span></td>

													<td style="background-color: #F8F9F9;" class="f18 <?php echo $txt20Lit; ?>"><b><?php echo $rand20Lit; ?></span></b></td>
													<td style="background-color: #F8F9F9;" class="f18"><b>15</b></td>
													<td style="background-color: #F8F9F9;" class="f18 <?php echo $txt20Lit; ?>"><?php echo $ico20Lit; ?> <?php echo $saldo20Lit; ?></span></td>

													<td class="f18 <?php echo $txtFidel; ?>"><b><?php echo $randFidel; ?></span></b></td>
													<td class="f18"><b>60</b></td>
													<td class="f18 <?php echo $txtFidel; ?>"><?php echo $icoFidel; ?> <?php echo $saldoFidel; ?></span></td>

												</tr>

											<?php
											}
											?>


											<tr>
												<th class="plan-header plan-header-free">TOTAL</th>

												<!-- abastecimentos -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAbast1; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAbast2; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAbast3; ?></div>
												</th>

												<!-- aditivada -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAditiv1; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAditiv2; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalAditiv3; ?></div>
												</th>

												<!-- 20 litros -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $total20Lit1; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $total20Lit2; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $total20Lit3; ?></div>
												</th>

												<!-- fidelidade -->
												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalFidel1; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalFidel2; ?></div>
												</th>

												<th class="plan-header plan-header-free">
													<div class="pricing-plan-period"><?php echo $totalFidel3; ?></div>
												</th>


											</tr>

										</tbody>
									</table>
									<h5><b>* Valores e usuários ilustrativos</b></h5>
								</div>
							</div>




						</div>

					</div>

					<div class="push100"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {

		//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1302); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idU=' + $("#ret_COD_UNIVEND_" + index).val());
		$('#formLista').submit();

	}
</script>