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
		$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
		$cod_tipmoti = fnLimpacampoZero($_REQUEST['COD_TIPMOTI']);

		$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);
		$num_cartao_novo = fnLimpacampo($_REQUEST['NUM_CARTAO_NOVO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//busca dados da empresa
			$sql = "select LOG_AUTOCAD FROM EMPRESAS WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			$qrBuscaLOG_AUTOCAD = mysqli_fetch_assoc($arrayQuery);
			$log_autocad = $qrBuscaLOG_AUTOCAD['LOG_AUTOCAD'];

			$sql1 = "CALL SP_ALTERA_NUMEROCARTAO(
						'" . $cod_cliente . "',
						'" . $cod_empresa . "',
						'" . $num_cartao . "',
						'" . $num_cartao_novo . "',
						'" . $cod_usucada . "',
						'" . $cod_tipmoti . "',
						'" . $log_autocad . "',
						'" . $opcao . "'  
					) ";

			//echo $sql1;	

			//$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql1);
			//$qrBuscaRetorno = mysqli_fetch_assoc($arrayQuery);
			//$mensagem_retorno = $qrBuscaRetorno['mensagem_retorno'];

			//mensagem de retorno
			$msgRetorno = $mensagem_retorno;
			if ($mensagem_retorno != "Alterado com sucesso!") {
				$msgTipo = 'alert-danger';
			} else {
				$msgTipo = 'alert-success';
			}
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);

	if (isset($_GET['tp'])) {
		$cod_cliente = fnLimpaCampoZero($_GET['idC']);
	} else {
		$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));
	}

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






$sqlCli = "SELECT CL.COD_UNIVEND FROM CLIENTES CL
			LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
			LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
			WHERE CL.COD_EMPRESA = $cod_empresa 
			AND CL.COD_CLIENTE = $cod_cliente";

$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_univend = $qrCli[COD_UNIVEND];


