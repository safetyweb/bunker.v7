<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

$cod_empresa = fnDecode($_GET['id']);
$cod_pedido = fnLimpaCampoZero(fnDecode($_GET['idp']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$num_parcelas = fnLimpaCampoZero($_REQUEST['QTD_PARCELA']);
		$arrParcelas = array();
		for ($i=1; $i <= $num_parcelas; $i++) {
			$arrParcelas[$i] = array( 
									  "CODIGO" => fnLimpaCampoZero($_REQUEST['COD_PARCELA_'.$i]),
									  "VALOR" => fnValorSql($_REQUEST['VAL_PARCELA_'.$i]),
									  "VENCIMENTO" => fnDataSql($_REQUEST['DAT_INI_'.$i])
									);
		}
		

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$valuesCad = "";

		// echo "<pre>";
		// print_r($arrParcelas);
		// echo "</pre>";

		//mensagem de retorno
		switch ($opcao)
		{
			case 'CAD':

				foreach ($arrParcelas as $num_parcela => $parcela ) {
					$valuesCad .= "(
										$cod_empresa,
										$cod_pedido,
										'$num_parcela',
										'$parcela[VALOR]',
										'$parcela[VENCIMENTO]',
										$cod_usucada
									),";

				}

				if($valuesCad != ""){

					$sqlDelete = "DELETE FROM ADORAI_PARCELAS 
								  WHERE COD_EMPRESA = $cod_empresa 
								  AND COD_PEDIDO = $cod_pedido";

					mysqli_query(conntemp($cod_empresa,''), $sqlDelete);

					$valuesCad = rtrim(trim($valuesCad),',');

					$sqlCad = "INSERT INTO ADORAI_PARCELAS(
													COD_EMPRESA,
													COD_PEDIDO,
													NUM_PARCELA,
													VAL_PARCELA,
													DAT_VENCIMEN,
													COD_USUCADA
												)VALUES $valuesCad";
					

					// fnEscreve($sqlCad);

					$valuesCad = "";			
					$arrayProc = mysqli_query(conntemp($cod_empresa,''), $sqlCad);



					// if (!$arrayProc) {

					// 	$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad,$nom_usuario);
					// }

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Parcelas geradas com <strong>sucesso!</strong>";
					 	$msgTipo = 'alert-success';
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					 	$msgTipo = 'alert-danger';
					}

				}

			break;
			case 'ALT':

				foreach ($arrParcelas as $num_parcela => $parcela ) {
					$sqlCad = "UPDATE ADORAI_PARCELAS SET
												VAL_PARCELA = '$parcela[VALOR]',
												DAT_VENCIMEN = '$parcela[VENCIMENTO]',
												COD_ALTERAC = $cod_ALTERAC
												DAT_USUCADA = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_PEDIDO = $cod_pedido
								AND COD_PARCELA = $parcela[CODIGO]";
			
					$arrayProc = mysqli_query(conntemp($cod_empresa,''), $sqlCad);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					 	$msgTipo = 'alert-success';
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					 	$msgTipo = 'alert-danger';
					}

				}

				$msgRetorno = "Parcelas alteradas com <strong>sucesso!</strong>";

			break;
			case 'EXC':

				$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

			break;
		}

	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$cod_empresa = 274;

//fnMostraForm();

?>

<style>

	.table-container td {
		padding: 8px;
	}

	.table-container tbody tr:last-child td {
		border-bottom: 1px solid #dddddd;
	}
	
	ul.summary-list {
		display: inline-block;
		padding-left:0 ;
		width: 100%;
		margin-bottom: 0;
	}

	ul.summary-list > li {
		display: inline-block;
		width: 19.5%;
		text-align: center;
	}

	ul.summary-list > li > a > i {
		display:block;
		font-size: 18px;
		padding-bottom: 5px;
	}

	ul.summary-list > li > a {
		padding: 10px 0;
		display: inline-block;
		color: #818181;
	}

	ul.summary-list > li  {
		border-right: 1px solid #eaeaea;
	}

	ul.summary-list > li:last-child  {
		border-right: none;
	}

</style>


