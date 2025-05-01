<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_tipdevo = fnLimpaCampo($_REQUEST['COD_TIPDEVO']);
		$id_reserva = fnLimpaCampo($_REQUEST['ID_RESERVA']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_pedido = fnLimpaCampo($_REQUEST['COD_PEDIDO']);
		$val_devolucao = fnLimpaCampo(fnValorSql($_REQUEST['VAL_DEVOLUCAO']));
		$tot_reembolso = fnLimpaCampo(fnValorSql($_REQUEST['TOT_REEMBOLSO']));
		$tip_tarifa = fnLimpaCampoZero($_REQUEST['TIP_TARIFA']);
		$val_tarifa = fnLimpaCampoZero(fnValorSql($_REQUEST['VAL_TARIFA']));
		$tip_pagamen = fnLimpaCampo($_REQUEST['TIP_PAGAMEN']);
		$data_estorno = fnLimpaCampo($_REQUEST['DATA_ESTORNO']);
		$dat_limite = date("Y-m-d", strtotime("$data_estorno +10 days"));

		$cod_usucanc = $_SESSION[SYS_COD_USUARIO];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				
				case 'ALT':

				$sql = "UPDATE ADORAI_CANCELAMENTOS SET
				COD_USUCANC = $cod_usucanc,
				DAT_CANCELA = NOW(),
				COD_STATUS = 4,
				COD_TIPDEVO = $cod_tipdevo
				WHERE ID_RESERVA = $id_reserva";

				//fnEscreve($sql);
				mysqli_query(connTemp($cod_empresa, ''), $sql);

				if($cod_tipdevo != 3){
					$sql = "INSERT INTO ADORAI_DEVOLUCOES(
						COD_EMPRESA,
						COD_PEDIDO,
						COD_CLIENTE,
						TIP_DEVOLUCAO, 
						VAL_DEVOLUCAO,
						DAT_LIMITE,
						COD_STATUS,
						TIP_PAGAMEN,
						COD_USUCADA,
						TIP_TARIFA,
						VAL_TARIFA,
						DAT_CADASTR
						)VALUES(
						$cod_empresa,
						$cod_pedido,
						0,
						'$cod_tipdevo',
						$tot_reembolso,
						'$dat_limite',
						0,
						'$tip_pagamen',
						$cod_usucanc,
						$tip_tarifa,
						$val_tarifa,
						NOW()
					)";
						mysqli_query(connTemp($cod_empresa, ''), $sql);
						//fnEscreve($sql);
						$sqlCaixa = "INSERT INTO CAIXA(
							COD_EMPRESA,
							COD_CONTRAT,
							COD_TIPO,
							VAL_ESTORNO,
							DAT_LANCAME,
							DAT_CADASTR,
							COD_USUCADA
							)VALUES(
							$cod_empresa,
							$cod_pedido,
							2,
							$tot_reembolso,
							NOW(),
							NOW(),
							$cod_usucanc
						)";

							mysqli_query(connTemp($cod_empresa, ''), $sqlCaixa);
						//fnEscreve($sqlCaixa);

						}else{
							$des_voucher = generateUniqueVoucherCSV(8);
							$sqlVoucher = "INSERT INTO ADORAI_VOUCHER(
								DES_VOUCHER,
								LOG_STATUS,
								VL_VOUCHER,
								COD_PEDIDO,
								DAT_CADASTR,
								DAT_EXPIRA,
								COD_EMPRESA
								)VALUES(
								'$des_voucher',
								'D',
								'$tot_reembolso',
								$cod_pedido,
								NOW(),
								'$dat_expira',
								$cod_empresa
							)";

								mysqli_query(connTemp($cod_empresa,''),$sqlVoucher);

								$sql = "UPDATE ADORAI_CANCELAMENTOS SET
								COD_TIPDEVO = 3,
								COD_STATUS = 4
								WHERE COD_PEDIDO = $cod_pedido";

								mysqli_query(connTemp($cod_empresa,''),$sql);

							}

							$sqlUpdate = "UPDATE ADORAI_PARCELAS SET
							TIP_PARCELA = 'C',
							DAT_ALTERAC = NOW()
							WHERE COD_PEDIDO = $cod_pedido AND TIP_PARCELA ='A'";

							mysqli_query(connTemp($cod_empresa,''),$sqlUpdate);

							$msgRetorno = "Registro cancelado com <strong>sucesso!</strong>";
							break;
						}
						$msgTipo = 'alert-success';
					}
				}
			}


