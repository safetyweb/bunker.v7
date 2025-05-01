<?php
	//echo fnDebug('true');

$hashLocal = mt_rand();	
$hoje = date("Y-m-d");

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

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$id_voucher = fnLimpaCampoZero($_REQUEST['ID_VOUCHER']);
		$cod_pedido = fnLimpaCampoZero($_REQUEST['COD_PEDIDO']);
		$vl_voucher = fnLimpaCampo(fnValorSql($_REQUEST['VL_VOUCHER']));
		$dat_limite = date("Y-m-d", strtotime("$hoje +10 days"));
		$tip_tarifa = fnLimpaCampoZero($_REQUEST['TIP_TARIFA']);
		$val_tarifa = fnLimpaCampoZero(fnValorSql($_REQUEST['VAL_TARIFA']));
		$tot_reembolso = fnLimpaCampo(fnValorSql($_REQUEST['TOT_REEMBOLSO']));

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//fnEscreve($cod_empresa);

		if ($opcao != ''){	

				//mensagem de retorno
			switch ($opcao)
			{
				case 'ALT':

				$sql = "UPDATE ADORAI_VOUCHER SET
				LOG_STATUS = 'P'
				WHERE ID_VOUCHER = $id_voucher AND COD_EMPRESA = $cod_empresa";

				mysqli_query(connTemp($cod_empresa,''),$sql);


				$sqlInsert ="INSERT INTO CAIXA(
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
					$cod_usucada
				)";	

					//fnEscreve($sqlInsert);
					mysqli_query(connTemp($cod_empresa,''),$sqlInsert);


					$sqlInsert2 ="INSERT INTO ADORAI_DEVOLUCOES(
						COD_EMPRESA,
						COD_PEDIDO,
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
						1,
						$tot_reembolso,
						'$dat_limite',
						0,
						'Pix',
						$cod_usucada,
						$tip_tarifa,
						$val_tarifa,
						NOW()
					)";

						//fnEscreve($sqlInsert2);
						mysqli_query(connTemp($cod_empresa,''),$sqlInsert2);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';	

						break;
					}			

				}  	

			}
		}

	//defaul - perfil

	//busca dados da url	
		if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
			$cod_empresa = fnDecode($_GET['id']);	
			$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);                     

		}else {
			$cod_empresa = 274;
		}

		if(is_numeric(fnLimpacampo(fnDecode($_GET['vch'])))){

			$id_voucher = fnLimpaCampo(fnDecode($_GET['vch']));

			$sql = "SELECT * FROM ADORAI_VOUCHER
			WHERE COD_EMPRESA = $cod_empresa
			AND ID_VOUCHER = $id_voucher";

			$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$qrBusca = mysqli_fetch_assoc($query);

			$des_voucher = $qrBusca['DES_VOUCHER'];
			$id_voucher = $qrBusca['ID_VOUCHER'];
			$vl_voucher = $qrBusca['VL_VOUCHER'];
			$cod_pedido = $qrBusca['COD_PEDIDO'];

		}else{
			$des_voucher = "";
			$id_voucher = "";
			$vl_voucher = "";	
			$cod_pedido = "";		
		}

		?>

		<?php if ($popUp != "true"){ ?>
			<div class="push30"></div> 
		<?php } ?>

		<div class="row">				

			<div class="col-md12 margin-bottom-30">
				<!-- Portlet -->
				<?php if ($popUp != "true"){  ?>							
					<div class="portlet portlet-bordered">
					<?php } else { ?>
						<div class="portlet" style="padding: 0 20px 20px 20px;" >
						<?php } ?>

						<?php if ($popUp != "true"){  ?>
							<div class="portlet-title">
								<div class="caption">
									<i class="glyphicon glyphicon-calendar"></i>
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

							<div class="login-form">

								<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

									<fieldset>
										<legend>Dados Gerais</legend> 

										<div class="row">
											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Código Voucher</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID_VOUCHER" id="ID_VOUCHER" value="<?php echo $id_voucher; ?>">
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Código Reserva</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PEDIDO" id="COD_PEDIDO" value="<?php echo $cod_pedido; ?>">
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Forma de devolução</label>
													<div class="push5"></div>
													<input type="text" class="form-control input-sm" readonly="readonly" name="TIP_PAGAMEN" id="TIP_PAGAMEN" value="PIX" maxlenght="100">
												</div>														
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Valor</label>
													<div class="push5"></div>
													<input type="text" class="form-control input-sm" readonly="readonly" name="VL_VOUCHER" id="VL_VOUCHER" value="<?php echo fnValor($vl_voucher,2); ?>" maxlenght="100">
												</div>														
											</div>

										</div>

										<div class="row">
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

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Total Reembolso</label>
													<div class="push5"></div>
													<input type="text" class="form-control input-sm money" readonly="readonly" name="TOT_REEMBOLSO" id="TOT_REEMBOLSO" value="<?php echo fnValor($vl_voucher,2); ?>" maxlenght="100">
												</div>														
											</div>
										</div>

										<div class="push10"></div>		

									</fieldset>										

									<div class="push10"></div>
									<hr>
									<div class="form-group text-right col-md-12">
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Confirmar Pagamento</button>
									</div>

									<input type="hidden" name="opcao" id="opcao" value="">
									<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	

									<div class="push5"></div> 

								</form>

								<div class="push50"></div>

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
			    var pago = $("#VL_VOUCHER").val();
			    
			    tarifa = tarifa.replace(/\./g, '').replace(/,/g, '.');
			    pago = pago.replace(/\./g, '').replace(/,/g, '.');

			    if(!isNaN(tarifa) && !isNaN(pago)){
			        var result = parseFloat(pago) - parseFloat(tarifa);
			        var valorFormatado = result.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
			        
			        $('#TOT_REEMBOLSO').val(valorFormatado);
			    }
			});

			</script>	