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

		$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_veiculo = fnLimpaCampoZero($_REQUEST['COD_VEICULO']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$tip_contrat = fnLimpaCampoZero($_REQUEST['COD_TPCONTRAT']);
		$tip_pagamen = fnLimpaCampoZero($_REQUEST['TIP_PAGAMEN']);
		$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
		$val_contrat = fnValorSql($_REQUEST['VAL_CONTRAT']);
		$cod_formapa = fnLimpaCampo($_REQUEST['COD_FORMAPA']);
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CONTRATO_ELEITORAL(
											COD_EMPRESA,
											COD_UNIVEND,
											COD_CLIENTE,
											COD_VEICULO,
											TIP_CONTRAT,
											DAT_INI,
											DAT_FIM,
											VAL_CONTRAT,
											COD_FORMAPA,
											TIP_PAGAMEN,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											$cod_univend,
											$cod_cliente,
											$cod_veiculo,
											$tip_contrat,
											'$dat_ini',
											'$dat_fim',
											'$val_contrat',
											$cod_formapa,
											$tip_pagamen,
											$cod_usucada
										)";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

						$sqlCod = "SELECT MAX(COD_CONTRAT) COD_CONTRAT 
									FROM CONTRATO_ELEITORAL
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_USUCADA = $cod_usucada";

						$arrayCod = mysqli_query(conntemp($cod_empresa, ''), $sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayCod);

						$cod_contrat = fnLimpaCampoZero($qrCod[COD_CONTRAT]);

					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sql = "UPDATE CONTRATO_ELEITORAL SET
											TIP_CONTRAT = $tip_contrat,
											DAT_INI = '$dat_ini',
											DAT_FIM = '$dat_fim',
											VAL_CONTRAT = '$val_contrat',
											COD_VEICULO = $cod_veiculo,
											COD_FORMAPA = $cod_formapa,
											TIP_PAGAMEN = $tip_pagamen,
											COD_ALTERAC = $cod_usucada, 
											DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_CLIENTE = $cod_cliente
							AND COD_CONTRAT = $cod_contrat
										";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':


					$sql = "UPDATE CONTRATO_ELEITORAL SET
											COD_EXCLUSA = $cod_usucada, 
											DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_CLIENTE = $cod_cliente
							AND COD_CONTRAT = $cod_contrat";

					//echo $sql;

					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}

			if(($cod_erro == 0 || $cod_erro ==  "") && $cod_contrat != 0){

				if($opcao != "EXC"){

					$sqlCaixa = "DELETE FROM CAIXA
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTRAT = $cod_contrat
								AND COD_CLIENTE = $cod_cliente;

								INSERT INTO CAIXA(
												COD_EMPRESA,
												COD_CONTRAT,
												COD_CLIENTE,
												DAT_LANCAME,
												COD_TIPO,
												VAL_CREDITO,
												TIP_LANCAME,
												COD_USUCADA,
												NUM_DIA
											) VALUES(
												$cod_empresa,
												$cod_contrat,
												$cod_cliente,
												NOW(),
												99,
												'$val_contrat',
												'C',
												$cod_usucada,
												$tip_pagamen
											)";

					//echo($sql);

					mysqli_multi_query(connTemp($cod_empresa, ''), $sqlCaixa);

				}else{
					$sqlCaixa = "UPDATE CAIXA SET
										COD_EXCLUSA = $cod_usucada, 
										DAT_EXCLUSA = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTRAT = $cod_contrat
								AND COD_CLIENTE = $cod_cliente";

					mysqli_query(connTemp($cod_empresa, ''), $sqlCaixa);
				}

			}

		}

	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_cliente = fnDecode($_GET['idC']);
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

$sqlCli = "SELECT CL.COD_UNIVEND, CL.NUM_CARTAO, CL.NOM_CLIENTE FROM CLIENTES CL
			LEFT JOIN ESTADO ES ON ES.COD_ESTADO = CL.COD_ESTADO
			LEFT JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = CL.COD_MUNICIPIO
			WHERE CL.COD_EMPRESA = $cod_empresa 
			AND CL.COD_CLIENTE = $cod_cliente";

