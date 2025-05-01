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
		$cod_lote = fnLimpaCampoZero($_REQUEST['COD_LOTE']);

		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$cod_indicad = fnLimpaCampoZero($_REQUEST['COD_INDICAD']);

		$contratos_cliente = fnLimpaCampo($_REQUEST['CONTRATOS_CLIENTE']);

		$contratos_cliente = explode(",", $contratos_cliente);

		// echo "<pre>";
		// print_r($clientes_contrato);
		// echo "</pre>";
		
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


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

<style>

	#impressaoContratos{
		display: none;
	}

	@media print {
		#impressaoContratos{
			display: block;
		}
		.assinatura {
			text-align: center;
			line-height:20px;
			color:red;
		}

		.quebra {
			
		}
	}

</style>

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
											<label for="inputName" class="control-label">Lote</label>
												<select data-placeholder="Selecione o lote" name="COD_LOTE" id="COD_LOTE" class="chosen-select-deselect">
													<option value=""></option>					
													<?php 																	
														$sql = "SELECT CL.COD_LOTE,
																	   CL.QTD_LOTE, 
																	   UV.NOM_FANTASI, 
																	   PP.DES_PROFISS 
																FROM CONTRATO_LOTE CL
																INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND
																INNER JOIN PROFISSOES_PREF PP ON PP.COD_PROFISS = CL.COD_PROFISS
																WHERE CL.COD_EMPRESA = $cod_empresa 
																ORDER BY UV.NOM_FANTASI ";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
													
														while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrListaProfi['COD_LOTE']."'>".$qrListaProfi['COD_LOTE']." - ".$qrListaProfi['DES_PROFISS']." (".$qrListaProfi['QTD_LOTE']." pessoas)</option> 
																"; 
															  }											
													?>	
												</select>	
                                                <script>$("#formulario #COD_LOTE").val("<?php echo $cod_lote; ?>").trigger("chosen:updated"); </script>                                                       
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Assessor</label>
												<select data-placeholder="Selecione o assessor" name="COD_INDICAD" id="COD_INDICAD" class="chosen-select-deselect">
													<option value=""></option>					
													<?php 																	
														$sql = "SELECT COD_CLIENTE,NOM_CLIENTE from clientes 
																WHERE COD_CLIENTE IN(
																SELECT DISTINCT  COD_INDICAD FROM CLIENTES
																WHERE COD_EMPRESA=$cod_empresa 
																AND COD_CLIENTE!=29007 
																AND COD_INDICAD IS NOT NULL)
																AND COD_EMPRESA=$cod_empresa
																AND COD_CLIENTE!=29007";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
													
														while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
														  {														
															echo"
																  <option value='".$qrListaProfi['COD_CLIENTE']."'>".$qrListaProfi['NOM_CLIENTE']."</option> 
																"; 
															  }											
													?>	
												</select>	
                                                <script>$("#formulario #COD_INDICAD").val("<?php echo $cod_indicad; ?>").trigger("chosen:updated"); </script>                                                       
											<div class="help-block with-errors"></div>
										</div>
									</div>

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

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Filtrar Contratos</button>
								<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

							<div class="push10"></div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="CONTRATOS_CLIENTE" id="CONTRATOS_CLIENTE" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						</form>

						<div class="push30"></div>

						<div class="col-lg-12">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th class="text-center {sorter:false}" width="40"><small>Todos</small><br><input type='checkbox' id="selectAll" onclick="selectAll(this)"></th>
										<th>Código</th>
										<th>Cod. Externo</th>
										<th>Nome</th>
										<th>CPF/CNPJ</th>
										<th>Vl. Contrato</th>
										<th>Tipo</th>
										<th>Nro. Impressões</th>
										<th>Unidade</th>
									</tr>
								</thead>
								<tbody>

									<?php

									$orLote = "";
									$andIndicad = "";


									if($cod_lote != 0){
										$orLote = "OR A.NUM_LOTE = $cod_lote";
									}

									if($cod_indicad != 0){
										$andIndicad = "AND (B.COD_INDICAD = $cod_indicad $orLote)";
									}else{
										if($cod_lote != 0){
											$orLote = "";
											$andIndicad = "AND A.NUM_LOTE = $cod_lote";
										}
									}

									$sql = "SELECT B.COD_CLIENTE,
												   B.LOG_JURIDICO,
											       IFNULL(B.COD_EXTERNO,'') AS COD_EXTERNO,
											       B.NOM_CLIENTE,
											       B.NUM_CGCECPF,
											       A.COD_CONTRAT,
											       A.VAL_CONTRAT, 
												   A.TIP_CONTRAT,
												   A.NUM_IMPRESSAO,
												   C.NOM_FANTASI
											FROM CONTRATO_ELEITORAL A
											INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE AND B.COD_UNIVEND = A.COD_UNIVEND 
											INNER JOIN WEBTOOLS.UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND 
											WHERE B.LOG_TERMO = 'N' 
											AND A.COD_EMPRESA = $cod_empresa 
											AND A.COD_UNIVEND = $cod_univend 
											AND A.COD_EXCLUSA = 0 
											$andIndicad";

									// fnEscreve($sql);
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

									$count = 0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

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

										$letraPessoa = "F";

										if($qrBuscaModulos[LOG_JURIDICO] == "S"){
											$letraPessoa = "J";
										}

									?>

										<tr>
											<td class='text-center'><input type='checkbox' name='radio_<?=$count?>' onclick='attListaContratos()'>&nbsp;</td>
											<td><?=$qrBuscaModulos['COD_CLIENTE']?></td>
											<td><?=$qrBuscaModulos['COD_EXTERNO']?></td>
											<td><?=$qrBuscaModulos['NOM_CLIENTE']?></td>
											<td class="cpfcnpj"><?=fnCompletaDoc($qrBuscaModulos['NUM_CGCECPF'],"$letraPessoa")?></td>
											<td><?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?></td>
											<td><?=$tipoContrato?></td>
											<td><?=$qrBuscaModulos['NUM_IMPRESSAO']?></td>
											<td><?=$qrBuscaModulos['NOM_FANTASI']?></td>
										</tr>

										<input type="hidden" id="ret_COD_CONTRAT_<?=$count?>" value="<?=$qrBuscaModulos['COD_CONTRAT']?>">

									<?php 
									}

									?>

								</tbody>
							</table>

							<div id="impressaoContratos"></div>

							<hr>
							<div class="form-group text-right col-lg-12">

								<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
								<a href="javascript:void(0)" name="CAD" id="CAD" class="btn btn-info" onclick="imprimirContratos()"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir contratos selecionados</a>
								<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

							</div>

						</div>

						<div class="push50"></div>

					</div>

				</div><!-- fim Portlet -->
			</div>
		</div>
	</div>
