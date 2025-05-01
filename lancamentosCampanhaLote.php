<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			

			//mensagem de retorno
			// $msgRetorno = "Busca realizada com sucesso.";
			// $msgTipo = 'alert-success';

		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);

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
	$nom_empresa = "";
}






$sqlCont = "SELECT COD_CONTRAT FROM CONTRATO_ELEITORAL
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_UNIVEND = $cod_univend
			AND TIP_CONTRAT NOT IN(4,5)";

$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sqlCont);

$cod_contrats = "";

while($qrCont = mysqli_fetch_assoc($arrayCont)){
	$cod_contrats .= $qrCont[COD_CONTRAT].",";
}

$cod_contrats = rtrim(ltrim($cod_contrats,","),",");

if($cod_contrats != ""){

	$sqlVal = "SELECT VAL_CREDITO, TIP_LANCAME FROM CAIXA 
				WHERE COD_EMPRESA = $cod_empresa
				AND COD_CONTRAT IN($cod_contrats)
				AND COD_EXCLUSA = 0";

	// fnEscreve2($sqlVal);
	$arrayVal = mysqli_query(connTemp($cod_empresa,''), $sqlVal);

	$tot_contrat = 0;
	$tot_pago = 0;
	$tot_receber = 0;

	while($qrVal = mysqli_fetch_assoc($arrayVal)){

		if($qrVal[TIP_LANCAME] == 'C'){
			$tot_contrat += $qrVal[VAL_CREDITO];
		}else{
			$tot_pago += $qrVal[VAL_CREDITO];
		}

	}

	$tot_receber = $tot_contrat - $tot_pago;

}else{

	$tot_contrat = 0;
	$tot_pago = 0;
	$tot_receber = 0;

}




//fnMostraForm();
//fnEscreve($mensagem_retorno);

?>

