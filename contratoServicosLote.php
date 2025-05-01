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

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_veiculo = fnLimpaCampoZero($_REQUEST['COD_VEICULO']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$cod_profiss = fnLimpaCampoZero($_REQUEST['COD_PROFISS']);
		$tip_contrat = fnLimpaCampoZero($_REQUEST['COD_TPCONTRAT']);
		$tip_pagamen = fnLimpaCampoZero($_REQUEST['TIP_PAGAMEN']);
		$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
		$val_contrat = fnValorSql($_REQUEST['VAL_CONTRAT']);
		$cod_formapa = fnLimpaCampo($_REQUEST['COD_FORMAPA']);

		$clientes_contrato = fnLimpaCampo($_REQUEST['CLIENTES_CONTRATO']);

		$clientes_contrato = explode(",", $clientes_contrato);

		// echo "<pre>";
		// print_r($clientes_contrato);
		// echo "</pre>";
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					if(count($clientes_contrato) > 0){

						$sqlLote = "INSERT INTO CONTRATO_LOTE(
													COD_EMPRESA,
													COD_UNIVEND,
													COD_PROFISS,
													COD_USUCADA
												) VALUES(
													$cod_empresa,
													$cod_univend,
													$cod_profiss,
													$cod_usucada
												)";

						mysqli_query(conntemp($cod_empresa, ''), $sqlLote);

						$sqlBuscaLote = "SELECT * FROM CONTRATO_LOTE 
										 WHERE COD_EMPRESA = $cod_empresa
										 AND COD_UNIVEND = $cod_univend
										 AND COD_PROFISS = $cod_profiss
										 AND COD_USUCADA = $cod_usucada";

						$arrayLote = mysqli_query(conntemp($cod_empresa, ''), $sqlBuscaLote);

						$qrLote = mysqli_fetch_assoc($arrayLote);

						$sqlContrato = "";
						$sqlCaixa = "";
						$cod_lote = $qrLote[COD_LOTE];

						foreach ($clientes_contrato as $cod_cliente) {

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
													COD_USUCADA,
													NUM_LOTE
												) VALUES (
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
													$cod_usucada,
													$cod_lote
												)";

							// fnEscreve($sql);

							$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sql);

							$sqlContrato = "SELECT COD_CONTRAT 
											FROM CONTRATO_ELEITORAL
											WHERE COD_EMPRESA = $cod_empresa
											AND COD_CLIENTE = $cod_cliente
											AND COD_USUCADA = $cod_usucada";

							// fnEscreve($sqlContrato);

							$arrayContrato = mysqli_query(conntemp($cod_empresa, ''), $sqlContrato);

							$qrContrato = mysqli_fetch_assoc($arrayContrato);

							$sql2 = "INSERT INTO CAIXA(
												COD_EMPRESA,
												COD_CONTRAT,
												COD_CLIENTE,
												DAT_LANCAME,
												COD_TIPO,
												VAL_CREDITO,
												TIP_LANCAME,
												COD_USUCADA,
												NUM_DIA
											) VALUES (
												$cod_empresa,
												$qrContrato[COD_CONTRAT],
												$cod_cliente,
												NOW(),
												99,
												'$val_contrat',
												'C',
												$cod_usucada,
												$tip_pagamen
											 )";

							// fnEscreve($sql2);

							$arrayProc2 = mysqli_query(conntemp($cod_empresa, ''), $sql2);

						}

						$sqlCount = "SELECT 1 FROM CONTRATO_ELEITORAL 
									 WHERE COD_EMPRESA = $cod_empresa
									 AND NUM_LOTE = $cod_lote";

						$arrCount = mysqli_query(conntemp($cod_empresa, ''), $sqlCount);

						$qtd_lote = mysqli_num_rows($arrCount);

						$sqlUpdate = "UPDATE CONTRATO_LOTE 
									  SET QTD_LOTE = $qtd_lote
									  WHERE COD_EMPRESA = $cod_empresa
									  AND COD_LOTE = $cod_lote";

						mysqli_query(conntemp($cod_empresa, ''), $sqlUpdate);
						
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong><br>
									   Lote gerado: $cod_lote ($qtd_lote pessoas)";	

					}


				break;
								
			}
			if (($cod_erro == 0 || $cod_erro == "") && count($clientes_contrato) > 0) {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}


		}

	}

}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
																						
						// include "abasClienteRH.php";

					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Filtros para geração dos contratos</legend>

								<div class="row">

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label required lbl_req">Campanha</label>
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

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cargo </label>
												<select data-placeholder="Selecione o cargo" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
													<option value=""></option>					
													<?php 																	
														$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES_PREF WHERE COD_EMPRESA = $cod_empresa order by DES_PROFISS ";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
													
														while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrListaProfi['COD_PROFISS']."'>".$qrListaProfi['DES_PROFISS']."</option> 
																"; 
															  }											
													?>	
												</select>	
                                                <script>$("#formulario #COD_PROFISS").val("<?php echo $cod_profiss; ?>").trigger("chosen:updated"); </script>                                                       
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
								<a href="javascript:void(0)" class="btn btn-primary" onclick="filtroApoiadores()"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar Apoiadores</a>
								<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

							<div class="push10"></div>

							<div id="contrato">

								
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="CLIENTES_CONTRATO" id="CLIENTES_CONTRATO" value="">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						</form>

						<div class="push50"></div>

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

	var listaProdutos = [];

	$(function(){

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

	function filtroApoiadores() {
		$.ajax({
			method: "POST",
			url: 'ajxContratoServicosLote.php?id=<?=fnEncode($cod_empresa)?>&acao=filtro',
			data: {COD_UNIVEND: $("#COD_UNIVEND").val(), COD_PROFISS: $("#COD_PROFISS").val()},
			beforeSend:function(){
				$('#contrato').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				console.log(data); 
				$('#contrato').html(data);
				
			},
			error:function(){
				$('#contrato').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

</script>