$arrayCli = mysqli_query(connTemp($cod_empresa,''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_univend = $qrCli[COD_UNIVEND];
$num_cartao = $qrCli[NUM_CARTAO];
$nom_cliente = $qrCli[NOM_CLIENTE];

?>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php 

						$abaCli = 1810;						
																						
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
											<label for="inputName" class="control-label">Código do Cliente</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-5">
										<label for="inputName" class="control-label">Nome do Colaborador</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
											<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Cartão</label>
											<input type="text" class="form-control input-sm text-right leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Dados do Contrato</legend>

								<div class="row">

									<div class="col-sm-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Contrato</label>

											<select data-placeholder="Selecione um contrato" name="COD_TPCONTRAT" id="COD_TPCONTRAT" class="chosen-select-deselect" tabindex="1" onchange="mostraVeiculos(this)" data-element="#dados">
												<option value="1">Genérico</option>
												<option value="2">Cabo Eleitoral</option>
												<option value="3">Coordenador Cabo Eleitoral</option>
												<option value="4">Cessão Serviços</option>
												<option value="5">Cessão Gratuita de Veículos</option>

											</select>

										</div>

									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial Vigência</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final Vigência</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>

												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor Total do Contrato</label>
											<input type="tel" class="form-control input-sm money" name="VAL_CONTRAT" id="VAL_CONTRAT" value="" required>
											<div class="help-block with-errors">Em Reais pelo período(R$)</div>
										</div>
									</div>

									<div class="col-sm-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Forma de Pagamento</label>

											<select data-placeholder="Selecione uma forma de pagamento" name="COD_FORMAPA" id="COD_FORMAPA" class="chosen-select-deselect" tabindex="1" required>
												<option value="1">Dinheiro</option>
												<option value="2">Pix</option>
												<option value="3">TED/DOC</option>
												<option value="4">Cheque</option>

											</select>

										</div>
									</div>

									<div class="col-sm-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Periodicidade de Pagamento</label>

											<select data-placeholder="Selecione um prazo" name="TIP_PAGAMEN" id="TIP_PAGAMEN" class="chosen-select-deselect" tabindex="1" required>
												<option value="0">Pagamento Único</option>
												<option value="1">Diário</option>
												<option value="7">Semanal</option>
												<option value="15">Quinzenal</option>
												<option value="30">Mensal</option>

											</select>

										</div>

									</div>

								</div>

								<div class="row">

									<div class="col-md-3" id="div_veiculos" style="display: none;">
										<div class="form-group">
											<label for="inputName" class="control-label required">Veículo</label>

											<select data-placeholder="Selecione um veículo" name="COD_VEICULO" id="COD_VEICULO" class="chosen-select-deselect" tabindex="1">
												<option value=""></option>
												<?php
                                                  
	                                                $sql = "SELECT * FROM VEICULO_CLIENTE 
															WHERE COD_CLIENTE = $cod_cliente
															AND COD_EMPRESA = $cod_empresa
															AND COD_EXCLUSA = 0
															ORDER BY DES_MARCA";

	                                                $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	                                                //fnEscreve($sql);
	                                                
	                                                while ($qrVeic = mysqli_fetch_assoc($arrayQuery))
	                                                  {
	                                                    
	                                                    echo"
	                                                          <option value='".$qrVeic['COD_VEICULO']."'>".$qrVeic['DES_MARCA']." ".$qrVeic['DES_MODELO']." ".($qrVeic['DES_PLACA'])."</option> 
	                                                        "; 
	                                                      }                                         
	                                            ?>
											</select>

										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
							<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?=$cod_univend?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th class="{ sorter: false }" width="40"></th>
										<th>Código</th>
										<th>Tipo</th>
										<th>Dt. Início Vigência</th>
										<th>Dt. Fim Vigência</th>
										<th class='text-right'>Valor do Contrato</th>
										<th>Forma de Pagamento</th>
										<th>Pagamento</th>
										<th class="text-center">Nro. Impressões</th>
										<th class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody>

									<?php

									$sql = "SELECT * FROM CONTRATO_ELEITORAL 
											WHERE COD_EMPRESA = $cod_empresa 
											AND COD_UNIVEND = $cod_univend
											AND COD_CLIENTE = $cod_cliente
											AND COD_EXCLUSA = 0";

									// echo($sql);
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

									$count = 0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										switch ($qrBuscaModulos[COD_FORMAPA]) {
											case '2':
												$formaPag = "Pix";
											break;

											case '3':
												$formaPag = "TED/DOC";
											break;

											case '4':
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
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></td>
													<td><?=$qrBuscaModulos['COD_CONTRAT']?></td>
													<td><?=$tipoContrato?></td>
													<td><?=fnDataShort($qrBuscaModulos['DAT_INI'])?></td>
													<td><?=fnDataShort($qrBuscaModulos['DAT_FIM'])?></td>
													<td class='text-right'><small>R$</small><?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?></td>
													<td><?=$formaPag?></td>
													<td><?=$tipoPag?></td>
													<td class="text-center"><?=fnValor($qrBuscaModulos['NUM_IMPRESSAO'],0)?></td>
													<td class="text-center">
										           		<small>
										           			<div class="btn-group dropdown dropleft">
																<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	ações &nbsp;
																	<span class="fas fa-caret-down"></span>
															    </button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1822)?>&id=<?php echo fnEncode($cod_empresa)?>&idCT=<?php echo fnEncode($qrBuscaModulos['COD_CONTRAT'])?>&pop=true" data-title="Impressão de contrato">Imprimir Contrato </a></li>
																	<!-- <li class="divider"></li> -->
																</ul>
															</div>
										           		</small>
									           	   </td>
												</tr>
												<input type='hidden' id='ret_COD_CONTRAT_<?=$count?>' value='<?=$qrBuscaModulos['COD_CONTRAT']?>'>
												<input type='hidden' id='ret_COD_VEICULO_<?=$count?>' value='<?=$qrBuscaModulos['COD_VEICULO']?>'>
												<input type='hidden' id='ret_COD_TPCONTRAT_<?=$count?>' value='<?=$qrBuscaModulos['TIP_CONTRAT']?>'>
												<input type='hidden' id='ret_DAT_INI_<?=$count?>' value='<?=fnDataShort($qrBuscaModulos['DAT_INI'])?>'>
												<input type='hidden' id='ret_DAT_FIM_<?=$count?>' value='<?=fnDataShort($qrBuscaModulos['DAT_FIM'])?>'>
												<input type='hidden' id='ret_VAL_CONTRAT_<?=$count?>' value='<?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?>'>
												<input type='hidden' id='ret_COD_FORMAPA_<?=$count?>' value='<?=$qrBuscaModulos['COD_FORMAPA']?>'>
												<input type='hidden' id='ret_TIP_PAGAMEN_<?=$count?>' value='<?=$qrBuscaModulos['TIP_PAGAMEN']?>'>

									<?php 
									}

									?>

								</tbody>
							</table>

						</div>

					</div>

				</div><!-- fim Portlet -->
			

		

<div class="push20"></div>

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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">

	$(function(){

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#dados1").css("display", "inline");
		$("#dados2").css("display", "none");

		$("#COD_TPCONTRAT").change(function() {

			

			if ($("#COD_TPCONTRAT").val() == "1") {
				$("#dados1").css("display", "inline");
				$("#dados2").css("display", "none");
			} else if ($("#COD_TPCONTRAT").val() == "2") {
				$("#dados2").css("display", "inline");
				$("#dados1").css("display", "none");
			} else {
				$("#dados1").css("display", "none");
				$("#dados2").css("display", "none");
			}
		})

		$("#print").click(function() {
			if ($("#COD_TPCONTRAT").val() == "1") {
				let impressao = document.getElementById("impressao1").innerHTML;
			} else {
				let impressao = document.getElementById("impressao2").innerHTML;
			}
			let a = window.open('', '', 'height=3508, widht=2480');
			a.document.write('<html>');
			a.document.write('<body>');
			a.document.write(impressao);
			a.document.write('</body></html>');
			a.document.close();
			a.print();
		});

	});

	function mostraVeiculos(el) {
		if($(el).val() == 5){
			$("#div_veiculos").attr('required',true).fadeIn('fast');
		}else{
			$("#div_veiculos").removeAttr('required').fadeOut('fast');
		}
		$('#formulario').validator('destroy').validator();
	}

	function retornaForm(index) {
		$("#formulario #COD_CONTRAT").val($("#ret_COD_CONTRAT_" + index).val());
		$("#formulario #COD_TPCONTRAT").val($("#ret_COD_TPCONTRAT_" + index).val()).trigger("chosen:updated");
		$("#formulario #DAT_INI").val($("#ret_DAT_INI_" + index).val());
		$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val());
		$("#formulario #VAL_CONTRAT").val($("#ret_VAL_CONTRAT_" + index).val());
		$("#formulario #COD_FORMAPA").val($("#ret_COD_FORMAPA_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_PAGAMEN").val($("#ret_TIP_PAGAMEN_" + index).val()).trigger("chosen:updated");

		if($("#ret_COD_TPCONTRAT_" + index).val() == 5){
			$("#formulario #COD_VEICULO").val($("#ret_COD_VEICULO_" + index).val()).trigger("chosen:updated");
			$("#div_veiculos").attr('required',true).fadeIn('fast');
		}else{
			$("#formulario #COD_VEICULO").val('').trigger("chosen:updated");
			$("#div_veiculos").removeAttr('required').fadeOut('fast');
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

</script>