$sqlVal = "SELECT VAL_CREDITO, TIP_LANCAME FROM CAIXA 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CLIENTE = $cod_cliente
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


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, 
					NUM_CARTAO, 
					NUM_CGCECPF, 
					COD_CLIENTE, 
					VAL_SALBASE, 
					PCT_JURIDICO, 
					LOG_JURIDICO 
			FROM CLIENTES 
			where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$log_juridico = $qrBuscaCliente['LOG_JURIDICO'];
	$pct_juridico = $qrBuscaCliente['PCT_JURIDICO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$val_salbase = $qrBuscaCliente['VAL_SALBASE'];

	$sqlSal = "SELECT VAL_LANCAME FROM LANCAMENTO_AUTOMATICO
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CLIENTE = $cod_cliente
					AND COD_TIPO = 1";

	//fnEscreve($sql);

	$arraySal = mysqli_query(connTemp($cod_empresa, ''), $sqlSal);

	$qrSal = mysqli_fetch_assoc($arraySal);

	$val_salbase = fnValorSql(fnValor($qrSal[VAL_LANCAME], 2));
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
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

				<?php
				
				$abaCli = 1703;

				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 20: //campanhas
						$abaEmpresa = 1823;
						$abaCli = 1823;
						break;
					default;
						$abaEmpresa = 1704;
						$abaCli = 1703;
						break;
				}
				
				//menu abas
				include "abasClienteRH.php";

				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código do Funcionário</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
									</div>
								</div>

								<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
														</div>														
													</div> -->

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Funcionário</label>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leitura" style="border-radius:0 3px 3px 0;" value="<?php echo $nom_cliente; ?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
									</div>
								</div>

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

						</fieldset>

						<div class="push10"></div>

						<div class="row">

							<!-- <div class="col-md-9">
								<a href="javascript:void(0)" id="btnNovo" class="btn btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1827) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&idm=<?= fnEncode($cod_mes) ?>&pop=true" data-title="Cadastro de Lançamento">Cadastrar Novo Lançamento&nbsp;<span class="fas fa-plus"></span></a>
							</div> -->

						</div>

						<div class="push20"></div>


						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">
								
								
									<?php

									$sql = "SELECT * FROM CONTRATO_ELEITORAL 
											WHERE COD_EMPRESA = $cod_empresa 
											AND COD_UNIVEND = $cod_univend
											AND COD_CLIENTE = $cod_cliente
											AND COD_EXCLUSA = 0";
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
													<td colspan="4"><small><a href="javascript:void(0)" id="btnNovo" class="btn btn-info btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idCT=<?=fnEncode($qrBuscaModulos[COD_CONTRAT])?>&idT=<?=fnEncode($qrBuscaModulos[TIP_PAGAMEN])?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'><span class="fal fa-plus"></span>&nbsp; Cadastrar novo lançamento</a></small></td>
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
															while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery))
															  {														  

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

																// 		//fnEscreve($sql);

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
																							<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'>Editar</a></li>
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

																	<tr>
																		<td colspan="4">
																		
																			<!-- <small><a target="_blank" class="btn btn-info btn-xs" href="action.php?mod=<?php echo fnEncode(1711)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idm=<?=fnEncode($cod_mes)?>&pop=true"><span class="fal fa-file"></span> Impressão de holerite</a></small> -->
																			
																		
																		</td>
																		<?php

																			$sqlSal = "SELECT 	1
																					FROM CAIXA
																					where CAIXA.COD_CONTRAT=$cod_cliente 
																					AND CAIXA.COD_EMPRESA=$cod_empresa 
																					AND CAIXA.COD_TIPO=1
																					AND CAIXA.COD_MES = $cod_mes";

																			$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

																			$temSal = mysqli_num_rows($arraySal);

																			if($temSal == 0){

																		?>
																				<!-- <td colspan="2"><small><a class="btn btn-primary btn-xs" onclick='lancarMes("<?=$cod_cliente?>")'><span class="fal fa-cog"></span> Lançar Mês</a></small></td> -->
																		<?php }else{ ?>
																				<!-- <td colspan="2"></td> -->
																		<?php } ?>
																																	
															</table>

									<?php 
									}

									?>
								
								

									<!-- <table class="table table-hover">
										<thead>
											<tr>
												<th width="150"><small>Data</small></th>
												<th><small>Operação</small></th>
												<th width="80"><small>Tipo</small></th>
												<th class="text-center" width="80"><small>Dias</small></th>
												<th class="text-right"><small>Valor</small></th>
												<th class="{sorter:false} text-center" width="10%"></th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php
											//$sql = "SELECT * FROM EMPENHO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim";
											$sql = "SELECT 	CAIXA.VAL_CREDITO,
															CAIXA.COD_CAIXA,
															CAIXA.NUM_DIA,
															TIP_CREDITO.COD_TIPO,
															TIP_CREDITO.DES_TIPO,
															TIP_CREDITO.TIP_OPERACAO,
															DATE_FORMAT(CAIXA.DAT_LANCAME, '%d/%m/%Y') DAT_LANCAME
														FROM CAIXA
														left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
														where CAIXA.COD_CONTRAT=$cod_cliente 
														AND CAIXA.COD_EMPRESA=$cod_empresa
														AND CAIXA.DAT_EXCLUSA IS NULL
														AND CAIXA.COD_EXCLUSA = 0
														AND CAIXA.TIP_LANCAME = 'F'
														ORDER BY CAIXA.DAT_LANCAME DESC";

											fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$val_total = 0;
											$dat_ref = "";
											while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery)) {

												if ($dat_ref !=  $qrListaCaixa['DAT_LANCAME'] || $count == 0) {

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

												$sqlSal = "SELECT VAL_LANCAME FROM LANCAMENTO_AUTOMATICO
																		WHERE COD_EMPRESA = $cod_empresa 
																		AND COD_CLIENTE = $cod_cliente
																		AND COD_TIPO = 1";

												//fnEscreve($sql);

												$arraySal = mysqli_query(connTemp($cod_empresa, ''), $sqlSal);

												$qrSal = mysqli_fetch_assoc($arraySal);

												$salario_base = fnValorSql(fnValor($qrSal[VAL_LANCAME], 2));


											?>
												<tr>
													<td class="f14"><b><?= $dat_lancame; ?></b></td>
													<td class="f12"><?= $qrListaCaixa['DES_TIPO']; ?></td>
													<td class='text-center <?= $corTexto; ?> f12'><?= $qrListaCaixa['TIP_OPERACAO']; ?></td>
													<td class="text-center"><?= $qrListaCaixa['NUM_DIA']; ?></td>
													<td class='text-right <?= $corTexto; ?> f14'><?= fnValor($qrListaCaixa['VAL_CREDITO'], 2); ?></td>
													<td class="text-center">
														<?php if ($qrListaCaixa[COD_TIPO] != 1 && $qrListaCaixa[COD_TIPO] != 2) { ?>
															<small>
																<div class="btn-group dropdown dropleft">
																	<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																		ações &nbsp;
																		<span class="fas fa-caret-down"></span>
																	</button>
																	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																		<li><a class="addBox" data-url="action.php?mod=<?= fnEncode(1705) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&idx=<?= fnEncode($qrListaCaixa[COD_CAIXA]) ?>&idm=<?= fnEncode($cod_mes) ?>&idd=<?= fnEncode($qrListaCaixa['NUM_DIA'])?>&pop=true" data-title="Cadastro de Lançamento">Editar</a></li>
																	</ul>
																</div>
															</small>
															<?php } else if ($qrListaCaixa[COD_TIPO] == 1) {

															if ($salario_base != $qrListaCaixa['VAL_CREDITO']) {
															?>
																<a href="javascript:void(0)" class="btn btn-warning btn-xs transparency" onclick='refreshSalario("<?= fnEncode($cod_mes) ?>")'>Atualizar Salário</a>
														<?php
															}
														}
														?>
													</td>
												</tr>

											<?php
												$count++;
											}
											?>

										</tbody>

										<tfoot>
											<tr>
												<td><b>Vl. Líquido</b></td>
												<td></td>
												<td></td>
												<td></td>
												<td class="text-right f16"><b id="ret_VAL_TOTAL"><?= fnValor($val_total, 2); ?></b></td>
											</tr>
										</tfoot>

									</table> -->
										
									<!-- <input type="hidden" id="ret_VAL_SALDO" value="<?= fnValor($val_saldo, 2); ?>"> -->


								</form>

							</div>

						</div>
						
						<?php
						
						?>	

						<div class="col-md-12">
							<!-- <a target="_blank" class="btn btn-info" href="action.php?mod=<?php echo fnEncode(1711) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&idm=<?= fnEncode($cod_mes) ?>&pop=true"><span class="fal fa-file"></span> Impressão de holerite</a> -->
						</div>


						<div class="push50"></div>
						<hr>

						<div class="row">

							<div class="col-md-12 text-center">
								<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-retweet" aria-hidden="true"></i>&nbsp; Carregar mais registros</button> -->
							</div>

						</div>

						<div class="push50"></div>

						<div class="form-group text-center col-lg-12">



						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<!-- <input type="hidden" name="COD_MES" id="COD_MES" value="<?= $cod_mes ?>"> -->
						<input type="hidden" name="REFRESH_LANCAMENTO" id="REFRESH_LANCAMENTO" value="N">
						<input type="hidden" name="REFRESH_FOLLOW" id="REFRESH_FOLLOW" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
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

			location.reload();

			if ($("#REFRESH_LANCAMENTO").val() == 'S') {

				carregaMes($("#COD_MES").val());

			}

		});

	});

	function carregaMes(cod_mes) {

		$.ajax({
			type: "POST",
			url: "ajxLancamentosRH.php",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_CLIENTE: "<?= fnEncode($cod_cliente) ?>",
				OPCAO: "paginar",
				COD_MES: cod_mes
			},
			beforeSend: function() {
				$("#relatorioConteudo").html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$("#REFRESH_LANCAMENTO").val('N');
				$("#btnNovo").attr("data-url", "action.php?mod=<?= fnEncode(1705) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&idm=" + cod_mes + "&pop=true")
			},
			error: function() {
				$("#relatorioConteudo").html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
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