</div>
			

		

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

<script src='js/printThis.js'></script>

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

		// $("#print").click(function() {
		// 	if ($("#COD_TPCONTRAT").val() == "1") {
		// 		let impressao = document.getElementById("impressao1").innerHTML;
		// 	} else {
		// 		let impressao = document.getElementById("impressao2").innerHTML;
		// 	}
		// 	let a = window.open('', '', 'height=3508, widht=2480');
		// 	a.document.write('<html>');
		// 	a.document.write('<body>');
		// 	a.document.write(impressao);
		// 	a.document.write('</body></html>');
		// 	a.document.close();
		// 	a.print();
		// });

	});

	function selectAll(el) {
		$(el).closest('table').find('td input:checkbox').prop('checked', el.checked);
		attListaContratos();
	}

	function mostraVeiculos(el) {
		if($(el).val() == 5){
			$("#div_veiculos").attr('required',true).fadeIn('fast');
		}else{
			$("#div_veiculos").removeAttr('required').fadeOut('fast');
		}
		$('#formulario').validator('destroy').validator();
	}

	function attListaContratos(index){

		listaContratos = [];

		$("table tr").each(function(index) {

			if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){

				var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
				listaContratos.push($("#ret_COD_CONTRAT_"+index).val());

			}

		});

		console.log(listaContratos);

		$("#CONTRATOS_CLIENTE").val(listaContratos);

	}

	function imprimirContratos() {
		if($("#CONTRATOS_CLIENTE").val() != ""){
			$.ajax({
				method: "POST",
				url: 'ajxImpressaoContratoLote.php?id=<?=fnEncode($cod_empresa)?>',
				data: $("#formulario").serialize(),
				success:function(data){
					console.log(data); 
					$('#impressaoContratos').html(data).printThis();
					
				}
			});
		}else{
			$.alert({
				title: "Aviso",
				content: "Nenhum contrato selecionado.",
				type: 'red'
			});
		}
	}

</script>