<div class="row" style="padding: 0 20px 20px 20px;">				
	<div class="col-md12 margin-bottom-30">
		<div class="portlet">
			<div class="portlet-body">

				<?php 
				$abaAdorai = 2025;
				include "abasReservaAdorai.php"; 
				?>

				<div class="push20"></div>

				<?php

				include_once "headerAdoraiPedido.php";
				?>
						
						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12">
								<form id="parcelamento">
									<fieldset>
										<legend>Parcelamento</legend>

										<div class="row" style="display: inline-flex; align-content: space-between;">
											
											<div class="col-md-3 text-center" style="align-self: center;">
												<div class="form-group">
													<span><small>R$</small> <span class="f18b"><?=fnValor($valor_a_pagar,2)?></span> em </span>
												</div>
											</div>
											
											<div class="col-md-2">
												<div class="form-group">
													<input type="text" class="form-control input-sm text-center int" name="NUM_PARCELAS" id="NUM_PARCELAS" maxlength="2" value="">
													<div class="help-block with-errors">qtd. de parcelas</div>
												</div>
											</div>
											
											<div class="col-md-3 text-center" style="align-self: center;">
												<span>parcelas, começando em</span>
											</div>

											<div class="col-md-4">
												<div class="form-group">

													<div class="input-group date datePicker" id="DAT_INI_GRP">
														<input type='text' class="form-control input-sm data text-center" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
													<div class="help-block with-errors"></div>
												</div>
											</div>
											
											<div class="col-md-2">
												<a href="javascript:void(0)" class='btn btn-info' onclick='simularParcelas()'>Simular Parcelas</a>
											</div>

										</div>

									</fieldset>
									<input type="hidden" name="VAL_A_PAGAR" id="VAL_A_PAGAR" value="<?=$valor_a_pagar?>">
								</form>
							</div>
						</div>

						<div class="row justify-content-end">
						</div>

						<form data-toggle="validator" role="form2" method="post" id="parcelamentoFinal" action="<?php echo $cmdPage; ?>">
							<div class="row" id="parcelamentoSimulado">

								<?php

									$sqlParcelas = "SELECT * FROM ADORAI_PARCELAS 
													WHERE COD_PEDIDO = $cod_pedido 
													AND COD_EMPRESA = $cod_empresa
													ORDER BY NUM_PARCELA";
									$arrParcelas = mysqli_query(connTemp($cod_empresa,''),$sqlParcelas);

									$num_parcelas = mysqli_num_rows($arrParcelas);

									if($num_parcelas > 0){
								?>
										<div class="push10"></div>
										<div class="col-md-12">
											<h4>Parcelamento salvo:</h4>
										</div>
								<?php 

									}

									while($qrParcela = mysqli_fetch_assoc($arrParcelas)) 
									{
									
									$totParcelas += $qrParcela[VAL_PARCELA];	

								?>

									<div class="col-md-12">

										<div class="row" style="display: inline-flex; align-content: space-between;">
											
											<div class="col-md-4 text-left" style="align-self: center;">
												<div class="form-group">
													<span><?=$qrParcela[NUM_PARCELA]?>ª parcela <small>R$</small> <b><?=fnValor($qrParcela[VAL_PARCELA],2)?></b></span>
												</div>
											</div>
											
											<div class="col-md-3" style="align-self: center; text-align: center;">
												<span>vencimento em</span>
											</div>

											<div class="col-md-5">
												<div class="form-group">

													<div class="input-group date datePicker" id="DAT_INI_GRP_<?=$qrParcela[NUM_PARCELA]?>">
														<input type='text' class="form-control input-sm data text-center" name="DAT_INI_<?=$qrParcela[NUM_PARCELA]?>" id="DAT_INI_<?=$qrParcela[NUM_PARCELA]?>" value="" required />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

										<input type="hidden" name="VAL_PARCELA_<?=$qrParcela[NUM_PARCELA]?>" val="VAL_PARCELA_<?=$qrParcela[NUM_PARCELA]?>" value="<?=fnValor($qrParcela[VAL_PARCELA],2)?>">
										<input type="hidden" name="COD_PARCELA_<?=$qrParcela[NUM_PARCELA]?>" val="COD_PARCELA_<?=$qrParcela[NUM_PARCELA]?>" value="<?=$qrParcela[COD_PARCELA]?>">
										
									</div>

									<script type="text/javascript">
										$(function(){
											$("#DAT_INI_<?=$qrParcela[NUM_PARCELA]?>").val("<?=fnDataShort($qrParcela[DAT_VENCIMEN])?>");
										});
									</script>

								<?php
								
									}
								?>	
								
								
									<div class="col-md-12">

										<div class="row">
											
											<div class="col-md-2 text-left">
												<div class="form-group">
													<span>Total <small>R$</small> <b class="f18b"><?=fnValor($totParcelas,2)?></b></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</div>
											</div>
											
											<div class="col-md-10 text-left"></div>
										
									</div>
								
								
								<?php
									if($num_parcelas > 0){
								?> 
								
										<div class="col-md-4 text-right" style="padding-right: 30px;">
											<div class="push20"></div>
											<!-- <a href="javascript:void(0)" class='btn btn-primary' onclick='document.getElementById("parcelamentoFinal").submit();'>Salvar parcelamento</a> -->
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn">Alterar parcelamento</button>
										</div>
										<input type="hidden" name="QTD_PARCELA" val="QTD_PARCELA" value="<?=$num_parcelas?>">
										<input type="hidden" name="opcao" val="opcao" value="ALT">

										<script type="text/javascript">
											$(function(){
												$('.datePicker').datetimepicker({
													format: 'DD/MM/YYYY'
												}).on('changeDate', function(e) {
													$(this).datetimepicker('hide');
												});
											});
										</script>
										
								<?php

									}

								?>

							</div>
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						</form>


					</div>
				</div>
			</div>
		</div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(function(){
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});
	});


	function simularParcelas(){
		$.ajax({
			type: "POST",
			url: "ajxSimulaParcela.php",
			data: $("#parcelamento").serialize(),
			beforeSend:function(){
				$('#parcelamentoSimulado').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#parcelamentoSimulado").html(data);
			},
			error:function(data){
				$('#parcelamentoSimulado').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}
</script>