<style>
	.chosen-big+div>.chosen-single {
		height: 45px !important;
		line-height: 20px !important;
		padding: 10px 15px !important;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 16: //gerenciador social
						$formBack = "1424";
						break;
					default;
						//$formBack = "1015";
						break;
				}
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

				

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-xs-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
											<select data-placeholder="Selecione a campanha" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>					
												<?php 																	
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = $cod_empresa $andUnivendCombo AND LOG_ESTATUS = 'S' order by NOM_UNIVEND ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
												
													while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery))
													  {														
														echo"
															  <option value='".$qrListaUnidade['COD_UNIVEND']."'>".$qrListaUnidade['NOM_FANTASI']."</option> 
															"; 
														  }											
												?>	
											</select>
                                            <script>$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated"); </script>                                                       
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div id="totais">
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Total de Contratos (R$)</label>
											<input type="text" class="form-control input-sm text-center leitura" name="VAL_CONTRAT" id="VAL_CONTRAT" value="<?php echo fnValor($tot_contrat, 2); ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Total Recebido (R$)</label>
											<input type="text" class="form-control input-sm text-center leitura" name="VAL_SALBASE" id="VAL_SALBASE" value="<?php echo fnValor($tot_pago, 2); ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Total a Receber (R$)</label>
											<input type="text" class="form-control input-sm text-center leitura" name="VAL_SALBASE" id="VAL_SALBASE" value="<?php echo fnValor($tot_receber, 2); ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Buscar</button>
							<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button> -->
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>


					<div class="row">

							
							
						<?php

							$sqlCli = "SELECT CE.COD_CLIENTE, CL.NOM_CLIENTE FROM CONTRATO_ELEITORAL CE
										INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = CE.COD_CLIENTE
										WHERE CE.COD_EMPRESA = $cod_empresa 
										AND CE.COD_UNIVEND = $cod_univend
										AND CE.COD_EXCLUSA = 0
										AND CE.TIP_CONTRAT NOT IN(4,5)";

							$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);

							$countCli = 0;

							while ($qrCli = mysqli_fetch_assoc($arrayCli)) {

						?>


							<div class="col-md-12">

								<a data-toggle="collapse" href="#multiCollapse_<?=$countCli?>" role="button" aria-expanded="false" aria-controls="multiCollapse_<?=$countCli?>"><?=$qrCli[COD_CLIENTE]?> - <?=$qrCli[NOM_CLIENTE]?></a>
								
								<div class="row">

									<div class="col-md-12">

										<div class="collapse multi-collapse div_<?=fnEncode($qrCli[COD_CLIENTE])?>" id="multiCollapse_<?=$countCli?>">

										<?php 

											$cod_cliente = $qrCli[COD_CLIENTE];

											$sql = "SELECT * FROM CONTRATO_ELEITORAL 
													WHERE COD_EMPRESA = $cod_empresa 
													AND COD_UNIVEND = $cod_univend
													AND COD_CLIENTE = $cod_cliente
													AND COD_EXCLUSA = 0
													AND TIP_CONTRAT NOT IN(4,5)";
											$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayCont)) {
												$count++;

												$tipoContrato = "Cabo Eleitoral";
												$formaPag = "Dinheiro";
												

												switch ($qrBuscaModulos[COD_FORMAPA]) {
													case '2':
														$formaPag = "Pix";
													break;

													case '3':
														$formaPag = "TED/DOC";
													break;

													case '4':
														$formaPag = "Transferência";
													break;

													case '5':
														$formaPag = "Cheque";
													break;
													
													default:
														$formaPag = "Dinheiro";
													break;
												}

												switch ($qrBuscaModulos[TIP_CONTRAT]) {
													case '2':
														$tipoContrato = "Cabo Eleitoral";
													break;

													case '3':
														$tipoContrato = "Coordenador Cabo Eleitoral";
													break;

													case '4':
														$tipoContrato = "Cessão Serviços";
													break;
													
													case '5':
														$tipoContrato = "Cessão Gratuita de Veículos";
													break;

													default:
														$tipoContrato = "Genérico";
													break;
												}

												switch ($qrBuscaModulos[TIP_PAGAMEN]) {
													case '1':
														$tipoPag = "Diário";
													break;

													case '7':
														$tipoPag = "Semanal";
													break;

													case '15':
														$tipoPag = "Quinzenal";
													break;

													case '30':
														$tipoPag = "Mensal";
													break;
													
													default:
														$tipoPag = "Pagamento Único";
													break;
												}

				?>
													<h3><?=$qrBuscaModulos['COD_CONTRAT']?> / <?=$tipoContrato?> / <?=fnDataShort($qrBuscaModulos['DAT_INI'])?> / <small>R$</small><?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?></h3>

													<table class="table" style="width: auto;">
														<tr>
															<td colspan="4"><small><a href="javascript:void(0)" id="btnNovo" class="btn btn-info btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idCT=<?=fnEncode($qrBuscaModulos[COD_CONTRAT])?>&idT=<?=fnEncode($qrBuscaModulos[TIP_PAGAMEN])?>&m=true&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'><span class="fal fa-plus"></span>&nbsp; Cadastrar novo lançamento</a></small></td>
															<td colspan="2"></td>
														</tr>
													
														<tr>
															<th><small>Dt. Lança.</small></th>
															<th><small>Op.</small></th>
															<th class="text-center"><small>Forma de pagamento</small></th>
															<th class="text-right"><small>Vl.</small></th>
														</tr>
														
				<?php 																	
													$sql = "SELECT 	CAIXA.VAL_CREDITO,
																	CAIXA.COD_CAIXA,
																	CAIXA.DAT_LANCAME,
																	CAIXA.NUM_DIA,
																	CAIXA.COD_PAGAMENTO	
															FROM CAIXA
															where CAIXA.COD_CONTRAT=$qrBuscaModulos[COD_CONTRAT] 
															AND CAIXA.COD_CLIENTE=$cod_cliente
															AND CAIXA.COD_EMPRESA=$cod_empresa
															AND CAIXA.DAT_EXCLUSA IS NULL
															AND CAIXA.TIP_LANCAME = 'D'
															AND CAIXA.COD_EXCLUSA = 0
															ORDER BY CAIXA.DAT_LANCAME DESC";
													
													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													$val_total = 0;
													$dat_ref = "";
													while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery)){														  

														if ($dat_ref !=  $qrListaCaixa['DAT_LANCAME'] || $count == 0){

															$dat_ref = $qrListaCaixa['DAT_LANCAME'];
															$dat_lancame = $dat_ref;	

														} else {

															$dat_lancame = "";	

														}
														
														$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
														
														if ($tip_operacao == "D") {
															$corTexto = "text-danger";
															$val_total -= $qrListaCaixa['VAL_CREDITO'];
														} else { 
															$corTexto = ""; 
															$val_total += $qrListaCaixa['VAL_CREDITO'];
														} 

														switch ($qrListaCaixa['COD_PAGAMENTO']){
															
															case '2':
																$des_pagamento = "Pix";
															break;

															case '3':
																$des_pagamento = "TED/DOC";
															break;

															case '4':
																$des_pagamento = "Transferência";
															break;

															case '5':
																$des_pagamento = "Cheque";
															break;
															
															default:
																$des_pagamento = "Dinheiro";
															break;
														}

														// $sqlSal = "SELECT VAL_LANCAME AS VAL_SALBASE FROM LANCAMENTO_AUTOMATICO LA 
														// 			WHERE LA.COD_EMPRESA = $cod_empresa 
														// 			AND LA.COD_CLIENTE = $cod_cliente
														// 			AND LA.COD_TIPO = 1";															

														//fnEscreve($sql);

														// $arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

														// $qrSal = mysqli_fetch_assoc($arraySal);

														// $salario_base = fnValorSql(fnValor($qrSal[VAL_SALBASE],2));

				?>																			  
														<tr codItemVenda="<?php echo $qrListaCaixa['COD_ITEMVEN'];?>">
															<td><small><?=fnDataShort($qrListaCaixa['DAT_LANCAME'])?></small></td>
															<td><small><div>Pagamento</div></small></td>
															<td class="text-center"><small><?=$des_pagamento?></small></td>
															<td class="text-right <?=$corTexto?>"><small><div><?=fnValor($qrListaCaixa['VAL_CREDITO'],2)?></div></small></td>
															<td class="text-center">
															  	<?php 
															  		if($qrListaCaixa[COD_TIPO] != 1 && $qrListaCaixa[COD_TIPO] != 2){ 
															  	?>
													           		<small>
													           			<div class="btn-group dropdown dropleft">
																			<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																				ações &nbsp;
																				<span class="fas fa-caret-down"></span>
																		    </button>
																			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																				<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&m=true&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'>Editar</a></li>
																				<li><a target="_blank" href="action.php?mod=<?php echo fnEncode(1828)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&pop=true">Imprimir Recibo</a></li>
																				<!-- <li class="divider"></li> -->
																				<!-- <li><a href="javascript:void(0)" onclick='excTemplate("")'>Excluir</a></li> -->
																			</ul>
																		</div>
													           		</small>
													           	<?php 
													           		}else if($qrListaCaixa[COD_TIPO] == 1){ 

													           			// if($salario_base != fnValorSql(fnValor($qrListaCaixa[VAL_CREDITO],2))){
													           	?>
													           				<!-- <a href="javascript:void(0)" class="btn btn-warning btn-xs transparency" onclick='refreshSalario("<?=$cod_cliente?>")'>Atualizar Salário</a> -->
													           	<?php
													           			// }
													           	 	} 
													           	?>
											           	   </td>
														</tr>	
																			
				<?php 																				
						  							}											
				?>																	
														<tr>
															<td><small><b>Vl. Líquido</b></small></td>
															<td class="text-right" colspan="3"><small><b><div class="subtotalProd"><?=fnValor($val_total,2);?></div></b></small></td>
														</tr>

																																	
													</table>

												


				<?php

					}

				?>


												</div>

												<div class="push20"></div>

											</div>

										</div>

									</div>



						<?php 

							$countCli++;

						}

						?>
							
					</div>
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


<script type="text/javascript">
	$(document).ready(function() {

		var mes = "";

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			// alert('fecha');

			if ($("#REFRESH_LANCAMENTO").val() == 'S') {

				location.reload();

			}

		});

	});

	function refreshCliente(cod_cliente){
		$.ajax({
			type: "POST",
			url: "ajxLancamentosCampLote.do?id=<?=fnEncode($cod_empresa)?>",
			data: { COD_CLIENTE: cod_cliente},
			success:function(data){
				// $(".div_"+cod_cliente).html(data);
				$("#totais").html($('#div_total',data));
				$(".div_"+cod_cliente).html($('#div_cliente',data));
				// console.log(data);
			},
			error:function(data){
	        	$("#totais, .div_"+cod_cliente).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
	    	}
		});
	}


	function refreshSalario(cod_mes) {
		$.ajax({
			type: "POST",
			url: "ajxLancamentosRH.do",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_MES: cod_mes,
				COD_CLIENTE: "<?= fnEncode($cod_cliente) ?>",
				OPCAO: "salario"
			},
			success: function(data) {
				carregaMes(cod_mes);
			}
		});
	}
</script>