//busca dados da url	
			if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
				$cod_empresa = fnDecode($_GET['id']);
				$cod_cliente = fnDecode($_GET['idc']);
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

			if(is_numeric(fnLimpacampo(fnDecode($_GET['idr'])))) {
				$id_reserva = fnDecode($_GET['idr']);

				$sql = "SELECT b.COD_PEDIDO,
				b.CPF,
				b.NOME,
				b.EMAIL,
				b.TELEFONE,
				b.DAT_CADASTR AS DATA_PEDIDO,
				a.DAT_CADASTR AS DATA_ESTORNO,
				c.ABV_STATUSPAG,
				a.ID_RESERVA,
				a.DES_OBSERVA,
				(
					SELECT SUM(CX.val_credito)
					FROM caixa AS cx 
					INNER JOIN adorai_pedido AS p ON cx.cod_contrat = p.COD_PEDIDO
					INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = cx.COD_TIPO
					WHERE p.COD_EMPRESA = 274 
					AND p.COD_PEDIDO = a.COD_PEDIDO
					AND cx.cod_contrat = a.COD_PEDIDO
					AND TC.TIP_OPERACAO = 'C'
					) AS valor_pago,
				e.ABV_FORMAPAG
				FROM adorai_cancelamentos a
				LEFT JOIN adorai_pedido b ON a.ID_RESERVA=b.ID_RESERVA
				LEFT JOIN adorai_statuspag c ON c.COD_STATUSPAG=a.COD_STATUS
				LEFT JOIN adorai_formapag e ON e.COD_FORMAPAG=b.COD_FORMAPAG
				WHERE a.ID_RESERVA = $id_reserva
				GROUP BY A.COD_PEDIDO
				ORDER BY A.COD_PEDIDO";
				
				$query = mysqli_query(connTemp($cod_empresa,''),$sql);
				$qrResult = mysqli_fetch_assoc($query);

				if($qrResult){
					$data_pedido = $qrResult['DATA_PEDIDO'];
					$data_estorno = $qrResult['DATA_ESTORNO'];
					$id_reserva = $qrResult['ID_RESERVA'];
					$cod_pedido = $qrResult['COD_PEDIDO'];
					$nome = $qrResult['NOME'];
					$cpf = $qrResult['CPF'];
					$email = $qrResult['EMAIL'];
					$des_observa = $qrResult['DES_OBSERVA'];
					$abv_formapag = $qrResult['ABV_FORMAPAG'];
					$val_devolucao = fnValor($qrResult['valor_pago'],2);
				}else{
					$id_reserva = '';
					$nome = '';
					$cpf = '';
					$email = '';
					$valor = '';
					$reserva = '';
				}
			}
			$diferenca = fnDateDif($data_pedido,$data_estorno);
			if($diferenca > 30){
				$disableFormEstor = "disabled";
			}

			?>

			<div class="push30"></div>

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
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
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

								<div class="push30"></div>

								<div class="login-form">

									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

										<fieldset>
											<legend>Dados do Cancelamento</legend>

											<div class="row">

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label">Cód. Contrato</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PEDIDO" id="COD_PEDIDO" value="<?=$cod_pedido?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Hóspede</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOME" id="NOME" value="<?=$nome ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Cpf</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="CPF" id="CPF" value="<?=$cpf ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Email</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="EMAIL" id="EMAIL" value="<?=$email ?>">
													</div>
												</div>

											</div>

											<div class="row">

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label">Valor Pago</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="VAL_DEVOLUCAO" id="VAL_DEVOLUCAO" value="<?=$val_devolucao ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Motivo</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_OBSERVA" id="DES_OBSERVA" value="<?=$des_observa ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Dias após a reserva</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DIFERENCA" id="DIFERENCA" value="<?=$diferenca ?>">
													</div>
												</div>

											</div>


											<div class="row">
												<div class="col-xs-4">
													<div class="form-group">
														<label for="inputName" class="control-label ">Forma de Estorno</label>
														<select data-placeholder="Selecione uma opção" name="COD_TIPDEVO" id="COD_TIPDEVO" class="chosen-select-deselect" required <?=$disableFormEstor?>>
															<option value=""></option>
															<?php
															$sqlHotel = "SELECT * FROM ADORAI_TIPO_DEVOLUCAO";

															$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);
															while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
																if($diferenca > 7 && $qrHotel['cod_tipdeve'] == 3){
																	$selected = "selected";
																}else{
																	$selected = "";
																}
																?>
																<option value="<?=$qrHotel['cod_tipdeve']?>" <?=$selected?>><?=$qrHotel['des_tipdeve']?></option>
																<?php 
															}
															?>
														</select>
														<div class="help-block with-errors"></div>
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Descontos</label>
														<select data-placeholder="Selecione a tarifa" name="TIP_TARIFA" id="TIP_TARIFA" class="chosen-select-deselect">
															<option value="" >&nbsp;</option>
															<?php
															$sql = "SELECT * FROM ADORAI_TARIFA";

															$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
															while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
																echo "
																<option value='" . $qrListaEstCivil['tip_tarifa'] . "'>" . $qrListaEstCivil['des_tarifa'] . "</option> 
																";
															}
															?>
														</select>
														<div class="help-block with-errors"></div>
														<script>
															$("#TIP_TARIFA").val('<?=$tip_tarifa?>').trigger("chosen:updated");
														</script>
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Valor de Desconto</label>
														<div class="push5"></div>
														<input type="text" class="form-control input-sm money" name="VAL_TARIFA" id="VAL_TARIFA" value="<?=$val_tarifa?>" maxlenght="100">
													</div>														
												</div>
											</div>

											<div class="row">

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Total Reembolso</label>
														<div class="push5"></div>
														<input type="text" class="form-control input-sm money" readonly="readonly" name="TOT_REEMBOLSO" id="TOT_REEMBOLSO" value="<?php echo $val_devolucao; ?>" maxlenght="100">
													</div>														
												</div>
												
											</div>


										</fieldset>

										<div class="push10"></div>
										<hr>
										<div class="form-group text-right col-lg-12">

											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn">&nbsp; Confirmar Cancelamento</button>

										</div>

										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
										<input type="hidden" name="TIP_PAGAMEN" id="TIP_PAGAMEN" value="<?=$tip_pagamen ?>">
										<input type="hidden" name="DATA_ESTORNO" id="DATA_ESTORNO" value="<?=$data_estorno ?>">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="ID_RESERVA" id="ID_RESERVA" value="<?php echo $id_reserva; ?>" />
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

										<div class="push5"></div>

									</form>

									<div class="push"></div>

								</div>

							</div>
						</div>
						<!-- fim Portlet -->
					</div>

				</div>

				<div class="push20"></div>

				<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
				<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
				<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
				<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

				<script type="text/javascript">

					$('#VAL_TARIFA').on('change', function(){
						var tarifa = $(this).val();
						var pago = $("#VAL_DEVOLUCAO").val();

						tarifa = tarifa.replace(/\./g, '').replace(/,/g, '.');
						pago = pago.replace(/\./g, '').replace(/,/g, '.');

						if(!isNaN(tarifa) && !isNaN(pago)){
							var result = parseFloat(pago) - parseFloat(tarifa);
							var valorFormatado = result.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

							$('#TOT_REEMBOLSO').val(valorFormatado);
						}
					});


					$('#COD_TIPDEVO').change(function(){
						var des_tipdeve = $(this).find('option:selected').text();
						$('#TIP_PAGAMEN').val(des_tipdeve);
					});


				</script>